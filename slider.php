<?php 
include('php/includes/header.php');

include('php/includes/connect.php');
include('php/functions.php');

include('php/includes/top-navbar.php');
include('php/includes/navbar.php');

$alertMessage = '';

$prodId = 100000;

if (isset($_POST['upload'])) {

	extract($_POST);

	$extension=array("jpeg", "jpg", "png");

	foreach($_FILES["image_files"]["tmp_name"] as $key=>$tmp_name) {

        $imageFile = $_FILES["image_files"]["name"][$key];
        $imageFileTmp = $_FILES["image_files"]["tmp_name"][$key];

        $ext = pathinfo($imageFile, PATHINFO_EXTENSION);

        $newFileName = time() . "_" . $imageFile;

        $imageDirectory = "images/multi/$newFileName";

        if(in_array($ext, $extension)) {
        	move_uploaded_file($imageFileTmp, $imageDirectory);

            if(!move_uploaded_file($imageFileTmp, $imageDirectory) && !is_writable($imageDirectory)){
						$alertMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
								 	File was not uploaded.
								 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								 		<span aria-hidden='true'>&times;</span>
								 	</button>
								 </div>";		 
			} else {
				$query = "INSERT INTO tbl_product_images (prod_image, prod_image_sequence, prod_id) VALUES ('$newFileName', '$key', '100000')";

				$insertImages = mysqli_query($conn, $query);

				checkQueryError($insertImages);

				if ($insertImages) {
					// Store to database
					$alertMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
									 	Sucessfully uploaded.
									 	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
									 		<span aria-hidden='true'>&times;</span>
									 	</button>
									 </div>";
				}			
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
}

?>

<div class="container-fluid">
	<div class="row mt-5 mb-5 bg-white p-5">
		<div class="col-sm-4">
			<div id="productImages" class="carousel slide" data-ride="carousel">
			  
			  <div class="carousel-inner">

			  	<?php

				$query = "SELECT * FROM tbl_product_images WHERE prod_id = '$prodId' ORDER BY prod_image_sequence DESC";

				$selectImages = mysqli_query($conn, $query);

				checkQueryError($selectImages);

				$row = mysqli_fetch_assoc($selectImages);

				$prod_image = $row['prod_image'];

				echo "<div class='carousel-item active'> 
				      <img class='d-block m-auto w-100' src='images/multi/$prod_image' alt='$prod_image' style=''>
				    </div>";

				while ($row = mysqli_fetch_assoc($selectImages)) {
					$prod_image = $row['prod_image'];

					echo "<div class='carousel-item'>
					      <img class='d-block w-100' src='images/multi/$prod_image' alt='$prod_image' style=''>
					    </div>";
				}

				?>
				<ol class="carousel-indicators shadow-lg">
				<?php

					$numRows = mysqli_num_rows($selectImages);

					for ($i = 0; $i < $numRows; $i++) { 
						echo "<li data-target='#productImages' data-slide-to='$i' class='active shadow'></li>";
					}


				?>
				</ol>
			  </div>
			  <a class="carousel-control-prev" href="#productImages" role="button" data-slide="prev">
			    <span class="carousel-control-prev-icon rounded-circle" aria-hidden="true"></span>
			    <span class="sr-only">Previous</span>
			  </a>
			  <a class="carousel-control-next" href="#productImages" role="button" data-slide="next">
			    <span class="carousel-control-next-icon rounded-circle" aria-hidden="true"></span>
			    <span class="sr-only">Next</span>
			  </a>
			</div>
		</div>

		<div class="col-sm-6">

			<?php echo $alertMessage; ?>

			<form action="" method="post" enctype="multipart/form-data">
				

				<div class="form-group">
					<label for=""></label>
					<input type="file" name="image_files[]" multiple>
				</div>
				<div class="form-group">
					<button type="submit" name="upload" class="btn btn-primary">Upload Images</button>
				</div>
			</form>
		</div>
			
	</div>

	<div class="row">
		<?php

		// // Get current date.
		// date_default_timezone_set("Asia/Manila");

		// // YYYY-MM-DD
		// $date = date("Y-m-d H:i:s");

		// $order_id = "1000000030";

		// $query8 = "SELECT orders.*, order_products.* FROM tbl_orders orders, tbl_order_products order_products WHERE orders.order_id = order_products.order_id AND order_products.order_id = '$order_id'";

  //   	$selectOrderProducts = mysqli_query($conn, $query8);

  //   	checkQueryError($selectOrderProducts);

  //   	while ($row8 = mysqli_fetch_assoc($selectOrderProducts)) {

  //   		$order_id = $row8['order_id'];
  //   		$prod_id = $row8['prod_id'];
  //   		$user_id = $row8['user_id'];
  //   		$seller_id = $row8['seller_id'];
  //   		$order_prod_quantity = $row8['order_prod_quantity'];
  //   		$order_prod_price = $row8['order_prod_price'];

  //   		$order_prod_total = $order_prod_price * $order_prod_quantity;

  //   		$query9 = "UPDATE tbl_product_stats SET stats_sales = stats_sales + '$order_prod_quantity' WHERE prod_id = '$prod_id'";

		// 	$updateStats = mysqli_query($conn, $query9);

		// 	checkQueryError($updateStats);

		// 	$query10 = "UPDATE tbl_product_stats SET stats_earnings = stats_earnings + '$order_prod_total' WHERE prod_id = '$prod_id'";

		// 	$updateStatsEarnings = mysqli_query($conn, $query10);

		// 	checkQueryError($updateStatsEarnings);

		// 	if ($updateStatsEarnings) {
		// 		$query11 = "INSERT INTO tbl reviews (rev_summary, rev_feedback, rev_image, rev_rating, rev_date_posted, user_id, prod_id) 
		// 						VALUES ('', '', '', '0', '$date', '$user_id', 'prod_id')";

		// 		$insertReviews = mysqli_query($conn, $query11);

		// 		checkQueryError($insertReviews);

		// 		echo "Success";
		// 	}

  //   		// Update Order Stats
  //   		// Send Review Link
  //   		// Insert Payment
  //   	}


		?>
	</div>

	<style>
		table {
		  margin: 0 auto;
		  text-align: center;
		  border-collapse: collapse;
		  border: 1px solid #d4d4d4;
		  font-size: 20px;
		  background: #fff;
		}
		 
		table th, 
		table tr:nth-child(2n+2) {
		  background: #e7e7e7;
		}
		  
		table th, 
		table td {
		  padding: 20px 50px;
		}
		  
		table th {
		  border-bottom: 1px solid #d4d4d4;
		}

		.stars-outer {
		  display: inline-block;
		  position: relative;
		  font-family: FontAwesome;
		}
		 
		.stars-outer::before {
		   font-family: "Font Awesome 5 Free";
		   content: "\f005 \f005 \f005 \f005 \f005";
		}
		 
		.stars-inner {
		  position: absolute;
		  top: 0;
		  left: 0;
		  white-space: nowrap;
		  overflow: hidden;
		  width: 0;
		}
		 
		.stars-inner::before {
		   font-family: "Font Awesome 5 Free";
		   content: "\f005 \f005 \f005 \f005 \f005";
		   font-weight: 900;
		   color: #f8ce0b;
		}

		.fa-star {

		}
/*
		.stars-inner:before {
		   font-family: "Font Awesome 5 Free";
		   content: "\f005 \f005 \f005 \f005 \f005";
		   display: inline-block;
		   padding-right: 3px;
		   vertical-align: middle;
		   font-weight: 900;
		}*/
	</style>

	<script>
		var ratings = {
		  hotel_a: 2.8,
		  hotel_b: 3.3,
		  hotel_c: 1.9,
		  hotel_d: 4.3,
		  hotel_e: 4.74
		};

		// total number of stars
		var starTotal = 5;

		for (var rating in ratings) {
		  var starPercentage = ratings[rating] / starTotal * 100;
		  var starPercentageRounded = Math.round(starPercentage / 10) * 10 + "%";
		  document.querySelector("." + rating + " .stars-inner").style.width = starPercentageRounded;
		}

		// Get all stars with class of star and turn it into an array
const stars = Array.from(document.querySelectorAll('.star'));
console.log(stars);

// Loop through all the classes
stars.forEach(star => {
  // Get star rating within the data attribute value
  const dataRating = star.dataset.rating;
  console.log(dataRating);
  // total number of stars
  const starTotal = 5;

  // Turn the value into a percentage.
  const starPercentage = (dataRating / starTotal) * 100;
  console.log(starPercentage);
  const starPercentageRounded = `${(Math.round(starPercentage / 10) * 10)}%`;
  // Add the percentage value to the class
  star.style.width = starPercentageRounded;
  console.log(starPercentageRounded);
})

	</script>

	<div class="row">
		<div class="col-sm-6">
			<table>
			 <thead>
			    <tr>
			      <th>Hotel</th>
			      <th>Rating</th>
			    </tr>
			  </thead>
			  <tbody>
			    <tr class="hotel-a">
			      <td>Hotel A</td>
			      <td>
			        <div class="stars-outer">
			          <div class="stars-inner"></div>
			        </div>
			      </td>
			    </tr>
			    <tr class="hotel-b">
			      <td>Hotel B</td>
			      <td>
			        <div class="stars-outer">
			          <div class="stars-inner"></div>
			        </div>
			      </td>
			    </tr>
			 
			    <!-- 3 more rows here -->
			     
			  </tbody>
			</table>
		</div>

		<div class="col-sm-6">
			<style>
			body {
  font-family:"Open Sans", Helvetica, Arial, sans-serif;
  color:#555;
  max-width:680px;
  margin:0 auto;
  padding:0 20px;
}

* {
  -webkit-box-sizing:border-box;
  -moz-box-sizing:border-box;
  box-sizing:border-box;
}

*:before, *:after {
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
}

.clearfix {
  clear:both;
}

.text-center {text-align:center;}

a {
  color: tomato;
  text-decoration: none;
}

a:hover {
  color: #2196f3;
}

pre {
display: block;
padding: 9.5px;
margin: 0 0 10px;
font-size: 13px;
line-height: 1.42857143;
color: #333;
word-break: break-all;
word-wrap: break-word;
background-color: #F5F5F5;
border: 1px solid #CCC;
border-radius: 4px;
}

.header {
  padding:20px 0;
  position:relative;
  margin-bottom:10px;
  
}

.header:after {
  content:"";
  display:block;
  height:1px;
  background:#eee;
  position:absolute; 
  left:30%; right:30%;
}

.header h2 {
  font-size:3em;
  font-weight:300;
  margin-bottom:0.2em;
}

.header p {
  font-size:14px;
}



#a-footer {
  margin: 20px 0;
}

.new-react-version {
  padding: 20px 20px;
  border: 1px solid #eee;
  border-radius: 20px;
  box-shadow: 0 2px 12px 0 rgba(0,0,0,0.1);
  
  text-align: center;
  font-size: 14px;
  line-height: 1.7;
}

.new-react-version .react-svg-logo {
  text-align: center;
  max-width: 60px;
  margin: 20px auto;
  margin-top: 0;
}





.success-box {
  margin:50px 0;
  padding:10px 10px;
  border:1px solid #eee;
  background:#f9f9f9;
}

.success-box img {
  margin-right:10px;
  display:inline-block;
  vertical-align:top;
}

.success-box > div {
  vertical-align:top;
  display:inline-block;
  color:#888;
}



/* Rating Star Widgets Style */
.rating-stars ul {
  list-style-type:none;
  padding:0;
  
  -moz-user-select:none;
  -webkit-user-select:none;
}
.rating-stars ul > li.star {
  display:inline-block;
  
}

/* Idle State of the stars */
.rating-stars ul > li.star > i.fa {
  font-size:2.5em; /* Change the size of the stars */
  color:#ccc; /* Color on idle state */
}

/* Hover state of the stars */
.rating-stars ul > li.star.hover > i.fa {
  color:#FFCC36;
}

/* Selected state of the stars */
.rating-stars ul > li.star.selected > i.fa {
  color:#FF912C;
}

			</style>
			<script>
				$(document).ready(function(){
  
  /* 1. Visualizing things on Hover - See next part for action on click */
  $('#stars li').on('mouseover', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
   
    // Now highlight all the stars that's not after the current hovered star
    $(this).parent().children('li.star').each(function(e){
      if (e < onStar) {
        $(this).addClass('hover');
      }
      else {
        $(this).removeClass('hover');
      }
    });
    
  }).on('mouseout', function(){
    $(this).parent().children('li.star').each(function(e){
      $(this).removeClass('hover');
    });
  });
  
  
  /* 2. Action to perform on click */
  $('#stars li').on('click', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently selected
    var stars = $(this).parent().children('li.star');
    
    for (i = 0; i < stars.length; i++) {
      $(stars[i]).removeClass('selected');
    }
    
    for (i = 0; i < onStar; i++) {
      $(stars[i]).addClass('selected');
    }
    
    // JUST RESPONSE (Not needed)
    var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
    var msg = "";
    if (ratingValue > 1) {
        msg = "Thanks! You rated this " + ratingValue + " stars.";
    }
    else {
        msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
    }
    responseMessage(msg);
    
  });
  
  
});


