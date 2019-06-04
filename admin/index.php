<?php
include('php/admin_header.php');
include('../php/includes/connect.php');
include('php/admin_navbar.php');
include('../php/functions.php');

// if ($_SESSION['admin_name']) {
// 		header("Location: admin_dashboard.php");
// }

$adminEmail = '';
$adminPassword = '';

$getAdminId = '';
$getAdminName = '';
$getAdminEmail = '';
$getAdminPassword = '';

$alertMessage = '';

if (isset($_POST['signin'])) {
	$adminEmail = validateFormData($_POST['admin_email']);
	$adminPassword = validateFormData($_POST['admin_password']);

	$query = "SELECT * FROM tbl_admin WHERE admin_email = '$adminEmail'";

	$selectAdminEmail = mysqli_query($conn, $query);

	if (!$selectAdminEmail) {
		die("Query failed: " . mysqli_error($conn));
	}

	while ($row = mysqli_fetch_array($selectAdminEmail)) {
		$getAdminId = $row['admin_id'];
		$getAdminName = $row['admin_name'];
		$getAdminEmail = $row['admin_email'];
		$getAdminPassword = $row['admin_password'];
	}

	if (!empty($adminEmail) && !empty($adminPassword)) {
		if ($adminEmail === $getAdminEmail && $adminPassword === $getAdminPassword) {
			$_SESSION['admin_id'] = $getAdminId;
			$_SESSION['admin_name'] = $getAdminName;
			$_SESSION['admin_email'] = $getAdminEmail;
			$_SESSION['admin_password'] = $getAdminPassword;

			header("Location: admin_dashboard.php");
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

<title>baraka - Administrator</title>

<div class="container">

	<h1 class="text-center mt-5">Sign In as Administrator</h1>

	<div class="row">
		<div class="col-sm-4 offset-4 mt-5">
			
			<?php echo $alertMessage; ?>

			<form action="" method="post">
				<div class="form-group">
					<label for="signin_email">Username</label>
					<input type="text" required class="form-control form-control-sm" name="admin_email" value="<?php echo $adminEmail; ?>">
				</div>
				<div class="form-group">
					<label for="signin_password">Password</label>
					<input type="password" required class="form-control form-control-sm" name="admin_password">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block" name="signin">Sign In</button>
				</div>
			</form>
		</div>
	</div>
</div>


<?php include('php/admin_footer.php'); ?>