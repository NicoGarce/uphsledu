<?php
/**
 * UPHSL Admin User Management
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for managing user accounts and permissions
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../auth/login.php');
}

$pdo = getDBConnection();
$message = '';
$error = '';

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $userId = $_POST['user_id'];
    
    if ($action === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND id != ?");
            $stmt->execute([$userId, $_SESSION['user_id']]);
            $message = 'User deleted successfully';
        } catch (PDOException $e) {
            $error = 'Failed to delete user';
        }
    } elseif ($action === 'change_role') {
        $newRole = $_POST['new_role'];
        try {
            $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ? AND id != ?");
            $stmt->execute([$newRole, $userId, $_SESSION['user_id']]);
            $message = 'User role updated successfully';
        } catch (PDOException $e) {
            $error = 'Failed to update user role';
        }
    }
}

// Get all users
$stmt = $pdo->query("
    SELECT u.*, 
           COUNT(p.id) as post_count
    FROM users u 
    LEFT JOIN posts p ON u.id = p.author_id 
    GROUP BY u.id 
    ORDER BY u.created_at DESC
");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
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
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <?php if (isAuthor() || isSuperAdmin()): ?>
                    <a href="create-post.php" class="nav-link">Create Post</a>
                <?php endif; ?>
                <a href="users.php" class="nav-link active">Users</a>
                <?php if (isSuperAdmin()): ?>
                    <a href="accounts.php" class="nav-link">Account Management</a>
                <?php endif; ?>
            </div>
            <div class="user-menu">
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['first_name'] ?? ''); ?></span>
                <a href="../auth/logout.php" class="nav-link">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Admin Content -->
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">
                <i class="fas fa-users-cog"></i>
                Manage Users
            </h1>
            <p class="admin-subtitle">Manage user accounts and permissions</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Users Table -->
        <div class="admin-section">
            <div class="section-header">
                <h2 class="section-title">All Users</h2>
                <div class="section-actions">
                    <a href="add-user.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Add User
                    </a>
                </div>
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Posts</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="user-details">
                                            <div class="user-name">
                                                <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                            </div>
                                            <div class="user-username">
                                                @<?php echo htmlspecialchars($user['username']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="role-badge role-<?php echo $user['role']; ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo $user['post_count']; ?></td>
                                <td><?php echo formatDate($user['created_at']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-secondary" 
                                                onclick="editUser(<?php echo $user['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <form method="POST" style="display: inline;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
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

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit User</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form id="editUserForm" method="POST">
                <input type="hidden" name="action" value="change_role">
                <input type="hidden" name="user_id" id="editUserId">
                
                <div class="form-group">
                    <label for="new_role" class="form-label">Role</label>
                    <select name="new_role" id="new_role" class="form-input" required>
                        <option value="user">User</option>
                        <option value="author">Author</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Role</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>
    <script>
        function editUser(userId) {
            document.getElementById('editUserId').value = userId;
            document.getElementById('editUserModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editUserModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editUserModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>

