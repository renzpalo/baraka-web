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

	$query = "SELECT products.* FROM tbl_featured_products fproducts, tbl_products products WHERE fproducts.prod_id = products.prod_id ORDER BY fproducts.fprod_priority ASC";

	$selectProdDet = mysqli_query($connect, $query);

	$numRows = mysqli_num_rows($selectProdDet);

	if (!$selectProdDet) {
		die("Query failed: " . mysqli_error($connect));
	}

	while ($row = mysqli_fetch_array($selectProdDet)) {
		$prodId = $row['prod_id'];
		$prodName = $row['prod_name'];
		$prodPrice = $row['prod_price'];
		$prodDiscPrice = $row['prod_old_price'];
		$prodRating = $row['prod_rating'];

		$query3 = "SELECT prod_image FROM tbl_product_images WHERE prod_id = '$prodId' ORDER BY prod_image_sequence DESC";

		$selectImages = mysqli_query($connect, $query3);

		$row3 = mysqli_fetch_assoc($selectImages);

		$prodImage = $row3['prod_image'];

		$status = 1;
		$message = "Displaying " . $numRows . " featured products.";
		$productArray[$count] = array(
									'prod_id' => $prodId,
									'prod_name' => $prodName,
									'prod_price' => $prodPrice,
									'prod_disc_price' => $prodDiscPrice,
									'prod_image' => $prodImageUrl . $prodImage,
									'prod_rating' => $prodRating);

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
	$message = "No products found.";
	$information = "No products found.";
}



?>