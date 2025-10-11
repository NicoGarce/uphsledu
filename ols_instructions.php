<?php
session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Set page title
$page_title = "Online Services Instructions";

// Set base path for assets (empty for root directory)
$base_path = '';

// Include header
include 'app/includes/header.php';
?>


    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>How to Access UPHSL Online Services</h2>
                        <p>This guide will help you access various online services provided by the University of Perpetual Help System Laguna. Select a platform below to view detailed instructions.</p>
                        
                        <!-- Tab Navigation -->
                        <div class="tabs-container">
                            <div class="tab-navigation">
                                <button class="tab-button active" data-tab="gti">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span>GTI</span>
                                </button>
                                <button class="tab-button" data-tab="moodle">
                                    <i class="fas fa-book"></i>
                                    <span>Moodle</span>
                                </button>
                                <button class="tab-button" data-tab="google">
                                    <i class="fab fa-google"></i>
                                    <span>Google</span>
                                </button>
                                <button class="tab-button" data-tab="microsoft">
                                    <i class="fab fa-microsoft"></i>
                                    <span>Microsoft 365</span>
                                </button>
                                <button class="tab-button" data-tab="troubleshooting">
                                    <i class="fas fa-tools"></i>
                                    <span>Troubleshooting</span>
                                </button>
                            </div>
                        
                            <!-- Tab Content -->
                            <div class="tab-content">
                                <!-- GTI Tab -->
                                <div class="tab-pane active" id="gti-tab">
                                    <div class="tab-header">
                                        <h3><i class="fas fa-graduation-cap"></i> GTI Online Grades</h3>
                                        <p class="tab-subtitle">Access your academic records, grades, and attendance information</p>
                                    </div>
                                    <div class="instruction-card">
                                        <h4>What is GTI?</h4>
                                        <p>GTI (School Automate Account) is the online portal where students and parents can view grades, attendance records, and academic information. It integrates technology for effective school management.</p>
                                        
                                        <h4>Access GTI:</h4>
                                        <div class="gti-buttons">
                                            <a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="gti-btn school-btn">
                                                <i class="fas fa-graduation-cap"></i>
                                                <span>School Portal</span>
                                                <small>gti-binan.uphsl.edu.ph:8339</small>
                                            </a>
                                            <a href="http://gti-allied.uphsl.edu.ph:8340/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="gti-btn medical-btn">
                                                <i class="fas fa-user-md"></i>
                                                <span>Medical University</span>
                                                <small>gti-allied.uphsl.edu.ph:8340</small>
                                            </a>
                                        </div>
                                        
                                        <h4>First Time Login (Default Account):</h4>
                                        <div class="login-credentials">
                                            <div class="credential-item">
                                                <strong>Username:</strong> Your Student Number
                                            </div>
                                            <div class="credential-item">
                                                <strong>Password:</strong> Your Student Number
                                            </div>
                                        </div>
                                        
                                        <h4>Example:</h4>
                                        <div class="example-box">
                                            <p><strong>Username:</strong> 20-1234-567</p>
                                            <p><strong>Password:</strong> 20-1234-567</p>
                                        </div>
                                        
                                        <h4>How to Access GTI:</h4>
                                        <div class="access-methods">
                                            <div class="method-section">
                                                <h5><i class="fas fa-mouse-pointer"></i> Method 1: Via Navigation Menu</h5>
                                                <ol class="instruction-steps">
                                                    <li>Click on "GTI Online Grades" from the Online Services menu</li>
                                                    <li>You will be redirected to the appropriate GTI portal</li>
                                                    <li>Enter your student number as both username and password</li>
                                                    <li>Click "Login" to access your account</li>
                                                </ol>
                                            </div>
                                            
                                            <div class="method-section">
                                                <h5><i class="fas fa-hand-pointer"></i> Method 2: Via Direct Buttons Above</h5>
                                                <ol class="instruction-steps">
                                                    <li>Click on either "School Portal" or "Medical University" button above</li>
                                                    <li>You will be taken directly to the GTI login page</li>
                                                    <li>Enter your student number as both username and password</li>
                                                    <li>Click "Login" to access your account</li>
                                                </ol>
                                            </div>
                                            
                                            <div class="method-section">
                                                <h5><i class="fas fa-link"></i> Method 3: Via Direct Links</h5>
                                                <div class="direct-links">
                                                    <p><strong>School Portal:</strong> <a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank">gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm</a></p>
                                                    <p><strong>Medical University:</strong> <a href="http://gti-allied.uphsl.edu.ph:8340/PARENTS_STUDENTS/parents_student_index.htm" target="_blank">gti-allied.uphsl.edu.ph:8340/PARENTS_STUDENTS/parents_student_index.htm</a></p>
                                                </div>
                                            </div>
                                            
                                            <div class="method-section">
                                                <h5><i class="fas fa-qrcode"></i> Method 4: Via QR Code Scan</h5>
                                                <div class="qr-section">
                                                    <div class="qr-codes">
                                                        <div class="qr-item">
                                                            <div class="qr-image">
                                                                <img src="assets/images/GTI/QR GTI.jpg" alt="GTI QR Code" class="qr-code">
                                                            </div>
                                                            <div class="qr-info">
                                                                <h6>School Portal QR Code</h6>
                                                                <p>Scan with your mobile device camera to access GTI School Portal</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="qr-instructions">
                                                        <h6><i class="fas fa-mobile-alt"></i> How to Scan:</h6>
                                                        <ol>
                                                            <li>Open your mobile device's camera app</li>
                                                            <li>Point the camera at the QR code above</li>
                                                            <li>Tap the notification that appears to open the GTI portal</li>
                                                            <li>Enter your login credentials when prompted</li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <h4>Creating Your Account (Step 2):</h4>
                                        <div class="account-creation">
                                            <div class="creation-section">
                                                <h5><i class="fas fa-user-plus"></i> Username Format:</h5>
                                                <div class="username-options">
                                                    <div class="username-option">
                                                        <div class="option-label">
                                                            <span class="option-button school-option">School</span>
                                                            <span class="option-format">UPHB + Desired Username</span>
                                                        </div>
                                                    </div>
                                                    <div class="username-option">
                                                        <div class="option-label">
                                                            <span class="option-button medical-option">Medical University</span>
                                                            <span class="option-format">UPHMU + Desired Username</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="creation-section">
                                                <h5><i class="fas fa-key"></i> Password:</h5>
                                                <div class="password-info">
                                                    <p>Use your desired password (choose a strong, secure password)</p>
                                                </div>
                                            </div>
                                            
                                            <div class="creation-section">
                                                <h5><i class="fas fa-lightbulb"></i> Example:</h5>
                                                <div class="example-creation">
                                                    <div class="example-item">
                                                        <strong>Username:</strong> UPHB-juan12
                                                    </div>
                                                    <div class="example-item">
                                                        <strong>Password:</strong> MabaitAko13
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="credentials-reminder">
                                                <h5><i class="fas fa-exclamation-triangle"></i> Important Reminder:</h5>
                                                <p><strong>Write down your new username and password in a safe place!</strong> You will need these credentials every time you log into GTI. We recommend saving them in a secure password manager or writing them down and keeping them in a safe location.</p>
                                                <ul>
                                                    <li>Your username format: <code>UPHB-[your-choice]</code> or <code>UPHMU-[your-choice]</code></li>
                                                    <li>Your password: The secure password you created</li>
                                                    <li>Keep these credentials private and don't share them with others</li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <div class="security-note">
                                            <h5><i class="fas fa-shield-alt"></i> Important Security Note:</h5>
                                            <p>Change your password after first login for security. Use a strong, unique password that you haven't used elsewhere.</p>
                                        </div>
                                        
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
                        
                                <!-- Moodle Tab -->
                                <div class="tab-pane" id="moodle-tab">
                                    <div class="tab-header">
                                        <h3><i class="fas fa-book"></i> Moodle Learning Management System</h3>
                                        <p class="tab-subtitle">Access your online courses, assignments, and learning materials</p>
                                    </div>
                                    <div class="instruction-card">
                                        <h4>What is Moodle?</h4>
                                        <p>Moodle is UPHSL's e-Learning platform where students access online courses, assignments, quizzes, and course materials.</p>
                                        
                                        <h4>Access Methods:</h4>
                                        <div class="access-methods">
                                            <div class="method-section">
                                                <h5><i class="fas fa-mouse-pointer"></i> Method 1: Via Navigation Menu</h5>
                                                <ol class="instruction-steps">
                                                    <li>Click on "Moodle" from the Online Services menu</li>
                                                    <li>You will be redirected to the Moodle login page</li>
                                                    <li>Enter your username and password (see credentials below)</li>
                                                    <li>Click "Log in" to access your courses</li>
                                                </ol>
                                            </div>
                                            
                                            <div class="method-section">
                                                <h5><i class="fas fa-qrcode"></i> Method 2: Via QR Code Scan</h5>
                                                <div class="qr-section">
                                                    <div class="qr-codes">
                                                        <div class="qr-item">
                                                            <div class="qr-image">
                                                                <img src="assets/images/moodle/moodle qr.jpg" alt="Moodle QR Code" class="qr-code">
                                                            </div>
                                                            <div class="qr-info">
                                                                <h6>Moodle QR Code</h6>
                                                                <p>Scan with your mobile device camera to access Moodle</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="qr-instructions">
                                                        <h6><i class="fas fa-mobile-alt"></i> How to Scan:</h6>
                                                        <ol>
                                                            <li>Open your mobile device's camera app</li>
                                                            <li>Point the camera at the QR code above</li>
                                                            <li>Tap the notification that appears to open Moodle</li>
                                                            <li>Enter your login credentials when prompted</li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="method-section">
                                                <h5><i class="fas fa-link"></i> Method 3: Via Direct Link</h5>
                                                <div class="direct-links">
                                                    <p><strong>Moodle LMS:</strong> <a href="https://uphslms.com/" target="_blank">https://uphslms.com/</a></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <h4>Default Login Credentials:</h4>
                                        <div class="login-credentials">
                                            <div class="credential-item">
                                                <strong>Username:</strong> c + Your Student Number
                                            </div>
                                            <div class="credential-item">
                                                <strong>Password:</strong> #Uphsl123
                                            </div>
                                        </div>
                                        
                                        <h4>Example:</h4>
                                        <div class="example-box">
                                            <p><strong>Username:</strong> c21-1234-456</p>
                                            <p><strong>Password:</strong> #Uphsl123</p>
                                        </div>
                                        
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
                        
                                <!-- Google Tab -->
                                <div class="tab-pane" id="google-tab">
                                    <div class="tab-header">
                                        <h3><i class="fab fa-google"></i> Google Account (Gmail & Google Workspace)</h3>
                                        <p class="tab-subtitle">Access Gmail, Google Drive, and other Google services</p>
                                    </div>
                                    <div class="instruction-card">
                                        <h4>What is Google Account?</h4>
                                        <p>UPHSL provides Google Workspace accounts for students, including Gmail, Google Meet, Google Drive, and other Google services essential for academic collaboration.</p>
                                        
                                        <h4>How to Access:</h4>
                                        <ol class="instruction-steps">
                                            <li>Click on "Google Account" from the Online Services menu</li>
                                            <li>You will be redirected to Google sign-in page</li>
                                            <li>Enter your UPHSL email address (see formats below)</li>
                                            <li>Enter your password (see options below)</li>
                                            <li>Click "Next" to access your Google account</li>
                                        </ol>
                                        
                                        <h4>Email Address Format:</h4>
                                        <div class="email-formats">
                                            <div class="email-option">
                                                <div class="email-label">
                                                    <span class="email-type">For Basic Ed, College and Graduate School</span>
                                                    <span class="email-format">c + Student Number@uphsl.edu.ph</span>
                                                </div>
                                            </div>
                                            <div class="email-option">
                                                <div class="email-label">
                                                    <span class="email-type">For Medical University</span>
                                                    <span class="email-format">a + Student Number@uphsl.edu.ph</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <h4>Default Password:</h4>
                                        <div class="password-options">
                                            <div class="password-option">
                                                <span class="password-format">#Uphsl123</span>
                                                <span class="password-label">Default Password</span>
                                            </div>
                                        </div>
                                        
                                        <h4>Example:</h4>
                                        <div class="example-box">
                                            <p><strong>Email:</strong> c21-1234-567@uphsl.edu.ph</p>
                                            <p><strong>Password:</strong> #Uphsl123</p>
                                        </div>
                                        
                                        <div class="password-change-notice">
                                            <h5><i class="fas fa-key"></i> Important Password Information:</h5>
                                            <p><strong>When you first log in with the default password, you will be required to create a new password.</strong></p>
                                            <ul>
                                                <li>Use the default password <code>#Uphsl123</code> to log in for the first time</li>
                                                <li>You will be prompted to create a new, secure password</li>
                                                <li><strong>Remember your new password!</strong> You will need it for all future logins</li>
                                                <li>Write down your new password in a safe place or use a password manager</li>
                                                <li>Do not share your password with anyone</li>
                                            </ul>
                                        </div>
                                        
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
                        
                                <!-- Microsoft 365 Tab -->
                                <div class="tab-pane" id="microsoft-tab">
                                    <div class="tab-header">
                                        <h3><i class="fab fa-microsoft"></i> Microsoft 365 (Office 365)</h3>
                                        <p class="tab-subtitle">Access Office applications and cloud services</p>
                                    </div>
                                    <div class="instruction-card">
                                        <h4>What is Microsoft 365?</h4>
                                        <p>Microsoft 365 provides access to Office applications like Word, Excel, PowerPoint, OneDrive, and other Microsoft services essential for academic productivity.</p>
                                        
                                        <h4>How to Access:</h4>
                                        <ol class="instruction-steps">
                                            <li>Click on "Microsoft 365" from the Online Services menu</li>
                                            <li>You will be redirected to Microsoft login page</li>
                                            <li>Enter your UPHSL email address (see formats below)</li>
                                            <li>Enter your password (see options below)</li>
                                            <li>Complete authentication if prompted</li>
                                            <li>Click "Sign in" to access Microsoft 365</li>
                                        </ol>
                                        
                                        <h4>Email Address Format:</h4>
                                        <div class="email-formats">
                                            <div class="email-option">
                                                <div class="email-label">
                                                    <span class="email-type">For Basic Ed, College and Graduate School</span>
                                                    <span class="email-format">c + Student Number@uphsl.edu.ph</span>
                                                </div>
                                            </div>
                                            <div class="email-option">
                                                <div class="email-label">
                                                    <span class="email-type">For Medical University</span>
                                                    <span class="email-format">a + Student Number@uphsl.edu.ph</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <h4>Default Password:</h4>
                                        <div class="password-options">
                                            <div class="password-option">
                                                <span class="password-format">#Uphsl123</span>
                                                <span class="password-label">Default Password</span>
                                            </div>
                                        </div>
                                        
                                        <h4>Example:</h4>
                                        <div class="example-box">
                                            <p><strong>Email:</strong> c21-1234-567@uphsl.edu.ph</p>
                                            <p><strong>Password:</strong> #Uphsl123</p>
                                        </div>
                                        
                                        <div class="password-change-notice">
                                            <h5><i class="fas fa-key"></i> Important Password Information:</h5>
                                            <p><strong>When you first log in with the default password, you will be required to create a new password.</strong></p>
                                            <ul>
                                                <li>Use the default password <code>#Uphsl123</code> to log in for the first time</li>
                                                <li>You will be prompted to create a new, secure password</li>
                                                <li><strong>Remember your new password!</strong> You will need it for all future logins</li>
                                                <li>Write down your new password in a safe place or use a password manager</li>
                                                <li>Do not share your password with anyone</li>
                                            </ul>
                                        </div>
                                        
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
                        
                                <!-- Troubleshooting Tab -->
                                <div class="tab-pane" id="troubleshooting-tab">
                                    <div class="tab-header">
                                        <h3><i class="fas fa-tools"></i> Troubleshooting Common Issues</h3>
                                        <p class="tab-subtitle">Get help with login problems and technical issues</p>
                                    </div>
                                    <div class="instruction-card">
                                        <h4>Common Login Issues:</h4>
                                        <div class="troubleshooting-section">
                                            <div class="issue-category">
                                                <h5><i class="fas fa-exclamation-triangle"></i> Wrong Username/Email Format</h5>
                                                <ul>
                                                    <li><strong>GTI:</strong> Use your Student Number only (e.g., 21-1234-567)</li>
                                                    <li><strong>Moodle:</strong> Use c + Student Number (e.g., c21-1234-567)</li>
                                                    <li><strong>Google Account:</strong> Use c + Student Number@uphsl.edu.ph (e.g., c21-1234-567@uphsl.edu.ph)</li>
                                                    <li><strong>Microsoft 365:</strong> Use c + Student Number@uphsl.edu.ph (e.g., c21-1234-567@uphsl.edu.ph)</li>
                                                </ul>
                                            </div>
                                            
                                            <div class="issue-category">
                                                <h5><i class="fas fa-key"></i> Password Problems</h5>
                                                <ul>
                                                    <li><strong>First Time Login:</strong> Use #Uphsl123 as default password</li>
                                                    <li><strong>After Password Change:</strong> Use the new password you created</li>
                                                    <li><strong>Forgot New Password:</strong> Contact ITS for password reset</li>
                                                    <li><strong>Case Sensitive:</strong> Make sure Caps Lock is off</li>
                                                </ul>
                                            </div>
                                            
                                            <div class="issue-category">
                                                <h5><i class="fas fa-globe"></i> Connection Issues</h5>
                                                <ul>
                                                    <li>Check your internet connection</li>
                                                    <li>Try refreshing the page</li>
                                                    <li>Clear browser cache and cookies</li>
                                                    <li>Try using a different browser</li>
                                                    <li>Disable browser extensions temporarily</li>
                                                </ul>
                                            </div>
                                            
                                            <div class="issue-category">
                                                <h5><i class="fas fa-mobile-alt"></i> QR Code Scanning Issues</h5>
                                                <ul>
                                                    <li>Ensure good lighting when scanning</li>
                                                    <li>Hold your device steady</li>
                                                    <li>Make sure the QR code is not damaged</li>
                                                    <li>Try using a different QR code scanner app</li>
                                                    <li>If QR code doesn't work, use the direct links instead</li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <h4>Account Access Problems:</h4>
                                        <div class="access-issues">
                                            <div class="access-issue">
                                                <strong>Account Not Found:</strong> Verify you're using the correct email format for your program
                                            </div>
                                            <div class="access-issue">
                                                <strong>Account Locked:</strong> Contact ITS - your account may be temporarily locked
                                            </div>
                                            <div class="access-issue">
                                                <strong>Wrong Portal:</strong> Make sure you're using the correct link (School vs Medical University)
                                            </div>
                                            <div class="access-issue">
                                                <strong>Password Expired:</strong> You may need to change your password again
                                            </div>
                                        </div>
                                        
                                        <h4>Need Help?</h4>
                                        <div class="contact-info">
                                            <div class="contact-item">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <div class="contact-details">
                                                    <strong>Visit ITS Office</strong>
                                                    <p>ITS Jonelta<br>2nd Floor, Main Building</p>
                                                </div>
                                            </div>
                                            <div class="contact-item">
                                                <i class="fas fa-clock"></i>
                                                <div class="contact-details">
                                                    <strong>Office Hours</strong>
                                                    <p>Monday - Friday<br>8:00 AM - 5:00 PM</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                        <h3>Need Help?</h3>
                        <div class="sidebar-contact">
                            <div class="sidebar-contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="sidebar-contact-details">
                                    <strong>Visit ITS Office</strong>
                                    <p>ITS Jonelta<br>2nd Floor, Main Building</p>
                                </div>
                            </div>
                            <div class="sidebar-contact-item">
                                <i class="fas fa-clock"></i>
                                <div class="sidebar-contact-details">
                                    <strong>Office Hours</strong>
                                    <p>Monday - Friday<br>8:00 AM - 5:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <!-- Tab Functionality JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    // Remove active class from all buttons and panes
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));
                    
                    // Add active class to clicked button and corresponding pane
                    this.classList.add('active');
                    document.getElementById(targetTab + '-tab').classList.add('active');
                });
            });
        });
    </script>

<?php
// Include footer
include 'app/includes/footer.php';
?>
