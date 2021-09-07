$(document).ready(function(){
     $('#from_date').datepicker({
                        autoclose: true,
                        format: "yyyy-mm-dd"
                    });
      $('#to_date').datepicker({
                              
                              autoclose: true,
                              format: "yyyy-mm-dd"
                          });

        $('#dealerbank_from_date').datepicker({
                  autoclose: true,
                  format: "yyyy-mm-dd"
      });
      $('#dealerbank_to_date').datepicker({
                        autoclose: true,
                        format: "yyyy-mm-dd"
        });
      
      $('#transaction_from_date').datepicker({
                        autoclose: true,
                        format: "yyyy-mm-dd"
        });
      $('#transaction_to_date').datepicker({
                        autoclose: true,
                        format: "yyyy-mm-dd"
        });

$('#download_csv').click(function(){
  var er_status = false;
  var from_date = $('#from_date').val();
  var to_date = $('#to_date').val();
  if(to_date=="" || from_date==""){
    er_status = true;
    $('#er_msg').text('Please Select Date');
  }

  if(er_status==false){
      if(from_date <= to_date){
          $.ajax({
              url : base_url+'downloadpolicybydate',
              type : 'POST',
              dataType : 'JSON',
              data : {from_date : from_date , to_date: to_date},
              success : function(response){
                //alert(response);
                 console.log(response);
                  var $a = $("<a>");
                  $a.attr("href",response.file);
                  $("body").append($a);
                  $a.attr("download",from_date+'_'+to_date+".xls");
                  $a[0].click();
                  $a.remove();
              }



          });


      }else{
        $('#er_to_date').text('To Date shhould be Greater than From date');
      }
  }

});
dealer_request_datatable = $('#dealer_request_datatable').DataTable({

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
        // Load data for the table's content from an Ajax source
        "ajax": {
            'url' : base_url+'Tvs_Dealer/DealerRequestDataAjax',
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
$('#date_submit').click(function(){
var dealerbank_from_date =  $('#dealerbank_from_date').val();
var dealerbank_to_date = $('#dealerbank_to_date').val();
  er_date_status = false;

    if(dealerbank_to_date=='' || dealerbank_from_date==''){
      er_date_status = true;
      alert('Please Select the Date');
    }
    if(er_date_status== false){
      date1 = new Date(dealerbank_from_date);
      date2 = new Date(dealerbank_to_date);
     
        if(date1 <= date2){
              dealer_request_datatable2 = $('#dealer_request_datatable').DataTable({

                'paging': true,
                  'destroy': true,
                // Load data for the table's content from an Ajax source
                "ajax": {
                    'url' : base_url+'Tvs_Dealer/DealerRequestDataAjax',
                    "type": "POST",
                    "dataType": "json",
                    "data" : {dealerbank_from_date : dealerbank_from_date ,dealerbank_to_date : dealerbank_to_date },
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
transaction_datatable = $('#transaction_datatable').DataTable({

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
        // Load data for the table's content from an Ajax source
        "ajax": {
            'url' : base_url+'Tvs_Dealer/TransactionAjax',
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

gst_transanction_datatable = $('#gst_transanction_datatable').DataTable({

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
        // Load data for the table's content from an Ajax source
        "ajax": {
            'url' : base_url+'Rsa_Dashboard/GstTransanctionAjax',
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


cancelled_policy_list = $('#cancelled_policy_list').DataTable({ 
           "scrollX": true,
          "processing": true,
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
          'info'        : true,
          'autoWidth'   : false,
          'dom': 'Bfrtip',
          "buttons": ['excel', 'csv', 'pdf', 'print'],  
        // Load data for the table's content from an Ajax source
        "ajax": {
            'url' : base_url+'CancelledPolicyListAjax',
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

table = $('#cancle_rsa_policy_table').DataTable({ 
           "scrollX": true,
          "processing": true,
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
          'info'        : true,
          'autoWidth'   : false,
          'dom': 'Bfrtip',
          "buttons": ['excel', 'csv', 'pdf', 'print'], 
        // Load data for the table's content from an Ajax source
        "ajax": {
            'url' : base_url+'cancleRsaPolicyAjax',
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

 invoice_list_datatabl = $('#invoice_list_datatabl').DataTable({ 
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
            'url' : base_url+'InvoiceListAjax',
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
    
    
    // $("#submitCancelPolicyComment").on('click',function(){
    //     var policy_id = $("#policy_id").val();
    //     var reasons = $("#reason_of_cancelation").val();
    //     $.ajax({
    //         url:base_url + 'requestCancelPolicy',
    //         data:{policy_id:policy_id,reasons:reasons},
    //         dataType:'JSON',
    //         type:'POST',
    //         success:function(response){
    //             if(response.status = 'true'){
    //                 window.location.href='';
    //             }else{
    //                 $("#error-msg").text('somthing went wrong please try again.');
    //             }
    //         }
            
    //     });
    // });
    
    
});
 function confirmRsaCancelation(policy_id){
     $('#policy_id').val(policy_id);
     $("#cancelPolicyPopUp").modal('show');
 }

function download_transanction_data(){
var status_er = false;
  var transaction_from_date = $('#transaction_from_date').val();
  var transaction_to_date = $('#transaction_to_date').val();
  var datas = transaction_from_date+'/'+transaction_to_date;
    if(transaction_to_date=="" || transaction_from_date==""){
        status_er = true;
        alert('Please Select Date ');
    }

    if(status_er==false){
      date1 = new Date(transaction_from_date);
      date2 = new Date(transaction_to_date);
    if(date1 <= date2){ 
            $.ajax({
                  url : base_url+"Tvs_Dealer/TransactionDataCSV/"+datas,
                  type: "POST",
                  success: function(data){
                    if(data!=""){
                      alert('Feed-File Downloaded');
                    }
                 window.location=base_url+"Tvs_Dealer/TransactionDataCSV/"+datas;
                  }
                });
    }else{
            $('#errto_date').text('End Date Should be greater Than Start Date');
        }
  
    }
}

function UploadModalOpen(dealer_id,invoice_no){
  $("#gst_upload_file").val(null);
 if(dealer_id!="" && invoice_no!=""){
    $("#dealer_id").val(dealer_id);
    $("#invoice_no").val(invoice_no);
    $("#gst_upload_modal").modal();
 }
  
  
}