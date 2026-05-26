<?php
/**
 * UPHSL Research Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about research programs and initiatives at UPHSL
 */
session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or Support Services section is in maintenance
if (isSectionInMaintenance('support-services', 'research') || isSectionInMaintenance('support-services')) {
    $page_title = "Research & Publication - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('support-services', $base_path, 'research')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$base_path = '../';
$page_title = "Research & Publication - UPHSL";

// Function to find PDF file for a research title
function findResearchPDF($title, $department) {
    global $base_path;
    
    // Map department names to folder names
    $departmentFolders = [
        'arts-science' => 'Cas',
        'criminology' => 'Criminology',
        'graduate-school' => 'Graduate School',
        'business-accountancy' => 'Business-Accountancy',
        'education' => 'Education',
        'hospitality' => 'Intl Hospitality Management',
        'computer-studies' => 'Computer Studies',
        'engineering' => 'Engineering',
        'maritime' => 'Maritime'
    ];
    
    $folder = $departmentFolders[$department] ?? '';
    if (empty($folder)) {
        return null;
    }
    
    $pdfDir = __DIR__ . '/../assets/documents/pdfs/researches/' . $folder . '/';
    
    if (!is_dir($pdfDir)) {
        return null;
    }
    
    // Normalize title for matching (remove extra spaces, convert to uppercase, remove special chars)
    $normalizedTitle = strtoupper(trim(preg_replace('/\s+/', ' ', $title)));
    $normalizedTitle = preg_replace('/[^\w\s]/', '', $normalizedTitle);
    
    // Get all PDF files in the directory
    $files = glob($pdfDir . '*.pdf');
    
    foreach ($files as $file) {
        $filename = basename($file, '.pdf');
        $normalizedFilename = strtoupper(trim(preg_replace('/\s+/', ' ', $filename)));
        $normalizedFilename = preg_replace('/[^\w\s]/', '', $normalizedFilename);
        
        // Try exact match first
        if ($normalizedFilename === $normalizedTitle) {
            return $base_path . 'assets/documents/pdfs/researches/' . $folder . '/' . basename($file);
        }
        
        // Try partial match - check if most of the title matches
        $titleWords = explode(' ', $normalizedTitle);
        $filenameWords = explode(' ', $normalizedFilename);
        $matchCount = 0;
        foreach ($titleWords as $word) {
            if (strlen($word) > 3 && in_array($word, $filenameWords)) {
                $matchCount++;
            }
        }
        // If 70% of significant words match, consider it a match
        if ($matchCount >= count(array_filter($titleWords, function($w) { return strlen($w) > 3; })) * 0.7) {
            return $base_path . 'assets/documents/pdfs/researches/' . $folder . '/' . basename($file);
        }
    }
    
    return null;
}

// Helper function to render research title with PDF link
function renderResearchTitle($title, $department) {
    $pdfLink = findResearchPDF($title, $department);
    if ($pdfLink) {
        return '<a href="' . htmlspecialchars($pdfLink) . '" target="_blank" class="research-link">' . htmlspecialchars($title) . '</a>';
    }
    return htmlspecialchars($title);
}

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

/* News Section */
.news-section {
    background: white;
}

.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.news-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(44, 90, 160, 0.1);
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
}

