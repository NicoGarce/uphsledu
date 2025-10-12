<?php
/**
 * UPHSL Support Services Index Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Main support services page listing all available support services at UPHSL
 */

$page_title = "Support Services";
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

<style>
.services-hero {
    background: linear-gradient(135deg, rgba(28, 77, 161, 0.9), rgba(20, 57, 128, 0.9)), url('<?php echo $base_path; ?>assets/images/FACADE.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: white;
    padding: 5rem 0 3rem;
    text-align: center;
}

.services-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.services-hero p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

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
    <!-- Support Services Hero Section -->
    <section class="services-hero">
        <div class="container">
            <h1>Support Services</h1>
            <p>Comprehensive support services to enhance your academic journey and campus experience at UPHSL</p>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="services-grid">
        <div class="service-card">
            <div class="service-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h3>Guidance & Admission</h3>
            <p>Student Personnel Services providing comprehensive guidance and admission support for all students.</p>
            <a href="sps" class="service-link">
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
