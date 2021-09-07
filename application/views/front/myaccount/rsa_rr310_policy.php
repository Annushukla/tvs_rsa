

<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Calibri" rel="stylesheet">
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
                   <!--  <div class="row">
                        <div class="col-md-12">
                            <div class="row" style="padding:10px 0;">
                                <div class="col-md-9 text-left">
                                    <h3 style="color: #333;">Search customer by vehicle’s Engine No. if it exists in our database</h3>
                                </div>
                                <div class="text-right col-md-3">
                                    <button id="reset_data" class="btn btn-primary button_purple" name="reset_data">RESET</button>
                                </div>
                            </div>
                            <input type="hidden" name="selected_dob" value="<?=$dob?>" id="selected_dob" class="selected_dob">
                            <form class="form-horizontal" id="vehicle_details_form" action="#">
                                <div id="custom-search-input">
                                    <div class="input-group col-md-12 border-light">
                                        <input type="text" class="search-query searchbox" name="vehicle_detail" id="vehicle_detail" placeholder="Enter customer’s Vehicle’s Engine No." value="<?php echo $engine_no ?>" style="color:black;">

                                        <span class="input-group-btn">
                                            <button class="btn btn-danger" type="button" id="search_button_rr_310">
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
                    </div> -->
                    <div id="cust_info_panel">
                        <div id="cust_info" class="custom-ewnow-form">
                            <div class="col-md-12 form-blue">
                                <p id="message" style="color: red"><?php echo $this->session->flashdata('message')?></p>
                                <form accept-charset="utf-8" enctype="multipart/form-data" action="<?php echo base_url('rr_310_generate_policy'); ?>" method="post" autocomplete="off" class="form-horizontal" role="form" id="rr_310_vehcile_data_form">
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
                                                        <input type="text" name="engine_no" id="engine_no" placeholder="ENGINE NO." class="form-control cust_info_field keypress_validation" value="<?php echo $engine_no; ?>" data-regex="" minlength="5" autofocus style="text-transform:uppercase" required <?php echo $readonly; ?> >
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
                                                        <select name="model_id" id="model_ids" class="form-control cust_info_field"  >

                                                            <option value="">SELECT MODEL</option>
                                                            <option value="8" selected>APACHE RR 310</option>
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
                                                       
                                                    </label>
                                                    <div class="col-sm-2">
                                                        <input  type="text" id="rto_name"  value="<?php echo $rto_name; ?>" name="rto_name" placeholder="MH" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="2">
                                                        <span id="error-rto_name" style="color: red"><?php echo $this->session->flashdata('er_rto_name'); ?></span>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input  type="text" id="rto_code1"  value="<?php echo ($rto_code1=='') ? $reg2 : $rto_code1; ?>" name="rto_code1" placeholder="01" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="2">
                                                        <span id="error-rto_code1" style="color: red"><?php echo $this->session->flashdata('er_rto_code1'); ?></span>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input  type="text" id="rto_code2"  value="<?php echo ($rto_code2=='') ? $reg3 : $rto_code2; ?>" name="rto_code2" placeholder="AB" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="3">
                                                        <span id="error-rto_code2" style="color: red"><?php echo $this->session->flashdata('er_rto_code2'); ?></span>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input  type="text" id="reg_no"  value="<?php echo ($reg_no=='') ? $reg4 : $reg_no; ?>" name="reg_no" placeholder="6831" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="4">
                                                        <span id="error-reg_no" style="color: red"><?php echo $this->session->flashdata('er_reg_no'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
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
                                           
                                        </div>
                                        <div class="col-md-12">
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
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Address 1
                                                    <span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="cust_addr1" name="cust_addr1" placeholder="ADDRESS 1" class="form-control cust_info_field"  value="<?php echo $addr1; ?>" autofocus style="text-transform:uppercase">
                                                        <span id="error-cust_addr1" style="color: red"><?php echo $this->session->flashdata('er_cust_addr1'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Address 2
                                                    <span style="color:red" >*</span></label>
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
                                                    <label for="pin" class="col-sm-3 control-label">Pin<span style="color:red" >*</span></label>
                                                    <div class="col-sm-9">
                                                        <input maxlength="6" size="6" type="text" id="pin_code" name="pin" placeholder="ENTER PIN" class="form-control cust_info_field" value="<?php echo $pincode; ?>" autofocus>
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
                                     
                            </div>
                            <div class="col-md-12 text-right padng-B p-0" style="margin: 20px 0;">
                                <div class="col-md-12">
                                    <button type="submit" id="rr310_rsa_generate_button" class="btn btn-primary button_purple">Buy Now</button>
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
<input type="hidden" id="make_hidden" value="">
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/rr_310_policy.js"></script>
