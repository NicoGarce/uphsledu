<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Law/Juris Doctor";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/LAW.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>Law/Juris Doctor</h1>
            <p>Pursuing justice through legal education and practice</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About Law Program</h2>
                        <p>Our Law program provides comprehensive legal education that prepares students for careers in law practice, judiciary, government service, and legal academia. We emphasize critical thinking, legal reasoning, and ethical practice to produce competent and ethical legal professionals.</p>
                        
                        <h2>Graduate Programs</h2>
                        
                        <div class="program-section">
                            <h3>Juris Doctor (JD)</h3>
                            <p>A four-year graduate program that provides comprehensive legal education and prepares students for the practice of law.</p>
                            
                            <h4>Core Subjects (First Year):</h4>
                            <ul>
                                <li>Constitutional Law I & II</li>
                                <li>Civil Law I (Persons and Family Relations)</li>
                                <li>Civil Law II (Property)</li>
                                <li>Criminal Law I & II</li>
                                <li>Legal Research and Writing</li>
                                <li>Legal Ethics</li>
                                <li>Statutory Construction</li>
                                <li>Legal Method</li>
                            </ul>
                            
                            <h4>Core Subjects (Second Year):</h4>
                            <ul>
                                <li>Civil Law III (Obligations and Contracts)</li>
                                <li>Civil Law IV (Sales, Lease, and Credit Transactions)</li>
                                <li>Civil Law V (Succession)</li>
                                <li>Labor Law I & II</li>
                                <li>Administrative Law</li>
                                <li>Evidence</li>
                                <li>Remedial Law I (Civil Procedure)</li>
                                <li>Remedial Law II (Criminal Procedure)</li>
                            </ul>
                            
                            <h4>Core Subjects (Third Year):</h4>
                            <ul>
                                <li>Taxation Law I & II</li>
                                <li>Commercial Law I (Partnership and Corporation)</li>
                                <li>Commercial Law II (Negotiable Instruments)</li>
                                <li>Commercial Law III (Insurance and Transportation)</li>
                                <li>Remedial Law III (Special Proceedings)</li>
                                <li>Remedial Law IV (Evidence)</li>
                                <li>Public International Law</li>
                                <li>Conflict of Laws</li>
                            </ul>
                            
                            <h4>Core Subjects (Fourth Year):</h4>
                            <ul>
                                <li>Legal Ethics and Practical Exercises</li>
                                <li>Civil Law Review</li>
                                <li>Criminal Law Review</li>
                                <li>Labor Law Review</li>
                                <li>Commercial Law Review</li>
                                <li>Taxation Law Review</li>
                                <li>Remedial Law Review</li>
                                <li>Legal Writing and Research</li>
                            </ul>
                        </div>
                        
                        <h2>Specializations</h2>
                        <div class="specialization-grid">
                            <div class="specialization-card">
                                <h4>Corporate Law</h4>
                                <p>Focus on business law and corporate governance.</p>
                                <ul>
                                    <li>Corporate Governance</li>
                                    <li>Securities Regulation</li>
                                    <li>Mergers and Acquisitions</li>
                                    <li>Banking Law</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Criminal Law</h4>
                                <p>Specialization in criminal justice and prosecution.</p>
                                <ul>
                                    <li>Criminal Procedure</li>
                                    <li>Evidence in Criminal Cases</li>
                                    <li>Juvenile Justice</li>
                                    <li>International Criminal Law</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Family Law</h4>
                                <p>Focus on family relations and domestic issues.</p>
                                <ul>
                                    <li>Marriage and Divorce</li>
                                    <li>Child Custody</li>
                                    <li>Adoption</li>
                                    <li>Domestic Violence</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Labor Law</h4>
                                <p>Specialization in employment and labor relations.</p>
                                <ul>
                                    <li>Labor Relations</li>
                                    <li>Employment Law</li>
                                    <li>Social Security Law</li>
                                    <li>Workers' Compensation</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Tax Law</h4>
                                <p>Focus on taxation and fiscal law.</p>
                                <ul>
                                    <li>Income Taxation</li>
                                    <li>Estate Tax</li>
                                    <li>Value Added Tax</li>
                                    <li>Tax Planning</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Environmental Law</h4>
                                <p>Specialization in environmental protection and sustainability.</p>
                                <ul>
                                    <li>Environmental Protection</li>
                                    <li>Natural Resources Law</li>
                                    <li>Climate Change Law</li>
                                    <li>Sustainable Development</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Practical Training</h2>
                        <div class="training-grid">
                            <div class="training-card">
                                <h4>Legal Internship</h4>
                                <p>Hands-on experience in law practice.</p>
                                <ul>
                                    <li>Law Firm Internship</li>
                                    <li>Court Observation</li>
                                    <li>Legal Research</li>
                                    <li>Document Preparation</li>
                                </ul>
                            </div>
                            
                            <div class="training-card">
                                <h4>Moot Court</h4>
                                <p>Simulated court proceedings and advocacy training.</p>
                                <ul>
                                    <li>Oral Arguments</li>
                                    <li>Brief Writing</li>
                                    <li>Court Procedures</li>
                                    <li>Legal Advocacy</li>
                                </ul>
                            </div>
                            
                            <div class="training-card">
                                <h4>Legal Clinic</h4>
                                <p>Pro bono legal services and community outreach.</p>
                                <ul>
                                    <li>Client Counseling</li>
                                    <li>Legal Aid Services</li>
                                    <li>Community Legal Education</li>
                                    <li>Document Assistance</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Career Opportunities</h2>
                        <div class="career-grid">
                            <div class="career-category">
                                <h4>Legal Practice</h4>
                                <ul>
                                    <li>Private Practice Attorney</li>
                                    <li>Corporate Counsel</li>
                                    <li>Public Defender</li>
                                    <li>Prosecutor</li>
                                    <li>Legal Consultant</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Judiciary</h4>
                                <ul>
                                    <li>Judge</li>
                                    <li>Court Administrator</li>
                                    <li>Court Clerk</li>
                                    <li>Legal Researcher</li>
                                    <li>Court Interpreter</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Government Service</h4>
                                <ul>
                                    <li>Government Attorney</li>
                                    <li>Legal Officer</li>
                                    <li>Compliance Officer</li>
                                    <li>Legislative Staff</li>
                                    <li>Regulatory Officer</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Legal Education</h4>
                                <ul>
                                    <li>Law Professor</li>
                                    <li>Legal Researcher</li>
                                    <li>Law School Administrator</li>
                                    <li>Legal Writer</li>
                                    <li>Legal Publisher</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Professional Requirements</h2>
                        <ul>
                            <li>Bachelor's Degree (any field)</li>
                            <li>Passed Law School Entrance Examination</li>
                                    <li>Bar Examination (after graduation)</li>
                                    <li>Mandatory Continuing Legal Education (MCLE)</li>
                                    <li>Professional Indemnity Insurance</li>
                        </ul>
                        
                        <h2>Admission Requirements</h2>
                        <ul>
                            <li>Bachelor's Degree from recognized institution</li>
                            <li>Passed UPHSL Law School Entrance Examination</li>
                            <li>Transcript of Records</li>
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
                            <li><strong>Programs:</strong> 1</li>
                            <li><strong>Specializations:</strong> 6</li>
                            <li><strong>Class Size:</strong> 30-40 students</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Faculty</h3>
                        <p>Our faculty includes practicing lawyers, judges, legal scholars, and government officials with extensive experience in various fields of law.</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Facilities</h3>
                        <ul>
                            <li>Law Library</li>
                            <li>Moot Court Room</li>
                            <li>Legal Clinic</li>
                            <li>Computer Laboratory</li>
                            <li>Legal Research Center</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Bar Review Program</h3>
                        <ul>
                            <li>Comprehensive Review</li>
                            <li>Mock Bar Examinations</li>
                            <li>Review Materials</li>
                            <li>Expert Reviewers</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Dean:</strong><br>
                        Atty. Maria Santos<br>
                        (02) 123-4580<br>
                        law.school@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
