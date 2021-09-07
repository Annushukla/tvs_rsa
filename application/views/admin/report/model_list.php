<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Model List</h1>
<br><br>

<?php
     $success_message = $this->session->flashdata('success');
     $admin_session = $this->session->userdata('admin_session');
 if(!empty($success_message)) {?>
<div class="row form-group">
    <div class="col-md-4 form-group alert alert-success" role="alert">
    <h4><?php echo isset($success_message) ? $success_message : '';?></h4>
  </div>
</div>
<?php }?>

    <br><br>
    <?php if(($admin_session['admin_role_id'] == 1 && $admin_session['admin_role'] == 'admin_master') || ($admin_session['admin_role_id'] == 2 && $admin_session['admin_role'] == 'opreation_admin')) { ?> 
     <div class="row form-group">
     	<div class="col-md-2 pull-right">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_model_btn">Add Model</button>
     	</div>
     </div>
 <?php }?>
     <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="modellist_tabl">
              <thead>
                <th>Sr.no</th>
                <th>Model Name</th>
                <th>Model</th>
                <th>Code</th>
                <th>OEM</th>
                <th>OEM Subtype</th>
                <th>Created Date</th>

              </thead>
              <tbody>
               
              </tbody>
          </table>
      </div>
      
</div>
      
    <br><br>
</div>    
</div>    


<div class="row">
   <!-- Modal -->
  <div class="modal fade" id="add_model_btn" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Model</h4>
        </div>
        <form action="<?php echo base_url('admin/submit_model_data');?>" method="post">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              Model Name : <input type="text" class="form-control" name="model_name" id="model_name" required>
            </div>
            <div class="col-md-4">
              Model : <input type="text" class="form-control" name="model" id="model" required>
            </div>
            <div class="col-md-4">
              Model Code : <input type="text" class="form-control" name="model_code" id="model_code">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success" >Submit</button>
        </div>
      </div>
      
    </div>
  </div>
</div>
   
</section>

</div>

  <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript">
  
$(document).ready(function(){

  table = $('#modellist_tabl').DataTable({ 

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
              'url' : base_url+'Report/ModelListAjax',
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