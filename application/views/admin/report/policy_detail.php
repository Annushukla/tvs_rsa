<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <h1>Layer3-Policy Details</h1>
      <br><br>
       <div class="row form-group">
                <div class="col-md-2">
                    <label>Start Date :</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" placeholder="Start Date" >
                </div>
                <div class="col-md-2">
                    <label>End Date :</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date" >
                    <span id="err_end_date" style="color:red;"></span>
                </div>
                <div class="col-md-2">
                    <label> &nbsp;</label>
                    <button type="button" class="form-control btn btn-primary" name="submit" id="layer_3" value="Submit">Submit</button>
                </div>
          </div>
      <br><br>
      <div class="row">
        <div class="col-lg-12">
            <div class="example-col">
                <table class="table table-bordered table-striped w-full" id="policy_detail">
                    <thead>
                      <th>Sr.no</th>
                      <th>Customer Name</th>
                      <th>Engine No</th>
                      <th>Chassis no</th>
                      <th>Policy No</th>
                      <th>Start Date</th>
                      <th>End Date</th>
                      <th>RSA IC</th>
                      <th>PA IC</th>
                      <th>Plan Name</th>
                      <th>Basic Premium</th>
                      <th>GST Amount</th>
                      <th>Total Premium</th>
                      <th>PA Cost</th>
                      <th>RSA Cost</th>
                      <th>Dealer Commission</th>
                      <th>TVS COMM</th>
                      <th>NET COST</th>
                      <th>GST(Net Cost)</th>
                      <th>Gross Total</th>
                      <!-- <th>TDS ON PA</th> -->
                      <th>TDS ON RSA</th>
                      <th>TDS ON DEALER COMM</th>
                      <th>TDS ON TVS</th>
                      <th>TOTAL TDS</th>
                      <th>Net Amt Payable</th>
                      <th>MARGIN ICPL</th>
                      <th>Comm To GIIB</th>
                      <th>Dealer Code</th>
                      <th>Dealer Name</th>
                      <th>Zone</th>
                      <th>City</th>
                      <th>State</th>
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
  table = $('#policy_detail').DataTable({ 
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
              'url' : base_url+'Report/policy_detail_ajax',
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




 $('#layer_3').on('click', function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            table1 = $('#policy_detail').DataTable({
                'paging': true,
                'destroy': true,
                "ajax": {
                    'url': base_url + 'Report/policy_detail_ajax',
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

</script>