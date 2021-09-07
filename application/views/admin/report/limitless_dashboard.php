
<style type="text/css">
  .report-blocks .info-box { min-height: 65px; border-top: 3px solid #d2d6de;}
  .report-blocks .info-box-content { margin-left: 0; }
  .report-blocks .info-box-content .info-box-text {text-overflow: initial;overflow: visible; white-space: pre-wrap;}
</style>
<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header report-blocks">
    <h1 style="color:blue">Admin Dashboard (LIMITLESS POLICIES)</h1>
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
            <div class="example-col">
                <div class="col-md-3">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Limitless New Policies</span>
                      <span class="info-box-number"><?php echo isset($td_limitless_new)?$td_limitless_new:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
            </div>
            <div class="example-col">
                <div class="col-md-3">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Limitless Renew Policies</span>
                      <span class="info-box-number"><?php echo isset($td_limitless_renew)?$td_limitless_renew:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
            </div>
            <div class="example-col">
                <div class="col-md-3">
                  <div class="info-box">
                    <div class="info-box-content">
                      <span class="info-box-text">Todays Limitless Online Policies</span>
                      <span class="info-box-number"><?php echo isset($td_limitless_online)?$td_limitless_online:'No Policy Till'?></span>
                    </div>
                  </div>
                </div>             
            </div>
            
          </div>
             
        </div>
      </div>
           
      <div class="col-md-12">
        <div class="row">
            <h4 style=" background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; ">Current Month Report</h4>
          <div class="row">
           
          <div class="example-col">
            <div class="col-md-3">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Limitless New Policies</span>
                  <span class="info-box-number"><?php echo isset($mtd_limitless_new)?$mtd_limitless_new:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Limitless Renew Policies</span>
                  <span class="info-box-number"><?php echo isset($mtd_limitless_renew)?$mtd_limitless_renew:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          <div class="example-col">
            <div class="col-md-3">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Limitless Online Policies</span>
                  <span class="info-box-number"><?php echo isset($mtd_limitless_online)?$mtd_limitless_online:'No Policy Till'?></span>
                </div>
              </div>
            </div>             
          </div>
          

                       
          </div>
        </div>

      </div>
      
    <div class="col-md-12">
        <div class="row">
            <h4 style=" background-color:#3c8dbc; font-size: 18px; padding: 7px 10px; color: #fff; ">Current Year Report</h4>
          	<div class="row">
           
	          <div class="example-col">
	            <div class="col-md-3">
	              <div class="info-box">
	                <div class="info-box-content">
	                  <span class="info-box-text">Limitless New Policies</span>
	                  <span class="info-box-number"><?php echo isset($ytd_limitless_new)?$ytd_limitless_new:'No Policy Till'?></span>
	                </div>
	              </div>
	            </div>             
	          </div>

	          <div class="example-col">
	            <div class="col-md-3">
	              <div class="info-box">
	                <div class="info-box-content">
	                  <span class="info-box-text">Limitless Renew Policies</span>
	                  <span class="info-box-number"><?php echo isset($ytd_limitless_renew)?$ytd_limitless_renew:'No Policy Till'?></span>
	                </div>
	              </div>
	            </div>             
	          </div>

	          <div class="example-col">
	            <div class="col-md-3">
	              <div class="info-box">
	                <div class="info-box-content">
	                  <span class="info-box-text">Limitless Online Policies</span>
	                  <span class="info-box-number"><?php echo isset($ytd_limitless_online)?$ytd_limitless_online:'No Policy Till'?></span>
	                </div>
	              </div>
	            </div>             
	          </div>
          

                       
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