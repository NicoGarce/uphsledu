<?php
/**
 * UPHSL Careers Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Career opportunities and job listings at the University of Perpetual Help System Laguna
 */
session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or Support Services section is in maintenance
if (isSectionInMaintenance('support-services', 'careers') || isSectionInMaintenance('support-services')) {
    $page_title = "Careers - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('support-services', $base_path, 'careers')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "Careers - UPHSL";
// Set base path for subdirectory
$base_path = '../';
// Include header
include '../app/includes/header.php';
?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- News Carousel Section -->
        <?php
        $categoryId = 'Careers'; // Pass category name, component will look it up
        $sectionTitle = 'Careers News & Updates';
        $sectionDescription = 'Stay updated with the latest news and announcements from the Careers department.';
        include '../app/includes/news-carousel.php';
        ?>

        <?php include '../app/includes/general-coming-soon.php'; ?>
    </main>

<?php
// Include footer
include '../app/includes/footer.php';
?>
