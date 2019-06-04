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

$order_id = "";
$address = "";
$order_payment = "";
$date_created = "";
$order_status = "";

$order_total = 0;

$count = 0;

global $connect;

global $prodImageUrl;

if (isset($securityCode) && !empty($securityCode) && isset($userId) && !empty($userId)) {

	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "Order History is empty.";
	
	$information = array();

	// $orderHistory = array(
	// 					'order_id' => $order_id,
	// 					'order_status' => $order_status,
	// 					'payment_type' => $order_payment,
	// 					'address' => $address,
	// 					'price' => $order_total,
	// 					'date' => $date_created
	// 					);

	$query = "SELECT orders.*, address.* FROM tbl_orders orders, tbl_user_address address WHERE orders.ad_id = address.ad_id AND orders.user_id = '$userId' ORDER BY orders.order_date_created";

	$selectOrders = mysqli_query($connect, $query);

	$numRows = mysqli_num_rows($selectOrders);

	checkQueryError($selectOrders);

	while ($row = mysqli_fetch_array($selectOrders)) {
		$doesNotExits = false;

		$order_id = $row['order_id'];
		$order_total = $row['order_total'];
		$order_payment = $row['order_payment'];
		$order_status = $row['order_status'];
		$date_created = $row['order_date_created'];

		$ad_id = $row['ad_id'];
		$ad_fullname = $row['ad_fullname'];
		$ad_contact = $row['ad_contact'];
		$ad_street = $row['ad_street'];
		$ad_barangay = $row['ad_barangay'];
		$ad_city_muni = $row['ad_city_muni'];
		$ad_province = $row['ad_province'];
		$ad_zipcode = $row['ad_zipcode'];

		$address = $ad_street . ", " . $ad_barangay . ", " . $ad_city_muni . ", " . $ad_province . ", " . $ad_zipcode;

		$orderHistory[$count] = array(
						'order_id' => $order_id,
						'order_status' => $order_status,
						'payment_type' => $order_payment,
						'fullname' => $ad_fullname,
						'contact' => $ad_contact,
						'address' => $address,
						'price' => $order_total,
						'date' => $date_created
						);

		$count = $count + 1;

		
	}

	if ($doesNotExits) {
		$status = 0;
		$message = "Order History is empty.";
		$information = array();
	} else {
		$status = 1;
		$message = "Displaying order history.";
		$information = array(
						'order_history' => $orderHistory,
						);

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
	$message = "Order History is empty.";
	$information = array();
}



?>