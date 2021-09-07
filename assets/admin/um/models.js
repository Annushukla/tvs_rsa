
$(document).ready(function(){
	jQuery.validator.addMethod("uniqueName", function(value, element) {
		var response =  '';
		var name = $('#name').val();
		var make_id = $('#make_id').val();
		var id = $('#id').val();
	    $.ajax({
	        type: "POST",
	        async:false,
	        url: base_url+'admin/AdminController/checkDuplicateModel',
	        dataType : "json",
	        data : { 'name' : name,'make_id' : make_id,'id' : id},
	        
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
	    	make_id: "required",
	    	name:{
	    		required: true,
				uniqueName : true
			}
	   	},
	  	messages: {
			make_id : {
				required: 'Select make required.'
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
        	'url' : base_url+'admin/AdminController/ajaxModels',
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
    	$('#pop_heading').html('Add Model');
        $('#open_model').modal('show');

    });




 	//$("#form_update").submit(function(e){
 	$('#submit').on('click', function() {
 		//e.preventDfault();
      
 	if(!$( "#form_update" ).valid()){ return false; }else{

 		$.ajax({
			url: base_url+'admin-models-update',
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
		url: base_url+'admin-models-edit',
		data : { 'id' : id},
		dataType: 'Json',
		type: 'POST',
		success: function(response){
			resetForm();
			$('#pop_heading').html('Update Model');
			//alert(response.result[0].state);
			$('#name').val(response.result[0].model);
			$('#make_id').val(response.result[0].make_id);
			$('#id').val(response.result[0].id);
			$('#open_model').modal('show');
			
		}
	})	
}


function deleteRecord(id){

	if(confirm('Are you sure delete this data?')){
	
		$.ajax({
			url: base_url+'admin-models-delete',
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
	$('#make_id').val('');
	$('#id').val('');

	var validator = $("#form_update").validate();
    validator.resetForm();

	
}