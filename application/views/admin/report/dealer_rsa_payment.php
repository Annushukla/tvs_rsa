<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <h1>AMOUNT RECEIVED FROM DEALER</h1>
      <br><br>
      <div class="row">
        <div class="col-lg-12">
            <div class="example-col">
                <table class="table table-bordered table-striped w-full" id="dealer_rsa_payment">
                    <thead>
                      <th>Sr.no</th>
                      <th>Dealer Name</th>
                    	<th>AMT Deposit TD</th>
                      <th>AMT Deposit MTD</th>
                      <th>AMT Deposit YTD</th>
                      <th>Credit Note YTD</th>
                    	<th>Total YTD</th>
                      <th>Sold Policy TD Amt</th>
                      <th>Sold Policy MTD Amt</th>
                      <th>Sold Policy YTD Amt</th>
                      <th>Total Sold YTD</th>
                      <th>Balance AMT</th>
                    </thead>
                    <tbody>
                     
                    </tbody>
                    <tfoot>
                      <th>--</th>
                      <th>TOTAL DEALERS</th>
                      <th><?php print $deposit_status['td_deposit_amount']; ?></th>
                      <th><?php print $deposit_status['mtd_deposit_amount']; ?></th>
                      <th><?php print $deposit_status['ytd_deposit_amount']; ?></th>
                      <th><?php print $total_commission['commission']; ?></th>
                      <th><?php print $deposit_status['ytd_deposit_amount'] + $total_commission['commission']; ?></th>
                      <th><?php print $sold_status['td_sold_amount']; ?></th>
                      <th><?php print $sold_status['mtd_sold_amount']; ?></th>
                      <th><?php print $sold_status['ytd_sold_amount']; ?></th>
                      <th><?php print $sold_status['this_year_total_policies']; ?></th>
                      <th><?php print $deposit_status['ytd_deposit_amount'] + $total_commission['commission'] - $sold_status['ytd_sold_amount']; ?></th>
                    </tfoot>
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
  table = $('#dealer_rsa_payment').DataTable({ 
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
              'url' : base_url+'Report/dealer_rsa_payment_ajax',
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



});

</script>