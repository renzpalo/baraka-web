<?php

include('connect.php');
include('../php/functions.php');

global $connect;

$securitycode = validateFormData($_POST['security_code']);

$adFullname = validateFormData($_POST['ad_fullname']);
$adContact = validateFormData($_POST['ad_contact']);
$adStreet = validateFormData($_POST['ad_street']);
$adBarangay = validateFormData($_POST['ad_barangay']);
$adCityMuni = validateFormData($_POST['ad_city_muni']);
$adProvince = validateFormData($_POST['ad_province']);
$adZipcode = validateFormData($_POST['ad_zipcode']);
$adNotes = validateFormData($_POST['ad_notes']);

$userId = validateFormData($_POST['user_id']);

// $securitycode = "1234";

// $adFullname = "Renz Palo";
// $adContact = "09455071146";
// $adStreet = "#123 Street";
// $adBarangay = "Sto. Rosario";
// $adCityMuni = "Sto. Tomas";
// $adProvince = "Pampanga";
// $adZipcode = "2020";
// $adNotes = "House with a yellow gate.";

// $userId = "1000016";

if (isset($securitycode) && !empty($securitycode) && !empty($adFullname) && !empty($adContact) && !empty($adStreet) && !empty($adBarangay) && !empty($adCityMuni) && !empty($adProvince) && !empty($adZipcode) && !empty($userId)) {
	
	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "Failed to add user address.";
	$information = "Failed to add user address.";

	$doesNotExist = true;

	$query = "SELECT * FROM tbl_user_address WHERE user_id = '$userId'";

	$selectUser = mysqli_query($connect, $query);

	checkQueryError($selectUser);

	while ($row = mysqli_fetch_assoc($selectUser)) {
		$ad_id = $row['ad_id'];
		$ad_fulname = $row['ad_fullname'];
		$ad_contact = $row['ad_contact'];
		$ad_street = $row['ad_street'];
		$ad_barangay = $row['ad_barangay'];
		$ad_city_muni = $row['ad_city_muni'];
		$ad_province = $row['ad_province'];
		$ad_zipcode = $row['ad_zipcode'];
		$ad_notes = $row['ad_notes'];


		$user_id = $row['user_id'];

		$doesNotExist = false;
	}

	$query2 = "INSERT INTO tbl_user_address (ad_fullname, ad_contact, ad_street, ad_barangay, ad_city_muni, ad_province, ad_zipcode, ad_notes, user_id) 				
				VALUES ('$adFullname', '$adContact', '$adStreet', '$adBarangay', '$adCityMuni', '$adProvince', '$adZipcode', '$adNotes', '$userId')";

	$addAddress = mysqli_query($connect, $query2);

	checkQueryError($addAddress);

	if ($addAddress) {
		$status = 1;
		$message = "Inserted. Successfully added user address.";
		$information = "Inserted. Successfully added user address.";
	}

	$postData = array(
					'status' => $status,
					'message' => $message,
					'information' => $information
					);

	$postData = json_encode($postData);

	echo $postData;

	mysqli_close($connect);


} else {
	$status = 0;
	$message = "Failed to add user address.";
	$information = "Failed to add user address.";
}



?>