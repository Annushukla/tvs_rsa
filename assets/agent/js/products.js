/* Start Loading all basic required functions from custom.js */
get_banks();
get_cities();
date_picker();
/* End Loading all basic required functions from custom.js */
$("#sel_all_policies").on("change", function () {
    if($(this).prop("checked")){
        $(".policy_price").prop("checked", true);
        recalculate();
    }else if (!$(this).prop("checked")) {
        $(".policy_price").prop("checked", false);
        recalculate();
    }
});

$(".policy_price").on("change", function () {
    recalculate();
});


function recalculate() {
        var sum = 0;
        $("input[type=checkbox]:checked").each(function() {
            var data = $(this).data('policy_price');
            if(isNaN(parseInt(data))) {
                return true;
            }
            sum += parseInt(data);
        });
        $("#tot_price").text(sum);
    }




    //datatables
    table = $('#employee-grid').DataTable({ 
 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        //"order": [], //Initial no order.
        "iDisplayLength" : 1,
         // Load data for the table's content from an Ajax source
        "ajax": {
            'url' : base_url+'sold_policies_server_data',
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


var dataTable = $('#employee-gridd').DataTable({
    "processing": true,
    "serverSide": true,
    "iDisplayLength" : 1,

    "ajax": {
        url: base_url+"sold_policies_server_data", 
        type: "post", // method  , by default get

        error: function () {  // error handling
            $(".employee-grid-error").html("");
            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
            $("#employee-grid_processing").css("display", "none");

        }
    },
    "columnDefs": [{
        /*"targets": -1,*/
        "render": function (data, type, full, meta) {
            $('[data-toggle="tooltip"]').tooltip();
            return '';
        }
    }]
});


//$("#employee-grid_filter").css("display", "none");  // hiding global search box

var dataTable = $('#bike-grid').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        url: base_url+"sold_policies_bike_data", // json datasource
        type: "post", // method  , by default get
        error: function () {  // error handling
            $(".bike-grid-error").html("");
            $("#bike-grid").append('<tbody class="bike-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
            $("#bike-grid_processing").css("display", "none");

        }
    },
    "columnDefs": [{
        /*"targets": -1,*/
        "render": function (data, type, full, meta) {
            $('[data-toggle="tooltip"]').tooltip();
            return '';
        }
    }]
});
//$("#bike-grid_filter").css("display", "none");  // hiding global search box
$('.search-input-text').on('keyup click', function () {   // for text boxes
    var i = $(this).attr('data-column');  // getting column index
    var v = $(this).val();  // getting search input value
    dataTable.columns(i).search(v).draw();
});
$('.search-input-select').on('change', function () {   // for select box
    var i = $(this).attr('data-column');
    var v = $(this).val();
    dataTable.columns(i).search(v).draw();
});

var dataTable = $('#commercial-grid').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        url: base_url+"sold_policies_commercial_data", // json datasource
        type: "post", // method  , by default get
        error: function () {  // error handling
            $(".bike-grid-error").html("");
            $("#bike-grid").append('<tbody class="bike-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
            $("#bike-grid_processing").css("display", "none");

        }
    },
    "columnDefs": [{
        /*  "targets": -1,*/
        "render": function (data, type, full, meta) {
            $('[data-toggle="tooltip"]').tooltip();
            return '';
        }
    }]
});

//$("#commercial-grid_filter").css("display", "none");  // hiding global search box

$('#car_agent_data').DataTable();
$('#bike_agent_data').DataTable();
$('#comm_agnt_data').DataTable();
