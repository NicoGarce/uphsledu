<?php
session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Law/Juris Doctor";

// Set base path for assets
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/LAW.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="img/logo/logo-law.png" alt="Law Logo">
            </div>
            <div class="banner-content">
                <h1>Law/Juris Doctor</h1>
                <p>Pursuing justice through legal education and practice</p>
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
