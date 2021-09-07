<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         Policy Summary Report Download
      </h1>

     
         <div class="row">
      <div class="col-lg-12">
      <div class="example-col">
                                  <table class="table table-bordered dataTable table-striped w-full">
                                      <thead>
                                      <tr>
                      <th  style="text-align:center;border-right: 0px;">Insurance Company</th>
                                          <th  style="text-align:center;border-right: 0px;">From</th>
                                          <th  style="text-align:center;border-right: 0px;">To</th>
                                          <th  style="text-align:center;">Download</th>
                                      </tr>
                                      </thead>
                    <tbody>
                    <tr>
                 
                    <td style="text-align:center;padding: 0px 6px 0px 14px;">
                    <select  name="ic_id"  id="ic_id" class="form-control form-control-sm" style="height: 42px;">
                 <option value="Bajaj Allianz">Bajaj Allianz</option>
                    </select>
                    </td>                                        
                    <td style="text-align:center;padding: 0px 6px 0px 14px;"><input type="date"   name="from_date"  id="from_date" class="form-control form-control-sm" style="height: 42px;"></td>
                                          <td style="text-align:center;"><input type="date" name="to_date" id="to_date" class="form-control form-control-sm"></td>
                                          <td style="text-align:center;"><input type="submit" value="Download CSV" onclick="download_csv();" class="btn btn-secondary buttons" style="height: 42px;"></td>
                                        </tr>
                                      </tbody>
                                  </table>
                                </div>
      </div>
      
    <br><br>
</div>    



</formss>
        
       </section>








    <!-- Main content -->
    <section class="content">

  
      <div class="box">
            <div class="box-header">
              <h3 class="box-title">MUTHOOT POLICY SUMMARY REPORT TABLE</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
             <tr>
                                            <th>Sr.No</th>
                                            <th>Category Name</th>
                                            <th>Policy No</th>
                                            <th>Custmoer Name</th> 
                                            <th>Mobile Number</th> 
                                            <th>Policy Start Date</th> 
                                            <th>Policy End Date</th> 
                                            <th>Policy Risk Date</th> 
                                            <th>Action</th> 
                                        </tr>
                </thead>
                <tbody>
             
               
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        
      <!-- /.row -->

    
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



  <!-- /.content-wrapper -->


  <!-- Control Sidebar -->
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>

<script>
    $(document).ready(function() {
        var dataTable = $('#example1').dataTable({
          "scrollX": true,
          "processing": true,
          'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
          "sDom": '<"dt-panelmenu clearfix"Bfr>t<"dt-panelfooter clearfix"ip>',
          "buttons": ['copy', 'excel', 'csv', 'pdf', 'print'],          
          "ajax": {
              url: "<?php echo base_url(); ?>policy_summary_ajax", 
              type: "post",
          }        
        });
    });
</script>



<script>
function download_csv()
{
  var from_date     = $('#from_date').val();
  var to_date       = $('#to_date').val();

  var datas = from_date+'/'+to_date;
 // alert(datas);

  $.ajax({
              url : "<?php base_url(); ?>download_policy_summary_report/"+datas,
              type: "POST",
              success: function(data){
      
      window.location="<?php base_url(); ?>download_policy_summary_report/"+datas;

              }
      });
    
}  
</script>


<!-- ./wrapper -->
