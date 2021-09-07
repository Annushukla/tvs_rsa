<?php $this->load->view('includes/datatable') ?>
<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">

<main class="section--lightGray main-ewnow">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 align="left" class="heading-sold">Rejected Cancel Policy List</h2>
            </div>
        </div>

 
        <div class="row">   
            <div class="col-md-12 text-right">
                <a href="<?php echo base_url(); ?>sold_rsa_policy"><button class="btn btn-primary button_purple my-2 my-sm-0" id="search_button" type="button">Back</button></a>
            </div>
        </div>
        <div class="row"><h3 style="color: #f21692;"></h3></div>
        <div class="row table100 ver1">
            
            <div class="col-md-12">
                <div class="bg-white border p-15">
                    <table class="table display table-striped data_table custom-datatable-soldpapolicy no-border table-sm " id="reject_cancel_policy_list">
                        <thead class="thead-dark">
                            <th>No.</th>
                            <th>Product Type</th>
                            <th>Plan</th>
                            <th>Policy No.</th>
                            <th>Customer Name</th>
                            <th>Engine No.</th>
                            <th>Chassis No</th>
                            <th>Reason</th>
                            <th>Created Date</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    


    </div>
</main>

