<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RM_Reports extends CI_Controller {

		public function __construct() {
        Parent::__construct();
        $this->load->model('Home_Model');
        $this->load->model('RM_Model');
        $this->load->library('database_library');
        $this->load->helper('csv');
        if (!empty($this->session->userdata('admin_session'))) {
            return true;
        } else {
            redirect('admin');
        }
    }


    public function RMDashboard(){
        $admin_session = $this->session->userdata('admin_session');
        $rm_id = $admin_session['admin_role_id'];
        $all_policy_details = $this->RM_Model->RMSoldPolicies($rm_id);
        $data['last_3_months_policies'] = $this->RM_Model->RMlast3MonthsPolicies($rm_id);
        $data['limitless_policies'] = $this->RM_Model->RMLimitlessPolicies($rm_id);
       // echo '<pre>';print_r($data['last_3_months_policies']);exit;
        // echo '<pre>'; print_r($all_policy_details);die('policiie');

        foreach ($all_policy_details as $value) {
           
            switch($value['ic_id']){
                case 1:
                     // *****BHATI ASSIST RSA****
                    $data['todays_bharti_policies'] = $value['todays_policies'];
                    $data['total_bharti_policies'] = $value['total_policies'];
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
                    
                    break;
                case 11:
                    // **MYTVS**
                    $data['todays_mytvs_policies'] = $value['todays_policies'];
                    $data['total_mytvs_policies'] = $value['total_policies'];
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
        $data['total_wallet_balance'] = $this->RM_Model->getRMTotalWalletBalance($rm_id);
        
       //Todays Total Logged In Dealers
        //echo '<pre>'; print_r($data);die('data');
        $data['main_contain'] = 'admin/rm_report/rm_template';
            $this->load->view('admin/includes/template', $data);
    }

    public function RMActiveDealers(){
    	$data['main_contain'] = 'admin/rm_report/rm_dealer_master';
        $this->load->view('admin/includes/template', $data);
    }

    public function RMDealerAjax(){
    	$admin_session = $this->session->userdata('admin_session');
    	$rm_id = $admin_session['admin_role_id'];
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


        $sql = "SELECT td.*,td.id AS tvs_dealer_id,td.`sap_ad_code` AS sap_code,COUNT(tsp.id) AS policy_count,(dw.security_amount-dw.credit_amount) AS dealer_wallet,
			tdd.* FROM tvs_dealers td INNER JOIN dealer_wallet dw ON td.id = dw.dealer_id 
			INNER JOIN tvs_rm_dealers trd ON trd.`dealer_id`=td.`id`
			LEFT JOIN tvs_dealer_documents tdd ON td.id = tdd.dealer_id 
			LEFT JOIN tvs_sold_policies tsp ON tsp.`user_id` = td.id 
			WHERE trd.`rm_id`='$rm_id' GROUP BY td.id ";
// print_r($sql);die; 
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
            $where = array('id' => $main->rsa_ic_master_id);
        	$get_rsa_ic_data = $this->Home_Model->getDataFromTable('tvs_insurance_companies',$where);
        	$row = array();
            $row[] = $i++;
            $row[] = $get_rsa_ic_data[0]['name'];
            $row[] = $main->dealer_wallet;
            $row[] = $main->dealer_code;
            $row[] = $main->sap_code;
            $row[] = $main->gst_no;
            $row[] = $main->policy_count;
            $row[] = $main->dealer_name;
            $row[] = $main->ad_name;
            $row[] = $main->mobile;
            $row[] = $main->state;
            $row[] = $main->location;
            $row[] = $main->created_at;

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

public function RMInactiveDealers(){
	$data['main_contain'] = 'admin/rm_report/rm_inactive_dealers';
    $this->load->view('admin/includes/template', $data);
}

public function RMInactiveDealerAjax(){
	$admin_session = $this->session->userdata('admin_session');
	$rm_id = $admin_session['admin_role_id'];
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


        $sql ="SELECT td.* FROM tvs_dealers td 
				LEFT JOIN dealer_bank_transaction db ON td.id = db.dealer_id 
				JOIN tvs_rm_dealers trd ON trd.`dealer_id`=td.`id`
				WHERE db.dealer_id IS NULL AND trd.`rm_id`='$rm_id'";
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
            $row = array();
            $row[] = $i++;
            $row[] = $main->dealer_code;
            $row[] = $main->dealer_name;
            $row[] = $main->sap_ad_code;
            $row[] = $main->ad_name;
            $row[] = $main->mobile;
            $row[] = $main->state;
            $row[] = $main->location;
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

public function RMDealerActivityReport(){
    $data['main_contain'] = 'admin/rm_report/rm_dealer_activity';
    $this->load->view('admin/includes/template', $data);   
}

public function RMDealerActivityAjax(){
    $admin_session = $this->session->userdata('admin_session');
    $rm_id = $admin_session['admin_role_id'];
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

       $result = $this->RM_Model->RMDealerActivityReport($rm_id);
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

public function RMLastWeekSoldPolicy(){
    $data['main_contain'] = 'admin/rm_report/rm_last_week_sold_policies';
    $this->load->view('admin/includes/template', $data); 
}

public function RmLastWeekSoldpolicyAjax(){
    $admin_session = $this->session->userdata('admin_session');
    $rm_id = $admin_session['admin_role_id'];
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

   $result = $this->RM_Model->RM_Lastweek_Soldpolicy($rm_id);
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

function RMLastSoldpolicyDate(){
    $data['main_contain'] = 'admin/rm_report/rm_last_policy_date';
    $this->load->view('admin/includes/template', $data);
}

function downloadRMLastPolicyDate($from,$to){
$admin_session = $this->session->userdata('admin_session');
$rm_id = $admin_session['admin_role_id'];
$heading_array = array("Dealer Code","Last Policy Date");
$main_array = array();
array_push($main_array, $heading_array);
$lastpolicydate = $this->RM_Model->getRMLastPolicydate($from,$to,$rm_id);
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

function RMDealerWiseReport(){
    $admin_session = $this->session->userdata('admin_session');
    $rm_id = $admin_session['admin_role_id'];
    $rm_dealerwise_data = $this->RM_Model->RmDealerWiseReportData($rm_id);
    // echo "<pre>"; print_r($rm_dealerwise_data); echo "</pre>"; die('end of line yoyo');
    $data['rm_dealerwise_data'] = $rm_dealerwise_data;
    $data['main_contain'] = 'admin/rm_report/rm_dealerwise_report';
    $this->load->view('admin/includes/template', $data);
}

public function RMDealerICMpapped(){
    $admin_session = $this->session->userdata('admin_session');
    $rm_id = $admin_session['admin_role_id'];
    $heading_array = array("Dealer Name","Sap Code","Mapped IC");
    $main_array = array();
    array_push($main_array, $heading_array);
    $mapped_dealeric = $this->RM_Model->MappedRMDealerIC($rm_id);
    ini_set('memory_limit', '-1');
    foreach ($mapped_dealeric as $value) {
        $dealer_name = $value['dealer_name'];
        $sap_ad_code = $value['sap_ad_code'];
        $mapped_ic = $value['ic_name'];

        $array_value = array($dealer_name,$sap_ad_code,$mapped_ic);
        array_push($main_array, $array_value);
    }
    $csv_file_name = 'RM_Dealer_IC_Mpapped.csv';
    echo array_to_csv($main_array, $csv_file_name);

}


 function calc_data($type, $user_id, $ic_id){
                $sql = $this->db->query("SELECT d.sap_ad_code,
        SUM(case when YEAR(tsp.created_date) = YEAR(CURDATE()) then 1 else 0 end) as 'YTD',
        SUM(case when MONTH(tsp.created_date) = MONTH(CURDATE()) then 1 else 0 end) as 'MTD',
        SUM(case when DATE(tsp.created_date) = CURDATE() then 1 else 0 end) as 'FTD'
        FROM tvs_sold_policies tsp
        LEFT JOIN tvs_insurance_companies tic ON tsp.`ic_id` = tic.`id`
        LEFT JOIN tvs_dealers d ON d.id = tsp.user_id
        WHERE d.id = $user_id AND tic.id = $ic_id
        GROUP BY d.id")->result();

                switch ($type) {
                    case 'TD':
                        $data = $sql[0]->FTD;
                        break;

                    case 'MTD':
                        $data = $sql[0]->MTD;
                        break;

                    case 'YTD':
                        $data = $sql[0]->YTD;
                        break;                        
                }
                return $data;
    }

function ic_data($dealer){
    $ic_sql = $this->db->query("SELECT id, name FROM tvs_insurance_companies")->result();
    $nestedData = array();
    foreach ($ic_sql as $key) {
        $nestedData[$key->name.'(TD)'] = $this->calc_data('TD', $dealer, $key->id);
        $nestedData[$key->name.'(MTD)'] = $this->calc_data('MTD', $dealer, $key->id);
        $nestedData[$key->name.'(YTD)'] = $this->calc_data('YTD', $dealer, $key->id);
    }
     return $nestedData;
}

function test(){
    $sql = $this->db->query("SELECT d.id, d.sap_ad_code 
        FROM tvs_sold_policies tsp
        LEFT JOIN tvs_insurance_companies tic ON tsp.`ic_id` = tic.`id`
        LEFT JOIN tvs_dealers d ON d.id = tsp.user_id
        GROUP BY d.id")->result();

$final_result = array();
foreach ($sql as $key) {
    $nestedData = array();
    $nestedData['SAPCODE'] = $key->sap_ad_code;
    $nestedICData = $this->ic_data($key->id);
    $final_result[] = array_merge($nestedData,$nestedICData);
}

    print "<pre>";
    print_r($final_result);
    print "</pre>";
    exit;
}    

}