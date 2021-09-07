<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <h1>Sapphire Till 30 Aug </h1>
      <br><br>
            <div class="row form-group">
                <!-- <div class="col-md-2">
                    <label> &nbsp;</label>
                    <a href="<?php echo base_url('Rsa_Dashboard/AllApologyLetterPDF')?>" class="form-control btn btn-primary" name="apology_pdf_btn" id="apology_pdf_btn" target="_blank">All PDF</a>
                </div> -->
            </div>
            <div class="row form-group">
              <div class="col-md-3">
                  <input type="date" class="form-control" placeholder="Start Date" name="start_date" id="start_date">
              </div>
              <div class="col-md-3">
                  <input type="date" class="form-control" placeholder="End Date" name="end_date" id="end_date">
              </div>
              <div class="col-md-3">
                  <button type="button" class="btn btn-primary" name="date_filter_btn" id="date_filter_btn">Submit</button>
              </div>
            </div>
            <br><br>
      <div class="row">
        <div class="col-lg-12">
            <div class="example-col">
                <table class="table table-bordered table-striped w-full" id="saphire_policy_table">
                    <thead>
                      <th>Sr.no</th>
                    	<th>Dealer Code</th>
                      <th>Dealer Name</th>
                    	<th>Policy No</th>
                      <th>Engine No</th>
                      <th>Chassis No</th>
                      <th>Make</th>
                      <th>Model</th>
                      <th>Plan Name</th>
                      <th>Ic Name</th>
                      <th>Master policy No</th>
                      <th>Customer Name</th>
                      <th>Created Date</th>
                    	<th>Action</th>
                    </thead>
                    <tbody>
                     
                    </tbody>
                </table>
            </div>
        </div>
      </div>    
    </section>
  </div>
  <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">
  var table;
  function check(event)
    {
      var href = event.currentTarget.getAttribute('href');
      var id = event.currentTarget.getAttribute('data-id');
      $.ajax({
        'url' : base_url+'Report/updateDownloadStatus',
              "type": "POST",
              "data":{'policy_id':id},
              "dataType": "json",
              "success":function(response){
                if(response.status == 'true'){
                    myWindow=window.open(href,'_blank');
                    myWindow.document.close(); 
                    myWindow.focus();
                    myWindow.print(); 
                }
              }
      });
      return false;
    }
$(document).ready(function(){
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  callSapphirePolicy30AugAjax(start_date,end_date);
     
$("#date_filter_btn").on('click',function(){
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  // console.log(start_date,end_date+"   -filter");
  callSapphirePolicy30AugAjax(start_date,end_date);

});


function callSapphirePolicy30AugAjax(start_date,end_date){
  // console.log(start_date,end_date+"   -cal");
  table = $('#saphire_policy_table').DataTable({ 
             "scrollX": true,
             'destroy': true,
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
              'url' : base_url+'Report/Sapphire30AugPolicyAjax',
              "type": "POST",
              "data":{'is_downloaded':0,'start_date':start_date,'end_date':end_date},
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
}


});

</script>