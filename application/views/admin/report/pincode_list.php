<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Pincode List</h1>
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
      <div class="col-md-6">
          <label>Enter Pincode : </label>
          <input type="text" id="pincode" name="pincode" maxlength="6">
          <button type="button" class="btn btn-info" name="submitbtn" id="submitbtn">Search</button>   
      </div>
      <div class="col-md-2 pull-right">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_pincode">Add Pincode</button>
      </div>
      
   </div>
 <?php }?>
     <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          
      </div>
      
</div>
    <div class="col-md-6 pull-right" id="image_loader">
        <img src="<?php echo base_url();?>assets/images/loading5.gif" height="100" width="">
    </div>
    <br><br>
</div>    
</div>    


<div class="row">
   <!-- Modal -->
  <div class="modal fade" id="add_pincode" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Pincode</h4>
        </div>
        <form action="<?php echo base_url('admin/submit_pincode_data');?>" method="post">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              State Name : <select class="form-control state_id" name="state_id" id="state_id" required="">
                            <option value="">Select</option>
                            <?php if(!empty($states)){
                                foreach ($states as $state) { ?>
                                  <option value="<?=$state['state_id']?>"><?=strtoupper($state['state_name'])?></option>
                            <?php } }?>
                          </select>
            </div>
            <div class="col-md-4">
              City Name : <select class="form-control" name="city_id" id="city_id" required="">
                          <option>Select</option>
                          </select>
            </div>
            <div class="col-md-4">
              Pincode Code : <input type="number" class="form-control" name="pincode" id="pincode" required="">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success" >Submit</button>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
   <!-- Modal -->
  <div class="modal fade" id="edit_pincode_modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Pincode</h4>
        </div>
        <form action="<?php echo base_url('admin/edit_pincode_data');?>" method="post">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              State Name : <p id="state_name"><b></b></p>
            </div>
            <div class="col-md-4">
              City Name : <select class="form-control" name="edit_city_id" id="edit_city_id" required="">
                          <option>Select</option>
                          </select>
                          <!-- <input type="hidden" id="selected_city_id" name="selected_city_id"> -->
            </div>
            <div class="col-md-4">
              Pincode Code : <p id="edit_pincode"><b></b></p>
              <input type="hidden" id="pincode_id" name="pincode_id">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success" >Submit</button>
        </div>
      </form>
      </div>
      
    </div>
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
  $("#state_id").select2({
        allowClear:true,
        placeholder: 'Position'
      });

  $(document).on('click','#submitbtn',function(){      
    var pincode=$('#pincode').val();
    
  $('.example-col').html('<table class="table table-bordered table-striped w-full" id="pincode_datatable"><thead><th>Sr.no</th><th>State</th><th>City</th><th>Pincode</th><th>Action</th></thead><tbody></tbody></table>');

  table = $('#pincode_datatable').DataTable({ 

             "scrollX": true,
            "processing": true,
            'paging'      : true,
            'destroy': true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'dom': 'Bfrtip',
             "buttons": ['excel', 'csv', 'pdf', 'print'], 
          // Load data for the table's content from an Ajax source
          "ajax": {
              'url' : base_url+'Report/PincodeListAjax',
              "type": "POST",
              "dataType": "json",
              "data":{pincode:pincode},
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



  $("#pincode").keypress(function (e) {
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          return false;
      }
  });

$('.state_id').on('change',function(){
  var state_val = $(this).val();
  $('#city_id').html('');
// alert(state_val);
    $.ajax({
        url : base_url+'Report/GetCityList',
        type : 'POST',
        data : {state_val:state_val},
        dataType : 'JSON',
        success : function(response){
                console.log(response);
                $("#city_id").select2({
                  allowClear:true,
                  placeholder: 'Position'
                });   
                $('#city_id').html(response.html);
        }

    })

});  

});

function EditPincode(pincode_id){
  var pin_code = "";
  var pin_code = $("#p_"+pincode_id).attr("data-pin_code");
console.log(pin_code);

  if(pincode_id!="" && pin_code!=""){
      $.ajax({
          url : base_url+"Report/GetCityList",
          type : "POST",
          data :{pincode_id : pincode_id,pin_code } ,
          dataType : "JSON",
          success : function(response){
              console.log(response.state_name);
              $("#edit_city_id").select2({
                  allowClear:true,
                  placeholder: 'Position'
                });   
                $('#edit_city_id').html('');
                $('#edit_city_id').html(response.html);
                $("#state_name").text(response.state_name);
                $("#edit_pincode").text(response.pincode);
                $("#pincode_id").val(pincode_id);
                
          }
      });
      $("#edit_pincode_modal").modal();
  }
}

// function EditPincode(pincode){
//   // alert();
//   var state = $("#p_"+pincode).attr("data-state");
//   var city = $("#p_"+pincode).attr("data-city");
//   var selected_city_id = $("#p_"+pincode).attr("data-city_id");
//   console.log(selected_city_id);
//   var state_val = $("#p_"+pincode).attr("data-state_id");
//   if(pincode!="" && state!="" && city!=""){
//        $.ajax({
//         url : base_url+'Report/GetCityList',
//         type : 'POST',
//         data : {state_val:state_val,selected_city_id:selected_city_id},
//         dataType : 'JSON',
//         success : function(response){
//                 // console.log(response);
//                 $("#edit_city_id").select2({
//                   allowClear:true,
//                   placeholder: 'Position'
//                 });   
//                 $('#edit_city_id').html('');
//                 $('#edit_city_id').html(response);
//         }

//     });
//       $("#state_name").text(state);
//       $("#edit_city_id").text(city);
//       $("#selected_city_id").val(selected_city_id);
//       $("#edit_pincode").text(pincode);
//       $("#selected_pincode").val(pincode);
//       $("#edit_pincode_modal").modal();
//   }
// }


</script>