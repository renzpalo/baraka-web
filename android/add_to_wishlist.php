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

$wish_prod_quantity = 0;

global $connect;

if (isset($securityCode) && !empty($securityCode) && isset($productId) && !empty($productId) && isset($productPrice) && !empty($productPrice) && isset($productQuantity) && !empty($productQuantity) && isset($userId) && !empty($userId)) {

	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "Failed to add into wishlist.";
	$information = "Failed to add into wishlist.";

	$cartCount = 0;

	// Check if User exist on table
	// Get User ID amd update table JSON Array.

	$doesNotExist = true;

	$query = "SELECT * FROM tbl_wishlist WHERE user_id = '$userId' AND prod_id = '$productId'";

	$selectUser = mysqli_query($connect, $query);

	checkQueryError($selectUser);

	while ($row = mysqli_fetch_assoc($selectUser)) {
		$wish_id = $row['wish_id'];
		$prod_id = $row['prod_id'];
		$user_id = $row['user_id'];
		$wish_prod_quantity = $row['wish_prod_quantity'];

		$doesNotExist = false;
	}

	if ($doesNotExist) {
		// Insert
		$query2 = "INSERT INTO tbl_wishlist (wish_prod_price, wish_prod_quantity, wish_date, prod_id, user_id) VALUES ('$productPrice', '$productQuantity', '$date', '$productId', '$userId')";

		$addProductToWishlist = mysqli_query($connect, $query2);

		checkQueryError($addProductToWishlist);

		if ($addProductToWishlist) {
			$status = 1;
			$message = "Inserted. Successfully added to wishlist.";
			$information = "Inserted. Successfully added to wishlist.";
		}

	} else {
		$wish_prod_quantity = $wish_prod_quantity + 1;

		$query3 = "UPDATE tbl_wishlist SET wish_prod_quantity = '$wish_prod_quantity' WHERE user_id = '$user_id' AND prod_id = '$productId'";

		$updateCart = mysqli_query($connect, $query3);

		checkQueryError($updateCart);

		if ($updateCart) {
			$status = 1;
			$message = "Updated. Successfully updated wishlist.";
			$information = "Updated. Successfully updated wishlist.";
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
	$message = "Failed to add into wishlist...";
	$information = array();
}

?>