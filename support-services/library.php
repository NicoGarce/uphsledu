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

<?php
// Helper: list PDFs for a program slug and return JSON array string usable in data-pdfs
function listProgramPdfsJson($slug, $base_path) {
    $dir = __DIR__ . '/../assets/documents/library/programs/' . $slug . '/';
    $items = [];
    if (is_dir($dir)) {
        $files = scandir($dir);
        $pdfs = [];
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            $full = $dir . $f;
            if (!is_file($full)) continue;
            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
            if ($ext !== 'pdf') continue;
            $pdfs[] = ['file' => $f, 'mtime' => filemtime($full)];
        }
        // sort by modification time desc (newest first)
        usort($pdfs, function($a, $b) { return $b['mtime'] <=> $a['mtime']; });
        foreach ($pdfs as $p) {
            $f = $p['file'];
            $title = preg_replace('/[_\-]+/', ' ', pathinfo($f, PATHINFO_FILENAME));
            $item = ['title' => $title, 'url' => $base_path . 'assets/documents/library/programs/' . $slug . '/' . rawurlencode($f)];
            // if a server-generated thumbnail JPEG exists next to the PDF, include it to speed up rendering
            $thumbPath = $dir . pathinfo($f, PATHINFO_FILENAME) . '.jpg';
            if (is_file($thumbPath)) {
                $item['thumb'] = $base_path . 'assets/documents/library/programs/' . $slug . '/' . rawurlencode(pathinfo($f, PATHINFO_FILENAME) . '.jpg');
            }
            $items[] = $item;
        }
    }
    return json_encode($items);
}
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
    background: white;
}

.content-section:nth-child(even) {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 1rem;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: var(--secondary-color);
    border-radius: 2px;
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
.mission-vision-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.mv-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    max-width: 1000px;
    margin: 0 auto;
}

