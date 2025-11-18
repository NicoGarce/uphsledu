<?php
/**
 * UPHSL Admin Dashboard
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Main administrative dashboard for managing the UPHSL website content and users
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
// Session is automatically initialized by security.php

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

// Redirect Author users to author dashboard
if (isAuthor()) {
    redirect('author-dashboard.php');
}

// HR users can access dashboard (no redirect needed)

$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Handle post deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_post') {
    // Verify CSRF token
    if (!CSRF::verify()) {
        $error = 'Security token mismatch. Please refresh the page and try again.';
    } else {
        $postId = (int)$_POST['post_id'];
        $password = $_POST['password'] ?? '';
        
        // Verify password
        if (empty($password) || !verifyUserPassword($_SESSION['user_id'], $password)) {
            $error = "Invalid password. Please try again.";
        } elseif ($postId > 0) {
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
$recentCareers = [];

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
    
} elseif ($userRole === 'hr') {
    // HR dashboard data - all career postings
    $pdo = getDBConnection();
    
    // All career postings
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM careers_postings");
    $stats['total_careers'] = $stmt->fetch()['count'];
    
    // Published career postings
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM careers_postings WHERE status = 'published'");
    $stats['published_careers'] = $stmt->fetch()['count'];
    
    // Draft career postings
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM careers_postings WHERE status = 'draft'");
    $stats['draft_careers'] = $stmt->fetch()['count'];
    
    // Recent career postings (all)
    $recentCareers = getAllCareerPostings();
    
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
            
            <?php if ($userRole === 'hr'): ?>
                <!-- HR Career Postings Stats -->
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number"><?php echo isset($stats['total_careers']) ? $stats['total_careers'] : 0; ?></h3>
                        <p class="stat-label">Total Career Postings</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number"><?php echo isset($stats['published_careers']) ? $stats['published_careers'] : 0; ?></h3>
                        <p class="stat-label">Published</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number"><?php echo isset($stats['draft_careers']) ? $stats['draft_careers'] : 0; ?></h3>
                        <p class="stat-label">Drafts</p>
                    </div>
                </div>
            <?php else: ?>
                <!-- Posts Stats for Admin/Author -->
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number"><?php echo isset($stats['total_posts']) ? $stats['total_posts'] : 0; ?></h3>
                        <p class="stat-label">Total Posts</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number"><?php echo isset($stats['published_posts']) ? $stats['published_posts'] : 0; ?></h3>
                        <p class="stat-label">Published</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number"><?php echo isset($stats['draft_posts']) ? $stats['draft_posts'] : 0; ?></h3>
                        <p class="stat-label">Drafts</p>
                    </div>
                </div>
            <?php endif; ?>
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
                <?php if (isHR()): ?>
                    <a href="create-career.php" class="action-card">
                        <i class="fas fa-plus-circle"></i>
                        <span>Create Career Posting</span>
                    </a>
                    <a href="careers.php" class="action-card">
                        <i class="fas fa-briefcase"></i>
                        <span>Manage Careers</span>
                    </a>
                <?php endif; ?>
                
                <?php if (isAuthor() || isAdmin() || isSuperAdmin()): ?>
                    <a href="posts.php" class="action-card">
                        <i class="fas fa-edit"></i>
                        <span>Post Management</span>
                    </a>
                    <a href="sdg-initiatives.php" class="action-card">
                        <i class="fas fa-globe-americas"></i>
                        <span>SDG Initiatives</span>
                    </a>
                <?php endif; ?>
                
                <?php if (isSuperAdmin()): ?>
                    <a href="accounts.php" class="action-card">
                        <i class="fas fa-users-cog"></i>
                        <span>Account Management</span>
                    </a>
                    
                    <a href="../online_payment/csv.php" class="action-card" target="_blank">
                        <i class="fas fa-file-excel"></i>
                        <span>Excel Import</span>
                    </a>
                <?php endif; ?>
                
            </div>
        </div>


        <?php if ($userRole === 'hr'): ?>
            <!-- Recent Career Postings for HR -->
            <div class="dashboard-section">
                <h2 class="section-title">Recent Career Postings</h2>
                <?php if (!empty($recentCareers)): ?>
                    <div class="posts-list">
                        <?php foreach (array_slice($recentCareers, 0, 5) as $career): ?>
                            <div class="post-item">
                                <div class="post-info">
                                    <h3 class="post-title">
                                        <a href="create-career.php?edit=<?php echo $career['id']; ?>">
                                            <?php echo htmlspecialchars($career['position']); ?>
                                        </a>
                                    </h3>
                                    <div class="post-meta">
                                        <span class="post-status status-<?php echo $career['status']; ?>">
                                            <?php echo ucfirst($career['status']); ?>
                                        </span>
                                        <span class="post-category">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo htmlspecialchars($career['location']); ?>
                                        </span>
                                        <span class="post-category">
                                            <i class="fas fa-clock"></i>
                                            <?php echo htmlspecialchars($career['employment_type']); ?>
                                        </span>
                                        <span class="post-date">
                                            <?php echo formatDate($career['published_at'] ?: $career['created_at']); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="post-actions">
                                    <a href="create-career.php?edit=<?php echo $career['id']; ?>" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="deleteCareer(<?php echo $career['id']; ?>)" title="Delete Posting">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-briefcase"></i>
                        <h3>No career postings yet</h3>
                        <p>Start creating career opportunities!</p>
                        <a href="create-career.php" class="btn btn-primary">Create Your First Career Posting</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
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
                                    <button class="btn btn-sm btn-danger" onclick="deletePost(<?php echo $post['id']; ?>)" title="Delete Post">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
        <?php endif; ?>
    </div>

        <?php if ($userRole === 'super_admin' || $userRole === 'admin'): ?>
            <!-- Delete Confirmation Modal for Posts -->
            <div id="deletePostModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Delete Post</h3>
                        <span class="close" onclick="closeDeletePostModal()">&times;</span>
                    </div>
                    <div class="modal-body">
                        <p>Please enter your password to confirm this action.</p>
                        <form id="deletePostForm" method="POST">
                            <?php echo CSRF::field(); ?>
                            <input type="hidden" name="action" value="delete_post">
                            <input type="hidden" name="post_id" id="delete_post_id">
                            
                            <div style="margin-bottom: 1rem;">
                                <label for="deletePostPassword" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password:</label>
                                <input type="password" id="deletePostPassword" name="password" required 
                                       style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;"
                                       placeholder="Enter your password" autocomplete="current-password">
                            </div>
                            
                            <div class="modal-actions">
                                <button type="button" class="btn btn-secondary" onclick="closeDeletePostModal()">Cancel</button>
                                <button type="submit" class="btn btn-danger">Delete Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($userRole === 'hr'): ?>
            <!-- Delete Confirmation Modal for Careers -->
            <div id="deleteModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Delete Career Posting</h3>
                        <span class="close" onclick="closeDeleteModal()">&times;</span>
                    </div>
                    <div class="modal-body">
                        <p>Please enter your password to confirm this action.</p>
                        <form id="deleteForm" method="POST" action="careers.php">
                            <?php echo CSRF::field(); ?>
                            <input type="hidden" name="action" value="delete_career">
                            <input type="hidden" name="career_id" id="delete_career_id">
                            
                            <div style="margin-bottom: 1rem;">
                                <label for="deletePassword" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password:</label>
                                <input type="password" id="deletePassword" name="password" required 
                                       style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;"
                                       placeholder="Enter your password" autocomplete="current-password">
                            </div>
                            
                            <div class="modal-actions">
                                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                                <button type="submit" class="btn btn-danger">Delete Posting</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <script>
                function deleteCareer(careerId) {
                    document.getElementById('delete_career_id').value = careerId;
                    document.getElementById('deleteModal').style.display = 'block';
                }
                
                function closeDeleteModal() {
                    document.getElementById('deleteModal').style.display = 'none';
                }
            </script>
        <?php endif; ?>
        
        <?php if ($userRole === 'super_admin' || $userRole === 'admin'): ?>
            <script>
                function deletePost(postId) {
                    document.getElementById('delete_post_id').value = postId;
                    document.getElementById('deletePostModal').style.display = 'block';
                }
                
                function closeDeletePostModal() {
                    document.getElementById('deletePostModal').style.display = 'none';
                    document.getElementById('deletePostPassword').value = '';
                }
            </script>
        <?php endif; ?>
        
        <!-- Shared modal click-outside handler -->
        <script>
            // Close modals when clicking outside
            window.onclick = function(event) {
                const deleteModal = document.getElementById('deleteModal');
                const deletePostModal = document.getElementById('deletePostModal');
                
                if (deleteModal && event.target === deleteModal) {
                    deleteModal.style.display = 'none';
                }
                if (deletePostModal && event.target === deletePostModal) {
                    deletePostModal.style.display = 'none';
                    const passwordField = document.getElementById('deletePostPassword');
                    if (passwordField) passwordField.value = '';
                }
            }
        </script>
        
        <!-- Shared Modal Styles -->
        <style>
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0,0,0,0.5);
            }
            .modal-content {
                background-color: #fefefe;
                margin: auto;
                padding: 0;
                border: 1px solid #888;
                border-radius: 8px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                width: 90%;
                max-width: 500px;
                position: relative;
                top: 50%;
                transform: translateY(-50%);
            }
            .modal-header {
                padding: 20px;
                border-bottom: 1px solid #dee2e6;
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: #1c4da1;
                border-radius: 8px 8px 0 0;
            }
            .modal-header h3 {
                margin: 0;
                color: white;
                font-size: 1.2rem;
                font-weight: 600;
            }
            .modal-body {
                padding: 20px;
            }
            .close {
                color: white;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
                line-height: 1;
            }
            .close:hover,
            .close:focus {
                color: #f0f0f0;
                opacity: 0.8;
            }
            .modal-actions {
                display: flex;
                gap: 10px;
                justify-content: flex-end;
                margin-top: 20px;
            }
        </style>
        
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

