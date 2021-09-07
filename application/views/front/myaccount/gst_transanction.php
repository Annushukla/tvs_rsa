<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

<!-- thimbnails -->
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
                    <li class=""><a href="<?php echo base_url('gst_transanction');?>"> GST Transaction </a></li>
                    <li class=""><a href="<?php echo base_url('renewal_policy');?>"> Policy Renewal </a></li>
                </ul>   
            </div>         
        </div>
      
        <div class="col-md-9">
            <div class="adminpage-content">  
                <h3 class="adminpage-pagehead">Dealer GST Transaction Data</h3>
                <div class="adminpage-texter">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="transaction_data-mainwrap">
                                <table class="table table-sm table-striped " id="gst_transanction_datatable" border="0">
                                    <thead>
                                        <th>Sr.No.</th>
                                        <th>Invoice No</th>
                                        <th>Invoice Month</th>
                                        <th>Gst Amount</th>
                                        <th>Is Complaint File Upload?</th>
                                        <th>File Name</th>
                                        <th>Approval Status</th>
                                        <th>Comment</th>
                                        <th>created_at</th>
                                        <th>Action</th>
                                       
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>

                                </table>
                               
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
        <div class="row">
            <div class="modal fade" id="gst_upload_modal" role="dialog">
                    <div class="modal-dialog">
                    
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Upload GST Complain File</h4>
                        </div>
                        <form action="<?php echo base_url('UploadGstComplaintData')?>" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                          <div class="row form-group">
                              <div class="col-md-6 form-group">
                                  UPLOAD : <input type="file" id="gst_upload_file" accept="file_extension" class="form-control" name="gst_upload_file" required>
                                  <span style="">(** Only Images are allowed as jpeg, jpg, png **)</span>
                              </div>
                          </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <input type="hidden" id="dealer_id" value="" name="dealer_id">
                          <input type="hidden" id="invoice_no" value="" name="invoice_no">
                          <input type="submit" name="" value="Submit" class="btn btn-success">
                        </div>
                      </div>
                      </form>
                    </div>
                  </div>
                  
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