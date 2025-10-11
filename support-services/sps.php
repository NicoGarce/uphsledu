<?php
/**
 * UPHSL Student Personnel Services Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about Student Personnel Services and guidance programs at UPHSL
 */

$page_title = "Student Personnel Services";
$base_path = '../';

// Add base tag for clean URLs to fix asset paths
if (strpos($_SERVER['REQUEST_URI'], '.php') === false) {
    echo '<base href="../">';
}

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

.intro-logo {
    margin-bottom: 1rem;
    padding-top: 0.5rem;
}

.intro-logo img {
    width: 180px;
    height: 180px;
    object-fit: contain;
    filter: brightness(1.1);
    transition: transform 0.3s ease;
}

.intro-content h2 {
    font-size: 2.2rem;
    margin-bottom: 0.8rem;
    margin-top: 0.5rem;
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
    .intro-logo img {
        width: 140px;
        height: 140px;
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

/* Admission and Discipline Container */
.admission-discipline-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin: 3rem 0;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    align-items: start;
}

/* Section Wrapper */
.admission-section,
.discipline-section {
    display: flex;
    flex-direction: column;
    height: 100%;
}

/* Admission Notice */
.admission-notice {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 2.5rem;
    border-radius: 20px;
    text-align: center;
    margin: 0;
    box-shadow: 0 8px 30px rgba(44, 90, 160, 0.2);
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 300px;
}

.admission-notice h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.admission-notice p {
    font-size: 1.1rem;
    line-height: 1.6;
    margin: 0;
}

/* Discipline Section */
.discipline-section {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(44, 90, 160, 0.1);
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 300px;
}

.handbook-section {
    margin: 0;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.handbook-section h3 {
    text-align: center;
    margin-bottom: 1.5rem;
    color: var(--primary-color);
    font-size: 1.5rem;
    font-weight: 700;
}

.handbook-container {
    display: flex;
    justify-content: center;
    align-items: center;
    max-width: 100%;
    margin: 0;
    flex: 1;
}

.handbook-image-container {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.2rem;
    width: 100%;
}

.handbook-image {
    max-width: 100%;
    width: 100%;
    max-width: 400px;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.handbook-image:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.18);
}

.handbook-title {
    color: var(--primary-color);
    font-weight: 600;
    font-size: 1.2rem;
    margin: 0;
}

.handbook-download-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    padding: 0.8rem 1.5rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(44, 90, 160, 0.25);
    margin-top: 0.5rem;
}

.handbook-download-btn:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(44, 90, 160, 0.35);
    text-decoration: none;
    color: white;
}

.handbook-download-btn i {
    font-size: 1rem;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .handbook-container {
        max-width: 100%;
        padding: 0 1rem;
    }
    
    .handbook-image {
        width: 100%;
        max-width: 400px;
    }
    
    .handbook-title {
        font-size: 1.3rem;
    }
    
    .handbook-download-btn {
        padding: 0.8rem 1.5rem;
        font-size: 0.9rem;
    }
}

/* Offense Categories */
.offense-categories {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin: 3rem 0;
    max-width: 1400px;
    margin-left: auto;
    margin-right: auto;
}

.offense-category {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 2px solid var(--primary-color);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.offense-category::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.offense-category:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(44, 90, 160, 0.15);
    border-color: var(--secondary-color);
}

.offense-category h4 {
    color: var(--primary-color);
    font-size: 1.1rem;
    margin-bottom: 1.2rem;
    font-weight: 700;
    line-height: 1.4;
    padding-right: 1rem;
}

.offense-category ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.offense-category ul li {
    color: #444;
    line-height: 1.6;
    margin-bottom: 0.8rem;
    padding-left: 1.8rem;
    position: relative;
    font-size: 0.85rem;
    font-weight: 500;
    transition: color 0.2s ease;
}

.offense-category ul li:hover {
    color: var(--primary-color);
}

.offense-category ul li::before {
    content: "▶";
    color: var(--secondary-color);
    font-weight: bold;
    position: absolute;
    left: 0;
    top: 0;
    font-size: 0.8rem;
    transition: transform 0.2s ease;
}

.offense-category ul li:hover::before {
    transform: translateX(3px);
}

/* Disciplinary Sanctions */
.sanctions-section {
    background: white;
}

.sanctions-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
    margin: 2rem 0;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.sanction-item {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 1.5rem;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.sanction-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.sanction-item p {
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0;
    font-weight: 600;
}

/* Evaluation Section */
.evaluation-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.evaluation-notice {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    border-left: 4px solid var(--secondary-color);
    margin: 2rem 0;
}

.evaluation-notice p {
    color: #666;
    line-height: 1.6;
    margin: 0;
    font-size: 1rem;
}

/* Tablet Responsive */
@media (max-width: 1024px) {
    .admission-discipline-container {
        grid-template-columns: 1fr;
        gap: 2rem;
        padding: 0 1rem;
    }
    
    .admission-section,
    .discipline-section {
        min-height: auto;
    }
    
    .admission-notice {
        min-height: 250px;
    }
    
    .discipline-section {
        padding: 1.5rem;
    }
    
    .offense-categories {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        max-width: 100%;
        padding: 0 1rem;
    }
    
    .sanctions-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        padding: 0 1rem;
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }
    
    .admission-discipline-container {
        grid-template-columns: 1fr;
        gap: 1.5rem;
        padding: 0 1rem;
    }
    
    .admission-section,
    .discipline-section {
        min-height: auto;
    }
    
    .admission-notice {
        padding: 2rem;
        min-height: 200px;
    }
    
    .discipline-section {
        padding: 1.5rem;
    }
    
    .offense-categories {
        grid-template-columns: 1fr;
        gap: 1rem;
        margin: 2rem 0;
        padding: 0 1rem;
    }
    
    .offense-category {
        padding: 1.2rem;
    }
    
    .offense-category h4 {
        font-size: 1rem;
        margin-bottom: 1rem;
    }
    
    .offense-category ul li {
        font-size: 0.8rem;
        margin-bottom: 0.7rem;
        padding-left: 1.5rem;
    }
    
    .sanctions-grid {
        grid-template-columns: 1fr;
        padding: 0 1rem;
    }
    
    .sanction-item {
        padding: 1.2rem;
    }
    
    .sanction-item p {
        font-size: 0.85rem;
    }
}
</style>

