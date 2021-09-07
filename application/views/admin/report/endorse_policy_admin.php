<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Policy Endorsement</h1>
<br><br>
<form method="post" action="<?php echo base_url('admin/post_endorsement_data');?>">     
  <div class="col-md-12 text-left nopadding" style="position: relative;"><h3>Vehicle Info :</h3></div>
    <p id="error-message" style="color: red;
       position: absolute;
       top: 10px;
       right: 12px;
       font-weight: bold;"></p>

<div class="row">

    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                <label for="engine_no" class="col-sm-3 control-label">Engine number</label>

                <div class="col-sm-9">
                    <input type="text" name="engine_no" id="engine_no" placeholder="ENGINE NO." class="form-control cust_info_field keypress_validation" value="<?php echo $engine_no; ?>" data-regex="" autofocus style="text-transform:uppercase" required <?=$paic_readonly?> >
                    <span id="error-engine_no" style="color: red"></span>
                </div>
            <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="chassis_no" class="col-sm-3 control-label">Chassis Number</label>
                <div class="col-sm-9">
                    <input type="text" name="chassis_no" id="chassis_no" placeholder="CHASSIS NO." value="<?php echo $chassis_no; ?>" class="form-control cust_info_field" minlength="17" maxlength="17" autofocus style="text-transform:uppercase" <?=$paic_readonly?> >
                    <span id="error-chassis_no" style="color: red"></span>
                </div>
            <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group" id="manufacturer_cont">
                <label for="manufacturer" class="col-sm-3 control-label">Manufacturer</label>
                <div class="col-sm-9">
                    <select name="manufacturer" id="manufacturer" name="manufacturer" class="form-control cust_info_field" disabled="">
                        <option value="">SELECT MANUFACTURER</option>
                        <option value="11" selected>TVS</option>
                    </select>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-6">
          <div class="form-group" id="model_cont">
              <label for="model" class="col-sm-3 control-label">Model
                  <span style="color:red" >*</span>
              </label>
              <div class="col-sm-9">
                  <select name="model_id" id="model_id" class="form-control cust_info_field" data-message = "Model" <?=$paic_disable?> >


                  </select>
                  <span id="error-model_id" style="color: red"></span>
              </div>
          </div>

          </div>
    </div>
    <div class="col-md-12">


        <div class="col-md-6">
            <div class="form-group">
                <label for="registration_no" class="col-sm-3 control-label">Registration no.</label>
                <div class="col-sm-2">
                    <input  type="text" id="rto_name"  value="<?php echo $rto_name; ?>" name="rto_name" placeholder="MH" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="2" <?=$paic_readonly?> >
                    <span id="error-rto_name" style="color: red"></span>
                </div>
                <div class="col-sm-2">
                    <input  type="text" id="rto_code1"  value="<?php echo $rto_code1; ?>" name="rto_code1" placeholder="01" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="2" <?=$paic_readonly?> >
                    <span id="error-rto_code1" style="color: red"></span>
                </div>
                <div class="col-sm-2">
                    <input  type="text" id="rto_code2"  value="<?php echo $rto_code2; ?>" name="rto_code2" placeholder="AB" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="3" <?=$paic_readonly?> >
                    <span id="error-rto_code2" style="color: red"></span>
                </div>
                <div class="col-sm-3">
                    <input  type="text" id="reg_no"  value="<?php echo $reg_no; ?>" name="reg_no" placeholder="6831" class="form-control cust_info_field" autofocus style="text-transform:uppercase" maxlength="4" <?=$paic_readonly?> >
                    <span id="error-reg_no" style="color: red"></span>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="col-md-6">
            <div class="form-group">
                <label for="first_name" class="col-sm-3 control-label">First name</label>
                <div class="col-sm-9">
                    <input type="text" id="first_name"  value="<?php echo $fname; ?>" name="first_name" placeholder="FIRST NAME" class="form-control cust_info_field" autofocus style="text-transform:uppercase">
                    <span id="error-first_name" style="color: red"></span>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                <label for="last_name" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-9">
                    <input type="text" id="last_name" name="last_name" placeholder="LAST NAME"  value="<?php echo $lname; ?>" class="form-control cust_info_field" autofocus style="text-transform:uppercase">
                    <span id="error-last_name" style="color: red"></span>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email" class="col-sm-3 control-label">Email</label>
                <div class="col-sm-9">
                    <input type="text" id="email" name="email" placeholder="EMAIL ID" class="form-control cust_info_field"  value="<?php echo $email; ?>" autofocus>
                    <span id="error-email" style="color: red"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                <label for="mobile_no" class="col-sm-3 control-label">Mobile number</label>
                <div class="col-sm-9">
                    <input maxlength="10" size="10" type="text" id="mobile_no" name="mobile_no" placeholder="MOBILE NO." class="form-control cust_info_field"  value="<?php echo $mobile_no; ?>" autofocus>
                    <span id="error-mobile_no" style="color: red"></span>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="col-md-6">
          <div class="form-group" id="gender_div">
              <label for="model" class="col-sm-3 control-label">Gender
              </label>
              <div class="col-sm-9">
                  <select name="gender" id="gender" class="form-control cust_info_field" data-message = "Gender">
                      <option value="">Select Gender</option>
                      <option value="male" <?php echo ($gender=='male')? 'selected' : '' ;?> >Male</option>
                      <option value="female" <?php echo ($gender=='female')? 'selected' : '' ;?> >Female</option>
                  </select>
                  <span id="error-gender" style="color: red"></span>
              </div>
              <div class="clearfix"></div>
          </div>
      </div>
      <div class="col-md-6">
          <div class="form-group">
              <label for="mobile_no" class="col-sm-3 control-label">DOB</label>
              <div class="col-sm-9">
                  <input type="date" id="dob" name="dob" placeholder="DOB" class="form-control cust_info_field"  value="<?php echo $dob;?>" >
                  <span id="error-dob" style="color: red"></span>
              </div>
              <div class="clearfix"></div>
          </div>
      </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-3 control-label">Address 1</label>
                <div class="col-sm-9">
                    <input type="text" id="cust_addr1" name="cust_addr1" placeholder="ADDRESS 1" class="form-control cust_info_field"  value="<?php echo $addr1; ?>" autofocus style="text-transform:uppercase">
                    <span id="error-cust_addr1" style="color: red"></span>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-3 control-label">Address 2</label>
                <div class="col-sm-9">
                    <input type="text" id="cust_addr2" name="cust_addr2" placeholder="ADDRESS 2" class="form-control cust_info_field"  value="<?php echo $addr2; ?>" autofocus style="text-transform:uppercase">
                    <span id="error-cust_addr2" style="color: red"></span>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="pin" class="col-sm-3 control-label">Pin</label>
                <div class="col-sm-9">
                    <input maxlength="6" size="6" type="text" id="pin" name="pin" placeholder="ENTER PIN" class="form-control cust_info_field" value="<?php echo $pincode; ?>" autofocus>
                    <span id="error-pin" style="color: red"></span>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-3 control-label">State</label>
                <div class="col-sm-9">
                   <input type="hidden" id="state_id" name="state_id" class="form-control"  value="">
                    <input type="text" id="state" name="state" placeholder="STATE" class="form-control cust_info_field"  value="<?php echo $state; ?>" autofocus style="text-transform:uppercase" >
                    <span id="error-state" style="color: red"></span>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-3 control-label">City</label>
                <div class="col-sm-9">
                   <input type="hidden" id="city_id" name="city_id" class="form-control"  value="">
                    <input type="text" id="city" name="city" placeholder="CITY" class="form-control cust_info_field"  value="<?php echo $city; ?>" autofocus style="text-transform:uppercase" >
                    <span id="error-city" style="color: red"></span>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
