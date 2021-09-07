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
      <h1>RECEIVABLE/PAYABLE DASH BOARD</h1>  
    </section>
    <br><br>
    <div class="container">
		<div class="row" style="border:5px solid #087cc6; padding: 10px 0; margin-bottom: 20px; ">
			<div class="col-md-12">
	          
	          <table class="table table-bordered table-striped bg-white text-center colored_tbl" style="margin: 0">
	            <thead>
	            	<tr>
						<td colspan="14" class="headtitle">
							<div class="text-center">
					            <h2>PAYMENT DETAILS</h2>
					          </div>
						</td>
	            	</tr>
	              <tr>
	                <th width="7%" rowspan="2" style="vertical-align: middle;">SR. NO.</th>
	                <th width="23%" rowspan="2" style="vertical-align: middle; text-align: left;">NAME OF INSURANCE CO.</th>
	                <th colspan="3">PAID AMOUNT</th>
	                <th colspan="4">SOLD POLICY AMT</th>
	                <th >BAL AMT</th> 
	              </tr>
	              <tr>
	                <th>TD</th>
	                <th>MTD</th>
	                <th>YTD</th>
	                <th>TD</th>
	                <th>MTD</th>
	                <th>YTD</th>
	                <th>Policy Count YTD</th>
	                <th>YTD</th>
	              </tr>
	            </thead>
	            <tbody>
	              <tr>
	                <td>1.</td>
	                <td style="text-align: left;">KOTAK MAHINDRA GIC LTD (PA)</td>
	                <td><?php echo $kotak_td_deposit;?></td>
	                <td><?php echo $kotak_mtd_deposit;?></td>
	                <td><?php echo $kotak_ytd_deposit;?></td>
	                <td><?php echo $kotak_td_policy_amount;?></td>
	                <td><?php echo $kotak_mtd_policy_amount;?></td>
	                <td><?php echo $kotak_ytd_policy_amount;?></td>
	                <td><?php echo $kotak_ytd_policy_count;?></td>
	                <td><?php echo round($kotak_ytd_balance,2) ;?></td>
	              </tr>
	              <tr>
	                <td>2.</td>
	                <td style="text-align: left;">ICICI LOMBARD GIC (PA)</td>
	                <td><?php echo $il_td_deposit;?></td>
	                <td><?php echo $il_mtd_deposit;?></td>
	                <td><?php echo $il_ytd_deposit;?></td>
	                <td><?php echo $il_td_policy_amount;?></td>
	                <td><?php echo $il_mtd_policy_amount;?></td>
	                <td><?php echo $il_ytd_policy_amount;?></td>
	                <td><?php echo $il_ytd_policy_count;?></td>
	                <td><?php echo round($il_ytd_balance,2)?></td>
	              </tr>
	              <tr>
	                <td>3.</td>
	                <td style="text-align: left;">TATA AIG GIC (PA)</td>
	               	<td><?php echo $tata_td_deposit;?></td>
	                <td><?php echo $tata_mtd_deposit;?></td>
	                <td><?php echo $tata_ytd_deposit;?></td>
	                <td><?php echo $tata_td_policy_amount;?></td>
	                <td><?php echo $tata_mtd_policy_amount;?></td>
	                <td><?php echo $tata_ytd_policy_amount;?></td>
	                <td><?php echo $tata_ytd_policy_count;?></td>
	                <td><?php echo round($tata_ytd_balance,2)?></td>
	              </tr>
	              <tr>
	                <td>4.</td>
	                <td style="text-align: left;">BHARTI AXA GIC (PA)</td>
	                <td><?php echo $bagi_td_deposit;?></td>
	                <td><?php echo $bagi_mtd_deposit;?></td>
	                <td><?php echo $bagi_ytd_deposit;?></td>
	                <td><?php echo $bagi_td_policy_amount;?></td>
	                <td><?php echo $bagi_mtd_policy_amount;?></td>
	                <td><?php echo $bagi_ytd_policy_amount;?></td>
	                <td><?php echo $bagi_ytd_policy_count;?></td>
	                <td><?php echo round($bagi_ytd_balance,2);?></td>
	              </tr>
	             
	              <tr>
	                <td></td>
	                <td><b>TOTAL</b></td>
	                <td><b><?php echo ($kotak_td_deposit+$il_td_deposit+$tata_td_deposit+$bagi_td_deposit) ;?></b></td>
	                <td><b><?php echo ($kotak_mtd_deposit+$il_mtd_deposit+$tata_mtd_deposit+$bagi_mtd_deposit) ;?></b></td>
	                <td><b><?php echo ($kotak_ytd_deposit+$il_ytd_deposit+$tata_ytd_deposit+$bagi_ytd_deposit) ;?></b></td>
	                <td><b><?php echo ($kotak_td_policy_amount+$il_td_policy_amount+$tata_td_policy_amount+$bagi_td_policy_amount) ;?></b></td>
	                <td><b><?php echo ($kotak_mtd_policy_amount+$il_mtd_policy_amount+$tata_mtd_policy_amount+$bagi_mtd_policy_amount) ;?></b></td>
	                <td><b><?php echo ($kotak_ytd_policy_amount+$il_ytd_policy_amount+$tata_ytd_policy_amount+$bagi_ytd_policy_amount) ;?></b></td>
	                <td><b><?php echo ($kotak_ytd_policy_count+$il_ytd_policy_count+$tata_ytd_policy_count+$bagi_ytd_policy_count) ;?></b></td>
	                <td><b><?php echo round(($kotak_ytd_balance+$il_ytd_balance+$tata_ytd_balance+$bagi_ytd_balance),2) ;?></b></td>	                
	              </tr>
