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
      <h1 style="color:blue">Online Renewed Policies Report</h1>
      <br>
      <div class="container">
        <form class="form-horizontal" name="submitdate" method="post" action="">  
            <div class="row form-group">
               <div class="col-md-5">
                 <a class="btn btn-primary pull-right" onclick="tableToExcel('online_renewed_policy_report', 'Online Renewed Policy Report','online_renewed_policy_report.xls')" >Export to Excel</a>
              </div>              
            </div>
        </form>
      </div>

      <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="online_renewed_policy_report">
                      <thead class="thead-dark">
                        <tr>
                          <th></th>
                          <th>FTD</th>
                          <th>MTD</th>
                          <th>YTD</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Sent SMS</td>
                          <td><?php echo $sms_ftd;?></td>
                          <td><?php echo $sms_mtd;?></td>
                          <td><?php echo $sms_ytd;?></td>
                          
                        </tr>
                        <tr>
                          <td>View SMS</td>
                          <td><?php echo $customer_activity_ftd;?></td>
                          <td><?php echo $customer_activity_mtd;?></td>
                          <td><?php echo $customer_activity_ytd;?></td>
                          
                        </tr>
                        <tr>
                          <td>Sold Renewed Policies</td>
                          <td><?php echo $policy_ftd;?></td>
                          <td><?php echo $policy_mtd;?></td>
                          <td><?php echo $policy_ytd;?></td>
                          
                        </tr>
                         
                      </tbody>
                     <thead>
                      <tr>
                          <th >Total</th>
                          <th><?php echo ($sms_ftd+$customer_activity_ftd+$policy_ftd)?></th>
                          <th><?php echo ($sms_mtd+$customer_activity_mtd+$policy_mtd)?></th>
                          <th><?php echo ($sms_ytd+$customer_activity_ytd+$policy_ytd)?></th>
                          
                      </tr>
                    </thead>
                    </table>
                  </div>
      </div>
<div class="clearfix"></div>
  </section>
  </div>
</div>