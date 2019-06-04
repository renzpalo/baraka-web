<?php

include('connect.php');
include('../php/functions.php');

$securityCode = validateFormData($_POST['security_code']);
$catId = validateFormData($_POST['cat_id']);

// $securityCode = "12345";
// $catId = "36";

global $connect;

global $prodImageUrl;

if (isset($securityCode) && !empty($securityCode) && isset($catId) && !empty($catId) ) {
	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "";
	$information = "";
	$productArray = array();
	$count = 0;

	$query = "SELECT products.*, province.prov_name, categories.cat_name FROM tbl_products products, tbl_province province, tbl_categories categories WHERE products.prov_id = province.prov_id AND products.cat_id = categories.cat_id AND products.cat_id = '$catId'";

	$search = mysqli_query($connect, $query);

	checkQueryError($search);

	$rowsCount = mysqli_num_rows($search);

	while ($row = mysqli_fetch_assoc($search)) {
		$prodId = $row['prod_id'];
		$prodName = $row['prod_name'];
		$prodPrice = $row['prod_price'];
		$prodDiscPrice = $row['prod_old_price'];
		$prodRating = $row['prod_rating'];

		$province = $row['prov_name'];
		$category = $row['cat_name'];

		$query3 = "SELECT prod_image FROM tbl_product_images WHERE prod_id = '$prodId' ORDER BY prod_image_sequence DESC";

		$selectImages = mysqli_query($connect, $query3);

		$row3 = mysqli_fetch_assoc($selectImages);

		$prodImage = $row3['prod_image'];

		$status = 1;
		$message = "Displaying " . $rowsCount . " featured products.";
		$productArray[$count] = array(
									'prod_id' => $prodId,
									'prod_name' => $prodName,
									'prod_price' => $prodPrice,
									'prod_disc_price' => $prodDiscPrice,
									'prod_image' => $prodImageUrl . $prodImage,
									'prod_rating' => $prodRating,
									'prov_name' => $province,
									'cat_name' => $category
								);

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