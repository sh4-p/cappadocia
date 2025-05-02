<?php
/**
 * Date Helper Class
 * 
 * Helper functions for date and time handling
 */
class DateHelper
{
    /**
     * Format date
     * 
     * @param string|int $date Date string or timestamp
     * @param string $format Output format
     * @param string $inputFormat Input format (if date is string)
     * @return string Formatted date
     */
    public static function format($date, $format = 'Y-m-d', $inputFormat = null)
    {
        if (is_numeric($date)) {
            // Date is timestamp
            return date($format, $date);
        } else {
            // Date is string
            $datetime = $inputFormat 
                ? \DateTime::createFromFormat($inputFormat, $date) 
                : new \DateTime($date);
            
            if (!$datetime) {
                return '';
            }
            
            return $datetime->format($format);
        }
    }
    
    /**
     * Format date for database (Y-m-d)
     * 
     * @param string|int $date Date string or timestamp
     * @param string $inputFormat Input format (if date is string)
     * @return string Formatted date
     */
    public static function formatForDatabase($date, $inputFormat = null)
    {
        return self::format($date, 'Y-m-d', $inputFormat);
    }
    
    /**
     * Format date for humans
     * 
     * @param string|int $date Date string or timestamp
     * @param string $inputFormat Input format (if date is string)
     * @return string Formatted date
     */
    public static function formatForHumans($date, $inputFormat = null)
    {
        return self::format($date, 'd M Y', $inputFormat);
    }
    
    /**
     * Format datetime for humans
     * 
     * @param string|int $date Date string or timestamp
     * @param string $inputFormat Input format (if date is string)
     * @return string Formatted datetime
     */
    public static function formatDatetimeForHumans($date, $inputFormat = null)
    {
        return self::format($date, 'd M Y, H:i', $inputFormat);
    }
    
    /**
     * Get current date
     * 
     * @param string $format Output format
     * @return string Current date
     */
    public static function now($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }
    
    /**
     * Get current timestamp
     * 
     * @return int Current timestamp
     */
    public static function timestamp()
    {
        return time();
    }
    
    /**
     * Convert date to timestamp
     * 
     * @param string $date Date string
     * @param string $format Date format
     * @return int Timestamp
     */
    public static function toTimestamp($date, $format = null)
    {
        if ($format) {
            $datetime = \DateTime::createFromFormat($format, $date);
            return $datetime ? $datetime->getTimestamp() : 0;
        } else {
            return strtotime($date);
        }
    }
    
    /**
     * Check if date is valid
     * 
     * @param string $date Date string
     * @param string $format Date format
     * @return bool Is valid
     */
    public static function isValid($date, $format = 'Y-m-d')
    {
        if ($format) {
            $datetime = \DateTime::createFromFormat($format, $date);
            return $datetime && $datetime->format($format) === $date;
        } else {
            return strtotime($date) !== false;
        }
    }
    
    /**
     * Get date difference
     * 
     * @param string|int $date1 First date
     * @param string|int $date2 Second date
     * @param string $unit Difference unit (seconds, minutes, hours, days, months, years)
     * @return int Difference
     */
    public static function diff($date1, $date2, $unit = 'days')
    {
        // Convert to timestamps
        $timestamp1 = is_numeric($date1) ? $date1 : strtotime($date1);
        $timestamp2 = is_numeric($date2) ? $date2 : strtotime($date2);
        
        // Calculate difference in seconds
        $diffSeconds = abs($timestamp2 - $timestamp1);
        
        // Convert to requested unit
        switch ($unit) {
            case 'seconds':
                return $diffSeconds;
            case 'minutes':
                return round($diffSeconds / 60);
            case 'hours':
                return round($diffSeconds / 3600);
            case 'days':
                return round($diffSeconds / 86400);
            case 'months':
                return round($diffSeconds / 2592000); // 30 days
            case 'years':
                return round($diffSeconds / 31536000); // 365 days
            default:
                return $diffSeconds;
        }
    }
    
