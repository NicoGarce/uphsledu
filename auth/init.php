<?php
/**
 * UPHSL Website Initialization Script
 * Run this script once to set up the database and create default user accounts
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Initializes the database and creates default user accounts for first-time setup
 */

require_once '../app/config/database.php';

echo "<h1>UPHSL Website Initialization</h1>";
echo "<p>Setting up database and creating default user accounts...</p>";

try {
    // Initialize database (this will create tables and default users)
    $result = initializeDatabase();
    
    if ($result) {
        echo "<h2 style='color: green;'>✓ Database initialization successful!</h2>";
        
        // Get created users
        $pdo = getDBConnection();
        $stmt = $pdo->query("SELECT username, email, first_name, last_name, role FROM users ORDER BY created_at ASC");
        $users = $stmt->fetchAll();
        
        echo "<h3>Created User Accounts:</h3>";
        echo "<ul>";
        foreach ($users as $user) {
            echo "<li><strong>" . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . "</strong> (" . htmlspecialchars($user['email']) . ") - " . ucfirst(str_replace('_', ' ', $user['role'])) . "</li>";
        }
        echo "</ul>";
        
        echo "<h3>Default Passwords:</h3>";
        echo "<ul>";
        echo "<li><strong>Super Admin:</strong> SuperAdmin@123</li>";
        echo "<li><strong>Marketing Admin:</strong> MarketingAdmin@123</li>";
        echo "<li><strong>Marketing Author:</strong> Marketing@123</li>";
        echo "</ul>";
        
        echo "<p><strong>Important:</strong> Please change these passwords immediately after first login!</p>";
        echo "<p><a href='setup.php'>View detailed setup information</a> | <a href='login.php'>Go to Login</a> | <a href='../index.php'>View Website</a></p>";
        
    } else {
        echo "<h2 style='color: red;'>✗ Database initialization failed!</h2>";
    }
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</h2>";
}
?>
