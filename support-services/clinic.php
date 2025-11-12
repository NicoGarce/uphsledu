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
        $isSupportService = true; // Use horizontal layout for support services
        include '../app/includes/news-carousel.php';
        ?>

        <?php include '../app/includes/general-coming-soon.php'; ?>
    </main>

<?php
// Include footer
include '../app/includes/footer.php';
?>
