<?php
include('php/logistics_header.php');
include('../../php/includes/connect.php');
include('php/logistics_dash_nav.php');
include('../../php/functions.php');

if (!$_SESSION['log_id']) {
	header("Location: index.php");
}

$logId = $_SESSION['log_id'];
$logCompany = $_SESSION['log_company'];
$logName = $_SESSION['log_name'];

$alertMessage = '';


// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");

if (isset($_POST['cbNewShipments'])) {
	foreach ($_POST['cbNewShipments'] as $shipIdValue) {

		$shipStatusOption = $_POST['shipping_status_option'];

		switch ($shipStatusOption) {
			case 'delivered':
				$query2 = "UPDATE tbl_shippings SET shipping_status = '$shipStatusOption' WHERE shipping_id = '$shipIdValue'";

				$updateOrderStatus = mysqli_query($conn, $query2);

				checkQueryError($updateOrderStatus);

				$query6 = "SELECT order_id FROM tbl_shippings WHERE shipping_id = '$shipIdValue'";

				$selectOrderId = mysqli_query($conn, $query6);

				checkQueryError($selectOrderId);

				$row6 = mysqli_fetch_assoc($selectOrderId);

				$order_id = $row6['order_id'];

				if ($updateOrderStatus) {
		            $writeAReview = "<a href='http://barakaph.com/reviews.php'>Write a review.</a>";
		            $orderUpdateInfo = "Your package has been delivered. Thank you for shopping. ";

		            $query5 = "INSERT INTO tbl_order_updates (order_update_status, order_update_info, order_update_by, order_update_date, order_id) VALUES ('$shipStatusOption', '$orderUpdateInfo', '$logName', '$date', '$order_id')";

		            $insertUpdateStatus = mysqli_query($conn, $query5);

		            checkQueryError($insertUpdateStatus);

	            	if ($selectOrderId) {
	            		$query7 = "UPDATE tbl_orders SET order_status = 'delivered' WHERE order_id = '$order_id'";

				        $updateOrderStatus = mysqli_query($conn, $query7);

				        checkQueryError($updateOrderStatus);
	            	}

		            if ($insertUpdateStatus) {
		            	$query8 = "SELECT orders.*, order_products.* FROM tbl_orders orders, tbl_order_products order_products WHERE orders.order_id = order_products.order_id AND order_products.order_id = '$order_id'";

				    	$selectOrderProducts = mysqli_query($conn, $query8);

				    	checkQueryError($selectOrderProducts);

				    	while ($row8 = mysqli_fetch_assoc($selectOrderProducts)) {
				    		$order_id = $row8['order_id'];
				    		$prod_id = $row8['prod_id'];
				    		$user_id = $row8['user_id'];
				    		$seller_id = $row8['seller_id'];
				    		$order_prod_quantity = $row8['order_prod_quantity'];
				    		$order_prod_price = $row8['order_prod_price'];

				    		$order_prod_total = $order_prod_price * $order_prod_quantity;

				    		$query9 = "UPDATE tbl_product_stats SET stats_sales = stats_sales + '$order_prod_quantity' WHERE prod_id = '$prod_id'";

							$updateStats = mysqli_query($conn, $query9);

							checkQueryError($updateStats);

							$query10 = "UPDATE tbl_product_stats SET stats_earnings = stats_earnings + '$order_prod_total' WHERE prod_id = '$prod_id'";

							$updateStatsEarnings = mysqli_query($conn, $query10);

							checkQueryError($updateStatsEarnings);

							if ($updateStatsEarnings) {
								$query11 = "INSERT INTO tbl_reviews (rev_posted_by, rev_summary, rev_feedback, rev_image, rev_rating, rev_date_posted, rev_status, user_id, prod_id) 
												VALUES ('', '', '', '', '0', '$date', 'unreviewed', '$user_id', '$prod_id')";

								$insertReviews = mysqli_query($conn, $query11);

								checkQueryError($insertReviews);

								$query12 = "INSERT INTO tbl_payments (payment_amount, payment_date, prod_quantity, prod_id, order_id, seller_id, user_id, log_name) 
												VALUES ('$order_prod_total', '$date', '$order_prod_quantity', '$prod_id', '$order_id', '$seller_id', '$user_id', '$logName')";

								$insertPayments = mysqli_query($conn, $query12);

								checkQueryError($insertPayments);

								if ($insertPayments) {
									$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
			                                Successfully updated.
			                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
			                                  <span aria-hidden='true'>&times;</span>
			                                </button>
			                               </div>";
								}
							}
				    		// Update Order Stats
				    		// Send Review Link
				    		// Insert Payment
				    	}
		            }



				}
			break;

			case 'redeliver':
				$query2 = "UPDATE tbl_shippings SET shipping_status = '$shipStatusOption' WHERE shipping_id = '$shipIdValue'";

				$updateOrderStatus = mysqli_query($conn, $query2);

				checkQueryError($updateOrderStatus);

				$query6 = "SELECT order_id FROM tbl_shippings WHERE shipping_id = '$shipIdValue'";

				$selectOrderId = mysqli_query($conn, $query6);

				checkQueryError($selectOrderId);

				$row6 = mysqli_fetch_assoc($selectOrderId);

				$order_id = $row6['order_id'];

				if ($updateOrderStatus) {
		            $orderUpdateInfo = "Our delivery team was unable to reach you. We will redeliver the package soon.";

		            $query5 = "INSERT INTO tbl_order_updates (order_update_status, order_update_info, order_update_by, order_update_date, order_id) VALUES ('$shipStatusOption', '$orderUpdateInfo', '$logName', '$date', '$order_id')";

		            $insertUpdateStatus = mysqli_query($conn, $query5);

		            checkQueryError($insertUpdateStatus);

		            if ($insertUpdateStatus) {
		            	// Send review link.

		                $alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
		                                Successfully updated.
		                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
		                                  <span aria-hidden='true'>&times;</span>
		                                </button>
		                               </div>";
		            }



				}
			break;

			case 'unreachable':
				$query2 = "UPDATE tbl_shippings SET shipping_status = '$shipStatusOption' WHERE shipping_id = '$shipIdValue'";

				$updateOrderStatus = mysqli_query($conn, $query2);

				checkQueryError($updateOrderStatus);

				$query6 = "SELECT order_id FROM tbl_shippings WHERE shipping_id = '$shipIdValue'";

				$selectOrderId = mysqli_query($conn, $query6);

				checkQueryError($selectOrderId);

				$row6 = mysqli_fetch_assoc($selectOrderId);

				$order_id = $row6['order_id'];

				if ($updateOrderStatus) {
		            $orderUpdateInfo = "Our delivery team was unable to reach you.";

		            $query5 = "INSERT INTO tbl_order_updates (order_update_status, order_update_info, order_update_by, order_update_date, order_id) VALUES ('$shipStatusOption', '$orderUpdateInfo', '$logName', '$date', '$order_id')";

		            $insertUpdateStatus = mysqli_query($conn, $query5);

		            checkQueryError($insertUpdateStatus);

		            if ($insertUpdateStatus) {
		            	// Send review link.

		                $alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
		                                Successfully updated.
		                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
		                                  <span aria-hidden='true'>&times;</span>
		                                </button>
		                               </div>";
		            }



				}
			break;

			default:

			break;
		}
	}
}

