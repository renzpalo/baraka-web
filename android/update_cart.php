<?php

include('connect.php');
include('../php/functions.php');

$securityCode = validateFormData($_POST['security_code']);
$productId = validateFormData($_POST['prod_id']);
$productQuantity = validateFormData($_POST['prod_quantity']);
$userId = validateFormData($_POST['user_id']);

// $securityCode = "123456";
// $productId = "1000000009";
// $productQuantity = 1;
// $userId = "1000016";

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");

$totalPrice = 0;

global $connect;

if (isset($securityCode) && !empty($securityCode) && isset($productId) && !empty($productId) && isset($userId) && !empty($userId) && isset($productQuantity) && !empty($productQuantity)) {

	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "Failed to update product from cart.";
	$information =  "Failed to update product from cart.";

	$prodArray = array();

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

		$doesNotExist = false;
	}

	if ($doesNotExist) {
		$status = 0;
		$message = "User does not exist.";
		$information =  "User does not exist.";

	} else {
		// Restructure the array index.
		$query4 = "UPDATE tbl_cart SET cart_prod_quantity = '$productQuantity' WHERE user_id = '$user_id' AND prod_id = '$prod_id'";

		$deleteCart = mysqli_query($connect, $query4);

		checkQueryError($deleteCart);

		if ($deleteCart) {
			$status = 1;
			$message = "Successfully updated product from cart.";
			$information = "Successfully updated product from cart.";
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
	$message = "Failed to update product from cart.";
	$information = "Failed to update product from cart.";
}

?>