	  
	  <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Profile
            
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Tables</a></li>
            <li class="active">Data tables</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              
			<div class="col-md-12">
              <!-- Horizontal Form -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <!--<h3 class="box-title">Horizontal Form</h3>-->
                </div><!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="post"  enctype="multipart/form-data"  action="<?php echo base_url().'Admin/profile';?>" >
                  <div class="box-body">
				  
					<div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Full Name</label>
                      <div class="col-sm-10">
                        <input required="required" type="text"  value="<?php echo $result->user_fullname;?>" class="form-control" name="user_fullname" placeholder="Full Name">
						<?php echo form_error('user_fullname'); ?>
                      </div>
                    </div>
					
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-10">
                        <input required="required" type="email"  value="<?php echo $result->user_email;?>" class="form-control" name="user_email" placeholder="Email">
						<?php echo form_error('user_email'); ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                      <div class="col-sm-10">
                        <input required="required" type="password" value="<?php echo $result->user_password;?>" class="form-control" name="user_password" placeholder="Password">
						<?php echo form_error('user_password'); ?>
                      </div>
                    </div>
                    
					
					<div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 control-label">Mobile Number 1</label>
                      <div class="col-sm-10">
                        <input required="required" type="text" value="<?php echo $result->user_mobile_no_1;?>" class="form-control" name="user_mobile_no_1" placeholder="Mobile Number 1">
						<?php echo form_error('user_mobile_no_1'); ?>
                      </div>
                    </div>
                    
					<div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 control-label">Mobile Number 2</label>
                      <div class="col-sm-10">
                        <input required="required" type="text" value="<?php echo $result->user_mobile_no_2;?>" class="form-control" name="user_mobile_no_2" placeholder="Mobile Number 2">
						<?php echo form_error('user_mobile_no_2'); ?>
                      </div>
                    </div>
                    
					<div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 control-label">Pincode</label>
                      <div class="col-sm-10">
                        <input required="required" name="user_pincode" value="<?php echo $result->user_pincode;?>" type="text" class="form-control"  placeholder="Pincode">
						<?php echo form_error('user_pincode'); ?>
                      </div>
                    </div>
                    
					<div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 control-label">Address</label>
                      <div class="col-sm-10">
						<textarea required="required" class='col-sm-12' name="user_address" placeholder="Address" ><?php echo $result->user_address;?></textarea>
						<?php echo form_error('user_address'); ?>
                        
                      </div>
                    </div>
                    
					
							
					<div class="form-group">
                      <label class="col-sm-2 control-label">Image</label>
                      <div class="col-sm-10">
						<input name="hidden_image" type="hidden" value="<?php echo $result->user_image;?>" />
                        <input name="user_image" type="file"   >
						
						<image  src="<?php echo base_url().'uploads/users/thumb/'.$result->user_image;?>" />
                      </div>
                    </div>
					
                  </div><!-- /.box-body -->
                  <div class="box-footer">
					
                   
                    <button type="submit" class="btn btn-info pull-right">Save</button>
                  </div><!-- /.box-footer -->
                </form>
              </div><!-- /.box -->
              <!-- general form elements disabled -->
              
            </div><!--/.col (right) -->
			  
			  
              
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
