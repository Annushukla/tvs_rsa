$(document).ready(function(){
	jQuery.validator.addMethod("lettersonly", function(value, element) {
	  return this.optional(element) || /^[a-z]+$/i.test(value);
	}, "Please enter valid character"); 

	$("#vehcile_data_formm").validate({
	  rules: {
	    product_type: "required",
	    vehicle_type: "required",
	    engine_no: "required",
	    chassis_no: "required",
	    manufacturer: "required",
	    model: "required",
	    varient: "required",
	    registration_no:{
		   required:{
			   depends: function(element){
				   var status = false;
				   if( $("#vehicle_type").val() == 'OLD'){
					   var status = true;
				   }
				   return status;
			   }
		   }
	   },
	    first_name: {
	    	required: true
	    	
	    },
	    last_name: {
	    	required: true
	    	
	    },
	    email: {
	    	required: true,
	    	email: true,
	    },
	    mobile_no: {
	    	required: true,
	    	number:true,
	    	minlength: 10,
	    	maxlength: 10
	    },
	    cust_addr1: "required",
	    state: "required",
	    city: "required",
	    pin: {
	    	required: true,
	    	number:true,
	    	minlength: 6,
	    	maxlength: 6
	    }
	  },
	  messages: {
			product_type : {
				required: 'Product type field is required.'
			},
			first_name : {
				required: 'First name field is required.'
			},
			last_name : {
				required: 'Last name field is required.'
			},
			email : {
				required: 'Email field is required.'
			},
			mobile_no : {
				required: 'Mobile number field is required.'
			},
			pan_card : {
				required: 'Pan card field is required.'
			},
			cust_addr1 : {
				required: 'Address  field is required.'
			},
			state : {
				required: 'Select state is required.'	
			},
			city : {
				required: 'Select city is required.'	
			},
			pin : {
				required: 'Pin code field is required.'	
			},
			vehicle_type : {
				required: 'Vehicle field is required.'	
			},
			engine_no : {
				required: 'Engine number field is required.'	
			},
			chassis_no : {
				required: 'Chassis field is required.'	
			},
			manufacturer : {
				required: 'Manufacturer field is required.'	
			},
			model : {
				required: 'Model field is required.'	
			},
			varient : {
				required: 'Varient field is required.'	
			},
			registration_no : {
				required: 'Registration field is required.'	
			}

		}

	});


$('#search_button').on('click',function(){

var vehicle_detail = $('#vehicle_detail').val();

	$.ajax({
		url : base_url+'check_exist_engineno',
		data : {vehicle_detail : vehicle_detail},
		dataType : 'JSON',
		type : 'POST',
		success : function(response){
			console.log(response);
			if(response.status=='exist'){
				$('#exist_policy').html('<span style="padding:5px 10px;">This is Vehicle is Covered Under RSA Till '+response.end_date+'</span>');
			}else if(response.status=='new_policy'){
				
				location.reload();
			}
			else if(response.status=='no_response'){
				
				$('#exist_policy').text('Please fill Manually');
			}
		}
	});

});


$('#search_button_rr310').on('click',function(){

var vehicle_detail = $('#vehicle_detail').val();

	$.ajax({
		url : base_url+'check_exist_engineno_rr310',
		data : {vehicle_detail : vehicle_detail},
		dataType : 'JSON',
		type : 'POST',
		success : function(response){
			console.log(response);
			if(response.status=='exist'){
				$('#exist_policy').html('<span style="padding:5px 10px;">This is Vehicle is Covered Under RSA Till '+response.end_date+'</span>');
			}else if(response.status=='new_policy'){
				
				location.reload();
			}
			else if(response.status=='no_response'){
				
				$('#exist_policy').text('Please fill Manually');
			}
		}
	});

});


	

	
//if( $("#vehicle_type").val() == 'OLD'){

$("#vehicle_type").change(function (){

	if($("#vehicle_type").val() == 'NEW'  ){
		$('#registration_no').val('New vehicle');
	}else{
		$('#registration_no').val('');
	}
});

$('#get_cust_veh_details').click(function(){
		var search_value = $('#vehicle_details').val();
		var url = "http://203.112.144.126/mypolicynow.com/Api/";
		//alert(url);
		//http://203.112.144.126/mypolicynow.com/Api/searchForHomePage
		$.ajax({
			url: url+'searchForHomePage',
			data:{ search_value:search_value},
			dataType: 'Json',
			type: 'POST',
			success: function(response){
				
				$('#first_name').val(response.first_name);
				$('#last_name').val(response.last_name);
				$('#aadhar_no').val(response.adharcard);
				$('#pan_card').val(response.pancard);
				$('#dob').val(response.dob);
				$('#gender').val(response.gender);
				$('#marital_status').val(response.married_status);
				$('#email').val(response.email);
				$('#registration_no').val(response.registration_no);
				$('#engine_no').val(response.engine_number);
				$('#chassis_no').val(response.chassis_number);

				//
				//chassis_no

				
				$('#mobile_no').val(response.mobile_no);
				$('#cust_addr1').val(response.address_1);
				$('#cust_addr2').val(response.address_2);
				$('#states_list').val(response.state_id);
				$('#states_list').change();
				$('#city_id').val(response.city_id);

				$('#pin').val(response.pincode);
				$('#occupation').val(response.occupation);
				$('#nominee_name').val(response.nominee_name);

				$('#nominee_relationship').val(response.nominee_relationship);
				

				
		    }
		});
	});


// $('#get_cust_veh_detailsss').click(function(event) {
// 	$('#error_msg').hide();
// 	var vehicle_data = $('#vehicle_details').val();
// 	var url = "<?php echo $this->config->item('api_url');?>";
// 	var url = "http://203.112.144.126/mypolicynow.com/Api/";
// 	//alert(url);
// 	if(vehicle_data != ''){
// 		var product_type = $('#product_type').val();
// 		var vehicle_details_arr = [];

// 	    $.ajax({
			
// 			url: url+'fetch_vehicle_details?vehicle_data='+vehicle_data,
// 			dataType: 'Json',
// 			type: 'GET',
// 			success: function(response){
// 				$('#veh_det_err').remove();
// 				vehicle_details_arr = response;
// 				$('#error_msg').hide();
// 		    },
// 		    error: function(response){
// 		    	$('#veh_det_err').remove();
// 		    	var errors = response.responseJSON;
// 		   		$('#error_msg').html(errors.message);
// 		   		$('#error_msg').show();
// 		    },
// 		    complete: function(response){
// 		    	var validator = $("#vehcile_data_formm").validate();
// 	    		validator.resetForm();

// 		    	var vehicle_details = vehicle_details_arr;
// 		    	//alert(vehicle_details.fname);
// 		    	$('#engine_no').val(vehicle_details.engine_no);
// 		    	$('#chassis_no').val(vehicle_details.chassis_no);
// 		    	//$('#manufacturer').val(vehicle_details.make_id);
// 		    	//$('#model').val(vehicle_details.model_id);
// 		    	$('#registration_no').val(vehicle_details.registration_no);
// 		    	$('#aadhar_no').val(vehicle_details.aadhar_card);
// 		    	$('#pan_card').val(vehicle_details.pan_card);
// 		    	$('#first_name').val(vehicle_details.first_name);
// 		    	$('#last_name').val(vehicle_details.last_name);
// 		    	$('#email').val(vehicle_details.email);
// 		    	$('#mobile_no').val(vehicle_details.mobile_no);
		    	
		    	
// 		    	$('#cust_addr1').val(vehicle_details.cust_addr1);
// 		    	$('#cust_addr2').val(vehicle_details.cust_addr2);
// 		    	//$('#state').val(vehicle_details.cust_state);
// 		    	//$('#city').val(vehicle_details.cust_city);
// 		    	$('#pin').val(vehicle_details.pin);
// 		    	$('#mobile_no').val(vehicle_details.mobile_no);
// 		    	//$('#vehicle_type').val(vehicle_details.vehicle_type);
		    	
		   	
// 		    }
// 		});

// 	}else{
// 		var errors_message = "Please enter value in search box";
// 		$('#error_msg').html(errors_message);
// 		$('#error_msg').show();
// 	}
// });

$(document).on('change','#state', function(){
	var sid = $(this).val();
	if(sid != "" ||  sid != 0){

	 $.ajax({
		url: base_url+'get_cities_list',
		data:{ sid:sid},
		dataType: 'Json',
		type: 'POST',
		success: function(response){
			var cities = response;
			var cities_html = "<option value=''>Select City </option>";

			$.each(cities, function(index,item){
				cities_html += '<option value="'+item.id+'">'+item.name+'</option>';
				
			});
			$('#city').html(cities_html);

			
	    }
	});
	}
});

// $(document).on('change','#state', function(){
// 	var sid = $(this).val();
// 	 $.ajax({
// 		url: base_url+'get_cities_list',
// 		data:{
// 			sid:sid
// 		},
// 		dataType: 'Json',
// 		type: 'POST',
// 		success: function(response){
// 			var cities = response;
// 			var cities_html = "";
// 			var html = '<div class="form-group"><label class="col-sm-5 control-label">City</label><div class="col-sm-7"><select id="cities_list" name="city" class="form-control cust_info_field" autofocus><option value="">Select City</option></select></div></div>';
// 			$('#cities').html(html);	
// 			$.each(cities, function(index,item){
// 				cities_html += '<option value="'+item.id+'">'+item.name+'</option>';
				
// 			});
// 			$('#cities_list').html(cities_html);

			
// 	    }
// 	});
// });

$('#vehicle_details').keydown(function(event){
	if(event.keyCode == 13) {
		event.preventDefault();
		$('#get_cust_veh_details').trigger('click');
    }
});

// $(document).on('click','#vehicle_plan_details', function(event){
// 	// event.preventDefault();
// 	if(!$("#vehcile_data_formm").valid()){
// 		return false;
// 	}
// 	var product_type = $('#product_type').val();
// 	var json_data = {
// 		'product_type': $('#product_type').val(),
// 		'vehicle_type': $('#vehicle_type').val(),
// 		'engine_no': $('#engine_no').val(),
// 		'chassis_no': $('#chassis_no').val(),
// 		'manufacturer': $('#manufacturer').val(),
// 		'model': $('#model').val(),
// 		'registration_no': $('#registration_no').val(),
// 		'aadhar_no': $('#aadhar_no').val(),
// 		'pan_card': $('#pan_card').val(),
// 		'first_name': $('#first_name').val(),
// 		'last_name': $('#last_name').val(),
// 		'email': $('#email').val(),
// 		'mobile_no': $('#mobile_no').val(),
// 		'cust_addr1': $('#cust_addr1').val(),
// 		'cust_addr2': $('#cust_addr2').val(),
// 		'state': $('#state').val(),
// 		'city': $('#city').val(),
// 		'pin': $('#pin').val(),
// 	};

// 	$.each(json_data, function(index,item){
//         $('#'+index+'_hidden').remove();
// 		$('body').append('<input class="veh_form_input_hidden" type="hidden" id="'+index+'_hidden" value="'+item+'">');
// 	});

// 	$.ajax({
// 		url: base_url+'store_cust_veh_details',
// 		data: $('#vehcile_data_formm').serialize(),
// 		dataType: 'Json',
// 		type: 'POST'
// 	});

// 	 $.ajax({
// 		url: base_url+'fetch_plan_details',
// 		data: {
// 			product_type:product_type
// 		},
// 		dataType: 'Json',
// 		type: 'POST',
// 		success: function(response){
// 			$('.plan_details_table').empty();
// 			$.each(response, function(index,item){
// 				var plan_details_html = '<div class="row"><div class="col-md-12" id="fixed-head-side"><h6 class="text-center">'+item.name+'</h6><div id="no-more-tables" style="overflow: scroll;height:600px;"><table class="col-sm-12 table-bordered table-striped table-condensed cf nopadding"><thead class="cf"><tr class="plan_names"><th>Plan Features - '+item.name+'</th>';
				
				
// 				$.each(item.product_plans, function(key,value){
// 					plan_details_html += '<th>'+value.name+value.price+'<div class="radio"><input class="buy_policy" id="plan_id_'+value.id+'" name="plan_id" type="radio" data-plan_id="'+value.id+'" data-toggle="modal" data-target="#chequeModal" data-policy_name="'+value.name+value.price+'" data-policy_price="'+value.price+'"><label for="plan_id_'+value.id+'" class="radio-label"></label></div></th>';
// 				});
				
// 				plan_details_html += '</tr></thead><tbody class="plan_features">';

// 				$.each(item.plan_features, function(index, item){
// 					plan_details_html += '<tr class="plan_values"><td data-title="'+item.name+'">'+item.name+'</td>';
					
// 					$.each(item.values, function(index,item){
// 						 var plan_value = '';
// 						 switch(item.value){
// 						 	case 'Y': plan_value = '<i class="fa fa-check text-green" aria-hidden="true"></i>';
// 						 	break;
// 						 	case 'N': plan_value = '<i class="fa fa-times text-red" aria-hidden="true"></i>';
// 						 	break;
// 						 	default: plan_value = item.value;
// 						 }
// 						 plan_details_html += '<td data-title="'+item.value+'">'+plan_value+'</td>';
// 					});
// 					plan_details_html += '</tr>';
// 				});

// 				plan_details_html += '</tr></tbody></table></div></div>';
// 				$('#plan_details_table').append(plan_details_html);
// 				$('#cust_info_panel,#custom-search-input').hide();
// 				$('#disp_cust_vehicle').show();
// 				$('#plan_details_panel').show();

// 			});
// 	    }
// 	});
// });

// $(document).on('click', '.buy_policy', function(event) {
//   	var plan_id = $(this).data('plan_id');
//   	var policy_name = $(this).data('policy_name');
  	
//   	$('#chequeMsg').remove();
//   	$('#chequeForm').show();
//   	$('#buy_policy_plan_id').remove();
//   	$('#chequeForm').show();
//   	$('#chequeCont').show();
//   	$('#cheque_form')[0].reset();
//   	$('.initiated_payment').removeAttr('data-dismiss').text('Submit').addClass('initiate_payment').removeClass('initiated_payment');
//   	$('body').append('<input type="hidden" id="buy_policy_plan_id" value="'+plan_id+'">');
//   	$('#otp').removeAttr('data-otp_sent').text('Send OTP');
//   	$('#policy_name').html('Buy '+policy_name);

//   	var policy_price = parseInt($('.buy_policy:checked').data('policy_price'));
//   	alert(policy_price);
// 	var gst_tax = policy_price * 18 / 100;
// 	var policy_price_total = Math.round(policy_price + gst_tax,2);
// 	$('#policy_amt').val(policy_price);
// 	$('#tax_perc').val(gst_tax);
// 	$('#payment_amount').val(policy_price_total);

// 	get_banks();
// 	get_cities();
// 	date_picker();
// 	cheque_form_validate();
	
//   });

  $(document).on('click','.payment_type',function(){
  	switch($(this).val()){
  		case 'Customer Cheque': $('#cheque-main').show();
  		break;
  		case 'Dealer Cheque': $('#cheque-main').hide(); 
  		break;
  		default: $('#cheque-main').show();
  	}

  });

 //  $(document).on('click','.initiate_paymenttttt', function(){
 //  	alert('cer');
 //  	if(!$("#cheque_form").valid()){
	// 	return false;
	// }

 //  	var data = { 
 //  		engine_no : $('#engine_no').val(),
 //  		chassis_no : $('#chassis_no').val(),
 //  		manufacturer : $('#manufacturer').val(),
 //  		model : $('#model').val(),
 //  		varient : $('#varient').val(),
 //  		registration_no : $('#registration_no').val(),
 //  		first_name : $('#first_name').val(),
 //  		last_name : $('#last_name').val(),
 //  		email : $('#email').val(),
 //  		mobile_no : $('#mobile_no').val(),
 //  		product_type : $('#product_type').val(),
 //  		cust_addr1 : $('#cust_addr1').val(),
 //  		cust_addr2 : $('#cust_addr2').val(),
  		
 //  		policy_plan_id : $('#buy_policy_plan_id').val(),
 //  		payment_type: $('input[name="payment_type"]:checked').val(),
 //  		bank_name: $('#bank_list').val(),
 //  		branch_city: $('#cheque_cities_list').val(),
 //  		cheque_no: $('#cheque_no').val(),
 //  		cheque_date: $('#cheque_date').val(),
 //  		policy_amt: $('#policy_amt').val(),
 //  		payment_amount: $('#payment_amount').val(),
 //  		terms: $('#terms').val(),
 //  		aadhar_no: $('#aadhar_no').val(),
 //  		pan_card: $('#pan_card').val(),
 //  		vehicle_type: $('#vehicle_type').val(),
 //  		tax_perc_amount: $('#tax_perc').val()
 //  	}


 //  	$.ajax({
	// 	url: base_url+'add_customer_and_products',
	// 	data: data,
	// 	dataType: 'json',
	// 	type: 'POST',
	// 	success: function(response){
	// 		var sold_policy_data = response;
	// 		console.log(sold_policy_data.sold_policy_id);
	// 		$('#chequeForm').hide();
	// 		$('#chequeCont').hide();
	// 		var html = '<div class="panel panel-success" id="chequeMsg"><div class="panel-heading">Your Details Have Been Stored Successfully! <br> Your Policy No. is : '+sold_policy_data.sold_policy_no+'</div></div>';
	// 		$('#chequeModalBody').append(html)
	// 		$('.initiate_payment').attr('data-dismiss','modal').text('Close').addClass('initiated_payment').removeClass('initiate_payment');
	// 		window.location.href = base_url+'policy_pdf/'+sold_policy_data.sold_policy_id;
	// 	}
	// });
 //  });

$('#otp').click(function(){
	if(!$(this).attr('data-otp_sent')){
		$.ajax({
			url: base_url+'send_otp_agent',
			type: 'POST',
			beforeSend: function(){
				$('#otp').text('Sending OTP');
			},
			success: function(response){
				if (response == true) {
					$('#otp').text('Validate OTP').attr('data-otp_sent','1');
					$('#otpText').empty();
					var otpHtml = '<label for="example-search-input" class="col-md-2 col-form-label">OTP</label><div class="col-md-8"><input class="form-control" name="otp" type="search" value="" id="otpTextBox" placeholder="Enter OTP"></div>';
					$('#otpText').append(otpHtml);
				}
				else{
					alert('There Was an error while sending email');
				}
			}
		});
	}
	else{
		$.ajax({
			url: base_url+'verify_otp_agent',
			data: {
				otp: $('#otpTextBox').val()
			},
			type: 'POST',
			beforeSend: function(){
				alert('Verifying OTP');
			},
			success: function(response){
				if (response == true) {
					var policy_price = parseInt($('.buy_policy:checked').data('policy_price'));
					var gst_tax = policy_price * 18 / 100;
					var policy_price_total = Math.round(policy_price + gst_tax,2);
					$('#chequeCont').empty();
					$('#chequeFooter').remove();
					var chequeConthtml = '<div class="radio"> <div class="radio"> <input id="radio-1" name="payment_type" type="radio" value="1" checked="" class="payment_type"> <label for="radio-1" class="radio-label">Customer Cheque</label> <input id="radio-2" name="payment_type" type="radio" value="2" class="payment_type"> <label for="radio-2" class="radio-label">Dealer Cheque</label> </div> </div> <div id="cheque-main"> <form id="cheque_form"> <div class="row"> <div class="col-md-6"> <div class="form-group"> <label for="bank_list">Select Bank</label> <select id="bank_list" name="bank_name" class="form-control"></select> </div> </div> <div class="col-md-6"> <div class="form-group"> <label for="branch_city">Select Bank Branch City</label> <select id="cheque_cities_list" type="text" name="branch_city" class="form-control"></select> </div> </div> </div> <div class="row"> <div class="col-md-6"> <div class="form-group"> <label for="cheque_no">Enter Cheque No.</label> <input id="cheque_no" type="text" name="cheque_no" class="form-control" placeholder="Enter Cheque No."> </div> </div> <div class="col-md-6"> <div class="form-group"> <label for="cheque_date">Enter Cheque Date</label> <input type="text" name="cheque_date" value="" id="cheque_date" placeholder="E.g: MM/DD/YYYY" class="form-control datepicker" /> </div> </div> </div> <div class="row" id="cheque_amt_cont"> <div class="col-md-6"> <div class="form-group"> <label for="policy_amt">Policy Amount.</label> <input id="policy_amt" type="text" name="policy_amt" readonly="" class="form-control" value="'+policy_price+'"></div></div> <div class="col-md-6"> <div class="form-group"> <label for="payment_amount">Amount to be paid With 18% Gst.</label> <input id="payment_amount" type="text" name="payment_amount" readonly="" class="form-control" value="'+policy_price_total+'"></div></div> </div> <div class="checkbox"> <label><input type="checkbox" name="terms" value="1" id="terms"> Buy Terms And Conditions</label> </div> </form> </div>';
					$('#chequeCont').append(chequeConthtml);

					var cheque_footer_html = '<div class="modal-footer" id="chequeFooter"><button type="button" class="btn btn-primary initiate_payment">Submit</button></div>';
					$('#cheque_modal_content').append(cheque_footer_html);
					get_banks();
					get_cities();
					date_picker();
					cheque_form_validate();
				}
				else{
					alert('Oops! OTP Mismatch.');
				}
			}
		});
	}
}); 

$('#vehicle_form_reset').click(function(){
	$('#vehcile_data_formm')[0].reset();
	$('#veh_det_err, #veh_form_input_hidden').remove();
	$('.cust_info_field').each(function(){
		$(this).removeAttr('style');
	});

	var validator = $("#vehcile_data_formm").validate();
    validator.resetForm();
    
});

$('#model_id').on('change', function() {
  var optionSelected = $("option:selected", this);
  var valueSelected = optionSelected.val();
  var textSelected   = optionSelected.text();
  
  $('#hidden_model_name_div').html("<input type='hidden' value='"+textSelected+"' name='model' > <input type='hidden' value=' "+valueSelected+" '  name='model_id' >");
  // console.log(textSelected);
});

$('#disp_cust_vehicle').click(function(){
	$('#custom-search-input').show();
    $('.veh_form_input_hidden').each(function(index, item){
        var input_field = $(this).attr('id').replace("_hidden","");
        $('#'+input_field).val($(this).val());
        $('#plan_details_table').empty()
        $('#plan_details_panel').hide();
        $('#cust_info_panel').show();
    });
//	$('#get_cust_veh_details').trigger('click');
});

// $('#product_type').change(function(){
// 	var json_data = {
// 		prod_type : $(this).val()
// 	};
    
// 	$.ajax({
// 			url: base_url+'fetch_manufacturers',
// 			data: json_data,
// 			dataType: 'Json',
// 			type: 'POST',
// 			success: function(response){
// 				$('#manufacturer').html(response.html);
// 				$('#manufacturer').change();
// 			}
// 		});
// });

// $(document).on('change', '#manufacturer', function(){
//  var json_data = {
//  	make_id : $(this).val()
//  };

//  $.ajax({
// 			url: base_url+'fetch_model',
// 			data: json_data,
// 			dataType: 'Json',
// 			type: 'POST',
// 			success: function(response){
// 				$('#model').html(response.html);				
// 			}
// 		});
// });

$(document).ready(function(){
   if($('#state').val() != undefined){
       $('#state').trigger('change');
   }
   
   if($('#product_type').val() != ''){
       $('#product_type').trigger('change');
   } 

   if ($('#plan_details_panel').attr('style') == 'display:block') {
   	$('#vehicle_plan_details').trigger('click');
   }

var manufacturer = $('#manufacturer').val();
$.ajax({
			url: base_url+'fetch_model',
			data: {make_id:manufacturer},
			dataType: 'Json',
			type: 'POST',
			success: function(response){
				$('#model_id').html(response.html);
				if(response.hidden!=""){
				$('#hidden_model_name_div').html(response.hidden);
				}				
			}
		});


});






});







