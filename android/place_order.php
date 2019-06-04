<?php

include('connect.php');
include('../php/functions.php');

global $connect;

$securitycode = validateFormData($_POST['security_code']);

$orderTotal = validateFormData($_POST['order_total']);
$orderShipFee = validateFormData($_POST['order_ship_fee']);
$orderPayment = validateFormData($_POST['order_payment']);
$orderStatus = validateFormData($_POST['order_status']);
$userId = validateFormData($_POST['user_id']);
$adId = validateFormData($_POST['ad_id']);

// $securitycode = "1234";

// $orderTotal = "99.99";
// $orderPayment = "Cash on Delivery";
// $orderStatus = "Pending";
// $userId = "1000001";
// $adId = "1000000000";

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");

$status = 0;
$message = "Transaction failed. Please try again.";
$information = array(
					'status' => "",
					'order_id' => ""
					);

$insertOrderProducts = "";

$prod_id = array();

if (isset($securitycode) && !empty($securitycode) && !empty($orderTotal) && !empty($orderShipFee) && !empty($orderPayment) && !empty($orderStatus) && !empty($userId) && !empty($adId) ) {

	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$query = "INSERT INTO tbl_orders (order_total, order_ship_fee, order_payment, order_status, order_date_created, order_date_updated, user_id, ad_id) 
				VALUES
					 ('$orderTotal', '$orderShipFee', '$orderPayment', '$orderStatus', '$date', '$date', '$userId', '$adId')";

	$insertOrder = mysqli_query($connect, $query);

	$orderId = mysqli_insert_id($connect);

	checkQueryError2($insertOrder);

	if ($insertOrder) {

		$query2 = "SELECT cart.*, product.seller_id FROM tbl_cart cart, tbl_products product WHERE cart.prod_id = product.prod_id AND cart.user_id = '$userId'";

		// $query2 = "SELECT * FROM tbl_cart WHERE user_id = '$userId'";

		$selectCart = mysqli_query($connect, $query2);

		$numRows = mysqli_num_rows($selectCart);

		checkQueryError2($selectCart);

		if ($numRows > 0) {
			while ($row = mysqli_fetch_array($selectCart)) {
				$cart_id = $row['cart_id'];
				$cart_prod_price = $row['cart_prod_price'];
				$cart_prod_quantity = $row['cart_prod_quantity'];

				$prod_id = $row['prod_id'];
				$user_id = $row['user_id'];
				$seller_id = $row['seller_id'];

				$query3 = "INSERT INTO tbl_order_products (order_prod_price, order_prod_quantity, prod_id, order_id, seller_id) VALUES ('$cart_prod_price', '$cart_prod_quantity', '$prod_id', '$orderId', '$seller_id')";

				$insertOrderProducts = mysqli_query($connect, $query3);

				checkQueryError2($insertOrderProducts);

				// Update Product Stats
				$query6 = "UPDATE tbl_product_stats SET stats_orders = stats_orders + 1 WHERE prod_id = '$prod_id'";

				$updateStats = mysqli_query($connect, $query6);

				checkQueryError2($updateStats);

				// Update Product Quantity
				$query7 = "UPDATE tbl_products SET prod_stock = prod_stock - '$cart_prod_quantity' WHERE prod_id = '$prod_id'";

				$updateQuantity = mysqli_query($connect, $query7);

				checkQueryError($updateQuantity);
			}
		}

		// mysqli_muliple_query

		if ($insertOrderProducts) {

			// Delete User cart from TBL_CART
			$query4 = "DELETE FROM tbl_cart WHERE user_id = '$userId'";

			$deleteCart = mysqli_query($connect, $query4);

			checkQueryError2($deleteCart);

			$orderStatus = "Placed";
            $orderUpdateInfo = "Your order has been placed.";

            $query5 = "INSERT INTO tbl_order_updates (order_update_status, order_update_info, order_update_by, order_update_date, order_id) VALUES ('$orderStatus', '$orderUpdateInfo', '$userId', '$date', '$orderId')";

            $insertUpdateStatus = mysqli_query($connect, $query5);

            checkQueryError($insertUpdateStatus);

			if ($deleteCart) {
				$status = 1;
				$message = "Order placed successfully.";
				$information = array(
							'status' => "Order placed successfully.",
							'order_id' => $orderId
							);
			} else {
				$status = 0;
				$message = "Please try again.";
				$information = array(
									'status' => "",
									'order_id' => ""
									);
			}
		}

		
	}

	$postData = array(
					'status' => $status,
					'message' => $message,
					'information' => $information
					);

	$postData = json_encode($postData);

	echo $postData;

	mysqli_close($connect);

} else {
	$status = 0;
	$message = "Transaction failed. Please try again.";
	$information = array(
						'status' => "",
						'order_id' => ""
						);
}


?>