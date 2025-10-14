<?php
/**
 * Utility Functions for UPHSL Website
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Core utility functions for the UPHSL website including database operations, user management, and content handling
 */

// Utility functions for the blog

// Get recent posts
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
function slugExists($slug, $excludeId = null) {
    $pdo = getDBConnection();
    $sql = "SELECT id FROM posts WHERE slug = ?";
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
function generateUniqueSlug($title, $excludeId = null) {
    $baseSlug = createSlug($title);
    $slug = $baseSlug;
    $counter = 1;
    
    while (slugExists($slug, $excludeId)) {
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
                $source = imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                $source = imagecreatefrompng($filePath);
                break;
            case 'image/gif':
                $source = imagecreatefromgif($filePath);
                break;
            case 'image/webp':
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
                // Create non-progressive JPEG for smoother loading
                imagejpeg($resized, $filePath, $quality);
                break;
            case 'image/png':
                imagepng($resized, $filePath, 9); // PNG compression level 0-9
                break;
            case 'image/gif':
                imagegif($resized, $filePath);
                break;
            case 'image/webp':
                imagewebp($resized, $filePath, $quality);
                break;
        }
        
        // Clean up memory
        imagedestroy($source);
        imagedestroy($resized);
        
        return true;
    } catch (Exception $e) {
        error_log("Image optimization failed: " . $e->getMessage());
        return false;
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

// Get post categories
function getPostCategories($postId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT c.* FROM categories c
        JOIN post_categories pc ON c.id = pc.category_id
        WHERE pc.post_id = ?
    ");
    $stmt->execute([$postId]);
    return $stmt->fetchAll();
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

// Increment post views
function incrementPostViews($postId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
    $stmt->execute([$postId]);
}
?>

