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

    // News Carousel functionality
    const newsCarousel = document.getElementById('newsCarousel');
    const newsPrev = document.getElementById('newsPrev');
    const newsNext = document.getElementById('newsNext');
    const newsDots = document.getElementById('newsDots');
    
    if (newsCarousel && newsPrev && newsNext && newsDots) {
        const slides = newsCarousel.querySelectorAll('.news-slide');
        let currentSlide = 0;
        
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
        
        // Event listeners
        newsNext.addEventListener('click', nextSlide);
        newsPrev.addEventListener('click', prevSlide);
        
        // Auto-play carousel
        setInterval(nextSlide, 5000);
        
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
    if (tickerContentEl) {
        // Duplicate content once to enable seamless scroll (animation translates -50%).
        const originalHTML = tickerContentEl.innerHTML.trim();
        tickerContentEl.innerHTML = originalHTML + originalHTML;
        // Pause on hover
        const tickerTrack = tickerContentEl.closest('.hero-ticker-track');
        if (tickerTrack) {
            tickerTrack.addEventListener('mouseenter', () => {
                tickerContentEl.style.animationPlayState = 'paused';
            });
            tickerTrack.addEventListener('mouseleave', () => {
                tickerContentEl.style.animationPlayState = 'running';
            });
        }
    }
});