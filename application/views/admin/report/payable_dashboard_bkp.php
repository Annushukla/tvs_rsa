<style type="text/css">
  .bg-white {background: #fff;}
</style>

<div class="wrapper">  
  <div class="content-wrapper">
    <section class="content-header">
      <h1>RECEIVABLE/PAYABLE DASH BOARD</h1>  
    </section>
    <div class="container">
		<div class="row">
			<div class="col-md-12">
	          <div class="text-left">
	            <h3>AMT GIVEN TO INSURANCE COMPANY</h3>
	          </div>
	          <table class="table table-bordered table-striped bg-white text-center">
	            <thead>
	              <tr>
	                <th width="7%" rowspan="2" style="vertical-align: middle;">SR. NO.</th>
	                <th width="23%" rowspan="2" style="vertical-align: middle; text-align: left;">NAME OF INSURANCE CO.</th>
	                <th colspan="3">AMT DEPOSIT</th>
	                <th colspan="6">SOLD POLICY AMT</th>
	                <th colspan="3">BAL AMT</th> 
	              </tr>
	              <tr>
	                <th>TD</th>
	                <th>MTD</th>
	                <th>YTD</th>
	                <th>TD</th>
	                <th>MTD</th>
	                <th>YTD</th>
	                <th>Policy TD</th>
	                <th>Policy MTD</th>
	                <th>Policy YTD</th>
	                <th>TD</th>
	                <th>MTD</th>
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
	                <td><?php echo $kotak_td_policy_count ;?></td>
	                <td><?php echo $kotak_mtd_policy_count;?></td>
	                <td><?php echo $kotak_ytd_policy_count;?></td>
	                <td><?php echo $kotak_td_balance ;?></td>
	                <td><?php echo $kotak_mtd_balance ;?></td>
	                <td><?php echo $kotak_ytd_balance ;?></td>
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
	                <td><?php echo $il_td_policy_count;?></td>
	                <td><?php echo $il_mtd_policy_count;?></td>
	                <td><?php echo $il_ytd_policy_count;?></td>
	                <td><?php echo $il_td_balance?></td>
	                <td><?php echo $il_mtd_balance?></td>
	                <td><?php echo $il_ytd_balance?></td>
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
	                <td><?php echo $tata_td_policy_count;?></td>
	                <td><?php echo $tata_mtd_policy_count;?></td>
	                <td><?php echo $tata_ytd_policy_count;?></td>
	                <td><?php echo $tata_td_balance?></td>
	                <td><?php echo $tata_mtd_balance?></td>
	                <td><?php echo $tata_ytd_balance?></td>
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
	                <td><?php echo $bagi_td_policy_count;?></td>
	                <td><?php echo $bagi_mtd_policy_count;?></td>
	                <td><?php echo $bagi_ytd_policy_count;?></td>
	                <td><?php echo $tata_td_balance ;?></td>
	                <td><?php echo $tata_mtd_balance ;?></td>
	                <td><?php echo $tata_ytd_balance ;?></td>
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
	                <td><b><?php echo ($kotak_td_policy_count+$il_td_policy_count+$tata_td_policy_count+$bagi_td_policy_count) ;?></b></td>
	                <td><b><?php echo ($kotak_mtd_policy_count+$il_mtd_policy_count+$tata_mtd_policy_count+$bagi_mtd_policy_count) ;?></b></td>
	                <td><b><?php echo ($kotak_ytd_policy_count+$il_ytd_policy_count+$tata_ytd_policy_count+$bagi_ytd_policy_count) ;?></b></td>
	                <td><b><?php echo ($kotak_td_balance+$il_td_balance+$tata_td_balance+$bagi_td_balance) ;?></b></td>
	                <td><b><?php echo ($kotak_mtd_balance+$il_mtd_balance+$tata_mtd_balance+$bagi_mtd_balance) ;?></b></td>
	                <td><b><?php echo ($kotak_ytd_balance+$il_ytd_balance+$tata_ytd_balance+$bagi_ytd_balance) ;?></b></td>
	              </tr>
	              <tr>
	                <td>5.</td>
	                <td style="text-align: left;">BHARTI ASSIST (RSA)</td>
	                <td><?php echo $bharti_assist_td_deposit;?></td>
	                <td><?php echo $bharti_assist_mtd_deposit;?></td>
	                <td><?php echo $bharti_assist_ytd_deposit;?></td>
	                <td><?php echo $bharti_assist_td_amount;?></td>
	                <td><?php echo $bharti_assist_mtd_amount;?></td>
	                <td><?php echo $bharti_assist_ytd_amount;?></td>
	                <td><?php echo $bharti_td_policy_count;?></td>
	                <td><?php echo $bharti_mtd_policy_count;?></td>
	                <td><?php echo $bharti_ytd_policy_count;?></td>
	                <td><?php echo $bharti_assist_td_balance;?></td>
	                <td><?php echo $bharti_assist_mtd_balance;?></td>
	                <td><?php echo $bharti_assist_ytd_balance;?></td>
	              </tr>
	            </tbody>
	          </table>
	        </div>

 
		</div>
    </div>
  </div>
  <div class="control-sidebar-bg"></div>
</div>