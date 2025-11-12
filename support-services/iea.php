<?php
/**
 * UPHSL International & External Affairs Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about international programs and external affairs at UPHSL
 */
session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
$base_path = '../';
$page_title = "International & External Affairs - UPHSL";

// Base path for assets

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
    padding: 0.5rem 0 2rem 0;
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


.intro-content h2 {
    font-size: 2.2rem;
    margin-bottom: 0.8rem;
    margin-top: 2rem;
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
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Mobile responsive styles for intro section */
@media (max-width: 768px) {
    .intro-section {
        padding: 0.3rem 0 1.5rem 0;
    }
    .intro-content h2 {
        font-size: 1.8rem;
    }
    .intro-description {
        font-size: 0.8rem;
        margin-bottom: 1.2rem;
    }
    .btn {
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
    }
}

/* Mission Vision Section */
.mvp-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.mvp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.mvp-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-top: 4px solid var(--primary-color);
}

.mvp-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.mvp-card h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.mvp-card p {
    color: #666;
    line-height: 1.6;
    font-size: 0.95rem;
}

/* News Section */
.news-section {
    padding: 4rem 0;
    background: white;
}

.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.news-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.news-card-content {
    padding: 1.5rem;
}

.news-card h4 {
    color: var(--primary-color);
    font-size: 1.2rem;
    margin-bottom: 0.8rem;
    font-weight: 600;
    line-height: 1.4;
}

.news-card p {
    color: #666;
    line-height: 1.6;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.news-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: color 0.3s ease;
}

.news-link:hover {
    color: var(--secondary-color);
}

