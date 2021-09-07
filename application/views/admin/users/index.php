      <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url().ADMIN_CSS_JS;?>plugins/datatables/dataTables.bootstrap.css">
	<!-- DataTables -->
    <script src="<?php echo base_url().ADMIN_CSS_JS;?>plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url().ADMIN_CSS_JS;?>plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script>
      $(function () {
        $("#example1").DataTable();
      });
    </script>
	  
	  <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            User Management
            
          </h1>
          <ol class="breadcrumb">
            
            <li style="margin-right: 10px;margin-top: -8px;" > 
			  <a href="<?php echo base_url().'Admin/addUser';?>">	<button class="btn btn-primary btn-sml" >Add</button>  </a>
			</li>
			
			
            
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              

              <div class="box">
                <div class="box-header">
                
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
						<th class="col-xs-1">No.</th>
                        <th class="col-xs-2">Full Name</th>
						<th class="col-xs-1">Email</th>
                        <th class="col-xs-1">Birth day</th>
                        <th class="col-xs-1">Mobile No</th>
                        <th class="col-xs-2">Addrss</th>
                        <th class="col-xs-2">Action</th>
                      </tr>
                    </thead>
                    <tbody>
					<?php if(isset($result)){ ?>
						<?php $i=1; foreach($result as $row){ ?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $row->user_fullname; ?></td>
								<td><?php echo $row->user_email; ?></td>
								<td><?php echo $row->user_birthday; ?></td>
								<td><?php echo $row->user_mobile_no_1; ?></td>
								<td><?php echo $row->user_address; ?></td>
								 <td>
									<!--<a title="Send Offer" href="<?php echo base_url().'Admin/sendOffer/offer/'. $row->user_id;?>" ><i class="glyphicon glyphicon-send"></i></a> -->
									<a title="Delete Offer" onclick="return confirm('Are you sure you want to delete?')" href="<?php echo base_url().'Admin/userDelete/'. $row->user_id;?>" ><i class="glyphicon glyphicon-remove"></i></a>
									<a title="Edit Offer" href="<?php echo base_url().'Admin/editUser/'. $row->user_id;?>" ><i class="glyphicon glyphicon-pencil"></i></a>									
								
															
									
								</td>
								</tr>
						<?php $i++;} ?>
					<?php } ?>
                      
                      </tbody>
                    <tfoot>
						<th class="col-xs-1">No.</th>
                        <th class="col-xs-2">Full Name</th>
						<th class="col-xs-1">Email</th>
                        <th class="col-xs-1">Birth day</th>
                        <th class="col-xs-1">Mobile No</th>
                        <th class="col-xs-2">Addrss</th>
                        <th class="col-xs-2">Action</th>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
