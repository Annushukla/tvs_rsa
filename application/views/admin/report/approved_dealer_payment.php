<?php
$admin_role=$this->session->userdata('admin_session')['admin_role'];
$ic_id=$this->session->userdata('admin_session')['ic_id'];
?>

<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<script type="text/javascript" src="<?php echo base_url('assets/js/tvs_admin.js');?>"></script>


<div class="wrapper">

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Approved Dealer Payment
            </h1>
            <br><br>
            <h3 style="color: red;"><?php echo $this->session->flashdata('message');?></h3>
            <br><br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="example-col">
                        <table class="table table-bordered table-striped w-full" id="approved_dealer_payment_datatable">
                            <thead>
                            <th>Sr.no</th>
                            <th>Dealer Name</th> 
                            <th>SAP Ad Code</th>
                            <th>Bank Account No.</th>
                            <th>Bank Name</th>
                            <th>Bank IFSC Code</th>
                            <th>Amount Paid</th>
                            <th>Status</th>
                            <th>Approval Date</th>
                            </thead>

                            <tbody>
                 
                            </tbody>
                        </table>
                    </div>
                </div>

                <br><br>
            </div>




<div class="row">
         <div class="col-md-6 pull-right" id="image_loader">
        <img src="<?php echo base_url();?>assets/images/loading5.gif" height="100" width="">
      </div>
    <br><br>
</div>

<div id="dealerPaymentPopUp" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Dealer Payment</h4>
                <p id="error-msg" style="color: red"></p>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Commission :</label>
                        <label id="commission"></label>
                        <input type="hidden" name="dealer_bank_tran_id" id="dealer_bank_tran_id">
                        <input type="hidden" name="amount" id="amount">
                        <input type="hidden" id="dealer_id" name="dealer_id">
                        <input type="hidden" id="transaction_type" name="transaction_type" value="withdrawal">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 form-group">
                        <label>Transaction Number : </label>
                        <input type="text" name="admin_trans_no" id="admin_trans_no" placeholder="Transaction NO.">
                    </div>
                </div>
            <button type="button" class="btn btn-default" id="approveDealer">Submit</button>

            </div>
        </div>
    </div>
</div>

        </section>

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div class="control-sidebar-bg"></div>
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
<script type="text/javascript">

    $(document).ready(function () {
        table = $('#approved_dealer_payment_datatable').DataTable({

            "scrollX": true,
            "processing": true,
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': false,
            'dom': 'Bfrtip',
            "buttons": ['excel', 'csv', 'pdf', 'print'],
            // Load data for the table's content from an Ajax source
            "ajax": {
                'url': base_url + 'admin/approvedDealerPayment_ajax',
                "type": "POST",
                "dataType": "json",
                "dataSrc": function (jsonData) {
                    return jsonData.data;
                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],

        });
    });

</script>