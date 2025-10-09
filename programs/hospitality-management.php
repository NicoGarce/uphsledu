<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "International Hospitality Management";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/CHIM.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>International Hospitality Management</h1>
            <p>Excellence in hospitality and tourism management</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About International Hospitality Management</h2>
                        <p>Our International Hospitality Management program prepares students for leadership roles in the global hospitality and tourism industry. We provide comprehensive training in hotel operations, food service, tourism management, and international business practices.</p>
                        
                        <h2>Undergraduate Programs</h2>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in International Hospitality Management (BS IHM)</h3>
                            <p>A four-year program that combines hospitality management with international business practices and cultural awareness.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Introduction to Hospitality Industry</li>
                                <li>Hotel Operations Management</li>
                                <li>Food and Beverage Management</li>
                                <li>Front Office Operations</li>
                                <li>Housekeeping Management</li>
                                <li>Restaurant Management</li>
                                <li>Event Management</li>
                                <li>Tourism Planning and Development</li>
                                <li>International Business</li>
                                <li>Cross-Cultural Communication</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Tourism Management (BS Tourism)</h3>
                            <p>A four-year program focused on tourism planning, development, and management.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Introduction to Tourism</li>
                                <li>Tourism Planning and Development</li>
                                <li>Tourism Marketing</li>
                                <li>Travel Agency Operations</li>
                                <li>Tour Guiding</li>
                                <li>Eco-Tourism</li>
                                <li>Cultural Tourism</li>
                                <li>MICE Management</li>
                                <li>Tourism Economics</li>
                                <li>Tourism Policy and Law</li>
                            </ul>
                        </div>
                        
                        <h2>Specializations</h2>
                        <div class="specialization-grid">
                            <div class="specialization-card">
                                <h4>Hotel Management</h4>
                                <p>Focus on hotel operations and management.</p>
                                <ul>
                                    <li>Front Office Management</li>
                                    <li>Housekeeping Operations</li>
                                    <li>Revenue Management</li>
                                    <li>Guest Relations</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Food Service Management</h4>
                                <p>Specialization in restaurant and food service operations.</p>
                                <ul>
                                    <li>Restaurant Operations</li>
                                    <li>Menu Planning</li>
                                    <li>Food Safety</li>
                                    <li>Culinary Arts</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Event Management</h4>
                                <p>Focus on planning and managing events and conferences.</p>
                                <ul>
                                    <li>Event Planning</li>
                                    <li>Conference Management</li>
                                    <li>Wedding Planning</li>
                                    <li>Exhibition Management</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Tourism Development</h4>
                                <p>Specialization in tourism planning and development.</p>
                                <ul>
                                    <li>Destination Management</li>
                                    <li>Sustainable Tourism</li>
                                    <li>Community-Based Tourism</li>
                                    <li>Heritage Tourism</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Practical Training</h2>
                        <div class="training-grid">
                            <div class="training-card">
                                <h4>Hotel Internship</h4>
                                <p>Hands-on experience in hotel operations.</p>
                                <ul>
                                    <li>Front Office Training</li>
                                    <li>Housekeeping Operations</li>
                                    <li>Food and Beverage Service</li>
                                    <li>Guest Relations</li>
                                </ul>
                            </div>
                            
                            <div class="training-card">
                                <h4>Restaurant Training</h4>
                                <p>Practical experience in food service operations.</p>
                                <ul>
                                    <li>Kitchen Operations</li>
                                    <li>Service Standards</li>
                                    <li>Menu Development</li>
                                    <li>Customer Service</li>
                                </ul>
                            </div>
                            
                            <div class="training-card">
                                <h4>Tourism Practicum</h4>
                                <p>Field experience in tourism operations.</p>
                                <ul>
                                    <li>Travel Agency Operations</li>
                                    <li>Tour Guiding</li>
                                    <li>Event Management</li>
                                    <li>Destination Marketing</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>International Programs</h2>
                        <ul>
                            <li>Study Abroad Programs</li>
                            <li>International Internships</li>
                            <li>Cultural Exchange Programs</li>
                            <li>International Certification Programs</li>
                            <li>Language Training (English, Mandarin, Japanese, Korean)</li>
                        </ul>
                        
                        <h2>Career Opportunities</h2>
                        <div class="career-grid">
                            <div class="career-category">
                                <h4>Hotel Industry</h4>
                                <ul>
                                    <li>Hotel Manager</li>
                                    <li>Front Office Manager</li>
                                    <li>Food and Beverage Manager</li>
                                    <li>Housekeeping Manager</li>
                                    <li>Revenue Manager</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Restaurant Industry</h4>
                                <ul>
                                    <li>Restaurant Manager</li>
                                    <li>Executive Chef</li>
                                    <li>Food Service Director</li>
                                    <li>Catering Manager</li>
                                    <li>Banquet Manager</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Tourism Industry</h4>
                                <ul>
                                    <li>Tourism Officer</li>
                                    <li>Travel Agency Manager</li>
                                    <li>Tour Guide</li>
                                    <li>Destination Manager</li>
                                    <li>MICE Coordinator</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Event Management</h4>
                                <ul>
                                    <li>Event Planner</li>
                                    <li>Conference Manager</li>
                                    <li>Wedding Coordinator</li>
                                    <li>Exhibition Manager</li>
                                    <li>Corporate Event Manager</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Professional Certifications</h2>
                        <ul>
                            <li>Certified Hospitality Professional (CHP)</li>
                            <li>Food Safety Certification</li>
                            <li>Tourism Professional Certification</li>
                            <li>Event Management Certification</li>
                            <li>International Language Certifications</li>
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
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Programs:</strong> 2</li>
                            <li><strong>Specializations:</strong> 4</li>
                            <li><strong>Class Size:</strong> 25-30 students</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Faculty</h3>
                        <p>Our faculty includes hospitality industry professionals, certified trainers, and international experts with extensive experience in hotels, restaurants, and tourism.</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Facilities</h3>
                        <ul>
                            <li>Training Hotel</li>
                            <li>Restaurant Laboratory</li>
                            <li>Event Management Center</li>
                            <li>Tourism Information Center</li>
                            <li>Language Laboratory</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Industry Partnerships</h3>
                        <ul>
                            <li>International Hotel Chains</li>
                            <li>Restaurant Groups</li>
                            <li>Tourism Agencies</li>
                            <li>Event Management Companies</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Department Head:</strong><br>
                        Ms. Sofia Reyes<br>
                        (02) 123-4578<br>
                        hospitality.department@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
