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

// Get recent posts for homepage
$recent_posts = getRecentPosts(6);

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

    <!-- Hero Section with Image Background (video injected via JS after load) -->
    <section class="hero">
        <div class="hero-background">
            <img 
                src="assets/images/banners/UPHSL Facade.png" 
                alt="University of Perpetual Help System Laguna" 
                class="hero-image"
                data-bg-video="assets/video/AD2025.mp4"
                data-bg-poster="assets/images/banners/UPHSL Facade.png">
        </div>
        <div class="video-overlay">
            <div class="hero-layout">
                <!-- Left Column: Latest Post -->
                <div class="hero-latest-post">
                    <?php if (!empty($recent_posts)): ?>
                        <?php $latest_post = $recent_posts[0]; ?>
                        <div class="latest-post-card">
                            <div class="post-meta">
                                <span class="latest-label">Latest</span>
                                <span class="post-date">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo formatDate($latest_post['published_at'] ?: $latest_post['created_at']); ?>
                                </span>
                            </div>
                            <h2 class="latest-post-title">
                                <a href="post.php?slug=<?php echo $latest_post['slug']; ?>">
                                    <?php echo htmlspecialchars($latest_post['title']); ?>
                                </a>
                            </h2>
                            <p class="latest-post-excerpt">
                                <?php 
                                $words = explode(' ', strip_tags($latest_post['content']));
                                $excerpt = implode(' ', array_slice($words, 0, 10));
                                echo htmlspecialchars($excerpt) . '...';
                                ?>
                            </p>
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
                
                <!-- Right Column: Tagline -->
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
        
        <?php if (!empty($recent_posts)): ?>
        <div class="hero-ticker">
            <div class="hero-ticker-inner">
                <div class="hero-clock">
                    <div class="hero-time" id="heroClock">--:--</div>
                    <div class="hero-date" id="heroDate">---</div>
                </div>
                <div class="hero-ticker-track">
                    <div class="hero-ticker-content" id="heroTickerContent">
                        <?php if (!empty($recent_posts)): ?>
                            <?php foreach ($recent_posts as $index => $post): ?>
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
    <section class="news-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Latest News & Announcements</h2>
                <p class="section-description">Stay updated with the latest news and announcements from the University of Perpetual Help System Laguna</p>
            </div>
            
            <div class="news-layout">
                <div class="news-content">
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
                                                <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
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
                            <a href="posts.php" class="btn btn-primary">View All Posts</a>
                            <div class="social-media-icons">
                                <a href="https://www.youtube.com/@uphsltv1397" target="_blank" rel="noopener" class="social-icon youtube" title="Subscribe to our YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="https://www.instagram.com/uphs.laguna" target="_blank" rel="noopener" class="social-icon instagram" title="Follow us on Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="https://tiktok.com/@uphs.laguna" target="_blank" rel="noopener" class="social-icon tiktok" title="Follow us on TikTok">
                                    <i class="fab fa-tiktok"></i>
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
                    <div class="facebook-embed">
                        <div class="fb-page" 
                             data-href="https://www.facebook.com/uphsl.info.ph" 
                             data-tabs="timeline" 
                             data-height="600" 
                             data-small-header="true" 
                             data-adapt-container-width="true" 
                             data-hide-cover="false" 
                             data-show-facepile="false">
                        </div>
                    </div>
                    <div class="facebook-header">
                        <h3 class="facebook-title">
                            <i class="fab fa-facebook"></i>
                            Follow Us on Facebook
                        </h3>
                        <p class="facebook-subtitle">Stay connected with our latest updates</p>
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

