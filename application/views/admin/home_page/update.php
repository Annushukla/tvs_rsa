
	<script src="<?php echo base_url().ADMIN_CSS_JS;?>plugins/jquery_validation_js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url().ADMIN_CSS_JS;?>custom_js/custom_function_js.js"></script>
	<!--<link rel="stylesheet" href="<?php //echo base_url().ADMIN_CSS_JS;?>plugins/multiple-select/multiple-select.css" />
	<script src="<?php echo base_url().ADMIN_CSS_JS;?>plugins/multiple-select/multiple-select.js"></script>
	<script>
		$(function() {
			$('#ms').change(function() {
				console.log($(this).val());
			}).multipleSelect({
				width: '100%'
			});
		});
	</script>-->
	
	
	
	  <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Home Page
            
          </h1>
		  <a  style="float:right; margin-right: 23px;margin-top: -24px;font-size: 20px;"  href="<?php echo base_url()."Admin/usersAlbumManagement";?>"> Back</a>
          
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
				
				<form id="frm_punch_card_add_update" class="form-horizontal" method="post"  enctype="multipart/form-data"  action="" >
				
                  <div class="box-body">
				  
					
					<div class="form-group">
                      <label class="col-sm-2 control-label">Home Page Album</label>
                      <div class="col-sm-10">
						
						<input name="user_album_images[]" type="file"  multiple="multiple" >
						<br>
						<?php if(!empty($result)) { ?>
							<?php foreach($result as $row) { ?>
								<span id="image_delete_<?php echo $row->id;?>" >
									<image  src="<?php echo base_url().'uploads/home_page/100x100/'.$row->image;?>" />
									<span style="cursor:pointer" onclick="deleteImage(<?php echo $row->id;?>)" ><i class="glyphicon glyphicon-remove"></i></span>
								</span>
							<?php } ?>
						<?php } ?>
						
							
						
					  </div>
                    </div>
					
                  </div><!-- /.box-body -->
                  <div class="box-footer">
					<input name="created_at" value="<?php echo date('Y-m-d H:i:s');?>"  type="hidden" >
					<button type="submit" value="submit" name="submit" class="btn btn-info pull-right">Save</button>
                  </div>
                </form>
              </div><!-- /.box -->
              <!-- general form elements disabled -->
              
            </div><!--/.col (right) -->
			  
			  
              
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
