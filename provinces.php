<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');
include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

?>

<title>Provinces</title>

<div class="container main-page">

	<h1>Provinces</h1>

	<div class="row">

		<?php

		$query = "SELECT * FROM tbl_province ORDER BY prov_name";

		$selectAllProvinces = mysqli_query($conn, $query);

		checkQueryError($selectAllProvinces);

		while ($row = mysqli_fetch_assoc($selectAllProvinces)) {
			$provId = $row['prov_id'];
			$provName = $row['prov_name'];
			$provImage = $row['prov_image'];

			echo "<div class='col-sm-2 card-box'>
					<a href='province_products.php?prov_id=$provId'>
						<div class='category card-item'>
							<div class='cover' style='background: url(images/provinces/$provImage) no-repeat; background-size: cover;'>

								<div class='card-overlay'>
									<p class='lead'>$provName</p>
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