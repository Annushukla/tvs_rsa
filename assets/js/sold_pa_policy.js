$(document).ready(function(){
     // alert('hiee');

// table = $('#sold_pa_policy_table').DataTable({ 

//             "scrollX": true,
//             "processing": true,
//             'paging': true,
//             'lengthChange': true,
//             'searching': true,
//             'ordering': true,
//             'info': true,
//             'autoWidth': false,
//             'dom': 'Bfrtip',
//             "buttons": ['excel', 'csv', 'pdf', 'print'],  
//         // Load data for the table's content from an Ajax source
//         "ajax": {
//             'url' : base_url+'sold_pa_policy_ajax',
//             "type": "POST",
//             "dataType": "json",
//             "dataSrc": function (jsonData) {
//                     return jsonData.data;
//             }
//         },
 
//         //Set column definition initialisation properties.
//         "columnDefs": [
//         { 
//             "targets": [ 0 ], //first column / numbering column
//             "orderable": false, //set not orderable
//         },
//         ],
//     });
  table = $('#sold_pa_policy_table').DataTable({ 
 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "buttons": ['excel', 'csv', 'pdf', 'print'],  
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+'sold_pa_policy_ajax',
            "type": "POST",
            "data": function ( data ) {
                // data.country = $('#country').val();
                // data.FirstName = $('#FirstName').val();
                // data.LastName = $('#LastName').val();
                // data.address = $('#address').val();
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
 
    $('#btn-filter').click(function(){ //button filter event click
        table.ajax.reload();  //just reload table
    });
    $('#btn-reset').click(function(){ //button reset event click
        $('#form-filter')[0].reset();
        table.ajax.reload();  //just reload table
    });


















$('#sold_policy_submit').click(function(){
  var status_er = false;
  var from_date = $('#from_date').val();
  var to_date = $('#to_date').val();
    if(from_date=="" || to_date==""){
        status_er = true;
        alert('Please Select Date');
    }

    if(status_er == false){
      date1 = new Date(from_date);
      date2 = new Date(to_date);
     
        if(date1 <= date2){
              dealer_request_datatable2 = $('#sold_pa_policy_table').DataTable({
                'paging': true,
                'destroy': true,
                // Load data for the table's content from an Ajax source
                "ajax": {
                    'url' : base_url+'sold_pa_policy_ajax',
                    "type": "POST",
                    "dataType": "json",
                    "data" : {from_date : from_date ,to_date : to_date },
                    "dataSrc": function (jsonData) {
                            return jsonData.data;
                    }
                },
                
                  "scrollX": true,
                  "processing": true,
                  'paging': true,
                  'lengthChange': true,
                  'searching': true,
                  'ordering': true,
                  'info': true,
                  'autoWidth': false,
                  'dom': 'Bfrtip',
                  "buttons": ['excel', 'csv', 'pdf', 'print'],
                //Set column definition initialisation properties.
                "columnDefs": [
                { 
                    "targets": [ 0 ], //first column / numbering column
                    "orderable": false, //set not orderable
                },
                ],
            });
        }else{
            $('#errto_date').text('End Date Should be greater Than Start Date');
        }
    }

});
table = $('#payin_policy_table').DataTable({ 
           "scrollX": true,
          "processing": true,
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
          'info'        : true,
          'autoWidth'   : false,
        // Load data for the table's content from an Ajax source
        "ajax": {
            'url' : base_url+'Paying_slip_policy_ajax',
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


table = $('#generated_payslip_table').DataTable({ 
           "scrollX": true,
          "processing": true,
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
          'info'        : true,
          'autoWidth'   : false,
        // Load data for the table's content from an Ajax source
        "ajax": {
            'url' : base_url+'view_generated_payslip_ajax',
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





});
 