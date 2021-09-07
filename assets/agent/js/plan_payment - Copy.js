$(document).ready(function(){

    $('#get_cust_veh_details').click(function(event) {
        var vehicle_data = $('#vehicle_details').val();
        var product_type = $('#product_type').val();
        var vehicle_details_arr = [];

        $.ajax({

            url: base_url+'fetch_vehicle_details?vehicle_data='+vehicle_data,
            dataType: 'Json',
            type: 'GET',
            success: function(response){
                $('#veh_det_err').remove();
                vehicle_details_arr = response;
            },
            error: function(response){
                $('#veh_det_err').remove();
                var errors = response.responseJSON;
                var errors_html = '<h3 id="veh_det_err" class="text-danger">'+errors.message+'</h3>';
                $('#vehicle_details_form').append(errors_html);
            },
            complete: function(response){
                $('#plan_details_table').empty()
                $('#plan_details_panel').hide();
                $('#cust_info_panel').show();

                var vehicle_details = vehicle_details_arr;

                $('#vehcile_data_form')[0].reset();
                $('.cust_info_field').each(function(){
                    $(this).removeAttr('style');
                });

                if(Object.keys(vehicle_details).length > 0){
                    var fetched_vehicle_details = { 
                        "engine_no" : vehicle_details.engine_no,
                        "chassis_no" : vehicle_details.chassis_no,
                        "manufacturer" : vehicle_details.make_id,
                        "model" : vehicle_details.model_id,
                        "registration_no" : vehicle_details.vehicle_registration_no,
                        "first_name" : vehicle_details.fname,
                        "last_name" : vehicle_details.lname,
                        "aadhar_no" : vehicle_details.aadhar_card_no,
                        "pan_card" : vehicle_details.pan_card_no,
                        "product_type" : vehicle_details.product_type,
                        "vehicle_type" : vehicle_details.vehicle_type
                    }

                    $.each(fetched_vehicle_details, function(index, item){
                        $('#'+index).val(item);
                    });

                    if(vehicle_details.make_id != ''){
                        $('#product_type').attr('data-manufacturer',vehicle_details.make_id);
                    }
                    if(vehicle_details.model_id != ''){
                        $('#product_type').attr('data-model',vehicle_details.model_id);
                    }




                    $('.cust_info_field').each(function(){
                        if($(this).val() == '' && $(this).attr('id') != 'cust_addr2'){
                            $(this).css({'background-color':'yellow','border':'2px solid red','color':'black'});
                        }
                    });
                    // states();

                    if($('#product_type').val() != ''){
                        $('#product_type').trigger('change');
                    }
                }
            }
        });
    });



    $(document).on('change','#states_list', function(){
        var sid = $(this).val();
        $.ajax({
            url: base_url+'get_cities_list',
            data:{
                sid:sid
            },
            dataType: 'Json',
            type: 'POST',
            success: function(response){
                var cities = response;
                var cities_html = "";
                var html = '<div class="form-group"><label class="col-sm-5 control-label">City</label><div class="col-sm-7"><select id="cities_list" name="city" class="form-control cust_info_field" autofocus><option value="">Select City</option></select></div></div>';
                $('#cities').html(html);  
                $.each(cities, function(index,item){
                    cities_html += '<option value="'+item.id+'">'+item.name+'</option>';

                });
                $('#cities_list').html(cities_html);


            }
        });
    });

    $('#vehicle_details').keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            $('#get_cust_veh_details').trigger('click');
        }
    });


    $(document).on('click', '.buy_policy', function(event) {
        var plan_id = $(this).data('plan_id');
        var policy_name = $(this).data('policy_name');

        $('#chequeMsg').remove();
        $('#chequeForm').show();
        $('#buy_policy_plan_id').remove();
        $('#chequeForm').show();
        $('#chequeCont').show();
        $('#cheque_form')[0].reset();
        $('.initiated_payment').removeAttr('data-dismiss').text('Submit').addClass('initiate_payment').removeClass('initiated_payment');
        $('body').append('<input type="hidden" id="buy_policy_plan_id" value="'+plan_id+'">');
        $('#otp').removeAttr('data-otp_sent').text('Send OTP');
        $('#policy_name').html('Buy '+policy_name);

        var policy_price = parseInt($('.buy_policy:checked').data('policy_price'));

        var gst_tax = policy_price * 18 / 100;
        var policy_price_total = Math.round(policy_price + gst_tax,2);
        $('#policy_amt').val(policy_price);
        $('#tax_perc').val(gst_tax);
        $('#payment_amount').val(policy_price_total);

        get_banks();
        get_cities();
        date_picker();
        cheque_form_validate();

    });

    $(document).on('click','.payment_type',function(){
        switch($(this).val()){
            case 'Customer Cheque': $('#cheque-main').show();
                break;
            case 'Dealer Cheque': $('#cheque-main').hide(); 
                break;
            default: $('#cheque-main').show();
        }

    });

     $(document).on('click','.initiate_payment', function(){
       
        if(!$("#cheque_form").valid()){
            return false;
        }

        $("#loading").show();
        $(".initiate_payment").hide();
      
        var data = { 
            payment_type: $('input[name="payment_type"]:checked').val(),
            bank_name: $('#bank_list').val(),
            branch_city: $('#cheque_cities_list').val(),
            cheque_no: $('#cheque_no').val(),
            cheque_date: $('#cheque_date').val(),
            policy_amt: $('#policy_amt').val(),
            payment_amount: $('#payment_amount').val(),
            terms: $('#terms').val(),
            policy_plan_id : $('#buy_policy_plan_id').val(),
            tax_perc_amount: $('#tax_perc').val()
        }


        $.ajax({
            url: base_url+'add_customer_and_products',
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(response){
                var sold_policy_data = response;
                console.log(response);
                $("#loading").hide(); // To Hide progress bar


                $('#chequeForm').hide();
                $('#chequeCont').hide();
                var html = '<div class="panel panel-success" id="chequeMsg"><div class="panel-heading"> Details Have Been Stored Successfully! <br> Your Policy No. is : '+sold_policy_data.sold_policy_no+'</div></div>';
                $('#chequeModalBody').append(html)
                $('.initiate_payment').attr('data-dismiss','modal').text('Close').addClass('initiated_payment').removeClass('initiate_payment');

                var url = base_url+'policy_pdf/'+sold_policy_data.sold_policy_id;
                window.location.href = base_url+'my-assist'; 
                window.open(
                  url,
                  '_blank' 
                );

            }
        });
    });


   

   
    $('#otp').click(function(){
        if(!$(this).attr('data-otp_sent')){
            $.ajax({
                url: base_url+'send_otp_agent',
                type: 'POST',
                beforeSend: function(){
                    $('#otp').text('Sending OTP');
                },
                success: function(response){
                    if (response == true) {
                        $('#otp').text('Validate OTP').attr('data-otp_sent','1');
                        $('#otpText').empty();
                        var otpHtml = '<label for="example-search-input" class="col-md-2 col-form-label">OTP</label><div class="col-md-8"><input class="form-control" name="otp" type="search" value="" id="otpTextBox" placeholder="Enter OTP"></div>';
                        $('#otpText').append(otpHtml);
                    }
                    else{
                        alert('There Was an error while sending email');
                    }
                }
            });
        }
        else{
            $.ajax({
                url: base_url+'verify_otp_agent',
                data: {
                    otp: $('#otpTextBox').val()
                },
                type: 'POST',
                beforeSend: function(){
                    alert('Verifying OTP');
                },
                success: function(response){
                    if (response == true) {
                        var policy_price = parseInt($('.buy_policy:checked').data('policy_price'));
                        var gst_tax = policy_price * 18 / 100;
                        var policy_price_total = Math.round(policy_price + gst_tax,2);
                        $('#chequeCont').empty();
                        $('#chequeFooter').remove();
                        var chequeConthtml = '<div class="radio"> <div class="radio"> <input id="radio-1" name="payment_type" type="radio" value="1" checked="" class="payment_type"> <label for="radio-1" class="radio-label">Customer Cheque</label> <input id="radio-2" name="payment_type" type="radio" value="2" class="payment_type"> <label for="radio-2" class="radio-label">Dealer Cheque</label> </div> </div> <div id="cheque-main"> <form id="cheque_form"> <div class="row"> <div class="col-md-6"> <div class="form-group"> <label for="bank_list">Select Bank</label> <select id="bank_list" name="bank_name" class="form-control"></select> </div> </div> <div class="col-md-6"> <div class="form-group"> <label for="branch_city">Select Bank Branch City</label> <select id="cheque_cities_list" type="text" name="branch_city" class="form-control"></select> </div> </div> </div> <div class="row"> <div class="col-md-6"> <div class="form-group"> <label for="cheque_no">Enter Cheque No.</label> <input id="cheque_no" type="text" name="cheque_no" class="form-control" placeholder="Enter Cheque No."> </div> </div> <div class="col-md-6"> <div class="form-group"> <label for="cheque_date">Enter Cheque Date</label> <input type="text" name="cheque_date" value="" id="cheque_date" placeholder="E.g: MM/DD/YYYY" class="form-control datepicker" /> </div> </div> </div> <div class="row" id="cheque_amt_cont"> <div class="col-md-6"> <div class="form-group"> <label for="policy_amt">Policy Amount.</label> <input id="policy_amt" type="text" name="policy_amt" readonly="" class="form-control" value="'+policy_price+'"></div></div> <div class="col-md-6"> <div class="form-group"> <label for="payment_amount">Amount to be paid With 18% Gst.</label> <input id="payment_amount" type="text" name="payment_amount" readonly="" class="form-control" value="'+policy_price_total+'"></div></div> </div> <div class="checkbox"> <label><input type="checkbox" name="terms" value="1" id="terms"> Buy Terms And Conditions</label> </div> </form> </div>';
                        $('#chequeCont').append(chequeConthtml);

                        var cheque_footer_html = '<div class="modal-footer" id="chequeFooter"><button type="button" class="btn btn-primary initiate_payment">Submit</button></div>';
                        $('#cheque_modal_content').append(cheque_footer_html);
                        get_banks();
                        get_cities();
                        date_picker();
                        cheque_form_validate();
                    }
                    else{
                        alert('Oops! OTP Mismatch.');
                    }
                }
            });
        }
    }); 

    $('#vehicle_form_reset').click(function(){
        $('#vehcile_data_form')[0].reset();
        $('#veh_det_err, #veh_form_input_hidden').remove();
        $('.cust_info_field').each(function(){
            $(this).removeAttr('style');
        });
    });

    $('#disp_cust_vehicle').click(function(){
        $('#custom-search-input').show();
        $('.veh_form_input_hidden').each(function(index, item){
            var input_field = $(this).attr('id').replace("_hidden","");
            $('#'+input_field).val($(this).val());
            $('#plan_details_table').empty()
            $('#plan_details_panel').hide();
            $('#cust_info_panel').show();
        });
        //  $('#get_cust_veh_details').trigger('click');
    });

    $('#product_type').change(function(){
        var json_data = {
            prod_type : $(this).val()
        };

        $.ajax({
            url: base_url+'fetch_manufacturers',
            data: json_data,
            dataType: 'Json',
            type: 'POST',
            success: function(response){
                $('.make_list,.model_list').remove();
                $.each(response, function(index,item){
                    var manufacturer_html = '<option class="make_list" value="'+item.id+'">'+item.make+'</option>';
                    $('#manufacturer').append(manufacturer_html);
                });

                if($('#product_type').attr('data-manufacturer')){
                    $('#manufacturer').val($('#product_type').data('manufacturer')); 
                    $('#manufacturer').trigger('change');
                    $('#product_type').removeAttr('data-manufacturer');
                }
                else{
                    $('#manufacturer').css({
                        "background-color": 'yellow',
                        "border": "2px solid red",
                        "color": "black"
                    });
                }

            }
        });
    });

    $(document).on('change', '#manufacturer', function(){
        var json_data = {
            make_id : $(this).val()
        };

        $.ajax({
            url: base_url+'fetch_model',
            data: json_data,
            dataType: 'Json',
            type: 'POST',
            success: function(response){
                $('.model_list').remove();
                $.each(response, function(index,item){
                    var model_html = '<option class="model_list" value="'+item.id+'">'+item.model+'</option>';
                    $('#model').append(model_html);
                });
                if($('#product_type').attr('data-model')){
                    $('#model').val($('#product_type').data('model'));
                    $('#product_type').removeAttr('data-model');
                }
                else{
                    $('#model').css({
                        "background-color": 'yellow',
                        "border": "2px solid red",
                        "color": "black"
                    });
                }
            }
        });
    });


    if($('#state').val() != undefined){
        $('#states_list').trigger('change');
    }

    if($('#product_type').val() != ''){
        $('#product_type').trigger('change');
    } 

    if ($('#plan_details_panel').attr('style') == 'display:block') {
        $('#vehicle_plan_details').trigger('click');
    }
});



