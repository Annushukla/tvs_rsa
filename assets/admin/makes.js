
$(document).ready(function(){
	jQuery.validator.addMethod("uniqueName", function(value, element) {
		var response =  '';
		var name = $('#name').val();
		var id = $('#id').val();
		var product_type_id = $('#product_type_id').val();
	    $.ajax({
	        type: "POST",
	        async:false,
	        url: base_url+'admin/AdminController/checkDuplicateMake',
	        dataType : "json",
	        data : { 'name' : name,'id' : id,'product_type_id' : product_type_id},
	        
	        success:function(data){
	            response = data.success;
	        }
	    });
	    if(response == '1'){
	        return true  ;
	    }else{
	        return false;
	    }

	}, "Name is Already Exist");

	

	$("#form_update").validate({
		errorElement: 'span',
    	errorClass: 'err',
	  	rules: {
	    	product_type_id: "required",
	    	name:{
	    		required: true,
				uniqueName : true
			}
	   	},
	  	messages: {
			product_type_id : {
				required: 'Select product type required.'
			},
			name : {
				required: 'Name field is required.'
			
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
        	'url' : base_url+'admin/AdminController/ajaxMakes',
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
    	$('#pop_heading').html('Add Make');
        $('#open_model').modal('show');

    });




 	//$("#form_update").submit(function(e){
 	$('#submit').on('click', function() {
 		//e.preventDfault();
      
 	if(!$( "#form_update" ).valid()){ return false; }else{

 		$.ajax({
			url: base_url+'admin-makes-update',
			data : $('#form_update').serialize(),
			dataType: 'Json',
			type: 'POST',
			success: function(response){
				if(response.success == 1){
					table.ajax.reload(null, false);
					$('#open_model').modal('hide');
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


    });


});





function editModel(id){

		
	$.ajax({
		url: base_url+'admin-makes-edit',
		data : { 'id' : id},
		dataType: 'Json',
		type: 'POST',
		success: function(response){
			resetForm();
			$('#pop_heading').html('Update Make');
			//alert(response.result[0].state);
			$('#name').val(response.result[0].make);
			$('#product_type_id').val(response.result[0].product_type_id);
			$('#id').val(response.result[0].id);
			$('#open_model').modal('show');
			
		}
	})	
}


function deleteRecord(id){

	if(confirm('Are you sure delete this data?')){
	
		$.ajax({
			url: base_url+'admin-makes-delete',
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
	$('#state').val('');
	$('#id').val('');

	var validator = $("#form_update").validate();
    validator.resetForm();

	
}