?>

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

				<div class="row mb-3">
					<div class="col-sm-3">
						<select name="shipping_status_option" id="" class="form-control">
							<option value="" selected disabled>Select</option>
							<option value="delivered">Delivered</option>
							<option value="redeliver">Redeliver</option>
							<option value="unreachable">Unreachable</option>
						</select>
					</div>
					
					<div class="col-sm-4">
						<input type="submit" value="Apply" class="btn btn-primary">
					</div>
				</div>



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
							<th>Details</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$query = "SELECT shippings.*, address.*, logistics.* FROM tbl_shippings shippings, tbl_user_address address, tbl_logistics logistics WHERE shippings.shippIng_status = 'redeliver' AND shippings.shipping_assigned_to = logistics.log_id AND shippings.ad_id = address.ad_id AND shipping_assigned_to = '$logId' AND  shipping_company = '$logCompany' ORDER BY address.ad_province ASC";

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

							$log_branch = $row['log_branch']
						?>
						<tr>
							<td>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" name="cbNewShipments[]" class="custom-control-input cbTableItem" id="<?php echo $shipping_id; ?>" value="<?php echo $shipping_id; ?>">
									<label class="custom-control-label" for="<?php echo $shipping_id; ?>"><p></p></label>
								</div>
							</td>
							<td><?php echo $shipping_id; ?></td>
							<td><?php echo $log_branch; ?></td>
							<td><?php echo $shipping_status; ?></td>
							<td><?php echo $shipping_date_created; ?></td>
							<td><?php echo $shipping_date_updated; ?></td>
							<td><?php echo $order_id; ?></td>
							<td><?php echo $ad_city_muni; ?></td>
							<td><?php echo $ad_province; ?></td>
							<td><a href="shipment_details.php?o_id=<?php echo $order_id; ?>">View Details</a></td>
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