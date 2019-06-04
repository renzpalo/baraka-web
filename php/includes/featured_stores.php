<div id="featured-brands" class="featured">
	<div class="container">
		<h4>Features Sellers</h4>
		<br>

		
		<div class="row">
			<?php

			$query = "SELECT fstore.*, seller.seller_id, seller.seller_name, seller.seller_image FROM tbl_featured_stores fstore, tbl_sellers seller WHERE fstore.seller_id = seller.seller_id ORDER BY fstore_priority";

			$selectFeaturedSeller = mysqli_query($conn, $query);

			checkQueryError($selectFeaturedSeller);

			while ($row = mysqli_fetch_assoc($selectFeaturedSeller)) {
				$sellerId = $row['seller_id'];
				$sellerName = $row['seller_name'];
				$sellerImage = $row['seller_image'];

				echo "<div class='col-sm-2 card-box'>
						<a href='store.php?store_id=$sellerId'>
							<div class=' featured-store card-item'>
								<div class='cover' style='background: url(images/sellers/$sellerImage) no-repeat; background-size: cover;'>
									<div class='card-overlay'>
										<p class=''>$sellerName</p>
									</div>	
								</div>
							</div>
						</a>	
					</div>";
			}

			?>
		</div>
	</div>
</div>