    /**
     * Get relative time (e.g. "2 hours ago")
     * 
     * @param string|int $date Date string or timestamp
     * @return string Relative time
     */
    public static function relativeTime($date)
    {
        // Convert to timestamp
        $timestamp = is_numeric($date) ? $date : strtotime($date);
        $difference = time() - $timestamp;
        
        if ($difference < 60) {
            return $difference . ' seconds ago';
        } elseif ($difference < 3600) {
            $minutes = round($difference / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($difference < 86400) {
            $hours = round($difference / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($difference < 604800) {
            $days = round($difference / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } elseif ($difference < 2592000) {
            $weeks = round($difference / 604800);
            return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
        } elseif ($difference < 31536000) {
            $months = round($difference / 2592000);
            return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
        } else {
            $years = round($difference / 31536000);
            return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
        }
    }
    
    /**
     * Add time to date
     * 
     * @param string|int $date Date string or timestamp
     * @param int $value Value to add
     * @param string $unit Unit (seconds, minutes, hours, days, months, years)
     * @param string $format Output format
     * @return string Modified date
     */
    public static function add($date, $value, $unit = 'days', $format = 'Y-m-d')
    {
        // Convert to timestamp
        $timestamp = is_numeric($date) ? $date : strtotime($date);
        
        // Add time
        switch ($unit) {
            case 'seconds':
                $timestamp += $value;
                break;
            case 'minutes':
                $timestamp += $value * 60;
                break;
            case 'hours':
                $timestamp += $value * 3600;
                break;
            case 'days':
                $timestamp += $value * 86400;
                break;
            case 'months':
                $timestamp = strtotime("+{$value} months", $timestamp);
                break;
            case 'years':
                $timestamp = strtotime("+{$value} years", $timestamp);
                break;
        }
        
        return date($format, $timestamp);
    }
    
    /**
     * Subtract time from date
     * 
     * @param string|int $date Date string or timestamp
     * @param int $value Value to subtract
     * @param string $unit Unit (seconds, minutes, hours, days, months, years)
     * @param string $format Output format
     * @return string Modified date
     */
    public static function subtract($date, $value, $unit = 'days', $format = 'Y-m-d')
    {
        return self::add($date, -$value, $unit, $format);
    }
    
    /**
     * Get days between two dates
     * 
     * @param string|int $startDate Start date
     * @param string|int $endDate End date
     * @param string $format Output format
     * @return array Days between
     */
    public static function getDaysBetween($startDate, $endDate, $format = 'Y-m-d')
    {
        // Convert to timestamps
        $startTimestamp = is_numeric($startDate) ? $startDate : strtotime($startDate);
        $endTimestamp = is_numeric($endDate) ? $endDate : strtotime($endDate);
        
        // Swap if end date is before start date
        if ($endTimestamp < $startTimestamp) {
            $temp = $startTimestamp;
            $startTimestamp = $endTimestamp;
            $endTimestamp = $temp;
        }
        
        $days = [];
        $currentTimestamp = $startTimestamp;
        
        while ($currentTimestamp <= $endTimestamp) {
            $days[] = date($format, $currentTimestamp);
            $currentTimestamp += 86400; // 1 day in seconds
        }
        
        return $days;
    }
    
    /**
     * Get month name
     * 
     * @param int $month Month number (1-12)
     * @param bool $short Short name
     * @return string Month name
     */
    public static function getMonthName($month, $short = false)
    {
        $months = [
            1 => $short ? 'Jan' : 'January',
            2 => $short ? 'Feb' : 'February',
            3 => $short ? 'Mar' : 'March',
            4 => $short ? 'Apr' : 'April',
            5 => $short ? 'May' : 'May',
            6 => $short ? 'Jun' : 'June',
            7 => $short ? 'Jul' : 'July',
            8 => $short ? 'Aug' : 'August',
            9 => $short ? 'Sep' : 'September',
            10 => $short ? 'Oct' : 'October',
            11 => $short ? 'Nov' : 'November',
            12 => $short ? 'Dec' : 'December'
        ];
        
        return isset($months[$month]) ? $months[$month] : '';
    }
    
    /**
     * Get day name
     * 
     * @param int $day Day number (0-6, 0 = Sunday)
     * @param bool $short Short name
     * @return string Day name
     */
    public static function getDayName($day, $short = false)
    {
        $days = [
            0 => $short ? 'Sun' : 'Sunday',
            1 => $short ? 'Mon' : 'Monday',
            2 => $short ? 'Tue' : 'Tuesday',
            3 => $short ? 'Wed' : 'Wednesday',
            4 => $short ? 'Thu' : 'Thursday',
            5 => $short ? 'Fri' : 'Friday',
            6 => $short ? 'Sat' : 'Saturday'
        ];
        
        return isset($days[$day]) ? $days[$day] : '';
    }
    
    /**
     * Get days in month
     * 
     * @param int $month Month (1-12)
     * @param int $year Year
     * @return int Days in month
     */
    public static function getDaysInMonth($month, $year)
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }
    
    /**
     * Check if date is in the future
     * 
     * @param string|int $date Date string or timestamp
     * @return bool Is in future
     */
    public static function isFuture($date)
    {
        $timestamp = is_numeric($date) ? $date : strtotime($date);
        return $timestamp > time();
    }
    
    /**
     * Check if date is in the past
     * 
     * @param string|int $date Date string or timestamp
     * @return bool Is in past
     */
    public static function isPast($date)
    {
        $timestamp = is_numeric($date) ? $date : strtotime($date);
        return $timestamp < time();
    }
    
    /**
     * Check if date is today
     * 
     * @param string|int $date Date string or timestamp
     * @return bool Is today
     */
    public static function isToday($date)
    {
        $timestamp = is_numeric($date) ? $date : strtotime($date);
        return date('Y-m-d', $timestamp) === date('Y-m-d');
    }
    
    /**
     * Get age from date of birth
     * 
     * @param string|int $dob Date of birth
     * @return int Age
     */
    public static function getAge($dob)
    {
        $timestamp = is_numeric($dob) ? $dob : strtotime($dob);
        $dobObject = new \DateTime(date('Y-m-d', $timestamp));
        $nowObject = new \DateTime();
        
        $diff = $dobObject->diff($nowObject);
        
        return $diff->y;
    }
}