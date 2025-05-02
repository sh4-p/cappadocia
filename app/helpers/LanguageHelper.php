
<?php
/**
 * Language Helper Class
 * 
 * Helper functions for language handling in views
 */
class LanguageHelper
{
    /**
     * Get translation
     * 
     * @param string $key Translation key
     * @param array $params Replacement parameters
     * @return string Translated text
     */
    public static function translate($key, $params = [])
    {
        global $language;
        
        if (!isset($language)) {
            $language = new Language();
        }
        
        $translation = $language->get($key);
        
        // Replace parameters
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $translation = str_replace(':' . $param, $value, $translation);
            }
        }
        
        return $translation;
    }
    
    /**
     * Translate and echo
     * 
     * @param string $key Translation key
     * @param array $params Replacement parameters
     */
    public static function _e($key, $params = [])
    {
        echo self::translate($key, $params);
    }
    
    /**
     * Get language switcher HTML
     * 
     * @param string $currentUrl Current URL
     * @return string HTML
     */
    public static function languageSwitcher($currentUrl = '')
    {
        global $language;
        
        if (!isset($language)) {
            $language = new Language();
        }
        
        $availableLanguages = $language->getAvailableLanguages();
        $currentLanguage = $language->getCurrentLanguage();
        
        // Remove language from URL if exists
        $urlParts = explode('/', trim($currentUrl, '/'));
        
        if (isset($urlParts[0]) && array_key_exists($urlParts[0], $availableLanguages)) {
            array_shift($urlParts);
        }
        
        $urlWithoutLang = implode('/', $urlParts);
        
        $html = '<div class="language-switcher">';
        $html .= '<ul>';
        
        foreach ($availableLanguages as $code => $name) {
            $activeClass = ($code == $currentLanguage) ? 'active' : '';
            $langUrl = rtrim(APP_URL, '/') . '/' . $code . '/' . $urlWithoutLang;
            
            $html .= '<li class="' . $activeClass . '">';
            $html .= '<a href="' . $langUrl . '">';
            $html .= '<img src="' . UPLOADS_URL . '/flags/' . $code . '.png" alt="' . $name . '">';
            $html .= '<span>' . $name . '</span>';
            $html .= '</a>';
            $html .= '</li>';
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Format URL with language
     * 
     * @param string $url URL
     * @param string $langCode Language code (if null, use current language)
     * @return string Formatted URL
     */
    public static function url($url, $langCode = null)
    {
        global $language;
        
        if (!isset($language)) {
            $language = new Language();
        }
        
        $langCode = $langCode ?? $language->getCurrentLanguage();
        
        // Remove leading slash
        $url = ltrim($url, '/');
        
        // Add language to URL
        return APP_URL . '/' . $langCode . '/' . $url;
    }
    
    /**
     * Get current language name
     * 
     * @return string Language name
     */
    public static function getCurrentLanguageName()
    {
        global $language;
        
        if (!isset($language)) {
            $language = new Language();
        }
        
        $availableLanguages = $language->getAvailableLanguages();
        $currentLanguage = $language->getCurrentLanguage();
        
        return $availableLanguages[$currentLanguage] ?? '';
    }
    
    /**
     * Check if a string is translated
     * 
     * @param string $key Translation key
     * @return bool Is translated
     */
    public static function hasTranslation($key)
    {
        global $language;
        
        if (!isset($language)) {
            $language = new Language();
        }
        
        $translations = $language->getAll();
        
        return isset($translations[$key]);
    }
    
    /**
     * Add translation to database
     * 
     * @param string $key Translation key
     * @param array $translations Translations for each language
     * @return bool Success
     */
    public static function addTranslation($key, $translations)
    {
        global $language;
        
        if (!isset($language)) {
            $language = new Language();
        }
        
        return $language->addTranslation($key, $translations);
    }
    
    /**
     * Update translation in database
     * 
     * @param string $key Translation key
     * @param string $langCode Language code
     * @param string $value Translation value
     * @return bool Success
     */
    public static function updateTranslation($key, $langCode, $value)
    {
        global $language;
        
        if (!isset($language)) {
            $language = new Language();
        }
        
        return $language->updateTranslation($key, $langCode, $value);
    }
}

// Define shorthand function for translation
if (!function_exists('__')) {
    /**
     * Translate text
     * 
     * @param string $key Translation key
     * @param array $params Replacement parameters
     * @return string Translated text
     */
    function __($key, $params = [])
    {
        return LanguageHelper::translate($key, $params);
    }
}

// Define shorthand function for translation and echo
if (!function_exists('_e')) {
    /**
     * Translate and echo text
     * 
     * @param string $key Translation key
     * @param array $params Replacement parameters
     */
    function _e($key, $params = [])
    {
        LanguageHelper::_e($key, $params);
    }
}