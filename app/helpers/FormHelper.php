<?php
/**
 * Form Helper Class
 * 
 * Helper functions for form generation and handling
 */
class FormHelper
{
    /**
     * CSRF token
     * 
     * @var string
     */
    private static $csrfToken;
    
    /**
     * Form errors
     * 
     * @var array
     */
    private static $errors = [];
    
    /**
     * Form data
     * 
     * @var array
     */
    private static $data = [];
    
    /**
     * Open a form
     * 
     * @param string $action Form action URL
     * @param string $method Form method
     * @param array $attributes Additional attributes
     * @param bool $multipart Include multipart/form-data
     * @param bool $csrf Include CSRF token
     * @return string Form opening tag
     */
    public static function open($action = '', $method = 'post', $attributes = [], $multipart = false, $csrf = true)
    {
        // Set method attribute
        $attributes['method'] = strtolower($method) === 'get' ? 'get' : 'post';
        
        // Set action attribute
        $attributes['action'] = $action;
        
        // Set enctype for file uploads
        if ($multipart) {
            $attributes['enctype'] = 'multipart/form-data';
        }
        
        // Build attributes
        $attributesStr = self::buildAttributes($attributes);
        
        // Generate form tag
        $form = '<form' . $attributesStr . '>';
        
        // Add method override if needed
        if (in_array(strtolower($method), ['put', 'patch', 'delete'])) {
            $form .= self::hidden('_method', $method);
        }
        
        // Add CSRF token
        if ($csrf) {
            $form .= self::csrf();
        }
        
        return $form;
    }
    
    /**
     * Close a form
     * 
     * @return string Form closing tag
     */
    public static function close()
    {
        return '</form>';
    }
    
    /**
     * Create a label
     * 
     * @param string $for Input ID
     * @param string $text Label text
     * @param array $attributes Additional attributes
     * @return string Label element
     */
    public static function label($for, $text, $attributes = [])
    {
        $attributes['for'] = $for;
        $attributesStr = self::buildAttributes($attributes);
        
        return '<label' . $attributesStr . '>' . $text . '</label>';
    }
    
    /**
     * Create a text input
     * 
     * @param string $name Input name
     * @param string $value Input value
     * @param array $attributes Additional attributes
     * @return string Input element
     */
    public static function text($name, $value = '', $attributes = [])
    {
        return self::input('text', $name, $value, $attributes);
    }
    
    /**
     * Create a password input
     * 
     * @param string $name Input name
     * @param string $value Input value
     * @param array $attributes Additional attributes
     * @return string Input element
     */
    public static function password($name, $value = '', $attributes = [])
    {
        return self::input('password', $name, $value, $attributes);
    }
    
    /**
     * Create an email input
     * 
     * @param string $name Input name
     * @param string $value Input value
     * @param array $attributes Additional attributes
     * @return string Input element
     */
    public static function email($name, $value = '', $attributes = [])
    {
        return self::input('email', $name, $value, $attributes);
    }
    
    /**
     * Create a number input
     * 
     * @param string $name Input name
     * @param string $value Input value
     * @param array $attributes Additional attributes
     * @return string Input element
     */
    public static function number($name, $value = '', $attributes = [])
    {
        return self::input('number', $name, $value, $attributes);
    }
    
    /**
     * Create a date input
     * 
     * @param string $name Input name
     * @param string $value Input value
     * @param array $attributes Additional attributes
     * @return string Input element
     */
    public static function date($name, $value = '', $attributes = [])
    {
        return self::input('date', $name, $value, $attributes);
    }
    
    /**
     * Create a hidden input
     * 
     * @param string $name Input name
     * @param string $value Input value
     * @param array $attributes Additional attributes
     * @return string Input element
     */
    public static function hidden($name, $value = '', $attributes = [])
    {
        return self::input('hidden', $name, $value, $attributes);
    }
    
    /**
     * Create a file input
     * 
     * @param string $name Input name
     * @param array $attributes Additional attributes
     * @return string Input element
     */
    public static function file($name, $attributes = [])
    {
        return self::input('file', $name, '', $attributes);
    }
    
    /**
     * Create a checkbox
     * 
     * @param string $name Input name
     * @param string $value Input value
     * @param bool $checked Is checked
     * @param array $attributes Additional attributes
     * @return string Input element
     */
    public static function checkbox($name, $value = '1', $checked = false, $attributes = [])
    {
        if ($checked) {
            $attributes['checked'] = 'checked';
        }
        
        return self::input('checkbox', $name, $value, $attributes);
    }
    
