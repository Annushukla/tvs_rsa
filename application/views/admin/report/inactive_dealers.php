<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         Dealer Master
      </h1>
     <br><br>
     <div class="row">
       <h3 style="color:red;"><?php echo $this->session->flashdata('message');?></h3>
     </div>
     <br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="dealer_datatable">
              <thead>
              	<th>Sr.no</th>
              	<th>Dealer Code</th>
              	<th>Dealer Name</th>
              	<th>Sap Code</th>
              	<th>Ad. Name</th>
              	<th>Contact No</th>
                <th>State</th>
                <th>Location</th>
              	<th>Login</th>
              	<th>Created At</th>
              	<!-- <th></th> -->

              </thead>
              <tbody>
              

              </tbody>
          </table>
      </div>
  </div>
      
    <br><br>
</div>    
      

         <div class="modal fade" id="edit_dealer_modal" role="dialog">
    <div class="modal-dialog modal-lg">
    <form action="<?php echo base_url('Report/SubmitupdateDealerData');?>" method="post" id="dealerdata">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h2 class="modal-title">Edit Dealer Data</h2>
          <h2>Dealer code : <span id="dealer_code"></span> </h2>
        </div>
    <div class="modal-body" id="dealer_edit_body">
        <div class="row form-group">
          <div class="col-md-6">
            Full Name : <input class="form-control" type="text" id="dealer_full_name" name="dealer_full_name" placeholder="Dealer Full Name" required>
            <span id="error-dealer_full_name" style="color: red"></span>
          </div>
          <div class="col-md-6">
            Email : <input class="form-control" type="text" id="email" name="email" placeholder="EMAIL ID" required>
            <span id="error-email" style="color: red"></span>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-md-6">
            Dealership Name : <input class="form-control" type="text" id="company_name" name="company_name" placeholder="Dealership Name" required>
            <span id="error-company_name" style="color: red"></span>
          </div>
          <div class="col-md-6">
            Company Type : <select id="company_type" name="company_type" class="form-control company_type" data-message="Company Type" required>
                            <option value="">Select Option</option>
                            <?php foreach($company_type as $company) {?>
                               <option value="<?= $company['id'] ?>"><?= $company['type_name']?></option>
                           <?php }?>
                           </select>
            <span id="error-company_type" style="color: red"></span>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-md-6">
            Mobile No : <input class="form-control" type="number" id="mobile_no" name="mobile_no" placeholder="Mobile Number" required>
            <span id="error-mobile_no" style="color: red"></span>
          </div>
          <div class="col-md-6">
            Landline No : <input class="form-control" type="number" id="phone_no" name="phone_no" placeholder="Phone Number">
            <span id="error-phone_no" style="color: red"></span>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-md-6">
            Tin No : <input class="form-control" type="text" id="tin_no" name="tin_no" placeholder="Tin Number">
            <span id="error-tin_no" style="color: red"></span>
          </div>
          <div class="col-md-6">
            GST No : <input class="form-control" type="text" id="gst_no" name="gst_no" placeholder="GST Number" required>
            <span id="error-gst_no" style="color: red"></span>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-md-6">
            Adhar No : <input class="form-control" type="text" id="aadhar_no" name="aadhar_no" placeholder="Adhar Number">
            <span id="error-aadhar_no" style="color: red"></span>
          </div>
          <div class="col-md-6">
            Pan No : <input class="form-control" type="text" id="pan_no" name="pan_no" placeholder="PAN Number" required>
            <span id="error-pan_no" style="color: red"></span>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-md-6">
            Address 1 : <input class="form-control" type="text" id="dealer_addr1" name="dealer_addr1" placeholder="Address 1" required>
            <span id="error-dealer_addr1" style="color: red"></span>
          </div>
          <div class="col-md-6">
            Address 2 : <input class="form-control" type="text" id="dealer_addr2" name="dealer_addr2" placeholder="Address 2" required>
            <span id="error-dealer_addr2" style="color: red"></span>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-md-4">
            Pincode : <input class="form-control" type="text" id="pin" name="pin" placeholder="Pincode" required>
            <span id="error-pin" style="color: red"></span>
          </div>
          <div class="col-md-4">
            State : <input class="form-control" type="text" id="state" name="state" placeholder="State" required>
            <span id="error-state" style="color: red"></span>
          </div>
          <div class="col-md-4">
            City : <input class="form-control" type="text" id="city" name="city" placeholder="City" required>
            <span id="error-city" style="color: red"></span>
          </div>
        </div>
