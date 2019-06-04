<?php
include('includes/connect.php');
include('functions.php');

if (isset($_POST['signin'])) {
	$email = validateFormData($_POST['signin_email']);
	$password = validateFormData($_POST['signin_password']);

	// $email = mysqli_real_escape_string($conn, $email);
	// $password = mysqli_real_escape_string($conn, $password);

	$query = "SELECT * FROM tbl_user_cred WHERE user_email = '$email'";

	$selectUserCred = mysqli_query($conn, $query);

	if (!$selectUserCred) {
		die("Query failed" .mysqli_error($conn));
	}

	while ($row = mysqli_fetch_array($selectUserCred)) {
		$userId = $row['user_id'];
		$userEmail = $row['user_email'];
		$userMobileNo = $row['user_phone_no'];
		$userPassword = $row['user_password'];
	}

	$query2 = "SELECT * FROM tbl_user_profile WHERE user_email = '$email'";

	$selectUserProf = mysqli_query($conn, $query2);

	if (!$selectUserProf) {
		die("Query failed" .mysqli_error($conn));
	}

	while ($row2 = mysqli_fetch_array($selectUserProf)) {
		$getUserId = $row2['user_id'];
		$getUserFullname = $row2['user_fullname'];
		$getUserEmail = $row2['user_email'];
		$getUserMobileNo = $row2['user_phone_no'];
		$getUserAddress = $row2['user_address'];
	}


	if (empty($email) && empty($password)) {
		$alertMessage = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
						 	Email and Password is required.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";

	} else {
		if ($email === $userEmail && $password === $userPassword) {

			$_SESSION['user_id'] = $getUserId;
			$_SESSION['user_fullname'] = $getUserFullname;
			$_SESSION['user_email'] = $getUserEmail;
			$_SESSION['user_mobileno'] = $getUserMobileNo;
			$_SESSION['user_address'] = $getUserAddress;

			header("Location: ../index.php");
		} else {
			$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
							 	Invalid credentials
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";
		}
		
	}
}

?>