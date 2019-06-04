<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');



$productImageUrl = "images/products/";

$prodId = "";
$productQuantity = 1;
$prodPrice = "";

$prodId = "";
$prodName = "";
$prodDescription = "";
$prodPrice = "";
$prodOldPrice = "";
$prodStock = "";
$prodImage = "";
$prodType = "";
$prodMinQty = "";
$prodShipFee = "";
$prodRating = "";
$prodExpiry = "";

$province = "";
$category = "";

$alertMessage = "";

if (isset($_SESSION['user_id'])) {
	$userId = $_SESSION['user_id'];
}

if (isset($_GET['p_id'])) {
	$getProductId = $_GET['p_id'];

	$query2 = "UPDATE tbl_product_stats SET stats_views = stats_views + 1 WHERE prod_id = '$getProductId'";

	$updateStats = mysqli_query($conn, $query2);

	checkQueryError($updateStats);
}

if (isset($_POST['signin'])) {
	$email = validateFormData($_POST['signin_email']);
	$password = validateFormData($_POST['signin_password']);

	// $email = mysqli_real_escape_string($conn, $email);
	// $password = mysqli_real_escape_string($conn, $password);

	$query = "SELECT * FROM tbl_user_cred WHERE user_email = '$email'";

	$selectUserCred = mysqli_query($conn, $query);

	if (!$selectUserCred) {
		die("Query failed" .mysqli_error($conn));
	}

	while ($row = mysqli_fetch_array($selectUserCred)) {
		$userId = $row['user_id'];
		$userEmail = $row['user_email'];
		$userMobileNo = $row['user_phone_no'];
		$userPassword = $row['user_password'];
	}

	$query2 = "SELECT * FROM tbl_user_profile WHERE user_email = '$email'";

	$selectUserProf = mysqli_query($conn, $query2);

	if (!$selectUserProf) {
		die("Query failed" .mysqli_error($conn));
	}

	while ($row2 = mysqli_fetch_array($selectUserProf)) {
		$getUserId = $row2['user_id'];
		$getUserFullname = $row2['user_fullname'];
		$getUserEmail = $row2['user_email'];
		$getUserMobileNo = $row2['user_phone_no'];
		$getUserAddress = $row2['user_address'];
	}


	if (empty($email) && empty($password)) {
		$alertMessage = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
						 	Email and Password is required.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";

	} else {
		if ($email === $userEmail && $password === $userPassword) {

			$_SESSION['user_id'] = $getUserId;
			$_SESSION['user_fullname'] = $getUserFullname;
			$_SESSION['user_email'] = $getUserEmail;
			$_SESSION['user_mobileno'] = $getUserMobileNo;
			$_SESSION['user_address'] = $getUserAddress;

			header("Refresh: 0");
		} else {
			$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
							 	Invalid credentials
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";
		}
		
	}
}



?>

<title>Product Details</title>

