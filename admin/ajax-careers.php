<?php
/**
 * AJAX Careers Endpoint for Admin
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description AJAX endpoint for admin career search and filtering
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if user is logged in and has appropriate permissions
if (!isLoggedIn() || (!isHR() && !isAdmin() && !isSuperAdmin())) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Set content type to JSON
header('Content-Type: application/json');

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
    $dateRange = $_GET['date_range'] ?? '';
    
    // Build query with filters
    $sql = "
        SELECT cp.id, cp.position, cp.slug, cp.location, cp.employment_type, cp.status, cp.created_at, cp.published_at,
               u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM careers_postings cp 
        JOIN users u ON cp.author_id = u.id 
        WHERE 1=1
    ";
    
    $params = [];
    
    // HR accounts can see all career postings (no filter needed)
    // Only filter if needed for other roles in the future
    
    // Search filter
    if (!empty($search)) {
        $sql .= " AND (cp.position LIKE ? OR cp.location LIKE ? OR cp.job_description LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    // Status filter
    if (!empty($statusFilter) && in_array($statusFilter, ['draft', 'published', 'archived'])) {
        $sql .= " AND cp.status = ?";
        $params[] = $statusFilter;
    }
    
    // Date range filter
    if (!empty($dateRange)) {
        $today = date('Y-m-d');
        switch ($dateRange) {
            case 'today':
                $sql .= " AND DATE(cp.created_at) = ?";
                $params[] = $today;
                break;
            case 'week':
                $sql .= " AND cp.created_at >= DATE_SUB(?, INTERVAL 1 WEEK)";
                $params[] = $today;
                break;
            case 'month':
                $sql .= " AND cp.created_at >= DATE_SUB(?, INTERVAL 1 MONTH)";
                $params[] = $today;
                break;
            case 'year':
                $sql .= " AND cp.created_at >= DATE_SUB(?, INTERVAL 1 YEAR)";
                $params[] = $today;
                break;
        }
    }
    
    $sql .= " ORDER BY cp.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $careers = $stmt->fetchAll();
    
    // Format careers for JSON response
    $formattedCareers = [];
    foreach ($careers as $career) {
        $formattedCareers[] = [
            'id' => $career['id'],
            'position' => $career['position'],
            'slug' => $career['slug'],
            'location' => $career['location'],
            'employment_type' => $career['employment_type'],
            'status' => $career['status'],
            'created_at' => $career['created_at'],
            'published_at' => $career['published_at'],
            'author_name' => $career['author_name'],
            'created_date' => date('M j, Y', strtotime($career['created_at'])),
            'published_date' => $career['published_at'] ? date('M j, Y', strtotime($career['published_at'])) : null
        ];
    }
    
    echo json_encode([
        'success' => true,
        'careers' => $formattedCareers,
        'count' => count($formattedCareers)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred: ' . $e->getMessage()
    ]);
}
?>

