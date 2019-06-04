<?php 
include('php/includes/header.php');
include('php/includes/connect.php');
include('php/functions.php');
include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

?>



<title>Categories</title>

<div class="container main-page category-page">

	<h1>Shop by Category</h1>

	<div class="row">

		<?php

		$query = "SELECT * FROM tbl_categories ORDER BY cat_name";

		$selectAllCategories = mysqli_query($conn, $query);

		checkQueryError($selectAllCategories);

		while ($row = mysqli_fetch_assoc($selectAllCategories)) {
			$catId = $row['cat_id'];
			$catName = $row['cat_name'];
			$catImage = $row['cat_image'];

			echo "<div class='col-sm-2 card-box'>
					<a href='category_products.php?cat_id=$catId''>
						<div class='category card-item'>
							<div class='cover' style='background: url(images/categories/$catImage) no-repeat; background-size: cover;'>
								<div class='card-overlay'>
									<p class='lead'>$catName</p>
								</div>	
							</div>
						</div>
					</a>	
				</div>";
		}



		?>
	</div>

	

</div>	











<?php include('php/includes/footer.php'); ?>