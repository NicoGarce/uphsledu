<?php
/**
 * UPHSL Support Services Index Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Main support services page listing all available support services at UPHSL
 */
session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if Support Services Index or Support Services section is in maintenance
if (isSectionInMaintenance('support-services', 'support-services-index') || isSectionInMaintenance('support-services')) {
    $page_title = "Support Services - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('support-services', $base_path, 'support-services-index')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "Support Services";
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

<style>
/* New page hero */
.page-hero { position: relative; padding: 80px 0; color: #fff; text-align: center; isolation: isolate; overflow: hidden; background: url('../assets/images/FACADE.jpg') center/cover no-repeat; }
.page-hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(28,77,161,.85), rgba(82,123,189,.85)); z-index: 1; }
.page-hero .content { position: relative; z-index: 2; display: inline-block; padding: 24px 28px; border-radius: 16px; background: rgba(0,0,0,.55); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); box-shadow: 0 16px 40px rgba(0,0,0,.35); }
.page-hero .title { font-size: 3.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,.3); }
.page-hero .subtitle { font-size: 1.3rem; margin: 0; }
@media (max-width: 1024px) { .page-hero{ padding:60px 0; } .page-hero .content{ padding:16px 18px; border-radius:12px; } .page-hero .title{ font-size:2.5rem; } .page-hero .subtitle{ font-size:1.1rem; } }

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    padding: 4rem 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.service-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 2px solid transparent;
    text-align: center;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    border-color: var(--primary-color);
}

.service-icon {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.service-card h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.service-card p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.service-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.service-link:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
    text-decoration: none;
    color: white;
}

@media (max-width: 768px) {
    .services-hero h1 {
        font-size: 2rem;
    }
    
    .services-hero p {
        font-size: 1rem;
    }
    
    .services-grid {
        grid-template-columns: 1fr;
        padding: 2rem 1rem;
    }
}
</style>

<main>
    <!-- New Banner -->
    <section class="page-hero">
        <div class="container">
            <div class="content">
                <h1 class="title">Support Services</h1>
                <p class="subtitle">Comprehensive services that support every Perpetualite’s journey</p>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="services-grid">
        <div class="service-card">
            <div class="service-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h3>Student Personnel Services</h3>
            <p>Student Personnel Services providing comprehensive guidance and admission support for all students.</p>
            <a href="sps.php" class="service-link">
                <i class="fas fa-arrow-right"></i>
                Learn More
            </a>
        </div>

        <div class="service-card">
            <div class="service-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <h3>Careers</h3>
            <p>Career development and job placement services to help students transition into professional life.</p>
            <a href="careers" class="service-link">
                <i class="fas fa-arrow-right"></i>
                Learn More
            </a>
        </div>

        <div class="service-card">
            <div class="service-icon">
                <i class="fas fa-heartbeat"></i>
            </div>
            <h3>Clinic</h3>
            <p>Health and medical services to ensure the well-being of all students and staff.</p>
            <a href="clinic" class="service-link">
                <i class="fas fa-arrow-right"></i>
                Learn More
            </a>
        </div>

        <div class="service-card">
            <div class="service-icon">
                <i class="fas fa-hands-helping"></i>
            </div>
            <h3>Community Outreach</h3>
            <p>Community development and outreach programs to serve the broader community.</p>
            <a href="cod" class="service-link">
                <i class="fas fa-arrow-right"></i>
                Learn More
            </a>
        </div>

        <div class="service-card">
            <div class="service-icon">
                <i class="fas fa-globe"></i>
            </div>
            <h3>International Affairs</h3>
            <p>International programs and external affairs to promote global education and partnerships.</p>
            <a href="iea" class="service-link">
                <i class="fas fa-arrow-right"></i>
                Learn More
            </a>
        </div>

        <div class="service-card">
            <div class="service-icon">
                <i class="fas fa-book"></i>
            </div>
            <h3>Library</h3>
            <p>Comprehensive library services and resources to support academic research and learning.</p>
            <a href="library" class="service-link">
                <i class="fas fa-arrow-right"></i>
                Learn More
            </a>
        </div>

        <div class="service-card">
            <div class="service-icon">
                <i class="fas fa-award"></i>
            </div>
            <h3>Quality Assurance</h3>
            <p>Quality assurance and accreditation services to maintain high academic standards.</p>
            <a href="quality-assurance" class="service-link">
                <i class="fas fa-arrow-right"></i>
                Learn More
            </a>
        </div>

        <div class="service-card">
            <div class="service-icon">
                <i class="fas fa-microscope"></i>
            </div>
            <h3>Research</h3>
            <p>Research development and support services to advance knowledge and innovation.</p>
            <a href="research" class="service-link">
                <i class="fas fa-arrow-right"></i>
                Learn More
            </a>
        </div>
    </section>
</main>

<?php include '../app/includes/footer.php'; ?>
