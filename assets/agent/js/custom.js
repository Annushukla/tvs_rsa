function get_banks(){
	$.ajax({
			url: base_url+'get_banks_list',
			dataType: 'Json',
			type: 'POST',
			success: function(response){
				$('#bank_list').empty();
				$('#bank_list').append('<option value="">Select Your Bank</option>');
				$.each(response, function(index,item){
					var html = '<option value="'+item.BankID+'">'+item.BankName+'</option>';
					$('#bank_list').append(html);
				});
			}
		})
}

function get_cities(){
	$.ajax({
			url: base_url+'get_cities_list',
			dataType: 'Json',
			type: 'POST',
			success: function(response){
				$('#cheque_cities_list').empty();
				$('#cheque_cities_list').append('<option value="">Select Your City</option>');
				$.each(response, function(index,item){
					var html = '<option value="'+item.id+'">'+item.name+'</option>';
					$('#cheque_cities_list').append(html);
				});
			}
		});
}

function date_picker(){
	$('.datepicker').datepicker({
	    calendarWeeks: true,
	    todayHighlight: true,
        autoclose: true
	});
}

$('.cheque_datepicker').datepicker({
    calendarWeeks: true,
    endDate: '+0d',
    autoclose: true
});