<?php
/**
 * UPHSL About Us Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the University of Perpetual Help System Laguna
 */

session_start();
require_once 'app/config/database.php';
require_once 'app/config/paths.php';
require_once 'app/includes/functions.php';

// Set page title
$page_title = "About UPHSL";

// Use the automatically detected base path
$base_path = $GLOBALS['base_path'];

// Include header
include 'app/includes/header.php';
?>

<style>
.about-intro {
    background: #F8F8F8;
    padding: 6rem 0 4rem;
    position: relative;
    overflow: hidden;
}

.about-intro::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(28, 77, 161, 0.05) 0%, rgba(82, 123, 189, 0.05) 100%);
    z-index: 0;
}

.intro-content {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.intro-text h1 {
    font-size: 3.5rem;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    font-weight: 900;
    line-height: 1.2;
    position: relative;
}

.intro-text h1::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 100px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    border-radius: 2px;
}

.intro-text .tagline {
    font-size: 1.5rem;
    color: var(--secondary-color);
    font-weight: 600;
    margin-bottom: 2rem;
    font-style: italic;
}

.intro-text .description {
    font-size: 1.1rem;
    color: var(--text-dark);
    line-height: 1.8;
    margin-bottom: 2rem;
}

.intro-visual {
    position: relative;
    height: 400px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.intro-visual img, .intro-visual video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.intro-visual:hover img, .intro-visual:hover video {
    transform: scale(1.05);
}

.intro-visual::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(28, 77, 161, 0.3) 0%, rgba(82, 123, 189, 0.3) 100%);
    z-index: 1;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-top: 3rem;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
}

.stat-item {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-5px);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 900;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1rem;
    color: var(--text-light);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.about-content {
    padding: 4rem 0;
    background: #F8F8F8;
}

.content-section {
    margin-bottom: 4rem;
}

.section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-title {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-weight: 700;
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    border-radius: 2px;
}

.section-content {
    background: white;
    padding: 3rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    line-height: 1.8;
    font-size: 1.1rem;
    color: var(--text-dark);
}

.history-timeline {
    position: relative;
    margin-top: 3rem;
    padding-left: 2rem;
}

.history-timeline::before {
    content: '';
    position: absolute;
    left: 2rem;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border-radius: 2px;
}

.timeline-item {
    position: relative;
    margin-bottom: 3rem;
    padding-left: 4rem;
    opacity: 0;
    transform: translateX(-50px);
    animation: slideInFromLeft 0.8s ease-out forwards;
}

.timeline-item:nth-child(1) { animation-delay: 0.1s; }
.timeline-item:nth-child(2) { animation-delay: 0.2s; }
.timeline-item:nth-child(3) { animation-delay: 0.3s; }
.timeline-item:nth-child(4) { animation-delay: 0.4s; }
.timeline-item:nth-child(5) { animation-delay: 0.5s; }
.timeline-item:nth-child(6) { animation-delay: 0.6s; }
.timeline-item:nth-child(7) { animation-delay: 0.7s; }

.timeline-item::before {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 0.5rem;
    width: 20px;
    height: 20px;
    background: var(--primary-color);
    border-radius: 50%;
    border: 4px solid white;
    box-shadow: 0 0 0 4px var(--primary-color);
    z-index: 2;
}

.timeline-item::after {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 0.5rem;
    width: 20px;
    height: 20px;
    background: var(--secondary-color);
    border-radius: 50%;
    transform: scale(0);
    transition: transform 0.3s ease;
    z-index: 1;
}

.timeline-item:hover::after {
    transform: scale(1.2);
}

