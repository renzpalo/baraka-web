<?php
include('php/logistics_header.php');
include('../../php/includes/connect.php');
include('php/logistics_dash_nav.php');

if (!$_SESSION['log_id']) {
	header("Location: ../index.php");
}

$totalShipments = 0;
$newShipments = 0;
$shippedShipments = 0;
$deliveredShipments = 0;
$redeliverShipments = 0;
$unreceivedShipments = 0;

$query1 = "SELECT * FROM tbl_shippings WHERE shipping_status = 'unreceived'";
$selectNumRows = mysqli_query($conn, $query1);
$unreceivedShipments = mysqli_num_rows($selectNumRows);

$query2 = "SELECT * FROM tbl_shippings WHERE shipping_status = 'redeliver'";
$selectNumRows = mysqli_query($conn, $query2);
$redeliverShipments = mysqli_num_rows($selectNumRows);

?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<div class="container-fluid">
	<div class="row">
		<?php include('php/logistics_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Dashboard</h1>
			</div>
			<div class="row">
				<div class="col-sm-3">
					<div class="card text-white bg-primary mb-3">
						<div class="card-header"><b>TOTAL SHIPMENTS</b></div>
						<div class="card-body">
							<?php 

							$query = "SELECT * FROM tbl_shippings";

                            $selectNumRows = mysqli_query($conn, $query);

                            $totalShipments = mysqli_num_rows($selectNumRows);

                            echo "<h2 class='card-title'><i class='fas fa-boxes mb-2'></i> $totalShipments</h2>";

							?>
							<!--<p class="card-text"><a href="" class="text-white">View Details <i class="fas fa-chevron-circle-right ml-1 mb-1"></i></a></p>-->
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="card text-white bg-warning mb-3">
						<div class="card-header"><b>NEW</b></div>
						<div class="card-body">
							<?php 

							$query = "SELECT * FROM tbl_shippings WHERE shipping_status = 'handed'";

                            $selectNumRows = mysqli_query($conn, $query);

                            $newShipments = mysqli_num_rows($selectNumRows);

                            echo "<h2 class='card-title'><i class='fas fa-dolly mb-2'></i> $newShipments</h2>";

							?>
							<!--<p class="card-text"><a href="" class="text-white">View Details <i class="fas fa-chevron-circle-right ml-1 mb-1"></i></a></p>-->
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="card text-white bg-success mb-3">
						<div class="card-header"><b>SHIPPED</b></div>
						<div class="card-body">
							<?php 

							$query = "SELECT * FROM tbl_shippings WHERE shipping_status = 'shipped'";

                            $selectNumRows = mysqli_query($conn, $query);

                            $shippedShipments = mysqli_num_rows($selectNumRows);

                            echo "<h2 class='card-title'><i class='fas fa-shipping-fast mb-2'></i> $shippedShipments</h2>";

							?>
							<!--<p class="card-text"><a href="" class="text-white">View Details <i class="fas fa-chevron-circle-right ml-1 mb-1"></i></a></p>-->
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="card text-white bg-danger mb-3">
						<div class="card-header"><b>DELIVERED</b></div>
						<div class="card-body">
							<?php 

							$query = "SELECT * FROM tbl_shippings WHERE shipping_status = 'delivered'";

                            $selectNumRows = mysqli_query($conn, $query);

                            $deliveredShipments = mysqli_num_rows($selectNumRows);

                            echo "<h2 class='card-title'><i class='fas fa-truck mb-2'></i> $deliveredShipments</h2>";

							?>
							<!--<p class="card-text"><a href="" class="text-white">View Details <i class="fas fa-chevron-circle-right ml-1 mb-1"></i></a></p>-->
						</div>
					</div>
				</div>

			</div>
			<script type="text/javascript">
			  google.charts.load('current', {'packages':['bar']});
			  google.charts.setOnLoadCallback(drawChart);

			  function drawChart() {
			    var data = google.visualization.arrayToDataTable([
			      ['Year', 'New', 'Shipped', 'Delivered', 'Redeliver', 'Unreceived'],
			      ['2019', 
			      <?php echo $newShipments; ?>, 
			      <?php echo $shippedShipments; ?>, 
			      <?php echo $deliveredShipments; ?>, 
			      <?php echo $redeliverShipments; ?>, 
			      <?php echo $unreceivedShipments; ?>]
			    ]);

			    var options = {
			      chart: {
			        title: 'Shipments',
			        subtitle: 'New, Shipped, Delivered, Redeliver and Unreceived Shipments: 2019-Present',
			      }
			    };

			    var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

			    chart.draw(data, google.charts.Bar.convertOptions(options));
			  }
			</script>
			<div class="row">
				<div id="columnchart_material" style="width: 100%; height: 500px;" class="m-5"></div>
			</div>
		</main>
	</div>
</div>


<?php include('php/logistics_footer.php'); ?>