<tr>
	<td colspan="9"></td>
</tr>

	               <tr>
	                <td>1.</td>
	                <td style="text-align: left;">BHARTI ASSIST (RSA)</td>
	                <td><?php echo $bharti_assist_td_deposit;?></td>
	                <td><?php echo $bharti_assist_mtd_deposit;?></td>
	                <td><?php echo $bharti_assist_ytd_deposit;?></td>
	                <td><?php echo $bharti_assist_td_amount;?></td>
	                <td><?php echo $bharti_assist_mtd_amount;?></td>
	                <td><?php echo $bharti_ytd_policy_count;?></td>
	                <td><?php echo $bharti_assist_ytd_amount;?></td>
	                <td><?php echo round(($bharti_assist_ytd_deposit - $bharti_ytd_policy_count),2);?></td>	                
	              </tr>

	               <tr>
	                <td>2.</td>
	                <td style="text-align: left;">TVS MOTOR COMPANY LTD</td>
	                <td><?php echo $td_tvs;?></td>
	                <td><?php echo $mtd_tvs;?></td>
	                <td><?php echo $ytd_tvs;?></td>
	                <td><?php echo $td_sold_tvs;?></td>
	                <td><?php echo $mtd_sold_tvs;?></td>
	                <td><?php echo $ytd_sold_tvs;?></td>
	                <td><b><?php echo ($kotak_ytd_policy_count+$il_ytd_policy_count+$tata_ytd_policy_count+$bagi_ytd_policy_count) ;?></b></td>
	                <td><?php echo $ytd_tvs - $ytd_sold_tvs; ?></td>	                
	              </tr>
<tr>
	<td colspan="9"></td>
