<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         Dealer Uploaded Documents
      </h1>

<br><br>

<?php $failed_message = $this->session->flashdata('failed');
    $success_message = $this->session->flashdata('success');
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
<br><br>
 <?php }?>
    <div class="row form-group">
          <div class="col-md-2">
              <label>Start Date :</label>
              <input type="date" class="form-control" name="start_date" id="start_date" placeholder="Start Date" min="2018-08-01" max="">
          </div>
          <div class="col-md-2">
              <label>End Date :</label>
              <input type="date" class="form-control" name="to_date" id="to_date" placeholder="End Date" min="2018-08-01" max="">
              <span id="errto_date" style="color: red;"></span>
          </div>
          <div class="col-md-2">
              <label> &nbsp;</label>
              <button type="button" class="form-control btn btn-primary" name="date_submit" id="date_submit" value="Submit">Submit</button>
          </div>
      </div>
      <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="uploaded_docs_datatable">
              <thead>
                <th>Sr.no</th>
                <th>RSA IC Name</th>
              	<th>Dealer Code</th>
              	<th>Dealer Name</th>
              	<th>Sap Code</th>
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
      
</section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript">
  
$(document).ready(function(){

  table = $('#uploaded_docs_datatable').DataTable({ 

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
              'url' : base_url+'Report/uploaded_docs_ajax',
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

$('#date_submit').click(function(){
var start_date =  $('#start_date').val();
var to_date = $('#to_date').val();
  er_date_status = false;

    if(start_date=='' || to_date==''){
      er_date_status = true;
      alert('Please Select the Date');
    }
    if(er_date_status== false){
      date1 = new Date(start_date);
      date2 = new Date(to_date);
     
        if(date1 <= date2){
              table1 = $('#uploaded_docs_datatable').DataTable({

                'paging': true,
                  'destroy': true,
                // Load data for the table's content from an Ajax source
                "ajax": {
                    'url' : base_url+'Report/uploaded_docs_ajax',
                    "type": "POST",
                    "dataType": "json",
                    "data" : {start_date : start_date ,to_date : to_date },
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
                //Set column definition initialisation properties.
                "columnDefs": [
                { 
                    "targets": [ 0 ], //first column / numbering column
                    "orderable": false, //set not orderable
                },
                ],

            });
        }else{
            $('#errto_date').text('End Date Should be greater Than Start Date');
        }

              
    }
});


});


function view_uploaded_document(id){
  if(id!=""){
      $.ajax({
          url : base_url+'Report/getUploadedDocument',
          type : 'post',
          data : {'dealer_id' : id},
          dataType : 'json',
          success : function(response){
            console.log(response);
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

</script>