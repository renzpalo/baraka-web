<?php
include('php/includes/header.php');
include('php/includes/connect.php');

include('php/functions.php');

if (isset($_SESSION['user_fullname'])) {
	header("Location: index.php");
}

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');



date_default_timezone_set("Asia/Manila");

$date = date("Y-m-d");

global $conn;

$userAddress = '';

$alertMessage = '';

$userFullname = '';
$userEmail = '';
$userPhoneNo = '';
$userPassword = '';

$confirmPassword = '';

if (isset($_POST['signup'])) {
	$userFullname = validateFormData($_POST['user_fullname']);
	$userEmail = validateFormData($_POST['user_email']);
	$userPhoneNo = validateFormData($_POST['user_phoneno']);
	$userPassword = validateFormData($_POST['user_password']);



	$userPhoneNo = "09" . $userPhoneNo;

	$confirmPassword = validateFormData($_POST['confirm_password']);

	if (!empty($userFullname) && !empty($userEmail) && !empty($userPhoneNo) && !empty($userPassword) && !empty($confirmPassword)) {

		if ($userPassword !== $confirmPassword) {
			$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
							 	Password and Confirm Password does not match
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";
		} else {

			// Check if User is already exists.
			$doesNotExist = true;
			
			// Get the lastest user_id.
			// LIMIT 1 - give the last row result
			$stmt1 = $conn -> prepare("SELECT user_id FROM tbl_user_profile WHERE user_email = ?");
			$stmt1 -> bind_param("s", $userEmail);
			$stmt1 -> execute();
			$stmt1 -> store_result();

			// Define initial value = 10000
			$userId = 1000000;

			while ($stmt1 -> fetch()) {
				$doesNotExist = false;
			}

			if ($doesNotExist) {
				// Get the lastest user_id.
				// LIMIT 1 - give the last row result
				$stmt = $conn -> prepare("SELECT user_id FROM tbl_user_profile ORDER BY  user_seq_no DESC LIMIT 1");

				$stmt -> execute();
				$stmt -> store_result();
				$stmt -> bind_result($col1);

				// Define initial value = 10000
				$userId = 1000000;

				while ($stmt -> fetch()) {
					$userId = $col1;
				}

				$userId = $userId + 1;

				$stmt2 = $conn -> prepare("INSERT INTO tbl_user_profile (user_fullname, user_address, user_email, user_phone_no, user_id, user_date_created) VALUES (? , ? , ? , ?, ?, ?)");
				$stmt2 -> bind_param("ssssis", $userFullname, $userAddress, $userEmail, $userPhoneNo, $userId, $date);
				$stmt2 -> execute();

				// Insert data to tbl_user_cred
				if (!empty($stmt2 -> insert_id)) {
					$userPassword = password_hash($userPassword, PASSWORD_DEFAULT);

					$stmt3 = $conn -> prepare("INSERT INTO tbl_user_cred (user_id, user_email, user_phone_no, user_password) VALUES (?, ?, ?, ?)");

					$stmt3 -> bind_param("isss", $userId, $userEmail, $userPhoneNo, $userPassword);
					$stmt3 -> execute();

					$userId = $userId;

					header('Location: signin.php');

					// echo "Username and Password is " .$stmt3 -> insert_id;
				} else {
					echo "Failed";
				}


				mysqli_close($conn);


			} else {
				$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
								 	Email already exist.
								 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								 		<span aria-hidden='true'>&times;</span>
								 	</button>
								 </div>";
			}

		}




	} else {
		$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
						 	Inputs are empty.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";
	}
}


?>

<title>Sign Up</title>


<div class="container main-page">

	<div class="row">
		<div class="col-sm-6 offset-3">
			<?php echo $alertMessage; ?>
		</div>
	</div>

	<div class="rows signup-page">
		<div class="col-sm-10 offset-1 signup-form-box rounded mt-5 shadow-lg mb-5 bg-white rounded border">
			<div class="row">
				<div class="col-sm-6 signup-form-box-img customer-signup-form-box-img">

				</div>
				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-8 offset-2 pt-5 mt-3">
							<h1>Sign Up</h1>

							<form action="" method="post">
								<div class="form-group">
									<label for="user_fullname">Full Name *</label>
									<input type="text" required autofocus class="form-control form-control-sm input-fullname" placeholder="Juan Dela Cruz" name="user_fullname" value="<?php echo $userFullname ?>">
								</div>
								<div class="form-group">
									<label for="user_email">Email *</label>
									<input type="email" required class="form-control form-control-sm input-email" placeholder="juandelacruz@email.com" name="user_email" value="<?php echo $userEmail ?>">
								</div>
								<!-- <div class="form-group">
									<label for="user_mobilenum">Phone Number</label>
									<input type="tel" class="form-control form-control-sm" placeholder="+63 998 765 4321" name="user_phoneno" value="" maxlength="13" >
								</div> -->
								<div class="form-group">
									<label for="user_mobilenum">Phone Number</label>
									<div class="input-group input-group-sm">
										<div class="input-group-prepend">
											<div class="input-group-text">+ 639 </div>
										</div>
										<input type="text" class="form-control form-control-sm input-mobile" placeholder="98 765 4321" name="user_phoneno" value="<?php echo $userPhoneNo ?>">
									</div>
								</div>
								
								<div class="form-group">
									<label for="user_password">Password *</label>
									<input type="password" minlength="6" required class="form-control form-control-sm input-password" placeholder="******" name="user_password" value="<?php echo $userPassword ?>">
								</div>
								<div class="form-group">
									<label for="confirm_password">Confirm Password *</label>
									<input type="password" minlength="6" required class="form-control form-control-sm input-password" placeholder="******" name="confirm_password" value="<?php echo $confirmPassword ?>">
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-block" name="signup">Sign Up</button>
								</div>

								<p class="text-center">Already Registered? <a href="signin.php">Sign In</a></p>
							</form>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

</div>
	




	











<?php include('php/includes/footer.php'); ?>