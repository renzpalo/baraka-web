<?php
include('php/seller_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');
include('php/seller_dash_nav.php');

if (!$_SESSION['seller_name']) {
	header("Location: index.php");
}

$sellerId = $_SESSION['seller_id'];

$alertMessage = '';

$extension = array("jpeg", "jpg", "png", "JPG");

if (isset($_POST['upload_banner'])) {
  $image = $_FILES['banner']['name'];

  

  $ext = pathinfo($image, PATHINFO_EXTENSION);

  // Temporary location
  $imageTemp = $_FILES['banner']['tmp_name'];

  $newFileName = time() . "_" . $image;

  $imageDirectory = "../images/banners/$newFileName";

  if (in_array($ext, $extension)) {
    move_uploaded_file($imageTemp, $imageDirectory);

  if(!move_uploaded_file($imageTemp, $imageDirectory) && !is_writable($imageDirectory)){
    $alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                      File was not uploaded.
                      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                      </button>
                     </div>";    
    } else {
    $query = "UPDATE tbl_sellers SET seller_banner = '$newFileName' WHERE seller_id = '$sellerId'";

    $insertProduct = mysqli_query($conn, $query);

    checkQueryError($insertProduct);

    $alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                      Banner updated.
                      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                      </button>
                     </div>";
    }
  } else {
    $alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
            File not supported.
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
           </div>"; 
  }

  
}

if (isset($_POST['upload_logo'])) {
  $image = $_FILES['logo']['name'];

  // Temporary location
  $imageTemp = $_FILES['logo']['tmp_name'];

  $ext = pathinfo($image, PATHINFO_EXTENSION);

  $newFileName = time() . "_" . $image;

  $imageDirectory = "../images/sellers/$newFileName";

  if (in_array($ext, $extension)) {
    move_uploaded_file($imageTemp, $imageDirectory);

  if(!move_uploaded_file($imageTemp, $imageDirectory) && !is_writable($imageDirectory)){
    $alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                      File was not uploaded.
                      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                      </button>
                     </div>";    
    } else {
    $query = "UPDATE tbl_sellers SET seller_image = '$newFileName' WHERE seller_id = '$sellerId'";

    $insertProduct = mysqli_query($conn, $query);

    checkQueryError($insertProduct);

    $alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                      Logo updated.
                      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                      </button>
                     </div>";
    }
  } else {
    $alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
            File not supported.
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
           </div>"; 
  }

  
}

if (isset($_POST['update_info'])) {
  $sellerName = validateFormData($_POST['seller_name']);
  $sellerInfo = validateFormData($_POST['seller_info']);

  $query = "UPDATE tbl_sellers SET seller_name = '$sellerName', seller_info = '$sellerInfo' WHERE seller_id = '$sellerId'";

  $insertProduct = mysqli_query($conn, $query);

  checkQueryError($insertProduct);

  $alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Store information updated.
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                      <span aria-hidden='true'>&times;</span>
                    </button>
                   </div>";
}


?>

<title>Store</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/seller_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Store</h1>

            <?php echo $alertMessage; ?>
            <?php

            $query5 = "SELECT * FROM tbl_sellers WHERE seller_id = '$sellerId'";

            $selectSeller = mysqli_query($conn, $query5);

            checkQueryError($selectSeller);

            while ($row5 = mysqli_fetch_assoc($selectSeller)) {
              $seller_name = $row5['seller_name'];
              $seller_info = $row5['seller_info'];
              $seller_image = $row5['seller_image'];
              $seller_banner = $row5['seller_banner'];
            }

            ?>
          </div>
          <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group row">
                  <label for="" class="col-sm-2 col-form-label col-form-label-sm">Banner</label>
                  <div class="col-sm-3">
                    <input type="file" multiple class="form-control-file form-control-sm" name="banner">
                  </div>
                  <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary" name="upload_banner">Upload Banner</button>
                  </div>
                  <div class="col-sm-1">
                    <img src="../images/sellers/<?php echo $seller_image; ?>" alt="" class="img-fluid">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-sm-2 col-form-label col-form-label-sm">Logo</label>
                  <div class="col-sm-3">
                    <input type="file" multiple class="form-control-file form-control-sm" name="logo">
                  </div>
                  <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary" name="upload_logo">Upload Logo</button>
                  </div>
                  <div class="col-sm-1">
                    <img src="../images/banners/<?php echo $seller_banner; ?>" alt="" class="img-fluid">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-sm-2 col-form-label col-form-label-sm">Store Name</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="seller_name" required value="<?php echo $seller_name; ?>">
                  </div>
                </div>
                
                <div class="form-group row">
                  <label for="" class="col-sm-2 col-form-label col-form-label-sm">Description</label>
                  <div class="col-sm-8">
                    <textarea id="" cols="30" rows="5" class="form-control" name="seller_info"><?php echo $seller_info; ?></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="" class="col-sm-2 col-form-label col-form-label-sm"></label>
                  <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary" name="update_info">Upload Information</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
    </main>
	</div>
</div>


<?php include('php/seller_footer.php'); ?>