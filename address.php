<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

$alertMessage = "";

if (!$_SESSION['user_id']) {
	header("Location: index.php");
}

if (isset($_SESSION['user_id'])) {
	$userId = $_SESSION['user_id'];
}


?>

<title>Address</title>
	
<div class="container main-page">

	<div class="row mt-5 bg-white rounded shadow-lg pb-5 pt-5">
		<div class="col-sm-3 mt-5">
			<?php include('php/includes/user_sidebar.php'); ?>
		</div>
 
		<div class="col-sm-9">
			<h1>Add Address</h1>

			<a href="add_address.php" class="btn btn-primary mb-3">Add Address</a>

			<table class="table">
				<thead>
					<tr>
						<th>Fullname</th>
						<th>Contact</th>
						<th>Address</th>
						<th>Notes</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php

					$query = "SELECT * FROM tbl_user_address WHERE user_id = '$userId'";

					$selectAddress = mysqli_query($conn, $query);

					checkQueryError($selectAddress);

					while ($row = mysqli_fetch_assoc($selectAddress)) {
						$ad_id = $row['ad_id'];
						$ad_fulname = $row['ad_fullname'];
						$ad_contact = $row['ad_contact'];
						$ad_street = $row['ad_street'];
						$ad_barangay = $row['ad_barangay'];
						$ad_city_muni = $row['ad_city_muni'];
						$ad_province = $row['ad_province'];
						$ad_zipcode = $row['ad_zipcode'];
						$ad_notes = $row['ad_notes'];


						$user_id = $row['user_id'];

						$address = $ad_street . ", " . $ad_barangay . ", " . $ad_city_muni . ", " . $ad_province . ", " . $ad_zipcode;

						echo "	<tr>
									<td>$ad_fulname</td>
									<td>$ad_contact</td>
									<td>$address</td>
									<td>$ad_notes</td>
									<td>
										<a href='cart.php?del_cart=<?php  ?>'' class='btn btn-danger'><i class='fas fa-trash'></i></a>
									</td>
								</tr>";
					}



					?>
					
				</tbody>
			</table>

			
		</div>
	</div>

</div>	








<?php include('php/includes/footer.php'); ?>