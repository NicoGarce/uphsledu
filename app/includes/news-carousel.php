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
 * @param string $facebookLink - Optional Facebook page URL to display beside the news slider
 */

// Ensure base_path is set (default to '../' if not set)
if (!isset($base_path)) {
    $base_path = '../';
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
$news_carousel_posts = (int)getSetting('news_carousel_posts', '5');
$category_posts = $categoryId ? getRecentPostsByCategory($categoryId, $news_carousel_posts) : [];

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
            
            <div class="news-layout" style="display: grid; grid-template-columns: <?php echo isset($facebookLink) && !empty($facebookLink) ? '2fr 1fr' : '1fr'; ?>; gap: 2rem; align-items: start;">
                <div class="news-content">
                    <div class="news-carousel-container">
                        <div class="news-carousel" id="newsCarousel-<?php echo $categoryId; ?>">
                            <?php foreach ($category_posts as $index => $post): ?>
                                <div class="news-slide <?php echo $index === 0 ? 'active' : ''; ?>">
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
                    
                    <div class="news-actions" style="display: flex; justify-content: center; align-items: center; gap: 15px; flex-wrap: wrap; margin-top: 30px;">
                        <a href="<?php echo $base_path; ?>posts.php?category=<?php echo urlencode($categoryId); ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: var(--primary-color, #1c4da1); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; font-size: 0.9rem;">
                            <i class="fas fa-newspaper"></i>
                            View All <?php echo htmlspecialchars($categoryName); ?> Posts
                        </a>
                    </div>
                </div>
                
                <?php if (isset($facebookLink) && !empty($facebookLink)): ?>
                    <div class="facebook-feed" style="position: sticky; top: 100px;">
                        <a href="<?php echo htmlspecialchars($facebookLink); ?>" target="_blank" rel="noopener" class="facebook-header" style="display: block; background: linear-gradient(135deg, #1877f2 0%, #0d5dbf 100%); padding: 1.5rem; border-radius: 12px; margin-bottom: 1rem; text-align: center; text-decoration: none; color: white; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <h3 class="facebook-title" style="margin: 0 0 0.5rem 0; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                <i class="fab fa-facebook"></i>
                                Follow Us on Facebook
                            </h3>
                            <p class="facebook-subtitle" style="margin: 0; font-size: 0.85rem; opacity: 0.9;">Stay connected with our latest updates</p>
                        </a>
                        <div class="facebook-embed" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            <div class="fb-page" data-href="<?php echo htmlspecialchars($facebookLink); ?>" data-tabs="timeline" data-width="" data-height="650" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <style>
    /* Mobile Responsive Styles for News Carousel on Program/Support Service Pages */
    @media (max-width: 1024px) {
        .news-layout {
            grid-template-columns: 1fr 1fr !important;
            gap: 30px !important;
        }
        
        .facebook-feed {
            position: static !important;
        }
    }
    
    @media (max-width: 768px) {
        .news-section {
            padding: 30px 0 !important;
        }
        
        .news-section .section-header {
            padding: 1.2rem !important;
            margin-bottom: 1.5rem !important;
        }
        
        .news-section .section-title {
            font-size: 1.3rem !important;
            margin-bottom: 1.5rem !important;
            padding-bottom: 0.5rem !important;
        }
        
        .news-section .section-title::after {
            bottom: -8px !important;
        }
        
        .news-section .section-description {
            font-size: 0.85rem !important;
            line-height: 1.4 !important;
            margin-top: 0.5rem !important;
        }
        
        .news-layout {
            grid-template-columns: 1fr !important;
            gap: 20px !important;
        }
        
        .news-content {
            order: 1 !important;
        }
        
        .facebook-feed {
            order: 3 !important;
            position: static !important;
            margin-top: 1.5rem !important;
        }
        
        .news-actions {
            order: 2 !important;
            margin-top: 20px !important;
            padding: 15px !important;
            flex-direction: column !important;
            gap: 10px !important;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
        }
        
        .news-actions::before {
            display: none !important;
        }
        
        .news-actions .btn {
            font-size: 0.85rem !important;
            padding: 0.75rem 1.5rem !important;
            width: calc(100% - 20px) !important;
            max-width: calc(100% - 20px) !important;
            margin: 0 auto !important;
            justify-content: center !important;
            white-space: normal !important;
            word-wrap: break-word !important;
            text-align: center !important;
            border-radius: 8px !important;
        }
        
        .news-carousel-container {
            margin: 0 10px;
            border-radius: 12px;
        }
        
        .news-carousel {
            height: 0 !important;
            padding-bottom: 56.25% !important; /* 16:9 aspect ratio */
            min-height: 200px !important;
        }
        
        .news-slide {
            min-height: auto !important;
        }
        
        .news-slide-meta {
            bottom: 8px !important;
            left: 8px !important;
            font-size: 0.75rem !important;
            padding: 5px 10px !important;
        }
        
        .news-slide-title-overlay {
            bottom: 8px !important;
            right: 8px !important;
            padding: 8px 10px !important;
            max-width: 55% !important;
        }
        
        .news-slide-title {
            font-size: 0.85rem !important;
            line-height: 1.3 !important;
        }
        
        .news-slide-title a {
            font-size: 0.85rem !important;
        }
        
        .carousel-nav {
            width: 30px !important;
            height: 30px !important;
            font-size: 0.7rem !important;
        }
        
        .carousel-prev {
            left: 8px !important;
        }
        
        .carousel-next {
            right: 8px !important;
        }
        
        .carousel-dots {
            bottom: 8px !important;
            gap: 6px !important;
        }
        
        .carousel-dot {
            width: 6px !important;
            height: 6px !important;
        }
        
        .facebook-header {
            padding: 1.2rem !important;
        }
        
        .facebook-title {
            font-size: 1rem !important;
        }
        
        .facebook-subtitle {
            font-size: 0.8rem !important;
        }
    }
    
    @media (max-width: 480px) {
        .news-section {
            padding: 20px 0 !important;
        }
        
        .news-section .section-header {
            padding: 1rem !important;
            margin-bottom: 1.2rem !important;
        }
        
        .news-section .section-title {
            font-size: 1.1rem !important;
            margin-bottom: 1.2rem !important;
            padding-bottom: 0.4rem !important;
        }
        
        .news-section .section-title::after {
            bottom: -6px !important;
        }
        
        .news-section .section-description {
            font-size: 0.75rem !important;
            margin-top: 0.4rem !important;
        }
        
        .news-layout {
            gap: 15px !important;
        }
        
        .news-actions {
            margin-top: 15px !important;
            padding: 12px !important;
            gap: 8px !important;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
        }
        
        .news-actions::before {
            display: none !important;
        }
        
        .news-actions .btn {
            font-size: 0.8rem !important;
            padding: 0.65rem 1.2rem !important;
            width: calc(100% - 16px) !important;
            max-width: calc(100% - 16px) !important;
            margin: 0 auto !important;
            line-height: 1.4 !important;
            border-radius: 8px !important;
        }
        
        .news-carousel-container {
            margin: 0 8px;
            border-radius: 10px;
        }
        
        .news-carousel {
            padding-bottom: 58% !important;
            min-height: 180px !important;
        }
        
        .news-slide-meta {
            bottom: 6px !important;
            left: 6px !important;
            font-size: 0.7rem !important;
            padding: 4px 8px !important;
        }
        
        .news-slide-title-overlay {
            bottom: 6px !important;
            right: 6px !important;
            padding: 6px 8px !important;
            max-width: 60% !important;
        }
        
        .news-slide-title {
            font-size: 0.75rem !important;
            line-height: 1.2 !important;
        }
        
        .news-slide-title a {
            font-size: 0.75rem !important;
        }
        
        .carousel-nav {
            width: 26px !important;
            height: 26px !important;
            font-size: 0.6rem !important;
        }
        
        .carousel-prev {
            left: 6px !important;
        }
        
        .carousel-next {
            right: 6px !important;
        }
        
        .carousel-dots {
            bottom: 6px !important;
            gap: 5px !important;
        }
        
        .carousel-dot {
            width: 5px !important;
            height: 5px !important;
        }
        
        .facebook-header {
            padding: 1rem !important;
        }
        
        .facebook-title {
            font-size: 0.9rem !important;
        }
        
        .facebook-subtitle {
            font-size: 0.75rem !important;
        }
    }
    
    @media (max-width: 360px) {
        .news-section {
            padding: 15px 0 !important;
        }
        
        .news-section .section-header {
            padding: 0.9rem !important;
            margin-bottom: 1rem !important;
        }
        
        .news-section .section-title {
            font-size: 1rem !important;
            margin-bottom: 1rem !important;
            padding-bottom: 0.3rem !important;
        }
        
        .news-section .section-title::after {
            bottom: -5px !important;
        }
        
        .news-section .section-description {
            font-size: 0.7rem !important;
            line-height: 1.3 !important;
            margin-top: 0.3rem !important;
        }
        
        .news-layout {
            gap: 12px !important;
        }
        
        .news-actions {
            margin-top: 12px !important;
            padding: 10px !important;
            gap: 6px !important;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
        }
        
        .news-actions::before {
            display: none !important;
        }
        
        .news-actions .btn {
            font-size: 0.75rem !important;
            padding: 0.6rem 1rem !important;
            width: calc(100% - 12px) !important;
            max-width: calc(100% - 12px) !important;
            margin: 0 auto !important;
            line-height: 1.3 !important;
            min-height: 44px !important;
            border-radius: 8px !important;
        }
        
        .news-carousel-container {
            margin: 0 5px;
            border-radius: 8px;
        }
        
        .news-carousel {
            padding-bottom: 60% !important;
            min-height: 160px !important;
        }
        
        .news-slide-meta {
            bottom: 5px !important;
            left: 5px !important;
            font-size: 0.65rem !important;
            padding: 3px 6px !important;
        }
        
        .news-slide-title-overlay {
            bottom: 5px !important;
            right: 5px !important;
            padding: 5px 6px !important;
            max-width: 65% !important;
        }
        
        .news-slide-title {
            font-size: 0.7rem !important;
            line-height: 1.1 !important;
        }
        
        .news-slide-title a {
            font-size: 0.7rem !important;
        }
        
        .carousel-nav {
            width: 24px !important;
            height: 24px !important;
            font-size: 0.55rem !important;
        }
        
        .carousel-prev {
            left: 5px !important;
        }
        
        .carousel-next {
            right: 5px !important;
        }
        
        .carousel-dots {
            bottom: 5px !important;
            gap: 4px !important;
        }
        
        .carousel-dot {
            width: 4px !important;
            height: 4px !important;
        }
        
        .facebook-header {
            padding: 0.9rem !important;
        }
        
        .facebook-title {
            font-size: 0.85rem !important;
        }
        
        .facebook-subtitle {
            font-size: 0.7rem !important;
        }
    }
    </style>
    
    <script>
    // Initialize news carousel for this page
    document.addEventListener('DOMContentLoaded', function() {
        const carouselId = 'newsCarousel-<?php echo $categoryId; ?>';
        if (typeof initializeNewsCarousel === 'function') {
            initializeNewsCarousel(carouselId);
        } else {
            // Fallback initialization if function doesn't exist
            const carousel = document.getElementById(carouselId);
            if (!carousel) return;
            
            const slides = carousel.querySelectorAll('.news-slide');
            const prevBtn = document.getElementById('newsPrev-<?php echo $categoryId; ?>');
            const nextBtn = document.getElementById('newsNext-<?php echo $categoryId; ?>');
            const dotsContainer = document.getElementById('newsDots-<?php echo $categoryId; ?>');
            
            let currentSlide = 0;
            
            // Create dots only if they don't already exist
            if (dotsContainer && slides.length > 1) {
                const existingDots = dotsContainer.querySelectorAll('.carousel-dot');
                if (existingDots.length === 0) {
                    slides.forEach((_, index) => {
                        const dot = document.createElement('button');
                        dot.className = 'carousel-dot';
                        if (index === 0) dot.classList.add('active');
                        dot.addEventListener('click', () => goToSlide(index));
                        dotsContainer.appendChild(dot);
                    });
                }
            }
            
            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === index);
                });
                
                if (dotsContainer) {
                    const dots = dotsContainer.querySelectorAll('.carousel-dot');
                    dots.forEach((dot, i) => {
                        dot.classList.toggle('active', i === index);
                    });
                }
            }
            
            function goToSlide(index) {
                currentSlide = index;
                showSlide(currentSlide);
            }
            
            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }
            
            function prevSlide() {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            }
            
            if (prevBtn) prevBtn.addEventListener('click', prevSlide);
            if (nextBtn) nextBtn.addEventListener('click', nextSlide);
            
            // Auto-rotate slides
            if (slides.length > 1) {
                setInterval(nextSlide, 5000);
            }
        }
    });
    </script>
<?php endif; ?>


