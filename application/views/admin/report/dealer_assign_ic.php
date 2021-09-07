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
         Assign Dealer IC
      </h1>

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
 <!-- div class="row form-group">
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
</div> -->
    <br><br>
    
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="assigndealer_tbl">
              <thead>
                <th>Sr.no</th>
                <th>Dealer Code</th>
                <th>Dealer Name</th>
                <th>Sap Code</th>
                <th>Ad. Name</th>
                <th>Contact No</th>
                <th>State</th>
                <th>Location</th>
                <th>Created At</th>
                <th>Login</th>
                <th>Action</th>

              </thead>
              <tbody>
               
              </tbody>
          </table>
      </div>
      
</div>
      
</div> 

<div class="modal fade" id="assign_modal" role="dialog">
    <div class="modal-dialog">
    <form action="<?php echo base_url('admin/confirm_assign_ic');?>" method="post">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Assign IC</h4>
        </div>
        <div class="modal-body">
          <p id="p_text"></p>
          <input type="hidden" name="sap_code" id="sap_code">
          <input type="hidden" name="assign_ic_id" id="assign_ic_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Assign</button>
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

  table = $('#assigndealer_tbl').DataTable({ 

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
              'url' : base_url+'Report/DealerICAjax',
              "type": "POST",
              "dataType": "json",
              "dataSrc": function (jsonData) {
                console.log(jsonData);
                    
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
  // $('#date_filter').on('click', function () {
  //           var start_date = $('#start_date').val();
  //           var end_date = $('#end_date').val();

  //           table1 = $('#dealer_datatable').DataTable({
  //               'paging': true,
  //               'destroy': true,
  //               "ajax": {
  //                   'url': base_url + 'admin/dealer_ajax',
  //                   "type": "POST",
  //                   "data": {'start_date': start_date, 'end_date': end_date},
  //                   "dataType": "json",
  //                   "dataSrc": function (jsonData) {
  //                       return jsonData.data;
  //                   }
  //               },

  //               "scrollX": true,
  //               "processing": true,
  //               'paging': true,
  //               'lengthChange': true,
  //               'searching': true,
  //               'ordering': true,
  //               'info': true,
  //               'autoWidth': false,
  //               'dom': 'Bfrtip',
  //               "buttons": ['excel', 'csv', 'pdf', 'print'],
  //               // Load data for the table's content from an Ajax source

  //               //Set column definition initialisation properties.
  //               "columnDefs": [
  //                   {
  //                       "targets": [0], //first column / numbering column
  //                       "orderable": false, //set not orderable
  //                   },
  //               ],

  //           });

  //       });



$(document).on('change',".assign_ic",function(){
    var ic_val = $(this).val();
    var sap_code = $(this).data('sap_code');
    var selected_ic = $(this).find('option:selected').text() ;
    $('#p_text').text('Are You Sure,do you want to Assign '+selected_ic+' to '+sap_code+' ?');
    $('#assign_ic_id').val(ic_val);
    $('#sap_code').val(sap_code);
    $('#assign_modal').modal();

});

$('.close_btn').on('click',function(){
        $('select').each( function() {
        $(this).val( $(this).find("option[selected]").val() );
        });
    });

});

 
</script>