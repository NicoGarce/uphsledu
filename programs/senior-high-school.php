<?php
/**
 * UPHSL Senior High School Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the Senior High School program at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Senior High School";

// Set base path for assets
$base_path = '../';

// Set background image path
$bg_image = 'img/banner/SHS.jpg';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('<?php echo $bg_image; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="<?php echo $base_path; ?>programs/img/logo/uphsl-shs-logo.png" alt="Senior High School Logo">
            </div>
            <div class="banner-content">
                <h1>Senior High School</h1>
                <p>Preparing students for college and career success</p>
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
                    $category = getCategoryByName('Senior High School');
                    $categoryId = $category ? $category['id'] : null;
                    $sectionTitle = 'Senior High School News & Updates';
                    $sectionDescription = 'Stay updated with the latest news and announcements from the Senior High School.';
                    include '../app/includes/news-carousel.php';
                    ?>
                    
                    <article class="content-article">
                        <!-- Mission and Vision -->
                        <div class="mission-vision-section">
                            <h2>Mission Statement</h2>
                            <p>The UPHSL Senior High School aims to produce Christian leaders research-oriented and competent graduates who have mastered the necessary concepts needed for tertiary education and have developed the skills for employment and entrepreneurship.</p>
                            
                            <h2>Vision Statement</h2>
                            <p>The UPHSL Senior High School will be a benchmark of excellence in delivering quality education and in inculcating Christian values leading to its graduates towards the attainment of the best quality of life.</p>
                        </div>

                        <!-- Programs Offered -->
                        <div class="programs-section">
                            <h2>Programs Offered</h2>
                            <div class="programs-grid">
                                <div class="program-category">
                                    <h3>Academic Track</h3>
                                    <ul>
                                        <li>Science, Technology, Engineering and Mathematics</li>
                                        <li>Accountancy and Business Management</li>
                                        <li>Humanities and Social Sciences</li>
                                        <li>General Academic Strand</li>
                                        <li>Pre-Baccalaureate Maritime</li>
                                    </ul>
                                </div>
                                
                                <div class="program-category">
                                    <h3>Technical-Vocational Livelihood</h3>
                                    <ul>
                                        <li>Home Economics</li>
                                        <li>Information and Communications Technology</li>
                                    </ul>
                                </div>
                                
                                <div class="program-category">
                                    <h3>Specialized Tracks</h3>
                                    <ul>
                                        <li>Arts and Design – Performing Arts</li>
                                        <li>Sports Track</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Career Opportunities -->
                        <div class="career-opportunities-section">
                            <h2>Career Opportunities</h2>
                            <div class="career-grid">
                                <div class="career-category">
                                    <h3>Science, Technology, Engineering and Mathematics</h3>
                                    <p>You can take this as your springboard to become a pilot, an architect, engineer, biologist, chemist, physicist, dentist, nutritionist, nurse, doctor and many more.</p>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Accountancy and Business Management</h3>
                                    <p>Can lead you to career on financial management, business management, corporate operations, management and accounting, sales manager, human resource, marketing director, project officer, bookkeeper, accounting clerk, internal auditor, hotel manager and a lot more.</p>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Humanities and Social Sciences</h3>
                                    <p>This is for those to pursue a career on journalism, communication arts, liberal arts, law, education, psychology and other social science related course.</p>
                                </div>
                                
                                <div class="career-category">
                                    <h3>General Academic Strand</h3>
                                    <p>It is great for students who are still thinking on which career to pursue.</p>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Pre Baccalaureate Maritime</h3>
                                    <p>It is great for students who wants to pursue course in maritime education.</p>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Technical-Vocational Livelihood (Home Economics)</h3>
                                    <p>These job-ready skills will prepare you to become baker, barista, tour guide, front office staff, and others. It will also prepare in hospitality and tourism management, culinary arts, food technology nutrition and flight attendant.</p>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Technical-Vocational Livelihood (ICT)</h3>
                                    <p>These job-ready skills will prepare you to become call center agent, web designer, data encoder, graphic designers, animator and others. This can also prepare you on different IT courses such as computer programming and engineer.</p>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Arts and Design (Performing Arts)</h3>
                                    <p>Can lead to career on architecture, interior design, industrial design, graphic design, painting, photography, desktop publishing and filmmaking.</p>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Sports</h3>
                                    <p>Can prepare you in becoming a fitness trainer, game officials, tournament manager, recreation attendant or gym instructor.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Program Educational Objectives -->
                        <div class="objectives-section">
                            <h2>Program Educational Objectives</h2>
                            <ul>
                                <li>Possess appropriate knowledge, skills, and attitudes for tertiary education and for the world of work.</li>
                                <li>Practice entrepreneurship skills and scientific-technological competencies to respond to varying society situations.</li>
                                <li>Contribute to the ideals of nation building and promote their unique history and heritage as Filipinos</li>
                                <li>Manifest Christian leadership skills through community service and outreach activities.</li>
                                <li>Demonstrate integral character and moral values in their personal lives and international dealings.</li>
                            </ul>
                        </div>

                        <!-- Official SHS Department Logo -->
                        <div class="logo-section">
                            <h2>Official SHS Department Logo</h2>
                            <div class="logo-description">
                                <div class="logo-image-container">
                                    <img src="<?php echo $base_path; ?>programs/img/logo/uphsl-shs-logo.png" alt="Official SHS Department Logo" class="department-logo">
                                </div>
                                <p><strong>"Soar High, Senior High."</strong> The logo signifies the true spirit and identity of the department and its multiple elements that symbolize the characteristics of Senior High School living as Perpetualites. The logo was created by Ms. Ma. Mimar F. Arceo, Ms. Joselle Anne G. Barredo, Mr. Cyrus Jade Barilea, and Ms. Keanna Aissen L. Belmonte last 2019.</p>
                                
                                <h3>Symbolism</h3>
                                <p>The circular or round shape which represents the totality and wholeness of all the other elements included, as well as the strong foundation of unity among all members of the Senior High School. Right at the left side of the circle are four rising flames, which include the 4 tracks that are included in the K-12 Curriculum: Academic, Tech-Voc, Sports, and Arts & Design. The flames represent wisdom and knowledge; the flames are rising to symbolize soaring, as Senior High School influences and lifts our capabilities, taking us to greater heights.</p>
                                
                                <p>These flames are overlapping because although the tracks are of their own strength and significance, they are still recognized as one and not individually. These flames also be interpreted as leaves or wreath, symbolizing unity and spirituality.</p>
                                
                                <p>The roundness shape symbolizes every success and failure we have God in us because like what Hermes Trismegistus said, "God is a circle whose center is everywhere and the circumference in nowhere." At the center, there are two figures which explains two things: first is the man that symbolizes every senior high school student with its arm extending, reaching something and that something is the dream of every student wants to reach and achieve. It also signifies the role of every Perpetualite to reach out and help others as a vital role in nation building. Next is the second main part which is the wing, this wing represents the freedom of every student to choose their own path, freedom to fly high meaning to do our very best to reach that dream that we've wanted. Behind the figures is the image of our school. The 12 leaves in both sides symbolizes the 12 children of the founder.</p>
                                
                                <h3>Color Symbolism</h3>
                                <p>The colors used in the logo are the following: red (energy, strength, power, determination, passion), orange (energy of red and happiness of yellow; joy, youth, creativity), yellow (optimism, happiness, intellect), aqua blue (depth, stability, wisdom, confidence), white (formality, success), maroon (ambition, courage, risk), gray (dignity, maturity, reliability, compromise), brown/tan (connection to the earth, warmth, wholesome) green (growth, harmony).</p>
                            </div>
                        </div>

                        <!-- SHS Faculty -->
                        <div class="faculty-section">
                            <h2>SHS Faculty</h2>
                            <div class="faculty-grid">
                                <div class="faculty-category">
                                    <h3>Administration</h3>
                                    <div class="faculty-list">
                                        <div class="faculty-member">
                                            <strong>Veronica C. Samson, MAEd</strong><br>
                                            Senior High School Director
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Michael Angelo B. Del Rosario, LPT</strong><br>
                                            Senior High School Academic Coordinator
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="faculty-category">
                                    <h3>Department Chairpersons</h3>
                                    <div class="faculty-list">
                                        <div class="faculty-member">
                                            <strong>Celestina C. Almenanza, MAEd</strong><br>
                                            English Chairman
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Rowena R. Contillo, PhD</strong><br>
                                            Research Chairman
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Victorio B. Duyan, PhD</strong><br>
                                            Science Chairman
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Jeanette Ana M. Orocay, LPT</strong><br>
                                            Social Science Chairman
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Engr. Randy V. Ogaya</strong><br>
                                            Mathematics Chairman
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Jesus M. Purificacion, MAEd</strong><br>
                                            Filipino Chairman
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Jeanne Pauline M. Sarmiento, LPT</strong><br>
                                            TVL Chairman
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Marilou C. Urbina, DBM</strong><br>
                                            ABM Chairman
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Wilbert Levi H. Eugenio, MAEd</strong><br>
                                            FIAT Advisor
                                        </div>
                                        <div class="faculty-member">
                                            <strong>Clarisse Anne G. Lebios, LPT</strong><br>
                                            Senior Student Council Advisor
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="faculty-category">
                                    <h3>Faculty Members</h3>
                                    <div class="faculty-list">
                                        <div class="faculty-member">Kristian Dale P. Almazora, LPT</div>
                                        <div class="faculty-member">Sheila R. Ambrocio, LPT</div>
                                        <div class="faculty-member">Anna Margarita N. Ayacocho, LPT</div>
                                        <div class="faculty-member">John Alfred C. Ayson, LPT</div>
                                        <div class="faculty-member">Melissa Joy A. Baron, LPT</div>
                                        <div class="faculty-member">Garry C. Bayran, LPT</div>
                                        <div class="faculty-member">Jolly Ray F. Bederico, LPT</div>
                                        <div class="faculty-member">Chriselle Anne V. Bermillas, LPT</div>
                                        <div class="faculty-member">Ervil B. Borromeo, MBA</div>
                                        <div class="faculty-member">Benjamin A. Budino Jr., LPT</div>
                                        <div class="faculty-member">Christine Joyce V. Buhay, LPT</div>
                                        <div class="faculty-member">Karla Joy E. Candela, LPT</div>
                                        <div class="faculty-member">Decerie G. Caparas, LPT</div>
                                        <div class="faculty-member">Cherry Karen M. Catador, LPT</div>
                                        <div class="faculty-member">Abegail S. Dacles, LPT</div>
                                        <div class="faculty-member">Jeffrey C. Deriquito, LPT</div>
                                        <div class="faculty-member">Marilen M. Dime, LPT</div>
                                        <div class="faculty-member">Michelle D. Gui, LPT</div>
                                        <div class="faculty-member">Engr. Eric L. Hernandez, MSEE</div>
                                        <div class="faculty-member">Donita A. Jorlano, LPT</div>
                                        <div class="faculty-member">Bryan Neil B. Ladim, LPT</div>
                                        <div class="faculty-member">Faithful G. Librado, MAEd</div>
                                        <div class="faculty-member">Maryrose F. Liwanag, MAEd</div>
                                        <div class="faculty-member">Aldin C. Llaneta, LPT</div>
                                        <div class="faculty-member">Nica A. Mañabo, LPT</div>
                                        <div class="faculty-member">Shane C. Mapanoo, LPT</div>
                                        <div class="faculty-member">Janine Kenneth C. Mendoza, LPT</div>
                                        <div class="faculty-member">John Elli G. Mendoza, LPT</div>
                                        <div class="faculty-member">Jose Miguel G. Miana, LPT</div>
                                        <div class="faculty-member">Belermino G. Montañez, LPT</div>
                                        <div class="faculty-member">Reggie R. Mueden, LPT</div>
                                        <div class="faculty-member">Maria Isabel L. Naval, LPT</div>
                                        <div class="faculty-member">William James T. Obrero, LPT</div>
                                        <div class="faculty-member">Arwin Francis R. Ocson</div>
                                        <div class="faculty-member">Rina Lyka E. Olata, LPT</div>
                                        <div class="faculty-member">Jojie B. Restrivera, LPT</div>
                                        <div class="faculty-member">Hezron S. Rocero, LPT</div>
                                        <div class="faculty-member">Lean Dennis M. Roldan, MAEd</div>
                                        <div class="faculty-member">Jonathan M. Salamo, LPT</div>
                                        <div class="faculty-member">John Carlo I. Salivio, LPT</div>
                                        <div class="faculty-member">Cedrick L. Santiago, LPT</div>
                                        <div class="faculty-member">John Menson V. Santidad, MAEd</div>
                                        <div class="faculty-member">Jeric V. Sarcia, LPT</div>
                                        <div class="faculty-member">Mary Ann C. Satsatin, LPT</div>
                                        <div class="faculty-member">Mark Braian B. Tandoc, LPT</div>
                                        <div class="faculty-member">Ma. Angelica V. Vesorio</div>
                                        <div class="faculty-member">Jeanilyn D. Villaralvo, LPT</div>
                                    </div>
                                </div>
                                
                                <div class="faculty-category">
                                    <h3>Part-Time Faculty</h3>
                                    <div class="faculty-list">
                                        <div class="faculty-member">Marcelino Almari</div>
                                        <div class="faculty-member">Michael M. Orozco, DIT</div>
                                        <div class="faculty-member">Paul Vincyd Peralta</div>
                                        <div class="faculty-member">Mark Anthony Tamayo</div>
                                        <div class="faculty-member">Emmanuel Vargas</div>
                                        <div class="faculty-member">Angelo Villapena</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Quick Facts</h3>
                        <ul>
                            <li><strong>Duration:</strong> 2 years</li>
                            <li><strong>Grade Levels:</strong> 11-12</li>
                            <li><strong>Tracks Available:</strong> 3</li>
                            <li><strong>Strands Available:</strong> 9</li>
                            <li><strong>Faculty Members:</strong> 50+</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Business Hours</h3>
                        <p><strong>Weekdays:</strong> 8am to 5pm<br>
                        <strong>Saturday:</strong> 8am to 5pm<br>
                        <strong>Sunday:</strong> Closed</p>
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
include '../app/includes/footer.php';
?>