function afterRefresh(){
	var product_type = "";
	$.ajax({
		url: base_url+'fetch_plan_details',
		data: {
			product_type:product_type
		},
		dataType: 'Json',
		type: 'POST',
		success: function(response){
			$('.plan_details_table').empty();
			$.each(response, function(index,item){
				var plan_details_html = '<div class="row"><div class="col-md-12" id="fixed-head-side"><h6 class="text-center">'+item.name+'</h6><div id="no-more-tables" style="overflow: scroll;height:600px;"><table class="col-sm-12 table-bordered table-striped table-condensed cf nopadding"><thead class="cf"><tr class="plan_names"><th>Plan Features - '+item.name+'</th>';
				
				
				$.each(item.product_plans, function(key,value){
					plan_details_html += '<th>'+value.name+value.price+'<div class="radio"><input class="buy_policy" id="plan_id_'+value.id+'" name="plan_id" type="radio" data-plan_id="'+value.id+'" data-toggle="modal" data-target="#chequeModal" data-policy_name="'+value.name+value.price+'" data-policy_price="'+value.price+'"><label for="plan_id_'+value.id+'" class="radio-label"></label></div></th>';
				});
				
				plan_details_html += '</tr></thead><tbody class="plan_features">';

				$.each(item.plan_features, function(index, item){
					plan_details_html += '<tr class="plan_values"><td data-title="'+item.name+'">'+item.name+'</td>';
					
					$.each(item.values, function(index,item){
						 var plan_value = '';
						 switch(item.value){
						 	case 'Y': plan_value = '<i class="fa fa-check text-green" aria-hidden="true"></i>';
						 	break;
						 	case 'N': plan_value = '<i class="fa fa-times text-red" aria-hidden="true"></i>';
						 	break;
						 	default: plan_value = item.value;
						 }
						 plan_details_html += '<td data-title="'+item.value+'">'+plan_value+'</td>';
					});
					plan_details_html += '</tr>';
				});

				plan_details_html += '</tr></tbody></table></div></div>';
				$('#plan_details_table').append(plan_details_html);
				$('#cust_info_panel,#custom-search-input').hide();
				$('#disp_cust_vehicle').show();
				$('#plan_details_panel').show();

			});
	    }
	});
}

function cheque_form_validate(){
	$('#cheque_form').validate({
		rules: {
			bank_name : "required",
			cheque_no : {
				required: true,
				number: true
			},
			branch_city: "required",
			cheque_date: "required",
			terms: "required",
		},
		message: {
			cheque_no : {
				number: 'Oops! Only numbers are accepted'
			}
		}
	});
}