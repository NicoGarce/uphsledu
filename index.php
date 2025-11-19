<?php
/**
 * UPHSL Homepage
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Main homepage for the University of Perpetual Help System Laguna website
 */

session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Check if this is the first time setup
$pdo = getDBConnection();
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
$result = $stmt->fetch();

// If no users exist, redirect to setup page
if ($result['count'] == 0) {
    header('Location: auth/init.php');
    exit();
}

// Check if Home section is in maintenance
if (isSectionInMaintenance('home')) {
    $page_title = "Home - Maintenance";
    $base_path = '';
    include 'app/includes/header.php';
    $maintenance_message = getSectionMaintenanceMessage('home');
    ?>
    <main class="main-content" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 4rem 2rem;">
        <div class="maintenance-message" style="text-align: center; max-width: 600px; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <i class="fas fa-tools" style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1.5rem;"></i>
            <h1 style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;">Under Maintenance</h1>
            <p style="font-size: 1.1rem; color: #666; line-height: 1.6; margin-bottom: 2rem;"><?php echo htmlspecialchars($maintenance_message); ?></p>
            <a href="<?php echo $base_path; ?>index.php" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: var(--primary-color); color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">
                <i class="fas fa-refresh"></i>
                Refresh Page
            </a>
        </div>
    </main>
    <?php include 'app/includes/footer.php'; ?>
    <?php exit; }

// Get recent posts for homepage
$homepage_recent_posts = (int)getSetting('homepage_recent_posts', '6');
$recent_posts = getRecentPosts($homepage_recent_posts);

// Get hero post (selected by super admin or default to latest)
$hero_post = getHeroPost();

// Set page title
$page_title = "Home";

// Set base path for assets
$base_path = ''; // Empty for root directory

