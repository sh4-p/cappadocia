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
        
        // Get booking
        $booking = $bookingModel->get($id);
        
        // Check if booking exists
        if (!$booking) {
            $this->session->setFlash('error', __('booking_not_found'));
            $this->redirect('admin/bookings');
        }
        
        // Update status
        $result = $bookingModel->updateStatus($id, $status);
        
        if ($result) {
            $this->session->setFlash('success', __('status_updated'));
        } else {
            $this->session->setFlash('error', __('status_update_failed'));
        }
        
        // Redirect back
        $this->redirect('admin/bookings');
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
            __('status'),
            __('created_at')
        ]);
        
        // Add bookings
        foreach ($bookings as $booking) {
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
                __($booking['status']),
                $booking['created_at']
            ]);
        }
        
        // Close output stream
        fclose($output);
        exit;
    }
}