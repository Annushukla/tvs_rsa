$(document).ready(function(){

    $("#campaign_close_btn").click(function(){
        $(".dashboad-overlay").removeClass('in');
        $(".dashboad-modal").hide();
    });

    var dealer_campaign_status = $("#dealer_campaign_status").val();
    // console.log('  kmkld '+dealer_campaign_status);
    if(dealer_campaign_status==='true'){
        // console.log(dealer_campaign_status);
        $(".dashboad-overlay").removeClass('in');
        $(".dashboad-modal").hide();
    }
    $("#balancemodal").show();

$("#campaign_submit_btn").on('click',function(){
    event.preventDefault();
        var form_status = [];

    $("#campaign_form input").each(function(){
        var valid_= validate(this.id);

        if(valid_ == true){

             form_status = false;
        }
        else{

             form_status = true;

        }
       
    });

        if(form_status===true){
            $("#campaign_form").submit();
        }

});
// campaign_form

$('#wallet_modal').modal('show');
$('#invoice_modal').modal('show');
$('#Infomodal').modal('show');
$('#oriental_popup').modal('show');
$('#balancemodal').modal('show');

 $('#oriental_declaration').on('click',function() {
    if ($(this).is(':checked')) {
      $("#oicl_decl_submit").attr('disabled',false);
    }else{
        $("#oicl_decl_submit").attr('disabled',true);
    }
  });
$('#pacover_form').on('click',function(){
$('#myModal').modal('show');
var username = $('#username').val();
var userpassword = $('#userpassword').val();
});
  $("#dealer_form_submit").click(function(e){
  var form_data = $("#login_form").serialize();
    $.ajax({
            url:base_url+'submitloginform',
            data:form_data,
            type:'POST',
            success:function(response){
            	console.log(response);
            	var result = JSON.parse(response);
                console.log(result);
                console.log(result.user_exist);
                if(result.user_exist == 'true'){
                	window.location.href = base_url+'generate_policy';
                }
                if(response.status == 'true'){
                	if(response.error_email == 'true'){
                		$("#error_email").html('Please enter valid email');
                	}
                }
            }
        });
});

var isChecked = $('#today').prop('checked');
if(isChecked === true){
    var selected_days = 'today';
getPolicyPayOut(selected_days);
}
$('#today').on('click',function(){
    var isChecked = $('#today').prop('checked');
    if(isChecked === true){
    var selected_days = 'today';
getPolicyPayOut(selected_days);
}
});

$('#month').on('click',function(){
    var isChecked = $('#month').prop('checked');
    if(isChecked === true){
    var selected_days = 'month';
getPolicyPayOut(selected_days);
}
});
$('#quarter').on('click',function(){
    var isChecked = $('#quarter').prop('checked');
    if(isChecked === true){
    var selected_days = 'quarter';
getPolicyPayOut(selected_days);
}
});
$('#year').on('click',function(){
    var isChecked = $('#year').prop('checked');
    if(isChecked === true){
    var selected_days = 'year';
getPolicyPayOut(selected_days);
}
});


});

function getPolicyPayOut(selected_days){
    $.ajax({
    url:base_url+'getPolicyPayout',
    data:{selected_days:selected_days},
    type:'POST',
    dataType:'JSON',
    success:function(response){
        $("#premium").html((parseInt(response.no_of_policies) * 212));
        $("#policies").text(response.no_of_policies);
        $("#payout").text((parseInt(response.no_of_policies) * 80));
        console.log(response);
    }
});
}