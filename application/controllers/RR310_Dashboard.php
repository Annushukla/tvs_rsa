<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

Class RR310_Dashboard extends CI_Controller {

	public function __construct() {
    	parent::__construct();
    	$this->load->model('Home_Model');
	}

	function RR310NewPolicies(){
		$data['main_contain'] = 'admin/report/rr10_new_policy';
        $this->load->view('admin/includes/template', $data);
	}

	 function RR310NewPolicyAjax() {
        $requestData = $_REQUEST;
        $sql = "SELECT 
				tsp.`engine_no`,tsp.`chassis_no`,tsp.`sold_policy_no`,tsp.`created_date`,tsp.`sold_policy_effective_date`,
				tsp.`sold_policy_end_date`,tsp.`model_name`,tsp.`sold_policy_price_with_tax`,tsp.`sold_policy_price_without_tax`,tsp.`id` AS sold_id,CONCAT(tcs.`fname`,' ',tcs.`lname`) AS customer_name,CONCAT(tcs.`fname`, ' ', tcs.`lname`) AS customer_name,tp.`dealer_commission`,tp.`gst_amount`,tp.`sum_insured`,tp.`plan_code`,td.`sap_ad_code` ,td.`dealer_name`,tsp.`plan_name`
				FROM
				tvs_sold_policies tsp 
				INNER JOIN tvs_customer_details tcs 
				ON tsp.`customer_id` = tcs.`id` 
				INNER JOIN tvs_plans tp 
				ON tsp.`plan_id` = tp.`id` 
				INNER JOIN tvs_dealers td 
				ON tsp.`user_id` = td.`id`  
				WHERE tsp.policy_status_id IN (3,4) AND tsp.`rsa_ic_id`=11 AND tsp.`ic_id`=0
				AND tsp.`plan_name`='PREMIUM NEW' 
				#AND (CAST(tsp.sold_policy_date AS DATE)) = CURDATE()   
				AND tsp.user_id != 0 
				ORDER BY tsp.`id` DESC";
        $query = $this->db->query($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;   
        $result = $query->result();
        $data = array();
        $i = 1;
        foreach ($result as $main) {
            $nestedData = array();
            $nestedData[] = $i++;
            $nestedData[] = $main->sold_policy_no;
            $nestedData[] = $main->engine_no;
            $nestedData[] = $main->chassis_no;
            $nestedData[] = $main->model_name;
            $nestedData[] = $main->sap_ad_code;
            $nestedData[] = $main->dealer_name;
            $nestedData[] = $main->customer_name;
            $nestedData[] = $main->plan_name;
            $nestedData[] = $main->sold_policy_price_without_tax;
            $nestedData[] = $main->gst_amount;
            $nestedData[] = $main->sold_policy_price_with_tax;
            $nestedData[] = $main->sold_policy_effective_date;
            $nestedData[] = $main->sold_policy_end_date;
            $nestedData[] = $main->created_date;
            $nestedData[] = '';
            
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

    function RR310RenewPolicy(){
    	$data['main_contain'] = 'admin/report/rr10_renew_policy';
        $this->load->view('admin/includes/template', $data);
    }

    function RR310RENewPolicyAjax(){
    	$requestData = $_REQUEST;
        $sql = "SELECT 
				tsp.`engine_no`,tsp.`chassis_no`,tsp.`sold_policy_no`,tsp.`created_date`,tsp.`sold_policy_effective_date`,
				tsp.`sold_policy_end_date`,tsp.`model_name`,tsp.`sold_policy_price_with_tax`,tsp.`sold_policy_price_without_tax`,tsp.`id` AS sold_id,CONCAT(tcs.`fname`,' ',tcs.`lname`) AS customer_name,CONCAT(tcs.`fname`, ' ', tcs.`lname`) AS customer_name,tp.`dealer_commission`,tp.`gst_amount`,tp.`sum_insured`,tp.`plan_code`,td.`sap_ad_code` ,td.`dealer_name`,tsp.`plan_name`
				FROM
				tvs_sold_policies tsp 
				INNER JOIN tvs_customer_details tcs 
				ON tsp.`customer_id` = tcs.`id` 
				INNER JOIN tvs_plans tp 
				ON tsp.`plan_id` = tp.`id` 
				INNER JOIN tvs_dealers td 
				ON tsp.`user_id` = td.`id`  
				WHERE tsp.policy_status_id IN (3,4) AND tsp.`rsa_ic_id`=11 AND tsp.`ic_id`=0
				AND tsp.`plan_name`='PREMIUM RENEWAL'
				#AND (CAST(tsp.sold_policy_date AS DATE)) = CURDATE()   
				AND tsp.user_id != 0 
				ORDER BY tsp.`id` DESC";
        $query = $this->db->query($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;   
        $result = $query->result();
        $data = array();
        $i = 1;
        foreach ($result as $main) {
            $nestedData = array();
            $nestedData[] = $i++;
            $nestedData[] = $main->sold_policy_no;
            $nestedData[] = $main->engine_no;
            $nestedData[] = $main->chassis_no;
            $nestedData[] = $main->model_name;
            $nestedData[] = $main->sap_ad_code;
            $nestedData[] = $main->dealer_name;
            $nestedData[] = $main->customer_name;
            $nestedData[] = $main->plan_name;
            $nestedData[] = $main->sold_policy_price_without_tax;
            $nestedData[] = $main->gst_amount;
            $nestedData[] = $main->sold_policy_price_with_tax;
            $nestedData[] = $main->sold_policy_effective_date;
            $nestedData[] = $main->sold_policy_end_date;
            $nestedData[] = $main->created_date;
            $nestedData[] = '';
            
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
}