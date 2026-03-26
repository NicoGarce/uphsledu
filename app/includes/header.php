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

// Include security features (CSRF, XSS, CSP, etc.)
require_once __DIR__ . '/security.php';

// Check for maintenance mode (before any output)
// Allow access to admin pages and auth pages even in maintenance mode
$current_script = basename($_SERVER['PHP_SELF']);
$is_admin_page = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;
$is_auth_page = strpos($_SERVER['REQUEST_URI'], '/auth/') !== false;
$is_maintenance_page = $current_script === 'maintenance.php';

// Check if maintenance mode is enabled (only if not admin/auth/maintenance page)
if (!$is_admin_page && !$is_auth_page && !$is_maintenance_page) {
    try {
        require_once __DIR__ . '/../config/database.php';
        require_once __DIR__ . '/functions.php';

        // Ensure DB initializer runs on every page load (idempotent)
        if (function_exists('initializeDatabase')) {
            try { initializeDatabase(); } catch (Exception $e) { error_log('DB init error: ' . $e->getMessage()); }
        }
        
        // Check if maintenance mode is enabled
        if (function_exists('getSetting')) {
            // Check if maintenance mode is enabled
            $maintenance_mode = getSetting('maintenance_mode', '0');
            
            if ($maintenance_mode === '1') {
                // Redirect to maintenance page
                $maintenance_url = $GLOBALS['base_path'] . 'maintenance.php';
                header('Location: ' . $maintenance_url);
                exit;
            }
        }
    } catch (Exception $e) {
        // If there's an error, continue loading the page normally
        error_log('Maintenance mode check error: ' . $e->getMessage());
    }
}

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
// Only apply this logic if we're NOT on the root index.php
if ($current_page === 'index' && (strpos($script_name, '/') !== false || strpos($request_uri, '/') !== false)) {
    // Check if we're on the root index.php (not in a subdirectory)
    $is_root_index = ($script_name === '/index.php' || $script_name === '/uphsledu/index.php' || 
                     $request_uri === '/' || $request_uri === '/uphsledu/' || 
                     $request_uri === '/index.php' || $request_uri === '/uphsledu/index.php');
    
    if (!$is_root_index) {
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
}

// Additional check for subdirectories - handle cases where the path contains the directory name
if ($current_page === 'index' && (strpos($request_uri, 'programs') !== false || strpos($request_uri, 'support-services') !== false)) {
    if (strpos($request_uri, 'programs') !== false) {
        $current_page = 'programs';
    } elseif (strpos($request_uri, 'support-services') !== false) {
        $current_page = 'support-services';
    }
}


// Use the automatically detected base path
$base_path = $GLOBALS['base_path'];

// Define navbar items configuration for checking sub-items
$navbar_items_config = [
    'programs' => [
        'basic-education' => 'Basic Education',
        'senior-high-school' => 'Senior High School',
        'junior-high-school' => 'Junior High School',
        'grade-school' => 'Grade School',
        'aviation' => 'Aviation',
        'arts-sciences' => 'Arts & Sciences',
        'business-accountancy' => 'Business & Accountancy',
        'computer-studies' => 'Computer Studies',
        'criminology' => 'Criminology',
        'education' => 'Education',
        'engineering-architecture' => 'Engineering & Architecture',
        'hospitality-management' => 'International Hospitality Management',
        'maritime' => 'Maritime',
        'law' => 'Law/Juris Doctor',
        'graduate-school' => 'Graduate School'
    ],
    'online-services' => [
        'instructions' => 'Instructions',
        'gti-online-grades' => 'GTI Online Grades',
        'moodle' => 'Moodle',
        'google-account' => 'Google Account',
        'microsoft-365' => 'Microsoft 365',
        'saliksik' => 'Saliksik'
    ],
    'support-services' => [
        'alumni' => 'Alumni',
        'careers' => 'Careers',
        'clinic' => 'University Clinic',
        'cod' => 'Community Outreach Department',
        'iea' => 'International & External Affairs',
        'sps' => 'Student Personnel Services',
        'library' => 'Library',
        'quality-assurance' => 'Quality Assurance',
        'research' => 'Research'
    ],
    'about' => [
        'about-us' => 'About Us',
        'contact' => 'Contact Us',
        'environmental-policy' => 'Environmental Policy',
        'university-policy' => 'University Policy',
        'map' => 'Map'
    ],
    'online-payment' => [
        'entrance-exam' => 'Entrance Exam',
        'new-enrollees' => 'New Enrollees',
        'enrolled-students' => 'Enrolled Students',
        'other-payments' => 'Other Payments'
    ],
    'calendar' => [
        'college-academic-calendar' => 'College Academic Calendar',
        'bed-shs-academic-calendar' => 'BED & SHS Academic Calendar'
    ],
    'enrollment' => [
        'enrollment-college' => 'Enrollment for College & Graduate School & Juris Doctor',
        'enrollment-shs' => 'Enrollment for Senior High School'
    ],
    'sdg-initiatives' => [
        'sdg-1' => 'SDG 1',
        'sdg-2' => 'SDG 2',
        'sdg-3' => 'SDG 3',
        'sdg-4' => 'SDG 4',
        'sdg-5' => 'SDG 5',
        'sdg-6' => 'SDG 6',
        'sdg-7' => 'SDG 7',
        'sdg-8' => 'SDG 8',
        'sdg-9' => 'SDG 9',
        'sdg-10' => 'SDG 10',
        'sdg-11' => 'SDG 11',
        'sdg-12' => 'SDG 12',
        'sdg-13' => 'SDG 13',
        'sdg-14' => 'SDG 14',
        'sdg-15' => 'SDG 15',
        'sdg-16' => 'SDG 16',
        'sdg-17' => 'SDG 17',
        'sdg-full-report' => 'SDG Full Report'
    ]
];
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
    <title><?php echo (isset($page_title) && $page_title !== 'Home') ? $page_title . ' - ' : ''; ?><?php echo htmlspecialchars(getSetting('site_name', 'University of Perpetual Help System Laguna')); ?></title>
    <!-- Performance: speed up icon/font loading to prevent flash of text -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <!-- Preload critical logo image to prevent text flash -->
    <link rel="preload" href="<?php echo $base_path; ?>assets/images/Logos/Logo2025.png" as="image" type="image/png">
    
    <?php
    // Conditional preloading based on current page
    // Don't reassign $current_page here as it was already set above with proper subdirectory detection
    $request_uri = $_SERVER['REQUEST_URI'];
    
    // Preload program logos only on programs pages
    if (strpos($request_uri, 'programs') !== false) {
        echo '    <!-- Preload program logos to prevent alt text flash -->' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'programs/img/logo/uphsl-cihm-logo.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'programs/img/logo/uphsl-shs-logo.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'programs/img/logo/logo.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'programs/img/logo/uphsl-educ-logo.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'programs/img/logo/uphsl-criminology-logo.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'programs/img/logo/CCS-Logo.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'programs/img/logo/uphsl-cba_logo.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'programs/img/logo/aviation_logo.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'programs/img/logo/uphsl-cas-logo.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'programs/img/logo/logo-cmt.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'programs/img/logo/logo-law.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'programs/img/logo/graduate-school-logo.png" as="image" type="image/png">' . "\n";
    }
    
    // Preload support service logos only on support-services pages
    if (strpos($request_uri, 'support-services') !== false) {
        echo '    <!-- Preload support service logos to prevent alt text flash -->' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/sps/Picture1.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/sps/Handbook.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/library/logo.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/research/uphsl-research-logo.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/cod/UPHSL-COD.png" as="image" type="image/png">' . "\n";
    }
    
    // Preload library service images only on library page
    if (strpos($request_uri, 'library') !== false) {
        echo '    <!-- Preload library service images to prevent alt text flash -->' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/support-services/college-library/img/olservices/uphsl-opac.jpg" as="image" type="image/jpeg">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/support-services/college-library/img/olservices/uphsl-ebsco.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/support-services/college-library/img/olservices/uphsl-pej.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/support-services/college-library/img/olservices/starbooks.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/support-services/college-library/img/olservices/escra.png" as="image" type="image/png">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/support-services/college-library/img/olservices/turnitin.png" as="image" type="image/png">' . "\n";
    }
    
    // Preload campus images only on campuses page
    if ($current_page === 'campuses') {
        echo '    <!-- Preload campus images to prevent alt text flash -->' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/FACADE.jpg" as="image" type="image/jpeg">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/campuses/gma-college.jpeg" as="image" type="image/jpeg">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/campuses/sampaloc-college.jpeg" as="image" type="image/jpeg">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/campuses/uphs-pangasinan.jpg" as="image" type="image/jpeg">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/campuses/Cauayan-college.jpg" as="image" type="image/jpeg">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/campuses/pueblo-college.jpg" as="image" type="image/jpeg">' . "\n";
        echo '    <link rel="preload" href="' . $base_path . 'assets/images/campuses/Allied.png" as="image" type="image/png">' . "\n";
    }
    ?>
    
    <?php if (!empty($og) && is_array($og)): ?>
        <meta property="og:title" content="<?php echo htmlspecialchars($og['title'] ?? ''); ?>">
        <meta property="og:description" content="<?php echo htmlspecialchars($og['description'] ?? ''); ?>">
        <meta property="og:url" content="<?php echo htmlspecialchars($og['url'] ?? ''); ?>">
        <?php if (!empty($og['image'])): ?>
            <meta property="og:image" content="<?php echo htmlspecialchars($og['image']); ?>">
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">
            <meta property="og:image:alt" content="<?php echo htmlspecialchars($og['title'] ?? ''); ?>">
        <?php endif; ?>
        <meta property="og:type" content="<?php echo htmlspecialchars($og['type'] ?? 'website'); ?>">
        <meta property="og:site_name" content="<?php echo htmlspecialchars($og['site_name'] ?? getSetting('site_name', 'University of Perpetual Help System Laguna')); ?>">
        
        <?php if (!empty($og['article_author'])): ?>
            <meta property="article:author" content="<?php echo htmlspecialchars($og['article_author']); ?>">
        <?php endif; ?>
        
        <?php if (!empty($og['article_publisher'])): ?>
            <meta property="article:publisher" content="<?php echo htmlspecialchars($og['article_publisher']); ?>">
        <?php endif; ?>
        
        <?php if (!empty($og['article_published_time'])): ?>
            <meta property="article:published_time" content="<?php echo htmlspecialchars($og['article_published_time']); ?>">
        <?php endif; ?>
        
        <?php if (!empty($og['article_modified_time'])): ?>
            <meta property="article:modified_time" content="<?php echo htmlspecialchars($og['article_modified_time']); ?>">
        <?php endif; ?>
        
        <!-- Facebook App ID for better page sharing -->
        
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
        
        /* Prevent alt text flash for logos - hide immediately */
        .intro-logo img, .banner-logo img, .service-image img, .campus-image {
            opacity: 0 !important;
            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .intro-logo img.loaded, .banner-logo img.loaded, .service-image img.loaded, .campus-image.loaded {
            opacity: 1 !important;
        }
        
        /* Banner images with shimmer loading */
        .hero-image, .news-slide-image img {
            opacity: 0 !important;
            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hero-image.loaded, .news-slide-image img.loaded {
            opacity: 1 !important;
        }
        
        /* Loading placeholder for image containers (no shimmer) */
        .campus-image-container, .service-image {
            position: relative;
            overflow: hidden;
        }
        
        .campus-image-container.loading::before, 
        .service-image.loading::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #f0f0f0;
            z-index: 1;
        }
        
        /* Logo containers - no loading background */
        .intro-logo {
            position: relative;
            overflow: hidden;
        }
        
        /* Banner image containers with shimmer loading */
        .hero-background, .page-hero, .page-header, .news-slide-image {
            position: relative;
            overflow: hidden;
        }
        
        .hero-background.loading::before,
        .page-hero.loading::before,
        .page-header.loading::before,
        .news-slide-image.loading::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading-shimmer 1.5s infinite;
            z-index: 1;
        }
        
        @keyframes loading-shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        /* Enhanced fade-in for specific image types */
        .campus-image {
            will-change: opacity;
        }
        
        .intro-logo img {
            will-change: opacity;
        }
        
        .service-image img {
            will-change: opacity;
        }
        
        /* Prevent logo text flash by hiding until image loads */
        .nav-logo, .mobile-sidebar-logo { 
            opacity: 0; 
            transition: opacity 0.2s ease-in-out;
        }
        .logo-loaded .nav-logo, .logo-loaded .mobile-sidebar-logo { 
            opacity: 1; 
        }
        
        /* Navbar Search Dropdown */
        .search-container {
            position: relative;
        }
        
        .search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
            max-height: 400px;
            overflow-y: auto;
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .search-dropdown.show {
            display: block;
            visibility: visible;
            opacity: 1;
        }
        
        /* Mobile show class */
        @media (max-width: 768px) {
            .search-dropdown.show {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
        }
        
        .search-results {
            padding: 0;
        }
        
        .search-result-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: background-color 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .search-result-item:last-child {
            border-bottom: none;
        }
        
        .search-result-item:hover {
            background-color: #f8fafc;
        }
        
        .search-result-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 14px;
        }
        
        .search-result-content {
            flex: 1;
        }
        
        .search-result-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 2px;
            font-size: 14px;
        }
        
        .search-result-meta {
            font-size: 12px;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .search-result-category {
            background: #e2e8f0;
            color: var(--text-dark);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
        }
        
        .search-no-results {
            padding: 16px;
            text-align: center;
            color: var(--text-light);
            font-size: 14px;
        }
        
        .search-loading {
            padding: 16px;
            text-align: center;
            color: var(--text-light);
            font-size: 14px;
        }
        
        .search-loading i {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Mobile Responsive Search */
        @media (max-width: 768px) {
            .mobile-search {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                padding: 10px 15px;
            }
            
            .mobile-search .search-container {
                width: 100%;
                max-width: 500px;
                position: relative;
            }
            
            .mobile-search .search-form {
                display: flex;
                width: 100%;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 25px;
                overflow: hidden;
                backdrop-filter: blur(10px);
            }
            
            .mobile-search .search-input {
                flex: 1;
                width: 100%;
                font-size: 14px;
                padding: 10px 15px;
                height: 40px;
                border: none;
                background: transparent;
                color: #ffffff;
                outline: none;
                transition: all 0.3s ease;
            }
            
            .mobile-search .search-input::placeholder {
                color: rgba(255, 255, 255, 0.7);
            }
            
            .mobile-search .search-input:focus {
                outline: none;
                box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
                background: rgba(255, 255, 255, 0.1);
            }
            
            .mobile-search .search-btn {
                padding: 10px 15px;
                height: 40px;
                border: none;
                background: rgba(255, 255, 255, 0.2);
                color: #ffffff;
                cursor: pointer;
                transition: background 0.3s ease;
                border-radius: 0 25px 25px 0;
            }
            
            .mobile-search .search-btn:hover {
                background: #0056b3;
            }
            
            .search-dropdown {
                position: fixed !important;
                top: 60px !important;
                left: 20px !important;
                right: 20px !important;
                z-index: 9999 !important;
                max-height: 50vh;
                border-radius: 8px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                background: white !important;
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
            }
            
            .search-dropdown.show {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            .search-result-item {
                padding: 12px;
                font-size: 14px;
                cursor: pointer;
                -webkit-tap-highlight-color: rgba(0,0,0,0.1);
            }
            
            .search-result-item:active {
                background-color: #f8f9fa;
            }
            
            .search-result-title {
                font-size: 14px;
                margin-bottom: 3px;
                line-height: 1.2;
            }
            
            .search-result-meta {
                font-size: 12px;
            }
            
            .search-result-icon {
                width: 18px;
                height: 18px;
                font-size: 12px;
            }
        }
        
        @media (max-width: 480px) {
            .mobile-search {
                padding: 8px 10px;
            }
            
            .mobile-search .search-container {
                max-width: 100%;
            }
            
            .mobile-search .search-input {
                font-size: 13px;
                padding: 8px 12px;
                height: 36px;
                background: transparent;
                color: #ffffff;
            }
            
            .mobile-search .search-input::placeholder {
                color: rgba(255, 255, 255, 0.7);
            }
            
            .mobile-search .search-input:focus {
                outline: none;
                box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
                background: rgba(255, 255, 255, 0.1);
            }
            
            .mobile-search .search-btn {
                padding: 8px 12px;
                height: 36px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 0 25px 25px 0;
            }
            
            .search-dropdown {
                left: 15px;
                right: 15px;
                top: 55px;
            }
            
            .search-result-item {
                padding: 10px;
                font-size: 13px;
            }
            
            .search-result-title {
                font-size: 13px;
                margin-bottom: 2px;
                line-height: 1.1;
            }
            
            .search-result-meta {
                font-size: 11px;
            }
            
            .search-result-icon {
                width: 16px;
                height: 16px;
                font-size: 10px;
            }
        }
        
        /* Tablet responsive */
        @media (min-width: 769px) and (max-width: 1024px) {
            .mobile-search {
                display: none;
            }
        }
        
        /* Desktop - hide mobile search */
        @media (min-width: 1025px) {
            .mobile-search {
                display: none;
            }
        }
        
        /* Dropdown placeholder text styling */
        .dropdown-placeholder {
            padding: 12px 16px;
            text-align: center;
            color: #6b7280;
            font-style: italic;
        }
        
        .dropdown-placeholder-text {
            font-size: 0.9rem;
        }
        
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
            
            // Prevent logo text flash by showing logo only when image loads
            const logoImg = new Image();
            logoImg.onload = function() {
                document.documentElement.classList.add('logo-loaded');
            };
            logoImg.src = '<?php echo $base_path; ?>assets/images/Logos/Logo2025.png';
        })();
        
        // Handle logo image loading to prevent alt text flash (no shimmer)
        document.addEventListener('DOMContentLoaded', function() {
            const logoImages = document.querySelectorAll('.intro-logo img, .banner-logo img, .service-image img, .campus-image');
            let loadedCount = 0;
            
            // Handle banner image loading with shimmer effect
            const bannerImages = document.querySelectorAll('.hero-image, .news-slide-image img');
            bannerImages.forEach(function(img, index) {
                // Add loading class to container
                const container = img.closest('.hero-background, .news-slide-image');
                if (container) {
                    container.classList.add('loading');
                }
                
                // Add staggered delay for multiple images
                const delay = index * 100; // 100ms delay between each image
                
                if (img.complete) {
                    setTimeout(() => {
                        img.classList.add('loaded');
                        if (container) {
                            container.classList.remove('loading');
                        }
                        loadedCount++;
                    }, delay);
                } else {
                    img.addEventListener('load', function() {
                        setTimeout(() => {
                            this.classList.add('loaded');
                            if (container) {
                                container.classList.remove('loading');
                            }
                            loadedCount++;
                        }, delay);
                    });
                    img.addEventListener('error', function() {
                        setTimeout(() => {
                            this.classList.add('loaded'); // Show alt text if image fails to load
                            if (container) {
                                container.classList.remove('loading');
                            }
                            loadedCount++;
                        }, delay);
                    });
                }
            });
            
            // Handle background image loading for page-hero and page-header banners
            const bannerSections = document.querySelectorAll('.page-hero, .page-header');
            bannerSections.forEach(function(section) {
                const bgImage = window.getComputedStyle(section).backgroundImage;
                if (bgImage && bgImage !== 'none') {
                    // Extract URL from background-image CSS property
                    const urlMatch = bgImage.match(/url\(['"]?([^'"]+)['"]?\)/);
                    if (urlMatch) {
                        const imageUrl = urlMatch[1];
                        section.classList.add('loading');
                        
                        // Create a new image to preload the background
                        const img = new Image();
                        img.onload = function() {
                            section.classList.remove('loading');
                        };
                        img.onerror = function() {
                            section.classList.remove('loading');
                        };
                        img.src = imageUrl;
                    }
                }
            });
            
            logoImages.forEach(function(img, index) {
                // Add loading class to container (no shimmer for these)
                const container = img.closest('.campus-image-container, .service-image');
                if (container) {
                    container.classList.add('loading');
                }
                
                // Add staggered delay for multiple images
                const delay = index * 100; // 100ms delay between each image
                
                if (img.complete) {
                    setTimeout(() => {
                        img.classList.add('loaded');
                        if (container) {
                            container.classList.remove('loading');
                        }
                        loadedCount++;
                    }, delay);
                } else {
                    img.addEventListener('load', function() {
                        setTimeout(() => {
                            this.classList.add('loaded');
                            if (container) {
                                container.classList.remove('loading');
                            }
                            loadedCount++;
                        }, delay);
                    });
                    img.addEventListener('error', function() {
                        setTimeout(() => {
                            this.classList.add('loaded'); // Show alt text if image fails to load
                            if (container) {
                                container.classList.remove('loading');
                            }
                            loadedCount++;
                        }, delay);
                    });
                }
            });
        });
        
        // Navbar AJAX Search - Global variables
        let navbarSearchTimeout;
        let navbarIsSearching = false;
        let navbarSearchInput;
        let searchDropdown;
        let searchResults;
        
        // Initialize after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            
            // Desktop search elements
            navbarSearchInput = document.getElementById('navbar-search-input');
            searchDropdown = document.getElementById('search-dropdown');
            searchResults = document.getElementById('search-results');
            
            // Mobile search elements
            const mobileSearchInput = document.getElementById('mobile-search-input');
            const mobileSearchDropdown = document.getElementById('mobile-search-dropdown');
            const mobileSearchResults = document.getElementById('mobile-search-results');
            
            
            
            
            if (navbarSearchInput) {
                
                navbarSearchInput.addEventListener('input', function() {
                    const query = this.value.trim();
                    
                    clearTimeout(navbarSearchTimeout);
                    
                    if (query.length < 2) {
                        hideSearchDropdown();
                        return;
                    }
                    
                    navbarSearchTimeout = setTimeout(() => {
                        performNavbarSearch(query);
                    }, 300);
                });
                
                // Hide dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.search-container')) {
                        hideSearchDropdown();
                    }
                });
                
                // Show dropdown on focus if there's a query
                navbarSearchInput.addEventListener('focus', function() {
                    if (this.value.trim().length >= 2) {
                        showSearchDropdown();
                    }
                });
                
                // Mobile touch events
                navbarSearchInput.addEventListener('touchstart', function() {
                    if (this.value.trim().length >= 2) {
                        showSearchDropdown();
                    }
                });
                
                // Show dropdown on click
                navbarSearchInput.addEventListener('click', function() {
                    if (this.value.trim().length >= 2) {
                        showSearchDropdown();
                    }
                });
                
                
            }
            
            // Mobile search functionality
            if (mobileSearchInput) {
                mobileSearchInput.addEventListener('input', function() {
                    const query = this.value.trim();
                    
                    clearTimeout(navbarSearchTimeout);
                    
                    if (query.length < 2) {
                        hideMobileSearchDropdown();
                        return;
                    }
                    
                    navbarSearchTimeout = setTimeout(() => {
                        performMobileSearch(query, mobileSearchDropdown, mobileSearchResults);
                    }, 300);
                });
                
                mobileSearchInput.addEventListener('click', function() {
                    if (this.value.trim().length >= 2) {
                        showMobileSearchDropdown(mobileSearchDropdown);
                    }
                });
            }
            
        });
        
        function performNavbarSearch(query) {
            if (navbarIsSearching) return;
            
            navbarIsSearching = true;
            showSearchDropdown();
            showLoading();
            
            
            fetch(`<?php echo $base_path; ?>ajax-navbar-search.php?q=${encodeURIComponent(query)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        displaySearchResults(data.results);
                    } else {
                        showNoResults();
                    }
                })
                .catch(error => {
                    showNoResults();
                })
                .finally(() => {
                    navbarIsSearching = false;
                });
        }
        
            function showSearchDropdown() {
                if (searchDropdown) {
                    searchDropdown.classList.add('show');
                    
                    // Add test content
                    if (searchResults) {
                        searchResults.innerHTML = '<div class="search-no-results">Search results will appear here</div>';
                    }
                }
            }
            
            function hideSearchDropdown() {
                if (searchDropdown) {
                    searchDropdown.classList.remove('show');
                }
            }
            
            // Mobile search functions
            function showMobileSearchDropdown(dropdown) {
                if (dropdown) {
                    dropdown.classList.add('show');
                }
            }
            
            function hideMobileSearchDropdown() {
                const mobileDropdown = document.getElementById('mobile-search-dropdown');
                if (mobileDropdown) {
                    mobileDropdown.classList.remove('show');
                }
            }
            
            function performMobileSearch(query, dropdown, results) {
                if (navbarIsSearching) return;
                
                navbarIsSearching = true;
                showMobileSearchDropdown(dropdown);
                showMobileLoading(results);
                
                fetch(`<?php echo $base_path; ?>ajax-navbar-search.php?q=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            displayMobileSearchResults(data.results, results);
                        } else {
                            showMobileNoResults(results);
                        }
                    })
                    .catch(error => {
                        showMobileNoResults(results);
                    })
                    .finally(() => {
                        navbarIsSearching = false;
                    });
            }
            
            
            function showLoading() {
                if (searchResults) {
                    searchResults.innerHTML = '<div class="search-loading"><i class="fas fa-spinner"></i> Searching...</div>';
                }
            }
            
            function displaySearchResults(results) {
                if (!searchResults) return;
                
                if (results.length === 0) {
                    showNoResults();
                    return;
                }
                
                let html = '';
                results.forEach(result => {
                    let icon = 'fas fa-file-alt';
                    if (result.type === 'post') {
                        icon = 'fas fa-newspaper';
                    } else if (result.type === 'career') {
                        icon = 'fas fa-briefcase';
                    } else if (result.type === 'external') {
                        icon = 'fas fa-external-link-alt';
                    }
                    
                    const date = result.date ? ` • ${result.date}` : '';
                    
                    html += `
                        <div class="search-result-item" onclick="window.location.href='${result.url}'" ontouchend="window.location.href='${result.url}'">
                            <div class="search-result-icon">
                                <i class="${icon}"></i>
                            </div>
                            <div class="search-result-content">
                                <div class="search-result-title">${result.title}</div>
                                <div class="search-result-meta">
                                    <span class="search-result-category">${result.category}</span>
                                    ${date}
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                searchResults.innerHTML = html;
            }
            
            function showNoResults() {
                if (searchResults) {
                    searchResults.innerHTML = '<div class="search-no-results">No results found</div>';
                }
            }
            
            // Mobile search helper functions
            function showMobileLoading(results) {
                if (results) {
                    results.innerHTML = '<div class="search-loading"><i class="fas fa-spinner"></i> Searching...</div>';
                }
            }
            
            function displayMobileSearchResults(results, resultsElement) {
                if (!resultsElement) return;
                
                if (results.length === 0) {
                    showMobileNoResults(resultsElement);
                    return;
                }
                
                let html = '';
                results.forEach(result => {
                    let icon = 'fas fa-file-alt';
                    if (result.type === 'post') {
                        icon = 'fas fa-newspaper';
                    } else if (result.type === 'career') {
                        icon = 'fas fa-briefcase';
                    } else if (result.type === 'external') {
                        icon = 'fas fa-external-link-alt';
                    }
                    
                    const date = result.date ? ` • ${result.date}` : '';
                    
                    html += `
                        <div class="search-result-item" onclick="window.location.href='${result.url}'" ontouchend="window.location.href='${result.url}'">
                            <div class="search-result-icon">
                                <i class="${icon}"></i>
                            </div>
                            <div class="search-result-content">
                                <div class="search-result-title">${result.title}</div>
                                <div class="search-result-meta">
                                    <span class="search-result-category">${result.category}</span>
                                    ${date}
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                resultsElement.innerHTML = html;
            }
            
            function showMobileNoResults(results) {
                if (results) {
                    results.innerHTML = '<div class="search-no-results">No results found</div>';
                }
            }
            
        
    </script>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- First Column: Logo -->
            <div class="nav-logo">
        <a href="<?php echo $base_path; ?>">
            <img src="<?php echo $base_path; ?>assets/images/Logos/Logo2025.png" alt="University of Perpetual Help System" class="logo-img">
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
                        <div class="search-container">
                            <form class="search-form" action="<?php echo isset($base_path) ? $base_path : ''; ?>search.php" method="GET">
                                <input type="text" name="q" placeholder="Search..." class="search-input" id="navbar-search-input" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                                <button type="submit" class="search-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                            <div class="search-dropdown" id="search-dropdown">
                                <div class="search-results" id="search-results">
                                    <!-- AJAX results will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Search Bar (separate from nav-header) -->
                <div class="nav-search mobile-search">
                    <div class="search-container">
                        <form class="search-form" action="<?php echo isset($base_path) ? $base_path : ''; ?>search.php" method="GET">
                            <input type="text" name="q" placeholder="Search..." class="search-input" id="mobile-search-input" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        <div class="search-dropdown" id="mobile-search-dropdown">
                            <div class="search-results" id="mobile-search-results">
                                <!-- AJAX results will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Menu Toggle Button -->
                <button class="mobile-menu-toggle" id="mobile-menu-toggle">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
                
                <!-- Second Row: Main Menu -->
                <div class="nav-menu" id="nav-menu">
                    <?php if (isNavbarItemVisible('home')): ?>
                    <div class="nav-item">
                        <a href="<?php echo $base_path; ?>" class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>">Home</a>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isNavbarItemVisible('programs')): ?>
                    <div class="nav-item dropdown">
                        <a href="<?php echo $base_path; ?>programs.php" class="nav-link dropdown-toggle <?php 
                            $is_programs_active = ($current_page == 'programs' || strpos($current_page, 'programs') !== false || in_array($current_page, ['senior-high-school', 'junior-high-school', 'grade-school', 'aviation', 'arts-sciences', 'business-accountancy', 'computer-studies', 'criminology', 'education', 'engineering-architecture', 'hospitality-management', 'maritime', 'law', 'graduate-school']));
                            echo $is_programs_active ? 'active' : '';
                        ?>" style="<?php echo $is_programs_active ? 'background: rgba(255, 198, 62, 0.2) !important; color: #ffc63e !important;' : ''; ?>">Programs <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <?php 
                            $programs_subitems = isset($navbar_items_config['programs']) ? $navbar_items_config['programs'] : [];
                            if (areAllNavbarSubItemsDisabled('programs', $programs_subitems)): ?>
                                <div class="dropdown-placeholder">
                                    <span class="dropdown-placeholder-text">No programs available at this time.</span>
                                </div>
                            <?php else: ?>
                                <?php if (isNavbarItemVisible('programs', 'basic-education')): ?>
                                <div class="dropdown-item-with-submenu">
                                    <a href="#" class="dropdown-link dropdown-parent">Basic Education <i class="fas fa-chevron-down submenu-chevron"></i></a>
                                    <div class="submenu-dropdown">
                                        <?php if (isNavbarItemVisible('programs', 'senior-high-school')): ?>
                                        <a href="<?php echo $base_path; ?>programs/senior-high-school.php" class="submenu-link <?php echo ($current_page == 'senior-high-school') ? 'active' : ''; ?>">Senior High School</a>
                                        <?php endif; ?>
                                        <?php if (isNavbarItemVisible('programs', 'junior-high-school')): ?>
                                        <a href="<?php echo $base_path; ?>programs/junior-high-school.php" class="submenu-link <?php echo ($current_page == 'junior-high-school') ? 'active' : ''; ?>">Junior High School</a>
                                        <?php endif; ?>
                                        <?php if (isNavbarItemVisible('programs', 'grade-school')): ?>
                                        <a href="<?php echo $base_path; ?>programs/grade-school.php" class="submenu-link <?php echo ($current_page == 'grade-school') ? 'active' : ''; ?>">Grade School</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('programs', 'aviation')): ?>
                                <a href="<?php echo $base_path; ?>programs/aviation.php" class="dropdown-link <?php echo ($current_page == 'aviation') ? 'active' : ''; ?>">Aviation</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('programs', 'arts-sciences')): ?>
                                <a href="<?php echo $base_path; ?>programs/arts-sciences.php" class="dropdown-link <?php echo ($current_page == 'arts-sciences') ? 'active' : ''; ?>">Arts & Sciences</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('programs', 'business-accountancy')): ?>
                                <a href="<?php echo $base_path; ?>programs/business-accountancy.php" class="dropdown-link <?php echo ($current_page == 'business-accountancy') ? 'active' : ''; ?>">Business & Accountancy</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('programs', 'computer-studies')): ?>
                                <a href="<?php echo $base_path; ?>programs/computer-studies.php" class="dropdown-link <?php echo ($current_page == 'computer-studies') ? 'active' : ''; ?>">Computer Studies</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('programs', 'criminology')): ?>
                                <a href="<?php echo $base_path; ?>programs/criminology.php" class="dropdown-link <?php echo ($current_page == 'criminology') ? 'active' : ''; ?>">Criminology</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('programs', 'education')): ?>
                                <a href="<?php echo $base_path; ?>programs/education.php" class="dropdown-link <?php echo ($current_page == 'education') ? 'active' : ''; ?>">Education</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('programs', 'engineering-architecture')): ?>
                                <a href="<?php echo $base_path; ?>programs/engineering-architecture.php" class="dropdown-link <?php echo ($current_page == 'engineering-architecture') ? 'active' : ''; ?>">Engineering & Architecture</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('programs', 'hospitality-management')): ?>
                                <a href="<?php echo $base_path; ?>programs/hospitality-management.php" class="dropdown-link <?php echo ($current_page == 'hospitality-management') ? 'active' : ''; ?>">International Hospitality Management</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('programs', 'maritime')): ?>
                                <a href="<?php echo $base_path; ?>programs/maritime.php" class="dropdown-link <?php echo ($current_page == 'maritime') ? 'active' : ''; ?>">Maritime</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('programs', 'law')): ?>
                                <a href="<?php echo $base_path; ?>programs/law.php" class="dropdown-link <?php echo ($current_page == 'law') ? 'active' : ''; ?>">Law/Juris Doctor</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('programs', 'graduate-school')): ?>
                                <a href="<?php echo $base_path; ?>programs/graduate-school.php" class="dropdown-link <?php echo ($current_page == 'graduate-school') ? 'active' : ''; ?>">Graduate School</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isNavbarItemVisible('online-services')): ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?php echo ($current_page == 'ols_instructions') ? 'active' : ''; ?>">Online Services <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <?php 
                            $online_services_subitems = isset($navbar_items_config['online-services']) ? $navbar_items_config['online-services'] : [];
                            if (areAllNavbarSubItemsDisabled('online-services', $online_services_subitems)): ?>
                                <div class="dropdown-placeholder">
                                    <span class="dropdown-placeholder-text">No online services available at this time.</span>
                                </div>
                            <?php else: ?>
                                <?php if (isNavbarItemVisible('online-services', 'instructions')): ?>
                                <a href="<?php echo $base_path; ?>ols_instructions.php" class="dropdown-link <?php echo ($current_page == 'ols_instructions') ? 'active' : ''; ?>">Instructions</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('online-services', 'gti-online-grades')): ?>
                                <a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="dropdown-link">GTI Online Grades</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('online-services', 'moodle')): ?>
                                <a href="https://uphslms.com/" target="_blank" class="dropdown-link">Moodle</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('online-services', 'google-account')): ?>
                                <a href="https://accounts.google.com/signin" target="_blank" class="dropdown-link">Google Account</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('online-services', 'microsoft-365')): ?>
                                <a href="https://login.microsoftonline.com/" target="_blank" class="dropdown-link">Microsoft 365</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('online-services', 'saliksik')): ?>
                                <a href="https://saliksikuphsl.org/" target="_blank" class="dropdown-link">Saliksik</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isNavbarItemVisible('support-services')): ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?php echo (in_array($current_page, ['support-services', 'careers', 'clinic', 'cod', 'iea', 'sps', 'library', 'quality-assurance', 'research'])) ? 'active' : ''; ?>">Support Services <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <?php 
                            $support_services_subitems = isset($navbar_items_config['support-services']) ? $navbar_items_config['support-services'] : [];
                            if (areAllNavbarSubItemsDisabled('support-services', $support_services_subitems)): ?>
                                <div class="dropdown-placeholder">
                                    <span class="dropdown-placeholder-text">No support services available at this time.</span>
                                </div>
                            <?php else: ?>
                                <?php if (isNavbarItemVisible('support-services', 'alumni')): ?>
                                <a href="https://docs.google.com/forms/d/e/1FAIpQLSea8-O2OuuKWgZ17XgKkyLQ7dDOawW31a8vq1nTWDRREODVMQ/viewform" target="_blank" class="dropdown-link">Alumni</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('support-services', 'careers')): ?>
                                <a href="<?php echo $base_path; ?>support-services/careers.php" class="dropdown-link <?php echo ($current_page == 'careers') ? 'active' : ''; ?>">Careers</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('support-services', 'clinic')): ?>
                                <a href="<?php echo $base_path; ?>support-services/clinic.php" class="dropdown-link <?php echo ($current_page == 'clinic') ? 'active' : ''; ?>">University Clinic</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('support-services', 'cod')): ?>
                                <a href="<?php echo $base_path; ?>support-services/cod.php" class="dropdown-link <?php echo ($current_page == 'cod') ? 'active' : ''; ?>">Community Outreach Department</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('support-services', 'iea')): ?>
                                <a href="<?php echo $base_path; ?>support-services/iea.php" class="dropdown-link <?php echo ($current_page == 'iea') ? 'active' : ''; ?>">International & External Affairs</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('support-services', 'sps')): ?>
                                <a href="<?php echo $base_path; ?>support-services/sps.php" class="dropdown-link <?php echo ($current_page == 'sps') ? 'active' : ''; ?>">Student Personnel Services</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('support-services', 'library')): ?>
                                <a href="<?php echo $base_path; ?>support-services/library.php" class="dropdown-link <?php echo ($current_page == 'library') ? 'active' : ''; ?>">Library</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('support-services', 'quality-assurance')): ?>
                                <a href="<?php echo $base_path; ?>support-services/quality-assurance.php" class="dropdown-link <?php echo ($current_page == 'quality-assurance') ? 'active' : ''; ?>">Quality Assurance</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('support-services', 'research')): ?>
                                <a href="<?php echo $base_path; ?>support-services/research.php" class="dropdown-link <?php echo ($current_page == 'research') ? 'active' : ''; ?>">Research</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isNavbarItemVisible('campuses')): ?>
                    <div class="nav-item">
                        <a href="<?php echo $base_path; ?>campuses.php" class="nav-link <?php echo ($current_page == 'campuses') ? 'active' : ''; ?>">Campuses</a>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isNavbarItemVisible('about')): ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?php echo (in_array($current_page, ['about', 'contact', 'environmental-policy', 'university-policy', 'map'])) ? 'active' : ''; ?>">About <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <?php 
                            $about_subitems = isset($navbar_items_config['about']) ? $navbar_items_config['about'] : [];
                            if (areAllNavbarSubItemsDisabled('about', $about_subitems)): ?>
                                <div class="dropdown-placeholder">
                                    <span class="dropdown-placeholder-text">No about pages available at this time.</span>
                                </div>
                            <?php else: ?>
                                <?php if (isNavbarItemVisible('about', 'about-us')): ?>
                                <a href="<?php echo $base_path; ?>about" class="dropdown-link <?php echo ($current_page == 'about') ? 'active' : ''; ?>">About Us</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('about', 'contact')): ?>
                                <a href="<?php echo $base_path; ?>about/contact.php" class="dropdown-link <?php echo ($current_page == 'contact') ? 'active' : ''; ?>">Contact Us</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('about', 'environmental-policy')): ?>
                                <a href="<?php echo $base_path; ?>about/environmental-policy.php" class="dropdown-link <?php echo ($current_page == 'environmental-policy') ? 'active' : ''; ?>">Environmental Policy</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('about', 'university-policy')): ?>
                                <a href="<?php echo $base_path; ?>about/university-policy.php" class="dropdown-link <?php echo ($current_page == 'university-policy') ? 'active' : ''; ?>">University Policy</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('about', 'map')): ?>
                                <a href="<?php echo $base_path; ?>about/map.php" class="dropdown-link <?php echo ($current_page == 'map') ? 'active' : ''; ?>">Map</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isNavbarItemVisible('online-payment')): ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Online Payment <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <?php 
                            $online_payment_subitems = isset($navbar_items_config['online-payment']) ? $navbar_items_config['online-payment'] : [];
                            if (areAllNavbarSubItemsDisabled('online-payment', $online_payment_subitems)): ?>
                                <div class="dropdown-placeholder">
                                    <span class="dropdown-placeholder-text">No payment options available at this time.</span>
                                </div>
                            <?php else: ?>
                                <?php if (isNavbarItemVisible('online-payment', 'entrance-exam')): ?>
                                <a href="https://uphsl.edu.ph/online_payment/guest_exam" target="_blank" class="dropdown-link">Entrance Exam</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('online-payment', 'new-enrollees')): ?>
                                <a href="https://uphsl.edu.ph/online_payment/guest" target="_blank" class="dropdown-link">New Enrollees</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('online-payment', 'enrolled-students')): ?>
                                <a href="https://uphsl.edu.ph/online_payment/guestold_student" target="_blank" class="dropdown-link">Enrolled Students</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('online-payment', 'other-payments')): ?>
                                <a href="https://uphsl.edu.ph/online_payment/guestold" target="_blank" class="dropdown-link">Other Payments</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isNavbarItemVisible('calendar')): ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?php echo (in_array($current_page, ['college-academic-calendar', 'bed-shs-academic-calendar'])) ? 'active' : ''; ?>">Calendar <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <?php 
                            $calendar_subitems = isset($navbar_items_config['calendar']) ? $navbar_items_config['calendar'] : [];
                            if (areAllNavbarSubItemsDisabled('calendar', $calendar_subitems)): ?>
                                <div class="dropdown-placeholder">
                                    <span class="dropdown-placeholder-text">No calendars available at this time.</span>
                                </div>
                            <?php else: ?>
                                <?php if (isNavbarItemVisible('calendar', 'college-academic-calendar')): ?>
                                <a href="<?php echo $base_path; ?>calendar/college-academic-calendar.php" class="dropdown-link <?php echo ($current_page == 'college-academic-calendar') ? 'active' : ''; ?>">College Academic Calendar</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('calendar', 'bed-shs-academic-calendar')): ?>
                                <a href="<?php echo $base_path; ?>calendar/bed-shs-academic-calendar.php" class="dropdown-link <?php echo ($current_page == 'bed-shs-academic-calendar') ? 'active' : ''; ?>">BED & SHS Academic Calendar</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isNavbarItemVisible('enrollment')): ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Enrollment <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu">
                            <?php 
                            $enrollment_subitems = isset($navbar_items_config['enrollment']) ? $navbar_items_config['enrollment'] : [];
                            if (areAllNavbarSubItemsDisabled('enrollment', $enrollment_subitems)): ?>
                                <div class="dropdown-placeholder">
                                    <span class="dropdown-placeholder-text">No enrollment options available at this time.</span>
                                </div>
                            <?php else: ?>
                                <?php if (isNavbarItemVisible('enrollment', 'enrollment-college')): ?>
                                <a href="https://docs.google.com/forms/d/e/1FAIpQLSfuxQtL77zIZ13Zqzk951FiIrSpGApccIFyp_Gr6faD1vtVng/closedform" class="dropdown-link" onclick="return false;">Enrollment for College & Graduate School & Juris Doctor</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('enrollment', 'enrollment-shs')): ?>
                                <a href="https://docs.google.com/forms/d/e/1FAIpQLSfh2CKtB6Nmz0CeDvWKaTETuNCbaFiZiuo2UdQ0u5t4zJtgvQ/closedform" class="dropdown-link" onclick="return false;">Enrollment for Senior High School</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php
                    // SDG Titles for navigation
                    $sdgTitles = [
                        1 => 'No Poverty',
                        2 => 'Zero Hunger',
                        3 => 'Good Health and Well-being',
                        4 => 'Quality Education',
                        5 => 'Gender Equality',
                        6 => 'Clean Water and Sanitation',
                        7 => 'Affordable and Clean Energy',
                        8 => 'Decent Work and Economic Growth',
                        9 => 'Industry, Innovation and Infrastructure',
                        10 => 'Reduced Inequalities',
                        11 => 'Sustainable Cities and Communities',
                        12 => 'Responsible Consumption and Production',
                        13 => 'Climate Action',
                        14 => 'Life Below Water',
                        15 => 'Life on Land',
                        16 => 'Peace, Justice and Strong Institutions',
                        17 => 'Partnerships for the Goals'
                    ];
                    ?>
                    <?php if (isNavbarItemVisible('sdg-initiatives')): ?>
                    <div class="nav-item dropdown">
                        <a href="<?php echo $base_path; ?>sdg-initiatives.php" class="nav-link dropdown-toggle <?php echo ($current_page == 'sdg-initiatives') ? 'active' : ''; ?>">SDG Initiatives <i class="fas fa-chevron-down desktop-chevron"></i></a>
                        <div class="dropdown-menu sdg-dropdown-menu">
                            <?php 
                            $sdg_subitems = isset($navbar_items_config['sdg-initiatives']) ? $navbar_items_config['sdg-initiatives'] : [];
                            if (areAllNavbarSubItemsDisabled('sdg-initiatives', $sdg_subitems)): ?>
                                <div class="dropdown-placeholder">
                                    <span class="dropdown-placeholder-text">No SDG initiatives available at this time.</span>
                                </div>
                            <?php else: ?>
                                <?php for ($i = 1; $i <= 17; $i++): ?>
                                    <?php if (isNavbarItemVisible('sdg-initiatives', 'sdg-' . $i)): ?>
                                    <a href="<?php echo $base_path; ?>sdg-initiatives.php?sdg=<?php echo $i; ?>" class="dropdown-link">SDG <?php echo $i; ?>: <?php echo $sdgTitles[$i]; ?></a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <?php if (isNavbarItemVisible('sdg-initiatives', 'sdg-full-report')): ?>
                                <a href="<?php echo $base_path; ?>sdg-initiatives.php?sdg=report" class="dropdown-link">SDG Full Report</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
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
                <img src="<?php echo $base_path; ?>assets/images/Logos/Logo2025.png" alt="University of Perpetual Help System" class="mobile-logo-img">
                <h2 class="mobile-site-name">UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA</h2>
            </div>
            <button class="mobile-sidebar-close" id="mobile-sidebar-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        
        <nav class="mobile-sidebar-menu">
            <?php if (isNavbarItemVisible('home')): ?>
            <div class="mobile-nav-item">
                <a href="<?php echo $base_path; ?>" class="mobile-nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>">Home</a>
            </div>
            <?php endif; ?>
            
            <?php if (isNavbarItemVisible('programs')): ?>
            <div class="mobile-nav-item mobile-dropdown">
                <a href="<?php echo $base_path; ?>programs.php" class="mobile-nav-link mobile-dropdown-toggle <?php echo ($current_page == 'programs' || strpos($current_page, 'programs') !== false || in_array($current_page, ['senior-high-school', 'junior-high-school', 'grade-school', 'aviation', 'arts-sciences', 'business-accountancy', 'computer-studies', 'criminology', 'education', 'engineering-architecture', 'hospitality-management', 'maritime', 'law', 'graduate-school'])) ? 'active' : ''; ?>">Programs <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <?php 
                    $programs_subitems = isset($navbar_items_config['programs']) ? $navbar_items_config['programs'] : [];
                    if (areAllNavbarSubItemsDisabled('programs', $programs_subitems)): ?>
                        <div class="dropdown-placeholder">
                            <span class="dropdown-placeholder-text">No programs available at this time.</span>
                        </div>
                    <?php else: ?>
                        <?php if (isNavbarItemVisible('programs', 'basic-education')): ?>
                        <div class="mobile-dropdown-item-with-submenu">
                            <a href="#" class="mobile-dropdown-link mobile-dropdown-parent">Basic Education <i class="fas fa-chevron-right mobile-submenu-chevron"></i></a>
                            <div class="mobile-submenu-dropdown">
                                <?php if (isNavbarItemVisible('programs', 'senior-high-school')): ?>
                                <a href="<?php echo $base_path; ?>programs/senior-high-school.php" class="mobile-submenu-link <?php echo ($current_page == 'senior-high-school') ? 'active' : ''; ?>">Senior High School</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('programs', 'junior-high-school')): ?>
                                <a href="<?php echo $base_path; ?>programs/junior-high-school.php" class="mobile-submenu-link <?php echo ($current_page == 'junior-high-school') ? 'active' : ''; ?>">Junior High School</a>
                                <?php endif; ?>
                                <?php if (isNavbarItemVisible('programs', 'grade-school')): ?>
                                <a href="<?php echo $base_path; ?>programs/grade-school.php" class="mobile-submenu-link <?php echo ($current_page == 'grade-school') ? 'active' : ''; ?>">Grade School</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('programs', 'aviation')): ?>
                        <a href="<?php echo $base_path; ?>programs/aviation.php" class="mobile-dropdown-link <?php echo ($current_page == 'aviation') ? 'active' : ''; ?>">Aviation</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('programs', 'arts-sciences')): ?>
                        <a href="<?php echo $base_path; ?>programs/arts-sciences.php" class="mobile-dropdown-link <?php echo ($current_page == 'arts-sciences') ? 'active' : ''; ?>">Arts & Sciences</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('programs', 'business-accountancy')): ?>
                        <a href="<?php echo $base_path; ?>programs/business-accountancy.php" class="mobile-dropdown-link <?php echo ($current_page == 'business-accountancy') ? 'active' : ''; ?>">Business & Accountancy</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('programs', 'computer-studies')): ?>
                        <a href="<?php echo $base_path; ?>programs/computer-studies.php" class="mobile-dropdown-link <?php echo ($current_page == 'computer-studies') ? 'active' : ''; ?>">Computer Studies</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('programs', 'criminology')): ?>
                        <a href="<?php echo $base_path; ?>programs/criminology.php" class="mobile-dropdown-link <?php echo ($current_page == 'criminology') ? 'active' : ''; ?>">Criminology</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('programs', 'education')): ?>
                        <a href="<?php echo $base_path; ?>programs/education.php" class="mobile-dropdown-link <?php echo ($current_page == 'education') ? 'active' : ''; ?>">Education</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('programs', 'engineering-architecture')): ?>
                        <a href="<?php echo $base_path; ?>programs/engineering-architecture.php" class="mobile-dropdown-link <?php echo ($current_page == 'engineering-architecture') ? 'active' : ''; ?>">Engineering & Architecture</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('programs', 'hospitality-management')): ?>
                        <a href="<?php echo $base_path; ?>programs/hospitality-management.php" class="mobile-dropdown-link <?php echo ($current_page == 'hospitality-management') ? 'active' : ''; ?>">International Hospitality Management</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('programs', 'maritime')): ?>
                        <a href="<?php echo $base_path; ?>programs/maritime.php" class="mobile-dropdown-link <?php echo ($current_page == 'maritime') ? 'active' : ''; ?>">Maritime</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('programs', 'law')): ?>
                        <a href="<?php echo $base_path; ?>programs/law.php" class="mobile-dropdown-link <?php echo ($current_page == 'law') ? 'active' : ''; ?>">Law/Juris Doctor</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('programs', 'graduate-school')): ?>
                        <a href="<?php echo $base_path; ?>programs/graduate-school.php" class="mobile-dropdown-link <?php echo ($current_page == 'graduate-school') ? 'active' : ''; ?>">Graduate School</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isNavbarItemVisible('online-services')): ?>
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle <?php echo ($current_page == 'ols_instructions') ? 'active' : ''; ?>">Online Services <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <?php 
                    $online_services_subitems = isset($navbar_items_config['online-services']) ? $navbar_items_config['online-services'] : [];
                    if (areAllNavbarSubItemsDisabled('online-services', $online_services_subitems)): ?>
                        <div class="dropdown-placeholder">
                            <span class="dropdown-placeholder-text">No online services available at this time.</span>
                        </div>
                    <?php else: ?>
                        <?php if (isNavbarItemVisible('online-services', 'instructions')): ?>
                        <a href="<?php echo $base_path; ?>ols_instructions.php" class="mobile-dropdown-link <?php echo ($current_page == 'ols_instructions') ? 'active' : ''; ?>">Instructions</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('online-services', 'gti-online-grades')): ?>
                        <a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="mobile-dropdown-link">GTI Online Grades</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('online-services', 'moodle')): ?>
                        <a href="https://uphslms.com/blended/login/index.php" target="_blank" class="mobile-dropdown-link">Moodle</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('online-services', 'google-account')): ?>
                        <a href="https://accounts.google.com/signin" target="_blank" class="mobile-dropdown-link">Google Account</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('online-services', 'microsoft-365')): ?>
                        <a href="https://login.microsoftonline.com/" target="_blank" class="mobile-dropdown-link">Microsoft 365</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('online-services', 'saliksik')): ?>
                        <a href="https://saliksikuphsl.org/" target="_blank" class="mobile-dropdown-link">Saliksik</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isNavbarItemVisible('support-services')): ?>
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle <?php echo (in_array($current_page, ['support-services', 'careers', 'clinic', 'cod', 'iea', 'sps', 'library', 'quality-assurance', 'research'])) ? 'active' : ''; ?>">Support Services <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <?php 
                    $support_services_subitems = isset($navbar_items_config['support-services']) ? $navbar_items_config['support-services'] : [];
                    if (areAllNavbarSubItemsDisabled('support-services', $support_services_subitems)): ?>
                        <div class="dropdown-placeholder">
                            <span class="dropdown-placeholder-text">No support services available at this time.</span>
                        </div>
                    <?php else: ?>
                        <?php if (isNavbarItemVisible('support-services', 'alumni')): ?>
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSea8-O2OuuKWgZ17XgKkyLQ7dDOawW31a8vq1nTWDRREODVMQ/viewform" target="_blank" class="mobile-dropdown-link">Alumni</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('support-services', 'careers')): ?>
                        <a href="<?php echo $base_path; ?>support-services/careers.php" class="mobile-dropdown-link <?php echo ($current_page == 'careers') ? 'active' : ''; ?>">Careers</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('support-services', 'clinic')): ?>
                        <a href="<?php echo $base_path; ?>support-services/clinic.php" class="mobile-dropdown-link <?php echo ($current_page == 'clinic') ? 'active' : ''; ?>">University Clinic</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('support-services', 'cod')): ?>
                        <a href="<?php echo $base_path; ?>support-services/cod.php" class="mobile-dropdown-link <?php echo ($current_page == 'cod') ? 'active' : ''; ?>">Community Outreach Department</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('support-services', 'iea')): ?>
                        <a href="<?php echo $base_path; ?>support-services/iea.php" class="mobile-dropdown-link <?php echo ($current_page == 'iea') ? 'active' : ''; ?>">International & External Affairs</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('support-services', 'sps')): ?>
                        <a href="<?php echo $base_path; ?>support-services/sps.php" class="mobile-dropdown-link <?php echo ($current_page == 'sps') ? 'active' : ''; ?>">Student Personnel Services</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('support-services', 'library')): ?>
                        <a href="<?php echo $base_path; ?>support-services/library.php" class="mobile-dropdown-link <?php echo ($current_page == 'library') ? 'active' : ''; ?>">Library</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('support-services', 'quality-assurance')): ?>
                        <a href="<?php echo $base_path; ?>support-services/quality-assurance.php" class="mobile-dropdown-link <?php echo ($current_page == 'quality-assurance') ? 'active' : ''; ?>">Quality Assurance</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('support-services', 'research')): ?>
                        <a href="<?php echo $base_path; ?>support-services/research.php" class="mobile-dropdown-link <?php echo ($current_page == 'research') ? 'active' : ''; ?>">Research</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isNavbarItemVisible('campuses')): ?>
            <div class="mobile-nav-item">
                <a href="<?php echo $base_path; ?>campuses.php" class="mobile-nav-link <?php echo ($current_page == 'campuses') ? 'active' : ''; ?>">Campuses</a>
            </div>
            <?php endif; ?>
            
            <?php if (isNavbarItemVisible('about')): ?>
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle <?php echo (in_array($current_page, ['about', 'contact', 'environmental-policy', 'university-policy', 'map'])) ? 'active' : ''; ?>">About <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <?php 
                    $about_subitems = isset($navbar_items_config['about']) ? $navbar_items_config['about'] : [];
                    if (areAllNavbarSubItemsDisabled('about', $about_subitems)): ?>
                        <div class="dropdown-placeholder">
                            <span class="dropdown-placeholder-text">No about pages available at this time.</span>
                        </div>
                    <?php else: ?>
                        <?php if (isNavbarItemVisible('about', 'about-us')): ?>
                        <a href="<?php echo $base_path; ?>about" class="mobile-dropdown-link <?php echo ($current_page == 'about') ? 'active' : ''; ?>">About Us</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('about', 'contact')): ?>
                        <a href="<?php echo $base_path; ?>about/contact.php" class="mobile-dropdown-link <?php echo ($current_page == 'contact') ? 'active' : ''; ?>">Contact Us</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('about', 'environmental-policy')): ?>
                        <a href="<?php echo $base_path; ?>about/environmental-policy.php" class="mobile-dropdown-link <?php echo ($current_page == 'environmental-policy') ? 'active' : ''; ?>">Environmental Policy</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('about', 'university-policy')): ?>
                        <a href="<?php echo $base_path; ?>about/university-policy.php" class="mobile-dropdown-link <?php echo ($current_page == 'university-policy') ? 'active' : ''; ?>">University Policy</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('about', 'map')): ?>
                        <a href="<?php echo $base_path; ?>about/map.php" class="mobile-dropdown-link <?php echo ($current_page == 'map') ? 'active' : ''; ?>">Map</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isNavbarItemVisible('online-payment')): ?>
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Online Payment <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <?php 
                    $online_payment_subitems = isset($navbar_items_config['online-payment']) ? $navbar_items_config['online-payment'] : [];
                    if (areAllNavbarSubItemsDisabled('online-payment', $online_payment_subitems)): ?>
                        <div class="dropdown-placeholder">
                            <span class="dropdown-placeholder-text">No payment options available at this time.</span>
                        </div>
                    <?php else: ?>
                        <?php if (isNavbarItemVisible('online-payment', 'entrance-exam')): ?>
                        <a href="https://uphsl.edu.ph/online_payment/guest_exam" target="_blank" class="mobile-dropdown-link">Entrance Exam</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('online-payment', 'new-enrollees')): ?>
                        <a href="https://uphsl.edu.ph/online_payment/guest" target="_blank" class="mobile-dropdown-link">New Enrollees</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('online-payment', 'enrolled-students')): ?>
                        <a href="https://uphsl.edu.ph/online_payment/guestold_student" target="_blank" class="mobile-dropdown-link">Enrolled Students</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('online-payment', 'other-payments')): ?>
                        <a href="https://uphsl.edu.ph/online_payment/guestold" target="_blank" class="mobile-dropdown-link">Other Payments</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isNavbarItemVisible('calendar')): ?>
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle <?php echo (in_array($current_page, ['college-academic-calendar', 'bed-shs-academic-calendar'])) ? 'active' : ''; ?>">Calendar <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <?php 
                    $calendar_subitems = isset($navbar_items_config['calendar']) ? $navbar_items_config['calendar'] : [];
                    if (areAllNavbarSubItemsDisabled('calendar', $calendar_subitems)): ?>
                        <div class="dropdown-placeholder">
                            <span class="dropdown-placeholder-text">No calendars available at this time.</span>
                        </div>
                    <?php else: ?>
                        <?php if (isNavbarItemVisible('calendar', 'college-academic-calendar')): ?>
                        <a href="<?php echo $base_path; ?>calendar/college-academic-calendar.php" class="mobile-dropdown-link <?php echo ($current_page == 'college-academic-calendar') ? 'active' : ''; ?>">College Academic Calendar</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('calendar', 'bed-shs-academic-calendar')): ?>
                        <a href="<?php echo $base_path; ?>calendar/bed-shs-academic-calendar.php" class="mobile-dropdown-link <?php echo ($current_page == 'bed-shs-academic-calendar') ? 'active' : ''; ?>">BED & SHS Academic Calendar</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isNavbarItemVisible('enrollment')): ?>
            <div class="mobile-nav-item mobile-dropdown">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Enrollment <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <?php 
                    $enrollment_subitems = isset($navbar_items_config['enrollment']) ? $navbar_items_config['enrollment'] : [];
                    if (areAllNavbarSubItemsDisabled('enrollment', $enrollment_subitems)): ?>
                        <div class="dropdown-placeholder">
                            <span class="dropdown-placeholder-text">No enrollment options available at this time.</span>
                        </div>
                    <?php else: ?>
                        <?php if (isNavbarItemVisible('enrollment', 'enrollment-college')): ?>
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSfuxQtL77zIZ13Zqzk951FiIrSpGApccIFyp_Gr6faD1vtVng/closedform" class="mobile-dropdown-link" onclick="return false;">Enrollment for College & Graduate School & Juris Doctor</a>
                        <?php endif; ?>
                        <?php if (isNavbarItemVisible('enrollment', 'enrollment-shs')): ?>
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSfh2CKtB6Nmz0CeDvWKaTETuNCbaFiZiuo2UdQ0u5t4zJtgvQ/closedform" class="mobile-dropdown-link" onclick="return false;">Enrollment for Senior High School</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isNavbarItemVisible('sdg-initiatives')): ?>
            <div class="mobile-nav-item mobile-dropdown">
                <a href="<?php echo $base_path; ?>sdg-initiatives.php" class="mobile-nav-link mobile-dropdown-toggle <?php echo ($current_page == 'sdg-initiatives') ? 'active' : ''; ?>">SDG Initiatives <i class="fas fa-chevron-down mobile-chevron"></i></a>
                <div class="mobile-dropdown-menu">
                    <?php 
                    $sdg_subitems = isset($navbar_items_config['sdg-initiatives']) ? $navbar_items_config['sdg-initiatives'] : [];
                    if (areAllNavbarSubItemsDisabled('sdg-initiatives', $sdg_subitems)): ?>
                        <div class="dropdown-placeholder">
                            <span class="dropdown-placeholder-text">No SDG initiatives available at this time.</span>
                        </div>
                    <?php else: ?>
                        <?php
                        for ($i = 1; $i <= 17; $i++): ?>
                            <?php if (isNavbarItemVisible('sdg-initiatives', 'sdg-' . $i)): ?>
                            <a href="<?php echo $base_path; ?>sdg-initiatives.php?sdg=<?php echo $i; ?>" class="mobile-dropdown-link">SDG <?php echo $i; ?>: <?php echo $sdgTitles[$i]; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <?php if (isNavbarItemVisible('sdg-initiatives', 'sdg-full-report')): ?>
                        <a href="<?php echo $base_path; ?>sdg-initiatives.php?sdg=report" class="mobile-dropdown-link">SDG Full Report</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
        </nav>
    </div>

    <!-- Dropdown positioning script to prevent overflow -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function adjustDropdownPosition(dropdown) {
            const menu = dropdown.querySelector('.dropdown-menu');
            if (!menu) return;
            
            // Temporarily show menu to measure
            const originalVisibility = menu.style.visibility;
            const originalOpacity = menu.style.opacity;
            menu.style.visibility = 'hidden';
            menu.style.opacity = '1';
            menu.style.display = 'block';
            
            const rect = menu.getBoundingClientRect();
            const viewportWidth = window.innerWidth;
            const navItemRect = dropdown.getBoundingClientRect();
            
            // Restore original state
            menu.style.visibility = originalVisibility;
            menu.style.opacity = originalOpacity;
            menu.style.display = '';
            
            // Check if dropdown would overflow
            if (navItemRect.left + rect.width > viewportWidth) {
                menu.style.left = 'auto';
                menu.style.right = '0';
            } else {
                menu.style.left = '';
                menu.style.right = '';
            }
        }
        
        // No JavaScript needed for inline submenu - CSS handles it
        
        const dropdowns = document.querySelectorAll('.nav-item.dropdown');
        
        dropdowns.forEach(function(dropdown) {
            // Check on hover
            dropdown.addEventListener('mouseenter', function() {
                adjustDropdownPosition(dropdown);
            });
            
            // No JavaScript needed for inline submenu - CSS handles it
        });
        
        // Re-adjust on window resize
        window.addEventListener('resize', function() {
            dropdowns.forEach(function(dropdown) {
                if (dropdown.querySelector('.dropdown-menu').style.visibility === 'visible' || 
                    dropdown.matches(':hover')) {
                    adjustDropdownPosition(dropdown);
                }
                
                const submenuItems = dropdown.querySelectorAll('.dropdown-item-with-submenu');
                submenuItems.forEach(function(submenuItem) {
                    if (submenuItem.matches(':hover')) {
                        adjustSubmenuPosition(submenuItem);
                    }
                });
            });
        });
    });
    </script>

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
