<div id="featured-products" class="featured">
	<div class="container">
		<h4>Features Products</h4>

		<!-- Cards -->
		<div class="row">
			<?php


			$query = "SELECT fprods.*, prods.*, prov.prov_name FROM tbl_featured_products fprods, tbl_products prods, tbl_province prov WHERE fprods.prod_id = prods.prod_id AND prov.prov_id = prods.prov_id ORDER BY fprods.fprod_priority ASC";

			//$query = "SELECT * FROM tbl_products";

			$selectFeaturedProducts = mysqli_query($conn, $query);

			checkQueryError($selectFeaturedProducts);

			while ($row = mysqli_fetch_assoc($selectFeaturedProducts)) {
				$prodId = $row['prod_id'];
				$prodName = $row['prod_name'];
				$prodPrice = $row['prod_price'];
				$prodProvince = $row['prov_name'];
				$prodRatings = $row['prod_rating'];

				$query2 = "SELECT prod_image FROM tbl_product_images WHERE prod_id = '$prodId' ORDER BY prod_image_sequence DESC";

				$selectImages = mysqli_query($conn, $query2);

				$row2 = mysqli_fetch_assoc($selectImages);

				$prodImage = $row2['prod_image'];

				$query4 = "SELECT * FROM tbl_reviews WHERE prod_id = '$prodId' AND rev_status = 'reviewed'";

				$selectRowsCount = mysqli_query($conn, $query4);
				checkQueryError($selectRowsCount);

				$revCount = mysqli_num_rows($selectRowsCount);

			?>

			<div class="col-md-2 mb-3">
				<div class="card product-card shadow">
					<div class="product-cover">
						<a href="product_info.php?p_id=<?php echo $prodId; ?>">
							<img class="product-image card-img-top img-fluid" src="images/products/<?php echo $prodImage; ?>" alt="<?php echo $prodImage; ?>">
						</a>
					</div>
					
					<div class="card-body p-3">
						<a href="product_info.php?p_id=<?php echo $prodId; ?>">
							<h6 class="card-title product-title m-0">
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

						<small class="product-province  m-0"><?php echo $prodProvince; ?></small>
					</div>
				</div>
			</div>

			<?php


			}


			?>
			
			
		</div>
	</div>
</div>