$(document).ready(function(){

// $('registration_date').datepicker({
                        
//                         autoclose: true,
//                         format: "yyyy-mm-dd"
//                     });

$('#cheque_date').datepicker({
                        
                        autoclose: true,
                        format: "yyyy-mm-dd"
                    });

$('#neft_date').datepicker({
                        
                        autoclose: true,
                        format: "yyyy-mm-dd"
                    });

$('input[type="radio"]').on('click', function(){
    // window.alert($(this).val());
    var radio_val = $(this).val();
    if(radio_val == 'NEW'){
        $('#error-rto_code2').text('');
        $('#error-reg_no').text('');
        $('#rto_code2').css("border-color", "#cccccc");
        $('#reg_no').css("border-color", "#cccccc");
    }
});

 var pin_code = $("#pin_code").val();
    if(checkIsExist(pin_code) === true){
        fetchStateCityByPin(pin_code);
    }

$("#pin_code").focusout(function(){
    var length = $(this).val().length;
    var pin = $(this).val();
    if(length === 6){
      fetchStateCityByPin(pin);
        
    }
});
// $("#pa_cover_generate_button").on('click',function(){
//     var form_data = $("#vehcile_data_formm").serialize();
// 	var status = false;
//     var pay_mode = $('#payment_mode').val();
//     var nominee_relation = $('#nominee_relation').val();
// 	$("#vehcile_data_formm input,#vehcile_data_formm select").each(function(){
//         var nominee_age = $("#nominee_age").val();
//         var vehicle_type = $("input[name='vehicle_type']:checked").val();

//         if((parseInt(nominee_age) >= 18) && (this.id == 'appointee_full_name' || this.id == 'appointee_relation' || this.id == 'appointee_age')){
//             return true;
//         }

//         if(vehicle_type == 'NEW'){
//             if(this.id == 'reg_no' || this.id == 'rto_code2'){
//                 $('#error-rto_code2').text('');
//                 $('#error-reg_no').text('');
//                 $('#rto_code2').css("border-color", "#cccccc");
//                 $('#reg_no').css("border-color", "#cccccc");
//                 return true;
//             }
//         }

//         if(vehicle_type == 'OLD'){
//             if(this.id == 'rto_code2'){
//                 $('#error-rto_code2').text('');
//                 $('#error-reg_no').text('');
//                 $('#rto_code2').css("border-color", "#cccccc");
//                 $('#reg_no').css("border-color", "#cccccc");
//                 return true;
//             }
//         }
        
//         if(this.id == 'rsa_start_date' || this.id == 'email'){
//             return true;
//         }

//         if(this.id == 'other_relation' && nominee_relation!='other' ){
//             return true;
//         }
//          if(validate(this.id) === true){
//             //console.log(this.id+'--'+this.value); 
//             status = true;
//         }
//     });
// 	if(status === false){
//         var plan_value = $("input[name='plan']:checked").attr('data-plan');
//         $("#plan_amount").text(plan_value);
//          var calcPerc = ((plan_value/100)*18);
//         var total_amount = (parseFloat(plan_value) + parseFloat(calcPerc));
//         $("#plan_amount_with_gst").text(total_amount.toFixed(2));
//         var chassis_no = $('#chassis_no').val();
//         var engine_no = $('#engine_no').val();
//         var policy_id = $("#policy_id").val();
//         if(checkIsExist(policy_id) === false){
//             checkIsPolicyExist(engine_no,chassis_no);
//         }else{
//             $("#myModal").modal();
//         }
        
// 	}
// });


    $("#confirm_policy_submit").on('click',function(){
        $("#vehcile_data_formm").submit();
    });
});

$('#search_button_rr_310').on('click',function(){
var vehicle_detail = $('#vehicle_detail').val();
  $.ajax({
    url : base_url+'rr_310/check_exist_engineno_rr310',
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
            // $("input[name=plan_type_rsa_only]").prop("checked",false);
            // $("#plan_details_rr310_data").remove();
            //plan_details_rr310
            $('#exist_policy').text('');
            // $("#dms_ic_id").val(response.data['insurance_company_id']);
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
            // $("#registration_date").val(response.data['registration_date']);
            // $('#registration_date').datepicker('setDate', response.data['registration_date']);

            // $("#registration_date").css('border-color', 'rgb(223, 223, 223)');
            // $("#error-registration_date").text('');
             // $('#vehicle_age').val(response.data['age_of_vehicle']);
            $("#first_name").val(response.data['first_name']);
            $("#first_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-first_name").text('');
            $("#last_name").val(response.data['last_name']);
            $("#last_name").css('border-color', 'rgb(223, 223, 223)');
            $("#error-last_name").text('');
            $("#mobile_no").val(response.data['mobile_no']);
            $("#mobile_no").css('border-color', 'rgb(223, 223, 223)');
            $("#error-mobile_no").text('');
            $("#pin_code").val(response.data['pin']);
            $("#pin_code").css('border-color', 'rgb(223, 223, 223)');
            $("#error-pin_code").text('');
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
            
            // $('#gender').val(response.data['gender']);
            // $('#vehicle_age').val(response.data['age_of_vehicle']);
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
            fetchStateCityByPin(response.data['pin']);
        }else if(response.status=='no_response'){
            $("#vehcile_data_formm").trigger('reset');
            $('#exist_policy').text('Please fill Manually');

        }
    }
  });
  });



$("#rr310_rsa_generate_button").on('click',function(event){
    event.preventDefault();
    // alert('hello moto');
    var form_data = $("#rr_310_vehcile_data_form").serialize();
    // console.log(form_data);
	var status = false;
    var pay_mode = 'online';
	$("#rr_310_vehcile_data_form input,#rr_310_vehcile_data_form select").each(function(){
           if(this.id == 'rto_name' || this.id == 'rto_code1' || this.id == 'rto_code2' || this.id == 'reg_no'){
               return true;
           }

         if(validate(this.id) === true){
            // console.log(this.id+'--'+this.value); 
            status = true;
        }
    });
	if(status === false){
        // alert('hello moto');
        $("#rr_310_vehcile_data_form").submit();
	}
});




function fetchStateCityByPin(pin){
    $.ajax({
            url : base_url + 'rr_310/fetchStateCityNames',
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
  
function checkIsPolicyExist(engine_no,chessiss_no){
    $.ajax({
            url:base_url+'checkIsPolicyExist',
            data:{engine_no:engine_no,chessiss_no:chessiss_no},
            dataType:'JSON',
            type:'POST',
            success:function(data){
                if(data.status === 'false'){
                   $("#error-message").text('');

                $("#myModal").modal();
                $("#rr310Modal").modal();
                }else if(data.status === 'true'){
                    $("#error-message").text('Policy for given details is allready created to find go to certificate section.');
                }
                
            }
        });
}
