<?php
/**
 * Laravel-like Security Features for UPHSL Website
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Comprehensive security features including CSRF protection, XSS prevention, rate limiting, and more
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Security Configuration
 */
class SecurityConfig {
    // CSRF Token lifetime (in seconds)
    const CSRF_TOKEN_LIFETIME = 3600; // 1 hour
    
    // Rate limiting
    const RATE_LIMIT_ENABLED = true;
    const RATE_LIMIT_MAX_ATTEMPTS = 5;
    const RATE_LIMIT_WINDOW = 900; // 15 minutes
    
    // Session security
    const SESSION_TIMEOUT = 1800; // 30 minutes
    const SESSION_REGENERATE_INTERVAL = 300; // 5 minutes
    
    // Password requirements
    const PASSWORD_MIN_LENGTH = 8;
    const PASSWORD_REQUIRE_UPPERCASE = true;
    const PASSWORD_REQUIRE_LOWERCASE = true;
    const PASSWORD_REQUIRE_NUMBER = true;
    const PASSWORD_REQUIRE_SPECIAL = false;
}

/**
 * CSRF Protection
 */
class CSRF {
    /**
     * Generate and store CSRF token
     */
    public static function token() {
        if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time']) || 
            (time() - $_SESSION['csrf_token_time']) > SecurityConfig::CSRF_TOKEN_LIFETIME) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Generate CSRF token field for forms
     */
    public static function field() {
        return '<input type="hidden" name="_token" value="' . self::token() . '">';
    }
    
