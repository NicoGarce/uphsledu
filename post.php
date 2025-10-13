<?php
/**
 * UPHSL Individual Post Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Displays individual blog posts and news articles for the UPHSL website
 */

session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Get post slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    http_response_code(404);
    include '404.php';
    exit;
}

// Get post by slug
$post = getPostBySlug($slug);

if (!$post) {
    http_response_code(404);
    include '404.php';
    exit;
}

// Increment view count
incrementPostViews($post['id']);

// Get post images
$images = getPostImages($post['id']);

// Get recent posts for sidebar
$recentPosts = getRecentPosts(5);

// Set base path for assets
$base_path = '';

// Set additional CSS for post page
$additional_css = ['assets/css/post.css'];

// Include header
include 'app/includes/header.php';
?>

    <!-- Post Content -->
    <div class="post-container">
        <div class="post-content">
            <!-- Post Header -->
            <header class="post-header">
                <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                <div class="post-meta">
                    <div class="post-author">
                        <i class="fas fa-user"></i>
                        <span>By University of Perpetual Help System Laguna</span>
                    </div>
                    <div class="post-date">
                        <i class="fas fa-calendar"></i>
                        <span><?php echo formatDate($post['published_at'] ?: $post['created_at']); ?></span>
                    </div>
                    <div class="post-views">
                        <i class="fas fa-eye"></i>
                        <span><?php echo $post['views']; ?> views</span>
                    </div>
                </div>
            </header>

            <!-- Featured Image or Image Slider -->
            <?php if (!empty($images)): ?>
                <div class="post-images">
                    <?php if (count($images) > 1): ?>
                        <!-- Image Slider -->
                        <div class="image-slider">
                            <div class="slider-container">
                                <?php foreach ($images as $index => $image): ?>
                                    <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <img src="<?php echo htmlspecialchars($image['image_path']); ?>" 
                                             alt="<?php echo htmlspecialchars($image['image_alt'] ?? $post['title']); ?>"
                                             class="slide-image">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Slider Controls -->
                            <button class="slider-btn prev-btn" onclick="changeSlide(-1)">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="slider-btn next-btn" onclick="changeSlide(1)">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            
                            <!-- Slider Indicators -->
                            <div class="slider-indicators">
                                <?php foreach ($images as $index => $image): ?>
                                    <span class="indicator <?php echo $index === 0 ? 'active' : ''; ?>" 
                                          onclick="currentSlide(<?php echo $index + 1; ?>)"></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Single Image -->
                        <div class="single-image">
                            <img src="<?php echo htmlspecialchars($images[0]['image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($images[0]['image_alt'] ?? $post['title']); ?>"
                                 class="featured-image">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Post Content -->
            <div class="post-body">
                <div class="post-text">
                    <?php 
                    // Convert content to proper HTML paragraphs
                    $content = htmlspecialchars($post['content']);
                    // Split by double line breaks to create paragraphs
                    $paragraphs = preg_split('/\n\s*\n/', $content);
                    foreach ($paragraphs as $paragraph) {
                        $paragraph = trim($paragraph);
                        if (!empty($paragraph)) {
                            echo '<p>' . $paragraph . '</p>';
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- Post Footer -->
            <footer class="post-footer">
                <div class="post-tags">
                    <i class="fas fa-tags"></i>
                    <span>University News</span>
                </div>
                <div class="post-share">
                    <span>Share:</span>
                    <a href="#" class="share-btn facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="share-btn twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="share-btn linkedin">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </div>
            </footer>
        </div>

        <!-- Sidebar -->
        <aside class="post-sidebar">
            <div class="sidebar-section">
                <h3 class="sidebar-title">Recent Posts</h3>
                <div class="recent-posts">
                    <?php foreach ($recentPosts as $recentPost): ?>
                        <?php if ($recentPost['id'] != $post['id']): ?>
                            <div class="recent-post-item">
                                <a href="post.php?slug=<?php echo $recentPost['slug']; ?>" class="recent-post-link">
                                    <h4 class="recent-post-title"><?php echo htmlspecialchars($recentPost['title']); ?></h4>
                                    <span class="recent-post-date"><?php echo formatDate($recentPost['published_at'] ?: $recentPost['created_at']); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">University Info</h3>
                <div class="university-info">
                    <p>Stay updated with the latest news and announcements from the University of Perpetual Help System Laguna.</p>
                    <a href="" class="btn btn-primary">Visit Homepage</a>
                </div>
            </div>
        </aside>
    </div>

<?php include 'app/includes/footer.php'; ?>
