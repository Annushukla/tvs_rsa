<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>
<?php 
        $admin_session = $this->session->userdata('admin_session');
       ?>
<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         Dealer Wise Report
      </h1>
     <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="dealer_wise_report_datatable">
              <thead>
                <th>Sr.no</th>
                <th>Dealer Code</th>
                <th>Dealer Name</th>
                <th>Location</th>
                <th>Territory</th>
                <th>Area</th>
                <th>Zone</th>
                <th>Activation Status</th>
                <?php if( ($admin_session['ic_id']==2) || in_array($admin_session['admin_role_id'], array(2,6,1) ) || ($admin_session['admin_role']=='zone_code') ){ ?>
                <th>Deposited Amount</th>
                <th>Amount Policy Issued</th>
                <th>Balance Amount</th>
                <th>Count Of Policies</th>
                <th>Count Platinum Plans</th>
                <th>Count Sapphire Plans</th>
                <th>Count Gold Plans</th>
                <th>Count Silver Plans</th>
                <?php } if( ($admin_session['ic_id']==2) || ($admin_session['ic_id']==5) || ($admin_session['admin_role']=='zone_code') || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){ ?>
                <th>Count Of IL</th>
              <?php } if( ($admin_session['ic_id']==2) || ($admin_session['ic_id']==2) || ($admin_session['admin_role']=='zone_code') || (in_array($admin_session['admin_role_id'], array(2,6,1) )) ) {?>
                <th>Count Of Kotak</th>
                <?php } if( ($admin_session['ic_id']==2) || ($admin_session['ic_id']==11) || ($admin_session['admin_role']=='zone_code') || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){?>
                <th>Count Of TVS AA</th>
              <?php } if( ($admin_session['ic_id']==2) || ($admin_session['ic_id']==1) || ($admin_session['admin_role']=='zone_code') || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){ ?>
                <th>Count Of BHARTI AA</th>
              <?php } if( ($admin_session['ic_id']==9)|| ($admin_session['admin_role']=='zone_code') || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){ ?>
                <th>Count Of TATA</th>
              <?php } if( ($admin_session['ic_id']==12) || ($admin_session['admin_role']=='zone_code') || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){ ?>
                <th>Count Of BAGI</th>
            <?php } if( ($admin_session['ic_id']==10) || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){ ?>
                <th>Count Of Oriental</th>
            <?php } if( ($admin_session['ic_id']==13) || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){ ?>
                <th>Count Of Liberty</th>
            <?php } if( ($admin_session['ic_id']==8) || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){ ?>
                <th>Count Of Reliance</th>
             <?php } if( ($admin_session['ic_id']==7) || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){ ?>
                <th>Count Of HDFC</th>

              <?php } ?>
              <th>Action</th>
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
  table = $('#dealer_wise_report_datatable').DataTable({ 
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

</script>