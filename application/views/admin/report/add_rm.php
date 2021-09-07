<style>
.error{color:red;}
</style>
<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add RM
      </h1>
      <?php if(!empty($error)){
          for($i=0;$i<count($error);$i++){
      ?>
      <br><p class="error"><?php echo $error[$i];?></p>
    <?php } }?>
<br><br>
<form method="post" action="<?php echo base_url('admin/submit_rm_form');?>">     
<div class="row form-group">
  <div class="col-md-2">
    <label>RM Name :</label>
  </div>
  <div class="col-md-4">
    <input type="text" class="form-control" name="rm_name" required style="text-transform:uppercase" />
  </div> 

</div> 

<div class="row form-group">
  <div class="col-md-2">
    <label>Mobile No :</label>
  </div>
  <div class="col-md-4">
    <input type="text" class="form-control" name="rm_contact_no" id="rm_contact_no" required maxlength="10"/>
    <span id="er_rm_contact_no" class="error"></span>
  </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <label>RM Email :</label>
  </div>
  <div class="col-md-4">
    <input type="email" class="form-control" name="rm_email" required />
  </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <label>Address :</label>
  </div>
  <div class="col-md-4">
    <textarea class="form-control" name="rm_address" required></textarea>
  </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <label>State :</label>
  </div>
  <div class="col-md-4">
    <select name="state" id="state" required="required">
      <option value="">--Select State--</option>
      <?php foreach($state as $state_val) { ?>
        <option value="<?php echo $state_val['id'];?>"><?php echo $state_val['name'];?></option>
      <?php }?>
    </select>  
    </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <label>City :</label>
  </div>
  <div class="col-md-4">
    <select name="city" id="city" required="required">
      <option value="">--Select City--</option>
    </select>
  </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <button type="submit" class="btn btn-primary" id="submitbtn">Submit</button>
  </div>
</div>
</form>      
</section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
 $(document).on("change", "#state",function () {
  //alert('bz')
  var state_id = $('#state').val();
  //alert(state_id);
  if(state_id!=""){
      $.ajax({
        url : base_url+'Report/getCitiesbyStateid',
        type : 'post',
        data : {state_id},
        dataType : 'JSON',
        success : function(response){
          console.log(response);
          $.each(response,function(key, value) 
          {
            $('#city').append('<option value=' + value.id + '>' + value.name + '</option>');
          });  
        }
    });
  }
  });

 $(document).on("blur","#rm_contact_no",function () {
    var mobNum = $(this).val();
        var filter = /^\d*(?:\.\d{1,2})?$/;

          if (filter.test(mobNum)) {
            if(mobNum.length!=10){
              //alert('mobile num is invalid');
              $('#er_rm_contact_no').text('Please Enter 10 digit Mobile number');
              $('#submitbtn').prop('disabled',true);
              $('#rm_contact_no').focus();
            }
            else{
             // alert('mobile num is invalid');
             $('#submitbtn').prop('disabled',false);
             $('#er_rm_contact_no').text('');
            }
          }
          else{
            $('#er_rm_contact_no').text('Please Enter 10 digit Mobile number');
            $('#submitbtn').prop('disabled',true);
            $('#rm_contact_no').focus();
          }  

 });

 $("#rm_contact_no").keypress(function (e) {
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
       
        $("#er_rm_contact_no").html("Please enter only digits").show();
               return false;
      }
      else{
        $("#er_rm_contact_no").html("");
      }
    });

}); 
</script>