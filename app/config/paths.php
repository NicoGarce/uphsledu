<?php
/**
 * Path Configuration
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Automatic path detection for development and production environments
 */

// Detect if we're in development (XAMPP) or production (cPanel)
function getBasePath() {
    // Get the current script directory
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    
    // Remove trailing slashes
    $scriptDir = rtrim($scriptDir, '/');
    
    // If we're in the root directory (production), return empty string
    if ($scriptDir === '' || $scriptDir === '/') {
        return '';
    }
    
    // If we're in a subdirectory (development), return the subdirectory path
    return $scriptDir . '/';
}

// Set the base path
$base_path = getBasePath();

// Alternative method: Check for specific development indicators
if (isset($_SERVER['HTTP_HOST'])) {
    $host = $_SERVER['HTTP_HOST'];
    
    // Development indicators
    if (strpos($host, 'localhost') !== false || 
        strpos($host, '127.0.0.1') !== false || 
        strpos($host, 'uphsledu') !== false) {
        // This is development - use subdirectory path
        $base_path = '/uphsledu/';
    } else {
        // This is production - use root path
        $base_path = '/';
    }
}

// Ensure base_path ends with a slash
if (!empty($base_path) && substr($base_path, -1) !== '/') {
    $base_path .= '/';
}

// Make base_path available globally
if (!isset($GLOBALS['base_path'])) {
    $GLOBALS['base_path'] = $base_path;
}
?>
