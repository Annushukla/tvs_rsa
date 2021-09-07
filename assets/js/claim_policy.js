$(document).ready(function(){

$('#search_button').click(function(e){

var search_value = $('#search_data').val();

	$.ajax({
		url : base_url+'search_data',
		data : { 'search_value' : search_value},
		dataType : 'html',
		type  :  'POST',

		success : function(response){
			console.log(response);

			if(response){
				$('#tbody_html').html(response);
			}else{
				$('#tbody_html').html();
			}
		}

	});

});

$("#dealer_estimationupload_form").on('submit',function(e){
    e.preventDefault();
    var courier_no = $("#courier_no").val();
    if(checkIsExist(courier_no) === true){
        $("#error-courier_no").text('');
        (this).submit();
    }else{
        $("#error-courier_no").text('Please Insert Courier No.');
    }
});

});




function claim_popup(id){
	$('#claim_modal').modal();
	$('#pa_policy_id').val(id);

}

function checkIsExist(checkvar) {
    if (checkvar === null || checkvar === "" || checkvar === "null" || checkvar === undefined || checkvar === 0 || checkvar === false) {
        return false;
    } else {
        return true;
    }
}