<main class="main-content">
    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-logo">
                    <img src="<?php echo $base_path; ?>assets/images/sps/Picture1.png" alt="Student Personnel Services Logo">
                </div>
                <h2>Guidance & Admission</h2>
                <h3 style="font-size: 1.2rem; font-weight: 500; margin-bottom: 1rem; opacity: 0.9;">Student Personnel Services</h3>
                <p class="intro-description">The Student Personnel Services (SPS) department is dedicated to providing comprehensive guidance and admission services to all students. We ensure proper admission procedures, maintain campus discipline, and support student activities while upholding the highest standards of academic integrity and moral conduct.</p>
                <div class="intro-actions">
                    <a href="#admission" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i>
                        Admission Information
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Admission and Discipline Section -->
    <section class="content-section">
        <div class="container">
            <div class="admission-discipline-container">
                <!-- Admission Notice -->
                <div class="admission-section">
                    <h2 class="section-title">Admission</h2>
                    <div class="admission-notice">
                        <h3>Important Notice</h3>
                        <p>ALL students who have yet to submit their admission credentials may swing by the SPS Office first for appropriate instructions.</p>
                    </div>
                </div>
                
                <!-- Discipline Section -->
                <div class="discipline-section">
                    <h2 class="section-title">Discipline</h2>
            
            <!-- The Perpetualite Handbook -->
            <div class="handbook-section">
                <h3>The Perpetualite Handbook</h3>
                <div class="handbook-container">
                    <div class="handbook-image-container">
                        <img src="<?php echo $base_path; ?>assets/images/sps/Handbook.png" alt="Handbook 2023" class="handbook-image">
                        <h4 class="handbook-title">Handbook 2023</h4>
                        <a href="<?php echo $base_path; ?>support-services/sps/Handbook 2023.pdf" target="_blank" class="handbook-download-btn">
                            <i class="fas fa-download"></i>
                            Download Full PDF
                        </a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Major Offenses Section -->
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Major Offense</h2>
            
            <div class="offense-categories">
                <div class="offense-category">
                    <h4>Offenses disrupting campus peace and order, security and safety.</h4>
                    <ul>
                        <li>Fistfight/Mauling</li>
                        <li>Carrying explosive, firearms or deadly weapons, etc.</li>
                        <li>Lending, borrowing, tampering with and on forging certificate of matriculation, ID, and the like.</li>
                    </ul>
                </div>

                <div class="offense-category">
                    <h4>Offenses maligning public decency, good customs and morals.</h4>
                    <ul>
                        <li>Gambling</li>
                        <li>Public Display of Affection</li>
                        <li>Cheating in Examinations</li>
                        <li>Entering University premises under the influence of liquor or being in a state of drunkenness</li>
                        <li>Use of and/or distributing prohibited drugs</li>
                        <li>Sexual Harassment</li>
                        <li>Cyber Bullying</li>
                    </ul>
                </div>

                <div class="offense-category">
                    <h4>Offenses detrimental to the property, rights and interests of the University, Administrative officials, personnel, faculty member and student.</h4>
                    <ul>
                        <li>Act of Vandalism</li>
                    </ul>
                </div>

                <div class="offense-category">
                    <h4>Offenses against the sanctity of school records and official paper and documents. This includes as destroying, tampering with or falsifying school records.</h4>
                    <ul>
                        <li>Counterfeit Forgery</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Disciplinary Sanctions Section -->
    <section class="content-section sanctions-section">
        <div class="container">
            <h2 class="section-title">Disciplinary Sanctions</h2>
            
            <div class="sanctions-grid">
                <div class="sanction-item">
                    <p>Suspension for a period not less than one semester</p>
                </div>
                <div class="sanction-item">
                    <p>Suspension for one semester</p>
                </div>
                <div class="sanction-item">
                    <p>Suspension for two semester</p>
                </div>
                <div class="sanction-item">
                    <p>Exclusion/ Dismissal or dropping from the University</p>
                </div>
                <div class="sanction-item">
                    <p>Expulsion</p>
                </div>
            </div>

            <div style="text-align: center; margin: 2rem 0; padding: 1.5rem; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 10px;">
                <p style="color: #856404; font-weight: 600; margin: 0;">
                    A major offense OR 3 minor offenses would warrant non-issuance of Certificate of Good Moral Character.
                </p>
            </div>
        </div>
    </section>

    <!-- Evaluation Section -->
    <section class="content-section evaluation-section">
        <div class="container">
            <h2 class="section-title">Evaluation of Student Activities</h2>
            
            <div class="evaluation-notice">
                <p>A hard copy of the ESDO approved concept paper is a pre-requisite for the Google form evaluation link from SPS.</p>
            </div>
        </div>
    </section>
</main>

<?php include '../app/includes/footer.php'; ?>
