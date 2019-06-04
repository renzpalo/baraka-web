<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

$forgot_id = '';
$forgot_email = '';
$forgot_token = '';
$forgot_status = '';

$alertMessage = "";

if (isset($_GET['token'])) {
	$token = $_GET['token'];

	if (isset($_POST['change_password'])) {
		$newPassword = validateFormData($_POST['new_password']);
		$confirmPassword = validateFormData($_POST['confirm_password']);

		$query = "SELECT * FROM tbl_forgot_password WHERE forgot_token = '$token'";
		$selectForgot = mysqli_query($conn, $query);
		checkQueryError($selectForgot);

		while ($row = mysqli_fetch_assoc($selectForgot)) {
			$forgot_id = $row['forgot_id'];
			$forgot_email = $row['forgot_email'];
			$forgot_token = $row['forgot_token'];
			$forgot_status = $row['forgot_status'];
		}

		if ($newPassword === $confirmPassword) {
			if ($forgot_token === $token) {
				if ($forgot_status == "pending") {
					// Security?
					$query2 = "UPDATE tbl_user_cred SET user_password = '$newPassword' WHERE user_email = '$forgot_email'";
					$changePassword = mysqli_query($conn, $query2);
					checkQueryError($changePassword);

					if ($changePassword) {
						// Password cant be matched with old password.

						// Update Forgot Status to Changed.
						$query3 = "UPDATE tbl_forgot_password SET forgot_status = 'changed' WHERE forgot_token = '$forgot_token'";
						$updateForgot = mysqli_query($conn, $query3);
						checkQueryError($updateForgot);

						if ($updateForgot) {
							$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
											 	Password successfully changed. <a href='signin.php'>Sign In</a>
											 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											 		<span aria-hidden='true'>&times;</span>
											 	</button>
											 </div>";
						}

						
						
					}

				} elseif ($forgot_status == "expired") {
					$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
									 	Request has been expired. Please file a new one.
									 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
									 		<span aria-hidden='true'>&times;</span>
									 	</button>
									 </div>";
				} elseif ($forgot_status == "changed") {
					$alertMessage = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
									 	Password already changed.
									 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
									 		<span aria-hidden='true'>&times;</span>
									 	</button>
									 </div>";
				}
			}
		} else {
			$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
							 	New Password and Confirm Password does not match.
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";
		}
	}
}

?>

<title>baraka</title>



<div class="container-fluid">
	<div class="row mt-5 mb-5 p-5">
		<div class="col-sm-4 offset-4">
			<?php echo $alertMessage; ?>
			<h1 class="text-center">Change Password</h1>
			<form action="" method="post">
				<div class="form-group">
					<label for="">New Password</label>
					<input type="password" class="form-control" name="new_password">
				</div>
				<div class="form-group">
					<label for="">Confirm Password</label>
					<input type="password" class="form-control" name="confirm_password">
				</div>
				<br>
				<div class="form-group">
					<button class="btn btn-primary btn-block" name="change_password">Change Password</button>
				</div>
			</form>
		</div>
		
			
	</div>
</div>

	










<?php 


include('php/includes/footer.php');


mysqli_close($conn);

 ?>