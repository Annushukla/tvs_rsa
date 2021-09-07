$(document).keypress(function(e) {
    if(e.which == 13) {
        fetch_data();
        
    }
});

$(document).ready(function(){
 
    
   
    $('#export_csv').click(function(){
        $('<input />')
            .attr('type', 'hidden')
            .attr('name', "download_file")
            .attr('value', "Export CSV")
            .appendTo('#form_index');

        //$('#export_pdf').remove();
        $( "#form_index" ).submit();
     

        
    });

    $('#export_pdf').click(function(){
        $('<input />')
            .attr('type', 'hidden')
            .attr('name', "download_file")
            .attr('value', "Export PDF")
            .appendTo('#form_index');
            //$('#export_csv').remove();
        $( "#form_index" ).submit();
    });


    $('#submit_filter').click(function(){
        fetch_data();
    });


    $('#select_dealer').change(function(){
        var dealer = this.value;
        $.ajax({
            url: base_url+'get-agents',
            data : { dealer : dealer },
            dataType: 'Json',
            type: 'POST',
            success: function(response){
                $('#select_agent').html(response.ajents)
              
                
            }
        })
    });  


    

    $("#form_update_status").validate({
        errorElement: 'span',
        errorClass: 'err',
        rules: {
            payment_status:{
                required: true
            }
        },
        messages: {
            payment_status : {
                required: 'Payment status field is required.'
            }
        }
    });

    $("#form_cancle_policy").validate({
        errorElement: 'span',
        errorClass: 'err',
        rules: {
            cancle_comment:{
                required: true
            }
        },
        messages: {
            cancle_comment : {
                required: 'Comment field is required.'
            }
        }
    });

    $('#submit_change_status').on('click', function() {
     
        if(!$( "#form_update_status" ).valid()){ return false; }else{
            $('#update_status_model').modal('hide');
            swal({
                title: "",
                text: "",
                icon: "assets/admin/giphy.gif",
                button: false,
            });
        
            $.ajax({
                url: base_url+'admin/AdminController/updatePaymentStatus',
                data : $("#form_update_status").serialize(),
                dataType: 'Json',
                type: 'POST',
                success: function(response){
                
                    if(response.success == 1){
                        fetch_data();
                        //table.ajax.reload(null, false);
                        swal({
                          icon: "success",
                          title: "Success",
                          text: response.suc_msg,
                          timer: 2000,
                        });
                        
                    }else{
                        swal({
                          icon: "error",
                          title: "Error",
                          text: response.err_msg,
                          timer: 2000,
                        });

                        
                    }
                    
                }
            })
        }
    }); 

    $('#btn_cancel_policy').on('click', function() {

        if(!$( "#form_cancle_policy" ).valid()){ return false; }else{

            $('#cancle_policy_model').modal('hide');
            swal({
                title: "",
                text: "",
                icon: "assets/admin/giphy.gif",
                button: false,
            });
        
            $.ajax({
                url: base_url+'admin/ReportController/canclePolicy',
                data : $("#form_cancle_policy").serialize(),
                dataType: 'Json',
                type: 'POST',
                success: function(response){
                
                    if(response.success == 1){
                        fetch_data();
                        swal({
                          icon: "success",
                          title: "Success",
                          text: response.suc_msg,
                          timer: 2000,
                        });
                        
                    }else{
                        swal({
                          icon: "error",
                          title: "Error",
                          text: response.err_msg,
                          timer: 2000,
                        });

                        
                    }
                    
                }
            })
        }
    });
});



function statusUpdate(id,payment_status,policy_number){
    $('#policy_label').html(policy_number);
    $('#payment_details_id').val(id);
    $('#policy_number').val(policy_number);
    //$('#update_payment_status').val(payment_status);
   // if(payment_status == 'Pending'){
         $('#update_payment_status').val('');
    //}
    
    $('#update_status_model').modal('show');
}

function canclePolicy(id,payment_status,policy_number){

    $.ajax({
        url: base_url+'admin/ReportController/getCommentByPolicy',
        data : { policy_number : policy_number } ,
        dataType: 'Json',
        type: 'POST',
        success: function(response){
            $('#cancle_policy_communication').html(response);   
            $('#cancle_comment').val('');
            $('#cancle_status_update').val('');
            $('#cancle_payment_details_id').val(id);
            $('#cancel_policy_label').html(policy_number);
            $('#cancel_policy_number').val(policy_number);
            $('#cancle_policy_model').modal('show');
        }
    });
}
function getPolicyDetails(id){
    $.ajax({
        url: base_url+'admin/ReportController/getPolicyDetails/'+id,
        dataType: 'Json',
        type: 'POST',
        success: function(response){
            $('#policy_detail_body').html(response);    
            $('#policy_detail_model').modal('show');         
            
        }
    })

   
}

