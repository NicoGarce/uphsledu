<?php
/**
 * UPHSL Business and Accountancy Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the Business and Accountancy program at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Business & Accountancy";

// Set base path for assets
$base_path = '../';

// Set background image path
$bg_image = 'img/banner/BUSINESS AND ACCOUNTANCY.jpg';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('<?php echo $bg_image; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="banner-logo">
                <img src="<?php echo $base_path; ?>programs/img/logo/uphsl-cba_logo.png" alt="Business and Accountancy Logo">
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
                    <!-- News Carousel Section -->
                    <?php
                    $category = getCategoryByName('Business & Accountancy');
                    $categoryId = $category ? $category['id'] : null;
                    $sectionTitle = 'Business & Accountancy News & Updates';
                    $sectionDescription = 'Stay updated with the latest news and announcements from the College of Business & Accountancy.';
                    include '../app/includes/news-carousel.php';
                    ?>
                    
                    <article class="content-article">
                        <!-- Mission Section -->
                        <section class="mission-vision-section">
                            <h2>Mission</h2>
                            <p><strong>Bachelor of Science in Accountancy (BSA) and Bachelor of Science in Management Accounting:</strong> The programs shall produce highly-competent graduates who are socially responsible and ethical accounting professionals.</p>
                            <p><strong>Bachelor of Science in Business Administration (BSBA):</strong> The program shall produce highly-competent graduates who are socially responsible and ethical business practitioners.</p>
                        </section>

                        <!-- Vision Section -->
                        <section class="mission-vision-section">
                            <h2>Vision</h2>
                            <p>In consonance with the vision of the University, the College of Business and Accountancy (CBA) shall always be the model for academic excellence in the area of business and accounting, setting the trends and standards in the academic community.</p>
                        </section>

                        <!-- CBA EOMS Quality Objectives Section -->
                        <section class="mission-vision-section">
                            <h2>CBA EOMS Quality Objectives</h2>
                            <ul>
                                <li>To develop business and accountancy graduates with appropriate technical and professional competencies rooted with Perpetualite values and ready for the international market.</li>
                                <li>To comply with professional requirements, regulations and standards for business and accountancy set by the governing agencies.</li>
                                <li>To achieve recognition as one of the respected business and accountancy programs in the country.</li>
                                <li>To promote knowledge creation and innovation through research output that contribute to the advancement of business and accountancy professions.</li>
                                <li>To engage in community extension activities that apply business and accounting information to uplift partner communities through sustainable business activities.</li>
                                <li>To integrate innovative and cutting-edge technologies and learning resources in the delivery of business and accounting education.</li>
                                <li>To promote awareness and protection of intellectual property rights to foster creativity, responsible innovation, and respect for ownership in academic outputs.</li>
                            </ul>
                        </section>

                        <!-- Programs Section -->
                        <section class="programs-section">
                            <h2>Programs Offered</h2>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Accountancy (BSA) / Bachelor of Science in Management Accounting (BSMA)</h3>
                                <p><em>CMO No 27, Series of 2017</em></p>
                                
                                <h4>Program Educational Objectives (PEO)</h4>
                                <p>The BSA/BSMA program shall produce graduates who:</p>
                                <ul>
                                    <li>Possess competencies, discipline, and technical training as accounting practitioners, land gainful employment and/or become successful entrepreneurs.</li>
                                    <li>Provide the public interest with a variety of accounting services executed at the highest level of ethical performance.</li>
                                    <li>Uphold the standards and ethical practices of the accounting profession to ensure the economic well-being of the business community and of the country</li>
                                    <li>Make positive contributions for the continuous improvement of the accountancy profession through research and studies.</li>
                                    <li>Demonstrate proficiency in organizational communication for effective human relations</li>
                                    <li>Apply effective leadership in the management of professional practice</li>
                                    <li>Participate actively in activities involving community welfare, environment protection, economic and spiritual development</li>
                                    <li>Value the importance of continuing business educational opportunities.</li>
                                </ul>
                                
                                <h4>Program Outcomes</h4>
                                <p>A graduate of a BS Accountancy and BS Management Accounting degree should have the ability to:</p>
                                <ul>
                                    <li>Resolve business issues and problems, with a global and strategic perspective using knowledge and technical proficiency in the areas of financial accounting and reporting, cost accounting and management, accounting and control, taxation and accounting information systems;</li>
                                    <li>Conduct accountancy research through independent studies of relevant literature and appropriate use of accounting theory and methodologies;</li>
                                    <li>Employ technology as a business tool in capturing financial and non-financial information, generating reports and making decisions;</li>
                                    <li>Apply knowledge and skills to successfully respond to various types of assessments (including professional licensure and certifications);</li>
                                    <li>Confidently maintain a commitment to good corporate citizenship, social responsibility and ethical practice in performing functions as an accountant; and</li>
                                    <li>Express clearly and communicate effectively with stakeholders both in oral and written forms.</li>
                                </ul>
                                
                                <h4>Career Opportunities</h4>
                                <p><strong>Entry-level jobs:</strong></p>
                                <ul>
                                    <li>Public Practice: Junior Analyst, Consulting staff</li>
                                    <li>Commerce and Industry: Cost Analyst, Investment Analyst, Accountancy Staff, Tax Accounting Staff, Financial Analyst, Budget Analyst, Credit Analyst, Cost Accountant</li>
                                    <li>Government: State Accounting Examiner, NBI Agent, Treasury Agent, State Accountant, LGU Accountant, Revenue Officer, Audit Examiner, Budget Officer, Financial Services Specialist</li>
                                    <li>Education: Junior Accounting Instructor</li>
                                </ul>
                                
                                <p><strong>Middle-level positions:</strong></p>
                                <ul>
                                    <li>Public Practice: Senior Consulting Manager/Financial Advisory Manager</li>
                                    <li>Commerce and Industry: Controller/Comptroller, Senior Information System Auditor, Senior Loan Officer, Senior Budget Officer</li>
                                    <li>Government: State Accountant V, Director III and Director IV, Government Accountancy and Audit, Financial Services Manager, Audit Services Manager, Senior Auditor</li>
                                    <li>Education: Senior Faculty, Accounting Department Chair</li>
                                </ul>
                                
                                <p><strong>Advanced positions:</strong></p>
                                <ul>
                                    <li>Public Practice: Partner, Senior Partner, Senior Consultant/Financial Advisor</li>
                                    <li>Commerce and Industry: Finance Director/Chief Financial Officer, Chief Information Officer</li>
                                    <li>Government: National Treasurer, Vice President for Finance/CFO (for GOCCs), Commissioner, Associate Commissioner, Assistant Commissioner (COA, BIR, BOC)</li>
                                    <li>Education: Vice President for Academic Affairs, Dean</li>
                                </ul>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Business Administration (BSBA)</h3>
                                <p><em>CMO No 27, Series of 2017</em></p>
                                
                                <h4>Major in:</h4>
                                <div class="major-grid">
                                    <div class="major-card">
                                        <h4>Marketing Management</h4>
                                        <p>Specialized focus on marketing strategies, consumer behavior, and brand management.</p>
                                    </div>
                                    
                                    <div class="major-card">
                                        <h4>Business Management</h4>
                                        <p>Comprehensive business education covering all aspects of management and administration.</p>
                                    </div>
                                    
                                    <div class="major-card">
                                        <h4>Financial Management</h4>
                                        <p>Focus on financial analysis, planning, and corporate finance management.</p>
                                    </div>
                                    
                                    <div class="major-card">
                                        <h4>Human Resource Management</h4>
                                        <p>Specialized training in personnel management, recruitment, and organizational development.</p>
                                    </div>
                                </div>
                                
                                <h4>Program Educational Objectives (PEO)</h4>
                                <p>The BSBA program shall produce graduates who:</p>
                                <ul>
                                    <li>Possess competencies, discipline, and technical training as business practitioners, land gainful employment and/or become successful entrepreneurs;</li>
                                    <li>Render professional services in the broad spectrum of management and marketing executed at the highest level of ethical performance;</li>
                                    <li>Make positive contribution for the continuous improvement of the Business Administration profession through research and studies;</li>
                                    <li>Demonstrate proficiency in organizational communication for effective human relations;</li>
                                    <li>Apply effective leadership in the management of professional practice;</li>
                                    <li>Participate actively in activities involving community welfare, environment protection, economic and spiritual development;</li>
                                    <li>Value the importance of continuing business educational opportunities.</li>
                                </ul>
                                
                                <h4>Program Outcomes (PO)</h4>
                                <p>A graduate of a BS Business Administration (BSBA) degree should have the ability to:</p>
                                <ul>
                                    <li>Analyze the business environment for strategic direction;</li>
                                    <li>Prepare operational plans for various type of business to become globally competitive;</li>
                                    <li>Innovate business ideas based on emerging industry;</li>
                                    <li>Manage a strategic business unit for economic sustainability;</li>
                                    <li>Conduct business research by identifying, analyzing, and solving business problems and apply appropriate methods, techniques, strategies, qualitative and quantitative information; and</li>
                                    <li>Demonstrate effective oral and written communication skills in various business setting.</li>
                                </ul>
                                
                                <h4>Career Opportunities</h4>
                                <p><strong>Marketing Management:</strong> Marketing Trainee, Marketing Assistant, Account Executive, Merchandising Assistant, PR/Advertising Assistant, Service Crew, Customer Service Agent/Representative, Junior Sales Trainer, Receptionist, Entrepreneur, Product/Brand Assistant</p>
                                
                                <p><strong>Business Management:</strong> Office Assistant, Administrative Assistant, Executive Assistant, Entrepreneur, Management Trainee</p>
                                
                                <p><strong>Financial Management:</strong> Management Trainee in Corporate Finance, Management Trainee in a Bank or Insurance Company, New Account Personnel, Credit and Collection Assistant, Credit Analyst, Treasury Assistant, Entrepreneur, Cashier, Trader/Financial Analyst</p>
                                
                                <p><strong>Human Resource Management:</strong> Management Trainee in Human Resource Management, Office Assistant, Administrative Assistant, Human Resource Assistant, Executive Assistant, Entrepreneur, Recruitment Assistant, Compensation Assistant, Benefits Assistant, Training and Development</p>
                            </div>
                            
                            <div class="program-section">
                                <h3>Bachelor of Science in Real Estate Management (BS REM)</h3>
                                <p><em>New program offering effective academic year 2026-2027 - CMO No 28, Series of 2011</em></p>
                                
                                <h4>Program Educational Objectives</h4>
                                <p>The BS Real Estate Management program shall produce graduates who:</p>
                                <ul>
                                    <li>Possess competencies, discipline, and technical training as real estate broker, appraiser and consultant, land gainful employment and/or become successful entrepreneurs.</li>
                                    <li>Render professional services in the broad spectrum of real estate management and business executed at the highest level of ethical practices, real estate laws and professional standards.</li>
                                    <li>Make positive contribution for the continuous improvement of the Real Estate Management profession through research studies.</li>
                                    <li>Demonstrate proficiency in organizational communication for effective human relations and effective leadership skills in the real estate professional practices.</li>
                                    <li>Participate actively in activities involving community welfare, environment protection, economic, spiritual development and fostering business innovation in the real estate industry.</li>
                                </ul>
                                
                                <h4>Student Outcomes</h4>
                                <p>A graduate of BS Real Estate Management degree should have the ability to:</p>
                                <ul>
                                    <li>Perform quality real estate service work as a real estate broker, appraiser, consultant or successful entrepreneur contributing to economic sustainability.</li>
                                    <li>Render services in estimating and arriving at an opinion of acts as an expert on real estate values, such services of which shall be finally rendered by preparation of the report in acceptable written form guided by highest level of ethical practices and apply real estate laws and standards.</li>
                                    <li>Conduct program base research related to real estate business, innovation engagement and submit for utilization to intended users or institution for its improvement.</li>
                                    <li>Apply the principles of the different forms of communication and convey ideas clearly both oral and written form.</li>
                                    <li>Perform fair market value assessment in the municipal, city, and provincial levels for real estate and public services purposes and help uplift the life of people living in the community.</li>
                                    <li>Demonstrate leadership qualities, civic mindedness, responsible citizenship and participate actively in professional activities that aim strong corporate social responsibilities.</li>
                                </ul>
                                
                                <h4>Career Opportunities</h4>
                                <p><strong>Real Estate Broker:</strong> Entrepreneur, Management Trainee in Real Estate Brokerage, Marketing Assistant, Administrative Assistant, Salesperson, Executive Assistant, Real Estate Marketing Assistant, Real Estate Management Trainee, Real Estate Junior Sales Trainee, Account Personnel, Credit and Collection Assistant</p>
                                
                                <p><strong>Real Estate Appraiser:</strong> Entrepreneur, Management Trainee in Real Estate Appraisal, Appraisal Assistant, Administrative Assistant, Executive Assistant, Real Estate Junior Appraisal Trainee, Credit and Collection Assistant, Real Estate Appraisal Analyst</p>
                                
                                <p><strong>Real Estate Consultant:</strong> Entrepreneur, Management Trainee in Real Estate Consultancy, Office Assistant, Administrative Assistant, Real Estate Consultant Assistant, Executive Assistant, Real Estate Junior Consultancy Trainee, New Account Personnel, Credit and Collection Assistant</p>
                                
                                <p><strong>Local Government Assessor:</strong> Office Assistant, Administrative Assistant, Municipal Government Assessor, Municipal Government Assistant Assessor, City Government Assessor, City Government Assistant Assessor, Local Assessor Officer (Municipal, City, Province), Administrative Assistant in the Assessor's Office (Municipal, City, Province)</p>
                            </div>
                        </section>

                        <!-- Linkages Section -->
                        <section class="programs-section">
                            <h2>Industry Linkages</h2>
                            <p>Our college maintains strong partnerships with various industry leaders to provide students with real-world experience and networking opportunities:</p>
                            
                            <div class="linkages-grid">
                                <div class="linkage-category">
                                    <h3>Financial Services</h3>
                                    <ul class="linkage-list">
                                        <li>
                                            <img src="img/ba/Linkages/rcbc.jpg" alt="RCBC Logo" class="linkage-icon">
                                            <div>
                                                <strong>RCBC</strong> - Banking and financial services (Muntinlupa City, Manila)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/pnb.jpg" alt="PNB Logo" class="linkage-icon">
                                            <div>
                                                <strong>PNB</strong> - Banking and financial services (City of Biñan, Laguna and San Pedro Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/ucpb.jpg" alt="UCPB Logo" class="linkage-icon">
                                            <div>
                                                <strong>UCPB</strong> - Banking and financial services (City of Biñan, Laguna and Sta. Rosa City Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/maybank.jpg" alt="Maybank Logo" class="linkage-icon">
                                            <div>
                                                <strong>Maybank</strong> - Commercial banking (City of Biñan, Laguna and Sta. Rosa City Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/usala.jpg" alt="University Savings Bank Logo" class="linkage-icon">
                                            <div>
                                                <strong>University Savings Bank</strong> - Banking and financial services (City of Biñan, Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/entre.jpg" alt="Entrepreneur Bank Logo" class="linkage-icon">
                                            <div>
                                                <strong>Entrepreneur Bank</strong> - Banking services (Mabini Street, San Pedro, Laguna)
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="linkage-category">
                                    <h3>Accounting & Professional Services</h3>
                                    <ul class="linkage-list">
                                        <li>
                                            <img src="img/ba/Linkages/decastro.jpg" alt="IDE Castro Logo" class="linkage-icon">
                                            <div>
                                                <strong>IDE Castro</strong> - Accounting and auditing services
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/mjn.jpg" alt="MJN Accounting Logo" class="linkage-icon">
                                            <div>
                                                <strong>MJN Accounting</strong> - Accounting and auditing services (Sta. Rosa City, Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/srhrc.jpg" alt="SRHRC Logo" class="linkage-icon">
                                            <div>
                                                <strong>SRHRC</strong> - Accounting, auditing and tax services (Carmona Cavite)
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="linkage-category">
                                    <h3>Corporate Partners</h3>
                                    <ul class="linkage-list">
                                        <li>
                                            <img src="img/ba/Linkages/HR.png" alt="HRInternational Inc. Logo" class="linkage-icon">
                                            <div>
                                                <strong>HRInternational Inc.</strong> - Educational and cultural exchange programs (Las Piñas City)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/pa.jpg" alt="P.A Properties Logo" class="linkage-icon">
                                            <div>
                                                <strong>P.A Properties</strong> - Real estate development (San Pedro, Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/motor.jpg" alt="Motorcentral Logo" class="linkage-icon">
                                            <div>
                                                <strong>Motorcentral</strong> - Motorcycle dealership (San Antonio, City of Biñan, Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/isuzu.jpg" alt="Isuzu Logo" class="linkage-icon">
                                            <div>
                                                <strong>Isuzu</strong> - Commercial and industrial vehicles (City of Biñan, Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/filin.jpg" alt="Filinvest City Logo" class="linkage-icon">
                                            <div>
                                                <strong>Filinvest City</strong> - Integrated development (Alabang, Muntinlupa, Metro Manila)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/ek.jpg" alt="Enchanted Kingdom Logo" class="linkage-icon">
                                            <div>
                                                <strong>Enchanted Kingdom</strong> - Theme park operations (Santa Rosa, Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/coke.jpg" alt="Coca-Cola Logo" class="linkage-icon">
                                            <div>
                                                <strong>Coca-Cola</strong> - Beverage company (City of Sta. Rosa, Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/ford.jpg" alt="Ford Logo" class="linkage-icon">
                                            <div>
                                                <strong>Ford</strong> - Automotive (Zapote Road Alabang and Sta. Rosa city Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/honda.jpg" alt="Honda Logo" class="linkage-icon">
                                            <div>
                                                <strong>Honda</strong> - Automotive dealership (Sta. Rosa City, Laguna and Carmona Exit, Cavite)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/panasonic.jpg" alt="Panasonic Logo" class="linkage-icon">
                                            <div>
                                                <strong>Panasonic</strong> - Electronics (City of Biñan Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/chevy.jpg" alt="Chevrolet Logo" class="linkage-icon">
                                            <div>
                                                <strong>Chevrolet</strong> - Automotive (Carmona Exit, Cavite and City of Sta. Rosa, Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/shopwise.jpg" alt="Shopwise Logo" class="linkage-icon">
                                            <div>
                                                <strong>Shopwise</strong> - Retail and hypermarkets (Festival Mall, Filinvest, Alabang, Muntinlupa City, Manila)
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="linkage-category">
                                    <h3>Government & Public Sector</h3>
                                    <ul class="linkage-list">
                                        <li>
                                            <img src="img/ba/Linkages/bin.jpg" alt="City of Biñan Logo" class="linkage-icon">
                                            <div>
                                                <strong>City of Biñan</strong> - Local government unit (Barangay Zapote, City of Biñan, Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/napocor.jpg" alt="National Power Corporation Logo" class="linkage-icon">
                                            <div>
                                                <strong>National Power Corporation</strong> - Power generation (Carmona, Cavite)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/hdmf.jpg" alt="Pag-Ibig Fund Logo" class="linkage-icon">
                                            <div>
                                                <strong>Pag-Ibig Fund</strong> - Housing and savings program (City of Biñan, Laguna)
                                            </div>
                                        </li>
                                        <li>
                                            <img src="img/ba/Linkages/sss.jpg" alt="Social Security System Logo" class="linkage-icon">
                                            <div>
                                                <strong>Social Security System</strong> - Social security protection (City of Biñan, Laguna)
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </section>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>College:</strong> College of Business & Accountancy</li>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Programs:</strong> 4 Bachelor's Programs</li>
                            <li><strong>Focus:</strong> Business & Accounting Excellence</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Programs Offered</h3>
                        <ul>
                            <li><strong>BSA:</strong> Bachelor of Science in Accountancy</li>
                            <li><strong>BSMA:</strong> Bachelor of Science in Management Accounting</li>
                            <li><strong>BSBA:</strong> Bachelor of Science in Business Administration</li>
                            <li><strong>BS REM:</strong> Bachelor of Science in Real Estate Management (New Program)</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>BSBA Majors</h3>
                        <ul>
                            <li>Marketing Management</li>
                            <li>Business Management</li>
                            <li>Financial Management</li>
                            <li>Human Resource Management</li>
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


