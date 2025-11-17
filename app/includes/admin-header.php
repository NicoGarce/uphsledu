<?php
/**
 * Admin Header Include
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Common header for all admin pages with sidebar navigation
 */

// Include path configuration
require_once __DIR__ . '/../config/paths.php';

// Get current page name for active state detection
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Use the automatically detected base path
$base_path = $GLOBALS['base_path'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Nico Roell D. Garce"/>
    <meta
      name="description"
      content="University of Perpetual Help System - Laguna, UPHSL, Perpetual Laguna"
    />
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>University of Perpetual Help System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@400;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?php echo $base_path; ?>assets/images/Logos/logo.png">
    <link rel="shortcut icon" type="image/png" href="<?php echo $base_path; ?>assets/images/Logos/logo.png">
    <link rel="apple-touch-icon" href="<?php echo $base_path; ?>assets/images/Logos/logo.png">
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/dashboard.css">
    <?php if (isset($additional_css)) echo $additional_css; ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="../">
                    <img src="<?php echo $base_path; ?>assets/images/Logos/Logo2025.png" alt="University of Perpetual Help System" class="logo-img">
                </a>
            </div>
            <div class="nav-menu">
                <a href="../" class="nav-link">Home</a>
                <?php if (isHR()): ?>
                    <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">Dashboard</a>
                    <a href="careers.php" class="nav-link <?php echo ($current_page == 'careers') ? 'active' : ''; ?>">Careers Posting</a>
                <?php elseif (isAuthor()): ?>
                    <a href="author-dashboard.php" class="nav-link <?php echo ($current_page == 'author-dashboard') ? 'active' : ''; ?>">Dashboard</a>
                <?php elseif (isAdmin() || isSuperAdmin()): ?>
                    <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">Dashboard</a>
                <?php endif; ?>
                <?php if (isAuthor() || isAdmin() || isSuperAdmin()): ?>
                    <a href="posts.php" class="nav-link <?php echo ($current_page == 'posts') ? 'active' : ''; ?>">Post Management</a>
                    <a href="sdg-initiatives.php" class="nav-link <?php echo ($current_page == 'sdg-initiatives') ? 'active' : ''; ?>">SDG Initiatives</a>
                    <a href="sdg-full-report.php" class="nav-link <?php echo ($current_page == 'sdg-full-report') ? 'active' : ''; ?>">SDG Full Report</a>
                <?php endif; ?>
                <?php if (isSuperAdmin() && !isHR()): ?>
                    <a href="careers.php" class="nav-link <?php echo ($current_page == 'careers') ? 'active' : ''; ?>">Careers Posting</a>
                <?php endif; ?>
                <?php if (isSuperAdmin()): ?>
                    <a href="accounts.php" class="nav-link icon-only <?php echo ($current_page == 'accounts') ? 'active' : ''; ?>" title="Account Management">
                        <i class="fas fa-users-cog"></i>
                    </a>
                    <a href="payment-monitoring.php" class="nav-link icon-only <?php echo ($current_page == 'payment-monitoring') ? 'active' : ''; ?>" title="Payment Monitoring">
                        <i class="fas fa-credit-card"></i>
                    </a>
                    <a href="database-export.php" class="nav-link icon-only <?php echo ($current_page == 'database-export') ? 'active' : ''; ?>" title="Database Export">
                        <i class="fas fa-database"></i>
                    </a>
                    <a href="settings.php" class="nav-link icon-only <?php echo ($current_page == 'settings') ? 'active' : ''; ?>" title="Settings">
                        <i class="fas fa-cog"></i>
                    </a>
                <?php endif; ?>
            </div>
            <div class="user-menu">
                <span class="user-name"><?php echo htmlspecialchars($user['first_name']); ?></span>
                <a href="../auth/logout.php" class="nav-link icon-only" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="<?php echo $base_path; ?>assets/images/Logos/Logo2025.png" alt="UPHSL" class="sidebar-logo-img">
                <span class="sidebar-title">Admin Panel</span>
            </div>
            <button class="sidebar-close" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="sidebar-content">
            <div class="sidebar-user">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                    <div class="user-role"><?php echo ucfirst(str_replace('_', ' ', $userRole)); ?></div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <?php if (isHR()): ?>
                    <a href="dashboard.php" class="sidebar-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="../" class="sidebar-link">
                        <i class="fas fa-home"></i>
                        <span>View Website</span>
                    </a>
                    <?php elseif (isAuthor()): ?>
                    <a href="author-dashboard.php" class="sidebar-link <?php echo ($current_page == 'author-dashboard') ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <?php elseif (isAdmin() || isSuperAdmin()): ?>
                    <a href="dashboard.php" class="sidebar-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <?php endif; ?>
                    <?php if (!isHR()): ?>
                    <a href="../" class="sidebar-link">
                        <i class="fas fa-home"></i>
                        <span>View Website</span>
                    </a>
                    <?php endif; ?>
                </div>
                
                <?php if (isHR() || isSuperAdmin()): ?>
                <div class="nav-section">
                    <div class="nav-section-title">Careers</div>
                    <a href="careers.php" class="sidebar-link <?php echo ($current_page == 'careers') ? 'active' : ''; ?>">
                        <i class="fas fa-briefcase"></i>
                        <span>Careers Posting</span>
                    </a>
                </div>
                <?php endif; ?>
                
                <?php if (isAuthor() || isAdmin() || isSuperAdmin()): ?>
                <div class="nav-section">
                    <div class="nav-section-title">Post Management</div>
                    <a href="posts.php" class="sidebar-link <?php echo ($current_page == 'posts') ? 'active' : ''; ?>">
                        <i class="fas fa-edit"></i>
                        <span>Manage Posts</span>
                    </a>
                    <a href="sdg-initiatives.php" class="sidebar-link <?php echo ($current_page == 'sdg-initiatives') ? 'active' : ''; ?>">
                        <i class="fas fa-globe-americas"></i>
                        <span>SDG Initiatives</span>
                    </a>
                    <a href="sdg-full-report.php" class="sidebar-link <?php echo ($current_page == 'sdg-full-report') ? 'active' : ''; ?>">
                        <i class="fas fa-file-pdf"></i>
                        <span>SDG Full Report</span>
                    </a>
                </div>
                <?php endif; ?>
                
                <?php if (isSuperAdmin()): ?>
                <div class="nav-section">
                    <div class="nav-section-title">Account Management</div>
                    <a href="accounts.php" class="sidebar-link <?php echo ($current_page == 'accounts') ? 'active' : ''; ?>">
                        <i class="fas fa-users-cog"></i>
                        <span>Manage Accounts</span>
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">System</div>
                    <a href="settings.php" class="sidebar-link <?php echo ($current_page == 'settings') ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="database-export.php" class="sidebar-link <?php echo ($current_page == 'database-export') ? 'active' : ''; ?>">
                        <i class="fas fa-database"></i>
                        <span>Database Export</span>
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Payment System</div>
                    <a href="payment-monitoring.php" class="sidebar-link <?php echo ($current_page == 'payment-monitoring') ? 'active' : ''; ?>">
                        <i class="fas fa-credit-card"></i>
                        <span>Payment Monitoring</span>
                    </a>
                </div>
                <?php endif; ?>
                
                <div class="nav-section">
                    <div class="nav-section-title">Account</div>
                    <a href="../auth/logout.php" class="sidebar-link logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Mobile Header -->
    <div class="mobile-header">
        <div class="mobile-header-content">
            <div class="mobile-page-title">
                <h1><?php echo isset($page_title) ? $page_title : 'Admin Panel'; ?></h1>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Sidebar Toggle JavaScript -->
    <script>
        // Sidebar Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Toggle sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
                document.body.classList.toggle('sidebar-open');
            }
            
            // Close sidebar
            function closeSidebar() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            }
            
            // Event listeners
            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarClose.addEventListener('click', closeSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);
            
            // Close sidebar when clicking on sidebar links (mobile)
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeSidebar();
                }
            });
            
            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                    closeSidebar();
                }
            });
        });
    </script>
