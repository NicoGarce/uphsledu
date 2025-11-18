<?php
/**
 * Security Features Quick Reference
 * 
 * This file provides quick examples of how to use security features.
 * Include this file for reference only - it doesn't execute any code.
 */

/*
 * ============================================
 * CSRF PROTECTION
 * ============================================
 */

// In HTML forms:
// <?php echo CSRF::field(); ?>

// In form processing:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!CSRF::verify()) {
        die('CSRF token mismatch');
    }
    // Process form...
}

/*
 * ============================================
 * XSS PROTECTION
 * ============================================
 */

// When outputting user data:
echo XSS::clean($userInput);              // Escapes HTML
echo XSS::cleanHTML($userInput);          // Allows safe HTML
echo XSS::escapeJS($userInput);           // For JavaScript
echo XSS::escapeAttr($userInput);         // For HTML attributes

/*
 * ============================================
 * RATE LIMITING
 * ============================================
 */

// Check rate limit:
$key = 'login_' . $_SERVER['REMOTE_ADDR'];
if (!RateLimiter::check($key, 5, 900)) {
    die('Too many attempts');
}

// Get remaining attempts:
$remaining = RateLimiter::remaining($key, 5, 900);

// Clear on success:
RateLimiter::clear($key);

/*
 * ============================================
 * INPUT VALIDATION
 * ============================================
 */

// Validate:
Validator::email($email);
Validator::password($password);  // Returns true or array of errors
Validator::required($value);
Validator::length($value, $min, $max);
Validator::numeric($value);
Validator::integer($value);

// Sanitize:
Validator::sanitize($input, 'string');
Validator::sanitize($input, 'email');
Validator::sanitize($input, 'int');
Validator::sanitize($input, 'float');
Validator::sanitize($input, 'url');
Validator::sanitize($input, 'html');

/*
 * ============================================
 * SQL INJECTION PREVENTION
 * ============================================
 */

// Always use prepared statements:
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

// For LIKE queries:
$search = SQLSecurity::escapeLike($userInput);
$stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE ?");
$stmt->execute(["%{$search}%"]);

/*
 * ============================================
 * SESSION SECURITY
 * ============================================
 */

// Regenerate session ID (e.g., after login):
SessionSecurity::regenerate();

// Session security is automatically initialized

/*
 * ============================================
 * SECURITY HEADERS
 * ============================================
 */

// Headers are automatically set
// To manually set:
SecurityHeaders::set();

/*
 * ============================================
 * ENCRYPTION
 * ============================================
 */

// Encrypt sensitive data:
$encrypted = Encryption::encrypt($data);

// Decrypt:
$decrypted = Encryption::decrypt($encrypted);

/*
 * ============================================
 * COMPLETE EXAMPLE: Secure Form Processing
 * ============================================
 */

/*
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verify CSRF token
    if (!CSRF::verify()) {
        $error = 'Security token mismatch. Please refresh and try again.';
    } else {
        // 2. Rate limiting
        $rateLimitKey = 'form_' . $_SERVER['REMOTE_ADDR'];
        if (!RateLimiter::check($rateLimitKey, 10, 60)) {
            $error = 'Too many requests. Please wait a moment.';
        } else {
            // 3. Validate and sanitize input
            $name = Validator::sanitize($_POST['name'] ?? '', 'string');
            $email = Validator::sanitize($_POST['email'] ?? '', 'email');
            
            // 4. Validate
            if (!Validator::required($name)) {
                $error = 'Name is required';
            } elseif (!Validator::email($email)) {
                $error = 'Invalid email address';
            } else {
                // 5. Use prepared statements for database
                $stmt = $pdo->prepare("INSERT INTO contacts (name, email) VALUES (?, ?)");
                $stmt->execute([$name, $email]);
                
                // 6. Clear rate limit on success
                RateLimiter::clear($rateLimitKey);
                
                $success = 'Form submitted successfully!';
            }
        }
    }
}
*/

/*
 * ============================================
 * COMPLETE EXAMPLE: Secure Output
 * ============================================
 */

/*
// In your HTML template:
<h1><?php echo XSS::clean($post['title']); ?></h1>
<p><?php echo XSS::clean($post['content']); ?></p>

// For JavaScript:
<script>
    var userName = <?php echo XSS::escapeJS($user['name']); ?>;
</script>

// For HTML attributes:
<img src="<?php echo XSS::escapeAttr($imageUrl); ?>" alt="<?php echo XSS::escapeAttr($altText); ?>">
*/


