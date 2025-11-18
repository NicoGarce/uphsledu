<?php 	
session_start();

// Check maintenance before any output
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if Guest (New Enrollees) or Online Payment section is in maintenance
// isSectionInMaintenance already checks main section if sub-page is not enabled
if (isSectionInMaintenance('online-payment', 'guest')) {
    $maintenance_message = getSectionMaintenanceMessage('online-payment', null, 'guest');
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
 if (isset($_POST["btnsubmit"])) {
     if ($_POST["campid"]<>"UPHB") {
      date_default_timezone_set("Asia/Manila");
	//$transid = "UPHSL" . date("HismdY");
	$transid = $_POST["campid"] ."_". date("HismdY");
    //header("Location: payment.php?payee=&transid=$transid");
	$locno = urlencode($_POST["locno"] ?? '');
	$gtrn = urlencode($_POST["gtrn"] ?? '');
	header("Location: payment.php?payee=&transid=" . urlencode($transid) . "&locno=$locno&gtrno=$gtrn");
     } else {
      $totalrec = 0;
      // Use prepared statement to prevent SQL injection
      $stmt = mysqli_prepare($con, "SELECT COUNT(*) as totalrec FROM tblgtrn WHERE locno=? AND gtrno=? AND is_valid=1");
      if ($stmt) {
        $locno = $_POST["locno"] ?? '';
        $gtrn = $_POST["gtrn"] ?? '';
        mysqli_stmt_bind_param($stmt, "ss", $locno, $gtrn);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
      } else {
        $result = false;
      }	   
	  while ($row = mysqli_fetch_array($result)) {	
	    $totalrec = $row["totalrec"];
	  }
     if ($totalrec>=1) {   
    date_default_timezone_set("Asia/Manila");
	//$transid = "UPHSL" . date("HismdY");
	$transid = $_POST["campid"] ."_". date("HismdY");
    //header("Location: payment.php?payee=&transid=$transid");
	$locno = urlencode($_POST["locno"] ?? '');
	$gtrn = urlencode($_POST["gtrn"] ?? '');
	header("Location: payment.php?payee=&transid=" . urlencode($transid) . "&locno=$locno&gtrno=$gtrn");
	 } else {
	?>
	 <div style="padding:20px; background-color:#FF0000; color:#FFFFFF; font-size:30px; font-weight:bold" align="center">Entered transaction reference no. does<br>not exist or is not validated</div>
<?php	
	 }
    die;
   }
 }
?>	
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPHSL Online Payment - New Students</title>
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
        
        .transref-section {
            background: var(--tertiary-color);
            border-radius: 15px;
            padding: 25px;
            margin-top: 20px;
            border: 2px solid #e9ecef;
        }
        
        .input-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .input-group {
            flex: 1;
            min-width: 200px;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            margin-top: 8px;
            font-family: 'Montserrat', sans-serif;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(28, 77, 161, 0.1);
        }
        
        .help-text {
            color: var(--text-light);
            font-size: 12px;
            margin-top: 5px;
            font-style: italic;
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
        
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .input-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .input-group {
                min-width: 100%;
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
                font-size: 20px;
            }
        }
    </style>

<script>
 function checkcampus(campval) {
            const transrefSection = document.getElementById("transref");
            const campusInfo = document.getElementById("campus-info");
            
            if (campval !== "UPHB") {
                transrefSection.style.display = "none";
                campusInfo.style.display = "block";
     } else {
                transrefSection.style.display = "block";
                campusInfo.style.display = "none";
            }
        }
        
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
            <div class="welcome-text">Welcome, New Perpetualites!</div>
        </div>

	<form method="post">
            <div class="form-group">
                <label class="form-label" for="campid">Select Your Campus</label>
                <select name="campid" id="campid" class="campus-select" onchange="checkcampus(this.value)" required>
                    <option value="">Choose your campus...</option>
                    <option value="UPHB">🏫 Binan Campus</option>
                    <option value="UPHMU">🏥 Medical University</option>
                    <option value="UPHG">🏢 GMA Campus</option>
                    <option value="UPHM">🏛️ Manila Campus</option>
                    <option value="PHCP">🏘️ Pangasinan Campus</option>
		</select>
	</div>

            <div id="campus-info" class="campus-info" style="display: none;">
                <h4>📋 Payment Information</h4>
                <p>For campuses other than Binan, you can proceed directly to payment. Please ensure you have your payment details ready.</p>
            </div>

            <div id="transref" style="display: none;">
                <div class="transref-section">
                    <h4 style="color: #12199C; margin-bottom: 20px; text-align: center;">📝 Transaction Reference Required</h4>
                    <div class="input-row">
                        <div class="input-group">
                            <label class="form-label" for="locno">Locator Number</label>
                            <input type="text" name="locno" id="locno" class="form-input" maxlength="20" placeholder="Enter locator number">
                            <div class="help-text">For new students only</div>
                        </div>
                        <div class="input-group">
                            <label class="form-label" for="gtrn">Transaction Reference</label>
                            <input type="text" name="gtrn" id="gtrn" class="form-input" maxlength="20" placeholder="Enter reference number">
                            <div class="help-text">Provided by your College/Department</div>
		</div>
		</div>
	</div>
</div>

            <button type="submit" name="btnsubmit" class="submit-btn">
                🚀 Proceed to Payment
            </button>
	</form>	
    </div>
</body>
</html>