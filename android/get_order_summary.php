<?php

include('connect.php');
include('../php/functions.php');

// Get product JSON Array from tbl_Cart.
// User For Loop and Get Product Details from tbl_products.

$securityCode = validateFormData($_POST['security_code']);
$userId = validateFormData($_POST['user_id']);

// $securityCode = "123456";
// $userId = "1000001";

$doesNotExits = true;

$productQuantity = "";

$totalPrice = 0;
$shippingFee = 0;
$orderTotal = 0;


global $connect;

global $prodImageUrl;

if (isset($securityCode) && !empty($securityCode) && isset($userId) && !empty($userId)) {
	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "Cart is empty.";
	
	$productArray = array();

	$information = array(
						'prod_details' => $productArray,
						'subtotal' => $totalPrice,
						'shipping_fee' => $shippingFee,
						'order_total' => $orderTotal
						);

	$count = 0;

	$query = "SELECT * FROM tbl_cart WHERE user_id = '$userId'";

	$selectCart = mysqli_query($connect, $query);

	$numRows = mysqli_num_rows($selectCart);

	checkQueryError($selectCart);

	while ($row = mysqli_fetch_array($selectCart)) {
		$user_id = $row['user_id'];

		$doesNotExits = false;
	}

	if ($doesNotExits) {
		$status = 1;
		$message = "Cart is empty.";
		$information = array(
							'prod_details' => $productArray,
							'subtotal' => $totalPrice,
							'shipping_fee' => $shippingFee,
							'order_total' => $orderTotal
							);
	} else {
		$query2 = "SELECT cart.*, products.* FROM tbl_cart cart, tbl_products products WHERE cart.prod_id = products.prod_id AND cart.user_id = '$user_id'";

		$selectCartProducts = mysqli_query($connect, $query2);

		checkQueryError2($selectCartProducts);

		while ($row2 = mysqli_fetch_assoc($selectCartProducts)) {
			$cart_id = $row2['cart_id'];
			$cart_prod_quantity = $row2['cart_prod_quantity'];
			$cart_prod_price = $row2['cart_prod_price'];

			$prod_id = $row2['prod_id'];
			$prod_name = $row2['prod_name'];
			$prod_price = $row2['prod_price'];
			$prod_image = $row2['prod_image'];

			$totalPrice = $totalPrice + ($cart_prod_price * $cart_prod_quantity);

			$productArray[$count] = array(
									'prod_id' => $prod_id,
									'prod_name' => $prod_name,
									'prod_price' => $prod_price,
									'prod_image' => $prodImageUrl . $prod_image,
									'prod_quantity' => $cart_prod_quantity
								);

			$count = $count + 1;
		}

		$query3 = "SELECT cart.*, MAX(products.prod_ship_fee) AS high_shipping_fee FROM tbl_cart cart, tbl_products products WHERE cart.prod_id = products.prod_id AND cart.user_id = '$user_id'";

		$selectShipFee = mysqli_query($connect, $query3);

		checkQueryError($selectShipFee);

		while ($row3 = mysqli_fetch_assoc($selectShipFee)) {
			$shippingFee = $row3['high_shipping_fee'];	
		}

		$status = 1;
		$message = "Displaying products.";
		$information = "Displaying products.";

	}

	$orderTotal = $totalPrice + $shippingFee;

	$information = array(
							'prod_details' => $productArray,
							'subtotal' => $totalPrice,
							'shipping_fee' => $shippingFee,
							'order_total' => $orderTotal
							);

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