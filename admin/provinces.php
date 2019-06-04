<?php
include('php/admin_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');
include('php/admin_dash_nav.php');

if (!$_SESSION['admin_name']) {
	header("Location: index.php");
}

$alertMessage = '';

$province = '';

$extension = array("jpeg", "jpg", "png", "JPG");

if (isset($_POST['add_province'])) {
	$province = validateFormData($_POST['province']);

	$query = "SELECT prov_name FROM tbl_province WHERE prov_name = '$province'";

	$selectProvName = mysqli_query($conn, $query);

	if (mysqli_num_rows($selectProvName) > 0) {
		$alertMessage = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
						 	Province already exist.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";
	} else {

		$provinceImage = validateFormData($_FILES['prov_image']['name']);

		$provinceImage = str_replace(' ', '_', $provinceImage);

		$provinceImageTemp = $_FILES['prov_image']['tmp_name'];

		$newFileName = time() . "_" . $provinceImage;

		$ext = pathinfo($provinceImage, PATHINFO_EXTENSION);

		$imageDirectory = "../images/provinces/$newFileName";

		if (in_array($ext, $extension)) {
    		move_uploaded_file($provinceImageTemp, $imageDirectory);

			if(!move_uploaded_file($provinceImageTemp, $imageDirectory) && !is_writable($imageDirectory)){
						$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
								 	File was not uploaded.
								 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								 		<span aria-hidden='true'>&times;</span>
								 	</button>
								 </div>";		 
		    } else {
		        $query = "INSERT INTO tbl_province (prov_name, prov_image) VALUES ('$province', '$newFileName')";
				$addProvince = mysqli_query($conn, $query);

				checkQueryError($addProvince);

				$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
								 	Province added.
								 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								 		<span aria-hidden='true'>&times;</span>
								 	</button>
								 </div>";
		    }
		} else {
		    $alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
					            File not supported.
					            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					              <span aria-hidden='true'>&times;</span>
					            </button>
					           </div>"; 
		}

		

	}

    

	
}


if (isset($_GET['del-prov'])) {
    $getProvId = $_GET['del-prov'];

    $query = "DELETE FROM tbl_province WHERE prov_id = $getProvId";

    $deleteProvince = mysqli_query($conn, $query);

    checkQueryError($deleteProvince);

    header("Location: provinces.php");
}

?>

<title>Provinces</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/admin_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Provinces</h1>
			</div>
			<div class="row">
				<div class="col-sm-8">
					<table class="table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Province</th>
								<th>Image</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php

							$query = "SELECT * FROM tbl_province";
							$selectAllProvince = mysqli_query($conn, $query);

							while ($row = mysqli_fetch_assoc($selectAllProvince)) {
								$provId = $row['prov_id'];
								$provName = $row['prov_name'];
								$provImage = $row['prov_image'];

								echo "<tr>
										<td>$provId</td>
										<td>$provName</td>
										<td>$provImage</td>
										<td>
											<a href='provinces.php?del-prov=$provId' class='btn btn-danger btn-sm'><i class='fa fa-fw fa-trash'></i></a>
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
							<label for="">Province</label>
							<input required type="text" class="form-control" name="province" value="<?php echo $province; ?>">
						</div>
						<div class="form-group">
							<label for="">Image</label>
							<input required type="file" class="form-control-file form-control-sm" name="prov_image">
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary" name="add_province">Add Province</button>
						</div>
					</form>
				</div>
			</div>
		</main>
	</div>
</div>


<?php include('php/admin_footer.php'); ?>