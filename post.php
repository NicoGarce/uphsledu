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
require_once 'app/includes/facebook-api.php';

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

// Check if this is a Facebook share request
if (isset($_POST['share_to_facebook']) && isLoggedIn() && isAdmin()) {
    $shareResult = sharePostToFacebook($post['id']);
    if ($shareResult['success']) {
        setFlashMessage('success', 'Post successfully shared to Facebook!');
    } else {
        setFlashMessage('error', 'Failed to share to Facebook: ' . $shareResult['error']);
    }
    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

// Get Facebook share status
$facebookShareStatus = getFacebookShareStatus($post['id']);

// Set base path for assets
$base_path = '';

// Build current post absolute URL for social sharing
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$currentUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$encodedUrl = urlencode($currentUrl);
$encodedTitle = urlencode($post['title']);
$shareFacebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $encodedUrl . '&quote=' . $encodedTitle . '&hashtag=UPHSL';
$shareTwitter = 'https://twitter.com/intent/tweet?url=' . $encodedUrl . '&text=' . $encodedTitle;
$shareLinkedIn = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encodedUrl;

// Set additional CSS for post page
$additional_css = ['assets/css/post.css'];

// Open Graph data for social sharing (use featured image if available)
$absoluteBase = $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$featuredImagePath = $post['featured_image'] ?? '';
if ($featuredImagePath) {
    // Normalize to absolute URL
    $ogImage = (strpos($featuredImagePath, 'http') === 0)
        ? $featuredImagePath
        : $absoluteBase . '/' . ltrim($featuredImagePath, '/');
} else {
    $ogImage = $absoluteBase . '/assets/images/Logos/logo.png';
}

$og = [
    'title' => $post['title'],
    'description' => substr(strip_tags($post['content']), 0, 160),
    'url' => $currentUrl,
    'image' => $ogImage,
    'type' => 'article',
    'site_name' => 'University of Perpetual Help System Laguna',
    'article_author' => 'UPHSL',
    'article_publisher' => 'University of Perpetual Help System Laguna',
    'article_published_time' => $post['published_at'] ?: $post['created_at'],
    'article_modified_time' => $post['updated_at'] ?: $post['created_at']
];

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
                                             class="slide-image"
                                             decoding="async">
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
                                 class="featured-image"
                                 decoding="async">
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
                    <a href="<?php echo $shareFacebook; ?>" target="_blank" rel="noopener" class="share-btn facebook" aria-label="Share on Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <!-- Facebook Debug Link (for troubleshooting) -->
                    <a href="https://developers.facebook.com/tools/debug/?q=<?php echo urlencode($currentUrl); ?>" target="_blank" rel="noopener" class="share-btn debug" aria-label="Debug Facebook Sharing" style="background: #666; font-size: 0.8rem; padding: 8px 12px;" title="Debug Facebook Sharing">
                        <i class="fas fa-bug"></i>
                    </a>
                    <a href="<?php echo $shareTwitter; ?>" target="_blank" rel="noopener" class="share-btn twitter" aria-label="Share on X (Twitter)">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="<?php echo $shareLinkedIn; ?>" target="_blank" rel="noopener" class="share-btn linkedin" aria-label="Share on LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    
                    <?php if (isLoggedIn() && isAdmin()): ?>
                        <!-- Admin Facebook Share Button -->
                        <div class="admin-share-section" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                            <span style="font-size: 0.9rem; color: #666;">Admin Share:</span>
                            <?php if ($facebookShareStatus): ?>
                                <span class="facebook-share-status" style="color: #28a745; font-size: 0.9rem;">
                                    <i class="fas fa-check-circle"></i> 
                                    Shared to Facebook on <?php echo formatDate($facebookShareStatus['shared_at']); ?>
                                </span>
                            <?php else: ?>
                                <form method="POST" style="display: inline-block; margin-left: 10px;">
                                    <button type="submit" name="share_to_facebook" class="share-btn facebook-admin" 
                                            style="background: #1877f2; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 0.9rem;"
                                            onclick="return confirm('Share this post to the UPHSL Facebook page?')">
                                        <i class="fab fa-facebook"></i> Share to Page
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
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
