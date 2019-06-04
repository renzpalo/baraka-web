<?php
include('php/seller_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');
include('php/seller_dash_nav.php');

if (!$_SESSION['seller_name']) {
	header("Location: index.php");
}

$sellerId = $_SESSION['seller_id'];

?>

<title>Reviews</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/seller_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Reviews</h1>
        
      </div>
      <div class="row">
        <div class="col-sm-12">
          <table class="table">
            <thead>
              <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Summary</th>
                <th>Feedback</th>
                <th>Ratings</th>
                <th>Date</th>
                <th>Posted by</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $query = "SELECT reviews.*, products.prod_name FROM tbl_reviews reviews, tbl_products products WHERE reviews.prod_id = products.prod_id AND products.seller_id = '$sellerId' AND rev_status = 'reviewed'";

              $selectReviews = mysqli_query($conn, $query);

              checkQueryError($selectReviews);

              while ($row = mysqli_fetch_assoc($selectReviews)) {
                $rev_id = $row['rev_id'];
                $rev_posted_by = $row['rev_posted_by'];
                $rev_summary = $row['rev_summary'];
                $rev_feedback = $row['rev_feedback'];
                $rev_image = $row['rev_image'];
                $rev_rating = $row['rev_rating'];
                $rev_date = $row['rev_date_posted'];

                $prod_name = $row['prod_name'];

              ?>
              <tr>
                <td><img src="../images/reviews/<?php echo $rev_image; ?>" alt="<?php echo $rev_image; ?>" style="height: 100px;"></td>
                <td><?php echo $prod_name; ?></td>
                <td><?php echo $rev_summary; ?></td>
                <td><?php echo $rev_feedback; ?></td>
                <td><?php echo $rev_rating; ?></td>
                <td><?php echo $rev_date; ?></td>
                <td><?php echo $rev_posted_by; ?></td>
              </tr>

              <?php
              }
              ?>
              
            </tbody>
          </table>
        </div>
      </div>
    </main>
	</div>
</div>


<?php include('php/seller_footer.php'); ?>