<?php
/**
 * UPHSL Search Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Search functionality for posts and content on the UPHSL website
 */

session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

/**
 * Dynamically discover all PHP pages on the website
 * @return array Array of page information for search indexing
 */
function getDynamicPages() {
    $pages = [];
    $base_dir = __DIR__;
    
    // Define directories to scan for pages
    $scan_dirs = [
        '' => '', // Root directory
        'programs' => 'programs',
        'about' => 'about',
        'support-services' => 'support-services'
        // Admin folder excluded - not for public use
    ];
    
    // Define excluded files and directories
    $excluded_files = [
        'search.php', '404.php', 'test-rewrite.php',
        'privacy-policy.php', 'terms-of-service.php', 'accessibility.php'
    ];
    
    $excluded_dirs = [
        'app', 'assets', 'uploads', 'auth'
    ];
    
    foreach ($scan_dirs as $dir => $url_prefix) {
        $full_path = $base_dir . ($dir ? '/' . $dir : '');
        
        if (!is_dir($full_path)) continue;
        
        $files = scandir($full_path);
        
        foreach ($files as $file) {
            // Skip hidden files, directories, and excluded files
            if ($file[0] === '.' || is_dir($full_path . '/' . $file) || 
                !preg_match('/\.php$/', $file) || in_array($file, $excluded_files)) {
                continue;
            }
            
            $file_path = $full_path . '/' . $file;
            $page_info = extractPageInfo($file_path, $file, $url_prefix);
            
            if ($page_info) {
                $pages[] = $page_info;
            }
        }
    }
    
    return $pages;
}

/**
 * Extract page information from a PHP file
 * @param string $file_path Full path to the PHP file
 * @param string $filename Just the filename
 * @param string $url_prefix URL prefix for the page
 * @return array|false Page information array or false if extraction fails
 */
function extractPageInfo($file_path, $filename, $url_prefix) {
    $content = file_get_contents($file_path);
    
    if (!$content) return false;
    
    // Extract title from various sources
    $title = '';
    $description = '';
    
    // Try to get title from page_title variable
    if (preg_match('/\$page_title\s*=\s*["\']([^"\']+)["\']/', $content, $matches)) {
        $title = $matches[1];
    }
    // Try to get title from <title> tag
    elseif (preg_match('/<title[^>]*>([^<]+)<\/title>/i', $content, $matches)) {
        $title = trim(strip_tags($matches[1]));
    }
    // Try to get title from h1 tag
    elseif (preg_match('/<h1[^>]*>([^<]+)<\/h1>/i', $content, $matches)) {
        $title = trim(strip_tags($matches[1]));
    }
    // Fallback to filename
    else {
        $title = ucwords(str_replace(['-', '_'], ' ', pathinfo($filename, PATHINFO_FILENAME)));
    }
    
    // Try to get description from meta description
    if (preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\']([^"\']+)["\']/', $content, $matches)) {
        $description = $matches[1];
    }
    // Try to get description from page description variable
    elseif (preg_match('/\$page_description\s*=\s*["\']([^"\']+)["\']/', $content, $matches)) {
        $description = $matches[1];
    }
    // Fallback description
    else {
        $description = "Page: " . $title;
    }
    
    // Generate clean URL
    $clean_url = generateCleanUrl($filename, $url_prefix);
    
    // Calculate relevance based on content
    $content_text = strip_tags($content);
    $content_text = preg_replace('/\s+/', ' ', $content_text);
    
    return [
        'type' => 'page',
        'title' => $title,
        'description' => $description,
        'url' => $clean_url,
        'relevance' => 999, // Will be calculated during search
        'content' => $content_text
    ];
}

/**
 * Generate clean URL for a page
 * @param string $filename PHP filename
 * @param string $url_prefix URL prefix
 * @return string Clean URL
 */
function generateCleanUrl($filename, $url_prefix) {
    $name = pathinfo($filename, PATHINFO_FILENAME);
    
    // Special cases for root files
    if ($name === 'index') {
        return '';
    }
    
    // Build clean URL with .php extension
    $clean_url = $url_prefix ? $url_prefix . '/' . $name . '.php' : $name . '.php';
    
    return $clean_url;
}

/**
 * Calculate relevance score for a page based on search query
 * @param array $page Page information array
 * @param string $query Search query
 * @return int Relevance score (lower is more relevant)
 */
function calculateRelevance($page, $query) {
    $score = 999;
    $query_lower = strtolower($query);
    $title_lower = strtolower($page['title']);
    $description_lower = strtolower($page['description']);
    $content_lower = strtolower($page['content']);
    
    // Exact title match
    if ($title_lower === $query_lower) {
        $score = 1;
    }
    // Title starts with query
    elseif (strpos($title_lower, $query_lower) === 0) {
        $score = 2;
    }
    // Title contains query
    elseif (strpos($title_lower, $query_lower) !== false) {
        $score = 3;
    }
    // Description contains query
    elseif (strpos($description_lower, $query_lower) !== false) {
        $score = 4;
    }
    // Content contains query
    elseif (strpos($content_lower, $query_lower) !== false) {
        $score = 5;
    }
    
    return $score;
}

// Get search query
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];
$total_results = 0;

