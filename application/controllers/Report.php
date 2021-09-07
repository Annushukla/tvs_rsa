<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

    public function __construct() {
        Parent::__construct();
        $this->load->model('Home_Model');
        $this->load->library('database_library');
        $this->load->helper('csv');
        if (!empty($this->session->userdata('admin_session'))) {
            return true;
        } else {
            redirect('admin');
        }
    }

   

      public function adminDashboard_21_09_2019(){
            //todays Total policies
        $ic_id = $this->session->userdata('admin_session')['ic_id'];
        $ic_id = $this->session->userdata('admin_session')['ic_id'];
        $dashboard_summary = $this->Home_Model->getDashboardSummary($ic_id);
        $final_dashboard = array();
        foreach ($dashboard_summary as $dash_summary) {
              $final_dashboard[$dash_summary['zone']][] = $dash_summary;
            }
        // $test_sum = array_sum(array_column($dashboard_summary, 'last_month_kotak_policies'));
        // echo '<pre>'; print_r($test_sum);    die('hello moto');
        $data['dashboard_summary'] = $dashboard_summary;
        $data['final_dashboard'] = $final_dashboard;
        $all_policy_details = $this->Home_Model->totalSoldPolicies();
        $data['last_3_months_policies'] = $this->Home_Model->last3MonthsPolicies();
         // echo '<pre>'; print_r($all_policy_details);die('policiie');
        
        $data['todays_policies'] = 0;
        $data['todays_policies'] += $all_policy_details[0]['todays_policies'];
        $data['todays_policies'] += $all_policy_details[2]['todays_policies'];
        $data['todays_policies'] += $all_policy_details[3]['todays_policies'];
        $data['todays_policies'] += $all_policy_details[4]['todays_policies'];
        $data['todays_policies'] += $all_policy_details[5]['todays_policies'];

        $data['total_policies'] = 0;
        $data['total_policies'] += $all_policy_details[0]['total_policies'];
        $data['total_policies'] += $all_policy_details[2]['total_policies'];
        $data['total_policies'] += $all_policy_details[3]['total_policies'];
        $data['total_policies'] += $all_policy_details[4]['total_policies'];
        $data['total_policies'] += $all_policy_details[5]['total_policies'];

        $data['todays_icici_policies'] = 0;
        $data['todays_icici_policies'] += $all_policy_details[2]['todays_policies'];

        $data['total_icici_policies'] = 0;
        $data['total_icici_policies'] += $all_policy_details[2]['total_policies'];

        $data['todays_kotak_policies'] = 0;
        $data['todays_kotak_policies'] += $all_policy_details[0]['todays_policies'];
        //Total kotak policies

        $data['total_kotak_policies'] = 0;
        $data['total_kotak_policies'] += $all_policy_details[0]['total_policies'];
         //Total Bharti Rsa policies
        
        $data['total_bharti_axa_policies'] = 0;
        $data['total_bharti_axa_policies'] += $all_policy_details[3]['total_policies']; 

        $data['todays_bharti_axa_policies'] = 0;
        $data['todays_bharti_axa_policies'] += $all_policy_details[3]['todays_policies']; 


        /**********Tata AIG Policies******************/

        $data['total_tata_aig_policies'] = 0;
        $data['total_tata_aig_policies'] += $all_policy_details[4]['total_policies']; 

        $data['todays_tata_aig_policies'] = 0;
        $data['todays_tata_aig_policies'] += $all_policy_details[4]['todays_policies']; 

        /**********Tata AIG Policies******************/


        $data['total_bharti_policies'] = 0;
        $data['total_bharti_policies'] += $all_policy_details[1]['total_policies'];
         //Todays Bharti Rsa policies


        $data['todays_bharti_policies'] = 0;
        $data['todays_bharti_policies'] += $all_policy_details[1]['todays_policies'];

     /**********Oriental******************/   
        $data['total_oriental_policies'] = 0;
        $data['total_oriental_policies'] += $all_policy_details[5]['total_policies'];
         //Todays oriental Rsa policies

        $data['todays_oriental_policies'] = 0;
        $data['todays_oriental_policies'] += $all_policy_details[5]['todays_policies'];

        $data['total_mytvs_policies'] = 0;
        
        $data['todays_mytvs_policies'] = 0;
        //Total Wallet Balance
        $data['total_wallet_balance'] = $this->Home_Model->getTotalWalletBalance();
        
       //Todays Total Logged In Dealers
        $data['total_dealers_logged_in'] = $this->Home_Model->CountDealerLoggedIn();
// echo '<pre>'; print_r($data);die('data');

        $data['main_contain'] = 'admin/report/admin_template';
        $this->load->view('admin/includes/template', $data);
    }

public function adminDashboard(){
            //todays Total policies
        $admin_session = $this->session->userdata('admin_session');
        $ic_id = $admin_session['ic_id'];
        
        if(in_array($admin_session['admin_role'],array('opreation_admin','admin_master','tvs_admin','account_admin','dashboard_admin')) && in_array($admin_session['admin_role_id'], array(2,6,1,8,11) ) ){
            $dashboard_summary = $this->Home_Model->getDashboardSummary($ic_id);
            $final_dashboard = array();
            foreach ($dashboard_summary as $dash_summary) {
                  $final_dashboard[$dash_summary['zone']][] = $dash_summary;
                }
            $data['dashboard_summary'] = $dashboard_summary;
            $data['final_dashboard'] = $final_dashboard;
        }

        
        $all_policy_details = $this->Home_Model->totalSoldPolicies();
        $data['last_3_months_policies'] = $this->Home_Model->last3MonthsPolicies();
       // echo '<pre>';print_r($data['last_3_months_policies']);exit;
       // echo '<pre>'; print_r($all_policy_details);die('policiie');

        foreach ($all_policy_details as $value) {
           
            switch($value['ic_id']){
                case 1:
                     // *****BHATI ASSIST RSA****
                    $data['todays_bharti_policies'] = $value['todays_policies'];
                    $data['total_bharti_policies'] = $value['total_policies'];
                    $data['td_RR310_new_bharti']=$value['td_limitless_assist_RR310_policies'];
                    $data['td_RR310_renew_bharti']=$value['td_limitless_assist_RR310renew_policies'];
                    $data['td_RR310_online_bharti']=$value['td_limitless_assistE_RR310_policies'];
                    $data['RR310_new_bharti']=$value['limitless_assist_RR310_policies'];
                    $data['RR310_renew_bharti']=$value['td_limitless_assist_RR310renew_policies'];
                    $data['RR310_online_bharti']=$value['limitless_assistE_RR310_policies'];
                    break;
                case 2:
                    // **KOTAK**
                    $data['todays_kotak_policies'] = $value['todays_policies'];
                    $data['total_kotak_policies'] = $value['total_policies'];
                    break;
                case 5:
                    // **ICICI**
                    $data['todays_icici_policies'] = $value['todays_policies'];
                    $data['total_icici_policies'] = $value['total_policies'];
                    break;
                case 7:
                    // **HDFC**
                    $data['todays_hdfc_policies'] = $value['todays_policies'];
                    $data['total_hdfc_policies'] = $value['total_policies'];
                    break;
                case 8:
                    // **RELIANCE***
                    $data['todays_reliance_policies'] = $value['todays_policies'];
                    $data['total_reliance_policies'] = $value['total_policies'];
                    break;
                case 9:
                     // **TATA AIG**
                    $data['todays_tata_aig_policies'] = $value['todays_policies']; 
                    $data['total_tata_aig_policies'] = $value['total_policies'];
                    break;
                case 10:
                     // **OICL**
                    $data['todays_oriental_policies'] = $value['todays_policies'];
                    $data['total_oriental_policies'] = $value['total_policies'];
                    $data['todays_plan_sold_policies']=$this->Home_Model->todaysplansoldpolicies(10);
                    
                    for($i=0;$i<count($data['todays_plan_sold_policies']);$i++){
                        if($data['todays_plan_sold_policies'][$i]['plan_name']=='Gold'){
                            $data['todays_gold']=$data['todays_plan_sold_policies'][$i]['count'];
                            $data['gold_premium']=$data['todays_gold']*95;
                        }
                        else if($data['todays_plan_sold_policies'][$i]['plan_name']=='Platinum'){
                            $data['todays_platinum']=$data['todays_plan_sold_policies'][$i]['count'];
                            $data['platinum_premium']=$data['todays_platinum']*140;
                        }
                        else if($data['todays_plan_sold_policies'][$i]['plan_name']=='Silver'){
                            $data['todays_silver']=$data['todays_plan_sold_policies'][$i]['count'];
                            $data['silver_premium']=$data['todays_silver']*50;
                        }
                        else if($data['todays_plan_sold_policies'][$i]['plan_name']=='Sapphire'){
                            $data['todays_sapphire']=$data['todays_plan_sold_policies'][$i]['count'];
                            $data['sapphire_premium']=$data['todays_sapphire']*140;
                        }
                    }
                    $data['oriental_total_policies']=$data['todays_gold']+$data['todays_platinum']+$data['todays_silver']+$data['todays_sapphire'];
                    $data['oriental_total_premium']=$data['gold_premium']+$data['platinum_premium']+$data['sapphire_premium']+$data['silver_premium'];
                    $data['last_3_months_oriental']=$l3o=$this->Home_Model->last3MonthsOriental();
                   // echo '<pre>'; print_r($l3o);die;
                    $data['total_oriental']=$this->Home_Model->totalorientalpolicies();
                    break;
                case 11:
                    // **MYTVS**
                    $data['todays_mytvs_policies'] = $value['todays_policies'];
                    $data['total_mytvs_policies'] = $value['total_policies'];
                    $data['td_RR310_new_mytvs']=$value['td_limitless_assist_RR310_policies'];
                    $data['td_RR310_renew_mytvs']=$value['td_limitless_assist_RR310renew_policies'];
                    $data['td_RR310_online_mytvs']=$value['td_limitless_assistE_RR310_policies'];
                    $data['RR310_new_mytvs']=$value['limitless_assist_RR310_policies'];
                    $data['RR310_renew_mytvs']=$value['limitless_assistrenew_RR310_policies'];
                    $data['RR310_online_mytvs']=$value['limitless_assistE_RR310_policies'];
                    break;
                case 12:
                    // **BHARTI AXA**
                    $data['todays_bharti_axa_policies'] = $value['todays_policies'];
                    $data['total_bharti_axa_policies'] = $value['total_policies'];
                    break;
                case 13:
                    // **LIBERTY**
                    $data['todays_liberty_policies'] = $value['todays_policies'];
                    $data['total_liberty_policies'] = $value['total_policies'];
                    break;
            }
//closing swicth condition
            
        } 
// foreach closing

         $data['todays_policies'] = $data['todays_kotak_policies'] + $data['todays_icici_policies']+$data['todays_reliance_policies']+$data['todays_tata_aig_policies']+$data['todays_oriental_policies']+$data['todays_bharti_axa_policies']+$data['todays_liberty_policies'] +$data['todays_hdfc_policies'];

        $data['total_policies'] = $data['total_kotak_policies']+$data['total_icici_policies']+$data['total_reliance_policies']+$data['total_tata_aig_policies']+$data['total_oriental_policies'] +$data['total_bharti_axa_policies']+$data['total_liberty_policies']+$data['total_hdfc_policies'];

  
// echo "<pre>"; print_r($data); echo "</pre>"; die('end of line yoyo');
        //Total Wallet Balance
        $data['total_wallet_balance'] = $this->Home_Model->getTotalWalletBalance();
        
       //Todays Total Logged In Dealers
        $data['total_dealers_logged_in'] = $this->Home_Model->CountDealerLoggedIn();
        //echo '<pre>'; print_r($data);die('data');
        $dashboard = ($ic_id==10)?'oriental_dashboard':'admin_template';
        $data['main_contain'] = 'admin/report/'.$dashboard;
        $this->load->view('admin/includes/template', $data);
        
    }
    
     
    function getMakeModelName($type, $id) {
        switch ($type) {
            case 'make':
                $table_name = 'tvs_make';
                $column_name = 'make';
                $column_where = 'id';
                break;
            case 'model':
                $table_name = 'tvs_model_master';
                $column_name = 'model_name';
                $column_where = 'model_id';
                break;
        }

        $name = $this->db->select($column_name)->from($table_name)->where($column_where, $id)->limit(1)->get()->row();
        //echo $this->db->last_query();

        return $name->$column_name;
    }
    
    function addPartyPayment() {
        $data['main_contain'] = 'admin/report/add_party_payment';
        // $where = array('insurance_type' => 'RSA');
        $data['party_lists'] = $this->Home_Model->getDataFromTable('tvs_insurance_companies');
        $this->load->view('admin/includes/template', $data);
    }
     function submitPartyPaymentForm() {
        $post_data = $this->input->post();
        $where = array('transaction_no' => $post_data['transaction_no']);
        $transantion_data = $this->Home_Model->getRowDataFromTable('party_payment_details',$where);
        if(!empty($transantion_data)){
            $this->session->set_flashdata('success', 'Transaction Is Already Exist');
            redirect('admin/party_payment_details');
        }
        if (!empty($post_data)) {
            $where = array('sap_ad_code' => $this->input->post('sap_ad_code'));
            $check_dealer_code_exist = $this->Home_Model->getDataFromTable('tvs_dealers', $where);
            $payment_id = $this->Home_Model->insertIntoTable('party_payment_details', $post_data);
            if (!empty($payment_id)) {
                $where = array('party_id'=>$post_data['party_id']);
                $is_exist = $this->Home_Model->getRowDataFromTable('party_wallet',$where);
                if($is_exist){
                    $balance_amount = $is_exist['balance_amount'];
                    $balance_amount = $balance_amount +$post_data['amount'];
                     $party_wallet = array(
                            'balance_amount'=>$balance_amount
                        );
                     $status = $this->Home_Model->updateTable('party_wallet',$party_wallet,$where);
                        if($status){
                                $this->session->set_flashdata('success', 'Payment Is Added');
                                redirect('admin/party_payment_details');
                         }else{
                            $this->session->set_flashdata('success', 'Something Went Wrong Please Try Again');
                            redirect('admin/party_payment_details');
                         }
                }else{
                    $party_wallet = array(
                            'party_id'=>$post_data['party_id'],
                            'balance_amount'=>$post_data['amount']
                        );
                 $status = $this->Home_Model->insertIntoTable('party_wallet', $party_wallet);
                     if($status){
                        $this->session->set_flashdata('success', 'Payment Is Added');
                        redirect('admin/party_payment_details');
                     }else{
                        $this->session->set_flashdata('success', 'Something Went Wrong Please Try Again');
                        redirect('admin/party_payment_details');
                    }
                }
            }
        }
    }
    function partyPaymentDetails(){
        $totalSoldPolicies = $this->Home_Model->totalSoldPolicies();
        // echo '<pre>'; print_r($totalSoldPolicies);die('here');
        $bharti_deposit_amount = 0;
        $bharti_policy_amount = 0; 

        $mytvs_deposit_amount = 0;
        $mytvs_policy_amount = 0;

        $kotak_deposit_amount = 0;
        $kotak_silver_amount = 0;
        $kotak_gold_amount = 0;
        $kotak_platinum_amount = 0;
        $kotak_sapphire_amount = 0;
        $kotak_sapphire_plus_amount =0;

        $il_deposit_amount = 0;
        $il_silver_amount = 0;
        $il_gold_amount = 0;
        $il_platinum_amount = 0;
        $il_sapphire_amount = 0;

        $ba_deposit_amount = 0;
        $ba_silver_amount = 0;
        $ba_gold_amount = 0;
        $ba_platinum_amount = 0;
        $ba_sapphire_amount = 0;

        $tagi_deposit_amount = 0;
        $tagi_silver_amount = 0;
        $tagi_gold_amount = 0;
        $tagi_platinum_amount = 0;
        $tagi_sapphire_amount = 0;
        // echo '<pre>'; print_r($totalSoldPolicies);
        foreach ($totalSoldPolicies as $key => $totalSoldPolicie) {
            
            if($totalSoldPolicie['ic_id'] == 1 && $totalSoldPolicie['name'] == 'BHARTI ASSIST GLOBAL'){
               $bharti_deposit_amount = $totalSoldPolicie['balance_amount'];
               $bharti_rsa_tenure_1_count = $totalSoldPolicie['rsa_tenure_1_count'];
               $bharti_rsa_tenure_2_count = $totalSoldPolicie['rsa_tenure_2_count'];
               $bharti_rsa1_amount = $bharti_rsa_tenure_1_count * (16 + (16 * 0.18));
               $bharti_rsa2_amount = $bharti_rsa_tenure_2_count * (32 + (32 * 0.18));
               $bharti_policy_amount = $bharti_rsa1_amount + $bharti_rsa2_amount;
            }
            if($totalSoldPolicie['ic_id'] == 11 && $totalSoldPolicie['name'] == 'MY TVS'){
               $mytvs_deposit_amount = $totalSoldPolicie['balance_amount'];
               $mytv_rsa_tenure_1_count = $totalSoldPolicie['rsa_tenure_1_count'];
               $mytv_rsa_tenure_2_count = $totalSoldPolicie['rsa_tenure_2_count'];
               $mytvs_ra1_amount = $mytv_rsa_tenure_1_count * (16 + (16 * 0.18));
               $mytvs_ra2_amount = $mytv_rsa_tenure_2_count * (32 + (32 * 0.18));
               $mytvs_policy_amount = $mytvs_ra1_amount + $mytvs_ra2_amount;
            }
            if($totalSoldPolicie['ic_id'] == 2 && $totalSoldPolicie['name'] == 'KOTAK'){
                $kotak_deposit_amount = $totalSoldPolicie['balance_amount'];
                $kotak_silver_amount = $totalSoldPolicie['silver_policies'] * (70 + (70 * 0.18));
                $kotak_gold_amount = $totalSoldPolicie['gold_policies'] * (140 + (140 * 0.18));
                $kotak_platinum_amount = $totalSoldPolicie['platinum_policies'] * (210 + (210 * 0.18));
                $kotak_sapphire_amount = $totalSoldPolicie['sapphire_policies'] * (210 + (210 * 0.18));
                $kotak_sapphire_plus_amount = $totalSoldPolicie['sapphire_plus_policies'] * (375 + (375 * 0.18));
                // die($totalSoldPolicie['sapphire_plus_policies']);
            }
            if($totalSoldPolicie['ic_id'] == 5 && $totalSoldPolicie['name'] == 'ICICI Lombard'){
                $il_deposit_amount = $totalSoldPolicie['balance_amount'];
                $il_silver_amount = $totalSoldPolicie['silver_policies'] * (70 + (70 * 0.18));
                $il_gold_amount = $totalSoldPolicie['gold_policies'] * (140 + (140 * 0.18));
                $il_platinum_amount = $totalSoldPolicie['platinum_policies'] * (210 + (210 * 0.18));
                $il_sapphire_amount = $totalSoldPolicie['sapphire_policies'] * (210 + (210 * 0.18));
            }
            if($totalSoldPolicie['ic_id'] == 12 && $totalSoldPolicie['name'] == 'Bharti AXA GI'){
                // die('here');
                $ba_deposit_amount = $totalSoldPolicie['balance_amount'];
                $ba_silver_amount = $totalSoldPolicie['silver_policies'] * (70 + (70 * 0.18));
                $ba_gold_amount = $totalSoldPolicie['gold_policies'] * (140 + (140 * 0.18));
                $ba_platinum_amount = $totalSoldPolicie['platinum_policies'] * (210 + (210 * 0.18));
                $ba_sapphire_amount = $totalSoldPolicie['sapphire_policies'] * (210 + (210 * 0.18));
            }
            if($totalSoldPolicie['ic_id'] == 9 && $totalSoldPolicie['name'] == 'Tata AIG General Insurance Company'){
                // die('here');
                $tagi_deposit_amount = $totalSoldPolicie['balance_amount'];
                $tagi_silver_amount = $totalSoldPolicie['silver_policies'] * (70 + (70 * 0.18));
                $tagi_gold_amount = $totalSoldPolicie['gold_policies'] * (140 + (140 * 0.18));
                $tagi_platinum_amount = $totalSoldPolicie['platinum_policies'] * (210 + (210 * 0.18));
                $tagi_sapphire_amount = $totalSoldPolicie['sapphire_policies'] * (210 + (210 * 0.18));
            }
             if($totalSoldPolicie['ic_id'] == 10 && $totalSoldPolicie['name'] == 'The Oriental Insurance  Co. Ltd.'){
                // die('here');
                $oriental_deposit_amount = $totalSoldPolicie['balance_amount'];
                $oriental_silver_amount = $totalSoldPolicie['silver_policies'] * (50 + (50 * 0.18));
                $oriental_gold_amount = $totalSoldPolicie['gold_policies'] * (95 + (95 * 0.18));
                $oriental_platinum_amount = $totalSoldPolicie['platinum_policies'] * (140 + (140 * 0.18));
                $oriental_sapphire_amount = $totalSoldPolicie['sapphire_policies'] * (140 + (140 * 0.18));
            }
            if($totalSoldPolicie['ic_id'] == 7 && $totalSoldPolicie['name'] == 'HDFC ERGO General Insurance Company Limited'){
                // die('here');
                $hdfc_deposit_amount = $totalSoldPolicie['balance_amount'];
                $hdfc_silver_amount = $totalSoldPolicie['silver_policies'] * (70 + (70 * 0.18));
                $hdfc_gold_amount = $totalSoldPolicie['gold_policies'] * (140 + (140 * 0.18));
                $hdfc_platinum_amount = $totalSoldPolicie['platinum_policies'] * (210 + (210 * 0.18));
                $hdfc_sapphire_amount = $totalSoldPolicie['sapphire_policies'] * (210 + (210 * 0.18));
            }
            if($totalSoldPolicie['ic_id'] == 8 && $totalSoldPolicie['name'] == 'Reliance General Insurance Co Ltd'){
                // die('here');
                $reliance_deposit_amount = $totalSoldPolicie['balance_amount'];
                $reliance_silver_amount = $totalSoldPolicie['silver_policies'] * (70 + (70 * 0.18));
                $reliance_gold_amount = $totalSoldPolicie['gold_policies'] * (140 + (140 * 0.18));
                $reliance_platinum_amount = $totalSoldPolicie['platinum_policies'] * (210 + (210 * 0.18));
                $reliance_sapphire_amount = $totalSoldPolicie['sapphire_policies'] * (210 + (210 * 0.18));
            }
            if($totalSoldPolicie['ic_id'] == 13 && $totalSoldPolicie['name'] == 'Liberty General Insurance Limited'){
                // die('here');
                $liberty_deposit_amount = $totalSoldPolicie['balance_amount'];
                $liberty_silver_amount = $totalSoldPolicie['silver_policies'] * (70 + (70 * 0.18));
                $liberty_gold_amount = $totalSoldPolicie['gold_policies'] * (140 + (140 * 0.18));
                $liberty_platinum_amount = $totalSoldPolicie['platinum_policies'] * (210 + (210 * 0.18));
                $liberty_sapphire_amount = $totalSoldPolicie['sapphire_policies'] * (210 + (210 * 0.18));
            }
        }

        $this->data['kotak_total_policy_amount'] = $kotak_total_policy_amount = $kotak_silver_amount+$kotak_gold_amount+$kotak_platinum_amount+$kotak_sapphire_amount+$kotak_sapphire_plus_amount;
        $this->data['kotak_deposit_amount'] = $kotak_deposit_amount;
        $this->data['kotak_balance_amount'] = ($kotak_deposit_amount - $kotak_total_policy_amount);

        $this->data['bharti_rsa_tenure_1_count'] = $bharti_rsa_tenure_1_count;
        $this->data['bharti_rsa_tenure_2_count'] = $bharti_rsa_tenure_2_count;
        $this->data['bharti_policy_amount'] = $bharti_policy_amount;
        $this->data['bharti_balance_amount'] = ($bharti_deposit_amount - $bharti_policy_amount);
        $this->data['bharti_deposit_amount'] = ($bharti_deposit_amount);

        $this->data['mytv_rsa_tenure_1_count'] = $mytv_rsa_tenure_1_count;
        $this->data['mytv_rsa_tenure_2_count'] = $mytv_rsa_tenure_2_count;
        $this->data['mytvs_deposit_amount'] = $mytvs_deposit_amount;
        $this->data['mytvs_policy_amount'] = $mytvs_policy_amount;
        $this->data['mytvs_balance_amount'] = $mytvs_deposit_amount - $mytvs_policy_amount;

        $this->data['ba_total_policy_amount'] = $ba_total_policy_amount = $ba_silver_amount+$ba_gold_amount+$ba_platinum_amount+$ba_sapphire_amount;
        $this->data['ba_deposit_amount'] = $ba_deposit_amount;
        $this->data['ba_balance_amount'] = ($ba_deposit_amount - $ba_total_policy_amount);

        $this->data['il_total_policy_amount'] = $il_total_policy_amount = $il_silver_amount+$il_gold_amount+$il_platinum_amount+$il_sapphire_amount;
        $this->data['il_deposit_amount'] = $il_deposit_amount;
        $this->data['il_balance_amount'] = ($il_deposit_amount - $il_total_policy_amount);

        $this->data['tagi_total_policy_amount'] = $tagi_total_policy_amount = $tagi_silver_amount+$tagi_gold_amount+$tagi_platinum_amount+$tagi_sapphire_amount;
        $this->data['tagi_deposit_amount'] = $tagi_deposit_amount;
        $this->data['tagi_balance_amount'] = ($tagi_deposit_amount - $tagi_total_policy_amount);

         $this->data['oriental_total_policy_amount'] = $oriental_total_policy_amount = $oriental_silver_amount + $oriental_gold_amount + $oriental_platinum_amount + $oriental_sapphire_amount ;
        $this->data['oriental_deposit_amount'] = $oriental_deposit_amount;
        $this->data['oriental_balance_amount'] = ($oriental_deposit_amount - $oriental_total_policy_amount);

        $this->data['liberty_total_policy_amount'] =$liberty_total_policy_amount = $liberty_silver_amount+$liberty_gold_amount+$liberty_platinum_amount+$liberty_sapphire_amount ;
        $this->data['liberty_deposit_amount'] = $liberty_deposit_amount;
        $this->data['liberty_balance_amount'] = ($liberty_deposit_amount-$liberty_total_policy_amount);

        $this->data['reliance_total_policy_amount'] =$reliance_total_policy_amount= $reliance_silver_amount+$reliance_gold_amount+$reliance_platinum_amount+$reliance_sapphire_amount;
        $this->data['reliance_deposit_amount'] = $reliance_deposit_amount;
        $this->data['reliance_balance_amount'] = ($reliance_deposit_amount-$reliance_total_policy_amount);

        $this->data['hdfc_total_policy_amount'] =$hdfc_total_policy_amount= $hdfc_silver_amount+$hdfc_gold_amount+$hdfc_platinum_amount+$hdfc_sapphire_amount;
        $this->data['hdfc_deposit_amount'] = $hdfc_deposit_amount;
        $this->data['hdfc_balance_Amount'] = ($hdfc_deposit_amount-$hdfc_total_policy_amount);

        $this->data['main_contain'] = 'admin/report/party_payment_details';
        $this->load->view('admin/includes/template', $this->data);
    }
    function partyPaymentDetailsAjax() {
        $admin_session = $this->session->userdata('admin_session');
        $ic_id = $admin_session['ic_id'];
        $requestData = $_REQUEST;
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $where_date = '';
         // $where = 'pp.invoice_date' != 0;
        if (!empty($start_date) && !empty($end_date)) {
            $where_date = "AND (CAST(ppd.`created_at` AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        }
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0=>'zone',
            1=>'state',
            2=>'no_of_amd',
            3=>'no_of_ad',
            4=>'no_of_amd_activeted',
            5=>'no_of_ad_activeted',
            6=>'avg_amd_activated',
            7=>'avg_ad_activated',
            8=>'amou_depo_by_dealer',
            9=>'amou_of_policy_issued',
            10=>'no_of_policy_issued',
            
        ); 
        $where_party_id = ($ic_id==10)?'$ic_id':'IS NOT NULL';
        $sql = "SELECT ppd.*,tic.name FROM party_payment_details AS ppd INNER JOIN tvs_insurance_companies AS tic ON tic.id = ppd.party_id WHERE ppd.`party_id` $where_party_id $where_date ORDER BY ppd.id DESC";
        // die($sql);
        $query = $this->db->query($sql);
        $results = $query->result_array();

        // echo '<pre>'; print_r($results1);die('here');
        $totalFiltered = $totalData;
        $totalFiltered = count($results1);
        $i = 1;
        $data = array();
        foreach ($results as $main) {
            $nestedData = array();
            $nestedData[] = $i++;
            $nestedData[] = $main['name'];
            $nestedData[] = $main['amount'];
            $nestedData[] = $main['transaction_no'];
            $nestedData[] = $main['ifsc_code'];
            $nestedData[] = $main['bank_name'];
            $nestedData[] = $main['account_no'];
            $nestedData[] = $main['payment_date'];
            $nestedData[] = $main['created_at'];
            $data[] = $nestedData;
        }
        // echo '<pre>'; print_r($data);die('here');
        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    function dealerWiseReports(){
         $data['main_contain'] = 'admin/report/dealer_wise_reports';
         $this->load->view('admin/includes/template', $data);
     }
    function dealerWiseReportsAjax() {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0=>'sap_ad_code',
            1=>'dealer_name',
            2=>'location',
            3=>'territory',
            4=>'area',
            5=>'zone',
            6=>'activation_status',
            7=>'deposited_amount',
            8=>'total_policy_amount',
            9=>'balance_amount',
            10=>'no_of_policies',
            11=>'no_platinum_plans',
            12=>'no_sapphire_plans',
            13=>'no_gold_plans',
            14=>'no_silver_plans',
            15=>'no_il_policies',
            16=>'no_kotak_policies',
            17=>'no_tvs_policies',
            18=>'no_bharti_policies'
        );

        $admin_session = $this->session->userdata('admin_session');
        $zone_code = '';$where = '';
        if($admin_session['admin_role']=='zone_code'){
            $zone_code = $admin_session['admin_role_id'];
            $where = "AND tdzm.`zone_code` = '$zone_code' " ;
        }


        $sql1 = "SELECT td.id as dealer_id,td.dealer_code,td.dealer_name,td.sap_ad_code,td.location,tdzm.*,
                  IF(dw.id > 0, 'Active', 'Inactive') AS activation_status,
                  dttr.total_deposit_amount AS deposited_amount,
                  (dw.security_amount - dw.credit_amount) AS balance_amount 
                  FROM tvs_dealers AS td 
                  INNER JOIN tvs_dealer_zone_mapper AS tdzm ON td.zone_id = tdzm.id 
                  LEFT JOIN dealer_wallet AS dw ON td.id = dw.dealer_id
                  LEFT JOIN dealer_bank_transaction dbt ON td.id = dbt.dealer_id  
                  LEFT JOIN dealer_total_transaction_record AS dttr ON td.id = dttr.dealer_id
                  WHERE  td.`dealer_code` != 11111 $where
                  GROUP BY td.sap_ad_code";
                  // die($sql1);
        $query1 = $this->db->query($sql1);
        $results1 = $query1->result_array();


         $sql2 = "SELECT  
                  tsp.`user_id`,
                  COUNT(tsp.id) AS no_of_policies, 
                  SUM(tsp.sold_policy_price_with_tax) AS total_policy_amount, 
                  COUNT(IF(tsp.plan_name = 'Platinum', tsp.plan_name, NULL)) AS no_platinum_plans,
                  COUNT(IF(tsp.plan_name = 'Sapphire', tsp.plan_name, NULL)) AS no_sapphire_plans, 
                  COUNT(IF(tsp.plan_name = 'Gold', tsp.plan_name, NULL)) AS no_gold_plans, 
                  COUNT(IF(tsp.plan_name = 'Silver', tsp.plan_name, NULL)) AS no_silver_plans, 
                  COUNT(IF(tsp.ic_id = 5, tsp.ic_id, NULL)) AS no_il_policies, 
                  COUNT(IF(tsp.ic_id = 2, tsp.ic_id, NULL)) AS no_kotak_policies, 
                  COUNT(IF(tsp.ic_id = 12, tsp.ic_id, NULL)) AS no_bagi_policies,
                  COUNT(IF(tsp.ic_id = 9, tsp.ic_id, NULL)) AS no_tata_policies, 
                  COUNT(IF(tsp.ic_id = 10, tsp.ic_id, NULL)) AS no_oriental_policies,
                  COUNT(IF(tsp.ic_id = 7, tsp.ic_id, NULL)) AS no_hdfc_policies,
                  COUNT(IF(tsp.ic_id = 8, tsp.ic_id, NULL)) AS no_reliance_policies,
                  COUNT(IF(tsp.ic_id = 13, tsp.ic_id, NULL)) AS no_liberty_policies,
                  COUNT(IF(tsp.rsa_ic_id = 1, tsp.rsa_ic_id, NULL)) AS no_bharti_policies, 
                  COUNT(IF(tsp.rsa_ic_id = 2, tsp.rsa_ic_id, NULL)) AS no_tvs_policies 
                  FROM tvs_sold_policies AS tsp GROUP BY tsp.`user_id`";
        $query2 = $this->db->query($sql2);
        $results2 = $query2->result_array();
        foreach ($results1 as $key1 => $result1) {
                foreach ($results2 as $key2 => $result2) {
                    if($result1['dealer_id'] == $result2['user_id']){
                        $results1[$key1]['no_of_policies'] = $result2['no_of_policies'];
                        $results1[$key1]['total_policy_amount'] = $result2['total_policy_amount'] ;
                        $results1[$key1]['no_platinum_plans'] = $result2['no_platinum_plans'] ;
                        $results1[$key1]['no_sapphire_plans'] = $result2['no_sapphire_plans'] ;
                        $results1[$key1]['no_gold_plans'] = $result2['no_gold_plans'] ;
                        $results1[$key1]['no_silver_plans'] = $result2['no_silver_plans'] ;
                        $results1[$key1]['no_il_policies'] = $result2['no_il_policies'] ;
                        $results1[$key1]['no_kotak_policies'] = $result2['no_kotak_policies'] ;
                        $results1[$key1]['no_tvs_policies'] = $result2['no_tvs_policies'] ;
                        $results1[$key1]['no_bharti_policies'] = $result2['no_bharti_policies'] ;
                        $results1[$key1]['no_tata_policies'] = $result2['no_tata_policies'] ;
                        $results1[$key1]['no_bagi_policies'] = $result2['no_bagi_policies'] ;
                        $results1[$key1]['no_oriental_policies'] = $result2['no_oriental_policies'] ;
                        $results1[$key1]['no_hdfc_policies'] = $result2['no_hdfc_policies'] ;
                        $results1[$key1]['no_reliance_policies'] = $result2['no_reliance_policies'] ;
                        $results1[$key1]['no_liberty_policies'] = $result2['no_liberty_policies'] ;
                    }
                }
        }
        // echo '<pre>'; print_r($results1);die('here');
        $totalFiltered = $totalData;
        $totalFiltered = count($results1);
        $i = 1;
        $data = array();
        foreach ($results1 as $main) {
            $nestedData = array();
            $nestedData[] = $i++;
            $nestedData[] = isset($main['sap_ad_code'])?$main['sap_ad_code']:'';
            $nestedData[] = $main['dealer_name'];
            $nestedData[] = $main['location'];
            $nestedData[] = $main['territory'];
            $nestedData[] = $main['area'];
            $nestedData[] = $main['zone'];
            $nestedData[] = $main['activation_status'];
            if( ($admin_session['ic_id']==2) || in_array($admin_session['admin_role_id'], array(2,6,1) ) || ($admin_session['admin_role']=='zone_code') ){
                $nestedData[] = isset($main['deposited_amount'])?$main['deposited_amount']:0;
                $nestedData[] = isset($main['total_policy_amount'])?$main['total_policy_amount']:0;
                $nestedData[] = isset($main['balance_amount'])?$main['balance_amount']:0;
                $nestedData[] = isset($main['no_of_policies'])?$main['no_of_policies']:0;
                $nestedData[] = isset($main['no_platinum_plans'])?$main['no_platinum_plans']:0;
                $nestedData[] = isset($main['no_sapphire_plans'])?$main['no_sapphire_plans']:0;
                $nestedData[] = isset($main['no_gold_plans'])?$main['no_gold_plans']:0;
                $nestedData[] = isset($main['no_silver_plans'])?$main['no_silver_plans']:0;
            }
            if( ($admin_session['ic_id']==2) || ($admin_session['ic_id']==5) || ($admin_session['admin_role']=='zone_code') || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){
                $nestedData[] = isset($main['no_il_policies'])?$main['no_il_policies']:0;
            }
            if( ($admin_session['ic_id']==2) || ($admin_session['admin_role']=='zone_code') || (in_array($admin_session['admin_role_id'], array(2,6,1) )) ){
                $nestedData[] = isset($main['no_kotak_policies'])?$main['no_kotak_policies']:0;
            }
            if( ($admin_session['ic_id']==2) || ($admin_session['ic_id']==11) || ($admin_session['admin_role']=='zone_code') || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){
                $nestedData[] = isset($main['no_tvs_policies'])?$main['no_tvs_policies']:0;
            }
            if( ($admin_session['ic_id']==2) || ($admin_session['ic_id']==1) || ($admin_session['admin_role']=='zone_code') || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){
                $nestedData[] = isset($main['no_bharti_policies'])?$main['no_bharti_policies']:0;
            }
            if( ($admin_session['ic_id']==9) || ($admin_session['admin_role']=='zone_code') || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){
                $nestedData[] = isset($main['no_tata_policies'])?$main['no_tata_policies']:0;
            }
            if( ($admin_session['ic_id']==12) || ($admin_session['admin_role']=='zone_code') || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){
                $nestedData[] = isset($main['no_bagi_policies'])?$main['no_bagi_policies']:0;
            }
            if( ($admin_session['ic_id']==10) || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){
                $nestedData[] = isset($main['no_oriental_policies'])?$main['no_oriental_policies']:0;
            }
            if( ($admin_session['ic_id']==13) || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){
                $nestedData[] = isset($main['no_liberty_policies'])?$main['no_liberty_policies']:0;
            }
            if( ($admin_session['ic_id']==8) || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){
                $nestedData[] = isset($main['no_reliance_policies'])?$main['no_reliance_policies']:0;
            }
            if( ($admin_session['ic_id']==7) || (in_array($admin_session['admin_role_id'], array(2,6,1) ) ) ){
                $nestedData[] = isset($main['no_hdfc_policies'])?$main['no_hdfc_policies']:0;
            }
            $viewdetails=base_url('admin/dealer_graphical_details/'.$main['dealer_id']);
             $nestedData[] = '<a title="View Details" href="'.$viewdetails.'" class="btn btn-primary" target="_blank"><i class="fa fa-bars" aria-hidden="true"></i></a>';
            $data[] = $nestedData;
        }
        // echo '<pre>'; print_r($data);die('here');
        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }
    function migReports(){
         $data['main_contain'] = 'admin/report/mig_reports';
         $this->load->view('admin/includes/template', $data);
     }
     function migReportsAjax() {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0=>'zone',
            1=>'state',
            2=>'no_of_amd_activeted',
            3=>'no_of_ad_activeted',
            4=>'percentage_of_amd_activeted',
            5=>'percentage_of_ad_activeted',
            6=>'deposited_amount',
            7=>'total_policy_amount',
            8=>'no_of_policies'
        );

        $admin_session = $this->session->userdata('admin_session');
        $zone_code = '';$where = '';
        if($admin_session['admin_role']=='zone_code'){

            $zone_code = $admin_session['admin_role_id'];
            $where = "AND tdzm.`zone_code` = '$zone_code' " ;

        }
        $sql1 = "SELECT 
                        DISTINCT( tdzm.`area`) AS state,
                        tdzm.`zone`,
                        SUM(td.`dealer_type` = 'AD') AS total_ads,
                        SUM(td.`dealer_type` = 'AMD') AS total_amds,
                        COUNT(IF(td.`dealer_type` = 'AMD',dw.id,NULL)) AS active_amds,
                        COUNT(IF(td.`dealer_type` = 'AD',dw.id,NULL)) AS active_ads,
                        SUM(dttr.total_deposit_amount) AS total_deposit_amount
                                FROM tvs_dealer_zone_mapper tdzm 
                                INNER JOIN tvs_dealers AS td ON td.`zone_id` = tdzm.`id`
                                LEFT JOIN dealer_wallet AS dw ON td.id = dw.dealer_id 
                                LEFT JOIN dealer_total_transaction_record AS dttr ON td.id = dttr.dealer_id
                                WHERE  td.`dealer_code` != 11111
                                GROUP BY tdzm.area";
        $query1 = $this->db->query($sql1);
        $results1 = $query1->result_array();

         $sql2 = "SELECT
                    DISTINCT( tdzm.`area`) AS state,tdzm.`zone`,
                    COUNT(tsp.id) AS no_of_policies,
                    SUM(tsp.sold_policy_price_with_tax) AS total_policy_amount
                    FROM tvs_sold_policies AS tsp
                    INNER JOIN tvs_dealers AS td ON td.`id` = tsp.`user_id`
                    INNER JOIN tvs_dealer_zone_mapper tdzm ON td.`zone_id` = tdzm.`id`
                    WHERE tsp.policy_status_id = 3
                    -- WHERE td.dealer_code != 11111 AND tsp.policy_status_id = 3
                    GROUP BY tdzm.area";
        $query2 = $this->db->query($sql2);
        $results2 = $query2->result_array();
        foreach ($results1 as $key1 => $result1) {
                 foreach ($results2 as $key2 => $result2) {
                    if($result1['state'] == $result2['state']){
                        $results1[$key1]['avg_amd_activated'] = (($result1['active_amds']/$result1['total_amds'])*100);
                        $results1[$key1]['avg_ad_activated'] = (($result1['active_ads']/$result1['total_ads'])*100);
                        $results1[$key1]['no_of_policies'] = $result2['no_of_policies'];
                        $results1[$key1]['total_policy_amount'] = $result2['total_policy_amount'];
                    }
                 }
        }
        //echo '<pre>'; print_r($results1);die('here');
        $totalFiltered = $totalData;
        $totalFiltered = count($results1);
        $i = 1;
        $data = array();
        foreach ($results1 as $main) {
            $nestedData = array();
            $nestedData[] = $i++;
            $nestedData[] = $main['zone'];
            $nestedData[] = $main['state'];
            $nestedData[] = isset($main['total_amds'])?$main['total_amds']:0;
            $nestedData[] = isset($main['active_amds'])?$main['active_amds']:0;
            $nestedData[] = isset($main['avg_amd_activated'])?round($main['avg_amd_activated']):0;
            $nestedData[] = isset($main['total_ads'])?$main['total_ads']:0;
            $nestedData[] = isset($main['active_ads'])?$main['active_ads']:0;
            $nestedData[] = isset($main['avg_ad_activated'])?round($main['avg_ad_activated']):0;
            $nestedData[] = isset($main['total_deposit_amount'])?round($main['total_deposit_amount']):0;
            $nestedData[] = isset($main['total_policy_amount'])?$main['total_policy_amount']:0;
            $nestedData[] = isset($main['no_of_policies'])?$main['no_of_policies']:0;
            $data[] = $nestedData;
        }
        // echo '<pre>'; print_r($data);die('here');
        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    function dashboardSummary(){
         $data['main_contain'] = 'admin/report/dashboard_summary';
         $this->load->view('admin/includes/template', $data);
     }
     function dashboardSummaryAjax() {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            5=>'zone',
            6=>'for_the_year',
            6=>'for_the_month',
            6=>'for_the_day',
            7=>'kotak_for_the_year',
            8=>'kotak_for_the_month',
            9=>'kotak_for_the_day',
            10=>'il_for_the_year',
            11=>'il_for_the_month',
            12=>'il_for_the_day',
            13=>'hdfc_for_the_year',
            14=>'hdfc_for_the_month',
            15=>'hdfc_for_the_day'
        );

        $admin_session = $this->session->userdata('admin_session');
        $zone_code = '';$where = '';
        if($admin_session['admin_role']=='zone_code'){
            $zone_code = $admin_session['admin_role_id'];
            $where = "AND tdzm.`zone_code` = '$zone_code' " ;
        }


        $sql = "SELECT DISTINCT( tdzm.`zone`) AS zone, 
COUNT(IF(DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 YEAR AND CURDATE(),1,NULL)) AS for_the_year,
COUNT(IF(DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 MONTH AND CURDATE(),1,NULL)) AS for_the_month,
COUNT(IF(DATE(tsp.created_date) = CURDATE(),1,NULL) ) AS for_the_day,
COUNT(IF(tsp.`ic_id` = 2 AND DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 YEAR AND CURDATE(),1,NULL) ) AS kotak_for_the_year,
COUNT(IF(tsp.`ic_id` = 2 AND DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 MONTH AND CURDATE(),1,NULL) ) AS kotak_for_the_month,
COUNT(IF(tsp.`ic_id` = 2 AND DATE(tsp.created_date) = CURDATE(),1,NULL) ) AS kotak_for_the_day,
COUNT(IF(tsp.`ic_id` = 5 AND DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 YEAR AND CURDATE(),1,NULL) ) AS il_for_the_year,
COUNT(IF(tsp.`ic_id` = 5 AND DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 MONTH AND CURDATE(),1,NULL) ) AS il_for_the_month,
COUNT(IF(tsp.`ic_id` = 5 AND DATE(tsp.created_date) = CURDATE(),1,NULL) ) AS il_for_the_day,
COUNT(IF(tsp.`ic_id` = 10 AND DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 YEAR AND CURDATE(),1,NULL) ) AS oriental_for_the_year,
COUNT(IF(tsp.`ic_id` = 10 AND DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 MONTH AND CURDATE(),1,NULL) ) AS oriental_for_the_month,
COUNT(IF(tsp.`ic_id` = 10 AND DATE(tsp.created_date) = CURDATE(),1,NULL) ) AS oriental_for_the_day,
COUNT(IF(tsp.`ic_id` = 8 AND DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 YEAR AND CURDATE(),1,NULL) ) AS reliance_for_the_year, 
COUNT(IF(tsp.`ic_id` = 8 AND DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 MONTH AND CURDATE(),1,NULL) ) AS reliance_for_the_month, 
COUNT(IF(tsp.`ic_id` = 8 AND DATE(tsp.created_date) = CURDATE(),1,NULL) ) AS reliance_for_the_day ,
COUNT(IF(tsp.`ic_id` = 13 AND DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 YEAR AND CURDATE(),1,NULL) ) AS liberty_for_the_year, 
COUNT(IF(tsp.`ic_id` = 13 AND DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 MONTH AND CURDATE(),1,NULL) ) AS liberty_for_the_month, 
COUNT(IF(tsp.`ic_id` = 13 AND DATE(tsp.created_date) = CURDATE(),1,NULL) ) AS liberty_for_the_day,
COUNT(IF(tsp.`ic_id` = 7 AND DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 YEAR AND CURDATE(),1,NULL) ) AS hdfc_for_the_year, 
COUNT(IF(tsp.`ic_id` = 7 AND DATE(tsp.created_date) BETWEEN NOW() - INTERVAL 1 MONTH AND CURDATE(),1,NULL) ) AS hdfc_for_the_month, 
COUNT(IF(tsp.`ic_id` = 7 AND DATE(tsp.created_date) = CURDATE(),1,NULL) ) AS hdfc_for_the_day
    FROM tvs_dealer_zone_mapper tdzm 
    INNER JOIN tvs_dealers AS td ON td.`zone_id` = tdzm.`id`
    LEFT JOIN dealer_wallet AS dw ON td.id = dw.dealer_id 
    LEFT JOIN tvs_sold_policies AS tsp ON tsp.`user_id` = td.id
    WHERE tsp.policy_status_id = 3
    -- WHERE  td.`dealer_code` != 11111 AND tsp.policy_status_id = 3
    GROUP BY tdzm.zone";
                  // die($sql);
        $query = $this->db->query($sql);
        $results = $query->result_array();

        // echo '<pre>'; print_r($results1);die('here');
        $totalFiltered = $totalData;
        $totalFiltered = count($results);
        $i = 1;
        $data = array();
        foreach ($results as $main) {
            $nestedData = array();
            $nestedData[] = $i++;
            $nestedData[] = $main['zone'];
            $nestedData[] = $main['for_the_year'];
            $nestedData[] = $main['for_the_month'];
            $nestedData[] = $main['for_the_day'];
            $nestedData[] = $main['kotak_for_the_year'];
            $nestedData[] = $main['kotak_for_the_month'];
            $nestedData[] = $main['kotak_for_the_day'];
            $nestedData[] = $main['il_for_the_year'];
            $nestedData[] = $main['il_for_the_month'];
            $nestedData[] = $main['il_for_the_day'];
            $nestedData[] = $main['oriental_for_the_year'];
            $nestedData[] = $main['oriental_for_the_month'];
            $nestedData[] = $main['oriental_for_the_day'];
            $nestedData[] = $main['liberty_for_the_year'];
            $nestedData[] = $main['liberty_for_the_month'];
            $nestedData[] = $main['liberty_for_the_day'];
            $nestedData[] = $main['reliance_for_the_year'];
            $nestedData[] = $main['reliance_for_the_month'];
            $nestedData[] = $main['reliance_for_the_day'];
            $nestedData[] = $main['hdfc_for_the_year'];
            $nestedData[] = $main['hdfc_for_the_month'];
            $nestedData[] = $main['hdfc_for_the_day'];
            $data[] = $nestedData;
        }
        // echo '<pre>'; print_r($data);die('here');
        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }


     function loggedInDealer(){
        $data['main_contain'] = 'admin/users/logged_in_dealers';
        $this->load->view('admin/includes/template', $data);
    }

     function loggedInDealerAjax() {
        $requestData = $_REQUEST;

        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'dealer_code',
            1 => 'dealer_name',
            2 => 'sap_ad_code',
            3 => 'mobile',
            4 => 'location',
        );


        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d');
        $where = array('DATE(rkl.created_at)'=>$curr_date);
        $sql = "SELECT td.* FROM redirect_key_log AS rkl INNER JOIN tvs_dealers AS td ON td.sap_ad_code = rkl.sap_ad_code WHERE DATE(rkl.created_at) = CURDATE() AND rkl.redirect_key IS NOT NULL";
        $totalFiltered = $totalData;
        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY rkl.id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        $i = 1;
        $data = array();
        foreach ($result as $main) {
            $nestedData = array();
            $nestedData[] = $i++;
            $nestedData[] = $main->dealer_code;
            $nestedData[] = $main->dealer_name;
            $nestedData[] = $main->sap_ad_code;
            $nestedData[] = $main->ad_name;
            $nestedData[] = $main->mobile;
            $nestedData[] = $main->location;
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }
     public function policy_summary_report() {
        $data['main_contain'] = 'admin/report/index';
        $data['ic_list'] = $this->Home_Model->getDataFromTable('hc_companies');
        $this->load->view('admin/includes/template', $data);
    }

    function policy_summary_ajax() {
        $requestData = $_REQUEST;
        $sql = "select CONCAT(hc_customers.title,' ',first_name,' ' ,middle_name)AS full_name,
                (select name from hc_categories where id=hc_customers.category_id) as category_name,
                CONCAT(hc_sold_policies.policy_no,'',hc_sold_policies.master_certification_no)as policy_number,
                hc_customers.mobile_number,
                hc_sold_policies.unique_value,
                hc_sold_policies.policy_start_date,
                hc_sold_policies.policy_end_date,
                hc_sold_policies.policy_risk_date
                from hc_sold_policies left join hc_customers
                on hc_customers.id=hc_sold_policies.customer_id";
        $query = $this->db->query($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
        $sql = "$sql AND  1 = 1";
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND policy_no LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " and ic_id = '" . $requestData['columns'][1]['search']['value'] . "' ";
        }
        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY hc_sold_policies.id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        $data = array();


        $i = 1;
        foreach ($result as $main) {
            $nestedData = array();
            $nestedData[] = $i++;
            $nestedData[] = $main->category_name;
            $nestedData[] = $main->policy_number;
            $nestedData[] = strtoupper($main->full_name);
            $nestedData[] = $main->mobile_number;
            $nestedData[] = $main->policy_start_date;
            $nestedData[] = $main->policy_end_date;
            $nestedData[] = $main->policy_risk_date;
            $nestedData[] = '<a  href="' . base_url() . 'myaccount/download-policy/' . $main->unique_value . '" target="_blank"> <button type="button" class="btn btn-warning" id="policy_no" style="margin-top: 7px;"><i class="fa fa-download" aria-hidden="true"></i></button></a>';
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

    function download_pacover_reportw($from, $to) {
        echo "hi";
    }

    function download_rsa_report($from, $to) {

        $heading_array = array("id", "Engine No", "Chassis No", "Sold Policy No", "Dealer Name", "Dealer Sap Code", "Policy Created Date", "Policy Effective Date", "Policy End Date");
        $main_array1 = array();
        array_push($main_array1, $heading_array);
        $query = $this->db->query("select tsp.engine_no,tsp.chassis_no,tsp.sold_policy_no,tsp.sold_policy_date,tsp.sold_policy_effective_date,tsp.sold_policy_end_date,tvd.dealer_code,tvd.dealer_name,tvd.sap_ad_code from tvs_sold_policies as tsp left join tvs_dealers as tvd on tvd.id = tsp.user_id where  date(sold_policy_date) >= '$from' AND date(sold_policy_date) <= '$to'");


        $row = $query->num_rows();
        $result = $query->result();
       

        foreach ($result as $key => $val) {
            $id = $result[$key]->id;
            $engine_no = $result[$key]->engine_no;
            $chassis_no = $result[$key]->chassis_no;
            $sold_policy_no =trim($result[$key]->sold_policy_no);
            $dealer_name =trim($result[$key]->dealer_name);
            $sap_ad_code =trim($result[$key]->sap_ad_code);
            $sold_policy_date = $result[$key]->sold_policy_date;
            $sold_policy_effective_date = $result[$key]->sold_policy_effective_date;
            $sold_policy_end_date = $result[$key]->sold_policy_end_date;

            $array_val = array($id, $engine_no, $chassis_no, $sold_policy_no, $dealer_name,$sap_ad_code , $sold_policy_date, $sold_policy_effective_date, $sold_policy_end_date);
     
            
            array_push($main_array1, $array_val);
        }


        $csv_file_name = "RSA Report-" . date('y-m-d') . ".csv";
        echo array_to_csv($main_array1, $csv_file_name);
    }

    public function rsa_feedfile($ic_id) {
        $data['ic_id'] = $ic_id;
         $data['main_contain'] = 'admin/report/pa_cover';
         $this->load->view('admin/includes/template', $data);
     }

     public function kotak_openrsa_feedfile($ic_id){
        $data['ic_id'] = $ic_id;
        $data['main_contain'] = 'admin/report/kotak_openrsa_feedfile';
        $this->load->view('admin/includes/template', $data);  
     }

     public function feedfile($ic_id) {
        if(empty($ic_id)){
            redirect('admin_dashboard');
        }else{
            $data['ic_id'] = $ic_id;
            $data['main_contain'] = 'admin/report/pa_cover';
            $this->load->view('admin/includes/template', $data);
        }
    }
     public function icici_feedfile($ic_id) {
        $data['ic_id'] = $ic_id ;
        $data['main_contain'] = 'admin/report/icici_feedfile';
        $this->load->view('admin/includes/template', $data);
    }

     public function hdfc_feedfile($ic_id) {
        $data['ic_id'] = $ic_id ;
        $data['main_contain'] = 'admin/report/hdfc_feedfile';
        $this->load->view('admin/includes/template', $data);
    }

    public function ViewPolicies() {
        //print_r($this->session->userdata());exit;
        $data['main_contain'] = 'admin/report/view_policies';
        $this->load->view('admin/includes/template', $data);
    }

    public function ViewPolicies_openrsa() {
        $data['main_contain'] = 'admin/report/view_policies_openrsa';
        $this->load->view('admin/includes/template', $data);
    }

    public function ViewOpenrsaPoliciesAjax() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $where = '';
        if (!empty($start_date) && !empty($end_date)) {
            $where = "AND (CAST(tsp.`created_date` AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        }else{
            $where = "AND (CAST(tsp.`created_date` AS DATE)) = CURDATE() " ;
        }

        $start = $requestData['start'];
        $length = $requestData['length'];

        $limit = ' LIMIT ' . $start . ', ' . $length . '';
        $openrsa_data = $this->Home_Model->getkotakOpnrsadata($where);
        // echo "<pre>"; print_r($openrsa_data); echo "</pre>"; die('end of line yoyo');
        $totalFiltered = $openrsa_data['num_rows'];
        $data=array();
        $i=1;
        foreach ($openrsa_data['result'] as $row) {
                // $file_list = '<a href="' . base_url() . 'download_opnrsa_kotak_lite_pdf/'. $row['sold_id'] . '" class="btn btn-info" target="_blank">Download Pdf</a><br/>';
                //         $pa_ic = 'Kotak';

                $gwp  = 375;
                $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;
                $opn_rsa=array();
                $opn_rsa[] = $i;
                $opn_rsa[] = trim($row['sold_policy_no']);
                $opn_rsa[] = 'Kotak';
                $opn_rsa[] = $row['engine_no'];
                $opn_rsa[] = $row['chassis_no'];
                $opn_rsa[] = $row['vehicle_type'];
                $opn_rsa[] = $row['model_name'];
                $opn_rsa[] = $row['sap_ad_code'];
                $opn_rsa[] = $row['dealer_name'];
                $opn_rsa[] = $row['fname'].' '.$row['lname'];
                $opn_rsa[] = $row['plan_name'];
                $opn_rsa[] = $row['basic_premium'];
                $opn_rsa[]  = $row['gst_amount'];
                $opn_rsa[] = $row['total_premium'];
                $opn_rsa[] = $row['dealer_commission'];
                $opn_rsa[] = $row['created_date'];
                $opn_rsa[] = $row['sold_policy_effective_date'];
                $opn_rsa[] = $row['sold_policy_end_date'];
                $opn_rsa[] = $row['city_name'];
                $opn_rsa[] = $row['state_name'];
                $opn_rsa[] = $file_list;

                $data[]=$opn_rsa;
                $i++;
        }
        
        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);

    }

    public function cancelPolicies($status=null) {
     $admin_session =  $this->session->userdata('admin_session');
       if(($admin_session['admin_role_id'] == 1 && $admin_session['admin_role'] == 'admin_master') || ($admin_session['admin_role_id'] == 2 && $admin_session['admin_role'] == 'opreation_admin')){
        $data['cancel_policy_status'] = $status ;
        if($status==4){
            $data['cancellation_status'] = 'Pending';
        }
        elseif($status==3){
            $data['cancellation_status'] ='Rejected Cancellation';
        }
        elseif($status==5){
            $data['cancellation_status'] = 'Cancelled' ;
        }
      
        $data['main_contain'] = 'admin/report/cancel_policies';
        $this->load->view('admin/includes/template', $data);
        }
        else if($admin_session['admin_role'] == 'service_admin'){
            $data['main_contain'] = 'admin/report/servicecancel_policies';
            $this->load->view('admin/includes/template', $data);
        }    
        else{
             redirect('admin');
        }
    }
    public function getRandomNumber($len){
            $better_token = $code=sprintf("%0".$len."d", mt_rand(1, str_pad("", $len,"9")));
            return $better_token;
        }
    // public function approveRsaCancellation() {
    //     $policy_id = $this->input->post('policy_id');
    //     $reason_of_cancellation = $this->input->post('reason_of_cancellation');
    //     $policy_details = $this->Home_Model->getPolicyDetails($policy_id);
    //     //echo '<pre>'; print_r($_POST);die('hello');
        // $transection_no = $this->getRandomNumber('16');
        // $dealer_transection_data = array(
        //     'dealer_id'=> $policy_details['user_id'],
        //     'policy_no'=> $policy_details['sold_policy_no'],
        //     'transection_id' => $transection_no,
        //     'transection_type' => 'dr',
        //     'transection_purpose' => 'Policy Cancelled',
        //     'policy_amount' =>$policy_details['sold_policy_price_with_tax'],
        //     'dealer_commission' =>$policy_details['dealer_commission'],
        //     'rsa_commission' =>$policy_details['rsa_commission_amount'],
        //     'pa_ic_commission' =>$policy_details['pa_ic_commission_amount'],
        //     'oem_commission' =>$policy_details['oem_commission_amount'],
        //     'brocker_commission' =>$policy_details['brocker_commission_amount'],
        // );
        // // echo '<pre>'; print_r($dealer_transection_data);die('here');
        // $status = $this->Home_Model->insertIntoTable('dealer_transections',$dealer_transection_data);
        // if($status){
        //     $data = array(
        //         'policy_no'=>$policy_details['sold_policy_no'],
        //         'dealer_id'=> $policy_details['user_id'],
        //         'transection_no' =>$transection_no,
        //         'transection_type'=> 'cr',
        //         'transection_amount'=>$policy_details['sold_policy_price_with_tax'],
        //         'transection_purpose'=>'Policy Cancelled'
        //         );
        //     $this->Home_Model->insertIntoTable('dealer_transection_statement',$data);
        //     $data = array(
        //         'policy_no'=> $policy_details['sold_policy_no'],
        //         'dealer_id'=> $policy_details['user_id'],
        //         'transection_no' => $transection_no,
        //         'transection_type'=>'dr',
        //         'transection_amount'=>$policy_details['dealer_commission'],
        //         'transection_purpose'=>'Commission diducted'
        //     );
        //     $this->Home_Model->insertIntoTable('dealer_transection_statement',$data);
        //     $policy_amount = ($policy_details['sold_policy_price_with_tax'] - $policy_details['dealer_commission']);
        //     // echo $policy_amount;
        //     // echo $policy_details['credit_amount'];
        //     $dealer_wallet_data = array(
        //         'credit_amount' => ($policy_details['credit_amount'] - $policy_amount)
        //     );
        //     // echo '<pre>';print_r($dealer_wallet_data);die('hello');
        //     $where = array('dealer_id'=>$policy_details['user_id']);
        //     $status = $this->Home_Model->updateTable('dealer_wallet',$dealer_wallet_data,$where);
        //     if($status){
        //          $set = array(
        //             'policy_status_id' => '5',
        //             'cancellation_reson' => $reason_of_cancellation,
        //             'cancellation_date' => date('Y-m-d H:i:s')
        //         );
        //         $where = array('id' => $policy_id);
        //         $status = $this->Home_Model->update_invoice_cancel_status('tvs_sold_policies', $set, $where);
        //         $return_data['status'] = isset($status) ? 'true' : 'false';
        //         $domainName = $_SERVER['HTTP_HOST'];
        //         if ($domainName != 'localhost') {
        //             $response = $this->CancelPolicyMail($policy_id);
        //         }
        //     }
        // }
        // echo json_encode($return_data);
    // }

    function approveRsaCancellation(){
        $policy_id = $this->input->post('policy_id');
        $reason_of_cancellation = $this->input->post('reason_of_cancellation');
        $policy_details = $this->Home_Model->getPolicyDetails($policy_id);
        // echo '<pre>'; print_r($policy_details);die('here');
        $where = array('id'=>$policy_details['user_id']);
        $dealer_data = $this->Home_Model->getRowDataFromTable('tvs_dealers',$where);
        $is_allowed_commission_to_bank = $dealer_data['is_allowed_commission_to_bank'];
        $policy_premium = $policy_details['sold_policy_price_with_tax'];
        $dealer_commission = $policy_details['dealer_commission'];
        $total_commission = $policy_details['total_commission'];
        $policy_effective_date = explode(' ', $policy_details['sold_policy_effective_date']);
        // print_r($policy_effective_date);die;
        $curentdate = date('Y-m-d');
        $earlier = new DateTime($policy_effective_date[0]);
        $later = new DateTime($curentdate);
        $diff = $later->diff($earlier)->format("%a");
        $final_comission='';
        if($is_allowed_commission_to_bank==1){
            // echo $dealer_commission;echo '<br>';
                $final_comission = ($total_commission - $dealer_commission);
                // echo $final_comission;echo '<br>';
                if($diff <= 15){
                    $cancelled_policy_amount = $policy_details['sold_policy_price_with_tax'];
                }else{
                    $cancel_premium = ($policy_premium / 365) * $diff;
                    $cancelled_policy_amount = $policy_premium - $cancel_premium ;                    
                }

                if( ($policy_details['credit_amount'] > 0) && ($policy_details['credit_amount'] > $cancelled_policy_amount) ){
                        $dealer_wallet_data = array(
                            'credit_amount' => ($policy_details['credit_amount'] - $cancelled_policy_amount),
                            'total_commission'=>$final_comission
                            );
                    }else{
                        $dealer_wallet_data = array(
                            'security_amount' => ($policy_details['security_amount'] + $cancelled_policy_amount),
                            'total_commission'=>$final_comission
                            );
                    }


        }else{
                if($diff <= 15){
                    $cancelled_policy_amount = $policy_details['sold_policy_price_with_tax']-$dealer_commission;
                    
                }else{
                    $cancel_premium = ($policy_premium / 365) * $diff;
                    $credit_cancel_amount = $policy_premium - $cancel_premium ;
                    $cancelled_policy_amount = ($credit_cancel_amount-$dealer_commission);
                    
                }

                 if( ($policy_details['credit_amount'] > 0) && ($policy_details['credit_amount'] > $cancelled_policy_amount) ){
                        $dealer_wallet_data = array(
                            'credit_amount' => ($policy_details['credit_amount'] - $cancelled_policy_amount)
                            );
                    }else{
                        $dealer_wallet_data = array(
                            'security_amount' => ($policy_details['security_amount'] + $cancelled_policy_amount)
                            );
                    }
                 
        }


            // echo '<pre>';print_r($dealer_wallet_data);die('hello');
            $where = array('dealer_id'=>$policy_details['user_id']);
            $status = $this->Home_Model->updateTable('dealer_wallet',$dealer_wallet_data,$where);
            // echo $status;die('  status');
            if($status){
                 $set = array(
                    'policy_status_id' => '5',
                    'cancellation_reson' => $reason_of_cancellation,
                    'cancellation_date' => date('Y-m-d H:i:s')
                );
                $where = array('id' => $policy_id);
                $status = $this->Home_Model->updateTable('tvs_sold_policies', $set, $where);
                $return_data['status'] = isset($status) ? 'true' : 'false';
                $domainName = $_SERVER['HTTP_HOST'];
                if ($domainName != 'localhost') {
                    $response = $this->CancelPolicyMail($policy_id);
                }
            }
       
        // echo $cancelled_policy_amount;die;
        $transection_no = $this->getRandomNumber('16');
        $dealer_transection_data = array(
            'dealer_id'=> $policy_details['user_id'],
            'policy_no'=> $policy_details['sold_policy_no'],
            'transection_id' => $transection_no,
            'transection_type' => 'dr',
            'transection_purpose' => 'Policy Cancelled',
            'policy_amount' =>$cancelled_policy_amount,
            'dealer_commission' =>$dealer_commission,
            'rsa_commission' =>$policy_details['rsa_commission_amount'],
            'pa_ic_commission' =>$policy_details['pa_ic_commission_amount'],
            'oem_commission' =>$policy_details['oem_commission_amount'],
            'brocker_commission' =>$policy_details['brocker_commission_amount'],
        );
        // echo '<pre>'; print_r($dealer_transection_data);die('here');
        $status = $this->Home_Model->insertIntoTable('dealer_transections',$dealer_transection_data);
        if($status){
            $data = array(
                'policy_no'=>$policy_details['sold_policy_no'],
                'dealer_id'=> $policy_details['user_id'],
                'transection_no' =>$transection_no,
                'transection_type'=> 'cr',
                'transection_amount'=>$cancelled_policy_amount,
                'transection_purpose'=>'Policy Cancelled'
                );
            // echo '<pre>'; print_r($data);die('here');
            $this->Home_Model->insertIntoTable('dealer_transection_statement',$data);
            $data = array(
                'policy_no'=> $policy_details['sold_policy_no'],
                'dealer_id'=> $policy_details['user_id'],
                'transection_no' => $transection_no,
                'transection_type'=>'dr',
                'transection_amount'=>$dealer_commission,
                'transection_purpose'=>'Commission diducted'
            );
            $this->Home_Model->insertIntoTable('dealer_transection_statement',$data);
                    
        }
        echo json_encode($return_data);
        
    }
    function CancelPolicyMail($policy_id){
    // echo $policy_id;die('policy_id');
    $cancel_policy_data = $this->Home_Model->getPolicyDetails($policy_id);
    // echo"<pre>"; print_r($cancel_policy_data);die('asdas');
        $this->load->library('email');
        $from = 'info@indicosmic.com';
        $fname = $cancel_policy_data['fname'];
        $lname = $cancel_policy_data['lname'];
        $gender = $cancel_policy_data['gender'];
        if($gender=='male'){
            $salutation = 'MR.';
        }else{
            $salutation = 'Miss.';
        }
        $customer_name = $fname . ' ' . $lname;
        // echo $customer_name;die('customer_name');
        $sold_policy_no = $cancel_policy_data['sold_policy_no'];
        $customer_mail = $cancel_policy_data['email'];
        $this->email->from($from, "TVS-RSA");
        $this->email->to($customer_mail);
        $this->email->bcc('info@indicosmic.com');
        $msg = "Dear $salutation $customer_name,
                Your RSA Policy number $sold_policy_no has been Cancelled .In case of any queries or assistance, please Contact your dealer.
                Warm Regards,

                Team ICPL";

        $this->email->subject('TVS RSA Cancel POLICY');
        $this->email->message($msg);
        if ($this->email->send()) {
            $result['status'] = true;
        } else {
            $to = $customer_mail;
            mail($to, 'test', 'Other sent option failed');
            echo $customer_mail;
            show_error($this->email->print_debugger());
            $result['status'] = false;
        }
        return $result;
}
    public function rejectRsaCancellation(){
        $policy_id = $this->input->post('policy_id');
        $set = array('policy_status_id' => '3');
        $where = array('id' => $policy_id);
        $status = $this->Home_Model->updateTable('tvs_sold_policies', $set, $where);
        $return_data['status'] = isset($status) ? 'true' : 'false';
        echo json_encode($return_data);
    }

     public function getReasonOfCancellationPolicy() {
        $policy_id = $this->input->post('policy_id');
        $reson = $this->db->select('cancellation_reson,cancelation_reason_type,cancel_file_name')->from('tvs_sold_policies')->where('id', $policy_id)->get()->row_array();
       //print $this->db->last_query();exit;
        // echo "<pre>"; print_r($reson); echo "</pre>"; die('end of line yoyo');
        echo json_encode($reson);
    }
    public function cancelPoliciesAjax() {
        $requestData = $_REQUEST;
        $start_date =  $requestData['start_date'];
        $end_date =  $requestData['end_date'];
        $cancel_policy_status = ($requestData['cancel_policy_status']) ? $requestData['cancel_policy_status'] : 4 ;
        
        $where="";
        if($end_date!='' && $start_date!=''){
           $where = "AND (CAST(tsp.sold_policy_date AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        }
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'product_type_name',
            1 => 'plan_name',
            2 => 'sold_policy_no',
            3 => 'fname',
            4 => 'engine_no',
            5 => 'chassis_no',
            6 => 'created_date',
        );
        $session_data = $this->session->userdata('user_session');

        $admin_session=$this->session->userdata('admin_session');
        $admin_role=$admin_session['admin_role'];

        //print $admin_role;exit;
        $user_id = $session_data['agent_detail']->id;
        $limit = ' LIMIT ' . $start . ', ' . $length . '';

        $sql = "SELECT tsp.id,tsp.policy_status_id,tsp.product_type_name,tsp.plan_name,tsp.sold_policy_no,tsp.engine_no,tsp.chassis_no,tsp.created_date,tsp.cancellation_reson,tsp.created_date,tcd.fname,td.`dealer_code`,td.`sap_ad_code`,td.`dealer_name`,tic.`name`,tsp.cancellation_date FROM tvs_sold_policies AS tsp"
            . " LEFT JOIN tvs_customer_details AS tcd "
            . " ON tcd.id = tsp.customer_id "." JOIN tvs_dealers td ON td.id = tsp.`user_id` "
            ."LEFT JOIN tvs_insurance_companies tic ON tic.`id` = tsp.`ic_id`"
            . " WHERE tsp.`cancellation_reson` IS NOT NULL AND (tsp.policy_status_id = '$cancel_policy_status' ) $where and tsp.user_id <> 0 ";
        //echo $sql;die;
        $totalFiltered = $totalData;
        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY tsp.id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        $data = array();
        $i = 1; $link='';
        foreach ($result as $main) {
            $created_date = explode(' ', $main->created_at);
            $created_date = $created_date[0];
            if ($main->policy_status_id == '4') {
                $link = '<span onclick=confirmRsaCancelation(' . $main->id . ') class="btn btn-info">Approve Cancellation</span><br/>';
            }elseif($main->policy_status_id == '5') {
                $link = '<span class="btn btn-info">Policy is cancelled</span><br/>';
            }
            $row = array();
            $row[] = $i++;
            $row[] = $main->sap_ad_code;
            $row[] = $main->dealer_name;
            $row[] = $main->product_type_name;
            $row[] = $main->name;
           
            $row[] = $main->plan_name;
            $row[] = $main->sold_policy_no;
            $row[] = $main->fname;
            $row[] = $main->engine_no;
            $row[] = $main->chassis_no;
            $row[] = $main->created_date;
            $row[] = $main->cancellation_reson;
            $row[] = $main->cancellation_date;
            $file_list = $link;
            $row[] = $file_list;
            
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

    public function ViewPoliciesAjax() {
        $admin_session=$this->session->userdata('admin_session');
        $ic_id=$admin_session['ic_id'];
        $admin_role=$admin_session['admin_role'];
        $role_id=$admin_session['admin_role_id'];

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

       
        if($admin_role=='rm_admin'){
            $policy_data = $this->Home_Model->getRMPolicyDetail($where,$role_id);
        }
        else{
            $policy_data = $this->Home_Model->getRsaPolicyDetail($where);
        }
       
// echo '<pre>';print_r($policy_data);die;
        $totalFiltered = $totalData;

        $totalFiltered = $policy_data['num_rows'];
        $data = array();
        $i = 1;
        foreach ($policy_data['result'] as $main) {
            $policy_start_date=$main['sold_policy_effective_date'];
            $policy_end_date=$main['sold_policy_end_date'];
            if(!empty($ic_id)){
                if($ic_id==1||$ic_id==11){
                    $policy_start_date = $main['sold_policy_effective_date'];
                    $policy_end_date = $main['sold_policy_end_date'];
                }else{
                    $policy_start_date = $main['pa_sold_policy_effective_date'];
                    $policy_end_date = $main['pa_sold_policy_end_date'];
                }
            }
           
            $endrose_button = '';
            $cancel_policy = ''; 
            if(in_array($admin_role, array('opreation_admin','admin_master','kotak_admin','bhartiaxa_admin','tataaig_admin','icici_admin') )){
                $endrose_button = '<a title="Endorse" href="' . base_url() . 'admin/endorse_by_admin/' . $main['sold_id'] . '" class="btn btn-info"><i class="fa fa-edit"></a>';
            }
            if(in_array($admin_role, array('opreation_admin','admin_master') ) ){
                $cancel_policy = '<button title="Cancel-Policy" class="btn btn-warning" onclick="cancelPolicy_by_admin('.$main['sold_id'].')"><i class="fa fa-ban" aria-hidden="true"></i></button>';
            }

            
             if($main['policy_status_id'] == 4){
                $cancel_policy = '';
            }
          
            

            switch ($main['ic_id']) {
                case 2:
                     $file_list = '<a href="' . base_url() . 'download_kotak_full_policy/' .$main['sold_id']. '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                    $pa_ic = 'Kotak';
                    break;
                case 5:
                     $file_list = '<a href="' . base_url() . 'download_icici_full_policy/' . $main['sold_id'] . '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                $pa_ic = 'ICICI Lombard';
                    break;
                case 7:
                     $file_list = '<a href="' . base_url() . 'download_hdfc_policy/' .$main['sold_id']. '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                $pa_ic = 'HDFC';
                    break;
				case 8:
                     $file_list = '<a href="' . base_url() . 'download_reliance_policy/' .$main['sold_id']. '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                $pa_ic = 'Reliance';
                    break;					
                case 9:
                     $file_list = '<a href="' . base_url() . 'download_tata_full_policy/' .$main['sold_id']. '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                $pa_ic = 'TATA AIG';
                    break;
                case 10:
                     $file_list = '<a href="' . base_url() . 'download_OICL_pdf/' .$main['sold_id']. '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                    $pa_ic = 'OICL';
                    break;
                case 12:
                     $file_list = '<a href="' . base_url() . 'download_bhartiaxa_full_policy/' .$main['sold_id']. '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                $pa_ic = 'Bharti AXA';
                    break;
				case 13:
                     $file_list = '<a href="' . base_url() . 'download_liberty_policy/' .$main['sold_id']. '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
                $pa_ic = 'Liberty';
                    break;
                case 0 :
                $file_list = '<a href="' . base_url() . 'download_rsa_pdf/' .$main['sold_id']. '" class="btn btn-info" target="_blank">Download Pdf</a>';
                    $pa_ic = ""; 
                break;          
                
            }
            if($main['plan_id']==65){
                $file_list = '<a href="' . base_url() . 'download_workshop_OICL_pdf/' .$main['sold_id']. '" class="btn btn-info" target="_blank"><i class="fa fa-download"></i></a><br/>';
                    $pa_ic = "OICL"; 
            }

            if(!empty($main['vehicle_type'])){
                    $vehicle_type = $main['vehicle_type'] ;
            }else{
                    $reg_no_arr = explode('-',$main['vehicle_registration_no']) ;
                    if(isset($reg_no_arr[2]) && !empty($reg_no_arr[2]) ){
                        $vehicle_type = "Old";
                    }else{
                        $vehicle_type = "New";
                    }
                }

            $row = array();
            $row[] = $i++;
            $row[] = $main['sold_policy_no'];
            if($admin_role!='service_admin'){
                    $row[] = $main['master_policy_no'];
                    $row[] = $pa_ic;
            }
            $row[] = $main['engine_no'];
            $row[] = $vehicle_type;
            $row[] = $main['chassis_no'];
            $row[] = $main['model_name'];
            $row[] = $main['sap_ad_code'];
            $row[] = $main['dealer_name'];
            $row[] = $main['customer_name'];
            $row[] = $main['plan_name'];
            if(($ic_id==10 && $admin_role=='oriental_admin2') && ($main['plan_name']=='Sapphire'||$main['plan_name']=='Platinum')){
                $main['basic_premium']=140;
                $main['gst_amount']=25.2;
                $main['total_premium']=$main['basic_premium']+$main['gst_amount'];
            }
            else if(($ic_id==10 && $admin_role=='oriental_admin2') && ($main['plan_name']=='Gold')){
                $main['basic_premium']=95;
                $main['gst_amount']=17.1;
                $main['total_premium']=$main['basic_premium']+$main['gst_amount'];
            }
            else if(($ic_id==10 && $admin_role=='oriental_admin2') && ($main['plan_name']=='Silver')){
                $main['basic_premium']=50;
                $main['gst_amount']=9;
                $main['total_premium']=$main['basic_premium']+$main['gst_amount'];
            }
            
            if($main['plan_id']==62){
                $main['basic_premium']='';
                $main['gst_amount']='';
                $main['total_premium']='';
                $main['dealer_commission']='';
            }
            if( ($admin_role!='oriental_admin')  && $admin_role!='service_admin'){
                if(in_array($admin_role, array('oriental_admin2','tvs_admin','opreation_admin','zone_code','account_admin','admin_master','dashboard_admin','kotak_admin') ) ){
                $row[] = $main['basic_premium'];
                $row[] = $main['gst_amount'];
                $row[] = $main['total_premium'];
                $row[] = $main['dealer_commission'];
            } }

            if($ic_id==10){
                $row[] = $main['policy_status'];
            }

            $row[] = $main['zone'];
            $row[] = $main['created_date'];
            $row[] = $policy_start_date;
            $row[] = $policy_end_date;
            $row[] = $main['city_name'];          
            $row[] = $main['state_name'];
            if($admin_role!='service_admin'){          
                $row[] = $file_list.$cancel_policy.$endrose_button;
            }

            $data[] = $row;
        }

        //print '<pre>';
        //print_r($data);
        //exit;

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    function EdorsePolicyBYAdmin($policyid){
        if(!empty($policyid)){
            $data['policy_data'] = $this->Home_Model->getPolicyById($policyid);
            // echo '<pre>';print_r($data);die('dadta');
            $admin_role = $this->session->userdata('admin_session')['admin_role'];
            // echo '<pre>';print_r($admin_role);die('dadta');
            $paic_readonly = '';
            $paic_disable = '';
            if(in_array($admin_role, array('kotak_admin','bhartiaxa_admin','tataaig_admin','icici_admin') )){
                $data['paic_readonly'] = 'readonly';
                $data['paic_disable'] = 'disabled';
            }
            $veh_reg_no = explode('-', $data['policy_data']['vehicle_registration_no']);
            $data['rto_name'] = $veh_reg_no[0];
            $data['rto_code1'] = $veh_reg_no[1];
            $data['rto_code2'] = $veh_reg_no[2];
            $data['reg_no'] = $veh_reg_no[3];
            $data['engine_no'] = $data['policy_data']['engine_no'];
            $data['chassis_no'] = $data['policy_data']['chassis_no'];
            $data['model_name'] = $data['policy_data']['model_name'];
            $data['pincode'] = $data['policy_data']['pincode'];
            $data['city'] = $data['policy_data']['city'];
            $data['state'] = $data['policy_data']['state'];
            $data['addr1'] = $data['policy_data']['addr1'];
            $data['addr2'] = $data['policy_data']['addr2'];
            $data['dob'] = $data['policy_data']['dob'];
            $data['gender'] = $data['policy_data']['gender'];
            $data['mobile_no'] = $data['policy_data']['mobile_no'];
            $data['email'] = $data['policy_data']['email'];
            $data['fname'] = $data['policy_data']['fname'];
            $data['lname'] = $data['policy_data']['lname'];
            $data['nominee_full_name'] = $data['policy_data']['nominee_full_name'];
            $data['nominee_relation'] = $data['policy_data']['nominee_relation'];
            $data['nominee_age'] = $data['policy_data']['nominee_age'];
            $data['appointee_full_name'] = $data['policy_data']['appointee_full_name'];
            $data['appointee_relation'] = $data['policy_data']['appointee_relation'];
            $data['appointee_age'] = $data['policy_data']['appointee_age'];
            $data['plan_type_id'] = $data['policy_data']['plan_type_id'];
            $data['plan_id'] = $data['policy_data']['plan_id'];
            $data['policy_id'] = $data['policy_data']['policy_id'];
            $data['customer_id'] = $data['policy_data']['customer_id'];
            $data['readonly'] = 'readonly';
            $data['disabled'] = 'disabled';
            if(!empty($data['policy_data']['appointee_full_name']) && !empty($data['policy_data']['appointee_relation'])){
                $data['display'] = 'display: block' ;
            }else{
                $data['display'] = 'display: none' ;
            }

        }
        // echo '<pre>';print_r($data);die('data');
        $data['main_contain'] = 'admin/report/endorse_policy_admin';
        $this->load->view('admin/includes/template', $data);
    }

    function PostEndorsementData(){
        $post = $this->input->post();
        $model_name = $this->getMakeModelName('model', $post['model_id']);
        $rto_name = $post['rto_name'];
        $rto_code1 = $post['rto_code1'];
        $rto_code2 = $post['rto_code2'];
        $reg_no = $post['reg_no'];
        $final_reg_no = $rto_name . '-' . $rto_code1 . '-' . $rto_code2 . '-' . $reg_no;
        // echo '<pre>';print_r($post);die('data');
        $customer_data = array(
            'fname' => $post['first_name'],
            'lname' => $post['last_name'],
            'email' => $post['email'],
            'mobile_no' => $post['mobile_no'] ,
            'gender' => $post['gender'],
            'dob' => $post['dob'],
            'addr1' => $post['cust_addr1'],
            'addr2' => $post['cust_addr2'],
            'state' => $post['state_id'],
            'city' =>  $post['city_id'],
            'state_name' => $post['state'],
            'city_name' => $post['city'],
            'pincode' =>  $post['pin'],
            'nominee_full_name' =>  $post['nominee_full_name'],
            'nominee_relation' =>  $post['nominee_relation'],
            'nominee_age' =>  $post['nominee_age'],
            'appointee_full_name' => ($post['nominee_full_name']) ? $post['nominee_full_name'] : '',
            'appointee_relation' => ($post['appointee_relation']) ? $post['appointee_relation'] : '',
            'appointee_age' => ($post['appointee_age']) ? $post['appointee_age'] : '',
            'updated_at' => date('Y-m-d H:i:s')

        );
        $pollicy_data = array(
            'engine_no' => $post['engine_no'],
            'chassis_no' => $post['chassis_no'],
            'model_id' => $post['model_id'],
            'model_name' => $model_name ,
            'vehicle_registration_no' => $final_reg_no,
            'updated_date' => date('Y-m-d H:i:s')
        );
        // echo '<pre>';print_r($pollicy_data);die('update');
        
        if(!empty($post['hid_customer_id']) && !empty($post['hid_policy_id']) ){
            $this->db->where("id",$post['hid_customer_id']);
            $updated_cust = $this->db->update("tvs_customer_details", $customer_data);
            $this->db->where("id",$post['hid_policy_id']);
            $update_data = $this->db->update("tvs_sold_policies", $pollicy_data);
            if(!empty($updated_cust) && !empty($update_data) ){
                $this->session->set_flashdata('message','Endorsement Done !');
            }else{
                $this->session->set_flashdata('message','Something Went Wrong Please Try Again');
            }
        }
        redirect('admin/view_policies');
        // echo '<pre>';print_r($update_data);
    }

    function fetchStateCityBypincode(){
        $pin = $this->input->post('data');
        $response_data = $this->Home_Model->fetchStateCity($pin);
        $return_data['state'] = $response_data['state_name'];
        $return_data['city'] = $response_data['district_name'];
        $return_data['state_id'] = $response_data['state_id'];
        $return_data['city_id'] = $response_data['city_id'];
        echo json_encode($return_data);
    }

function RequestcancelpolicyByAdmin() {

        $post_data = $this->input->post();
       // echo '<pre>'; print_r($_FILES);
       // echo "<pre>"; print_r($post_data);die;
        if(!empty($post_data['reason_of_cancelation']) && !empty($post_data['cancel_upload_reason']) && !empty($_FILES['cancel_upload_file']['name']) ){

       $upload_PATH_NAME = './uploads/cancel_upload_file/';
        if ($_FILES['cancel_upload_file']['name'] != "") {
            $config = array(
              'upload_path' => $upload_PATH_NAME,
              'allowed_types' => 'jpg|png|jpeg|pdf'           
            );
            $this->load->library('upload', $config);
            //$this->upload->initialize($config);
            $upload = $this->upload->do_upload('cancel_upload_file');
            // echo '<pre>';print_r($upload);
            $get_uploaded_name = $this->upload->data('file_name');
            // echo "<pre>"; print_r($get_uploaded_name); echo "</pre>"; die('end of line yoyo');
            $where = array('id' => $post_data['policy_id']);
            $data = array(
                'cancellation_reson' => $post_data['reason_of_cancelation'],
                 'policy_status_id' => '4',
                 'cancelation_reason_type' => $post_data['cancel_upload_reason'],
                 'cancel_file_name' => $get_uploaded_name,
                 'cancel_request_date' => date('Y-m-d H:i:s')
             );
            $status = $this->Home_Model->updateTable('tvs_sold_policies', $data, $where);
            
        }
        if ($status) {
            $this->session->set_flashdata('message','Cancellation Request Is Done');
        }else{
            $this->session->set_flashdata('message','Something Went Wrong,Please Try Again.');
        }
    }else{
            $this->session->set_flashdata('message','Please Fill all the input fields.');
    }
    redirect('admin/view_policies');
    }

    public function getModel() {
       $model =  $this->input->post('model');
       $model = isset($model)?$model:'';
       // echo '<pre>'; print_r($post_data);die('hello');
        $policyid= $this->input->post('hid_policy_id');
        $policy_data = $this->Home_Model->getPolicyById($policyid);
        // echo '<pre>';print_r($policy_data);die;
        $selected_model = $policy_data['model_name'];
        $models = $this->Home_Model->getDataFromTableWithOject('tvs_model_master');
        $data['html'] = "<option value='' >SELECT MODEL</option>";
        if (!empty($models)) {
            foreach ($models as $row) {
                $select = '';
                if ((strtolower(trim($selected_model)) == strtolower($row->model_name)) || (strtolower(trim($model)) ==  strtolower($row->model_name)))
                 {
                    $select = 'selected';
                    $data['model_name'] = $row->model_name;
                }
                $data['html'] .= "<option class'model_list' value='" . $row->model_id . "' " . $select . " >" . $row->model_name . "</option>";
            }
        }

        echo json_encode($data);
    }

    /*Start Active Dealers List*/
    function activeDealers() {
        $data['main_contain'] = 'admin/report/active_dealers';
        $this->load->view('admin/includes/template', $data);
    }

    function activeDealersAjax() {
        $admin_session = $this->session->userdata('admin_session');
        $zone_code = $admin_session['admin_role_id'];
        $admin_role = $admin_session['admin_role'];
        $where1 = '';
        $join = '';
        if(!empty($zone_code) && $admin_role == 'zone_code'){
            $where1  = 'AND tdzm.zone_code = '.$zone_code.'';
            $join = 'INNER JOIN tvs_dealer_zone_mapper AS tdzm ON tdzm.id = td.zone_id';
        }
        $requestData = $_REQUEST;
        $start_date =  $requestData['start_date'];
        $end_date =  $requestData['end_date'];
        $where="";
        if($end_date!='' && $start_date!=''){
           $where = "AND (CAST(dw.deposit_date AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        }
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'dealer_code',
            1 => 'dealer_name',
            2 => 'sap_ad_code',
            3 => 'mobile',
            4 => 'location',
        );
        $sql = "SELECT td.*,td.id AS tvs_dealer_id,dw.* FROM tvs_dealers td 
        INNER JOIN dealer_wallet AS dw ON td.id = dw.dealer_id 
        $join
        WHERE td.id != 2871 
        $where1 $where ";
        //die($sql);
        $totalFiltered = $totalData;

        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY td.id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        $data = array();
        $i = 1;
        foreach ($result as $main) {
            if($main->sap_ad_code==$main->dealer_code){
                $dealer_type='AMD';
            }else{
                $dealer_type='AD';
            }
            $row = array();
            $row[] = $i++;
            $row[] = $main->sap_ad_code;
            $row[] = $main->dealer_name;
            $row[] = $main->dealer_type;
            $row[] = $main->state;
            $row[] = $main->location;
            $row[] = $main->deposit_date;
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

    /*End Active Dealers List*/

public function fetchStateCityNames() {
    $pin = $this->input->post('pin');
    $response_data = $this->Home_Model->fetchStateCity($pin);
    $return_data['state'] = $response_data['state_name'];
    $return_data['city'] = $response_data['district_name'];
    $return_data['state_id'] = $response_data['state_id'];
    $return_data['city_id'] = $response_data['city_id'];
    echo json_encode($return_data);
}

function getDealerDetail(){
    $post = $this->input->post();
    $dealer_data = $this->Home_Model->getDealerinfo($post['dealer_id']);
    // echo "<pre>"; print_r($dealer_data); echo "</pre>"; die('end of line yoyo');
    echo json_encode($dealer_data);

}

function SubmitupdateDealerData(){
    $post_data = $this->input->post();
    // echo "<pre>"; print_r($post_data); echo "</pre>"; die('end of line yoyo');
    $edit_data = array(
                    'dealer_name' => $post_data['company_name'],
                    'email' => $post_data['email'],
                    'ad_name' => $post_data['dealer_full_name'],
                    'landline' => $post_data['phone_no'],
                    'mobile' => $post_data['mobile_no'],
                    'tin_no' => $post_data['tin_no'],
                    'gst_no' => $post_data['gst_no'],
                    'aadhar_no' => $post_data['aadhar_no'],
                    'pan_no' => $post_data['pan_no'],
                    'add1' => $post_data['dealer_addr1'],
                    'add2' => $post_data['dealer_addr2'],
                    'pin_code' => $post_data['pin'],
                    'state' => $post_data['state'],
                    'location' => $post_data['city'],
                    'bank_name' => $post_data['bank_name'],
                    'banck_acc_name' => $post_data['acc_holder_name'],
                    'banck_acc_no' => $post_data['account_no'],
                    'banck_ifsc_code' => $post_data['ifsc_code'],
                    'account_type' => $post_data['account_type'],
                    'branch_address' => $post_data['branch_address'],
                    'company_type_id' => $post_data['company_type'],
                    'pa_ic_id' => $post_data['pa_ic_id'],
                    'updated_at' =>date("Y-m-d H:i:s")
                );

    $where = array('id' => $post_data['edit_dealer_id']);
    $status = $this->Home_Model->updateTable('tvs_dealers', $edit_data, $where);
    if(!empty($status)){
        $this->session->set_flashdata('success',"Dealer is Updated.");
        redirect('admin/dealer_master');
    }else{
        $this->session->set_flashdata('success','Something Went Wrong Please Try Again !');
        redirect('admin/dealer_master');
    }
}

    /*End Active Dealers List*/

    function DealerMaster() {
        $data['company_type'] = $this->Home_Model->getDataFromTable('company_type');
        $pa_ics_ar = $this->db->query("SELECT * FROM tvs_insurance_companies WHERE insurance_type = 'PA' AND id NOT IN (6,7,8,10,13) ")->result_array();
        $universal = array(
            'id'=>'all',
            'dms_ic_id' =>'',
            'insurance_type' =>'',
            'name'=> 'Universal',
            'display_name'=>'Universal',
            'logo'=>'',
            'email'=>'',
            'address'=>'',
            'toll_free_no'=>'',
            'certificate_no_prefix'=>'',
            'certificate_no'=>''
        );
        array_push($pa_ics_ar, $universal);
        $data['pa_ics'] = $pa_ics_ar;
        $data['main_contain'] = 'admin/report/dealer_master';
        $this->load->view('admin/includes/template', $data);
    }
    function DealerMasterAjax() {
        set_time_limit(1200);
        $requestData = $_REQUEST;
        $start_date =  $requestData['start_date'];
        $end_date =  $requestData['end_date'];
        $where="";
        if($end_date!='' && $start_date!=''){
           $where = "AND (CAST(tdd.`created_at` AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        }
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'dealer_code',
            1 => 'dealer_name',
            2 => 'sap_ad_code',
            3 => 'mobile',
            4 => 'location',
        );
        $sql = "SELECT td.*,td.id as tvs_dealer_id,COUNT(tsp.id) AS policy_count,(dw.security_amount-dw.credit_amount) AS dealer_wallet,tdd.* FROM tvs_dealers td INNER JOIN dealer_wallet dw ON td.id = dw.dealer_id LEFT JOIN tvs_dealer_documents tdd ON td.id = tdd.dealer_id LEFT JOIN tvs_sold_policies tsp ON tsp.`user_id` = td.id  GROUP BY td.id ORDER BY td.id DESC";
        // die($sql);
        $query = $this->db->query($sql);
        $result = $query->result();
        $totalFiltered = $query->num_rows();
        // echo '<pre>';print_r($result);die('here');
        $data = array();
        $i = 1;
        $tot_wallet = 0;

                
        foreach ($result as $main) {
            $reset_btn = '<a title="RESET" class="btn btn-primary" id="reset_button" onclick="ResetPassword('.$main->sap_ad_code.');"><i class="fa fa-eraser"></i></a>';
            $assign = ' <a title="Assign IC" class="btn btn-primary" onclick="AssignICView('.$main->tvs_dealer_id.')" >A-IC</a> <a title="Dealer Info" class="btn btn-primary" onclick="DealerInfo('.$main->tvs_dealer_id.')" ><i class="fa fa-info"></i></a> <a title="Exclusive IC" class="btn btn-primary" onclick="AssignExclusiveIC('.$main->tvs_dealer_id.')" ><i class="fa fa-etsy"></i></a>';
            $edit_dealer = '<a class="btn btn-primary" title="Edit Dealer" onclick="EditDealerData('.$main->tvs_dealer_id.')"><i class="fa fa-edit" ></i></a>';
            $is_gst_complaint = '<a title="Gst Complaint" onclick="updateGSTComplaint('.$main->tvs_dealer_id.')" class="btn btn-primary"><i class="fa fa-question-circle" aria-hidden="true"></i></a>';
            $where = array('id' => $main->rsa_ic_master_id);
            $get_rsa_ic_data = $this->Home_Model->getDataFromTable('tvs_insurance_companies',$where);
            $row = array();
            $agreement_bar='' ; $gst_certificate_bar = ''; $pan_card_bar=''; $cancel_cheque_bar='';

            if(isset($main->agreement)){
                $agreement_bar = '<div class="progress-bar progress-bar-success" style="width:40%">Agreement PDF</div>';
            }
            if(isset($main->gst_certificate)){
                $gst_certificate_bar = '<div class="progress-bar progress-bar-warning" style="width:20%">GST</div>';
            }
            if(isset($main->pan_card)){
                $pan_card_bar = '<div class="progress-bar progress-bar-info" style="width:20%">Pan Card</div>';
            }
            if(isset($main->cancel_cheque)){
                $cancel_cheque_bar = '<div class="progress-bar progress-bar-danger" style="width:20%">Cancel Cheque</div>';
            }

           
            if((isset($main->pan_no)) && (isset($main->banck_acc_no)) && (isset($main->banck_ifsc_code)) && (isset($main->agreement) || isset($main->gst_certificate) || isset($main->pan_card) || isset($main->cancel_cheque) ) ){

                    $progress_bar = '<a class="btn" onclick="view_document('.$main->tvs_dealer_id.')" > <div class="progress">'
                             .$agreement_bar.$gst_certificate_bar.$pan_card_bar.$cancel_cheque_bar .'
                            </div></a>';
            }else{
                $progress_bar = '';
            }
            $dealer_wallet = !empty($main->dealer_wallet)?$main->dealer_wallet:0;
            if($main->tvs_dealer_id == 2871 || $main->tvs_dealer_id == 2872){
                $dealer_wallet = 0;
            }
            $tot_wallet = $tot_wallet + $dealer_wallet;
            $row[] = $i++;
            $row[] = $main->tvs_dealer_id;
            $row[] = $get_rsa_ic_data[0]['name'];
            $row[] = $main->dealer_wallet;
            $row[] = $main->dealer_code;
            $row[] = $main->sap_ad_code;
            $row[] = $main->gst_no;
            $row[] = $main->policy_count;
            $row[] = $main->dealer_name;
            $row[] = $main->ad_name;
            $row[] = $main->mobile;
            $row[] = $main->state;
            $row[] = $main->location;
            $row[] = $main->created_at;
            $row[] = '<a title="LOGIN" class="btn btn-primary" onclick="login('.$main->tvs_dealer_id.')" ><i class="fa fa-user"></i></a> '.$reset_btn.$assign.$edit_dealer.$is_gst_complaint;
            $row[] = $progress_bar;

            $data[] = $row;
        }

        
        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "tot_wallet" => $tot_wallet
        );

        echo json_encode($json_data);
    }

function assignIC(){
    $data['main_contain'] = 'admin/report/assign_ic';
    $this->load->view('admin/includes/template', $data);
}

function SubmitDMSICData(){
    $post = $this->input->post();
    // echo '<pre>';print_r($post);die;
    $dms_data = array(
                    array('dealer_code' => $post['hid_sap_code'],
                        'dms_ic_id' => $post['dms_ic_5'],
                        'pa_ic_id' => $post['pa_ic_5']
                    ),
                    array('dealer_code' => $post['hid_sap_code'],
                        'dms_ic_id' => $post['dms_ic_6'],
                        'pa_ic_id' => $post['pa_ic_6']
                    ),
                    array('dealer_code' => $post['hid_sap_code'],
                        'dms_ic_id' => $post['dms_ic_7'],
                        'pa_ic_id' => $post['pa_ic_7']
                    ),
                    array('dealer_code' => $post['hid_sap_code'],
                        'dms_ic_id' => $post['dms_ic_8'],
                        'pa_ic_id' => $post['pa_ic_8']
                    ),
                    array('dealer_code' => $post['hid_sap_code'],
                        'dms_ic_id' => $post['dms_ic_9'],
                        'pa_ic_id' => $post['pa_ic_9']
                    ),
                    array('dealer_code' => $post['hid_sap_code'],
                        'dms_ic_id' => $post['dms_ic_10'],
                        'pa_ic_id' => $post['pa_ic_10']
                    ),
                    array('dealer_code' => $post['hid_sap_code'],
                        'dms_ic_id' => $post['dms_ic_12'],
                        'pa_ic_id' => $post['pa_ic_12']
                    ),
                    array('dealer_code' => $post['hid_sap_code'],
                        'dms_ic_id' => $post['dms_ic_13'],
                        'pa_ic_id' => $post['pa_ic_13']
                    )
            );
$where = array('dealer_code'=>$post['hid_sap_code']);
$checkExist = $this->Home_Model->getDataFromTable('dms_ic_and_pa_ic_mapping',$where);
    if(!empty($checkExist)){
// ....update...
        foreach ($dms_data as $value) {
            $update_data = array(
                        'pa_ic_id' => $value['pa_ic_id'],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
            $where = array('dealer_code' => $value['dealer_code'],
                        'dms_ic_id' => $value['dms_ic_id']);
            $status =  $this->Home_Model->updateTable('dms_ic_and_pa_ic_mapping',$update_data,$where);
          
        }
        
    }else{
        // ....insert...
        $status = $this->Home_Model->insertIntoTableBatch('dms_ic_and_pa_ic_mapping',$dms_data);
        
    }
    if($status){
        $this->session->set_flashdata('success','DMS IC is Mapped !');
        redirect('admin/dealer_master');
    }else{
        $this->session->set_flashdata('success','Something Went Wrong Please Try Again !');
        redirect('admin/dealer_master');
    }
}

function ExclusiveICView(){
$dealer_id = $this->input->post('dealer_id');
$html = '';
    if(!empty($dealer_id)){
        $where = array('id' => $dealer_id);
        $dealer_data = $this->Home_Model->getRowDataFromTable('tvs_dealers',$where);
        // $sql = "SELECT id,name FROM tvs_insurance_companies WHERE insurance_type = 'PA' AND id NOT IN (6,7,8,10,13) ORDER BY id ASC";
        // $pa_ics = $this->db->query($sql)->result_array();
        $sap_ad_code = $dealer_data['sap_ad_code'];
        $dealer_name = $dealer_data['dealer_name'];
        $where = array('dealer_code'=>$sap_ad_code);
        $sapcode_dms_logic = $this->Home_Model->getRowDataFromTable('tvs_dealers_dmsic_logic',$where);
        // echo "<pre>"; print_r($sapcode_dms_logic); echo "</pre>"; die('end of line yoyo');
        // $kotak_selected = ($sapcode_dms_logic['pa_ic_mapping']=='Kotak')?'selected':'';
        $tata_selected = ($sapcode_dms_logic['pa_ic_mapping']=='TAGIC')?'selected':'';
        $bagi_selected = ($sapcode_dms_logic['pa_ic_mapping']=='BAGI')?'selected':'';
        $univaersal_selected = ($sapcode_dms_logic['pa_ic_mapping']=='Universal Logic')?'selected':'';
        $icici_l = ($sapcode_dms_logic['pa_ic_mapping']=='ICICIL')?'selected':'';
        $oicl = ($sapcode_dms_logic['pa_ic_mapping']=='OICL')?'selected':'';
        $lgi = ($sapcode_dms_logic['pa_ic_mapping']=='LGI')?'selected':'';
        $rgl = ($sapcode_dms_logic['pa_ic_mapping']=='RGL')?'selected':'';
        
        // if($sapcode_dms_logic)
    $html .= <<<EOD
        <div class="form-control" id="">
          <div class="col-md-2">
         Dealer Name :  </div>
          <div class="col-md-4"><h5 style="color:red" id="assign_dealer_name"><b>{$dealer_name}</b></h5></div>
          <div class="col-md-2">
            Dealer Code :  </div>
          <div class="col-md-4"><h5 style="color:red" id="assign_dealer_code"><b>{$sap_ad_code}</b></h5>
          <input type="hidden" name="hid_sap_code" id="hid_sap_code" value="{$sap_ad_code}">
          </div>
          </div><br>

        <div class="row ">
        <div class="col-md-2" >Exclusive IC :</div>
        <div class="col-md-5" >
        <select class="form-control" name="exclusive_ic" required>
        <option value="">Select</option>
        <option value="TAGIC" {$tata_selected}>Tata AIG General Insurance Company</option>
        <option value="BAGI" {$bagi_selected}>Bharti AXA GI</option>
        <option value="Universal Logic" {$univaersal_selected}>Universal IC</option>
        <option value="ICICIL" {$icici_l}>ICICI Lombard</option>
        <option value="LGI" {$lgi}>Liberty General</option>
        <option value="RGL" {$rgl}>Reliance General</option>
        <option value="OICL" {$oicl}>Oriental Insurance</option>
        </select>
        </div>
        </div>
EOD;
      

    }

    echo $html;
}

function SubmitExclusiveIC(){
    // echo "<pre>"; print_r($this->input->post()); echo "</pre>"; die('end of line yoyo');
    $exclusive_ic = $this->input->post('exclusive_ic');
    $sap_code = $this->input->post('hid_sap_code');
    if(!empty($sap_code) && !empty($exclusive_ic)){
        // $sap_code = '10769';
        $where = array('dealer_code'=>$sap_code);
        $sapcode_dms_logic = $this->Home_Model->getRowDataFromTable('tvs_dealers_dmsic_logic',$where);
        if(!empty($sapcode_dms_logic)){
            // echo "<pre>"; print_r($sapcode_dms_logi  c); echo "</pre>"; die('end of line yoyo');
            $update_logic = array('pa_ic_mapping'=>$exclusive_ic,'updated_at'=>date('Y-m-d H:i:s'));
            $status = $this->Home_Model->updateTable('tvs_dealers_dmsic_logic',$update_logic,$where);
        }else{
            $where = array('sap_ad_code'=>$sap_code);
            $dealer_data = $this->Home_Model->getRowDataFromTable('tvs_dealers',$where);
            // echo "<pre>"; print_r($dealer_data); echo "</pre>"; die('end of line yoyo');
            $insert_data = array(
                'dealer_code' => $sap_code,
                'dealer_name' => $dealer_data['dealer_name'],
                'location' => $dealer_data['location'],
                'pa_ic_mapping' => $exclusive_ic,
            );
            $status = $this->Home_Model->insertIntoTable('tvs_dealers_dmsic_logic',$insert_data);
        }
        
    }
    if(!empty($status)){
        $this->session->set_flashdata('success','DMS IC Logic is Set');
    }else{
        $this->session->set_flashdata('success','Something Went Wrong,Please Try Again');
    }
    redirect('admin/dealer_master');
}

function getDealerInfo(){
    $dealer_id = $this->input->post('dealer_id');
    if(!empty($dealer_id)){
        $where = array('id' => $dealer_id);
        $dealer_data = $this->Home_Model->getRowDataFromTable('tvs_dealers',$where);
        $sql = "SELECT id,name FROM tvs_insurance_companies WHERE insurance_type = 'PA' ORDER BY id ASC";
        $tvs_ics = $this->db->query($sql)->result_array();
        // echo "<pre>"; print_r($tvs_insurance_companies); echo "</pre>"; die('end of line yoyo tvs_insurance_companies');
        $sap_ad_code = $dealer_data['sap_ad_code'];
        $dealer_name = $dealer_data['dealer_name'];
        $sql ="SELECT * FROM dms_ic_and_pa_ic_mapping AS diapim 
        INNER JOIN tvs_insurance_companies AS tic ON diapim.dms_ic_id = tic.dms_ic_id
        WHERE diapim.dealer_code = '$sap_ad_code' ORDER BY diapim.dms_ic_id ASC ";
        $dms_ic_details = $this->db->query($sql)->result_array();
      // echo '<pre>';print_r($dms_ic_details);die();


        $html = <<<EOD
        <div class="" id="">
          <div class="col-md-6">
         Dealer Name :  <h4 style="color:red" id="assign_dealer_name">{$dealer_name}</h4>
          </div>
          <div class="col-md-6">
            Dealer Code :  <h4 style="color:red" id="assign_dealer_code">{$sap_ad_code}</h4>
            <input type="hidden" name="hid_sap_code" id="hid_sap_code" value="{$sap_ad_code}">
          </div>
EOD;
      if(!empty($dms_ic_details)){

          foreach ($dms_ic_details as $dms_ic_detail) {
              $dms_ic_id = $dms_ic_detail['dms_ic_id'];
              $dms_ic_name = $dms_ic_detail['name'];
          
          $html .=<<<EOD
          <div class="row">
              <div class="col-md-6" style="">
               <input type="radio" name="dms_ic_{$dms_ic_id}" value="{$dms_ic_id}" checked>{$dms_ic_name}
              </div>
              <div class="col-md-6" style="">
                  <select class="form-control" name="pa_ic_{$dms_ic_id}">
EOD;
                  foreach ($tvs_ics as $key =>  $tvs_ic) {
                      $tvs_ic_id = $tvs_ic['id'];
                      $tvs_ic_name = $tvs_ic['name'];
                      if(!in_array($tvs_ic_id, array(6,7,2) )){
                      $selected = ($dms_ic_detail['pa_ic_id'] == $tvs_ic['id'])?'selected':'';
                  $html .=<<<EOD
                    <option value="{$tvs_ic_id}" {$selected}>{$tvs_ic_name}</option>
EOD;
                     }
                 }
                    $html .=<<<EOD
                  </select>
              </div>
            </div>
EOD;
        }
    }else{
        foreach ($tvs_ics as $tvs_ic) {
                $dms_ic_id = $tvs_ic['id'];
                $dms_ic_name = $tvs_ic['name'];
                $html .=<<<EOD
                  <div class="row">
                      <div class="col-md-6" style="">
                       <input type="radio" name="dms_ic_{$dms_ic_id}" value="{$dms_ic_id}" checked>{$dms_ic_name}
                      </div>
                      <div class="col-md-6" style="">
                          <select class="form-control" name="pa_ic_{$dms_ic_id}">
EOD;
                 foreach ($tvs_ics as $tvs_ic) {
                      $tvs_ic_id = $tvs_ic['id'];
                      $tvs_ic_name = $tvs_ic['name'];
                      if(!in_array($tvs_ic_id, array(6,7,2) )){
                  $html .=<<<EOD
                    <option value="{$tvs_ic_id}" >{$tvs_ic_name}</option>
EOD;
                     }
                }
                    $html .=<<<EOD
                  </select>
              </div>
            </div>
EOD;
            }
        // die('else');
    }

        $html .=<<<EOD
        <div class="row">
          <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to Map DMS IC ?')" >Submit</button>
        </div>
      </div>
    </div>
EOD;

    }
    echo $html;
}

 
 function getDealerDetailView(){
    $indicosmic_data = $this->Home_Model->getRowDataFromTable('tvs_indicosmic_capital');
    $indi_acc_no = $indicosmic_data['account_no'];
    // echo "<pre>"; print_r($indicosmic_data); echo "</pre>"; die('end of line yoyo');
    $dealer_id = $this->input->post('dealer_id');
    $dealer_data = $this->Home_Model->getDealerData($dealer_id);
    // echo "<pre>"; print_r($dealer_data); echo "</pre>"; die('end of line yoyo');
    $dealer_name = ($dealer_data['dealer_name'])?strtoupper($dealer_data['dealer_name']):'';
    $ad_name = ($dealer_data['ad_name'])?strtoupper($dealer_data['ad_name']):'';
    $dealer_code = ($dealer_data['sap_ad_code'])?strtoupper($dealer_data['sap_ad_code']):'';
    $address = strtoupper($dealer_data['add1'].' '.$dealer_data['add2']);
    $city = ($dealer_data['location'])?strtoupper($dealer_data['location']):'';
    $state = ($dealer_data['state'])?strtoupper($dealer_data['state']):'';
    $contact_no = ($dealer_data['mobile'])?$dealer_data['mobile']:'';
    $email = ($dealer_data['email'])?strtoupper($dealer_data['email']):'';
    $gst_no = ($dealer_data['gst_no'])?strtoupper($dealer_data['gst_no']):'';
    $pan_no = ($dealer_data['pan_no'])?strtoupper($dealer_data['pan_no']):'';
    $banck_acc_no = ($dealer_data['banck_acc_no'])?strtoupper($dealer_data['banck_acc_no']):'';
    $banck_acc_name = ($dealer_data['banck_acc_name'])?strtoupper($dealer_data['banck_acc_name']):'';
    $bank_name = ($dealer_data['bank_name'])?strtoupper($dealer_data['bank_name']):'';
    $banck_ifsc_code = ($dealer_data['banck_ifsc_code'])?strtoupper($dealer_data['banck_ifsc_code']):'';
    // $balance_amount = ($dealer_data['balance_amount'])?$dealer_data['balance_amount']:0;
    $opening_balance = ($dealer_data['opening_balance'])?$dealer_data['opening_balance']:0;
    $total_deposit_amount = ($dealer_data['total_deposit_amount'])?$dealer_data['total_deposit_amount']:0;
    $total_credit_amount = ($dealer_data['total_credit_amount'])?$dealer_data['total_credit_amount']:0;
    $c_n_amount = ($dealer_data['c_n_amount'])?$dealer_data['c_n_amount']:0;
    $net_credit_amt = $opening_balance+$total_credit_amount + $c_n_amount ;
    $bank_debit_amount = ($dealer_data['bank_debit_amount'])?$dealer_data['bank_debit_amount']:0;
    $policy_debit_amount = ($dealer_data['policy_debit_amount'])?$dealer_data['policy_debit_amount']:0;
    $total_debit_amount = ($dealer_data['total_debit_amount'])?$dealer_data['total_debit_amount']:0;
    $balance_amount = $net_credit_amt - $total_debit_amount ;
    $achive_till_date_sapphire = ($dealer_data['achive_till_date_sapphire'])?$dealer_data['achive_till_date_sapphire']:0;
    $achive_till_date_gold = ($dealer_data['achive_till_date_gold'])?$dealer_data['achive_till_date_gold']:0;
    $achive_till_date_platinum = ($dealer_data['achive_till_date_platinum'])?$dealer_data['achive_till_date_platinum']:0;
    $achive_till_date_silver = ($dealer_data['achive_till_date_silver'])?$dealer_data['achive_till_date_silver']:0;
    $commission_generated_gold = ($dealer_data['commission_generated_gold'])?$dealer_data['commission_generated_gold']:0;
    $commission_generated_silver = ($dealer_data['commission_generated_silver'])?$dealer_data['commission_generated_silver']:0;
    $commission_generated_sapphire = ($dealer_data['commission_generated_sapphire'])?$dealer_data['commission_generated_sapphire']:0;
    $commission_generated_platinum = ($dealer_data['commission_generated_platinum'])?$dealer_data['commission_generated_platinum']:0;
    $product_cn_amount_gold = ($dealer_data['product_cn_amount_gold'])?$dealer_data['product_cn_amount_gold']:0;
    $product_cn_amount_platinum = ($dealer_data['product_cn_amount_platinum'])?$dealer_data['product_cn_amount_platinum']:0;
    $product_cn_amount_silver = ($dealer_data['product_cn_amount_silver'])?$dealer_data['product_cn_amount_silver']:0;
    $product_cn_amount_sapphire = ($dealer_data['product_cn_amount_sapphire'])?$dealer_data['product_cn_amount_sapphire']:0;
    $gold_pending_amount = ($dealer_data['gold_pending_amount'])?$dealer_data['gold_pending_amount']:0;
    $platinum_pending_amount = ($dealer_data['platinum_pending_amount'])?$dealer_data['platinum_pending_amount']:0;
    $silver_pending_amount = ($dealer_data['silver_pending_amount'])?$dealer_data['silver_pending_amount']:0;
    $sapphire_pending_amount = ($dealer_data['sapphire_pending_amount'])?$dealer_data['sapphire_pending_amount']:0;
    $gold_billed_amount = ($dealer_data['gold_billed_amount'])?$dealer_data['gold_billed_amount']:0;
    $silver_billed_amount = ($dealer_data['silver_billed_amount'])?$dealer_data['silver_billed_amount']:0;
    $platinum_billed_amount = ($dealer_data['platinum_billed_amount'])?$dealer_data['platinum_billed_amount']:0;
    $sapphire_billed_amount = ($dealer_data['sapphire_billed_amount'])?$dealer_data['sapphire_billed_amount']:0;

    $billed_generated_gold = ($dealer_data['billed_generated_gold'])?$dealer_data['billed_generated_gold']:0;
    $billed_generated_silver = ($dealer_data['billed_generated_silver'])?$dealer_data['billed_generated_silver']:0;
    $billed_generated_sapphire = ($dealer_data['billed_generated_sapphire'])?$dealer_data['billed_generated_sapphire']:0;
    $billed_generated_platinum = ($dealer_data['billed_generated_platinum'])?$dealer_data['billed_generated_platinum']:0;

    $pending_generated_gold = ($dealer_data['pending_generated_gold'])?$dealer_data['pending_generated_gold']:0;
    $pending_generated_silver = ($dealer_data['pending_generated_silver'])?$dealer_data['pending_generated_silver']:0;
    $pending_generated_sapphire = ($dealer_data['pending_generated_sapphire'])?$dealer_data['pending_generated_sapphire']:0;
    $pending_generated_platinum = ($dealer_data['pending_generated_platinum'])?$dealer_data['pending_generated_platinum']:0;
    // echo "<pre>"; print_r($dealer_data); echo "</pre>"; die('end of line yoyo');
    if(!empty($dealer_id)){
        $html = <<<EOD
        <table class="table-bordered colored_tbl" id="dealer_details" cellpadding="10" style="border-color: #7c2a0b; border-width: 10px;">
            <tr>
                <td>
                    <div class="row">
                      <div class="col-md-12">
                        
                              <table class="table table-bordered table-striped bg-white table-sm colored_tbl">
                                <tbody>
                                    <tr>
                                        <td colspan="4" style="background: #7c2a0b;">
                                            <div class="text-center">
                                                <h3 style="margin:5px 0; font-weight: bold; color: #fff;">DEALER DETAILS</h3>
                                              </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="20%">NAME</th>
                                        <td width="30%">{$dealer_name}</td>
                                        <th width="20%">DEALER CODE</th>
                                        <td width="30%">{$dealer_code}</td>
                                    </tr>
                                    <tr>
                                        <th>PRINCIPAL NAME</th>
                                        <td>{$ad_name}</td>
                                        <th>CONTACT NO</th>
                                        <td></td>
                                    </tr>
                                     <tr>
                                        <th>AUTHORIZED PERSON</th>
                                        <td></td>
                                        <th>CONTACT NO</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>EMAIL</th>
                                        <td>{$email}</td>
                                        <th>DEALER CONTACT NO</th>
                                        <td>{$contact_no}</td>
                                    </tr>
                                    <tr>
                                        <th>ADDRESS</th>
                                        <td>{$address}</td>
                                        <th>PAN NO</th>
                                        <td>{$pan_no}</td>
                                    </tr>
                                    <tr>
                                        <th >BANK NAME</th>
                                        <td >{$bank_name}</td>
                                        <th >GST NO</th>
                                        <td >{$gst_no}</td>
                                    </tr>
                                    <tr>
                                        <th>ACCOUNT NO</th>
                                        <td>{$banck_acc_no}</td>
                                        <th>STATE</th>
                                        <td>{$state}</td>
                                    </tr>
                                     <tr>
                                        <th>IFSC CODE</th>
                                        <td>{$banck_ifsc_code}</td>
                                        <th>CITY</th>
                                        <td>{$city}</td>
                                    </tr>

                                </tbody>
                              </table>
                              <table class="table table-bordered table-striped bg-white colored_tbl">
                                <tbody>
                                    <tr>
                                        <td colspan="4" style="background: #7c2a0b;">
                                            <div class="text-center">
                                                <h3 style="margin:5px 0; font-weight: bold; color: #fff;">INDICOSMIC DETAILS</h3>
                                              </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="20%">ZONE NAME</th>
                                        <td width="30%"></td>
                                        <th width="20%">RM NAME</th>
                                        <td width="30%"></td>
                                    </tr>
                                    <tr>
                                        <th>ZONE EMAIL</th>
                                        <td></td>
                                        <th>RM EMAIL</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>ZONE CONTACT NO</th>
                                        <td></td>
                                        <th>RM CONTACT NO</th>
                                        <td></td>
                                    </tr>

                                </tbody>
                              </table>
                      
                      </div>
                      
                      <div class="col-md-12">
                        <table class="table table-bordered table-striped bg-white text-center colored_tbl" style="margin-bottom:0;">
                          <thead>
                            <tr>
                                <td colspan="10" style="background: #7c2a0b;">
                                    <div class="text-center">
                                        <h3 style="margin:5px 0; font-weight: bold; color: #fff;">BUSINESS DETAIL</h3>
                                      </div>
                                </td>
                            </tr>
                            <tr>
                              <th>Sr. No.</th>
                              <th>DEPOSIT ACCOUNT NO.</th>
                              <th>Opening balance </th>
                              <th>Credit Amt</th>
                              <th>C/N Amt</th>
                              <th>Total Credit Amt</th>
                              <th>Debit Amt</th>
                              <th>Policy Issued Debit Amt</th>
                              <th>Total Debit Amt</th>
                              <th>Balance Amt </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1.</td>
                              <td>{$indi_acc_no}</td>
                              <td>{$opening_balance}</td>
                              <td>{$total_credit_amount}</td>
                              <td>{$c_n_amount}</td>
                              <td>{$net_credit_amt}</td>
                              <td>{$bank_debit_amount}</td>
                              <td>{$policy_debit_amount}</td>
                              <td>{$total_debit_amount}</td>
                              <td>{$balance_amount}</td>
                            </tr>
                            <tr>
                              <td>2.</td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                          </tbody>
                        </table>
                        <table class="table table-bordered table-striped bg-white text-center colored_tbl" style="margin-bottom:0;">
                          <thead>
                            <tr>
                              <th>Sr. No.</th>
                              <th style="text-align: left;">PRODUCT</th>
                              <th>MONTH TARGET</th>
                              <th>ACHIVE TILL DATE</th>
                              <th>MTD COMM GENERATED</th>
                              <th>PENDING BILL AMT</th>
                              <th>BILLED AMT</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1.</td>
                              <td style="text-align: left;"><b>SAPPHIRE</b></td>
                              <td></td>
                              <td>{$achive_till_date_sapphire}</td>
                              <td>{$commission_generated_sapphire}</td>
                              <td>{$pending_generated_sapphire}</td>
                              <td>{$billed_generated_sapphire}</td>
                              
                            </tr>
                            <tr>
                              <td>2.</td>
                              <td style="text-align: left;"><b>PLATINUM</b></td>
                              <td></td>
                              <td>{$achive_till_date_platinum}</td>
                              <td>{$commission_generated_platinum}</td>
                              <td>{$pending_generated_platinum}</td>
                              <td>{$billed_generated_platinum}</td>
                             
                            </tr>
                            <tr>
                              <td>3.</td>
                              <td style="text-align: left;"><b>GOLD</b></td>
                              <td></td>
                              <td>{$achive_till_date_gold}</td>
                              <td>{$commission_generated_gold}</td>
                              <td>{$pending_generated_gold}</td>
                              <td>{$billed_generated_gold}</td>
                              
                            </tr>
                            <tr>
                              <td>4.</td>
                              <td style="text-align: left;"><b>SILVER</b></td>
                              <td></td>
                              <td>{$achive_till_date_silver}</td>
                              <td>{$commission_generated_silver}</td>
                              <td>{$pending_generated_silver}</td>
                              <td>{$billed_generated_silver}</td>
                              
                            </tr>
                          </tbody>
                        </table>
                      </div>

                    </div>
                </td>
            </tr>
        </table>
EOD;
    }
echo $html;
}

  


    function loginAsDealer(){
        $dealer_id = $this->input->post('dealer_id');
        $where = array('id'=>$dealer_id);
        $dealer_data =  $this->Home_Model->getRowDataFromTable('tvs_dealers',$where);
        $return_data['status'] = 'false';
        if(!empty($dealer_data)){
            $session_data = array(
                'id'=>$dealer_data['id'],
                'dealer_name'=>$dealer_data['dealer_name'],
                'product_type_id'=>$dealer_data['product_type_id'],
                'pa_ic_id'=>$dealer_data['pa_ic_id'],
                'logged_in'=>$dealer_data['id'],
                'dealer_code'=>$dealer_data['dealer_code'],
                'sap_ad_code'=>$dealer_data['sap_ad_code'],
                'add1'=>$dealer_data['add1'],
                'add2'=>$dealer_data['add2'],
                'ad_name'=>$dealer_data['ad_name'],
                'tin_no'=>$dealer_data['id'],
                'gst_no'=>$dealer_data['gst_no'],
                'pan_no'=>$dealer_data['pan_no'],
                'bank_name'=>$dealer_data['bank_name'],
                'bank_acc_name'=>$dealer_data['bank_acc_name'],
                'bank_ifsc_code'=>$dealer_data['bank_ifsc_code'],
                'location'=>$dealer_data['location'],
                'state'=>$dealer_data['state'],
                'pin_code'=>$dealekr_data['pin_code'],
                'email'=>$dealer_data['email'],
                'rsa_ic_master_id'=>$dealer_data['rsa_ic_master_id'],
                'company_type_id'=>$dealer_data['company_type_id']
            );
            $this->session->set_userdata('user_session',$session_data);
            $return_data['status'] = 'true';
        }
        echo json_encode($return_data);    
    }


    function BankTransamction(){
        $data['main_contain'] = 'admin/report/bank_transanction';
        $this->load->view('admin/includes/template', $data);
    }

    function getDealerDocument(){
        $dealer_id = $this->input->post('dealer_id');
        // echo 'string ---'. $dealer_id;

        $dealer_doc_data = $this->Home_Model->getDealerDocumentData($dealer_id);
        if(!empty($dealer_doc_data)){
            $result = $dealer_doc_data;
            $result['status'] = true;
        }else{
            $result['status'] = false;
        }
        echo json_encode($result);
    }

    function AddDealer() {
        $data['main_contain'] = 'admin/report/add_dealer';
        $where = array('insurance_type' => 'RSA');
        $data['rmdata'] = $this->Home_Model->getRMdata();
       
        $data['rsa_ic_data'] = $this->Home_Model->getDataFromTable('tvs_insurance_companies',$where);
        $this->load->view('admin/includes/template', $data);
    }

    function SubmitDealerForm() {
        $post = $this->input->post();
        $status = '';

        if($post['dealer_code']==$post['sap_ad_code']){
            $dealer_type = 'AMD';
        }else{
             $dealer_type = 'AD';
        }
        if (!empty($post)) {
            $dealer_info = array(
                'dealer_code' => strtoupper($this->input->post('dealer_code')),
                'dealer_name' => trim($this->input->post('dealer_name')),
                'sap_ad_code' => $this->input->post('sap_ad_code'),
                'dealer_type' => $dealer_type,
                'ad_name' => trim($this->input->post('ad_name')),
                'location' => trim($this->input->post('location')),
                'state' => $this->input->post('dealer_state'),
                'password' => md5($this->input->post('sap_ad_code')),
                'rsa_ic_master_id' => $this->input->post('rsa_ic'),
                'created_at' => date('Y-m-d H:i:s')
            );
            $bizz_user = array(
                'f_name' => trim($this->input->post('dealer_name')),
                'dealer_code' => $this->input->post('sap_ad_code'),
                'employee_code' => $this->input->post('sap_ad_code'),
                'password' => md5($this->input->post('sap_ad_code')) ,
                'created_at' => date('Y-m-d H:i:s')
            );
            $where = array('sap_ad_code' => $this->input->post('sap_ad_code'));
            $check_tvs_dealer_exist = $this->Home_Model->getDataFromTable('tvs_dealers', $where);
            $where = array('employee_code' => $this->input->post('sap_ad_code') );
            $check_bizz_dealer_exist = $this->Home_Model->getDataFromTable('biz_users', $where);
            if(empty($check_tvs_dealer_exist) ){
                $tvs_dealer_id = $this->Home_Model->insertIntoTable('tvs_dealers', $dealer_info);

                    if($tvs_dealer_id){
                        $this->assigndealerrm($tvs_dealer_id,$post);
                            if(empty($check_bizz_dealer_exist)){
                                 $bizz_user_id = $this->Home_Model->insertIntoTable('biz_users', $bizz_user);
                                 if($bizz_user_id){
                                   $status = $this->MyBizzAPICall($post);
                                 }else{
                                     $this->session->set_flashdata('success', 'Something Went Wrong !');
                                     redirect('admin/dealer_master');
                                 }
                            }else{
                                $status = $this->MyBizzAPICall($post);
                            }
                     }else{
                            $this->session->set_flashdata('success', 'Something Went Wrong !');
                            redirect('admin/dealer_master');
                     }
            }else{ 
                    if(empty($check_bizz_dealer_exist)){
                          $bizz_user_id = $this->Home_Model->insertIntoTable('biz_users', $bizz_user);
                          if($bizz_user_id){
                                 // check API
                           $status = $this->MyBizzAPICall($post);
                             }else{
                                 $this->session->set_flashdata('success', 'Something Went Wrong !');
                                 redirect('admin/dealer_master');
                             }
                    }else{
                       $status = $this->MyBizzAPICall($post);

                    }
            }
           $response_data =  json_decode($status,true);
           if(!empty($response_data) && $response_data['status'] == 'true'){
                $counter = ($post['sap_ad_code']==$post['dealer_code'])?'AMD':'AD';
                $dms_ic_logic = array(
                    'dealer_code' => $post['sap_ad_code'],
                    'dealer_name' => trim($post['dealer_name']),
                    'location' => trim($post['location']),
                    'counter' =>$counter,
                    'pa_ic_mapping' => 'OICL',
                    'created_at'=>date('Y-m-d H:i:s')
                );
                $this->Home_Model->insertIntoTable('tvs_dealers_dmsic_logic',$dms_ic_logic);
                $dms_ic_mapping = array( 
                array(
                    'dealer_code' => $post['sap_ad_code'],
                    'dms_ic_id' =>5,
                    'pa_ic_id'=>10,
                    'is_active' =>1,
                    'created_at'=>date('Y-m-d H:i:s')
                ),
                array(
                    'dealer_code' => $post['sap_ad_code'],
                    'dms_ic_id' =>6,
                    'pa_ic_id'=>10,
                    'is_active' =>1,
                    'created_at'=>date('Y-m-d H:i:s')
                ),
                array(
                    'dealer_code' => $post['sap_ad_code'],
                    'dms_ic_id' =>7,
                    'pa_ic_id'=>10,
                    'is_active' =>1,
                    'created_at'=>date('Y-m-d H:i:s')
                ),
                array(
                    'dealer_code' => $post['sap_ad_code'],
                    'dms_ic_id' =>8,
                    'pa_ic_id'=>10,
                    'is_active' =>1,
                    'created_at'=>date('Y-m-d H:i:s')
                ),
                array(
                    'dealer_code' => $post['sap_ad_code'],
                    'dms_ic_id' =>9,
                    'pa_ic_id'=>10,
                    'is_active' =>1,
                    'created_at'=>date('Y-m-d H:i:s')
                ),
                array(
                    'dealer_code' => $post['sap_ad_code'],
                    'dms_ic_id' =>10,
                    'pa_ic_id'=>10,
                    'is_active' =>1,
                    'created_at'=>date('Y-m-d H:i:s')
                ),
                array(
                    'dealer_code' => $post['sap_ad_code'],
                    'dms_ic_id' =>12,
                    'pa_ic_id'=>10,
                    'is_active' =>1,
                    'created_at'=>date('Y-m-d H:i:s')
                ),
                array(
                    'dealer_code' => $post['sap_ad_code'],
                    'dms_ic_id' =>13,
                    'pa_ic_id'=>10,
                    'is_active' =>1,
                    'created_at'=>date('Y-m-d H:i:s')
                )                

        );
                foreach ($dms_ic_mapping as $dms_ic) {
                    $insert_data = array(
                        'dealer_code' => $dms_ic['dealer_code'],
                        'dms_ic_id' =>$dms_ic['dms_ic_id'],
                        'pa_ic_id'=>$dms_ic['pa_ic_id'],
                        'is_active' =>1,
                        'created_at'=>date('Y-m-d H:i:s')
                    );
                    $this->Home_Model->insertIntoTable('dms_ic_and_pa_ic_mapping',$insert_data);
                }
                $this->session->set_flashdata('success', 'Dealer Is Successfully Added.');
                redirect('admin/dealer_master');
           }else{
                $this->session->set_flashdata('success', $response_data['msg']);
                redirect('admin/dealer_master');
           }
        }
    }

    function assigndealerrm($tvs_dealer_id,$post){
        $rmdealer = array('dealer_id'=>$tvs_dealer_id,
                      'dealer_name'=>trim($post['dealer_name']),
                      'rm_id'=>$post['rm'],
                      'rm_name'=>$post['rmname'],
                     'sap_ad_code'=>$post['sap_ad_code'],
                      'is_active'=>1
        );


        $insert_res=$this->Home_Model->insertIntoTable("tvs_rm_dealers",$rmdealer);
    }

    function MyBizzAPICall($post){
        $data_string = json_encode($post);
        $url = 'https://www.mybizznow.com/tvs/api_call.php';
        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_RETURNTRANSFER => true ,
            CURLOPT_HTTPHEADER =>array(
                     'Content-Type: application/json',
                 'Content-Length: ' . strlen($data_string))
        ));
        $resp = curl_exec($curl);
        curl_close($curl);

        return $resp;
    }

    function ViewCancelPolicies() {
        $data['main_contain'] = 'admin/report/view_cancelpolicies';
        $this->load->view('admin/includes/template', $data);
    }

    function CancelPolicyAjax() {
        $requestData = $_REQUEST;

        // print_r($start_date) ;die('date');
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'invoice_no',
            1 => 'invoice_date',
            2 => 'engine_no',
            3 => 'chassis_no',
            4 => 'certificate_no',
        );
        $session_data = $this->session->userdata('user_session');
        $user_id = $session_data['agent_detail']->id;
        $limit = ' LIMIT ' . $start . ', ' . $length . '';
        $sql = "SELECT pp.*,pd.dealer_code,pd.dealer_name,pd.city,pd.state,pd.contact_person,pd.`contact_no` FROM pa_policy pp LEFT JOIN pa_dealers pd ON pd.id = pp.user_id ";
// print_r($sql);die;


        $totalFiltered = $totalData;

        $query = $this->db->query($sql);

        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
// echo "<pre>";print_r($result);die;
        $data = array();
        $i = 1;
        foreach ($result as $main) {

            $created_date = explode(' ', $main->created_at);
            $created_date = $created_date[0];
            $status = ($main->status == 2) ? 'cancelled' : 'Active';

            if ($status == 'cancelled') {
                $status_button = '<button onclick="active_popup(' . $main->id . ')" class="btn btn-info" type="button">Activate</button>';
            } else {
                $status_button = '<button onclick="cancel_popup(' . $main->id . ')" class="btn btn-info" type="button">Cancel</button>';
            }

            $row = array();
            $row[] = $i++;
            $row[] = $main->invoice_no;
            $row[] = $main->invoice_date;
            $row[] = $created_date;
            $row[] = $main->engine_no;
            $row[] = $main->chassis_no;
            $row[] = $main->certificate_no;
            $row[] = $main->dealer_code;
            $row[] = $main->dealer_name;
            $row[] = $status;

            // $row[] = '<a href="' . base_url() . 'admin/cancel_policy/' . $main->id . '" class="btn btn-info">Cancel</a>';
            $row[] = $status_button;


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

    function CancelPolicy() {

        if (!empty($this->input->post('hidden_policyid'))) {

            $status = array('status' => 2);
            $this->db->where("id", $this->input->post('hidden_policyid'));
            $update_status = $this->db->update("pa_policy", $status);
            (!empty($update_status)) ? $this->session->set_flashdata('cancelled', ' Your policy is Cancelled') : 'Error';
        }

        redirect('admin/cancel_policies');
    }

    function ActivePolicy() {
        if (!empty($this->input->post('policyid'))) {

            $status = array('status' => 1);
            $this->db->where("id", $this->input->post('policyid'));
            $update_status = $this->db->update("pa_policy", $status);
            (!empty($update_status)) ? $this->session->set_flashdata('activated', ' Your policy is Activated') : 'Error';
        }

        redirect('admin/cancel_policies');
    }

    function InActiveDealers() {
        $data['company_type'] = $this->Home_Model->getDataFromTable('company_type');
        $pa_ics_ar = $this->db->query("SELECT * FROM tvs_insurance_companies WHERE insurance_type = 'PA' AND id NOT IN (6,7,8,10,13) ")->result_array();
        $universal = array(
            'id'=>'all',
            'dms_ic_id' =>'',
            'insurance_type' =>'',
            'name'=> 'Universal',
            'display_name'=>'Universal',
            'logo'=>'',
            'email'=>'',
            'address'=>'',
            'toll_free_no'=>'',
            'certificate_no_prefix'=>'',
            'certificate_no'=>''
        );
        array_push($pa_ics_ar, $universal);
        $data['pa_ics'] = $pa_ics_ar;
        $data['main_contain'] = 'admin/report/inactive_dealers';
        $this->load->view('admin/includes/template', $data);
    }

    function InActiveDealersAjax() {
        $requestData = $_REQUEST;

        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'dealer_code',
            1 => 'dealer_name',
            2 => 'sap_ad_code',
            3 => 'ad_name',
            4 => 'contact_no',
        );


        $sql ="SELECT td.* FROM tvs_dealers td LEFT JOIN dealer_bank_transaction db ON td.id = db.dealer_id WHERE db.dealer_id IS NULL";
// print_r($sql);die;


        $totalFiltered = $totalData;

        $query = $this->db->query($sql);

        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        // echo "<pre>";print_r($result);die('result');
        $data = array();
        $i = 1;
        foreach ($result as $main) {
            $reset_btn = '<a class="btn" id="reset_button" onclick="ResetPassword('.$main->sap_ad_code.');">Reset</a>';
            $edit_dealer = '<a class="btn btn-primary" title="Edit Dealer" onclick="EditDealerData('.$main->id.')"><i class="fa fa-edit"></i></a>';

            $row = array();
            $row[] = $i++;
            $row[] = $main->dealer_code;
            $row[] = $main->dealer_name;
            $row[] = $main->sap_ad_code;
            $row[] = $main->ad_name;
            $row[] = $main->mobile;
            $row[] = $main->state;
            $row[] = $main->location;
            $row[] = '<a class="btn" onclick="login('.$main->id.')" >Log In</a> '.$reset_btn.$edit_dealer;
            $row[] = $main->created_at;



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

   public function ResetDealerpassword($sap_code){
        $reset_data = array(
            'password' => md5($sap_code),
        );
        if(!empty($sap_code)){
            $status = $this->MyBizzResetPassword($sap_code);
            $response_data =  json_decode($status,true);
            // print_r($response_data);die('stats');
            if($response_data['status'] == 'true'){
                $where1 = array('sap_ad_code' => $sap_code);
                $where2 = array('employee_code' => $sap_code);
                    $update_tvs = $this->Home_Model->updateTable('tvs_dealers',$reset_data,$where1);
                    $update_biz = $this->Home_Model->updateTable('biz_users',$reset_data,$where2);
                    if(!empty($update_tvs) && !empty($update_biz)){
                        $this->session->set_flashdata('message','Password is reset');
                    }
            }
        }
        
        redirect('admin/inactive_dealers');
    }

    function MyBizzResetPassword($post){
        $data_string = json_encode($post);
        // print_r($data_string);die('dat');
        $url = 'https://www.mybizznow.com/tvs/reset_password.php';
        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_RETURNTRANSFER => true ,
            CURLOPT_HTTPHEADER =>array(
                     'Content-Type: application/json',
                 'Content-Length: ' . strlen($data_string))
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
// echo '<pre>';print_r($resp);die('resp');
        return $resp;
    }

     function download_rsa_feedfile($ic_id,$from,$to) {  
        $heading_array = array("First Name","Last Name","Proposer DOB","Gender","Master Policy No","Booking Policy Location","Employee Number","Dealer Code","Location","IMD Code","Address Line 1","Address Line 2","Pincode","Mobile No","Policy Start date","Policy Start time","Policy End Date","Plan Name","Sum Insured","GWP","Tax","Total Premium","Plan Code","Insured Name","Insured date Of Birth","Insured Relationship with Proposer","Gender1","Nominee Name","Age","Relationship with Insured","Appointee Name","Appointee Age","Appointee Relationship");

        $main_array1 = array();
        ini_set('memory_limit', '-1');
        array_push($main_array1, $heading_array);
        $feedfile_data = $this->Home_Model->getFeedFileDataForPaIc($ic_id,$from,$to);
       // echo '<pre>';print_r($feedfile_data['result']);die;
       
       
        foreach ($feedfile_data['result'] as $row) {
            switch ($row['sum_insured']) {
                case 500000:
                   $gwp  = (14 * 5);
                    break;
                case 1000000:
                   $gwp  = (14 * 10);
                    break;
                case 1500000:
                   $gwp  = (14 * 15);
                    break;
                default:
                    $gwp = 0;
                    break;
            }
            if($row['plan_name']=='Sapphire Plus'){
                $gwp = 375;
            }
            $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;

            $fname = trim($row['fname']);
            $lname = trim($this->clean($row['lname']));
            //echo $lname.PHP_EOL;
            $Proposer_dob = $row['dob'];
            $gender = $row['gender'];
            $master_policy_no = $row['master_policy_no'];
            $booking_policy_no = $row['mp_localtion'];
            $Employee_no = trim($row['sold_policy_no']);
            $dealer_code = $row['sap_ad_code'];
            $location = $row['city_name'];
            $imd_code = ($ic_id == 2)?'3204180000':'IRDA?DB-596/14';
            $Address_Line_1 = $row['addr1'];
            $Address_Line_2 = $row['addr2'];
            $Pincode = $row['pincode'];
            $mobile_no = $row['mobile_no'];
            $Policy_Start_date = $row['policy_start_date'];
            $Policy_Start_time = $row['policy_start_time'];
            $Policy_end_date = $row['sold_policy_end_date'];
            $Plan_Name = $row['plan_name'];
            $sum_insured = $row['sum_insured'];
            $gwp = $gwp;
            $gwp_gst  = $gwp_gst;
            $total_premium = ($gwp + $gwp_gst);
            $Plan_Code = $row['plan_code'];
            $Insured_Name = $row['customer_name'];
            $Insured_date_Of_Birth = $row['dob'];
            $Insured_Relationship_with_Proposer = 'self';
            $Gender1 = $row['gender'];
            $Nominee_Name = $row['nominee_full_name'];
            $nominee_age = $row['nominee_age'];
            $Relationship_with_Insured = $row['nominee_relation'];
            $appointee_full_name = ($row['appointee_full_name'])?$row['appointee_full_name']:'';
            $appointee_age = ($row['appointee_age'])?$row['appointee_age']:'';
            $appointee_relation = ($row['appointee_relation'])?$row['appointee_relation']:'';
            
            $array_val = array($fname,$lname,$Proposer_dob,$gender,$master_policy_no,$booking_policy_no,$Employee_no,$dealer_code,$location,$imd_code,$Address_Line_1,$Address_Line_2,$Pincode,$mobile_no,$Policy_Start_date,$Policy_Start_time,$Policy_end_date,$Plan_Name,$sum_insured,$gwp,$gwp_gst,$total_premium,$Plan_Code,$Insured_Name,$Insured_date_Of_Birth,$Insured_Relationship_with_Proposer,$Gender1,$Nominee_Name,$nominee_age,$Relationship_with_Insured,$appointee_full_name,$appointee_age,$appointee_relation);
            array_push($main_array1, $array_val);
           // echo '<pre>';print_r($main_array1); die('asd');
        }
        // echo '<pre>';print_r($main_array1); die('asd');

        $csv_file_name = "PA Report-" . date('y-m-d') . ".csv";
        echo array_to_csv($main_array1, $csv_file_name);
    }

   function clean($string) {
       $string = str_replace('-', ' ', $string); // Replaces all spaces with hyphens.
       $string = preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
       $string =  preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

     //  echo $string.PHP_EOL;
        return $string;
    }

    function download_kotakopenrsa_feedfile($ic_id,$from,$to) {
       $heading_array = array("First Name","Last Name","Proposer DOB","Gender","Master Policy No","Booking Policy Location","Employee Number","Dealer Code","Location","IMD Code","Address Line 1","Address Line 2","Pincode","Mobile No","Policy Start date","Policy Start time","Policy End Date","Plan Name","Sum Insured","GWP","Tax","Total Premium","Plan Code","Insured Name","Insured date Of Birth","Insured Relationship with Proposer","Gender1","Nominee Name","Age","Relationship with Insured","Appointee Name","Appointee Age","Appointee Relationship");

        $main_array1 = array();
        ini_set('memory_limit', '-1');
        array_push($main_array1, $heading_array);
        $feedfile_data = $this->Home_Model->getkotakOpnrsaFeedfile($ic_id,$from,$to);
       
        foreach ($feedfile_data['result'] as $row) {

                $gwp  = 375;
                /*if($row['plan_name']=='Sapphire Plus'){
                     $gwp = 375;
                }*/

                $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;

                $fname = $row['fname'];
                $lname = $row['lname'];
                $Proposer_dob = $row['dob'];

                $gender = $row['gender'];
                $master_policy_no = $row['master_policy_no'];
                $booking_policy_no = $row['mp_location'];

                $Employee_no = trim($row['sold_policy_no']);
                $dealer_code = $row['sap_ad_code'];

                $location = $row['city_name'];
                $imd_code = ($ic_id == 4)?'3204180000':'IRDA?DB-596/14';
                $Address_Line_1 = $row['addr1'];
                $Address_Line_2 = $row['addr2'];
                $Pincode = $row['pincode'];
                $mobile_no = $row['mobile_no'];
                $Policy_Start_date = $row['policy_start_date'];
                $Policy_Start_time = $row['policy_start_time'];
                $Policy_end_date = $row['sold_policy_end_date'];
                $Plan_Name = $row['plan_name'];
                $sum_insured = OPEN_RSA_SUM_INSURED;
                $gwp = $gwp;
                $gwp_gst  = $gwp_gst;
                $total_premium = ($gwp + $gwp_gst);
                $Plan_Code = $row['plan_code'];
                $Insured_Name = $row['customer_name'];
                $Insured_date_Of_Birth = $row['dob'];
                $Insured_Relationship_with_Proposer = 'self';
                $Gender1 = $row['gender'];
                $Nominee_Name = $row['nominee_full_name'];
                $nominee_age = $row['nominee_age'];
                $Relationship_with_Insured = $row['nominee_relation'];
                $appointee_full_name = ($row['appointee_full_name'])?$row['appointee_full_name']:'';
                $appointee_age = ($row['appointee_age'])?$row['appointee_age']:'';
                $appointee_relation = ($row['appointee_relation'])?$row['appointee_relation']:'';

                $array_val = array($fname,$lname,$Proposer_dob,$gender,$master_policy_no,$booking_policy_no,$Employee_no,$dealer_code,$location,$imd_code,$Address_Line_1,$Address_Line_2,$Pincode,$mobile_no,$Policy_Start_date,$Policy_Start_time,$Policy_end_date,$Plan_Name,$sum_insured,$gwp,$gwp_gst,$total_premium,$Plan_Code,$Insured_Name,$Insured_date_Of_Birth,$Insured_Relationship_with_Proposer,$Gender1,$Nominee_Name,$nominee_age,$Relationship_with_Insured,$appointee_full_name,$appointee_age,$appointee_relation);
                array_push($main_array1, $array_val);


        }
        $csv_file_name = "PA openrsakotakReport-" . date('y-m-d') . ".csv";
        echo array_to_csv($main_array1, $csv_file_name);
    }

    public function MYTVS_feed_file($ic_id){
        $data['ic_id'] = $ic_id;
        $data['main_contain'] = 'admin/report/mytvs_feedfile';
        $this->load->view('admin/includes/template', $data);
    }

    public function DownloadMYTVSFeedfile($ic_id,$from,$to){
    $heading_array = array("First Name","Last Name","Proposer DOB","Gender","Employee Number","Dealer Code","Location","Address Line 1","Address Line 2","Pincode","Mobile No","Policy Start date","Policy End Date","Insured Name","Insured date Of Birth","Insured Relationship with Proposer","Gender1","Nominee Name","Age","Relationship with Insured","Appointee Name","Appointee Age","Appointee Relationship");

        $main_array1 = array();
        ini_set('memory_limit', '-1');
        array_push($main_array1, $heading_array);
        $feedfile_data = $this->Home_Model->RSAICID($ic_id,$from,$to);
       // echo '<pre>';print_r($feedfile_data['result']);die;
       
       
        foreach ($feedfile_data['result'] as $row) {
            // echo $row['sap_ad_code'];die('sap');
            // switch ($row['sum_insured']) {
            //     case 500000:
            //        $gwp  = (14 * 5);
            //         break;
            //     case 1000000:
            //        $gwp  = (14 * 10);
            //         break;
            //     case 1500000:
            //        $gwp  = (14 * 15);
            //         break;
            //     default:
            //         $gwp = 0;
            //         break;
            // }

            $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;

            $fname = $row['fname'];
            $lname = $row['lname'];
            $Proposer_dob = $row['dob'];
            $gender = $row['gender'];
            // $master_policy_no = $row['master_policy_no'];
            // $booking_policy_no = $row['mp_localtion'];
            $Employee_no = trim($row['sold_policy_no']);
            $dealer_code = $row['sap_ad_code'];
            $location = $row['city_name'];
            // $imd_code = ($ic_id == 2)?'3204180000':'IRDA?DB-596/14';
            $Address_Line_1 = $row['addr1'];
            $Address_Line_2 = $row['addr2'];
            $Pincode = $row['pincode'];
            $mobile_no = $row['mobile_no'];
            $Policy_Start_date = $row['sold_policy_effective_date'];
            $Policy_Start_time = $row['policy_start_time'];
            $Policy_end_date = $row['sold_policy_end_date'];
            // $Plan_Name = $row['plan_name'];
            // $sum_insured = $row['sum_insured'];
            // $gwp = $gwp;
            // $gwp_gst  = $gwp_gst;
            // $total_premium = ($gwp + $gwp_gst);
            // $Plan_Code = $row['plan_code'];
            $Insured_Name = $row['customer_name'];
            $Insured_date_Of_Birth = $row['dob'];
            $Insured_Relationship_with_Proposer = 'self';
            $Gender1 = $row['gender'];
            $Nominee_Name = $row['nominee_full_name'];
            $nominee_age = $row['nominee_age'];
            $Relationship_with_Insured = $row['nominee_relation'];
            $appointee_full_name = ($row['appointee_full_name'])?$row['appointee_full_name']:'';
            $appointee_age = ($row['appointee_age'])?$row['appointee_age']:'';
            $appointee_relation = ($row['appointee_relation'])?$row['appointee_relation']:'';
            
            $array_val = array($fname,$lname,$Proposer_dob,$gender,$Employee_no,$dealer_code,$location,$Address_Line_1,$Address_Line_2,$Pincode,$mobile_no,$Policy_Start_date,$Policy_end_date,$Insured_Name,$Insured_date_Of_Birth,$Insured_Relationship_with_Proposer,$Gender1,$Nominee_Name,$nominee_age,$Relationship_with_Insured,$appointee_full_name,$appointee_age,$appointee_relation);
            array_push($main_array1, $array_val);
           // echo '<pre>';print_r($main_array1); die('asd');
        }


        $csv_file_name = "MYTVS_Feedfile-" . date('y-m-d') . ".csv";
        echo array_to_csv($main_array1, $csv_file_name);

    }


    public function PolicyforKotak(){
        $data['main_contain'] = 'admin/report/policies_for_kotak';
        $this->load->view('admin/includes/template', $data);
    }

    public function kotak_policy_ajax(){
        $requestData = $_REQUEST;
        
       $start_date = $requestData['start_date'];
       $end_date = $requestData['end_date'];

        $where = '';
         // $where = 'pp.invoice_date' != 0;
        if (!empty($start_date) && !empty($end_date)) {
            $where = "AND  (CAST(tsp.sold_policy_date AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
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
        $session_data = $this->session->userdata('user_session');
        $user_id = $session_data['agent_detail']->id;
        $limit = ' LIMIT ' . $start . ', ' . $length . '';
       $policy_data = $this->Home_Model->getRsaPolicyDetail($where);
// echo '<pre>';print_r($policy_data);die;
        $totalFiltered = $totalData;

        $totalFiltered = $policy_data['num_rows'];
        $data = array();
        $i = 1;
        foreach ($policy_data['result'] as $main) {

            $row = array();
            $row[] = $i++;
            $row[] = $main['sold_policy_no'];
            $row[] = $main['sum_insured'];
            $row[] = $main['engine_no'];
            $row[] = $main['chassis_no'];
            $row[] = $main['customer_name'];
            $row[] = $main['mobile_no'];
            $row[] = $main['email'];
            $row[] = $main['nominee_full_name'];
            $row[] = $main['nominee_relation'];
            $row[] = $main['nominee_age'];
            $row[] = $main['appointee_full_name'];
            $row[] = $main['appointee_relation'];
            $row[] = $main['appointee_age'];
            $row[] = $main['sold_policy_date'];
            $row[] = $main['sold_policy_end_date'];

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

    public function EndorsementPolicy(){
        $data['main_contain'] = 'admin/report/endorsement_policy';
        $this->load->view('admin/includes/template', $data);
    }

    public function EndorsementPolicyAjax(){
        $requestData = $_REQUEST;
        $endorse_start_date = $requestData['endorse_start_date'];
        $endorse_end_date = $requestData['endorse_end_date'];
        $where = '';
         // $where = 'pp.invoice_date' != 0;
        if (!empty($endorse_start_date) && !empty($endorse_end_date)) {
            $where = "AND (CAST(tcs.`updated_at` AS DATE)) BETWEEN '" . $endorse_start_date . "' AND '" . $endorse_end_date . "'";
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
        $session_data = $this->session->userdata('user_session');
        $user_id = $session_data['agent_detail']->id;
        $limit = ' LIMIT ' . $start . ', ' . $length . '';
       $policy_data = $this->Home_Model->getEndorsePolicyData($where);
// echo '<pre>';print_r($policy_data);die;
        $totalFiltered = $totalData;

        $totalFiltered = $policy_data['num_rows'];
        $data = array();
        $i = 1;
        foreach ($policy_data['result'] as $main) {

            $row = array();
            $row[] = $i++;
            $row[] = $main['sold_policy_no'];
            $row[] = $main['engine_no'];
            $row[] = $main['chassis_no'];
            $row[] = $main['vehicle_registration_no'];
            $row[] = $main['customer_name'];
            $row[] = $main['mobile_no'];
            $row[] = $main['email'];
            $row[] = $main['nominee_full_name'];
            $row[] = $main['nominee_relation'];
            $row[] = $main['sold_policy_date'];
            $row[] = $main['sold_policy_end_date'];
            $row[] = $main['updated_at'];

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

    public function TvsPolicies(){
        $data['main_contain'] = 'admin/report/tvs_policies';
        $this->load->view('admin/includes/template', $data);
    }

    public function TvsPolicyAjax(){
        $requestData = $_REQUEST;
        
       $tvs_start_date = $requestData['tvs_start_date'];
       $tvs_end_date = $requestData['tvs_end_date'];

        $where = '';
         // $where = 'pp.invoice_date' != 0;
        if (!empty($tvs_start_date) && !empty($tvs_end_date)) {
            $where = "WHERE (CAST(tsp.sold_policy_date AS DATE)) BETWEEN '" . $tvs_start_date . "' AND '" . $tvs_end_date . "'";
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
        $session_data = $this->session->userdata('user_session');
        $user_id = $session_data['agent_detail']->id;
        $limit = ' LIMIT ' . $start . ', ' . $length . '';
       $policy_data = $this->Home_Model->getRsaPolicyDetail($where);
// echo '<pre>';print_r($policy_data);die;
        $totalFiltered = $totalData;

        $totalFiltered = $policy_data['num_rows'];
        $data = array();
        $i = 1;
        foreach ($policy_data['result'] as $main) {

            $created_date = explode(' ', $main->created_at);
            $created_date = $created_date[0];

            $row = array();
            $row[] = $i++;
            $row[] = $main['sold_policy_no'];
            $row[] = $main['engine_no'];
            $row[] = $main['chassis_no'];
            $row[] = $main['vehicle_registration_no'];
            $row[] = $main['make_name'];
            $row[] = $main['model_name'];
            $row[] = $main['customer_name'];
            $row[] = $main['mobile_no'];
            $row[] = $main['email'];
            $row[] = $main['sold_policy_date'];
            $row[] = $main['sold_policy_end_date'];

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

    public function BhartiFeedfile($ic_id){
        $data['ic_id'] = $ic_id;
        $data['main_contain'] = 'admin/report/bharti_feedfile';
        $this->load->view('admin/includes/template', $data);
    }

     function feedfileforBharti($ic_id,$from,$to) {  
        $heading_array = array("First Name","Last Name","Proposer DOB","Gender","Employee Number","Dealer Code","Location","Address Line 1","Address Line 2","Pincode","Mobile No","Policy Start date","Policy Start time","Policy End Date","Insured Name","Insured date Of Birth","Insured Relationship with Proposer","Gender1","Nominee Name","Age","Relationship with Insured","Appointee Name","Appointee Age","Appointee Relationship");

        $main_array1 = array();
        ini_set('memory_limit', '-1');
        array_push($main_array1, $heading_array);
        $feedfile_data = $this->Home_Model->RSAICID($ic_id,$from,$to);
       // echo '<pre>';print_r($feedfile_data['result']);die;
       
       
        foreach ($feedfile_data['result'] as $row) {
            // echo $row['sap_ad_code'];die('sap');
            // switch ($row['sum_insured']) {
            //     case 500000:
            //        $gwp  = (14 * 5);
            //         break;
            //     case 1000000:
            //        $gwp  = (14 * 10);
            //         break;
            //     case 1500000:
            //        $gwp  = (14 * 15);
            //         break;
            //     default:
            //         $gwp = 0;
            //         break;
            // }

            $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;

            $fname = $row['fname'];
            $lname = $row['lname'];
            $Proposer_dob = $row['dob'];
            $gender = $row['gender'];
            // $master_policy_no = $row['master_policy_no'];
            // $booking_policy_no = $row['mp_localtion'];
            $Employee_no = trim($row['sold_policy_no']);
            $dealer_code = $row['sap_ad_code'];
            $location = $row['city_name'];
            // $imd_code = ($ic_id == 2)?'3204180000':'IRDA?DB-596/14';
            $Address_Line_1 = $row['addr1'];
            $Address_Line_2 = $row['addr2'];
            $Pincode = $row['pincode'];
            $mobile_no = $row['mobile_no'];
            $Policy_Start_date = $row['policy_start_date'];
            $Policy_Start_time = $row['policy_start_time'];
            $Policy_end_date = $row['sold_policy_end_date'];
            // $Plan_Name = $row['plan_name'];
            // $sum_insured = $row['sum_insured'];
            // $gwp = $gwp;
            // $gwp_gst  = $gwp_gst;
            // $total_premium = ($gwp + $gwp_gst);
            // $Plan_Code = $row['plan_code'];
            $Insured_Name = $row['customer_name'];
            $Insured_date_Of_Birth = $row['dob'];
            $Insured_Relationship_with_Proposer = 'self';
            $Gender1 = $row['gender'];
            $Nominee_Name = $row['nominee_full_name'];
            $nominee_age = $row['nominee_age'];
            $Relationship_with_Insured = $row['nominee_relation'];
            $appointee_full_name = ($row['appointee_full_name'])?$row['appointee_full_name']:'';
            $appointee_age = ($row['appointee_age'])?$row['appointee_age']:'';
            $appointee_relation = ($row['appointee_relation'])?$row['appointee_relation']:'';
            
            $array_val = array($fname,$lname,$Proposer_dob,$gender,$Employee_no,$dealer_code,$location,$Address_Line_1,$Address_Line_2,$Pincode,$mobile_no,$Policy_Start_date,$Policy_Start_time,$Policy_end_date,$Insured_Name,$Insured_date_Of_Birth,$Insured_Relationship_with_Proposer,$Gender1,$Nominee_Name,$nominee_age,$Relationship_with_Insured,$appointee_full_name,$appointee_age,$appointee_relation);
            array_push($main_array1, $array_val);
           // echo '<pre>';print_r($main_array1); die('asd');
        }


        $csv_file_name = "Bharti_Assist_Feedfile-" . date('y-m-d') . ".csv";
        echo array_to_csv($main_array1, $csv_file_name);
    }


    public function feedfileforBharti_old(){
    ini_set('memory_limit', '2048M');
    $from_date = $this->input->post('from_date');
    $to_date =$this->input->post('to_date');
    $ic_id =$this->input->post('ic_id');
    $policy_data = $this->Home_Model->getFeedFileData($from_date,$to_date,$ic_id);
    // echo '<pre>';print_r($policy_data);die;

    $this->load->library('excel');

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Policy No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Engine No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Chassis No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Make');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Model');
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Vehicle Registration No');
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Customer Name'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Email'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Mobile No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Address1');
    $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Address2'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'State'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'City');  
    $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Policy Start Date'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Policy End Date'); 

 
    
    $rowCount = 2;
    foreach ($policy_data['result'] as $row) {
        // print_r($row);
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row['sold_policy_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['engine_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['chassis_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['make_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row['model_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row['vehicle_registration_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row['customer_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row['addr1']);
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row['addr2']);
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $row['state_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $row['city_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $row['sold_policy_effective_date']);
        $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $row['sold_policy_end_date']);
       

         $rowCount++;
    }


 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

            $fileName = 'bajajfeedfile.xlsx';  

            ob_start();
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response =  array(
                    'op' => 'ok',
                    'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
                );

            die(json_encode($response));
    }

    public function BajajPolicies(){
        $data['main_contain'] = 'admin/report/bajaj_policies';
        $this->load->view('admin/includes/template', $data);
    }

    public function bajaj_policy_ajax(){
        $requestData = $_REQUEST;
        
       $start_date = $requestData['start_date'];
       $end_date = $requestData['end_date'];

        $where = '';
         // $where = 'pp.invoice_date' != 0;
        if (!empty($start_date) && !empty($end_date)) {
            $where = "WHERE (CAST(tsp.sold_policy_date AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
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
        $session_data = $this->session->userdata('user_session');
        $user_id = $session_data['agent_detail']->id;
        $limit = ' LIMIT ' . $start . ', ' . $length . '';
       $policy_data = $this->Home_Model->getRsaPolicyDetail($where);
// echo '<pre>';print_r($policy_data);die;
        $totalFiltered = $totalData;

        $totalFiltered = $policy_data['num_rows'];
        $data = array();
        $i = 1;
        foreach ($policy_data['result'] as $main) {

            $row = array();
            $row[] = $i++;
            $row[] = $main['sold_policy_no'];
            $row[] = $main['engine_no'];
            $row[] = $main['chassis_no'];
            $row[] = $main['vehicle_registration_no'];
            $row[] = $main['make_name'];
            $row[] = $main['model_name'];
            $row[] = $main['customer_name'];
            $row[] = $main['mobile_no'];
            $row[] = $main['email'];
             $row[] = $main['sold_policy_date'];
            $row[] = $main['sold_policy_end_date'];
        

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

     public function dealer_uploaded_docs(){
        $data['main_contain'] = 'admin/report/dealer_uploded_document';
        $this->load->view('admin/includes/template', $data);
    }


    public function uploaded_docs_ajax(){
        $requestData = $_REQUEST;
        $start_date = $requestData['start_date'];
        $to_date = $requestData['to_date'];
        $where = '';
        if(!empty($start_date) && !empty($to_date)){
            $where = "AND DATE(tdd.created_at) BETWEEN '$start_date' AND '$to_date'" ;
        }

        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'dealer_code',
            1 => 'dealer_name',
            2 => 'sap_ad_code',
            3 => 'mobile',
            4 => 'location',
        );


        $sql = "SELECT td.*,td.id AS tvs_dealer_id,tdd.* FROM tvs_dealers td JOIN tvs_dealer_documents tdd ON td.`id` = tdd.`dealer_id` WHERE td.id != 2871 $where ";
// print_r($sql);//die;

        $totalFiltered = $totalData;

        $query = $this->db->query($sql);
        // echo $this->db->last_query();
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY td.id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        // echo "<pre>";print_r($result);die('result');
        $data = array();
        $i = 1;
        $tot_wallet = 0;
        foreach ($result as $main) {
            $reset_btn = '<a class="btn btn-primary" id="reset_button" onclick="ResetPassword('.$main->sap_ad_code.');">Reset</a>';
            $where = array('id' => $main->rsa_ic_master_id);
            $get_rsa_ic_data = $this->Home_Model->getDataFromTable('tvs_insurance_companies',$where);
            $row = array();
            $agreement_bar='' ; $gst_certificate_bar = ''; $pan_card_bar=''; $cancel_cheque_bar='';

            if(isset($main->agreement)){
                $agreement_bar = '<div class="progress-bar progress-bar-success" style="width:40%">Agreement PDF</div>';
            }
            if(isset($main->gst_certificate)){
                $gst_certificate_bar = '<div class="progress-bar progress-bar-warning" style="width:20%">GST</div>';
            }
            if(isset($main->pan_card)){
                $pan_card_bar = '<div class="progress-bar progress-bar-info" style="width:20%">Pan Card</div>';
            }
            if(isset($main->cancel_cheque)){
                $cancel_cheque_bar = '<div class="progress-bar progress-bar-danger" style="width:20%">Cancel Cheque</div>';
            }           
                
                    $progress_bar = '<a class="btn" onclick="view_uploaded_document('.$main->tvs_dealer_id.')" > <div class="progress">'
                             .$agreement_bar.$gst_certificate_bar.$pan_card_bar.$cancel_cheque_bar .'
                            </div></a> ';
            $row[] = $i++;
            $row[] = $get_rsa_ic_data[0]['name'];
            $row[] = $main->dealer_code;
            $row[] = $main->dealer_name;
            $row[] = $main->sap_ad_code;
            $row[] = $main->ad_name;
            $row[] = $main->mobile;
            $row[] = $main->state;
            $row[] = $main->location;
            $row[] = $main->created_at;
            $row[] = '<a class="btn" onclick="login('.$main->tvs_dealer_id.')" >Log In</a>&nbsp;&nbsp;&nbsp; '.$reset_btn;
            $row[] = $progress_bar;

            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        echo json_encode($json_data);
    }

    public function getUploadedDocument(){
        $where = array('dealer_id' =>  $_POST['dealer_id']);
        $docment_data = $this->Home_Model->getDataFromTable('tvs_dealer_documents',$where);
        echo json_encode($docment_data[0]);
    }

    public function document_not_uploaded(){
        $data['main_contain'] = 'admin/report/document_not_uploaded';
        $this->load->view('admin/includes/template', $data);
    }

    public function docs_not_uploaded_ajax(){
        $requestData = $_REQUEST;

        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'dealer_code',
            1 => 'dealer_name',
            2 => 'sap_ad_code',
            3 => 'mobile',
            4 => 'location',
        );


        $sql = "SELECT * FROM tvs_dealers WHERE `gst_no` = '' AND `banck_acc_no`='' AND id != 2871  ";
// print_r($sql);//die;

        $totalFiltered = $totalData;

        $query = $this->db->query($sql);
        // echo $this->db->last_query();die;
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        // echo "<pre>";print_r($result);die('result');
        $data = array();
        $i = 1;
        $tot_wallet = 0;
        foreach ($result as $main) {
            $where = array('id' => $main->rsa_ic_master_id);
            $get_rsa_ic_data = $this->Home_Model->getDataFromTable('tvs_insurance_companies',$where);
            $where = array('dealer_id' => $main->id);
            $docs_data = $this->Home_Model->getDataFromTable('tvs_dealer_documents',$where);
            $row = array();
                if(empty($docs_data)){
                    // echo $i++ .'<br/>';
                    $row[] = $i++;
                    $row[] = $get_rsa_ic_data[0]['name'];
                    $row[] = $main->dealer_code;
                    $row[] = $main->dealer_name;
                    $row[] = $main->sap_ad_code;
                    $row[] = $main->ad_name;
                    $row[] = $main->mobile;
                    $row[] = $main->state;
                    $row[] = $main->location;
                    $row[] = '<a class="btn" onclick="login('.$main->id.')" >Log In</a> ';
                    $row[] = $main->created_at;

                    $data[] = $row;
                }
           
        }

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        echo json_encode($json_data);
    }

    function dealer_activity_report(){
    $data['main_contain'] = 'admin/report/dealer_activity';
    $this->load->view('admin/includes/template', $data);
}

function dealer_activity_ajax(){
    $requestData = $_REQUEST;
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'dealer_code',
            1 => 'dealer_name',
            2 => 'sap_ad_code',
            3 => 'mobile',
            4 => 'location',
        );

       $result = $this->Home_Model->DealerActivityReport();
// print_r($sql);//die;

        $totalFiltered = $result['num_rows'];

        // echo $this->db->last_query();die;
        $totalFiltered = $result['num_rows'];
        
        // echo "<pre>";print_r($result);die('result');
        $data = array();
        $i = 1;
        foreach ($result['result'] as $main) {
            $row =array();
            $row[] = $i++;
            $row[] = $main['sap_ad_code'];
            $row[] = $main['dealer_type'];
            $row[] = $main['dealer_name'];
            $row[] = $main['mobile'];
            $row[] = $main['state'];
            $row[] = $main['location'];
            $row[] = $main['logged_in'];
            $row[] = $main['agreement_pdf'];
            $row[] = $main['pan_card'];
            $row[] = $main['gst_certificate'];
            $row[] = $main['cancel_cheque'];
            $row[] = $main['payment_status'];
            $row[] = $main['Is_Sold_Policy'];

            $data[] = $row;           
        }

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        echo json_encode($json_data);
}

function last_week_sold_policies(){
    $data['main_contain'] = 'admin/report/last_week_sold_policies';
    $this->load->view('admin/includes/template', $data);
}

function lastweek_soldpolicies_ajax(){
    $requestData = $_REQUEST;
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'dealer_code',
            1 => 'dealer_name',
            2 => 'sap_ad_code',
            3 => 'mobile',
            4 => 'location',
        );

       $result = $this->Home_Model->lastweek_soldpolicy();
// print_r($sql);//die;
        // echo "<pre>";print_r($result);die('result');
        $totalFiltered = $result['num_rows'];
        $data = array();
        $i = 1;
        foreach ($result['result'] as $main) {
            $row =array();
            $row[] = $i++;
            $row[] = $main['dealer_code'];
            $row[] = $main['dealer_name'];
            $row[] = $main['today'];
            $row[] = $main['T_1'];
            $row[] = $main['T_2'];
            $row[] = $main['T_3'];
            $row[] = $main['T_4'];
            $row[] = $main['T_5'];
            $row[] = $main['T_6'];
            $row[] = $main['T_7'];
            $row[] = $main['T_8'];
            $row[] = $main['T_9'];
            $row[] = $main['T_10'];
            $row[] = $main['T_11'];

            $data[] = $row;           
        }

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        echo json_encode($json_data);
}



    function PAEndorseFeedFile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['main_contain'] = 'admin/report/pa_endorse_feedfile';
    $this->load->view('admin/includes/template', $data);
}

function endorse_PA_feedfile($ic_id,$from,$to){
    // echo $ic_id.' -ic_id-'.$from.'--from--'.$to;
 $heading_array = array("Employee Number","First Name","Last Name","Proposer DOB","Gender","Location","Address Line 1","Address Line 2","Pincode","Mobile No","Insured Name","Insured date Of Birth","Gender1","Nominee Name","Age","Relationship with Insured","Appointee Name","Appointee Age","Appointee Relationship");
 // echo '<pre>';print_r($heading_array);die;
 
        $endorse_data=array();
        $main_array1 = array();
        ini_set('memory_limit', '-1');
        array_push($main_array1, $heading_array);
        $feedfile_data = $this->Home_Model->getPAEndorseFeedfile($ic_id,$from,$to);
       // echo '<pre>';print_r($feedfile_data['result']);die;
        foreach ($feedfile_data['result'] as $row) {
            $old_data = $this->Home_Model->getOldData($row['sold_id']);
            if(!empty($old_data)){
                // echo '<pre>';  print_r($row);
                // echo '<pre>';  print_r($old_data);
                $response = array_diff($row,$old_data);
                if($response){
         // echo '<pre>';  print_r($response); echo '</pre>'; die('in');
                $endorse_data[] = $response;
            }

            }
        }
        // echo '<pre>';  print_r($endorse_data); echo '</pre>'; die('in');
        foreach ($endorse_data as $endorse) {
            
            // switch ($row['sum_insured']) {
            //     case 500000:
            //        $gwp  = (14 * 5);
            //         break;
            //     case 1000000:
            //        $gwp  = (14 * 10);
            //         break;
            //     case 1500000:
            //        $gwp  = (14 * 15);
            //         break;
            //     default:
            //         $gwp = 0;
            //         break;
            // }

            // $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;
            $Employee_no = ($endorse['sold_policy_no'])?$endorse['sold_policy_no']:'';
            $endorse_fname = ($endorse['fname'])?$endorse['fname']:'';

            $endorse_lname = ($endorse['lname'])?$endorse['lname']:'';
            
            $endorse_Proposer_dob = ($endorse['dob'])?$endorse['dob']:'';
            
            $endorse_gender = ($endorse['gender'])?$endorse['gender']:'';

            $endorse_location = ($endorse['city_name'])?$endorse['city_name']:'';

            $imd_code = ($ic_id == 2)?'3204180000':'IRDA?DB-596/14';

            $endorse_Address_Line_1 = ($endorse['addr1'])?$endorse['addr1']:'';

            $endorse_Address_Line_2 = ($endorse['addr2'])?$endorse['addr2']:'';

            $endorse_Pincode = ($endorse['pincode'])?$endorse['pincode']:'';

            $endorse_mobile_no = ($endorse['mobile_no'])?$endorse['mobile_no']:'';

            // $gwp = $gwp;
            // $gwp_gst  = $gwp_gst;
            // $total_premium = ($gwp + $gwp_gst);


            $endorse_Insured_Name = ($endorse['customer_name'])?$endorse['customer_name']:'';

            $endorse_Insured_date_Of_Birth = ($endorse['dob'])?$endorse['dob']:'';

            $Insured_Relationship_with_Proposer = 'self';

            $endorse_Gender1 = ($endorse['gender'])?$endorse['gender']:'';

            $endorse_Nominee_Name = ($endorse['nominee_full_name'])?$endorse['nominee_full_name']:'';

            $endorse_nominee_age = ($endorse['nominee_age'])?$endorse['nominee_age']:'';

            $endorse_Relationship_with_Insured = ($endorse['nominee_relation'])?$endorse['nominee_relation']:'';

            $endorse_appointee_full_name = ($endorse['appointee_full_name'])?$endorse['appointee_full_name']:'';

            $endorse_appointee_age = ($endorse['appointee_age'])?$endorse['appointee_age']:'';

            $endorse_appointee_relation = ($endorse['appointee_relation'])?$endorse['appointee_relation']:'';
            

            $array_val = array($Employee_no,$endorse_fname,$endorse_lname,$endorse_Proposer_dob,$endorse_gender,$endorse_location,$endorse_Address_Line_1,$endorse_Address_Line_2,$endorse_Pincode,$endorse_mobile_no,$endorse_Insured_Name,$endorse_Insured_date_Of_Birth,$endorse_Gender1,$endorse_Nominee_Name,$endorse_nominee_age,$endorse_Relationship_with_Insured,$endorse_appointee_full_name,$endorse_appointee_age,$endorse_appointee_relation);
            
            array_push($main_array1, $array_val);
           // echo '<pre>';
           // print_r($main_array1); 
           // echo '</pre>';

        }
            // die('out');

        $csv_file_name = "PA-Endorse-Report-" . date('y-m-d') . ".csv";
        echo array_to_csv($main_array1, $csv_file_name);
}


function bharti_endorse_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['main_contain'] = 'admin/report/bharti_endorse_feedfile';
    $this->load->view('admin/includes/template', $data);
}

function download_bharti_endorse_feedfile(){
    $from_date = $this->input->post('from_date');
    $to_date =$this->input->post('to_date');
    $ic_id =$this->input->post('ic_id');
    $policy_data = $this->Home_Model->getRSAEndoresfeedfile($from_date,$to_date,$ic_id);
    // echo '<pre>';print_r($policy_data);die;

    $this->load->library('excel');

$objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Policy No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Engine No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Chassis No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Make');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Model');
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Vehicle Registration No');
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Customer Name'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Email'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Mobile No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Address1');
    $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Address2'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'State'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'City');  
    $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Created Date'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'End Date'); 

 
    
    $rowCount = 2;
    foreach ($policy_data['result'] as $row) {
        // print_r($row);
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row['sold_policy_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['engine_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['chassis_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['make_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row['model_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row['vehicle_registration_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row['customer_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row['addr1']);
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row['addr2']);
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $row['state']);
        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $row['city_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $row['created_date']);
        $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $row['sold_policy_end_date']);
        
       
      

         $rowCount++;
    }

//     // die;
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

            $fileName = 'bharti_endorse_rsa.xlsx';  

            ob_start();
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response =  array(
                    'op' => 'ok',
                    'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
                );

            die(json_encode($response));
}

function pa_canceled_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['main_contain'] = 'admin/report/pa_canceled_feedfile';
    $this->load->view('admin/includes/template', $data);
}


function download_pa_canceled_feedfile($ic_id,$from,$to){
       // echo $ic_id.' -ic_id-'.$from.'--from--'.$to;
 $heading_array = array("First Name","Last Name","Proposer DOB","Gender","Master Policy No","Booking Policy Location","Employee Number","Dealer Code","Location","IMD Code","Address Line 1","Address Line 2","Pincode","Mobile No","Policy Start date","Policy Start time","Policy End Date","Plan Name","Sum Insured","GWP","Tax","Total Premium","Plan Code","Insured Name","Insured date Of Birth","Insured Relationship with Proposer","Gender1","Nominee Name","Age","Relationship with Insured","Appointee Name","Appointee Age","Appointee Relationship","Reason For Cancellation","Cancellation Date");

        $main_array1 = array();
        ini_set('memory_limit', '-1');
        array_push($main_array1, $heading_array);
        if($ic_id=='2'){
            $feedfile_data = $this->Home_Model->getKotakCanceledFeedfile($ic_id,$from,$to);
        }else{
            $feedfile_data = $this->Home_Model->getPACanceledFeedfile($ic_id,$from,$to);
        }
       // echo '<pre>';print_r($feedfile_data['result']);die;
       
       
        foreach ($feedfile_data['result'] as $row) {
            switch ($row['sum_insured']) {
                case 500000:
                   $gwp  = (14 * 5);
                    break;
                case 1000000:
                   $gwp  = (14 * 10);
                    break;
                case 1500000:
                   $gwp  = (14 * 15);
                    break;
                default:
                    $gwp = 0;
                    break;
            }

            $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;

            $fname = $row['fname'];
            $lname = $row['lname'];
            $Proposer_dob = $row['dob'];
            $gender = $row['gender'];
            $master_policy_no = $row['master_policy_no'];
            $booking_policy_no = $row['mp_localtion'];
            $Employee_no = trim($row['sold_policy_no']);
            $dealer_code = $row['sap_ad_code'];
            $location = $row['city_name'];
            $imd_code = ($ic_id == 2)?'3204180000':'IRDA?DB-596/14';
            $Address_Line_1 = $row['addr1'];
            $Address_Line_2 = $row['addr2'];
            $Pincode = $row['pincode'];
            $mobile_no = $row['mobile_no'];
            $Policy_Start_date = $row['policy_start_date'];
            $Policy_Start_time = $row['policy_start_time'];
            $Policy_end_date = $row['sold_policy_end_date'];
            $Plan_Name = $row['plan_name'];
            $sum_insured = $row['sum_insured'];
            $gwp = $gwp;
            $gwp_gst  = $gwp_gst;
            $total_premium = ($gwp + $gwp_gst);
            $Plan_Code = $row['plan_code'];
            $Insured_Name = $row['customer_name'];
            $Insured_date_Of_Birth = $row['dob'];
            $Insured_Relationship_with_Proposer = 'self';
            $Gender1 = $row['gender'];
            $Nominee_Name = $row['nominee_full_name'];
            $nominee_age = $row['nominee_age'];
            $Relationship_with_Insured = $row['nominee_relation'];
            $appointee_full_name = ($row['appointee_full_name'])?$row['appointee_full_name']:'';
            $appointee_age = ($row['appointee_age'])?$row['appointee_age']:'';
            $appointee_relation = ($row['appointee_relation'])?$row['appointee_relation']:'';
            $cancellation_reson = $row['cancellation_reson'];
            $cancellation_date = $row['cancellation_date'];
            
            $array_val = array($fname,$lname,$Proposer_dob,$gender,$master_policy_no,$booking_policy_no,$Employee_no,$dealer_code,$location,$imd_code,$Address_Line_1,$Address_Line_2,$Pincode,$mobile_no,$Policy_Start_date,$Policy_Start_time,$Policy_end_date,$Plan_Name,$sum_insured,$gwp,$gwp_gst,$total_premium,$Plan_Code,$Insured_Name,$Insured_date_Of_Birth,$Insured_Relationship_with_Proposer,$Gender1,$Nominee_Name,$nominee_age,$Relationship_with_Insured,$appointee_full_name,$appointee_age,$appointee_relation,$cancellation_reson,$cancellation_date);
            array_push($main_array1, $array_val);
           // echo '<pre>';print_r($main_array1); die('asd');
        }

        $csv_file_name = "PA-Cancelled-Report-" . date('y-m-d') . ".csv";
        echo array_to_csv($main_array1, $csv_file_name);
}




function bharti_canceled_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['main_contain'] = 'admin/report/bharti_cancelled_feedfile';
    $this->load->view('admin/includes/template', $data);
}

function download_bharti_canceled_feedfile(){
    $from_date = $this->input->post('from_date');
    $to_date =$this->input->post('to_date');
    $ic_id =$this->input->post('ic_id');
    $policy_data = $this->Home_Model->getRSACanceledFeedfile($from_date,$to_date,$ic_id);
    // echo '<pre>';print_r($policy_data);die;

    $this->load->library('excel');

$objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Policy No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Engine No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Chassis No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Make');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Model');
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Vehicle Registration No');
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Customer Name'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Email'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Mobile No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Address1');
    $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Address2'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'State'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'City');  
    $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Created Date'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'End Date'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Canceled Date'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Cancellation Reason'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Cancellation Reson Type'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Cancellation File Name'); 

 
    
    $rowCount = 2;
    foreach ($policy_data['result'] as $row) {
        // print_r($row);
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row['sold_policy_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['engine_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['chassis_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['make_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row['model_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row['vehicle_registration_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row['customer_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row['addr1']);
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row['addr2']);
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $row['state_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $row['city_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $row['created_date']);
        $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $row['sold_policy_end_date']);
        $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $row['cancellation_date']);
        $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $row['cancellation_reson']);
        $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, $row['cancelation_reason_type']);
        $objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, $row['cancel_file_name']);
        
       
      

         $rowCount++;
    }

//     // die;
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

            $fileName = 'bharti_canceled.xlsx';  

            ob_start();
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response =  array(
                    'op' => 'ok',
                    'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
                );

            die(json_encode($response));
}

function mytvs_endorse_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['main_contain'] = 'admin/report/mytvs_endorse_feedfile';
    $this->load->view('admin/includes/template', $data);
}

function download_mytvs_endorse_feedfile($ic_id,$from,$to){
    $heading_array = array("First Name","Last Name","Proposer DOB","Gender","Employee Number","Dealer Code","Location","Address Line 1","Address Line 2","Pincode","Mobile No","Policy Start date","Policy End Date","Insured Name","Insured date Of Birth","Insured Relationship with Proposer","Gender1","Nominee Name","Age","Relationship with Insured","Appointee Name","Appointee Age","Appointee Relationship");

        $main_array1 = array();
        ini_set('memory_limit', '-1');
        array_push($main_array1, $heading_array);
        $feedfile_data = $this->Home_Model->getRSAEndoresfeedfile($from,$to,$ic_id);
       // echo '<pre>';print_r($feedfile_data['result']);die;
       
       
        foreach ($feedfile_data['result'] as $row) {
            // echo $row['sap_ad_code'];die('sap');

            $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;

            $fname = $row['fname'];
            $lname = $row['lname'];
            $Proposer_dob = $row['dob'];
            $gender = $row['gender'];
            // $master_policy_no = $row['master_policy_no'];
            // $booking_policy_no = $row['mp_localtion'];
            $Employee_no = trim($row['sold_policy_no']);
            $dealer_code = $row['sap_ad_code'];
            $location = $row['city_name'];
            // $imd_code = ($ic_id == 2)?'3204180000':'IRDA?DB-596/14';
            $Address_Line_1 = $row['addr1'];
            $Address_Line_2 = $row['addr2'];
            $Pincode = $row['pincode'];
            $mobile_no = $row['mobile_no'];
            $Policy_Start_date = $row['sold_policy_effective_date'];
            $Policy_Start_time = $row['policy_start_time'];
            $Policy_end_date = $row['sold_policy_end_date'];
            $Insured_Name = $row['customer_name'];
            $Insured_date_Of_Birth = $row['dob'];
            $Insured_Relationship_with_Proposer = 'self';
            $Gender1 = $row['gender'];
            $Nominee_Name = $row['nominee_full_name'];
            $nominee_age = $row['nominee_age'];
            $Relationship_with_Insured = $row['nominee_relation'];
            $appointee_full_name = ($row['appointee_full_name'])?$row['appointee_full_name']:'';
            $appointee_age = ($row['appointee_age'])?$row['appointee_age']:'';
            $appointee_relation = ($row['appointee_relation'])?$row['appointee_relation']:'';
            
            $array_val = array($fname,$lname,$Proposer_dob,$gender,$Employee_no,$dealer_code,$location,$Address_Line_1,$Address_Line_2,$Pincode,$mobile_no,$Policy_Start_date,$Policy_end_date,$Insured_Name,$Insured_date_Of_Birth,$Insured_Relationship_with_Proposer,$Gender1,$Nominee_Name,$nominee_age,$Relationship_with_Insured,$appointee_full_name,$appointee_age,$appointee_relation);
            array_push($main_array1, $array_val);
           // echo '<pre>';print_r($main_array1); die('asd');
        }


        $csv_file_name = "MYTVS_Endorse_Feedfile-" . date('y-m-d') . ".csv";
        echo array_to_csv($main_array1, $csv_file_name);
}


function mytvs_canceled_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['main_contain'] = 'admin/report/mytvs_canceled_feedfile';
    $this->load->view('admin/includes/template', $data);
}


function download_mytvs_canceled_feedfile($ic_id,$from,$to){
    $heading_array = array("First Name","Last Name","Proposer DOB","Gender","Employee Number","Dealer Code","Location","Address Line 1","Address Line 2","Pincode","Mobile No","Policy Start date","Policy End Date","Insured Name","Insured date Of Birth","Insured Relationship with Proposer","Gender1","Nominee Name","Age","Relationship with Insured","Appointee Name","Appointee Age","Appointee Relationship","Canceled Date","Cancelation Reason");

        $main_array1 = array();
        ini_set('memory_limit', '-1');
        array_push($main_array1, $heading_array);
        $feedfile_data = $this->Home_Model->getRSACanceledFeedfile($from,$to,$ic_id);
       // echo '<pre>';print_r($feedfile_data['result']);die;
       
        foreach ($feedfile_data['result'] as $row) {
            // echo $row['sap_ad_code'];die('sap');

            $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;

            $fname = $row['fname'];
            $lname = $row['lname'];
            $Proposer_dob = $row['dob'];
            $gender = $row['gender'];
            $Employee_no = trim($row['sold_policy_no']);
            $dealer_code = $row['sap_ad_code'];
            $location = $row['city_name'];
            // $imd_code = ($ic_id == 2)?'3204180000':'IRDA?DB-596/14';
            $Address_Line_1 = $row['addr1'];
            $Address_Line_2 = $row['addr2'];
            $Pincode = $row['pincode'];
            $mobile_no = $row['mobile_no'];
            $Policy_Start_date = $row['sold_policy_effective_date'];
            $Policy_Start_time = $row['policy_start_time'];
            $Policy_end_date = $row['sold_policy_end_date'];
            // $Plan_Name = $row['plan_name'];
            // $Plan_Code = $row['plan_code'];
            $Insured_Name = $row['customer_name'];
            $Insured_date_Of_Birth = $row['dob'];
            $Insured_Relationship_with_Proposer = 'self';
            $Gender1 = $row['gender'];
            $Nominee_Name = $row['nominee_full_name'];
            $nominee_age = $row['nominee_age'];
            $Relationship_with_Insured = $row['nominee_relation'];
            $appointee_full_name = ($row['appointee_full_name'])?$row['appointee_full_name']:'';
            $appointee_age = ($row['appointee_age'])?$row['appointee_age']:'';
            $appointee_relation = ($row['appointee_relation'])?$row['appointee_relation']:'';
            $cancellation_date = ($row['cancellation_date'])?$row['cancellation_date']:'';
            $cancellation_reson = ($row['cancellation_reson'])?$row['cancellation_reson']:'';
            
            $array_val = array($fname,$lname,$Proposer_dob,$gender,$Employee_no,$dealer_code,$location,$Address_Line_1,$Address_Line_2,$Pincode,$mobile_no,$Policy_Start_date,$Policy_end_date,$Insured_Name,$Insured_date_Of_Birth,$Insured_Relationship_with_Proposer,$Gender1,$Nominee_Name,$nominee_age,$Relationship_with_Insured,$appointee_full_name,$appointee_age,$appointee_relation,$cancellation_date,$cancellation_reson);
            array_push($main_array1, $array_val);
           // echo '<pre>';print_r($main_array1); die('asd');
        }


        $csv_file_name = "MYTVS_Canceled_Feedfile-" . date('y-m-d') . ".csv";
        echo array_to_csv($main_array1, $csv_file_name);
}


public function DownloadIciciFeedfile($ic_id,$from,$to,$policy_type){
    $heading_array = array("Alternate Policy No","Transaction date","Customer Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Marital Status","Dealer Code","Geo/Vertical","Pin Code");
    $csv_file_name = "ICICI-feedfile.csv";
    if($policy_type ==='endorse'){
            $policy_data = $this->Home_Model->getPAEndorseFeedfile($ic_id,$from,$to);
            $csv_file_name = "ICICI-endorse-feedfile.csv";
        }
        elseif($policy_type ==='canceled'){
            $policy_data = $this->Home_Model->getPACanceledFeedfile($ic_id,$from,$to);
            $heading_array = array("Alternate Policy No","Transaction date","Customer Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Marital Status","Dealer Code","Geo/Vertical","Pin Code","Reason Of Cancelation","Cancelation Date");
            $csv_file_name = "ICICI-canceled-feedfile.csv";
        }
        else{
            $policy_data = $this->Home_Model->getFeedFileDataForPaIc($ic_id,$from,$to);
        }
    $main_array1 = array();
        array_push($main_array1, $heading_array);
        
 //echo '<pre>';print_r($policy_data['result']);die;
    foreach($policy_data['result'] as $data){
        switch ($data['sum_insured']) {
                case 500000:
                   $gwp  = (14 * 5);
                    break;
                case 1000000:
                   $gwp  = (14 * 10);
                    break;
                case 1500000:
                   $gwp  = (14 * 15);
                    break;
                default:
                    $gwp = 0;
                    break;
                }
            $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;

            $policy_no = '4005/M/'.$data['chassis_no'];
            $Transaction_date = $data['policy_start_date'];
            $customer_name = $data['fname'].' '.$data['lname'];
            $contact_no = $data['mobile_no'];
            $email = $data['email'];
            $address = $data['addr1'];
            $location = $data['city_name'];
            $State = $data['state_name'];
            $policy_start_date = $data['policy_start_date'];
            $policy_end_date = $data['sold_policy_end_date'];
            $sum_insured = $data['sum_insured'];
            $Premium = $gwp;
            $DOB = $data['dob'];
            $gender = $data['gender'];
            $Marital_status = '';
            $dealer_code = $data['dealer_code'];
            $geo_vertical = $data['icici_geo'];
            $pin_code = $data['pincode'];
            $cancelation_reson = $data['cancellation_reson'];
            $cancellation_date = $data['cancellation_date'];

            $value_array = array($policy_no,$Transaction_date,$customer_name,$contact_no,$email,$address,$location,$State,$policy_start_date,$policy_end_date,$sum_insured,$Premium,$DOB,$gender,$Marital_status,$dealer_code,$geo_vertical,$pin_code);
            if($policy_type=='canceled'){
                    $value_array = array($policy_no,$Transaction_date,$customer_name,$contact_no,$email,$address,$location,$State,$policy_start_date,$policy_end_date,$sum_insured,$Premium,$DOB,$gender,$Marital_status,$dealer_code,$geo_vertical,$pin_code,$cancelation_reson,$cancellation_date);
                }

            array_push($main_array1, $value_array);

    }

    // echo '<pre>';print_r($main_array1);die('main_array1');
        
        echo array_to_csv($main_array1, $csv_file_name);
    
}

public function icici_endorse_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['endorse'] = 'endorse' ;
    $data['main_contain'] = 'admin/report/icici_feedfile';
    $this->load->view('admin/includes/template', $data);
}

public function icici_canceled_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['canceled'] = 'canceled' ;
    $data['main_contain'] = 'admin/report/icici_feedfile';
    $this->load->view('admin/includes/template', $data);
}

public function DownloadHdfcFeedfile($ic_id,$from,$to,$policy_type){
    $heading_array = array("Alternate Policy No","Transaction date","Customer Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Marital Status","Dealer Code","Geo/Vertical","Pin Code");
    $csv_file_name = "HDFC-feedfile.csv";
    if($policy_type ==='endorse'){
            $policy_data = $this->Home_Model->getPAEndorseFeedfile($ic_id,$from,$to);
            $csv_file_name = "HDFC-endorse-feedfile.csv";
        }
        elseif($policy_type ==='canceled'){
            $policy_data = $this->Home_Model->getPACanceledFeedfile($ic_id,$from,$to);
            $heading_array = array("Alternate Policy No","Transaction date","Customer Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Marital Status","Dealer Code","Geo/Vertical","Pin Code","Reason Of Cancelation","Cancelation Date");
            $csv_file_name = "HDFC-canceled-feedfile.csv";
        }
        else{
            $policy_data = $this->Home_Model->getFeedFileDataForPaIc($ic_id,$from,$to);
        }
    $main_array1 = array();
        array_push($main_array1, $heading_array);
        
 //echo '<pre>';print_r($policy_data['result']);die;
    foreach($policy_data['result'] as $data){
        switch ($data['sum_insured']) {
                case 500000:
                   $gwp  = (14 * 5);
                    break;
                case 1000000:
                   $gwp  = (14 * 10);
                    break;
                case 1500000:
                   $gwp  = (14 * 15);
                    break;
                default:
                    $gwp = 0;
                    break;
                }
            $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;

            $policy_no = '4005/M/'.$data['chassis_no'];
            $Transaction_date = $data['policy_start_date'];
            $customer_name = $data['fname'].' '.$data['lname'];
            $contact_no = $data['mobile_no'];
            $email = $data['email'];
            $address = $data['addr1'];
            $location = $data['city_name'];
            $State = $data['state_name'];
            $policy_start_date = $data['policy_start_date'];
            $policy_end_date = $data['sold_policy_end_date'];
            $sum_insured = $data['sum_insured'];
            $Premium = $gwp;
            $DOB = $data['dob'];
            $gender = $data['gender'];
            $Marital_status = '';
            $dealer_code = $data['dealer_code'];
            $geo_vertical = $data['icici_geo'];
            $pin_code = $data['pincode'];
            $cancelation_reson = $data['cancellation_reson'];
            $cancellation_date = $data['cancellation_date'];

            $value_array = array($policy_no,$Transaction_date,$customer_name,$contact_no,$email,$address,$location,$State,$policy_start_date,$policy_end_date,$sum_insured,$Premium,$DOB,$gender,$Marital_status,$dealer_code,$geo_vertical,$pin_code);
            if($policy_type=='canceled'){
                    $value_array = array($policy_no,$Transaction_date,$customer_name,$contact_no,$email,$address,$location,$State,$policy_start_date,$policy_end_date,$sum_insured,$Premium,$DOB,$gender,$Marital_status,$dealer_code,$geo_vertical,$pin_code,$cancelation_reson,$cancellation_date);
                }

            array_push($main_array1, $value_array);

    }

    // echo '<pre>';print_r($main_array1);die('main_array1');
        
        echo array_to_csv($main_array1, $csv_file_name);
    
}

public function hdfc_endorse_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['endorse'] = 'endorse' ;
    $data['main_contain'] = 'admin/report/hdfc_feedfile';
    $this->load->view('admin/includes/template', $data);
}

public function hdfc_canceled_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['canceled'] = 'canceled' ;
    $data['main_contain'] = 'admin/report/hdfc_feedfile';
    $this->load->view('admin/includes/template', $data);
}

 public function testDataTable(){
        $data['main_contain'] = 'admin/report/testdatatable';
        $this->load->view('admin/includes/template', $data);
 }

public function bharti_axa_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['main_contain'] = 'admin/report/bharti_axa_feedfile';
    $this->load->view('admin/includes/template', $data);
}

public function DownloadBhartiAxaFeedfile($ic_id,$from,$to,$policy_type){
    // echo "<pre>"; print_r($ic_id); echo "</pre>"; die('end of line yoyo');
    $where = array(
            'ic_id'=>$ic_id
        );
    $master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
    //echo '<pre>'; print_r($master_policy_details);die();
    $master_policy_no = $master_policy_details['master_policy_no'];
    $heading_array = array("Master Policy No","Policy No","Transaction date","Salutation","Customer Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Dealer Code","Pin Code","Nominee Name","Nominee Relationship","Nominee Age","Appointee Name","Appointee Relationship","Appointee Age",);
        $csv_file_name = "BhartiAxa-feedfile.csv";
    if($policy_type=='endorse'){
            $policy_data = $this->Home_Model->getPAEndorseFeedfile($ic_id,$from,$to);
            $csv_file_name = "BhartiAxa-endorse-feedfile.csv";
        }
        elseif($policy_type=='canceled'){
            $policy_data = $this->Home_Model->getPACanceledFeedfile($ic_id,$from,$to);
            $heading_array = array("Master Policy No","Policy No","Transaction date","Salutation","Customer Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Dealer Code","Pin Code","Nominee Name","Nominee Relationship","Nominee Age","Appointee Name","Appointee Relationship","Appointee Age","Reason Of Cancelation","Cancelation Date");
            $csv_file_name = "BhartiAxa-canceled-feedfile.csv";
        }
        else{
            $policy_data = $this->Home_Model->getFeedFileDataForPaIc($ic_id,$from,$to);
        }
    // echo '<pre>';print_r($policy_data);die;
    $main_array1 = array();
        array_push($main_array1, $heading_array);
        
        
 // echo '<pre>';print_r($policy_data['result']);die;
    foreach($policy_data['result'] as $data){
        switch ($data['sum_insured']) {
                case 500000:
                   $gwp  = (14 * 5);
                    break;
                case 1000000:
                   $gwp  = (14 * 10);
                    break;
                case 1500000:
                   $gwp  = (14 * 15);
                    break;
                default:
                    $gwp = 0;
                    break;
                }
            $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;

            $policy_no = $data['sold_policy_no'];
            $Transaction_date = $data['policy_start_date'];
            $customer_name = $data['fname'].' '.$data['lname'];
            $contact_no = $data['mobile_no'];
            $email = $data['email'];
            $address = $data['addr1'];
            $location = $data['city_name'];
            $State = $data['state_name'];
            $policy_start_date = $data['policy_start_date'];
            $policy_end_date = $data['sold_policy_end_date'];
            $sum_insured = $data['sum_insured'];
            $Premium = $gwp;
            $DOB = $data['dob'];
            $gender = $data['gender'];
            $salutation = ($gender == 'male')?'Mr':'Ms/Mrs';
            $dealer_code = $data['dealer_code'];
            $pin_code = $data['pincode'];
            $cancelation_reson = $data['cancellation_reson'];
            $cancellation_date = $data['cancellation_date'];
            $nominee_full_name = $data['nominee_full_name'];
            $nominee_relation = $data['nominee_relation'];
            $appointee_full_name = $data['appointee_full_name'];
            $nominee_age = $data['nominee_age'];
            $appointee_age = $data['appointee_age'];
            $appointee_relation = $data['appointee_relation'];

            $value_array = array($master_policy_no,$policy_no,$Transaction_date,$salutation,$customer_name,$contact_no,$email,$address,$location,$State,$policy_start_date,$policy_end_date,$sum_insured,$Premium,$DOB,$gender,$dealer_code,$pin_code,$nominee_full_name,$nominee_relation,$nominee_age,$appointee_full_name,$appointee_relation,$appointee_age);

            if($policy_type=='canceled'){
                    $value_array = array($master_policy_no,$policy_no,$Transaction_date,$salutation,$customer_name,$contact_no,$email,$address,$location,$State,$policy_start_date,$policy_end_date,$sum_insured,$Premium,$DOB,$gender,$dealer_code,$pin_code,$nominee_full_name,$nominee_relation,$nominee_age,$appointee_full_name,$appointee_relation,$appointee_age,$cancelation_reson,$cancellation_date);
                }
                
            array_push($main_array1, $value_array);

    }

    // echo '<pre>';print_r($main_array1);die('main_array1');
        echo array_to_csv($main_array1, $csv_file_name);
}

// public function DownloadBhartiAxaFeedfile($ic_id,$from,$to,$policy_type){
//     // echo "<pre>"; print_r($ic_id); echo "</pre>"; die('end of line yoyo');
//     $heading_array = array("Policy No","Transaction date","Customer Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Dealer Code","Pin Code","Nominee Name","Nominee Relationship","Nominee Age","Appointee Name","Appointee Relationship","Appointee Age",);
//         $csv_file_name = "BhartiAxa-feedfile.csv";
//     if($policy_type=='endorse'){
//             $policy_data = $this->Home_Model->getPAEndorseFeedfile($ic_id,$from,$to);
//             $csv_file_name = "BhartiAxa-endorse-feedfile.csv";
//         }
//         elseif($policy_type=='canceled'){
//             $policy_data = $this->Home_Model->getPACanceledFeedfile($ic_id,$from,$to);
//             $heading_array = array("Policy No","Transaction date","Customer Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Dealer Code","Pin Code","Nominee Name","Nominee Relationship","Nominee Age","Appointee Name","Appointee Relationship","Appointee Age","Reason Of Cancelation");
//             $csv_file_name = "BhartiAxa-canceled-feedfile.csv";
//         }
//         else{
//             $policy_data = $this->Home_Model->getFeedFileDataForPaIc($ic_id,$from,$to);
//         }
//     // echo '<pre>';print_r($policy_data);die;
//     $main_array1 = array();
//         array_push($main_array1, $heading_array);
        
        
//  // echo '<pre>';print_r($policy_data['result']);die;
//     foreach($policy_data['result'] as $data){
//         switch ($data['sum_insured']) {
//                 case 500000:
//                    $gwp  = (14 * 5);
//                     break;
//                 case 1000000:
//                    $gwp  = (14 * 10);
//                     break;
//                 case 1500000:
//                    $gwp  = (14 * 15);
//                     break;
//                 default:
//                     $gwp = 0;
//                     break;
//                 }
//             $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;

//             $policy_no = $data['sold_policy_no'];
//             $Transaction_date = $data['policy_start_date'];
//             $customer_name = $data['fname'].' '.$data['lname'];
//             $contact_no = $data['mobile_no'];
//             $email = $data['email'];
//             $address = $data['addr1'];
//             $location = $data['city_name'];
//             $State = $data['state_name'];
//             $policy_start_date = $data['pa_sold_policy_effective_date'];
//             $policy_end_date = $data['pa_sold_policy_end_date'];
//             $sum_insured = $data['sum_insured'];
//             $Premium = $gwp;
//             $DOB = $data['dob'];
//             $gender = $data['gender'];
//             $dealer_code = $data['dealer_code'];
//             $pin_code = $data['pincode'];
//             $cancelation_reson = $data['cancellation_reson'];
//             $nominee_full_name = $data['nominee_full_name'];
//             $nominee_relation = $data['nominee_relation'];
//             $appointee_full_name = $data['appointee_full_name'];
//             $nominee_age = $data['nominee_age'];
//             $appointee_age = $data['appointee_age'];
//             $appointee_relation = $data['appointee_relation'];

//             $value_array = array($policy_no,$Transaction_date,$customer_name,$contact_no,$email,$address,$location,$State,$policy_start_date,$policy_end_date,$sum_insured,$Premium,$DOB,$gender,$dealer_code,$pin_code,$nominee_full_name,$nominee_relation,$nominee_age,$appointee_full_name,$appointee_relation,$appointee_age);

//             if($policy_type=='canceled'){
//                     $value_array = array($policy_no,$Transaction_date,$customer_name,$contact_no,$email,$address,$location,$State,$policy_start_date,$policy_end_date,$sum_insured,$Premium,$DOB,$gender,$dealer_code,$pin_code,$nominee_full_name,$nominee_relation,$nominee_age,$appointee_full_name,$appointee_relation,$appointee_age,$cancelation_reson);
//                 }
                
//             array_push($main_array1, $value_array);

//     }

//     // echo '<pre>';print_r($main_array1);die('main_array1');
//         echo array_to_csv($main_array1, $csv_file_name);
// }

public function bhartiaxa_endorse_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['endorse'] = 'endorse' ;
    $data['main_contain'] = 'admin/report/bharti_axa_feedfile';
    $this->load->view('admin/includes/template', $data);
}

public function bhartiaxa_canceled_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['endorse'] = 'canceled' ;
    $data['main_contain'] = 'admin/report/bharti_axa_feedfile';
    $this->load->view('admin/includes/template', $data);
}

function TataOpnrsaFeedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['main_contain'] = 'admin/report/tata_opnrsa_feedfile';
    $this->load->view('admin/includes/template', $data);
}
function TataOpnrsaEndorseFeedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['endorse'] = 'endorse' ;
    $data['main_contain'] = 'admin/report/tata_opnrsa_feedfile';
    $this->load->view('admin/includes/template', $data);
}
function TataOpnrsaCanceledFeedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['endorse'] = 'canceled' ;
    $data['main_contain'] = 'admin/report/tata_opnrsa_feedfile';
    $this->load->view('admin/includes/template', $data);
}

function DownloadTATAOpnRSAFeedfile($ic_id,$from,$to,$policy_type){
    $heading_array = array("Policy No","Transaction date","Customer's First Name","Customer's Last Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Dealer Code","Pin Code","Nominee Name","Nominee Relationship","Appointee Name","Appointee Relationship");
    $csv_file_name = "TATA-OpnRSA-feedfile.csv";
    if($policy_type=='endorse'){
            $policy_data = $this->Home_Model->getTataOpnrsaEndorseFeedfile($ic_id,$from,$to);
            $csv_file_name = "TATA-Opnrsa-endorse-feedfile.csv";
        }
        elseif($policy_type=='canceled'){
            $policy_data = $this->Home_Model->getTataOpnrsaCanceledFeedfile($ic_id,$from,$to);
            $heading_array = array("Policy No","Transaction date","Customer's First Name","Customer's Last Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Dealer Code","Pin Code","Reason Of Cancelation","Nominee Name","Nominee Relationship","Appointee Name","Appointee Relationship","Cancelation Date");
            $csv_file_name = "TATA-Opnrsa-canceled-feedfile.csv";
        }
        else{
            $policy_data = $this->Home_Model->getTataOpnrsaFeedfile($ic_id,$from,$to);
        }
    $main_array1 = array();
        array_push($main_array1, $heading_array);
        
 // echo '<pre>';print_r($policy_data);die;
    foreach($policy_data as $data){

            $policy_no = $data['sold_policy_no'];
            $Transaction_date = $data['sold_policy_effective_date'];
            $fname = $data['fname'];
            $lname = $data['lname'];
            $contact_no = $data['mobile_no'];
            $email = $data['email'];
            $address = $data['addr1'];
            $location = $data['city_name'];
            $State = $data['state_name'];
            $policy_start_date = $data['sold_policy_effective_date'];
            $policy_end_date = $data['sold_policy_end_date'];
            $sum_insured = $data['sum_insured'];
            $Premium = $data['policy_premium'];
            $DOB = $data['dob'];
            $gender = $data['gender'];
            $dealer_code = $data['sap_ad_code'];
            $pin_code = $data['pincode'];
            $cancelation_reson = $data['cancellation_reson'];
            $cancellation_date = $data['cancellation_date'];
            $nominee_full_name = $data['nominee_full_name'];
            $nominee_relation = $data['nominee_relation'];
            $appointee_full_name = ($data['appointee_full_name'])?$data['appointee_full_name']:'';
            $appointee_relation = ($data['appointee_relation'])?$data['appointee_relation']:'';

            $value_array = array($policy_no,$Transaction_date,$fname,$lname,$contact_no,$email,$address,$location,$State,$policy_start_date,$policy_end_date,$sum_insured,$Premium,$DOB,$gender,$dealer_code,$pin_code,$nominee_full_name,$nominee_relation,$appointee_full_name,$appointee_relation);
            if($policy_type=='canceled'){
                $value_array = array($policy_no,$Transaction_date,$fname,$lname,$contact_no,$email,$address,$location,$State,$policy_start_date,$policy_end_date,$sum_insured,$Premium,$DOB,$gender,$dealer_code,$pin_code,$cancelation_reson,$nominee_full_name,$nominee_relation,$appointee_full_name,$appointee_relation,$cancellation_date);
            }
            array_push($main_array1, $value_array);

    }

    // echo '<pre>';print_r($main_array1);die('main_array1');
        echo array_to_csv($main_array1, $csv_file_name);
}

public function TataAigFeedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['main_contain'] = 'admin/report/tata_feedfile';
    $this->load->view('admin/includes/template', $data);
}  

public function DownloadTATAFeedfile($ic_id,$from,$to,$policy_type){
   $heading_array = array("Policy No","Transaction date","Customer's First Name","Customer's Last Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Dealer Code","Pin Code","Nominee Name","Nominee Relationship","Appointee Name","Appointee Relationship");
    $csv_file_name = "TATA-feedfile.csv";
    if($policy_type=='endorse'){
            $policy_data = $this->Home_Model->getPAEndorseFeedfile($ic_id,$from,$to);
            $csv_file_name = "TATA-endorse-feedfile.csv";
        }
        elseif($policy_type=='canceled'){
            $policy_data = $this->Home_Model->getPACanceledFeedfile($ic_id,$from,$to);
            $heading_array = array("Policy No","Transaction date","Customer's First Name","Customer's Last Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Dealer Code","Pin Code","Reason Of Cancelation","Nominee Name","Nominee Relationship","Appointee Name","Appointee Relationship","Cancelation Date");
            $csv_file_name = "TATA-canceled-feedfile.csv";
        }
        else{
            $policy_data = $this->Home_Model->getFeedFileDataForPaIc($ic_id,$from,$to);
        }
    $main_array1 = array();
        array_push($main_array1, $heading_array);
        
 // echo '<pre>';print_r($policy_data['result']);die;
    foreach($policy_data['result'] as $data){
        switch ($data['sum_insured']) {
                case 500000:
                   $gwp  = (14 * 5);
                    break;
                case 1000000:
                   $gwp  = (14 * 10);
                    break;
                case 1500000:
                   $gwp  = (14 * 15);
                    break;
                default:
                    $gwp = 0;
                    break;
                }
            $gwp_gst = ($gwp >0)?((18 / 100) * $gwp):0;

            $policy_no = $data['sold_policy_no'];
            $Transaction_date = $data['policy_start_date'];
            $fname = $data['fname'];
            $lname = $data['lname'];
            $contact_no = $data['mobile_no'];
            $email = $data['email'];
            $address = $data['addr1'];
            $location = $data['city_name'];
            $State = $data['state_name'];
            $policy_start_date = $data['policy_start_date'];
            $policy_end_date = $data['sold_policy_end_date'];
            $sum_insured = $data['sum_insured'];
            $Premium = $gwp;
            $DOB = $data['dob'];
            $gender = $data['gender'];
            $dealer_code = $data['dealer_code'];
            $pin_code = $data['pincode'];
            $cancelation_reson = $data['cancellation_reson'];
            $cancelation_reson = $data['cancellation_date'];
            $nominee_full_name = $data['nominee_full_name'];
            $nominee_relation = $data['nominee_relation'];
            $appointee_full_name = ($data['appointee_full_name'])?$data['appointee_full_name']:'';
            $appointee_relation = ($data['appointee_relation'])?$data['appointee_relation']:'';

            $value_array = array($policy_no,$Transaction_date,$fname,$lname,$contact_no,$email,$address,$location,$State,$policy_start_date,$policy_end_date,$sum_insured,$Premium,$DOB,$gender,$dealer_code,$pin_code,$nominee_full_name,$nominee_relation,$appointee_full_name,$appointee_relation);
            if($policy_type=='canceled'){
                $value_array = array($policy_no,$Transaction_date,$fname,$lname,$contact_no,$email,$address,$location,$State,$policy_start_date,$policy_end_date,$sum_insured,$Premium,$DOB,$gender,$dealer_code,$pin_code,$cancelation_reson,$nominee_full_name,$nominee_relation,$appointee_full_name,$appointee_relation,$cancellation_date);
            }
            array_push($main_array1, $value_array);

    }

    // echo '<pre>';print_r($main_array1);die('main_array1');
        echo array_to_csv($main_array1, $csv_file_name);
} 


public function tata_endorse_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['endorse'] = 'endorse' ;
    $data['main_contain'] = 'admin/report/tata_feedfile';
    $this->load->view('admin/includes/template', $data);
}

public function tata_canceled_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['endorse'] = 'canceled' ;
    $data['main_contain'] = 'admin/report/tata_feedfile';
    $this->load->view('admin/includes/template', $data);
}

function cover_oriental_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['main_contain'] = 'admin/report/oriental_cover_feedfile';
    $this->load->view('admin/includes/template', $data);
}

function download_oriental_cover_feedfile(){
    $post_data = $this->input->post();
    // echo '<pre>';print_r($post_data);die('hello');
    $from_date = $this->input->post('from_date');
    $to_date =$this->input->post('to_date');
    $ic_id =$this->input->post('ic_id');
    $policy_data = $this->Home_Model->getOiclCoverFeedFile($ic_id,$from_date,$to_date); 
    $count = $this->Home_Model->getOrientalCount($from_date);
    // echo '<pre>';print_r($policy_data);die;

    $this->load->library('excel');

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'System Code'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Sr. No.'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'System Table code'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Rate');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Sum Insured');
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Per Mile');

 
    
    $rowCount = 2;
    foreach ($policy_data['result'] as $row) {
        $oriental_count = ++$count['count'];
        // print_r($row);
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount,'C');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['sold_policy_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'MIST-CVR-219');
        switch ($row['sum_insured']) {
            case 500000:
                $rate = '0.01';
                break;
            case 100000:
                $rate = '0.01';
                break;
            case 1000000:
                $rate = '0.095';
                break;
            case 1500000:
                $rate = '0.093';
                break;
        }
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $rate);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row['sum_insured']);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $oriental_count);

         $rowCount++;
    }

//     // die;
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

            $fileName = 'oriental_cover_feedfile.xlsx';  

            ob_start();
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response =  array(
                    'op' => 'ok',
                    'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
                );

            die(json_encode($response));
}

function level_oriental_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['main_contain'] = 'admin/report/oriental_level_feedfile';
    $this->load->view('admin/includes/template', $data);
}

function download_oriental_level_feedfile(){
    $from_date = $this->input->post('from_date');
    $to_date =$this->input->post('to_date');
    $ic_id =$this->input->post('ic_id');
    $policy_data = $this->Home_Model->getFeedFileDataForPaIc($ic_id,$from_date,$to_date);
    $count = $this->Home_Model->getOrientalCount($from_date);

    // echo '<pre>';print_r($count);//die;

    // echo '<pre>'; print_r($policy_data);die('hello');
    $this->load->library('excel');

$objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'System Code'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'System Code'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Name Of Employee'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Emp. Code');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Date Of Birth');
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Gender');
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'System Risk Code');
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Salary PM');
    $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'System Risk Code');
    $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Medical Ext. Code');
    $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Pre Existing Disease');
    $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Sr. NO.');

 
    
    $rowCount = 2;
    foreach ($policy_data['result'] as $row) {
        $gender = (trim($row['gender'])=='male')?'M':'F';
        $date = new DateTime($row['dob']);
        $dob = $date->format('d-M-Y');
        $oriental_count = ++$count['count'];
        // print_r($row);
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount,'R');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 11);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['fname'].' '.$row['lname']);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['sold_policy_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $dob);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $gender);
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, 'OCCUP2');
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, '25000');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, 'CLASS_2');
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, 'MIST_NEXT_03');
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, 'NIL');
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $oriental_count);

         $rowCount++;
    }

//     // die;
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

            $fileName = 'oriental_level_feedfile.xlsx';  

            ob_start();
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response =  array(
                    'op' => 'ok',
                    'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
                );

            die(json_encode($response));
}

function oriental_canceled_feedfile($ic_id){
    $data['ic_id'] = $ic_id ;
    $data['main_contain'] = 'admin/report/oriental_cancel_feedfile';
    $this->load->view('admin/includes/template', $data);
}

function download_oriental_cancelled_feedfile(){
    $from_date = $this->input->post('from_date');
    $to_date =$this->input->post('to_date');
    $ic_id =$this->input->post('ic_id');
    $policy_data = $this->Home_Model->getOrientalCancelledFeedFile($ic_id,$from_date,$to_date);
    $count = $this->Home_Model->getOrientalCount($from_date);

    //echo '<pre>';print_r($count);die;

    // echo '<pre>'; print_r($policy_data);die('hello');
    $this->load->library('excel');

$objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'System Code'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'System Code'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Name Of Employee'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Emp. Code');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Date Of Birth');
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Gender');
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'System Risk Code');
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Salary PM');
    $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'System Risk Code');
    $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Medical Ext. Code');
    $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Pre Existing Disease');
    $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Sr. NO.');
    $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Created Date');
    $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Canceled Date');

 
    
    $rowCount = 2;
    foreach ($policy_data['result'] as $row) {
        $gender = (trim($row['gender'])=='male')?'M':'F';
        $date = new DateTime($row['dob']);
        $dob = $date->format('d-M-Y');
        $oriental_count = ++$count['count'];
        // print_r($row);
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount,'R');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 11);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['fname'].' '.$row['lname']);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['sold_policy_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $dob);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $gender);
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, 'OCCUP2');
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, '25000');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, 'CLASS_2');
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, 'MIST_NEXT_03');
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, 'NIL');
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $oriental_count);
        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $row['created_date']);
        $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $row['cancellation_date']);

         $rowCount++;
    }

//     // die;
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

            $fileName = 'oriental_cancelled_feedfile.xlsx';  

            ob_start();
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response =  array(
                    'op' => 'ok',
                    'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
                );

            die(json_encode($response));
}

function PincodeList(){
    $data['states'] = $this->Home_Model->getStateByPincodeMaster();
    // echo "<pre>"; print_r($data['states']); echo "</pre>"; die('end of line yoyo');
    $data['main_contain'] = 'admin/report/pincode_list';
    $this->load->view('admin/includes/template', $data);
}

function GetCityList(){
    // echo "<pre>"; print_r($_POST); echo "</pre>"; die('end of line yoyo');
    $pincode = $this->input->post('pin_code');
    $pincode_id = $this->input->post('pincode_id');
    $pincode_data = $this->Home_Model->getRowDataFromTable('tvs_pincode_master',['id'=>$pincode_id]);
    // echo "<pre>"; print_r($pincode_data); echo "</pre>"; die('end of line yoyo');
    $state_val_post = $this->input->post('state_val');
    $state_id =($pincode_data['state_id'])? $pincode_data['state_id'] : $state_val_post;
    $selected_city = $pincode_data['city_id'];
    $cities = $this->Home_Model->getCityByPincodeMaster($state_id);
    // echo "<pre>"; print_r($cities); echo "</pre>"; die('end of line yoyo');
    $html['pincode'] = $pincode;
    $html['state_name'] = $pincode_data['state_name'];
    // $html['selected_city_id'] = $pincode_data['city_id'];
    $html['html'] = '<option value="">Select</option>';
    foreach ($cities as $city) {
        $selected = '';
        if($selected_city==$city['city_id']){
            $selected = 'selected';
        }
        $html['html'].= '<option value="'.$city['city_id'].'" '.$selected.'>'.$city['district_name'].'</option>';
    }
    echo json_encode($html);
}

function PincodeListAjax(){
    //print_R($_REQUEST);exit;
    $admin_session = $this->session->userdata('admin_session');
    $requestData = $_REQUEST;
    $start = $requestData['start'];
    $length = $requestData['length'];
    $columns = array(
        0 => 'state_name',
        1 => 'district_name',
        2 => 'state_id',
        3 => 'city_id',
    );
    $sql = "SELECT * FROM tvs_pincode_master WHERE pin_code='".$requestData['pincode']."' ORDER BY id DESC";
    //die($sql);
    $totalFiltered = $totalData;

    $query = $this->db->query($sql);
    $totalFiltered = $query->num_rows();
    $result = $query->result();
    $data = array();
    $i = 1;
    foreach ($result as $main) {
        
        $edit_pin_btn='<button id="p_'.$main->id.'" class="btn btn-success" data-pin_code="'.$main->pin_code.'" onclick="EditPincode('.$main->id.') "><i class="fa fa-edit"></i></button> ';

        $row = array();
        $row[] = $i++;
        $row[] = $main->state_name;
        $row[] = $main->district_name;
        $row[] = $main->pin_code;
        $row[] = $edit_pin_btn;
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
function SubmitPincodeData(){
    $post = $this->input->post();
    // echo "<pre>"; print_r($post); echo "</pre>"; die('end of line yoyo');
    $where = array('pin_code' => $post['pincode']);
    $exist_pincode = $this->Home_Model->getDataFromTable('tvs_pincode_master',$where);
    $state_id = $post['state_id'];
    $sql = "SELECT state_name,state_id,state_cleaned FROM tvs_pincode_master WHERE state_id='$state_id' GROUP BY state_id";
    $state_data = $this->db->query($sql)->row_array();
    $city_id = $post['city_id'];
    $city_data = $this->Home_Model->getCityBYCityid($city_id);
    // echo "<pre>"; print_r($state_data); echo "</pre>"; die('end of line yoyo');
    // echo "<pre>"; print_r($city_data); echo "</pre>"; die('end of line yoyo');
    $length = strlen(trim($post['pincode']));
    if($length!=6){
        $this->session->set_flashdata('success','Pincode Should be 6 digit number.');
        redirect('admin/pincode_list');
    }
    if(empty($exist_pincode) || $exist_pincode==''){
        $insert_pincode = array(
            'state_id' => $post['state_id'],
            'state_cleaned'=>$state_data['state_cleaned'],
            'state_name'=>strtoupper($state_data['state_name']),
            'district_name'=>strtoupper($city_data['district_name']),
            'district_id_pk'=>$city_data['district_id_pk'],
            'city_or_village_name'=>strtoupper($city_data['city_or_village_name']),
            'city_id'=> $post['city_id'],
            'city_cleaned'=>$city_data['city_cleaned'],
            'pin_code'=>$post['pincode']
        );
        // echo "<pre>"; print_r($insert_pincode); echo "</pre>"; die('end of line yoyo');
        $insert = $this->Home_Model->insertIntoTable('tvs_pincode_master',$insert_pincode);
        if(!empty($insert)){
            $this->session->set_flashdata('success','Pincode is Added');
        }else{
            $this->session->set_flashdata('success','Something Went Wrong Please Try Again.');
        }
    }else{
        $this->session->set_flashdata('success','Pincode is Already Exist.');
    }
        redirect('admin/pincode_list');
}

// function UpdatePincodeData(){
//     echo "<pre>"; print_r($_POST); echo "</pre>"; die('end of line yoyo');
//     $pincode = $this->input->post('selected_pincode');
//     $edit_city_id = $this->input->post('edit_city_id');
//     $selected_city_id = $this->input->post('selected_city_id');
//     $city_data = $this->Home_Model->getCityBYCityid($edit_city_id);
//     // echo "<pre>"; print_r($city_data); echo "</pre>"; die('end of line yoyo');
//     $where = array('pin_code'=>$pincode,'city_id'=>$selected_city_id);
//     $update_pin = array(
//         'district_name'=>strtoupper($city_data['district_name']),
//         'district_id_pk'=>$city_data['district_id_pk'],
//         'city_or_village_name'=>strtoupper($city_data['city_or_village_name']),
//         'city_id'=> $edit_city_id,
//         'city_cleaned'=>$city_data['city_cleaned'],
//     );
//     $update = $this->Home_Model->updateTable('tvs_pincode_master',$update_pin,$where);
//     if(!empty($update)){
//         $this->session->set_flashdata('success','Pincode is Updated.');
//     }else{
//         $this->session->set_flashdata('success','Something Went Wrong,Please Try Again.');
//     }

//     redirect('admin/pincode_list');
// }

function UpdatePincodeData(){
    $edit_city_id = $this->input->post('edit_city_id');
    $pincode_id = $this->input->post('pincode_id');
    $pincode_data = $this->Home_Model->getRowDataFromTable('tvs_pincode_master',['id'=>$pincode_id]);
    // echo "<pre>"; print_r($pincode_data); echo "</pre>"; die('end of line yoyo');
    $pin_code =$pincode_data['pin_code'];
    $selected_city_id = $pincode_data['city_id'];
    $city_data = $this->Home_Model->getCityBYCityid($edit_city_id);
// echo "<pre>"; print_r($city_data); echo "</pre>"; die('end of line yoyo');
    $where = array('pin_code'=>$pin_code,'city_id'=>$selected_city_id);
    $update_pin = array(
        'district_name'=>strtoupper($city_data['district_name']),
        'district_id_pk'=>$city_data['district_id_pk'],
        'city_or_village_name'=>strtoupper($city_data['city_or_village_name']),
        'city_id'=> $edit_city_id,
        'city_cleaned'=>$city_data['city_cleaned'],
    );
    // echo "<pre>"; print_r($update_pin); echo "</pre>"; die('end of line yoyo');
    $update = $this->Home_Model->updateTable('tvs_pincode_master',$update_pin,$where);
    if(!empty($update)){
        $this->session->set_flashdata('success','Pincode is Updated.');
    }else{
        $this->session->set_flashdata('success','Something Went Wrong,Please Try Again.');
    }

    redirect('admin/pincode_list');
}


function ModelList(){
    $data['main_contain'] = 'admin/report/model_list';
    $this->load->view('admin/includes/template', $data);
}

function ModelListAjax(){
    $admin_session = $this->session->userdata('admin_session');
    $requestData = $_REQUEST;
    $start = $requestData['start'];
    $length = $requestData['length'];
    $columns = array(
        0 => 'model_name',
        1 => 'model',
        2 => 'model_code',
        3 => 'OEM',
    );
    $sql = "SELECT * FROM tvs_model_master ORDER BY model_id DESC";
    //die($sql);
    $totalFiltered = $totalData;

    $query = $this->db->query($sql);
    $totalFiltered = $query->num_rows();
    $result = $query->result();
    $data = array();
    $i = 1;
    foreach ($result as $main) {
        $row = array();
        $row[] = $i++;
        $row[] = $main->model_name;
        $row[] = $main->model;
        $row[] = $main->model_code;
        $row[] = $main->OEM;
        $row[] = $main->OEM_subtype;
        $row[] = $main->created_at;
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

function SubmitModelData(){
    $post_data = $this->input->post();
    // echo "<pre>"; print_r($post_data); echo "</pre>"; die('end of line yoyo');
    if(!empty($post_data['model_name']) && !empty($post_data['model']) ){
        $model_name = strtoupper($post_data['model_name']);
        $sql = "SELECT * FROM tvs_model_master WHERE model_name ='$model_name' ";
        $row = $this->db->query($sql)->row_array();
        if(empty($row)){
            // not exist
            $data = array(
                'model_name' =>$model_name,
                'model' => $post_data['model'],
                'OEM' =>'TV',
                'OEM_subtype' =>'TW',
                'model_code' =>($post_data['model_code'])?$post_data['model_code']:'',
                'created_at' => date("Y-m-d H:i:s")
            );
            $insert_id = $this->Home_Model->insertIntoTable('tvs_model_master',$data);
            if(!empty($insert_id)){
                $this->session->set_flashdata('success','Model Is Added');
            }else{
                $this->session->set_flashdata('success','Something Went Wrong Please Try Again !');
            }
        }else{
            // Exist
            $this->session->set_flashdata('success','Model Is Already Exist');
        }
    }else{
        $this->session->set_flashdata('success','Model Detail is required');
    }
    redirect('admin/model_list');
}

public function target_achivement(){
    $data['layer_one_details'] = $layer_one_details =  $this->Home_Model->getLayerTwoDetails();
    $data['target_data'] = $this->Home_Model->getTargetData();
    $data['deposit_status'] = $deposit_status =  $this->Home_Model->deposit_status();  
    $party_payment_details = $this->Home_Model->partyPaymentDetails();
    $tvs_deposit =  $this->Home_Model->tvs_deposit();

    $data['ytd_tvs'] = $tvs_deposit['ytd_tvs_deposit_amount'];
    $data['ytd_gst'] = $tvs_deposit['ytd_gst_deposit_amount'];
    $data['ytd_tds'] = $tvs_deposit['ytd_tds_deposit_amount'];    

    $data['ytd_platinum_input_tds'] = ($layer_one_details['ytd_platinum_policy_count'] * 4.2) + ($layer_one_details['ytd_platinum_policy_count'] * 0.32) + ($layer_one_details['ytd_platinum_policy_count'] * 3.75) + ($layer_one_details['ytd_platinum_policy_count'] * 5.7);
    
    $data['ytd_sapphire_input_tds'] = ($layer_one_details['ytd_sapphire_policy_count'] * 4.2) + ($layer_one_details['ytd_sapphire_policy_count'] * 0.64) + ($layer_one_details['ytd_sapphire_policy_count'] * 4) + ($layer_one_details['ytd_sapphire_policy_count'] * 5.4);
    
    $data['ytd_gold_input_tds'] = ($layer_one_details['ytd_gold_policy_count'] * 2.8) + ($layer_one_details['ytd_gold_policy_count'] * 0.32) + ($layer_one_details['ytd_gold_policy_count'] * 3) + ($layer_one_details['ytd_gold_policy_count'] * 5.7);
    
    $data['ytd_silver_input_tds'] = ($layer_one_details['ytd_silver_policy_count'] * 1.4) + ($layer_one_details['ytd_silver_policy_count'] * 0.32) + ($layer_one_details['ytd_silver_policy_count'] * 2.5) + ($layer_one_details['ytd_silver_policy_count'] * 5.35);     

    $data['ytd_input_tds'] = $data['ytd_platinum_input_tds'] + $data['ytd_sapphire_input_tds'] + $data['ytd_gold_input_tds'] + $data['ytd_silver_input_tds'];    

    $data['giib_td_kotak_platinum_margin'] = ($layer_one_details['td_kotak_platinum_policy_count'] * 210 * 0.15);
    $data['giib_td_il_platinum_margin'] = ($layer_one_details['td_il_platinum_policy_count'] * 210 * 0.05);
    $data['giib_td_tata_platinum_margin'] = ($layer_one_details['td_tata_platinum_policy_count'] * 210 * 0.075);
    $data['giib_td_bagi_platinum_margin'] = ($layer_one_details['td_bagi_platinum_policy_count'] * 210 * 0.10);
    $data['giib_td_platinum_policy_margin'] = ($data['giib_td_kotak_platinum_margin'] + $data['giib_td_il_platinum_margin'] + $data['giib_td_tata_platinum_margin'] + $data['giib_td_bagi_platinum_margin']);
    
    $data['giib_td_kotak_sapphire_margin'] = ($layer_one_details['td_kotak_sapphire_policy_count'] * 210 * 0.15);
    $data['giib_td_il_sapphire_margin'] = ($layer_one_details['td_il_sapphire_policy_count'] * 210 * 0.05);
    $data['giib_td_tata_sapphire_margin'] = ($layer_one_details['td_tata_sapphire_policy_count'] * 210 * 0.075);
    $data['giib_td_bagi_sapphire_margin'] = ($layer_one_details['td_bagi_sapphire_policy_count'] * 210 * 0.10);
    $data['giib_td_sapphire_policy_margin'] = ($data['giib_td_kotak_sapphire_margin'] + $data['giib_td_il_sapphire_margin'] + $data['giib_td_tata_sapphire_margin'] + $data['giib_td_bagi_sapphire_margin']);
    
    $data['giib_td_kotak_gold_margin'] = ($layer_one_details['td_kotak_gold_policy_count'] * 140 * 0.15);
    $data['giib_td_il_gold_margin'] = ($layer_one_details['td_il_gold_policy_count'] * 140 * 0.05);
    $data['giib_td_tata_gold_margin'] = ($layer_one_details['td_tata_gold_policy_count'] * 140 * 0.075);
    $data['giib_td_bagi_gold_margin'] = ($layer_one_details['td_bagi_gold_policy_count'] * 140 * 0.10);
    $data['giib_td_gold_policy_margin'] = ($data['giib_td_kotak_gold_margin'] + $data['giib_td_il_gold_margin'] + $data['giib_td_tata_gold_margin'] + $data['giib_td_bagi_gold_margin']);
    
    $data['giib_td_kotak_silver_margin'] = ($layer_one_details['td_kotak_silver_policy_count'] * 70 * 0.15);
    $data['giib_td_il_silver_margin'] = ($layer_one_details['td_il_silver_policy_count'] * 70 * 0.05);
    $data['giib_td_tata_silver_margin'] = ($layer_one_details['td_tata_silver_policy_count'] * 70 * 0.075);
    $data['giib_td_bagi_silver_margin'] = ($layer_one_details['td_bagi_silver_policy_count'] * 70 * 0.10);
    $data['giib_td_silver_policy_margin'] = ($data['giib_td_kotak_silver_margin'] + $data['giib_td_il_silver_margin'] + $data['giib_td_tata_silver_margin'] + $data['giib_td_bagi_silver_margin']);
    
    $data['giib_mtd_kotak_platinum_margin'] = ($layer_one_details['mtd_kotak_platinum_policy_count'] * 210 * 0.15);
    $data['giib_mtd_il_platinum_margin'] = ($layer_one_details['mtd_il_platinum_policy_count'] * 210 * 0.05);
    $data['giib_mtd_tata_platinum_margin'] = ($layer_one_details['mtd_tata_platinum_policy_count'] * 210 * 0.075);
    $data['giib_mtd_bagi_platinum_margin'] = ($layer_one_details['mtd_bagi_platinum_policy_count'] * 210 * 0.10);
    $data['giib_mtd_platinum_policy_margin'] = ($data['giib_mtd_kotak_platinum_margin'] + $data['giib_mtd_il_platinum_margin'] + $data['giib_mtd_tata_platinum_margin'] + $data['giib_mtd_bagi_platinum_margin']);
    
    $data['giib_mtd_kotak_sapphire_margin'] = ($layer_one_details['mtd_kotak_sapphire_policy_count'] * 210 * 0.15);
    $data['giib_mtd_il_sapphire_margin'] = ($layer_one_details['mtd_il_sapphire_policy_count'] * 210 * 0.05);
    $data['giib_mtd_tata_sapphire_margin'] = ($layer_one_details['mtd_tata_sapphire_policy_count'] * 210 * 0.075);
    $data['giib_mtd_bagi_sapphire_margin'] = ($layer_one_details['mtd_bagi_sapphire_policy_count'] * 210 * 0.10);
    $data['giib_mtd_sapphire_policy_margin'] = ($data['giib_mtd_kotak_sapphire_margin'] + $data['giib_mtd_il_sapphire_margin'] + $data['giib_mtd_tata_sapphire_margin'] + $data['giib_mtd_bagi_sapphire_margin']);
    
    $data['giib_mtd_kotak_gold_margin'] = ($layer_one_details['mtd_kotak_gold_policy_count'] * 140 * 0.15);
    $data['giib_mtd_il_gold_margin'] = ($layer_one_details['mtd_il_gold_policy_count'] * 140 * 0.05);
    $data['giib_mtd_tata_gold_margin'] = ($layer_one_details['mtd_tata_gold_policy_count'] * 140 * 0.075);
    $data['giib_mtd_bagi_gold_margin'] = ($layer_one_details['mtd_bagi_gold_policy_count'] * 140 * 0.10);
    $data['giib_mtd_gold_policy_margin'] = ($data['giib_mtd_kotak_gold_margin'] + $data['giib_mtd_il_gold_margin'] + $data['giib_mtd_tata_gold_margin'] + $data['giib_mtd_bagi_gold_margin']);
    
    $data['giib_mtd_kotak_silver_margin'] = ($layer_one_details['mtd_kotak_silver_policy_count'] * 70 * 0.15);
    $data['giib_mtd_il_silver_margin'] = ($layer_one_details['mtd_il_silver_policy_count'] * 70 * 0.05);
    $data['giib_mtd_tata_silver_margin'] = ($layer_one_details['mtd_tata_silver_policy_count'] * 70 * 0.075);
    $data['giib_mtd_bagi_silver_margin'] = ($layer_one_details['mtd_bagi_silver_policy_count'] * 70 * 0.10);
    $data['giib_mtd_silver_policy_margin'] = ($data['giib_mtd_kotak_silver_margin'] + $data['giib_mtd_il_silver_margin'] + $data['giib_mtd_tata_silver_margin'] + $data['giib_mtd_bagi_silver_margin']);
   
    $data['giib_ytd_kotak_platinum_margin'] = ($layer_one_details['ytd_kotak_platinum_policy_count'] * 210 * 0.15);
    $data['giib_ytd_il_platinum_margin'] = ($layer_one_details['ytd_il_platinum_policy_count'] * 210 * 0.05);
    $data['giib_ytd_tata_platinum_margin'] = ($layer_one_details['ytd_tata_platinum_policy_count'] * 210 * 0.075);
    $data['giib_ytd_bagi_platinum_margin'] = ($layer_one_details['ytd_bagi_platinum_policy_count'] * 210 * 0.10);
    $data['giib_ytd_platinum_policy_margin'] = ($data['giib_ytd_kotak_platinum_margin'] + $data['giib_ytd_il_platinum_margin'] + $data['giib_ytd_tata_platinum_margin'] + $data['giib_ytd_bagi_platinum_margin']);
    
    $data['giib_ytd_kotak_sapphire_margin'] = ($layer_one_details['ytd_kotak_sapphire_policy_count'] * 210 * 0.15);
    $data['giib_ytd_il_sapphire_margin'] = ($layer_one_details['ytd_il_sapphire_policy_count'] * 210 * 0.05);
    $data['giib_ytd_tata_sapphire_margin'] = ($layer_one_details['ytd_tata_sapphire_policy_count'] * 210 * 0.075);
    $data['giib_ytd_bagi_sapphire_margin'] = ($layer_one_details['ytd_bagi_sapphire_policy_count'] * 210 * 0.10);
    $data['giib_ytd_sapphire_policy_margin'] = ($data['giib_ytd_kotak_sapphire_margin'] + $data['giib_ytd_il_sapphire_margin'] + $data['giib_ytd_tata_sapphire_margin'] + $data['giib_ytd_bagi_sapphire_margin']);
    
    $data['giib_ytd_kotak_gold_margin'] = ($layer_one_details['ytd_kotak_gold_policy_count'] * 140 * 0.15);
    $data['giib_ytd_il_gold_margin'] = ($layer_one_details['ytd_il_gold_policy_count'] * 140 * 0.05);
    $data['giib_ytd_tata_gold_margin'] = ($layer_one_details['ytd_tata_gold_policy_count'] * 140 * 0.075);
    $data['giib_ytd_bagi_gold_margin'] = ($layer_one_details['ytd_bagi_gold_policy_count'] * 140 * 0.10);
    $data['giib_ytd_gold_policy_margin'] = ($data['giib_ytd_kotak_gold_margin'] + $data['giib_ytd_il_gold_margin'] + $data['giib_ytd_tata_gold_margin'] + $data['giib_ytd_bagi_gold_margin']);
    
    $data['giib_ytd_kotak_silver_margin'] = ($layer_one_details['ytd_kotak_silver_policy_count'] * 70 * 0.15);
    $data['giib_ytd_il_silver_margin'] = ($layer_one_details['ytd_il_silver_policy_count'] * 70 * 0.05);
    $data['giib_ytd_tata_silver_margin'] = ($layer_one_details['ytd_tata_silver_policy_count'] * 70 * 0.075);
    $data['giib_ytd_bagi_silver_margin'] = ($layer_one_details['ytd_bagi_silver_policy_count'] * 70 * 0.10);
    $data['giib_ytd_silver_policy_margin'] = ($data['giib_ytd_kotak_silver_margin'] + $data['giib_ytd_il_silver_margin'] + $data['giib_ytd_tata_silver_margin'] + $data['giib_ytd_bagi_silver_margin']);

    $data['td_giib_margin'] = $data['giib_td_silver_policy_margin'] + $data['giib_td_gold_policy_margin'] + $data['giib_td_sapphire_policy_margin'] + $data['giib_td_platinum_policy_margin'];
    $data['mtd_giib_margin'] = $data['giib_mtd_silver_policy_margin'] + $data['giib_mtd_gold_policy_margin'] + $data['giib_mtd_sapphire_policy_margin'] + $data['giib_mtd_platinum_policy_margin'];
    $data['ytd_giib_margin'] = $data['giib_ytd_silver_policy_margin'] + $data['giib_ytd_gold_policy_margin'] + $data['giib_ytd_sapphire_policy_margin'] + $data['giib_ytd_platinum_policy_margin'];    

    $data['kotak_td_deposit'] = $party_payment_details['kotak_td_deposit'];
    $data['kotak_mtd_deposit'] = $party_payment_details['kotak_mtd_deposit'];
    $data['kotak_ytd_deposit'] = $party_payment_details['kotak_ytd_deposit'];

    $data['il_td_deposit'] = $party_payment_details['il_td_deposit'];
    $data['il_mtd_deposit'] = $party_payment_details['il_mtd_deposit'];
    $data['il_ytd_deposit'] = $party_payment_details['il_ytd_deposit'];

    $data['tata_td_deposit'] = $party_payment_details['tata_td_deposit'];
    $data['tata_mtd_deposit'] = $party_payment_details['tata_mtd_deposit'];
    $data['tata_ytd_deposit'] = $party_payment_details['tata_ytd_deposit'];
    
    $data['bagi_td_deposit'] = $party_payment_details['bagi_td_deposit'];
    $data['bagi_mtd_deposit'] = $party_payment_details['bagi_mtd_deposit'];
    $data['bagi_ytd_deposit'] = $party_payment_details['bagi_ytd_deposit'];
    
    $data['bharti_assist_td_deposit'] = $party_payment_details['bharti_assist_td_deposit'];
    $data['bharti_assist_mtd_deposit'] = $party_payment_details['bharti_assist_mtd_deposit'];
    $data['bharti_assist_ytd_deposit'] = $party_payment_details['bharti_assist_ytd_deposit'];    

    $ytd_kotak_platinum_policy_count = $layer_one_details['ytd_kotak_platinum_policy_count'] * (210 + (210 * 0.18)) ;
    $ytd_kotak_sapphire_policy_count = $layer_one_details['ytd_kotak_sapphire_policy_count'] * (210 + (210 * 0.18)) ;
    $ytd_kotak_gold_policy_count = $layer_one_details['ytd_kotak_gold_policy_count'] * (140 + (140 * 0.18)) ;
    $ytd_kotak_silver_policy_count = $layer_one_details['ytd_kotak_silver_policy_count'] * (70 + (70 * 0.18));

    $ytd_il_platinum_policy_count = $layer_one_details['ytd_il_platinum_policy_count'] * (210 + (210 * 0.18));
    $ytd_il_sapphire_policy_count = $layer_one_details['ytd_il_sapphire_policy_count'] * (210 + (210 * 0.18));
    $ytd_il_gold_policy_count = $layer_one_details['ytd_il_gold_policy_count'] * (140 + (140 * 0.18));
    $ytd_il_silver_policy_count = $layer_one_details['ytd_il_silver_policy_count'] * (70 + (70 * 0.18));

    $ytd_tata_platinum_policy_count = $layer_one_details['ytd_tata_platinum_policy_count'] * (210 + (210 * 0.18));
    $ytd_tata_sapphire_policy_count = $layer_one_details['ytd_tata_sapphire_policy_count'] * (210 + (210 * 0.18));
    $ytd_tata_gold_policy_count = $layer_one_details['ytd_tata_gold_policy_count'] * (140 + (140 * 0.18));
    $ytd_tata_silver_policy_count = $layer_one_details['ytd_tata_silver_policy_count'] * (70 + (70 * 0.18));

    $ytd_bagi_platinum_policy_count = $layer_one_details['ytd_bagi_platinum_policy_count'] * (210 + (210 * 0.18));
    $ytd_bagi_sapphire_policy_count = $layer_one_details['ytd_bagi_sapphire_policy_count'] * (210 + (210 * 0.18));
    $ytd_bagi_gold_policy_count = $layer_one_details['ytd_bagi_gold_policy_count'] * (140 + (140 * 0.18));
    $ytd_bagi_silver_policy_count = $layer_one_details['ytd_bagi_silver_policy_count'] * (70 + (70 * 0.18));    

    $data['bharti_ytd_policy_count'] = $party_payment_details['bharti_ytd_policy_count'] * (16 + (16 * 0.18));

    $data['kotak_ytd_policy_amount'] = $ytd_kotak_platinum_policy_count + $ytd_kotak_sapphire_policy_count + $ytd_kotak_gold_policy_count + $ytd_kotak_silver_policy_count;
    $data['il_ytd_policy_amount'] = $ytd_il_platinum_policy_count + $ytd_il_sapphire_policy_count + $ytd_il_gold_policy_count + $ytd_il_silver_policy_count;
    $data['tata_ytd_policy_amount'] = $ytd_tata_platinum_policy_count + $ytd_tata_sapphire_policy_count + $ytd_tata_gold_policy_count + $ytd_tata_silver_policy_count;
    $data['bagi_ytd_policy_amount'] = $ytd_bagi_platinum_policy_count + $ytd_bagi_sapphire_policy_count + $ytd_bagi_gold_policy_count + $ytd_bagi_silver_policy_count;    
    $data['ytd_sold_tvs'] = ($layer_one_details['ytd_gold_policy_count'] * 57) + ($layer_one_details['ytd_platinum_policy_count'] * 57) + ($layer_one_details['ytd_sapphire_policy_count'] * 54) + ($layer_one_details['ytd_silver_policy_count'] * 53.5);    


    $data['party_td_payment'] = $data['kotak_td_deposit'] + $data['il_td_deposit'] + $data['tata_td_deposit'] + $data['bagi_td_deposit'] + $data['bharti_assist_td_deposit'];
    $data['party_mtd_payment'] = $data['kotak_mtd_deposit'] + $data['il_mtd_deposit'] + $data['tata_mtd_deposit'] + $data['bagi_mtd_deposit'] + $data['bharti_assist_mtd_deposit'];
    $data['party_ytd_payment'] = $data['kotak_ytd_deposit'] + $data['il_ytd_deposit'] + $data['tata_ytd_deposit'] + $data['bagi_ytd_deposit'] + $data['bharti_assist_ytd_deposit'] + $data['ytd_tvs'] + $data['ytd_gst'] + $data['ytd_tds'];        

    $data['td_receipt_deposit_amount'] = $deposit_status['td_deposit_amount'];
    $data['mtd_receipt_deposit_amount'] = $deposit_status['mtd_deposit_amount'];
    $data['ytd_receipt_deposit_amount'] = $deposit_status['ytd_deposit_amount'];

    $data['td_platinum_purchase_cost'] = $layer_one_details['td_platinum_policy_count'] * 210;
    $data['td_platinum_purchase_tds'] = $data['td_platinum_purchase_cost'] * 0.02;
    $data['td_platinum_purchase_gst'] = $layer_one_details['td_platinum_policy_count'] * 37.80;
    $data['td_platinum_purchase_total_amount'] = ($data['td_platinum_purchase_cost'] + $data['td_platinum_purchase_gst'] - $data['td_platinum_purchase_tds']);
    
    $data['td_sapphire_purchase_cost'] = $layer_one_details['td_sapphire_policy_count'] * 210;
    $data['td_sapphire_purchase_tds'] = $data['td_sapphire_purchase_cost'] * 0.02;
    $data['td_sapphire_purchase_gst'] = $layer_one_details['td_sapphire_policy_count'] * 37.80;
    $data['td_sapphire_purchase_total_amount'] = ($data['td_sapphire_purchase_cost'] + $data['td_sapphire_purchase_gst'] - $data['td_sapphire_purchase_tds']);
    
    $data['td_gold_purchase_cost'] = $layer_one_details['td_gold_policy_count'] * 140;
    $data['td_gold_purchase_tds'] = $data['td_gold_purchase_cost'] * 0.02;
    $data['td_gold_purchase_gst'] = $layer_one_details['td_gold_policy_count'] * 25.2;
    $data['td_gold_purchase_total_amount'] = ($data['td_gold_purchase_cost'] + $data['td_gold_purchase_gst'] - $data['td_gold_purchase_tds']);
    
    $data['td_silver_purchase_cost'] = $layer_one_details['td_silver_policy_count'] * 70;
    $data['td_silver_purchase_tds'] = $data['td_silver_purchase_cost'] * 0.02;
    $data['td_silver_purchase_gst'] = $layer_one_details['td_silver_policy_count'] * 12.6;
    $data['td_silver_purchase_total_amount'] = ($data['td_silver_purchase_cost'] + $data['td_silver_purchase_gst'] - $data['td_silver_purchase_tds']);
    /*********************TD DATA***************************/
//        echo '<pre>';
//    print_r($layer_one_details['td_gold_policy_count']);
//    print_r($data['td_gold_purchase_tds']);
//    print_r($data['td_gold_purchase_gst']);
//    echo '<pre>';
// die('hello moto');
    /*********************MTD DATA***************************/
    $data['mtd_platinum_purchase_cost'] = $layer_one_details['mtd_platinum_policy_count'] * 210;
    $data['mtd_platinum_purchase_tds'] = $data['mtd_platinum_purchase_cost'] * 0.02;
    $data['mtd_platinum_purchase_gst'] = $layer_one_details['mtd_platinum_policy_count'] * 37.80;
    $data['mtd_platinum_purchase_total_amount'] = ($data['mtd_platinum_purchase_cost'] + $data['mtd_platinum_purchase_gst'] - $data['mtd_platinum_purchase_tds']);
    
    $data['mtd_sapphire_purchase_cost'] = $layer_one_details['mtd_sapphire_policy_count'] * 210;
    $data['mtd_sapphire_purchase_tds'] = $data['mtd_sapphire_purchase_cost'] * 0.02;
    $data['mtd_sapphire_purchase_gst'] = $layer_one_details['mtd_sapphire_policy_count'] * 37.80;
    $data['mtd_sapphire_purchase_total_amount'] = ($data['mtd_sapphire_purchase_cost'] + $data['mtd_sapphire_purchase_gst'] - $data['mtd_sapphire_purchase_tds']);
    
    $data['mtd_gold_purchase_cost'] = $layer_one_details['mtd_gold_policy_count'] * 140;
    $data['mtd_gold_purchase_tds'] = $data['mtd_gold_purchase_cost'] * 0.02;
    $data['mtd_gold_purchase_gst'] = $layer_one_details['mtd_gold_policy_count'] * 25.2;
    $data['mtd_gold_purchase_total_amount'] = ($data['mtd_gold_purchase_cost'] + $data['mtd_gold_purchase_gst'] - $data['mtd_gold_purchase_tds']);

    $data['mtd_silver_purchase_cost'] = $layer_one_details['mtd_silver_policy_count'] * 70;
    $data['mtd_silver_purchase_tds'] = $data['mtd_silver_purchase_cost'] * 0.02;
    $data['mtd_silver_purchase_gst'] = $layer_one_details['mtd_silver_policy_count'] * 12.6;
    $data['mtd_silver_purchase_total_amount'] = ($data['mtd_silver_purchase_cost'] + $data['mtd_silver_purchase_gst'] - $data['mtd_silver_purchase_tds']);
    /*********************MTD DATA***************************/
    
    /*********************YTD DATA***************************/
    $data['ytd_platinum_purchase_cost'] = $layer_one_details['ytd_platinum_policy_count'] * 210;
    $data['ytd_platinum_purchase_tds'] = $data['ytd_platinum_purchase_cost'] * 0.02;
    $data['ytd_platinum_purchase_gst'] = $layer_one_details['ytd_platinum_policy_count'] * 37.80;
    $data['ytd_platinum_purchase_total_amount'] = ($data['ytd_platinum_purchase_cost'] + $data['ytd_platinum_purchase_gst'] - $data['ytd_platinum_purchase_tds']);
    
    $data['ytd_sapphire_purchase_cost'] = $layer_one_details['ytd_sapphire_policy_count'] * 210;
    $data['ytd_sapphire_purchase_tds'] = $data['ytd_sapphire_purchase_cost'] * 0.02;
    $data['ytd_sapphire_purchase_gst'] = $layer_one_details['ytd_sapphire_policy_count'] * 37.80;
    $data['ytd_sapphire_purchase_total_amount'] = ($data['ytd_sapphire_purchase_cost'] + $data['ytd_sapphire_purchase_gst'] - $data['ytd_sapphire_purchase_tds']);
    
    $data['ytd_gold_purchase_cost'] = $layer_one_details['ytd_gold_policy_count'] * 140;
    $data['ytd_gold_purchase_tds'] = $data['ytd_gold_purchase_cost'] * 0.02;
    $data['ytd_gold_purchase_gst'] = $layer_one_details['ytd_gold_policy_count'] * 25.2;
    $data['ytd_gold_purchase_total_amount'] = ($data['ytd_gold_purchase_cost'] + $data['ytd_gold_purchase_gst'] - $data['ytd_gold_purchase_tds']);
    
    $data['ytd_silver_purchase_cost'] = $layer_one_details['ytd_silver_policy_count'] * 70;
    $data['ytd_silver_purchase_tds'] = $data['ytd_silver_purchase_cost'] * 0.02;
    $data['ytd_silver_purchase_gst'] = $layer_one_details['ytd_silver_policy_count'] * 12.6;
    $data['ytd_silver_purchase_total_amount'] = ($data['ytd_silver_purchase_cost'] + $data['ytd_silver_purchase_gst'] - $data['ytd_silver_purchase_tds']);

    /*$data['total_liabilities'] = $data['ytd_sapphire_policy_tds'] +  $data['ytd_silver_policy_tds'] +  $data['ytd_platinum_policy_tds'] +  $data['ytd_gold_policy_tds'];*/
    /*********************YTD DATA***************************/
    
    //END Purchase Cost PA
    
    
    // Purchase Cost RSA
     /*****************td data*******************/
    $data['rsa_td_platinum_purchase_cost'] = $layer_one_details['td_platinum_policy_count'] * 16;
    $data['rsa_td_platinum_purchase_tds'] = $data['rsa_td_platinum_purchase_cost'] * 0.02;
    $data['rsa_td_platinum_purchase_gst'] = $layer_one_details['td_platinum_policy_count'] * 2.88;
    $data['rsa_td_platinum_purchase_total_amount'] = ($data['rsa_td_platinum_purchase_cost'] + $data['rsa_td_platinum_purchase_gst'] - $data['rsa_td_platinum_purchase_tds']);
    
    $data['rsa_td_sapphire_purchase_cost'] = $layer_one_details['td_sapphire_policy_count'] * 32;
    $data['rsa_td_sapphire_purchase_tds'] = $data['rsa_td_sapphire_purchase_cost'] * 0.02;
    $data['rsa_td_sapphire_purchase_gst'] = $layer_one_details['td_sapphire_policy_count'] * 5.76;
    $data['rsa_td_sapphire_purchase_total_amount'] = ($data['rsa_td_sapphire_purchase_cost'] + $data['rsa_td_sapphire_purchase_gst'] - $data['rsa_td_sapphire_purchase_tds']);
    
    $data['rsa_td_gold_purchase_cost'] = $layer_one_details['td_gold_policy_count'] * 16;
    $data['rsa_td_gold_purchase_tds'] = $data['rsa_td_gold_purchase_cost'] * 0.02;
    $data['rsa_td_gold_purchase_gst'] = $layer_one_details['td_gold_policy_count'] * 2.88;
    $data['rsa_td_gold_purchase_total_amount'] = ($data['rsa_td_gold_purchase_cost'] + $data['rsa_td_gold_purchase_gst'] - $data['rsa_td_gold_purchase_tds']);
    
    $data['rsa_td_silver_purchase_cost'] = $layer_one_details['td_silver_policy_count'] * 16;
    $data['rsa_td_silver_purchase_tds'] = $data['rsa_td_silver_purchase_cost'] * 0.02;
    $data['rsa_td_silver_purchase_gst'] = $layer_one_details['td_silver_policy_count'] * 2.88;
    $data['rsa_td_silver_purchase_total_amount'] = ($data['rsa_td_silver_purchase_cost'] + $data['rsa_td_silver_purchase_gst'] - $data['rsa_td_silver_purchase_tds']);
    /*********************TD DATA***************************/
    
    /*********************MTD DATA***************************/
    $data['rsa_mtd_platinum_purchase_cost'] = $layer_one_details['mtd_platinum_policy_count'] * 16;
    $data['rsa_mtd_platinum_purchase_tds'] = $data['rsa_mtd_platinum_purchase_cost'] * 0.02;
    $data['rsa_mtd_platinum_purchase_gst'] = $layer_one_details['mtd_platinum_policy_count'] * 2.88;
    $data['rsa_mtd_platinum_purchase_total_amount'] = ($data['rsa_mtd_platinum_purchase_cost'] + $data['rsa_mtd_platinum_purchase_gst'] - $data['rsa_mtd_platinum_purchase_tds']);
    
    $data['rsa_mtd_sapphire_purchase_cost'] = $layer_one_details['mtd_sapphire_policy_count'] * 32;
    $data['rsa_mtd_sapphire_purchase_tds'] = $data['rsa_mtd_sapphire_purchase_cost'] * 0.02;
    $data['rsa_mtd_sapphire_purchase_gst'] = $layer_one_details['mtd_sapphire_policy_count'] * 5.76;
    $data['rsa_mtd_sapphire_purchase_total_amount'] = ($data['rsa_mtd_sapphire_purchase_cost'] + $data['rsa_mtd_sapphire_purchase_gst'] - $data['rsa_mtd_sapphire_purchase_tds']);
    
    $data['rsa_mtd_gold_purchase_cost'] = $layer_one_details['mtd_gold_policy_count'] * 16;
    $data['rsa_mtd_gold_purchase_tds'] = $data['rsa_mtd_gold_purchase_cost'] * 0.02;
    $data['rsa_mtd_gold_purchase_gst'] = $layer_one_details['mtd_gold_policy_count'] * 2.88;
    $data['rsa_mtd_gold_purchase_total_amount'] = ($data['rsa_mtd_gold_purchase_cost'] + $data['rsa_mtd_gold_purchase_gst'] - $data['rsa_mtd_gold_purchase_tds']);
    
    $data['rsa_mtd_silver_purchase_cost'] = $layer_one_details['mtd_silver_policy_count'] * 16;
    $data['rsa_mtd_silver_purchase_tds'] = $data['rsa_mtd_silver_purchase_cost'] * 0.02;
    $data['rsa_mtd_silver_purchase_gst'] = $layer_one_details['mtd_silver_policy_count'] * 2.88;
    $data['rsa_mtd_silver_purchase_total_amount'] = ($data['rsa_mtd_silver_purchase_cost'] + $data['rsa_mtd_silver_purchase_gst'] - $data['rsa_mtd_silver_purchase_tds']);
    /*********************MTD DATA***************************/
    
    /*********************YTD DATA***************************/
    $data['rsa_ytd_platinum_purchase_cost'] = $layer_one_details['ytd_platinum_policy_count'] * 16;
    $data['rsa_ytd_platinum_purchase_tds'] = $data['rsa_ytd_platinum_purchase_cost'] * 0.02;
    $data['rsa_ytd_platinum_purchase_gst'] = $layer_one_details['ytd_platinum_policy_count'] * 2.88;
    $data['rsa_ytd_platinum_purchase_total_amount'] = ($data['rsa_ytd_platinum_purchase_cost'] + $data['rsa_ytd_platinum_purchase_gst'] - $data['rsa_ytd_platinum_purchase_tds']);
    
    $data['rsa_ytd_sapphire_purchase_cost'] = $layer_one_details['ytd_sapphire_policy_count'] * 32;
    $data['rsa_ytd_sapphire_purchase_tds'] = $data['rsa_ytd_sapphire_purchase_cost'] * 0.02;
    $data['rsa_ytd_sapphire_purchase_gst'] = $layer_one_details['ytd_sapphire_policy_count'] * 5.76;
    $data['rsa_ytd_sapphire_purchase_total_amount'] = ($data['rsa_ytd_sapphire_purchase_cost'] + $data['rsa_ytd_sapphire_purchase_gst'] - $data['rsa_ytd_sapphire_purchase_tds']);
    
    $data['rsa_ytd_gold_purchase_cost'] = $layer_one_details['ytd_gold_policy_count'] * 16;
    $data['rsa_ytd_gold_purchase_tds'] = $data['rsa_ytd_gold_purchase_cost'] * 0.02;
    $data['rsa_ytd_gold_purchase_gst'] = $layer_one_details['ytd_gold_policy_count'] * 2.88;
    $data['rsa_ytd_gold_purchase_total_amount'] = ($data['rsa_ytd_gold_purchase_cost'] + $data['rsa_ytd_gold_purchase_gst'] - $data['rsa_ytd_gold_purchase_tds']);
    
    $data['rsa_ytd_silver_purchase_cost'] = $layer_one_details['ytd_silver_policy_count'] * 16;
    $data['rsa_ytd_silver_purchase_tds'] = $data['rsa_ytd_silver_purchase_cost'] * 0.02;
    $data['rsa_ytd_silver_purchase_gst'] = $layer_one_details['ytd_silver_policy_count'] * 2.88;
    $data['rsa_ytd_silver_purchase_total_amount'] = ($data['rsa_ytd_silver_purchase_cost'] + $data['rsa_ytd_silver_purchase_gst'] - $data['rsa_ytd_silver_purchase_tds']);
    /*********************YTD DATA***************************/
    
    // END Purchase Cost RSA
    
    // Purchase Cost Dealer Commission
     /*****************td data*******************/
    $data['dc_td_platinum_purchase_cost'] = $layer_one_details['td_platinum_policy_count'] * 75;
    $data['dc_td_platinum_purchase_tds'] = $layer_one_details['td_platinum_policy_count'] * 3.75;
    $data['dc_td_platinum_purchase_gst'] = $layer_one_details['td_platinum_policy_count'] * 13.50;
    $data['dc_td_platinum_purchase_total_amount'] = ($data['dc_td_platinum_purchase_cost'] + $data['dc_td_platinum_purchase_gst'] - $data['dc_td_platinum_purchase_tds']);
    
    $data['dc_td_sapphire_purchase_cost'] = $layer_one_details['td_sapphire_policy_count'] * 80;
    $data['dc_td_sapphire_purchase_tds'] = $layer_one_details['td_sapphire_policy_count'] * 4;
    $data['dc_td_sapphire_purchase_gst'] = $layer_one_details['td_sapphire_policy_count'] * 14.4;
    $data['dc_td_sapphire_purchase_total_amount'] = ($data['dc_td_sapphire_purchase_cost'] + $data['dc_td_sapphire_purchase_gst'] - $data['dc_td_sapphire_purchase_tds']);
    
    $data['dc_td_gold_purchase_cost'] = $layer_one_details['td_gold_policy_count'] * 60;
    $data['dc_td_gold_purchase_tds'] = $layer_one_details['td_gold_policy_count'] * 3;
    $data['dc_td_gold_purchase_gst'] = $layer_one_details['td_gold_policy_count'] * 10.8;
    $data['dc_td_gold_purchase_total_amount'] = ($data['dc_td_gold_purchase_cost'] + $data['dc_td_gold_purchase_gst'] - $data['dc_td_gold_purchase_tds']);
    
    $data['dc_td_silver_purchase_cost'] = $layer_one_details['td_silver_policy_count'] * 50;
    $data['dc_td_silver_purchase_tds'] = $layer_one_details['td_silver_policy_count'] * 2.5;
    $data['dc_td_silver_purchase_gst'] = $layer_one_details['td_silver_policy_count'] * 9;
    $data['dc_td_silver_purchase_total_amount'] = ($data['dc_td_silver_purchase_cost'] + $data['dc_td_silver_purchase_gst'] - $data['dc_td_silver_purchase_tds']);
    /*********************TD DATA***************************/
    
    /*********************MTD DATA***************************/
    $data['dc_mtd_platinum_purchase_cost'] = $layer_one_details['mtd_platinum_policy_count'] * 75;
    $data['dc_mtd_platinum_purchase_tds'] = $layer_one_details['mtd_platinum_policy_count'] * 3.75;
    $data['dc_mtd_platinum_purchase_gst'] = $layer_one_details['mtd_platinum_policy_count'] * 13.50;
    $data['dc_mtd_platinum_purchase_total_amount'] = ($data['dc_mtd_platinum_purchase_cost'] + $data['dc_mtd_platinum_purchase_gst'] - $data['dc_mtd_platinum_purchase_tds']);
    
    $data['dc_mtd_sapphire_purchase_cost'] = $layer_one_details['mtd_sapphire_policy_count'] * 80;
    $data['dc_mtd_sapphire_purchase_tds'] = $layer_one_details['mtd_sapphire_policy_count'] * 4;
    $data['dc_mtd_sapphire_purchase_gst'] = $layer_one_details['mtd_sapphire_policy_count'] * 14.4;
    $data['dc_mtd_sapphire_purchase_total_amount'] = ($data['dc_mtd_sapphire_purchase_cost'] + $data['dc_mtd_sapphire_purchase_gst'] - $data['dc_mtd_sapphire_purchase_tds']);
    
    $data['dc_mtd_gold_purchase_cost'] = $layer_one_details['mtd_gold_policy_count'] * 60;
    $data['dc_mtd_gold_purchase_tds'] = $layer_one_details['mtd_gold_policy_count'] * 3;
    $data['dc_mtd_gold_purchase_gst'] = $layer_one_details['mtd_gold_policy_count'] * 10.8;
    $data['dc_mtd_gold_purchase_total_amount'] = ($data['dc_mtd_gold_purchase_cost'] + $data['dc_mtd_gold_purchase_gst'] - $data['dc_mtd_gold_purchase_tds']);
    
    $data['dc_mtd_silver_purchase_cost'] = $layer_one_details['mtd_silver_policy_count'] * 50;
    $data['dc_mtd_silver_purchase_tds'] = $layer_one_details['mtd_silver_policy_count'] * 2.5;
    $data['dc_mtd_silver_purchase_gst'] = $layer_one_details['mtd_silver_policy_count'] * 9;
    $data['dc_mtd_silver_purchase_total_amount'] = ($data['dc_mtd_silver_purchase_cost'] + $data['dc_mtd_silver_purchase_gst'] - $data['dc_mtd_silver_purchase_tds']);
    /*********************MTD DATA***************************/
    
    /*********************YTD DATA***************************/
    $data['dc_ytd_platinum_purchase_cost'] = $layer_one_details['ytd_platinum_policy_count'] * 75;
    $data['dc_ytd_platinum_purchase_tds'] = $layer_one_details['ytd_platinum_policy_count'] * 3.75;
    $data['dc_ytd_platinum_purchase_gst'] = $layer_one_details['ytd_platinum_policy_count'] * 13.50;
    $data['dc_ytd_platinum_purchase_total_amount'] = ($data['dc_ytd_platinum_purchase_cost'] + $data['dc_ytd_platinum_purchase_gst'] - $data['dc_ytd_platinum_purchase_tds']);
    
    $data['dc_ytd_sapphire_purchase_cost'] = $layer_one_details['ytd_sapphire_policy_count'] * 80;
    $data['dc_ytd_sapphire_purchase_tds'] = $layer_one_details['ytd_sapphire_policy_count'] * 4;
    $data['dc_ytd_sapphire_purchase_gst'] = $layer_one_details['ytd_sapphire_policy_count'] * 14.4;
    $data['dc_ytd_sapphire_purchase_total_amount'] = ($data['dc_ytd_sapphire_purchase_cost'] + $data['dc_ytd_sapphire_purchase_gst'] - $data['dc_ytd_sapphire_purchase_tds']);
    
    $data['dc_ytd_gold_purchase_cost'] = $layer_one_details['ytd_gold_policy_count'] * 60;
    $data['dc_ytd_gold_purchase_tds'] = $layer_one_details['ytd_gold_policy_count'] * 3;
    $data['dc_ytd_gold_purchase_gst'] = $layer_one_details['ytd_gold_policy_count'] * 10.8;
    $data['dc_ytd_gold_purchase_total_amount'] = ($data['dc_ytd_gold_purchase_cost'] + $data['dc_ytd_gold_purchase_gst'] - $data['dc_ytd_gold_purchase_tds']);
    
    $data['dc_ytd_silver_purchase_cost'] = $layer_one_details['ytd_silver_policy_count'] * 50;
    $data['dc_ytd_silver_purchase_tds'] = $layer_one_details['ytd_silver_policy_count'] * 2.5;
    $data['dc_ytd_silver_purchase_gst'] = $layer_one_details['ytd_silver_policy_count'] * 9;
    $data['dc_ytd_silver_purchase_total_amount'] = ($data['dc_ytd_silver_purchase_cost'] + $data['dc_ytd_silver_purchase_gst'] - $data['dc_ytd_silver_purchase_tds']);
    /*********************YTD DATA***************************/
    
    // END Purchase Cost Dealer Commission
    
    // Purchase Cost TVS Commission
     /*****************td data*******************/
    $data['tvs_td_platinum_purchase_cost'] = $layer_one_details['td_platinum_policy_count'] * 57;
    $data['tvs_td_platinum_purchase_tds'] = $layer_one_details['td_platinum_policy_count'] * 5.7;
    $data['tvs_td_platinum_purchase_gst'] = $layer_one_details['td_platinum_policy_count'] * 8.73;
    $data['tvs_td_platinum_purchase_total_amount'] = ($data['tvs_td_platinum_purchase_cost'] + $data['tvs_td_platinum_purchase_gst'] - $data['tvs_td_platinum_purchase_tds']);
    
    $data['tvs_td_sapphire_purchase_cost'] = $layer_one_details['td_sapphire_policy_count'] * 54;
    $data['tvs_td_sapphire_purchase_tds'] = $layer_one_details['td_sapphire_policy_count'] * 5.4;
    $data['tvs_td_sapphire_purchase_gst'] = $layer_one_details['td_sapphire_policy_count'] * 9.72;
    $data['tvs_td_sapphire_purchase_total_amount'] = ($data['tvs_td_sapphire_purchase_cost'] + $data['tvs_td_sapphire_purchase_gst'] - $data['tvs_td_sapphire_purchase_tds']);
    
    $data['tvs_td_gold_purchase_cost'] = $layer_one_details['td_gold_policy_count'] * 57;
    $data['tvs_td_gold_purchase_tds'] = $layer_one_details['td_gold_policy_count'] * 5.7;
    $data['tvs_td_gold_purchase_gst'] = $layer_one_details['td_gold_policy_count'] * 10.26;
    $data['tvs_td_gold_purchase_total_amount'] = ($data['tvs_td_gold_purchase_cost'] + $data['tvs_td_gold_purchase_gst'] - $data['tvs_td_gold_purchase_tds']);
    
    $data['tvs_td_silver_purchase_cost'] = $layer_one_details['td_silver_policy_count'] * 53.5;
    $data['tvs_td_silver_purchase_tds'] = $layer_one_details['td_silver_policy_count'] * 5.35;
    $data['tvs_td_silver_purchase_gst'] = $layer_one_details['td_silver_policy_count'] * 9.63;
    $data['tvs_td_silver_purchase_total_amount'] = ($data['tvs_td_silver_purchase_cost'] + $data['tvs_td_silver_purchase_gst'] - $data['tvs_td_silver_purchase_tds']);
    /*********************TD DATA***************************/
    
    /*********************MTD DATA***************************/
    $data['tvs_mtd_platinum_purchase_cost'] = $layer_one_details['mtd_platinum_policy_count'] * 57;
    $data['tvs_mtd_platinum_purchase_tds'] = $layer_one_details['mtd_platinum_policy_count'] * 5.7;
    $data['tvs_mtd_platinum_purchase_gst'] = $layer_one_details['mtd_platinum_policy_count'] * 8.73;
    $data['tvs_mtd_platinum_purchase_total_amount'] = ($data['tvs_mtd_platinum_purchase_cost'] + $data['tvs_mtd_platinum_purchase_gst'] - $data['tvs_mtd_platinum_purchase_tds']);
    
    $data['tvs_mtd_sapphire_purchase_cost'] = $layer_one_details['mtd_sapphire_policy_count'] * 54;
    $data['tvs_mtd_sapphire_purchase_tds'] = $layer_one_details['mtd_sapphire_policy_count'] * 5.4;
    $data['tvs_mtd_sapphire_purchase_gst'] = $layer_one_details['mtd_sapphire_policy_count'] * 9.72;
    $data['tvs_mtd_sapphire_purchase_total_amount'] = ($data['tvs_mtd_sapphire_purchase_cost'] + $data['tvs_mtd_sapphire_purchase_gst'] - $data['tvs_mtd_sapphire_purchase_tds']);
    
    $data['tvs_mtd_gold_purchase_cost'] = $layer_one_details['mtd_gold_policy_count'] * 57;
    $data['tvs_mtd_gold_purchase_tds'] = $layer_one_details['mtd_gold_policy_count'] * 5.7;
    $data['tvs_mtd_gold_purchase_gst'] = $layer_one_details['mtd_gold_policy_count'] * 10.26;
    $data['tvs_mtd_gold_purchase_total_amount'] = ($data['tvs_mtd_gold_purchase_cost'] + $data['tvs_mtd_gold_purchase_gst'] - $data['tvs_mtd_gold_purchase_tds']);
    
    $data['tvs_mtd_silver_purchase_cost'] = $layer_one_details['mtd_silver_policy_count'] * 53.5;
    $data['tvs_mtd_silver_purchase_tds'] = $layer_one_details['mtd_silver_policy_count'] * 5.35;
    $data['tvs_mtd_silver_purchase_gst'] = $layer_one_details['mtd_silver_policy_count'] * 9.63;
    $data['tvs_mtd_silver_purchase_total_amount'] = ($data['tvs_mtd_silver_purchase_cost'] + $data['tvs_mtd_silver_purchase_gst'] - $data['tvs_mtd_silver_purchase_tds']);
    /*********************MTD DATA***************************/
    
    /*********************YTD DATA***************************/
    $data['tvs_ytd_platinum_purchase_cost'] = $layer_one_details['ytd_platinum_policy_count'] * 57;
    $data['tvs_ytd_platinum_purchase_tds'] = $data['tvs_ytd_platinum_purchase_cost'] * 0.10;
    $data['tvs_ytd_platinum_purchase_gst'] = $layer_one_details['ytd_platinum_policy_count'] * 8.73;
    $data['tvs_ytd_platinum_purchase_total_amount'] = ($data['tvs_ytd_platinum_purchase_cost'] + $data['tvs_ytd_platinum_purchase_gst'] - $data['tvs_ytd_platinum_purchase_tds']);
    
    $data['tvs_ytd_sapphire_purchase_cost'] = $layer_one_details['ytd_sapphire_policy_count'] * 54;
    $data['tvs_ytd_sapphire_purchase_tds'] = $layer_one_details['ytd_sapphire_policy_count'] * 5.4;
    $data['tvs_ytd_sapphire_purchase_gst'] = $layer_one_details['ytd_sapphire_policy_count'] * 9.72;
    $data['tvs_ytd_sapphire_purchase_total_amount'] = ($data['tvs_ytd_sapphire_purchase_cost'] + $data['tvs_ytd_sapphire_purchase_gst'] - $data['tvs_ytd_sapphire_purchase_tds']);
    
    $data['tvs_ytd_gold_purchase_cost'] = $layer_one_details['ytd_gold_policy_count'] * 57;
    $data['tvs_ytd_gold_purchase_tds'] = $data['tvs_ytd_gold_purchase_cost'] * 0.10;
    $data['tvs_ytd_gold_purchase_gst'] = $layer_one_details['ytd_gold_policy_count'] * 10.26;
    $data['tvs_ytd_gold_purchase_total_amount'] = ($data['tvs_ytd_gold_purchase_cost'] + $data['tvs_ytd_gold_purchase_gst'] - $data['tvs_ytd_gold_purchase_tds']);
    
    $data['tvs_ytd_silver_purchase_cost'] = $layer_one_details['ytd_silver_policy_count'] * 53.5;
    $data['tvs_ytd_silver_purchase_tds'] = $layer_one_details['ytd_silver_policy_count'] * 5.35;
    $data['tvs_ytd_silver_purchase_gst'] = $layer_one_details['ytd_silver_policy_count'] * 9.63;
    $data['tvs_ytd_silver_purchase_total_amount'] = ($data['tvs_ytd_silver_purchase_cost'] + $data['tvs_ytd_silver_purchase_gst'] - $data['tvs_ytd_silver_purchase_tds']);


    $data['td_platinum_policy_output_gst'] = $layer_one_details['td_platinum_policy_count'] * 67.27;
    $data['mtd_platinum_policy_output_gst'] = $layer_one_details['mtd_platinum_policy_count'] * 67.27;
    $data['ytd_platinum_policy_output_gst'] = $layer_one_details['ytd_platinum_policy_count'] * 67.27;
    
    $data['td_sapphire_policy_output_gst'] = $layer_one_details['td_sapphire_policy_count'] * 71.85;
    $data['mtd_sapphire_policy_output_gst'] = $layer_one_details['mtd_sapphire_policy_count'] * 71.85;
    $data['ytd_sapphire_policy_output_gst'] = $layer_one_details['ytd_sapphire_policy_count'] * 71.85;

    $data['td_gold_policy_output_gst'] = $layer_one_details['td_gold_policy_count'] * 53.39;
    $data['mtd_gold_policy_output_gst'] = $layer_one_details['mtd_gold_policy_count'] * 53.39;
    $data['ytd_gold_policy_output_gst'] = $layer_one_details['ytd_gold_policy_count'] * 53.39;
    
    $data['td_silver_policy_output_gst'] = $layer_one_details['td_silver_policy_count'] * 38.29;
    $data['mtd_silver_policy_output_gst'] = $layer_one_details['mtd_silver_policy_count'] * 38.29;
    $data['ytd_silver_policy_output_gst'] = $layer_one_details['ytd_silver_policy_count'] * 38.29;
    
    //END OUTPUT GST
    
    //INPUT GST
    $data['td_platinum_policy_input_gst'] = ($data['td_platinum_purchase_gst']+$data['rsa_td_platinum_purchase_gst']+$data['dc_td_platinum_purchase_gst']+$data['tvs_td_platinum_purchase_gst']);
    $data['mtd_platinum_policy_input_gst'] = ($data['mtd_platinum_purchase_gst']+$data['rsa_mtd_platinum_purchase_gst']+$data['dc_mtd_platinum_purchase_gst']+$data['tvs_mtd_platinum_purchase_gst']);
    $data['ytd_platinum_policy_input_gst'] = ($data['ytd_platinum_purchase_gst']+$data['rsa_ytd_platinum_purchase_gst']+$data['dc_ytd_platinum_purchase_gst']+$data['tvs_ytd_platinum_purchase_gst']);
    
    $data['td_sapphire_policy_input_gst'] = ($data['td_sapphire_purchase_gst']+$data['rsa_td_sapphire_purchase_gst']+$data['dc_td_sapphire_purchase_gst']+$data['tvs_td_sapphire_purchase_gst']);
    $data['mtd_sapphire_policy_input_gst'] = ($data['mtd_sapphire_purchase_gst']+$data['rsa_mtd_sapphire_purchase_gst']+$data['dc_mtd_sapphire_purchase_gst']+$data['tvs_mtd_sapphire_purchase_gst']);
    $data['ytd_sapphire_policy_input_gst'] = ($data['ytd_sapphire_purchase_gst']+$data['rsa_ytd_sapphire_purchase_gst']+$data['dc_ytd_sapphire_purchase_gst']+$data['tvs_mtd_sapphire_purchase_gst']);

    $data['td_gold_policy_input_gst'] = ($data['td_gold_purchase_gst']+$data['rsa_td_gold_purchase_gst']+$data['dc_td_gold_purchase_gst']+$data['tvs_td_gold_purchase_gst']);
    $data['mtd_gold_policy_input_gst'] = ($data['mtd_gold_purchase_gst']+$data['rsa_mtd_gold_purchase_gst']+$data['dc_mtd_gold_purchase_gst']+$data['tvs_mtd_gold_purchase_gst']);
    $data['ytd_gold_policy_input_gst'] = ($data['ytd_gold_purchase_gst']+$data['rsa_ytd_gold_purchase_gst']+$data['dc_ytd_gold_purchase_gst']+$data['tvs_ytd_gold_purchase_gst']);
    
    $data['td_silver_policy_input_gst'] = ($data['td_silver_purchase_gst']+$data['rsa_td_silver_purchase_gst']+$data['dc_td_silver_purchase_gst']+$data['tvs_td_silver_purchase_gst']);
    $data['mtd_silver_policy_input_gst'] = ($data['mtd_silver_purchase_gst']+$data['rsa_mtd_silver_purchase_gst']+$data['dc_mtd_silver_purchase_gst']+$data['tvs_mtd_silver_purchase_gst']);
    $data['ytd_silver_policy_input_gst'] = ($data['ytd_silver_purchase_gst']+$data['rsa_ytd_silver_purchase_gst']+$data['dc_ytd_silver_purchase_gst']+$data['tvs_ytd_silver_purchase_gst']);
    
    //END INPUT GST
    
    //NET GST
   $data['td_platinum_policy_net_gst'] = ($data['td_platinum_policy_output_gst'] - $data['td_platinum_policy_input_gst']);
   $data['mtd_platinum_policy_net_gst'] = ($data['mtd_platinum_policy_output_gst'] - $data['mtd_platinum_policy_input_gst']);
   $data['ytd_platinum_policy_net_gst'] = ($data['ytd_platinum_policy_output_gst'] - $data['ytd_platinum_policy_input_gst']);
    
   
   $data['td_sapphire_policy_net_gst'] = ($data['td_sapphire_policy_output_gst'] - $data['td_sapphire_policy_input_gst']);
   $data['mtd_sapphire_policy_net_gst'] = ($data['mtd_sapphire_policy_output_gst'] - $data['mtd_sapphire_policy_input_gst']);
   $data['ytd_sapphire_policy_net_gst'] = ($data['ytd_sapphire_policy_output_gst'] - $data['ytd_sapphire_policy_input_gst']);
   
   $data['td_gold_policy_net_gst'] = ($data['td_gold_policy_output_gst'] - $data['td_gold_policy_input_gst']);
   $data['mtd_gold_policy_net_gst'] = ($data['mtd_gold_policy_output_gst'] - $data['mtd_gold_policy_input_gst']);
   $data['ytd_gold_policy_net_gst'] = ($data['ytd_gold_policy_output_gst'] - $data['ytd_gold_policy_input_gst']);
   
   $data['td_silver_policy_net_gst'] = ($data['td_silver_policy_output_gst'] - $data['td_silver_policy_input_gst']);
   $data['mtd_silver_policy_net_gst'] = ($data['mtd_silver_policy_output_gst'] - $data['mtd_silver_policy_input_gst']);
   $data['ytd_silver_policy_net_gst'] = ($data['ytd_silver_policy_output_gst'] - $data['ytd_silver_policy_input_gst']);
    //END NET GST
   
   
    /*****************td data*******************/
    $data['td_platinum_sell_value'] = $layer_one_details['td_platinum_policy_count'] * 373.73;
    $data['td_platinum_gst'] = $layer_one_details['td_platinum_policy_count'] * 67.27;
    $data['td_platinum_total_amount'] = $data['td_platinum_sell_value'] + $data['td_platinum_gst'];
    
    $data['td_sapphire_sell_value'] = $layer_one_details['td_sapphire_policy_count'] * 399.15;
    $data['td_sapphire_gst'] = $layer_one_details['td_sapphire_policy_count'] * 71.85;
    $data['td_sapphire_total_amount'] = $data['td_sapphire_sell_value'] + $data['td_sapphire_gst'];
    
    $data['td_gold_sell_value'] = $layer_one_details['td_gold_policy_count'] * 296.61;
    $data['td_gold_gst'] = $layer_one_details['td_gold_policy_count'] * 53.39;
    $data['td_gold_total_amount'] = $data['td_gold_sell_value'] + $data['td_gold_gst'];
    
    $data['td_silver_sell_value'] = $layer_one_details['td_silver_policy_count'] * 212.71;
    $data['td_silver_gst'] = $layer_one_details['td_silver_policy_count'] * 38.29;
    $data['td_silver_total_amount'] = $data['td_silver_sell_value'] + $data['td_silver_gst'];
    $data['td_receipt_amount'] = $data['td_silver_total_amount'] + $data['td_sapphire_total_amount'] + $data['td_platinum_total_amount'] + $data['td_gold_total_amount'];

    $data['td_icpl_margin'] = ($layer_one_details['td_gold_policy_count'] * 23.61) + ($layer_one_details['td_platinum_policy_count'] * 15.73) + ($layer_one_details['td_sapphire_policy_count'] * 77.15) + ($layer_one_details['td_silver_policy_count'] * 23.21);

    /*********************TD DATA***************************/
    
    /*********************MTD DATA***************************/
    $data['mtd_platinum_sell_value'] = $layer_one_details['mtd_platinum_policy_count'] * 373.73;
    $data['mtd_platinum_gst'] = $layer_one_details['mtd_platinum_policy_count'] * 67.27;
    $data['mtd_platinum_total_amount'] = $data['mtd_platinum_sell_value'] + $data['mtd_platinum_gst'];

    
    $data['mtd_sapphire_sell_value'] = $layer_one_details['mtd_sapphire_policy_count'] * 399.15;
    $data['mtd_sapphire_gst'] = $layer_one_details['mtd_sapphire_policy_count'] * 71.85;
    $data['mtd_sapphire_total_amount'] = $data['mtd_sapphire_sell_value'] + $data['mtd_sapphire_gst'];
    
    $data['mtd_gold_sell_value'] = $layer_one_details['mtd_gold_policy_count'] * 296.61;
    $data['mtd_gold_gst'] = $layer_one_details['mtd_gold_policy_count'] * 53.39;
    $data['mtd_gold_total_amount'] = $data['mtd_gold_sell_value'] + $data['mtd_gold_gst'];
    
    $data['mtd_silver_sell_value'] = $layer_one_details['mtd_silver_policy_count'] * 212.71;
    $data['mtd_silver_gst'] = $layer_one_details['mtd_silver_policy_count'] * 38.29;
    $data['mtd_silver_total_amount'] = $data['mtd_silver_sell_value'] + $data['mtd_silver_gst'];
    $data['mtd_receipt_amount'] = ($data['mtd_platinum_total_amount'] + $data['mtd_sapphire_total_amount'] + $data['mtd_gold_total_amount'] + $data['mtd_silver_total_amount']);     
    $data['mtd_icpl_margin'] = ($layer_one_details['mtd_gold_policy_count'] * 23.61) + ($layer_one_details['mtd_platinum_policy_count'] * 15.73) + ($layer_one_details['mtd_sapphire_policy_count'] * 77.15) + ($layer_one_details['mtd_silver_policy_count'] * 23.21);
    /*********************MTD DATA***************************/
    
    /*********************YTD DATA***************************/
    $data['ytd_platinum_sell_value'] = $layer_one_details['ytd_platinum_policy_count'] * 373.73;
    $data['ytd_platinum_gst'] = $layer_one_details['ytd_platinum_policy_count'] * 67.27;
    $data['ytd_platinum_total_amount'] = $data['ytd_platinum_sell_value'] + $data['ytd_platinum_gst'];
    
    $data['ytd_sapphire_sell_value'] = $layer_one_details['ytd_sapphire_policy_count'] * 399.15;
    $data['ytd_sapphire_gst'] = $layer_one_details['ytd_sapphire_policy_count'] * 71.85;
    $data['ytd_sapphire_total_amount'] = $data['ytd_sapphire_sell_value'] + $data['ytd_sapphire_gst'];
    
    $data['ytd_gold_sell_value'] = $layer_one_details['ytd_gold_policy_count'] * 296.61;
    $data['ytd_gold_gst'] = $layer_one_details['ytd_gold_policy_count'] * 53.39;
    $data['ytd_gold_total_amount'] = $data['ytd_gold_sell_value'] + $data['ytd_gold_gst'];
    
    $data['ytd_silver_sell_value'] = $layer_one_details['ytd_silver_policy_count'] * 212.71;
    $data['ytd_silver_gst'] = $layer_one_details['ytd_silver_policy_count'] * 38.29;
    $data['ytd_silver_total_amount'] = $data['ytd_silver_sell_value'] + $data['ytd_silver_gst'];
    $data['ytd_receipt_amount'] = ($data['ytd_platinum_total_amount'] + $data['ytd_sapphire_total_amount'] + $data['ytd_gold_total_amount'] + $data['ytd_silver_total_amount']);
    $data['ytd_icpl_margin'] = ($layer_one_details['ytd_gold_policy_count'] * 23.61) + ($layer_one_details['ytd_platinum_policy_count'] * 15.73) + ($layer_one_details['ytd_sapphire_policy_count'] * 77.15) + ($layer_one_details['ytd_silver_policy_count'] * 23.21);
    /*********************YTD DATA***************************/

    //revenue
    $data['td_platinum_sell_value'] = $layer_one_details['td_platinum_policy_count'] * 373.73;
    $data['td_sapphire_sell_value'] = $layer_one_details['td_sapphire_policy_count'] * 399.15;
    $data['td_gold_sell_value'] = $layer_one_details['td_gold_policy_count'] * 296.61;
    $data['td_silver_sell_value'] = $layer_one_details['td_silver_policy_count'] * 212.71;
    $data['td_revenue'] = ($data['td_platinum_sell_value'] + $data['td_sapphire_sell_value'] + $data['td_gold_sell_value'] + $data['td_silver_sell_value']);
    $data['td_count'] = ($layer_one_details['td_platinum_policy_count'] + $layer_one_details['td_sapphire_policy_count'] + $layer_one_details['td_gold_policy_count'] + $layer_one_details['td_silver_policy_count']);
   
    $data['mtd_platinum_sell_value'] = $layer_one_details['mtd_platinum_policy_count'] * 373.73;
    $data['mtd_sapphire_sell_value'] = $layer_one_details['mtd_sapphire_policy_count'] * 399.15;
    $data['mtd_gold_sell_value'] = $layer_one_details['mtd_gold_policy_count'] * 296.61;
    $data['mtd_silver_sell_value'] = $layer_one_details['mtd_silver_policy_count'] * 212.71;
    $data['mtd_revenue'] = ($data['mtd_platinum_sell_value'] + $data['mtd_sapphire_sell_value'] + $data['mtd_gold_sell_value'] + $data['mtd_silver_sell_value']); 
    $data['mtd_count'] = ($layer_one_details['mtd_platinum_policy_count'] + $layer_one_details['mtd_sapphire_policy_count'] + $layer_one_details['mtd_gold_policy_count'] + $layer_one_details['mtd_silver_policy_count']);    
   
    $data['ytd_platinum_sell_value'] = $layer_one_details['ytd_platinum_policy_count'] * 373.73;
    $data['ytd_sapphire_sell_value'] = $layer_one_details['ytd_sapphire_policy_count'] * 399.15;
    $data['ytd_gold_sell_value'] = $layer_one_details['ytd_gold_policy_count'] * 296.61;
    $data['ytd_silver_sell_value'] = $layer_one_details['ytd_silver_policy_count'] * 212.71;
    $data['ytd_revenue'] = ($data['ytd_platinum_sell_value'] + $data['ytd_sapphire_sell_value'] + $data['ytd_gold_sell_value'] + $data['ytd_silver_sell_value']); 
    $data['ytd_count'] = ($layer_one_details['ytd_platinum_policy_count'] + $layer_one_details['ytd_sapphire_policy_count'] + $layer_one_details['ytd_gold_policy_count'] + $layer_one_details['ytd_silver_policy_count']);
    //end revenue
    
    
    //expenditure
    $data['td_platinum_purchase_cost'] = $layer_one_details['td_platinum_policy_count'] * 210;
    $data['td_sapphire_purchase_cost'] = $layer_one_details['td_sapphire_policy_count'] * 210;
    $data['td_gold_purchase_cost'] = $layer_one_details['td_gold_policy_count'] * 140;
    $data['td_silver_purchase_cost'] = $layer_one_details['td_silver_policy_count'] * 70;
    $data['td_total_pa_amount'] = ($data['td_platinum_purchase_cost'] + $data['td_sapphire_purchase_cost'] + $data['td_gold_purchase_cost'] + $data['td_silver_purchase_cost']);
    $data['rsa_td_platinum_purchase_cost'] = $layer_one_details['td_platinum_policy_count'] * 16;
    $data['rsa_td_sapphire_purchase_cost'] = $layer_one_details['td_sapphire_policy_count'] * 32;
    $data['rsa_td_gold_purchase_cost'] = $layer_one_details['td_gold_policy_count'] * 16;
    $data['rsa_td_silver_purchase_cost'] = $layer_one_details['td_silver_policy_count'] * 16;
    $data['td_total_rsa_amount'] = ($data['rsa_td_platinum_purchase_cost'] + $data['rsa_td_sapphire_purchase_cost'] + $data['rsa_td_gold_purchase_cost'] + $data['rsa_td_silver_purchase_cost']);
    $data['dc_td_platinum_purchase_cost'] = $layer_one_details['td_platinum_policy_count'] * 75;
    $data['dc_td_sapphire_purchase_cost'] = $layer_one_details['td_sapphire_policy_count'] * 80;
    $data['dc_td_gold_purchase_cost'] = $layer_one_details['td_gold_policy_count'] * 60;
    $data['dc_td_silver_purchase_cost'] = $layer_one_details['td_silver_policy_count'] * 50;
    $data['td_total_dc_amount'] = ($data['dc_td_platinum_purchase_cost'] + $data['dc_td_sapphire_purchase_cost'] + $data['dc_td_gold_purchase_cost'] + $data['dc_td_silver_purchase_cost']);
    $data['tvs_td_platinum_purchase_cost'] = $layer_one_details['td_platinum_policy_count'] * 57;
    $data['tvs_td_sapphire_purchase_cost'] = $layer_one_details['td_sapphire_policy_count'] * 54;
    $data['tvs_td_gold_purchase_cost'] = $layer_one_details['td_gold_policy_count'] * 57;
    $data['tvs_td_silver_purchase_cost'] = $layer_one_details['td_silver_policy_count'] * 53.5;
    $data['td_total_tvs_amount'] = ($data['tvs_td_platinum_purchase_cost'] + $data['tvs_td_sapphire_purchase_cost'] + $data['tvs_td_gold_purchase_cost'] + $data['tvs_td_silver_purchase_cost']);
    $data['td_total_expenditure_amount'] = ($data['td_total_pa_amount'] + $data['td_total_rsa_amount'] + $data['td_total_dc_amount'] + $data['td_total_tvs_amount']);
    
    //mtd
    $data['mtd_platinum_purchase_cost'] = $layer_one_details['mtd_platinum_policy_count'] * 210;
    $data['mtd_sapphire_purchase_cost'] = $layer_one_details['mtd_sapphire_policy_count'] * 210;
    $data['mtd_gold_purchase_cost'] = $layer_one_details['mtd_gold_policy_count'] * 140;
    $data['mtd_silver_purchase_cost'] = $layer_one_details['mtd_silver_policy_count'] * 70;
    $data['mtd_total_pa_amount'] = ($data['mtd_platinum_purchase_cost'] + $data['mtd_sapphire_purchase_cost'] + $data['mtd_gold_purchase_cost'] + $data['mtd_silver_purchase_cost']);

    $data['rsa_mtd_platinum_purchase_cost'] = $layer_one_details['mtd_platinum_policy_count'] * 16;
    $data['rsa_mtd_sapphire_purchase_cost'] = $layer_one_details['mtd_sapphire_policy_count'] * 32;
    $data['rsa_mtd_gold_purchase_cost'] = $layer_one_details['mtd_gold_policy_count'] * 16;
    $data['rsa_mtd_silver_purchase_cost'] = $layer_one_details['mtd_silver_policy_count'] * 16;
    $data['mtd_total_rsa_amount'] = ($data['rsa_mtd_platinum_purchase_cost'] + $data['rsa_mtd_sapphire_purchase_cost'] + $data['rsa_mtd_gold_purchase_cost'] + $data['rsa_mtd_silver_purchase_cost']);

    $data['dc_mtd_platinum_purchase_cost'] = $layer_one_details['mtd_platinum_policy_count'] * 75;
    $data['dc_mtd_sapphire_purchase_cost'] = $layer_one_details['mtd_sapphire_policy_count'] * 80;
    $data['dc_mtd_gold_purchase_cost'] = $layer_one_details['mtd_gold_policy_count'] * 60;
    $data['dc_mtd_silver_purchase_cost'] = $layer_one_details['mtd_silver_policy_count'] * 50;
    $data['mtd_total_dc_amount'] = ($data['dc_mtd_platinum_purchase_cost'] + $data['dc_mtd_sapphire_purchase_cost'] + $data['dc_mtd_gold_purchase_cost'] + $data['dc_mtd_silver_purchase_cost']);

    $data['tvs_mtd_platinum_purchase_cost'] = $layer_one_details['mtd_platinum_policy_count'] * 57;
    $data['tvs_mtd_sapphire_purchase_cost'] = $layer_one_details['mtd_sapphire_policy_count'] * 54;
    $data['tvs_mtd_gold_purchase_cost'] = $layer_one_details['mtd_gold_policy_count'] * 57;
    $data['tvs_mtd_silver_purchase_cost'] = $layer_one_details['mtd_silver_policy_count'] * 53.5;
    $data['mtd_total_tvs_amount'] = ($data['tvs_mtd_platinum_purchase_cost'] + $data['tvs_mtd_sapphire_purchase_cost'] + $data['tvs_mtd_gold_purchase_cost'] + $data['tvs_mtd_silver_purchase_cost']);
    $data['mtd_total_expenditure_amount'] = ($data['mtd_total_pa_amount'] + $data['mtd_total_rsa_amount'] + $data['mtd_total_dc_amount'] + $data['mtd_total_tvs_amount']);
    //ytd
    $data['ytd_platinum_purchase_cost'] = $layer_one_details['ytd_platinum_policy_count'] * 210;
    $data['ytd_sapphire_purchase_cost'] = $layer_one_details['ytd_sapphire_policy_count'] * 210;
    $data['ytd_gold_purchase_cost'] = $layer_one_details['ytd_gold_policy_count'] * 140;
    $data['ytd_silver_purchase_cost'] = $layer_one_details['ytd_silver_policy_count'] * 70;
    $data['ytd_total_pa_amount'] = ($data['ytd_platinum_purchase_cost'] + $data['ytd_sapphire_purchase_cost'] + $data['ytd_gold_purchase_cost'] + $data['ytd_silver_purchase_cost']);
    
    $data['rsa_ytd_platinum_purchase_cost'] = $layer_one_details['ytd_platinum_policy_count'] * 16;
    $data['rsa_ytd_sapphire_purchase_cost'] = $layer_one_details['ytd_sapphire_policy_count'] * 32;
    $data['rsa_ytd_gold_purchase_cost'] = $layer_one_details['ytd_gold_policy_count'] * 16;
    $data['rsa_ytd_silver_purchase_cost'] = $layer_one_details['ytd_silver_policy_count'] * 16;
    $data['ytd_total_rsa_amount'] = ($data['rsa_ytd_platinum_purchase_cost'] + $data['rsa_ytd_sapphire_purchase_cost'] + $data['rsa_ytd_gold_purchase_cost'] + $data['rsa_ytd_silver_purchase_cost']);
    
    $data['dc_ytd_platinum_purchase_cost'] = $layer_one_details['ytd_platinum_policy_count'] * 75;
    $data['dc_ytd_sapphire_purchase_cost'] = $layer_one_details['ytd_sapphire_policy_count'] * 80;
    $data['dc_ytd_gold_purchase_cost'] = $layer_one_details['ytd_gold_policy_count'] * 60;
    $data['dc_ytd_silver_purchase_cost'] = $layer_one_details['ytd_silver_policy_count'] * 50;
    $data['ytd_total_dc_amount'] = ($data['dc_ytd_platinum_purchase_cost'] + $data['dc_ytd_sapphire_purchase_cost'] + $data['dc_ytd_gold_purchase_cost'] + $data['dc_ytd_silver_purchase_cost']);
    
    $data['tvs_ytd_platinum_purchase_cost'] = $layer_one_details['ytd_platinum_policy_count'] * 57;
    $data['tvs_ytd_sapphire_purchase_cost'] = $layer_one_details['ytd_sapphire_policy_count'] * 54;
    $data['tvs_ytd_gold_purchase_cost'] = $layer_one_details['ytd_gold_policy_count'] * 57;
    $data['tvs_ytd_silver_purchase_cost'] = $layer_one_details['ytd_silver_policy_count'] * 53.5;
    $data['ytd_total_tvs_amount'] = ($data['tvs_ytd_platinum_purchase_cost'] + $data['tvs_ytd_sapphire_purchase_cost'] + $data['tvs_ytd_gold_purchase_cost'] + $data['tvs_ytd_silver_purchase_cost']);
    $data['ytd_total_expenditure_amount'] = ($data['ytd_total_pa_amount'] + $data['ytd_total_rsa_amount'] + $data['ytd_total_dc_amount'] + $data['ytd_total_tvs_amount']);
    //end expenditure
    
    //GROSS PROFIT
    $data['td_gross_profit'] = ($data['td_revenue']-$data['td_total_expenditure_amount']);
    $data['mtd_gross_profit'] = ($data['mtd_revenue']-$data['mtd_total_expenditure_amount']);
    $data['ytd_gross_profit'] = ($data['ytd_revenue']-$data['ytd_total_expenditure_amount']);
    //END GROSS PROFIT
    
    //RECEIPT & PAYMENT
    //RECEIPT
    $data['td_platinum_receipt_amount'] = $layer_one_details['td_platinum_policy_count'] * 441;
    $data['td_sapphire_receipt_amount'] = $layer_one_details['td_sapphire_policy_count'] * 471;
    $data['td_gold_receipt_amount'] = $layer_one_details['td_gold_policy_count'] * 350;
    $data['td_silver_receipt_amount'] = $layer_one_details['td_silver_policy_count'] * 251;
    //$data['td_receipt_amount'] = ($data['td_platinum_receipt_amount'] + $data['td_sapphire_receipt_amount'] + $data['td_gold_receipt_amount'] + $data['td_silver_receipt_amount']); 
   
    $data['mtd_platinum_receipt_amount'] = $layer_one_details['mtd_platinum_policy_count'] * 441;
    $data['mtd_sapphire_receipt_amount'] = $layer_one_details['mtd_sapphire_policy_count'] * 471;
    $data['mtd_gold_receipt_amount'] = $layer_one_details['mtd_gold_policy_count'] * 350;
    $data['mtd_silver_receipt_amount'] = $layer_one_details['mtd_silver_policy_count'] * 251;
    //$data['mtd_receipt_amount'] = ($data['mtd_platinum_receipt_amount'] + $data['mtd_sapphire_receipt_amount'] + $data['mtd_gold_receipt_amount'] + $data['mtd_silver_receipt_amount']);
   
    $data['ytd_platinum_receipt_amount'] = $layer_one_details['ytd_platinum_policy_count'] * 441;
    $data['ytd_sapphire_receipt_amount'] = $layer_one_details['ytd_sapphire_policy_count'] * 471;
    $data['ytd_gold_receipt_amount'] = $layer_one_details['ytd_gold_policy_count'] * 350;
    $data['ytd_silver_receipt_amount'] = $layer_one_details['ytd_silver_policy_count'] * 251;
    //$data['ytd_receipt_amount'] = ($data['ytd_platinum_receipt_amount'] + $data['ytd_sapphire_receipt_amount'] + $data['ytd_gold_receipt_amount'] + $data['ytd_silver_receipt_amount']); 
    //END RECEIPT
    //PAYMENT
    //td pa
    $data['td_platinum_payment_amount_without_gst'] = $layer_one_details['td_platinum_policy_count'] * 210;
    $data['td_platinum_payment_gst_amount'] = $layer_one_details['td_platinum_policy_count'] * 210 * 0.18;
    $data['td_platinum_payment_tds_amount'] = $layer_one_details['td_platinum_policy_count'] * 210 * 0.02;
    $data['td_pa_platinum_payment_amount'] = (($data['td_platinum_payment_amount_without_gst'] + $data['td_platinum_payment_gst_amount']) - ($data['td_platinum_payment_tds_amount']));
    
    $data['td_gold_payment_amount_without_gst'] = $layer_one_details['td_gold_policy_count'] * 140;
    $data['td_gold_payment_gst_amount'] = $layer_one_details['td_gold_policy_count'] * 140 * 0.18;
    $data['td_gold_payment_tds_amount'] = $layer_one_details['td_gold_policy_count'] * 140 * 0.02;
    $data['td_pa_gold_payment_amount'] = (($data['td_gold_payment_amount_without_gst'] + $data['td_gold_payment_gst_amount']) - ($data['td_gold_payment_tds_amount']));
    
    $data['td_silver_payment_amount_without_gst'] = $layer_one_details['td_silver_policy_count'] * 70;
    $data['td_silver_payment_gst_amount'] = $layer_one_details['td_silver_policy_count'] * 70 * 0.18;
    $data['td_silver_payment_tds_amount'] = $layer_one_details['td_silver_policy_count'] * 70 * 0.02;
    $data['td_pa_silver_payment_amount'] = (($data['td_silver_payment_amount_without_gst'] + $data['td_silver_payment_gst_amount']) - ($data['td_silver_payment_tds_amount']));
    
    $data['td_sapphire_payment_amount_without_gst'] = $layer_one_details['td_sapphire_policy_count'] * 210;
    $data['td_sapphire_payment_gst_amount'] = $layer_one_details['td_sapphire_policy_count'] * 210 * 0.18;
    $data['td_sapphire_payment_tds_amount'] = $layer_one_details['td_sapphire_policy_count'] * 210 * 0.02;
    $data['td_pa_sapphire_payment_amount'] = (($data['td_sapphire_payment_amount_without_gst'] + $data['td_sapphire_payment_gst_amount']) - ($data['td_sapphire_payment_tds_amount']));
    
    $data['td_pa_total_payment_amount'] = ($data['td_pa_platinum_payment_amount'] + $data['td_pa_gold_payment_amount'] + $data['td_pa_silver_payment_amount'] + $data['td_pa_sapphire_payment_amount']);
   
    //mtd pa
    $data['mtd_platinum_payment_amount_without_gst'] = $layer_one_details['mtd_platinum_policy_count'] * 210;
    $data['mtd_platinum_payment_gst_amount'] = $layer_one_details['mtd_platinum_policy_count'] * 210 * 0.18;
    $data['mtd_platinum_payment_tds_amount'] = $layer_one_details['mtd_platinum_policy_count'] * 210 * 0.02;
    $data['mtd_pa_platinum_payment_amount'] = (($data['mtd_platinum_payment_amount_without_gst'] + $data['mtd_platinum_payment_gst_amount']) - ($data['mtd_platinum_payment_tds_amount']));
    
    $data['mtd_gold_payment_amount_without_gst'] = $layer_one_details['mtd_gold_policy_count'] * 140;
    $data['mtd_gold_payment_gst_amount'] = $layer_one_details['mtd_gold_policy_count'] * 140 * 0.18;
    $data['mtd_gold_payment_tds_amount'] = $layer_one_details['mtd_gold_policy_count'] * 140 * 0.02;
    $data['mtd_pa_gold_payment_amount'] = (($data['mtd_gold_payment_amount_without_gst'] + $data['mtd_gold_payment_gst_amount']) - ($data['mtd_gold_payment_tds_amount']));
    
    $data['mtd_silver_payment_amount_without_gst'] = $layer_one_details['mtd_silver_policy_count'] * 70;
    $data['mtd_silver_payment_gst_amount'] = $layer_one_details['mtd_silver_policy_count'] * 70 * 0.18;
    $data['mtd_silver_payment_tds_amount'] = $layer_one_details['mtd_silver_policy_count'] * 70 * 0.02;
    $data['mtd_pa_silver_payment_amount'] = (($data['mtd_silver_payment_amount_without_gst'] + $data['mtd_silver_payment_gst_amount']) - ($data['mtd_silver_payment_tds_amount']));
    
    $data['mtd_sapphire_payment_amount_without_gst'] = $layer_one_details['mtd_sapphire_policy_count'] * 210;
    $data['mtd_sapphire_payment_gst_amount'] = $layer_one_details['mtd_sapphire_policy_count'] * 210 * 0.18;
    $data['mtd_sapphire_payment_tds_amount'] = $layer_one_details['mtd_sapphire_policy_count'] * 210 * 0.02;
    $data['mtd_pa_sapphire_payment_amount'] = (($data['mtd_sapphire_payment_amount_without_gst'] + $data['mtd_sapphire_payment_gst_amount']) - ($data['mtd_sapphire_payment_tds_amount']));
    
    $data['mtd_pa_total_payment_amount'] = ($data['mtd_pa_platinum_payment_amount'] + $data['mtd_pa_gold_payment_amount'] + $data['mtd_pa_silver_payment_amount'] + $data['mtd_pa_sapphire_payment_amount']);
    
     //ytd pa
    $data['ytd_platinum_payment_amount_without_gst'] = $layer_one_details['ytd_platinum_policy_count'] * 210;
    $data['ytd_platinum_payment_gst_amount'] = $layer_one_details['ytd_platinum_policy_count'] * 210 * 0.18;
    $data['ytd_platinum_payment_tds_amount'] = $layer_one_details['ytd_platinum_policy_count'] * 210 * 0.02;
    $data['ytd_pa_platinum_payment_amount'] = (($data['ytd_platinum_payment_amount_without_gst'] + $data['ytd_platinum_payment_gst_amount']) - ($data['ytd_platinum_payment_tds_amount']));
    
    $data['ytd_gold_payment_amount_without_gst'] = $layer_one_details['ytd_gold_policy_count'] * 140;
    $data['ytd_gold_payment_gst_amount'] = $layer_one_details['ytd_gold_policy_count'] * 140 * 0.18;
    $data['ytd_gold_payment_tds_amount'] = $layer_one_details['ytd_gold_policy_count'] * 140 * 0.02;
    $data['ytd_pa_gold_payment_amount'] = (($data['ytd_gold_payment_amount_without_gst'] + $data['ytd_gold_payment_gst_amount']) - ($data['ytd_gold_payment_tds_amount']));
    
    $data['ytd_silver_payment_amount_without_gst'] = $layer_one_details['ytd_silver_policy_count'] * 70;
    $data['ytd_silver_payment_gst_amount'] = $layer_one_details['ytd_silver_policy_count'] * 70 * 0.18;
    $data['ytd_silver_payment_tds_amount'] = $layer_one_details['ytd_silver_policy_count'] * 70 * 0.02;
    $data['ytd_pa_silver_payment_amount'] = (($data['ytd_silver_payment_amount_without_gst'] + $data['ytd_silver_payment_gst_amount']) - ($data['ytd_silver_payment_tds_amount']));
    
    $data['ytd_sapphire_payment_amount_without_gst'] = $layer_one_details['ytd_sapphire_policy_count'] * 210;
    $data['ytd_sapphire_payment_gst_amount'] = $layer_one_details['ytd_sapphire_policy_count'] * 210 * 0.18;
    $data['ytd_sapphire_payment_tds_amount'] = $layer_one_details['ytd_sapphire_policy_count'] * 210 * 0.02;
    $data['ytd_pa_sapphire_payment_amount'] = (($data['ytd_sapphire_payment_amount_without_gst'] + $data['ytd_sapphire_payment_gst_amount']) - ($data['ytd_sapphire_payment_tds_amount']));
    
    $data['ytd_pa_total_payment_amount'] = ($data['ytd_pa_platinum_payment_amount'] + $data['ytd_pa_gold_payment_amount'] + $data['ytd_pa_silver_payment_amount'] + $data['ytd_pa_sapphire_payment_amount']);
    //ytd
    //td rsa
    $data['td_rsa_platinum_payment_amount_without_gst'] = $layer_one_details['td_platinum_policy_count'] * 16;
    $data['td_rsa_platinum_payment_gst_amount'] = $layer_one_details['td_platinum_policy_count'] * 16 * 0.18;
    $data['td_rsa_platinum_payment_tds_amount'] = $layer_one_details['td_platinum_policy_count'] * 16 * 0.02;
    $data['td_rsa_platinum_payment_amount'] = (($data['td_rsa_platinum_payment_amount_without_gst'] + $data['td_rsa_platinum_payment_gst_amount']) - ($data['td_rsa_platinum_payment_tds_amount']));
    
    $data['td_rsa_gold_payment_amount_without_gst'] = $layer_one_details['td_gold_policy_count'] * 16;
    $data['td_rsa_gold_payment_gst_amount'] = $layer_one_details['td_gold_policy_count'] * 16 * 0.18;
    $data['td_rsa_gold_payment_tds_amount'] = $layer_one_details['td_gold_policy_count'] * 16 * 0.02;
    $data['td_rsa_gold_payment_amount'] = (($data['td_rsa_gold_payment_amount_without_gst'] + $data['td_rsa_gold_payment_gst_amount']) - ($data['td_rsa_gold_payment_tds_amount']));
    
    $data['td_rsa_silver_payment_amount_without_gst'] = $layer_one_details['td_silver_policy_count'] * 16;
    $data['td_rsa_silver_payment_gst_amount'] = $layer_one_details['td_silver_policy_count'] * 16 * 0.18;
    $data['td_rsa_silver_payment_tds_amount'] = $layer_one_details['td_silver_policy_count'] * 16 * 0.02;
    $data['td_rsa_silver_payment_amount'] = (($data['td_rsa_silver_payment_amount_without_gst'] + $data['td_rsa_silver_payment_gst_amount']) - ($data['td_rsa_silver_payment_tds_amount']));
    
    $data['td_rsa_sapphire_payment_amount_without_gst'] = $layer_one_details['td_sapphire_policy_count'] * 32;
    $data['td_rsa_sapphire_payment_gst_amount'] = $layer_one_details['td_sapphire_policy_count'] * 32 * 0.18;
    $data['td_rsa_sapphire_payment_tds_amount'] = $layer_one_details['td_sapphire_policy_count'] * 32 * 0.02;
    $data['td_rsa_sapphire_payment_amount'] = (($data['td_rsa_sapphire_payment_amount_without_gst'] + $data['td_rsa_sapphire_payment_gst_amount']) - ($data['td_rsa_sapphire_payment_tds_amount']));
    
    $data['td_rsa_total_payment_amount'] = ($data['td_rsa_platinum_payment_amount'] + $data['td_rsa_gold_payment_amount'] + $data['td_rsa_silver_payment_amount'] + $data['td_rsa_sapphire_payment_amount']);
   
    //mtd rsa
    $data['mtd_rsa_platinum_payment_amount_without_gst'] = $layer_one_details['mtd_platinum_policy_count'] * 16;
    $data['mtd_rsa_platinum_payment_gst_amount'] = $layer_one_details['mtd_platinum_policy_count'] * 16 * 0.18;
    $data['mtd_rsa_platinum_payment_tds_amount'] = $layer_one_details['mtd_platinum_policy_count'] * 16 * 0.02;
    $data['mtd_rsa_platinum_payment_amount'] = (($data['mtd_rsa_platinum_payment_amount_without_gst'] + $data['mtd_rsa_platinum_payment_gst_amount']) - ($data['mtd_rsa_platinum_payment_tds_amount']));
    
    $data['mtd_rsa_gold_payment_amount_without_gst'] = $layer_one_details['mtd_gold_policy_count'] * 16;
    $data['mtd_rsa_gold_payment_gst_amount'] = $layer_one_details['mtd_gold_policy_count'] * 16 * 0.18;
    $data['mtd_rsa_gold_payment_tds_amount'] = $layer_one_details['mtd_gold_policy_count'] * 16 * 0.02;
    $data['mtd_rsa_gold_payment_amount'] = (($data['mtd_rsa_gold_payment_amount_without_gst'] + $data['mtd_rsa_gold_payment_gst_amount']) - ($data['mtd_rsa_gold_payment_tds_amount']));
    
    $data['mtd_rsa_silver_payment_amount_without_gst'] = $layer_one_details['mtd_silver_policy_count'] * 16;
    $data['mtd_rsa_silver_payment_gst_amount'] = $layer_one_details['mtd_silver_policy_count'] * 16 * 0.18;
    $data['mtd_rsa_silver_payment_tds_amount'] = $layer_one_details['mtd_silver_policy_count'] * 16 * 0.02;
    $data['mtd_rsa_silver_payment_amount'] = (($data['mtd_rsa_silver_payment_amount_without_gst'] + $data['mtd_rsa_silver_payment_gst_amount']) - ($data['mtd_rsa_silver_payment_tds_amount']));
    
    $data['mtd_rsa_sapphire_payment_amount_without_gst'] = $layer_one_details['mtd_sapphire_policy_count'] * 32;
    $data['mtd_rsa_sapphire_payment_gst_amount'] = $layer_one_details['mtd_sapphire_policy_count'] * 32 * 0.18;
    $data['mtd_rsa_sapphire_payment_tds_amount'] = $layer_one_details['mtd_sapphire_policy_count'] * 32 * 0.02;
    $data['mtd_rsa_sapphire_payment_amount'] = (($data['mtd_rsa_sapphire_payment_amount_without_gst'] + $data['mtd_rsa_sapphire_payment_gst_amount']) - ($data['mtd_rsa_sapphire_payment_tds_amount']));
    
    $data['mtd_rsa_total_payment_amount'] = ($data['mtd_rsa_platinum_payment_amount'] + $data['mtd_rsa__gold_payment_amount'] + $data['mtd_rsa__silver_payment_amount'] + $data['mtd_rsa__sapphire_payment_amount']);
    
     //ytd rsa
    $data['ytd_rsa_platinum_payment_amount_without_gst'] = $layer_one_details['ytd_platinum_policy_count'] * 16;
    $data['ytd_rsa_platinum_payment_gst_amount'] = $layer_one_details['ytd_platinum_policy_count'] * 16 * 0.18;
    $data['ytd_rsa_platinum_payment_tds_amount'] = $layer_one_details['ytd_platinum_policy_count'] * 16 * 0.02;
    $data['ytd_rsa_platinum_payment_amount'] = (($data['ytd_rsa_platinum_payment_amount_without_gst'] + $data['ytd_rsa_platinum_payment_gst_amount']) - ($data['ytd_rsa_platinum_payment_tds_amount']));
    
    $data['ytd_rsa_gold_payment_amount_without_gst'] = $layer_one_details['ytd_gold_policy_count'] * 16;
    $data['ytd_rsa_gold_payment_gst_amount'] = $layer_one_details['ytd_gold_policy_count'] * 16 * 0.18;
    $data['ytd_rsa_gold_payment_tds_amount'] = $layer_one_details['ytd_gold_policy_count'] * 16 * 0.02;
    $data['ytd_rsa_gold_payment_amount'] = (($data['ytd_rsa_gold_payment_amount_without_gst'] + $data['ytd_rsa_gold_payment_gst_amount']) - ($data['ytd_rsa_gold_payment_tds_amount']));
    
    $data['ytd_rsa_silver_payment_amount_without_gst'] = $layer_one_details['ytd_silver_policy_count'] * 16;
    $data['ytd_rsa_silver_payment_gst_amount'] = $layer_one_details['ytd_silver_policy_count'] * 16 * 0.18;
    $data['ytd_rsa_silver_payment_tds_amount'] = $layer_one_details['ytd_silver_policy_count'] * 16 * 0.02;
    $data['ytd_rsa_silver_payment_amount'] = (($data['ytd_rsa_silver_payment_amount_without_gst'] + $data['ytd_rsa_silver_payment_gst_amount']) - ($data['ytd_rsa_silver_payment_tds_amount']));
    
    $data['ytd_rsa_sapphire_payment_amount_without_gst'] = $layer_one_details['ytd_sapphire_policy_count'] * 32;
    $data['ytd_rsa_sapphire_payment_gst_amount'] = $layer_one_details['ytd_sapphire_policy_count'] * 32 * 0.18;
    $data['ytd_rsa_sapphire_payment_tds_amount'] = $layer_one_details['ytd_sapphire_policy_count'] * 32 * 0.02;
    $data['ytd_rsa_sapphire_payment_amount'] = (($data['ytd_rsa_sapphire_payment_amount_without_gst'] + $data['ytd_rsa_sapphire_payment_gst_amount']) - ($data['ytd_rsa_sapphire_payment_tds_amount']));
    
    $data['ytd_rsa_total_payment_amount'] = ($data['ytd_rsa_platinum_payment_amount'] + $data['ytd_rsa_gold_payment_amount'] + $data['ytd_rsa_silver_payment_amount'] + $data['ytd_rsa_sapphire_payment_amount']);
    //ytd
    //td DC
    $data['td_dc_platinum_payment_amount_without_gst'] = $layer_one_details['td_platinum_policy_count'] * 75;
    $data['td_dc_platinum_payment_gst_amount'] = $layer_one_details['td_platinum_policy_count'] * 75 * 0.18;
    $data['td_dc_platinum_payment_tds_amount'] = $layer_one_details['td_platinum_policy_count'] * 75 * 0.05;
    $data['td_dc_platinum_payment_amount'] = (($data['td_dc_platinum_payment_amount_without_gst'] + $data['td_dc_platinum_payment_gst_amount']) - ($data['td_dc_platinum_payment_tds_amount']));
    
    $data['td_dc_gold_payment_amount_without_gst'] = $layer_one_details['td_gold_policy_count'] * 60;
    $data['td_dc_gold_payment_gst_amount'] = $layer_one_details['td_gold_policy_count'] * 60 * 0.18;
    $data['td_dc_gold_payment_tds_amount'] = $layer_one_details['td_gold_policy_count'] * 60 * 0.05;
    $data['td_dc_gold_payment_amount'] = (($data['td_dc_gold_payment_amount_without_gst'] + $data['td_dc_gold_payment_gst_amount']) - ($data['td_dc_gold_payment_tds_amount']));
    
    $data['td_dc_silver_payment_amount_without_gst'] = $layer_one_details['td_silver_policy_count'] * 50;
    $data['td_dc_silver_payment_gst_amount'] = $layer_one_details['td_silver_policy_count'] * 50 * 0.18;
    $data['td_dc_silver_payment_tds_amount'] = $layer_one_details['td_silver_policy_count'] * 50 * 0.05;
    $data['td_dc_silver_payment_amount'] = (($data['td_dc_silver_payment_amount_without_gst'] + $data['td_dc_silver_payment_gst_amount']) - ($data['td_dc_silver_payment_tds_amount']));
    
    $data['td_dc_sapphire_payment_amount_without_gst'] = $layer_one_details['td_sapphire_policy_count'] * 80;
    $data['td_dc_sapphire_payment_gst_amount'] = $layer_one_details['td_sapphire_policy_count'] * 80 * 0.18;
    $data['td_dc_sapphire_payment_tds_amount'] = $layer_one_details['td_sapphire_policy_count'] * 80 * 0.05;
    $data['td_dc_sapphire_payment_amount'] = (($data['td_dc_sapphire_payment_amount_without_gst'] + $data['td_dc_sapphire_payment_gst_amount']) - ($data['td_dc_sapphire_payment_tds_amount']));
    
    $data['td_dc_total_payment_amount'] = ($data['td_dc_platinum_payment_amount'] + $data['td_dc_gold_payment_amount'] + $data['td_dc_silver_payment_amount'] + $data['td_dc_sapphire_payment_amount']);
   
    //mtd DC
    $data['mtd_dc_platinum_payment_amount_without_gst'] = $layer_one_details['mtd_platinum_policy_count'] * 75;
    $data['mtd_dc_platinum_payment_gst_amount'] = $layer_one_details['mtd_platinum_policy_count'] * 75 * 0.18;
    $data['mtd_dc_platinum_payment_tds_amount'] = $layer_one_details['mtd_platinum_policy_count'] * 75 * 0.05;
    $data['mtd_dc_platinum_payment_amount'] = (($data['mtd_dc_platinum_payment_amount_without_gst'] + $data['mtd_dc_platinum_payment_gst_amount']) - ($data['mtd_dc_platinum_payment_tds_amount']));
    
    $data['mtd_dc_gold_payment_amount_without_gst'] = $layer_one_details['mtd_gold_policy_count'] * 60;
    $data['mtd_dc_gold_payment_gst_amount'] = $layer_one_details['mtd_gold_policy_count'] * 60 * 0.18;
    $data['mtd_dc_gold_payment_tds_amount'] = $layer_one_details['mtd_gold_policy_count'] * 60 * 0.05;
    $data['mtd_dc_gold_payment_amount'] = (($data['mtd_dc_gold_payment_amount_without_gst'] + $data['mtd_dc_gold_payment_gst_amount']) - ($data['mtd_dc_gold_payment_tds_amount']));
    
    $data['mtd_dc_silver_payment_amount_without_gst'] = $layer_one_details['mtd_silver_policy_count'] * 50;
    $data['mtd_dc_silver_payment_gst_amount'] = $layer_one_details['mtd_silver_policy_count'] * 50 * 0.18;
    $data['mtd_dc_silver_payment_tds_amount'] = $layer_one_details['mtd_silver_policy_count'] * 50 * 0.05;
    $data['mtd_dc_silver_payment_amount'] = (($data['mtd_dc_silver_payment_amount_without_gst'] + $data['mtd_dc_silver_payment_gst_amount']) - ($data['mtd_dc_silver_payment_tds_amount']));
    
    $data['mtd_dc_sapphire_payment_amount_without_gst'] = $layer_one_details['mtd_sapphire_policy_count'] * 80;
    $data['mtd_dc_sapphire_payment_gst_amount'] = $layer_one_details['mtd_sapphire_policy_count'] * 80 * 0.18;
    $data['mtd_dc_sapphire_payment_tds_amount'] = $layer_one_details['mtd_sapphire_policy_count'] * 80 * 0.05;
    $data['mtd_dc_sapphire_payment_amount'] = (($data['mtd_dc_sapphire_payment_amount_without_gst'] + $data['mtd_dc_sapphire_payment_gst_amount']) - ($data['mtd_dc_sapphire_payment_tds_amount']));
    
    $data['mtd_dc_total_payment_amount'] = ($data['mtd_dc_platinum_payment_amount'] + $data['mtd_dc__gold_payment_amount'] + $data['mtd_dc__silver_payment_amount'] + $data['mtd_dc__sapphire_payment_amount']);
    
     //ytd DC
    $data['ytd_dc_platinum_payment_amount_without_gst'] = $layer_one_details['ytd_platinum_policy_count'] * 75;
    $data['ytd_dc_platinum_payment_gst_amount'] = $layer_one_details['ytd_platinum_policy_count'] * 75 * 0.18;
    $data['ytd_dc_platinum_payment_tds_amount'] = $layer_one_details['ytd_platinum_policy_count'] * 75 * 0.05;
    $data['ytd_dc_platinum_payment_amount'] = (($data['ytd_dc_platinum_payment_amount_without_gst'] + $data['ytd_dc_platinum_payment_gst_amount']) - ($data['ytd_dc_platinum_payment_tds_amount']));
    
    $data['ytd_dc_gold_payment_amount_without_gst'] = $layer_one_details['ytd_gold_policy_count'] * 60;
    $data['ytd_dc_gold_payment_gst_amount'] = $layer_one_details['ytd_gold_policy_count'] * 60 * 0.18;
    $data['ytd_dc_gold_payment_tds_amount'] = $layer_one_details['ytd_gold_policy_count'] * 60 * 0.05;
    $data['ytd_dc_gold_payment_amount'] = (($data['ytd_dc_gold_payment_amount_without_gst'] + $data['ytd_dc_gold_payment_gst_amount']) - ($data['ytd_dc_gold_payment_tds_amount']));
    
    $data['ytd_dc_silver_payment_amount_without_gst'] = $layer_one_details['ytd_silver_policy_count'] * 50;
    $data['ytd_dc_silver_payment_gst_amount'] = $layer_one_details['ytd_silver_policy_count'] * 50 * 0.18;
    $data['ytd_dc_silver_payment_tds_amount'] = $layer_one_details['ytd_silver_policy_count'] * 50 * 0.05;
    $data['ytd_dc_silver_payment_amount'] = (($data['ytd_dc_silver_payment_amount_without_gst'] + $data['ytd_dc_silver_payment_gst_amount']) - ($data['ytd_dc_silver_payment_tds_amount']));
    
    $data['ytd_dc_sapphire_payment_amount_without_gst'] = $layer_one_details['ytd_sapphire_policy_count'] * 80;
    $data['ytd_dc_sapphire_payment_gst_amount'] = $layer_one_details['ytd_sapphire_policy_count'] * 80 * 0.18;
    $data['ytd_dc_sapphire_payment_tds_amount'] = $layer_one_details['ytd_sapphire_policy_count'] * 80 * 0.05;
    $data['ytd_dc_sapphire_payment_amount'] = (($data['ytd_dc_sapphire_payment_amount_without_gst'] + $data['ytd_dc_sapphire_payment_gst_amount']) - ($data['ytd_dc_sapphire_payment_tds_amount']));
    
    $data['ytd_dc_total_payment_amount'] = ($data['ytd_dc_platinum_payment_amount'] + $data['ytd_dc_gold_payment_amount'] + $data['ytd_dc_silver_payment_amount'] + $data['ytd_dc_sapphire_payment_amount']);
    //ytd
    //td TVS
    $data['td_tvs_platinum_payment_amount_without_gst'] = $layer_one_details['td_platinum_policy_count'] * 57;
    $data['td_tvs_platinum_payment_gst_amount'] = $layer_one_details['td_platinum_policy_count'] * 48.5 * 0.18;
    $data['td_tvs_platinum_payment_tds_amount'] = $layer_one_details['td_platinum_policy_count'] * 48.5 * 0.10;
    $data['td_tvs_platinum_payment_amount'] = (($data['td_tvs_platinum_payment_amount_without_gst'] + $data['td_tvs_platinum_payment_gst_amount']) - ($data['td_tvs_platinum_payment_tds_amount']));
    
    $data['td_tvs_gold_payment_amount_without_gst'] = $layer_one_details['td_gold_policy_count'] * 57;
    $data['td_tvs_gold_payment_gst_amount'] = $layer_one_details['td_gold_policy_count'] * 57 * 0.18;
    $data['td_tvs_gold_payment_tds_amount'] = $layer_one_details['td_gold_policy_count'] * 57 * 0.10;
    $data['td_tvs_gold_payment_amount'] = (($data['td_tvs_gold_payment_amount_without_gst'] + $data['td_tvs_gold_payment_gst_amount']) - ($data['td_tvs_gold_payment_tds_amount']));
    
    $data['td_tvs_silver_payment_amount_without_gst'] = $layer_one_details['td_silver_policy_count'] * 53.5;
    $data['td_tvs_silver_payment_gst_amount'] = $layer_one_details['td_silver_policy_count'] * 53.5 * 0.18;
    $data['td_tvs_silver_payment_tds_amount'] = $layer_one_details['td_silver_policy_count'] * 53.5 * 0.10;
    $data['td_tvs_silver_payment_amount'] = (($data['td_tvs_silver_payment_amount_without_gst'] + $data['td_tvs_silver_payment_gst_amount']) - ($data['td_tvs_silver_payment_tds_amount']));
    
    $data['td_tvs_sapphire_payment_amount_without_gst'] = $layer_one_details['td_sapphire_policy_count'] * 54;
    $data['td_tvs_sapphire_payment_gst_amount'] = $layer_one_details['td_sapphire_policy_count'] * 54 * 0.18;
    $data['td_tvs_sapphire_payment_tds_amount'] = $layer_one_details['td_sapphire_policy_count'] * 54 * 0.10;
    $data['td_tvs_sapphire_payment_amount'] = (($data['td_tvs_sapphire_payment_amount_without_gst'] + $data['td_tvs_sapphire_payment_gst_amount']) - ($data['td_tvs_sapphire_payment_tds_amount']));
    
    $data['td_tvs_total_payment_amount'] = ($data['td_tvs_platinum_payment_amount'] + $data['td_tvs_gold_payment_amount'] + $data['td_tvs_silver_payment_amount'] + $data['td_tvs_sapphire_payment_amount']);
   
    //mtd tvs
    $data['mtd_tvs_platinum_payment_amount_without_gst'] = $layer_one_details['mtd_platinum_policy_count'] * 57;
    $data['mtd_tvs_platinum_payment_gst_amount'] = $layer_one_details['mtd_platinum_policy_count'] * 48.5 * 0.18;
    $data['mtd_tvs_platinum_payment_tds_amount'] = $layer_one_details['mtd_platinum_policy_count'] * 48.5 * 0.10;
    $data['mtd_tvs_platinum_payment_amount'] = (($data['mtd_tvs_platinum_payment_amount_without_gst'] + $data['mtd_tvs_platinum_payment_gst_amount']) - ($data['mtd_tvs_platinum_payment_tds_amount']));
    
    $data['mtd_tvs_gold_payment_amount_without_gst'] = $layer_one_details['mtd_gold_policy_count'] * 57;
    $data['mtd_tvs_gold_payment_gst_amount'] = $layer_one_details['mtd_gold_policy_count'] * 57 * 0.18;
    $data['mtd_tvs_gold_payment_tds_amount'] = $layer_one_details['mtd_gold_policy_count'] * 57 * 0.10;
    $data['mtd_tvs_gold_payment_amount'] = (($data['mtd_tvs_gold_payment_amount_without_gst'] + $data['mtd_tvs_gold_payment_gst_amount']) - ($data['mtd_tvs_gold_payment_tds_amount']));
    
    $data['mtd_tvs_silver_payment_amount_without_gst'] = $layer_one_details['mtd_silver_policy_count'] * 53.5;
    $data['mtd_tvs_silver_payment_gst_amount'] = $layer_one_details['mtd_silver_policy_count'] * 53.5 * 0.18;
    $data['mtd_tvs_silver_payment_tds_amount'] = $layer_one_details['mtd_silver_policy_count'] * 53.5 * 0.10;
    $data['mtd_tvs_silver_payment_amount'] = (($data['mtd_tvs_silver_payment_amount_without_gst'] + $data['mtd_tvs_silver_payment_gst_amount']) - ($data['mtd_tvs_silver_payment_tds_amount']));
    
    $data['mtd_tvs_sapphire_payment_amount_without_gst'] = $layer_one_details['mtd_sapphire_policy_count'] * 54;
    $data['mtd_tvs_sapphire_payment_gst_amount'] = $layer_one_details['mtd_sapphire_policy_count'] * 54 * 0.18;
    $data['mtd_tvs_sapphire_payment_tds_amount'] = $layer_one_details['mtd_sapphire_policy_count'] * 54 * 0.10;
    $data['mtd_tvs_sapphire_payment_amount'] = (($data['mtd_tvs_sapphire_payment_amount_without_gst'] + $data['mtd_tvs_sapphire_payment_gst_amount']) - ($data['mtd_tvs_sapphire_payment_tds_amount']));
    
    $data['mtd_tvs_total_payment_amount'] = ($data['mtd_tvs_platinum_payment_amount'] + $data['mtd_tvs__gold_payment_amount'] + $data['mtd_tvs__silver_payment_amount'] + $data['mtd_tvs__sapphire_payment_amount']);
    
     //ytd tvs
    $data['ytd_tvs_platinum_payment_amount_without_gst'] = $layer_one_details['ytd_platinum_policy_count'] * 57;
    $data['ytd_tvs_platinum_payment_gst_amount'] = $layer_one_details['ytd_platinum_policy_count'] * 48.5 * 0.18;
    $data['ytd_tvs_platinum_payment_tds_amount'] = $layer_one_details['ytd_platinum_policy_count'] * 48.5 * 0.10;
    $data['ytd_tvs_platinum_payment_amount'] = (($data['ytd_tvs_platinum_payment_amount_without_gst'] + $data['ytd_tvs_platinum_payment_gst_amount']) - ($data['ytd_tvs_platinum_payment_tds_amount']));
    
    $data['ytd_tvs_gold_payment_amount_without_gst'] = $layer_one_details['ytd_gold_policy_count'] * 57;
    $data['ytd_tvs_gold_payment_gst_amount'] = $layer_one_details['ytd_gold_policy_count'] * 57 * 0.18;
    $data['ytd_tvs_gold_payment_tds_amount'] = $layer_one_details['ytd_gold_policy_count'] * 57 * 0.10;
    $data['ytd_tvs_gold_payment_amount'] = (($data['ytd_tvs_gold_payment_amount_without_gst'] + $data['ytd_tvs_gold_payment_gst_amount']) - ($data['ytd_tvs_gold_payment_tds_amount']));
    
    $data['ytd_tvs_silver_payment_amount_without_gst'] = $layer_one_details['ytd_silver_policy_count'] * 53.5;
    $data['ytd_tvs_silver_payment_gst_amount'] = $layer_one_details['ytd_silver_policy_count'] * 53.5 * 0.18;
    $data['ytd_tvs_silver_payment_tds_amount'] = $layer_one_details['ytd_silver_policy_count'] * 53.5 * 0.10;
    $data['ytd_tvs_silver_payment_amount'] = (($data['ytd_tvs_silver_payment_amount_without_gst'] + $data['ytd_tvs_silver_payment_gst_amount']) - ($data['ytd_tvs_silver_payment_tds_amount']));
    
    $data['ytd_tvs_sapphire_payment_amount_without_gst'] = $layer_one_details['ytd_sapphire_policy_count'] * 54;
    $data['ytd_tvs_sapphire_payment_gst_amount'] = $layer_one_details['ytd_sapphire_policy_count'] * 54 * 0.18;
    $data['ytd_tvs_sapphire_payment_tds_amount'] = $layer_one_details['ytd_sapphire_policy_count'] * 54 * 0.10;
    $data['ytd_tvs_sapphire_payment_amount'] = (($data['ytd_tvs_sapphire_payment_amount_without_gst'] + $data['ytd_tvs_sapphire_payment_gst_amount']) - ($data['ytd_tvs_sapphire_payment_tds_amount']));
    
    $data['ytd_tvs_total_payment_amount'] = ($data['ytd_tvs_platinum_payment_amount'] + $data['ytd_tvs_gold_payment_amount'] + $data['ytd_tvs_silver_payment_amount'] + $data['ytd_tvs_sapphire_payment_amount']);
    //ytd tvs


    
        //total payment
    $data['td_net_fund'] = ($data['td_receipt_amount'] - $data['td_total_payment_amount']);
    $data['mtd_net_fund'] = ($data['mtd_receipt_amount'] - $data['mtd_total_payment_amount']);
    $data['ytd_net_fund'] = ($data['ytd_receipt_amount'] - $data['ytd_total_payment_amount']);
    
    //NET FUND
    $data['total_wallet_balance'] = $this->Home_Model->getTotalWalletBalance();//$this->Home_Model->liability();
    //END NET FUND
    
    $data['mtd_platinum_payment_amount'] = $layer_one_details['mtd_platinum_policy_count'] * 441;
    $data['mtd_sapphire_payment_amount'] = $layer_one_details['mtd_sapphire_policy_count'] * 471;
    $data['mtd_gold_payment_amount'] = $layer_one_details['mtd_gold_policy_count'] * 350;
    $data['mtd_silver_payment_amount'] = $layer_one_details['mtd_silver_policy_count'] * 251;
    $data['mtd_payment_amount'] = ($data['mtd_platinum_payment_amount'] + $data['mtd_sapphire_payment_amount'] + $data['mtd_gold_payment_amount'] + $data['mtd_silver_payment_amount']); 
   
    $data['ytd_platinum_payment_amount'] = $layer_one_details['ytd_platinum_policy_count'] * 441;
    $data['ytd_sapphire_payment_amount'] = $layer_one_details['ytd_sapphire_policy_count'] * 471;
    $data['ytd_gold_payment_amount'] = $layer_one_details['ytd_gold_policy_count'] * 350;
    $data['ytd_silver_payment_amount'] = $layer_one_details['ytd_silver_policy_count'] * 251;
    $data['ytd_payment_amount'] = ($data['ytd_platinum_payment_amount'] + $data['ytd_sapphire_payment_amount'] + $data['ytd_gold_payment_amount'] + $data['ytd_silver_payment_amount']);

   $data['ytd_platinum_policy_tds']   =($data['ytd_platinum_purchase_tds'] + $data['rsa_ytd_platinum_purchase_tds'] + $data['dc_ytd_platinum_purchase_tds'] + $data['tvs_ytd_platinum_purchase_tds']);
   
   $data['ytd_sapphire_policy_tds'] =($data['ytd_sapphire_purchase_tds'] + $data['rsa_ytd_sapphire_purchase_tds'] + $data['dc_ytd_sapphire_purchase_tds'] + $data['tvs_ytd_sapphire_purchase_tds']);
   //die($data['ytd_sapphire_policy_tds']);

   
   $data['ytd_gold_policy_tds'] =($data['ytd_gold_purchase_tds'] + $data['rsa_ytd_gold_purchase_tds'] + $data['dc_ytd_gold_purchase_tds'] + $data['tvs_ytd_gold_purchase_tds']);

   $data['ytd_silver_policy_tds'] =($data['ytd_silver_purchase_tds'] + $data['rsa_ytd_silver_purchase_tds'] + $data['dc_ytd_silver_purchase_tds'] + $data['tvs_ytd_silver_purchase_tds']);

    
    $data['assets'] = 0;
    $data['liabilities'] = 0;

    $data['kotak_ytd_balance'] = $data['kotak_ytd_deposit'] - $data['kotak_ytd_policy_amount'];
    $data['il_ytd_balance'] = $data['il_ytd_deposit'] - $data['il_ytd_policy_amount'];
    $data['tata_ytd_balance'] = $data['tata_ytd_deposit'] - $data['tata_ytd_policy_amount'];
    $data['bagi_ytd_balance'] = $data['bagi_ytd_deposit'] - $data['bagi_ytd_policy_amount'];
    $data['bharti_ytd_balance'] = $data['bharti_assist_ytd_deposit'] - $data['bharti_ytd_policy_count'];
    $data['tvs_balance'] = $data['ytd_tvs'] - $data['ytd_sold_tvs'];
    $data['gst_balance'] = $data['ytd_gst'] - ($data['ytd_silver_policy_net_gst'] + $data['ytd_sapphire_policy_net_gst'] + $data['ytd_platinum_policy_net_gst'] + $data['ytd_gold_policy_net_gst']);
    $data['tds_balance'] = $data['ytd_tds'] - $data['ytd_input_tds'];
    //die($data['gst_balance']);
    
    //isset($main['deposited_amount'])?$main['deposited_amount']:0;
    $total_values = array($data['kotak_ytd_balance'],$data['il_ytd_balance'],$data['tata_ytd_balance'],$data['bagi_ytd_balance'],$data['bharti_ytd_balance'],$data['tvs_balance'], $data['tds_balance'], $data['gst_balance']);
    foreach ($total_values as $key) {
        if($key>0){
            $data['assets']+=$key;
        } else{
            $data['liabilities']-=$key;            
        }
    }   


    //END PAYMENT
    //END RECEIPT & PAYMENT
    
    //ASSETS & LIABILITIES
    $totalSoldPolicies = $this->Home_Model->totalSoldPolicies();
        // echo '<pre>'; print_r($totalSoldPolicies);die('here');
        $bharti_deposit_amount = 0;
        $bharti_policy_amount = 0; 

        $kotak_deposit_amount = 0;
        $kotak_silver_amount = 0;
        $kotak_gold_amount = 0;
        $kotak_platinum_amount = 0;
        $kotak_sapphire_amount = 0;

        $il_deposit_amount = 0;
        $il_silver_amount = 0;
        $il_gold_amount = 0;
        $il_platinum_amount = 0;
        $il_sapphire_amount = 0;

        $ba_deposit_amount = 0;
        $ba_silver_amount = 0;
        $ba_gold_amount = 0;
        $ba_platinum_amount = 0;
        $ba_sapphire_amount = 0;

        $tagi_deposit_amount = 0;
        $tagi_silver_amount = 0;
        $tagi_gold_amount = 0;
        $tagi_platinum_amount = 0;
        $tagi_sapphire_amount = 0;
        //echo '<pre>'; print_r($totalSoldPolicies);
        foreach ($totalSoldPolicies as $key => $totalSoldPolicie) {
            
            if($totalSoldPolicie['id'] == 1 && $totalSoldPolicie['name'] == 'BHARTI ASSIST GLOBAL'){
               $bharti_deposit_amount = $totalSoldPolicie['balance_amount'];
               $bharti_policy_amount = $totalSoldPolicie['total_policies'] * (16 + (16 * 0.18));
            }
            if($totalSoldPolicie['id'] == 2 && $totalSoldPolicie['name'] == 'KOTAK'){
                $kotak_deposit_amount = $totalSoldPolicie['balance_amount'];
                $kotak_silver_amount = $totalSoldPolicie['silver_policies'] * (70 + (70 * 0.18));
                $kotak_gold_amount = $totalSoldPolicie['gold_policies'] * (140 + (140 * 0.18));
                $kotak_platinum_amount = $totalSoldPolicie['platinum_policies'] * (210 + (210 * 0.18));
                $kotak_sapphire_amount = $totalSoldPolicie['sapphire_policies'] * (210 + (210 * 0.18));
            }
            if($totalSoldPolicie['id'] == 5 && $totalSoldPolicie['name'] == 'ICICI Lombard'){
                $il_deposit_amount = $totalSoldPolicie['balance_amount'];
                $il_silver_amount = $totalSoldPolicie['silver_policies'] * (70 + (70 * 0.18));
                $il_gold_amount = $totalSoldPolicie['gold_policies'] * (140 + (140 * 0.18));
                $il_platinum_amount = $totalSoldPolicie['platinum_policies'] * (210 + (210 * 0.18));
                $il_sapphire_amount = $totalSoldPolicie['sapphire_policies'] * (210 + (210 * 0.18));
            }
            if($totalSoldPolicie['id'] == 12 && $totalSoldPolicie['name'] == 'Bharti AXA GI'){
                // die('here');
                $ba_deposit_amount = $totalSoldPolicie['balance_amount'];
                $ba_silver_amount = $totalSoldPolicie['silver_policies'] * (70 + (70 * 0.18));
                $ba_gold_amount = $totalSoldPolicie['gold_policies'] * (140 + (140 * 0.18));
                $ba_platinum_amount = $totalSoldPolicie['platinum_policies'] * (210 + (210 * 0.18));
                $ba_sapphire_amount = $totalSoldPolicie['sapphire_policies'] * (210 + (210 * 0.18));
            }
            if($totalSoldPolicie['id'] == 9 && $totalSoldPolicie['name'] == 'Tata AIG General Insurance Company'){
                // die('here');
                $tagi_deposit_amount = $totalSoldPolicie['balance_amount'];
                $tagi_silver_amount = $totalSoldPolicie['silver_policies'] * (70 + (70 * 0.18));
                $tagi_gold_amount = $totalSoldPolicie['gold_policies'] * (140 + (140 * 0.18));
                $tagi_platinum_amount = $totalSoldPolicie['platinum_policies'] * (210 + (210 * 0.18));
                $tagi_sapphire_amount = $totalSoldPolicie['sapphire_policies'] * (210 + (210 * 0.18));
            }
        }

        $data['kotak_total_policy_amount'] = $kotak_total_policy_amount = $kotak_silver_amount+$kotak_gold_amount+$kotak_platinum_amount+$kotak_sapphire_amount;
        $data['kotak_deposit_amount'] = $kotak_deposit_amount;
        $data['kotak_balance_amount'] = ($kotak_deposit_amount - $kotak_total_policy_amount);

        $data['bharti_policy_amount'] = $bharti_policy_amount;
        $data['bharti_balance_amount'] = ($bharti_deposit_amount - $bharti_policy_amount);
        $data['bharti_deposit_amount'] = ($bharti_deposit_amount);

        $data['ba_total_policy_amount'] = $ba_total_policy_amount = $ba_silver_amount+$ba_gold_amount+$ba_platinum_amount+$ba_sapphire_amount;
        $data['ba_deposit_amount'] = $ba_deposit_amount;
        $data['ba_balance_amount'] = ($ba_deposit_amount - $ba_total_policy_amount);

        $data['il_total_policy_amount'] = $il_total_policy_amount = $il_silver_amount+$il_gold_amount+$il_platinum_amount+$il_sapphire_amount;
        $data['il_deposit_amount'] = $il_deposit_amount;
        $data['il_balance_amount'] = ($il_deposit_amount - $il_total_policy_amount);

        $data['tagi_total_policy_amount'] = $tagi_total_policy_amount = $tagi_silver_amount+$tagi_gold_amount+$tagi_platinum_amount+$tagi_sapphire_amount;
        $data['tagi_deposit_amount'] = $tagi_deposit_amount;
        $data['tagi_balance_amount'] = ($tagi_deposit_amount - $tagi_total_policy_amount);
        $data['total_balance_amount'] = ($data['kotak_balance_amount'] + $data['ba_balance_amount'] + $data['il_balance_amount'] + $data['tagi_balance_amount']);
    //END ASSETS & LIABILITIES
  

     //Purchase Cost PA
     /*****************td data*******************/

    /*********************YTD DATA***************************/

/*$data['td_total_payment_amount'] = ($data['td_pa_total_payment_amount'] + $data['td_rsa_total_payment_amount'] + $data['td_dc_total_payment_amount'] + $data['td_tvs_total_payment_amount']);
    $data['mtd_total_payment_amount'] = ($data['mtd_pa_total_payment_amount'] + $data['mtd_rsa_total_payment_amount'] + $data['mtd_dc_total_payment_amount'] + $data['mtd_tvs_total_payment_amount']);
    $data['ytd_total_payment_amount'] = ($data['ytd_pa_total_payment_amount'] + $data['ytd_rsa_total_payment_amount'] + $data['ytd_dc_total_payment_amount'] + $data['ytd_tvs_total_payment_amount']);
*/
    
    // END Purchase Cost TVS Commission
   
    
    
    
    
    
    
    //echo '<pre>';    print_r($layer_one_details);die('hello');
    $data['main_contain'] = 'admin/report/target_achivement';
/*    print "<pre>";
    print_r($data['mtd_silver_purchase_total_amount']);
    print "<pre>";
    exit;*/
    $this->load->view('admin/includes/template', $data);
}
public function layerTwo(){
    $data['layer_two_details'] = $layer_two_details =  $this->Home_Model->getLayerTwoDetails();

    $data['giib_td_kotak_platinum_margin'] = ($layer_two_details['td_kotak_platinum_policy_count'] * 210 * 0.15);
    $data['giib_td_il_platinum_margin'] = ($layer_two_details['td_il_platinum_policy_count'] * 210 * 0.05);
    $data['giib_td_tata_platinum_margin'] = ($layer_two_details['td_tata_platinum_policy_count'] * 210 * 0.075);
    $data['giib_td_bagi_platinum_margin'] = ($layer_two_details['td_bagi_platinum_policy_count'] * 210 * 0.10);
    $data['giib_td_platinum_policy_margin'] = ($data['giib_td_kotak_platinum_margin'] + $data['giib_td_il_platinum_margin'] + $data['giib_td_tata_platinum_margin'] + $data['giib_td_bagi_platinum_margin']);
    
    $data['giib_td_kotak_sapphire_margin'] = ($layer_two_details['td_kotak_sapphire_policy_count'] * 210 * 0.15);
    $data['giib_td_il_sapphire_margin'] = ($layer_two_details['td_il_sapphire_policy_count'] * 210 * 0.05);
    $data['giib_td_tata_sapphire_margin'] = ($layer_two_details['td_tata_sapphire_policy_count'] * 210 * 0.075);
    $data['giib_td_bagi_sapphire_margin'] = ($layer_two_details['td_bagi_sapphire_policy_count'] * 210 * 0.10);
    $data['giib_td_sapphire_policy_margin'] = ($data['giib_td_kotak_sapphire_margin'] + $data['giib_td_il_sapphire_margin'] + $data['giib_td_tata_sapphire_margin'] + $data['giib_td_bagi_sapphire_margin']);
    
    $data['giib_td_kotak_gold_margin'] = ($layer_two_details['td_kotak_gold_policy_count'] * 140 * 0.15);
    $data['giib_td_il_gold_margin'] = ($layer_two_details['td_il_gold_policy_count'] * 140 * 0.05);
    $data['giib_td_tata_gold_margin'] = ($layer_two_details['td_tata_gold_policy_count'] * 140 * 0.075);
    $data['giib_td_bagi_gold_margin'] = ($layer_two_details['td_bagi_gold_policy_count'] * 140 * 0.10);
    $data['giib_td_gold_policy_margin'] = ($data['giib_td_kotak_gold_margin'] + $data['giib_td_il_gold_margin'] + $data['giib_td_tata_gold_margin'] + $data['giib_td_bagi_gold_margin']);
    
    $data['giib_td_kotak_silver_margin'] = ($layer_two_details['td_kotak_silver_policy_count'] * 70 * 0.15);
    $data['giib_td_il_silver_margin'] = ($layer_two_details['td_il_silver_policy_count'] * 70 * 0.05);
    $data['giib_td_tata_silver_margin'] = ($layer_two_details['td_tata_silver_policy_count'] * 70 * 0.075);
    $data['giib_td_bagi_silver_margin'] = ($layer_two_details['td_bagi_silver_policy_count'] * 70 * 0.10);
    $data['giib_td_silver_policy_margin'] = ($data['giib_td_kotak_silver_margin'] + $data['giib_td_il_silver_margin'] + $data['giib_td_tata_silver_margin'] + $data['giib_td_bagi_silver_margin']);
    
    $data['giib_mtd_kotak_platinum_margin'] = ($layer_two_details['mtd_kotak_platinum_policy_count'] * 210 * 0.15);
    $data['giib_mtd_il_platinum_margin'] = ($layer_two_details['mtd_il_platinum_policy_count'] * 210 * 0.05);
    $data['giib_mtd_tata_platinum_margin'] = ($layer_two_details['mtd_tata_platinum_policy_count'] * 210 * 0.075);
    $data['giib_mtd_bagi_platinum_margin'] = ($layer_two_details['mtd_bagi_platinum_policy_count'] * 210 * 0.10);
    $data['giib_mtd_platinum_policy_margin'] = ($data['giib_mtd_kotak_platinum_margin'] + $data['giib_mtd_il_platinum_margin'] + $data['giib_mtd_tata_platinum_margin'] + $data['giib_mtd_bagi_platinum_margin']);
    
    $data['giib_mtd_kotak_sapphire_margin'] = ($layer_two_details['mtd_kotak_sapphire_policy_count'] * 210 * 0.15);
    $data['giib_mtd_il_sapphire_margin'] = ($layer_two_details['mtd_il_sapphire_policy_count'] * 210 * 0.05);
    $data['giib_mtd_tata_sapphire_margin'] = ($layer_two_details['mtd_tata_sapphire_policy_count'] * 210 * 0.075);
    $data['giib_mtd_bagi_sapphire_margin'] = ($layer_two_details['mtd_bagi_sapphire_policy_count'] * 210 * 0.10);
    $data['giib_mtd_sapphire_policy_margin'] = ($data['giib_mtd_kotak_sapphire_margin'] + $data['giib_mtd_il_sapphire_margin'] + $data['giib_mtd_tata_sapphire_margin'] + $data['giib_mtd_bagi_sapphire_margin']);
    
    $data['giib_mtd_kotak_gold_margin'] = ($layer_two_details['mtd_kotak_gold_policy_count'] * 140 * 0.15);
    $data['giib_mtd_il_gold_margin'] = ($layer_two_details['mtd_il_gold_policy_count'] * 140 * 0.05);
    $data['giib_mtd_tata_gold_margin'] = ($layer_two_details['mtd_tata_gold_policy_count'] * 140 * 0.075);
    $data['giib_mtd_bagi_gold_margin'] = ($layer_two_details['mtd_bagi_gold_policy_count'] * 140 * 0.10);
    $data['giib_mtd_gold_policy_margin'] = ($data['giib_mtd_kotak_gold_margin'] + $data['giib_mtd_il_gold_margin'] + $data['giib_mtd_tata_gold_margin'] + $data['giib_mtd_bagi_gold_margin']);
    
    $data['giib_mtd_kotak_silver_margin'] = ($layer_two_details['mtd_kotak_silver_policy_count'] * 70 * 0.15);
    $data['giib_mtd_il_silver_margin'] = ($layer_two_details['mtd_il_silver_policy_count'] * 70 * 0.05);
    $data['giib_mtd_tata_silver_margin'] = ($layer_two_details['mtd_tata_silver_policy_count'] * 70 * 0.075);
    $data['giib_mtd_bagi_silver_margin'] = ($layer_two_details['mtd_bagi_silver_policy_count'] * 70 * 0.10);
    $data['giib_mtd_silver_policy_margin'] = ($data['giib_mtd_kotak_silver_margin'] + $data['giib_mtd_il_silver_margin'] + $data['giib_mtd_tata_silver_margin'] + $data['giib_mtd_bagi_silver_margin']);
   
    $data['giib_ytd_kotak_platinum_margin'] = ($layer_two_details['ytd_kotak_platinum_policy_count'] * 210 * 0.15);
    $data['giib_ytd_il_platinum_margin'] = ($layer_two_details['ytd_il_platinum_policy_count'] * 210 * 0.05);
    $data['giib_ytd_tata_platinum_margin'] = ($layer_two_details['ytd_tata_platinum_policy_count'] * 210 * 0.075);
    $data['giib_ytd_bagi_platinum_margin'] = ($layer_two_details['ytd_bagi_platinum_policy_count'] * 210 * 0.10);
    $data['giib_ytd_platinum_policy_margin'] = ($data['giib_ytd_kotak_platinum_margin'] + $data['giib_ytd_il_platinum_margin'] + $data['giib_ytd_tata_platinum_margin'] + $data['giib_ytd_bagi_platinum_margin']);
    
    $data['giib_ytd_kotak_sapphire_margin'] = ($layer_two_details['ytd_kotak_sapphire_policy_count'] * 210 * 0.15);
    $data['giib_ytd_il_sapphire_margin'] = ($layer_two_details['ytd_il_sapphire_policy_count'] * 210 * 0.05);
    $data['giib_ytd_tata_sapphire_margin'] = ($layer_two_details['ytd_tata_sapphire_policy_count'] * 210 * 0.075);
    $data['giib_ytd_bagi_sapphire_margin'] = ($layer_two_details['ytd_bagi_sapphire_policy_count'] * 210 * 0.10);
    $data['giib_ytd_sapphire_policy_margin'] = ($data['giib_ytd_kotak_sapphire_margin'] + $data['giib_ytd_il_sapphire_margin'] + $data['giib_ytd_tata_sapphire_margin'] + $data['giib_ytd_bagi_sapphire_margin']);
    
    $data['giib_ytd_kotak_gold_margin'] = ($layer_two_details['ytd_kotak_gold_policy_count'] * 140 * 0.15);
    $data['giib_ytd_il_gold_margin'] = ($layer_two_details['ytd_il_gold_policy_count'] * 140 * 0.05);
    $data['giib_ytd_tata_gold_margin'] = ($layer_two_details['ytd_tata_gold_policy_count'] * 140 * 0.075);
    $data['giib_ytd_bagi_gold_margin'] = ($layer_two_details['ytd_bagi_gold_policy_count'] * 140 * 0.10);
    $data['giib_ytd_gold_policy_margin'] = ($data['giib_ytd_kotak_gold_margin'] + $data['giib_ytd_il_gold_margin'] + $data['giib_ytd_tata_gold_margin'] + $data['giib_ytd_bagi_gold_margin']);
    
    $data['giib_ytd_kotak_silver_margin'] = ($layer_two_details['ytd_kotak_silver_policy_count'] * 70 * 0.15);
    $data['giib_ytd_il_silver_margin'] = ($layer_two_details['ytd_il_silver_policy_count'] * 70 * 0.05);
    $data['giib_ytd_tata_silver_margin'] = ($layer_two_details['ytd_tata_silver_policy_count'] * 70 * 0.075);
    $data['giib_ytd_bagi_silver_margin'] = ($layer_two_details['ytd_bagi_silver_policy_count'] * 70 * 0.10);
    $data['giib_ytd_silver_policy_margin'] = ($data['giib_ytd_kotak_silver_margin'] + $data['giib_ytd_il_silver_margin'] + $data['giib_ytd_tata_silver_margin'] + $data['giib_ytd_bagi_silver_margin']);
    
    //END MARGIN -GIIB
    
    
    //Sale Price
    /*****************td data*******************/
    $data['td_platinum_sell_value'] = $layer_two_details['td_platinum_policy_count'] * 373.73;
    $data['td_platinum_gst'] = $layer_two_details['td_platinum_policy_count'] * 67.27;
    $data['td_platinum_total_amount'] = $data['td_platinum_sell_value'] + $data['td_platinum_gst'];
    
    $data['td_sapphire_sell_value'] = $layer_two_details['td_sapphire_policy_count'] * 399.15;
    $data['td_sapphire_gst'] = $layer_two_details['td_sapphire_policy_count'] * 71.85;
    $data['td_sapphire_total_amount'] = $data['td_sapphire_sell_value'] + $data['td_sapphire_gst'];
    
    $data['td_gold_sell_value'] = $layer_two_details['td_gold_policy_count'] * 296.61;
    $data['td_gold_gst'] = $layer_two_details['td_gold_policy_count'] * 53.39;
    $data['td_gold_total_amount'] = $data['td_gold_sell_value'] + $data['td_gold_gst'];
    
    $data['td_silver_sell_value'] = $layer_two_details['td_silver_policy_count'] * 212.71;
    $data['td_silver_gst'] = $layer_two_details['td_silver_policy_count'] * 38.29;
    $data['td_silver_total_amount'] = $data['td_silver_sell_value'] + $data['td_silver_gst'];
    /*********************TD DATA***************************/
    
    /*********************MTD DATA***************************/
    $data['mtd_platinum_sell_value'] = $layer_two_details['mtd_platinum_policy_count'] * 373.73;
    $data['mtd_platinum_gst'] = $layer_two_details['mtd_platinum_policy_count'] * 67.27;
    $data['mtd_platinum_total_amount'] = $data['mtd_platinum_sell_value'] + $data['mtd_platinum_gst'];
    
    $data['mtd_sapphire_sell_value'] = $layer_two_details['mtd_sapphire_policy_count'] * 399.15;
    $data['mtd_sapphire_gst'] = $layer_two_details['mtd_sapphire_policy_count'] * 71.85;
    $data['mtd_sapphire_total_amount'] = $data['mtd_sapphire_sell_value'] + $data['mtd_sapphire_gst'];
    
    $data['mtd_gold_sell_value'] = $layer_two_details['mtd_gold_policy_count'] * 296.61;
    $data['mtd_gold_gst'] = $layer_two_details['mtd_gold_policy_count'] * 53.39;
    $data['mtd_gold_total_amount'] = $data['mtd_gold_sell_value'] + $data['mtd_gold_gst'];
    
    $data['mtd_silver_sell_value'] = $layer_two_details['mtd_silver_policy_count'] * 212.71;
    $data['mtd_silver_gst'] = $layer_two_details['mtd_silver_policy_count'] * 38.29;
    $data['mtd_silver_total_amount'] = $data['mtd_silver_sell_value'] + $data['mtd_silver_gst'];
    /*********************MTD DATA***************************/
    
    /*********************YTD DATA***************************/
    $data['ytd_platinum_sell_value'] = $layer_two_details['ytd_platinum_policy_count'] * 373.73;
    $data['ytd_platinum_gst'] = $layer_two_details['ytd_platinum_policy_count'] * 67.27;
    $data['ytd_platinum_total_amount'] = $data['ytd_platinum_sell_value'] + $data['ytd_platinum_gst'];
    
    $data['ytd_sapphire_sell_value'] = $layer_two_details['ytd_sapphire_policy_count'] * 399.15;
    $data['ytd_sapphire_gst'] = $layer_two_details['ytd_sapphire_policy_count'] * 71.85;
    $data['ytd_sapphire_total_amount'] = $data['ytd_sapphire_sell_value'] + $data['ytd_sapphire_gst'];
    
    $data['ytd_gold_sell_value'] = $layer_two_details['ytd_gold_policy_count'] * 296.61;
    $data['ytd_gold_gst'] = $layer_two_details['ytd_gold_policy_count'] * 53.39;
    $data['ytd_gold_total_amount'] = $data['ytd_gold_sell_value'] + $data['ytd_gold_gst'];
    
    $data['ytd_silver_sell_value'] = $layer_two_details['ytd_silver_policy_count'] * 212.71;
    $data['ytd_silver_gst'] = $layer_two_details['ytd_silver_policy_count'] * 38.29;
    $data['ytd_silver_total_amount'] = $data['ytd_silver_sell_value'] + $data['ytd_silver_gst'];
    /*********************YTD DATA***************************/
     //END Sale Price
    
     
     
    //Purchase Cost PA
     /*****************td data*******************/
    $data['td_platinum_purchase_cost'] = $layer_two_details['td_platinum_policy_count'] * 210;
    $data['td_platinum_purchase_tds'] = $data['td_platinum_purchase_cost'] * 0.02;
    $data['td_platinum_purchase_gst'] = $layer_two_details['td_platinum_policy_count'] * 37.80;
    $data['td_platinum_purchase_total_amount'] = ($data['td_platinum_purchase_cost'] + $data['td_platinum_purchase_gst'] - $data['td_platinum_purchase_tds']);
    
    $data['td_sapphire_purchase_cost'] = $layer_two_details['td_sapphire_policy_count'] * 210;
    $data['td_sapphire_purchase_tds'] = $data['td_sapphire_purchase_cost'] * 0.02;
    $data['td_sapphire_purchase_gst'] = $layer_two_details['td_sapphire_policy_count'] * 37.80;
    $data['td_sapphire_purchase_total_amount'] = ($data['td_sapphire_purchase_cost'] + $data['td_sapphire_purchase_gst'] - $data['td_sapphire_purchase_tds']);
    
    $data['td_gold_purchase_cost'] = $layer_two_details['td_gold_policy_count'] * 140;
    $data['td_gold_purchase_tds'] = $data['td_gold_purchase_cost'] * 0.02;
    $data['td_gold_purchase_gst'] = $layer_two_details['td_gold_policy_count'] * 25.2;
    $data['td_gold_purchase_total_amount'] = ($data['td_gold_purchase_cost'] + $data['td_gold_purchase_gst'] - $data['td_gold_purchase_tds']);
    
    $data['td_silver_purchase_cost'] = $layer_two_details['td_silver_policy_count'] * 70;
    $data['td_silver_purchase_tds'] = $data['td_silver_purchase_cost'] * 0.02;
    $data['td_silver_purchase_gst'] = $layer_two_details['td_silver_policy_count'] * 12.6;
    $data['td_silver_purchase_total_amount'] = ($data['td_silver_purchase_cost'] + $data['td_silver_purchase_gst'] - $data['td_silver_purchase_tds']);
    /*********************TD DATA***************************/
//        echo '<pre>';
//    print_r($layer_two_details['td_gold_policy_count']);
//    print_r($data['td_gold_purchase_tds']);
//    print_r($data['td_gold_purchase_gst']);
//    echo '<pre>';
// die('hello moto');
    /*********************MTD DATA***************************/
    $data['mtd_platinum_purchase_cost'] = $layer_two_details['mtd_platinum_policy_count'] * 210;
    $data['mtd_platinum_purchase_tds'] = $data['mtd_platinum_purchase_cost'] * 0.02;
    $data['mtd_platinum_purchase_gst'] = $layer_two_details['mtd_platinum_policy_count'] * 37.80;
    $data['mtd_platinum_purchase_total_amount'] = ($data['mtd_platinum_purchase_cost'] + $data['mtd_platinum_purchase_gst'] - $data['mtd_platinum_purchase_tds']);
    
    $data['mtd_sapphire_purchase_cost'] = $layer_two_details['mtd_sapphire_policy_count'] * 210;
    $data['mtd_sapphire_purchase_tds'] = $data['mtd_sapphire_purchase_cost'] * 0.02;
    $data['mtd_sapphire_purchase_gst'] = $layer_two_details['mtd_sapphire_policy_count'] * 37.80;
    $data['mtd_sapphire_purchase_total_amount'] = ($data['mtd_sapphire_purchase_cost'] + $data['mtd_sapphire_purchase_gst'] - $data['mtd_sapphire_purchase_tds']);
    
    $data['mtd_gold_purchase_cost'] = $layer_two_details['mtd_gold_policy_count'] * 140;
    $data['mtd_gold_purchase_tds'] = $data['mtd_gold_purchase_cost'] * 0.02;
    $data['mtd_gold_purchase_gst'] = $layer_two_details['mtd_gold_policy_count'] * 25.2;
    $data['mtd_gold_purchase_total_amount'] = ($data['mtd_gold_purchase_cost'] + $data['mtd_gold_purchase_gst'] - $data['mtd_gold_purchase_tds']);

    $data['mtd_silver_purchase_cost'] = $layer_two_details['mtd_silver_policy_count'] * 70;
    $data['mtd_silver_purchase_tds'] = $data['mtd_silver_purchase_cost'] * 0.02;
    $data['mtd_silver_purchase_gst'] = $layer_two_details['mtd_silver_policy_count'] * 12.6;
    $data['mtd_silver_purchase_total_amount'] = ($data['mtd_silver_purchase_cost'] + $data['mtd_silver_purchase_gst'] - $data['mtd_silver_purchase_tds']);
    /*********************MTD DATA***************************/
    
    /*********************YTD DATA***************************/
    $data['ytd_platinum_purchase_cost'] = $layer_two_details['ytd_platinum_policy_count'] * 210;
    $data['ytd_platinum_purchase_tds'] = $data['ytd_platinum_purchase_cost'] * 0.02;
    $data['ytd_platinum_purchase_gst'] = $layer_two_details['ytd_platinum_policy_count'] * 37.80;
    $data['ytd_platinum_purchase_total_amount'] = ($data['ytd_platinum_purchase_cost'] + $data['ytd_platinum_purchase_gst'] - $data['ytd_platinum_purchase_tds']);
    
    $data['ytd_sapphire_purchase_cost'] = $layer_two_details['ytd_sapphire_policy_count'] * 210;
    $data['ytd_sapphire_purchase_tds'] = $data['ytd_sapphire_purchase_cost'] * 0.02;
    $data['ytd_sapphire_purchase_gst'] = $layer_two_details['ytd_sapphire_policy_count'] * 37.80;
    $data['ytd_sapphire_purchase_total_amount'] = ($data['ytd_sapphire_purchase_cost'] + $data['ytd_sapphire_purchase_gst'] - $data['ytd_sapphire_purchase_tds']);
    
    $data['ytd_gold_purchase_cost'] = $layer_two_details['ytd_gold_policy_count'] * 140;
    $data['ytd_gold_purchase_tds'] = $data['ytd_gold_purchase_cost'] * 0.02;
    $data['ytd_gold_purchase_gst'] = $layer_two_details['ytd_gold_policy_count'] * 25.2;
    $data['ytd_gold_purchase_total_amount'] = ($data['ytd_gold_purchase_cost'] + $data['ytd_gold_purchase_gst'] - $data['ytd_gold_purchase_tds']);
    
    $data['ytd_silver_purchase_cost'] = $layer_two_details['ytd_silver_policy_count'] * 70;
    $data['ytd_silver_purchase_tds'] = $data['ytd_silver_purchase_cost'] * 0.02;
    $data['ytd_silver_purchase_gst'] = $layer_two_details['ytd_silver_policy_count'] * 12.6;
    $data['ytd_silver_purchase_total_amount'] = ($data['ytd_silver_purchase_cost'] + $data['ytd_silver_purchase_gst'] - $data['ytd_silver_purchase_tds']);
    /*********************YTD DATA***************************/
    
    //END Purchase Cost PA
    
    
    // Purchase Cost RSA
     /*****************td data*******************/
    $data['rsa_td_platinum_purchase_cost'] = $layer_two_details['td_platinum_policy_count'] * 16;
    $data['rsa_td_platinum_purchase_tds'] = $data['rsa_td_platinum_purchase_cost'] * 0.02;
    $data['rsa_td_platinum_purchase_gst'] = $layer_two_details['td_platinum_policy_count'] * 2.88;
    $data['rsa_td_platinum_purchase_total_amount'] = ($data['rsa_td_platinum_purchase_cost'] + $data['rsa_td_platinum_purchase_gst'] - $data['rsa_td_platinum_purchase_tds']);
    
    $data['rsa_td_sapphire_purchase_cost'] = $layer_two_details['td_sapphire_policy_count'] * 32;
    $data['rsa_td_sapphire_purchase_tds'] = $data['rsa_td_sapphire_purchase_cost'] * 0.02;
    $data['rsa_td_sapphire_purchase_gst'] = $layer_two_details['td_sapphire_policy_count'] * 5.76;
    $data['rsa_td_sapphire_purchase_total_amount'] = ($data['rsa_td_sapphire_purchase_cost'] + $data['rsa_td_sapphire_purchase_gst'] - $data['rsa_td_sapphire_purchase_tds']);
    
    $data['rsa_td_gold_purchase_cost'] = $layer_two_details['td_gold_policy_count'] * 16;
    $data['rsa_td_gold_purchase_tds'] = $data['rsa_td_gold_purchase_cost'] * 0.02;
    $data['rsa_td_gold_purchase_gst'] = $layer_two_details['td_gold_policy_count'] * 2.88;
    $data['rsa_td_gold_purchase_total_amount'] = ($data['rsa_td_gold_purchase_cost'] + $data['rsa_td_gold_purchase_gst'] - $data['rsa_td_gold_purchase_tds']);
    
    $data['rsa_td_silver_purchase_cost'] = $layer_two_details['td_silver_policy_count'] * 16;
    $data['rsa_td_silver_purchase_tds'] = $data['rsa_td_silver_purchase_cost'] * 0.02;
    $data['rsa_td_silver_purchase_gst'] = $layer_two_details['td_silver_policy_count'] * 2.88;
    $data['rsa_td_silver_purchase_total_amount'] = ($data['rsa_td_silver_purchase_cost'] + $data['rsa_td_silver_purchase_gst'] - $data['rsa_td_silver_purchase_tds']);
    /*********************TD DATA***************************/
    
    /*********************MTD DATA***************************/
    $data['rsa_mtd_platinum_purchase_cost'] = $layer_two_details['mtd_platinum_policy_count'] * 16;
    $data['rsa_mtd_platinum_purchase_tds'] = $data['rsa_mtd_platinum_purchase_cost'] * 0.02;
    $data['rsa_mtd_platinum_purchase_gst'] = $layer_two_details['mtd_platinum_policy_count'] * 2.88;
    $data['rsa_mtd_platinum_purchase_total_amount'] = ($data['rsa_mtd_platinum_purchase_cost'] + $data['rsa_mtd_platinum_purchase_gst'] - $data['rsa_mtd_platinum_purchase_tds']);
    
    $data['rsa_mtd_sapphire_purchase_cost'] = $layer_two_details['mtd_sapphire_policy_count'] * 32;
    $data['rsa_mtd_sapphire_purchase_tds'] = $data['rsa_mtd_sapphire_purchase_cost'] * 0.02;
    $data['rsa_mtd_sapphire_purchase_gst'] = $layer_two_details['mtd_sapphire_policy_count'] * 5.76;
    $data['rsa_mtd_sapphire_purchase_total_amount'] = ($data['rsa_mtd_sapphire_purchase_cost'] + $data['rsa_mtd_sapphire_purchase_gst'] - $data['rsa_mtd_sapphire_purchase_tds']);
    
    $data['rsa_mtd_gold_purchase_cost'] = $layer_two_details['mtd_gold_policy_count'] * 16;
    $data['rsa_mtd_gold_purchase_tds'] = $data['rsa_mtd_gold_purchase_cost'] * 0.02;
    $data['rsa_mtd_gold_purchase_gst'] = $layer_two_details['mtd_gold_policy_count'] * 2.88;
    $data['rsa_mtd_gold_purchase_total_amount'] = ($data['rsa_mtd_gold_purchase_cost'] + $data['rsa_mtd_gold_purchase_gst'] - $data['rsa_mtd_gold_purchase_tds']);
    
    $data['rsa_mtd_silver_purchase_cost'] = $layer_two_details['mtd_silver_policy_count'] * 16;
    $data['rsa_mtd_silver_purchase_tds'] = $data['rsa_mtd_silver_purchase_cost'] * 0.02;
    $data['rsa_mtd_silver_purchase_gst'] = $layer_two_details['mtd_silver_policy_count'] * 2.88;
    $data['rsa_mtd_silver_purchase_total_amount'] = ($data['rsa_mtd_silver_purchase_cost'] + $data['rsa_mtd_silver_purchase_gst'] - $data['rsa_mtd_silver_purchase_tds']);
    /*********************MTD DATA***************************/
    
    /*********************YTD DATA***************************/
    $data['rsa_ytd_platinum_purchase_cost'] = $layer_two_details['ytd_platinum_policy_count'] * 16;
    $data['rsa_ytd_platinum_purchase_tds'] = $data['rsa_ytd_platinum_purchase_cost'] * 0.02;
    $data['rsa_ytd_platinum_purchase_gst'] = $layer_two_details['ytd_platinum_policy_count'] * 2.88;
    $data['rsa_ytd_platinum_purchase_total_amount'] = ($data['rsa_ytd_platinum_purchase_cost'] + $data['rsa_ytd_platinum_purchase_gst'] - $data['rsa_ytd_platinum_purchase_tds']);
    
    $data['rsa_ytd_sapphire_purchase_cost'] = $layer_two_details['ytd_sapphire_policy_count'] * 32;
    $data['rsa_ytd_sapphire_purchase_tds'] = $data['rsa_ytd_sapphire_purchase_cost'] * 0.02;
    $data['rsa_ytd_sapphire_purchase_gst'] = $layer_two_details['ytd_sapphire_policy_count'] * 5.76;
    $data['rsa_ytd_sapphire_purchase_total_amount'] = ($data['rsa_ytd_sapphire_purchase_cost'] + $data['rsa_ytd_sapphire_purchase_gst'] - $data['rsa_ytd_sapphire_purchase_tds']);
    
    $data['rsa_ytd_gold_purchase_cost'] = $layer_two_details['ytd_gold_policy_count'] * 16;
    $data['rsa_ytd_gold_purchase_tds'] = $data['rsa_ytd_gold_purchase_cost'] * 0.02;
    $data['rsa_ytd_gold_purchase_gst'] = $layer_two_details['ytd_gold_policy_count'] * 2.88;
    $data['rsa_ytd_gold_purchase_total_amount'] = ($data['rsa_ytd_gold_purchase_cost'] + $data['rsa_ytd_gold_purchase_gst'] - $data['rsa_ytd_gold_purchase_tds']);
    
    $data['rsa_ytd_silver_purchase_cost'] = $layer_two_details['ytd_silver_policy_count'] * 16;
    $data['rsa_ytd_silver_purchase_tds'] = $data['rsa_ytd_silver_purchase_cost'] * 0.02;
    $data['rsa_ytd_silver_purchase_gst'] = $layer_two_details['ytd_silver_policy_count'] * 2.88;
    $data['rsa_ytd_silver_purchase_total_amount'] = ($data['rsa_ytd_silver_purchase_cost'] + $data['rsa_ytd_silver_purchase_gst'] - $data['rsa_ytd_silver_purchase_tds']);
    /*********************YTD DATA***************************/
    
    // END Purchase Cost RSA
    
    // Purchase Cost Dealer Commission
     /*****************td data*******************/
    $data['dc_td_platinum_purchase_cost'] = $layer_two_details['td_platinum_policy_count'] * 75;
    $data['dc_td_platinum_purchase_tds'] = $layer_two_details['td_platinum_policy_count'] * 3.75;
    $data['dc_td_platinum_purchase_gst'] = $layer_two_details['td_platinum_policy_count'] * 13.50;
    $data['dc_td_platinum_purchase_total_amount'] = ($data['dc_td_platinum_purchase_cost'] + $data['dc_td_platinum_purchase_gst'] - $data['dc_td_platinum_purchase_tds']);
    
    $data['dc_td_sapphire_purchase_cost'] = $layer_two_details['td_sapphire_policy_count'] * 80;
    $data['dc_td_sapphire_purchase_tds'] = $layer_two_details['td_sapphire_policy_count'] * 4;
    $data['dc_td_sapphire_purchase_gst'] = $layer_two_details['td_sapphire_policy_count'] * 14.4;
    $data['dc_td_sapphire_purchase_total_amount'] = ($data['dc_td_sapphire_purchase_cost'] + $data['dc_td_sapphire_purchase_gst'] - $data['dc_td_sapphire_purchase_tds']);
    
    $data['dc_td_gold_purchase_cost'] = $layer_two_details['td_gold_policy_count'] * 60;
    $data['dc_td_gold_purchase_tds'] = $layer_two_details['td_gold_policy_count'] * 3;
    $data['dc_td_gold_purchase_gst'] = $layer_two_details['td_gold_policy_count'] * 10.8;
    $data['dc_td_gold_purchase_total_amount'] = ($data['dc_td_gold_purchase_cost'] + $data['dc_td_gold_purchase_gst'] - $data['dc_td_gold_purchase_tds']);
    
    $data['dc_td_silver_purchase_cost'] = $layer_two_details['td_silver_policy_count'] * 50;
    $data['dc_td_silver_purchase_tds'] = $layer_two_details['td_silver_policy_count'] * 2.5;
    $data['dc_td_silver_purchase_gst'] = $layer_two_details['td_silver_policy_count'] * 9;
    $data['dc_td_silver_purchase_total_amount'] = ($data['dc_td_silver_purchase_cost'] + $data['dc_td_silver_purchase_gst'] - $data['dc_td_silver_purchase_tds']);
    /*********************TD DATA***************************/
    
    /*********************MTD DATA***************************/
    $data['dc_mtd_platinum_purchase_cost'] = $layer_two_details['mtd_platinum_policy_count'] * 75;
    $data['dc_mtd_platinum_purchase_tds'] = $layer_two_details['mtd_platinum_policy_count'] * 3.75;
    $data['dc_mtd_platinum_purchase_gst'] = $layer_two_details['mtd_platinum_policy_count'] * 13.50;
    $data['dc_mtd_platinum_purchase_total_amount'] = ($data['dc_mtd_platinum_purchase_cost'] + $data['dc_mtd_platinum_purchase_gst'] - $data['dc_mtd_platinum_purchase_tds']);
    
    $data['dc_mtd_sapphire_purchase_cost'] = $layer_two_details['mtd_sapphire_policy_count'] * 80;
    $data['dc_mtd_sapphire_purchase_tds'] = $layer_two_details['mtd_sapphire_policy_count'] * 4;
    $data['dc_mtd_sapphire_purchase_gst'] = $layer_two_details['mtd_sapphire_policy_count'] * 14.4;
    $data['dc_mtd_sapphire_purchase_total_amount'] = ($data['dc_mtd_sapphire_purchase_cost'] + $data['dc_mtd_sapphire_purchase_gst'] - $data['dc_mtd_sapphire_purchase_tds']);
    
    $data['dc_mtd_gold_purchase_cost'] = $layer_two_details['mtd_gold_policy_count'] * 60;
    $data['dc_mtd_gold_purchase_tds'] = $layer_two_details['mtd_gold_policy_count'] * 3;
    $data['dc_mtd_gold_purchase_gst'] = $layer_two_details['mtd_gold_policy_count'] * 10.8;
    $data['dc_mtd_gold_purchase_total_amount'] = ($data['dc_mtd_gold_purchase_cost'] + $data['dc_mtd_gold_purchase_gst'] - $data['dc_mtd_gold_purchase_tds']);
    
    $data['dc_mtd_silver_purchase_cost'] = $layer_two_details['mtd_silver_policy_count'] * 50;
    $data['dc_mtd_silver_purchase_tds'] = $layer_two_details['mtd_silver_policy_count'] * 2.5;
    $data['dc_mtd_silver_purchase_gst'] = $layer_two_details['mtd_silver_policy_count'] * 9;
    $data['dc_mtd_silver_purchase_total_amount'] = ($data['dc_mtd_silver_purchase_cost'] + $data['dc_mtd_silver_purchase_gst'] - $data['dc_mtd_silver_purchase_tds']);
    /*********************MTD DATA***************************/
    
    /*********************YTD DATA***************************/
    $data['dc_ytd_platinum_purchase_cost'] = $layer_two_details['ytd_platinum_policy_count'] * 75;
    $data['dc_ytd_platinum_purchase_tds'] = $layer_two_details['ytd_platinum_policy_count'] * 3.75;
    $data['dc_ytd_platinum_purchase_gst'] = $layer_two_details['ytd_platinum_policy_count'] * 13.50;
    $data['dc_ytd_platinum_purchase_total_amount'] = ($data['dc_ytd_platinum_purchase_cost'] + $data['dc_ytd_platinum_purchase_gst'] - $data['dc_ytd_platinum_purchase_tds']);
    
    $data['dc_ytd_sapphire_purchase_cost'] = $layer_two_details['ytd_sapphire_policy_count'] * 80;
    $data['dc_ytd_sapphire_purchase_tds'] = $layer_two_details['ytd_sapphire_policy_count'] * 4;
    $data['dc_ytd_sapphire_purchase_gst'] = $layer_two_details['ytd_sapphire_policy_count'] * 14.4;
    $data['dc_ytd_sapphire_purchase_total_amount'] = ($data['dc_ytd_sapphire_purchase_cost'] + $data['dc_ytd_sapphire_purchase_gst'] - $data['dc_ytd_sapphire_purchase_tds']);
    
    $data['dc_ytd_gold_purchase_cost'] = $layer_two_details['ytd_gold_policy_count'] * 60;
    $data['dc_ytd_gold_purchase_tds'] = $layer_two_details['ytd_gold_policy_count'] * 3;
    $data['dc_ytd_gold_purchase_gst'] = $layer_two_details['ytd_gold_policy_count'] * 10.8;
    $data['dc_ytd_gold_purchase_total_amount'] = ($data['dc_ytd_gold_purchase_cost'] + $data['dc_ytd_gold_purchase_gst'] - $data['dc_ytd_gold_purchase_tds']);
    
    $data['dc_ytd_silver_purchase_cost'] = $layer_two_details['ytd_silver_policy_count'] * 50;
    $data['dc_ytd_silver_purchase_tds'] = $layer_two_details['ytd_silver_policy_count'] * 2.5;
    $data['dc_ytd_silver_purchase_gst'] = $layer_two_details['ytd_silver_policy_count'] * 9;
    $data['dc_ytd_silver_purchase_total_amount'] = ($data['dc_ytd_silver_purchase_cost'] + $data['dc_ytd_silver_purchase_gst'] - $data['dc_ytd_silver_purchase_tds']);
    /*********************YTD DATA***************************/
    
    // END Purchase Cost Dealer Commission
    
    // Purchase Cost TVS Commission
     /*****************td data*******************/
    $data['tvs_td_platinum_purchase_cost'] = $layer_two_details['td_platinum_policy_count'] * 57;
    $data['tvs_td_platinum_purchase_tds'] = $layer_two_details['td_platinum_policy_count'] * 5.7;
    $data['tvs_td_platinum_purchase_gst'] = $layer_two_details['td_platinum_policy_count'] * 8.73;
    $data['tvs_td_platinum_purchase_total_amount'] = ($data['tvs_td_platinum_purchase_cost'] + $data['tvs_td_platinum_purchase_gst'] - $data['tvs_td_platinum_purchase_tds']);
    
    $data['tvs_td_sapphire_purchase_cost'] = $layer_two_details['td_sapphire_policy_count'] * 54;
    $data['tvs_td_sapphire_purchase_tds'] = $layer_two_details['td_sapphire_policy_count'] * 5.4;
    $data['tvs_td_sapphire_purchase_gst'] = $layer_two_details['td_sapphire_policy_count'] * 9.72;
    $data['tvs_td_sapphire_purchase_total_amount'] = ($data['tvs_td_sapphire_purchase_cost'] + $data['tvs_td_sapphire_purchase_gst'] - $data['tvs_td_sapphire_purchase_tds']);
    
    $data['tvs_td_gold_purchase_cost'] = $layer_two_details['td_gold_policy_count'] * 57;
    $data['tvs_td_gold_purchase_tds'] = $layer_two_details['td_gold_policy_count'] * 5.7;
    $data['tvs_td_gold_purchase_gst'] = $layer_two_details['td_gold_policy_count'] * 10.26;
    $data['tvs_td_gold_purchase_total_amount'] = ($data['tvs_td_gold_purchase_cost'] + $data['tvs_td_gold_purchase_gst'] - $data['tvs_td_gold_purchase_tds']);
    
    $data['tvs_td_silver_purchase_cost'] = $layer_two_details['td_silver_policy_count'] * 53.5;
    $data['tvs_td_silver_purchase_tds'] = $layer_two_details['td_silver_policy_count'] * 5.35;
    $data['tvs_td_silver_purchase_gst'] = $layer_two_details['td_silver_policy_count'] * 9.63;
    $data['tvs_td_silver_purchase_total_amount'] = ($data['tvs_td_silver_purchase_cost'] + $data['tvs_td_silver_purchase_gst'] - $data['tvs_td_silver_purchase_tds']);
    /*********************TD DATA***************************/
    
    /*********************MTD DATA***************************/
    $data['tvs_mtd_platinum_purchase_cost'] = $layer_two_details['mtd_platinum_policy_count'] * 57;
    $data['tvs_mtd_platinum_purchase_tds'] = $layer_two_details['mtd_platinum_policy_count'] * 5.7;
    $data['tvs_mtd_platinum_purchase_gst'] = $layer_two_details['mtd_platinum_policy_count'] * 8.73;
    $data['tvs_mtd_platinum_purchase_total_amount'] = ($data['tvs_mtd_platinum_purchase_cost'] + $data['tvs_mtd_platinum_purchase_gst'] - $data['tvs_mtd_platinum_purchase_tds']);
    
    $data['tvs_mtd_sapphire_purchase_cost'] = $layer_two_details['mtd_sapphire_policy_count'] * 54;
    $data['tvs_mtd_sapphire_purchase_tds'] = $layer_two_details['mtd_sapphire_policy_count'] * 5.4;
    $data['tvs_mtd_sapphire_purchase_gst'] = $layer_two_details['mtd_sapphire_policy_count'] * 9.72;
    $data['tvs_mtd_sapphire_purchase_total_amount'] = ($data['tvs_mtd_sapphire_purchase_cost'] + $data['tvs_mtd_sapphire_purchase_gst'] - $data['tvs_mtd_sapphire_purchase_tds']);
    
    $data['tvs_mtd_gold_purchase_cost'] = $layer_two_details['mtd_gold_policy_count'] * 57;
    $data['tvs_mtd_gold_purchase_tds'] = $layer_two_details['mtd_gold_policy_count'] * 5.7;
    $data['tvs_mtd_gold_purchase_gst'] = $layer_two_details['mtd_gold_policy_count'] * 10.26;
    $data['tvs_mtd_gold_purchase_total_amount'] = ($data['tvs_mtd_gold_purchase_cost'] + $data['tvs_mtd_gold_purchase_gst'] - $data['tvs_mtd_gold_purchase_tds']);
    
    $data['tvs_mtd_silver_purchase_cost'] = $layer_two_details['mtd_silver_policy_count'] * 53.5;
    $data['tvs_mtd_silver_purchase_tds'] = $layer_two_details['mtd_silver_policy_count'] * 5.35;
    $data['tvs_mtd_silver_purchase_gst'] = $layer_two_details['mtd_silver_policy_count'] * 9.63;
    $data['tvs_mtd_silver_purchase_total_amount'] = ($data['tvs_mtd_silver_purchase_cost'] + $data['tvs_mtd_silver_purchase_gst'] - $data['tvs_mtd_silver_purchase_tds']);
    /*********************MTD DATA***************************/
    
    /*********************YTD DATA***************************/
    $data['tvs_ytd_platinum_purchase_cost'] = $layer_two_details['ytd_platinum_policy_count'] * 57;
    $data['tvs_ytd_platinum_purchase_tds'] = $data['tvs_ytd_platinum_purchase_cost'] * 0.10;
    $data['tvs_ytd_platinum_purchase_gst'] = $layer_two_details['ytd_platinum_policy_count'] * 8.73;
    $data['tvs_ytd_platinum_purchase_total_amount'] = ($data['tvs_ytd_platinum_purchase_cost'] + $data['tvs_ytd_platinum_purchase_gst'] - $data['tvs_ytd_platinum_purchase_tds']);
    
    $data['tvs_ytd_sapphire_purchase_cost'] = $layer_two_details['ytd_sapphire_policy_count'] * 54;
    $data['tvs_ytd_sapphire_purchase_tds'] = $layer_two_details['ytd_sapphire_policy_count'] * 5.4;
    $data['tvs_ytd_sapphire_purchase_gst'] = $layer_two_details['ytd_sapphire_policy_count'] * 9.72;
    $data['tvs_ytd_sapphire_purchase_total_amount'] = ($data['tvs_ytd_sapphire_purchase_cost'] + $data['tvs_ytd_sapphire_purchase_gst'] - $data['tvs_ytd_sapphire_purchase_tds']);
    
    $data['tvs_ytd_gold_purchase_cost'] = $layer_two_details['ytd_gold_policy_count'] * 57;
    $data['tvs_ytd_gold_purchase_tds'] = $data['tvs_ytd_gold_purchase_cost'] * 0.10;
    $data['tvs_ytd_gold_purchase_gst'] = $layer_two_details['ytd_gold_policy_count'] * 10.26;
    $data['tvs_ytd_gold_purchase_total_amount'] = ($data['tvs_ytd_gold_purchase_cost'] + $data['tvs_ytd_gold_purchase_gst'] - $data['tvs_ytd_gold_purchase_tds']);
    
    $data['tvs_ytd_silver_purchase_cost'] = $layer_two_details['ytd_silver_policy_count'] * 53.5;
    $data['tvs_ytd_silver_purchase_tds'] = $layer_two_details['ytd_silver_policy_count'] * 5.35;
    $data['tvs_ytd_silver_purchase_gst'] = $layer_two_details['ytd_silver_policy_count'] * 9.63;
    $data['tvs_ytd_silver_purchase_total_amount'] = ($data['tvs_ytd_silver_purchase_cost'] + $data['tvs_ytd_silver_purchase_gst'] - $data['tvs_ytd_silver_purchase_tds']);
    /*********************YTD DATA***************************/
    
    // END Purchase Cost TVS Commission
    
    //MARGIN -ICPL
    $data['icpl_td_platinum_policy_margin'] = $layer_two_details['td_platinum_policy_count'] * 72.73;
    $data['icpl_mtd_platinum_policy_margin'] = $layer_two_details['mtd_platinum_policy_count'] * 72.73;
    $data['icpl_ytd_platinum_policy_margin'] = $layer_two_details['ytd_platinum_policy_count'] * 72.73;
    
    $data['icpl_td_sapphire_policy_margin'] = $layer_two_details['td_sapphire_policy_count'] * 24.23;
    $data['icpl_mtd_sapphire_policy_margin'] = $layer_two_details['mtd_sapphire_policy_count'] * 24.23;
    $data['icpl_ytd_sapphire_policy_margin'] = $layer_two_details['ytd_sapphire_policy_count'] * 24.23;

    $data['icpl_td_gold_policy_margin'] = $layer_two_details['td_gold_policy_count'] * 15.73;
    $data['icpl_mtd_gold_policy_margin'] = $layer_two_details['mtd_gold_policy_count'] * 15.73;
    $data['icpl_ytd_gold_policy_margin'] = $layer_two_details['ytd_gold_policy_count'] * 15.73;
    
    $data['icpl_td_silver_policy_margin'] = $layer_two_details['td_silver_policy_count'] * 19.23;
    $data['icpl_mtd_silver_policy_margin'] = $layer_two_details['mtd_silver_policy_count'] * 19.23;
    $data['icpl_ytd_silver_policy_margin'] = $layer_two_details['ytd_silver_policy_count'] * 19.23;
    
    //END MARGIN -ICPL
    
    
    
    //OUTPUT GST
    $data['td_platinum_policy_output_gst'] = $layer_two_details['td_platinum_policy_count'] * 67.27;
    $data['mtd_platinum_policy_output_gst'] = $layer_two_details['mtd_platinum_policy_count'] * 67.27;
    $data['ytd_platinum_policy_output_gst'] = $layer_two_details['ytd_platinum_policy_count'] * 67.27;
    
    $data['td_sapphire_policy_output_gst'] = $layer_two_details['td_sapphire_policy_count'] * 71.85;
    $data['mtd_sapphire_policy_output_gst'] = $layer_two_details['mtd_sapphire_policy_count'] * 71.85;
    $data['ytd_sapphire_policy_output_gst'] = $layer_two_details['ytd_sapphire_policy_count'] * 71.85;

    $data['td_gold_policy_output_gst'] = $layer_two_details['td_gold_policy_count'] * 53.39;
    $data['mtd_gold_policy_output_gst'] = $layer_two_details['mtd_gold_policy_count'] * 53.39;
    $data['ytd_gold_policy_output_gst'] = $layer_two_details['ytd_gold_policy_count'] * 53.39;
    
    $data['td_silver_policy_output_gst'] = $layer_two_details['td_silver_policy_count'] * 38.29;
    $data['mtd_silver_policy_output_gst'] = $layer_two_details['mtd_silver_policy_count'] * 38.29;
    $data['ytd_silver_policy_output_gst'] = $layer_two_details['ytd_silver_policy_count'] * 38.29;
    
    //END OUTPUT GST
    
    //INPUT GST
    $data['td_platinum_policy_input_gst'] = ($data['td_platinum_purchase_gst']+$data['rsa_td_platinum_purchase_gst']+$data['dc_td_platinum_purchase_gst']+$data['tvs_td_platinum_purchase_gst']);
    $data['mtd_platinum_policy_input_gst'] = ($data['mtd_platinum_purchase_gst']+$data['rsa_mtd_platinum_purchase_gst']+$data['dc_mtd_platinum_purchase_gst']+$data['tvs_mtd_platinum_purchase_gst']);
    $data['ytd_platinum_policy_input_gst'] = ($data['ytd_platinum_purchase_gst']+$data['rsa_ytd_platinum_purchase_gst']+$data['dc_ytd_platinum_purchase_gst']+$data['tvs_ytd_platinum_purchase_gst']);
    
    $data['td_sapphire_policy_input_gst'] = ($data['td_sapphire_purchase_gst']+$data['rsa_td_sapphire_purchase_gst']+$data['dc_td_sapphire_purchase_gst']+$data['tvs_td_sapphire_purchase_gst']);
    $data['mtd_sapphire_policy_input_gst'] = ($data['mtd_sapphire_purchase_gst']+$data['rsa_mtd_sapphire_purchase_gst']+$data['dc_mtd_sapphire_purchase_gst']+$data['tvs_mtd_sapphire_purchase_gst']);
    $data['ytd_sapphire_policy_input_gst'] = ($data['ytd_sapphire_purchase_gst']+$data['rsa_ytd_sapphire_purchase_gst']+$data['dc_ytd_sapphire_purchase_gst']+$data['tvs_mtd_sapphire_purchase_gst']);

    $data['td_gold_policy_input_gst'] = ($data['td_gold_purchase_gst']+$data['rsa_td_gold_purchase_gst']+$data['dc_td_gold_purchase_gst']+$data['tvs_td_gold_purchase_gst']);
    $data['mtd_gold_policy_input_gst'] = ($data['mtd_gold_purchase_gst']+$data['rsa_mtd_gold_purchase_gst']+$data['dc_mtd_gold_purchase_gst']+$data['tvs_mtd_gold_purchase_gst']);
    $data['ytd_gold_policy_input_gst'] = ($data['ytd_gold_purchase_gst']+$data['rsa_ytd_gold_purchase_gst']+$data['dc_ytd_gold_purchase_gst']+$data['tvs_ytd_gold_purchase_gst']);
    
    $data['td_silver_policy_input_gst'] = ($data['td_silver_purchase_gst']+$data['rsa_td_silver_purchase_gst']+$data['dc_td_silver_purchase_gst']+$data['tvs_td_silver_purchase_gst']);
    $data['mtd_silver_policy_input_gst'] = ($data['mtd_silver_purchase_gst']+$data['rsa_mtd_silver_purchase_gst']+$data['dc_mtd_silver_purchase_gst']+$data['tvs_mtd_silver_purchase_gst']);
    $data['ytd_silver_policy_input_gst'] = ($data['ytd_silver_purchase_gst']+$data['rsa_ytd_silver_purchase_gst']+$data['dc_ytd_silver_purchase_gst']+$data['tvs_ytd_silver_purchase_gst']);
    
    //END INPUT GST
    
    //NET GST
   $data['td_platinum_policy_net_gst'] = ($data['td_platinum_policy_output_gst'] - $data['td_platinum_policy_input_gst']);
   $data['mtd_platinum_policy_net_gst'] = ($data['mtd_platinum_policy_output_gst'] - $data['mtd_platinum_policy_input_gst']);
   $data['ytd_platinum_policy_net_gst'] = ($data['ytd_platinum_policy_output_gst'] - $data['ytd_platinum_policy_input_gst']);
    
   
   $data['td_sapphire_policy_net_gst'] = ($data['td_sapphire_policy_output_gst'] - $data['td_sapphire_policy_input_gst']);
   $data['mtd_sapphire_policy_net_gst'] = ($data['mtd_sapphire_policy_output_gst'] - $data['mtd_sapphire_policy_input_gst']);
   $data['ytd_sapphire_policy_net_gst'] = ($data['ytd_sapphire_policy_output_gst'] - $data['ytd_sapphire_policy_input_gst']);
   
   $data['td_gold_policy_net_gst'] = ($data['td_gold_policy_output_gst'] - $data['td_gold_policy_input_gst']);
   $data['mtd_gold_policy_net_gst'] = ($data['mtd_gold_policy_output_gst'] - $data['mtd_gold_policy_input_gst']);
   $data['ytd_gold_policy_net_gst'] = ($data['ytd_gold_policy_output_gst'] - $data['ytd_gold_policy_input_gst']);
   
   $data['td_silver_policy_net_gst'] = ($data['td_silver_policy_output_gst'] - $data['td_silver_policy_input_gst']);
   $data['mtd_silver_policy_net_gst'] = ($data['mtd_silver_policy_output_gst'] - $data['mtd_silver_policy_input_gst']);
   $data['ytd_silver_policy_net_gst'] = ($data['ytd_silver_policy_output_gst'] - $data['ytd_silver_policy_input_gst']);
    //END NET GST
   
    //TDS
   $data['td_platinum_policy_tds']    =($data['td_platinum_purchase_tds'] + $data['rsa_td_platinum_purchase_tds'] + $data['dc_td_platinum_purchase_tds'] + $data['tvs_td_platinum_purchase_tds']);
   $data['mtd_platinum_policy_tds']   =($data['mtd_platinum_purchase_tds'] + $data['rsa_mtd_platinum_purchase_tds'] + $data['dc_mtd_platinum_purchase_tds'] + $data['tvs_mtd_platinum_purchase_tds']);
   $data['ytd_platinum_policy_tds']   =($data['ytd_platinum_purchase_tds'] + $data['rsa_ytd_platinum_purchase_tds'] + $data['dc_ytd_platinum_purchase_tds'] + $data['tvs_ytd_platinum_purchase_tds']);
    
   $data['td_sapphire_policy_tds'] =($data['td_sapphire_purchase_tds'] + $data['rsa_td_sapphire_purchase_tds'] + $data['dc_td_sapphire_purchase_tds'] + $data['tvs_td_sapphire_purchase_tds']);
   $data['mtd_sapphire_policy_tds'] =($data['mtd_sapphire_purchase_tds'] + $data['rsa_mtd_sapphire_purchase_tds'] + $data['dc_mtd_sapphire_purchase_tds'] + $data['tvs_mtd_sapphire_purchase_tds']);
   $data['ytd_sapphire_policy_tds'] =($data['ytd_sapphire_purchase_tds'] + $data['rsa_ytd_sapphire_purchase_tds'] + $data['dc_ytd_sapphire_purchase_tds'] + $data['tvs_ytd_sapphire_purchase_tds']);
   
   $data['td_gold_policy_tds'] =($data['td_gold_purchase_tds'] + $data['rsa_td_gold_purchase_tds'] + $data['dc_td_gold_purchase_tds'] + $data['tvs_td_gold_purchase_tds']);
   $data['mtd_gold_policy_tds'] =($data['mtd_gold_purchase_tds'] + $data['rsa_mtd_gold_purchase_tds'] + $data['dc_mtd_gold_purchase_tds'] + $data['tvs_mtd_gold_purchase_tds']);
   $data['ytd_gold_policy_tds'] =($data['ytd_gold_purchase_tds'] + $data['rsa_ytd_gold_purchase_tds'] + $data['dc_ytd_gold_purchase_tds'] + $data['tvs_ytd_gold_purchase_tds']);
   
   $data['td_silver_policy_tds'] =($data['td_silver_purchase_tds'] + $data['rsa_td_silver_purchase_tds'] + $data['dc_td_silver_purchase_tds'] + $data['tvs_td_silver_purchase_tds']);
   $data['mtd_silver_policy_tds'] =($data['mtd_silver_purchase_tds'] + $data['rsa_mtd_silver_purchase_tds'] + $data['dc_mtd_silver_purchase_tds'] + $data['tvs_mtd_silver_purchase_tds']);
   $data['ytd_silver_policy_tds'] =($data['ytd_silver_purchase_tds'] + $data['rsa_ytd_silver_purchase_tds'] + $data['dc_ytd_silver_purchase_tds'] + $data['tvs_ytd_silver_purchase_tds']);
    //END TDS
    
    
    $data['main_contain'] = 'admin/report/layer_two';
    $this->load->view('admin/includes/template', $data);
}

public function receivable_dashboard(){
    $data['deposit_status'] = $this->Home_Model->deposit_status();
    $data['sold_status'] = $this->Home_Model->sold_policies();
    $data['total_commission'] = $this->Home_Model->total_commission();
    $data['main_contain'] = 'admin/report/receivable_dashboard';
    $this->load->view('admin/includes/template', $data);    
}

public function payable_dashboard(){
    $party_payment_details = $this->Home_Model->partyPaymentDetails();
    $layer_two_details =  $this->Home_Model->getLayerTwoDetails();
    $tvs_deposit =  $this->Home_Model->tvs_deposit();

    $data['td_platinum_policy_output_gst'] = $layer_two_details['td_platinum_policy_count'] * 67.27;
    $data['mtd_platinum_policy_output_gst'] = $layer_two_details['mtd_platinum_policy_count'] * 67.27;
    $data['ytd_platinum_policy_output_gst'] = $layer_two_details['ytd_platinum_policy_count'] * 67.27;
    
    $data['td_sapphire_policy_output_gst'] = $layer_two_details['td_sapphire_policy_count'] * 71.85;
    $data['mtd_sapphire_policy_output_gst'] = $layer_two_details['mtd_sapphire_policy_count'] * 71.85;
    $data['ytd_sapphire_policy_output_gst'] = $layer_two_details['ytd_sapphire_policy_count'] * 71.85;

    $data['td_gold_policy_output_gst'] = $layer_two_details['td_gold_policy_count'] * 53.39;
    $data['mtd_gold_policy_output_gst'] = $layer_two_details['mtd_gold_policy_count'] * 53.39;
    $data['ytd_gold_policy_output_gst'] = $layer_two_details['ytd_gold_policy_count'] * 53.39;
    
    $data['td_silver_policy_output_gst'] = $layer_two_details['td_silver_policy_count'] * 38.29;
    $data['mtd_silver_policy_output_gst'] = $layer_two_details['mtd_silver_policy_count'] * 38.29;
    $data['ytd_silver_policy_output_gst'] = $layer_two_details['ytd_silver_policy_count'] * 38.29;

    $data['td_platinum_input_gst'] = ($layer_two_details['td_platinum_policy_count'] * 37.80) + ($layer_two_details['td_platinum_policy_count'] * 2.88) + ($layer_two_details['td_platinum_policy_count'] * 13.50) + ($layer_two_details['td_platinum_policy_count'] * 8.73);
    $data['td_sapphire_input_gst'] = ($layer_two_details['td_sapphire_policy_count'] * 37.80) + ($layer_two_details['td_sapphire_policy_count'] * 5.76) + ($layer_two_details['td_sapphire_policy_count'] * 14.4) + ($layer_two_details['td_sapphire_policy_count'] * 9.72);
    $data['td_gold_input_gst'] = ($layer_two_details['td_gold_policy_count'] * 25.2) + ($layer_two_details['td_gold_policy_count'] * 2.88) + ($layer_two_details['td_gold_policy_count'] * 10.8) + ($layer_two_details['td_gold_policy_count'] * 10.26);
    $data['td_silver_input_gst'] = ($layer_two_details['td_silver_policy_count'] * 12.6) + ($layer_two_details['td_silver_policy_count'] * 2.88) + ($layer_two_details['td_silver_policy_count'] * 9) + ($layer_two_details['td_silver_policy_count'] * 9.63);    

    $data['mtd_platinum_input_gst'] = ($layer_two_details['mtd_platinum_policy_count'] * 37.80) + ($layer_two_details['mtd_platinum_policy_count'] * 2.88) + ($layer_two_details['mtd_platinum_policy_count'] * 13.50) + ($layer_two_details['mtd_platinum_policy_count'] * 8.73);
    $data['mtd_sapphire_input_gst'] = ($layer_two_details['mtd_sapphire_policy_count'] * 37.80) + ($layer_two_details['mtd_sapphire_policy_count'] * 5.76) + ($layer_two_details['mtd_sapphire_policy_count'] * 14.4) + ($layer_two_details['mtd_sapphire_policy_count'] * 9.72);
    $data['mtd_gold_input_gst'] = ($layer_two_details['mtd_gold_policy_count'] * 25.2) + ($layer_two_details['mtd_gold_policy_count'] * 2.88) + ($layer_two_details['mtd_gold_policy_count'] * 10.8) + ($layer_two_details['mtd_gold_policy_count'] * 10.26);
    $data['mtd_silver_input_gst'] = ($layer_two_details['mtd_silver_policy_count'] * 12.6) + ($layer_two_details['mtd_silver_policy_count'] * 2.88) + ($layer_two_details['mtd_silver_policy_count'] * 9) + ($layer_two_details['mtd_silver_policy_count'] * 9.63);

    $data['ytd_platinum_input_gst'] = ($layer_two_details['ytd_platinum_policy_count'] * 37.80) + ($layer_two_details['ytd_platinum_policy_count'] * 2.88) + ($layer_two_details['ytd_platinum_policy_count'] * 13.50) + ($layer_two_details['ytd_platinum_policy_count'] * 8.73);
    $data['ytd_sapphire_input_gst'] = ($layer_two_details['ytd_sapphire_policy_count'] * 37.80) + ($layer_two_details['ytd_sapphire_policy_count'] * 5.76) + ($layer_two_details['ytd_sapphire_policy_count'] * 14.4) + ($layer_two_details['ytd_sapphire_policy_count'] * 9.72);
    $data['ytd_gold_input_gst'] = ($layer_two_details['ytd_gold_policy_count'] * 25.2) + ($layer_two_details['ytd_gold_policy_count'] * 2.88) + ($layer_two_details['ytd_gold_policy_count'] * 10.8) + ($layer_two_details['ytd_gold_policy_count'] * 10.26);
    $data['ytd_silver_input_gst'] = ($layer_two_details['ytd_silver_policy_count'] * 12.6) + ($layer_two_details['ytd_silver_policy_count'] * 2.88) + ($layer_two_details['ytd_silver_policy_count'] * 9) + ($layer_two_details['ytd_silver_policy_count'] * 9.63);

    $data['td_net_gst'] = ($data['td_platinum_policy_output_gst'] + $data['td_sapphire_policy_output_gst'] + $data['td_gold_policy_output_gst'] + $data['td_silver_policy_output_gst']) - ($data['td_platinum_input_gst'] + $data['td_sapphire_input_gst'] + $data['td_gold_input_gst'] + $data['td_silver_input_gst']);

    $data['mtd_net_gst'] = ($data['mtd_platinum_policy_output_gst'] + $data['mtd_sapphire_policy_output_gst'] + $data['mtd_gold_policy_output_gst'] + $data['mtd_silver_policy_output_gst']) - ($data['mtd_platinum_input_gst'] + $data['mtd_sapphire_input_gst'] + $data['mtd_gold_input_gst'] + $data['mtd_silver_input_gst']);
    
    $data['ytd_net_gst'] = ($data['ytd_platinum_policy_output_gst'] + $data['ytd_sapphire_policy_output_gst'] + $data['ytd_gold_policy_output_gst'] + $data['ytd_silver_policy_output_gst']) - ($data['ytd_platinum_input_gst'] + $data['ytd_sapphire_input_gst'] + $data['ytd_gold_input_gst'] + $data['ytd_silver_input_gst']);

    $data['td_platinum_input_tds'] = ($layer_two_details['td_platinum_policy_count'] * 4.2) + ($layer_two_details['td_platinum_policy_count'] * 0.32) + ($layer_two_details['td_platinum_policy_count'] * 3.75) + ($layer_two_details['td_platinum_policy_count'] * 5.7);
    
    $data['td_sapphire_input_tds'] = ($layer_two_details['td_sapphire_policy_count'] * 4.2) + ($layer_two_details['td_sapphire_policy_count'] * 0.64) + ($layer_two_details['td_sapphire_policy_count'] * 4) + ($layer_two_details['td_sapphire_policy_count'] * 5.4);
    
    $data['td_gold_input_tds'] = ($layer_two_details['td_gold_policy_count'] * 2.8) + ($layer_two_details['td_gold_policy_count'] * 0.32) + ($layer_two_details['td_gold_policy_count'] * 3) + ($layer_two_details['td_gold_policy_count'] * 5.7);
    
    $data['td_silver_input_tds'] = ($layer_two_details['td_silver_policy_count'] * 1.4) + ($layer_two_details['td_silver_policy_count'] * 0.32) + ($layer_two_details['td_silver_policy_count'] * 2.5) + ($layer_two_details['td_silver_policy_count'] * 5.35); 

    $data['mtd_platinum_input_tds'] = ($layer_two_details['mtd_platinum_policy_count'] * 4.2) + ($layer_two_details['mtd_platinum_policy_count'] * 0.32) + ($layer_two_details['mtd_platinum_policy_count'] * 3.75) + ($layer_two_details['mtd_platinum_policy_count'] * 5.7);
    
    $data['mtd_sapphire_input_tds'] = ($layer_two_details['mtd_sapphire_policy_count'] * 4.2) + ($layer_two_details['mtd_sapphire_policy_count'] * 0.64) + ($layer_two_details['mtd_sapphire_policy_count'] * 4) + ($layer_two_details['mtd_sapphire_policy_count'] * 5.4);
    
    $data['mtd_gold_input_tds'] = ($layer_two_details['mtd_gold_policy_count'] * 2.8) + ($layer_two_details['mtd_gold_policy_count'] * 0.32) + ($layer_two_details['mtd_gold_policy_count'] * 3) + ($layer_two_details['mtd_gold_policy_count'] * 5.7);
    
    $data['mtd_silver_input_tds'] = ($layer_two_details['mtd_silver_policy_count'] * 1.4) + ($layer_two_details['mtd_silver_policy_count'] * 0.32) + ($layer_two_details['mtd_silver_policy_count'] * 2.5) + ($layer_two_details['mtd_silver_policy_count'] * 5.35); 

    $data['ytd_platinum_input_tds'] = ($layer_two_details['ytd_platinum_policy_count'] * 4.2) + ($layer_two_details['ytd_platinum_policy_count'] * 0.32) + ($layer_two_details['ytd_platinum_policy_count'] * 3.75) + ($layer_two_details['ytd_platinum_policy_count'] * 5.7);
    
    $data['ytd_sapphire_input_tds'] = ($layer_two_details['ytd_sapphire_policy_count'] * 4.2) + ($layer_two_details['ytd_sapphire_policy_count'] * 0.64) + ($layer_two_details['ytd_sapphire_policy_count'] * 4) + ($layer_two_details['ytd_sapphire_policy_count'] * 5.4);
    
    $data['ytd_gold_input_tds'] = ($layer_two_details['ytd_gold_policy_count'] * 2.8) + ($layer_two_details['ytd_gold_policy_count'] * 0.32) + ($layer_two_details['ytd_gold_policy_count'] * 3) + ($layer_two_details['ytd_gold_policy_count'] * 5.7);
    
    $data['ytd_silver_input_tds'] = ($layer_two_details['ytd_silver_policy_count'] * 1.4) + ($layer_two_details['ytd_silver_policy_count'] * 0.32) + ($layer_two_details['ytd_silver_policy_count'] * 2.5) + ($layer_two_details['ytd_silver_policy_count'] * 5.35);     


    $data['td_input_tds'] = $data['td_platinum_input_tds'] + $data['td_sapphire_input_tds'] + $data['td_gold_input_tds'] + $data['td_silver_input_tds'];
    $data['mtd_input_tds'] = $data['mtd_platinum_input_tds'] + $data['mtd_sapphire_input_tds'] + $data['mtd_gold_input_tds'] + $data['mtd_silver_input_tds'];
    $data['ytd_input_tds'] = $data['ytd_platinum_input_tds'] + $data['ytd_sapphire_input_tds'] + $data['ytd_gold_input_tds'] + $data['ytd_silver_input_tds'];


    $data['td_tvs'] = $tvs_deposit['td_tvs_deposit_amount'];
    $data['mtd_tvs'] = $tvs_deposit['mtd_tvs_deposit_amount'];
    $data['ytd_tvs'] = $tvs_deposit['ytd_tvs_deposit_amount'];
    $data['ytd_gst'] = $tvs_deposit['ytd_gst_deposit_amount'];
    $data['ytd_tds'] = $tvs_deposit['ytd_tds_deposit_amount'];

    $data['td_sold_tvs'] = ($layer_two_details['td_gold_policy_count'] * 57) + ($layer_two_details['td_platinum_policy_count'] * 57) + ($layer_two_details['td_sapphire_policy_count'] * 54) + ($layer_two_details['td_silver_policy_count'] * 53.5);
    $data['mtd_sold_tvs'] = ($layer_two_details['mtd_gold_policy_count'] * 57) + ($layer_two_details['mtd_platinum_policy_count'] * 57) + ($layer_two_details['mtd_sapphire_policy_count'] * 54) + ($layer_two_details['mtd_silver_policy_count'] * 53.5);    
    $data['ytd_sold_tvs'] = ($layer_two_details['ytd_gold_policy_count'] * 57) + ($layer_two_details['ytd_platinum_policy_count'] * 57) + ($layer_two_details['ytd_sapphire_policy_count'] * 54) + ($layer_two_details['ytd_silver_policy_count'] * 53.5);    
   //echo '<pre>'; print_r($party_payment_details);die('annu');
    // $data['party_payment_details'] = $party_payment_details;
    $data['kotak_td_deposit'] = $party_payment_details['kotak_td_deposit'];
    $data['kotak_mtd_deposit'] = $party_payment_details['kotak_mtd_deposit'];
    $data['kotak_ytd_deposit'] = $party_payment_details['kotak_ytd_deposit'];

    $data['il_td_deposit'] = $party_payment_details['il_td_deposit'];
    $data['il_mtd_deposit'] = $party_payment_details['il_mtd_deposit'];
    $data['il_ytd_deposit'] = $party_payment_details['il_ytd_deposit'];
    $data['tata_td_deposit'] = $party_payment_details['tata_td_deposit'];
    $data['tata_mtd_deposit'] = $party_payment_details['tata_mtd_deposit'];
    $data['tata_ytd_deposit'] = $party_payment_details['tata_ytd_deposit'];
    $data['bagi_td_deposit'] = $party_payment_details['bagi_td_deposit'];
    $data['bagi_mtd_deposit'] = $party_payment_details['bagi_mtd_deposit'];
    $data['bagi_ytd_deposit'] = $party_payment_details['bagi_ytd_deposit'];
    $data['bharti_assist_td_deposit'] = $party_payment_details['bharti_assist_td_deposit'];
    $data['bharti_assist_mtd_deposit'] = $party_payment_details['bharti_assist_mtd_deposit'];
    $data['bharti_assist_ytd_deposit'] = $party_payment_details['bharti_assist_ytd_deposit'];
    $data['bharti_assist_td_amount'] = $party_payment_details['bharti_assist_td_amount'];
    $data['bharti_assist_mtd_amount'] = $party_payment_details['bharti_assist_mtd_amount'];
    $data['bharti_assist_ytd_amount'] = $party_payment_details['bharti_ytd_policy_count'];
    $data['bharti_assist_td_balance'] = $data['bharti_assist_td_deposit']-$data['bharti_assist_td_amount'];
    $data['bharti_assist_mtd_balance'] = $data['bharti_assist_mtd_deposit']-$data['bharti_assist_mtd_amount'];
    $data['bharti_assist_ytd_balance'] = $data['bharti_assist_ytd_deposit']-$data['bharti_assist_ytd_amount'];

    $data['kotak_td_policy_count'] = $party_payment_details['kotak_td_policy_count'];
    $data['kotak_mtd_policy_count'] = $party_payment_details['kotak_mtd_policy_count'];
    $data['kotak_ytd_policy_count'] = $party_payment_details['kotak_ytd_policy_count'];
    $data['il_td_policy_count'] = $party_payment_details['il_td_policy_count'];
    $data['il_mtd_policy_count'] = $party_payment_details['il_mtd_policy_count'];
    $data['il_ytd_policy_count'] = $party_payment_details['il_ytd_policy_count'];
    $data['tata_td_policy_count'] = $party_payment_details['tata_td_policy_count'];
    $data['tata_mtd_policy_count'] = $party_payment_details['tata_mtd_policy_count'];
    $data['tata_ytd_policy_count'] = $party_payment_details['tata_ytd_policy_count'];
    $data['bagi_td_policy_count'] = $party_payment_details['bagi_td_policy_count'];
    $data['bagi_mtd_policy_count'] = $party_payment_details['bagi_mtd_policy_count'];
    $data['bagi_ytd_policy_count'] = $party_payment_details['bagi_ytd_policy_count'];

    $data['bharti_td_policy_count'] = $party_payment_details['bharti_td_policy_count'] * (16 + (16 * 0.18));
    $data['bharti_mtd_policy_count'] = $party_payment_details['bharti_mtd_policy_count'] * (16 + (16 * 0.18));
    $data['bharti_ytd_policy_count'] = $party_payment_details['bharti_ytd_policy_count'] * (16 + (16 * 0.18));

    $td_kotak_platinum_policy_count = $layer_two_details['td_kotak_platinum_policy_count'] * (210 + (210 * 0.18)) ;
    $td_kotak_sapphire_policy_count = $layer_two_details['td_kotak_sapphire_policy_count'] * (210 + (210 * 0.18)) ;
    $td_kotak_gold_policy_count = $layer_two_details['td_kotak_gold_policy_count'] * (140 + (140 * 0.18)) ;
    $td_kotak_silver_policy_count = $layer_two_details['td_kotak_silver_policy_count'] * (70 + (70 * 0.18));

    $td_il_platinum_policy_count = $layer_two_details['td_il_platinum_policy_count'] * (210 + (210 * 0.18));
    $td_il_sapphire_policy_count = $layer_two_details['td_il_sapphire_policy_count'] * (210 + (210 * 0.18));
    $td_il_gold_policy_count = $layer_two_details['td_il_gold_policy_count'] * (140 + (140 * 0.18));
    $td_il_silver_policy_count = $layer_two_details['td_il_silver_policy_count'] * (70 + (70 * 0.18));

    $td_tata_platinum_policy_count = $layer_two_details['td_tata_platinum_policy_count'] * (210 + (210 * 0.18));
    $td_tata_sapphire_policy_count = $layer_two_details['td_tata_sapphire_policy_count'] * (210 + (210 * 0.18));
    $td_tata_gold_policy_count = $layer_two_details['td_tata_gold_policy_count'] * (140 + (140 * 0.18));
    $td_tata_silver_policy_count = $layer_two_details['td_tata_silver_policy_count'] * (70 + (70 * 0.18));

    $td_bagi_platinum_policy_count = $layer_two_details['td_bagi_platinum_policy_count'] * (210 + (210 * 0.18));
    $td_bagi_sapphire_policy_count = $layer_two_details['td_bagi_sapphire_policy_count'] * (210 + (210 * 0.18));
    $td_bagi_gold_policy_count = $layer_two_details['td_bagi_gold_policy_count'] * (140 + (140 * 0.18));
    $td_bagi_silver_policy_count = $layer_two_details['td_bagi_silver_policy_count'] * (70 + (70 * 0.18));

    $mtd_kotak_platinum_policy_count = $layer_two_details['mtd_kotak_platinum_policy_count'] * (210 + (210 * 0.18)) ;
    $mtd_kotak_sapphire_policy_count = $layer_two_details['mtd_kotak_sapphire_policy_count'] * (210 + (210 * 0.18)) ;
    $mtd_kotak_gold_policy_count = $layer_two_details['mtd_kotak_gold_policy_count'] * (140 + (140 * 0.18)) ;
    $mtd_kotak_silver_policy_count = $layer_two_details['mtd_kotak_silver_policy_count'] * (70 + (70 * 0.18));

    $mtd_il_platinum_policy_count = $layer_two_details['mtd_il_platinum_policy_count'] * (210 + (210 * 0.18));
    $mtd_il_sapphire_policy_count = $layer_two_details['mtd_il_sapphire_policy_count'] * (210 + (210 * 0.18));
    $mtd_il_gold_policy_count = $layer_two_details['mtd_il_gold_policy_count'] * (140 + (140 * 0.18));
    $mtd_il_silver_policy_count = $layer_two_details['mtd_il_silver_policy_count'] * (70 + (70 * 0.18));

    $mtd_tata_platinum_policy_count = $layer_two_details['mtd_tata_platinum_policy_count'] * (210 + (210 * 0.18));
    $mtd_tata_sapphire_policy_count = $layer_two_details['mtd_tata_sapphire_policy_count'] * (210 + (210 * 0.18));
    $mtd_tata_gold_policy_count = $layer_two_details['mtd_tata_gold_policy_count'] * (140 + (140 * 0.18));
    $mtd_tata_silver_policy_count = $layer_two_details['mtd_tata_silver_policy_count'] * (70 + (70 * 0.18));

    $mtd_bagi_platinum_policy_count = $layer_two_details['mtd_bagi_platinum_policy_count'] * (210 + (210 * 0.18));
    $mtd_bagi_sapphire_policy_count = $layer_two_details['mtd_bagi_sapphire_policy_count'] * (210 + (210 * 0.18));
    $mtd_bagi_gold_policy_count = $layer_two_details['mtd_bagi_gold_policy_count'] * (140 + (140 * 0.18));
    $mtd_bagi_silver_policy_count = $layer_two_details['mtd_bagi_silver_policy_count'] * (70 + (70 * 0.18));

    $ytd_kotak_platinum_policy_count = $layer_two_details['ytd_kotak_platinum_policy_count'] * (210 + (210 * 0.18)) ;
    $ytd_kotak_sapphire_policy_count = $layer_two_details['ytd_kotak_sapphire_policy_count'] * (210 + (210 * 0.18)) ;
    $ytd_kotak_gold_policy_count = $layer_two_details['ytd_kotak_gold_policy_count'] * (140 + (140 * 0.18)) ;
    $ytd_kotak_silver_policy_count = $layer_two_details['ytd_kotak_silver_policy_count'] * (70 + (70 * 0.18));

    $ytd_il_platinum_policy_count = $layer_two_details['ytd_il_platinum_policy_count'] * (210 + (210 * 0.18));
    $ytd_il_sapphire_policy_count = $layer_two_details['ytd_il_sapphire_policy_count'] * (210 + (210 * 0.18));
    $ytd_il_gold_policy_count = $layer_two_details['ytd_il_gold_policy_count'] * (140 + (140 * 0.18));
    $ytd_il_silver_policy_count = $layer_two_details['ytd_il_silver_policy_count'] * (70 + (70 * 0.18));

    $ytd_tata_platinum_policy_count = $layer_two_details['ytd_tata_platinum_policy_count'] * (210 + (210 * 0.18));
    $ytd_tata_sapphire_policy_count = $layer_two_details['ytd_tata_sapphire_policy_count'] * (210 + (210 * 0.18));
    $ytd_tata_gold_policy_count = $layer_two_details['ytd_tata_gold_policy_count'] * (140 + (140 * 0.18));
    $ytd_tata_silver_policy_count = $layer_two_details['ytd_tata_silver_policy_count'] * (70 + (70 * 0.18));

    $ytd_bagi_platinum_policy_count = $layer_two_details['ytd_bagi_platinum_policy_count'] * (210 + (210 * 0.18));
    $ytd_bagi_sapphire_policy_count = $layer_two_details['ytd_bagi_sapphire_policy_count'] * (210 + (210 * 0.18));
    $ytd_bagi_gold_policy_count = $layer_two_details['ytd_bagi_gold_policy_count'] * (140 + (140 * 0.18));
    $ytd_bagi_silver_policy_count = $layer_two_details['ytd_bagi_silver_policy_count'] * (70 + (70 * 0.18));
// die($layer_two_details['ytd_bagi_platinum_policy_count']);
    $data['kotak_td_policy_amount'] =  $td_kotak_platinum_policy_count + $td_kotak_sapphire_policy_count + $td_kotak_gold_policy_count + $td_kotak_silver_policy_count ;
    $data['il_td_policy_amount'] =  $td_il_platinum_policy_count + $td_il_sapphire_policy_count + $td_il_gold_policy_count + $td_il_silver_policy_count ;
    $data['tata_td_policy_amount'] =  $td_tata_platinum_policy_count + $td_tata_sapphire_policy_count + $td_tata_gold_policy_count + $td_tata_silver_policy_count ;
    $data['bagi_td_policy_amount'] =  $td_bagi_platinum_policy_count + $td_bagi_sapphire_policy_count + $td_bagi_gold_policy_count + $td_bagi_silver_policy_count ;

    $data['kotak_mtd_policy_amount'] =  $mtd_kotak_platinum_policy_count + $mtd_kotak_sapphire_policy_count + $mtd_kotak_gold_policy_count + $mtd_kotak_silver_policy_count ;
    $data['il_mtd_policy_amount'] =  $mtd_il_platinum_policy_count + $mtd_il_sapphire_policy_count + $mtd_il_gold_policy_count + $mtd_il_silver_policy_count ;
    $data['tata_mtd_policy_amount'] =  $mtd_tata_platinum_policy_count + $mtd_tata_sapphire_policy_count + $mtd_tata_gold_policy_count + $mtd_tata_silver_policy_count ;
    $data['bagi_mtd_policy_amount'] =  $mtd_bagi_platinum_policy_count + $mtd_bagi_sapphire_policy_count + $mtd_bagi_gold_policy_count + $mtd_bagi_silver_policy_count ;    

    $data['kotak_ytd_policy_amount'] =  $ytd_kotak_platinum_policy_count + $ytd_kotak_sapphire_policy_count + $ytd_kotak_gold_policy_count + $ytd_kotak_silver_policy_count ;
    $data['il_ytd_policy_amount'] =  $ytd_il_platinum_policy_count + $ytd_il_sapphire_policy_count + $ytd_il_gold_policy_count + $ytd_il_silver_policy_count ;
    $data['tata_ytd_policy_amount'] =  $ytd_tata_platinum_policy_count + $ytd_tata_sapphire_policy_count + $ytd_tata_gold_policy_count + $ytd_tata_silver_policy_count ;
    $data['bagi_ytd_policy_amount'] =  $ytd_bagi_platinum_policy_count + $ytd_bagi_sapphire_policy_count + $ytd_bagi_gold_policy_count + $ytd_bagi_silver_policy_count ;

    $data['kotak_td_balance'] = $data['kotak_td_deposit'] - $data['kotak_td_policy_amount'] ;
    $data['kotak_mtd_balance'] = $data['kotak_mtd_deposit'] - $data['kotak_mtd_policy_amount'] ;
    $data['kotak_ytd_balance'] = $data['kotak_ytd_deposit'] - $data['kotak_ytd_policy_amount'] ;

    $data['il_td_balance'] = $data['il_td_deposit'] - $data['il_td_policy_amount'] ;
    $data['il_mtd_balance'] = $data['il_mtd_deposit'] - $data['il_mtd_policy_amount'] ;
    $data['il_ytd_balance'] = $data['il_ytd_deposit'] - $data['il_ytd_policy_amount'] ;

    $data['tata_td_balance'] = $data['tata_td_deposit'] - $data['tata_td_policy_amount'] ;
    $data['tata_mtd_balance'] = $data['tata_mtd_deposit'] - $data['tata_mtd_policy_amount'] ;
    $data['tata_ytd_balance'] = $data['tata_ytd_deposit'] - $data['tata_ytd_policy_amount'] ;

    $data['bagi_td_balance'] = $data['bagi_td_deposit'] - $data['bagi_td_policy_amount'] ;
    $data['bagi_mtd_balance'] = $data['bagi_mtd_deposit'] - $data['bagi_mtd_policy_amount'] ;
    $data['bagi_ytd_balance'] = $data['bagi_ytd_deposit'] - $data['bagi_ytd_policy_amount'] ;
// echo "<pre>"; print_r($data); echo "</pre>"; die('end of line yoyo');

    $data['main_contain'] = 'admin/report/payable_dashboard';
    $this->load->view('admin/includes/template', $data);
}



function dealer_rsa_payment(){
    $data['deposit_status'] = $this->Home_Model->deposit_status();
    $data['sold_status'] = $this->Home_Model->sold_policies();
    $data['total_commission'] = $this->Home_Model->total_commission();
    $data['main_contain'] = 'admin/report/dealer_rsa_payment';
    $this->load->view('admin/includes/template', $data);
}

function dealer_rsa_payment_ajax(){
    $requestData = $_REQUEST;
    $start = $requestData['start'];
    $length = $requestData['length'];
    $columns = array(
        5=>'dealer_name',
        6=>'sap_ad_code',
        6=>'for_the_month',
        6=>'for_the_day',
    );

    $admin_session = $this->session->userdata('admin_session');

    $policy_sql = " SELECT tsp.`user_id`,td.`dealer_name`,td.`sap_ad_code`,(security_amount - credit_amount) AS balance_amount,
                    SUM(IF(YEAR(tsp.created_date) = YEAR(CURDATE()),tsp.`sold_policy_price_with_tax`,0)) AS policy_amt_for_year,
                    SUM(IF(MONTH(tsp.created_date) = MONTH(CURDATE()),tsp.`sold_policy_price_with_tax`,0)) AS policy_amt_for_month,
                    COUNT(IF(DATE(tsp.`created_date`) = CURDATE(),tsp.`sold_policy_price_with_tax`,0)) AS ytd_sold,
                    SUM(IF(DATE(tsp.`created_date`) = CURDATE(),tsp.`sold_policy_price_with_tax`,0)) AS policy_amt_today, dw.credit_amount
                    FROM tvs_sold_policies tsp 
                    INNER JOIN tvs_dealers td ON td.`id`=tsp.`user_id`
                    JOIN dealer_wallet dw ON dw.`dealer_id`=tsp.`user_id`
                    WHERE tsp.`policy_status_id`=3 GROUP BY tsp.`user_id` ";
              // die($sql1);
    $query = $this->db->query($policy_sql);
    $policy_data = $query->result_array();

    $deposit_sql = " SELECT dbt.`dealer_id`,
                    SUM(IF(YEAR(dbt.created_date) = YEAR(CURDATE()),dbt.`deposit_amount`,0)) AS deposit_for_year,
                    SUM(IF(MONTH(dbt.created_date) = MONTH(CURDATE()) ,dbt.`deposit_amount`,0)) AS deposit_for_month,
                    SUM(IF(DATE(dbt.`created_date`) = CURDATE(),dbt.`deposit_amount`,0)) AS deposit_today
                    FROM dealer_bank_transaction dbt WHERE dbt.`transaction_type`='deposit'
                    AND dbt.`approval_status`='approved' GROUP BY dbt.dealer_id " ;

     $query = $this->db->query($deposit_sql);
    $dealer_deposit = $query->result_array();

    foreach ($policy_data as $key1 => $data) {
        foreach ($dealer_deposit as $key2 => $value) {
            if($data['user_id']==$value['dealer_id']){
                $policy_data[$key1]['deposit_for_year'] = $value['deposit_for_year'];
                $policy_data[$key1]['deposit_for_month'] = $value['deposit_for_month'];
                $policy_data[$key1]['deposit_today'] = $value['deposit_today'];
            }
        }
    }
     //echo '<pre>'; print_r($policy_data);die('here');
    $totalFiltered = $totalData;
    $totalFiltered = count($results);
    $i = 1;
    $data = array();
    foreach ($policy_data as $main) {
        $nestedData = array();
        $nestedData[] = $i++;
        $nestedData[] = $main['dealer_name'];
        $nestedData[] = $main['deposit_today'];
        $nestedData[] = $main['deposit_for_month'];
        $nestedData[] = $main['deposit_for_year'];
        $nestedData[] = $main['credit_amount'];
        $nestedData[] = $main['credit_amount'] + $main['deposit_for_year'];
        $nestedData[] = $main['policy_amt_today'];
        $nestedData[] = $main['policy_amt_for_month'];
        $nestedData[] = $main['policy_amt_for_year'];
        $nestedData[] = $main['ytd_sold'];
        $nestedData[] = $main['balance_amount'];
        $data[] = $nestedData;
    }
    // echo '<pre>'; print_r($data);die('here');
    $json_data = array(
        "draw" => intval(0),
        "recordsTotal" => intval($totalFiltered),
        "recordsFiltered" => intval($totalFiltered),
        "data" => $data
    );

    echo json_encode($json_data);
}

function policy_detail(){
    $data['main_contain'] = 'admin/report/policy_detail';
    $this->load->view('admin/includes/template', $data);
}

function policy_detail_ajax(){
    $requestData = $_REQUEST;
    $start = $requestData['start'];
    $length = $requestData['length'];
    $columns = array(
        5=>'dealer_name',
        6=>'engine_no',
        6=>'chassis_no',
        6=>'sold_policy_no',
    );

    $admin_session = $this->session->userdata('admin_session');
    $start_date = $_REQUEST['start_date'];
    $end_date = $_REQUEST['end_date'];
    // die($start_date);
    $where = '';
     // $where = 'pp.invoice_date' != 0;
    if (!empty($start_date) && !empty($end_date)) {
        $where = "AND (CAST(tsp.sold_policy_date AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
    }else{
        $where = "AND (CAST(tsp.sold_policy_date AS DATE)) = CURDATE() " ;
    }
    
    $policy_sql = "SELECT paic.`name` AS pa_ic_name,rsaic.name AS rsa_ic_name, tsp.*,tp.`dealer_commission`,tp.`gst_amount`,tp.`rsa_tenure`,td.`dealer_name`,td.`sap_ad_code`,tdz.`zone`,
        tcd.`state`,tcd.`city`,tcd.`city_name`,tcd.`state_name`,tcd.`fname`,tcd.`lname` FROM tvs_sold_policies tsp 
        INNER JOIN tvs_customer_details tcd ON tsp.`customer_id`=tcd.`id` 
        LEFT JOIN tvs_insurance_companies rsaic ON rsaic.`id`=tsp.`rsa_ic_id`
        LEFT JOIN tvs_insurance_companies paic ON paic.`id`=tsp.`ic_id`
        INNER JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` 
        JOIN tvs_dealers td ON td.`id`= tsp.`user_id` LEFT JOIN `tvs_dealer_zone_mapper` tdz ON tdz.`id`=td.`zone_id`
        WHERE tsp.`policy_status_id`=3 AND tsp.user_id <> 2871 $where ";
        // die($policy_sql);
              
    $query = $this->db->query($policy_sql);
    $policy_data = $query->result_array();

    // echo '<pre>'; print_r($policy_data);die('here');
    // $totalFiltered = $totalData;
    $totalFiltered = count($policy_data);
    $i = 1;$rsa_amount=0;$pa_cost=0;
    $data = array();
    foreach ($policy_data as $main) {

$tvs_comm = 0;

        if($main['rsa_tenure']==1){
            // $rsa_gst = (18 / 100) *; 16;
            // $rsa_amount = 16 + $rsa_gst
            $rsa_amount = 16;
        }
         if($main['rsa_tenure']==2){
            // $rsa_gst = (18 / 100) * 32;
            // $rsa_amount = 32 + $rsa_gst;
            $rsa_amount = 32 ;
        }
        switch ($main['plan_name']) {
            case 'Silver':
                $pa_cost = 70;
                $tvs_comm = 53.50;
                break;
            case 'Gold':
                $pa_cost = 140;
                $tvs_comm = 57;
                break;
            case 'Platinum':
                $pa_cost = 210;
                $tvs_comm = 57;
                break;
            case 'Sapphire':
                $pa_cost = 210;
                $tvs_comm = 54;
                break;
            case 'Sapphire Plus':
                $pa_cost = 375;
                $tvs_comm = 54;
                break;
            case 'LIMITLESS ASSIST E (RR 310)':
                $pa_cost = 0; 
                $tvs_comm = 237; $rsa_amount =535;
                $main['sold_policy_price_without_tax'] = 846.61;
                $main['sold_policy_price_with_tax'] = 999;
                $main['dealer_commission'] =0;
                break;
            case 'LIMITLESS ASSIST RENEWAL':
                $pa_cost = 0;
                $tvs_comm = 162; $rsa_amount =535;
                $main['sold_policy_price_without_tax'] = 846.61;
                $main['gst_amount'] = 152.39;
                $main['sold_policy_price_with_tax'] = 999;
                $main['dealer_commission'] =100;
                break;
            case 'LIMITLESS ASSIST(RR 310)':
                $pa_cost = 0;
                $tvs_comm = 262; $rsa_amount =535;
                $main['sold_policy_price_without_tax'] = 846.61;
                $main['gst_amount'] = 152.39;
                $main['sold_policy_price_with_tax'] = 999;
                $main['dealer_commission'] =0;
                break;
            
            default:
                $pa_cost = 0;
                $tvs_comm = 0;
                break;
        }

         $net_cost = $pa_cost + $rsa_amount + $main['dealer_commission'] + $tvs_comm;
         $gst_on_net = ($net_cost * 18) / 100;
         $total_cost = $net_cost + $gst_on_net;
         $tds_pa = ($pa_cost * 2) / 100;
         $tds_rsa = ($rsa_amount * 2) / 100;
         $tds_dealer = ($main['dealer_commission'] * 5) / 100;
         $tds_tvs = ($tvs_comm * 10) / 100;
         $tds_total = $tds_rsa + $tds_dealer + $tds_tvs;
         $net_amount = $total_cost - $tds_total;
         $margin_icpl = $main['sold_policy_price_without_tax'] - $net_cost;

switch ($main['ic_id']) {
    case 2:
        $margin_giib = ($pa_cost * 15) / 100;
    break;

    case 5:
        $margin_giib = ($pa_cost * 5) / 100;
    break;

    case 9:
        $margin_giib = ($pa_cost * 7.5) / 100;
    break;

    case 12:
        $margin_giib = ($pa_cost * 10) / 100;
    break;        

    default:
    $margin_giib = 0;
    break;
}
        $nestedData = array();
        $nestedData[] = $i++;
        $nestedData[] = $main['fname'].' '.$main['lname'];
        $nestedData[] = $main['engine_no'];
        $nestedData[] = $main['chassis_no'];
        $nestedData[] = $main['sold_policy_no'];
        $nestedData[] = $main['sold_policy_effective_date'];
        $nestedData[] = $main['sold_policy_end_date'];
        $nestedData[] = $main['rsa_ic_name'];
        $nestedData[] = $main['pa_ic_name'];
        $nestedData[] = $main['plan_name'];
        $nestedData[] = $main['sold_policy_price_without_tax'];
        $nestedData[] = $main['gst_amount'];
        $nestedData[] = $main['sold_policy_price_with_tax'];
        $nestedData[] = $pa_cost;
        $nestedData[] = $rsa_amount;
        $nestedData[] = $main['dealer_commission'];
        $nestedData[] = $tvs_comm;
        $nestedData[] = $net_cost;
        $nestedData[] = $gst_on_net;
        $nestedData[] = $total_cost;
        //$nestedData[] = $gst;
        //$nestedData[] = $total_cost;
        // $nestedData[] = $tds_pa;
        $nestedData[] = $tds_rsa;
        $nestedData[] = $tds_dealer;
        $nestedData[] = $tds_tvs;
        $nestedData[] = $tds_total;
        $nestedData[] = $net_amount;
        $nestedData[] = $margin_icpl;
        $nestedData[] = $margin_giib;
        $nestedData[] = $main['sap_ad_code'];
        $nestedData[] = $main['dealer_name'];
        $nestedData[] = '';
        $nestedData[] = $main['city_name'];
        $nestedData[] = $main['state_name'];
        $data[] = $nestedData;
    }
    // echo '<pre>'; print_r($data);die('here');
    $json_data = array(
        "draw" => intval(0),
        "recordsTotal" => intval($totalFiltered),
        "recordsFiltered" => intval($totalFiltered),
        "data" => $data
    );

    echo json_encode($json_data);
}


function LastSoldPolicyDate(){
    $data['main_contain'] = 'admin/report/last_policy_date';
    $this->load->view('admin/includes/template', $data);
}

function downloadLastPolicyDate($from,$to){

$heading_array = array("Dealer Code","Last Policy Date");
$main_array = array();
array_push($main_array, $heading_array);
$lastpolicydate = $this->Home_Model->getLastPolicydate($from,$to);
ini_set('memory_limit', '-1');
foreach ($lastpolicydate as $value) {
    $sap_ad_code = $value['sap_ad_code'];
    $last_sold_policy_date = $value['last_sold_policy_date'];


    $array_value = array($sap_ad_code,$last_sold_policy_date);
    array_push($main_array, $array_value);
}
    $csv_file_name = "Last-Policy-Date" . date('y-m-d') . ".csv";
    echo array_to_csv($main_array, $csv_file_name);

}

function dealerGraphicalReport($dealer_id)
    {
        $data['dealer_data'] = $this->Home_Model->getDealerDetails($dealer_id);
        $data['kotak_data'] = $this->Home_Model->getKotakDealerGraphicalData($dealer_id);
        $data['il_data'] = $this->Home_Model->getIlDealerGraphicalData($dealer_id);
        $data['tata_data'] = $this->Home_Model->getTataDealerGraphicalData($dealer_id);
        $data['bagi_data'] = $this->Home_Model->getBagiDealerGraphicalData($dealer_id);
        $data['main_contain'] = 'admin/report/dealer_graphical_report';
        $this->load->view('admin/includes/template', $data);
    }

	public function LibertyGeneralFeedfile($ic_id){
		$data['ic_id'] = $ic_id ;
		$data['main_contain'] = 'admin/report/liberty_feedfile';
		$this->load->view('admin/includes/template', $data);
	}

    public function liberty_endorse_feedfile($ic_id){
        $data['ic_id'] = $ic_id ;
        $data['endorse'] = 'endorse' ;
        $data['main_contain'] = 'admin/report/liberty_feedfile';
        $this->load->view('admin/includes/template', $data);
    }

    public function liberty_canceled_feedfile($ic_id){
            $data['ic_id'] = $ic_id ;
            $data['endorse'] = 'canceled' ;
            $data['main_contain'] = 'admin/report/liberty_feedfile';
            $this->load->view('admin/includes/template', $data);
    }

	function DownloadLibertyFeedfile($ic_id,$from,$to,$policy_type){
		$heading_array = array("Member_Name","Date_of_Birth","Age","Gender","Relationship_with_Primary_Insured","Earning","Monthly_Income","Sum_Insured","Occupation_Profession","RiskCategory","Group_Id","Joining_Date","Employee_Code","Location","Address1","Address2","Address3","City_Town","District","State","Area","Pincode","Nominee_Name","Nominee_RelationShip","Nominee_Address");
		$main_array = array();
		array_push($main_array, $heading_array);

        if($policy_type=='endorse'){
            $policy_data = $this->Home_Model->getPAEndorseFeedfile($ic_id,$from,$to);
            $csv_file_name = "Liberty-endorse-feedfile".date('Y-m-d').".csv";
        }
        elseif($policy_type=='canceled'){
            $policy_data = $this->Home_Model->getPACanceledFeedfile($ic_id,$from,$to);

            $heading_array = array("Policy No","Transaction date","Customer Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Dealer Code","Pin Code","Reason Of Cancelation","Nominee Name","Nominee Relationship","Appointee Name","Appointee Relationship","Cancelation Date");
            $csv_file_name = "Liberty-canceled-feedfile".date('Y-m-d').".csv";
        }
        else{        
		  $policy_data = $this->Home_Model->getLibertyFeedfile_data($ic_id,$from,$to);
        }
		// echo "<pre>"; print_r($policy_data); echo "</pre>"; die('end of line yoyo');
		foreach ($policy_data as $key => $policy) {
				
				$Member_Name = $policy['customer_name'];
				$Date_of_Birth = $policy['dob'];
				$dob = date('Y-m-d',strtotime($Date_of_Birth));
				$Age =  date_diff(date_create($dob), date_create('today'))->y;
				$Gender = $policy['gender'];
				$Relationship_with_Primary_Insured = 'Self';
				$Earning = "";
				$Monthly_Income = "";
				$Sum_Insured = $policy['sum_insured'];
				$Occupation_Profession = "";
				$RiskCategory="CLASS-I";
				$Group_Id ="Group1";
				$Joining_Date = $policy['created_date'];
				$Employee_Code = $policy['sold_policy_no'];
				$Location = $policy['city_name'];
				$Address1 = $policy['addr1'];
				$Address2 = $policy['addr2'];
				$Address3 = "";
				$City_Town = $policy['city_name'];
				$District = "";
				$State = $policy['state_name'];
				$Area = "";
				$Pincode = $policy['pincode'];
				$Nominee_Name = $policy['nominee_full_name'];
				$Nominee_RelationShip = $policy['nominee_relation'];
				$Nominee_Address = "";

				$value_array = array($Member_Name,$Date_of_Birth,$Age,$Gender,$Relationship_with_Primary_Insured,$Earning,$Monthly_Income,$Sum_Insured,$Occupation_Profession,$RiskCategory,$Group_Id,$Joining_Date,$Employee_Code,$Location,$Address1,$Address2,$Address3,$City_Town,$District,$State,$Area,$Pincode,$Nominee_Name,$Nominee_RelationShip,$Nominee_Address);

				array_push($main_array, $value_array);
		}

		$csv_file_name = "Liberty_feedfile-".date('d-m-Y').".csv";
		echo array_to_csv($main_array,$csv_file_name);
	}

	function RelianceGeneralFeedfile($ic_id){
		$data['ic_id'] = $ic_id ;
		$data['main_contain'] = 'admin/report/reliance_feedfile';
		$this->load->view('admin/includes/template', $data);
	}

	function DownloadRelianceFeedfile($ic_id,$from,$to,$policy_type){

		$heading_array = array("Reference No","Alternate Policy No","Transaction date","Customer Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Marital Status","Nominee name","Nominee Age","Nominee Relationship","Dealer Code","Geo/Vertical","Salutation","Pincode","Occupation","Name of Asignee","Relationship_of_the_assignee_with_the_Insured","Policy start time","Registered_under_GST","GSTIN","Net Premium","CGST_Rate","CGST_Premium","IGST_Rate","IGST_Premium","SGST_Rate","SGST_Premium","UGST_Rate","UGST_Premium","Total Premium","Nomiee DOB");

        $csv_file_name = "RelianceFeedfile".date('Y-m-d').".csv";

        if($policy_type=='endorse'){
            $policy_data = $this->Home_Model->getPAEndorseFeedfile($ic_id,$from,$to);
            $csv_file_name = "Reliance-endorse-feedfile".date('Y-m-d').".csv";
        }
        elseif($policy_type=='canceled'){
            $policy_data = $this->Home_Model->getRelianceFeedfile_data($ic_id,$from,$to,5);

            $heading_array = array("Reference No","Alternate Policy No","Transaction date","Customer Name","Contact Number","Email id","Address","Location","State","Policy Start Date","Policy End date","Sum Insured","Premium","DOB","Gender","Marital Status","Nominee name","Nominee Age","Nominee Relationship","Dealer Code","Geo/Vertical","Salutation","Pincode","Occupation","Name of Asignee","Relationship_of_the_assignee_with_the_Insured","Policy start time","Registered_under_GST","GSTIN","Net Premium","CGST_Rate","CGST_Premium","IGST_Rate","IGST_Premium","SGST_Rate","SGST_Premium","UGST_Rate","UGST_Premium","Total Premium","Nomiee DOB","Reason Of Cancelation","Cancelation Date");
            $csv_file_name = "Reliance-canceled-feedfile".date('Y-m-d').".csv";
        }
        else{
            $policy_data = $this->Home_Model->getRelianceFeedfile_data($ic_id,$from,$to,3);
        }
		$main_array = array();
		array_push($main_array, $heading_array);
        
		//$policy_data = $this->Home_Model->getRelianceFeedfile_data($ic_id,$from,$to);
		//echo "<pre>"; print_r($policy_data); echo "</pre>"; die('end of line yoyo');


		foreach ($policy_data['result'] as $key => $policy) {
			$created_time = explode(" ", $policy['created_date']);
			$time = $created_time[1];
	// echo "<pre>"; print_r($time); echo "</pre>"; die('end of line yoyo');
			$Premium = $policy['pa_ic_commission_amount'];
			$SGST_Rate = (0.18 * $Premium);
			$net_sgst_prem = $SGST_Rate + $Premium ;

			$refrence_no = $policy['sold_policy_no'];
			$alternate_policy_no = "";
			$transaction_date = $policy['sold_policy_effective_date'];
			$customer_name = $policy['customer_name'];
			$contact_no = $policy['mobile_no'];
			$email = $policy['email'];
			$address = $policy['addr1'].','.$policy['addr2'];
			$location = $policy['city_name'];
			$state_name = $policy['state_name'];
			$start_date = $policy['pa_sold_policy_effective_date'];
			$end_date = $policy['pa_sold_policy_end_date'];
			$sum_insured = $policy['sum_insured'];
			$Premium = $policy['pa_ic_commission_amount'];
			$dob = $policy['dob'];
			$gender = $policy['gender'];
			$Marital_status = "";
			$nominee_full_name = $policy['nominee_full_name'];
			$nominee_age = $policy['nominee_age'];
			$nominee_relation = $policy['nominee_relation'];
			$dealer_code = $policy['dealer_code'];
			$geo_vertical = "";
			$Salutation = "";
			$pincode = $policy['pincode'];
            $cancelation_reson = $policy['cancellation_reson'];
			$Occupation = "";
			$assignee_name = $policy['appointee_full_name'];
			$assignee_relation = $policy['appointee_relation'];
            $cancellation_date = $policy['cancellation_date'];

			$policy_start_time = $time;
			$Registered_under_GST = "INDICOSMIC PVT LTD";
			$GSTIN = "27AAECI3370G1ZN";
			$net_premium = $net_sgst_prem;
			$CGST_Rate = "";
			$CGST_Premium = "";
			$IGST_Rate = "";
			$IGST_Premium = "";
			$SGST_Rate = $SGST_Rate;
			$SGST_Premium = $net_sgst_prem;
			$UGST_Rate = "";
			$UGST_Premium = "";
			$total_premium = $net_sgst_prem;
			$Nominee_dob = "";


			$value_array = array($refrence_no,$alternate_policy_no,$transaction_date,$customer_name,$contact_no,$email,$address,$location,$state_name,$start_date,$end_date,$sum_insured,$Premium,$dob,$gender,$Marital_status,$nominee_full_name,$nominee_age,$nominee_relation,$dealer_code,$geo_vertical,$Salutation,$pincode,$Occupation,$assignee_name,$assignee_relation,$policy_start_time,$Registered_under_GST,$GSTIN,$net_premium,$CGST_Rate,$CGST_Premium,$IGST_Rate,$IGST_Premium,$SGST_Rate,$SGST_Premium,$UGST_Rate,$UGST_Premium,$total_premium,$Nominee_dob);

            if($policy_type=='canceled'){
                $value_array = array($refrence_no,$alternate_policy_no,$transaction_date,$customer_name,$contact_no,$email,$address,$location,$state_name,$start_date,$end_date,$sum_insured,$Premium,$dob,$gender,$Marital_status,$nominee_full_name,$nominee_age,$nominee_relation,$dealer_code,$geo_vertical,$Salutation,$pincode,$Occupation,$assignee_name,$assignee_relation,$policy_start_time,$Registered_under_GST,$GSTIN,$net_premium,$CGST_Rate,$CGST_Premium,$IGST_Rate,$IGST_Premium,$SGST_Rate,$SGST_Premium,$UGST_Rate,$UGST_Premium,$total_premium,$Nominee_dob,$cancelation_reson,$cancellation_date);
            }
			
				array_push($main_array, $value_array);
				//$csvfile_name = 'RelianceFeedfile'.date('Y-m-d').'.csv';
		}
				echo array_to_csv($main_array,$csv_file_name);
	}

    public function reliance_endorse_feedfile($ic_id){
        $data['ic_id'] = $ic_id ;
        $data['endorse'] = 'endorse' ;
        $data['main_contain'] = 'admin/report/reliance_feedfile';
        $this->load->view('admin/includes/template', $data);
    }

    public function reliance_canceled_feedfile($ic_id){
            $data['ic_id'] = $ic_id ;
            $data['endorse'] = 'canceled' ;
            $data['main_contain'] = 'admin/report/reliance_feedfile';
            $this->load->view('admin/includes/template', $data);
    }

    function orientalPoliciesUpload()
        {
           $data['oriental_master_policies'] = $this->Home_Model->getDataFromTable('tvs_oriental_master_policy');
           $data['main_contain'] = 'admin/report/oriental_policies_upload';
           $this->load->view('admin/includes/template', $data);
        }
function submitOrientalPoliciesForm() {
if(isset($_POST["upload"]))
{
if($_FILES['oriental_policies_file']['name'])
{
 $filename = explode(".", $_FILES['oriental_policies_file']['name']);
 if(end($filename) == "csv")
 {
  $handle = fopen($_FILES['oriental_policies_file']['tmp_name'], "r");
  $count=0;
  while($data = fgetcsv($handle))
  {
   $count++;
   if($count>1)
   {
   $oriental_where_data = array(
               'master_policy_no' => $data[0]
               );    
   $start_date = str_replace('/', '-', $data[1]);
   $end_date = str_replace('/', '-', $data[2]);
   $oriental_data = array(
               'master_policy_no' => $data[0],
               'start_date' => date('Y-m-d',strtotime($start_date)),
               'end_date' => date('Y-m-d',strtotime($end_date))
               );
   $where = $oriental_where_data;
   $is_exist = $this->Home_Model->getRowDataFromTable('tvs_oriental_master_policy',$where);
   if(!$is_exist)
   {
       $insert_res=$this->Home_Model->insertIntoTable("tvs_oriental_master_policy",$oriental_data);
   }
   }
   }
  fclose($handle);
  $this->session->set_flashdata('success_message', 'Oriental master policies data updated successfully');
  redirect('admin/oreiental_policies_upload');
 }
 else
 {
  $message = '<label class="text-danger">Please Select CSV File only</label>';
  $this->session->set_flashdata('success_message',$message);
  redirect('admin/oreiental_policies_upload');
 }
}
else
{
 $message = '<label class="text-danger">Please Select File</label>';
 $this->session->set_flashdata('success_message',$message);
  redirect('admin/oreiental_policies_upload');
}
}
}

function OrientalMasterPoliciesListAjax(){
   $admin_session = $this->session->userdata('admin_session');
   $requestData = $_REQUEST;
   $start = $requestData['start'];
   $length = $requestData['length'];
   $columns = array(
       0 => 'master_policy_no',
       1 => 'start_date',
       2 => 'end_date',
       3 => 'created_date',
   );
   $sql = "SELECT * FROM tvs_oriental_master_policy ORDER BY id DESC";
   //die($sql);
   $totalFiltered = $totalData;

   $query = $this->db->query($sql);
   $totalFiltered = $query->num_rows();
   $result = $query->result();
   $data = array();
   $i = 1;
   foreach ($result as $main) {
       $row = array();
       $row[] = $i++;
       $row[] = $main->master_policy_no;
       $row[] = $main->start_date;
       $row[] = $main->end_date;
       $row[] = $main->created_date;
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

function fetchByPolicyNo(){
    $data['main_contain'] = 'admin/report/fetch_data';
    // $where = array('insurance_type' => 'RSA');
    $data['party_lists'] = $this->Home_Model->getDataFromTable('tvs_insurance_companies');
    $this->load->view('admin/includes/template', $data);
}

function fetchPolicydata(){
        $search_policy_no = $this->input->post('search_policy_no');
        if(!empty($search_policy_no)){
            $policy_data = $this->Home_Model->getPolicyDataByNo($search_policy_no);
        }
        // echo "<pre>"; print_r($policy_data); echo "</pre>"; die('end of line yoyo');
        if(!empty($policy_data)){

        foreach ($policy_data as $row) {
            if($row['policy_status_id']==3){
                $policy_status = "Active";
            }elseif($row['policy_status_id']==4){
                $policy_status = "Requested for Cancelation";
            }elseif($row['policy_status_id']==5){
                $policy_status = "Policy is Canceled";
            }
            $html .= '<tr>
                        <td>'.$row['sap_ad_code'].'</td>
                        <td>'.$row['customer_name'].'</td>
                        <td>'.$row['mobile_no'].'</td>
                        <td>'.$row['dob'].'</td>
                        <td>'.$row['sold_policy_no'].'</td>
                        <td>'.$row['engine_no'].'</td>
                        <td>'.$row['chassis_no'].'</td>
                        <td>'.$row['vehicle_registration_no'].'</td>
                        <td>'.$row['make_name'].'</td>
                        <td>'.$row['model_name'].'</td>
                        <td>'.$policy_status.'</td>
                        <td>'.$row['plan_name'].'</td>
                        <td>'.$row['name'].'</td>
                        <td>'.$row['created_date'].'</td>
                        <td>'.$row['sold_policy_effective_date'].'</td>
                        <td>'.$row['sold_policy_end_date'].'</td>
                        <td>'.$row['cancellation_date'].'</td>
                    </tr>';
        }
            
            
    }else{
        $html='<tr><td>NO DATA</td></tr>';

    }

        echo json_encode($html);

        // echo "<pre>"; print_r($_POST); echo "</pre>"; die('end of line yoyo');
    }

    public function orientalReports($ic_id){
        // die($ic_id);
        $data['ic_id'] = $ic_id;
        $data['main_contain'] = 'admin/report/special_report';
        $this->load->view('admin/includes/template', $data);
    }

public function specialReportsAjax($ic_id,$from,$to){
    $report_data = $this->Home_Model->specialReports($ic_id,$from,$to);
        // echo '<pre>'; print_r($report_data);die('hello');
        $length = count($report_data);
        $this->load->library('Tcpdf/Mypdf.php');
              ob_start();


        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Oriental MIS requirment');
$pdf->SetSubject('MYPDF Tutorial');
$pdf->SetKeywords('MYPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(5, 30, 5);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);

// add a page
$pdf->AddPage();
$html .=<<<EOD

EOD;
// set some text to print
$html .=<<<EOD
<style>
    .pagewrap {color: #000; font-size: 7pt; line-height:9pt; color:#000;}
    .textcenter {text-align:center;}
    .textleft {text-align:left;}
    .textright {text-align:right;}
    .font-7{font-size: 7pt; line-height:9pt;}
    .font-8{font-size: 8pt; line-height:10pt;}
    .font-9{font-size: 9pt; line-height:11pt;}
    .font-10{font-size: 10pt; line-height:12pt;}
    .font-11{font-size: 11pt; line-height:13pt;}
    .header {background-color:#fff;}
    .headertext {font-size:12pt; line-height:16pt; color:#000;}
    .border, .boxtable td {border:0.2px solid #000;}
    .sectionhead { font-size:7pt; line-height:10pt; background-color:#000; color:#fff;} 
</style>


<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
    
    <tr>
        <td>
            <table cellpadding="0" border="0" cellspacing="0" class="header">
                <tr>
                    <td class="textcenter font-11">Datewise Premium</td>
                </tr>
                <tr>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            
            <table cellpadding="3" border="0" cellspacing="0" class="boxtable">
                <tr>
                    <td width="10%" class="sectionhead"><b>DATE</b></td>
                    <td width="25%" class="sectionhead"><b>NO. OF MEMBERS</b></td>
                    <td width="15%" class="sectionhead textright"><b>SUM INSURED</b></td>
                    <td width="12%" class="sectionhead textright"><b>PREMIUM</b></td>
                    <td width="8%" class="sectionhead"><b>GST @ 18</b></td>
                    <td width="10%" class="sectionhead"><b>T0TAL PREM</b></td>
                    <td width="20%" class="sectionhead"><b>POLICY NO.</b></td>
                </tr>
EOD;
        $dates = [];
        $master_policy_nos =[];$gst_premium=$total_gst=0;
        // $total_no_of_member = 0;

//echo "<pre>";print_r($report_data);

        for ($i=0; $i <= $length; $i++) { 
            if(in_array($report_data[$i]['date'], $dates)){
                $date = '';
            }else{
                $date = $report_data[$i]['date'];
                $dates[$i] = $report_data[$i]['date'];
            }
            if(in_array($report_data[$i]['master_policy_no'], $master_policy_nos)){
                $master_policy_no = '';
            }else{
                $master_policy_no = $report_data[$i-1]['master_policy_no'];
                $master_policy_nos[$i] = $report_data[$i]['master_policy_no'];
            }
  //          echo "policy==".$master_policy_no."<br>";
            $no_of_member = $report_data[$i]['no_of_member'];
            //echo $no_of_member.'<br>';
            $total_no_of_member += $report_data[$i-1]['no_of_member'];
            $sum_insured = $report_data[$i]['sum_insured'];
            $basic_premium = $report_data[$i]['premium'];
            $total_basic_premium += $report_data[$i-1]['premium'];

            $gst_premium = $basic_premium * 0.18 ;
            $total_gst += $report_data[$i-1]['premium'] * 0.18 ;
            //$total_gst += $basic_premium * 0.18;
  //          echo "baic--".$basic_premium."--------gst==".$gst_premium."------total===".$total_gst."<br>";

            $total_premium = $basic_premium + $gst_premium;
            $final_total_premium += $report_data[$i-1]['premium'] + $report_data[$i-1]['premium'] * 0.18;
            //$final_total_premium += $basic_premium + $gst_premium ;
            // $gst = round(((18/100)*$basic_premium),2);
            // $total_gst += round(((18/100)*$basic_premium),2);
            // $total_premium = $basic_premium + $gst;
            // $final_total_premium += $basic_premium + $gst;
           // die();
            //echo '<pre>'; print_r($dates);//die('hello');
           if((!empty($date) && $i != 0) || ($i == $length)){
//echo "tttttt===".$total_gst."<br>";
            // echo $master_policy_no.'<br>';
                
            $html .=<<<EOD
                <tr>
                    <td></td>
                    <td class="textright"><b>{$total_no_of_member}</b></td>
                    <td></td>
                    <td class="textright"><b>{$total_basic_premium}</b></td>
                    <td class="textright"><b>{$total_gst}</b></td>
                    <td class="textright"><b>{$final_total_premium}</b></td>
                    <td><b>{$master_policy_no}</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
EOD;
           }
           if((!empty($date))){
                $total_no_of_member = 0;
                $total_basic_premium = 0;
                $total_gst = 0;
                $final_total_premium = 0;
           }
           $html .=<<<EOD
                <tr>
                    <td>{$date}</td>
                    <td class="textright">{$no_of_member}</td>
                    <td class="textright">{$sum_insured}</td>
                    <td class="textright">{$basic_premium}</td>
                    <td class="textright">{$gst_premium}</td>
                    <td class="textright">{$total_premium}</td>
                    <td></td>
                </tr>
EOD;
            
        }
        $html .=<<<EOD
        </table>    
        </td>       
    </tr>
</table>
EOD;
   

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Oriental_MIS.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
}
public function specialReportsAjax1($ic_id,$from,$to){
        $report_data = $this->Home_Model->specialReports($ic_id,$from,$to);
        // echo '<pre>'; print_r($report_data);die('hello');
        $html = '';
        $length = count($report_data);
        $html .=<<<EOD
         <table cellpadding="3" border="0" cellspacing="0" class="boxtable">
                            <tr>
                                <td width="10%" class="sectionhead"><b>DATE</b></td>
                                <td width="25%" class="sectionhead"><b>NO. OF MEMBERS</b></td>
                                <td width="15%" class="sectionhead textright"><b>SUM INSURED</b></td>
                                <td width="12%" class="sectionhead textright"><b>PREMIUM</b></td>
                                <td width="8%" class="sectionhead"><b>GST @ 18</b></td>
                                <td width="10%" class="sectionhead"><b>T0TAL PREM</b></td>
                                <td width="20%" class="sectionhead"><b>POLICY NO.</b></td>
                            </tr>
EOD;
        $dates = [];
        $master_policy_nos =[];
        // $total_no_of_member = 0;
        for ($i=0; $i <= $length; $i++) { 
            if(in_array($report_data[$i]['date'], $dates)){
                $date = '';
            }else{
                $date = $report_data[$i]['date'];
                $dates[$i] = $report_data[$i]['date'];
            }
            if(in_array($report_data[$i]['master_policy_no'], $master_policy_nos)){
                $master_policy_no = '';
            }else{
                $master_policy_no = $report_data[$i-1]['master_policy_no'];
                $master_policy_nos[$i] = $report_data[$i]['master_policy_no'];
            }
            $no_of_member = $report_data[$i]['no_of_member'];
            //echo $no_of_member.'<br>';
            $total_no_of_member += $report_data[$i-1]['no_of_member'];
            $sum_insured = $report_data[$i]['sum_insured'];
            $basic_premium = $report_data[$i]['premium'];
            $total_basic_premium += $report_data[$i-1]['premium'];
            $gst = round(((18/100)*$basic_premium),2);
            $total_gst += round(((18/100)*$basic_premium),2);
            $total_premium = $basic_premium + $gst;
            $final_total_premium += $basic_premium + $gst;
           // die();
            //echo '<pre>'; print_r($dates);//die('hello');
           if((!empty($date) && $i != 0) || ($i == $length)){

            // echo $master_policy_no.'<br>';
            $html .=<<<EOD
                <tr>
                    <td></td>
                    <td>{$total_no_of_member}</td>
                    <td></td>
                    <td>{$total_basic_premium}</td>
                    <td>{$total_gst}</td>
                    <td>{$final_total_premium}</td>
                    <td>{$master_policy_no}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
EOD;
           }
           if((!empty($date))){
                $total_no_of_member = 0;
                $total_basic_premium = 0;
                $total_gst = 0;
                $final_total_premium = 0;
           }
           $html .=<<<EOD
                <tr>
                    <td>{$date}</td>
                    <td class="textright">{$no_of_member}</td>
                    <td class="textright">{$sum_insured}</td>
                    <td class="textright">{$basic_premium}</td>
                    <td class="textright"></td>
                    <td class="textright"></td>
                    <td></td>
                </tr>
EOD;
            
        }

        

        echo $html;

    	}


function dealerCmpaignList($value=''){
    $data['main_contain'] = 'admin/report/dealer_camapign_list';
    $this->load->view('admin/includes/template', $data);
}

function downloadDealerCampaignList($from,$to){
   $dealer_camapign_list = $this->Home_Model->getCampaignList($from,$to);
    // echo "<pre>"; print_r($dealer_camapign_list); echo "</pre>"; die('end of line yoyo');
   $header_value = array("Dealer Code","Insurance Manager Name","Insurance Manager Email","Insurance Manager Contact","CEO/GM Name","CEO/GM Email","CEO/GM Contact","Principal Name","Principal Email","Principal Contact","Dealer NO.","Show Room Name","Created Date");
   $main_array = array();
   array_push($main_array, $header_value);
   foreach ($dealer_camapign_list as $value) {
       $dealer_code = $value['dealer_code'];
       $insurance_name = $value['insurance_name'];
       $insurance_email = $value['insurance_email'];
       $insurance_contact = $value['insurance_contact'];
       $principle_name = $value['principle_name']; 
       $principle_email = $value['principle_email']; 
       $principle_contact = $value['principle_contact']; 
       $gm_name = $value['gm_name']; 
       $gm_email = $value['gm_email']; 
       $gm_contact = $value['gm_contact']; 
       $dealer_no = $value['dealer_no']; 
       $showroom = $value['showroom']; 
       $created_at = $value['created_at'];


       $array_value = array($dealer_code,$insurance_name,$insurance_email,$insurance_contact,$gm_name,$gm_email,$gm_contact,$principle_name,$principle_email,$principle_contact,$dealer_no,$showroom,$created_at);
       array_push($main_array, $array_value); 
   }
// echo "<pre>"; print_r($main_array); echo "</pre>"; die('end of line yoyo');
    $csvfile_name = 'dealer_campaign_list'.$from.'.csv';

    echo array_to_csv($main_array,$csvfile_name);

}

function AddRM() {
    $data['state'] = $this->Home_Model->getallstates();
    $data['main_contain'] = 'admin/report/add_rm';
    $this->load->view('admin/includes/template', $data);
}

function SubmitRMForm() {
    $post=$this->input->post();

    
    if(!empty($post)){
        $exist_rm_data = $this->Home_Model->getExistRm($post['rm_contact_no'],$post['rm_email']);

        if(!$exist_rm_data){
            $rm_info = array(
                    'name' => trim($this->input->post('rm_name')),
                    'mobile_no' => $this->input->post('rm_contact_no'),
                    'email' => trim($this->input->post('rm_email')),
                    'password' => md5(trim($this->input->post('rm_email'))),
                    'address' => trim($this->input->post('rm_address')),
                    'state' => $this->input->post('state'),
                    'city' => $this->input->post('city'),
                    'created_at' => date('Y-m-d H:i:s')
            );

            $rm_insert_id=$this->Home_Model->insertIntoTable('tvs_rm_list', $rm_info);

            if($rm_insert_id){

            // Add to tvs_admin table
            
            $rm_tvsadmin = array('first_name'=>trim($this->input->post('rm_name')),
                        'created_at'=>date('Y-m-d h:i:s'),
                        'email_id'=>trim($this->input->post('rm_email')),
                        'password'=>md5(trim($this->input->post('rm_email'))),
                        'admin_role_id'=>$rm_insert_id,
                        'admin_role'=>'rm_admin',
                        'is_active'=>'1'
            );

            $rm_tvsadmin_id = $this->Home_Model->insertIntoTable('tvs_admin', $rm_tvsadmin);

                if($rm_tvsadmin_id){
                    $this->session->set_flashdata('success', 'RM Is Successfully Added.');
                    redirect('admin/rm_master');
                }
            }
        }
        else{
            $error=array();
            foreach($exist_rm_data as $val){
                if($val['email']==trim($post['rm_email'])){
                    $error[]='Rm Email '.$post['rm_email'].' already exists';
                }
                if($val['mobile_no']==$post['rm_contact_no']){
                    $error[]='Mobile No '.$post['rm_contact_no'].' already exists';
                }
            }

            $data['error']=$error;
            $data['state'] = $this->Home_Model->getallstates();
            $data['main_contain'] = 'admin/report/add_rm';
            $this->load->view('admin/includes/template', $data);            
        }
    }

}

function RM_Master() {
    $data['main_contain'] = 'admin/report/rm_master';
    $this->load->view('admin/includes/template', $data);
}

function RMMasterAjax(){
        $requestData = $_REQUEST;
        $start_date =  $requestData['start_date'];
        $end_date =  $requestData['end_date'];
        $where="";
        if($end_date!='' && $start_date!=''){
           $where = "AND (CAST(created_at AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        }
    $start = $requestData['start'];
    $length = $requestData['length'];

    $data = array();

    $result=$this->Home_Model->getRMdata($where);
    
    $i=1;

    foreach ($result as $main) {
            $row = array();
            $row[] = $i++;
            $row[] = $main->id;
            $row[] = $main->name;
            $row[] = $main->mobile_no;
            $row[] = $main->email;
            $row[] = $main->created_at;

            //$rm_name = '"'.addslashes($main->name).'"';
            $rm_name = sprintf("'%s'",$main->name);
            //print $rm_name;exit;

            $row[] = '<a class="btn btn-primary" onclick="addrmdealer('.$main->id.','.$rm_name.')">Add</a>'; 
            //$row[] = '<a class="btn btn-primary" onclick="editrmdealer('.$main->id.','.$rm_name.')">Edit</a>'; 
            $row[] = '<a class="btn btn-primary" href="'.base_url() . 'admin/editrmdealer/'.$main->id.'">Edit</a>';
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

function ImportRM(){
    $data['main_contain'] = 'admin/report/import_rm';
    $this->load->view('admin/includes/template', $data);
}

function SaveRMdata(){
    if(isset($_POST['rm_import_hd'])){
        $filename = explode(".", $_FILES['rm_import_file']['name']);

        if(end($filename) == "csv")
        {
            $handle = fopen($_FILES['rm_import_file']['tmp_name'], "r");
            $count=0;

            $column_headings=array('Dealer_id','Dealer_Name','RM_id','RM_Name','Sap_ad_code');

            
            while($data = fgetcsv($handle))
            {
                $count++;
                $data=array_map('trim',$data);
                if($count==1){
                    if($data != $column_headings){
                        echo "CSV is invalid ";exit;
                    }
                    
                }

                if($count>1)
                {
                    if($data[0]==''){
                            echo "Please Enter Dealer Id. at column A row ".$count;die;
                        
                    }
                    if (!(preg_match('/^[0-9]+$/', $data[0]))) {
                           echo "Please Enter only number at column A row ".$count;die;
                    } 

                    if($data[1]==''){
                           echo "Please Enter Dealer Name. at column B row ".$count;die;
                    }

                    if($data[2]==''){
                        echo "Please Enter Rm Id at column C row ".$count;die;
                    }
                    if (!(preg_match('/^[0-9]+$/', $data[2]))) {
                        echo "Please Enter only number at column C row ".$count;die;
                    } 

                    if($data[3]==''){
                        echo "Please Enter Rm Name at column D row ".$count;die;
                     }

                    if($data[4]==''){
                            echo "Please Enter Sap Ad Code at column E row ".$count;die;   
                    }                  
                
                                   

                 $rm_data = array('dealer_id'=>$data[0], 'dealer_name'=>$data[1], 'rm_id'=>$data[2], 'rm_name'=>$data[3],'sap_ad_code'=>$data[4]);

                  
                    $is_exist = $this->Home_Model->isrmdealerexist($data[0],$data[2]);
                    
                    if(!$is_exist){
                         $insert_res=$this->Home_Model->insertIntoTable("tvs_rm_dealers",$rm_data);
                    }
                }    
                                 
            }
        }

            fclose($handle);

            $this->session->set_flashdata('success_message', 'RM data updated successfully');
            redirect('admin/import_rm');
    }
}

function getDealers(){
   $post = $this->input->post();
   $dealer_data = $this->Home_Model->getDealers($post['rm_id']);

   echo json_encode($dealer_data);
}

function assignrmdealer(){
    $post=$this->input->post();

    if(!empty($post)){
        $is_exist = $this->Home_Model->isrmdealerexist($post['dealers'],$post['rm_id']);

        if(!$is_exist){
                 $rmdealer = array('dealer_id'=>$post['dealers'],
                      'dealer_name'=>$post['dealer_name'],
                      'rm_id'=>$post['rm_id'],
                      'rm_name'=>$post['rm_name'],
                      'sap_ad_code'=>$post['sap_ad_code'],
                      'is_active'=>1
                 );


                $insert_res=$this->Home_Model->insertIntoTable("tvs_rm_dealers",$rmdealer);

                if($insert_res){
                 $this->session->set_flashdata('success', 'Dealer Assigned Successfully');
                 redirect('admin/rm_master');
                }
        }
        else{
           $this->session->set_flashdata('success', 'Dealer Already Assigned');
           redirect('admin/rm_master'); 
        }       
    }
}

public function servicecancel_policy_ajax() {
        $admin_session=$this->session->userdata('admin_session');
        $ic_id=$admin_session['ic_id'];
        $admin_role=$admin_session['admin_role'];
        $role_id=$admin_session['admin_role_id'];

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

        $policy_data = $this->Home_Model->getRsacancelPolicyDetail($where);

        $totalFiltered = $totalData;

        $totalFiltered = $policy_data['num_rows'];
        $data = array();
        $i = 1;
        foreach ($policy_data['result'] as $main) {

            if(!empty($main['vehicle_type'])){
                    $vehicle_type = $main['vehicle_type'] ;
            }else{
                    $reg_no_arr = explode('-',$main['vehicle_registration_no']) ;
                    if(isset($reg_no_arr[2]) && !empty($reg_no_arr[2]) ){
                        $vehicle_type = "Old";
                    }else{
                        $vehicle_type = "New";
                    }
                }

            $row = array();
            $row[] = $i++;
            $row[] = $main['sold_policy_no'];
                        
            $row[] = $main['engine_no'];
            $row[] = $vehicle_type;
            $row[] = $main['chassis_no'];
            $row[] = $main['model_name'];
            $row[] = $main['sap_ad_code'];
            $row[] = $main['dealer_name'];
            $row[] = $main['customer_name'];
            $row[] = $main['plan_name'];

            $row[] = $main['zone'];
            $row[] = $main['created_date'];
            $row[] = $main['sold_policy_effective_date'];
            $row[] = $main['sold_policy_end_date'];
            $row[] = $main['city_name'];          
            $row[] = $main['state_name'];
            $row[] = $main['cancellation_reson'];
            $row[] = $main['cancellation_date'];

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




function getrmDealers(){
   $post = $this->input->post();
   $dealer_data = $this->Home_Model->getrmDealers($post['rm_id']);

   echo json_encode($dealer_data);
}

function editrmdealer($rm_id){
   
   $dealer_data = $this->Home_Model->getrmDealers($rm_id);
   //print_r($dealer_data);exit;
   $data['dealer_data'] = $dealer_data;
   $data['main_contain'] = 'admin/report/editrmdealer'; 
   $this->load->view('admin/includes/template', $data); 
}

function updatermdealer(){

    $post = $this->input->post();

    //print_R($post);exit;
    
    if(!empty($post)){
        for($i=0;$i<count($post['mapping']);$i++){
            $active=array('is_active'=>$post['active'][$i]);
            $where=array('id'=>$post['mapping'][$i]);

            $update_res=$this->Home_Model->updateTable('tvs_rm_dealers',$active,$where);
            
        }
        if($update_res){
         $this->session->set_flashdata('success', 'RM Dealer Mapping status updated successfully');
         redirect('admin/rm_master');
        }
    }
}

function getCitiesbyStateid(){
    $post = $this->input->post();

    if(!empty($post)){
       $cities = $this->Home_Model->getCitiesbyStateid($post['state_id']);
       echo json_encode($cities);
    }
}

function SapphirePolicytill30Aug(){
   $data['main_contain'] = 'admin/report/sapphire_30aug_policy'; 
   $this->load->view('admin/includes/template', $data);
}
function SapphirePolicytill30AugDownloaded(){
   $data['main_contain'] = 'admin/report/sapphire_30aug_policy_downloaded'; 
   $this->load->view('admin/includes/template', $data);
}
function Sapphire30AugPolicyAjax(){
    $requestData = $_REQUEST;
    // echo '<pre>';print_r($_POST);die('in');
    $is_downloaded = !empty($requestData['is_downloaded'])?$requestData['is_downloaded']:0;
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    // echo '<pre>';print_r($requestData);die('in');
    $sapphire_data = $this->Home_Model->getSapphirePolicyTill30($is_downloaded,$start_date,$end_date);
    $total_data = $sapphire_data['num_rows'];
    $data = array();$i=1;
    foreach ($sapphire_data['result'] as $policy) {
        switch ($policy['ic_id']) {
            case 5:
                 $pdf_btn = '<a href="' . base_url() . 'download_icici_full_policy/' . $policy['sold_id'] . '" onclick="return check(event)" class="btn btn-info" data-id="' . $policy['sold_id'] . '" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
            $pa_ic = 'ICICI Lombard';
                break;
            case 9:
                 $pdf_btn = '<a href="' . base_url() . 'download_tata_full_policy/' .$policy['sold_id']. '" onclick="return check(event)" class="btn btn-info" data-id="' . $policy['sold_id'] . '"  target="_blank"><i class="fa fa-download"></i></a><br/>' ;
            $pa_ic = 'TATA AIG';
                break;
            case 12:
                 $pdf_btn = '<a href="' . base_url() . 'download_bhartiaxa_full_policy/' .$policy['sold_id']. '"onclick="return check(event)" class="btn btn-info" data-id="' . $policy['sold_id'] . '" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
            $pa_ic = 'Bharti AXA';
                break;
            
        }
        $apolgoy_btn = '<a href="'.base_url().'download_apology_letter_pdf/'.$policy['sold_id'].'/'. $i.'" class="btn btn-info" target="_blank">Appology PDF</a>';
        $row = array();
        $row[] = $i++;
        $row[] = $policy['sap_ad_code'];
        $row[] = $policy['dealer_name'];
        $row[] = $policy['sold_policy_no'];
        $row[] = $policy['engine_no'];
        $row[] = $policy['chassis_no'];
        $row[] = $policy['make_name'];
        $row[] = $policy['model_name'];
        $row[] = $policy['plan_name'];
        $row[] = $pa_ic;
        $row[] = $policy['master_policy_no'];
        $row[] = $policy['customer_name'];
        $row[] = $policy['created_date'];
        $row[] = $pdf_btn.' '.$apolgoy_btn;

        $data[]= $row;
    }

    $json_data = array(
        "draw"=>intval(0),
        "recordsTotal"=>intval($total_data),
        "recordsFiltered"=>intval($total_data),
        "data"=>$data
    );

    echo json_encode($json_data);

}

function updateDownloadStatus(){
    $post_data = $this->input->post();
    $policy_id = $post_data['policy_id'];
    $return_data = array();
    if(!empty($policy_id)){
        $this->db->where('id',$policy_id);
        $is_updated = $this->db->update('tvs_sold_policies',array('is_mail_send'=>1));
        $return_data['status'] ='false'; 
        $return_data['msg'] ='Not Updated'; 
        if(!empty($is_updated)){
            $return_data['status'] ='true'; 
            $return_data['msg'] ='Updated'; 
        }
    }else{
        $return_data['status'] ='false'; 
        $return_data['msg'] ='Policy Id Not Found'; 
    }
    echo json_encode($return_data);
}
function tvsDashboard(){

        $data['main_contain'] = 'admin/report/tvs_dashboard';
        $this->load->view('admin/includes/template', $data);
}

function paid_service(){
    $data['main_contain'] = 'admin/report/paid_service';
    $this->load->view('admin/includes/template',$data);
}
function paid_service_ajax(){
    $requestData = $_REQUEST;
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    $where = '';
    if (!empty($start_date) && !empty($end_date)) {
            $where = "AND (DATE(tsp.sold_policy_date)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
    }else{
        $where = "AND (CAST(tsp.sold_policy_date AS DATE)) = CURDATE() " ;
    }
    $policydata = $this->Home_Model->getPaidServicePolicy($where);
    // echo "<pre>"; print_r($policydata); echo "</pre>"; die('end of line yoyo');
    $totalFiltered = $policydata['num_rows'];
    $i=1;$data = array();
    foreach ($policydata['result'] as $main) {
        $file = '<a href="' . base_url() . 'download_workshop_OICL_pdf/' .$main['sold_id']. '" class="btn btn-info" target="_blank">Download Pdf</a>';

        $row = array();
        $row[] = $i++;
        $row[] = $main['sold_policy_no'];
        $row[] = $main['master_policy_no'];
        $row[] = 'Oriental';
        $row[] = $main['engine_no'];
        $row[] = $main['chassis_no'];
        $row[] = $main['model_name'];
        $row[] = $main['sap_ad_code'];
        $row[] = $main['dealer_name'];
        $row[] = $main['customer_name'];
        $row[] = $main['plan_name'];
        $row[] = $main['policy_status'];
        $row[] = $main['zone'];
        $row[] = $main['created_date'];
        $row[] = $main['sold_policy_effective_date'];
        $row[] = $main['sold_policy_end_date'];
        $row[] = $main['pa_sold_policy_effective_date'];
        $row[] = $main['pa_sold_policy_end_date'];
        $row[] = $main['city_name'];
        $row[] = $main['state_name'];
        $row[] = $file;

        $data[] = $row; 
    }

    $json_data = array(
        "draw" => intval(0),
        "recordsTotal" =>$totalFiltered,
        "recordsFiltered" =>$totalFiltered,
        "data" =>$data
    );
    echo json_encode($json_data);
}

function wrong_punched_policies(){
    $data['main_contain'] = 'admin/report/wrong_punched_policies';
    $this->load->view('admin/includes/template', $data);
}

function wrong_punched_policies_ajax(){
    $requestData = $_REQUEST;

    $start_date =  $requestData['start_date'];
    $end_date =  $requestData['end_date'];
    
    $where="";
    if($end_date!='' && $start_date!=''){
           $where = "AND (CAST(created_at AS DATE)) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
    }

    $data = array();

    $result=$this->Home_Model->getWrongpunchedpolicies($where);

    $i=1;

    foreach ($result as $main) {
            $row = array();

            $row[] = $i++;
            $row[] = $main->sold_policy_no;
            $row[] = $main->dealer_name;
            $row[] = $main->engine_no;
            $row[] = $main->chassis_no;
            $row[] = $main->mobile_no;
            $row[] = $main->is_link_opened;
            $row[] = $main->created_at;

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

function Workshop_frameno(){
    $data['main_contain'] = 'admin/report/import_frameno';
    $this->load->view('admin/includes/template', $data);
}

function upload_frameno_file(){
    ini_set('max_execution_time', '1000');
    if(isset($_FILES['import_frameno_file'])){
        $filename = explode(".", $_FILES['import_frameno_file']['name']);
        if(end($filename) == "csv"){
            $csv = array();
            $lines = file($_FILES['import_frameno_file']['tmp_name'], FILE_IGNORE_NEW_LINES);
            $i=0;
            foreach ($lines as $key => $value){
                echo "<pre>"; print_r($value); echo "</pre>"; die('end of code');
                $csv = str_getcsv($value);
                $insert_data[$i] = array(
                            'chassis_no'=>strtoupper($csv[0]),
                            'start_date'=>date('Y-m-d'),
                            'end_date'=>date('Y-m-d'),
                            'is_active'=>1
                        );
                if ($i > 0 && $i % 100000 == 0) {
                    ini_set('memory_limit', '1G');
                    $this->db->custom_insert_batch('workshop_tvs_vehicle_master_amit',$insert_data);
                    sleep(5);
                    }
                $i++;
            }
           // redirect('admin/Workshop_frameno');
        }
    }else{
        $this->session->set_flashdata('success_message', 'Please Upload CSV File');
        redirect('admin/Workshop_frameno');
    }
}



// function upload_frameno_file(){
//     // echo "<pre>"; print_r($_FILES);
//     // print_r($_POST);
//     //  echo "</pre>"; 
//     ini_set('memory_limit', '512M');
//     ini_set('max_execution_time', '1000');
//     if(isset($_FILES['import_frameno_file'])){
//         $filename = explode(".", $_FILES['import_frameno_file']['name']);
//         // echo '<pre>'; print_r($filename);die('here');
//         if(end($filename) == "csv")
//         {
//             $handle = fopen($_FILES['import_frameno_file']['tmp_name'], "r");
//             $count=0;

//             $column_headings=array('frame_no');
//             $file = new SplFileObject($_FILES['import_frameno_file']['tmp_name'], 'r');
//             $file->seek(PHP_INT_MAX);
//             $lenght = $file->key() + 1;//exit;
            
//             while(($data = fgetcsv($handle,$lenght, ",")) !== FALSE){ 
//            // echo 'hello moto'.PHP_EOL;  
//                 // echo '<pre>'; print_r($data);
//                 $data=array_map('trim',$data);
//                 if(!empty($data[0])){
//                     $exist_frame = $this->Home_Model->checkExistFrameNo($data[0]);
//                     if(empty($exist_frame)){
//                         $insert_data[$count] = array(
//                             'chassis_no'=>strtoupper($data[0]),
//                             'start_date'=>date('Y-m-d H:i:s'),
//                             'end_date'=>date('Y-m-d H:i:s'),
//                             'is_active'=>1
//                         );
//                     }
//                 }
//                // echo '<pre>'; print_r($insert_data);
               
//                 $count++;                  
//             }
//              echo '<pre>'; print_r(count($insert_data));die('hello moto');
//             //$insert = $this->db->insert_batch("workshop_tvs_vehicle_master",$insert_data);
//             if($insert){    
//                 redirect('admin/Workshop_frameno');
//             }
           
//         }else{
//                 $this->session->set_flashdata('success_message', 'Please Upload CSV File');
//                 redirect('admin/Workshop_frameno');
//         }

//             fclose($handle);

            
//     }
// }

function pending_renewal_policies(){
    $data['main_contain'] = 'admin/report/pending_renewal_policies';
    $this->load->view('admin/includes/template', $data);

}

function pending_renewal_policy_ajax(){
    $requestData = $_REQUEST;
    $draw = $_POST['draw'];
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    $engine_no = $this->input->post('engine_no');
    $dealer_code = $this->input->post('dealer_code');
    $where = '';
    // if(!empty($start_date) && !empty($end_date)){
    //     $where = "AND DATE(tsp.`sold_policy_end_date`) BETWEEN DATE('$start_date') AND DATE('$end_date')";
    // }else {
    //     $where = "AND DATE(tsp.`sold_policy_end_date`) = SUBDATE(CURRENT_DATE, 1)";
    // }
    $policy_data = $this->Home_Model->getPendingRenewal($dealer_code,$engine_no,$start_date,$end_date);
    // echo "<pre>"; print_r($policy_data); echo "</pre>";die;
    $totalFiltered = $policy_data['num_rows'];$i=1;$data = array();
    $totalrecord = $policy_data['num_rows'];
    foreach ($policy_data['result'] as $value) {
        $rsa_ic='';$pa_ic='';
        if($value['rsa_ic_id']==1){
            $rsa_ic = "Bharti Assist";
        }elseif($value['rsa_ic_id']==11){
            $rsa_ic = "MY TVS";
        }
        switch ($value['ic_id']) {
            case 2:
                // ICICI
                $pa_ic = "Kotak";
                break;
            case 5:
                // ICICI
                $pa_ic = "ICICI Lombard";
                break;
            case 7:
                // HDFC
                $pa_ic = "HDFC";
                break;
            case 8:
                // Reliance
                $pa_ic = "Reliance";
                break;
            case 9:
                // TATA
                $pa_ic = "TATA";
                break;
            case 10:
                // Oriental
                $pa_ic = "Oriental";
                break;
            case 12:
                // Bharti Axa
                $pa_ic = "Bharti Axa";
                break;
            case 13:
                // Liberty
                $pa_ic = "Liberty";
                break;
            
        }
        if($value['is_send']==1 && $value['sms_type']=='buy_renew_policy'){
            $sms_status ='sms sent';
        }else{
            $sms_status ='not sent';
        }
        $row = array();
        $row[] = '<input type="checkbox" name="check_box[]" id="policy_'.$value['sold_id'].'" class="checkbox_data checkedAll" value="'.$value['sold_id'].'"> ';
        $row[] = $i++;
        $row[] = $value['sold_policy_no'];
        $row[] = $value['master_policy_no'];
        $row[] = $rsa_ic;
        $row[] = $pa_ic;
        $row[] = $value['engine_no'];
        $row[] = $value['chassis_no'];
        $row[] = $value['model_name'];
        $row[] = $value['sap_ad_code'];
        $row[] = $value['dealer_name'];
        $row[] = $value['customer_name'];
        $row[] = $value['plan_name'];
        $row[] = $value['created_date'];
        $row[] = $value['sold_policy_effective_date'];
        $row[] = $value['sold_policy_end_date'];
        $row[] = $value['pa_sold_policy_effective_date'];
        $row[] = $value['pa_sold_policy_end_date'];
        $row[] = $value['city_name'];
        $row[] = $value['state_name'];
        $row[] = $sms_status;
// echo "<pre>"; print_r($row); echo "</pre>";
        $data[] = $row;

        
    }
// echo "<pre>"; print_r($data); echo "</pre>";die;
    $json_data = array(
        'draw' => intval($draw),
        'recordsTotal' =>$totalrecord,
        'recordsFiltered' => $totalFiltered,
        'data' => $data
    );
    echo json_encode($json_data);
}

function flash_report(){
    $select_date='';
    if(!empty($_REQUEST) && isset($_REQUEST['select_date'])){
        $select_date = date('Y-m-d',strtotime($_REQUEST['select_date']));
    }
    else{
        $select_date=date('Y-m-d');
    }

    $data['paicpolicycounts']=$this->Home_Model->getpaicpolicycounts($select_date);
    
    $data['main_contain'] = 'admin/report/flash_report';
    $this->load->view('admin/includes/template', $data);
}  

function tvsdashboard_report(){
    $select_date='';
    if(!empty($_REQUEST) && isset($_REQUEST['select_date'])){
        $select_date = date('Y-m-d',strtotime($_REQUEST['select_date']));
    }
    else{
        $select_date=date('Y-m-d');
    }

    $data['zonepolicycounts']=$this->Home_Model->getzonewisepolicycounts($select_date);
    
    $data['main_contain'] = 'admin/report/tvsdashboard_report';
    $this->load->view('admin/includes/template', $data);
}

function consolidated_report(){
   $select_date='';
    if(!empty($_REQUEST) && isset($_REQUEST['select_date'])){
        $select_date = date('Y-m-d',strtotime($_REQUEST['select_date']));
    }
    else{
        $select_date=date('Y-m-d');
    }

    $data['zoneallpolicycounts']=$this->Home_Model->getzoneallpolicycounts($select_date);
    //$data['totalallpolicies']=$this->Home_Model->gettotalofallpolicies($select_date);

    
    $data['main_contain'] = 'admin/report/consolidated_report';
    $this->load->view('admin/includes/template', $data);
}

function sendsms_checkedboxdata(){
    $this->load->helper('common_helper');
    $post_data = $this->input->post();
        // echo "<pre>"; print_r($post_data); echo "</pre>"; die('end of line yoyo');

    // $checked_policy_id = $post_data['check_box_val'];
    $mobile = $post_data['mobile'];
    $checked_policy_id = json_decode($post_data['check_box_val'],TRUE);
    // echo "<pre>";print_r(array_count_values($checked_policy_id));
  
    // echo "<pre>"; print_r($checked_policy_id); echo "</pre>"; die('end of line yoyo');
    
            foreach ($checked_policy_id as $value) {
                // echo "<pre>"; print_r($value); echo "</pre>"; die('end of line yoyo');
                $policy_data = $this->Home_Model->getCustomerPolicydata($value);
                $customer_name = $policy_data['customer_name'];
                $sold_policy_end_date = $policy_data['sold_policy_end_date'];
                $policy_id = $value;
                $sold_policy_no = $policy_data['sold_policy_no'];
                $model_name = $policy_data['model_name'];
                $buynow = base_url('BuyRenewRSAPolicy').'/'.$policy_id;
                $message = "Dear $customer_name sir,
                TVS RSA policy with personal accident cover for $model_name has expired on $sold_policy_end_date; to renew your policy click on $buynow OR contact your TVS dealer.";
                $mobile_no = (!empty($mobile))?$mobile:$policy_data['mobile_no'];
                // echo $mobile_no;die(' mobile_no');
                // $mobile_no = '8898188910';
                $output = sendTVSRSAsms($mobile_no,$message);
                if($output){
                    $insert = array(
                                'dealer_code'=> 98765,
                                'policy_id'=> $policy_id,
                                'sms_type' => 'buy_renew_policy',
                                'is_send' => 1,
                                'date' => date('Y-m-d'),
                                'created_at' => date('Y-m-d H:i:s')
                            );
                            $insert_id = $this->Home_Model->insertIntoTable('send_sms_status',$insert);
                            if(!empty($insert_id)){
                                $status = 'true';
                            }
                }        
            }
    

    echo json_encode($status);
}  

function todays_renewal_policies(){
    $data['main_contain'] = 'admin/report/todays_renewal_policies';
    $this->load->view('admin/includes/template', $data);
}

function todays_renewd_policy_ajax(){
   $requestData = $_REQUEST;
    $draw = $_POST['draw'];
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    $where = '';
    if(!empty($start_date) && !empty($end_date)){
        $where = "AND DATE(tsp.`created_date`) BETWEEN DATE('$start_date') AND DATE('$end_date')";
    }else {
        $where = "AND DATE(tsp.`created_date`) = curdate()";
    }
    $policy_data = $this->Home_Model->getRenewedPolicies($where);
    // echo "<pre>"; print_r($policy_data); echo "</pre>";die;
    $totalFiltered = $policy_data['num_rows'];$i=1;$data = array();
    $totalrecord = $policy_data['num_rows'];
    foreach ($policy_data['result'] as $value) {
        $dealer_data = $this->Home_Model->getRowDataFromTable('tvs_dealers',['id'=>$value['user_id']]);
        $rsa_ic='';$pa_ic='';
        if($value['rsa_ic_id']==1){
            $rsa_ic = "Bharti Assist";
        }elseif($value['rsa_ic_id']==11){
            $rsa_ic = "MY TVS";
        }
        
         if(!empty($value['payment_detail_id'])){
            $policy_through = 'online pytm';
         }else{
            $policy_through = 'offline';
         }   
        
        $row = array();
        $row[] = $i++;
        $row[] = $value['sold_policy_no'];
        $row[] = $value['master_policy_no'];
        $row[] = $rsa_ic;
        $row[] = $value['name'];
        $row[] = $value['engine_no'];
        $row[] = $value['chassis_no'];
        $row[] = $value['model_name'];
        $row[] = $dealer_data['sap_ad_code'];
        $row[] = $dealer_data['dealer_name'];
        $row[] = $value['customer_name'];
        $row[] = $value['plan_name'];
        $row[] = $value['created_date'];
        $row[] = $value['sold_policy_effective_date'];
        $row[] = $value['sold_policy_end_date'];
        $row[] = $value['pa_sold_policy_effective_date'];
        $row[] = $value['pa_sold_policy_end_date'];
        $row[] = $value['city_name'];
        $row[] = $value['state_name'];
        $row[] = $policy_through;
// echo "<pre>"; print_r($row); echo "</pre>";
        $data[] = $row;

        
    }
// echo "<pre>"; print_r($data); echo "</pre>";die;
    $json_data = array(
        'draw' => intval($draw),
        'recordsTotal' =>$totalrecord,
        'recordsFiltered' => $totalFiltered,
        'data' => $data
    );
    echo json_encode($json_data);
}

function serial_no_of_apology_letter(){
    $data['main_contain'] = 'admin/report/serial_no_apology_letter'; 
    $this->load->view('admin/includes/template', $data);   
}

function serial_no_apology_letter_ajax(){
    $requestData = $_REQUEST;
    // echo '<pre>';print_r($requestData);die('in');
    $sapphire_data = $this->Home_Model->getApologyPolicy();
    // echo '<pre>';print_r($sapphire_data);die('in');
    $total_data = $sapphire_data['num_rows'];
    $data = array();$i=1;
    foreach ($sapphire_data['result'] as $policy) {
        switch ($policy['ic_id']) {
            case 5:
                 $pdf_btn = '<a href="' . base_url() . 'download_icici_full_policy/' . $policy['sold_id'] . '" onclick="return check(event)" class="btn btn-info" data-id="' . $policy['sold_id'] . '" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
            $pa_ic = 'ICICI Lombard';
                break;
            case 9:
                 $pdf_btn = '<a href="' . base_url() . 'download_tata_full_policy/' .$policy['sold_id']. '" onclick="return check(event)" class="btn btn-info" data-id="' . $policy['sold_id'] . '"  target="_blank"><i class="fa fa-download"></i></a><br/>' ;
            $pa_ic = 'TATA AIG';
                break;
            case 12:
                 $pdf_btn = '<a href="' . base_url() . 'download_bhartiaxa_full_policy/' .$policy['sold_id']. '"onclick="return check(event)" class="btn btn-info" data-id="' . $policy['sold_id'] . '" target="_blank"><i class="fa fa-download"></i></a><br/>' ;
            $pa_ic = 'Bharti AXA';
                break;
            
        }
        $apolgoy_btn = '<a href="'.base_url().'download_apology_letter_pdf/'.$policy['sold_id'].'" class="btn btn-info" target="_blank">Appology PDF</a>';
        $row = array();
        $row[] = $i++;
        $row[] = $policy['customer_name'];
        $row[] = $policy['addr1'].' '.$policy['addr2'];
        $row[] = $policy['city_name'];
        $row[] = $policy['pincode'];
        $row[] = '';
        
        // $row[] = $pdf_btn.' '.$apolgoy_btn;

        $data[]= $row;
    }

    $json_data = array(
        "draw"=>intval(0),
        "recordsTotal"=>intval($total_data),
        "recordsFiltered"=>intval($total_data),
        "data"=>$data
    );

    echo json_encode($json_data);
}

function pendingDealerPayment(){
    $data['main_contain'] = 'admin/report/pending_dealer_payment';
    $this->load->view('admin/includes/template', $data);
}

function pendingDealerPaymentAjax(){
   $admin_session=$this->session->userdata('admin_session');
   $ic_id=$admin_session['ic_id'];
   $admin_role=$admin_session['admin_role'];
   $role_id=$admin_session['admin_role_id'];

   $dealer_data = $this->Home_Model->getPendingDealerPaymentdata();

   $data = array();
   $i = 1;


   foreach ($dealer_data['result'] as $main) {
       $row = array();
       $row[]=$i;
       $row[]=$main['dealer_name'];
       $row[]=$main['sap_ad_code'];
       $row[]=$main['banck_acc_no'];
       $row[]=$main['bank_name'];
       $row[]=$main['branch_address'];
       $row[]=$main['banck_ifsc_code'];
       $row[]=$main['total_commission'];
       $row[]='<button title="Dealer-Payment" class="btn btn-info" onclick="dealerPayment('.$main['id'].','.$main['total_commission'].')">Pay</button>';

      
       $i++;
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
    public function postalAdCard($s=0){
        $this->load->helper('common_helper');
        $customer_details = $this->Home_Model->getWrongedPolicyDetails();
        // echo '<pre>';print_r($customer_details);die('hello');
        $customer_data = array_slice($customer_details,$s,4);
        // foreach ($customer_details as $key => $customer_detail) {
        //     $customer_data[$s] = $customer_detail;
        //     $s++;
        // }
        $this->load->library('Tcpdf/Mypdf.php');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('Postal Service Card');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(7, 14, 6);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 0);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set font
        $pdf->SetFont('helvetica', '', 8);

        $total_policies = count($customer_data);
        $total_loop = ($total_policies/4);
        // echo '<pre>'; print_r($total_loop);die('hello moto');
    $details = array_chunk($customer_data, 4);
for ($i=0; $i < $total_loop; $i++) { 
    $pdf->AddPage('L', array('format' => 'A4', 'Rotate' => 0));
    // $html =  <<<EOD 
$html = <<<EOD
<style>
    .pagewrap {color: #000; font-size: 7pt; line-height:8pt;}
    .eng-font {font-family:helvetica;}
    .textcenter {text-align:center;}
    .textleft {text-align:left;}
    .textright {text-align:right;}
    .font-7{font-size: 7pt; line-height:9pt;}
    .font-8{font-size: 8pt; line-height:10pt;}
    .font-9{font-size: 9pt; line-height:11pt;}
    .font-10{font-size: 10pt; line-height:12pt;}
    .font-11{font-size: 11pt; line-height:13pt;}
    .line-height-9{line-height:9pt;}
    .line-height-10{line-height:10pt;}
    .line-height-11{line-height:11pt;}
    .line-height-12{line-height:12pt;}
    .line-height-13{line-height:13pt;}
    .line-height-14{line-height:14pt;}
    .line-height-15{line-height:15pt;}
    .line-height-16{line-height:16pt;}
    .line-height-17{line-height:17pt;}
    .line-height-18{line-height:18pt;}
    .line-height-19{line-height:19pt;}
    .line-height-20{line-height:20pt;}
    .border { border:0.2px solid #000;}
</style>

<table cellpadding="0" border="0" cellspacing="0">
    <tr>

EOD;
        
    $final_details = array_chunk($details[$i],2);
    foreach ($final_details AS $f_key => $final_detail) {
     $html .=<<<EOD
    <td width="500">
EOD;
                    // echo $c_key.'<pre>'; print_r($final_detail);
                foreach ($final_detail as $ekdam_final_detail) {
                    $cust_name = $ekdam_final_detail->cust_name;
                    $cust_address = $ekdam_final_detail->cust_address;
                    $city_name = $ekdam_final_detail->city_name;
                    $state_name = $ekdam_final_detail->state_name;
                    $pincode = $ekdam_final_detail->pincode;
           
    $html .=<<<EOD
            <table cellpadding="0" border="1" cellspacing="0" class="pagewrap" width="500">
                <tr>
                    <td width="213"><img src="assets/images/postal_card/postalcard_back_01.jpg" alt="" width="213"></td>
                    <td width="289"><table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td><img src="assets/images/postal_card/postalcard_back_02.jpg" alt="" width="289"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td width="2"></td>
                                            <td height="55" width="255">
                                                <table cellpadding="0" border="0" cellspacing="4" style="border:1px solid #000;">
                                                    <tr>
                                                        <td><b>{$cust_name}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{$cust_address},{$city_name},{$state_name}, {$city_name}-{$pincode}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td width="32"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td><img src="assets/images/postal_card/postalcard_back_04.jpg" alt="" width="289"></td>
                                        </tr>
                                    </table>
                    </td>
                </tr>
            </table>
EOD;
    }
        $html .=<<<EOD
        </td>
EOD;
}
        $html .=<<<EOD
    </tr>
</table>
EOD;

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0, '');



$pdf->AddPage('L', array('format' => 'A4', 'Rotate' => -180));

$html1 =<<<EOD
<style>
    .pagewrap {color: #000; font-size: 7pt; line-height:8pt;}
    .eng-font {font-family:helvetica;}
    .textcenter {text-align:center;}
    .textleft {text-align:left;}
    .textright {text-align:right;}
    .font-7{font-size: 7pt; line-height:9pt;}
    .font-8{font-size: 8pt; line-height:10pt;}
    .font-9{font-size: 9pt; line-height:11pt;}
    .font-10{font-size: 10pt; line-height:12pt;}
    .font-11{font-size: 11pt; line-height:13pt;}
    .line-height-9{line-height:9pt;}
    .line-height-10{line-height:10pt;}
    .line-height-11{line-height:11pt;}
    .line-height-12{line-height:12pt;}
    .line-height-13{line-height:13pt;}
    .line-height-14{line-height:14pt;}
    .line-height-15{line-height:15pt;}
    .line-height-16{line-height:16pt;}
    .line-height-17{line-height:17pt;}
    .line-height-18{line-height:18pt;}
    .line-height-19{line-height:19pt;}
    .line-height-20{line-height:20pt;}
    .border { border:0.2px solid #000;}
</style>
<table cellpadding="0" border="0" cellspacing="0">
    <tr>
        <td width="500">
            <table cellpadding="0" border="1" cellspacing="0" class="pagewrap" width="500">
                <tr>
                    <td width="223"><img src="assets/images/postal_card/postalcard_front_01.jpg" alt="" width="223"></td>
                    <td width="278"><table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td><img src="assets/images/postal_card/postalcard_front_02.jpg" alt="" width="278"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="5" border="0" cellspacing="0" height="150">
                                        <tr>
                                            <td width="12"></td>
                                            <td width="210" height="81" class="border textleft"><b>Indicosmic Capital Private Limited</b> <br><br>318, 3rd Floor, Summit-Business Bay, Behind Gurunanak Petrol Pump, Off Andheri Kurla Road, Beside Magic Bricks WEH metro stn., Andheri (E), Mumbai-400093</td>
                                            <td width="56"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td height="20"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td><img src="assets/images/postal_card/postalcard_front_06.jpg" alt="" width="278"></td>
                                        </tr>
                                    </table>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" border="1" cellspacing="0" class="pagewrap" width="500">
                <tr>
                    <td width="223"><img src="assets/images/postal_card/postalcard_front_01.jpg" alt="" width="223"></td>
                    <td width="278"><table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td><img src="assets/images/postal_card/postalcard_front_02.jpg" alt="" width="278"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="5" border="0" cellspacing="0" height="150">
                                        <tr>
                                            <td width="12"></td>
                                            <td width="210" height="81" class="border textleft"><b>Indicosmic Capital Private Limited</b> <br><br>318, 3rd Floor, Summit-Business Bay, Behind Gurunanak Petrol Pump, Off Andheri Kurla Road, Beside Magic Bricks WEH metro stn., Andheri (E), Mumbai-400093</td>
                                            <td width="56"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td height="20"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td><img src="assets/images/postal_card/postalcard_front_06.jpg" alt="" width="278"></td>
                                        </tr>
                                    </table>
                    </td>
                </tr>
            </table>
        </td>
        <td width="500">
            <table cellpadding="0" border="1" cellspacing="0" class="pagewrap" width="500">
                <tr>
                    <td width="223"><img src="assets/images/postal_card/postalcard_front_01.jpg" alt="" width="223"></td>
                    <td width="278"><table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td><img src="assets/images/postal_card/postalcard_front_02.jpg" alt="" width="278"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="5" border="0" cellspacing="0" height="150">
                                        <tr>
                                            <td width="12"></td>
                                            <td width="210" height="81" class="border textleft"><b>Indicosmic Capital Private Limited</b> <br><br>318, 3rd Floor, Summit-Business Bay, Behind Gurunanak Petrol Pump, Off Andheri Kurla Road, Beside Magic Bricks WEH metro stn., Andheri (E), Mumbai-400093</td>
                                            <td width="56"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td height="20"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td><img src="assets/images/postal_card/postalcard_front_06.jpg" alt="" width="278"></td>
                                        </tr>
                                    </table>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" border="1" cellspacing="0" class="pagewrap" width="500">
                <tr>
                    <td width="223"><img src="assets/images/postal_card/postalcard_front_01.jpg" alt="" width="223"></td>
                    <td width="278"><table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td><img src="assets/images/postal_card/postalcard_front_02.jpg" alt="" width="278"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="5" border="0" cellspacing="0" height="150">
                                        <tr>
                                            <td width="12"></td>
                                            <td width="210" height="81" class="border textleft"><b>Indicosmic Capital Private Limited</b> <br><br>318, 3rd Floor, Summit-Business Bay, Behind Gurunanak Petrol Pump, Off Andheri Kurla Road, Beside Magic Bricks WEH metro stn., Andheri (E), Mumbai-400093</td>
                                            <td width="56"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td height="20"></td>
                                        </tr>
                                    </table>
                                    <table cellpadding="0" border="0" cellspacing="0">
                                        <tr>
                                            <td><img src="assets/images/postal_card/postalcard_front_06.jpg" alt="" width="278"></td>
                                        </tr>
                                    </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
EOD;

    $pdf->writeHTML($html1, true, 0, true, 0, '');
        }

    $pdf->Output('Postal_Service_Card.pdf', 'I');   

    }

 function getDealerBankTran_id(){
  $dealer_bank_tran_id['id'] = $this->Home_Model->getDealerBankTran_id($_POST['dealer_id']);
  echo json_encode($dealer_bank_tran_id);
}

function approvedDealerPayment(){
    $data['main_contain'] = 'admin/report/approved_dealer_payment';
    $this->load->view('admin/includes/template', $data);
}

function approvedDealerPaymentAjax(){
   $dealer_data = $this->Home_Model->getApprovedDealerPaymentdata();

   $data = array();
   $i = 1;


   foreach ($dealer_data['result'] as $main) {
       $row = array();
       $row[]=$i;
       $row[]=$main['dealer_name'];
       $row[]=$main['sap_ad_code'];
       $row[]=$main['banck_acc_no'];
       $row[]=$main['bank_name'];
       $row[]=$main['banck_ifsc_code'];
       $row[]=$main['deposit_amount'];
       $row[]='Paid';
       $row[]=$main['approved_date'];
             
       $i++;
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

function paycommission(){
    $return_data = array();
    $post_data = $this->input->post();

    $dealer_bank_tran_id = $post_data['dealer_bank_tran_id'];
    $dealer_id = $post_data['dealer_id'];
    $amount = $post_data['amount'];
    $transection_type = $post_data['transaction_type'];
    $is_approved = $this->Home_Model->cheeckIsStatusApproved($post_data);

    if(empty($is_approved)){
        $data = array('total_commission'=>0);
        $where = array('dealer_id'=>$dealer_id);
        $status = $this->Home_Model->updateTable('dealer_wallet',$data,$where);
        $return_data['status'] = ($status == true)?'true':'false';
        $return_data['msg'] = 'success';

        $transaction_no = $this->getRandomNumber('16');

        if($return_data['status']){

                     $update_status_data = array(
                    'approval_status' => 'approved',
                    'approved_date' => date('Y-m-d'),
                    'bank_transaction_no' => $post_data['admin_trans_no'],
                    'transection_no' => $transaction_no
                    );
                
                $where = array('id'=>$dealer_bank_tran_id);
                $result = $this->Home_Model->updateTable('dealer_bank_transaction', $update_status_data, $where);

                $return_data['status'] == ($result == true)?'true':'false';

                if($result){
                    $data = array(
                        'dealer_id'=> $dealer_id,
                        'transection_no' => $transaction_no,
                        'transection_type'=>($transection_type == 'withdrawal')?'dr':'cr',
                        'transection_amount'=>$amount,
                        'transection_purpose'=>($transection_type == 'withdrawal')?'Wallet Debit':'Wallet Credit',
                    );
                    $this->Home_Model->insertIntoTable('dealer_transection_statement',$data);
                }

                $domainName = $_SERVER['HTTP_HOST'];
                if ($domainName != 'localhost') {
                    $mail_response = $this->PaymentMail($dealer_bank_tran_id);
                }

                $sms_response = $this->PaymentSms($dealer_bank_tran_id);

                $return_data['msg'] == ($result == true)?'status updated':'status not updated';
        }



    }
    else{
            $return_data['status'] = 'false';
            $return_data['msg'] = 'Already Approved.';
    }
        
            echo json_encode($return_data);
}

public function PaymentMail($dealer_bank_tran_id){
    $trans_data = $this->Home_Model->getDealerBankTransaction($dealer_bank_tran_id);
    $dealer_name = $trans_data['dealer_name'];
    
    $from = 'info@indicosmic.com';
    //$email = $trans_data['email'];
    $email = 'pritishko@gmail.com';
    $this->load->library('email');
    $this->email->from($from, "TVS-RSA");
    $this->email->to($email);
    $this->email->bcc('info@indicosmic.com');
    //$this->email->cc('amitdeep@indicosmic.com');
    $this->email->cc('pritishko@gmail.com');

      $msg = "Dear MR. $dealer_name,
        Your payment request is approved .
        Warm Regards,

        Team ICPL";
 
        $this->email->subject('TVS RSA Payment Request id Approved');
        $this->email->message($msg);
        if ($this->email->send()) {
            $result['status'] = true;
        } else {
            $to = $email;
            mail($to, 'test', 'Other sent option failed');
            echo $email;
            show_error($this->email->print_debugger());
            $result['status'] = false;
        }
        return $result;
}

public function PaymentSms($dealer_bank_tran_id){
    $this->load->helper('common_helper');
    $trans_data = $this->Home_Model->getDealerBankTransaction($dealer_bank_tran_id);
    $dealer_name = $trans_data['dealer_name'];
    //$mobile_no = $trans_data['mobile'];
    $mobile_no = '8291027664';

    $message = "Dear MR. $dealer_name,
        Your payment request is approved .
        Warm Regards,

        Team ICPL";

   // $output = sendTVSRSAsms($mobile_no,$message);
}

function renewed_policy_report(){
    $report_data = $this->Home_Model->getRenewedPolicyReport();
    $data['policy_ftd'] = ($report_data['policy_ftd'])?$report_data['policy_ftd']:0;
    $data['policy_mtd'] = ($report_data['policy_mtd'])?$report_data['policy_mtd']:0;
    $data['policy_ytd'] = ($report_data['policy_ytd'])?$report_data['policy_ytd']:0;
    $data['sms_ftd'] = ($report_data['sms_ftd'])?$report_data['sms_ftd']:0;
    $data['sms_mtd'] = ($report_data['sms_mtd'])?$report_data['sms_mtd']:0;
    $data['sms_ytd'] = ($report_data['sms_ytd'])?$report_data['sms_ytd']:0;
    $data['customer_activity_ftd'] = ($report_data['customer_activity_ftd'])?$report_data['customer_activity_ftd']:0;
    $data['customer_activity_mtd'] = ($report_data['customer_activity_mtd'])?$report_data['customer_activity_mtd']:0;
    $data['customer_activity_ytd'] = ($report_data['customer_activity_ytd'])?$report_data['customer_activity_ytd']:0;
    $data['main_contain'] = 'admin/report/renewed_policy_report';
    $this->load->view('admin/includes/template', $data);
}


}