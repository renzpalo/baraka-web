<?php

include('connect.php');

$userPhoneNo = htmlentities($_POST['user_phone_no']);

// $userPhoneNo = "09987654321";

$userPhoneNo = stripslashes($userPhoneNo);

$userId = "";

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d");

$status = 0;
$message = "";
$information = "";

if (isset($userPhoneNo) && !empty($userPhoneNo)) {
	global $connect;

	$userExist = false;

	if (!$connect) {
		die("Connection failed: " .mysqli_connect_error());
	}

	$stmt = $connect -> prepare("SELECT user_id FROM tbl_user_profile WHERE user_phone_no = ?");
	$stmt -> bind_param("s", $userPhoneNo);
	$stmt -> execute();
	$stmt -> store_result();
	$stmt -> bind_result($col1);

	while ($stmt -> fetch()) {
		$userExist = true;
		$userId = $col1;
	}

	// Send OPT SMS
	// Send response with OTP and User ID.

	if ($userExist) {
		
		// Random 6 Letter OTP
		$otpValue = substr(md5(microtime()), rand(0, 26), 6);;

		$status = 1;
		$message = "OTP sent successfully.";
		$information = array(
							'otp' => $otpValue,
							'user_id' => $userId,				
						);

	} else {
		$message = "Phone number does not exist.";
		$information = "Phone number not registered.";
	}

	$postData = array(
						'status' => $status,
						'message' => $message,
						'information' => $information
					);

	// Wrap the array into JSON Format.
	$postData = json_encode($postData);

	echo $postData;

	mysqli_close($connect);


}


?>