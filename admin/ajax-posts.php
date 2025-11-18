<?php
/**
 * AJAX Posts Endpoint for Admin
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description AJAX endpoint for admin post search and filtering
 */

// Start output buffering to catch any accidental output
ob_start();

// Define constant to skip security headers for AJAX endpoints
define('SKIP_SECURITY_HEADERS', true);

// Set content type to JSON first (before any includes that might output)
header('Content-Type: application/json');

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
// Session is automatically initialized by security.php

// Clear any accidental output
ob_clean();

// Check if user is logged in and has appropriate permissions
if (!isLoggedIn() || (!isAuthor() && !isAdmin() && !isSuperAdmin())) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    $pdo = getDBConnection();
    
    // Get filter parameters
    $search = $_GET['search'] ?? '';
    $statusFilter = $_GET['status'] ?? '';
    $categoryFilter = $_GET['category'] ?? '';
    $dateRange = $_GET['date_range'] ?? '';
    
    // Build query with filters
    $sql = "
        SELECT p.id, p.title, p.slug, p.status, p.created_at, p.published_at, p.category_id,
               u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name,
               COALESCE(c.name, 'University News') as category_name
        FROM posts p 
        JOIN users u ON p.author_id = u.id 
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE 1=1
    ";
    
    $params = [];
    
    // Filter by author if user is an author
    if (isAuthor()) {
        $sql .= " AND p.author_id = ?";
        $params[] = $_SESSION['user_id'];
    }
    
    // Search filter
    if (!empty($search)) {
        $sql .= " AND (p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    // Status filter
    if (!empty($statusFilter) && in_array($statusFilter, ['draft', 'published', 'archived'])) {
        $sql .= " AND p.status = ?";
        $params[] = $statusFilter;
    }
    
    // Category filter
    if (!empty($categoryFilter)) {
        if ($categoryFilter === 'university-news') {
            // Filter for posts with null category (University News)
            $sql .= " AND p.category_id IS NULL";
        } elseif (is_numeric($categoryFilter)) {
            $sql .= " AND p.category_id = ?";
            $params[] = (int)$categoryFilter;
        } else {
            $cat = getCategoryByName($categoryFilter);
            if ($cat) {
                $sql .= " AND p.category_id = ?";
                $params[] = $cat['id'];
            }
        }
    }
    
    // Date range filter
    if (!empty($dateRange)) {
        $today = date('Y-m-d');
        switch ($dateRange) {
            case 'today':
                $sql .= " AND DATE(p.created_at) = ?";
                $params[] = $today;
                break;
            case 'week':
                $sql .= " AND p.created_at >= DATE_SUB(?, INTERVAL 1 WEEK)";
                $params[] = $today;
                break;
            case 'month':
                $sql .= " AND p.created_at >= DATE_SUB(?, INTERVAL 1 MONTH)";
                $params[] = $today;
                break;
            case 'year':
                $sql .= " AND p.created_at >= DATE_SUB(?, INTERVAL 1 YEAR)";
                $params[] = $today;
                break;
        }
    }
    
    $sql .= " ORDER BY p.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $posts = $stmt->fetchAll();
    
    // Remove duplicates by post ID (in case of any remaining duplicates)
    $uniquePosts = [];
    $seenIds = [];
    foreach ($posts as $post) {
        if (!in_array($post['id'], $seenIds)) {
            $uniquePosts[] = $post;
            $seenIds[] = $post['id'];
        }
    }
    
    // Format posts for JSON response
    $formattedPosts = [];
    foreach ($uniquePosts as $post) {
        $formattedPosts[] = [
            'id' => $post['id'],
            'title' => $post['title'],
            'slug' => $post['slug'],
            'status' => $post['status'],
            'category_name' => $post['category_name'],
            'created_at' => $post['created_at'],
            'published_at' => $post['published_at'],
            'author_name' => $post['author_name'],
            'created_date' => date('M j, Y', strtotime($post['created_at'])),
            'published_date' => $post['published_at'] ? date('M j, Y', strtotime($post['published_at'])) : null
        ];
    }
    
    echo json_encode([
        'success' => true,
        'posts' => $formattedPosts,
        'count' => count($formattedPosts)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred: ' . $e->getMessage()
    ]);
}
?>

