<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<!-- thimbnails -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/demo.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/set1.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dealer_transaction.js"></script>

    <?php 
        $sap_ad_code = str_pad($user_session['sap_ad_code'], 8, '0', STR_PAD_LEFT);
    ?>

<div class="modal fade bd-example-modal-lg" id="new_payment" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="padding: 20px; font-size: 18px;">
      Dear Dealer,<br>
      We've recently changed our <b style="color: green;">Bank account details.</b><br>
      Kindly use the below mentioned <b style="color: green;">Bank account details</b> for transactions.<br><br>
      <!-- <center><strong>New Bank Ac Number : ICCP<?=$sap_ad_code;?></strong> </center><br><br> -->
      <table class="table-striped" align="center">
        <tbody style="font-size: 15px;">
            <tr> <td><strong>Bank Ac Number</strong></td> <td style="background-color: #fbff00;"><strong>ICCP<?=$sap_ad_code;?></strong></td> </tr>
            <tr> <td><strong>Beneficiary Name</strong></td> <td><strong>INDICOSMIC CAPITAL PVT LTD</strong></td> </tr>
            <tr> <td><strong>Bank Name</strong></td> <td><strong>ICICI BANK</strong></td> </tr>
            <tr> <td><strong>Bank Branch</strong></td> <td><strong>MIDC Andheri (E), Mumbai</strong></td> </tr>
            <tr> <td><strong>IFSC Code</strong></td> <td style="background-color: #fbff00;"><strong>ICIC0000104</strong></td> </tr>
            <tr> <td><strong>ICPL GST No.</strong></td> <td><strong>27AAECI3370G1ZN</strong></td> </tr>
        </tbody>
      </table><br>
      <center><button type="button" class="btn btn-success" data-dismiss="modal">OK</button></center>
    </div>
  </div>
</div>


