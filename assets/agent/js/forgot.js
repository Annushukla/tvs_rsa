
function resetForm(){
	$('#txt_user_mbl_no').val('');
	var validator = $("#form_forgot_password").validate();
    validator.resetForm();
}


$('.model_forgot_password').click(function(){
    	resetForm();
    	$('#model_forgot_password_content').show();
		$('#model_otp_verify_content').hide();
		$('#model_change_password_content').hide();

    	$('#model_forgot_password').modal('show');

    });

$(document).ready(function(){

	jQuery.validator.addMethod("checkUserMobile", function(value, element) {
		var response =  '';
		var mobile_no = $('#txt_user_mbl_no').val();
		
		$.ajax({
	        type: "POST",
	        async:false,
	        url: base_url+'user/User_controller/checkUserMobile',
	        dataType : "html",
	        data : { 'mobile_no' : mobile_no},
	        
	        success:function(data){
	            response = data;
	        }
	    });
	    if(response == 'true'){
	        return true;
	    }else{
	        return false;
	    }

	}, "Please Enter Registered Mobile Number");



	$("#form_forgot_password").validate({
		errorElement: 'span',
		errorClass: 'err',
	  	rules: {
	    	txt_user_mbl_no:{
	    		required: true,
	    		checkUserMobile:true
			}
	   	},
	  	messages: {
			name : {
				required: 'Mobile number required.'
			
			}
		}
	});


	$('#btn_forgot_password').on('click', function() {
		if(!$( "#form_forgot_password" ).valid()){ return false; }else{
			$('#span_forgot_password').html('Sending SMS');
			$.ajax({
				url: base_url + 'user/user_controller/sendSms',
				data : $('#form_forgot_password').serialize(),
				dataType: 'Json',
				type: 'POST',
				success: function(response){
					$('#model_forgot_password_content').hide();
					//alert(response.user_id);
					$('#txt_user_id').val(response.user_id);
					$('#model_otp_verify_content').show();
	
				}
			})
		}
	});


	$('#btn_mobile_verify').on('click', function() {
		if(!$( "#form_mobile_verify" ).valid()){ return false; }else{
			$('#span_mobile_verify').html('Verify OTP please wait');
			$.ajax({
				url: base_url + 'user/user_controller/verifyMobileNo',
				data : $('#form_mobile_verify').serialize(),
				dataType: 'Json',
				type: 'POST',
				success: function(response){
					if(response.success == 1){
						$('#model_forgot_password_content').hide();
						$('#model_otp_verify_content').hide();

						$('#txt_user_id_password').val(response.user_id);
						$('#model_change_password_content').show();
					}
					
				}
			})
		}
	});


	$('#btn_change_password').on('click', function() {
		if(!$( "#form_change_password" ).valid()){ return false; }else{
			$('#span_change_password').html('Please wait');
			$.ajax({
				url: base_url + 'user/user_controller/changePassword',
				data : $('#form_change_password').serialize(),
				dataType: 'Json',
				type: 'POST',
				success: function(response){
					if(response.success == 1){
						$('#model_forgot_password').modal('hide');
						//alert('Change password successfully');
						swal("Success", "Change password successfully", "success");

						
					}
					
				}
			})
		}
	});


	

});
 	
