<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>

<div class="wrapper">
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Party Payment Master</h1>
      <br>
    
<br>

<?php
  // echo '<pre>'; print_r($data);
  // echo '<pre>'; print_r($kotak_deposit_amount);
  // exit;


 $failed_message = $this->session->flashdata('failed');
    $success_message = $this->session->flashdata('success');
    $admin_role_id = $this->session->userdata('admin_session')['admin_role_id'] ;
if(!empty($failed_message)){
    ?>
<div class="row form-group">
  <div class="col-md-4 form-group alert alert-warning" role="alert">
    <h4><?php echo isset($failed_message) ? $failed_message : '';?></h4>
  </div>
</div>

<?php } 
 if(!empty($success_message)) {?>
<div class="row form-group">
    <div class="col-md-4 form-group alert alert-success" role="alert">
    <h4><?php echo isset($success_message) ? $success_message : '';?></h4>
  </div>
</div>

<br>
    <?php } if($admin_role_id == 1 || $admin_role_id == 8 || $admin_role=='opreation_admin') { ?> 
      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><b>KOTAK (PA)</b></h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td width="70%">KOTAK DEPOSIT AMOUNT</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $kotak_deposit_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>KOTAK SOLD POLICY AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo $kotak_total_policy_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>KOTAK BALANCE AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo round($kotak_balance_amount,2);?></span></td>
                  </tr>  
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><b>IL (PA)</b></h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td width="70%">IL DEPOSIT BALANCE</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $il_deposit_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>IL SOLD POLICY AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo $il_total_policy_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>IL BALANCE AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo round($il_balance_amount,2);?></span></td>
                  </tr>  
                </tbody>
              </table>
            </div>
          </div>
          </div>
          <div class="col-md-6">          
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><b>BAGI (PA)</b></h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td width="70%">BAGI DEPOSIT BALANCE</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $ba_deposit_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>BAGI SOLD POLICY AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo $ba_total_policy_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>BAGI BALANCE AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo round($ba_balance_amount,2);?></span></td>
                  </tr>  
                </tbody>
              </table>
            </div>
          </div> 
        </div>
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><b>TAIG (PA)</b></h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td width="70%">TAIG DEPOSIT BALANCE</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $tagi_deposit_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>TAIG SOLD POLICY AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo $tagi_total_policy_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>TAIG BALANCE AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo round($tagi_balance_amount,2);?></span></td>
                  </tr>  
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><b>Oriental (PA)</b></h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td width="70%">Oriental DEPOSIT BALANCE</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $oriental_deposit_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>Oriental SOLD POLICY AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo $oriental_total_policy_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>Oriental BALANCE AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo round($oriental_balance_amount,2);?></span></td>
                  </tr>  
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><b>Liberty (PA)</b></h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td width="70%">Liberty DEPOSIT BALANCE</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $liberty_deposit_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>Liberty SOLD POLICY AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo $liberty_total_policy_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>Liberty BALANCE AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo round($liberty_balance_amount,2);?></span></td>
                  </tr>  
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><b>Reliance (PA)</b></h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td width="70%">Reliance DEPOSIT BALANCE</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $reliance_deposit_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>Reliance SOLD POLICY AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo $reliance_total_policy_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>Reliance BALANCE AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo round($reliance_balance_amount,2);?></span></td>
                  </tr>  
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><b>HDFC (PA)</b></h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td width="70%">HDFC DEPOSIT BALANCE</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $hdfc_deposit_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>HDFC SOLD POLICY AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo $hdfc_total_policy_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>HDFC BALANCE AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo round($hdfc_balance_amount,2);?></span></td>
                  </tr>  
                </tbody>
              </table>
            </div>
          </div>
        </div>

         <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><b>BHARTI ASSIST (RSA)</b></h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td width="70%">BHARTI RSA TENURE 1 COUNT</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $bharti_rsa_tenure_1_count;?></span></td>
                  </tr>
                  <tr>
                    <td width="70%">BHARTI RSA TENURE 2 COUNT</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $bharti_rsa_tenure_2_count;?></span></td>
                  </tr>
                  <tr>
                    <td width="70%">BHARTI DEPOSIT BALANCE</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $bharti_deposit_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>BHARTI SOLD POLICY AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo $bharti_policy_amount;?></span></td>
                  </tr>
                  <tr>
                    <td>BHARTI BALANCE AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo round($bharti_balance_amount,2);?></span></td>
                  </tr>  
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><b>MYTVS (RSA)</b></h3> 
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td width="70%">MYTVS RSA TENURE 1 COUNT</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $mytv_rsa_tenure_1_count;?></span></td>
                  </tr>
                  <tr>
                    <td width="70%">MYTVS RSA TENURE 2 COUNT</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $mytv_rsa_tenure_2_count;?></span></td>
                  </tr>
                  <tr>
                    <td width="70%">MYTVS DEPOSIT BALANCE</td>
                    <td width="30%"><span class="badge bg-light-blue"><?php echo $mytvs_deposit_amount; ?></span></td>
                  </tr>
                  <tr>
                    <td>MYTVS SOLD POLICY AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo $mytvs_policy_amount; ?></span></td>
                  </tr>
                  <tr>
                    <td>MYTVS BALANCE AMOUNT</td>
                    <td><span class="badge bg-light-blue"><?php echo round($mytvs_balance_amount,2); ?></span></td>
                  </tr>  
                </tbody>
              </table>
            </div>
          </div>
        </div> 
      </div>




     <div class="row form-group">
     	<div class="col-md-2 pull-right">
     		 <a href="<?php echo base_url('admin/add_party_payment');?>" class="btn btn-primary">Add Payment</a>
     	</div>
     </div>
 <?php }?>
     <br><br>
     <div class="col-md-12">
      <div class="row form-group">
        <div style="background-color: #222d32; font-size: 18px; padding: 15px 0;  color: #fff;">
          <div class="col-md-3">
              <label>Start Date :</label>
              <input type="date" class="form-control" name="start_date" id="start_date" placeholder="Start Date" min="2018-08-01" max="">
          </div>
          <div class="col-md-3">
              <label>End Date :</label>
              <input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date" min="2018-08-01" max="">
              <span id="err_end_date" style="color:red;"></span>
          </div>
          <div class="col-md-2">
              <label> &nbsp;</label>
              <button type="button" class="form-control btn btn-primary" name="submit" id="filter_by_date" value="Submit">Submit</button>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
