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

    switch ($orderStatusOption) {
      case 'Processing':
        $query2 = "UPDATE tbl_order_products SET order_prod_status = '$orderStatusOption' WHERE order_prod_id = '$orderIdValue'";

        $updateOrderStatus = mysqli_query($conn, $query2);

        checkQueryError($updateOrderStatus);

        if ($updateOrderStatus) {
          // $alertMessage = "<div class='alert alert-success' role='alert'>
          //                     Order approved.
          //                  </div>";

          // Check if all rows are true.

          $query3 = "SELECT order_id FROM tbl_order_products WHERE order_prod_id = '$orderIdValue'";
          
          $selectOrderId = mysqli_query($conn, $query3);

          checkQueryError($selectOrderId);

          $row = mysqli_fetch_assoc($selectOrderId);

          $orderId = $row['order_id'];

          // -------------------------------

          $query4 = "SELECT order_prod_status FROM tbl_order_products WHERE order_prod_status = 'pending' AND order_id = '$orderId'";

          $selectOrderStatus = mysqli_query($conn, $query4);

          checkQueryError($selectOrderStatus);

          $isApproved = true;

          while ($row2 = mysqli_fetch_assoc($selectOrderStatus)) {
            $order_prod_status = $row2['order_prod_status'];

            $isApproved = false;
          }

          if ($isApproved) {
            $query5 = "UPDATE tbl_orders SET order_status = 'Processing' WHERE order_id = '$orderId'";

            $updateOrder = mysqli_query($conn, $query5);

            checkQueryError($updateOrder);

            if ($updateOrder) {
              $query7 = "SELECT products.prod_name FROM tbl_order_products order_products, tbl_products products WHERE order_products.prod_id = 
              products.prod_id AND order_products.order_id = '$orderId'";

              $selectProdName = mysqli_query($conn, $query7);
              checkQueryError($selectProdName);
              $row7 = mysqli_fetch_assoc($selectProdName);
              $prod_name = $row7['prod_name'];


              $orderStatus = "Packed";
              $orderUpdateInfo = "Your item/s ($prod_name) has been packed and being handed over to our logistics partner.";

              $query6 = "INSERT INTO tbl_order_updates (order_update_status, order_update_info, order_update_by, order_update_date, order_id) VALUES ('$orderStatus', '$orderUpdateInfo', '$sellerName', '$date', '$orderId')";

              $insertUpdateStatus = mysqli_query($conn, $query6);

              checkQueryError($insertUpdateStatus);

              if ($insertUpdateStatus) {
                $alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                Product posted.
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                  <span aria-hidden='true'>&times;</span>
                                </button>
                               </div>";
              }

            } else {

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

<title>New Orders</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/seller_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">New Orders</h1>

            <?php // echo $alertMessage; ?>

            
          </div>

          <form action="" method="post">
            <div class="row mb-3">
              <div class="col-sm-4">
                <select name="order_status_option" id="" class="form-control">
                  <option value="" selected disabled>Select</option>
                  <option value="Processing">Confirm</option>
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
                      <th>Product</th>
                      <th>Total Price</th>
                      <th>Quantity</th>
                      <th>Payment</th>
                      <th>Status</th>
                      <th>Date</th>
                      <!-- <th>Action</th> -->
                    </tr>
                  </thead>

                  <tbody>

                    <?php

                    // $query = "SELECT orders.*, order_products.* FROM tbl_orders orders, tbl_order_products order_products WHERE orders.order_id = order_products.order_id AND order_products.seller_id = '$sellerId'";

                    $query = "SELECT orders.*, order_products.*, products.* FROM tbl_orders orders, tbl_order_products order_products, tbl_products products WHERE orders.order_status = 'pending' AND products.prod_id = order_products.prod_id AND orders.order_id = order_products.order_id AND order_products.seller_id = '$sellerId'";

                    $selectOrders = mysqli_query($conn, $query);

                    checkQueryError($selectOrders);

                    while ($row = mysqli_fetch_assoc($selectOrders)) {
                      $order_id = $row['order_id'];

                      $order_payment = $row['order_payment'];
                      $order_status = $row['order_status'];
                      $order_date_created = $row['order_date_created'];

                      $user_id = $row['user_id'];

                      $order_prod_id = $row['order_prod_id'];
                      $order_prod_price = $row['order_prod_price'];
                      $order_prod_quantity = $row['order_prod_quantity'];
                      $order_prod_status = $row['order_prod_status'];

                      $prod_name = $row['prod_name'];

                      $totalPrice = $order_prod_price * $order_prod_quantity;


                    ?>




                    <tr>
                      <td>
                        <!-- <input type="checkbox" name="checkBoxArray[]" value="$postId" class="cbNewOrder"> -->
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" name="cbNewOrderArray[]" class="custom-control-input cbTableItem" id="<?php echo $order_prod_id; ?>" value="<?php echo $order_prod_id; ?>">
                          <label class="custom-control-label" for="<?php echo $order_prod_id; ?>"><p></p></label>
                        </div>
                      </td>
                      <td><?php echo $order_prod_id; ?></td>
                      <td><?php echo $prod_name; ?></td>
                      <td><?php echo $totalPrice; ?></td>
                      <td><?php echo $order_prod_quantity; ?></td>
                      <td><?php echo $order_payment; ?></td>
                      <td><?php echo $order_prod_status; ?></td>
                      <td><?php echo $order_date_created; ?></td>
                      <!-- <td><?php // echo $order_id; ?></td> -->
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