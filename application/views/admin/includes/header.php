<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Panel</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="<?php echo base_url() . ADMIN_CSS_JS; ?>bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url() . ADMIN_CSS_JS; ?>bootstrap/css/font-awesome.css">
        <link rel="stylesheet" href="<?php echo base_url() . ADMIN_CSS_JS; ?>dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="<?php echo base_url() . ADMIN_CSS_JS; ?>dist/css/skins/_all-skins.min.css">
         <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-datepicker.css">
         <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/select2.min.css">
         <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/select2-bootstrap.min.css">
         <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
         <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/multiselect.css">
        <script src="<?php echo base_url(); ?>assets/js/multiselect.min.js"></script>
        <script src="<?php echo base_url() . ADMIN_CSS_JS; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <script>var base_url = "<?php echo base_url(); ?>"</script>
        <style>
            .error{
                color : red;
            }
        </style>

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <header class="main-header">
                <!-- Logo -->
                <a href="<?php echo base_url(); ?>" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>A</b>LT</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>Admin</b></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">

                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <?php
                                    $user_image = isset($this->session->userdata('admin_session')['user_image']) ? $this->session->userdata('admin_session')['user_image'] : 'user_image.jpg';
                                    ?>
                                    <img src="<?php echo base_url() . 'uploads/users/' . $user_image; ?>" class="user-image" alt="User Image">
                                    <span class="hidden-xs"><?php echo $this->session->userdata('admin_session')['user_fullname']; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="<?php echo base_url() . 'uploads/users/' . $user_image; ?>" class="img-circle" alt="User Image">
                                        <p>
                                            <?php echo $this->session->userdata('admin_session')['user_fullname']; ?> - Admin
                                            <small>Member since Aug. 2018</small>
                                        </p>
                                    </li>
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="<?php echo base_url() . 'Rsa_admin/adminLogout'; ?>" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Control Sidebar Toggle Button -->
                            <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
