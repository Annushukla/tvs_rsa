$(document).ready(function(){
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
 if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 

today = yyyy+'-'+mm+'-'+dd;
//document.getElementById("start_date").setAttribute("max", today);
//document.getElementById("end_date").setAttribute("max", today);

  $("#approveCancellation").on('click',function(){
    $("#er_cancel").text("");
    var r = confirm("Are You Sure,Do You Want to Approve ?");
    if (r == true) {
        var policy_id =   $("#policy_id").val();
        var reason_of_cancellation =  $("#reason_of_cancellation").val();
        if(policy_id=="" || reason_of_cancellation==""){
            $("#er_cancel").text("Cancellation Reason is Required.")
            $("#cancelPolicyApprove").modal('show');
        }else{
              $.ajax({
                  url:base_url +'admin/approveRsaCancellation',
                  data:{policy_id:policy_id,reason_of_cancellation:reason_of_cancellation},
                  dataType:'JSON',
                  type:'POST',
                  success:function(response){
                      if(response.status == 'true'){
                          window.location.href='';
                      }
                  }
              });
        }
        
    }
    
   });

  $("#rejectCancellation").on('click',function(){
    var r = confirm("Are You Sure,Do You Want to Reject ?");
    if (r == true) {
        var policy_id =   $("#policy_id").val();
        $.ajax({
            url:base_url +'admin/rejectRsaCancellation',
            data:{policy_id:policy_id},
            dataType:'JSON',
            type:'POST',
            success:function(response){
                if(response.status == 'true'){
                    window.location.href='';
                }
            }
        });
    }
    
   });



  var dataTable = $('#pending_claim').DataTable({
          "scrollX": true,
          "processing": true,
          "serverSide": true,
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
         'info'        : true,
          'autoWidth'   : false,
          'dom': 'Bfrtip',
          "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],          
          "ajax": {
              url: base_url+"admin/pending_ajax", 
              type: "post",
          }        

        });  


  var dataTable = $('#referback_claim').DataTable({
          "scrollX": true,
          "processing": true,
          "serverSide": true,
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
         'info'        : true,
          'autoWidth'   : false,
          'dom': 'Bfrtip',
          "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],          
          "ajax": {
              url: base_url+"admin/referback_ajax", 
              type: "post",
          }        

        });  


  var dataTable = $('#reject_claim').DataTable({
          "scrollX": true,
          "processing": true,
          "serverSide": true,
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
         'info'        : true,
          'autoWidth'   : false,
          'dom': 'Bfrtip',
          "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],          
          "ajax": {
              url: base_url+"admin/reject_ajax", 
              type: "post",
          }        

        });   




/*approved*/



  var dataTable = $('#approved_claim').DataTable({
          "scrollX": true,
          "processing": true,
          "serverSide": true,
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
         'info'        : true,
          'autoWidth'   : false,
          'dom': 'Bfrtip',
          "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],          
          "ajax": {
              url: base_url+"admin/approved_ajax", 
              type: "post",
          }        

        });   









  var dataTable = $('#pending_claim_courier').DataTable({
          "scrollX": true,
          "processing": true,
          "serverSide": true,
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
         'info'        : true,
          'autoWidth'   : false,
          'dom': 'Bfrtip',
          "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],          
          "ajax": {
              url: base_url+"admin/pending-courier-ajax", 
              type: "post",
          }        

        });   



  var dataTable = $('#approved_courier').DataTable({
          "scrollX": true,
          "processing": true,
          "serverSide": true,
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
         'info'        : true,
          'autoWidth'   : false,
          'dom': 'Bfrtip',
          "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],          
          "ajax": {
              url: base_url+"admin/approved-courier-ajax", 
              type: "post",
          }        

        });   

  var dataTable = $('#reject_courier').DataTable({
          "scrollX": true,
          "processing": true,
          "serverSide": true,
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
         'info'        : true,
          'autoWidth'   : false,
          'dom': 'Bfrtip',
          "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],          
          "ajax": {
              url: base_url+"admin/reject-courier-ajax", 
              type: "post",
          }        

        });   
   var dataTable = $('#referback_courier').DataTable({
          "scrollX": true,
          "processing": true,
          "serverSide": true,
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
         'info'        : true,
          'autoWidth'   : false,
          'dom': 'Bfrtip',
          "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],          
          "ajax": {
              url: base_url+"admin/referback-courier-ajax", 
              type: "post",
          }        

        });   
     


  

});

var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name ,filename) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    // window.location.href = uri + base64(format(template, ctx))
    var link = document.createElement("A");
    link.href = uri + base64(format(template, ctx));
    link.download = filename || 'Workbook.xls';
    link.click();
  }
})()



function confirmRsaCancelation(policy_id){
     $.ajax({
        url:base_url+'admin/getReasonOfCancellationPolicy',
        data:{policy_id:policy_id},
        type:'POST',
        dataType:'JSON',
        success:function(response){
          // console.log(response);
            $("#reason_of_cancellation").text(response.cancellation_reson);
            $("#cancellation_type").text(response.cancelation_reason_type);
            if(response.cancel_file_name=="" || response.cancel_file_name==null){
                // $("#cancel_file").attr("src", '');
                $("#cancel_file").removeAttr("src");
                $("#cancel_file_download").removeAttr("href");
                $("#cancel_file").hide();
            }else{
                  $("#cancel_file").show();
                  $("#cancel_file").attr("src", base_url+'uploads/cancel_upload_file/'+response.cancel_file_name);
                  $("#cancel_file_download").attr("href", base_url+'uploads/cancel_upload_file/'+response.cancel_file_name);
            }
            $("#policy_id").val(policy_id);
            $("#cancelPolicyApprove").modal('show');

        }

});
    
}
function confirmRsaCancelationAjax(policy_id){
     $.ajax({
    url:base_url+'getPolicyPayout',
    data:{policy_id:policy_id},
    type:'POST',
    dataType:'JSON',
    success:function(response){
        $("#premium").html(response.policy_premium);
        $("#policies").text(response.no_of_policies);
        $("#payout").text((parseInt(response.no_of_policies) * 60));
        console.log(response);
    }
});
}