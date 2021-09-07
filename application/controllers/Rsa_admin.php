<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Rsa_admin extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->library('database_library');
        $this->load->model('Home_Model');
        $this->load->helper('common_helper');
        // isUserLoggedIn();
        /*       $this ->checkLogin(); */
    }

    public function index() {
        $this->load->view('admin/login/admin');
    }

    public function submitAdminLogin() {
        if ($this->database_library->postAdminLogin($this->input->post('email'), $this->input->post('password'))) {
                $admin_session = $this->session->userdata('admin_session');
                $role = $admin_session['admin_role'];
                switch ($role) {
                    case 'rm_admin':
                        redirect(base_url() . 'admin/rm_dashboard');
                        break;
                    case 'limitless_policy_admin':
                        redirect(base_url() . 'admin/limitless_dashboard');
                        break;
                    case 'service_admin':
                        redirect(base_url() . 'admin/view_policies');
                        break;
                    case 'paid_service':
                       redirect(base_url() . 'admin/paid_service');
                        break;

                    default:
                        redirect(base_url() . 'admin/admin_dashboard');
                        break;
                }
                
            
        } else {
            redirect(base_url('admin'));
        }
    }

    public function adminLogout() {
        $this->session->unset_userdata('pa_user_session');
        $this->session->unset_userdata('policy_data');
        $this->session->unset_userdata('pa_customer_id');
        $this->session->unset_userdata('admin_session');
        $this->session->unset_userdata('admin_role');
        $this->session->unset_userdata('admin_role_id');
        redirect(base_url('admin'));
    }

}
