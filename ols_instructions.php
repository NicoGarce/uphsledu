<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Set page title
$page_title = "Online Services Instructions";

// Set base path for assets (empty for root directory)
$base_path = '';

// Include header
include 'includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('assets/images/UPHSL Facade.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>Online Services Instructions</h1>
            <p>Step-by-step guide to access UPHSL online services</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>How to Access UPHSL Online Services</h2>
                        <p>This guide will help you access various online services provided by the University of Perpetual Help System Laguna. Follow the step-by-step instructions for each service.</p>
                        
                        <!-- Quick Navigation -->
                        <div class="quick-nav">
                            <h3>Quick Navigation</h3>
                            <div class="nav-links">
                                <a href="#gti" class="nav-link">GTI Online Grades</a>
                                <a href="#moodle" class="nav-link">Moodle LMS</a>
                                <a href="#google" class="nav-link">Google Account</a>
                                <a href="#microsoft" class="nav-link">Microsoft 365</a>
                                <a href="#troubleshooting" class="nav-link">Troubleshooting</a>
                            </div>
                        </div>
                        
                        <!-- GTI Instructions -->
                        <div class="instruction-section" id="gti">
                            <h3>1. GTI (Grade Tracking Information) Online Grades</h3>
                            <div class="instruction-card">
                                <h4>What is GTI?</h4>
                                <p>GTI is the online portal where students and parents can view grades, attendance records, and academic information.</p>
                                
                                <h4>How to Access:</h4>
                                <ol class="instruction-steps">
                                    <li>Click on "GTI Online Grades" from the Online Services menu</li>
                                    <li>You will be redirected to the GTI portal</li>
                                    <li>Enter your student ID number as username</li>
                                    <li>Enter your default password (usually your birthdate in MMDDYYYY format)</li>
                                    <li>Click "Login" to access your account</li>
                                </ol>
                                
                                <h4>First Time Login:</h4>
                                <ul>
                                    <li>Default username: Your student ID number</li>
                                    <li>Default password: Your birthdate (MMDDYYYY format)</li>
                                    <li>Example: If born on March 15, 2000, password would be 03152000</li>
                                    <li>Change your password after first login for security</li>
                                </ul>
                                
                                <h4>What You Can Do:</h4>
                                <ul>
                                    <li>View current grades and GPA</li>
                                    <li>Check attendance records</li>
                                    <li>View class schedules</li>
                                    <li>Access academic records</li>
                                    <li>Download grade reports</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Moodle Instructions -->
                        <div class="instruction-section" id="moodle">
                            <h3>2. Moodle Learning Management System</h3>
                            <div class="instruction-card">
                                <h4>What is Moodle?</h4>
                                <p>Moodle is UPHSL's Learning Management System where students access online courses, assignments, quizzes, and course materials.</p>
                                
                                <h4>How to Access:</h4>
                                <ol class="instruction-steps">
                                    <li>Click on "Moodle" from the Online Services menu</li>
                                    <li>You will be redirected to the Moodle login page</li>
                                    <li>Enter your student email address as username</li>
                                    <li>Enter your Moodle password</li>
                                    <li>Click "Log in" to access your courses</li>
                                </ol>
                                
                                <h4>Login Credentials:</h4>
                                <ul>
                                    <li>Username: Your UPHSL email address (e.g., student@uphsl.edu.ph)</li>
                                    <li>Password: Your assigned Moodle password (provided by IT department)</li>
                                    <li>If you don't have credentials, contact the IT department</li>
                                </ul>
                                
                                <h4>What You Can Do:</h4>
                                <ul>
                                    <li>Access online course materials</li>
                                    <li>Submit assignments online</li>
                                    <li>Take online quizzes and exams</li>
                                    <li>Participate in discussion forums</li>
                                    <li>View grades and feedback</li>
                                    <li>Download course resources</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Google Account Instructions -->
                        <div class="instruction-section" id="google">
                            <h3>3. Google Account (Gmail & Google Workspace)</h3>
                            <div class="instruction-card">
                                <h4>What is Google Account?</h4>
                                <p>UPHSL provides Google Workspace accounts for students, including Gmail, Google Drive, Google Docs, and other Google services.</p>
                                
                                <h4>How to Access:</h4>
                                <ol class="instruction-steps">
                                    <li>Click on "Google Account" from the Online Services menu</li>
                                    <li>You will be redirected to Google sign-in page</li>
                                    <li>Enter your UPHSL email address</li>
                                    <li>Enter your Google account password</li>
                                    <li>Complete two-factor authentication if enabled</li>
                                    <li>Click "Next" to access your Google account</li>
                                </ol>
                                
                                <h4>Login Credentials:</h4>
                                <ul>
                                    <li>Email: Your UPHSL email address (e.g., student@uphsl.edu.ph)</li>
                                    <li>Password: Your Google account password (set during account creation)</li>
                                    <li>If you don't have an account, contact the IT department</li>
                                </ul>
                                
                                <h4>Available Services:</h4>
                                <ul>
                                    <li>Gmail - Email communication</li>
                                    <li>Google Drive - File storage and sharing</li>
                                    <li>Google Docs - Document creation and collaboration</li>
                                    <li>Google Sheets - Spreadsheet creation</li>
                                    <li>Google Slides - Presentation creation</li>
                                    <li>Google Meet - Video conferencing</li>
                                    <li>Google Calendar - Schedule management</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Microsoft 365 Instructions -->
                        <div class="instruction-section" id="microsoft">
                            <h3>4. Microsoft 365 (Office 365)</h3>
                            <div class="instruction-card">
                                <h4>What is Microsoft 365?</h4>
                                <p>Microsoft 365 provides access to Office applications like Word, Excel, PowerPoint, and OneDrive for UPHSL students.</p>
                                
                                <h4>How to Access:</h4>
                                <ol class="instruction-steps">
                                    <li>Click on "Microsoft 365" from the Online Services menu</li>
                                    <li>You will be redirected to Microsoft login page</li>
                                    <li>Enter your UPHSL email address</li>
                                    <li>Enter your Microsoft 365 password</li>
                                    <li>Complete authentication if prompted</li>
                                    <li>Click "Sign in" to access Microsoft 365</li>
                                </ol>
                                
                                <h4>Login Credentials:</h4>
                                <ul>
                                    <li>Email: Your UPHSL email address (e.g., student@uphsl.edu.ph)</li>
                                    <li>Password: Your Microsoft 365 password (may be same as Google account)</li>
                                    <li>If you don't have access, contact the IT department</li>
                                </ul>
                                
                                <h4>Available Applications:</h4>
                                <ul>
                                    <li>Microsoft Word - Document processing</li>
                                    <li>Microsoft Excel - Spreadsheet management</li>
                                    <li>Microsoft PowerPoint - Presentation creation</li>
                                    <li>Microsoft OneNote - Note-taking</li>
                                    <li>OneDrive - Cloud storage</li>
                                    <li>Microsoft Teams - Collaboration platform</li>
                                    <li>Outlook - Email and calendar</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Troubleshooting Section -->
                        <div class="instruction-section" id="troubleshooting">
                            <h3>5. Troubleshooting Common Issues</h3>
                            <div class="instruction-card">
                                <h4>Forgot Password:</h4>
                                <ul>
                                    <li><strong>GTI:</strong> Contact the Registrar's Office or your academic advisor</li>
                                    <li><strong>Moodle:</strong> Use "Lost password?" link or contact IT department</li>
                                    <li><strong>Google Account:</strong> Use Google's password recovery process</li>
                                    <li><strong>Microsoft 365:</strong> Use Microsoft's password reset or contact IT department</li>
                                </ul>
                                
                                <h4>Account Not Working:</h4>
                                <ul>
                                    <li>Verify you're using the correct email format</li>
                                    <li>Check if your account is active (contact IT if unsure)</li>
                                    <li>Clear browser cache and cookies</li>
                                    <li>Try using a different browser</li>
                                    <li>Check your internet connection</li>
                                </ul>
                                
                                <h4>Need Help:</h4>
                                <ul>
                                    <li>Contact IT Department: (02) 123-4567</li>
                                    <li>Email: it.support@uphsl.edu.ph</li>
                                    <li>Visit IT Office: Ground Floor, Main Building</li>
                                    <li>Office Hours: Monday-Friday, 8:00 AM - 5:00 PM</li>
                                </ul>
                            </div>
                        </div>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Quick Access</h3>
                        <ul class="quick-links">
                            <li><a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank">GTI Online Grades</a></li>
                            <li><a href="https://uphslms.com/blended/login/index.php" target="_blank">Moodle LMS</a></li>
                            <li><a href="https://accounts.google.com/signin" target="_blank">Google Account</a></li>
                            <li><a href="https://login.microsoftonline.com/" target="_blank">Microsoft 365</a></li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Important Notes</h3>
                        <ul>
                            <li>Keep your login credentials secure</li>
                            <li>Change default passwords immediately</li>
                            <li>Log out after each session</li>
                            <li>Report suspicious activity to IT</li>
                            <li>Use strong, unique passwords</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Support</h3>
                        <p><strong>IT Department:</strong><br>
                        Phone: (02) 123-4567<br>
                        Email: it.support@uphsl.edu.ph<br>
                        Location: Ground Floor, Main Building<br>
                        Hours: Mon-Fri, 8:00 AM - 5:00 PM</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include 'includes/footer.php';
?>
