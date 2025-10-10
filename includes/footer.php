    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- University Info Column -->
                <div class="footer-section university-info">
                    <div class="footer-logo-section">
                        <img src="<?php echo isset($base_path) ? $base_path : ''; ?>assets/images/logo.png" alt="University of Perpetual Help System" class="footer-logo">
                        <div class="university-details">
                            <h3 class="university-name">University of Perpetual Help System Laguna</h3>
                            <p class="university-tagline">Character Building is Nation Building</p>
                        </div>
                    </div>
                    <div class="social-links">
                        <a href="#" class="social-link facebook" title="Follow us on Facebook">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="social-link twitter" title="Follow us on Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link instagram" title="Follow us on Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link youtube" title="Subscribe to our YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="social-link linkedin" title="Connect with us on LinkedIn">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Online Services Column -->
                <div class="footer-section">
                    <h4 class="footer-subtitle">Online Services</h4>
                    <ul class="footer-links">
                        <li><a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="service-link">
                            <i class="fas fa-graduation-cap"></i>
                            School Automate (GTI)
                        </a></li>
                        <li><a href="https://uphslms.com/blended/login/index.php" target="_blank" class="service-link">
                            <i class="fas fa-book"></i>
                            Moodle
                        </a></li>
                        <li><a href="https://accounts.google.com/signin" target="_blank" class="service-link">
                            <i class="fab fa-google"></i>
                            Google Workspace
                        </a></li>
                        <li><a href="https://login.microsoftonline.com/" target="_blank" class="service-link">
                            <i class="fab fa-microsoft"></i>
                            Microsoft 365
                        </a></li>
                        <li><a href="http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="service-link">
                            <i class="fas fa-credit-card"></i>
                            Online Payment
                        </a></li>
                        <li><a href="https://saliksikuphsl.org/" target="_blank" class="service-link">
                            <i class="fas fa-search"></i>
                            Saliksik
                        </a></li>
                    </ul>
                </div>
                
                <!-- Quick Links Column -->
                <div class="footer-section">
                    <h4 class="footer-subtitle">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php">Home</a></li>
                        <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>about.php">About</a></li>
                        <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>contact.php">Contact</a></li>
                        <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs.php">Programs</a></li>
                        <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>support-services/careers.php">Careers</a></li>
                        <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>support-services/clinic.php">University Clinic</a></li>
                    </ul>
                </div>
                
                <!-- Contact Information Column -->
                <div class="footer-section">
                    <h4 class="footer-subtitle">Contact Information</h4>
                    <div class="contact-details">
                        <div class="contact-item">
                            <h5>Our Business Office</h5>
                            <p>UPH Compound, National Highway,<br>
                            Sto. Niño, City of Biñan, Laguna</p>
                        </div>
                        
                        <div class="contact-item">
                            <h5>Phone</h5>
                            <p><a href="tel:02-779-5310">02-779-5310</a></p>
                        </div>
                        
                        <div class="contact-item">
                            <h5>Email</h5>
                            <ul class="email-links">
                                <li><a href="mailto:marketing@uphsl.edu.ph">marketing@uphsl.edu.ph</a></li>
                                <li><a href="mailto:info@uphsl.edu.ph">info@uphsl.edu.ph</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p class="copyright">&copy; <?php echo date('Y'); ?> University of Perpetual Help System Laguna. All rights reserved.</p>
                    <div class="footer-bottom-links">
                        <a href="<?php echo isset($base_path) ? $base_path : ''; ?>privacy-policy.php" class="footer-bottom-link">Privacy Policy</a>
                        <a href="<?php echo isset($base_path) ? $base_path : ''; ?>terms-of-service.php" class="footer-bottom-link">Terms of Service</a>
                        <a href="<?php echo isset($base_path) ? $base_path : ''; ?>accessibility.php" class="footer-bottom-link">Accessibility</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?php echo isset($base_path) ? $base_path : ''; ?>assets/js/script.js"></script>
    
    <?php if (isset($additional_js)): ?>
        <?php foreach ($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($inline_js)): ?>
        <script>
            <?php echo $inline_js; ?>
        </script>
    <?php endif; ?>
    
    <!-- News Carousel JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('newsCarousel');
            const prevBtn = document.getElementById('newsPrev');
            const nextBtn = document.getElementById('newsNext');
            const dotsContainer = document.getElementById('newsDots');
            
            if (!carousel) return;
            
            const slides = carousel.querySelectorAll('.news-slide');
            const totalSlides = slides.length;
            let currentSlide = 0;
            
            // Create dots
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('button');
                dot.className = 'carousel-dot';
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }
            
            function updateCarousel() {
                // Hide all slides
                slides.forEach(slide => slide.classList.remove('active'));
                
                // Show current slide
                slides[currentSlide].classList.add('active');
                
                // Update dots
                const dots = dotsContainer.querySelectorAll('.carousel-dot');
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentSlide);
                });
            }
            
            function goToSlide(slideIndex) {
                currentSlide = slideIndex;
                updateCarousel();
            }
            
            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateCarousel();
            }
            
            function prevSlide() {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateCarousel();
            }
            
            // Event listeners
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);
            
            // Auto-play with faster interval for auto-scroll effect
            let autoPlayInterval = setInterval(nextSlide, 4000);
            
            // Pause on hover
            carousel.addEventListener('mouseenter', () => clearInterval(autoPlayInterval));
            carousel.addEventListener('mouseleave', () => {
                autoPlayInterval = setInterval(nextSlide, 4000);
            });
            
            // Touch/swipe support
            let startX = 0;
            let endX = 0;
            
            carousel.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
            });
            
            carousel.addEventListener('touchend', (e) => {
                endX = e.changedTouches[0].clientX;
                const diff = startX - endX;
                
                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                }
            });
            
            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                }
            });
        });
    </script>
    
    <!-- Facebook SDK -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0" nonce=""></script>
</body>
</html>
