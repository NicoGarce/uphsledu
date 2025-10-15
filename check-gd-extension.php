<?php
/**
 * GD Extension Checker
 * 
 * This file checks if the GD extension is available for image processing
 */

// Check if GD extension is loaded
$gd_loaded = extension_loaded('gd');
$gd_info = $gd_loaded ? gd_info() : null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GD Extension Check</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .check-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .status {
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .status.ok {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info-section {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .info-section h3 {
            margin-top: 0;
            color: #0066cc;
        }
        .info-section code {
            background: #f0f0f0;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        .gd-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            font-family: monospace;
            font-size: 0.9rem;
        }
        h1 {
            color: #333;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="check-container">
        <h1>GD Extension Check</h1>
        
        <?php if ($gd_loaded): ?>
            <div class="status ok">✓ GD Extension is loaded and working!</div>
            
            <div class="gd-info">
                <strong>GD Version:</strong> <?php echo $gd_info['GD Version']; ?><br>
                <strong>FreeType Support:</strong> <?php echo $gd_info['FreeType Support'] ? 'Yes' : 'No'; ?><br>
                <strong>JPEG Support:</strong> <?php echo $gd_info['JPEG Support'] ? 'Yes' : 'No'; ?><br>
                <strong>PNG Support:</strong> <?php echo $gd_info['PNG Support'] ? 'Yes' : 'No'; ?><br>
                <strong>GIF Support:</strong> <?php echo $gd_info['GIF Support'] ? 'Yes' : 'No'; ?><br>
                <strong>WebP Support:</strong> <?php echo isset($gd_info['WebP Support']) && $gd_info['WebP Support'] ? 'Yes' : 'No'; ?><br>
            </div>
        <?php else: ?>
            <div class="status error">⚠ GD Extension is NOT loaded!</div>
            
            <div class="info-section">
                <h3>How to Enable GD Extension in XAMPP:</h3>
                <ol>
                    <li>Open <code>C:\xampp\php\php.ini</code> in a text editor</li>
                    <li>Find the line: <code>;extension=gd</code></li>
                    <li>Remove the semicolon to uncomment it: <code>extension=gd</code></li>
                    <li>Save the file</li>
                    <li>Restart Apache in XAMPP Control Panel</li>
                </ol>
                
                <p><strong>Alternative:</strong> If the line doesn't exist, add this line to the php.ini file:</p>
                <code>extension=gd</code>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 20px; text-align: center;">
            <a href="admin/create-post.php" style="background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Test Image Upload</a>
        </div>
    </div>
</body>
</html>
