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
	    <h1 style="color:blue">TVS Dashboard Report</h1>
	    <br>
      <div class="container">
        <form name="submitdate" method="post" action="<?php echo base_url('admin/tvsdashboard_report')?>">
          <div class="row form-group">
            <div class="col-md-6">
              <label>Date</label>
              <input type="date" name="select_date" id="select_date" style="height: 42px;" value="<?php echo isset($_REQUEST['select_date'])?$_REQUEST['select_date']:date('Y-m-d')?>">
              <input type="submit" value="Submit" class="btn btn-primary" style="height: 42px;">
            </div>

            <div class="col-md-5">
              <a class="btn btn-primary pull-right" onclick="tableToExcel('tvsdashboard_report', 'TVS Dashboard','tvsdashboard_report.xls')" >Export to Excel</a>
            </div>
          </div>
        </form>
      </div>
             
              <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="tvsdashboard_report">
                      <thead class="thead-dark">
                        <tr>
                          <th rowspan="2">S.NO</th>
                          <th rowspan="2">Zone</th>
                          <th colspan="3">No. of New Policy</th>
                          <th colspan="3">No. of Renewal Policy</th>
                          <th colspan="3">No. of Total Policy</th>
                          <th colspan="3">No. of Cancel Policy</th>
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
                        </tr>
                      </thead>
                      <tbody>
                  <?php $i=1;
                        $nsum_ftd=0;
                        $nsum_mtd=0;
                        $nsum_ytd=0;

                        $rsum_ftd=0;
                        $rsum_mtd=0;
                        $rsum_ytd=0;

                        $tsum_ftd=0;
                        $tsum_mtd=0;
                        $tsum_ytd=0;

                        $csum_ftd=0;
                        $csum_mtd=0;
                        $csum_ytd=0;
                        foreach($zonepolicycounts as $value) {
                          extract($value);

                          $nsum_ftd=$nsum_ftd+$N_FTD;
                          $nsum_mtd=$nsum_mtd+$N_MTD;
                          $nsum_ytd=$nsum_ytd+$N_YTD;

                          $rsum_ftd=$rsum_ftd+$R_FTD;
                          $rsum_mtd=$rsum_mtd+$R_MTD;
                          $rsum_ytd=$rsum_ytd+$R_YTD;

                          $tsum_ftd=$tsum_ftd+($N_FTD+$R_FTD);
                          $tsum_mtd=$tsum_mtd+($N_MTD+$R_MTD);
                          $tsum_ytd=$tsum_ytd+($N_YTD+$R_YTD);

                          $csum_ftd=$csum_ftd+$C_FTD;
                          $csum_mtd=$csum_mtd+$C_MTD;
                          $csum_ytd=$csum_ytd+$C_YTD;
                         ?>
                        <tr>
                          <td><?php echo $i;?>
                          <td><?php echo $zone;?></td>
                          <td><?php echo $N_FTD;?></td>
                          <td><?php echo $N_MTD;?></td>
                          <td><?php echo $N_YTD;?></td>
                          <td><?php echo $R_FTD;?></td>
                          <td><?php echo $R_MTD;?></td>
                          <td><?php echo $R_YTD;?></td>
                          <td><?php echo ($N_FTD + $R_FTD);?></td>
                          <td><?php echo ($N_MTD + $R_MTD);?></td>
                          <td><?php echo ($N_YTD + $R_YTD);?></td>
                          <td><?php echo $C_FTD;?></td>
                          <td><?php echo $C_MTD;?></td>
                          <td><?php echo $C_YTD;?></td>
                      </tr>
                  <?php $i++;
                  } ?>
                    </tbody>
                     <thead>
                      <tr>
                          <th colspan="2">Total No. of Policy</th>
                          <th><?php echo $nsum_ftd;?></th>
                          <th><?php echo $nsum_mtd;?></th>
                          <th><?php echo $nsum_ytd;?></th>
                          <th><?php echo $rsum_ftd;?></th>
                          <th><?php echo $rsum_mtd;?></th>
                          <th><?php echo $rsum_ytd;?></th>
                          <th><?php echo $tsum_ftd;?></th>
                          <th><?php echo $tsum_mtd;?></th>
                          <th><?php echo $tsum_ytd;?></th>
                          <th><?php echo $csum_ftd;?></th>
                          <th><?php echo $csum_mtd;?></th>
                          <th><?php echo $csum_ytd;?></th>
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