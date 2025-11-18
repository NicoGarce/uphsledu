<?php
/**
 * UPHSL Admin Account Management
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for managing user accounts and system settings
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
// Session is automatically initialized by security.php

// Check if user is logged in and is super admin only
if (!isLoggedIn() || !isSuperAdmin()) {
    redirect('../auth/login.php');
}

$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Set page title for header
$page_title = 'Account Management';

// Get database connection
$pdo = getDBConnection();

$error = '';
$success = '';

// Get success message from URL
if (isset($_GET['success'])) {
    $success = urldecode($_GET['success']);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!CSRF::verify()) {
        $error = 'Security token mismatch. Please refresh the page and try again.';
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'create_user') {
        $username = Validator::sanitize($_POST['username'], 'string');
        $email = Validator::sanitize($_POST['email'], 'email');
        $password = $_POST['password'];
        $firstName = Validator::sanitize($_POST['first_name'], 'string');
        $lastName = Validator::sanitize($_POST['last_name'], 'string');
        $role = Validator::sanitize($_POST['role'], 'string');
        
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
    } elseif ($action === 'delete_user') {
        $userId = $_POST['user_id'];
        $password = $_POST['password'] ?? '';
        
        // Verify password
        if (empty($password) || !verifyUserPassword($_SESSION['user_id'], $password)) {
            $error = "Invalid password. Please try again.";
        } elseif ($userId && $userId != $_SESSION['user_id']) {
            try {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $success = 'User account deleted successfully';
            } catch (PDOException $e) {
                $error = 'Failed to delete user account';
            }
        } else {
            $error = 'Cannot delete your own account';
        }
    } elseif ($action === 'verify_password') {
        $password = $_POST['password'];
        $userId = $_POST['user_id'];
        
        // Check password against current logged-in user's password
        if (password_verify($password, $user['password'])) {
            // Password verified, get user data for editing
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $editUser = $stmt->fetch();
            
            if ($editUser) {
                // Return user data as JSON for modal
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'user' => [
                        'id' => $editUser['id'],
                        'username' => $editUser['username'],
                        'email' => $editUser['email'],
                        'first_name' => $editUser['first_name'],
                        'last_name' => $editUser['last_name'],
                        'role' => $editUser['role']
                    ]
                ]);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'User not found']);
                exit;
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid password. Please enter your current password.']);
            exit;
        }
    } elseif ($action === 'update_user') {
        $userId = $_POST['user_id'];
        $username = sanitizeInput($_POST['username']);
        $email = sanitizeInput($_POST['email']);
        $firstName = sanitizeInput($_POST['first_name']);
        $lastName = sanitizeInput($_POST['last_name']);
        $role = sanitizeInput($_POST['role']);
        
        // Validation
        if (empty($username) || empty($email) || empty($firstName) || empty($lastName)) {
            $error = 'Please fill in all fields';
        } elseif (!validateEmail($email)) {
            $error = 'Please enter a valid email address';
        } else {
            // Check if email or username already exists (excluding current user)
            $stmt = $pdo->prepare("SELECT id FROM users WHERE (email = ? OR username = ?) AND id != ?");
            $stmt->execute([$email, $username, $userId]);
            $existingUser = $stmt->fetch();
            
            if ($existingUser) {
                $error = 'Email or username already exists';
            } else {
                try {
                    $stmt = $pdo->prepare("
                        UPDATE users 
                        SET username = ?, email = ?, first_name = ?, last_name = ?, role = ?, updated_at = NOW()
                        WHERE id = ?
                    ");
                    $stmt->execute([$username, $email, $firstName, $lastName, $role, $userId]);
                    $success = 'User account updated successfully';
                } catch (PDOException $e) {
                    $error = 'Failed to update user account';
                }
            }
        }
    } elseif ($action === 'verify_reset_password') {
        $adminPassword = $_POST['admin_password'] ?? '';
        $userId = $_POST['user_id'] ?? '';
        
        // Verify admin's password
        if (empty($adminPassword)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Please enter your password']);
            exit;
        }
        
        if (!password_verify($adminPassword, $user['password'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid password. Please enter your current password.']);
            exit;
        }
        
        // Get target user info
        if (empty($userId)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            exit;
        }
        
        $stmt = $pdo->prepare("SELECT id, username, email, first_name, last_name, role FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $targetUser = $stmt->fetch();
        
        if (!$targetUser) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'User not found']);
            exit;
        }
        
        // Return success with user data to show confirmation
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'user' => [
                'id' => $targetUser['id'],
                'name' => $targetUser['first_name'] . ' ' . $targetUser['last_name'],
                'username' => $targetUser['username'],
                'email' => $targetUser['email'],
                'role' => $targetUser['role']
            ]
        ]);
        exit;
    } elseif ($action === 'reset_password') {
        $userId = $_POST['user_id'];
        $newPassword = $_POST['new_password'] ?? '';
        $generatePassword = isset($_POST['generate_password']) && $_POST['generate_password'] === '1';
        $adminPassword = $_POST['admin_password'] ?? '';
        
        // Security: Verify admin password again before resetting
        if (empty($adminPassword) || !password_verify($adminPassword, $user['password'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Admin password verification failed. Please try again.']);
            exit;
        }
        
        // Validation
        if (empty($userId)) {
            $error = 'User ID is required';
        } else {
            // Check if user exists
            $stmt = $pdo->prepare("SELECT id, username, email, first_name, last_name FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $targetUser = $stmt->fetch();
            
            if (!$targetUser) {
                $error = 'User not found';
            } else {
                // Prevent resetting own password through this method (security measure)
                if ($userId == $_SESSION['user_id']) {
                    $error = 'You cannot reset your own password through this method. Please use the profile settings.';
                } else {
                    // Generate password if requested, otherwise use provided password
                    if ($generatePassword) {
                        // Generate a random 12-character password
                        $newPassword = bin2hex(random_bytes(6)); // 12 characters
                    } elseif (empty($newPassword)) {
                        $error = 'Please enter a new password or select generate password';
                    } elseif (strlen($newPassword) < 6) {
                        $error = 'Password must be at least 6 characters long';
                    }
                    
                    if (empty($error)) {
                        try {
                            // Hash the new password
                            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                            
                            // Update password
                            $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
                            $stmt->execute([$hashedPassword, $userId]);
                            
                            // Return success with the new password (for display)
                            header('Content-Type: application/json');
                            echo json_encode([
                                'success' => true,
                                'message' => 'Password reset successfully',
                                'new_password' => $newPassword,
                                'user' => [
                                    'id' => $targetUser['id'],
                                    'name' => $targetUser['first_name'] . ' ' . $targetUser['last_name'],
                                    'username' => $targetUser['username'],
                                    'email' => $targetUser['email']
                                ]
                            ]);
                            exit;
                        } catch (PDOException $e) {
                            $error = 'Failed to reset password';
                        }
                    }
                }
            }
        }
        
        // If we get here, there was an error
        if (!empty($error)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $error]);
            exit;
        }
    }
    }
}

// Get all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<?php include '../app/includes/admin-header.php'; ?>

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

        <?php if (isSuperAdmin()): ?>
        <!-- Create New Account Form -->
        <div class="dashboard-section">
            <h2 class="section-title">Create New Staff Account</h2>
            <div class="form-container">
                <form method="POST" class="create-account-form">
                    <?php echo CSRF::field(); ?>
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
                                <option value="hr">HR</option>
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
        <?php endif; ?>

        <!-- Existing Accounts -->
        <div class="dashboard-section">
            <h2 class="section-title">Existing Staff Accounts</h2>
            <div class="posts-list">
                <?php foreach ($users as $account): ?>
                    <div class="post-item">
                        <div class="post-info">
                            <h3 class="post-title">
                                <strong><?php echo htmlspecialchars($account['first_name'] . ' ' . $account['last_name']); ?></strong>
                            </h3>
                            <div class="post-meta">
                                <span class="post-status status-<?php echo $account['role']; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $account['role'])); ?>
                                </span>
                                <span class="post-date">
                                    Username: <?php echo htmlspecialchars($account['username']); ?>
                                </span>
                                <span class="post-date">
                                    Email: <?php echo htmlspecialchars($account['email']); ?>
                                </span>
                                <span class="post-date">
                                    Created: <?php echo formatDate($account['created_at']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="post-actions">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="openEditModal(<?php echo $account['id']; ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <?php if (isSuperAdmin()): ?>
                                <button type="button" class="btn btn-sm btn-warning" onclick="openResetPasswordModal(<?php echo $account['id']; ?>, '<?php echo htmlspecialchars($account['first_name'] . ' ' . $account['last_name'], ENT_QUOTES); ?>')" title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </button>
                                <?php if ($account['id'] != $_SESSION['user_id']): ?>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="openDeleteAccountModal(<?php echo $account['id']; ?>, '<?php echo htmlspecialchars($account['first_name'] . ' ' . $account['last_name'], ENT_QUOTES); ?>')" title="Delete Account">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Password Verification Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Verify Password</h3>
                <span class="close" onclick="closePasswordModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>Please enter your password to edit this user account:</p>
                <form id="passwordForm">
                    <?php echo CSRF::field(); ?>
                    <div class="form-group">
                        <label for="password" class="form-label">Your Password</label>
                        <input type="password" id="password" name="password" class="form-input" required>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closePasswordModal()">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="verifyPassword()">Verify</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit User Account</h3>
                <span class="close" onclick="closeEditModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="update_user">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_first_name" class="form-label">First Name</label>
                            <input type="text" id="edit_first_name" name="first_name" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_last_name" class="form-label">Last Name</label>
                            <input type="text" id="edit_last_name" name="last_name" class="form-input" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_username" class="form-label">Username</label>
                            <input type="text" id="edit_username" name="username" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" id="edit_email" name="email" class="form-input" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_role" class="form-label">Role</label>
                        <select id="edit_role" name="role" class="form-input" required>
                            <option value="author">Author</option>
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteAccountModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Delete Account</h3>
                <span class="close" onclick="closeDeleteAccountModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>Please enter your password to confirm this action.</p>
                <form id="deleteAccountForm" method="POST">
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="delete_user">
                    <input type="hidden" name="user_id" id="delete_account_id">
                    
                    <div style="margin-bottom: 1rem;">
                        <label for="deleteAccountPassword" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password:</label>
                        <input type="password" id="deleteAccountPassword" name="password" required 
                               style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;"
                               placeholder="Enter your password" autocomplete="current-password">
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeDeleteAccountModal()">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Verification Modal for Reset -->
    <div id="resetPasswordVerifyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Verify Your Password</h3>
                <span class="close" onclick="closeResetPasswordVerifyModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" style="margin-bottom: 15px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Security Verification Required</strong>
                </div>
                <p id="resetPasswordVerifyUserInfo" style="margin-bottom: 15px; color: #666;"></p>
                <p style="margin-bottom: 15px; color: #666;">Please enter your password to confirm this action:</p>
                <form id="resetPasswordVerifyForm">
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="verify_reset_password">
                    <input type="hidden" name="user_id" id="verify_reset_user_id">
                    
                    <div class="form-group">
                        <label for="admin_password_verify" class="form-label">Your Password</label>
                        <input type="password" id="admin_password_verify" name="admin_password" class="form-input" required autocomplete="current-password">
                        <small style="color: #666; display: block; margin-top: 5px;">Enter your admin password to proceed</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeResetPasswordVerifyModal()">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="verifyResetPassword()">Verify & Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div id="resetPasswordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Reset Password</h3>
                <span class="close" onclick="closeResetPasswordModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" style="margin-bottom: 15px;">
                    <i class="fas fa-info-circle"></i>
                    <strong>Confirming password reset for:</strong>
                    <div id="resetPasswordUserInfo" style="margin-top: 5px; font-weight: bold;"></div>
                </div>
                <form id="resetPasswordForm">
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="reset_password">
                    <input type="hidden" name="user_id" id="reset_user_id">
                    <input type="hidden" name="admin_password" id="reset_admin_password">
                    
                    <div class="form-group">
                        <label class="form-label">
                            <input type="checkbox" id="generate_password" name="generate_password" value="1" checked onchange="togglePasswordInput()">
                            Generate random password
                        </label>
                    </div>
                    
                    <div class="form-group" id="passwordInputGroup" style="display: none;">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-input" minlength="6" placeholder="Enter new password (min. 6 characters)">
                        <small style="color: #666; display: block; margin-top: 5px;">Password must be at least 6 characters long</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeResetPasswordModal()">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="resetPassword()">Confirm Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Display Modal -->
    <div id="passwordDisplayModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Password Reset Successful</h3>
                <span class="close" onclick="closePasswordDisplayModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 15px;">The password has been reset successfully for:</p>
                <p id="passwordDisplayUserInfo" style="margin-bottom: 20px; font-weight: bold; color: #333;"></p>
                <div class="form-group">
                    <label class="form-label">New Password:</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="text" id="displayed_password" class="form-input" readonly style="font-family: monospace; font-size: 16px; letter-spacing: 2px;">
                        <button type="button" class="btn btn-sm btn-secondary" onclick="copyPassword()" title="Copy password">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="alert alert-warning" style="margin-top: 15px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Important:</strong> Please save this password securely. It will not be shown again.
                </div>
                <div class="form-actions" style="margin-top: 20px;">
                    <button type="button" class="btn btn-primary" onclick="closePasswordDisplayModal()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentEditUserId = null;

        // Open password verification modal
        function openEditModal(userId) {
            currentEditUserId = userId;
            // Reset form first
            document.getElementById('passwordForm').reset();
            document.getElementById('passwordModal').style.display = 'block';
            
            // Focus on password field
            setTimeout(() => {
                const passwordField = document.getElementById('password');
                passwordField.focus();
                
                // Add real-time password tracking with multiple event types
                passwordField.addEventListener('input', function() {
                    console.log('Input event - Password field changed:', this.value);
                });
                
                passwordField.addEventListener('keyup', function() {
                    console.log('Keyup event - Password field changed:', this.value);
                });
                
                passwordField.addEventListener('change', function() {
                    console.log('Change event - Password field changed:', this.value);
                });
            }, 100);
        }

        // Close password modal
        function closePasswordModal() {
            document.getElementById('passwordModal').style.display = 'none';
            document.getElementById('passwordForm').reset();
            currentEditUserId = null;
        }

        // Close edit modal
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('editForm').reset();
            currentEditUserId = null;
        }

        // Verify password function
        function verifyPassword() {
            console.log('=== VERIFY PASSWORD DEBUG ===');
            
            // Try multiple ways to get the password
            const passwordField = document.getElementById('password');
            console.log('Password field found:', passwordField);
            
            // Method 1: Direct value
            let password = passwordField.value;
            console.log('Method 1 - Direct value:', password);
            
            // Method 2: Form data
            if (!password) {
                const form = document.getElementById('passwordForm');
                const formData = new FormData(form);
                password = formData.get('password');
                console.log('Method 2 - FormData:', password);
            }
            
            // Method 3: Query selector
            if (!password) {
                const input = document.querySelector('#password');
                password = input ? input.value : '';
                console.log('Method 3 - Query selector:', password);
            }
            
            // Method 4: All password inputs
            if (!password) {
                const inputs = document.querySelectorAll('input[type="password"]');
                console.log('All password inputs found:', inputs.length);
                inputs.forEach((input, index) => {
                    console.log(`Password input ${index}:`, input.value);
                });
                if (inputs.length > 0) {
                    password = inputs[0].value;
                }
            }
            
            // Trim and final check
            password = password ? password.trim() : '';
            
            console.log('Final password value:', password);
            console.log('Password length:', password.length);
            console.log('=== END DEBUG ===');
            
            if (password.length === 0) {
                alert('Please enter your password. The field appears to be empty.');
                passwordField.focus();
                return;
            }
            
            // Get CSRF token from the page
            const csrfToken = document.querySelector('input[name="_token"]')?.value || '';
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=verify_password&password=${encodeURIComponent(password)}&user_id=${currentEditUserId}&_token=${encodeURIComponent(csrfToken)}`
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    closePasswordModal();
                    openEditUserModal(data.user);
                } else {
                    alert(data.message || 'Invalid password');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }

        // Open edit user modal with data
        function openEditUserModal(userData) {
            document.getElementById('edit_user_id').value = userData.id;
            document.getElementById('edit_first_name').value = userData.first_name;
            document.getElementById('edit_last_name').value = userData.last_name;
            document.getElementById('edit_username').value = userData.username;
            document.getElementById('edit_email').value = userData.email;
            document.getElementById('edit_role').value = userData.role;
            
            document.getElementById('editModal').style.display = 'block';
        }

        // Reset Password Functions
        let currentResetUserId = null;
        let currentResetUserName = null;
        let verifiedAdminPassword = null;

        function openResetPasswordModal(userId, userName) {
            currentResetUserId = userId;
            currentResetUserName = userName;
            
            // Show verification modal first
            document.getElementById('verify_reset_user_id').value = userId;
            document.getElementById('resetPasswordVerifyUserInfo').textContent = `Reset password for: ${userName}`;
            document.getElementById('resetPasswordVerifyForm').reset();
            document.getElementById('resetPasswordVerifyModal').style.display = 'block';
            
            // Focus on password field
            setTimeout(() => {
                document.getElementById('admin_password_verify').focus();
            }, 100);
        }

        function closeResetPasswordVerifyModal() {
            document.getElementById('resetPasswordVerifyModal').style.display = 'none';
            document.getElementById('resetPasswordVerifyForm').reset();
            verifiedAdminPassword = null;
        }

        function verifyResetPassword() {
            const form = document.getElementById('resetPasswordVerifyForm');
            const formData = new FormData(form);
            const adminPassword = document.getElementById('admin_password_verify').value;
            
            if (!adminPassword) {
                alert('Please enter your password');
                return;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="button"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Store verified password for the reset step
                    verifiedAdminPassword = adminPassword;
                    closeResetPasswordVerifyModal();
                    openResetPasswordForm(data.user);
                } else {
                    alert(data.message || 'Verification failed');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    document.getElementById('admin_password_verify').focus();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        function openResetPasswordForm(userData) {
            document.getElementById('reset_user_id').value = userData.id;
            document.getElementById('reset_admin_password').value = verifiedAdminPassword;
            document.getElementById('resetPasswordUserInfo').innerHTML = `
                <div style="margin-top: 5px;">
                    <strong>${userData.name}</strong><br>
                    <small>Username: ${userData.username}</small><br>
                    <small>Email: ${userData.email}</small><br>
                    <small>Role: ${userData.role.replace('_', ' ').toUpperCase()}</small>
                </div>
            `;
            document.getElementById('resetPasswordForm').reset();
            document.getElementById('generate_password').checked = true;
            togglePasswordInput();
            
            document.getElementById('resetPasswordModal').style.display = 'block';
        }

        function closeResetPasswordModal() {
            document.getElementById('resetPasswordModal').style.display = 'none';
            document.getElementById('resetPasswordForm').reset();
            verifiedAdminPassword = null;
            currentResetUserId = null;
            currentResetUserName = null;
        }

        function togglePasswordInput() {
            const generateCheckbox = document.getElementById('generate_password');
            const passwordInputGroup = document.getElementById('passwordInputGroup');
            const passwordInput = document.getElementById('new_password');
            
            if (generateCheckbox.checked) {
                passwordInputGroup.style.display = 'none';
                passwordInput.removeAttribute('required');
                passwordInput.value = '';
            } else {
                passwordInputGroup.style.display = 'block';
                passwordInput.setAttribute('required', 'required');
                passwordInput.focus();
            }
        }

        function resetPassword() {
            const form = document.getElementById('resetPasswordForm');
            const formData = new FormData(form);
            const generatePassword = document.getElementById('generate_password').checked;
            
            // Double-check admin password is still set
            if (!verifiedAdminPassword) {
                alert('Security verification expired. Please start over.');
                closeResetPasswordModal();
                return;
            }
            
            // Ensure admin password is in form data
            formData.set('admin_password', verifiedAdminPassword);
            
            if (!generatePassword) {
                const newPassword = document.getElementById('new_password').value;
                if (!newPassword || newPassword.length < 6) {
                    alert('Please enter a password with at least 6 characters');
                    return;
                }
            }
            
            // Final confirmation
            const userId = document.getElementById('reset_user_id').value;
            const userName = currentResetUserName || 'this user';
            
            if (!confirm(`Are you sure you want to reset the password for ${userName}? This action cannot be undone.`)) {
                return;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="button"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Resetting...';
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeResetPasswordModal();
                    showPasswordDisplay(data);
                } else {
                    alert(data.message || 'Failed to reset password');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        function showPasswordDisplay(data) {
            document.getElementById('passwordDisplayUserInfo').textContent = 
                `${data.user.name} (${data.user.username})`;
            document.getElementById('displayed_password').value = data.new_password;
            document.getElementById('passwordDisplayModal').style.display = 'block';
        }

        function closePasswordDisplayModal() {
            document.getElementById('passwordDisplayModal').style.display = 'none';
            // Reload page with success message
            window.location.href = 'accounts.php?success=' + encodeURIComponent('Password reset successfully');
        }

        function copyPassword() {
            const passwordInput = document.getElementById('displayed_password');
            passwordInput.select();
            passwordInput.setSelectionRange(0, 99999); // For mobile devices
            
            try {
                document.execCommand('copy');
                // Show temporary feedback
                const copyBtn = event.target.closest('button');
                const originalHTML = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="fas fa-check"></i>';
                copyBtn.style.backgroundColor = '#28a745';
                setTimeout(() => {
                    copyBtn.innerHTML = originalHTML;
                    copyBtn.style.backgroundColor = '';
                }, 2000);
            } catch (err) {
                // Fallback for modern browsers
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(passwordInput.value).then(() => {
                        const copyBtn = event.target.closest('button');
                        const originalHTML = copyBtn.innerHTML;
                        copyBtn.innerHTML = '<i class="fas fa-check"></i>';
                        copyBtn.style.backgroundColor = '#28a745';
                        setTimeout(() => {
                            copyBtn.innerHTML = originalHTML;
                            copyBtn.style.backgroundColor = '';
                        }, 2000);
                    });
                } else {
                    alert('Failed to copy password. Please select and copy manually.');
                }
            }
        }

        // Delete Account Modal Functions
        function openDeleteAccountModal(userId, userName) {
            document.getElementById('delete_account_id').value = userId;
            document.getElementById('deleteAccountModal').style.display = 'block';
            document.getElementById('deleteAccountPassword').focus();
        }

        function closeDeleteAccountModal() {
            document.getElementById('deleteAccountModal').style.display = 'none';
            document.getElementById('deleteAccountForm').reset();
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const passwordModal = document.getElementById('passwordModal');
            const editModal = document.getElementById('editModal');
            const deleteAccountModal = document.getElementById('deleteAccountModal');
            const resetPasswordVerifyModal = document.getElementById('resetPasswordVerifyModal');
            const resetPasswordModal = document.getElementById('resetPasswordModal');
            const passwordDisplayModal = document.getElementById('passwordDisplayModal');
            
            if (event.target === passwordModal) {
                closePasswordModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
            if (event.target === deleteAccountModal) {
                closeDeleteAccountModal();
            }
            if (event.target === resetPasswordVerifyModal) {
                closeResetPasswordVerifyModal();
            }
            if (event.target === resetPasswordModal) {
                closeResetPasswordModal();
            }
            if (event.target === passwordDisplayModal) {
                closePasswordDisplayModal();
            }
        }
    </script>

    <script src="../assets/js/script.js"></script>

<?php include '../app/includes/admin-footer.php'; ?>
