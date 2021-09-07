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
            </div>
        </div>
        <div class="row"><h3 style="color: #f21692;"></h3></div>
        <div class="row table100 ver1">
            <div class="col-md-12">
                <div class="bg-white border p-15">
                    <table class="table display table-striped data_table custom-datatable-soldpapolicy no-border" id="generated_payslip_table">
                        <thead>
                        <th>SR.No. <input type="checkbox" id="sel_all_policies"></th>
                        <th>Paying Slip No</th>
                        <th>Created Date</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        <td>1</td>
                        <td>2</td>
                        <td>3</td>
                        <td>4</td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>