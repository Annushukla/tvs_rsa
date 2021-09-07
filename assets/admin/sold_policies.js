$(document).ready(function(){

    fetch_data();

});



function fetch_data(){
        $('.data_tables').DataTable().destroy();
        var payment_status = $('#payment_status').val();
        var cheque_from = $('#cheque_from').val();
        var date_range_picker = $('#date_range_picker').val();

        var select_rm = $('#select_rm').val();
        var select_dealer = $('#select_dealer').val();
        var select_agent = $('#select_agent').val();
        var policy_no = $('#policy_no').val();
        var customer_mobile_no = $('#customer_mobile_no').val();
        var select_customer = $('#select_customer').val();

        var cheque_number = $('#cheque_number').val();
        var insurance_company_id = $('#insurance_company_id').val();
        
        

        var dataTable = $('.data_tables').DataTable({
            "processing" : true,
            "serverSide" : true,
            "order" : [],
            "columnDefs": [
               { orderable: false, targets: 0 },
               { orderable: false, targets: 1 },
               
               { orderable: false, targets: 10 }
            ],
            "ajax" : {
                url: base_url+'admin/ReportController/ajaxSoldPolicies',
                type:"POST",
                dataType: "json",
                data:{
                    payment_status : payment_status,
                    cheque_from : cheque_from,
                    date_range_picker : date_range_picker,
                    select_rm : select_rm,
                    select_dealer : select_dealer,
                    select_agent : select_agent,
                    policy_no : policy_no,
                    customer_mobile_no : customer_mobile_no,
                    select_customer : select_customer,
                    cheque_number : cheque_number,
                    insurance_company_id : insurance_company_id
                }
            }
        });
    }



