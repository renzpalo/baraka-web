<?php
include('php/seller_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');
include('php/seller_dash_nav.php');

if (!$_SESSION['seller_name']) {
	header("Location: index.php");
}

$sellerId = $_SESSION['seller_id'];

?>

<title>Payments</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/seller_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Payments</h1>
        
      </div>
      <div class="row">
        <div class="col-sm-12">
          <table class="table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Paid By</th>
                <th>Received by</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $query = "SELECT payments.*, products.prod_name, user.user_fullname FROM tbl_payments payments, tbl_products products, tbl_user_profile user WHERE payments.user_id = user.user_id AND payments.prod_id = products.prod_id AND payments.seller_id = '$sellerId'";

              $selectReviews = mysqli_query($conn, $query);

              checkQueryError($selectReviews);

              while ($row = mysqli_fetch_assoc($selectReviews)) {
                $payment_id = $row['payment_id'];
                $payment_amount = $row['payment_amount'];
                $payment_date = $row['payment_date'];

                $prod_name = $row['prod_name'];
                $prod_quantity = $row['prod_quantity'];
                $order_id = $row['order_id'];
                $user_id = $row['user_id'];
                $user_fullname = $row['user_fullname'];

                $log_name = $row['log_name'];

              ?>
              <tr>
                <td><?php echo $order_id; ?></td>
                <td><?php echo $prod_name; ?></td>
                <td><?php echo $prod_quantity; ?></td>
                <td>PHP <?php echo $payment_amount; ?></td>
                <td><?php echo $payment_date; ?></td>
                <td><?php echo $user_fullname; ?></td>
                <td><?php echo $log_name; ?></td>
              </tr>

              <?php
              }
              ?>
              
            </tbody>
          </table>
        </div>
      </div>
    </main>
	</div>
</div>


<?php include('php/seller_footer.php'); ?>