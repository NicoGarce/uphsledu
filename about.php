<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Set page title
$page_title = "About Us";

// Include header
include 'includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>About University of Perpetual Help System Laguna</h1>
            <p>Character Building is Nation Building</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <h2>Our Mission</h2>
                        <p>The University of Perpetual Help System Laguna is committed to providing quality education that develops the character and competence of its students, preparing them to become productive citizens and future leaders of the nation.</p>
                        
                        <h2>Our Vision</h2>
                        <p>To be a leading educational institution that produces graduates who are not only academically excellent but also morally upright, socially responsible, and globally competitive.</p>
                        
                        <h2>Our History</h2>
                        <p>Founded with the vision of providing accessible quality education, the University of Perpetual Help System Laguna has been serving the community for decades, producing thousands of successful graduates who have made significant contributions to society.</p>
                        
                        <h2>Academic Excellence</h2>
                        <p>We offer a wide range of academic programs from basic education to graduate studies, ensuring that our students receive comprehensive education that meets international standards while maintaining our commitment to character formation.</p>
                        
                        <h2>Community Engagement</h2>
                        <p>Our university is deeply committed to community service and outreach programs, instilling in our students the value of giving back to society and contributing to nation-building.</p>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Quick Facts</h3>
                        <ul>
                            <li>Established: 1975</li>
                            <li>Location: Laguna, Philippines</li>
                            <li>Student Population: 15,000+</li>
                            <li>Faculty: 500+</li>
                            <li>Programs: 50+</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3>Contact Information</h3>
                        <p><strong>Address:</strong><br>
                        University of Perpetual Help System Laguna<br>
                        Biñan, Laguna, Philippines</p>
                        
                        <p><strong>Phone:</strong><br>
                        (02) 123-4567</p>
                        
                        <p><strong>Email:</strong><br>
                        info@uphsl.edu.ph</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include 'includes/footer.php';
?>
