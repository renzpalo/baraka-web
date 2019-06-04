<?php

include('connect.php');
include('../php/functions.php');

$securityCode = validateFormData($_POST['security_code']);
$productId = validateFormData($_POST['prod_id']);
$userId = validateFormData($_POST['user_id']);

// $securityCode = "123456";
// $productId = "1000000009";
// $userId = "1000016";

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");

global $connect;

if (isset($securityCode) && !empty($securityCode) && isset($productId) && !empty($productId) && isset($userId) && !empty($userId)) {

	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "Failed to delete product from cart.";
	$information = "Failed to delete product from cart.";

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
		$information = "User does not exist.";

	} else {
		$query2 = "DELETE FROM tbl_cart WHERE user_id = '$user_id' AND prod_id = '$prod_id'";

		$deleteCart = mysqli_query($connect, $query2);

		checkQueryError($deleteCart);

		if ($deleteCart) {
			$status = 1;
			$message = "Successfully deleted product from cart.";
			$information = "Successfully deleted product from cart.";
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
	$message = "Failed to delete product from cart.";
	$information = "Failed to delete product from cart.";
}

?>