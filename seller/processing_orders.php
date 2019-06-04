<?php
include('php/seller_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');
include('php/seller_dash_nav.php');

if (!$_SESSION['seller_name']) {
	header("Location: index.php");
}

$sellerId = $_SESSION['seller_id'];
$sellerName = $_SESSION['seller_name'];

$totalPrice = 0;

$alertMessage = '';

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");

if (isset($_POST['cbNewOrderArray'])) {
  foreach ($_POST['cbNewOrderArray'] as $orderIdValue) {

    $orderStatusOption = $_POST['order_status_option'];
    $logisticsCompany = $_POST['logistics_company'];

    switch ($orderStatusOption) {
      case 'handed':
        $query2 = "UPDATE tbl_orders SET order_status = '$orderStatusOption' WHERE order_id = '$orderIdValue'";

        $updateOrderStatus = mysqli_query($conn, $query2);

        checkQueryError($updateOrderStatus);

        if ($updateOrderStatus) {

          $query3 = "SELECT ad_id FROM tbl_orders WHERE order_id = '$orderIdValue'";

          $selectAddress = mysqli_query($conn, $query3);

          checkQueryError($selectAddress);

          $row3 = mysqli_fetch_assoc($selectAddress);

          $ad_id = $row3['ad_id'];

          if ($selectAddress) {
            $query4 = "INSERT INTO tbl_shippings (shipping_company, shipping_assigned_to, shipping_status, shipping_date_created, shipping_date_updated, order_id, ad_id) VALUES ('$logisticsCompany', 'Unassigned', 'handed', '$date', '$date', '$orderIdValue', '$ad_id')";

            $insertShipment = mysqli_query($conn, $query4);

            checkQueryError($insertShipment);

            if ($insertShipment) {
              $orderStatus = "Handed";
              $orderUpdateInfo = "Your order has been handed to our logistics partner.";

              $query5 = "INSERT INTO tbl_order_updates (order_update_status, order_update_info, order_update_by, order_update_date, order_id) VALUES ('$orderStatus', '$orderUpdateInfo', '$sellerName', '$date', '$orderIdValue')";

              $insertUpdateStatus = mysqli_query($conn, $query5);

              checkQueryError($insertUpdateStatus);

              if ($insertUpdateStatus) {
                $alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                Order successfully handed.
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

<title>Processing Orders</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/seller_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Processing Orders</h1>

            <?php echo $alertMessage; ?>

            
          </div>

          <form action="" method="post">
            <div class="row mb-3">
              <div class="col-sm-3">
                <select name="order_status_option" id="" class="form-control">
                  <option value="" selected disabled>Select</option>
                  <option value="handed">Hand Package</option>
                </select>
              </div>
              <h6 class="mt-2">to</h6>
              <div class="col-sm-3">
                <select name="logistics_company" id="" class="form-control">
                  <option value="" selected disabled>Select</option>
                  <?php

                  $query4 = "SELECT log_company FROM tbl_logistics GROUP BY log_company";

                  $selectLogistics = mysqli_query($conn, $query4);

                  checkQueryError($selectLogistics);

                  while ($row4 = mysqli_fetch_assoc($selectLogistics)) {
                    $log_company = $row4['log_company'];

                    echo "<option value='$log_company'>$log_company</option>";
                  }

                  ?>
                  
                </select>
              </div>
              <div class="col-sm-4">
                <input type="submit" value="Apply" class="btn btn-primary">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <table class="table">
                  <thead>
                    <tr>
                      <th>
                        <!-- <input type="checkbox" id="cbAllNewOrders"> -->
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="cbTableAll">
                          <label class="custom-control-label" for="cbTableAll"><p></p></label>
                        </div>
                      </th>
                      <th>Order ID</th>
                      <!-- <th>Product</th> -->
                      <th>Total Price</th>
                      <!-- <th>Quantity</th> -->
                      <th>Payment</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Details</th>
                    </tr>
                  </thead>

                  <tbody>

                    <?php

                    //$query = "SELECT orders.* FROM tbl_orders2 orders, tbl_order_products order_products WHERE orders.order_status = 'processing' AND order_products.seller_id = '$sellerId' GROUP BY order_products.order_id";

                    $query = "SELECT orders.* FROM tbl_orders orders, tbl_order_products order_products WHERE orders.order_status = 'processing' AND orders.order_id = order_products.order_id AND order_products.seller_id = '$sellerId' GROUP BY order_products.order_id";

                    $selectOrders = mysqli_query($conn, $query);

                    checkQueryError($selectOrders);

                    while ($row = mysqli_fetch_assoc($selectOrders)) {
                      $order_id = $row['order_id'];
                      $order_total = $row['order_total'];
                      $order_payment = $row['order_payment'];
                      $order_status = $row['order_status'];
                      $order_date_created = $row['order_date_created'];

                      // $user_id = $row['user_id'];

                      // $order_prod_id = $row['order_prod_id'];
                      // $order_prod_price = $row['order_prod_price'];
                      // $order_prod_quantity = $row['order_prod_quantity'];
                      // $order_prod_status = $row['order_prod_status'];

                      // $prod_name = $row['prod_name'];

                      // $totalPrice = $order_prod_price * $order_prod_quantity;


                    ?>




                    <tr>
                      <td>
                        <!-- <input type="checkbox" name="checkBoxArray[]" value="$postId" class="cbNewOrder"> -->
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" name="cbNewOrderArray[]" class="custom-control-input cbTableItem" id="<?php echo $order_id; ?>" value="<?php echo $order_id; ?>">
                          <label class="custom-control-label" for="<?php echo $order_id; ?>"><p></p></label>
                        </div>
                      </td>
                      <td><?php echo $order_id; ?></td>
                      <td><?php echo $order_total; ?></td>
                      <td><?php echo $order_payment; ?></td>
                      <td><?php echo $order_status; ?></td>
                      <td><?php echo $order_date_created; ?></td>
                      <td><a href="shipment_details.php?o_id=<?php echo $order_id; ?>">View Details</a></td>
                    </tr>


                    <?php

                    }




                    ?>

                  </tbody>
                </table>
              </div>
            </div>
          </form>

          

         
    </main>
	</div>
</div>


<?php 








include('php/seller_footer.php');


?>