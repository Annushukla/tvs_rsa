<?php
$cust_veh_details = $this->session->userdata('customer_and_vehicle_details');
$form_data = $this->session->userdata('form_data');

$product_type = isset($cust_veh_details['product_type']) ? $cust_veh_details['product_type'] : '';
$engine_no = isset($cust_veh_details['engine_no']) ? $cust_veh_details['engine_no'] : $form_data['engine_no'];
$chassis_no = isset($cust_veh_details['chassis_no']) ? $cust_veh_details['chassis_no'] : $form_data['chassis_no'];
$manufacturer = isset($cust_veh_details['manufacturer']) ? $cust_veh_details['manufacturer'] : $form_data['manufacturer'];
$model = isset($model['model_name']) ? $model['model_name'] : '';
//echo '<pre>';
//echo $model;

$model = isset($cust_veh_details['model']) ? $cust_veh_details['model'] : $model;
//echo $model;
//die;
$model_id = isset($cust_veh_details['model_id']) ? $cust_veh_details['model_id'] : '';
$odometer_readng = isset($cust_veh_details['odometer_readng']) ? $cust_veh_details['odometer_readng'] : '';
$fuel_type = isset($cust_veh_details['fuel_type']) ? $cust_veh_details['fuel_type'] : '';
$mfg_date = isset($cust_veh_details['mfg_date']) ? $cust_veh_details['mfg_date'] : '';
$color = isset($cust_veh_details['color']) ? $cust_veh_details['color'] : '';
$registration_no = isset($cust_veh_details['registration_no']) ? $cust_veh_details['registration_no'] : '';
$rto_name = isset($cust_veh_details['reg1']) ? $cust_veh_details['reg1'] : $form_data['rto_name'];
$rto_code1 = isset($cust_veh_details['reg2']) ? $cust_veh_details['reg2'] : $form_data['rto_code1'];
$rto_code2 = isset($cust_veh_details['reg3']) ? $cust_veh_details['reg3'] : $form_data['rto_code2'];
$reg_no = isset($cust_veh_details['reg4']) ? $cust_veh_details['reg4'] : $form_data['reg_no'];
//$rto_name = isset($form_data['rto_name']) ? $form_data['rto_name'] : '';
//$rto_code1 = isset($form_data['rto_code1']) ? $form_data['rto_code1'] : '';
//$rto_code2 = isset($form_data['rto_code2']) ? $form_data['rto_code2'] : '';
//$reg_no = isset($form_data['reg_no']) ? $form_data['reg_no'] : '';




$first_name = isset($cust_veh_details['first_name']) ? $cust_veh_details['first_name'] : $form_data['first_name'];
$last_name = isset($form_data['last_name']) ? $form_data['last_name'] : $cust_veh_details['last_name'];
$email = isset($form_data['email']) ? $form_data['email'] : $cust_veh_details['email'];
$mobile_no = isset($cust_veh_details['mobile_no']) ? $cust_veh_details['mobile_no'] : $form_data['mobile_no'];
$cust_addr1 = isset($cust_veh_details['cust_addr1']) ? $cust_veh_details['cust_addr1'] : $form_data['cust_addr1'];
$cust_addr2 = isset($cust_veh_details['cust_addr2']) ? $cust_veh_details['cust_addr2'] : $form_data['cust_addr2'];
$session_state = isset($cust_veh_details['state']) ? $cust_veh_details['state'] : $form_data['state'];
$session_city = isset($cust_veh_details['city']) ? $cust_veh_details['city'] : $form_data['city'];
$pin = isset($cust_veh_details['pin']) ? $cust_veh_details['pin'] : $form_data['pin'];
$product_type_id = isset($cust_veh_details['product_type_id']) ? $cust_veh_details['product_type_id'] : $form_data['product_type_id'];

$readonly = '';
$disable = '';
if (!empty($engine_no)) {
    $readonly = 'readonly';
    $disable = 'disabled';
}
//if (!empty($session_state)) {
//    $state_readonly = 'readonly';
//    $disable = 'disabled';
//}
?>

<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Calibri" rel="stylesheet">



<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">

<style type="text/css">
    /*    .modal-backdrop {
        z-index: 10 !important;
    }
    .modal-dialog {
        margin: 2px auto;
        z-index: 12 !important;
    }*/
</style>


