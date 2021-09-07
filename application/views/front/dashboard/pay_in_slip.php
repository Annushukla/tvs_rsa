<?php $this->load->view('includes/datatable') ?>
<script src="<?php echo base_url(); ?>assets/js/sold_pa_policy.js"></script>
<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">
<main class="section--lightGray main-ewnow">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 align="left" class="heading-sold"> Generate Paying Slip RSA Policies</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="<?php echo base_url('view_generated_payslip'); ?>" class="btn btn-primary button_purple">View Generated Pay Slip</a>&nbsp;
                <a href="<?php echo base_url(); ?>dashboard"><button class="btn btn-primary button_purple my-2 my-sm-0" id="search_button" type="button">Back</button></a>
            </div>
        </div>
        <div class="row"><h3 style="color: #f21692;"></h3></div>
        <div class="row table100 ver1">
            <div class="col-md-12">
                <div class="bg-white border p-15">
                    <table class="table display table-striped data_table custom-datatable-soldpapolicy no-border" id="payin_policy_table">
                        <thead>
                        <th>SR.No. <input type="checkbox" id="sel_all_policies"></th>
                        <th>Product Type</th>
                        <th>Plan</th>
                        <th>Policy No.</th>
                        <th>Customer Name</th>
                        <th>Engine No.</th>
                        <th>Chassis No</th>
                        <th>Created Date</th>

                        </thead>

                    </table>
                </div>
                <br> <br>


                <div class="col-md-12 p-0 bg-white border">
                    <form id="generatepayingslip" method="post">
                        <div class="border-bottom p-15">
                          <!-- <label><input type="radio" name="payment_mode" value="dealer_cheque"> DEALER CHEQUE</label> -->

                            <label> <input type="radio" name="payment_mode" value="neft" checked> NEFT</label>
                        </div>
                        <div class="border-bottom p-15">
                            <div class="row">
                                <div class="col-md-12 p-0 p-tb-10">
                                    <div class="col-md-4">
                                        <label for="">Select Bank Name</label>
                                        <select name="bank_id" class="form-control input-sm" id="dealer_bank_id">
                                            <option value=""> Please Select Bank Name</option>
                                            <?php foreach ($bankmaster_details as $value) { ?>
                                                <option value="<?php echo $value->BankID ?>" >
                                                    <?php echo $value->BankName ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">IFSC Code</label>
                                        <input type="text" name="ifsc_code" value="" class="form-control input-sm" id="ifsc_code">
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Bank City</label>
                                        <input type="text" name="bank_city" class="form-control input-sm" value="" id="bank_city" >
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 p-0 p-tb-10 ">
                                    <div class="col-md-4">
                                        <label for="" id="changes_cheque_no">NEFT No</label>
                                        <input type="text" name="cheque_no" value="" class="form-control input-sm" id="cheque_no" >
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" id="changes_cheque_date">NEFT Date</label>
                                        <input type="text" readonly="" name="cheque_date" class="form-control input-sm" value="<?php echo date('d-m-Y') ?>" id=""  >
                                    </div>

                                    <div class="col-md-4">
                                        <label for="" id="">Total Amount</label>
                                        <input type="text" placeholder="Amount" name="amount" class="form-control input-sm" id="amount_value">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-2 p-tb-10">
                            <input type="hidden" name="ic_list" id="counts_ic_id" class="form-control" value="">
                            <input type="button" class="generate_paying_slip  btn btn-info" name="submit" onclick="generate_paying_slip()" value="generate paying slip">


                            <!--<a href="#" class="btn btn-info" id="pay_in_slip">Download </a>-->

                        </div>

                    </form>
                    <div class="col-md-2 p-tb-10">
                        <form action="<?= base_url('view_pdf_policy_data'); ?>" method="post">

                            <input type="hidden" id="counts_policy_id" name="counts_policy_id" value="">
                            <input type="hidden" placeholder="Amount" name="amount" id="amount_value_1">

                            <button class="btn btn-info" type="submit">Download</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</main>