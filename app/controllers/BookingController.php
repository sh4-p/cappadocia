<?php
/**
 * Booking Controller
 * 
 * Handles the booking process with email integration
 */
class BookingController extends Controller
{
    private $tourModel;
    private $bookingModel;
    private $settingsModel;
    
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Load models
        $this->tourModel = $this->loadModel('Tour');
        $this->bookingModel = $this->loadModel('Booking');
        $this->settingsModel = $this->loadModel('Settings');
    }
    
    /**
     * Index action - display booking form
     */
    public function index()
    {
        // Redirect to tours page
        $this->redirect('tours');
    }
    
    /**
     * Tour action - display booking form for a specific tour
     * 
     * @param int $id Tour ID
     */
    public function tour($id)
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get tour details
        $tour = $this->tourModel->getWithDetails($id, $langCode);
        
        // Check if tour exists
        if (!$tour) {
            // Redirect to tours page
            $this->redirect('tours');
        }
        
        // Get available dates (can be from a calendar or fixed dates)
        $availableDates = $this->getAvailableDates();
        
        // Get settings for payment methods and other configurations
        $settings = $this->settingsModel->getAllSettings();
        
        // Get active payment methods
        $activePaymentMethods = $this->getActivePaymentMethods($settings);
        
        // Check if at least one payment method is active
        if (empty($activePaymentMethods)) {
            // Set error message and redirect
            $this->session->setFlash('error', __('no_payment_methods_available'));
            $this->redirect('tours/' . $tour['slug']);
        }
        
        // Set page data
        $data = [
            'tour' => $tour,
            'availableDates' => $availableDates,
            'settings' => $settings,
            'activePaymentMethods' => $activePaymentMethods,
            'pageTitle' => sprintf(__('book_tour'), $tour['name']),
            'metaDescription' => sprintf(__('book_tour_description'), $tour['name']),
            'additionalCss' => [
                'booking.css'
            ],
            'additionalJs' => [
                'booking.js'
            ]
        ];
        
        // Render view
        $this->render('booking/form', $data);
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
            $paymentMethods['card'] = [
                'id' => 'card',
                'name' => __('credit_card'),
                'description' => __('credit_card_description'),
                'icon' => 'credit_card',
                'requires_fields' => true
            ];
        }
        
        if (isset($settings['payment_paypal']) && $settings['payment_paypal'] == '1') {
            $paymentMethods['paypal'] = [
                'id' => 'paypal',
                'name' => __('paypal'),
                'description' => __('paypal_description'),
                'icon' => 'account_balance_wallet',
                'requires_fields' => false
            ];
        }
        
        if (isset($settings['payment_bank']) && $settings['payment_bank'] == '1') {
            $paymentMethods['bank'] = [
                'id' => 'bank',
                'name' => __('bank_transfer'),
                'description' => __('bank_transfer_description'),
                'icon' => 'account_balance',
                'requires_fields' => false
            ];
        }
        
        if (isset($settings['payment_cash']) && $settings['payment_cash'] == '1') {
            $paymentMethods['cash'] = [
                'id' => 'cash',
                'name' => __('cash_payment'),
                'description' => __('cash_payment_description'),
                'icon' => 'money',
                'requires_fields' => false
            ];
        }
        
        return $paymentMethods;
    }
    
    /**
     * Confirm action - process booking form
     */
    public function confirm()
    {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('tours');
        }
        
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
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
        } elseif (strtotime($bookingDate) < strtotime(date('Y-m-d'))) {
            $errors[] = __('booking_date_past');
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
        
        // Get tour details
        $tour = $this->tourModel->getWithDetails($tourId, $langCode);
        
        // Check if tour exists
        if (!$tour) {
            $errors[] = __('invalid_tour');
        }
        
        // Validate payment method is active
        if ($tour) {
            $settings = $this->settingsModel->getAllSettings();
            $activePaymentMethods = $this->getActivePaymentMethods($settings);
            
            if (!isset($activePaymentMethods[$paymentMethod])) {
                $errors[] = __('invalid_payment_method');
            }
        }
        
        // If there are errors, redirect back to form
        if (!empty($errors)) {
            // Set error message
            $this->session->setFlash('error', implode('<br>', $errors));
            
            // Redirect to booking form
            $this->redirect('booking/tour/' . $tourId);
        }
        
        // Calculate total price (for verification)
        $price = $tour['discount_price'] > 0 ? $tour['discount_price'] : $tour['price'];
        $calculatedTotal = ($adults * $price) + ($children * $price * 0.5);
        
        // Round to 2 decimal places
        $calculatedTotal = round($calculatedTotal, 2);
        
        // Verify total price
        if (abs($calculatedTotal - $totalPrice) > 0.01) { // Allow small difference due to rounding
            // Set error message
            $this->session->setFlash('error', __('price_mismatch'));
            
            // Redirect to booking form
            $this->redirect('booking/tour/' . $tourId);
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
            'status' => 'pending'
        ];
        
        // Save booking
        $bookingId = $this->bookingModel->insert($bookingData);
        
        if ($bookingId) {
            // Set booking data in session for thank you page
            $this->session->set('booking_id', $bookingId);
            $this->session->set('booking_data', array_merge($bookingData, ['tour_name' => $tour['name']]));
            
            // Send confirmation emails
            $this->sendConfirmationEmails($bookingId, $bookingData, $tour);
            
            // Redirect to thank you page
            $this->redirect('booking/thank-you');
        } else {
            // Set error message
            $this->session->setFlash('error', __('booking_failed'));
            
            // Redirect to booking form
            $this->redirect('booking/tour/' . $tourId);
        }
    }
    
    /**
     * Thank you action - display thank you page
     */
    public function thankYou()
    {
        // Check if booking data exists in session
        $bookingId = $this->session->get('booking_id');
        $bookingData = $this->session->get('booking_data');
        
        if (!$bookingId || !$bookingData) {
            // Redirect to tours page
            $this->redirect('tours');
        }
        
        // Set page data
        $data = [
            'bookingId' => $bookingId,
            'bookingData' => $bookingData,
            'pageTitle' => __('booking_confirmed'),
            'metaDescription' => __('booking_confirmed_description')
        ];
        
        // Render view
        $this->render('booking/thank-you', $data);
        
        // Clear booking data from session
        $this->session->remove('booking_id');
        $this->session->remove('booking_data');
    }
    
    /**
     * Send booking confirmation emails
     * 
     * @param int $bookingId Booking ID
     * @param array $bookingData Booking data
     * @param array $tour Tour data
     * @return bool Success
     */
    private function sendConfirmationEmails($bookingId, $bookingData, $tour)
    {
        try {
            // Load Email class
            require_once BASE_PATH . '/core/Email.php';
            $email = new Email();
            
            // Send confirmation email to customer
            $customerEmailSent = $this->sendCustomerConfirmationEmail($email, $bookingData, $tour);
            
            // Send notification email to admin
            $adminEmailSent = $this->sendAdminNotificationEmail($email, $bookingData, $tour);
            
            // Log email results for debugging
            if ($customerEmailSent) {
                error_log("Booking #{$bookingId}: Customer confirmation email sent to {$bookingData['email']}");
            } else {
                error_log("Booking #{$bookingId}: Failed to send customer confirmation email to {$bookingData['email']}. Error: " . $email->getError());
            }
            
            if ($adminEmailSent) {
                error_log("Booking #{$bookingId}: Admin notification email sent");
            } else {
                error_log("Booking #{$bookingId}: Failed to send admin notification email. Error: " . $email->getError());
            }
            
            return $customerEmailSent || $adminEmailSent; // Return true if at least one email was sent
            
        } catch (Exception $e) {
            error_log("Booking #{$bookingId}: Email sending failed with exception: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send confirmation email to customer
     * 
     * @param Email $email Email instance
     * @param array $bookingData Booking data
     * @param array $tour Tour data
     * @return bool Success
     */
    private function sendCustomerConfirmationEmail($email, $bookingData, $tour)
    {
        // Get settings for currency
        $settings = $this->settingsModel->getAllSettings();
        $currencySymbol = $settings['currency_symbol'] ?? '€';
        
        // Prepare booking data for email
        $emailBookingData = array_merge($bookingData, [
            'tour_name' => $tour['name'],
            'total_price_formatted' => $currencySymbol . number_format($bookingData['total_price'], 2),
            'booking_date_formatted' => date('l, F j, Y', strtotime($bookingData['booking_date'])),
            'payment_method_formatted' => $this->formatPaymentMethod($bookingData['payment_method'])
        ]);
        
        return $email->sendBookingConfirmation($emailBookingData);
    }
    
    /**
     * Send notification email to admin
     * 
     * @param Email $email Email instance
     * @param array $bookingData Booking data
     * @param array $tour Tour data
     * @return bool Success
     */
    private function sendAdminNotificationEmail($email, $bookingData, $tour)
    {
        // Get settings for admin email
        $settings = $this->settingsModel->getAllSettings();
        
        // Check if admin notifications are enabled
        if (!isset($settings['email_notification_booking']) || $settings['email_notification_booking'] != '1') {
            return true; // Not enabled, but don't consider this a failure
        }
        
        // Get currency symbol
        $currencySymbol = $settings['currency_symbol'] ?? '€';
        
        // Prepare booking data for admin email
        $emailBookingData = array_merge($bookingData, [
            'tour_name' => $tour['name'],
            'total_price_formatted' => $currencySymbol . number_format($bookingData['total_price'], 2),
            'booking_date_formatted' => date('l, F j, Y', strtotime($bookingData['booking_date'])),
            'payment_method_formatted' => $this->formatPaymentMethod($bookingData['payment_method'])
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
     * Get available booking dates
     * 
     * @return array Available dates
     */
    private function getAvailableDates()
    {
        // In a real application, this would be from a calendar or database
        // For now, just return the next 30 days
        $availableDates = [];
        $currentDate = time();
        
        for ($i = 1; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime("+$i days", $currentDate));
            $availableDates[] = $date;
        }
        
        return $availableDates;
    }
}