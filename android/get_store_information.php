<?php

include('connect.php');
include('../php/functions.php');

global $connect;
global $sellerImageUrl;
global $bannerImageUrl;

$securitycode = validateFormData($_POST['security_code']);
$sellerId = validateFormData($_POST['seller_id']);

// $securitycode = "1234";
// $sellerId = "1000001";

if (isset($securitycode) && !empty($securitycode) && isset($sellerId) && !empty($sellerId)) {

	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$count = 0;
	$sellerInfo = array();

	$status = 0;
	$message = "Failed to get seller information.";
	$information = array(
						'seller_info' => $sellerInfo
						);

	

	$query = "SELECT * FROM tbl_sellers WHERE seller_id = '$sellerId'";

	$selectSeller = mysqli_query($connect, $query);

	checkQueryError($selectSeller);

	while ($row = mysqli_fetch_assoc($selectSeller)) {
		$seller_id = $row['seller_id'];
		$seller_name = $row['seller_name'];
		$seller_info = $row['seller_info'];
		$seller_image = $row['seller_image'];
		$seller_banner = $row['seller_banner'];

		$sellerInfo[$count] = array(
									'seller_id' => $seller_id,
									'seller_name' => $seller_name,
									'seller_info' => $seller_info,
									'seller_image' => $sellerImageUrl . $seller_image,
									'seller_banner' => $bannerImageUrl . $seller_banner
									);

		$count = $count + 1;
	}

	if ($selectSeller) {
		$status = 1;
		$message = "Showing seller information.";
		$information = array(
							'seller_info' => $sellerInfo
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
	$message = "Failed to get seller information.";
	$information = array(
						'seller_info' => $sellerInfo
						);
}


?>