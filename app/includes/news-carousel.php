<?php
/**
 * News Carousel Component
 * 
 * Reusable news carousel for program and support service pages
 * 
 * @param int|string $categoryId - The category ID (or name for backward compatibility) to filter posts
 * @param string $base_path - Base path for assets (e.g., '../' for subdirectories)
 * @param string $sectionTitle - Title for the carousel section
 * @param string $sectionDescription - Description for the carousel section
 * @param string $viewAllLink - Link to view all posts for this category
 * @param bool $isSupportService - Set to true for support services (uses horizontal layout), false for programs (uses overlay layout)
 */

// Ensure base_path is set (default to '../' if not set)
if (!isset($base_path)) {
    $base_path = '../';
}

// Default to false (program layout) if not set
if (!isset($isSupportService)) {
    $isSupportService = false;
}

// Get category ID if name is provided
if (isset($categoryId) && !is_numeric($categoryId)) {
    $category = getCategoryByName($categoryId);
    $categoryId = $category ? $category['id'] : null;
} elseif (isset($categoryId) && is_numeric($categoryId)) {
    $categoryId = (int)$categoryId;
} else {
    $categoryId = null;
}

// Get recent posts for this category
$category_posts = $categoryId ? getRecentPostsByCategory($categoryId, 5) : [];

// Get category name for display
$categoryName = '';
if ($categoryId) {
    $cat = getCategoryById($categoryId);
    $categoryName = $cat ? $cat['name'] : '';
}
?>

<?php if (!empty($category_posts)): ?>
    <section class="news-section" style="padding: 60px 0; background: #f8f9fa;">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-newspaper"></i>
                    <?php echo htmlspecialchars($sectionTitle); ?>
                </h2>
                <p class="section-description">
                    <?php echo htmlspecialchars($sectionDescription); ?>
                </p>
            </div>
            
            <div class="news-carousel-container <?php echo $isSupportService ? 'support-service-layout' : 'program-layout'; ?>">
                <div class="news-carousel" id="newsCarousel-<?php echo $categoryId; ?>">
                    <?php foreach ($category_posts as $index => $post): ?>
                        <div class="news-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                            <?php if ($isSupportService): ?>
                                <!-- Support Service Layout: Image left (2/3), Text right (1/3) -->
                                <div class="news-slide-content">
                                    <div class="news-slide-image">
                                        <?php if ($post['featured_image']): ?>
                                            <?php 
                                                $img = $post['featured_image'];
                                                // Handle both absolute paths and relative paths
                                                if (strpos($img, 'uploads/') === 0 || strpos($img, '../uploads/') === 0) {
                                                    $imgSrc = $base_path . $img;
                                                } elseif (strpos($img, '/') === 0) {
                                                    $imgSrc = $img;
                                                } else {
                                                    $imgSrc = $base_path . 'uploads/' . $img;
                                                }
                                            ?>
                                            <img src="<?php echo htmlspecialchars($imgSrc); ?>" 
                                                 alt="<?php echo htmlspecialchars($post['title']); ?>"
                                                 decoding="async">
                                        <?php else: ?>
                                            <div class="news-slide-placeholder">
                                                <i class="fas fa-newspaper"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="news-slide-text">
                                        <h3 class="news-slide-title">
                                            <a href="<?php echo $base_path; ?>post.php?slug=<?php echo $post['slug']; ?>">
                                                <?php echo htmlspecialchars($post['title']); ?>
                                            </a>
                                        </h3>
                                        <div class="news-slide-meta">
                                            <span class="news-slide-date">
                                                <i class="fas fa-calendar"></i>
                                                <?php echo formatDate($post['published_at'] ?: $post['created_at']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Program Layout: Full image with overlay (original design) -->
                                <div class="news-slide-meta">
                                    <span class="news-slide-date">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo formatDate($post['published_at'] ?: $post['created_at']); ?>
                                    </span>
                                </div>
                                <div class="news-slide-title-overlay">
                                    <h3 class="news-slide-title">
                                        <a href="<?php echo $base_path; ?>post.php?slug=<?php echo $post['slug']; ?>">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </a>
                                    </h3>
                                </div>
                                <div class="news-slide-image">
                                    <?php if ($post['featured_image']): ?>
                                        <?php 
                                            $img = $post['featured_image'];
                                            // Handle both absolute paths and relative paths
                                            if (strpos($img, 'uploads/') === 0 || strpos($img, '../uploads/') === 0) {
                                                $imgSrc = $base_path . $img;
                                            } elseif (strpos($img, '/') === 0) {
                                                $imgSrc = $img;
                                            } else {
                                                $imgSrc = $base_path . 'uploads/' . $img;
                                            }
                                        ?>
                                        <img src="<?php echo htmlspecialchars($imgSrc); ?>" 
                                             alt="<?php echo htmlspecialchars($post['title']); ?>"
                                             decoding="async">
                                    <?php else: ?>
                                        <div class="news-slide-placeholder">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Navigation Arrows -->
                <button class="carousel-nav carousel-prev" id="newsPrev-<?php echo $categoryId; ?>">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-nav carousel-next" id="newsNext-<?php echo $categoryId; ?>">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Dots Indicator -->
                <div class="carousel-dots" id="newsDots-<?php echo $categoryId; ?>"></div>
            </div>
            
            <div class="news-actions" style="text-align: center; margin-top: 30px;">
                <a href="<?php echo $base_path; ?>posts.php?category=<?php echo urlencode($categoryId); ?>" class="btn btn-primary">
                    <i class="fas fa-newspaper"></i>
                    View All <?php echo htmlspecialchars($categoryName); ?> Posts
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

