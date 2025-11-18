# Security Features Documentation

This document describes the Laravel-like security features implemented in the UPHSL website.

## Overview

The security system provides comprehensive protection against common web vulnerabilities including:
- CSRF (Cross-Site Request Forgery) protection
- XSS (Cross-Site Scripting) prevention
- SQL Injection prevention
- Rate limiting
- Session security
- Security headers
- Input validation and sanitization

## Installation

The security features are automatically initialized when you include `app/includes/functions.php` or `app/includes/security.php`.

## Usage

### CSRF Protection

#### In Forms

Add the CSRF token field to all forms:

```php
<form method="POST">
    <?php echo CSRF::field(); ?>
    <!-- Your form fields -->
</form>
```

#### Verify CSRF Token

In your form processing code:

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!CSRF::verify()) {
        die('CSRF token mismatch');
    }
    // Process form
}
```

Or use the exception-throwing version:

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    CSRF::verifyOrFail(); // Throws error if invalid
    // Process form
}
```

#### Get Token Value

```php
$token = CSRF::token();
```

### XSS Protection

#### Clean Output

```php
// Basic cleaning (escapes HTML)
echo XSS::clean($userInput);

// Clean but allow safe HTML tags
echo XSS::cleanHTML($userInput);

// Escape for JavaScript
echo XSS::escapeJS($userInput);

// Escape for HTML attributes
echo XSS::escapeAttr($userInput);
```

### Rate Limiting

#### Check Rate Limit

```php
$key = 'login_' . $_SERVER['REMOTE_ADDR'];
if (!RateLimiter::check($key, 5, 900)) {
    // 5 attempts per 15 minutes (900 seconds)
    die('Too many attempts');
}
```

#### Get Remaining Attempts

```php
$remaining = RateLimiter::remaining($key, 5, 900);
echo "You have {$remaining} attempts remaining";
```

#### Clear Rate Limit

```php
RateLimiter::clear($key);
```

#### Get Retry After Time

```php
$retryAfter = RateLimiter::retryAfter($key, 900);
echo "Try again in " . ceil($retryAfter / 60) . " minutes";
```

### Input Validation

#### Validate Input

```php
// Email
if (!Validator::email($email)) {
    $error = 'Invalid email';
}

// Password strength
$result = Validator::password($password);
if ($result !== true) {
    // $result is an array of error messages
    foreach ($result as $error) {
        echo $error;
    }
}

// Required field
if (!Validator::required($value)) {
    $error = 'This field is required';
}

// String length
if (!Validator::length($value, 5, 100)) {
    $error = 'Must be between 5 and 100 characters';
}

// Numeric
if (!Validator::numeric($value)) {
    $error = 'Must be a number';
}
```

#### Sanitize Input

```php
// String
$clean = Validator::sanitize($input, 'string');

// Email
$clean = Validator::sanitize($input, 'email');

// Integer
$clean = Validator::sanitize($input, 'int');

// Float
$clean = Validator::sanitize($input, 'float');

// URL
$clean = Validator::sanitize($input, 'url');

// HTML (escapes special characters)
$clean = Validator::sanitize($input, 'html');
```

### Session Security

Session security is automatically initialized. You can manually regenerate the session ID:

```php
SessionSecurity::regenerate();
```

### Security Headers

Security headers are automatically set. To manually set them:

```php
SecurityHeaders::set();
```

### SQL Injection Prevention

#### Using Prepared Statements

```php
// Using the SQLSecurity helper
$stmt = SQLSecurity::execute($pdo, 
    "SELECT * FROM users WHERE email = ? AND status = ?",
    [$email, 'active']
);

// Or use PDO directly (recommended)
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
```

#### Escape LIKE Queries

```php
$search = SQLSecurity::escapeLike($userInput);
$stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE ?");
$stmt->execute(["%{$search}%"]);
```

### Encryption

```php
// Encrypt data
$encrypted = Encryption::encrypt($sensitiveData);

// Decrypt data
$decrypted = Encryption::decrypt($encrypted);
```

## Configuration

Security settings can be configured in `app/config/security.php`:

- CSRF token lifetime
- Rate limiting thresholds
- Session timeout
- Password requirements
- Security headers
- Content Security Policy

## Best Practices

1. **Always use CSRF protection** on forms that modify data
2. **Always sanitize user input** before displaying or storing
3. **Use prepared statements** for all database queries
4. **Implement rate limiting** on authentication endpoints
5. **Validate all input** before processing
6. **Use HTTPS in production** and enable secure cookies
7. **Regenerate session IDs** after login
8. **Set appropriate security headers** for your application

## Examples

### Complete Login Form with Security

```php
<?php
require_once '../app/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF
    if (!CSRF::verify()) {
        $error = 'Security token mismatch';
    } else {
        // Rate limiting
        $key = 'login_' . $_SERVER['REMOTE_ADDR'];
        if (!RateLimiter::check($key, 5, 900)) {
            $error = 'Too many login attempts';
        } else {
            // Validate and sanitize
            $username = Validator::sanitize($_POST['username'], 'string');
            $password = $_POST['password'];
            
            if (Validator::required($username) && Validator::required($password)) {
                // Check credentials
                $user = getUserByUsername($username);
                if ($user && password_verify($password, $user['password'])) {
                    RateLimiter::clear($key);
                    SessionSecurity::regenerate();
                    // Login successful
                } else {
                    $error = 'Invalid credentials';
                }
            }
        }
    }
}
?>

<form method="POST">
    <?php echo CSRF::field(); ?>
    <input type="text" name="username" required>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
```

### Secure Database Query

```php
// BAD - SQL Injection vulnerability
$sql = "SELECT * FROM users WHERE id = " . $_GET['id'];
$result = $pdo->query($sql);

// GOOD - Using prepared statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_GET['id']]);
$result = $stmt->fetch();
```

### Secure Output

```php
// BAD - XSS vulnerability
echo $_GET['name'];

// GOOD - XSS protection
echo XSS::clean($_GET['name']);
```

## Security Checklist

- [ ] All forms have CSRF tokens
- [ ] All user input is validated
- [ ] All user output is escaped
- [ ] All database queries use prepared statements
- [ ] Rate limiting is implemented on sensitive endpoints
- [ ] Session security is enabled
- [ ] Security headers are set
- [ ] Passwords are hashed with password_hash()
- [ ] HTTPS is enabled in production
- [ ] Error messages don't expose sensitive information

## Support

For security issues or questions, contact the development team.


