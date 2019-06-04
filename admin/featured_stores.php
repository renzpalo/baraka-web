<?php
include('php/admin_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');

if (!$_SESSION['admin_name']) {
  header("Location: index.php");
}

include('php/admin_dash_nav.php');

if (isset($_GET['del_fstore'])) {
  $getFStoreId = $_GET['del_fstore'];

  $query = "DELETE FROM tbl_featured_stores WHERE fstore_id = '$getFStoreId'";

  $deleteFStore = mysqli_query($conn, $query);
  checkQueryError($deleteFStore);
}

?>

<title>Featured Stores</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/admin_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Featured Stores</h1>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Seller Name</th>
                    <th>Description</th>
                    <th>Priority</th>
                    <th>Buttons</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
	              <?php

	              $query = "SELECT fstore.*, sellers.seller_name, sellers.seller_info FROM tbl_featured_stores fstore, tbl_sellers sellers WHERE fstore.seller_id = sellers.seller_id";

	              $selectAllSellers = mysqli_query($conn, $query);

	              checkQueryError($selectAllSellers);

	              while ($row = mysqli_fetch_assoc($selectAllSellers)) {
	                $fStoreId = $row['fstore_id'];
                  $fStorePriority = $row['fstore_priority'];
	                $sellerName = $row['seller_name'];
	                $sellerInfo = $row['seller_info'];

	                echo "<tr>
	                        <td>$fStoreId</td>
	                        <td>$sellerName</td>
	                        <td>$sellerInfo</td>
                          <td>$fStorePriority</td>
	                        <td>
                            <a href='featured_stores.php?del_fstore=$fStoreId' class='btn btn-danger btn-sm'><i class='fa fa-fw fa-trash'></i></a>
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