.timeline-card {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(28, 77, 161, 0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.timeline-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.timeline-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.timeline-card:hover::before {
    transform: scaleX(1);
}

.timeline-year {
    font-size: 1.4rem;
    font-weight: 900;
    color: var(--primary-color);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.timeline-year::before {
    content: '📅';
    font-size: 1.2rem;
}

.timeline-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1rem;
    line-height: 1.4;
}

.timeline-content {
    color: var(--text-dark);
    line-height: 1.7;
    font-size: 1.05rem;
}

.timeline-highlight {
    background: linear-gradient(135deg, rgba(28, 77, 161, 0.1), rgba(82, 123, 189, 0.1));
    padding: 1rem;
    border-radius: 10px;
    margin-top: 1rem;
    border-left: 4px solid var(--secondary-color);
    font-style: italic;
    color: var(--primary-color);
    font-weight: 500;
}

@keyframes slideInFromLeft {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.board-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.board-member {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease;
}

.board-member:hover {
    transform: translateY(-5px);
}

.member-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.member-position {
    font-size: 1rem;
    color: var(--secondary-color);
    font-weight: 600;
    margin-bottom: 1rem;
}

.member-qualifications {
    font-size: 0.9rem;
    color: var(--text-light);
    font-style: italic;
}

.campus-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.campus-item {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease;
}

.campus-item:hover {
    transform: translateY(-5px);
}

.campus-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.campus-location {
    font-size: 0.9rem;
    color: var(--text-light);
}

.philosophy-box {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 3rem;
    border-radius: 15px;
    text-align: center;
    margin: 2rem 0;
}

.philosophy-quote {
    font-size: 1.5rem;
    font-weight: 600;
    font-style: italic;
    margin-bottom: 1rem;
    line-height: 1.6;
}

.philosophy-author {
    font-size: 1.1rem;
    opacity: 0.9;
}

.quality-objectives {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.objective-item {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    border-top: 4px solid var(--secondary-color);
}

.objective-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.objective-list {
    list-style: none;
    padding: 0;
}

.objective-list li {
    padding: 0.5rem 0;
    color: var(--text-dark);
    position: relative;
    padding-left: 1.5rem;
}

.objective-list li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--secondary-color);
    font-weight: bold;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .intro-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .intro-text h1 {
        font-size: 2.5rem;
    }
    
    .intro-text .tagline {
        font-size: 1.2rem;
    }
    
    .intro-text .description {
        font-size: 1rem;
    }
    
    .intro-visual {
        height: 300px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        max-width: 100%;
    }
    
    .stat-item {
        padding: 1.5rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .stat-label {
        font-size: 0.9rem;
    }
    
    .section-content {
        padding: 2rem;
    }
    
    .board-grid {
        grid-template-columns: 1fr;
    }
    
    .campus-grid {
        grid-template-columns: 1fr;
    }
    
    .quality-objectives {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .intro-text h1 {
        font-size: 2rem;
    }
    
    .history-timeline {
        padding-left: 1rem;
    }
    
    .history-timeline::before {
        left: 1rem;
    }
    
    .timeline-item {
        padding-left: 3rem;
    }
    
    .timeline-item::before {
        left: -1rem;
    }
    
    .timeline-item::after {
        left: -1rem;
    }
    
    .timeline-card {
        padding: 2rem;
    }
    
    .timeline-year {
        font-size: 1.2rem;
    }
    
    .timeline-title {
        font-size: 1.1rem;
    }
    
    .timeline-content {
        font-size: 1rem;
    }
}
</style>

<main class="main-content">
    <!-- Introduction Section -->
    <section class="about-intro">
        <div class="container">
            <div class="intro-content">
                <div class="intro-text">
                    <h1>About UPHSL</h1>
                    <div class="tagline">Character Building is Nation Building</div>
                    <div class="description">
                        The University of Perpetual Help System is a premier educational institution committed to developing Filipino leaders through quality education, character formation, and community service. With multiple campuses across the Philippines, we continue to uphold our founding principles of excellence and service.
                    </div>
                </div>
                <div class="intro-visual">
                    <video class="about-video" autoplay muted loop playsinline poster="<?php echo $base_path; ?>assets/images/FACADE.jpg">
                        <source src="<?php echo $base_path; ?>assets/video/AD2025.mp4" type="video/mp4">
                    </video>
                </div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Years of Excellence</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">7</div>
                    <div class="stat-label">Campus Locations</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Academic Programs</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="about-content">
        <div class="container">
            <!-- History Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Our History</h2>
                </div>
                <div class="section-content">
                    <p>The University of Perpetual Help System, having committed itself to service in the forefront of education and health care, came into being out of the unselfish effort and untiring commitment of its founder: Dr. Jose G. Tamayo and Dr. Josefina Laperal Tamayo. The desire to serve others was manifested at a very young age when Dr. Jose G. Tamayo, then a young boy dreamt of being a doctor. For him, it was the best way he that he could serve his fellowmen. But when that dream became a reality, he realized that his best was not good enough the services he rendered were so limited and only within the realm of his profession as a doctor. With an ardent desire to serve his fellowmen, the idea of reaching out to through the setting up of an educational institution, gave birth to the following:</p>
                    
                    <div class="history-timeline">
                        <div class="timeline-item">
                            <div class="timeline-card">
                                <div class="timeline-year">1968</div>
                                <div class="timeline-title">Perpetual Help College Manila</div>
                                <div class="timeline-content">Opened with Nursing as key a course offering. Most graduate are now in the USA who formed themselves into Perpetualites Association of America and serving as a direct linkage for Perpetualite students and alumni there.</div>
                                <div class="timeline-highlight">🏥 First campus established with focus on healthcare education</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-card">
                                <div class="timeline-year">1970</div>
                                <div class="timeline-title">Perpetual Help College Malasiqui</div>
                                <div class="timeline-content">Located in the heart of Municipality of Malasiqui, Pangasinan, it was founded to accelerate the development of health education in the rural areas particularly in the Province of Pangasinan.</div>
                                <div class="timeline-highlight">🌾 Expanding healthcare education to rural communities</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-card">
                                <div class="timeline-year">1975</div>
                                <div class="timeline-title">University of Perpetual Help Rizal</div>
                                <div class="timeline-content">The eldest son, Antonio, inspired by his parents, spearheaded the founding of another school in the City of Las Piñas. The Molino Campus in Bacoor, Cavite and Calamba Campus in Laguna are extension programs which started their operations in 1996 and 1997 respectively.</div>
                                <div class="timeline-highlight">👨‍👩‍👧‍👦 Family legacy continues with second generation leadership</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-card">
                                <div class="timeline-year">1976</div>
                                <div class="timeline-title">University of Perpetual Help System Laguna</div>
                                <div class="timeline-content">Opened its door for academic excellence with a total of 89 students in the first and second and 367 students in the tertiary level. The campus is located along the old national highway in Biñan which is very accessible.</div>
                                <div class="timeline-highlight">🎓 Main campus established with comprehensive academic programs</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-card">
                                <div class="timeline-year">1976</div>
                                <div class="timeline-title">UPH-Dr. Jose G. Tamayo Medical University</div>
                                <div class="timeline-content">Formed specializing in medical and health related courses and also located in Biñan, Laguna.</div>
                                <div class="timeline-highlight">⚕️ Dedicated medical university for specialized healthcare education</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-card">
                                <div class="timeline-year">1997</div>
                                <div class="timeline-title">University of Perpetual Help System – GMA Campus</div>
                                <div class="timeline-content">Started its operation in General Mariano Alvarez, Cavite.</div>
                                <div class="timeline-highlight">🏢 Strategic expansion to serve more communities</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-card">
                                <div class="timeline-year">Present</div>
                                <div class="timeline-title">Isabela Campus</div>
                                <div class="timeline-content">The youngest satellite of the university system. It aims to provide the northern part of the country an avenue to bring out the nurture the seeds of excellence through Perpetualite education.</div>
                                <div class="timeline-highlight">🌟 Latest addition to the UPHSL family of campuses</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mission Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Our Mission</h2>
                </div>
                <div class="section-content">
                    <p>The University of Perpetual Help System is dedicated to the development of the Filipino as a leader. It aims to graduate dynamic students who are physically, intellectually, socially and spiritually committed to the achievement of the best quality of life.</p>
                    
                    <p>As a system of service in health and education, the University of Perpetual Help System is dedicated to the formation of Christian, services and research oriented professionals and leaders in quality education and health care.</p>
                    
                    <p>It shall produce Perpetualites who outstandingly value the virtues of reaching out and helping others as vital ingredients to nation building.</p>
                </div>
            </div>

            <!-- Vision Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Our Vision</h2>
                </div>
                <div class="section-content">
                    <p>The University of Perpetual Help System is a premier University that provides unique and innovative educational processes, contents, end-results for the pursuit of excellence in academics, technology, and research through community partnership and industry linkages.</p>
                    
                    <p>The University takes the lead role as a catalyst for human resource development, continues to inculcate values as way of strengthening the moral fiber of the Filipino individuals proud of their race and prepared for exemplary global participation in the realm of arts, sciences, humanities, and business.</p>
                    
                    <p>It sees the Filipino people enjoying quality and abundant life, living in peace and building a nation that the next generations shall be nourishing, cherishing and valuing.</p>
                </div>
            </div>

            <!-- Philosophy Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Our Philosophy</h2>
                </div>
                <div class="philosophy-box">
                    <div class="philosophy-quote">"Character Building is Nation Building"</div>
                    <div class="philosophy-author">- University of Perpetual Help System Laguna</div>
                </div>
                <div class="section-content">
                    <p>The University of Perpetual Help System Laguna believes that national development and transformation are predicated on the quality of the education of its people. Towards this end, the institution is committed to the ideas of teaching, community service and research with "Character Building is Nation Building" as its guiding principle.</p>
                </div>
            </div>

            <!-- Board of Directors Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Board of Directors</h2>
                </div>
                <div class="board-grid">
                    <div class="board-member">
                        <div class="member-name">Dr./BGen. Antonio Laperal Tamayo, GSC, FPCHA, Ph.D.</div>
                        <div class="member-position">Chairman of the Board, CEO and President</div>
                    </div>
                    
                    <div class="board-member">
                        <div class="member-name">Ma. Theresa T. Salazar, MD, MS</div>
                        <div class="member-position">Board Member</div>
                    </div>
                    
                    <div class="board-member">
                        <div class="member-name">Marianito L. Tamayo, BSFA</div>
                        <div class="member-position">Board Member</div>
                    </div>
                    
                    <div class="board-member">
                        <div class="member-name">Arcadio L. Tamayo, MD, PhD</div>
                        <div class="member-position">Chancellor & EVP</div>
                    </div>
                    
                    <div class="board-member">
                        <div class="member-name">Roberto L. Tamayo, BSC, EdD</div>
                        <div class="member-position">Board Member</div>
                    </div>
                    
                    <div class="board-member">
                        <div class="member-name">Manuel L. Tamayo, BSC</div>
                        <div class="member-position">Board Member</div>
                    </div>
                    
                    <div class="board-member">
                        <div class="member-name">Maj. Rafael L. Tamayo, BSC, MBA</div>
                        <div class="member-position">Board Member</div>
                    </div>
                    
                    <div class="board-member">
                        <div class="member-name">Marcia Ana L. Tamayo, BSC ARCH</div>
                        <div class="member-position">Board Member</div>
                    </div>
                    
                    <div class="board-member">
                        <div class="member-name">Ma. Florencia T. Tampoya, MD FAAFP</div>
                        <div class="member-position">Board Member</div>
                    </div>
                    
                    <div class="board-member">
                        <div class="member-name">Jose Mauro L. Tamayo, BSC</div>
                        <div class="member-position">Board Member</div>
                    </div>
                    
                    <div class="board-member">
                        <div class="member-name">Ma. Consorcia L. Tamayo, BSC</div>
                        <div class="member-position">Board Member</div>
                    </div>
                    
                    <div class="board-member">
                        <div class="member-name">Maj. Victor L. Tamayo, MD, MHA</div>
                        <div class="member-position">Board Member</div>
                    </div>
                </div>
            </div>

            <!-- Quality Policy Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Quality Policy</h2>
                </div>
                <div class="section-content">
                    <p>The University of Perpetual Help System Laguna/University of Perpetual Help Dr. Jose G. Tamayo Medical University (UPHSL/UPHDJGTMU) is committed to producing competent and competitive professionals who are holistic graduates, achievers of life imbued with Christian values and research oriented leaders in quality education and health care.</p>
                    
                    <p><strong>Pursuing our commitment through:</strong></p>
                    <ul>
                        <li>Relevant and updated curriculum</li>
                        <li>Internationalization</li>
                        <li>Student-oriented curricular and extra-curricular programs</li>
                        <li>Adept delivery mechanism</li>
                        <li>Intellectual and professional fulfillment of faculty and staff</li>
                        <li>Quality research</li>
                        <li>Corporate Social Responsibility</li>
                        <li>Involvement of all stakeholders in growth and development of the University</li>
                        <li>Continuous upgrading of infrastructure and facilities</li>
                        <li>Creation of congenial and conducive work environment</li>
                        <li>Spiritual formation</li>
                    </ul>
                </div>
            </div>

            <!-- Quality Objectives Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Institutional Quality Objectives</h2>
                </div>
                <div class="quality-objectives">
                    <div class="objective-item">
                        <div class="objective-title">Professional Development</div>
                        <ul class="objective-list">
                            <li>Develop professionals with appropriate technical and professional competencies for local and international market</li>
                        </ul>
                    </div>
                    
                    <div class="objective-item">
                        <div class="objective-title">Recognition</div>
                        <ul class="objective-list">
                            <li>Achieve recognition as one of the respected universities in the country</li>
                        </ul>
                    </div>
                    
                    <div class="objective-item">
                        <div class="objective-title">Knowledge Generation</div>
                        <ul class="objective-list">
                            <li>Serve as venue for knowledge generation and dissemination</li>
                        </ul>
                    </div>
                    
                    <div class="objective-item">
                        <div class="objective-title">Community Upliftment</div>
                        <ul class="objective-list">
                            <li>Uplift the quality of life of people living in the adopted community</li>
                        </ul>
                    </div>
                    
                    <div class="objective-item">
                        <div class="objective-title">Service Delivery</div>
                        <ul class="objective-list">
                            <li>Deliver quality services to the clientele</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Our Campuses Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Our Campuses</h2>
                </div>
                <div class="campus-grid">
                    <div class="campus-item">
                        <div class="campus-name">Binan, Laguna</div>
                        <div class="campus-location">Sto. Niño, City of Biñan, Laguna</div>
                    </div>
                    
                    <div class="campus-item">
                        <div class="campus-name">GMA, Cavite</div>
                        <div class="campus-location">San Gabriel, General Mariano Alvarez, Cavite</div>
                    </div>
                    
                    <div class="campus-item">
                        <div class="campus-name">Sampaloc, Manila</div>
                        <div class="campus-location">1240 V. Concepcion Street, Sampaloc, Manila</div>
                    </div>
                    
                    <div class="campus-item">
                        <div class="campus-name">Malasiqui, Pangasinan</div>
                        <div class="campus-location">Montemayor Street, Poblacion, Malasiqui, Pangasinan</div>
                    </div>
                    
                    <div class="campus-item">
                        <div class="campus-name">Cauayan, Isabela</div>
                        <div class="campus-location">Minante I, Cauayan City, Isabela</div>
                    </div>
                    
                    <div class="campus-item">
                        <div class="campus-name">Panay, Roxas</div>
                        <div class="campus-location">Pueblo de Panay, Roxas City</div>
                    </div>
                    
                    <div class="campus-item">
                        <div class="campus-name">UPH-Dr. Jose G. Tamayo Medical University</div>
                        <div class="campus-location">Binan, Laguna</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>

<?php
// Include footer
include 'app/includes/footer.php';
?>