<?php 	
	include "dbconnect.php";

	function mapCampusToTable($campid) {
		$map = [
			"UPHB" => "binan",
			"UPHMU" => "allied",
			"UPHG" => "gma",
			"UPHM" => "manila",
			"PHCP" => "pangasinan"
		];
		return isset($map[$campid]) ? $map[$campid] : null;
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

function tableExists($con, $tableName) {
	if (trim($tableName) === '') { return false; }
	$t = mysqli_real_escape_string($con, $tableName);
	$sql = "SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name='".$t."' LIMIT 1";
	$res = @mysqli_query($con, $sql);
	if ($res && mysqli_fetch_row($res)) { return true; }
	return false;
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

<html>

<head>

<meta charset="utf-8">

<title>UPHSL Online Payment (Perpetualites)</title>
<link rel="icon" type="image/png" href="images/logo.png">
<link rel="shortcut icon" type="image/png" href="images/logo.png">

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
	isStudentVerified = false;
	document.getElementById('verification-result').innerHTML = '';
	document.getElementById('submit-help').innerHTML = 'Please verify your student number before proceeding';
	toggleSubmit();
}

function confirmStudent() {
	isStudentVerified = true;
	document.getElementById('submit-help').innerHTML = '✓ Student confirmed! You may now proceed with payment.';
	toggleSubmit();
}

function rejectStudent() {
	// Reset everything back to initial state
	isStudentVerified = false;
	document.getElementById('studentno').value = '';
	document.getElementById('verification-result').innerHTML = '';
	document.getElementById('submit-help').innerHTML = 'Please verify your student number before proceeding';
	toggleSubmit();
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
	verifyBtn.value = 'Verifying...';
	resultDiv.innerHTML = '<div style="color: #666; font-style: italic;">Verifying student...</div>';
	
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
			resultDiv.innerHTML = '<div style="color: green; font-weight: bold; margin-top: 10px;">✓ ' + data.message + '</div>' +
								  '<div style="color: #333; font-size: 16px; margin-top: 5px;">Student Name: <strong>' + data.name + '</strong></div>' +
								  '<div style="color: #666; font-size: 14px; margin-top: 5px;">Please confirm this is your name before proceeding.</div>' +
								  '<div style="margin-top: 15px;">' +
								  '<button type="button" onclick="confirmStudent()" style="padding: 8px 20px; margin-right: 10px; border-radius: 5px; background-color: #28a745; color: white; border: none; cursor: pointer; font-size: 14px;" onMouseOver="this.style.backgroundColor = \'#218838\';" onMouseOut="this.style.backgroundColor = \'#28a745\';">Confirm</button>' +
								  '<button type="button" onclick="rejectStudent()" style="padding: 8px 20px; border-radius: 5px; background-color: #dc3545; color: white; border: none; cursor: pointer; font-size: 14px;" onMouseOver="this.style.backgroundColor = \'#c82333\';" onMouseOut="this.style.backgroundColor = \'#dc3545\';">No</button>' +
								  '</div>';
			document.getElementById('submit-help').innerHTML = 'Please confirm the student name above to proceed';
			// Keep submit button disabled until user confirms
			toggleSubmit();
		} else {
			isStudentVerified = false;
			resultDiv.innerHTML = '<div style="color: red; font-weight: bold; margin-top: 10px;">✗ ' + data.message + '</div>';
			document.getElementById('submit-help').innerHTML = 'Please verify your student number before proceeding';
			// Keep submit button disabled on verification failure
			toggleSubmit();
		}
	})
	.catch(error => {
		isStudentVerified = false;
		resultDiv.innerHTML = '<div style="color: red; font-weight: bold; margin-top: 10px;">✗ Error verifying student. Please try again.</div>';
		document.getElementById('submit-help').innerHTML = 'Please verify your student number before proceeding';
		console.error('Error:', error);
		toggleSubmit();
	})
	.finally(() => {
		// Re-enable button
		verifyBtn.disabled = false;
		verifyBtn.value = 'Verify';
	});
}
</script>

</head>



<body style="font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif' ">
<?php if (isset($err)) { ?>
<script>
alert(<?php echo json_encode($err); ?>);
</script>
<?php } ?>

	<form method="post">

	<div align="center">

	<h2>University of Perpetual Help System</h2>	

	<h2 style="color: #12199C">ONLINE PAYMENT</h2><br>	

	<h1 style="color: green">Welcome Perpetualites!</h1>	

	<strong>Select the UPHSL Campus to where you will be requesting your documents</strong><br><br>

		<select name="campid" id="campid" style="font-size: 14px; padding: 10px;" onchange="resetVerification()">

			<option value="UPHB">Binan</option>

			<option value="UPHMU">Medical University</option>

			<option value="UPHG">GMA</option>

			<option value="UPHM">Manila</option>

			<option value="PHCP">Pangasinan</option>

		</select>

	</div>

	<div>&nbsp;</div>

	<?php if (isset($err)) { ?>
	<div align="center" style="color:#FFFFFF; background-color:#FF0000; padding:10px; font-weight:bold"><?php echo $err; ?></div>
	<div>&nbsp;</div>
	<?php } ?>

	<div align="center">
		<div id="submit-help" style="color: #666; font-size: 12px; margin-bottom: 10px;">Please verify your student number before proceeding</div>
		<strong>Enter Student Number:</strong>
		<input type="text" value="" maxlength="50" size="20" name="studentno" id="studentno" style="text-align:center; margin-right: 10px;" oninput="resetVerification()" required>
		<input type="button" value="Verify" id="verifyBtn" onclick="verifyStudent()" style="padding: 8px 15px; border-radius: 5px; background-color: #007bff; color: white; border: none; cursor: pointer; font-size: 14px;" onMouseOver="this.style.backgroundColor = '#0056b3';" onMouseOut="this.style.backgroundColor = '#007bff';">
	</div>
	
	<div id="verification-result" align="center" style="margin-top: 15px;"></div>

	<div align="center" style="padding: 20px; display: none;" id="submit-section">
		<input type="submit" value="Submit" id="btnsubmit" name="btnsubmit" style="padding: 10px; border-radius: 5px; background-color:green; color: white; width: 150px; font-size: 14px;" onMouseOver="this.style.backgroundColor = '#ED822F';" onMouseOut="this.style.backgroundColor = '#008000';">
	</div>

	</form>	


</body>

</html>