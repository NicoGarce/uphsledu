<?php
/**
 * UPHSL Law Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the Law program at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Law/Juris Doctor";

// Set base path for assets
$base_path = '../';

// Set background image path
$bg_image = 'img/banner/LAW.jpg';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('<?php echo $bg_image; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="<?php echo $base_path; ?>programs/img/logo/logo-law.png" alt="Law Logo">
            </div>
            <div class="banner-content">
                <h1>Law/Juris Doctor</h1>
                <p>Pursuing justice through legal education and practice</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <?php
        // Check if category exists and has posts
        $category = getCategoryByName('Law/Juris Doctor');
        $categoryId = $category ? $category['id'] : null;
        $hasPosts = false;
        
        if ($categoryId) {
            $category_posts = getRecentPostsByCategory($categoryId, 1);
            $hasPosts = !empty($category_posts);
        }
        
        if ($hasPosts):
        ?>
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <!-- News Carousel Section -->
                    <?php
                    $sectionTitle = 'Law/Juris Doctor News & Updates';
                    $sectionDescription = 'Stay updated with the latest news and announcements from the College of Law.';
                    include '../app/includes/news-carousel.php';
                    ?>
                </div>
            </div>
        </div>
        <?php else: ?>
        <?php include '../app/includes/coming-soon.php'; ?>
        <?php endif; ?>
    </main>

<?php
// Include footer
include '../app/includes/footer.php';
?>



