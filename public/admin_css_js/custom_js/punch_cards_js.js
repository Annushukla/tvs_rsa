$('documnet').ready(function(){



$("#frm_punch_card_add_update").validate({
   rules: {
     no_of_star: "required",
	 murchant_offer: "required",
	 murchant_rules: "required"
	 
   },
   messages: {
   	 no_of_star: "Please Enter Number of Start.",
	 murchant_offer: "Please Enter Murchant Offer.",
	 murchant_rules: "Please Enter Murchant rule."
	 
   }
});











});