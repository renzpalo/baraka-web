<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

if (!$_SESSION['user_id']) {
	header("Location: index.php");
}

if (isset($_SESSION['user_id'])) {
	$userId = $_SESSION['user_id'];
}

$order_id = "";
$order_status = "";

$alertMessage = "";

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");

if (isset($_GET['o_id'])) {
	$getOrderId = $_GET['o_id'];

	$query = "SELECT * FROM tbl_orders WHERE order_id = '$getOrderId'";
	$selectOrder = mysqli_query($conn, $query);
	checkQueryError($selectOrder);

	while ($row = mysqli_fetch_assoc($selectOrder)) {
		$order_id = $row['order_id'];
		$order_status = $row['order_status'];

	}

	if ($order_status == 'processing' || $order_status == 'handed' || $order_status == 'shipped' || $order_status == 'delivered' || $order_status == 'cancelled') {
		header("Location: order_details.php?o_id=$getOrderId&status=failed");
	} else {
		$query5 = "UPDATE tbl_orders SET order_status = 'cancelled' WHERE order_id = '$getOrderId'";

        $updateOrder = mysqli_query($conn, $query5);

        checkQueryError($updateOrder);

        if ($updateOrder) {
          $orderStatus = "Canceled";
          $orderUpdateInfo = "Your order has been canceled.";

          $query6 = "INSERT INTO tbl_order_updates (order_update_status, order_update_info, order_update_by, order_update_date, order_id) VALUES ('$orderStatus', '$orderUpdateInfo', '$userId', '$date', '$getOrderId')";

          $insertUpdateStatus = mysqli_query($conn, $query6);

          checkQueryError($insertUpdateStatus);

          if ($insertUpdateStatus) {
          	header("Location: order_details.php?o_id=$getOrderId");
          }

      }
	}
}

echo $alertMessage;

?>