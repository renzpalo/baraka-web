<link rel="stylesheet" href="../css/dashboard.css" type="text/css">

<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">baraka seller</a>
  <!--<input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">-->
  <ul class="navbar-nav px-3">
  	<li class="nav-item active">
  		<a href="#" class="nav-link">
  			<?php 
			$userName = $_SESSION['seller_name'];

			echo $userName;
			?>
  		</a>
  	</li>
<!--     <li class="nav-item dropdown active">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		
		</a>
		<div class="dropdown-menu drop-menu" aria-labelledby="navbarDropdown">
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="php/seller_logout.php">Log Out</a>
		</div>
	</li> -->

  </ul>
</nav>