<?php
include('php/admin_header.php');
include('../php/includes/connect.php');
include('php/admin_dash_nav.php');

if (!$_SESSION['admin_name']) {
	header("Location: index.php");
}

$sellerCount = 0;
$userCount = 0;
$orderCount = 0;
$productCount = 0;
$visits = 0;


?>

<title>Dashboard</title>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="container-fluid">
	<div class="row">
		<?php include('php/admin_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Dashboard</h1>

			</div>

			<div class="row">
				<div class="col-sm-3">
					<div class="card text-white bg-primary mb-3">
						<div class="card-header"><b>USERS</b></div>
						<div class="card-body">
							<?php 

							$query = "SELECT * FROM tbl_user_profile";

                            $selectNumRows = mysqli_query($conn, $query);

                            $userCount = mysqli_num_rows($selectNumRows);

                            echo "<h2 class='card-title'><i class='fas fa-users mb-2'></i> $userCount</h2>";

							?>
							<!--<p class="card-text"><a href="" class="text-white">View Details <i class="fas fa-chevron-circle-right ml-1 mb-1"></i></a></p>-->
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="card text-white bg-success mb-3">
						<div class="card-header"><b>SELLERS</b></div>
						<div class="card-body">
							<?php 

							$query = "SELECT * FROM tbl_sellers";

                            $selectNumRows = mysqli_query($conn, $query);

                            $sellerCount = mysqli_num_rows($selectNumRows);

                            echo "<h2 class='card-title'><i class='fas fa-user-tie mb-2'></i> $sellerCount</h2>";

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

							$query = "SELECT * FROM tbl_products";

                            $selectNumRows = mysqli_query($conn, $query);

                            $productCount = mysqli_num_rows($selectNumRows);

                            echo "<h2 class='card-title'><i class='fas fa-users mb-2'></i> $productCount</h2>";

							?>
							<!--<p class="card-text"><a href="" class="text-white">View Details <i class="fas fa-chevron-circle-right ml-1 mb-1"></i></a></p>-->
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="card text-white bg-warning mb-3">
						<div class="card-header"><b>ORDERS</b></div>
						<div class="card-body">
							<?php 

							$query = "SELECT * FROM tbl_orders";

                            $selectNumRows = mysqli_query($conn, $query);

                            $orderCount = mysqli_num_rows($selectNumRows);

                            echo "<h2 class='card-title'><i class='fas fa-shopping-basket mb-2'></i> $orderCount</h2>";

							?>
							<!--<p class="card-text"><a href="" class="text-white">View Details <i class="fas fa-chevron-circle-right ml-1 mb-1"></i></a></p>-->
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div id="columnchart_material" style="width: 100%; height: 500px;" class="m-5"></div>
			</div>

			<script type="text/javascript">
			  google.charts.load("current", {packages:["corechart"]});
			  google.charts.setOnLoadCallback(drawChart);
			  function drawChart() {
			    var data = google.visualization.arrayToDataTable([
			      ['Users', 'User Accounts'],
			      ['Buyers', <?php echo $userCount; ?>],
			      ['Sellers', <?php echo $sellerCount; ?>]
			    ]);

			  var options = {
			    legend: 'none',
			    pieSliceText: 'label',
			    title: 'Buyers and Sellers Pie Chart',
			    pieStartAngle: 100,
			  };

			    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
			    chart.draw(data, options);
			  }
			</script>

			<script type="text/javascript">
			  google.charts.load('current', {'packages':['bar']});
			  google.charts.setOnLoadCallback(drawChart);

			  function drawChart() {
			    var data = google.visualization.arrayToDataTable([
			      ['Year', 'Users', 'Sellers', 'Products', 'Orders'],
			      ['2019', 
			      <?php echo $userCount; ?>, 
			      <?php echo $sellerCount; ?>, 
			      <?php echo $productCount; ?>, 
			      <?php echo $orderCount; ?>]
			    ]);

			    var options = {
			      chart: {
			        title: 'baraka Stats',
			        subtitle: 'Users, Sellers, Products and Orders: 2019-Present',
			      }
			    };

			    var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

			    chart.draw(data, google.charts.Bar.convertOptions(options));
			  }
			</script>

			<div class="row">
				<div id="piechart" style="width: 900px; height: 500px;"></div>
			</div>
			<div class="row">
				<!-- <div id="donutchart" style="width: 900px; height: 500px;"></div> -->
			</div>
		</main>
	</div>
</div>


<?php include('php/admin_footer.php'); ?>