<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

if (!$_SESSION['user_id']) {
	header("Location: index.php");
}

$userId = $_SESSION['user_id'];

$alertMessage = "";

if (isset($_POST['update_email'])) {
	$email = validateFormData($_POST['email']);

	$query = "UPDATE tbl_user_profile profile, tbl_user_cred cred SET profile.user_email = '$email', cred.user_email = '$email' WHERE profile.user_id = cred.user_id AND profile.user_id = '$userId' AND cred.user_id = '$userId'";

	$updateEmail = mysqli_query($conn, $query);

	checkQueryError($updateEmail);

	if ($updateEmail) {
		$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
						 	Successfully updated email address.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";
	} else {
		$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
						 	Failed to update email.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";
	}
}

if (isset($_POST['update_password'])) {
	$currentPassword = validateFormData($_POST['current_password']);
	$newPassword = validateFormData($_POST['new_password']);
	$retypePassword = validateFormData($_POST['retype_password']);

	$query = "SELECT user_password FROM tbl_user_cred WHERE user_id = '$userId'";
	$selectPassword = mysqli_query($conn, $query);
	checkQueryError($selectPassword);
	$row = mysqli_fetch_assoc($selectPassword);

	$password = $row['user_password'];

	if ($currentPassword === $password) {
		if ($newPassword === $retypePassword) {
			$query2 = "UPDATE tbl_user_cred SET user_password = '$newPassword' WHERE user_id = '$userId'";
			$updatePassword = mysqli_query($conn, $query2);
			checkQueryError($updatePassword);

			if ($updatePassword) {
				$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
								 	Successfully updated.
								 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								 		<span aria-hidden='true'>&times;</span>
								 	</button>
								 </div>";
			}
		} else {
			$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
							 	Retype Password does not match.
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";
		}
	} else {
		$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
						 	Current Password does not match.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";
	}

	
}

?>

<title>Account</title>

<div class="container main-page">

	<div class="row mt-5 bg-white rounded shadow-lg pb-5 pt-5">
		<div class="col-sm-3 mt-5 border-right">
			<?php include('php/includes/user_sidebar.php'); ?>
		</div>
 
		<div class="col-sm-9">
			<h1>Account</h1>

			<?php echo $alertMessage; ?>

			<?php
				$query = "SELECT * FROM tbl_user_profile WHERE user_id = '$userId'";

				$selectUser = mysqli_query($conn, $query);

				checkQueryError($selectUser);

				while ($row = mysqli_fetch_assoc($selectUser)) {
					$user_fullname = $row['user_fullname'];
					$user_email = $row['user_email'];
					$user_phone_no = $row['user_phone_no'];

					// echo $user_fullname;
					// echo $user_email;
					// echo $user_phone_no;
				}
			?>

			<div class="row">
				<div class="col-sm-5 offset-1">
					<form action="" method="post">
						<div class="form-group">
							<label for="">Email</label>
							<input type="email" class="form-control" name="email" required value="<?php echo $user_email; ?>">
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-block" name="update_email">Update Email</button>
						</div>
					</form>

					
				</div>
				<div class="col-sm-5">
					<form action="" method="post">
						<div class="form-group">
							<label for="">Current Password</label>
							<input type="password" class="form-control" name="current_password" required minlength="6">
						</div>
						<br>
						<div class="form-group">
							<label for="">New Password</label>
							<input type="password" class="form-control" name="new_password" required minlength="6">
						</div>
						<div class="form-group">
							<label for="">Re-type New Password</label>
							<input type="password" class="form-control" name="retype_password" required minlength="6">
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-block" name="update_password">Update Password</button>
						</div>
					</form>
				</div>
			</div>

			
		</div>
	</div>

</div>	








<?php include('php/includes/footer.php'); ?>