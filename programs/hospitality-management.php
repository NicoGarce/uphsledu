<?php
session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "International Hospitality Management";

// Set base path for assets
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/CHIM.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="img/logo/uphsl-cihm-logo.png" alt="Hospitality Management Logo">
            </div>
            <div class="banner-content">
                <h1>College of International Hospitality Management</h1>
                <p>The College of International Hospitality Management and Tourism educates the next generation of leaders and managers for the world's fastest growing and most dynamic industry. The impressive CIHM building provides an ideal learning environment through modern facilities to allow students in honing their skills and knowledge in a realistic setting.</p>
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
                            <p>The CIHM is dedicated to produce dynamic, globally-competitive, and successful individuals who are God-fearing and service-oriented, through quality leadership, advancement in education, research, human resource development and extension services.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>The CIHM envisions itself to be the center of excellence in the fields of Hospitality Management and Nutrition and Dietetics through the quality education and development of highly skilled and competent individuals.</p>
                        </section>

                        <!-- Programs Section -->
                        <section class="programs-section">
                            <h2>Programs Offered</h2>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Hospitality Management</h3>
                                <p>The course offers an intensive four-year academic program that equip students with competencies to prepare them to become future hoteliers, restaurateurs and entrepreneurs.</p>
                                
                                <h4>Program Educational Objectives:</h4>
                                <ul>
                                    <li>Apply comprehensive knowledge and skills to keep pace with the global demands in the hospitality and tourism industry</li>
                                    <li>Demonstrate expertise in specialized hospitality and tourism industry positions</li>
                                    <li>Apply effective leadership in managing hospitality and tourism related - businesses</li>
                                    <li>Extend entrepreneurial knowledge and skills in hospitality and tourism services to generate livelihood opportunities for the community</li>
                                    <li>Actualize the Christian virtues in the quest for the best quality of life</li>
                                    <li>Value the importance of research and continuing educational opportunities</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Demonstrate knowledge of tourism industry, local tourism products and services</li>
                                    <li>Utilize information technology applications for tourism and hospitality</li>
                                    <li>Demonstrate administrative and management skills in a service-oriented business organization</li>
                                    <li>Produce food products and services complying with enterprise standards</li>
                                    <li>Perform and provide full guest cycle services for front office</li>
                                    <li>Perform and maintain various housekeeping services for guest and facility operations</li>
                                    <li>Plan and implement a risk management program to provide a safe and secure workplace</li>
                                    <li>Provide food and beverage services, and manage the operation seamlessly based on industry standards</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Nutrition & Dietetics</h3>
                                <p>A degree in Nutrition and Dietetics aims to adequately equip the students with fundamental knowledge and attitude in nutrition, dietetics, food service, management and allied fields in order to prepare them for the responsibilities in food and nutrition research, teaching and commercial food service.</p>
                                
                                <h4>Program Educational Objectives:</h4>
                                <ul>
                                    <li>Apply comprehensive knowledge and skills to keep pace with the global demands in the hospitality and tourism industry</li>
                                    <li>Demonstrate expertise in specialized hospitality and tourism industry positions</li>
                                    <li>Apply effective leadership in managing hospitality and tourism related - businesses</li>
                                    <li>Extend entrepreneurial knowledge and skills in hospitality and tourism services to generate livelihood opportunities for the community</li>
                                    <li>Actualize the Christian virtues in the quest for the best quality of life</li>
                                    <li>Value the importance of research and continuing educational opportunities</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Promote the role of nutrition and dietetics for human well-being in relation to the needs, resources and potentials of individuals, groups and families</li>
                                    <li>Practice comprehensive nutritional care for the total wellness of individuals in a multidisciplinary and multi-cultural settings</li>
                                    <li>Integrate nutrition concerns with local and national development efforts</li>
                                    <li>Manage nutrition programs for individuals, groups and institutions</li>
                                    <li>Manage a foodservice unit in hospital or other settings</li>
                                    <li>Implement an economically viable activity related to nutrition and dietetics</li>
                                    <li>Design and/or conduct scientific study on food, nutrition and related topics</li>
                                    <li>Uphold ethical standards of the profession</li>
                                    <li>Engage in lifelong learning activities</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Tourism Management</h3>
                                <p>Boast of its academic curriculum which provides students necessary skills to prepare them for Hospitality practices in the field of Tourism as early as within a year of enrollment.</p>
                                
                                <h4>Program Educational Objectives:</h4>
                                <ul>
                                    <li>Apply comprehensive knowledge and skills to keep pace with the global demands in the travel and tourism industry</li>
                                    <li>Demonstrate expertise in specialized travel and tourism industry positions</li>
                                    <li>Apply effective leadership in managing travel and tourism related-businesses</li>
                                    <li>Extend entrepreneurial knowledge and skills in travel and tourism services to generate livelihood opportunities for the community</li>
                                    <li>Actualize the Christian virtues in the quest for the best quality of life</li>
                                    <li>Value the importance of research and continuing educational opportunities</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Value the importance of research and continuing educational opportunities</li>
                                    <li>Utilize information technology applications for tourism and hospitality</li>
                                    <li>Utilize various communication channels proficiently in dealing with guests and college</li>
                                    <li>Research, plan, and conduct various tour guiding activities</li>
                                    <li>Develop appropriate marketing programs and arrange the required travel services</li>
                                    <li>Plan / organize, implement, and evaluate MICE activities</li>
                                    <li>Plan, develop, and evaluate tourism sites and attractions</li>
                                </ul>
                            </div>
                        </section>

                        <!-- Quality Objectives Section -->
                        <section class="objectives-section">
                            <h2>Quality Objectives</h2>
                            <p>The College of Engineering, as envisioned by our Founder, should be able to:</p>
                            <ul>
                                <li>To develop cosmopolitan and service provider professional responsive to the needs of hospitality, tourism industry and nutrition related services</li>
                                <li>To achieve recognition as one of the respected universities in the field of Hospitality, Tourism, and Nutrition & Dietetics</li>
                                <li>To develop quality and responsive research activities that address emerging issues in Hospitality, Tourism and Nutrition</li>
                                <li>To develop and implement sustainable program-based community extension activities participated in by various stakeholders</li>
                            </ul>
                        </section>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>College:</strong> International Hospitality Management</li>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Programs:</strong> 3 Bachelor's Programs</li>
                            <li><strong>Focus:</strong> Hospitality & Tourism Excellence</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Programs Offered</h3>
                        <ul>
                            <li><strong>BSHM:</strong> Hospitality Management</li>
                            <li><strong>BSND:</strong> Nutrition & Dietetics</li>
                            <li><strong>BSTM:</strong> Tourism Management</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Key Features</h3>
                        <ul>
                            <li>Center of excellence in hospitality</li>
                            <li>Modern facilities & realistic setting</li>
                            <li>Global industry preparation</li>
                            <li>God-fearing & service-oriented</li>
                            <li>Quality leadership development</li>
                            <li>Research & extension services</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Career Preparation</h3>
                        <ul>
                            <li>Hoteliers & Restaurateurs</li>
                            <li>Tourism Professionals</li>
                            <li>Nutrition & Dietetics</li>
                            <li>Food Service Management</li>
                            <li>Event Management</li>
                            <li>Entrepreneurship</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Email:</strong><br>
                        <a href="mailto:cihm@uphsl.edu.ph">cihm@uphsl.edu.ph</a></p>
                        
                        <p><strong>Phone:</strong><br>
                        02-779-5310</p>
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
