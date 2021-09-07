<style type="text/css">
  .report-blocks .info-box { min-height: 65px; border-top: 3px solid #d2d6de;}
  .report-blocks .info-box-content { margin-left: 0; }
  .report-blocks .info-box-content .info-box-text {text-overflow: initial;overflow: visible; white-space: pre-wrap;}
  .bg-white { background: #fff; }
  .table thead.thead-dark { color: #fff;  background-color: #212529;  border-color: #32383e; }
  .table-primary, .table-primary>td, .table-primary>th { background-color: #b8daff;}
  .table-success, .table-success>td, .table-success>th { background-color: #c3e6cb; font-size: 18px;}
</style>

<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header report-blocks">
      <h1 style="color:blue">
         Admin Dashboard</h1>
<?php $admin_session = $this->session->userdata('admin_session');?>
    
      <!-- if( ($admin_session['admin_role'] != 'zone_code') && ($admin_session['admin_role_id'] == 2 || $admin_session['admin_role_id'] == 6) ){ ?> -->
<?php if( (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin'))) && in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) || (!empty($admin_session['ic_id'])) ){ ?>

      <div class="col-md-12">
          <div class="row">
            <br>
        <h4 style=" background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; " >Today's Report</h4>
       <div class="col-md-12">
         <div class="col-md-6">
           <p><b>Logged In Dealers:-<?=$total_dealers_logged_in;?></b></p>
         </div>
         <div class="col-md-6">
           <a class="btn btn-primary pull-right" onclick="tableToExcel('ttd', 'Todays Report','TTD_MIS.xls')" >Export to Excel</a>
         </div> 
       </div>
       <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-striped table-bordered bg-white" id="ttd">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">State</th>
                <?php if( ($admin_session['ic_id']==2) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <th scope="col">Kotak Policies</th>
                <?php } if( ($admin_session['ic_id']==5) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <th scope="col">IL Policies</th>
                  <?php } if( ($admin_session['ic_id']==9) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <th scope="col">TATA Policies</th>
                <?php } if( ($admin_session['ic_id']==12 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                  <th scope="col">BAGI Policies</th>
                  <?php }?>
                  <th scope="col">Total Policies</th>
                  <th scope="col">Activated Dealers</th>
                  <th scope="col">Total Active Dealers</th>
                  <th scope="col">Total Dealers</th>
                  <?php if(in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ){ ?>
                  <th scope="col">Total Available Balance</th>
                  <th scope="col">Deposit Amount</th>
                  <?php }?>
                </tr>
              </thead>
              <tbody>
              <?php 
              foreach ($final_dashboard as $key => $dashboard_datas) { 
                $total_kotak_policies = 0;
                $total_il_policies = 0;
                $total_tata_policies = 0;
                $total_bagi_policies = 0;
                $total_policies = 0;
                $todays_total_active_dealers = 0;
                $total_active_dealers = 0;
                $total_dealers = 0;
                $total_available_wallet_balance = 0;
                $total_deposit_amount = 0;
                    foreach ($dashboard_datas as $dashboard_data) {
                      $total_kotak_policies += $dashboard_data['todays_kotak_policies'];
                      $total_il_policies += $dashboard_data['todays_il_policies'];
                      $total_tata_policies += $dashboard_data['todays_tata_policies'];
                      $total_bagi_policies += $dashboard_data['todays_bagi_policies'];
                      $total_policies +=  $dashboard_data['todays_total_policies'];
                      $todays_total_active_dealers +=  $dashboard_data['todays_active_dealers'];
                      $total_active_dealers +=  $dashboard_data['active_dealers'];
                      $total_dealers +=  $dashboard_data['total_dealers'];
                      $total_available_wallet_balance +=  $dashboard_data['available_wallet_balance'];
                      $total_deposit_amount +=  $dashboard_data['todays_deposit_amount'];
                      ?>
                <tr>
                  <th scope="row"><?=$dashboard_data['state']?></th>
                  <?php if( ($admin_session['ic_id']==2) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <td><?=$dashboard_data['todays_kotak_policies']?></td>
                <?php } if( ($admin_session['ic_id']==5) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <td><?=$dashboard_data['todays_il_policies']?></td>
                <?php } if( ($admin_session['ic_id']==9) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <td><?=$dashboard_data['todays_tata_policies']?></td>
                  <?php } if( ($admin_session['ic_id']==12 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                  <td><?=$dashboard_data['todays_bagi_policies']?></td>
                <?php }?>
                  <td><?=$dashboard_data['todays_total_policies']?></td>
                  <td><?=$dashboard_data['todays_active_dealers']?></td>
                  <td><?=$dashboard_data['active_dealers']?></td>
                  <td><?=$dashboard_data['total_dealers']?></td>
                  <?php if(in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ){ ?>
                  <td><?=$dashboard_data['available_wallet_balance']?></td>
                  <td><?=$dashboard_data['todays_deposit_amount']?></td>
                <?php }?>
                </tr>
              <?php  } ?>
                <tr class="table-primary">
                    <th scope="row"><?=$key?></th>
                    <?php if( ($admin_session['ic_id']==2) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                    <th scope="row"><?=$total_kotak_policies;?></th>
                  <?php } if( ($admin_session['ic_id']==5) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                    <th scope="row"><?=$total_il_policies;?></th>
                  <?php } if( ($admin_session['ic_id']==9) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                    <th scope="row"><?=$total_tata_policies;?></th>
                  <?php } if( ($admin_session['ic_id']==12 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                    <th scope="row"><?=$total_bagi_policies;?></th>
                    <?php }?>
                    <th scope="row"><?=$total_policies;?></th>
                    <th scope="row"><?=$todays_total_active_dealers;?></th>
                    <th scope="row"><?=$total_active_dealers;?></th>
                    <th scope="row"><?=$total_dealers;?></th>
                    <?php if(in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ){ ?>
                    <th scope="row"><?=$total_available_wallet_balance;?></th>
                    <th scope="row"><?=$total_deposit_amount;?></th>
                    <?php }?>
                </tr>
            <?php } ?>
             <tr class="table-success">
                <th scope="row">Total</th>
                <?php if( ($admin_session['ic_id']==2) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>

                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'todays_kotak_policies'));?></th>

              <?php } if( ($admin_session['ic_id']==5) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>

                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'todays_il_policies'));?></th>

              <?php } if( ($admin_session['ic_id']==9) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'todays_tata_policies'));?></th>
              <?php } if( ($admin_session['ic_id']==12 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'todays_bagi_policies'));?></th>
              <?php } ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'todays_total_policies'));?></th>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'todays_active_dealers'));?></th>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'active_dealers'));?></th>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'total_dealers'));?></th>
                <?php if(in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ){ ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'available_wallet_balance'));?></th>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'todays_deposit_amount'));?></th>
              <?php }?>
              </tr>
              </tbody>
            </table>
          </div>
          </div>
          </div>
      </div>
      <div class="col-md-12">
          <div class="row">
            <div class="col-md-6">
             <p></p>
           </div>
            <div class="col-md-6">
             <a class="btn btn-primary pull-right" onclick="tableToExcel('mtd', 'Monthly Report','MTD_MIS.xls')" >Export to Excel</a>
           </div> 
            <br><br>
        <h4 style=" background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; " >MTD Report</h4>
            <div class="table-responsive">
            <table class="table table-striped table-bordered bg-white" id="mtd">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">State</th>
                  <?php if( ($admin_session['ic_id']==2) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <th scope="col">Kotak Policies</th>
                  <?php } if( ($admin_session['ic_id']==5) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <th scope="col">IL Policies</th>
                <?php } if( ($admin_session['ic_id']==9) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <th scope="col">TATA Policies</th>
                <?php } if( ($admin_session['ic_id']==12 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                  <th scope="col">BAGI Policies</th>
                <?php }?>
                  <th scope="col">Total Policies</th>
                  <th scope="col">Activated Dealers</th>
                  <th scope="col">Total Active Dealers</th>
                  <th scope="col">Total Dealers</th>
                  <?php if(in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) ){ ?>
                  <th scope="col">Total Available Balance</th>
                  <th scope="col">Deposit Amount</th>
                <?php }?>
                </tr>
              </thead>
              <tbody>
              <?php 
              foreach ($final_dashboard as $key => $dashboard_datas) { 
                $total_kotak_policies = 0;
                $total_il_policies = 0;
                $total_tata_policies = 0;
                $total_bagi_policies = 0;
                $total_policies = 0;
                $last_month_total_active_dealers = 0;
                $total_active_dealers = 0;
                $total_dealers = 0;
                $total_available_wallet_balance = 0;
                $total_deposit_amount = 0;
                    foreach ($dashboard_datas as $dashboard_data) {
                      $total_kotak_policies += $dashboard_data['last_month_kotak_policies'];
                      $total_il_policies += $dashboard_data['last_month_il_policies'];
                      $total_tata_policies += $dashboard_data['last_month_tata_policies'];
                      $total_bagi_policies += $dashboard_data['last_month_bagi_policies'];
                      $total_policies +=  $dashboard_data['last_month_total_policies'];
                      $last_month_total_active_dealers +=  $dashboard_data['last_month_active_dealers'];
                      $total_active_dealers +=  $dashboard_data['active_dealers'];
                      $total_dealers +=  $dashboard_data['total_dealers'];
                      $total_available_wallet_balance +=  $dashboard_data['available_wallet_balance'];
                      $total_deposit_amount +=  $dashboard_data['last_month_deposit_amount'];
                      ?>
                <tr>
                  <th scope="row"><?=$dashboard_data['state']?></th>
                <?php if( ($admin_session['ic_id']==2) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <td><?=$dashboard_data['last_month_kotak_policies']?></td>
                  <?php } if( ($admin_session['ic_id']==5) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <td><?=$dashboard_data['last_month_il_policies']?></td>
                <?php } if( ($admin_session['ic_id']==9) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <td><?=$dashboard_data['last_month_tata_policies']?></td>
                  <?php } if( ($admin_session['ic_id']==12 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                  <td><?=$dashboard_data['last_month_bagi_policies']?></td>
                  <?php }?>
                  <td><?=$dashboard_data['last_month_total_policies']?></td>
                  <td><?=$dashboard_data['last_month_active_dealers']?></td>
                  <td><?=$dashboard_data['active_dealers']?></td>
                  <td><?=$dashboard_data['total_dealers']?></td>
                  <?php if(in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) ){ ?>
                  <td><?=$dashboard_data['available_wallet_balance']?></td>
                  <td><?=$dashboard_data['last_month_deposit_amount']?></td>
                <?php }?>
                </tr>
              <?php  } ?>
                <tr class="table-primary">
                    <th scope="row"><?=$key?></th>
                    <?php if( ($admin_session['ic_id']==2) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                    <th scope="row"><?=$total_kotak_policies;?></th>
                  <?php } if( ($admin_session['ic_id']==5) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                    <th scope="row"><?=$total_il_policies;?></th>
                  <?php } if( ($admin_session['ic_id']==9) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                    <th scope="row"><?=$total_tata_policies;?></th>
                  <?php } if( ($admin_session['ic_id']==12 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                    <th scope="row"><?=$total_bagi_policies;?></th>
                  <?php }?>
                    <th scope="row"><?=$total_policies;?></th>
                    <th scope="row"><?=$last_month_total_active_dealers;?></th>
                    <th scope="row"><?=$total_active_dealers;?></th>
                    <th scope="row"><?=$total_dealers;?></th>
                    <?php if(in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ){ ?>
                    <th scope="row"><?=$total_available_wallet_balance;?></th>
                    <th scope="row"><?=$total_deposit_amount;?></th>
                  <?php }?>
                </tr>
            <?php } ?>
             <tr class="table-success">
                <th scope="row">Total</th>
                <?php if( ($admin_session['ic_id']==2) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'last_month_kotak_policies'));?></th>
                <?php } if( ($admin_session['ic_id']==5) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'last_month_il_policies'));?></th>
                <?php } if( ($admin_session['ic_id']==9) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'last_month_tata_policies'));?></th>
                <?php } if( ($admin_session['ic_id']==12 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'last_month_bagi_policies'));?></th>
                <?php }?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'last_month_total_policies'));?></th>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'last_month_active_dealers'));?></th>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'active_dealers'));?></th>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'total_dealers'));?></th>
                <?php if(in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ){ ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'available_wallet_balance'));?></th>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'last_month_deposit_amount'));?></th>
              <?php }?>
              </tr>
              </tbody>
            </table>
          </div>
          </div>
      </div>
      <div class="col-md-12">
          <div class="row">
            <div class="col-md-6">
             <p></p>
           </div>
            <div class="col-md-6">
             <a class="btn btn-primary pull-right" onclick="tableToExcel('ytd', 'Yearly Report','YTD_MIS.xls')" >Export to Excel</a>
           </div> 
            <br><br>
        <h4 style=" background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; " >YTD Report</h4>
           <div class="table-responsive">
            <table class="table table-striped table-bordered bg-white" id="ytd">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">State</th>
                  <?php if( ($admin_session['ic_id']==2) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <th scope="col">Kotak Policies</th>
                  <?php } if( ($admin_session['ic_id']==5) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <th scope="col">IL Policies</th>
                  <?php } if( ($admin_session['ic_id']==9) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <th scope="col">TATA Policies</th>
                  <?php } if( ($admin_session['ic_id']==12 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                  <th scope="col">BAGI Policies</th>
                  <?php }?>
                  <th scope="col">Total Policies</th>
                  <th scope="col">Activated Dealers</th>
                  <th scope="col">Total Active Dealers</th>
                  <th scope="col">Total Dealers</th>
                  <?php if(in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ){ ?>
                  <th scope="col">Deposit Amount</th>
                <?php }?>
                </tr>
              </thead>
              <tbody>
              <?php 
              foreach ($final_dashboard as $key => $dashboard_datas) { 
                $total_kotak_policies = 0;
                $total_il_policies = 0;
                $total_tata_policies = 0;
                $total_bagi_policies = 0;
                $total_policies = 0;
                $last_year_total_active_dealers = 0;
                $total_active_dealers = 0;
                $total_dealers = 0;
                $total_deposit_amount = 0;
                    foreach ($dashboard_datas as $dashboard_data) {
                      $total_kotak_policies += $dashboard_data['this_year_kotak_policies'];
                      $total_il_policies += $dashboard_data['this_year_il_policies'];
                      $total_tata_policies += $dashboard_data['this_year_tata_policies'];
                      $total_bagi_policies += $dashboard_data['this_year_bagi_policies'];
                      $total_policies +=  $dashboard_data['this_year_total_policies'];
                      $last_year_total_active_dealers +=  $dashboard_data['last_year_active_dealers'];
                      $total_active_dealers +=  $dashboard_data['active_dealers'];
                      $total_dealers +=  $dashboard_data['total_dealers'];
                      $total_deposit_amount +=  $dashboard_data['last_year_deposit_amount'];
                      ?>
                <tr>
                  <th scope="row"><?=$dashboard_data['state']?></th>
                  <?php if( ($admin_session['ic_id']==2) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <td><?=$dashboard_data['this_year_kotak_policies']?></td>
                  <?php } if( ($admin_session['ic_id']==5) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <td><?=$dashboard_data['this_year_il_policies']?></td>
                  <?php } if( ($admin_session['ic_id']==9) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                  <td><?=$dashboard_data['this_year_tata_policies']?></td>
                  <?php } if( ($admin_session['ic_id']==12 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                  <td><?=$dashboard_data['this_year_bagi_policies']?></td>
                  <?php }?>
                  <td><?=$dashboard_data['this_year_total_policies']?></td>
                  <td><?=$dashboard_data['last_year_active_dealers']?></td>
                  <td><?=$dashboard_data['active_dealers']?></td>
                  <td><?=$dashboard_data['total_dealers']?></td>
                  <?php if(in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ){ ?>
                  <td><?=$dashboard_data['last_year_deposit_amount']?></td>
                <?php }?>
                </tr>
              <?php  } ?>
                <tr class="table-primary">
                    <th scope="row"><?=$key?></th>
                    <?php if( ($admin_session['ic_id']==12 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                    <th scope="row"><?=$total_kotak_policies;?></th>
                    <?php } if( ($admin_session['ic_id']==5) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                    <th scope="row"><?=$total_il_policies;?></th>
                    <?php } if( ($admin_session['ic_id']==9) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                    <th scope="row"><?=$total_tata_policies;?></th>
                    <?php } if( ($admin_session['ic_id']==12 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                    <th scope="row"><?=$total_bagi_policies;?></th>
                  <?php }?>
                    <th scope="row"><?=$total_policies;?></th>
                    <th scope="row"><?=$last_year_total_active_dealers;?></th>
                    <th scope="row"><?=$total_active_dealers;?></th>
                    <th scope="row"><?=$total_dealers;?></th>
                    <?php if(in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ){ ?>
                    <th scope="row"><?=$total_deposit_amount;?></th>
                  <?php }?>
                </tr>
            <?php } ?>
             <tr class="table-success">
                <th scope="row">Total</th>
                <?php if( ($admin_session['ic_id']==2) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'this_year_kotak_policies'));?></th>
                <?php } if( ($admin_session['ic_id']==5) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'this_year_il_policies'));?></th>
                <?php } if( ($admin_session['ic_id']==9) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'this_year_tata_policies'));?></th>
                <?php } if( ($admin_session['ic_id']==12 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'this_year_bagi_policies'));?></th>
              <?php }?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'this_year_total_policies'));?></th>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'last_year_active_dealers'));?></th>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'active_dealers'));?></th>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'total_dealers'));?></th>
                <?php if(in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ){ ?>
                <th scope="row"><?= array_sum(array_column($dashboard_summary, 'last_year_deposit_amount'));?></th>
              <?php }?>
              </tr>
              </tbody>
            </table>
          </div>
          </div>
      </div>
<?php }?>
<div class="clearfix"></div>
</section> 







  </div>
  <!-- /.content-wrapper -->



  <!-- /.content-wrapper -->


  <!-- Control Sidebar -->
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>

