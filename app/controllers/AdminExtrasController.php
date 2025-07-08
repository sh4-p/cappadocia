<?php
/**
 * Admin Extras Controller
 * 
 * Handles extras management in admin panel
 */
class AdminExtrasController extends Controller
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
        return 'AdminExtras';
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
     * Index action - list all extras
     */
    public function index()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load models
        $tourExtraModel = $this->loadModel('TourExtra');
        
        // Get extras with details
        $extras = $tourExtraModel->getAllWithDetails($langCode);
        
        // Render view
        $this->render('admin/extras/index', [
            'pageTitle' => __('tour_extras'),
            'extras' => $extras
        ], 'admin');
    }
    
    /**
     * Create action - create a new extra
     */
    public function create()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load models
        $tourExtraModel = $this->loadModel('TourExtra');
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get languages
        $languages = $languageModel->getActiveLanguages();
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $pricingType = $this->post('pricing_type', 'fixed_group');
            $basePrice = $this->post('base_price');
            $category = $this->post('category');
            $orderNumber = $this->post('order_number', 0);
            $isActive = $this->post('is_active', 0);
            $details = $this->post('details', []);
            $pricingTiers = $this->post('pricing_tiers', []);
            
            // Validate inputs
            $errors = [];
            
            if (empty($basePrice) || !is_numeric($basePrice) || $basePrice < 0) {
                $errors[] = __('valid_base_price_required');
            }
            
            // Validate details for each language
            foreach ($languages as $lang) {
                if (empty($details[$lang['id']]['name'])) {
                    $errors[] = sprintf(__('name_required_for_lang'), $lang['name']);
                }
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Render view again with data
                $this->render('admin/extras/create', [
                    'pageTitle' => __('add_extra'),
                    'languages' => $languages,
                    'details' => $details,
                    'pricingType' => $pricingType,
                    'basePrice' => $basePrice,
                    'category' => $category,
                    'orderNumber' => $orderNumber,
                    'isActive' => $isActive,
                    'pricingTiers' => $pricingTiers,
                    'currentLang' => $langCode
                ], 'admin');
                
                return;
            }
            
            // Prepare extra data
            $extraData = [
                'pricing_type' => $pricingType,
                'base_price' => $basePrice,
                'category' => $category ?: null,
                'order_number' => $orderNumber,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Create extra
            $extraId = $tourExtraModel->addWithDetails($extraData, $details);
            
            if ($extraId) {
                // Handle tiered pricing if applicable
                if ($pricingType === 'tiered' && !empty($pricingTiers)) {
                    $extraPricingModel = $this->loadModel('TourExtraPricing');
                    $extraPricingModel->savePricingTiers($extraId, $pricingTiers);
                }
                
                $this->session->setFlash('success', __('extra_added'));
                $this->redirect('admin/extras');
            } else {
                $this->session->setFlash('error', __('extra_add_failed'));
            }
        }
        
        // Render view
        $this->render('admin/extras/create', [
            'pageTitle' => __('add_extra'),
            'languages' => $languages,
            'currentLang' => $langCode
        ], 'admin');
    }
    
    /**
     * Edit action - edit an extra
     * 
     * @param int $id Extra ID
     */
    public function edit($id)
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load models
        $tourExtraModel = $this->loadModel('TourExtra');
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get extra
        $extra = $tourExtraModel->getWithDetails($id, $langCode);
        
        // Check if extra exists
        if (!$extra) {
            $this->session->setFlash('error', __('extra_not_found'));
            $this->redirect('admin/extras');
        }
        
        // Get languages
        $languages = $languageModel->getActiveLanguages();
        
        // Get extra details for all languages
        $extraDetails = [];
        foreach ($languages as $lang) {
            $langExtra = $tourExtraModel->getWithDetails($id, $lang['code']);
            if ($langExtra) {
                $extraDetails[$lang['id']] = [
                    'name' => $langExtra['name'],
                    'description' => $langExtra['description']
                ];
            }
        }
        
        // Get pricing tiers if applicable
        $pricingTiers = [];
        if ($extra['pricing_type'] === 'tiered') {
            $extraPricingModel = $this->loadModel('TourExtraPricing');
            $pricingTiers = $extraPricingModel->getPricingTiers($id);
        }
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $pricingType = $this->post('pricing_type', 'fixed_group');
            $basePrice = $this->post('base_price');
            $category = $this->post('category');
            $orderNumber = $this->post('order_number', 0);
            $isActive = $this->post('is_active', 0);
            $details = $this->post('details', []);
            $pricingTiers = $this->post('pricing_tiers', []);
            
            // Validate inputs
            $errors = [];
            
            if (empty($basePrice) || !is_numeric($basePrice) || $basePrice < 0) {
                $errors[] = __('valid_base_price_required');
            }
            
            // Validate details for each language
            foreach ($languages as $lang) {
                if (empty($details[$lang['id']]['name'])) {
                    $errors[] = sprintf(__('name_required_for_lang'), $lang['name']);
                }
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Update extra details with POST data
                $extraDetails = $details;
                
                // Render view again
                $this->render('admin/extras/edit', [
                    'pageTitle' => __('edit_extra'),
                    'extra' => $extra,
                    'extraDetails' => $extraDetails,
                    'languages' => $languages,
                    'pricingTiers' => $pricingTiers,
                    'currentLang' => $langCode
                ], 'admin');
                
                return;
            }
            
            // Prepare extra data
            $extraData = [
                'pricing_type' => $pricingType,
                'base_price' => $basePrice,
                'category' => $category ?: null,
                'order_number' => $orderNumber,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Update extra
            $result = $tourExtraModel->updateWithDetails($id, $extraData, $details);
            
            if ($result) {
                // Handle tiered pricing if applicable
                if ($pricingType === 'tiered' && !empty($pricingTiers)) {
                    $extraPricingModel = $this->loadModel('TourExtraPricing');
                    $extraPricingModel->savePricingTiers($id, $pricingTiers);
                }
                
                $this->session->setFlash('success', __('extra_updated'));
                $this->redirect('admin/extras');
            } else {
                $this->session->setFlash('error', __('extra_update_failed'));
            }
        }
        
        // Render view
        $this->render('admin/extras/edit', [
            'pageTitle' => __('edit_extra'),
            'extra' => $extra,
            'extraDetails' => $extraDetails,
            'languages' => $languages,
            'pricingTiers' => $pricingTiers,
            'currentLang' => $langCode
        ], 'admin');
    }
    
    /**
     * Delete action - delete an extra
     * 
     * @param int $id Extra ID
     */
    public function delete($id)
    {
        // Load extra model
        $tourExtraModel = $this->loadModel('TourExtra');
        
        // Get extra
        $extra = $tourExtraModel->getById($id);
        
        // Check if extra exists
        if (!$extra) {
            $this->session->setFlash('error', __('extra_not_found'));
            $this->redirect('admin/extras');
        }
        
        // Delete extra
        $result = $tourExtraModel->deleteWithDetails($id);
        
        if ($result) {
            $this->session->setFlash('success', __('extra_deleted'));
        } else {
            $this->session->setFlash('error', __('extra_delete_failed'));
        }
        
        // Redirect to extras list
        $this->redirect('admin/extras');
    }
    
    /**
     * Toggle status action
     * 
     * @param int $id Extra ID
     */
    public function toggleStatus($id)
    {
        // Load extra model
        $tourExtraModel = $this->loadModel('TourExtra');
        
        // Get extra
        $extra = $tourExtraModel->getById($id);
        
        // Check if extra exists
        if (!$extra) {
            $this->session->setFlash('error', __('extra_not_found'));
            $this->redirect('admin/extras');
        }
        
        // Toggle status
        $newStatus = $extra['is_active'] ? 0 : 1;
        $result = $tourExtraModel->update(['is_active' => $newStatus], ['id' => $id]);
        
        if ($result) {
            $statusText = $newStatus ? __('activated') : __('deactivated');
            $this->session->setFlash('success', __('extra') . ' ' . $statusText);
        } else {
            $this->session->setFlash('error', __('status_update_failed'));
        }
        
        // Redirect to extras list
        $this->redirect('admin/extras');
    }
}