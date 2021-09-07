$(document).ready(function(){

$('#cheque_form').validate({
    rules: {
        bank_name : "required",
        cheque_no : {
            required: true,
            maxlength: 6
           
        },
        branch_city: "required",
        cheque_date: "required",
        terms: "required"
    },
    messages: {
        bank_name : {
            required: 'Bank name field is required.'
        },
        cheque_no : {
            required: 'Cheque number field is required.',
            maxlength:'Only 6 characters accepted.'
            

        },
        branch_city : {
            required: 'City name field is required.'
        },
        cheque_date : {
            required: 'Cheque date field is required.'
        },
        terms : {
            required: 'Terms field is required.'
        }
    }
});

// $('.buy_policy_button').click(function() {

    
//     //$('#cheque_form').find('.form-error').remove();

//     // var validator = $("#cheque_form").validate();
//     // validator.resetForm();
//     // $('#cheque_form').find('.error').removeClass('error');

//     var plan_detail_id = $('#plan_id').val();
    
    
//     var policy_price = parseInt($('.buy_policy:checked').data('policy_price'));
//     var policy_price_with_gst = parseInt($('.buy_policy:checked').data('policy_price_with_gst'));
    
//     $('#policy_amt').val(policy_price);
//     $('#tax_perc').val(policy_price_with_gst  - policy_price);
//     $('#payment_amount').val(policy_price_with_gst);

  
//     $('#plan_detail_id').val(plan_detail_id);
//     $('#chequeModal').modal('show');
// });


$(document).on('click','#confirm_button', function(){

    var data = $("#buy_form").serialize();
     $('#chequeFooter').html("<img src='"+base_url+"assets/uploads/loding.gif'>");
            

            $.ajax({
                url: base_url+'add_customer_and_products',
                data: data,
                dataType: 'json',
                type: 'POST',
                success: function(response){
                   
                    if(response.check_duplicate_entry){
                        alert('policy already');
                        window.location.href = base_url+'my-assist';    
                    }else{
                        window.location.href = base_url+'policy-generate-success/'+response.sold_policy_id;    
                        //window.open( base_url+'policy_pdf/'+response.sold_policy_id, target='_blank')
                        //window.location.href = base_url+'products/Product_controller/policyGenerateSuccessPage/'+response.sold_policy_id;    
                        
                    }
                    

                }
            });

});
    

$(document).on('click','.initiate_payment', function(){

    swal({
        title: "Confirm your payment",
        text: "",
        icon: "warning",
        buttons: true,
        buttons: ["Cancel", "Confirm"],
        closeOnConfirm: false,

       
        


    })
    .then((willDelete) => {
      if (willDelete) {

            var payment_type = $('.payment_type:checked').val();
            if(payment_type == 'Customer Cheque'){
                if(!$( "#cheque_form" ).valid()){ 
                    return false; 
                }
            }

            var plan_detail_id = $('#plan_detail_id').val();
            var bank_name = $('#bank_namee').val();
            var branch_city = $('#cheque_cities_list').val();
            var cheque_no = $('#cheque_no').val();
            var cheque_date = $('#cheque_date').val();

            $('#chequeFooter').html("<img src='"+base_url+"assets/uploads/loding.gif'>");
            

            $.ajax({
                url: base_url+'add_customer_and_products',
                data: {
                    plan_detail_id : plan_detail_id, 
                    payment_type : payment_type,
                    bank_name:bank_name,
                    branch_city:branch_city,
                    cheque_no:cheque_no,
                    cheque_date:cheque_date
                },
                dataType: 'json',
                type: 'POST',
                success: function(response){
                    $('#chequeModal').modal('hide');
                    if(response.check_duplicate_entry){
                        alert('policy already');
                        window.location.href = base_url+'my-assist';    
                    }else{
                        window.location.href = base_url+'policy-generate-success/'+response.sold_policy_id;    
                        //window.open( base_url+'policy_pdf/'+response.sold_policy_id, target='_blank')
                        //window.location.href = base_url+'products/Product_controller/policyGenerateSuccessPage/'+response.sold_policy_id;    
                        
                    }
                    

                }
            });
                
      } else {
        $('#chequeModal').modal('hide');
      }
    });


    
});


// $(document).on('click','.initiate_payment', function(){

//     swal({
//       title: "Are you sure?",
//       text: "Your will not be able to recover this imaginary file!",
//       type: "warning",
//       showCancelButton: true,
//       confirmButtonClass: "btn-success",
//       confirmButtonText: "Confirm",
//       closeOnConfirm: false
//     },
//     function(){
//       swal("Deleted!", "Your imaginary file has been deleted.", "success");
//     });


//     var payment_type = $('.payment_type:checked').val();

//     if(payment_type == 'Customer Cheque'){
//         if(!$( "#cheque_form" ).valid()){ 
//             return false; 
//         }
//     }

//     var plan_detail_id = $('#plan_detail_id').val();
//     var bank_name = $('#bank_namee').val();
//     var branch_city = $('#cheque_cities_list').val();
//     var cheque_no = $('#cheque_no').val();
//     var cheque_date = $('#cheque_date').val();

//     $('#chequeFooter').html("<img src='"+base_url+"assets/uploads/loding.gif'>");
    

//     $.ajax({
//         url: base_url+'add_customer_and_products',
//         data: {
//             plan_detail_id : plan_detail_id, 
//             payment_type : payment_type,
//             bank_name:bank_name,
//             branch_city:branch_city,
//             cheque_no:cheque_no,
//             cheque_date:cheque_date
//         },
//         dataType: 'json',
//         type: 'POST',
//         success: function(response){
//             $('#chequeModal').modal('hide');
//             if(response.check_duplicate_entry){
//                 alert('policy already');
//                 window.location.href = base_url+'my-assist';    
//             }else{
                
//                 //window.location.href = base_url+'my-assist';    
//                 window.open( base_url+'policy_pdf/'+response.sold_policy_id, target='_blank')
//                 window.location.href = base_url+'my-assist';    

               
//             }
            

//         }
//     });
// });


$(document).on('click','.payment_type',function(){
    switch($(this).val()){
        case 'Customer Cheque': $('#cheque-main').show();
            break;
        case 'Dealer Cheque': $('#cheque-main').hide(); 
            break;
        default: $('#cheque-main').show();
    }

});





});




