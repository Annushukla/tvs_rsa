<style type="text/css">
  .bg-white {background: #fff;}
  .text-align{text-align: center;}
</style>

<div class="wrapper">  
  <div class="content-wrapper">
    <section class="content-header">
      <h1>LAYER - 2 DATA</h1>    
    </section>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="text-center">
            <h3>SALE PRICE</h3>
          </div>
            <div class="table-responsive">
		<table cellpadding="0" cellspacing="0" border="0" class="table table-sm table-bordered table-striped bg-white">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2" colspan="3">MAIN HEAD</th>
                            <th colspan="17" class="text-align">Sale Price</th>
                        </tr>
                        <tr>
                                <th colspan="5" class="text-align">TD</th>
                                <th colspan="5" class="text-align">MTD</th>
                                <th colspan="7" class="text-align">YTD</th>
                        </tr>
                        <tr>
                                <th>Sr. No.</th>
                                <th>PARTICULAR</th>
                                <th>PLAN</th>
                                <th>No. of Policy</th>
                                <th>SALE VALUE</th>
                                <th>GST</th>
                                <th>TOTAL</th>
                                <th>AMT RECD</th>
                                <th>No. of Policy</th>
                                <th>SALE VALUE</th>
                                <th>GST</th>
                                <th>TOTAL</th>
                                <th>AMT RECD</th>
                                <th>No. of Policy</th>
                                <th>SALE VALUE</th>
                                <th>GST</th>
                                <th>TOTAL</th>
                                <th>RECD</th>
                                <th>D/N</th>
                                <th>BAL PAYL/RCVL</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                    <td>1</td>
                                    <td>RSA</td>
                                    <td>Sapphire</td>
                                    <td><?=$layer_two_details['td_sapphire_policy_count'];?></td>
                                    <td><?=$td_sapphire_sell_value;?></td>
                                    <td><?=$td_sapphire_gst;?></td>
                                    <td><?=$td_sapphire_total_amount;?></td>
                                    <td><?=$td_sapphire_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_sapphire_policy_count'];?></td>
                                    <td><?=$mtd_sapphire_sell_value;?></td>
                                    <td><?=$mtd_sapphire_gst;?></td>
                                    <td><?=$mtd_sapphire_total_amount;?></td>
                                    <td><?=$mtd_sapphire_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_sapphire_policy_count'];?></td>
                                    <td><?=$ytd_sapphire_sell_value;?></td>
                                    <td><?=$ytd_sapphire_gst;?></td>
                                    <td><?=$ytd_sapphire_total_amount;?></td>
                                    <td><?=$ytd_sapphire_total_amount;?></td>
                                    <td>0</td>
                                    <td>0</td>
                            </tr>
                            <tr>
                                    <td>2</td>
                                    <td>RSA</td>
                                    <td>Platinum</td>
                                    <td><?=$layer_two_details['td_platinum_policy_count'];?></td>
                                    <td><?=$td_platinum_sell_value;?></td>
                                    <td><?=$td_platinum_gst;?></td>
                                    <td><?=$td_platinum_total_amount;?></td>
                                    <td><?=$td_platinum_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_platinum_policy_count'];?></td>
                                    <td><?=$mtd_platinum_sell_value;?></td>
                                    <td><?=$mtd_platinum_gst;?></td>
                                    <td><?=$mtd_platinum_total_amount;?></td>
                                    <td><?=$mtd_platinum_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_platinum_policy_count'];?></td>
                                    <td><?=$ytd_platinum_sell_value;?></td>
                                    <td><?=$ytd_platinum_gst;?></td>
                                    <td><?=$ytd_platinum_total_amount;?></td>
                                    <td><?=$ytd_platinum_total_amount;?></td>
                                    <td>0</td>
                                    <td>0</td>
                            </tr>
                            <tr>
                                    <td>3</td>
                                    <td>RSA</td>
                                    <td>GOLD</td>
                                    <td><?=$layer_two_details['td_gold_policy_count'];?></td>
                                    <td><?=$td_gold_sell_value;?></td>
                                    <td><?=$td_gold_gst;?></td>
                                    <td><?=$td_gold_total_amount;?></td>
                                    <td><?=$td_gold_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_gold_policy_count'];?></td>
                                    <td><?=$mtd_gold_sell_value;?></td>
                                    <td><?=$mtd_gold_gst;?></td>
                                    <td><?=$mtd_gold_total_amount;?></td>
                                    <td><?=$mtd_gold_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_gold_policy_count'];?></td>
                                    <td><?=$ytd_gold_sell_value;?></td>
                                    <td><?=$ytd_gold_gst;?></td>
                                    <td><?=$ytd_gold_total_amount;?></td>
                                    <td><?=$ytd_gold_total_amount;?></td>
                                    <td>0</td>
                                    <td>0</td>
                            </tr>
                            <tr>
                                    <td>4</td>
                                    <td>RSA</td>
                                    <td>Silver</td>
                                    <td><?=$layer_two_details['td_silver_policy_count'];?></td>
                                    <td><?=$td_silver_sell_value;?></td>
                                    <td><?=$td_silver_gst;?></td>
                                    <td><?=$td_silver_total_amount;?></td>
                                    <td><?=$td_silver_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_silver_policy_count'];?></td>
                                    <td><?=$mtd_silver_sell_value;?></td>
                                    <td><?=$mtd_silver_gst;?></td>
                                    <td><?=$mtd_silver_total_amount;?></td>
                                    <td><?=$mtd_silver_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_silver_policy_count'];?></td>
                                    <td><?=$ytd_silver_sell_value;?></td>
                                    <td><?=$ytd_silver_gst;?></td>
                                    <td><?=$ytd_silver_total_amount;?></td>
                                    <td><?=$ytd_silver_total_amount;?></td>
                                    <td>0</td>
                                    <td>0</td>
                            </tr>
                            <tr>
                                    <td colspan="20"></td>
                            </tr>
                            <tr class="font-weight-600">
                                    <td colspan="3" class="text-right">total</td>
                                    <td><?=$layer_two_details['td_sapphire_policy_count'] + $layer_two_details['td_platinum_policy_count'] + $layer_two_details['td_gold_policy_count'] + $layer_two_details['td_silver_policy_count']; ?></td>
                                    <td><?=$td_silver_sell_value + $td_gold_sell_value + $td_platinum_sell_value + $td_sapphire_sell_value; ?></td>
                                    <td><?=$td_silver_gst + $td_gold_gst + $td_platinum_gst + $td_sapphire_gst; ?></td>
                                    <td><?=$td_silver_total_amount + $td_gold_total_amount + $td_platinum_total_amount + $td_sapphire_total_amount; ?></td>
                                    <td><?=$td_silver_total_amount + $td_gold_total_amount + $td_platinum_total_amount + $td_sapphire_total_amount; ?></td>
                                    <td><?=$layer_two_details['mtd_silver_policy_count'] + $layer_two_details['mtd_gold_policy_count'] + $layer_two_details['mtd_sapphire_policy_count'] + $layer_two_details['mtd_platinum_policy_count'];?></td>
                                    <td><?=$mtd_silver_sell_value + $mtd_gold_sell_value + $mtd_platinum_sell_value + $mtd_sapphire_sell_value; ?></td>
                                    <td><?=$mtd_silver_gst + $mtd_gold_gst + $mtd_platinum_gst + $mtd_sapphire_gst; ?></td>
                                    <td><?=$mtd_silver_total_amount + $mtd_gold_total_amount + $mtd_platinum_total_amount + $mtd_sapphire_total_amount; ?></td>
                                    <td><?=$mtd_silver_total_amount + $mtd_gold_total_amount + $mtd_platinum_total_amount + $mtd_sapphire_total_amount; ?></td>
                                    <td><?=$layer_two_details['ytd_silver_policy_count'] + $layer_two_details['ytd_gold_policy_count'] + $layer_two_details['ytd_sapphire_policy_count'] + $layer_two_details['ytd_platinum_policy_count'];?></td>
                                    <td><?=$ytd_silver_sell_value + $ytd_gold_sell_value + $ytd_platinum_sell_value + $ytd_sapphire_sell_value; ?></td>
                                    <td><?=$ytd_silver_gst + $ytd_gold_gst + $ytd_platinum_gst + $ytd_sapphire_gst; ?></td>
                                    <td><?=$ytd_silver_total_amount + $ytd_gold_total_amount + $ytd_platinum_total_amount + $ytd_sapphire_total_amount; ?></td>
                                    <td><?=$ytd_silver_total_amount + $ytd_gold_total_amount + $ytd_platinum_total_amount + $ytd_sapphire_total_amount; ?></td>
                                    <td>0</td>
                                    <td>0</td>
                            </tr>
                    </tbody>
		</table>
	</div>
          <div class="table-responsive">
              <div class="text-center">
            <h3>PURCHASE COST PA</h3>
          </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table table-sm table-bordered table-striped bg-white">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2" colspan="3">MAIN HEAD</th>
                            <th colspan="19" class="text-align">PURCHASE COST PA</th>
                        </tr>
                        <tr>
                                <th colspan="7" class="text-align">TD</th>
                                <th colspan="5" class="text-align">MTD</th>
                                <th colspan="7" class="text-align">YTD</th>
                        </tr>
                        <tr>
                                <th>Sr. No.</th>
                                <th>PARTICULAR</th>
                                <th>PLAN</th>
                                <th>No. of Policy</th>
                                <th>PA COST</th>
                                <th>GST</th>
                                <th>TDS</th>
                                <th>NET</th>
                                <th>PAYMENT</th>
                                <th>No. of Policy</th>
                                <th>PA COST</th>
                                <th>GST</th>
                                <th>TDS</th>
                                <th>NET</th>
                                <th>PAYMENT</th>
                                <th>No. of Policy</th>
                                <th>PA COST</th>
                                <th>GST</th>
                                <th>TDS</th>
                                <th>NET</th>
                                <th>PAYMENT</th>
                                <th>BAL PAYL/RCVL</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                    <td>1</td>
                                    <td>RSA</td>
                                    <td>Sapphire</td>
                                    <td><?=$layer_two_details['td_sapphire_policy_count'];?></td>
                                    <td><?=$td_sapphire_purchase_cost;?></td>
                                    <td><?=$td_sapphire_purchase_gst;?></td>
                                    <td><?=$td_sapphire_purchase_tds;?></td>
                                    <td><?=$td_sapphire_purchase_total_amount;?></td>
                                    <td><?=$td_sapphire_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_sapphire_policy_count'];?></td>
                                    <td><?=$mtd_sapphire_purchase_cost;?></td>
                                    <td><?=$mtd_sapphire_purchase_gst;?></td>
                                    <td><?=$mtd_sapphire_purchase_tds;?></td>
                                    <td><?=$mtd_sapphire_purchase_total_amount;?></td>
                                    <td><?=$mtd_sapphire_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_sapphire_policy_count'];?></td>
                                    <td><?=$ytd_sapphire_purchase_cost;?></td>
                                    <td><?=$ytd_sapphire_purchase_gst;?></td>
                                    <td><?=$ytd_sapphire_purchase_tds;?></td>
                                    <td><?=$ytd_sapphire_purchase_total_amount;?></td>
                                    <td><?=$ytd_sapphire_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td>2</td>
                                    <td>RSA</td>
                                    <td>Platinum</td>
                                    <td><?=$layer_two_details['td_platinum_policy_count'];?></td>
                                    <td><?=$td_platinum_purchase_cost;?></td>
                                    <td><?=$td_platinum_purchase_gst;?></td>
                                    <td><?=$td_platinum_purchase_tds;?></td>
                                    <td><?=$td_platinum_purchase_total_amount;?></td>
                                    <td><?=$td_platinum_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_platinum_policy_count'];?></td>
                                    <td><?=$mtd_platinum_purchase_cost;?></td>
                                    <td><?=$mtd_platinum_purchase_gst;?></td>
                                    <td><?=$mtd_platinum_purchase_tds;?></td>
                                    <td><?=$mtd_platinum_purchase_total_amount;?></td>
                                    <td><?=$mtd_platinum_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_platinum_policy_count'];?></td>
                                    <td><?=$ytd_platinum_purchase_cost;?></td>
                                    <td><?=$ytd_platinum_purchase_gst;?></td>
                                    <td><?=$ytd_platinum_purchase_tds;?></td>
                                    <td><?=$ytd_platinum_purchase_total_amount;?></td>
                                    <td><?=$ytd_platinum_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td>3</td>
                                    <td>RSA</td>
                                    <td>GOLD</td>
                                    <td><?=$layer_two_details['td_gold_policy_count'];?></td>
                                    <td><?=$td_gold_purchase_cost;?></td>
                                    <td><?=$td_gold_purchase_gst;?></td>
                                    <td><?=$td_gold_purchase_tds;?></td>
                                    <td><?=$td_gold_purchase_total_amount;?></td>
                                    <td><?=$td_gold_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_gold_policy_count'];?></td>
                                    <td><?=$mtd_gold_purchase_cost;?></td>
                                    <td><?=$mtd_gold_purchase_gst;?></td>
                                    <td><?=$mtd_gold_purchase_tds;?></td>
                                    <td><?=$mtd_gold_purchase_total_amount;?></td>
                                    <td><?=$mtd_gold_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_gold_policy_count'];?></td>
                                    <td><?=$ytd_gold_purchase_cost;?></td>
                                    <td><?=$ytd_gold_purchase_gst;?></td>
                                    <td><?=$ytd_gold_purchase_tds;?></td>
                                    <td><?=$ytd_gold_purchase_total_amount;?></td>
                                    <td><?=$ytd_gold_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td>4</td>
                                    <td>RSA</td>
                                    <td>Silver</td>
                                    <td><?=$layer_two_details['td_silver_policy_count'];?></td>
                                    <td><?=$td_silver_purchase_cost;?></td>
                                    <td><?=$td_silver_purchase_gst;?></td>
                                    <td><?=$td_silver_purchase_tds;?></td>
                                    <td><?=$td_silver_purchase_total_amount;?></td>
                                    <td><?=$td_silver_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_silver_policy_count'];?></td>
                                    <td><?=$mtd_silver_purchase_cost;?></td>
                                    <td><?=$mtd_silver_purchase_gst;?></td>
                                    <td><?=$mtd_silver_purchase_tds;?></td>
                                    <td><?=$mtd_silver_purchase_total_amount;?></td>
                                    <td><?=$mtd_silver_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_silver_policy_count'];?></td>
                                    <td><?=$ytd_silver_purchase_cost;?></td>
                                    <td><?=$ytd_silver_purchase_gst;?></td>
                                    <td><?=$ytd_silver_purchase_tds;?></td>
                                    <td><?=$ytd_silver_purchase_total_amount;?></td>
                                    <td><?=$ytd_silver_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td colspan="23"></td>
                            </tr>
                            <tr class="font-weight-600">
                                    <td colspan="3" class="text-right">total</td>
                                    <td><?=$layer_two_details['td_sapphire_policy_count'] + $layer_two_details['td_platinum_policy_count'] + $layer_two_details['td_gold_policy_count'] + $layer_two_details['td_silver_policy_count']; ?></td>
                                    <td><?=$td_silver_purchase_cost + $td_gold_purchase_cost + $td_platinum_purchase_cost + $td_sapphire_purchase_cost; ?></td>
                                    <td><?=$td_silver_purchase_gst + $td_gold_purchase_gst + $td_platinum_purchase_gst + $td_sapphire_purchase_gst; ?></td>
                                    <td><?=$td_silver_purchase_tds + $td_gold_purchase_tds + $td_platinum_purchase_tds + $td_sapphire_purchase_tds; ?></td>
                                    <td><?=$td_silver_purchase_total_amount + $td_gold_purchase_total_amount + $td_platinum_purchase_total_amount + $td_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$td_silver_purchase_total_amount + $td_gold_purchase_total_amount + $td_platinum_purchase_total_amount + $td_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$layer_two_details['mtd_sapphire_policy_count'] + $layer_two_details['mtd_platinum_policy_count'] + $layer_two_details['mtd_gold_policy_count'] + $layer_two_details['mtd_silver_policy_count']; ?></td>
                                    <td><?=$mtd_silver_purchase_cost + $mtd_gold_purchase_cost + $mtd_platinum_purchase_cost + $mtd_sapphire_purchase_cost; ?></td>
                                    <td><?=$mtd_silver_purchase_gst + $mtd_gold_purchase_gst + $mtd_platinum_purchase_gst + $mtd_sapphire_purchase_gst; ?></td>
                                    <td><?=$mtd_silver_purchase_tds + $mtd_gold_purchase_tds + $mtd_platinum_purchase_tds + $mtd_sapphire_purchase_tds; ?></td>
                                    <td><?=$mtd_silver_purchase_total_amount + $mtd_gold_purchase_total_amount + $mtd_platinum_purchase_total_amount + $mtd_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$mtd_silver_purchase_total_amount + $mtd_gold_purchase_total_amount + $mtd_platinum_purchase_total_amount + $mtd_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$layer_two_details['ytd_sapphire_policy_count'] + $layer_two_details['ytd_platinum_policy_count'] + $layer_two_details['ytd_gold_policy_count'] + $layer_two_details['ytd_silver_policy_count']; ?></td>
                                    <td><?=$ytd_silver_purchase_cost + $ytd_gold_purchase_cost + $ytd_platinum_purchase_cost + $ytd_sapphire_purchase_cost; ?></td>
                                    <td><?=$ytd_silver_purchase_gst + $ytd_gold_purchase_gst + $ytd_platinum_purchase_gst + $ytd_sapphire_purchase_gst; ?></td>
                                    <td><?=$ytd_silver_purchase_tds + $ytd_gold_purchase_tds + $ytd_platinum_purchase_tds + $ytd_sapphire_purchase_tds; ?></td>
                                    <td><?=$ytd_silver_purchase_total_amount + $ytd_gold_purchase_total_amount + $ytd_platinum_purchase_total_amount + $ytd_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$ytd_silver_purchase_total_amount + $ytd_gold_purchase_total_amount + $ytd_platinum_purchase_total_amount + $ytd_sapphire_purchase_total_amount; ?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                    </tbody>
		</table>
	</div>
            
            <div class="table-responsive">
              <div class="text-center">
            <h3>PURCHASE COST RSA</h3>
          </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table table-sm table-bordered table-striped bg-white">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2" colspan="3">MAIN HEAD</th>
                            <th colspan="17" class="text-align">PURCHASE COST RSA</th>
                        </tr>
                        <tr>
                                <th colspan="5" class="text-align">TD</th>
                                <th colspan="5" class="text-align">MTD</th>
                                <th colspan="7" class="text-align">YTD</th>
                        </tr>
                        <tr>
                                <th>Sr. No.</th>
                                <th>PARTICULAR</th>
                                <th>PLAN</th>
                                <th>No. of Policy</th>
                                <th>RSA COST</th>
                                <th>GST</th>
                                <th>TDS</th>
                                <th>NET</th>
                                <th>PAYMENT</th>
                                <th>No. of Policy</th>
                                <th>RSA COST</th>
                                <th>GST</th>
                                <th>TDS</th>
                                <th>NET</th>
                                <th>PAYMENT</th>
                                <th>No. of Policy</th>
                                <th>RSA COST</th>
                                <th>GST</th>
                                <th>TDS</th>
                                <th>NET</th>
                                <th>PAYMENT</th>
                                <th>BAL PAYL/RCVL</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                    <td>1</td>
                                    <td>RSA</td>
                                    <td>Sapphire</td>
                                    <td><?=$layer_two_details['td_sapphire_policy_count'];?></td>
                                    <td><?=$rsa_td_sapphire_purchase_cost;?></td>
                                    <td><?=$rsa_td_sapphire_purchase_gst;?></td>
                                    <td><?=$rsa_td_sapphire_purchase_tds;?></td>
                                    <td><?=$rsa_td_sapphire_purchase_total_amount;?></td>
                                    <td><?=$rsa_td_sapphire_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_sapphire_policy_count'];?></td>
                                    <td><?=$rsa_mtd_sapphire_purchase_cost;?></td>
                                    <td><?=$rsa_mtd_sapphire_purchase_gst;?></td>
                                    <td><?=$rsa_mtd_sapphire_purchase_tds;?></td>
                                    <td><?=$rsa_mtd_sapphire_purchase_total_amount;?></td>
                                    <td><?=$rsa_mtd_sapphire_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_sapphire_policy_count'];?></td>
                                    <td><?=$rsa_ytd_sapphire_purchase_cost;?></td>
                                    <td><?=$rsa_ytd_sapphire_purchase_gst;?></td>
                                    <td><?=$rsa_ytd_sapphire_purchase_tds;?></td>
                                    <td><?=$rsa_ytd_sapphire_purchase_total_amount;?></td>
                                    <td><?=$rsa_ytd_sapphire_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td>2</td>
                                    <td>RSA</td>
                                    <td>Platinum</td>
                                    <td><?=$layer_two_details['td_platinum_policy_count'];?></td>
                                    <td><?=$rsa_td_platinum_purchase_cost;?></td>
                                    <td><?=$rsa_td_platinum_purchase_gst;?></td>
                                    <td><?=$rsa_td_platinum_purchase_tds;?></td>
                                    <td><?=$rsa_td_platinum_purchase_total_amount;?></td>
                                    <td><?=$rsa_td_platinum_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_platinum_policy_count'];?></td>
                                    <td><?=$rsa_mtd_platinum_purchase_cost;?></td>
                                    <td><?=$rsa_mtd_platinum_purchase_gst;?></td>
                                    <td><?=$rsa_mtd_platinum_purchase_tds;?></td>
                                    <td><?=$rsa_mtd_platinum_purchase_total_amount;?></td>
                                    <td><?=$rsa_mtd_platinum_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_platinum_policy_count'];?></td>
                                    <td><?=$rsa_ytd_platinum_purchase_cost;?></td>
                                    <td><?=$rsa_ytd_platinum_purchase_gst;?></td>
                                    <td><?=$rsa_ytd_platinum_purchase_tds;?></td>
                                    <td><?=$rsa_ytd_platinum_purchase_total_amount;?></td>
                                    <td><?=$rsa_ytd_platinum_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td>3</td>
                                    <td>RSA</td>
                                    <td>GOLD</td>
                                    <td><?=$layer_two_details['td_gold_policy_count'];?></td>
                                    <td><?=$rsa_td_gold_purchase_cost;?></td>
                                    <td><?=$rsa_td_gold_purchase_gst;?></td>
                                    <td><?=$rsa_td_gold_purchase_tds;?></td>
                                    <td><?=$rsa_td_gold_purchase_total_amount;?></td>
                                    <td><?=$rsa_td_gold_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_gold_policy_count'];?></td>
                                    <td><?=$rsa_mtd_gold_purchase_cost;?></td>
                                    <td><?=$rsa_mtd_gold_purchase_gst;?></td>
                                    <td><?=$rsa_mtd_gold_purchase_tds;?></td>
                                    <td><?=$rsa_mtd_gold_purchase_total_amount;?></td>
                                    <td><?=$rsa_mtd_gold_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_gold_policy_count'];?></td>
                                    <td><?=$rsa_ytd_gold_purchase_cost;?></td>
                                    <td><?=$rsa_ytd_gold_purchase_gst;?></td>
                                    <td><?=$rsa_ytd_gold_purchase_tds;?></td>
                                    <td><?=$rsa_ytd_gold_purchase_total_amount;?></td>
                                    <td><?=$rsa_ytd_gold_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td>4</td>
                                    <td>RSA</td>
                                    <td>Silver</td>
                                    <td><?=$layer_two_details['td_silver_policy_count'];?></td>
                                    <td><?=$rsa_td_silver_purchase_cost;?></td>
                                    <td><?=$rsa_td_silver_purchase_gst;?></td>
                                    <td><?=$rsa_td_silver_purchase_tds;?></td>
                                    <td><?=$rsa_td_silver_purchase_total_amount;?></td>
                                    <td><?=$rsa_td_silver_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_silver_policy_count'];?></td>
                                    <td><?=$rsa_mtd_silver_purchase_cost;?></td>
                                    <td><?=$rsa_mtd_silver_purchase_gst;?></td>
                                    <td><?=$rsa_mtd_silver_purchase_tds;?></td>
                                    <td><?=$rsa_mtd_silver_purchase_total_amount;?></td>
                                    <td><?=$rsa_mtd_silver_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_silver_policy_count'];?></td>
                                    <td><?=$rsa_ytd_silver_purchase_cost;?></td>
                                    <td><?=$rsa_ytd_silver_purchase_gst;?></td>
                                    <td><?=$rsa_ytd_silver_purchase_tds;?></td>
                                    <td><?=$rsa_ytd_silver_purchase_total_amount;?></td>
                                    <td><?=$rsa_ytd_silver_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td colspan="20"></td>
                            </tr>
                            <tr class="font-weight-600">
                                    <td colspan="3" class="text-right">total</td>
                                    <td><?=$layer_two_details['td_sapphire_policy_count'] + $layer_two_details['td_platinum_policy_count'] + $layer_two_details['td_gold_policy_count'] + $layer_two_details['td_silver_policy_count']; ?></td>
                                    <td><?=$rsa_td_silver_purchase_cost + $rsa_td_gold_purchase_cost + $rsa_td_platinum_purchase_cost + $rsa_td_sapphire_purchase_cost; ?></td>
                                    <td><?=$rsa_td_silver_purchase_gst + $rsa_td_gold_purchase_gst + $rsa_td_platinum_purchase_gst + $rsa_td_sapphire_purchase_gst; ?></td>
                                    <td><?=$rsa_td_silver_purchase_tds + $rsa_td_gold_purchase_tds + $rsa_td_platinum_purchase_tds + $rsa_td_sapphire_purchase_tds; ?></td>
                                    <td><?=$rsa_td_silver_purchase_total_amount + $rsa_td_gold_purchase_total_amount + $rsa_td_platinum_purchase_total_amount + $rsa_td_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$rsa_td_silver_purchase_total_amount + $rsa_td_gold_purchase_total_amount + $rsa_td_platinum_purchase_total_amount + $rsa_td_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$layer_two_details['mtd_sapphire_policy_count'] + $layer_two_details['mtd_platinum_policy_count'] + $layer_two_details['mtd_gold_policy_count'] + $layer_two_details['mtd_silver_policy_count']; ?></td>
                                    <td><?=$rsa_mtd_silver_purchase_cost + $rsa_mtd_gold_purchase_cost + $rsa_mtd_platinum_purchase_cost + $rsa_mtd_sapphire_purchase_cost; ?></td>
                                    <td><?=$rsa_mtd_silver_purchase_gst + $rsa_mtd_gold_purchase_gst + $rsa_mtd_platinum_purchase_gst + $rsa_mtd_sapphire_purchase_gst; ?></td>
                                    <td><?=$rsa_mtd_silver_purchase_tds + $rsa_mtd_gold_purchase_tds + $rsa_mtd_platinum_purchase_tds + $rsa_mtd_sapphire_purchase_tds; ?></td>
                                    <td><?=$rsa_mtd_silver_purchase_total_amount + $rsa_mtd_gold_purchase_total_amount + $rsa_mtd_platinum_purchase_total_amount + $rsa_mtd_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$rsa_mtd_silver_purchase_total_amount + $rsa_mtd_gold_purchase_total_amount + $rsa_mtd_platinum_purchase_total_amount + $rsa_mtd_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$layer_two_details['ytd_sapphire_policy_count'] + $layer_two_details['ytd_platinum_policy_count'] + $layer_two_details['ytd_gold_policy_count'] + $layer_two_details['ytd_silver_policy_count']; ?></td>
                                    <td><?=$rsa_ytd_silver_purchase_cost + $rsa_ytd_gold_purchase_cost + $rsa_ytd_platinum_purchase_cost + $rsa_ytd_sapphire_purchase_cost; ?></td>
                                    <td><?=$rsa_ytd_silver_purchase_gst + $rsa_ytd_gold_purchase_gst + $rsa_ytd_platinum_purchase_gst + $rsa_ytd_sapphire_purchase_gst; ?></td>
                                    <td><?=$rsa_ytd_silver_purchase_tds + $rsa_ytd_gold_purchase_tds + $rsa_ytd_platinum_purchase_tds + $rsa_ytd_sapphire_purchase_tds; ?></td>
                                    <td><?=$rsa_ytd_silver_purchase_total_amount + $rsa_ytd_gold_purchase_total_amount + $rsa_ytd_platinum_purchase_total_amount + $rsa_ytd_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$rsa_ytd_silver_purchase_total_amount + $rsa_ytd_gold_purchase_total_amount + $rsa_ytd_platinum_purchase_total_amount + $rsa_ytd_sapphire_purchase_total_amount; ?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                    </tbody>
		</table>
	</div>
            
            <div class="table-responsive">
              <div class="text-center">
            <h3>PURCHASE COST D.C.</h3>
          </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table table-sm table-bordered table-striped bg-white">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2" colspan="3">MAIN HEAD</th>
                            <th colspan="17" class="text-align">PURCHASE COST D.C.</th>
                        </tr>
                        <tr>
                                <th colspan="5" class="text-align">TD</th>
                                <th colspan="5" class="text-align">MTD</th>
                                <th colspan="7" class="text-align">YTD</th>
                        </tr>
                        <tr>
                                <th>Sr. No.</th>
                                <th>PARTICULAR</th>
                                <th>PLAN</th>
                                <th>No. of Policy</th>
                                <th>D.C COST</th>
                                <th>GST</th>
                                <th>TDS</th>
                                <th>NET</th>
                                <th>PAYMENT</th>
                                <th>No. of Policy</th>
                                <th>D.C COST</th>
                                <th>GST</th>
                                <th>TDS</th>
                                <th>NET</th>
                                <th>PAYMENT</th>
                                <th>No. of Policy</th>
                                <th>D.C COST</th>
                                <th>GST</th>
                                <th>TDS</th>
                                <th>NET</th>
                                <th>PAYMENT</th>
                                <th>BAL PAYL/RCVL</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                    <td>1</td>
                                    <td>RSA</td>
                                    <td>Sapphire</td>
                                    <td><?=$layer_two_details['td_sapphire_policy_count'];?></td>
                                    <td><?=$dc_td_sapphire_purchase_cost;?></td>
                                    <td><?=$dc_td_sapphire_purchase_gst;?></td>
                                    <td><?=$dc_td_sapphire_purchase_tds;?></td>
                                    <td><?=$dc_td_sapphire_purchase_total_amount;?></td>
                                    <td><?=$dc_td_sapphire_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_sapphire_policy_count'];?></td>
                                    <td><?=$dc_mtd_sapphire_purchase_cost;?></td>
                                    <td><?=$dc_mtd_sapphire_purchase_gst;?></td>
                                    <td><?=$dc_mtd_sapphire_purchase_tds;?></td>
                                    <td><?=$dc_mtd_sapphire_purchase_total_amount;?></td>
                                    <td><?=$dc_mtd_sapphire_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_sapphire_policy_count'];?></td>
                                    <td><?=$dc_ytd_sapphire_purchase_cost;?></td>
                                    <td><?=$dc_ytd_sapphire_purchase_gst;?></td>
                                    <td><?=$dc_ytd_sapphire_purchase_tds;?></td>
                                    <td><?=$dc_ytd_sapphire_purchase_total_amount;?></td>
                                    <td><?=$dc_ytd_sapphire_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td>2</td>
                                    <td>RSA</td>
                                    <td>Platinum</td>
                                    <td><?=$layer_two_details['td_platinum_policy_count'];?></td>
                                    <td><?=$dc_td_platinum_purchase_cost;?></td>
                                    <td><?=$dc_td_platinum_purchase_gst;?></td>
                                    <td><?=$dc_td_platinum_purchase_tds;?></td>
                                    <td><?=$dc_td_platinum_purchase_total_amount;?></td>
                                    <td><?=$dc_td_platinum_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_platinum_policy_count'];?></td>
                                    <td><?=$dc_mtd_platinum_purchase_cost;?></td>
                                    <td><?=$dc_mtd_platinum_purchase_gst;?></td>
                                    <td><?=$dc_mtd_platinum_purchase_tds;?></td>
                                    <td><?=$dc_mtd_platinum_purchase_total_amount;?></td>
                                    <td><?=$dc_mtd_platinum_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_platinum_policy_count'];?></td>
                                    <td><?=$dc_ytd_platinum_purchase_cost;?></td>
                                    <td><?=$dc_ytd_platinum_purchase_gst;?></td>
                                    <td><?=$dc_ytd_platinum_purchase_tds;?></td>
                                    <td><?=$dc_ytd_platinum_purchase_total_amount;?></td>
                                    <td><?=$dc_ytd_platinum_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td>3</td>
                                    <td>RSA</td>
                                    <td>GOLD</td>
                                    <td><?=$layer_two_details['td_gold_policy_count'];?></td>
                                    <td><?=$dc_td_gold_purchase_cost;?></td>
                                    <td><?=$dc_td_gold_purchase_gst;?></td>
                                    <td><?=$dc_td_gold_purchase_tds;?></td>
                                    <td><?=$dc_td_gold_purchase_total_amount;?></td>
                                    <td><?=$dc_td_gold_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_gold_policy_count'];?></td>
                                    <td><?=$dc_mtd_gold_purchase_cost;?></td>
                                    <td><?=$dc_mtd_gold_purchase_gst;?></td>
                                    <td><?=$dc_mtd_gold_purchase_tds;?></td>
                                    <td><?=$dc_mtd_gold_purchase_total_amount;?></td>
                                    <td><?=$dc_mtd_gold_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_gold_policy_count'];?></td>
                                    <td><?=$dc_ytd_gold_purchase_cost;?></td>
                                    <td><?=$dc_ytd_gold_purchase_gst;?></td>
                                    <td><?=$dc_ytd_gold_purchase_tds;?></td>
                                    <td><?=$dc_ytd_gold_purchase_total_amount;?></td>
                                    <td><?=$dc_ytd_gold_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td>4</td>
                                    <td>RSA</td>
                                    <td>Silver</td>
                                    <td><?=$layer_two_details['td_silver_policy_count'];?></td>
                                    <td><?=$dc_td_silver_purchase_cost;?></td>
                                    <td><?=$dc_td_silver_purchase_gst;?></td>
                                    <td><?=$dc_td_silver_purchase_tds;?></td>
                                    <td><?=$dc_td_silver_purchase_total_amount;?></td>
                                    <td><?=$dc_td_silver_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_silver_policy_count'];?></td>
                                    <td><?=$dc_mtd_silver_purchase_cost;?></td>
                                    <td><?=$dc_mtd_silver_purchase_gst;?></td>
                                    <td><?=$dc_mtd_silver_purchase_tds;?></td>
                                    <td><?=$dc_mtd_silver_purchase_total_amount;?></td>
                                    <td><?=$dc_mtd_silver_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_silver_policy_count'];?></td>
                                    <td><?=$dc_ytd_silver_purchase_cost;?></td>
                                    <td><?=$dc_ytd_silver_purchase_gst;?></td>
                                    <td><?=$dc_ytd_silver_purchase_tds;?></td>
                                    <td><?=$dc_ytd_silver_purchase_total_amount;?></td>
                                    <td><?=$dc_ytd_silver_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td colspan="20"></td>
                            </tr>
                            <tr class="font-weight-600">
                                    <td colspan="3" class="text-right">total</td>
                                    <td><?=$layer_two_details['td_sapphire_policy_count'] + $layer_two_details['td_platinum_policy_count'] + $layer_two_details['td_gold_policy_count'] + $layer_two_details['td_silver_policy_count']; ?></td>
                                    <td><?=$dc_td_silver_purchase_cost + $dc_td_gold_purchase_cost + $dc_td_platinum_purchase_cost + $dc_td_sapphire_purchase_cost; ?></td>
                                    <td><?=$dc_td_silver_purchase_gst + $dc_td_gold_purchase_gst + $dc_td_platinum_purchase_gst + $dc_td_sapphire_purchase_gst; ?></td>
                                    <td><?=$dc_td_silver_purchase_tds + $dc_td_gold_purchase_tds + $dc_td_platinum_purchase_tds + $dc_td_sapphire_purchase_tds; ?></td>
                                    <td><?=$dc_td_silver_purchase_total_amount + $dc_td_gold_purchase_total_amount + $dc_td_platinum_purchase_total_amount + $dc_td_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$dc_td_silver_purchase_total_amount + $dc_td_gold_purchase_total_amount + $dc_td_platinum_purchase_total_amount + $dc_td_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$layer_two_details['mtd_sapphire_policy_count'] + $layer_two_details['mtd_platinum_policy_count'] + $layer_two_details['mtd_gold_policy_count'] + $layer_two_details['mtd_silver_policy_count']; ?></td>
                                    <td><?=$dc_mtd_silver_purchase_cost + $dc_mtd_gold_purchase_cost + $dc_mtd_platinum_purchase_cost + $dc_mtd_sapphire_purchase_cost; ?></td>
                                    <td><?=$dc_mtd_silver_purchase_gst + $dc_mtd_gold_purchase_gst + $dc_mtd_platinum_purchase_gst + $dc_mtd_sapphire_purchase_gst; ?></td>
                                    <td><?=$dc_mtd_silver_purchase_tds + $dc_mtd_gold_purchase_tds + $dc_mtd_platinum_purchase_tds + $dc_mtd_sapphire_purchase_tds; ?></td>
                                    <td><?=$dc_mtd_silver_purchase_total_amount + $dc_mtd_gold_purchase_total_amount + $dc_mtd_platinum_purchase_total_amount + $dc_mtd_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$dc_mtd_silver_purchase_total_amount + $dc_mtd_gold_purchase_total_amount + $dc_mtd_platinum_purchase_total_amount + $dc_mtd_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$layer_two_details['ytd_sapphire_policy_count'] + $layer_two_details['ytd_platinum_policy_count'] + $layer_two_details['ytd_gold_policy_count'] + $layer_two_details['ytd_silver_policy_count']; ?></td>
                                    <td><?=$dc_ytd_silver_purchase_cost + $dc_ytd_gold_purchase_cost + $dc_ytd_platinum_purchase_cost + $dc_ytd_sapphire_purchase_cost; ?></td>
                                    <td><?=$dc_ytd_silver_purchase_gst + $dc_ytd_gold_purchase_gst + $dc_ytd_platinum_purchase_gst + $dc_ytd_sapphire_purchase_gst; ?></td>
                                    <td><?=$dc_ytd_silver_purchase_tds + $dc_ytd_gold_purchase_tds + $dc_ytd_platinum_purchase_tds + $dc_ytd_sapphire_purchase_tds; ?></td>
                                    <td><?=$dc_ytd_silver_purchase_total_amount + $dc_ytd_gold_purchase_total_amount + $dc_ytd_platinum_purchase_total_amount + $dc_ytd_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$dc_ytd_silver_purchase_total_amount + $dc_ytd_gold_purchase_total_amount + $dc_ytd_platinum_purchase_total_amount + $dc_ytd_sapphire_purchase_total_amount; ?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                    </tbody>
		</table>
	</div>
            
        <div class="table-responsive">
              <div class="text-center">
            <h3>PURCHASE COST TVS</h3>
          </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table table-sm table-bordered table-striped bg-white">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2" colspan="3">MAIN HEAD</th>
                            <th colspan="19" class="text-align">PURCHASE COST TVS</th>
                        </tr>
                        <tr>
                                <th colspan="7" class="text-align">TD</th>
                                <th colspan="5" class="text-align">MTD</th>
                                <th colspan="7" class="text-align">YTD</th>
                        </tr>
                        <tr>
                                <th>Sr. No.</th>
                                <th>PARTICULAR</th>
                                <th>PLAN</th>
                                <th>No. of Policy</th>
                                <th>TVS COST</th>
                                <th>GST</th>
                                <th>TDS</th>
                                <th>NET</th>
                                <th>PAYMENT</th>
                                <th>No. of Policy</th>
                                <th>TVS COST</th>
                                <th>GST</th>
                                <th>TDS</th>
                                <th>NET</th>
                                <th>PAYMENT</th>
                                <th>No. of Policy</th>
                                <th>TVS COST</th>
                                <th>GST</th>
                                <th>TDS</th>
                                <th>NET</th>
                                <th>PAYMENT</th>
                                <th>BAL PAYL/RCVL</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                    <td>1</td>
                                    <td>RSA</td>
                                    <td>Sapphire</td>
                                    <td><?=$layer_two_details['td_sapphire_policy_count'];?></td>
                                    <td><?=$tvs_td_sapphire_purchase_cost;?></td>
                                    <td><?=$tvs_td_sapphire_purchase_gst;?></td>
                                    <td><?=$tvs_td_sapphire_purchase_tds;?></td>
                                    <td><?=$tvs_td_sapphire_purchase_total_amount;?></td>
                                    <td><?=$tvs_td_sapphire_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_sapphire_policy_count'];?></td>
                                    <td><?=$tvs_mtd_sapphire_purchase_cost;?></td>
                                    <td><?=$tvs_mtd_sapphire_purchase_gst;?></td>
                                    <td><?=$tvs_mtd_sapphire_purchase_tds;?></td>
                                    <td><?=$tvs_mtd_sapphire_purchase_total_amount;?></td>
                                    <td><?=$tvs_mtd_sapphire_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_sapphire_policy_count'];?></td>
                                    <td><?=$tvs_ytd_sapphire_purchase_cost;?></td>
                                    <td><?=$tvs_ytd_sapphire_purchase_gst;?></td>
                                    <td><?=$tvs_ytd_sapphire_purchase_tds;?></td>
                                    <td><?=$tvs_ytd_sapphire_purchase_total_amount;?></td>
                                    <td><?=$tvs_ytd_sapphire_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td>2</td>
                                    <td>RSA</td>
                                    <td>Platinum</td>
                                    <td><?=$layer_two_details['td_platinum_policy_count'];?></td>
                                    <td><?=$tvs_td_platinum_purchase_cost;?></td>
                                    <td><?=$tvs_td_platinum_purchase_gst;?></td>
                                    <td><?=$tvs_td_platinum_purchase_tds;?></td>
                                    <td><?=$tvs_td_platinum_purchase_total_amount;?></td>
                                    <td><?=$tvs_td_platinum_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_platinum_policy_count'];?></td>
                                    <td><?=$tvs_mtd_platinum_purchase_cost;?></td>
                                    <td><?=$tvs_mtd_platinum_purchase_gst;?></td>
                                    <td><?=$tvs_mtd_platinum_purchase_tds;?></td>
                                    <td><?=$tvs_mtd_platinum_purchase_total_amount;?></td>
                                    <td><?=$tvs_mtd_platinum_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_platinum_policy_count'];?></td>
                                    <td><?=$tvs_ytd_platinum_purchase_cost;?></td>
                                    <td><?=$tvs_ytd_platinum_purchase_gst;?></td>
                                    <td><?=$tvs_ytd_platinum_purchase_tds;?></td>
                                    <td><?=$tvs_ytd_platinum_purchase_total_amount;?></td>
                                    <td><?=$tvs_ytd_platinum_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td>3</td>
                                    <td>RSA</td>
                                    <td>GOLD</td>
                                    <td><?=$layer_two_details['td_gold_policy_count'];?></td>
                                    <td><?=$tvs_td_gold_purchase_cost;?></td>
                                    <td><?=$tvs_td_gold_purchase_gst;?></td>
                                    <td><?=$tvs_td_gold_purchase_tds;?></td>
                                    <td><?=$tvs_td_gold_purchase_total_amount;?></td>
                                    <td><?=$tvs_td_gold_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_gold_policy_count'];?></td>
                                    <td><?=$tvs_mtd_gold_purchase_cost;?></td>
                                    <td><?=$tvs_mtd_gold_purchase_gst;?></td>
                                    <td><?=$tvs_mtd_gold_purchase_tds;?></td>
                                    <td><?=$tvs_mtd_gold_purchase_total_amount;?></td>
                                    <td><?=$tvs_mtd_gold_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_gold_policy_count'];?></td>
                                    <td><?=$tvs_ytd_gold_purchase_cost;?></td>
                                    <td><?=$tvs_ytd_gold_purchase_gst;?></td>
                                    <td><?=$tvs_ytd_gold_purchase_tds;?></td>
                                    <td><?=$tvs_ytd_gold_purchase_total_amount;?></td>
                                    <td><?=$tvs_ytd_gold_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td>4</td>
                                    <td>RSA</td>
                                    <td>Silver</td>
                                    <td><?=$layer_two_details['td_silver_policy_count'];?></td>
                                    <td><?=$tvs_td_silver_purchase_cost;?></td>
                                    <td><?=$tvs_td_silver_purchase_gst;?></td>
                                    <td><?=$tvs_td_silver_purchase_tds;?></td>
                                    <td><?=$tvs_td_silver_purchase_total_amount;?></td>
                                    <td><?=$tvs_td_silver_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['mtd_silver_policy_count'];?></td>
                                    <td><?=$tvs_mtd_silver_purchase_cost;?></td>
                                    <td><?=$tvs_mtd_silver_purchase_gst;?></td>
                                    <td><?=$tvs_mtd_silver_purchase_tds;?></td>
                                    <td><?=$tvs_mtd_silver_purchase_total_amount;?></td>
                                    <td><?=$tvs_mtd_silver_purchase_total_amount;?></td>
                                    <td><?=$layer_two_details['ytd_silver_policy_count'];?></td>
                                    <td><?=$tvs_ytd_silver_purchase_cost;?></td>
                                    <td><?=$tvs_ytd_silver_purchase_gst;?></td>
                                    <td><?=$tvs_ytd_silver_purchase_tds;?></td>
                                    <td><?=$tvs_ytd_silver_purchase_total_amount;?></td>
                                    <td><?=$tvs_ytd_silver_purchase_total_amount;?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <tr>
                                    <td colspan="20"></td>
                            </tr>
                            <tr class="font-weight-600">
                                    <td colspan="3" class="text-right">total</td>
                                    <td><?=$layer_two_details['td_sapphire_policy_count'] + $layer_two_details['td_platinum_policy_count'] + $layer_two_details['td_gold_policy_count'] + $layer_two_details['td_silver_policy_count']; ?></td>
                                    <td><?=$tvs_td_silver_purchase_cost + $tvs_td_gold_purchase_cost + $tvs_td_platinum_purchase_cost + $tvs_td_sapphire_purchase_cost; ?></td>
                                    <td><?=$tvs_td_silver_purchase_gst + $tvs_td_gold_purchase_gst + $tvs_td_platinum_purchase_gst + $tvs_td_sapphire_purchase_gst; ?></td>
                                    <td><?=$tvs_td_silver_purchase_tds + $tvs_td_gold_purchase_tds + $tvs_td_platinum_purchase_tds + $tvs_td_sapphire_purchase_tds; ?></td>
                                    <td><?=$tvs_td_silver_purchase_total_amount + $tvs_td_gold_purchase_total_amount + $tvs_td_platinum_purchase_total_amount + $tvs_td_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$tvs_td_silver_purchase_total_amount + $tvs_td_gold_purchase_total_amount + $tvs_td_platinum_purchase_total_amount + $tvs_td_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$layer_two_details['mtd_sapphire_policy_count'] + $layer_two_details['mtd_platinum_policy_count'] + $layer_two_details['mtd_gold_policy_count'] + $layer_two_details['mtd_silver_policy_count']; ?></td>
                                    <td><?=$tvs_mtd_silver_purchase_cost + $tvs_mtd_gold_purchase_cost + $tvs_mtd_platinum_purchase_cost + $tvs_mtd_sapphire_purchase_cost; ?></td>
                                    <td><?=$tvs_mtd_silver_purchase_gst + $tvs_mtd_gold_purchase_gst + $tvs_mtd_platinum_purchase_gst + $tvs_mtd_sapphire_purchase_gst; ?></td>
                                    <td><?=$tvs_mtd_silver_purchase_tds + $tvs_mtd_gold_purchase_tds + $tvs_mtd_platinum_purchase_tds + $tvs_mtd_sapphire_purchase_tds; ?></td>
                                    <td><?=$tvs_mtd_silver_purchase_total_amount + $tvs_mtd_gold_purchase_total_amount + $tvs_mtd_platinum_purchase_total_amount + $tvs_mtd_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$tvs_mtd_silver_purchase_total_amount + $tvs_mtd_gold_purchase_total_amount + $tvs_mtd_platinum_purchase_total_amount + $tvs_mtd_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$layer_two_details['ytd_sapphire_policy_count'] + $layer_two_details['ytd_platinum_policy_count'] + $layer_two_details['ytd_gold_policy_count'] + $layer_two_details['ytd_silver_policy_count']; ?></td>
                                    <td><?=$tvs_ytd_silver_purchase_cost + $tvs_ytd_gold_purchase_cost + $tvs_ytd_platinum_purchase_cost + $tvs_ytd_sapphire_purchase_cost; ?></td>
                                    <td><?=$tvs_ytd_silver_purchase_gst + $tvs_ytd_gold_purchase_gst + $tvs_ytd_platinum_purchase_gst + $tvs_ytd_sapphire_purchase_gst; ?></td>
                                    <td><?=$tvs_ytd_silver_purchase_tds + $tvs_ytd_gold_purchase_tds + $tvs_ytd_platinum_purchase_tds + $tvs_ytd_sapphire_purchase_tds; ?></td>
                                    <td><?=$tvs_ytd_silver_purchase_total_amount + $tvs_ytd_gold_purchase_total_amount + $tvs_ytd_platinum_purchase_total_amount + $tvs_ytd_sapphire_purchase_total_amount; ?></td>
                                    <td><?=$tvs_ytd_silver_purchase_total_amount + $tvs_ytd_gold_purchase_total_amount + $tvs_ytd_platinum_purchase_total_amount + $tvs_ytd_sapphire_purchase_total_amount; ?></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                    </tbody>
		</table>
	</div>
            
            <div class="table-responsive">
              <div class="text-center">
            <h3>ICPL/GIIB MARGIN AND GST</h3>
          </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table table-sm table-bordered table-striped bg-white">
                    <thead class="thead-light">
                        <tr>
                            
                            <th  colspan="3">MAIN HEAD</th>
                            <th colspan="3" class="text-align">MARGIN-ICPL</th>
                            <th colspan="3" class="text-align">MARGIN-GIIB</th>
                            <th colspan="3" class="text-align">OUTPUT GST</th>
                            <th colspan="3" class="text-align">INPUT GST</th>
                            <th colspan="6" class="text-align">NET GST</th>
                        </tr>
                        <tr>
                            <th>Sr. No.</th>
                                <th>PARTICULAR</th>
                                <th>PLAN</th>
                            <th class="text-align">TD</th>
                            <th class="text-align">MTD</th>
                            <th class="text-align">YTD</th>
                            
                            <th  class="text-align">TD</th>
                            <th  class="text-align">MTD</th>
                            <th  class="text-align">YTD</th>
                            
                             <th  class="text-align">TD</th>
                            <th  class="text-align">MTD</th>
                            <th  class="text-align">YTD</th>
                            
                             <th  class="text-align">TD</th>
                            <th  class="text-align">MTD</th>
                            <th  class="text-align">YTD</th>
                            
                             <th  class="text-align">TD</th>
                            <th  class="text-align">MTD</th>
                            <th  class="text-align">YTD</th>
                            <th  class="text-align">AD SET UP</th>
                            <th  class="text-align">PAYMENT</th>
                            <th  class="text-align">BAL PAYL/RCVL</th>
                        </tr>
                        
                    </thead>
                    <tbody>
                            <tr>                                
                                    <td>1</td>
                                    <td>RSA</td>
                                    <td>Sapphire</td>
                                    <td><?=$total_sapphire_td = ($td_sapphire_sell_value - $td_sapphire_purchase_cost - $rsa_td_sapphire_purchase_cost - $tvs_td_sapphire_purchase_cost - $dc_td_sapphire_purchase_cost); ?></td>
                                    <td><?=$total_sapphire_mtd = ($mtd_sapphire_sell_value - $mtd_sapphire_purchase_cost - $rsa_mtd_sapphire_purchase_cost - $tvs_mtd_sapphire_purchase_cost - $dc_mtd_sapphire_purchase_cost); ?></td>
                                    <td><?=$total_sapphire_ytd = ($ytd_sapphire_sell_value - $ytd_sapphire_purchase_cost - $rsa_ytd_sapphire_purchase_cost - $tvs_ytd_sapphire_purchase_cost - $dc_ytd_sapphire_purchase_cost); ?></td>
                                    <td><?=$giib_td_sapphire_policy_margin;?></td>
                                    <td><?=$giib_mtd_sapphire_policy_margin;?></td>
                                    <td><?=$giib_ytd_sapphire_policy_margin;?></td>
                                    <td><?=$td_sapphire_policy_output_gst;?></td>
                                    <td><?=$mtd_sapphire_policy_output_gst;?></td>
                                    <td><?=$ytd_sapphire_policy_output_gst;?></td>
                                    <td><?=$td_sapphire_policy_input_gst;?></td>
                                    <td><?=$mtd_sapphire_policy_input_gst;?></td>
                                    <td><?=$ytd_sapphire_policy_input_gst;?></td>
                                     <td><?=$td_sapphire_policy_net_gst;?></td>
                                    <td><?=$mtd_sapphire_policy_net_gst;?></td>
                                    <td><?=$ytd_sapphire_policy_net_gst;?></td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    
                            </tr>
                            <tr>
                                    <td>2</td>
                                    <td>RSA</td>
                                    <td>Platinum</td>
                                    <td><?=$total_platinum_td = ($td_platinum_sell_value - $td_platinum_purchase_cost - $rsa_td_platinum_purchase_cost - $tvs_td_platinum_purchase_cost - $dc_td_platinum_purchase_cost); ?></td>
                                    <td><?=$total_platinum_mtd = ($mtd_platinum_sell_value - $mtd_platinum_purchase_cost - $rsa_mtd_platinum_purchase_cost - $tvs_mtd_platinum_purchase_cost - $dc_mtd_platinum_purchase_cost); ?></td>
                                    <td><?=$total_platinum_ytd = ($ytd_platinum_sell_value - $ytd_platinum_purchase_cost - $rsa_ytd_platinum_purchase_cost - $tvs_ytd_platinum_purchase_cost - $dc_ytd_platinum_purchase_cost); ?></td>
                                    <td><?=$giib_td_platinum_policy_margin;?></td>
                                    <td><?=$giib_mtd_platinum_policy_margin;?></td>
                                    <td><?=$giib_ytd_platinum_policy_margin;?></td>
                                    <td><?=$td_platinum_policy_output_gst;?></td>
                                    <td><?=$mtd_platinum_policy_output_gst;?></td>
                                    <td><?=$ytd_platinum_policy_output_gst;?></td>
                                    <td><?=$td_platinum_policy_input_gst;?></td>
                                    <td><?=$mtd_platinum_policy_input_gst;?></td>
                                    <td><?=$ytd_platinum_policy_input_gst;?></td>
                                    <td><?=$td_platinum_policy_net_gst;?></td>
                                    <td><?=$mtd_platinum_policy_net_gst;?></td>
                                    <td><?=$ytd_platinum_policy_net_gst;?></td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                            </tr>
                            <tr>
                                    <td>3</td>
                                    <td>RSA</td>
                                    <td>GOLD</td>
                                    <td><?=$total_gold_td = ($td_gold_sell_value - $td_gold_purchase_cost - $rsa_td_gold_purchase_cost - $tvs_td_gold_purchase_cost - $dc_td_gold_purchase_cost); ?></td>
                                    <td><?=$total_gold_mtd = ($mtd_gold_sell_value - $mtd_gold_purchase_cost - $rsa_mtd_gold_purchase_cost - $tvs_mtd_gold_purchase_cost - $dc_mtd_gold_purchase_cost); ?></td>
                                    <td><?=$total_gold_ytd = ($ytd_gold_sell_value - $ytd_gold_purchase_cost - $rsa_ytd_gold_purchase_cost - $tvs_ytd_gold_purchase_cost - $dc_ytd_gold_purchase_cost); ?></td>
                                    <td><?=$giib_td_gold_policy_margin;?></td>
                                    <td><?=$giib_mtd_gold_policy_margin;?></td>
                                    <td><?=$giib_ytd_gold_policy_margin;?></td>
                                    <td><?=$td_gold_policy_output_gst;?></td>
                                    <td><?=$mtd_gold_policy_output_gst;?></td>
                                    <td><?=$ytd_gold_policy_output_gst;?></td>
                                    <td><?=$td_gold_policy_input_gst;?></td>
                                    <td><?=$mtd_gold_policy_input_gst;?></td>
                                    <td><?=$ytd_gold_policy_input_gst;?></td>
                                    <td><?=$td_gold_policy_net_gst;?></td>
                                    <td><?=$mtd_gold_policy_net_gst;?></td>
                                    <td><?=$ytd_gold_policy_net_gst;?></td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                            </tr>
                            <tr>
                                    <td>4</td>
                                    <td>RSA</td>
                                    <td>Silver</td>
                                    <td><?=$total_silver_td = ($td_silver_sell_value - $td_silver_purchase_cost - $rsa_td_silver_purchase_cost - $tvs_td_silver_purchase_cost - $dc_td_silver_purchase_cost); ?></td>
                                    <td><?=$total_silver_mtd = ($mtd_silver_sell_value - $mtd_silver_purchase_cost - $rsa_mtd_silver_purchase_cost - $tvs_mtd_silver_purchase_cost - $dc_mtd_silver_purchase_cost); ?></td>
                                    <td><?=$total_silver_ytd = ($ytd_silver_sell_value - $ytd_silver_purchase_cost - $rsa_ytd_silver_purchase_cost - $tvs_ytd_silver_purchase_cost - $dc_ytd_silver_purchase_cost); ?></td>
                                    <td><?=$giib_td_silver_policy_margin;?></td>
                                    <td><?=$giib_mtd_silver_policy_margin;?></td>
                                    <td><?=$giib_ytd_silver_policy_margin;?></td>
                                    <td><?=$td_silver_policy_output_gst;?></td>
                                    <td><?=$mtd_silver_policy_output_gst;?></td>
                                    <td><?=$ytd_silver_policy_output_gst;?></td>
                                    <td><?=$td_silver_policy_input_gst;?></td>
                                    <td><?=$mtd_silver_policy_input_gst;?></td>
                                    <td><?=$ytd_silver_policy_input_gst;?></td>
                                    <td><?=$td_silver_policy_net_gst;?></td>
                                    <td><?=$mtd_silver_policy_net_gst;?></td>
                                    <td><?=$ytd_silver_policy_net_gst;?></td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                            </tr>
                            <tr>
                                    <td colspan="21"></td>
                            </tr>
                            <tr class="font-weight-600">
                                    <td colspan="3" class="text-right">total</td>
                                    <td><?=$total_sapphire_td + $total_platinum_td + $total_silver_td + $total_gold_td;?></td>
                                    <td><?=$total_sapphire_mtd + $total_platinum_mtd + $total_silver_mtd + $total_gold_mtd;?></td>
                                    <td><?=$total_sapphire_ytd + $total_platinum_ytd + $total_silver_ytd + $total_gold_ytd;?></td>
                                    <td><?=$giib_td_silver_policy_margin + $giib_td_gold_policy_margin + $giib_td_sapphire_policy_margin + $giib_td_platinum_policy_margin;?></td>                                    
                                    <td><?=$giib_mtd_silver_policy_margin + $giib_mtd_gold_policy_margin + $giib_mtd_sapphire_policy_margin + $giib_mtd_platinum_policy_margin;?></td>
                                    <td><?=$giib_ytd_silver_policy_margin + $giib_ytd_gold_policy_margin + $giib_ytd_sapphire_policy_margin + $giib_ytd_platinum_policy_margin;?></td>
                                    <td><?=$td_silver_policy_output_gst + $td_sapphire_policy_output_gst + $td_gold_policy_output_gst + $td_platinum_policy_output_gst; ?></td>
                                    <td><?=$mtd_silver_policy_output_gst + $mtd_sapphire_policy_output_gst + $mtd_gold_policy_output_gst + $mtd_platinum_policy_output_gst; ?></td>
                                    <td><?=$ytd_silver_policy_output_gst + $ytd_sapphire_policy_output_gst + $ytd_gold_policy_output_gst + $ytd_platinum_policy_output_gst; ?></td>
                                    <td><?=$td_silver_policy_input_gst + $td_sapphire_policy_input_gst + $td_gold_policy_input_gst + $td_platinum_policy_input_gst; ?></td>
                                    <td><?=$mtd_silver_policy_input_gst + $mtd_sapphire_policy_input_gst + $mtd_gold_policy_input_gst + $mtd_platinum_policy_input_gst; ?></td>
                                    <td><?=$ytd_silver_policy_input_gst + $ytd_sapphire_policy_input_gst + $ytd_gold_policy_input_gst + $ytd_platinum_policy_input_gst; ?></td>                                    
                                    <td><?=$td_silver_policy_net_gst + $td_sapphire_policy_net_gst + $td_gold_policy_net_gst + $td_platinum_policy_net_gst; ?></td>
                                    <td><?=$mtd_silver_policy_net_gst + $mtd_sapphire_policy_net_gst + $mtd_gold_policy_net_gst + $mtd_platinum_policy_net_gst; ?></td>
                                    <td><?=$ytd_silver_policy_net_gst + $ytd_sapphire_policy_net_gst + $ytd_gold_policy_net_gst + $ytd_platinum_policy_net_gst; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                    </tbody>
		</table>
	</div>
            <div class="table-responsive">
              <div class="text-center">
            <h3>TDS/RECD/PAYMENT</h3>
          </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table table-sm table-bordered table-striped bg-white">
                    <thead class="thead-light">
                        <tr>
                            
                            <th  colspan="3">MAIN HEAD</th>
                            <th colspan="6" class="text-align">TDS</th>
<!--                             <th colspan="3" class="text-align">RECD</th>
                            <th colspan="3" class="text-align">PAYMENT</th> -->
                        </tr>
                        <tr>
                            <th>Sr. No.</th>
                                <th>PARTICULAR</th>
                                <th>PLAN</th>
                            <th class="text-align">TD</th>
                            <th class="text-align">MTD</th>
                            <th class="text-align">YTD</th>
                            <th  class="text-align">AD SET UP</th>
                            <th  class="text-align">PAYMENT</th>
                            <th  class="text-align">BAL PAYL/RCVL</th>
                            
<!--                             <th  class="text-align">TD</th>
                            <th  class="text-align">MTD</th>
                            <th  class="text-align">YTD</th>
                            
                             <th  class="text-align">TD</th>
                            <th  class="text-align">MTD</th>
                            <th  class="text-align">YTD</th> -->
                        </tr>
                        
                    </thead>
                    <tbody>
                            <tr>
                                    <td>1</td>
                                    <td>RSA</td>
                                    <td>Sapphire</td>
                                    <td><?=$td_sapphire_policy_tds;?></td>
                                    <td><?=$mtd_sapphire_policy_tds;?></td>
                                    <td><?=$ytd_sapphire_policy_tds;?></td> 
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
<!--                                     <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td> -->
                            </tr>
                            <tr>
                                    <td>2</td>
                                    <td>RSA</td>
                                    <td>Platinum</td>
                                    <td><?=$td_platinum_policy_tds;?></td>
                                    <td><?=$mtd_platinum_policy_tds;?></td>
                                    <td><?=$ytd_platinum_policy_tds;?></td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
<!--                                     <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td> -->
                            </tr>
                            <tr>
                                    <td>3</td>
                                    <td>RSA</td>
                                    <td>GOLD</td>
                                    <td><?=$td_gold_policy_tds;?></td>
                                    <td><?=$mtd_gold_policy_tds;?></td>
                                    <td><?=$ytd_gold_policy_tds;?></td>    
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
<!--                                     <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td> -->
                            </tr>
                            <tr>
                                    <td>4</td>
                                    <td>RSA</td>
                                    <td>Silver</td>
                                    <td><?=$td_silver_policy_tds;?></td>
                                    <td><?=$mtd_silver_policy_tds;?></td>
                                    <td><?=$ytd_silver_policy_tds;?></td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
<!--                                     <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td> -->
                            </tr>
                            <tr>
                                    <td colspan="21"></td>
                            </tr>
                            <tr class="font-weight-600">
                                    <td colspan="3" class="text-right">total</td>
                                    <td><?=$td_sapphire_policy_tds + $td_silver_policy_tds + $td_platinum_policy_tds + $td_gold_policy_tds; ?></td>
                                    <td><?=$mtd_sapphire_policy_tds + $mtd_silver_policy_tds + $mtd_platinum_policy_tds + $mtd_gold_policy_tds; ?></td>
                                    <td><?=$ytd_sapphire_policy_tds + $ytd_silver_policy_tds + $ytd_platinum_policy_tds + $ytd_gold_policy_tds; ?></td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td></td>
                                    <td></td>
<!--                                     <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td> -->
                                    
                            </tr>
                    </tbody>
		</table>
	</div>
        </div>
      </div>
    </div>
  </div>
  <div class="control-sidebar-bg"></div>
</div>