<?php 
include "dbconnect.php";
$s = "";
$status = $_GET["status"] ?? "";
if ($status=="S") {$s="Success";} 
if ($status=="F") {$s="Failure";} 
if ($status=="P") {$s="Pending";} 
if ($status=="U") {$s="Unknown";} 
if ($status=="R") {$s="Refund";} 
if ($status=="K") {$s="Chargeback";} 
if ($status=="V") {$s="Void";} 
if ($status=="A") {$s="Authorized";}

// Use prepared statements to prevent SQL injection
$txnid = $_GET["txnid"] ?? "";
$refno = $_GET["refno"] ?? "";
$param1 = $_GET["param1"] ?? "0";
$param2 = $_GET["param2"] ?? "";

// Validate and sanitize amount
$amount = is_numeric($param1) ? floatval($param1) : 0;

$stmt = mysqli_prepare($con, "UPDATE return_data SET transdate=now(), refno=?, status=?, amount=?, message=? WHERE txnid=?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssdss", $refno, $s, $amount, $param2, $txnid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

	

 //https://uphsl.edu.ph/online_payment/retback.php?txnid=20-1234-567&refno=PAMKFB89&status=P&message=%5b000%5d+Waiting+for+deposit+to+Bayad+Center+%23PAMKFB89&digest=3ed203d83f3da862e3e9c229193b1aac86c5ea39

//txnid=20-1234-567
//refno=PAMKFB89
//status=P
//message=%5b000%5d+Waiting+for+deposit+to+Bayad+Center+%23PAMKFB89&digest=3ed203d83f3da862e3e9c229193b1aac86c5ea39


?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>UPHSL Online Payment</title>
</head>

<body style="font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'">
<div><img src="images/header.png" width="100%"></div>	
	<table width="100%" border="0" cellspacing="10" cellpadding="5" align="center">
  <tbody>    
    <tr>
      <td colspan="2" align="center"><u><strong>Payment Details and Status</strong></u></td>
    </tr>
    <tr>
      <td colspan="2" align="center">Thank you for your payment request. Please visit the email address you have provided on your posting<br>
        so that you can see or view instruction details pertaining to your transaction.</td>
    </tr>
    <tr>
      <td width="50%" align="right">Transaction No :.</td>
      <td>&nbsp;<strong><?php echo htmlspecialchars($txnid, ENT_QUOTES, 'UTF-8'); ?></strong></td>
    </tr>
    <tr>
      <td align="right">Reference No. :</td>
      <td>&nbsp;<strong><?php echo htmlspecialchars($refno, ENT_QUOTES, 'UTF-8'); ?></strong></td>
    </tr>
    <tr>
      <td align="right">Status :</td>
      <td>&nbsp;<strong><?php echo htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); ?></strong></td>
    </tr>
    <tr>
      <td align="right">Amount :</td>
      <td>&nbsp;<strong><?php echo htmlspecialchars($param1, ENT_QUOTES, 'UTF-8'); ?></strong></td>
    </tr>
    <tr>
      <td align="right">Message :</td>
      <td>&nbsp;<strong><?php echo htmlspecialchars($param2, ENT_QUOTES, 'UTF-8'); ?></strong></td>
    </tr>
    <tr>
      <td colspan="2"><hr></td>
    </tr>
  </tbody>
</table>

	
	
</body>
</html>