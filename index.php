<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');


include('php/includes/carousel.php');

$query = "UPDATE tbl_web_stats SET web_views = web_views + 1 WHERE web_id = 1";

$updateViews = mysqli_query($conn, $query);

?>

<title>baraka</title>

<div class="container-fluid">
	<div class="row mt-5 mb-5 bg-white p-5">
		<div class="col-sm-2 offset-4">
			<img src="images/android-mockup2.jpg" alt="" class="img-fluid">
		</div>
		<div class="col-sm-4">
			<p class="lead mt-5"><b>baraka Mobile App</b></p>
			<p>Support Local Products by ordering on our mobile app from your Android Device.</p>
			<!-- <button type="submit" class="btn btn-primary btn-lg mt-5">Download Now</button> -->
			<a href="download_app.php" class="btn btn-primary mt-5" target="_blank">Download Now</a>
		</div>
			
	</div>
</div>

<?php

include('php/includes/featured_products.php');

include('php/includes/featured_stores.php');

?>
	










<?php 


include('php/includes/footer.php');


mysqli_close($conn);

 ?>