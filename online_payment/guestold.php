<?php 	
session_start();

// Check maintenance before any output
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Get campus from URL parameter for pre-selection
$selected_campus = isset($_GET['campus']) ? strtoupper(trim($_GET['campus'])) : '';

// Function to generate shareable link with campus parameter
function generateShareableLink($campus) {
    $base_url = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
    $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $current_path = dirname($current_path);
    
    // Remove existing query parameters
    $base_url = rtrim($base_url, '/');
    
    return $base_url . '?campus=' . $campus;
}

// Check if Other Payments or Online Payment section is in maintenance
// isSectionInMaintenance already checks main section if sub-page is not enabled
if (isSectionInMaintenance('online-payment', 'guestold')) {
    $maintenance_message = getSectionMaintenanceMessage('online-payment', null, 'guestold');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Online Payment - Maintenance</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                margin: 0;
                background: #f5f5f5;
            }
            .maintenance-container {
                text-align: center;
                max-width: 600px;
                padding: 3rem;
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            }
            .maintenance-icon {
                font-size: 4rem;
                color: #1c4da1;
                margin-bottom: 1.5rem;
            }
            h1 {
                font-size: 2rem;
                color: #1c4da1;
                margin-bottom: 1rem;
            }
            p {
                font-size: 1.1rem;
                color: #666;
                line-height: 1.6;
                margin-bottom: 2rem;
            }
            .btn {
                display: inline-block;
                padding: 0.75rem 1.5rem;
                background: #1c4da1;
                color: white;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
            }
        </style>
    </head>
    <body>
        <div class="maintenance-container">
            <div class="maintenance-icon">🔧</div>
            <h1>Under Maintenance</h1>
            <p><?php echo htmlspecialchars($maintenance_message); ?></p>
            <a href="../index.php" class="btn">Go to Homepage</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$_SESSION['isin'] = 'iamin';

include "dbconnect.php";
include "campus_table_manager.php";

// Ensure all campus tables exist (including Isabela and Roxas)
ensureCampusTablesExist($con);

