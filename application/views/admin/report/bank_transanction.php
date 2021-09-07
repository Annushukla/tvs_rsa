<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Bank Transanction Data
      </h1>

     <br><br>
     <div class="row form-group">
          <div class="col-md-2">
              <label>Start Date :</label>
              <input type="date" class="form-control" name="dealerbank_from_date" id="dealerbank_from_date" placeholder="Start Date" min="2018-08-01" max="">
          </div>
          <div class="col-md-2">
              <label>End Date :</label>
              <input type="date" class="form-control" name="dealerbank_to_date" id="dealerbank_to_date" placeholder="End Date" min="2018-08-01" max="">
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
          <table class="table table-bordered table-striped w-full" id="transanction_datatable">
              <thead>
              	<th>Sr.No.</th>
                <th>Dealer Name</th>
                <th>Transaction No</th>
                <th>Deposit Amount</th>
                <th>Account Holder Name</th>
                <th>Account Type</th>
                <th>Transaction Type</th>
                <th>Approval Status</th>
                <th>Created Date</th>
                <!-- <th>Action</th> -->
            </thead>

              </thead>
              <tbody>
               

              </tbody>
          </table>
      </div>
  </div>
      
    <br><br>
</div>    
      
</section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript">
  
$(document).ready(function(){
    transanction_datatable = $('#transanction_datatable').DataTable({

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
        "ajax": {
            'url' : base_url+'Report/BankTransanctiondataAjax',
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
var dealerbank_from_date =  $('#dealerbank_from_date').val();
var dealerbank_to_date = $('#dealerbank_to_date').val();
  er_date_status = false;

    if(dealerbank_to_date=='' || dealerbank_from_date==''){
      er_date_status = true;
      alert('Please Select the Date');
    }
    if(er_date_status== false){
      date1 = new Date(dealerbank_from_date);
      date2 = new Date(dealerbank_to_date);
     
        if(date1 <= date2){
              transanction_datatable2 = $('#transanction_datatable').DataTable({

                'paging': true,
                  'destroy': true,
                // Load data for the table's content from an Ajax source
                "ajax": {
                    'url' : base_url+'Report/BankTransanctiondataAjax',
                    "type": "POST",
                    "dataType": "json",
                    "data" : {dealerbank_from_date : dealerbank_from_date ,dealerbank_to_date : dealerbank_to_date },
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

</script>