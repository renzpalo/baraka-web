





$(document).ready(function() {

	// Product Price
	$("#prod_price").inputmask({ 
		mask: "09{1,6}",
		placeholder: "",
		definitions: {
			'9': {
				validator: "[0-9]"
			},
			'0': {
				validator: "[1-9]"
			}
		}
	});

	// Product Price
	$("#prod_stock").inputmask({ 
		mask: "9{1,6}",
		placeholder: "",
		definitions: {
			'9': {
				validator: "[0-9]"
			},
			'0': {
				validator: "[1-9]"
			}
		}
	});

	// Product Price
	$("#prod_min_quantity, #prod_max_quantity,  #prod_ship_fee").inputmask({ 
		mask: "09{1,3}",
		placeholder: "",
		definitions: {
			'9': {
				validator: "[0-9]"
			},
			'0': {
				validator: "[1-9]"
			}
		}
	});

	// Product Name
	$("#prod_name").inputmask({ 
		mask: " *{1,50}",
		placeholder: "",
		definitions: {
			'*': {
				validator: "[ 0-9A-Za-z!@#$%&'*+/=?^_`{|}~\-]"
			},
			' ': {
				validator: "[0-9A-Za-z]"
			}
		}
	});

	// Store Name
	$(".input-store-name").inputmask({ 
		mask: " *{1,50}",
		placeholder: "",
		definitions: {
			'*': {
				validator: "[ 0-9A-Za-z!@#$%&'*+/=?^_`{|}~\-]"
			},
			' ': {
				validator: "[0-9A-Za-z]"
			}
		}
	});

	// Email
	$(".input-email").inputmask({ 
		mask: " *{1,50}",
		placeholder: "",
		definitions: {
			'*': {
				validator: "[ 0-9A-Za-z!@#$%&'*+/=?^_`{|}~\-]"
			},
			' ': {
				validator: "[0-9A-Za-z]"
			}
		}
	});

	// Mobile Number
	$(".input-mobile").inputmask({ 
		mask: "999999999",
		placeholder: ""
	});

	// Password
	$(".input-password").inputmask({ 
		mask: "*{1,50}",
		placeholder: ""
	});

	// Fullname
	$(".input-fullname").inputmask({ 
		mask: " *{1,50}",
		placeholder: "",
		definitions: {
			'*': {
				validator: "[ A-Za-z.,]"
			},
			' ': {
				validator: "[A-Za-z]"
			}
		}
	});

	// Fullname
	$(".add_cart_qty").inputmask({ 
		mask: "1",
		placeholder: "",
		definitions: {
			'1': {
				validator: "[1]"
			},
			'2': {
				validator: "[2]"
			}
		}
	});
	
});