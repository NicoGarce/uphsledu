<?php
/**
 * UPHSL Research Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about research programs and initiatives at UPHSL
 */

$base_path = '../';
$page_title = "Research & Publication";
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

/* News Section */
.news-section {
    background: white;
}

.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.news-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(44, 90, 160, 0.1);
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
}

.news-image {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

.news-content {
    padding: 1.5rem;
}

.news-content h4 {
    color: var(--primary-color);
    font-size: 1.2rem;
    margin-bottom: 1rem;
    font-weight: 700;
    line-height: 1.4;
}

.news-content p {
    color: #666;
    line-height: 1.6;
    font-size: 0.95rem;
    margin-bottom: 1rem;
}

.news-link {
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.news-link:hover {
    color: var(--primary-color);
    text-decoration: none;
}

/* Research Tables Section */
.research-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.research-container {
    max-width: 1200px;
    margin: 0 auto;
}

.department-select {
    margin-bottom: 3rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.department-select select {
    width: 100%;
    padding: 1rem 1.5rem;
    border: 2px solid var(--primary-color);
    border-radius: 10px;
    background: white;
    color: var(--primary-color);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231c4da1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1.2rem;
    padding-right: 3rem;
    transition: all 0.3s ease;
}

.department-select select:focus {
    outline: none;
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(28, 77, 161, 0.1);
}

.department-select select:hover {
    border-color: var(--secondary-color);
}

.research-table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.research-table {
    width: 100%;
    border-collapse: collapse;
}

.research-table th {
    background: var(--primary-color);
    color: white;
    padding: 1rem;
    text-align: left;
    font-weight: 700;
    font-size: 1rem;
}

.research-table td {
    padding: 1rem;
    border-bottom: 1px solid #eee;
    vertical-align: top;
}

.research-table tr:hover {
    background: #f8f9fa;
}

.research-title {
    color: var(--primary-color);
    font-weight: 600;
    font-size: 0.95rem;
    line-height: 1.4;
    margin-bottom: 0.5rem;
}

.research-author {
    color: #666;
    font-size: 0.9rem;
    font-style: italic;
}

.research-link {
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.research-link:hover {
    color: var(--primary-color);
    text-decoration: none;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .intro-content h2 {
        font-size: 2rem;
    }
    
    .intro-description {
        font-size: 0.8rem;
    }
    
    .mv-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .news-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .research-table {
        font-size: 0.9rem;
    }
    
    .research-table th,
    .research-table td {
        padding: 0.8rem 0.5rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .department-select {
        margin-bottom: 2rem;
    }
    
    .department-select select {
        padding: 0.8rem 1.2rem;
        font-size: 0.9rem;
    }
}
</style>

<main class="main-content">
    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-logo">
                    <img src="<?php echo $base_path; ?>assets/images/research/uphsl-research-logo.png" alt="Research & Development Center Logo">
                </div>
                <h2>Research & Publication</h2>
                <p class="intro-description">Research and Development Center (R&DC) demonstrates bold initiatives in escalating the research culture of the university, envisioning it as a research-renowned institution through its efficient and effective research mechanisms, ensuring that relevant and responsive services are in place for school heads, faculty, students and non-teaching staff who engage in research activities.</p>
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
                    <p>A research-renowned university.</p>
                </div>
                <div class="mv-card">
                    <h3>Mission</h3>
                    <p>Develop research-oriented professionals who produce high impact researches that are locally responsive and globally competitive, worthy of publication and citation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- News & Updates Section -->
    <section class="content-section news-section">
        <div class="container">
            <h2 class="section-title">News & Updates</h2>
            <div class="news-grid">
                <div class="news-card">
                    <div class="news-image">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="news-content">
                        <h4>UPHSL GRADUATE STUDENTS MENTORED ON IMRD STRUCTURE, ENCOURAGED TO BE VISIBLE THROUGH PUBLICATIONS</h4>
                        <p>In a significant move towards enhancing the research capabilities of the graduate students of the University of the Perpetual Help System Laguna (UPHSL), a webinar was launched by the UPHSL Research and Development Center in coordination with the UPHSL Graduate School focusing on the IMRD (Introduction, Methods, Results, and Discussion)</p>
                        <a href="#" class="news-link">Click Here</a>
                    </div>
                </div>

                <div class="news-card">
                    <div class="news-image">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="news-content">
                        <h4>3 UNIVERSITY RESEARCHERS WIN AT 2ND INTERNATIONAL CONFERENCE ON BUSINESS AND STEM EDUCATION</h4>
                        <p>UPHSL Makes a Mark! Dr. Josefa Carrillo, Mary Easter Claire S. Perez, and Felbert Rosales celebrate their research wins at the 2nd International Conference on Business and STEM Education.</p>
                        <a href="#" class="news-link">Click Here</a>
                    </div>
                </div>

                <div class="news-card">
                    <div class="news-image">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="news-content">
                        <h4>Back-to-Back Publication Seminars Target to Boost Research Productivity of CBA, CIHM, CAS, and CoED Researchers</h4>
                        <p>The Research and Development Center (R&DC) held consecutive publication seminars aimed at enhancing the research capabilities of its undergraduate students.</p>
                        <a href="#" class="news-link">Click Here</a>
                    </div>
                </div>

                <div class="news-card">
                    <div class="news-image">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="news-content">
                        <h4>UNIVERSITY MENTORS ATTEND ON-SITE MASTERCLASS IN TEACHING RESEARCH 2024 AT DLSU</h4>
                        <p>The university delegation was headed by Dr. Leomar S. Galicia with Ms. Jeanne Pauline M. Sarmiento and Reggie R. Mueden, forming the total of 201 on-site delegates from 78 HEIs and DEPEd schools in the country</p>
                        <a href="#" class="news-link">Click Here</a>
                    </div>
                </div>

                <div class="news-card">
                    <div class="news-image">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="news-content">
                        <h4>Perpetualite researchers achieve back-to-back publications in Quartile 3 Scopus Journal</h4>
                        <p>A significant step to increase the publications of the university in high-impact journals, select university researchers published their papers in Journal of Lifestyle and SDGs Review, a Scopus-indexed journal, before the year 2024 ended and as the new year began, marking a substantial increase in the institutional research history.</p>
                        <a href="#" class="news-link">Click Here</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Research by Department Section -->
    <section class="content-section research-section">
        <div class="container">
            <h2 class="section-title">Researches By Department</h2>
            <div class="research-container">
                <!-- Department Dropdown -->
                <div class="department-select">
                    <select id="departmentSelect" onchange="showDepartmentMobile(this.value)">
                        <option value="arts-science">Arts & Science</option>
                        <option value="criminology">Criminology</option>
                        <option value="graduate-school">Graduate School</option>
                        <option value="business-accountancy">Business & Accountancy</option>
                        <option value="education">Education</option>
                        <option value="hospitality">Int'l Hospitality Management</option>
                        <option value="computer-studies">Computer Studies</option>
                        <option value="engineering">Engineering</option>
                        <option value="maritime">Maritime</option>
                    </select>
                </div>

                <!-- Arts & Science Research -->
                <div id="arts-science" class="research-table-container">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title">ADVANTAGES, CHALLENGES ENCOUNTERED AND ATTITUDE OF TEACHERS IN UTILIZING MULTIMEDIA IN THE CLASSROOM</div>
                                </td>
                                <td><div class="research-author">Alma T. Jallorina</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">ASSESSING STUDENTS' RESEARCH EXPERIENCE AT THE UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA COLLEGE OF MARITIME EDUCATION</div>
                                </td>
                                <td><div class="research-author">Amador B. Alumia & Leomar S. Galicia</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">AWARENESS ON THE ADVANTAGES AND DISADVANTAGES OF OUTCOME BASED EDUCATION AMONG GRADUATING PSYCHOLOGY STUDENTS</div>
                                </td>
                                <td><div class="research-author">Sherill S. Villaluz</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">BEYOND PREJUDICE UNDERSTANDING PEOPLE LIVING WITH HUMAN</div>
                                </td>
                                <td><div class="research-author">Sherill S. Villaluz, Jershon Ammon N. Teodoro & Radlyn L. del Prado</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">DEGREE OF INCLINATION, BOARD COURSE COMPETENCE, AND LICENSURE READINESS AMONG UPHSL PSYCHOLOGY GRADUATES</div>
                                </td>
                                <td><div class="research-author">Sherill S. Villaluz</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">DEGREE OF INVOLVEMENT IN LEISURE ACTIVITIES AND ACADEMIC PERFORMANCE OF UPHSL MARITIME STUDENTS</div>
                                </td>
                                <td><div class="research-author">Araceli C. Corpuz, Ace C. Bernarte, Eraume Ramir M. Saluba & Raymond-Paul T. Sanchez</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">DIFFICULTIES ENCOUNTERED, LEARNING STRATEGIES AND ACADEMIC PERFORMANCE IN PHYSICS OF PSYCHOLOGY STUDENTS</div>
                                </td>
                                <td><div class="research-author">Araceli C. Corpuz</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">FAMILY DYNAMICS, EMOTIONAL RESPONSES, HOPE AND HANDLING STRATEGIES AMONG CALAMITY VICTIMS</div>
                                </td>
                                <td><div class="research-author">Laura De Guzman, Frances Amara Cristobal & Gimmeli Ann Palomares</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">FREQUENCY OF WATCHING POLITICAL NEWS PROGRAM ON TELEVISION, POLITICAL NEWS BIAS, AND POLITICAL NEWS DELIVERY SATISFACTION</div>
                                </td>
                                <td><div class="research-author">Nimfa R. Marcelo</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">FROM LETTERS TO LIFE UNDERSTANDING LANGUAGE TEACHERS EXPERIENCES</div>
                                </td>
                                <td><div class="research-author">Rowena R. Contillo, Leomar S. Galicia, Antonio R. Yango & Pedrito Jose V. Bermudo</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">GAY LANGUAGE IMPACT ON COLLOQUIAL COMMUNICATION IN BARANGAY STO. TOMAS, CITY OF BIÑAN, LAGUNA</div>
                                </td>
                                <td><div class="research-author">Hazel V. Cortez</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">KNOWLEDGE AND AWARENESS ON MTRCB ADVISORIES AMONG FOURTH GRADERS OF UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA</div>
                                </td>
                                <td><div class="research-author">Hazel V. Cortez, Yves Carlson R. Hitchon & Precious May V. Dicolen</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">LAUGHTER IN CLASS HUMOROUS MEMES IN 21ST CENTURY LEARNING</div>
                                </td>
                                <td><div class="research-author">Paulo Emmanuel G. Baysac</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">MOTIVATION, FREQUENCY OF USAGE AND LEVEL OF CONFIDENCE IN USING PHILIPPINE ENGLISH AMONG FOREIGN STUDENTS</div>
                                </td>
                                <td><div class="research-author">Alma T. Jallorina</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">PAIN AND FORGIVENESS IN THE EYES OF THE FILIPINOS</div>
                                </td>
                                <td><div class="research-author">Sherill S. Villaluz, Paulo Emmanuel G. Baysac, Antonio S. Yango, Czarina Isabelle I. Arimado & Fatima Mikaela C. Remoquillo</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">PET ANIMALS TO OWN AND TO LOVE</div>
                                </td>
                                <td><div class="research-author">Luz Remedios Quito Del Rosario, Antonio Yango, Rissel C. Dela Paz, Jodel Clarissa B. Margate & Eire Ramallosa P. May</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">REVISITING ART THERAPY A COUNSELING INTERVENTION FOR PUPILS</div>
                                </td>
                                <td><div class="research-author">Jocelyn G. Capacio</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">SELF – APPRAISAL, INTERPERSONAL RELATIONSHIP, AND LIFE SATISFACTION OF TEENAGE PARENTS</div>
                                </td>
                                <td><div class="research-author">Leomar S. Galicia</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">TO LOCK AND TO UNLOCK UNDERSTANDING THE LIVED EXPERIENCE OF PUBLIC HIGH SCHOOL TEACHERS WITH STUDENTS HAVING READING DIFFICULTY</div>
                                </td>
                                <td><div class="research-author">Juditha L. Nievarez –Teodoro & Antonio R. Yango</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Criminology Research -->
                <div id="criminology" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title">CLOSED CIRCUIT TELEVISION (CCTV) IN THE SCHOOL CAMPUS FACULTYEMPLOYEE, AND STUDENT'S PERSPECTIVE</div>
                                </td>
                                <td><div class="research-author">Diadem DV. Fantony</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">PARENTAL ATTITUDE TOWARDS WAR TOYS ITS PERCEIVED EFFECTS TO CHILD'S BEHAVIOR</div>
                                </td>
                                <td><div class="research-author">J. Acosta, R. Abayan, P. Abella, M. Alad, H. Buenconsejo, F. Cabanero, A. Figueroa & A. Santiago</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Graduate School Research -->
                <div id="graduate-school" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title">ASSESSING STUDENTS' RESEARCH EXPERIENCE AT THE UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA COLLEGE OF MARITIME EDUCATION</div>
                                </td>
                                <td><div class="research-author">Amador B. Alumia & Leomar S. Galicia</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">COLLEGE STUDENTS' ATTITUDE TOWARDS THE INTERNET AS A COMMUNICATION MEDIUM AND LEVEL OF UTILIZATION OF ENGLISH LANGUAGE IN THE CLASSROOM</div>
                                </td>
                                <td><div class="research-author">Antonio R. Yango & Maria Cecilia L. Garcia</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">CROSSROADS OF QUALITY ASSURANCE: THE PHILIPPINE BASIC EDUCATION EXPERIENCE</div>
                                </td>
                                <td><div class="research-author">Ferdinand C. Somido</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">FROM LETTERS TO LIFE: UNDERSTANDING LANGUAGE TEACHERS EXPERIENCES IN TEACHING LITERATURE</div>
                                </td>
                                <td><div class="research-author">Rowena R. Contillo, Leomar S. Galicia, Antonio R. Yango & Pedrito Jose V. Bermudo</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">PERSONALITY TYPE, ORGANIZATIONAL COMMITMENT, AND COLLABORATIVE ALLIANCE AMONG UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA (UPHSL) ACADEMIC PERSONNEL</div>
                                </td>
                                <td><div class="research-author">Sherill S. Villaluz & Leomar S. Galicia</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">SELF - APPRAISAL, INTERPERSONAL RELATIONSHIP, AND LIFE SATISFACTION OF TEENAGE PARENTS</div>
                                </td>
                                <td><div class="research-author">Leomar S. Galicia</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">TO LOCK AND TO UNLOCK UNDERSTANDING THE LIVED EXPERIENCE OF PUBLIC HIGH SCHOOL TEACHERS WITH STUDENTS HAVING READING DIFFICULTY</div>
                                </td>
                                <td><div class="research-author">Juditha L. Nievarez - Teodoro & Antonio R. Yango</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">WORDS OR NUMBERS THE ESSENCE OF LANGUAGE TEACHERS' UNAPPRECIATION OF MATHEMATICS</div>
                                </td>
                                <td><div class="research-author">Leomar S. Galicia</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Business & Accountancy Research -->
                <div id="business-accountancy" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title">THE GRADUATES OF BUSINESS VS. EMPLOYMENT (A TRACER STUDY)</div>
                                </td>
                                <td><div class="research-author">Francisca A. Argana, Marilyn A. Cabalza & Ernesto A. Serrano Jr.</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">THE PARTICIPANTS EVALUATION'S RESULT FOR THE SEMINAR WORKSHOP - COMMUNITY OUTREACH PROGRAM OF THE COLLEGE OF BUSINESS AND ACCOUNTANCY</div>
                                </td>
                                <td><div class="research-author">Carlito A. Vizconde</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">THE PERSONAL AND ORGANIZATIONAL COMPETENCES OF THE SELECTED DEPARTMENT HEADS OF THE UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA</div>
                                </td>
                                <td><div class="research-author">Carlito A. Vizconde</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Education Research -->
                <div id="education" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title">KINDERGARTEN TEACHERS' PROFILE, FACILITIES & INSTRUCTIONAL PRACTICES TOWARDS SUSTAINABILITY AND ENVIRONMENTAL SAFETY</div>
                                </td>
                                <td><div class="research-author">Elena A. Salinas</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">READING COMPREHENSION INTERVENTION PROGRAM OF UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA</div>
                                </td>
                                <td><div class="research-author">Alberto R. Rocero & Jhoana L. Macha</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">REGISTRAR'S EXECUTIVE ASSISTANCE (REA) A CUSTOMER RELATIONSHIP MANAGEMENT SYSTEM OR NOT?</div>
                                </td>
                                <td><div class="research-author">Ma. Eliza D. Mapanoo, Oliver M. Junio & Remina L. Tanyag</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">THE EFFECTIVENESS OF COOPERATIVE LEARNING IN STUDENTS' COMPREHENSION OF LITERARY TEXTS</div>
                                </td>
                                <td><div class="research-author">Victorio B. Duyan, Alberto R. Rocero, Adelaida G. Abalos, Jesus M. Purificacion & Edmil L. Recibe</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">THE PICTURE IMAGINATIVE MATERIALS AND THE CREATIVE WRITING SKILLS OF GRADE 10 STUDENTS OF UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA</div>
                                </td>
                                <td><div class="research-author">Alberto R. Rocero, Elena A. Salinas & Remedios M. Dela Rosa</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- International Hospitality Management Research -->
                <div id="hospitality" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title">ACADEMIC PERFORMANCE AND PERCEIVED EMPLOYABILITY SKILLS OF HOTEL AND RESTAURANT MANAGEMENT GRADUATING STUDENTS</div>
                                </td>
                                <td><div class="research-author">Nenita A. Daquiz</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">CUSTOMERS' SATISFACTION ON ONLINE RESERVATION AMONG SELECTED FIVE-STAR HOTELS</div>
                                </td>
                                <td><div class="research-author">Susan L. Palaroan</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">LEVEL OF DIFFICULTIES ENCOUNTERED AND THE PERFORMANCE OF NUTRITION AND DIETETICS GRADUATES IN FOOD SERVICE, COMMUNITY, AND HOSPITAL PRACTICUM</div>
                                </td>
                                <td><div class="research-author">Olivia J. Factoriza</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">LEVEL OF IMPLEMENTATION OF FOOD SANITATION PRACTICES IN SCHOOL CAFETERIAS AS RATED BY UPHSL STUDENTS</div>
                                </td>
                                <td><div class="research-author">Adorita C. De Jose</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">PROPOSED PREVENTIVE AND CORRECTIVE MEASURES FOR HANDLING CUSTOMER COMPLAINTS</div>
                                </td>
                                <td><div class="research-author">Susan L. Palaroan</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Computer Studies Research -->
                <div id="computer-studies" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title">ACCREDITATION MANAGEMENT SYSTEM</div>
                                </td>
                                <td><div class="research-author">Ma. Eliza D. Mapanoo</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">ASSESSMENT OF ONLINE OJT PERFORMANCE MONITORING</div>
                                </td>
                                <td><div class="research-author">Jasmin H. Almarinez</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">COMPARATIVE STUDY OF AIRDROP VS SHAREIT WIFI DIRECT FILE TRANSFER USING COMPATIBLE DEVICES</div>
                                </td>
                                <td><div class="research-author">Michael M. Orozco</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">DEVELOPMENT OF ADDA (ADDITIONAL DATA) ALGORITHM FOR 10T SECURITY AND PRIVACY</div>
                                </td>
                                <td><div class="research-author">Oliver M. Junio & Jasmin De Castro-Niguidula</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">DEVELOPMENT OF OFFLINE CHAT APPLICATION: FRAMEWORK FOR RESILIENT DISASTER MANAGEMENT</div>
                                </td>
                                <td><div class="research-author">Oliver M. Junio & Enrico P. Chavez</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">DEVELOPMENT OF ONLINE GRADUATE TRACER SYSTEM WITH DATA ANALYTICS</div>
                                </td>
                                <td><div class="research-author">Michael M. Orozco</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">DEVELOPMENT OF READING TUTORIAL A SUPPLEMENTARY LEARNING SOFTWARE FOR DAY CARE CENTER</div>
                                </td>
                                <td><div class="research-author">Ma. Eliza D. Mapanoo</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">E-GOVERNMENT FOR HUMAN CAPABILITY DEVELOPMENT PROGRAM: AN IMPLEMENTATION OF G2E SYSTEM FOR ENHANCED GOVERNMENT SERVICES</div>
                                </td>
                                <td><div class="research-author">Ma. Eliza D. Mapanoo & Jonathan M. Caballero</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">HISTOGRAM-BASED IMAGE SEGMENTATION ALGORITHM APPLICATION FOR FLOOD DISASTER MANAGEMENT</div>
                                </td>
                                <td><div class="research-author">Michael M. Orozco & Jonathan M. Caballero</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">MAVIS: SPECIAL EDUCATION VIRTUAL ASSISTANT</div>
                                </td>
                                <td><div class="research-author">Eliza D. Mapanoo & Jonathan M. Caballero</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">POINT OF SALES SYSTEM FOR DRUGSTORE</div>
                                </td>
                                <td><div class="research-author">Jasmin H. Almarinez</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">SMART DISASTER PREDICTION APPLICATION USING FLOOD-RISK ANALYTICS TOWARDS SUSTAINABLE CLIMATE ACTION</div>
                                </td>
                                <td><div class="research-author">Michael M. Orozco & Jonathan M. Caballero</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">WEB-BASED THESIS/ CAPSTONE PROJECT DEFENSE EVALUATION SYSTEM OF THE CCS BIÑAN</div>
                                </td>
                                <td><div class="research-author">Michael M. Orozco</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Engineering Research -->
                <div id="engineering" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title">5S: A LEARNER WORKFLOW TOOL AT AGM VENTURE</div>
                                </td>
                                <td><div class="research-author">Teresita B. Gonzales & Deserie D. Mendoza</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">ADVANCED INTEGRATION OF QUALITY CONTROL THROUGH INVENTORY MANAGEMENT SYSTEM IN A SEMICONDUCTOR COMPANY IN THE PHILIPPINES</div>
                                </td>
                                <td><div class="research-author">Kierven R. de Mesa</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">ASSESSING THE ENRICHMENT PROGRAM FOR SOPHOMORE ENGINEERING STUDENTS OF UPHSL SCHOOL YEAR 2013-14: BASIS FOR DEVELOPMENT INTERVENTIONS</div>
                                </td>
                                <td><div class="research-author">Antonino D. Carpena, Leilani A. Gonzales, Teresita B. Gonzales, Nancy P. Mercado & Engr. Jimmy B. Teodoro</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">CYCLE TIME REDUCTION THROUGH MINIMIZATION OF TRANSPORTATION AT DYNASTY PALLETS SYSTEMS INC.</div>
                                </td>
                                <td><div class="research-author">Nancy P. Mercado, Teresita B. Gonzales & Jayson D. Pobar</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">DAYLIGHT AND SOLAR ENERGY OPTIMIZATION THRU SMART LIGHTING MANAGEMENT SYSTEM WITH MANUAL OVERRIDE</div>
                                </td>
                                <td><div class="research-author">Antonino D. Carpena & Flocerfida L. Amaya</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">DESIGN AND EVALUATION OF ELECTRONIC CLASS RECORD IN UNIVERSITY OF PERPETUAL HELP SYSTEM-LAGUNA</div>
                                </td>
                                <td><div class="research-author">Nancy P. Mercado</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">DEVELOPMENT OF FUN LEARNING APPLICATION FO PRESCHOOLERS</div>
                                </td>
                                <td><div class="research-author">Leilani A. Gonzales</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">IMPROVED PRODUCTION EFFICIENCY THROUGH LEAN MANUFACTURING TOOL WITH THE USE OF VALUE STREAM MAPPING (VSM)</div>
                                </td>
                                <td><div class="research-author">Antonino D. Carpena & Flocerfida L. Amaya</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">LEVEL OF SAFETY AWARENESS OF THE MANAGEMENT AND THE WORKERS AT THE ASSEMBLY AREA IN A SEWING COMPANY</div>
                                </td>
                                <td><div class="research-author">Leilani A. Gonzales & Jimmy M. Teodoro</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">PRODUCTIVITY IMPROVEMENT THROUGH VALUE STREAM MAPPING IN JARVY'S FOOTWEAR COMPANY</div>
                                </td>
                                <td><div class="research-author">Leilani A. Gonzales, Teresita B. Gonzales & Nancy P. Mercado</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">RAPID UPPER LIMB ASSESSMENT: BASIS FOR INTERVENTION OF FACTORY WORKERS IN A GARMENT COMPANY</div>
                                </td>
                                <td><div class="research-author">Nancy P. Mercado</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">STUDY HABITS, ATTITUDES AND ACADEMIC PERFORMANCE OF SELECTED COLLEGE OF ENGINEERING STUDENTS OF SUMMER 2016: BASIS FOR STUDENT REINFORCEMENT</div>
                                </td>
                                <td><div class="research-author">Teresita B. Gonzales</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">VALUE STREAM MAPPING AS AN EFFECTIVE LEAN MANUFACTURING TOOL IN A CARAGEENAN PRODUCING COMPANY IN THE PHILIPPINES</div>
                                </td>
                                <td><div class="research-author">Antonino D. Carpena & Flocerfida L. Amaya</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Maritime Research -->
                <div id="maritime" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title">BERTHING ALONG SIDE PIER: RISK FACTORS AND SAFETY PRACTICES DURING MOORING AND UNMOORING OPERATIONS</div>
                                </td>
                                <td><div class="research-author">Reynaldo A. Lora, Elpidio P. Onte, Dalisay G. Bantatua & Sherill S. Villaluz</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">RISK FACTORS ASSOCIATED WITH THE SEAFARER'S FREQUENCY AND LEVEL OF FATIGUE</div>
                                </td>
                                <td><div class="research-author">Dalisay G. Bantatua, Nonet A. Cuy, Maximo V. Herrera & Elpidio P. Onte</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title">THE APPRENTICESHIP DIFFICULTIES MANAGED BY SEAFARERS WHILE TRAINING ONBOARD</div>
                                </td>
                                <td><div class="research-author">Maximo V. Herrera & Hazel Cortez</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
function showDepartmentMobile(departmentId) {
    // Hide all department tables
    const tables = document.querySelectorAll('.research-table-container');
    tables.forEach(table => {
        table.style.display = 'none';
    });
    
    // Show selected department table
    document.getElementById(departmentId).style.display = 'block';
}
</script>

<?php include '../app/includes/footer.php'; ?>
