<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<div class="wrapper">

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>RR310 RENEW POLICY</h1>
        <br><br>
    <h3 style="color: red;"><?php echo $this->session->flashdata('message');?></h3>
    <br><br>
    <div class="row">
        <div class="col-lg-12">
            <div class="example-col">
                <table class="table table-bordered table-striped w-full" id="rr310_renew_policy">
                    <thead>
                    <th>Sr.no</th>
                    <th>Policy No</th>
                    <th>Engine No</th>
                    <th>Chassis No</th>
                    <th>Model Name</th>
                    <th>Dealer Code</th>
                    <th>Dealer Name</th>
                    <th>Customer Name</th>
                    <th>Plan Name</th>
                    <th>Basic Premium</th>
                    <th>Gst Amount</th>
                    <th>Total Premium</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Created Date</th>
                    <!-- <th>Download Pdf</th> -->
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

</section>

      <!-- /.content -->
   
</div>
    <!-- /.content-wrapper -->
   
    <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/tvs_admin.js');?>"></script>
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