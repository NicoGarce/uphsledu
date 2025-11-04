<?php 	
	include "dbconnect.php";

	function mapCampusToTable($campid) {
		$map = [
			"UPHB" => "binan",
			"UPHMU" => "medical_university",
			"UPHG" => "gma",
			"UPHM" => "manila",
			"PHCP" => "pangasinan"
		];
		return isset($map[$campid]) ? $map[$campid] : null;
	}

	function tableExists($con, $tableName) {
		if (trim($tableName) === '') { return false; }
		$t = mysqli_real_escape_string($con, $tableName);
		$sql = "SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name='".$t."' LIMIT 1";
		$res = @mysqli_query($con, $sql);
		if ($res && mysqli_fetch_row($res)) { return true; }
		return false;
	}

	function findStudentByNumber($con, $studentNumber, $campid) {
		$studentNumber = trim($studentNumber);
		if ($studentNumber === '') { return null; }
		$table = mapCampusToTable($campid);
		if ($table === null) { return null; }
		$t = str_replace("`", "", $table);
		$stud = mysqli_real_escape_string($con, $studentNumber);
	$sql = "SELECT `lname`, `fname` FROM `{$t}` WHERE `stud_num`='".$stud."' LIMIT 1";
	$res = @mysqli_query($con, $sql);
	if ($res && ($row = mysqli_fetch_assoc($res))) {
		$lname = isset($row['lname']) ? trim($row['lname']) : '';
		$fname = isset($row['fname']) ? trim($row['fname']) : '';
		$name = trim($fname . (($fname !== '' && $lname !== '') ? ' ' : '') . $lname);
		return ($name !== '') ? $name : null;
	}
		return null;
	}


	// Handle AJAX verification request
	if (isset($_POST["verify_student"])) {
		// Clear any previous output
		if (ob_get_level()) {
			ob_clean();
		}
		
		// Set proper headers
		header('Content-Type: application/json');
		
		$studno = isset($_POST['studentno']) ? trim($_POST['studentno']) : '';
		$campid = isset($_POST['campid']) ? $_POST['campid'] : '';
		$table = mapCampusToTable($campid);
		
		if ($table === null) {
			echo json_encode(['success' => false, 'message' => 'Invalid campus selected.']);
		} else if (!tableExists($con, $table)) {
			echo json_encode(['success' => false, 'message' => 'Campus database not available.']);
		} else {
			$studentName = findStudentByNumber($con, $studno, $campid);
			if ($studentName && $studno !== '') {
				echo json_encode(['success' => true, 'name' => $studentName, 'message' => 'Student verified successfully!']);
			} else {
				echo json_encode(['success' => false, 'message' => 'Student number not found. Please check your student number and campus selection.']);
			}
		}
		exit;
	}

	if (isset($_POST["btnsubmit"])) {
		date_default_timezone_set("Asia/Manila");
		$transid = $_POST["campid"] ."_". date("HismdY");
		$studno = isset($_POST['studentno']) ? trim($_POST['studentno']) : '';
		$campid = isset($_POST['campid']) ? $_POST['campid'] : '';
		$table = mapCampusToTable($campid);
		if ($table === null) {
			$err = "We couldn't verify the student number. Please review your campus and student number, then try again.";
		} else if (!tableExists($con, $table)) {
			$err = "We couldn't verify the student number. Please review your campus and student number, then try again.";
		} else {
			$studentName = findStudentByNumber($con, $studno, $campid);
			if ($studentName && $studno !== '') {
				header("Location: payment_oldstud.php?payee=".urlencode($studentName)."&transid=$transid&studentno=".urlencode($studno));
				die;
			} else {
				$err = "We couldn't verify the student number. Please review your campus and student number, then try again.";
			}
		}
	}
