<!-- Left side column. contains the logo and sidebar -->
<?php
$admin_session = $this->session->userdata('admin_session');
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php
                $user_image = isset($admin_session['user_image']) ? $admin_session['user_image'] : 'user_image.jpg';
                ?>
                <img src="<?php echo base_url() . 'uploads/users/' . $user_image; ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $admin_session['user_fullname']; ?></p>

            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <?php if(!in_array($admin_session['admin_role'], array('limitless_policy_admin','service_admin','paid_service') ) )  { ?>
             <li class="treeview">
                <a href="<?php echo base_url('admin/admin_dashboard');?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
        <?php }?>

<?php if(!in_array($admin_session['admin_role'], array('dashboard_admin','limitless_policy_admin','paid_service') ) )  { ?>            
            <li class="treeview">
                <a href="<?php echo base_url() . 'admin/view_policies'; ?>">
                    <i class="fa fa-dashboard"></i> <span>Sold Policies</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
            <?php if($admin_session['admin_role'] != 'zone_code') { ?>
            <!-- <li class="treeview">
                <a href="<?php echo base_url('admin/endorsement_policy')?>">
                    <i class="fa fa-dashboard"></i> <span>Endorsements</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li> -->
        <?php }?>
            <?php if($admin_session['admin_role'] == 'kotak_admin' && $admin_session['ic_id'] == 2) { ?>
                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/view_policies_openrsa'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Sold Policies Open Rsa</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
                <li class=" treeview menu-open">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Feed File</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/feed-file/2'; ?>"><i class="fa fa-circle-o"></i>Active Kotak-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/kotak_openrsa_feedfile/4'; ?>"><i class="fa fa-circle-o"></i>Open rsa Kotak-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/pa_endorse_feedfile/2'; ?>"><i class="fa fa-circle-o"></i>Endorse-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/pa_canceled_feedfile/2'; ?>"><i class="fa fa-circle-o"></i>Cancelled-Feedfile</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/active_dealer'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Active Dealers</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
               
                 <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>All Reports</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/dealer_wise_reports'; ?>"><i class="fa fa-circle-o"></i> Dealer Wise Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/dealer_activity_report';?>"><i class="fa fa-circle-o"></i>Dealers Activity Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/dashboard_summary';?>"><i class="fa fa-circle-o"></i>Dashboard Summary</a></li>
                    </ul>
                </li>
            <?php } if($admin_session['admin_role_id'] == 5 && $admin_session['ic_id'] == 1) { ?>
                <li class=" treeview menu-open">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Feed File</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                       <li><a href="<?php echo base_url() . 'admin/bharti_feedfile/1'; ?>"><i class="fa fa-circle-o"></i>Active Bharti FeedFile</a></li>
                    </ul>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/bharti_endorse_feedfile/1'; ?>"><i class="fa fa-circle-o"></i>Endorse-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/bharti_canceled_feedfile/1'; ?>"><i class="fa fa-circle-o"></i>Cancelled-Feedfile</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/active_dealer'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Active Dealers</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
                 <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>All Reports</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/dealer_wise_reports'; ?>"><i class="fa fa-circle-o"></i> Dealer Wise Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/dashboard_summary';?>"><i class="fa fa-circle-o"></i>Dashboard Summary</a></li>
                    </ul>
                </li>
            
                <?php } if($admin_session['admin_role'] == 'bhartiaxa_admin' && $admin_session['ic_id'] == 12) { ?>
                <li class=" treeview menu-open">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Feed File</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/bharti_axa_feedfile/12'; ?>"><i class="fa fa-circle-o"></i>Active Bharti Axa-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/bhartiaxa_endorse_feedfile/12'; ?>"><i class="fa fa-circle-o"></i>Endorse-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/bhartiaxa_canceled_feedfile/12'; ?>"><i class="fa fa-circle-o"></i>Cancelled-Feedfile</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/active_dealer'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Active Dealers</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
               
                 <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>All Reports</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/dealer_wise_reports'; ?>"><i class="fa fa-circle-o"></i> Dealer Wise Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/dealer_activity_report';?>"><i class="fa fa-circle-o"></i>Dealers Activity Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/dashboard_summary';?>"><i class="fa fa-circle-o"></i>Dashboard Summary</a></li>
                    </ul>
                </li>
            <?php } if($admin_session['admin_role'] == 'tataaig_admin' && $admin_session['ic_id'] == 9) { ?>
                <li class=" treeview menu-open">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>TVSRSA-Feed File</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/tata_feedfile/9'; ?>"><i class="fa fa-circle-o"></i>Active TATA-Feedfile</a></li>
                    </ul>

                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/tata_endorse_feedfile/9'; ?>"><i class="fa fa-circle-o"></i>Endorse-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/tata_canceled_feedfile/9'; ?>"><i class="fa fa-circle-o"></i>Canceled-Feedfile</a></li>
                    </ul>
                    
                </li>
                <li class=" treeview menu-open">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Open-RSA Feed File</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/tata_opnrsa_feedfile/2'; ?>"><i class="fa fa-circle-o"></i> TATA-Open-Rsa</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/tata_opnrsa_endorse_feedfile/2'; ?>"><i class="fa fa-circle-o"></i> TATA-Open-Rsa-Endorse</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/tata_opnrsa_canceled_feedfile/2'; ?>"><i class="fa fa-circle-o"></i> TATA-Open-Rsa-Canceled</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/active_dealer'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Active Dealers</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
               
                 <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>All Reports</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/dealer_wise_reports'; ?>"><i class="fa fa-circle-o"></i> Dealer Wise Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/dealer_activity_report';?>"><i class="fa fa-circle-o"></i>Dealers Activity Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/dashboard_summary';?>"><i class="fa fa-circle-o"></i>Dashboard Summary</a></li>
                    </ul>
                </li>
            <?php } if($admin_session['admin_role_id'] == 7 && $admin_session['ic_id'] == 5) { ?>
                <li class=" treeview menu-open">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Feed File</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                       <li><a href="<?php echo base_url() . 'admin/icici_feedfile/5'; ?>"><i class="fa fa-circle-o"></i> ICICI FeedFile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/icici_endorse_feedfile/5'; ?>"><i class="fa fa-circle-o"></i>Endorse-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/icici_canceled_feedfile/5'; ?>"><i class="fa fa-circle-o"></i>Cancelled-Feedfile</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/active_dealer'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Active Dealers</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
                 <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>All Reports</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/dealer_wise_reports'; ?>"><i class="fa fa-circle-o"></i> Dealer Wise Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/dashboard_summary';?>"><i class="fa fa-circle-o"></i>Dashboard Summary</a></li>
                    </ul>
                </li>
            <?php } if($admin_session['admin_role_id'] == 7 && $admin_session['ic_id'] == 7) { ?>
                <li class=" treeview menu-open">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Feed File</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                       <li><a href="<?php echo base_url() . 'admin/hdfc_feedfile/7'; ?>"><i class="fa fa-circle-o"></i> HDFC FeedFile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/hdfc_endorse_feedfile/7'; ?>"><i class="fa fa-circle-o"></i>Endorse-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/hdfc_canceled_feedfile/7'; ?>"><i class="fa fa-circle-o"></i>Cancelled-Feedfile</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/active_dealer'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Active Dealers</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
                 <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>All Reports</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/dealer_wise_reports'; ?>"><i class="fa fa-circle-o"></i> Dealer Wise Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/dashboard_summary';?>"><i class="fa fa-circle-o"></i>Dashboard Summary</a></li>
                    </ul>
                </li>
            <?php } if($admin_session['admin_role'] == 'zone_code') { ?>
                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/dealer_master'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Active Dealers</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
                <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>All Reports</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/dealer_wise_reports'; ?>"><i class="fa fa-circle-o"></i> Dealer Wise Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/mig_reports'; ?>"><i class="fa fa-circle-o"></i> MIG Reports</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/dashboard_summary';?>"><i class="fa fa-circle-o"></i>Dashboard Summary</a></li>
                    </ul>
                </li>
                
            <?php } if($admin_session['admin_role_id'] == 6 && $admin_session['admin_role'] == 'tvs_admin') { ?>

                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/mytvs_policies'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Mytvs Policies</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>

               <!-- <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>Rr310 Policies</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php //echo base_url() . 'admin/dealer_wise_reports'; ?>"><i class="fa fa-circle-o"></i> Dealer Wise Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                    <li><a href="<?php //echo base_url().'admin/dashboard_summary';?>"><i class="fa fa-circle-o"></i>Dashboard Summary</a></li>
                    </ul>
                </li>-->

                <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>RR310 Policies</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/limitless_assist_RR310_Bharti'; ?>"><i class="fa fa-circle-o"></i>Limitless assist New RR310 Bharti</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/limitless_assist_RR310_Mytvs';?>"><i class="fa fa-circle-o"></i>Limitless assist New RR310 My Tvs</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/limitless_assistE_RR310_Bharti'; ?>"><i class="fa fa-circle-o"></i>Limitless assist Renew RR310 Bharti</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/limitless_assistE_RR310_Mytvs';?>"><i class="fa fa-circle-o"></i>Limitless assist Renew RR310 My Tvs</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/limitless_assistE_RR310_Bharti'; ?>"><i class="fa fa-circle-o"></i>Limitless assist Online RR310 Bharti</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/limitless_assistE_RR310_Mytvs';?>"><i class="fa fa-circle-o"></i>Limitless assist Online RR310 My Tvs</a></li>
                    </ul>
                </li>
               
                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/dealer_master'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Active Dealers</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
                <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>All Reports</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/dealer_wise_reports'; ?>"><i class="fa fa-circle-o"></i> Dealer Wise Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/mig_reports'; ?>"><i class="fa fa-circle-o"></i> MIG Reports</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/dashboard_summary';?>"><i class="fa fa-circle-o"></i>Dashboard Summary</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>RR310 Policy</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/rr310_new_policies'; ?>"><i class="fa fa-circle-o"></i> New</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/rr310_renew_policies'; ?>"><i class="fa fa-circle-o"></i>Renew</a></li>
                    </ul>
                    
                </li>
                <li class=" treeview menu-open">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Dealers Info</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/logged_in_dealer'; ?>"><i class="fa fa-circle-o"></i> Todays Logged In Dealers</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/dealer_master'; ?>"><i class="fa fa-circle-o"></i> Active Dealers</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/inactive_dealers';?>"><i class="fa fa-circle-o"></i>InActive Dealers</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/dealer_activity_report';?>"><i class="fa fa-circle-o"></i>Dealer's Activity Report</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/last_week_sold_policies';?>"><i class="fa fa-circle-o"></i>Last Week Sold Policies</a></li>
                </ul>                
                
            </li>

            <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>Other Reports</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                        <li>
                            <a href="<?php echo base_url() . 'admin/paid_service'; ?>">
                                <i class="fa fa-circle-o"></i><span>Paid Service Policies</span> 
                            </a>
                        </li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li>

                            <a href="<?php echo base_url() . 'admin/flash_report'; ?>">
                                <i class="fa fa-circle-o"></i><span>Flash Report</span> </a>
                            <a href="<?php echo base_url() . 'admin/tvsdashboard_report'; ?>">
                                <i class="fa fa-circle-o"></i><span>TVS Dashboard</span> </a>
                            <a href="<?php echo base_url() . 'admin/pending_renewal_policies'; ?>">
                                <i class="fa fa-circle-o"></i><span>Pending Renewal Policies</span> 
                            </a>
                            <a href="<?php echo base_url() . 'admin/consolidated_report'; ?>">
                                <i class="fa fa-circle-o"></i><span>Consolidated Report</span>
                            </a>
                        </li>
                    </ul>
                    
            </li>

            <?php } if($admin_session['admin_role_id'] == 8 && $admin_session['admin_role'] == 'account_admin') { ?>
             <li class="treeview">
                <a href="">
                    <i class="fa fa-dashboard"></i> <span>Party Payment Details</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                 <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/party_payment_details'; ?>"><i class="fa fa-circle-o"></i>Party Payment Details</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="">
                    <i class="fa fa-dashboard"></i> <span>Dealer Payment</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                 <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/pending_dealer_payment'; ?>"><i class="fa fa-circle-o"></i>Pending</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/approved_dealer_payment'; ?>"><i class="fa fa-circle-o"></i>Approved</a></li>
                </ul>
            </li>

             <?php } if($admin_session['admin_role'] == 'oriental_admin' && $admin_session['ic_id'] == 10) { ?>
                <li class=" treeview menu-open">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Feed File</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                       <li><a href="<?php echo base_url() . 'admin/cover_oriental_feedfile/10'; ?>"><i class="fa fa-circle-o"></i> Cover-Oriental FeedFile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                       <li><a href="<?php echo base_url() . 'admin/level_oriental_feedfile/10'; ?>"><i class="fa fa-circle-o"></i> Level-Oriental FeedFile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <!-- <li><a href="<?php echo base_url() . 'admin/oriental_endorse_feedfile/10'; ?>"><i class="fa fa-circle-o"></i>Endorse-Feedfile</a></li> -->
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/oriental_canceled_feedfile/10'; ?>"><i class="fa fa-circle-o"></i>Cancelled-Feedfile</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>Party Payment Details</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/party_payment_details'; ?>"><i class="fa fa-circle-o"></i>Party Payment Details</a></li>
                    </ul>
                </li>
            

            <?php } if($admin_session['admin_role'] == 'liberty_admin' && $admin_session['ic_id'] == 13) { ?>
                <li class=" treeview menu-open">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Feed File</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/liberty_general_feedfile/13'; ?>"><i class="fa fa-circle-o"></i> Liberty General-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                         <li><a href="<?php echo base_url() . 'admin/liberty_general_endorsed_feedfile/13'; ?>"><i class="fa fa-circle-o"></i> Endorsed-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                         <li><a href="<?php echo base_url() . 'admin/liberty_general_canceled_feedfile/13'; ?>"><i class="fa fa-circle-o"></i> Cancelled-Feedfile</a></li>
                    </ul>
                </li>
            <?php } if($admin_session['admin_role'] == 'reliance_general_admin' && $admin_session['ic_id'] == 8) { ?>
                <li class=" treeview menu-open">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Feed File</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/reliance_general_feedfile/8'; ?>"><i class="fa fa-circle-o"></i> Reliance General-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                         <li><a href="<?php echo base_url() . 'admin/reliance_general_endorsed_feedfile/8'; ?>"><i class="fa fa-circle-o"></i> Endorsed-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                         <li><a href="<?php echo base_url() . 'admin/reliance_general_canceled_feedfile/8'; ?>"><i class="fa fa-circle-o"></i> Cancelled-Feedfile</a></li>
                    </ul>

                </li>

            <?php } if($admin_session['admin_role'] == 'mytvs_admin' && $admin_session['ic_id'] == 11) { ?>
                <li class=" treeview menu-open">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Feed File</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                       <li><a href="<?php echo base_url() . 'admin/MYTVS_feed_file/11'; ?>"><i class="fa fa-circle-o"></i>MY TVS FeedFile</a></li>
                    </ul>
                      <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/mytvs_endorse_feedfile/11'; ?>"><i class="fa fa-circle-o"></i>Endorse-Feedfile</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/mytvs_canceled_feedfile/11'; ?>"><i class="fa fa-circle-o"></i>Cancelled-Feedfile</a></li>
                    </ul>
                   
                </li>

            <?php } if($admin_session['admin_role'] == 'rm_admin' ) { ?>
                <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>All Reports</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/RMActiveDealers'; ?>"><i class="fa fa-circle-o"></i> Active Dealers</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/RMInactiveDealers';?>"><i class="fa fa-circle-o"></i>In Active Dealers</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/rm_dealer_activity_report';?>"><i class="fa fa-circle-o"></i>Dealer's Activity Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/rm_last_week_sold_policies';?>"><i class="fa fa-circle-o"></i>Last Week Sold Policies</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/rm_last_policy_date';?>"><i class="fa fa-circle-o"></i>Last Sold Policy Date</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/rm_dealer_wise_reports';?>"><i class="fa fa-circle-o"></i>Dealer Wise Report</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/rm_DealerIC_mapped';?>"><i class="fa fa-circle-o"></i>Dealer IC Mapped</a></li>
                    </ul>

                  
                </li>

            <?php }  if($admin_session['admin_role'] == 'admin_master' || $admin_session['admin_role'] == 'opreation_admin'){ ?>
            <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/mytvs_policies'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Mytvs Policies</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
            </li>

            <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>RR310 Policies</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/limitless_assist_RR310_Bharti'; ?>"><i class="fa fa-circle-o"></i>RR310 New Bharti</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/limitless_assist_RR310_Mytvs';?>"><i class="fa fa-circle-o"></i>RR310 New My Tvs</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/limitless_assistrenew_RR310_Bharti'; ?>"><i class="fa fa-circle-o"></i>RR310 Renew Bharti</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/limitless_assistrenew_RR310_Mytvs';?>"><i class="fa fa-circle-o"></i>RR310 Renew My Tvs</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/limitless_assistE_RR310_Bharti'; ?>"><i class="fa fa-circle-o"></i>RR310 Online Bharti</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/limitless_assistE_RR310_Mytvs';?>"><i class="fa fa-circle-o"></i>RR310 Online My Tvs</a></li>
                    </ul>
            </li>
            
            <li class="treeview">
                <a href="">
                    <i class="fa fa-dashboard"></i> <span>RR310 Import</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                 <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/RR310Import'; ?>"><i class="fa fa-circle-o"></i>RR310 Import</a></li>
                    <li><a href="<?php echo base_url() . 'admin/rr310_generate_policy_script'; ?>"><i class="fa fa-circle-o"></i>RR310 Generate Policy</a></li>
                </ul>
            </li>
            <li class=" treeview menu-open">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Feed File</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/feed_file/2'; ?>"><i class="fa fa-circle-o"></i> Kotak FeedFile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/pa_canceled_feedfile/2'; ?>"><i class="fa fa-circle-o"></i>Cancelled Kotak-Feedfile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/icici_feedfile/5'; ?>"><i class="fa fa-circle-o"></i> ICICI FeedFile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/icici_canceled_feedfile/5'; ?>"><i class="fa fa-circle-o"></i>Cancelled ICICI-Feedfile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/bharti_feedfile/1'; ?>"><i class="fa fa-circle-o"></i>Bharti FeedFile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/bharti_canceled_feedfile/1'; ?>"><i class="fa fa-circle-o"></i>Cancelled Bharti-Feedfile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/MYTVS_feed_file/11'; ?>"><i class="fa fa-circle-o"></i>MY TVS FeedFile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/mytvs_canceled_feedfile/11'; ?>"><i class="fa fa-circle-o"></i>Cancelled Mytvs-Feedfile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/bharti_axa_feedfile/12'; ?>"><i class="fa fa-circle-o"></i>Active Bharti Axa-Feedfile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                   <li><a href="<?php echo base_url() . 'admin/bhartiaxa_canceled_feedfile/12'; ?>"><i class="fa fa-circle-o"></i>Cancelled Bharti Axa-Feedfile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/tata_feedfile/9'; ?>"><i class="fa fa-circle-o"></i>Active TATA-Feedfile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/tata_canceled_feedfile/9'; ?>"><i class="fa fa-circle-o"></i>Canceled TATA-Feedfile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                   <li><a href="<?php echo base_url() . 'admin/cover_oriental_feedfile/10'; ?>"><i class="fa fa-circle-o"></i> Cover-Oriental FeedFile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                   <li><a href="<?php echo base_url() . 'admin/level_oriental_feedfile/10'; ?>"><i class="fa fa-circle-o"></i> Level-Oriental FeedFile</a></li>
                </ul>

                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/oriental_canceled_feedfile/10'; ?>"><i class="fa fa-circle-o"></i>Cancelled Oriental-Feedfile</a></li>
                </ul>

                <ul class="treeview-menu" style="">
                   <li><a href="<?php echo base_url() . 'admin/liberty_general_feedfile/13'; ?>"><i class="fa fa-circle-o"></i> Liberty General-Feedfile</a></li>
                </ul>

                <ul class="treeview-menu" style="">
                  <li><a href="<?php echo base_url() . 'admin/liberty_general_canceled_feedfile/13'; ?>"><i class="fa fa-circle-o"></i> Cancelled Liberty General-Feedfile</a></li>
                </ul>

                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/reliance_general_feedfile/8'; ?>"><i class="fa fa-circle-o"></i> Reliance General-Feedfile</a></li>
                </ul>

                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/reliance_general_canceled_feedfile/8'; ?>"><i class="fa fa-circle-o"></i> Cancelled Reliance General-Feedfile</a></li>
                </ul>


                <ul class="treeview-menu" style="">
                       <li><a href="<?php echo base_url() . 'admin/hdfc_feedfile/7'; ?>"><i class="fa fa-circle-o"></i> HDFC FeedFile</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/hdfc_canceled_feedfile/7'; ?>"><i class="fa fa-circle-o"></i>Cancelled HDFC-Feedfile</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="<?php echo base_url('admin/model_list');?>">
                    <i class="fa fa-dashboard"></i> <span>Add Model</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
            <li class="treeview">
                <a href="<?php echo base_url('admin/fetchByPolicyNo');?>">
                    <i class="fa fa-dashboard"></i> <span>Fetch Policy Data </span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
            <li class="treeview">
                <a href="<?php echo base_url('admin/pincode_list');?>">
                    <i class="fa fa-dashboard"></i> <span>Add Pincode</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
            <li class="treeview">
                <a href="">
                    <i class="fa fa-dashboard"></i> <span>Party Payment Details</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                 <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/party_payment_details'; ?>"><i class="fa fa-circle-o"></i>Party Payment Details</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="">
                    <i class="fa fa-dashboard"></i> <span>All Reports</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                 <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/dealer_wise_reports'; ?>"><i class="fa fa-circle-o"></i> Dealer Wise Report</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/mig_reports'; ?>"><i class="fa fa-circle-o"></i> MIG Reports</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                <li><a href="<?php echo base_url().'admin/dashboard_summary';?>"><i class="fa fa-circle-o"></i>Dashboard Summary</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/target_achivement';?>"><i class="fa fa-circle-o"></i>Layer One</a></li>
                </ul> 
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/layer_two';?>"><i class="fa fa-circle-o"></i>Layer Two</a></li>
                </ul> 
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/payable_dashboard';?>"><i class="fa fa-circle-o"></i>Payable Dashboard</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/receivable_dashboard';?>"><i class="fa fa-circle-o"></i>Receivable Dashboard</a></li>
                </ul>                
             
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/dealer_rsa_payment';?>"><i class="fa fa-circle-o"></i>RSA Payment</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/policy_detail';?>"><i class="fa fa-circle-o"></i>Layer3-Policy Detail</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/last_policy_date';?>"><i class="fa fa-circle-o"></i>Last Sold Policy Date</a></li>
                </ul>
            </li>
            <li class=" treeview menu-open">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Dealers Info</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/logged_in_dealer'; ?>"><i class="fa fa-circle-o"></i> Todays Logged In Dealers</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url() . 'admin/dealer_master'; ?>"><i class="fa fa-circle-o"></i> Active Dealers</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/inactive_dealers';?>"><i class="fa fa-circle-o"></i>In Active Dealers</a></li>
                </ul>
                 <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/dealer_uploaded_docs';?>"><i class="fa fa-circle-o"></i>Uploaded Documents</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/document_not_uploaded';?>"><i class="fa fa-circle-o"></i>Document Not Uploaded</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/dealer_activity_report';?>"><i class="fa fa-circle-o"></i>Dealer's Activity Report</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/last_week_sold_policies';?>"><i class="fa fa-circle-o"></i>Last Week Sold Policies</a></li>
                </ul>
                <ul class="treeview-menu" style="">
                    <li><a href="<?php echo base_url().'admin/dealerCmpaignList';?>"><i class="fa fa-circle-o"></i>Download Dealer Campaign List</a></li>
                </ul>
                
            </li>
                
                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/cancel_policies'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Cancel Policies</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/admin_dealer_approve'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Admin Approval</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/invoice_approval'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Invoice Approval</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
                <li class="treeview">
                    <a href="<?php echo base_url() . 'admin/gst_approval'; ?>">
                        <i class="fa fa-dashboard"></i> <span>Approve GST</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
                <li class="treeview">
                <a href="<?php echo base_url() . 'admin/send_sms'; ?>">
                    <i class="fa fa-dashboard"></i> <span>Send SMS</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                </li>
                <li class="treeview">
                <a href="<?php echo base_url() . 'admin/oreiental_policies_upload'; ?>">
                    <i class="fa fa-dashboard"></i> <span>Oriental Policies Upload</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                </li>
                <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>Oriental Special Report</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/orientalReports/10'; ?>"><i class="fa fa-circle-o"></i>Oriental Special Report</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>Other Reports</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/SapphirePolicytill30Aug'; ?>"><i class="fa fa-circle-o"></i>Saphhire till 30 Aug</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/SapphirePolicytill30AugDownloaded'; ?>"><i class="fa fa-circle-o"></i>Downloaded Saphhire till 30 Aug</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/serial_no_of_apology_letter'; ?>"><i class="fa fa-circle-o"></i>Serial No. Saphhire till 30 Aug</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li>
                            <a href="<?php echo base_url() . 'admin/wrong_punched_policies'; ?>">
                                <i class="fa fa-circle-o"></i>Wrong Punched Policies 
                            </a>
                        </li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li>
                            <a href="<?php echo base_url() . 'admin/Workshop_frameno'; ?>">
                                <i class="fa fa-circle-o"></i>Upload Workshop Frame Nos. 
                            </a>
                        </li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li>
                            <a href="<?php echo base_url() . 'admin/paid_service'; ?>">
                                <i class="fa fa-circle-o"></i><span>Paid Service Policies</span> 
                            </a>
                        </li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li>

                            <a href="<?php echo base_url() . 'admin/flash_report'; ?>">
                                <i class="fa fa-circle-o"></i><span>Flash Report</span>
                            </a> 
                            <a href="<?php echo base_url() . 'admin/tvsdashboard_report'; ?>">
                                <i class="fa fa-circle-o"></i><span>TVS Dashboard</span> 
                            </a>
                            <a href="<?php echo base_url() . 'admin/pending_renewal_policies'; ?>">
                                <i class="fa fa-circle-o"></i><span>Pending Renewal Policies</span>
                            </a>
                            <a href="<?php echo base_url() . 'admin/todays_renewal_policies'; ?>">
                                <i class="fa fa-circle-o"></i><span>Today's Renewal Policies</span>
                            </a> 
                            <a href="<?php echo base_url() . 'admin/renewed_policy_report'; ?>">
                                <i class="fa fa-circle-o"></i><span>Online Renewed Policies Report</span>
                            </a> 
                            <a href="<?php echo base_url() . 'admin/consolidated_report'; ?>">
                                <i class="fa fa-circle-o"></i><span>Consolidated Report</span>
                            </a>

                            
                        </li>
                    </ul>
                    
                    
                </li>

                <li class=" treeview menu-open">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>RM</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/rm_master'; ?>"><i class="fa fa-circle-o"></i>RM LIST</a></li>
                    </ul>

                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/import_rm'; ?>"><i class="fa fa-circle-o"></i>RM Dealer Mapping Import</a></li>
                    </ul>
                </li>

                


<?php } }?>
<?php if($admin_session['admin_role'] == 'limitless_policy_admin'){?>
            <li class="treeview">
                <a href="<?php echo base_url('admin/limitless_dashboard');?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
                <li class="treeview">
                    <a href="">
                        <i class="fa fa-dashboard"></i> <span>RR310 Policies</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                     <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/limitless_assist_RR310_Bharti'; ?>"><i class="fa fa-circle-o"></i>RR310 New Bharti</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/limitless_assist_RR310_Mytvs';?>"><i class="fa fa-circle-o"></i>RR310 New My Tvs</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/limitless_assistrenew_RR310_Bharti'; ?>"><i class="fa fa-circle-o"></i>RR310 Renew Bharti</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/limitless_assistrenew_RR310_Mytvs';?>"><i class="fa fa-circle-o"></i>RR310 Renew My Tvs</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url() . 'admin/limitless_assistE_RR310_Bharti'; ?>"><i class="fa fa-circle-o"></i>RR310 Online Bharti</a></li>
                    </ul>
                    <ul class="treeview-menu" style="">
                        <li><a href="<?php echo base_url().'admin/limitless_assistE_RR310_Mytvs';?>"><i class="fa fa-circle-o"></i>RR310 Online My Tvs</a></li>
                    </ul>
                </li>
<?php }?>
<?php if($admin_session['admin_role'] == 'oriental_admin2'){?>
<li class=" treeview menu-open">
    <a href="#">
        <i class="fa fa-dashboard"></i> <span>Feed File</span>
        <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu" style="">
       <li><a href="<?php echo base_url() . 'admin/cover_oriental_feedfile/10'; ?>"><i class="fa fa-circle-o"></i> Cover-Oriental FeedFile</a></li>
    </ul>
    <ul class="treeview-menu" style="">
       <li><a href="<?php echo base_url() . 'admin/level_oriental_feedfile/10'; ?>"><i class="fa fa-circle-o"></i> Level-Oriental FeedFile</a></li>
    </ul>
    <ul class="treeview-menu" style="">
        <!-- <li><a href="<?php echo base_url() . 'admin/oriental_endorse_feedfile/10'; ?>"><i class="fa fa-circle-o"></i>Endorse-Feedfile</a></li> -->
    </ul>
    <ul class="treeview-menu" style="">
        <li><a href="<?php echo base_url() . 'admin/oriental_canceled_feedfile/10'; ?>"><i class="fa fa-circle-o"></i>Cancelled-Feedfile</a></li>
    </ul>
</li>
<li class="treeview">
    <a href="">
        <i class="fa fa-dashboard"></i> <span>Party Payment Details</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
     <ul class="treeview-menu" style="">
        <li><a href="<?php echo base_url() . 'admin/party_payment_details'; ?>"><i class="fa fa-circle-o"></i>Party Payment Details</a></li>
    </ul>
</li>
<li class="treeview">
    <a href="<?php echo base_url() . 'admin/oreiental_policies_upload'; ?>">
        <i class="fa fa-dashboard"></i> <span>Oriental Policies Upload</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
</li>
<li class="treeview">
    <a href="">
        <i class="fa fa-dashboard"></i> <span>Oriental Special Report</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
     <ul class="treeview-menu" style="">
        <li><a href="<?php echo base_url() . 'admin/orientalReports/10'; ?>"><i class="fa fa-circle-o"></i>Oriental Special Report</a></li>
    </ul>
</li>
<?php } if($admin_session['admin_role'] == 'service_admin'){?>
    <li class="treeview">
        <a href="<?php echo base_url() . 'admin/cancel_policies'; ?>">
            <i class="fa fa-dashboard"></i> <span>Cancel Policies</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
    </li>
<?php }?>
<?php if($admin_session['admin_role'] == 'paid_service'){?>
    <li class="treeview">
        <a href="<?php echo base_url() . 'admin/paid_service'; ?>">
            <i class="fa fa-dashboard"></i> <span>Paid Service Policies</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
    </li>
<?php }?>

        </ul>
    </section>
</aside>

