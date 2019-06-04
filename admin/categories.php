<?php
include('php/admin_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');
include('php/admin_dash_nav.php');

if (!$_SESSION['admin_name']) {
	header("Location: index.php");
}

$alertMessage = '';

$category = '';

$extension = array("jpeg", "jpg", "png", "JPG");

if (isset($_POST['add_category'])) {
	$category = validateFormData($_POST['category']);

	$query = "SELECT cat_name FROM tbl_categories WHERE cat_name = '$category'";

	$selectCatName = mysqli_query($conn, $query);

	if (mysqli_num_rows($selectCatName) > 0) {
		$alertMessage = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
						 	Category already exist.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";
	} else {

		$categoryImage = $_FILES['cat_image']['name'];

		$categoryImage = str_replace(' ', '_', $categoryImage);

		$categoryImageTemp = $_FILES['cat_image']['tmp_name'];

		$newFileName = time() . "_" . $categoryImage;

		$ext = pathinfo($categoryImage, PATHINFO_EXTENSION);

		$imageDirectory = "../images/categories/$newFileName";

		if (in_array($ext, $extension)) {
    		move_uploaded_file($categoryImageTemp, $imageDirectory);

			if(!move_uploaded_file($categoryImageTemp, $imageDirectory) && !is_writable($imageDirectory)){
						$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
								 	File was not uploaded.
								 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								 		<span aria-hidden='true'>&times;</span>
								 	</button>
								 </div>";		 
		    } else {
			    $query = "INSERT INTO tbl_categories (cat_name, cat_image) VALUES ('$category', '$newFileName')";
				$addCategory = mysqli_query($conn, $query);

				checkQueryError($addCategory);

				$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
								 	Category added.
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

if (isset($_GET['del-cat'])) {
    $getCatId = $_GET['del-cat'];

    $query = "DELETE FROM tbl_categories WHERE cat_id = $getCatId";

    $deleteCategory = mysqli_query($conn, $query);

    checkQueryError($deleteCategory);

    header("Location: categories.php");
}

?>

<title>Categories</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/admin_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Categories</h1>
			</div>
			<div class="row">
				<div class="col-sm-8">
					<table class="table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Category</th>
								<th>Image</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php

							$query = "SELECT * FROM tbl_categories";
							$selectAllCategories = mysqli_query($conn, $query);

							while ($row = mysqli_fetch_assoc($selectAllCategories)) {
								$catId = $row['cat_id'];
								$catName = $row['cat_name'];
								$catImage = $row['cat_image'];

								echo "<tr>
										<td>$catId</td>
										<td>$catName</td>
										<td>$catImage</td>
										<td>
											<a href='categories.php?del-cat=$catId' class='btn btn-danger btn-sm'><i class='fa fa-fw fa-trash'></i></a>
										</td>
									  </tr>";
							}

							?>
							
						</tbody>
					</table>
				</div>
				<div class="col-sm-4">
					<?php echo $alertMessage; ?>

					<p class="lead">Add Categories</p>

					<form action="" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="">Category</label>
							<input type="text" class="form-control" name="category" value="<?php echo $category; ?>">
						</div>
						<div class="form-group">
							<label for="">Image</label>
							<input required type="file" class="form-control-file form-control-sm" name="cat_image">
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary" name="add_category">Add Category</button>
						</div>
					</form>
				</div>
			</div>
		</main>
	</div>
</div>


<?php include('php/admin_footer.php'); ?>