<h2 class="modal-title">Account Data</h2>
    <div class="row form-group">
          <div class="col-md-6">
            IFSC Code : <input class="form-control" type="text" id="ifsc_code" name="ifsc_code" placeholder="IFSC Code" required>
            <span id="error-ifsc_code" style="color: red"></span>
          </div>
          <div class="col-md-6">
            Bank Name : <input class="form-control" type="text" id="bank_name" name="bank_name" placeholder="Bank Name" required>
            <span id="error-bank_name" style="color: red"></span>
          </div>
        </div>
    <div class="row form-group">
        <div class="col-md-6">
          Account Holder Name : <input class="form-control" type="text" id="acc_holder_name" name="acc_holder_name" placeholder="Account Holder Name" required>
          <span id="error-acc_holder_name" style="color: red"></span>
        </div>
        <div class="col-md-6">
          Account No : <input class="form-control" type="text" id="account_no" name="account_no" placeholder="Account Number" required>
          <span id="error-account_no" style="color: red"></span>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-4">
          Branch Address : <input class="form-control" type="text" id="branch_address" name="branch_address" placeholder="Branch Address" required>
          <span id="error-branch_address" style="color: red"></span>
        </div>
        <div class="col-md-4">
          Account Type : <select id="account_type" name="account_type" class="form-control cust_info_field account_type" data-message="Account Type" required>
                         <option value="">Select Option</option>
                         <option value="saving">Saving</option>
                         <option value="current">Current</option>
                         <option value="cc">CC</option>
                     </select>
          <span id="error-account_type" style="color: red"></span>
        </div>
         <div class="col-md-4">
          Insurance Company : 
          <select disabled id="pa_ic_id" name="pa_ic_id" class="form-control cust_info_field pa_ic_id" data-message="PA IC" required>
             <option value="">Select Option</option>
             <?php if(!empty($pa_ics)){
              foreach ($pa_ics as $pa_ic) { ?>
             <option value="<?php echo $pa_ic['id'];?>"><?php echo $pa_ic['name']?></option>
             <?php } }?>
         </select>
        </div>
    </div>
  </div>
    <div class="modal-footer">
        <input type="hidden" id="edit_dealer_id" name="edit_dealer_id">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>&nbsp;
        <button type="button" class="btn btn-success" id="submitbtn">Submit</button>
    </div>
</div>
  </form>
</div>
 </div>  

</section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript">
  
$(document).ready(function(){

   

  table = $('#dealer_datatable').DataTable({ 

             "scrollX": true,
            "processing": true,
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'dom': 'Bfrtip',
             "buttons": ['excel'], 
          // Load data for the table's content from an Ajax source
          "ajax": {
              'url' : base_url+'admin/inactive_dealer_ajax',
              "type": "POST",
              "dataType": "json",
              "dataSrc": function (jsonData) {
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

  $("#pin").focusout(function(){
    var length = $(this).val().length;
    var pin = $(this).val();
    if(length === 6){
      getStateCityByPin(pin);
        
    }
});

});
function login(dealer_id){
      if(dealer_id !=""){
             $.ajax({
              url : base_url+'Report/loginAsDealer',
              type : 'POST',
              data : {'dealer_id' : dealer_id},
              dataType : 'JSON',
              success : function(response){
                if(response.status == 'true'){
                 window.open(base_url+'dashboard', '_blank');
                }
              }
            });
    }
  }

function ResetPassword(sap_code){
  // alert(sap_code);
  var r=confirm("Are you sure, do you want to reset the password of "+sap_code+" ?")
     if (r==true)
     {
        var url = base_url+'admin/ResetDealerpassword/'+sap_code;
        window.location.href = url;
     }
    
}

function EditDealerData(dealer_id){
  if(dealer_id!=""){
      $.ajax({
        url : base_url+'Report/getDealerDetail',
        type : 'post',
        data : {dealer_id},
        dataType : 'JSON',
        success : function(response){
          // console.log(response);
          $("#dealer_code").text(response.sap_ad_code);
          $("#dealer_full_name").val(response.ad_name);
          $("#email").val(response.email);
          $("#company_name").val(response.dealer_name);
          $("#phone_no").val(response.landline);
          $("#mobile_no").val(response.mobile);
          $("#tin_no").val(response.tin_no);
          $("#pan_no").val(response.pan_no);
          $("#aadhar_no").val(response.aadhar_no);
          $("#gst_no").val(response.gst_no);
          $("#dealer_addr1").val(response.add1);
          $("#dealer_addr2").val(response.add2);
          $("#pin").val(response.pin_code);
          $("#state").val(response.state);
          $("#city").val(response.location);
          $("#account_type").val(response.account_type);
          $("#account_no").val(response.banck_acc_no);
          $("#acc_holder_name").val(response.banck_acc_name);
          $("#bank_name").val(response.banck_acc_name);
          $("#branch_address").val(response.branch_address);
          $("#ifsc_code").val(response.banck_ifsc_code);
          $("#company_type").val(response.company_type_id);
          $("#pa_ic_id").val(response.pa_ic_id);
          $("#edit_dealer_id").val(response.tvs_dealer_id);
        }
      });

      $('#edit_dealer_modal').modal();
  }

}




    function getStateCityByPin(pin){
      // alert(pin);
    $.ajax({
            url : base_url + 'Report/fetchStateCityNames',
            data : {pin:pin},
            dataType : 'JSON',
            type : 'POST',
            success:function(response){
                if(response){
                    $("#state").val(response.state);
                    $("#city").val(response.city);
                    // $("#state_id").val(response.state_id);
                    // $("#city_id").val(response.city_id);
                }
            }
        });
}

function checkIsExist(checkvar) {
    if (checkvar === null || checkvar === "" || checkvar === "null" || checkvar === undefined || checkvar === 0 || checkvar === false) {
        return false;
    } else {
        return true;
    }
}

</script>