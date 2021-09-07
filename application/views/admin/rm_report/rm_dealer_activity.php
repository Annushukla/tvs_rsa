<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        RM Dealer Activity Report
      </h1>   
      <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="rm_dealer_activity_datatable">
              <thead>
              	<th>Sr.no</th>
                <th>Dealer Code</th>
                <th>Dealer Type</th>
                <th>Dealer Name</th>
                <th>Mobile</th>
                <th>State</th>
                <th>Location</th>
                <th>Logged In</th>
                <th>Agreement PDF</th>
                <th>Pan Card</th>
                <th>GST Certificate</th>
                <th>Cancel Cheque</th>
                <th>Payment Status</th>
                <th>IS Sold Policy</th>
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
    rm_dealer_activity_datatable = $('#rm_dealer_activity_datatable').DataTable({

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
            'url' : base_url+'RM_Reports/RMDealerActivityAjax',
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

</script>