<?php
/**
 * UPHSL University Clinic Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the University Clinic services and facilities
 */
session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or Support Services section is in maintenance
if (isSectionInMaintenance('support-services', 'clinic') || isSectionInMaintenance('support-services')) {
    $page_title = "University Clinic - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('support-services', $base_path, 'clinic')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "University Clinic - UPHSL";
// Set base path for subdirectory
$base_path = '../';
// Include header
include '../app/includes/header.php';
?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- News Carousel Section -->
        <?php
        $categoryId = 'University Clinic'; // Pass category name, component will look it up
        $sectionTitle = 'University Clinic News & Updates';
        $sectionDescription = 'Stay updated with the latest news and announcements from the University Clinic.';
        include '../app/includes/news-carousel.php';
        ?>

        <?php include '../app/includes/general-coming-soon.php'; ?>
    </main>

<?php
// Include footer
include '../app/includes/footer.php';
?>
