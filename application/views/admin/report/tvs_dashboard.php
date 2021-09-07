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
// $mtd_payment = $mtd_silver_purchase_total_amount + $mtd_gold_purchase_total_amount + $mtd_platinum_purchase_total_amount + $mtd_sapphire_purchase_total_amount + $rsa_mtd_silver_purchase_total_amount + $rsa_mtd_gold_purchase_total_amount + $rsa_mtd_platinum_purchase_total_amount + $rsa_mtd_sapphire_purchase_total_amount + $dc_mtd_silver_purchase_total_amount + $dc_mtd_gold_purchase_total_amount + $dc_mtd_platinum_purchase_total_amount + $dc_mtd_sapphire_purchase_total_amount + $tvs_mtd_silver_purchase_total_amount + $tvs_mtd_gold_purchase_total_amount + $tvs_mtd_platinum_purchase_total_amount + $tvs_mtd_sapphire_purchase_total_amount; 

// $td_payment = $td_silver_purchase_total_amount + $td_gold_purchase_total_amount + $td_platinum_purchase_total_amount + $td_sapphire_purchase_total_amount + $rsa_td_silver_purchase_total_amount + $rsa_td_gold_purchase_total_amount + $rsa_td_platinum_purchase_total_amount + $rsa_td_sapphire_purchase_total_amount + $dc_td_silver_purchase_total_amount + $dc_td_gold_purchase_total_amount + $dc_td_platinum_purchase_total_amount + $dc_td_sapphire_purchase_total_amount + $tvs_td_silver_purchase_total_amount + $tvs_td_gold_purchase_total_amount + $tvs_td_platinum_purchase_total_amount + $tvs_td_sapphire_purchase_total_amount;

// $ytd_payment = $ytd_silver_purchase_total_amount + $ytd_gold_purchase_total_amount + $ytd_platinum_purchase_total_amount + $ytd_sapphire_purchase_total_amount + $rsa_ytd_silver_purchase_total_amount + $rsa_ytd_gold_purchase_total_amount + $rsa_ytd_platinum_purchase_total_amount + $rsa_ytd_sapphire_purchase_total_amount + $dc_ytd_silver_purchase_total_amount + $dc_ytd_gold_purchase_total_amount + $dc_ytd_platinum_purchase_total_amount + $dc_ytd_sapphire_purchase_total_amount + $tvs_ytd_silver_purchase_total_amount + $tvs_ytd_gold_purchase_total_amount + $tvs_ytd_platinum_purchase_total_amount + $tvs_ytd_sapphire_purchase_total_amount;

//   $total_liabilities = $total_wallet_balance + $ytd_silver_policy_net_gst + $ytd_sapphire_policy_net_gst + $ytd_gold_policy_net_gst + $ytd_platinum_policy_net_gst + $ytd_sapphire_policy_tds + $ytd_silver_policy_tds + $ytd_platinum_policy_tds + $ytd_gold_policy_tds;

//     if($bharti_balance_amount<0){
//         $total_liabilities = $total_liabilities + abs($bharti_balance_amount);
//     }

//   $liabilities = $liabilities + $total_wallet_balance;  

?>

<div class="wrapper">  
  <div class="content-wrapper">
    <section class="content-header">
    </section>
    <div class="container">
      <div class="row" style="border:5px solid #087cc6; padding: 10px 0; margin-bottom: 20px; ">
        <div class="col-md-12">
          <table class="table table-bordered table-striped bg-white text-center colored_tbl">
            <thead>
              <tr>
                <td colspan="15" class="headtitle">
                  <div class="text-center">
                    <h2>TVS DASHBOARD</h2>
                  </div>
                </td>
              </tr>
              <tr>
                <th width="7%" rowspan="2" style="vertical-align: middle;">SR. NO.</th>
                <th width="23%" rowspan="2" style="vertical-align: middle; text-align: left;">ZONE</th>
                <th colspan="3">No. of New Policy</th>
                <th colspan="3">No. of Renewal Policy</th>
                <th colspan="3">No. of Total Policy</th>                
                <th colspan="3">Net  Premium(A)</th>                
              </tr>
              <tr>
                <th>FTD</th>
                <th>MTD</th>
                <th>YTD</th>
                <th>FTD</th>
                <th>MTD</th>
                <th>YTD</th>
                <th>FTD</th>
                <th>MTD</th>
                <th>YTD</th>
                <th>FTD</th>
                <th>MTD</th>
                <th>YTD</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1.</td>
                <td style="text-align: left;">RSA (In Nos.)</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>  
                <td></td>
                <td></td>
                <td></td>              
              </tr>
              <tr>
                <td></td>
                <td><b>TOTAL</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td> 
                <td></td>
                <td></td>
                <td></td>               
              </tr>
            </tbody>
          </table>
        </div>
  </div>

    </div>
  </div>
  <div class="control-sidebar-bg"></div>
</div>