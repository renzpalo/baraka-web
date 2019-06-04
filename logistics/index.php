<?php 
include('php/logistics_header.php'); 
include('php/logistics_navbar.php');

include('../php/includes/connect.php');
include('../php/functions.php');

if (isset($_POST['log_signin'])) {
	$username = validateFormData($_POST['log_username']);
	$password = validateFormData($_POST['log_password']);

	$query = "SELECT * FROM tbl_logistics WHERE log_username = '$username'";

	$selectLogistics = mysqli_query($conn, $query);

	checkQueryError($selectLogistics);

	while ($row = mysqli_fetch_assoc($selectLogistics)) {
		$log_id = $row['log_id'];
		$log_company = $row['log_company'];
		$log_name = $row['log_name'];
		$log_username = $row['log_username'];
		$log_password = $row['log_password'];
		$log_role = $row['log_role'];

		$prov_id = $row['prov_id'];
	}

	if ($username === $log_username && $password === $log_password) {
		$_SESSION['log_id'] = $log_id;
		$_SESSION['log_company'] = $log_company;
		$_SESSION['log_name'] = $log_name;
		$_SESSION['log_username'] = $log_username;
		$_SESSION['log_role'] = $log_role;
		$_SESSION['prov_id'] = $prov_id;

		if ($log_role == 'admin') {
			header("Location: admin/index.php");
		} else if ($log_role == 'warehouse') {
			header("Location: warehouse/new_shipments.php");
		} else if ($log_role == 'delivery') {
			header("Location: delivery/new_shipments.php");
		}
	} else {
		echo "Invalid credentials";
	}
}


?>


<div class="container">
	<div class="row">
		<div class="col-sm-10 offset-1 signin-form-box rounded mt-5 shadow-lg mb-5 bg-white rounded border">
			<div class="row">
				<div class="col-sm-6 signin-form-box-img logistics-signin-form-box-img">

				</div>
				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-8 offset-2 pt-5 mt-3">
							<h1 class="text-center">Sign In</h1>

							<form action="" method="post">
								<div class="form-group">
									<label for="">Username</label>
									<input type="text" required class="form-control form-control-sm" placeholder="" name="log_username" value="<?php //echo $sellerEmail; ?>">
								</div>
								<div class="form-group">
									<label for="">Password</label>
									<input type="password" required class="form-control form-control-sm" placeholder="" name="log_password">
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-block" name="log_signin">Sign In</button>
								</div>
								
							</form>
						</div>
					</div>
				
				</div>
			</div>

		</div>
	</div>
</div>

<?php include('php/logistics_footer.php'); ?>