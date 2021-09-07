<style type="text/css">
  .report-blocks .info-box { min-height: 65px; border-top: 3px solid #d2d6de;}
  .report-blocks .info-box-content { margin-left: 0; }
  .report-blocks .info-box-content .info-box-text {text-overflow: initial;overflow: visible; white-space: pre-wrap;}
</style>
<?php 
      $admin_session = $this->session->userdata('admin_session');
      $display_oriental = (($admin_session['ic_id']==10) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin'))) ) ? 'block' : 'none' ;

      $hide_data = (  (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin'))) && in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) ) ? 'block' : 'none' ;
        // echo "<pre>"; print_r($data); echo "</pre>"; die('data');
       ?>
<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header report-blocks">
    <h1 style="color:blue">Oriental Dashboard</h1>
    <br>
    <div class="">
      <ul class="nav nav-tabs" style="">
        <li class="active"><a data-toggle="tab" href="#summary">Summary</a></li>
      </ul>

      <div class="tab-content">
        <div id="summary" class="tab-pane fade in active">
          <h3>Summary</h3>
          <div class="col-md-12">
            <div class="row">
              <br>
              <h4 style="background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; " >Today's Report</h2>
             
               <div class="row">
               

             

              <div class="example-col">
                <div class="col-md-3" style="display: <?php echo $display_oriental ;?>">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Sapphire</span>
                      <span class="info-box-text">Policies : <b><?php echo isset($todays_sapphire)?$todays_sapphire:0?></b></span>
                    </div> 
                  </div>
                </div>             
              </div>

              <div class="example-col">
                <div class="col-md-3" style="display: <?php echo $display_oriental ;?>">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Gold</span>
                      <span class="info-box-text">Policies : <b><?php echo isset($todays_gold)?$todays_gold:0?></b></span>
                    </div> 
                  </div>
                </div>             
              </div>

              <div class="example-col">
                <div class="col-md-3" style="display: <?php echo $display_oriental ;?>">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Silver</span>
                      <span class="info-box-text">Policies: <b><?php echo isset($todays_silver)?$todays_silver:0?></b></span>
                    </div> 
                  </div>
                </div>             
              </div>

              <div class="example-col">
                <div class="col-md-3" style="display: <?php echo $display_oriental ;?>">
                  <div class="info-box">
                    <div class="info-box-content">
                    
                      <span class="info-box-text">Todays Platinum</span>
                      <span class="info-box-text">Policies :<b><?php echo isset($todays_platinum)?$todays_platinum:0?></b></span>
                    </div> 
                  </div>
                </div> 
                <div class="col-md-3" style="display: <?php echo $display_oriental ;?>">
                <div class="info-box">
                  <div class="info-box-content">
                    <span class="info-box-text">Todays Total Oriental Policies : <b><?php echo isset($oriental_total_policies)?$oriental_total_policies:0?></b></span>
                  </div>
                </div>
              </div>
            
              </div>

               
          </div>
             
        </div>
      </div>
           
      

       <?php
      if( (in_array($admin_session['ic_id'], array(2,5,7,9,12) ) ) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin')) ) ){

        if(!empty($last_3_months_policies)){?>
       <div class="col-md-12">
        <div class="row">
          <h4 style=" background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; " >Last 3 Months Policies : PA</h4>
      <div class="row">
        <?php foreach($last_3_months_policies as $row){?>
          <div class="example-col">
            <div class="col-md-3" style="">
              <div class="info-box">
                <div class="info-box-content">
                  <h3><?= $row['MONTH'].'-'.$row['policy_year']?></h3>
                  <span class="info-box-text" style="" >Oriental : <b><?= $row['oriental_policies']?></b></span>
                  
                </div>
              </div>
            </div>             
          </div>
           <?php }?>
        </div>
      </div>
      </div>
       <?php } }?>

      <?php
      if(!empty($last_3_months_oriental)){?>
       <div class="col-md-12"  >
        <div class="row">
          <h4 style=" background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; " >Last 3 Months Policies : RSA</h4>
      <div class="row">
        <?php foreach($last_3_months_oriental as $row){?>
          <div class="example-col">
            <div class="col-md-3" style="">
              <div class="info-box">
                <div class="info-box-content">
                  <h3><?= $row['MONTH'].'-'.$row['policy_year']?></h3>
                  <span class="info-box-text">Sapphire : <b><?= $row['sapphire_policies']?></b></span>
                  <span class="info-box-text">Gold : <b><?= $row['gold_policies']?></b></span>
                  <span class="info-box-text">Silver : <b><?= $row['silver_policies']?></b></span>
                  <span class="info-box-text">Platinum : <b><?= $row['platinum_policies']?></b></span>
                  <span class="info-box-text">Total Policies: <b><?= ($row['total_policy'])?></b></span>
                </div>
              </div>
            </div>             
          </div>
           <?php }?>
        </div>
      </div>
      </div>
       <?php } 
       ?>
<div class="col-md-12">
        <div class="row">
            <h4 style="background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; " >Total Records</h4>
          <div class="row">
          
          <div class="col-md-3">
            <div class="info-box">
              <div class="info-box-content">
                <span class="info-box-text">Total Sapphire</span>
                <span class="info-box-text">Policies : <b><?php echo isset($total_oriental[0]['sapphire_policies'])?$total_oriental[0]['sapphire_policies']:0?></b></span>
              </div>
            </div>
          </div>

          <div class="example-col">
            
          </div>

           <div class="example-col">
            <div class="col-md-3">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total Gold</span>
                  <span class="info-box-text">Policies : <b><?php echo isset($total_oriental[0]['platinum_policies'])?$total_oriental[0]['gold_policies']:0?></b></span>
                </div>
              </div>
            </div>             
          </div>

          <div class="col-md-3">
            <div class="info-box">
              <div class="info-box-content">
                <span class="info-box-text">Total Silver</span>
                <span class="info-box-text">Policies : <b><?php echo isset($total_oriental[0]['gold_policies'])?$total_oriental[0]['silver_policies']:0?></b></span>
                </div>
              </div>
            </div>

            <div class="col-md-3">
            <div class="info-box">
              <div class="info-box-content">
                <span class="info-box-text">Total Platinum</span>
                <span class="info-box-text">Policies : <b><?php echo isset($total_oriental[0]['silver_policies'])?$total_oriental[0]['platinum_policies']:0?></b></span>
                </div>
              </div>
            </div>

            <div class="col-md-3">
            <div class="info-box">
              <div class="info-box-content">
                <span class="info-box-text">Total Oriental Policies : <b><?php echo isset($total_oriental[0]['total_policies'])?$total_oriental[0]['total_policies']:0?></b></span>
              </div>
              </div>
            </div>

          </div>

        </div>            
          
      </div>   
    </div>


      <div class="clearfix"></div>
        </div>        
      </div>
    </div>
      
  
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

