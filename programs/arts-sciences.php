<?php
/**
 * UPHSL Arts and Sciences Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the Arts and Sciences program at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Arts & Sciences";

// Set base path for assets
$base_path = '../';

// Set background image path
$bg_image = 'img/banner/CAS.jpg';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('<?php echo $bg_image; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="<?php echo $base_path; ?>programs/img/logo/uphsl-cas-logo.png" alt="Arts and Sciences Logo">
            </div>
            <div class="banner-content">
                <h1>College of Arts and Science</h1>
                <p>The College of Arts and Sciences recognizes the multidimensionality of human intelligence and aspires to educate future liberal arts practitioners who can communicate effectively, think critically, act creatively, and uphold Christian faith and values.</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <!-- Mission Section -->
                        <section class="mission-vision-section">
                            <h2>Mission</h2>
                            <p>The College of Arts and Sciences is committed to producing well-rounded graduates who embody Perpetualite core values and demonstrate program excellence in psychology, communication, and political science. Guided by a holistic approach, the College develops individuals who are physically, intellectually, socially, and spiritually prepared to contribute meaningfully to society. We strive to promote socio-economic advancement, foster critical thinking, enhance communication skills, encourage responsible leadership, and champion lifelong learning through innovative, technology-based instruction and interdisciplinary community engagement, all while upholding academic integrity and the protection of intellectual property.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>The College of Arts and Sciences envisions itself as a leading educational institution recognized for excellence in psychology, communication, and political science. We aim to be a creative catalyst of change, forming values-driven, globally competent graduates who can think critically, lead effectively, innovate responsibly, and uplift themselves and their communities. Anchored in our commitment to quality, compliance, and social relevance, we dedicate ourselves to generating and sharing knowledge through meaningful partnerships and impactful community engagements.</p>
                        </section>

                        <!-- Brief History Section -->
                        <section class="mission-vision-section">
                            <h2>Brief History</h2>
                            
                            <div class="history-content">
                                <p class="history-intro">The College of Arts & Sciences (CAS) of the University of Perpetual Help System-Laguna (UPHSL) is tasked with the delivery of first-rate tertiary instruction in programs related to Liberal Arts and Social Sciences, such as Political Science, Psychology, and Communication. Likewise, the CAS is a servicing College, providing General Education courses to other colleges of the UPHSL.</p>
                                
                                <div class="history-paragraph">
                                    <p>The College of Arts & Sciences stands witness to the phenomenal growth of the University from a College (i.e. Perpetual Help College of Laguna) when it opened in School Year 1976-1977 with a total of 89 high school freshmen and sophomores and 37 college students in the Arts & Sciences, Nursing, Commerce. Through the years, CAS has evolved in response to global academic trends and local needs, expanding its curriculum to include interdisciplinary studies, research-based instruction, and innovative community engagement. The college remains committed to academic excellence, ethical scholarship, and nation-building.</p>
                                </div>
                                
                                <div class="history-highlight">
                                    <h3>Academic Excellence</h3>
                                    <p>The College has consistently produced outstanding graduates recognized for their academic excellence. A significant number of students have graduated with Latin honors—Cum Laude, Magna Cum Laude, and Summa Cum Laude—demonstrating the college's dedication to high academic standards. These awardees have gone on to become respected professionals, researchers, educators, and leaders in their respective fields both locally and internationally. Their success reflects the rigorous training and mentorship provided by the college.</p>
                                </div>
                                
                                <div class="history-highlight">
                                    <h3>Research and Scholarship</h3>
                                    <p>CAS faculty members are actively engaged in scholarly research, contributing to local and international knowledge production. Many have published in reputable peer-reviewed journals, authored books, and presented papers in conferences around the world. The college also promotes collaborative research through interdisciplinary projects, research centers, and partnerships with other academic institutions and government agencies.</p>
                                </div>
                                
                                <div class="history-highlight">
                                    <h3>International Recognition and Quality Assurance</h3>
                                    <p>As part of the university's drive toward internationalization, CAS actively participates in several internationally recognized accreditation and ranking systems that reflect its pursuit of continuous improvement and global competitiveness.</p>
                                    
                                    <p>In alignment with the university's quality assurance initiatives, CAS contributed to the successful attainment and maintenance of <strong>ISO 9001:2015 certification</strong>, ensuring that administrative and academic support processes meet international quality management standards.</p>
                                    
                                    <p>CAS has also taken part in the <strong>QS Stars Rating System</strong>, supporting the university's achievement of high scores in areas such as teaching, employability, and inclusiveness. The college's innovative curriculum, competent faculty, and student support systems have been instrumental in boosting the university's QS performance.</p>
                                    
                                    <p>Furthermore, CAS played a significant role in fulfilling the university's requirements for the <strong>Times Higher Education (THE) Impact Rankings</strong>, particularly in key United Nations Sustainable Development Goals (SDGs) such as Quality Education, Gender Equality, and Climate Action. Faculty-led community extension programs, sustainability research, and interdisciplinary collaborations were central to this recognition.</p>
                                    
                                    <p>The college is also engaged in initiatives aligned with <strong>AppliedHE</strong>, a rising global rating and ranking platform that focuses on teaching and learning quality, graduate employability, and internationalization. CAS has contributed to the university's standing through its student-centered pedagogy, international research publications, and growing global linkages.</p>
                                    
                                    <p>Through these accreditations and rankings, the College of Arts and Sciences reaffirms its commitment to delivering globally benchmarked education, fostering a culture of excellence, and nurturing graduates who are equipped to thrive in a global society.</p>
                                </div>
                                
                                <div class="history-paragraph">
                                    <p>The Liberal Arts programs (AB Psychology, AB Communication Arts, AB Political Science) of the College were accredited <strong>Level IV</strong> by the Philippines Association of Colleges and Universities-Commission on Accreditation (PACUCOA) for the period of January 2021 to November 2026. Bachelor of Science in Psychology was granted <strong>Level III</strong> accreditation status in October 2024 to October 2029.</p>
                                </div>
                            </div>
                        </section>

                        <!-- Programs Section -->
                        <section class="programs-section">
                            <h2>Programs Offered</h2>
                            
                            <div class="program-section">
                                <h3>Bachelor of Arts/Science in Psychology</h3>
                                <p>Guided by the University mission, the Perpetualite Graduates can:</p>
                                <ul>
                                    <li>Practice psychometrics for human resource development</li>
                                    <li>Engage in mentoring and training in the community</li>
                                    <li>Demonstrate organizational communication for effective human relations</li>
                                    <li>Participate in continuing educational opportunities for the improvement of their profession</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Demonstrate an understanding of theories, principles, concepts, and skills in psychology</li>
                                    <li>Develop and sustain argument about established principles in psychology</li>
                                    <li>Critically evaluate the established principles in psychology</li>
                                    <li>Comprehend and evaluate new information related to psychology that may be presented in various forms and from various sources</li>
                                    <li>Understand and explain the main methods of inquiry in psychology</li>
                                    <li>Critically evaluate the appropriateness of different approaches to problem solving in the field</li>
                                    <li>Apply knowledge of methods in psychological inquiry to make adjustments and create approaches to solving problems in applied or an employment context</li>
                                    <li>Correctly apply the theories, principles, concepts and skills in psychology in an employment context</li>
                                    <li>Undertake research using the knowledge and skills in psychology, and communicate the research results to both specialist and non-specialist audiences</li>
                                    <li>Demonstrate understanding of the ethical dimensions of the use of psychological theories and methods</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Arts in Political Science</h3>
                                <p>Guided by the University mission, the Perpetualite Graduates can:</p>
                                <ul>
                                    <li>Conduct political analysis in aid of governance</li>
                                    <li>Lead civic organizations for the delivery of socio-political services</li>
                                    <li>Demonstrate organizational communication for effective human relations</li>
                                    <li>Participate in continuing educational opportunities for the improvement of their profession</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Understand the major concepts of the discipline</li>
                                    <li>Possess a thorough knowledge of political science, its various sub-disciplines, major themes, and analytical techniques as well as basic analytical techniques from other relevant disciplines</li>
                                    <li>Demonstrate a well-developed ability to conduct their own scholarly inquiries using established quantitative and qualitative methods guided by a theory-based or conceptual framework</li>
                                    <li>Exhibit written, visual and oral presentation skills to produce or present analytical reports</li>
                                    <li>Demonstrate a substantive understanding of the historical and contemporary developments in the national and global setting</li>
                                    <li>Manifest a predisposition towards political involvement or participation in any form</li>
                                    <li>Apply knowledge of methods in psychological inquiry to make adjustments and create approaches to solving problems in applied or an employment context</li>
                                    <li>Communicate effectively with the computing community and with society at large about complex computing activities by being able to comprehend and write effective reports, design documentation, make effective presentations, and give and understand clear instructions</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Arts in Communication</h3>
                                <p>Guided by the University mission, the Perpetualite Graduates can:</p>
                                <ul>
                                    <li>Create multi-media materials for responsible mass communication</li>
                                    <li>Conduct researches to generate information for communication</li>
                                    <li>Demonstrate organizational communication for effective human relations</li>
                                    <li>Participate in continuing educational opportunities for the improvement of their profession</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Exhibit the knowledge and skills in planning, managing, and evaluating communication programs</li>
                                    <li>Demonstrate skills in designing and producing multi media (print, broadcast, audiovisual, and electronic) outputs</li>
                                    <li>Demonstrate skills in designing, managing, and evaluating communication campaigns</li>
                                    <li>Design media-based learning system</li>
                                    <li>Conduct communication media research</li>
                                    <li>Uphold professional ethics and standards to practice social responsibility at all times</li>
                                    <li>Demonstrate an understanding of the professional and ethical consideration of communication</li>
                                </ul>
                            </div>
                        </section>

                        <!-- Quality Objectives Section -->
                        <section class="objectives-section">
                            <h2>Quality Objectives</h2>
                            <ul>
                                <li>To develop liberal arts professionals who are imbued with high ethical standards of professionalism and commitment to their chosen fields</li>
                                <li>To be recognized as a respected provider of education in the liberal arts and humanities</li>
                                <li>To serve as a venue for knowledge generation, dissemination, and ensure its utilization for the improvement of instruction and community extension</li>
                                <li>To provide programs for the development and sustainability of selected community and participate in international COP</li>
                            </ul>
                        </section>

                        <!-- Quality Policy Section -->
                        <section class="objectives-section">
                            <h2>Quality Policy</h2>
                            <p>University of Perpetual Help System Laguna/University of Perpetual Help Dr. Jose G. Tamayo Medical University is committed to producing competent and competitive professionals who are holistic graduates, achievers of life imbued with Christian Values and research-oriented leader in quality education and health care.</p>
                            
                            <p>Pursuing our commitment through:</p>
                            <ul>
                                <li>Relevant and updated curriculum</li>
                                <li>Student-centered curricular and extra-curricular programs</li>
                                <li>Adept delivery mechanism</li>
                                <li>Intellectual and professional fulfillment of faculty and staff</li>
                                <li>Quality research</li>
                                <li>Corporate Social Responsibility</li>
                                <li>Involvement of all stakeholders in growth and development of the University</li>
                                <li>Continuous upgrading of infrastructure and facilities</li>
                                <li>Creation of congenial and conducive work environment</li>
                                <li>Spiritual formation</li>
                            </ul>
                        </section>

                        <!-- Career Opportunities Section -->
                        <section class="career-opportunities-section">
                            <h2>Career Opportunities</h2>
                            
                            <div class="career-grid">
                                <div class="career-category">
                                    <h3>Bachelor of Arts/Science in Psychology</h3>
                                    <ul>
                                        <li>HR Specialist</li>
                                        <li>Recruitment Officer</li>
                                        <li>Training Officer</li>
                                        <li>Employee Relations Officer</li>
                                        <li>Customer Care Specialist</li>
                                        <li>Recruitment and Sourcing Administrator</li>
                                        <li>Guidance Counselor</li>
                                        <li>School Psychologist</li>
                                        <li>Testing Officer</li>
                                        <li>Faculty /Instructor</li>
                                        <li>Child Care Specialist</li>
                                        <li>Learning and Development Officer</li>
                                        <li>Health and Wellness Adviser</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Bachelor of Arts in Political Science</h3>
                                    <ul>
                                        <li>Policy Analyst</li>
                                        <li>Legislative Assistant</li>
                                        <li>Public Relations Specialist</li>
                                        <li>Political Consultant</li>
                                        <li>Attorney</li>
                                        <li>Political Campaign Staff</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Bachelor of Arts in Communication</h3>
                                    <ul>
                                        <li>Radio Practitioner</li>
                                        <li>Broadcaster</li>
                                        <li>Field Reporter</li>
                                        <li>Communication Specialists</li>
                                        <li>Educators</li>
                                        <li>Call Center Representatives</li>
                                    </ul>
                                </div>
                            </div>
                        </section>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>College:</strong> College of Arts and Science</li>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Programs:</strong> 3 Bachelor's Programs</li>
                            <li><strong>Focus:</strong> Liberal Arts & Humanities</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Programs Offered</h3>
                        <ul>
                            <li><strong>Psychology:</strong> Bachelor of Arts/Science in Psychology</li>
                            <li><strong>Political Science:</strong> Bachelor of Arts in Political Science</li>
                            <li><strong>Communication:</strong> Bachelor of Arts in Communication</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Key Features</h3>
                        <ul>
                            <li>Multidimensional human intelligence</li>
                            <li>Effective communication skills</li>
                            <li>Critical thinking development</li>
                            <li>Creative problem solving</li>
                            <li>Christian faith and values</li>
                            <li>Values-oriented education</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Our Business Office</h3>
                        <p>UPH Compound, National Highway,<br>
                        Sto. Niño, City of Biñan, Laguna</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../app/includes/footer.php';
?>



