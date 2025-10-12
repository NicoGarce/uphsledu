<?php
/**
 * UPHSL Author Dashboard
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Author-specific dashboard for managing posts
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if user is logged in and is author
if (!isLoggedIn() || !isAuthor()) {
    redirect('../auth/login.php');
}

$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Set page title for header
$page_title = 'Author Dashboard';

// Get author's posts
$pdo = getDBConnection();
$stmt = $pdo->prepare("
    SELECT p.*, 
           COUNT(pi.id) as image_count
    FROM posts p 
    LEFT JOIN post_images pi ON p.id = pi.post_id 
    WHERE p.author_id = ? 
    GROUP BY p.id 
    ORDER BY p.created_at DESC 
    LIMIT 10
");
$stmt->execute([$_SESSION['user_id']]);
$recentPosts = $stmt->fetchAll();

// Get post statistics
$stats = [
    'total_posts' => 0,
    'published_posts' => 0,
    'draft_posts' => 0
];

$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_posts,
        SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published_posts,
        SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft_posts
    FROM posts 
    WHERE author_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$postStats = $stmt->fetch();

if ($postStats) {
    $stats['total_posts'] = $postStats['total_posts'];
    $stats['published_posts'] = $postStats['published_posts'];
    $stats['draft_posts'] = $postStats['draft_posts'];
}


// Include admin header
include '../app/includes/admin-header.php';
?>

<div class="dashboard-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="fas fa-edit"></i>
            Author Dashboard
        </h1>
        <p class="dashboard-subtitle">Manage your posts and track your content performance</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $stats['total_posts']; ?></h3>
                <p>Total Posts</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon published">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $stats['published_posts']; ?></h3>
                <p>Published</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon draft">
                <i class="fas fa-edit"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $stats['draft_posts']; ?></h3>
                <p>Drafts</p>
            </div>
        </div>
        
    </div>

    <!-- Quick Actions -->
    <div class="dashboard-section">
        <h2 class="section-title">Quick Actions</h2>
        <div class="quick-actions">
            <a href="create-post.php" class="action-card">
                <i class="fas fa-plus"></i>
                <span>Create New Post</span>
            </a>
            <a href="posts.php" class="action-card">
                <i class="fas fa-list"></i>
                <span>Manage Posts</span>
            </a>
        </div>
    </div>

    <!-- Recent Posts -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2 class="section-title">Recent Posts</h2>
            <a href="posts.php" class="btn btn-secondary">View All</a>
        </div>
        
        <?php if (empty($recentPosts)): ?>
            <div class="empty-state">
                <i class="fas fa-file-alt"></i>
                <h3>No posts yet</h3>
                <p>Start creating amazing content!</p>
                <a href="create-post.php" class="btn btn-primary">Create Your First Post</a>
            </div>
        <?php else: ?>
            <div class="posts-table">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentPosts as $post): ?>
                            <tr>
                                <td>
                                    <div class="post-title-cell">
                                        <h4><?php echo htmlspecialchars($post['title']); ?></h4>
                                        <p class="post-excerpt"><?php echo htmlspecialchars(substr($post['excerpt'] ?: $post['content'], 0, 100)) . '...'; ?></p>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $post['status']; ?>">
                                        <?php echo ucfirst($post['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="post-date">
                                        <?php echo date('M j, Y', strtotime($post['published_at'] ?: $post['created_at'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="create-post.php?edit=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </a>
                                        <a href="../post.php?slug=<?php echo $post['slug']; ?>" class="btn btn-sm btn-secondary" target="_blank">
                                            <i class="fas fa-external-link-alt"></i>
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Author Dashboard Specific Styles */
.post-title-cell h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
}

.post-title-cell .post-excerpt {
    margin: 0;
    font-size: 0.875rem;
    color: var(--text-gray);
    line-height: 1.4;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-published {
    background: #d1fae5;
    color: #065f46;
}

.status-draft {
    background: #fef3c7;
    color: #92400e;
}

.status-archived {
    background: #f3f4f6;
    color: #374151;
}


.action-buttons {
    display: flex;
    gap: 0.5rem;
}
</style>

<?php include '../app/includes/admin-footer.php'; ?>
