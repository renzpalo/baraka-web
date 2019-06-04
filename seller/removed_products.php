<?php
include('php/seller_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');
include('php/seller_dash_nav.php');

if (!$_SESSION['seller_name']) {
	header("Location: index.php");
}

$prodId = "";
$prodName = "";
$prodPrice = "";
$prodStock = "";
$prodType = "";
$prodRatings = "";
$prodDatePosted = "";

if (isset($_GET['del_prod'])) {
  $getProdId = $_GET['del_prod'];

  $query = "DELETE FROM tbl_products WHERE prod_id = $getProdId";
  $deleteProduct = mysqli_query($conn, $query);

  checkQueryError($deleteProduct);

  $query2 = "DELETE FROM tbl_featured_products WHERE prod_id = $getProdId";
  $deleteFProd = mysqli_query($conn, $query2);
  checkQueryError($deleteFProd);

  header("Location: seller_products.php");
}


?>

<title>Products</title>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this product?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-dismiss="modal">Cancel</button>
        <a class="btn btn-danger modal_delete_link" href="">Delete</a>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
	<div class="row">
		<?php include('php/seller_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Removed Products</h1>

          </div>
          <div class="row">
            <div class="col-sm-12">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Type</th>
                    <th>Ratings</th>
                    <th>Date Posted</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                  <?php

                  $sellerId = $_SESSION['seller_id'];

                  $query = "SELECT * FROM tbl_removed_products WHERE seller_id = $sellerId";

                  $selectProducts = mysqli_query($conn, $query);

                  checkQueryError($selectProducts);

                  while ($row = mysqli_fetch_assoc($selectProducts)) {
                    $prodId = $row['prod_id'];
                    $prodName = $row['prod_name'];
                    $prodPrice = $row['prod_price'];
                    $prodStock = $row['prod_stock'];
                    $prodType = $row['prod_type'];
                    $prodRatings = $row['prod_rating'];
                    $prodDatePosted = $row['prod_date_posted'];

                    echo "<tr>
                            <td>$prodId</td>
                            <td>$prodName</td>
                            <td>$prodPrice</td>
                            <td>$prodStock</td>
                            <td>$prodType</td>
                            <td>$prodRatings</td>
                            <td>$prodDatePosted</td
                          </tr>";
                  }



                  ?>
                  
                </tbody>
              </table>

              <script>
                function confirmDelete(productId) {
                    var deleteUrl = "seller_products.php?del_prod=" + productId;

                    $(".modal_delete_link").attr("href", deleteUrl);

                    $("#confirmDeleteModal").modal('show');

                    // alert("Hello " + deleteUrl);
                }
              </script>
            </div>
          </div>
         
        </main>
	</div>
</div>




<?php include('php/seller_footer.php');