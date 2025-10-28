<?php
/**
 * UPHSL Engineering and Architecture Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the Engineering and Architecture program at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Engineering & Architecture";

// Set base path for assets
$base_path = '../';

// Set background image path
$bg_image = 'img/banner/ENGINEERING.jpg';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('<?php echo $bg_image; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="<?php echo $base_path; ?>programs/img/logo/logo.png" alt="Engineering and Architecture Logo">
            </div>
            <div class="banner-content">
                <h1>College of Engineering & Architecture</h1>
                <p>Globally recognized hub of academic excellence, leading transformative advancements in engineering and architectural disciplines through innovative research, cutting-edge technology, and socially responsive education.</p>
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
                            <p>Guided by a commitment to academic excellence, the College of Engineering & Architecture is dedicated to provide accessible and high-quality education aligned with the global sustainable development goals through outcomes-based education, industry collaboration and innovative research.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>The College of Engineering aims to be a globally recognized hub of academic and excellence, leading transformative advancements in engineering and architectural disciplines through innovative research, cutting-edge technology, and socially responsive education for future generations.</p>
                        </section>

                        <!-- Graduate Attributes Section -->
                        <section class="mission-vision-section">
                            <h2>Graduate Attributes</h2>
                            <ul>
                                <li>Professionally Competent</li>
                                <li>Effective Communicator & Collaborator</li>
                                <li>Reflective Learner</li>
                                <li>Problem Solver & Design Specialist</li>
                                <li>Socially Responsible & Ethical Person</li>
                            </ul>
                        </section>

                        <!-- Quality Policy Section -->
                        <section class="objectives-section">
                            <h2>Quality Policy</h2>
                            <p>The College of Engineering & Architecture is committed to continually improve the quality of Engineering and Architecture education that develops competent, innovative, and ethical professionals dedicated to nation-building and sustainable development. We uphold the University's Mission of nurturing Helpers of God by promoting academic excellence, research, industry collaboration, and community service guided by moral integrity and social responsibility.</p>
                        </section>

                        <!-- Quality Objectives Section -->
                        <section class="objectives-section">
                            <h2>Quality Objectives</h2>
                            <ul>
                                <li>Improve international readiness of students through curriculum enhancements, certification training and value formation programs.</li>
                                <li>Be at par with the national passing percentage in the licensure examinations given by the Professional Regulation Commission for Engineering and Architecture programs.</li>
                                <li>Maintain the institutional autonomous status and accreditation of all programs by local and international certifying bodies.</li>
                                <li>Promote the creation and dissemination of knowledge through faculty and student engagement in research presentations at local and international conferences, publication in reputable journals, and the application of research outcomes in industry and community extension programs.</li>
                                <li>Actively engage in community extension programs that improve the socio-economic, health, educational, and environmental conditions of the adopted community.</li>
                                <li>Enhance teaching and learning through the strategic integration of new technologies and educational practices.</li>
                                <li>Develop and enforce intellectual property policies, raise awareness, and support IP registration to foster a culture of innovation and protect authorship.</li>
                            </ul>
                        </section>

                        <!-- Programs Section -->
                        <section class="programs-section">
                            <h2>Programs Offered</h2>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Civil Engineering</h3>
                                <p>Civil Engineering is one of the most diverse branches of engineering in terms of range of problems that fall within its preview and in the range of knowledge required to solve those problems. Civil Engineer plans, designs, construct and maintain a large variety of structures and facilities for public, commercial and industrial use. These structures include residential, office and factory building; highways, railroads, airports, tunnels, bridges, harbors, channels and pipelines. It also deals with other many facilities that are a part of the transportation systems of most countries, as well as sewage and waste disposal systems that add convenience and safeguard of our health.</p>
                                
                                <h4>Guided by the University Mission, the Perpetualite Civil Engineers must be able to:</h4>
                                <ul>
                                    <li>Utilize their technical knowledge and skills to thrive in Civil Engineering practice</li>
                                    <li>Apply professional ethics with a deep sense of Christian Values in fulfilling the needs of the society</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Apply knowledge of mathematics and sciences to solve complex engineering problems</li>
                                    <li>Design and conduct experiments, as well as to analyze and interpret data</li>
                                    <li>Design a system, component, or process to meet desired needs within realistic constraints in accordance with standards</li>
                                    <li>Function on multi-disciplinary and multi-cultural teams</li>
                                    <li>Identify, formulate and solve complex civil engineering problems</li>
                                    <li>Understand professional and ethical responsibility</li>
                                    <li>Communicate effectively civil engineering activities with the engineering community and with society at large</li>
                                    <li>Understand the impact of civil engineering solutions in a global, economic, environmental, and societal context</li>
                                    <li>Recognize the need for and engage in lifelong learning</li>
                                    <li>Know contemporary issues</li>
                                    <li>Use techniques, skills, and modern engineering tools necessary for civil engineering practice</li>
                                    <li>Know and understand engineering and management principles as a member and leader in a team to manage projects in multidisciplinary environments</li>
                                    <li>Understand at least one specialized field of civil engineering practice</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Computer Engineering</h3>
                                <p>Computer Engineering is a profession that applies engineering principles and methodologies in the design, analysis, and application of computing structures that involve hardware, software, and the integration of both. The program includes subjects in computer hardware system development and design, computer-based controllers system, data communication and network engineering, robotics and artificial intelligence.</p>
                                
                                <h4>Guided by the University Mission, the Perpetualite Computer Engineers must be able to:</h4>
                                <ul>
                                    <li>Utilize their technical knowledge and skills to thrive in Computer Engineering practice</li>
                                    <li>Apply professional ethics with a deep sense of Christian Values in fulfilling the needs of the society</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Apply knowledge of mathematics and sciences to solve complex engineering problems</li>
                                    <li>Design and conduct experiments, as well as to analyze and interpret data</li>
                                    <li>Design a system, component, or process to meet desired needs within realistic constraints such as economic, environmental, social, political, ethical, health and safety, manufacturability, and sustainability, in accordance with standards</li>
                                    <li>Function on multi-disciplinary teams</li>
                                    <li>Identify, formulate and solve complex engineering problems</li>
                                    <li>Understand professional and ethical responsibility</li>
                                    <li>Communicate Effectively</li>
                                    <li>Understand the impact of engineering solutions in a global, economic, environmental, and societal context</li>
                                    <li>Recognize the need for, and an ability to engage in lifelong learning</li>
                                    <li>Know contemporary issues</li>
                                    <li>Use techniques, skills, and modern engineering tools necessary for engineering practice</li>
                                    <li>Know and understand engineering and management principles as a member and leader in a team to manage projects in multidisciplinary environments</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Electrical Engineering</h3>
                                <p>Electrical Engineering is concerned with the generation, distribution, and use of electric power. Its products include electric generators, transformers and other kinds of motors. It is a five-year program designed to develop multi-skilled, technically competent engineers trained in electronics, energy conversion, power generation, electrical design and allied engineering sciences.</p>
                                
                                <h4>Guided by the University Mission, the Perpetualite Electrical Engineers must be able to:</h4>
                                <ul>
                                    <li>Utilize their technical knowledge and skills to thrive in Electrical Engineering Practice</li>
                                    <li>Apply professional ethics with a deep sense of Christian Values in fulfilling the needs of the society</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Apply knowledge of mathematics and sciences to solve complex engineering problems</li>
                                    <li>Develop and conduct appropriate experimentation, analyze and interpret data</li>
                                    <li>Design a system, component, or process to meet desired needs within realistic constraints such as economic, environmental, social, political, ethical, health and safety, manufacturability, and sustainability, in accordance with standards</li>
                                    <li>Function effectively on multi-disciplinary and multi-cultural teams that establish goals, plan tasks, and meet deadlines</li>
                                    <li>Identify, formulate and solve complex problems in electrical engineering</li>
                                    <li>Recognize ethical and professional responsibilities in engineering practice</li>
                                    <li>Communicate effectively with a range of audiences</li>
                                    <li>Understand the impact of engineering solutions in a global, economic, environmental, and societal context</li>
                                    <li>Recognize the need for additional knowledge and engage in lifelong learning</li>
                                    <li>Articulate and discuss the latest developments in the field of electrical engineering</li>
                                    <li>Apply techniques, skills, and modern engineering tools necessary for electrical engineering practice</li>
                                    <li>Know and understand engineering and management principles as a member and leader in a team to manage projects in multidisciplinary environments</li>
                                    <li>Demonstrate knowledge and understanding of engineering and management principles as a member and/or leader in a team to manage projects in multidisciplinary environments</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Electronics Engineering</h3>
                                <p>Electronics Engineering is a branch of engineering that integrates available and emerging technologies with knowledge of mathematics, natural, social and applied sciences to conceptualize, design and implement new, improved, or innovative electronic, computer and communication systems, devices, goods, services and processes.</p>
                                <p>An Electronics Engineer is endowed with spiritual, moral, and ethical values, mindful of safety concerns and guided with responsibility to society and environment in the discharge of his/her functions.</p>
                                
                                <h4>Guided by the University Mission, the Perpetualite Electronics Engineers must be able to:</h4>
                                <ul>
                                    <li>Utilize their technical knowledge and skills to thrive in Electronics Engineering practice</li>
                                    <li>Apply professional ethics with a deep sense of Christian Values in fulfilling the needs of the society</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Apply knowledge of mathematics and sciences to solve complex engineering problems</li>
                                    <li>Design and conduct experiments, as well as to analyze and interpret data</li>
                                    <li>Design a system, component, or process to meet desired needs within realistic constraints such as economic, environmental, social, political, ethical, health and safety, manufacturability, and sustainability, in accordance with standards</li>
                                    <li>Function on multi-disciplinary teams</li>
                                    <li>Identify, formulate and solve complex engineering problems</li>
                                    <li>Recognize ethical and professional responsibilities in engineering practice</li>
                                    <li>Communicate effectively</li>
                                    <li>Identify the impact of engineering solutions in a global, economic, environmental, and societal context</li>
                                    <li>Recognize the need for, and an ability to engage in lifelong learning</li>
                                    <li>Apply knowledge on contemporary issues</li>
                                    <li>Use techniques, skills, and modern engineering tools necessary for engineering practice</li>
                                    <li>Apply knowledge of engineering and management principles as a member and leader in a team to manage projects in multidisciplinary environments</li>
                                    <li>Understand at least one specialized field of electronics engineering practice</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Industrial Engineering</h3>
                                <p>Industrial Engineering is primarily concerned with the analysis of the processes of production and the design of methods for making them more efficient. Hence, it involves designing of plant facilities, establishing work standards through time and motion studies, developing wage scales based on an analysis of required job skill levels, and determining quality control procedures.</p>
                                
                                <h4>Guided by the University Mission, the Perpetualite Industrial Engineers must be able to:</h4>
                                <ul>
                                    <li>Utilize their technical knowledge and skills to thrive in Industrial Engineering practice</li>
                                    <li>Apply professional ethics with a deep sense of Christian Values in fulfilling the needs of the society</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Apply knowledge of mathematics and sciences to solve complex industrial engineering problems</li>
                                    <li>Design and Conduct experiments, as well as to analyze and interpret data</li>
                                    <li>Design a system, component, or process to meet desired needs within realistic constraints such as economic, environmental, social, political, ethical, health and safety, manufacturability, and sustainability, in accordance with standards</li>
                                    <li>Function effectively on multi-disciplinary and multi-cultural teams that establish goals, plan tasks, and meet deadlines</li>
                                    <li>Identify, formulate and solve complex industrial engineering problems</li>
                                    <li>Communicate effectively</li>
                                    <li>Understand the impact of engineering solutions in a global, economic, environmental, and societal context</li>
                                    <li>Recognize the need for, and an ability to engage in lifelong learning</li>
                                    <li>Know contemporary issues</li>
                                    <li>Use techniques, skills, and modern engineering tools necessary for engineering practice</li>
                                    <li>Know and understand engineering and management principles as a member and leader in a team to manage projects in multidisciplinary environments</li>
                                    <li>Design, develop, implement, and improve integrated systems that include people, materials, information, equipment and energy</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Mechanical Engineering</h3>
                                <p>Mechanical Engineering is a profession that concerns itself with mechanical design, energy conversion fuel and combustion technologies, heat transfer, materials, noise control and acoustics, manufacturing processes, rail transportation, automatic control, product safety and reliability, solar energy and technological impacts to the society.</p>
                                <p>Mechanical Engineering deals with the study of prime movers and functional mechanism. It is also concerned with the means of converting energy to useful mechanical forms. The specialty is machine oriented; the mechanical engineer's creations involve motion in contrast to other branches of Engineering where most creations are static.</p>
                                
                                <h4>Guided by the University Mission, the Perpetualite Mechanical Engineers must be able to:</h4>
                                <ul>
                                    <li>Utilize their technical knowledge and skills to thrive in Mechanical Engineering practice</li>
                                    <li>Apply professional ethics with a deep sense of Christian Values in fulfilling the needs of the society</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Apply knowledge of mathematics and sciences to solve complex Mechanical engineering problems</li>
                                    <li>Design and conduct experiments, as well as to analyze and interpret data</li>
                                    <li>Design a system, component, or process to meet desired needs within realistic constraints such as economic, environmental, social, political, ethical, health and safety, manufacturability, and sustainability, in accordance with standards</li>
                                    <li>Function on multi-disciplinary teams and multi-cultural teams</li>
                                    <li>Identify, formulate and solve complex Mechanical engineering problems</li>
                                    <li>Understand professional and ethical responsibility</li>
                                    <li>Communicate effectively</li>
                                    <li>Understand the impact of mechanical engineering solutions in a global, economic, environmental, and societal context</li>
                                    <li>Recognize the need for, and an ability to engage in lifelong learning</li>
                                    <li>Apply knowledge on contemporary issues</li>
                                    <li>Know contemporary issues</li>
                                    <li>Use techniques, skills, and modern engineering tools necessary for mechanical engineering practice</li>
                                    <li>Know and understand engineering and management principles as a member and leader in a team to manage projects in multidisciplinary environments</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Architecture</h3>
                                <p>The Bachelor of Science in Architecture is a five-year undergraduate program designed to develop competent, creative, and ethical architects who can contribute to nation-building through sustainable and responsive design. The curriculum integrates artistic, scientific, and technical knowledge in the planning, design, and construction of buildings and communities for global competitiveness. It provides a balanced learning experience through design studios, technical courses, research, and professional practice.</p>
                                
                                <h4>Program Educational Objectives</h4>
                                <p>Guided by the University Mission, the Perpetualite Architects must be able to:</p>
                                <ol>
                                    <li>Optimize their technical knowledge and skills to thrive in Architecture practice</li>
                                    <li>Apply professional ethics with a deep sense of Christian values in fulfilling the needs of society</li>
                                </ol>
                                
                                <h4>Student Outcomes:</h4>
                                <p>By the time of graduation, the students of the Architecture program should be able to:</p>
                                <ul>
                                    <li>Apply knowledge of mathematics, science, and architecture to solve complex architectural problems</li>
                                    <li>Use architectural research methods including data collection and analysis to address complex architectural problems</li>
                                    <li>Understand and apply the history and theory of architecture, and the culture and heritage of architecture in the local and global context</li>
                                    <li>Apply architectural principles and design processes in producing a set of architectural solutions that demonstrate aesthetic, functional, and technical soundness</li>
                                    <li>Use modern technologies and digital tools relevant to architectural design and practice</li>
                                    <li>Understand and apply laws, codes, and regulations related to building construction, safety, and environmental sustainability</li>
                                    <li>Prepare contract documents, cost estimates, and technical specifications in accordance with professional standards</li>
                                    <li>Work effectively in multidisciplinary and multicultural teams in architectural and construction projects</li>
                                    <li>Communicate effectively in oral, written, and graphical forms with professionals and stakeholders</li>
                                    <li>Demonstrate ethical and professional responsibility in the practice of architecture</li>
                                    <li>Understand contemporary issues and apply sustainable and inclusive design solutions to address societal needs</li>
                                    <li>Engage in lifelong learning and keep abreast with the latest trends and developments in the field</li>
                                </ul>
                                
                                <h4>Career Opportunities</h4>
                                <p>Graduates of the Bachelor of Science in Architecture program can pursue a wide range of careers in the architecture, design, construction, and allied industries, both in the Philippines and abroad.</p>
                                
                                <div class="career-grid" style="margin-top: 20px;">
                                    <div class="career-category">
                                        <h3>A. Professional Practice (Licensed Architects)</h3>
                                        <ul>
                                            <li>Registered and Licensed Architect (RLA)</li>
                                            <li>Architectural Designer</li>
                                            <li>Project Architect / Design Architect</li>
                                            <li>Site or Supervising Architect</li>
                                            <li>Urban Planner or Urban Designer</li>
                                            <li>Heritage or Conservation Architect</li>
                                            <li>Landscape Architect (with further specialization)</li>
                                            <li>Sustainable Design Consultant / Green Building Specialist</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="career-category">
                                        <h3>B. Technical and Construction Fields</h3>
                                        <ul>
                                            <li>Project Manager / Construction Manager</li>
                                            <li>Building Information Modeling (BIM) Specialist</li>
                                            <li>CAD Designer / Draftsperson</li>
                                            <li>Estimator / Specifications Writer</li>
                                            <li>Facilities Planner / Building Administrator</li>
                                            <li>Construction Supervisor / Site Engineer</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="career-category">
                                        <h3>C. Design and Creative Industries</h3>
                                        <ul>
                                            <li>Interior Designer (with specialization or collaboration license)</li>
                                            <li>Set Designer / Exhibition Designer</li>
                                            <li>Graphic or 3D Visual Artist</li>
                                            <li>Furniture and Product Designer</li>
                                            <li>Lighting Designer</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="career-category">
                                        <h3>D. Research, Education, and Government Service</h3>
                                        <ul>
                                            <li>Architecture Educator / Professor</li>
                                            <li>Researcher in Architecture and Urban Studies</li>
                                            <li>Technical Officer / Planning Officer in LGUs or Government Agencies</li>
                                            <li>Housing and Urban Development Specialist</li>
                                            <li>Environmental and Policy Planner</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="career-category">
                                        <h3>E. Entrepreneurial and Allied Professions</h3>
                                        <ul>
                                            <li>Real Estate Developer or Consultant</li>
                                            <li>Construction Firm Owner / Design-Build Contractor</li>
                                            <li>Property Management Professional</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Career Opportunities Section -->
                        <section class="career-opportunities-section">
                            <h2>Career Opportunities</h2>
                            
                            <div class="career-grid">
                                <div class="career-category">
                                    <h3>Civil Engineering</h3>
                                    <ul>
                                        <li>Structural Engineer</li>
                                        <li>Geotechnical Engineer</li>
                                        <li>Transportation or Highway Engineer</li>
                                        <li>Water Resources Engineer</li>
                                        <li>Construction or Project Manager</li>
                                        <li>Sanitary or Environmental Engineer</li>
                                        <li>Building or Infrastructure Consultant</li>
                                        <li>Researcher</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Computer Engineering</h3>
                                    <ul>
                                        <li>Computer Engineers</li>
                                        <li>Network Engineers or System Administrators</li>
                                        <li>Embedded Systems Developers</li>
                                        <li>Software and Hardware Designers</li>
                                        <li>Robotics Engineers</li>
                                        <li>IT Project Managers</li>
                                        <li>Researchers</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Electrical Engineering</h3>
                                    <ul>
                                        <li>Power System Engineer</li>
                                        <li>Electrical Design Engineer</li>
                                        <li>Maintenance and Operations Engineer</li>
                                        <li>Control and Instrumentation Engineer</li>
                                        <li>Project or Plant Engineer</li>
                                        <li>Energy Systems Analyst or Consultant</li>
                                        <li>Research and Development Engineer</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Electronics Engineering</h3>
                                    <ul>
                                        <li>Electronics Design Engineer</li>
                                        <li>Telecommunications Engineer</li>
                                        <li>Instrumentation and Control Engineer</li>
                                        <li>Broadcast or Audio-Video Systems Engineer</li>
                                        <li>Embedded Systems or IoT Engineer</li>
                                        <li>Semiconductor Process Engineer</li>
                                        <li>Project Engineer or Technical Consultant</li>
                                        <li>Researcher or Innovator</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Industrial Engineering</h3>
                                    <ul>
                                        <li>Industrial Engineer</li>
                                        <li>Production or Operations Manager</li>
                                        <li>Quality Assurance Engineer</li>
                                        <li>Systems Analyst or Process Improvement Specialist</li>
                                        <li>Supply Chain or Logistics Engineer</li>
                                        <li>Project Management Professional</li>
                                        <li>Operations Research Analyst</li>
                                        <li>Systems Engineer</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Mechanical Engineering</h3>
                                    <ul>
                                        <li>Mechanical Design Engineer</li>
                                        <li>Power Plant Engineer</li>
                                        <li>Maintenance and Operations Engineer</li>
                                        <li>Manufacturing or Production Engineer</li>
                                        <li>HVAC (Heating, Ventilation, and Air Conditioning) Engineer</li>
                                        <li>Automotive or Aerospace Engineer</li>
                                        <li>Energy Systems Engineer</li>
                                        <li>Project Engineer</li>
                                        <li>Researcher</li>
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
                            <li><strong>College:</strong> Engineering, Architecture & Aviation</li>
                            <li><strong>Duration:</strong> 5 years</li>
                            <li><strong>Programs:</strong> 6 Bachelor's Programs</li>
                            <li><strong>Focus:</strong> Engineering Excellence & Innovation</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Programs Offered</h3>
                        <ul>
                            <li><strong>BSCE:</strong> Civil Engineering</li>
                            <li><strong>BSCpE:</strong> Computer Engineering</li>
                            <li><strong>BSEE:</strong> Electrical Engineering</li>
                            <li><strong>BSECE:</strong> Electronics Engineering</li>
                            <li><strong>BSIE:</strong> Industrial Engineering</li>
                            <li><strong>BSME:</strong> Mechanical Engineering</li>
                            <li><strong>BS Architecture:</strong> Architecture</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Key Features</h3>
                        <ul>
                            <li>Center of excellence in engineering education</li>
                            <li>State-of-the-art methods of instruction</li>
                            <li>Research & industry practice integration</li>
                            <li>Perpetualite values integration</li>
                            <li>ASEAN standards compliance</li>
                            <li>Divine guidance philosophy</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Career Preparation</h3>
                        <ul>
                            <li>Civil & Structural Engineering</li>
                            <li>Computer & Electronics Engineering</li>
                            <li>Electrical & Power Systems</li>
                            <li>Industrial & Manufacturing</li>
                            <li>Mechanical & HVAC Systems</li>
                            <li>Architecture & Design</li>
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



