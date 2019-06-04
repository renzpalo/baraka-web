<?php
include('php/logistics_header.php');
include('../../php/includes/connect.php');
include('php/logistics_dash_nav.php');
include('../../php/functions.php');

if (!$_SESSION['log_id']) {
	header("Location: index.php");
}

$logCompany = $_SESSION['log_company'];
$logName = $_SESSION['log_name'];

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");

$alertMessage = '';

if (isset($_POST['cbNewShipments'])) {
	foreach ($_POST['cbNewShipments'] as $shipIdValue) {

		$shipStatusOption = $_POST['shipping_status_option'];
		$shipBranch = $_POST['logistics_branch'];

		switch ($shipStatusOption) {
			case 'assigned':
				$query2 = "UPDATE tbl_shippings SET shipping_status = '$shipStatusOption', shipping_assigned_to = '$shipBranch' WHERE shipping_id = '$shipIdValue'";

				$updateOrderStatus = mysqli_query($conn, $query2);

				checkQueryError($updateOrderStatus);

				if ($updateOrderStatus) {
					$query4 = "INSERT INTO tbl_shipping (shipping_company, shipping_assigned_to, shipping_status, shipping_date_created, shipping_date_updated, order_id, ad_id) VALUES ('$logisticsCompany', 'Unassigned', 'handed', '$date', '$date', '$orderIdValue', '$ad_id')";

		            $insertShipment = mysqli_query($conn, $query4);

		            checkQueryError($insertShipment);

		            if ($insertShipment) {
		            	$query6 = "SELECT order_id FROM tbl_shippings WHERE shipping_id = '$shipIdValue'";

		            	$selectOrderId = mysqli_fetch_assoc($conn, $query6);

		            	checkQueryError($selectOrderId);

		            	$row6 = mysqli_fetch_assoc($selectOrderId);

		            	$order_id = $row6['order_id'];

			            $orderStatus = "Handed";
			            $orderUpdateInfo = "Your order has been handed to our logistics partner.";

			            $query5 = "INSERT INTO tbl_order_updates (order_update_status, order_update_info, order_update_by, order_update_date, order_id) VALUES ('$orderStatus', '$orderUpdateInfo', '$logName', '$date', '$order_id')";

			            $insertUpdateStatus = mysqli_query($conn, $query5);

			            checkQueryError($insertUpdateStatus);

			            if ($insertUpdateStatus) {
				            $orderStatus = "Handed";
				            $orderUpdateInfo = "Your order has been handed to our logistics partner.";

				            $query5 = "INSERT INTO tbl_order_updates (order_update_status, order_update_info, order_update_by, order_update_date, order_id) VALUES ('$orderStatus', '$orderUpdateInfo', '$sellerName', '$date', '$orderIdValue')";

				            $insertUpdateStatus = mysqli_query($conn, $query5);

				            checkQueryError($insertUpdateStatus);

				            if ($insertUpdateStatus) {
				                $alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
				                                Product posted.
				                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				                                  <span aria-hidden='true'>&times;</span>
				                                </button>
				                               </div>";
				             }
			            }

					}

				}
			break;

			default:

			break;
		}
	}
}

?>

<div class="container-fluid">
	<div class="row">
		<?php include('php/logistics_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Redeliver Shipments</h1>

				<?php echo $alertMessage; ?>
				
			</div>

			<form action="" method="post">
				<table class="table">
					<thead>
						<tr>
							<th>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="cbTableAll">
									<label class="custom-control-label" for="cbTableAll"><p></p></label>
								</div>
							</th>
							<th>ID</th>
							<th>Assigned</th>
							<th>Status</th>
							<th>Date Created</th>
							<th>Date Updated</th>
							<th>Order ID</th>
							<th>City / Municipality</th>
							<th>Province</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$query = "SELECT shippings.*, address.* FROM tbl_shippings shippings, tbl_user_address address WHERE shippings.ad_id = address.ad_id AND shipping_status = 'redeliver' AND  shipping_company = '$logCompany' ORDER BY address.ad_province ASC";

						// $query = "SELECT * FROM tbl_shippings";

						$selectAllShippings = mysqli_query($conn, $query);

						checkQueryError($selectAllShippings);

						while ($row = mysqli_fetch_assoc($selectAllShippings)) {
							$shipping_id = $row['shipping_id'];
							$shipping_company = $row['shipping_company'];
							$shipping_assigned_to = $row['shipping_assigned_to'];
							$shipping_status = $row['shipping_status'];
							$shipping_date_created = $row['shipping_date_created'];
							$shipping_date_updated = $row['shipping_date_updated'];
							$shipping_date = $row['shipping_date'];
							$shipping_date_delivered = $row['shipping_date_delivered'];
							$order_id = $row['order_id'];

							$ad_id = $row['ad_id'];
							$ad_city_muni = $row['ad_city_muni'];
							$ad_province = $row['ad_province'];
						?>
						<tr>
							<td>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" name="cbNewShipments[]" class="custom-control-input cbTableItem" id="<?php echo $shipping_id; ?>" value="<?php echo $shipping_id; ?>">
									<label class="custom-control-label" for="<?php echo $shipping_id; ?>"><p></p></label>
								</div>
							</td>
							<td><?php echo $shipping_id; ?></td>
							<td><?php echo $shipping_assigned_to; ?></td>
							<td><?php echo $shipping_status; ?></td>
							<td><?php echo $shipping_date_created; ?></td>
							<td><?php echo $shipping_date_updated; ?></td>
							<td><?php echo $order_id; ?></td>
							<td><?php echo $ad_city_muni; ?></td>
							<td><?php echo $ad_province; ?></td>
						</tr>


						<?php

						}




						?>
					</tbody>
				</table>




			</form>
			
		</main>
	</div>
</div>


<?php include('php/logistics_footer.php'); ?>