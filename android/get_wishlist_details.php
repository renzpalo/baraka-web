<?php

include('connect.php');
include('../php/functions.php');

// Get product JSON Array from tbl_Cart.
// User For Loop and Get Product Details from tbl_products.

$securityCode = validateFormData($_POST['security_code']);
$userId = validateFormData($_POST['user_id']);

// $securityCode = "123456";
// $userId = "1000016";

$doesNotExits = true;

$totalPrice = 0;

global $connect;

global $prodImageUrl;

if (isset($securityCode) && !empty($securityCode) && isset($userId) && !empty($userId)) {
	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "Wishlist is empty.";
	$information = "Wishlist is empty.";

	$productArray = array();

	$count = 0;

	$query = "SELECT * FROM tbl_wishlist WHERE user_id = '$userId'";

	$selectWishlist = mysqli_query($connect, $query);

	$numRows = mysqli_num_rows($selectWishlist);

	checkQueryError($selectWishlist);

	while ($row = mysqli_fetch_array($selectWishlist)) {
		$user_id = $row['user_id'];

		$doesNotExits = false;
	}

	if ($doesNotExits) {
		$status = 1;
		$message = "Wishlist is empty.";
		$information = array(
						'product_array' => $productArray,
						'total_price' => 0
						);
	} else {
		$query2 = "SELECT wishlist.*, products.* FROM tbl_wishlist wishlist, tbl_products products WHERE wishlist.prod_id = products.prod_id AND wishlist.user_id = '$user_id'";

		$selectProducts = mysqli_query($connect, $query2);

		checkQueryError($selectProducts);

		while ($row2 = mysqli_fetch_assoc($selectProducts)) {
			$prod_id = $row2['prod_id'];
			$prod_name = $row2['prod_name'];
			$prod_price = $row2['prod_price'];

			$prod_quantity = $row2['wish_prod_quantity'];

			$query3 = "SELECT prod_image FROM tbl_product_images WHERE prod_id = '$prod_id' ORDER BY prod_image_sequence DESC";

			$selectImages = mysqli_query($connect, $query3);

			$row3 = mysqli_fetch_assoc($selectImages);

			$prod_image = $row3['prod_image'];

			$totalPrice = $totalPrice + ($prod_price * $prod_quantity);

			$productArray[$count] = array(
										'prod_id' => $prod_id,
										'prod_name' => $prod_name,
										'prod_price' => $prod_price,
										'prod_image' => $prodImageUrl . $prod_image,
										'prod_quantity' => $prod_quantity
									);

			$count = $count + 1;
		}
		
		$status = 1;
		$message = "Displaying products.";
		$information = "Displaying products.";

	}

	$information = array(
						'product_array' => $productArray,
						'total_price' => $totalPrice
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