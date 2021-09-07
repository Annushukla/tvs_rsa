$(document).ready(function(){

$('#transaction_date').datepicker({
    autoclose: true,
    endDate: "today"
});

var transaction_type = $("#transaction_type option:selected").val();
		if(checkIsExist(transaction_type) == true){

			if(transaction_type == 'withdrawal'){
				$("#amt_typ_label").text("Withdrawal Amount");
				$('.acc_type_div').css('display','block');
				$('.acc_type_div').find("*").attr('disabled',false);
				$('.acc_name_div').css('display','block');
				$('.acc_name_div').find("*").attr('disabled',false);
				$('.ifsc_code_div').css('display','block');
				$('.ifsc_code_div').find("*").attr('disabled',false);
				$('.transactn_no_div').css('display','none');
				$('.transactn_no_div').find("*").attr('disabled',true);
				$('#transaction_no').prop('required',false);
				
			}else{
					$('.acc_type_div').css('display','none');
					$('.acc_type_div').find("*").attr('disabled',true);
					$('.acc_name_div').css('display','none');
					$('.acc_name_div').find("*").attr('disabled',true);
					$('.ifsc_code_div').css('display','none');
					$('.ifsc_code_div').find("*").attr('disabled',true);
					$('.transactn_no_div').css('display','block');
					$('.transactn_no_div').find("*").attr('disabled',false);
					$("#amt_typ_label").text("Deposit Amount");
					$('.transactn_no_div').show();
					$('#transaction_no').prop('required',true);
				}
		}


$('#transaction_type').on('change',function(){
	var transaction_type = $(this).val();
	if(transaction_type=='withdrawal'){
		$("#amt_typ_label").text("Withdrawal Amount");
		$('.acc_type_div').css('display','block');
		$('.acc_type_div').find("*").attr('disabled',false);
		$('.acc_name_div').css('display','block');
		$('.acc_name_div').find("*").attr('disabled',false);
		$('.ifsc_code_div').css('display','block');
		$('.ifsc_code_div').find("*").attr('disabled',false);
		$('.transactn_no_div').css('display','none');
		$('.transactn_no_div').find("*").attr('disabled',true);
		$('#transaction_no').prop('required',false);
	}else{
		$('.acc_type_div').css('display','none');
		$('.acc_type_div').find("*").attr('disabled',true);
		$('.acc_name_div').css('display','none');
		$('.acc_name_div').find("*").attr('disabled',true);
		$('.ifsc_code_div').css('display','none');
		$('.ifsc_code_div').find("*").attr('disabled',true);
		$('.transactn_no_div').css('display','block');
		$('.transactn_no_div').find("*").attr('disabled',false);
		$("#amt_typ_label").text("Deposit Amount");
		$('.transactn_no_div').show();
		$('#transaction_no').prop('required',true);
	}
});

$('#deposit_amount').on('focusout',function(){
	var amount = $(this).val();
	var transaction_type = $('#transaction_type').val();
	var transanction_exist = $('#exist_transanction_er').val();
	$('#error-transaction_type').text('');
	if(transaction_type=="" ){
		$('#error-transaction_type').text('please select the transaction type');
		var transaction_type_er = true;
		// $("#dealer_transactn_submit").prop("disabled", true);
	}
	else{
		$('#error-transaction_type').text();
		transaction_type_er = false;
		// $("#dealer_transactn_submit").prop("disabled", false);
	}

	// ...Deposite...case
	if(amount <=0 || amount.substring(0,1) == "0"){
			$('#error-deposit_amount').text('Please Enter Valid Amount');
			var deposit_amount_er = true;
			// $("#dealer_transactn_submit").prop("disabled", true);
		}else{ 
			$('#error-deposit_amount').text('');
			deposit_amount_er = false;
			// $("#dealer_transactn_submit").prop("disabled", false);
		}

	if(transaction_type=='withdrawal'){
		if(amount <= 0){
			$('#error-deposit_amount').text('Please Enter Valid Amount');
			var deposit_amount_er = true;
			// $("#dealer_transactn_submit").prop("disabled", true);
			}else{
				$('#error-deposit_amount').text('');
				deposit_amount_er = false;
				// $("#dealer_transactn_submit").prop("disabled", false);
				$.ajax({
					url : base_url+'Tvs_Dealer/getDealerBalance',
					type : 'POST',
					dataType : 'JSON',
					data : {amount,amount},
					success : function(response){
						if(response==false){
							$('#error-deposit_amount').text('You Have Insufficient balance');
							// $("#dealer_transactn_submit").prop("disabled", true);
							var wallet_er = true;
						}else{
							$('#error-deposit_amount').text();
							// $("#dealer_transactn_submit").prop("disabled", false);
							wallet_er = false;
						}
					}
				});
			}
	}else{
		
			$('#error-deposit_amount').text('');
			$("#dealer_transactn_submit").prop("disabled", false);
	}
	
	if( (transaction_type_er==true || deposit_amount_er==true || wallet_er==true) || transanction_exist=="transanction_exist"){
		// alert('in');
		$("#dealer_transactn_submit").prop("disabled", true);
	}else{
		$("#dealer_transactn_submit").prop("disabled", false);
		// alert(transaction_type_er+'sss'+deposit_amount_er+'--'+transanction_exist);
	}
		// alert(transaction_type_er+'sss'+'deposit_amount_er'+'--'+transanction_exist);
});

$('#transaction_no').keyup(function(){
	var trans_no = $(this).val();
		if(trans_no!=''){
			$.ajax({
				url : 'Tvs_Dealer/checkExistTransactionNO',
				type : 'POST',
				dataType : 'JSON',
				data : {trans_no,trans_no},
				success : function(response){
					// console.log(response);
					if(response==false){
						$('#error-transaction_no').text('This Transaction No is Already Exist');
						$('#exist_transanction_er').val('transanction_exist');
						$("#dealer_transactn_submit").prop("disabled", true);
					}else{
						$('#error-transaction_no').text('');
						$('#exist_transanction_er').val('');
						$("#dealer_transactn_submit").prop("disabled", false);
					}
						
				}
			});
		}
});

});