<?php
$ic_id=$this->session->userdata('admin_session')['ic_id'];
?>

<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>


<div class="wrapper">

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
               RR310 New My TVS Policies
            </h1>
            <br><br>
            <div class="row form-group">
                <div class="col-md-2">
                    <label>Start Date :</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" placeholder="Start Date" min="2018-08-01" max="">
                </div>
                <div class="col-md-2">
                    <label>End Date :</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date" min="2018-08-01" max="">
                </div>
                <div class="col-md-2">
                    <label> &nbsp;</label>
                    <button type="button" class="form-control btn btn-primary" name="submit" id="filter_by_date" value="Submit">Submit</button>
                </div>
            </div>
            <h3 style="color: red;"><?php echo $this->session->flashdata('message');?></h3>
            <br><br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="example-col">
                        <table class="table table-bordered table-striped w-full" id="limitless_assist_mytvs">
                            <thead>
                            <th>Sr.no</th>
                            <th>Certificate No</th> 
                            <th>IC</th>
                            <th>Engine no</th>
                            <th>Chassis No</th>
                            <th>Model Name</th>
                            <th>Dealer Code</th>
                            <th>Dealer Name</th>
                            <th>Customer Name</th>
                            <th>Plan Name</th>
                            <th>Basic Premium</th>
                            <th>Gst Amount</th>
                            <th>Total Premium</th>
                            <th>Dealer Commission</th>
                            <th>Zone</th>
                            <th>Created Date</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Download PDF</th>
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

<div id="cancelPolicyPopUp" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cancel Policy</h4>
                <p id="error-msg" style="color: red"></p>
            </div>
            <form action="<?php echo base_url('admin/RequestcancelpolicyByAdmin');?>" method="post" enctype="multipart/form-data">
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
        table = $('#limitless_assist_mytvs').DataTable({

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
                'url': base_url + 'admin/limitless_assist_RR310_Mytvs_ajax',
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

        $('#filter_by_date').on('click', function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            table1 = $('#limitless_assist_mytvs').DataTable({
                'paging': true,
                'destroy': true,

                "ajax": {
                    'url': base_url + 'admin/limitless_assist_RR310_Mytvs_ajax',
                    "type": "POST",
                    "data": {'start_date': start_date, 'end_date': end_date},
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
function cancelPolicy_by_admin(policy_id){
    //alert(policy_id);
    $('#policy_id').val(policy_id);
    $("#cancelPolicyPopUp").modal('show');
}


</script>