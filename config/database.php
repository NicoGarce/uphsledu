<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'uphsledu');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

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
        
        // Create default admin user if no users exist
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        
        if ($result['count'] == 0) {
            $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password, first_name, last_name, role) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute(['admin', 'admin@uphs.edu.ph', $adminPassword, 'Admin', 'User', 'super_admin']);
        }
        
        return true;
    } catch (PDOException $e) {
        die("Database initialization failed: " . $e->getMessage());
    }
}

// Initialize database on first run
initializeDatabase();
?>

