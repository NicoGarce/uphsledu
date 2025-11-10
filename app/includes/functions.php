<?php
/**
 * Utility Functions for UPHSL Website
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Core utility functions for the UPHSL website including database operations, user management, and content handling
 */

// Utility functions for the blog

// Get recent posts (excludes categorized posts - only shows general/university-wide posts)
function getRecentPosts($limit = 10) {
    $pdo = getDBConnection();
    // Cast limit to integer to prevent SQL injection
    $limit = (int)$limit;
    $stmt = $pdo->prepare("
        SELECT p.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM posts p 
        JOIN users u ON p.author_id = u.id 
        WHERE p.status = 'published' 
        AND p.category_id IS NULL
        ORDER BY p.published_at DESC, p.created_at DESC 
        LIMIT :limit
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get recent posts by category ID
function getRecentPostsByCategory($categoryId, $limit = 10) {
    $pdo = getDBConnection();
    // Cast limit to integer to prevent SQL injection
    $limit = (int)$limit;
    $categoryId = (int)$categoryId;
    $stmt = $pdo->prepare("
        SELECT p.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM posts p 
        JOIN users u ON p.author_id = u.id 
        WHERE p.status = 'published' AND p.category_id = ?
        ORDER BY p.published_at DESC, p.created_at DESC 
        LIMIT ?
    ");
    $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get category by name
function getCategoryByName($name) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE name = ?");
    $stmt->execute([$name]);
    return $stmt->fetch();
}

// Get category by ID
function getCategoryById($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Get all categories
function getAllCategories() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    return $stmt->fetchAll();
}

// Get recent SDG posts
function getRecentSDGPosts($limit = 10) {
    $pdo = getDBConnection();
    // Cast limit to integer to prevent SQL injection
    $limit = (int)$limit;
    $stmt = $pdo->prepare("
        SELECT p.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM sdg_initiatives_posts p 
        JOIN users u ON p.author_id = u.id 
        WHERE p.status = 'published' 
        ORDER BY p.published_at DESC, p.created_at DESC 
        LIMIT :limit
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get post by ID
function getPostById($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT p.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM posts p 
        JOIN users u ON p.author_id = u.id 
        WHERE p.id = ? AND p.status = 'published'
    ");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Get all posts for admin/author dashboard
function getAllPosts($authorId = null, $status = null) {
    $pdo = getDBConnection();
    $sql = "
        SELECT p.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM posts p 
        JOIN users u ON p.author_id = u.id 
    ";
    $params = [];
    $conditions = [];
    
    if ($authorId) {
        $conditions[] = "p.author_id = ?";
        $params[] = $authorId;
    }
    
    if ($status) {
        $conditions[] = "p.status = ?";
        $params[] = $status;
    }
    
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $sql .= " ORDER BY p.published_at DESC, p.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Create slug from title
function createSlug($title) {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// Check if slug exists
function slugExists($slug, $table = 'posts', $excludeId = null) {
    $pdo = getDBConnection();
    $sql = "SELECT id FROM $table WHERE slug = ?";
    $params = [$slug];
    
    if ($excludeId) {
        $sql .= " AND id != ?";
        $params[] = $excludeId;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch() !== false;
}

// Generate unique slug
function generateUniqueSlug($title, $table = 'posts', $excludeId = null) {
    $baseSlug = createSlug($title);
    $slug = $baseSlug;
    $counter = 1;
    
    while (slugExists($slug, $table, $excludeId)) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }
    
    return $slug;
}

// Get user by ID
function getUserById($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Get user by email
function getUserByEmail($email) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

// Get user by username
function getUserByUsername($username) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is super admin
function isSuperAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'super_admin';
}

// Check if user is admin or super admin
function isAdmin() {
    return isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'super_admin']);
}

// Check if user is author only
function isAuthor() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'author';
}

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit();
}

// Sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Format date
function formatDate($date, $format = 'M j, Y') {
    return date($format, strtotime($date));
}

// Get post excerpt
function getExcerpt($content, $length = 150) {
    $excerpt = strip_tags($content);
    if (strlen($excerpt) > $length) {
        $excerpt = substr($excerpt, 0, $length) . '...';
    }
    return $excerpt;
}

// Optimize image for better performance
function optimizeImage($filePath, $mimeType) {
    try {
        // Check if GD extension is available
        if (!extension_loaded('gd')) {
            error_log("GD extension not available - skipping image optimization");
            return true; // Return true to continue upload process
        }
        
        $maxWidth = 1920; // Max width for web display
        $maxHeight = 1080; // Max height for web display
        $quality = 85; // JPEG quality (0-100)
        
        // Get image info
        $imageInfo = getimagesize($filePath);
        if (!$imageInfo) {
            return false;
        }
        
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        
        // Calculate new dimensions maintaining aspect ratio
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
        $newWidth = (int)($originalWidth * $ratio);
        $newHeight = (int)($originalHeight * $ratio);
        
        // Only resize if image is larger than max dimensions
        if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight) {
            return true; // No need to resize
        }
        
        // Create image resource based on type
        switch ($mimeType) {
            case 'image/jpeg':
                if (!function_exists('imagecreatefromjpeg')) {
                    error_log("imagecreatefromjpeg function not available");
                    return true; // Skip optimization but continue
                }
                $source = imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                if (!function_exists('imagecreatefrompng')) {
                    error_log("imagecreatefrompng function not available");
                    return true; // Skip optimization but continue
                }
                $source = imagecreatefrompng($filePath);
                break;
            case 'image/gif':
                if (!function_exists('imagecreatefromgif')) {
                    error_log("imagecreatefromgif function not available");
                    return true; // Skip optimization but continue
                }
                $source = imagecreatefromgif($filePath);
                break;
            case 'image/webp':
                if (!function_exists('imagecreatefromwebp')) {
                    error_log("imagecreatefromwebp function not available");
                    return true; // Skip optimization but continue
                }
                $source = imagecreatefromwebp($filePath);
                break;
            default:
                return false;
        }
        
        if (!$source) {
            return false;
        }
        
        // Create new image with calculated dimensions
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG and GIF
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
            imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Resize image
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
        
        // Save optimized image
        switch ($mimeType) {
            case 'image/jpeg':
                if (function_exists('imagejpeg')) {
                    imagejpeg($resized, $filePath, $quality);
                }
                break;
            case 'image/png':
                if (function_exists('imagepng')) {
                    imagepng($resized, $filePath, 9); // PNG compression level 0-9
                }
                break;
            case 'image/gif':
                if (function_exists('imagegif')) {
                    imagegif($resized, $filePath);
                }
                break;
            case 'image/webp':
                if (function_exists('imagewebp')) {
                    imagewebp($resized, $filePath, $quality);
                }
                break;
        }
        
        // Clean up memory
        imagedestroy($source);
        imagedestroy($resized);
        
        return true;
    } catch (Exception $e) {
        error_log("Image optimization failed: " . $e->getMessage());
        return true; // Return true to continue upload process even if optimization fails
    }
}

// Upload file
function uploadFile($file, $uploadDir = 'uploads/') {
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 10 * 1024 * 1024; // 10MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File too large'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Optimize the uploaded image
        optimizeImage($filepath, $file['type']);
        return ['success' => true, 'filename' => $filename, 'filepath' => $filepath];
    } else {
        return ['success' => false, 'message' => 'Upload failed'];
    }
}

// Get categories
function getCategories() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    return $stmt->fetchAll();
}

// Get post category (posts have a single category via category_id)
function getPostCategories($postId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT c.* FROM categories c
        JOIN posts p ON c.id = p.category_id
        WHERE p.id = ?
    ");
    $stmt->execute([$postId]);
    $result = $stmt->fetch();
    return $result ? [$result] : [];
}

// Flash message functions
function setFlashMessage($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

function getFlashMessage($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

function hasFlashMessage($type) {
    return isset($_SESSION['flash'][$type]);
}

// Get post images
function getPostImages($postId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT * FROM post_images 
        WHERE post_id = ? 
        ORDER BY sort_order ASC, created_at ASC
    ");
    $stmt->execute([$postId]);
    return $stmt->fetchAll();
}

// Get SDG post images
function getSDGPostImages($postId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT * FROM sdg_initiatives_images 
        WHERE post_id = ? 
        ORDER BY sort_order ASC, created_at ASC
    ");
    $stmt->execute([$postId]);
    return $stmt->fetchAll();
}

// Get all published posts with pagination
function getPublishedPosts($page = 1, $limit = 10) {
    $pdo = getDBConnection();
    $offset = ($page - 1) * $limit;
    
    // Cast to integers to prevent SQL injection
    $limit = (int)$limit;
    $offset = (int)$offset;
    
    $stmt = $pdo->prepare("
        SELECT p.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM posts p 
        JOIN users u ON p.author_id = u.id 
        WHERE p.status = 'published' 
        ORDER BY p.published_at DESC, p.created_at DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get total count of published posts
function getPublishedPostsCount() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM posts WHERE status = 'published'");
    $result = $stmt->fetch();
    return $result['count'];
}

// Get post by slug
function getPostBySlug($slug) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT p.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM posts p 
        JOIN users u ON p.author_id = u.id 
        WHERE p.slug = ? AND p.status = 'published'
    ");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

// Get SDG post by slug
function getSDGPostBySlug($slug) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT p.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM sdg_initiatives_posts p 
        JOIN users u ON p.author_id = u.id 
        WHERE p.slug = ? AND p.status = 'published'
    ");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

// Increment post views
function incrementPostViews($postId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
    $stmt->execute([$postId]);
}

// Increment SDG post views
function incrementSDGPostViews($postId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE sdg_initiatives_posts SET views = views + 1 WHERE id = ?");
    $stmt->execute([$postId]);
}

// Get published posts with search and filters
function getPublishedPostsWithFilters($page = 1, $limit = 12, $search = '', $category = '', $dateRange = '', $specificDate = '') {
    $pdo = getDBConnection();
    $offset = ($page - 1) * $limit;
    
    // Cast to integers to prevent SQL injection
    $limit = (int)$limit;
    $offset = (int)$offset;
    
    $sql = "
        SELECT p.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM posts p 
        JOIN users u ON p.author_id = u.id 
        WHERE p.status = 'published'
    ";
    
    $params = [];
    
    // Add search condition
    if (!empty($search)) {
        $sql .= " AND (p.title LIKE ? OR p.content LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    // Add category filter (category can be ID or name for backward compatibility)
    if (!empty($category)) {
        // Check if category is numeric (ID) or string (name)
        if (is_numeric($category)) {
            $sql .= " AND p.category_id = ?";
            $params[] = (int)$category;
        } else {
            // Try to find category by name
            $catStmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
            $catStmt->execute([$category]);
            $cat = $catStmt->fetch();
            if ($cat) {
                $sql .= " AND p.category_id = ?";
                $params[] = $cat['id'];
            } else {
                // Category not found, return empty result
                $sql .= " AND 1 = 0";
            }
        }
    }
    
    // Add date range filter
    if (!empty($dateRange)) {
        $dateCondition = getDateRangeCondition($dateRange);
        if ($dateCondition) {
            $sql .= " AND " . $dateCondition;
        }
    }
    
    // Add specific date filter
    if (!empty($specificDate)) {
        $sql .= " AND DATE(p.published_at) = ?";
        $params[] = $specificDate;
    }
    
    $sql .= " ORDER BY p.published_at DESC, p.created_at DESC LIMIT ? OFFSET ?";
    
    $stmt = $pdo->prepare($sql);
    
    // Bind all parameters
    $paramIndex = 1;
    foreach ($params as $param) {
        $stmt->bindValue($paramIndex, $param);
        $paramIndex++;
    }
    
    // Bind LIMIT and OFFSET as integers
    $stmt->bindValue($paramIndex, (int)$limit, PDO::PARAM_INT);
    $paramIndex++;
    $stmt->bindValue($paramIndex, (int)$offset, PDO::PARAM_INT);
    
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get total count of published posts with filters
function getPublishedPostsCountWithFilters($search = '', $category = '', $dateRange = '', $specificDate = '') {
    $pdo = getDBConnection();
    
    $sql = "SELECT COUNT(*) as count FROM posts p WHERE p.status = 'published'";
    $params = [];
    
    // Add search condition
    if (!empty($search)) {
        $sql .= " AND (p.title LIKE ? OR p.content LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    // Add category filter (category can be ID or name for backward compatibility)
    if (!empty($category)) {
        // Check if category is numeric (ID) or string (name)
        if (is_numeric($category)) {
            $sql .= " AND p.category_id = ?";
            $params[] = (int)$category;
        } else {
            // Try to find category by name
            $catStmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
            $catStmt->execute([$category]);
            $cat = $catStmt->fetch();
            if ($cat) {
                $sql .= " AND p.category_id = ?";
                $params[] = $cat['id'];
            } else {
                // Category not found, return empty result
                $sql .= " AND 1 = 0";
            }
        }
    }
    
    // Add date range filter
    if (!empty($dateRange)) {
        $dateCondition = getDateRangeCondition($dateRange);
        if ($dateCondition) {
            $sql .= " AND " . $dateCondition;
        }
    }
    
    // Add specific date filter
    if (!empty($specificDate)) {
        $sql .= " AND DATE(p.published_at) = ?";
        $params[] = $specificDate;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result['count'];
}

// Get date range condition for SQL
function getDateRangeCondition($dateRange) {
    $today = date('Y-m-d');
    
    switch ($dateRange) {
        case 'today':
            return "DATE(p.published_at) = '$today'";
        case 'week':
            return "p.published_at >= DATE_SUB('$today', INTERVAL 1 WEEK)";
        case 'month':
            return "p.published_at >= DATE_SUB('$today', INTERVAL 1 MONTH)";
        case 'year':
            return "p.published_at >= DATE_SUB('$today', INTERVAL 1 YEAR)";
        default:
            return null;
    }
}

// Get setting value
function getSetting($key, $default = null) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['setting_value'] : $default;
}

// Set setting value
function setSetting($key, $value, $type = 'text', $description = null, $userId = null) {
    try {
        $pdo = getDBConnection();
        
        // First check if setting exists
        $checkStmt = $pdo->prepare("SELECT id FROM settings WHERE setting_key = ?");
        $checkStmt->execute([$key]);
        $exists = $checkStmt->fetch();
        
        if ($exists) {
            // Update existing setting
            $stmt = $pdo->prepare("
                UPDATE settings 
                SET setting_value = ?, 
                    setting_type = ?, 
                    description = ?, 
                    updated_by = ?, 
                    updated_at = CURRENT_TIMESTAMP
                WHERE setting_key = ?
            ");
            return $stmt->execute([$value, $type, $description, $userId, $key]);
        } else {
            // Insert new setting
            $stmt = $pdo->prepare("
                INSERT INTO settings (setting_key, setting_value, setting_type, description, updated_by)
                VALUES (?, ?, ?, ?, ?)
            ");
            return $stmt->execute([$key, $value, $type, $description, $userId]);
        }
    } catch (PDOException $e) {
        error_log("Error setting setting '{$key}': " . $e->getMessage());
        return false;
    }
}
?>

