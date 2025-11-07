<?php
/**
 * UPHSL Careers Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Career opportunities and job listings at the University of Perpetual Help System Laguna
 */
session_start();
$page_title = "Careers - UPHSL";
// Set base path for subdirectory
$base_path = '../';
// Include header
include '../app/includes/header.php';
?>

    <!-- Main Content -->
    <main class="main-content">
        <?php include '../app/includes/general-coming-soon.php'; ?>
    </main>

<?php
// Include footer
include '../app/includes/footer.php';
?>
