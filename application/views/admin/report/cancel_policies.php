<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>
<?php
        $default_pending = ($this->uri->segment(3) == '') ? 'checked' : '' ;
        $checked_pending = ($this->uri->segment(3) == '4') ? 'checked' : '' ;
        $checked_approved = ($this->uri->segment(3) == '5') ? 'checked' : '' ;
        $checked_rejected = ($this->uri->segment(3) == '3') ? 'checked' : '' ;
?>

<div class="wrapper">

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                RSA Cancel Policies : <?= $cancellation_status?>
            </h1>
             <br><br>
             <div class="row form-group">
                <div class="col-md-2">
                    <a href="<?php echo base_url('admin/cancel_policies/4')?>"><input type="radio" name="" <?= $checked_pending ?> <?= $default_pending ?> >Pending Cancellation</a>
                </div>
                 <div class="col-md-2">
                    <a href="<?php echo base_url('admin/cancel_policies/5')?>"><input type="radio" name="" <?= $checked_approved ?> >Approved Cancellation</a>
                </div>
                <div class="col-md-2">
                    <a href="<?php echo base_url('admin/cancel_policies/3')?>"><input type="radio" name="" <?= $checked_rejected ?> >Rejected Cancellation</a>
                </div>
            </div>
            <br><br>
            <div class="row form-group">
                <div class="col-md-2">
                    <label>Start Date :</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" placeholder="Start Date" min="2018-08-01" max="">
                </div>
                <div class="col-md-2">
                    <label>End Date :</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date" min="2018-08-01" max="">
                    <span id="err_end_date" style="color:red;"></span>
                </div>
                <div class="col-md-2">
                    <label> &nbsp;</label>
                    <button type="button" class="form-control btn btn-primary" name="submit" id="cancel_filter_btn" value="Submit">Submit</button>
                </div>
                <input type="hidden" id="cancel_policy_status" name="cancel_policy_status" value="<?= $cancel_policy_status ;?>">
            </div>
            <br><br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="example-col">
                        <table class="table table-bordered table-striped w-full" id="policy_datatable">
                            <thead>
                            <th>No.</th>
                            <th>Dealer Code</th>
                            <th>Dealer Name</th>
                            <th>Product Type</th>
                            <th>IC Name</th>
                            <th>Plan</th>
                            <th>Policy No.</th>
                            <th>Customer Name</th>
                            <th>Engine No.</th>
                            <th>Chassis No</th>
                            <th>Created Date</th>
                            <th>Reason</th>
                            <th>Cancelled Date</th>
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
                    <h4 class="modal-title">Cancel Policy</h4>
                </div>
                <div class="modal-body">
                    <!-- <p>Reason Of Cancellation</p>
                    <p id="reason_of_cancellation"></p>
                    <input type="hidden" id="policy_id" name="policy_id" class="policy_id"> -->
                    <div class="row ">
                        <div class="col-md-6">
                            Reason Of Cancellation : 
                        </div>
                        <div class="col-md-6">
                            <textarea rows="4" cols="5" class="form-control" id="reason_of_cancellation" name="reason_of_cancellation" required></textarea>
                            <span style="color: red;" id="er_cancel"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            Cancellation Type : 
                        </div>
                        <div class="col-md-6">
                            <p id="cancellation_type"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            Cancellation File : 
                        </div>
                        <div class="col-md-6">
                           <a id="cancel_file_download" target="_blank" download><img class="" id="cancel_file" height="50" width="50" ></a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="policy_id" name="policy_id" class="policy_id">
                    <button type="button" class="btn btn-default" id="approveCancellation">Approve Cancellation</button>
                    <button type="button" class="btn btn-default" id="rejectCancellation">Reject Cancellation</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>



    <!-- /.content-wrapper -->



    <!-- /.content-wrapper -->


    <!-- Control Sidebar -->
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">

    $(document).ready(function () {
        var cancel_policy_status = $('#cancel_policy_status').val();
        table = $('#policy_datatable').DataTable({

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
                'url': base_url + 'admin/cancel_policies_ajax',
                "type": "POST",
                "data" : {cancel_policy_status : cancel_policy_status} ,
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

        $('#cancel_filter_btn').on('click', function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            table1 = $('#policy_datatable').DataTable({
                'paging': true,
                'destroy': true,

                "ajax": {
                    'url': base_url + 'admin/cancel_policies_ajax',
                    "type": "POST",
                    "data": {'start_date': start_date, 'end_date': end_date,cancel_policy_status : cancel_policy_status},
                    "dataType": "json",
                    "dataSrc": function (jsonData) {
                        return jsonData.data;
                    }
                },

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


                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        "targets": [0], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ],

            });

        });
    });


</script>