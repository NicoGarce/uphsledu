// Mobile Navigation Toggle
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const userMenu = document.querySelector('.user-menu');

    if (navToggle && navMenu) {
        console.log('Mobile navigation initialized');
        navToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Hamburger clicked, toggling menu');
            console.log('Nav menu element:', navMenu);
            console.log('Nav menu computed style before:', window.getComputedStyle(navMenu).display);
            navMenu.classList.toggle('active');
            if (userMenu) {
                userMenu.classList.toggle('active');
            }
            navToggle.classList.toggle('active');
            console.log('Menu classes:', navMenu.className);
            console.log('Nav menu computed style after:', window.getComputedStyle(navMenu).display);
            console.log('Nav menu transform:', window.getComputedStyle(navMenu).transform);
            console.log('Nav menu opacity:', window.getComputedStyle(navMenu).opacity);
        });

        // Handle dropdown toggles for both nav-dropdown and nav-item.dropdown
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const dropdown = this.closest('.nav-dropdown') || this.closest('.nav-item.dropdown');
                if (dropdown) {
                    dropdown.classList.toggle('active');
                }
            });
        });

        // Close mobile menu when clicking on a regular nav link (not dropdown toggles)
        const navLinks = document.querySelectorAll('.nav-link:not(.dropdown-toggle)');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                if (userMenu) {
                    userMenu.classList.remove('active');
                }
                navToggle.classList.remove('active');
            });
        });

        // Close mobile menu when clicking outside or on backdrop
        document.addEventListener('click', function(event) {
            if (!navToggle.contains(event.target) && !navMenu.contains(event.target) && (!userMenu || !userMenu.contains(event.target))) {
                navMenu.classList.remove('active');
                if (userMenu) {
                    userMenu.classList.remove('active');
                }
                navToggle.classList.remove('active');
            }
        });
        
        // Close mobile menu when clicking on backdrop
        navMenu.addEventListener('click', function(event) {
            if (event.target === navMenu) {
                navMenu.classList.remove('active');
                if (userMenu) {
                    userMenu.classList.remove('active');
                }
                navToggle.classList.remove('active');
            }
        });

        // Prevent body scroll when mobile menu is open
        const body = document.body;
        navToggle.addEventListener('click', function() {
            if (navMenu.classList.contains('active')) {
                body.style.overflow = 'hidden';
            } else {
                body.style.overflow = '';
            }
        });

        // Reset body scroll when menu is closed
        const closeMenu = () => {
            navMenu.classList.remove('active');
            if (userMenu) {
                userMenu.classList.remove('active');
            }
            navToggle.classList.remove('active');
            body.style.overflow = '';
        };

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                closeMenu();
            }
        });
    }
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add scroll effect to navbar
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.style.background = 'linear-gradient(135deg, rgba(28, 77, 161, 0.95) 0%, rgba(82, 123, 189, 0.95) 100%)';
        navbar.style.backdropFilter = 'blur(10px)';
    } else {
        navbar.style.background = 'linear-gradient(135deg, #1c4da1 0%, #527bbd 100%)';
        navbar.style.backdropFilter = 'none';
    }
});

// Animate elements on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements for animation
document.addEventListener('DOMContentLoaded', function() {
    const animateElements = document.querySelectorAll('.post-card, .feature-card');
    animateElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});

// Form validation helper
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            showFieldError(input, 'This field is required');
            isValid = false;
        } else {
            clearFieldError(input);
        }
    });

    return isValid;
}

// Show field error
function showFieldError(field, message) {
    clearFieldError(field);
    field.style.borderColor = '#ef4444';
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.style.color = '#ef4444';
    errorDiv.style.fontSize = '0.875rem';
    errorDiv.style.marginTop = '5px';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

// Clear field error
function clearFieldError(field) {
    field.style.borderColor = '';
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Show flash messages
function showFlashMessage(type, message) {
    const flashContainer = document.createElement('div');
    flashContainer.className = `flash-message flash-${type}`;
    flashContainer.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        animation: slideInRight 0.3s ease;
        max-width: 400px;
    `;

    if (type === 'success') {
        flashContainer.style.background = '#10b981';
    } else if (type === 'error') {
        flashContainer.style.background = '#ef4444';
    } else if (type === 'warning') {
        flashContainer.style.background = '#f59e0b';
    } else {
        flashContainer.style.background = '#6b7280';
    }

    flashContainer.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; cursor: pointer; font-size: 18px;">&times;</button>
        </div>
    `;

    document.body.appendChild(flashContainer);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (flashContainer.parentNode) {
            flashContainer.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => flashContainer.remove(), 300);
        }
    }, 5000);
}

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .field-error {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
`;
document.head.appendChild(style);

// Utility function to format date
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    };
    return date.toLocaleDateString('en-US', options);
}

// Utility function to truncate text
function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substr(0, maxLength) + '...';
}

// Search functionality
function initSearch() {
    const searchInput = document.getElementById('search-input');
    if (!searchInput) return;

    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            clearSearchResults();
            return;
        }

        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });
}

function performSearch(query) {
    // This would typically make an AJAX request to a search endpoint
    console.log('Searching for:', query);
    // Implementation would depend on your backend search functionality
}

function clearSearchResults() {
    const resultsContainer = document.getElementById('search-results');
    if (resultsContainer) {
        resultsContainer.innerHTML = '';
    }
}

// Initialize search when DOM is loaded
document.addEventListener('DOMContentLoaded', initSearch);

// Hide YouTube controls and ensure proper video loading
document.addEventListener('DOMContentLoaded', function() {
    const videoContainer = document.querySelector('#youtube-video');
    if (videoContainer) {
        // Force hide YouTube controls with CSS
        const style = document.createElement('style');
        style.textContent = `
            #youtube-video {
                pointer-events: none !important;
            }
            .ytp-chrome-top,
            .ytp-show-cards-title,
            .ytp-title,
            .ytp-watermark,
            .ytp-impression-link,
            .ytp-endscreen-content,
            .ytp-videowall-still,
            .ytp-suggested-action,
            .ytp-pause-overlay,
            .ytp-scroll-min,
            .ytp-scroll-max,
            .ytp-progress-bar-container,
            .ytp-chrome-controls,
            .ytp-chrome-bottom,
            .ytp-chrome-top,
            .ytp-gradient-top,
            .ytp-gradient-bottom {
                display: none !important;
                opacity: 0 !important;
                visibility: hidden !important;
            }
        `;
        document.head.appendChild(style);
        
        // Continuously hide YouTube elements
        setInterval(() => {
            const youtubeElements = document.querySelectorAll('[class*="ytp"], [id*="ytp"], [class*="yt-"], [id*="yt-"]');
            youtubeElements.forEach(el => {
                if (el.tagName !== 'IFRAME') {
                    el.style.display = 'none !important';
                    el.style.opacity = '0 !important';
                    el.style.visibility = 'hidden !important';
                }
            });
        }, 100);
        
        // Ensure video covers the entire container
        videoContainer.style.width = '100%';
        videoContainer.style.height = '100%';
        videoContainer.style.objectFit = 'cover';
        videoContainer.style.pointerEvents = 'none';
    }
});


