
$('#agent_login').click(function(event) {
	var username = $('#username').val();
	var password = $('#password').val();
	if (username != '' && password != '') {
		$.ajax({
			 url: base_url+'agent-login',
			 data: {
			 	'username': username,
			 	'password': password
			 },
			 dataType: 'JSON',
			 type : 'POST',
			 success: function(response){
	        	window.location = base_url+'dashboard';
		      },
		     error : function(response){
		    	var message = response.responseJSON.message;
		    	var error_html = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+message+'</strong> </div>';
		    	$('.login_res').html(error_html);
		    }
		});
	}
	else{
		return false;
	}
});

$( "#agent_login_form" ).validate({
  rules: {
    username: {
      required: true,
    },
    password: {
      required: true
    },
  }
});

$('.form-control').keydown(function(event){
	if(event.keyCode == 13) {
		$("#agent_login_form" ).valid();
		event.preventDefault();
		$('#agent_login').trigger('click');
    }
});

$('#user_pass_reset').click(function(){
	$.ajax({
		 url: base_url+'user_pass_reset',
		 data: {
		 	'user_data': $('#user_data').val()
		 },
		 type : 'POST',
		 success: function(response){
        	alert(response);
	      }
	});
});