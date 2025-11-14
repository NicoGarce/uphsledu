<?php
/**
 * UPHSL SDG Initiatives Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Sustainable Development Goals Initiatives and Programs
 */

session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Check if SDG Initiatives section is in maintenance
if (isSectionInMaintenance('sdg-initiatives')) {
    $page_title = "SDG Initiatives - Maintenance";
    $base_path = '';
    include 'app/includes/header.php';
    if (displaySectionMaintenance('sdg-initiatives', $base_path)) {
        include 'app/includes/footer.php';
        exit;
    }
}

// Set page title
$page_title = "SDG Initiatives";
$base_path = '';

// Fetch SDG initiatives posts and regular posts with SDG tags
$pdo = getDBConnection();
$sdgPosts = [];

try {
    // Fetch SDG initiatives posts
    $stmt = $pdo->prepare("
        SELECT p.*, u.first_name, u.last_name, 'sdg_initiative' as post_type
        FROM sdg_initiatives_posts p 
        JOIN users u ON p.author_id = u.id 
        WHERE p.status = 'published'
        ORDER BY p.published_at DESC, p.created_at DESC
    ");
    $stmt->execute();
    $allSdgPosts = $stmt->fetchAll();
    
    // Group SDG posts by SDG number
    foreach ($allSdgPosts as $post) {
        $post['post_type'] = 'sdg_initiative';
        $sdgPosts[$post['sdg_number']][] = $post;
    }
    
    // Fetch regular posts with SDG tags (including featured image)
    $stmt = $pdo->prepare("
        SELECT p.*, u.first_name, u.last_name, t.sdg_number, 'regular_post' as post_type
        FROM posts p 
        JOIN users u ON p.author_id = u.id 
        JOIN post_sdg_tags t ON p.id = t.post_id
        WHERE p.status = 'published'
        ORDER BY p.published_at DESC, p.created_at DESC
    ");
    $stmt->execute();
    $taggedPosts = $stmt->fetchAll();
    
    // For regular posts, ensure featured_image is set (it's already in the posts table)
    
    // Group tagged posts by SDG number
    foreach ($taggedPosts as $post) {
        $post['post_type'] = 'regular_post';
        $sdgNumber = $post['sdg_number'];
        if (!isset($sdgPosts[$sdgNumber])) {
            $sdgPosts[$sdgNumber] = [];
        }
        $sdgPosts[$sdgNumber][] = $post;
    }
    
    // Sort posts within each SDG by published date (newest first)
    foreach ($sdgPosts as $sdgNumber => &$posts) {
        usort($posts, function($a, $b) {
            $dateA = $a['published_at'] ?: $a['created_at'];
            $dateB = $b['published_at'] ?: $b['created_at'];
            return strtotime($dateB) - strtotime($dateA);
        });
    }
    unset($posts);
    
} catch (PDOException $e) {
    // Handle error silently for now
    $sdgPosts = [];
}

// Include header
include 'app/includes/header.php';
?>

<style>
/* New page hero */
.page-hero { position: relative; padding: 80px 0; color: #fff; text-align: center; isolation: isolate; overflow: hidden; background: url('assets/images/FACADE.jpg') center/cover no-repeat; }
.page-hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(28,77,161,.85), rgba(82,123,189,.85)); z-index: 1; }
.page-hero .content { position: relative; z-index: 2; display: inline-block; padding: 24px 28px; border-radius: 16px; background: rgba(0,0,0,.55); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); box-shadow: 0 16px 40px rgba(0,0,0,.35); }
.page-hero .title { font-size: 3rem; font-weight: 800; line-height: 1.1; margin-bottom: 18px; text-shadow: 2px 2px 4px rgba(0,0,0,.3); }
.page-hero .subtitle { font-size: 1.05rem; margin: 0; }
@media (max-width: 1024px){ .page-hero{ padding:60px 0; } .page-hero .content{ padding:16px 18px; border-radius:12px; } .page-hero .title{ font-size:2.2rem; } .page-hero .subtitle{ font-size:1rem; } }
/* SDG Initiatives Page Colors - use site branding */
:root {
    --primary-blue: var(--primary-color);
    --secondary-blue: var(--secondary-color);
    --accent-green: #059669;
    --text-dark: #1f2937;
    --text-gray: #6b7280;
    --border-light: #e5e7eb;
    --bg-light: #f8fafc;
    --bg-accent: #f1f5f9;
    --white: #ffffff;
    
    /* SDG Colors */
    --sdg-1: #e5243b; /* No Poverty */
    --sdg-2: #dda63a; /* Zero Hunger */
    --sdg-3: #4c9f38; /* Good Health */
    --sdg-4: #c5192d; /* Quality Education */
    --sdg-5: #ff3a21; /* Gender Equality */
    --sdg-6: #26bde2; /* Clean Water */
    --sdg-7: #fcc30b; /* Clean Energy */
    --sdg-8: #a21942; /* Decent Work */
    --sdg-9: #fd6925; /* Industry Innovation */
    --sdg-10: #dd1367; /* Reduced Inequalities */
    --sdg-11: #fd9d24; /* Sustainable Cities */
    --sdg-12: #bf8b2e; /* Responsible Consumption */
    --sdg-13: #3f7e44; /* Climate Action */
    --sdg-14: #0a97d9; /* Life Below Water */
    --sdg-15: #56c02b; /* Life on Land */
    --sdg-16: #00689d; /* Peace Justice */
    --sdg-17: #19486a; /* Partnerships */
}

