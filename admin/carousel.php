<?php
include('php/admin_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');
include('php/admin_dash_nav.php');

if (!$_SESSION['admin_name']) {
	header("Location: index.php");
}

$alertMessage = '';

$carTitle = '';
$carLink = '';

date_default_timezone_set("Asia/Manila");

$date = date("Y-m-d H:i:s");

if (isset($_POST['add_carousel'])) {
	$carTitle = validateFormData($_POST['car_title']);
	$carDescription = validateFormData($_POST['car_description']);
	$carLink = validateFormData($_POST['car_link']);
	$carPriority = validateFormData($_POST['car_priority']);

	$query = "SELECT car_title FROM tbl_carousel_ads WHERE car_title = '$carTitle'";

	$selectCarouselName = mysqli_query($conn, $query);

	if (mysqli_num_rows($selectCarouselName) > 0) {
		$alertMessage = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
						 	Carousel advertisement already exist.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";
	} else {

		$carImage = validateFormData($_FILES['car_image']['name']);
		$carImage = str_replace(' ', '_', $carImage);

		$carImageTemp = $_FILES['car_image']['tmp_name'];

		$newFileName = time() . "_" . $carImage;

		$imageDirectory = "../images/carousel/$newFileName";

		move_uploaded_file($carImageTemp, $imageDirectory);

		if(!move_uploaded_file($carImageTemp, $imageDirectory) && !is_writable($imageDirectory)){
					$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
							 	File was not uploaded.
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";		 
	    } else {
	        $query = "INSERT INTO tbl_carousel_ads (car_title, car_description, car_image, car_link, car_priority, car_date_posted) VALUES ('$carTitle', '$carDescription', '$newFileName', '$carLink', '$carPriority', '$date')";

			$addCarouselAds = mysqli_query($conn, $query);

			checkQueryError($addCarouselAds);

			$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
							 	Carousel advertisement added.
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";
	    }

	}

    

	
}


if (isset($_GET['del-car'])) {
    $getCarId = $_GET['del-car'];

    $query = "DELETE FROM tbl_carousel_ads WHERE car_id = $getCarId";

    $deleteCarAd = mysqli_query($conn, $query);

    checkQueryError($deleteCarAd);

    header("Location: carousel.php");
}

?>

<title>Carousel Ads</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/admin_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Carousel Banners</h1>
			</div>
			<div class="row">
				<div class="col-sm-8">
					<table class="table">
						<thead>
							<tr>
								<th>Priority</th>
								<th>Title</th>
								<th>Description</th>
								<th>Link</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php

							$query = "SELECT * FROM tbl_carousel_ads";
							$selectAllAds = mysqli_query($conn, $query);

							while ($row = mysqli_fetch_assoc($selectAllAds)) {
								$carId = $row['car_id'];
								$carTitle = $row['car_title'];
								$carDescription = $row['car_description'];
								$carLink = $row['car_link'];
								$carPriority = $row['car_priority'];

								echo "<tr>
										<td>$carPriority</td>
										<td>$carTitle</td>
										<td>$carDescription</td>
										<td><a href='$carLink'>$carLink</a></td>
										<td>
											<a href='carousel.php?del-car=$carId' class='btn btn-danger btn-sm'><i class='fa fa-fw fa-trash'></i></a>
										</td>
									  </tr>";
							}

							?>
							
						</tbody>
					</table>
				</div>
				<div class="col-sm-4">
					<?php echo $alertMessage; ?>

					<p class="lead">Add Province</p>

					<form action="" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="">Title</label>
							<input required type="text" class="form-control" name="car_title" value="<?php echo $carTitle; ?>">
						</div>
						<div class="form-group">
							<label for="">Description</label>
							<textarea name="car_description" id="" cols="30" rows="5" class="form-control"></textarea>
						</div>
						<div class="form-group">
							<label for="">Image</label>
							<input required type="file" class="form-control-file form-control-sm" name="car_image">
						</div>
						<div class="form-group">
							<label for="">Link</label>
							<input required type="text" class="form-control" name="car_link" value="<?php echo $carLink; ?>">
						</div>
						<div class="form-group">
							<label for="">Priority</label>
							<select name="car_priority" id="" class="form-control">	
							<?php

							for ($i = 1; $i <= 10; $i++) { 
								echo "<option value='$i'>$i</option>";
							}

							?>
							</select>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary" name="add_carousel">Add to Carousel</button>
						</div>
					</form>
				</div>
			</div>
		</main>
	</div>
</div>


<?php include('php/admin_footer.php'); ?>