<div class="row">
  <div class="col-lg-12">
      <div class="example-col">
          <table class="table table-bordered table-striped w-full" id="party_payment_datatable">
              <thead>
                <th>Sr.no</th>
                <th>Party Name</th>
              	<th>Amount</th>
              	<th>Transaction No</th>
              	<th>IFSC Code</th>
              	<th>Bank Name</th>
              	<th>Account No</th>
              	<th>Payment Date</th>
              	<th>Created At</th>
              </thead>
              <tbody>
               
              </tbody>
          </table>
      </div>
</div>
    <br><br>
</div> 
</section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript">
  
$(document).ready(function(){

  table = $('#party_payment_datatable').DataTable({ 

             "scrollX": true,
            "processing": true,
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'dom': 'Bfrtip',
             "buttons": ['excel', 'csv', 'pdf', 'print'], 
          // Load data for the table's content from an Ajax source
          "ajax": {
              'url' : base_url+'admin/party_payment_details_ajax',
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
  $('#filter_by_date').on('click', function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            table1 = $('#party_payment_datatable').DataTable({
                'paging': true,
                'destroy': true,

                "ajax": {
                    'url': base_url + 'admin/party_payment_details_ajax',
                    "type": "POST",
                    "data": {'start_date': start_date, 'end_date': end_date},
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