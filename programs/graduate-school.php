<?php
/**
 * UPHSL Graduate School Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the Graduate School program at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Graduate School";

// Set base path for assets
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/GRADUATE SCHOOL.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="img/logo/graduate-school-logo.png" alt="Graduate School Logo">
            </div>
            <div class="banner-content">
                <h1>Graduate School</h1>
                <p>Advancing knowledge through advanced studies and research</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <?php include '../app/includes/coming-soon.php'; ?>
    </main>

<?php
// Include footer
include '../app/includes/footer.php';
?>
