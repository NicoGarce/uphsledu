<?php
/**
 * UPHSL Individual Post Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Displays individual blog posts and news articles for the UPHSL website
 */

require_once 'app/config/database.php';
require_once 'app/includes/functions.php';
// Session is automatically initialized by security.php

// Check if Post section is in maintenance
if (isSectionInMaintenance('post')) {
    $page_title = "Post - Maintenance";
    $base_path = '';
    include 'app/includes/header.php';
    $maintenance_message = getSectionMaintenanceMessage('post');
    ?>
    <main class="main-content" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 4rem 2rem;">
        <div class="maintenance-message" style="text-align: center; max-width: 600px; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <i class="fas fa-tools" style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1.5rem;"></i>
            <h1 style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;">Under Maintenance</h1>
            <p style="font-size: 1.1rem; color: #666; line-height: 1.6; margin-bottom: 2rem;"><?php echo htmlspecialchars($maintenance_message); ?></p>
            <a href="<?php echo $base_path; ?>index.php" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: var(--primary-color); color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">
                <i class="fas fa-home"></i>
                Go to Homepage
            </a>
        </div>
    </main>
    <?php include 'app/includes/footer.php'; ?>
    <?php exit; }

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

// Build current post absolute URL for social sharing
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$currentUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$encodedUrl = urlencode($currentUrl);
$encodedTitle = urlencode($post['title']);
$shareFacebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $encodedUrl;
$shareTwitter = 'https://twitter.com/intent/tweet?url=' . $encodedUrl . '&text=' . $encodedTitle;
$shareLinkedIn = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encodedUrl;

// Set additional CSS for post page
$additional_css = ['assets/css/post.css'];

// Open Graph data for social sharing (use featured image if available)
$absoluteBase = $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$featuredImagePath = $post['featured_image'] ?? '';

// Check for featured image first, then post images
if ($featuredImagePath) {
    // Normalize to absolute URL
    $ogImage = (strpos($featuredImagePath, 'http') === 0)
        ? $featuredImagePath
        : $absoluteBase . '/' . ltrim($featuredImagePath, '/');
} elseif (!empty($images) && isset($images[0]['image_path'])) {
    // Use first post image if no featured image
    $ogImage = (strpos($images[0]['image_path'], 'http') === 0)
        ? $images[0]['image_path']
        : $absoluteBase . '/' . ltrim($images[0]['image_path'], '/');
} else {
    // Default logo
    $ogImage = $absoluteBase . '/assets/images/Logos/logo.png';
}

// Ensure the image URL is properly formatted
$ogImage = str_replace('//', '/', $ogImage);
$ogImage = str_replace(':/', '://', $ogImage);

// Debug: Uncomment the line below to see what image URL is being generated
// echo "<!-- Debug: Open Graph Image URL: " . $ogImage . " -->";

// Create a better description for social sharing
$description = $post['excerpt'] ?: substr(strip_tags($post['content']), 0, 160);
if (strlen($description) > 160) {
    $description = substr($description, 0, 157) . '...';
}

