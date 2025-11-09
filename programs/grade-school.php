<?php
/**
 * UPHSL Grade School Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the Grade School program at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Grade School";

// Set base path for assets
$base_path = '../';

// Set background image path
$bg_image = 'img/banner/BASIC EDUCATION.jpg';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('<?php echo $bg_image; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-content">
                <h1>Early Education & Grade School</h1>
                <p>Developing the whole child as a lifelong learner</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <!-- News Carousel Section -->
                    <?php
                    $category = getCategoryByName('Grade School');
                    $categoryId = $category ? $category['id'] : null;
                    $sectionTitle = 'Grade School News & Updates';
                    $sectionDescription = 'Stay updated with the latest news and announcements from the Grade School.';
                    include '../app/includes/news-carousel.php';
                    ?>
                    
                    <article class="content-article">
                        <!-- Mission Section -->
                        <section class="mission-vision-section">
                            <h2>Mission</h2>
                            <p>The Basic Education Department- Early Education and Grade School shall develop the whole child as a lifelong learner. It shall provide an encouraging and caring environment that will enhance the child's unique talents and potentials.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>The Basic Education Department- Grade School will be a benchmark of best practices in early and grade school education.</p>
                        </section>

                        <!-- Program Educational Objectives -->
                        <section class="objectives-section">
                            <h2>Program Educational Objectives</h2>
                            <p>Guided by the University's Mission, the Perpetualite Pupils are/can:</p>
                            <ul>
                                <li>Apply basic skills in the core learning areas.</li>
                                <li>Demonstrate good citizenship qualities through community service and outreach activities.</li>
                                <li>Performs skills to enable him to respond to real life situation.</li>
                                <li>Actuate integral character formation in his personal life and interpersonal dealings.</li>
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
                                            <strong>Abanto, Ailyn F.</strong> - LPT
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Arcellana, Maria Ana P.</strong> - LPT
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Cerico, Agnes S.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Danila, Anna Liza M.</strong> - LPT with Early Education Specialization
                                        </div>
                                        <div class="faculty-member">
                                            <strong>De Jesus, Leah Marie A.</strong> - LPT
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Del Rosario, Cristy G.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Dela Peña, Joan P.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Honduna, Ma. Fe P.</strong> - LPT with Early Education Specialization
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Noche, John Kenneth G.</strong> - LPT
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Olino, Sherry Miann O.</strong> - LPT
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Peña, Susan M.</strong> - LPT with MA Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Rosado, Jovel T.</strong> - LPT, MAED with Doctoral Units
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Sumeguin, Eszel Joe R.</strong> - LPT
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
                            <li><strong>Program:</strong> Early Education & Grade School</li>
                            <li><strong>Duration:</strong> 7 years (K-6)</li>
                            <li><strong>Focus:</strong> Whole child development</li>
                            <li><strong>Environment:</strong> Encouraging and caring</li>
                        </ul>
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
include '../app/includes/footer.php';
?>