<main class="section--lightGray main-ewnow" style="  font-family: 'Roboto';">
    <header class="custom-pacoverheader text-center">


        <p>RoadSide Assistance</p>
        <h2 class="banner-small-text">Roadside Assistance is a 24x7 emergency service  <br/>which is offered in case of breakdown of a vehicle</h2>
    </header>
    <div class="container marT20 margnB5">
        <div class="row">
            <input type="hidden" id="policy_id" value="<?= $policy_id ?>" name="policy_id">
            <div class="col-md-12 custom-bg-dark text-center">


                <div class="col-md-12 white-bg" style="height:  auto;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row" style="padding:10px 0;">
                                <div class="col-md-9 text-left">
                                    <h3 style="color: #333;">Search customer by vehicle’s Engine No. if it exists in our database</h3>
                                </div>
                                <div class="text-right col-md-3">
                                    <button id="reset_data" class="btn btn-primary button_purple" name="reset_data">RESET</button>
                                </div>
                            </div>
                            <form class="form-horizontal" id="vehicle_details_form" action="#">
                                <div id="custom-search-input">
                                    <div class="input-group col-md-12 border-light">
                                        <input type="text" class="search-query searchbox" name="vehicle_detail" id="vehicle_detail" placeholder="Enter customer’s Vehicle’s Engine No." value="<?php echo $engine_no ?>" style="color:black;">

                                        <span class="input-group-btn">
                                            <button class="btn btn-danger" type="button" id="search_button">
                                                <span class=" glyphicon glyphicon-search"></span>
                                            </button>
                                        </span>

                                    </div>
                                    <div class=" ">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <p id="exist_policy" style="color: #fff;font-weight: bolder;font-size: 14px;background-color: #f00;margin: 10px 0;"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <b style="color: red;font-size:15px;padding-top: 4px; display: none;" id="error_msg"></b>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="cust_info_panel">
                        <div id="cust_info" class="custom-ewnow-form">
                            <div class="col-md-12 form-blue">
                                <form method="post" action="<?php echo base_url('generated_policy_data'); ?>" autocomplete="off" class="form-horizontal" role="form" id="vehcile_data_formm">
                                    <div class=" ">
                                        <div class="col-md-12 text-left nopadding" style="position: relative;"><h5>Vehicle Info</h5>
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
                                                    <label for="engine_no" class="col-sm-3 control-label">Engine number
                                                        <span style="color:red" >*</span>
                                                    </label>

                                                    <div class="col-sm-9">
                                                        <input type="text" name="engine_no" id="engine_no" placeholder="ENGINE NO." class="form-control cust_info_field keypress_validation" value="<?php echo $engine_no; ?>" data-regex="" autofocus style="text-transform:uppercase" required <?php echo $readonly; ?> >
                                                        <span id="error-engine_no" style="color: red"></span>
                                                        <span style="color:red;"><?php echo $this->session->flashdata('er_engin_no'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="chassis_no" class="col-sm-3 control-label">Chassis Number
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="chassis_no" id="chassis_no" placeholder="CHASSIS NO." value="<?php echo $chassis_no; ?>" class="form-control cust_info_field" minlength="17" maxlength="17" autofocus style="text-transform:uppercase" required <?php echo $readonly; ?> >
                                                        <span id="error-chassis_no" style="color: red"></span>
                                                        <span style="color:red;"><?php echo $this->session->flashdata('er_chassis'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group" id="manufacturer_cont">
                                                    <label for="manufacturer" class="col-sm-3 control-label">Manufacturer
                                                        <span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                        <select name="manufacturer" id="manufacturer" name="manufacturer" class="form-control cust_info_field" disabled="">
                                                            <option value="">SELECT MANUFACTURER</option>
                                                            <option value="11" selected>TVS</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" id="model_cont">
                                                    <label for="model" class="col-sm-3 control-label">Model
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <select name="model_id" id="model_id" class="form-control cust_info_field">


                                                        </select>
                                                        <span id="error-model_id" style="color: red"><?php echo $this->session->flashdata('er_model_id'); ?></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-12">


                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="registration_no" class="col-sm-3 control-label">Registration no.
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-2">
                                                        <input  type="text" id="rto_name"  value="<?php echo $rto_name; ?>" name="rto_name" placeholder="MH" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="2">
                                                        <span id="error-rto_name" style="color: red"><?php echo $this->session->flashdata('er_rto_name'); ?></span>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input  type="text" id="rto_code1"  value="<?php echo $rto_code1; ?>" name="rto_code1" placeholder="01" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="2">
                                                        <span id="error-rto_code1" style="color: red"><?php echo $this->session->flashdata('er_rto_code1'); ?></span>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input  type="text" id="rto_code2"  value="<?php echo $rto_code2; ?>" name="rto_code2" placeholder="AB" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="3">
                                                        <span id="error-rto_code2" style="color: red"><?php echo $this->session->flashdata('er_rto_code2'); ?></span>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input  type="text" id="reg_no"  value="<?php echo $reg_no; ?>" name="reg_no" placeholder="6831" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="4">
                                                        <span id="error-reg_no" style="color: red"><?php echo $this->session->flashdata('er_reg_no'); ?></span>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="first_name" class="col-sm-3 control-label">First name
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="first_name"  value="<?php echo $first_name; ?>" name="first_name" placeholder="FIRST NAME" class="form-control cust_info_field" autofocus style="text-transform:uppercase">
                                                        <span id="error-first_name" style="color: red"><?php echo $this->session->flashdata('er_first_name'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="last_name" class="col-sm-3 control-label">Last Name
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="last_name" name="last_name" placeholder="LAST NAME"  value="<?php echo $last_name; ?>" class="form-control cust_info_field" autofocus style="text-transform:uppercase">
                                                        <span id="error-last_name" style="color: red"><?php echo $this->session->flashdata('er_last_name'); ?></span>
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
                                                    <label for="mobile_no" class="col-sm-3 control-label">Mobile number
                                                        <span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                        <input maxlength="10" size="10" type="text" id="mobile_no" name="mobile_no" placeholder="MOBILE NO." class="form-control cust_info_field"  value="<?php echo $mobile_no; ?>" autofocus>
                                                        <span id="error-mobile_no" style="color: red"><?php echo $this->session->flashdata('er_mobile_no'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Address 1<span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="cust_addr1" name="cust_addr1" placeholder="ADDRESS 1" class="form-control cust_info_field"  value="<?php echo $cust_addr1; ?>" autofocus style="text-transform:uppercase">
                                                        <span id="error-cust_addr1" style="color: red"><?php echo $this->session->flashdata('er_cust_addr1'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Address 2</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="cust_addr2" name="cust_addr2" placeholder="ADDRESS 2" class="form-control cust_info_field"  value="<?php echo $cust_addr2; ?>" autofocus style="text-transform:uppercase">
                                                        <span id="error-cust_addr2" style="color: red"><?php echo $this->session->flashdata('er_cust_addr2'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pin" class="col-sm-3 control-label">Pin</label>
                                                    <div class="col-sm-9">
                                                        <input maxlength="6" size="6" type="text" id="pin" name="pin" placeholder="ENTER PIN" class="form-control cust_info_field" value="<?php echo $pin; ?>" autofocus>
                                                        <span id="error-pin" style="color: red"><?php echo $this->session->flashdata('er_pin'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">State<span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="state" name="state" placeholder="STATE" class="form-control cust_info_field"  value="<?php echo $state; ?>" autofocus style="text-transform:uppercase" >
                                                        <span id="error-state" style="color: red"><?php echo $this->session->flashdata('er_state'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">City<span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="city" name="city" placeholder="CITY" class="form-control cust_info_field"  value="<?php echo $city; ?>" autofocus style="text-transform:uppercase" >
                                                        <span id="error-city" style="color: red"><?php echo $this->session->flashdata('er_city'); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                     <div class=" ">
                                        <div class="col-md-12 text-left nopadding" style="position: relative;"><h5>Payment Mode</h5>
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
                                                    <label for="engine_no" class="col-sm-3 control-label">Payment Mode
                                                        <span style="color:red" >*</span>
                                                    </label>

                                                    <div class="col-sm-9">
                                                        <select class="form-control" id="payment_mode" name="payment_mode" >
                                                           <option value="">Select Payment Mode</option>
                                                           <option value="cheque">Cheque Payment</option>
                                                           <option value="neft">NEFT Payment</option>
                                                           <option value="cash">Cash Payment</option>
                                                           <option value="paytm">Paytm Mode</option>
                                                       </select>
                                                        <span id="error-payment_mode" style="color: red"></span>
                                                        <span style="color:red;"></span>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                        </div>
                                    </div>

                                <div id="cheque_form_div" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bank_name" class="col-sm-3 control-label">Bank Name
                                                        <span style="color:red" >*</span>
                                                    </label>

                                                    <div class="col-sm-9">
                                                        <input type="text" name="bank_name" id="bank_name" placeholder="Bank Name" class="form-control cust_info_field keypress_validation" value="" data-regex="" autofocus style="text-transform:uppercase" >
                                                        <span id="error-bank_name" style="color: red"></span>
                                                        <span style="color:red;"><?php echo $this->session->flashdata('er_bank_name');?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="cheque_no" class="col-sm-3 control-label">Cheque No.
                                                        <span style="color:red" >*</span>
                                                    </label>

                                                    <div class="col-sm-9">
                                                        <input type="text" name="cheque_no" id="cheque_no" placeholder="Cheque No." class="form-control cust_info_field keypress_validation" value="" data-regex="" autofocus style="text-transform:uppercase" >
                                                        <span id="error-cheque_no" style="color: red"></span>
                                                        <span style="color:red;"><?php echo $this->session->flashdata('er_cheque_no');?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                           <!--  <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="ifsc_code" class="col-sm-3 control-label">IFSC Code
                                                        <span style="color:red" >*</span>
                                                    </label>

                                                    <div class="col-sm-9">
                                                        <input type="text" name="ifsc_code" id="ifsc_code" placeholder="IFSC Code" class="form-control cust_info_field keypress_validation" value="" data-regex="" autofocus style="text-transform:uppercase" required >
                                                        <span id="error-ifsc_code" style="color: red"></span>
                                                        <span style="color:red;"></span>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="cheque_date" class="col-sm-3 control-label">Cheque Date
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="cheque_date" id="cheque_date" placeholder="Cheque Date" value="" class="form-control cust_info_field" autofocus style="text-transform:uppercase" required  >
                                                        <span id="error-cheque_date" style="color: red"></span>
                                                        <span style="color:red;"><?php echo $this->session->flashdata('er_cheque_date');?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div id="neft_form_div" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="neft_bank_name" class="col-sm-3 control-label">Bank Name
                                                        <span style="color:red" >*</span>
                                                    </label>

                                                    <div class="col-sm-9">
                                                        <input type="text" name="neft_bank_name" id="neft_bank_name" placeholder="Bank Name" class="form-control cust_info_field keypress_validation" value="" data-regex="" autofocus style="text-transform:uppercase" required >
                                                        <span id="error-neft_bank_name" style="color: red"></span>
                                                        <span style="color:red;"><?php echo $this->session->flashdata('er_neft_bank_name');?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="neft_no" class="col-sm-3 control-label">NEFT No.
                                                        <span style="color:red" >*</span>
                                                    </label>

                                                    <div class="col-sm-9">
                                                        <input type="text" name="neft_no" id="neft_no" placeholder="NEFT No." class="form-control cust_info_field keypress_validation" value="" data-regex="" autofocus style="text-transform:uppercase" required >
                                                        <span id="error-neft_no" style="color: red"></span>
                                                        <span style="color:red;"><?php echo $this->session->flashdata('er_neft_no');?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                          
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="neft_date" class="col-sm-3 control-label">NEFT Date
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="neft_date" id="neft_date" placeholder="NEFT Date" value="" class="form-control cust_info_field" autofocus style="text-transform:uppercase" required  >
                                                        <span id="error-neft_date" style="color: red"></span>
                                                        <span style="color:red;"><?php echo $this->session->flashdata('er_neft_date');?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            <div id="cash_div" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="cash_comment" class="col-sm-3 control-label">Comment
                                                        <span style="color:red" >*</span>
                                                    </label>

                                                    <div class="col-sm-9">
                                                        <textarea id="cash_comment" name="cash_comment" rows="5" cols="15" class="form-control cust_info_field keypress_validation" placeholder="Comment here" autofocus style="text-transform:uppercase;" required></textarea>
                                                        <span id="error-cash_comment" style="color: red"></span>
                                                        <span style="color:red;"><?php echo $this->session->flashdata('er_cash_comment');?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>

                                    </div>
                                </div>

                            <div id="paytm_div" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="paytm_transaction_no" class="col-sm-3 control-label">Transaction No
                                                        <span style="color:red" >*</span>
                                                    </label>

                                                    <div class="col-sm-9">
                                                        <input type="text" name="transaction_no" id="transaction_no" placeholder="Transaction No" value="" class="form-control cust_info_field" autofocus style="text-transform:uppercase" required  >
                                                        <span id="error-transaction_no" style="color: red"></span>
                                                        <span style="color:red;"><?php echo $this->session->flashdata('er_transaction_no');?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>

                                    </div>
                                </div>

                                    <!--  <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="engine_no" class="col-sm-3 control-label">Bank Name
                                                        <span style="color:red" >*</span>
                                                    </label>

                                                    <div class="col-sm-9">
                                                        <input type="text" name="engine_no" id="engine_no" placeholder="Bank Name" class="form-control cust_info_field keypress_validation" value="" data-regex="" autofocus style="text-transform:uppercase" required >
                                                        <span id="error-engine_no" style="color: red"></span>
                                                        <span style="color:red;"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="chassis_no" class="col-sm-3 control-label">Cheque Number
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="chassis_no" id="chassis_no" placeholder="CHASSIS NO." value="" class="form-control cust_info_field" autofocus style="text-transform:uppercase" required  >
                                                        <span id="error-chassis_no" style="color: red"></span>
                                                        <span style="color:red;"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->



                            </div>



                            <div class="col-md-12 text-right padng-B">
                                <div class="col-md-12">
                                    <button type="button" id="pa_cover_generate_button" class="btn btn-primary button_purple">Generate Policy</button>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" style="  border-radius: 0px;">
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
</div>
<input type="hidden" id="seleted_manufacturin_month" name="$seleted_manufacturin_month" value="">
<input type="hidden" id="make_hidden" value="">
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/generate_pa_policy.js"></script>
