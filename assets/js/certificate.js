$(document).ready(function(){

    var page_name = $("#page_name").val();
  $("input[name='plan_type']").change(function(){
     $("#pa_cover_generate_button").attr('disabled','disabled');
  }); 


 $("input[name='plan_type_rsa_only']").change(function(){
     $("#rr310pa_cover_generate_button").attr('disabled','disabled');
  });

  var selected_dob = $("#selected_dob").val();
   if(checkIsExist(selected_dob) == true){
        $("#dob").val(selected_dob);
        var page_name = $("#page_name").val();
        if(page_name != 'renewal'){
          $("#pa_cover_generate_button").text('Endorse Policy');
          $('#myModal h4').text('Endorse Policy');
        }
   }

    
  $("#reset_data").on('click',function(){
   $("#vehcile_data_formm").trigger('reset');
  });
    var is_downloaded = $("#is_downloaded").val();
    var page_name = $("#page_name").val();
    if(is_downloaded == "FALSE" && page_name == "dealer_document_page"){
        var link = document.getElementById("download_pdf");
            link.click();
    }
    var is_allow_policy = $("#is_allow_policy").val();
    if(checkIsExist(is_allow_policy) == true){
        if(is_allow_policy == 'yes'){
            $("#plans_details").removeClass('shw');
        }else{
            $("#plans_details").addClass('shw');
        }
    }
    var is_uploaded = $("#is_uploaded").val();
    if(is_uploaded === 'TRUE'){
        $("#documentPopUp").modal('show');
    }
    var pin_code = $("#pin").val();
    if(checkIsExist(pin_code) === true){
        getStateCityByPin(pin_code);
    }
var manufacturer = $('#manufacturer').val();
var policyid = $('#policyid').val();
if(checkIsExist(manufacturer) == true){
$.ajax({
        url: base_url+'fetch_model',
        data: {make_id:manufacturer,policyid:policyid},
        dataType: 'Json',
        type: 'POST',
        success: function(response){
            $('#model_id').html(response.html);
            if(response.model_name!=""){
            $('#model').val(response.model_name);
            }               
        }
    });
}

$('#search_button').on('click',function(){
var vehicle_detail = $('#vehicle_detail').val();
  $.ajax({
    url : base_url+'check_exist_engineno',
    data : {vehicle_detail : vehicle_detail},
    dataType : 'JSON',
    type : 'POST',
    success : function(response){
        if(response.status=='exist'){
            $("#vehcile_data_formm").trigger('reset');
            $('#vehicle_detail').css('border-color', 'rgb(255, 190, 0)');
            $('#exist_policy').text('This Vehicle is Covered Under RSA Till '+response.end_date);
            $('#engine_no').attr('readonly', false);
            $('#chassis_no').attr('readonly', false);
        }else if(response.status=='new_policy'){ 
          console.log(response.data);
              // $('#vehcile_data_formm input[type="radio":checked]').each(function(){
              //         $(this).checked = false;  
              //     }); 
            // $("#plan_details").css('')
            $("input[name=plan_type]").prop("checked",false);
            $("#plan_details_data").remove();
            $('#exist_policy').text('');
            $("#dms_ic_id").val(response.data['insurance_company_id']);
            $("#engine_no").val(response.data['engine_no']);
            $('#engine_no').attr('readonly', true);
            $("#engine_no").css('border-color', 'rgb(223, 223, 223)');
            $("#error-engine_no").text('');
            $("#chassis_no").val(response.data['chassis_no']);
            $("#chassis_no").css('border-color', 'rgb(223, 223, 223)');
            $('#chassis_no').attr('readonly', true);
            $("#error-chassis_no").text('');
            $("#city").val(response.data['city']);
            $("#city").css('border-color', 'rgb(223, 223, 223)');
            $("#error-city").text('');
            $("#cust_addr1").val(response.data['cust_addr1']);
            $("#cust_addr1").css('border-color', 'rgb(223, 223, 223)');
            $("#error-cust_addr1").text('');
            $("#cust_addr2").val(response.data['cust_addr2']);
            $("#cust_addr2").css('border-color', 'rgb(223, 223, 223)');
            $("#error-cust_addr2").text('');
            $("#first_name").val(response.data['first_name']);
            $("#first_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-first_name").text('');
            $("#middel_name").val(response.data['middel_name']);
            $("#middel_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-middel_name").text('');
            $("#last_name").val(response.data['last_name']);
            $("#last_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-last_name").text('');
            $("#mobile_no").val(response.data['mobile_no']);
            $("#mobile_no").css('border-color', 'rgb(223, 223, 223)');
            $("#error-mobile_no").text('');
            $("#pin").val(response.data['pin']);
            $("#pin").css('border-color', 'rgb(223, 223, 223)');
            $("#error-pin").text('');
            $("#rto_name").val(response.data['reg1']);
            $("#rto_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-rto_name").text('');
            $("#state").val(response.data['state']);
            $("#state").css('border-color', 'rgb(223, 223, 223)');
            $("#error-state").text(''); 
            $("#nominee_full_name").val(response.data['insured_nominee_name']);
            $("#nominee_full_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-nominee_full_name").text('');
            $("#model_id").css('border-color', 'rgb(223, 223, 223)');
            $("#error-model_id").text('');
            if(response.data['nominee_relation']=='--Select--'){
              $('#nominee_relation').val('');
            }else{
              $('#nominee_relation').val(response.data['nominee_relation']);
            }
              $('#nominee_age').val(response.data['nominee_age']);
              $('#gender').val(response.data['gender']);
              // var dob_format = moment(response.data['dob']).format('MM-DD-YY');
              // $('#dob').val(dob_format);
              //$('#dob').val(response.data['dob']); 
            fetch_model(response.data['model']);
            getStateCityByPin(response.data['pin']);
        }else if(response.status=='no_response'){
            $("#vehcile_data_formm").trigger('reset');
            $('#engine_no').attr('readonly', false);
            $('#chassis_no').attr('readonly', false);
            $('#exist_policy').text('Please fill Manually');

        }
    }
  });

});

function jsonParseFn(obj) {
    var type_of_var = typeof (obj);
    if (type_of_var == 'string') {
        obj = jQuery.parseJSON(obj.trim());
    }
    return obj;
}


$('#workshop_search_button').on('click',function(){
  $("#rsa_workshop_form").trigger('reset');
  var engine_no = $('#vehicle_detail').val();
  $.ajax({
    url : base_url+'front/myaccount/Rsa_workshop/check_workshop_vehicledata_exist',
    type : 'POST',
    data : {engine_no : engine_no},
    dataType : 'JSON',
    success : function(response)
    {
      /*console.log(response.data+ "sushnt");*/
      var response_data=  jsonParseFn(response)
        if(response_data.status=="eligible"){
            $("#is_eligible").val('yes');
              datapopup(engine_no);
              $("#message").text(response_data.message);
        }else{
            $("#is_eligible").val('no');
            console.log("no elegible ");
            $("#message").text(response_data.message);
        }
      
    }
  });


});

$('#only_rsa_search_button').on('click',function(){
  $("#onlyrsa_renewal_form").trigger('reset');
  var engine_no = $('#vehicle_detail').val();
  $.ajax({
    url : base_url+'front/myaccount/renew_only_rsa/check_onlyrsa_vehicledata_exist',
    type : 'POST',
    data : {engine_no : engine_no},
    dataType : 'JSON',
    success : function(response)
    {
      /*console.log(response.data+ "sushnt");*/
      var response_data=  jsonParseFn(response)
        if(response_data.status=="eligible"){
            $("#is_eligible").val('yes');
              only_rsa_datapopup(engine_no);
              $("#message").text(response_data.message);
        }else{
            $("#is_eligible").val('no');
            console.log("no elegible ");
            $("#message").text(response_data.message);
        }
      
    }
  });


});

function datapopup(vehicle_detail){
  $.ajax({
    url : base_url+'CheckExistFrameNo',
    data : {vehicle_detail : vehicle_detail},
    dataType : 'JSON',
    type : 'POST',
    success : function(response){
        if(response.status=='exist'){
            $("#vehcile_data_formm").trigger('reset');
            $('#vehicle_detail').css('border-color', 'rgb(255, 190, 0)');
            $('#exist_policy').text('This Vehicle is Covered Under RSA Till '+response.end_date);
        }else if(response.status=='new_policy'){ 
              // $('#vehcile_data_formm input[type="radio":checked]').each(function(){
              //         $(this).checked = false;  
              //     }); 
            // $("#plan_details").css('')
            $("input[name=plan_type]").prop("checked",false);
           // $("#plan_details_data").remove();
            $('#exist_policy').text('');
            $("#dms_ic_id").val(response.data['insurance_company_id']);
            $("#engine_no").val(response.data['engine_no']);
            $("#engine_no").css('border-color', 'rgb(223, 223, 223)');
            $("#engine_no").attr('readonly',true);
            $("#error-engine_no").text('');
            $("#chassis_no").val(response.data['chassis_no']);
            $("#chassis_no").css('border-color', 'rgb(223, 223, 223)');
            $("#chassis_no").attr('readonly',true);
            $("#error-chassis_no").text('');
            $("#city").val(response.data['city']);
            $("#city").css('border-color', 'rgb(223, 223, 223)');
            $("#error-city").text('');
            $("#cust_addr1").val(response.data['cust_addr1']);
            $("#cust_addr1").css('border-color', 'rgb(223, 223, 223)');
            $("#error-cust_addr1").text('');
            $("#cust_addr2").val(response.data['cust_addr2']);
            $("#cust_addr2").css('border-color', 'rgb(223, 223, 223)');
            $("#error-cust_addr2").text('');
            $("#first_name").val(response.data['first_name']);
            $("#first_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-first_name").text('');
            $("#last_name").val(response.data['last_name']);
            $("#last_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-last_name").text('');
            $("#mobile_no").val(response.data['mobile_no']);
            $("#mobile_no").css('border-color', 'rgb(223, 223, 223)');
            $("#error-mobile_no").text('');
            $("#pin").val(response.data['pin']);
            $("#pin").css('border-color', 'rgb(223, 223, 223)');
            $("#error-pin").text('');
            $("#rto_name").val(response.data['reg1']);
            $("#rto_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-rto_name").text('');
            $("#state").val(response.data['state']);
            $("#state").css('border-color', 'rgb(223, 223, 223)');
            $("#error-state").text(''); 
            $("#nominee_full_name").val(response.data['insured_nominee_name']);
            $("#nominee_full_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-nominee_full_name").text('');
            $("#model_id").css('border-color', 'rgb(223, 223, 223)');
            $("#error-model_id").text('');
            if(response.data['nominee_relation']=='--Select--'){
              $('#nominee_relation').val('');
            }else{
              $('#nominee_relation').val(response.data['nominee_relation']);
            }
              $('#nominee_age').val(response.data['nominee_age']);
              $('#gender').val(response.data['gender']);
              // var dob_format = moment(response.data['dob']).format('MM-DD-YY');
              // $('#dob').val(dob_format);
              //$('#dob').val(response.data['dob']); 
            fetch_model(response.data['model']);
            getStateCityByPin(response.data['pin']);
        }else if(response.status=='no_response'){
            $("#vehcile_data_formm").trigger('reset');
            $('#exist_policy').text('Please fill Manually');

        }
    }
  });

}


function only_rsa_datapopup(vehicle_detail){
  $.ajax({
    url : base_url+'CheckExistFrameNo',
    data : {vehicle_detail : vehicle_detail},
    dataType : 'JSON',
    type : 'POST',
    success : function(response){
        if(response.status=='exist'){
            $("#onlyrsa_renewal_form").trigger('reset');
            $('#vehicle_detail').css('border-color', 'rgb(255, 190, 0)');
            $('#exist_policy').text('This Vehicle is Covered Under RSA Till '+response.end_date);
        }else if(response.status=='new_policy'){ 
              // $('#onlyrsa_renewal_form input[type="radio":checked]').each(function(){
              //         $(this).checked = false;  
              //     }); 
            // $("#plan_details").css('')
            $("input[name=plan_type]").prop("checked",false);
           // $("#plan_details_data").remove();
            $('#exist_policy').text('');
            $("#dms_ic_id").val(response.data['insurance_company_id']);
            $("#engine_no").val(response.data['engine_no']);
            $("#engine_no").css('border-color', 'rgb(223, 223, 223)');
            $("#engine_no").attr('readonly',true);
            $("#error-engine_no").text('');
            $("#chassis_no").val(response.data['chassis_no']);
            $("#chassis_no").css('border-color', 'rgb(223, 223, 223)');
            $("#chassis_no").attr('readonly',true);
            $("#error-chassis_no").text('');
            $("#city").val(response.data['city']);
            $("#city").css('border-color', 'rgb(223, 223, 223)');
            $("#error-city").text('');
            $("#cust_addr1").val(response.data['cust_addr1']);
            $("#cust_addr1").css('border-color', 'rgb(223, 223, 223)');
            $("#error-cust_addr1").text('');
            $("#cust_addr2").val(response.data['cust_addr2']);
            $("#cust_addr2").css('border-color', 'rgb(223, 223, 223)');
            $("#error-cust_addr2").text('');
            $("#first_name").val(response.data['first_name']);
            $("#first_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-first_name").text('');
            $("#last_name").val(response.data['last_name']);
            $("#last_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-last_name").text('');
            $("#mobile_no").val(response.data['mobile_no']);
            $("#mobile_no").css('border-color', 'rgb(223, 223, 223)');
            $("#error-mobile_no").text('');
            $("#pin").val(response.data['pin']);
            $("#pin").css('border-color', 'rgb(223, 223, 223)');
            $("#error-pin").text('');
            $("#rto_name").val(response.data['reg1']);
            $("#rto_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-rto_name").text('');
            $("#state").val(response.data['state']);
            $("#state").css('border-color', 'rgb(223, 223, 223)');
            $("#error-state").text(''); 
            $("#nominee_full_name").val(response.data['insured_nominee_name']);
            $("#nominee_full_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-nominee_full_name").text('');
            $("#model_id").css('border-color', 'rgb(223, 223, 223)');
            $("#error-model_id").text('');
            if(response.data['nominee_relation']=='--Select--'){
              $('#nominee_relation').val('');
            }else{
              $('#nominee_relation').val(response.data['nominee_relation']);
            }
              $('#nominee_age').val(response.data['nominee_age']);
              $('#gender').val(response.data['gender']);
              // var dob_format = moment(response.data['dob']).format('MM-DD-YY');
              // $('#dob').val(dob_format);
              //$('#dob').val(response.data['dob']); 
            fetch_model(response.data['model']);
            getStateCityByPin(response.data['pin']);
        }else if(response.status=='no_response'){
            $("#onlyrsa_renewal_form").trigger('reset');
            $('#exist_policy').text('Please fill Manually');

        }
    }
  });

}




$('#search_button_rr310').on('click',function(){
var vehicle_detail = $('#vehicle_detail').val();
  $.ajax({
    url : base_url+'check_exist_engineno_rr310',
    data : {vehicle_detail : vehicle_detail},
    dataType : 'JSON',
    type : 'POST',
    success : function(response){
        if(response.status=='exist'){
            $("#vehcile_data_formm").trigger('reset');
            $('#vehicle_detail').css('border-color', 'rgb(255, 190, 0)');
            $('#exist_policy').text('This Vehicle is Covered Under RSA Till '+response.end_date);
        }else if(response.status=='new_policy'){ 
              // $('#vehcile_data_formm input[type="radio":checked]').each(function(){
              //         $(this).checked = false;  
              //     }); 
            // $("#plan_details").css('')
            $("input[name=plan_type_rsa_only]").prop("checked",false);
            // $("#plan_details_rr310_data").remove();
            //plan_details_rr310
            $('#exist_policy').text('');
            $("#dms_ic_id").val(response.data['insurance_company_id']);
            $("#engine_no").val(response.data['engine_no']);
            $("#engine_no").css('border-color', 'rgb(223, 223, 223)');
            $("#error-engine_no").text('');
            $("#chassis_no").val(response.data['chassis_no']);
            $("#chassis_no").css('border-color', 'rgb(223, 223, 223)');
            $("#error-chassis_no").text('');
            $("#city").val(response.data['city']);
            $("#city").css('border-color', 'rgb(223, 223, 223)');
            $("#error-city").text('');
            $("#cust_addr1").val(response.data['cust_addr1']);
            $("#cust_addr1").css('border-color', 'rgb(223, 223, 223)');
            $("#error-cust_addr1").text('');
            $("#cust_addr2").val(response.data['cust_addr2']);
            $("#cust_addr2").css('border-color', 'rgb(223, 223, 223)');
            $("#error-cust_addr2").text('');
            $("#registration_date").val(response.data['registration_date']);
            $('#registration_date').datepicker('setDate', response.data['registration_date']);

            $("#registration_date").css('border-color', 'rgb(223, 223, 223)');
            $("#error-registration_date").text('');
             $('#vehicle_age').val(response.data['age_of_vehicle']);
            $("#first_name").val(response.data['first_name']);
            $("#first_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-first_name").text('');
            $("#last_name").val(response.data['last_name']);
            $("#last_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-last_name").text('');
            $("#mobile_no").val(response.data['mobile_no']);
            $("#mobile_no").css('border-color', 'rgb(223, 223, 223)');
            $("#error-mobile_no").text('');
            $("#pin").val(response.data['pin']);
            $("#pin").css('border-color', 'rgb(223, 223, 223)');
            $("#error-pin").text('');
            $("#rto_name").val(response.data['reg1']);
            $("#rto_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-rto_name").text('');
            $("#rto_code1").val(response.data['reg2']);
            $("#rto_code1").css('border-color', 'rgb(223, 223, 223)');
            $("#error-rto_name").text('');
            $("#state").val(response.data['state']);
            $("#state").css('border-color', 'rgb(223, 223, 223)');
            $("#error-state").text(''); 
           
            $("#model_id").css('border-color', 'rgb(223, 223, 223)');
            $("#error-model_id").text('');
            
            $('#gender').val(response.data['gender']);
            $('#vehicle_age').val(response.data['age_of_vehicle']);
              // var dob_format = moment(response.data['dob']).format('MM-DD-YY');
              // $('#dob').val(dob_format);
              //$('#dob').val(response.data['dob']); 
            //fetch_model("APACHE RR 310");
             //console.display("-->"+parseInt(response.data['age_of_vehicle']));
            // if(parseInt(response.data['age_of_vehicle'])>90){
            //   document.getElementById("div_plan_deatils").style.display = "block";
            //    $("plan_id").val(response.data['plan_id']);
            //   // getPlanDetailsForRR310();
             
            // }else {
            //   document.getElementById("div_plan_deatils").style.display = "none";
            //   $("plan_id").val(response.data['plan_id']);
            // }
            getStateCityByPin(response.data['pin']);
        }else if(response.status=='no_response'){
            $("#vehcile_data_formm").trigger('reset');
            $('#exist_policy').text('Please fill Manually');

        }
    }
  });

});


function fetch_model(model){
        $.ajax({
            url : base_url+'fetch_model',
            data : {model : model},
            dataType : 'JSON',
            type : 'POST',
            success : function(response){
                    $('#model_id').html('');
                    $('#model_id').html(response.html);
                        if(response.model_name!=""){
                    $('#model').val(response.model_name);
                  }   
            }
        });
}



$("#pin").focusout(function(){
    var length = $(this).val().length;
    var pin = $(this).val();
    if(length === 6){
      getStateCityByPin(pin);
        
    }
});

$("#ins_manager_email").focusout(function(){
     validate(this.id);
    });
$("#ins_manager_name").focusout(function(){
     validate(this.id);
    });

$("#ins_manager_contact").focusout(function(){
     validate(this.id);
    });
$("#principle_name").focusout(function(){
     validate(this.id);
    });
$("#principle_email").focusout(function(){
     validate(this.id);
    });
$("#principle_contact").focusout(function(){
     validate(this.id);
    });
$("#gm_name").focusout(function(){
     validate(this.id);
    });
$("#gm_email").focusout(function(){
     validate(this.id);
    });
$("#gm_contact").focusout(function(){
     validate(this.id);
    });
$("#dealer_no").focusout(function(){
     validate(this.id);
    });
$("#showoom_name").focusout(function(){
     validate(this.id);
    });
    
$("#dealer_full_name").focusout(function(){
     validate(this.id);
    });
$("#chassis_no").focusout(function(){
    validate(this.id);
    var value = this.value;
     if(page_name == 'workshop' && value.length == 17){
        checkIsAvailableForWorkshopPolicy(this.value);
      }
    });
$("#chassis_no").focusout(function(){
    validate(this.id);
    var value = this.value;
     if(page_name == 'renew_only_rsa' && value.length == 17){
        checkIsAvailableForOnlyRSAPolicy(this.value);
      }
    });


 $("#engine_no").focusout(function(){

    if(checkIsExist(this.value) == true && checkIsExist(selected_dob) == false){
      if(page_name != 'workshop'){
        checkDuplicateEntries(this.value);
      }
   }
    validate(this.id);
});
    $("#email").focusout(function(){
      if(checkIsExist(this.value) === true){
            validate(this.id);
          }else{
            $("#"+this.id).css('border-color', 'rgb(223, 223, 223)');
            $("#error-"+this.id).text('');
          }
    });
     $("#nominee_full_name").focusout(function(){
        if(checkIsExist($(this).val()) === true){
           validate(this.id);
       }else{
           $("#"+this.id).css('border-color', 'red');
           $("#error-"+this.id).text('Please Enter Nominee Full Name');
       }
   });

     $("#nominee_relation").on("change",function(){
        if(checkIsExist(this.value) == false){
            $("#"+this.id).css('border-color', 'red');
            $("#error-"+this.id).text('Please Select Relationship');
        }else{
           $("#"+this.id).css('border-color', 'rgb(223, 223, 223)');
           $("#error-"+this.id).text('');
       }
    })

       $("#nominee_age").focusout(function(){
        if(checkIsExist($(this).val()) === true){
           validate(this.id);
       }else{
            $("#"+this.id).css('border-color', 'red');
           $("#error-"+this.id).text('Please Enter Nominee Age');
       }
   });

        $("#appointee_full_name").focusout(function(){
        if(checkIsExist($(this).val()) === true){
           validate(this.id);
       }else{
           $("#"+this.id).css('border-color', 'red');
           $("#error-"+this.id).text('Please Enter Appointee Full Name');
       }
   });

    $("#appointee_age").focusout(function(){
        if(checkIsExist($(this).val()) === true){
           validate(this.id);
       }else{
           $("#"+this.id).css('border-color', 'red');
           $("#error-"+this.id).text('Please Enter Appointee Age');
       }
   });

    $("#appointee_relation").on("change",function(){
        if(checkIsExist(this.value) == false){
            $("#"+this.id).css('border-color', 'red');
            $("#error-"+this.id).text('Please Select Relationship');
        }else{
           $("#"+this.id).css('border-color', 'rgb(223, 223, 223)');
           $("#error-"+this.id).text('');
       }
    })
    
    $("#company_name").focusout(function(){
      validate(this.id);
    });
    $("#mobile_no").focusout(function(){
      validate(this.id);
    });
    $("#tin_no").focusout(function(){
      if(checkIsExist($(this).val()) === true){
            validate(this.id);
        }else{
            $("#"+this.id).css('border-color', 'rgb(223, 223, 223)');
            $("#error-"+this.id).text('');
        }
    });
    $("#aadhar_no").focusout(function(){
      if(checkIsExist($(this).val()) === true){
            validate(this.id);
        }else{
            $("#"+this.id).css('border-color', 'rgb(223, 223, 223)');
            $("#error-"+this.id).text('');
        }
    });
    $("#gst_no").focusout(function(){
      validate(this.id);
    });
    $("#pan_no").focusout(function(){
      validate(this.id);
    });
    $("#dealer_addr1").focusout(function(){
     validate(this.id);
    });
    $("#dealer_addr2").focusout(function(){
      validate(this.id);
    });
    $("#pin").focusout(function(){
      validate(this.id);
    });
    // $("#state").focusout(function(){
    //   validate(this.id);
    // });
    // $("#city").focusout(function(){
    //   validate(this.id);
    // });
    $("#bank_name").focusout(function(){
     validate(this.id);
    });
    $("#acc_holder_name").focusout(function(){
      validate(this.id);
    });
    $("#account_no").focusout(function(){
      validate(this.id);
    });
     $("#ifsc_code").focusout(function(){
      validate(this.id);
    });
    $("#rto_name").focusout(function(){
     validate(this.id);
  });
    $("#rto_code1").focusout(function(){
    validate(this.id);
  });

/*    $("#rto_code2").focusout(function(){
       if(checkIsExist(this.value)){ 
            validate(this.id);reg_no
        }
  });*/

  $("#reg_no").focusout(function(){
        if(checkIsExist(this.value)){ 
            validate(this.id);
        }
  });



  $("#first_name").focusout(function(){
    validate(this.id);
  });

   $("#last_name").focusout(function(){
   validate(this.id);
  });

   $("#cust_addr1").focusout(function(){
   validate(this.id);
  });

    $("#cust_addr2").focusout(function(){
   validate(this.id);
  });
$("#dealer_details_submit_button").on('click',function(){
    var error_status = false;
         $('#submit_dealer_details input,select').each(function(){
             if(this.id ==='tin_no' && checkIsExist( $(this).val()) === false){
                 return true;
             }
             if(this.id ==='aadhar_no' && checkIsExist( $(this).val()) === false){
                 return true;
             }
             if(this.id ==='phone_no' && checkIsExist( $(this).val()) === false){
                 return true;
             }
                if(validate(this.id)=== true){
                    error_status = true;
                }
         });
         if(error_status === false){
             $( "#submit_dealer_details" ).submit();
         }
});
});
function checkDuplicateEntries(engine_no){
     $.ajax({
            url : base_url + 'checkDuplicateEntries',
            data : {engine_no:engine_no},
            dataType : 'JSON',
            type : 'POST',
            success:function(response){
                if(response.status == 'true'){
                    $("#error-engine_no").text('Duplicate Engine No.');
                }else{
                  $("#error-engine_no").text('');
                }
            }
        });
}
function checkIsAvailableForWorkshopPolicy(engine_no){
     $.ajax({
            url : base_url + 'checkIsAvailableForWorkshopPolicy',
            data : {engine_no:engine_no},
            dataType : 'JSON',
            type : 'POST',
            success:function(response){
                if(response.status == 'false'){
                    $("#error-chassis_no").text('This Chassis No Is Already Used.');
                }else{
                  $("#error-chassis_no").text('');
                }
            }
        });
}
function checkIsAvailableForOnlyRSAPolicy(engine_no){
     $.ajax({
            url : base_url + 'checkIsAvailableForOnlyRSAPolicy',
            data : {engine_no:engine_no},
            dataType : 'JSON',
            type : 'POST',
            success:function(response){
                if(response.status == 'false'){
                    $("#error-chassis_no").text('This Chassis No Is Already Used.');
                }else{
                  $("#error-chassis_no").text('');
                }
            }
        });
}


