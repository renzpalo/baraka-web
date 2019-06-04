<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

$getSellerId = $_GET['store_id'];


?>

<title>Store</title>

<div class="container-fluid">
	<div class="row">
		<?php

		$query = "SELECT * FROM tbl_sellers WHERE seller_id = '$getSellerId'";

		$selectSeller = mysqli_query($conn, $query);

		checkQueryError($selectSeller);

		while ($row = mysqli_fetch_assoc($selectSeller)) {
			$seller_id = $row['seller_id'];
			$seller_name = $row['seller_name'];
			$seller_info = $row['seller_info'];
			$seller_image = $row['seller_image'];
			$seller_banner = $row['seller_banner'];
		}

		?>
		<div class="col-sm-12 p-0">
			<div class="seller-banner" style="background: url(images/banners/<?php echo $seller_banner; ?>); background-size: cover;">
				<div class="overlay">
					<div class="row pt-5 m-0">
						<div class="col-sm-1 offset-1 seller-logo-cover">
							<img src="images/sellers/<?php echo $seller_image; ?>" alt="" class="rounded-circle seller-logo" width="100" height="100">
						</div>
						<div class="col-sm-4">
							<h1 class=""><?php echo $seller_name; ?></h1>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
	
<div class="container">
	<div class="row p-5">
		<div class="col-sm-9">
			<div class="row">
				<?php
				$query = "SELECT products.*, province.prov_name FROM tbl_products products, tbl_province province WHERE products.prov_id = province.prov_id AND seller_id = '$getSellerId'";

				$searchQuery = mysqli_query($conn, $query);

				checkQueryError($searchQuery);

				while ($row = mysqli_fetch_assoc($searchQuery)) {
					$prodId = $row['prod_id'];
					$prodName = $row['prod_name'];
					$prodPrice = $row['prod_price'];
					$prodRatings = $row['prod_rating'];

					$province = $row['prov_name'];

					$query2 = "SELECT prod_image FROM tbl_product_images WHERE prod_id = '$prodId' ORDER BY prod_image_sequence DESC";

					$selectImages = mysqli_query($conn, $query2);

					$row2 = mysqli_fetch_assoc($selectImages);

					$prodImage = $row2['prod_image'];

					$query4 = "SELECT * FROM tbl_reviews WHERE prod_id = '$prodId' AND rev_status = 'reviewed'";

					$selectRowsCount = mysqli_query($conn, $query4);
					checkQueryError($selectRowsCount);

					$revCount = mysqli_num_rows($selectRowsCount);
				?>

					<div class="col-sm-3 mb-3">
						<div class="card product-card shadow">
							<div class="product-cover">
								<a href="product_info.php?p_id=<?php echo $prodId; ?>">
									<img class="product-image card-img-top img-fluid" src="images/products/<?php echo $prodImage; ?>" alt="Card image cap">
								</a>
							</div>
							
							<div class="card-body">
								<a href="product_info.php?p_id=<?php echo $prodId; ?>">
									<h6 class="card-title product-title  m-0">
										<?php echo substr($prodName, 0, 32); ?>
									</h6>
								</a>
								<p class="card-text product-price  m-0">PHP <?php echo $prodPrice; ?></p>

								<span class="product-rating ">
									<?php

									if ($prodRatings < 0.4) {
										echo "
												<i class='far fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
										";
									} else if ($prodRatings >= 0.5 && $prodRatings <= 1.4) {
										echo "
												<i class='fa fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
										";
									} else if ($prodRatings >= 1.5 && $prodRatings <= 2.4) {
										echo "
												<i class='fa fa-star star-yellow fa-xs'></i>
												<i class='fa fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
										";
									} else if ($prodRatings >= 2.5 && $prodRatings <= 3.4) {
										echo "
												<i class='fa fa-star star-yellow fa-xs'></i>
												<i class='fa fa-star star-yellow fa-xs'></i>
												<i class='fa fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
										";
									} else if ($prodRatings >= 3.5 && $prodRatings <= 4.4) {
										echo "
												<i class='fa fa-star star-yellow fa-xs'></i>
												<i class='fa fa-star star-yellow fa-xs'></i>
												<i class='fa fa-star star-yellow fa-xs'></i>
												<i class='fa fa-star star-yellow fa-xs'></i>
												<i class='far fa-star star-yellow fa-xs'></i>
										";
									} else if ($prodRatings >= 4.5) {
										echo '
												<i class="fa fa-star star-yellow fa-xs"></i>
												<i class="fa fa-star star-yellow fa-xs"></i>
												<i class="fa fa-star star-yellow fa-xs"></i>
												<i class="fa fa-star star-yellow fa-xs"></i>
												<i class="fa fa-star star-yellow fa-xs"></i>
										';
									}

									?>
		<!-- 							<i class="far fa-star fa-xs"></i>
									<i class="far fa-star fa-xs"></i>
									<i class="far fa-star fa-xs"></i>
									<i class="far fa-star fa-xs"></i>
									<i class="far fa-star fa-xs"></i> -->
									
								</span>
								<small class="d-inline">(<?php echo $revCount; ?>)</small>
								
								<small class="product-province  m-0"><?php echo $province; ?></small>
							</div>
						</div>
					</div>

				<?php
				}
				?>
			</div>
		</div>
		<div class="col-sm-3">
			<p class="lead"><?php echo $seller_name ?></p>
			<p><?php echo $seller_info; ?></p>
			<a href="message.php?s_id=<?php echo $getSellerId; ?>" class="btn btn-outline-primary">Send Message</a>
		</div>
	</div>
</div>









<?php include('php/includes/footer.php'); ?>