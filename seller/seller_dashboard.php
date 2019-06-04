<?php
include('php/seller_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');
include('php/seller_dash_nav.php');

if (!$_SESSION['seller_name']) {
	header("Location: index.php");
}

$sellerId = $_SESSION['seller_id'];

$totalViews = 0;
$totalOrders = 0;
$totalSales = 0;
$totalEarnings = 0;

$query = "SELECT stats.stats_earnings FROM tbl_product_stats stats, tbl_products products WHERE products.seller_id = '$sellerId' AND products.prod_id = stats.prod_id";

$selectViews = mysqli_query($conn, $query);

checkQueryError($selectViews);

$totalViews = 0;

while ($row = mysqli_fetch_assoc($selectViews)) {
  $stats_earnings = $row['stats_earnings'];

  $totalEarnings = $totalEarnings + $stats_earnings;
}

?>

<title>Seller Dashboard</title>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<div class="container-fluid">
	<div class="row">
		<?php include('php/seller_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard</h1>

          </div>
          <div class="row">
            <div class="col-sm-3">
              <div class="card text-white bg-warning mb-3">
                <div class="card-header"><b>ORDERS</b></div>
                <div class="card-body">
                  <?php 

                  $query = "SELECT orders.order_id, order_products.seller_id FROM tbl_orders orders, tbl_order_products order_products WHERE orders.order_id = order_products.order_id AND order_products.seller_id = '$sellerId'";

                  $selectNumRows = mysqli_query($conn, $query);

                  checkQueryError($selectNumRows);

                  $totalOrders = mysqli_num_rows($selectNumRows);

                  echo "<h2 class='card-title'><i class='fas fa-shopping-basket mb-2'></i> $totalOrders</h2>";

                  ?>
                  <!--<p class="card-text"><a href="" class="text-white">View Details <i class="fas fa-chevron-circle-right ml-1 mb-1"></i></a></p>-->
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="card text-white bg-danger mb-3">
                <div class="card-header"><b>PRODUCTS</b></div>
                <div class="card-body">
                  <?php 

                  $query = "SELECT * FROM tbl_products WHERE seller_id = '$sellerId'";

                  $selectNumRows = mysqli_query($conn, $query);

                  $rowsCount = mysqli_num_rows($selectNumRows);

                  echo "<h2 class='card-title'><i class='fas fa-users mb-2'></i> $rowsCount</h2>";

                  ?>
                  <!--<p class="card-text"><a href="" class="text-white">View Details <i class="fas fa-chevron-circle-right ml-1 mb-1"></i></a></p>-->
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="card text-white bg-primary mb-3">
                <div class="card-header"><b>PRODUCT VIEWS</b></div>
                <div class="card-body">
                  <?php 

                  $query = "SELECT stats.stats_views FROM tbl_product_stats stats, tbl_products products WHERE products.seller_id = '$sellerId' AND products.prod_id = stats.prod_id";

                  $selectViews = mysqli_query($conn, $query);

                  checkQueryError($selectViews);

                  $totalViews = 0;

                  while ($row = mysqli_fetch_assoc($selectViews)) {
                    $stats_views = $row['stats_views'];

                    $totalViews = $totalViews + $stats_views;
                  }

                  echo "<h2 class='card-title'><i class='far fa-eye mb-2'></i> $totalViews</h2>";

                  ?>
                  <!--<p class="card-text"><a href="" class="text-white">View Details <i class="fas fa-chevron-circle-right ml-1 mb-1"></i></a></p>-->
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="card text-white bg-success mb-3">
                <div class="card-header"><b>SALES</b></div>
                <div class="card-body">
                  <?php 

                  $query = "SELECT stats.stats_sales FROM tbl_product_stats stats, tbl_products products WHERE products.seller_id = '$sellerId' AND products.prod_id = stats.prod_id";

                  $selectSales = mysqli_query($conn, $query);

                  checkQueryError($selectSales);

                  while ($row = mysqli_fetch_assoc($selectSales)) {
                    $stats_sales = $row['stats_sales'];

                    $totalSales = $totalSales + $stats_sales;
                  }

                  echo "<h2 class='card-title'><i class='fas fa-money-bill-wave mb-2 mr-2'></i>$totalSales</h2>";

                  ?>
                  <!--<p class="card-text"><a href="" class="text-white">View Details <i class="fas fa-chevron-circle-right ml-1 mb-1"></i></a></p>-->
                </div>
              </div>
            </div>



            
          </div>

          <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
              var data = google.visualization.arrayToDataTable([
                ['Year', 'Sales', 'Expenses'],
                ['2004',  1000,      400],
                ['2005',  1170,      460],
                ['2006',  660,       1120],
                ['2007',  1030,      540]
              ]);

              var options = {
                title: 'Seller Chart',
                curveType: 'function',
                legend: { position: 'bottom' }
              };

              var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

              chart.draw(data, options);
            }
          </script>

          <script type="text/javascript">
            google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
              var data = google.visualization.arrayToDataTable([
                ['Year', 'Views', 'Orders', 'Sales', 'Earnings'],
                ['2019', 
                <?php echo $totalViews;  ?>, 
                <?php echo $totalOrders;  ?>, 
                <?php echo $totalSales;  ?>, 
                <?php echo $totalEarnings;  ?>]
              ]);

              var options = {
                chart: {
                  title: 'Product Stats',
                  subtitle: 'Views, Orders, Sales, and Earnings: 2019-Present',
                }
              };

              var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

              chart.draw(data, google.charts.Bar.convertOptions(options));
            }
          </script>
          <div class="row">
            <!-- <div id="curve_chart" style="width: 100%; height: 500px"></div> -->
          </div>
          <div class="row mt-5">
            <div id="columnchart_material" style="width: 80%; height: 500px;" class="ml-5"></div>
          </div>
    </main>
	</div>
</div>


<?php include('php/seller_footer.php'); ?>