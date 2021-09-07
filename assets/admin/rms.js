
$(document).ready(function(){
	jQuery.validator.addMethod("lettersonly", function(value, element) {
	  return this.optional(element) || /^[a-z]+$/i.test(value);
	}, "Please enter valid character"); 

	jQuery.validator.addMethod("uniqueEmail", function(value, element) {
		var response =  '';
		var email = $('#email').val();
		var id = $('#id').val();

		$.ajax({
	        type: "POST",
	        async:false,
	        url: base_url+'admin/AdminController/checkDuplicateRM',
	        dataType : "json",
	        data : { 'email' : email,'id' : id},
	        
	        success:function(data){
	            response = data.success;
	        }
	    });
	    if(response == '1'){
	        return true  ;
	    }else{
	        return false;
	    }
	}, " EMAILID IS ALREADY EXIST");


	jQuery.validator.addMethod("uniqueMobile", function(value, element) {
		var response =  '';
		var mobile_no = $('#mobile_no').val();
		var id = $('#id').val();
		$.ajax({
	        type: "POST",
	        async:false,
	        url: base_url+'admin/AdminController/checkDuplicateRmMobile',
	        dataType : "json",
	        data : { 'mobile_no' : mobile_no,'id' : id},
	        
	        success:function(data){
	             response = data.success;
	        }
	    });
	    if(response == '1'){
	        return true  ;
	    }else{
	        return false;
	    }
	    

	}, " MOBILE NUMBER IS ALREADY EXIST");

	

	$("#form_update").validate({
		ignore: [],
		errorElement: 'span',
    	errorClass: 'err',
	  	rules: {
	    	email:{
	    		required: true,
				email: true,
				uniqueEmail: true

			},
			name:{
	    		required: true
	    	},
			mobile_no:{
	    		required: true,
	    		number:true,
	    		minlength: 10,
    			maxlength: 10,
    			uniqueMobile: true
	    	},
			password: {
			    required: function(element){
			        if($("#id").val()== ""){
			        	return true;
			        }else{
			        	return false;
			        }
			    }
			},
			state:{
	    		required: true
			}
	   	},
	  	messages: {
			email : {
				required: 'Email field is required.'
			},
			name : {
				required: 'First name field is required.'
			},
			mobile_no : {
				required: 'Mobile number field is required.'
			},
			password : {
				required: 'Password field is required.'	
			},
			state : {
				required: 'state field is required.'	
			}
		}
	});


	
	//datatables
    table = $('.data_tables').DataTable({ 
 
        "processing": true, //Feature control the processing indicator.
       	"serverSide": true, //Feature control DataTables' servermside processing mode.
        //"order": [], //Initial no order.
 		"iDisplayLength" : 10,
 
        // Load data for the table's content from an Ajax source
        "ajax": {
        	'url' : base_url+'admin/adminController/ajaxRms',
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
      				return jsonData.data;
 			}
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
 
    });
 

    $('.open_model').click(function(){
    	resetForm();
    	$('#pop_heading').html('Add RM');
        $('#open_model').modal('show');

    });




    $('#state').change(function(){
 	    var state = $(this).val();
 	    $.ajax({
			url: base_url+'admin/AdminController/getCitiesByStateId',
			data : { state : state},
			dataType: 'Json',
			type: 'POST',
			success: function(response){
				if(response.success == 1){
					$('#city').html(response.html);
				}
	
			}
		});
 	    

    });

 
$('#submit').on('click', function() {

    var form = document.forms.namedItem("form_update"); // high importance!, here you need change "yourformname" with the name of your form
    var form_data = new FormData(form); // high importance!
   
 	if(!$( "#form_update" ).valid()){ return false; }else{
 		//swal("Click on either the button or outside the modal.");
 		$('#open_model').modal('hide');
 		
 		swal({
			title: "",
			text: "",
			icon: "assets/admin/giphy.gif",
			button: false,
		 });


			$.ajax({
				url: base_url+'admin-rms-update',
				data : form_data,
				dataType: 'Json',
				type: 'POST',
				contentType: false,
				cache: false,
	    	    processData:false,
				success: function(response){
					if(response.success == 1){
						table.ajax.reload(null, false);
						swal({
						  icon: "success",
						  title: "Success",
						  text: response.suc_msg,
						  timer: 2000,
						});
						
					}else{
						swal({
						  icon: "error",
						  title: "Error",
						  text: response.err_msg,
						  timer: 2000,
						});

						
					}
				}
			});
	}
});




});


function editModel(id){
	$.ajax({
		url: base_url+'admin-rms-edit',
		data : { 'id' : id},
		dataType: 'Json',
		type: 'POST',
		success: function(response){
			//alert(response.result[0].state);
			resetForm();
			$('#pop_heading').html('Update RM');
			$('#city').html(response.html);


			$('#name').val(response.result[0].name);
			$('#email').val(response.result[0].email);
			$('#mobile_no').val(response.result[0].mobile_no);
			$('#address').val(response.result[0].address);
			// $('#pan_card_no').val(response.result[0].pan_card_no);
			// $('#aadhar_card_no').val(response.result[0].aadhar_card_no);
			// $('#comp_name').val(response.result[0].comp_name);
			// $('#gst').val(response.result[0].gst);
			// $('#tan').val(response.result[0].tan);
			// $('#cin').val(response.result[0].cin);
			 $('#is_active').val(response.result[0].is_active);

			$('#state').val(response.result[0].state);
			$('#city').val(response.result[0].city);
		

			$('#show_image_div').hide();

			if(response.result[0].logo != ''){
				$('#show_image_div').show();
				$('#show_image_div').attr('src', base_url+response.result[0].logo); 
			}
		

			$('#id').val(response.result[0].id);
			$('#open_model').modal('show');
			
		}
	})	
}


function deleteRecord(id){

	if(confirm('Are you sure delete this data?')){
	
		$.ajax({
			url: base_url+'admin-rms-delete',
			data : { 'id' : id},
			dataType: 'Json',
			type: 'POST',
			success: function(response){
				if(response.success == 1){
					table.ajax.reload(null, false);
					swal({
					  icon: "success",
					  title: "Success",
					  text: response.suc_msg,
					  timer: 2000,
					});
					
				}else{
					swal({
					  icon: "error",
					  title: "Error",
					  text: response.err_msg,
					  timer: 2000,
					});

					
				}
			
			}
		})
	}	
}

function resetForm(){
	$('#name').val('');
	$('#email').val('');
	$('#mobile_no').val('');
	$('#address').val('');
	$('#is_active').val('');
	$('#id').val('');

	$('#is_active').val(1);
	$('#show_image_div').hide();

	$('#password').val('');

	$('#state').val('');
	$('#city').val('');
	$('#file').val('');
	
	
    var validator = $("#form_update").validate();
    validator.resetForm();

    //$('#assign_dealer_id').val('');
    // $(".master_checkbox").prop("checked", false);
    // $(".child_checkbox").prop("checked", false);
    
    
}








