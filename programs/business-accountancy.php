<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Business & Accountancy";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/BUSINESS AND ACCOUNTANCY.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="img/logo/uphsl-cba_logo.png" alt="Business and Accountancy Logo">
            </div>
            <div class="banner-content">
                <h1>College of Business & Accountancy</h1>
                <p>The College of Business Administration and Accountancy develops effective skills of analysis, strategic thinking, communication, and techniques on managing different personalities - crucial tools for a successful career.</p>
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
                            <p>The program shall produce highly-competent graduates who are socially responsible, ethical business practitioners and accounting professionals.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>In consonance with the vision of the University, the College of Business and Accountancy shall always be the model for academic excellence in the area of Business and Accounting, setting the trends and standards in the academic community.</p>
                        </section>

                        <!-- Programs Section -->
                        <section class="programs-section">
                            <h2>Programs Offered</h2>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Accountancy (BSA)</h3>
                                <p>The BSA program shall produce graduates who:</p>
                                <ul>
                                    <li>Possess competencies, discipline, and technical training as CPAs, practitioners, land gainful employment and/or become successful entrepreneurs</li>
                                    <li>Provide variety of accounting services executed at the highest level of ethical standards</li>
                                    <li>Uphold the standards and ethical practices of the accounting profession to ensure the economic well-being of the business community and of the country</li>
                                    <li>Make positive contribution for the continuous improvement of the accountancy profession through research</li>
                                    <li>Demonstrate proficiency in organizational communication for effective human relations</li>
                                    <li>Apply effective leadership in the practice of profession</li>
                                    <li>Participate actively in activities involving community welfare, environment protection, economic and spiritual development</li>
                                    <li>Value the importance of continuing business educational opportunities</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Resolve business issues and problems, with a global and strategic perspective using knowledge and technical proficiency in the areas of financial accounting and reporting, cost accounting and management, accounting and control, taxation and accounting information systems</li>
                                    <li>Conduct accountancy research through independent studies of relevant literature and appropriate use of accounting theory and methodologies</li>
                                    <li>Employ technology as a business tool in capturing financial and non-financial information, generating reports and making decisions</li>
                                    <li>Apply knowledge and skills to successfully respond to various types of assessments (including professional licensure and certifications)</li>
                                    <li>Confidently maintain a commitment to good corporate citizenship, social responsibility and ethical practice in performing functions as an accountant</li>
                                    <li>Express clearly and communicate effectively with stakeholders both in oral and written forms</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Management Accounting (BSMA)</h3>
                                <p>The BSMA program shall produce graduates who:</p>
                                <ul>
                                    <li>Possess competencies, discipline, and technical training as accounting practitioners, land gainful employment and/or become successful entrepreneurs</li>
                                    <li>Provide variety of accounting services executed at the highest level of ethical standards</li>
                                    <li>Uphold the standards and ethical practices of the accounting profession to ensure the economic well-being of the business community and of the country</li>
                                    <li>Make positive contribution for the continuous improvement of the accountancy profession through research</li>
                                    <li>Demonstrate proficiency in organizational communication for effective human relations</li>
                                    <li>Apply effective leadership in the practice of profession</li>
                                    <li>Participate actively in activities involving community welfare, environment protection, economic and spiritual development</li>
                                    <li>Value the importance of continuing business educational opportunities</li>
                                </ul>
                                
                                <h4>Student Outcomes:</h4>
                                <ul>
                                    <li>Resolve business issues and problems, with a global and strategic perspective using knowledge and technical proficiency in the areas of financial accounting and reporting, cost accounting and management, accounting and control, taxation and accounting information systems</li>
                                    <li>Conduct accountancy research through independent studies of relevant literature and appropriate use of accounting theory and methodologies</li>
                                    <li>Employ technology as a business tool in capturing financial and non-financial information, generating reports and making decisions</li>
                                    <li>Apply knowledge and skills to successfully respond to various types of assessments (including professional licensure and certifications)</li>
                                    <li>Confidently maintain a commitment to good corporate citizenship, social responsibility and ethical practice in performing functions as an accountant</li>
                                    <li>Express clearly and communicate effectively with stakeholders both in oral and written forms</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Business Administration, Major in:</h3>
                                <div class="major-grid">
                                    <div class="major-card">
                                        <h4>Business Management</h4>
                                        <p>Comprehensive business education covering all aspects of management and administration.</p>
                                    </div>
                                    
                                    <div class="major-card">
                                        <h4>Marketing Management</h4>
                                        <p>Specialized focus on marketing strategies, consumer behavior, and brand management.</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Linkages Section -->
                        <section class="programs-section">
                            <h2>Industry Linkages</h2>
                            <p>Our college maintains strong partnerships with various industry leaders to provide students with real-world experience and networking opportunities:</p>
                            
                            <div class="linkages-grid">
                                <div class="linkage-category">
                                    <h3>Financial Services</h3>
                                    <ul>
                                        <li><strong>RCBC</strong> - Banking and financial services (Muntinlupa City, Manila)</li>
                                        <li><strong>PNB</strong> - Banking and financial services (City of Biñan, Laguna and San Pedro Laguna)</li>
                                        <li><strong>UCPB</strong> - Banking and financial services (City of Biñan, Laguna and Sta. Rosa City Laguna)</li>
                                        <li><strong>Maybank</strong> - Commercial banking (City of Biñan, Laguna and Sta. Rosa City Laguna)</li>
                                        <li><strong>University Savings Bank</strong> - Banking and financial services (City of Biñan, Laguna)</li>
                                        <li><strong>Entrepreneur Bank</strong> - Banking services (Mabini Street, San Pedro, Laguna)</li>
                                    </ul>
                                </div>
                                
                                <div class="linkage-category">
                                    <h3>Accounting & Professional Services</h3>
                                    <ul>
                                        <li><strong>IDE Castro</strong> - Accounting and auditing services</li>
                                        <li><strong>MJN Accounting</strong> - Accounting and auditing services (Sta. Rosa City, Laguna)</li>
                                        <li><strong>SRHRC</strong> - Accounting, auditing and tax services (Carmona Cavite)</li>
                                    </ul>
                                </div>
                                
                                <div class="linkage-category">
                                    <h3>Corporate Partners</h3>
                                    <ul>
                                        <li><strong>HRInternational Inc.</strong> - Educational and cultural exchange programs (Las Piñas City)</li>
                                        <li><strong>P.A Properties</strong> - Real estate development (San Pedro, Laguna)</li>
                                        <li><strong>Motorcentral</strong> - Motorcycle dealership (San Antonio, City of Biñan, Laguna)</li>
                                        <li><strong>Isuzu</strong> - Commercial and industrial vehicles (City of Biñan, Laguna)</li>
                                        <li><strong>Filinvest City</strong> - Integrated development (Alabang, Muntinlupa, Metro Manila)</li>
                                        <li><strong>Enchanted Kingdom</strong> - Theme park operations (Santa Rosa, Laguna)</li>
                                        <li><strong>Coca-Cola</strong> - Beverage company (City of Sta. Rosa, Laguna)</li>
                                        <li><strong>Ford</strong> - Automotive (Zapote Road Alabang and Sta. Rosa city Laguna)</li>
                                        <li><strong>Honda</strong> - Automotive dealership (Sta. Rosa City, Laguna and Carmona Exit, Cavite)</li>
                                        <li><strong>Panasonic</strong> - Electronics (City of Biñan Laguna)</li>
                                        <li><strong>Chevrolet</strong> - Automotive (Carmona Exit, Cavite and City of Sta. Rosa, Laguna)</li>
                                        <li><strong>Shopwise</strong> - Retail and hypermarkets (Festival Mall, Filinvest, Alabang, Muntinlupa City, Manila)</li>
                                    </ul>
                                </div>
                                
                                <div class="linkage-category">
                                    <h3>Government & Public Sector</h3>
                                    <ul>
                                        <li><strong>City of Biñan</strong> - Local government unit (Barangay Zapote, City of Biñan, Laguna)</li>
                                        <li><strong>National Power Corporation</strong> - Power generation (Carmona, Cavite)</li>
                                        <li><strong>Pag-Ibig Fund</strong> - Housing and savings program (City of Biñan, Laguna)</li>
                                        <li><strong>Social Security System</strong> - Social security protection (City of Biñan, Laguna)</li>
                                    </ul>
                                </div>
                            </div>
                        </section>

                        <!-- Facilities Section -->
                        <section class="programs-section">
                            <h2>Facilities</h2>
                            <p>Our college is equipped with state-of-the-art facilities to support student learning and development:</p>
                            <ul>
                                <li><strong>Computer Lab 1</strong> - Modern computer laboratory for accounting and business software training</li>
                                <li><strong>Mac Lab</strong> - Apple Macintosh laboratory for multimedia and design work</li>
                                <li><strong>Computer Lab 2</strong> - Additional computer facility for research and coursework</li>
                            </ul>
                        </section>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>College:</strong> College of Business & Accountancy</li>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Programs:</strong> 3 Bachelor's Programs</li>
                            <li><strong>Focus:</strong> Business & Accounting Excellence</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Programs Offered</h3>
                        <ul>
                            <li><strong>BSA:</strong> Bachelor of Science in Accountancy</li>
                            <li><strong>BSMA:</strong> Bachelor of Science in Management Accounting</li>
                            <li><strong>BSBA:</strong> Bachelor of Science in Business Administration</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Key Features</h3>
                        <ul>
                            <li>Effective analysis and strategic thinking</li>
                            <li>Communication skills development</li>
                            <li>Personality management techniques</li>
                            <li>Peer learning opportunities</li>
                            <li>Individual academic advising</li>
                            <li>Networking opportunities</li>
                            <li>Fun college events</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Industry Partnerships</h3>
                        <p>Strong linkages with 20+ industry partners including banks, corporations, government agencies, and professional service firms for real-world experience and career opportunities.</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Email:</strong><br>
                        <a href="mailto:business@uphsl.edu.ph">business@uphsl.edu.ph</a></p>
                        
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
include '../includes/footer.php';
?>
