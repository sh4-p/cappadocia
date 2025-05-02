<?php
/**
 * Validator Class
 * 
 * Handles form validation
 */
class Validator
{
    private $errors = [];
    private $data = [];
    private $rules = [];
    private $labels = [];
    
    /**
     * Constructor
     * 
     * @param array $data Data to validate
     * @param array $rules Validation rules
     * @param array $labels Field labels
     */
    public function __construct($data = [], $rules = [], $labels = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->labels = $labels;
    }
    
    /**
     * Set data to validate
     * 
     * @param array $data Data to validate
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
    
    /**
     * Set validation rules
     * 
     * @param array $rules Validation rules
     * @return $this
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
        return $this;
    }
    
    /**
     * Set field labels
     * 
     * @param array $labels Field labels
     * @return $this
     */
    public function setLabels($labels)
    {
        $this->labels = $labels;
        return $this;
    }
    
    /**
     * Add validation rule
     * 
     * @param string $field Field name
     * @param string $rule Rule name
     * @param mixed $params Rule parameters
     * @param string $message Error message
     * @return $this
     */
    public function addRule($field, $rule, $params = null, $message = null)
    {
        if (!isset($this->rules[$field])) {
            $this->rules[$field] = [];
        }
        
        $this->rules[$field][] = [
            'rule' => $rule,
            'params' => $params,
            'message' => $message
        ];
        
        return $this;
    }
    
    /**
     * Set field label
     * 
     * @param string $field Field name
     * @param string $label Field label
     * @return $this
     */
    public function setLabel($field, $label)
    {
        $this->labels[$field] = $label;
        return $this;
    }
    
    /**
     * Validate data
     * 
     * @return bool Is valid
     */
    public function validate()
    {
        $this->errors = [];
        
        foreach ($this->rules as $field => $rules) {
            foreach ($rules as $rule) {
                $ruleName = is_array($rule) ? $rule['rule'] : $rule;
                $params = is_array($rule) && isset($rule['params']) ? $rule['params'] : null;
                $message = is_array($rule) && isset($rule['message']) ? $rule['message'] : null;
                
                $method = 'validate' . ucfirst($ruleName);
                
                if (method_exists($this, $method)) {
                    $valid = $this->$method($field, $params);
                    
                    if (!$valid) {
                        $this->addError($field, $ruleName, $message);
                        break; // Stop validation for this field on first error
                    }
                } else {
                    throw new Exception("Validation rule '$ruleName' not found");
                }
            }
        }
        
        return empty($this->errors);
    }
    
    /**
     * Add error message
     * 
     * @param string $field Field name
     * @param string $rule Rule name
     * @param string $customMessage Custom error message
     */
    private function addError($field, $rule, $customMessage = null)
    {
        $label = isset($this->labels[$field]) ? $this->labels[$field] : ucfirst($field);
        
        if ($customMessage) {
            $message = $customMessage;
        } else {
            // Default error messages
            $messages = [
                'required' => '{field} is required',
                'email' => '{field} must be a valid email address',
                'url' => '{field} must be a valid URL',
                'numeric' => '{field} must be numeric',
                'integer' => '{field} must be an integer',
                'float' => '{field} must be a float number',
                'min' => '{field} must be at least {param}',
                'max' => '{field} must be at most {param}',
                'minLength' => '{field} must be at least {param} characters',
                'maxLength' => '{field} must be at most {param} characters',
                'exactLength' => '{field} must be exactly {param} characters',
                'alpha' => '{field} must contain only letters',
                'alphaNumeric' => '{field} must contain only letters and numbers',
                'alphaDash' => '{field} must contain only letters, numbers, underscores and dashes',
                'regex' => '{field} is not in the correct format',
                'date' => '{field} must be a valid date',
                'dateFormat' => '{field} must be in the format {param}',
                'matches' => '{field} must match {param}',
                'different' => '{field} must be different from {param}',
                'in' => '{field} must be one of: {param}',
                'notIn' => '{field} must not be one of: {param}',
                'unique' => '{field} already exists'
            ];
            
            $message = isset($messages[$rule]) ? $messages[$rule] : '{field} is invalid';
        }
        
        // Replace placeholders
        $message = str_replace('{field}', $label, $message);
        
        if (isset($this->rules[$field])) {
            foreach ($this->rules[$field] as $fieldRule) {
                if (is_array($fieldRule) && $fieldRule['rule'] === $rule && isset($fieldRule['params'])) {
                    $message = str_replace('{param}', $fieldRule['params'], $message);
                    break;
                }
            }
        }
        
        $this->errors[$field] = $message;
    }
    
