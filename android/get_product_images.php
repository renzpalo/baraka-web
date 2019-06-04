<?php

include('connect.php');
include('../php/functions.php');

global $connect;
global $prodImageUrl;

$securitycode = validateFormData($_POST['security_code']);
$productId = validateFormData($_POST['prod_id']);

// $securitycode = "1234";
// $productId = "1000000020";

if (isset($securitycode) && !empty($securitycode) && isset($productId) && !empty($productId)) {

	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$count = 0;
	$productImages = array();

	$status = 0;
	$message = "Failed to get images.";
	$information = array(
						'images' => $productImages
						);

	$query = "SELECT * FROM tbl_product_images WHERE prod_id = '$productId' ORDER BY prod_image_sequence ASC";

	$selectImages = mysqli_query($connect, $query);

	checkQueryError2($selectImages);

	while ($row = mysqli_fetch_assoc($selectImages)) {
		$prod_image_id = $row['prod_image_id'];
		$prod_image = $row['prod_image'];
		$prod_image_sequence = $row['prod_image_sequence'];

		$productImages[$count] = array(
									'image_id' => $prod_image_id,
									'image' => $prodImageUrl . $prod_image,
									'image_seq' => $prod_image_sequence
									);

		$count = $count + 1;
	}

	if ($selectImages) {
		$status = 1;
		$message = "Showing images.";
		$information = array(
							'images' => $productImages
							);
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
	$message = "Failed to get images.";
	$information = array(
						'images' => $productImages
						);
}


?>