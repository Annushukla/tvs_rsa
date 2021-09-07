<style type="text/css">
  .report-blocks .info-box { min-height: 65px; border-top: 3px solid #d2d6de;}
  .report-blocks .info-box-content { margin-left: 0; }
  .report-blocks .info-box-content .info-box-text {text-overflow: initial;overflow: visible; white-space: pre-wrap;}
  table.table-bordered{
    border:1px solid black;
    margin-top:20px;
  }
  table.table-bordered > thead > tr > th{
    border:1px solid black;
  }
  table.table-bordered > tbody > tr > td{
    border:1px solid black;
  }
</style>
<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
  <section class="content-header report-blocks">
      <h1 style="color:blue">Consolidated Report</h1>
      <br>
      <div class="container">
        <form name="submitdate" method="post" action="<?php echo base_url('admin/consolidated_report')?>">
          <div class="row form-group">
            <div class="col-md-6">
              <label>Date</label>
              <input type="date" name="select_date" id="select_date" style="height: 42px;" value="<?php echo isset($_REQUEST['select_date'])?$_REQUEST['select_date']:date('Y-m-d')?>">
              <input type="submit" value="Submit" class="btn btn-primary" style="height: 42px;">
            </div>

            <div class="col-md-5">
              <a class="btn btn-primary pull-right" onclick="tableToExcel('consolidated_report', 'TVS Dashboard','consolidated_report.xls')" >Export to Excel</a>
            </div>
          </div>
        </form>
      </div>
             
              <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="consolidated_report">
                      <thead class="thead-dark">
                        <tr>
                          <th rowspan="2">S.NO</th>
                          <th rowspan="2">Zone</th>
                          <th colspan="3">RSAPA Policy</th>
                          <th colspan="3">RR310 New Policy</th>
                          <th colspan="3">RR310 Renewal Policy</th>
                          <th colspan="3">RR310 Online Policy</th>
                          <th colspan="3">Basic Service</th>
                        </tr>
                        <tr>
                          <th>FTD</th>
                          <th>MTD</th>
                          <th>YTD</th>
                          <th>FTD</th>
                          <th>MTD</th>
                          <th>YTD</th>
                          <th>FTD</th>
                          <th>MTD</th>
                          <th>YTD</th>
                          <th>FTD</th>
                          <th>MTD</th>
                          <th>YTD</th>
                          <th>FTD</th>
                          <th>MTD</th>
                          <th>YTD</th>
                        </tr>
                      </thead>
                      <tbody>
                  <?php $i=1;

                        $rsasum_ftd=0;
                        $rsasum_mtd=0;
                        $rsasum_ytd=0;

                        $nsumftd=0;
                        $nsummtd=0;
                        $nsumytd=0;

                        $rsumftd=0;
                        $rsummtd=0;
                        $rsumytd=0;

                        $osumftd=0;
                        $osummtd=0;
                        $osumytd=0;

                        $bsumftd=0;
                        $bsummtd=0;
                        $bsumytd=0;


                        foreach($zoneallpolicycounts as $value) {
                          extract($value);
                          
                          $rsasum_ftd=$rsasum_ftd+$RSA_FTD;
                          $rsasum_mtd=$rsasum_mtd+$RSA_MTD;
                          $rsasum_ytd=$rsasum_ytd+$RSA_YTD;

                          $nsumftd=$nsumftd+$N_FTD;
                          $nsummtd=$nsummtd+$N_MTD;
                          $nsumytd=$nsumytd+$N_YTD;

                          $rsumftd=$rsumftd+$R_FTD;
                          $rsummtd=$rsummtd+$R_MTD;
                          $rsumytd=$rsumytd+$R_YTD;

                          $osumftd=$osumftd+$O_FTD;
                          $osummtd=$osummtd+$O_MTD;
                          $osumytd=$osumytd+$O_YTD;

                          $bsumftd=$bsumftd+$B_FTD;
                          $bsummtd=$bsummtd+$B_MTD;
                          $bsumytd=$bsumytd+$B_YTD;

                         ?>
                        <tr>
                          <td><?php echo $i;?>
                          <td><?php echo $zone;?></td>
                          <td><?php echo $RSA_FTD;?></td>
                          <td><?php echo $RSA_MTD;?></td>
                          <td><?php echo $RSA_YTD;?></td>
                          <td><?php echo $N_FTD;?></td>
                          <td><?php echo $N_MTD;?></td>
                          <td><?php echo $N_YTD;?></td>
                          <td><?php echo $R_FTD;?></td>
                          <td><?php echo $R_MTD;?></td>
                          <td><?php echo $R_YTD;?></td>
                          <td><?php echo $O_FTD;?></td>
                          <td><?php echo $O_MTD;?></td>
                          <td><?php echo $O_YTD;?></td>
                          <td><?php echo $B_FTD;?></td>
                          <td><?php echo $B_MTD;?></td>
                          <td><?php echo $B_YTD;?></td>
                        </tr>
                  <?php $i++;
                  } ?>
                    </tbody>
                     <thead>
                      <tr>
                          <th colspan="2">Total No. of Policy</th>
                          <th><?php echo $rsasum_ftd;?></th>
                          <th><?php echo $rsasum_mtd;?></th>
                          <th><?php echo $rsasum_ytd;?></th>
                          <th><?php echo $nsumftd;?></th>
                          <th><?php echo $nsummtd;?></th>
                          <th><?php echo $nsumytd;?></th>
                          <th><?php echo $rsumftd;?></th>
                          <th><?php echo $rsummtd;?></th>
                          <th><?php echo $rsumytd;?></th>
                          <th><?php echo $osumftd;?></th>
                          <th><?php echo $osummtd;?></th>
                          <th><?php echo $osumytd;?></th>
                          <th><?php echo $bsumftd;?></th>
                          <th><?php echo $bsummtd;?></th>
                          <th><?php echo $bsumytd?></th>
                      </tr>
                    </thead>
                    </table>
                  </div>
      </div>
<div class="clearfix"></div>
  </section>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
   $(function(){
    var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var maxDate = year + '-' + month + '-' + day;
    
    $('#select_date').attr('max', maxDate);
  });

});
</script>