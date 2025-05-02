<?php
/**
 * Booking Controller
 * 
 * Handles the booking process
 */
class BookingController extends Controller
{
    private $tourModel;
    private $bookingModel;
    
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Load models
        $this->tourModel = $this->loadModel('Tour');
        $this->bookingModel = $this->loadModel('Booking');
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
        
        // Set page data
        $data = [
            'tour' => $tour,
            'availableDates' => $availableDates,
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
        
        // Get tour details
        $tour = $this->tourModel->getWithDetails($tourId, $langCode);
        
        // Check if tour exists
        if (!$tour) {
            $errors[] = __('invalid_tour');
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
            'status' => 'pending'
        ];
        
        // Save booking
        $bookingId = $this->bookingModel->insert($bookingData);
        
        if ($bookingId) {
            // Set booking data in session for thank you page
            $this->session->set('booking_id', $bookingId);
            $this->session->set('booking_data', array_merge($bookingData, ['tour_name' => $tour['name']]));
            
            // Send confirmation email (can be implemented later)
            $this->sendConfirmationEmail($bookingId);
            
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
    
    /**
     * Send booking confirmation email
     * 
     * @param int $bookingId Booking ID
     * @return bool Success
     */
    private function sendConfirmationEmail($bookingId)
    {
        // In a real application, this would send an email
        // For now, just return true
        return true;
    }
}