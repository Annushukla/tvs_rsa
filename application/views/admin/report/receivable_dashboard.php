<style type="text/css">
  .bg-white {background: #fff;}
  .colored_tbl { margin-bottom: 10px; }
    .colored_tbl th{ color: #fff; background-color: #7eb9df; } 
    .colored_tbl td{background-color: #f3fafe; } 
    .colored_tbl>tbody>tr>td, .colored_tbl>tbody>tr>th, .colored_tbl>tfoot>tr>td, .colored_tbl>tfoot>tr>th, .colored_tbl>thead>tr>td, .colored_tbl>thead>tr>th { padding: 5px; }
    .table-bordered.colored_tbl>thead>tr>th, .table-bordered.colored_tbl>tbody>tr>th, .table-bordered.colored_tbl>tfoot>tr>th, .table-bordered.colored_tbl>thead>tr>td, .table-bordered.colored_tbl>tbody>tr>td, .table-bordered.colored_tbl>tfoot>tr>td { border-color: #087cc6; }
    .colored_tbl>caption+thead>tr:first-child>td, .colored_tbl>caption+thead>tr:first-child>th, .colored_tbl>colgroup+thead>tr:first-child>td, .colored_tbl>colgroup+thead>tr:first-child>th, .colored_tbl>thead:first-child>tr:first-child>td, .colored_tbl>thead:first-child>tr:first-child>th {border: 1px solid #087cc6;}
    .colored_tbl .headtitle {background-color: #087cc6;}
    .colored_tbl .headtitle h2 {margin: 5px 0;  font-weight: bold; color: #fff; font-size: 24px;}
    .colored_tbl>tbody>tr:last-child>td {font-weight: bold;}
</style>

<div class="wrapper">  
  <div class="content-wrapper">
    <section class="content-header">
      <h1>RECEIVABLE DASHBOARD</h1>
    </section>
    <br><br>
    <div class="container">
		<div class="row" style="border:5px solid #087cc6; padding: 10px 0; margin-bottom: 20px; ">
			<div class="col-md-12">
	          
	          <table class="table table-bordered table-striped bg-white text-center colored_tbl" id="receivable_table_temp" style="margin: 0">
	            <thead>
	            	<tr>
						<td colspan="14" class="headtitle">
							<div class="text-center">
					            <h2>RECEIPT DETAILS</h2>
					          </div>
						</td>
	            	</tr>
	              <tr>
	                <th width="7%" rowspan="2" style="vertical-align: middle;">SR. NO.</th>
	                <th width="23%" rowspan="2" style="vertical-align: middle; text-align: left;">DEALER NAME</th>
	                <th colspan="3">RECEIPT</th>
	                <th>CREDIT NOTE</th>
	                <th>TOTAL</th>
	                <th colspan="3">SOLD</th>
	                <th>TOTAL SOLD</th>
	                <th >BAL AMT</th> 
	              </tr>
	              <tr>
	                <th>TD</th>
	                <th>MTD</th>
	                <th>YTD</th>
	                <th>YTD</th>
	                <th>YTD</th>
	                <th>TD</th>
	                <th>MTD</th>
	                <th>YTD</th>
	                <th>YTD</th>
	                <th>YTD</th>
	              </tr>
	            </thead>

	            <tbody>
		            <tr>
		            	<td>1.</td>		            	
		            	<td>TOTAL DEALERS</td>
		            	<td><?php print $deposit_status['td_deposit_amount']; ?></td>
		            	<td><?php print $deposit_status['mtd_deposit_amount']; ?></td>
		            	<td><?php print $deposit_status['ytd_deposit_amount']; ?></td>
		            	<td><?php print $total_commission['commission']; ?></td>
		            	<td><?php print $deposit_status['ytd_deposit_amount'] + $total_commission['commission']; ?></td>
		            	<td><?php print $sold_status['td_sold_amount']; ?></td>
		            	<td><?php print $sold_status['mtd_sold_amount']; ?></td>
		            	<td><?php print $sold_status['ytd_sold_amount']; ?></td>
		            	<td><?php print $sold_status['this_year_total_policies']; ?></td>
		            	<td><?php print $deposit_status['ytd_deposit_amount'] + $total_commission['commission'] - $sold_status['ytd_sold_amount']; ?></td>
		            </tr>	
	            </tbody>
	          </table>
	        </div>
	       </div> 

    </div>
  </div>
  <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#receivable_table').DataTable();
	});
</script>