</tr>	              
	              <tr>
	                <th width="7%" style="vertical-align: middle;">SR. NO.</th>
	                <th width="23%" style="vertical-align: middle; text-align: left;">NAME OF PARTY.</th>
	                <th>TD</th>
	                <th>MTD</th>
	                <th>YTD</th>
	                <th>SETUP</th>
	                <th>TOTAL PAID</th>
	                <th colspan="2">YTD O/S AMT</th>
	                <th>BAL AMT</th>
	              </tr>
	              <tr>
	                <td width="7%" style="vertical-align: middle;">1.</td>
	                <td width="23%" style="vertical-align: middle; text-align: left;">GST</td>
	                <td><?php echo 0;?></td>
	                <td><?php echo 0;?></td>
	                <td><?php echo 0;?></td>
	                <td><?php echo 0;?></td>
	                <td><?php echo 0;?></td>
	                <td colspan="2"><?php echo $ytd_net_gst;?></td>
	                <td><?php echo $ytd_gst - $ytd_net_gst;?></td>
	              </tr>	              
	              <tr>
	                <td width="7%" style="vertical-align: middle;">2.</td>
	                <td width="23%" style="vertical-align: middle; text-align: left;">TDS</td>
	                <td><?php echo 0;?></td>
	                <td><?php echo 0;?></td>
	                <td><?php echo 0;?></td>
	                <td><?php echo 0;?></td>
	                <td><?php echo 0;?></td>
	                <td colspan="2"><?php echo $ytd_input_tds;?></td>
	                <td><?php echo $ytd_tds - $ytd_input_tds;?></td>
	              </tr>	              	              
	              <tr>
	                <td colspan="2">GRAND TOTAL</td>
	                <td><b><?php echo ($td_tvs+ $kotak_td_deposit+$il_td_deposit+$tata_td_deposit+$bagi_td_deposit+$bharti_assist_td_deposit);?></b></td>
	                <td><b><?php echo ($mtd_tvs+ $kotak_mtd_deposit+$il_mtd_deposit+$tata_mtd_deposit+$bagi_mtd_deposit+$bharti_assist_mtd_deposit);?></b></td>
	                <td><b><?php echo ($ytd_tvs+ $kotak_ytd_deposit+$il_ytd_deposit+$tata_ytd_deposit+$bagi_ytd_deposit+$bharti_assist_ytd_deposit);?></b></td>
	                <td>0</td>
	                <td>0</td>
	                <td colspan="2"><?php echo $ytd_input_tds + $ytd_net_gst;?></td>	                
	                <td><b><?php echo round(($kotak_ytd_balance+$il_ytd_balance+$tata_ytd_balance+$bagi_ytd_balance + ($bharti_assist_ytd_deposit - $bharti_ytd_policy_count) + ($ytd_tvs - $ytd_sold_tvs)),2) ;?></b></td>
	              </tr>	              	              	              
	            </tbody>
	          </table>
	        </div>
	       </div> 


		<div class="row" style="border:5px solid #087cc6; padding: 10px 0; margin-bottom: 20px; display: none;">
			<div class="col-md-12">	          
	          <table class="table table-bordered table-striped bg-white text-center colored_tbl" style="margin: 0">
	            <thead>
	            	<tr>
						<td colspan="14" class="headtitle">
							<div class="text-left">
					            <h2>AMT GIVEN TO OTHER PARTIES</h2>
					          </div>
						</td>
	            	</tr>
	              <tr>
	                <th width="7%" rowspan="2" style="vertical-align: middle;">SR. NO.</th>
	                <th width="23%" rowspan="2" style="vertical-align: middle; text-align: left;">NAME OF PARTY.</th>	                
	                <th>PAID AMOUNT</th>
	                <th>SETUP</th>
	                <th>TOTAL PAID</th>
	                <th>OUTSTANDING AMOUNT</th>	                
	                <th >BAL AMT</th> 
	              </tr>
	            </thead>
	            <tbody>
<!-- 	              <tr>
	                <td>1.</td>
	                <td style="text-align: left;">TVS MOTOR COMPANY LTD</td>
	                <td><?php echo $ytd_tvs;?></td>
	                <td><?php echo $ytd_sold_tvs;?></td>
	                <td><?php echo ($kotak_ytd_policy_count+$il_ytd_policy_count+$tata_ytd_policy_count+$bagi_ytd_policy_count) ;?></td>
	                <td><?php echo $ytd_tvs - $ytd_sold_tvs; ?></td>
	              </tr> -->
	              <tr>
	                <td>1.</td>
	                <td style="text-align: left;">GST</td>
	                <td><?php echo $ytd_gst;?></td>
	                <td><?php echo 0;?></td>
	                <td><?php echo 0;?></td>
	                <td><?php echo 0;?></td>
	                <td><?php echo $ytd_gst;?></td>
	              </tr>
	              <tr>
	                <td>2.</td>
	                <td style="text-align: left;">TDS</td>
	                <td><?php echo $ytd_tds;?></td>
	                <td><?php echo 0;?></td>
	                <td><?php echo 0;?></td>	                
	                <td><?php echo 0;?></td>
<!-- 	                <td><?php echo ($kotak_ytd_policy_count+$il_ytd_policy_count+$tata_ytd_policy_count+$bagi_ytd_policy_count) ;?></td> -->
	                <td><?php echo $ytd_tds;?></td>
	              </tr>
	              <tr>	              
	                <td></td>
	                <td><b>TOTAL</b></td>
	                <td><?php echo 0;//$ytd_tvs + $ytd_gst + $ytd_tds;?></td>
	                <td><?php echo 0;//$ytd_tvs + $ytd_gst + $ytd_tds;?></td>
	                <td><?php echo 0;//$ytd_tvs + $ytd_gst + $ytd_tds;?></td>
	                <td><?php echo 0;//$ytd_tvs + $ytd_gst + $ytd_tds;?></td>
	                <td><?php echo 0;//$ytd_tvs + $ytd_gst + $ytd_tds;?></td>
	              </tr>
	            </tbody>
	          </table>
	        </div>	        
		</div>

    </div>
  </div>
  <div class="control-sidebar-bg"></div>
</div>