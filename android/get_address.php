<?php

include('connect.php');
include('../php/functions.php');

// Get product JSON Array from tbl_Cart.
// User For Loop and Get Product Details from tbl_products.

$securityCode = validateFormData($_POST['security_code']);
$userId = validateFormData($_POST['user_id']);

// $securityCode = "123456";
// $userId = "1000001";

$doesNotExits = true;

global $connect;


if (isset($securityCode) && !empty($securityCode) && isset($userId) && !empty($userId)) {

	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "Cart is empty.";
	$information = "Cart is empty.";

	$address = array();
	$count = 0;

	$query = "SELECT * FROM tbl_user_address WHERE user_id = '$userId'";

	$selectAddress = mysqli_query($connect, $query);

	checkQueryError($selectAddress);

	while ($row = mysqli_fetch_array($selectAddress)) {
		$user_id = $row['user_id'];

		$doesNotExits = false;
	}

	if ($doesNotExits) {
		$status = 0;
		$message = "User does not exist.";
		$information = $address;
	} else {

		$query2 = "SELECT * FROM tbl_user_address WHERE user_id = '$user_id'";

		$selectAddressDetails = mysqli_query($connect, $query2);

		checkQueryError($selectAddressDetails);

		while ($row2 = mysqli_fetch_assoc($selectAddressDetails)) {
			$ad_id = $row2['ad_id'];
			$ad_fulname = $row2['ad_fullname'];
			$ad_contact = $row2['ad_contact'];
			$ad_street = $row2['ad_street'];
			$ad_barangay = $row2['ad_barangay'];
			$ad_city_muni = $row2['ad_city_muni'];
			$ad_province = $row2['ad_province'];
			$ad_zipcode = $row2['ad_zipcode'];
			$ad_notes = $row2['ad_notes'];

			$address[$count] = array(
								'ad_id' => $ad_id,
								'ad_fulname' => $ad_fulname,
								'ad_contact' => $ad_contact,
								'ad_street' => $ad_street,
								'ad_barangay' => $ad_barangay,
								'ad_city_muni' => $ad_city_muni,
								'ad_province' => $ad_province,
								'ad_zipcode' => $ad_zipcode,
								'ad_notes' => $ad_notes
							);

			$count = $count + 1;
		}
		
		$status = 1;
		$message = "Displaying address details.";
		$information = $address;

	}



	$postData = array(
					'status' => $status,
					'message' => $message,
					'information' => $information);

	$postData = json_encode($postData);

	echo $postData;

	mysqli_close($connect);

} else {
	$status = 0;
	$message = "No address found.";
	$information = array();
}



?>