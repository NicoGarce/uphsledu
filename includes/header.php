<?php
// Get current page for active navigation highlighting
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo (isset($page_title) && $page_title !== 'Home') ? $page_title . ' - ' : ''; ?>University of Perpetual Help System Laguna</title>
    <link rel="icon" type="image/png" href="<?php echo isset($base_path) ? $base_path : ''; ?>assets/images/logo.png">
    <link rel="shortcut icon" type="image/png" href="<?php echo isset($base_path) ? $base_path : ''; ?>assets/images/logo.png">
    <link rel="apple-touch-icon" href="<?php echo isset($base_path) ? $base_path : ''; ?>assets/images/logo.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo isset($base_path) ? $base_path : ''; ?>assets/css/style.css">
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- First Column: Logo -->
            <div class="nav-logo">
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php">
                    <img src="<?php echo isset($base_path) ? $base_path : ''; ?>assets/images/logo.png" alt="University of Perpetual Help System" class="logo-img">
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
                        <a href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php" class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>">Home</a>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs.php" class="nav-link dropdown-toggle">Programs <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-item-with-submenu">
                                <a href="#" class="dropdown-link dropdown-parent">Basic Education <i class="fas fa-chevron-right submenu-chevron"></i></a>
                                <div class="submenu-dropdown">
                                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/senior-high-school.php" class="submenu-link">Senior High School</a>
                                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/junior-high-school.php" class="submenu-link">Junior High School</a>
                                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/grade-school.php" class="submenu-link">Grade School</a>
                                </div>
                            </div>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/aviation.php" class="dropdown-link">Aviation</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/arts-sciences.php" class="dropdown-link">Arts & Sciences</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/business-accountancy.php" class="dropdown-link">Business & Accountancy</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/computer-studies.php" class="dropdown-link">Computer Studies</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/criminology.php" class="dropdown-link">Criminology</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/education.php" class="dropdown-link">Education</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/engineering-architecture.php" class="dropdown-link">Engineering & Architecture</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/hospitality-management.php" class="dropdown-link">International Hospitality Management</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/maritime.php" class="dropdown-link">Maritime</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/law.php" class="dropdown-link">Law/Juris Doctor</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/graduate-school.php" class="dropdown-link">Graduate School</a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Online Services <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>ols_instructions.php" class="dropdown-link">Instructions</a>
                            <a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="dropdown-link">GTI Online Grades</a>
                            <a href="https://uphslms.com/blended/login/index.php" target="_blank" class="dropdown-link">Moodle</a>
                            <a href="https://accounts.google.com/signin" target="_blank" class="dropdown-link">Google Account</a>
                            <a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="dropdown-link">Online Payment</a>
                            <a href="https://login.microsoftonline.com/" target="_blank" class="dropdown-link">Microsoft 365</a>
                            <a href="https://saliksikuphsl.org/" target="_blank" class="dropdown-link">Saliksik</a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Support Services <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <a href="https://docs.google.com/forms/d/e/1FAIpQLSea8-O2OuuKWgZ17XgKkyLQ7dDOawW31a8vq1nTWDRREODVMQ/viewform" target="_blank" class="dropdown-link">Alumni</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>support-services/careers.php" class="dropdown-link">Careers</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>support-services/clinic.php" class="dropdown-link">University Clinic</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>support-services/cod.php" class="dropdown-link">Community Outreach Department</a>
                            <a href="#" class="dropdown-link">International and External Affairs</a>
                            <a href="#" class="dropdown-link">Guidance and Admission</a>
                            <a href="#" class="dropdown-link">Library</a>
                            <a href="#" class="dropdown-link">Quality Assurance</a>
                            <a href="#" class="dropdown-link">Research</a>
                        </div>
                    </div>
                    
                    <div class="nav-item">
                        <a href="<?php echo isset($base_path) ? $base_path : ''; ?>contact.php" class="nav-link">Campuses</a>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">About <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>about.php" class="dropdown-link">About Us</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>contact.php" class="dropdown-link">Contact Us</a>
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
                        <a href="<?php echo isset($base_path) ? $base_path : ''; ?>about.php" class="nav-link">Calendar</a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="<?php echo isset($base_path) ? $base_path : ''; ?>about.php" class="nav-link">SDG Initiatives</a>
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
                <img src="<?php echo isset($base_path) ? $base_path : ''; ?>assets/images/logo.png" alt="University of Perpetual Help System" class="mobile-logo-img">
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
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php" class="mobile-nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>">Home</a>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs.php" class="mobile-nav-link mobile-dropdown-toggle">Programs <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <div class="mobile-dropdown-item-with-submenu">
                        <a href="#" class="mobile-dropdown-link mobile-dropdown-parent">Basic Education <i class="fas fa-chevron-right mobile-submenu-chevron"></i></a>
                        <div class="mobile-submenu-dropdown">
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/senior-high-school.php" class="mobile-submenu-link">Senior High School</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/junior-high-school.php" class="mobile-submenu-link">Junior High School</a>
                            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/grade-school.php" class="mobile-submenu-link">Grade School</a>
                        </div>
                    </div>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/aviation.php" class="mobile-dropdown-link">Aviation</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/arts-sciences.php" class="mobile-dropdown-link">Arts & Sciences</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/business-accountancy.php" class="mobile-dropdown-link">Business & Accountancy</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/computer-studies.php" class="mobile-dropdown-link">Computer Studies</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/criminology.php" class="mobile-dropdown-link">Criminology</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/education.php" class="mobile-dropdown-link">Education</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/engineering-architecture.php" class="mobile-dropdown-link">Engineering & Architecture</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/hospitality-management.php" class="mobile-dropdown-link">International Hospitality Management</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/maritime.php" class="mobile-dropdown-link">Maritime</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/law.php" class="mobile-dropdown-link">Law/Juris Doctor</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs/graduate-school.php" class="mobile-dropdown-link">Graduate School</a>
                </div>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Online Services <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>ols_instructions.php" class="mobile-dropdown-link">Instructions</a>
                    <a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="mobile-dropdown-link">GTI Online Grades</a>
                    <a href="https://uphslms.com/blended/login/index.php" target="_blank" class="mobile-dropdown-link">Moodle</a>
                    <a href="https://accounts.google.com/signin" target="_blank" class="mobile-dropdown-link">Google Account</a>
                    <a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="mobile-dropdown-link">Online Payment</a>
                    <a href="https://login.microsoftonline.com/" target="_blank" class="mobile-dropdown-link">Microsoft 365</a>
                    <a href="https://saliksikuphsl.org/" target="_blank" class="mobile-dropdown-link">Saliksik</a>
                </div>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Support Services <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSea8-O2OuuKWgZ17XgKkyLQ7dDOawW31a8vq1nTWDRREODVMQ/viewform" target="_blank" class="mobile-dropdown-link">Alumni</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>support-services/careers.php" class="mobile-dropdown-link">Careers</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>support-services/clinic.php" class="mobile-dropdown-link">University Clinic</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>support-services/cod.php" class="mobile-dropdown-link">Community Outreach Department</a>
                    <a href="#" class="mobile-dropdown-link">International and External Affairs</a>
                    <a href="#" class="mobile-dropdown-link">Guidance and Admission</a>
                    <a href="#" class="mobile-dropdown-link">Library</a>
                    <a href="#" class="mobile-dropdown-link">Quality Assurance</a>
                    <a href="#" class="mobile-dropdown-link">Research</a>
                </div>
            </div>
            
            <div class="mobile-nav-item">
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>contact.php" class="mobile-nav-link">Campuses</a>
            </div>
            
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">About <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>about.php" class="mobile-dropdown-link">About Us</a>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>contact.php" class="mobile-dropdown-link">Contact Us</a>
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
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>about.php" class="mobile-nav-link">Calendar</a>
            </div>
            
            <div class="mobile-nav-item">
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>about.php" class="mobile-nav-link">SDG Initiatives</a>
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
