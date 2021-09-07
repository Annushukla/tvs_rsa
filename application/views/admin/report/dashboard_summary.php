<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Summary Report
      </h1>
     <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="dashboard_summary">
              <thead>
                <th>Sr.no</th>
              	<th>Zone</th>
              	<th>This Year Active Dealers</th>
                <th>This Month Active Dealers</th>
                <th>Todays Active Dealers</th>
                <th>Kotak Policy This Year</th>
                <th>Kotak Policy This Month</th>
                <th>Kotak Policy Today</th>
              	<th>IL Policy This Year</th>
              	<th>IL Policy This Month</th>
              	<th>IL Policy Today</th>
                <th>Oriental Policy This Year</th>
                <th>Oriental Policy This Month</th>
                <th>Oriental Policy Today</th>
                <th>Liberty Policy This Year</th>
                <th>Liberty Policy This Month</th>
                <th>Liberty Policy Today</th>
                <th>Reliance Policy This Year</th>
                <th>Reliance Policy This Month</th>
                <th>Reliance Policy Today</th>
                <th>HDFC Policy This Year</th>
                <th>HDFC Policy This Month</th>
                <th>HDFC Policy Today</th>

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
  table = $('#dashboard_summary').DataTable({ 
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
              'url' : base_url+'admin/dashboard_summary_ajax',
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