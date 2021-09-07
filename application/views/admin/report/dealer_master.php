<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
    .colored_tbl { margin-bottom: 10px; }
    .colored_tbl th{ color: #fff; background-color: #7f868b; } 
    .colored_tbl td{background-color: #ffebe3; } 
    .colored_tbl>tbody>tr>td, .colored_tbl>tbody>tr>th, .colored_tbl>tfoot>tr>td, .colored_tbl>tfoot>tr>th, .colored_tbl>thead>tr>td, .colored_tbl>thead>tr>th { padding: 5px; }
    .table-bordered.colored_tbl>thead>tr>th, .table-bordered.colored_tbl>tbody>tr>th, .table-bordered.colored_tbl>tfoot>tr>th, .table-bordered.colored_tbl>thead>tr>td, .table-bordered.colored_tbl>tbody>tr>td, .table-bordered.colored_tbl>tfoot>tr>td { border-color: #7c2a0b; }
    .colored_tbl>caption+thead>tr:first-child>td, .colored_tbl>caption+thead>tr:first-child>th, .colored_tbl>colgroup+thead>tr:first-child>td, .colored_tbl>colgroup+thead>tr:first-child>th, .colored_tbl>thead:first-child>tr:first-child>td, .colored_tbl>thead:first-child>tr:first-child>th {border-top-width: 1px}
</style>

<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Dealer Master</h1>
<br><br>

<?php $failed_message = $this->session->flashdata('failed');
    $success_message = $this->session->flashdata('success');
     $admin_session = $this->session->userdata('admin_session');
if(!empty($failed_message)){
    ?>
<div class="row form-group">
  <div class="col-md-4 form-group alert alert-warning" role="alert">
    <h4><?php echo isset($failed_message) ? $failed_message : '';?></h4>
  </div>
</div>

<?php } 
 if(!empty($success_message)) {?>
<div class="row form-group">
    <div class="col-md-4 form-group alert alert-success" role="alert">
    <h4><?php echo isset($success_message) ? $success_message : '';?></h4>
  </div>
</div>
<?php }?>
<br><br>
 <div class="row form-group">
    <div class="col-md-2">
        <label>Start Date :</label>
        <input type="date" class="form-control" name="start_date" id="start_date" placeholder="Start Date" min="2018-08-01" max="">
    </div>
    <div class="col-md-2">
        <label>End Date :</label>
        <input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date" min="2018-08-01" max="">
        <span id="err_end_date" style="color:red;"></span>
    </div>
    <div class="col-md-2">
        <label> &nbsp;</label>
        <button type="button" class="form-control btn btn-primary" name="submit" id="date_filter" value="Submit">Submit</button>
    </div>
</div>
    <br><br>
    <?php if(($admin_session['admin_role_id'] == 1 && $admin_session['admin_role'] == 'admin_master') || ($admin_session['admin_role_id'] == 2 && $admin_session['admin_role'] == 'opreation_admin')) { ?> 
     <div class="row form-group">
      <div class="col-md-2 pull-right">
         <a href="<?php echo base_url('admin/add_dealer');?>" class="btn btn-primary">Add Dealers</a>
      </div>
     </div>
 <?php }?>
     <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="dealer_datatable">
              <thead>
                <th>Sr.no</th>
                <th>Dealer Id</th>
                <th>RSA IC Name</th>
                <th>Wallet Amount</th>
                <th>Dealer Code</th>
                <th>Sap Code</th>
                <th>GST NO</th>
                <th>Total Policy Count</th>
                <th>Dealer Name</th>
                <th>Ad. Name</th>
                <th>Contact No</th>
                <th>State</th>
                <th>Location</th>
                <th>Created At</th>
                <th>Login</th>
                <th>View</th>
              </thead>
              <tbody>
               
              </tbody>
          </table>
      </div>
      <div class="col-md-6 walllet_div">
        Total wallet : <p id="total_wallet_balance"></p>
      </div>
</div>
      
    <br><br>
</div>    

<div class="row">
  <div class="modal fade" id="dealer_doc_modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Uploaded Dealer Document</h4>
        </div>
        <div class="modal-body">
         Dealer Name :  <h4 id="dealer_name"></h4>
           <div class="row">
                  <div class="col-md-6" style="">
                 Agreement PDF :  <a name="agrrement_pdf" id="agrrement_pdf" target="_blank" download>Click here</a>
                  </div>
                  <div class="col-md-6" style="">
                 GST : <a name="gst_certificate" id="gst_certificate" target="_blank" download>Click here</a>
                  </div>
                 
          </div>
          <div class="row">
                  <div class="col-md-6" style="">
                 Pan Card :  <a name="pan_card" id="pan_card" target="_blank" download>Click here</a>
                  </div>
                  <div class="col-md-6" style="">
                 Cancel Cheque  : <a name="cancel_cheque" id="cancel_cheque" target="_blank" download>Click here</a>
                  </div>
                 
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
</div>

<div class="modal fade" id="assignic_modal" role="dialog">
    <div class="modal-dialog">
    <form action="<?php echo base_url('admin/submit_dms_ic_data');?>" method="post">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">DMS IC And PA IC Mapping</h4>
        </div>
    <div class="modal-body" id="ic_list_div">
      
    </div>
  </div>
  </form>
</div>
 </div> 

 <div class="modal fade" id="gst_complaint_modal" role="dialog">
    <div class="modal-dialog">
    <form action="<?php echo base_url('Dealer_Approve/updateGSTComplaintData');?>" method="post">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update GST Complaint</h4>
          <b>Dealer Code: </b><span id="gst_dealer_code"></span>
        </div>
          <div class="modal-body" id="">
            <div class="row">
              <div class="col-md-6">
                <h4>Is GST Compliant Dealer ?</h4>
              </div>
              <div class="col-md-6 form-group">
                <select id="gst_complaint_val" name="gst_complaint_val" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="sap_ad_code" id="sap_ad_code" value="">
          <button type="submit" class="btn btn-Success" onclick="return confirm('Are you sure do you want to change the GST Complaint ?')" >Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
  </div>
  </form>
</div>
 </div>  

 <div class="modal fade" id="dealer_info" role="dialog">
    <div class="modal-dialog modal-lg">
    <form action="" method="post">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <a class="btn btn-primary pull-center" onclick="tableToExcel('dealer_details', 'Dealer Report','dealer_report.xls')" >Export to Excel</a>
          <h4 class="modal-title">DEALER DASH BOARD</h4>
        </div>
    <div class="modal-body" id="dealer_popup_div">
        

    </div>
    <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
  </form>
</div>
 </div>  

 <div class="modal fade" id="exclusive_ic_modal" role="dialog">
    <div class="modal-dialog modal-lg">
    <form action="<?php echo base_url('admin/exclusive_ic_submit');?>" method="post">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Assign Exclusive IC</h4>
        </div>
    <div class="modal-body" id="exclusive_ic_popup">
        

    </div>
    <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>&nbsp;
          <button type="submit" class="btn btn-success" >Submit</button>
    </div>
  </div>
  </form>
</div>
 </div>  

   <div class="modal fade" id="edit_dealer_modal" role="dialog">
    <div class="modal-dialog modal-lg">
    <form action="<?php echo base_url('Report/SubmitupdateDealerData');?>" id="dealerdata" method="post">
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
            Pincode : <input class="form-control" type="text" id="pin" name="pin" maxlength="6" placeholder="Pincode" required>
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
         <!--<span id="error-pa_ic_id" style="color: red"></span>-->
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
             "buttons": ['excel', 'csv', 'pdf', 'print'], 
          // Load data for the table's content from an Ajax source
          "ajax": {
              'url' : base_url+'admin/dealer_ajax',
              "type": "POST",
              "dataType": "json",
              "dataSrc": function (jsonData) {
                console.log(jsonData);
                    if((jsonData.tot_wallet == "") || (jsonData.tot_wallet == null) || (jsonData.tot_wallet <= 0)){
                          $('#total_wallet_balance').text('');
                    }else{
                          $('#total_wallet_balance').text(jsonData.tot_wallet);
                    }
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
  $('#date_filter').on('click', function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            table1 = $('#dealer_datatable').DataTable({
                'paging': true,
                'destroy': true,
                "ajax": {
                    'url': base_url + 'admin/dealer_ajax',
                    "type": "POST",
                    "data": {'start_date': start_date, 'end_date': end_date},
                    "dataType": "json",
                    "dataSrc": function (jsonData) {
                        return jsonData.data;
                    }
                },

                "scrollX": true,
                "processing": true,
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': false,
                'dom': 'Bfrtip',
                "buttons": ['excel', 'csv', 'pdf', 'print'],
                // Load data for the table's content from an Ajax source

                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        "targets": [0], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ],

            });

        });

$("#pin").focusout(function(){
    var length = $(this).val().length;
    var pin = $(this).val();
    //console.log("Length "+length);
    if(length === 6){
      getStateCityByPin(pin);
        
    }
    if(length>0 && length<6){
      $("#error-pin").html("Please enter six digit pincode").show();
      $('#pin').focus();
    }
});

$("#pin").keypress(function (e) {
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
       
        $("#error-pin").html("Please enter only digits").show();
               return false;
      }
      else{
        $("#error-pin").html("");
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
function view_document(id){
  if(id!=""){
      $.ajax({
          url : base_url+'Report/getDealerDocument',
          type : 'post',
          data : {'dealer_id' : id},
          dataType : 'json',
          success : function(response){
            // console.log(response);
            $('#dealer_name').text(response.dealer_name);

                if((response.gst_certificate == null) || (response.gst_certificate == "")){
                  $('#gst_certificate').text('NOT UPLOADED');
                }else{
                  $("#gst_certificate").attr("href", base_url+'uploads/dealer_docs/'+response.gst_certificate);
                  $('#gst_certificate').text('Click Here');
                }

                 if((response.agreement == null) || (response.agreement == "")){
                  $('#agrrement_pdf').text('NOT UPLOADED');
                }else{
                  $("#agrrement_pdf").attr("href", base_url+'uploads/dealer_docs/'+response.agreement);
                  $('#agrrement_pdf').text('Click Here');
                }
                 if((response.pan_card == null) || (response.pan_card == "")){
                  $('#pan_card').text('NOT UPLOADED');
                }else{
                  $("#pan_card").attr("href", base_url+'uploads/dealer_docs/'+response.pan_card);
                  $('#pan_card').text('Click Here');
                }
                 if((response.cancel_cheque == null) || (response.cancel_cheque == "")){
                  $('#cancel_cheque').text('NOT UPLOADED');
                }else{
                  $("#cancel_cheque").attr("href", base_url+'uploads/dealer_docs/'+response.cancel_cheque);
                  $('#cancel_cheque').text('Click Here');
                }

                 
          }
      });
        $('#dealer_doc_modal').modal();
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

function AssignICView(dealer_id){
  if(dealer_id!=""){
    $.ajax({
        url : base_url+'Report/getDealerInfo',
        type : 'post',
        data : {dealer_id:dealer_id},
        // dataType : '',
        success : function(response){
              $('#ic_list_div').html(response);
                $('#assignic_modal').modal();

        }
    });
  }
}

function AssignExclusiveIC(dealer_id){
  // alert(dealer_id);
  if(dealer_id!=""){
      $.ajax({
            url : base_url+'Report/ExclusiveICView',
            type :'post',
            data : {dealer_id:dealer_id},
            success : function(response){
              $('#exclusive_ic_popup').html(response);
              $('#exclusive_ic_modal').modal();
              // console.log(response);
            }
      });
  }
}


function DealerInfo(dealer_id){
  
  // console.log(dealer_id);
  if(dealer_id!=""){
      $.ajax({
          url : base_url+'Report/getDealerDetailView',
          type : 'post',
          data : {dealer_id:dealer_id},
          success : function(response){
            $('#dealer_popup_div').html(response);
          }
      });
      $('#dealer_info').modal();
  }
}

function DealerTarget(dealer_id){
    if(dealer_id!=""){
      $.ajax({
          url : base_url+'Report/getDealerDocument',
          type : 'post',
          data : {dealer_id:dealer_id},
          success : function(response){
            // $('#dealer_popup_div').html(response);
          }
      });
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


function updateGSTComplaint(dealer_id){

  // alert($dealer_id);
    if(dealer_id!=""){
        $.ajax({
              url : base_url+'Report/getDealerDetail',
              data : {dealer_id:dealer_id},
              dataType : 'JSON',
              type : 'POST',
              success : function(response){
                console.log(response);
                var sap_ad_code = response.sap_ad_code;
                  if(sap_ad_code=="" || sap_ad_code===null){
                        alert('Nil');
                  }else{
                      $("#gst_dealer_code").text(sap_ad_code);
                      var gst_complnt =response.is_gst_compliant;
                      $("#gst_complaint_val").val(gst_complnt);
                      $("#sap_ad_code").val(sap_ad_code);
                      $("#gst_complaint_modal").modal();
                  }
              }
        });
        
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