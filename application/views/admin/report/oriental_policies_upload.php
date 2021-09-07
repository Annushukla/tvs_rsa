<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>
<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Update Oriental Master Policies</h1>
      <br/>
<?php  
     $success_message = $this->session->flashdata('success_message');
 if(!empty($success_message)) {?>
<div class="row form-group">
    <div class="col-md-4 form-group alert alert-success" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">X</a>
    <h4><?php echo isset($success_message) ? $success_message : '';?></h4>
  </div>
</div>
<?php }?>
    <form method="post" action="<?php echo base_url('admin/submit_oriental_policies_form');?>" enctype='multipart/form-data'>     
<div class="row form-group">
  <div class="col-md-2">
    <label>Upload Data :</label>
  </div>
  <div class="col-md-4">
    <input type="file" class="form-control" name="oriental_policies_file" id="oriental_policies_file" required />
  </div>
  <div class="col-md-4">
    <a href="<?php echo base_url();?>uploads/oriental_master_policy/oriental_masterpolicy.csv" class="btn btn-primary">Download Sample CSV</a>
  </div> 

</div> 

<div class="row form-group">
  <div class="col-md-2">
    <button type="submit" name="upload" class="btn btn-primary">Submit</button>
  </div>
</div>
</form>
     <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="oriental_master_policy_datatable">
              <thead>
                <th>Sr.no</th>
                <th>Master Policy No.</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Created At</th>
              </thead>
              <tbody>
               
              </tbody>
          </table>
      </div>
      
</div>
    <div class="col-md-6 pull-right" id="image_loader">
        <img src="<?php echo base_url();?>assets/images/loading5.gif" height="100" width="">
    </div>
    <br><br>
</div>    
</div>    
</section>
</div>
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
$(document).ready(function(){
  table = $('#oriental_master_policy_datatable').DataTable({ 

             "scrollX": true,
            "processing": true,
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'dom': 'Bfrtip',
             "buttons": ['excel', 'csv', 'pdf', 'print'], 
          // Load data for the table's content from an Ajax source
          "ajax": {
              'url' : base_url+'Report/OrientalMasterPoliciesListAjax',
              "type": "POST",
              "dataType": "json",
              "dataSrc": function (jsonData) {
                // console.log(jsonData);
                    
                      return jsonData.data;
              }
          },
   
          //Set column definition initialisation properties.
          "columnDefs": [
          { 
              "targets": [ 0 ], //first column / numbering column
              "orderable": false, //set not orderable
          },
          ],
   
      });
});
</script>