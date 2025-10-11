<?php
session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Education";

// Set base path for assets
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/TEACHER EDUCATION.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="img/logo/uphsl-educ-logo.png" alt="Teacher Education Logo">
            </div>
            <div class="banner-content">
                <h1>College of Teacher Education</h1>
                <p>Leading teacher education for globally competitive teaching professionals and information specialist professionals</p>
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
                            <p>The Education shall produce graduates with pedagogical expertise/information specialist and with imbued human values.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>The Education program is leading teacher education for globally competitive teaching professionals and information specialist professionals</p>
                        </section>

                        <!-- Programs Section -->
                        <section class="programs-section">
                            <h2>Programs Offered</h2>
                            
                            <div class="program-section">
                                <h3>Bachelor of Elementary Education</h3>
                                <p>Comprehensive program preparing future elementary school teachers with pedagogical expertise and human values.</p>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Early Childhood Education</h3>
                                <p>Specialized program focusing on early childhood development and education for young learners.</p>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Special Needs Education Generalist</h3>
                                <p>Program designed to prepare teachers for special education and inclusive learning environments.</p>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Physical Education</h3>
                                <p>Program focusing on physical education, sports, and health education for all grade levels.</p>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Library and Information Science</h3>
                                <p>Comprehensive program preparing information specialists and library professionals.</p>
                            </div>
                            
                            <div class="program-section">
                                <h3>EMAP (Educational Modular Approach Program)</h3>
                                <p>Innovative modular approach to teacher education and professional development.</p>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Secondary Education</h3>
                                <p>Specialized programs for secondary education with majors in:</p>
                                <ul>
                                    <li>English</li>
                                    <li>Mathematics</li>
                                    <li>Filipino</li>
                                    <li>Science</li>
                                    <li>Social Studies</li>
                                </ul>
                            </div>
                        </section>

                        <!-- Program Educational Objectives Section -->
                        <section class="objectives-section">
                            <h2>Program Educational Objectives</h2>
                            
                            <h3>Teacher Education Graduates Can:</h3>
                            <ol>
                                <li>Demonstrate expertise in their own discipline in providing meaningful relevant learning experiences using educational resources.</li>
                                <li>Engage in collaborative research within the discipline.</li>
                                <li>Involvement in local, national, and international extension programs in addressing the needs of society.</li>
                                <li>Demonstrate proficiency in organizational communication for effective human relations.</li>
                                <li>Participate in continuing professional advancement.</li>
                            </ol>
                            
                            <h3>Library and Information Science Graduates Can:</h3>
                            <ol>
                                <li>Provide quality library services as library and information science professionals.</li>
                                <li>Conduct research for technological advancement of library and information science.</li>
                                <li>Participate in local, national, and international extension programs.</li>
                                <li>Demonstrate proficiency in organizational communication for effective human relations.</li>
                                <li>Participate in continuing professional advancement.</li>
                            </ol>
                        </section>

                        <!-- Student Outcomes Section -->
                        <section class="objectives-section">
                            <h2>Student Outcomes</h2>
                            
                            <h3>Teacher Education Program Graduates:</h3>
                            <ol>
                                <li>Articulate the rootedness of education in philosophical, socio-cultural, historical, psychological, and political contexts</li>
                                <li>Demonstration of mastery of subject matter/discipline in-depth understanding of the diversity of learners in various learning areas</li>
                                <li>Facilitate learning using various teaching methodologies and delivery modes appropriate to specific learners and their environments.</li>
                                <li>Develop innovative curricula, instructional plans, teaching approaches, and resources for diverse learners and manifest meaningful and comprehensive pedagogical content knowledge (PCK) of the different subject areas</li>
                                <li>Apply skills in the development and utilization of ICT to promote quality, relevant, and sustainable educational practices, and manifest skills in communication, higher-order thinking, and use of tools and technology to accept learning and teaching</li>
                                <li>Demonstrate a variety of thinking skills in planning, monitoring, assessing, and reporting learning processes and outcomes and utilize appropriate assessment and evaluation tools to measure learning outcomes</li>
                                <li>Practice professional and ethical teaching standards sensitive to the changing local, national, and global realities and demonstrate the positive attributes of a model teacher, both as an individual and as a professional</li>
                                <li>Manifest a desire to pursue personal and professional development continuously</li>
                            </ol>
                            
                            <h3>Bachelor of Library and Information Science Graduates:</h3>
                            <ol>
                                <li>Select, evaluate organize and disseminate print, multimedia, and digital information resources</li>
                                <li>Effectively communicate orally in writing, at the same time, use a variety of communication methods in a manner that enables the messages to be understood</li>
                                <li>Demonstrate logical and systematic approaches to the accomplishment of tasks</li>
                                <li>Formulate objectives, policies, and processes as well as design and manage resources in anticipation of future educational organizational changes</li>
                                <li>Recognize, analyze, and constructively solve problems, provide appropriate direction and assistance, and overcome barriers when necessary</li>
                                <li>Identify users' needs and wants through reference interviews, customers surveys, complaint logs and other means in order to evaluate the effectiveness of current services and improve these and other practices</li>
                                <li>Work well in groups and seeks ways to build team efforts to solve problems and achieve goals</li>
                                <li>Understand the library's automation systems and the use of computer hardware, software and peripherals, including online collaborations tools (internet, the world wide web, and social working sites)</li>
                                <li>Develop information technology solutions (e.g., library automation system, website, e-mail system, etc.)</li>
                                <li>Conduct significant research projects that benefit the library and the organization</li>
                                <li>Evaluate and debate information policy (e.g. copyright law, plagiarism, and cybercrimes) and ethical issues applicable in a local, national, and global context</li>
                                <li>Participate in continuing education activities organized by library associations and other entities</li>
                                <li>Participate in the generation of new knowledge or research and development projects</li>
                            </ol>
                        </section>

                        <!-- Career Opportunities Section -->
                        <section class="career-opportunities-section">
                            <h2>Career Opportunities</h2>
                            
                            <div class="career-grid">
                                <div class="career-category">
                                    <h3>Teacher Education</h3>
                                    <ul>
                                        <li>Practice teaching profession at the elementary level both in public and private schools</li>
                                        <li>Practice teaching profession at the secondary level both in public and private schools</li>
                                        <li>Practice teaching and supporting young children's development</li>
                                        <li>Practice teaching profession in the field of special education, therapists, clinicians</li>
                                        <li>Physical education teacher in Basic Education</li>
                                        <li>Dance and sports club moderator</li>
                                        <li>School-based sports program and events moderator/coordinator</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Library Information</h3>
                                    <ul>
                                        <li>Archivist, Conservator and records manager</li>
                                        <li>Information Officer/Director</li>
                                        <li>Database Designer / Administrator</li>
                                        <li>Web Designer / Administrator</li>
                                        <li>Audio-visual Designer</li>
                                        <li>Systems Analyst</li>
                                        <li>Indexer/Abstractor</li>
                                    </ul>
                                </div>
                            </div>
                        </section>

                        <!-- Quality Policy Section -->
                        <section class="objectives-section">
                            <h2>Quality Policy</h2>
                            <p>The College of Teacher Education is committed to producing competent and competitive teaching professionals and information science specialists who are holistic graduates, achievers of life imbued with Christian values, and research-oriented leaders in quality education.</p>
                            
                            <p>Pursuing our commitment through:</p>
                            <ul>
                                <li>Relevant and updated curriculum</li>
                                <li>Student-centered curricular and extra-curricular programs</li>
                                <li>Adept delivery mechanism</li>
                                <li>Intellectual and professional fulfillment of faculty and staff</li>
                                <li>Quality Research</li>
                                <li>College social and environmental responsibility</li>
                                <li>Involvement of all stakeholders in the growth and development of the college</li>
                                <li>Continuous quality improvement upgrading of infrastructure and facilities</li>
                                <li>A resilient congenial facilitative learning environment</li>
                                <li>Spiritual formation - Stewards of God's creation</li>
                            </ul>
                        </section>

                        <!-- Quality Objectives Section -->
                        <section class="objectives-section">
                            <h2>Quality Objectives</h2>
                            <ol>
                                <li>To develop professionals with appropriate technical and professional competencies for the international market.</li>
                                <li>To achieve recognition as one of the respected universities in the country.</li>
                                <li>To serve as a venue for knowledge generation and dissemination.</li>
                                <li>To uplift the quality of life of people living in the adopted community.</li>
                                <li>To deliver quality services to the clientele.</li>
                            </ol>
                        </section>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>College:</strong> College of Teacher Education</li>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Programs:</strong> 7 Bachelor's Programs</li>
                            <li><strong>Focus:</strong> Teaching & Information Science</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Programs Offered</h3>
                        <ul>
                            <li><strong>BEED:</strong> Elementary Education</li>
                            <li><strong>BECE:</strong> Early Childhood Education</li>
                            <li><strong>BSNED:</strong> Special Needs Education</li>
                            <li><strong>BPE:</strong> Physical Education</li>
                            <li><strong>BLIS:</strong> Library & Information Science</li>
                            <li><strong>EMAP:</strong> Educational Modular Approach</li>
                            <li><strong>BSED:</strong> Secondary Education (5 majors)</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Key Features</h3>
                        <ul>
                            <li>Pedagogical expertise development</li>
                            <li>Information specialist training</li>
                            <li>Human values integration</li>
                            <li>Globally competitive preparation</li>
                            <li>Research-oriented approach</li>
                            <li>Christian values foundation</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Career Preparation</h3>
                        <ul>
                            <li>Elementary & Secondary Teaching</li>
                            <li>Special Education</li>
                            <li>Physical Education</li>
                            <li>Library & Information Science</li>
                            <li>Educational Administration</li>
                            <li>Research & Development</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Email:</strong><br>
                        <a href="mailto:education@uphsl.edu.ph">education@uphsl.edu.ph</a></p>
                        
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
