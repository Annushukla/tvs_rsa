<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Calibri" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">
<main class="section--lightGray main-ewnow" style="  font-family: 'Roboto';">

    <header class="custom-pacoverheader text-center">
        <p>Upload / DEALER Documents</p>
        <h2 class="banner-small-text">Upload the Dealer's Document <br/>To Start Punching Policy.</h2>
    </header>

    <div class="container marT20 margnB5">
        <div class="row">
            <input type="hidden" id="policy_id" value="<?= $policy_id ?>" name="policy_id">
            <input type="hidden" id="is_downloaded" value="<?= $is_downloaded ?>" name="is_downloaded">
            <input type="hidden" id="page_name" value="dealer_document_page" name="page_name">
            <div class="col-md-12 custom-bg-dark text-center">
                <div class="col-md-12 white-bg" style="height:  auto;">
                    <div id="cust_info_panel">
                        <div id="cust_info" class="custom-ewnow-form">
                            <div class="col-md-12 form-blue">
                                <form method="post" action="<?php echo base_url('dealer_uploads_data'); ?>" autocomplete="off" class="form-horizontal" role="form" id="dealer_uploads_form" enctype="multipart/form-data">
                                    <input type="hidden" id="dealer_id" name="dealer_id" value="<?php echo @$check_dealer_docs[0]['id']; ?>" >
                                    <div class=" ">
                                        <div class="col-md-12 text-left nopadding" style="position: relative;"><h5>Upload / DEALER Documents</h5>
                                        </div>
                                        <p id="error-message" style="color: red;
                                           position: absolute;
                                           top: 10px;
                                           right: 12px;
                                           font-weight: bold;"></p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <!--<div class="col-md-6">-->
                                            <div class="form-group">
                                                
                                                <div class="col-sm-8">
                                                      <div class="col-sm-4" style="padding-left: 10px;">
                                                    <label for="agreement_pdf" class="control-label" style="padding: 1%;float: left;">Download Agreement Pdf
                                                </label>
                                                    </div>
                                                     <div class="col-sm-6">
                                                    <a href="<?= base_url('download_rsa_agreement_pdf'); ?>" id="download_pdf" target="_blank">Click Here To Download Agreement Pdf</a>
                                                </div>
                                                </div>
                                            </div>
                                            <!--</div>-->
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="agreement_pdf" class="col-sm-3 control-label">Agreement Pdf
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="file" id="agreement_pdf"  name="agreement_pdf" placeholder="Upload PDF" class="form-control cust_info_field" autofocus accept=".pdf, .png, .jpg, .jpeg"  >
                                                        <span id="error-agreement_pdf" style="color: red"><?php echo $this->session->flashdata('er_agreement'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pancard" class="col-sm-3 control-label">Pan Card
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="file" id="pancard"  name="pancard" placeholder="Upload Pancard" class="form-control cust_info_field" autofocus accept=".pdf, .png, .jpg, .jpeg"  >
                                                        <span id="error-pancard" style="color: red"><?php echo $this->session->flashdata('er_pancard'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                               <div class="form-group">
                                                   <label for="gst_certificate" class="col-sm-3 control-label">GST Certificate
                                                       <span style="color:red" >*</span>
                                                   </label>
                                                   <div class="col-sm-9">
                                                       <input type="file" id="gst_certificate"  name="gst_certificate" placeholder="Upload GST Certificate" class="form-control cust_info_field" autofocus accept=".png, .jpg, .jpeg, .pdf" >
                                                       <span id="error-gst_certificate" style="color: red"><?php echo $this->session->flashdata('er_gst_certificate');           ?></span>
                                                   </div>
                                               </div>
                                           </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="cancel_cheque" class="col-sm-3 control-label">Cancel Cheque
                                                        <span style="color:red" >*</span>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="file" id="cancel_cheque"  name="cancel_cheque" placeholder="Upload Cancel Cheque" class="form-control cust_info_field" autofocus accept=".pdf, .png, .jpg, .jpeg"  >
                                                        <span id="error-cancel_cheque" style="color: red"><?php echo $this->session->flashdata('er_cancel_cheque'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-right padng-B">
                                        <div class="col-md-12">
                                            <button type="submit" id="upload_dealer_docs" class="btn btn-primary button_purple">Upload Documents</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php
// print_r($check_dealer_docs);die;
    if (!empty($check_dealer_docs)) {
        $agreement_pdf = ($check_dealer_docs[0]['agreement']) ? $check_dealer_docs[0]['agreement'] : '';
        $gst_certificate = ($check_dealer_docs[0]['gst_certificate']) ? $check_dealer_docs[0]['gst_certificate'] : '';
        $pan_card = ($check_dealer_docs[0]['pan_card']) ? $check_dealer_docs[0]['pan_card'] : '';
        $cancel_cheque = ($check_dealer_docs[0]['cancel_cheque']) ? $check_dealer_docs[0]['cancel_cheque'] : '';
        $this->load->helper('download');
        ?>

        <div class="container marT20 margnB5">
            <div class="row">
                <input type="hidden" id="policy_id" value="<?= $policy_id ?>" name="policy_id">
                <div class="col-md-12 custom-bg-dark text-center">


                    <div class="col-md-12 white-bg" style="height:  auto;">
                        <div id="cust_info_panel">
                            <div id="cust_info" class="custom-ewnow-form">
                                <div class="col-md-12 form-blue">
                                    <form method="post" action="<?php echo base_url('dealer_uploads_data'); ?>" autocomplete="off" class="form-horizontal" role="form" id="dealer_uploads_form" enctype="multipart/form-data">
                                        <div class=" ">
                                            <div class="col-md-12 text-left nopadding" style="position: relative;"><h5>Uploaded / DEALER Documents</h5>
                                            </div>
                                            <span id="error-message" style="color: red;
                                                  position: absolute;
                                                  top: 10px;
                                                  right: 12px;
                                                  font-weight: bold;"><?php echo $this->session->flashdata('upload_success'); ?></span>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-6" style="<?= empty($agreement_pdf)?"display: none":""?>">
                                                    <div class="form-group">
                                                        <label for="agreement_pdf" class="col-sm-3 control-label">Agreement Pdf
                                                            <span style="color:red" >*</span>
                                                        </label>
                                                        <div class="col-sm-9 control-label">
                                                            <a href="<?php echo base_url('uploads/dealer_docs').'/'.$agreement_pdf;
                                                            ?>" target="_blank" download>Click here</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="<?= empty($gst_certificate)?"display: none":""?>">
                                                   <div class="form-group">
                                                       <label for="adharcard" class="col-sm-3 control-label">GST Certificate
                                                           <span style="color:red" >*</span>
                                                       </label>
                                                       <div class="col-sm-9 control-label">
                                                           <a href="<?php echo base_url('uploads/dealer_docs').'/'.$gst_certificate;?>" target="_blank" download>Click here</a>
                                                       </div>
                                                   </div>
                                               </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-6" style="<?= empty($pan_card)?"display: none":""?>">
                                                    <div class="form-group">
                                                        <label for="pancard" class="col-sm-3 control-label">Pan Card
                                                            <span style="color:red" >*</span>
                                                        </label>
                                                        <div class="col-sm-9 control-label">
                                                            <a href="<?php echo base_url('uploads/dealer_docs').'/'.$pan_card;
                                                            ?>" target="_blank" download>Click here</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="<?= empty($cancel_cheque)?"display: none":""?>">
                                                    <div class="form-group">
                                                        <label for="cancel_cheque" class="col-sm-3 control-label">Cancel Cheque
                                                            <span style="color:red" >*</span>
                                                        </label>
                                                        <div class="col-sm-9 control-label">
                                                            <a href="<?php echo base_url('uploads/dealer_docs').'/'.$cancel_cheque;
                                                            ?>" target="_blank" download>Click here</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <?php } ?>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/generate_pa_policy.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var dealer_id = $('#dealer_id').val();
        // alert(dealer_id);
    });
</script>