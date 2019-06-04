<?php

include('connect.php');
include('../php/functions.php');

$securityCode = validateFormData($_POST['security_code']);

// $securityCode = "123456";

global $connect;

global $carouselImageUrl;

if (isset($securityCode) && !empty($securityCode)) {
	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "";
	$information = "";
	$bannerArray = array();
	$count = 0;

	$query = "SELECT car_id, car_image FROM tbl_carousel_ads ORDER BY car_priority ASC";

	$selectBanners = mysqli_query($connect, $query);

	$numRows = mysqli_num_rows($selectBanners);

	if (!$selectBanners) {
		die("Query failed: " . mysqli_error($connect));
	}

	while ($row = mysqli_fetch_array($selectBanners)) {
		$bannerId = $row['car_id'];
		$bannerImage = $row['car_image'];

		$status = 1;
		$message = "Displaying " . $numRows . " banners.";
		$bannerArray[$count] = array(
									'car_id' => $bannerId,
									'car_image' => $carouselImageUrl . $bannerImage);

		$count = $count + 1;
	}

	$information = $bannerArray;

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