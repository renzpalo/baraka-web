<?php

include('connect.php');
include('../php/functions.php');


$securityCode = validateFormData($_POST['security_code']);
$productId = validateFormData($_POST['prod_id']);

// $securityCode = "123456";
// $productId = "1000000007";

$category = '';
$subcategory = '';

global $connect;

global $prodImageUrl;

if (isset($securityCode) && !empty($securityCode) && isset($productId) && !empty($productId)) {
	if (!$connect) {
		die("Connection error: " . mysqli_connect_error());
	}

	$status = 0;
	$message = "";
	$information = "";
	$productArray = array();
	$count = 0;

	$productReviews = array();
	$reviewCount = 0;

	$relatedProducts = array();
	$relatedProdCount = 0;

	

	$query = "SELECT products.*, province.prov_name, seller.seller_name FROM tbl_products products, tbl_province province, tbl_sellers seller WHERE products.prod_id = '$productId' AND products.prov_id = province.prov_id AND products.seller_id = seller.seller_id";

	$selectProdDet = mysqli_query($connect, $query);

	checkQueryError($selectProdDet);

	while ($row = mysqli_fetch_array($selectProdDet)) {
		$prodId = $row['prod_id'];
		$prodName = $row['prod_name'];
		$prodDesc = $row['prod_description'];
		$prodPrice = $row['prod_price'];
		$prodOldPrice = $row['prod_old_price'];
		$prodStock = $row['prod_stock'];
		$prodImage = $row['prod_image'];
		$prodType = $row['prod_type'];
		$prodShippingFee = $row['prod_ship_fee'];
		$prodRating = $row['prod_rating'];

		$category = $row['cat_id'];
		$subcategory = $row['subcat_id'];
		$province = $row['prov_name'];
		$seller = $row['seller_name'];
		

		$status = 1;
		$message = "Displaying information.";
		$productArray[$count] = array(
									'prod_id' => $prodId,
									'prod_name' => $prodName,
									'prod_desc' => $prodDesc,
									'prod_price' => $prodPrice,
									'prod_old_price' => $prodOldPrice,
									'prod_stock' => $prodStock,
									'prod_image' => $prodImageUrl . $prodImage,
									'prod_type' => $prodType,
									'prod_ship_fee' => $prodShippingFee,
									'prod_rating' => $prodRating,
									'province' => $province,
									'seller' => $seller
									);

		$count = $count + 1;
	}

	if ($selectProdDet) {
		$query4 = "UPDATE tbl_product_stats SET stats_views = stats_views + 1 WHERE prod_id = '$prodId'";

		$updateStats = mysqli_query($connect, $query4);

		checkQueryError2($updateStats);
	}

	$query2 = "SELECT reviews.*, user.user_fullname FROM tbl_reviews reviews, tbl_user_profile user WHERE prod_id = '$prodId' AND reviews.user_id = user.user_id";

	$selectReviews = mysqli_query($connect, $query2);

	checkQueryError($selectReviews);

	while ($row2 = mysqli_fetch_array($selectReviews)) {
		$revId = $row2['rev_id'];
		$revSummary = $row2['rev_summary'];
		$revFeedback = $row2['rev_feedback'];
		$revImage = $row2['rev_image'];
		$revRating = $row2['rev_rating'];
		$revDate = $row2['rev_date_posted'];
		$userName = $row2['user_fullname'];

		$productReviews[$reviewCount] = array(
											'rev_id' => $revId,
											'rev_summary' => $revSummary,
											'rev_feedback' => $revFeedback,
											'rev_image' => $revImage,
											'rev_rating' =>  $revRating,
											'rev_date' => $revDate,
											'user_name' => $userName
											);

		$reviewCount = $reviewCount + 1;
	}

	$query3 = "SELECT * FROM tbl_products WHERE subcat_id = '$subcategory' AND prod_id <> '$prodId' LIMIT 10";

	$selectRelatedProducts = mysqli_query($connect, $query3);

	checkQueryError($selectRelatedProducts);

	while ($row3 = mysqli_fetch_array($selectRelatedProducts)) {
		$relProdId = $row3['prod_id'];
		$relProdName = $row3['prod_name'];
		$relProdPrice = $row3['prod_price'];
		$relProdOldPrice = $row3['prod_old_price'];
		$relProdImage = $row3['prod_image'];
		$relProdRating = $row3['prod_rating'];

		$relatedProducts[$relatedProdCount] = array(
									'rel_prod_id' => $relProdId,
									'rel_prod_name' => $relProdName,
									'rel_prod_price' => $relProdPrice,
									'rel_prod_disc_price' => $relProdOldPrice,
									'rel_prod_image' => $prodImageUrl . $relProdImage,
									'rel_prod_rating' => $relProdRating);

		$relatedProdCount = $relatedProdCount + 1;
	}

	$products = $productArray;
	$reviews = $productReviews;
	$relatedProds = $relatedProducts;

	$information = array(
						'product_details' => $products,
						'reviews' => $reviews,
						'related_products' => $relatedProds
					);

	
	$postData = array(
					'status' => $status,
					'message' => $message,
					'information' => $information);

	$postData = json_encode($postData);

	echo $postData;

	mysqli_close($connect);

} else {
	$status = 0;
	$message = "No products found.";
	$information = "No products found.";
}



?>