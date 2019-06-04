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

<title>Reviews</title>
	
<div class="container main-page">

	<div class="row mt-5 bg-white rounded shadow-lg pb-5 pt-5">
		<div class="col-sm-3 mt-5">
			<?php include('php/includes/user_sidebar.php'); ?>
		</div>
 
		<div class="col-sm-9">
			<h1>Reviews</h1>

			<table class="table">
				<thead>
					<tr>
						<th>Image</th>
						<th>Product</th>
						<th>Seller</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php

					// $query = "SELECT reviews.*, products.* FROM tbl_reviews reviews, tbl_products products WHERE reviews.prod_id = products.prod_id AND reviews.user_id = '$userId'";

					$query = "SELECT reviews.rev_id, products.prod_id, products.prod_name, seller.seller_id, seller.seller_name FROM tbl_reviews reviews, tbl_products products, tbl_sellers seller WHERE products.seller_id = seller.seller_id AND reviews.prod_id = products.prod_id AND reviews.user_id = '$userId'";

					$selectReviews = mysqli_query($conn, $query);

					checkQueryError($selectReviews);

					while ($row = mysqli_fetch_assoc($selectReviews)) {
						$rev_id = $row['rev_id'];

						$prod_id = $row['prod_id'];
						$prod_name = $row['prod_name'];

						$seller_id = $row['seller_id'];
						$seller_name = $row['seller_name'];

						$query2 = "SELECT prod_image FROM tbl_product_images WHERE prod_id = '$prod_id' ORDER BY prod_image_sequence DESC";
						$selectImages = mysqli_query($conn, $query2);
						$row2 = mysqli_fetch_assoc($selectImages);

						$prod_image = $row2['prod_image'];

					?>


					<tr style="height: 100px;">
						<td style="height: 100px;"><img src="images/products/<?php echo $prod_image; ?>" alt="<?php echo $prod_image; ?>"  style="height: 75px;"></td>
						<td><?php echo $prod_name; ?></td>
						<td><a href=""><?php echo $seller_name; ?></a></td>
						<td><a href="write_review.php?r_id=<?php echo $rev_id; ?>">Write Review</a></td>
					</tr>

					<?php
					}


					?>
					
				</tbody>
			</table>



			
		</div>
	</div>

</div>	








<?php include('php/includes/footer.php'); ?>