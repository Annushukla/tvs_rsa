$(document).ready(function(){

var dt = new Date();
var start_dt = new Date();
dt.setFullYear(new Date().getFullYear()-18);
start_dt.setFullYear(new Date().getFullYear()-75);

var selected_dob = $("#selected_dob").val();
$('#dob_renew').datepicker({
    container: '#custom-pos',
    viewMode: "years",
    calendarWeeks: true,
    startDate:start_dt,
    endDate: dt,
    autoclose: true
});
$('#dob_renew').datepicker('setDate', new Date(selected_dob));
  //  if(checkIsExist(selected_dob) == true){
  //       $("#dob_renew").val(selected_dob);
  // }
$("input[name='plan']").change(function(){
     $("#generate_pending_policy").attr('disabled',false);
  });

var is_renewed = $("#is_renewed").val();
getRenewedPolicyExist(is_renewed);

var pincode = $('#pending_pin').val();
  getStateCityByPincode(pincode);

  $("#pending_pin").focusout(function(){
    var length = $(this).val().length;
    var pin = $(this).val();
    if(length === 6){
        // alert(base_url);
      getStateCityByPincode(pin);
    }
});


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
// alert(hid_policy_id);
$.ajax({
        url: base_url+'front/myaccount/BuyRSApolicy/getModel',
        data: {hid_policy_id:hid_policy_id},
        dataType: 'Json',
        type: 'POST',
        success: function(response){
            $('#pending_model_id').html(response.html);
                     
        }
    });



$("#generate_pending_policy").on('click',function(){
    var form_data = $("#pending_policy_form").serialize();
	var status = false;
    var nominee_relation = $('#nominee_relation').val();
	$("#pending_policy_form input,#pending_policy_form select").each(function(){
        var nominee_age = $("#nominee_age").val();
        var vehicle_type = $("input[name='vehicle_type']:checked").val();

        if((parseInt(nominee_age) >= 18) && (this.id == 'appointee_full_name' || this.id == 'appointee_relation' || this.id == 'appointee_age')){
            return true;
        }

        if(vehicle_type == 'OLD'){
            if(this.id == 'reg_no' || this.id == 'rto_code2'){
                $('#error-rto_code2').text('');
                $('#error-reg_no').text('');
                $('#rto_code2').css("border-color", "#cccccc");
                $('#reg_no').css("border-color", "#cccccc");
                return true;
            }
        }

        // if(vehicle_type == 'OLD'){
        //     if(this.id == 'rto_code2'){
        //         $('#error-rto_code2').text('');
        //         $('#error-reg_no').text('');
        //         $('#rto_code2').css("border-color", "#cccccc");
        //         $('#reg_no').css("border-color", "#cccccc");
        //         return true;
        //     }
        // }
        

        if(this.id == 'other_relation' && nominee_relation!='other' ){
            return true;
        }
         if(validate(this.id) === true){
            console.log(this.id+'--'+this.value); 
            status = true;
        }
    });
	if(status === false){
        var plan_value = $("input[name='plan']:checked").attr('data-plan');
        $("#plan_amount").text(plan_value);
         var calcPerc = ((plan_value/100)*18);
        var total_amount = (parseFloat(plan_value) + parseFloat(calcPerc));
        $("#plan_amount_with_gst").text(total_amount.toFixed(2));
        var chassis_no = $('#chassis_no').val();
        var engine_no = $('#engine_no').val();
        var policy_id = $("#hid_policy_id").val();
        if(checkIsExist(policy_id) === false){
			console.log('ploicsdf');
            
        }else{
            $("#confirmModal").modal();
        }
        
	}
});


    $("#confirm_apirsa_submit").on('click',function(){
        $("#pending_model_id").attr('disabled',false);
        $("#pending_policy_form").submit();
    });



});

function getStateCityByPincode(pin){
  $.ajax({
        url : base_url + 'front/myaccount/BuyRSApolicy/fetchStateCityBypincode',
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

function getRenewedPolicyExist(renew_policyid){
  $.ajax({
        url : base_url + 'front/myaccount/BuyRSApolicy/getRenewedPolicyExist',
        data : {renew_policyid:renew_policyid},
        dataType : 'JSON',
        type : 'POST',
        success:function(response){
            if(response){
                console.log(response);
                if(response=="exist"){
                  $("#message").text("This Policy is Already Renewed");
                  $("#generate_pending_policy").attr('disabled',true);
                }else{
                  $("#generate_pending_policy").attr('disabled',false);
                  $("#message").text("");
                }
            }
        }
      });
}

