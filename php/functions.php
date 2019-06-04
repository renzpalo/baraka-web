<?php



// clean form data to prevent INJECTIONS
/*
	built in functions used:
	
	trim()
	stripslashes()
	htmlspecialchars()
	strip_tags()
	str_replace()
*/

$alertMessage = "";

function validateFormData($formData) {
	$formData = trim(stripslashes(htmlspecialchars(strip_tags(str_replace(array( '(', ')' ), '', $formData)), ENT_QUOTES)));
	return $formData;
}

function checkQueryError($result) {
    global $conn;

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
}

function checkQueryError2($result) {
    global $connect;

    if (!$result) {
        die("Query failed: " . mysqli_error($connect));
    }
}

// function addToCart($productId, $productQuantity, $productPrice, $userId) {
// 	global $conn;

// 	// Get current date.
// 	date_default_timezone_set("Asia/Manila");

// 	// YYYY-MM-DD
// 	$date = date("Y-m-d H:i:s");

// 	$cartCount = 0;

// 	$prod_id = array();

// 	$doesNotExist = true;

// 	$query = "SELECT * FROM tbl_cart WHERE user_id = '$userId'";

// 	$selectUser = mysqli_query($conn, $query);

// 	checkQueryError($selectUser);

// 	while ($row = mysqli_fetch_assoc($selectUser)) {
// 		$cart_id = $row['cart_id'];
// 		$prod_id = $row['prod_id'];
// 		$user_id = $row['user_id'];

// 		$doesNotExist = false;
// 	}

// 	if ($doesNotExist) {
// 		$productArray[0] = array(
// 								'prod_id' => $productId,
// 								'prod_quantity' => $productQuantity,
// 								'prod_price' => $productPrice,
// 								'date' => $date
// 							);

// 		$productJson = json_encode($productArray);

// 		// echo $productJson;

// 		$query2 = "INSERT INTO tbl_cart (prod_id, user_id) VALUES ('$productJson', '$userId')";

// 		$addProductToCart = mysqli_query($conn, $query2);

// 		checkQueryError($addProductToCart);

// 		if ($addProductToCart) {
// 			// echo "Added to cart.";
// 		}
// 	} else {
// 		// Update
// 		$newProdArray = array(
// 							'prod_id' => $productId,
// 							'prod_quantity' => $productQuantity,
// 							'prod_price' => $productPrice,
// 							'date' => $date 
// 							);

// 		// Convert JSON
// 		$oldProdArray = json_decode($prod_id , true);

// 		$prodIdExist = false;

// 		foreach ($oldProdArray as $key) {
// 			if ($productId == $key['prod_id']) {
// 				$prodIdExist = true;
// 			}

// 			$cartCount = $cartCount + 1;
// 		}

// 		if ($prodIdExist) {
			
// 		} else {

// 			array_push($oldProdArray, $newProdArray);

// 			$newProdJson = json_encode($oldProdArray);

// 			// echo $newProdJson;

// 			$query3 = "UPDATE tbl_cart SET prod_id = '$newProdJson' WHERE user_id = '$user_id'";

// 			$updateProductId = mysqli_query($conn, $query3);

// 			checkQueryError($updateProductId);

// 			if ($updateProductId) {
// 				// echo "Added to cart.";
// 			}

// 		}
// 	}
// }

function addToCart($productId, $productPrice, $productQuantity, $userId) {
	global $conn;

	// Get current date.
	date_default_timezone_set("Asia/Manila");

	// YYYY-MM-DD
	$date = date("Y-m-d H:i:s");

	$cart_prod_quantity = 0;

	$doesNotExist = true;

	$query = "SELECT * FROM tbl_cart WHERE user_id = '$userId' AND prod_id = '$productId'";

	$selectUser = mysqli_query($conn, $query);

	checkQueryError($selectUser);

	while ($row = mysqli_fetch_assoc($selectUser)) {
		$cart_id = $row['cart_id'];
		$prod_id = $row['prod_id'];
		$user_id = $row['user_id'];
		$cart_prod_quantity = $row['cart_prod_quantity'];

		$doesNotExist = false;
	}

	if ($doesNotExist) {
		// Insert
		$query2 = "INSERT INTO tbl_cart (cart_prod_price, cart_prod_quantity, cart_date, prod_id, user_id) VALUES ('$productPrice', '$productQuantity', '$date', '$productId', '$userId')";

		$addProductToCart = mysqli_query($conn, $query2);

		checkQueryError($addProductToCart);

		if ($addProductToCart) {
			$query4 = "SELECT * FROM tbl_cart WHERE user_id = '$userId'";

			$getNumRows = mysqli_query($conn, $query4);

			checkQueryError($getNumRows);

			$numRows = mysqli_num_rows($getNumRows);

			header("Refresh: 0");

			$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
							 	Successfully added to cart.
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";
		}

	} else {
		$cart_prod_quantity = $cart_prod_quantity + $productQuantity;

		$query3 = "UPDATE tbl_cart SET cart_prod_quantity = '$cart_prod_quantity' WHERE user_id = '$user_id' AND prod_id = '$productId'";

		$updateProductId = mysqli_query($conn, $query3);

		checkQueryError($updateProductId);

		header("Refresh: 0");

		$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
							 	Successfully added to cart.
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";
	}


}

function addToWishlist($productId, $productPrice, $productQuantity, $userId) {
	global $conn;

	// Get current date.
	date_default_timezone_set("Asia/Manila");

	// YYYY-MM-DD
	$date = date("Y-m-d H:i:s");

	$cart_prod_quantity = 0;

	$doesNotExist = true;

	$query = "SELECT * FROM tbl_wishlist WHERE user_id = '$userId' AND prod_id = '$productId'";

	$selectUser = mysqli_query($conn, $query);

	checkQueryError($selectUser);

	while ($row = mysqli_fetch_assoc($selectUser)) {
		$wish_id = $row['wish_id'];
		$prod_id = $row['prod_id'];
		$user_id = $row['user_id'];
		$wish_prod_quantity = $row['wish_prod_quantity'];

		$doesNotExist = false;
	}

	if ($doesNotExist) {
		// Insert
		$query2 = "INSERT INTO tbl_wishlist (wish_prod_price, wish_prod_quantity, wish_date, prod_id, user_id) VALUES ('$productPrice', '$productQuantity', '$date', '$productId', '$userId')";

		$addProductToCart = mysqli_query($conn, $query2);

		checkQueryError($addProductToCart);

		if ($addProductToCart) {
			$query4 = "SELECT * FROM tbl_wishlist WHERE user_id = '$userId'";

			$getNumRows = mysqli_query($conn, $query4);

			checkQueryError($getNumRows);

			$numRows = mysqli_num_rows($getNumRows);

			header("Refresh: 0");

			$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
							 	Successfully added to wishlist.
							 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
							 		<span aria-hidden='true'>&times;</span>
							 	</button>
							 </div>";
		}

	} else {
		$wish_prod_quantity = $wish_prod_quantity + 1;

		$query3 = "UPDATE tbl_wishlist SET wish_prod_quantity = '$wish_id' WHERE user_id = '$user_id' AND prod_id = '$productId'";

		$updateProductId = mysqli_query($conn, $query3);

		checkQueryError($updateProductId);

		header("Refresh: 0");

		if ($updateProductId) {
			$alertMessage = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
						 	Product is already on wishlist.
						 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						 		<span aria-hidden='true'>&times;</span>
						 	</button>
						 </div>";
		}

	}
}


?>