<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Fetch Data BY Policy NO.
      </h1>
<br><br>
<!-- <form method="post" action="">      -->
<div class="row form-group">
  <div class="col-md-2">
    <label>POLICY No :</label>
  </div>
  <div class="col-md-4">
    <input type="text" class="form-control" name="search_policy_no" id="search_policy_no" placeholder="Policy NO./Engine No./Chassis NO." style="text-transform:uppercase" />
  </div> 
  <div class="col-md-6">
      <button type="button" id="fetch_data_btn" class="btn btn-primary ">Submit</button>
  </div>
</div>
<div class="row">
         <div class="col-md-6 pull-right" id="image_loader">
        <img src="<?php echo base_url();?>assets/images/loading5.gif" height="100" width="">
      </div>
    <br><br>
</div>
<br>

<div class="row">
<table class="table table-bordered table-responsive table-striped" >
    <thead>
      <th>Dealer Code</th>
      <th>Customer Name</th>
      <th>Contact No</th>
      <th>DOB</th>
      <th>Policy No</th>
      <th>Engine No</th>
      <th>Chassis No</th>
      <th>Vehicle Registration No</th>
      <th>Make</th>
      <th>Model</th>
      <th>Policy Status</th>
      <th>Plan Name</th>
      <th>PA IC</th>
      <th>Created Date</th>
      <th>Start Date</th>
      <th>End Date</th>
      <th>Cancellation Date</th>
    </thead>
    <tbody id="policy_data_table">
        
    </tbody>
</table>
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

    $("#fetch_data_btn").click(function() {
       var search_policy_no = $("#search_policy_no").val();
       $("#policy_data_table").html('');
         if(search_policy_no!="" || search_policy_no!=null){
              $.ajax({
                  url : base_url+'Report/fetchPolicydata',
                  type : 'POST',
                  data : {search_policy_no:search_policy_no},
                  dataType : "JSON",
                  success : function(response){
                      console.log(response);
                      $("#policy_data_table").html(response);
                  }
              });
         }
    });

  });
  
</script>