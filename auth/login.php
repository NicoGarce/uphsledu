<?php
/**
 * UPHSL Login Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description User authentication and login system for UPHSL website
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('../admin/dashboard.php');
}

$error = '';
$success = '';

// Check for flash messages
if (hasFlashMessage('success')) {
    $success = getFlashMessage('success');
}
if (hasFlashMessage('error')) {
    $error = getFlashMessage('error');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!CSRF::verify()) {
        $error = 'Security token mismatch. Please refresh the page and try again.';
    } else {
        // Rate limiting for login attempts
        $rateLimitKey = 'login_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        if (!RateLimiter::check($rateLimitKey, 5, 900)) {
            $retryAfter = RateLimiter::retryAfter($rateLimitKey, 900);
            $error = 'Too many login attempts. Please try again in ' . ceil($retryAfter / 60) . ' minutes.';
        } else {
            $username = Validator::sanitize($_POST['username'], 'string');
            $password = $_POST['password'];
            
            if (empty($username) || empty($password)) {
                $error = 'Please fill in all fields';
            } else {
                $user = getUserByUsername($username);
                
                if ($user && password_verify($password, $user['password'])) {
                    // Clear rate limit on successful login
                    RateLimiter::clear($rateLimitKey);
                    
                    // Regenerate session ID on login
                    SessionSecurity::regenerate();
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['first_name'] = $user['first_name'];
                    
                    setFlashMessage('success', 'Welcome back, ' . $user['first_name'] . '!');
                    
                    // Redirect based on user role
                    if ($user['role'] === 'author') {
                        redirect('../admin/author-dashboard.php');
                    } else {
                        redirect('../admin/dashboard.php');
                    }
                } else {
                    $error = 'Invalid username or password';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <title>Login - University of Perpetual Help System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@400;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/Logos/logo.png">
    <link rel="shortcut icon" type="image/png" href="../assets/images/Logos/logo.png">
    <link rel="apple-touch-icon" href="../assets/images/Logos/logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

    <!-- Login Form -->
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <img src="../assets/images/Logos/Logo2025.png" alt="University of Perpetual Help System" class="logo-img">
                </div>
                <h1 class="auth-title">Welcome Back</h1>
                <p class="auth-subtitle">Sign in to your account</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <?php echo CSRF::field(); ?>
                <div class="form-group">
                    <label for="username" class="form-label">
                        <i class="fas fa-user"></i>
                        Username
                    </label>
                    <input type="text" id="username" name="username" class="form-input" 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span class="checkmark"></span>
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>

        </div>
        
        <!-- Website Access Button -->
        <div class="website-access">
            <a href="../" class="btn btn-secondary">
                <i class="fas fa-home"></i>
                Visit Website
            </a>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>