.news-image {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

.news-content {
    padding: 1.5rem;
}

.news-content h4 {
    color: var(--primary-color);
    font-size: 1.2rem;
    margin-bottom: 1rem;
    font-weight: 700;
    line-height: 1.4;
}

.news-content p {
    color: #666;
    line-height: 1.6;
    font-size: 0.95rem;
    margin-bottom: 1rem;
}

.news-link {
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.news-link:hover {
    color: var(--primary-color);
    text-decoration: none;
}

/* Research Tables Section */
.research-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.research-container {
    max-width: 1200px;
    margin: 0 auto;
}

.department-select {
    margin-bottom: 3rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.department-select select {
    width: 100%;
    padding: 1rem 1.5rem;
    border: 2px solid var(--primary-color);
    border-radius: 10px;
    background: white;
    color: var(--primary-color);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231c4da1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1.2rem;
    padding-right: 3rem;
    transition: all 0.3s ease;
}

.department-select select:focus {
    outline: none;
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(28, 77, 161, 0.1);
}

.department-select select:hover {
    border-color: var(--secondary-color);
}

.research-table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.research-table {
    width: 100%;
    border-collapse: collapse;
}

.research-table th {
    background: var(--primary-color);
    color: white;
    padding: 1rem;
    text-align: left;
    font-weight: 700;
    font-size: 1rem;
}

.research-table td {
    padding: 1rem;
    border-bottom: 1px solid #eee;
    vertical-align: top;
}

.research-table tr:hover {
    background: #f8f9fa;
}

.research-title {
    color: var(--primary-color);
    font-weight: 600;
    font-size: 0.95rem;
    line-height: 1.4;
    margin-bottom: 0.5rem;
}

.research-author {
    color: #666;
    font-size: 0.9rem;
    font-style: italic;
}

.research-link {
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.research-link:hover {
    color: var(--primary-color);
    text-decoration: none;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .intro-content h2 {
        font-size: 2rem;
    }
    
    .intro-description {
        font-size: 0.8rem;
    }
    
    .mv-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .news-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .research-table {
        font-size: 0.9rem;
    }
    
    .research-table th,
    .research-table td {
        padding: 0.8rem 0.5rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .department-select {
        margin-bottom: 2rem;
    }
    
    .department-select select {
        padding: 0.8rem 1.2rem;
        font-size: 0.9rem;
    }
}
</style>

<main class="main-content">
    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-logo">
                    <img src="<?php echo $base_path; ?>assets/images/research/uphsl-research-logo.png" alt="Research & Development Center Logo">
                </div>
                <h2>Research & Publication</h2>
                <p class="intro-description">Research and Development Center (R&DC) demonstrates bold initiatives in escalating the research culture of the university, envisioning it as a research-renowned institution through its efficient and effective research mechanisms, ensuring that relevant and responsive services are in place for school heads, faculty, students and non-teaching staff who engage in research activities.</p>
            </div>
        </div>
    </section>

    <!-- News Carousel and Video Section -->
    <section class="news-video-section">
        <div class="container">
            <!-- Shared Section Header 
            <div class="section-header">
                <h2 class="section-title">Research News & Updates</h2>
                <p class="section-description">
                    Stay updated with the latest news and announcements from the Research department.
                </p>
            </div>-->
            
            <div class="news-video-layout" id="newsVideoLayout">
                <div class="video-pdf-flex">
                    <!-- IRC Video Slider -->
                    <div class="video-wrapper">
                        <div class="video-container">
                            <div class="video-header">
                                <h3 class="video-title">International Conference on Multidisciplinary Research for Sustainable Development Goals</h3>
                                <p class="video-subtitle">Advancing Knowledge Frontiers for a Sustainable Future</p>
                            </div>
                            <div class="video-slider">
                                <div class="video-slider-track" id="videoSliderTrack">
                                    <!-- IRC 2026 Video -->
                                    <div class="video-slide">
                                        <div class="video-player">
                                            <video controls muted playsinline preload="auto">
                                                <source src="<?php echo $base_path; ?>assets/video/IRC 2026.mp4" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    </div>
                                    <!-- IRC 2025 Video -->
                                    <div class="video-slide">
                                        <div class="video-player">
                                            <video controls muted playsinline preload="auto">
                                                <source src="<?php echo $base_path; ?>assets/video/IRC 2025.mp4" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    </div>
                                </div>
                                <!-- Navigation Arrows -->
                                <button class="video-slider-nav video-slider-prev" id="videoSliderPrev">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>
                                </button>
                                <button class="video-slider-nav video-slider-next" id="videoSliderNext">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </button>
                                <!-- Dots Navigation -->
                                <div class="video-slider-dots" id="videoSliderDots"></div>
                            </div>
                        </div>
                    </div>
                    <!-- PDF Preview: UPHSL Research Agenda -->
                    <div class="pdf-preview-wrapper">
                        <div class="pdf-preview-container">
                            <div class="pdf-preview-header">
                                <h3 class="pdf-preview-title">UPHSL Research Agenda</h3>
                                <a href="<?php echo $base_path; ?>assets/documents/pdfs/UPHSL_Research_Agenda.pdf" target="_blank" class="pdf-download-link">Download PDF</a>
                            </div>
                            <div class="pdf-preview-frame">
                                <iframe src="<?php echo $base_path; ?>assets/documents/pdfs/UPHSL_Research_Agenda.pdf#toolbar=1&navpanes=0&scrollbar=1" width="100%" height="500px" style="border:1px solid #ccc; border-radius:10px; min-height:350px;" allowfullscreen loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- News Carousel -->
                <div class="news-carousel-wrapper" id="newsCarouselWrapper">
                    <?php
                    $categoryId = 'Research'; // Pass category name, component will look it up
                    $sectionTitle = ''; // Empty to hide duplicate header
                    $sectionDescription = ''; // Empty to hide duplicate description
                    include '../app/includes/news-carousel.php';
                    ?>
                </div>
            </div>
        </div>
    </section>
    
    <style>
    /* News and Video Layout */
    .news-video-section {
        padding: 60px 0;
        background: #f8f9fa;
    }
    
    .news-video-section .section-header {
        text-align: center;
        margin-bottom: 60px;
        position: relative;
    }
    
    .news-video-section .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 15px;
        position: relative;
        display: block;
        text-align: center;
    }
    
    .news-video-section .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--alt-color-1));
        border-radius: 2px;
    }
    
    .news-video-section .section-description {
        font-size: 1.2rem;
        color: var(--text-light);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
        text-align: center;
    }
    
    .news-video-layout {
        display: flex;
        flex-direction: column;
        gap: 3rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    .video-pdf-flex {
        display: flex;
        flex-direction: row;
        gap: 2.5rem;
        width: 100%;
        align-items: stretch;
    }
    .video-wrapper {
        flex: 0 0 60%;
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: stretch;
        height: 100%;
    }
    .pdf-preview-wrapper {
        flex: 0 0 40%;
        min-width: 0;
        display: flex;
        justify-content: center;
        align-items: stretch;
        overflow: hidden;
    }
    .pdf-preview-container {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 1.5rem 1rem 1rem 1rem;
        width: 100%;
        max-width: 420px;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        overflow: hidden;
        box-sizing: border-box;
    }
    .pdf-preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    .pdf-preview-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary-color, #2c5aa0);
        margin: 0;
    }
    .pdf-download-link {
        font-size: 0.95rem;
        color: var(--secondary-color, #ffc63e);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }
    .pdf-download-link:hover {
        color: var(--primary-color, #2c5aa0);
        text-decoration: underline;
    }
    .pdf-preview-frame {
        width: 100%;
        min-height: 350px;
        margin-top: 0.5rem;
        overflow: auto;
    }
    .pdf-preview-frame iframe {
        border-radius: 12px;
        width: 100%;
        max-width: 100%;
        display: block;
        background: #fff;
        box-sizing: border-box;
    }
    @media (max-width: 1024px) {
        .video-pdf-flex {
            flex-direction: column;
            gap: 2rem;
        }
        .video-wrapper,
        .pdf-preview-wrapper {
            max-width: 100%;
            flex-basis: 100% !important;
        }
        .pdf-preview-frame iframe {
            height: 350px !important;
        }
    }
    @media (max-width: 768px) {
        .video-pdf-flex {
            flex-direction: column;
            gap: 1.5rem;
        }
        .pdf-preview-frame iframe {
            height: 250px !important;
        }
    }
    @media (max-width: 480px) {
        .pdf-preview-container {
            padding: 1rem 0.5rem 0.5rem 0.5rem;
        }
        .pdf-preview-frame iframe {
            height: 180px !important;
        }
    }
    
    /* Override news-section styling when inside our layout */
    .news-video-layout .news-section {
        padding: 0 !important;
        background: transparent !important;
    }
    
    /* Hide the duplicate section header from news-carousel.php */
    .news-video-layout .news-section .section-header {
        display: none;
    }
    
    .news-carousel-wrapper {
        width: 100%;
        min-height: 100px;
        overflow: hidden;
    }
    
    /* Ensure news carousel maintains proper aspect ratio */
    .news-carousel-wrapper .news-carousel-container {
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .news-carousel-wrapper .news-carousel {
        width: 100%;
        aspect-ratio: 16 / 9; /* Maintain proper aspect ratio for carousel */
    }
    
    /* Hide news section container padding since we're in a custom layout */
    .news-carousel-wrapper .news-section .container {
        padding: 0 !important;
        max-width: 100% !important;
        margin: 0 !important;
    }
    
    .news-carousel-wrapper .news-section {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .news-carousel-wrapper .news-layout {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .news-carousel-wrapper .news-content {
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .video-wrapper {
        width: 100%;
        order: -1; /* Place video first (above news) */
    }
    
    .video-container {
        background: white;
        border-radius: 20px;
        padding: 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: box-shadow 0.3s ease;
        overflow: hidden;
        width: 100%;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .video-container:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }
    
    .video-header {
        padding: 2rem 2rem 1.5rem 2rem;
        text-align: center;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .video-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.3px;
        line-height: 1.4;
        font-family: inherit;
    }
    
    .video-subtitle {
        font-size: 0.95rem;
        color: #888;
        margin: 0;
        font-weight: 400;
        letter-spacing: 0.3px;
        font-style: italic;
        font-family: inherit;
    }
    
    .video-player {
        width: 100%;
        position: relative;
        overflow: hidden;
        background: #000;
        flex: 1 1 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        aspect-ratio: 16 / 9;
    }
    
    .video-player video {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
        border-radius: 0 0 20px 20px;
        background: #000;
        position: relative;
        z-index: 5;
    }

    /* Video Slider Styles */
    .video-slider {
        position: relative;
        width: 100%;
        overflow: hidden;
        flex: 1 1 auto;
        display: flex;
        align-items: stretch;
        height: 100%;
    }

    .video-slider-track {
        display: flex;
        transition: transform 0.5s ease;
        width: 100%;
        height: 100%;
    }

    .video-slide {
        flex: 0 0 100%;
        width: 100%;
        height: 100%;
        position: relative;
    }

    .video-slide .video-player {
        height: 100%;
    }

    .video-slide-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        padding: 2rem 1.5rem 1rem;
        color: white;
        z-index: 10;
    }

    .video-year {
        font-size: 1.2rem;
        font-weight: 700;
        color: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }

    .video-slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: var(--primary-color, #2c5aa0);
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 20;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .video-slider-nav:hover {
        background: var(--primary-color, #2c5aa0);
        color: white;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .video-slider-prev {
        left: 1rem;
    }

    .video-slider-next {
        right: 1rem;
    }

    .video-slider-dots {
        position: absolute;
        bottom: 1.5rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 0.5rem;
        z-index: 20;
    }

    .video-slider-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        border: 2px solid rgba(0, 0, 0, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .video-slider-dot.active {
        background: white;
        border-color: white;
        transform: scale(1.2);
    }

    .video-slider-dot:hover {
        background: rgba(255, 255, 255, 0.8);
    }
    
    /* When news carousel is hidden/empty, video takes full width */
    .news-video-layout.news-hidden {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Responsive Design */
    @media (max-width: 1024px) {
        .news-video-layout {
            gap: 2.5rem;
        }
        
        .video-header {
            padding: 1.5rem 1.5rem 1rem 1.5rem;
        }
        
        .video-title {
            font-size: 1.15rem;
            line-height: 1.4;
        }
        
        .video-subtitle {
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 768px) {
        .news-video-section {
            padding: 40px 0 !important;
        }
        
        .news-video-section .section-title {
            font-size: 2rem;
        }
        
        .news-video-section .section-description {
            font-size: 1rem;
        }
        
        .news-video-layout {
            gap: 2rem;
        }
        
        .news-video-section .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .video-container {
            border-radius: 16px;
        }
        
        .video-header {
            padding: 1.25rem 1.25rem 0.875rem 1.25rem;
        }
        
        .video-title {
            font-size: 1.05rem;
            line-height: 1.4;
        }
        
        .video-subtitle {
            font-size: 0.85rem;
        }
        
        /* Fix news carousel alignment on mobile */
        .news-carousel-wrapper .news-carousel-container {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        
        .news-carousel-wrapper .news-section .container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
    }
    
    @media (max-width: 480px) {
        .news-video-section {
            padding: 30px 0 !important;
        }
        
        .news-video-section .section-title {
            font-size: 1.75rem;
        }
        
        .news-video-section .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .video-container {
            border-radius: 12px;
        }
        
        .video-header {
            padding: 1rem 1rem 0.75rem 1rem;
        }
        
        .video-title {
            font-size: 1rem;
            line-height: 1.4;
        }
        
        .video-subtitle {
            font-size: 0.8rem;
        }
        
        /* Fix news carousel alignment on small mobile */
        .news-carousel-wrapper .news-carousel-container {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        
        .news-carousel-wrapper .news-section .container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
    }
    </style>
    
    <script>
    // Video Slider Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('videoSliderTrack');
        const prevBtn = document.getElementById('videoSliderPrev');
        const nextBtn = document.getElementById('videoSliderNext');
        const dotsContainer = document.getElementById('videoSliderDots');
        
        if (!track) return;
        
        const slides = Array.from(track.children);
        const slideCount = slides.length;
        let currentIndex = 0;
        
        // Create dots
        if (dotsContainer && slideCount > 1) {
            for (let i = 0; i < slideCount; i++) {
                const dot = document.createElement('button');
                dot.className = 'video-slider-dot' + (i === 0 ? ' active' : '');
                dot.addEventListener('click', () => {
                    goToSlide(i);
                });
                dotsContainer.appendChild(dot);
            }
        }
        
        function goToSlide(index) {
            currentIndex = (index + slideCount) % slideCount;
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
            
            // Update dots
            if (dotsContainer) {
                const dots = Array.from(dotsContainer.children);
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentIndex);
                });
            }
            
            // Pause all videos and reset to beginning
            slides.forEach((slide) => {
                const video = slide.querySelector('video');
                if (video) {
                    video.pause();
                    video.currentTime = 0;
                }
            });
            
            // Play the current slide's video
            const currentSlide = slides[currentIndex];
            const currentVideo = currentSlide.querySelector('video');
            if (currentVideo) {
                currentVideo.play().catch(e => console.log('Autoplay prevented:', e));
            }
        }
        
        function nextSlide() {
            goToSlide(currentIndex + 1);
        }
        
        function prevSlide() {
            goToSlide(currentIndex - 1);
        }
        
        // Add video end event listeners to auto-switch slides
        slides.forEach((slide, index) => {
            const video = slide.querySelector('video');
            if (video) {
                video.addEventListener('ended', () => {
                    // When video ends, go to next slide
                    nextSlide();
                });
            }
        });
        
        // Navigation buttons
        if (prevBtn) {
            prevBtn.addEventListener('click', prevSlide);
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', nextSlide);
        }
        
        // Initialize and play first video
        goToSlide(0);
    });
    
    // Adjust layout when news carousel is hidden
    document.addEventListener('DOMContentLoaded', function() {
        const layout = document.getElementById('newsVideoLayout');
        const newsWrapper = document.getElementById('newsCarouselWrapper');
        
        function checkNewsVisibility() {
            if (!layout || !newsWrapper) return;
            
            const newsSection = newsWrapper.querySelector('.news-section');
            
            // Check if news section exists and is visible
            if (!newsSection || newsSection.offsetParent === null || newsSection.style.display === 'none') {
                // News carousel is hidden - video takes full width
                layout.classList.add('news-hidden');
            } else {
                // News carousel is visible - stacked layout
                layout.classList.remove('news-hidden');
            }
        }
        
        // Check on load (with a small delay to ensure DOM is ready)
        setTimeout(checkNewsVisibility, 100);
        
        // Check on resize
        window.addEventListener('resize', checkNewsVisibility);
        
        // Use MutationObserver to watch for changes in news section visibility
        if (newsWrapper) {
            const observer = new MutationObserver(function() {
                checkNewsVisibility();
            });
            
            observer.observe(newsWrapper, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['style', 'class']
            });
        }
    });
    </script>

    <!-- Mission and Vision Section -->
    <section class="content-section mission-vision-section">
        <div class="container">
            <h2 class="section-title">Mission & Vision</h2>
            <div class="mv-container">
                <div class="mv-card">
                    <h3>Vision</h3>
                    <p>A research-renowned university.</p>
                </div>
                <div class="mv-card">
                    <h3>Mission</h3>
                    <p>Develop research-oriented professionals who produce high impact researches that are locally responsive and globally competitive, worthy of publication and citation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Research by Department Section -->
    <section class="content-section research-section">
        <div class="container">
            <h2 class="section-title">Researches By Department</h2>
            <div class="research-container">
                <!-- Department Dropdown -->
                <div class="department-select">
                    <select id="departmentSelect" onchange="showDepartmentMobile(this.value)">
                        <option value="arts-science">Arts & Science</option>
                        <option value="criminology">Criminology</option>
                        <option value="graduate-school">Graduate School</option>
                        <option value="business-accountancy">Business & Accountancy</option>
                        <option value="education">Education</option>
                        <option value="hospitality">Int'l Hospitality Management</option>
                        <option value="computer-studies">Computer Studies</option>
                        <option value="engineering">Engineering</option>
                        <option value="maritime">Maritime</option>
                    </select>
                </div>

                <!-- Arts & Science Research -->
                <div id="arts-science" class="research-table-container">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("ADVANTAGES, CHALLENGES ENCOUNTERED AND ATTITUDE OF TEACHERS IN UTILIZING MULTIMEDIA IN THE CLASSROOM", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Alma T. Jallorina</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("ASSESSING STUDENTS' RESEARCH EXPERIENCE AT THE UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA COLLEGE OF MARITIME EDUCATION", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Amador B. Alumia & Leomar S. Galicia</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("AWARENESS ON THE ADVANTAGES AND DISADVANTAGES OF OUTCOME BASED EDUCATION AMONG GRADUATING PSYCHOLOGY STUDENTS", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Sherill S. Villaluz</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("BEYOND PREJUDICE UNDERSTANDING PEOPLE LIVING WITH HUMAN IMMUNODEFICIENCY VIRUS (PLHIV)", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Sherill S. Villaluz, Jershon Ammon N. Teodoro & Radlyn L. del Prado</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("DEGREE OF INCLINATION, BOARD COURSE COMPETENCE, AND LICENSURE READINESS AMONG UPHSL PSYCHOLOGY GRADUATES", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Sherill S. Villaluz</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("DEGREE OF INVOLVEMENT IN LEISURE ACTIVITIES AND ACADEMIC PERFORMANCE OF UPHSL MARITIME STUDENTS", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Araceli C. Corpuz, Ace C. Bernarte, Eraume Ramir M. Saluba & Raymond-Paul T. Sanchez</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("DIFFICULTIES ENCOUNTERED, LEARNING STRATEGIES AND ACADEMIC PERFORMANCE IN PHYSICS OF PSYCHOLOGY STUDENTS", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Araceli C. Corpuz</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("FAMILY DYNAMICS, EMOTIONAL RESPONSES, HOPE AND HANDLING STRATEGIES AMONG CALAMITY VICTIMS", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Laura De Guzman, Frances Amara Cristobal & Gimmeli Ann Palomares</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("FREQUENCY OF WATCHING POLITICAL NEWS PROGRAM ON TELEVISION, POLITICAL NEWS BIAS, AND POLITICAL NEWS DELIVERY SATISFACTION", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Nimfa R. Marcelo</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("FROM LETTERS TO LIFE UNDERSTANDING LANGUAGE TEACHERS EXPERIENCES", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Rowena R. Contillo, Leomar S. Galicia, Antonio R. Yango & Pedrito Jose V. Bermudo</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("GAY LANGUAGE IMPACT ON COLLOQUIAL COMMUNICATION IN BARANGAY STO. TOMAS, CITY OF BIÑAN, LAGUNA", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Hazel V. Cortez</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("KNOWLEDGE AND AWARENESS ON MTRCB ADVISORIES AMONG FOURTH GRADERS OF UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Hazel V. Cortez, Yves Carlson R. Hitchon & Precious May V. Dicolen</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("LAUGHTER IN CLASS HUMOROUS MEMES IN 21ST CENTURY LEARNING", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Paulo Emmanuel G. Baysac</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("MOTIVATION, FREQUENCY OF USAGE AND LEVEL OF CONFIDENCE IN USING PHILIPPINE ENGLISH AMONG FOREIGN STUDENTS", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Alma T. Jallorina</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("PAIN AND FORGIVENESS IN THE EYES OF THE FILIPINOS", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Sherill S. Villaluz, Paulo Emmanuel G. Baysac, Antonio S. Yango, Czarina Isabelle I. Arimado & Fatima Mikaela C. Remoquillo</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("PET ANIMALS TO OWN AND TO LOVE", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Luz Remedios Quito Del Rosario, Antonio Yango, Rissel C. Dela Paz, Jodel Clarissa B. Margate & Eire Ramallosa P. May</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("REVISITING ART THERAPY A COUNSELING INTERVENTION FOR PUPILS", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Jocelyn G. Capacio</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("SELF –APPRAISAL, INTERPERSONAL RELATIONSHIP, AND LIFE SATISFACTION OF TEENAGE PARENTS", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Leomar S. Galicia</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("TO LOCK AND TO UNLOCK UNDERSTANDING THE LIVED EXPERIENCE OF PUBLIC HIGH SCHOOL TEACHERS WITH STUDENTS HAVING READING DIFFICULTY", 'arts-science'); ?></div>
                                </td>
                                <td><div class="research-author">Juditha L. Nievarez –Teodoro & Antonio R. Yango</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Criminology Research -->
                <div id="criminology" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("CLOSED CIRCUIT TELEVISION (CCTV) IN THE SCHOOL CAMPUS FACULTYEMPLOYEE, AND STUDENT'S PERSPECTIVE", 'criminology'); ?></div>
                                </td>
                                <td><div class="research-author">Diadem DV. Fantony</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("PARENTAL ATTITUDE TOWARDS WAR TOYS ITS PERCEIVED EFFECTS TO CHILD'S BEHAVIOR", 'criminology'); ?></div>
                                </td>
                                <td><div class="research-author">J. Acosta, R. Abayan, P. Abella, M. Alad, H. Buenconsejo, F. Cabanero, A. Figueroa & A. Santiago</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Graduate School Research -->
                <div id="graduate-school" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("ASSESSING STUDENTS' RESEARCH EXPERIENCE AT THE UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA COLLEGE OF MARITIME EDUCATION", 'graduate-school'); ?></div>
                                </td>
                                <td><div class="research-author">Amador B. Alumia & Leomar S. Galicia</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("COLLEGE STUDENTS' ATTITUDE TOWARDS THE INTERNET AS A COMMUNICATION MEDIUM AND LEVEL OF UTILIZATION OF ENGLISH LANGUAGE IN THE CLASSROOM", 'graduate-school'); ?></div>
                                </td>
                                <td><div class="research-author">Antonio R. Yango & Maria Cecilia L. Garcia</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("CROSSROADS OF QUALITY ASSURANCE: THE PHILIPPINE BASIC EDUCATION EXPERIENCE", 'graduate-school'); ?></div>
                                </td>
                                <td><div class="research-author">Ferdinand C. Somido</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("FROM LETTERS TO LIFE: UNDERSTANDING LANGUAGE TEACHERS EXPERIENCES IN TEACHING LITERATURE", 'graduate-school'); ?></div>
                                </td>
                                <td><div class="research-author">Rowena R. Contillo, Leomar S. Galicia, Antonio R. Yango & Pedrito Jose V. Bermudo</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("PERSONALITY TYPE, ORGANIZATIONAL COMMITMENT, AND COLLABORATIVE ALLIANCE AMONG UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA (UPHSL) ACADEMIC PERSONNEL", 'graduate-school'); ?></div>
                                </td>
                                <td><div class="research-author">Sherill S. Villaluz & Leomar S. Galicia</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("SELF – APPRAISAL, INTERPERSONAL RELATIONSHIP, AND LIFE SATISFACTION OF TEENAGE PARENTS", 'graduate-school'); ?></div>
                                </td>
                                <td><div class="research-author">Leomar S. Galicia</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("TO LOCK AND TO UNLOCK UNDERSTANDING THE LIVED EXPERIENCE OF PUBLIC HIGH SCHOOL TEACHERS WITH STUDENTS HAVING READING DIFFICULTY", 'graduate-school'); ?></div>
                                </td>
                                <td><div class="research-author">Juditha L. Nievarez - Teodoro & Antonio R. Yango</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("WORDS OR NUMBERS THE ESSENCE OF LANGUAGE TEACHERS' UNAPPRECIATION OF MATHEMATICS", 'graduate-school'); ?></div>
                                </td>
                                <td><div class="research-author">Leomar S. Galicia</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Business & Accountancy Research -->
                <div id="business-accountancy" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("THE GRADUATES OF BUSINESS VS. EMPLOYMENT (A TRACER STUDY)", 'business-accountancy'); ?></div>
                                </td>
                                <td><div class="research-author">Francisca A. Argana, Marilyn A. Cabalza & Ernesto A. Serrano Jr.</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("THE PARTICIPANTS EVALUATION'S RESULT FOR THE SEMINAR WORKSHOP - COMMUNITY OUTREACH PROGRAM OF THE COLLEGE OF BUSINESS AND ACCOUNTANCY", 'business-accountancy'); ?></div>
                                </td>
                                <td><div class="research-author">Carlito A. Vizconde</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("THE PERSONAL AND ORGANIZATIONAL COMPETENCES OF THE SELECTED DEPARTMENT HEADS OF THE UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA", 'business-accountancy'); ?></div>
                                </td>
                                <td><div class="research-author">Carlito A. Vizconde</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Education Research -->
                <div id="education" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("KINDERGARTEN TEACHERS' PROFILE, FACILITIES & INSTRUCTIONAL PRACTICES TOWARDS SUSTAINABILITY AND ENVIRONMENTAL SAFETY", 'education'); ?></div>
                                </td>
                                <td><div class="research-author">Elena A. Salinas</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("READING COMPREHENSION INTERVENTION PROGRAM OF UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA", 'education'); ?></div>
                                </td>
                                <td><div class="research-author">Alberto R. Rocero & Jhoana L. Macha</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("REGISTRAR'S EXECUTIVE ASSISTANCE (REA) A CUSTOMER RELATIONSHIP MANAGEMENT SYSTEM OR NOT?", 'education'); ?></div>
                                </td>
                                <td><div class="research-author">Ma. Eliza D. Mapanoo, Oliver M. Junio & Remina L. Tanyag</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("THE EFFECTIVENESS OF COOPERATIVE LEARNING IN STUDENTS' COMPREHENSION OF LITERARY TEXTS", 'education'); ?></div>
                                </td>
                                <td><div class="research-author">Victorio B. Duyan, Alberto R. Rocero, Adelaida G. Abalos, Jesus M. Purificacion & Edmil L. Recibe</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("THE PICTURE IMAGINATIVE MATERIALS AND THE CREATIVE WRITING SKILLS OF GRADE 10 STUDENTS OF UNIVERSITY OF PERPETUAL HELP SYSTEM LAGUNA", 'education'); ?></div>
                                </td>
                                <td><div class="research-author">Alberto R. Rocero, Elena A. Salinas & Remedios M. Dela Rosa</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- International Hospitality Management Research -->
                <div id="hospitality" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("ACADEMIC PERFORMANCE AND PERCEIVED EMPLOYABILITY SKILLS OF HOTEL AND RESTAURANT MANAGEMENT GRADUATING STUDENTS", 'hospitality'); ?></div>
                                </td>
                                <td><div class="research-author">Nenita A. Daquiz</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("CUSTOMERS' SATISFACTION ON ONLINE RESERVATION AMONG SELECTED FIVE-STAR HOTELS", 'hospitality'); ?></div>
                                </td>
                                <td><div class="research-author">Susan L. Palaroan</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("LEVEL OF DIFFICULTIES ENCOUNTERED AND THE PERFORMANCE OF NUTRITION AND DIETETICS GRADUATES IN FOOD SERVICE, COMMUNITY, AND HOSPITAL PRACTICUM", 'hospitality'); ?></div>
                                </td>
                                <td><div class="research-author">Olivia J. Factoriza</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("LEVEL OF IMPLEMENTATION OF FOOD SANITATION PRACTICES IN SCHOOL CAFETERIAS AS RATED BY UPHSL STUDENTS", 'hospitality'); ?></div>
                                </td>
                                <td><div class="research-author">Adorita C. De Jose</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("PROPOSED PREVENTIVE AND CORRECTIVE MEASURES FOR HANDLING CUSTOMER COMPLAINTS", 'hospitality'); ?></div>
                                </td>
                                <td><div class="research-author">Susan L. Palaroan</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Computer Studies Research -->
                <div id="computer-studies" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("ACCREDITATION MANAGEMENT SYSTEM", 'computer-studies'); ?></div>
                                </td>
                                <td><div class="research-author">Ma. Eliza D. Mapanoo</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("ASSESSMENT OF ONLINE OJT PERFORMANCE MONITORING", 'computer-studies'); ?></div>
                                </td>
                                <td><div class="research-author">Jasmin H. Almarinez</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("COMPARATIVE STUDY OF AIRDROP VS SHAREIT WIFI DIRECT FILE TRANSFER USING COMPATIBLE DEVICES", 'computer-studies'); ?></div>
                                </td>
                                <td><div class="research-author">Michael M. Orozco</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("DEVELOPMENT OF ADDA (ADDITIONAL DATA) ALGORITHM FOR 10T SECURITY AND PRIVACY", 'computer-studies'); ?></div>
                                </td>
                                <td><div class="research-author">Oliver M. Junio & Jasmin De Castro-Niguidula</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("DEVELOPMENT OF OFFLINE CHAT APPLICATION: FRAMEWORK FOR RESILIENT DISASTER MANAGEMENT", 'computer-studies'); ?></div>
                                </td>
                                <td><div class="research-author">Oliver M. Junio & Enrico P. Chavez</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("DEVELOPMENT OF ONLINE GRADUATE TRACER SYSTEM WITH DATA ANALYTICS", 'computer-studies'); ?></div>
                                </td>
                                <td><div class="research-author">Michael M. Orozco</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("DEVELOPMENT OF READING TUTORIAL A SUPPLEMENTARY LEARNING SOFTWARE FOR DAY CARE CENTER", 'computer-studies'); ?></div>
                                </td>
                                <td><div class="research-author">Ma. Eliza D. Mapanoo</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("E-GOVERNMENT FOR HUMAN CAPABILITY DEVELOPMENT PROGRAM: AN IMPLEMENTATION OF G2E SYSTEM FOR ENHANCED GOVERNMENT SERVICES", 'computer-studies'); ?></div>
                                </td>
                                <td><div class="research-author">Ma. Eliza D. Mapanoo & Jonathan M. Caballero</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("HISTOGRAM-BASED IMAGE SEGMENTATION ALGORITHM APPLICATION FOR FLOOD DISASTER MANAGEMENT", 'computer-studies'); ?></div>
                                </td>
                                <td><div class="research-author">Michael M. Orozco & Jonathan M. Caballero</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("MAVIS: SPECIAL EDUCATION VIRTUAL ASSISTANT", 'computer-studies'); ?></div>
                                </td>
                                <td><div class="research-author">Eliza D. Mapanoo & Jonathan M. Caballero</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("POINT OF SALES SYSTEM FOR DRUGSTORE", 'computer-studies'); ?></div>
                                </td>
                                <td><div class="research-author">Jasmin H. Almarinez</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("SMART DISASTER PREDICTION APPLICATION USING FLOOD-RISK ANALYTICS TOWARDS SUSTAINABLE CLIMATE ACTION", 'computer-studies'); ?></div>
                                </td>
                                <td><div class="research-author">Michael M. Orozco & Jonathan M. Caballero</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("WEB-BASED THESIS/ CAPSTONE PROJECT DEFENSE EVALUATION SYSTEM OF THE CCS BIÑAN", 'computer-studies'); ?></div>
                                </td>
                                <td><div class="research-author">Michael M. Orozco</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Engineering Research -->
                <div id="engineering" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("5S: A LEARNER WORKFLOW TOOL AT AGM VENTURE", 'engineering'); ?></div>
                                </td>
                                <td><div class="research-author">Teresita B. Gonzales & Deserie D. Mendoza</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("ADVANCED INTEGRATION OF QUALITY CONTROL THROUGH INVENTORY MANAGEMENT SYSTEM IN A SEMICONDUCTOR COMPANY IN THE PHILIPPINES", 'engineering'); ?></div>
                                </td>
                                <td><div class="research-author">Kierven R. de Mesa</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("ASSESSING THE ENRICHMENT PROGRAM FOR SOPHOMORE ENGINEERING STUDENTS OF UPHSL SCHOOL YEAR 2013-14: BASIS FOR DEVELOPMENT INTERVENTIONS", 'engineering'); ?></div>
                                </td>
                                <td><div class="research-author">Antonino D. Carpena, Leilani A. Gonzales, Teresita B. Gonzales, Nancy P. Mercado & Engr. Jimmy B. Teodoro</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("CYCLE TIME REDUCTION THROUGH MINIMIZATION OF TRANSPORTATION AT DYNASTY PALLETS SYSTEMS INC.", 'engineering'); ?></div>
                                </td>
                                <td><div class="research-author">Nancy P. Mercado, Teresita B. Gonzales & Jayson D. Pobar</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("DAYLIGHT AND SOLAR ENERGY OPTIMIZATION THRU SMART LIGHTING MANAGEMENT SYSTEM WITH MANUAL OVERRIDE", 'engineering'); ?></div>
                                </td>
                                <td><div class="research-author">Antonino D. Carpena & Flocerfida L. Amaya</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("DESIGN AND EVALUATION OF ELECTRONIC CLASS RECORD IN UNIVERSITY OF PERPETUAL HELP SYSTEM-LAGUNA", 'engineering'); ?></div>
                                </td>
                                <td><div class="research-author">Nancy P. Mercado</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("DEVELOPMENT OF FUN LEARNING APPLICATION FO PRESCHOOLERS", 'engineering'); ?></div>
                                </td>
                                <td><div class="research-author">Leilani A. Gonzales</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("IMPROVED PRODUCTION EFFICIENCY THROUGH LEAN MANUFACTURING TOOL WITH THE USE OF VALUE STREAM MAPPING (VSM)", 'engineering'); ?></div>
                                </td>
                                <td><div class="research-author">Antonino D. Carpena & Flocerfida L. Amaya</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("LEVEL OF SAFETY AWARENESS OF THE MANAGEMENT AND THE WORKERS AT THE ASSEMBLY AREA IN A SEWING COMPANY", 'engineering'); ?></div>
                                </td>
                                <td><div class="research-author">Leilani A. Gonzales & Jimmy M. Teodoro</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("PRODUCTIVITY IMPROVEMENT THROUGH VALUE STREAM MAPPING IN JARVY'S FOOTWEAR COMPANY", 'engineering'); ?></div>
                                </td>
                                <td><div class="research-author">Leilani A. Gonzales, Teresita B. Gonzales & Nancy P. Mercado</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("RAPID UPPER LIMB ASSESSMENT: BASIS FOR INTERVENTION OF FACTORY WORKERS IN A GARMENT COMPANY", 'engineering'); ?></div>
                                </td>
                                <td><div class="research-author">Nancy P. Mercado</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("STUDY HABITS, ATTITUDES AND ACADEMIC PERFORMANCE OF SELECTED COLLEGE OF ENGINEERING STUDENTS OF SUMMER 2016: BASIS FOR STUDENT REINFORCEMENT", 'engineering'); ?></div>
                                </td>
                                <td><div class="research-author">Teresita B. Gonzales</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("VALUE STREAM MAPPING AS AN EFFECTIVE LEAN MANUFACTURING TOOL IN A CARAGEENAN PRODUCING COMPANY IN THE PHILIPPINES", 'engineering'); ?></div>
                                </td>
                                <td><div class="research-author">Antonino D. Carpena & Flocerfida L. Amaya</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Maritime Research -->
                <div id="maritime" class="research-table-container" style="display: none;">
                    <table class="research-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("BERTHING ALONG SIDE PIER: RISK FACTORS AND SAFETY PRACTICES DURING MOORING AND UNMOORING OPERATIONS", 'maritime'); ?></div>
                                </td>
                                <td><div class="research-author">Reynaldo A. Lora, Elpidio P. Onte, Dalisay G. Bantatua & Sherill S. Villaluz</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("RISK FACTORS ASSOCIATED WITH THE SEAFARER'S FREQUENCY AND LEVEL OF FATIGUE", 'maritime'); ?></div>
                                </td>
                                <td><div class="research-author">Dalisay G. Bantatua, Nonet A. Cuy, Maximo V. Herrera & Elpidio P. Onte</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="research-title"><?php echo renderResearchTitle("THE APPRENTICESHIP DIFFICULTIES MANAGED BY SEAFARERS WHILE TRAINING ONBOARD", 'maritime'); ?></div>
                                </td>
                                <td><div class="research-author">Maximo V. Herrera & Hazel Cortez</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
function showDepartmentMobile(departmentId) {
    // Hide all department tables
    const tables = document.querySelectorAll('.research-table-container');
    tables.forEach(table => {
        table.style.display = 'none';
    });
    
    // Show selected department table
    document.getElementById(departmentId).style.display = 'block';
}
</script>

<?php include '../app/includes/footer.php'; ?>
