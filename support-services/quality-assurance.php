<?php
/**
 * UPHSL Quality Assurance Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about Quality Assurance programs and initiatives at UPHSL
 */
session_start();
$base_path = '../';
$page_title = "Quality Assurance - UPHSL";
include '../app/includes/header.php';
?>

<style>
body {
    padding: 0 !important;
    margin: 0 !important;
}

.main-content {
    padding-top: 100px; /* Base padding for header */
}

/* Responsive header spacing for different devices */
@media (max-width: 1200px) {
    .main-content {
        padding-top: 90px;
    }
}

@media (max-width: 992px) {
    .main-content {
        padding-top: 80px;
    }
}

@media (max-width: 768px) {
    .main-content {
        padding-top: 70px;
    }
}

@media (max-width: 576px) {
    .main-content {
        padding-top: 60px;
    }
}

/* Ensure intro section starts immediately after header */
.intro-section {
    margin-top: 0;
}

/* Intro Section */
.intro-section {
    background: linear-gradient(135deg, rgba(44, 90, 160, 0.9), rgba(255, 198, 62, 0.9)), url('<?php echo $base_path; ?>assets/images/FACADE.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: white;
    padding: 2rem 0;
    margin: 0;
    position: relative;
    overflow: hidden;
}

.intro-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 1rem;
}

.intro-logo {
    margin-bottom: 1.5rem;
}

.intro-logo img {
    width: 150px;
    height: 150px;
    object-fit: contain;
    filter: brightness(1.1);
    transition: transform 0.3s ease;
}

.intro-content h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    letter-spacing: -0.5px;
}

.intro-description {
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1.5rem;
    opacity: 0.95;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .intro-content h2 {
        font-size: 2rem;
    }
    
    .intro-description {
        font-size: 0.8rem;
    }
}
</style>

<main class="main-content">
    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-logo">
                    <img src="<?php echo $base_path; ?>assets/images/Logos/Logo2025.png" alt="Quality Assurance Logo">
                </div>
                <h2>Quality Assurance</h2>
                <p class="intro-description">We are committed to maintaining the highest standards of quality in all our academic programs and services. Our Quality Assurance department ensures continuous improvement and excellence across the university.</p>
            </div>
        </div>
    </section>

    <!-- Coming Soon Section -->
    <section class="content-section">
        <div class="container">
            <?php include '../app/includes/general-coming-soon.php'; ?>
        </div>
    </section>
</main>

<?php include '../app/includes/footer.php'; ?>
