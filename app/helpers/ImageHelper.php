<?php
/**
 * Image Helper Class
 * 
 * Helper functions for image processing and management
 */
class ImageHelper
{
    /**
     * Upload image
     * 
     * @param array $file $_FILES array item
     * @param string $destination Destination directory
     * @param string $filename Custom filename (optional, default is random)
     * @param array $options Additional options
     * @return string|false Image filename or false on error
     */
    public static function upload($file, $destination, $filename = null, $options = [])
    {
        // Default options
        $defaults = [
            'maxSize' => 5 * 1024 * 1024, // 5MB
            'allowedTypes' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            'maxWidth' => 2000,
            'maxHeight' => 2000,
            'minWidth' => 100,
            'minHeight' => 100,
            'resize' => false,
            'resizeWidth' => 800,
            'resizeHeight' => 600,
            'crop' => false,
            'cropWidth' => 400,
            'cropHeight' => 300,
            'cropPosition' => 'center',
            'watermark' => false,
            'watermarkImage' => null,
            'watermarkPosition' => 'center',
            'watermarkOpacity' => 70,
            'quality' => 90
        ];
        
        // Merge options
        $options = array_merge($defaults, $options);
        
        // Check if file is uploaded
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }
        
        // Validate file size
        if ($file['size'] > $options['maxSize']) {
            return false;
        }
        
        // Validate file type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $fileType = $finfo->file($file['tmp_name']);
        
        if (!in_array($fileType, $options['allowedTypes'])) {
            return false;
        }
        