    /**
     * Create a radio button
     * 
     * @param string $name Input name
     * @param string $value Input value
     * @param bool $checked Is checked
     * @param array $attributes Additional attributes
     * @return string Input element
     */
    public static function radio($name, $value, $checked = false, $attributes = [])
    {
        if ($checked) {
            $attributes['checked'] = 'checked';
        }
        
        return self::input('radio', $name, $value, $attributes);
    }
    
    /**
     * Create a textarea
     * 
     * @param string $name Textarea name
     * @param string $value Textarea value
     * @param array $attributes Additional attributes
     * @return string Textarea element
     */
    public static function textarea($name, $value = '', $attributes = [])
    {
        $attributes['name'] = $name;
        
        // Set ID if not set
        if (!isset($attributes['id'])) {
            $attributes['id'] = $name;
        }
        
        // Set value from data if exists
        if (isset(self::$data[$name]) && empty($value)) {
            $value = self::$data[$name];
        }
        
        // Add error class if error exists
        if (isset(self::$errors[$name])) {
            $attributes['class'] = isset($attributes['class']) 
                ? $attributes['class'] . ' is-invalid' 
                : 'is-invalid';
        }
        
        $attributesStr = self::buildAttributes($attributes);
        
        return '<textarea' . $attributesStr . '>' . htmlspecialchars($value) . '</textarea>';
    }
    
    /**
     * Create a select dropdown
     * 
     * @param string $name Select name
     * @param array $options Select options
     * @param string|array $selected Selected option(s)
     * @param array $attributes Additional attributes
     * @return string Select element
     */
    public static function select($name, $options, $selected = '', $attributes = [])
    {
        $attributes['name'] = $name;
        
        // Set ID if not set
        if (!isset($attributes['id'])) {
            $attributes['id'] = $name;
        }
        
        // Set selected from data if exists
        if (isset(self::$data[$name]) && empty($selected)) {
            $selected = self::$data[$name];
        }
        
        // Convert selected to array
        if (!is_array($selected)) {
            $selected = [$selected];
        }
        
        // Add error class if error exists
        if (isset(self::$errors[$name])) {
            $attributes['class'] = isset($attributes['class']) 
                ? $attributes['class'] . ' is-invalid' 
                : 'is-invalid';
        }
        
        $attributesStr = self::buildAttributes($attributes);
        
        $html = '<select' . $attributesStr . '>';
        
        foreach ($options as $value => $label) {
            $optionAttrs = ['value' => $value];
            
            if (in_array($value, $selected)) {
                $optionAttrs['selected'] = 'selected';
            }
            
            $optionAttrsStr = self::buildAttributes($optionAttrs);
            
            $html .= '<option' . $optionAttrsStr . '>' . htmlspecialchars($label) . '</option>';
        }
        
        $html .= '</select>';
        
        return $html;
    }
    
    /**
     * Create a submit button
     * 
     * @param string $text Button text
     * @param array $attributes Additional attributes
     * @return string Button element
     */
    public static function submit($text, $attributes = [])
    {
        $attributes['type'] = 'submit';
        
        // Add default class if not set
        if (!isset($attributes['class'])) {
            $attributes['class'] = 'btn btn-primary';
        }
        
        $attributesStr = self::buildAttributes($attributes);
        
        return '<button' . $attributesStr . '>' . $text . '</button>';
    }
    
    /**
     * Create a button
     * 
     * @param string $text Button text
     * @param array $attributes Additional attributes
     * @return string Button element
     */
    public static function button($text, $attributes = [])
    {
        $attributes['type'] = 'button';
        
        // Add default class if not set
        if (!isset($attributes['class'])) {
            $attributes['class'] = 'btn btn-secondary';
        }
        
        $attributesStr = self::buildAttributes($attributes);
        
        return '<button' . $attributesStr . '>' . $text . '</button>';
    }
    
    /**
     * Create an input element
     * 
     * @param string $type Input type
     * @param string $name Input name
     * @param string $value Input value
     * @param array $attributes Additional attributes
     * @return string Input element
     */
    public static function input($type, $name, $value = '', $attributes = [])
    {
        $attributes['type'] = $type;
        $attributes['name'] = $name;
        
        // Set ID if not set
        if (!isset($attributes['id'])) {
            $attributes['id'] = $name;
        }
        
        // Set value from data if exists
        if (isset(self::$data[$name]) && empty($value) && $type !== 'password') {
            $value = self::$data[$name];
        }
        
        // Set value attribute
        if ($type !== 'file') {
            $attributes['value'] = $value;
        }
        
        // Add error class if error exists
        if (isset(self::$errors[$name])) {
            $attributes['class'] = isset($attributes['class']) 
                ? $attributes['class'] . ' is-invalid' 
                : 'is-invalid';
        }
        
        $attributesStr = self::buildAttributes($attributes);
        
        return '<input' . $attributesStr . '>';
    }
    
