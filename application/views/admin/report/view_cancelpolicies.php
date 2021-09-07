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
                PA Cover Policies
            </h1>

            <br><br>
<?php $cancelled_message = $this->session->flashdata('cancelled');
$activated_status = $this->session->flashdata('activated');
    
if(!empty($cancelled_message)){
    ?>
<div class="row form-group">
  <div class="col-md-4 form-group alert alert-danger" role="alert">
    <h4><?php echo isset($cancelled_message) ? $cancelled_message : '';?></h4>
  </div>
</div>
<?php }  if(!empty($activated_status)){ ?>
         
<div class="row form-group">
  <div class="col-md-4 form-group alert alert-success" role="alert">
    <h4><?php echo isset($activated_status) ? $activated_status : '';?></h4>
  </div>
</div>
<?php } ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="example-col">
                        <table class="table table-bordered table-striped w-full" id="policy_datatable">
                            <thead>
                            <th>Sr.no</th>
                            <th>Invoice No</th>
                            <th>Invoice Date</th>
                            <th>Created Date</th>
                            <th>Engine no</th>
                            <th>Chassis No</th>
                            <th>Certificate No</th>
                            <th>Dealer Code</th>
                            <th>Dealer Name</th>
                            <th>Status</th>
                            <th>Action</th>

                            </thead>
                            <tbody>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                            <td>7</td>
                            <td>8</td>
                            <td>9</td>
                            <td>10</td>
                            <td>11</td>

                            </tbody>
                        </table>
                    </div>
                </div>

                <br><br>
            </div>


<div class="modal fade" id="cancel_modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Cancel Policy</h4>
        </div>
        <form action="<?php echo base_url('admin/cancel_policy');?>" method="post">
        <div class="modal-body">
          <h3>Are you sure, you want to cancel the policy ?</h3>
          <input type="hidden" id="hidden_policyid" value="" name="hidden_policyid">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Yes</button>
          <button type="button" id="cancel_button" class="btn btn-default" data-dismiss="modal">No</button>
        </div>
        </form>
      </div>
      
    </div>
</div>

<div class="modal fade" id="active_modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Active Policy</h4>
        </div>
        <form action="<?php echo base_url('admin/active');?>" method="post">
        <div class="modal-body">
          <h3>Are you sure, you want to Activate the policy ?</h3>
          <input type="hidden" id="policyid" value="" name="policyid">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Yes</button>
          <button type="button" id="cancel_button" class="btn btn-default" data-dismiss="modal">No</button>
        </div>
        </form>
      </div>
      
    </div>
</div>


        </section>

    </div>

    <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">

    $(document).ready(function () {
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
                'url': base_url + 'admin/cancelpolicy_ajax',
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


 function cancel_popup(policyid){
    
    $('#hidden_policyid').val(policyid);
    $('#cancel_modal').modal();
 }

 function active_popup(policyid){
    $('#policyid').val(policyid);
    $('#active_modal').modal();
 }

</script>