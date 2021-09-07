$(document).ready(function(){
     // alert('hiee');

 $('#from_date').datepicker({
                        
                        autoclose: true,
                        format: "yyyy-mm-dd"
                    });

$('#to_date').datepicker({
                        
                        autoclose: true,
                        format: "yyyy-mm-dd"
                    });

$('#download_csv').click(function(){
  var er_status = false;
  var from_date = $('#from_date').val();
  var to_date = $('#to_date').val();
  if(to_date=="" || from_date==""){
    er_status = true;
  }

  if(er_status==false){
      if(from_date <= to_date){
          $.ajax({
              'url' : base_url+'Tvs_Dealer/DownloadCsv',
              'type' : 'POST',
              'dataType' : 'JSON',
              'data' : {from_date : from_date , to_date: to_date},
              success : function(response){
                  console.log(response);
              }



          });


      }else{
        $('#er_to_date').text('To Date shhould be Greater than From date');
      }
  }

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
    
    
    $("#submitCancelPolicyComment").on('click',function(){
        var policy_id = $("#policy_id").val();
        var reasons = $("#reason_of_cancelation").val();
        $.ajax({
            url:base_url + 'requestCancelPolicy',
            data:{policy_id:policy_id,reasons:reasons},
            dataType:'JSON',
            type:'POST',
            success:function(response){
                if(response.status = 'true'){
                    window.location.href='';
                }else{
                    $("#error-msg").text('somthing went wrong please try again.');
                }
            }
            
        });
    });
    
    
    
});
 function confirmRsaCancelation(policy_id){
     $('#policy_id').val(policy_id);
     $("#cancelPolicyPopUp").modal('show');
 }