// Include header
include 'app/includes/header.php';
?>

    <style>
        /* Hero Ticker & Clock */
        .hero { position: relative; }
        .hero-ticker {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.55);
            color: #fff;
            z-index: 2;
        }
        .hero-ticker-inner {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 10px 16px;
        }
        .hero-clock {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }
        .hero-time {
            font-weight: 600;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }
        .hero-date {
            font-size: 12px;
            opacity: 0.9;
            white-space: nowrap;
        }
        .hero-ticker-track {
            overflow: hidden;
            flex: 1;
            position: relative;
            min-height: 30px;
        }
        .hero-ticker-content {
            position: relative;
            overflow: hidden;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .ticker-item {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.5s ease, transform 0.5s ease;
            opacity: 0;
            transform: translateY(20px);
            position: absolute;
            width: 100%;
            text-align: center;
            visibility: hidden;
            top: 0;
            left: 0;
        }
        .ticker-item.active {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }
        .ticker-item:hover { 
            text-decoration: underline; 
        }
        @media (max-width: 768px) {
            .hero-ticker-inner { gap: 12px; padding: 8px 12px; }
            .hero-clock { font-size: 14px; }
            .ticker-item { 
                font-size: 14px; 
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .hero-ticker-inner { gap: 8px; padding: 6px 10px; }
            .hero-clock { font-size: 12px; }
            .ticker-item { 
                font-size: 12px; 
                line-height: 1.2;
            }
        }
        
        @media (max-width: 360px) {
            .hero-ticker-inner { gap: 6px; padding: 5px 8px; }
            .hero-clock { font-size: 11px; }
            .ticker-item { 
                font-size: 11px; 
                line-height: 1.1;
            }
        }
        
        /* Interactive Education Level Buttons */
        .education-levels {
            grid-column: 1 / -1;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(28, 77, 161, 0.1);
        }
        
        .level-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .level-btn {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: left;
            width: 100%;
        }
        
        .level-btn:hover {
            background: #e3f2fd;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(28, 77, 161, 0.1);
        }
        
        .level-btn.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 5px 20px rgba(28, 77, 161, 0.3);
        }
        
        .btn-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .level-btn.active .btn-icon {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .btn-content h4 {
            margin: 0 0 5px 0;
            font-size: 1.1rem;
            font-weight: 700;
        }
        
        .btn-content p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .level-content {
            position: relative;
            min-height: 300px;
        }
        
        .content-panel {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        .content-panel.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .panel-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .panel-header h3 {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .panel-header p {
            color: #666;
            font-size: 1.1rem;
            margin: 0;
        }
        
        .programs-preview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .program-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            background: #f8f9fa;
            border-radius: 12px;
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
            text-decoration: none;
            color: inherit;
        }
        
        .program-item:hover {
            background: #e3f2fd;
            transform: translateX(5px);
            text-decoration: none;
            color: inherit;
        }
        
        .program-item i {
            color: var(--primary-color);
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }
        
        .program-item span {
            font-weight: 600;
            color: #333;
        }
        
        .view-all-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--primary-color);
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 0 auto;
            display: block;
            text-align: center;
            max-width: 300px;
        }
        
        .view-all-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(28, 77, 161, 0.3);
        }
        
        @media (max-width: 768px) {
            .level-buttons {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .level-btn {
                padding: 15px;
            }
            
            .btn-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
            
            .programs-preview {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .program-item {
                padding: 12px 15px;
            }
        }
    </style>

    <script>
        // Hero Slider Auto-Rotation
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('heroSlider');
            const slides = slider.querySelectorAll('.hero-slide');
            
            let currentSlide = 0;
            let autoSlideInterval;
            
            // Function to show specific slide
            function showSlide(index) {
                // Remove active class from all slides
                slides.forEach(slide => slide.classList.remove('active'));
                
                // Add active class to current slide
                slides[index].classList.add('active');
                
                currentSlide = index;
            }
            
            // Function to go to next slide
            function nextSlide() {
                const nextIndex = (currentSlide + 1) % slides.length;
                showSlide(nextIndex);
            }
            
            // Function to start auto-slide
            function startAutoSlide() {
                autoSlideInterval = setInterval(nextSlide, 20000); // Change slide every 20 seconds
            }
            
            // Function to stop auto-slide
            function stopAutoSlide() {
                if (autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                    autoSlideInterval = null;
                }
            }
            
            // Start auto-slide
            startAutoSlide();
            
            // Pause auto-slide on hover
            slider.addEventListener('mouseenter', stopAutoSlide);
            
            // Resume auto-slide when mouse leaves
            slider.addEventListener('mouseleave', startAutoSlide);
        });
    </script>

    <!-- Hero Section with Image Background (video injected via JS after load) -->
    <section class="hero">
        <div class="hero-background">
            <img 
                src="assets/images/FACADE.jpg" 
                alt="University of Perpetual Help System Laguna" 
                class="hero-image"
                data-bg-video="assets/video/AD2025.mp4"
                data-bg-poster="assets/images/FACADE.jpg">
        </div>
        <div class="video-overlay">
            <div class="hero-slider-container">
                <div class="hero-slider" id="heroSlider">
                    <!-- Slide 1: Latest News -->
                    <div class="hero-slide active">
                        <div class="hero-slide-content">
                            <?php if ($hero_post): ?>
                                <div class="latest-post-card">
                                    <div class="post-meta">
                                        <span class="latest-label">Latest</span>
                                        <span class="post-date">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo formatDate($hero_post['published_at'] ?: $hero_post['created_at']); ?>
                                        </span>
                                    </div>
                                    <h2 class="latest-post-title">
                                        <a href="post.php?slug=<?php echo $hero_post['slug']; ?>">
                                            <?php echo htmlspecialchars($hero_post['title']); ?>
                                        </a>
                                    </h2>
                                    <div class="hero-buttons">
                                        <a href="#news" class="btn btn-primary">View News</a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="latest-post-card">
                                    <h2 class="latest-post-title">Stay Updated</h2>
                                    <p class="latest-post-excerpt">
                                        Check back soon for the latest news and announcements from the University of Perpetual Help System Laguna.
                                    </p>
                                    <a href="#news" class="btn btn-outline">View News</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Slide 2: Tagline -->
                    <div class="hero-slide">
                        <div class="hero-slide-content">
                            <div class="hero-tagline">
                                <div class="hero-content">
                                    <div class="tagline-container">
                                        <h1 class="tagline">Character Building is Nation Building</h1>
                                    </div>
                                    <p class="hero-description">
                                        Excellence in education, character formation, and nation building. Join our community of learners and discover endless opportunities for academic and personal growth.
                                    </p>
                                    <div class="hero-buttons">
                                        <a href="#programs" class="btn btn-primary">Explore Programs</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($recent_posts) && count($recent_posts) > 1): ?>
        <div class="hero-ticker">
            <div class="hero-ticker-inner">
                <div class="hero-clock">
                    <div class="hero-time" id="heroClock">--:--</div>
                    <div class="hero-date" id="heroDate">---</div>
                </div>
                <div class="hero-ticker-track">
                    <div class="hero-ticker-content" id="heroTickerContent">
                        <?php if (!empty($recent_posts) && count($recent_posts) > 1): ?>
                            <?php foreach (array_slice($recent_posts, 1) as $index => $post): ?>
                                <a href="post.php?slug=<?php echo $post['slug']; ?>" class="ticker-item <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <!-- News Section -->
    <section class="news-section" id="news">
        <div class="container">
            <div class="news-layout">
                <div class="news-content">
                    <div class="section-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); padding: 2rem; border-radius: 12px; margin-bottom: 2rem; text-align: center;">
                        <h2 class="section-title" style="color: white; margin-bottom: 0.5rem; font-size: 1.4rem;">News & Announcements</h2>
                        <p class="section-description" style="color: rgba(255, 255, 255, 0.9); margin: 0; font-size: 0.9rem;">Stay updated with the latest news and announcements from the University of Perpetual Help System Laguna</p>
                    </div>
                    
                    <?php if (!empty($recent_posts)): ?>
                        <div class="news-carousel-container">
                            <div class="news-carousel" id="newsCarousel">
                                <?php foreach ($recent_posts as $index => $post): ?>
                                    <div class="news-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <div class="news-slide-meta">
                                            <span class="news-slide-date">
                                                <i class="fas fa-calendar"></i>
                                                <?php echo formatDate($post['published_at'] ?: $post['created_at']); ?>
                                            </span>
                                        </div>
                                        <div class="news-slide-title-overlay">
                                            <h3 class="news-slide-title">
                                                <a href="post.php?slug=<?php echo $post['slug']; ?>">
                                                    <?php echo htmlspecialchars($post['title']); ?>
                                                </a>
                                            </h3>
                                        </div>
                                        <div class="news-slide-image">
                                            <?php if ($post['featured_image']): ?>
                                                <?php 
                                                    $img = $post['featured_image'];
                                                    $imgSrc = (strpos($img, 'uploads/') === 0) ? $img : 'uploads/' . $img;
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
                            <button class="carousel-nav carousel-prev" id="newsPrev">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="carousel-nav carousel-next" id="newsNext">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            
                            <!-- Dots Indicator -->
                            <div class="carousel-dots" id="newsDots"></div>
                        </div>
                        
                        <div class="news-actions">
                            <a href="posts.php" class="btn btn-primary">
                                <i class="fas fa-newspaper"></i>
                                View All Posts
                            </a>
                            <div class="social-media-icons">
                                <a href="https://www.youtube.com/@uphsltv1397" target="_blank" rel="noopener" class="social-icon youtube" title="Subscribe to our YouTube">
                                    <i class="fab fa-youtube"></i>
                                    <span class="social-label">YouTube</span>
                                </a>
                                <a href="https://www.instagram.com/uphs.laguna" target="_blank" rel="noopener" class="social-icon instagram" title="Follow us on Instagram">
                                    <i class="fab fa-instagram"></i>
                                    <span class="social-label">Instagram</span>
                                </a>
                                <a href="https://tiktok.com/@uphs.laguna" target="_blank" rel="noopener" class="social-icon tiktok" title="Follow us on TikTok">
                                    <i class="fab fa-tiktok"></i>
                                    <span class="social-label">TikTok</span>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="empty-news">
                            <i class="fas fa-newspaper"></i>
                            <h3>No News Available</h3>
                            <p>Check back later for the latest news and announcements.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="facebook-feed">
                    <?php $facebook_url = getSetting('facebook_url', 'https://www.facebook.com/uphsl.info.ph'); ?>
                    <a href="<?php echo htmlspecialchars($facebook_url); ?>" target="_blank" rel="noopener" class="facebook-header">
                        <h3 class="facebook-title">
                            <i class="fab fa-facebook"></i>
                            Follow Us on Facebook
                        </h3>
                        <p class="facebook-subtitle">Stay connected with our latest updates</p>
                    </a>
                    <div class="facebook-embed">
                        <div class="fb-page" data-href="<?php echo htmlspecialchars($facebook_url); ?>" data-tabs="timeline" data-width="" data-height="650" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Academic Programs Section -->
    <section class="featured-posts" id="programs">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Academic Programs</h2>
                <p class="section-description">Discover our comprehensive range of academic programs designed to shape future leaders</p>
            </div>
            
            <div class="posts-grid">
                <!-- Interactive Education Level Buttons -->
                <div class="education-levels">
                    <div class="level-buttons">
                        <button class="level-btn active" data-level="academic">
                            <div class="btn-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="btn-content">
                                <h4>Academic Programs</h4>
                                <p>Undergraduate Programs</p>
                            </div>
                        </button>
                        
                        <button class="level-btn" data-level="graduate">
                            <div class="btn-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="btn-content">
                                <h4>Graduate Programs</h4>
                                <p>Master's & Doctoral</p>
                            </div>
                        </button>
                        
                        <button class="level-btn" data-level="basic">
                            <div class="btn-icon">
                                <i class="fas fa-school"></i>
                            </div>
                            <div class="btn-content">
                                <h4>Basic Education</h4>
                                <p>K-12 Programs</p>
                            </div>
                        </button>
                    </div>
                    
                    <!-- Dynamic Content Area -->
                    <div class="level-content">
                        <div class="content-panel active" id="academic-content">
                            <div class="panel-header">
                                <h3>Academic Programs</h3>
                                <p>Comprehensive undergraduate programs across multiple disciplines</p>
                            </div>
                            <div class="programs-preview">
                                <a href="programs/business-accountancy.php" class="program-item">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Business & Accountancy</span>
                                </a>
                                <a href="programs/computer-studies.php" class="program-item">
                                    <i class="fas fa-laptop-code"></i>
                                    <span>Computer Studies</span>
                                </a>
                                <a href="programs/engineering-architecture.php" class="program-item">
                                    <i class="fas fa-cogs"></i>
                                    <span>Engineering</span>
                                </a>
                                <a href="programs/education.php" class="program-item">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span>Education</span>
                                </a>
                                <a href="programs/aviation.php" class="program-item">
                                    <i class="fas fa-plane"></i>
                                    <span>Aviation</span>
                                </a>
                                <a href="programs/maritime.php" class="program-item">
                                    <i class="fas fa-ship"></i>
                                    <span>Maritime</span>
                                </a>
                            </div>
                            <a href="programs.php" class="view-all-btn">
                                View All Academic Programs <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        
                        <div class="content-panel" id="graduate-content">
                            <div class="panel-header">
                                <h3>Graduate Programs</h3>
                                <p>Master's and Doctoral programs for advanced professional development</p>
                            </div>
                            <div class="programs-preview">
                                <a href="programs/graduate-school.php" class="program-item">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Master in Business Administration</span>
                                </a>
                                <a href="programs/graduate-school.php" class="program-item">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span>Master of Arts in Education</span>
                                </a>
                                <a href="programs/graduate-school.php" class="program-item">
                                    <i class="fas fa-laptop-code"></i>
                                    <span>Master in Information Technology</span>
                                </a>
                                <a href="programs/graduate-school.php" class="program-item">
                                    <i class="fas fa-gavel"></i>
                                    <span>Doctor of Philosophy (Ph.D.)</span>
                                </a>
                                <a href="programs/graduate-school.php" class="program-item">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Doctor of Business Administration</span>
                                </a>
                                <a href="programs/graduate-school.php" class="program-item">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span>Doctor of Education</span>
                                </a>
                            </div>
                            <a href="programs/graduate-school.php" class="view-all-btn">
                                View All Graduate Programs <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        
                        <div class="content-panel" id="basic-content">
                            <div class="panel-header">
                                <h3>Basic Education</h3>
                                <p>Complete K-12 basic education from Kindergarten to Senior High School</p>
                            </div>
                            <div class="programs-preview">
                                <a href="programs/grade-school.php" class="program-item">
                                    <i class="fas fa-school"></i>
                                    <span>Grade School (K-6)</span>
                                </a>
                                <a href="programs/junior-high-school.php" class="program-item">
                                    <i class="fas fa-book"></i>
                                    <span>Junior High School (7-10)</span>
                                </a>
                                <a href="programs/senior-high-school.php" class="program-item">
                                    <i class="fas fa-user-graduate"></i>
                                    <span>Senior High School (11-12)</span>
                                </a>
                                <a href="programs/senior-high-school.php" class="program-item">
                                    <i class="fas fa-tools"></i>
                                    <span>Technical-Vocational Tracks</span>
                                </a>
                            </div>
                            <a href="programs.php" class="view-all-btn">
                                View All Basic Education Programs <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Career Opportunities Section -->
    <section class="careers-section" id="careers" style="padding: 4rem 2rem; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); position: relative;">
        <div class="container" style="max-width: 1200px; margin: 0 auto;">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-briefcase"></i> Career Opportunities
                </h2>
                <p class="section-description">Join our team and make a difference at the University of Perpetual Help System Laguna</p>
            </div>

            <?php
            // Get published career postings (limit to 6 for homepage)
            $careerPostings = getPublishedCareerPostings(6);
            ?>

            <?php if (empty($careerPostings)): ?>
                <div style="text-align: center; padding: 3rem 2rem; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <i class="fas fa-briefcase" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                    <h3 style="color: #666; margin-bottom: 0.5rem;">No Job Postings Available</h3>
                    <p style="color: #999;">Check back soon for new career opportunities.</p>
                </div>
            <?php else: ?>
                <div class="careers-carousel-wrapper" style="position: relative; margin-bottom: 2rem;">
                    <div class="careers-carousel-container" style="overflow: hidden; position: relative;">
                        <div class="careers-carousel-track <?php echo count($careerPostings) <= 3 ? 'few-cards' : ''; ?>" id="careersCarouselTrack" style="display: flex; transition: transform 0.5s ease-in-out; gap: 1.5rem;">
                            <?php foreach ($careerPostings as $posting): ?>
                                <div class="career-card" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1); transition: transform 0.3s ease, box-shadow 0.3s ease; min-width: 280px; max-width: 280px; flex-shrink: 0;">
                                    <div class="career-header" style="margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 2px solid #f0f0f0;">
                                        <h3 style="font-size: 1.1rem; color: var(--primary-color); margin-bottom: 0.5rem; line-height: 1.3;">
                                            <a href="career.php?slug=<?php echo htmlspecialchars($posting['slug']); ?>" style="color: inherit; text-decoration: none;">
                                                <?php echo htmlspecialchars($posting['position']); ?>
                                            </a>
                                        </h3>
                                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; font-size: 0.75rem; color: #666;">
                                            <span style="display: flex; align-items: center; gap: 0.375rem;">
                                                <i class="fas fa-map-marker-alt" style="color: var(--primary-color); font-size: 0.7rem;"></i>
                                                <?php echo htmlspecialchars($posting['location']); ?>
                                            </span>
                                            <span style="display: flex; align-items: center; gap: 0.375rem;">
                                                <i class="fas fa-clock" style="color: var(--primary-color); font-size: 0.7rem;"></i>
                                                <?php echo htmlspecialchars($posting['employment_type']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="career-footer" style="display: flex; justify-content: space-between; align-items: center; padding-top: 0.75rem; border-top: 1px solid #f0f0f0; margin-top: 1rem;">
                                        <span style="font-size: 0.7rem; color: #999;">
                                            <i class="fas fa-calendar" style="font-size: 0.65rem;"></i>
                                            <?php echo formatDate($posting['published_at'] ?: $posting['created_at']); ?>
                                        </span>
                                        <a href="career.php?slug=<?php echo htmlspecialchars($posting['slug']); ?>" 
                                           class="btn btn-primary" 
                                           style="padding: 0.35rem 0.9rem; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.375rem;">
                                            View
                                            <i class="fas fa-arrow-right" style="font-size: 0.7rem;"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <?php if (count($careerPostings) > 1): ?>
                        <button class="career-carousel-btn career-carousel-prev" aria-label="Previous careers" style="position: absolute; left: -20px; top: 50%; transform: translateY(-50%); background: white; border: 2px solid var(--primary-color); border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; box-shadow: 0 4px 12px rgba(0,0,0,0.15); transition: all 0.3s ease;">
                            <i class="fas fa-chevron-left" style="color: var(--primary-color); font-size: 1rem;"></i>
                        </button>
                        <button class="career-carousel-btn career-carousel-next" aria-label="Next careers" style="position: absolute; right: -20px; top: 50%; transform: translateY(-50%); background: white; border: 2px solid var(--primary-color); border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; box-shadow: 0 4px 12px rgba(0,0,0,0.15); transition: all 0.3s ease;">
                            <i class="fas fa-chevron-right" style="color: var(--primary-color); font-size: 1rem;"></i>
                        </button>
                    <?php endif; ?>
                </div>
                
                <div style="text-align: center; margin-top: 2rem;">
                    <a href="support-services/careers.php" class="btn btn-primary" style="padding: 0.75rem 2rem; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-briefcase"></i>
                        View All Career Opportunities
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <style>
        .careers-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--alt-color-1), var(--secondary-color));
        }

        .careers-section .section-header {
            margin-bottom: 3rem;
        }

        .careers-section .section-title {
            color: var(--primary-color);
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        .careers-section .section-title i {
            color: var(--alt-color-1);
            margin-right: 0.5rem;
        }

        .careers-carousel-wrapper {
            padding: 0 3rem;
        }

        .careers-carousel-container {
            padding: 1rem 0;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        
        .careers-carousel-track {
            display: flex;
            justify-content: center;
            align-items: stretch;
            width: 100%;
        }
        
        /* When there are 1-3 cards, make them responsive and centered (desktop only) */
        @media (min-width: 769px) {
            .careers-carousel-track.few-cards {
                justify-content: center !important;
                transform: translateX(0) !important;
            }
            
            /* Make cards responsive when there are fewer cards on desktop */
            .careers-carousel-track.few-cards .career-card {
                min-width: auto !important;
                max-width: 350px !important;
                flex: 0 0 auto;
            }
        }
        
        /* On mobile, always allow scrolling - don't center */
        @media (max-width: 768px) {
            .careers-carousel-track.few-cards {
                justify-content: flex-start !important;
            }
        }
        
        /* When there are 2 cards, make them slightly larger */
        .careers-carousel-track.few-cards:has(.career-card:nth-child(2):last-child) .career-card,
        .careers-carousel-wrapper:has(.career-card:nth-child(2):last-child) .career-card {
            max-width: 400px !important;
        }
        
        /* When there are 3 cards, keep them at a good size */
        .careers-carousel-track.few-cards:has(.career-card:nth-child(3):last-child) .career-card,
        .careers-carousel-wrapper:has(.career-card:nth-child(3):last-child) .career-card {
            max-width: 320px !important;
        }
        
        /* Fallback for browsers without :has() support */
        .careers-carousel-track.few-cards .career-card:nth-child(1):last-child {
            max-width: 450px !important;
        }
        
        .careers-carousel-track.few-cards .career-card:nth-child(2):last-child {
            max-width: 400px !important;
        }
        
        .careers-carousel-track.few-cards .career-card:nth-child(3):last-child {
            max-width: 320px !important;
        }

        .career-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .career-carousel-btn:hover {
            background: var(--primary-color) !important;
            transform: translateY(-50%) scale(1.1);
        }

        .career-carousel-btn:hover i {
            color: white !important;
        }

        @media (max-width: 968px) {
            .careers-carousel-wrapper {
                padding: 0 2.5rem;
            }

            .career-carousel-btn {
                width: 40px !important;
                height: 40px !important;
            }

            .career-carousel-prev {
                left: -15px !important;
            }

            .career-carousel-next {
                right: -15px !important;
            }
        }

        @media (max-width: 968px) {
            .careers-carousel-wrapper {
                padding: 0 1rem;
            }

            .career-card {
                min-width: calc(50% - 0.75rem) !important;
                max-width: calc(50% - 0.75rem) !important;
            }
        }

        @media (max-width: 768px) {
            .careers-section {
                padding: 3rem 1.5rem !important;
            }

            .careers-section .section-header {
                margin-bottom: 2rem;
            }

            .careers-section .section-title {
                font-size: 1.5rem !important;
            }

            .careers-section .section-description {
                font-size: 1rem !important;
                padding: 0 1rem;
            }

            .careers-carousel-wrapper {
                padding: 0 0.5rem;
            }

            .careers-carousel-container {
                padding: 0.5rem 0;
                justify-content: flex-start;
            }

            .career-card {
                min-width: calc(100% - 1rem) !important;
                max-width: calc(100% - 1rem) !important;
                padding: 1.25rem !important;
            }

            .career-carousel-btn {
                display: none !important;
            }

            .careers-carousel-track {
                gap: 1rem !important;
            }
        }

        @media (max-width: 480px) {
            .careers-section {
                padding: 2.5rem 1rem !important;
            }

            .careers-section .section-header {
                margin-bottom: 1.5rem;
            }

            .careers-section .section-title {
                font-size: 1.3rem !important;
            }

            .careers-section .section-title i {
                font-size: 1.1rem;
            }

            .careers-section .section-description {
                font-size: 0.9rem !important;
            }

            .careers-carousel-wrapper {
                padding: 0 0.25rem;
            }

            .career-card {
                min-width: calc(100% - 0.5rem) !important;
                max-width: calc(100% - 0.5rem) !important;
                padding: 1rem !important;
            }

            .career-card h3 {
                font-size: 1rem !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('careersCarouselTrack');
            const cards = track ? track.querySelectorAll('.career-card') : [];
            const prevBtn = document.querySelector('.career-carousel-prev');
            const nextBtn = document.querySelector('.career-carousel-next');
            
            if (!track || cards.length === 0) return;
            
            // Determine cards per view based on screen size
            function getCardsPerView() {
                if (window.innerWidth <= 768) return 1;
                if (window.innerWidth <= 968) return 2;
                return 3;
            }
            
            // Calculate card width dynamically
            function getCardWidth() {
                if (window.innerWidth <= 768) {
                    // On mobile, get actual card width from DOM
                    const firstCard = cards[0];
                    if (firstCard) {
                        return firstCard.offsetWidth;
                    }
                    // Fallback calculation
                    const wrapper = document.querySelector('.careers-carousel-wrapper');
                    if (wrapper) {
                        return wrapper.offsetWidth - 16; // 1rem = 16px
                    }
                    return window.innerWidth - 16;
                }
                
                const wrapper = document.querySelector('.careers-carousel-wrapper');
                if (!wrapper) return 280;
                
                const wrapperWidth = wrapper.offsetWidth;
                const cardsPerView = getCardsPerView();
                const gap = 24;
                const padding = window.innerWidth <= 968 ? 16 : 48;
                
                return (wrapperWidth - padding - (gap * (cardsPerView - 1))) / cardsPerView;
            }
            
            // Calculate offset to center cards
            function getCenteredOffset() {
                const wrapper = document.querySelector('.careers-carousel-wrapper');
                const container = document.querySelector('.careers-carousel-container');
                if (!wrapper || !container) return 0;
                
                const wrapperWidth = wrapper.offsetWidth;
                const containerWidth = container.offsetWidth;
                const cardsPerView = getCardsPerView();
                const gap = window.innerWidth <= 768 ? 16 : 24;
                const cardWidth = getCardWidth();
                
                // On mobile, center the single visible card
                if (window.innerWidth <= 768) {
                    const cardWithGap = cardWidth + gap;
                    const availableWidth = containerWidth;
                    return (availableWidth - cardWidth) / 2;
                }
                
                // For tablet/desktop, use padding offset
                const padding = window.innerWidth <= 968 ? 16 : 48;
                
                // If showing all cards or less, center them
                if (cards.length <= cardsPerView) {
                    const totalCardsWidth = (cards.length * cardWidth) + (gap * (cards.length - 1));
                    return (wrapperWidth - totalCardsWidth) / 2;
                }
                
                return padding / 2;
            }
            
            let currentIndex = 0;
            let cardsPerView = getCardsPerView();
            let cardWidth = getCardWidth();
            let isDragging = false;
            let startX = 0;
            let currentX = 0;
            let initialOffset = 0;
            let autoSlideInterval = null;
            
            function updateCarousel(smooth = true) {
                const gap = window.innerWidth <= 768 ? 16 : 24;
                const isMobile = window.innerWidth <= 768;
                
                // On mobile, always allow scrolling if there are multiple cards
                // On desktop, only center if all cards fit
                if (!isMobile && cards.length <= cardsPerView) {
                    track.style.transition = 'none';
                    track.style.transform = 'translateX(0)';
                    track.style.justifyContent = 'center';
                    return;
                }
                
                // Reset justify-content for carousel mode
                track.style.justifyContent = 'flex-start';
                
                // For mobile, center each card as we slide
                if (isMobile) {
                    // Recalculate card width to ensure accuracy
                    cardWidth = getCardWidth();
                    const container = document.querySelector('.careers-carousel-container');
                    if (!container) return;
                    
                    const containerWidth = container.offsetWidth;
                    const centeredOffset = (containerWidth - cardWidth) / 2;
                    const slideOffset = currentIndex * (cardWidth + gap);
                    const offset = centeredOffset - slideOffset;
                    
                    track.style.transition = smooth ? 'transform 0.5s ease-in-out' : 'none';
                    track.style.transform = `translateX(${offset}px)`;
                    return;
                }
                
                // For tablet/desktop, use normal sliding with padding
                const padding = window.innerWidth <= 968 ? 16 : 48;
                const offset = (padding / 2) - (currentIndex * (cardWidth + gap));
                track.style.transition = smooth ? 'transform 0.5s ease-in-out' : 'none';
                track.style.transform = `translateX(${offset}px)`;
            }
            
            function nextSlide() {
                cardsPerView = getCardsPerView();
                const maxIndex = Math.max(0, cards.length - cardsPerView);
                currentIndex = (currentIndex + 1) % (maxIndex + 1);
                if (maxIndex === 0) currentIndex = 0;
                updateCarousel();
            }
            
            function prevSlide() {
                cardsPerView = getCardsPerView();
                const maxIndex = Math.max(0, cards.length - cardsPerView);
                currentIndex = (currentIndex - 1 + (maxIndex + 1)) % (maxIndex + 1);
                if (maxIndex === 0) currentIndex = 0;
                updateCarousel();
            }
            
            function startAutoSlide() {
                if (cards.length <= cardsPerView) return;
                if (autoSlideInterval) clearInterval(autoSlideInterval);
                autoSlideInterval = setInterval(nextSlide, 5000);
            }
            
            function stopAutoSlide() {
                if (autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                    autoSlideInterval = null;
                }
            }
            
            // Initialize carousel
            function initCarousel() {
                cardsPerView = getCardsPerView();
                cardWidth = getCardWidth();
                currentIndex = 0;
                const isMobile = window.innerWidth <= 768;
                
                // Only enable carousel if there are more cards than visible
                // On mobile, always allow scrolling if there are 2+ cards
                if (!isMobile && cards.length <= cardsPerView) {
                    stopAutoSlide();
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';
                    // Let CSS flexbox center the cards - don't use transform
                    track.style.transition = 'none';
                    track.style.transform = 'translateX(0)';
                    track.style.justifyContent = 'center';
                    return;
                }
                
                // On mobile with 2+ cards, or desktop with 4+ cards, enable carousel
                if (isMobile && cards.length <= 1) {
                    stopAutoSlide();
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';
                    track.style.transition = 'none';
                    track.style.transform = 'translateX(0)';
                    track.style.justifyContent = 'center';
                    return;
                }
                
                // Initialize carousel mode
                updateCarousel(false);
                
                if (prevBtn) prevBtn.style.display = 'flex';
                if (nextBtn) nextBtn.style.display = 'flex';
                
                // Only auto-slide on desktop
                if (window.innerWidth > 768) {
                    startAutoSlide();
                } else {
                    stopAutoSlide();
                }
            }
            
            // Event listeners
            if (prevBtn) prevBtn.addEventListener('click', () => { stopAutoSlide(); prevSlide(); startAutoSlide(); });
            if (nextBtn) nextBtn.addEventListener('click', () => { stopAutoSlide(); nextSlide(); startAutoSlide(); });
            
            // Pause on hover (desktop only)
            const wrapper = document.querySelector('.careers-carousel-wrapper');
            if (wrapper) {
                wrapper.addEventListener('mouseenter', stopAutoSlide);
                wrapper.addEventListener('mouseleave', () => {
                    if (window.innerWidth > 768) startAutoSlide();
                });
            }
            
            // Touch/swipe support for mobile
            if (track) {
                track.addEventListener('touchstart', (e) => {
                    isDragging = true;
                    startX = e.touches[0].clientX;
                    currentX = startX;
                    
                    // Get current transform value
                    const currentTransform = track.style.transform;
                    const match = currentTransform.match(/translateX\(([^)]+)\)/);
                    
                    // Calculate initial offset based on current position
                    if (window.innerWidth <= 768) {
                        const container = document.querySelector('.careers-carousel-container');
                        const containerWidth = container ? container.offsetWidth : 0;
                        const centeredOffset = (containerWidth - cardWidth) / 2;
                        const slideOffset = currentIndex * (cardWidth + 16);
                        initialOffset = centeredOffset - slideOffset;
                    } else {
                        initialOffset = match ? parseFloat(match[1]) : 0;
                    }
                    
                    track.style.transition = 'none';
                    stopAutoSlide();
                }, { passive: true });
                
                track.addEventListener('touchmove', (e) => {
                    if (!isDragging) return;
                    currentX = e.touches[0].clientX;
                    const diff = currentX - startX;
                    const offset = initialOffset + diff;
                    track.style.transform = `translateX(${offset}px)`;
                }, { passive: true });
                
                track.addEventListener('touchend', () => {
                    if (!isDragging) return;
                    isDragging = false;
                    
                    const diff = startX - currentX;
                    const threshold = 50;
                    
                    if (Math.abs(diff) > threshold) {
                        if (diff > 0) {
                            nextSlide();
                        } else {
                            prevSlide();
                        }
                    } else {
                        updateCarousel();
                    }
                    
                    // Resume auto-slide on desktop after a delay
                    if (window.innerWidth > 768) {
                        setTimeout(startAutoSlide, 2000);
                    }
                }, { passive: true });
            }
            
            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    initCarousel();
                }, 250);
            });
            
            // Initialize on load
            initCarousel();
        });
    </script>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Why Choose University of Perpetual Help System Laguna</h2>
                <p class="section-description">Experience excellence in education with our comprehensive academic programs and student support services</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="feature-title">Academic Excellence</h3>
                    <p class="feature-description">Comprehensive programs across multiple disciplines with experienced faculty and modern facilities.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="feature-title">Character Formation</h3>
                    <p class="feature-description">Values-based education that builds character and prepares students to be responsible citizens.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Student Support</h3>
                    <p class="feature-description">Comprehensive support services including guidance, counseling, and career development programs.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3 class="feature-title">Global Perspective</h3>
                    <p class="feature-description">International programs and partnerships that provide students with global learning opportunities.</p>
                </div>
            </div>
        </div>
    </section>


