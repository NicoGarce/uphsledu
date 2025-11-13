/**
 * UPHSL Website JavaScript
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Main JavaScript functionality for the UPHSL website
 */

// Dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = this.closest('.dropdown');
            if (dropdown) {
                // Close other dropdowns
                document.querySelectorAll('.dropdown').forEach(otherDropdown => {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.classList.remove('active');
                    }
                });
                
                // Toggle current dropdown
                dropdown.classList.toggle('active');
        }
    });
});

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown').forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }
    });

    // News Carousel functionality - supports multiple carousels
    // Track initialized carousels to prevent double initialization
    const initializedCarousels = new Set();
    
    function initializeNewsCarousel(carouselId) {
        // Prevent double initialization
        if (initializedCarousels.has(carouselId)) {
            return;
        }
        
        // Extract base ID (remove category suffix if present)
        const baseId = carouselId.replace(/^newsCarousel-/, 'newsCarousel');
        const suffix = carouselId.includes('-') ? carouselId.replace('newsCarousel-', '') : '';
        
        const newsCarousel = document.getElementById(carouselId);
        const newsPrev = document.getElementById(suffix ? `newsPrev-${suffix}` : 'newsPrev');
        const newsNext = document.getElementById(suffix ? `newsNext-${suffix}` : 'newsNext');
        const newsDots = document.getElementById(suffix ? `newsDots-${suffix}` : 'newsDots');
        
        if (newsCarousel && newsPrev && newsNext && newsDots) {
            const slides = newsCarousel.querySelectorAll('.news-slide');
            if (slides.length === 0) return; // No slides, skip initialization
            
            // Check if dots already exist (prevent double creation)
            const existingDots = newsDots.querySelectorAll('.carousel-dot');
            if (existingDots.length > 0) {
                // Dots already exist, mark as initialized and return
                initializedCarousels.add(carouselId);
                return;
            }
            
            let currentSlide = 0;
            let autoPlayInterval;
            
            // Create dots
            slides.forEach((_, index) => {
                const dot = document.createElement('button');
                dot.className = 'carousel-dot';
                if (index === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(index));
                newsDots.appendChild(dot);
            });
            
            function goToSlide(slideIndex) {
                slides.forEach((slide, index) => {
                    slide.classList.toggle('active', index === slideIndex);
                });
                
                newsDots.querySelectorAll('.carousel-dot').forEach((dot, index) => {
                    dot.classList.toggle('active', index === slideIndex);
                });
                
                currentSlide = slideIndex;
            }
            
            function nextSlide() {
                const next = (currentSlide + 1) % slides.length;
                goToSlide(next);
            }
            
            function prevSlide() {
                const prev = (currentSlide - 1 + slides.length) % slides.length;
                goToSlide(prev);
            }
            
            // Function to start auto-play
            function startAutoPlay() {
                autoPlayInterval = setInterval(nextSlide, 5000);
            }
            
            // Function to stop auto-play
            function stopAutoPlay() {
                if (autoPlayInterval) {
                    clearInterval(autoPlayInterval);
                    autoPlayInterval = null;
                }
            }
            
            // Event listeners
            newsNext.addEventListener('click', nextSlide);
            newsPrev.addEventListener('click', prevSlide);
            
            // Start auto-play carousel
            startAutoPlay();
            
            // Pause auto-play on hover over carousel
            newsCarousel.addEventListener('mouseenter', stopAutoPlay);
            
            // Resume auto-play when mouse leaves carousel
            newsCarousel.addEventListener('mouseleave', startAutoPlay);
            
            // Pause auto-play on hover over navigation controls
            newsPrev.addEventListener('mouseenter', stopAutoPlay);
            newsNext.addEventListener('mouseenter', stopAutoPlay);
            newsDots.addEventListener('mouseenter', stopAutoPlay);
            
            // Resume auto-play when mouse leaves navigation controls
            newsPrev.addEventListener('mouseleave', startAutoPlay);
            newsNext.addEventListener('mouseleave', startAutoPlay);
            newsDots.addEventListener('mouseleave', startAutoPlay);
            
            // Touch/swipe support
            let startX = 0;
            let endX = 0;
            
            newsCarousel.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
            });
            
            newsCarousel.addEventListener('touchend', (e) => {
                endX = e.changedTouches[0].clientX;
                const diff = startX - endX;
                
                if (Math.abs(diff) > 50) { // Minimum swipe distance
                    if (diff > 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                }
            });
            
            // Mark as initialized after all setup is complete
            initializedCarousels.add(carouselId);
        }
    }
    
    // Initialize all carousels on the page
    const allCarousels = document.querySelectorAll('[id^="newsCarousel"]');
    allCarousels.forEach(carousel => {
        initializeNewsCarousel(carousel.id);
    });

    // Progressive hero video injection (avoid FOUC on hard refresh)
    const heroBg = document.querySelector('.hero-background .hero-image');
    if (heroBg && heroBg.dataset.bgVideo) {
        // Defer injection until after load to let CSS settle
        window.requestAnimationFrame(() => {
            const video = document.createElement('video');
            video.className = 'hero-video';
            video.autoplay = true;
            video.muted = true;
            video.loop = true;
            video.playsInline = true;
            video.poster = heroBg.dataset.bgPoster || '';
            const source = document.createElement('source');
            source.src = heroBg.dataset.bgVideo;
            source.type = 'video/mp4';
            video.appendChild(source);
            // Replace image once metadata is ready to minimize flash
            video.addEventListener('loadeddata', () => {
                heroBg.replaceWith(video);
            }, { once: true });
        });
    }

    // Progressive about video injection
    const aboutPoster = document.querySelector('.intro-visual .about-poster');
    if (aboutPoster && aboutPoster.dataset.aboutVideo) {
        window.requestAnimationFrame(() => {
            const video = document.createElement('video');
            video.className = 'about-video';
            video.autoplay = true;
            video.muted = true;
            video.loop = true;
            video.playsInline = true;
            video.poster = aboutPoster.dataset.aboutPoster || '';
            const source = document.createElement('source');
            source.src = aboutPoster.dataset.aboutVideo;
            source.type = 'video/mp4';
            video.appendChild(source);
            video.addEventListener('loadeddata', () => {
                aboutPoster.replaceWith(video);
            }, { once: true });
        });
    }
    // Mobile Menu functionality
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');
    const mobileSidebarClose = document.getElementById('mobile-sidebar-close');
    const mobileDropdownToggles = document.querySelectorAll('.mobile-dropdown-toggle');

    // Toggle mobile sidebar
    function toggleMobileSidebar() {
        if (mobileSidebar) {
            mobileSidebar.classList.toggle('active');
            mobileSidebarOverlay.classList.toggle('active');
            document.body.classList.toggle('sidebar-open');
        }
    }

    // Close mobile sidebar
    function closeMobileSidebar() {
        if (mobileSidebar) {
            mobileSidebar.classList.remove('active');
            mobileSidebarOverlay.classList.remove('active');
            document.body.classList.remove('sidebar-open');
        }
    }

    // Event listeners for mobile menu
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', toggleMobileSidebar);
    }

    if (mobileSidebarClose) {
        mobileSidebarClose.addEventListener('click', closeMobileSidebar);
    }

    if (mobileSidebarOverlay) {
        mobileSidebarOverlay.addEventListener('click', closeMobileSidebar);
    }

    // Mobile dropdown functionality
    mobileDropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = this.closest('.mobile-dropdown');
            if (dropdown) {
                // Close other mobile dropdowns
                document.querySelectorAll('.mobile-dropdown').forEach(otherDropdown => {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.classList.remove('active');
                    }
                });
                
                // Toggle current dropdown
                dropdown.classList.toggle('active');
            }
        });
    });

    // Mobile nested submenu functionality
    const mobileSubmenuToggles = document.querySelectorAll('.mobile-dropdown-parent');
    
    mobileSubmenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const submenuItem = this.closest('.mobile-dropdown-item-with-submenu');
            if (submenuItem) {
                // Close other nested submenus
                document.querySelectorAll('.mobile-dropdown-item-with-submenu').forEach(otherSubmenu => {
                    if (otherSubmenu !== submenuItem) {
                        otherSubmenu.classList.remove('active');
                    }
                });
                
                // Toggle current nested submenu
                submenuItem.classList.toggle('active');
            }
        });
    });

    // Close mobile dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.mobile-dropdown')) {
            document.querySelectorAll('.mobile-dropdown').forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }
    });

    // Close mobile sidebar when window is resized to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeMobileSidebar();
        }
    });

    // Back to Top Button
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        const toggleBackToTop = () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        };
        toggleBackToTop();
        window.addEventListener('scroll', toggleBackToTop);

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Hero Clock & Ticker
    const heroClockEl = document.getElementById('heroClock');
    const heroDateEl = document.getElementById('heroDate');
    const tickerContentEl = document.getElementById('heroTickerContent');
    if (heroClockEl) {
        function updateClock() {
            const now = new Date();
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
            heroClockEl.textContent = now.toLocaleTimeString([], timeOptions);
            if (heroDateEl) {
                const dateOptions = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
                heroDateEl.textContent = now.toLocaleDateString([], dateOptions);
            }
        }
        updateClock();
        setInterval(updateClock, 1000);
    }
    
    // Image loading optimization
    function initializeImageLoading() {
        // Handle lazy loaded images
        const lazyImages = document.querySelectorAll('img[loading="lazy"]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        // Wait for image to fully load before showing
                        img.addEventListener('load', () => {
                            // Small delay to ensure image is fully rendered
                            setTimeout(() => {
                                img.classList.add('loaded');
                            }, 50);
                        });
                        observer.unobserve(img);
                    }
                });
            });
            
            lazyImages.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback for older browsers
            lazyImages.forEach(img => {
                img.addEventListener('load', () => {
                    setTimeout(() => {
                        img.classList.add('loaded');
                    }, 50);
                });
            });
        }
        
        // Handle regular images - make them visible immediately
        const regularImages = document.querySelectorAll('img:not([loading="lazy"]):not(.hero-image)');
        regularImages.forEach(img => {
            img.style.opacity = '1'; // Make visible immediately
            if (img.complete) {
                img.classList.add('loaded');
            } else {
                img.addEventListener('load', () => {
                    img.classList.add('loaded');
                });
            }
        });
        
        // Preload critical images to prevent progressive loading
        const criticalImages = document.querySelectorAll('.hero-image, .slide-image:first-child');
        criticalImages.forEach(img => {
            if (img.complete) {
                img.classList.add('loaded');
            } else {
                img.addEventListener('load', () => {
                    img.classList.add('loaded');
                });
            }
        });
    }
    
    initializeImageLoading();
    
    // Note: The hero ticker cycles items via inline script in index.php using
    // absolute-positioned .ticker-item elements. Avoid duplicating content here
    // to prevent stacking/overlap.
});