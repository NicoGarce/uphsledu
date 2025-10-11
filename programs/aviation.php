<?php
/**
 * UPHSL Aviation Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the Aviation program at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Aviation";

// Set base path for assets
$base_path = '../';

// Set background image path
$bg_image = 'img/banner/AVIATION.jpg';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('<?php echo $bg_image; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="img/logo/aviation_logo.png" alt="Aviation Logo">
            </div>
            <div class="banner-content">
                <h1>College of Aviation</h1>
                <p>Unlock your wings to limitless possibilities with the School of Aviation, where excellence takes flight.</p>
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
                            <p>The University of Perpetual Help system Laguna-School of Aviation faculty, staff and state-of-the-art facilities create a dynamic learning environment that promotes critical thinking, teamwork, and a passion for aviation. We are dedicated to shaping the future of aviation by nurturing the talents and aspirations of our students and preparing them to take flight in their chosen aviation careers.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>The University of Perpetual System Laguna-School of Aviation is a leading institution in aviation education, recognized globally for our excellence in preparing highly skilled and competent aviation professionals.</p>
                            <p>By creating a learning environment that nurtures innovation, fosters a passion for aviation, and embraces technological advancements in continually adapting to industry trends and emerging technologies.</p>
                            <p>The University of Perpetual Help System Laguna-School of Aviation vision's is to shape the future of aviation by producing graduates who embody the highest standards of professionalism, safety, and ethical responsibility, making a positive impact on the aviation industry and society as a whole.</p>
                        </section>

                        <!-- Programs Section -->
                        <section class="programs-section">
                            <h2>Programs Offered</h2>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Aircraft Maintenance and Technology (BSAMT)</h3>
                                <p>Guided by the University Mission, the Perpetualite Aviators must be able to:</p>
                                <ul>
                                    <li>Utilize their technical knowledge and skills to thrive in Aviation practice</li>
                                    <li>Apply professional ethics with a deep sense of Christian Values in fulfilling the needs of the society</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Demonstrate comprehensive knowledge of aircraft systems</li>
                                    <li>Apply maintenance and troubleshooting skills</li>
                                    <li>Interpret technical manuals and documentation</li>
                                    <li>Utilize industry-standard tools and equipment</li>
                                    <li>Adhere to safety protocols and regulations</li>
                                    <li>Collaborate effectively in a team environment</li>
                                    <li>Demonstrate professionalism and ethics</li>
                                    <li>Stay updated with industry advancements</li>
                                    <li>Exhibit critical thinking and problem-solving abilities</li>
                                    <li>Communicate effectively</li>
                                    <li>Perform comprehensive inspections of aircraft systems and components</li>
                                    <li>Conduct preventative maintenance and scheduled servicing of aircraft</li>
                                    <li>Troubleshoot and diagnose avionics and electrical system issues</li>
                                    <li>Apply knowledge of aviation regulations and compliance standards</li>
                                    <li>Demonstrate proficiency in aviation industry software and diagnostic tools</li>
                                    <li>Effectively document maintenance activities and generate accurate reports</li>
                                    <li>Apply problem-solving skills to resolve complex technical issues</li>
                                    <li>Demonstrate understanding of aircraft performance and flight characteristics</li>
                                    <li>Implement effective time management and prioritization skills</li>
                                    <li>Develop a strong foundation in mathematics, physics, and engineering principles relevant to aviation technology</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Aviation Electronics Technology (BSAVT)</h3>
                                <p>Is an applied computer four year degree course. It is intended for those who want a career in developing computer software applications in different fields of endeavor.</p>
                                <p>The BSAVT (Bachelor of Science in Aviation Electronics Technology) prepares students to be professional in this field. By the time they graduate, the students are expected to:</p>
                                <ul>
                                    <li>Have acquired the basic Computer Science Foundation</li>
                                    <li>Have gained knowledge in the field of theoretical analysis and computer programming</li>
                                    <li>And have developed the ability of conceptualizing and designing new strategies in the field of programming</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Apply knowledge of computing fundamentals, knowledge of a computing specialization, and mathematics, science, and domain knowledge appropriate for the computing specialization to the abstraction and conceptualization of computing models from defined problems and requirements</li>
                                    <li>Identify, analyze, formulate, research literature, and solve complex computing problems and requirements reaching substantiated conclusions using fundamental principles of mathematics, computing sciences, and relevant domain disciplines</li>
                                    <li>An ability to apply mathematical foundations, algorithmic principles and computer science theory in the modeling and design of computer-based systems in a way that demonstrates comprehension of the tradeoffs involved in design choices</li>
                                    <li>Knowledge and understanding of information security issues in relation to the design, development and use of information systems</li>
                                    <li>Design and evaluate solutions for complex computing problems, and design and evaluate systems, components, or processes that meet specified needs with appropriate consideration for public health and safety, cultural, societal, and environmental considerations</li>
                                    <li>Create, select, adapt and apply appropriate techniques, resources and modern computing tools to complex computing activities, with an understanding of the limitations to accomplish a common goal</li>
                                    <li>Function effectively as an individual and as a member or leader in diverse teams and in multidisciplinary settings</li>
                                    <li>Communicate effectively with the computing community and with society at large about complex computing activities by being able to comprehend and write effective reports, design documentation, make effective presentations, and give and understand clear instructions</li>
                                    <li>An ability to recognize the legal, social, ethical and professional issues involved in the utilization of computer technology and be guided by the adoption of appropriate professional, ethical and legal practices</li>
                                    <li>Recognize the need, and have the ability, to engage in independent learning for continual development as a computing professional</li>
                                </ul>
                            </div>
                        </section>

                        <!-- Career Opportunities Section -->
                        <section class="career-opportunities-section">
                            <h2>Career Opportunities</h2>
                            
                            <div class="career-grid">
                                <div class="career-category">
                                    <h3>Bachelor of Science in Aircraft Maintenance and Technology (BSAMT) / Bachelor of Science in Aviation Electronics Technology (BSAVT)</h3>
                                    <ul>
                                        <li>Aircraft Parts Sales Representative</li>
                                        <li>Aerospace Researcher</li>
                                        <li>Aviation Consultant</li>
                                        <li>Aircraft Powerplant Engineer</li>
                                        <li>Aircraft Structural Engineer</li>
                                        <li>Aircraft Interior Systems Specialist</li>
                                        <li>Aircraft Component Repair Technician</li>
                                        <li>Aerospace Project Manager</li>
                                        <li>Aircraft Maintenance Planning Analyst</li>
                                        <li>Avionics Integration Engineer</li>
                                        <li>Aircraft Ground Support Equipment Technician</li>
                                        <li>Aircraft Test Engineer</li>
                                        <li>Aircraft Materials and Logistics Manager</li>
                                        <li>Aircraft Paint and Finishing Specialist</li>
                                        <li>Aircraft Electrical Systems Engineer</li>
                                        <li>Aviation Regulatory Compliance Officer</li>
                                        <li>Unmanned Aircraft Systems (UAS) Technician</li>
                                        <li>Aviation Software Developer</li>
                                        <li>Aircraft System Integration Specialist</li>
                                        <li>Aviation Operations Coordinator</li>
                                        <li>Flight Data Analyst</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Associate in Aircraft Maintenance Technology (AAMT) / Associate in Aviation Electronics Technology (AAET)</h3>
                                    <ul>
                                        <li>Aircraft Maintenance Technician</li>
                                        <li>Cabin Maintenance</li>
                                        <li>Line Maintenance</li>
                                        <li>Base Maintenance</li>
                                        <li>Avionics Technician</li>
                                        <li>Technical Support Specialist</li>
                                        <li>Aircraft Parts Sales Representative</li>
                                        <li>Aircraft Component Repair Technician</li>
                                        <li>Aircraft Ground Support Equipment Technician</li>
                                        <li>Aircraft Materials and Logistics Manager</li>
                                        <li>Aircraft Paint and Finishing Specialist</li>
                                        <li>Ramp Agent</li>
                                        <li>Cargo Handler</li>
                                        <li>Aircraft Ground Support Services</li>
                                        <li>Tool Keeper</li>
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
                            <li><strong>College:</strong> College of Aviation</li>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Programs:</strong> 2 Bachelor's Programs</li>
                            <li><strong>Focus:</strong> Aviation Technology & Electronics</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Programs Offered</h3>
                        <ul>
                            <li><strong>BSAMT:</strong> Bachelor of Science in Aircraft Maintenance and Technology</li>
                            <li><strong>BSAVT:</strong> Bachelor of Science in Aviation Electronics Technology</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Key Features</h3>
                        <ul>
                            <li>State-of-the-art facilities</li>
                            <li>Dynamic learning environment</li>
                            <li>Critical thinking development</li>
                            <li>Teamwork skills</li>
                            <li>Passion for aviation</li>
                            <li>Industry-standard training</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Email:</strong><br>
                        <a href="mailto:aviation@uphsl.edu.ph">aviation@uphsl.edu.ph</a></p>
                        
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



