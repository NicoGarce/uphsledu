<?php
/**
 * Production Deployment Helper
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Helper script to verify production readiness
 */

echo "<h1>UPHSL Website - Production Deployment Check</h1>";

// Check if we're in development or production
$isDevelopment = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || 
                 strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

echo "<h2>Environment Detection</h2>";
echo "<p><strong>Current Environment:</strong> " . ($isDevelopment ? "Development" : "Production") . "</p>";
echo "<p><strong>HTTP Host:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";
echo "<p><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";

// Include path configuration
require_once 'app/config/paths.php';

echo "<h2>Path Configuration</h2>";
echo "<p><strong>Base Path:</strong> '" . $GLOBALS['base_path'] . "'</p>";

// Check if paths.php is working
if (isset($GLOBALS['base_path'])) {
    echo "<p style='color: green;'>✅ Path configuration is working correctly</p>";
} else {
    echo "<p style='color: red;'>❌ Path configuration failed</p>";
}

// Check required files
echo "<h2>Required Files Check</h2>";
$requiredFiles = [
    'app/config/database.php',
    'app/config/paths.php',
    'app/includes/functions.php',
    'app/includes/header.php',
    'app/includes/footer.php',
    'app/includes/admin-header.php',
    'app/includes/admin-footer.php',
    '.htaccess',
    'index.php',
    'about.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $file</p>";
    } else {
        echo "<p style='color: red;'>❌ $file (MISSING)</p>";
    }
}

// Check uploads directory
echo "<h2>Directory Permissions Check</h2>";
if (is_dir('uploads')) {
    if (is_writable('uploads')) {
        echo "<p style='color: green;'>✅ uploads/ directory is writable</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ uploads/ directory exists but is not writable</p>";
    }
} else {
    echo "<p style='color: red;'>❌ uploads/ directory does not exist</p>";
}

// Check .htaccess
echo "<h2>URL Rewriting Check</h2>";
if (file_exists('.htaccess')) {
    echo "<p style='color: green;'>✅ .htaccess file exists</p>";
    
    $htaccessContent = file_get_contents('.htaccess');
    if (strpos($htaccessContent, 'RewriteEngine On') !== false) {
        echo "<p style='color: green;'>✅ URL rewriting is enabled</p>";
    } else {
        echo "<p style='color: red;'>❌ URL rewriting not properly configured</p>";
    }
} else {
    echo "<p style='color: red;'>❌ .htaccess file missing</p>";
}

echo "<h2>Production Deployment Instructions</h2>";
echo "<ol>";
echo "<li>Upload all files to your cPanel public_html directory</li>";
echo "<li>Create a MySQL database in cPanel</li>";
echo "<li>Update database credentials in app/config/database.php</li>";
echo "<li>Set uploads/ directory permissions to 755 or 777</li>";
echo "<li>Visit yourdomain.com/auth/setup.php to initialize</li>";
echo "<li>Test the website at yourdomain.com</li>";
echo "</ol>";

echo "<h2>Notes</h2>";
echo "<ul>";
echo "<li>The system will automatically detect production environment</li>";
echo "<li>All paths will be automatically configured</li>";
echo "<li>No manual path changes are needed</li>";
echo "<li>Clean URLs will work automatically</li>";
echo "</ul>";

echo "<p><strong>Ready for Production:</strong> " . (isset($GLOBALS['base_path']) ? "✅ YES" : "❌ NO") . "</p>";
?>
