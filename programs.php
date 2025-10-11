<?php
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

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Academic Programs</h1>
            <p>Discover our comprehensive range of academic programs designed to shape future leaders</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="programs-overview">
                <div class="programs-intro">
                    <h2>Choose Your Path to Success</h2>
                    <p>At University of Perpetual Help System Laguna, we offer a diverse range of academic programs that cater to different interests and career aspirations. Our programs are designed to provide students with both theoretical knowledge and practical skills necessary for success in their chosen fields.</p>
                </div>
                
                <!-- Basic Education Programs -->
                <section class="program-category">
                    <h3 class="category-title">
                        <i class="fas fa-graduation-cap"></i>
                        Basic Education
                    </h3>
                    <div class="programs-grid">
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-school"></i>
                            </div>
                            <h4>Grade School</h4>
                            <p>Foundation years that build strong academic and character foundations for young learners.</p>
                            <a href="programs/grade-school.php" class="program-link">Learn More</a>
                        </div>
                        
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <h4>Junior High School</h4>
                            <p>Comprehensive secondary education program preparing students for senior high school.</p>
                            <a href="programs/junior-high-school.php" class="program-link">Learn More</a>
                        </div>
                        
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h4>Senior High School</h4>
                            <p>Specialized tracks and strands preparing students for college and career paths.</p>
                            <a href="programs/senior-high-school.php" class="program-link">Learn More</a>
                        </div>
                    </div>
                </section>
                
                <!-- Higher Education Programs -->
                <section class="program-category">
                    <h3 class="category-title">
                        <i class="fas fa-university"></i>
                        Higher Education
                    </h3>
                    <div class="programs-grid">
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-plane"></i>
                            </div>
                            <h4>Aviation</h4>
                            <p>Professional pilot training and aviation management programs.</p>
                            <a href="programs/aviation.php" class="program-link">Learn More</a>
                        </div>
                        
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-palette"></i>
                            </div>
                            <h4>Arts & Sciences</h4>
                            <p>Liberal arts and sciences programs fostering critical thinking and creativity.</p>
                            <a href="programs/arts-sciences.php" class="program-link">Learn More</a>
                        </div>
                        
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4>Business & Accountancy</h4>
                            <p>Business administration, accounting, and management programs.</p>
                            <a href="programs/business-accountancy.php" class="program-link">Learn More</a>
                        </div>
                        
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <h4>Computer Studies</h4>
                            <p>Information technology, computer science, and software engineering programs.</p>
                            <a href="programs/computer-studies.php" class="program-link">Learn More</a>
                        </div>
                        
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4>Criminology</h4>
                            <p>Law enforcement, criminal justice, and forensic science programs.</p>
                            <a href="programs/criminology.php" class="program-link">Learn More</a>
                        </div>
                        
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h4>Education</h4>
                            <p>Teacher education and educational leadership programs.</p>
                            <a href="programs/education.php" class="program-link">Learn More</a>
                        </div>
                        
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <h4>Engineering & Architecture</h4>
                            <p>Engineering disciplines and architectural design programs.</p>
                            <a href="programs/engineering-architecture.php" class="program-link">Learn More</a>
                        </div>
                        
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                            <h4>International Hospitality Management</h4>
                            <p>Hotel and restaurant management with international standards.</p>
                            <a href="programs/hospitality-management.php" class="program-link">Learn More</a>
                        </div>
                        
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-ship"></i>
                            </div>
                            <h4>Maritime</h4>
                            <p>Maritime transportation and marine engineering programs.</p>
                            <a href="programs/maritime.php" class="program-link">Learn More</a>
                        </div>
                        
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-gavel"></i>
                            </div>
                            <h4>Law/Juris Doctor</h4>
                            <p>Legal education and jurisprudence programs.</p>
                            <a href="programs/law.php" class="program-link">Learn More</a>
                        </div>
                        
                        <div class="program-card">
                            <div class="program-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h4>Graduate School</h4>
                            <p>Master's and doctoral programs for advanced studies.</p>
                            <a href="programs/graduate-school.php" class="program-link">Learn More</a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

<?php
// Include footer
include 'app/includes/footer.php';
?>
