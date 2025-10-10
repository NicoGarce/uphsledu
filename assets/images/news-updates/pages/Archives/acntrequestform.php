<?php
 if (isset($_POST["btnsubmit"])) {

	$to = "ferolino.arnold@uphsl.edu.ph";
	$subject = "Test Email from Nod";
	$message = "Hello, this is a test email sent using PHP!";
	$headers = "From: ferolno.arnold@uphsl.edu.ph";
	
	// Send email
	if (mail($to, $subject, $message, $headers)) {
		echo "Email sent successfully!";die;
	} else {
		echo "Failed to send email.";die;
	}
     header("Location: acntrequestform.php");exit();
 }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UPHSL Website Account Request Form</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
<div class="container-md">
<div>&nbsp;</div>
<div>&nbsp;</div>
<div class="display-6" align="center" >ACCOUNT REQUEST</div>
<form method="post">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Name</label>
    <input type="text" class="form-control" id="name" name="name" aria-describedby="" required>

  </div>
    <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Company</label>
    <input type="text" class="form-control" id="company" name="company" aria-describedby="" required>

  </div>
    <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email</label>
    <input type="text" class="form-control" id="email" name="email" aria-describedby="" required>

  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Reason to Access</label>
    <textarea name="reason" rows="3" cols="100" required></textarea>

  </div>

  <button type="submit" class="btn btn-primary" name="btnsubmit">Submit</button>
</form>
</div>  

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
