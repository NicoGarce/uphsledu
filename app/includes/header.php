<?php
/**
 * UPHSL Website Header
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Common header template for all pages on the UPHSL website
 */

// Include path configuration
require_once __DIR__ . '/../config/paths.php';

// Get current page for active navigation highlighting
// Handle both local and production environments
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Get the request URI for more reliable detection
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];

// Remove query string from REQUEST_URI
$request_uri = strtok($request_uri, '?');

// Special handling for index.php files in subdirectories
// Check both SCRIPT_NAME and REQUEST_URI for better production compatibility
if ($current_page === 'index' && (strpos($script_name, '/') !== false || strpos($request_uri, '/') !== false)) {
    // Try SCRIPT_NAME first
    if (strpos($script_name, '/') !== false) {
        $path_parts = explode('/', trim($script_name, '/'));
        array_pop($path_parts); // Remove index.php
        if (!empty($path_parts)) {
            $current_page = end($path_parts);
        }
    }
    
    // If still 'index', try REQUEST_URI
    if ($current_page === 'index' && strpos($request_uri, '/') !== false) {
        $path_parts = explode('/', trim($request_uri, '/'));
        $last_part = end($path_parts);
        
        // If the last part is not empty and not a file extension, use it
        if (!empty($last_part) && !strpos($last_part, '.')) {
            $current_page = $last_part;
        }
    }
}