    /**
     * Create an error message
     * 
     * @param string $name Field name
     * @param string $defaultMessage Default message
     * @return string Error message HTML
     */
    public static function error($name, $defaultMessage = '')
    {
        if (!isset(self::$errors[$name])) {
            return '';
        }
        
        $message = !empty(self::$errors[$name]) ? self::$errors[$name] : $defaultMessage;
        
        return '<div class="invalid-feedback">' . $message . '</div>';
    }
    
    /**
     * Create a form group
     * 
     * @param string $name Field name
     * @param string $label Label text
     * @param string $input Input HTML
     * @param string $error Error message
     * @param array $attributes Additional attributes
     * @return string Form group HTML
     */
    public static function formGroup($name, $label, $input, $error = '', $attributes = [])
    {
        $attributes['class'] = isset($attributes['class']) 
            ? $attributes['class'] . ' form-group' 
            : 'form-group';
        
        if (isset(self::$errors[$name])) {
            $attributes['class'] .= ' has-error';
        }
        
        $attributesStr = self::buildAttributes($attributes);
        
        $html = '<div' . $attributesStr . '>';
        $html .= self::label($name, $label);
        $html .= $input;
        
        if (empty($error) && isset(self::$errors[$name])) {
            $html .= self::error($name);
        } else if (!empty($error)) {
            $html .= $error;
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Create a CSRF token field
     * 
     * @return string CSRF token field
     */
    public static function csrf()
    {
        // Generate CSRF token if not exists
        if (empty(self::$csrfToken)) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            
            self::$csrfToken = $_SESSION['csrf_token'];
        }
        
        return self::hidden('csrf_token', self::$csrfToken);
    }
    
    /**
     * Verify CSRF token
     * 
     * @param string $token CSRF token
     * @return bool Is valid
     */
    public static function verifyCsrf($token = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = $token ?: ($_POST['csrf_token'] ?? '');
        
        return !empty($_SESSION['csrf_token']) && !empty($token) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Set form data
     * 
     * @param array $data Form data
     */
    public static function setData($data)
    {
        self::$data = $data;
    }
    
    /**
     * Get form data
     * 
     * @return array Form data
     */
    public static function getData()
    {
        return self::$data;
    }
    
    /**
     * Set form errors
     * 
     * @param array $errors Form errors
     */
    public static function setErrors($errors)
    {
        self::$errors = $errors;
    }
    
    /**
     * Get form errors
     * 
     * @return array Form errors
     */
    public static function getErrors()
    {
        return self::$errors;
    }
    
    /**
     * Build HTML attributes
     * 
     * @param array $attributes Attributes
     * @return string Attributes string
     */
    private static function buildAttributes($attributes)
    {
        $attributesStr = '';
        
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $attributesStr .= ' ' . $key;
            } else if ($value !== false && $value !== null) {
                $attributesStr .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
            }
        }
        
        return $attributesStr;
    }
    
    /**
     * Create a multi-language form group
     * 
     * @param string $name Field name
     * @param string $label Label text
     * @param array $languages Languages
     * @param array $values Values for each language
     * @param string $type Input type
     * @param array $attributes Additional attributes
     * @return string Multi-language form group HTML
     */
    public static function multiLangGroup($name, $label, $languages, $values = [], $type = 'text', $attributes = [])
    {
        $html = '<div class="form-group">';
        $html .= self::label($name, $label);
        
        $html .= '<div class="lang-tabs">';
        foreach ($languages as $code => $language) {
            $activeClass = $code === array_key_first($languages) ? ' active' : '';
            $html .= '<div class="lang-tab' . $activeClass . '" data-lang="' . $code . '">';
            $html .= '<img src="' . APP_URL . '/public/uploads/flags/' . $code . '.png" alt="' . $language['name'] . '">';
            $html .= '<span>' . $language['name'] . '</span>';
            $html .= '</div>';
        }
        $html .= '</div>';
        
        foreach ($languages as $code => $language) {
            $langId = $language['id'];
            $fieldName = $name . '[' . $langId . ']';
            $fieldValue = $values[$langId] ?? '';
            $activeClass = $code === array_key_first($languages) ? ' active' : '';
            
            $html .= '<div class="lang-content' . $activeClass . '" data-lang="' . $code . '">';
            
            if ($type === 'textarea') {
                $html .= self::textarea($fieldName, $fieldValue, $attributes);
            } else {
                $html .= self::input($type, $fieldName, $fieldValue, $attributes);
            }
            
            $html .= self::error($fieldName);
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
}