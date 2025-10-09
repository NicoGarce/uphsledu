<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Maritime";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/MARITIME.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>Maritime</h1>
            <p>Navigating the world's oceans with excellence and safety</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About Maritime Program</h2>
                        <p>Our Maritime program provides comprehensive education and training for careers in the maritime industry. We prepare students to become competent seafarers, maritime engineers, and maritime professionals who can work on ships and in maritime-related industries worldwide.</p>
                        
                        <h2>Undergraduate Programs</h2>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Marine Transportation (BSMT)</h3>
                            <p>A four-year program that prepares students for careers as ship officers and maritime professionals.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Navigation</li>
                                <li>Ship Handling</li>
                                <li>Maritime Law</li>
                                <li>Meteorology</li>
                                <li>Ship Construction</li>
                                <li>Cargo Operations</li>
                                <li>Marine Communication</li>
                                <li>Ship Management</li>
                                <li>Marine Safety</li>
                                <li>Port Operations</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Marine Engineering (BSMarE)</h3>
                            <p>A four-year program that prepares students for careers as marine engineers and ship engineers.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Marine Engineering</li>
                                <li>Ship Machinery</li>
                                <li>Marine Electrical Systems</li>
                                <li>Marine Electronics</li>
                                <li>Marine Refrigeration</li>
                                <li>Marine Auxiliary Systems</li>
                                <li>Marine Safety Systems</li>
                                <li>Ship Maintenance</li>
                                <li>Marine Pollution Control</li>
                                <li>Marine Power Plants</li>
                            </ul>
                        </div>
                        
                        <h2>Specializations</h2>
                        <div class="specialization-grid">
                            <div class="specialization-card">
                                <h4>Deck Officer</h4>
                                <p>Specialization in ship navigation and deck operations.</p>
                                <ul>
                                    <li>Celestial Navigation</li>
                                    <li>Electronic Navigation</li>
                                    <li>Ship Handling</li>
                                    <li>Cargo Operations</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Engine Officer</h4>
                                <p>Specialization in ship machinery and engine operations.</p>
                                <ul>
                                    <li>Diesel Engines</li>
                                    <li>Steam Turbines</li>
                                    <li>Electrical Systems</li>
                                    <li>Control Systems</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Port Management</h4>
                                <p>Focus on port operations and maritime logistics.</p>
                                <ul>
                                    <li>Port Operations</li>
                                    <li>Maritime Logistics</li>
                                    <li>Freight Forwarding</li>
                                    <li>Customs Procedures</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Maritime Safety</h4>
                                <p>Specialization in maritime safety and security.</p>
                                <ul>
                                    <li>Maritime Safety</li>
                                    <li>Search and Rescue</li>
                                    <li>Maritime Security</li>
                                    <li>Emergency Response</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Practical Training</h2>
                        <div class="training-grid">
                            <div class="training-card">
                                <h4>Shipboard Training</h4>
                                <p>Onboard training on actual ships.</p>
                                <ul>
                                    <li>12 months shipboard training</li>
                                    <li>Deck operations</li>
                                    <li>Engine room operations</li>
                                    <li>Safety procedures</li>
                                </ul>
                            </div>
                            
                            <div class="training-card">
                                <h4>Simulator Training</h4>
                                <p>Advanced simulation training for various scenarios.</p>
                                <ul>
                                    <li>Bridge Simulator</li>
                                    <li>Engine Room Simulator</li>
                                    <li>GMDSS Simulator</li>
                                    <li>ECDIS Training</li>
                                </ul>
                            </div>
                            
                            <div class="training-card">
                                <h4>Laboratory Training</h4>
                                <p>Hands-on training in maritime laboratories.</p>
                                <ul>
                                    <li>Navigation Laboratory</li>
                                    <li>Marine Engineering Lab</li>
                                    <li>Electronics Laboratory</li>
                                    <li>Safety Training Center</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>International Certifications</h2>
                        <ul>
                            <li>Standards of Training, Certification and Watchkeeping (STCW)</li>
                            <li>Global Maritime Distress and Safety System (GMDSS)</li>
                            <li>Electronic Chart Display and Information System (ECDIS)</li>
                            <li>Basic Safety Training (BST)</li>
                            <li>Advanced Fire Fighting</li>
                            <li>Medical First Aid</li>
                        </ul>
                        
                        <h2>Career Opportunities</h2>
                        <div class="career-grid">
                            <div class="career-category">
                                <h4>Deck Department</h4>
                                <ul>
                                    <li>Master Mariner (Captain)</li>
                                    <li>Chief Officer</li>
                                    <li>Second Officer</li>
                                    <li>Third Officer</li>
                                    <li>Deck Cadet</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Engine Department</h4>
                                <ul>
                                    <li>Chief Engineer</li>
                                    <li>Second Engineer</li>
                                    <li>Third Engineer</li>
                                    <li>Fourth Engineer</li>
                                    <li>Engine Cadet</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Shore-Based Careers</h4>
                                <ul>
                                    <li>Maritime Surveyor</li>
                                    <li>Port Manager</li>
                                    <li>Maritime Consultant</li>
                                    <li>Marine Insurance</li>
                                    <li>Maritime Education</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Government Positions</h4>
                                <ul>
                                    <li>Maritime Administrator</li>
                                    <li>Port Authority Officer</li>
                                    <li>Coast Guard Officer</li>
                                    <li>Customs Officer</li>
                                    <li>Maritime Safety Inspector</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Admission Requirements</h2>
                        <ul>
                            <li>High School Diploma or equivalent</li>
                            <li>Passed UPHSL Entrance Examination</li>
                            <li>Medical Certificate (Class 1)</li>
                            <li>Eye Examination (20/20 vision or corrected)</li>
                            <li>Hearing Test</li>
                            <li>Psychological Assessment</li>
                            <li>Swimming Test</li>
                            <li>NBI Clearance</li>
                            <li>Police Clearance</li>
                        </ul>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Programs:</strong> 2</li>
                            <li><strong>Shipboard Training:</strong> 12 months</li>
                            <li><strong>Class Size:</strong> 20-25 students</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Faculty</h3>
                        <p>Our faculty includes licensed mariners, marine engineers, and maritime professionals with extensive sea experience and international certifications.</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Facilities</h3>
                        <ul>
                            <li>Bridge Simulator</li>
                            <li>Engine Room Simulator</li>
                            <li>Navigation Laboratory</li>
                            <li>Marine Engineering Lab</li>
                            <li>Safety Training Center</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Industry Partnerships</h3>
                        <ul>
                            <li>International Shipping Companies</li>
                            <li>Maritime Training Centers</li>
                            <li>Port Authorities</li>
                            <li>Maritime Agencies</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Department Head:</strong><br>
                        Capt. Roberto Cruz<br>
                        (02) 123-4579<br>
                        maritime.department@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
