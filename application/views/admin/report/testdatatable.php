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
               TVS RSA Cover Policies
            </h1>
            <br><br>
            <div class="row form-group">
                <div class="col-md-2">
                    <label>Start Date :</label>
                    <input type="date" class="form-control" name="start_date" id="tvs_start_date" placeholder="Start Date" min="2018-08-01" max="">
                </div>
                <div class="col-md-2">
                    <label>End Date :</label>
                    <input type="date" class="form-control" name="end_date" id="tvs_end_date" placeholder="End Date" min="2018-08-01" max="">
                </div>
                <div class="col-md-2">
                    <label> &nbsp;</label>
                    <button type="button" class="form-control btn btn-primary" name="submit" id="filter_by_date" value="Submit">Submit</button>
                </div>
            </div>

            <br><br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="example-col">
                        <table class="table table-bordered table-striped w-full" id="tvs_policy_datatable">
                            <thead>
                            <th>Sr.no</th>
                            <th>Policy No</th>
                            <th>Engine no</th>
                            <th>Chassis No</th>
                            <th>Vehicle Registration No</th>
                            <th>Make</th>
                            <th>Model</th>
                            <th>Customer Name</th>
                            <th>Mobile No</th>
                            <th>Email</th>
                             <th>Start Date</th>
                            <th>End Date</th>
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
         $('#tvs_policy_datatable').DataTable( {
                serverSide: true,
                ordering: false,
                searching: false,
                ajax: function ( data, callback, settings ) {
                    var out = [];
         
                    for ( var i=data.start, ien=data.start+data.length ; i<ien ; i++ ) {
                        out.push( [ i+'-1', i+'-2', i+'-3', i+'-4', i+'-5' ] );
                    }
         
                    setTimeout( function () {
                        callback( {
                            draw: data.draw,
                            data: out,
                            recordsTotal: 5000000,
                            recordsFiltered: 5000000
                        } );
                    }, 50 );
                },
                scrollY: 200,
                scroller: {
                    loadingIndicator: true
                },
            } );




        
        // table = $('#tvs_policy_datatable').DataTable({

        //     "scrollX": true,
        //     "processing": true,
        //     'paging': true,
        //     'lengthChange': true,
        //     'searching': true,
        //     'ordering': true,
        //     'info': true,
        //     'autoWidth': false,
        //     'dom': 'Bfrtip',
        //     "buttons": ['excel', 'csv', 'pdf', 'print'],
        //     // Load data for the table's content from an Ajax source
        //     "ajax": {
        //         'url': base_url + 'Report/TvsPolicyAjax',
        //         "type": "POST",
        //         "dataType": "json",
        //         "dataSrc": function (jsonData) {
        //             return jsonData.data;
        //         }
        //     },

        //     //Set column definition initialisation properties.
        //     "columnDefs": [
        //         {
        //             "targets": [0], //first column / numbering column
        //             "orderable": false, //set not orderable
        //         },
        //     ],

        // });

        $('#filter_by_date').on('click', function () {
            var tvs_start_date = $('#tvs_start_date').val();
            var tvs_end_date = $('#tvs_end_date').val();

            table1 = $('#tvs_policy_datatable').DataTable({
                'paging': true,
                'destroy': true,

                "ajax": {
                    'url': base_url + 'Report/TvsPolicyAjax',
                    "type": "POST",
                    "data": {'tvs_start_date': tvs_start_date, 'tvs_end_date': tvs_end_date},
                    "dataType": "json",
                    "dataSrc": function (jsonData) {
                        return jsonData.data;
                    }
                },

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


                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        "targets": [0], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ],

            });

        });
    });


</script>