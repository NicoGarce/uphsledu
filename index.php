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
            
            <!-- Mobile Search Bar -->
            <div class="nav-search-mobile">
                <form class="search-form" action="posts.php" method="GET">
                    <input type="text" name="search" class="search-input" placeholder="Search posts..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            
            <!-- Mobile Menu Toggle -->
            <div class="nav-toggle" id="nav-toggle">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
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
                
                <!-- Second Row: Main Menu -->
                <div class="nav-menu" id="nav-menu">
                    <div class="nav-item">
                        <a href="index.php" class="nav-link active">Home</a>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Programs</a>
                        <div class="dropdown-menu">
                            <div class="dropdown-section">
                                <h4>Basic Education</h4>
                                <a href="#" class="dropdown-link">Senior High School</a>
                                <a href="#" class="dropdown-link">Junior High School</a>
                                <a href="#" class="dropdown-link">Grade School</a>
                            </div>
                            <div class="dropdown-section">
                                <h4>Higher Education</h4>
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
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Online Services</a>
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
                        <a href="#" class="nav-link dropdown-toggle">Support Services</a>
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
                        <a href="#" class="nav-link dropdown-toggle">About</a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-link">About Us</a>
                            <a href="#" class="dropdown-link">Contact Us</a>
                            <a href="#" class="dropdown-link">Environmental Policy</a>
                            <a href="#" class="dropdown-link">University Policy</a>
                            <a href="#" class="dropdown-link">Map</a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Online Payment</a>
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

    <!-- Hero Section with Video Background -->
    <section class="hero">
        <div class="video-container">
            <div class="video-wrapper">
                <iframe 
                    id="youtube-video"
                    src="https://www.youtube.com/embed/kCIdWgbfuoE?autoplay=1&mute=1&loop=1&playlist=kCIdWgbfuoE&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3&fs=0&disablekb=1&cc_load_policy=0&start=0&end=0&enablejsapi=0&wmode=opaque&origin=*&playsinline=1&html5=1" 
                    frameborder="0" 
                    allow="autoplay; encrypted-media; fullscreen" 
                    allowfullscreen
                    style="pointer-events: none; border: none; outline: none;"
                    loading="eager">
                </iframe>
            </div>
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
                                        <div class="news-slide-content">
                                            <div class="news-slide-meta">
                                                <span class="news-slide-date">
                                                    <i class="fas fa-calendar"></i>
                                                    <?php echo formatDate($post['created_at']); ?>
                                                </span>
                                                <span class="news-slide-author">
                                                    <i class="fas fa-user"></i>
                                                    <?php echo htmlspecialchars($post['author_name']); ?>
                                                </span>
                                            </div>
                                            <h3 class="news-slide-title">
                                                <a href="post.php?slug=<?php echo $post['slug']; ?>">
                                                    <?php echo htmlspecialchars($post['title']); ?>
                                                </a>
                                            </h3>
                                            <p class="news-slide-excerpt">
                                                <?php echo htmlspecialchars($post['excerpt'] ?? getExcerpt($post['content'], 200)); ?>
                                            </p>
                                            <div class="news-slide-footer">
                                                <a href="post.php?slug=<?php echo $post['slug']; ?>" class="read-more-btn">
                                                    Read More <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </div>
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
                    <div class="facebook-header">
                        <h3 class="facebook-title">
                            <i class="fab fa-facebook"></i>
                            Follow Us on Facebook
                        </h3>
                        <p class="facebook-subtitle">Stay connected with our latest updates</p>
                    </div>
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
                <div class="footer-section">
                    <h3 class="footer-title">
                        <img src="assets/images/logo.png" alt="University of Perpetual Help System" class="footer-logo">
                    </h3>
                    <p class="footer-description">
                        University of Perpetual Help System - Character Building is Nation Building
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-subtitle">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="login.php">Login</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-subtitle">Categories</h4>
                    <ul class="footer-links">
                        <li><a href="#">Technology</a></li>
                        <li><a href="#">Lifestyle</a></li>
                        <li><a href="#">Business</a></li>
                        <li><a href="#">Travel</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-subtitle">Contact Info</h4>
                    <div class="contact-info">
                        <p><i class="fas fa-envelope"></i> info@myblog.com</p>
                        <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                        <p><i class="fas fa-map-marker-alt"></i> New York, NY</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> University of Perpetual Help System. All rights reserved.</p>
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

