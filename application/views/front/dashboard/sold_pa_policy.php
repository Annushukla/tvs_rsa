<?php $this->load->view('includes/datatable') ?>
<script src="<?php echo base_url(); ?>assets/js/sold_pa_policy.js"></script>
<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">

<main class="section--lightGray main-ewnow">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 align="left" class="heading-sold">Sold RSA Policies</h2>
            </div>
        </div>

 
        <div class="row">   
            <div class="col-md-12 text-right">
                <a href="<?php echo base_url(); ?>dashboard"><button class="btn btn-primary button_purple my-2 my-sm-0" id="search_button" type="button">Back</button></a>
                <a href="<?php echo base_url(); ?>cancelation_rejected_policy" class="btn btn-primary button_purple my-2 my-sm-0" >Cancelation Rejected Policies</a>
            </div>
        </div>
        <div class="row"><h3 style="color: #f21692;"></h3></div>
        <div class="row table100 ver1">
            <div class="row">
                <div class="col-md-12 form-group">
                    <div class="col-md-4">
                       From: <input type="text" name="" class="form-control" id="from_date" placeholder="Start Date" readonly="readonly">
                    </div>
                    <div class="col-md-4">
                       to: <input type="text" name="" class="form-control" id="to_date" placeholder="End Date" readonly="readonly">
                       <span style="color: red;" id="errto_date"></span>
                    </div>
                    <div class="col-md-3">
                         <input type="button" name="" class="btn btn-success" value="Submit" id="sold_policy_submit"> 
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="bg-white border p-15">
    <table class="table display table-striped data_table custom-datatable-soldpapolicy no-border" id="sold_pa_policy_table">
                        <thead>
                        <th>No.</th>
                        <th>Product Type</th>
                        <th>IC Name</th>
                        <th>Plan</th>
                        <th>Policy No.</th>
                        <th>Customer Name</th>
                        <th>Engine No.</th>
                        <th>Chassis No</th>
                        <th>Created Date</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>