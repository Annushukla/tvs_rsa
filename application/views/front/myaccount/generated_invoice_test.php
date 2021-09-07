<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

<!-- thimbnails -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/demo.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/set1.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/js/invoice.js"></script>
<div class="page main-ewnow">
    <div class="container adminpage-wrap">
        <div class="col-md-3">
            <div class="adminpage-sidebar">
                <ul>
                    <li class=""><a href="<?php echo base_url('dealer_request_data');?>">Dealer Request Data</a></li>
                    <li class=""><a href="<?php echo base_url('summary_page');?>">Summary</a></li>
                    <li class=""><a href="<?php echo base_url('myDashboardSection');?>"> Dealer Bank Transaction </a></li>
                    <li class=""><a href="<?php echo base_url('transaction_data');?>"> Transaction Data </a></li>
                    <li class="active"><a href="<?php echo base_url('generated_invoice');?>"> Generated Invoice </a></li>
                    <li class=""><a href="<?php echo base_url('gst_transanction');?>"> GST Transaction </a></li>
                    <li class=""><a href="<?php echo base_url('renewal_policy');?>"> Policy Renewal </a></li>
                </ul>   
            </div>         
        </div>
      
        <div class="col-md-9">
            <div class="adminpage-content">  
              
                <h3 class="adminpage-pagehead">Generated Invoice List</h3>
                <div class="adminpage-texter">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                               Month: <input type="text" name="" class="form-control" id="invoice_month" placeholder="Month" readonly="readonly">
                               <input type="hidden" name="session_dealer_id" id="session_dealer_id" value="<?= $user_id ;?>">
                               <input type="hidden" name="invoice_created_dates" id="invoice_created_dates" value="<?= $invoice_date_ar;?>">
                               <input type="hidden" name="policy_started_from" id="policy_started_from" value="<?= $policy_started_from;?>">
                               
                            </div>
                            
                            <div class="col-md-4">
                                <br>
                                 <input type="button" name="" class="btn btn-primary" value="View Invoice" id="invoice_month_submit"> 
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 transaction_data-mainwrap">
                          <div class="dataTables_wrapper">
                            <table class="table  table-striped" id="invoice_list_datatabl">
                                <thead class="summary_tblhead">
                                    <th scope="col">Dealer Name</th>
                                    <th scope="col">Invoice No</th>
                                    <th scope="col">Invoice date</th>
                                    <th scope="col">Invoice Month</th>
                                    <th scope="col">Total Policy Count</th>
                                    <th scope="col">Total Policy Premium</th>
                                    <th scope="col">Total Gst</th>
                                    <th scope="col">Final Premium</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Created Date</th>
                                    <th scope="col">Comment</th>
                                    <th scope="col">Action</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                 
                </div>
                <div class="clearfix"></div>
            </div>
            
        </div>
        
        <div class="col-md-12">
            <!-- <div class="adminpage-content" id="invoice_details">  
            </div> -->
            <div class="modal fade" id="generate_invoice_modal" role="dialog">
                <div class="modal-dialog">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Generate Invoice</h4>
                    </div>
                    <div class="modal-body" >
                        <div class="row">
                            <div class="col-md-12">
                                <h3 id="success_msg" style="color:red;"></h3>
                            </div>
                        </div>
                        <br>
                      <div class="" id="invoice_details">
                          
                      </div>
                    
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                  
                </div>
              </div>
        </div>
    </div>    
</div>