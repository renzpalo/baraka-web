<header>
	<nav class="main-nav navbar navbar-expand-md navbar-dark bg-primary">
		<div class="container">
			<a class="navbar-brand" href="index.php">baraka</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Shop
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="provinces.php">Provinces</a>
							<a class="dropdown-item" href="katutubo.php">Made by Katutubos</a>
							<a class="dropdown-item" href="categories.php">Shop by Category</a>
							<!--<a class="dropdown-item" href="farm_to_market.php">Farm to Market</a>-->
							<a class="dropdown-item" href="stores.php">Stores</a>
						</div>
					</li>

				</ul>
				<ul class="navbar-nav">
					<form action="products.php" method="get" class="nav-search">
						<div class="input-group">
							<input id="search-box" type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2" name="q">
							<div class="input-group-append">
								<button class="btn btn-primary" type="submit">
									<i class="fas fa-search"></i>
								</button>
							</div>
						</div>
					</form>
					
				</ul>
				<?php

				if (isset($_SESSION['user_fullname'])) {
					$userId = $_SESSION['user_id'];

					$numCart = 0;

					$query = "SELECT cart_id FROM tbl_cart WHERE user_id = '$userId'";
					$selectRows = mysqli_query($conn, $query);
					checkQueryError($selectRows);
					$numCart = mysqli_num_rows($selectRows);
				}

				
				?>
				<ul class="navbar-nav navbar-right">
					<li class="nav-item shop-cart">
						<a href="cart.php" class="nav-link"><i class="fas fa-shopping-cart"></i> 
							<?php
							if (isset($_SESSION['user_fullname'])) {
								echo "<span class='badge badge-light'>$numCart</span>";

							}

							?>
							
						</a>
					</li>


					<?php
					if (isset($_SESSION['user_fullname'])) {
					?>
						<li class="nav-item dropdown active">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php 
							$userName = $_SESSION['user_fullname'];

							$arrayName = explode(' ', $userName);

							echo substr($arrayName[0], 0, 9);
							?>
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="orders.php">My Orders</a>
								<a class="dropdown-item" href="wishlist.php">Wishlists</a>
								<a class="dropdown-item" href="account.php">Account</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="php/logout.php">Log Out</a>
							</div>
						</li>
					<?php
					} else {
					?>
						<li class="nav-item active"><a href="signin.php" class="nav-link">Sign In</a></li>
					<?php
					}
					?>
				</ul>
			</div>
		</div>
		
	</nav>
</header>

<?php include('bottom-navbar.php'); ?>