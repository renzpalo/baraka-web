<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

$alertMessage = "";

if (!$_SESSION['user_id']) {
	header("Location: index.php");
}

if (isset($_SESSION['user_id'])) {
	$userId = $_SESSION['user_id'];
}


?>

<title>Profile</title>
	
<div class="container main-page">

	<div class="row mt-5 bg-white">
		<div class="col-sm-3 mt-5">
			<?php include('php/includes/user_sidebar.php'); ?>
		</div>
 
		<div class="col-sm-9">
			<h1>Profile</h1>
			



			
		</div>
	</div>

</div>	








<?php include('php/includes/footer.php'); ?>