    /**
     * Get validation errors
     * 
     * @return array Errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Get first error
     * 
     * @return string|null First error
     */
    public function getFirstError()
    {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
    
    /**
     * Get error for field
     * 
     * @param string $field Field name
     * @return string|null Error
     */
    public function getError($field)
    {
        return isset($this->errors[$field]) ? $this->errors[$field] : null;
    }
    
    /**
     * Check if field has error
     * 
     * @param string $field Field name
     * @return bool Has error
     */
    public function hasError($field)
    {
        return isset($this->errors[$field]);
    }
    
    /**
     * Validate required
     * 
     * @param string $field Field name
     * @return bool Is valid
     */
    protected function validateRequired($field)
    {
        $value = isset($this->data[$field]) ? $this->data[$field] : null;
        
        if (is_array($value)) {
            return !empty($value);
        }
        
        return $value !== null && $value !== '';
    }
    
    /**
     * Validate email
     * 
     * @param string $field Field name
     * @return bool Is valid
     */
    protected function validateEmail($field)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty (unless required)
        }
        
        return filter_var($this->data[$field], FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate URL
     * 
     * @param string $field Field name
     * @return bool Is valid
     */
    protected function validateUrl($field)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        return filter_var($this->data[$field], FILTER_VALIDATE_URL) !== false;
    }
    
