<?php
/**
 * UPHSL Graduate School Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the Graduate School program at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or Programs section is in maintenance
if (isSectionInMaintenance('programs', 'graduate-school') || isSectionInMaintenance('programs')) {
    $page_title = "Graduate School - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'graduate-school')) {
        include '../app/includes/footer.php';
        exit;
    }
}

// Set page title
$page_title = "Graduate School";

// Set base path for assets
$base_path = '../';

// Set background image path
$bg_image = 'img/banner/GRADUATE SCHOOL.jpg';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('<?php echo $bg_image; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="<?php echo $base_path; ?>programs/img/logo/graduate-school-logo.png" alt="Graduate School Logo">
            </div>
            <div class="banner-content">
                <h1>Graduate School</h1>
                <p>Advancing knowledge through advanced studies and research</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <!-- News Carousel Section -->
                    <?php
                    $sectionTitle = 'Graduate School News & Updates';
                    $sectionDescription = 'Stay updated with the latest news and announcements from Graduate School.';
                    include '../app/includes/news-carousel.php';
                    ?>

                    <!-- Program Offerings Section -->
                    <section class="programs-section">
                        <div class="section-header">
                            <div class="header-content">
                                <div class="header-text">
                                    <h2>Program Offerings</h2>
                                    <p>Choose from our comprehensive range of graduate programs designed to advance your career and research capabilities.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Doctoral Programs -->
                        <div class="program-category">
                            <h3 class="category-title">Doctoral Programs</h3>
                            <div class="programs-grid">
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <h4>Doctor of Philosophy in Business Management</h4>
                                    <p>Advanced research and leadership in business administration and management.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <h4>Doctor of Philosophy major in Educational Management</h4>
                                    <p>Leadership and advanced studies in educational administration and management.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <h4>Doctor of Occupational Therapy</h4>
                                    <p>Advanced clinical practice and research in occupational therapy.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <h4>Doctor of Physical Therapy</h4>
                                    <p>Advanced clinical practice and research in physical therapy.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Master's Programs -->
                        <div class="program-category">
                            <h3 class="category-title">Master's Programs</h3>
                            <div class="programs-grid">
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master in Business Administration</h4>
                                    <p>Comprehensive business management and leadership development.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master in Hospital Administration</h4>
                                    <p>Healthcare management and hospital operations leadership.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master of Arts in Education</h4>
                                    <p>Advanced studies in educational theory and practice.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master of Arts in Communication</h4>
                                    <p>Advanced communication studies and media research.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master of Arts in Guidance and Counselling</h4>
                                    <p>Professional counseling and guidance services training.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master in Library and Information Science</h4>
                                    <p>Advanced library science and information management.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master of Science in Information Technology</h4>
                                    <p>Advanced IT studies and technology management.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master of Science in Management Engineering</h4>
                                    <p>Engineering management and systems optimization.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master of Science in Psychology</h4>
                                    <p>Advanced psychological research and clinical practice.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master of Science in Microbiology</h4>
                                    <p>Advanced microbiological research and applications.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master of Science of Clinical Program Development</h4>
                                    <p>Healthcare program development and clinical management.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master of Science in Radiologic Technology</h4>
                                    <p>Advanced radiologic technology and medical imaging.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master of Science in Pharmacy</h4>
                                    <p>Advanced pharmaceutical sciences and drug development.</p>
                                </div>
                                <div class="program-card">
                                    <div class="program-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h4>Master of Science in Clinical Laboratory Science</h4>
                                    <p>Advanced clinical laboratory diagnostics and research.</p>
                                </div>
                            </div>
                        </div>

                        <!-- MA in Education Majors -->
                        <div class="program-category">
                            <h3 class="category-title">Master of Arts in Education Majors</h3>
                            <div class="majors-grid">
                                <div class="major-item">
                                    <div class="major-icon">
                                        <i class="fas fa-language"></i>
                                    </div>
                                    <h4>Teaching English as Second Language</h4>
                                    <p>Specialized training in English language education for non-native speakers.</p>
                                </div>
                                <div class="major-item">
                                    <div class="major-icon">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <h4>Educational Management</h4>
                                    <p>Leadership and administration in educational institutions.</p>
                                </div>
                                <div class="major-item">
                                    <div class="major-icon">
                                        <i class="fas fa-hands-helping"></i>
                                    </div>
                                    <h4>Special Education</h4>
                                    <p>Teaching strategies and support for students with special needs.</p>
                                </div>
                                <div class="major-item">
                                    <div class="major-icon">
                                        <i class="fas fa-calculator"></i>
                                    </div>
                                    <h4>Mathematics</h4>
                                    <p>Advanced mathematical education and curriculum development.</p>
                                </div>
                                <div class="major-item">
                                    <div class="major-icon">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <h4>Filipino</h4>
                                    <p>Filipino language education and cultural studies.</p>
                                </div>
                            </div>
                        </div>

                    </section>
                </div>
                <!-- Facebook Sidebar Widget -->
                <aside class="content-sidebar">
                    <div class="sidebar-widget facebook-widget">
                        <a href="https://www.facebook.com/UPHSLGraduateSchool" target="_blank" rel="noopener" class="facebook-header">
                            <h3 class="facebook-title">
                                <i class="fab fa-facebook"></i>
                                Follow Us on Facebook
                            </h3>
                            <p class="facebook-subtitle">Stay connected with our latest updates</p>
                        </a>
                        <div class="facebook-embed">
                            <div class="fb-page" data-href="https://www.facebook.com/UPHSLGraduateSchool" data-tabs="timeline" data-width="" data-height="500" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div>
                        </div>
                </div>
            </aside>
        </div>
    </main>

    <style>
    /* Programs Section Styles */
    .programs-section {
        padding: 4rem 0;
        background: #f8f9fa;
    }

    .section-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 2rem;
    }

    .header-text {
        flex: 1;
        text-align: center;
    }

    .social-links {
        display: flex;
        align-items: center;
    }

    .social-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.8rem 1.2rem;
        background: #1877f2;
        color: white;
        text-decoration: none;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .social-link.facebook:hover {
        background: #0d66d3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(24, 119, 242, 0.3);
    }

    .social-link i {
        font-size: 1.1rem;
    }

    .social-link span {
        font-weight: 600;
    }

    /* Facebook Widget Styling for Sidebar */
    .facebook-widget {
        padding: 0 !important;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .facebook-header {
        display: block;
        background: linear-gradient(135deg, #1877f2 0%, #0d5dbf 100%);
        padding: 1.5rem;
        text-decoration: none;
        color: white !important;
        
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .facebook-header:hover {
        background: linear-gradient(135deg, #166fe5 0%, #0c5bb8 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(24, 119, 242, 0.3);
        text-decoration: none;
    }

    .facebook-title {
        margin: 0 0 0.5rem 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: white !important;
    }

    .facebook-title i {
        font-size: 1.4rem;
        color: white !important;
    }

    .facebook-subtitle {
        margin: 0;
        font-size: 0.85rem;
        opacity: 0.9;
        color: white !important;
    }

    .facebook-embed {
        background: white;
        padding: 1rem;
        border-radius: 0 0 12px 12px;
    }

    /* Ensure FB widget doesn't overflow container */
    .facebook-embed .fb-page,
    .facebook-embed .fb-page > span,
    .facebook-embed .fb-page iframe {
        width: 100% !important;
        max-width: 100% !important;
    }

    .section-header h2 {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .section-header p {
        font-size: 1.1rem;
        color: #666;
        max-width: 600px;
        margin: 0 auto;
    }

    .program-category {
        margin-bottom: 4rem;
    }

    .category-title {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 2rem;
        text-align: center;
        font-weight: 600;
        position: relative;
    }

    .category-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: var(--secondary-color);
        border-radius: 2px;
    }

    .programs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .program-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        text-align: center;
    }

    .program-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border-color: var(--secondary-color);
    }

    .program-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .program-icon i {
        color: white;
        font-size: 1.5rem;
    }

    .program-card h4 {
        font-size: 1.2rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .program-card p {
        color: #666;
        line-height: 1.6;
        margin: 0;
    }

    .majors-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .major-item {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 4px solid var(--primary-color);
    }

    .major-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        border-left-color: var(--secondary-color);
    }

    .major-icon {
        width: 50px;
        height: 50px;
        background: var(--primary-color);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .major-icon i {
        color: white;
        font-size: 1.2rem;
    }

    .major-item h4 {
        font-size: 1.1rem;
        color: var(--primary-color);
        margin-bottom: 0.8rem;
        font-weight: 600;
    }

    .major-item p {
        color: #666;
        line-height: 1.5;
        margin: 0;
        font-size: 0.9rem;
    }

    .admission-info {
        background: white;
        padding: 3rem;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-top: 3rem;
    }

    .admission-info h3 {
        text-align: center;
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 2rem;
        font-weight: 600;
    }

    .admission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .admission-item {
        text-align: center;
        padding: 2rem;
        border-radius: 12px;
        background: #f8f9fa;
    }

    .admission-item i {
        color: var(--primary-color);
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .admission-item h4 {
        font-size: 1.2rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .admission-item ul {
        text-align: left;
        color: #666;
        line-height: 1.6;
        margin: 0;
        padding-left: 1.5rem;
    }

    .admission-item p {
        color: #666;
        line-height: 1.6;
        margin: 0;
    }

    /* Sidebar Layout */
    .content-wrapper {
        display: flex;
        gap: 2rem;
    }

    .content-main {
        flex: 3;
    }

    .content-sidebar {
        width: 1fr;
        flex-shrink: 0;
        max-width: 350px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .content-wrapper {
            flex-direction: column;
        }

        .content-sidebar {
            width: 100%;
            order: 2;
        }

        .programs-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .majors-grid {
            grid-template-columns: 1fr;
        }

        .admission-grid {
            grid-template-columns: 1fr;
        }

        .section-header h2 {
            font-size: 2rem;
        }

        .category-title {
            font-size: 1.5rem;
        }

        .program-card {
            padding: 1.5rem;
        }

        .major-item {
            padding: 1rem;
        }

        .admission-info {
            padding: 2rem;
        }

        .header-content {
            flex-direction: column;
            gap: 1rem;
        }

        .social-links {
            justify-content: center;
        }

        .social-link {
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
        }

        .facebook-widget {
            margin-bottom: 0;
        }
    }
    </style>

<?php
// Include footer
include '../app/includes/footer.php';
?>

