<?php
include('php/admin_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');
include('php/admin_dash_nav.php');

if (!$_SESSION['admin_name']) {
	header("Location: index.php");
}

$alertMessage = '';

if (isset($_POST['add_subcategory'])) {
	$subCategory = validateFormData($_POST['sub_category']);
	$category = validateFormData($_POST['category']);

	$query = "INSERT INTO tbl_subcategories (subcat_name, cat_id) VALUES ('$subCategory', '$category')";
	$addSubCategory = mysqli_query($conn, $query);

	checkQueryError($addSubCategory);

	$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					 	Sub Category added.
					 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					 		<span aria-hidden='true'>&times;</span>
					 	</button>
					 </div>";
}

if (isset($_GET['del-subcat'])) {
    $getSubCatId = $_GET['del-subcat'];

    $query = "DELETE FROM tbl_subcategories WHERE subcat_id = $getSubCatId";

    $deleteSubCategory = mysqli_query($conn, $query);

    checkQueryError($deleteSubCategory);

    header("Location: sub_categories.php");
}

?>

<title>Sub Categories</title>

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
								<th>Sub Category</th>
								<th>Category</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php

							$query = "SELECT subcat.*, cat.cat_name FROM tbl_subcategories subcat, tbl_categories cat WHERE subcat.cat_id = cat.cat_id";
							$selectAllSubCategories = mysqli_query($conn, $query);

							while ($row = mysqli_fetch_assoc($selectAllSubCategories)) {
								$subcatId = $row['subcat_id'];
								$subcatName = $row['subcat_name'];
								$catId = $row['cat_id'];
								$catName = $row['cat_name'];

								echo "<tr>
										<td>$subcatId</td>
										<td>$subcatName</td>
										<td>$catName</td>
										<td>
											<a href='sub_categories.php?del-subcat=$subcatId' class='btn btn-danger btn-sm'><i class='fa fa-fw fa-trash'></i></a>
										</td>
									  </tr>";
							}

							?>
							
						</tbody>
					</table>
				</div>
				<div class="col-sm-4">
					<?php echo $alertMessage; ?>

					<p class="lead">Add Sub Categories</p>

					<form action="" method="post">
						<div class="form-group">
							<label for="">Sub Category</label>
							<input type="text" class="form-control" name="sub_category">
						</div>
						<div class="form-group">
							<label for="">Category</label>
							<select name="category" id="cat" class="form-control">

								<?php

								$query = "SELECT * FROM tbl_categories";
								$selectAllCategories = mysqli_query($conn, $query);

								while ($row = mysqli_fetch_assoc($selectAllCategories)) {
									$catId = $row['cat_id'];
									$catName = $row['cat_name'];

									echo "<option value='$catId'>$catName</option>";
								}

								?>
								
							</select>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary" name="add_subcategory">Add Sub Category</button>
						</div>
					</form>
				</div>
			</div>
		</main>
	</div>
</div>


<?php include('php/admin_footer.php'); ?>