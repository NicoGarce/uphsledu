<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Graduate School";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
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
        <?php include '../includes/coming-soon.php'; ?>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
