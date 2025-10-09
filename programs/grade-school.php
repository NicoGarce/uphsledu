<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Grade School";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/BASIC EDUCATION.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>Grade School</h1>
            <p>Nurturing young minds for a bright future</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About Grade School</h2>
                        <p>Our Grade School program provides a strong foundation for young learners from Kindergarten to Grade 6. We focus on developing essential academic skills, character formation, and social development in a nurturing environment.</p>
                        
                        <h2>Grade Levels</h2>
                        <div class="grade-levels">
                            <div class="grade-card">
                                <h3>Kindergarten</h3>
                                <p>Introduction to school life, basic literacy, and social skills development.</p>
                            </div>
                            <div class="grade-card">
                                <h3>Grades 1-3 (Primary)</h3>
                                <p>Foundation building in reading, writing, mathematics, and character formation.</p>
                            </div>
                            <div class="grade-card">
                                <h3>Grades 4-6 (Intermediate)</h3>
                                <p>Advanced academic skills and preparation for high school.</p>
                            </div>
                        </div>
                        
                        <h2>Core Subjects</h2>
                        <ul>
                            <li>Language Arts (English & Filipino)</li>
                            <li>Mathematics</li>
                            <li>Science</li>
                            <li>Social Studies</li>
                            <li>Values Education</li>
                            <li>Physical Education</li>
                            <li>Music and Arts</li>
                            <li>Computer Education</li>
                        </ul>
                        
                        <h2>Special Programs</h2>
                        <div class="program-grid">
                            <div class="program-card">
                                <h4>Reading Program</h4>
                                <p>Comprehensive literacy development for all grade levels.</p>
                            </div>
                            <div class="program-card">
                                <h4>Math Enhancement</h4>
                                <p>Specialized mathematics instruction and problem-solving skills.</p>
                            </div>
                            <div class="program-card">
                                <h4>Character Formation</h4>
                                <p>Values education and moral development programs.</p>
                            </div>
                            <div class="program-card">
                                <h4>Arts and Crafts</h4>
                                <p>Creative expression and artistic skill development.</p>
                            </div>
                        </div>
                        
                        <h2>Admission Requirements</h2>
                        <ul>
                            <li>Birth Certificate (PSA)</li>
                            <li>Medical Certificate</li>
                            <li>2x2 ID Photos</li>
                            <li>Previous School Records (for transferees)</li>
                            <li>Parent/Guardian ID</li>
                        </ul>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>Duration:</strong> 7 years (K-6)</li>
                            <li><strong>Age Range:</strong> 4-12 years</li>
                            <li><strong>Class Size:</strong> 25-30 students</li>
                            <li><strong>Student-Teacher Ratio:</strong> 1:25</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Principal:</strong><br>
                        Mrs. Ana Dela Cruz<br>
                        (02) 123-4572<br>
                        gs.principal@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
