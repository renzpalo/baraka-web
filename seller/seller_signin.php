<?php
include('php/seller_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');

if (isset($_SESSION['seller_name'])) {
	header("Location: seller_dashboard.php");
}

$isSignInPage = true;

include('php/seller_navbar.php');

$sellerEmail = '';
$sellerPassword = '';

$getSellerId = '';
$getSellerName = '';
$getSellerEmail = '';
$getSellerPhoneNo = '';
$getSellerPassword = '';
$getSellerInfo = '';

$alertMessage = '';

if (isset($_POST['signin'])) {
	$sellerEmail = validateFormData($_POST['signin_email']);
	$sellerPassword = validateFormData($_POST['signin_password']);

	$query = "SELECT * FROM tbl_sellers WHERE seller_email = '$sellerEmail'";

	$selectSellerEmail = mysqli_query($conn, $query);

	if (!$selectSellerEmail) {
		die("Query failed: " . mysqli_error($conn));
	}

	while ($row = mysqli_fetch_array($selectSellerEmail)) {
		$getSellerId = $row['seller_id'];
		$getSellerName = $row['seller_name'];
		$getSellerEmail = $row['seller_email'];
		$getSellerPhoneNo = $row['seller_mobileno'];
		$getSellerPassword = $row['seller_password'];
		$getSellerInfo = $row['seller_info'];
	}

	if (!empty($sellerEmail) && !empty($sellerPassword)) {
		if ($sellerEmail === $getSellerEmail) {
			if (password_verify($sellerPassword, $getSellerPassword) || $sellerPassword === $getSellerPassword) {
				$_SESSION['seller_id'] = $getSellerId;
				$_SESSION['seller_name'] = $getSellerName;
				$_SESSION['seller_email'] = $getSellerEmail;
				$_SESSION['seller_mobileno'] = $getSellerPhoneNo;

				header("Location: seller_dashboard.php");
			}
		} else {
			$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
							 	Invalid credentials
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";
		}
	} else {
		$alertMessage = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
						 	Email and Password is required.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";
	}
}

?>

<title>Sign In</title>

<div class="container">

	<div class="row">
		<div class="col-sm-6 offset-3">
			<?php echo $alertMessage; ?>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-10 offset-1 signin-form-box rounded mt-5 shadow-lg mb-5 bg-white border">
			<div class="row">
				<div class="col-sm-6 signin-form-box-img seller-signin-form-box-img">

				</div>
				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-8 offset-2 pt-5 mt-3">
							<h1 class="text-center">Sign In</h1>

							<form action="" method="post">
								<div class="form-group">
									<label for="">Email</label>
									<input type="email" required class="form-control form-control-sm input-email" placeholder="" name="signin_email" value="<?php echo $sellerEmail; ?>">
								</div>
								<div class="form-group">
									<label for="">Password</label>
									<input type="password" required class="form-control form-control-sm input-password" placeholder="" name="signin_password">
								</div>
								<p class="text-center"><a href="forgot_password.php">Forgot Password?</a></p>
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-block" name="signin">Sign In</button>
								</div>

								<p class="text-center"><a href="index.php">Register</a> as a seller.</p>
								
							</form>
						</div>
					</div>
				
				</div>
			</div>

		</div>
	</div>
</div>



<?php include('php/seller_footer.php');