<!-- Modal -->
<div class="modal fade" id="signinModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Sign In</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-10 offset-1">
						<form action="" method="post">
							<div class="form-group">
								<label for="signin_email">Email</label>
								<input type="email" required autofocus class="form-control form-control-sm input-email" placeholder="juandelacruz@email.com" name="signin_email" value="">
							</div>
							<div class="form-group">
								<label for="signin_password">Password</label>
								<input type="password" required class="form-control form-control-sm input-password" placeholder="******" name="signin_password">
							</div>
							<p class="text-center">Not Registered? <a href="signup.php">Sign Up</a></p>
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block" name="signin">Sign In</button>
								<button type="button" class="btn btn-default btn-block mt-2" data-dismiss="modal">Cancel</button>
							</div>
							
						</form>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container main-page product-info">

	<!-- <div class="row">
		<div class="col-sm-4 offset-8 bg-dark">
			<?php // echo $alertMessage; ?>
		</div>
	</div> -->
	
	<div class="row p-5 mt-5 border rounded bg-white">
		<?php echo $alertMessage; ?>
		<?php

		$query = "SELECT product.*, province.prov_name, category.cat_name FROM tbl_products product, tbl_province province, tbl_categories category WHERE product.prov_id = province.prov_id AND product.cat_id = category.cat_id AND prod_id = '$getProductId'";

		$selectProductDetails = mysqli_query($conn, $query);

		checkQueryError($selectProductDetails);

		while ($row = mysqli_fetch_assoc($selectProductDetails)) {
			$prodId = $row['prod_id'];
			$prodName = $row['prod_name'];
			$prodDescription = $row['prod_description'];
			$prodPrice = $row['prod_price'];
			$prodOldPrice = $row['prod_old_price'];
			$prodStock = $row['prod_stock'];
			$prodImage = $row['prod_image'];
			$prodType = $row['prod_type'];
			$prodMinQty = $row['prod_min_quantity'];
			$prodMaxQty = $row['prod_max_quantity'];
			$prodShipFee = $row['prod_ship_fee'];
			$prodRating = $row['prod_rating'];
			$prodExpiry = $row['prod_expiry'];

			$province = $row['prov_name'];
			$cat_id = $row['cat_id'];
			$category = $row['cat_name'];

			$sellerId = $row['seller_id'];
		}

		$query4 = "SELECT * FROM tbl_reviews WHERE prod_id = '$prodId' AND rev_status = 'reviewed'";

		$selectRowsCount = mysqli_query($conn, $query4);
		checkQueryError($selectRowsCount);

		$revCount = mysqli_num_rows($selectRowsCount);



		?>
		<div class="col-sm-5 product-image-div">
			<div id="productImages" class="carousel slide" data-ride="carousel">
			  
			  <div class="carousel-inner">

			  	<?php

				$query = "SELECT * FROM tbl_product_images WHERE prod_id = '$prodId' ORDER BY prod_image_sequence DESC";

				$selectImages = mysqli_query($conn, $query);

				checkQueryError($selectImages);

				$row = mysqli_fetch_assoc($selectImages);

				$prod_image = $row['prod_image'];

				echo "<div class='carousel-item active'> 
				      <img class='d-block w-100 mt-4' src='images/products/$prod_image' alt='$prod_image' style=''>
				    </div>";

				while ($row = mysqli_fetch_assoc($selectImages)) {
					$prod_image = $row['prod_image'];

					echo "<div class='carousel-item'>
					      <img class='d-block w-100 mt-4' src='images/products/$prod_image' alt='$prod_image' style=''>
					    </div>";
				}

				?>
				<ol class="carousel-indicators shadow-lg">
				<?php

				$numRows = mysqli_num_rows($selectImages);

				for ($i = 0; $i < $numRows; $i++) { 
					echo "<li data-target='#productImages' data-slide-to='$i' class='active shadow bg-primary'></li>";
				}


				?>
				</ol>
			  </div>

