
$(document).ready(function(){
	$("#form_add_policy_form").validate({
		errorElement: 'span',
    	errorClass: 'err',
	  	rules: {
	    	
			category_id:{
	    		required: true
	    	},
			title:{
	    		required: true
	    	},
	    	employee_code:{
	    		maxlength: 10,
	    		minlength: 5
	    	},
	    	first_name:{
	    			required: true,
	    			lettersonly: true
	    	},
	    	pan:{
	    		pancardonly: true,
	    		maxlength: 10,
	    		 minlength: 10,
	    		
	    	},
	    		
	    	last_name:{
	    		required: true,
	    		lettersonly: true
	    	},	
	    	father_full_name:{
	    		required: true,
	    		lettersonly: true
	
	    	},
	    	mobile_number:{
	    		required: true,
	    		 number: true,
	    		 maxlength: 10,
	    		 minlength: 10


	    	},
	    	adhar_card:{
	    		 aadharcard: true,
	    		 maxlength: 12,
	    		 minlength: 12,
	    		 required: true,
	    		

	    	},
	    	email:{
	    		required: true,
	    		email: true

	    	},
	    	gender:{
	    		required: true
	    	},
	    	marital_status:{
	    		required: true
	    	},
	    	
	    	occupation:{
	    		required: true
	    	},
	    	correspondence_address_1:{
	    		required: true
	    	},
	    	correspondence_address_2:{
	    		required: true
	    	},
	    	correspondence_address_3:{
	    		required: true
	    	},
	    	correspondence_state_id:{
	    		required: true
	    	},
	    	correspondence_pincode:{
	    		required: true
	    	},
	    	correspondence_city_id:{
	    		required: true
	    	},
	    	education:{
	    		required: true
	    	},
	    	annual_income:{
	    		required: true
	    	},
	    	introducer_emp_code:{
	    		required: true
	    	},
	    	introducer_emp_name:{
	    		required: true
	    	}




/*3*/




	   	},
	  	messages: {
			category_id : {
				required: 'Category is required.'
			},
			title : {
				required: 'Title is required.'
			},
			first_name : {
				required: 'First Name is required.'
			},
			last_name : {
				required: 'Last Name is required.'
			},
			father_full_name : {
				required: 'Father Name is required.'
			},
			mobile_number : {
				required: 'Mobile  Name is required.'
			},
			email : {
				required: 'Email  is required.'
			},
			gender : {
				required: 'Gender  is required.'
			},
			marital_status : {
				required: 'Marital Status  is required.'
			
			},
			pan : {
				required: 'Pan Card not valid.'
			
			},
				occupation :  {
				required: 'Occupation  is required.'
			},
			correspondence_address_1 : {
				required: 'Correspondence Address 1  is required.'
			},
			correspondence_address_2 : {
				required: 'Correspondence Address 2  is required.'
			},
				correspondence_address_3 : {
				required: 'Correspondence Address 3  is required.'
			},

			correspondence_state_id : {
				required: 'State  is required.'
			},
			
			correspondence_pincode : {
				required: 'Pincode  is required.'
			},
			correspondence_city_id : {
				required: 'City  is required.'
			},
			education : {
				required:'Education Qualifaication is required.'
			},
			annual_income : {
				required:'Annual Income is required.'
			},
			introducer_emp_code : {
				required:'Introducer Emp Code is required.'
			},
			introducer_emp_name : {
				required:'Introducer Emp Name is required.'
			},









		}
	});

$.validator.addMethod( "lettersonly", function( value, element ) {
    return this.optional( element ) || /^[a-zA-Z ]*$/i.test( value );
}, "Only allow alphabet  " );

$.validator.addMethod( "pancardonly", function( value, element ) {
    return this.optional( element ) || /[A-Za-z]{5}\d{4}[A-Za-z]{1}/i.test( value );
}, "Pan card number Valid format AAAPL1234C " );

$.validator.addMethod( "aadharcard", function( value, element ) {
    return this.optional( element ) || /^[0-9]{12,}$/i.test( value );
}, "Please Enter valid aadharcard " );





$(".dob_proof").change(function(){


        if($(this).val() != '')
            {
            	$(".upload_file_name").show();
            }
            else{

	$(".upload_file_name").hide();
            }
        });


});



/*Drop down box*/

$('.education').selectize({
    sortField: 'text'
});
/*pincode*/
$("#correspondence_pincode").change(function() 
        {
            var pincode = $("#correspondence_pincode").val();
            $('select[name="correspondence_state_id"]').html('');
            $('select[name="correspondence_city_id"]').html('');
            $.ajax({
                type : "POST",
                dataType : "json",
                url  : base_url+ "Home_Controller/Getpincode",
                data : "pincode=" + pincode,
                success: function(data) 
                {

                	console.log(data);
                     $.each(data, function(key, value) {
                            $('select[name="correspondence_city_id"]').append('<option value="'+ value.area_name +'">'+ value.area_name +'</option>');
                        });
                   $('select[name="correspondence_state_id"]').append('<option value="'+ data[0].state_name +'">'+ data[0].state_name +'</option>');
                    //$("#base_amount").html(data);
                }
            });
        });

