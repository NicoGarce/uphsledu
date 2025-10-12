<?php
/**
 * UPHSL Programs Index Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Main programs page listing all available programs at UPHSL
 */

$page_title = "Programs";
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

<style>
.programs-hero {
    background: linear-gradient(135deg, rgba(28, 77, 161, 0.9), rgba(20, 57, 128, 0.9)), url('<?php echo $base_path; ?>assets/images/FACADE.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: white;
    padding: 5rem 0 3rem;
    text-align: center;
}

.programs-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.programs-hero p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.programs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    padding: 4rem 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.program-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.program-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    border-color: var(--primary-color);
}

.program-card h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.program-card p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.program-link {
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

.program-link:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
    text-decoration: none;
    color: white;
}

@media (max-width: 768px) {
    .programs-hero h1 {
        font-size: 2rem;
    }
    
    .programs-hero p {
        font-size: 1rem;
    }
    
    .programs-grid {
        grid-template-columns: 1fr;
        padding: 2rem 1rem;
    }
}
</style>

<main>
    <!-- Programs Hero Section -->
    <section class="programs-hero">
        <div class="container">
            <h1>Academic Programs</h1>
            <p>Discover the comprehensive range of academic programs offered at the University of Perpetual Help System Laguna</p>
        </div>
    </section>

    <!-- Programs Grid -->
    <section class="programs-grid">
        <div class="program-card">
            <h3>Basic Education</h3>
            <p>Foundation programs including Senior High School, Junior High School, and Grade School education.</p>
            <a href="senior-high-school" class="program-link">
                <i class="fas fa-arrow-right"></i>
                Explore Basic Education
            </a>
        </div>

        <div class="program-card">
            <h3>Aviation</h3>
            <p>Unlock your wings to limitless possibilities with our comprehensive aviation programs.</p>
            <a href="aviation" class="program-link">
                <i class="fas fa-arrow-right"></i>
                Explore Aviation
            </a>
        </div>

        <div class="program-card">
            <h3>Business & Accountancy</h3>
            <p>Develop effective skills for successful careers in business administration and accountancy.</p>
            <a href="business-accountancy" class="program-link">
                <i class="fas fa-arrow-right"></i>
                Explore Business & Accountancy
            </a>
        </div>

        <div class="program-card">
            <h3>Computer Studies</h3>
            <p>Comprehensive computer science and information technology programs for the digital age.</p>
            <a href="computer-studies" class="program-link">
                <i class="fas fa-arrow-right"></i>
                Explore Computer Studies
            </a>
        </div>

        <div class="program-card">
            <h3>Criminology</h3>
            <p>Produce graduates with technical skills in law enforcement and public safety.</p>
            <a href="criminology" class="program-link">
                <i class="fas fa-arrow-right"></i>
                Explore Criminology
            </a>
        </div>

        <div class="program-card">
            <h3>Teacher Education</h3>
            <p>Leading teacher education for globally competitive teaching professionals.</p>
            <a href="education" class="program-link">
                <i class="fas fa-arrow-right"></i>
                Explore Teacher Education
            </a>
        </div>

        <div class="program-card">
            <h3>Engineering & Architecture</h3>
            <p>Comprehensive programs in engineering, architecture, and aviation disciplines.</p>
            <a href="engineering-architecture" class="program-link">
                <i class="fas fa-arrow-right"></i>
                Explore Engineering & Architecture
            </a>
        </div>

        <div class="program-card">
            <h3>Hospitality Management</h3>
            <p>International hospitality management and tourism education for the global industry.</p>
            <a href="hospitality-management" class="program-link">
                <i class="fas fa-arrow-right"></i>
                Explore Hospitality Management
            </a>
        </div>

        <div class="program-card">
            <h3>Maritime</h3>
            <p>Navigate the world's oceans with excellence and safety through our maritime programs.</p>
            <a href="maritime" class="program-link">
                <i class="fas fa-arrow-right"></i>
                Explore Maritime
            </a>
        </div>

        <div class="program-card">
            <h3>Law/Juris Doctor</h3>
            <p>Pursue justice through comprehensive legal education and practice programs.</p>
            <a href="law" class="program-link">
                <i class="fas fa-arrow-right"></i>
                Explore Law
            </a>
        </div>

        <div class="program-card">
            <h3>Graduate School</h3>
            <p>Advance your knowledge through advanced studies and research programs.</p>
            <a href="graduate-school" class="program-link">
                <i class="fas fa-arrow-right"></i>
                Explore Graduate School
            </a>
        </div>

        <div class="program-card">
            <h3>Arts & Sciences</h3>
            <p>Multidimensional education fostering critical thinking and creative communication.</p>
            <a href="arts-sciences" class="program-link">
                <i class="fas fa-arrow-right"></i>
                Explore Arts & Sciences
            </a>
        </div>
    </section>
</main>

<?php include '../app/includes/footer.php'; ?>
