<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

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
    
    // Search in pages (static pages)
    $pages = [
        [
            'type' => 'page',
            'title' => 'Home',
            'description' => 'University of Perpetual Help System Laguna - Homepage',
            'url' => 'index.php',
            'relevance' => (stripos('home university perpetual help system laguna', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Programs',
            'description' => 'Academic programs and courses offered by UPHSL',
            'url' => 'programs.php',
            'relevance' => (stripos('programs courses academic degrees', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Aviation',
            'description' => 'Aviation program and courses',
            'url' => 'programs/aviation.php',
            'relevance' => (stripos('aviation pilot flight', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Arts & Sciences',
            'description' => 'College of Arts and Sciences programs',
            'url' => 'programs/arts-sciences.php',
            'relevance' => (stripos('arts sciences liberal arts', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Business & Accountancy',
            'description' => 'Business and Accountancy programs',
            'url' => 'programs/business-accountancy.php',
            'relevance' => (stripos('business accountancy commerce', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Computer Studies',
            'description' => 'Computer Science and Information Technology programs',
            'url' => 'programs/computer-studies.php',
            'relevance' => (stripos('computer technology IT programming', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Criminology',
            'description' => 'Criminology and Criminal Justice programs',
            'url' => 'programs/criminology.php',
            'relevance' => (stripos('criminology criminal justice law enforcement', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Education',
            'description' => 'Education and Teaching programs',
            'url' => 'programs/education.php',
            'relevance' => (stripos('education teaching teacher', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Engineering & Architecture',
            'description' => 'Engineering and Architecture programs',
            'url' => 'programs/engineering-architecture.php',
            'relevance' => (stripos('engineering architecture civil mechanical', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Hospitality Management',
            'description' => 'International Hospitality Management program',
            'url' => 'programs/hospitality-management.php',
            'relevance' => (stripos('hospitality hotel management tourism', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Maritime',
            'description' => 'Maritime programs and courses',
            'url' => 'programs/maritime.php',
            'relevance' => (stripos('maritime shipping marine', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Law',
            'description' => 'Law and Juris Doctor programs',
            'url' => 'programs/law.php',
            'relevance' => (stripos('law legal juris doctor', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Graduate School',
            'description' => 'Graduate School programs',
            'url' => 'programs/graduate-school.php',
            'relevance' => (stripos('graduate school masters doctorate', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Senior High School',
            'description' => 'Senior High School programs',
            'url' => 'programs/senior-high-school.php',
            'relevance' => (stripos('senior high school SHS', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Junior High School',
            'description' => 'Junior High School programs',
            'url' => 'programs/junior-high-school.php',
            'relevance' => (stripos('junior high school JHS', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Grade School',
            'description' => 'Grade School programs',
            'url' => 'programs/grade-school.php',
            'relevance' => (stripos('grade school elementary', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Online Services',
            'description' => 'Online services and student portal',
            'url' => 'ols_instructions.php',
            'relevance' => (stripos('online services portal GTI moodle', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Community Outreach Department',
            'description' => 'Community Outreach Department programs and services',
            'url' => 'support-services/cod.php',
            'relevance' => (stripos('community outreach COD', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'Careers',
            'description' => 'Career opportunities and job listings',
            'url' => 'support-services/careers.php',
            'relevance' => (stripos('careers jobs employment', $query) !== false) ? 1 : 999
        ],
        [
            'type' => 'page',
            'title' => 'University Clinic',
            'description' => 'University Clinic services and information',
            'url' => 'support-services/clinic.php',
            'relevance' => (stripos('clinic health medical', $query) !== false) ? 1 : 999
        ]
    ];
    
    // Filter pages based on search query
    $filtered_pages = array_filter($pages, function($page) use ($query) {
        return stripos($page['title'] . ' ' . $page['description'], $query) !== false;
    });
    
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

// Include header
include 'includes/header.php';
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
                        $base_path = '';
                        $result_url = ($result['type'] === 'post') ? $base_path . 'post.php?slug=' . $result['slug'] : $base_path . $result['url'];
                        ?>
                        <a href="<?php echo $result_url; ?>" class="result-item-link">
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
                                            <?php echo formatDate($result['created_at']); ?>
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

<?php include 'includes/footer.php'; ?>
