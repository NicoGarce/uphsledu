<?php
/**
 * UPHSL Posts Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Displays all published posts and news articles for the UPHSL website
 */

session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Get current page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1

// Get posts for current page
$posts = getPublishedPosts($page, 10);
$totalPosts = getPublishedPostsCount();
$totalPages = ceil($totalPosts / 10);

// Get recent posts for sidebar
$recentPosts = getRecentPosts(5);

// Set page title
$page_title = "All Posts - University of Perpetual Help System";

// Set base path for assets
$base_path = '';

// Add posts-specific CSS
$additional_css = ['assets/css/posts.css'];

// Include header
include 'app/includes/header.php';
?>

<!-- Posts Content -->
    <div class="posts-container">
        <div class="posts-header">
            <h1 class="posts-title">
                <i class="fas fa-newspaper"></i>
                University News & Announcements
            </h1>
            <p class="posts-subtitle">
                Stay updated with the latest news and announcements from the University of Perpetual Help System Laguna.
            </p>
        </div>

        <div class="posts-content">
            <div class="posts-main">
                <?php if (!empty($posts)): ?>
                    <div class="posts-grid">
                        <?php foreach ($posts as $post): ?>
                            <article class="post-card">
                                <div class="post-card-image">
                                    <?php if ($post['featured_image']): ?>
                                        <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($post['title']); ?>"
                                             class="card-image"
                                             decoding="async">
                                    <?php else: ?>
                                        <div class="card-image-placeholder">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="post-card-overlay">
                                        <a href="post.php?slug=<?php echo $post['slug']; ?>" class="read-more-btn">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="post-card-content">
                                    <div class="post-card-meta">
                                        <span class="post-date">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo formatDate($post['published_at'] ?: $post['created_at']); ?>
                                        </span>
                                        <span class="post-author">
                                            <i class="fas fa-user"></i>
                                            University of Perpetual Help System Laguna
                                        </span>
                                    </div>
                                    
                                    <h2 class="post-card-title">
                                        <a href="post.php?slug=<?php echo $post['slug']; ?>">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </a>
                                    </h2>
                                    
                                    <div class="post-card-footer">
                                        <div class="post-stats">
                                            <span class="post-views">
                                                <i class="fas fa-eye"></i>
                                                <?php echo $post['views']; ?> views
                                            </span>
                                        </div>
                                        <a href="post.php?slug=<?php echo $post['slug']; ?>" class="read-more-link">
                                            Read More <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination-container">
                            <div class="pagination">
                                <?php if ($page > 1): ?>
                                    <a href="posts.php?page=<?php echo $page - 1; ?>" class="pagination-btn prev-btn">
                                        <i class="fas fa-chevron-left"></i>
                                        Previous
                                    </a>
                                <?php endif; ?>

                                <div class="pagination-numbers">
                                    <?php
                                    $startPage = max(1, $page - 2);
                                    $endPage = min($totalPages, $page + 2);
                                    
                                    if ($startPage > 1): ?>
                                        <a href="posts.php?page=1" class="pagination-number">1</a>
                                        <?php if ($startPage > 2): ?>
                                            <span class="pagination-ellipsis">...</span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                        <a href="posts.php?page=<?php echo $i; ?>" 
                                           class="pagination-number <?php echo $i === $page ? 'active' : ''; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>

                                    <?php if ($endPage < $totalPages): ?>
                                        <?php if ($endPage < $totalPages - 1): ?>
                                            <span class="pagination-ellipsis">...</span>
                                        <?php endif; ?>
                                        <a href="posts.php?page=<?php echo $totalPages; ?>" class="pagination-number">
                                            <?php echo $totalPages; ?>
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <?php if ($page < $totalPages): ?>
                                    <a href="posts.php?page=<?php echo $page + 1; ?>" class="pagination-btn next-btn">
                                        Next
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="pagination-info">
                                <p>Showing <?php echo (($page - 1) * 10) + 1; ?>-<?php echo min($page * 10, $totalPosts); ?> of <?php echo $totalPosts; ?> posts</p>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="empty-posts">
                        <div class="empty-posts-content">
                            <i class="fas fa-newspaper"></i>
                            <h3>No Posts Available</h3>
                            <p>There are no published posts at the moment. Check back later for updates.</p>
                            <a href="index.php" class="btn btn-primary">Go to Homepage</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <aside class="posts-sidebar">
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Recent Posts</h3>
                    <div class="recent-posts">
                        <?php foreach ($recentPosts as $recentPost): ?>
                            <div class="recent-post-item">
                                <a href="post.php?slug=<?php echo $recentPost['slug']; ?>" class="recent-post-link">
                                    <h4 class="recent-post-title"><?php echo htmlspecialchars($recentPost['title']); ?></h4>
                                    <span class="recent-post-date"><?php echo formatDate($recentPost['published_at'] ?: $recentPost['created_at']); ?></span>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="sidebar-section">
                    <h3 class="sidebar-title">University Info</h3>
                    <div class="university-info">
                        <p>Stay connected with the University of Perpetual Help System Laguna for the latest news, announcements, and updates.</p>
                        <a href="index.php" class="btn btn-primary">Visit Homepage</a>
                    </div>
                </div>

                <div class="sidebar-section">
                    <h3 class="sidebar-title">Quick Links</h3>
                    <div class="quick-links">
                        <a href="index.php" class="quick-link">
                            <i class="fas fa-home"></i>
                            Homepage
                        </a>
                        <a href="about" class="quick-link">
                            <i class="fas fa-info-circle"></i>
                            About Us
                        </a>
                        <a href="about/contact.php" class="quick-link">
                            <i class="fas fa-envelope"></i>
                            Contact
                        </a>
                        <?php if (isLoggedIn()): ?>
                            <a href="dashboard.php" class="quick-link">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>

<?php include 'app/includes/footer.php'; ?>