</div>

<div class="row" id="nominee_div">
  <div class="col-md-12 text-left " style="position: relative;"><h3>Nominee Details : </h3></div>
  <p id="error-message" style="color: red;
     position: absolute;
     top: 10px;
     right: 12px;
     font-weight: bold;"></p>
  <div class="col-md-12 text-left ">
      <div class="col-md-6">
          <div class="form-group">
              <label for="first_name" class="col-sm-3 control-label">Nominee Full Name</label>
              <div class="col-sm-9">
                  <input type="text" id="nominee_full_name"  value="<?php echo $nominee_full_name; ?>" name="nominee_full_name" placeholder="NOMINEE FULL NAME" class="form-control cust_info_field" autofocus style="text-transform:uppercase">
                  <span id="error-nominee_full_name" style="color: red"></span>
              </div>
              <div class="clearfix"></div>
          </div>
      </div>
      <div class="col-md-6">
          <div class="form-group" id="model_cont">
              <label for="model" class="col-sm-3 control-label">Relation</label>
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
                          'daughter'=>'Daughter'
                      );
                      foreach ($nominee_relation_array as $key => $nominee_rela) { 
                          $selected = ($key == $nominee_relation)?'selected = "selected"':'';

                          ?>
                          <option value="<?=$key?>" <?= $selected; ?> ><?=$nominee_rela?></option>
                   <?php   } ?>
                  </select>
                  <span id="error-nominee_relation" style="color: red"></span>
              </div>
              <div class="clearfix"></div>
          </div>

      </div>
      <div class="col-md-12">
          
      </div>
      <div class="col-md-6">
          <div class="form-group">
              <label for="mobile_no" class="col-sm-3 control-label">Age</label>
              <div class="col-sm-9">
                  <input maxlength="2" size="2" type="text" id="nominee_age" name="nominee_age" placeholder="NOMINEE AGE" class="form-control cust_info_field"  value="<?= $nominee_age ?>" autofocus>
                  <span id="error-nominee_age" style="color: red"></span>
              </div>
              <div class="clearfix"></div>
          </div>
      </div>
  </div>
