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
    <section class="page-header" style="background-image: url('img/banner/JHS.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-content">
                <h1>Junior Business High School / Junior Science High School</h1>
                <p>Excellence in business and science education</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <!-- Mission Section -->
                        <section class="mission-vision-section">
                            <h2>Mission</h2>
                            <p>The Basic Education Department- Junior High School shall provide the students an integral character formation program and a responsive and challenging learning environment in preparation for Senior High School Education in the different tracks and strands of business, arts and sciences.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>The Basic Education Department- Junior High School will be a benchmark of best practices in business and science high school education.</p>
                        </section>

                        <!-- Program Educational Objectives -->
                        <section class="objectives-section">
                            <h2>Program Educational Objectives</h2>
                            <p>Guided by the University's Mission, the Perpetualite Pupils are/can:</p>
                            <ul>
                                <li>Possess appropriate knowledge, skills, and attitudes for tertiary education and for the world of work.</li>
                                <li>Practice entrepreneurship skills and scientific-technological competencies to respond to varying society situations.</li>
                                <li>Participate in extension services for personal and social relations towards active leadership and community development.</li>
                                <li>Demonstrate proficiency in oral and written communication for effective human relation.</li>
                            </ul>
                        </section>

                        <!-- Basic Education Requirements -->
                        <section class="programs-section">
                            <h2>Basic Education Requirements for Enrolment</h2>
                            <ol>
                                <li>Beat (Basic Education Admission Test) Result</li>
                                <li>Form 138 - School Report Card</li>
                                <li>Form 137 - Student's Permanent Record (Original and 2 photocopies)</li>
                                <li>PSA Birth Certificate (Original and 2 photocopies)</li>
                                <li>Certificate of Good Moral</li>
                                <li>One piece ling brown envelope with plastic</li>
                                <li>Three copies recent 2x2 picture</li>
                            </ol>
                        </section>

                        <!-- Faculty Section -->
                        <section class="faculty-section">
                            <h2>Faculty of Instruction</h2>
                            <div class="faculty-grid">
                                <div class="faculty-category">
                                    <h3>Teaching Staff</h3>
                                    <div class="faculty-list">
                                        <div class="faculty-member">
                                            <strong>Babor, Raul R.</strong> - LPT
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Barado, Ramon C.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Cabrera, Felix Gabriel O.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Cañete, Carmencita T.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Concepcion, Benuel E.</strong> - LPT
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Espeleta, Lourdes M.</strong> - LPT, MAED
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Jalop, Ma. Teresita R.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Lagunzad, Ma. Lourdes C.</strong> - LPT with MA units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Lalim, Melanie M.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Layacan, Lourdes M.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Maderazo, Maria Theresa I.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Mojica, Evangeline A.</strong> - LPT, MAED
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Pacya, Roberto Jr. S.</strong> - LPT
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Piche, Mike Z.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Resurreccion, Jan Martin C.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Sarabia, Teresita A.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Sayaman, Amelita P.</strong> - LPT, MAED, PhD
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>Program:</strong> Junior Business High School / Junior Science High School</li>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Grade Levels:</strong> 7-10</li>
                            <li><strong>Focus:</strong> Business and Science Education</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Email:</strong><br>
                        <a href="mailto:basiced@uphsl.edu.ph">basiced@uphsl.edu.ph</a></p>
                        
                        <p><strong>Phone:</strong><br>
                        (049) 554-5150 or 02-779-5310<br>
                        09156269569<br>
                        Local 3029 / 3030</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Business Hours</h3>
                        <ul>
                            <li><strong>Weekdays:</strong> 8am to 5pm</li>
                            <li><strong>Saturday:</strong> 8am to 5pm</li>
                            <li><strong>Sunday:</strong> Closed</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Our Business Office</h3>
                        <p>UPH Compound, National Highway,<br>
                        Sto. Niño, City of Biñan, Laguna</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
