<style type="text/css">
  .bg-white {background: #fff;}
  .colored_tbl { margin-bottom: 10px; }
    .colored_tbl th{ color: #fff; background-color: #7eb9df; } 
    .colored_tbl td{background-color: #f3fafe; } 
    .colored_tbl>tbody>tr>td, .colored_tbl>tbody>tr>th, .colored_tbl>tfoot>tr>td, .colored_tbl>tfoot>tr>th, .colored_tbl>thead>tr>td, .colored_tbl>thead>tr>th { padding: 5px; }
    .table-bordered.colored_tbl>thead>tr>th, .table-bordered.colored_tbl>tbody>tr>th, .table-bordered.colored_tbl>tfoot>tr>th, .table-bordered.colored_tbl>thead>tr>td, .table-bordered.colored_tbl>tbody>tr>td, .table-bordered.colored_tbl>tfoot>tr>td { border-color: #087cc6; }
    .colored_tbl>caption+thead>tr:first-child>td, .colored_tbl>caption+thead>tr:first-child>th, .colored_tbl>colgroup+thead>tr:first-child>td, .colored_tbl>colgroup+thead>tr:first-child>th, .colored_tbl>thead:first-child>tr:first-child>td, .colored_tbl>thead:first-child>tr:first-child>th {border: 1px solid #087cc6;}
    .colored_tbl .headtitle {background-color: #087cc6;}
    .colored_tbl .headtitle h2 {margin: 5px 0;  font-weight: bold; color: #fff; font-size: 24px;}
    .colored_tbl>tbody>tr:last-child>td {font-weight: bold;}
</style>
<?php 
        $admin_session = $this->session->userdata('admin_session');
       ?>
<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>RM Dealer Wise Report</h1>
     <br><br>
<div class="row">
  <div class="col-lg-12">
    <div class="col-md-6">
     <a class="btn btn-primary pull-right" onclick="tableToExcel('rm_dealerwise_datatable', 'Todays Report','DealerWiseMIS.xls')" >Export to Excel</a><br><br>
   </div>
      <div class="example-col">
          <table class="table table-bordered table-striped bg-white text-center colored_tbl" id="rm_dealerwise_datatable">
              <thead>
                <tr>
                  <th rowspan="2">Sr.no</th>
                  <th rowspan="2">Dealer Code</th>
                  <th rowspan="2">Dealer Name</th>
                  <th rowspan="2">Location</th>
                  <th colspan="3">Bharti Axa</th>
                  <th colspan="3">ICICI Lombard</th>
                  <th colspan="3">TATA AIG</th>
                  <th colspan="3">Reliance</th>
                  <th colspan="3">Oriental</th>
                  <th colspan="3">Kotak</th>
                  <th colspan="3">Liberty</th>
                </tr>
                <tr>
                  <th>TD</th>
                  <th>MTD</th>
                  <th>YTD</th>
                  <th>TD</th>
                  <th>MTD</th>
                  <th>YTD</th>
                  <th>TD</th>
                  <th>MTD</th>
                  <th>YTD</th>
                  <th>TD</th>
                  <th>MTD</th>
                  <th>YTD</th>
                  <th>TD</th>
                  <th>MTD</th>
                  <th>YTD</th>
                  <th>TD</th>
                  <th>MTD</th>
                  <th>YTD</th>
                  <th>TD</th>
                  <th>MTD</th>
                  <th>YTD</th>
                </tr>
                
              </thead>
              <tbody>
              <?php if(!empty($rm_dealerwise_data)){
                $i=0;
                    foreach ($rm_dealerwise_data as $value) { ?>

                    <tr>
                      <td><?=++$i;?></td>
                      <td><?=$value['sap_ad_code']?></td>
                      <td><?=$value['dealer_name']?></td>
                      <td><?=$value['location']?></td>
                      <td><?=$value['td_bagi_policies']?></td>
                      <td><?=$value['mtd_bagi_policies']?></td>
                      <td><?=$value['ytd_bagi_policies']?></td>
                      <td><?=$value['td_il_policies']?></td>
                      <td><?=$value['mtd_il_policies']?></td>
                      <td><?=$value['ytd_il_policies']?></td>
                      <td><?=$value['td_tata_policies']?></td>
                      <td><?=$value['mtd_tata_policies']?></td>
                      <td><?=$value['ytd_tata_policies']?></td>
                      <td><?=$value['td_reliance_policies']?></td>
                      <td><?=$value['mtd_reliance_policies']?></td>
                      <td><?=$value['ytd_reliance_policies']?></td>
                      <td><?=$value['td_oriental_policies']?></td>
                      <td><?=$value['mtd_oriental_policies']?></td>
                      <td><?=$value['ytd_oriental_policies']?></td>
                      <td><?=$value['td_kotak_policies']?></td>
                      <td><?=$value['mtd_kotak_policies']?></td>
                      <td><?=$value['ytd_kotak_policies']?></td>
                      <td><?=$value['td_liberty_policies']?></td>
                      <td><?=$value['mtd_liberty_policies']?></td>
                      <td><?=$value['ytd_liberty_policies']?></td>
                    </tr>
              <?php }}  ?>

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

<!-- <script type="text/javascript">
  
$(document).ready(function(){
  rm_dealerwise_datatable = $('#rm_dealerwise_datatable').DataTable({ 
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
              'url' : base_url+'admin/dealerWiseReportsAjax',
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

</script> -->