/* Use global Programs banner styles (inherited from CSS); remove page overrides */

/* Inherit global .hero-title and .hero-subtitle sizes from main stylesheet */

.sdg-content {
    padding: 3rem 0;
    background: var(--bg-light);
    position: relative;
    width: 100%;
}

.sdg-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.sdg-intro {
    background: white;
    padding: clamp(0.5rem, 1vw + 0.25rem, 1rem);
    margin-bottom: 1.5rem;
    border-radius: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border-left: 2px solid var(--primary-blue);
    text-align: center;
}

.sdg-intro h2 {
    color: var(--primary-blue);
    font-size: clamp(0.85rem, 1vw + 0.6rem, 1.2rem);
    font-weight: 600;
    margin-bottom: 0.3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
}

.sdg-intro h2::before {
    content: '🌍';
    font-size: 1.1rem;
}

.sdg-intro p {
    font-size: clamp(0.7rem, 0.5vw + 0.6rem, 0.85rem);
    color: var(--text-gray);
    line-height: 1.4;
    margin: 0;
}

.sdg-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 1rem;
    margin-bottom: 3rem;
}

.sdg-goal {
    background: var(--white);
    border-radius: 8px;
    padding: 1.5rem 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
    min-height: 140px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.sdg-goal::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    transition: all 0.3s ease;
}

.sdg-goal:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.sdg-goal:hover::before {
    height: 8px;
}

.sdg-goal-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--white);
    margin-bottom: 0.5rem;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.sdg-goal-title {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--white);
    line-height: 1.2;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    margin-bottom: 0.5rem;
}

.sdg-goal-icon {
    font-size: 1.5rem;
    color: var(--white);
    margin-bottom: 0.5rem;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.sdg-goal-icon i {
    font-size: 1.5rem;
}



/* Individual SDG Colors */
.sdg-goal-1 { background: var(--sdg-1); }
.sdg-goal-1::before { background: var(--sdg-1); }

.sdg-goal-2 { background: var(--sdg-2); }
.sdg-goal-2::before { background: var(--sdg-2); }

.sdg-goal-3 { background: var(--sdg-3); }
.sdg-goal-3::before { background: var(--sdg-3); }

.sdg-goal-4 { background: var(--sdg-4); }
.sdg-goal-4::before { background: var(--sdg-4); }

.sdg-goal-5 { background: var(--sdg-5); }
.sdg-goal-5::before { background: var(--sdg-5); }

.sdg-goal-6 { background: var(--sdg-6); }
.sdg-goal-6::before { background: var(--sdg-6); }

.sdg-goal-7 { background: var(--sdg-7); }
.sdg-goal-7::before { background: var(--sdg-7); }

.sdg-goal-8 { background: var(--sdg-8); }
.sdg-goal-8::before { background: var(--sdg-8); }

.sdg-goal-9 { background: var(--sdg-9); }
.sdg-goal-9::before { background: var(--sdg-9); }

.sdg-goal-10 { background: var(--sdg-10); }
.sdg-goal-10::before { background: var(--sdg-10); }

.sdg-goal-11 { background: var(--sdg-11); }
.sdg-goal-11::before { background: var(--sdg-11); }

.sdg-goal-12 { background: var(--sdg-12); }
.sdg-goal-12::before { background: var(--sdg-12); }

.sdg-goal-13 { background: var(--sdg-13); }
.sdg-goal-13::before { background: var(--sdg-13); }

.sdg-goal-14 { background: var(--sdg-14); }
.sdg-goal-14::before { background: var(--sdg-14); }

.sdg-goal-15 { background: var(--sdg-15); }
.sdg-goal-15::before { background: var(--sdg-15); }

.sdg-goal-16 { background: var(--sdg-16); }
.sdg-goal-16::before { background: var(--sdg-16); }

.sdg-goal-17 { background: var(--sdg-17); }
.sdg-goal-17::before { background: var(--sdg-17); }

/* SDG Full Report */
.sdg-goal-report {
    background: #6c757d; /* Gray color */
}

.sdg-goal-report:before {
    background: #6c757d;
}

.sdg-report-main {
    font-size: 2rem;
    font-weight: 700;
    color: var(--white);
    margin-bottom: 0.3rem;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    line-height: 1;
}

.sdg-report-subtitle {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--white);
    line-height: 1.2;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    margin-bottom: 0.5rem;
}