// Use the automatically detected base path
$base_path = $GLOBALS['base_path'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo (isset($page_title) && $page_title !== 'Home') ? $page_title . ' - ' : ''; ?>University of Perpetual Help System Laguna</title>
    <!-- Performance: speed up icon/font loading to prevent flash of text -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <?php if (!empty($og) && is_array($og)): ?>
        <meta property="og:title" content="<?php echo htmlspecialchars($og['title'] ?? ''); ?>">
        <meta property="og:description" content="<?php echo htmlspecialchars($og['description'] ?? ''); ?>">
        <meta property="og:url" content="<?php echo htmlspecialchars($og['url'] ?? ''); ?>">
        <?php if (!empty($og['image'])): ?>
            <meta property="og:image" content="<?php echo htmlspecialchars($og['image']); ?>">
        <?php endif; ?>
        <meta property="og:type" content="<?php echo htmlspecialchars($og['type'] ?? 'website'); ?>">
        <meta property="og:site_name" content="<?php echo htmlspecialchars($og['site_name'] ?? 'University of Perpetual Help System Laguna'); ?>">
        <!-- Optional Twitter card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo htmlspecialchars($og['title'] ?? ''); ?>">
        <meta name="twitter:description" content="<?php echo htmlspecialchars($og['description'] ?? ''); ?>">
        <?php if (!empty($og['image'])): ?>
            <meta name="twitter:image" content="<?php echo htmlspecialchars($og['image']); ?>">
        <?php endif; ?>
    <?php endif; ?>
    <link rel="icon" type="image/png" href="<?php echo $base_path; ?>assets/images/Logos/logo.png">
    <link rel="shortcut icon" type="image/png" href="<?php echo $base_path; ?>assets/images/Logos/logo.png">
    <link rel="apple-touch-icon" href="<?php echo $base_path; ?>assets/images/Logos/logo.png">
    <!-- Preload Font Awesome to reduce icon flash on navigation -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style" onload="this.rel='stylesheet'">
    <noscript><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"></noscript>
    <style>
        /* Hide icon glyphs until webfont is ready to avoid brief text/fallback flash */
        .fa, .fas, .far, .fal, .fab { visibility: hidden; }
        .icons-ready .fa, .icons-ready .fas, .icons-ready .far, .icons-ready .fal, .icons-ready .fab { visibility: visible; }
    </style>
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/style.css">
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $base_path; ?><?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <script>
        (function() {
            try {
                if (document.fonts && document.fonts.ready) {
                    document.fonts.ready.then(function(){ document.documentElement.classList.add('icons-ready'); });
                } else {
                    window.addEventListener('load', function(){ document.documentElement.classList.add('icons-ready'); });
                }
            } catch (e) { document.documentElement.classList.add('icons-ready'); }
        })();
    </script>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- First Column: Logo -->
            <div class="nav-logo">
        <a href="<?php echo $base_path; ?>index.php">
            <img src="<?php echo $base_path; ?>assets/images/Logos/logo.png" alt="University of Perpetual Help System" class="logo-img">
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
                        <form class="search-form" action="<?php echo isset($base_path) ? $base_path : ''; ?>search.php" method="GET">
                            <input type="text" name="q" placeholder="Search..." class="search-input" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Mobile Search Bar (separate from nav-header) -->
                <div class="nav-search mobile-search">
                    <form class="search-form" action="<?php echo isset($base_path) ? $base_path : ''; ?>search.php" method="GET">
                        <input type="text" name="q" placeholder="Search..." class="search-input" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
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
                        <a href="<?php echo $base_path; ?>index.php" class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>" style="<?php echo ($current_page != 'index') ? 'background: none !important; color: #ffffff !important;' : ''; ?>">Home</a>
                        <!-- DEBUG: Home - current_page='<?php echo $current_page; ?>', is_index=<?php echo ($current_page == 'index') ? 'true' : 'false'; ?>, classes="<?php echo ($current_page == 'index') ? 'active' : ''; ?>" -->
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="<?php echo $base_path; ?>programs.php" class="nav-link dropdown-toggle <?php 
                            $is_programs_active = ($current_page == 'programs' || strpos($current_page, 'programs') !== false || in_array($current_page, ['senior-high-school', 'junior-high-school', 'grade-school', 'aviation', 'arts-sciences', 'business-accountancy', 'computer-studies', 'criminology', 'education', 'engineering-architecture', 'hospitality-management', 'maritime', 'law', 'graduate-school']));
                            echo $is_programs_active ? 'active' : '';
                        ?>" style="<?php echo $is_programs_active ? 'background: rgba(255, 198, 62, 0.2) !important; color: #ffc63e !important;' : ''; ?>">Programs <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <!-- DEBUG: Programs - current_page='<?php echo $current_page; ?>', is_programs_active=<?php echo $is_programs_active ? 'true' : 'false'; ?>, classes="<?php echo $is_programs_active ? 'active' : ''; ?>" -->
                        <div class="dropdown-menu">
                            <div class="dropdown-item-with-submenu">
                                <a href="#" class="dropdown-link dropdown-parent">Basic Education <i class="fas fa-chevron-right submenu-chevron"></i></a>
                                <div class="submenu-dropdown">
                                    <a href="<?php echo $base_path; ?>programs/senior-high-school.php" class="submenu-link <?php echo ($current_page == 'senior-high-school') ? 'active' : ''; ?>">Senior High School</a>
                                    <a href="<?php echo $base_path; ?>programs/junior-high-school.php" class="submenu-link <?php echo ($current_page == 'junior-high-school') ? 'active' : ''; ?>">Junior High School</a>
                                    <a href="<?php echo $base_path; ?>programs/grade-school.php" class="submenu-link <?php echo ($current_page == 'grade-school') ? 'active' : ''; ?>">Grade School</a>
                                </div>
                            </div>
                            <a href="<?php echo $base_path; ?>programs/aviation.php" class="dropdown-link <?php echo ($current_page == 'aviation') ? 'active' : ''; ?>">Aviation</a>
                            <a href="<?php echo $base_path; ?>programs/arts-sciences.php" class="dropdown-link <?php echo ($current_page == 'arts-sciences') ? 'active' : ''; ?>">Arts & Sciences</a>
                            <a href="<?php echo $base_path; ?>programs/business-accountancy.php" class="dropdown-link <?php echo ($current_page == 'business-accountancy') ? 'active' : ''; ?>">Business & Accountancy</a>
                            <a href="<?php echo $base_path; ?>programs/computer-studies.php" class="dropdown-link <?php echo ($current_page == 'computer-studies') ? 'active' : ''; ?>">Computer Studies</a>
                            <a href="<?php echo $base_path; ?>programs/criminology.php" class="dropdown-link <?php echo ($current_page == 'criminology') ? 'active' : ''; ?>">Criminology</a>
                            <a href="<?php echo $base_path; ?>programs/education.php" class="dropdown-link <?php echo ($current_page == 'education') ? 'active' : ''; ?>">Education</a>
                            <a href="<?php echo $base_path; ?>programs/engineering-architecture.php" class="dropdown-link <?php echo ($current_page == 'engineering-architecture') ? 'active' : ''; ?>">Engineering & Architecture</a>
                            <a href="<?php echo $base_path; ?>programs/hospitality-management.php" class="dropdown-link <?php echo ($current_page == 'hospitality-management') ? 'active' : ''; ?>">International Hospitality Management</a>
                            <a href="<?php echo $base_path; ?>programs/maritime.php" class="dropdown-link <?php echo ($current_page == 'maritime') ? 'active' : ''; ?>">Maritime</a>
                            <a href="<?php echo $base_path; ?>programs/law.php" class="dropdown-link <?php echo ($current_page == 'law') ? 'active' : ''; ?>">Law/Juris Doctor</a>
                            <a href="<?php echo $base_path; ?>programs/graduate-school.php" class="dropdown-link <?php echo ($current_page == 'graduate-school') ? 'active' : ''; ?>">Graduate School</a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?php echo ($current_page == 'ols_instructions') ? 'active' : ''; ?>">Online Services <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <a href="<?php echo $base_path; ?>ols_instructions.php" class="dropdown-link <?php echo ($current_page == 'ols_instructions') ? 'active' : ''; ?>">Instructions</a>
                            <a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="dropdown-link">GTI Online Grades</a>
                            <a href="https://uphslms.com/blended/login/index.php" target="_blank" class="dropdown-link">Moodle</a>
                            <a href="https://accounts.google.com/signin" target="_blank" class="dropdown-link">Google Account</a>
                            <a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="dropdown-link">Online Payment</a>
                            <a href="https://login.microsoftonline.com/" target="_blank" class="dropdown-link">Microsoft 365</a>
                            <a href="https://saliksikuphsl.org/" target="_blank" class="dropdown-link">Saliksik</a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?php echo (in_array($current_page, ['support-services', 'careers', 'clinic', 'cod', 'iea', 'sps', 'library', 'quality-assurance', 'research'])) ? 'active' : ''; ?>">Support Services <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <a href="https://docs.google.com/forms/d/e/1FAIpQLSea8-O2OuuKWgZ17XgKkyLQ7dDOawW31a8vq1nTWDRREODVMQ/viewform" target="_blank" class="dropdown-link">Alumni</a>
                            <a href="<?php echo $base_path; ?>support-services/careers.php" class="dropdown-link <?php echo ($current_page == 'careers') ? 'active' : ''; ?>">Careers</a>
                            <a href="<?php echo $base_path; ?>support-services/clinic.php" class="dropdown-link <?php echo ($current_page == 'clinic') ? 'active' : ''; ?>">University Clinic</a>
                            <a href="<?php echo $base_path; ?>support-services/cod.php" class="dropdown-link <?php echo ($current_page == 'cod') ? 'active' : ''; ?>">Community Outreach Department</a>
                            <a href="<?php echo $base_path; ?>support-services/iea.php" class="dropdown-link <?php echo ($current_page == 'iea') ? 'active' : ''; ?>">International & External Affairs</a>
                            <a href="<?php echo $base_path; ?>support-services/sps.php" class="dropdown-link <?php echo ($current_page == 'sps') ? 'active' : ''; ?>">Guidance & Admission</a>
                            <a href="<?php echo $base_path; ?>support-services/library.php" class="dropdown-link <?php echo ($current_page == 'library') ? 'active' : ''; ?>">Library</a>
                            <a href="<?php echo $base_path; ?>support-services/quality-assurance.php" class="dropdown-link <?php echo ($current_page == 'quality-assurance') ? 'active' : ''; ?>">Quality Assurance</a>
                            <a href="<?php echo $base_path; ?>support-services/research.php" class="dropdown-link <?php echo ($current_page == 'research') ? 'active' : ''; ?>">Research</a>
                        </div>
                    </div>
                    
                    <div class="nav-item">
                        <a href="<?php echo $base_path; ?>campuses.php" class="nav-link <?php echo ($current_page == 'campuses') ? 'active' : ''; ?>">Campuses</a>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?php echo (in_array($current_page, ['about', 'contact', 'environmental-policy', 'university-policy', 'map'])) ? 'active' : ''; ?>">About <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <a href="<?php echo $base_path; ?>about" class="dropdown-link <?php echo ($current_page == 'about') ? 'active' : ''; ?>">About Us</a>
                            <a href="<?php echo $base_path; ?>about/contact.php" class="dropdown-link <?php echo ($current_page == 'contact') ? 'active' : ''; ?>">Contact Us</a>
                            <a href="<?php echo $base_path; ?>about/environmental-policy.php" class="dropdown-link <?php echo ($current_page == 'environmental-policy') ? 'active' : ''; ?>">Environmental Policy</a>
                            <a href="<?php echo $base_path; ?>about/university-policy.php" class="dropdown-link <?php echo ($current_page == 'university-policy') ? 'active' : ''; ?>">University Policy</a>
                            <a href="<?php echo $base_path; ?>about/map.php" class="dropdown-link <?php echo ($current_page == 'map') ? 'active' : ''; ?>">Map</a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Online Payment <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <a href="https://uphsl.edu.ph/online_payment/guest_exam.php" target="_blank" class="dropdown-link">Entrance Exam</a>
                            <a href="https://uphsl.edu.ph/online_payment/guest.php" target="_blank" class="dropdown-link">New Enrollees</a>
                            <a href="https://uphsl.edu.ph/online_payment/guestold_student.php" target="_blank" class="dropdown-link">Enrolled Students</a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?php echo (in_array($current_page, ['college-academic-calendar', 'bed-shs-academic-calendar'])) ? 'active' : ''; ?>">Calendar <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <a href="<?php echo $base_path; ?>calendar/college-academic-calendar.php" class="dropdown-link <?php echo ($current_page == 'college-academic-calendar') ? 'active' : ''; ?>">College Academic Calendar</a>
                            <a href="<?php echo $base_path; ?>calendar/bed-shs-academic-calendar.php" class="dropdown-link <?php echo ($current_page == 'bed-shs-academic-calendar') ? 'active' : ''; ?>">BED & SHS Academic Calendar</a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Enrollment <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <a href="https://docs.google.com/forms/d/e/1FAIpQLSfuxQtL77zIZ13Zqzk951FiIrSpGApccIFyp_Gr6faD1vtVng/closedform" class="dropdown-link disabled" onclick="return false;">Enrollment for College & Graduate School & Juris Doctor</a>
                            <a href="https://docs.google.com/forms/d/e/1FAIpQLSfh2CKtB6Nmz0CeDvWKaTETuNCbaFiZiuo2UdQ0u5t4zJtgvQ/closedform" class="dropdown-link disabled" onclick="return false;">Enrollment for Senior High School</a>
                        </div>
                    </div>
                    
                    <div class="nav-item">
                        <a href="<?php echo $base_path; ?>sdg-initiatives.php" class="nav-link <?php echo ($current_page == 'sdg-initiatives') ? 'active' : ''; ?>">SDG Initiatives</a>
                    </div>
                    
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
                <img src="<?php echo $base_path; ?>assets/images/Logos/logo.png" alt="University of Perpetual Help System" class="mobile-logo-img">
                <h2 class="mobile-site-name">UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA</h2>
            </div>
            <button class="mobile-sidebar-close" id="mobile-sidebar-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mobile-sidebar-search">
            <form class="mobile-search-form" action="<?php echo isset($base_path) ? $base_path : ''; ?>search.php" method="GET">
                <input type="text" name="q" placeholder="Search..." class="mobile-search-input" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                <button type="submit" class="mobile-search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        
        <nav class="mobile-sidebar-menu">
            <div class="mobile-nav-item">
                <a href="<?php echo $base_path; ?>index.php" class="mobile-nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>">Home</a>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="<?php echo $base_path; ?>programs.php" class="mobile-nav-link mobile-dropdown-toggle <?php echo ($current_page == 'programs' || strpos($current_page, 'programs') !== false || in_array($current_page, ['senior-high-school', 'junior-high-school', 'grade-school', 'aviation', 'arts-sciences', 'business-accountancy', 'computer-studies', 'criminology', 'education', 'engineering-architecture', 'hospitality-management', 'maritime', 'law', 'graduate-school'])) ? 'active' : ''; ?>">Programs <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <div class="mobile-dropdown-item-with-submenu">
                        <a href="#" class="mobile-dropdown-link mobile-dropdown-parent">Basic Education <i class="fas fa-chevron-right mobile-submenu-chevron"></i></a>
                        <div class="mobile-submenu-dropdown">
                            <a href="<?php echo $base_path; ?>programs/senior-high-school.php" class="mobile-submenu-link <?php echo ($current_page == 'senior-high-school') ? 'active' : ''; ?>">Senior High School</a>
                            <a href="<?php echo $base_path; ?>programs/junior-high-school.php" class="mobile-submenu-link <?php echo ($current_page == 'junior-high-school') ? 'active' : ''; ?>">Junior High School</a>
                            <a href="<?php echo $base_path; ?>programs/grade-school.php" class="mobile-submenu-link <?php echo ($current_page == 'grade-school') ? 'active' : ''; ?>">Grade School</a>
                        </div>
                    </div>
                    <a href="<?php echo $base_path; ?>programs/aviation.php" class="mobile-dropdown-link <?php echo ($current_page == 'aviation') ? 'active' : ''; ?>">Aviation</a>
                    <a href="<?php echo $base_path; ?>programs/arts-sciences.php" class="mobile-dropdown-link <?php echo ($current_page == 'arts-sciences') ? 'active' : ''; ?>">Arts & Sciences</a>
                    <a href="<?php echo $base_path; ?>programs/business-accountancy.php" class="mobile-dropdown-link <?php echo ($current_page == 'business-accountancy') ? 'active' : ''; ?>">Business & Accountancy</a>
                    <a href="<?php echo $base_path; ?>programs/computer-studies.php" class="mobile-dropdown-link <?php echo ($current_page == 'computer-studies') ? 'active' : ''; ?>">Computer Studies</a>
                    <a href="<?php echo $base_path; ?>programs/criminology.php" class="mobile-dropdown-link <?php echo ($current_page == 'criminology') ? 'active' : ''; ?>">Criminology</a>
                    <a href="<?php echo $base_path; ?>programs/education.php" class="mobile-dropdown-link <?php echo ($current_page == 'education') ? 'active' : ''; ?>">Education</a>
                    <a href="<?php echo $base_path; ?>programs/engineering-architecture.php" class="mobile-dropdown-link <?php echo ($current_page == 'engineering-architecture') ? 'active' : ''; ?>">Engineering & Architecture</a>
                    <a href="<?php echo $base_path; ?>programs/hospitality-management.php" class="mobile-dropdown-link <?php echo ($current_page == 'hospitality-management') ? 'active' : ''; ?>">International Hospitality Management</a>
                    <a href="<?php echo $base_path; ?>programs/maritime.php" class="mobile-dropdown-link <?php echo ($current_page == 'maritime') ? 'active' : ''; ?>">Maritime</a>
                    <a href="<?php echo $base_path; ?>programs/law.php" class="mobile-dropdown-link <?php echo ($current_page == 'law') ? 'active' : ''; ?>">Law/Juris Doctor</a>
                    <a href="<?php echo $base_path; ?>programs/graduate-school.php" class="mobile-dropdown-link <?php echo ($current_page == 'graduate-school') ? 'active' : ''; ?>">Graduate School</a>
                </div>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle <?php echo ($current_page == 'ols_instructions') ? 'active' : ''; ?>">Online Services <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="<?php echo $base_path; ?>ols_instructions.php" class="mobile-dropdown-link <?php echo ($current_page == 'ols_instructions') ? 'active' : ''; ?>">Instructions</a>
                    <a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="mobile-dropdown-link">GTI Online Grades</a>
                    <a href="https://uphslms.com/blended/login/index.php" target="_blank" class="mobile-dropdown-link">Moodle</a>
                    <a href="https://accounts.google.com/signin" target="_blank" class="mobile-dropdown-link">Google Account</a>
                    <a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="mobile-dropdown-link">Online Payment</a>
                    <a href="https://login.microsoftonline.com/" target="_blank" class="mobile-dropdown-link">Microsoft 365</a>
                    <a href="https://saliksikuphsl.org/" target="_blank" class="mobile-dropdown-link">Saliksik</a>
                </div>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle <?php echo (in_array($current_page, ['support-services', 'careers', 'clinic', 'cod', 'iea', 'sps', 'library', 'quality-assurance', 'research'])) ? 'active' : ''; ?>">Support Services <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSea8-O2OuuKWgZ17XgKkyLQ7dDOawW31a8vq1nTWDRREODVMQ/viewform" target="_blank" class="mobile-dropdown-link">Alumni</a>
                    <a href="<?php echo $base_path; ?>support-services/careers.php" class="mobile-dropdown-link <?php echo ($current_page == 'careers') ? 'active' : ''; ?>">Careers</a>
                    <a href="<?php echo $base_path; ?>support-services/clinic.php" class="mobile-dropdown-link <?php echo ($current_page == 'clinic') ? 'active' : ''; ?>">University Clinic</a>
                    <a href="<?php echo $base_path; ?>support-services/cod.php" class="mobile-dropdown-link <?php echo ($current_page == 'cod') ? 'active' : ''; ?>">Community Outreach Department</a>
                    <a href="<?php echo $base_path; ?>support-services/iea.php" class="mobile-dropdown-link <?php echo ($current_page == 'iea') ? 'active' : ''; ?>">International & External Affairs</a>
                    <a href="<?php echo $base_path; ?>support-services/sps.php" class="mobile-dropdown-link <?php echo ($current_page == 'sps') ? 'active' : ''; ?>">Guidance & Admission</a>
                    <a href="<?php echo $base_path; ?>support-services/library.php" class="mobile-dropdown-link <?php echo ($current_page == 'library') ? 'active' : ''; ?>">Library</a>
                    <a href="<?php echo $base_path; ?>support-services/quality-assurance.php" class="mobile-dropdown-link <?php echo ($current_page == 'quality-assurance') ? 'active' : ''; ?>">Quality Assurance</a>
                    <a href="<?php echo $base_path; ?>support-services/research.php" class="mobile-dropdown-link <?php echo ($current_page == 'research') ? 'active' : ''; ?>">Research</a>
                </div>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle <?php echo (in_array($current_page, ['college-academic-calendar', 'bed-shs-academic-calendar'])) ? 'active' : ''; ?>">Calendar <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="<?php echo $base_path; ?>calendar/college-academic-calendar.php" class="mobile-dropdown-link <?php echo ($current_page == 'college-academic-calendar') ? 'active' : ''; ?>">College Academic Calendar</a>
                    <a href="<?php echo $base_path; ?>calendar/bed-shs-academic-calendar.php" class="mobile-dropdown-link <?php echo ($current_page == 'bed-shs-academic-calendar') ? 'active' : ''; ?>">BED & SHS Academic Calendar</a>
                </div>
            </div>
            
            <div class="mobile-nav-item">
                <a href="<?php echo $base_path; ?>campuses.php" class="mobile-nav-link <?php echo ($current_page == 'campuses') ? 'active' : ''; ?>">Campuses</a>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">About <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="<?php echo $base_path; ?>about" class="mobile-dropdown-link">About Us</a>
                    <a href="<?php echo $base_path; ?>about/contact.php" class="mobile-dropdown-link">Contact Us</a>
                    <a href="<?php echo $base_path; ?>about/environmental-policy.php" class="mobile-dropdown-link">Environmental Policy</a>
                    <a href="<?php echo $base_path; ?>about/university-policy.php" class="mobile-dropdown-link">University Policy</a>
                    <a href="<?php echo $base_path; ?>about/map.php" class="mobile-dropdown-link">Map</a>
                </div>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Online Payment <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="https://uphsl.edu.ph/online_payment/guest_exam.php" target="_blank" class="mobile-dropdown-link">Entrance Exam</a>
                    <a href="https://uphsl.edu.ph/online_payment/guest.php" target="_blank" class="mobile-dropdown-link">New Enrollees</a>
                    <a href="https://uphsl.edu.ph/online_payment/guestold_student.php" target="_blank" class="mobile-dropdown-link">Enrolled Students</a>
                </div>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Calendar <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="<?php echo $base_path; ?>about/about.php" class="mobile-dropdown-link">College Academic Calendar</a>
                    <a href="<?php echo $base_path; ?>about/about.php" class="mobile-dropdown-link">BED & SHS Academic Calendar</a>
                </div>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Enrollment <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSfuxQtL77zIZ13Zqzk951FiIrSpGApccIFyp_Gr6faD1vtVng/closedform" class="mobile-dropdown-link disabled" onclick="return false;">Enrollment for College & Graduate School & Juris Doctor</a>
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSfh2CKtB6Nmz0CeDvWKaTETuNCbaFiZiuo2UdQ0u5t4zJtgvQ/closedform" class="mobile-dropdown-link disabled" onclick="return false;">Enrollment for Senior High School</a>
                </div>
            </div>
            
            <div class="mobile-nav-item">
                <a href="<?php echo $base_path; ?>sdg-initiatives.php" class="mobile-nav-link <?php echo ($current_page == 'sdg-initiatives') ? 'active' : ''; ?>">SDG Initiatives</a>
            </div>
            
        </nav>
    </div>

    <!-- Floating User Actions (only for logged-in users on main website) -->
    <?php if (isset($_SESSION['user_id']) && !strpos($_SERVER['REQUEST_URI'], '/admin/')): ?>
    <div class="floating-user-actions">
        <a href="<?php echo $base_path; ?>admin/dashboard.php" class="floating-btn floating-dashboard" title="Dashboard">
            <i class="fas fa-tachometer-alt"></i>
        </a>
        <a href="<?php echo $base_path; ?>auth/logout.php" class="floating-btn floating-logout" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
    <?php endif; ?>

    </div>
