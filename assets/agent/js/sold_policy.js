
$(document).ready(function() {
    //datatables
    $('input[name="daterange"]').daterangepicker();
    $('input[name="bike_daterange"]').daterangepicker();
    
     car_grid = $('#employee-grid').DataTable({  
        "processing": true,
        "serverSide": true,
        "order": [[ 7, "desc" ]],
        "ajax": {
            url: base_url+'sold_policies_server_data',
            type: 'POST'
        },
    
        
    });
     $('.search-input-text').on('keyup click', function () {   // for text boxes
        var i = $(this).attr('data-column');  // getting column index
        var v = $(this).val();  // getting search input value
        car_grid.columns(i).search(v).draw();
    });
    $('.search-input-select').on('change', function () {   // for select box
        var i = $(this).attr('data-column');
        var v = $(this).val();
        car_grid.columns(i).search(v).draw();
    });

	
	
    $('#policy_status').on('change', function(){
	
	car_grid.search(this.value).draw();   
    });
	
	
    $('#mobile_no').on('keyup', function(){
	
	car_grid.search(this.value).draw();   
    });
	
    $('#policy_number').on('keyup', function(){
	
	car_grid.search(this.value).draw();   
    });
	
    $('#cheque_no').on('keyup', function(){
	
	car_grid.search(this.value).draw();   
    });
	
	
    $('#agent_list').on('change', function(){
	
	car_grid.search(this.value).draw();   
    });
	
	
	
    
	
    $('#car_daterange').daterangepicker({}, function(start, end, label) {
        var selected_date = new Array();
        selected_date.push(start.format('DD/MM/YYYY'));
        selected_date.push(end.format('DD/MM/YYYY'));
        // selected_date[] = end.format('DD/MM/YYYY');
        console.log(selected_date);
        var stringList = selected_date.join(",");
        console.log(stringList);
        car_grid.search(stringList).draw(); 
      // console.log("New date range selected: " + start.format('DD-MM-YYYY') + " to " + end.format('DD-MM-YYYY') + " (predefined range: " + label + ")");
    });

	
	

    bike_grid = $('#bike-grid').DataTable({ 

        "processing": true, 
        "serverSide": true, 
        "iDisplayLength" : 10,
        "ajax": {
            'url' : base_url+'sold_policies_bike_data',
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
                return jsonData.data;
            }
        },
        "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false,
            },
        ],

    });

  $('.search-input-text').on('keyup click', function () {   // for text boxes
        var i = $(this).attr('data-column');  // getting column index
        var v = $(this).val();  // getting search input value
        bike_grid.columns(i).search(v).draw();
    });
    $('.search-input-select').on('change', function () {   // for select box
        var i = $(this).attr('data-column');
        var v = $(this).val();
        bike_grid.columns(i).search(v).draw();
    });

    

    commercial_grid = $('#commercial-grid').DataTable({ 

        "processing": true, 
        "serverSide": true, 
        "iDisplayLength" : 1,
        "ajax": {
            'url' : base_url+'sold_policies_commercial_data',
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
                return jsonData.data;
            }
        },
        "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false,
            },
        ],

    });

      $('.search-input-text').on('keyup click', function () {   // for text boxes
        var i = $(this).attr('data-column');  // getting column index
        var v = $(this).val();  // getting search input value
        commercial_grid.columns(i).search(v).draw();
    });
    $('.search-input-select').on('change', function () {   // for select box
        var i = $(this).attr('data-column');
        var v = $(this).val();
        commercial_grid.columns(i).search(v).draw();
    });

} );




function cheque_status(id)
{

    $('#form_cheque')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : base_url+'sold_policies_server_data/' + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="payment_details_id"]').val(data.payment_details_id);
            $('[name="cheque_date"]').val(data.cheque_date);
            $('[name="cheque_no"]').val(data.cheque_no);
            $('[name="payin_slip_no"]').val(data.payin_slip_no);
            $('[name="payment_amount"]').val(data.payment_amount);
            $('[name="payment_status"]').val(data.payment_status);
            $('[name="payment_type"]').val(data.payment_type);
            $('[name="sold_policy_date"]').val(data.sold_policy_date);
            $('[name="sold_policy_no"]').val(data.sold_policy_no);
            $('[name="vehicle_registration_no"]').val(data.vehicle_registration_no);
            $("label[for='sold_policy_no']").text(data.sold_policy_no);
            $('.modal-title').text('Update Cheque Status'); // Set title to Bootstrap modal title

            $('#modal_cheque').modal('show');  // show bootstrap modal when complete loaded

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}


