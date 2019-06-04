<?php
include('php/logistics_header.php');
include('../../php/includes/connect.php');
include('php/logistics_dash_nav.php');
include('../../php/functions.php');

if (!$_SESSION['log_id']) {
	header("Location: index.php");
}

$alertMessage = '';

$logCompany = $_SESSION['log_company'];

if (isset($_POST['log_register'])) {
	$logComp = validateFormData($_POST['log_company']);
	$logBranch = validateFormData($_POST['log_branch']);
	$logName = validateFormData($_POST['log_name']);
	$logUsername = validateFormData($_POST['log_username']);
	$logPassword = validateFormData($_POST['log_password']);
	$logRole = validateFormData($_POST['log_role']);
	$logProvince = validateFormData($_POST['log_province']);

	$query = "INSERT INTO tbl_logistics (log_company, log_branch, log_name, log_username, log_password, log_role, prov_id) VALUES ('$logComp', '$logBranch', '$logName', '$logUsername', '$logPassword', '$logRole', '$logProvince')";

	$insertUser = mysqli_query($conn, $query);

	checkQueryError($insertUser);

	if ($insertUser) {
		$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
						 	Successfully registered.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";
	}

}

?>

<div class="container-fluid">
	<div class="row">
		<?php include('php/logistics_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Accounts</h1>

				<?php echo $alertMessage; ?>
				
			</div>

			<div class="row">
				<div class="col-sm-8">
					<table class="table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Company</th>
								<th>Branch</th>
								<th>Name</th>
								<th>Role</th>
							</tr>
						</thead>
						<tbody>


							<?php

							$query = "SELECT * FROM tbl_logistics WHERE log_company = '$logCompany'";

							$selectLogistics = mysqli_query($conn, $query);

							checkQueryError($selectLogistics);

							while ($row = mysqli_fetch_assoc($selectLogistics)) {
								$log_id = $row['log_id'];
								$log_company = $row['log_company'];
								$log_branch = $row['log_branch'];
								$log_name = $row['log_name'];
								$log_role = $row['log_role'];
							?>




							<tr>
								<td><?php echo $log_id; ?></td>
								<td><?php echo $log_company; ?></td>
								<td><?php echo $log_branch; ?></td>
								<td><?php echo $log_name; ?></td>
								<td><?php echo $log_role; ?></td>
							</tr>

							<?php

							}


							?>
						</tbody>
					</table>
				</div>
				<div class="col-sm-4">
					<h1>Register</h1>

					<form action="" method="post">
						<div class="form-group">
							<label for="">Company</label>
							<input type="text" class="form-control form-control-sm" value="<?php echo $logCompany; ?>" name="log_company">
						</div>
						<div class="form-group">
							<label for="">Branch</label>
							<input type="text" class="form-control form-control-sm" name="log_branch">
						</div>
						<div class="form-group">
							<label for="">Name</label>
							<input type="text" class="form-control form-control-sm" name="log_name">
						</div>
						<div class="form-group">
							<label for="">Username</label>
							<input type="text" class="form-control form-control-sm" name="log_username">
						</div>
						<div class="form-group">
							<label for="">Password</label>
							<input type="text" class="form-control form-control-sm" name="log_password">
						</div>
						<div class="form-group">
							<label for="">Role</label>
							<select name="log_role" id="" class="form-control form-control-sm">
								<option value="" selected disabled>Select Role</option>
								<option value="warehouse">Warehouse</option>
								<option value="delivery">Delivery</option>
							</select>
						</div>
						<div class="form-group">
							<label for="">Role</label>
							<select class="form-control form-control-sm" name="log_province">
		            			<option value="" selected disabled>Select Province</option>

								<?php

								$query = "SELECT * FROM tbl_province ORDER BY prov_name";
								$selectAllProvince = mysqli_query($conn, $query);

								while ($row = mysqli_fetch_assoc($selectAllProvince)) {
									$provId = $row['prov_id'];
									$provName = $row['prov_name'];

									echo "<option value='$provId'>$provName</option>";
								}

								?>
							</select>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-block" name="log_register">Register</button>
						</div>
					</form>
				</div>
			</div>

			
		</main>
	</div>
</div>


<?php include('php/logistics_footer.php'); ?>