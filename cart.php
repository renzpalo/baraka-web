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

<title>Cart</title>
	

<div class="container main-page">

	<h1>Cart</h1>

	<div class="row bg-white shadow-lg rounded border">
		<div class="col sm-9 pt-5 pb-5">
			<table class="table">
				<thead>
					<tr>
						<th>Image</th>
						<th>Product</th>
						<th>Price</th>
						<th>Qty</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					
			
			<?php

			$productArray = array();

			$count = 0;
			$totalPrice = 0;

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

					$totalPrice = $totalPrice + ($prod_price * $prod_quantity);

					$isCartEmpty = false;

			?>

					<tr>
						<td style="width: 15%;"><img src='images/products/<?php echo $prod_image; ?>' alt='<?php echo $prod_image; ?>' class='img-fluid'></td>
						<td style="width: 30%;"><a href="product_info.php?p_id=<?php echo $prod_id; ?>"><?php echo $prod_name ?></a></td>
						<td style="width: 20%;">PHP <?php echo $prod_price; ?></td>
						<td style="width: 18%;">
							<div class="input-group">
								<div class="input-group-prepend">
									<a href="cart.php?minus_qty=<?php echo $cart_id ?>" class="btn btn-outline-secondary 
										<?php if($prod_quantity <= 1) { echo 'disabled'; } ?>" 
										<?php if($prod_quantity <= 1) { echo 'aria-disabled="true"'; } ?>>
										<i class="fas fa-minus"></i>
									</a>
								</div>
								<input type="text" class="form-control border" placeholder="" aria-label="" aria-describedby="basic-addon1" value="<?php echo $prod_quantity; ?>" disabled>
								<div class="input-group-append">
									<a href="cart.php?add_qty=<?php echo $cart_id ?>" class="btn btn-outline-secondary
										"><i class="fas fa-plus"></i>
									</a>
								</div>
							</div>
							
						</td>
						<td style="width: 20%;">
							<a href="cart.php?del_cart=<?php echo $prod_id; ?>" class="btn btn-danger"><i class="fas fa-trash"></i></a>
						</td>
					</tr>

			<?php

					$count = $count + 1;
				}

				$query3 = "SELECT cart.*, MAX(products.prod_ship_fee) AS high_shipping_fee FROM tbl_cart cart, tbl_products products WHERE cart.prod_id = products.prod_id AND cart.user_id = '$user_id'";

				$selectShipFee = mysqli_query($conn, $query3);

				checkQueryError($selectShipFee);

				while ($row3 = mysqli_fetch_assoc($selectShipFee)) {
					$prod_ship_fee = $row3['high_shipping_fee'];	
				}

			}

			?>

				</tbody>
			</table>
		</div>
		<div class="col-sm-3 border-left pt-5">
			<h2 class="text-center mb-5">Cart Summary</h2>
			<hr>
			<div class="row">
				<div class="col">
					<p class="lead d-inline"><b>Cart Total: </b></p><p class="lead d-inline float-right">PHP <?php echo $totalPrice ?></p>
				</div>
				
			</div>
			<?php
			if ($isCartEmpty) {
				echo "<a href='place_order.php' class='btn btn-primary btn-block disabled mb-5' role='button' aria-disabled='true'>Check Out</a>";
			} else {
				echo "<a href='place_order.php' class='btn btn-primary btn-block mb-5'>Check Out</a>";
			}

			?>
		</div>
	</div>


</div>	








<?php 

if (isset($_GET['minus_qty'])) {
	$getCartId = $_GET['minus_qty'];

	$query = "UPDATE tbl_cart SET cart_prod_quantity = cart_prod_quantity - 1 WHERE cart_id = '$getCartId'";
	$updateCartQty = mysqli_query($conn, $query);
	checkQueryError($updateCartQty);

	if ($updateCartQty) {
		header("Location: cart.php");
	}
}

if (isset($_GET['add_qty'])) {
	$getCartId = $_GET['add_qty'];

	$query = "UPDATE tbl_cart SET cart_prod_quantity = cart_prod_quantity + 1 WHERE cart_id = '$getCartId'";
	$updateCartQty = mysqli_query($conn, $query);
	checkQueryError($updateCartQty);

	if ($updateCartQty) {
		header("Location: cart.php");
	}
}

include('php/includes/footer.php');


 ?>