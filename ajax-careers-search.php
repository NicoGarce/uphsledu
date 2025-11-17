<?php
/**
 * AJAX Search Endpoint for Careers
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description AJAX endpoint for real-time career search and filtering
 */

session_start();

// Set content type to JSON
header('Content-Type: application/json');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

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
    $location = $_GET['location'] ?? '';
    $employmentType = $_GET['employment_type'] ?? '';
    $dateRange = $_GET['date_range'] ?? '';
    $specificDate = $_GET['specific_date'] ?? '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max(1, $page);
    
    // Use the filtering function
    $careers = getPublishedCareerPostingsWithFilters($page, 12, $search, $location, $employmentType, $dateRange, $specificDate);
    $totalCareers = getPublishedCareerPostingsCountWithFilters($search, $location, $employmentType, $dateRange, $specificDate);
    $totalPages = ceil($totalCareers / 12);
    
    // Generate HTML for careers
    $html = '';
    
    if (!empty($careers)) {
        $html .= '<div class="careers-grid">';
        
        foreach ($careers as $posting) {
            $html .= '<div class="career-card">';
            $html .= '<div class="career-header">';
            $html .= '<h3 class="career-title">';
            $html .= '<a href="../career.php?slug=' . htmlspecialchars($posting['slug']) . '">';
            $html .= htmlspecialchars($posting['position']) . '</a>';
            $html .= '</h3>';
            $html .= '<div class="career-meta">';
            $html .= '<span class="career-location">';
            $html .= '<i class="fas fa-map-marker-alt"></i>';
            $html .= htmlspecialchars($posting['location']);
            $html .= '</span>';
            $html .= '<span class="career-type">';
            $html .= '<i class="fas fa-clock"></i>';
            $html .= htmlspecialchars($posting['employment_type']);
            $html .= '</span>';
            $html .= '</div>';
            $html .= '</div>';
            
            $html .= '<div class="career-footer">';
            $html .= '<span class="career-date">';
            $html .= '<i class="fas fa-calendar"></i>';
            $html .= formatDate($posting['published_at'] ?: $posting['created_at']);
            $html .= '</span>';
            $html .= '<a href="../career.php?slug=' . htmlspecialchars($posting['slug']) . '" class="btn btn-primary career-btn">';
            $html .= 'View <i class="fas fa-arrow-right"></i>';
            $html .= '</a>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        // Add pagination if needed
        if ($totalPages > 1) {
            $html .= '<div class="careers-pagination">';
            
            // Previous button
            if ($page > 1) {
                $html .= '<a href="#" class="pagination-btn pagination-prev" data-page="' . ($page - 1) . '">';
                $html .= '<i class="fas fa-chevron-left"></i> Previous';
                $html .= '</a>';
            } else {
                $html .= '<span class="pagination-btn pagination-prev disabled">';
                $html .= '<i class="fas fa-chevron-left"></i> Previous';
                $html .= '</span>';
            }
            
            // Page numbers
            $html .= '<div class="pagination-numbers">';
            
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);
            
            if ($startPage > 1) {
                $html .= '<a href="#" class="pagination-number" data-page="1">1</a>';
                if ($startPage > 2) {
                    $html .= '<span class="pagination-dots">...</span>';
                }
            }
            
            for ($i = $startPage; $i <= $endPage; $i++) {
                $activeClass = ($i === $page) ? 'active' : '';
                $html .= '<a href="#" class="pagination-number ' . $activeClass . '" data-page="' . $i . '">' . $i . '</a>';
            }
            
            if ($endPage < $totalPages) {
                if ($endPage < $totalPages - 1) {
                    $html .= '<span class="pagination-dots">...</span>';
                }
                $html .= '<a href="#" class="pagination-number" data-page="' . $totalPages . '">' . $totalPages . '</a>';
            }
            
            $html .= '</div>';
            
            // Next button
            if ($page < $totalPages) {
                $html .= '<a href="#" class="pagination-btn pagination-next" data-page="' . ($page + 1) . '">';
                $html .= 'Next <i class="fas fa-chevron-right"></i>';
                $html .= '</a>';
            } else {
                $html .= '<span class="pagination-btn pagination-next disabled">';
                $html .= 'Next <i class="fas fa-chevron-right"></i>';
                $html .= '</span>';
            }
            
            $html .= '</div>';
        }
        
    } else {
        $html .= '<div class="empty-careers" style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">';
        $html .= '<i class="fas fa-briefcase" style="font-size: 4rem; color: #ddd; margin-bottom: 1.5rem;"></i>';
        $html .= '<h3 style="color: #666; margin-bottom: 1rem;">No Job Postings Found</h3>';
        $html .= '<p style="color: #999;">No careers match your search criteria. Try adjusting your search terms or filters.</p>';
        $html .= '</div>';
    }
    
    // Return JSON response
    echo json_encode([
        'success' => true,
        'html' => $html,
        'totalCareers' => $totalCareers,
        'currentPage' => $page,
        'totalPages' => $totalPages
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred while searching careers: ' . $e->getMessage()
    ]);
}
?>

