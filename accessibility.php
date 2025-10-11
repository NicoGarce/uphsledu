<?php
/**
 * UPHSL Accessibility Statement Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Accessibility statement for the University of Perpetual Help System Laguna website
 */

session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Set page title
$page_title = "Accessibility Statement";

// Set base path for assets
$base_path = '';

// Include header
include 'app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1>Accessibility Statement</h1>
                <p>Our commitment to making our website accessible to everyone</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="policy-content">
                <div class="policy-section">
                    <h2>Our Commitment</h2>
                    <p>The University of Perpetual Help System Laguna (UPHSL) is committed to ensuring digital accessibility for people with disabilities. We are continually improving the user experience for everyone and applying the relevant accessibility standards to ensure we provide equal access to all users.</p>
                </div>

                <div class="policy-section">
                    <h2>Accessibility Standards</h2>
                    <p>We aim to conform to the Web Content Accessibility Guidelines (WCAG) 2.1 Level AA standards. These guidelines help make web content more accessible to people with disabilities and user-friendly for everyone.</p>
                </div>

                <div class="policy-section">
                    <h2>Accessibility Features</h2>
                    <p>Our UPHSL website includes the following accessibility features:</p>
                    <ul>
                        <li><strong>Interactive Education Buttons:</strong> Our Bachelor's, Master's, Doctorate, and K-12 program buttons are fully keyboard accessible</li>
                        <li><strong>Program Navigation:</strong> All academic program pages can be navigated using keyboard only</li>
                        <li><strong>News Carousel:</strong> Our news updates carousel supports keyboard navigation and screen readers</li>
                        <li><strong>Search Functionality:</strong> The search feature is accessible via keyboard and screen readers</li>
                        <li><strong>Online Services Links:</strong> All external service links (GTI, Moodle, Google Workspace) are properly labeled</li>
                        <li><strong>Support Services:</strong> Our support services pages are structured with proper headings for screen readers</li>
                        <li><strong>Contact Information:</strong> Contact details are clearly labeled and accessible</li>
                        <li><strong>Responsive Design:</strong> All content adapts to different screen sizes and devices</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h2>Assistive Technologies</h2>
                    <p>Our website is designed to work with various assistive technologies, including:</p>
                    <ul>
                        <li>Screen readers (NVDA, JAWS, VoiceOver)</li>
                        <li>Voice recognition software</li>
                        <li>Switch navigation devices</li>
                        <li>Magnification software</li>
                        <li>Text-to-speech tools</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h2>Known Limitations</h2>
                    <p>While we strive to make our UPHSL website fully accessible, we acknowledge that some areas may have limitations:</p>
                    <ul>
                        <li>External online services (GTI, Moodle, Google Workspace, Microsoft 365) are managed by third parties and may have different accessibility standards</li>
                        <li>Some PDF documents in our support services may not be fully screen reader compatible</li>
                        <li>News carousel auto-play features may need to be paused for some users</li>
                        <li>Interactive program buttons may require JavaScript to be enabled for full functionality</li>
                    </ul>
                    <p>We are continuously working to address these limitations and improve accessibility across all our website content.</p>
                </div>

                <div class="policy-section">
                    <h2>Feedback and Support</h2>
                    <p>We welcome your feedback on the accessibility of our website. If you encounter any accessibility barriers or have suggestions for improvement, please contact us:</p>
                    <div class="contact-info">
                        <p><strong>Accessibility Coordinator:</strong> accessibility@uphsl.edu.ph</p>
                        <p><strong>Phone:</strong> (02) 779-5310</p>
                        <p><strong>TTY:</strong> Available through our main phone line</p>
                        <p><strong>Address:</strong> University of Perpetual Help System Laguna, UPH Compound, National Highway, Sto. Niño, City of Biñan, Laguna</p>
                    </div>
                </div>

                <div class="policy-section">
                    <h2>Alternative Formats</h2>
                    <p>If you need information from our website in an alternative format, please contact us. We can provide:</p>
                    <ul>
                        <li>Large print materials</li>
                        <li>Audio recordings</li>
                        <li>Braille documents</li>
                        <li>Electronic text files</li>
                        <li>Other accessible formats as needed</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h2>Ongoing Improvements</h2>
                    <p>We are committed to continuously improving the accessibility of our website. This includes:</p>
                    <ul>
                        <li>Regular accessibility audits and testing</li>
                        <li>Training staff on accessibility best practices</li>
                        <li>Implementing user feedback and suggestions</li>
                        <li>Staying updated with accessibility standards and guidelines</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h2>Accessibility Resources</h2>
                    <p>For more information about web accessibility, please visit:</p>
                    <ul>
                        <li><a href="https://www.w3.org/WAI/" target="_blank" rel="noopener">Web Accessibility Initiative (WAI)</a></li>
                        <li><a href="https://www.section508.gov/" target="_blank" rel="noopener">Section 508 Guidelines</a></li>
                        <li><a href="https://www.ada.gov/" target="_blank" rel="noopener">Americans with Disabilities Act (ADA)</a></li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h2>Compliance</h2>
                    <p>This accessibility statement is reviewed and updated regularly to ensure it reflects our current accessibility practices and compliance with applicable laws and standards.</p>
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
