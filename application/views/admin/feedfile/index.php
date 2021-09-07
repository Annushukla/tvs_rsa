<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Feed File
      </h1>
      <br><br><br>
     

      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Feed File Download</h3>
        </div>
        <div class="box-body">

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
                            <td style="text-align:center;">
                                                
                          
                              </td>                                        
                              <td style="text-align:center;"><input type="date"   name="from_date"  id="from_date" class="form-control form-control-sm"></td>
                                                    <td style="text-align:center;"><input type="date" name="to_date" id="to_date" class="form-control form-control-sm"></td>
                                                    <td style="text-align:center;"><input type="submit" value="Download CSV" onclick="download_csv();" class="btn btn-secondary buttons"></td>
                                                  </tr>
                                                </tbody>
                                            </table>

        </div>
      </div>
    </section>
  </div>
</div>
<!--Edit start from here -->

<!--Add start from here -->



<script type="text/javascript">
	

function download_csv()
{

  var from_date     = $('#from_date').val();
  var to_date       = $('#to_date').val();
  
  var datas = from_date+'/'+to_date;
  $.ajax({
              url : "<?php base_url(); ?>downloadfeedfile/"+datas,
              type: "POST",
              success: function(data){
      
      window.location="<?php base_url(); ?>downloadfeedfile/"+datas;

              }
      });
    
}  
</script>