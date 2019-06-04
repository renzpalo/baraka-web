<?php

include('connect.php');
include('../php/functions.php');

// Get product JSON Array from tbl_Cart.
// User For Loop and Get Product Details from tbl_products.

$securityCode = validateFormData($_POST['security_code']);
$userId = validateFormData($_POST['user_id']);
$orderId = validateFormData($_POST['order_id']);

// $securityCode = "123456";
// $userId = "1000001";
// $orderId = "1000000005";

$doesNotExits = true;

$productQuantity = "";

$totalPrice = 0;
$shippingFee = 0;
$orderTotal = 0;


global $connect;

global $prodImageUrl;

if (isset($securityCode) && !empty($securityCode) && isset($userId) && !empty($userId) && isset($orderId) && !empty($orderId)) {
	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "Order is empty.";
	
	$productArray = array();

	$information = array(
						'prod_details' => $productArray,
						'subtotal' => $totalPrice,
						'shipping_fee' => $shippingFee,
						'order_total' => $orderTotal
						);

	$count = 0;

	$query = "SELECT * FROM tbl_orders WHERE user_id = '$userId' AND order_id = '$orderId'";

	$selectCart = mysqli_query($connect, $query);

	$numRows = mysqli_num_rows($selectCart);

	checkQueryError($selectCart);

	while ($row = mysqli_fetch_array($selectCart)) {
		$order_id = $row['order_id'];
		$user_id = $row['user_id'];

		$doesNotExits = false;
	}

	if ($doesNotExits) {
		$status = 1;
		$message = "Order is empty.";
		$information = array(
							'prod_details' => $productArray,
							'subtotal' => $totalPrice,
							'shipping_fee' => $shippingFee,
							'order_total' => $orderTotal
							);
	} else {
		$query2 = "SELECT order_products.*, product.* FROM tbl_order_products order_products, tbl_products product WHERE order_products.order_id = '$orderId' AND order_products.prod_id = product.prod_id";

		$selectProducts = mysqli_query($connect, $query2);

		checkQueryError2($selectProducts);

		while ($row2 = mysqli_fetch_assoc($selectProducts)) {
			$order_prod_price = $row2['order_prod_price'];
			$order_prod_quantity = $row2['order_prod_quantity'];

			$prod_id = $row2['prod_id'];
			$prod_name = $row2['prod_name'];
			$prod_image = $row2['prod_image'];

			$totalPrice = $totalPrice + ($order_prod_price * $order_prod_quantity);

			$productArray[$count] = array(
									'prod_id' => $prod_id,
									'prod_name' => $prod_name,
									'prod_price' => $order_prod_price,
									'prod_image' => $prodImageUrl . $prod_image,
									'prod_quantity' => $order_prod_quantity
									);

			$count = $count + 1;
		}

		if ($selectProducts) {
			$query3 = "SELECT * FROM tbl_orders WHERE order_id = '$orderId'";

			$selectOrder = mysqli_query($connect, $query3);

			checkQueryError2($selectOrder);

			while ($row3 = mysqli_fetch_assoc($selectOrder)) {
				$order_total = $row3['order_total'];
				$order_ship_fee = $row3['order_ship_fee'];
			}

			$status = 1;
			$message = "Displaying products.";
			$information = array(
							'prod_details' => $productArray,
							'subtotal' => $totalPrice,
							'shipping_fee' => $order_ship_fee,
							'order_total' => $order_total
							);
		}

		

	}

	$postData = array(
					'status' => $status,
					'message' => $message,
					'information' => $information);

	$postData = json_encode($postData);

	echo $postData;

	mysqli_close($connect);
} else {
	$status = 0;
	$message = "No products found.";
	$information = array();
}



?>