<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         RM Inactive Dealers
      </h1>
     <br><br>
     <div class="row">
       <h3 style="color:red;"><?php echo $this->session->flashdata('message');?></h3>
     </div>
     <br>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="rm_inactive_dealer">
              <thead>
              	<th>Sr.no</th>
              	<th>Dealer Code</th>
              	<th>Dealer Name</th>
              	<th>Sap Code</th>
              	<th>Ad. Name</th>
              	<th>Contact No</th>
                <th>State</th>
                <th>Location</th>
              	<th>Created At</th>
              	<!-- <th></th> -->

              </thead>
              <tbody>
              

              </tbody>
          </table>
      </div>
  </div>
      
    <br><br>
</div>    
      
<div class="row">
     <div class="col-md-6 pull-right" id="image_loader">
        <img src="<?php echo base_url();?>assets/images/loading5.gif" height="100" width="">
      </div>
    <br><br>
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

   

  table = $('#rm_inactive_dealer').DataTable({ 

             "scrollX": true,
            "processing": true,
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'dom': 'Bfrtip',
             "buttons": ['excel'], 
          // Load data for the table's content from an Ajax source
          "ajax": {
              'url' : base_url+'RM_Reports/RMInactiveDealerAjax',
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