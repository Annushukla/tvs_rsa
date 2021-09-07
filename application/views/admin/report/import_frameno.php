<style>
.error{color:red;}
</style>
<div class="wrapper">

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Import Frame Nos for Paid Service Camp</h1>

            <?php 
            if(!empty($error)){
          for($i=0;$i<count($error);$i++){
      ?>
      <br><p class="error"><?php echo $error[$i];?></p>
    <?php } }?>

    <br><br>
<?php  
$success_message = $this->session->flashdata('success_message');

 if(!empty($success_message)) {?>
        <div class="row form-group">
            <div class="col-md-4 form-group alert alert-success" role="alert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
                <h4><?php echo isset($success_message) ? $success_message : '';?></h4>
          </div>
</div>
<?php }?>
            <div class="row form-group">
                <form accept-charset="utf-8" enctype="multipart/form-data" action="<?php echo base_url('admin/upload_frameno_file'); ?>" name="workshop_frameno_form" method="POST" class="form-horizontal" role="form" id="workshop_frameno_form" accept=".csv">
                <div id="cust_info" class="custom-ewnow-form">
                    <div class="col-md-12 form-blue">
                        <p id="message" style="color: red"><?php echo $this->session->flashdata('message')?></p>
                        
                        <div class="row">                                                                          
                            <div class="col-md-12">
                             <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="file" id="import_frameno"  value="" name="import_frameno_file" placeholder="Import CSV" class="form-control " required>
                                        <span id="error-import_frameno" style="color: red"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                             
                    </div>
                    <div class="col-md-12 text-right padng-B p-0" style="margin: 20px 0;">
                        <div class="col-md-1">
                            <input type="submit" name="import_btn" id="import_btn" class="btn btn-primary button_purple" value="Import" />
                        </div>
                    </div>
                </div> 
            </form>
        </div>
    </section>

</div>
</div>

<div class="control-sidebar-bg"></div>
</div>

