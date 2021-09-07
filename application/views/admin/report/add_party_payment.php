<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Party Payment
      </h1>
<br><br>
<form method="post" action="<?php echo base_url('admin/submit_party_payment_form');?>">     
  <div class="row form-group">
  <div class="col-md-2">
    <label>Party Name :</label>
  </div>
  <div class="col-md-4">
    <select class="form-control" name="party_id" id="party_id">
      <option value="">Select</option>
      <?php
        if(!empty($party_lists)){
          foreach ($party_lists as $party_list) { ?>
            <option value="<?php echo $party_list['id'] ;?>"><?php echo $party_list['name'] ;?></option>
          
     <?php }}?>
    </select>
  </div> 
</div>
<div class="row form-group">
  <div class="col-md-2">
    <label>Cheque No / Transaction No :</label>
  </div>
  <div class="col-md-4">
    <input type="text" class="form-control" name="transaction_no" required style="text-transform:uppercase" />
  </div> 

</div> 

<div class="row form-group">
  <div class="col-md-2">
    <label>Bank IFSC Code :</label>
  </div>
  <div class="col-md-4">
    <input type="text" class="form-control" name="ifsc_code" required style="text-transform:uppercase" />
  </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <label>Bank Name :</label>
  </div>
  <div class="col-md-4">
    <input type="text" class="form-control" name="bank_name" required style="text-transform:uppercase" />
  </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <label>Bank Account No. :</label>
  </div>
  <div class="col-md-4">
    <input type="text" class="form-control" name="account_no" required style="text-transform:uppercase" />
  </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <label>Amount :</label>
  </div>
  <div class="col-md-4">
    <input type="text" class="form-control" name="amount" required style="text-transform:uppercase" />
  </div> 
</div>

<div class="row form-group">
  <div class="col-md-2">
    <label>Date :</label>
  </div>
  <div class="col-md-4">
    <input type="date" class="form-control" name="payment_date" required style="text-transform:uppercase" />
  </div> 
</div>



<div class="row form-group">
  <div class="col-md-2">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</div>
</form>      
</section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>