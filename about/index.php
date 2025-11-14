<?php
/**
 * UPHSL About Index Redirect
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Redirects about/index.php to about.php
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if About Index or About section is in maintenance
if (isSectionInMaintenance('about', 'about-index') || isSectionInMaintenance('about')) {
    $page_title = "About Us - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('about', $base_path, 'about-index')) {
        include '../app/includes/footer.php';
        exit;
    }
}

// Redirect to about.php
header('Location: about.php');
exit();
?>
