$(document).ready(function(){



  $("input[name='plan_type']").change(function(){
     $("#pa_cover_generate_button").attr('disabled','disabled');
  }); 

  $(document).on('change','input[type=radio][name=plan]', function() {
      $("#pa_cover_generate_button").removeAttr("disabled");
});

var plan_id = $("#plan_id").val();
  if(checkIsExist(plan_id) == true){
      $("#pa_cover_generate_button").removeAttr("disabled");
  } 

  var plan_type_id = $("input[name='plan_type']:checked").val();
  // alert(plan_type_id);

  if(checkIsExist(plan_type_id) == true){
      var plan_id = $("#plan_id").val();
      getPlanDetails(plan_type_id,plan_id);
  }


var plan_id = $("#plan_id").val();
  if(checkIsExist(plan_id) == true){
      $("#pa_cover_generate_button").removeAttr("disabled");
  } 


  $('input[type=radio][name=plan_type]').on('change', function() {
    var plan_type_id = $(this).val();
    var plan_id = $("#plan_id").val();
    if(plan_type_id==1){
      $('#st_date_div').css("display","block");
      // $('#st_date_div').css('clip', 'auto');
      $('#rsa_start_date').datepicker({
          autoclose: true,
          format: "yyyy-mm-dd",
          startDate: new Date(), // controll start date like startDate: '-2m' m: means Month
          endDate: '+30d'
      });
    }else{
      $('#st_date_div').css("display","none");
    }
    var dms_ic_id = $("#dms_ic_id").val();
    getPlanDetails(plan_type_id,plan_id,dms_ic_id);
});

var policy_id = $("#policy_id").val();
if(checkIsExist(policy_id) == true){
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
  if(checkIsExist(value) == true){
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



$("#search_button").on('click',function(){
	var search_engine_no = $("#search_engine_no").val();
	$.ajax({
			url : base_url+'front/myaccount/TvsRsa_Renewal/FetchRenewalPolicy',
			data : {search_engine_no:search_engine_no},
			dataType : 'JSON',
			type : "POST",
			success : function(response){
					console.log(response);
					var policy_status = false;
					if(response.policy_status==='Policy is Exist'){
						policy_status = false;
            $("#message").text(response.policy_status);
					}
					if(response.policy_status==='renewal'){
						policy_status = true;
					}
					if(response.policy_status==='No Response'){
						policy_status = false;
            $("#message").text(response.policy_status);
					}
					if(response.policy_status==='No Data'){
						policy_status = false;
            $("#message").text(response.policy_status);
					}
					if(policy_status===true){

						$("#search_engine_no").val(response.engine_no);
						$("#engine_no").val(response.engine_no);
						$("#chassis_no").val(response.chassis_no);
            $("#pin").val(response.pincode);
            $("#rto_code1").val(response.rto_code1);
            $("#rto_code2").val(response.rto_code2);
            $("#rto_name").val(response.rto_name);
            $("#first_name").val(response.fname);
            $("#last_name").val(response.lname);
            $("#mobile_no").val(response.mobile_no);
            $("#email").val(response.email);
            $("#dob").val(response.dob);
            $("#gender").val(response.gender);
            $("#cust_addr1").val(response.addr1);
            $("#cust_addr2").val(response.addr2);
            $("#nominee_age").val(response.nominee_age);
            $("#nominee_full_name").val(response.nominee_full_name);
            $("#nominee_relation").val(response.nominee_relation);
            if(response.appointee_full_name!="" || response.appointee_full_name!=null){
              $("#appointee_div").css("display","block");
              $("#appointee_div").find('*').attr('disabled',false);
            }
            $("#appointee_full_name").val(response.appointee_full_name);
            $("#appointee_relation").val(response.appointee_relation);
            $("#appointee_age").val(response.appointee_age);
						fetch_model(response.model_name);
            			getStateCityByPin(response.pincode);
					}
			}
	});
})

});


function getPlanDetails(plan_type_id,plan_id,dms_ic_id){
        $.ajax({
          url:base_url + 'planDetails',
          data:{plan_type_id:plan_type_id,plan_id:plan_id,dms_ic_id:dms_ic_id},
          dataType: 'html',
          type: 'POST',
          success: function(response){
             $("#plan_details").html(response);
        }
      });
}


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

function checkIsExist(checkvar) {
    if (checkvar === null || checkvar === "" || checkvar === "null" || checkvar === undefined || checkvar === 0 || checkvar === false) {
        return false;
    } else {
        return true;
    }
}