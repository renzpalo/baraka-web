<?php
include('php/admin_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');


if (!$_SESSION['admin_name']) {
  header("Location: index.php");
}

include('php/admin_dash_nav.php');



$prodId = "";
$prodName = "";
$prodPrice = "";
$prodStock = "";
$prodType = "";
$prodRatings = "";
$prodDatePosted = "";

$alertMessage = "";

// if (isset($_GET['del_prod'])) {
//   $getProdId = $_GET['del_prod'];

//   $query = "DELETE FROM tbl_products WHERE prod_id = $getProdId";

//   $deleteProduct = mysqli_query($conn, $query);

//   checkQueryError($deleteProduct);

//   header("Location: admin_products.php");
// }

if (isset($_GET['add_fprod'])) {
  $getProdId = $_GET['add_fprod'];

  $query2 = "SELECT prod_id FROM tbl_featured_products WHERE prod_id = '$getProdId'";
  $selectProdId = mysqli_query($conn, $query2);
  checkQueryError($selectProdId);
  $numRows = mysqli_num_rows($selectProdId);

  if ($numRows > 0) {
    header("Location: admin_products.php?status=exist");
  } else {
    $query = "INSERT INTO tbl_featured_products (prod_id) VALUES ('$getProdId')";

    $addFeaturedProd = mysqli_query($conn, $query);

    checkQueryError($addFeaturedProd);

    header("Location: admin_products.php?status=success");
  }
  
}

if (isset($_GET['add_mbk'])) {
  $getProdId = $_GET['add_mbk'];

  $query2 = "SELECT prod_id FROM tbl_featured_products WHERE prod_id = '$getProdId'";
  $selectProdId = mysqli_query($conn, $query2);
  checkQueryError($selectProdId);
  $numRows = mysqli_num_rows($selectProdId);

  if ($numRows > 0) {
    header("Location: admin_products.php?status=exist");
  } else {
    $query = "INSERT INTO tbl_made_by_katutubos (prod_id) VALUES ('$getProdId')";
    $addMbk = mysqli_query($conn, $query);
    checkQueryError($addMbk);

    header("Location: admin_products.php?status=success");
  }

  
}

if (isset($_GET['status'])) {
  $status = $_GET['status'];

  if ($status == 'success') {
    $alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                      Successfully added to featured product.
                      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                      </button>
                     </div>";
  } else if ($status == 'exist') {
    $alertMessage = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                      Product already exist.
                      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                      </button>
                     </div>";
  }
}

if (isset($_GET['del_prod'])) {
  $getDelProd = $_GET['del_prod'];

  $query = "INSERT INTO tbl_removed_products 
              (prod_id, prod_name, prod_description, prod_price, prod_old_price, prod_stock, prod_image, prod_type, prod_min_quantity, prod_max_quantity, prod_ship_fee, prod_rating, prod_date_posted, prov_id, cat_id, subcat_id, rev_id, seller_id) 
              SELECT prod_id, prod_name, prod_description, prod_price, prod_old_price, prod_stock, prod_image, prod_type, prod_min_quantity, prod_max_quantity, prod_ship_fee, prod_rating, prod_date_posted, prov_id, cat_id, subcat_id, rev_id, seller_id FROM tbl_products WHERE prod_id = '$getDelProd'";

  $removeProduct = mysqli_query($conn, $query);

  checkQueryError($removeProduct);

  if ($removeProduct) {
    $query2 = "DELETE FROM tbl_products WHERE prod_id = '$getDelProd'";

    $deleteProduct = mysqli_query($conn, $query2);

    checkQueryError($deleteProduct);

    $alertMessage = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                      Successfully removed.
                      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                      </button>
                     </div>";
  }
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
        Are you sure you want to remove this product?
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
		<?php include('php/admin_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Products</h1>

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
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Type</th>
                    <th>Ratings</th>
                    <th>Date Posted</th>
                    <th>Seller</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                  <?php

                  $query = "SELECT products.*, seller.seller_name FROM tbl_products products, tbl_sellers seller WHERE products.seller_id = seller.seller_id";

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

                    $sellerName = $row['seller_name'];

                    echo "<tr>
                            <td>$prodId</td>
                            <td>$prodName</td>
                            <td>$prodPrice</td>
                            <td>$prodStock</td>
                            <td>$prodType</td>
                            <td>$prodRatings</td>
                            <td>$prodDatePosted</td>
                            <td>$sellerName</td>
                            <td>
                              <a href='admin_products.php?add_fprod=$prodId' class='btn btn-primary btn-sm'><i class='fas fa-shopping-bag'></i></a>
                              <a href='admin_products.php?add_mbk=$prodId' class='btn btn-success btn-sm'><i class='fas fa-leaf'></i></i></a>
                              <a href='#' class='btn btn-danger btn-sm del_product' onclick='confirmDelete($prodId)''><i class='fa fa-fw fa-trash'></i></a>
                            </td>
                          </tr>";
                  }



                  ?>
                  
                </tbody>
              </table>

              <script>
                function confirmDelete(productId) {
                    var deleteUrl = "admin_products.php?del_prod=" + productId;

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


<?php include('php/admin_footer.php');