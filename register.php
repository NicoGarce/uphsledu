<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $firstName = sanitizeInput($_POST['first_name']);
    $lastName = sanitizeInput($_POST['last_name']);
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($firstName) || empty($lastName)) {
        $error = 'Please fill in all fields';
    } elseif (!validateEmail($email)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } else {
        // Check if email or username already exists
        $existingUser = getUserByEmail($email);
        $existingUsername = getUserByUsername($username);
        
        if ($existingUser) {
            $error = 'Email address is already registered';
        } elseif ($existingUsername) {
            $error = 'Username is already taken';
        } else {
            // Create new user
            $pdo = getDBConnection();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO users (username, email, password, first_name, last_name, role) 
                    VALUES (?, ?, ?, ?, ?, 'user')
                ");
                $stmt->execute([$username, $email, $hashedPassword, $firstName, $lastName]);
                
                setFlashMessage('success', 'Registration successful! Please log in.');
                redirect('login.php');
            } catch (PDOException $e) {
                $error = 'Registration failed. Please try again.';
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
    <title>Register - University of Perpetual Help System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@400;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link rel="shortcut icon" type="image/png" href="assets/images/logo.png">
    <link rel="apple-touch-icon" href="assets/images/logo.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- First Column: Logo -->
            <div class="nav-logo">
                <a href="index.php">
                    <img src="assets/images/logo.png" alt="University of Perpetual Help System" class="logo-img">
                </a>
            </div>
            
            <!-- Second Column: Site Info and Menu -->
            <div class="nav-content">
                <!-- First Row: Site Name and Search -->
                <div class="nav-header">
                    <div class="site-name">
                        <h1>UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA</h1>
                    </div>
                    <div class="nav-search">
                        <form class="search-form">
                            <input type="text" placeholder="Search..." class="search-input">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Second Row: Main Menu -->
                <div class="nav-menu" id="nav-menu">
                    <div class="nav-item">
                        <a href="index.php" class="nav-link">Home</a>
                    </div>
                    
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Programs</a>
                        <div class="dropdown-menu">
                            <div class="dropdown-section">
                                <h4>Basic Education</h4>
                                <a href="#" class="dropdown-link">Senior High School</a>
                                <a href="#" class="dropdown-link">Junior High School</a>
                                <a href="#" class="dropdown-link">Grade School</a>
                            </div>
                            <div class="dropdown-section">
                                <h4>Higher Education</h4>
                                <a href="#" class="dropdown-link">Aviation</a>
                                <a href="#" class="dropdown-link">Arts & Sciences</a>
                                <a href="#" class="dropdown-link">Business & Accountancy</a>
                                <a href="#" class="dropdown-link">Computer Studies</a>
                                <a href="#" class="dropdown-link">Criminology</a>
                                <a href="#" class="dropdown-link">Education</a>
                                <a href="#" class="dropdown-link">Engineering & Architecture</a>
                                <a href="#" class="dropdown-link">International Hospitality Management</a>
                                <a href="#" class="dropdown-link">Maritime</a>
                                <a href="#" class="dropdown-link">Law/Juris Doctor</a>
                                <a href="#" class="dropdown-link">Graduate School</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Online Services</a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-link">Instructions</a>
                            <a href="#" class="dropdown-link">GTI Online Grades</a>
                            <a href="#" class="dropdown-link">Moodle</a>
                            <a href="#" class="dropdown-link">Google Account</a>
                            <a href="#" class="dropdown-link">Online Payment</a>
                            <a href="#" class="dropdown-link">Microsoft 365</a>
                            <a href="#" class="dropdown-link">Saliksik</a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Support Services</a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-link">Alumni</a>
                            <a href="#" class="dropdown-link">Careers</a>
                            <a href="#" class="dropdown-link">Clinic</a>
                            <a href="#" class="dropdown-link">Community Outreach Department</a>
                            <a href="#" class="dropdown-link">International and External Affairs</a>
                            <a href="#" class="dropdown-link">Guidance and Admission</a>
                            <a href="#" class="dropdown-link">Library</a>
                            <a href="#" class="dropdown-link">Quality Assurance</a>
                            <a href="#" class="dropdown-link">Research</a>
                        </div>
                    </div>
                    
                    <div class="nav-item">
                        <a href="#" class="nav-link">Campuses</a>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">About</a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-link">About Us</a>
                            <a href="#" class="dropdown-link">Contact Us</a>
                            <a href="#" class="dropdown-link">Environmental Policy</a>
                            <a href="#" class="dropdown-link">University Policy</a>
                            <a href="#" class="dropdown-link">Map</a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Online Payment</a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-link">Entrance Exam</a>
                            <a href="#" class="dropdown-link">New Enrollees</a>
                            <a href="#" class="dropdown-link">Enrolled Students</a>
                        </div>
                    </div>
                    
                    <div class="nav-item">
                        <a href="#" class="nav-link">Calendar</a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="#" class="nav-link">SDG Initiatives</a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="login.php" class="nav-link">Login</a>
                    </div>
                </div>
            </div>
            
            <div class="nav-toggle" id="nav-toggle">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>

    <!-- Registration Form -->
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Join Our Community</h1>
                <p class="auth-subtitle">Create your account to start writing</p>
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
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name" class="form-label">
                            <i class="fas fa-user"></i>
                            First Name
                        </label>
                        <input type="text" id="first_name" name="first_name" class="form-input" 
                               value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="last_name" class="form-label">
                            <i class="fas fa-user"></i>
                            Last Name
                        </label>
                        <input type="text" id="last_name" name="last_name" class="form-input" 
                               value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" 
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="username" class="form-label">
                        <i class="fas fa-at"></i>
                        Username
                    </label>
                    <input type="text" id="username" name="username" class="form-input" 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <input type="email" id="email" name="email" class="form-input" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">
                        <i class="fas fa-lock"></i>
                        Confirm Password
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required>
                        <span class="checkmark"></span>
                        I agree to the <a href="terms.php" class="terms-link">Terms of Service</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    <i class="fas fa-user-plus"></i>
                    Create Account
                </button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="login.php" class="auth-link">Sign in here</a></p>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>

