<?php
include('php/admin_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');

if (!$_SESSION['admin_name']) {
  header("Location: index.php");
}

include('php/admin_dash_nav.php');

?>

<title>Users</title>

<div class="container-fluid">
	<div class="row">
		<?php include('php/admin_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Users</h1>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Fullname</th>
                    <th>Email</th>
                    <th>Phone</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
	              <?php

	              $query = "SELECT * FROM tbl_user_profile";

	              $selectAllUser = mysqli_query($conn, $query);

	              checkQueryError($selectAllUser);

	              while ($row = mysqli_fetch_assoc($selectAllUser)) {
	                $user_id = $row['user_id'];
	                $user_fullname = $row['user_fullname'];
	                $user_email = $row['user_email'];
                  $user_phone_no = $row['user_phone_no'];

	                echo "<tr>
	                        <td>$user_id</td>
	                        <td>$user_fullname</td>
	                        <td>$user_email</td>
                          <td>$user_phone_no</td>
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