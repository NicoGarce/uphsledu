<?php
/**
 * UPHSL Community Outreach Department Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the Community Outreach Department and its programs
 */
session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
$page_title = "Community Outreach Department - UPHSL";
$base_path = '../';

// Base path for assets

// Include header
include '../app/includes/header.php';
?>


    <!-- Main Content -->
    <main class="main-content">
                        <!-- Introduction Section -->
                        <section class="intro-section">
                            <div class="container">
                                <div class="intro-content">
                                    <div class="intro-logo">
                                        <img src="<?php echo $base_path; ?>assets/images/cod/UPHSL-COD.png" alt="Community Outreach Department Logo">
                                    </div>
                                    <h2>Community Outreach Department</h2>
                                    <p class="intro-description">The Community Outreach Department is responsible for the implementation of community outreach and extension programs of the university. Its main objectives include, but are not limited to, improvement of health status of a community, enhancement of literacy of the community members, and cultivation of values and culture toward attaining improved quality of life as we exercise our virtue that Perpetualites are helpers of God.</p>
                                </div>
                            </div>
                        </section>

    <!-- News Carousel Section -->
    <?php
    $categoryId = 'Community Outreach Department'; // Pass category name, component will look it up
    $sectionTitle = 'Community Outreach Department News & Updates';
    $sectionDescription = 'Stay updated with the latest news and announcements from the Community Outreach Department.';
    $isSupportService = true; // Use horizontal layout for support services
    include '../app/includes/news-carousel.php';
    ?>

                        <!-- Mission, Vision, Philosophy Section -->
                        <section class="mvp-section">
                            <div class="container">
                                <div class="mvp-grid">
                                <div class="mvp-card">
                                    <h3>Vision</h3>
                                    <p>The UPHSL Community Outreach Department (COD) is a dynamic, facilitative and integrative office that assists people to become physically, socially, mentally healthy, and economically stable especially those in the underprivileged communities in the country and beyond.</p>
                                </div>

                                <div class="mvp-card">
                                    <h3>Mission</h3>
                                    <p>The UPHSL-COD contributes to the development of the social, economic and environmental well-being of individuals toward a productive and sustainable future.</p>
                                </div>

                                <div class="mvp-card">
                                    <h3>Philosophy</h3>
                                    <p>The Community Outreach Department (COD) believes in the dignity of man and the development of his potentials to the optimum. The program further believes that such development could be attained through the involvement of socially conscious students, faculty members and non-teaching staff in community services.</p>
                                </div>
                                </div>
                            </div>
                        </section>

                        <!-- Quality Objectives Section -->
                        <section class="objectives-section">
                            <div class="container">
                                <h2>Quality Objectives</h2>
                                <p class="objectives-subtitle">Our commitment to excellence through clearly defined goals and measurable outcomes</p>
                            <ul class="objectives-list">
                                <li>Improve health status of the depressed, deprived and underprivileged communities it seeks to serve;</li>
                                <li>Enhance the literacy by maximizing transfer of resource in terms of knowledge and technology according to the assessed needs and problems of the community viewed from the perspective of the community members</li>
                                <li>Cultivate values and culture toward attaining improved quality of life.</li>
                                <li>Provide avenues for psychosocial, environmental and livelihood programs</li>
                                <li>Assist in local, national and international development through linkages with NGO's LGU's and other sectors</li>
                                <li>Monitor and evaluate the impact of COP activities</li>
                                <li>Utilize research findings for innovation and change in community lifestyles thereby improving quality of life.</li>
                            </ul>
                            </div>
                        </section>

                        <!-- History Section -->
                        <section class="history-section">
                            <div class="container">
                                <h2>History of College</h2>
                                <p class="history-subtitle">Tracing our journey from humble beginnings to becoming a beacon of community service and social responsibility</p>
                            <div class="history-content">
                                <p>The Community Outreach Department of the Perpetual Help System Laguna began approximately thirty years ago to answer the need for required community exposure of its students. When the College opened in 1976, the surrounding area was rural and underdeveloped. There was an acute need for health services and other community programs particularly in the areas seasonally isolated by the rain swollen Biñan River.</p>
                                
                                <p>In 1984, the offering of programs was enriched with a community-based curriculum. It adopted the framework of the government program geared towards delivery of basic health services in Primary Health Care. In 1979, the College of Medicine reinforced community consciousness through prescribed activities in Preventive Medicine. The opening of Allied Health Sciences in 1988 extended community services to far-flung areas outside the perimeters of UPHS for their rehabilitation programs.</p>
                                
                                <p>Early endeavors were confined to the application of community diagnoses and problem-solving techniques. Sporadic attempts to establish joint projects were difficult to sustain, particularly when the students involved in the initial effort moved on to the higher years. However, as more health-related courses were added, the programs gradually broadened which enabled the University to answer the needs of the wider community. The evolution of community programs gave more lasting educational benefits to those who were exposed early on a long-term basis from different perspectives.</p>
                                
                                <p>Primary Health Care aimed to attain an acceptable level of health for all by the year 2000. This global goal was addressed by all professional disciplines. Dr. Jose G. Tamayo, the founder of the UPHS launched ROPES (Rainbow Outreach Project and Extension Services) in September 1986.</p>
                                
                                <p>The project was geared to provide health and related services to the marginal communities of Biñan and surrounding towns of San Pedro, Calamba, Sta. Rosa, Cabuyao and Carmona and Gen. Mariano Alvarez in Cavite. Initially, the program had twin approaches – as a curriculum requirement for social awareness; and a vehicle for community involvement of the institution through its faculty.</p>
                                
                                <p>Gradually, the program became more comprehensive with the participation of other departments offering varied courses, the faculty and the non-teaching staff. This multidisciplinary approach embraced the theories of holism and humanism, views the community through the eyes of the individual and his family with the involvement of agencies from the private sectors as well as from the local or even, the national government.</p>
                            </div>
                            </div>
                        </section>
    </main>

    <style>
    /* Remove body padding to eliminate whitespace */
    body {
        padding: 0 !important;
        margin: 0 !important;
    }
    
    /* Add top padding to main content to account for fixed header */
    .main-content {
        padding-top: 100px;
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
    
    /* COD Styles with UPHSL Branding */
    .intro-section {
        background: linear-gradient(135deg, rgba(44, 90, 160, 0.9), rgba(255, 198, 62, 0.9)), url('<?php echo $base_path; ?>assets/images/FACADE.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: white;
        padding: 0.5rem 0 2rem 0;
        margin: 0;
        position: relative;
        overflow: hidden;
    }

    .intro-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .intro-content {
        text-align: center;
        max-width: 1000px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .intro-logo {
        margin-bottom: 1rem;
        padding-top: 1rem;
    }

    .intro-logo img {
        width: 150px;
        height: 150px;
        object-fit: contain;
        filter: brightness(1.1);
        transition: transform 0.3s ease;
    }

    .intro-logo img:hover {
        transform: scale(1.05);
    }

    .intro-content h2 {
        font-size: 2.2rem;
        margin-bottom: 0.8rem;
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


    .intro-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.7rem 1.5rem;
        text-decoration: none;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    .btn-primary {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .btn-primary:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .btn-outline {
        background: transparent;
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    .btn-outline:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.8);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .mvp-section {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 4rem 0;
        position: relative;
        overflow: hidden;
    }

    .mvp-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        opacity: 0.3;
    }

    .mvp-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        position: relative;
        z-index: 2;
    }

    .mvp-card {
        background: rgba(255, 255, 255, 0.1);
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .mvp-card:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-3px);
    }

    .mvp-card h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        font-weight: 700;
        color: white;
    }

    .mvp-card p {
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
    }

    .objectives-section {
        padding: 4rem 0;
        background: #f8f9fa;
    }

    .objectives-section h2 {
        color: var(--primary-color);
        font-size: 2.5rem;
        margin-bottom: 1rem;
        text-align: center;
        font-weight: 700;
    }

    .objectives-subtitle {
        text-align: center;
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 3rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .objectives-list {
        list-style: none;
        padding: 0;
        max-width: 800px;
        margin: 0 auto;
    }

    .objectives-list li {
        background: white;
        margin-bottom: 1rem;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        padding-left: 3rem;
        transition: all 0.3s ease;
    }

    .objectives-list li:hover {
        transform: translateX(5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .objectives-list li::before {
        content: '✓';
        position: absolute;
        left: 1rem;
        top: 1.5rem;
        color: var(--primary-color);
        font-weight: bold;
        font-size: 1.2rem;
        background: rgba(44, 90, 160, 0.1);
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .history-section {
        padding: 4rem 0;
        background: white;
    }

    .history-section h2 {
        color: var(--primary-color);
        font-size: 2.5rem;
        margin-bottom: 1rem;
        text-align: center;
        font-weight: 700;
    }

    .history-subtitle {
        text-align: center;
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 3rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .history-content {
        background: #f8f9fa;
        padding: 3rem;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        max-width: 900px;
        margin: 0 auto;
        position: relative;
    }

    .history-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-color);
        border-radius: 2px;
    }

    .history-content p {
        color: #666;
        line-height: 1.7;
        margin-bottom: 1.5rem;
        font-size: 1rem;
        text-align: justify;
    }

    .history-content p:last-child {
        margin-bottom: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .intro-section {
            padding: 0.3rem 0 1.5rem 0;
        }

        .intro-logo img {
            width: 120px;
            height: 120px;
        }

        .intro-content h2 {
            font-size: 1.8rem;
        }

        .intro-description {
            font-size: 0.8rem;
            margin-bottom: 1.2rem;
        }


        .intro-actions {
            gap: 0.6rem;
        }

        .btn {
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
        }

        .objectives-section h2,
        .history-section h2 {
            font-size: 2.2rem;
        }

        .mvp-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .mvp-card {
            padding: 2rem;
        }

        .history-content {
            padding: 2.5rem;
        }

        .objectives-list li {
            padding: 1.5rem;
            padding-left: 3.5rem;
        }

        .objectives-list li::before {
            left: 1rem;
            top: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .intro-content h2 {
            font-size: 2rem;
        }

        .objectives-section h2,
        .history-section h2 {
            font-size: 1.8rem;
        }

        .mvp-card {
            padding: 1.5rem;
        }

        .history-content {
            padding: 2rem;
        }
    }
    </style>

<?php
// Include footer
include '../app/includes/footer.php';
?>
