<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Last Week Sold Policies
      </h1>   
      <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="last_soldpolicies_datatble">
              <thead>
              	<th>Sr.no</th>
                <th>Dealer Code</th>
                <th>Dealer Name</th>
                <th>Today</th>
                <th>T1</th>
                <th>T2</th>
                <th>T3</th>
                <th>T4</th>
                <th>T5</th>
                <th>T6</th>
                <th>T7</th>
                <th>T8</th>
                <th>T9</th>
                <th>T10</th>
                <th>T11</th>
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
    last_soldpolicies_datatble = $('#last_soldpolicies_datatble').DataTable({

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
            'url' : base_url+'Report/lastweek_soldpolicies_ajax',
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

</script>