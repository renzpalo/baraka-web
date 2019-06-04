<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

$alertMessage = "";

if (!$_SESSION['user_id']) {
	header("Location: index.php");
}

if (isset($_SESSION['user_id'])) {
	$userId = $_SESSION['user_id'];
}

if (isset($_POST['add_address'])) {
	$adFullname = validateFormData($_POST['ad_fullname']);
	$adContact = validateFormData($_POST['ad_contact']);
	$adNotes = validateFormData($_POST['ad_notes']);
	$adStreet = validateFormData($_POST['ad_street']);
	$adBarangay = validateFormData($_POST['ad_barangay']);
	$adCityMuni = validateFormData($_POST['ad_city_muni']);
	$adProvince = validateFormData($_POST['ad_province']);
	$adZipcode = validateFormData($_POST['ad_postal']);

	$query = "INSERT INTO tbl_user_address (ad_fullname, ad_contact, ad_street, ad_barangay, ad_city_muni, ad_province, ad_zipcode, ad_notes, user_id) 				
				VALUES ('$adFullname', '$adContact', '$adStreet', '$adBarangay', '$adCityMuni', '$adProvince', '$adZipcode', '$adNotes', '$userId')";

	$addAddress = mysqli_query($conn, $query);

	checkQueryError($addAddress);

	if ($addAddress) {
		$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
						 	Address added.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";
	} else {
		$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
						 	Failed to add address.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";
	}


}


?>

<script src="js/city.js"></script>

<script>	
window.onload = function() {	
	// ---------------
	// basic usage
	// ---------------
	var $ = new City();
	$.showProvinces("#province");
	$.showCities("#city");
	// ------------------
	// additional methods 
	// -------------------
	// will return all provinces 
	console.log($.getProvinces());
	
	// will return all cities 
	console.log($.getAllCities());
	
	// will return all cities under specific province (e.g Batangas)
	console.log($.getCities("Batangas"));	
	
}
</script>

<title>Address</title>
	
<div class="container main-page">

	<div class="row mt-5 bg-white rounded shadow-lg pb-5 pt-5">
		<div class="col-sm-3 mt-5">
			<?php include('php/includes/user_sidebar.php'); ?>
		</div>
 
		<div class="col-sm-9">
			<h1>Add Address</h1>

			<?php echo $alertMessage; ?>

			<form action="" method="post">
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="">Full Name</label>
							<input type="text" class="form-control form-control-sm" placeholder="Juan Dela Cruz" name="ad_fullname" required>
						</div>
						<div class="form-group">
							<label for="">Mobile Number</label>
							<input type="text" class="form-control form-control-sm" placeholder="+639 " name="ad_contact" required>
						</div>
						<div class="form-group">
							<label for="">Address Notes</label>
							<textarea name="ad_notes" id="" cols="30" rows="4" class="form-control"></textarea>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="">Street</label>
							<input type="text" class="form-control form-control-sm" placeholder="123, Fatima Street" name="ad_street" required>
						</div>
						<div class="form-group">
							<label for="">Barangay</label>
							<input type="text" class="form-control form-control-sm" placeholder="Dela Paz" name="ad_barangay" required>
						</div>
						<div class="form-group">
							<label for="">Province</label>
							<select id="province" class="form-control form-control-sm" name="ad_province" required></select>
						</div>
						<div class="form-group">
							<label for="">City / Municipality</label>
							<select id="city" class="form-control form-control-sm" name="ad_city_muni" required></select>	
						</div>
						
						<div class="form-group">
							<label for="">Postal Code</label>
							<input type="text" class="form-control form-control-sm" placeholder="2020" name="ad_postal" required>
						</div>
						<div class="form-group mt-5">
							<button class="btn btn-primary btn-lg btn-block" type="submit" name="add_address">Save</button>
						</div>
						
					</div>
				</div>
			</form>

			
		</div>
	</div>

</div>	








<?php include('php/includes/footer.php'); ?>