<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Set page title
$page_title = "Graduate School";

// Set base path for assets
$base_path = '../';

// Include header
include '../includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('img/banner/GRADUATE SCHOOL.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>Graduate School</h1>
            <p>Advancing knowledge through advanced studies and research</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>About Graduate School</h2>
                        <p>Our Graduate School provides advanced academic programs designed to develop specialized knowledge, research skills, and leadership capabilities. We offer master's and doctoral programs across various disciplines to prepare students for advanced careers in academia, research, and professional practice.</p>
                        
                        <h2>Master's Programs</h2>
                        
                        <div class="program-section">
                            <h3>Master of Arts in Education (MA Ed)</h3>
                            <p>A two-year program that enhances teaching skills and educational leadership capabilities.</p>
                            
                            <h4>Specializations:</h4>
                            <ul>
                                <li>Educational Management</li>
                                <li>Curriculum and Instruction</li>
                                <li>Guidance and Counseling</li>
                                <li>Special Education</li>
                                <li>Educational Technology</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Master of Business Administration (MBA)</h3>
                            <p>A two-year program that develops advanced business management and leadership skills.</p>
                            
                            <h4>Specializations:</h4>
                            <ul>
                                <li>General Management</li>
                                <li>Human Resource Management</li>
                                <li>Marketing Management</li>
                                <li>Financial Management</li>
                                <li>Operations Management</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Master of Science in Computer Science (MS CS)</h3>
                            <p>A two-year program that provides advanced knowledge in computer science and technology.</p>
                            
                            <h4>Specializations:</h4>
                            <ul>
                                <li>Software Engineering</li>
                                <li>Data Science</li>
                                <li>Artificial Intelligence</li>
                                <li>Cybersecurity</li>
                                <li>Information Systems</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Master of Science in Psychology (MS Psychology)</h3>
                            <p>A two-year program that provides advanced training in psychological theory and practice.</p>
                            
                            <h4>Specializations:</h4>
                            <ul>
                                <li>Clinical Psychology</li>
                                <li>Industrial Psychology</li>
                                <li>Educational Psychology</li>
                                <li>Social Psychology</li>
                                <li>Counseling Psychology</li>
                            </ul>
                        </div>
                        
                        <h2>Doctoral Programs</h2>
                        
                        <div class="program-section">
                            <h3>Doctor of Philosophy in Education (PhD Ed)</h3>
                            <p>A three-year program that prepares students for advanced research and academic leadership in education.</p>
                            
                            <h4>Research Areas:</h4>
                            <ul>
                                <li>Educational Policy and Administration</li>
                                <li>Curriculum Development and Evaluation</li>
                                <li>Educational Psychology</li>
                                <li>Comparative Education</li>
                                <li>Educational Technology</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Doctor of Philosophy in Business Administration (PhD BA)</h3>
                            <p>A three-year program that develops advanced research capabilities in business and management.</p>
                            
                            <h4>Research Areas:</h4>
                            <ul>
                                <li>Strategic Management</li>
                                <li>Organizational Behavior</li>
                                <li>Marketing Research</li>
                                <li>Financial Management</li>
                                <li>Operations Research</li>
                            </ul>
                        </div>
                        
                        <div class="program-section">
                            <h3>Doctor of Philosophy in Computer Science (PhD CS)</h3>
                            <p>A three-year program that prepares students for advanced research in computer science and technology.</p>
                            
                            <h4>Research Areas:</h4>
                            <ul>
                                <li>Artificial Intelligence</li>
                                <li>Machine Learning</li>
                                <li>Data Science</li>
                                <li>Cybersecurity</li>
                                <li>Software Engineering</li>
                            </ul>
                        </div>
                        
                        <h2>Program Formats</h2>
                        <div class="format-grid">
                            <div class="format-card">
                                <h4>Full-Time Program</h4>
                                <p>Traditional on-campus study with regular class schedules.</p>
                                <ul>
                                    <li>Regular class attendance</li>
                                    <li>Face-to-face instruction</li>
                                    <li>Full-time study load</li>
                                    <li>Complete in 2-3 years</li>
                                </ul>
                            </div>
                            
                            <div class="format-card">
                                <h4>Part-Time Program</h4>
                                <p>Flexible schedule for working professionals.</p>
                                <ul>
                                    <li>Evening and weekend classes</li>
                                    <li>Reduced course load</li>
                                    <li>Work-friendly schedule</li>
                                    <li>Complete in 3-4 years</li>
                                </ul>
                            </div>
                            
                            <div class="format-card">
                                <h4>Online Program</h4>
                                <p>Distance learning with online instruction and support.</p>
                                <ul>
                                    <li>Online lectures and materials</li>
                                    <li>Virtual classroom sessions</li>
                                    <li>Flexible study schedule</li>
                                    <li>Remote research support</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Research Facilities</h2>
                        <ul>
                            <li>Graduate Research Center</li>
                            <li>Computer Laboratory</li>
                            <li>Statistical Analysis Center</li>
                            <li>Library and Information Center</li>
                            <li>Conference and Seminar Rooms</li>
                            <li>Research Consultation Services</li>
                        </ul>
                        
                        <h2>Career Opportunities</h2>
                        <div class="career-grid">
                            <div class="career-category">
                                <h4>Academia</h4>
                                <ul>
                                    <li>University Professor</li>
                                    <li>Research Director</li>
                                    <li>Academic Administrator</li>
                                    <li>Research Coordinator</li>
                                    <li>Curriculum Developer</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Research</h4>
                                <ul>
                                    <li>Research Scientist</li>
                                    <li>Policy Researcher</li>
                                    <li>Market Research Analyst</li>
                                    <li>Data Scientist</li>
                                    <li>Research Consultant</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Industry</h4>
                                <ul>
                                    <li>Senior Manager</li>
                                    <li>Technical Director</li>
                                    <li>Chief Technology Officer</li>
                                    <li>Research and Development Manager</li>
                                    <li>Strategic Planning Manager</li>
                                </ul>
                            </div>
                            
                            <div class="career-category">
                                <h4>Government</h4>
                                <ul>
                                    <li>Policy Analyst</li>
                                    <li>Program Director</li>
                                    <li>Research Officer</li>
                                    <li>Technical Advisor</li>
                                    <li>Project Manager</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h2>Admission Requirements</h2>
                        <ul>
                            <li>Bachelor's Degree (for Master's) or Master's Degree (for PhD)</li>
                            <li>Passed Graduate School Entrance Examination</li>
                            <li>Transcript of Records</li>
                            <li>Certificate of Good Moral Character</li>
                            <li>Birth Certificate (PSA)</li>
                            <li>2x2 ID Photos</li>
                            <li>Medical Certificate</li>
                            <li>Research Proposal (for PhD)</li>
                            <li>Letters of Recommendation</li>
                        </ul>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Program Details</h3>
                        <ul>
                            <li><strong>Master's Duration:</strong> 2 years</li>
                            <li><strong>PhD Duration:</strong> 3 years</li>
                            <li><strong>Master's Programs:</strong> 4</li>
                            <li><strong>PhD Programs:</strong> 3</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Faculty</h3>
                        <p>Our faculty includes distinguished professors, researchers, and industry experts with PhD degrees and extensive research experience.</p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Research Support</h3>
                        <ul>
                            <li>Research Grants</li>
                            <li>Conference Funding</li>
                            <li>Publication Support</li>
                            <li>Research Mentoring</li>
                            <li>Statistical Consultation</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Dean:</strong><br>
                        Dr. Elena Rodriguez<br>
                        (02) 123-4581<br>
                        graduate.school@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../includes/footer.php';
?>
