<style>
.error{color:red;}
</style>
<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Dealer
      </h1>
<br><br>
<form method="post" action="<?php echo base_url('admin/submit_dealler_form');?>">     
<div class="row form-group">
  <div class="col-md-2">
    <label>Dealer Name :</label>
  </div>
  <div class="col-md-4">
    <input type="text" class="form-control" name="dealer_name" required style="text-transform:uppercase" />
  </div> 

</div> 

<div class="row form-group">
  <div class="col-md-2">
    <label>Dealer Code :</label>
  </div>
  <div class="col-md-4">
    <input type="text" class="form-control" name="dealer_code" id="dealer_code" minlength="5" maxlength="6" required/>
    <span id="error-dealer_code" class="error"></span>
  </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <label>Sap Code :</label>
  </div>
  <div class="col-md-4">
    <input type="text" class="form-control" name="sap_ad_code" id="sap_ad_code" minlength="5" maxlength="6" required/>
    <span id="error-sap_ad_code" class="error"></span>
  </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <label>Ad. Name :</label>
  </div>
  <div class="col-md-4">
    <input type="text" class="form-control" name="ad_name" required style="text-transform:uppercase" />
  </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <label>Location :</label>
  </div>
  <div class="col-md-4">
    <input type="text" class="form-control" name="location" required style="text-transform:uppercase" />
  </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <label>RSA IC :</label>
  </div>
  <div class="col-md-4">
    <select class="form-control" name="rsa_ic" id="rsa_ic" required>
      <option value="">Select</option>
      <?php
        if(!empty($rsa_ic_data)){
          foreach ($rsa_ic_data as $rsa_ic) { ?>
            <option value="<?php echo $rsa_ic['id'] ;?>"><?php echo $rsa_ic['name'] ;?></option>
          
     <?php }}?>
    </select>
  </div> 
</div>


<div class="row form-group">
  <div class="col-md-2">
    <label>RM :</label>
  </div>
  <div class="col-md-4">
    <select class="form-control" name="rm" id="rm" required>
     <option value="">Select</option>
      <?php
        if(!empty($rmdata)){
          foreach ($rmdata as $rm_data) { ?>
            <option value="<?php echo $rm_data->id;?>" data-rmname="<?php echo $rm_data->name;?>"><?php echo $rm_data->name;?></option>
          
     <?php }}?>
    </select>
  </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <input type="hidden" name="rmname" id="rm_name">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</div>
</form>      
</section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
   
    $('#rm').change(function(){
      $('#rm_name').val($(this).find(':selected').data('rmname'));
    });

    $("#dealer_code").keypress(function (e) {
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
       
        $("#error-dealer_code").html("Please enter only digits").show();
               return false;
      }
      else{
        $("#error-dealer_code").html("");
      }
    });

    $("#sap_ad_code").keypress(function (e) {
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
       
        $("#error-sap_ad_code").html("Please enter only digits").show();
               return false;
      }
      else{
        $("#error-sap_ad_code").html("");
      }
    });

  });
</script>