<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Get recent posts for homepage
$recent_posts = getRecentPosts(6);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University of Perpetual Help System - Home</title>
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link rel="shortcut icon" type="image/png" href="assets/images/logo.png">
    <link rel="apple-touch-icon" href="assets/images/logo.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- First Column: Logo -->
            <div class="nav-logo">
                <a href="index.php">
                    <img src="assets/images/logo.png" alt="University of Perpetual Help System" class="logo-img">
                </a>
            </div>
            
            <!-- Second Column: Site Info and Menu -->
            <div class="nav-content">
                <!-- First Row: Site Name and Search -->
                <div class="nav-header">
                    <div class="site-name">
                        <h1>UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA</h1>
                    </div>
                    <div class="nav-search">
                        <form class="search-form">
                            <input type="text" placeholder="Search..." class="search-input">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Mobile Search Bar (separate from nav-header) -->
                <div class="nav-search mobile-search">
                    <form class="search-form">
                        <input type="text" placeholder="Search..." class="search-input">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Mobile Menu Toggle Button -->
                <button class="mobile-menu-toggle" id="mobile-menu-toggle">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
                
                <!-- Second Row: Main Menu -->
            <div class="nav-menu" id="nav-menu">
                    <div class="nav-item">
                <a href="index.php" class="nav-link active">Home</a>
                    </div>
                    
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Programs <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-item-with-submenu">
                                <a href="#" class="dropdown-link dropdown-parent">Basic Education <i class="fas fa-chevron-right submenu-chevron"></i></a>
                                <div class="submenu-dropdown">
                                    <a href="#" class="submenu-link">Senior High School</a>
                                    <a href="#" class="submenu-link">Junior High School</a>
                                    <a href="#" class="submenu-link">Grade School</a>
                                </div>
                            </div>
                            <a href="#" class="dropdown-link">Aviation</a>
                            <a href="#" class="dropdown-link">Arts & Sciences</a>
                            <a href="#" class="dropdown-link">Business & Accountancy</a>
                            <a href="#" class="dropdown-link">Computer Studies</a>
                            <a href="#" class="dropdown-link">Criminology</a>
                            <a href="#" class="dropdown-link">Education</a>
                            <a href="#" class="dropdown-link">Engineering & Architecture</a>
                            <a href="#" class="dropdown-link">International Hospitality Management</a>
                            <a href="#" class="dropdown-link">Maritime</a>
                            <a href="#" class="dropdown-link">Law/Juris Doctor</a>
                            <a href="#" class="dropdown-link">Graduate School</a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Online Services <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-link">Instructions</a>
                            <a href="#" class="dropdown-link">GTI Online Grades</a>
                            <a href="#" class="dropdown-link">Moodle</a>
                            <a href="#" class="dropdown-link">Google Account</a>
                            <a href="#" class="dropdown-link">Online Payment</a>
                            <a href="#" class="dropdown-link">Microsoft 365</a>
                            <a href="#" class="dropdown-link">Saliksik</a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Support Services <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-link">Alumni</a>
                            <a href="#" class="dropdown-link">Careers</a>
                            <a href="#" class="dropdown-link">Clinic</a>
                            <a href="#" class="dropdown-link">Community Outreach Department</a>
                            <a href="#" class="dropdown-link">International and External Affairs</a>
                            <a href="#" class="dropdown-link">Guidance and Admission</a>
                            <a href="#" class="dropdown-link">Library</a>
                            <a href="#" class="dropdown-link">Quality Assurance</a>
                            <a href="#" class="dropdown-link">Research</a>
                        </div>
                    </div>
                    
                    <div class="nav-item">
                        <a href="#" class="nav-link">Campuses</a>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">About <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-link">About Us</a>
                            <a href="#" class="dropdown-link">Contact Us</a>
                            <a href="#" class="dropdown-link">Environmental Policy</a>
                            <a href="#" class="dropdown-link">University Policy</a>
                            <a href="#" class="dropdown-link">Map</a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Online Payment <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-link">Entrance Exam</a>
                            <a href="#" class="dropdown-link">New Enrollees</a>
                            <a href="#" class="dropdown-link">Enrolled Students</a>
                        </div>
                    </div>
                    
                    <div class="nav-item">
                        <a href="#" class="nav-link">Calendar</a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="#" class="nav-link">SDG Initiatives</a>
                    </div>
                    
                <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="nav-item">
                    <a href="dashboard.php" class="nav-link">Dashboard</a>
                        </div>
                        <div class="nav-item">
                    <a href="logout.php" class="nav-link">Logout</a>
                        </div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Sidebar Overlay -->
    <div class="mobile-sidebar-overlay" id="mobile-sidebar-overlay"></div>
    
    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar" id="mobile-sidebar">
        <div class="mobile-sidebar-header">
            <div class="mobile-sidebar-logo">
                <img src="assets/images/logo.png" alt="University of Perpetual Help System" class="mobile-logo-img">
                <h2 class="mobile-site-name">UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA</h2>
            </div>
            <button class="mobile-sidebar-close" id="mobile-sidebar-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mobile-sidebar-search">
            <form class="mobile-search-form">
                <input type="text" placeholder="Search..." class="mobile-search-input">
                <button type="submit" class="mobile-search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        
        <nav class="mobile-sidebar-menu">
            <div class="mobile-nav-item">
                <a href="index.php" class="mobile-nav-link active">Home</a>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Programs <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <div class="mobile-dropdown-item-with-submenu">
                        <a href="#" class="mobile-dropdown-link mobile-dropdown-parent">Basic Education <i class="fas fa-chevron-right mobile-submenu-chevron"></i></a>
                        <div class="mobile-submenu-dropdown">
                            <a href="#" class="mobile-submenu-link">Senior High School</a>
                            <a href="#" class="mobile-submenu-link">Junior High School</a>
                            <a href="#" class="mobile-submenu-link">Grade School</a>
                        </div>
                    </div>
                    <a href="#" class="mobile-dropdown-link">Aviation</a>
                    <a href="#" class="mobile-dropdown-link">Arts & Sciences</a>
                    <a href="#" class="mobile-dropdown-link">Business & Accountancy</a>
                    <a href="#" class="mobile-dropdown-link">Computer Studies</a>
                    <a href="#" class="mobile-dropdown-link">Criminology</a>
                    <a href="#" class="mobile-dropdown-link">Education</a>
                    <a href="#" class="mobile-dropdown-link">Engineering & Architecture</a>
                    <a href="#" class="mobile-dropdown-link">International Hospitality Management</a>
                    <a href="#" class="mobile-dropdown-link">Maritime</a>
                    <a href="#" class="mobile-dropdown-link">Law/Juris Doctor</a>
                    <a href="#" class="mobile-dropdown-link">Graduate School</a>
                </div>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Online Services <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="#" class="mobile-dropdown-link">Instructions</a>
                    <a href="#" class="mobile-dropdown-link">GTI Online Grades</a>
                    <a href="#" class="mobile-dropdown-link">Moodle</a>
                    <a href="#" class="mobile-dropdown-link">Google Account</a>
                    <a href="#" class="mobile-dropdown-link">Online Payment</a>
                    <a href="#" class="mobile-dropdown-link">Microsoft 365</a>
                    <a href="#" class="mobile-dropdown-link">Saliksik</a>
                </div>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Support Services <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="#" class="mobile-dropdown-link">Alumni</a>
                    <a href="#" class="mobile-dropdown-link">Careers</a>
                    <a href="#" class="mobile-dropdown-link">Clinic</a>
                    <a href="#" class="mobile-dropdown-link">Community Outreach Department</a>
                    <a href="#" class="mobile-dropdown-link">International and External Affairs</a>
                    <a href="#" class="mobile-dropdown-link">Guidance and Admission</a>
                    <a href="#" class="mobile-dropdown-link">Library</a>
                    <a href="#" class="mobile-dropdown-link">Quality Assurance</a>
                    <a href="#" class="mobile-dropdown-link">Research</a>
                </div>
            </div>
            
            <div class="mobile-nav-item">
                <a href="#" class="mobile-nav-link">Campuses</a>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">About <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="#" class="mobile-dropdown-link">About Us</a>
                    <a href="#" class="mobile-dropdown-link">Contact Us</a>
                    <a href="#" class="mobile-dropdown-link">Environmental Policy</a>
                    <a href="#" class="mobile-dropdown-link">University Policy</a>
                    <a href="#" class="mobile-dropdown-link">Map</a>
                </div>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Online Payment <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="#" class="mobile-dropdown-link">Entrance Exam</a>
                    <a href="#" class="mobile-dropdown-link">New Enrollees</a>
                    <a href="#" class="mobile-dropdown-link">Enrolled Students</a>
                </div>
            </div>
            
            <div class="mobile-nav-item">
                <a href="#" class="mobile-nav-link">Calendar</a>
            </div>
            
            <div class="mobile-nav-item">
                <a href="#" class="mobile-nav-link">SDG Initiatives</a>
            </div>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="mobile-nav-item">
                    <a href="dashboard.php" class="mobile-nav-link">Dashboard</a>
                </div>
                <div class="mobile-nav-item">
                    <a href="logout.php" class="mobile-nav-link">Logout</a>
                </div>
            <?php endif; ?>
        </nav>
    </div>

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


    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- University Info Column -->
                <div class="footer-section university-info">
                    <div class="footer-logo-section">
                        <img src="assets/images/logo.png" alt="University of Perpetual Help System" class="footer-logo">
                        <div class="university-details">
                            <h3 class="university-name">University of Perpetual Help System Laguna</h3>
                            <p class="university-tagline">Character Building is Nation Building</p>
                        </div>
                    </div>
                    <div class="social-links">
                        <a href="#" class="social-link facebook" title="Follow us on Facebook">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="social-link twitter" title="Follow us on Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link instagram" title="Follow us on Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link youtube" title="Subscribe to our YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="social-link linkedin" title="Connect with us on LinkedIn">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Online Services Column -->
                <div class="footer-section">
                    <h4 class="footer-subtitle">Online Services</h4>
                    <ul class="footer-links">
                        <li><a href="#" class="service-link">
                            <i class="fas fa-graduation-cap"></i>
                            School Automate (GTI)
                        </a></li>
                        <li><a href="#" class="service-link">
                            <i class="fas fa-book"></i>
                            Moodle
                        </a></li>
                        <li><a href="#" class="service-link">
                            <i class="fab fa-google"></i>
                            Google Workspace
                        </a></li>
                        <li><a href="#" class="service-link">
                            <i class="fab fa-microsoft"></i>
                            Microsoft 365
                        </a></li>
                        <li><a href="#" class="service-link">
                            <i class="fas fa-credit-card"></i>
                            Online Payment
                        </a></li>
                        <li><a href="#" class="service-link">
                            <i class="fas fa-search"></i>
                            Saliksik
                        </a></li>
                    </ul>
                </div>
                
                <!-- Quick Links Column -->
                <div class="footer-section">
                    <h4 class="footer-subtitle">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="campuses.php">Campuses</a></li>
                        <li><a href="admissions.php">Admissions</a></li>
                        <li><a href="academics.php">Academics</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p class="copyright">&copy; <?php echo date('Y'); ?> University of Perpetual Help System Laguna. All rights reserved.</p>
                    <div class="footer-bottom-links">
                        <a href="privacy-policy.php" class="footer-bottom-link">Privacy Policy</a>
                        <a href="terms-of-service.php" class="footer-bottom-link">Terms of Service</a>
                        <a href="accessibility.php" class="footer-bottom-link">Accessibility</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
    
    <!-- News Carousel JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('newsCarousel');
            const prevBtn = document.getElementById('newsPrev');
            const nextBtn = document.getElementById('newsNext');
            const dotsContainer = document.getElementById('newsDots');
            
            if (!carousel) return;
            
            const slides = carousel.querySelectorAll('.news-slide');
            const totalSlides = slides.length;
            let currentSlide = 0;
            
            // Create dots
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('button');
                dot.className = 'carousel-dot';
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }
            
            function updateCarousel() {
                // Hide all slides
                slides.forEach(slide => slide.classList.remove('active'));
                
                // Show current slide
                slides[currentSlide].classList.add('active');
                
                // Update dots
                const dots = dotsContainer.querySelectorAll('.carousel-dot');
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentSlide);
                });
            }
            
            function goToSlide(slideIndex) {
                currentSlide = slideIndex;
                updateCarousel();
            }
            
            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateCarousel();
            }
            
            function prevSlide() {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateCarousel();
            }
            
            // Event listeners
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);
            
            // Auto-play with faster interval for auto-scroll effect
            let autoPlayInterval = setInterval(nextSlide, 4000);
            
            // Pause on hover
            carousel.addEventListener('mouseenter', () => clearInterval(autoPlayInterval));
            carousel.addEventListener('mouseleave', () => {
                autoPlayInterval = setInterval(nextSlide, 4000);
            });
            
            // Touch/swipe support
            let startX = 0;
            let endX = 0;
            
            carousel.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
            });
            
            carousel.addEventListener('touchend', (e) => {
                endX = e.changedTouches[0].clientX;
                const diff = startX - endX;
                
                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                }
            });
            
            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                }
            });
        });
    </script>
    
    <!-- Facebook SDK -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0" nonce=""></script>
</body>
</html>

