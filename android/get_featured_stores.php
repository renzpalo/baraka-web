<?php

include('connect.php');


$securityCode = htmlentities($_POST['security_code']);
$securityCode = stripslashes($securityCode);

// $securityCode = "123456";

global $connect;

global $prodImageUrl;

if (isset($securityCode) && !empty($securityCode)) {
	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "";
	$information = "";
	$productArray = array();
	$count = 0;

	// SELECT PROD_ID from tbl_featured_products WHERE ORDER BY fprod_priority ASC
	// SELECT * FROM tbl_products WHERE prod_id = prod_id

	// $stmt = $connect -> prepare("SELECT prod_det.prod_id, prod_det.prod_name, prod_det.prod_price, prod_det.prod_disc_price, prod_det.prod_image, prod_det.prod_rating FROM tbl_featured_products f_prod, tbl_product_details prod_det WHERE f_prod.prod_id = prod_det.prod_id ORDER BY f_prod.fprod_priority ASC");
	// $stmt -> execute();
	// $stmt -> store_result();
	// $stmt -> bind_result($prodId, $prodName, $prodPrice, $prodDiscPrice, $prodImage, $prodRating);

	// while ($stmt -> fetch()) {
	// 	$status = 1;
	// 	$message = "Displaying Products" . $numRows;
	// 	$productArray[$count] = array(
	// 								'prod_id' => $prodId,
	// 								'prod_name' => $prodName,
	// 								'prod_price' => $prodPrice,
	// 								'prod_disc_price' => $prodDiscPrice,
	// 								'prod_image' => $imageUrl . $prodImage,
	// 								'prod_rating' => $prodRating);

	// 	$count = $count + 1;
	// }

	// $query = "SELECT prod_det.prod_id, prod_det.prod_name, prod_det.prod_price, prod_det.prod_disc_price, prod_det.prod_image, prod_det.prod_rating FROM tbl_featured_products f_prod, tbl_product_details prod_det WHERE f_prod.prod_id = prod_det.prod_id ORDER BY f_prod.fprod_priority ASC";

	$query = "SELECT seller.seller_id, seller.seller_name, seller.seller_image FROM tbl_featured_stores fstores, tbl_sellers seller WHERE fstores.seller_id = seller.seller_id ORDER BY fstores.fstore_priority ASC";

	$selectSeller = mysqli_query($connect, $query);

	$numRows = mysqli_num_rows($selectSeller);

	if (!$selectSeller) {
		die("Query failed: " . mysqli_error($connect));
	}

	while ($row = mysqli_fetch_array($selectSeller)) {
		$sellerId = $row['seller_id'];
		$sellerName = $row['seller_name'];
		$sellerImage = $row['seller_image'];

		$status = 1;
		$message = "Displaying " . $numRows . " featured stores.";
		$productArray[$count] = array(
									'seller_id' => $sellerId,
									'seller_name' => $sellerName,
									'seller_image' => $sellerImageUrl . $sellerImage);

		$count = $count + 1;
	}

	$information = $productArray;

	$postData = array(
					'status' => $status,
					'message' => $message,
					'information' => $information);

	$postData = json_encode($postData);

	echo $postData;

	mysqli_close($connect);
} else {
	$status = 0;
	$message = "No seller found.";
	$information = "No seller found.";
}



?>