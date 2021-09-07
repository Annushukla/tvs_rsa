<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>
<?php   $checked_pending = ($this->uri->segment(3) == 'pending') ? 'checked' : '' ;
        $checked_approved = ($this->uri->segment(3) == 'approved') ? 'checked' : '' ;
        $checked_referback = ($this->uri->segment(3) == 'referback') ? 'checked' : '' ;
        $checked_rejected = ($this->uri->segment(3) == 'rejected') ? 'checked' : '' ;
        $checked_all = ($this->uri->segment(3) == 'all') ? 'checked' : '' ;
        $checked_reconcile = ($this->uri->segment(3) == 'reconcile_approved') ? 'checked' : '' ;
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
                Transaction Status : <?= ($status) ?>
            </h1>
            <br><br>
             <div class="row form-group">
                <div class="col-md-2">
                    <a href="<?php echo base_url('admin/admin_dealer_approve/pending')?>"><input type="radio" name="" <?= $checked_pending ?> >Pending</a>
                </div>
                 <div class="col-md-2">
                    <a href="<?php echo base_url('admin/admin_dealer_approve/approved')?>"><input type="radio" name="" <?= $checked_approved ?> >Approved</a>
                </div>
                 <div class="col-md-2">
                    <a href="<?php echo base_url('admin/admin_dealer_approve/referback')?>"><input type="radio" name="" <?= $checked_referback ?> >Referback</a>
                </div>
                 <div class="col-md-2">
                    <a href="<?php echo base_url('admin/admin_dealer_approve/rejected')?>"><input type="radio" name="" <?= $checked_rejected ?> >Rejected</a>
                </div>
                <div class="col-md-2">
                    <a href="<?php echo base_url('admin/admin_dealer_approve/reconcile_approved')?>"><input type="radio" name="" <?= $checked_reconcile ?> >Reconcile</a>
                </div>
                <div class="col-md-2">
                    <a href="<?php echo base_url('admin/admin_dealer_approve/all')?>"><input type="radio" name="" <?= $checked_all ?> >ALL</a>
                </div>
            </div>
            <br><br>
            <div class="row form-group">
                <div class="col-md-2">
                    <label>Start Date :</label>
                    <input type="date" class="form-control" name="trans_start_date" id="trans_start_date" placeholder="Start Date" min="2018-08-01" max="">
                </div>
                <div class="col-md-2">
                    <label>End Date :</label>
                    <input type="date" class="form-control" name="trans_end_date" id="trans_end_date" placeholder="End Date" min="2018-08-01" max="">
                    <span id="err_end_date" style="color:red;"></span>
                </div>
                <div class="col-md-2">
                    <label>Dealer Code :</label>
                    <input type="text" id="dealer_code" name="" class="form-control">
                </div>
                <div class="col-md-2">
                    <label> &nbsp;</label>
                    <button type="button" class="form-control btn btn-primary" name="submit" id="filter_trans_date" value="Submit">Submit</button>
                </div>
                <input type="hidden" id="approval_status_val" name="approval_status_val" value="<?= $status ;?>">
            </div>
            <br><br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="example-col">
                        <table class="table table-bordered table-striped w-full" id="policy_datatable">
                            <thead>
                            <th>No.</th>
                            <th>Dealer Name</th>
                            <th>Dealer Code</th>
                            <th>Transaction No</th>
                            <?php 
                            if($checked_reconcile == 'checked'){ ?>
                            <th>Reconcile No</th>
                           <?php }
                            ?>
                            <th>Deposit Amount</th>
                            <th>Bank Name</th>
                            <th>Transaction Type</th>                            
                            <th>Transaction Date</th>
                            <th>State</th>
                            <th>Location</th>
                            <th>Created Date</th>
                            <th>Updated Date</th>
                            <th>Approve Status</th>
                            <th>Approved Date</th>
                            <th>Request Type</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                        
                            </tbody>
                        </table>
                    </div>
                </div>

                <br><br>
            </div>



        </section>

        <!-- /.content -->
    </div>
    <div id="cancelPolicyApprove" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Approve Dealer</h4>
                </div>
                
                <div class="modal-body">
                    <p>Do you really want to approve?</p>
                    <div class="row" id="transaction_block">
                        <div class="col-md-6">
                            Transaction No : <input type="text" id="admin_trans_no" name="admin_trans_no" class="form-control" placeholder="Enter Transaction No" minlength="5" required>
                        <span id="error-admin_trans_no" style="color: red"></span><br>
                        </div>
                    </div><br>
                    <input type="hidden" id="dealer_bank_tran_id" name="dealer_bank_tran_id" class="policy_id">
                    <input type="hidden" id="dealer_id" name="dealer_id" class="policy_id">
                    <input type="hidden" id="amount" name="amount" class="amount">
                    <input type="hidden" id="transaction_type" name="transaction_type" class="transaction_type">
                    <input type="hidden" id="action_type" name="action_type" class="action_type">
                    <button type="button" class="btn btn-default" id="approveDealer">YES</button>
                    <!-- <button type="button" class="btn btn-default" id="rejectDealer">NO</button> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div id="statusModalid" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 id="status_modal_header" class="modal-title">Reject Transaction</h4>
                </div>
                
                <div class="modal-body">
                    <p id="status_title">Do you really want to rject ?</p>
                    <form action="<?php echo base_url('admin/updateTransactionStatus');?>" method="post">
                        <div class="row form-group">
                            <div class="col-md-3">Comment : </div>
                            <div class="col-md-5">
                                <textarea id="comment" name="comment" rows="5" cols="15" class="form-control" required></textarea>
                            </div>
                        </div>                    
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="hid_dealer_bank_tran_id" name="dealer_bank_tran_id" class="dealer_bank_tran_id">
                    <input type="hidden" id="hid_dealer_id" name="dealer_id" class="dealer_id">
                    <input type="hidden" id="hid_status" name="hid_status" class="hid_status">
                    <button type="submit" class="btn btn-success" id="">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
            </div>

        </div>
    </div>

