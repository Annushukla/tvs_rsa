<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <h1>Sapphire Till 30 Aug With Serial No. </h1>
      <br><br>
            <!-- <div class="row form-group">
                <div class="col-md-2">
                    <label> &nbsp;</label>
                    <a href="<?php echo base_url('Rsa_Dashboard/AllApologyLetterPDF')?>" class="form-control btn btn-primary" name="apology_pdf_btn" id="apology_pdf_btn" target="_blank">All PDF</a>
                </div>
            </div> -->
            <br><br>
      <div class="row">
        <div class="col-lg-12">
            <div class="example-col">
                <table class="table table-bordered table-striped w-full" id="serial_no_apology_tbl">
                    <thead>
                      <th>Sr.no</th>
                    	<th>Name</th>
                      <th>Address</th>
                    	<th>City</th>
                      <th>Pincode</th>
                      <th>Barcode</th>
                    	<!-- <th>Action</th> -->
                    </thead>
                    <tbody>
                     
                    </tbody>
                </table>
            </div>
        </div>
      </div>    
    </section>
  </div>
  <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">
  // function check(event)
  //   {
  //     var href = event.currentTarget.getAttribute('href');
  //     var id = event.currentTarget.getAttribute('data-id');
  //     $.ajax({
  //       'url' : base_url+'Report/updateDownloadStatus',
  //             "type": "POST",
  //             "data":{'policy_id':id},
  //             "dataType": "json",
  //             "success":function(response){
  //               if(response.status == 'true'){
  //                   myWindow=window.open(href,'_blank');
  //                   myWindow.document.close(); 
  //                   myWindow.focus();
  //                   myWindow.print(); 
  //               }
  //             }
  //     });
  //     return false;
  //   }
$(document).ready(function(){
  table = $('#serial_no_apology_tbl').DataTable({ 
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
              'url' : base_url+'Report/serial_no_apology_letter_ajax',
              "type": "POST",
              "data":{'is_downloaded':0},
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