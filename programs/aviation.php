<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Aviation";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/AVIATION.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>Aviation</h1>
            <p>Soaring to new heights in aviation education</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About Aviation Program</h2>
                        <p>Our Aviation program provides comprehensive training for aspiring pilots and aviation professionals. We offer both theoretical knowledge and practical flight training to prepare students for careers in the aviation industry.</p>
                        
                        <h2>Undergraduate Programs</h2>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Aviation (BS Aviation)</h3>
                            <p>A four-year program that combines academic coursework with flight training to produce competent pilots and aviation professionals.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Flight Theory and Operations</li>
                                <li>Aviation Meteorology</li>
                                <li>Navigation and Air Traffic Control</li>
                                <li>Aircraft Systems and Maintenance</li>
                                <li>Aviation Safety and Security</li>
                                <li>Airport Management</li>
                                <li>Aviation Law and Regulations</li>
                                <li>Human Factors in Aviation</li>
                                <li>Flight Planning and Dispatch</li>
                                <li>Aviation Communication</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Aviation Management (BS Aviation Management)</h3>
                            <p>A four-year program focused on the business and management aspects of the aviation industry.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Airline Operations Management</li>
                                <li>Airport Planning and Development</li>
                                <li>Aviation Economics</li>
                                <li>Airline Marketing and Sales</li>
                                <li>Aviation Human Resource Management</li>
                                <li>Aviation Finance and Accounting</li>
                                <li>International Aviation Law</li>
                                <li>Aviation Quality Management</li>
                                <li>Air Cargo Operations</li>
                                <li>Aviation Environmental Management</li>
                            </ul>
                        </div>
                        
                        <h2>Flight Training Programs</h2>
                        <div class="training-grid">
                            <div class="training-card">
                                <h4>Private Pilot License (PPL)</h4>
                                <p>Basic flight training for recreational flying.</p>
                                <ul>
                                    <li>40 hours flight time</li>
                                    <li>Ground school training</li>
                                    <li>Written and practical exams</li>
                                </ul>
                            </div>
                            
                            <div class="training-card">
                                <h4>Commercial Pilot License (CPL)</h4>
                                <p>Professional pilot training for commercial operations.</p>
                                <ul>
                                    <li>200 hours flight time</li>
                                    <li>Advanced flight training</li>
                                    <li>Multi-engine rating</li>
                                </ul>
                            </div>
                            
                            <div class="training-card">
                                <h4>Airline Transport Pilot License (ATPL)</h4>
                                <p>Highest level of pilot certification for airline operations.</p>
                                <ul>
                                    <li>1500 hours flight time</li>
                                    <li>Airline-specific training</li>
                                    <li>Type rating certification</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Facilities and Equipment</h2>
                        <ul>
                            <li>Flight Simulator Laboratory</li>
                            <li>Aircraft Maintenance Hangar</li>
                            <li>Aviation Meteorology Laboratory</li>
                            <li>Air Traffic Control Simulator</li>
                            <li>Aviation Library and Resource Center</li>
                            <li>Training Aircraft Fleet</li>
                        </ul>
                        
                        <h2>Career Opportunities</h2>
                        <div class="career-grid">
                            <div class="career-category">
                                <h4>Pilot Careers</h4>
                                <ul>
                                    <li>Commercial Airline Pilot</li>
                                    <li>Corporate Pilot</li>
                                    <li>Flight Instructor</li>
                                    <li>Charter Pilot</li>
                                    <li>Cargo Pilot</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Aviation Management</h4>
                                <ul>
                                    <li>Airline Operations Manager</li>
                                    <li>Airport Manager</li>
                                    <li>Aviation Consultant</li>
                                    <li>Flight Operations Manager</li>
                                    <li>Aviation Safety Inspector</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Admission Requirements</h2>
                        <ul>
                            <li>High School Diploma or equivalent</li>
                            <li>Passed UPHSL Entrance Examination</li>
                            <li>Medical Certificate (Class 1 for pilots)</li>
                            <li>Eye Examination (20/20 vision or corrected)</li>
                            <li>Hearing Test</li>
                            <li>Psychological Assessment</li>
                            <li>English Proficiency Test</li>
                        </ul>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Programs:</strong> 2</li>
                            <li><strong>Flight Training:</strong> Available</li>
                            <li><strong>Class Size:</strong> 20-25 students</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Faculty</h3>
                        <p>Our faculty includes licensed pilots, aviation professionals, and industry experts with extensive experience in commercial and military aviation.</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Partnerships</h3>
                        <ul>
                            <li>Civil Aviation Authority</li>
                            <li>Major Airlines</li>
                            <li>Aviation Training Centers</li>
                            <li>International Aviation Schools</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Department Head:</strong><br>
                        Capt. Juan Santos<br>
                        (02) 123-4573<br>
                        aviation.department@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
