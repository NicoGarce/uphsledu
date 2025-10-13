<?php
/**
 * UPHSL Admin Account Management
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for managing user accounts and system settings
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

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
        
        if ($userId && $userId != $_SESSION['user_id']) {
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
        <?php endif; ?>

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
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="openEditModal(<?php echo $account['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if (isSuperAdmin() && $account['id'] != $_SESSION['user_id']): ?>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this account?')">
                                                <input type="hidden" name="action" value="delete_user">
                                                <input type="hidden" name="user_id" value="<?php echo $account['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=verify_password&password=${encodeURIComponent(password)}&user_id=${currentEditUserId}`
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

        // Close modals when clicking outside
        window.onclick = function(event) {
            const passwordModal = document.getElementById('passwordModal');
            const editModal = document.getElementById('editModal');
            
            if (event.target === passwordModal) {
                closePasswordModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
        }
    </script>

    <script src="../assets/js/script.js"></script>

<?php include '../app/includes/admin-footer.php'; ?>
