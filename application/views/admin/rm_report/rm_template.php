<style type="text/css">
  .report-blocks .info-box { min-height: 65px; border-top: 3px solid #d2d6de;}
  .report-blocks .info-box-content { margin-left: 0; }
  .report-blocks .info-box-content .info-box-text {text-overflow: initial;overflow: visible; white-space: pre-wrap;}
</style>

<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header report-blocks">
    <h1 style="color:blue">RM Dashboard</h1>
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
              <h4 style=" background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; " >Today's Report</h2>
             
               <div class="row">

              <div class="col-md-3" style="">
                <div class="info-box">
                  <div class="info-box-content">
                    <span class="info-box-text">No Of Policies Today</span>
                    <span class="info-box-number"><?php echo isset($todays_policies)?$todays_policies:'No Policy Till'?></span>
                  </div>
                </div>
              </div>

              <div class="example-col">
                <div class="col-md-3" style="">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Oriental Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_oriental_policies)?$todays_oriental_policies:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
              </div>
               
              
               <div class="col-md-3" style="">
                <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays ICICI Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_icici_policies)?$todays_icici_policies:'No Policy Till'?></span>
                    </div>
                  </div>
              </div>
              <div class="col-md-3" style="">
                <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Bharti AXA Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_bharti_axa_policies)?$todays_bharti_axa_policies:'No Policy Till'?></span>
                    </div>
                  </div>
              </div>
              <div class="col-md-3" style="">
                <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Tata AIG Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_tata_aig_policies)?$todays_tata_aig_policies:'No Policy Till'?></span>
                    </div>
                  </div>
              </div>
               <div class="col-md-3" style="">
                <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Liberty General Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_liberty_policies)?$todays_liberty_policies:'No Policy Till'?></span>
                    </div>
                  </div>
              </div>
               <div class="col-md-3" style="">
                <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Reliance Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_reliance_policies)?$todays_reliance_policies:'No Policy Till'?></span>
                    </div>
                  </div>
              </div>
              <div class="col-md-3" style="">
                <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays HDFC Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_hdfc_policies)?$todays_hdfc_policies:'No Policy Till'?></span>
                    </div>
                  </div>
              </div>
              <div class="example-col" style="">
                <div class="col-md-3">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Kotak Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_kotak_policies)?$todays_kotak_policies:'No Policy Till'?></span>
                    </div>
                  </div>            
                </div>             
            </div>
              <div class="example-col" style="">
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
                <div class="col-md-3" style="">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays MYTVS Policies</span>
                      <span class="info-box-number"><?php echo isset($todays_mytvs_policies)?$todays_mytvs_policies:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
              </div>
            

          </div>
             
        </div>
      </div>
           
      <div class="col-md-12">
        <div class="row">
            <h4 style=" background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; " >Total Records</h4>
          <div class="row">
          
          <div class="col-md-3" style="">
            <div class="info-box">
              <div class="info-box-content">
                <span class="info-box-text">Total Policies</span>
                <span class="info-box-number"><?php echo isset($total_policies)?$total_policies:'No Policy Till'?></span>
              </div>
            </div>
          </div>

          <div class="example-col">
            <div class="col-md-3" style="">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total Oriental Policies</span>
                  <span class="info-box-number"><?php echo isset($total_oriental_policies)?$total_oriental_policies:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>

           <div class="example-col">
            <div class="col-md-3" style="">
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
          
          <div class="col-md-3" style="">
            <div class="info-box">
              <div class="info-box-content">
                <span class="info-box-text">Total Bharti AXA Policies</span>
                <span class="info-box-number"><?php echo isset($total_bharti_axa_policies)?$total_bharti_axa_policies:'No Policy Till'?></span>
              </div>
            </div>
          </div>
           <div class="col-md-3" style="">
            <div class="info-box">
              <div class="info-box-content">
                <span class="info-box-text">Total Tata aig Policies</span>
                <span class="info-box-number"><?php echo isset($total_tata_aig_policies)?$total_tata_aig_policies:'No Policy Till'?></span>
              </div>
            </div>          
          </div>
          <div class="example-col">
            <div class="col-md-3" style="">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total Liberty Policies</span>
                  <span class="info-box-number"><?php echo isset($total_liberty_policies)?$total_liberty_policies:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total Reliance Policies</span>
                  <span class="info-box-number"><?php echo isset($total_reliance_policies)?$total_reliance_policies:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total HDFC Policies</span>
                  <span class="info-box-number"><?php echo isset($total_hdfc_policies)?$total_hdfc_policies:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total Kotak Policies</span>
                  <span class="info-box-number"><?php echo isset($total_kotak_policies)?$total_kotak_policies:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3" style="">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total MYTVS Policies</span>
                  <span class="info-box-number"><?php echo isset($total_mytvs_policies)?$total_mytvs_policies:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          
          <div class="example-col">
            <div class="col-md-3" style="">
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
                  <h3><?= $row['MONTH']?></h3>
                  <span class="info-box-text" style="" >Oriental : <b><?= $row['oriental_policies']?></b></span>
                  <span class="info-box-text" style="" >ICICI Lombard : <b><?= $row['il_policies']?></b></span>
                  <span class="info-box-text" style="" >TATA : <b><?= $row['tata_policies']?></b></span>
                  <span class="info-box-text" style="" >BAGI : <b><?= $row['bagi_policies']?></b></span>
                  <span class="info-box-text" style="" >Liberty : <b><?= $row['liberty_policies']?></b></span>
                  <span class="info-box-text" style="" >Reliance : <b><?= $row['reliance_policies']?></b></span>
                  <span class="info-box-text" style="" >HDFC : <b><?= $row['hdfc_policies']?></b></span>
                  <span class="info-box-text" style="" >Kotak : <b><?= $row['kotak_policies']?></b></span>
                </div>
              </div>
            </div>             
          </div>
           <?php }?>
        </div>
      </div>
      </div>
<?php }?>

      <?php
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
                  <h3><?= $row['MONTH']?></h3>
                  <span class="info-box-text" style="" >Bharti Assist : <b><?= $row['bharti_rsa_policies']?></b></span>
                  <span class="info-box-text" style="" >MY TVS : <b><?= $row['mytvs_rsa_policies']?></b></span>
                  <span class="info-box-text" style="" >Total : <b><?= ($row['bharti_rsa_policies']+$row['mytvs_rsa_policies'])?></b></span>
                </div>
              </div>
            </div>             
          </div>
           <?php }?>
        </div>
      </div>
      </div>
       <?php } ?>


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

