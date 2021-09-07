<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Calibri" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">
<main class="section--lightGray main-ewnow" style="  font-family: 'Roboto';">
    <header class="custom-pacoverheader text-center">
        <p>EMPLOYEE DETAILS</p>
        <!-- <h2 class="banner-small-text">First Fill Dealers Details <br/>To Start Punching Policy.</h2> -->
    </header>
    <div class="container marT20 margnB5">
        <div class="row">
            <input type="hidden" id="policy_id" value="<?= $policy_id ?>" name="policy_id">
            <div class="col-md-12 custom-bg-dark text-center">


                <div class="col-md-12 white-bg" style="height:  auto;">
                    <div id="cust_info_panel">
                        <div id="cust_info" class="custom-ewnow-form">
                            <div class="col-md-12 form-blue">
                                <form method="post" id='submit_dealer_details' action="<?php echo base_url('submit_dealer_details'); ?>" autocomplete="off" class="form-horizontal" role="form" id="vehcile_data_formm">
                                    <div class=" ">
                                        <div class="col-md-12 text-left nopadding" style="position: relative;"><h5>EMPLOYEE DETAILS</h5>
                                        </div>
                                        <p id="error-message" style="color: red;
                                           position: absolute;
                                           top: 10px;
                                           right: 12px;
                                           font-weight: bold;"></p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="dealer_full_name" class="col-sm-3 control-label">First Name
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="dealer_full_name"  value="<?php //echo $first_name;   ?>" name="dealer_full_name" placeholder="Dealer NAME" class="form-control cust_info_field" autofocus style="text-transform:uppercase">
                                                        <span id="error-dealer_full_name" style="color: red"><?php echo $this->session->flashdata('er_dealer_full_name'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email" class="col-sm-3 control-label">Email
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="email" name="email" placeholder="EMAIL ID" class="form-control cust_info_field"  value="<?php echo $email; ?>" autofocus>
                                                        <span id="error-email" style="color: red"><?php echo $this->session->flashdata('er_email'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="company_name" class="col-sm-3 control-label">Dealership Name
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="company_name"  value="<?php //echo $first_name;   ?>" name="company_name" placeholder="Company Name" class="form-control cust_info_field" autofocus style="text-transform:uppercase">
                                                        <span id="error-company_name" style="color: red"><?php echo $this->session->flashdata('er_company_name'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Company Type<span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                       <select id="company_type" name="company_type" class="form-control cust_info_field company_type">
                                                           <option value="">Select Option</option>
                                                           <option value="saving">Partnership</option>
                                                           <option value="current">Current</option>
                                                       </select>>
                                                        <span id="error-company_type" style="color: red"><?php echo $this->session->flashdata('er_company_type'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mobile_no" class="col-sm-3 control-label">Mobile No.
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="mobile_no"  value="<?php //echo $first_name;   ?>" name="mobile_no" placeholder="Mobile No" class="form-control cust_info_field" autofocus style="text-transform:uppercase">
                                                        <span id="error-mobile_no" style="color: red"><?php echo $this->session->flashdata('er_mobile_no'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone_no" class="col-sm-3 control-label">Landline No.
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="phone_no"  value="<?php //echo $first_name;   ?>" name="phone_no" placeholder="Landline No" class="form-control cust_info_field" autofocus style="text-transform:uppercase">
                                                        <span id="error-phone_no" style="color: red"><?php echo $this->session->flashdata('er_phone_no'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            

                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tin_no" class="col-sm-3 control-label">Tin No
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="tin_no" id="tin_no" placeholder="Tin No." class="form-control cust_info_field keypress_validation" value="<?php //echo $engine_no;   ?>" data-regex="" autofocus style="text-transform:uppercase" required>
                                                        <span id="error-tin_no" style="color: red"></span>
                                                        <span style="color:red;"><?php //echo $this->session->flashdata('er_tin_no');   ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="gst_no" class="col-sm-3 control-label">GST NO.
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="gst_no" id="gst_no" placeholder="GST NO." value="<?php //echo $chassis_no;                                                ?>" class="form-control cust_info_field" autofocus style="text-transform:uppercase" required>
                                                        <span id="error-gst_no" style="color: red"></span>
                                                        <span style="color:red;"><?php echo $this->session->flashdata('er_gst_no'); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-12">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="aadhar_no" class="col-sm-3 control-label">Aadhar No.</label>
                                                    <div class="col-sm-9">
                                                        <input maxlength="12" size="12" type="text" id="aadhar_no" name="aadhar_no" placeholder="Aadhar No." class="form-control cust_info_field"  value="<?php //echo $mobile_no;   ?>" autofocus>
                                                        <span id="error-aadhar_no" style="color: red"><?php //echo $this->session->flashdata('er_aadhar_no');   ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Pan No.<span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="pan_no" name="pan_no" placeholder="Pan No." class="form-control cust_info_field"  value="<?php //echo $cust_addr1;   ?>" autofocus style="text-transform:uppercase">
                                                        <span id="error-pan_no" style="color: red"><?php echo $this->session->flashdata('er_pan_no'); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-12">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Address 1<span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="dealer_addr1" name="dealer_addr1" placeholder="ADDRESS 1" class="form-control cust_info_field"  value="<?php //echo $cust_addr1;   ?>" autofocus style="text-transform:uppercase">
                                                        <span id="error-dealer_addr1" style="color: red"><?php echo $this->session->flashdata('er_dealer_addr1'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Address 2</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="dealer_addr2" name="dealer_addr2" placeholder="ADDRESS 2" class="form-control cust_info_field"  value="<?php //echo $cust_addr2;   ?>" autofocus style="text-transform:uppercase">
                                                        <span id="error-dealer_addr2" style="color: red"><?php echo $this->session->flashdata('er_dealer_addr2'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pin" class="col-sm-3 control-label">Pin Code</label>
                                                    <div class="col-sm-9">
                                                        <input maxlength="6" size="6" type="text" id="pin" name="pin" placeholder="ENTER PIN" class="form-control cust_info_field" value="<?php echo $pin; ?>" autofocus>
                                                        <span id="error-pin" style="color: red"><?php echo $this->session->flashdata('er_pin'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">State<span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="state" name="state" placeholder="STATE" class="form-control cust_info_field"  value="<?php echo $state; ?>" autofocus style="text-transform:uppercase" readonly>
                                                        <span id="error-state" style="color: red"><?php echo $this->session->flashdata('er_state'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">City<span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="city" name="city" placeholder="CITY" class="form-control cust_info_field"  value="<?php echo $city; ?>" autofocus style="text-transform:uppercase" readonly>
                                                        <span id="error-city" style="color: red"><?php echo $this->session->flashdata('er_city'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class=" ">
                                        <div class="col-md-12 text-left nopadding" style="position: relative;"><h5>ACCOUNT DETAILS</h5>
                                        </div>
                                        <p id="error-message" style="color: red;
                                           position: absolute;
                                           top: 10px;
                                           right: 12px;
                                           font-weight: bold;"></p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">IFSC Code<span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="dealer_form_ifsc_code" name="ifsc_code" placeholder="IFSC Code" class="form-control cust_info_field"  value="<?php //echo $city;                             ?>" autofocus style="text-transform:uppercase" >
                                                        <span id="error-dealer_form_ifsc_code" style="color: red"><?php echo $this->session->flashdata('er_dealer_form_ifsc_code'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">BANK NAME<span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="bank_name" name="bank_name" placeholder="BANK NAME" class="form-control cust_info_field"  value="" autofocus style="text-transform:uppercase" readonly>
                                                        <span id="error-bank_name" style="color: red"><?php echo $this->session->flashdata('er_bank_name'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">ACC HOLDER'S NAME<span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="acc_holder_name" name="acc_holder_name" placeholder="ACCOUNT HOLDER'S NAME" class="form-control cust_info_field"  value="<?php //echo $city;   ?>" autofocus style="text-transform:uppercase" >
                                                        <span id="error-acc_holder_name" style="color: red"><?php echo $this->session->flashdata('er_acc_holder_name'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">ACCOUNT NO.<span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="account_no" name="account_no" placeholder="ACCOUNT NO" class="form-control cust_info_field"  value="<?php //echo $state;                                ?>" autofocus style="text-transform:uppercase" >
                                                        <span id="error-account_no" style="color: red"><?php echo $this->session->flashdata('er_account_no'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12"></div>
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Branch Address.<span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="branch_address" name="branch_address" placeholder="BRANCH ADDRESS" class="form-control cust_info_field"  value="<?php //echo $state; ?>" autofocus style="text-transform:uppercase"  readonly>
                                                        <span id="error-account_no" style="color: red"><?php echo $this->session->flashdata('er_branch_address'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Account Type<span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                       <select id="account_type" name="account_type" class="form-control cust_info_field account_type">
                                                           <option value="">Select Option</option>
                                                           <option value="saving">Saving</option>
                                                           <option value="current">Current</option>
                                                       </select>>
                                                        <span id="error-account_type" style="color: red"><?php echo $this->session->flashdata('er_account_type'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-right padng-B">
                                        <div class="col-md-12">
                                            <button type="button" id="dealer_details_submit_button" class="btn btn-primary button_purple">SUBMIT DETAILS</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">-->
                <!-- Modal content-->
                <!-- <div class="modal-content" style="  border-radius: 0px;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="    font-weight: bold;
                            color: #0090d3;">Generate Policy(Two Wheeler)</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row boxdiv">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b> Plan Amt:</b>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="plan_amt"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b> Plan Amt With GST:</b>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="plan_amt_gst"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <b>Note:"If incorrect Engine/Chassis number provided, claim will be repudiated"</b>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary  button_purple" id="confirm_policy_submit">Confirm</button>
                        <button type="button" class="btn button_purple button-dark" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div> -->
        <!-- <input type="hidden" id="seleted_manufacturin_month" name="$seleted_manufacturin_month" value="">
        <input type="hidden" id="make_hidden" value="">
        </main> -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/generate_pa_policy.js"></script>
