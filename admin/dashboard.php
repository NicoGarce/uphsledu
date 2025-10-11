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

$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Set base path for assets
$base_path = '../';

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - University of Perpetual Help System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@400;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/logos/logo.png">
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/logo.png">
    <link rel="apple-touch-icon" href="../assets/images/logos/logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="../">
                        <img src="../assets/images/logos/logo.png" alt="University of Perpetual Help System" class="logo-img">
                </a>
            </div>
            <div class="nav-menu">
                        <a href="../" class="nav-link">Home</a>
                <a href="dashboard.php" class="nav-link active">Dashboard</a>
                <?php if (isAuthor() || isSuperAdmin()): ?>
                    <a href="create-post.php" class="nav-link">Create Post</a>
                <?php endif; ?>
                <?php if (isAdmin()): ?>
                    <a href="users.php" class="nav-link">Users</a>
                <?php endif; ?>
                <?php if (isSuperAdmin()): ?>
                    <a href="accounts.php" class="nav-link">Account Management</a>
                <?php endif; ?>
            </div>
            <div class="user-menu">
                <span class="user-name"><?php echo htmlspecialchars($user['first_name']); ?></span>
                <a href="../auth/logout.php" class="nav-link">Logout</a>
            </div>
        </div>
    </nav>

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

        <!-- Quick Actions -->
        <div class="dashboard-section">
            <h2 class="section-title">Quick Actions</h2>
            <div class="quick-actions">
                <?php if (isAuthor() || isSuperAdmin()): ?>
                    <a href="create-post.php" class="action-card">
                        <i class="fas fa-plus"></i>
                        <span>Create New Post</span>
                    </a>
                <?php endif; ?>
                
                <a href="posts.php" class="action-card">
                    <i class="fas fa-list"></i>
                    <span>My Posts</span>
                </a>
                
                <?php if (isAdmin()): ?>
                    <a href="users.php" class="action-card">
                        <i class="fas fa-users-cog"></i>
                        <span>Manage Users</span>
                    </a>
                    
                    <a href="settings.php" class="action-card">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                <?php endif; ?>
                
                <?php if (isSuperAdmin()): ?>
                    <a href="accounts.php" class="action-card">
                        <i class="fas fa-user-plus"></i>
                        <span>Create Accounts</span>
                    </a>
                    
                    <a href="posts.php" class="action-card">
                        <i class="fas fa-edit"></i>
                        <span>Edit Posts</span>
                    </a>
                    
                    <a href="role-management.php" class="action-card">
                        <i class="fas fa-shield-alt"></i>
                        <span>Role Management</span>
                    </a>
                <?php endif; ?>
                
                <a href="profile.php" class="action-card">
                    <i class="fas fa-user-edit"></i>
                    <span>Edit Profile</span>
                </a>
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
                                        <?php echo formatDate($post['created_at']); ?>
                                    </span>
                                    <?php if ($userRole === 'super_admin' || $userRole === 'admin'): ?>
                                        <span class="post-author">
                                            by <?php echo htmlspecialchars($post['author_name']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="post-actions">
                                <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete-post.php?id=<?php echo $post['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this post?')">
                                    <i class="fas fa-trash"></i>
                                </a>
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
</body>
</html>

