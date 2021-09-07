<script type="text/javascript" src="<?php echo base_url('assets/js/tvs_admin.js');?>"></script>
<div class="wrapper">

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Send SMS</h1>
            <br><br>
        <div class="row container">
            <h3 style="color: red"><?php echo $this->session->flashdata('success');?></h3>
        </div>
            <br><br>
        <div class="container">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="card border-secondary mb-3" style="max-width: 20rem;">
                        <div class="card-header">Not Logged In Dealer</div>
                        <div class="card-header">3.30</div>
                        <div class="card-body">
                            <a href="<?php echo base_url('admin/NotLoggedIn');?>" onclick="return confirm('Are you sure you want to Send SMS ?')" >Send SMS</a>
                        </div>
                    </div>
                 </div>
                <div class="col-md-4">
                    <div class="col-md-4">
                    <div class="card border-secondary mb-3" style="max-width: 20rem;">
                        <div class="card-header">Less Wallet Balance</div>
                        <div class="card-header">10.30</div>
                        <div class="card-body">
                            <a href="<?php echo base_url('admin/less_balance_dealer');?>" onclick="return confirm('Are you sure you want to Send SMS ?')" >Send SMS</a>
                        </div>
                    </div>
                 </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-4">
                    <div class="card border-secondary mb-3" style="max-width: 20rem;">
                        <div class="card-header">Dealer Policy Issued Today</div>
                        <div class="card-header">07.30</div>
                        <div class="card-body">
                            <a href="<?php echo base_url('admin/dealer_policy_issued_today');?>" onclick="return confirm('Are you sure you want to Send SMS ?')" >Send SMS</a>
                        </div>
                    </div>
                 </div>
                </div>
            </div>
            <div class="row form-group">
                 <div class="col-md-4">
                    <div class="col-md-4">
                    <div class="card border-secondary mb-3" style="max-width: 20rem;">
                        <div class="card-header">Last 7 Days Inactive Dealers</div>
                        <div class="card-header">11am</div>
                        <div class="card-body">
                            <a href="<?php echo base_url('admin/last_7days_inactive_dealers');?>" onclick="return confirm('Are you sure you want to Send SMS ?')" >Send SMS</a>
                        </div>
                    </div>
                 </div>
                </div>
            </div>
        </div>
        </section>

        <!-- /.content -->
    </div>



    <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">

</script>