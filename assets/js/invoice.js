$(document).ready(function(){

	var endYear = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());

	var datesToDisable = $("#invoice_created_dates").val().split(',');
	var policy_started_from = $('#policy_started_from').val();
	$('#invoice_month').datepicker({
	        format: "mm-yyyy",
	        startView: "months", 
	        startDate: policy_started_from,
	        endDate: endYear,
	        viewMode: "months", 
	        minViewMode: "months",
	        autoclose: true,
	    }).on("show", function(event) {

	  var year = $("th.datepicker-switch").eq(1).text();  // there are 3 matches

	  $(".month").each(function(index, element) {
	    var el = $(element);

	var hideMonth = $.grep( datesToDisable, function( n, i ) {
	                  return n.substr(4, 4) == year && n.substr(0, 3) == el.text();
	                });

	    if (hideMonth.length)
	      el.addClass('disabled');

	/* To hide those months completely...
	    if (hideMonth.length)
	      el.hide();
	*/
	  });
	});


	$("#invoice_month_submit").on('click',function(){
	   $('#success_msg').text('');
	   $('#invoice_er').text('');
	   $('#date_er').text('');
	  var invoice_month = $("#invoice_month").val();
	    if(checkIsExist(invoice_month) == true){
	      // alert(invoice_month);
	        $.ajax({
	          url:base_url + 'getDealerInvoice',
	          data:{invoice_month:invoice_month,invoice_type:'new'},
	          dataType: 'html',
	          type: 'POST',
	          success: function(response){
	             $("#invoice_details").html(response);
	             $('#generate_invoice_modal').modal();
	          }

	         });
	    }else{
	      alert('Please Select Month');
	    }

	});


// Dom closing
});


  $(document).on("click", ".modal-body", function () {
  	var endYear = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
  	var invoice_no = $('#invoice_no').val();
  	if(invoice_no == ""){
      var selected_invoice_month = $('#invoice_month').val();
      // alert('post_invoice_month'+post_invoice_month);
  	}else{
  		 var selected_invoice_month = $('#post_invoice_month').val();
      // alert('invoice_month'+selected_invoice_month);
  	}
  	 var selected_invoice_month_array = selected_invoice_month.split('-');
     var month_select = selected_invoice_month_array[0];
     var year_select = selected_invoice_month_array[1];
     // console.log(month_select);
		var date = new Date();
		var firstDay = new Date(date.getFullYear(), selected_invoice_month_array[0], 1);
		var lastDay = new Date(date.getFullYear(), selected_invoice_month_array[0], 0);
    // console.log('firstDay  '+firstDay+' -- lastDay  '+lastDay);
    if(month_select=='12'){
        var firstDayWithSlashes = (year_select) + '-' + (lastDay.getMonth()) + '-' + lastDay.getDate();
        var firstDayWithSlashes1 = new Date(firstDayWithSlashes);
        var lastDayWithSlashes = firstDayWithSlashes1.setMonth(firstDayWithSlashes1.getMonth() + 2);
        var lastDayWithSlashes = new Date(lastDayWithSlashes);
    }else{
        var firstDayWithSlashes = (year_select) + '-' + (firstDay.getMonth()) + '-' + firstDay.getDate();
        var firstDayWithSlashes1 = new Date(firstDayWithSlashes);
        var lastDayWithSlashes = firstDayWithSlashes1.setMonth(firstDayWithSlashes1.getMonth() + 2);
        var lastDayWithSlashes = new Date(lastDayWithSlashes);
    }
		
		// console.log(firstDayWithSlashes+'--'+lastDayWithSlashes);
  $("#invoice_date").datepicker({
         	 format: "yyyy-mm-dd",
	         startDate: firstDayWithSlashes,
	         endDate: lastDayWithSlashes,
	        autoclose: true,                                   
         });
  });

$(document).on('focusout','#invoice_no',function(){
  // alert($(this).val());
  var invoice_no = $(this).val();
      if(invoice_no ==""){
            $('#invoice_er').text('Please Enter Invoice No.');
                $('#invoice_btn').hide();
          }else{
              $('#invoice_er').text('');
              $('#invoice_btn').show();
          }
});


 $(document).on('click' ,'#invoice_btn' , function() {
    var invoice_no = $('#invoice_no').val();
    var invoice_date = $('#invoice_date').val();
    if(invoice_no!="" && invoice_date!=""){
        var form_data = $("#invoice_form").serialize();
        $('#success_msg').text('');
        // console.log(form_data);
        $.ajax({
            url : base_url+'post_invoice_data',
            type : 'POST',
            dataType : 'json',
            data : form_data,
            success : function(response){
              console.log(response);
              if(response.status=='true'){
                alert(response.msg);
                location.reload();
              }else{
                  $('#success_msg').text(response.msg);
              }
            }
        });
    }else{
        $('#success_msg').text('');
        alert('Invoice No and Invoice Date are require');
        // $('#invoice_btn').hide();
    }
 });



function edit_invoice_data(invoice_id){
var invoice_month = '';
$.ajax({
        url:base_url + 'getDealerInvoice',
        data:{invoice_month:invoice_month,invoice_id:invoice_id,invoice_type:referback},
        dataType: 'html',
        type: 'POST',
        success: function(response){
           $("#invoice_details").html(response);
           // console.log(response)
           $('#generate_invoice_modal').modal();
        }

       });

}
