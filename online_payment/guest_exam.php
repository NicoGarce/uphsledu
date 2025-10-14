<?php 	
session_start();
$_SESSION['isin'] = 'iamin';

include "dbconnect.php";
include "campus_fees.php";

if (isset($_POST["btnsubmit"])) {
    date_default_timezone_set("Asia/Manila");
    $transid = $_POST["campid"] ."_". date("HismdY");
    header("Location: payment_exam.php?payee=&transid=$transid");
    die;
}
?>	

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>UPHSL Online Payment - Entrance Exam</title>
<link rel="icon" type="image/png" href="images/logo.png">
<link rel="shortcut icon" type="image/png" href="images/logo.png">
<script>
<?php echo getCourseAmountsJS(); ?>

function updateFees() {
    var campusSelect = document.getElementById('campid');
    var selectedCampus = campusSelect.value;
    var feesDiv = document.getElementById('fees-display');
    var submitSection = document.getElementById('submit-section');
    
    if (selectedCampus && courseAmounts[selectedCampus]) {
        var fees = courseAmounts[selectedCampus];
        feesDiv.innerHTML = '<h3 style="color: #333; margin-top: 0;">Entrance Exam Fees for ' + campusSelect.options[campusSelect.selectedIndex].text + ':</h3>' +
                           '<div style="text-align: left; margin: 10px 0;">' +
                           '<strong>Baccalaureate:</strong> ₱' + fees['Baccalaureate'].toFixed(2) + '<br>' +
                           '<strong>Graduate School:</strong> ₱' + fees['Graduate School'].toFixed(2) + '<br>' +
                           '<strong>Law/Juris Doctor:</strong> ₱' + fees['Law/Juris Doctor'].toFixed(2) + '<br>' +
                           '<strong>Basic Education:</strong> ₱' + fees['Basic Education'].toFixed(2) +
                           '</div>';
        feesDiv.style.display = 'block';
        submitSection.style.display = 'block';
    } else {
        feesDiv.style.display = 'none';
        submitSection.style.display = 'none';
    }
}
</script>
</head>

<body style="font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif' ">
	<form method="post">
	<div align="center">
	<h2>University of Perpetual Help System</h2>	
	<h2 style="color: #12199C">ONLINE PAYMENT - ENTRANCE EXAM</h2><br>	
	<h1 style="color: green">Welcome Perpetualites!</h1>	
	<strong>Select the UPHSL Campus where you will be taking the entrance exam</strong><br><br>
		<select name="campid" id="campid" style="font-size: 14px; padding: 10px;" onchange="updateFees()">
			<option value="">Select Campus</option>
			<option value="UPHB">Binan</option>
			<option value="UPHMU">Medical University</option>
			<option value="UPHG">GMA</option>
			<option value="UPHM">Manila</option>
			<option value="PHCP">Pangasinan</option>
		</select>
	</div>
	<div>&nbsp;</div>
	
	<div align="center" style="padding: 20px;">
		<div id="fees-display" style="background-color: #f0f8ff; padding: 15px; border-radius: 5px; margin: 20px 0; max-width: 600px; display: none;">
			<!-- Fees will be populated by JavaScript when campus is selected -->
		</div>
	</div>
	
	<div align="center" style="padding: 20px; display: none;" id="submit-section">
		<input type="submit" value="Proceed to Payment" name="btnsubmit" style="padding: 10px; border-radius: 5px; background-color:green; color: white; width: 200px; font-size: 14px;" onMouseOver="this.style.backgroundColor = '#ED822F';" onMouseOut="this.style.backgroundColor = '#008000';">
	</div>
	</form>	
</body>
</html>