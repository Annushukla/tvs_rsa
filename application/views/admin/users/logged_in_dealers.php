<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         Dealer Master
      </h1>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="logged_in_dealer_datatable">
              <thead>
                <th>Sr.no</th>
              	<th>Dealer Code</th>
              	<th>Dealer Name</th>
              	<th>Sap Code</th>
              	<th>Ad. Name</th>
              	<th>Contact No</th>
              	<th>Location</th>

              </thead>
              <tbody>
               <td>1</td>
               <td>2</td>
               <td>3</td>
               <td>4</td>
               <td>5</td>
               <td>6</td>
               <td>7</td>

              </tbody>
          </table>
      </div>
</div>
      
    <br><br>
</div>    

<div class="row">
    <div class="modal fade" id="dealer_doc_modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Uploaded Dealer Document</h4>
        </div>
        <div class="modal-body">
         Dealer Name :  <h4 id="dealer_name"></h4>
           <div class="row">
                  <div class="col-md-6" style="">
                 Agreement PDF :  <a name="agrrement_pdf" id="agrrement_pdf" target="_blank" download>Click here</a>
                  </div>
                  <div class="col-md-6" style="">
                 GST : <a name="gst_certificate" id="gst_certificate" target="_blank" download>Click here</a>
                  </div>
                 
          </div>
          <div class="row">
                  <div class="col-md-6" style="">
                 Pan Card :  <a name="pan_card" id="pan_card" target="_blank" download>Click here</a>
                  </div>
                  <div class="col-md-6" style="">
                 Cancel Cheque  : <a name="cancel_cheque" id="cancel_cheque" target="_blank" download>Click here</a>
                  </div>
                 
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
      
</section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript">
  
$(document).ready(function(){

  table = $('#logged_in_dealer_datatable').DataTable({ 

            "scrollX": true,
            "processing": true,
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'dom': 'Bfrtip',
             "buttons": ['excel', 'csv', 'pdf', 'print'], 
          // Load data for the table's content from an Ajax source
          "ajax": {
              'url' : base_url+'admin/logged_in_dealer_ajax',
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