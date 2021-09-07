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
                Invoice Approval : <?= ($status) ?>
            </h1>
            <br><br>
          <div class="row form-group">
            <div class="col-md-2">
                <a href="<?php echo base_url('admin/invoice_approval/pending')?>"><input type="radio" name="" <?= $checked_pending ?> >Pending</a>
            </div>
             <div class="col-md-2">
                <a href="<?php echo base_url('admin/invoice_approval/approved')?>"><input type="radio" name="" <?= $checked_approved ?> >Approved</a>
            </div>
             <div class="col-md-2">
                <a href="<?php echo base_url('admin/invoice_approval/referback')?>"><input type="radio" name="" <?= $checked_referback ?> >Referback</a>
            </div>
             <div class="col-md-2">
                <a href="<?php echo base_url('admin/invoice_approval/rejected')?>"><input type="radio" name="" <?= $checked_rejected ?> >Rejected</a>
            </div>
            <div class="col-md-2">
                <a href="<?php echo base_url('admin/invoice_approval/all')?>"><input type="radio" name="" <?= $checked_all ?> >ALL</a>
            </div>
        </div>  
            <br><br>
            <div class="row">
              <div class="col-md-4">
              <span id="" style="color:red;"><b><?php echo $this->session->flashdata('success');?></b></span>
              </div>
            </div>
            <br><br>
            <div class="row">
                <input type="hidden" id="invoice_approval_status" name="invoice_approval_status" value="<?= $status ;?>">
                <div class="col-lg-12">
                    <div class="example-col">
                        <table class="table table-bordered table-striped w-full" id="invoice_approval_tabl">
                            <thead>
                            <th scope="col">Dealer Name</th>
                            <th scope="col">Dealer Code</th>
                            <th scope="col">GST NO</th>
                            <th scope="col">PAN NO</th>
                            <th scope="col">Invoice No</th>
                            <th scope="col">Invoice date</th>
                            <th scope="col">Total Policy Count</th>
                            <th scope="col">Total Policy Premium</th>
                            <th scope="col">Total Gst</th>
                            <th scope="col">Final Premium</th>
                            <th scope="col">Invoice-Month</th>
                            <th scope="col">Updated Date</th>
                            <th scope="col">Created Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Comment</th>
                            <th scope="col">Action</th>
                            </thead>
                            <tbody>
                        
                            </tbody>
                        </table>
                    </div>
                </div>

                <br><br>
            </div>
    <div id="invoice_view_modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Invoice-Detail</h4>
              </div>
              <input type="hidden" id="invoice_id" name="invoice_id" class="invoice_id">
              <input type="hidden" id="dealer_id" name="dealer_id" class="dealer_id">
              <input type="hidden" id="total_deposit_amount" name="total_deposit_amount" class="total_deposit_amount">
              <input type="hidden" id="total_policy_commission_gst" name="total_policy_commission_gst" class="total_policy_commission_gst">
              <input type="hidden" id="tds_deducted" name="tds_deducted" class="tds_deducted">
              <div class="modal-body">
                <div class="row">
                  <div class="col-lg-12">
                  <table class="table table-bordered">
                    <tbody class="cancel_policy_count_body">
                      
                    </tbody>
                  </table>
                  </div>
                </div>
                <div class="col-lg-12">
                <table id="classTable" class="table table-bordered" id="invoice_data_tabl">
                  <thead>
                    <tr>
                        <th scope="col">Product Name</th>
                        <th scope="col">Count Of Policies</th>
                        <th scope="col">Policy Premium</th>
                        <th scope="col">Policy Gst</th>
                        <th scope="col">Total Policy Premium</th>
                        <th scope="col">Commission</th>
                        <th scope="col">Commission Gst</th>
                        <th scope="col">Total Commission</th>
                      </tr>
                  </thead>
                  <tbody class="invoice_body" id="invoice_body">

                  </tbody>

                </table>
                </div>
                <div id="deposit-amount-div">
                      <p>Deposit Amount:</p><P id="deposit-amount"></P>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary right" id="send-gst-to-dealer">Approve</button>
                <button type="button" class="btn btn-primary right" id="referback_invoice">Referback</button>
                <button type="button" class="btn btn-primary right" id="reject_invoice">Reject</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>
  
  <div id="reject_modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">Reject Invoice</h4>
        </div>
        <form method="post" action="<?php echo base_url('admin/submit_reject_invoice');?>">
        <input type="hidden" id="rej_invoice_id" name="rej_invoice_id" >
        <input type="hidden" id="rej_dealer_id" name="rej_dealer_id" >
        <div class="modal-body">
          <div class="form-group row">
            <div class="col-md-6">
             Comment : <textarea rows="5" cols="15" class="form-control" id="reject_comment" name="reject_comment" required></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary right" id="reject_btn">Reject</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">
            Close
          </button>
        </form>
        </div>
      </div>
    </div>



        </section>

        <!-- /.content -->

