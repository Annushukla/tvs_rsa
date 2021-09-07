<?php $this->load->view('includes/datatable') ?>
<script src="<?php echo base_url(); ?>assets/js/sold_pa_policy.js"></script>
<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">

<main class="section--lightGray main-ewnow">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 align="left" class="heading-sold">Cancelation Request RSA Policies</h2>
            </div>
        </div>

 
        <div class="row">   
            <div class="col-md-12 text-right">
                <a href="<?php echo base_url(); ?>dashboard"><button class="btn btn-primary button_purple my-2 my-sm-0" id="search_button" type="button">Back</button></a>
                <a href="<?php echo base_url(); ?>Cancelled_list" class="btn btn-primary button_purple my-2 my-sm-0" >Canceled List</a>
            </div>
        </div>
        <div class="row"><h3 style="color: #f21692;"></h3></div>
        <div class="row table100 ver1">
            <div class="row">
                <div class="col-md-12 form-group">
                    <div class="col-md-4">
                       From: <input type="text" name="" class="form-control" id="from_date" placeholder="Start Date" readonly="readonly">
                    </div>
                    <div class="col-md-4">
                       to: <input type="text" name="" class="form-control" id="to_date" placeholder="End Date" readonly="readonly">
                       <span style="color: red;" id="errto_date"></span>
                    </div>
                    <div class="col-md-3">
                         <input type="button" name="" class="btn btn-success" value="Submit" id="btn_for_cancel"> 
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="bg-white border p-15">
                    <table class="table display table-striped data_table custom-datatable-soldpapolicy no-border table-sm " id="cancle_rsa_policy_table">
                        <thead class="thead-dark">
                            <th>No.</th>
                            <th>Product Type</th>
                            <th>Plan</th>
                            <th>Policy No.</th>
                            <th>Customer Name</th>
                            <th>Engine No.</th>
                            <th>Chassis No</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    

    <div id="cancelPolicyPopUp" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cancel Policy</h4>
                <p id="error-msg" style="color: red"></p>
            </div>
            <form action="<?php echo base_url('requestCancelPolicy');?>" method="post" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <p>Reason For Policy Cancellation </p>
                        <textarea rows="4" cols="5" name="reason_of_cancelation" id="reason_of_cancelation" class="reason_of_cancelation form-control" required></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <p>Allow to Upload :</p>
                        <select id="cancel_upload_reason" name="cancel_upload_reason" class="form-control" required>
                            <option value="">Select</option>
                            <option value="not_deleverd_or_damaged">Vehicle not delivered/Damaged</option>
                            <option value="duplicate_policy">Duplicate Policy</option>
                            <option value="other_reason">Other Reason</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <p>Upload File :</p>
                         <input type="file" name="cancel_upload_file" class="form-control" accept=".jpg, .jpeg, .png, .pdf" required>
                    </div>
                </div>
                <input type="hidden" name="policy_id" id="policy_id" class="policy_id">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default" id="submitCancelPolicyComment">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>

    </div>
</div>


    </div>
</main>

<script type="text/javascript">


    function cancel_popup(policyid) {

        $('#hidden_policyid').val(policyid);
        $('#cancel_modal').modal();
    }

    function active_popup(policyid) {
        $('#policyid').val(policyid);
        $('#active_modal').modal();
    }

</script>