$(document).ready(function() {  
  $(".fa").hide();
  //alert("hghf");
  $(".cheque").hide();
  $(".neft").hide();





   $('input[type="radio"]').click(function(){
       
    //alert($(this).val());
    var radio_val = $(this).val();

      if(radio_val=="cheque")
      { 
        $(".cheque").show();
        $(".neft").hide();
     



      }
      if(radio_val=="neft")
      { 
        $(".neft").show();
        $(".cheque").hide();

      }
     
  




    });





   /*------idv change--------*/




   /*----------PA Covers-----------*/


$('#search_claim').click(function(){
 
        var i = $('#policy_no').attr('data-column');
        var v = $('#policy_no').val();
        dataTable_policy_claim.columns(i).search(v).draw();
});

  var dataTable_policy_claim = $('#sold_policy_claim').DataTable({
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
              url: base_url+"front/myaccount/Dashboard/sold_policy_claim_ajax", 
              type: "post",
          }        

        });      

$('#search').click(function(){
        var i = $('#policy_no').attr('data-column');
        var v = $('#policy_no').val();
        dataTable.columns(i).search(v).draw();
});



  var dataTable = $('#policy_courier_claim').DataTable({
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
              url: base_url+"front/myaccount/Dashboard/sold_policy_courier_claim_ajax", 
              type: "post",
          }        

        });  



/*  $("#FromDate").datepicker({ dateFormat: 'dd-mm-yy' });
  $("#ToDate").datepicker({ dateFormat: 'dd-mm-yy' });*/
$('#FromDate').datepicker({
    format: 'dd-mm-yyyy',
        autoclose: true

})

$('#ToDate').datepicker({
    format: 'dd-mm-yyyy',
        autoclose: true

})

$('#paying_slip .policy_price').on("click", function (e) {
 count_data(this);
});









    /*----------Accessories-----------*/
         $('[data-toggle="tooltip"]').tooltip(); 

});


function count_data(e){
var val  = $('[name="check_data[]"]:checked').length;
var values = [];
$(".check_data:checked").each(function() {
    values.push($(this).val()); });
$('#counts_ic_id').val(values);
$('#counts_policy_id').val(values);
$('#counts').val(val);
var jsonval = $('#counts_ic_id').val();
$.ajax({
        type: 'POST',
        url: base_url + 'front/myaccount/Dashboard/count_amount_ajax',
        data: 'jsonval=' + jsonval,
        success: function (html) {
          var data = JSON.parse(html);
          $('#amount_value').val(data.amount);
          $('#amount_value_1').val(data.amount);
          $('#policy_last_date').val(data.created);
        }
    }); 

}


