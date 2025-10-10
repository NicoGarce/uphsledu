<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Maritime";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/MARITIME.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="img/logo/logo-cmt.png" alt="Maritime Logo">
            </div>
            <div class="banner-content">
                <h1>Maritime</h1>
                <p>Navigating the world's oceans with excellence and safety</p>
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
