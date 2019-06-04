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

if (isset($_POST['update_email'])) {
  $email = validateFormData($_POST['email']);

  $query = "UPDATE tbl_sellers SET seller_email = '$email' WHERE seller_id = '$sellerId'";

  $updateEmail = mysqli_query($conn, $query);

  checkQueryError($updateEmail);

  if ($updateEmail) {
    $alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
              Successfully updated email address.
              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
             </div>";
  } else {
    $alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
              Failed to update email.
              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
             </div>";
  }
}

if (isset($_POST['update_password'])) {
  $currentPassword = validateFormData($_POST['current_password']);
  $newPassword = validateFormData($_POST['new_password']);
  $retypePassword = validateFormData($_POST['retype_password']);

  $query = "SELECT seller_password FROM tbl_sellers WHERE seller_id = '$sellerId'";
  $selectPassword = mysqli_query($conn, $query);
  checkQueryError($selectPassword);
  $row = mysqli_fetch_assoc($selectPassword);

  $password = $row['seller_password'];

  if ($currentPassword === $password) {
    if ($newPassword === $retypePassword) {
      $query2 = "UPDATE tbl_sellers SET seller_password = '$newPassword' WHERE seller_id = '$sellerId'";
      $updatePassword = mysqli_query($conn, $query2);
      checkQueryError($updatePassword);

      if ($updatePassword) {
        $alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                  Successfully updated.
                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                  </button>
                 </div>";
      }
    } else {
      $alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Retype Password does not match.
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span>
                </button>
               </div>";
    }
  } else {
    $alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
              Current Password does not match.
              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
             </div>";
  }

  
}

?>

<title>Seller Account</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/seller_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Account</h1>
        <div>
          <?php echo $alertMessage; ?>
        </div>
      </div>

      <?php
        $query = "SELECT * FROM tbl_sellers WHERE seller_id = '$sellerId'";

        $selectUser = mysqli_query($conn, $query);

        checkQueryError($selectUser);

        while ($row = mysqli_fetch_assoc($selectUser)) {
          $seller_email = $row['seller_email'];
        }
      ?>

      <div class="row">
        <div class="col-sm-12">
          <div class="row">
            <div class="col-sm-4">
              <form action="" method="post">
                <div class="form-group">
                  <label for="">Email</label>
                  <input type="email" class="form-control" required name="email" value="<?php echo $seller_email; ?>">
                </div>
                <div class="form-group">
                  <button class="btn btn-primary btn-block" type="submit" name="update_email">Update Email</button>
                </div>
              </form>
            </div>
            <div class="col-sm-4">
              <form action="" method="post">
                <div class="form-group">
                  <label for="">Current Password</label>
                  <input type="password" class="form-control" required name="current_password" minlength="6">
                </div>
                <div class="form-group">
                  <label for="">New Password</label>
                  <input type="password" class="form-control" required name="new_password" minlength="6">
                </div>
                <div class="form-group">
                  <label for="">Retype Password</label>
                  <input type="password" class="form-control" required name="retype_password" minlength="6">
                </div>
                <div class="form-group">
                  <button class="btn btn-primary btn-block" type="submit" name="update_password">Update Password</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

         
    </main>
	</div>
</div>


<?php include('php/seller_footer.php');