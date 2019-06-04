<?php

include('connect.php');
include('../php/functions.php');

global $connect;
global $categoryImageUrl;

$securitycode = validateFormData($_POST['security_code']);

// $securitycode = "1234";

if (isset($securitycode) && !empty($securitycode)) {

	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$count = 0;
	$categories = array();

	$status = 0;
	$message = "Failed to get Categories.";
	$information = array(
						'categories' => $categories
						);

	$query = "SELECT * FROM tbl_categories ORDER BY cat_name ASC";

	$selectCategories = mysqli_query($connect, $query);

	checkQueryError($selectCategories);

	while ($row = mysqli_fetch_assoc($selectCategories)) {
		$cat_id = $row['cat_id'];
		$cat_name = $row['cat_name'];
		$cat_image = $row['cat_image'];

		$categories[$count] = array(
									'cat_id' => $cat_id,
									'cat_name' => $cat_name,
									'cat_image' => $categoryImageUrl . $cat_image
									);

		$count = $count + 1;
	}

	if ($selectCategories) {
		$status = 1;
		$message = "Showing Categories.";
		$information = array(
							'categories' => $categories
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
	$message = "Failed to get Categories.";
	$information = array(
						'categories' => $categories
						);
}


?>