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
            <h1>Business & Accountancy</h1>
            <p>Building future business leaders and financial professionals</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About Business & Accountancy</h2>
                        <p>Our Business & Accountancy programs are designed to develop competent business professionals and certified public accountants who can contribute to the economic growth and development of the country. We provide students with both theoretical knowledge and practical skills necessary for success in the business world.</p>
                        
                        <h2>Undergraduate Programs</h2>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Accountancy (BSA)</h3>
                            <p>A four-year program that prepares students to become Certified Public Accountants (CPAs). The curriculum covers accounting principles, auditing, taxation, and business law.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Financial Accounting</li>
                                <li>Managerial Accounting</li>
                                <li>Cost Accounting</li>
                                <li>Auditing and Assurance</li>
                                <li>Taxation</li>
                                <li>Business Law</li>
                                <li>Management Science</li>
                                <li>Strategic Business Analysis</li>
                                <li>Accounting Information Systems</li>
                                <li>International Accounting</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Business Administration (BSBA)</h3>
                            <p>A four-year program with various majors that prepare students for careers in business management, marketing, finance, and operations.</p>
                            
                            <h4>Available Majors:</h4>
                            <div class="major-grid">
                                <div class="major-card">
                                    <h4>Marketing Management</h4>
                                    <p>Focus on marketing strategies, consumer behavior, and brand management.</p>
                                    <ul>
                                        <li>Consumer Behavior</li>
                                        <li>Marketing Research</li>
                                        <li>Digital Marketing</li>
                                        <li>Brand Management</li>
                                        <li>Sales Management</li>
                                    </ul>
                                </div>
                                
                                <div class="major-card">
                                    <h4>Financial Management</h4>
                                    <p>Specialize in financial planning, investment analysis, and corporate finance.</p>
                                    <ul>
                                        <li>Financial Planning</li>
                                        <li>Investment Analysis</li>
                                        <li>Corporate Finance</li>
                                        <li>Risk Management</li>
                                        <li>International Finance</li>
                                    </ul>
                                </div>
                                
                                <div class="major-card">
                                    <h4>Operations Management</h4>
                                    <p>Focus on production, supply chain, and quality management.</p>
                                    <ul>
                                        <li>Production Management</li>
                                        <li>Supply Chain Management</li>
                                        <li>Quality Management</li>
                                        <li>Project Management</li>
                                        <li>Logistics Management</li>
                                    </ul>
                                </div>
                                
                                <div class="major-card">
                                    <h4>Human Resource Management</h4>
                                    <p>Specialize in personnel management, organizational behavior, and labor relations.</p>
                                    <ul>
                                        <li>Organizational Behavior</li>
                                        <li>Personnel Management</li>
                                        <li>Labor Relations</li>
                                        <li>Training and Development</li>
                                        <li>Compensation Management</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Entrepreneurship (BSE)</h3>
                            <p>A four-year program that develops entrepreneurial skills and prepares students to start and manage their own businesses.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Entrepreneurship Principles</li>
                                <li>Business Plan Development</li>
                                <li>Small Business Management</li>
                                <li>Innovation and Creativity</li>
                                <li>Venture Capital and Financing</li>
                                <li>E-Commerce and Digital Business</li>
                                <li>Social Entrepreneurship</li>
                                <li>Family Business Management</li>
                                <li>International Business</li>
                                <li>Business Ethics and Social Responsibility</li>
                            </ul>
                        </div>
                        
                        <h2>Professional Certifications</h2>
                        <div class="certification-grid">
                            <div class="certification-card">
                                <h4>Certified Public Accountant (CPA)</h4>
                                <p>Professional certification for accounting graduates who pass the CPA licensure examination.</p>
                            </div>
                            
                            <div class="certification-card">
                                <h4>Certified Management Accountant (CMA)</h4>
                                <p>International certification for management accounting professionals.</p>
                            </div>
                            
                            <div class="certification-card">
                                <h4>Certified Financial Planner (CFP)</h4>
                                <p>Professional certification for financial planning specialists.</p>
                            </div>
                            
                            <div class="certification-card">
                                <h4>Project Management Professional (PMP)</h4>
                                <p>Global certification for project management professionals.</p>
                            </div>
                        </div>
                        
                        <h2>Career Opportunities</h2>
                        <div class="career-grid">
                            <div class="career-category">
                                <h4>Accounting & Finance</h4>
                                <ul>
                                    <li>Certified Public Accountant</li>
                                    <li>Financial Analyst</li>
                                    <li>Auditor</li>
                                    <li>Tax Specialist</li>
                                    <li>Budget Analyst</li>
                                    <li>Credit Analyst</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Management & Administration</h4>
                                <ul>
                                    <li>Business Manager</li>
                                    <li>Operations Manager</li>
                                    <li>Human Resource Manager</li>
                                    <li>Project Manager</li>
                                    <li>General Manager</li>
                                    <li>Executive Assistant</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Marketing & Sales</h4>
                                <ul>
                                    <li>Marketing Manager</li>
                                    <li>Sales Manager</li>
                                    <li>Brand Manager</li>
                                    <li>Digital Marketing Specialist</li>
                                    <li>Market Research Analyst</li>
                                    <li>Advertising Executive</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Entrepreneurship</h4>
                                <ul>
                                    <li>Business Owner</li>
                                    <li>Startup Founder</li>
                                    <li>Business Consultant</li>
                                    <li>Franchise Owner</li>
                                    <li>Investment Advisor</li>
                                    <li>Venture Capitalist</li>
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
                            <li><strong>Units:</strong> 160-180 units</li>
                            <li><strong>Programs:</strong> 3</li>
                            <li><strong>Majors Available:</strong> 4</li>
                            <li><strong>Class Size:</strong> 30-35 students</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Faculty</h3>
                        <p>Our faculty includes certified public accountants, business executives, and industry professionals with extensive experience in their respective fields.</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Laboratories</h3>
                        <ul>
                            <li>Accounting Laboratory</li>
                            <li>Computer Laboratory</li>
                            <li>Business Simulation Lab</li>
                            <li>Case Study Room</li>
                            <li>Presentation Room</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Department Head:</strong><br>
                        Dr. Juan Dela Cruz<br>
                        (02) 123-4570<br>
                        business.department@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
