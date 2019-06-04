<header>
	<nav class="navbar navbar-expand-md navbar-light sticky-top">
		<div class="container">
			<a class="navbar-brand" href="index.php">baraka seller</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<a href="../index.php" class="nav-link">Home</a>
					</li>
				</ul>

				<ul class="navbar-nav navbar-right">
					<?php
					if (isset($_SESSION['seller_name'])) {
					?>
						<li class="nav-item dropdown active">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php 
							$userName = $_SESSION['seller_name'];

							$arrayName = explode(' ', $userName);

							echo $arrayName[0];
							?>
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="#">Account</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="php/seller_logout.php">Log Out</a>
							</div>
						</li>
					<?php
					} else {
						if (!$isSignInPage) {
					?>
						<li class="navbar-item active">
							<a href="seller_signin.php" class="nav-link">Sign In</a>
						</li>
					<?php
						}
					}
					?>					
				</ul>

			</div>
		</div>
		
	</nav>
</header>