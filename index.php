<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if this is the first time setup
$pdo = getDBConnection();
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
$result = $stmt->fetch();

// If no users exist, redirect to setup page
if ($result['count'] == 0) {
    header('Location: init.php');
    exit();
}

// Get recent posts for homepage
$recent_posts = getRecentPosts(6);

// Set page title
$page_title = "Home";

// Include header
include 'includes/header.php';
?>

    <!-- Hero Section with Image Background -->
    <section class="hero">
        <div class="hero-background">
            <img 
                src="assets/images/UPHSL Facade.png" 
                alt="University of Perpetual Help System Laguna" 
                class="hero-image">
        </div>
        <div class="video-overlay">
            <div class="hero-content">
                <div class="tagline-container">
                    <h1 class="tagline">Character Building is Nation Building</h1>
                </div>
                <p class="hero-description">
                    Excellence in education, character formation, and nation building. Join our community of learners and discover endless opportunities for academic and personal growth.
                </p>
                <div class="hero-buttons">
                    <a href="#programs" class="btn btn-primary">Explore Programs</a>
                    <a href="about.php" class="btn btn-secondary">Admissions</a>
                </div>
            </div>
        </div>
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
                                                <?php echo formatDate($post['created_at']); ?>
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
                                <a href="#" class="social-icon instagram" title="Follow us on Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-icon youtube" title="Subscribe to our YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="#" class="social-icon tiktok" title="Follow us on TikTok">
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
                             data-width="400" 
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
                        <article class="post-card">
                            <div class="post-image">
                        <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="post-content">
                                <div class="post-meta">
                                    <span class="post-date">
                                <i class="fas fa-book"></i>
                                Undergraduate Programs
                                    </span>
                                    <span class="post-author">
                                <i class="fas fa-users"></i>
                                Multiple Disciplines
                                    </span>
                                </div>
                                <h3 class="post-title">
                            <a href="#">
                                Bachelor's Degree Programs
                                    </a>
                                </h3>
                                <p class="post-excerpt">
                            Comprehensive undergraduate programs in Business, Engineering, Education, Computer Studies, and more. Build a strong foundation for your future career.
                        </p>
                    </div>
                </article>

                <article class="post-card">
                    <div class="post-image">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span class="post-date">
                                <i class="fas fa-book"></i>
                                Graduate Programs
                            </span>
                            <span class="post-author">
                                <i class="fas fa-users"></i>
                                Advanced Studies
                            </span>
                        </div>
                        <h3 class="post-title">
                            <a href="#">
                                Master's & Doctoral Programs
                            </a>
                        </h3>
                        <p class="post-excerpt">
                            Advanced degree programs designed for professionals seeking to enhance their expertise and leadership capabilities in their respective fields.
                        </p>
                            </div>
                        </article>

                <article class="post-card">
                    <div class="post-image">
                        <i class="fas fa-school"></i>
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span class="post-date">
                                <i class="fas fa-book"></i>
                                Basic Education
                            </span>
                            <span class="post-author">
                                <i class="fas fa-users"></i>
                                K-12 Programs
                            </span>
                        </div>
                        <h3 class="post-title">
                            <a href="#">
                                K-12 Education
                            </a>
                        </h3>
                        <p class="post-excerpt">
                            Complete basic education programs from Kindergarten to Senior High School, providing students with a solid foundation for higher education.
                        </p>
                    </div>
                </article>
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
include 'includes/footer.php';
?>
    

