<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Set page title
$page_title = "Contact Us";

// Include header
include 'includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Contact Us</h1>
            <p>Get in touch with University of Perpetual Help System Laguna</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <div class="contact-info-grid">
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3>Address</h3>
                            <p>University of Perpetual Help System Laguna<br>
                            Biñan, Laguna, Philippines</p>
                        </div>
                        
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h3>Phone</h3>
                            <p>Main: (02) 123-4567<br>
                            Admissions: (02) 123-4568</p>
                        </div>
                        
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3>Email</h3>
                            <p>General: info@uphsl.edu.ph<br>
                            Admissions: admissions@uphsl.edu.ph</p>
                        </div>
                        
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3>Office Hours</h3>
                            <p>Monday - Friday: 8:00 AM - 5:00 PM<br>
                            Saturday: 8:00 AM - 12:00 PM</p>
                        </div>
                    </div>
                    
                    <div class="contact-form-section">
                        <h2>Send us a Message</h2>
                        <form class="contact-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone">
                                </div>
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <select id="subject" name="subject" required>
                                        <option value="">Select a subject</option>
                                        <option value="admissions">Admissions Inquiry</option>
                                        <option value="academic">Academic Information</option>
                                        <option value="financial">Financial Aid</option>
                                        <option value="general">General Inquiry</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="5" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php
// Include footer
include 'includes/footer.php';
?>
