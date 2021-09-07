
<link href="<?php echo base_url(); ?>assets/css/styles.css?v=1.1" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

<!-- thimbnails -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/demo.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/set1.css">
<!-- thimbnails -->
<style type="text/css">
    @media (min-width: 768px) {
        .modal-dialog {
            width: 900px;            
        }
    }
    .pro_intro_table thead {background-color: #0070c0;}
    .pro_intro_table thead th{color: #fff;}

</style>

<main class="section--lightGray main-ewnow">
<div class=" form-group">
<!--Oriental Popup Modal.. -->

<?php
if($dealer_confirm =='false'){?>
    <div class="modal fade" id="oriental_popup" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h4 class="modal-title">Changes in Personal Accident Coverages under RSA  from today i.e. 06-09-2019</h4>
                </div>
                <form id="" role="form" action="<?php echo base_url('SubmitConfirmOriental')?>"  method="post">
                    <div class="modal-body">
                        <p><b>1.PA Coverage changes in the RSA with complementary PA cover effective 6th Sept 2019:-</b></p>
                        <ul>
                            <li>The personal accident coverage is applicable only to the owner of the vehicle for which Road Side Assistance service has been provided by the Indicosmic Capital Pvt Ltd.</li>
                            <li>The said personal accident cover is active only whilst the owner is driving or travelling in the said vehicle including mounting into/dismounting from the said vehicle.</li>
                            <li>Death or permanent total disability claims due to any other incidence would not be covered</li>
                            <li>The same would be effective from 6th Sept 2019 for all insurance companies part of this program</li>
                        </ul>
                        <p><b>2.New Product Introduction</b></p>
                        <table class="table table-sm pro_intro_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Plan Name</th>
                                    <th>RSA Tenure</th>
                                    <th>RSA Covered Kms</th>
                                    <th>PA + Accidental Medical Expenses</th>
                                    <th>PA Sum Insured</th>
                                    <th>Accidental Medical Expenses Sum Insured</th>
                                    <th>PA RSD *</th>
                                    <th>Dealer Margin</th>
                                    <th>Policy Price (Incl GST)*</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Sapphire Plus</td>
                                    <td>2 Years</td>
                                    <td>25</td>
                                    <td>1 Year</td>
                                    <td>15 lac</td>
                                    <td>25,000</td>
                                    <td>Current</td>
                                    <td>₹ 125</td>
                                    <td>₹ 675</td>
                                </tr>
                            </tbody>
                        </table>
                        <p>Terms of the products</p>
                        <p><b>Sapphire Plus</b> would be exclusively provided by <b>Kotak General Insurance</b></p>
                        <ul>
                            <li><b>A PA cover covered under</b><br>The personal accident coverage is applicable only to the owner of the vehicle for which Road Side Assistance service has been provided by the ICPL.  The said personal accident cover is active only whilst the owner is driving or travelling in the said vehicle including mounting into/dismounting from the said vehicle.</li>
                            <li><b>Cover - Accidental Medical Expenses Extension</b><br>Coverage details – We will pay the reimburse the Medical Expenses incurred by the Insured Person provided that such treatment is following the Accident and If we have admitted a Claim for Accidental Death or Permanent Total Disablement <br><b>Limit – Upto INR 25,000</b></li>
                        </ul>
                        <p><b>3.Introduction of Oriental Insurance Company Limited :-</b></p>
                        <p>We are glad to inform you that we are introducing Oriental Insurance Company as our additional partner for the complementary PA cover provided by us along with different plans od RSA. Oriental being a Public sector company the acceptability with TVS customers would be at par with our existing other Insurance Partners.</p>
                        <input type="checkbox" id="oriental_declaration" name="oriental_declaration" value="1"><p>I have read and understood the changes as mentioned above and accept the above changes and terms and condition of the RSA products offered by ICPL</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="dealer_id" value="<?php echo $this->session->userdata('user_session')['id'];?>">
                        <button type="submit" id="oicl_decl_submit" value="submit" name="submit" class="btn btn-success" disabled="disabled">Submit</button>
                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                    </div>
                </form>
            </div>

        </div>
    </div>
<?php }?>
    <!-- End Modal..... -->
</div>
    <input type="hidden" id="is_uploaded" name="is_uploaded" class="is_uploaded" value="<?= $is_uploaded; ?>">
    <div class="container cont-pa" style="padding-top:0px; border: 0px;">
        <div class="row" style="  font-family: 'Roboto';">
            <div class="col-md-12 text-center">
                <div style="background: #de4019; color: #fff;  padding: 10px 15px; font-size: 18px; line-height: 21px;">For any problem/assistance call: <b>9372777632 / 9372777633 / 9372777634.</b></div>
            </div>
            <div class="col-md-12">
                <!-- <h2 align="center" class="heading-sold">Dashboard</h2> -->

                <marquee direction="left" style="background: #183883; color: #fff;  padding: 5px;">TVS PA Policy With Liberty General Insurance PA Policy Is Now Live</marquee>
            </div>
            <div class="col-md-12">
                <div class="card">

                <!--
                            <div class="card welcome-bg custom-ewnow-form">
                                <div class="card-block">
                                    <h5>
                                        <span class="glyphicons glyphicons-display"></span>Dashboard
                                    </h5>
                                    <h4></h4>
                                    <div class="clear"></div>
                                </div>
                            </div> -->

                <div class="row blocks-grid custom-ewnow-grid" id="custom-grid-pacovers">
                </div>
                <div class="row blocks-grid custom-ewnow-grid">
                    <div class="col-md-12 p-tb-10">
                        <div class="col-md-4">
                            <div class="grid p-0">
                                <figure class="effect-layla">
                                    <img src="<?= base_url(); ?>assets/images/RoadSide-Assistance-img.jpg" alt="img06" class="img-responsive">
                                    <figcaption>
                                        <h2>Roadside<br/><span>Assistance</span></h2>
                                        <p>Create Policy</p>
                                        <a class="btn btn-black custom-btn-grid marT10" id="" href="<?php echo base_url('generate_policy'); ?>" > Click Here
                                        </a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
						<div class="col-md-4">
                            <div class="grid p-0">
                                <figure class="effect-layla">
                                    <img src="<?= base_url(); ?>assets/images/RoadSide-Assistance-img.jpg" alt="img06" class="img-responsive">
                                    <figcaption>
                                        <h2>Limitless<span>Assist</span></h2>
                                        <!-- <h2>RSA<span>RR310</span></h2> -->
                                        <p>RSA RR310 Create Policy</p>
                                        <a class="btn btn-black custom-btn-grid marT10" id="" href="<?php echo base_url('generate_policy_rr310'); ?>" > Click Here
                                        </a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="grid p-0">
                                <figure class="effect-layla">
                                    <img src="<?= base_url(); ?>assets/images/Certificate.jpg" alt="img06" class="img-responsive">
                                    <figcaption>
                                        <h2>Certificates  of<br/><span> RSA</span></h2>
                                        <p>Download PDF</p>
                                        <a class="btn btn-black custom-btn-grid" href="<?php echo base_url('sold_rsa_policy'); ?>">Click Here</a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                        
                        <!--  <div class="col-md-4">
                             <div class="grid">
                                 <figure class="effect-layla">
                                     <img src="<?= base_url(); ?>assets/images/Claims.jpg" alt="img06" class="img-responsive">
                                     <figcaption>
                                         <h2>Claim<br/><span> Management </span></h2>
                                      <p>Get covered against accidental death and disability with personal accident insurance.</p>
                                         <a class="btn btn-black custom-btn-grid" href="<?php //echo base_url('pa_claim');                  ?>">Click Here</a>
                                     </figcaption>
                                 </figure>
                             </div>
                         </div> -->


                      <!--   <div class="col-md-4">
                            <div class="grid p-0">
                                <figure class="effect-layla">
                                    <img src="<?= base_url(); ?>assets/images/Payin-Slip-img.jpg" alt="img06" class="img-responsive">
                                    <figcaption>
                                        <h2>PayIn<br/><span> slip </span></h2>
                                     <p>Get covered against accidental death and disability with personal accident insurance.</p>
                                        <a class="btn btn-black custom-btn-grid" href="<?php echo base_url('generate-pay-ing-slip'); ?>">Click Here</a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div> -->
                    </div>
                    <div class="col-md-12 p-tb-10">
                        <div class="col-md-4">
                            <div class="grid p-0">
                                <figure class="effect-layla">
                                    <img src="<?= base_url(); ?>assets/images/Cancel-Policy-img.jpg" alt="img06" class="img-responsive">
                                    <figcaption>
                                        <h2>Cancel<br/><span> Policy </span></h2>
                                    <!--     <p>Get covered against accidental death and disability with personal accident insurance.</p> -->
                                        <a class="btn btn-black custom-btn-grid" href="<?php echo base_url('cancelPolicy'); ?>">Click Here</a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                        <?php
                        $user_session = $this->session->userdata('user_session');
                        $sap_ad_code = $user_session['sap_ad_code'];
                        $numlength = strlen($sap_ad_code);
                        if ($numlength = 5) {
                            ?>
                            <div class="col-md-4">
                                <div class="grid p-0">
                                    <figure class="effect-layla">
                                        <img src="<?= base_url(); ?>assets/images/Dashboard-img.jpg" alt="img06" class="img-responsive">
                                        <figcaption>
                                            <h2>Payments<br/><span>my account section</span></h2>
                                        <!--     <p>Get covered against accidental death and disability with personal accident insurance.</p> -->
                                            <a class="btn btn-black custom-btn-grid" href="<?php echo base_url('myDashboardSection'); ?>">Click Here</a>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                            <div class="col-md-4">
                            <div class="grid p-0">
                                <figure class="effect-layla">
                                    <img src="<?= base_url(); ?>assets/images/Dealer-Documents-img.jpg" alt="img06" class="img-responsive">
                                    <figcaption>
                                        <h2>Dealer<br/><span>Documents</span></h2>
                                    <!--     <p>Get covered against accidental death and disability with personal accident insurance.</p> -->
                                        <a class="btn btn-black custom-btn-grid" href="<?php echo base_url('dealer_document_form'); ?>">Click Here</a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                        <?php } ?>
                        
                    </div>

                    <div class="col-md-12 p-tb-10">
                        <div class="col-md-4">
                            <div class="grid p-0">
                                <figure class="effect-layla">
                                    <img src="<?= base_url(); ?>assets/images/Dashboard-img.jpg" alt="img06" class="img-responsive">
                                    <figcaption>
                                        <h2>Paid Service<br/><span> Camp </span></h2>
                                        <a class="btn btn-black custom-btn-grid" href="<?php echo base_url('generate_policy_workshop'); ?>">Click Here</a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="grid p-0">
                                <figure class="effect-layla">
                                    <img src="<?= base_url(); ?>assets/images/Dashboard-img.jpg" alt="img06" class="img-responsive">
                                    <figcaption>
                                        <h2>Renew Only RSA<br/><span> Service </span></h2>
                                        <a class="btn btn-black custom-btn-grid" href="<?php echo base_url('renew_only_rsa'); ?>">Click Here</a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                       
                        
                    </div>

                </div>

                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Login</h4>
                            </div>
                            <form id="login_form" role="form"  method="post">
                                <div class="modal-body">
                                    <div style="margin: 0 auto; width: 80%;">
                                        <div class="row form-group">
                                            <input type="text" id="username" name="username" class="form-control" placeholder="Dealer Code">
                                            <p id="error_email"></p>
                                        </div>
                                        <div class="row form-group">
                                            <input type="text" id="userpassword" name="userpassword" class="form-control" placeholder="Dealer Password">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="dealer_form_submit" class="btn btn-success">Submit</button>
                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>        <!-- End Loading Main Content -->


</main>

<?php  if($dealer_campaign_status==='false'){?>
<div class="dashboad-modal">    
    <div class="dashboad-modal-wrap">
        <div class="dashboad-modal-content">
            <div class="row">                
                <div class="col-md-4">
                    <div class="dashboad-modal-text">
                        <p>Dear partner,</p>
                        <p>Request you to update the contact details of insurance manager, CEO/ GM and Dealer Principal.</p>
                        <p>Updated list will help is to send communications related to RSA (operational and technical), reward and recognition and update on new products.</p>
                        <p>Your one time active participation is highly appreciated.</p>
                        <p style="text-align: right;margin-top: 40px;">- Team Indicosmic Capital</p>
                    </div>
                </div>
                <div class="col-md-8">
                    <form method="post" id="campaign_form" action="<?php echo base_url('submitDealerCampaignList');?>">
                        <div class="row">
                            <h4><?php echo $this->session->flashdata('campaign_msg');?></h4>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Insurance Manager</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="row form-group required">
                                    <!-- <label class="col-sm-12 control-label">Name</label> -->
                                    <div class="col-sm-12">
                                        <input type="text" name="ins_manager_name" id="ins_manager_name" placeholder="Name" class="form-control" >
                                        <span style="color: red;font-size: 12px;" id="error-ins_manager_name"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row form-group required">
                                    <!-- <label class="col-sm-12 control-label">Email</label> -->
                                    <div class="col-sm-12">
                                        <input type="email" name="ins_manager_email" id="ins_manager_email" placeholder="Email" class="form-control" >
                                        <span style="color: red;font-size: 12px;" id="error-ins_manager_email"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row form-group required">
                                    <!-- <label class="col-sm-12 control-label">Contact</label> -->
                                    <div class="col-sm-12">
                                        <input type="text" maxlength="10" minlength="10" name="ins_manager_contact" id="ins_manager_contact" placeholder="Contact" class="form-control" >
                                         <span style="color: red;font-size: 12px;" id="error-ins_manager_contact"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>General Manager/CEO</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="row form-group required">
                                    <!-- <label class="col-sm-12 control-label">Name</label> -->
                                    <div class="col-sm-12">
                                        <input type="text" name="gm_name" id="gm_name" placeholder="Name" class="form-control" >
                                         <span style="color: red;font-size: 12px;" id="error-gm_name"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row form-group required">
                                    <!-- <label class="col-sm-12 control-label">Email</label> -->
                                    <div class="col-sm-12">
                                        <input type="email" name="gm_email" id="gm_email" placeholder="Email" class="form-control" >
                                         <span style="color: red;font-size: 12px;" id="error-gm_email"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row form-group required">
                                    <!-- <label class="col-sm-12 control-label">Contact</label> -->
                                    <div class="col-sm-12">
                                        <input type="text" maxlength="10" minlength="10" name="gm_contact" id="gm_contact" placeholder="Contact" class="form-control" >
                                         <span style="color: red;font-size: 12px;" id="error-gm_contact"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Dealer Principal</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="row form-group required">
                                    <!-- <label class="col-sm-12 control-label">Name</label> -->
                                    <div class="col-sm-12">
                                        <input type="text" name="principle_name" id="principle_name" placeholder="Name" class="form-control" >
                                         <span style="color: red;font-size: 12px;" id="error-principle_name"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row form-group required">
                                    <!-- <label class="col-sm-12 control-label">Email</label> -->
                                    <div class="col-sm-12">
                                        <input type="email" name="principle_email" id="principle_email" placeholder="Email" class="form-control" >
                                         <span style="color: red;font-size: 12px;" id="error-principle_email"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row form-group required">
                                    <!-- <label class="col-sm-12 control-label">Contact</label> -->
                                    <div class="col-sm-12">
                                        <input type="text" maxlength="10" minlength="10" name="principle_contact" id="principle_contact" placeholder="Contact" class="form-control" >
                                         <span style="color: red;font-size: 12px;" id="error-principle_contact"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Dealer Code</h4>
                                <div class="row form-group required">
                                    <div class="col-sm-12">
                                        <input type="text" name="dealer_no" id="dealer_no" placeholder="Dealer Code" class="form-control" value="<?=$dealer_code?>" readonly>
                                         <span style="color: red;font-size: 12px;" id="error-dealer_no"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Showroom Name</h4>
                                <div class="row form-group required">
                                    <div class="col-sm-12">
                                        <input type="text" name="showoom_name" id="showoom_name" placeholder="Showroom Name" class="form-control" >
                                         <span style="color: red;font-size: 12px;" id="error-showoom_name"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="button" id="campaign_submit_btn" class="btn btn-success btn-lg" >Submit</button>
                                <button type="button" id="campaign_close_btn" class="btn btn-success btn-lg" >Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>            
        </div>
    </div>
</div>
<?php }?>
<?php if($wallet_popup=='true'){?>
<div class="row">
      <!-- Modal -->
  <div class="modal fade" id="wallet_modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content col-md-6 col-md-offset-3">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Wallet Balance</h4>
        </div>
        <div class="modal-body">
          <p>Your Current Wallet Balance is <?php echo $balance;?>.<br> Minimum Balance is 1000.<br><br> Please maintain wallet balance.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

</div>
<?php }?>

<div class="">
    <input type="hidden" name="dealer_campaign_status" id="dealer_campaign_status" value="<?=$dealer_campaign_status?>">
</div>
<div class="dashboad-overlay in"></div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dashboard.js"></script>

