<?php
/**
 * UPHSL Admin Dashboard
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Main administrative dashboard for managing the UPHSL website content and users
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

// Redirect Author users to author dashboard
if (isAuthor()) {
    redirect('author-dashboard.php');
}

$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Handle post deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_post') {
    $postId = (int)$_POST['post_id'];
    
    if ($postId > 0) {
        try {
            $pdo = getDBConnection();
            
            // Check if user has permission to delete this post
            if ($userRole === 'super_admin') {
                // Super admin can delete any post
                $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
                $stmt->execute([$postId]);
            } elseif ($userRole === 'admin') {
                // Admin can delete any post
                $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
                $stmt->execute([$postId]);
            }
            
            if ($stmt->rowCount() > 0) {
                $success = 'Post deleted successfully';
            } else {
                $error = 'Post not found or you do not have permission to delete it';
            }
        } catch (PDOException $e) {
            $error = 'Failed to delete post';
        }
    }
}

// Facebook features removed

// Set page title for header
$page_title = 'Dashboard';

// Initialize messages (only if not already set)
if (!isset($error)) $error = '';
if (!isset($success)) $success = '';

// Get dashboard data based on user role
$stats = [];
$recentPosts = [];

if ($userRole === 'super_admin' || $userRole === 'admin') {
    // Admin dashboard data
    $pdo = getDBConnection();
    
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $stats['total_users'] = $stmt->fetch()['count'];
    
    // Total posts
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM posts");
    $stats['total_posts'] = $stmt->fetch()['count'];
    
    // Published posts
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM posts WHERE status = 'published'");
    $stats['published_posts'] = $stmt->fetch()['count'];
    
    // Draft posts
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM posts WHERE status = 'draft'");
    $stats['draft_posts'] = $stmt->fetch()['count'];
    
    // Recent posts (all authors)
    $recentPosts = getAllPosts();
    
} else {
    // Author/User dashboard data
    $pdo = getDBConnection();
    
    // User's posts
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM posts WHERE author_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $stats['total_posts'] = $stmt->fetch()['count'];
    
    // Published posts
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM posts WHERE author_id = ? AND status = 'published'");
    $stmt->execute([$_SESSION['user_id']]);
    $stats['published_posts'] = $stmt->fetch()['count'];
    
    // Draft posts
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM posts WHERE author_id = ? AND status = 'draft'");
    $stmt->execute([$_SESSION['user_id']]);
    $stats['draft_posts'] = $stmt->fetch()['count'];
    
    // Recent posts (user's only)
    $recentPosts = getAllPosts($_SESSION['user_id']);
}
?>
<?php include '../app/includes/admin-header.php'; ?>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <?php if ($userRole === 'super_admin'): ?>
                    <i class="fas fa-crown"></i>
                    Super Admin Dashboard
                <?php elseif ($userRole === 'admin'): ?>
                    <i class="fas fa-tachometer-alt"></i>
                    Admin Dashboard
                <?php else: ?>
                    <i class="fas fa-user-circle"></i>
                    My Dashboard
                <?php endif; ?>
            </h1>
            <p class="dashboard-subtitle">
                Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>!
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <?php if ($userRole === 'super_admin' || $userRole === 'admin'): ?>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number"><?php echo $stats['total_users']; ?></h3>
                        <p class="stat-label">Total Users</p>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number"><?php echo $stats['total_posts']; ?></h3>
                    <p class="stat-label">Total Posts</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number"><?php echo $stats['published_posts']; ?></h3>
                    <p class="stat-label">Published</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number"><?php echo $stats['draft_posts']; ?></h3>
                    <p class="stat-label">Drafts</p>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="dashboard-section">
            <h2 class="section-title">Quick Actions</h2>
            <div class="quick-actions">
                <?php if (isAuthor() || isAdmin() || isSuperAdmin()): ?>
                    <a href="posts.php" class="action-card">
                        <i class="fas fa-edit"></i>
                        <span>Post Management</span>
                    </a>
                <?php endif; ?>
                
                <?php if (isSuperAdmin()): ?>
                    <a href="accounts.php" class="action-card">
                        <i class="fas fa-users-cog"></i>
                        <span>Account Management</span>
                    </a>
                <?php endif; ?>
                
            </div>
        </div>


        <!-- Recent Posts -->
        <div class="dashboard-section">
            <h2 class="section-title">Recent Posts</h2>
            <?php if (!empty($recentPosts)): ?>
                <div class="posts-list">
                    <?php foreach (array_slice($recentPosts, 0, 5) as $post): ?>
                        <div class="post-item">
                            <div class="post-info">
                                <h3 class="post-title">
                                    <a href="post.php?id=<?php echo $post['id']; ?>">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h3>
                                <div class="post-meta">
                                    <span class="post-status status-<?php echo $post['status']; ?>">
                                        <?php echo ucfirst($post['status']); ?>
                                    </span>
                                    <span class="post-date">
                                        <?php echo formatDate($post['published_at'] ?: $post['created_at']); ?>
                                    </span>
                                    <?php if ($userRole === 'super_admin' || $userRole === 'admin'): ?>
                                        <span class="post-author">
                                            by University of Perpetual Help System Laguna
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="post-actions">
                                <a href="create-post.php?edit=<?php echo $post['id']; ?>" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this post?')">
                                    <input type="hidden" name="action" value="delete_post">
                                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-newspaper"></i>
                    <h3>No posts yet</h3>
                    <p>Start creating amazing content!</p>
                    <?php if (isAuthor()): ?>
                        <a href="create-post.php" class="btn btn-primary">Create Your First Post</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

        <script src="../assets/js/script.js"></script>
        
        <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            // Check if we just refreshed (look for success/error messages)
            const successMsg = document.querySelector('.alert-success');
            const errorMsg = document.querySelector('.alert-error');
            
            if (successMsg || errorMsg) {
                // Scroll to top to show the message
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Highlight the Facebook status section
                const statusSection = document.querySelector('.facebook-status');
                if (statusSection) {
                    statusSection.style.animation = 'pulse 2s ease-in-out';
                }
                
                // Show refresh indicator
                const refreshIndicator = document.querySelector('.refresh-indicator');
                if (refreshIndicator) {
                    refreshIndicator.textContent = '✓ Just Updated';
                    refreshIndicator.style.animation = 'fadeIn 0.5s ease-in';
                    
                    // Remove indicator after 3 seconds
                    setTimeout(() => {
                        refreshIndicator.style.animation = 'fadeOut 0.5s ease-out';
                        setTimeout(() => {
                            refreshIndicator.textContent = '';
                        }, 500);
                    }, 3000);
                }
            }
        });
        </script>
        
        <style>
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); background-color: rgba(24, 119, 242, 0.1); }
            100% { transform: scale(1); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-10px); }
        }
        </style>

<?php include '../app/includes/admin-footer.php'; ?>

