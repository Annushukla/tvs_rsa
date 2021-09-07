<style>
.error{color:red;}
</style>
<div class="wrapper">

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                RM Dealer Mapping Import
            </h1>

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
                <form accept-charset="utf-8" enctype="multipart/form-data" action="<?php echo base_url('admin/save_rm_data'); ?>" name="frmImport" method="POST" class="form-horizontal" role="form" id="rm_data_import" accept=".xlsx, .xls, .csv">
                <div id="cust_info" class="custom-ewnow-form">
                    <div class="col-md-12 form-blue">
                        <p id="message" style="color: red"><?php echo $this->session->flashdata('message')?></p>
                        
                        <div class="row">                                                                          
                            <div class="col-md-12">
                             <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="file" id="rm_import"  value="" name="rm_import_file" placeholder="Import CSV" class="form-control " required>
                                        <span id="error-rm_import" style="color: red"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                             
                    </div>
                    <div class="col-md-12 text-right padng-B p-0" style="margin: 20px 0;">
                        <div class="col-md-1">
                            <input type="button" name="rm_import_btn" id="rm_import_btn" class="btn btn-primary button_purple" value="Import" />
                            <input type="hidden" name="rm_import_hd" id="rm_import_hd" class="btn btn-primary button_purple" value="Import" />
                            <div class="loadData load_hide">Loading</div>
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
<script type="text/javascript">
   
   $('#rm_import_btn').click(function(){
        var import_path=$("#rm_import").val();

        if(import_path.length>0){
            $("#rm_import_btn").attr('disabled',true);
            $('.loadData').removeClass('load_hide');
            $('.loadData').addClass('load_show');
        
            document.getElementById("rm_data_import").submit();
        }
        else{
            $("#error-rm_import").html('Please Select File');
        }
  });
</script>
<style>
.load_show {display: block;}
.load_hide {display: none;}
</style>
