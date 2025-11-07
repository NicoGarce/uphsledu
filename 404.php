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
    <link rel="icon" type="image/png" href="assets/images/Logos/logo.png">
    <link rel="shortcut icon" type="image/png" href="assets/images/Logos/logo.png">
    <link rel="apple-touch-icon" href="assets/images/Logos/logo.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Override any potential spacing from main stylesheet */
        body {
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .error-container {
            margin: 0 !important;
            padding: 20px !important;
        }
    </style>
    <style>
        :root {
            --primary-color: #1c4da1;
            --secondary-color: #527bbd;
            --accent-color: #ffc63e;
            --text-light: #ffffff;
            --text-dark: #333333;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            color: var(--text-light);
            overflow-x: hidden;
        }
        
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 20px;
            position: relative;
            margin: 0;
        }
        
        .error-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .error-content {
            max-width: 700px;
            background: rgba(255, 255, 255, 0.15);
            padding: 80px 60px;
            border-radius: 30px;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-align: center;
            position: relative;
            z-index: 1;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            margin: 40px 0;
        }
        
        .error-code {
            font-family: 'Barlow Semi Condensed', sans-serif;
            font-size: 10rem;
            font-weight: 800;
            color: var(--accent-color);
            margin-bottom: 30px;
            text-shadow: 0 0 30px rgba(255, 198, 62, 0.6);
            line-height: 0.8;
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from { text-shadow: 0 0 30px rgba(255, 198, 62, 0.6); }
            to { text-shadow: 0 0 40px rgba(255, 198, 62, 0.8), 0 0 60px rgba(255, 198, 62, 0.4); }
        }
        
        .error-title {
            font-family: 'Barlow Semi Condensed', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-light);
        }
        
        .error-message {
            font-size: 1.3rem;
            margin-bottom: 50px;
            line-height: 1.7;
            opacity: 0.95;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .error-actions {
            display: flex;
            gap: 25px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 18px 35px;
            background: var(--accent-color);
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(255, 198, 62, 0.3);
        }
        
        .btn:hover {
            background: #e0b03c;
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(255, 198, 62, 0.4);
            color: var(--primary-color);
        }
        
        .btn-secondary {
            background: transparent;
            color: var(--text-light);
            border: 2px solid var(--text-light);
            box-shadow: none;
        }
        
        .btn-secondary:hover {
            background: var(--text-light);
            color: var(--primary-color);
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(255, 255, 255, 0.2);
        }
        
        .quick-links {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .quick-links h3 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: var(--accent-color);
            font-weight: 600;
        }
        
        .quick-links-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .quick-link {
            display: block;
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            text-decoration: none;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .quick-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            color: var(--text-light);
        }
        
        @media (max-width: 768px) {
            .error-content {
                padding: 60px 30px;
                margin: 20px;
            }
            
            .error-code {
                font-size: 7rem;
            }
            
            .error-title {
                font-size: 2.2rem;
            }
            
            .error-message {
                font-size: 1.1rem;
                margin-bottom: 40px;
            }
            
            .error-actions {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
            
            .btn {
                width: 100%;
                max-width: 280px;
                justify-content: center;
            }
            
            .quick-links-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .error-content {
                padding: 40px 20px;
            }
            
            .error-code {
                font-size: 5rem;
            }
            
            .error-title {
                font-size: 1.8rem;
            }
            
            .error-message {
                font-size: 1rem;
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
                Oops! The page you're looking for seems to have wandered off. 
                Don't worry, even the best students sometimes take a wrong turn. 
                Let's get you back on track!
            </p>
            <div class="error-actions">
                <a href="index.php" class="btn">
                    <i class="fas fa-home"></i>
                    Go Home
                </a>
                <a href="posts.php" class="btn btn-secondary">
                    <i class="fas fa-newspaper"></i>
                    View News
                </a>
            </div>
            
            <div class="quick-links">
                <h3>Quick Navigation</h3>
                <div class="quick-links-grid">
                    <a href="programs.php" class="quick-link">
                        <i class="fas fa-graduation-cap"></i>
                        Academic Programs
                    </a>
                    <a href="about.php" class="quick-link">
                        <i class="fas fa-university"></i>
                        About UPHSL
                    </a>
                    <a href="support-services/" class="quick-link">
                        <i class="fas fa-headset"></i>
                        Support Services
                    </a>
                    <a href="ols_instructions.php" class="quick-link">
                        <i class="fas fa-globe"></i>
                        Online Services
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
