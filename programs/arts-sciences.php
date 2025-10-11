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

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/CAS.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="img/logo/uphsl-cas-logo.png" alt="Arts and Sciences Logo">
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
                            <p>The College of Arts and Sciences is designed to produce dynamic graduates who are physically, intellectually, socially, and spiritually committed to the achievement of the best quality of life. It pursues the overriding philosophy of streamlining its offerings to promote the socio-economic conditions of the clientele it serves, aiming at the total development of the individual so he could be of meaningful service to the mission of the institution.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>The College of Arts and Sciences is an educational organization that serves as a creative catalyst of change in the formation of values-oriented, socially and intellectually balanced graduates who can emancipate themselves and their fellowmen, by developing the ability to think critically and upholding useful knowledge and skills for globally-oriented citizenry.</p>
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
                        <h3>Contact Information</h3>
                        <p><strong>Email:</strong><br>
                        <a href="mailto:cas@uphsl.edu.ph">cas@uphsl.edu.ph</a></p>
                        
                        <p><strong>Phone:</strong><br>
                        02-779-5310</p>
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
