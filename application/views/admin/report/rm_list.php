<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>RM List</h1>
<br><br>

<?php 
    $success_message = $this->session->flashdata('success');
    $admin_session = $this->session->userdata('admin_session');
if(!empty($success_message)){
    ?>
<div class="row form-group">
  <div class="col-md-4 form-group alert alert-warning" role="alert">
    <h4><?php echo isset($success_message) ? $success_message : '';?></h4>
  </div>
</div>

<?php } ?>

<br><br>
 
    <br><br>
    <?php if(($admin_session['admin_role_id'] == 1 && $admin_session['admin_role'] == 'admin_master') || ($admin_session['admin_role_id'] == 2 && $admin_session['admin_role'] == 'opreation_admin')) { ?> 
     <div class="row form-group">
     	<div class="col-md-2 pull-right">
     		 <button type="button" class="btn btn-info " data-toggle="modal" data-target="#myModal">Add RM</button>
     	</div>
     </div>
 <?php }?>
     <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="rm_datatable">
              <thead>
                <th>Sr.no</th>
                <th>Name</th>
                <th>Email</th>
              	<th>Mobile</th>
                <th>State</th>
                <th>Zone</th>
              	<th>Is Active</th>
                <th>Created_at</th>
              </thead>
              <tbody>
               
              </tbody>
          </table>
      </div>
      
</div>
      
    <br><br>
</div>    



 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add RM</h4>
        </div>
        <form action="<?php echo base_url('admin/submit_rm_data');?>" method="post">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              Name: <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="col-md-4">
              Email : <input type="text" class="form-control" name="email" id="email" required>
            </div>
            <div class="col-md-4">
              Mobile : <input type="number" class="form-control" name="mobile" id="mobile" required>
            </div>
          </div>
          <br><br>
          <div class="row">
            <div class="col-md-4">
              Help Desk : <select class="form-control" id="help_desk_id" name="help_desk_id" required>
                      <option value="">Select</option>
                <?php if(!empty($help_desk)){
                foreach ($help_desk as $help_d) {?>
                <option value="<?=$help_d['id']?>"><?=$help_d['name']?></option>
              <?php }}?>
              </select>
            </div>
            <div class="col-md-4">
              State : <select class="form-control" id="state_id" name="state_id" required>
                      <option value="">Select</option>
                <?php if(!empty($tvs_state)){
                foreach ($tvs_state as $state) {?>
                <option value="<?=$state['id']?>"><?=$state['name']?></option>
              <?php }}?>
              </select>
            </div>
            <div class="col-md-4">
              Zone : <select class="form-control zone_ids" id="zone_ids" name="zone_ids[]" required multiple>
                      <option value="">Select</option>
                <?php if(!empty($tvs_zone)){
                foreach ($tvs_zone as $zone) {?>
                <option value="<?=$zone['id']?>"><?=$zone['name']?></option>
              <?php }}?>
              </select>
              <span id="zone_er" style="color:red;"></span>
            </div>
          </div>
          <br><br>
          <div class="row">
            <div class="col-md-4 checkbox">
              <label><input type="checkbox" name="is_full_zone" id="is_full_zone" value="1">Is Full Zone?</label>
            </div>
            <div class="col-md-4" id="zone_state_div">
               
              <span id="zone_er" style="color:red;"></span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" >Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
  
$(document).ready(function(){

  table = $('#rm_datatable').DataTable({ 

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
              'url' : base_url+'Report/RMListAjax',
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
/*$('#zone_state_div').hide();
*/
$('.zone_ids').on('change',function(){
  // alert($(this).val());
  console.log($(this).val());
  $('#zone_er').text('');
  var zone_ids = $(this).val();
    if(zone_ids==""){
        $('#zone_er').text('Please Select Zone');
    }else{
        $.ajax({
                'url' : base_url+'Report/getZoneMaster',
                'type' : 'post',
                'data' : {zone_ids:zone_ids},
                'dataType' : 'html',
                success : function(response){
                      // console.log(response);
                    $('#zone_state_div').html(response);
                }
            });
    }
});



$('#is_full_zone').click(function() {
    if ($(this).is(':checked')) {
        $('#zone_state_id').prop('required',false);
        $('#zone_state_div').hide();
    }
    else
    {
      $('#zone_state_id').prop('required',true);
      $('#zone_state_div').show();
    }
});

  
});

 
</script>