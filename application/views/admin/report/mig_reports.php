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
        MIG Reports
      </h1>
     <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="mig_report_datatable">
              <thead>
                <th>Sr.no</th>
                <th>Zone</th>
                <th>State</th>
              	<th>Total No AMD Dealers</th>
              	<th>No. Of AMD Activated</th>
                <th>%age AMD Activated</th>
                <th>No. Of AD Dealers</th>
                <th>No. Of AD Activated</th>
              	<th>%age AD Activated</th>
              	<th>Amount Deposited By Dealers</th>
              	<th>Amount of Policy issued</th>
                <th>No. of Policy issued</th>
              </thead>
              <tbody>
               <td>1</td>
               <td>2</td>
               <td>3</td>
               <td>4</td>
               <td>5</td>
               <td>6</td>
               <td>7</td>
               <td>8</td>
               <td>9</td>
               <td>10</td>
               <td>11</td>
               <td>12</td>
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
  table = $('#mig_report_datatable').DataTable({ 
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
              'url' : base_url+'admin/mig_report_ajax',
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