<?php
/**
 * UPHSL 404 Error Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Custom 404 error page for the UPHSL website
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - University of Perpetual Help System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@400;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link rel="shortcut icon" type="image/png" href="assets/images/logo.png">
    <link rel="apple-touch-icon" href="assets/images/logo.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1c4da1 0%, #527bbd 100%);
            color: white;
            text-align: center;
            padding: 20px;
        }
        
        .error-content {
            max-width: 600px;
            background: rgba(255, 255, 255, 0.1);
            padding: 60px 40px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .error-code {
            font-family: 'Barlow Semi Condensed', sans-serif;
            font-size: 8rem;
            font-weight: 800;
            color: #ffc63e;
            margin-bottom: 20px;
            text-shadow: 0 0 20px rgba(255, 198, 62, 0.5);
        }
        
        .error-title {
            font-family: 'Barlow Semi Condensed', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .error-message {
            font-size: 1.2rem;
            margin-bottom: 40px;
            line-height: 1.6;
            opacity: 0.9;
        }
        
        .error-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: #ffc63e;
            color: #1c4da1;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn:hover {
            background: #e0b03c;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 198, 62, 0.3);
        }
        
        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-secondary:hover {
            background: white;
            color: #1c4da1;
        }
        
        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }
            
            .error-title {
                font-size: 2rem;
            }
            
            .error-content {
                padding: 40px 20px;
            }
            
            .error-actions {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-content">
            <div class="error-code">404</div>
            <h1 class="error-title">Page Not Found</h1>
            <p class="error-message">
                The page you're looking for doesn't exist or has been moved. 
                Please check the URL or return to our homepage.
            </p>
            <div class="error-actions">
                <a href="index.php" class="btn">
                    <i class="fas fa-home"></i>
                    Go Home
                </a>
                <a href="posts.php" class="btn btn-secondary">
                    <i class="fas fa-newspaper"></i>
                    View Posts
                </a>
            </div>
        </div>
    </div>
</body>
</html>
