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
include "campus_table_manager.php";

// Ensure all campus tables exist (including Isabela and Roxas)
ensureCampusTablesExist($con);
// Ensure temporary locator-based student tables exist
ensureTmpStudentTablesExist($con);

// Find student name by locator from the campus tmp table
function findStudentByLocator($con, $locator, $campid) {
    $locator = trim($locator);
    if ($locator === '') { return null; }
    $table = mapCampusToTmpTable($campid);
    if ($table === null) { return null; }
    $t = str_replace("`", "", $table);
    $loc = mysqli_real_escape_string($con, $locator);
    $sql = "SELECT `stud_name` FROM `{$t}` WHERE `locator_num`='".$loc."' LIMIT 1";
    $res = @mysqli_query($con, $sql);
    if ($res && ($row = mysqli_fetch_assoc($res))) {
        $name = isset($row['stud_name']) ? trim($row['stud_name']) : '';
        return ($name !== '') ? $name : null;
    }
    return null;
}

// Handle AJAX verification request for locator
if (isset($_POST["verify_locator"])) {
    if (ob_get_level()) { ob_clean(); }
    header('Content-Type: application/json');
    $locno = isset($_POST['locno']) ? trim($_POST['locno']) : '';
    $campid = isset($_POST['campid']) ? $_POST['campid'] : '';
    $table = mapCampusToTmpTable($campid);
    if ($table === null) {
        echo json_encode(['success' => false, 'message' => 'Invalid campus selected.']);
    } else if (!tableExists($con, $table)) {
        echo json_encode(['success' => false, 'message' => 'Campus verification data not available.']);
    } else {
        $studentName = findStudentByLocator($con, $locno, $campid);
        if ($studentName && $locno !== '') {
            echo json_encode(['success' => true, 'name' => $studentName, 'message' => 'Locator verified successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Locator number not found. Please check your locator number and campus selection.']);
        }
    }
    exit;
}
if (isset($_POST["btnsubmit"])) {
    date_default_timezone_set("Asia/Manila");
    $campid = $_POST["campid"] ?? '';
    $locno_raw = $_POST["locno"] ?? '';
    $locno = trim($locno_raw);
    $studentName = findStudentByLocator($con, $locno, $campid);
    if ($studentName && $locno !== '') {
        $transid = $campid ."_". date("HismdY");
        // Redirect with confirmed payee name and locator
        header("Location: payment.php?payee=" . urlencode($studentName) . "&transid=" . urlencode($transid) . "&locno=" . urlencode($locno));
        die;
    } else {
        ?>
        <div style="padding:20px; background-color:#FF0000; color:#FFFFFF; font-size:20px; font-weight:bold" align="center">Entered locator number does not exist or is not validated</div>
        <?php
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

        .verification-section {
            background: var(--tertiary-color);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            border: 2px solid #e9ecef;
        }

        .verification-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: end;
        }

        .verify-btn {
            padding: 12px 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            font-family: 'Montserrat', sans-serif;
        }

        .verify-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(28, 77, 161, 0.3);
        }

        .verify-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .verification-result {
            margin-top: 15px;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
        }

        .verification-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .verification-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .verification-loading {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        .student-name {
            font-size: 18px;
            font-weight: 700;
            margin: 10px 0;
            color: var(--primary-color);
        }

        .confirmation-buttons {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .confirm-btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--alt-color-1), var(--alt-color-2));
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Montserrat', sans-serif;
        }

        .confirm-btn:hover {
            background: linear-gradient(135deg, var(--alt-color-2), var(--alt-color-1));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 198, 62, 0.3);
        }

        .reject-btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Montserrat', sans-serif;
        }

        .reject-btn:hover {
            background: linear-gradient(135deg, #c82333, #bd2130);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
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
    var isLocatorVerified = false;

    function toggleSubmit() {
        var l = document.getElementById('locno');
        var btn = document.getElementById('btnsubmit');
        var submitSection = document.getElementById('submit-section');
        if (isLocatorVerified && l.value.trim() !== '') {
            submitSection.style.display = 'block';
            btn.disabled = false;
        } else {
            submitSection.style.display = 'none';
            if (btn) btn.disabled = true;
        }
    }

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
        } else {
            if (locatorSection) locatorSection.style.display = 'none';
            if (campusInfo) campusInfo.style.display = 'none';
        }
        // Reset verification state
        isLocatorVerified = false;
        var resultDiv = document.getElementById('verification-result');
        if (resultDiv) resultDiv.innerHTML = '';
        var submitHelp = document.getElementById('submit-help');
        if (submitHelp) submitHelp.innerHTML = 'Please verify your locator number before proceeding';
        toggleSubmit();
    }

    function confirmLocator() {
        isLocatorVerified = true;
        var submitHelp = document.getElementById('submit-help');
        if (submitHelp) submitHelp.innerHTML = '✓ Name confirmed! You may now proceed with payment.';
        toggleSubmit();
        setTimeout(function() {
            var submitBtn = document.getElementById('btnsubmit');
            if (submitBtn) submitBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    }

    function rejectLocator() {
        isLocatorVerified = false;
        document.getElementById('locno').value = '';
        var resultDiv = document.getElementById('verification-result');
        if (resultDiv) resultDiv.innerHTML = '';
        var submitHelp = document.getElementById('submit-help');
        if (submitHelp) submitHelp.innerHTML = 'Please verify your locator number before proceeding';
        toggleSubmit();
        var locatorSection = document.getElementById('locator-section');
        var campusInfo = document.getElementById('campus-info');
        if (locatorSection) locatorSection.style.display = 'none';
        if (campusInfo) campusInfo.style.display = 'none';
        document.getElementById('campid').value = '';
    }

    function verifyLocator() {
        var locno = document.getElementById('locno').value.trim();
        var campid = document.getElementById('campid').value;
        var resultDiv = document.getElementById('verification-result');
        var verifyBtn = document.getElementById('verifyBtn');
        if (locno === '') { alert('Please enter a locator number first.'); return; }
        if (campid === '') { alert('Please select a campus first.'); return; }
        verifyBtn.disabled = true;
        verifyBtn.innerHTML = 'Verifying...';
        resultDiv.innerHTML = '<div class="verification-loading">Verifying locator...</div>';
        var formData = new FormData();
        formData.append('verify_locator', '1');
        formData.append('locno', locno);
        formData.append('campid', campid);
        fetch('', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = '<div class="verification-success">' +
                                      '<div>✓ ' + data.message + '</div>' +
                                      '<div class="student-name">' + data.name + '</div>' +
                                      '<div>Please confirm this is your name before proceeding.</div>' +
                                      '<div class="confirmation-buttons">' +
                                      '<button type="button" onclick="confirmLocator()" class="confirm-btn">✓ Confirm</button>' +
                                      '<button type="button" onclick="rejectLocator()" class="reject-btn">✗ No</button>' +
                                      '</div>' +
                                      '</div>';
                var submitHelp = document.getElementById('submit-help');
                if (submitHelp) submitHelp.innerHTML = 'Please confirm the name above to proceed';
                toggleSubmit();
                setTimeout(function() { resultDiv.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 100);
            } else {
                isLocatorVerified = false;
                resultDiv.innerHTML = '<div class="verification-error">✗ ' + data.message + '</div>';
                var submitHelp = document.getElementById('submit-help');
                if (submitHelp) submitHelp.innerHTML = 'Please verify your locator number before proceeding';
                toggleSubmit();
                setTimeout(function() { resultDiv.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 100);
            }
        })
        .catch(error => {
            isLocatorVerified = false;
            resultDiv.innerHTML = '<div class="verification-error">✗ Error verifying locator. Please try again.</div>';
            var submitHelp = document.getElementById('submit-help');
            if (submitHelp) submitHelp.innerHTML = 'Please verify your locator number before proceeding';
            console.error('Error:', error);
            toggleSubmit();
            setTimeout(function() { resultDiv.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 100);
        })
        .finally(() => { verifyBtn.disabled = false; verifyBtn.innerHTML = 'Verify'; });
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
                <select name="campid" id="campid" class="campus-select" onchange="resetVerification()" required>
                    <option value="">Choose your campus...</option>
                    <option value="UPHB">🏫 Binan Campus</option>
                    <option value="UPHMU">🏥 Medical University</option>
                    <option value="UPHG">🏢 GMA Campus</option>
                    <option value="UPHM">🏛️ Manila Campus</option>
                    <option value="PHCP">🏘️ Pangasinan Campus</option>
                    <option value="UPHI">🏛️ Isabela Campus</option>
                    <option value="UPHR">🏛️ Roxas Campus</option>
		</select>
	</div>

            <div id="campus-info" class="campus-info" style="display: none;">
                <h4>📋 Payment Information</h4>
                <p>For campuses other than Binan, you can proceed directly to payment. Please ensure you have your payment details ready.</p>
            </div>

            <div id="locator-section" style="display: none;">
                <div class="transref-section">
                    <h4 style="color: #12199C; margin-bottom: 20px; text-align: center;">📝 Locator Number Required</h4>
                    <div id="submit-help" class="help-text" style="text-align: center; margin-bottom: 15px;">Please verify your locator number before proceeding</div>
                    <div class="verification-form">
                        <div class="input-group">
                            <label class="form-label" for="locno">Locator Number</label>
                            <input type="text" name="locno" id="locno" class="form-input" maxlength="20" placeholder="Enter locator number" oninput="resetVerification()" required>
                        </div>
                        <button type="button" id="verifyBtn" onclick="verifyLocator()" class="verify-btn">🔍 Verify</button>
                    </div>
                    <div id="verification-result" class="verification-result"></div>
                </div>
            </div>

            <div id="submit-section" style="display: none;">
                <button type="submit" id="btnsubmit" name="btnsubmit" class="submit-btn" disabled>
                    🚀 Proceed to Payment
                </button>
            </div>
	</form>	
    </div>
</body>
</html>