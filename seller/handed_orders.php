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


?>

<title>Handed Orders</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/seller_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Handed Orders</h1>

            <?php // echo $alertMessage; ?>

            
          </div>

          <form action="" method="post">
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

                    $query = "SELECT orders.* FROM tbl_orders orders, tbl_order_products order_products WHERE orders.order_status = 'handed' AND orders.order_id = order_products.order_id AND order_products.seller_id = '$sellerId' GROUP BY order_products.order_id";

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