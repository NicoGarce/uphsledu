<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is super admin
if (!isLoggedIn() || !isSuperAdmin()) {
    redirect('../login.php');
}

$user = getUserById($_SESSION['user_id']);
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create_user') {
        $username = sanitizeInput($_POST['username']);
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];
        $firstName = sanitizeInput($_POST['first_name']);
        $lastName = sanitizeInput($_POST['last_name']);
        $role = sanitizeInput($_POST['role']);
        
        // Validation
        if (empty($username) || empty($email) || empty($password) || empty($firstName) || empty($lastName)) {
            $error = 'Please fill in all fields';
        } elseif (!validateEmail($email)) {
            $error = 'Please enter a valid email address';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters long';
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
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$username, $email, $hashedPassword, $firstName, $lastName, $role]);
                    
                    $success = 'User account created successfully!';
                } catch (PDOException $e) {
                    $error = 'Failed to create user account';
                }
            }
        }
    }
}

// Get all users
$pdo = getDBConnection();
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management - University of Perpetual Help System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@400;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link rel="shortcut icon" type="image/png" href="../assets/images/logo.png">
    <link rel="apple-touch-icon" href="../assets/images/logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="../index.php">
                    <img src="../assets/images/logo.png" alt="University of Perpetual Help System" class="logo-img">
                </a>
            </div>
            <div class="nav-menu">
                <a href="../index.php" class="nav-link">Home</a>
                <a href="../dashboard.php" class="nav-link">Dashboard</a>
                <?php if (isAuthor() || isSuperAdmin()): ?>
                    <a href="../create-post.php" class="nav-link">Create Post</a>
                <?php endif; ?>
                <?php if (isAdmin()): ?>
                    <a href="users.php" class="nav-link">Users</a>
                <?php endif; ?>
                <a href="accounts.php" class="nav-link active">Account Management</a>
            </div>
            <div class="user-menu">
                <span class="user-name"><?php echo htmlspecialchars($user['first_name']); ?></span>
                <a href="../logout.php" class="nav-link">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Account Management Content -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-user-plus"></i>
                Account Management
            </h1>
            <p class="dashboard-subtitle">
                Create and manage staff accounts for the University of Perpetual Help System
            </p>
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

        <!-- Create New Account Form -->
        <div class="dashboard-section">
            <h2 class="section-title">Create New Staff Account</h2>
            <div class="form-container">
                <form method="POST" class="create-account-form">
                    <input type="hidden" name="action" value="create_user">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="form-input" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email" class="form-input" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="role" class="form-label">Role</label>
                            <select id="role" name="role" class="form-input" required>
                                <option value="">Select Role</option>
                                <option value="user">User</option>
                                <option value="author">Author</option>
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i>
                        Create Account
                    </button>
                </form>
            </div>
        </div>

        <!-- Existing Accounts -->
        <div class="dashboard-section">
            <h2 class="section-title">Existing Staff Accounts</h2>
            <div class="accounts-table">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $account): ?>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <strong><?php echo htmlspecialchars($account['first_name'] . ' ' . $account['last_name']); ?></strong>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($account['username']); ?></td>
                                <td><?php echo htmlspecialchars($account['email']); ?></td>
                                <td>
                                    <span class="role-badge role-<?php echo $account['role']; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $account['role'])); ?>
                                    </span>
                                </td>
                                <td><?php echo formatDate($account['created_at']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit-user.php?id=<?php echo $account['id']; ?>" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($account['id'] != $_SESSION['user_id']): ?>
                                            <a href="delete-user.php?id=<?php echo $account['id']; ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this account?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>
