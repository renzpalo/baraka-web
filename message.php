<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

if (!$_SESSION['user_id']) {
	header("Location: index.php");
}

$userId = $_SESSION['user_id'];

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

		$query4 = "INSERT INTO tbl_message_texts (text_message, user_id, user_role, text_date, message_id) VALUES ('$textMessage', '$userId', 'buyer', '$date', '$getMessageId')";
		$sendTextMessage = mysqli_query($conn, $query4);
		checkQueryError($sendTextMessage);
	}
}



?>

<title>Messages</title>

<div class="container main-page">

	<div class="row mt-5 bg-white rounded shadow-lg pb-5 pt-5">
		<div class="col-sm-3 mt-5 mb-5 border-right">
			<?php include('php/includes/user_sidebar.php'); ?>
		</div>
 
		<div class="col-sm-9">
			<div class="row">
				<div class="col-sm-12">
					<div class="row">
						<div class="col-sm-4 mt-5 mb-5">
							<div class="list-group">
								<?php

								$query3 = "SELECT messages.*, sellers.seller_name FROM tbl_messages messages, tbl_sellers sellers WHERE messages.user_id = '$userId' AND messages.seller_id = sellers.seller_id";
								$selectAllMessages = mysqli_query($conn, $query3);
								checkQueryError($selectAllMessages);

								while ($row3 = mysqli_fetch_assoc($selectAllMessages)) {
									$message_id = $row3['message_id'];
									$seller_name = $row3['seller_name'];

									echo "<a href='message.php?m_id=$message_id' class='list-group-item list-group-item-action'>$seller_name</a>";

								}
								?>
							</div>
						</div>
						<div class="col-sm-8 mt-5 mb-5">
							<div class="rounded border chat-box p-3">
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
			
		</div>
	</div>

</div>








<?php include('php/includes/footer.php'); ?>


<script>

$(document).ready(function() {
	$(".seller").addClass("bg-primary rounded p-3 text-white");
	$(".buyer").addClass("bg-light rounded p-3");

	$(".chat-box").scrollTop($(".chat-box")[0].scrollHeight);
});

</script>