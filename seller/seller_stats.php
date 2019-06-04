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

<title>Product Stats</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/seller_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Statistics</h1>

          </div>

          <div class="row">
            <div class="col-sm-12">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Views</th>
                    <th>Orders</th>
                    <th>Sales</th>
                    <th>Earnings</th>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  $query = "SELECT stats.*, products.prod_name FROM tbl_product_stats stats, tbl_products products WHERE products.prod_id = stats.prod_id AND products.seller_id = '$sellerId'";

                  $selectProductStats = mysqli_query($conn, $query);

                  checkQueryError($selectProductStats);

                  while ($row = mysqli_fetch_assoc($selectProductStats)) {
                    $stats_id = $row['stats_id'];
                    $stats_views = $row['stats_views'];
                    $stats_orders = $row['stats_orders'];
                    $stats_sales = $row['stats_sales'];
                    $stats_earnings = $row['stats_earnings'];

                    $prod_name = $row['prod_name'];

                  ?>

                  <tr>
                    <td><?php echo $stats_id; ?></td>
                    <td><?php echo $prod_name; ?></td>
                    <td><?php echo $stats_views; ?></td>
                    <td><?php echo $stats_orders; ?></td>
                    <td><?php echo $stats_sales; ?></td>
                    <td>PHP <?php echo $stats_earnings; ?></td>
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