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
            <!-- <div class="row form-group">
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
                    <button type="button" class="form-control btn btn-primary" name="submit" id="active_dealer" value="Submit">Submit</button>
                </div>
            </div>
            <br><br> -->
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
$(document).ready(function(){
  table = $('#saphire_policy_table').DataTable({ 
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
              'url' : base_url+'Report/Sapphire30AugPolicyAjax',
              "type": "POST",
              "data":{'is_downloaded':1},
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
});

</script>