<?php 
include('php/includes/header.php');
include('php/includes/connect.php');
include('php/functions.php');
include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

?>

<title>Stores</title>

<div class="container main-page">

	<h1>Stores</h1>

	<div class="row">

		<?php

		$query = "SELECT * FROM tbl_sellers ORDER BY seller_name";

		$selectAllSellers = mysqli_query($conn, $query);

		checkQueryError($selectAllSellers);

		while ($row = mysqli_fetch_assoc($selectAllSellers)) {
			$seller_id = $row['seller_id'];
			$seller_name = $row['seller_name'];
			$seller_image = $row['seller_image'];

			echo "<div class='col-sm-2 card-box'>
					<a href='store.php?store_id=$seller_id'>
						<div class='category card-item'>
							<div class='cover' style='background: url(images/sellers/$seller_image) no-repeat; background-size: cover;'>

								<div class='card-overlay'>
									<p>$seller_name</p>
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