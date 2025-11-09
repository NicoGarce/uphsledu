<?php
/**
 * AJAX Search Endpoint for Posts
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description AJAX endpoint for real-time post search and filtering
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Set content type to JSON
header('Content-Type: application/json');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Try to include required files
try {
    require_once 'app/config/database.php';
    require_once 'app/includes/functions.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to load required files: ' . $e->getMessage()
    ]);
    exit;
}

try {
    // Get parameters
    $search = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? '';
    $dateRange = $_GET['date_range'] ?? '';
    $specificDate = $_GET['specific_date'] ?? '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max(1, $page);
    
    // Use the proper filtering function that handles all filters including category
    $posts = getPublishedPostsWithFilters($page, 12, $search, $category, $dateRange, $specificDate);
    $totalPosts = getPublishedPostsCountWithFilters($search, $category, $dateRange, $specificDate);
    $totalPages = ceil($totalPosts / 12);
    
    // Generate HTML for posts
    $html = '';
    
    if (!empty($posts)) {
        $html .= '<div class="posts-grid">';
        
        foreach ($posts as $post) {
            $html .= '<article class="post-card">';
            $html .= '<div class="post-card-image">';
            
            if ($post['featured_image']) {
                $html .= '<img src="' . htmlspecialchars($post['featured_image']) . '" 
                             alt="' . htmlspecialchars($post['title']) . '"
                             class="card-image"
                             decoding="async">';
            } else {
                $html .= '<div class="card-image-placeholder">
                            <i class="fas fa-newspaper"></i>
                          </div>';
            }
            
            $html .= '<div class="post-card-overlay">
                        <a href="post.php?slug=' . $post['slug'] . '" class="read-more-btn">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                      </div>';
            $html .= '</div>';
            
            $html .= '<div class="post-card-content">';
            $html .= '<h2 class="post-card-title">
                        <a href="post.php?slug=' . $post['slug'] . '">' . 
                        htmlspecialchars($post['title']) . '</a>
                      </h2>';
            
            $html .= '<div class="post-card-meta">
                        <span class="post-date">
                            <i class="fas fa-calendar"></i>
                            ' . formatDate($post['published_at'] ?: $post['created_at']) . '
                        </span>
                      </div>';
            
            $html .= '<div class="post-card-footer">
                        <div class="post-stats">
                            <span class="post-views">
                                <i class="fas fa-eye"></i>
                                ' . $post['views'] . ' views
                            </span>
                        </div>
                        <a href="post.php?slug=' . $post['slug'] . '" class="read-more-link">
                            Read More <i class="fas fa-arrow-right"></i>
                        </a>
                      </div>';
            $html .= '</div>';
            $html .= '</article>';
        }
        
        $html .= '</div>';
        
        // Add pagination if needed
        if ($totalPages > 1) {
            $html .= '<div class="pagination-container">
                        <div class="pagination">';
            
            // Previous button
            if ($page > 1) {
                $html .= '<a href="#" class="pagination-btn prev-btn" data-page="' . ($page - 1) . '">
                            <i class="fas fa-chevron-left"></i>
                            Previous
                          </a>';
            }
            
            // Page numbers
            $html .= '<div class="pagination-numbers">';
            
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);
            
            if ($startPage > 1) {
                $html .= '<a href="#" class="pagination-number" data-page="1">1</a>';
                if ($startPage > 2) {
                    $html .= '<span class="pagination-ellipsis">...</span>';
                }
            }
            
            for ($i = $startPage; $i <= $endPage; $i++) {
                $activeClass = ($i === $page) ? 'active' : '';
                $html .= '<a href="#" class="pagination-number ' . $activeClass . '" data-page="' . $i . '">' . $i . '</a>';
            }
            
            if ($endPage < $totalPages) {
                if ($endPage < $totalPages - 1) {
                    $html .= '<span class="pagination-ellipsis">...</span>';
                }
                $html .= '<a href="#" class="pagination-number" data-page="' . $totalPages . '">' . $totalPages . '</a>';
            }
            
            $html .= '</div>';
            
            // Next button
            if ($page < $totalPages) {
                $html .= '<a href="#" class="pagination-btn next-btn" data-page="' . ($page + 1) . '">
                            Next
                            <i class="fas fa-chevron-right"></i>
                          </a>';
            }
            
            $html .= '</div>
                      <div class="pagination-info">
                        <p>Showing ' . (($page - 1) * 12) + 1 . '-' . min($page * 12, $totalPosts) . ' of ' . $totalPosts . ' posts</p>
                      </div>
                    </div>';
        }
        
    } else {
        $html .= '<div class="empty-posts">
                    <div class="empty-posts-content">
                        <i class="fas fa-search"></i>
                        <h3>No Results Found</h3>
                        <p>No posts match your search criteria. Try adjusting your search terms or filters.</p>
                        <a href="posts.php" class="btn btn-primary">View All Posts</a>
                    </div>
                  </div>';
    }
    
    // Return JSON response
    echo json_encode([
        'success' => true,
        'html' => $html,
        'totalPosts' => $totalPosts,
        'currentPage' => $page,
        'totalPages' => $totalPages
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred while searching posts: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]
    ]);
}
?>