/* Services Section */
.services-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.service-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-left: 4px solid var(--primary-color);
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.service-card h4 {
    color: var(--primary-color);
    font-size: 1.3rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.service-card ul {
    list-style: none;
    padding: 0;
}

.service-card ul li {
    color: #666;
    line-height: 1.6;
    margin-bottom: 0.8rem;
    padding-left: 1.5rem;
    position: relative;
    font-size: 0.9rem;
}

.service-card ul li::before {
    content: "•";
    color: var(--primary-color);
    font-weight: bold;
    position: absolute;
    left: 0;
}

/* Offers Section */
.offers-section {
    padding: 4rem 0;
    background: white;
}

.offers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.offer-card {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}

.offer-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.offer-card h4 {
    font-size: 1.3rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.offer-card p {
    line-height: 1.6;
    opacity: 0.9;
    font-size: 0.9rem;
}

/* FAQ Section */
.faq-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.faq-list {
    max-width: 800px;
    margin: 2rem auto 0;
}

.faq-item {
    background: white;
    margin-bottom: 1rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.faq-question {
    padding: 1.5rem;
    background: var(--primary-color);
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
    margin: 0;
}

.faq-question:hover {
    background: var(--secondary-color);
}

.faq-answer {
    padding: 1.5rem;
    color: #666;
    line-height: 1.6;
    font-size: 0.9rem;
}

/* Quality Objectives Section */
.objectives-section {
    padding: 4rem 0;
    background: white;
}

.objectives-list {
    max-width: 800px;
    margin: 2rem auto 0;
}

.objective-item {
    background: white;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-left: 4px solid var(--primary-color);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.objective-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

.objective-item p {
    color: #666;
    line-height: 1.6;
    margin: 0;
    font-size: 0.9rem;
}

/* Accomplishments Section */
.accomplishments-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.accomplishments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.accomplishment-item {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-top: 3px solid var(--primary-color);
}

.accomplishment-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

.accomplishment-item p {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0;
}

/* Section Titles */
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

/* Mobile Responsive */
@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }
    
    .mvp-grid,
    .news-grid,
    .services-grid,
    .offers-grid,
    .accomplishments-grid {
        grid-template-columns: 1fr;
    }
    
    .mvp-card,
    .service-card,
    .offer-card {
        padding: 1.5rem;
    }
    
    .news-card-content {
        padding: 1rem;
    }
}
</style>

<main class="main-content">
    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <h2>International & External Affairs</h2>
                <p class="intro-description">The International and External Affairs Office (IEAO) includes developing and implementing initiatives that promote international cooperation and collaboration, managing international partnerships, and alliances. It organizes and promotes international events, as well as handling visa and other administrative issues related to the concerns of international students and partners.</p>
                <div class="intro-actions">
                    <a href="#services" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i>
                        Our Services
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- News Carousel Section -->
    <?php
    $categoryId = 'International & External Affairs'; // Pass category name, component will look it up
    $sectionTitle = 'International & External Affairs News & Updates';
    $sectionDescription = 'Stay updated with the latest news and announcements from the International & External Affairs department.';
    $isSupportService = true; // Use horizontal layout for support services
    include '../app/includes/news-carousel.php';
    ?>

    <!-- Mission & Vision Section -->
    <section class="mvp-section">
        <div class="container">
            <h2 class="section-title">Mission & Vision</h2>
            <div class="mvp-grid">
                <div class="mvp-card">
                    <h3>Vision</h3>
                    <p>The International and External Affairs Office envisions in building enduring relationships to local and international partners by promoting advocacies, linkages and service programs beneficial in attaining the UPHSL vision, mission and objectives.</p>
                </div>
                <div class="mvp-card">
                    <h3>Mission</h3>
                    <p>An integrated organization driven by advocacy, linkage and service programs to achieve results. As such, the International and External Affairs Office endeavors to advance UPHSL advocacies, foster local and international linkages with organizations, industries, agencies, companies, and educational institutions both public and private by sharing its resources, skills and expertise to contribute to the advancement of the global society.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- News & Updates Section -->
    <section class="news-section">
        <div class="container">
            <h2 class="section-title">News & Updates</h2>
            <div class="news-grid">
                <div class="news-card">
                    <div class="news-card-content">
                        <h4>Commission on Higher Education (CHED) CALABARZON IZN Awards</h4>
                        <p>The University of Perpetual Help System Laguna (UPHSL) emerged as one of the outstanding universities emerged among State Universities and Colleges (SUCs) and private Higher Education Institutions (HEIs) in Region IV-A during the "2023 Commission on Higher Education (CHED) CALABARZON IZN Awards".</p>
                        <a href="#" class="news-link">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="news-card">
                    <div class="news-card-content">
                        <h4>29th National Crime Prevention Week WITH NAPOLCOM</h4>
                        <p>The University of Perpetual Help System Laguna with the College of Criminology and the Office of International and External Affairs joins the NAPOLCOM in celebrating the 29th National Crime Prevention Week through series of lectures delivered by the University's external partners to the students of the institution.</p>
                        <a href="#" class="news-link">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="news-card">
                    <div class="news-card-content">
                        <h4>5th Leadership and Management Perspectives (LAMP) in Higher Education</h4>
                        <p>Dr. Josefa G. Carrillo, the Director for International and External Affairs of the University of Perpetual Help System Laguna (UPHSL) took the helm in representing the university in the 5th Leadership and Management Perspectives (LAMP) in Higher Education Summit held at Manila Central University in Caloocan City, Metro Manila.</p>
                        <a href="#" class="news-link">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="news-card">
                    <div class="news-card-content">
                        <h4>Annual Institutional Job Fair 2023</h4>
                        <p>The University of Perpetual Help System Laguna (UPHSL) hosted its highly anticipated Annual Job Fair. Dr. Josefa G. Carrillo, the Director for International and External Affairs, took the helm of this significant event, in a collaborative effort with the dedicated Alumni Department.</p>
                        <a href="#" class="news-link">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services-section">
        <div class="container">
            <h2 class="section-title">Our Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <h4>Student Visa Conversion</h4>
                    <ul>
                        <li>Joint letter addressed to the commissioner from the applicant and the authorized representative of UPHSL using the school letterhead with a dry seal from the Office of International and External Affairs (IEAO)</li>
                        <li>2 Duly accomplished CGAF (BI Form 2014-00-003 Rev 0) downloaded from the Bureau of Immigration website</li>
                        <li>Photocopy of Passport bio-page and latest admission with valid authorized stay (4 copies)</li>
                        <li>Bureau of Quarantine (BOQ) stamp; (from Bureau of Quarantine Manila)</li>
                        <li>Notice of Acceptance of the applicant bearing a clear impression of the school's official dry seal from the Registrar's Office of the University</li>
                        <li>Endorsement addressed to the commissioner from the school for the conversion of the applicant's status, signed by the School Registrar</li>
                        <li>CHED Endorsement for transfer and shifting of course, if applicable</li>
                        <li>National Bureau of Investigation (NBI) Clearance, if the application is filed (6) months or more from the date of the first arrival in the Philippines</li>
                        <li>Photocopy of Bureau of Immigration school accreditation ID of the school representative</li>
                    </ul>
                </div>
                <div class="service-card">
                    <h4>Visa Conversion Process</h4>
                    <ul>
                        <li>Submission of the documents of the foreign student (FS) by the school representative to the Bureau of Immigration – Student Visa Section for application of conversion and payment of the required fees (conversion and ACR/I-Card). Validation will take 3 to 4 weeks.</li>
                        <li>Claiming of the approved conversion of tourist visa to student visa and booking of the schedule of FS for personal appearance intended for biometrics in the Bureau of Immigration by the school representative. Scheduled appearance will be given by the Bureau of Immigration.</li>
                        <li>Application of ACR/I-Card of the FS by the school representative. This process will take 1 to 2 months.</li>
                        <li>Claiming of the ACR/I-Card of the foreign student.</li>
                    </ul>
                </div>
                <div class="service-card">
                    <h4>Required Documents</h4>
                    <ul>
                        <li>3 pcs 2x2 picture w/ white background</li>
                        <li>Original Passport</li>
                        <li>Original copy and duplicate of individual Medical Examinations if already done in another clinic with original signature of the concerned doctor/specialist</li>
                        <li>Physical Appearance and Examination in the Bureau of Quarantine</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Offers Section -->
    <section class="offers-section">
        <div class="container">
            <h2 class="section-title">What We Offer</h2>
            <div class="offers-grid">
                <div class="offer-card">
                    <h4>International On-the-Job Training</h4>
                    <p>Provides opportunity to the students to gain international experience by completing part of their education in a foreign host country and to foster mutual understanding and cooperation between the host country and the Philippines.</p>
                </div>
                <div class="offer-card">
                    <h4>Training Goals</h4>
                    <p>Broaden the access of deserving qualified students to higher learning and skills development through actual application.</p>
                </div>
                <div class="offer-card">
                    <h4>International Program Development</h4>
                    <p>Assistance for faculty members in proposal development and the identification of external funding. Enhancement of UPHSL Faculty Members' international expertise by assisting them in conducting international research or other kinds of academic creative activities.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <div class="faq-list">
                <div class="faq-item">
                    <h4 class="faq-question">Does UPHSL admit/accept transferees for undergraduate/graduate program from another Philippine school or school abroad?</h4>
                    <div class="faq-answer">
                        <p>YES. The University is accepting transferees from another school provided that they will secure CHED endorsement.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <h4 class="faq-question">Where can I get information on courses?</h4>
                    <div class="faq-answer">
                        <p>University of Perpetual Help System Laguna has a wide range of courses at various levels - Bachelors, Masters, and Doctorate. For information, please browse the programs offered at uphsl.edu.ph.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <h4 class="faq-question">Can an international student take an admission exam any time of the year?</h4>
                    <div class="faq-answer">
                        <p>Yes, provided he takes the exam during the admission exam schedule in time for the enrollment schedule for first or second semester or summer.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <h4 class="faq-question">How do I get a visa?</h4>
                    <div class="faq-answer">
                        <p>After meeting all the requirements (academic, language and documentary) UPHSL Registrar's Office will issue a Notice of Acceptance and Endorsement Letter needed for converting current visa to student visa. The Liaison Officer of UPHSL will assist students at the Bureau of Immigration.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <h4 class="faq-question">How long does it take to get a student visa?</h4>
                    <div class="faq-answer">
                        <p>Processing of student visa conversion takes at most 2-3 months provided documents are complete and duly authenticated by the Philippine Foreign Service Post in the applicant's country of origin. The UPHSL Liaison officer of the International and External Affairs Office (IEAO) facilitates student visa processing.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <h4 class="faq-question">If I am below 18 years old, am I required to have a student visa?</h4>
                    <div class="faq-answer">
                        <p>A Special Study Permit (SSP) from the Philippine Bureau of Immigration is required for a student below 18 years old. The UPHSL Liaison Officer of the IEAO will assist you in securing the SSP.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quality Objectives Section -->
    <section class="objectives-section">
        <div class="container">
            <h2 class="section-title">Quality Objectives</h2>
            <div class="objectives-list">
                <div class="objective-item">
                    <p>Delivering programs and services that can help the academic community of UPHSL in its international and external relations</p>
                </div>
                <div class="objective-item">
                    <p>Provide a well-defined and functional link between UPHSL and institutional partners</p>
                </div>
                <div class="objective-item">
                    <p>Establish partnerships with local and international Higher Educational Institutions (HEI), industry, government agencies, and other groups for knowledge generation and dissemination</p>
                </div>
                <div class="objective-item">
                    <p>Create partnerships with the adopted community and other groups that are productive and mutually beneficial to the University to foster academe-community collaborations</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Accomplishments Section -->
    <section class="accomplishments-section">
        <div class="container">
            <h2 class="section-title">Key Accomplishments</h2>
            <div class="accomplishments-grid">
                <div class="accomplishment-item">
                    <p>1ST LAGUNA EMPLOYMENT SUMMIT "WORKING TOWARDS A RESPONSIVE EMPLOYABILITY FRAMEWORK"</p>
                </div>
                <div class="accomplishment-item">
                    <p>2nd Industry Partners' Recognition Day</p>
                </div>
                <div class="accomplishment-item">
                    <p>AFS Host School & Host Family Orientation</p>
                </div>
                <div class="accomplishment-item">
                    <p>Annual Institutional Job Fair 2019</p>
                </div>
                <div class="accomplishment-item">
                    <p>Arrival of AFS Scholars and Courtesy Call</p>
                </div>
                <div class="accomplishment-item">
                    <p>ASPELO 4th General Assembly & Launching of Employment Summit</p>
                </div>
                <div class="accomplishment-item">
                    <p>ASPELO and LES Technical Working Group Meeting</p>
                </div>
                <div class="accomplishment-item">
                    <p>Association of PESO Locators (ASPELO) General Assembly & 5th Anniversary</p>
                </div>
                <div class="accomplishment-item">
                    <p>Consortium of Teacher Education Institutions of the South (CoTEIs) "2nd Membership Assembly"</p>
                </div>
                <div class="accomplishment-item">
                    <p>Consortium of Teacher Education Institution of the South (CoTEIs) 3rd Board Meeting and Induction Rites</p>
                </div>
                <div class="accomplishment-item">
                    <p>External Affairs Office Facilitated the Sponsorship of UPHSL Takbo Saya 2018</p>
                </div>
                <div class="accomplishment-item">
                    <p>FAITH College 18th Foundation Year Re Transforming Ourselves</p>
                </div>
                <div class="accomplishment-item">
                    <p>First ANTENA Project Survey Regional Meeting CHED through International Affairs Staff</p>
                </div>
                <div class="accomplishment-item">
                    <p>Meeting with Chinese Visitors, Deans, and Support Services</p>
                </div>
                <div class="accomplishment-item">
                    <p>Meeting with DOST - FNRI Representatives</p>
                </div>
                <div class="accomplishment-item">
                    <p>Network of CALABARZON Educational Institutions, Inc. (NOCEI)</p>
                </div>
                <div class="accomplishment-item">
                    <p>NOCEI Strategic Planning and Board of Trustees Meeting</p>
                </div>
                <div class="accomplishment-item">
                    <p>PATLEPAM 23rd National Educators</p>
                </div>
                <div class="accomplishment-item">
                    <p>UPHSL Institutional Partners' Visit</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include '../app/includes/footer.php'; ?>
