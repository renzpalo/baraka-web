<?php
include('php/logistics_header.php');
include('../../php/includes/connect.php');
include('php/logistics_dash_nav.php');

if (!$_SESSION['log_id']) {
	header("Location: ../../index.php");
}

?>

<div class="container-fluid">
	<div class="row">
		<?php include('php/logistics_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Dashboard</h1>
				<div class="btn-toolbar mb-2 mb-md-0">
					<div class="btn-group mr-2">
						<button class="btn btn-sm btn-outline-secondary">Share</button>
						<button class="btn btn-sm btn-outline-secondary">Export</button>
					</div>
					<button class="btn btn-sm btn-outline-secondary dropdown-toggle">
					<span data-feather="calendar"></span>
					This week
					</button>
				</div>
			</div>
		</main>
	</div>
</div>


<?php include('php/logistics_footer.php'); ?>