$("#permanent_pincode").change(function() 
        {
            var pincode = $("#correspondence_pincode").val();
            $('select[name="permanent_state_id"]').html('');
            $('select[name="permanent_city_id"]').html('');
            $.ajax({
                type : "POST",
                dataType : "json",
                url  : base_url+ "Home_Controller/Getpincode",
                data : "pincode=" + pincode,
                success: function(data) 
                {

                	console.log(data);
                     $.each(data, function(key, value) {
                            $('select[name="permanent_city_id"]').append('<option value="'+ value.area_name +'">'+ value.area_name +'</option>');
                        });
                   $('select[name="permanent_state_id"]').append('<option value="'+ data[0].state_name +'">'+ data[0].state_name +'</option>');
                    //$("#base_amount").html(data);
                }
            });
        });

/*select check box for addreee*/

/*Address*/
$("#permanent_pincode").change(function() 
        {
            var pincode = $("#permanent_pincode").val();
            $('select[name="permanent_state_id"]').html('');
            $('select[name="permanent_city_id"]').html('');
            $.ajax({
                type : "POST",
                dataType : "json",
                url  : base_url+ "Home_Controller/Getpincode",
                data : "pincode=" + pincode,
                success: function(data) 
                {

                	console.log(data);
                     $.each(data, function(key, value) {
                            $('select[name="permanent_city_id"]').append('<option value="'+ value.area_name +'">'+ value.area_name +'</option>');
                        });
                   $('select[name="permanent_state_id"]').append('<option value="'+ data[0].state_name +'">'+ data[0].state_name +'</option>');
                    //$("#base_amount").html(data);
                }
            });
        });

  var maxdatedob = current_year-0+'-'+current_month+'-'+current_day;
  var startdatedob = current_year-55+'-'+current_month+'-'+current_day;
  console.log(maxdatedob);
     $('.datepicker_dob').datepicker({

    format: 'yyyy-mm-dd', 
    autoclose: true,
      autoclose: 1, 
        minView: 2,
        maxView: 4,
        pickTime: 0,
        startDate:startdatedob,
        endDate :maxdatedob

});

      $('#date_of_birth').on('change', function() {
    alert("Premium is calculated on basis of the insurer’s age, make sure you have entered correct DOB as per proof");
  });

    function showDiv(id){
        switch(id) {
            case '1':
                $('#div_employee').hide();
                $('#div_corporate').hide();
                break;
            case '2':
                $('#div_employee').show();
                $('#div_corporate').hide();
                break;

            case '3':
                $('#div_employee').hide();
                $('#div_corporate').show();
                break;
            
        }

    }
 $('#address_same').click(function(){
 	
    if($(this).prop('checked')){
        var co_address1=$('#correspondence_address_1').val();
        var co_address2=$('#correspondence_address_2').val();
        var co_address3=$('#correspondence_address_3').val();
        var co_pincode=$('#correspondence_pincode').val();
        $('.permanent_tag').prop('required',false);

         $('.is_same').hide();
         $('#permanent_address_1').val(co_address1);
         $('#permanent_address_2').val(co_address2);
         $('#textpermanent_address_3box1').val(co_address3);
         $('#permanent_pincode').val(co_pincode);

    }else{

        $('#permanent_address_1').val('');
          $('.is_same').show();
         $('#permanent_address_2').val('');
         $('#textpermanent_address_3box1').val('');
         $('#permanent_pincode').val('');
		 $('.permanent_tag').prop('required',true);
    }
});

function resetForm(){
	$('#policy_no').val('');
	$('#insured_name').val('');
	$('#insurance_period').val('');
	$('#policy_mobile_no').val('');
	$('#policy_email').val('');
	$('#nominee_name').val('');
	$('#hypothecation_lease').val('');
	$('#insured_address').val('');
	$('#registration_no').val('');
	$('#product_type').val('');
	$('#make').val('');
	$('#model').val('');
	$('#engine_no').val('');
	$('#chassis_no').val('');
	$('#odometer_reading').val('');
	$('#fuel_type').val('');
	$('#anti_theft_device_status').val('');
	$('#mfg_date').val('');
	$('#cc_hp').val('');
	$('#seating_capacity').val('');
	$('#color').val('');
	$('#type_of_body').val('');
	$('#lpg_cng').val('');
	$('#addon_ans_1').val('');
	$('#addon_ans_2').val('');
	$('#addon_ans_3').val('');
	$('#addon_ans_4').val('');
	$('#addon_ans_5').val('');
	$('#addon_ans_6').val('');
	$('#addon_ans_7').val('');

	var validator = $("#form_add_policy").validate();
    validator.resetForm();

	
}