function afterRefresh(){
    var product_type = "";
    $.ajax({
        url: base_url+'fetch_plan_details',
        data: {
            product_type:product_type
        },
        dataType: 'Json',
        type: 'POST',
        success: function(response){
            $('.plan_details_table').empty();
            $.each(response, function(index,item){
                var plan_details_html = '<div class="row"><div class="col-md-12" id="fixed-head-side"><h6 class="text-center">'+item.name+'</h6><div id="no-more-tables" style="overflow: scroll;height:600px;"><table class="col-sm-12 table-bordered table-striped table-condensed cf nopadding"><thead class="cf"><tr class="plan_names"><th>Plan Features - '+item.name+'</th>';


                $.each(item.product_plans, function(key,value){
                    plan_details_html += '<th>'+value.name+value.price+'<div class="radio"><input class="buy_policy" id="plan_id_'+value.id+'" name="plan_id" type="radio" data-plan_id="'+value.id+'" data-toggle="modal" data-target="#chequeModal" data-policy_name="'+value.name+value.price+'" data-policy_price="'+value.price+'"><label for="plan_id_'+value.id+'" class="radio-label"></label></div></th>';
                });

                plan_details_html += '</tr></thead><tbody class="plan_features">';

                $.each(item.plan_features, function(index, item){
                    plan_details_html += '<tr class="plan_values"><td data-title="'+item.name+'">'+item.name+'</td>';

                    $.each(item.values, function(index,item){
                        var plan_value = '';
                        switch(item.value){
                            case 'Y': plan_value = '<i class="fa fa-check text-green" aria-hidden="true"></i>';
                                break;
                            case 'N': plan_value = '<i class="fa fa-times text-red" aria-hidden="true"></i>';
                                break;
                            default: plan_value = item.value;
                                         }
                        plan_details_html += '<td data-title="'+item.value+'">'+plan_value+'</td>';
                    });
                    plan_details_html += '</tr>';
                });

                plan_details_html += '</tr></tbody></table></div></div>';
                $('#plan_details_table').append(plan_details_html);
                $('#cust_info_panel,#custom-search-input').hide();
                $('#disp_cust_vehicle').show();
                $('#plan_details_panel').show();

            });
        }
    });
}


function cheque_form_validate(){
    $('#cheque_form').validate({
        rules: {
            bank_name : "required",
            cheque_no : {
                required: true,
                number: true
            },
            branch_city: "required",
            cheque_date: "required",
            terms: "required",
        },
        message: {
            cheque_no : {
                number: 'Oops! Only numbers are accepted'
            }
        }
    });
}