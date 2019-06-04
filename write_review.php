<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

if (!$_SESSION['user_id']) {
	header("Location: index.php");
}

if (isset($_GET['r_id'])) {
	$revId = validateFormData($_GET['r_id']);
}

if (isset($_SESSION['user_id'])) {
	$userId = $_SESSION['user_id'];
	$userName = $_SESSION['user_fullname'];
}

$alertMessage = '';

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");


?>

<title>Write a Review</title>

<div class="container-fluid">
	<div class="row mb-5 bg-white p-5">
		<div class="col-sm-8 offset-2">
			<h1 class="text-center">Write a Review</h1>

			<?php



			$query = "SELECT reviews.*, products.prod_id, products.prod_name, products.prod_rating, seller.seller_id, seller.seller_name FROM tbl_reviews reviews, tbl_products products, tbl_sellers seller WHERE products.seller_id = seller.seller_id AND reviews.prod_id = products.prod_id AND reviews.rev_id = '$revId'";

			$selectReviews = mysqli_query($conn, $query);

			checkQueryError($selectReviews);

			while ($row = mysqli_fetch_assoc($selectReviews)) {
				$rev_id = $row['rev_id'];
				$rev_summary = $row['rev_summary'];
				$rev_feedback = $row['rev_feedback'];
				$rev_image = $row['rev_image'];
				$rev_rating = $row['rev_rating'];
				$rev_status = $row['rev_status'];

				$prod_id = $row['prod_id'];
				$prod_name = $row['prod_name'];
				$prod_rating = $row['prod_rating'];

				$seller_id = $row['seller_id'];
				$seller_name = $row['seller_name'];

				$user_id = $row['user_id'];

				$query2 = "SELECT prod_image FROM tbl_product_images WHERE prod_id = '$prod_id' ORDER BY prod_image_sequence DESC";
				$selectImages = mysqli_query($conn, $query2);
				$row2 = mysqli_fetch_assoc($selectImages);

				$prod_image = $row2['prod_image'];

			}

			if (isset($_POST['write_review'])) {
				$revPostedBy = validateFormData($_POST['rev_posted_by']);
				$revSummary = validateFormData($_POST['rev_summary']);
				$revFeedback = validateFormData($_POST['rev_feedback']);
				$revRatings = validateFormData($_POST['rev_ratings']);

				$extension = array("jpeg", "jpg", "png");

				$revImage = $_FILES['rev_image']['name'];
				$revImageTemp = $_FILES['rev_image']['tmp_name'];
		        $newFileName = time() . "_" . $revImage;
		        $imageDirectory = "images/reviews/$newFileName";

		        $ext = pathinfo($revImage, PATHINFO_EXTENSION);

		        if(in_array($ext, $extension)) {
		        	move_uploaded_file($revImageTemp, $imageDirectory);

		            if(!move_uploaded_file($revImageTemp, $imageDirectory) && !is_writable($imageDirectory)){
						$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
								 	File was not uploaded.
								 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								 		<span aria-hidden='true'>&times;</span>
								 	</button>
								 </div>";		 
					} else {
						if ($user_id === $userId) {
							$query3 = "UPDATE tbl_reviews SET
										rev_posted_by = '$revPostedBy',
										rev_summary = '$revSummary',
										rev_feedback = '$revFeedback',
										rev_image = '$newFileName',
										rev_rating = '$revRatings',
										rev_date_posted = '$date',
										rev_status = 'reviewed'
										WHERE rev_id = $revId
									";

							$insertReview = mysqli_query($conn, $query3);

							checkQueryError($insertReview);		

							if ($insertReview) {
								// Update Product Ratings
								$query4 = "SELECT * FROM tbl_reviews WHERE prod_id = '$prod_id' AND rev_status = 'reviewed'";

								$selectRowsCount = mysqli_query($conn, $query4);
								checkQueryError($selectRowsCount);

								$rowCount = mysqli_num_rows($selectRowsCount);

								$newRatings = ($prod_rating + $revRatings) / $rowCount;

								$query5 = "UPDATE tbl_products SET prod_rating = '$newRatings' WHERE prod_id = '$prod_id'";

								$updateRatings = mysqli_query($conn, $query5);
								
								checkQueryError($updateRatings);

								if ($updateRatings) {
									$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
													 	Review posted.
													 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
													 		<span aria-hidden='true'>&times;</span>
													 	</button>
													 </div>";	
								}
								
							}
						} else {
							$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
											 	Failed to post review.
											 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											 		<span aria-hidden='true'>&times;</span>
											 	</button>
											 </div>";	
						}

					}
		        } else {
		            $alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
							 	File not supported.
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";	
		        }

			}

			?>

			<div class="row border shadow-lg rounded text-center p-3">
				<div class="col-sm-12">
					<img src="images/products/<?php echo $prod_image; ?>" alt="<?php echo $prod_image; ?>" class="img-fluid d-inline mr-5" style="height: 100px;">
					<h6 style="line-height: 100px;" class="d-inline">
						<a href="product_info.php?p_id=<?php echo $prod_id; ?>"><?php echo $prod_name; ?></a> by 
						<a href="store.php?store_id=<?php echo $seller_id; ?>"><?php echo $seller_name; ?></a>
						<p class="d-inline m-0"> - <?php echo $prod_rating; ?> stars</p>
					</h6>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12 p-5 mt-5 border shadow-lg rounded">
					<div class="row">
						<div class="col-sm-8 offset-2">
							<?php echo $alertMessage; ?>
							<form action="" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label for="">Posted by</label>
									<input type="text" class="form-control" name="rev_posted_by" value="<?php echo $userName; ?>">
								</div>
								<div class="form-group">
									<label for="">Summary</label>
									<input type="text" class="form-control" name="rev_summary" value="<?php echo $rev_summary; ?>">
								</div>
								<div class="form-group">
									<label for="">Feedback</label>
									<textarea id="" cols="30" rows="5" class="form-control" name="rev_feedback"><?php echo $rev_feedback; ?></textarea>
								</div>
								
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="">Image</label>
											<input type="file" class="form-control" name="rev_image">
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="">Ratings (1-5 Stars)</label>
											<select name="rev_ratings" id="" class="form-control" required>
												<option value="" selected disabled>Select Ratings</option>
												<option value="1">1 Star</option>
												<option value="2">2 Stars</option>
												<option value="3">3 Stars</option>
												<option value="4">4 Stars</option>
												<option value="5">5 Stars</option>
											</select>
											
										</div>
									</div>
									
									
								</div>
								<div class="form-group">
									<?php

									if ($rev_status == 'reviewed') {
										echo "<button type='submit' class='btn btn-primary btn-block btn-lg' name='write_review' disabled>Submit Review</button>";
									} else {
										echo "<button type='submit' class='btn btn-primary btn-block btn-lg' name='write_review'>Submit Review</button>";
									}

									?>
									
								</div>
							</form>
						</div>
					</div>
					
				</div>
			</div>
			

			
		</div>
			
	</div>
</div>
	








<?php 

mysqli_close($conn);


include('php/includes/footer.php');




 ?>