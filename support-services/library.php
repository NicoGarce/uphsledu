<?php
/**
 * UPHSL College Library Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the College Library services and resources
 */
session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Ensure the library CAS table exists (Current Awareness Services)
try {
    $db = getDBConnection();
    $db->exec("CREATE TABLE IF NOT EXISTS library_cas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(191) NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        image VARCHAR(1024) DEFAULT '',
        link VARCHAR(2048) DEFAULT '',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY ux_slug (slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
} catch (Exception $e) {
    // non-fatal: table creation failure should not break the page
}

// Check if this sub-page or Support Services section is in maintenance
if (isSectionInMaintenance('support-services', 'library') || isSectionInMaintenance('support-services')) {
    $page_title = "University Library - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('support-services', $base_path, 'library')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$base_path = '../';
$page_title = "University Library - UPHSL";
include '../app/includes/header.php';
?>


<style>
body {
    padding: 0 !important;
    margin: 0 !important;
}

.main-content {
    padding-top: 100px; /* Base padding for header */
}

/* Responsive header spacing for different devices */
@media (max-width: 1200px) {
    .main-content {
        padding-top: 90px;
    }
}

@media (max-width: 992px) {
    .main-content {
        padding-top: 80px;
    }
}

@media (max-width: 768px) {
    .main-content {
        padding-top: 70px;
    }
}

@media (max-width: 576px) {
    .main-content {
        padding-top: 60px;
    }
}

/* Ensure intro section starts immediately after header */
.intro-section {
    margin-top: 0;
}

/* Intro Section */
.intro-section {
    background: linear-gradient(135deg, rgba(44, 90, 160, 0.9), rgba(255, 198, 62, 0.9)), url('<?php echo $base_path; ?>assets/images/FACADE.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: white;
    padding: 2rem 0;
    margin: 0;
    position: relative;
    overflow: hidden;
}

.intro-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 1rem;
}

.intro-logo {
    margin-bottom: 1.5rem;
}

.intro-logo img {
    width: 150px;
    height: 150px;
    object-fit: contain;
    filter: brightness(1.1);
    transition: transform 0.3s ease;
}

.intro-content h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    letter-spacing: -0.5px;
}

.intro-description {
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1.5rem;
    opacity: 0.95;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

/* Content Sections */
.content-section {
    padding: 4rem 0;
    background: #ffffff;
}


.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 1rem;
    position: relative;
}

.section-subtitle {
    text-align: center;
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Mission Vision Section */
.lib-mission-vision-section {
    background: #ffffff; /* make section background white for cohesion */
    padding: 4rem 0;
}

.mv-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    max-width: 1000px;
    margin: 0 auto;
    padding: 1rem;
}

.mv-card {
    background: #ffffff;
    padding: 2.5rem;
    border-radius: 18px;
    box-shadow: 0 12px 40px rgba(16,24,40,0.06);
    text-align: center;
    border-left: 4px solid var(--primary-color);
}

.mv-card h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.mv-card p {
    color: #666;
    line-height: 1.6;
    font-size: 1rem;
}

/* Online Services Section */
.online-services-section {
    background: white;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.service-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(44, 90, 160, 0.1);
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
}

.service-image {
    width: 120px;
    height: 80px;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border-radius: 10px;
    padding: 10px;
}

.service-image img {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
    filter: brightness(1.1);
}

.service-card h4 {
    color: var(--primary-color);
    font-size: 1.3rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.service-card p {
    color: #666;
    line-height: 1.6;
    font-size: 0.95rem;
}

/* Quality Objectives Section */
.quality-objectives-section {
    background: #ffffff;
}

.objectives-list {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.objectives-list ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.objectives-list ul li {
    color: #444;
    line-height: 1.7;
    margin-bottom: 1rem;
    padding-left: 2rem;
    position: relative;
    font-size: 1rem;
    font-weight: 500;
}

.objectives-list ul li::before {
    content: "✓";
    color: var(--secondary-color);
    font-weight: bold;
    position: absolute;
    left: 0;
    top: 0;
    font-size: 1.2rem;
}

/* History Section */
.history-section {
    background: white;
}

.history-content {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-left: 4px solid var(--secondary-color);
}

.history-content p {
    color: #666;
    line-height: 1.6;
    font-size: 1rem;
    margin: 0;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .intro-content h2 {
        font-size: 2rem;
    }
    
    .intro-description {
        font-size: 1rem;
    }
    
    .mv-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .services-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .service-card {
        padding: 1.5rem;
    }
    
    .objectives-list,
    .history-content {
        padding: 2rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
}

/* Library Programs Section */
.library-programs-section {
    background: white;
}

.program-carousel-container {
    max-width: 1100px;
    margin: 2rem auto;
    position: relative;
    overflow: visible;
}

.program-carousel {
    overflow: hidden;
    border-radius: 12px;
}

.program-carousel-track {
    display: flex;
    transition: transform 0.6s ease;
    will-change: transform;
}

/* Ensure each slide (or link-wrapped slide) occupies full carousel width */
.program-carousel-track > .program-slide-link,
.program-carousel-track > .program-slide {
    flex: 0 0 100%;
}

.program-slide {
    min-width: 100%;
    display: grid;
    grid-template-columns: 60% 40%;
    gap: 1.5rem;
    align-items: center;
    background: #fff;
    position: relative;
    padding: 1.25rem;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.03);
}

.program-slide { cursor: pointer; }
.program-slide:focus { outline: 3px solid rgba(28,77,161,0.12); outline-offset: 6px; }

.program-image {
    width: 100%;
    aspect-ratio: 16/9;
    overflow: hidden;
    border-radius: 8px;
}

.program-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.program-text h4 {
    margin-top: 0;
    color: var(--primary-color);
    font-size: 1.3rem; /* increased for better visibility in slider */
    margin-bottom: 0.4rem;
}

.program-text p {
    margin: 0;
    color: #555;
    line-height: 1.5;
    font-size: 0.95rem;
    overflow-wrap: anywhere;
}

.program-text {
    min-width: 0;
    padding-right: 0.75rem;
}

.carousel-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.98);
    color: var(--primary-color);
    border: 1px solid rgba(0,0,0,0.06);
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 10px 30px rgba(16,24,40,0.08);
    transition: transform 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
    opacity: 1;
    visibility: visible;
}

.carousel-nav:hover { transform: translateY(-50%) scale(1.04); box-shadow: 0 14px 40px rgba(16,24,40,0.12); }

.carousel-prev { left: -28px; }
.carousel-next { right: -28px; }

.carousel-dots {
    position: absolute;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
}

.carousel-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,0.95);
    border: 1px solid rgba(16,24,40,0.08);
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(16,24,40,0.06);
}