<?php
// Include footer
include 'app/includes/footer.php';
?>

    <script>
        // Interactive Education Level Buttons
        document.addEventListener('DOMContentLoaded', function() {
            const levelButtons = document.querySelectorAll('.level-btn');
            const contentPanels = document.querySelectorAll('.content-panel');
            
            levelButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetLevel = this.getAttribute('data-level');
                    
                    // Remove active class from all buttons
                    levelButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Hide all content panels
                    contentPanels.forEach(panel => panel.classList.remove('active'));
                    
                    // Show target content panel
                    const targetPanel = document.getElementById(targetLevel + '-content');
                    if (targetPanel) {
                        targetPanel.classList.add('active');
                    }
                });
            });

            // News Ticker Cycling
            const tickerItems = document.querySelectorAll('.ticker-item');
            if (tickerItems.length > 0) {
                let currentIndex = 0;
                
                // Ensure first item is visible immediately
                tickerItems[0].classList.add('active');
                
                if (tickerItems.length > 1) {
                    function cycleTicker() {
                        // Remove active class from current item
                        tickerItems[currentIndex].classList.remove('active');
                        
                        // Move to next item
                        currentIndex = (currentIndex + 1) % tickerItems.length;
                        
                        // Add active class to new item
                        tickerItems[currentIndex].classList.add('active');
                    }
                    
                    // Start cycling after 3 seconds, then every 4 seconds
                    setTimeout(() => {
                        setInterval(cycleTicker, 4000);
                    }, 3000);
                }
            }
        });
    </script>

