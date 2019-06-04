<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

if (!$_SESSION['user_id']) {
	header("Location: signin.php");
}

$userId = $_SESSION['user_id'];

$prodImageUrl = "images/products/";

if (isset($_GET['del_cart'])) {
	$cartId = $_GET['del_cart'];

	$query3 = "DELETE FROM tbl_cart WHERE user_id = '$userId' AND prod_id = '$cartId'";

	$deleteCart = mysqli_query($conn, $query3);

	checkQueryError($deleteCart);

	if ($deleteCart) {
		header("Location: cart.php");
	}
}

if (isset($GET['add_wishlist'])) {
	$getProdId = $GET['add_wishlist'];

	addToWishlist($getProdId, 1, 1, $userId);

	header("Location: cart.php");
}

$isCartEmpty = true;

?>

<title>Place Order</title>

<div class="container main-page">

	<h1>Place Order</h1>

	<div class="row bg-white shadow-lg rounded border">
		<div class="col sm-9 pt-5 pb-5">
			<table class="table">
				<thead>
					<tr>
						<th>Image</th>
						<th>Product</th>
						<th>Price</th>
						<th>Qty</th>
					</tr>
				</thead>
				<tbody>
					
			
			<?php

			$productArray = array();

			$count = 0;
			$totalPrice = 0;
			$subTotal = 0;

			$doesNotExist = true;

			$query = "SELECT * FROM tbl_cart WHERE user_id = '$userId'";

			$selectCart = mysqli_query($conn, $query);

			$numRows = mysqli_num_rows($selectCart);

			checkQueryError($selectCart);

			while ($row = mysqli_fetch_array($selectCart)) {
				$cart_id = $row['cart_id'];
				$prod_id = $row['prod_id'];
				$user_id = $row['user_id'];

				$doesNotExist = false;
			}

			if ($doesNotExist) {
				
			} else {
				$query2 = "SELECT cart.*, products.* FROM tbl_cart cart, tbl_products products WHERE cart.prod_id = products.prod_id AND cart.user_id = '$user_id'";

				$selectProducts = mysqli_query($conn, $query2);

				checkQueryError($selectProducts);

				while ($row2 = mysqli_fetch_assoc($selectProducts)) {
					$cart_id = $row2['cart_id'];

					$prod_id = $row2['prod_id'];
					$prod_name = $row2['prod_name'];
					$prod_price = $row2['prod_price'];

					$prod_quantity = $row2['cart_prod_quantity'];

					$query3 = "SELECT prod_image FROM tbl_product_images WHERE prod_id = '$prod_id' ORDER BY prod_image_sequence DESC";

					$selectImages = mysqli_query($conn, $query3);

					$row3 = mysqli_fetch_assoc($selectImages);

					$prod_image = $row3['prod_image'];

					$subTotal = $subTotal + ($prod_price * $prod_quantity);

					$isCartEmpty = false;

			?>

					<tr>
						<td style="width: 20%;"><img src='images/products/<?php echo $prod_image; ?>' alt='' class='img-fluid'></td>
						<td style="width: 30%;"><a href="product_info.php?p_id=<?php echo $prod_id; ?>"><?php echo $prod_name ?></a></td>
						<td style="width: 20%;">PHP <?php echo $prod_price; ?></td>
						<td style="width: 12%;">
							<?php echo $prod_quantity; ?>
						</td>
					</tr>

			<?php

					$count = $count + 1;
				}

				$query3 = "SELECT cart.*, products.prod_ship_fee FROM tbl_cart cart, tbl_products products WHERE cart.prod_id = products.prod_id AND cart.user_id = '$user_id' GROUP BY products.seller_id";

				$selectShipFee = mysqli_query($conn, $query3);

				checkQueryError($selectShipFee);

				$totalShipFee = 0;

				while ($row3 = mysqli_fetch_assoc($selectShipFee)) {
					$prod_ship_fee = $row3['prod_ship_fee'];

					$totalShipFee = $totalShipFee + $prod_ship_fee;
				}

				$totalPrice = $subTotal + $totalShipFee;

				$_SESSION['total_price'] = $totalPrice;
				$_SESSION['shipping_fee'] = $totalShipFee;

			}

			?>

				</tbody>
			</table>
		</div>
		<div class="col-sm-3 border-left pt-5">
			<h3 class="mb-5 text-center">Order Summary</h3>
			<div class="row">
				<div class="col">
					<b>Sub Total: </b><p class="d-inline float-right">PHP <?php echo $subTotal ?></p>
				</div>
				
			</div>
			<div class="row">
				<div class="col">
					<b>Shipping Fee: </b><p class="d-inline float-right">PHP <?php echo $totalShipFee ?></p>
				</div>
				
			</div>
			
			
			<hr>
			<div class="row">
				<div class="col">
					<p class="lead d-inline"><b>Order Total: </b></p><p class="lead d-inline float-right">PHP <?php echo $totalPrice ?></p>
				</div>
				
			</div>
			
			<?php 

			if ($isCartEmpty) {
				echo "<a href='payment.php' class='btn btn-primary btn-block disabled mb-5' role='button' aria-disabled='true'>Place Order</a>";
			} else {
				echo "<a href='payment.php' class='btn btn-primary btn-block mb-5'>Place Order</a>";
			}

			?>
		</div>
	</div>


</div>	








<?php 







include('php/includes/footer.php');



 ?>