</div>
<div class="row" id="appointee_div" style="<?php echo $display ;?>;">
  <div class="col-md-12 text-left " style="position: relative;"><h3>Appointee Details : </h3>
  </div>
  <p id="error-message" style="color: red;
     position: absolute;
     top: 10px;
     right: 12px;
     font-weight: bold;"></p>
  <div class="col-md-12 text-left ">
      <div class="col-md-6">
          <div class="form-group">
              <label for="first_name" class="col-sm-3 control-label">Appointee Full Name</label>
              <div class="col-sm-9">
                  <input type="text" id="appointee_full_name"  value="<?= $appointee_full_name ?>" name="appointee_full_name" placeholder="APPOINTEE FULL NAME" class="form-control cust_info_field" autofocus style="text-transform:uppercase" >
                  <span id="error-appointee_full_name" style="color: red"></span>
              </div>
              <div class="clearfix"></div>
          </div>
      </div>
      <div class="col-md-6">
          <div class="form-group" id="model_cont">
              <label for="model" class="col-sm-3 control-label">Relation</label>
              <div class="col-sm-9">
                  <select name="appointee_relation" id="appointee_relation" class="form-control cust_info_field" data-message = "Appointee Relation" >
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
                  <span id="error-appointee_relation" style="color: red"></span>
              </div>
              <div class="clearfix"></div>
          </div>

      </div>
      <div class="col-md-12">
          
      </div>
      <div class="col-md-6">
          <div class="form-group">
              <label for="mobile_no" class="col-sm-3 control-label">Age</label>
              <div class="col-sm-9">
                  <input maxlength="2" size="2" type="text" id="appointee_age" name="appointee_age" placeholder="APPOINTEE AGE" class="form-control cust_info_field"  value="<?= $appointee_age ?>" autofocus >
                  <span id="error-appointee_age" style="color: red"></span>
              </div>
              <div class="clearfix"></div>
          </div>
      </div>
  </div>
</div>

<div class="row form-group">
  <div class="col-md-2">
    <input type="hidden" name="hid_policy_id" id="hid_policy_id" value="<?= $policy_id;?>">
    <input type="hidden" name="hid_customer_id" id="hid_customer_id" value="<?= $customer_id;?>">

    <button type="submit" class="btn btn-primary">Endorse</button>
  </div>
</div>
</form>      
</section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript">
  var pincode = $('#pin').val();
  getStateCityByPin(pincode);

  $("#pin").focusout(function(){
    var length = $(this).val().length;
    var pin = $(this).val();
    if(length === 6){
      getStateCityByPin(pin);
    }
});

function getStateCityByPin(pin){
  $.ajax({
        url : base_url + 'admin/fetchStateCityBypincode',
        data : {data:pin},
        dataType : 'JSON',
        type : 'POST',
        success:function(response){
            if(response){
                $("#state").val(response.state);
                $("#city").val(response.city);
                $("#state_id").val(response.state_id);
                $("#city_id").val(response.city_id);
            }
        }
      });
}

var hid_policy_id = $("#hid_policy_id").val();
if(hid_policy_id!=''){
var nominee_age = $("#nominee_age").val();

  if(nominee_age < 18 ){
    $("#appointee_div").css("display","block");
    $("#appointee_div").find('*').attr('disabled',false)
  }else{
      $("#appointee_div").css("display","none");
      $("#appointee_div").find('*').attr('disabled',true);
  }
}else{
  $("#appointee_div").css("display","none");
  $("#appointee_div").find('*').attr('disabled',true)
}

$("#nominee_age").keyup(function(){
  var value = $(this).val();
  if(value !='' && value != 0){
  if(value < 18 ){
    $("#appointee_div").css("display","block");
    $("#appointee_div").find('*').attr('disabled',false)
  }else{
      $("#appointee_div").css("display","none");
    $("#appointee_div").find('*').attr('disabled',true);
  }
}else{
  $("#appointee_div").css("display","none");
  $("#appointee_div").find('*').attr('disabled',true)
}
});

var hid_policy_id = $('#hid_policy_id').val();
$.ajax({
        url: base_url+'Report/getModel',
        data: {hid_policy_id:hid_policy_id},
        dataType: 'Json',
        type: 'POST',
        success: function(response){
            $('#model_id').html(response.html);
            if(response.model_name!=""){
            $('#model').val(response.model_name);
            }               
        }
    });

// function fetch_model(model){
//         $.ajax({
//             url : base_url+'fetch_model',
//             data : {model : model},
//             dataType : 'JSON',
//             type : 'POST',
//             success : function(response){
//                     $('#model_id').html(response.html);
//                         if(response.model_name!=""){
//                     $('#model').val(response.model_name);
//                         }   
//             }
//         });
// }



</script>