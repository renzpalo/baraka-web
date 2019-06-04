<?php

include('connect.php');
include('../php/functions.php');

$securityCode = validateFormData($_POST['security_code']);
$productId = validateFormData($_POST['prod_id']);
$productPrice = validateFormData($_POST['prod_price']);
$productQuantity = validateFormData($_POST['prod_quantity']);
$userId = validateFormData($_POST['user_id']);

// $securityCode = "123456";
// $productId = "1000000009";
// $productPrice = 99;
// $productQuantity = 1;
// $userId = "1000016";

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");

$cart_prod_quantity = 0;

global $connect;

if (isset($securityCode) && !empty($securityCode) && isset($productId) && !empty($productId) && isset($productPrice) && !empty($productPrice) && isset($productQuantity) && !empty($productQuantity) && isset($userId) && !empty($userId)) {

	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "Failed to add into cart.";
	$information = "Failed to add into cart.";

	$cartCount = 0;

	// Check if User exist on table
	// Get User ID amd update table JSON Array.

	$doesNotExist = true;

	$query = "SELECT * FROM tbl_cart WHERE user_id = '$userId' AND prod_id = '$productId'";

	$selectUser = mysqli_query($connect, $query);

	checkQueryError($selectUser);

	while ($row = mysqli_fetch_assoc($selectUser)) {
		$cart_id = $row['cart_id'];
		$prod_id = $row['prod_id'];
		$user_id = $row['user_id'];
		$cart_prod_quantity = $row['cart_prod_quantity'];

		$doesNotExist = false;
	}

	if ($doesNotExist) {
		// Insert
		$query2 = "INSERT INTO tbl_cart (cart_prod_price, cart_prod_quantity, cart_date, prod_id, user_id) VALUES ('$productPrice', '$productQuantity', '$date', '$productId', '$userId')";

		$addProductToCart = mysqli_query($connect, $query2);

		checkQueryError2($addProductToCart);

		if ($addProductToCart) {
			$query4 = "SELECT * FROM tbl_cart WHERE user_id = '$userId'";

			$getNumRows = mysqli_query($connect, $query4);

			checkQueryError($getNumRows);

			$numRows = mysqli_num_rows($getNumRows);

			$status = 1;
			$message = "Inserted. Successfully added to cart.";
			$information = $numRows;

			
		}

	} else {
		$cart_prod_quantity = $cart_prod_quantity + 1;

		$query3 = "UPDATE tbl_cart SET cart_prod_quantity = '$cart_prod_quantity' WHERE user_id = '$user_id' AND prod_id = '$productId'";

		$updateProductId = mysqli_query($connect, $query3);

		checkQueryError($updateProductId);

		if ($updateProductId) {
			$query4 = "SELECT * FROM tbl_cart WHERE user_id = '$user_id'";

			$getNumRows = mysqli_query($connect, $query4);

			checkQueryError($getNumRows);

			$numRows = mysqli_num_rows($getNumRows);

			$status = 1;
			$message = "Inserted. Successfully added to cart.";
			$information = $numRows;
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
	$message = "Failed to add into cart...";
	$information = array();
}

?>