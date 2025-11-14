<?php
/**
 * UPHSL Contact Us Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Contact information for the University of Perpetual Help System Laguna
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or About section is in maintenance
if (isSectionInMaintenance('about', 'contact') || isSectionInMaintenance('about')) {
    $page_title = "Contact Us - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('about', $base_path, 'contact')) {
        include '../app/includes/footer.php';
        exit;
    }
}

// Set page title
$page_title = "Contact Us";
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Get in Touch</h1>
                <p class="hero-subtitle">We're here to help and answer any questions you might have about UPHSL</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="contact-layout">
                <!-- Left Column: Contact Information -->
                <div class="contact-column">
                    <h2 class="section-title">Contact Information</h2>
                    <div class="contact-methods-grid">
                        <div class="contact-card modern-card visit-card">
                            <div class="card-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="card-content">
                                <h3>Visit Us</h3>
                                <p>University of Perpetual Help System Laguna<br>
                                UPH Compound, National Highway<br>
                                Sto. Niño, City of Biñan, Laguna</p>
                                <div class="map-container">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3865.6606787139544!2d121.08281817589393!3d14.331132083615685!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd63ad4c1a178d%3A0x76688d7ab7914234!2sUniversity%20of%20Perpetual%20Help%20System%20Laguna!5e0!3m2!1sen!2sph!4v1760175109945!5m2!1sen!2sph" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-card modern-card">
                            <div class="card-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="card-content">
                                <h3>Call Us</h3>
                                <p><strong>Main Office:</strong> (02) 779-5310<br>
                                <strong>Admissions:</strong> (02) 779-5311<br>
                                <strong>Registrar:</strong> (02) 779-5312</p>
                            </div>
                        </div>
                        
                        <div class="contact-card modern-card">
                            <div class="card-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="card-content">
                                <h3>Email Us</h3>
                                <p><strong>General:</strong> info@uphsl.edu.ph<br>
                                <strong>Admissions:</strong> admissions@uphsl.edu.ph<br>
                                <strong>Marketing:</strong> marketing@uphsl.edu.ph</p>
                            </div>
                        </div>
                        
                        <div class="contact-card modern-card">
                            <div class="card-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="card-content">
                                <h3>Office Hours</h3>
                                <p><strong>Monday - Friday:</strong> 8:00 AM - 5:00 PM<br>
                                <strong>Saturday:</strong> 8:00 AM - 12:00 PM<br>
                                <strong>Sunday:</strong> Closed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Additional Information -->
                <div class="info-column">
                    <div class="info-container">
                        <div class="info-header">
                            <h2>Why Choose UPHSL?</h2>
                            <p>Discover what makes us the premier educational institution in Laguna</p>
                        </div>
                        
                        <div class="info-features">
                            <div class="info-feature">
                                <div class="feature-icon">
                                    <i class="fas fa-award"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>Quality Education</h3>
                                    <p>Committed to providing world-class education with modern facilities and experienced faculty.</p>
                                </div>
                            </div>
                            
                            <div class="info-feature">
                                <div class="feature-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>Diverse Community</h3>
                                    <p>Join a vibrant community of students from different backgrounds and cultures.</p>
                                </div>
                            </div>
                            
                            <div class="info-feature">
                                <div class="feature-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>Career Support</h3>
                                    <p>Comprehensive career guidance and job placement assistance for all graduates.</p>
                                </div>
                            </div>
                            
                            <div class="info-feature">
                                <div class="feature-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>Student Care</h3>
                                    <p>Dedicated support services to ensure every student's success and well-being.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="cta-section">
                            <h3>Ready to Start Your Journey?</h3>
                            <p>Visit our campus or contact us today to learn more about our programs and admission requirements.</p>
                            <div class="cta-buttons">
                                <a href="../programs.php" class="btn-secondary">
                                    <i class="fas fa-graduation-cap"></i>
                                    View Programs
                                </a>
                            </div>
                        </div>
                        
                        <!-- Quick Links moved to Right Column -->
                        <div class="quick-links-section">
                            <h3 class="quick-links-title">Quick Links</h3>
                            <div class="quick-links-grid">
                                <a href="../programs.php" class="quick-link">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span>Academic Programs</span>
                                </a>
                                <a href="../support-services/sps.php" class="quick-link">
                                    <i class="fas fa-user-graduate"></i>
                                    <span>Student Personnel Services</span>
                                </a>
                                <a href="../support-services/careers.php" class="quick-link">
                                    <i class="fas fa-briefcase"></i>
                                    <span>Careers</span>
                                </a>
                                <a href="../support-services/library.php" class="quick-link">
                                    <i class="fas fa-book"></i>
                                    <span>Library</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../app/includes/footer.php';
?>