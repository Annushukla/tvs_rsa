$(document).ready(function(){
var page_name = $("#page_name").val();
if(page_name == 'workshop'){
  
    $("#generate_workshop_button").removeAttr("disabled");
      getPlanDetailsWorkshop(plan_type_id = 1);
}
$("#days").on('change',function(){
    var days = this.value;
    renewalPolicyDataTable(days);
});
var days = $("#days").val();
renewalPolicyDataTable(days);
function renewalPolicyDataTable(days){
    renewal_policy_datatable = $('#renewal_policy_datatable').DataTable({
        "scrollX": true,
        "processing": true,
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        "bDestroy": true,
        'dom': 'Bfrtip',
        "buttons": ['excel', 'csv', 'pdf', 'print'],  
        // Load data for the table's content from an Ajax source
        "ajax": {
            'url' : base_url+'renewalPolicyAjax',
            "type": "POST",
            "dataType": "json",
            "data" : {interval : days},
            "dataSrc": function (jsonData) {
                    return jsonData.data;
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });
    //renewal_policy_datatable.destroy();
}

expired_policies_tbl = $('#expired_policies_tbl').DataTable({
        "scrollX": true,
        "processing": true,
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        "bDestroy": true,
        'dom': 'Bfrtip',
        "buttons": ['excel', 'csv', 'pdf', 'print'],  
        // Load data for the table's content from an Ajax source
        "ajax": {
            'url' : base_url+'DealerExpiredPolicyAjax',
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
                    return jsonData.data;
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });

$("#expired_policy_btn").on('click',function(){
 var start_date = $("#start_date").val();
 var end_date = $("#end_date").val();
 var engine_no = $("#engine_no").val();
  expired_policies_tbl = $('#expired_policies_tbl').DataTable({
        "scrollX": true,
        "processing": true,
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        "bDestroy": true,
        'dom': 'Bfrtip',
        "buttons": ['excel', 'csv', 'pdf', 'print'],  
        // Load data for the table's content from an Ajax source
        "ajax": {
            'url' : base_url+'DealerExpiredPolicyAjax',
            "type": "POST",
            "dataType": "json",
            "data" : {engine_no:engine_no,start_date:start_date,end_date:end_date},
            "dataSrc": function (jsonData) {
                    return jsonData.data;
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });
});



$(".commission_parameter").on('click',function(){
    confirm('R U SURE!');
    $.ajax({
      url: base_url + 'updateCommisionMethod',
      data: {is_bank_payment:this.value},
      type:'POST',
      dataType:'JSON',
      success:function(response){
        console.log(response);
        if(response.error_code == 200){
          window.location.href = '';
        }else{
          alert(response.msg);
        }
      },
      error:function(response){
        console.log(response);
      },
      complete:function(response){
        console.log(response);
      }

    });
});



    
$("#vehicle_detail").focusout(function(){
  if(checkIsExist(this.value) == false){
    $("#exist_policy").text('');
  }
});

  $(document).on('change','input[type=radio][name=plan]', function() {
      $("#pa_cover_generate_button").removeAttr("disabled");
});
  $(document).on('change','input[type=radio][name=plan_rr310]', function() {
      $("#rr310pa_cover_generate_button").removeAttr("disabled");
});
  var plan_type_id = $("input[name='plan_type']:checked").val();
  if(checkIsExist(plan_type_id) == true){
      var plan_id = $("#plan_id").val();
      var selected_dob = $("#dob").val();
      var age = calAge(selected_dob);
      getPlanDetails(plan_type_id,plan_id,age);
  }


var plan_type_rsa_only_id = $("input[name='plan_type_rsa_only']:checked").val();

  if(checkIsExist(plan_type_rsa_only_id) == true){
      //var plan_id = $("#plan_id").val();
      //alert(plan_type_rsa_only_id);
      getPlanDetailsForRR310();
  }


  var plan_id = $("#plan_id").val();
  if(checkIsExist(plan_id) == true){
    if(page_name != 'renewal'){
      $("#pa_cover_generate_button").removeAttr("disabled");
    }
  } 

  $('input[type=radio][name=plan_type]').on('change', function() {
    var plan_type_id = $(this).val();
    var plan_id = $("#plan_id").val();
    if(plan_type_id==1){
      $('#st_date_div').css("display","block");
      // $('#st_date_div').css('clip', 'auto');
      $('#rsa_start_date').datepicker({
          autoclose: true,
          format: "yyyy-mm-dd",
          startDate: new Date(), // controll start date like startDate: '-2m' m: means Month
          endDate: '+30d'
      });
    }else{
      $('#st_date_div').css("display","none");
    }
    var selected_dob = $("#dob").val();
    var age = calAge(selected_dob);
    var dms_ic_id = $("#dms_ic_id").val();
    getPlanDetails(plan_type_id,plan_id,dms_ic_id,age);
});

  $('input[type=radio][name=plan_type_workshop]').on('change', function() {
      var plan_type_id = $(this).val();
      if(plan_type_id==1){
      $('#st_date_div').css("display","block");
       $('#rsa_start_date').datepicker({
          autoclose: true,
          format: "yyyy-mm-dd",
          startDate: new Date(), // controll start date like startDate: '-2m' m: means Month
          endDate: '+30d'
      });
    }else{
      $('#st_date_div').css("display","none");
    }

   // var dms_ic_id = $("#dms_ic_id").val();
    getPlanDetailsWorkshop(plan_type_id);
    $("#generate_workshop_button").removeAttr("disabled");
  });  



 $('input[type=radio][name=plan_type_rr]').on('change', function() {
    var plan_type_id = $(this).val();
    var vehicle_age = $("#vehicle_age").val();
    // alert(plan_type_id);
    getPlanDetailsForRR310(plan_type_id,vehicle_age);
});


$('#nominee_relation').on('change',function(){
  var nominee_relation = $(this).val();
  if(nominee_relation=='other'){
    $('.other_relation_div').removeClass("hide");
  }else{
    $('.other_relation_div').addClass("hide");
  }
});

var policy_id = $("#policy_id").val();
if(checkIsExist(policy_id) == true){
var nominee_age = $("#nominee_age").val();

  if(nominee_age < 18 ){
    $("#appointee_div").css("display","block");
    $("#appointee_div").find('*').attr('disabled',false)
  }else{
      $("#appointee_div").css("display","none");
      $("#appointee_div").find('*').attr('disabled',true);
  }
}else{
  $("#appointee_div").css("display","none");
  $("#appointee_div").find('*').attr('disabled',true)
}

$("#nominee_age").keyup(function(){
  var value = $(this).val();
  if(checkIsExist(value) == true){
  if(value < 18 ){
    $("#appointee_div").css("display","block");
    $("#appointee_div").find('*').attr('disabled',false)
  }else{
      $("#appointee_div").css("display","none");
    $("#appointee_div").find('*').attr('disabled',true);
  }
}else{
  $("#appointee_div").css("display","none");
  $("#appointee_div").find('*').attr('disabled',true)
}
});

$("#dealer_form_ifsc_code").focusout(function(){
    if((checkIsExist(this.value) == true) && (this.value.length == 11)){
      $("#error-dealer_form_ifsc_code").text('');
       $.ajax({
          url:base_url + 'getBankDetailsByIFSC',
          data:{ifsc_code:this.value},
          dataType: 'JSON',
          type: 'POST',
          success: function(response){
            $("#bank_name").val(response.bank);
            $("#branch_address").val(response.address);
        }

    });
  }else{
    $("#error-dealer_form_ifsc_code").text('Please Insert valid IFSC CODE');
    $("#dealer_form_ifsc_code").css("border-color : rgb(255, 190, 0)");
  }
});





function isAllowedToPunchPolicy(){
         $.ajax({
          url:base_url + 'isAllowedToPunchPolicy',
          data:{plan_type_id:plan_type_id},
          dataType: 'html',
          type: 'POST',
          success: function(response){
             $("#plan_details").html(response);
        }

         });
}



function getPlanDetails(plan_type_id,plan_id,dms_ic_id,age){
        $.ajax({
          url:base_url + 'planDetails',
          data:{plan_type_id:plan_type_id,plan_id:plan_id,dms_ic_id:dms_ic_id,age:age},
          dataType: 'html',
          type: 'POST',
          success: function(response){
             $("#plan_details").html(response);
        }
      });
}
});


function getPlanDetailsForRR310(plan_type_id,vehicle_age){
  $.ajax({
            url : base_url + 'planDetailsForRR310',   
            type : 'POST', 
            data:{plan_type_id:plan_type_id,vehicle_age:vehicle_age},        
            dataType : 'HTML',
            success:function(response){
                   $("#plan_details_rr310_data").html(response);
                // }
            }
        });




}

function getPlanDetailsWorkshop(plan_type_id){
  $.ajax({
            url : base_url + 'planDetailsWorkshop',
            type : 'POST', 
            data:{plan_type_id:plan_type_id},
            dataType: 'html',
            type: 'POST',
            success: function(response){
             $("#plan_details").html(response);
            }
  });
}  



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
var dt = new Date();
var start_dt = new Date();
dt.setFullYear(new Date().getFullYear()-18);
start_dt.setFullYear(new Date().getFullYear()-74);
$('#dob').datepicker({
    viewMode: "years",
    calendarWeeks: true,
    startDate:start_dt,
    endDate: dt,
    autoclose: true
}).on('changeDate', function (ev) {
      var age = calAge(this.value);
      if(parseInt(age) > 64){
        var is_checked = isCheckedById('678');
        if(is_checked){
          $('#678').prop('checked', false);
          $('#pa_cover_generate_button').attr("disabled", "disabled");
        }
        $("#678").attr("disabled", "disabled");
        // $("table tbody tr:nth-child(5)").hide();
      }else{
        $("#678").removeAttr("disabled", "disabled");
        // $("table tbody tr:nth-child(5)").show();
      }
    });
var today = new Date();
$('#registration_date').datepicker({
    viewMode: "years",
    calendarWeeks: true,
	  format: "dd-mm-yyyy",
    showMonthAfterYear: false,
    showOn: 'both',
    yearRange: "-40:+10",
    changeYear: true,
    changeMonth: true,
    startDate:start_dt,
    endDate: today,
    autoclose: true
}).on("change", function() {
  $("input[name=plan_rr310]").prop("checked",false);
  $("#plan_type_rr").prop("checked", false);
  $("#plan_details_rr310").remove();
  var new_date = parseDate(this.value);
  var today = new Date();
  var vehicle_age =  calculateDays(new_date,today);
  $("#vehicle_age").val(vehicle_age);
});
function isCheckedById(id) {
    var checked = $("input[id=" + id + "]:checked").length;
    alert(checked);

    if (checked == 0) {
        return false;
    } else {
        return true;
    }
}
function calAge(dob){
    dob = new Date(dob);
    var today = new Date();
    var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
    return age;
    // $('#age').html(age+' years old');
}
// $('#registration_date').datepicker('setDate', 'today');
function calculateDays(selected_date,today_date){
   days = (today_date - selected_date) / (1000 * 60 * 60 * 24);
   return (Math.round(days));
}

function parseDate(str) {
    var mdy = str.split('-');
    return new Date(mdy[2],mdy[1]-1,mdy[0]);
}

$('#sel_all_policies').change(function(){
     var status = this.checked; // "select all" checked status
    $('.policy_price').each(function(){ //iterate all listed checkbox items
        this.checked = status; //change ".checkbox" checked status
    });
 count_data();  
    var policy_price_sum = 0;
    if($(this).is(':checked')){
        var policy_price = $(".policy_price").map(function(){
            return $(this).data('policy_price');
        }).get().join();

        var policy_price_arr = policy_price.split(",");

        for (var i = 0; i <policy_price_arr.length; i++) {
            policy_price_sum += parseInt(policy_price_arr[i]);
        }

        $('.policy_price').attr('checked',true);

        $('#tot_price').text();
    }
    else{
        $('.policy_price').attr('checked',false);   
    }

    $('#tot_price').text(policy_price_sum);

});
$('#paying_slip .policy_price').on("click", function (e) {
 count_data(this);
});

function count_data(e){
var val  = $('[name="check_data[]"]:checked').length;
var values = [];
$(".check_data:checked").each(function() {
    values.push($(this).val()); });
$('#counts_ic_id').val(values);
$('#counts_policy_id').val(values);
$('#counts').val(val);
var jsonval = $('#counts_ic_id').val();
$.ajax({
        type: 'POST',
        url: base_url + 'count_amount_ajax',
        data: 'jsonval=' + jsonval,
        success: function (html) {
          var data = JSON.parse(html);
          $('#amount_value').val(data.amount);
          $('#amount_value_1').val(data.amount);
          $('#policy_last_date').val(data.created);
        }
    }); 

}

// $('input[type="radio"]').click(function(){
//     var radio_val = $(this).val();
// if(radio_val=="neft"){
//  $('#changes_cheque_no').text("NEFT No");
//  $('#changes_cheque_date').text("NEFT Date");

// }
// else{
//  $('#changes_cheque_no').text("Cheque No");
//   $('#changes_cheque_date').text("Cheque Date");
// }


  
// });



  function generate_paying_slip() {
    var input_data=$("#amount_value").val();
    var dealer_bank_id=$("#dealer_bank_id").val();

    if(input_data === "")
    {
    alert("Please Select Policy ");
    return false;
    }
    else if(!$("input[name='payment_mode']:checked").val()) {
    alert("Please Select Payment Option ");
    return false;
    }
   
 else
 {
var form_data = $("form").serialize();
$.ajax({
        type: 'POST',
        url: base_url + 'payslip_insert_data',
        data: form_data,
        success: function (response) {
          var obj = jQuery.parseJSON(response);
          console.log(obj);
          if(obj.message="data_save"){
            alert("Data Save successfully");
            var paying_no=obj.paying_slip_number;
            window.location = 'view_pdf_paying_slip/'+paying_no;
          }

         /*alert(html.trim());*/
         
        }
    });
}

}


 $(document).on('click', '.check_data', function () {
var val  = $('[name="check_data[]"]:checked').length;

var values = [];
$(".check_data:checked").each(function() {
    values.push($(this).val()); });
$('#counts_ic_id').val(values);
$('#counts').val(val);
$('#counts_policy_id').val(values);

var jsonval = $('#counts_ic_id').val();
$.ajax({
        type: 'POST',
        url: base_url + 'count_amount_ajax',
        data: 'jsonval=' + jsonval,
        success: function (html) {
          var data = JSON.parse(html);
          $('#amount_value').val(data.amount);
          $('#amount_value_1').val(data.amount);
          $('#policy_last_date').val(data.created);
        }
    });  
});