/* SDG Modal Header Top Border Colors */
.sdg-modal-header.sdg-header-1::before { background: var(--sdg-1); }
.sdg-modal-header.sdg-header-2::before { background: var(--sdg-2); }
.sdg-modal-header.sdg-header-3::before { background: var(--sdg-3); }
.sdg-modal-header.sdg-header-4::before { background: var(--sdg-4); }
.sdg-modal-header.sdg-header-5::before { background: var(--sdg-5); }
.sdg-modal-header.sdg-header-6::before { background: var(--sdg-6); }
.sdg-modal-header.sdg-header-7::before { background: var(--sdg-7); }
.sdg-modal-header.sdg-header-8::before { background: var(--sdg-8); }
.sdg-modal-header.sdg-header-9::before { background: var(--sdg-9); }
.sdg-modal-header.sdg-header-10::before { background: var(--sdg-10); }
.sdg-modal-header.sdg-header-11::before { background: var(--sdg-11); }
.sdg-modal-header.sdg-header-12::before { background: var(--sdg-12); }
.sdg-modal-header.sdg-header-13::before { background: var(--sdg-13); }
.sdg-modal-header.sdg-header-14::before { background: var(--sdg-14); }
.sdg-modal-header.sdg-header-15::before { background: var(--sdg-15); }
.sdg-modal-header.sdg-header-16::before { background: var(--sdg-16); }
.sdg-modal-header.sdg-header-17::before { background: var(--sdg-17); }
.sdg-modal-header.sdg-header-report::before { background: #6c757d; }

/* Modal Styles */
.sdg-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    backdrop-filter: blur(5px);
}

.sdg-modal-content {
    background-color: var(--white);
    margin: 2% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 800px;
    max-height: 92vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    animation: modalSlideIn 0.3s ease;
    display: flex;
    flex-direction: column;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.sdg-modal-header {
    padding: 1.25rem 2rem;
    border-bottom: 1px solid var(--border-light);
    position: relative;
    transition: background-color 0.3s ease;
}

.sdg-modal-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-blue);
    border-radius: 12px 12px 0 0;
    transition: background-color 0.3s ease;
}

.sdg-modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-gray);
    padding: 0;
    border-radius: 50%;
    transition: all 0.3s ease;
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
}

.sdg-modal-close:hover {
    color: var(--text-dark);
}

.sdg-modal-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.sdg-modal-title span:not(.sdg-modal-number) {
    font-size: 1.2rem;
    font-weight: 600;
    font-family: 'Barlow Semi Condensed', sans-serif;
}

.sdg-modal-number {
    background: var(--primary-blue);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 1.2rem;
    font-weight: 700;
}

.sdg-modal-body {
    padding: 2rem;
    flex: 1;
    overflow-y: auto;
}

.sdg-modal-description {
    font-size: 1.1rem;
    line-height: 1.6;
    color: var(--text-gray);
    margin-bottom: 2rem;
}

.sdg-programs-section {
    background: var(--bg-accent);
    padding: 1.5rem;
    border-radius: 8px;
    margin-top: 1.5rem;
}

