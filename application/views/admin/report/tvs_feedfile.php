<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Download TVS Feed-File
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
                  <td style="text-align:center;padding: 0px 6px 0px 14px;"><input type="date"   name="from_date"  id="from_date" class="form-control form-control-sm" style="height: 42px;">
                    <span style="color:red;" id="err_from_date"></span>
                  </td>
                  <td style="text-align:center;"><input type="date" name="to_date" id="to_date" class="form-control form-control-sm">
                    <span style="color:red;" id="err_to_date"></span>
                  </td>
                  <td style="text-align:center;"><input type="button" value="Download CSV" id="download_csv" class="btn btn-secondary buttons" style="height: 42px;"></td>
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
$(document).ready(function (){

		$('#download_csv').click(function(){
		  var er_status = false;
		  var from_date = $('#from_date').val();
		  var to_date = $('#to_date').val();
		  if(to_date=="" || from_date==""){
		    er_status = true;
		    // $('#er_msg').text('Please Select Date');
		    alert('Please Select Date')
		  }

		  if(er_status==false){
		      if(from_date <= to_date){
		          $.ajax({
		              url : base_url+'downlodfeedfile_bydate',
		              type : 'POST',
		              dataType : 'JSON',
		              data : {from_date : from_date , to_date: to_date},
		              success : function(response){
		                //alert(response);
		                 console.log(response);
		                  var $a = $("<a>");
		                  $a.attr("href",response.file);
		                  $("body").append($a);
		                  $a.attr("download",from_date+'_'+to_date+".xls");
		                  $a[0].click();
		                  $a.remove();
		              }



		          });


		      }else{
		        $('#err_to_date').text('To Date shhould be Greater than From date');
		      }
		  }

		});
}) ;
</script>

