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

if (isset($_GET['del_wishlist'])) {
	$cartId = $_GET['del_wishlist'];

	$query3 = "DELETE FROM tbl_wishlist WHERE user_id = '$userId' AND prod_id = '$cartId'";

	$deleteWishlist = mysqli_query($conn, $query3);

	checkQueryError($deleteWishlist);

	if ($deleteWishlist) {
		header("Location: wishlist.php");
	}
}

if (isset($GET['add_cart'])) {
	$getProdId = $GET['add_cart'];

	addToCart($getProdId, 1, 1, $userId);

	header("Location: wishlist.php");
}

?>

<title>Wishlist</title>

<div class="container main-page">

	<h1>Wishlist</h1>

	<div class="row">
		<div class="col-sm-12 border rounded shadow m-3 pt-5 pb-5 bg-white">

			<table class="table">
				<thead>
					<tr>
						<th>Image</th>
						<th>Product</th>
						<th>Price</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
			
	
	<?php

	$productArray = array();

	$count = 0;
	$totalPrice = 0;

	$doesNotExist = true;

	$query = "SELECT * FROM tbl_wishlist WHERE user_id = '$userId'";

	$selectCart = mysqli_query($conn, $query);

	$numRows = mysqli_num_rows($selectCart);

	checkQueryError($selectCart);

	while ($row = mysqli_fetch_array($selectCart)) {
		$wish_id = $row['wish_id'];
		$prod_id = $row['prod_id'];
		$user_id = $row['user_id'];

		$doesNotExist = false;
	}

	if ($doesNotExist) {
		
	} else {
		$query2 = "SELECT wishlist.*, products.* FROM tbl_wishlist wishlist, tbl_products products WHERE wishlist.prod_id = products.prod_id AND wishlist.user_id = '$user_id'";

		$selectProducts = mysqli_query($conn, $query2);

		checkQueryError($selectProducts);

		while ($row2 = mysqli_fetch_assoc($selectProducts)) {
			$wish_id = $row2['wish_id'];

			$prod_id = $row2['prod_id'];
			$prod_name = $row2['prod_name'];
			$prod_price = $row2['prod_price'];

			$query3 = "SELECT prod_image FROM tbl_product_images WHERE prod_id = '$prod_id' ORDER BY prod_image_sequence DESC";

			$selectImages = mysqli_query($conn, $query3);

			$row3 = mysqli_fetch_assoc($selectImages);

			$prod_image = $row3['prod_image'];


	?>

			<tr>
				<td style="width: 20%;"><img src='images/products/<?php echo $prod_image; ?>' alt='' class='img-fluid' style="height: 100px;"></td>
				<td style="width: 30%;"><a href="product_info.php?p_id=<?php echo $prod_id; ?>"><?php echo $prod_name ?></a></td>
				<td style="width: 20%;">PHP <?php echo $prod_price; ?></td>
				<td style="width: 20%;">
					<a href="wishlist.php?del_wishlist=<?php echo $prod_id; ?>" class="btn btn-danger"><i class="fas fa-trash"></i></a>
				</td>
			</tr>

	<?php

			$count = $count + 1;
		}

	}


	?>

				</tbody>
			</table>

			
			
		</div>
	</div>


</div>	








<?php 

mysqli_close($conn);

include('php/includes/footer.php');


 ?>