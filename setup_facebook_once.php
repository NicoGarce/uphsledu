<?php
/**
 * One-time Facebook Setup
 * Run this once to set up Facebook integration
 */

require_once 'app/config/database.php';

try {
    $pdo = getDBConnection();
    
    echo "<h2>Facebook Integration Setup</h2>";
    
    // Create facebook_tokens table
    $createTable = "
    CREATE TABLE IF NOT EXISTS facebook_tokens (
        id INT PRIMARY KEY DEFAULT 1,
        app_id VARCHAR(255) NOT NULL,
        app_secret VARCHAR(255) NOT NULL,
        page_id VARCHAR(255) NOT NULL,
        page_access_token TEXT NOT NULL,
        token_expires_at TIMESTAMP NOT NULL,
        last_refreshed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($createTable);
    echo "✅ Database table created<br>";
    
    // Insert your token data
    $appId = '1179084790748948';
    $appSecret = '3e48bf434afadfc30f8971e5b8d72c3b';
    $pageId = '1219056361442381';
    $pageAccessToken = 'EAAQwXxIc1xQBPq7My47R4l0i9HHSrCDk8Nt2bnhGtyfiDLStr1c3EVBZAQ5eeLiKri7IJmGYLUmbcTC22p9s8vyTvEy3lGF5yfn2W6LbpNPA3glOQb5R5H3ZC6i5Rgb96ACxe8ZAO0htyqZBevEZB0uGbvgEvjoyOZBTjyAiVCFGSMY8qQKcIdRYWxTaYzQU4yxz2f9MA5';
    $expiresAt = date('Y-m-d H:i:s', time() + 5183062); // 60 days
    
    // Check if data already exists
    $stmt = $pdo->prepare("SELECT id FROM facebook_tokens WHERE id = 1");
    $stmt->execute();
    $exists = $stmt->fetch();
    
    if ($exists) {
        // Update existing data
        $stmt = $pdo->prepare("
            UPDATE facebook_tokens 
            SET app_id = ?, app_secret = ?, page_id = ?, page_access_token = ?, 
                token_expires_at = ?, last_refreshed_at = NOW(), updated_at = NOW()
            WHERE id = 1
        ");
        $stmt->execute([$appId, $appSecret, $pageId, $pageAccessToken, $expiresAt]);
        echo "✅ Token data updated<br>";
    } else {
        // Insert new data
        $stmt = $pdo->prepare("
            INSERT INTO facebook_tokens (id, app_id, app_secret, page_id, page_access_token, token_expires_at, created_at, updated_at) 
            VALUES (1, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([$appId, $appSecret, $pageId, $pageAccessToken, $expiresAt]);
        echo "✅ Token data inserted<br>";
    }
    
    echo "<br><h3>✅ Setup Complete!</h3>";
    echo "Your Facebook integration is ready. You can now:<br>";
    echo "• Share posts to Facebook from <a href='admin/posts.php'>Posts Management</a><br>";
    echo "• Refresh tokens from <a href='admin/dashboard.php'>Admin Dashboard</a><br>";
    echo "<br><strong>You can delete this file now.</strong>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