<!-- // 1-02-2019 by annu start-->
      <div id="update_transaction_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Transaction Data</h4>
                </div>
                <form action="<?php echo base_url('admin/update_transaction_data');?>" method="post">
                <div class="modal-body">
                    <div class="row" id="update_transaction_block">
                        <div class="col-md-6">
                            Transaction No : <input type="text" id="update_transaction_no" name="update_transaction_no" class="form-control" placeholder="Enter Transaction No" minlength="5" required>
                        </div>
                          <div class="col-md-6">
                            Amount : <input type="number" id="update_amount" name="update_amount" class="form-control" placeholder="Enter Amount" minlength="2" required> RS.
                        </div>
                    </div><br>
                    <input type="hidden" id="update_bank_trans_id" name="update_bank_trans_id" class="update_bank_trans_id">
                    
                    <button type="submit" class="btn btn-success" id="update_trans_data">Update</button>
                    <!-- <button type="button" class="btn btn-default" id="rejectDealer">NO</button> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
            </div>

        </div>
    </div>
<!-- // 1-02-2019 by annu end-->

<div id="reconcile_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Reconcile</h4>
                </div>
                <form action="<?php echo base_url('admin/reconcile_data');?>" method="post">
                <div class="modal-body">
                    <div class="row" id="update_transaction_block">
                        <div class="col-md-6">
                            Reconcile No : <input type="text" id="reconcile_no" name="reconcile_no" class="form-control" placeholder="Enter Reconcile No" required>
                        </div>
                    </div><br>
                    <input type="hidden" id="bank_id" name="bank_id" class="bank_id">
                    
                    <button type="submit" class="btn btn-success" id="reconcile_btn">Reconcile</button>
                    <!-- <button type="button" class="btn btn-default" id="rejectDealer">NO</button> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
            </div>

        </div>
    </div>
    
    <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">

</script>