function getStateCityByPin(pin){
    $.ajax({
            url : base_url + 'fetchStateCityNames',
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


function clearHtmlInputSelect(){
        $('#email').val('');
        $('#first_name').val('');
        $('#last_name').val('');
        $('#rto_name').val('');
        $('#rto_code1').val('');
        $('#rto_code2').val('');
        $('#reg_no').val('');
        $('#engine_no').val('');
        $('#chassis_no').val('');
        $('#mobile_no').val('');
        $('#cust_addr1').val('');
        $('#cust_addr2').val('');
        $('#state').val('');
        $('#city').val('');
        $('#model_id').val('');
        $('#model_id').change(); 
        $('#pin').val('');
}

function validate(element) {
    var element_obj = $('#' + element);
    var element_val = element_obj.val();
    var element_id = element;
    var element_placeholder = element_obj.attr('placeholder');
    var error_obj = $('#error-' + element_id);
    var error_status = false;
    var error_msg = '';
    // console.log('element_val=='+element_val+'---element=='+element);
    
    if (checkIsExist(element_val) === false) {
        var input_type = $("#" + element_id).get(0).tagName;
        error_status = true;
        if(input_type ==='SELECT'){
            error_msg = 'Please Select ' + $("#" + element_id).data('message');
        }else{
            error_msg = 'Please Enter ' + element_placeholder;
        }
    } else {
    if (element_id === 'dealer_full_name') {
           var regex = /[a-zA-Z][a-zA-Z ]*/;
            var test = regex.test(element_val); 
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Name.';
            }
        }

         else if (element_id === 'email') {
            var regex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter valid email.';
            }
        }
        
        else if (element_id === 'company_name') {
            var regex = /^[.@&]?[a-zA-Z0-9 ]+[ !.@&()]?[ a-zA-Z0-9!()]+/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Company Name.';
            }
        }
        
         else if (element_id === 'mobile_no') {
            var regex = /^[0]?[6789]\d{9}$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter valid Mobile No.';
            }
        }
        
        else if (element_id === 'phone_no') {
            var regex = /^[0-9]{11}$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter valid Phone No.';
            }
        }
        
         else if (element_id === 'tin_no') {
            var regex = /^(?:\d{3}-\d{2}-\d{4}|\d{2}-\d{7})$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Tin Number.';
            }
        }
        else if (element_id === 'aadhar_no') {
            var regex = /[0-9]{12}/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Aadhar Number.';
            }
        }
        
        else if (element_id === 'gst_no') {
            var regex = /\d{2}[A-Z]{5}\d{4}[A-Z]{1}\d[Z]{1}[A-Z\d]{1}/;
            var test = regex.test(element_val.toUpperCase());           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid GST No.';
            }
        }

         else if (element_id === 'pan_no') {
            var regex = /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/;
            var test = regex.test(element_val);         
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Pan No.';
            }
        }

        else if (element_id === 'dealer_addr1') {
            var regex = /^[a-zA-Z0-9\s\/.,'-]*$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Adderess1';
            }
        }

        else if (element_id === 'dealer_addr2') {
            var regex = /^[a-zA-Z0-9\s\/.,'-]*$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Adderess2';
            }
        }

        else if (element_id === 'pin') {
            var regex = /^[0-9]{6}$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Pincode';
            }
        }

        else if (element_id === 'state') {
            var regex = /^[a-zA-Z\s]+$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid state';
            }
        }

        else if (element_id === 'city') {
            var regex = /[a-zA-Z0-9.]/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid City';
            }
        }

        else if (element_id === 'dealer_form_ifsc_code') {
            var regex = /^[A-Za-z]{4}\d{7}$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid IFSC code';
            }
        }       

         else if (element_id === 'nominee_full_name') {
            var regex = /^[a-zA-Z\s]+$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Name';
            }
        }

         else if (element_id === 'nominee_age') {
            var regex = /^[1-9][0-9]?$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Age';
            }
        }
        else if (element_id === 'appointee_full_name') {
            var regex = /^[a-zA-Z\s]+$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Name';
            }
        }
       else if (element_id === 'appointee_age') {
           var regex = /^[A-Za-z]{4}\d{7}$/;
           var test = regex.test(element_val);
           var value = $("#"+element_id).val();
           if (parseInt(value) < 18) {
               error_status = true;
               error_msg = 'Appointee Age Should Be Above 18';
           }
       }
        else if (element_id === 'chassis_no') {
            var regex = /^[a-zA-Z0-9]{17}$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Chassis No';
            }
        }

        else if (element_id === 'engine_no') {
            var regex =  /^[a-zA-Z0-9]{5,}$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Engine No';
            }
        }

         else if (element_id === 'rto_name') {
            var regex = /^[a-zA-Z]{2,3}$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter 3 character of RTO Name.';
            }
        }

         else if (element_id === 'rto_code1') {
            var regex = /^[a-zA-Z0-9]{1,}$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter 2 Numbers of RTO Code.';
            }
        }
        
