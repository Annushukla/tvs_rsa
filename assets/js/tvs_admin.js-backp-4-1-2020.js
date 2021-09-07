
    $(document).ready(function () {

        $("#send-gst-to-dealer").on('click',function(){
            var dealer_id = $("#dealer_id").val();
            var invoice_id = $("#invoice_id").val();
            var total_deposit_amount = $("#total_deposit_amount").val();
            var total_policy_commission_gst = $("#total_policy_commission_gst").val();
            var tds_deducted = $("#tds_deducted").val();
            $.ajax({
                url : base_url+'admin/submitDealerGstAmount',
                data : {dealer_id:dealer_id,invoice_id:invoice_id,total_deposit_amount:total_deposit_amount,total_policy_commission_gst:total_policy_commission_gst,tds_deducted:tds_deducted},
                type : 'POST',
                dataType : 'JSON',
                success : function(response){
                    console.log(response);
                    if(response.status == 'true'){
                        window.location.href = '';
                    }else{
                        // $("#update_transaction_modal").modal('hide');
                    }
                }
            }) ;
        });


  $("#referback_invoice").on('click',function(){
            $('#success_msg').text('');
            $('#invoice_er').text('');
            var dealer_id = $("#dealer_id").val();
            var invoice_id = $("#invoice_id").val();
            $.ajax({
                url : base_url+'Dealer_Approve/ReferbackInvoiceStatus',
                data : {dealer_id:dealer_id,invoice_id:invoice_id},
                type : 'POST',
                dataType : 'JSON',
                success : function(response){
                    console.log(response);
                    if(response == 'true'){
                        location.reload();
                    }else{
                        // $("#update_transaction_modal").modal('hide');
                    }
                }
            }) ;
});

$("#reject_invoice").on('click',function(){
    var dealer_id = $("#dealer_id").val();
    var invoice_id = $("#invoice_id").val();
    $('#rej_invoice_id').val(invoice_id);
    $('#rej_dealer_id').val(dealer_id);
    $('#reject_modal').modal();
});

        rr310_new_policy = $('#rr310_new_policy').DataTable({

            "scrollX": true,
            "processing": true,
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': false,
            'dom': 'Bfrtip',
            "buttons": ['excel', 'csv', 'pdf', 'print'],
            // Load data for the table's content from an Ajax source
            "ajax": {
                'url': base_url + 'admin/rr310_new_policy_ajax',
                "type": "POST",
                "data" : {},
                "dataType": "json",
                "dataSrc": function (jsonData) {
                    return jsonData.data;
                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],

        });

        rr310_renew_policy = $('#rr310_renew_policy').DataTable({

            "scrollX": true,
            "processing": true,
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': false,
            'dom': 'Bfrtip',
            "buttons": ['excel', 'csv', 'pdf', 'print'],
            // Load data for the table's content from an Ajax source
            "ajax": {
                'url': base_url + 'admin/rr310_renew_policy_ajax',
                "type": "POST",
                "data" : {},
                "dataType": "json",
                "dataSrc": function (jsonData) {
                    return jsonData.data;
                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],

        });

        var approval_status_val = $('#approval_status_val').val();
        table = $('#policy_datatable').DataTable({

            "scrollX": true,
            "processing": true,
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': false,
            'dom': 'Bfrtip',
            "buttons": ['excel', 'csv', 'pdf', 'print'],
            // Load data for the table's content from an Ajax source
            "ajax": {
                'url': base_url + 'admin/admin_approved_dealer_ajax',//admin_approved_dealer_ajax//cancel_policies_ajax
                "type": "POST",
                "data" : {approval_status_val : approval_status_val },
                "dataType": "json",
                "dataSrc": function (jsonData) {
                    return jsonData.data;
                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],

        });
        $('#filter_trans_date').on('click', function () {
            var trans_start_date = $('#trans_start_date').val();
            var trans_end_date = $('#trans_end_date').val();
            var dealer_code = $('#dealer_code').val();
            filter_trans_datatabl = $('#policy_datatable').DataTable({
                'paging': true,
                'destroy': true,

                "ajax": {
                    'url': base_url + 'admin/admin_approved_dealer_ajax',
                    "type": "POST",
                    "data": {'trans_start_date': trans_start_date, 'trans_end_date': trans_end_date,
                                'approval_status_val' : approval_status_val , 'dealer_code' : dealer_code
                            },
                    "dataType": "json",
                    "dataSrc": function (jsonData) {
                        return jsonData.data;
                    }
                },

                "scrollX": true,
                "processing": true,
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': false,
                'dom': 'Bfrtip',
                "buttons": ['excel', 'csv', 'pdf', 'print'],
                // Load data for the table's content from an Ajax source


                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        "targets": [0], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ],

            });

        });

        $('#filter_by_date').on('click', function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            table1 = $('#policy_datatable').DataTable({
                'paging': true,
                'destroy': true,

                "ajax": {
                    'url': base_url + 'admin/view_policy_ajax',
                    "type": "POST",
                    "data": {'start_date': start_date, 'end_date': end_date},
                    "dataType": "json",
                    "dataSrc": function (jsonData) {
                        return jsonData.data;
                    }
                },

                "scrollX": true,
                "processing": true,
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': false,
                'dom': 'Bfrtip',
                "buttons": ['excel', 'csv', 'pdf', 'print'],
                // Load data for the table's content from an Ajax source


                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        "targets": [0], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ],

            });

        });

        var invoice_approval_status = $('#invoice_approval_status').val();
        invoice_approval_tabl = $('#invoice_approval_tabl').DataTable({

            "scrollX": true,
            "processing": true,
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': false,
            'dom': 'Bfrtip',
            "buttons": ['excel', 'csv', 'pdf', 'print'],
            // Load data for the table's content from an Ajax source
            "ajax": {
                'url': base_url + 'admin/invoice_approval_ajax',   
                "type": "POST",
                "dataType": "json",
                "data" : {invoice_approval_status:invoice_approval_status},
                "dataSrc": function (jsonData) {
                    return jsonData.data;
                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],

        });
        var gst_approval_status = $('#gst_approval_status').val();
        gst_approval_tabl = $('#gst_approval_tabl').DataTable({

            "scrollX": true,
            "processing": true,
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': false,
            'dom': 'Bfrtip',
            "buttons": ['excel', 'csv', 'pdf', 'print'],
            // Load data for the table's content from an Ajax source
            "ajax": {
                'url': base_url + 'admin/gst_approval_ajax',   
                "type": "POST",
                "dataType": "json",
                "data" : {gst_approval_status:gst_approval_status},
                "dataSrc": function (jsonData) {
                    return jsonData.data;
                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],

        });
       $("#approveDealer").on('click',function(){
        var dealer_bank_tran_id =   $("#dealer_bank_tran_id").val();
        var dealer_id =   $("#dealer_id").val();
        var amount =   $("#amount").val();
        var transaction_type =   $("#transaction_type").val();
            if(transaction_type =='withdrawal'){
                 var admin_trans_no = $('#admin_trans_no').val();
            }else{
                    admin_trans_no='';   
            }
                $.ajax({
                url:base_url +'admin/approveDealer',
                data:{'dealer_bank_tran_id':dealer_bank_tran_id, 'dealer_id': dealer_id,'amount':amount,
                        'transaction_type':transaction_type,admin_trans_no:admin_trans_no},
                dataType:'JSON',
                type:'POST',
                success:function(response){
                    console.log(response);
                    if(response.status == 'true'){
                        window.location.href='';
                    }
                }
            });
        
       });

$(document).on('change',".approval_status",function(){
    var app_status_val = $(this).val();
    var d_bank_trans_id = $(this).data('transactn_id');
    var dealer_id = $(this).data('dealer_id');
    var amount = $(this).data('amount');
    var transaction_type = $(this).data('transaction_type');
    var approval_status = $(this).data('selected_app_status');
    if(app_status_val=='approved'){
         $('#dealer_bank_tran_id').val(d_bank_trans_id);
         $('#dealer_id').val(dealer_id);
         $('#amount').val(amount);
         $('#action_type').val(app_status_val);
         $('#transaction_type').val(transaction_type);
            if(transaction_type == 'withdrawal'){
                // console.log(transaction_type);
                 $("#transaction_block").css("display", "block");
            }else{
                    $("#transaction_block").css("display", "none");
            }

         $("#cancelPolicyApprove").modal('show');
    }
    if(app_status_val=='rejected'){
            $('#hid_dealer_bank_tran_id').val(d_bank_trans_id);
            $('#hid_dealer_id').val(dealer_id);
            $('#hid_status').val(app_status_val);
            $('#status_modal_header').text('Reject the Transaction');
            $('#status_title').text('Comment is require to Reject Transaction .');
            $('#statusModalid').modal('show');

        }
        if(app_status_val=='pending'){
            $('#hid_dealer_bank_tran_id').val(d_bank_trans_id);
            $('#hid_dealer_id').val(dealer_id);
            $('#hid_status').val(app_status_val);
            $('#status_modal_header').text('Pending the Transaction');
            $('#status_title').text('Comment is require to Refferback Transaction .');
            $('#statusModalid').modal('show');

        }
        if(app_status_val=='referback'){
            $('#hid_dealer_bank_tran_id').val(d_bank_trans_id);
            $('#hid_dealer_id').val(dealer_id);
            $('#hid_status').val(app_status_val);
            $('#status_modal_header').text('Referback the Transaction');
            $('#status_title').text('Comment is require to Refferback Transaction .');
            $('#statusModalid').modal('show');

        }

    
});

    
$("#comment").val("");
$("#gst_referback").on('click',function(){
    $(".comment_div").show();
    var comment = $("#comment").val();
    var hid_gst_invoice = $("#hid_gst_invoice").val();
    var hid_dealer_id = $("#hid_dealer_id").val();
    // alert('comment- '+comment+' -hid_dealer_id'+hid_dealer_id);
    if(hid_dealer_id !="" && hid_gst_invoice !="" && comment!=""){
        $.ajax({
            url : base_url+'Dealer_Approve/ReferbackGstInvoice',
            type : 'POST',
            data : {hid_gst_invoice:hid_gst_invoice,hid_dealer_id:hid_dealer_id,comment:comment},
            dataType : 'JSON',
            success : function(response){
                if(response=="true"){
                    window.location.href=base_url+'admin/gst_approval/referback';
                }else{
                    window.location.href=base_url+'admin/gst_approval/pending';
                }
            }
        })
    }
});


});
////Doucment Closing/////

function UpdateTransaction_data(bank_id){
    if(bank_id!=""){

            $.ajax({
                url : base_url+'Dealer_Approve/GetTransaction_data',
                data : {bank_id:bank_id},
                type : 'POST',
                dataType : 'json',
                success : function(response){
                    console.log(response);
                    if(response.status==true){
                        $('#update_transaction_no').val(response.bank_data.bank_transaction_no);
                        $('#update_amount').val(response.bank_data.deposit_amount);
                        $('#update_bank_trans_id').val(response.bank_data.id);
                        $("#update_transaction_modal").modal('show');
                    }else{
                        $("#update_transaction_modal").modal('hide');
                    }
                }
            }) ;
    
}
    }


function Reconcile(bank_id){
    $('#bank_id').val(bank_id);
    if(bank_id!=""){
        $('#reconcile_modal').modal('show');
    }
}

function view_invoice_data(invoice_id,dealer_id){
     $('#success_msg').text('');
     $('#invoice_er').text('');
    if(invoice_id !=""){
        $('#invoice_id').val(invoice_id);
        $('#dealer_id').val(dealer_id);
        $.ajax({
            url : base_url+'Dealer_Approve/ViewInvoiceData',
            data : {invoice_id : invoice_id},
            type : 'POST',
            dataType : 'JSON',
            success : function(response){
                if(response!=""){
                    // console.log(response);
                    // alert(response.invoice_status);
                    $('.invoice_body').html(response.html);
                    $('#total_deposit_amount').val(response.total_deposit_amount);
                    $('#deposit-amount').text(response.total_deposit_amount);
                    $('#total_policy_commission_gst').val(response.total_policy_commission_gst);
                    $('#tds_deducted').val(response.tds_deducted);
                        if(response.invoice_status == 'approved'){
                            $('#send-gst-to-dealer').hide();
                            $('#referback_invoice').hide();
                            $('#reject_invoice').hide();
                        }
                        else if(response.invoice_status == 'rejected'){
                            $('#send-gst-to-dealer').hide();
                            $('#referback_invoice').hide();
                            $('#reject_invoice').hide();
                        }
                        else if(response.invoice_status == 'referback'){
                            $('#send-gst-to-dealer').hide();
                            $('#referback_invoice').hide();
                            $('#reject_invoice').show();
                        }
                        else{
                            $('#send-gst-to-dealer').show();
                            $('#referback_invoice').show();
                            $('#reject_invoice').show();
                        }
                    $('#invoice_view_modal').modal('show');
                    
                }else{
                    $('#invoice_view_modal').modal('hide');
                }
                
            }
        });
    }

}

function view_gst_data(gst_id,dealer_id){
    // alert(gst_id+'   -  '+dealer_id)
    $("#comment").val("");
    $(".comment_div").hide();
    if(gst_id!="" && dealer_id!=""){
            $.ajax({
                url : base_url+'Dealer_Approve/ViewUploadedGstFile',
                data : {gst_id:gst_id,dealer_id:dealer_id},
                type : 'POST',
                dataType : 'JSON',
                success : function(response){
                    var gst_file_data = response.gst_compliant_file;
                    var approval_status = response.approval_status;
                    console.log(response);
                    $("#gst_invoice_no").text(response.invoice_no);
                    $("#gst_no").text(response.gst_no);
                    $("#pan_no").text(response.gst_no);
                    $("#gst_amount").text(response.gst_amount);
                    $("#gst_month").text(response.invoice_month);
                    $("#dealer_code").text(response.sap_ad_code);
                    $("#dealer_name").text(response.dealer_name);
                    $("#hid_gst_invoice").val(response.invoice_no);
                    $("#hid_dealer_id").val(response.dealer_id);

                    if(approval_status=="approved" || approval_status=="rejected" || approval_status=="referback"){
                            $("#gst_referback").hide();
                    }else{
                        $("#gst_referback").show();
                    }
                    

                    if((gst_file_data=="") || (gst_file_data==='null') || (gst_file_data===null) ){
                        var file_html = '<h3>No Data</h3>';
                        // console.log(file_html);                       
                    }else{
                        var file_html = '<a href="'+base_url+'uploads/gst_uploaded_files/'+gst_file_data+'" download><img src="'+base_url+'uploads/gst_uploaded_files/'+gst_file_data+'" width="100" height="100"></a>';
                        // console.log(file_html);
                    }
                    $(".gst_file_div").html(file_html);
                    $("#gst_uploadedfile_modal").modal();
                }
            });
    }
}

function addGstToDealer(gst_id,dealer_id){
     $('#success_msg').text('');
     $('#invoice_er').text('');
     var r = confirm("Do You want to Approve GST?");
      if (r == true) {
        $.ajax({
            url : base_url+'Dealer_Approve/addGstToDealers',
            data : {gst_id : gst_id,dealer_id:dealer_id},
            type : 'POST',
            dataType : 'JSON',
            success : function(response){
                if(response.status == 'true'){
                    $("#message").css("color", "green");
                }else{
                    $("#message").css("color", "red");
                }
                $("#message").text(response.msg);
                setTimeout(function() {
                    location.reload();
                }, 5000);
                console.log(response);
            }
               
        });
      } else {
        txt = "You pressed Cancel!";
      }

}

function EditInvoiceByAdmin(invoice_id,dealer_id){
    // console.log('invoice_id--'+invoice_id+' --dealer_id'+dealer_id);
    $('#invoice_month_er').text('');
    var invoice_date = null;
    if(invoice_id!=""){
        $.ajax({
            async: false,
            url : base_url+'Dealer_Approve/getInvoiceData',
            type : 'POST',
            data : {invoice_id:invoice_id},
            global: false,
            dataType : 'json',
            success : function(response){
                // console.log(response);

                invoice_date = response.invoice_date;
                if(response.status==='true'){
                    $('#hid_invoice_id').val(response.id);
                    // $('#edit_invoice_date').val(response.invoice_date);
                    $('#edit_invoice_month').val(response.invoice_month);
                    $('#edit_invoice_no').val(response.invoice_no);
                    $('#policy_started_from').val(response.policy_started_from);
                }
            }

        });
         var invoice_date1 = new Date(invoice_date);
         // var invoicedate_ar = invoice_date.split('-');
         // alert(invoicedate_ar);
         var lastDayWithSlashes = invoice_date1.setMonth(invoice_date1.getMonth() + 2);
         var max_date = convert(lastDayWithSlashes);
         $("#edit_invoice_date" ).datepicker({
             dateFormat: "yy-mm-dd",
             minDate:invoice_date,
             // defaultDate:invoice_date,
             defaultDate:new Date(invoice_date),
             setDate : new Date(invoice_date),
             showOtherMonths: true,
             selectOtherMonths: true,
             changeMonth: true,
             changeYear: true,
             maxDate:max_date
            });
            // $("#edit_invoice_date").datetimepicker({
            //      format: "yyyy-mm-dd",
            //      startDate: invoice_date,
            //      endDate: lastDayWithSlashes,
            //      orientation: "right bottom",
            //     autoclose: true,                                   
            //    });



                    $('#edit_invoice_modal').modal();
    }
    console.log(invoice_date);
    } 

    function convert(str) {
          var date = new Date(str),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
          return [date.getFullYear(), mnth, day].join("-");
        }  

function UpdatePending(invoice_id,dealer_id){
        // alert(invoice_id);
    var r = confirm("Do You want to change the status to Pending ?");
    if (r == true) {
        if(invoice_id=="" || invoice_id===null || dealer_id=="" || dealer_id===null){
            alert('Something Went Wrong.');
        }else{
                $.ajax({
                    url : base_url+'Dealer_Approve/UpdatePendingInvoice',
                    type : 'POST',
                    data : {invoice_id:invoice_id},
                    dataType : 'json',
                    success : function(response){
                        // console.log(response);
                        if(response==='true'){
                            console.log(response);
                            location.reload();
                        }
                    }
                });
        }
    }
}