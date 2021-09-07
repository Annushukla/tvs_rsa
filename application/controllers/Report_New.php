<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_New extends CI_Controller {

    public function __construct() {
        Parent::__construct();
        $this->load->model('Home_Model_New');
        $this->load->model('RSA_API_Model');
        $this->load->model('Home_Model');
        $this->load->helper('csv');
        // if (!empty($this->session->userdata('admin_session'))) {
        //     return true;
        // } else {
        //     redirect('admin');
        // }
    }

    public function mytvspolicies() {
        //print_r($this->session->userdata());exit;
        $data['main_contain'] = 'admin/report/mytvs_policies';
        $this->load->view('admin/includes/template', $data);
    }


    public function ViewtvspoliciesAjax() {
        $requestData = $_REQUEST;
        
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $where = '';
         // $where = 'pp.invoice_date' != 0;
        if (!empty($start_date) && !empty($end_date)) {
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        }else{
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) = CURDATE() " ;
        }
        // print_r($start_date) ;die('date');
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'sold_policy_date',
            1 => 'sold_policy_end_date',
            2 => 'engine_no',
            3 => 'chassis_no',
            4 => 'certificate_no',
        );
        $limit = ' LIMIT ' . $start . ', ' . $length . '';

        $policy_data = $this->Home_Model_New->getmytvspolicyDetail($where);
       
        // echo '<pre>';print_r($policy_data);die;
        $totalFiltered = $totalData;

        $totalFiltered = $policy_data['num_rows'];
        $data = array();
        $i = 1;
        foreach ($policy_data['result'] as $main) {
           
            
            $pa_ic='';
            $file_list = '<a href="' . base_url() . 'download_rsa_pdf/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>';
            switch ($main['ic_id']) {
                case 2:
                     $file_list = '<a href="' . base_url() . 'download_kotak_full_policy/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                    $pa_ic = 'Kotak';
                    break;
                case 5:
                     $file_list = '<a href="' . base_url() . 'download_icici_full_policy/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                $pa_ic = 'ICICI Lombard';
                    break;
                case 7:
                     $file_list = '<a href="' . base_url() . 'download_hdfc_policy/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                $pa_ic = 'HDFC';
                    break;
                case 8:
                     $file_list = '<a href="' . base_url() . 'download_reliance_policy/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                $pa_ic = 'Reliance';
                    break;                  
                case 9:
                     $file_list = '<a href="' . base_url() . 'download_tata_full_policy/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                $pa_ic = 'TATA AIG';
                    break;
                case 10:
                     $file_list = '<a href="' . base_url() . 'download_OICL_pdf/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                    $pa_ic = 'OICL';
                    break;
                case 12:
                     $file_list = '<a href="' . base_url() . 'download_bhartiaxa_full_policy/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                $pa_ic = 'Bharti AXA';
                    break;
                case 13:
                     $file_list = '<a href="' . base_url() . 'download_liberty_policy/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                $pa_ic = 'Liberty';
                    break;                  
               }
               if($main['plan_id']==62){
                    $main['basic_premium'] =0;
                    $main['gst_amount'] = 0;
               }
         
            $row = array();
            $row[] = $i++;
            $row[] = $main['sold_policy_no'];
            $row[] = $pa_ic;
            $row[] = $main['engine_no'];
            $row[] = $main['chassis_no'];
            $row[] = $main['model_name'];
            $row[] = $main['sap_ad_code'];
            $row[] = $main['dealer_name'];
            $row[] = $main['customer_name'];
            $row[] = $main['plan_name'];
            $row[] = $main['basic_premium'];
            $row[] = $main['gst_amount'];
            $main['total_premium'] = $main['basic_premium']+$main['gst_amount'];
            $row[] = $main['total_premium'];
            $row[] = $main['dealer_commission'];
            $row[] = $main['zone'];
            $row[] = $main['created_date'];
            $row[] = $main['sold_policy_effective_date'];
            $row[] = $main['sold_policy_end_date'];
            $row[] = $main['city_name'];          
            $row[] = $main['state_name'];          
            $row[] = $file_list.$cancel_policy.$endrose_button;

            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function limitless_assist_RR310_Bharti() {
        $data['main_contain'] = 'admin/report/limitless_assist_RR310_Bharti';
        $this->load->view('admin/includes/template', $data);
    }

    public function limitless_assist_RR310_Bharti_ajax() {
        $requestData = $_REQUEST;
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $where = '';
         // $where = 'pp.invoice_date' != 0;
        if (!empty($start_date) && !empty($end_date)) {
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        }else{
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) = CURDATE() " ;
        }
        // print_r($start_date) ;die('date');
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'sold_policy_date',
            1 => 'sold_policy_end_date',
            2 => 'engine_no',
            3 => 'chassis_no',
            4 => 'certificate_no',
        );
        $limit = ' LIMIT ' . $start . ', ' . $length . '';

        $policy_data = $this->Home_Model_New->getLimitlessAssistBhartipolicyDetail($where);
               
        // echo '<pre>';print_r($policy_data);die;
        $totalFiltered = $totalData;

        $totalFiltered = $policy_data['num_rows'];
        $data = array();
        $i = 1;
        foreach ($policy_data['result'] as $main) {
           
            $file_list = '<a href="' . base_url() . 'download_rsa_pdf/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>';
            $ic_name = ($main['rsa_ic_id']==1)?'Bharti Assist':'';
            $row = array();
            $row[] = $i++;
            $row[] = $main['sold_policy_no'];
            $row[] = $ic_name;
            $row[] = $main['engine_no'];
            $row[] = $main['vehicle_type'];
            $row[] = $main['chassis_no'];
            $row[] = $main['model_name'];
            $row[] = $main['sap_ad_code'];
            $row[] = $main['dealer_name'];
            $row[] = $main['customer_name'];
            $row[] = $main['plan_name'];

            $row[] = 0;
            $row[] = 0;
            $main['total_premium'] = $main['basic_premium']+$main['gst_amount'];
            $row[] = 0;
            $row[] = $main['dealer_commission'];
            $row[] = $main['zone'];
            $row[] = $main['created_date'];
            $row[] = $main['sold_policy_effective_date'];
            $row[] = $main['sold_policy_end_date'];
            $row[] = $main['city_name'];          
            $row[] = $main['state_name'];          
            $row[] = $file_list.$cancel_policy.$endrose_button;

            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function limitless_assist_RR310_Mytvs() {
        $data['main_contain'] = 'admin/report/limitless_assist_RR310_Mytvs';
        $this->load->view('admin/includes/template', $data);
    }

    public function limitless_assist_RR310_Mytvs_ajax() {
        $requestData = $_REQUEST;
        
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $where = '';
         // $where = 'pp.invoice_date' != 0;
        if (!empty($start_date) && !empty($end_date)) {
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        }else{
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) = CURDATE() " ;
        }
        // print_r($start_date) ;die('date');
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'sold_policy_date',
            1 => 'sold_policy_end_date',
            2 => 'engine_no',
            3 => 'chassis_no',
            4 => 'certificate_no',
        );
        $limit = ' LIMIT ' . $start . ', ' . $length . '';

        
        $policy_data = $this->Home_Model_New->getLimitlessAssistMyTvspolicyDetail($where);
        
        // echo '<pre>';print_r($policy_data);die;
        $totalFiltered = $totalData;

        $totalFiltered = $policy_data['num_rows'];
        $data = array();
        $i = 1;
        foreach ($policy_data['result'] as $main) {
          
             $file_list = '<a href="' . base_url() . 'download_rsa_pdf/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>';
            $ic_name = ($main['rsa_ic_id']==11)?'MYTVS':'';
            // echo $ic_name;die;
            $row = array();
            $row[] = $i++;
            $row[] = $main['sold_policy_no'];
            $row[] = $ic_name;
            $row[] = $main['engine_no'];
            $row[] = $main['chassis_no'];
            $row[] = $main['model_name'];
            $row[] = $main['sap_ad_code'];
            $row[] = $main['dealer_name'];
            $row[] = $main['customer_name'];
            $row[] = $main['plan_name'];
            $row[] = 0;
            $row[] = 0;
            $main['total_premium'] = $main['basic_premium']+$main['gst_amount'];
            $row[] = 0;
            $row[] = $main['dealer_commission'];
            $row[] = $main['zone'];
            $row[] = $main['created_date'];
            $row[] = $main['sold_policy_effective_date'];
            $row[] = $main['sold_policy_end_date'];
            $row[] = $main['city_name'];          
            $row[] = $main['state_name'];          
            $row[] = $file_list.$cancel_policy.$endrose_button;

            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function limitless_assistE_RR310_Bharti() {
        $data['main_contain'] = 'admin/report/limitless_assistE_RR310_Bharti';
        $this->load->view('admin/includes/template', $data);
    }

    public function limitless_assistE_RR310_Bharti_ajax() {
        $requestData = $_REQUEST;
        
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $where = '';
         // $where = 'pp.invoice_date' != 0;
        if (!empty($start_date) && !empty($end_date)) {
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        }else{
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) = CURDATE() " ;
        }
        // print_r($start_date) ;die('date');
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'sold_policy_date',
            1 => 'sold_policy_end_date',
            2 => 'engine_no',
            3 => 'chassis_no',
            4 => 'certificate_no',
        );
        $limit = ' LIMIT ' . $start . ', ' . $length . '';

        
        $policy_data = $this->Home_Model_New->getLimitlessAssistEBhartipolicyDetail($where);
       
        // echo '<pre>';print_r($policy_data);die;
        $totalFiltered = $totalData;

        $totalFiltered = $policy_data['num_rows'];
        $data = array();
        $i = 1;
        foreach ($policy_data['result'] as $main) {
           // echo '<pre>'; print_r($main);

            $file_list = '<a href="' . base_url() . 'download_rsa_pdf/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>';
            
            $ic_name = ($main['rsa_ic_id']==1)?'Bharti Assist':'';      
            $row = array();
            $row[] = $i++;
            $row[] = $main['sold_policy_no'];
            $row[] = $ic_name;
            $row[] = $main['engine_no'];
            $row[] = $main['vehicle_type'];
            $row[] = $main['chassis_no'];
            $row[] = $main['model_name'];
            $row[] = $main['sap_ad_code'];
            $row[] = $main['dealer_name'];
            $row[] = $main['customer_name'];
            $row[] = $main['plan_name'];

            $row[] = $main['basic_premium'];
            $row[] = $main['gst_amount'];
            $main['total_premium'] = $main['basic_premium']+$main['gst_amount'];
            $row[] = $main['total_premium'];
            $row[] = $main['dealer_commission'];
            $row[] = $main['zone'];
            $row[] = $main['created_date'];
            $row[] = $main['sold_policy_effective_date'];
            $row[] = $main['sold_policy_end_date'];
            $row[] = $main['city_name'];          
            $row[] = $main['state_name'];          
            $row[] = $file_list.$cancel_policy.$endrose_button;

            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function limitless_assistE_RR310_Mytvs() {
        $data['main_contain'] = 'admin/report/limitless_assistE_RR310_Mytvs';
        $this->load->view('admin/includes/template', $data);
    }

    public function limitless_assistE_RR310_Mytvs_ajax() {
        $requestData = $_REQUEST;
        
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $where = '';
         // $where = 'pp.invoice_date' != 0;
        if (!empty($start_date) && !empty($end_date)) {
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        }else{
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) = CURDATE() " ;
        }
        // print_r($start_date) ;die('date');
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'sold_policy_date',
            1 => 'sold_policy_end_date',
            2 => 'engine_no',
            3 => 'chassis_no',
            4 => 'certificate_no',
        );
        $limit = ' LIMIT ' . $start . ', ' . $length . '';

        
        $policy_data = $this->Home_Model_New->getLimitlessAssistEMyTvspolicyDetail($where);
        
       
        // echo '<pre>';print_r($policy_data);die;
        $totalFiltered = $totalData;

        $totalFiltered = $policy_data['num_rows'];
        $data = array();
        $i = 1;
        //echo '<pre>'; print_r($policy_data['result']);exit;
        foreach ($policy_data['result'] as $main) {

             $file_list = '<a href="' . base_url() . 'download_rsa_pdf/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>';
          
            $ic_name = ($main['rsa_ic_id']==11)?'MYTVS':'';      
            $row = array();
            $row[] = $i++;
            $row[] = $main['sold_policy_no'];
            $row[] = $ic_name;
            $row[] = $main['engine_no'];
            $row[] = $main['vehicle_type'];
            $row[] = $main['chassis_no'];
            $row[] = $main['model_name'];
            $row[] = $main['sap_ad_code'];
            $row[] = $main['dealer_name'];
            $row[] = $main['customer_name'];
            $row[] = $main['plan_name'];

            $row[] = $main['basic_premium'];
            $row[] = $main['gst_amount'];
            $main['total_premium'] = $main['basic_premium']+$main['gst_amount'];
            $row[] = $main['total_premium'];
            $row[] = $main['dealer_commission'];
            $row[] = $main['zone'];
            $row[] = $main['created_date'];
            $row[] = $main['sold_policy_effective_date'];
            $row[] = $main['sold_policy_end_date'];
            $row[] = $main['city_name'];          
            $row[] = $main['state_name'];          
            $row[] = $file_list.$cancel_policy.$endrose_button;

            $data[] = $row;
        }//exit;

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function limitless_assistrenew_RR310_Bharti() {
        $data['main_contain'] = 'admin/report/limitless_assistrenew_RR310_Bharti';
        $this->load->view('admin/includes/template', $data);
    }

    public function limitless_assistrenew_RR310_Bharti_ajax() {
        $requestData = $_REQUEST;
        
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $where = '';
         // $where = 'pp.invoice_date' != 0;
        if (!empty($start_date) && !empty($end_date)) {
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        }else{
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) = CURDATE() " ;
        }
        // print_r($start_date) ;die('date');
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'sold_policy_date',
            1 => 'sold_policy_end_date',
            2 => 'engine_no',
            3 => 'chassis_no',
            4 => 'certificate_no',
        );
        $limit = ' LIMIT ' . $start . ', ' . $length . '';

        
        $policy_data = $this->Home_Model_New->getLimitlessAssistRenewBhartipolicyDetail($where);
       
        // echo '<pre>';print_r($policy_data);die;
        $totalFiltered = $totalData;

        $totalFiltered = $policy_data['num_rows'];
        $data = array();
        $i = 1;
        //echo '<pre>'; print_r($policy_data['result']);exit;
        foreach ($policy_data['result'] as $main) {
            
             $file_list = '<a href="' . base_url() . 'download_rsa_pdf/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>';
            $ic_name = ($main['rsa_ic_id']==1)?'Bharti Assist':'';
            $row = array();
            $row[] = $i++;
            $row[] = $main['sold_policy_no'];
            $row[] = $ic_name;
            $row[] = $main['engine_no'];
            $row[] = $main['vehicle_type'];
            $row[] = $main['chassis_no'];
            $row[] = $main['model_name'];
            $row[] = $main['sap_ad_code'];
            $row[] = $main['dealer_name'];
            $row[] = $main['customer_name'];
            $row[] = $main['plan_name'];

            $row[] = $main['basic_premium'];
            $row[] = $main['gst_amount'];
            $main['total_premium'] = $main['basic_premium']+$main['gst_amount'];
            $row[] = $main['total_premium'];
            $row[] = $main['dealer_commission'];
            $row[] = $main['zone'];
            $row[] = $main['created_date'];
            $row[] = $main['sold_policy_effective_date'];
            $row[] = $main['sold_policy_end_date'];
            $row[] = $main['city_name'];          
            $row[] = $main['state_name'];          
            $row[] = $file_list.$cancel_policy.$endrose_button;

            $data[] = $row;
        }//exit;

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function limitless_assistrenew_RR310_Mytvs() {
        $data['main_contain'] = 'admin/report/limitless_assistrenew_RR310_Mytvs';
        $this->load->view('admin/includes/template', $data);
    }

    public function limitless_assistrenew_RR310_Mytvs_ajax() {
        $requestData = $_REQUEST;
        
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $where = '';
         // $where = 'pp.invoice_date' != 0;
        if (!empty($start_date) && !empty($end_date)) {
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        }else{
            $where = "AND (CAST(tsp.sold_policy_date AS DATE)) = CURDATE() " ;
        }
        // print_r($start_date) ;die('date');
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'sold_policy_date',
            1 => 'sold_policy_end_date',
            2 => 'engine_no',
            3 => 'chassis_no',
            4 => 'certificate_no',
        );
        $limit = ' LIMIT ' . $start . ', ' . $length . '';
        
        $policy_data = $this->Home_Model_New->getLimitlessAssistRenewMyTvspolicyDetail($where);
       
        // echo '<pre>';print_r($policy_data);die;
        $totalFiltered = $totalData;

        $totalFiltered = $policy_data['num_rows'];
        $data = array();
        $i = 1;
        //echo '<pre>'; print_r($policy_data['result']);exit;
        foreach ($policy_data['result'] as $main) {
            
             $file_list = '<a href="' . base_url() . 'download_rsa_pdf/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>';
            $ic_name = ($main['rsa_ic_id']==11)?'MYTVS':'';
            $row = array();
            $row[] = $i++;
            $row[] = $main['sold_policy_no'];
            $row[] = $ic_name;
            $row[] = $main['engine_no'];
            $row[] = $main['vehicle_type'];
            $row[] = $main['chassis_no'];
            $row[] = $main['model_name'];
            $row[] = $main['sap_ad_code'];
            $row[] = $main['dealer_name'];
            $row[] = $main['customer_name'];
            $row[] = $main['plan_name'];
            $row[] = $main['basic_premium'];
            $row[] = $main['gst_amount'];
            $main['total_premium'] = $main['basic_premium']+$main['gst_amount'];
            $row[] = $main['total_premium'];
            $row[] = $main['dealer_commission'];
            $row[] = $main['zone'];
            $row[] = $main['created_date'];
            $row[] = $main['sold_policy_effective_date'];
            $row[] = $main['sold_policy_end_date'];
            $row[] = $main['city_name'];          
            $row[] = $main['state_name'];          
            $row[] = $file_list.$cancel_policy.$endrose_button;

            $data[] = $row;
        }//exit;

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }


    function LimitlessDashboard(){
        $totallimitlessPolicies = $this->Home_Model_New->totallimitlessPolicies();
        // echo "<pre>"; print_r($totallimitlessPolicies); echo "</pre>"; die('end of line yoyo');
        $data['td_limitless_new']=$totallimitlessPolicies[0]['td_limitless_new'];
        $data['td_limitless_renew']=$totallimitlessPolicies[0]['td_limitless_renew'];
        $data['td_limitless_online']=$totallimitlessPolicies[0]['td_limitless_online'];
        $data['mtd_limitless_new']=$totallimitlessPolicies[0]['mtd_limitless_new'];
        $data['mtd_limitless_renew']=$totallimitlessPolicies[0]['mtd_limitless_renew'];
        $data['mtd_limitless_online']=$totallimitlessPolicies[0]['mtd_limitless_online'];
        $data['ytd_limitless_new']=$totallimitlessPolicies[0]['ytd_limitless_new'];
        $data['ytd_limitless_renew']=$totallimitlessPolicies[0]['ytd_limitless_renew'];
        $data['ytd_limitless_online']=$totallimitlessPolicies[0]['ytd_limitless_online'];
        $data['main_contain'] = 'admin/report/limitless_dashboard';
        $this->load->view('admin/includes/template', $data);
    }



}
?>