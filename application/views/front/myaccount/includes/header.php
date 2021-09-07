<!doctype html>
<html class="no-js" lang="en">
    <head>
        <title>GIIB Hospicash</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta charset="utf-8">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />
        <!-- favicon -->
 
        <!-- animation --> 
        <link rel="stylesheet" href="<?php echo base_url();?>public/front_css_js/css/animate.css" />
        <!-- bootstrap --> 
        <link rel="stylesheet" href="<?php echo base_url();?>public/front_css_js/css/bootstrap.css" />
        <!-- et line icon --> 
        <link rel="stylesheet" href="<?php echo base_url();?>public/front_css_js/css/et-line-icons.css" />
        <!-- font-awesome icon -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/front_css_js/css/font-awesome.min.css" />
        <!-- hamburger menu  -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/front_css_js/css/menu-hamburger.css" />
        <!-- select2  -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/front_css_js/css/select2.min.css" />
        <!-- common -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/front_css_js/css/style.css" />
        <!-- responsive -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/front_css_js/css/responsive.css" />

        <link rel="stylesheet" href="<?php echo base_url();?>public/front_css_js/css/muthoot.css" />
        <link rel="stylesheet" type="text/css"  href="<?php echo base_url();?>public/front_css_js/css/bootstrap-datepicker.css">

        <!--[if IE]>
            <link rel="stylesheet" href="css/style-ie.css" />
        <![endif]-->
        <!--[if IE]>
            <script src="js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body>
        <!-- navigation panel -->
        <nav class="navbar navbar-default navbar-fixed-top nav-transparent overlay-nav sticky-nav nav-black nav-border-bottom " role="navigation">
            <div class="container width-100">
                <div class="rowr mainheader giib-header">
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <a class="logo-light" 
                        href="<?php echo base_url();?>">
                            <img alt="" src="<?php echo base_url();?>public/front_css_js/images/logo_spinner.gif" class="logo">
                        </a>
                        <a class="logo-dark" href="<?php echo base_url();?>">
                            <img alt="" src="<?php echo base_url();?>public/front_css_js/images/logo_spinner.gif" class="logo">
                        </a>
                    </div>

                    <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> 
                            <span class="sr-only">Toggle navigation</span> 
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span> 
                    </button>
                    </div>
                    <div class="col-md-9 accordion-menu text-right">
                        <div class="navbar-collapse collapse">
                            <ul id="accordion" class="nav navbar-nav navbar-right panel-group">
                                <li>
                                    <a href="<?php echo base_url();?>">home</a>
                                </li>
                            <li><a href="#login-popup" class="popup-with-move-anim login-btn">Login</a></li>                    </ul>
                        </div>
                        <!--/.nav-collapse --> 
                    </div>
                </div>
                <div class="row clientheader muthoot-header">
                   
                    <div class="col-md-1 pull-left"><a href="#">
                        <img alt="" src="<?php echo base_url();?>public/front_css_js/images/giib/logo.jpg" class="logo-muthoot"></a>
                    </div>
                    <!-- end logo -->
                    <!-- <div class="col-md-3 pull-left text-center desktopview"><a href="#" class="padding-left-right-px"><img alt="" src="" class="border" /></a></div> -->
                    <div class="col-md-2 pull-right text-right desktopview">
                        <a href="#">
                            <!-- <img alt="" src="<?php //echo base_url();?>public/front_css_js/images/muthoot/logo_muthoot_group.png" class="logo-muthoot"> -->
                        </a>
                    </div>
                    <!-- toggle navigation -->
                    <div class="navbar-header col-sm-9 col-xs-2 pull-right">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> 
                            <span class="sr-only">Toggle navigation</span> 
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span> 
                        </button>
                    </div>
                    <!-- toggle navigation end -->
                    <!-- main menu -->
                        <div class="col-md-9 no-padding-right accordion-menu text-right">
                        <div class="navbar-collapse collapse">
                             <ul id="accordion" class="nav navbar-nav navbar-right panel-group">
                                 <li>
                                    <a href="<?php echo base_url();?>add-policy" class="inner-link">Application Form</a> 
                                </li>
                                <li class="dropdown panel simple-dropdown">
                                    <a href="<?php base_url();?>myaccount" class="" data-toggle="collapse" >My Account
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    
                                </li> 
                                  <li class="dropdown panel simple-dropdown">
                                    <a href="<?php base_url();?>cliam-policy" class="">Claim Request 
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                   
                                </li> 

                                <li><a href="<?php echo base_url();?>Home_Controller/logout">Logout</a></li>
                            </ul>
                            <!-- end main menu -->
                        </div>
                    </div>
                    <!-- end main menu -->
                </div>
            </div>
        </nav>
        <!-- end navigation panel -->
