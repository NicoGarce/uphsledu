<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Education";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/TEACHER EDUCATION.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>Education</h1>
            <p>Shaping the future through quality education</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About Education Program</h2>
                        <p>Our Education program prepares future educators to become competent, committed, and caring teachers. We provide comprehensive training in pedagogy, subject matter expertise, and educational leadership to produce quality teachers for the nation.</p>
                        
                        <h2>Undergraduate Programs</h2>
                        
                        <div class="program-section">
                            <h3>Bachelor of Elementary Education (BEED)</h3>
                            <p>A four-year program that prepares students to teach in elementary schools (Grades 1-6).</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Principles of Teaching</li>
                                <li>Child and Adolescent Development</li>
                                <li>Educational Psychology</li>
                                <li>Curriculum Development</li>
                                <li>Classroom Management</li>
                                <li>Assessment and Evaluation</li>
                                <li>Teaching Methods and Strategies</li>
                                <li>Educational Technology</li>
                                <li>Special Education</li>
                                <li>Research in Education</li>
                            </ul>
                            
                            <h4>Major Subjects:</h4>
                            <ul>
                                <li>Teaching English in Elementary Grades</li>
                                <li>Teaching Mathematics in Elementary Grades</li>
                                <li>Teaching Science in Elementary Grades</li>
                                <li>Teaching Social Studies in Elementary Grades</li>
                                <li>Teaching Filipino in Elementary Grades</li>
                                <li>Teaching Music, Arts, and PE</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Secondary Education (BSED)</h3>
                            <p>A four-year program that prepares students to teach in secondary schools (Grades 7-12).</p>
                            
                            <h4>Available Majors:</h4>
                            <div class="major-grid">
                                <div class="major-card">
                                    <h4>BSED English</h4>
                                    <p>Specialization in English language and literature teaching.</p>
                                    <ul>
                                        <li>English Literature</li>
                                        <li>Linguistics</li>
                                        <li>Creative Writing</li>
                                        <li>Language Teaching Methods</li>
                                    </ul>
                                </div>
                                
                                <div class="major-card">
                                    <h4>BSED Mathematics</h4>
                                    <p>Specialization in mathematics education.</p>
                                    <ul>
                                        <li>Advanced Mathematics</li>
                                        <li>Statistics</li>
                                        <li>Mathematical Modeling</li>
                                        <li>Math Teaching Methods</li>
                                    </ul>
                                </div>
                                
                                <div class="major-card">
                                    <h4>BSED Science</h4>
                                    <p>Specialization in science education.</p>
                                    <ul>
                                        <li>General Science</li>
                                        <li>Biology</li>
                                        <li>Chemistry</li>
                                        <li>Physics</li>
                                    </ul>
                                </div>
                                
                                <div class="major-card">
                                    <h4>BSED Social Studies</h4>
                                    <p>Specialization in social studies education.</p>
                                    <ul>
                                        <li>Philippine History</li>
                                        <li>World History</li>
                                        <li>Geography</li>
                                        <li>Political Science</li>
                                    </ul>
                                </div>
                                
                                <div class="major-card">
                                    <h4>BSED Filipino</h4>
                                    <p>Specialization in Filipino language and literature teaching.</p>
                                    <ul>
                                        <li>Filipino Literature</li>
                                        <li>Panitikan</li>
                                        <li>Wika at Gramatika</li>
                                        <li>Filipino Teaching Methods</li>
                                    </ul>
                                </div>
                                
                                <div class="major-card">
                                    <h4>BSED Values Education</h4>
                                    <p>Specialization in values and character education.</p>
                                    <ul>
                                        <li>Philosophy of Education</li>
                                        <li>Values Formation</li>
                                        <li>Character Education</li>
                                        <li>Religious Education</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <h2>Teaching Practicum</h2>
                        <div class="practicum-grid">
                            <div class="practicum-card">
                                <h4>Observation Phase</h4>
                                <p>Observe experienced teachers in actual classroom settings.</p>
                                <ul>
                                    <li>40 hours observation</li>
                                    <li>Classroom management techniques</li>
                                    <li>Teaching strategies observation</li>
                                    <li>Student assessment methods</li>
                                </ul>
                            </div>
                            
                            <div class="practicum-card">
                                <h4>Assisted Teaching</h4>
                                <p>Assist teachers with lesson planning and classroom activities.</p>
                                <ul>
                                    <li>60 hours assisted teaching</li>
                                    <li>Lesson plan preparation</li>
                                    <li>Teaching assistance</li>
                                    <li>Student evaluation</li>
                                </ul>
                            </div>
                            
                            <div class="practicum-card">
                                <h4>Independent Teaching</h4>
                                <p>Take full responsibility for classroom instruction.</p>
                                <ul>
                                    <li>120 hours independent teaching</li>
                                    <li>Full classroom management</li>
                                    <li>Complete lesson delivery</li>
                                    <li>Student assessment and grading</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Career Opportunities</h2>
                        <div class="career-grid">
                            <div class="career-category">
                                <h4>Teaching Positions</h4>
                                <ul>
                                    <li>Elementary School Teacher</li>
                                    <li>High School Teacher</li>
                                    <li>Subject Specialist Teacher</li>
                                    <li>Special Education Teacher</li>
                                    <li>Alternative Learning System Teacher</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Educational Administration</h4>
                                <ul>
                                    <li>School Principal</li>
                                    <li>Assistant Principal</li>
                                    <li>Department Head</li>
                                    <li>Curriculum Coordinator</li>
                                    <li>Academic Supervisor</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Educational Support</h4>
                                <ul>
                                    <li>Guidance Counselor</li>
                                    <li>Librarian</li>
                                    <li>Educational Researcher</li>
                                    <li>Curriculum Developer</li>
                                    <li>Educational Consultant</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Professional Certifications</h2>
                        <ul>
                            <li>Licensure Examination for Teachers (LET)</li>
                                    <li>Professional Teaching Certificate</li>
                                    <li>National Certificate (NC) for Technical Subjects</li>
                                    <li>First Aid and CPR Certification</li>
                                    <li>Child Protection Training</li>
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
                            <li>NBI Clearance</li>
                        </ul>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Programs:</strong> 2</li>
                            <li><strong>Majors Available:</strong> 6</li>
                            <li><strong>Class Size:</strong> 25-30 students</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Faculty</h3>
                        <p>Our faculty includes licensed teachers, educational administrators, and curriculum specialists with extensive teaching and administrative experience.</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Laboratories</h3>
                        <ul>
                            <li>Microteaching Laboratory</li>
                            <li>Educational Technology Lab</li>
                            <li>Science Laboratory</li>
                            <li>Computer Laboratory</li>
                            <li>Language Laboratory</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Partner Schools</h3>
                        <ul>
                            <li>Public Elementary Schools</li>
                            <li>Public High Schools</li>
                            <li>Private Schools</li>
                            <li>Special Education Centers</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Department Head:</strong><br>
                        Dr. Ana Martinez<br>
                        (02) 123-4576<br>
                        education.department@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
