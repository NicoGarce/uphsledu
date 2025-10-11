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

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/ENGINEERING.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="img/logo/logo.png" alt="Engineering and Architecture Logo">
            </div>
            <div class="banner-content">
                <h1>College of Engineering, Architecture and Aviation</h1>
                <p>The College of Engineering, Architecture & Aviation adheres to the philosophy of the university which believes and invokes Divine Guidance in the betterment of the quality of life through national development and transformation, which are predicated upon the quality of education of its people.</p>
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
                            <p>The College of Engineering shall provide students with responsive Engineering education through state-of-the-art methods of instruction, research & industry practice.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>The College of Engineering as a center of excellence in Engineering education, research & development.</p>
                        </section>

                        <!-- Quality Policy Section -->
                        <section class="objectives-section">
                            <h2>Quality Policy</h2>
                            <p>College of Engineering is committed to continually improve the quality of Engineering education which will mould the students with moral character, enhance the competence of the faculty, collaborate with industry and other institutions for mutual benefit, promote research and development, and adhere to the quality assurance requirements set by the institution.</p>
                        </section>

                        <!-- Quality Objectives Section -->
                        <section class="objectives-section">
                            <h2>Quality Objectives</h2>
                            <p>The college of Engineering as envisioned by our Founder should be able to:</p>
                            <ul>
                                <li>Develop competent and competitive Engineers with Perpetualite values</li>
                                <li>Be recognized as one of the highly reputable Engineering schools at par with ASEAN standards</li>
                                <li>Develop research and programs that address emerging issues in Engineering education and practice</li>
                                <li>Develop and implement sustainable programs based community activities in the country and participate in an international community</li>
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
                                <h4>Guided by the University Mission, the Perpetualite Architects must be able to:</h4>
                                <ul>
                                    <li>Perform standard competencies in accordance with the scope of the global and local practice of architecture</li>
                                    <li>Show traits of professionalism, sense of responsibility equality and patriotism</li>
                                    <li>Receptiveness to new ideas and knowledge through scientific research</li>
                                    <li>Direct and focus the thrust of architecture education to the needs and demands of society and its integration into the social, economic, cultural and environmental aspects of nation building</li>
                                    <li>Instill understanding of the basic philosophy and fundamental principles of the multi-dimensional aspects of architecture, and the direct relationship between man and his environment</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Creation of Architectural solution by applying knowledge in history, theory, planning, building technology and utilities, structural concepts and professional practice</li>
                                    <li>Use of concepts and principles from specialized fields and allied disciplines into various architectural problems</li>
                                    <li>Preparation of contract documents, technical reports and other legal documents used in architectural practice adhering to applicable laws, standards and regulations</li>
                                    <li>Interpretation and application of relevant laws, codes, charters and standards of architecture and the built environment</li>
                                    <li>Application of research methods to address architectural problems</li>
                                    <li>Use of various information and communication technology (ICT) media for architectural solutions, presentation and techniques in design and construction</li>
                                    <li>Acquisition of entrepreneurial and business acumen relevant to Architecture practice</li>
                                    <li>Involvement in the management of the construction works and Building administration</li>
                                </ul>
                            </div>
                        </section>

                        <!-- Career Opportunities Section -->
                        <section class="career-opportunities-section">
                            <h2>Career Opportunities</h2>
                            
                            <div class="career-grid">
                                <div class="career-category">
                                    <h3>Civil Engineering</h3>
                                    <ul>
                                        <li>Water Resources Engineer</li>
                                        <li>Construction Engineer</li>
                                        <li>Structural Engineer</li>
                                        <li>Geotechnical Engineer</li>
                                        <li>Highway Transportation Engineer</li>
                                        <li>Site Project Engineer</li>
                                        <li>Engineering Educator & Researcher</li>
                                        <li>Sanitary / Environmental Engineer</li>
                                        <li>Physical Plant Maintenance Administrator</li>
                                        <li>Contractor</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Computer Engineering</h3>
                                    <ul>
                                        <li>System Engineer / Developer/Manager</li>
                                        <li>Network System Administrator/Manager</li>
                                        <li>Data Communication Engineer</li>
                                        <li>Project Engineer/Manager</li>
                                        <li>Technical Support Engineer</li>
                                        <li>Test Engineer</li>
                                        <li>Quality Assurance Engineer</li>
                                        <li>Technopreneur</li>
                                        <li>System Analyst/Designer</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Electrical Engineering</h3>
                                    <ul>
                                        <li>Design / Production Engineer</li>
                                        <li>Power System Engineer</li>
                                        <li>Construction & Project Engineer</li>
                                        <li>Illumination Engineer</li>
                                        <li>Maintenance Engineer</li>
                                        <li>Engineering Educator & Researcher</li>
                                        <li>Distribution Engineer</li>
                                        <li>Software Developer</li>
                                        <li>Instrumentation & Control Engineer</li>
                                        <li>Safety Engineer</li>
                                        <li>Electrical Design Inspector</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Electronics Engineering</h3>
                                    <ul>
                                        <li>Electronics / Communications Systems</li>
                                        <li>Biomedical Engineer</li>
                                        <li>Industrial Electronics Engineer</li>
                                        <li>Instrumentation & Control Engineer</li>
                                        <li>Broadcast Engineer</li>
                                        <li>Designer / Engineer</li>
                                        <li>Semiconductor Engineer</li>
                                        <li>Sales Engineer</li>
                                        <li>Entrepreneur</li>
                                        <li>Operations and Maintenance Engineer</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Industrial Engineering</h3>
                                    <ul>
                                        <li>Facilities Engineer</li>
                                        <li>Production Engineer</li>
                                        <li>Methods Engineer</li>
                                        <li>Sales Engineer</li>
                                        <li>Manufacturing/Process Engineer</li>
                                        <li>Strategic Planning Engineer</li>
                                        <li>Information System Engineer</li>
                                        <li>Maintenance Engineer</li>
                                        <li>Product Design Engineer</li>
                                        <li>Engineering Educator & Researcher</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Mechanical Engineer</h3>
                                    <ul>
                                        <li>Plant Engineer / Manager</li>
                                        <li>Production Engineer</li>
                                        <li>HVAC Engineer</li>
                                        <li>Maintenance/Design Engineer</li>
                                        <li>Construction/Project Engineer</li>
                                        <li>Sales Engineer</li>
                                        <li>Manufacturing Engineer</li>
                                        <li>Instrumentation & Control Engineer</li>
                                        <li>Biomedical Engineer</li>
                                        <li>Pollution Control Officer</li>
                                        <li>Safety Engineer</li>
                                        <li>Engineering Educator & Researcher</li>
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
                        <h3>Contact Information</h3>
                        <p><strong>Email:</strong><br>
                        <a href="mailto:engineering@uphsl.edu.ph">engineering@uphsl.edu.ph</a></p>
                        
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
