<?php

include('connect.php');
include('../php/functions.php');

global $connect;
global $provinceImageUrl;

$securitycode = validateFormData($_POST['security_code']);

// $securitycode = "1234";

if (isset($securitycode) && !empty($securitycode)) {

	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$count = 0;
	$provinces = array();

	$status = 0;
	$message = "Failed to get Provinces.";
	$information = array(
						'provinces' => $provinces
						);

	

	$query = "SELECT * FROM tbl_province ORDER BY prov_name ASC";

	$selectProvinces = mysqli_query($connect, $query);

	checkQueryError($selectProvinces);

	while ($row = mysqli_fetch_assoc($selectProvinces)) {
		$prov_id = $row['prov_id'];
		$prov_name = $row['prov_name'];
		$prov_image = $row['prov_image'];

		$provinces[$count] = array(
									'prov_id' => $prov_id,
									'prov_name' => $prov_name,
									'prov_image' => $provinceImageUrl . $prov_image
									);

		$count = $count + 1;
	}

	if ($selectProvinces) {
		$status = 1;
		$message = "Showing provinces.";
		$information = array(
							'provinces' => $provinces
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
	$message = "Failed to get Provinces.";
	$information = array(
						'provinces' => $provinces
						);
}


?>