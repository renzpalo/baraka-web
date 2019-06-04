<?php

include('connect.php');
include('../php/functions.php');

global $connect;

$securitycode = validateFormData($_POST['security_code']);
$orderId = validateFormData($_POST['order_id']);

// $securitycode = "1234";
// $orderId = "1000000033";

if (isset($securitycode) && !empty($securitycode) && isset($orderId) && !empty($orderId)) {

	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$count = 0;
	$orderStatus = array();

	$status = 0;
	$message = "Failed to get order updates.";
	$information = array(
						'order_status' => $orderStatus
						);

	$query = "SELECT * FROM tbl_order_updates WHERE order_id = '$orderId' ORDER BY order_update_date DESC";

	$selectOrderStatus = mysqli_query($connect, $query);

	checkQueryError($selectOrderStatus);

	while ($row = mysqli_fetch_assoc($selectOrderStatus)) {
		$order_update_id = $row['order_update_id'];
		$order_update_info = $row['order_update_info'];
		$order_update_date = $row['order_update_date'];

		$orderStatus[$count] = array(
									'order_update_id' => $order_update_id,
									'order_update_info' => $order_update_info,
									'order_update_date' => $order_update_date
									);

		$count = $count + 1;
	}

	if ($selectOrderStatus) {
		$status = 1;
		$message = "Showing order updates.";
		$information = array(
							'order_status' => $orderStatus
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
	$message = "Failed to get order updates.";
	$information = array(
						'order_status' => $orderStatus
						);
}


?>