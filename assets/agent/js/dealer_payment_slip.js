
$(document).ready(function(){
	var dealer_id = $('#dealer_id').val();;
    // var table = $('.data_tables').DataTable({
    //         "processing" : true,
    //         "serverSide" : true,
    //         "order" : [],
    //         "columnDefs": [
    //            { orderable: false, targets: 0 },
    //            { orderable: false, targets: 1 },
               
    //            { orderable: false, targets: 10 }
    //         ],
    //         "ajax" : {
    //             url: base_url+'user/user_controller/ajaxGetPaymentData',
    //             type:"POST",
    //             dataType: "json",
    //             data:{
    //                 dealer_id : dealer_id
    //             }
    //         }
    //     });

    table = $('.data_tables').DataTable({ 

 
        "processing": true, //Feature control the processing indicator.
       	"serverSide": true, //Feature control DataTables' servermside processing mode.
        //"order": [], //Initial no order.
 		"iDisplayLength" : 10,
 
        // Load data for the table's content from an Ajax source
        "ajax": {
        	'url' : base_url+'user/user_controller/ajaxGetPaymentData',
            "type": "POST",
            "dataType": "json",
            "data":{
                dealer_id : dealer_id
            },

            "processing" : true,
            "serverSide" : true,
            "order" : [],
            "columnDefs": [
               { orderable: false, targets: 0 },
               { orderable: false, targets: 1 },
               
               { orderable: false, targets: 10 }
            ],

            "dataSrc": function (jsonData) {
      				return jsonData.data;
 			}
        },
 
        
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
 
    });


});
 
    

