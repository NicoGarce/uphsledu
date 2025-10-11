<?php
/**
 * UPHSL Programs Overview Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Displays all academic programs offered by the University of Perpetual Help System Laguna
 */

session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Set page title
$page_title = "Academic Programs";

// Set base path for assets (empty for root directory)
$base_path = '';

// Include header
include 'app/includes/header.php';
?>

    <!-- Hero Section -->
    <section class="programs-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Academic Programs</h1>
                <p class="hero-subtitle">Discover our comprehensive range of academic programs designed to shape future leaders and innovators</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Programs Overview -->
            <section class="programs-overview">
                <div class="overview-content">
                    <h2 class="section-title">Choose Your Path to Success</h2>
                    <p class="section-description">At University of Perpetual Help System Laguna, we offer a diverse range of academic programs that cater to different interests and career aspirations. Our programs are designed to provide students with both theoretical knowledge and practical skills necessary for success in their chosen fields.</p>
                </div>
            </section>

            <!-- Program Categories -->
            <div class="programs-layout">
                <!-- Basic Education -->
                <section class="program-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-title">Basic Education</h3>
                            <p class="category-description">Foundation programs that build strong academic and character foundations</p>
                        </div>
                    </div>
                    <div class="programs-grid">
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div class="program-badge">K-6</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Grade School</h4>
                                <p class="program-description">Foundation years that build strong academic and character foundations for young learners.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Elementary</span>
                                    <span class="feature-tag">Foundation</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/grade-school.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="program-badge">7-10</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Junior High School</h4>
                                <p class="program-description">Comprehensive secondary education program preparing students for senior high school.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Secondary</span>
                                    <span class="feature-tag">Foundation</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/junior-high-school.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="program-badge">11-12</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Senior High School</h4>
                                <p class="program-description">Specialized tracks and strands preparing students for college and career paths.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Tracks</span>
                                    <span class="feature-tag">Strands</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/senior-high-school.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Higher Education -->
                <section class="program-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-title">Higher Education</h3>
                            <p class="category-description">Undergraduate and graduate programs for specialized career paths</p>
                        </div>
                    </div>
                    <div class="programs-grid">
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-plane"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Aviation</h4>
                                <p class="program-description">Professional pilot training and aviation management programs.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Pilot Training</span>
                                    <span class="feature-tag">Management</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/aviation.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-palette"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Arts & Sciences</h4>
                                <p class="program-description">Liberal arts and sciences programs fostering critical thinking and creativity.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Liberal Arts</span>
                                    <span class="feature-tag">Sciences</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/arts-sciences.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Business & Accountancy</h4>
                                <p class="program-description">Business administration, accounting, and management programs.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Business</span>
                                    <span class="feature-tag">Accounting</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/business-accountancy.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-laptop-code"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Computer Studies</h4>
                                <p class="program-description">Information technology, computer science, and software engineering programs.</p>
                                <div class="program-features">
                                    <span class="feature-tag">IT</span>
                                    <span class="feature-tag">Programming</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/computer-studies.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Criminology</h4>
                                <p class="program-description">Law enforcement, criminal justice, and forensic science programs.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Law Enforcement</span>
                                    <span class="feature-tag">Forensics</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/criminology.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Education</h4>
                                <p class="program-description">Teacher education and educational leadership programs.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Teaching</span>
                                    <span class="feature-tag">Leadership</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/education.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Engineering & Architecture</h4>
                                <p class="program-description">Engineering disciplines and architectural design programs.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Engineering</span>
                                    <span class="feature-tag">Architecture</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/engineering-architecture.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-concierge-bell"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">International Hospitality Management</h4>
                                <p class="program-description">Hotel and restaurant management with international standards.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Hospitality</span>
                                    <span class="feature-tag">International</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/hospitality-management.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-ship"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Maritime</h4>
                                <p class="program-description">Maritime transportation and marine engineering programs.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Maritime</span>
                                    <span class="feature-tag">Transportation</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/maritime.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-gavel"></i>
                                </div>
                                <div class="program-badge">Juris Doctor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Law/Juris Doctor</h4>
                                <p class="program-description">Legal education and jurisprudence programs.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Legal</span>
                                    <span class="feature-tag">Jurisprudence</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/law.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="program-badge">Graduate</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Graduate School</h4>
                                <p class="program-description">Master's and doctoral programs for advanced studies.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Master's</span>
                                    <span class="feature-tag">Doctoral</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="programs/graduate-school.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Call to Action -->
            <section class="programs-cta">
                <div class="cta-content">
                    <h2>Ready to Start Your Academic Journey?</h2>
                    <p>Explore our programs and find the perfect path for your future career</p>
                    <div class="cta-buttons">
                        <a href="about/contact.php" class="btn-primary">
                            <i class="fas fa-phone"></i>
                            Contact Us
                        </a>
                        <a href="support-services/sps.php" class="btn-secondary">
                            <i class="fas fa-user-graduate"></i>
                            Apply Now
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </main>

<?php
// Include footer
include 'app/includes/footer.php';
?>