<!-- 			  <a class="carousel-control-prev" href="#productImages" role="button" data-slide="prev">
			    <span class="carousel-control-prev-icon rounded-circle" aria-hidden="true"></span>
			    <span class="sr-only">Previous</span>
			  </a>
			  <a class="carousel-control-next" href="#productImages" role="button" data-slide="next">
			    <span class="carousel-control-next-icon rounded-circle" aria-hidden="true"></span>
			    <span class="sr-only">Next</span>
			  </a> -->
			  
			</div>
		</div>
		<div class="col-sm-7 ">
			<h1 class="product-name"><?php echo $prodName; ?></h1>

			<div class="row ml-1">
				<?php

				if ($prodRating < 0.4) {
					echo "
						<div class='m-0 p-0 star-0'>
							<i class='far fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
						</div>
					";
				} else if ($prodRating >= 0.5 && $prodRating <= 1.4) {
					echo "
						<div class='m-0 p-0 star-1'>
							<i class='fa fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
						</div>
					";
				} else if ($prodRating >= 1.5 && $prodRating <= 2.4) {
					echo "
						<div class='m-0 p-0 star-2'>
							<i class='fa fa-star star-yellow'></i>
							<i class='fa fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
						</div>
					";
				} else if ($prodRating >= 2.5 && $prodRating <= 3.4) {
					echo "
						<div class='m-0 p-0 star-3'>
							<i class='fa fa-star star-yellow'></i>
							<i class='fa fa-star star-yellow'></i>
							<i class='fa fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
						</div>
					";
				} else if ($prodRating >= 3.5 && $prodRating <= 4.4) {
					echo "
						<div class='m-0 p-0 star-4'>
							<i class='fa fa-star star-yellow'></i>
							<i class='fa fa-star star-yellow'></i>
							<i class='fa fa-star star-yellow'></i>
							<i class='fa fa-star star-yellow'></i>
							<i class='far fa-star star-yellow'></i>
						</div>
					";
				} else if ($prodRating >= 4.5) {
					echo '
						<div class="m-0 p-0 star-5">
							<i class="fa fa-star star-yellow"></i>
							<i class="fa fa-star star-yellow"></i>
							<i class="fa fa-star star-yellow"></i>
							<i class="fa fa-star star-yellow"></i>
							<i class="fa fa-star star-yellow"></i>
						</div>
					';
				}

				?>
				
				

				



		

	


				

				<p class="ml-2">(<?php echo $revCount; ?>)</p>
			</div>

			

			<h2 class="lead product-price text-primary"><b>PHP <?php echo $prodPrice; ?></b></h2>
			<p><?php echo $prodDescription; ?></p>


			<?php

			if (isset($_POST['add_to_cart'])) {
				if (isset($_SESSION['user_id'])) {
					$prodQuantity = $_POST['prod_quantity'];

					addToCart($prodId, $prodPrice, $prodQuantity, $userId);
				}
			
			}

			if (isset($_POST['add_to_wishlist'])) {
				$prodQuantity = $_POST['prod_quantity'];

				addToWishlist($prodId, $prodPrice, $prodQuantity, $userId);
			}

			if ($prodExpiry == '0000-00-00') {
						
			} else {
				echo "<p class='mb-1'><b>Expiration Date: </b>$prodExpiry</p>";
			}

			?>
			
			<p class="m-0"><b>Category: </b> <?php echo $category; ?></p>
			<p class="m-0"><b>Province: </b> <?php echo $province; ?></p>

			
			<form action="" method="post">
				<div class="row">
					<div class="col-sm-2">
						<div class="form-group mt-3 mb-2">
							<input type="number" min="<?php echo $prodMinQty; ?>" max="<?php echo $prodMaxQty; ?>" value="<?php echo $prodMinQty; ?>" name="prod_quantity" class="form-control add_cart_qty" required>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php
					if (isset($_SESSION['user_id'])) {
					?>

						<button type="submit" class="btn btn-primary" name="add_to_cart" 
						<?php if ($prodStock < 1) { echo "disabled"; } ?>
						>
							Add to Cart
						</button>
						<button type="submit" class="btn btn-outline-primary" name="add_to_wishlist">
							Add to Wishlist
						</button>

					<?php
					} else {
					?>

						<button type="button" class="btn btn-primary" name="add_to_cart" data-toggle="modal" data-target="#signinModal">
							Add to Cart
						</button>
						<button type="button" class="btn btn-outline-primary" name="add_to_wishlist" data-toggle="modal" data-target="#signinModal">
							Add to Wishlist
						</button>

					<?php
					}
					?>
					
				</div>
			</form>
			
			
			
		</div>
	</div>

	<div class="row">
		<?php

		$query2 = "SELECT * FROM tbl_sellers WHERE seller_id = '$sellerId'";

		$selectSeller = mysqli_query($conn, $query2);

		checkQueryError($selectSeller);

		while ($row2 = mysqli_fetch_assoc($selectSeller)) {
			$seller_id = $row2['seller_id'];
			$seller_name = $row2['seller_name'];
			$seller_info = $row2['seller_info'];
			$seller_image = $row2['seller_image'];
		}



		?>
		<div class="col-sm-6 mt-3 p-5 border rounded bg-white">
			<h5>Seller Information</h5>
			<div class="row mt-3">
				<div class="col-sm-3 seller-logo-cover">
					<img src="images/sellers/<?php echo $seller_image; ?>" alt="" class="rounded-circle seller-logo" width="100" height="100">
				</div>
				<div class="col-sm-8">
					<p class="lead"><a href="store.php?store_id=<?php echo $seller_id; ?>"><?php echo $seller_name; ?></a></p>
					<p>
						<?php echo $seller_info; ?>
					</p>
				</div>
			</div>
			
			
		</div>
		<div class="col-sm-5 offset-1 mt-3 p-5 border rounded bg-white sr-only">
			<h5>Product Information</h5>
		</div>
	</div>

	<div class="row mt-3 p-5 border rounded bg-white">
		<div class="col-sm-12">
			<p class="lead">Product Details</p>
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Overview</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Details</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Reviews</a>
				</li>
			</ul>
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade show active p-5" id="home" role="tabpanel" aria-labelledby="home-tab">
					<?php echo $prodDescription; ?>


				</div>
				<div class="tab-pane fade p-5" id="profile" role="tabpanel" aria-labelledby="profile-tab">
					<p><b>Product: </b><?php echo $prodName; ?></p>
					<p><b>Description: </b><?php echo $prodDescription; ?></p>
					<p><b>Province: </b><?php echo $province; ?></p>
					<p><b>Category: </b><?php echo $category; ?></p>
					<?php

					if ($prodExpiry == '0000-00-00') {
						
					} else {
						echo "<p><b>Expiration Date: </b>$prodExpiry</p>";
					}

					?>
					
				</div>
				<div class="tab-pane fade p-5" id="contact" role="tabpanel" aria-labelledby="contact-tab">
					<div class="row">
						<div class="col-sm-12">
						<?php

						$query4 = "SELECT reviews.*, user_profile.user_fullname FROM tbl_reviews reviews, tbl_user_profile user_profile WHERE reviews.rev_status = 'reviewed' AND reviews.user_id = user_profile.user_id AND prod_id = '$getProductId'";

						$getReviews = mysqli_query($conn, $query4);

						checkQueryError($getReviews);

						while ($row4 = mysqli_fetch_assoc($getReviews)) {
							$rev_id = $row4['rev_id'];
							$rev_summary = $row4['rev_summary'];
							$rev_feedback = $row4['rev_feedback'];
							$rev_image = $row4['rev_image'];
							$rev_rating = $row4['rev_rating'];
							$rev_date_posted = $row4['rev_date_posted'];

							$userName = $row4['user_fullname'];

						?>

							<div class="rev-item mb-2">
								<div class="row">
									<div class="col-sm-1 p-0">
										<img src="images/reviews/<?php echo $rev_image; ?>" alt="" class="img-fluid mt-3">
									</div>
									<div class="col-sm-8">
										<small class="d-block"><b><?php echo $rev_summary; ?></b></small>
										<small class="d-block"><?php echo $rev_feedback; ?></small>

										<span class="product-rating ">

										<?php 

										if ($rev_rating < 0.4) {
											echo "
													<i class='far fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
											";
										} else if ($rev_rating >= 0.5 && $rev_rating <= 1.4) {
											echo "
													<i class='fa fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
											";
										} else if ($rev_rating >= 1.5 && $rev_rating <= 2.4) {
											echo "
													<i class='fa fa-star star-yellow fa-xs'></i>
													<i class='fa fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
											";
										} else if ($rev_rating >= 2.5 && $rev_rating <= 3.4) {
											echo "
													<i class='fa fa-star star-yellow fa-xs'></i>
													<i class='fa fa-star star-yellow fa-xs'></i>
													<i class='fa fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
											";
										} else if ($rev_rating >= 3.5 && $rev_rating <= 4.4) {
											echo "
													<i class='fa fa-star star-yellow fa-xs'></i>
													<i class='fa fa-star star-yellow fa-xs'></i>
													<i class='fa fa-star star-yellow fa-xs'></i>
													<i class='fa fa-star star-yellow fa-xs'></i>
													<i class='far fa-star star-yellow fa-xs'></i>
											";
										} else if ($rev_rating >= 4.5) {
											echo '
													<i class="fa fa-star star-yellow fa-xs"></i>
													<i class="fa fa-star star-yellow fa-xs"></i>
													<i class="fa fa-star star-yellow fa-xs"></i>
													<i class="fa fa-star star-yellow fa-xs"></i>
													<i class="fa fa-star star-yellow fa-xs"></i>
											';
										}


										?>

										</span>
										<br>	
										<small class=""><?php echo $userName; ?> - </small>
										<small class=""><?php echo $rev_date_posted; ?></small>
									</div>
								</div>
							</div>
							<hr>


						<?php

						}


						?>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3 p-5 border rounded bg-white floa">
		<div class="col-sm-12">
			<p class="lead">Related Products</p>

			<!-- Cards -->
			<div class="row">
				<?php

				$query3 = "SELECT prods.*, prov.prov_name FROM tbl_products prods, tbl_province prov WHERE prods.prod_id != '$getProductId' AND prods.cat_id = '$cat_id' AND prov.prov_id = prods.prov_id LIMIT 6";

				//$query = "SELECT * FROM tbl_products";

				$selectFeaturedProducts = mysqli_query($conn, $query3);

				checkQueryError($selectFeaturedProducts);

				while ($row3 = mysqli_fetch_assoc($selectFeaturedProducts)) {
					$prodId = $row3['prod_id'];
					$prodName = $row3['prod_name'];
					$prodPrice = $row3['prod_price'];
					$prodProvince = $row3['prov_name'];
					$prodRatings = $row3['prod_rating'];

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
								<img class="product-image card-img-top img-fluid" src="images/products/<?php echo $prodImage; ?>" alt="Card image cap">
							</a>
						</div>
						
						<div class="card-body p-2">
							<a href="product_info.php?p_id=<?php echo $prodId; ?>">
								<h6 class="card-title product-title m-0">
									<?php echo substr($prodName, 0, 32); ?>
								</h6>
							</a>
							<p class="card-text product-price m-0">PHP <?php echo $prodPrice; ?></p>

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
							
							<small class="product-province m-0"><?php echo $prodProvince; ?></small>
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


<script src="js/jquery.mobile-1.4.5.min.js"></script>
<script>
	$(document).ready(function() {
		$("#productImages").swiperight(function() {
			$(this).carousel('prev');
		});
		$("#productImages").swipeleft(function() {
			$(this).carousel('next');
		});
	});
</script>








<?php include('php/includes/footer.php'); ?>