/*        else if (element_id === 'rto_code2') {
            var regex = /^[a-zA-Z]{0,3}$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter 3 character of RTO Code.';
            }
        }*/
        
         else if (element_id === 'reg_no') {
            var regex = /^[0-9]{0,4}$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter 4 Numbers of Registration Number.';
            }
        }
        
         else if (element_id === 'manufacturer') {
            var regex = /^[a-zA-Z0-9]+$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Manufacturer.';
            }
        }
        /*else if (element_id === 'registration_date') {
            var regex = /^[a-zA-Z\-]+$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Registration / Invoice Date.';
            }
        }*/
         else if (element_id === 'first_name') {
            var regex = /^[a-zA-Z\-]+$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid First Name.';
            }
        }

        else if (element_id === 'last_name') {
            var regex = /^[a-zA-Z_\s.,'-/]+$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Last Name';
            }
        }

         else if (element_id === 'cust_addr1') {
            var regex = /^[a-zA-Z0-9\s.,'-/]*$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Address1';
            }
          }

             else if (element_id === 'cust_addr2') {
            var regex = /^[a-zA-Z0-9\s.,'-/]*$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Address2';
            }
          }

            else if (element_id === 'ins_manager_name') {
            var regex = /^[a-zA-Z\s]+$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Name';
            }
          }
          else if (element_id === 'ins_manager_email') {
            var regex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter valid email.';
            }
        }

         else if (element_id === 'ins_manager_contact') {
            var regex = /^[0]?[6789]\d{9}$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter valid Mobile No.';
            }
        }

        else if (element_id === 'principle_name') {
            var regex = /^[a-zA-Z\s]+$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Name';
            }
          }
          else if (element_id === 'principle_email') {
            var regex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter valid email.';
            }
        }

         else if (element_id === 'principle_contact') {
           var regex = /^[0]?[6789]\d{9}$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter valid Mobile No.';
            }
        }

        else if (element_id === 'gm_name') {
            var regex = /^[a-zA-Z\s]+$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Name';
            }
          }
          else if (element_id === 'gm_email') {
            var regex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter valid email.';
            }
        }

         else if (element_id === 'gm_contact') {
            var regex = /^[0]?[6789]\d{9}$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter valid Mobile No.';
            }
        }
        else if (element_id === 'dealer_no') {
          if(checkIsExist(checkIsExist) == false){
              error_status = true;
              error_msg = 'Please Enter Dealer No';
          }
            
         }

         else if (element_id === 'showoom_name') {
            var regex = /^[a-zA-Z\s]+$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Name';
            }
        }

          

        }                   
    if (error_status === true) {
        console.log(error_msg+"  -err");
        error_obj.text(error_msg);
        element_obj.css("border-color", "#FFBE00");
    } else {
        error_obj.text('');
        element_obj.css("border-color", "#cccccc");
    }
    return error_status;
}


function checkIsExist(checkvar) {
    if (checkvar === null || checkvar === "" || checkvar === "null" || checkvar === undefined || checkvar === 0 || checkvar === false) {
        return false;
    } else {
        return true;
    }
}