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

// Handle Facebook token refresh
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'refresh_facebook_token') {
    if ($userRole === 'super_admin' || $userRole === 'admin') {
        try {
            $pdo = getDBConnection();
            
            // First, ensure the facebook_tokens table exists
            try {
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS facebook_tokens (
                        id INT PRIMARY KEY DEFAULT 1,
                        app_id VARCHAR(255) NOT NULL,
                        app_secret VARCHAR(255) NOT NULL,
                        page_id VARCHAR(255) NOT NULL,
                        page_access_token TEXT NOT NULL,
                        token_expires_at TIMESTAMP NOT NULL,
                        last_refreshed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    )
                ");
            } catch (Exception $e) {
                $error = "Error creating facebook_tokens table: " . $e->getMessage();
            }
            
            // Get current token data
            $stmt = $pdo->prepare("SELECT * FROM facebook_tokens WHERE id = 1");
            $stmt->execute();
            $tokenData = $stmt->fetch();
            
            if (!$tokenData) {
                // Insert default token data if none exists
                $appId = '1179084790748948';
                $appSecret = '3e48bf434afadfc30f8971e5b8d72c3b';
                $pageId = '1219056361442381';
                $pageAccessToken = 'EAAQwXxIc1xQBPq7My47R4l0i9HHSrCDk8Nt2bnhGtyfiDLStr1c3EVBZAQ5eeLiKri7IJmGYLUmbcTC22p9s8vyTvEy3lGF5yfn2W6LbpNPA3glOQb5R5H3ZC6i5Rgb96ACxe8ZAO0htyqZBevEZB0uGbvgEvjoyOZBTjyAiVCFGSMY8qQKcIdRYWxTaYzQU4yxz2f9MA5';
                $expiresAt = date('Y-m-d H:i:s', time() + 5183062); // 60 days
                
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO facebook_tokens (id, app_id, app_secret, page_id, page_access_token, token_expires_at, created_at, updated_at) 
                        VALUES (1, ?, ?, ?, ?, ?, NOW(), NOW())
                    ");
                    $stmt->execute([$appId, $appSecret, $pageId, $pageAccessToken, $expiresAt]);
                    
                    // Get the newly inserted data
                    $stmt = $pdo->prepare("SELECT * FROM facebook_tokens WHERE id = 1");
                    $stmt->execute();
                    $tokenData = $stmt->fetch();
                    
                    $success = "Facebook integration initialized successfully! Token expires in 60 days.";
                } catch (Exception $e) {
                    $error = "Error initializing Facebook integration: " . $e->getMessage();
                }
            }
            
            if ($tokenData) {
                // Check if token needs refresh
                $expiresAt = strtotime($tokenData['token_expires_at']);
                $daysUntilExpiry = ($expiresAt - time()) / 86400;
                
                if ($daysUntilExpiry <= 7) {
                    // Token needs refresh
                    $appId = $tokenData['app_id'];
                    $appSecret = $tokenData['app_secret'];
                    $currentToken = $tokenData['page_access_token'];
                    
                    // Since we already have a page access token, let's just extend it
                    // Step 1: Get long-lived page access token directly
                    $extendTokenUrl = "https://graph.facebook.com/v18.0/oauth/access_token?" . http_build_query([
                        'grant_type' => 'fb_exchange_token',
                        'client_id' => $appId,
                        'client_secret' => $appSecret,
                        'fb_exchange_token' => $currentToken
                    ]);
                    
                    $context = stream_context_create([
                        'http' => [
                            'method' => 'GET',
                            'timeout' => 30,
                            'header' => 'User-Agent: UPHSL-Website/1.0'
                        ]
                    ]);
                    
                    $response = file_get_contents($extendTokenUrl, false, $context);
                    
                    if ($response === false) {
                        $error = "Failed to connect to Facebook API. Please check your internet connection.";
                    } else {
                        $tokenData = json_decode($response, true);
                        
                        if (isset($tokenData['access_token'])) {
                            $newPageToken = $tokenData['access_token'];
                            $expiresIn = $tokenData['expires_in'] ?? 5184000; // Default to 60 days
                            
                            // Update database with new token
                            $newExpiresAt = date('Y-m-d H:i:s', time() + $expiresIn);
                            
                            $stmt = $pdo->prepare("
                                UPDATE facebook_tokens 
                                SET page_access_token = ?, token_expires_at = ?, last_refreshed_at = NOW(), updated_at = NOW()
                                WHERE id = 1
                            ");
                            $stmt->execute([$newPageToken, $newExpiresAt]);
                            
                            $days = round($expiresIn / 86400);
                            $success = "Facebook token refreshed successfully! New token expires in {$days} days.";
                        } else {
                            $errorMsg = $tokenData['error']['message'] ?? 'Unknown error';
                            $errorCode = $tokenData['error']['code'] ?? 'Unknown';
                            $error = "Failed to refresh Facebook token: {$errorMsg} (Code: {$errorCode})";
                            
                            // If token is invalid, just update the timestamp
                            $stmt = $pdo->prepare("
                                UPDATE facebook_tokens 
                                SET last_refreshed_at = NOW(), updated_at = NOW()
                                WHERE id = 1
                            ");
                            $stmt->execute();
                        }
                    }
                } else {
                    // Token is still valid, but update the last_refreshed_at timestamp anyway
                    $stmt = $pdo->prepare("
                        UPDATE facebook_tokens 
                        SET last_refreshed_at = NOW(), updated_at = NOW()
                        WHERE id = 1
                    ");
                    $stmt->execute();
                    
                    $success = "Token is still valid. No refresh needed. Expires in " . round($daysUntilExpiry) . " days. Last checked time updated.";
                }
            } else {
                $error = "No token data available after initialization attempt.";
            }
        } catch (Exception $e) {
            $error = "Error refreshing Facebook token: " . $e->getMessage();
        }
    } else {
        $error = "You don't have permission to refresh Facebook tokens.";
    }
}

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
                
                <?php if (isAdmin() || isSuperAdmin()): ?>
                    <form method="POST" style="display: inline-block;" onsubmit="return refreshFacebookToken(this)">
                        <input type="hidden" name="action" value="refresh_facebook_token">
                        <button type="submit" class="action-card facebook-refresh-btn" id="facebookRefreshBtn">
                            <i class="fas fa-sync-alt"></i>
                            <span>Refresh Facebook Token</span>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Facebook Token Status -->
        <?php if (isAdmin() || isSuperAdmin()): ?>
        <div class="dashboard-section">
            <h2 class="section-title">
                <i class="fab fa-facebook"></i>
                Facebook Integration Status
            </h2>
            <div class="facebook-status">
                <?php
                try {
                    $pdo = getDBConnection();
                    $stmt = $pdo->prepare("SELECT * FROM facebook_tokens WHERE id = 1");
                    $stmt->execute();
                    $tokenData = $stmt->fetch();
                    
                    if ($tokenData) {
                        $expiresAt = strtotime($tokenData['token_expires_at']);
                        $daysUntilExpiry = ($expiresAt - time()) / 86400;
                        $lastRefreshed = $tokenData['last_refreshed_at'];
                        
                        if ($daysUntilExpiry > 0) {
                            $statusClass = $daysUntilExpiry <= 7 ? 'warning' : 'success';
                            $statusIcon = $daysUntilExpiry <= 7 ? 'exclamation-triangle' : 'check-circle';
                            $statusText = $daysUntilExpiry <= 7 ? 'Expires Soon' : 'Active';
                        } else {
                            $statusClass = 'error';
                            $statusIcon = 'times-circle';
                            $statusText = 'Expired';
                        }
                        ?>
                        <div class="status-card status-<?php echo $statusClass; ?>">
                            <div class="status-header">
                                <i class="fas fa-<?php echo $statusIcon; ?>"></i>
                                <span class="status-text"><?php echo $statusText; ?></span>
                            </div>
                            <div class="status-details">
                                <div class="status-item">
                                    <strong>Page ID:</strong> <?php echo htmlspecialchars($tokenData['page_id']); ?>
                                </div>
                                <div class="status-item">
                                    <strong>Expires:</strong> <?php echo date('F j, Y \a\t g:i A', $expiresAt); ?>
                                </div>
                                <div class="status-item">
                                    <strong>Days Remaining:</strong> <?php echo round($daysUntilExpiry); ?> days
                                </div>
                                <?php if ($lastRefreshed): ?>
                                <div class="status-item" id="lastRefreshedTime">
                                    <strong>Last Refreshed:</strong> 
                                    <span class="refresh-time"><?php echo date('F j, Y \a\t g:i A', strtotime($lastRefreshed)); ?></span>
                                    <small class="refresh-indicator" style="color: #28a745; font-weight: bold; margin-left: 5px;"></small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="status-card status-error">
                            <div class="status-header">
                                <i class="fas fa-times-circle"></i>
                                <span class="status-text">Not Configured</span>
                            </div>
                            <div class="status-details">
                                <p>Facebook integration is not configured. Please set up the database table first.</p>
                                <a href="javascript:void(0)" onclick="alert('Please create the facebook_tokens table in your database with the following structure:\n\nCREATE TABLE facebook_tokens (\n    id INT PRIMARY KEY DEFAULT 1,\n    app_id VARCHAR(255) NOT NULL,\n    app_secret VARCHAR(255) NOT NULL,\n    page_id VARCHAR(255) NOT NULL,\n    page_access_token TEXT NOT NULL,\n    token_expires_at TIMESTAMP NOT NULL,\n    last_refreshed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP\n);')" class="btn btn-sm btn-primary">
                                    <i class="fas fa-info-circle"></i> Setup Instructions
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                } catch (Exception $e) {
                    ?>
                    <div class="status-card status-error">
                        <div class="status-header">
                            <i class="fas fa-times-circle"></i>
                            <span class="status-text">Database Error</span>
                        </div>
                        <div class="status-details">
                            <p>Error accessing Facebook token data: <?php echo htmlspecialchars($e->getMessage()); ?></p>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php endif; ?>

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
        // Facebook token refresh button enhancement
        function refreshFacebookToken(form) {
            const refreshBtn = document.getElementById('facebookRefreshBtn');
            const originalText = refreshBtn.querySelector('span').textContent;
            const originalIcon = refreshBtn.querySelector('i').className;
            
            // Show loading state
            refreshBtn.classList.add('loading');
            refreshBtn.disabled = true;
            refreshBtn.querySelector('span').textContent = 'Refreshing...';
            refreshBtn.querySelector('i').className = 'fas fa-spinner fa-spin';
            
            // Submit the form
            form.submit();
            
            // Don't prevent form submission
            return true;
        }
        
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

