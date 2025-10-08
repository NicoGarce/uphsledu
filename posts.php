<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Get current page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1

// Get posts for current page
$posts = getPublishedPosts($page, 10);
$totalPosts = getPublishedPostsCount();
$totalPages = ceil($totalPosts / 10);

// Get recent posts for sidebar
$recentPosts = getRecentPosts(5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts - University of Perpetual Help System</title>
    <meta name="description" content="Stay updated with the latest news and announcements from the University of Perpetual Help System Laguna.">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@400;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link rel="shortcut icon" type="image/png" href="assets/images/logo.png">
    <link rel="apple-touch-icon" href="assets/images/logo.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/posts.css">
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
                            <a href="dashboard.php" class="nav-link">Dashboard</a>
                        </div>
                        <?php if (isAuthor() || isSuperAdmin()): ?>
                            <div class="nav-item">
                                <a href="create-post.php" class="nav-link">Create Post</a>
                            </div>
                        <?php endif; ?>
                        <div class="nav-item">
                            <a href="logout.php" class="nav-link">Logout</a>
                        </div>
                    <?php else: ?>
                        <div class="nav-item">
                            <a href="login.php" class="nav-link">Login</a>
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

    <!-- Posts Content -->
    <div class="posts-container">
        <div class="posts-header">
            <h1 class="posts-title">
                <i class="fas fa-newspaper"></i>
                University News & Announcements
            </h1>
            <p class="posts-subtitle">
                Stay updated with the latest news and announcements from the University of Perpetual Help System Laguna.
            </p>
        </div>

        <div class="posts-content">
            <div class="posts-main">
                <?php if (!empty($posts)): ?>
                    <div class="posts-grid">
                        <?php foreach ($posts as $post): ?>
                            <article class="post-card">
                                <div class="post-card-image">
                                    <?php if ($post['featured_image']): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($post['featured_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($post['title']); ?>"
                                             class="card-image">
                                    <?php else: ?>
                                        <div class="card-image-placeholder">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="post-card-overlay">
                                        <a href="post.php?slug=<?php echo $post['slug']; ?>" class="read-more-btn">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="post-card-content">
                                    <div class="post-card-meta">
                                        <span class="post-date">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo formatDate($post['created_at']); ?>
                                        </span>
                                        <span class="post-author">
                                            <i class="fas fa-user"></i>
                                            <?php echo htmlspecialchars($post['author_name']); ?>
                                        </span>
                                    </div>
                                    
                                    <h2 class="post-card-title">
                                        <a href="post.php?slug=<?php echo $post['slug']; ?>">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </a>
                                    </h2>
                                    
                                    <p class="post-card-excerpt">
                                        <?php echo htmlspecialchars($post['excerpt'] ?? getExcerpt($post['content'], 120)); ?>
                                    </p>
                                    
                                    <div class="post-card-footer">
                                        <div class="post-stats">
                                            <span class="post-views">
                                                <i class="fas fa-eye"></i>
                                                <?php echo $post['views']; ?> views
                                            </span>
                                        </div>
                                        <a href="post.php?slug=<?php echo $post['slug']; ?>" class="read-more-link">
                                            Read More <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination-container">
                            <div class="pagination">
                                <?php if ($page > 1): ?>
                                    <a href="posts.php?page=<?php echo $page - 1; ?>" class="pagination-btn prev-btn">
                                        <i class="fas fa-chevron-left"></i>
                                        Previous
                                    </a>
                                <?php endif; ?>

                                <div class="pagination-numbers">
                                    <?php
                                    $startPage = max(1, $page - 2);
                                    $endPage = min($totalPages, $page + 2);
                                    
                                    if ($startPage > 1): ?>
                                        <a href="posts.php?page=1" class="pagination-number">1</a>
                                        <?php if ($startPage > 2): ?>
                                            <span class="pagination-ellipsis">...</span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                        <a href="posts.php?page=<?php echo $i; ?>" 
                                           class="pagination-number <?php echo $i === $page ? 'active' : ''; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>

                                    <?php if ($endPage < $totalPages): ?>
                                        <?php if ($endPage < $totalPages - 1): ?>
                                            <span class="pagination-ellipsis">...</span>
                                        <?php endif; ?>
                                        <a href="posts.php?page=<?php echo $totalPages; ?>" class="pagination-number">
                                            <?php echo $totalPages; ?>
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <?php if ($page < $totalPages): ?>
                                    <a href="posts.php?page=<?php echo $page + 1; ?>" class="pagination-btn next-btn">
                                        Next
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="pagination-info">
                                <p>Showing <?php echo (($page - 1) * 10) + 1; ?>-<?php echo min($page * 10, $totalPosts); ?> of <?php echo $totalPosts; ?> posts</p>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="empty-posts">
                        <div class="empty-posts-content">
                            <i class="fas fa-newspaper"></i>
                            <h3>No Posts Available</h3>
                            <p>There are no published posts at the moment. Check back later for updates.</p>
                            <a href="index.php" class="btn btn-primary">Go to Homepage</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <aside class="posts-sidebar">
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Recent Posts</h3>
                    <div class="recent-posts">
                        <?php foreach ($recentPosts as $recentPost): ?>
                            <div class="recent-post-item">
                                <a href="post.php?slug=<?php echo $recentPost['slug']; ?>" class="recent-post-link">
                                    <h4 class="recent-post-title"><?php echo htmlspecialchars($recentPost['title']); ?></h4>
                                    <span class="recent-post-date"><?php echo formatDate($recentPost['created_at']); ?></span>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="sidebar-section">
                    <h3 class="sidebar-title">University Info</h3>
                    <div class="university-info">
                        <p>Stay connected with the University of Perpetual Help System Laguna for the latest news, announcements, and updates.</p>
                        <a href="index.php" class="btn btn-primary">Visit Homepage</a>
                    </div>
                </div>

                <div class="sidebar-section">
                    <h3 class="sidebar-title">Quick Links</h3>
                    <div class="quick-links">
                        <a href="index.php" class="quick-link">
                            <i class="fas fa-home"></i>
                            Homepage
                        </a>
                        <a href="about.php" class="quick-link">
                            <i class="fas fa-info-circle"></i>
                            About Us
                        </a>
                        <a href="contact.php" class="quick-link">
                            <i class="fas fa-envelope"></i>
                            Contact
                        </a>
                        <?php if (isLoggedIn()): ?>
                            <a href="dashboard.php" class="quick-link">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>
        </div>
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
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
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
</body>
</html>
