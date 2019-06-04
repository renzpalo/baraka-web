<?php
include('php/logistics_header.php');
include('../../php/includes/connect.php');
include('php/logistics_dash_nav.php');
include('../../php/functions.php');

if (!$_SESSION['log_id']) {
	header("Location: index.php");
}

$logId = $_SESSION['log_id'];
$logCompany = $_SESSION['log_company'];
$logName = $_SESSION['log_name'];

$alertMessage = '';


// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");

$getOrderId = $_GET['o_id'];

?>

<div class="container-fluid">
	<div class="row">
		<?php include('php/logistics_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Order Details</h1>

				
				
			</div>

			<div class="row">
				<div class="col-sm-8">
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
								$prod_image = $row['prod_image'];

								echo "	<tr>
											<td style='width: 20%;'><img src='../../images/products/$prod_image' alt='$prod_image' class='img-fluid'></td>
											<td>$prod_name</td>
											<td>$order_prod_price</td>
											<td>$order_prod_quantity</td>
										</tr>";

							}

							?>

							
						</tbody>
					</table>
				</div>

				<div class="col-sm-4">
					<?php

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
					<h3>Order Details</h3>

					<p><b>Total: </b><?php echo $order_total; ?></p>
					<p><b>Shipping Fee: </b><?php echo $order_ship_fee; ?></p>
					<p><b>Payment: </b><?php echo $order_payment; ?></p>
					<p><b>Status: </b><?php echo $order_status; ?></p>
					<p><b>Date: </b><?php echo $order_date_created; ?></p>

					<p><b>Fullname: </b><?php echo $ad_fullname; ?></p>
					<p><b>Contact: </b><?php echo $ad_contact; ?></p>
					<p><b>Address: </b><?php echo $address; ?></p>
				</div>
			</div>

			

			
			
		</main>
	</div>
</div>


<?php include('php/logistics_footer.php'); ?>