.mv-card {
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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

.program-slide {
    min-width: 100%;
    display: grid;
    grid-template-columns: 60% 40%;
    gap: 1.5rem;
    align-items: center;
    background: #fff;
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
    font-size: 1.05rem;
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
    transition: transform 0.18s ease, box-shadow 0.18s ease;
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

    <!-- Library Programs Section -->
    <?php
    // compute whether to show the DB-driven carousel: require DB source, at least one program, and at least one PDF
    $showCarousel = true;
    $useDB = getSetting('library_programs_source', 'static');
    if ($useDB === 'db') {
        try {
            // If any program has no PDFs, hide the entire carousel (strict requirement)
            $db = getDBConnection();
            $stmt = $db->query('SELECT slug FROM library_programs');
            $rows = $stmt->fetchAll();
            if (empty($rows)) {
                $showCarousel = false;
            } else {
                foreach ($rows as $r) {
                    $slug = $r['slug'];
                    $pdfsJson = listProgramPdfsJson($slug, $base_path);
                    $arr = json_decode($pdfsJson, true);
                    if (empty($arr)) { $showCarousel = false; break; }
                }
            }
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
                                $stmt = getDBConnection()->query("SELECT slug, title, description, image FROM library_programs ORDER BY created_at ASC");
                                $rows = $stmt->fetchAll();
                                foreach ($rows as $r) {
                                    $slug = htmlspecialchars($r['slug']);
                                    $title = htmlspecialchars($r['title']);
                                    $desc = htmlspecialchars($r['description']);
                                        $img = !empty($r['image']) ? $base_path . $r['image'] : ($base_path . 'assets/images/support-services/college-library/img/programs/placeholder.jpg');
                                        $pdfsJson = listProgramPdfsJson($slug, $base_path);
                                        // if the newest PDF has a server thumbnail, use it as the program image and mark the container so client-side PDF.js rendering skips work
                                        $hasThumbClass = '';
                                        $imgAttr = '';
                                        $pdfsArr = json_decode($pdfsJson, true);
                                        if (!empty($pdfsArr) && !empty($pdfsArr[0]['thumb'])) {
                                            $img = $pdfsArr[0]['thumb'];
                                            $hasThumbClass = ' has-thumb';
                                            $imgAttr = ' data-pdf-thumb="1"';
                                        }
                                        // render image as background to avoid browser alt-text showing when file missing
                                        $bgStyle = "style=\"background-image:url('" . htmlspecialchars($img, ENT_QUOTES) . "');\"";
                                        echo "<div class=\"program-slide\" data-pdfs='" . htmlspecialchars($pdfsJson, ENT_QUOTES) . "'>";
                                        echo "<div class=\"program-image{$hasThumbClass}\"><div class=\"prog-thumb-loader\"><div class=\"rs-loading\"></div></div><div class=\"program-image-bg\" {$bgStyle} {$imgAttr}></div></div>";
                                    echo "<div class=\"program-text\"><h4>" . $title . "</h4><p>" . $desc . "</p><div style=\"margin-top:0.75rem;\"><button type=\"button\" class=\"btn resources-btn\">Resources</button></div></div></div>";
                                }
                            } catch (Exception $e) {
                                // fallback: nothing here, keep static slides below
                            }
                        } else {
                            // static slides (kept as-is above)
                        ?>
                        <div class="program-slide" data-pdfs='<?php echo listProgramPdfsJson("free-coffee", $base_path); ?>'>
                            <div class="program-image">
                                <div class="prog-thumb-loader"><div class="rs-loading"></div></div>
                                <div class="program-image-bg" style="background-image:url('<?php echo $base_path; ?>assets/images/support-services/college-library/img/programs/free-coffee.jpg')"></div>
                            </div>
                            <div class="program-text">
                                <h4>Free Coffee</h4>
                                <p>The library offers complimentary coffee during study hours to foster a welcoming, focused atmosphere for students and staff. This small but meaningful amenity encourages longer study sessions, peer collaboration, and informal librarian-student interactions that increase resource discovery and support academic success across disciplines.</p>
                                <div style="margin-top:0.75rem;">
                                    <button type="button" class="btn resources-btn">Resources</button>
                                </div>
                            </div>
                        </div>

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

    <!-- Mission and Vision Section -->
    <section class="content-section mission-vision-section">
        <div class="container">
            <h2 class="section-title">Mission & Vision</h2>
            <div class="mv-container">
                <div class="mv-card">
                    <h3>Vision</h3>
                    <p>A dominant university library provider in global community</p>
                </div>
                <div class="mv-card">
                    <h3>Mission</h3>
                    <p>Committed to provide users with comprehensive resources and services as tools for independent critical thinking and life-long learning.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Resources Modal -->
    <div id="resourcesModal" class="rs-modal" aria-hidden="true">
        <div class="rs-modal-backdrop"></div>
        <div class="rs-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="rsModalTitle">
            <header class="rs-modal-header">
                <h3 id="rsModalTitle">Program Resources</h3>
                <button type="button" id="rsModalClose" aria-label="Close resources">✕</button>
            </header>
            <div class="rs-modal-body">
                    <div id="rsResourcesGrid" class="rs-resources-grid"></div>
                </div>
                <!-- In-modal PDF viewer overlay -->
                <div id="rsViewer" class="rs-viewer" aria-hidden="true">
                    <div class="rs-viewer-header">
                        <strong id="rsViewerTitle">Preview</strong>
                        <button type="button" id="rsViewerClose" aria-label="Close preview">✕</button>
                    </div>
                    <iframe id="rsViewerFrame" class="rs-viewer-frame" src="" title="PDF preview"></iframe>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* Modal for resources */
    .rs-modal { display: none; position: fixed; inset: 0; z-index: 1200; }
    .rs-modal[aria-hidden="false"] { display: block; }
    .rs-modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.45); }
    .rs-modal-dialog { position: absolute; left: 50%; top: 50%; transform: translate(-50%,-50%); width: 95%; max-width: 1000px; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 20px 60px rgba(2,6,23,0.3); }
    .rs-modal-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-bottom: 1px solid #eee; }
    .rs-modal-header h3 { margin: 0; font-size: 1.125rem; }
    .rs-modal-header button { background: transparent; border: none; font-size: 1.25rem; cursor: pointer; }
    .rs-modal-body { padding: 1rem; max-height: 70vh; overflow: auto; }
    .rs-resources-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
    .rs-resource { background: #fff; border-radius: 10px; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.04); display: flex; flex-direction: column; gap: 0.5rem; align-items: stretch; min-height: 220px; }
    .rs-thumb { width: 100%; aspect-ratio: 3/4; background: #f4f6f8; display: flex; align-items: center; justify-content: center; border-radius: 6px; overflow: hidden; }
    .rs-thumb canvas { width: 100%; height: 100%; object-fit: cover; display: block; }
    .rs-resource-title { font-size: 0.95rem; color: #222; margin: 0; }
    .rs-actions { display: flex; gap: 0.5rem; margin-top: auto; }
    .rs-actions a, .rs-actions button { padding: 0.45rem 0.6rem; text-decoration: none; border-radius: 8px; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.06); background: white; color: var(--primary-color); cursor: pointer; }
    .rs-loading { display: inline-block; width: 28px; height: 28px; border-radius: 50%; border: 3px solid rgba(0,0,0,0.08); border-top-color: var(--primary-color); animation: rs-spin 1s linear infinite; }
    @keyframes rs-spin { to { transform: rotate(360deg); } }
    .rs-pagination { display:flex; gap:8px; justify-content:center; align-items:center; padding:0.75rem 1rem; border-top:1px solid #eee; background:#fafafa; }
    .rs-page-btn { padding:6px 10px; border-radius:8px; border:1px solid rgba(0,0,0,0.06); background:white; color:var(--primary-color); cursor:pointer; }
    .rs-page-btn.active { background:var(--primary-color); color:white; border-color:var(--primary-color); }

    /* In-modal PDF viewer (overlay inside dialog) */
    .rs-viewer { position: absolute; inset: 0; background: #fff; z-index: 12; display: none; flex-direction: column; }
    .rs-viewer[aria-hidden="false"] { display: flex; }
    .rs-viewer-header { display:flex; align-items:center; justify-content:space-between; padding:0.5rem 0.75rem; border-bottom:1px solid #eee; }
    .rs-viewer-frame { width:100%; height: calc(70vh - 48px); border: none; }

    /* program card loader */
    .program-image { position: relative; }
    .prog-thumb-loader { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.6); }
    .prog-thumb-loader .rs-loading { width: 36px; height: 36px; border-width: 4px; }
    .program-image.has-thumb .prog-thumb-loader { display: none; }

    @media (max-width: 900px) {
        .rs-resources-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 560px) {
        .rs-resources-grid { grid-template-columns: 1fr; }
    }
    </style>

    <!-- PDF.js from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

    <script>
    // Resources modal + PDF first-page thumbnails using PDF.js
    document.addEventListener('DOMContentLoaded', function() {
        // configure worker
        if (window['pdfjsLib']) {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
        }

        const modal = document.getElementById('resourcesModal');
        const modalClose = document.getElementById('rsModalClose');
        const resourcesGrid = document.getElementById('rsResourcesGrid');

        // pagination state
        let modalItems = [];
        let currentPage = 0;
        const PAGE_SIZE = 9;

        // in-modal viewer elements
        const viewer = document.getElementById('rsViewer');
        const viewerClose = document.getElementById('rsViewerClose');
        const viewerFrame = document.getElementById('rsViewerFrame');
        const viewerTitle = document.getElementById('rsViewerTitle');

        function openViewer(url, title) {
            if (!viewer) return;
            viewer.setAttribute('aria-hidden', 'false');
            viewerFrame.src = url;
            if (viewerTitle && title) viewerTitle.textContent = title;
            viewerClose.focus();
        }

        function closeViewer() {
            if (!viewer) return;
            viewer.setAttribute('aria-hidden', 'true');
            viewerFrame.src = '';
        }

        function renderPage(pageIndex) {
            currentPage = pageIndex;
            resourcesGrid.innerHTML = '';
            const start = pageIndex * PAGE_SIZE;
            const slice = modalItems.slice(start, start + PAGE_SIZE);

            if (slice.length === 0) {
                resourcesGrid.innerHTML = '<p>No resources available.</p>';
            }

            slice.forEach(item => {
                const node = document.createElement('div');
                node.className = 'rs-resource';

                const thumb = document.createElement('div');
                thumb.className = 'rs-thumb';
                const loader = document.createElement('div'); loader.className = 'rs-loading';
                thumb.appendChild(loader);

                const title = document.createElement('p'); title.className = 'rs-resource-title'; title.textContent = item.title || 'Document';
                // hide title during thumbnail loading; reveal after success/failure
                title.style.display = 'none';

                const actions = document.createElement('div'); actions.className = 'rs-actions';
                const view = document.createElement('a'); view.href = item.url; view.textContent = 'View';
                const openNew = document.createElement('a'); openNew.href = item.url; openNew.target = '_blank'; openNew.rel = 'noopener'; openNew.textContent = 'Open';
                const dl = document.createElement('a'); dl.href = item.url; dl.download = ''; dl.textContent = 'Download';
                actions.appendChild(view); actions.appendChild(openNew); actions.appendChild(dl);

                node.appendChild(thumb);
                node.appendChild(title);
                node.appendChild(actions);
                resourcesGrid.appendChild(node);

                // helper: show a simple 'unavailable' placeholder
                const showUnavailable = () => {
                    if (loader && loader.parentNode) loader.parentNode.removeChild(loader);
                    const err = document.createElement('div'); err.textContent = 'Preview unavailable'; err.style.color = '#666'; err.style.fontSize = '0.9rem'; err.style.padding = '0.25rem';
                    thumb.appendChild(err);
                    title.style.display = '';
                };

                // helper: render using PDF.js
                const renderPdfThumb = async () => {
                    if (!window['pdfjsLib']) return showUnavailable();
                    try {
                        const loadingTask = pdfjsLib.getDocument(item.url);
                        const pdf = await loadingTask.promise;
                        const page = await pdf.getPage(1);
                        const viewport = page.getViewport({ scale: 1 });

                        const dpr = window.devicePixelRatio || 1;
                        const targetWidthCSS = Math.min(420, Math.max(120, thumb.clientWidth || 160));
                        const targetWidth = Math.max(120, Math.round(targetWidthCSS * dpr));
                        const targetHeight = Math.round(targetWidth * 4 / 3);

                        const scale = Math.max(targetWidth / viewport.width, targetHeight / viewport.height);
                        const vp = page.getViewport({ scale: scale });

                        const off = document.createElement('canvas');
                        off.width = Math.round(vp.width);
                        off.height = Math.round(vp.height);
                        const offCtx = off.getContext('2d');
                        await page.render({ canvasContext: offCtx, viewport: vp }).promise;

                        const canvas = document.createElement('canvas');
                        canvas.width = targetWidth;
                        canvas.height = targetHeight;
                        canvas.style.width = '100%';
                        canvas.style.height = '100%';
                        const ctx = canvas.getContext('2d');

                        const sx = Math.max(0, Math.round((off.width - canvas.width) / 2));
                        const sy = 0;
                        ctx.drawImage(off, sx, sy, canvas.width, canvas.height, 0, 0, canvas.width, canvas.height);

                        if (loader && loader.parentNode) loader.parentNode.removeChild(loader);
                        thumb.appendChild(canvas);
                        thumb.style.cursor = 'pointer';
                        thumb.addEventListener('click', function() { openViewer(item.url, item.title || 'Preview'); });
                        title.style.display = '';
                    } catch (e) {
                        if (loader && loader.parentNode) loader.parentNode.removeChild(loader);
                        showUnavailable();
                    }
                };

                // prefer server-generated JPEG thumb if available; fall back to PDF.js
                if (item.thumb) {
                    const img = document.createElement('img');
                    img.src = item.thumb;
                    img.alt = item.title || '';
                    img.style.width = '100%'; img.style.height = '100%'; img.style.objectFit = 'cover';
                    img.onload = function() {
                        if (loader && loader.parentNode) loader.parentNode.removeChild(loader);
                        thumb.appendChild(img);
                        thumb.style.cursor = 'pointer';
                        thumb.addEventListener('click', function() { openViewer(item.url, item.title || 'Preview'); });
                        title.style.display = '';
                    };
                    img.onerror = function() { renderPdfThumb(); };
                } else {
                    renderPdfThumb();
                }

                // wire actions: open in modal viewer for 'View', keep download as-is
                view.addEventListener('click', function(e) { e.preventDefault(); openViewer(item.url, item.title || 'Preview'); });
            });

            renderPagination();
        }

        function openModalForSlide(slide) {
            const raw = slide.getAttribute('data-pdfs') || '[]';
            try { modalItems = JSON.parse(raw); } catch(e) { modalItems = []; }
            currentPage = 0;
            renderPage(0);

            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden'; // prevent background scroll
            // focus first close button
            modalClose.focus();
        }

        function closeModal() {
            modal.setAttribute('aria-hidden', 'true');
            resourcesGrid.innerHTML = '';
            document.body.style.overflow = '';
        }

        function renderPagination() {
            // remove existing pagination if any
            let existing = modal.querySelector('.rs-pagination');
            if (existing) existing.remove();

            const pages = Math.ceil(modalItems.length / PAGE_SIZE);
            if (pages <= 1) return;

            const pager = document.createElement('div');
            pager.className = 'rs-pagination';

            const prevBtn = document.createElement('button'); prevBtn.className = 'rs-page-btn'; prevBtn.textContent = 'Prev';
            prevBtn.disabled = (currentPage === 0);
            prevBtn.addEventListener('click', () => { renderPage(Math.max(0, currentPage - 1)); });
            pager.appendChild(prevBtn);

            // page numbers (max show 5 around current)
            const maxShow = 5; const half = Math.floor(maxShow/2);
            let start = Math.max(0, currentPage - half);
            let end = Math.min(pages - 1, start + maxShow - 1);
            if (end - start < maxShow - 1) start = Math.max(0, end - (maxShow - 1));

            for (let i = start; i <= end; i++) {
                const p = document.createElement('button'); p.className = 'rs-page-btn' + (i === currentPage ? ' active' : ''); p.textContent = (i+1);
                p.addEventListener('click', () => { renderPage(i); });
                pager.appendChild(p);
            }

            const nextBtn = document.createElement('button'); nextBtn.className = 'rs-page-btn'; nextBtn.textContent = 'Next';
            nextBtn.disabled = (currentPage >= pages - 1);
            nextBtn.addEventListener('click', () => { renderPage(Math.min(pages-1, currentPage + 1)); });
            pager.appendChild(nextBtn);

            modal.querySelector('.rs-modal-dialog').appendChild(pager);
        }

        // wire up resource buttons
        document.querySelectorAll('.program-slide .resources-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const slide = e.currentTarget.closest('.program-slide');
                if (!slide) return;
                openModalForSlide(slide);
            });
        });

        // make whole slide clickable (but ignore clicks on inner controls/links)
        document.querySelectorAll('.program-slide').forEach(slide => {
            slide.tabIndex = 0;
            slide.setAttribute('role', 'button');
            slide.addEventListener('click', function(e) {
                // if the user clicked a control or link inside the slide, don't open modal
                if (e.target.closest('.resources-btn') || e.target.closest('a') || e.target.closest('button')) return;
                openModalForSlide(slide);
            });
            slide.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openModalForSlide(slide);
                }
            });
        });

        modalClose.addEventListener('click', closeModal);
        modal.querySelector('.rs-modal-backdrop').addEventListener('click', closeModal);
        if (viewerClose) viewerClose.addEventListener('click', closeViewer);
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (viewer && viewer.getAttribute('aria-hidden') === 'false') { closeViewer(); return; }
                closeModal();
            }
        });
    });
    </script>

    <style>
    /* Ensure program image area preserves 16:9 and prevents stretching on small screens */
    .program-image{position:relative;aspect-ratio:16/9;overflow:hidden;background:#f6f6f6}
    .program-image-bg, .program-image canvas{width:100%;height:100%;object-fit:cover;display:block;background-size:cover;background-position:center}
    .program-image .program-image-bg { width:100%; height:100%; }

    /* Mobile: reduce title and description sizes for readability */
    @media (max-width:600px){
        .program-slide .program-title{font-size:1rem}
        .program-slide .program-desc{font-size:0.85rem}
        .program-slide .program-desc p{line-height:1.25}
        .program-nav-button{top:50%}
    }
    </style>

    <script>
    // Render newest PDF first page as the program card image (16:9) using PDF.js
    document.addEventListener('DOMContentLoaded', function() {
        if (!window['pdfjsLib']) return;
        const slides = document.querySelectorAll('.program-slide');
        slides.forEach(async (slide) => {
            try {
                const raw = slide.getAttribute('data-pdfs') || '[]';
                const items = JSON.parse(raw);
                if (!items || items.length === 0) return;
                const first = items[0];
                if (!first || !first.url) return;

                const container = slide.querySelector('.program-image');
                if (!container) return;
                // if a server-generated thumbnail image is present (container has has-thumb class), prefer it
                const bgDiv = container.querySelector('.program-image-bg');
                if (container.classList && container.classList.contains('has-thumb')) {
                    // check whether the background image actually resolves; if not, fall back to client rendering
                    let bgOk = false;
                    if (bgDiv) {
                        const bg = window.getComputedStyle(bgDiv).backgroundImage || '';
                        if (bg && bg !== 'none' && bg.indexOf('placeholder') === -1) bgOk = true;
                    }
                    if (bgOk) {
                        const l = container.querySelector('.prog-thumb-loader'); if (l) l.style.display = 'none';
                        return;
                    }
                    // fall through to client-side rendering if background missing or placeholder
                }
                // show loader while rendering
                let cardLoader = container.querySelector('.prog-thumb-loader');
                if (!cardLoader) {
                    cardLoader = document.createElement('div'); cardLoader.className = 'prog-thumb-loader'; const spin = document.createElement('div'); spin.className = 'rs-loading'; cardLoader.appendChild(spin); container.appendChild(cardLoader);
                }

                const rect = container.getBoundingClientRect();
                const dpr = window.devicePixelRatio || 1;
                const targetWidthCSS = rect.width || 320;
                const targetWidth = Math.max(320, Math.round(targetWidthCSS * dpr));
                const targetHeight = Math.round(targetWidth * 9 / 16);

                // load PDF and first page
                const loading = pdfjsLib.getDocument(first.url);
                const pdf = await loading.promise;
                const page = await pdf.getPage(1);
                const viewport = page.getViewport({ scale: 1 });

                // scale so the rendered page fully covers the target 16:9 area
                const scale = Math.max(targetWidth / viewport.width, targetHeight / viewport.height);
                const vp = page.getViewport({ scale: scale });

                // render to an offscreen canvas at device pixels
                const off = document.createElement('canvas');
                off.width = Math.round(vp.width);
                off.height = Math.round(vp.height);
                const offCtx = off.getContext('2d');
                await page.render({ canvasContext: offCtx, viewport: vp }).promise;

                // final canvas sized to target (device pixels)
                const canvas = document.createElement('canvas');
                canvas.width = targetWidth;
                canvas.height = targetHeight;
                // show canvas responsive to its container which enforces 16:9
                canvas.style.width = '100%';
                canvas.style.height = '100%';
                canvas.alt = first.title || '';
                const ctx = canvas.getContext('2d');

                // top-crop from the offscreen render into the final 16:9 canvas
                // horizontally center, but crop from the top (sy = 0) to keep page header visible
                const sx = Math.max(0, Math.round((off.width - canvas.width) / 2));
                const sy = 0;
                ctx.drawImage(off, sx, sy, canvas.width, canvas.height, 0, 0, canvas.width, canvas.height);

                // Hide existing background image div if present, append canvas and remove loader
                if (bgDiv) bgDiv.style.display = 'none';
                container.appendChild(canvas);
                if (cardLoader && cardLoader.parentNode) cardLoader.parentNode.removeChild(cardLoader);
            } catch (e) {
                console.warn('Program card thumbnail render failed', e);
                // ensure loader removed on failure
                const cardLoader = slide.querySelector('.prog-thumb-loader'); if (cardLoader && cardLoader.parentNode) cardLoader.parentNode.removeChild(cardLoader);
            }
        });
    });
    </script>

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
    <section class="content-section quality-objectives-section">
        <div class="container">
            <h2 class="section-title">Quality Objectives</h2>
            <div class="objectives-list">
                <ul>
                    <li>To develop better access to resources and services.</li>
                    <li>To collaborate with all stakeholders on their learning needs and experience.</li>
                    <li>To assist the faculty in updating references for their course syllabi.</li>
                    <li>To disseminate new services and promote their use.</li>
                    <li>To use feedback for continual improvement.</li>
                    <li>To expand proactive library user education and information literacy program.</li>
                </ul>
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
