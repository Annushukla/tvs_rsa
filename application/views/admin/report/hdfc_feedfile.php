<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         HDFC FEED-FILE
      </h1>

     
         <div class="row">
      <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered dataTable table-striped w-full">
              <thead>
              <tr>
                  <th  style="text-align:center;border-right: 0px;">From</th>
                  <th  style="text-align:center;border-right: 0px;">To</th>
                  <th  style="text-align:center;">Download</th>
              </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="text-align:center;padding: 0px 6px 0px 14px;">
                    <input type="hidden"   name="ic_id"  id="ic_id" class="form-control form-control-sm" value="<?= $ic_id?>">
                    <input type="hidden"   name="endorse"  id="endorse" class="form-control form-control-sm" value="<?= $endorse?>">
                    <input type="hidden"   name="canceled"  id="canceled" class="form-control form-control-sm" value="<?= $canceled?>">
                    <input type="date"   name="from_date"  id="from_date" class="form-control form-control-sm" style="height: 42px;">
                    <span style="color:red;" id="err_from_date"></span>
                  </td>
                  <td style="text-align:center;">
                    <input type="date" name="to_date" id="to_date" class="form-control form-control-sm">
                    <span style="color:red;" id="err_to_date"></span>
                  </td>
                  <td style="text-align:center;">
                    <input type="submit" value="Download CSV" onclick="download_csv();" class="btn btn-secondary buttons" style="height: 42px;">
                  </td>
                </tr>
              </tbody>
          </table>
      </div>
</div>
      <div class="col-md-6 pull-right" id="image_loader">
        <img src="<?php echo base_url();?>assets/images/loading5.gif" height="100" width="">
      </div>
    <br><br>
</div>    


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

<script>
function download_csv()
  {
  var from_date     = $('#from_date').val();
  var to_date       = $('#to_date').val();
  var ic_id       = $('#ic_id').val();
  var endorse = $('#endorse').val();
  var canceled = $('#canceled').val();
  var policy_type = "NULL";
  if(endorse!=""){
    policy_type = endorse ;
  }
  if(canceled!=""){
     policy_type = canceled ;
  }
  var status = true;
  var datas = ic_id+'/'+from_date+'/'+to_date+'/'+policy_type;
// alert(datas);
  if(from_date=='' || to_date==''){
      alert('Please Select the Date');
      status = false;
  }
  if(status==true){
        if(from_date <= to_date){
              $.ajax({
              url : base_url+"Report/DownloadHdfcFeedfile/"+datas,
              type: "POST",
              success: function(data){
                if(data!=""){
                  alert('Feed-File Downloaded');
                }
             window.location=base_url+"Report/DownloadHdfcFeedfile/"+datas;
              }
            });
        }else{
                $('#err_to_date').text('To Date should be Greater Than From Date');
        }
  }

 
  
    }  
</script>

