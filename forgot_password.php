<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");

$alertMessage = "";

if (isset($_POST['send_email'])) {
	$email = validateFormData($_POST['forgot_email']);

	// $hash = hash('salt', $hello.$world);
	$random = rand(0, 100);
	$hash = sha1(md5($random . $email));

	// the message
	$message = 'Change Password \n ';

	$link = "http://barakaph.com/change_password.php?token=" . $hash;

	$headers = "From: " . "baraka";

	

	$forgotExist = false;
	$emailExist = false;

	// Select if email exist.
	$query4 = "SELECT user_email FROM tbl_user_cred WHERE user_email = '$email'";
	$selectEmail = mysqli_query($conn, $query4);
	checkQueryError($selectEmail);

	while ($row = mysqli_fetch_assoc($selectEmail)) {
		$emailExist = true;
	}

	if ($emailExist) {
		$query = "SELECT * FROM tbl_forgot_password WHERE forgot_email = '$email' AND forgot_status = 'pending'";
		$selectForgot = mysqli_query($conn, $query);
		checkQueryError($selectForgot);

		while ($row = mysqli_fetch_assoc($selectForgot)) {
			$forgotExist = true;
		}

		if ($email) {
			// Update all emails to expire
			$query2 = "UPDATE tbl_forgot_password SET forgot_status = 'expired' WHERE forgot_email = '$email'";
			$updateForgot = mysqli_query($conn, $query2);
			checkQueryError($updateForgot);

			if ($updateForgot) {
				$query3 = "INSERT INTO tbl_forgot_password (forgot_email, forgot_token, forgot_status, forgot_date) VALUES ('$email', '$hash', 'pending', '$date')";
				$insertForgot = mysqli_query($conn ,$query3);
				checkQueryError($insertForgot);

				if ($insertForgot) {
					// send email
					mail($email, "Forgot Password", $message . $link, $headers);

					$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
									 	Success. A link to change your password was sent to $email.
									 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
									 		<span aria-hidden='true'>&times;</span>
									 	</button>
									 </div>";
				}
			}
		}
	} else {
		$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
						 	Email does not exist.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";
	}

	


}






?>

<title>baraka</title>


<div class="container-fluid">
	<div class="row mt-5 mb-5 p-5">
		<div class="col-sm-4 offset-4">
			<?php echo $alertMessage; ?>
			<h1 class="text-center">Forgot Password</h1>
			<form action="" method="post">
				<div class="form-group">
					<label for="">Email</label>
					<input type="text" class="form-control" name="forgot_email">
				</div>
				<br>
				<div class="form-group">
					<button class="btn btn-primary btn-block" name="send_email">Send Email</button>
				</div>
			</form>
		</div>
		
			
	</div>
</div>

	










<?php 


include('php/includes/footer.php');


mysqli_close($conn);

 ?>