<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
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

</script>