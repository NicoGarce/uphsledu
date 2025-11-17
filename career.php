<?php
/**
 * UPHSL Individual Career Posting Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Displays individual career postings for the UPHSL website
 */

session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Get career posting slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    http_response_code(404);
    include '404.php';
    exit;
}

// Get career posting by slug
$career = getCareerPostingBySlug($slug);

if (!$career) {
    http_response_code(404);
    include '404.php';
    exit;
}

// Increment view count
incrementCareerPostingViews($career['id']);

// Get recent career postings for sidebar
$recentCareers = getPublishedCareerPostings(5);

// Set base path for assets
$base_path = '';

// Build current career posting absolute URL for social sharing
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$currentUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$encodedUrl = urlencode($currentUrl);
$encodedTitle = urlencode($career['position']);
$shareFacebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $encodedUrl;
$shareTwitter = 'https://twitter.com/intent/tweet?url=' . $encodedUrl . '&text=' . $encodedTitle;
$shareLinkedIn = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encodedUrl;

// Open Graph data for social sharing
$absoluteBase = $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$ogImage = $absoluteBase . '/assets/images/Logos/logo.png';

// Create description for social sharing
$description = substr(strip_tags($career['job_description']), 0, 160);
if (strlen($description) > 160) {
    $description = substr($description, 0, 157) . '...';
}

$og = [
    'title' => $career['position'] . ' - UPHSL Careers',
    'description' => $description,
    'url' => $currentUrl,
    'image' => $ogImage,
    'type' => 'article',
    'site_name' => 'University of Perpetual Help System Laguna',
    'article_author' => 'University of Perpetual Help System Laguna',
    'article_publisher' => 'University of Perpetual Help System Laguna',
    'article_published_time' => $career['published_at'] ?: $career['created_at'],
    'article_modified_time' => $career['updated_at'] ?: $career['created_at']
];

// Set page title
$page_title = htmlspecialchars($career['position']) . ' - Careers';

