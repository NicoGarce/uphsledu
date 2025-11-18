<?php 
include "dbconnect.php";
$s = "";
if ($_POST["status"]=="S") {$s="Success";} 
if ($_POST["status"]=="F") {$s="Failure";} 
if ($_POST["status"]=="P") {$s="Pending";} 
if ($_POST["status"]=="U") {$s="Unknown";} 
if ($_POST["status"]=="R") {$s="Refund";} 
if ($_POST["status"]=="K") {$s="Chargeback";} 
if ($_POST["status"]=="V") {$s="Void";} 
if ($_POST["status"]=="A") {$s="Authorized";}

//$m = $_POST["param2"]." ||| ".$_POST["param1"];
//$m = $_POST["param2"];

// Use prepared statement to prevent SQL injection
$stmt = mysqli_prepare($con, "UPDATE return_data SET status=?, message=? WHERE refno=?");
if ($stmt) {
    $refno = $_POST["refno"] ?? '';
    $message = $_POST["param2"] ?? '';
    mysqli_stmt_bind_param($stmt, "sss", $s, $message, $refno);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} else {
    $result = false;
}

?>
