<?php
include('php/includes/header.php');

include('php/includes/connect.php');

include('php/functions.php');

if (isset($_SESSION['user_fullname'])) {
	header("Location: index.php");
}

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');





$alertMessage = '';

$userId = '';
$userEmail = '';
$userMobileNo = '';
$userPassword = '';

$getUserId = '';
$getUserFullname = '';
$getUserEmail = '';
$getUserMobileNo = '';
$getUserAddress = '';

$email = '';
$password = '';

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
		if ($email === $userEmail) {
			if (password_verify($password, $userPassword) || $password === $userPassword) {
				$_SESSION['user_id'] = $getUserId;
				$_SESSION['user_fullname'] = $getUserFullname;
				$_SESSION['user_email'] = $getUserEmail;
				$_SESSION['user_mobileno'] = $getUserMobileNo;
				$_SESSION['user_address'] = $getUserAddress;

				header("Location: index.php");
			} else {
				$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
							 	Invalid credentials. Hash.
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";
			}
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

<title>Sign In</title>


<div class="container main-page">

	<div class="row">
		<div class="col-sm-6 offset-3">
			<?php echo $alertMessage; ?>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-10 offset-1 signin-form-box rounded mt-5 shadow-lg mb-5 bg-white rounded border">
			<div class="row">
				<div class="col-sm-6 signin-form-box-img customer-signin-form-box-img">

				</div>
				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-8 offset-2 pt-5 mt-3">
							<h1>Sign In</h1>

							<form action="" method="post">
								<div class="form-group">
									<label for="signin_email">Email</label>
									<input type="email" required autofocus class="form-control form-control-sm input-email" placeholder="juandelacruz@email.com" name="signin_email" value="<?php echo $email; ?>">
								</div>
								<div class="form-group">
									<label for="signin_password">Password</label>
									<input type="password" required class="form-control form-control-sm input-password" placeholder="******" name="signin_password" minlength="6">
								</div>
								<p class="text-center"><a href="forgot_password.php">Forgot Password?</a></p>
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-block" name="signin">Sign In</button>
								</div>
								<p class="text-center">Not Registered? <a href="signup.php">Sign Up</a></p>
							</form>
						</div>
					</div>
				
				</div>
			</div>

		</div>
	</div>
</div>




	











<?php include('php/includes/footer.php'); ?>