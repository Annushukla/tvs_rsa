

$("#user_data_form").validate({
  rules: {
    current_password: "required",
    password: "required",
    confirm_password: {
        required: true,
        equalTo: "#password"
    },
  }
});


$("#form_forgot_password").validate({
	errorElement: 'span',
	errorClass: 'err',
  	rules: {
    	txt_user_mbl_no:{
    		required: true
		}
   	},
  	messages: {
		name : {
			required: 'Mobile number required.'
		
		}
	}
});





