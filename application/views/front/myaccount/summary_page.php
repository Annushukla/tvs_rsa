<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

<!-- thimbnails -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/demo.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/set1.css">
<div class="page main-ewnow">
    <div class="container adminpage-wrap">
        <div class="col-md-3">
            <div class="adminpage-sidebar">
                <ul>
                    <?php 
                    $sap_ad_code = $this->session->user_session['sap_ad_code'];
                    $is_display = (strlen($sap_ad_code) ==5) || in_array($sap_ad_code, array('1011591','1010964'))?'block':'none';
                    ?>

                    <li class="" style="display: <?=$is_display?>"><a href="<?php echo base_url('dealer_request_data');?>"> Dealer Request Data</a></li>
                    <li class="active"><a href="<?php echo base_url('summary_page');?>"> Summary</a></li>
                    <li class="" style="display: <?=$is_display?>"><a href="<?php echo base_url('myDashboardSection');?>"> Dealer Bank Transaction </a></li>
                    <li class="" style="display: <?=$is_display?>"><a href="<?php echo base_url('transaction_data');?>"> Transaction Data </a></li>
                    <li class="" style="display: <?=$is_display?>"><a href="<?php echo base_url('generated_invoice');?>"> Generated Invoice </a></li>
                    <li class="" style="display: <?=$is_display?>"><a href="<?php echo base_url('gst_transanction');?>"> GST Transaction </a></li>
                </ul>      
            </div>         
        </div>
      
        <div class="col-md-9">
            <div class="adminpage-content">  
            <div class="row form-group">
                            <span id="er_msg" style="color: red;"></span>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                From : <input type="" name="from_date" id="from_date" class="form-control" placeholder="Select date">
                                <span style="color: red" id="er_from_date"></span>
                            </div>
                            <div class="col-md-4">
                                TO : <input type="" name="to_date" id="to_date" class="form-control" placeholder="Select Date">
                                <span style="color: red" id="er_to_date"></span>
                            </div>
                            <div class="col-md-4">
                                <br><button type="" class="btn btn-primary" id="download_csv">Download</button>
                            </div>

                        </div>
                    </div>   
                <h3 class="adminpage-pagehead">Dealer Policy Summary</h3>
                <div class="adminpage-texter">
                    <div class="row">
                        <div class="col-md-12">
                            <p>Do You Want Commision In Your Bank?</p>
                            <input type="radio" class="commission_parameter" id="commission_yes" name="commission_parameter" value="1" <?php echo ($is_allowed_commission_to_bank == 1)?'checked="checked"':''?>> Yes<br>
                            <input type="radio" class ="commission_parameter" id="commission_no" name="commission_parameter" value="0" <?php echo ($is_allowed_commission_to_bank == 0)?'checked="checked"':''?>> No<br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="transaction_data-mainwrap">
                                <div class="dataTables_wrapper">
                                    <table class="table table-sm table-striped" border="0">
                                        <thead class="summary_tblhead">
                                            <th>Total Policies</th>
                                            <th>Policy Basic Premium</th>
                                            <th>Policy GST Amount</th>
                                            <th>Policy Gross Premium</th>
                                            <th>Total Available Balance</th>
                                            <?php if($is_allowed_commission_to_bank == 1){?>
                                                <th>Total Commission Amount</th>
                                            <?php }?>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <td><?php echo ($dealer_transactn_data[0]['policy_count']) ? $dealer_transactn_data[0]['policy_count'] : ''  ?> </td>
                                            <?php 
                                                $policy_amount_without_gst = $dealer_transactn_data[0]['sum_policy_amount_without_tax'];

                                                $policy_amount_gst = $policy_amount_without_gst * 0.18;
                                                 $gross_premium = ($policy_amount_without_gst + $policy_amount_gst);
                                            ?>
                                            <td><?php echo ($policy_amount_without_gst) ? round($policy_amount_without_gst,2) : 0  ?> </td> 
                                            <td><?php echo ($policy_amount_gst) ? round($policy_amount_gst,2) : 0  ?> </td> 
                                            <td><?php echo ($gross_premium) ? round($gross_premium) : 0  ?> </td> 
                                            <td><?php echo ($wallet_balance) ? round($wallet_balance,2) : 0  ?> </td>  
                                            <?php if($is_allowed_commission_to_bank == 1){?>  
                                                <td><?php echo ($total_commission) ? round($total_commission,2) : 0  ?> </td>    
                                            <?php }?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        
        </div>
    </div>    
</div>