// Include header
include 'app/includes/header.php';
?>

    <!-- Career Posting Content -->
    <div class="career-page-container">
        <div class="career-main-content">
            <!-- Career Posting Header Card -->
            <div class="career-header-card">
                <div class="career-header-content">
                    <h1 class="career-position-title"><?php echo htmlspecialchars($career['position']); ?></h1>
                    <div class="career-meta-info">
                        <span class="career-posted-date">
                            <i class="fas fa-calendar-alt"></i>
                            Posted <?php echo formatDate($career['published_at'] ?: $career['created_at']); ?>
                        </span>
                    </div>
                    <div class="career-company-info">
                        <div class="company-logo-placeholder">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="company-details">
                            <h2 class="company-name">University of Perpetual Help System Laguna</h2>
                            <div class="career-badges">
                                <span class="career-badge location-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($career['location']); ?>
                                </span>
                                <span class="career-badge type-badge">
                                    <i class="fas fa-clock"></i>
                                    <?php echo htmlspecialchars($career['employment_type']); ?>
                                </span>
                                <?php if (isset($career['views']) && $career['views'] > 0): ?>
                                <span class="career-badge views-badge">
                                    <i class="fas fa-eye"></i>
                                    <?php echo number_format($career['views']); ?> views
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Career Posting Content -->
            <div class="career-content-body">
                <!-- Job Description Section -->
                <div class="career-content-section">
                    <h2 class="career-section-heading">Job Description</h2>
                    <div class="career-section-text">
                        <?php 
                        $jobDescription = html_entity_decode($career['job_description'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $hasHtmlTags = strip_tags($jobDescription) !== $jobDescription;
                        
                        if ($hasHtmlTags) {
                            $allowedTags = '<p><br><strong><b><em><i><u><s><strike><del><ins><a><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><pre><code><span><div>';
                            $jobDescription = strip_tags($jobDescription, $allowedTags);
                            $jobDescription = trim($jobDescription);
                            echo $jobDescription;
                        } else {
                            $paragraphs = preg_split('/\n\s*\n/', $jobDescription);
                            foreach ($paragraphs as $paragraph) {
                                $paragraph = trim($paragraph);
                                if (!empty($paragraph)) {
                                    $paragraph = htmlspecialchars($paragraph, ENT_NOQUOTES, 'UTF-8');
                                    $paragraph = nl2br($paragraph);
                                    echo '<p>' . $paragraph . '</p>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- Requirements Section -->
                <div class="career-content-section">
                    <h2 class="career-section-heading">Requirements</h2>
                    <div class="career-section-text">
                        <?php 
                        $requirements = html_entity_decode($career['requirements'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $hasHtmlTags = strip_tags($requirements) !== $requirements;
                        
                        if ($hasHtmlTags) {
                            $allowedTags = '<p><br><strong><b><em><i><u><s><strike><del><ins><a><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><pre><code><span><div>';
                            $requirements = strip_tags($requirements, $allowedTags);
                            $requirements = trim($requirements);
                            echo $requirements;
                        } else {
                            $paragraphs = preg_split('/\n\s*\n/', $requirements);
                            foreach ($paragraphs as $paragraph) {
                                $paragraph = trim($paragraph);
                                if (!empty($paragraph)) {
                                    $paragraph = htmlspecialchars($paragraph, ENT_NOQUOTES, 'UTF-8');
                                    $paragraph = nl2br($paragraph);
                                    echo '<p>' . $paragraph . '</p>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- Application Details Section -->
                <div class="career-content-section">
                    <h2 class="career-section-heading">How to Apply</h2>
                    <div class="career-section-text">
                        <?php 
                        $applicationDetails = html_entity_decode($career['application_details'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $hasHtmlTags = strip_tags($applicationDetails) !== $applicationDetails;
                        
                        if ($hasHtmlTags) {
                            $allowedTags = '<p><br><strong><b><em><i><u><s><strike><del><ins><a><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><pre><code><span><div>';
                            $applicationDetails = strip_tags($applicationDetails, $allowedTags);
                            $applicationDetails = trim($applicationDetails);
                            echo $applicationDetails;
                        } else {
                            $paragraphs = preg_split('/\n\s*\n/', $applicationDetails);
                            foreach ($paragraphs as $paragraph) {
                                $paragraph = trim($paragraph);
                                if (!empty($paragraph)) {
                                    $paragraph = htmlspecialchars($paragraph, ENT_NOQUOTES, 'UTF-8');
                                    // Convert URLs to clickable links
                                    $paragraph = preg_replace_callback(
                                        '/(https?:\/\/[^\s<>"\'\)]+(?:\s+[^\s<>"\'\)]+)*)/',
                                        function($matches) {
                                            $url = trim($matches[0]);
                                            $url = rtrim($url, '.,;:!?');
                                            $encodedUrl = str_replace(' ', '%20', $url);
                                            $displayUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
                                            return '<a href="' . htmlspecialchars($encodedUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . $displayUrl . '</a>';
                                        },
                                        $paragraph
                                    );
                                    $paragraph = nl2br($paragraph);
                                    echo '<p>' . $paragraph . '</p>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="career-sidebar">
            <!-- Quick Info Card -->
            <div class="career-sidebar-card">
                <h3 class="sidebar-card-title">Job Details</h3>
                <div class="job-details-list">
                    <div class="job-detail-item">
                        <div class="job-detail-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Location
                        </div>
                        <div class="job-detail-value"><?php echo htmlspecialchars($career['location']); ?></div>
                    </div>
                    <div class="job-detail-item">
                        <div class="job-detail-label">
                            <i class="fas fa-briefcase"></i>
                            Employment Type
                        </div>
                        <div class="job-detail-value"><?php echo htmlspecialchars($career['employment_type']); ?></div>
                    </div>
                    <div class="job-detail-item">
                        <div class="job-detail-label">
                            <i class="fas fa-calendar"></i>
                            Posted
                        </div>
                        <div class="job-detail-value"><?php echo formatDate($career['published_at'] ?: $career['created_at']); ?></div>
                    </div>
                </div>
            </div>

            <!-- Share Section -->
            <div class="career-sidebar-card">
                <h3 class="sidebar-card-title">Share this Job</h3>
                <div class="career-share-buttons">
                    <a href="<?php echo $shareFacebook; ?>" target="_blank" rel="noopener" class="career-share-btn facebook-btn" aria-label="Share on Facebook">
                        <i class="fab fa-facebook-f"></i>
                        <span>Facebook</span>
                    </a>
                    <a href="<?php echo $shareTwitter; ?>" target="_blank" rel="noopener" class="career-share-btn twitter-btn" aria-label="Share on X (Twitter)">
                        <i class="fab fa-twitter"></i>
                        <span>Twitter</span>
                    </a>
                    <a href="<?php echo $shareLinkedIn; ?>" target="_blank" rel="noopener" class="career-share-btn linkedin-btn" aria-label="Share on LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                        <span>LinkedIn</span>
                    </a>
                </div>
            </div>

            <!-- Related Jobs -->
            <div class="career-sidebar-card">
                <h3 class="sidebar-card-title">Other Opportunities</h3>
                <div class="related-jobs-list">
                    <?php 
                    $relatedCount = 0;
                    foreach ($recentCareers as $recentCareer): 
                        if ($recentCareer['id'] != $career['id'] && $relatedCount < 5):
                            $relatedCount++;
                    ?>
                        <a href="career.php?slug=<?php echo $recentCareer['slug']; ?>" class="related-job-item">
                            <h4 class="related-job-title"><?php echo htmlspecialchars($recentCareer['position']); ?></h4>
                            <div class="related-job-meta">
                                <span class="related-job-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($recentCareer['location']); ?>
                                </span>
                                <span class="related-job-type"><?php echo htmlspecialchars($recentCareer['employment_type']); ?></span>
                            </div>
                        </a>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
                <a href="support-services/careers.php" class="view-all-jobs-btn">
                    View All Jobs
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </aside>
    </div>

    <style>
        /* Career Page Container - UPHSL Branding */
        .career-page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 1.5rem 2rem 1.5rem;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2rem;
            font-family: 'Montserrat', sans-serif;
        }

        .career-main-content {
            min-width: 0;
        }

        /* Header Card - Indeed Style */
        .career-header-card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .career-header-content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .career-company-info {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-top: 0.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .company-logo-placeholder {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.125rem;
            flex-shrink: 0;
        }

        .company-details {
            flex: 1;
        }

        .company-name {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 0.5rem 0;
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        .career-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .career-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            font-size: 0.75rem;
            color: var(--text-dark);
            font-weight: 500;
        }

        .career-badge i {
            font-size: 0.625rem;
            color: var(--text-light);
        }

        .location-badge {
            background: rgba(28, 77, 161, 0.1);
            border-color: rgba(28, 77, 161, 0.3);
            color: var(--primary-color);
        }

        .location-badge i {
            color: var(--primary-color);
        }

        .type-badge {
            background: rgba(255, 198, 62, 0.15);
            border-color: rgba(255, 198, 62, 0.4);
            color: #b8860b;
        }

        .type-badge i {
            color: #b8860b;
        }

        .views-badge {
            background: rgba(82, 123, 189, 0.1);
            border-color: rgba(82, 123, 189, 0.3);
            color: var(--secondary-color);
        }

        .views-badge i {
            color: var(--secondary-color);
        }

        .career-position-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary-color);
            margin: 0.5rem 0;
            line-height: 1.3;
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        .career-meta-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--text-light);
            font-size: 0.875rem;
        }

        .career-posted-date {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .career-posted-date i {
            font-size: 0.75rem;
        }

        /* Content Sections - Indeed Style */
        .career-content-body {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .career-content-section {
            margin-bottom: 2.5rem;
        }

        .career-content-section:last-child {
            margin-bottom: 0;
        }

        .career-section-heading {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0 0 1rem 0;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--tertiary-color);
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        .career-section-text {
            color: var(--text-dark);
            line-height: 1.8;
            font-size: 0.9375rem;
        }

        .career-section-text p {
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .career-section-text ul,
        .career-section-text ol {
            margin: 1rem 0;
            padding-left: 1.5rem;
        }

        .career-section-text li {
            margin-bottom: 0.5rem;
            line-height: 1.7;
        }

        .career-section-text strong {
            font-weight: 600;
            color: var(--text-dark);
        }

        .career-section-text a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .career-section-text a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        /* Share Section in Sidebar */
        .career-share-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .career-share-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s ease;
            border: 1px solid #e0e0e0;
        }

        .career-share-btn i {
            font-size: 0.875rem;
        }

        .facebook-btn {
            color: #1877f2;
            border-color: #1877f2;
            background: rgba(24, 119, 242, 0.1);
        }

        .facebook-btn:hover {
            background: #1877f2;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(24, 119, 242, 0.2);
        }

        .twitter-btn {
            color: #1da1f2;
            border-color: #1da1f2;
            background: rgba(29, 161, 242, 0.1);
        }

        .twitter-btn:hover {
            background: #1da1f2;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(29, 161, 242, 0.2);
        }

        .linkedin-btn {
            color: #0a66c2;
            border-color: #0a66c2;
            background: rgba(10, 102, 194, 0.1);
        }

        .linkedin-btn:hover {
            background: #0a66c2;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(10, 102, 194, 0.2);
        }

        /* Sidebar - Indeed Style */
        .career-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .career-sidebar-card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .sidebar-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0 0 1.25rem 0;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--tertiary-color);
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        .job-details-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .job-detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
        }

        .job-detail-label {
            font-size: 0.8125rem;
            color: var(--text-light);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .job-detail-label i {
            font-size: 0.75rem;
            color: var(--primary-color);
        }

        .job-detail-value {
            font-size: 0.9375rem;
            color: var(--text-dark);
            font-weight: 600;
        }

        .related-jobs-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .related-job-item {
            display: block;
            padding: 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .related-job-item:hover {
            border-color: var(--primary-color);
            background: rgba(28, 77, 161, 0.05);
            box-shadow: 0 2px 8px rgba(28, 77, 161, 0.1);
        }

        .related-job-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 0.5rem 0;
            line-height: 1.4;
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        .related-job-item:hover .related-job-title {
            color: var(--primary-color);
        }

        .related-job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            font-size: 0.8125rem;
            color: var(--text-light);
        }

        .related-job-location {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .related-job-location i {
            font-size: 0.75rem;
        }

        .related-job-type {
            padding: 0.25rem 0.5rem;
            background: #f5f5f5;
            border-radius: 4px;
            font-size: 0.75rem;
        }

        .view-all-jobs-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.2s ease;
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        .view-all-jobs-btn:hover {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(28, 77, 161, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .career-page-container {
                grid-template-columns: 1fr;
                padding: 2.5rem 1rem 1.5rem 1rem;
            }

            .career-main-content {
                order: 1;
            }

            .career-sidebar {
                order: 2;
            }

            .career-header-card,
            .career-content-body {
                padding: 1.5rem;
            }

            .career-position-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 640px) {
            .career-page-container {
                padding: 3rem 1rem 1.5rem 1rem;
            }

            .career-company-info {
                flex-direction: column;
            }

            .company-logo-placeholder {
                width: 56px;
                height: 56px;
                font-size: 1.5rem;
            }

        }
    </style>

<?php include 'app/includes/footer.php'; ?>

