<?php
/**
 * UPHSL Privacy Policy Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Privacy policy for the University of Perpetual Help System Laguna website
 */

session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Set page title
$page_title = "Privacy Policy";

// Set base path for assets
$base_path = '';

// Include header
include 'app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1>Privacy Policy</h1>
                <p>How we collect, use, and protect your personal information</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="policy-content">
                <div class="policy-section">
                    <h2>1. Information We Collect</h2>
                    <p>Through our UPHSL website, we collect information when you:</p>
                    <ul>
                        <li>Browse our academic programs and campus information</li>
                        <li>Access our online services (GTI, Moodle, Google Workspace, Microsoft 365)</li>
                        <li>View our news updates and announcements</li>
                        <li>Use our search functionality to find specific information</li>
                        <li>Navigate through our support services pages</li>
                        <li>Access our research and library resources</li>
                    </ul>
                    <p>The types of information we may collect include:</p>
                    <ul>
                        <li>IP address and browser information for website analytics</li>
                        <li>Pages visited and time spent on our website</li>
                        <li>Referring website information</li>
                        <li>Device and browser type for optimization purposes</li>
                        <li>Any information you voluntarily provide through contact forms</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h2>2. How We Use Your Information</h2>
                    <p>We use the information we collect to:</p>
                    <ul>
                        <li>Improve our website functionality and user experience</li>
                        <li>Analyze website traffic and popular content areas</li>
                        <li>Optimize our academic program pages and support services</li>
                        <li>Ensure our online services (GTI, Moodle, etc.) are accessible</li>
                        <li>Monitor the performance of our news updates and announcements</li>
                        <li>Enhance our search functionality and content organization</li>
                        <li>Provide technical support for website-related issues</li>
                        <li>Comply with educational and institutional reporting requirements</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h2>3. Information Sharing</h2>
                    <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except in the following circumstances:</p>
                    <ul>
                        <li>With your explicit consent</li>
                        <li>To comply with legal obligations</li>
                        <li>To protect our rights and safety</li>
                        <li>With trusted service providers who assist us in operating our website</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h2>4. Data Security</h2>
                    <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure.</p>
                </div>

                <div class="policy-section">
                    <h2>5. Cookies and Tracking</h2>
                    <p>Our UPHSL website uses cookies and similar technologies to:</p>
                    <ul>
                        <li>Remember your preferences when browsing our academic programs</li>
                        <li>Track which campus locations and support services you're interested in</li>
                        <li>Improve the performance of our interactive education level buttons</li>
                        <li>Optimize our news carousel and content display</li>
                        <li>Ensure smooth navigation between our various program pages</li>
                    </ul>
                    <p>You can control cookie settings through your browser preferences. Note that disabling cookies may affect some website functionality.</p>
                </div>

                <div class="policy-section">
                    <h2>6. Your Rights</h2>
                    <p>You have the right to:</p>
                    <ul>
                        <li>Access your personal information</li>
                        <li>Correct inaccurate information</li>
                        <li>Request deletion of your information</li>
                        <li>Opt-out of marketing communications</li>
                        <li>Withdraw consent at any time</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h2>7. Changes to This Policy</h2>
                    <p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last Updated" date.</p>
                </div>

                <div class="policy-section">
                    <h2>8. Contact Us</h2>
                    <p>If you have any questions about this privacy policy, please contact us:</p>
                    <div class="contact-info">
                        <p><strong>Email:</strong> privacy@uphsl.edu.ph</p>
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
