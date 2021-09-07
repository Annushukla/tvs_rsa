
$(document).ready(function(){
	//var dealer_id = "1";
    var dealer_id = $('#dealer_id').val();;
    //alert(dealer_id);
	//datatables
    table = $('.data_tables').DataTable({ 

 
        "processing": true, //Feature control the processing indicator.
       	"serverSide": true, //Feature control DataTables' servermside processing mode.
        //"order": [], //Initial no order.
 		"iDisplayLength" : 10,
 
        // Load data for the table's content from an Ajax source
        "ajax": {
        	'url' : base_url+'admin/adminController/ajaxGetPaymentData',
            "type": "POST",
            "dataType": "json",
            "data":{
                dealer_id : dealer_id
            },
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


});
 
    

