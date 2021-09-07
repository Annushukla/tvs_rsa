
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv='cache-control' content='no-cache'>
        <meta http-equiv='expires' content='0'>
        <meta http-equiv='pragma' content='no-cache'>

        <title>TVS RSA</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <!-- Start adding additional css from controller before custom css  -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/all.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/dashboard_styles.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/table.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/print.css">
        <?php
        $current_uri = $this->uri->segment(1);
        if ($current_uri == 'myDashboardSection') {
            ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/site.css">
        <?php }
        ?>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-datepicker.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">


        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-3.2.1.min.js"></script>
        <script type="text/javascript">
            var base_url = '<?php echo base_url(); ?>';
        </script>
        <!-- End adding additional css from controller before custom css  -->

        <!-- Custom Css Starts -->
        <!-- Custom Css Ends -->

        <!-- Start adding additional css from controller after custom css  -->
        <!-- End adding additional css from controller after custom css  -->
        <style type="text/css">
            .err{
                color:red;
            }
/*
            .modal {
                    display:    none;
                    position:   fixed;
                    z-index:    1000;
                    top:        0;
                    left:       0;
                    height:     100%;
                    width:      100%;
                    background: rgba( 255, 255, 255, .8 ) 
                                url('http://i.stack.imgur.com/FhHRx.gif') 
                                50% 50% 
                                no-repeat;
                }

                /* When the body has the loading class, we turn
                   the scrollbar off with overflow:hidden */
           /*     body.loading .modal {
                    overflow: hidden;   
                }

                /* Anytime the body has the loading class, our
                   modal element will be visible */
             /*   body.loading .modal {
                    display: block;
                } */

        </style>

    </head>
    <body>
        <header id="my-assist-nav" class="custom-ewnow-header">
            <div class="container">
                <div class="row">
                    <!-- Start Navbar -->
                    <div  id="nav-assistance"  class="col-md-3 heading-ewnow">

                        <a href="<?php echo base_url('dashboard'); ?>"><img src="<?php echo base_url(); ?>assets/images/tvs.png" alt="Logo" class="img-responsive"></a>

                    </div>
                    <div class="col-md-7 text-right">
                        <?php
                        $user_details = $this->session->userdata('user_session');
                        // echo '<pre>'; print_r($user_details);//die('here');
                        $deale_name = $user_details['dealer_name'];
                        $sap_ad_code = $user_details['sap_ad_code'];
                       //  die($sap_ad_code);
                        if ((!empty($user_details['gst_no']) && !empty($user_details['pan_no'])) ) {
                            ?>
                            <p class="text-black" style="margin: 0 0 10px;
                               color: #0090d3;
                               font-weight: bold; line-height: 63px;"><a href="<?php echo base_url('faq_generate_invoice'); ?>">How to Generate Tax Invoice ?</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?= $deale_name ?><a href="<?php echo base_url('dashboard'); ?>"> <i class="fa fa-home"></i> Home</a></p>
                        </div>

                        <div id="nav-assistance" class="col-md-2 custom-ewnow-nav dropdown-menu2">
                            <ul class="navigation">
                                <a class="main" href="#url"><i class="fa fa-user"></i> My Account </a>
                                <li class="n2"><a href="<?= base_url('generate_policy'); ?>">RSA</a></li>
                                <li class="n2"><a href="<?= base_url('sold_rsa_policy'); ?>">Certificates </a></li>
                                <?php
                                $numlength = mb_strlen($sap_ad_code);
                                // die($sap_ad_code);
                                if ($numlength === 5 || in_array($sap_ad_code, array('1011591','1010964')) ) {
                                    ?>
                                    <li class="n2"><a href="<?= base_url('myDashboardSection'); ?>">My Dashboard </a></li>
                                    <li class="n2"><a href="<?= base_url('dealer_document_form'); ?>">My Documents</a></li>
                                <?php } ?>
                                <li class="n2"><a href="<?= base_url('cancelPolicy'); ?>">Cancel Policy</a></li>
                              
                                <?php 

                                if($sap_ad_code == 22222 || $sap_ad_code == 11111){
                                ?>
                                <li class="n3"><a href="<?php echo base_url('dealer_logout'); ?>">Sign Out </a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                    <!-- End Navbar -->
                </div>
            </div>
        </header>