.sdg-programs-section h4 {
    color: var(--primary-blue);
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sdg-programs-section h4::before {
    content: '🎓';
    font-size: 1.1rem;
}

.sdg-programs-placeholder {
    text-align: center;
    padding: 2rem;
    color: var(--text-gray);
    font-style: italic;
}

.sdg-posts-list {
    display: grid;
    gap: 1rem;
}

.sdg-post-item {
    background: var(--white);
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-left: 4px solid var(--primary-blue);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 1rem;
}

.sdg-post-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.sdg-post-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.sdg-post-image {
    margin: 1rem 0;
    border-radius: 8px;
    overflow: hidden;
}

.sdg-post-image img {
    width: 100%;
    height: auto;
    max-height: 300px;
    object-fit: cover;
    display: block;
}

.sdg-post-content {
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
}

.sdg-excerpt {
    text-align: justify;
    text-indent: 0 !important;
    margin-bottom: 15px;
}

.sdg-post-content > a.read-more-btn {
    align-self: center; /* Centers the button */
    margin-top: 10px;
}

.read-more-btn {
    display: inline-block;
    padding: 10px 24px;
    background-color: #1c4da1;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.read-more-btn:hover {
    background-color: #527bbd;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.sdg-post-content p {
    margin-bottom: 0.75rem;
}

.sdg-post-content p:last-child {
    margin-bottom: 0;
}

.sdg-post-meta {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    font-size: 0.8rem;
    color: var(--text-gray);
    border-top: 1px solid var(--border-light);
    padding-top: 0.75rem;
}

.sdg-post-date {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.sdg-programs-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sdg-program-item {
    background: white;
    padding: 1rem;
    margin-bottom: 0.5rem;
    border-radius: 6px;
    border-left: 4px solid var(--primary-blue);
    transition: all 0.3s ease;
}

.sdg-program-item:hover {
    transform: translateX(4px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.sdg-program-title {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.3rem;
}

.sdg-program-description {
    font-size: 0.9rem;
    color: var(--text-gray);
    line-height: 1.4;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .sdg-grid {
        grid-template-columns: repeat(5, 1fr);
    }
}

@media (max-width: 992px) {
    .sdg-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (max-width: 768px) {
    .sdg-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 0.8rem;
    }
    .sdg-intro { padding: 0.75rem; }
    .sdg-intro h2 { margin-bottom: 0.25rem; }
    .sdg-intro p { line-height: 1.3; }
    
    .sdg-goal {
        padding: 1rem 0.5rem;
        min-height: 120px;
    }
    
    .sdg-goal-number {
        font-size: 1.5rem;
    }
    
    .sdg-goal-title {
        font-size: 0.7rem;
    }
    
    .sdg-report-main {
        font-size: 1.5rem;
    }
    
    .sdg-report-subtitle {
        font-size: 0.7rem;
    }
    
    .sdg-modal-content {
        width: 95%;
        margin: 5% auto;
        max-height: 90vh;
    }
    
    .sdg-modal-header {
        padding: 1rem 1.2rem;
    }
    
    .sdg-modal-body {
        padding: 1.2rem;
        max-height: calc(90vh - 100px);
    }
    
    .sdg-modal-title {
        font-size: 1.4rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .sdg-modal-number {
        font-size: 1rem;
        padding: 0.4rem 0.8rem;
    }
    
    .sdg-modal-description {
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 1.5rem;
    }
    
    .sdg-programs-section {
        padding: 1rem;
    }
    
    .sdg-programs-section h4 {
        font-size: 1rem;
        margin-bottom: 0.8rem;
    }
    
    .sdg-post-item {
        padding: 1rem;
    }
    
    .sdg-post-title {
        font-size: 1rem;
        line-height: 1.3;
    }
    
    .sdg-post-content {
        font-size: 0.85rem;
        line-height: 1.5;
    }
    
    .sdg-post-content p {
        margin-bottom: 0.6rem;
    }
    
    .sdg-post-meta {
        font-size: 0.75rem;
        padding-top: 0.6rem;
    }
    
    .sdg-program-item {
        padding: 0.8rem;
    }
    
    .sdg-program-title {
        font-size: 0.9rem;
    }
    
    .sdg-program-description {
        font-size: 0.8rem;
        line-height: 1.3;
    }
}

@media (max-width: 480px) {
    .sdg-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .sdg-intro { 
        padding: 0.5rem; 
    }
    
    .sdg-modal-content {
        width: 98%;
        margin: 2% auto;
        max-height: 95vh;
    }
    
    .sdg-modal-header {
        padding: 0.8rem 1rem;
    }
    
    .sdg-modal-body {
        padding: 1rem;
        max-height: calc(95vh - 80px);
    }
    
    .sdg-modal-title {
        font-size: 1.2rem;
        gap: 0.3rem;
    }
    
    .sdg-modal-number {
        font-size: 0.9rem;
        padding: 0.3rem 0.6rem;
    }
    
    .sdg-modal-description {
        font-size: 0.85rem;
        line-height: 1.4;
        margin-bottom: 1.2rem;
    }
    
    .sdg-programs-section {
        padding: 0.8rem;
    }
    
    .sdg-programs-section h4 {
        font-size: 0.9rem;
        margin-bottom: 0.6rem;
    }
    
    .sdg-post-item {
        padding: 0.8rem;
    }
    
    .sdg-post-title {
        font-size: 0.9rem;
        line-height: 1.2;
    }
    
    .sdg-post-content {
        font-size: 0.8rem;
        line-height: 1.4;
    }
    
    .sdg-post-content p {
        margin-bottom: 0.5rem;
    }
    
    .sdg-post-meta {
        font-size: 0.7rem;
        padding-top: 0.5rem;
    }
    
    .sdg-program-item {
        padding: 0.6rem;
    }
    
    .sdg-program-title {
        font-size: 0.85rem;
    }
    
    .sdg-program-description {
        font-size: 0.75rem;
        line-height: 1.2;
    }
    
    #pdfViewerContainer {
        height: calc(95vh - 120px);
        min-height: 400px;
    }
}

/* PDF Viewer Styles */
#pdfViewerContainer {
    background: var(--bg-accent);
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1rem;
}

#pdfViewer {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>

<!-- New Banner -->
<section class="page-hero">
    <div class="container">
        <div class="content">
            <h1 class="title">Sustainable Development Goals</h1>
            <p class="subtitle">UPHSL's Commitment to Global Impact and Sustainable Development</p>
        </div>
    </div>
    </section>

<!-- SDG Content -->
<section class="sdg-content">
    <div class="sdg-container">
        <!-- Introduction -->
        <div class="sdg-intro">
            <h2>Sustainable Development Goals Initiatives</h2>
            <p>The University of Perpetual Help System Laguna is committed to advancing the United Nations Sustainable Development Goals through innovative programs, research, and community engagement. Click on any goal below to learn more about our initiatives and programs.</p>
        </div>

        <!-- SDG Goals Grid -->
        <div class="sdg-grid">
            <!-- Goal 1: No Poverty -->
            <div class="sdg-goal sdg-goal-1" data-goal="1">
                <div class="sdg-goal-number">1</div>
                <div class="sdg-goal-title">NO POVERTY</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>

            <!-- Goal 2: Zero Hunger -->
            <div class="sdg-goal sdg-goal-2" data-goal="2">
                <div class="sdg-goal-number">2</div>
                <div class="sdg-goal-title">ZERO HUNGER</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-utensils"></i>
                </div>
            </div>

            <!-- Goal 3: Good Health -->
            <div class="sdg-goal sdg-goal-3" data-goal="3">
                <div class="sdg-goal-number">3</div>
                <div class="sdg-goal-title">GOOD HEALTH AND WELL-BEING</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
            </div>

            <!-- Goal 4: Quality Education -->
            <div class="sdg-goal sdg-goal-4" data-goal="4">
                <div class="sdg-goal-number">4</div>
                <div class="sdg-goal-title">QUALITY EDUCATION</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>

            <!-- Goal 5: Gender Equality -->
            <div class="sdg-goal sdg-goal-5" data-goal="5">
                <div class="sdg-goal-number">5</div>
                <div class="sdg-goal-title">GENDER EQUALITY</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-balance-scale"></i>
                </div>
            </div>

            <!-- Goal 6: Clean Water -->
            <div class="sdg-goal sdg-goal-6" data-goal="6">
                <div class="sdg-goal-number">6</div>
                <div class="sdg-goal-title">CLEAN WATER AND SANITATION</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-tint"></i>
                </div>
            </div>

            <!-- Goal 7: Clean Energy -->
            <div class="sdg-goal sdg-goal-7" data-goal="7">
                <div class="sdg-goal-number">7</div>
                <div class="sdg-goal-title">AFFORDABLE AND CLEAN ENERGY</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-bolt"></i>
                </div>
            </div>

            <!-- Goal 8: Decent Work -->
            <div class="sdg-goal sdg-goal-8" data-goal="8">
                <div class="sdg-goal-number">8</div>
                <div class="sdg-goal-title">DECENT WORK AND ECONOMIC GROWTH</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>

            <!-- Goal 9: Industry Innovation -->
            <div class="sdg-goal sdg-goal-9" data-goal="9">
                <div class="sdg-goal-number">9</div>
                <div class="sdg-goal-title">INDUSTRY, INNOVATION AND INFRASTRUCTURE</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-industry"></i>
                </div>
            </div>

            <!-- Goal 10: Reduced Inequalities -->
            <div class="sdg-goal sdg-goal-10" data-goal="10">
                <div class="sdg-goal-number">10</div>
                <div class="sdg-goal-title">REDUCED INEQUALITIES</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-equals"></i>
                </div>
            </div>

            <!-- Goal 11: Sustainable Cities -->
            <div class="sdg-goal sdg-goal-11" data-goal="11">
                <div class="sdg-goal-number">11</div>
                <div class="sdg-goal-title">SUSTAINABLE CITIES AND COMMUNITIES</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-city"></i>
                </div>
            </div>

            <!-- Goal 12: Responsible Consumption -->
            <div class="sdg-goal sdg-goal-12" data-goal="12">
                <div class="sdg-goal-number">12</div>
                <div class="sdg-goal-title">RESPONSIBLE CONSUMPTION AND PRODUCTION</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-recycle"></i>
                </div>
            </div>

            <!-- Goal 13: Climate Action -->
            <div class="sdg-goal sdg-goal-13" data-goal="13">
                <div class="sdg-goal-number">13</div>
                <div class="sdg-goal-title">CLIMATE ACTION</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-globe"></i>
                </div>
            </div>

            <!-- Goal 14: Life Below Water -->
            <div class="sdg-goal sdg-goal-14" data-goal="14">
                <div class="sdg-goal-number">14</div>
                <div class="sdg-goal-title">LIFE BELOW WATER</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-fish"></i>
                </div>
            </div>

            <!-- Goal 15: Life on Land -->
            <div class="sdg-goal sdg-goal-15" data-goal="15">
                <div class="sdg-goal-number">15</div>
                <div class="sdg-goal-title">LIFE ON LAND</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-tree"></i>
                </div>
            </div>

            <!-- Goal 16: Peace Justice -->
            <div class="sdg-goal sdg-goal-16" data-goal="16">
                <div class="sdg-goal-number">16</div>
                <div class="sdg-goal-title">PEACE, JUSTICE AND STRONG INSTITUTIONS</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-dove"></i>
                </div>
            </div>

            <!-- Goal 17: Partnerships -->
            <div class="sdg-goal sdg-goal-17" data-goal="17">
                <div class="sdg-goal-number">17</div>
                <div class="sdg-goal-title">PARTNERSHIPS FOR THE GOALS</div>
                <div class="sdg-goal-icon">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>

            <!-- SDG Full Report -->
            <div class="sdg-goal sdg-goal-report" data-goal="report">
                <div class="sdg-report-main">SDG</div>
                <div class="sdg-report-subtitle">Full Report</div>
                    <div class="sdg-goal-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
            </div>
        </div>
    </div>
</section>

<!-- SDG Modal -->
<div id="sdgModal" class="sdg-modal">
    <div class="sdg-modal-content">
        <div class="sdg-modal-header">
            <button class="sdg-modal-close" id="sdgModalClose">&times;</button>
            <div class="sdg-modal-title">
                <span class="sdg-modal-number" id="modalGoalNumber">1</span>
                <span id="modalGoalTitle">NO POVERTY</span>
            </div>
        </div>
        <div class="sdg-modal-body">
            <div class="sdg-modal-description" id="modalGoalDescription">
                <!-- Goal description will be loaded here -->
            </div>
            
            <!-- PDF Viewer Container (hidden by default) -->
            <div id="pdfViewerContainer" style="display: none; width: 100%; height: calc(92vh - 150px); min-height: 500px;">
                <iframe id="pdfViewer" src="" style="width: 100%; height: 100%; border: none; border-radius: 8px;"></iframe>
            </div>
            
            <div class="sdg-programs-section" id="programsSection">
                <h4>UPHSL Programs & Initiatives</h4>
                <div class="sdg-programs-placeholder" id="programsPlaceholder" style="display: none;">
                    No initiatives posted for this SDG yet.
                </div>
                <div class="sdg-posts-list" id="programsList">
                    <!-- SDG posts will be loaded here dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>


<script>
// SDG Goals Data
const sdgGoals = {
    1: {
        title: "NO POVERTY",
        description: "End poverty in all its forms everywhere. UPHSL addresses poverty through scholarship programs, community outreach, and economic development initiatives that provide opportunities for underprivileged students and families.",
        icon: "👨‍👩‍👧‍👦"
    },
    2: {
        title: "ZERO HUNGER",
        description: "End hunger, achieve food security and improved nutrition, and promote sustainable agriculture. UPHSL supports food security through nutrition programs, community gardens, and partnerships with local food organizations.",
        icon: "🍲"
    },
    3: {
        title: "GOOD HEALTH AND WELL-BEING",
        description: "Ensure healthy lives and promote well-being for all at all ages. UPHSL's medical programs, health clinics, and wellness initiatives contribute to better health outcomes in our communities.",
        icon: "💗"
    },
    4: {
        title: "QUALITY EDUCATION",
        description: "Ensure inclusive and equitable quality education and promote lifelong learning opportunities for all. UPHSL provides accessible, high-quality education across all levels from basic education to graduate studies.",
        icon: "📚"
    },
    5: {
        title: "GENDER EQUALITY",
        description: "Achieve gender equality and empower all women and girls. UPHSL promotes gender equality through inclusive policies, women's leadership programs, and equal opportunities for all students.",
        icon: "⚖️"
    },
    6: {
        title: "CLEAN WATER AND SANITATION",
        description: "Ensure availability and sustainable management of water and sanitation for all. UPHSL implements water conservation programs and promotes clean water initiatives in our communities.",
        icon: "💧"
    },
    7: {
        title: "AFFORDABLE AND CLEAN ENERGY",
        description: "Ensure access to affordable, reliable, sustainable and modern energy for all. UPHSL invests in renewable energy solutions and promotes energy efficiency across campus facilities.",
        icon: "⚡"
    },
    8: {
        title: "DECENT WORK AND ECONOMIC GROWTH",
        description: "Promote sustained, inclusive and sustainable economic growth, full and productive employment and decent work for all. UPHSL prepares students for meaningful careers and supports entrepreneurship programs.",
        icon: "📈"
    },
    9: {
        title: "INDUSTRY, INNOVATION AND INFRASTRUCTURE",
        description: "Build resilient infrastructure, promote inclusive and sustainable industrialization and foster innovation. UPHSL's engineering and technology programs drive innovation and infrastructure development.",
        icon: "🏗️"
    },
    10: {
        title: "REDUCED INEQUALITIES",
        description: "Reduce inequality within and among countries. UPHSL promotes social inclusion through diverse programs, accessibility initiatives, and equal opportunities for all students regardless of background.",
        icon: "⚖️"
    },
    11: {
        title: "SUSTAINABLE CITIES AND COMMUNITIES",
        description: "Make cities and human settlements inclusive, safe, resilient and sustainable. UPHSL contributes to sustainable urban development through research, community planning, and environmental initiatives.",
        icon: "🏙️"
    },
    12: {
        title: "RESPONSIBLE CONSUMPTION AND PRODUCTION",
        description: "Ensure sustainable consumption and production patterns. UPHSL promotes sustainable practices through waste reduction programs, recycling initiatives, and responsible resource management.",
        icon: "♻️"
    },
    13: {
        title: "CLIMATE ACTION",
        description: "Take urgent action to combat climate change and its impacts. UPHSL addresses climate change through environmental research, sustainability programs, and climate education initiatives.",
        icon: "🌍"
    },
    14: {
        title: "LIFE BELOW WATER",
        description: "Conserve and sustainably use the oceans, seas and marine resources for sustainable development. UPHSL supports marine conservation through research programs and environmental awareness campaigns.",
        icon: "🐟"
    },
    15: {
        title: "LIFE ON LAND",
        description: "Protect, restore and promote sustainable use of terrestrial ecosystems, sustainably manage forests, combat desertification, and halt and reverse land degradation and halt biodiversity loss. UPHSL promotes biodiversity conservation and sustainable land use practices.",
        icon: "🌳"
    },
    16: {
        title: "PEACE, JUSTICE AND STRONG INSTITUTIONS",
        description: "Promote peaceful and inclusive societies for sustainable development, provide access to justice for all and build effective, accountable and inclusive institutions at all levels. UPHSL fosters peace and justice through law programs, conflict resolution, and civic engagement.",
        icon: "🕊️"
    },
    17: {
        title: "PARTNERSHIPS FOR THE GOALS",
        description: "Strengthen the means of implementation and revitalize the global partnership for sustainable development. UPHSL builds partnerships with local and international organizations to advance all SDGs through collaborative initiatives.",
        icon: "🤝"
    }
};

// Modal functionality
const modal = document.getElementById('sdgModal');
const modalClose = document.getElementById('sdgModalClose');
const modalGoalNumber = document.getElementById('modalGoalNumber');
const modalGoalTitle = document.getElementById('modalGoalTitle');
const modalGoalDescription = document.getElementById('modalGoalDescription');
const programsPlaceholder = document.getElementById('programsPlaceholder');
const programsList = document.getElementById('programsList');

// Store scroll position
let scrollPosition = 0;

// SDG Posts Data (passed from PHP)
const sdgPostsData = <?php echo json_encode($sdgPosts); ?>;

// Open modal when SDG goal is clicked
document.querySelectorAll('.sdg-goal').forEach(goal => {
    goal.addEventListener('click', function() {
        const goalType = this.dataset.goal;
        
        // Store current scroll position
        scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
        
        // Check if it's the SDG Full Report
        if (goalType === 'report') {
            openReportModal();
        } else {
            // Regular SDG goal
            const goalNumber = parseInt(goalType);
            const goalData = sdgGoals[goalNumber];
        
        // Remove any existing header color classes
        const modalHeader = document.querySelector('.sdg-modal-header');
        modalHeader.className = modalHeader.className.replace(/sdg-header-\d+/g, '');
        
        // Add the specific SDG header color class
        modalHeader.classList.add(`sdg-header-${goalNumber}`);
        
        modalGoalNumber.textContent = goalNumber;
        modalGoalTitle.textContent = goalData.title;
        modalGoalDescription.textContent = goalData.description;
            
            // Reset modal width for regular SDG content
            const modalContent = document.querySelector('.sdg-modal-content');
            modalContent.style.maxWidth = '800px';
            modalContent.style.width = '90%';
            
            // Hide PDF viewer, show regular content
            document.getElementById('pdfViewerContainer').style.display = 'none';
            document.getElementById('modalGoalDescription').style.display = 'block';
            document.getElementById('programsSection').style.display = 'block';
        
        // Display SDG posts for this goal
        displaySdgPosts(goalNumber);
        
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        document.body.style.position = 'fixed';
        document.body.style.width = '100%';
        document.body.style.top = `-${scrollPosition}px`;
        }
    });
});

// Function to open the SDG Full Report modal
function openReportModal() {
    const modalHeader = document.querySelector('.sdg-modal-header');
    const modalContent = document.querySelector('.sdg-modal-content');
    modalHeader.className = modalHeader.className.replace(/sdg-header-\d+/g, '');
    modalHeader.classList.add('sdg-header-report');
    
    // Make modal wider for PDF viewing
    modalContent.style.maxWidth = '95%';
    modalContent.style.width = '95%';
    
    modalGoalNumber.textContent = 'REPORT';
    modalGoalTitle.textContent = 'SDG Report for Academic Year 2023-2024';
    modalGoalDescription.textContent = '';
    
    // Hide regular content, show PDF viewer
    document.getElementById('modalGoalDescription').style.display = 'none';
    document.getElementById('programsSection').style.display = 'none';
    document.getElementById('pdfViewerContainer').style.display = 'block';
    
    // Set PDF source from database setting (use default only if setting exists)
    <?php 
    $pdfPath = getSetting('sdg_full_report_pdf');
    if (!$pdfPath || !file_exists($pdfPath)) {
        // Try to find the 2023-2024 report as fallback
        $fallbackPath = 'assets/documents/pdfs/SDG Report for Academic Year 2023-2024.pdf';
        if (file_exists($fallbackPath)) {
            $pdfPath = $fallbackPath;
        } else {
            $pdfPath = null;
        }
    }
    ?>
    const pdfPath = <?php echo $pdfPath ? "'" . htmlspecialchars($pdfPath, ENT_QUOTES, "UTF-8") . "'" : "null"; ?>;
    // Clear iframe first to force reload, then set with cache-busting parameter
    const pdfViewer = document.getElementById('pdfViewer');
    pdfViewer.src = '';
    
    if (pdfPath) {
        // Add timestamp to bypass browser cache
        pdfViewer.src = pdfPath + '?t=' + new Date().getTime();
    } else {
        // Show error message if no PDF is set
        document.getElementById('pdfViewerContainer').innerHTML = '<div style="padding: 2rem; text-align: center; color: #dc3545;"><i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem;"></i><p>No SDG Full Report PDF has been uploaded yet.</p><p>Please contact an administrator to upload the PDF.</p></div>';
    }
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    document.body.style.position = 'fixed';
    document.body.style.width = '100%';
    document.body.style.top = `-${scrollPosition}px`;
}

// Function to display SDG posts
function displaySdgPosts(goalNumber) {
    const postsList = document.getElementById('programsList');
    const placeholder = document.getElementById('programsPlaceholder');
    
    if (sdgPostsData[goalNumber] && sdgPostsData[goalNumber].length > 0) {
        // Hide placeholder and show posts
        placeholder.style.display = 'none';
        postsList.style.display = 'block';
        
        // Generate HTML for posts
        let postsHTML = '';
        sdgPostsData[goalNumber].forEach(post => {
            const publishedDate = new Date(post.published_at || post.created_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            // Determine post link based on post type
            const postLink = post.post_type === 'regular_post' 
                ? `post.php?slug=${post.slug}` 
                : `sdg-post.php?slug=${post.slug}`;
            
            // Get featured image - check different possible fields
            const featuredImage = post.featured_image || (post.images && post.images[0] && post.images[0].image_path) || '';
            
            postsHTML += `
                <div class="sdg-post-item">
                    <div class="sdg-post-title">${post.title}</div>
                    ${featuredImage ? `<div class="sdg-post-image"><img src="${featuredImage}" alt="${post.title}" /></div>` : ''}
                    <div class="sdg-post-content">
                        <div class="sdg-excerpt">
                            ${(() => {
                                // Use excerpt field if available, otherwise extract from content
                                let excerpt = '';
                                
                                // Prefer database excerpt field if it exists and is not empty
                                if (post.excerpt && post.excerpt.trim() !== '') {
                                    excerpt = post.excerpt.trim();
                                } else if (post.content) {
                                    // Create a temporary div to parse HTML
                                    const tempDiv = document.createElement('div');
                                    tempDiv.innerHTML = post.content;
                                    
                                    // Try to get first paragraph
                                    const firstParagraph = tempDiv.querySelector('p');
                                    
                                    if (firstParagraph) {
                                        // Get text content from first paragraph
                                        excerpt = firstParagraph.textContent || firstParagraph.innerText || '';
                                    } else {
                                        // If no paragraph tags, get all text content
                                        excerpt = tempDiv.textContent || tempDiv.innerText || '';
                                    }
                                    
                                    // If still empty, try plain text extraction
                                    if (!excerpt || excerpt.trim() === '') {
                                        // Strip HTML tags manually
                                        excerpt = post.content.replace(/<[^>]*>/g, '');
                                        // Get first line or first 200 chars
                                        const firstLine = excerpt.split('\n')[0];
                                        excerpt = firstLine || excerpt.substring(0, 200);
                                    }
                                    
                                    // Clean up whitespace
                                    excerpt = excerpt.trim().replace(/^[\s\u00A0]+/, '').replace(/\s+/g, ' ');
                                    
                                    // Limit to 200 characters
                                    const maxLength = 200;
                                    if (excerpt.length > maxLength) {
                                        excerpt = excerpt.substring(0, maxLength);
                                        // Don't cut in the middle of a word
                                        const lastSpace = excerpt.lastIndexOf(' ');
                                        if (lastSpace > maxLength * 0.8) {
                                            excerpt = excerpt.substring(0, lastSpace);
                                        }
                                        excerpt += '...';
                                    }
                                }
                                
                                return excerpt || 'No content available.';
                            })()}
                        </div>
                        <div style="text-align: center;">
                            <a class="read-more-btn" href="${postLink}">Read More</a>
                        </div>
                    </div>
                    <div class="sdg-post-meta">
                        <!--<div class="sdg-post-date">
                            <i class="fas fa-calendar"></i>
                            ${publishedDate}
                        </div>-->
                    </div>
                </div>
            `;
        });
        
        postsList.innerHTML = postsHTML;
    } else {
        // Show placeholder if no posts
        placeholder.style.display = 'block';
        postsList.style.display = 'none';
    }
}

// Function to close modal and restore scroll position
function closeModal() {
    const modalContent = document.querySelector('.sdg-modal-content');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    document.body.style.position = '';
    document.body.style.width = '';
    document.body.style.top = '';
    
    // Clear PDF viewer to stop loading
    document.getElementById('pdfViewer').src = '';
    
    // Reset modal width
    modalContent.style.maxWidth = '800px';
    modalContent.style.width = '90%';
    
    // Restore scroll position
    window.scrollTo(0, scrollPosition);
}

// Close modal
modalClose.addEventListener('click', closeModal);

// Close modal when clicking outside
modal.addEventListener('click', function(e) {
    if (e.target === modal) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.style.display === 'block') {
        closeModal();
    }
});

// Add hover effects for better interactivity
document.querySelectorAll('.sdg-goal').forEach(goal => {
    goal.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-4px) scale(1.02)';
    });
    
    goal.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// Check URL parameter and open modal automatically
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const sdgParam = urlParams.get('sdg');
    
    if (sdgParam) {
        // Small delay to ensure page is fully loaded
        setTimeout(function() {
            if (sdgParam === 'report') {
                openReportModal();
            } else {
                const goalNumber = parseInt(sdgParam);
                if (goalNumber >= 1 && goalNumber <= 17 && sdgGoals[goalNumber]) {
                    const goalElement = document.querySelector(`.sdg-goal[data-goal="${goalNumber}"]`);
                    if (goalElement) {
                        goalElement.click();
                    }
                }
            }
        }, 100);
    }
});

</script>

<?php
// Include footer
include 'app/includes/footer.php';
?>
