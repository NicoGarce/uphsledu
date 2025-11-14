<?php
/**
 * UPHSL Programs Index Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Main programs page listing all available programs at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if Programs section or Programs Index is in maintenance
if (isSectionInMaintenance('programs', 'programs-index') || isSectionInMaintenance('programs')) {
    $page_title = "Programs - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'programs-index')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "Academic Programs";
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

<style>
    /* New page hero - matching SDG banner styling */
    .page-hero { position: relative; padding: 80px 0; color: #fff; text-align: center; isolation: isolate; overflow: hidden; background: url('<?php echo $base_path; ?>assets/images/FACADE.jpg') center/cover no-repeat; }
    .page-hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(28,77,161,.85), rgba(82,123,189,.85)); z-index: 1; }
    .page-hero .content { position: relative; z-index: 2; display: inline-block; padding: 24px 28px; border-radius: 16px; background: rgba(0,0,0,.55); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); box-shadow: 0 16px 40px rgba(0,0,0,.35); }
    .page-hero .title { font-size: 3rem; font-weight: 800; line-height: 1.1; margin-bottom: 18px; text-shadow: 2px 2px 4px rgba(0,0,0,.3); }
    .page-hero .subtitle { font-size: 1.05rem; margin: 0; }
    @media (max-width: 1024px){ .page-hero{ padding:60px 0; } .page-hero .content{ padding:16px 18px; border-radius:12px; } .page-hero .title{ font-size:2.2rem; } .page-hero .subtitle{ font-size:1rem; } }
    
</style>


    <!-- New Banner -->
    <section class="page-hero">
        <div class="container">
            <div class="content">
                <h1 class="title">Academic Programs</h1>
                <p class="subtitle">Discover our comprehensive range of academic programs designed to shape future leaders and innovators</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">

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
                                <a href="grade-school.php" class="program-link">
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
                                <a href="junior-high-school.php" class="program-link">
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
                                <a href="senior-high-school.php" class="program-link">
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
                                <a href="aviation.php" class="program-link">
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
                                <a href="arts-sciences.php" class="program-link">
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
                                <a href="business-accountancy.php" class="program-link">
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
                                <a href="computer-studies.php" class="program-link">
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
                                <a href="criminology.php" class="program-link">
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
                                <a href="education.php" class="program-link">
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
                                <a href="engineering-architecture.php" class="program-link">
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
                                <a href="hospitality-management.php" class="program-link">
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
                                <a href="maritime.php" class="program-link">
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
                                <a href="law.php" class="program-link">
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
                                <a href="graduate-school.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
        </div>
    </section>
        </div>

        </div>
</main>

<?php include '../app/includes/footer.php'; ?>
