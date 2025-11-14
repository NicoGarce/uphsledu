<?php
/**
 * UPHSL Campuses Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Displays information about all UPHSL campus locations and facilities
 */
session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Check if Campuses section is in maintenance
if (isSectionInMaintenance('campuses')) {
    $page_title = "Campuses - Maintenance";
    $base_path = '';
    include 'app/includes/header.php';
    if (displaySectionMaintenance('campuses', $base_path)) {
        include 'app/includes/footer.php';
        exit;
    }
}

$page_title = "JONELTA Campuses - UPHSL";

// Set base path for assets
$base_path = '';

include 'app/includes/header.php';
?>

<style>
.campus-showcase {
    padding: 3rem 0;
    background: #F8F8F8;
    min-height: 80vh;
}

.campus-header {
    text-align: center;
    margin-bottom: 4rem;
}

.campus-header h1 {
    font-size: 3.5rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 3px;
    text-shadow: 3px 3px 6px rgba(0,0,0,0.1);
    position: relative;
    display: inline-block;
    opacity: 1;
}

.campus-header h1::before {
    content: '';
    position: absolute;
    top: 50%;
    left: -2rem;
    right: -2rem;
    height: 4px;
    background: linear-gradient(90deg, transparent, var(--secondary-color), transparent);
    transform: translateY(-50%);
    z-index: -1;
}

.campus-header h1::after {
    content: '';
    position: absolute;
    bottom: -0.5rem;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    border-radius: 2px;
}

.campus-header p {
    font-size: 1.2rem;
    color: var(--text-light);
    max-width: 600px;
    margin: 0 auto;
    font-weight: 500;
    line-height: 1.6;
    opacity: 0.9;
}

.campus-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    justify-items: center;
}

.campus-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
    cursor: pointer;
    height: 250px;
    width: 100%;
    max-width: 400px;
    text-decoration: none;
    color: inherit;
    display: block;
}

.campus-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.medical-university-card {
    grid-column: 1 / -1;
    justify-self: center;
    max-width: 500px;
}

.campus-image-container {
    height: 100%;
    position: relative;
    overflow: hidden;
}

.campus-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.campus-card:hover .campus-image {
    transform: scale(1.1);
}

.campus-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(28, 77, 161, 0.95), rgba(82, 123, 189, 0.9));
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    padding: 2rem;
}

.campus-card:hover .campus-overlay {
    opacity: 1;
}

.campus-basic-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    color: white;
    padding: 2rem 1.5rem 1.5rem;
    transform: translateY(0);
    transition: transform 0.3s ease;
}

.campus-card:hover .campus-basic-info {
    transform: translateY(100%);
}

