<?php
/**
 * Utility Functions for UPHSL Website
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Core utility functions for the UPHSL website including database operations, user management, and content handling
 */

// Include security features
require_once __DIR__ . '/security.php';

// Utility functions for the blog

// Get hero post (selected by super admin) or default to latest post
function getHeroPost() {
    $pdo = getDBConnection();
    $heroPostId = getSetting('hero_post_id', '');
    
    // If a hero post is selected, try to get it
    if (!empty($heroPostId) && is_numeric($heroPostId) && (int)$heroPostId > 0) {
        $stmt = $pdo->prepare("
            SELECT p.*, u.first_name, u.last_name, 
                   CONCAT(u.first_name, ' ', u.last_name) as author_name
            FROM posts p 
            JOIN users u ON p.author_id = u.id 
            WHERE p.id = ? AND p.status = 'published'
        ");
        $stmt->execute([(int)$heroPostId]);
        $heroPost = $stmt->fetch();
        
        // If hero post exists and is published, return it (even if it has a category)
        if ($heroPost) {
            return $heroPost;
        }
    }
    
    // Otherwise, return the latest post (default behavior) - only university news (category_id IS NULL)
    $stmt = $pdo->prepare("
        SELECT p.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM posts p 
        JOIN users u ON p.author_id = u.id 
        WHERE p.status = 'published' 
        AND p.category_id IS NULL
        ORDER BY p.published_at DESC, p.created_at DESC 
        LIMIT 1
    ");
    $stmt->execute();
    return $stmt->fetch();
}

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

// Verify user password for bulk actions
function verifyUserPassword($userId, $password) {
    $user = getUserById($userId);
    if (!$user) {
        return false;
    }
    return password_verify($password, $user['password']);
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

// Check if user is HR
function isHR() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'hr';
}

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit();
}

// Sanitize input (uses new Validator class)
function sanitizeInput($input, $type = 'string') {
    return Validator::sanitize($input, $type);
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
    // Validate upload directory path to prevent directory traversal
    $uploadDir = rtrim($uploadDir, '/') . '/';
    $uploadDir = realpath(dirname(__DIR__) . '/' . $uploadDir) . '/';
    
    if (!file_exists($uploadDir)) {
        // Use secure directory permissions (0755 instead of 0777)
        if (!mkdir($uploadDir, 0755, true)) {
            return ['success' => false, 'message' => 'Failed to create upload directory'];
        }
    }
    
    // Validate file upload
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ['success' => false, 'message' => 'Invalid file upload'];
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $maxSize = 10 * 1024 * 1024; // 10MB
    
    // Validate file size
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File too large'];
    }
    
    // Validate file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions)) {
        return ['success' => false, 'message' => 'Invalid file extension'];
    }
    
    // Verify actual file content using getimagesize (more secure than trusting MIME type)
    $imageInfo = @getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        return ['success' => false, 'message' => 'File is not a valid image'];
    }
    
    // Verify MIME type matches actual file content
    $detectedMime = $imageInfo['mime'];
    if (!in_array($detectedMime, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type detected'];
    }
    
    // Generate secure filename
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Ensure filename doesn't contain path traversal
    $filepath = realpath($uploadDir) . '/' . basename($filename);
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Optimize the uploaded image
        optimizeImage($filepath, $detectedMime);
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

// Increment career posting views
function incrementCareerPostingViews($careerId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE careers_postings SET views = views + 1 WHERE id = ?");
    $stmt->execute([$careerId]);
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
function getDateRangeCondition($dateRange, $dateColumn = 'p.published_at') {
    $today = date('Y-m-d');
    
    switch ($dateRange) {
        case 'today':
            return "DATE($dateColumn) = '$today'";
        case 'week':
            return "$dateColumn >= DATE_SUB('$today', INTERVAL 1 WEEK)";
        case 'month':
            return "$dateColumn >= DATE_SUB('$today', INTERVAL 1 MONTH)";
        case 'year':
            return "$dateColumn >= DATE_SUB('$today', INTERVAL 1 YEAR)";
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
    // Return the actual value even if it's an empty string, only use default if setting doesn't exist
    if ($result !== false && isset($result['setting_value'])) {
        return $result['setting_value'];
    }
    return $default;
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

// Check if a section is in maintenance mode (checks both main section and sub-page)
function isSectionInMaintenance($sectionKey, $subKey = null) {
    // Check main section first - if main section is enabled, all sub-pages are in maintenance
    $maintenance_enabled = getSetting("section_maintenance_{$sectionKey}", '0');
    if ($maintenance_enabled === '1') {
        return true;
    }
    
    // If main section is not enabled, check sub-page if provided
    if ($subKey !== null) {
        $subMaintenance = getSetting("section_maintenance_{$sectionKey}_{$subKey}", '0');
        if ($subMaintenance === '1') {
            return true;
        }
    }
    
    return false;
}

// Get section maintenance message (checks both main section and sub-page)
function getSectionMaintenanceMessage($sectionKey, $defaultMessage = null, $subKey = null) {
    // Check sub-page first if provided
    if ($subKey !== null) {
        $subMessage = getSetting("section_maintenance_message_{$sectionKey}_{$subKey}", null);
        if ($subMessage !== null) {
            return $subMessage;
        }
    }
    
    $sections = [
        'home' => 'Home',
        'programs' => 'Programs',
        'online-services' => 'Online Services',
        'support-services' => 'Support Services',
        'campuses' => 'Campuses',
        'about' => 'About',
        'online-payment' => 'Online Payment',
        'calendar' => 'Calendar',
        'enrollment' => 'Enrollment',
        'sdg-initiatives' => 'SDG Initiatives',
        'posts' => 'Posts',
        'post' => 'Post'
    ];
    
    $sectionName = $sections[$sectionKey] ?? 'This section';
    $default = $defaultMessage ?? "The {$sectionName} section is currently under maintenance. Please check back soon.";
    
    return getSetting("section_maintenance_message_{$sectionKey}", $default);
}

// Check if navbar item is visible
function isNavbarItemVisible($itemKey, $subItemKey = null) {
    // Check main item first - if main item is disabled, all sub-items are hidden
    $itemVisible = getSetting("navbar_item_{$itemKey}", '1');
    if ($itemVisible !== '1') {
        return false; // Main item is disabled
    }
    
    // If sub-item is specified, check sub-item visibility
    if ($subItemKey !== null) {
        $subItemVisible = getSetting("navbar_item_{$itemKey}_{$subItemKey}", '1');
        return $subItemVisible === '1';
    }
    
    // Main item is visible
    return true;
}

// Check if all sub-items for a navbar item are disabled
function areAllNavbarSubItemsDisabled($itemKey, $subItems) {
    // If main item is disabled, return false (main item won't show anyway)
    $itemVisible = getSetting("navbar_item_{$itemKey}", '1');
    if ($itemVisible !== '1') {
        return false;
    }
    
    // If no sub-items, return false
    if (empty($subItems)) {
        return false;
    }
    
    // Check if all sub-items are disabled
    $allDisabled = true;
    foreach ($subItems as $subKey => $subName) {
        $subItemVisible = getSetting("navbar_item_{$itemKey}_{$subKey}", '1');
        if ($subItemVisible === '1') {
            $allDisabled = false;
            break;
        }
    }
    
    return $allDisabled;
}

// Display section maintenance message
function displaySectionMaintenance($sectionKey, $base_path = '', $subKey = null) {
    if (!isSectionInMaintenance($sectionKey, $subKey)) {
        return false;
    }
    
    $maintenance_message = getSectionMaintenanceMessage($sectionKey, null, $subKey);
    ?>
    <main class="main-content" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 4rem 2rem;">
        <div class="maintenance-message" style="text-align: center; max-width: 600px; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <i class="fas fa-tools" style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1.5rem;"></i>
            <h1 style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;">Under Maintenance</h1>
            <p style="font-size: 1.1rem; color: #666; line-height: 1.6; margin-bottom: 2rem;"><?php echo htmlspecialchars($maintenance_message); ?></p>
            <a href="<?php echo $base_path; ?>index.php" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: var(--primary-color); color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">
                <i class="fas fa-home"></i>
                Go to Homepage
            </a>
        </div>
    </main>
    <?php
    return true;
}

// Get all career postings
function getAllCareerPostings($authorId = null, $status = null) {
    $pdo = getDBConnection();
    $sql = "
        SELECT cp.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM careers_postings cp 
        JOIN users u ON cp.author_id = u.id 
    ";
    $params = [];
    $conditions = [];
    
    if ($authorId) {
        $conditions[] = "cp.author_id = ?";
        $params[] = $authorId;
    }
    
    if ($status) {
        $conditions[] = "cp.status = ?";
        $params[] = $status;
    }
    
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $sql .= " ORDER BY cp.published_at DESC, cp.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Get published career postings
function getPublishedCareerPostings($limit = 10) {
    $pdo = getDBConnection();
    $limit = (int)$limit;
    $stmt = $pdo->prepare("
        SELECT cp.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM careers_postings cp 
        JOIN users u ON cp.author_id = u.id 
        WHERE cp.status = 'published' 
        ORDER BY cp.published_at DESC, cp.created_at DESC 
        LIMIT :limit
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get published career postings with pagination
function getPublishedCareerPostingsPaginated($page = 1, $limit = 12) {
    $pdo = getDBConnection();
    $offset = ($page - 1) * $limit;
    
    // Cast to integers to prevent SQL injection
    $limit = (int)$limit;
    $offset = (int)$offset;
    
    $stmt = $pdo->prepare("
        SELECT cp.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM careers_postings cp 
        JOIN users u ON cp.author_id = u.id 
        WHERE cp.status = 'published' 
        ORDER BY cp.published_at DESC, cp.created_at DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get total count of published career postings
function getPublishedCareerPostingsCount() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM careers_postings WHERE status = 'published'");
    $result = $stmt->fetch();
    return $result['count'];
}

// Get published career postings with filters
function getPublishedCareerPostingsWithFilters($page = 1, $limit = 12, $search = '', $location = '', $employmentType = '', $dateRange = '', $specificDate = '') {
    $pdo = getDBConnection();
    $offset = ($page - 1) * $limit;
    
    // Cast to integers to prevent SQL injection
    $limit = (int)$limit;
    $offset = (int)$offset;
    
    $sql = "
        SELECT cp.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM careers_postings cp 
        JOIN users u ON cp.author_id = u.id 
        WHERE cp.status = 'published'
    ";
    
    $params = [];
    
    // Add search condition
    if (!empty($search)) {
        $sql .= " AND (cp.position LIKE ? OR cp.job_description LIKE ? OR cp.location LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    // Add location filter
    if (!empty($location)) {
        $sql .= " AND cp.location LIKE ?";
        $params[] = "%{$location}%";
    }
    
    // Add employment type filter
    if (!empty($employmentType)) {
        $sql .= " AND cp.employment_type = ?";
        $params[] = $employmentType;
    }
    
    // Add date range filter
    if (!empty($dateRange)) {
        $dateCondition = getDateRangeCondition($dateRange, 'cp.published_at');
        if ($dateCondition) {
            $sql .= " AND " . $dateCondition;
        }
    }
    
    // Add specific date filter
    if (!empty($specificDate)) {
        $sql .= " AND DATE(cp.published_at) = ?";
        $params[] = $specificDate;
    }
    
    $sql .= " ORDER BY cp.published_at DESC, cp.created_at DESC LIMIT ? OFFSET ?";
    
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

// Get total count of published career postings with filters
function getPublishedCareerPostingsCountWithFilters($search = '', $location = '', $employmentType = '', $dateRange = '', $specificDate = '') {
    $pdo = getDBConnection();
    
    $sql = "SELECT COUNT(*) as count FROM careers_postings cp WHERE cp.status = 'published'";
    $params = [];
    
    // Add search condition
    if (!empty($search)) {
        $sql .= " AND (cp.position LIKE ? OR cp.job_description LIKE ? OR cp.location LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    // Add location filter
    if (!empty($location)) {
        $sql .= " AND cp.location LIKE ?";
        $params[] = "%{$location}%";
    }
    
    // Add employment type filter
    if (!empty($employmentType)) {
        $sql .= " AND cp.employment_type = ?";
        $params[] = $employmentType;
    }
    
    // Add date range filter
    if (!empty($dateRange)) {
        $dateCondition = getDateRangeCondition($dateRange, 'cp.published_at');
        if ($dateCondition) {
            $sql .= " AND " . $dateCondition;
        }
    }
    
    // Add specific date filter
    if (!empty($specificDate)) {
        $sql .= " AND DATE(cp.published_at) = ?";
        $params[] = $specificDate;
    }
    
    $stmt = $pdo->prepare($sql);
    if (!empty($params)) {
        $stmt->execute($params);
    } else {
        $stmt->execute();
    }
    $result = $stmt->fetch();
    return $result['count'];
}

// Get all unique locations from published career postings
function getCareerLocations() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT DISTINCT location FROM careers_postings WHERE status = 'published' ORDER BY location ASC");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Get all unique employment types from published career postings
function getCareerEmploymentTypes() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT DISTINCT employment_type FROM careers_postings WHERE status = 'published' ORDER BY employment_type ASC");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Get career posting by ID
function getCareerPostingById($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT cp.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM careers_postings cp 
        JOIN users u ON cp.author_id = u.id 
        WHERE cp.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Get career posting by slug
function getCareerPostingBySlug($slug) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT cp.*, u.first_name, u.last_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM careers_postings cp 
        JOIN users u ON cp.author_id = u.id 
        WHERE cp.slug = ? AND cp.status = 'published'
    ");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

// Check if slug exists for careers postings
function careerSlugExists($slug, $excludeId = null) {
    return slugExists($slug, 'careers_postings', $excludeId);
}

// Generate unique slug for career posting

function generateUniqueCareerSlug($position, $excludeId = null) {
    return generateUniqueSlug($position, 'careers_postings', $excludeId);
}


