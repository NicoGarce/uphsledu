<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Arts & Sciences";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/CAS.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>Arts & Sciences</h1>
            <p>Exploring the depths of human knowledge and creativity</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About Arts & Sciences</h2>
                        <p>Our Arts & Sciences program provides a comprehensive liberal arts education that fosters critical thinking, creativity, and intellectual curiosity. Students explore diverse fields of knowledge while developing essential skills for lifelong learning and career success.</p>
                        
                        <h2>Undergraduate Programs</h2>
                        
                        <div class="program-section">
                            <h3>Bachelor of Arts in Psychology (AB Psychology)</h3>
                            <p>A four-year program that studies human behavior, mental processes, and psychological principles.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>General Psychology</li>
                                <li>Developmental Psychology</li>
                                <li>Social Psychology</li>
                                <li>Cognitive Psychology</li>
                                <li>Abnormal Psychology</li>
                                <li>Psychological Testing</li>
                                <li>Research Methods in Psychology</li>
                                <li>Counseling Psychology</li>
                                <li>Industrial Psychology</li>
                                <li>Statistics for Psychology</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Arts in English (AB English)</h3>
                            <p>A four-year program focused on English language, literature, and communication skills.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>English Literature</li>
                                <li>Creative Writing</li>
                                <li>Technical Writing</li>
                                <li>Linguistics</li>
                                <li>Literary Criticism</li>
                                <li>World Literature</li>
                                <li>Communication Arts</li>
                                <li>Research Writing</li>
                                <li>Public Speaking</li>
                                <li>Media Studies</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Biology (BS Biology)</h3>
                            <p>A four-year program that studies living organisms and their interactions with the environment.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>General Biology</li>
                                <li>Cell Biology</li>
                                <li>Genetics</li>
                                <li>Ecology</li>
                                <li>Microbiology</li>
                                <li>Botany</li>
                                <li>Zoology</li>
                                <li>Biochemistry</li>
                                <li>Molecular Biology</li>
                                <li>Research Methods in Biology</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Mathematics (BS Mathematics)</h3>
                            <p>A four-year program that develops mathematical reasoning and problem-solving skills.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Calculus</li>
                                <li>Linear Algebra</li>
                                <li>Statistics</li>
                                <li>Discrete Mathematics</li>
                                <li>Number Theory</li>
                                <li>Differential Equations</li>
                                <li>Mathematical Modeling</li>
                                <li>Abstract Algebra</li>
                                <li>Real Analysis</li>
                                <li>Mathematical Logic</li>
                            </ul>
                        </div>
                        
                        <h2>Specializations</h2>
                        <div class="specialization-grid">
                            <div class="specialization-card">
                                <h4>Pre-Med Track</h4>
                                <p>Preparation for medical school with focus on biology and chemistry.</p>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Pre-Law Track</h4>
                                <p>Preparation for law school with focus on political science and philosophy.</p>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Research Track</h4>
                                <p>Focus on research methodology and academic preparation.</p>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Teaching Track</h4>
                                <p>Preparation for teaching careers in secondary education.</p>
                            </div>
                        </div>
                        
                        <h2>Career Opportunities</h2>
                        <div class="career-grid">
                            <div class="career-category">
                                <h4>Psychology</h4>
                                <ul>
                                    <li>Clinical Psychologist</li>
                                    <li>Counselor</li>
                                    <li>Human Resource Specialist</li>
                                    <li>Research Analyst</li>
                                    <li>Social Worker</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>English</h4>
                                <ul>
                                    <li>Writer/Editor</li>
                                    <li>Journalist</li>
                                    <li>Content Creator</li>
                                    <li>Public Relations Specialist</li>
                                    <li>Teacher/Professor</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Biology</h4>
                                <ul>
                                    <li>Research Scientist</li>
                                    <li>Laboratory Technician</li>
                                    <li>Environmental Consultant</li>
                                    <li>Biomedical Researcher</li>
                                    <li>Wildlife Biologist</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Mathematics</h4>
                                <ul>
                                    <li>Data Analyst</li>
                                    <li>Actuary</li>
                                    <li>Statistician</li>
                                    <li>Financial Analyst</li>
                                    <li>Operations Research Analyst</li>
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
                            <li><strong>Programs:</strong> 4</li>
                            <li><strong>Specializations:</strong> 4</li>
                            <li><strong>Class Size:</strong> 25-30 students</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Faculty</h3>
                        <p>Our faculty includes published researchers, experienced practitioners, and industry professionals with advanced degrees in their respective fields.</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Laboratories</h3>
                        <ul>
                            <li>Psychology Laboratory</li>
                            <li>Biology Laboratory</li>
                            <li>Chemistry Laboratory</li>
                            <li>Computer Laboratory</li>
                            <li>Language Laboratory</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Department Head:</strong><br>
                        Dr. Maria Rodriguez<br>
                        (02) 123-4574<br>
                        arts.department@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
