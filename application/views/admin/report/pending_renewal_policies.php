<?php
$admin_role=$this->session->userdata('admin_session')['admin_role'];
$ic_id=$this->session->userdata('admin_session')['ic_id'];

//print $admin_role;exit;
?>

<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>


<div class="wrapper">

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>RSA Pending Renewal Policies</h1>
            <br><br>
            <div class="row form-group">
                <div class="col-md-2">
                    <label>Start Date :</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" placeholder="Start Date" min="2018-08-01" max="">
                </div>
                <div class="col-md-2">
                    <label>End Date :</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date" min="2018-08-01" max="">
                </div>
                <div class="col-md-2">
                    <label>Dealer Code :</label>
                    <input type="text" class="form-control" name="dealer_code" id="dealer_code" placeholder="Dealer Code" >
                </div>
                <div class="col-md-2">
                    <label>Engine No :</label>
                    <input type="text" class="form-control" name="engine_no" id="engine_no" placeholder="Engine/Chassis/Policy No" >
                </div>
                <div class="col-md-2">
                    <label> &nbsp;</label>
                    <button type="button" class="form-control btn btn-primary" name="submit" id="filter_by_date" value="Submit">Submit</button>
                </div>
                
            </div>
            
            
            <h3 style="color: red;"><?php echo $this->session->flashdata('message');?></h3>
            <br><br>
            <!-- <form action="" method="post" id="checkddata_sms_form"> -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="example-col">
                        <table class="table table-bordered table-striped w-full" id="pending_renewal_datatable">
                            <thead>
                            <th><input type="checkbox" name="check_box[]" id="all_checkbox" class="allcheckbox_data" value="all"></th>
                            <th>Sr.no</th>
                            <th>Certificate No</th>
                            <th>Master Policy No</th> 
                            <th>RSA IC</th>
                            <th>PA IC</th>
                            <th>Engine no</th>
                            <th>Chassis No</th>
                            <th>Model Name</th>
                            <th>Dealer Code</th>
                            <th>Dealer Name</th>
                            <th>Customer Name</th>
                            <th>Plan Name</th>
                            <th>Created Date</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>PA Start Date</th>
                            <th>PA End Date</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Is SMS Send?</th>
                            </thead>
                            <tbody>
                 
                            </tbody>
                        </table>
                    </div>
                </div>

                <br><br>
            </div>

<div class="row">
    <div class="col-md-6 pull-right" id="image_loader">
        <img src="<?php echo base_url();?>assets/images/loading5.gif" height="100" width="">
    </div>
    <br><br>
</div>
<div class="row">
    <div class="col-md-2">
        <button type="button" class="form-control btn btn-primary" name="checkbox_sms_btn" id="checkbox_sms_btn">Send SMS</button>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="modal fade" id="changemobile_modal" role="dialog">
            <div class="modal-dialog">
            
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Change Mobile No to Send Message</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                      <div class="col-md-2 form-group">
                          Name : <span id="customer_name"></span>
                      </div> 
                      <div class="col-md-2 form-group">
                          Policy No : <span id="policy_no"></span>
                      </div>  
                    
                      <div class="col-md-6 form-group">
                          Mobile No. <input type="text" class="form-control" name="mobile_no" minlength="10" maxlength="10" id="mobile_no">
                          <input type="hidden" name="checked_value" id="checked_value" required>
                          <span style="color: red" id="mobile_er"></span>
                      </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="change_mobile_submit" >Submit</button>
                </div>
              </div>
              
            </div>
          </div>
    </div>
</div>

        </section>

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

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
var table;
$(document).ready(function () {
    
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var dealer_code = $('#dealer_code').val();
    var engine_no = $('#engine_no').val();
    $( "#all_checkbox" ).prop( "checked", false);
    callRenewalAjax(start_date,end_date,dealer_code,engine_no);
    $('#filter_by_date').on('click', function () {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var dealer_code = $('#dealer_code').val();
        var engine_no = $('#engine_no').val();
        $( "#all_checkbox" ).prop( "checked", false );
        callRenewalAjax(start_date,end_date,dealer_code,engine_no);
    });

      function callRenewalAjax(start_date,end_date,dealer_code,engine_no){
         table = $('#pending_renewal_datatable').DataTable({
                'paging': true,
                'destroy': true,

                "ajax": {
                    'url': base_url + 'Report/pending_renewal_policy_ajax',
                    "type": "POST",
                    "data": {'start_date': start_date, 'end_date': end_date , 'dealer_code':dealer_code,'engine_no':engine_no},
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
      }
    $("#checkbox_sms_btn").on('click',function(){
        $("#mobile_no").val('');
        var totalchecked = $('.checkbox_data:checked').length;
             console.log("total  "+totalchecked);
             if(parseInt(totalchecked) == 1){
                // console.log('ch  '+$('.checkbox_data:checked').val());
                checked_value = $('.checkbox_data:checked').val();
                $("#checked_value").val(checked_value);
                getcustomerMobileNo(checked_value);
                $("#changemobile_modal").modal();
             }else if(parseInt(totalchecked) > 1){
                    var checkd_values = new Array();
                        table.$('input[type="checkbox"]').each(function(){
                         
                            // If checkbox is checked
                            if(this.checked){
                                checkd_values.push($(this).val());
                            }
                        });
                    // console.log(checkd_values);
                     var policy_ids  = JSON.stringify(checkd_values);
                     
                    console.log(checkd_values);
                       CheckBoxAjax(policy_ids);
             }

        
            
    });

$("#change_mobile_submit").on('click',function(){
    var checked_value = $("#checked_value").val();
    var mobile_no = $("#mobile_no").val();
    var checkd_values=new Array();
    if(mobile_no!="" && checked_value!=""){
        checkd_values.push(checked_value);
        var policy_ids  = JSON.stringify(checkd_values);
        CheckBoxAjax(policy_ids,mobile_no);
        $("#changemobile_modal").modal('hide');
    }else{
        $("#mobile_er").text("Mobile No is Required");
        $("#changemobile_modal").modal('show');
    }
});

 });
// closing document ready///

function CheckBoxAjax(checkd_values,mobile_no=''){
    
     $.ajax({
            url: base_url+'admin/sendsms_checkedboxdata',
            data : {check_box_val:checkd_values,mobile:mobile_no},
            type : 'POST',
            dataType : 'json',
            success : function(response){
                if(response=="true"){
                    location.reload();
                }

            }
        });
}

function getcustomerMobileNo(checked_value){
    $.ajax({
            url: base_url+'Report/getcustomerMobileNo',
            data : {checked_value:checked_value},
            type : 'POST',
            dataType : 'json',
            success : function(response){
                if(response!=""){
                    $("#customer_name").text(response.customer_name);
                    $("#policy_no").text(response.sold_policy_no);
                    $("#mobile_no").val(response.mobile_no);
                    console.log(customer_name);
                }

            }
        });
}

$(document).on('click', '#all_checkbox', function () {
        var rows = table.rows({ 'search': 'applied' }).nodes();
       $('input[type="checkbox"]', rows).prop('checked', this.checked);
   
});
    

</script>