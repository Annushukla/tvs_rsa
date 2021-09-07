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

$("#pa_cover_generate_button").on('click',function(){
    var form_data = $("#vehcile_data_formm").serialize();
	var status = false;
    var pay_mode = $('#payment_mode').val();
    var nominee_relation = $('#nominee_relation').val();
	$("#vehcile_data_formm input,#vehcile_data_formm select").each(function(){
        var nominee_age = $("#nominee_age").val();
        var vehicle_type = $("input[name='vehicle_type']:checked").val();

        if((parseInt(nominee_age) >= 18) && (this.id == 'appointee_full_name' || this.id == 'appointee_relation' || this.id == 'appointee_age')){
            return true;
        }

        if(vehicle_type == 'NEW'){
            if(this.id == 'reg_no' || this.id == 'rto_code2'){
                $('#error-rto_code2').text('');
                $('#error-reg_no').text('');
                $('#rto_code2').css("border-color", "#cccccc");
                $('#reg_no').css("border-color", "#cccccc");
                return true;
            }
        }

        if(vehicle_type == 'OLD'){
            if(this.id == 'rto_code2'){
                $('#error-rto_code2').text('');
                $('#error-reg_no').text('');
                $('#rto_code2').css("border-color", "#cccccc");
                $('#reg_no').css("border-color", "#cccccc");
                return true;
            }
        }
        
        if(this.id == 'rsa_start_date' || this.id == 'email' || this.id == 'middel_name'){
            return true;
        }

        if(this.id == 'other_relation' && nominee_relation!='other' ){
            return true;
        }
         if(validate(this.id) === true){
            //console.log(this.id+'--'+this.value); 
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
        var policy_id = $("#policy_id").val();
        console.log('Policy Id');
        console.log(policy_id);
        if(checkIsExist(policy_id) === false){
			console.log('ploicsdf');
            checkIsPolicyExist(engine_no,chassis_no);
        }else{
            console.log('Policy ID : ');
            console.log(policy_id);
            $("#myModal").modal();
        }
        
	}
});


    $("#confirm_policy_submit").on('click',function(){
        $("#vehcile_data_formm").submit();
    });

    $('#confirm_workshop_submit').on('click',function(){
        $("#rsa_workshop_form").submit();
    });
    $('#confirm_onlyrsa_submit').on('click',function(){
        $("#onlyrsa_renewal_form").submit();
    });
    
});


$("#rr310pa_cover_generate_button").on('click',function(){
    // alert('hello moto');
    var form_data = $("#vehcile_data_formm").serialize();
	var status = false;
    var pay_mode = $('#payment_mode').val();
    var vehicle_age = $("#vehicle_age").val();
    var vehicle_type = $("input[name='vehicle_type']:checked").val();
	$("#vehcile_data_formm input,#vehcile_data_formm select").each(function(){
        if(vehicle_type == 'NEW'){
            if(this.id == 'reg_no' || this.id == 'rto_code2'){
                $('#error-rto_code2').text('');
                $('#error-reg_no').text('');
                $('#rto_code2').css("border-color", "#cccccc");
                $('#reg_no').css("border-color", "#cccccc");
                return true;
            }
        }
        if(vehicle_type == 'OLD'){
            if(this.id == 'rto_code2'){
                $('#error-rto_code2').text('');
                $('#error-reg_no').text('');
                $('#rto_code2').css("border-color", "#cccccc");
                $('#reg_no').css("border-color", "#cccccc");
                return true;
            }
        }
        if(this.id == 'rsa_start_date' || this.id == 'email'){
            return true;
        }

       
         if(validate(this.id) === true){
            //console.log(this.id+'--'+this.value); 
            status = true;
        }
    });
	if(status === false){
        var plan_value = $("input[name='plan_rr310']:checked").attr('data-plan');
        $("#plan_amount").text(plan_value);
         var calcPerc = ((plan_value/100)*18);
        var total_amount = (parseFloat(plan_value) + parseFloat(calcPerc));
        $("#plan_amount_with_gst").text(total_amount.toFixed(2));
        var chassis_no = $('#chassis_no').val();
        var engine_no = $('#engine_no').val();
        var policy_id = $("#policy_id").val();
        if(checkIsExist(policy_id) === false){
			
            checkIsPolicyExist(engine_no,chassis_no);
        }else{
            $("#rr310Modal").modal();
        }
        
	}
});




