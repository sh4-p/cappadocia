<?php
/**
 * Admin Bookings Controller
 * 
 * Handles bookings management in admin panel
 */
class AdminBookingsController extends Controller
{
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Require login
        $this->requireLogin();
    }
    
    /**
     * Get controller name
     * 
     * @return string Controller name
     */
    public function getControllerName()
    {
        return 'AdminBookings';
    }
    
    /**
     * Get action name
     * 
     * @return string Action name
     */
    public function getActionName()
    {
        $action = '';
        
        if (isset($_GET['url'])) {
            $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
            
            if (count($url) >= 2) {
                $action = $url[1];
            }
        }
        
        return $action ?: 'index';
    }
    
    /**
     * Index action - list all bookings
     */
    public function index()
    {
        // Load booking model
        $bookingModel = $this->loadModel('Booking');
        
        // Get bookings
        $bookings = $bookingModel->getAllWithTourDetails();
        
        // Count bookings by status
        $totalBookings = $bookingModel->countBookings();
        $confirmedBookings = $bookingModel->countBookings(['status' => 'confirmed']);
        $pendingBookings = $bookingModel->countBookings(['status' => 'pending']);
        $cancelledBookings = $bookingModel->countBookings(['status' => 'cancelled']);
        
        // Calculate total revenue
        $totalRevenue = 0;
        foreach ($bookings as $booking) {
            if ($booking['status'] != 'cancelled') {
                $totalRevenue += $booking['total_price'];
            }
        }
        
        // Prepare bookings chart data
        $bookingsChartData = [
            'labels' => [],
            'values' => []
        ];
        
        // Get last 30 days bookings
        $salesData = $bookingModel->getSalesData(30);
        foreach ($salesData as $data) {
            $bookingsChartData['labels'][] = date('d M', strtotime($data['date']));
            $bookingsChartData['values'][] = $data['total'] > 0 ? 1 : 0; // Just count if there was a booking on this day
        }
        
        // Render view
        $this->render('admin/bookings/index', [
            'pageTitle' => __('bookings'),
            'bookings' => $bookings,
            'totalBookings' => $totalBookings,
            'confirmedBookings' => $confirmedBookings,
            'pendingBookings' => $pendingBookings,
            'cancelledBookings' => $cancelledBookings,
            'totalRevenue' => $totalRevenue,
            'bookingsChartData' => $bookingsChartData
        ], 'admin');
    }
    
    /**
     * View booking details
     * 
     * @param int $id Booking ID
     */
    public function view($id)
    {
        // Load booking model
        $bookingModel = $this->loadModel('Booking');
        
        // Get booking with tour details
        $booking = $bookingModel->getWithTourDetails($id, $this->language->getCurrentLanguage());
        
        // Check if booking exists
        if (!$booking) {
            $this->session->setFlash('error', __('booking_not_found'));
            $this->redirect('admin/bookings');
        }
        
        // Render view
        $this->render('admin/bookings/view', [
            'pageTitle' => __('view_booking'),
            'booking' => $booking
        ], 'admin');
    }
    
    /**
     * Update booking status
     * 
     * @param int $id Booking ID
     * @param string $status New status
     */
    public function status($id, $status)
    {
        // Check if status is valid
        if (!in_array($status, ['pending', 'confirmed', 'cancelled'])) {
            $this->session->setFlash('error', __('invalid_status'));
            $this->redirect('admin/bookings');
        }
        
        // Load booking model
        $bookingModel = $this->loadModel('Booking');
        
        // Get booking with tour details
        $langCode = $this->language->getCurrentLanguage();
        $booking = $bookingModel->getWithTourDetails($id, $langCode);
        
        // Check if booking exists
        if (!$booking) {
            $this->session->setFlash('error', __('booking_not_found'));
            $this->redirect('admin/bookings');
        }
        
        // Store old status for email notification
        $oldStatus = $booking['status'];
        
        // Update status
        $result = $bookingModel->updateStatus($id, $status);
        
        if ($result) {
            // Send email notification if status changed to confirmed or cancelled
            if ($oldStatus !== $status && in_array($status, ['confirmed', 'cancelled'])) {
                $this->sendStatusChangeEmail($booking, $status);
            }
            
            $this->session->setFlash('success', __('status_updated'));
        } else {
            $this->session->setFlash('error', __('status_update_failed'));
        }
        
        // Redirect back
        $this->redirect('admin/bookings');
    }

    /**
     * Send status change email notification
     * 
     * @param array $booking Booking data
     * @param string $newStatus New status
     */
    private function sendStatusChangeEmail($booking, $newStatus)
    {
        try {
            // Load Email class
            require_once BASE_PATH . '/core/Email.php';
            $email = new Email();
            
            // Load settings
            $settingsModel = $this->loadModel('Settings');
            $settings = $settingsModel->getAllSettings();
            
            // Check if booking status notifications are enabled
            if (!isset($settings['email_notification_booking_status']) || $settings['email_notification_booking_status'] != '1') {
                return; // Notifications disabled
            }
            
            // Get currency symbol
            $currencySymbol = $settings['currency_symbol'] ?? '€';
            
            // Prepare email variables
            $variables = [
                'first_name' => $booking['first_name'],
                'last_name' => $booking['last_name'],
                'tour_name' => $booking['tour_name'],
                'booking_date' => date('l, F j, Y', strtotime($booking['booking_date'])),
                'adults' => $booking['adults'],
                'children' => $booking['children'],
                'total_price' => $currencySymbol . number_format($booking['total_price'], 2),
                'status' => ucfirst($newStatus),
                'booking_id' => $booking['id']
            ];
            
            // Choose appropriate template key
            $templateKey = 'booking_status_' . $newStatus;
            
            // Send email
            $result = $email->sendTemplate($templateKey, $booking['email'], $variables);
            
            if ($result) {
                error_log("Booking #{$booking['id']}: Status change email sent to {$booking['email']} (status: {$newStatus})");
            } else {
                error_log("Booking #{$booking['id']}: Failed to send status change email. Error: " . $email->getError());
            }
            
        } catch (Exception $e) {
            error_log("Booking status email error: " . $e->getMessage());
        }
    }
    
    /**
     * Export bookings to CSV
     */
    public function export()
    {
        // Load booking model
        $bookingModel = $this->loadModel('Booking');
        
        // Get all bookings
        $bookings = $bookingModel->getAllWithTourDetails();
        
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="bookings_' . date('Y-m-d') . '.csv"');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Add headers
        fputcsv($output, [
            'ID',
            __('tour'),
            __('customer_name'),
            __('email'),
            __('phone'),
            __('booking_date'),
            __('adults'),
            __('children'),
            __('total_price'),
            __('payment_method'),
            __('status'),
            __('created_at')
        ]);
        
        // Add bookings
        foreach ($bookings as $booking) {
            // Format payment method
            $paymentMethod = '';
            switch ($booking['payment_method']) {
                case 'card':
                    $paymentMethod = __('credit_card');
                    break;
                case 'paypal':
                    $paymentMethod = __('paypal');
                    break;
                case 'bank':
                    $paymentMethod = __('bank_transfer');
                    break;
                case 'cash':
                    $paymentMethod = __('cash_payment');
                    break;
                default:
                    $paymentMethod = $booking['payment_method'];
            }
            
            fputcsv($output, [
                $booking['id'],
                $booking['tour_name'],
                $booking['first_name'] . ' ' . $booking['last_name'],
                $booking['email'],
                $booking['phone'],
                $booking['booking_date'],
                $booking['adults'],
                $booking['children'],
                $booking['total_price'],
                $paymentMethod,
                __($booking['status']),
                $booking['created_at']
            ]);
        }
        
        // Close output stream
        fclose($output);
        exit;
    }

    /**
     * List bookings by customer email
     * 
     * @param string $email Customer email
     */
    public function listByCustomer($email)
    {
        // URL decode email
        $email = urldecode($email);
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->setFlash('error', __('invalid_email'));
            $this->redirect('admin/bookings');
        }
        
        // Load booking model
        $bookingModel = $this->loadModel('Booking');
        
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get bookings by email
        $bookings = $bookingModel->getByEmail($email, $langCode);
        
        // Get customer statistics
        $totalBookings = count($bookings);
        $confirmedBookings = count(array_filter($bookings, function($booking) {
            return $booking['status'] === 'confirmed';
        }));
        $pendingBookings = count(array_filter($bookings, function($booking) {
            return $booking['status'] === 'pending';
        }));
        $cancelledBookings = count(array_filter($bookings, function($booking) {
            return $booking['status'] === 'cancelled';
        }));
        
        // Calculate total spent
        $totalSpent = 0;
        foreach ($bookings as $booking) {
            if ($booking['status'] !== 'cancelled') {
                $totalSpent += $booking['total_price'];
            }
        }
        
        // Get customer name from first booking
        $customerName = '';
        if (!empty($bookings)) {
            $customerName = $bookings[0]['first_name'] . ' ' . $bookings[0]['last_name'];
        }
        
        // Render view
        $this->render('admin/bookings/list-by-customer', [
            'pageTitle' => sprintf(__('bookings_by_customer'), $customerName),
            'bookings' => $bookings,
            'customerEmail' => $email,
            'customerName' => $customerName,
            'totalBookings' => $totalBookings,
            'confirmedBookings' => $confirmedBookings,
            'pendingBookings' => $pendingBookings,
            'cancelledBookings' => $cancelledBookings,
            'totalSpent' => $totalSpent
        ], 'admin');
    }
    /**
     * Create booking action - create a new booking
     */
    public function create()
    {
        // Load models
        $tourModel = $this->loadModel('Tour');
        $settingsModel = $this->loadModel('Settings');
        
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get active tours
        $tours = $tourModel->getActiveTours($langCode);
        
        // Get settings for payment methods
        $settings = $settingsModel->getAllSettings();
        $activePaymentMethods = $this->getActivePaymentMethods($settings);
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $tourId = $this->post('tour_id');
            $firstName = $this->post('first_name');
            $lastName = $this->post('last_name');
            $email = $this->post('email');
            $phone = $this->post('phone');
            $bookingDate = $this->post('booking_date');
            $adults = $this->post('adults', 1);
            $children = $this->post('children', 0);
            $totalPrice = $this->post('total_price');
            $specialRequests = $this->post('special_requests');
            $paymentMethod = $this->post('payment_method');
            $status = $this->post('status', 'pending');
            
            // Email notification options
            $sendCustomerEmail = $this->post('send_customer_email', 0);
            $sendAdminEmail = $this->post('send_admin_email', 0);
            $adminNotes = $this->post('admin_notes', '');
            
            // Validate inputs
            $errors = [];
            
            if (empty($tourId)) {
                $errors[] = __('tour_required');
            }
            
            if (empty($firstName)) {
                $errors[] = __('first_name_required');
            }
            
            if (empty($lastName)) {
                $errors[] = __('last_name_required');
            }
            
            if (empty($email)) {
                $errors[] = __('email_required');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = __('invalid_email');
            }
            
            if (empty($phone)) {
                $errors[] = __('phone_required');
            }
            
            if (empty($bookingDate)) {
                $errors[] = __('booking_date_required');
            }
            
            if ($adults < 1) {
                $errors[] = __('adults_required');
            }
            
            if ($totalPrice <= 0) {
                $errors[] = __('invalid_price');
            }
            
            if (empty($paymentMethod)) {
                $errors[] = __('payment_method_required');
            }
            
            if (!in_array($status, ['pending', 'confirmed', 'cancelled'])) {
                $errors[] = __('invalid_status');
            }
            
            // Get tour details for validation
            $tour = null;
            if ($tourId) {
                $tour = $tourModel->getWithDetails($tourId, $langCode);
                if (!$tour) {
                    $errors[] = __('invalid_tour');
                }
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Render view again with data
                $this->render('admin/bookings/create', [
                    'pageTitle' => __('add_booking'),
                    'tours' => $tours,
                    'activePaymentMethods' => $activePaymentMethods,
                    'formData' => [
                        'tour_id' => $tourId,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $email,
                        'phone' => $phone,
                        'booking_date' => $bookingDate,
                        'adults' => $adults,
                        'children' => $children,
                        'total_price' => $totalPrice,
                        'special_requests' => $specialRequests,
                        'payment_method' => $paymentMethod,
                        'status' => $status,
                        'send_customer_email' => $sendCustomerEmail,
                        'send_admin_email' => $sendAdminEmail,
                        'admin_notes' => $adminNotes
                    ]
                ], 'admin');
                
                return;
            }
            
            // Create booking data
            $bookingData = [
                'tour_id' => $tourId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'booking_date' => $bookingDate,
                'adults' => $adults,
                'children' => $children,
                'total_price' => $totalPrice,
                'special_requests' => $specialRequests,
                'payment_method' => $paymentMethod,
                'status' => $status,
                'notes' => $adminNotes, // Admin notes
                'created_by_admin' => 1 // Flag to indicate admin created
            ];
            
            // Load booking model
            $bookingModel = $this->loadModel('Booking');
            
            // Save booking
            $bookingId = $bookingModel->insert($bookingData);
            
            if ($bookingId) {
                // Send emails only if requested
                $emailResults = [];
                
                if ($sendCustomerEmail || $sendAdminEmail) {
                    $emailResults = $this->handleOptionalEmails($bookingId, $bookingData, $tour, $sendCustomerEmail, $sendAdminEmail);
                }
                
                // Prepare success message
                $successMessage = __('booking_added_successfully');
                
                // Add email status to success message
                if (!empty($emailResults)) {
                    $emailMessages = [];
                    if (isset($emailResults['customer'])) {
                        $emailMessages[] = $emailResults['customer'] ? __('customer_email_sent') : __('customer_email_failed');
                    }
                    if (isset($emailResults['admin'])) {
                        $emailMessages[] = $emailResults['admin'] ? __('admin_email_sent') : __('admin_email_failed');
                    }
                    
                    if (!empty($emailMessages)) {
                        $successMessage .= '<br>' . implode('<br>', $emailMessages);
                    }
                }
                
                $this->session->setFlash('success', $successMessage);
                $this->redirect('admin/bookings/view/' . $bookingId);
            } else {
                $this->session->setFlash('error', __('booking_add_failed'));
            }
        }
        
        // Render view
        $this->render('admin/bookings/create', [
            'pageTitle' => __('add_booking'),
            'tours' => $tours,
            'activePaymentMethods' => $activePaymentMethods
        ], 'admin');
    }
    
    /**
     * Handle optional email notifications for admin-created bookings
     * 
     * @param int $bookingId Booking ID
     * @param array $bookingData Booking data
     * @param array $tour Tour data
     * @param bool $sendCustomerEmail Send customer email
     * @param bool $sendAdminEmail Send admin email
     * @return array Email results
     */
    private function handleOptionalEmails($bookingId, $bookingData, $tour, $sendCustomerEmail, $sendAdminEmail)
    {
        $results = [];
        
        try {
            // Load Email class
            require_once BASE_PATH . '/core/Email.php';
            $email = new Email();
            
            // Load settings
            $settingsModel = $this->loadModel('Settings');
            $settings = $settingsModel->getAllSettings();
            
            // Send customer email if requested
            if ($sendCustomerEmail) {
                $results['customer'] = $this->sendCustomerConfirmationEmail($email, $bookingData, $tour, $settings);
                
                if ($results['customer']) {
                    error_log("Admin-created Booking #{$bookingId}: Customer email sent to {$bookingData['email']}");
                } else {
                    error_log("Admin-created Booking #{$bookingId}: Failed to send customer email. Error: " . $email->getError());
                }
            }
            
            // Send admin email if requested
            if ($sendAdminEmail) {
                $results['admin'] = $this->sendAdminNotificationEmail($email, $bookingData, $tour, $settings);
                
                if ($results['admin']) {
                    error_log("Admin-created Booking #{$bookingId}: Admin notification sent");
                } else {
                    error_log("Admin-created Booking #{$bookingId}: Failed to send admin notification. Error: " . $email->getError());
                }
            }
            
        } catch (Exception $e) {
            error_log("Admin-created Booking #{$bookingId}: Email error: " . $e->getMessage());
            
            // Mark failed emails
            if ($sendCustomerEmail) $results['customer'] = false;
            if ($sendAdminEmail) $results['admin'] = false;
        }
        
        return $results;
    }
    
    /**
     * Send confirmation email to customer (for admin-created bookings)
     * 
     * @param Email $email Email instance
     * @param array $bookingData Booking data
     * @param array $tour Tour data
     * @param array $settings Settings
     * @return bool Success
     */
    private function sendCustomerConfirmationEmail($email, $bookingData, $tour, $settings)
    {
        $currencySymbol = $settings['currency_symbol'] ?? '€';
        
        $emailBookingData = array_merge($bookingData, [
            'tour_name' => $tour['name'],
            'total_price_formatted' => $currencySymbol . number_format($bookingData['total_price'], 2),
            'booking_date_formatted' => date('l, F j, Y', strtotime($bookingData['booking_date'])),
            'payment_method_formatted' => $this->formatPaymentMethod($bookingData['payment_method'])
        ]);
        
        // Use appropriate template based on status
        if ($bookingData['status'] === 'confirmed') {
            return $email->sendBookingConfirmation($emailBookingData);
        } else {
            // For pending/cancelled, send a simple notification
            return $email->sendTemplate('booking_admin_created', $bookingData['email'], $emailBookingData);
        }
    }
    
    /**
     * Send notification email to admin (for admin-created bookings)
     * 
     * @param Email $email Email instance
     * @param array $bookingData Booking data
     * @param array $tour Tour data
     * @param array $settings Settings
     * @return bool Success
     */
    private function sendAdminNotificationEmail($email, $bookingData, $tour, $settings)
    {
        // Only send if admin notifications are enabled in settings
        if (!isset($settings['email_notification_booking']) || $settings['email_notification_booking'] != '1') {
            return true; // Not enabled, but don't consider this a failure
        }
        
        $currencySymbol = $settings['currency_symbol'] ?? '€';
        
        $emailBookingData = array_merge($bookingData, [
            'tour_name' => $tour['name'],
            'total_price_formatted' => $currencySymbol . number_format($bookingData['total_price'], 2),
            'booking_date_formatted' => date('l, F j, Y', strtotime($bookingData['booking_date'])),
            'payment_method_formatted' => $this->formatPaymentMethod($bookingData['payment_method']),
            'created_by' => 'Admin Panel' // Indicate it was created by admin
        ]);
        
        return $email->sendBookingAdminNotification($emailBookingData);
    }
    
    /**
     * Format payment method for display
     * 
     * @param string $paymentMethod Payment method
     * @return string Formatted payment method
     */
    private function formatPaymentMethod($paymentMethod)
    {
        switch ($paymentMethod) {
            case 'card':
                return __('credit_card');
            case 'paypal':
                return __('paypal');
            case 'bank':
                return __('bank_transfer');
            case 'cash':
                return __('cash_payment');
            default:
                return ucfirst($paymentMethod);
        }
    }
    
    /**
     * Get tour price via AJAX
     * 
     * @param int $id Tour ID
     */
    public function getTourPrice($id)
    {
        // Check if request is AJAX
        if (!$this->isAjax()) {
            $this->redirect('admin/bookings');
        }
        
        // Load tour model
        $tourModel = $this->loadModel('Tour');
        
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get tour details
        $tour = $tourModel->getWithDetails($id, $langCode);
        
        if ($tour) {
            $price = $tour['discount_price'] > 0 ? $tour['discount_price'] : $tour['price'];
            
            $this->json([
                'success' => true,
                'price' => floatval($price),
                'original_price' => floatval($tour['price']),
                'discount_price' => floatval($tour['discount_price']),
                'tour_name' => $tour['name']
            ]);
        } else {
            $this->json([
                'success' => false,
                'message' => __('tour_not_found')
            ]);
        }
    }
    
    /**
     * Get active payment methods from settings
     * 
     * @param array $settings Settings array
     * @return array Active payment methods
     */
    private function getActivePaymentMethods($settings)
    {
        $paymentMethods = [];
        
        // Check each payment method
        if (isset($settings['payment_card']) && $settings['payment_card'] == '1') {
            $paymentMethods['card'] = __('credit_card');
        }
        
        if (isset($settings['payment_paypal']) && $settings['payment_paypal'] == '1') {
            $paymentMethods['paypal'] = __('paypal');
        }
        
        if (isset($settings['payment_bank']) && $settings['payment_bank'] == '1') {
            $paymentMethods['bank'] = __('bank_transfer');
        }
        
        if (isset($settings['payment_cash']) && $settings['payment_cash'] == '1') {
            $paymentMethods['cash'] = __('cash_payment');
        }
        
        return $paymentMethods;
    }
    
}