<div class="page main-ewnow">

    <div class="container adminpage-wrap">
        <div class="col-md-3">
            <div class="adminpage-sidebar">
                <ul>
                    <li class=""><a href="<?php echo base_url('dealer_request_data');?>">Dealer Request Data</a></li>
                    <li class=""><a href="<?php echo base_url('summary_page');?>">Summary</a></li>
                    <li class="active"><a href="<?php echo base_url('myDashboardSection');?>"> Dealer Bank Transaction </a></li>
                    <li class=""><a href="<?php echo base_url('transaction_data');?>"> Transaction Data </a></li>
                    <li class=""><a href="<?php echo base_url('generated_invoice');?>"> Generated Invoice </a></li>
                    <li class=""><a href="<?php echo base_url('gst_transanction');?>"> GST Transaction </a></li>
                    <li class=""><a href="<?php echo base_url('renewal_policy');?>"> Policy Renewal </a></li>
                    <li class=""><a href="<?php echo base_url('expired_policies');?>">Expired Policies </a></li>
                </ul>   
            </div>         
        </div>
        <div class="col-md-9">
            <div class="adminpage-content">
                <h3 class="adminpage-pagehead">Dealer Bank Transaction</h3>
                <h1 id="message" style="color:red">
                    <?php echo $this->session->flashdata('message');?>
                </h1>
                <div class="adminpage-texter">
                    <form method="post" action="<?php echo base_url('dealer_transaction_post');?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_no" class="col-sm-4 control-label">Transaction Type
                                        <span style="color:red" >*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="transaction_type" id="transaction_type" required>
                                            <option value="">Select</option>
                                            <option value="deposit" <?php echo ($dealer_bank_data['transaction_type']=='deposit') ? 'selected' : '' ?> >Deposit</option>
                                            <option value="withdrawal" <?php echo ($dealer_bank_data['transaction_type']=='withdrawal') ? 'selected' : '' ?> >Withdrawal</option>
                                        </select>
                                        <span id="error-transaction_type" style="color: red"></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_name" class="col-sm-4 control-label">Bank Name
                                        <span style="color:red" >*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_name" id="bank_name" placeholder="Bank Name." class="form-control keypress_validation" value="<?php echo isset($user_session['bank_name'])?$user_session['bank_name']:''?>" data-regex="" autofocus style="text-transform:uppercase" required readonly="readonly">
                                        <span id="error-bank_name" style="color: red"></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                
                            </div>
                             <div class="col-md-6">  
                                <div class="form-group">
                                    <label for="bank_name" class="col-sm-4 control-label">Bank Account No.
                                        <span style="color:red" >*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_acc" id="bank_acc" placeholder="Bank Account No." class="form-control keypress_validation" value="<?php echo isset($user_session['banck_acc_no'])?$user_session['banck_acc_no']:''?>" data-regex="" autofocus style="text-transform:uppercase" required readonly="readonly">
                                        <span id="error-bank_acc" style="color: red"></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-md-6 transactn_no_div">
                                <div class="form-group">
                                    <label for="transaction_no" class="col-sm-4 control-label">Bank Transaction No
                                        <span style="color:red" >*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="transaction_no" id="transaction_no" placeholder="Transaction NO." class="form-control keypress_validation" value="<?php echo $dealer_bank_data['bank_transaction_no'];?>" data-regex="" autofocus style="text-transform:uppercase" required >
                                        <span id="error-transaction_no" style="color: red"></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="col-md-6 ifsc_code_div" style="display:  none">
                                <div class="form-group">
                                    <label for="transaction_no" class="col-sm-4 control-label">IFSC Code
                                        <span style="color:red" >*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="ifsc_code" id="ifsc_code" placeholder="IFSC Code" class="form-control keypress_validation" value="<?php echo isset($user_session['banck_ifsc_code'])?$user_session['banck_ifsc_code']:''?>" data-regex="" autofocus style="text-transform:uppercase" required readonly="readonly">
                                        <span id="error-ifsc_code" style="color: red"></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="col-md-6 acc_name_div" style="display:  none">
                                <div class="form-group">
                                    <label for="acc_name" class="col-sm-4 control-label">Account Name
                                        <span style="color:red" >*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="acc_name" id="acc_name" placeholder="Account Name" class="form-control keypress_validation" value="<?php echo isset($user_session['banck_acc_name'])?$user_session['banck_acc_name']:''?>" data-regex="" autofocus style="text-transform:uppercase" required readonly="readonly">
                                        <span id="error-acc_name" style="color: red"></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="col-md-6 acc_type_div" style="display:  none">
                                <div class="form-group">
                                    <label for="acc_type" class="col-sm-4 control-label">Account Type
                                        <span style="color:red" >*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="acc_type" id="acc_type" required readonly="readonly">
                                            <option value="">Select</option>
                                            <option value="current" <?php echo ($dealer_bank_data['account_type'] == 'current')?'selected':''?> >Current</option>
                                            <option value="saving" <?php echo ($dealer_bank_data['account_type'] == 'saving')?'selected':''?> >Saving</option>
                                        </select>
                                        <span id="error-acc_type" style="color: red"></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bank_name" id="amt_typ_label" class="col-sm-4 control-label">Deposit Amount
                                    <span style="color:red" >*</span>
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" name="deposit_amount" id="deposit_amount" placeholder="Deposit Amount" class="form-control keypress_validation" value="<?php echo $dealer_bank_data['deposit_amount'];?>" data-regex="" autofocus style="text-transform:uppercase" required >
                                    <span id="error-deposit_amount" style="color: red"></span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_name" class="col-sm-4 control-label">Transaction Date
                                        <span style="color:red" >*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="transaction_date" id="transaction_date" class="form-control keypress_validation" value="<?php echo $dealer_bank_data['created_date'];?>" placeholder="" data-regex="" autofocus style="text-transform:uppercase" required readonly>
                                        <span id="error-transaction_date" style="color: red"></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="dealer_bank_trans_id" id="dealer_bank_trans_id" value="<?php echo $this->uri->segment(2);?>">
                                <input type="hidden" id="exist_transanction_er" name="" value="">
                                <button class="btn btn-success" id="dealer_transactn_submit" type="Submit">Submit</button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>    

    <div class="container adminpage-wrap">
        <div class="col-md-3">
            <div class="adminpage-sidebar">
               
            </div>         
        </div>

        <div class="col-md-9">
            <div class="adminpage-content">
                <h3 class="adminpage-pagehead">Indicosmic Bank Details</h3>
                <div class="adminpage-texter">
                   <span><strong>Beneficiary Name  &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;: </strong>INDICOSMIC CAPITAL PVT LTD</span><br> 
                   <span><strong>Bank Name   &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;: ICICI BANK</strong></span><br> 
                   <span><strong>Bank Branch  &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;: </strong> MIDC Andheri (E), Mumbai</span><br> 
                   <span><strong>Bank Ac Number &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;: ICCP<?=$sap_ad_code;?></strong> </span><br> 
                   <span><strong>IFSC Code    &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;: </strong>ICIC0000104 </span><br> 
                   <span><strong>ICPL GST No. &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;: 27AAECI3370G1ZN</strong> </span><br> 
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>  



</div>

<script>
    $(document).ready(function(){
        $('#new_payment').modal('show');
    });
</script>