<div class="modal fade bs-example-modal-lg" id="edit_invoice_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Edit Invoice-Detail</h4>
    </div>
    <form action="<?php echo base_url('admin/update_invoice_data')?>" method="post">
    <div class="modal-body">
      <div class="row">
        <div class="form-group col-md-4" >
          Invoice Month : <input type="text" class="form-control" name="edit_invoice_month" id="edit_invoice_month" required readonly>
          <span id="invoice_month_er" style="color:red;"></span>
        </div>
        <div class="form-group col-md-4 ivoice_datepicker" >
          Invoice Date : <input type="text" class="form-control einvoice_date" name="edit_invoice_date" id="edit_invoice_date" required>
        </div>
        <div class="form-group col-md-4" >
          Invoice No : <input type="text" class="form-control" name="edit_invoice_no" id="edit_invoice_no" required>
        </div>
        <input type="hidden" name="hid_invoice_id" id="hid_invoice_id">
        <input type="hidden" name="policy_started_from" id="policy_started_from" >
    </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-warning" >Update</button>
    </div>
  </form>
    </div>
  </div>
</div>
   
<div class="control-sidebar-bg"></div>
</div>
</div>
<script type="text/javascript">
$(document).on("click", ".modal-body", function () {
  $('#invoice_month_er').text('');
  var endYear = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    
  var policy_started_from = $('#policy_started_from').val();
  $('#edit_invoice_month').datepicker({
          format: "mm-yyyy",
          startView: "months", 
          startDate: policy_started_from,
          endDate: endYear,
          viewMode: "months", 
          minViewMode: "months",
          orientation: "right bottom",
          autoclose: true,
      });

var edit_invoice_month = $('#edit_invoice_month').val();
var edit_invoice_date = $('#edit_invoice_date').val();
//alert(edit_invoice_date+'  edit_invoice_month-'+edit_invoice_month);
      var selected_invoice_month_array = edit_invoice_month.split('-');
      var month_select = selected_invoice_month_array[0];
      var year_select = selected_invoice_month_array[1];

      var date = new Date();
      var firstDay = new Date(date.getFullYear(), selected_invoice_month_array[0], 1);
      var lastDay = new Date(date.getFullYear(), selected_invoice_month_array[0], 0);
      // var lastDayWithSlashes = (lastDay.getFullYear()) + '-' + (lastDay.getMonth() + 4) + '-' + lastDay.getDate();
      // var firstDayWithSlashes = (firstDay.getFullYear()) + '-' + (firstDay.getMonth()) + '-' + firstDay.getDate();

    if(month_select=='12'){
        var firstDayWithSlashes = (year_select) + '-' + (lastDay.getMonth()) + '-' + lastDay.getDate();
        var firstDayWithSlashes1 = new Date(firstDayWithSlashes);
        var lastDayWithSlashes = firstDayWithSlashes1.setMonth(firstDayWithSlashes1.getMonth() + 2);
        var lastDayWithSlashes = new Date(lastDayWithSlashes);
    }else{
        var firstDayWithSlashes = (year_select) + '-' + (firstDay.getMonth()) + '-' + firstDay.getDate();
        var firstDayWithSlashes1 = new Date(firstDayWithSlashes);
        var lastDayWithSlashes = firstDayWithSlashes1.setMonth(firstDayWithSlashes1.getMonth() + 2);
        var lastDayWithSlashes = new Date(lastDayWithSlashes);
    }

     // alert(firstDayWithSlashes+'       --       '+lastDayWithSlashes);
         $("#edit_invoice_date").datepicker({
             format: "yyyy-mm-dd",
             startDate: firstDayWithSlashes,
             endDate: lastDayWithSlashes,
             orientation: "right bottom",
            autoclose: true,                                   
           });

  
});


 


</script>