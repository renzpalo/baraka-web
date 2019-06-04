<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

if (!$_SESSION['user_id']) {
	header("Location: index.php");
}

if (isset($_SESSION['user_id'])) {
	$userId = $_SESSION['user_id'];
	$userFullname = $_SESSION['user_fullname'];
}

$alertMessage = "";

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");


if (isset($_POST['place_order'])) {
	$adId = validateFormData($_POST['ad_id']);
	$orderPayment = validateFormData($_POST['payment_type']);

	$orderShipFee = $_SESSION['shipping_fee'];
	$orderTotal = $_SESSION['total_price'];

	$orderStatus = "Pending";

	// IF SESSION IS NOT NULL.. EXECUTE...

	$query = "INSERT INTO tbl_orders (order_total, order_ship_fee, order_payment, order_status, order_date_created, order_date_updated, user_id, ad_id) 
				VALUES
					 ('$orderTotal', '$orderShipFee', '$orderPayment', '$orderStatus', '$date', '$date', '$userId', '$adId')";

	$insertOrder = mysqli_query($conn, $query);

	$orderId = mysqli_insert_id($conn);

	checkQueryError($insertOrder);

	if ($insertOrder) {

		$query2 = "SELECT cart.*, product.seller_id FROM tbl_cart cart, tbl_products product WHERE cart.prod_id = product.prod_id AND cart.user_id = '$userId'";

		// $query2 = "SELECT * FROM tbl_cart WHERE user_id = '$userId'";

		$selectCart = mysqli_query($conn, $query2);

		$numRows = mysqli_num_rows($selectCart);

		checkQueryError($selectCart);

		if ($numRows > 0) {
			while ($row = mysqli_fetch_array($selectCart)) {
				$cart_id = $row['cart_id'];
				$cart_prod_price = $row['cart_prod_price'];
				$cart_prod_quantity = $row['cart_prod_quantity'];

				$prod_id = $row['prod_id'];
				$user_id = $row['user_id'];
				$seller_id = $row['seller_id'];

				$query3 = "INSERT INTO tbl_order_products (order_prod_price, order_prod_quantity, prod_id, order_id, seller_id) VALUES ('$cart_prod_price', '$cart_prod_quantity', '$prod_id', '$orderId', '$seller_id')";

				$insertOrderProducts = mysqli_query($conn, $query3);

				checkQueryError($insertOrderProducts);

				// Update Product Stats
				$query6 = "UPDATE tbl_product_stats SET stats_orders = stats_orders + 1 WHERE prod_id = '$prod_id'";

				$updateStats = mysqli_query($conn, $query6);

				checkQueryError($updateStats);

				// Update Product Quantity
				$query7 = "UPDATE tbl_products SET prod_stock = prod_stock - '$cart_prod_quantity' WHERE prod_id = '$prod_id'";

				$updateQuantity = mysqli_query($conn, $query7);

				checkQueryError($updateQuantity);
			}
		}

		// mysqli_muliple_query

		if ($insertOrderProducts) {

			$orderStatus = "Placed";
            $orderUpdateInfo = "Your order has been placed.";

            $query5 = "INSERT INTO tbl_order_updates (order_update_status, order_update_info, order_update_by, order_update_date, order_id) VALUES ('$orderStatus', '$orderUpdateInfo', '$userFullname', '$date', '$orderId')";

            $insertUpdateStatus = mysqli_query($conn, $query5);

            checkQueryError($insertUpdateStatus);

			// Clear Order Session

			// Delete User cart from TBL_CART
			$query4 = "DELETE FROM tbl_cart WHERE user_id = '$userId'";

			$deleteCart = mysqli_query($conn, $query4);

			checkQueryError($deleteCart);

			if ($deleteCart) {
				// session_unset($_SESSION['total_price']);
				// session_unset($_SESSION['shipping_fee']);
				$_SESSION['total_price'] = null;
				$_SESSION['shipping_fee'] = null;

				header("Location: order_success.php?o_id=$orderId");

				$_SESSION['order_success'] = true;
			}
		}

		
	}
}

// if (isset($_SESSION['order_success'])) {
// 	header("Location: order_success.php?o_id=$orderId");
// }


?>



<title>Payment</title>
	
<div class="container main-page">

			<form action="" method="post">

				<div class="row mt-5 mb-5 border p-5 bg-white shadow-lg rounded">
					<div class="col-sm-4 text-center">
						<h1>Payment Option</h1>
						<div class="custom-control custom-radio">
			                <input id="cod" name="payment_type" type="radio" class="custom-control-input" value="Cash on Delivery" checked required>
			                <label class="custom-control-label" for="cod">Cash on Delivery</label>
			            </div>
					</div>
					<div class="col-sm-8">
						<h1>Select Shipping and Billing Address</h1>

			            <table class="table">
							<thead>
								<tr>
									<th></th>
									<th>Fullname</th>
									<th>Contact</th>
									<th>Address</th>
									<th>Notes</th>
								</tr>
							</thead>
							<tbody>
								<?php

								$hasAddress = false;

								$query2 = "SELECT * FROM tbl_user_address WHERE user_id = '$userId'";

								$selectAddress = mysqli_query($conn, $query2);

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

									$hasAddress = true;

									$address = $ad_street . ", " . $ad_barangay . ", " . $ad_city_muni . ", " . $ad_province . ", " . $ad_zipcode;

									echo "	<tr>
												<td>
													<div class='custom-control custom-radio'>
										                <input id='$ad_id' name='ad_id' type='radio' class='custom-control-input' value='$ad_id' checked required>
										                <label class='custom-control-label' for='$ad_id'><p></p></label>
										            </div>
												</td>
												<td>$ad_fulname</td>
												<td>$ad_contact</td>
												<td>$address</td>
												<td>$ad_notes</td>
												
											</tr>";
								}



								?>

								
								
							</tbody>
						</table>

						<div class="row">
							<div class="col-sm-6">
								<a href="add_address.php" target="_blank" class="btn btn-outline-primary">Add Address</a>
							</div>
							<div class="col-sm-6">
								<?php

								if ($hasAddress) {
									echo "<button type='submit' class='btn btn-primary btn-block' name='place_order'>Place Order</button>";
								} else {
									echo "<button type='submit' class='btn btn-primary btn-block' name='place_order' disabled>Place Order</button>";
								}


								?>
								
							</div>
						</div>
						
					</div>
				</div>

				

	            

			</form>

</div>	








<?php include('php/includes/footer.php'); ?>