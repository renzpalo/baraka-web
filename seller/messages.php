<?php
include('php/seller_header.php');
include('../php/includes/connect.php');
include('../php/functions.php');
include('php/seller_dash_nav.php');

if (!$_SESSION['seller_name']) {
	header("Location: index.php");
}

$sellerId = $_SESSION['seller_id'];

$alertMessage = "";

// Get current date.
date_default_timezone_set("Asia/Manila");

// YYYY-MM-DD
$date = date("Y-m-d H:i:s");

if (isset($_GET['s_id'])) {
  $getStoreId = validateFormData($_GET['s_id']);

  $query = "SELECT message_id FROM tbl_messages WHERE seller_id = '$getStoreId'";
  $selectMessage = mysqli_query($conn, $query);
  checkQueryError($selectMessage);



  if (mysqli_num_rows($selectMessage) > 0) {
    $row = mysqli_fetch_assoc($selectMessage);
    $messageId = $row['message_id'];

    header("Location: message.php?m_id=$messageId");
  } else {
    $query2 = "INSERT INTO tbl_messages (user_id, seller_id, message_date) VALUES ('$userId', '$getStoreId', '$date')";
    $insertMessage = mysqli_query($conn, $query2);
    $messageId = mysqli_insert_id($conn);
    checkQueryError($insertMessage);

    if ($insertMessage) {
      header("Location: message.php?m_id=$messageId");
    }
  }
}

if (isset($_GET['m_id'])) {
  $getMessageId = validateFormData($_GET['m_id']);

  if (isset($_POST['send_message'])) {
    $textMessage = validateFormData($_POST['text_message']);

    $query4 = "INSERT INTO tbl_message_texts (text_message, user_id, user_role, text_date, message_id) VALUES ('$textMessage', '$userId', 'seller', '$date', '$getMessageId')";
    $sendTextMessage = mysqli_query($conn, $query4);
    checkQueryError($sendTextMessage);
  }
}

?>

<title>Seller Messages</title>



<div class="container-fluid">
	<div class="row">
		<?php include('php/seller_sidebar.php'); ?>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Messages</h1>

          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-4 mt-5 mb-5">
                  <div class="list-group">
                    <?php

                    $query3 = "SELECT messages.*, profile.user_fullname FROM tbl_messages messages, tbl_user_profile profile WHERE messages.seller_id = '$sellerId' AND messages.user_id = profile.user_id";
                    $selectAllMessages = mysqli_query($conn, $query3);
                    checkQueryError($selectAllMessages);

                    while ($row3 = mysqli_fetch_assoc($selectAllMessages)) {
                      $message_id = $row3['message_id'];
                      $user_fullname = $row3['user_fullname'];

                      echo "<a href='messages.php?m_id=$message_id' class='list-group-item list-group-item-action'>$user_fullname</a>";

                    }
                    ?>
                  </div>
                </div>
                <div class="col-sm-8 mt-5 mb-5">
                  <div class="rounded border chat-box p-3 bg-white">
                    <?php

                    if (isset($_GET['m_id'])) {
                      $getMessageId = validateFormData($_GET['m_id']);

                      $query5 = "SELECT * FROM tbl_message_texts WHERE message_id = '$getMessageId'";
                      $selectMessageTexts = mysqli_query($conn, $query5);
                      checkQueryError($selectMessageTexts);

                      while ($row5 = mysqli_fetch_assoc($selectMessageTexts)) {
                        $text_id = $row5['text_id'];
                        $text_message = $row5['text_message'];
                        $user_id = $row5['user_id'];
                        $user_role = $row5['user_role'];
                        $text_date = $row5['text_date'];

                      ?>


                        <p class="<?php echo $user_role; ?>">
                          <?php echo $text_message; ?>
                          <br>
                          <small><?php echo $text_date; ?></small>
                        </p>


                      <?php
                      }
                    }


                    ?>
                  </div>
                  <hr>
                  <form action="" method="post">
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Message" required name="text_message">
                      <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" name="send_message">Send</button>
                      </div>
                    </div>
                  </form>
                  
                </div>
              </div>
            </div>
          </div>

    </main>
	</div>
</div>


<?php include('php/seller_footer.php'); ?>

<script>

$(document).ready(function() {
  $(".buyer").addClass("bg-primary rounded p-3 text-white");
  $(".seller").addClass("bg-light rounded p-3");


  $(".chat-box").scrollTop($(".chat-box")[0].scrollHeight);

});

</script>