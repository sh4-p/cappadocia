<?php
/**
 * Booking Controller - Updated with Anti-Bot Protection and Enhanced Tracking
 * 
 * Handles the booking process with email integration, tracking, and comprehensive bot protection
 */
class BookingController extends Controller
{
    private $tourModel;
    private $bookingModel;
    private $settingsModel;
    private $antiBot;
    
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
        
        // Initialize anti-bot protection
        $settings = $this->settingsModel->getAllSettings();
        
        require_once BASE_PATH . '/core/AntiBot.php';
        $this->antiBot = new AntiBot($this->db, $settings, $this->session);
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
            ],
            // Anti-bot protection HTML
            'honeypotFields' => $this->antiBot->generateHoneypotFields(),
            'recaptchaV2Html' => $this->antiBot->getRecaptchaV2Html(),
            'recaptchaV3Html' => $this->antiBot->getRecaptchaV3Html('booking'),
            'turnstileHtml' => $this->antiBot->getTurnstileHtml(),
            'antibotEnabled' => $this->antiBot->isEnabled()
        ];
        
        // Render view
        $this->render('booking/form', $data);
    }
    
    /**
     * Confirm action - process booking form with comprehensive validation
     */
    public function confirm()
    {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('tours');
        }
        
        // Anti-bot validation
        if ($this->antiBot->isEnabled()) {
            $antibotResult = $this->antiBot->validateSubmission('booking', $_POST);
            
            if (!$antibotResult['success']) {
                // Log the bot attempt with detailed information
                error_log("Booking form bot attempt blocked: " . json_encode([
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'reason' => $antibotResult['blocked_reason'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                    'post_data' => array_keys($_POST),
                    'tour_id' => $this->post('tour_id'),
                    'timestamp' => date('Y-m-d H:i:s')
                ]));
                
                $this->session->setFlash('error', implode('<br>', $antibotResult['errors']));
                
                // Redirect back to booking form
                $tourId = $this->post('tour_id');
                if ($tourId) {
                    $this->redirect('booking/tour/' . $tourId);
                } else {
                    $this->redirect('tours');
                }
                return;
            }
        }
        
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get form data
        $tourId = $this->post('tour_id');
        $firstName = trim($this->post('first_name'));
        $lastName = trim($this->post('last_name'));
        $email = trim($this->post('email'));
        $phone = trim($this->post('phone'));
        $bookingDate = $this->post('booking_date');
        $adults = (int) $this->post('adults', 1);
        $children = (int) $this->post('children', 0);
        $totalPrice = (float) $this->post('total_price');
        $specialRequests = trim($this->post('special_requests'));
        $paymentMethod = $this->post('payment_method');
        
        // Enhanced validation
        $errors = [];
        
        if (empty($tourId)) {
            $errors[] = __('tour_required');
        }
        
        if (empty($firstName)) {
            $errors[] = __('first_name_required');
        } elseif (strlen($firstName) < 2) {
            $errors[] = __('first_name_too_short');
        } elseif (strlen($firstName) > 50) {
            $errors[] = __('first_name_too_long');
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/u', $firstName)) {
            $errors[] = __('invalid_first_name_format');
        }
        
        if (empty($lastName)) {
            $errors[] = __('last_name_required');
        } elseif (strlen($lastName) < 2) {
            $errors[] = __('last_name_too_short');
        } elseif (strlen($lastName) > 50) {
            $errors[] = __('last_name_too_long');
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/u', $lastName)) {
            $errors[] = __('invalid_last_name_format');
        }
        
        if (empty($email)) {
            $errors[] = __('email_required');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = __('invalid_email');
        } elseif ($this->isDisposableEmail($email)) {
            $errors[] = __('disposable_email_not_allowed');
        }
        
        if (empty($phone)) {
            $errors[] = __('phone_required');
        } elseif (!preg_match('/^[\+]?[0-9\s\-\(\)\.]{7,20}$/', $phone)) {
            $errors[] = __('invalid_phone_format');
        }
        
        if (empty($bookingDate)) {
            $errors[] = __('booking_date_required');
        } elseif (strtotime($bookingDate) < strtotime(date('Y-m-d'))) {
            $errors[] = __('booking_date_past');
        } elseif (strtotime($bookingDate) > strtotime('+1 year')) {
            $errors[] = __('booking_date_too_far');
        }
        
        if ($adults < 1) {
            $errors[] = __('adults_required');
        } elseif ($adults > 20) {
            $errors[] = __('too_many_adults');
        }
        
        if ($children < 0) {
            $errors[] = __('invalid_children_count');
        } elseif ($children > 10) {
            $errors[] = __('too_many_children');
        }
        
        if ($totalPrice <= 0) {
            $errors[] = __('invalid_price');
        } elseif ($totalPrice > 50000) {
            $errors[] = __('price_too_high');
        }
        
        if (empty($paymentMethod)) {
            $errors[] = __('payment_method_required');
        }
        
        // Validate special requests length and content
        if (!empty($specialRequests)) {
            if (strlen($specialRequests) > 1000) {
                $errors[] = __('special_requests_too_long');
            } elseif ($this->containsSpamContent($specialRequests)) {
                $errors[] = __('spam_content_detected');
            }
        }
        
        // Get tour details for validation
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
        
        // Validate price calculation
        if ($tour) {
            $price = $tour['discount_price'] > 0 ? $tour['discount_price'] : $tour['price'];
            $calculatedTotal = ($adults * $price) + ($children * $price * 0.5);
            $calculatedTotal = round($calculatedTotal, 2);
            
            // Allow small difference due to rounding
            if (abs($calculatedTotal - $totalPrice) > 0.01) {
                $errors[] = __('price_mismatch');
            }
        }
        
        // Check for suspicious booking patterns
        if ($this->isSuspiciousBooking($firstName, $lastName, $email, $phone, $specialRequests)) {
            $errors[] = __('booking_under_review');
            
            // Log suspicious booking attempt
            error_log("Suspicious booking attempt: " . json_encode([
                'ip' => $_SERVER['REMOTE_ADDR'],
                'email' => $email,
                'name' => $firstName . ' ' . $lastName,
                'phone' => $phone,
                'tour_id' => $tourId,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]));
        }
        
        // Check booking frequency (prevent spam bookings)
        if (!$this->checkBookingFrequency($email)) {
            $errors[] = __('too_many_booking_attempts');
        }
        
        // If there are errors, redirect back to form
        if (!empty($errors)) {
            // Set error message
            $this->session->setFlash('error', implode('<br>', $errors));
            
            // Redirect to booking form
            $this->redirect('booking/tour/' . $tourId);
            return;
        }
        
        // Create booking data with enhanced tracking
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
            'status' => 'pending',
            'tracking_token' => $this->generateTrackingToken(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ];
        
        // Save booking
        $bookingId = $this->bookingModel->insert($bookingData);
        
        if ($bookingId) {
            // Log successful booking
            $this->logBookingAction('create', $bookingId, $email, true);
            
            // Get the created booking with tracking token
            $createdBooking = $this->bookingModel->getWithTourDetails($bookingId, $langCode);
            
            // Set booking data in session for thank you page
            $this->session->set('booking_id', $bookingId);
            $this->session->set('booking_data', array_merge($bookingData, [
                'tour_name' => $tour['name'],
                'tracking_token' => $createdBooking['tracking_token']
            ]));
            
            // Send confirmation emails
            $this->sendConfirmationEmails($bookingId, $bookingData, $tour);
            
            // Redirect to thank you page
            $this->redirect('booking/thank-you');
        } else {
            // Log failed booking
            $this->logBookingAction('create', null, $email, false);
            
            // Set error message
            $this->session->setFlash('error', __('booking_failed'));
            
            // Redirect to booking form
            $this->redirect('booking/tour/' . $tourId);
        }
    }
    
    /**
     * Check if email is from a disposable email service
     */
    private function isDisposableEmail($email)
    {
        $domain = strtolower(substr(strrchr($email, "@"), 1));
        
        $disposableDomains = [
            '10minutemail.com', '10minutemail.net', 'tempmail.org', 'guerrillamail.com',
            'mailinator.com', 'temp-mail.org', 'throwaway.email', 'fakeinbox.com',
            'getnada.com', 'sharklasers.com', 'guerrillamailblock.com', 'pokemail.net',
            'spam4.me', 'tempail.com', 'tempinbox.com', 'yopmail.com', 'maildrop.cc'
        ];
        
        return in_array($domain, $disposableDomains);
    }
    
    /**
     * Check for spam content
     */
    private function containsSpamContent($content)
    {
        $content = strtolower($content);
        
        $spamKeywords = [
            'viagra', 'cialis', 'pharmacy', 'casino', 'poker', 'lottery',
            'bitcoin', 'cryptocurrency', 'investment', 'loan', 'credit',
            'seo service', 'web design', 'marketing', 'promotion'
        ];
        
        foreach ($spamKeywords as $keyword) {
            if (strpos($content, $keyword) !== false) {
                return true;
            }
        }
        
        // Check for excessive links
        $linkCount = preg_match_all('/https?:\/\//', $content);
        if ($linkCount > 1) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check for suspicious booking patterns
     */
    private function isSuspiciousBooking($firstName, $lastName, $email, $phone, $specialRequests)
    {
        // Check for fake names
        $fakeName = preg_match('/^[a-zA-Z]{1,2}$/', $firstName) || 
                   preg_match('/^[a-zA-Z]{1,2}$/', $lastName) ||
                   preg_match('/test|admin|user|guest|demo|fake/i', $firstName . ' ' . $lastName);
        
        // Check for suspicious email patterns
        $suspiciousEmail = preg_match('/\+.*test|\.ru$|\.tk$|temp|trash|fake/i', $email);
        
        // Check for suspicious phone patterns
        $suspiciousPhone = preg_match('/^1{7,}|^0{7,}|^123456/i', $phone);
        
        // Check for suspicious special requests
        $suspiciousRequests = !empty($specialRequests) && 
                             preg_match('/test|check|hello|hi$/i', trim($specialRequests));
        
        return $fakeName || $suspiciousEmail || $suspiciousPhone || $suspiciousRequests;
    }
    
    /**
     * Check booking frequency to prevent spam
     */
    private function checkBookingFrequency($email)
    {
        try {
            $ip = $_SERVER['REMOTE_ADDR'];
            
            // Check if same email or IP tried to book multiple times recently
            $sql = "SELECT COUNT(*) FROM booking_attempts 
                    WHERE (email = :email OR ip_address = :ip) 
                    AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
            
            $attempts = $this->db->getValue($sql, ['email' => $email, 'ip' => $ip]);
            
            return $attempts < 5; // Max 5 attempts per hour
        } catch (Exception $e) {
            // If table doesn't exist, allow booking
            return true;
        }
    }
    
    /**
     * Generate unique tracking token
     */
    private function generateTrackingToken()
    {
        do {
            $token = strtoupper(substr(md5(time() . uniqid() . mt_rand()), 0, 16));
            
            // Check if token already exists
            $existing = $this->db->getValue(
                "SELECT id FROM bookings WHERE tracking_token = :token",
                ['token' => $token]
            );
        } while ($existing);
        
        return $token;
    }
    
    /**
     * Log booking action
     */
    private function logBookingAction($action, $bookingId, $email, $success)
    {
        try {
            $data = [
                'action' => $action,
                'booking_id' => $bookingId,
                'email' => $email,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'success' => $success ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert('booking_attempts', $data);
        } catch (Exception $e) {
            // Log error but don't fail the request
            error_log("Failed to log booking action: " . $e->getMessage());
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
            'pageTitle' => __('booking_received'),
            'metaDescription' => __('booking_received_description')
        ];
        
        // Render view
        $this->render('booking/thank-you', $data);
        
        // Clear booking data from session
        $this->session->remove('booking_id');
        $this->session->remove('booking_data');
    }
    
    /**
     * Track redirect action - redirect to search if no token provided
     */
    public function trackRedirect()
    {
        // Set informative message
        $this->session->setFlash('info', __('track_link_required'));
        
        // Redirect to search page
        $this->redirect('booking/search');
    }
    
    /**
     * Track action - display booking tracking page by token
     * 
     * @param string $token Tracking token
     */
    public function track($token = null)
    {
        // Security check: if no token provided or invalid format, redirect to search
        if (empty($token) || !preg_match('/^[A-Z0-9]{16}$/', $token)) {
            $this->session->setFlash('error', __('invalid_tracking_token'));
            $this->redirect('booking/search');
            return;
        }
        
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get booking by tracking token
        $booking = $this->bookingModel->getByTrackingToken($token, $langCode);
        
        if (!$booking) {
            // Log invalid tracking attempt
            error_log("Invalid booking tracking token: " . $token . " from IP: " . $_SERVER['REMOTE_ADDR']);
            
            // Set error message and redirect
            $this->session->setFlash('error', __('booking_not_found_token'));
            $this->redirect('booking/search');
            return;
        }
        
        // Get booking status history
        $statusHistory = $this->bookingModel->getStatusHistory($booking['id']);
        
        // Get settings for currency and contact info
        $settings = $this->settingsModel->getAllSettings();
        
        // Set page data
        $data = [
            'booking' => $booking,
            'statusHistory' => $statusHistory,
            'settings' => $settings,
            'pageTitle' => sprintf(__('booking_tracking'), $this->formatBookingReference($booking['id'])),
            'metaDescription' => __('track_your_booking_status')
        ];
        
        // Render view
        $this->render('booking/track', $data);
    }
    
    /**
     * Search action - display booking search page
     */
    public function search()
    {
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($this->post('email'));
            $reference = trim($this->post('reference'));
            
            // Validate inputs
            if (empty($email) || empty($reference)) {
                $this->session->setFlash('error', __('email_and_reference_required'));
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->session->setFlash('error', __('invalid_email'));
            } else {
                // Search for booking
                $langCode = $this->language->getCurrentLanguage();
                $booking = $this->bookingModel->searchByEmailAndReference($email, $reference, $langCode);
                
                if ($booking) {
                    // Redirect to tracking page using token
                    $this->redirect('booking/track/' . $booking['tracking_token']);
                } else {
                    $this->session->setFlash('error', __('booking_not_found_with_details'));
                }
            }
        }
        
        // Set page data
        $data = [
            'pageTitle' => __('find_your_booking'),
            'metaDescription' => __('search_booking_description')
        ];
        
        // Render view
        $this->render('booking/search', $data);
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
            'payment_method_formatted' => $this->formatPaymentMethod($bookingData['payment_method']),
            'tracking_url' => $this->getTrackingUrl($bookingData['tracking_token']),
            'booking_reference' => $this->formatBookingReference($bookingData['id'] ?? 0)
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
            'payment_method_formatted' => $this->formatPaymentMethod($bookingData['payment_method']),
            'booking_reference' => $this->formatBookingReference($bookingData['id'] ?? 0),
            'ip_address' => $bookingData['ip_address'] ?? $_SERVER['REMOTE_ADDR']
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
     * Format booking reference
     * 
     * @param int $bookingId Booking ID
     * @return string Formatted reference
     */
    private function formatBookingReference($bookingId)
    {
        return '#' . str_pad($bookingId, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Get tracking URL for booking
     * 
     * @param string $trackingToken Tracking token
     * @return string Tracking URL
     */
    private function getTrackingUrl($trackingToken)
    {
        $currentLang = $this->language->getCurrentLanguage();
        $appUrl = defined('APP_URL') ? APP_URL : 'https://yourwebsite.com';
        
        return $appUrl . '/' . $currentLang . '/booking/track/' . $trackingToken;
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