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

if (isset($_GET['o_id'])) {
	$getOrderId = $_GET['o_id'];
}


?>

<title>Order Success</title>
	
<div class="container main-page">

	<div class="row">
		<div class="col-sm-10 offset-1 signin-form-box rounded mt-5 shadow-lg mb-5 bg-white rounded border">
			<div class="row">
				<div class="col-sm-6 signin-form-box-img customer-signin-form-box-img">

				</div>
				<div class="col-sm-6 p-5 text-center">
					<h1 class="">Order Success</h1>

					<p class="lead"><b>Order ID</b> <a href="order_details.php?o_id=<?php echo $getOrderId; ?>"><?php echo $getOrderId; ?></a></p>

					<p class="lead pt-5 pr-5 pl-5">Thank you for supporting local products.</p>

					
					
					<a href="index.php" class="btn btn-primary btn-block mt-5">Continue Shopping</a>
				</div>
			</div>

		</div>
	</div>

</div>	








<?php include('php/includes/footer.php'); ?>