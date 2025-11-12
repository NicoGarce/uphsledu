<?php
/**
 * UPHSL Maintenance Mode Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Maintenance mode page displayed when the website is in maintenance mode
 */

require_once __DIR__ . '/app/config/paths.php';
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/includes/functions.php';

// Get maintenance message from settings
$maintenance_message = getSetting('maintenance_message', 'We are currently performing scheduled maintenance. Please check back soon.');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode - UPHSL</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #1c4da1 0%, #143980 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #fff;
        }
        
        .maintenance-container {
            text-align: center;
            max-width: 600px;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 60px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .maintenance-icon {
            font-size: 80px;
            margin-bottom: 30px;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .message {
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 40px;
            opacity: 0.95;
        }
        
        .logo {
            margin-top: 40px;
            opacity: 0.9;
        }
        
        .logo img {
            max-width: 200px;
            height: auto;
            filter: brightness(1.2);
        }
        
        @media (max-width: 768px) {
            .maintenance-container {
                padding: 40px 30px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .message {
                font-size: 1rem;
            }
            
            .maintenance-icon {
                font-size: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">🔧</div>
        <h1>Under Maintenance</h1>
        <p class="message"><?php echo htmlspecialchars($maintenance_message); ?></p>
        <div class="logo">
            <img src="<?php echo $GLOBALS['base_path']; ?>assets/images/logo.png" alt="UPHSL Logo" onerror="this.style.display='none'">
        </div>
    </div>
</body>
</html>