        // Get image info
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return false;
        }
        
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        // Validate dimensions
        if ($width > $options['maxWidth'] || $height > $options['maxHeight']) {
            return false;
        }
        
        if ($width < $options['minWidth'] || $height < $options['minHeight']) {
            return false;
        }
        
        // Create destination directory if not exists
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        // Generate filename
        if (empty($filename)) {
            $filename = self::generateFilename($file['name']);
        } else {
            $filename = self::sanitizeFilename($filename);
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename .= '.' . $extension;
        }
        
        $filepath = $destination . '/' . $filename;
        
        // Simple upload if no processing needed
        if (!$options['resize'] && !$options['crop'] && !$options['watermark']) {
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                return $filename;
            }
            
            return false;
        }
        
        // Process image
        $image = self::createImage($file['tmp_name'], $fileType);
        if (!$image) {
            return false;
        }
        
        // Resize image
        if ($options['resize']) {
            $image = self::resize($image, $width, $height, $options['resizeWidth'], $options['resizeHeight']);
            $width = $options['resizeWidth'];
            $height = $options['resizeHeight'];
        }
        
        // Crop image
        if ($options['crop']) {
            $image = self::crop($image, $width, $height, $options['cropWidth'], $options['cropHeight'], $options['cropPosition']);
            $width = $options['cropWidth'];
            $height = $options['cropHeight'];
        }
        
        // Add watermark
        if ($options['watermark'] && !empty($options['watermarkImage']) && file_exists($options['watermarkImage'])) {
            $image = self::addWatermark($image, $width, $height, $options['watermarkImage'], $options['watermarkPosition'], $options['watermarkOpacity']);
        }
        
        // Save image
        $result = self::saveImage($image, $filepath, $fileType, $options['quality']);
        
        // Free memory
        imagedestroy($image);
        
        return $result ? $filename : false;
    }
    
    /**
     * Upload multiple images
     * 
     * @param array $files $_FILES array
     * @param string $destination Destination directory
     * @param array $options Additional options
     * @return array Uploaded filenames
     */
    public static function uploadMultiple($files, $destination, $options = [])
    {
        $uploaded = [];
        
        if (isset($files['name']) && is_array($files['name'])) {
            foreach ($files['name'] as $index => $name) {
                if ($files['error'][$index] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $files['name'][$index],
                        'type' => $files['type'][$index],
                        'tmp_name' => $files['tmp_name'][$index],
                        'error' => $files['error'][$index],
                        'size' => $files['size'][$index]
                    ];
                    
                    $filename = self::upload($file, $destination, null, $options);
                    
                    if ($filename) {
                        $uploaded[] = $filename;
                    }
                }
            }
        }
        
        return $uploaded;
    }
    
    /**
     * Resize image file
     * 
     * @param string $sourcePath Source image path
     * @param string $destinationPath Destination image path
     * @param int $width Width
     * @param int $height Height
     * @param int $quality Quality (0-100)
     * @return bool Success
     */
    public static function resizeFile($sourcePath, $destinationPath, $width, $height, $quality = 90)
    {
        if (!file_exists($sourcePath)) {
            return false;
        }
        
        // Get image info
        $imageInfo = getimagesize($sourcePath);
        if ($imageInfo === false) {
            return false;
        }
        
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];
        
        // Create image
        $sourceImage = self::createImage($sourcePath, $mimeType);
        if (!$sourceImage) {
            return false;
        }
        
        // Resize image
        $destinationImage = self::resize($sourceImage, $sourceWidth, $sourceHeight, $width, $height);
        
        // Save image
        $result = self::saveImage($destinationImage, $destinationPath, $mimeType, $quality);
        
        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($destinationImage);
        
        return $result;
    }
    
    /**
     * Crop image file
     * 
     * @param string $sourcePath Source image path
     * @param string $destinationPath Destination image path
     * @param int $width Width
     * @param int $height Height
     * @param string $position Crop position (center, top, bottom, left, right, top-left, top-right, bottom-left, bottom-right)
     * @param int $quality Quality (0-100)
     * @return bool Success
     */
    public static function cropFile($sourcePath, $destinationPath, $width, $height, $position = 'center', $quality = 90)
    {
        if (!file_exists($sourcePath)) {
            return false;
        }
        
        // Get image info
        $imageInfo = getimagesize($sourcePath);
        if ($imageInfo === false) {
            return false;
        }
        
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];
        
        // Create image
        $sourceImage = self::createImage($sourcePath, $mimeType);
        if (!$sourceImage) {
            return false;
        }
        
        // Crop image
        $destinationImage = self::crop($sourceImage, $sourceWidth, $sourceHeight, $width, $height, $position);
        
        // Save image
        $result = self::saveImage($destinationImage, $destinationPath, $mimeType, $quality);
        
        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($destinationImage);
        
        return $result;
    }
    
    /**
     * Add watermark to image file
     * 
     * @param string $sourcePath Source image path
     * @param string $destinationPath Destination image path
     * @param string $watermarkPath Watermark image path
     * @param string $position Watermark position (center, top, bottom, left, right, top-left, top-right, bottom-left, bottom-right)
     * @param int $opacity Watermark opacity (0-100)
     * @param int $quality Quality (0-100)
     * @return bool Success
     */
    public static function addWatermarkToFile($sourcePath, $destinationPath, $watermarkPath, $position = 'center', $opacity = 70, $quality = 90)
    {
        if (!file_exists($sourcePath) || !file_exists($watermarkPath)) {
            return false;
        }
        
        // Get image info
        $imageInfo = getimagesize($sourcePath);
        if ($imageInfo === false) {
            return false;
        }
        
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mimeType = $imageInfo['mime'];
        
        // Create image
        $image = self::createImage($sourcePath, $mimeType);
        if (!$image) {
            return false;
        }
        
        // Add watermark
        $image = self::addWatermark($image, $width, $height, $watermarkPath, $position, $opacity);
        
        // Save image
        $result = self::saveImage($image, $destinationPath, $mimeType, $quality);
        
        // Free memory
        imagedestroy($image);
        
        return $result;
    }
    
    /**
     * Create image from file
     * 
     * @param string $filepath File path
     * @param string $type File type
     * @return resource|false Image resource or false on error
     */
    private static function createImage($filepath, $type)
    {
        switch ($type) {
            case 'image/jpeg':
                return imagecreatefromjpeg($filepath);
            case 'image/png':
                return imagecreatefrompng($filepath);
            case 'image/gif':
                return imagecreatefromgif($filepath);
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    return imagecreatefromwebp($filepath);
                }
                break;
        }
        
        return false;
    }
    
    /**
     * Save image to file
     * 
     * @param resource $image Image resource
     * @param string $filepath File path
     * @param string $type File type
     * @param int $quality Quality (0-100)
     * @return bool Success
     */
    private static function saveImage($image, $filepath, $type, $quality = 90)
    {
        switch ($type) {
            case 'image/jpeg':
                return imagejpeg($image, $filepath, $quality);
            case 'image/png':
                // Convert quality to PNG compression level (0-9)
                $pngQuality = round((100 - $quality) / 11.111111);
                return imagepng($image, $filepath, $pngQuality);
            case 'image/gif':
                return imagegif($image, $filepath);
            case 'image/webp':
                if (function_exists('imagewebp')) {
                    return imagewebp($image, $filepath, $quality);
                }
                break;
        }
        
        return false;
    }
    
    /**
     * Resize image
     * 
     * @param resource $image Image resource
     * @param int $currentWidth Current width
     * @param int $currentHeight Current height
     * @param int $newWidth New width
     * @param int $newHeight New height
     * @return resource Resized image
     */
    private static function resize($image, $currentWidth, $currentHeight, $newWidth, $newHeight)
    {
        // Calculate dimensions
        if ($newWidth === 0 && $newHeight === 0) {
            $newWidth = $currentWidth;
            $newHeight = $currentHeight;
        } elseif ($newWidth === 0) {
            $newWidth = round($currentWidth * $newHeight / $currentHeight);
        } elseif ($newHeight === 0) {
            $newHeight = round($currentHeight * $newWidth / $currentWidth);
        }
        
        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency
        if (imageistruecolor($image) && imagecolortransparent($image) >= 0) {
            $transparent = imagecolortransparent($image);
            $rgb = imagecolorsforindex($image, $transparent);
            $transparent = imagecolorallocate($newImage, $rgb['red'], $rgb['green'], $rgb['blue']);
            imagefill($newImage, 0, 0, $transparent);
            imagecolortransparent($newImage, $transparent);
        } elseif (imageistruecolor($image)) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
            imagefill($newImage, 0, 0, $transparent);
        }
        
        // Resize image
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $currentWidth, $currentHeight);
        
        return $newImage;
    }
    
    /**
     * Crop image
     * 
     * @param resource $image Image resource
     * @param int $currentWidth Current width
     * @param int $currentHeight Current height
     * @param int $cropWidth Crop width
     * @param int $cropHeight Crop height
     * @param string $position Crop position (center, top, bottom, left, right, top-left, top-right, bottom-left, bottom-right)
     * @return resource Cropped image
     */
    private static function crop($image, $currentWidth, $currentHeight, $cropWidth, $cropHeight, $position = 'center')
    {
        // Calculate crop coordinates
        $x = 0;
        $y = 0;
        
        switch ($position) {
            case 'center':
                $x = max(0, round(($currentWidth - $cropWidth) / 2));
                $y = max(0, round(($currentHeight - $cropHeight) / 2));
                break;
            case 'top':
                $x = max(0, round(($currentWidth - $cropWidth) / 2));
                $y = 0;
                break;
            case 'bottom':
                $x = max(0, round(($currentWidth - $cropWidth) / 2));
                $y = max(0, $currentHeight - $cropHeight);
                break;
            case 'left':
                $x = 0;
                $y = max(0, round(($currentHeight - $cropHeight) / 2));
                break;
            case 'right':
                $x = max(0, $currentWidth - $cropWidth);
                $y = max(0, round(($currentHeight - $cropHeight) / 2));
                break;
            case 'top-left':
                $x = 0;
                $y = 0;
                break;
            case 'top-right':
                $x = max(0, $currentWidth - $cropWidth);
                $y = 0;
                break;
            case 'bottom-left':
                $x = 0;
                $y = max(0, $currentHeight - $cropHeight);
                break;
            case 'bottom-right':
                $x = max(0, $currentWidth - $cropWidth);
                $y = max(0, $currentHeight - $cropHeight);
                break;
        }
        
        // Create new image
        $newImage = imagecreatetruecolor($cropWidth, $cropHeight);
        
        // Preserve transparency
        if (imageistruecolor($image) && imagecolortransparent($image) >= 0) {
            $transparent = imagecolortransparent($image);
            $rgb = imagecolorsforindex($image, $transparent);
            $transparent = imagecolorallocate($newImage, $rgb['red'], $rgb['green'], $rgb['blue']);
            imagefill($newImage, 0, 0, $transparent);
            imagecolortransparent($newImage, $transparent);
        } elseif (imageistruecolor($image)) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
            imagefill($newImage, 0, 0, $transparent);
        }
        
        // Crop image
        imagecopy($newImage, $image, 0, 0, $x, $y, $cropWidth, $cropHeight);
        
        return $newImage;
    }
    
    /**
     * Add watermark to image
     * 
     * @param resource $image Image resource
     * @param int $imageWidth Image width
     * @param int $imageHeight Image height
     * @param string $watermarkPath Watermark image path
     * @param string $position Watermark position (center, top, bottom, left, right, top-left, top-right, bottom-left, bottom-right)
     * @param int $opacity Watermark opacity (0-100)
     * @return resource Image with watermark
     */
    private static function addWatermark($image, $imageWidth, $imageHeight, $watermarkPath, $position = 'center', $opacity = 70)
    {
        // Get watermark info
        $watermarkInfo = getimagesize($watermarkPath);
        if ($watermarkInfo === false) {
            return $image;
        }
        
        $watermarkWidth = $watermarkInfo[0];
        $watermarkHeight = $watermarkInfo[1];
        $watermarkType = $watermarkInfo['mime'];
        
        // Create watermark image
        $watermark = self::createImage($watermarkPath, $watermarkType);
        if (!$watermark) {
            return $image;
        }
        
        // Calculate position
        $x = 0;
        $y = 0;
        
        switch ($position) {
            case 'center':
                $x = max(0, round(($imageWidth - $watermarkWidth) / 2));
                $y = max(0, round(($imageHeight - $watermarkHeight) / 2));
                break;
            case 'top':
                $x = max(0, round(($imageWidth - $watermarkWidth) / 2));
                $y = 10;
                break;
            case 'bottom':
                $x = max(0, round(($imageWidth - $watermarkWidth) / 2));
                $y = max(0, $imageHeight - $watermarkHeight - 10);
                break;
            case 'left':
                $x = 10;
                $y = max(0, round(($imageHeight - $watermarkHeight) / 2));
                break;
            case 'right':
                $x = max(0, $imageWidth - $watermarkWidth - 10);
                $y = max(0, round(($imageHeight - $watermarkHeight) / 2));
                break;
            case 'top-left':
                $x = 10;
                $y = 10;
                break;
            case 'top-right':
                $x = max(0, $imageWidth - $watermarkWidth - 10);
                $y = 10;
                break;
            case 'bottom-left':
                $x = 10;
                $y = max(0, $imageHeight - $watermarkHeight - 10);
                break;
            case 'bottom-right':
                $x = max(0, $imageWidth - $watermarkWidth - 10);
                $y = max(0, $imageHeight - $watermarkHeight - 10);
                break;
        }
        
        // Apply opacity
        if ($opacity < 100) {
            $opacity = $opacity / 100;
            self::imageAlphaBlending($watermark, $opacity);
        }
        
        // Add watermark
        imagecopy($image, $watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);
        
        // Free memory
        imagedestroy($watermark);
        
        return $image;
    }
    
    /**
     * Apply alpha blending to image
     * 
     * @param resource $image Image resource
     * @param float $opacity Opacity (0-1)
     */
    private static function imageAlphaBlending($image, $opacity)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Create temporary image
        $temp = imagecreatetruecolor($width, $height);
        
        // Preserve transparency
        imagealphablending($temp, false);
        imagesavealpha($temp, true);
        $transparent = imagecolorallocatealpha($temp, 0, 0, 0, 127);
        imagefill($temp, 0, 0, $transparent);
        
        // Copy image
        imagecopy($temp, $image, 0, 0, 0, 0, $width, $height);
        
        // Apply opacity
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $colorIndex = imagecolorat($temp, $x, $y);
                $color = imagecolorsforindex($temp, $colorIndex);
                
                $alpha = 127 - round((127 - $color['alpha']) * $opacity);
                $alpha = max(0, min(127, $alpha));
                
                $newColor = imagecolorallocatealpha($image, $color['red'], $color['green'], $color['blue'], $alpha);
                imagesetpixel($image, $x, $y, $newColor);
            }
        }
        
        // Free memory
        imagedestroy($temp);
    }
    
    /**
     * Generate random filename
     * 
     * @param string $originalName Original filename
     * @return string Random filename
     */
    public static function generateFilename($originalName)
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $filename = bin2hex(random_bytes(16));
        
        return $filename . '.' . $extension;
    }
    
    /**
     * Sanitize filename
     * 
     * @param string $filename Filename
     * @return string Sanitized filename
     */
    public static function sanitizeFilename($filename)
    {
        // Remove file extension
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        
        // Replace non-alphanumeric characters with hyphens
        $filename = preg_replace('/[^a-zA-Z0-9-_]/', '-', $filename);
        
        // Replace multiple hyphens with single hyphen
        $filename = preg_replace('/-+/', '-', $filename);
        
        // Trim hyphens from beginning and end
        $filename = trim($filename, '-');
        
        // Convert to lowercase
        $filename = strtolower($filename);
        
        return $filename;
    }
    
    /**
     * Get image dimensions
     * 
     * @param string $filepath Image file path
     * @return array|false Width and height or false on error
     */
    public static function getDimensions($filepath)
    {
        if (!file_exists($filepath)) {
            return false;
        }
        
        $imageInfo = getimagesize($filepath);
        if ($imageInfo === false) {
            return false;
        }
        
        return [
            'width' => $imageInfo[0],
            'height' => $imageInfo[1]
        ];
    }
    
    /**
     * Check if file is image
     * 
     * @param string $filepath File path
     * @return bool Is image
     */
    public static function isImage($filepath)
    {
        if (!file_exists($filepath)) {
            return false;
        }
        
        // Get MIME type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($filepath);
        
        return strpos($mimeType, 'image/') === 0;
    }
    
    /**
     * Delete image
     * 
     * @param string $filepath Image file path
     * @return bool Success
     */
    public static function delete($filepath)
    {
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        
        return false;
    }
    
    /**
     * Create thumbnail
     * 
     * @param string $sourcePath Source image path
     * @param string $destinationPath Destination thumbnail path
     * @param int $width Thumbnail width
     * @param int $height Thumbnail height
     * @param string $mode Thumbnail mode (fit, crop)
     * @param int $quality Thumbnail quality (0-100)
     * @return bool Success
     */
    public static function createThumbnail($sourcePath, $destinationPath, $width, $height, $mode = 'fit', $quality = 90)
    {
        if ($mode === 'crop') {
            return self::cropFile($sourcePath, $destinationPath, $width, $height, 'center', $quality);
        } else {
            return self::resizeFile($sourcePath, $destinationPath, $width, $height, $quality);
        }
    }
    
    /**
     * Create multiple thumbnails
     * 
     * @param string $sourcePath Source image path
     * @param array $thumbnails Thumbnails configuration
     * @return array Created thumbnails
     */
    public static function createMultipleThumbnails($sourcePath, $thumbnails)
    {
        $created = [];
        
        foreach ($thumbnails as $name => $config) {
            $width = $config['width'] ?? 0;
            $height = $config['height'] ?? 0;
            $mode = $config['mode'] ?? 'fit';
            $quality = $config['quality'] ?? 90;
            $destinationPath = $config['path'] ?? '';
            
            if (!empty($destinationPath)) {
                $result = self::createThumbnail($sourcePath, $destinationPath, $width, $height, $mode, $quality);
                
                if ($result) {
                    $created[$name] = basename($destinationPath);
                }
            }
        }
        
        return $created;
    }
    
    /**
     * Get random image from directory
     * 
     * @param string $directory Directory path
     * @param string $extension File extension (jpg, png, gif, etc.) or empty for all image types
     * @return string|false Random image filename or false on error
     */
    public static function getRandomImage($directory, $extension = '')
    {
        if (!is_dir($directory)) {
            return false;
        }
        
        $images = self::getImagesFromDirectory($directory, $extension);
        
        if (empty($images)) {
            return false;
        }
        
        return $images[array_rand($images)];
    }
    
    /**
     * Get images from directory
     * 
     * @param string $directory Directory path
     * @param string $extension File extension (jpg, png, gif, etc.) or empty for all image types
     * @return array Image filenames
     */
    public static function getImagesFromDirectory($directory, $extension = '')
    {
        $images = [];
        
        if (!is_dir($directory)) {
            return $images;
        }
        
        $files = scandir($directory);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $filepath = $directory . '/' . $file;
            
            if (is_file($filepath) && self::isImage($filepath)) {
                if (empty($extension) || pathinfo($file, PATHINFO_EXTENSION) === $extension) {
                    $images[] = $file;
                }
            }
        }
        
        return $images;
    }
    
    /**
     * Compress image
     * 
     * @param string $sourcePath Source image path
     * @param string $destinationPath Destination image path
     * @param int $quality Quality (0-100)
     * @return bool Success
     */
    public static function compressImage($sourcePath, $destinationPath, $quality = 75)
    {
        if (!file_exists($sourcePath)) {
            return false;
        }
        
        // Get image info
        $imageInfo = getimagesize($sourcePath);
        if ($imageInfo === false) {
            return false;
        }
        
        $mimeType = $imageInfo['mime'];
        
        // Create image
        $image = self::createImage($sourcePath, $mimeType);
        if (!$image) {
            return false;
        }
        
        // Save image with lower quality
        $result = self::saveImage($image, $destinationPath, $mimeType, $quality);
        
        // Free memory
        imagedestroy($image);
        
        return $result;
    }
    
    /**
     * Create placeholder image
     * 
     * @param string $destinationPath Destination image path
     * @param int $width Width
     * @param int $height Height
     * @param string $text Text
     * @param string $backgroundColor Background color (hex)
     * @param string $textColor Text color (hex)
     * @return bool Success
     */
    public static function createPlaceholder($destinationPath, $width, $height, $text = '', $backgroundColor = '#EEEEEE', $textColor = '#AAAAAA')
    {
        // Create image
        $image = imagecreatetruecolor($width, $height);
        
        // Convert hex to RGB
        $bgColor = self::hexToRgb($backgroundColor);
        $txtColor = self::hexToRgb($textColor);
        
        // Allocate colors
        $bgColorAllocated = imagecolorallocate($image, $bgColor['r'], $bgColor['g'], $bgColor['b']);
        $txtColorAllocated = imagecolorallocate($image, $txtColor['r'], $txtColor['g'], $txtColor['b']);
        
        // Fill background
        imagefill($image, 0, 0, $bgColorAllocated);
        
        // Add text
        if (!empty($text)) {
            // Use default text if no text provided
            $displayText = $text ?: "{$width}x{$height}";
            
            // Calculate font size
            $fontSize = min($width, $height) / 10;
            $fontSize = max(8, min(48, $fontSize));
            
            // Get text dimensions
            $textBbox = imagettfbbox($fontSize, 0, 'arial', $displayText);
            $textWidth = $textBbox[2] - $textBbox[0];
            $textHeight = $textBbox[1] - $textBbox[7];
            
            // Calculate position
            $textX = ($width - $textWidth) / 2;
            $textY = ($height + $textHeight) / 2;
            
            // Add text
            imagettftext($image, $fontSize, 0, $textX, $textY, $txtColorAllocated, 'arial', $displayText);
        }
        
        // Save image
        $extension = pathinfo($destinationPath, PATHINFO_EXTENSION);
        $result = false;
        
        switch (strtolower($extension)) {
            case 'jpg':
            case 'jpeg':
                $result = imagejpeg($image, $destinationPath, 90);
                break;
            case 'png':
                $result = imagepng($image, $destinationPath, 0);
                break;
            case 'gif':
                $result = imagegif($image, $destinationPath);
                break;
            case 'webp':
                if (function_exists('imagewebp')) {
                    $result = imagewebp($image, $destinationPath, 90);
                }
                break;
        }
        
        // Free memory
        imagedestroy($image);
        
        return $result;
    }
    
    /**
     * Convert hex color to RGB
     * 
     * @param string $hex Hex color
     * @return array RGB color
     */
    private static function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) === 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        
        return [
            'r' => $r,
            'g' => $g,
            'b' => $b
        ];
    }
}