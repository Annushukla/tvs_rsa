	  <div class="content-wrapper">
        <section class="content-header">
          <h1>
           <?php echo ($this->uri->segment(2) == 'addUser') ? 'Add User' : 'Edit User' ?>
		   <a  class="pull-right"  href="<?php echo base_url().'Admin/usersManagement';?>">Back</a>
          </h1>
		  
        </section>

        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              
			<div class="col-md-12">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo $this->session->flashdata('suc_msg');?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="post"  enctype="multipart/form-data"  action="" >
                  <div class="box-body">
				  
					<div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Full Name</label>
                      <div class="col-sm-10">
                        <input required="required" type="text"  value="<?php if(!empty($result->user_fullname)) echo $result->user_fullname; else echo set_value('user_fullname');?>" class="form-control" name="user_fullname" placeholder="Full Name">
						<?php echo form_error('user_fullname'); ?>
                      </div>
                    </div>
					
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-10">
                        <input required="required" type="email"  value="<?php if(!empty($result->user_email)) echo $result->user_email; else echo set_value('user_email'); ?>" class="form-control" name="user_email" placeholder="Email">
						<?php echo form_error('user_email'); ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                      <div class="col-sm-10">
                        <input required="required" type="password" value="<?php if(!empty($result->user_password)) echo $result->user_password; else echo set_value('user_password'); ?>" class="form-control" name="user_password" placeholder="Password">
						<?php echo form_error('user_password'); ?>
                      </div>
                    </div>
                    
					
					<div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 control-label">Mobile Number 1</label>
                      <div class="col-sm-10">
                        <input required="required" type="text" value="<?php if(!empty($result->user_mobile_no_1)) echo $result->user_mobile_no_1; else echo set_value('user_mobile_no_1'); ?>" class="form-control" name="user_mobile_no_1" placeholder="Mobile Number 1">
						<?php echo form_error('user_mobile_no_1'); ?>
                      </div>
                    </div>
                    
					<div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 control-label">Mobile Number 2</label>
                      <div class="col-sm-10">
                        <input required="required" type="text" value="<?php if(!empty($result->user_mobile_no_2)) echo $result->user_mobile_no_2; else echo set_value('user_mobile_no_2'); ?>" class="form-control" name="user_mobile_no_2" placeholder="Mobile Number 2">
						<?php echo form_error('user_mobile_no_2'); ?>
                      </div>
                    </div>
                    
					<div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 control-label">Pincode</label>
                      <div class="col-sm-10">
                        <input required="required" name="user_pincode" value="<?php if(!empty($result->user_pincode)) echo $result->user_pincode; else echo set_value('user_pincode'); ?>" type="text" class="form-control"  placeholder="Pincode">
						<?php echo form_error('user_pincode'); ?>
                      </div>
                    </div>
                    
					<div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 control-label">Address</label>
                      <div class="col-sm-10">
						<textarea required="required" class='col-sm-12' name="user_address" placeholder="Address" ><?php if(!empty($result->user_address)) echo $result->user_address; else echo set_value('user_address');?></textarea>
						<?php echo form_error('user_address'); ?>
                        
                      </div>
                    </div>
                    
					
							
					<div class="form-group">
                      <label class="col-sm-2 control-label">Image</label>
                      <div class="col-sm-10">
						<input name="hidden_image" type="hidden" value="<?php if(!empty($result->user_image)) echo $result->user_image;?>" />
                        <input name="user_image" type="file"   >
							<?php if(!empty($result->user_image)){ ?>
								<image  src="<?php echo base_url().'uploads/users/thumb/'.$result->user_image;?>" />
							<?php } ?>
                      </div>
                    </div>
					
                  </div><!-- /.box-body -->
                  <div class="box-footer">
					<?php if(!empty($result->user_id)){ ?>
						<input name="user_id" type="hidden" value="<?php if(!empty($result->user_id)) echo $result->user_id;?>" />
					<?php } ?>
                   
                    <button name="submit" value="submit" type="submit" class="btn btn-info pull-right">Save</button>
                  </div><!-- /.box-footer -->
                </form>
              </div><!-- /.box -->
              <!-- general form elements disabled -->
              
            </div><!--/.col (right) -->
			  
			  
              
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
