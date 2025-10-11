<?php
/**
 * UPHSL Terms of Service Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Terms of service for the University of Perpetual Help System Laguna website
 */

session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Set page title
$page_title = "Terms of Service";

// Set base path for assets
$base_path = '';

// Include header
include 'app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1>Terms of Service</h1>
                <p>Terms and conditions for using our website and services</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="policy-content">
                <div class="policy-section">
                    <h2>1. Acceptance of Terms</h2>
                    <p>By accessing and using the University of Perpetual Help System Laguna (UPHSL) website, including browsing our academic programs, accessing online services, viewing news updates, and using our interactive features, you accept and agree to be bound by these terms. If you do not agree to these terms, please do not use our website.</p>
                </div>

                <div class="policy-section">
                    <h2>2. Permitted Use</h2>
                    <p>You may use our UPHSL website for the following purposes:</p>
                    <ul>
                        <li>Browse and view information about our academic programs and campuses</li>
                        <li>Access our online services (GTI, Moodle, Google Workspace, Microsoft 365)</li>
                        <li>Read news updates and university announcements</li>
                        <li>Use our search functionality to find specific information</li>
                        <li>Navigate through our support services and resources</li>
                        <li>Interact with our educational content and program information</li>
                    </ul>
                    <p>You may not:</p>
                    <ul>
                        <li>Modify, copy, or redistribute our academic program information</li>
                        <li>Use our content for commercial purposes without permission</li>
                        <li>Attempt to access restricted areas or administrative functions</li>
                        <li>Remove copyright notices or proprietary markings</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h2>3. User Conduct</h2>
                    <p>When using our UPHSL website, you agree to:</p>
                    <ul>
                        <li>Use the website respectfully and for educational purposes</li>
                        <li>Not interfere with the functionality of our interactive features</li>
                        <li>Not attempt to access our administrative areas or database systems</li>
                        <li>Respect the intellectual property of our academic content</li>
                        <li>Not use automated tools to scrape or harvest website content</li>
                        <li>Not attempt to disrupt our online services (GTI, Moodle, etc.)</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h2>4. Academic Integrity</h2>
                    <p>All students and users of our educational services must maintain the highest standards of academic integrity. This includes:</p>
                    <ul>
                        <li>Completing all assignments and examinations honestly</li>
                        <li>Properly citing all sources and references</li>
                        <li>Not engaging in plagiarism or cheating</li>
                        <li>Respecting intellectual property rights</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h2>5. Intellectual Property</h2>
                    <p>The content, organization, graphics, design, compilation, magnetic translation, digital conversion, and other matters related to the website are protected under applicable copyrights, trademarks, and other proprietary rights. The copying, redistribution, use, or publication by you of any such matters or any part of the website is strictly prohibited.</p>
                </div>

                <div class="policy-section">
                    <h2>6. Disclaimer</h2>
                    <p>The materials on UPHSL's website are provided on an 'as is' basis. UPHSL makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
                </div>

                <div class="policy-section">
                    <h2>7. Limitations</h2>
                    <p>In no event shall UPHSL or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on UPHSL's website, even if UPHSL or an authorized representative has been notified orally or in writing of the possibility of such damage.</p>
                </div>

                <div class="policy-section">
                    <h2>8. Accuracy of Materials</h2>
                    <p>The materials appearing on UPHSL's website could include technical, typographical, or photographic errors. UPHSL does not warrant that any of the materials on its website are accurate, complete, or current. UPHSL may make changes to the materials contained on its website at any time without notice.</p>
                </div>

                <div class="policy-section">
                    <h2>9. Links to Other Websites</h2>
                    <p>Our website may contain links to third-party websites that are not owned or controlled by UPHSL. We have no control over and assume no responsibility for the content, privacy policies, or practices of any third-party websites.</p>
                </div>

                <div class="policy-section">
                    <h2>10. Modifications</h2>
                    <p>UPHSL may revise these terms of service at any time without notice. By using this website, you are agreeing to be bound by the then current version of these terms of service.</p>
                </div>

                <div class="policy-section">
                    <h2>11. Governing Law</h2>
                    <p>These terms and conditions are governed by and construed in accordance with the laws of the Philippines and you irrevocably submit to the exclusive jurisdiction of the courts in that state or location.</p>
                </div>

                <div class="policy-section">
                    <h2>12. Contact Information</h2>
                    <p>If you have any questions about these Terms of Service, please contact us:</p>
                    <div class="contact-info">
                        <p><strong>Email:</strong> legal@uphsl.edu.ph</p>
                        <p><strong>Phone:</strong> (02) 779-5310</p>
                        <p><strong>Address:</strong> University of Perpetual Help System Laguna, UPH Compound, National Highway, Sto. Niño, City of Biñan, Laguna</p>
                    </div>
                </div>

                <div class="policy-footer">
                    <p><strong>Last Updated:</strong> January 2025</p>
                </div>
            </div>
        </div>
    </main>

<?php
// Include footer
include 'app/includes/footer.php';
?>
