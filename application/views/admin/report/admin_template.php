<style type="text/css">
  .report-blocks .info-box { min-height: 65px; border-top: 3px solid #d2d6de;}
  .report-blocks .info-box-content { margin-left: 0; }
  .report-blocks .info-box-content .info-box-text {text-overflow: initial;overflow: visible; white-space: pre-wrap;}
</style>
<?php 
        $admin_session = $this->session->userdata('admin_session');
        $display_kotak = (($admin_session['ic_id']==2) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin') ) ) ) ) ? 'block' : 'none' ;
        $display_icici = (($admin_session['ic_id']==5) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin'))) )) ? 'block' : 'none' ;

         $display_bharti_axa = (($admin_session['ic_id']==12) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin'))) )) ? 'block' : 'none' ;

         $display_tata_aig = (($admin_session['ic_id']==9) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin'))) )) ? 'block' : 'none' ;

         $display_liberty = (($admin_session['ic_id']==13) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin'))) )) ? 'block' : 'none' ;
         $display_reliance = (($admin_session['ic_id']==8) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin'))) )) ? 'block' : 'none' ;

        $display_bharti = (($admin_session['ic_id']==1) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin'))) ) ? 'block' : 'none' ;
        $display_mytvs = (($admin_session['ic_id']==11) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin'))) ) ? 'block' : 'none' ;
        $display_oriental = (($admin_session['ic_id']==10) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin'))) ) ? 'block' : 'none' ;
        $display_hdfc = (($admin_session['ic_id']==7) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin'))) ) ? 'block' : 'none' ;
        $hide_data = (  (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin'))) && in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) ) ? 'block' : 'none' ;
        // echo $display_bharti;die('display_bharti');
        // echo "<pre>"; print_r($data); echo "</pre>"; die('data');
       ?>
<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header report-blocks">
    <h1 style="color:blue">Admin Dashboard</h1>
    <br>
    <div class="">
      <ul class="nav nav-tabs" style="display: <?php echo $hide_data ;?>;">
        <li class="active"><a data-toggle="tab" href="#summary">Summary</a></li>
        <li><a data-toggle="tab" href="#all_data">All Details</a></li>
      </ul>

      <div class="tab-content">
        <div id="summary" class="tab-pane fade in active">
          <h3>Summary</h3>
          <div class="col-md-12">
            <div class="row">
              <br>
              <h4 style="display: <?php echo $hide_data ;?>; background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; " >Today's Report</h2>
             
               <div class="row">
                <div class="col-md-3" style="display: <?php echo $hide_data ;?>">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Logged In Dealers</span>
                      <span class="info-box-number"><?php echo isset($total_dealers_logged_in)?$total_dealers_logged_in:'No Dealer Logged In Yet'?></span>
                    </div>
                  </div>         
              </div>

              <div class="col-md-3" style="display: <?php echo $hide_data ;?>">
                <div class="info-box">
                  <div class="info-box-content">
                    <span class="info-box-text">No Of Policies Today</span>
                    <span class="info-box-number"><?php echo isset($todays_policies)?$todays_policies:'No Policy Till'?></span>
                  </div>
                </div>
              </div>

              <div class="example-col">
                <div class="col-md-3" style="display: <?php echo $display_oriental ;?>">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Oriental Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_oriental_policies)?$todays_oriental_policies:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
              </div>
               
              
               <div class="col-md-3" style="display: <?php echo $display_icici ;?>">
                <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays ICICI Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_icici_policies)?$todays_icici_policies:'No Policy Till'?></span>
                    </div>
                  </div>
              </div>
              <div class="col-md-3" style="display: <?php echo $display_bharti_axa ;?>">
                <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Bharti AXA Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_bharti_axa_policies)?$todays_bharti_axa_policies:'No Policy Till'?></span>
                    </div>
                  </div>
              </div>
              <div class="col-md-3" style="display: <?php echo $display_tata_aig ;?>">
                <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Tata AIG Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_tata_aig_policies)?$todays_tata_aig_policies:'No Policy Till'?></span>
                    </div>
                  </div>
              </div>
               <div class="col-md-3" style="display: <?php echo $display_liberty ;?>">
                <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Liberty General Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_liberty_policies)?$todays_liberty_policies:'No Policy Till'?></span>
                    </div>
                  </div>
              </div>
               <div class="col-md-3" style="display: <?php echo $display_reliance ;?>">
                <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Reliance Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_reliance_policies)?$todays_reliance_policies:'No Policy Till'?></span>
                    </div>
                  </div>
              </div>
              <div class="col-md-3" style="display: <?php echo $display_hdfc ;?>">
                <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays HDFC Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_hdfc_policies)?$todays_hdfc_policies:'No Policy Till'?></span>
                    </div>
                  </div>
              </div>
              <div class="example-col" style="display: <?php echo $display_kotak ;?>">
                <div class="col-md-3">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Kotak Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_kotak_policies)?$todays_kotak_policies:'No Policy Till'?></span>
                    </div>
                  </div>            
                </div>             
            </div>
              <div class="example-col" style="display: <?php echo $display_bharti ;?>">
                <div class="col-md-3">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Bharti RSA Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_bharti_policies)?$todays_bharti_policies:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
              </div>
              <div class="example-col">
                <div class="col-md-3" style="display: <?php echo $display_mytvs ;?>">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays MYTVS Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_mytvs_policies)?$todays_mytvs_policies:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
              </div>
            <div class="example-col">
                <div class="col-md-3" style="display: <?php echo $display_bharti ;?>">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays RR310 New Bharti Assist</span>
                      <span class="info-box-number"><?php echo isset($td_RR310_new_bharti)?$td_RR310_new_bharti:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
            </div>
            <div class="example-col">
                <div class="col-md-3" style="display: <?php echo $display_mytvs ;?>">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays RR310 New My Tvs</span>
                      <span class="info-box-number"><?php echo isset($td_RR310_new_mytvs)?$td_RR310_new_mytvs:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
            </div>
            <div class="example-col">
                <div class="col-md-3" style="display: <?php echo $display_mytvs ;?>">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays RR310 Renew Bharti Assist</span>
                      <span class="info-box-number"><?php echo isset($td_RR310_renew_bharti)?$td_RR310_renew_bharti:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
            </div>
            <div class="example-col">
                <div class="col-md-3" style="display: <?php echo $display_mytvs ;?>">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays RR310 Renew My Tvs</span>
                      <span class="info-box-number"><?php echo isset($td_RR310_renew_mytvs)?$td_RR310_renew_mytvs:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
            </div>
            <div class="example-col">
                <div class="col-md-3" style="display: <?php echo $display_bharti ;?>">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays RR310 Online Bharti Assist</span>
                      <span class="info-box-number"><?php echo isset($td_RR310_online_bharti)?$td_RR310_online_bharti:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
            </div>
            <div class="example-col">
                <div class="col-md-3" style="display: <?php echo $display_mytvs ;?>">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays RR310 Online My Tvs</span>
                      <span class="info-box-number"><?php echo isset($td_RR310_online_mytvs)?$td_RR310_online_mytvs:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
            </div>
          </div>
             
        </div>
      </div>
           
      <div class="col-md-12">
        <div class="row">
            <h4 style="display: <?php echo $hide_data ;?>; background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; " >Total Records</h4>
          <div class="row">
          
          <div class="col-md-3" style="display: <?php echo $hide_data ;?>">
            <div class="info-box">
              <div class="info-box-content">
                <span class="info-box-text">Total Policies</span>
                <span class="info-box-number"><?php echo isset($total_policies)?$total_policies:'No Policy Till'?></span>
              </div>
            </div>
          </div>

          <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $display_oriental ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total Oriental Policies</span>
                  <span class="info-box-number"><?php echo isset($total_oriental_policies)?$total_oriental_policies:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>

           <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $display_bharti ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total Bharti RSA Policies</span>
                  <span class="info-box-number"><?php echo isset($total_bharti_policies)?$total_bharti_policies:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>

          <div class="col-md-3" style="display: <?php echo $display_icici ;?>">
            <div class="info-box">
              <div class="info-box-content">
                <span class="info-box-text">Total ICICI Policies</span>
                <span class="info-box-number"><?php echo isset($total_icici_policies)?$total_icici_policies:'No Policy Till'?></span>
              </div>
            </div>
          </div>

        </div>            
          
      </div>   
    </div>
      <div class="col-md-12"> 
      <div class="row">
        <div class="row">
          <div class="example-col">
          
          <div class="col-md-3" style="display: <?php echo $display_bharti_axa ;?>">
            <div class="info-box">
              <div class="info-box-content">
                <span class="info-box-text">Total Bharti AXA Policies</span>
                <span class="info-box-number"><?php echo isset($total_bharti_axa_policies)?$total_bharti_axa_policies:'No Policy Till'?></span>
              </div>
            </div>
          </div>
           <div class="col-md-3" style="display: <?php echo $display_tata_aig ;?>">
            <div class="info-box">
              <div class="info-box-content">
                <span class="info-box-text">Total Tata aig Policies</span>
                <span class="info-box-number"><?php echo isset($total_tata_aig_policies)?$total_tata_aig_policies:'No Policy Till'?></span>
              </div>
            </div>          
          </div>
          <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $display_liberty ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total Liberty Policies</span>
                  <span class="info-box-number"><?php echo isset($total_liberty_policies)?$total_liberty_policies:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $display_reliance ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total Reliance Policies</span>
                  <span class="info-box-number"><?php echo isset($total_reliance_policies)?$total_reliance_policies:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $display_hdfc ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total HDFC Policies</span>
                  <span class="info-box-number"><?php echo isset($total_hdfc_policies)?$total_hdfc_policies:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $display_kotak ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total Kotak Policies</span>
                  <span class="info-box-number"><?php echo isset($total_kotak_policies)?$total_kotak_policies:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $display_mytvs ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total MYTVS Policies</span>
                  <span class="info-box-number"><?php echo isset($total_mytvs_policies)?$total_mytvs_policies:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $display_mytvs ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total RR310 New BHARTI Assist</span>
                  <span class="info-box-number"><?php echo isset($RR310_new_bharti)?$RR310_new_bharti:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $display_mytvs ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total RR310 New MY TVS</span>
                  <span class="info-box-number"><?php echo isset($RR310_new_mytvs)?$RR310_new_mytvs:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $display_mytvs ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total RR310 Renew BHARTI Assist</span>
                  <span class="info-box-number"><?php echo isset($RR310_renew_bharti)?$RR310_renew_bharti:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $display_mytvs ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total RR310 Renew MY TVS</span>
                  <span class="info-box-number"><?php echo isset($RR310_renew_mytvs)?$RR310_renew_mytvs:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $display_mytvs ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total RR310 Online Bharti Assist</span>
                  <span class="info-box-number"><?php echo isset($RR310_online_bharti)?$RR310_online_bharti:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $display_mytvs ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total RR310 Online MY TVS</span>
                  <span class="info-box-number"><?php echo isset($RR310_online_mytvs)?$RR310_online_mytvs:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          
          <div class="example-col">
            <div class="col-md-3" style="display: <?php echo $hide_data ;?>">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total Available Balance</span>
                  <span class="info-box-number"><?php echo isset($total_wallet_balance)?$total_wallet_balance:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>

                       
          </div>
        </div>

      </div>
         

      </div>


       <?php
      if( (in_array($admin_session['ic_id'], array(2,5,7,8,9,11,12,13) ) ) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin')) ) ){

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
                  <h3><?= $row['MONTH'] .'-'.$row['policy_year']?></h3>
                  <span class="info-box-text" style="display: <?php echo $hide_data ;?>" >Oriental : <b><?= $row['oriental_policies']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_icici ;?>" >ICICI Lombard : <b><?= $row['il_policies']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_tata_aig ;?>" >TATA : <b><?= $row['tata_policies']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_bharti_axa ;?>" >BAGI : <b><?= $row['bagi_policies']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_liberty ;?>" >Liberty : <b><?= $row['liberty_policies']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_reliance ;?>" >Reliance : <b><?= $row['reliance_policies']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_hdfc ;?>" >HDFC : <b><?= $row['hdfc_policies']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_kotak ;?>" >Kotak : <b><?= $row['kotak_policies']?></b></span>
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
      if( (in_array($admin_session['ic_id'], array(1,11) ) ) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) ) && (in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin')) ) ){

       if(!empty($last_3_months_policies)){?>
       <div class="col-md-12"  >
        <div class="row">
          <h4 style=" background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; " >Last 3 Months Policies : RSA</h4>
      <div class="row">
        <?php foreach($last_3_months_policies as $row){?>
          <div class="example-col">
            <div class="col-md-3" style="">
              <div class="info-box">
                <div class="info-box-content">
                  <h3><?= $row['MONTH'] .'-'.$row['policy_year']?></h3>
                  <span class="info-box-text" style="display: <?php echo $display_bharti ;?>" >Bharti Assist : <b><?= $row['bharti_rsa_policies']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_mytvs ;?>" >MY TVS : <b><?= $row['mytvs_rsa_policies']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_mytvs ;?>" >Total : <b><?= ($row['bharti_rsa_policies']+$row['mytvs_rsa_policies'])?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_mytvs ;?>" >RR310 New Bharti Assist : <b><?= $row['limitless_assist_RR310_bharti']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_mytvs ;?>" >RR310 New MY TVS : <b><?= $row['limitless_assist_RR310_mytvs']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_mytvs ;?>" >RR310 Renew Bharti Assist : <b><?= $row['limitless_assistrenew_RR310_bharti']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_mytvs ;?>" >RR310 Renew MY TVS : <b><?= $row['limitless_assistrenew_RR310_mytvs']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_mytvs ;?>" >RR310 Online Bharti Assist : <b><?= $row['limitless_assistE_RR310_bharti']?></b></span>
                  <span class="info-box-text" style="display: <?php echo $display_mytvs ;?>" >RR310 Online MY TVS : <b><?= $row['limitless_assistE_RR310_mytvs']?></b></span>
                </div>
              </div>
            </div>             
          </div>
           <?php }?>
        </div>
      </div>
      </div>
       <?php } }?>



      <div class="clearfix"></div>
        </div>
        <div id="all_data" class="tab-pane fade">
          <h3>All Details</h3>
            
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
                        <?php } if( ($admin_session['ic_id']==7) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                          <th scope="col">HDFC Policies</th>
                        <?php } if( ($admin_session['ic_id']==8) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                          <th scope="col">Reliance Policies</th>
                        <?php } if( ($admin_session['ic_id']==13) || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                          <th scope="col">Liberty Policies</th>
                        <?php } if( (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                          <th scope="col">Oriental Policies</th>
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
                        $total_hdfc_policies = 0;
                        $total_reliance_policies = 0;
                        $total_liberty_policies = 0;
                        $total_oriental_policies = 0;
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
                              $total_hdfc_policies += $dashboard_data['todays_hdfc_policies'];
                              $total_reliance_policies += $dashboard_data['todays_reliance_policies'];
                              $total_liberty_policies += $dashboard_data['todays_liberty_policies'];
                              $total_oriental_policies += $dashboard_data['todays_oriental_policies'];
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
                        <?php } if( ($admin_session['ic_id']==7 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <td><?=$dashboard_data['todays_hdfc_policies']?></td>
                        <?php } if( ($admin_session['ic_id']==8 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <td><?=$dashboard_data['todays_reliance_policies']?></td>
                        <?php } if( ($admin_session['ic_id']==13 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <td><?=$dashboard_data['todays_liberty_policies']?></td>
                        <?php } if(  (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                          <td><?=$dashboard_data['todays_oriental_policies']?></td>
                        <?php }?>
                          <td><?=$dashboard_data['todays_total_policies']?></td>
                          <td><?=$dashboard_data['todays_active_dealers'];?></td>
                          <td><?=$dashboard_data['active_dealers'];?></td>
                          <td><?=$dashboard_data['total_dealers']?></td>
                          <?php if(in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ){ ?>
                          <td><?=$dashboard_data['available_wallet_balance']?></td>
                          <td><?=$dashboard_data['todays_deposit_amount'];?></td>
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
                          <?php } if( ($admin_session['ic_id']==7 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                            <th scope="row"><?=$total_hdfc_policies;?></th>
                          <?php } if( ($admin_session['ic_id']==8 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                            <th scope="row"><?=$total_reliance_policies;?></th>
                          <?php } if( ($admin_session['ic_id']==13 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                            <th scope="row"><?=$total_liberty_policies;?></th>
                          <?php } if(  (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                            <th scope="row"><?=$total_oriental_policies;?></th> 
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
                      <?php } if( ($admin_session['ic_id']==7 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                        <th scope="row"><?= array_sum(array_column($dashboard_summary, 'todays_hdfc_policies'));?></th>
                      <?php } if( ($admin_session['ic_id']==8 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                        <th scope="row"><?= array_sum(array_column($dashboard_summary, 'todays_reliance_policies'));?></th>
                      <?php } if( ($admin_session['ic_id']==13 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                        <th scope="row"><?= array_sum(array_column($dashboard_summary, 'todays_liberty_policies'));?></th>
                      <?php } if( (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                        <th scope="row"><?= array_sum(array_column($dashboard_summary, 'todays_oriental_policies'));?></th>
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
                        <?php } if( ($admin_session['ic_id']==7 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <th scope="col">HDFC Policies</th>
                        <?php } if( ($admin_session['ic_id']==8 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <th scope="col">Reliance Policies</th>
                        <?php } if( ($admin_session['ic_id']==13 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <th scope="col">Liberty Policies</th>
                        <?php } if( (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                          <th scope="col">Oriental Policies</th>
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
                        $total_hdfc_policies = 0;
                        $total_reliance_policies = 0;
                        $total_liberty_policies = 0;

                        $total_oriental_policies = 0;
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
                              $total_hdfc_policies += $dashboard_data['last_month_hdfc_policies'];
                              $total_reliance_policies += $dashboard_data['last_month_reliance_policies'];
                              $total_liberty_policies += $dashboard_data['last_month_liberty_policies'];
                              
                              $total_oriental_policies += $dashboard_data['last_month_oriental_policies'];
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
                        <?php } if( ($admin_session['ic_id']==7 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <td><?=$dashboard_data['last_month_hdfc_policies']?></td>
                        <?php } if( ($admin_session['ic_id']==8 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <td><?=$dashboard_data['last_month_reliance_policies']?></td>
                        <?php } if( ($admin_session['ic_id']==13 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <td><?=$dashboard_data['last_month_liberty_policies']?></td>
                        <?php } if(  (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                          <td><?=$dashboard_data['last_month_oriental_policies']?></td>
                          <?php }?>
                          <td><?=$dashboard_data['last_month_total_policies'];?></td>
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
                          <?php } if( ($admin_session['ic_id']==7 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                            <th scope="row"><?=$total_hdfc_policies;?></th>
                          <?php } if( ($admin_session['ic_id']==8 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                            <th scope="row"><?=$total_reliance_policies;?></th>
                          <?php } if( ($admin_session['ic_id']==13 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                            <th scope="row"><?=$total_liberty_policies;?></th>
                          <?php } if( (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                            <th scope="row"><?=$total_oriental_policies;?></th>
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
                      <?php } if( ($admin_session['ic_id']==7 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                        <th scope="row"><?= array_sum(array_column($dashboard_summary, 'last_month_hdfc_policies'));?></th>
                      <?php } if( ($admin_session['ic_id']==8 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                        <th scope="row"><?= array_sum(array_column($dashboard_summary, 'last_month_reliance_policies'));?></th>
                      <?php } if( ($admin_session['ic_id']==13 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                        <th scope="row"><?= array_sum(array_column($dashboard_summary, 'last_month_liberty_policies'));?></th>
                      <?php } if( (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                        <th scope="row"><?= array_sum(array_column($dashboard_summary, 'last_month_oriental_policies'));?></th>
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
                          <?php } if( ($admin_session['ic_id']==7 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <th scope="col">HDFC Policies</th>
                          <?php } if( ($admin_session['ic_id']==8 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <th scope="col">Reliance Policies</th>
                          <?php } if( ($admin_session['ic_id']==13 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <th scope="col">Liberty Policies</th>
                          <?php } if( (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                          <th scope="col">Oriental Policies</th>
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
                        $total_hdfc_policies = 0;
                        $total_reliance_policies = 0;
                        $total_liberty_policies = 0;
                        $total_oriental_policies = 0;
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
                              $total_hdfc_policies += $dashboard_data['this_year_hdfc_policies'];
                              $total_reliance_policies += $dashboard_data['this_year_reliance_policies'];
                              $total_liberty_policies += $dashboard_data['this_year_liberty_policies'];
                              $total_oriental_policies += $dashboard_data['this_year_oriental_policies'];
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
                          <?php } if( ($admin_session['ic_id']==7 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <td><?=$dashboard_data['this_year_hdfc_policies']?></td>
                          <?php } if( ($admin_session['ic_id']==8 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <td><?=$dashboard_data['this_year_reliance_policies']?></td>
                          <?php } if( ($admin_session['ic_id']==13 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                          <td><?=$dashboard_data['this_year_liberty_policies']?></td>
                        <?php } if( (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                          <td><?=$dashboard_data['this_year_oriental_policies']?></td>
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
                          <?php } if( ($admin_session['ic_id']==7 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                            <th scope="row"><?=$total_hdfc_policies;?></th>
                          <?php } if( ($admin_session['ic_id']==8 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                            <th scope="row"><?=$total_reliance_policies;?></th>
                          <?php } if( ($admin_session['ic_id']==13 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                            <th scope="row"><?=$total_liberty_policies;?></th>
                          <?php } if( (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                            <th scope="row"><?=$total_oriental_policies;?></th>
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
                      <?php } if( ($admin_session['ic_id']==7 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                        <th scope="row"><?= array_sum(array_column($dashboard_summary, 'this_year_hdfc_policies'));?></th>
                      <?php } if( ($admin_session['ic_id']==8 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                        <th scope="row"><?= array_sum(array_column($dashboard_summary, 'this_year_reliance_policies'));?></th>
                      <?php } if( ($admin_session['ic_id']==13 || (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) )) ){ ?>
                        <th scope="row"><?= array_sum(array_column($dashboard_summary, 'this_year_liberty_policies'));?></th>
                      <?php } if( (in_array($admin_session['admin_role_id'], array(2,6,1,8,11)) ) ){ ?>
                        <th scope="row"><?= array_sum(array_column($dashboard_summary, 'this_year_oriental_policies'));?></th>
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

