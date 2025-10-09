<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Criminology";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/CRIMINOLOGY.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>Criminology</h1>
            <p>Protecting communities through justice and law enforcement</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About Criminology</h2>
                        <p>Our Criminology program provides comprehensive education in criminal justice, law enforcement, and forensic science. Students learn about crime prevention, investigation techniques, and the administration of justice to prepare for careers in law enforcement and criminal justice.</p>
                        
                        <h2>Undergraduate Programs</h2>
                        
                        <div class="program-section">
                            <h3>Bachelor of Science in Criminology (BS Criminology)</h3>
                            <p>A four-year program that prepares students for careers in law enforcement, criminal investigation, and criminal justice administration.</p>
                            
                            <h4>Core Subjects:</h4>
                            <ul>
                                <li>Introduction to Criminology</li>
                                <li>Criminal Law</li>
                                <li>Criminal Procedure</li>
                                <li>Crime Detection and Investigation</li>
                                <li>Forensic Science</li>
                                <li>Police Organization and Administration</li>
                                <li>Juvenile Delinquency</li>
                                <li>Victimology</li>
                                <li>Correctional Administration</li>
                                <li>Human Rights Education</li>
                            </ul>
                        </div>
                        
                        <h2>Specializations</h2>
                        <div class="specialization-grid">
                            <div class="specialization-card">
                                <h4>Law Enforcement</h4>
                                <p>Focus on police work, investigation, and crime prevention.</p>
                                <ul>
                                    <li>Police Operations</li>
                                    <li>Criminal Investigation</li>
                                    <li>Traffic Management</li>
                                    <li>Community Policing</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Forensic Science</h4>
                                <p>Specialization in crime scene investigation and evidence analysis.</p>
                                <ul>
                                    <li>Crime Scene Investigation</li>
                                    <li>Fingerprint Analysis</li>
                                    <li>Ballistics</li>
                                    <li>DNA Analysis</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Corrections</h4>
                                <p>Focus on correctional administration and rehabilitation.</p>
                                <ul>
                                    <li>Prison Management</li>
                                    <li>Rehabilitation Programs</li>
                                    <li>Parole and Probation</li>
                                    <li>Correctional Counseling</li>
                                </ul>
                            </div>
                            
                            <div class="specialization-card">
                                <h4>Security Management</h4>
                                <p>Private security and corporate security management.</p>
                                <ul>
                                    <li>Corporate Security</li>
                                    <li>Risk Assessment</li>
                                    <li>Security Technology</li>
                                    <li>Emergency Response</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Practical Training</h2>
                        <div class="training-grid">
                            <div class="training-card">
                                <h4>Field Training</h4>
                                <p>Hands-on experience with law enforcement agencies.</p>
                                <ul>
                                    <li>Police Station Internship</li>
                                    <li>Court Observation</li>
                                    <li>Correctional Facility Visit</li>
                                    <li>Crime Scene Simulation</li>
                                </ul>
                            </div>
                            
                            <div class="training-card">
                                <h4>Laboratory Work</h4>
                                <p>Forensic science laboratory training and practice.</p>
                                <ul>
                                    <li>Fingerprint Analysis Lab</li>
                                    <li>Ballistics Laboratory</li>
                                    <li>Chemistry Laboratory</li>
                                    <li>Computer Forensics Lab</li>
                                </ul>
                            </div>
                            
                            <div class="training-card">
                                <h4>Physical Training</h4>
                                <p>Physical fitness and self-defense training.</p>
                                <ul>
                                    <li>Physical Fitness Program</li>
                                    <li>Self-Defense Training</li>
                                    <li>Arnis (Filipino Martial Arts)</li>
                                    <li>Firearms Training</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Career Opportunities</h2>
                        <div class="career-grid">
                            <div class="career-category">
                                <h4>Law Enforcement</h4>
                                <ul>
                                    <li>Police Officer</li>
                                    <li>Detective</li>
                                    <li>Special Agent</li>
                                    <li>Intelligence Officer</li>
                                    <li>Traffic Enforcer</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Forensic Science</h4>
                                <ul>
                                    <li>Crime Scene Investigator</li>
                                    <li>Forensic Analyst</li>
                                    <li>Fingerprint Expert</li>
                                    <li>Ballistics Expert</li>
                                    <li>Digital Forensics Specialist</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Corrections</h4>
                                <ul>
                                    <li>Correctional Officer</li>
                                    <li>Probation Officer</li>
                                    <li>Parole Officer</li>
                                    <li>Rehabilitation Counselor</li>
                                    <li>Prison Administrator</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Private Sector</h4>
                                <ul>
                                    <li>Security Manager</li>
                                    <li>Loss Prevention Specialist</li>
                                    <li>Corporate Investigator</li>
                                    <li>Risk Assessment Analyst</li>
                                    <li>Security Consultant</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Professional Certifications</h2>
                        <ul>
                            <li>Criminology Licensure Examination (CLE)</li>
                            <li>Security Guard License</li>
                            <li>Firearms License</li>
                            <li>Driving License</li>
                            <li>First Aid Certification</li>
                        </ul>
                        
                        <h2>Admission Requirements</h2>
                        <ul>
                            <li>High School Diploma or equivalent</li>
                            <li>Passed UPHSL Entrance Examination</li>
                            <li>Report Card (Form 138)</li>
                            <li>Certificate of Good Moral Character</li>
                            <li>Birth Certificate (PSA)</li>
                            <li>2x2 ID Photos</li>
                            <li>Medical Certificate</li>
                            <li>NBI Clearance</li>
                            <li>Police Clearance</li>
                        </ul>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Specializations:</strong> 4</li>
                            <li><strong>Field Training:</strong> Required</li>
                            <li><strong>Class Size:</strong> 30-35 students</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Faculty</h3>
                        <p>Our faculty includes licensed criminologists, retired law enforcement officers, and forensic science experts with extensive field experience.</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Laboratories</h3>
                        <ul>
                            <li>Forensic Science Laboratory</li>
                            <li>Fingerprint Analysis Lab</li>
                            <li>Ballistics Laboratory</li>
                            <li>Computer Forensics Lab</li>
                            <li>Crime Scene Simulation Room</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Partnerships</h3>
                        <ul>
                            <li>Philippine National Police</li>
                            <li>National Bureau of Investigation</li>
                            <li>Bureau of Jail Management</li>
                            <li>Private Security Agencies</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Department Head:</strong><br>
                        Dr. Pedro Santos<br>
                        (02) 123-4575<br>
                        criminology.department@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
