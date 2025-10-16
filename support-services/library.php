<?php
/**
 * UPHSL College Library Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the College Library services and resources
 */

$base_path = '../';
$page_title = "University Library";
include '../app/includes/header.php';
?>

<style>
body {
    padding: 0 !important;
    margin: 0 !important;
}

.main-content {
    padding-top: 100px; /* Base padding for header */
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

/* Intro Section */
.intro-section {
    background: linear-gradient(135deg, rgba(44, 90, 160, 0.9), rgba(255, 198, 62, 0.9)), url('<?php echo $base_path; ?>assets/images/FACADE.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: white;
    padding: 2rem 0;
    margin: 0;
    position: relative;
    overflow: hidden;
}

.intro-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 1rem;
}

.intro-logo {
    margin-bottom: 1.5rem;
}

.intro-logo img {
    width: 150px;
    height: 150px;
    object-fit: contain;
    filter: brightness(1.1);
    transition: transform 0.3s ease;
}

.intro-content h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
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

/* Content Sections */
.content-section {
    padding: 4rem 0;
    background: white;
}

.content-section:nth-child(even) {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 1rem;
    position: relative;
}

.section-title::after {
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

.section-subtitle {
    text-align: center;
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Mission Vision Section */
.mission-vision-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.mv-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    max-width: 1000px;
    margin: 0 auto;
}

.mv-card {
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    text-align: center;
    border-left: 4px solid var(--primary-color);
}

.mv-card h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.mv-card p {
    color: #666;
    line-height: 1.6;
    font-size: 1rem;
}

/* Online Services Section */
.online-services-section {
    background: white;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.service-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(44, 90, 160, 0.1);
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
}

.service-image {
    width: 120px;
    height: 80px;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border-radius: 10px;
    padding: 10px;
}

.service-image img {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
    filter: brightness(1.1);
}

.service-card h4 {
    color: var(--primary-color);
    font-size: 1.3rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.service-card p {
    color: #666;
    line-height: 1.6;
    font-size: 0.95rem;
}

/* Quality Objectives Section */
.quality-objectives-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.objectives-list {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.objectives-list ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.objectives-list ul li {
    color: #444;
    line-height: 1.7;
    margin-bottom: 1rem;
    padding-left: 2rem;
    position: relative;
    font-size: 1rem;
    font-weight: 500;
}

.objectives-list ul li::before {
    content: "✓";
    color: var(--secondary-color);
    font-weight: bold;
    position: absolute;
    left: 0;
    top: 0;
    font-size: 1.2rem;
}

/* History Section */
.history-section {
    background: white;
}

.history-content {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-left: 4px solid var(--secondary-color);
}

.history-content p {
    color: #666;
    line-height: 1.6;
    font-size: 1rem;
    margin: 0;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .intro-content h2 {
        font-size: 2rem;
    }
    
    .intro-description {
        font-size: 1rem;
    }
    
    .mv-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .services-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .service-card {
        padding: 1.5rem;
    }
    
    .objectives-list,
    .history-content {
        padding: 2rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
}
</style>

<main class="main-content">
    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-logo">
                    <img src="<?php echo $base_path; ?>assets/images/library/logo.png" alt="University Library Logo">
                </div>
                <h2>University Library</h2>
                <p class="intro-description">The Library Services Department manages and provides seamless access to both print and online scholarly information; offers reference services, research assistance, and information literacy instruction; provides excellent facility and equipment. Licensed, professional, and computer-savvy Librarians are always ready to assist library users.</p>
            </div>
        </div>
    </section>

    <!-- Mission and Vision Section -->
    <section class="content-section mission-vision-section">
        <div class="container">
            <h2 class="section-title">Mission & Vision</h2>
            <div class="mv-container">
                <div class="mv-card">
                    <h3>Vision</h3>
                    <p>A dominant university library provider in global community</p>
                </div>
                <div class="mv-card">
                    <h3>Mission</h3>
                    <p>Committed to provide users with comprehensive resources and services as tools for independent critical thinking and life-long learning.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Online Services Section -->
    <section class="content-section online-services-section">
        <div class="container">
            <h2 class="section-title">Online Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-image">
                        <img src="<?php echo $base_path; ?>assets/images/support-services/college-library/img/olservices/uphsl-opac.jpg" alt="OPAC">
                    </div>
                    <h4>OPAC - Online Public Access Catalogue</h4>
                    <p>This feature-rich Online LMS, there is never a need to worry on valuable data. All of your data, including archival data, remains instantly accessible all the time—with no system slowdown. Up-to-date information on books, members and status reports is just a click away.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">
                        <img src="<?php echo $base_path; ?>assets/images/support-services/college-library/img/olservices/uphsl-ebsco.png" alt="EBSCOhost">
                    </div>
                    <h4>EBSCOhost</h4>
                    <p>A powerful online reference system accessible via internet. It offers a variety of proprietary full-text databases and popular databases from leading information providers. The comprehensive databases range from general reference collections to specially designed, subject-specific databases for public, academic, medical, corporate, and school libraries.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">
                        <img src="<?php echo $base_path; ?>assets/images/support-services/college-library/img/olservices/uphsl-pej.png" alt="Philippine E-Journals">
                    </div>
                    <h4>Philippine E-Journals</h4>
                    <p>An expanding collection of academic journals that are made accessible globally through a single Web-based platform. It is hosted by C&E Publishing, Inc., a premier educational publisher in the Philippines and a leader in the distribution of integrated information-based solutions.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">
                        <img src="<?php echo $base_path; ?>assets/images/support-services/college-library/img/olservices/starbooks.png" alt="Starbooks">
                    </div>
                    <h4>Starbooks</h4>
                    <p>State of the art facilities to access science and technology information via the STOO portals. A technically-qualified staff will be on hand to assist STARBOOKS users on-site while an online Librarian's HelpDesk service will also be available to answer queries.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">
                        <img src="<?php echo $base_path; ?>assets/images/support-services/college-library/img/olservices/escra.png" alt="eSCRA Online">
                    </div>
                    <h4>eSCRA Online</h4>
                    <p>A Complete Decision from 1901 to the Present. Online Library, Always Updated and Available. Search and Browse Modes Makes it Fast and Intuitive. Smart Searching through Intelligent Fields. TrueCite Technology gives you the same look and feel as the book.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">
                        <img src="<?php echo $base_path; ?>assets/images/support-services/college-library/img/olservices/turnitin.png" alt="Turnitin">
                    </div>
                    <h4>Turnitin</h4>
                    <p>An expanding collection of academic journals that are made accessible globally through a single Web-based platform. It is hosted by C&E Publishing, Inc., a premier educational publisher in the Philippines and a leader in the distribution of integrated information-based solutions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quality Objectives Section -->
    <section class="content-section quality-objectives-section">
        <div class="container">
            <h2 class="section-title">Quality Objectives</h2>
            <div class="objectives-list">
                <ul>
                    <li>To develop better access to resources and services.</li>
                    <li>To collaborate with all stakeholders on their learning needs and experience.</li>
                    <li>To assist the faculty in updating references for their course syllabi.</li>
                    <li>To disseminate new services and promote their use.</li>
                    <li>To use feedback for continual improvement.</li>
                    <li>To expand proactive library user education and information literacy program.</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- History Section -->
    <section class="content-section history-section">
        <div class="container">
            <h2 class="section-title">History</h2>
            <div class="history-content">
                <p>Through the STARBOOKS Program, Filipinos can have access to scientific information for their research needs or simply satisfy their curious minds. Eventually, it is hoped that (1) it will create interest in the field of Science and Technology which may increase the number of Filipinos enrolling in S&T courses, (2) encourage great and curious minds to develop new ideas - inventions and innovations, and (3) inspire one's capacity for entrepreneurship and research for socio-economic development.</p>
            </div>
        </div>
    </section>
</main>

<?php include '../app/includes/footer.php'; ?>