function responseMessage(msg) {
  $('.success-box').fadeIn(200);  
  $('.success-box div.text-message').html("<span>" + msg + "</span>");
}
			</script>

<section class='rating-widget'>
  
  <!-- Rating Stars Box -->
  <div class='rating-stars text-center'>
    <ul id='stars'>
      <li class='star' title='Poor' data-value='1'>
        <i class='fa fa-star fa-fw'></i>
      </li>
      <li class='star' title='Fair' data-value='2'>
        <i class='fa fa-star fa-fw'></i>
      </li>
      <li class='star' title='Good' data-value='3'>
        <i class='fa fa-star fa-fw'></i>
      </li>
      <li class='star' title='Excellent' data-value='4'>
        <i class='fa fa-star fa-fw'></i>
      </li>
      <li class='star' title='WOW!!!' data-value='5'>
        <i class='fa fa-star fa-fw'></i>
      </li>
    </ul>
  </div>
  
  <div class='success-box'>
    <div class='clearfix'></div>
    <img alt='tick image' width='32' src='data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MjYuNjY3IDQyNi42NjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQyNi42NjcgNDI2LjY2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxwYXRoIHN0eWxlPSJmaWxsOiM2QUMyNTk7IiBkPSJNMjEzLjMzMywwQzk1LjUxOCwwLDAsOTUuNTE0LDAsMjEzLjMzM3M5NS41MTgsMjEzLjMzMywyMTMuMzMzLDIxMy4zMzMgIGMxMTcuODI4LDAsMjEzLjMzMy05NS41MTQsMjEzLjMzMy0yMTMuMzMzUzMzMS4xNTcsMCwyMTMuMzMzLDB6IE0xNzQuMTk5LDMyMi45MThsLTkzLjkzNS05My45MzFsMzEuMzA5LTMxLjMwOWw2Mi42MjYsNjIuNjIyICBsMTQwLjg5NC0xNDAuODk4bDMxLjMwOSwzMS4zMDlMMTc0LjE5OSwzMjIuOTE4eiIvPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K'/>
    <div class='text-message'></div>
    <div class='clearfix'></div>
  </div>
  
  
  
</section>



		</div>

		
	</div>
</div>











<?php 


include('php/includes/footer.php');




 ?>