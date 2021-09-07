<style type="text/css">
  .bg-white {background: #fff;}
</style>

<?php 
$mtd_payment = $mtd_silver_purchase_total_amount + $mtd_gold_purchase_total_amount + $mtd_platinum_purchase_total_amount + $mtd_sapphire_purchase_total_amount + $rsa_mtd_silver_purchase_total_amount + $rsa_mtd_gold_purchase_total_amount + $rsa_mtd_platinum_purchase_total_amount + $rsa_mtd_sapphire_purchase_total_amount + $dc_mtd_silver_purchase_total_amount + $dc_mtd_gold_purchase_total_amount + $dc_mtd_platinum_purchase_total_amount + $dc_mtd_sapphire_purchase_total_amount + $tvs_mtd_silver_purchase_total_amount + $tvs_mtd_gold_purchase_total_amount + $tvs_mtd_platinum_purchase_total_amount + $tvs_mtd_sapphire_purchase_total_amount; 

$td_payment = $td_silver_purchase_total_amount + $td_gold_purchase_total_amount + $td_platinum_purchase_total_amount + $td_sapphire_purchase_total_amount + $rsa_td_silver_purchase_total_amount + $rsa_td_gold_purchase_total_amount + $rsa_td_platinum_purchase_total_amount + $rsa_td_sapphire_purchase_total_amount + $dc_td_silver_purchase_total_amount + $dc_td_gold_purchase_total_amount + $dc_td_platinum_purchase_total_amount + $dc_td_sapphire_purchase_total_amount + $tvs_td_silver_purchase_total_amount + $tvs_td_gold_purchase_total_amount + $tvs_td_platinum_purchase_total_amount + $tvs_td_sapphire_purchase_total_amount;

$ytd_payment = $ytd_silver_purchase_total_amount + $ytd_gold_purchase_total_amount + $ytd_platinum_purchase_total_amount + $ytd_sapphire_purchase_total_amount + $rsa_ytd_silver_purchase_total_amount + $rsa_ytd_gold_purchase_total_amount + $rsa_ytd_platinum_purchase_total_amount + $rsa_ytd_sapphire_purchase_total_amount + $dc_ytd_silver_purchase_total_amount + $dc_ytd_gold_purchase_total_amount + $dc_ytd_platinum_purchase_total_amount + $dc_ytd_sapphire_purchase_total_amount + $tvs_ytd_silver_purchase_total_amount + $tvs_ytd_gold_purchase_total_amount + $tvs_ytd_platinum_purchase_total_amount + $tvs_ytd_sapphire_purchase_total_amount;

?>

<div class="wrapper">  
  <div class="content-wrapper">
    <section class="content-header">
      <h1>TARGET VS ACHIVEMENT</h1>    
    </section>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="text-center">
            <h3>TARGET VS ACHIVEMENT</h3>
          </div>
          <div class="col-md-6 ">
            <a href="" class="btn btn-primary" data-toggle="modal" data-target="#TargetModal">Add Target</a>
          </div><br><br>
          <table class="table table-bordered table-striped bg-white text-center">
            <thead>
              <tr>
                <th width="7%" rowspan="2" style="vertical-align: middle;">SR. NO.</th>
                <th width="23%" rowspan="2" style="vertical-align: middle; text-align: left;">PARTICULAR</th>
                <th colspan="3">TARGET</th>
                <th colspan="3">ACHIVEMENT</th>
                <th colspan="3">ACHIVED %</th>                
              </tr>
              <tr>
                <th>TD</th>
                <th>MTD</th>
                <th>YTD</th>
                <th>TD</th>
                <th>MTD</th>
                <th>YTD</th>
                <th>TD</th>
                <th>MTD</th>
                <th>YTD</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1.</td>
                <td style="text-align: left;">RSA (In Nos.)</td>
                <td><?=$target_data['td']?></td>
                <td><?=$target_data['mtd']?></td>
                <td><?=$target_data['ytd']?></td>
                <td><?=$td_count?></td>
                <td><?=$mtd_count?></td>
                <td><?=$ytd_count?></td>
                <td>80</td>
                <td>44</td>
                <td>33</td>
              </tr>
              <tr>
                <td></td>
                <td><b>TOTAL</b></td>
                <td><b>5</b></td>
                <td><b>9</b></td>
                <td><b>12</b></td>
                <td><?=$td_count?></td>
                <td><?=$mtd_count?></td>
                <td><?=$ytd_count?></td>
                <td><b>80</b></td>
                <td><b>44</b></td>
                <td><b>33</b></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-md-12">
          <div class="text-center">
            <h3>REVENUE & EXPENDITURE</h3>
          </div>
          <table class="table table-bordered table-striped bg-white text-center">
            <thead>
              <tr>
                <th width="7%" rowspan="2" style="vertical-align: middle;">SR. NO.</th>
                <th width="23%" rowspan="2" style="vertical-align: middle; text-align: left;">PARTICULAR</th>
                <th colspan="3">REVENUE</th>
                <th colspan="3">EXPENDITURE</th>              
                <th colspan="3">GROSS PROFIT</th>              
              </tr>
              <tr>
                <th>TD</th>
                <th>MTD</th>
                <th>YTD</th>
                <th>TD</th>
                <th>MTD</th>
                <th>YTD</th>
                <th>TD</th>
                <th>MTD</th>
                <th>YTD</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1.</td>
                <td style="text-align: left;">RSA</td>
                <td> <?=$td_revenue?></td>
                <td> <?=$mtd_revenue?> </td>
                <td> <?=$ytd_revenue?> </td>
                <td> <?=$td_total_expenditure_amount?> </td>
                <td> <?=$mtd_total_expenditure_amount?> </td>
                <td> <?=$ytd_total_expenditure_amount?> </td>
                <td> <?=$td_gross_profit?> </td>
                <td> <?=$mtd_gross_profit?> </td>
                <td> <?=$ytd_gross_profit?> </td>
              </tr>
              <tr>
                <td></td>
                <td><b>TOTAL</b></td>
                <td> <?=$td_revenue?></td>
                <td> <?=$mtd_revenue?> </td>
                <td> <?=$ytd_revenue?> </td>
                <td> <?=$td_total_expenditure_amount?> </td>
                <td> <?=$mtd_total_expenditure_amount?> </td>
                <td> <?=$ytd_total_expenditure_amount?> </td>
                <td> <?=$td_gross_profit?> </td>
                <td> <?=$mtd_gross_profit?> </td>
                <td> <?=$ytd_gross_profit?> </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-md-12">
          <div class="text-center">
            <h3>RECEIPT & PAYMENT</h3>
          </div>
          <table class="table table-bordered table-striped bg-white text-center">
            <thead>
              <tr>
                <th width="7%" rowspan="2" style="vertical-align: middle;">SR. NO.</th>
                <th width="23%" rowspan="2" style="vertical-align: middle; text-align: left;">PARTICULAR</th>
                <th colspan="3">RECEIPT</th>
                <th colspan="3">PAYMENT</th>              
                <th colspan="3">NET FUND</th>              
              </tr>
              <tr>
                <th>TD</th>
                <th>MTD</th>
                <th>YTD</th>
                <th>TD</th>
                <th>MTD</th>
                <th>YTD</th>
                <th>TD</th>
                <th>MTD</th>
                <th>YTD</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1.</td>
                <td style="text-align: left;">RSA</td>
                <td><?=$td_receipt_amount?></td>
                <td><?=$mtd_receipt_amount?></td>
                <td><?=$ytd_receipt_amount?></td>
                <td><?=$td_payment?></td>
                <td><?=$mtd_payment?></td>
                <td><?=$ytd_payment?></td>
                <td><?=$td_receipt_amount - $td_payment?></td>
                <td><?=$mtd_receipt_amount - $mtd_payment?></td>
                <td><?=$ytd_receipt_amount - $ytd_payment?></td>
              </tr>
              <tr>
                <td></td>
                <td><b>TOTAL</b></td>
                <td><?=$td_receipt_amount?></td>
                <td><?=$mtd_receipt_amount?></td>
                <td><?=$ytd_receipt_amount?></td>
                <td><?=$td_payment?></td>
                <td><?=$mtd_payment?></td>
                <td><?=$ytd_payment?></td>
                <td><?=$td_receipt_amount - $td_payment?></td>
                <td><?=$mtd_receipt_amount - $mtd_payment?></td>
                <td><?=$ytd_receipt_amount - $ytd_payment?></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-md-12">
          <div class="text-center">
            <h3>ASSETS & LIABILITIES</h3>
          </div>
          <table class="table table-bordered table-striped bg-white text-center">
            <thead>
              <tr>
                <th width="7%" rowspan="2" style="vertical-align: middle;">SR. NO.</th>
                <th width="23%" rowspan="2" style="vertical-align: middle; text-align: left;">PARTICULAR</th>
                <th>LIABILITIES </th>
                <th>ASSETS</th>              
                <th>NET WORTH</th>              
              </tr>
<!--               <tr>
                <th>TD</th>
                <th>MTD</th>
                <th>YTD</th>
                <th>TD</th>
                <th>MTD</th>
                <th>YTD</th>
                <th>TD</th>
                <th>MTD</th>
                <th>YTD</th>
              </tr> -->
            </thead>
            <tbody>
              <tr>
                <td>1.</td>
                <td style="text-align: left;">RSA</td>
                <td><?=$total_wallet_balance?></td>
<!--                 <td><?=$total_wallet_balance?></td>
                <td><?=$total_wallet_balance?></td> -->
                <td><?=$total_balance_amount?></td>
<!--                 <td><?=$total_balance_amount?></td>
                <td><?=$total_balance_amount?></td> -->
                <td><?=$total_balance_amount - $total_wallet_balance; ?></td>
<!--                 <td><?=$td_receipt_amount?></td>
                <td><?=$td_receipt_amount?></td> -->
              </tr>
              <tr>
                <td></td>
                <td><b>TOTAL</b></td>
                <td><?=$total_wallet_balance?></td>
<!--                 <td><?=$total_wallet_balance?></td>
                <td><?=$total_wallet_balance?></td> -->
                <td><?=$total_balance_amount?></td>
<!--                 <td><?=$total_balance_amount?></td>
                <td><?=$total_balance_amount?></td> -->
                <td><?=$total_balance_amount - $total_wallet_balance; ?></td>
<!--                 <td><?=$td_receipt_amount?></td>
                <td><?=$td_receipt_amount?></td> -->
              </tr>
            </tbody>
          </table>
        </div>
      </div>

<div class="modal fade" id="TargetModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Target</h4>
        </div>
      <form action="<?php echo base_url('admin/submitTarget');?>" method="post" id="target_form" >
        <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-4">
                  TD : <input type="number" class="form-control" min="100" name="td_target" value="" required>
                </div>
                <div class="col-md-4">
                  MTD : <input type="number" class="form-control" min="100" name="mtd_target" value="" required>
                </div>
                <div class="col-md-4">
                  YTD : <input type="number" class="form-control" min="100" name="ytd_target" value="" required>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" >Submit</button>
          <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Close</button>
          </form>
        </div>
      </div>
      
    </div>
  </div>
  
    </div>
  </div>
  <div class="control-sidebar-bg"></div>
</div>