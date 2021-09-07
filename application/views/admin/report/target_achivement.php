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

<?php 
$mtd_payment = $mtd_silver_purchase_total_amount + $mtd_gold_purchase_total_amount + $mtd_platinum_purchase_total_amount + $mtd_sapphire_purchase_total_amount + $rsa_mtd_silver_purchase_total_amount + $rsa_mtd_gold_purchase_total_amount + $rsa_mtd_platinum_purchase_total_amount + $rsa_mtd_sapphire_purchase_total_amount + $dc_mtd_silver_purchase_total_amount + $dc_mtd_gold_purchase_total_amount + $dc_mtd_platinum_purchase_total_amount + $dc_mtd_sapphire_purchase_total_amount + $tvs_mtd_silver_purchase_total_amount + $tvs_mtd_gold_purchase_total_amount + $tvs_mtd_platinum_purchase_total_amount + $tvs_mtd_sapphire_purchase_total_amount; 

$td_payment = $td_silver_purchase_total_amount + $td_gold_purchase_total_amount + $td_platinum_purchase_total_amount + $td_sapphire_purchase_total_amount + $rsa_td_silver_purchase_total_amount + $rsa_td_gold_purchase_total_amount + $rsa_td_platinum_purchase_total_amount + $rsa_td_sapphire_purchase_total_amount + $dc_td_silver_purchase_total_amount + $dc_td_gold_purchase_total_amount + $dc_td_platinum_purchase_total_amount + $dc_td_sapphire_purchase_total_amount + $tvs_td_silver_purchase_total_amount + $tvs_td_gold_purchase_total_amount + $tvs_td_platinum_purchase_total_amount + $tvs_td_sapphire_purchase_total_amount;

$ytd_payment = $ytd_silver_purchase_total_amount + $ytd_gold_purchase_total_amount + $ytd_platinum_purchase_total_amount + $ytd_sapphire_purchase_total_amount + $rsa_ytd_silver_purchase_total_amount + $rsa_ytd_gold_purchase_total_amount + $rsa_ytd_platinum_purchase_total_amount + $rsa_ytd_sapphire_purchase_total_amount + $dc_ytd_silver_purchase_total_amount + $dc_ytd_gold_purchase_total_amount + $dc_ytd_platinum_purchase_total_amount + $dc_ytd_sapphire_purchase_total_amount + $tvs_ytd_silver_purchase_total_amount + $tvs_ytd_gold_purchase_total_amount + $tvs_ytd_platinum_purchase_total_amount + $tvs_ytd_sapphire_purchase_total_amount;

  $total_liabilities = $total_wallet_balance + $ytd_silver_policy_net_gst + $ytd_sapphire_policy_net_gst + $ytd_gold_policy_net_gst + $ytd_platinum_policy_net_gst + $ytd_sapphire_policy_tds + $ytd_silver_policy_tds + $ytd_platinum_policy_tds + $ytd_gold_policy_tds;

    if($bharti_balance_amount<0){
        $total_liabilities = $total_liabilities + abs($bharti_balance_amount);
    }

  $liabilities = $liabilities + $total_wallet_balance;  

?>

