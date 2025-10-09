<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Engineering & Architecture";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/ENGINEERING.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>Engineering & Architecture</h1>
            <p>Building tomorrow's infrastructure through innovation and design</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About Engineering & Architecture</h2>
                        <p>Our Engineering & Architecture programs provide students with the technical knowledge and practical skills needed to design, build, and maintain the infrastructure of tomorrow. We combine theoretical learning with hands-on experience to produce competent engineers and architects.</p>
                        
                        <h2>Undergraduate Programs</h2>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Civil Engineering (BSCE)</h3>
                            <p>A five-year program that prepares students to design and construct infrastructure projects.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Engineering Mathematics</li>
                                <li>Engineering Mechanics</li>
                                <li>Structural Analysis</li>
                                <li>Concrete Design</li>
                                <li>Steel Design</li>
                                <li>Highway Engineering</li>
                                <li>Hydraulics</li>
                                <li>Geotechnical Engineering</li>
                                <li>Construction Management</li>
                                <li>Surveying</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Electrical Engineering (BSEE)</h3>
                            <p>A five-year program focused on electrical systems, power generation, and electronics.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Circuit Analysis</li>
                                <li>Electromagnetic Fields</li>
                                <li>Power Systems</li>
                                <li>Control Systems</li>
                                <li>Electronics</li>
                                <li>Digital Systems</li>
                                <li>Electrical Machines</li>
                                <li>Power Electronics</li>
                                <li>Renewable Energy</li>
                                <li>Electrical Safety</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Mechanical Engineering (BSME)</h3>
                            <p>A five-year program covering mechanical systems, thermodynamics, and manufacturing.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Thermodynamics</li>
                                <li>Fluid Mechanics</li>
                                <li>Heat Transfer</li>
                                <li>Machine Design</li>
                                <li>Manufacturing Processes</li>
                                <li>Materials Science</li>
                                <li>Vibration Analysis</li>
                                <li>Refrigeration and Air Conditioning</li>
                                <li>Internal Combustion Engines</li>
                                <li>CAD/CAM</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Computer Engineering (BSCpE)</h3>
                            <p>A five-year program combining electrical engineering and computer science.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Digital Logic Design</li>
                                <li>Microprocessors</li>
                                <li>Computer Architecture</li>
                                <li>Embedded Systems</li>
                                <li>Network Engineering</li>
                                <li>Software Engineering</li>
                                <li>Data Communications</li>
                                <li>Computer Networks</li>
                                <li>Operating Systems</li>
                                <li>VLSI Design</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Architecture (BS Architecture)</h3>
                            <p>A five-year program that prepares students to design buildings and urban spaces.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Architectural Design</li>
                                <li>Building Technology</li>
                                <li>Architectural History</li>
                                <li>Building Materials</li>
                                <li>Structural Design</li>
                                <li>Environmental Planning</li>
                                <li>Urban Design</li>
                                <li>Architectural Graphics</li>
                                <li>Building Codes</li>
                                <li>Professional Practice</li>
                            </ul>
                        </div>
                        
                        <h2>Laboratory Facilities</h2>
                        <div class="facility-grid">
                            <div class="facility-card">
                                <h4>Civil Engineering Lab</h4>
                                <ul>
                                    <li>Materials Testing Laboratory</li>
                                    <li>Hydraulics Laboratory</li>
                                    <li>Surveying Equipment</li>
                                    <li>Concrete Testing</li>
                                </ul>
                            </div>
                            
                            <div class="facility-card">
                                <h4>Electrical Engineering Lab</h4>
                                <ul>
                                    <li>Power Systems Laboratory</li>
                                    <li>Electronics Laboratory</li>
                                    <li>Control Systems Lab</li>
                                    <li>High Voltage Laboratory</li>
                                </ul>
                            </div>
                            
                            <div class="facility-card">
                                <h4>Mechanical Engineering Lab</h4>
                                <ul>
                                    <li>Thermodynamics Laboratory</li>
                                    <li>Fluid Mechanics Lab</li>
                                    <li>Machine Shop</li>
                                    <li>CAD/CAM Laboratory</li>
                                </ul>
                            </div>
                            
                            <div class="facility-card">
                                <h4>Architecture Lab</h4>
                                <ul>
                                    <li>Design Studio</li>
                                    <li>Model Making Workshop</li>
                                    <li>Computer-Aided Design Lab</li>
                                    <li>Materials Library</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Career Opportunities</h2>
                        <div class="career-grid">
                            <div class="career-category">
                                <h4>Civil Engineering</h4>
                                <ul>
                                    <li>Structural Engineer</li>
                                    <li>Highway Engineer</li>
                                    <li>Water Resources Engineer</li>
                                    <li>Construction Manager</li>
                                    <li>Project Engineer</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Electrical Engineering</h4>
                                <ul>
                                    <li>Power Systems Engineer</li>
                                    <li>Electronics Engineer</li>
                                    <li>Control Systems Engineer</li>
                                    <li>Telecommunications Engineer</li>
                                    <li>Renewable Energy Engineer</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Mechanical Engineering</h4>
                                <ul>
                                    <li>Design Engineer</li>
                                    <li>Manufacturing Engineer</li>
                                    <li>HVAC Engineer</li>
                                    <li>Automotive Engineer</li>
                                    <li>Maintenance Engineer</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Architecture</h4>
                                <ul>
                                    <li>Architect</li>
                                    <li>Urban Planner</li>
                                    <li>Interior Designer</li>
                                    <li>Landscape Architect</li>
                                    <li>Project Manager</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Professional Certifications</h2>
                        <ul>
                            <li>Professional Civil Engineer (CE)</li>
                            <li>Professional Electrical Engineer (EE)</li>
                            <li>Professional Mechanical Engineer (ME)</li>
                            <li>Registered Master Electrician (RME)</li>
                            <li>Licensed Architect</li>
                        </ul>
                        
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
                            <li><strong>Duration:</strong> 5 years</li>
                            <li><strong>Programs:</strong> 5</li>
                            <li><strong>Laboratories:</strong> 4</li>
                            <li><strong>Class Size:</strong> 25-30 students</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Faculty</h3>
                        <p>Our faculty includes licensed engineers, registered architects, and industry professionals with extensive experience in their respective fields.</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Industry Partnerships</h3>
                        <ul>
                            <li>Construction Companies</li>
                            <li>Engineering Consultancies</li>
                            <li>Government Agencies</li>
                            <li>Architecture Firms</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Department Head:</strong><br>
                        Engr. Carlos Mendoza<br>
                        (02) 123-4577<br>
                        engineering.department@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
