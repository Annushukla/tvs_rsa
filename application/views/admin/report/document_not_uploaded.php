<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Document Not Uploaded
      </h1>

     <br><br>
   
      <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="docs_not_uploaded">
              <thead>
              	<th>Sr.no</th>
                <th>RSA IC Name</th>
                <th>Dealer Code</th>
                <th>Dealer Name</th>
                <th>Sap Code</th>
                <th>Ad. Name</th>
                <th>Contact No</th>
                <th>State</th>
                <th>Location</th>
                <th>Login</th>
                <th>Created At</th>
                <!-- <th>View</th> -->
            </thead>

              </thead>
              <tbody>
               

              </tbody>
          </table>
      </div>
  </div>
      
    <br><br>
</div>    
      
</section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript">
  
$(document).ready(function(){
    docs_not_uploaded = $('#docs_not_uploaded').DataTable({

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
            'url' : base_url+'Report/docs_not_uploaded_ajax',
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
// $('#date_submit').click(function(){
// var dealerbank_from_date =  $('#dealerbank_from_date').val();
// var dealerbank_to_date = $('#dealerbank_to_date').val();
//   er_date_status = false;

//     if(dealerbank_to_date=='' || dealerbank_from_date==''){
//       er_date_status = true;
//       alert('Please Select the Date');
//     }
//     if(er_date_status== false){
//       date1 = new Date(dealerbank_from_date);
//       date2 = new Date(dealerbank_to_date);
     
//         if(date1 <= date2){
//               transanction_datatable2 = $('#transanction_datatable').DataTable({

//                 'paging': true,
//                   'destroy': true,
//                 // Load data for the table's content from an Ajax source
//                 "ajax": {
//                     'url' : base_url+'Report/BankTransanctiondataAjax',
//                     "type": "POST",
//                     "dataType": "json",
//                     "data" : {dealerbank_from_date : dealerbank_from_date ,dealerbank_to_date : dealerbank_to_date },
//                     "dataSrc": function (jsonData) {
//                             return jsonData.data;
//                     }
//                 },
                
//                   "scrollX": true,
//                   "processing": true,
//                   'paging': true,
//                   'lengthChange': true,
//                   'searching': true,
//                   'ordering': true,
//                   'info': true,
//                   'autoWidth': false,
//                   'dom': 'Bfrtip',
//                   "buttons": ['excel', 'csv', 'pdf', 'print'],
//                 //Set column definition initialisation properties.
//                 "columnDefs": [
//                 { 
//                     "targets": [ 0 ], //first column / numbering column
//                     "orderable": false, //set not orderable
//                 },
//                 ],

//             });
//         }else{
//             $('#errto_date').text('End Date Should be greater Than Start Date');
//         }

              
//     }
// });

});
function login(dealer_id){
          if(dealer_id !=""){
                 $.ajax({
                  url : base_url+'Report/loginAsDealer',
                  type : 'POST',
                  data : {'dealer_id' : dealer_id},
                  dataType : 'JSON',
                  success : function(response){
                    if(response.status == 'true'){
                     window.open(base_url+'dashboard', '_blank');
                    }
                  }
                });
        }
        }
</script>