    /**
     * Validate numeric
     * 
     * @param string $field Field name
     * @return bool Is valid
     */
    protected function validateNumeric($field)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        return is_numeric($this->data[$field]);
    }
    
    /**
     * Validate integer
     * 
     * @param string $field Field name
     * @return bool Is valid
     */
    protected function validateInteger($field)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        return filter_var($this->data[$field], FILTER_VALIDATE_INT) !== false;
    }
    
    /**
     * Validate float
     * 
     * @param string $field Field name
     * @return bool Is valid
     */
    protected function validateFloat($field)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        return filter_var($this->data[$field], FILTER_VALIDATE_FLOAT) !== false;
    }
    
    /**
     * Validate minimum value
     * 
     * @param string $field Field name
     * @param mixed $min Minimum value
     * @return bool Is valid
     */
    protected function validateMin($field, $min)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        if (!is_numeric($this->data[$field])) {
            return false;
        }
        
        return floatval($this->data[$field]) >= floatval($min);
    }
    
    /**
     * Validate maximum value
     * 
     * @param string $field Field name
     * @param mixed $max Maximum value
     * @return bool Is valid
     */
    protected function validateMax($field, $max)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        if (!is_numeric($this->data[$field])) {
            return false;
        }
        
        return floatval($this->data[$field]) <= floatval($max);
    }
    
    /**
     * Validate minimum length
     * 
     * @param string $field Field name
     * @param int $length Minimum length
     * @return bool Is valid
     */
    protected function validateMinLength($field, $length)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        return mb_strlen($this->data[$field]) >= $length;
    }
    
    /**
     * Validate maximum length
     * 
     * @param string $field Field name
     * @param int $length Maximum length
     * @return bool Is valid
     */
    protected function validateMaxLength($field, $length)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        return mb_strlen($this->data[$field]) <= $length;
    }
    
    /**
     * Validate exact length
     * 
     * @param string $field Field name
     * @param int $length Exact length
     * @return bool Is valid
     */
    protected function validateExactLength($field, $length)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        return mb_strlen($this->data[$field]) === $length;
    }
    
    /**
     * Validate alpha
     * 
     * @param string $field Field name
     * @return bool Is valid
     */
    protected function validateAlpha($field)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        return ctype_alpha($this->data[$field]);
    }
    
    /**
     * Validate alphanumeric
     * 
     * @param string $field Field name
     * @return bool Is valid
     */
    protected function validateAlphaNumeric($field)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        return ctype_alnum($this->data[$field]);
    }
    
    /**
     * Validate alpha dash
     * 
     * @param string $field Field name
     * @return bool Is valid
     */
    protected function validateAlphaDash($field)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        return preg_match('/^[a-zA-Z0-9_-]+$/', $this->data[$field]) === 1;
    }
    
    /**
     * Validate regex
     * 
     * @param string $field Field name
     * @param string $pattern Regex pattern
     * @return bool Is valid
     */
    protected function validateRegex($field, $pattern)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        return preg_match($pattern, $this->data[$field]) === 1;
    }
    
    /**
     * Validate date
     * 
     * @param string $field Field name
     * @return bool Is valid
     */
    protected function validateDate($field)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        $date = date_parse($this->data[$field]);
        
        return $date['error_count'] === 0 && $date['warning_count'] === 0;
    }
    
    /**
     * Validate date format
     * 
     * @param string $field Field name
     * @param string $format Date format
     * @return bool Is valid
     */
    protected function validateDateFormat($field, $format)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        $date = \DateTime::createFromFormat($format, $this->data[$field]);
        
        return $date && $date->format($format) === $this->data[$field];
    }
    
    /**
     * Validate matches
     * 
     * @param string $field Field name
     * @param string $matchField Field to match
     * @return bool Is valid
     */
    protected function validateMatches($field, $matchField)
    {
        if (!isset($this->data[$field])) {
            return true; // Skip validation if field doesn't exist
        }
        
        return isset($this->data[$matchField]) && $this->data[$field] === $this->data[$matchField];
    }
    
    /**
     * Validate different
     * 
     * @param string $field Field name
     * @param string $differentField Field to be different from
     * @return bool Is valid
     */
    protected function validateDifferent($field, $differentField)
    {
        if (!isset($this->data[$field])) {
            return true; // Skip validation if field doesn't exist
        }
        
        return !isset($this->data[$differentField]) || $this->data[$field] !== $this->data[$differentField];
    }
    
    /**
     * Validate in
     * 
     * @param string $field Field name
     * @param string|array $values Allowed values
     * @return bool Is valid
     */
    protected function validateIn($field, $values)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        if (is_string($values)) {
            $values = explode(',', $values);
            $values = array_map('trim', $values);
        }
        
        return in_array($this->data[$field], $values);
    }
    
    /**
     * Validate not in
     * 
     * @param string $field Field name
     * @param string|array $values Disallowed values
     * @return bool Is valid
     */
    protected function validateNotIn($field, $values)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        if (is_string($values)) {
            $values = explode(',', $values);
            $values = array_map('trim', $values);
        }
        
        return !in_array($this->data[$field], $values);
    }
    
    /**
     * Validate unique
     * 
     * @param string $field Field name
     * @param array $params Table, column, and except
     * @return bool Is valid
     */
    protected function validateUnique($field, $params)
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return true; // Skip validation if empty
        }
        
        // Parse parameters
        if (is_string($params)) {
            $params = explode(',', $params);
            $params = array_map('trim', $params);
        }
        
        if (count($params) < 2) {
            throw new Exception('Unique validation requires table and column parameters');
        }
        
        $table = $params[0];
        $column = $params[1];
        $except = isset($params[2]) ? $params[2] : null;
        $exceptColumn = isset($params[3]) ? $params[3] : 'id';
        
        // Build query
        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = :value";
        $bindings = ['value' => $this->data[$field]];
        
        // Add except condition if needed
        if ($except !== null && isset($this->data[$except])) {
            $sql .= " AND {$exceptColumn} != :except";
            $bindings['except'] = $this->data[$except];
        }
        
        // Execute query
        $db = new Database();
        $count = $db->getValue($sql, $bindings);
        
        return $count === '0';
    }
}