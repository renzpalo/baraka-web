<?php
include('php/seller_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');
include('php/seller_dash_nav.php');

if (!$_SESSION['seller_name']) {
	header("Location: index.php");
}

$alertMessage = '';

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");

$prodName = "";
$prodDescription = "";
$prodPrice = "";
$prodOldPrice = "";
$prodStock = "";
$prodProvince = "";
$prodCategory = "";
$prodSubCategory = "";
$prodType = "";
$prodMinQty = "";
$prodMaxQty = "";
$prodShippingFee = "";
$prodImage = "";

if (isset($_POST['add_product'])) {
	$prodName = validateFormData($_POST['prod_name']);
	$prodDescription = validateFormData($_POST['prod_description']);
	$prodPrice = validateFormData($_POST['prod_price']);
	$prodOldPrice = validateFormData($_POST['prod_old_price']);
	$prodStock = validateFormData($_POST['prod_stock']);
	$prodProvince = validateFormData($_POST['prod_province']);
	$prodCategory = validateFormData($_POST['prod_category']);
	$prodSubCategory = validateFormData($_POST['prod_subcategory']);
	$prodType = validateFormData($_POST['prod_type']);
	$prodMinQty = validateFormData($_POST['prod_min_quantity']);
	$prodMaxQty = validateFormData($_POST['prod_max_quantity']);
	$prodShippingFee = validateFormData($_POST['prod_ship_fee']);
	$prodExpiry = validateFormData($_POST['prod_expiry']);

	$sellerId = $_SESSION['seller_id'];
	
	$query = "INSERT INTO tbl_products (prod_name, prod_description, prod_price, prod_old_price, prod_stock, prod_image, prod_type, prod_min_quantity, prod_max_quantity, prod_ship_fee, prod_expiry, prod_date_posted, prov_id, cat_id, subcat_id, seller_id) VALUES ('$prodName', '$prodDescription', '$prodPrice', '$prodOldPrice', '$prodStock', '', '$prodType', '$prodMinQty', '$prodMaxQty', '$prodShippingFee', '$prodExpiry', '$date', '$prodProvince', '$prodCategory', '$prodSubCategory', $sellerId)";

	$insertProduct = mysqli_query($conn, $query);

	$productId = mysqli_insert_id($conn);

	checkQueryError($insertProduct);

	if ($insertProduct) {
		$query2 = "INSERT INTO tbl_product_stats (stats_views, stats_orders, stats_sales, prod_id) VALUES (0, 0, 0, '$productId')";

		$insertStats = mysqli_query($conn, $query2);

		checkQueryError($insertStats);

		if ($insertStats) {
			$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
							 	Product posted.
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";
		}
	}

	extract($_POST);

	$extension = array("jpeg", "jpg", "png", "JPG");

	foreach($_FILES["prod_image"]["tmp_name"] as $key => $tmp_name) {

		$prodImage = $_FILES['prod_image']['name'][$key];

		$prodImage = str_replace(' ', '_', $prodImage);

		$prodImageTemp = $_FILES['prod_image']['tmp_name'][$key];
        $newFileName = time() . "_" . $prodImage;
        $imageDirectory = "../images/products/$newFileName";

        $ext = pathinfo($prodImage, PATHINFO_EXTENSION);

        if(in_array($ext, $extension)) {
        	move_uploaded_file($prodImageTemp, $imageDirectory);

            if(!move_uploaded_file($prodImageTemp, $imageDirectory) && !is_writable($imageDirectory)){
						$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
								 	File was not uploaded.
								 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								 		<span aria-hidden='true'>&times;</span>
								 	</button>
								 </div>";		 
			} else {
				$query = "INSERT INTO tbl_product_images (prod_image, prod_image_sequence, prod_id) VALUES ('$newFileName', '$key', '$productId')";

				$insertImages = mysqli_query($conn, $query);

				checkQueryError($insertImages);			
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


?>

<title>Post Product</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/seller_sidebar.php'); ?>
		
		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            	<h1 class="h2">Post Product</h1>
            
          	</div>

          	<div class="row">
          		<div class="col-sm-10 offset-1">

				<?php echo $alertMessage; ?>

	          	<form action="" method="post" enctype="multipart/form-data">
	            	<div class="form-group row">
	            		<label for="" class="col-sm-2 col-form-label col-form-label-sm"><b>Product Name</b></label>
	            		<div class="col-sm-10">
	            			<input id="prod_name" required type="text" class="form-control form-control-sm" placeholder="" value="<?php echo $prodName; ?>" name="prod_name">
	            		</div>
	            	</div>
	            	<div class="form-group row">
	            		<label for="" class="col-sm-2 col-form-label col-form-label-sm"><b>Description</b></label>
	            		<div class="col-sm-10">
	            			<textarea id="prod_name" id="" required cols="30" rows="5" class="form-control" name="prod_description"><?php echo $prodDescription; ?></textarea>
	            		</div>
	            	</div>
	            	<hr>
	            	<div class="form-group row">
	            		<label for="" class="col-sm-2 col-form-label col-form-label-sm"><b>Price</b></label>
	            		<div class="col-sm-4">
            				<div class="input-group input-group-sm">
								<div class="input-group-prepend">
									<span class="input-group-text">PHP</span>
								</div>
								<input id="prod_price" type="text" class="form-control" required placeholder="" value="<?php echo $prodPrice; ?>" name="prod_price">
							</div>
	            		</div>
	            		<div class="col-sm-3 sr-only">
	            			<div class="form-group">
	            				<label for="">Old Price</label>
	            				<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<span class="input-group-text">PHP</span>
									</div>
									<input id="prod_old_price" type="text" class="form-control" placeholder="" value="<?php echo $prodOldPrice; ?>" name="prod_old_price">
								</div>
	            			</div>
	            		</div>
	            		<label for="" class="col-sm-1 col-form-label col-form-label-sm"><b>Stock</b></label>
	            		<div class="col-sm-4">
	            			<input id="prod_stock" required type="text" class="form-control form-control-sm" placeholder="" value="<?php echo $prodStock; ?>" name="prod_stock">
	            		</div>
	            	</div>
	            	<hr>
	            	<div class="form-group row">
	            		<label for="" class="col-sm-2 col-form-label col-form-label-sm"><b>Images</b></label>
	            		<!-- <div class="col-sm-8">
	            			<input type="file" name="prod_image[]" class="form-control-file form-control-sm" multiple required>
	            		</div> -->
						<div class="input-group col-sm-10">
							<div class="input-group-prepend">
								<span class="input-group-text">Upload</span>
							</div>
							<div class="custom-file">
								<input id="prod_image" type="file" class="custom-file-input" name="prod_image[]" multiple required>
								<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
							</div>
						</div>
	            	</div>
	            	<hr>
	            	<div class="form-group row">
	            		<label for="" class="col-sm-2 col-form-label col-form-label-sm"><b>Province</b></label>
	            		<div class="col-sm-4">
		            		<select id="prod_province" class="form-control form-control-sm" name="prod_province" required>
		            			<option value="" selected disabled>Select Province</option>

								<?php

								$query = "SELECT * FROM tbl_province ORDER BY prov_name";
								$selectAllProvince = mysqli_query($conn, $query);

								while ($row = mysqli_fetch_assoc($selectAllProvince)) {
									$provId = $row['prov_id'];
									$provName = $row['prov_name'];

									echo "<option value='$provId'>$provName</option>";
								}

								?>
							</select>
	            		</div>
	            		<div class="form-group col-sm-6">
	            			<div class="row">
		            			<label for="" class="col-sm-3 col-form-label col-form-label-sm"><b>Category</b></label>
			            		<div class="col-sm-8">
				            		<select id="prod_category" class="form-control form-control-sm" name="prod_category" required>
				            			<option value="" selected disabled>Select Category</option>
										<?php

										$query = "SELECT * FROM tbl_categories ORDER BY cat_name";
										$selectAllCategories = mysqli_query($conn, $query);

										while ($row = mysqli_fetch_assoc($selectAllCategories)) {
											$catId = $row['cat_id'];
											$catName = $row['cat_name'];

											echo "<option value='$catId'>$catName</option>";
										}

										?>
									</select>
			            		</div>
	            			</div>
		            		
		            	</div>
	            	</div>
	            	
	            	<div class="form-group row sr-only">
	            		<label for="" class="col-sm-2 col-form-label col-form-label-sm">Sub Category</label>
	            		<div class="col-sm-8">
		            		<select id="prod_subcategory" class="form-control form-control-sm" name="prod_subcategory">
		            			<option value="" selected disabled>Select Sub Category</option>
								<?php

								$query = "SELECT * FROM tbl_subcategories ORDER BY subcat_name";
								$selectAllSubCategories = mysqli_query($conn, $query);

								while ($row = mysqli_fetch_assoc($selectAllSubCategories)) {
									$subCatId = $row['subcat_id'];
									$subCatName = $row['subcat_name'];

									echo "<option value='$subCatId'>$subCatName</option>";
								}

								?>
							</select>
	            		</div>
	            	</div>
	            	<hr>
					<fieldset class="form-group">
						<div class="row">
							<legend class="col-form-label col-sm-2 pt-0 pl-1"><b>Product Type</b></legend>
							<div class="col-sm-10">
								<div class="row">
									<div class="col-sm-2">
										<div class="custom-control custom-radio">
											<input id="retail" name="prod_type" type="radio" class="custom-control-input" value="retail" checked required>
											<label class="custom-control-label" for="retail">Retail</label>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="custom-control custom-radio">
											<input id="wholesale" name="prod_type" type="radio" class="custom-control-input" value="wholesale" required>
											<label class="custom-control-label" for="wholesale">Wholesale</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
					<hr>
					<div class="form-group row">
	            		<label for="" class="col-sm-2 col-form-label col-form-label-sm"><b>Expiry Date</b></label>
	            		<div class="col-sm-10">
	            			<input type="date" name="prod_expiry" class="form-control form-control-sm" value="NULL">
	            		</div>
	            	</div>
	            	<hr>
					<div class="form-group row">
	            		<label for="" class="col-sm-2 col-form-label col-form-label-sm"><b>Quantity</b></label>
						<div class="col-sm-3">
							<div class="form-group">
								<label for=""><b>Minimum Quantity</b></label>
								<input id="prod_min_quantity" required type="text" class="form-control form-control-sm" placeholder="" value="<?php echo $prodMinQty; ?>" name="prod_min_quantity">
							</div>
						</div>
	            		<div class="col-sm-3">
							<div class="form-group">
								<label for=""><b>Maximum Quantity</b></label>
								<input id="prod_max_quantity" required type="text" class="form-control form-control-sm" placeholder="" value="<?php echo $prodMaxQty; ?>" name="prod_max_quantity">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Shipping Fee</b></label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<span class="input-group-text">PHP</span>
									</div>
									<input id="prod_ship_fee" type="text" class="form-control" required placeholder="" value="<?php echo $prodShippingFee; ?>" name="prod_ship_fee">
								</div>
							</div>
						</div>
	            	</div>
	            	<div class="form-group">
	            		<button type="submit" class="btn btn-primary btn-block" name="add_product">Post Product</button>
	            	</div>
	            </form>
          	</div>
          	
          </div>

         
        </main>
	</div>
	
</div>


<?php include('php/seller_footer.php'); ?>

<script>



</script>