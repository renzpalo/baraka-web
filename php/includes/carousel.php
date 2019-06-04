<div id="myCarousel" class="carousel slide" data-ride="carousel">
	<div class="carousel-inner">
		<?php

		$query = "SELECT * FROM tbl_carousel_ads ORDER BY car_priority ASC";

		$selectCarouselAds = mysqli_query($conn, $query);

		checkQueryError($selectCarouselAds);

		$row = mysqli_fetch_assoc($selectCarouselAds);

		$carTitle = $row['car_title'];
		$carDescription = $row['car_description'];
		$carImage = $row['car_image'];
		$carLink = $row['car_link'];

		echo "<div class='carousel-item active'>
				<img class='' src='images/carousel/$carImage' alt='Slide' style='width: 100%;'>
				<div class='container'>
					<div class='carousel-caption text-left'>
						<h1>$carTitle</h1>
						<p>$carDescription</p>
						<p><a class='btn btn-lg btn-primary' href='$carLink'' role='button' target='_blank'>Learn more</a></p>
					</div>
				</div>
			</div>";

		while ($row = mysqli_fetch_assoc($selectCarouselAds)) {
			$carTitle = $row['car_title'];
			$carDescription = $row['car_description'];
			$carImage = $row['car_image'];
			$carLink = $row['car_link'];

			echo "<div class='carousel-item'>
					<img class='' src='images/carousel/$carImage' alt='Slide' style='width: 100%;'>
					<div class='container'>
						<div class='carousel-caption text-left'>
							<h1>$carTitle</h1>
							<p>$carDescription</p>
							<p><a class='btn btn-lg btn-primary' href='$carLink' role='button' target='_blank'>Learn more</a></p>
						</div>
					</div>
				</div>";
		}

		?>

	<ol class="carousel-indicators">
		<?php

		$numRows = mysqli_num_rows($selectCarouselAds);

		for ($i = 0; $i < $numRows; $i++) { 
			echo "<li data-target='#myCarousel' data-slide-to='$i' class='active'></li>";
		}


		?>
	</ol>

	</div>
	<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div>