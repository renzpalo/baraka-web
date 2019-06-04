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

<!-- <script src="js/inputmask/inputmask.js"></script> -->




<div class="container-fluid">

	<div class="row mt-5">
		<div class="col-sm-4 offset-4">
			<form action="">
				<div class="form-group">
					<label for="">Email</label>
					<input id="email" type="email" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="">Fullname</label>
					<input id="fullname" type="text" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="">Password</label>
					<input id="password" type="text" class="form-control" minlength="6" required>
				</div>
				<div class="form-group">
					<label for="">Currency</label>
					<input id="currency" type="text" class="form-control" minlength="6" required>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block">Sign In</button>
				</div>

			</form>
		</div>
	</div>

<!-- 	<div class="row">
		<form action="">
  
		  <div>
		    <label for="cc">Credit Card</label>
		    <input id="cc" type="text" data-inputmask="'mask': '9999 9999 9999 9999'" />
		  </div>
		  
		  <div>
		    <label for="date">Date</label>
		    <input id="date" data-inputmask="'alias': 'date'" />
		  </div>
		  
		  <div>
		    <label for="phone">Phone</label>
		    <input id="phone" type="text" />
		  </div>

		</form>

		<td>
		  <input value="10" id="currIN">
		</td>
	</div> -->

<!-- 	<script src="js/jquery.barrating.js"></script>
	<script>
		// $(function() {
  //     $('#example').barrating({
  //       theme: 'fontawesome-stars'
  //     });
  //  });

		// $("#currIN").inputmask("9999 9999 9999");
	</script>

	<div class="row">


		<div class="col-sm-12">
			<select id="example">
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>
</select>
		</div>

		
	</div>
</div> -->











<?php 


include('php/includes/footer.php');




 ?>

<script>
$(document).ready(function(){

	// $("#email").inputmask({
	//     mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
	//     placeholder: "",
	//     greedy: false,
	//     onBeforePaste: function (pastedValue, opts) {
	//       pastedValue = pastedValue.toLowerCase();
	//       return pastedValue.replace("mailto:", "");
	//     },
	//     definitions: {
	//       '*': {
	//         validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
	//         casing: "lower"
	//       }
	//     }
	// });


	// Inputmask("(.999){+|1},00", {
 //        positionCaretOnClick: "radixFocus",
 //        radixPoint: ",",
 //        _radixDance: true,
 //        numericInput: true,
 //        placeholder: "0",
 //        definitions: {
 //            "0": {
 //                validator: "[0-9\uFF11-\uFF19]"
 //            }
 //        }
 //    }).mask("#email");


	$("#fullname").inputmask({ 
		mask: " *{1,50}",
		placeholder: "",
		definitions: {
			'*': {
				validator: "[A-Za-z ]"
			},
			' ': {
				validator: "[A-Za-z]"
			}
		}
	});

	$("#password").inputmask({ 
		mask: "*{1,30}",
		placeholder: "",
		definitions: {
			'*': {
				validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]"
			}
		}
	});

    $("#currency").inputmask({ 
		mask: "99",
		placeholder: "",
		min: 1,
		max: 25,
		definitions: {
			'9': {
				validator: "[0-9]"
			}
		},
		// "numeric", {
		// 	min: 0,
		// 	max: 100
		// }
	});

	// $("#currency").inputmask("numeric", {
	// 	min: 0,
	// 	max: 100
	// });
	
});
</script>