<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

<!-- thimbnails -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/demo.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/set1.css">
<div class="page main-ewnow">
    <div class="container adminpage-wrap">
        <div class="col-md-3">
            <div class="adminpage-sidebar">
                <ul>
                    <li class=""><a href="<?php echo base_url('dealer_request_data');?>">Dealer Request Data</a></li>
                    <li class=""><a href="<?php echo base_url('summary_page');?>">Summary</a></li>
                    <li class=""><a href="<?php echo base_url('myDashboardSection');?>"> Dealer Bank Transaction </a></li>
                    <li class="active"><a href="<?php echo base_url('transaction_data');?>"> Transaction Data </a></li>
                    <li class=""><a href="<?php echo base_url('generated_invoice');?>"> Generated Invoice </a></li>
                    <li class=""><a href="<?php echo base_url('gst_transanction');?>"> GST Transaction </a></li>
                    <li class=""><a href="<?php echo base_url('renewal_policy');?>"> Policy Renewal </a></li>

                </ul>   
            </div>         
        </div>
      
        <div class="col-md-9">
            <div class="adminpage-content">  
              
                <h3 class="adminpage-pagehead">Dealer Transaction Data</h3>
                <div class="adminpage-texter">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <div class="col-md-4">
                               From: <input type="text" name="" class="form-control" id="transaction_from_date" placeholder="Start Date" readonly="readonly">
                            </div>
                            <div class="col-md-4">
                               to: <input type="text" name="" class="form-control" id="transaction_to_date" placeholder="End Date" readonly="readonly">
                               <span style="color: red;" id="errto_date"></span>
                            </div>
                            <div class="col-md-4">
                                 <input type="button" name="" class="btn btn-success" value="Submit" id="transaction_date_submit" onclick="download_transanction_data();"> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="transaction_data-mainwrap">
                                <table class="table table-sm table-striped " id="transaction_datatable" border="0">
                                    <thead>
                                        <th>Sr.No.</th>
                                        <th>Policy No</th>
                                        <th>Customer Name</th>
                                        <th>Vehicle Type</th>
                                        <th>Model</th>
                                        <th>Engine NO</th>
                                        <th>Chassis No</th>
                                        <th>Plan Name</th>
                                        <th>Transaction No</th>
                                        <th>Transaction Amount</th>
                                        <th>Transaction Type</th>
                                        <th>Transaction Purpose</th>
                                        <th>Employee Code</th>
                                        <th>Employee Name</th>
                                        <th>Created Date</th>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>

                                </table>
                                 <div class="transaction_data-totalwrap">
                                    <p><b>Total Balance :</b><?php echo $wallet_balance;?></p>
                                </div>
                            </div>
                        </div>
                            <div class="col-md-6 pull-right" id="image_loader">
                            <img src="<?php echo base_url();?>assets/images/loading5.gif" height="100" width="">
                          </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            
        </div>
    </div>    
</div>
<script type="text/javascript">
  $("#image_loader").css("display","none");
 $(document).ajaxStart(function() {
        // show loader on start
        $("#image_loader").css("display","block");
    }).ajaxSuccess(function() {
        // hide image_loader on success
        $("#image_loader").css("display","none");
    }); 
</script>