<?php
/**
 * UPHSL Maritime Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the Maritime program at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Maritime Education";

// Set base path for assets
$base_path = '../';

// Set background image path
$bg_image = 'img/banner/MARITIME.jpg';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('<?php echo $bg_image; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="<?php echo $base_path; ?>programs/img/logo/logo-cmt.png" alt="Maritime Logo">
            </div>
            <div class="banner-content">
                <h1>College of Maritime Education</h1>
                <p>Educating and training competent seafarers and maritime officers with knowledge, skills, and values essential for a dignified maritime profession.</p>
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
                            <p>To educate, train and development young men and women to become competent seafarers and maritime officers with distinct knowledge, skills, attitudes and values, and with high sense of diligence, loyalty, service, and honor essential to the pursuit of a dignified maritime profession in support of national development.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>The College of Maritime Education (CME) of the University of Perpetual Help System Laguna (UPHSL) envisions itself to be a premier institution that provides quality maritime education and training for the development of competent seafarers, and maritime officers.</p>
                        </section>

                        <!-- CME College Quality Objectives Section -->
                        <section class="mission-vision-section">
                            <h2>CME College Quality Objectives</h2>
                            <ul>
                                <li>To develop Global Maritime Professionals (GMP) imbued with Perpetualite values.</li>
                                <li>To ensure compliance with national and international maritime education and training standards and best practices.</li>
                                <li>To be recognized as a leading maritime higher education institution both locally and internationally.</li>
                                <li>To promote knowledge creation and innovation through research output that contribute to the advancement of maritime professions.</li>
                                <li>To engage in community extension activities that apply maritime best practices and traditions to uplift partner communities through sustainable maritime operations.</li>
                                <li>To integrate innovative and cutting-edge technologies and learning resources in the delivery of maritime education and training.</li>
                                <li>To promote awareness and protection of intellectual property rights to foster creativity, responsible innovation, and respect for ownership in academic outputs.</li>
                            </ul>
                        </section>

                        <!-- Programs Section -->
                        <section class="programs-section">
                            <h2>Programs Offered</h2>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Marine Transportation (BSMT)</h3>
                                <p>BSMT is a Maritime Education and Training Program that covers the mandatory education and training for Officers in Charge of a Navigational Watch required under Regulation II/1, Paragraph 2.5 of the STCW Convention, 1978, as amended.</p>
                                <p>The BSMT Program shall cover the study of navigation, cargo handling and stowage, controlling the operation of the ship and care for persons on board at the operational level and some of the knowledge and understanding under Table A-II/2.</p>
                                
                                <h4>Program Educational Objectives</h4>
                                <p>Guided by the University Mission, the BSMT Perpetualite students are:</p>
                                <ol>
                                    <li>Equipped with the knowledge, understanding, proficiencies, skills, competencies, attitudes and values to qualify them for:
                                        <ul>
                                            <li>Professional licensure examination; and</li>
                                            <li>Assessment and Certification as Officer-In-Charge of a Navigational Watch on seagoing ships of 500 gross tonnage or more.</li>
                                        </ul>
                                    </li>
                                    <li>Competent to carry out safely the tasks, duties and responsibilities of an Officer-In-Charge of a Navigational Watch on seagoing ships of 500 gross tonnage or more, both at sea and in port.</li>
                                    <li>Fully conversant with the basic principles to be observed in keeping a navigational as per STCW Regulation VIII/2 and Chapter VIII of the STCW Code.</li>
                                    <li>Qualified to pursue a professional career or advanced studies in any maritime field of specialization.</li>
                                </ol>
                                
                                <h4>Program Outcomes</h4>
                                <p>A graduate of BSMT shall have the ability to:</p>
                                <ul>
                                    <li>Plan and conduct passage and determine position</li>
                                    <li>Maintain a safe navigational watch</li>
                                    <li>Use of radar and ARPA to maintain safety of navigation</li>
                                    <li>Use of ECDIS to maintain the safety of navigation</li>
                                    <li>Respond to emergencies</li>
                                    <li>Respond to a distress signal at sea</li>
                                    <li>Use of the IMO Standard Marine Communication Phrases and use English in written and oral form</li>
                                    <li>Transmit and receive information by visual signaling</li>
                                    <li>Maneuver the ship</li>
                                    <li>Monitor the loading, stowage, securing, care during the voyage and the unloading of cargoes</li>
                                    <li>Engage in lifelong learning and understanding to keep abreast of the developments in maritime practice</li>
                                    <li>Work independently and in multi-disciplinary and multi-cultural teams</li>
                                    <li>Act in recognition and practice of professional, social, and ethical accountability and responsibility</li>
                                    <li>Preserve and promote "Filipino historical and cultural heritage"</li>
                                    <li>Apply knowledge in mathematics, science and technology in solving problems related to the profession and the workplace</li>
                                    <li>Evaluate the impact and implications of various contemporary issues in the global and social context of the profession</li>
                                    <li>Use appropriate techniques, skills and modern tools in the practice of the profession in order to remain globally competitive</li>
                                    <li>Conduct research using appropriate research methodologies</li>
                                    <li>Ensure compliance with pollution prevention requirements</li>
                                    <li>Maintain seaworthiness of the ship</li>
                                    <li>Prevent, control and fight fires onboard</li>
                                    <li>Operate life-saving appliances</li>
                                    <li>Apply medical first aid on board ship</li>
                                    <li>Monitor compliance with legislative requirements</li>
                                    <li>Application of leadership and teamwork skills</li>
                                    <li>Contribute to the safety of personnel and ship</li>
                                </ul>
                                
                                <h4>Career Opportunities</h4>
                                <p>A graduate of BSMT program may find careers in:</p>
                                <div class="career-grid">
                                    <div class="career-category">
                                        <ul>
                                            <li>Merchant Marine Profession</li>
                                            <li>Maritime Industry</li>
                                            <li>Ship building and repair</li>
                                            <li>Ship operations and management</li>
                                            <li>Port Operations and Management</li>
                                            <li>Ship Surveying and inspection</li>
                                            <li>Offshore industry</li>
                                            <li>Maritime Education and Training</li>
                                        </ul>
                                    </div>
                                    <div class="career-category">
                                        <ul>
                                            <li>Government</li>
                                            <li>Philippine Navy</li>
                                            <li>Philippine Coast Guard</li>
                                            <li>Maritime Industry Authority</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Marine Engineering (BSMarE)</h3>
                                <p>BSMarE is a Maritime Education and Training Program that covers the mandatory education and training for Officers in Charge of an Engineering Watch required under Regulation III/1, Paragraph 2.4 of the STCW Convention, 1978, as amended.</p>
                                <p>The BSMarE program shall cover the study of marine engineering, electrical, electronic, and control engineering, maintenance and repair, controlling the operation of the ship and care for persons on board at the operational level and some of the knowledge and understanding under Table A-III/2 are included in the program.</p>
                                
                                <h4>Program Educational Objectives</h4>
                                <p>Guided by the University Mission, the BSMarE Perpetualite students are:</p>
                                <ol>
                                    <li>Equipped with the knowledge, understanding, proficiencies, skills, competencies, attitudes and values to qualify them for:
                                        <ul>
                                            <li>Professional licensure examination; and</li>
                                            <li>Assessment and Certification as Officer-In-Charge of an Engineering Watch in a manned engine room or designated duty engineer officer in periodically unmanned engine room on seagoing ships powered by main propulsion machinery of 750 kW propulsion power or more.</li>
                                        </ul>
                                    </li>
                                    <li>Competent to carry out safely the tasks, duties and responsibilities of an Officer-In-Charge of an Engineering Watch in a manned engine room or designated duty engineer officer in periodically unmanned engine room, both at sea and in port.</li>
                                    <li>Fully conversant with the basic principles to be observed in keeping a navigational as per STCW Regulation VIII/2 and Chapter VIII of the STCW Code.</li>
                                    <li>Qualified to pursue a professional career or advanced studies in any maritime field of specialization.</li>
                                </ol>
                                
                                <h4>Program Outcomes</h4>
                                <p>A graduate of BSMarE shall have the ability to:</p>
                                <ul>
                                    <li>Maintain a safe engineering watch</li>
                                    <li>Use English in written and oral form</li>
                                    <li>Use internal communication systems</li>
                                    <li>Operate main and auxiliary machineries and associated control systems</li>
                                    <li>Operate fuel, lubrication, ballast, and other pumping systems and associated control system</li>
                                    <li>Operate electrical, electronic and control systems</li>
                                    <li>Maintenance and repair of the electrical, electronic equipment and control system</li>
                                    <li>Appropriate use of hand tools, machine tools and measuring instruments for fabrication and repair onboard</li>
                                    <li>Maintenance and repair of shipboard machinery and equipment</li>
                                    <li>Engage in lifelong learning and understanding to keep abreast of the developments in maritime practice</li>
                                    <li>Work independently and in multi-disciplinary and multi-cultural teams</li>
                                    <li>Act in recognition and practice of professional, social, and ethical accountability and responsibility</li>
                                    <li>Preserve and promote "Filipino historical and cultural heritage"</li>
                                    <li>Apply knowledge in mathematics, science and technology in solving problems related to the profession and the workplace</li>
                                    <li>Evaluate the impact and implications of various contemporary issues in the global and social context of the profession</li>
                                    <li>Use appropriate techniques, skills and modern tools in the practice of the profession in order to remain globally competitive</li>
                                    <li>Conduct research using appropriate research methodologies</li>
                                    <li>Ensure compliance with pollution prevention requirements</li>
                                    <li>Maintain seaworthiness of the ship</li>
                                    <li>Prevent, control and fight fires onboard</li>
                                    <li>Operate life-saving appliances</li>
                                    <li>Apply medical first aid on board ship</li>
                                    <li>Monitor compliance with legislative requirements</li>
                                    <li>Application of leadership and teamwork skills</li>
                                    <li>Contribute to the safety of personnel and ship</li>
                                </ul>
                                
                                <h4>Career Opportunities</h4>
                                <p>A graduate of BSMarE program may find careers in:</p>
                                <div class="career-grid">
                                    <div class="career-category">
                                        <ul>
                                            <li>Merchant Marine Profession</li>
                                            <li>Maritime Industry</li>
                                            <li>Ship building and repair</li>
                                            <li>Ship operations and management</li>
                                            <li>Port Operations and Management</li>
                                            <li>Ship Surveying and inspection</li>
                                            <li>Offshore industry</li>
                                            <li>Maritime Education and Training</li>
                                            <li>Industrial and Commercial Establishment</li>
                                        </ul>
                                    </div>
                                    <div class="career-category">
                                        <ul>
                                            <li>Government</li>
                                            <li>Philippine Navy</li>
                                            <li>Philippine Coast Guard</li>
                                            <li>Maritime Industry Authority</li>
                                        </ul>
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
                            <li><strong>College:</strong> College of Maritime Education</li>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Programs:</strong> 2 Bachelor's Programs</li>
                            <li><strong>Focus:</strong> Maritime Excellence</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Programs Offered</h3>
                        <ul>
                            <li><strong>BSMT:</strong> Bachelor of Science in Marine Transportation</li>
                            <li><strong>BSMarE:</strong> Bachelor of Science in Marine Engineering</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Key Features</h3>
                        <ul>
                            <li>STCW Convention compliance</li>
                            <li>Global Maritime Professional development</li>
                            <li>International standards and best practices</li>
                            <li>Industry partnerships</li>
                            <li>Modern maritime facilities</li>
                            <li>Hands-on training and simulation</li>
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



