<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Senior High School";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/SHS.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>Senior High School</h1>
            <p>Preparing students for college and career success</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About Senior High School</h2>
                        <p>Our Senior High School program is designed to provide students with specialized knowledge and skills in their chosen track and strand. This two-year program serves as a bridge between basic education and higher education, preparing students for college and career readiness.</p>
                        
                        <h2>Available Tracks and Strands</h2>
                        
                        <div class="track-section">
                            <h3>Academic Track</h3>
                            <div class="strand-grid">
                                <div class="strand-card">
                                    <h4>Science, Technology, Engineering, and Mathematics (STEM)</h4>
                                    <p>For students who are inclined towards science, technology, engineering, and mathematics. Prepares students for careers in engineering, medicine, research, and technology.</p>
                                    <ul>
                                        <li>Advanced Mathematics</li>
                                        <li>Physics</li>
                                        <li>Chemistry</li>
                                        <li>Biology</li>
                                        <li>Research Methods</li>
                                    </ul>
                                </div>
                                
                                <div class="strand-card">
                                    <h4>Accountancy, Business, and Management (ABM)</h4>
                                    <p>For students who are interested in business, entrepreneurship, and management. Prepares students for careers in business, finance, and management.</p>
                                    <ul>
                                        <li>Business Mathematics</li>
                                        <li>Principles of Marketing</li>
                                        <li>Business Ethics</li>
                                        <li>Fundamentals of Accountancy</li>
                                        <li>Business Communication</li>
                                    </ul>
                                </div>
                                
                                <div class="strand-card">
                                    <h4>Humanities and Social Sciences (HUMSS)</h4>
                                    <p>For students who are interested in social sciences, humanities, and liberal arts. Prepares students for careers in education, law, social work, and public service.</p>
                                    <ul>
                                        <li>Introduction to Philosophy</li>
                                        <li>Creative Writing</li>
                                        <li>Disciplines and Ideas in Social Sciences</li>
                                        <li>Philippine Politics and Governance</li>
                                        <li>Community Engagement</li>
                                    </ul>
                                </div>
                                
                                <div class="strand-card">
                                    <h4>General Academic Strand (GAS)</h4>
                                    <p>For students who are still undecided about their career path. Provides a broad range of subjects to help students explore different fields.</p>
                                    <ul>
                                        <li>Applied Economics</li>
                                        <li>Organization and Management</li>
                                        <li>Disaster Readiness and Risk Reduction</li>
                                        <li>Creative Nonfiction</li>
                                        <li>Trends, Networks, and Critical Thinking</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="track-section">
                            <h3>Technical-Vocational-Livelihood (TVL) Track</h3>
                            <div class="strand-grid">
                                <div class="strand-card">
                                    <h4>Information and Communications Technology (ICT)</h4>
                                    <p>For students interested in computer technology, programming, and digital media.</p>
                                    <ul>
                                        <li>Computer Programming</li>
                                        <li>Web Development</li>
                                        <li>Digital Graphics</li>
                                        <li>Computer Hardware Servicing</li>
                                        <li>Database Management</li>
                                    </ul>
                                </div>
                                
                                <div class="strand-card">
                                    <h4>Home Economics (HE)</h4>
                                    <p>For students interested in hospitality, tourism, and home management.</p>
                                    <ul>
                                        <li>Food and Beverage Services</li>
                                        <li>Housekeeping</li>
                                        <li>Tourism Promotion</li>
                                        <li>Travel Services</li>
                                        <li>Event Management</li>
                                    </ul>
                                </div>
                                
                                <div class="strand-card">
                                    <h4>Industrial Arts (IA)</h4>
                                    <p>For students interested in technical and industrial skills.</p>
                                    <ul>
                                        <li>Automotive Servicing</li>
                                        <li>Electrical Installation</li>
                                        <li>Plumbing</li>
                                        <li>Welding</li>
                                        <li>Refrigeration and Air Conditioning</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <h2>Admission Requirements</h2>
                        <ul>
                            <li>Completed Junior High School education</li>
                            <li>Report Card (Form 138)</li>
                            <li>Certificate of Good Moral Character</li>
                            <li>Birth Certificate (PSA)</li>
                            <li>2x2 ID Photos</li>
                            <li>Medical Certificate</li>
                        </ul>
                        
                        <h2>Program Duration</h2>
                        <p>The Senior High School program is completed in two years (Grade 11 and Grade 12), with each year consisting of two semesters.</p>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Quick Facts</h3>
                        <ul>
                            <li><strong>Duration:</strong> 2 years</li>
                            <li><strong>Grade Levels:</strong> 11-12</li>
                            <li><strong>Tracks Available:</strong> 2</li>
                            <li><strong>Strands Available:</strong> 7</li>
                            <li><strong>Class Size:</strong> 30-35 students</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Registrar's Office:</strong><br>
                        (02) 123-4567<br>
                        registrar@uphsl.edu.ph</p>
                        
                        <p><strong>Academic Affairs:</strong><br>
                        (02) 123-4568<br>
                        academic@uphsl.edu.ph</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Related Programs</h3>
                        <ul>
                            <li><a href="junior-high-school.php">Junior High School</a></li>
                            <li><a href="grade-school.php">Grade School</a></li>
                            <li><a href="../programs.php">All Programs</a></li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