<div class="wrapper">  
  <div class="content-wrapper">
    <section class="content-header">
    </section>
    <div class="container">
      <div class="row" style="border:5px solid #087cc6; padding: 10px 0; margin-bottom: 20px; ">
        <div class="col-md-12">
          
          <p><a href="" class="btn btn-primary" data-toggle="modal" data-target="#TargetModal">Add Target</a></p>
          
          <table class="table table-bordered table-striped bg-white text-center colored_tbl">
            <thead>
              <tr>
                <td colspan="11" class="headtitle">
                  <div class="text-center">
                    <h2>TARGET VS ACHIVEMENT</h2>
                  </div>
                </td>
              </tr>
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
                <td><?=round((($td_count/$target_data['td'])*100),2)?></td>
                <td><?=round((($mtd_count/$target_data['mtd'])*100),2)?></td>
                <td><?=round((($ytd_count/$target_data['ytd'])*100),2)?></td>                
              </tr>
              <tr>
                <td></td>
                <td><b>TOTAL</b></td>
                <td><?=$target_data['td']?></td>
                <td><?=$target_data['mtd']?></td>
                <td><?=$target_data['ytd']?></td>
                <td><?=$td_count?></td>
                <td><?=$mtd_count?></td>
                <td><?=$ytd_count?></td>
                <td><?=round((($td_count/$target_data['td'])*100),2)?></td>
                <td><?=round((($mtd_count/$target_data['mtd'])*100),2)?></td>
                <td><?=round((($ytd_count/$target_data['ytd'])*100),2)?></td>                
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-md-12">
          
          <table class="table table-bordered table-striped bg-white text-center colored_tbl">
            <thead>
              <tr>
                <td colspan="11" class="headtitle">
                  <div class="text-center">
                    <h2>REVENUE & EXPENDITURE</h2>
                  </div>
                </td>
              </tr>
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
                <td> <?=$td_icpl_margin + $td_giib_margin;?> </td>
                <td> <?=$mtd_icpl_margin + $mtd_giib_margin;?> </td>
                <td> <?=$ytd_icpl_margin + $ytd_giib_margin;?> </td>
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
                <td> <?=$td_icpl_margin + $td_giib_margin;?> </td>
                <td> <?=$mtd_icpl_margin + $mtd_giib_margin;?> </td>
                <td> <?=$ytd_icpl_margin + $ytd_giib_margin;?> </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-md-12">
          <table class="table table-bordered table-striped bg-white text-center colored_tbl">
            <thead>
              <tr>
                <td colspan="11" class="headtitle">
                  <div class="text-center">
                    <h2>RECEIPT & PAYMENT</h2>
                  </div>
                </td>
              </tr>
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
                <td><?=$td_receipt_deposit_amount?></td>
                <td><?=$mtd_receipt_deposit_amount?></td>
                <td><?=$ytd_receipt_deposit_amount?></td>
                <td><?=$party_td_payment?></td>
                <td><?=$party_mtd_payment?></td>
                <td><?=$party_ytd_payment?></td>
                <td><?=$td_receipt_deposit_amount - $party_td_payment?></td>
                <td><?=$mtd_receipt_deposit_amount - $party_mtd_payment?></td>
                <td><?=$ytd_receipt_deposit_amount - $party_ytd_payment?></td>
              </tr>
              <tr>
                <td></td>
                <td><b>TOTAL</b></td>
                <td><?=$td_receipt_deposit_amount?></td>
                <td><?=$mtd_receipt_deposit_amount?></td>
                <td><?=$ytd_receipt_deposit_amount?></td>
                <td><?=$party_td_payment?></td>
                <td><?=$party_mtd_payment?></td>
                <td><?=$party_ytd_payment?></td>
                <td><?=$td_receipt_deposit_amount - $party_td_payment?></td>
                <td><?=$mtd_receipt_deposit_amount - $party_mtd_payment?></td>
                <td><?=$ytd_receipt_deposit_amount - $party_ytd_payment?></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-md-12">
          <table class="table table-bordered table-striped bg-white text-center colored_tbl">
            <thead>
              <tr>
                <td colspan="5" class="headtitle">
                  <div class="text-center">
                    <h2>ASSETS & LIABILITIES</h2>
                  </div>
                </td>
              </tr>
              <tr>
                <th width="7%" rowspan="2" style="vertical-align: middle;">SR. NO.</th>
                <th width="23%" rowspan="2" style="vertical-align: middle; text-align: left;">PARTICULAR</th>
                <th>LIABILITIES </th>
                <th>ASSETS</th>              
                <th>NET WORTH</th>              
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1.</td>
                <td style="text-align: left;">RSA</td>
                <td><?=$liabilities?></td>
                <td><?=$assets?></td>
                <td><?=$assets - $liabilities;?></td>
              </tr>
              <tr>
                <td></td>
                <td><b>TOTAL</b></td>
                <td><?=$liabilities?></td>
                <td><?=$assets?></td>
                <td><?=$assets - $liabilities;?></td>
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

<script type="text/javascript">
  $('.close_btn').on('click',function(){
      $('#target_form').trigger('reset');
  });
</script>