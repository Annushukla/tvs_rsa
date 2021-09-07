<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        RM Last Week Sold Policies
      </h1>   
      <br><br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="rm_last_soldpolicies_datatble">
              <thead>
              	<th>Sr.no</th>
                <th>Dealer Code</th>
                <th>Dealer Name</th>
                <th>Today</th>
                <th>T1</th>
                <th>T2</th>
                <th>T3</th>
                <th>T4</th>
                <th>T5</th>
                <th>T6</th>
                <th>T7</th>
                <th>T8</th>
                <th>T9</th>
                <th>T10</th>
                <th>T11</th>
            </thead>
              <tbody>
               

              </tbody>
          </table>
      </div>
  </div>
      
    <br><br>
</div> 

<div class="col-md-6 pull-right" id="image_loader">
    <img src="<?php echo base_url();?>assets/images/loading5.gif" height="100" width="">
  </div>   
      
</section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript">
  $("#image_loader").css("display","none");
 $(document).ajaxStart(function() {
        // show loader on start
        $("#image_loader").css("display","block");
    }).ajaxSuccess(function() {
        // hide image_loader on success
        $("#image_loader").css("display","none");
    }); 
</script>

<script type="text/javascript">
  
$(document).ready(function(){
    rm_last_soldpolicies_datatble = $('#rm_last_soldpolicies_datatble').DataTable({

            "scrollX": true,
            "processing": true,
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': false,
            'dom': 'Bfrtip',
            "buttons": ['excel', 'csv', 'pdf', 'print'],  
        // Load data for the table's content from an Ajax source
        "ajax": {
            'url' : base_url+'RM_Reports/RmLastWeekSoldpolicyAjax',
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


});

</script>