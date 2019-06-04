<?php
include('php/admin_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');

if (!$_SESSION['admin_name']) {
  header("Location: index.php");
}

include('php/admin_dash_nav.php');

if (isset($_GET['del_mbk'])) {
  $getFProdId = $_GET['del_mbk'];

  $query = "DELETE FROM tbl_made_by_katutubos WHERE mbk_id = $getFProdId";

  $deleteProduct = mysqli_query($conn, $query);

  checkQueryError($deleteProduct);

  header("Location: made_by_katutubos.php");
}

?>

<title>Featured Products</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/admin_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Featured Products</h1>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Type</th>
                    <th>Seller</th>
                    <th>Date Posted</th>
                    <th>Buttons</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                  <?php

                  $query = "SELECT mbk.*, products.*, seller.seller_name FROM tbl_made_by_katutubos mbk, tbl_products products, tbl_sellers seller WHERE mbk.prod_id = products.prod_id AND products.seller_id = seller.seller_id";

                  $selectFeaturedProducts = mysqli_query($conn, $query);

                  checkQueryError($selectFeaturedProducts);

                  while ($row = mysqli_fetch_assoc($selectFeaturedProducts)) {
                    $mbkId = $row['mbk_id'];
                    $prodName = $row['prod_name'];
                    $prodType = $row['prod_type'];
                    $prodDatePosted = $row['prod_date_posted'];
                    $sellerName = $row['seller_name'];

                    echo "<tr>
                            <td>$mbkId</td>
                            <td>$prodName</td>
                            <td>$prodType</td>
                            <td>$sellerName</td>
                            <td>$prodDatePosted</td>
                            <td>
                              <a href='made_by_katutubos.php?del_mbk=$mbkId' class='btn btn-danger btn-sm'><i class='fa fa-fw fa-trash'></i></a>
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