    /**
     * Verify CSRF token
     */
    public static function verify($token = null) {
        if ($token === null) {
            $token = $_POST['_token'] ?? $_GET['_token'] ?? null;
        }
        
        if (empty($token) || !isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        // Check if token has expired
        if (isset($_SESSION['csrf_token_time']) && 
            (time() - $_SESSION['csrf_token_time']) > SecurityConfig::CSRF_TOKEN_LIFETIME) {
            unset($_SESSION['csrf_token']);
            unset($_SESSION['csrf_token_time']);
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Verify CSRF token and throw exception if invalid
     */
    public static function verifyOrFail($token = null) {
        if (!self::verify($token)) {
            http_response_code(419);
            die('CSRF token mismatch. Please refresh the page and try again.');
        }
    }
}

/**
 * XSS Protection
 */
class XSS {
    /**
     * Clean output to prevent XSS
     */
    public static function clean($data, $flags = ENT_QUOTES, $encoding = 'UTF-8') {
        if (is_array($data)) {
            return array_map(function($item) use ($flags, $encoding) {
                return self::clean($item, $flags, $encoding);
            }, $data);
        }
        return htmlspecialchars($data, $flags, $encoding);
    }
    
    /**
     * Clean output but allow HTML (use with caution)
     */
    public static function cleanHTML($data) {
        if (is_array($data)) {
            return array_map([self::class, 'cleanHTML'], $data);
        }
        // Remove dangerous tags and attributes
        $data = strip_tags($data, '<p><br><strong><em><u><a><ul><ol><li><h1><h2><h3><h4><h5><h6><img>');
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Escape for JavaScript
     */
    public static function escapeJS($string) {
        return json_encode($string, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }
    
    /**
     * Escape for HTML attributes
     */
    public static function escapeAttr($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Rate Limiting
 */
class RateLimiter {
    /**
     * Check if rate limit is exceeded
     */
    public static function check($key, $maxAttempts = null, $decaySeconds = null) {
        if (!SecurityConfig::RATE_LIMIT_ENABLED) {
            return true;
        }
        
        $maxAttempts = $maxAttempts ?? SecurityConfig::RATE_LIMIT_MAX_ATTEMPTS;
        $decaySeconds = $decaySeconds ?? SecurityConfig::RATE_LIMIT_WINDOW;
        
        $cacheKey = 'rate_limit_' . $key;
        $attempts = $_SESSION[$cacheKey] ?? [];
        
        // Remove expired attempts
        $currentTime = time();
        $attempts = array_filter($attempts, function($timestamp) use ($currentTime, $decaySeconds) {
            return ($currentTime - $timestamp) < $decaySeconds;
        });
        
        // Check if limit exceeded
        if (count($attempts) >= $maxAttempts) {
            return false;
        }
        
        // Record this attempt
        $attempts[] = $currentTime;
        $_SESSION[$cacheKey] = $attempts;
        
        return true;
    }
    
    /**
     * Get remaining attempts
     */
    public static function remaining($key, $maxAttempts = null, $decaySeconds = null) {
        $maxAttempts = $maxAttempts ?? SecurityConfig::RATE_LIMIT_MAX_ATTEMPTS;
        $decaySeconds = $decaySeconds ?? SecurityConfig::RATE_LIMIT_WINDOW;
        
        $cacheKey = 'rate_limit_' . $key;
        $attempts = $_SESSION[$cacheKey] ?? [];
        
        // Remove expired attempts
        $currentTime = time();
        $attempts = array_filter($attempts, function($timestamp) use ($currentTime, $decaySeconds) {
            return ($currentTime - $timestamp) < $decaySeconds;
        });
        
        return max(0, $maxAttempts - count($attempts));
    }
    
    /**
     * Clear rate limit for a key
     */
    public static function clear($key) {
        $cacheKey = 'rate_limit_' . $key;
        unset($_SESSION[$cacheKey]);
    }
    
    /**
     * Get retry after seconds
     */
    public static function retryAfter($key, $decaySeconds = null) {
        $decaySeconds = $decaySeconds ?? SecurityConfig::RATE_LIMIT_WINDOW;
        $cacheKey = 'rate_limit_' . $key;
        $attempts = $_SESSION[$cacheKey] ?? [];
        
        if (empty($attempts)) {
            return 0;
        }
        
        $oldestAttempt = min($attempts);
        $retryAfter = ($oldestAttempt + $decaySeconds) - time();
        
        return max(0, $retryAfter);
    }
}

/**
 * Session Security
 */
class SessionSecurity {
    /**
     * Initialize secure session
     */
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            // Set secure session parameters
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', self::isHTTPS());
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_samesite', 'Strict');
            
            session_start();
        }
        
        // Check session timeout
        self::checkTimeout();
        
        // Regenerate session ID periodically
        self::regenerateIfNeeded();
    }
    
    /**
     * Check if session has timed out
     */
    public static function checkTimeout() {
        if (isset($_SESSION['last_activity'])) {
            if ((time() - $_SESSION['last_activity']) > SecurityConfig::SESSION_TIMEOUT) {
                session_unset();
                session_destroy();
                session_start();
                $_SESSION['timeout'] = true;
            }
        }
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Regenerate session ID if needed
     */
    public static function regenerateIfNeeded() {
        if (!isset($_SESSION['regenerated_at'])) {
            $_SESSION['regenerated_at'] = time();
        }
        
        if ((time() - $_SESSION['regenerated_at']) > SecurityConfig::SESSION_REGENERATE_INTERVAL) {
            session_regenerate_id(true);
            $_SESSION['regenerated_at'] = time();
        }
    }
    
    /**
     * Check if using HTTPS
     */
    private static function isHTTPS() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
               $_SERVER['SERVER_PORT'] == 443 ||
               (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    }
    
    /**
     * Regenerate session ID immediately
     */
    public static function regenerate() {
        session_regenerate_id(true);
        $_SESSION['regenerated_at'] = time();
    }
}

/**
 * Input Validation
 */
class Validator {
    /**
     * Validate email
     */
    public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate URL
     */
    public static function url($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
    
    /**
     * Validate password strength
     */
    public static function password($password) {
        $errors = [];
        
        if (strlen($password) < SecurityConfig::PASSWORD_MIN_LENGTH) {
            $errors[] = 'Password must be at least ' . SecurityConfig::PASSWORD_MIN_LENGTH . ' characters long';
        }
        
        if (SecurityConfig::PASSWORD_REQUIRE_UPPERCASE && !preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        }
        
        if (SecurityConfig::PASSWORD_REQUIRE_LOWERCASE && !preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        }
        
        if (SecurityConfig::PASSWORD_REQUIRE_NUMBER && !preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }
        
        if (SecurityConfig::PASSWORD_REQUIRE_SPECIAL && !preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'Password must contain at least one special character';
        }
        
        return empty($errors) ? true : $errors;
    }
    
    /**
     * Validate required fields
     */
    public static function required($value) {
        if (is_array($value)) {
            return !empty($value);
        }
        return trim($value) !== '';
    }
    
    /**
     * Validate string length
     */
    public static function length($value, $min = null, $max = null) {
        $length = strlen($value);
        
        if ($min !== null && $length < $min) {
            return false;
        }
        
        if ($max !== null && $length > $max) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate numeric value
     */
    public static function numeric($value) {
        return is_numeric($value);
    }
    
    /**
     * Validate integer
     */
    public static function integer($value) {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
    
    /**
     * Sanitize string input
     */
    public static function sanitize($input, $type = 'string') {
        switch ($type) {
            case 'string':
                return trim(strip_tags($input));
            case 'email':
                return filter_var(trim($input), FILTER_SANITIZE_EMAIL);
            case 'int':
                return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'url':
                return filter_var(trim($input), FILTER_SANITIZE_URL);
            case 'html':
                return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
            default:
                return trim($input);
        }
    }
}

/**
 * Security Headers
 */
class SecurityHeaders {
    /**
     * Set security headers
     */
    public static function set() {
        // Prevent clickjacking
        header('X-Frame-Options: SAMEORIGIN');
        
        // XSS Protection
        header('X-XSS-Protection: 1; mode=block');
        
        // Prevent MIME type sniffing
        header('X-Content-Type-Options: nosniff');
        
        // Referrer Policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Content Security Policy (basic)
        // Allow Font Awesome fonts from cdnjs.cloudflare.com, Quill editor from cdn.quilljs.com, and Facebook SDK
        $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://cdn.quilljs.com https://fonts.googleapis.com https://maps.googleapis.com https://connect.facebook.net; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.quilljs.com https://fonts.googleapis.com; font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com data:; img-src 'self' data: https: https://*.fbcdn.net; frame-src 'self' https://www.facebook.com https://web.facebook.com https://www.google.com; connect-src 'self' https://www.facebook.com https://web.facebook.com https://connect.facebook.net https://staticxx.facebook.com https://maps.googleapis.com;";
        header("Content-Security-Policy: $csp");
        
        // HSTS (if HTTPS)
        if (self::isHTTPS()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
        
        // Permissions Policy
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    }
    
    /**
     * Check if using HTTPS
     */
    private static function isHTTPS() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
               $_SERVER['SERVER_PORT'] == 443 ||
               (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    }
}

/**
 * SQL Injection Prevention Helper
 */
class SQLSecurity {
    /**
     * Prepare and execute query safely
     */
    public static function execute($pdo, $sql, $params = []) {
        try {
            $stmt = $pdo->prepare($sql);
            
            // Bind parameters
            foreach ($params as $key => $value) {
                if (is_int($key)) {
                    // Positional parameters (1-indexed)
                    $stmt->bindValue($key + 1, $value, self::getPDOType($value));
                } else {
                    // Named parameters
                    $stmt->bindValue($key, $value, self::getPDOType($value));
                }
            }
            
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get appropriate PDO type for value
     */
    private static function getPDOType($value) {
        if (is_int($value)) {
            return PDO::PARAM_INT;
        } elseif (is_bool($value)) {
            return PDO::PARAM_BOOL;
        } elseif (is_null($value)) {
            return PDO::PARAM_NULL;
        } else {
            return PDO::PARAM_STR;
        }
    }
    
    /**
     * Escape string for LIKE queries
     */
    public static function escapeLike($string) {
        return str_replace(['%', '_'], ['\%', '\_'], $string);
    }
}

/**
 * Encryption Helper
 */
class Encryption {
    /**
     * Encrypt data (simple base64 encoding - for production use proper encryption)
     */
    public static function encrypt($data, $key = null) {
        if ($key === null) {
            $key = self::getEncryptionKey();
        }
        
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }
    
    /**
     * Decrypt data
     */
    public static function decrypt($data, $key = null) {
        if ($key === null) {
            $key = self::getEncryptionKey();
        }
        
        $data = base64_decode($data);
        list($encrypted, $iv) = explode('::', $data, 2);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    }
    
    /**
     * Get encryption key from environment or generate one
     */
    private static function getEncryptionKey() {
        // In production, store this in environment variables
        if (defined('ENCRYPTION_KEY')) {
            return ENCRYPTION_KEY;
        }
        
        // Generate a key if not set (store this securely in production)
        if (!isset($_SESSION['encryption_key'])) {
            $_SESSION['encryption_key'] = hash('sha256', 'uphsl_secret_key_' . __DIR__);
        }
        
        return $_SESSION['encryption_key'];
    }
}

/**
 * Initialize security features
 */
function initSecurity() {
    // Initialize secure session
    SessionSecurity::init();
    
    // Skip security headers for AJAX JSON endpoints
    if (!defined('SKIP_SECURITY_HEADERS') || !SKIP_SECURITY_HEADERS) {
        // Set security headers
        SecurityHeaders::set();
    }
}

// Auto-initialize security on include
initSecurity();

