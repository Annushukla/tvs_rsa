<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class TvsRsa_Renewal extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->library('database_library');
        $this->load->model('Home_Model', 'HomeMdl');
        $this->load->model('TvsRsa_Renewal_Model', 'RenwalMdl');
        $this->load->helper('common_helper');
        $this->load->helper('encdec_paytm');
    }


    function index(){
    	$where = array('is_active'=>1,'plan_type_id'=>2);
        $plan_details = $this->HomeMdl->getDataFromTable('tvs_plans',$where);
        $where = array('is_active'=>1);
        $plan_types = $this->HomeMdl->getDataFromTable('plan_types',$where);
        $this->data['plan_details'] = $plan_details;
        $this->data['plan_types'] = $plan_types;
    	$this->load->RenewaldashboardTemplate('front/myaccount/tvsrsa_rewal', $this->data);
    }


function FetchRenewalPolicy(){
	$post = $this->input->post();
	$response_ar['policy_status'] = 'No Data';
	$policy_data = $this->RenwalMdl->getSoldPolicyBYEngineNo($post['search_engine_no']);
	if(!empty($policy_data)){
			$policy_end_date = $policy_data['sold_policy_end_date'];
			$policy_end_date_ar = explode(' ', $policy_end_date);
			$d1 = $policy_end_date_ar[0];
			// echo $d1.'  ...  ';die;
			$current_date = date('Y-m-d');
			if($d1 > $current_date){
				$response_ar['policy_status'] = 'Policy is Exist';
			}else{

				$response_ar = $policy_data;
				$registration_no = $policy_data['vehicle_registration_no'];
				$veh_reg_no = explode('-', $registration_no);
	            $response_ar['rto_name'] = $veh_reg_no[0];
	            $response_ar['rto_code1'] = $veh_reg_no[1];
	            $response_ar['rto_code2'] = $veh_reg_no[2];
	            $response_ar['reg_no'] = $veh_reg_no[3];
				$response_ar['policy_status'] = 'renewal';
			}
			// echo "<pre>"; print_r($veh_reg_no); echo "</pre>"; die(' yoyo');
	}else{
			// DMS API
			$policy_data = $this->RenwalMdl->getTvsRsaPolicyData(strtoupper($post['search_engine_no']));
			// echo "<pre>"; print_r($policy_data); echo "</pre>"; die(' yoyo');
			if(!empty($policy_data)){

				$response['engine_no'] = $policy_data['vehicle']['engine_no'];
				$response['chassis_no'] = $policy_data['vehicle']['chassis_no'];
				$response['make_name'] = $policy_data['vehicle']['make_name'];
				$response['model_id'] = $policy_data['vehicle']['model_id'];
				$response['model_name'] = $policy_data['vehicle']['model_name'];
				$response['mfg_date'] = $policy_data['vehicle']['mfg_date'];
				$response['mobile_no'] = $policy_data['insured_mobile_no'];
				$response['ic_id'] = $policy_data['insurance_company_id'];
				$response['ic_name'] = $policy_data['Insurance_Company_name'];
				$response['email'] = $insured_name = $policy_data['customer']['insured_email_id'];
				$response['insured_name'] = $insured_name = $policy_data['customer']['insured_name'];
				$name = explode(' ', $insured_name);
				$response['fname'] = $name[0];
				$response['lname'] = $name[1];
				$response['addr1'] = $policy_data['customer']['insured_address1'];
				$response['addr2'] = $policy_data['customer']['insured_address2'];
				$response['addr3'] = $policy_data['customer']['insured_address3'];
				$response['city_name'] = $policy_data['customer']['insured_city'];
				$response['state_name'] = $policy_data['customer']['insured_state'];
				$response['pincode'] = $policy_data['customer']['insured_pincode'];
				$response['nominee_full_name'] = $policy_data['customer']['insured_nominee_name'];
				$response['dob'] = $policy_data['Owner']['owner_DOB'];
				$response['gender'] = $policy_data['Owner']['Owner_Gender'];
				$response['rto_code'] = $policy_data['Owner']['rto_code'];
				$response['rto_name'] = $policy_data['Owner']['rto_name'];
				$response['nominee_age'] = $policy_data['Owner']['Nominee_Age'];
				$response['Nominee_Gender'] = $policy_data['Owner']['Nominee_Gender'];
				$response['nominee_relation'] = $policy_data['Owner']['Nominee_Relation'];
				$response['sold_policy_effective_date'] = $policy_data['policy']['policy_start_date'];
				$response['policy_end_date'] = $policy_end_date= $policy_data['policy']['policy_end_date'];
				$response['product_type_name'] = $policy_data['policy']['product_type'];
			
				$policy_end_date = date('Y-m-d', strtotime($policy_end_date));
				$policy_end_date_ar = explode(' ', $policy_end_date);
				// echo "<pre>"; print_r($policy_end_date_ar); echo "</pre>"; die('end of line yoyo');
				$d1 = $policy_end_date_ar[0];
				$current_date = date('Y-m-d');
				if($d1 > $current_date){
					$response_ar['policy_status'] = 'Policy is Exist';
				}else{
					$response_ar = $response;
					$response_ar['policy_status'] = 'renewal';
				}


			}else{
				$response_ar['policy_status'] = 'No Response';
			}
	}
	echo json_encode($response_ar);
	// echo "<pre>"; print_r($policy_data); echo "</pre>"; die('end of line yoyo');
}





}