?>	

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPHSL Online Payment - Current Students</title>
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
        
        .verification-section {
            background: var(--tertiary-color);
            border-radius: 15px;
            padding: 25px;
            margin: 20px 0;
            border: 2px solid #e9ecef;
        }
        
        .verification-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: end;
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
        
        .verify-btn {
            padding: 12px 25px;
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
        
        .help-text {
            color: var(--text-light);
            font-size: 12px;
            margin-top: 5px;
            font-style: italic;
        }
        
        .verification-result {
            margin-top: 20px;
            padding: 15px;
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
            margin-top: 20px;
            font-family: 'Montserrat', sans-serif;
        }
        
        .submit-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(28, 77, 161, 0.3);
        }
        
        .submit-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }
        
        .student-info {
            background: #e3f2fd;
            border-left: 4px solid var(--primary-color);
            padding: 12px 15px;
            margin-top: 20px;
            border-radius: 0 10px 10px 0;
        }
        
        .student-info h4 {
            color: var(--primary-color);
            font-family: 'Barlow Semi Condensed', sans-serif;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .student-info p {
            color: var(--text-light);
            font-size: 12px;
            line-height: 1.4;
            margin-bottom: 6px;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #f5c6cb;
            text-align: center;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .verification-form {
                flex-direction: column;
                gap: 15px;
            }
            
            .input-group {
                min-width: 100%;
            }
            
            .confirmation-buttons {
                flex-direction: column;
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
            
            .verification-section {
                padding: 20px 15px;
            }
        }
    </style>

<script>
var isStudentVerified = false;

function toggleSubmit() {
	var s = document.getElementById('studentno');
	var btn = document.getElementById('btnsubmit');
	var submitSection = document.getElementById('submit-section');
	
	// Show submit button only when student is verified
	if (isStudentVerified && s.value.trim() !== '') {
		submitSection.style.display = 'block';
		btn.disabled = false;
	} else {
		submitSection.style.display = 'none';
		btn.disabled = true;
	}
}

function resetVerification() {
	var campid = document.getElementById('campid').value;
	var verificationSection = document.getElementById('verification-section');
	
	// Show verification section only if campus is selected
	if (campid !== '') {
		verificationSection.style.display = 'block';
	} else {
		verificationSection.style.display = 'none';
	}
	
	// Reset verification state
	isStudentVerified = false;
	document.getElementById('verification-result').innerHTML = '';
	document.getElementById('submit-help').innerHTML = 'Please verify your student number before proceeding';
	toggleSubmit();
}

function confirmStudent() {
	isStudentVerified = true;
	document.getElementById('submit-help').innerHTML = '✓ Student confirmed! You may now proceed with payment.';
	toggleSubmit();
	
	// Scroll to the proceed to payment button after it appears
	setTimeout(function() {
		var submitBtn = document.getElementById('btnsubmit');
		if (submitBtn) {
			submitBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
		}
	}, 100);
}

function rejectStudent() {
	// Reset everything back to initial state
	isStudentVerified = false;
	document.getElementById('studentno').value = '';
	document.getElementById('verification-result').innerHTML = '';
	document.getElementById('submit-help').innerHTML = 'Please verify your student number before proceeding';
	toggleSubmit();
	
	// Hide verification section and reset campus selection
	document.getElementById('verification-section').style.display = 'none';
	document.getElementById('campid').value = '';
}

function verifyStudent() {
	var studentno = document.getElementById('studentno').value.trim();
	var campid = document.getElementById('campid').value;
	var resultDiv = document.getElementById('verification-result');
	var verifyBtn = document.getElementById('verifyBtn');
	
	if (studentno === '') {
		alert('Please enter a student number first.');
		return;
	}
	
	if (campid === '') {
		alert('Please select a campus first.');
		return;
	}
	
	// Disable button and show loading
	verifyBtn.disabled = true;
            verifyBtn.innerHTML = 'Verifying...';
            resultDiv.innerHTML = '<div class="verification-loading">Verifying student...</div>';
	
	// Create form data
	var formData = new FormData();
	formData.append('verify_student', '1');
	formData.append('studentno', studentno);
	formData.append('campid', campid);
	
	// Send AJAX request
	fetch('', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.success) {
			// Don't set isStudentVerified to true yet - wait for user confirmation
                    resultDiv.innerHTML = '<div class="verification-success">' +
                                        '<div>✓ ' + data.message + '</div>' +
                                        '<div class="student-name">' + data.name + '</div>' +
                                        '<div>Please confirm this is your name before proceeding.</div>' +
                                        '<div class="confirmation-buttons">' +
                                        '<button type="button" onclick="confirmStudent()" class="confirm-btn">✓ Confirm</button>' +
                                        '<button type="button" onclick="rejectStudent()" class="reject-btn">✗ No</button>' +
                                        '</div>' +
								  '</div>';
			document.getElementById('submit-help').innerHTML = 'Please confirm the student name above to proceed';
			// Keep submit button disabled until user confirms
			toggleSubmit();
			// Scroll to verification result
			setTimeout(function() {
				if (resultDiv) {
					resultDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
				}
			}, 100);
		} else {
			isStudentVerified = false;
                    resultDiv.innerHTML = '<div class="verification-error">✗ ' + data.message + '</div>';
			document.getElementById('submit-help').innerHTML = 'Please verify your student number before proceeding';
			// Keep submit button disabled on verification failure
			toggleSubmit();
			// Scroll to verification result
			setTimeout(function() {
				if (resultDiv) {
					resultDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
				}
			}, 100);
		}
	})
	.catch(error => {
		isStudentVerified = false;
                resultDiv.innerHTML = '<div class="verification-error">✗ Error verifying student. Please try again.</div>';
		document.getElementById('submit-help').innerHTML = 'Please verify your student number before proceeding';
		console.error('Error:', error);
		toggleSubmit();
		// Scroll to verification result
		setTimeout(function() {
			if (resultDiv) {
				resultDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
			}
		}, 100);
	})
	.finally(() => {
		// Re-enable button
		verifyBtn.disabled = false;
                verifyBtn.innerHTML = 'Verify';
            });
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
<?php if (isset($err)) { ?>
    <div class="error-message"><?php echo $err; ?></div>
<?php } ?>

    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="../assets/images/Logos/Logo2025.png" alt="UPHSL Logo 2025">
            </div>
            <div class="university-name">University of Perpetual Help System</div>
            <div class="page-title">Student Payment Portal</div>
            <div class="welcome-text">Welcome back, Perpetualites!</div>
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
		</select>
	</div>

            <div id="verification-section" class="verification-section" style="display: none;">
                <h4 style="color: var(--primary-color); margin-bottom: 20px; text-align: center;">🔐 Student Verification</h4>
                <div id="submit-help" class="help-text" style="text-align: center; margin-bottom: 15px;">Please verify your student number before proceeding</div>
                
                <div class="verification-form">
                    <div class="input-group">
                        <label class="form-label" for="studentno">Student Number</label>
                        <input type="text" name="studentno" id="studentno" class="form-input" maxlength="50" placeholder="Enter your student number" oninput="resetVerification()" required>
                    </div>
                    <button type="button" id="verifyBtn" onclick="verifyStudent()" class="verify-btn">
                        🔍 Verify
                    </button>
                </div>
                
                <div id="verification-result" class="verification-result"></div>
	</div>
	
            <div id="submit-section" style="display: none;">
                <button type="submit" id="btnsubmit" name="btnsubmit" class="submit-btn">
                    💳 Proceed to Payment
                </button>
            </div>

            <div class="student-info">
                <h4>📋 Payment Information</h4>
                <p><strong>For Current Students:</strong></p>
                <p>Please select your campus first, then verify your student number to access the payment portal. This ensures that only registered students can make payments for their accounts.</p>
                <p><strong>Note:</strong> Make sure you have your student number ready and select the correct campus where you are enrolled.</p>
	</div>
	</form>	
    </div>
</body>
</html>