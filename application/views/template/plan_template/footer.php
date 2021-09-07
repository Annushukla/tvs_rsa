

        <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <!-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" /> -->
 
        <!-- Include Date Range Picker -->
        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
        <!-- Start adding additional js from controller before custom js  -->
                <!-- End adding additional js from controller before custom js  -->
        
        <!-- Start adding additional js from controller after custom js  -->
                <!-- End adding additional js from controller after custom js  -->


                 <script type="text/javascript">

            $(document).on('click', '.selected_plan', function (e) {  
                var paln_amount = this.value;
                var paln_gst_per = $(this).attr('data-gstper');
                var paln_gst_amount = $(this).attr('data-gst');
                var paln_total_amount = $(this).attr('data-totalgst');

                var plan_detail_id = $(this).attr('data-plandetailid');
                
                $('#div_policy_amount').html(paln_amount);
                $('#div_policy_gst_per').html('GST '+ paln_gst_per+ ' %' );
                $('#div_policy_gst_amount').html(paln_gst_amount);
                $('#div_policy_total_amount').html(paln_total_amount);

                $('#cus_selected_plan').val(plan_detail_id);


                
               
            });
           

        </script>

    </body>
</html>
