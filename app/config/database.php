<?php
/**
 * Database Configuration
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Database configuration and initialization for UPHSL website
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');

// Environment detection - check if running on production
$isProduction = (strpos($_SERVER['HTTP_HOST'], 'uphsl.edu.ph') !== false || 
                 strpos($_SERVER['HTTP_HOST'], 'www.uphsl.edu.ph') !== false ||
                 (isset($_SERVER['SERVER_NAME']) && strpos($_SERVER['SERVER_NAME'], 'uphsl.edu.ph') !== false));

if ($isProduction) {
    // Production database credentials
    define('DB_NAME', 'uphsledu_main');
    define('DB_USER', 'uphsledu_main');
    define('DB_PASS', 'uphsledu_main');
} else {
    // Local development database credentials
    define('DB_NAME', 'uphsledu_main');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}

// Create database connection
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Initialize database tables
function initializeDatabase() {
    $pdo = getDBConnection();
    
    // Create users table
    $usersTable = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            role ENUM('super_admin', 'admin', 'author', 'user') DEFAULT 'user',
            avatar VARCHAR(255) DEFAULT NULL,
            bio TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ";
    
    // Create posts table
    $postsTable = "
        CREATE TABLE IF NOT EXISTS posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            content TEXT NOT NULL,
            excerpt TEXT DEFAULT NULL,
            featured_image VARCHAR(255) DEFAULT NULL,
            status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
            author_id INT NOT NULL,
            views INT DEFAULT 0,
            published_at TIMESTAMP NULL DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ";
    
    // Create post_images table
    $postImagesTable = "
        CREATE TABLE IF NOT EXISTS post_images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            image_alt VARCHAR(255) DEFAULT NULL,
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
        )
    ";
    
    // Create categories table
    $categoriesTable = "
        CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) UNIQUE NOT NULL,
            description TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    
    // Create post_categories table (many-to-many relationship)
    $postCategoriesTable = "
        CREATE TABLE IF NOT EXISTS post_categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            category_id INT NOT NULL,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
            UNIQUE KEY unique_post_category (post_id, category_id)
        )
    ";
    
    // Create comments table
    $commentsTable = "
        CREATE TABLE IF NOT EXISTS comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            user_id INT DEFAULT NULL,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            content TEXT NOT NULL,
            status ENUM('pending', 'approved', 'spam') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        )
    ";
    
    try {
        $pdo->exec($usersTable);
        $pdo->exec($postsTable);
        $pdo->exec($postImagesTable);
        $pdo->exec($categoriesTable);
        $pdo->exec($postCategoriesTable);
        $pdo->exec($commentsTable);
        
        // Add published_at column if it doesn't exist
        $pdo->exec("ALTER TABLE posts ADD COLUMN IF NOT EXISTS published_at TIMESTAMP NULL DEFAULT NULL AFTER views");
        
        // Create default users if no users exist
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        
        if ($result['count'] == 0) {
            // Create super admin account
            $superAdminPassword = password_hash('SuperAdmin@123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password, first_name, last_name, role) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute(['web-admin', 'web-admin@uphsl.edu.ph', $superAdminPassword, 'Web', 'Administrator', 'super_admin']);
            
            // Create marketing staff as author
            $authorPassword = password_hash('Marketing@123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password, first_name, last_name, role) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute(['marketing-author', 'marketing.author@uphsl.edu.ph', $authorPassword, 'Marketing', 'Author', 'author']);
            
            // Create marketing staff as admin
            $adminPassword = password_hash('MarketingAdmin@123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password, first_name, last_name, role) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute(['marketing-admin', 'marketing.admin@uphsl.edu.ph', $adminPassword, 'Marketing', 'Admin', 'admin']);
            
            // Create some default categories
            $defaultCategories = [
                ['name' => 'News', 'slug' => 'news', 'description' => 'University news and announcements'],
                ['name' => 'Events', 'slug' => 'events', 'description' => 'University events and activities'],
                ['name' => 'Academics', 'slug' => 'academics', 'description' => 'Academic programs and updates'],
                ['name' => 'Student Life', 'slug' => 'student-life', 'description' => 'Student activities and achievements'],
                ['name' => 'Research', 'slug' => 'research', 'description' => 'Research projects and publications']
            ];
            
            foreach ($defaultCategories as $category) {
                $stmt = $pdo->prepare("
                    INSERT INTO categories (name, slug, description) 
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$category['name'], $category['slug'], $category['description']]);
            }
        }
        
        return true;
    } catch (PDOException $e) {
        die("Database initialization failed: " . $e->getMessage());
    }
}

// Initialize database on first run
initializeDatabase();
?>

