
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Calibri" rel="stylesheet">
<!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/select2.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/select2-bootstrap.min.css"> -->

<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">

<style>
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {background-color: #f2f2f2;}
table, th, td {
     border: 0px;
    color: black;
}
</style>

<main class="section--lightGray main-ewnow" style="  font-family: 'Roboto';">
    <header class="custom-pacoverheader text-center">
        <p>RoadSide Assistance</p>
        <h2 class="banner-small-text">Roadside Assistance is a 24x7 emergency service  <br/>which is offered in case of breakdown of a vehicle</h2>
    </header>
    <div class="container marT20 margnB5">
        <div class="row">
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

                            <input type="hidden" name="page_name" value="renew_only_rsa" id="page_name" class="renew_only_rsa">
                            <input type="hidden" name="selected_dob" value="<?=$dob?>" id="selected_dob" class="selected_dob">
                            <form class="form-horizontal" id="abcd" action="#">
                                <div id="custom-search-input">
                                    <div class="input-group col-md-12 border-light">
                                        <input type="text" class="search-query searchbox" name="vehicle_detail" id="vehicle_detail" placeholder="Enter customer’s Vehicle’s Engine No." value="<?php echo $engine_no ?>" style="color:black;">

                                        <span class="input-group-btn">
                                            <button class="btn btn-danger" type="button" id="only_rsa_search_button">
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
                                <p id="message" style="color: red"><?php echo $this->session->flashdata('message')?></p>
                                <form method="post" action="<?php echo base_url('submit_renew_only_rsa_policy'); ?>" autocomplete="off" class="form-horizontal" role="form" id="onlyrsa_renewal_form">
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
                                                        <input type="text" name="engine_no" id="engine_no" placeholder="ENGINE NO." class="form-control cust_info_field keypress_validation" value="<?php echo $engine_no; ?>" data-regex="" minlength="5" maxlength="17" autofocus style="text-transform:uppercase" required <?php echo $readonly; ?> >
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
                                                <div class="form-group" id="vehicle_type_div">
                                                    <div class="col-sm-4">
                                                    <label for="vehicle_type" class="col-sm-6 control-label">Vehicle Type
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label for="vehicle_type" class="col-sm-6 control-label">
                                                        <input type="radio" name="vehicle_type" id="vehicle_type" class="vehicle_type" value="NEW" checked="checked"> New</label>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label for="vehicle_type" class="col-sm-6 control-label">
                                                        <input type="radio" name="vehicle_type" id="vehicle_type" class="vehicle_type" value="OLD"> Old</label>
                                                    </div>
                                                </div>
                                     </div>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group" id="manufacturer_cont">
                                                    <label for="manufacturer" class="col-sm-3 control-label">Manufacturer
                                                        <span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                        <select name="manufacturer" id="manufacturer" name="manufacturer" class="form-control cust_info_field" data-message = "Model" disabled="">
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
                                                        <select name="model_id" id="model_id" class="form-control cust_info_field" data-message = "Model" <?php echo $disabled ;?> >


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
                                                        <input  type="text" id="rto_name"  value="<?php echo $rto_name; ?>" name="rto_name" placeholder="MH" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="2" <?php echo $readonly; ?> >
                                                        <span id="error-rto_name" style="color: red"><?php echo $this->session->flashdata('er_rto_name'); ?></span>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input  type="text" id="rto_code1"  value="<?php echo $rto_code1; ?>" name="rto_code1" placeholder="01" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="2" <?php echo $readonly; ?> >
                                                        <span id="error-rto_code1" style="color: red"><?php echo $this->session->flashdata('er_rto_code1'); ?></span>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input  type="text" id="rto_code2"  value="<?php echo $rto_code2; ?>" name="rto_code2" placeholder="AB" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="3" <?php echo $readonly; ?> >
                                                        <span id="error-rto_code2" style="color: red"><?php echo $this->session->flashdata('er_rto_code2'); ?></span>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input  type="text" id="reg_no"  value="<?php echo $reg_no; ?>" name="reg_no" placeholder="6831" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="4" <?php echo $readonly; ?> >
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
                                                        <input type="text" id="first_name"  value="<?php echo $fname; ?>" name="first_name" placeholder="FIRST NAME" class="form-control cust_info_field" autofocus style="text-transform:uppercase">
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
                                                        <input type="text" id="last_name" name="last_name" placeholder="LAST NAME"  value="<?php echo $lname; ?>" class="form-control cust_info_field" autofocus style="text-transform:uppercase">
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
                                                <div class="form-group" id="gender_div">
                                                    <label for="model" class="col-sm-3 control-label">Gender
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <select name="gender" id="gender" class="form-control cust_info_field" data-message = "Gender">
                                                            <option value="">Select Gender</option>
                                                            <option value="male" <?php echo ($gender=='male')? 'selected' : '' ;?> >Male</option>
                                                            <option value="female" <?php echo ($gender=='female')? 'selected' : '' ;?> >Female</option>
                                                        </select>
                                                        <span id="error-gender" style="color: red"><?php echo $this->session->flashdata('er_gender'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mobile_no" class="col-sm-3 control-label">DOB
                                                        <span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="dob" name="dob" placeholder="DOB" class="form-control cust_info_field"  value="" readonly>
                                                        <span id="error-dob" style="color: red">
                                                    <?php echo $this->session->flashdata('er_dob'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Address 1<span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="cust_addr1" name="cust_addr1" placeholder="ADDRESS 1" class="form-control cust_info_field"  value="<?php echo $addr1; ?>" autofocus style="text-transform:uppercase">
                                                        <span id="error-cust_addr1" style="color: red"><?php echo $this->session->flashdata('er_cust_addr1'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Address 2</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="cust_addr2" name="cust_addr2" placeholder="ADDRESS 2" class="form-control cust_info_field"  value="<?php echo $addr2; ?>" autofocus style="text-transform:uppercase">
                                                        <span id="error-cust_addr2" style="color: red"><?php echo $this->session->flashdata('er_cust_addr2'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pin" class="col-sm-3 control-label">Pin</label>
                                                    <div class="col-sm-9">
                                                        <input maxlength="6" size="6" type="text" id="pin" name="pin" placeholder="ENTER PIN" class="form-control cust_info_field" value="<?php echo $pincode; ?>" autofocus>
                                                        <span id="error-pin" style="color: red"><?php echo $this->session->flashdata('er_pin'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">City<span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="hidden" id="city_id" name="city_id" class="form-control"  value="">
                                                        <input type="text" id="city" name="city" placeholder="CITY" class="form-control cust_info_field"  value="<?php echo $city; ?>" autofocus style="text-transform:uppercase" readonly = "readonly">
                                                        <span id="error-city" style="color: red"><?php echo $this->session->flashdata('er_city'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">State<span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="hidden" id="state_id" name="state_id" class="form-control"  value="">
                                                        <input type="text" id="state" name="state" placeholder="STATE" class="form-control cust_info_field"  value="<?php echo $state; ?>" autofocus style="text-transform:uppercase" readonly = "readonly">
                                                        <span id="error-state" style="color: red"><?php echo $this->session->flashdata('er_state'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                        </div>
                                    </div>
                                    <div class="row" id="nominee_div">
                                        <div class="col-md-12 text-left " style="position: relative;"><h5>Nominee Details</h5>
                                        </div>
                                        <p id="error-message" style="color: red;
                                           position: absolute;
                                           top: 10px;
                                           right: 12px;
                                           font-weight: bold;"></p>
                                        <div class="col-md-12 text-left ">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="first_name" class="col-sm-3 control-label">Nominee Full Name
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="nominee_full_name"  value="<?php echo $nominee_full_name; ?>" name="nominee_full_name" placeholder="NOMINEE FULL NAME" class="form-control cust_info_field" autofocus style="text-transform:uppercase">
                                                        <span id="error-nominee_full_name" style="color: red"><?php echo $this->session->flashdata('er_nominee_full_name'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="mobile_no" class="col-sm-3 control-label">Age
                                                        <span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                        <input maxlength="2" size="2" type="text" id="nominee_age" name="nominee_age" placeholder="NOMINEE AGE" class="form-control cust_info_field"  value="<?= $nominee_age ?>" autofocus>
                                                        <span id="error-nominee_age" style="color: red">
                                                    <?php echo $this->session->flashdata('er_nominee_age'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group" id="model_cont">
                                                    <label for="model" class="col-sm-3 control-label">Relation
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <select name="nominee_relation" id="nominee_relation" class="form-control cust_info_field" data-message = "Nominee Relation">
                                                             <option value="">Select Relationship</option>
                                                        <?php 
                                                            $nominee_relation_array = array(
                                                                'father'=>'Father',
                                                                'mother'=>'Mother',
                                                                'brother'=>'Brother',
                                                                'sister'=>'Sister',
                                                                'spouse'=>'Spouse',
                                                                'son'=>'Son',
                                                                'daughter'=>'Daughter',
                                                                'other'=>'Other'
                                                            );
                                                            foreach ($nominee_relation_array as $key => $nominee_rela) { 
                                                                $selected = ($key == $nominee_relation)?'selected = "selected"':'';

                                                                ?>
                                                                <option value="<?=$key?>" <?= $selected; ?> ><?=$nominee_rela?></option>
                                                         <?php   } ?>
                                                        </select>
                                                        <span id="error-nominee_relation" style="color: red"><?php echo $this->session->flashdata('er_nominee_relation'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">                                            
                                            <div class="col-md-4">
                                                <div class="form-group other_relation_div hide" id="model_cont">
                                                    <label for="model" class="col-sm-3 control-label">Other Relation
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="other_relation" id="other_relation" class="form-control " placeholder="Other Relation" >
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row" id="appointee_div" style="display: none;">
                                        <div class="col-md-12 text-left " style="position: relative;"><h5>Appointee Details</h5>
                                        </div>
                                        <p id="error-message" style="color: red;
                                           position: absolute;
                                           top: 10px;
                                           right: 12px;
                                           font-weight: bold;"></p>
                                        <div class="col-md-12 text-left ">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="first_name" class="col-sm-3 control-label">Appointee Full Name
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="appointee_full_name"  value="<?= $appointee_full_name ?>" name="appointee_full_name" placeholder="APPOINTEE FULL NAME" class="form-control cust_info_field" autofocus style="text-transform:uppercase" disabled>
                                                        <span id="error-appointee_full_name" style="color: red"><?php echo $this->session->flashdata('er_appointee_full_name'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" id="model_cont">
                                                    <label for="model" class="col-sm-3 control-label">Relation
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <select name="appointee_relation" id="appointee_relation" class="form-control cust_info_field" data-message = "Appointee Relation" disabled>
                                                            <option value="">Select Relationship</option>
                                                            <?php 
                                                            $appointee_relation_array = array(
                                                                'father'=>'Father',
                                                                'mother'=>'Mother',
                                                                'brother'=>'Brother',
                                                                'sister'=>'Sister',
                                                                'spouse'=>'Spouse',
                                                                'son'=>'Son',
                                                                'daughter'=>'Daughter'
                                                            );
                                                            foreach ($appointee_relation_array as $key => $appointee_rela) { 
                                                                $selected = ($key == $appointee_relation)?'selected = "selected"':'';

                                                                ?>
                                                                <option value="<?=$key?>" <?= $selected; ?> ><?=$appointee_rela?></option>
                                                         <?php   } ?>
                                                        </select>
                                                        <span id="error-appointee_relation" style="color: red"><?php echo $this->session->flashdata('er_appointee_relation'); ?></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-12">
                                                
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mobile_no" class="col-sm-3 control-label">Age
                                                        <span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                        <input maxlength="2" size="2" type="text" id="appointee_age" name="appointee_age" placeholder="APPOINTEE AGE" class="form-control cust_info_field"  value="<?= $appointee_age ?>" autofocus disabled>
                                                        <span id="error-appointee_age" style="color: red">
                                                    <?php echo $this->session->flashdata('er_appointee_age'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="row plandetails-wrap">
                                        <div class="hidepanel shw" id="plans_details"></div>
                                        <div class="col-md-12 text-left" style="position: relative;"><h5>Plan Details</h5>
                                        </div>
                                        <p id="error-message" style="color: red;
                                           position: absolute;
                                           top: 10px;
                                           right: 12px;
                                           font-weight: bold;">
                                               
                                       </p> 
                                        <div class="col-md-12 text-left plan-details cf p-0">
                                             <?php
                                                foreach ($plan_types as $plan_type) {
                                                    $checked = ($plan_type['id'] == $plan_type_id)?'checked':'';
                                                 ?>
                                                <div class="plan cf col-md-4">
                                                    <label>
                                                    <input type="radio" name="rsa_plan_type" id="rsa_plan_type" value="<?php echo $plan_type['id']?>" data-plan="" <?php echo $checked;?> checked> &nbsp;&nbsp; <?php echo $plan_type['plan_type'];?>
                                                    </label>
                                                </div>
 
                                                <?php } ?>
                                                <p id="error-plan_type" style="color: red"></p>
                                            <!-- <div class="col-md-6" style="display:none" id="st_date_div">
                                                <div class="form-group">
                                                    <label for="mobile_no" class="col-sm-3 control-label">Start Date</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="rsa_start_date" name="start_date" placeholder="Rsa Start Date" class="form-control cust_info_field"  value="" readonly>
                                                        <span id="error-start_date" style="color: red"></span>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="col-md-12 text-left p-0 " id="onlyrsa_plan_details">
                                            <table class="table table-bordered table-hover table-dark">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">#</th>
                                                      <th scope="col">Plan Name</th>
                                                      <th scope="col">RSA Tenure</th>
                                                      <th scope="col">RSA Covered Kms</th>
                                                      <th scope="col">Policy Price <span style="color:red">*</span></th>
                                                      <th scope="col">Select Plan</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>                        
                                                    <tr>
                                                      <th scope="row">1</th>
                                                      <td>Only RSA Service</td>
                                                      <td>1 Year</td>
                                                      <td>25</td>
                                                      <td>₹ 0</td>
                                                      <td><input type="radio" name="only_rsa_plan" id="only_rsa_plan" value="66" data-plan="0.00" checked></td>
                                                    </tr>               
                                                    </tbody>
                                                </table>
                                        </div>
                                        <p id="error-plan_type" style="color: red"></p>
                                    </div>
                                    


                            </div>
                            <div class="col-md-12 text-right padng-B p-0" style="margin: 20px 0;">
                                <div class="col-md-12">
                                    <input type="hidden" name="policyid" id="policyid" value="<?php echo $policyid ;?>">
                                    <input type="hidden" name="is_eligible" id="is_eligible">
                                    <!-- <input type="hidden" id="is_allow_policy" value="<?= $is_allow_policy ?>" name="is_allow_policy"> -->
                                    <input type="hidden" id="plan_id" value="<?= $plan_id ?>" name="plan_id">
                                    <input type="hidden" id="dms_ic_id" value="" name="dms_ic_id">
                                    <!-- <input type="hidden" id="registration_date" value="" name="registration_date"> -->
                                    <button type="button" id="renew_only_rsa_btn" class="btn btn-primary button_purple">Generate Policy</button>
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
<div class="modal fade" id="confirm_onlyrsa_modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" style="  border-radius: 0px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="    font-weight: bold;
                    color: #0090d3;">Generate Policy</h4>
            </div>
            <div class="modal-body">
                <div class="row boxdiv">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <b> Plan Amt:<p id="plan_amount"><?php echo $plan_details['plan_amount'];?></p></b>
                            </div>
                            <div class="col-md-6">
                                <p id="plan_amt"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <b> Plan Amt With GST:<p id="plan_amount_with_gst"><?php echo ($plan_details['plan_amount'] + $plan_details['gst_amount']);?></p></b>
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
                <button type="submit" class="btn btn-primary  button_purple" id="confirm_onlyrsa_submit">Confirm</button>
                <button type="button" class="btn button_purple button-dark" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<input type="hidden" id="seleted_manufacturin_month" name="$seleted_manufacturin_month" value="">
<input type="hidden" id="make_hidden" value="">
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/generate_pa_policy.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.min.js"></script> -->
