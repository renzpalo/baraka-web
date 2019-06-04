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

<title>Orders</title>
	
<div class="container main-page">

	<div class="row mt-5 bg-white rounded shadow-lg pb-5 pt-5">
		<div class="col-sm-3 mt-5">
			<?php include('php/includes/user_sidebar.php'); ?>
		</div>
 
		<div class="col-sm-9">
			<h1>Orders</h1>

			<table class="table">
				<thead>
					<tr>
						<th>Order ID</th>
						<th>Order Total</th>
						<th>Payment Type</th>
						<th>Status</th>
						<th>Date</th>
						<th></th>
					</tr>
				</thead>
				<tbody>

				

			<?php

			$query = "SELECT orders.*, address.* FROM tbl_orders orders, tbl_user_address address WHERE orders.ad_id = address.ad_id AND orders.user_id = '$userId' ORDER BY orders.order_date_created ASC";

			$selectOrders = mysqli_query($conn, $query);

			$numRows = mysqli_num_rows($selectOrders);

			checkQueryError($selectOrders);

			while ($row = mysqli_fetch_array($selectOrders)) {

				$order_id = $row['order_id'];
				$order_total = $row['order_total'];
				$order_payment = $row['order_payment'];
				$order_status = $row['order_status'];
				$date_created = $row['order_date_created'];

				
				echo "	<tr>
							<td>$order_id</td>
							<td>PHP $order_total</td>
							<td>$order_payment</td>
							<td>$order_status</td>
							<td>$date_created</td>
							<td><a href='order_details.php?o_id=$order_id'>View Details</a></td>
						</tr>";
				

				
			}



			?>

				</tbody>
			</table>



			
		</div>
	</div>

</div>	








<?php include('php/includes/footer.php'); ?>