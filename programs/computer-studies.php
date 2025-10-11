<?php
/**
 * UPHSL Computer Studies Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the Computer Studies program at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Computer Studies";

// Set base path for assets
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/COMPUTER STUDIES.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="img/logo/CCS-Logo.png" alt="Computer Studies Logo">
            </div>
            <div class="banner-content">
                <h1>College of Computer Studies</h1>
                <p>The College of Computer Studies (CCS) is committed to its three-pronged vision of continually sharing knowledge and expertise through teaching, engaging in Computer Science research and Information Technology product development and rendering service to in need.</p>
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
                            <p>We provide students with the knowledge and understanding of emerging technologies so that they become lifelong learners imbued with Perpetualite character and values, and are able to adapt to technological changes and function in an informational society through updated, specialized, and individualized instructions and support programs.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>Leader in providing IT Professionals who are competitive with the best in the world. We challenge, encourage, and support all learners to be responsible for developing their abilities with respect for self, others, and the environment.</p>
                        </section>

                        <!-- History Section -->
                        <section class="programs-section">
                            <h2>History of College</h2>
                            <p>The increasing demands and applications of computers and information technology in the different fields of endeavor required the need to provide more extensive and specialized training in computers and research. This paved the way for the opening of the BS Computer Science three years later.</p>
                            <p>Our advanced and state-of-the-art computer facilities simulating the corporate environment, a component lineup of faculty members, an up-to-date and well-balanced curriculum, and our continuing linkages with the different companies are keystones in delivering the kind of training that is at par with the institution's commitment to character building. With this as our mission coupled with massive technical training will be our basic foundation to help build a better nation.</p>
                            <p>A leader in providing IT Professionals who are competitive with the best in the world. We challenge, encourage, and support all learners to be responsible for developing their abilities with respect for self, others, and the environment.</p>
                        </section>

                        <!-- Achievements Section -->
                        <section class="programs-section">
                            <h2>Prestigious Achievements & Recognition</h2>
                            <div class="achievements-grid">
                                <div class="achievement-item">
                                    <h3>🏆 PACUCOA Level IV - Accredited Status</h3>
                                    <p>Highest level of accreditation from the Philippine Association of Colleges and Universities Commission on Accreditation, recognizing our exceptional academic standards and institutional excellence in computer studies education.</p>
                                </div>
                                
                                <div class="achievement-item">
                                    <h3>🌟 Center for Development</h3>
                                    <p>Designated as a premier Center for Development, serving as a beacon of innovation and excellence in information technology education, research, and community service across the region.</p>
                                </div>
                                
                                <div class="achievement-item">
                                    <h3>🎓 UPHSL Autonomous Status</h3>
                                    <p>Granted autonomous status by the Commission on Higher Education (CHED), affirming our institutional maturity, academic freedom, and capacity to maintain the highest standards of education without external supervision.</p>
                                </div>
                                
                                <div class="achievement-item">
                                    <h3>✅ ISO 9001:2015 Certified - Bureau Veritas</h3>
                                    <p>Internationally recognized quality management system certification, demonstrating our unwavering commitment to continuous improvement, excellence in service delivery, and adherence to global quality standards in education.</p>
                                </div>
                                
                                <div class="achievement-item">
                                    <h3>🚀 Industry-Leading Faculty</h3>
                                    <p>Comprised of distinguished professors, industry veterans, and certified professionals who bring decades of real-world experience and cutting-edge expertise to our classrooms.</p>
                                </div>
                                
                                <div class="achievement-item">
                                    <h3>💡 Innovation Hub</h3>
                                    <p>Recognized as a leading innovation hub that bridges the gap between academia and industry, producing graduates who are immediately ready to contribute to the rapidly evolving technology landscape.</p>
                                </div>
                            </div>
                        </section>

                        <!-- Certifications Section -->
                        <section class="programs-section">
                            <h2>Certifications</h2>
                            <ul>
                                <li>Oracle Certification</li>
                                <li>MikroTik Certified Network Associate Certifications</li>
                                <li>IC3 GS5 Digital Literacy Certification (Living Online)</li>
                                <li>MTA - Microsoft Technology Associate Certification</li>
                                <li>Microsoft Office Specialist Certification</li>
                                <li>Hewlett Packard Enterprise Certification</li>
                                <li>Load Runner/ Unified Functional Testing Certification</li>
                                <li>Adobe Certified Associate Certification</li>
                                <li>SAP - System Applications and Products Certification</li>
                            </ul>
                        </section>

                        <!-- Software Licenses Section -->
                        <section class="programs-section">
                            <h2>Software Licenses</h2>
                            <ul>
                                <li>Microsoft Office 365 License</li>
                                <li>Adobe System License</li>
                                <li>Microsoft Windows/ Mac OS license</li>
                            </ul>
                        </section>

                        <!-- Online Tools Section -->
                        <section class="programs-section">
                            <h2>Online Tools/ Applications/ LMS for Education</h2>
                            <ul>
                                <li>Google for Education/ Google Suite</li>
                                <li>Moodle - Open-source learning platform</li>
                                <li>Microsoft Teams</li>
                            </ul>
                        </section>

                        <!-- Facilities Section -->
                        <section class="programs-section">
                            <h2>Facilities</h2>
                            <p>Our college is equipped with state-of-the-art facilities to support student learning and development:</p>
                            <ul>
                                <li><strong>Computer Lab 3</strong> - Advanced computer laboratory for specialized computing tasks</li>
                                <li><strong>Mac Lab</strong> - Apple Macintosh laboratory for multimedia and design work</li>
                                <li><strong>Computer Lab 2</strong> - Additional computer facility for research and coursework</li>
                            </ul>
                        </section>

                        <!-- Partnerships Section -->
                        <section class="programs-section">
                            <h2>Partnership/ Linkages</h2>
                            <ul>
                                <li>PSITE (Philippine Society of Information Technology Educators)</li>
                                <li>FrontLearner</li>
                                <li>GAFE (Google for Education)</li>
                                <li>Mikrotik Rizal</li>
                                <li>CodeChum</li>
                            </ul>
                        </section>

                        <!-- Programs Section -->
                        <section class="programs-section">
                            <h2>Programs Offered</h2>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Information Technology, Specialization in Game Development</h3>
                                <p>This program is an applied computer four-year degree course. It is intended for those who want a career in developing computer Technology applications in different fields of endeavor.</p>
                                <p>The BSIT (Bachelor of Science in Information Technology) prepares students to be professionals in this field. By the time they graduate, the students are expected to:</p>
                                <ul>
                                    <li>Have acquired basic IT principles and foundation</li>
                                    <li>Have gained practical knowledge on the installation, operation management, and administration of information systems</li>
                                    <li>And have developed the ability to conceptualize, designing, developing, implementing and maintaining information technology in the field of game development</li>
                                </ul>
                                
                                <h4>Program Educational Objectives:</h4>
                                <ul>
                                    <li>Equipped with creative and technical knowledge, skills, and values in information technology with a specialization in game development</li>
                                    <li>Leaders and program innovators in the field of Information Technology with a specialization in game development</li>
                                    <li>Holistic practitioners upholding high levels of professional ethics conscious of their social and corporate responsibilities</li>
                                    <li>Demonstrate proficiency in organizational communication for effective human relations</li>
                                    <li>Extends entrepreneurial knowledge and skills in computing services to generate opportunities for the community</li>
                                    <li>Values the importance of continuing educational opportunities</li>
                                </ul>
                                
                                <h4>Program Outcomes:</h4>
                                <ul>
                                    <li>Apply knowledge of computing, science, and mathematics appropriate to the practice of being a game developer, programmer, or designer</li>
                                    <li>Understand best practices, standards and the applications to be used in a game development industry</li>
                                    <li>Analyze complex problems, and identify and define the computing requirements appropriate to its solution</li>
                                    <li>Identify and analyze user needs and take them into account in the selection, creation, evaluation, and administration of computer-based systems</li>
                                    <li>Design, develop, and implement an appropriate strategy for the organization of the design and delivery of the product to meet desired needs and requirements under various constraints</li>
                                    <li>Integrate IT-based solutions into the user environment effectively</li>
                                    <li>Apply knowledge through the use of current techniques, skills, tools, and practices necessary for the IT profession</li>
                                    <li>Function effectively as a member or leader of a development team recognizing the different roles within a team to accomplish a common goal</li>
                                    <li>Assist in the creation of an effective IT project plan, development and implementation</li>
                                    <li>Communicate effectively with the computing community and with society at large about complex computing activities through logical writing, research, presentations, and clear instructions</li>
                                    <li>Analyze the local and global impact of computing information technology on individuals, organizations, and society</li>
                                    <li>Understand professional, ethical, legal, security, and social issues and responsibilities in the utilization of information technology</li>
                                    <li>Recognize the need for and engage in planning self-learning and improving performance as a foundation for continuing professional development</li>
                                </ul>
                                
                                <h4>Career Paths:</h4>
                                <ul>
                                    <li>Game Programmer, Game Designer, Graphics Programmer</li>
                                    <li>Technical Artist, Game Tester/QA, Animation Designer and Production</li>
                                    <li>3D Modeler and Rigger, Web Developer, Software Developer/Engineer</li>
                                    <li>Database Administrator, Network Engineer/Administrator, System Analyst/Architect</li>
                                    <li>IT Support Specialist, Information Security Analyst, Human-Computer Interaction (HCI) Specialist/UX Designer, Entrepreneur</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Computer Science, Specialization in Data Science</h3>
                                <p>This program is an applied computer four-year degree course. It is intended for those who want a career in developing computer software applications in different fields of endeavor.</p>
                                <p>The BSCS (Bachelor of Science in Computer Science) prepares students to be professional in this field. By the time they graduate, the students are expected to:</p>
                                <ul>
                                    <li>Have gained knowledge in the field of theoretical analysis and computer programming</li>
                                    <li>And have developed the ability to conceptualize and design new strategies in the field of programming and data science</li>
                                </ul>
                                
                                <h4>Program Educational Objectives:</h4>
                                <ul>
                                    <li>Provide students with knowledge and skills in computer science and data science modeling for data-intensive problem-solving and scientific discovery</li>
                                    <li>Prepare students with machine learning and software engineering skills to design and implement efficient data-driven solutions to real-world problems</li>
                                    <li>Train students for careers and advanced studies in a wide range of applied computing, business intelligence, and data science</li>
                                    <li>Develop leaders with problem-solving skills who are committed in contributing to their fields and society</li>
                                    <li>Provide students with a broad foundation of knowledge and skills, committed to cultivation and commitment to life-long learning</li>
                                    <li>Values the importance of continuing educational opportunities</li>
                                </ul>
                                
                                <h4>Program Outcomes:</h4>
                                <ul>
                                    <li>Apply statistical concepts of data analysis, data collection, modeling, and inference and contemporary computing technologies such as AI, Parallel and distributed computing, and machine learning to solve large-scale practical problems</li>
                                    <li>Identify, analyze, formulate, research literature, and solve complex computing problems and requirements reaching substantiated conclusions using fundamental principles of mathematics, computing sciences, and relevant domain disciplines</li>
                                    <li>Apply data science methods and analytical reasoning to business intelligence domain applications</li>
                                    <li>Employ algorithmic problem-solving skills in developing project-based systems using efficient strategies and implementing solutions through suitable programming language</li>
                                    <li>Design and evaluate solutions for complex computing problems, and design and evaluate systems, components, or processes that meet specified needs with appropriate consideration for public health and safety, cultural, societal, and environmental considerations</li>
                                    <li>Function effectively as an individual and as a member or leader in diverse teams and in multidisciplinary settings</li>
                                    <li>Communicate effectively with the computing community and with society at large about complex computing activities by being able to comprehend and write effective reports, design documentation, make effective presentations, and give and understand clear instructions</li>
                                    <li>Demonstrate professional and ethical responsibility in data security, data sensitivity, and data privacy concerns with data analysis, transparency, and reproducibility</li>
                                    <li>An ability to recognize the legal, social, ethical, and professional issues involved in the utilization of computer technology and be guided by the adoption of appropriate professional, ethical, and legal practices</li>
                                    <li>Recognize the need, and have the ability, to engage in independent learning for continual development as a computing professional</li>
                                </ul>
                                
                                <h4>Career Paths:</h4>
                                <ul>
                                    <li>Software Engineer/Developer, Web Developer (Front-End, Back-End, or Full-Stack)</li>
                                    <li>Mobile App Developer, Data Scientist, Data Analyst</li>
                                    <li>Database Administrator, Network Engineer, Systems Administrator</li>
                                    <li>Security Analyst/Engineer, Technical Consultant, Quality Assurance Engineer/Tester</li>
                                    <li>Human-Computer Interaction (HCI) Specialist, Artificial Intelligence (AI) Specialist</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Entertainment and Multimedia Computing</h3>
                                <p>This four-year degree program is a dynamic and comprehensive degree designed to equip students with the skills and knowledge needed to thrive in the exciting and ever-evolving world of digital media and entertainment. It blends the creative and technical aspects of computing, focusing on the development of interactive and engaging multimedia experiences.</p>
                                
                                <h4>Program Educational Objectives:</h4>
                                <ul>
                                    <li>Equipped with creative and technical knowledge, skills, and values in digital animation products and solutions</li>
                                    <li>Leaders and Innovators in the field of Digital Animation</li>
                                    <li>Demonstrate organizational communication for effective human relations</li>
                                    <li>Extend entrepreneurial knowledge and skills in computing services to generate opportunities for the community</li>
                                    <li>Value the importance of continuing educational opportunities</li>
                                </ul>
                                
                                <h4>Program Outcomes:</h4>
                                <ul>
                                    <li>Ability to apply knowledge of mathematics, physical sciences, and computing sciences to the practice of being an entertainment and multimedia computing professional</li>
                                    <li>Specialized computing knowledge in each applicable field, and the ability to apply such knowledge to provide solutions to actual problems</li>
                                    <li>A knowledge of contemporary issues</li>
                                    <li>An ability to analyze project requirements and to design and implement project prototypes</li>
                                    <li>An ability to recognize, formulate, and solve computing problems</li>
                                    <li>An ability to design, build, improve, and deploy products that meet client needs within realistic constraints</li>
                                    <li>An ability to use the appropriate techniques, skills, and modern computing tools necessary for the practice of being a professional game developer or animator</li>
                                    <li>An ability to work effectively in multi-disciplinary and multi-cultural teams</li>
                                    <li>An ability to effectively communicate orally and in writing using the English Language</li>
                                    <li>An ability to understand and assess local and global impacts of computing on society relevant to professional computing practice and subscription to accepted industry standards</li>
                                    <li>An understanding of the effects and impact of entertainment and multimedia computing projects on nature and society, and of their social and ethical responsibilities</li>
                                    <li>An ability to create or use modified artifacts in consideration of intellectual property rights of the author</li>
                                    <li>An ability to engage in life-long learning and an acceptance of the need to keep current with the development in the specific field of specialization</li>
                                    <li>An ability to demonstrate original creative outputs</li>
                                    <li>An ability to demonstrate innovativeness in their outputs</li>
                                    <li>An ability to demonstrate client-centric service</li>
                                </ul>
                                
                                <h4>Career Paths:</h4>
                                <ul>
                                    <li>Character Modelers/Riggers, Motion Graphics Designers, VFX Artists</li>
                                    <li>Animators, Web Designers/Developers, Multimedia Developers</li>
                                    <li>Video Editors/Producers, Sound Designers/Engineers, Lighting Artists</li>
                                    <li>Texture Artists, Game Programmers, Game Designers</li>
                                    <li>Game Artists, Game Producers, QA Testers, Application Developers</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Information Technology, Major in Cybersecurity and Forensics</h3>
                                <p>This four-year degree program is designed to equip students with the knowledge and skills necessary to protect digital assets and investigate cybercrimes. This comprehensive program covers a wide range of topics, including computer security fundamentals, network security, ethical hacking, digital forensics, information assurance, and IT project management. Students learn to identify vulnerabilities, implement security measures, analyze cyberattacks, and recover digital evidence. The curriculum blends theoretical concepts with hands-on practical experience through laboratory exercises and real-world projects, utilizing industry-standard tools and techniques. Graduates of this program will be prepared for diverse careers in cybersecurity and digital forensics, such as security analysts, penetration testers, forensics investigators, and security administrators, contributing to the growing need for skilled professionals in the digital age. The program also emphasizes the importance of ethical conduct and professional responsibility in the field of information technology.</p>
                                
                                <h4>Program Educational Objectives:</h4>
                                <ul>
                                    <li>Equipped with creative and technical knowledge, skills and values in information technology in cybersecurity and forensics</li>
                                    <li>Leaders and program innovators in the field of IT by developing advanced cybersecurity solutions, driving digital forensics practices, and ensuring secure, ethical, and compliant digital environments</li>
                                    <li>Holistic practitioners upholding high levels of professional ethics conscious of his social and corporate responsibilities</li>
                                    <li>Demonstrate proficiency in organizational communication for effective human relations</li>
                                    <li>Apply entrepreneurial skills to create innovative cybersecurity solutions and forensic services, generating opportunities to enhance community security and support digital crime prevention and investigation</li>
                                    <li>Values the importance of continuing educational opportunities</li>
                                </ul>
                                
                                <h4>Program Outcomes:</h4>
                                <ul>
                                    <li>Apply knowledge of computing, cybersecurity principles, and digital forensics techniques to effectively address complex security challenges, investigate cybercrimes, and develop secure systems and solutions</li>
                                    <li>Understand best practices, industry standards, and the tools necessary to secure digital systems, protect data, and apply forensics techniques to effectively address cybersecurity challenges in various sectors</li>
                                    <li>Analyze complex cybersecurity and forensics problems, identifying and defining the computing requirements necessary to develop effective solutions</li>
                                    <li>Identify and analyze user needs to guide the selection, creation, evaluation, and management of secure, efficient, and effective cybersecurity and forensics systems</li>
                                    <li>Design, develop, and implement strategies to create and deliver secure cybersecurity and forensics solutions that meet organizational needs and requirements within various constraints</li>
                                    <li>Integrate IT-based cybersecurity and forensics solutions effectively into user environments to enhance security and address specific organizational needs</li>
                                    <li>Apply current techniques, skills, tools, and practices in cybersecurity and forensics to effectively address challenges in the IT profession</li>
                                    <li>Function effectively as a member or leader of a development team recognizing the different roles within a team to accomplish a common goal</li>
                                    <li>Communicate effectively with the computing community and with society at large about complex computing activities through logical writing, research, presentations, and clear instructions</li>
                                    <li>Analyze the local and global impact of cybersecurity and forensics technologies on individuals, organizations, and society, understanding ethical, legal, and societal implications</li>
                                    <li>Understand professional, ethical, legal, security and social issues and responsibilities in utilizing information technology</li>
                                    <li>Recognize the need for and engage in planning self-learning and improving performance as a foundation for continuing professional development</li>
                                </ul>
                                
                                <h4>Career Paths:</h4>
                                <ul>
                                    <li>Security Analyst, Security Engineer, Penetration Tester (Ethical Hacker)</li>
                                    <li>Security Architect, Information Security Manager (CISO), Incident Responder</li>
                                    <li>Digital Forensics Analyst, Computer Forensics Investigator</li>
                                    <li>Cybersecurity and Forensics Specialist, Network Administrator</li>
                                    <li>System Administrator, Database Administrator, Software Developer</li>
                                </ul>
                            </div>
                        </section>

                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>College:</strong> College of Computer Studies</li>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Programs:</strong> 4 Bachelor's Programs</li>
                            <li><strong>Focus:</strong> IT Excellence & Innovation</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Programs Offered</h3>
                        <ul>
                            <li><strong>BSIT:</strong> Information Technology (Game Development)</li>
                            <li><strong>BSCS:</strong> Computer Science (Data Science)</li>
                            <li><strong>BSEMC:</strong> Entertainment & Multimedia Computing</li>
                            <li><strong>BSIT-CF:</strong> Information Technology (Cybersecurity & Forensics)</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Key Features</h3>
                        <ul>
                            <li>Three-pronged vision: Teaching, Research, Service</li>
                            <li>Emerging technologies focus</li>
                            <li>Lifelong learning approach</li>
                            <li>Perpetualite character and values</li>
                            <li>Updated and specialized instruction</li>
                            <li>Individualized support programs</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Accreditation & Certifications</h3>
                        <ul>
                            <li>PACUCOA Level IV - Accredited</li>
                            <li>Center for Development</li>
                            <li>UPHSL Autonomous Status</li>
                            <li>ISO 9001 Certified</li>
                            <li>9 Industry Certifications</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Email:</strong><br>
                        <a href="mailto:ccs@uphsl.edu.ph">ccs@uphsl.edu.ph</a></p>
                        
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
