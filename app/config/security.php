<?php
/**
 * Security Configuration
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Security configuration settings for the UPHSL website
 */

// Encryption key (CHANGE THIS IN PRODUCTION!)
// Generate a secure key: openssl rand -base64 32
if (!defined('ENCRYPTION_KEY')) {
    // In production, load from environment variable or secure config file
    define('ENCRYPTION_KEY', hash('sha256', 'uphsl_secret_key_change_in_production_' . __DIR__));
}

// Security Settings
return [
    // CSRF Protection
    'csrf' => [
        'enabled' => true,
        'token_lifetime' => 3600, // 1 hour
    ],
    
    // Rate Limiting
    'rate_limiting' => [
        'enabled' => true,
        'login_max_attempts' => 5,
        'login_window' => 900, // 15 minutes
        'api_max_attempts' => 60,
        'api_window' => 60, // 1 minute
    ],
    
    // Session Security
    'session' => [
        'timeout' => 1800, // 30 minutes
        'regenerate_interval' => 300, // 5 minutes
        'cookie_httponly' => true,
        'cookie_secure' => false, // Set to true in production with HTTPS
        'cookie_samesite' => 'Strict',
    ],
    
    // Password Requirements
    'password' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_number' => true,
        'require_special' => false,
    ],
    
    // Security Headers
    'headers' => [
        'x_frame_options' => 'SAMEORIGIN',
        'x_xss_protection' => '1; mode=block',
        'x_content_type_options' => 'nosniff',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'hsts_max_age' => 31536000, // 1 year
        'hsts_include_subdomains' => true,
    ],
    
    // Content Security Policy
    'csp' => [
        'default_src' => "'self'",
        'script_src' => "'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://cdn.quilljs.com https://fonts.googleapis.com https://maps.googleapis.com https://connect.facebook.net",
        'style_src' => "'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.quilljs.com https://fonts.googleapis.com",
        'font_src' => "'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com data:",
        'img_src' => "'self' data: https: https://*.fbcdn.net",
        'frame_src' => "'self' https://www.facebook.com https://web.facebook.com https://www.google.com",
        'connect_src' => "'self' https://www.facebook.com https://web.facebook.com https://connect.facebook.net https://staticxx.facebook.com https://maps.googleapis.com",
    ],
    
    // Allowed file upload types
    'uploads' => [
        'allowed_types' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        'max_size' => 10 * 1024 * 1024, // 10MB
        'max_width' => 1920,
        'max_height' => 1080,
    ],
    
    // IP Whitelist/Blacklist (optional)
    'ip_filtering' => [
        'enabled' => false,
        'whitelist' => [],
        'blacklist' => [],
    ],
];

