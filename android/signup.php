<?php

include('connect.php');

// tbl_user_profile
// user_seq_no
// user_fullname
// user_address
// user_phone_no
// user_id
// user_date_created

// tbl_user_cred
// user_seq_no
// user_username
// user_password

// Get the value from Android.
$userFullname = htmlentities($_POST['user_fullname']);
$userPhoneNo = htmlentities($_POST['user_phone_no']);
$userEmail = htmlentities($_POST['user_username']);
$userPassword = htmlentities($_POST['user_password']);

// $userFullname = "Renz Palo";
// $userPhoneNo = "09987654328";
// $userEmail = "renzpalo@gmail.com";
// $userPassword = "abc123";

$userAddress = "";

// Filter the data.
// Remove backslashes.
$userFullname = stripslashes($userFullname);
$userPhoneNo = stripslashes($userPhoneNo);
$userEmail = stripslashes($userEmail);
$userPassword = stripslashes($userPassword);

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d");

$status = 0;
$message = "";

if (isset($userPhoneNo) && isset($userEmail) && !empty($userPhoneNo) && !empty($userEmail)) {

	global $connect;

	if (!$connect) {
		die("Connection failed: " .mysqli_connect_error());
	}

	// Check if User is already exists.
	$isNotExist = true;

	// Get the lastest user_id.
	// LIMIT 1 - give the last row result
	$stmt1 = $connect -> prepare("SELECT user_id FROM tbl_user_profile WHERE user_email = ?");
	$stmt1 -> bind_param("s", $userEmail);
	$stmt1 -> execute();
	$stmt1 -> store_result();

	// Define initial value = 10000
	$userId = 1000000;

	while ($stmt1 -> fetch()) {
		$isNotExist = false;
		echo "Email already exist.";
	}

	if ($isNotExist) {

		// Get the lastest user_id.
		// LIMIT 1 - give the last row result
		$stmt = $connect -> prepare("SELECT user_id FROM tbl_user_profile ORDER BY  user_seq_no DESC LIMIT 1");

		$stmt -> execute();
		$stmt -> store_result();
		$stmt -> bind_result($col1);

		// Define initial value = 10000
		$userId = 1000000;

		while ($stmt -> fetch()) {
			$userId = $col1;
		}

		$userId = $userId + 1;

		// echo "User ID is " .$userId;

		$stmt2 = $connect -> prepare("INSERT INTO tbl_user_profile (user_fullname, user_address, user_email, user_phone_no, user_id, user_date_created) VALUES (?, ? , ? , ? , ?, ?)");

		$stmt2 -> bind_param("ssssis", $userFullname, $userAddress, $userEmail, $userPhoneNo, $userId, $date);
		$stmt2 -> execute();

		// echo "Row ID is " .$stmt2 -> insert_id;

		// Insert data to tbl_user_cred
		if (!empty($stmt2 -> insert_id)) {
			$stmt3 = $connect -> prepare("INSERT INTO tbl_user_cred (user_id, user_email, user_phone_no, user_password) VALUES (?, ?, ?, ?)");

			$stmt3 -> bind_param("isss", $userId, $userEmail, $userPhoneNo, $userPassword);
			$stmt3 -> execute();

			$status = 1;
			$message = "Registered successfully.";
			$userId = $userId;

			// echo "Username and Password is " .$stmt3 -> insert_id;
		} else {
			$message = "Failed to register.";
		}

		$postData = array(
							'status' => $status,
							'message' => $message,
							'information' => $userId
						);

		// Wrap the array into JSON Format.
		$postData = json_encode($postData);

		echo $postData;

		mysqli_close($connect);

	}

}

?>