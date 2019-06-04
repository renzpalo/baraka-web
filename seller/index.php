<?php
include('php/seller_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');

if (isset($_SESSION['seller_name'])) {
	header("Location: seller_dashboard.php");
}

include('php/seller_navbar.php');

date_default_timezone_set("Asia/Manila");

$date = date("Y-m-d");

$alertMessage = '';

$sellerName = '';
$sellerEmail = '';
$sellerMobileNo = '';
$sellerPassword = '';

$confirmPassword = '';

if (isset($_POST['register'])) {
	$sellerName = validateFormData($_POST['seller_name']);
	$sellerEmail = validateFormData($_POST['seller_email']);
	$sellerMobileNo = validateFormData($_POST['seller_mobileno']);
	$sellerPassword = validateFormData($_POST['seller_password']);

	$confirmPassword = validateFormData($_POST['seller_confirm_password']);

	$sellerMobileNo = "09" . $sellerMobileNo;

	if (!empty($sellerName) && !empty($sellerEmail) && !empty($sellerMobileNo) && !empty($sellerPassword) && !empty($confirmPassword)) {
		if ($sellerPassword !== $confirmPassword) {
			$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
							 	Password and Confirm Password does not match
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";
		} else {

			$doesNotExist = true;

			$query = "SELECT seller_id FROM tbl_sellers WHERE seller_email = '$sellerEmail'";

			$selectUserId = mysqli_query($conn, $query);

			if (!$selectUserId) {
				die("Query failed: " . mysqli_error($conn));
			}

			while ($row = mysqli_fetch_array($selectUserId)) {
				$doesNotExist = false;
			}

			if ($doesNotExist) {
				$query2 = "SELECT seller_id FROM tbl_sellers ORDER BY seller_seq_no DESC LIMIT 1";

				$selectUserId2 = mysqli_query($conn, $query2);

				if (!$selectUserId2) {
					die("Query failed: " . mysqli_error($conn));
				}

				$userId = 1000000;

				while ($row = mysqli_fetch_array($selectUserId2)) {
					$userId = $row['seller_id'];
				}

				$userId = $userId + 1;

				$sellerPassword = password_hash($sellerPassword, PASSWORD_DEFAULT);

				$query3 = "INSERT INTO tbl_sellers (seller_id, seller_name, seller_email, seller_mobileno, seller_password, date_created) VALUES ('$userId', '$sellerName', '$sellerEmail', '$sellerMobileNo', '$sellerPassword', '$date')";

				$insertData = mysqli_query($conn, $query3);

				if (!$insertData) {
					die("Query failed: " . mysqli_error($conn));
				}

				header("Location: seller_signin.php");

			} else {
				$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
								 	Email already exist.
								 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								 		<span aria-hidden='true'>&times;</span>
								 	</button>
								 </div>";
			}

			mysqli_close($conn);

		}
	}
}


?>

<title>Sign Up - Seller</title>

<div class="container">

	<div class="row">
		<div class="col-sm-6 offset-3">
			<?php echo $alertMessage; ?>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-10 offset-1 signup-form-box rounded mt-5 shadow-lg mb-5 bg-white rounded border">
			<div class="row">
				<div class="col-sm-6 signup-form-box-img seller-signup-form-box-img">

				</div>
				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-8 offset-2 pt-5 mt-3">
							<h1 class="text-center">Register as a Seller</h1>

							<form action="" method="post">
								<div class="form-group">
									<label for="">Name/Store Name</label>
									<input type="text" autofocus required class="form-control form-control-sm input-store-name" placeholder="" name="seller_name" value="<?php echo $sellerName; ?>">
								</div>
								<div class="form-group">
									<label for="">Email</label>
									<input type="email" required class="form-control form-control-sm input-email" placeholder="" name="seller_email" value="<?php echo $sellerEmail; ?>">
								</div>
								<div class="form-group">
									<label for="seller_mobileno">Mobile Number</label>
									<div class="input-group input-group-sm">
										<div class="input-group-prepend">
											<div class="input-group-text">+ 639 </div>
										</div>
										<input type="text" required class="form-control form-control-sm input-mobile" placeholder="" name="seller_mobileno" value="<?php echo $sellerMobileNo ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="">Password</label>
									<input type="password" required class="form-control form-control-sm input-password" placeholder="" name="seller_password">
								</div>
								<div class="form-group">
									<label for="">Confirm Password</label>
									<input type="password" required class="form-control form-control-sm input-password" placeholder="" name="seller_confirm_password">
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-block" name="register">Register</button>
								</div>
								
							</form>
						</div>
					</div>
				
				</div>
			</div>

		</div>
	</div>
</div>



<?php include('php/seller_footer.php'); ?>