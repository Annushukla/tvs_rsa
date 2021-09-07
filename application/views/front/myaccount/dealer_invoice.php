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
                    <li class=""><a href="<?php echo base_url('transaction_data');?>"> Transaction Data </a></li>
                    <li class=""><a href="<?php echo base_url('generated_invoice');?>"> Generated Invoice </a></li>
                    <li class="active"><a href="<?php echo base_url('generate_invoice');?>"> Generate Invoice </a></li>


                </ul>   
            </div>         
        </div>
      
        <div class="col-md-9">
            <div class="adminpage-content">  
              
                <h3 class="adminpage-pagehead">Generate Invoice</h3>
                <div class="adminpage-texter">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                               Month: <input type="text" name="" class="form-control" id="invoice_month" placeholder="Month" readonly="readonly">
                            </div>
                            
                            <div class="col-md-4">
                                <br>
                                 <input type="button" name="" class="btn btn-primary" value="Submit" id="invoice_month_submit"> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 id="success_msg" style="color:red;"></h3>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            
        </div>
        
        <div class="col-md-9">
            <div class="adminpage-content" id="invoice_details">  
            </div>
        </div>
    </div>    
</div>
