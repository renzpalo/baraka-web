<?php
include('php/admin_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');

if (!$_SESSION['admin_name']) {
  header("Location: index.php");
}

include('php/admin_dash_nav.php');

$alertMessage = '';

if (isset($_GET['add_fseller'])) {
  $getSellerId = $_GET['add_fseller'];

  $query2 = "SELECT seller_id FROM tbl_featured_stores WHERE seller_id = '$getSellerId'";
  $selectSellerId = mysqli_query($conn, $query2);
  checkQueryError($selectSellerId);
  $numRows = mysqli_num_rows($selectSellerId);

  if ($numRows > 0) {
    header("Location: admin_sellers.php?status=exist");
  } else {
    $query = "INSERT INTO tbl_featured_stores (seller_id) VALUES ('$getSellerId')";

    $addFeaturedStore = mysqli_query($conn, $query);

    checkQueryError($addFeaturedStore);

    header("Location: admin_sellers.php?status=success");
  }

  
}

if (isset($_GET['status'])) {
  $status = $_GET['status'];

  if ($status == 'success') {
    $alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                      Successfully added to featured stores.
                      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                      </button>
                     </div>";
  } else if ($status == 'exist') {
    $alertMessage = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                      Seller already exist.
                      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                      </button>
                     </div>";
  }
}


?>

<title>Sellers</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/admin_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Stores</h1>
            <div>
              <?php echo $alertMessage; ?>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Seller Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
	              <?php

	              $query = "SELECT * FROM tbl_sellers";

	              $selectAllSellers = mysqli_query($conn, $query);

	              checkQueryError($selectAllSellers);

	              while ($row = mysqli_fetch_assoc($selectAllSellers)) {
	                $sellerId = $row['seller_id'];
	                $sellerName = $row['seller_name'];
	                $sellerInfo = $row['seller_info'];

	                echo "<tr>
	                        <td>$sellerId</td>
	                        <td>$sellerName</td>
	                        <td>$sellerInfo</td>
	                        <td>
	                          <a href='admin_sellers.php?add_fseller=$sellerId' class='btn btn-primary btn-sm'><i class='fas fa-store'></i></a>
	                        </td>
	                      </tr>";
	              }



                  ?>
                  
                </tbody>
              </table>
            </div>
          </div>
         
        </main>
	</div>
</div>


<?php include('php/admin_footer.php');