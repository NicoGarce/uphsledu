<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Junior High School";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/BASIC EDUCATION.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>Junior High School</h1>
            <p>Building strong foundations for academic success</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About Junior High School</h2>
                        <p>Our Junior High School program provides students with a comprehensive secondary education that builds upon their elementary foundation. This four-year program (Grades 7-10) prepares students for senior high school and beyond.</p>
                        
                        <h2>Grade Levels</h2>
                        <div class="grade-levels">
                            <div class="grade-card">
                                <h3>Grade 7</h3>
                                <p>Transition year from elementary to high school, focusing on adaptation and foundational skills.</p>
                            </div>
                            <div class="grade-card">
                                <h3>Grade 8</h3>
                                <p>Building academic skills and introducing more specialized subjects.</p>
                            </div>
                            <div class="grade-card">
                                <h3>Grade 9</h3>
                                <p>Advanced coursework and preparation for senior high school tracks.</p>
                            </div>
                            <div class="grade-card">
                                <h3>Grade 10</h3>
                                <p>Final year preparation for senior high school and career planning.</p>
                            </div>
                        </div>
                        
                        <h2>Core Subjects</h2>
                        <ul>
                            <li>English Language Arts</li>
                            <li>Mathematics</li>
                            <li>Science</li>
                            <li>Social Studies</li>
                            <li>Filipino</li>
                            <li>Values Education</li>
                            <li>Physical Education</li>
                            <li>Music and Arts</li>
                            <li>Technology and Livelihood Education (TLE)</li>
                        </ul>
                        
                        <h2>Special Programs</h2>
                        <div class="program-grid">
                            <div class="program-card">
                                <h4>Honors Program</h4>
                                <p>Advanced coursework for academically gifted students.</p>
                            </div>
                            <div class="program-card">
                                <h4>Remedial Classes</h4>
                                <p>Additional support for students who need extra help.</p>
                            </div>
                            <div class="program-card">
                                <h4>Extracurricular Activities</h4>
                                <p>Sports, clubs, and organizations for holistic development.</p>
                            </div>
                        </div>
                        
                        <h2>Admission Requirements</h2>
                        <ul>
                            <li>Completed Elementary Education</li>
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
                            <li><strong>Grade Levels:</strong> 7-10</li>
                            <li><strong>Class Size:</strong> 35-40 students</li>
                            <li><strong>Age Range:</strong> 12-16 years</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Principal:</strong><br>
                        Mrs. Maria Santos<br>
                        (02) 123-4571<br>
                        jhs.principal@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