$og = [
    'title' => $post['title'],
    'description' => $description,
    'url' => $currentUrl,
    'image' => $ogImage,
    'type' => 'article',
    'site_name' => 'University of Perpetual Help System Laguna',
    'article_author' => 'University of Perpetual Help System Laguna',
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
                <h1 class="post-title"><?php echo XSS::clean($post['title']); ?></h1>
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
                                        <img src="<?php echo XSS::escapeAttr($image['image_path']); ?>" 
                                             alt="<?php echo XSS::escapeAttr($image['image_alt'] ?? $post['title']); ?>"
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
                            <img src="<?php echo XSS::escapeAttr($images[0]['image_path']); ?>" 
                                 alt="<?php echo XSS::escapeAttr($images[0]['image_alt'] ?? $post['title']); ?>"
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
                    // Display content as HTML (already sanitized in database)
                    $content = $post['content'];
                    
                    // Decode HTML entities first (for apostrophes, quotes, etc.)
                    $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    
                    // Check if content has HTML tags (from TinyMCE or other rich text editor)
                    $hasHtmlTags = strip_tags($content) !== $content;
                    
                    if ($hasHtmlTags) {
                        // Content already has HTML formatting (from TinyMCE or Quill)
                        // Sanitize to allow only safe HTML tags
                        $allowedTags = '<p><br><strong><b><em><i><u><s><strike><del><ins><a><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><pre><code><span><div><img><table><thead><tbody><tr><td><th>';
                        $content = strip_tags($content, $allowedTags);
                        
                        // Remove leading/trailing whitespace and newlines from the content
                        $content = trim($content);
                        
                        // Normalize paragraph spacing - remove empty paragraphs
                        $content = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $content);
                        
                        // Remove multiple consecutive <br> tags (more than one)
                        $content = preg_replace('/(<br\s*\/?>){2,}/i', '<br>', $content);
                        
                        // Remove <br> tags at the start or end of paragraphs
                        $content = preg_replace('/<p[^>]*>(\s*<br\s*\/?>\s*)+/i', '<p>', $content);
                        $content = preg_replace('/(\s*<br\s*\/?>\s*)+<\/p>/i', '</p>', $content);
                        
                        // Remove any leading <p> tags that might have only whitespace
                        $content = preg_replace('/^<p[^>]*>(\s+)/i', '<p>', $content);
                        
                        // Normalize whitespace in paragraphs
                        $content = preg_replace('/(\s+)<\/p>/i', '</p>', $content);
                        
                        // Process hashtags in HTML content
                        $processedContent = preg_replace(
                            '/(?<!\w)#([a-zA-Z0-9_]+)/',
                            '<span class="hashtag">#$1</span>',
                            $content
                        );
                        
                        // Output the formatted HTML content
                        echo $processedContent;
                    } else {
                        // Plain text content - convert line breaks to paragraphs and URLs to links
                        // Split by double line breaks to create paragraphs
                        $paragraphs = preg_split('/\n\s*\n/', $content);
                        foreach ($paragraphs as $paragraph) {
                            $paragraph = trim($paragraph);
                            if (!empty($paragraph)) {
                                // Escape HTML to prevent XSS, but use ENT_NOQUOTES to keep apostrophes readable
                                $paragraph = htmlspecialchars($paragraph, ENT_NOQUOTES, 'UTF-8');
                                
                                // Convert URLs to clickable links (after escaping)
                                // Improved regex to handle URLs with spaces and encode them properly
                                $paragraph = preg_replace_callback(
                                    '/(https?:\/\/[^\s<>"\'\)]+(?:\s+[^\s<>"\'\)]+)*)/',
                                    function($matches) {
                                        $url = trim($matches[0]);
                                        // Remove trailing punctuation that shouldn't be part of URL
                                        $url = rtrim($url, '.,;:!?');
                                        // URL encode spaces and other special characters in the href
                                        $encodedUrl = str_replace(' ', '%20', $url);
                                        // Also encode other characters that might cause issues
                                        $encodedUrl = str_replace(['<', '>', '"', "'"], ['%3C', '%3E', '%22', '%27'], $encodedUrl);
                                        // Keep display text readable (spaces visible)
                                        $displayUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
                                        return '<a href="' . htmlspecialchars($encodedUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . $displayUrl . '</a>';
                                    },
                                    $paragraph
                                );
                                
                                // Convert hashtags to styled spans
                                $paragraph = preg_replace(
                                    '/(?<!\w)#([a-zA-Z0-9_]+)/',
                                    '<span class="hashtag">#$1</span>',
                                    $paragraph
                                );
                                
                                // Convert single line breaks to <br> tags within paragraphs
                                $paragraph = nl2br($paragraph);
                                echo '<p>' . $paragraph . '</p>';
                            }
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
                    <a href="<?php echo $shareTwitter; ?>" target="_blank" rel="noopener" class="share-btn twitter" aria-label="Share on X (Twitter)">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="<?php echo $shareLinkedIn; ?>" target="_blank" rel="noopener" class="share-btn linkedin" aria-label="Share on LinkedIn">
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

<script>
// Image Slider Functionality
let currentSlideIndex = 0;
const slides = document.querySelectorAll('.slide');
const indicators = document.querySelectorAll('.indicator');

function showSlide(index) {
    // Hide all slides
    slides.forEach(slide => slide.classList.remove('active'));
    indicators.forEach(indicator => indicator.classList.remove('active'));
    
    // Show current slide
    if (slides[index]) {
        slides[index].classList.add('active');
    }
    if (indicators[index]) {
        indicators[index].classList.add('active');
    }
}

function changeSlide(direction) {
    currentSlideIndex += direction;
    
    // Handle wrap-around
    if (currentSlideIndex >= slides.length) {
        currentSlideIndex = 0;
    } else if (currentSlideIndex < 0) {
        currentSlideIndex = slides.length - 1;
    }
    
    showSlide(currentSlideIndex);
}

function currentSlide(index) {
    currentSlideIndex = index - 1; // Convert to 0-based index
    showSlide(currentSlideIndex);
}

// Initialize slider when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initialize image slider
    if (slides.length > 0) {
        showSlide(0);
    }
    
    // Add touch/swipe support for mobile
    const sliderContainer = document.querySelector('.slider-container');
    if (sliderContainer) {
        let startX = 0;
        let endX = 0;
        
        sliderContainer.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
        });
        
        sliderContainer.addEventListener('touchend', function(e) {
            endX = e.changedTouches[0].clientX;
            const diff = startX - endX;
            
            if (Math.abs(diff) > 50) { // Minimum swipe distance
                if (diff > 0) {
                    changeSlide(1); // Swipe left - next slide
                } else {
                    changeSlide(-1); // Swipe right - previous slide
                }
            }
        });
    }
});

// Hashtag Detection and Styling
document.addEventListener('DOMContentLoaded', function() {
    const postText = document.querySelector('.post-text');
    
    if (postText) {
        // Function to process hashtags in text content
        function processHashtags(element) {
            if (element.nodeType === Node.TEXT_NODE) {
                const text = element.textContent;
                // Regex to match hashtags: # followed by alphanumeric characters and underscores
                const hashtagRegex = /#([a-zA-Z0-9_]+)/g;
                
                if (hashtagRegex.test(text)) {
                    const parent = element.parentNode;
                    const newHTML = text.replace(hashtagRegex, '<span class="hashtag">#$1</span>');
                    
                    // Create a temporary div to parse the HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = newHTML;
                    
                    // Replace the text node with the new content
                    while (tempDiv.firstChild) {
                        parent.insertBefore(tempDiv.firstChild, element);
                    }
                    parent.removeChild(element);
                }
            } else if (element.nodeType === Node.ELEMENT_NODE) {
                // Process child nodes
                const children = Array.from(element.childNodes);
                children.forEach(child => processHashtags(child));
            }
        }
        
        // Process all text content in the post
        processHashtags(postText);
    }
});
</script>

<?php include 'app/includes/footer.php'; ?>
