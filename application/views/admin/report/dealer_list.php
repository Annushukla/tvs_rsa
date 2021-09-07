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
                Dealer List
            </h1>
            <br><br>
            <h3 style="color: red;"><?php echo $this->session->flashdata('message');?></h3>
            <br><br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="example-col">
                        <table class="table table-bordered table-striped w-full" id="dealer_list">
                            <thead>
                            <th>Sr.no</th>
                            <th>Dealer Code</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                 
                            </tbody>
                        </table>
                    </div>
                </div>

                <br><br>
            </div>



        </section>

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">

    $(document).ready(function () {
        dealer_list = $('#dealer_list').DataTable({ 
             "scrollX": true,
            "processing": true,
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'destroy'     : true,
            'dom': 'Bfrtip',
             "buttons": ['excel', 'csv', 'pdf', 'print'], 
          // Load data for the table's content from an Ajax source
          "ajax": {
              'url' : base_url+'Report/dealerListAjax',
              "type": "POST",
              "dataType": "json",
              "dataSrc": function (jsonData) {
                console.log(jsonData);
                    
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