if (isset($_POST["btnsubmit"])) {
    date_default_timezone_set("Asia/Manila");
    $transid = $_POST["campid"] ."_". date("HismdY");
    header("Location: paymentold.php?payee=&transid=$transid");
    die;
}
?>	

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPHSL Online Payment - Perpetualites</title>
    <link rel="icon" type="image/png" href="../assets/images/Logos/logo.png">
    <link rel="shortcut icon" type="image/png" href="../assets/images/Logos/logo.png">
    
    <!-- Import UPHSL Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@600;800&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary-color: #1c4da1;
            --secondary-color: #527bbd;
            --tertiary-color: #f8f9fa;
            --text-dark: #2a2a2a;
            --text-light: #666;
            --alt-color-1: #ffc63e;
            --alt-color-2: #e0b03c;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--alt-color-1), var(--secondary-color));
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            background: var(--primary-color);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(28, 77, 161, 0.3);
        }
        
        .logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        
        .university-name {
            color: var(--primary-color);
            font-family: 'Barlow Semi Condensed', sans-serif;
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 10px;
        }
        
        .page-title {
            color: var(--secondary-color);
            font-family: 'Barlow Semi Condensed', sans-serif;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .welcome-text {
            color: var(--text-light);
            font-size: 18px;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .campus-select {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
        }
        
        .campus-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(28, 77, 161, 0.1);
        }
        
        .submit-btn {
            width: 100%;
            padding: 15px 30px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Montserrat', sans-serif;
        }
        
        .submit-btn:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(28, 77, 161, 0.3);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .campus-info {
            background: #e3f2fd;
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            margin-top: 20px;
            border-radius: 0 8px 8px 0;
        }
        
        .campus-info h4 {
            color: var(--primary-color);
            font-family: 'Barlow Semi Condensed', sans-serif;
            margin-bottom: 10px;
        }
        
        .campus-info p {
            color: var(--text-light);
            font-size: 14px;
            line-height: 1.5;
        }

        .share-link {
            display: inline-block;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 12px;
            margin-top: 10px;
            transition: color 0.3s ease;
        }

        .share-link:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .university-name {
                font-size: 20px;
            }
            
            .page-title {
                font-size: 18px;
            }
            
            .welcome-text {
                font-size: 16px;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .container {
                padding: 20px 15px;
            }
            
            .logo {
                width: 60px;
                height: 60px;
            }
            
            .logo img {
                width: 50px;
                height: 50px;
            }
        }
    </style>

    <script>
        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.6s ease';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });

        function resetVerification() {
            var campid = document.getElementById('campid').value;
            var locatorSection = document.getElementById('locator-section');
            var campusInfo = document.getElementById('campus-info');
            // Show locator input when a campus is selected; show campus info for non-UPHB
            if (campid !== '') {
                if (locatorSection) locatorSection.style.display = 'block';
                if (campid !== 'UPHB') {
                    if (campusInfo) campusInfo.style.display = 'block';
                } else {
                    if (campusInfo) campusInfo.style.display = 'none';
                }
                // Update URL with campus parameter
                updateURLWithCampus(campid);
            } else {
                if (locatorSection) locatorSection.style.display = 'none';
                if (campusInfo) campusInfo.style.display = 'none';
                // Remove campus parameter from URL
                updateURLWithCampus('');
            }
            // Reset verification state
            isLocatorVerified = false;
            var resultDiv = document.getElementById('verification-result');
            if (resultDiv) resultDiv.innerHTML = '';
            var submitHelp = document.getElementById('submit-help');
            if (submitHelp) submitHelp.innerHTML = 'Please verify your locator number before proceeding';
            toggleSubmit();
        }

        function updateURLWithCampus(campus) {
            var url = new URL(window.location.href);
            
            if (campus) {
                url.searchParams.set('campus', campus);
            } else {
                url.searchParams.delete('campus');
            }
            
            // Update URL without page reload
            window.history.replaceState({}, '', url.toString());
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="../assets/images/Logos/Logo2025.png" alt="UPHSL Logo 2025">
            </div>
            <div class="university-name">University of Perpetual Help System</div>
            <div class="page-title">Online Payment Portal</div>
            <div class="welcome-text">Welcome, Perpetualites!</div>
        </div>

        <form method="post">
            <div class="form-group">
                <label class="form-label" for="campid">Select Your Campus</label>
                <select name="campid" id="campid" class="campus-select" onchange="resetVerification()" required>
                    <option value="">Choose your campus...</option>
                    <option value="UPHB" <?php echo ($selected_campus === 'UPHB') ? 'selected' : ''; ?>>🏫 Binan Campus</option>
                    <option value="UPHMU" <?php echo ($selected_campus === 'UPHMU') ? 'selected' : ''; ?>>🏥 Medical University</option>
                    <option value="UPHG" <?php echo ($selected_campus === 'UPHG') ? 'selected' : ''; ?>>🏢 GMA Campus</option>
                    <option value="UPHM" <?php echo ($selected_campus === 'UPHM') ? 'selected' : ''; ?>>🏛️ Manila Campus</option>
                    <option value="PHCP" <?php echo ($selected_campus === 'PHCP') ? 'selected' : ''; ?>>🏘️ Pangasinan Campus</option>
                    <option value="UPHI" <?php echo ($selected_campus === 'UPHI') ? 'selected' : ''; ?>>🏛️ Isabela Campus</option>
                    <option value="UPHR" <?php echo ($selected_campus === 'UPHR') ? 'selected' : ''; ?>>🏛️ Roxas Campus</option>
                </select>
            </div>

            <div class="campus-info">
                <h4>📋 Payment Information</h4>
                <p>Please select your campus to proceed with the payment. Make sure you have your payment details ready.</p>
            </div>

            <button type="submit" name="btnsubmit" class="submit-btn">
                🚀 Proceed to Payment
            </button>
        </div>
	</form>	
    </div>
</body>
</html>