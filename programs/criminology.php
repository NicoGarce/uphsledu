<?php
/**
 * UPHSL Criminology Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the Criminology program at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Criminology";

// Set base path for assets
$base_path = '../';

// Set background image path
$bg_image = 'img/banner/CRIMINOLOGY.jpg';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('<?php echo $bg_image; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="<?php echo $base_path; ?>programs/img/logo/uphsl-criminology-logo.png" alt="Criminology Logo">
            </div>
            <div class="banner-content">
                <h1>College of Criminology</h1>
                <p>The College of Criminology aims to produce graduates imbued with technical skills and knowledge in the field of law enforcement, public safety, industrial security and social defense, coupled with a deep sense of loyalty to God, country, people, and Alma Mater.</p>
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
                    $category = getCategoryByName('Criminology');
                    $categoryId = $category ? $category['id'] : null;
                    $sectionTitle = 'Criminology News & Updates';
                    $sectionDescription = 'Stay updated with the latest news and announcements from the College of Criminology.';
                    include '../app/includes/news-carousel.php';
                    ?>
                    
                    <article class="content-article">
                        <!-- Mission Section -->
                        <section class="mission-vision-section">
                            <h2>Mission</h2>
                            <p>UPHSL College of Criminology is committed to develop competent, morally upright, and service and research oriented graduates, who have the passion for excellence in the field of criminology and criminal justice administration.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>UPHSL College of Criminology is committed to develop competent, morally upright, and service and research oriented graduates, who have the passion for excellence in the field of criminology and criminal justice administration.</p>
                        </section>

                        <!-- Programs Section -->
                        <section class="programs-section">
                            <h2>Programs Offered</h2>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Criminology</h3>
                                <p>The University of Perpetual Help System Laguna Bachelor of Science in Criminology program is dedicated to produce graduates who:</p>
                                <ul>
                                    <li>Deliver efficient and effective services in the field of criminology and criminal justice administration</li>
                                    <li>Respectful of human rights</li>
                                    <li>Foster the value of leadership, integrity, accountability and responsibility while serving the community and the country</li>
                                    <li>Are service and research oriented criminologists and criminal justice professionals</li>
                                    <li>Are continually engage in life-long learning and professional development through continuing professional education</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Articulate and discuss the latest development in the specific field of practice (PQF level 6 descriptor)</li>
                                    <li>Effectively communicate orally and in writing using both English and Filipino</li>
                                    <li>Work effectively and independently in multi-disciplinary and multi-cultural teams (PQF level 6 descriptor)</li>
                                    <li>Act in recognition of professional, social and ethical responsibility</li>
                                    <li>Preserve and promote "Filipino historical and cultural heritage" (based on RA 7722)</li>
                                    <li>Conduct criminological research on crime, crime caution, victims, and offenders to include deviant behavior</li>
                                    <li>Internalize the concepts of human rights and victim welfare</li>
                                    <li>Demonstrate competence and broad understanding in law enforcement administration, public safety and criminal justice</li>
                                    <li>Utilize criminalistics or forensic science in the investigation and detection of crime</li>
                                    <li>Apply the principles and jurisprudence of criminal law, evidence and criminal procedure</li>
                                    <li>Ensure offenders welfare and development for their re-integration to the community</li>
                                    <li>Participate in the generation of new knowledge or in research and development projects</li>
                                </ul>
                            </div>
                        </section>

                        <!-- Career Opportunities Section -->
                        <section class="career-opportunities-section">
                            <h2>Career Opportunities</h2>
                            <div class="career-grid">
                                <div class="career-category">
                                    <h3>Government Agencies</h3>
                                    <ul>
                                        <li>PHILIPPINE NATIONAL POLICE</li>
                                        <li>BUREAU OF FIRE PROTECTION</li>
                                        <li>BUREAU OF JAIL MANAGEMENT AND PENOLOGY</li>
                                        <li>PHILIPPINE DRUG ENFORCEMENT AGENCY</li>
                                        <li>NATIONAL BUREAU OF INVESTIGATION</li>
                                        <li>BUREAU OF IMMIGRATION</li>
                                        <li>BUREAU OF CORRECTIONS</li>
                                        <li>PHILIPPINE COAST GUARD</li>
                                        <li>LAND TRANSPORTATION OFFICE</li>
                                        <li>PHILIPPINE ARMY</li>
                                        <li>PHILIPPINE AIRFORCE</li>
                                        <li>PHILIPPINE NAVY</li>
                                        <li>BANKO SENTRAL NG PILIPINAS</li>
                                    </ul>
                                </div>
                                
                                <div class="career-category">
                                    <h3>Private Sector</h3>
                                    <ul>
                                        <li>PRIVATE INDUSTRIES</li>
                                        <li>PRIVATE SECURITY POSITIONS</li>
                                    </ul>
                                </div>
                            </div>
                        </section>

                        <!-- Quality Policy Section -->
                        <section class="objectives-section">
                            <h2>Quality Policy</h2>
                            <p>The College of Criminology is committed to producing competent and competitive criminology/criminal justice professionals and information science specialist who are holistic graduates, achievers of life imbued with Christian values and research oriented leaders in quality education.</p>
                            
                            <p>Pursuing our commitment through:</p>
                            <ul>
                                <li>Relevant and updated curriculum</li>
                                <li>Adept delivery mechanism</li>
                                <li>Intellectual and professional fulfillment of faculty and staff</li>
                                <li>Quality Research</li>
                                <li>College Social and Environmental responsibility</li>
                                <li>Involvement of all stakeholders in growth and development of the college</li>
                                <li>Continuous quality improvement upgrading of infrastructure and facilities</li>
                                <li>Resilient congenial facilitative learning environment</li>
                                <li>Spiritual formation - Stewards of God's creation</li>
                            </ul>
                        </section>

                        <!-- Quality Objectives Section -->
                        <section class="objectives-section">
                            <h2>Quality Objectives</h2>
                            <ul>
                                <li>To develop professionals with appropriate technical and professional competencies for international market</li>
                                <li>To achieve recognition as one of the respected universities in the country</li>
                                <li>To serve as venue for knowledge generation and dissemination</li>
                                <li>To uplift the quality of life of people living in the adopted community</li>
                                <li>To deliver quality services to the clientele</li>
                            </ul>
                        </section>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>College:</strong> College of Criminology</li>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Program:</strong> Bachelor of Science in Criminology</li>
                            <li><strong>Focus:</strong> Law Enforcement & Criminal Justice</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Key Features</h3>
                        <ul>
                            <li>Technical skills and knowledge</li>
                            <li>Law enforcement training</li>
                            <li>Public safety education</li>
                            <li>Industrial security focus</li>
                            <li>Social defense principles</li>
                            <li>Christian values integration</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Career Preparation</h3>
                        <ul>
                            <li>Government agencies</li>
                            <li>Law enforcement</li>
                            <li>Forensic science</li>
                            <li>Corrections</li>
                            <li>Private security</li>
                            <li>Research and development</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Professional Values</h3>
                        <ul>
                            <li>Loyalty to God</li>
                            <li>Service to country</li>
                            <li>Dedication to people</li>
                            <li>Alma Mater pride</li>
                            <li>Moral uprightness</li>
                            <li>Research orientation</li>
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



