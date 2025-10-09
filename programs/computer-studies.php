<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Computer Studies";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/COMPUTER STUDIES.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>Computer Studies</h1>
            <p>Building the future through technology and innovation</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About Computer Studies</h2>
                        <p>Our Computer Studies program is designed to provide students with comprehensive knowledge and skills in information technology, computer science, and software engineering. We prepare students for careers in the rapidly evolving technology industry.</p>
                        
                        <h2>Undergraduate Programs</h2>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Computer Science (BSCS)</h3>
                            <p>A four-year program that focuses on the theoretical foundations of computing and software development. Students learn programming languages, algorithms, data structures, and software engineering principles.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Programming Fundamentals (C++, Java, Python)</li>
                                <li>Data Structures and Algorithms</li>
                                <li>Database Management Systems</li>
                                <li>Software Engineering</li>
                                <li>Computer Networks</li>
                                <li>Operating Systems</li>
                                <li>Web Development</li>
                                <li>Mobile Application Development</li>
                                <li>Artificial Intelligence</li>
                                <li>Machine Learning</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Information Technology (BSIT)</h3>
                            <p>A four-year program that focuses on the practical application of technology in business and organizational settings. Students learn to design, implement, and manage information systems.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Information Systems Analysis and Design</li>
                                <li>Database Design and Management</li>
                                <li>Network Administration</li>
                                <li>Web Technologies</li>
                                <li>System Administration</li>
                                <li>Cybersecurity</li>
                                <li>Project Management</li>
                                <li>Business Intelligence</li>
                                <li>Cloud Computing</li>
                                <li>IT Service Management</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Information Systems (BSIS)</h3>
                            <p>A four-year program that combines business knowledge with technical skills. Students learn to bridge the gap between business needs and technology solutions.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Business Process Analysis</li>
                                <li>Systems Analysis and Design</li>
                                <li>Enterprise Resource Planning</li>
                                <li>E-Commerce and E-Business</li>
                                <li>Data Analytics</li>
                                <li>IT Governance</li>
                                <li>Digital Marketing</li>
                                <li>Supply Chain Management</li>
                                <li>Customer Relationship Management</li>
                                <li>IT Risk Management</li>
                            </ul>
                        </div>
                        
                        <h2>Specializations</h2>
                        <div class="specialization-grid">
                            <div class="specialization-card">
                                <h4>Software Development</h4>
                                <p>Focus on programming, software design, and application development using modern technologies and frameworks.</p>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Web Development</h4>
                                <p>Specialize in front-end and back-end web development, including responsive design and web applications.</p>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Mobile Development</h4>
                                <p>Learn to develop mobile applications for iOS and Android platforms using native and cross-platform technologies.</p>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Data Science</h4>
                                <p>Focus on data analysis, machine learning, and artificial intelligence applications.</p>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Cybersecurity</h4>
                                <p>Specialize in information security, network security, and digital forensics.</p>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Network Administration</h4>
                                <p>Learn to design, implement, and manage computer networks and network security.</p>
                            </div>
                        </div>
                        
                        <h2>Career Opportunities</h2>
                        <div class="career-grid">
                            <div class="career-category">
                                <h4>Software Development</h4>
                                <ul>
                                    <li>Software Engineer</li>
                                    <li>Application Developer</li>
                                    <li>Full-Stack Developer</li>
                                    <li>Mobile App Developer</li>
                                    <li>Game Developer</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>IT Management</h4>
                                <ul>
                                    <li>IT Manager</li>
                                    <li>Systems Administrator</li>
                                    <li>Database Administrator</li>
                                    <li>Network Administrator</li>
                                    <li>IT Consultant</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Data & Analytics</h4>
                                <ul>
                                    <li>Data Scientist</li>
                                    <li>Data Analyst</li>
                                    <li>Business Intelligence Analyst</li>
                                    <li>Machine Learning Engineer</li>
                                    <li>Data Engineer</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Cybersecurity</h4>
                                <ul>
                                    <li>Cybersecurity Analyst</li>
                                    <li>Information Security Officer</li>
                                    <li>Penetration Tester</li>
                                    <li>Security Consultant</li>
                                    <li>Digital Forensics Specialist</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Admission Requirements</h2>
                        <ul>
                            <li>High School Diploma or equivalent</li>
                            <li>Passed UPHSL Entrance Examination</li>
                            <li>Report Card (Form 138)</li>
                            <li>Certificate of Good Moral Character</li>
                            <li>Birth Certificate (PSA)</li>
                            <li>2x2 ID Photos</li>
                            <li>Medical Certificate</li>
                        </ul>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Units:</strong> 160-180 units</li>
                            <li><strong>Programs:</strong> 3</li>
                            <li><strong>Specializations:</strong> 6</li>
                            <li><strong>Class Size:</strong> 25-30 students</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Faculty</h3>
                        <p>Our faculty members are industry professionals with extensive experience in software development, IT management, and academic research.</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Laboratories</h3>
                        <ul>
                            <li>Computer Programming Lab</li>
                            <li>Network Administration Lab</li>
                            <li>Database Management Lab</li>
                            <li>Web Development Lab</li>
                            <li>Cybersecurity Lab</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Department Head:</strong><br>
                        Dr. Maria Santos<br>
                        (02) 123-4569<br>
                        cs.department@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