.campus-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.campus-location {
    font-size: 0.9rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.campus-location i {
    font-size: 0.8rem;
}

.campus-details {
    text-align: center;
    color: white;
    width: 100%;
}

.campus-details h3 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.campus-description {
    font-size: 0.85rem;
    line-height: 1.5;
    opacity: 0.95;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    font-weight: 500;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .campus-header h1 {
        font-size: 2.5rem;
        letter-spacing: 2px;
    }
    
    .campus-header h1::before {
        left: -1rem;
        right: -1rem;
        height: 3px;
    }
    
    .campus-header h1::after {
        width: 80px;
        height: 2px;
    }
    
    .campus-header p {
        font-size: 1rem;
    }
    
    .campus-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .campus-card {
        height: 200px;
    }
    
    .campus-overlay {
        padding: 1rem;
    }
    
    .campus-details h3 {
        font-size: 1.5rem;
    }
    
    .campus-description {
        font-size: 0.8rem;
    }
}

/* Animations */
@keyframes slideInFromTop {
    0% {
        opacity: 0;
        transform: translateY(-50px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 0.9;
        transform: translateY(0);
    }
}

@keyframes glow {
    0% {
        text-shadow: 3px 3px 6px rgba(0,0,0,0.1), 0 0 10px rgba(28, 77, 161, 0.3);
    }
    100% {
        text-shadow: 3px 3px 6px rgba(0,0,0,0.1), 0 0 20px rgba(28, 77, 161, 0.6), 0 0 30px rgba(82, 123, 189, 0.4);
    }
}

@keyframes typewriter {
    0% {
        width: 0;
    }
    100% {
        width: 100%;
    }
}
</style>

<main class="main-content">
    <!-- Campus Showcase Section -->
    <section class="campus-showcase">
        <div class="container">
            <div class="campus-header">
                <h1>JONELTA Campuses</h1>
                <p>Explore our network of campuses across the Philippines, each designed to provide exceptional education and comprehensive student services.</p>
            </div>

            <div class="campus-grid">
                <!-- Laguna Campus -->
                <a href="https://uphsl.edu.ph/" target="_blank" class="campus-card">
                    <div class="campus-image-container">
                        <img src="<?php echo $base_path; ?>assets/images/FACADE.jpg" alt="Laguna Campus" class="campus-image">
                        <div class="campus-basic-info">
                            <h3 class="campus-title">Laguna</h3>
                            <div class="campus-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Sto. Niño, City of Biñan, Laguna</span>
                            </div>
                        </div>
                        <div class="campus-overlay">
                            <div class="campus-details">
                                <h3>Laguna</h3>
                                <p class="campus-description">
                                    Main campus located in Sto. Niño, City of Biñan, Laguna, serving as the flagship campus with comprehensive academic programs and state-of-the-art facilities.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- GMA Cavite Campus -->
                <a href="https://gma.uphsl.edu.ph/" target="_blank" class="campus-card">
                    <div class="campus-image-container">
                        <img src="<?php echo $base_path; ?>assets/images/campuses/gma-college.jpeg" alt="GMA Cavite Campus" class="campus-image">
                        <div class="campus-basic-info">
                            <h3 class="campus-title">GMA Cavite</h3>
                            <div class="campus-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>San Gabriel, General Mariano Alvarez, Cavite</span>
                            </div>
                        </div>
                        <div class="campus-overlay">
                            <div class="campus-details">
                                <h3>GMA Cavite</h3>
                                <p class="campus-description">
                                    Located in San Gabriel, General Mariano Alvarez, Cavite, providing quality education and modern facilities to serve the growing community in the area.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Manila Campus -->
                <a href="https://manila.uphsl.edu.ph/" target="_blank" class="campus-card">
                    <div class="campus-image-container">
                        <img src="<?php echo $base_path; ?>assets/images/campuses/sampaloc-college.jpeg" alt="Manila Campus" class="campus-image">
                        <div class="campus-basic-info">
                            <h3 class="campus-title">Manila</h3>
                            <div class="campus-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>1240 V. Concepcion Street, Sampaloc, Manila</span>
                            </div>
                        </div>
                        <div class="campus-overlay">
                            <div class="campus-details">
                                <h3>Manila</h3>
                                <p class="campus-description">
                                    Situated in the heart of Manila at 1240 V. Concepcion Street, Sampaloc, providing easy access to government offices, business districts, and cultural centers.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Pangasinan Campus -->
                <a href="https://pangasinan.uphsl.edu.ph/" target="_blank" class="campus-card">
                    <div class="campus-image-container">
                        <img src="<?php echo $base_path; ?>assets/images/campuses/uphs-pangasinan.jpg" alt="Pangasinan Campus" class="campus-image">
                        <div class="campus-basic-info">
                            <h3 class="campus-title">Pangasinan</h3>
                            <div class="campus-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Montemayor Street, Poblacion, Malasiqui, Pangasinan</span>
                            </div>
                        </div>
                        <div class="campus-overlay">
                            <div class="campus-details">
                                <h3>Pangasinan</h3>
                                <p class="campus-description">
                                    Located in Montemayor Street, Poblacion, Malasiqui, Pangasinan, serving the northern regions of Luzon with quality education and community development programs.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Isabela Campus -->
                <a href="https://isabela.uphsl.edu.ph/" target="_blank" class="campus-card">
                    <div class="campus-image-container">
                        <img src="<?php echo $base_path; ?>assets/images/campuses/Cauayan-college.jpg" alt="Isabela Campus" class="campus-image">
                        <div class="campus-basic-info">
                            <h3 class="campus-title">Isabela</h3>
                            <div class="campus-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Cauayan City, Isabela</span>
                            </div>
                        </div>
                        <div class="campus-overlay">
                            <div class="campus-details">
                                <h3>Isabela</h3>
                                <p class="campus-description">
                                    Located in Cauayan City, Isabela, bringing quality education and comprehensive academic programs to the Cagayan Valley region.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Roxas Campus -->
                <div class="campus-card">
                    <div class="campus-image-container">
                        <img src="<?php echo $base_path; ?>assets/images/campuses/pueblo-college.jpg" alt="Roxas Campus" class="campus-image">
                        <div class="campus-basic-info">
                            <h3 class="campus-title">Roxas</h3>
                            <div class="campus-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Pueblo de Panay, Roxas City</span>
                            </div>
                        </div>
                        <div class="campus-overlay">
                            <div class="campus-details">
                                <h3>Roxas</h3>
                                <p class="campus-description">
                                    Situated in Pueblo de Panay, Roxas City, providing quality education and academic excellence to the Capiz region and surrounding areas.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UPH-DR. JOSE G. TAMAYO MEDICAL UNIVERSITY -->
                <a href="https://uphdjgtmedicaluniversity.edu.ph/" target="_blank" class="campus-card medical-university-card">
                    <div class="campus-image-container">
                        <img src="<?php echo $base_path; ?>assets/images/campuses/Allied.png" alt="UPH-DR. JOSE G. TAMAYO MEDICAL UNIVERSITY" class="campus-image">
                        <div class="campus-basic-info">
                            <h3 class="campus-title">UPH-DR. JOSE G. TAMAYO MEDICAL UNIVERSITY</h3>
                            <div class="campus-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Sto. Niño, City of Biñan, Laguna</span>
                            </div>
                        </div>
                        <div class="campus-overlay">
                            <div class="campus-details">
                                <h3>UPH-DR. JOSE G. TAMAYO MEDICAL UNIVERSITY</h3>
                                <p class="campus-description">
                                    Specialized medical university located in Sto. Niño, City of Biñan, Laguna, dedicated to excellence in medical and health sciences education.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
</main>

<?php include 'app/includes/footer.php'; ?>
