$(document).ready(function(){
	$(document).on("click","#submitbtn",function(){
		var form_data = $("#dealerdata").serialize();
        var status = false;

		$("#dealerdata input,#dealerdata select").each(function(){
			if(this.id == 'pa_ic_id'){
           		 return true;
        	}
        	
        	if(validate(this.id) === true){
				status = true;
			}
    	});

        if(status === false){
                $("#dealerdata").submit();
        }
			  
	});

	function validate(element) {
    	console.log(element);
        var element_obj = $('#' + element);
        var element_val = element_obj.val();
       // console.log('Element Val '+element_val);
        var element_id = element;
       // console.log('Element ID '+element_id);
        var element_placeholder = element_obj.attr('placeholder');
        var error_obj = $('#error-' + element_id);
        var error_status = false;
        var error_msg = '';
        // console.log('element_val=='+element_val+'---element=='+element);
    
    if (checkIsExist(element_val) === false) {
        var input_type = $("#" + element_id).get(0).tagName;
        error_status = true;

        if(element_id === 'phone_no' || element_id === 'tin_no' || element_id === 'aadhar_no'){
            error_status = false;
        }
        else{
            if(input_type ==='SELECT'){
                error_msg = 'Please Select ' + $("#" + element_id).data('message');
            }else{
                error_msg = 'Please Enter ' + element_placeholder;
            }
        }    
    } else {
        //console.log('bzb Tin');
        //console.log('Element Id '+element_id);
      if (element_id === 'dealer_full_name') {
                var regex = /^[a-zA-Z ]{2,30}$/;
                var test = regex.test(element_val); 
                if (test === false) {
                    error_status = true;
                    error_msg = 'Please Enter Valid Name.';
                }
      }

         
      else if (element_id === 'company_name') {
                var regex = /^[.@&]?[a-zA-Z0-9 ]+[ !.@&()]?[ a-zA-Z0-9!()]+/;
                var test = regex.test(element_val);           
                if (test === false) {
                    error_status = true;
                    error_msg = 'Please Enter Valid Company Name.';
                }
      }

      else if (element_id === 'mobile_no') {
                var regex = /^[0]?[6789]\d{9}$/;
                var test = regex.test(element_val);           
                if (test === false) {
                    error_status = true;
                    error_msg = 'Please Enter valid Mobile No.';
                }
      }

      else if (element_id === 'phone_no') {
            var regex = /^[0-9]{11}$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter valid Phone No.';
            }
      }

     else if(element_id === 'tin_no'){
           // console.log('Tin element_val '+element_val);
            if(element_val==''){
          //  console.log('Tin if ...');
               error_status = false; 
            }
            else{
              //  console.log('Tin else ...');
                var regex = /^(?:\d{3}-\d{2}-\d{4}|\d{2}-\d{7})$/;
                var test = regex.test(element_val);
                if (test === false) {
                    error_status = true;
                    error_msg = 'Please Enter Valid Tin Number.';
                }
            }    
     }

     else if (element_id === 'gst_no') {
            var regex = /^\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}[Z]{1}[A-Z\d]{1}$/;
            var test = regex.test(element_val.toUpperCase());           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid GST No.';
            }
     }

     else if (element_id === 'aadhar_no') {
            var regex = /^\d{12}$/;
            var test = regex.test(element_val);           
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Aadhar Number.';
            }
     }

     else if (element_id === 'pan_no') {
            var regex = /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/;
            var test = regex.test(element_val);         
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Pan No.';
            }
    }

    else if (element_id === 'dealer_addr1') {
            var regex = /^[a-zA-Z0-9\s\/.,'-]*$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Adderess1';
            }
    }

    else if (element_id === 'dealer_addr2') {
            var regex = /^[a-zA-Z0-9\s\/.,'-]*$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Adderess2';
            }
    }

    else if (element_id === 'pin') {
            var regex = /^[0-9]{6}$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid Pincode';
            }
    }

    else if (element_id === 'state') {
            var regex = /^[a-zA-Z\s]+$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid state';
            }
    }

    else if (element_id === 'city') {
            var regex = /[a-zA-Z0-9.]/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid City';
            }
    } 

    else if (element_id === 'ifsc_code') {
            var regex = /^[A-Za-z]{4}\d{7}$/;
            var test = regex.test(element_val);
            if (test === false) {
                error_status = true;
                error_msg = 'Please Enter Valid IFSC code';
            }
    }       

        
    }


    console.log('Error Status '+error_status);

    if (error_status === true) {
        console.log(error_msg+"  -err");
        error_obj.text(error_msg);
        element_obj.css("border-color", "#FFBE00");
    } else {
        error_obj.text('');
        element_obj.css("border-color", "#cccccc");
    }
    return error_status;
   }

   function checkIsExist(checkvar) {
   			 if (checkvar === null || checkvar === "" || checkvar === "null" || checkvar === undefined || checkvar === 0 || checkvar === false) {
        		return false;
    	} else {
       		 return true;
    	}
	}
});