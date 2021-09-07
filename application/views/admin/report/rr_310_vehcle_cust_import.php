<?php
$ic_id=$this->session->userdata('admin_session')['ic_id'];
?>

<style>
    .dt-buttons .buttons-pdf, .dt-buttons .buttons-csv{background-color:lightblue!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    .dt-buttons .buttons-excel, .dt-buttons .buttons-print{background-color:lightgreen!important; border:transparent; padding:2px 5px; border:1px solid #efefef;}
    #policy_datatable_filter label{float:right;}
</style>


<div class="wrapper">

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>RR310 Import File</h1>
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
                <form accept-charset="utf-8" enctype="multipart/form-data" action="<?php echo base_url('SaveImportData'); ?>" name="frmImport" method="POST" class="form-horizontal" role="form" id="cust_veh_import" accept=".xlsx, .xls, .csv">
                <div id="cust_info" class="custom-ewnow-form">
                    <div class="col-md-12 form-blue">
                        <p id="message" style="color: red"><?php echo $this->session->flashdata('message')?></p>
                        
                        <div class="row">                                                                          
                            <div class="col-md-12">
                             <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="file" id="import_cust_veh_file"  value="" name="import_cust_veh_file" placeholder="Import CSV" class="form-control " autofocus style="text-transform:uppercase" required>
                                        <span id="error-import_cust_veh_file" style="color: red"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                             
                    </div>
                    <div class="col-md-12 text-right padng-B p-0" style="margin: 20px 0;">
                        <div class="col-md-1">
                            <input type="button" name="cust_veh_import_btn" id="cust_veh_import_btn" class="btn btn-primary button_purple" value="Import" />
                            <input type="hidden" name="cust_veh_import_hd" id="cust_veh_import_hd" class="btn btn-primary button_purple" value="Import" />
                            <div class="loadData load_hide">Loading</div>
                        </div>
                   <div class="col-md-4">
                     <?php if($_GET['updated']) {   ?>
                     <a href="<?php echo base_url();?>/uploads/vehicle_customer_data/duplicateengineno.csv">Download Duplicate Engine no.s</a>
                   </div>
                <?php } ?>
                    </div>
                    
                </div>
                </form>
            </div>
            <h3 style="color: red;"><?php echo $this->session->flashdata('message');?></h3>
            <br><br>


        </section>

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">
   
   $('#cust_veh_import_btn').click(function(){
        $("#cust_veh_import_btn").attr('disabled',true);
        $('.loadData').removeClass('load_hide');
        $('.loadData').addClass('load_show');
        document.getElementById("cust_veh_import").submit();
  });
</script>
<style>
.load_show {display: block;}
.load_hide {display: none;}
    </style>
