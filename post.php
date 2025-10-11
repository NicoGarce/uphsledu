<?php
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - University of Perpetual Help System</title>
    <meta name="description" content="<?php echo htmlspecialchars($post['excerpt'] ?? getExcerpt($post['content'], 160)); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@400;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/logos/logo.png">
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/logo.png">
    <link rel="apple-touch-icon" href="assets/images/logos/logo.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/post.css">
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
                
                <!-- Second Row: Main Menu -->
                <div class="nav-menu" id="nav-menu">
                    <div class="nav-item">
                        <a href="index.php" class="nav-link">Home</a>
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
                    
                    <?php if (isLoggedIn()): ?>
                        <div class="nav-item">
                            <a href="admin/dashboard.php" class="nav-link">Dashboard</a>
                        </div>
                        <?php if (isAuthor() || isSuperAdmin()): ?>
                            <div class="nav-item">
                                <a href="admin/create-post.php" class="nav-link">Create Post</a>
                            </div>
                        <?php endif; ?>
                        <div class="nav-item">
                            <a href="auth/logout.php" class="nav-link">Logout</a>
                        </div>
                    <?php else: ?>
                        <div class="nav-item">
                            <a href="auth/login.php" class="nav-link">Login</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="nav-toggle" id="nav-toggle">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>

    <!-- Post Content -->
    <div class="post-container">
        <div class="post-content">
            <!-- Post Header -->
            <header class="post-header">
                <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                <div class="post-meta">
                    <div class="post-author">
                        <i class="fas fa-user"></i>
                        <span>By <?php echo htmlspecialchars($post['author_name']); ?></span>
                    </div>
                    <div class="post-date">
                        <i class="fas fa-calendar"></i>
                        <span><?php echo formatDate($post['created_at']); ?></span>
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
                                        <img src="uploads/<?php echo htmlspecialchars($image['image_path']); ?>" 
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
                            <img src="uploads/<?php echo htmlspecialchars($images[0]['image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($images[0]['image_alt'] ?? $post['title']); ?>"
                                 class="featured-image">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Post Content -->
            <div class="post-body">
                <div class="post-text">
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
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
                                    <span class="recent-post-date"><?php echo formatDate($recentPost['created_at']); ?></span>
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
                    <a href="index.php" class="btn btn-primary">Visit Homepage</a>
                </div>
            </div>
        </aside>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3 class="footer-title">
                        <img src="assets/images/logo.png" alt="University of Perpetual Help System" class="footer-logo">
                    </h3>
                    <p class="footer-description">
                        Character Building is Nation Building. Excellence in education, character formation, and nation building.
                    </p>
                </div>
                <div class="footer-section">
                    <h4 class="footer-heading">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="posts.php">All Posts</a></li>
                        <li><a href="about/about.php">About</a></li>
                        <li><a href="about/contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4 class="footer-heading">Programs</h4>
                    <ul class="footer-links">
                        <li><a href="#">Basic Education</a></li>
                        <li><a href="#">Higher Education</a></li>
                        <li><a href="#">Graduate School</a></li>
                        <li><a href="#">Online Services</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4 class="footer-heading">Contact Info</h4>
                    <div class="contact-info">
                        <p><i class="fas fa-map-marker-alt"></i> Laguna, Philippines</p>
                        <p><i class="fas fa-phone"></i> +63 (XXX) XXX-XXXX</p>
                        <p><i class="fas fa-envelope"></i> info@uphsl.edu.ph</p>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> University of Perpetual Help System Laguna. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
    <script src="assets/js/post.js"></script>
</body>
</html>
