<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<?php   
    $checked_pending = ($this->uri->segment(3) == 'pending') ? 'checked' : '' ;
    $checked_approved = ($this->uri->segment(3) == 'approved') ? 'checked' : '' ;
    $checked_referback = ($this->uri->segment(3) == 'referback') ? 'checked' : '' ;
    $checked_rejected = ($this->uri->segment(3) == 'rejected') ? 'checked' : '' ;
    $checked_all = ($this->uri->segment(3) == 'all') ? 'checked' : '' ;
    if(!empty($status)){
        $status = $status ;
    }else{
        $status = 'pending';
        $checked_pending = 'checked' ;
    }
?>

<script type="text/javascript" src="<?php echo base_url('assets/js/tvs_admin.js');?>"></script>
<div class="wrapper">

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                GST Approval : <?= ($status) ?>
            </h1>
            <br><br>
          <div class="row form-group">
            <div class="col-md-2">
                <a href="<?php echo base_url('admin/gst_approval/pending')?>"><input type="radio" name="" <?= $checked_pending ?> >Pending</a>
            </div>
             <div class="col-md-2">
                <a href="<?php echo base_url('admin/gst_approval/approved')?>"><input type="radio" name="" <?= $checked_approved ?> >Approved</a>
            </div>
             <div class="col-md-2">
                <a href="<?php echo base_url('admin/gst_approval/referback')?>"><input type="radio" name="" <?= $checked_referback ?> >Referback</a>
            </div>
             
            <div class="col-md-2">
                <a href="<?php echo base_url('admin/gst_approval/all')?>"><input type="radio" name="" <?= $checked_all ?> >ALL</a>
            </div>
        </div>  
            <br><br>
            <div class="row">
              <div class="col-md-4">
              <span id="message" style="color:red;"><b><?php echo $this->session->flashdata('success');?></b></span>
              </div>
            </div>
            <br><br>
            <div class="row">
                <input type="hidden" id="gst_approval_status" name="gst_approval_status" value="<?= $status ;?>">
                <div class="col-lg-12">
                    <div class="example-col">
                        <table class="table table-bordered table-striped w-full" id="gst_approval_tabl">
                            <thead>
                            <th scope="col">Dealer Name</th>
                            <th scope="col">Dealer Code</th>
                            <th scope="col">GST NO</th>
                            <th scope="col">PAN NO</th>
                            <th scope="col">Invoice No</th>
                            <th scope="col">Invoice date</th>
                            <th scope="col">Invoice-Month</th>
                            <th scope="col">Gst Amount</th>
                            <th scope="col">Created Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Complient File</th>
                            <th scope="col">Action</th>
                            </thead>
                            <tbody>
                        
                            </tbody>
                        </table>
                    </div>
                </div>

                <br><br>
            </div>
    <div id="gst_uploadedfile_modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">GST Uploaded File</h4>
                <b>Dealer Code: </b><span id="dealer_code"></span> &nbsp;&nbsp;<b>Dealer Name: </b><span id="dealer_name">
              </div>
              
              <div class="modal-body">
                <b>Invoice No : </b><span id="gst_invoice_no"></span>
                <div class="row">
                  <div class="col-md-4 gst_file_div">
                    
                  </div>
                  <div class="col-md-2"><b>GST No :</b><span id="gst_no"></span></div>
                  <div class="col-md-2"><b>Pan No :</b><span id="pan_no"></span></div>
                  <div class="col-md-2"><b>GST Amount :</b><p id="gst_amount"></p></div>
                  <div class="col-md-2"><b>GST Month :</b><p id="gst_month"></p></div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                  </div>
                  <div class="col-md-4 comment_div">
                    Comment : <textarea cols="20" rows="5" name="comment" id="comment"></textarea>
                  </div>
                </div>
                  
              </div>
              <div class="modal-footer">
                <input type="hidden" id="hid_gst_invoice" name="hid_gst_invoice">
                <input type="hidden" id="hid_dealer_id" name="hid_dealer_id">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="gst_referback">Referback</button>
              </div>
            </div>
          </div>
        </div>




        </section>

        <!-- /.content --> 

    
<div class="control-sidebar-bg"></div>
</div>
</div>
<script type="text/javascript">

</script>