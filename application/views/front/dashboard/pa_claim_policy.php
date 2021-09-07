<?php $this->load->view('includes/datatable') ?>
<script src="<?php echo base_url(); ?>assets/js/claim_policy.js"></script>
<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">

<main class="section--lightGray main-ewnow">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 align="center" class="heading-sold">Claim</h2>
                <h2 align="center"><?php echo $this->session->flashdata('Message');
; ?></h2>
            </div>
        </div>
        <div class="row form-inline" style="padding-bottom: 20px;">
            <div class="col-md-6">
                <h3 style="color: #f21692;"></h3>

                <input class="form-control mr-sm-2" type="text" id="search_data" placeholder="Search">
                <button class="btn btn-secondary my-2 my-sm-0" id="search_button" type="button">Search</button>
            </div>

            <div class="col-md-6 text-right">
                <br>
                <a href="<?php echo base_url(); ?>dashboard"><button class="btn btn-primary button_purple my-2 my-sm-0" id="search_button" type="button">Back</button></a>
            </div>

        </div>
        <div class="row table100 ver1">
            <div class="col-md-12">
                <div class="" id="table_html">
                    <table class="table display  table-striped  data_table custom-datatable-soldpapolicy" id="">
                        <thead>
                        <th>No.</th>
                        <th>Invoice No</th>
                        <th>Invoice Date</th>
                        <th>Engine No</th>
                        <th>Chassiss No</th>
                        <th>Certificate No</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                            <tr id="tbody_html">
                            </tr>



                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            <div class="modal fade" id="claim_modal" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">courier Uploads</h4>
                        </div>

                        <div class="modal-body">
                            <form id='dealer_estimationupload_form' action="<?php echo base_url('upload_claim_data') ?>" method="POST" class="" enctype="multipart/form-data" >
                                <input type="text" id="pa_policy_id" hidden="" name="pa_policy_id" value="">
                                <div class="col-md-12 nopadding">
                                    <div class="form-group text-left">

<!-- <span style="color:red" >* </span>Courier POD Number</label> -->
                                        <div class="col-sm-12 nopadding">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <label for="type" class="text-left">
                                                        <span style="color:red" ></span>To Select Multiple files (press Ctrl + select)</label>
                                                    <input  type="file" multiple name="pa_cover_pdf[]" id="pa_cover_pdf-1"
                                                            placeholder="PDF" class="form-control"
                                                            value="" autofocus>
                                                </div>

                                                <div class="col-md-5">
                                                    <label for="type" class="text-left">
                                                        <span style="color:red" >* </span>Courier POD Number</label>
                                                    <input  type="text" id="courier_no" name="courier_no"
                                                            placeholder="Courier Number" class="form-control cust_info_field"
                                                            value="" required autofocus>
                                                </div>

                                            </div>


                                        </div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="" class="btn btn-primary btn-info pull-right" type="submit">Submit</button>
                        </div>
                        </form>
                        <div class="clearfix"></div>

                    </div>

                </div>
            </div>
        </div>



    </div>
</main>