.carousel-dot.active {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

@media (max-width: 768px) {
    .program-slide {
        grid-template-columns: 1fr;
    }
    .carousel-nav { width: 34px; height: 34px; border-radius: 8px; }
    .carousel-prev { left: 8px; }
    .carousel-next { right: 8px; }

    /* slightly smaller title on small screens to maintain layout */
    .program-text h4 { font-size: 1.15rem; }
}

/* Desktop: only show nav arrows when hovering the carousel container */
@media (min-width: 769px) {
    .carousel-nav { opacity: 0; visibility: hidden; }
    .program-carousel:hover .carousel-nav { opacity: 1; visibility: visible; }
}
</style>

<main class="main-content">
    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-logo">
                    <img src="<?php echo $base_path; ?>assets/images/library/logo.png" alt="University Library Logo">
                </div>
                <h2>University Library</h2>
                <p class="intro-description">The Library Services Department manages and provides seamless access to both print and online scholarly information; offers reference services, research assistance, and information literacy instruction; provides excellent facility and equipment. Licensed, professional, and computer-savvy Librarians are always ready to assist library users.</p>
            </div>
        </div>
    </section>

    <script>
    // Library Programs carousel
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('libraryProgramsTrack');
        if (!track) return;

        const slides = Array.from(track.children);
        const prev = document.getElementById('programPrev');
        const next = document.getElementById('programNext');
        const dotsContainer = document.getElementById('programDots');

        let idx = 0;
        let intervalId = null;
        const slideCount = slides.length;

        function update() {
            track.style.transform = `translateX(-${idx * 100}%)`;
            // update dots
            if (dotsContainer) {
                const dots = Array.from(dotsContainer.children);
                dots.forEach((d, i) => d.classList.toggle('active', i === idx));
            }
        }

        function goTo(i) {
            idx = (i + slideCount) % slideCount;
            update();
        }

        function nextSlide() { goTo(idx + 1); }
        function prevSlide() { goTo(idx - 1); }

        // create dots
        if (dotsContainer && slideCount > 1) {
            for (let i = 0; i < slideCount; i++) {
                const btn = document.createElement('button');
                btn.className = 'carousel-dot' + (i === 0 ? ' active' : '');
                btn.addEventListener('click', () => { goTo(i); resetInterval(); });
                dotsContainer.appendChild(btn);
            }
        }

        if (next) next.addEventListener('click', () => { nextSlide(); resetInterval(); });
        if (prev) prev.addEventListener('click', () => { prevSlide(); resetInterval(); });

        function startInterval() {
            if (intervalId) return;
            intervalId = setInterval(nextSlide, 5000);
        }

        function resetInterval() {
            if (intervalId) { clearInterval(intervalId); intervalId = null; }
            startInterval();
        }

        // pause on hover
        const carousel = track.closest('.program-carousel');
        if (carousel) {
            carousel.addEventListener('mouseenter', () => { if (intervalId) clearInterval(intervalId); intervalId = null; });
            carousel.addEventListener('mouseleave', () => { startInterval(); });
        }

        // touch / swipe support for mobile and tablets
        if (carousel && ('ontouchstart' in window || navigator.maxTouchPoints > 0)) {
            let startX = 0;
            let currentX = 0;
            let dragging = false;
            const threshold = 50; // px

            carousel.addEventListener('touchstart', function(e){
                if (e.touches.length !== 1) return;
                startX = e.touches[0].clientX;
                currentX = startX;
                dragging = true;
                if (intervalId) { clearInterval(intervalId); intervalId = null; }
            }, { passive: true });

            carousel.addEventListener('touchmove', function(e){
                if (!dragging) return;
                currentX = e.touches[0].clientX;
                const dx = currentX - startX;
                const pct = (dx / carousel.offsetWidth) * 100;
                track.style.transition = 'none';
                track.style.transform = `translateX(${ -idx * 100 + pct }%)`;
            }, { passive: true });

            carousel.addEventListener('touchend', function(e){
                if (!dragging) return;
                dragging = false;
                track.style.transition = '';
                const dx = currentX - startX;
                if (Math.abs(dx) > threshold) {
                    if (dx < 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                } else {
                    update();
                }
                startInterval();
            });
        }

        // click animation for link-based slides
        document.querySelectorAll('.program-slide-link').forEach(function(a){
            a.addEventListener('click', function(e){
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return; // allow modifier-initiated behavior
                e.preventDefault();
                a.classList.add('clicked');
                const href = a.getAttribute('href');
                setTimeout(()=>{ window.open(href, '_blank'); a.classList.remove('clicked'); }, 140);
                resetInterval();
            });
        });

        // tooltip that follows cursor on hover for link slides
        (function(){
            let tip = null;
            function showTip(text){
                if (!tip) { tip = document.createElement('div'); tip.className = 'program-tooltip'; document.body.appendChild(tip); }
                tip.textContent = text;
                tip.style.display = 'block';
            }
            function moveTip(x,y){ if (!tip) return; const offsetY = 14; tip.style.left = x + 'px'; tip.style.top = (y - offsetY) + 'px'; }
            function hideTip(){ if (!tip) return; tip.style.display = 'none'; }

            document.querySelectorAll('.program-slide-link').forEach(function(a){
                let enabled = false;
                a.addEventListener('mouseenter', function(e){
                    // ignore on touch devices
                    if (('ontouchstart' in window) || navigator.maxTouchPoints > 0) return;
                    showTip('Open in Gdrive');
                    moveTip(e.clientX, e.clientY);
                    enabled = true;
                });
                a.addEventListener('mousemove', function(e){ if (!enabled) return; moveTip(e.clientX, e.clientY); });
                a.addEventListener('mouseleave', function(){ hideTip(); enabled = false; });
            });
        })();

        // add Gdrive icon to CAS slides as well
        (function(){
            const track = document.getElementById('casTrack');
            if (!track) return;
            const slides = track.querySelectorAll('.program-slide');
            slides.forEach(function(slide){
                const parentLink = slide.closest('.program-slide-link');
                const href = parentLink ? parentLink.getAttribute('href') : (slide.getAttribute('data-link') || '');
                if (!href || href === '#' || href.toLowerCase().startsWith('javascript:')) return;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'gdrive-btn';
                btn.setAttribute('aria-label', 'Open in Gdrive');
                btn.title = 'Open in Gdrive';
                btn.innerHTML = '<i class="fab fa-google-drive" aria-hidden="true"></i>';
                btn.addEventListener('click', function(e){
                    e.stopPropagation();
                    try { window.open(href, '_blank', 'noopener'); } catch(err){ window.open(href, '_blank'); }
                });
                slide.appendChild(btn);
            });
        })();

        // add a small Google Drive (Gdrive) icon button to slides that have a real link
        (function(){
            const track = document.getElementById('libraryProgramsTrack');
            if (!track) return;
            const slides = track.querySelectorAll('.program-slide');
            slides.forEach(function(slide){
                const parentLink = slide.closest('.program-slide-link');
                const href = parentLink ? parentLink.getAttribute('href') : (slide.getAttribute('data-link') || '');
                if (!href || href === '#' || href.toLowerCase().startsWith('javascript:')) return;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'gdrive-btn';
                btn.setAttribute('aria-label', 'Open in Gdrive');
                btn.title = 'Open in Gdrive';
                // Use Font Awesome brand icon; fallback text if not available
                btn.innerHTML = '<i class="fab fa-google-drive" aria-hidden="true"></i>';
                btn.addEventListener('click', function(e){
                    e.stopPropagation();
                    try { window.open(href, '_blank', 'noopener'); } catch(err){ window.open(href, '_blank'); }
                });
                slide.appendChild(btn);
            });
        })();

        // initial state
        update();
        if (slideCount > 1) startInterval();
    });
    </script>

    <!-- News Carousel Section -->
    <?php
    $categoryId = 'Library'; // Pass category name, component will look it up
    $sectionTitle = 'Library News & Updates';
    $sectionDescription = 'Stay updated with the latest news and announcements from the University Library.';
    include '../app/includes/news-carousel.php';
    ?>

    

    <!-- Mission and Vision Section -->
    <section class="content-section lib-mission-vision-section ">
        <div class="container">
            <h2 class="section-title">Mission & Vision</h2>
            <div class="mv-container">
                <div class="mv-card">
                    <h3>Vision</h3>
                    <p> The UPHSL University Library envisions to be a pioneering hub of innovative and comprehensive resources and services, leading in both national and global academic communities. </p>
                </div>
                <div class="mv-card">
                    <h3>Mission</h3>
                    <p>Dedicated to empowering users through state-of-the-art library resources and technology-driven services, fostering independent critical thinking, creativity, and lifelong learning in an ever-evolving digital landscape.</p>
                </div>
            </div>
        </div>
    </section>

    <style>
    /* Lightweight modal-free resources: keep card sizing and image behavior */
    .program-image{position:relative;aspect-ratio:16/9;overflow:hidden;background:#f6f6f6}
    .program-image-bg{width:100%;height:100%;background-size:cover;background-position:center;display:block}
    .program-slide-link{display:block;color:inherit;text-decoration:none}
    .program-slide-link .program-slide{transition:transform .18s ease,box-shadow .18s ease}
    .program-slide-link.clicked .program-slide{transform:scale(.985);opacity:.98}
    /* Hover highlight for link-based slides */
    .program-slide-link:hover .program-slide {
        transform: translateY(-6px);
        box-shadow: 0 18px 40px rgba(14,61,170,0.12);
        border-color: rgba(14,61,170,0.12);
    }
    /* Tooltip styling (shown near cursor) */
    .program-tooltip {
        position: fixed;
        display: inline-block;
        padding: 6px 10px;
        background: var(--primary-color, #0e3da5);
        color: #fff;
        font-size: 0.85rem;
        border-radius: 6px;
        pointer-events: none;
        z-index: 4000;
        transform: translate(-50%, -120%);
        white-space: nowrap;
        box-shadow: 0 6px 18px rgba(16,24,40,0.12);
    }

    /* Gdrive icon button placed at top-right of each slide */
    .gdrive-btn {
        position: absolute;
        top: 10px;
        right: 12px;
        z-index: 30;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: rgba(255,255,255,0.98);
        border: 1px solid rgba(16,24,40,0.06);
        color: var(--primary-color);
        cursor: pointer;
        box-shadow: 0 8px 22px rgba(16,24,40,0.08);
        padding: 0;
        font-size: 16px;
    }
    .gdrive-btn i { font-size: 18px; }
    .gdrive-btn:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(16,24,40,0.12); }
    </style>

    

    <!-- Online Services Section -->
    <section class="content-section online-services-section">
        <div class="container">
            <h2 class="section-title">Online Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-image">
                        <img src="<?php echo $base_path; ?>assets/images/support-services/college-library/img/olservices/uphsl-opac.jpg" alt="OPAC">
                    </div>
                    <h4>OPAC - Online Public Access Catalogue</h4>
                    <p>This feature-rich Online LMS, there is never a need to worry on valuable data. All of your data, including archival data, remains instantly accessible all the time—with no system slowdown. Up-to-date information on books, members and status reports is just a click away.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">
                        <img src="<?php echo $base_path; ?>assets/images/support-services/college-library/img/olservices/uphsl-ebsco.png" alt="EBSCOhost">
                    </div>
                    <h4>EBSCOhost</h4>
                    <p>A powerful online reference system accessible via internet. It offers a variety of proprietary full-text databases and popular databases from leading information providers. The comprehensive databases range from general reference collections to specially designed, subject-specific databases for public, academic, medical, corporate, and school libraries.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">
                        <img src="<?php echo $base_path; ?>assets/images/support-services/college-library/img/olservices/uphsl-pej.png" alt="Philippine E-Journals">
                    </div>
                    <h4>Philippine E-Journals</h4>
                    <p>An expanding collection of academic journals that are made accessible globally through a single Web-based platform. It is hosted by C&E Publishing, Inc., a premier educational publisher in the Philippines and a leader in the distribution of integrated information-based solutions.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">
                        <img src="<?php echo $base_path; ?>assets/images/support-services/college-library/img/olservices/starbooks.png" alt="Starbooks">
                    </div>
                    <h4>Starbooks</h4>
                    <p>State of the art facilities to access science and technology information via the STOO portals. A technically-qualified staff will be on hand to assist STARBOOKS users on-site while an online Librarian's HelpDesk service will also be available to answer queries.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">
                        <img src="<?php echo $base_path; ?>assets/images/support-services/college-library/img/olservices/escra.png" alt="eSCRA Online">
                    </div>
                    <h4>eSCRA Online</h4>
                    <p>A Complete Decision from 1901 to the Present. Online Library, Always Updated and Available. Search and Browse Modes Makes it Fast and Intuitive. Smart Searching through Intelligent Fields. TrueCite Technology gives you the same look and feel as the book.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">
                        <img src="<?php echo $base_path; ?>assets/images/support-services/college-library/img/olservices/turnitin.png" alt="Turnitin">
                    </div>
                    <h4>Turnitin</h4>
                    <p>An expanding collection of academic journals that are made accessible globally through a single Web-based platform. It is hosted by C&E Publishing, Inc., a premier educational publisher in the Philippines and a leader in the distribution of integrated information-based solutions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quality Objectives Section -->
    <!-- Library Programs Section -->
    <?php
    // compute whether to show the DB-driven carousel: require DB source, at least one program, and at least one PDF
    $showCarousel = true;
    $useDB = getSetting('library_programs_source', 'static');
    if ($useDB === 'db') {
        try {
            // Determine whether there are any DB-driven programs with a usable link.
            $db = getDBConnection();
            $stmt = $db->query("SELECT COUNT(*) as cnt FROM library_programs WHERE TRIM(COALESCE(link, '')) <> ''");
            $cnt = (int)$stmt->fetchColumn();
            if ($cnt === 0) { $showCarousel = false; }
        } catch (Exception $e) {
            $showCarousel = false;
        }
    }
    if (!empty($showCarousel)): ?>
    <section class="content-section library-programs-section">
        <div class="container">
            <h2 class="section-title">Library Programs</h2>
            <div class="program-carousel-container">
                <div class="program-carousel">
                    <div class="program-carousel-track" id="libraryProgramsTrack">
                        <?php
                        // $useDB already determined above; render DB or static slides below
                        if ($useDB === 'db') {
                            try {
                                // select new fields: include link; only render programs that have a link
                                $stmt = getDBConnection()->query("SELECT id, slug, title, description, image, link FROM library_programs ORDER BY created_at ASC");
                                $rows = $stmt->fetchAll();
                                foreach ($rows as $r) {
                                    $slug = htmlspecialchars($r['slug']);
                                    $title = htmlspecialchars($r['title']);
                                    $desc = htmlspecialchars($r['description']);
                                    $link = trim($r['link'] ?? '');
                                    if (empty($link)) continue; // skip entries without a link
                                    $img = !empty($r['image']) ? $base_path . $r['image'] : ($base_path . 'assets/images/support-services/college-library/img/programs/placeholder.jpg');

                                    $bgStyle = "style=\"background-image:url('" . htmlspecialchars($img, ENT_QUOTES) . "');\"";
                                    // render slide as a clickable link that opens in a new tab
                                    $safeLink = htmlspecialchars($link, ENT_QUOTES);
                                    echo "<a class=\"program-slide-link\" href=\"{$safeLink}\" target=\"_blank\" rel=\"noopener noreferrer\">";
                                    echo "<div class=\"program-slide\">";
                                    echo "<div class=\"program-image\"><div class=\"program-image-bg\" {$bgStyle}></div></div>";
                                    echo "<div class=\"program-text\"><h4>" . $title . "</h4><p>" . $desc . "</p></div>";
                                    echo "</div></a>";
                                }
                            } catch (Exception $e) {
                                // fallback: nothing here, keep static slides below
                            }
                        } else {
                            // static slides (kept as-is above)
                        ?>
                        <a class="program-slide-link" href="#" target="_blank" rel="noopener noreferrer"><div class="program-slide">
                            <div class="program-image">
                                <div class="program-image-bg" style="background-image:url('<?php echo $base_path; ?>assets/images/support-services/college-library/img/programs/free-coffee.jpg')"></div>
                            </div>
                            <div class="program-text">
                                <h4>Free Coffee</h4>
                                <p>The library offers complimentary coffee during study hours to foster a welcoming, focused atmosphere for students and staff. This small but meaningful amenity encourages longer study sessions, peer collaboration, and informal librarian-student interactions that increase resource discovery and support academic success across disciplines.</p>
                            </div>
                        </div></a>

                        <div class="program-slide" data-pdfs='<?php echo listProgramPdfsJson("seed-library", $base_path); ?>'>
                            <div class="program-image">
                                <div class="prog-thumb-loader"><div class="rs-loading"></div></div>
                                <div class="program-image-bg" style="background-image:url('<?php echo $base_path; ?>assets/images/support-services/college-library/img/programs/seed-library.jpg')"></div>
                            </div>
                            <div class="program-text">
                                <h4>Seed Library Program</h4>
                                <p>A curated collection of seeds available for students, faculty, and community members to borrow, plant, and return seeds from their harvests. The program promotes sustainable gardening, biodiversity awareness, and hands-on learning while supporting campus greening projects and offering workshops on seed saving and native planting techniques.</p>
                                <div style="margin-top:0.75rem;">
                                    <button type="button" class="btn resources-btn">Resources</button>
                                </div>
                            </div>
                        </div>

                        <div class="program-slide" data-pdfs='<?php echo listProgramPdfsJson("community-outreach", $base_path); ?>'>
                            <div class="program-image">
                                <div class="prog-thumb-loader"><div class="rs-loading"></div></div>
                                <div class="program-image-bg" style="background-image:url('<?php echo $base_path; ?>assets/images/support-services/college-library/img/programs/community-outreach.jpg')"></div>
                            </div>
                            <div class="program-text">
                                <h4>Community Outreach Program</h4>
                                <p>Library staff partner with local schools, NGOs, and community groups to deliver mobile library services, literacy workshops, and tailored resource sessions. Outreach expands access to information, fosters lifelong learning, and strengthens university-community ties through collaborative events, volunteer opportunities, and shared educational resources.</p>
                                <div style="margin-top:0.75rem;">
                                    <button type="button" class="btn resources-btn">Resources</button>
                                </div>
                            </div>
                        </div>

                        <div class="program-slide" data-pdfs='<?php echo listProgramPdfsJson("international-conference", $base_path); ?>'>
                            <div class="program-image">
                                <div class="prog-thumb-loader"><div class="rs-loading"></div></div>
                                <div class="program-image-bg" style="background-image:url('<?php echo $base_path; ?>assets/images/support-services/college-library/img/programs/international-conference.jpg')"></div>
                            </div>
                            <div class="program-text">
                                <h4>International Collaborative Conference</h4>
                                <p>The library organizes an annual conference bringing together international scholars, librarians, and students to exchange research, best practices, and innovations in information services. The event features keynote speakers, panels, and networking aimed at building research collaborations and elevating the library's global engagement.</p>
                                <div style="margin-top:0.75rem;">
                                    <button type="button" class="btn resources-btn">Resources</button>
                                </div>
                            </div>
                        </div>

                        <div class="program-slide" data-pdfs='<?php echo listProgramPdfsJson("newsletter-reports", $base_path); ?>'>
                            <div class="program-image">
                                <div class="prog-thumb-loader"><div class="rs-loading"></div></div>
                                <div class="program-image-bg" style="background-image:url('<?php echo $base_path; ?>assets/images/support-services/college-library/img/programs/newsletter-reports.jpg')"></div>
                            </div>
                            <div class="program-text">
                                <h4>Library Newsletter and Annual Reports</h4>
                                <p>A periodic newsletter and comprehensive annual reports highlight library initiatives, program outcomes, acquisitions, and impact metrics. Distributed digitally and in print, these publications keep stakeholders informed, celebrate achievements, and guide strategic planning by presenting data-driven narratives about services and user engagement.</p>
                                <div style="margin-top:0.75rem;">
                                    <button type="button" class="btn resources-btn">Resources</button>
                                </div>
                            </div>
                        </div>
                        <?php } // end static fallback ?>
                    </div>

                    <button type="button" aria-label="Previous program" class="carousel-nav carousel-prev" id="programPrev">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </button>
                    <button type="button" aria-label="Next program" class="carousel-nav carousel-next" id="programNext">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </button>
                    <div class="carousel-dots" id="programDots"></div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Current Awareness Services (CAS) Section -->
    <?php
    $showCas = true;
    $useCasDB = getSetting('library_cas_source', 'static');
    if ($useCasDB === 'db') {
        try {
            $stmt = $db->query("SELECT COUNT(*) as cnt FROM library_cas WHERE TRIM(COALESCE(link, '')) <> ''");
            $cnt = (int)$stmt->fetchColumn();
            if ($cnt === 0) { $showCas = false; }
        } catch (Exception $e) {
            $showCas = false;
        }
    }
    if (!empty($showCas)): ?>
    <section class="content-section library-programs-section">
        <div class="container">
            <h2 class="section-title">Current Awareness Services</h2>
            <div class="program-carousel-container">
                <div class="program-carousel">
                    <div class="program-carousel-track" id="casTrack">
                        <?php
                        if ($useCasDB === 'db') {
                            try {
                                $stmt = getDBConnection()->query("SELECT id, slug, title, description, image, link FROM library_cas ORDER BY created_at ASC");
                                $rows = $stmt->fetchAll();
                                foreach ($rows as $r) {
                                    $slug = htmlspecialchars($r['slug']);
                                    $title = htmlspecialchars($r['title']);
                                    $desc = htmlspecialchars($r['description']);
                                    $link = trim($r['link'] ?? '');
                                    if (empty($link)) continue;
                                    $img = !empty($r['image']) ? $base_path . $r['image'] : ($base_path . 'assets/images/support-services/college-library/img/programs/placeholder.jpg');
                                    $bgStyle = "style=\"background-image:url('" . htmlspecialchars($img, ENT_QUOTES) . "');\"";
                                    $safeLink = htmlspecialchars($link, ENT_QUOTES);
                                    echo "<a class=\"program-slide-link\" href=\"{$safeLink}\" target=\"_blank\" rel=\"noopener noreferrer\">";
                                    echo "<div class=\"program-slide\">";
                                    echo "<div class=\"program-image\"><div class=\"program-image-bg\" {$bgStyle}></div></div>";
                                    echo "<div class=\"program-text\"><h4>" . $title . "</h4><p>" . $desc . "</p></div>";
                                    echo "</div></a>";
                                }
                            } catch (Exception $e) {
                                // fallback to static
                            }
                        }
                        // static fallback (renders if DB not used or empty)
                        if ($useCasDB !== 'db') {
                        ?>
                        <div class="program-slide">
                            <div class="program-image">
                                <div class="program-image-bg" style="background-image:url('<?php echo $base_path; ?>assets/images/support-services/college-library/img/programs/cas-sample.jpg')"></div>
                            </div>
                            <div class="program-text">
                                <h4>Current Awareness Briefs</h4>
                                <p>Curated alerts and brief summaries of newly acquired resources, journals, and noteworthy research items to keep faculty and students current in their fields.</p>
                            </div>
                        </div>
                        <?php } // end static fallback ?>
                    </div>

                    <button type="button" aria-label="Previous CAS" class="carousel-nav carousel-prev" id="casPrev">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </button>
                    <button type="button" aria-label="Next CAS" class="carousel-nav carousel-next" id="casNext">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </button>
                    <div class="carousel-dots" id="casDots"></div>
                </div>
            </div>
        </div>
    </section>

    <script>
    // CAS carousel (similar behavior to Library Programs carousel)
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('casTrack');
        if (!track) return;

        const slides = Array.from(track.children);
        const prev = document.getElementById('casPrev');
        const next = document.getElementById('casNext');
        const dotsContainer = document.getElementById('casDots');

        let idx = 0;
        let intervalId = null;
        const slideCount = slides.length;

        function update() {
            track.style.transform = `translateX(-${idx * 100}%)`;
            if (dotsContainer) {
                const dots = Array.from(dotsContainer.children);
                dots.forEach((d, i) => d.classList.toggle('active', i === idx));
            }
        }

        function goTo(i) { idx = (i + slideCount) % slideCount; update(); }
        function nextSlide() { goTo(idx + 1); }
        function prevSlide() { goTo(idx - 1); }

        if (dotsContainer && slideCount > 1) {
            for (let i = 0; i < slideCount; i++) {
                const btn = document.createElement('button');
                btn.className = 'carousel-dot' + (i === 0 ? ' active' : '');
                btn.addEventListener('click', () => { goTo(i); resetInterval(); });
                dotsContainer.appendChild(btn);
            }
        }

        if (next) next.addEventListener('click', () => { nextSlide(); resetInterval(); });
        if (prev) prev.addEventListener('click', () => { prevSlide(); resetInterval(); });

        function startInterval() { if (intervalId) return; intervalId = setInterval(nextSlide, 5000); }
        function resetInterval() { if (intervalId) { clearInterval(intervalId); intervalId = null; } startInterval(); }

        const carousel = track.closest('.program-carousel');
        if (carousel) {
            carousel.addEventListener('mouseenter', () => { if (intervalId) clearInterval(intervalId); intervalId = null; });
            carousel.addEventListener('mouseleave', () => { startInterval(); });
        }

        // touch support
        if (carousel && ('ontouchstart' in window || navigator.maxTouchPoints > 0)) {
            let startX = 0, currentX = 0, dragging = false, threshold = 50;
            carousel.addEventListener('touchstart', function(e){ if (e.touches.length !== 1) return; startX = e.touches[0].clientX; currentX = startX; dragging = true; if (intervalId) { clearInterval(intervalId); intervalId = null; } }, { passive: true });
            carousel.addEventListener('touchmove', function(e){ if (!dragging) return; currentX = e.touches[0].clientX; const dx = currentX - startX; const pct = (dx / carousel.offsetWidth) * 100; track.style.transition = 'none'; track.style.transform = `translateX(${ -idx * 100 + pct }%)`; }, { passive: true });
            carousel.addEventListener('touchend', function(){ if (!dragging) return; dragging = false; track.style.transition = ''; const dx = currentX - startX; if (Math.abs(dx) > threshold) { if (dx < 0) nextSlide(); else prevSlide(); } else { update(); } startInterval(); });
        }

        // initial
        update(); if (slideCount > 1) startInterval();
    });
    </script>
    <?php endif; ?>

    <style>
    /* Objectives cards: two-column layout that stacks on small screens */
    .objectives-cards {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        max-width: 1100px;
        margin: 0 auto;
        align-items: start;
    }

    .objective-card {
        background: #ffffff;
        padding: 1.75rem;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(16,24,40,0.06);
        border-left: 4px solid var(--secondary-color);
    }

    .objective-card h3 { margin-top: 0; color: var(--primary-color); font-size: 1.25rem; font-weight: 700; }

    /* Readability improvements for objective lists */
    .objective-card ul { list-style: none; padding: 0; margin: 0; }
    .objective-card li {
        position: relative;
        padding-left: 2.4rem;
        margin-bottom: 1rem;
        line-height: 1.45;
        font-size: 0.98rem;
        color: #333;
    }
    .objective-card li::before {
        content: "\2713"; /* checkmark */
        position: absolute;
        left: 0;
        top: 0.12rem;
        color: var(--secondary-color);
        font-weight: 700;
        font-size: 1rem;
    }
    .objective-card li strong { display: inline-block; color: var(--primary-color); margin-right: 0.4rem; font-weight:700; }
    .objective-card li p { display: inline; margin: 0; }

    @media (max-width: 768px) {
        .objectives-cards { grid-template-columns: 1fr; }
    }
    </style>

    <section class="content-section quality-objectives-section">
        <div class="container">
            <div class="objectives-cards">
                <div class="objective-card">
                    <h3>General Objectives</h3>
                        <ul>
                            <li>Enhance the continuous collection with cutting-edge, diverse, and inclusive materials that support the evolving curriculum and research needs.</li>
                            <li>Implement advanced technologies and innovative services to enhance user experience, accessibility, and engagement.</li>
                            <li>Promote information literacy and digital fluency through targeted programs, workshops, and personalized support.</li>
                            <li>Strengthen collaborations with local and international institutions to share knowledge, resources, and best practices.</li>
                            <li>Integrate sustainable practices in library operations, focusing on eco-friendly initiatives and long-term resource management.</li>
                            <li>Invest in the continuous professional growth of library staff through training, seminars, and exposure to global trends and innovations.</li>
                            <li>Engage with the university community and beyond through outreach programs, cultural events, and support for community-driven projects.</li>
                        </ul>
                </div>

                <div class="objective-card">
                    <h3>Educational Organizations Management System Quality Objectives</h3>
                    <ul>
                        <li>To innovate access to resources and services that enhance users' technical and professional competencies aligned with Perpetualite values.</li>
                        <li>To provide organized and updated references and academic materials that support compliance and align with national and international educational standards.</li>
                        <li>To establish library collaborations and linkages that expand resource access and improve service delivery.</li>
                        <li>To support knowledge generation, dissemination, and utilization for academic and research excellence.</li>
                        <li>To provide relevant library services that contribute to uplifting the quality of life in adopted communities.</li>
                        <li>To adopt cutting-edge technologies and innovations that ensure relevant and efficient library services.</li>
                        <li>To promote responsible knowledge sharing, research dissemination, and protection of intellectual property rights.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- History Section -->
    <section class="content-section history-section">
        <div class="container">
            <h2 class="section-title">History</h2>
            <div class="history-content">
                <p>Through the STARBOOKS Program, Filipinos can have access to scientific information for their research needs or simply satisfy their curious minds. Eventually, it is hoped that (1) it will create interest in the field of Science and Technology which may increase the number of Filipinos enrolling in S&T courses, (2) encourage great and curious minds to develop new ideas - inventions and innovations, and (3) inspire one's capacity for entrepreneurship and research for socio-economic development.</p>
            </div>
        </div>
    </section>
</main>

<?php include '../app/includes/footer.php'; ?>
