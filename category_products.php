<?php 
include('php/includes/header.php');
include('php/includes/connect.php');
include('php/functions.php');
include('php/includes/top-navbar.php');
include('php/includes/navbar.php');



$prodImageUrl = "images/products/";

$getCatId = $_GET['cat_id'];


?>

<title>Shop by Categories</title>

<div class="container main-page search-product">

	

	<div class="row">
		<div class="col-sm-3">
			<div class="product-sidebar mt-5">
				<p class="lead">Categories</p>
				<ul>
					<?php

					$query = "SELECT * FROM tbl_categories ORDER BY cat_name ASC";

					$selectAllCategories = mysqli_query($conn, $query);

					checkQueryError($selectAllCategories);

					while ($row2 = mysqli_fetch_assoc($selectAllCategories)) {
						$catId = $row2['cat_id'];
						$category = $row2['cat_name'];

						echo "<li><a href='category_products.php?cat_id=$catId'>$category</a></li>";
					}



					?>
				</ul>
			</div>
			
		</div>
		<div class="col-sm-9">
			<h1>Products</h1>

<!-- 			<div class="row">
				<div class="col-sm-3 offset-9">
					<select name="sort" id="sort" class="form-control"  onchange="if (this.value) window.location.href=this.value">
						<option value="category_products.php?cat_id=<?php // echo $getCatId; ?>&sort=relevance" id="relevance"
							<?php // if (isset($_GET['cat_id']) && isset($_GET['sort']) && $_GET['sort'] == 'relevance') { echo "selected"; } ?>
						>Relevance</option>
						<option value="category_products.php?cat_id=<?php // echo $getCatId; ?>&sort=popularity" id="popularity"
							<?php // if (isset($_GET['cat_id']) && isset($_GET['sort']) && $_GET['sort'] == 'popularity') { echo "selected"; } ?>
						>Popularity</option>
						<option value="category_products.php?cat_id=<?php // echo $getCatId; ?>&sort=low_price" id="low_price"
							<?php // if (isset($_GET['cat_id']) && isset($_GET['sort']) && $_GET['sort'] == 'low_price') { echo "selected"; } ?>
						>Price: Low - High</option>
						<option value="category_products.php?cat_id=<?php // echo $getCatId; ?>&sort=high_price" id="high_price" 
							<?php // if (isset($_GET['cat_id']) && isset($_GET['sort']) && $_GET['sort'] == 'high_price') { echo "selected"; } ?>
						>Price: High - Low</option>
					</select>
				</div>
			</div> -->
			<div class="row product-row">
				<?php

				$query = "SELECT products.*, province.prov_name FROM tbl_products products, tbl_province province WHERE products.prov_id = province.prov_id AND products.cat_id = '$getCatId'";

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




					<div class="col-md-3 mb-3">
						<div class="card product-card shadow">
							<div class="product-cover">
								<a href="product_info.php?p_id=<?php echo $prodId; ?>">
									<img class="product-image card-img-top img-fluid" src="<?php echo $prodImageUrl . $prodImage; ?>" alt="Card image cap">
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
	</div>
</div>











<?php include('php/includes/footer.php'); ?>