$("#generate_workshop_button").on('click',function(){
    //alert('bzb');
    var form_data = $("#rsa_workshop_form").serialize();
    var status = false;
    var pay_mode = $('#payment_mode').val();
    var nominee_relation = $('#nominee_relation').val();
    $("#rsa_workshop_form input,#rsa_workshop_form select").each(function(){
        var nominee_age = $("#nominee_age").val();
        var vehicle_type = $("input[name='vehicle_type']:checked").val();

        if((parseInt(nominee_age) >= 18) && (this.id == 'appointee_full_name' || this.id == 'appointee_relation' || this.id == 'appointee_age')){
            return true;
        }

        if(vehicle_type == 'NEW'){
            if(this.id == 'reg_no' || this.id == 'rto_code2'){
                $('#error-rto_code2').text('');
                $('#error-reg_no').text('');
                $('#rto_code2').css("border-color", "#cccccc");
                $('#reg_no').css("border-color", "#cccccc");
                return true;
            }
        }

        if(vehicle_type == 'OLD'){
            if(this.id == 'rto_code2'){
                $('#error-rto_code2').text('');
                $('#error-reg_no').text('');
                $('#rto_code2').css("border-color", "#cccccc");
                $('#reg_no').css("border-color", "#cccccc");
                return true;
            }
        }
        
        if(this.id == 'rsa_start_date' || this.id == 'email'){
            return true;
        }

        if(this.id == 'other_relation' && nominee_relation!='other' ){
            return true;
        }
         if(validate(this.id) === true){
            //console.log(this.id+'--'+this.value); 
            status = true;
        }
    });
    if(status === false){
        var plan_value = $("input[name='workshop_plan']:checked").attr('data-plan');
        $("#plan_amount").text(plan_value);
         var calcPerc = ((plan_value/100)*18);
        var total_amount = (parseFloat(plan_value) + parseFloat(calcPerc));
        $("#plan_amount_with_gst").text(total_amount.toFixed(2));
        var chassis_no = $('#chassis_no').val();
        var engine_no = $('#engine_no').val();
        var policy_id = $("#policy_id").val();
       // alert(policy_id);
        if(checkIsExist(policy_id) === false){
            // console.log('ploicsdf');
            checkIsAvailableForWorkshopPolicy1(chassis_no);
        }else{
            $("#confirm_worksop_modal").modal();
        }
        
    }
});

$("#renew_only_rsa_btn").on('click',function(){
    //alert('bzb');
    var form_data = $("#onlyrsa_renewal_form").serialize();
    var status = false;
    // var pay_mode = $('#payment_mode').val();
    var nominee_relation = $('#nominee_relation').val();
    $("#onlyrsa_renewal_form input,#onlyrsa_renewal_form select").each(function(){
        var nominee_age = $("#nominee_age").val();
        var vehicle_type = $("input[name='vehicle_type']:checked").val();

        if((parseInt(nominee_age) >= 18) && (this.id == 'appointee_full_name' || this.id == 'appointee_relation' || this.id == 'appointee_age')){
            return true;
        }

        if(vehicle_type == 'NEW'){
            if(this.id == 'reg_no' || this.id == 'rto_code2'){
                $('#error-rto_code2').text('');
                $('#error-reg_no').text('');
                $('#rto_code2').css("border-color", "#cccccc");
                $('#reg_no').css("border-color", "#cccccc");
                return true;
            }
        }

        if(vehicle_type == 'OLD'){
            if(this.id == 'rto_code2'){
                $('#error-rto_code2').text('');
                $('#error-reg_no').text('');
                $('#rto_code2').css("border-color", "#cccccc");
                $('#reg_no').css("border-color", "#cccccc");
                return true;
            }
        }
        
        if(this.id == 'rsa_start_date' || this.id == 'email'){
            return true;
        }

        if(this.id == 'other_relation' && nominee_relation!='other' ){
            return true;
        }
         if(validate(this.id) === true){
            //console.log(this.id+'--'+this.value); 
            status = true;
        }
    });
    if(status === false){
        var chassis_no = $('#chassis_no').val();
        var engine_no = $('#engine_no').val();
        var policy_id = $("#policy_id").val();
       // alert(policy_id);
        if(checkIsExist(policy_id) === false){
            // console.log('ploicsdf');
            checkIsAvailableForOnlyRSAPolicy1(chassis_no);
        }else{
            $("#confirm_onlyrsa_modal").modal();
        }
        
    }
});

function checkIsAvailableForWorkshopPolicy1(chassis_no){
     $.ajax({
            url : base_url + 'checkIsAvailableForWorkshopPolicy',
            data : {engine_no:chassis_no},
            dataType : 'JSON',
            type : 'POST',
            success:function(response){
                if(response.status == 'false'){
                     $("#message").text('');
                  $("#error-message").text('Either Wrong Chassis No Or Policy Is ALready Created For This Chassis No To Find Go Certificate Section.');
                }else{
                   $("#error-message").text('');
                   $("#confirm_worksop_modal").modal();
                }
            }
        });
}

function checkIsAvailableForOnlyRSAPolicy1(chassis_no){
     $.ajax({
            url : base_url + 'checkIsAvailableForOnlyRSAPolicy',
            data : {engine_no:chassis_no},
            dataType : 'JSON',
            type : 'POST',
            success:function(response){
                if(response.status == 'false'){
                     $("#message").text('');
                  $("#error-message").text('Either Wrong Chassis No Or Policy Is ALready Created For This Chassis No To Find Go Certificate Section.');
                }else{
                   $("#error-message").text('');
                   $("#confirm_onlyrsa_modal").modal();
                }
            }
        });
}

function checkIsPolicyExisforWorkshop(engine_no,chessiss_no){
    $.ajax({
            url:base_url+'checkIsPolicyExist',
            data:{engine_no:engine_no,chessiss_no:chessiss_no},
            dataType:'JSON',
            type:'POST',
            success:function(data){
                if(data.status === 'false'){
                    
                   $("#error-message").text('');
                    $("#confirm_worksop_modal").modal();
                    // console.log('sdfsdfsdfsdf');
                }else if(data.status === 'true'){
                    // console.log('iiiiiii');
                    $("#error-message").text('Policy for given details is allready created to find go to certificate section.');
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
					$("#rr310Modal").modal();
					$("#myModal").modal();
					// console.log('sdfsdfsdfsdf');
                }else if(data.status === 'true'){
					// console.log('iiiiiii');
                    $("#error-message").text('Policy for given details is allready created to find go to certificate section.');
                }
                
            }
        });
}
