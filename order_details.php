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

if (isset($_GET['o_id'])) {
	$getOrderId = $_GET['o_id'];
}

if (isset($_GET['status'])) {
	$status = $_GET['status'];

	if ($status == 'failed') {
		$alertMessage = "<div class='alert alert-secondary alert-dismissible fade show' role='alert'>
                           	Cannot cancel order right now.
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                              <span aria-hidden='true'>&times;</span>
                            </button>
                           </div>";
	} elseif ($status == 'success') {
		$alertMessage = "<div class='alert alert-secondary alert-dismissible fade show' role='alert'>
                           	Order cancelled.
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                              <span aria-hidden='true'>&times;</span>
                            </button>
                           </div>";
	}
}



$totalPrice = 0;

?>

<title>Order Details</title>
	
<div class="container main-page">



	<div class="row mt-5 bg-white">

		<div class="col-sm-6">
				<?php echo $alertMessage; ?>
				
			<h1>Orders Details</h1>

			<table class="table">
				<thead>
					<tr>
						<th>Image</th>
						<th>Product</th>
						<th>Price</th>
						<th>Quantity</th>
					</tr>
				</thead>
				<tbody>
				

			<?php

			$query = "SELECT order_products.*, product.* FROM tbl_order_products order_products, tbl_products product WHERE order_products.order_id = '$getOrderId' AND order_products.prod_id = product.prod_id";

			$selectProducts = mysqli_query($conn, $query);

			checkQueryError($selectProducts);

			while ($row = mysqli_fetch_assoc($selectProducts)) {
				$order_prod_price = $row['order_prod_price'];
				$order_prod_quantity = $row['order_prod_quantity'];

				$prod_id = $row['prod_id'];
				$prod_name = $row['prod_name'];
				
				$query2 = "SELECT prod_image FROM tbl_product_images WHERE prod_id = '$prod_id' ORDER BY prod_image_sequence DESC";

				$selectImages = mysqli_query($conn, $query2);

				$row2 = mysqli_fetch_assoc($selectImages);

				$prod_image = $row2['prod_image'];

				echo "	<tr>
							<td style='width: 20%;'><img src='images/products/$prod_image' alt='$prod_image' class='img-fluid'></td>
							<td>$prod_name</td>
							<td>$order_prod_price</td>
							<td>$order_prod_quantity</td>
						</tr>";

			}

			$query2 = "SELECT orders.*, user_address.* FROM tbl_orders orders, tbl_user_address user_address WHERE user_address.ad_id = orders.ad_id AND orders.order_id = $getOrderId";

			$selectOrder = mysqli_query($conn, $query2);

			checkQueryError($selectOrder);

			while ($row2 = mysqli_fetch_assoc($selectOrder)) {
				$order_total = $row2['order_total'];
				$order_ship_fee = $row2['order_ship_fee'];
				$order_payment = $row2['order_payment'];
				$order_status = $row2['order_status'];
				$order_date_created = $row2['order_date_created'];

				$ad_fullname = $row2['ad_fullname'];
				$ad_contact = $row2['ad_contact'];
				$ad_street = $row2['ad_street'];
				$ad_barangay = $row2['ad_barangay'];
				$ad_city_muni = $row2['ad_city_muni'];
				$ad_province = $row2['ad_province'];
				$ad_zipcode = $row2['ad_zipcode'];
				$ad_notes = $row2['ad_notes'];

				$address = $ad_street . ", " . $ad_barangay . ", " . $ad_city_muni . ", " . $ad_province . ", " . $ad_zipcode;
				
			}

			?>

				</tbody>
			</table>

			<p><b>Total: </b><?php echo $order_total; ?></p>
			<p><b>Shipping Fee: </b><?php echo $order_ship_fee; ?></p>
			<p><b>Payment: </b><?php echo $order_payment; ?></p>
			<p><b>Status: </b><?php echo $order_status; ?></p>
			<p><b>Date: </b><?php echo $order_date_created; ?></p>

			<p><b>Fullname: </b><?php echo $ad_fullname; ?></p>
			<p><b>Contact: </b><?php echo $ad_contact; ?></p>
			<p><b>Address: </b><?php echo $address; ?></p>
			<p><b>Notes: </b><?php echo $ad_notes; ?></p>

			<a href="cancel_order.php?o_id=<?php echo $getOrderId; ?>" class="btn btn-outline-secondary">Cancel Order</a>
			
		</div>

		<div class="col-sm-6">
			<h1>Order Status</h1>

			<table class="table">
				<thead>
					<tr>
						<th>Status</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					<?php

					$query3 = "SELECT * FROM tbl_order_updates WHERE order_id = '$getOrderId' ORDER BY order_update_date DESC";

					$orderUpdates = mysqli_query($conn, $query3);

					checkQueryError($orderUpdates);

					while ($row3 = mysqli_fetch_assoc($orderUpdates)) {
						$order_update_id = $row3['order_update_id'];
						$order_update_status = $row3['order_update_status'];
						$order_update_info = $row3['order_update_info'];
						$order_update_date = $row3['order_update_date'];

				




					?>
					<tr>
						<td><?php echo $order_update_info; ?></td>
						<td><?php echo $order_update_date; ?></td>
					</tr>
					<?php


					}

					?>
				</tbody>
			</table>
		</div>
	</div>

</div>	








<?php include('php/includes/footer.php'); ?>