<?php

include('connect.php');
include('../php/functions.php');

$userPhoneNo = validateFormData($_POST['user_phone_no']);
$userPassword = validateFormData($_POST['user_password']);

// $userPhoneNo = "renzpalo@gmail.com";
// $userPassword = "abc123";

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d");

$status = 0;
$message = "";
$information = "";

if (isset($userPhoneNo) && isset($userPassword) && !empty($userPhoneNo) && !empty($userPassword)) {
	
	global $connect;

	if (!$connect) {
		die("Connection failed: " .mysqli_connect_error());
	}

	$query = "SELECT * FROM tbl_user_profile profile, tbl_user_cred cred WHERE profile.user_id = cred.user_id AND cred.user_email = '$userPhoneNo'";

	$selectUser = mysqli_query($connect, $query);

	checkQueryError($selectUser);

	while ($row = mysqli_fetch_assoc($selectUser)) {
		$user_id = $row['user_id'];
		$user_fullname = $row['user_fullname'];
		$user_email = $row['user_email'];
		$user_phone_no = $row['user_phone_no'];
		$user_password = $row['user_password'];
	}

	if ($userPassword === $user_password) {
		$status = 1;
		$message = "Logged in successfully.";
		$information = array(
							'user_id' => $user_id,
							'user_fullname' => $user_fullname,
							'user_email' => $user_email,
							'user_phone_no' => $user_phone_no
							);
	} else {
		$status = 0;
		$message = "Invalid credentials.";
		$information = "Invalid credentials.";
	}

	// $stmt = $connect -> prepare("SELECT usercred.user_password, userprofile.user_id FROM tbl_user_profile userprofile, tbl_user_cred usercred WHERE userprofile.user_id = usercred.user_id AND userprofile.user_email = ?");
	// $stmt -> bind_param("s", $userPhoneNo);
	// $stmt -> execute();
	// $stmt -> store_result();
	// $stmt -> bind_result($col1, $col2);

	// while ($stmt -> fetch()) {
	// 	if ($col1 === $userPassword) {
	// 		$status = 1;
	// 		$message = "Logged in successfully.";
	// 		$information = $col2;
	// 	} else {
	// 		$status = 0;
	// 		$message = "Invalid credentials.";
	// 		$information = "Invalid credentials.";
	// 	}
	// }

	$postData = array(
					'status' => $status,
					'message' => $message,
					'information' => $information);

	$postData = json_encode($postData);

	echo $postData;

	mysqli_close($connect);

}

?>