if (!empty($query)) {
    $pdo = getDBConnection();
    
    // Search in posts/articles
    $posts_query = "SELECT 'post' as type, id, title, content, slug, created_at, featured_image 
                    FROM posts 
                    WHERE (title LIKE :query OR content LIKE :query) 
                    AND status = 'published'
                    ORDER BY 
                        CASE 
                            WHEN title LIKE :exact_query THEN 1
                            WHEN title LIKE :start_query THEN 2
                            WHEN title LIKE :contains_query THEN 3
                            WHEN content LIKE :exact_query THEN 4
                            WHEN content LIKE :start_query THEN 5
                            WHEN content LIKE :contains_query THEN 6
                            ELSE 7
                        END,
                        created_at DESC";
    
    $stmt = $pdo->prepare($posts_query);
    $search_term = '%' . $query . '%';
    $exact_search = $query;
    $start_search = $query . '%';
    $contains_search = '%' . $query . '%';
    
    $stmt->bindParam(':query', $search_term);
    $stmt->bindParam(':exact_query', $exact_search);
    $stmt->bindParam(':start_query', $start_search);
    $stmt->bindParam(':contains_query', $contains_search);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Dynamic page discovery - scan for all PHP pages
    $pages = getDynamicPages();
    
    // Filter pages based on search query with improved matching
    $filtered_pages = array_filter($pages, function($page) use ($query) {
        $search_text = $page['title'] . ' ' . $page['description'] . ' ' . $page['content'];
        return stripos($search_text, $query) !== false;
    });
    
    // Calculate relevance scores for filtered pages
    foreach ($filtered_pages as &$page) {
        $page['relevance'] = calculateRelevance($page, $query);
    }
    
    // Sort pages by relevance
    usort($filtered_pages, function($a, $b) {
        return $a['relevance'] - $b['relevance'];
    });
    
    // Combine results
    $results = array_merge($posts, $filtered_pages);
    $total_results = count($results);
}

// Set page title
$page_title = "Search Results";

// Set base path for assets (search.php is in root, so no base path needed)
$base_path = '';

// Include header
include 'app/includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="search-results">
            <div class="search-header">
                <h1>Search Results</h1>
                <?php if (!empty($query)): ?>
                    <p class="search-query">Search results for: "<strong><?php echo htmlspecialchars($query); ?></strong>"</p>
                    <p class="search-count"><?php echo $total_results; ?> result<?php echo $total_results !== 1 ? 's' : ''; ?> found</p>
                <?php endif; ?>
            </div>
            
            <?php if (empty($query)): ?>
                <div class="no-results">
                    <h2>Enter a search term</h2>
                    <p>Please enter a search term to find pages and articles.</p>
                </div>
            <?php elseif (empty($results)): ?>
                <div class="no-results">
                    <h2>No results found</h2>
                    <p>Sorry, no results were found for "<strong><?php echo htmlspecialchars($query); ?></strong>".</p>
                    <p>Try different keywords or check your spelling.</p>
                </div>
            <?php else: ?>
                <div class="results-list">
                    <?php foreach ($results as $result): ?>
                        <?php 
                        // Ensure URLs are root-relative using the configured base path
                        $bp = isset($GLOBALS['base_path']) ? $GLOBALS['base_path'] : '/';

                        if ($result['type'] === 'post') {
                            $result_url = $bp . 'post.php?slug=' . $result['slug'];
                        } else {
                            // Handle empty URL for home page - use index.php for root
                            $result_url = $result['url'] === '' ? $bp . 'index.php' : $bp . ltrim($result['url'], '/');
                        }
                        ?>
                        <a href="<?php echo htmlspecialchars($result_url); ?>" class="result-item-link">
                            <div class="result-item">
                                <?php if ($result['type'] === 'post'): ?>
                                    <div class="result-type post-type">
                                        <i class="fas fa-newspaper"></i>
                                        Article
                                    </div>
                                    <h3 class="result-title">
                                        <?php echo htmlspecialchars($result['title']); ?>
                                    </h3>
                                    <p class="result-excerpt">
                                        <?php 
                                        $excerpt = strip_tags($result['content']);
                                        $excerpt = substr($excerpt, 0, 200);
                                        echo htmlspecialchars($excerpt) . '...';
                                        ?>
                                    </p>
                                    <div class="result-meta">
                                        <span class="result-date">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo formatDate(isset($result['published_at']) && $result['published_at'] ? $result['published_at'] : $result['created_at']); ?>
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <div class="result-type page-type">
                                        <i class="fas fa-file-alt"></i>
                                        Page
                                    </div>
                                    <h3 class="result-title">
                                        <?php echo htmlspecialchars($result['title']); ?>
                                    </h3>
                                    <p class="result-excerpt">
                                        <?php echo htmlspecialchars($result['description']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
.search-results {
    padding: 2rem 0;
}

.search-header {
    margin-bottom: 2rem;
    text-align: center;
}

.search-header h1 {
    color: var(--primary-color);
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.search-query {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.search-count {
    color: #888;
    font-size: 1rem;
}

.no-results {
    text-align: center;
    padding: 3rem 0;
}

.no-results h2 {
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.no-results p {
    color: #666;
    margin-bottom: 0.5rem;
}

.results-list {
    max-width: 800px;
    margin: 0 auto;
}

.result-item-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.result-item {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.result-item-link:hover .result-item {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.result-type {
    display: inline-block;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.post-type {
    background: var(--primary-color);
    color: white;
}

.page-type {
    background: var(--secondary-color);
    color: white;
}

.result-title {
    color: var(--primary-color);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0.8rem;
}

.result-item-link:hover .result-title {
    color: var(--secondary-color);
}

.result-excerpt {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.result-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.9rem;
    color: #888;
}

.result-date i {
    margin-right: 0.3rem;
}

@media (max-width: 768px) {
    .search-header h1 {
        font-size: 2rem;
    }
    
    .result-item {
        padding: 1rem;
    }
    
    .result-title a {
        font-size: 1.1rem;
    }
}
</style>

<?php include 'app/includes/footer.php'; ?>