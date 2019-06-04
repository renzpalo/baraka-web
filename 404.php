<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

$query = "UPDATE tbl_web_stats SET web_views = web_views + 1 WHERE web_id = 1";

$updateViews = mysqli_query($conn, $query);

?>

<title>baraka</title>

<div class="container-fluid">
	<div class="row mt-5 mb-5 p-5">
		
		<h1 class="text-center">Error: 404</h1>
			
	</div>
</div>

<?php



?>
	










<?php 


include('php/includes/footer.php');


mysqli_close($conn);

 ?>