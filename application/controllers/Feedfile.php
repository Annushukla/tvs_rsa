<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedfile extends CI_Controller {

	public function __construct(){
		Parent::__construct();
		$this->load->model('Main_Model');
		$this->load->library('database_library');
		$this->load->helper('csv');	
	
		//$this->output->enable_profiler(TRUE);
		//echo "<pre> "; print_r($_SESSION); exit;
		
	}
	

	

	

	public function index(){
		//echo "<pre>"; print_r($this->session->userdata('admin_session')['user_image']); exit;
		$data['main_contain'] = 'admin/feedfile/index';
		$this->load->view('admin/includes/template',$data);
	}

	 function downloadFeedFile($from,$to)
	{


/*$array_val = array($id,$category_id,$title,$first_name,$middle_name,$last_name,$fatherfull_name,$mobile_number,$email,$gender,$marital_status,$pan,$adhar_card,$date_of_birth,$occupation,$correspondenceaddress_1,$correspondenceaddress2,$correspondence_address3,$correspondencestate,$correspondencecity,$correspondencepincode,$education,$annualincome,$createdat,$planname,$userid,$policyno,$mastercertificationno,$policydate,$policystartdate,$policyenddate,$policyriskdate,$amount,$gstamount,$gstpercentage,$totalamount,$waitingperiod,$preexistingd);
	*/	
    $heading_array = array("id","category id","title","staff_id_no","first_name","middle_name","last_name","fatherfull_name","mobile_number","email","gender","marital status","pan","adhar card","date of birth","occupation","correspondenceaddress1","correspondenceaddress2","correspondenceaddress3","correspondencestate","correspondence city","correspondencepincode","education","monthlyincome","created at","plan name","user id","master certification no","policy no","policy creation date","policy risk date","policy end date","amount","gst amount","gst percentage","total amount","waiting period","pre existing d","nominee_relation","nominee_name");
	$main_array1 = array();
	array_push($main_array1, $heading_array);

		  	$query = $this->db->query("select hc_sold_policies.id as sold_policy_id,
hc_sold_policies.policy_no,hc_sold_policies.master_certification_no,hc_sold_policies.plan_detail_id,
hc_sold_policies.payment_detail_id,hc_sold_policies.policy_date,hc_sold_policies.policy_start_date,
hc_sold_policies.policy_end_date,hc_sold_policies.policy_risk_date,
hc_sold_policies.64b_status,hc_sold_policies.clearance_date
 ,hc_customers .* 
from  hc_customers , hc_sold_policies where  hc_customers.id =hc_sold_policies.customer_id and 
date(hc_sold_policies.policy_date) >= '$from' AND date(hc_sold_policies.policy_date) <= '$to'  
");
			$row = $query->num_rows(); 
			$result = $query->result();	
/*		echo "<pre>";	print_r($result);exit();
*/				foreach($result as $res)
				{
					
				$query = $this->db->query("select (select name from hc_plan where id=hpd.plan_id)as plan_name,
							 (select name from hc_plan_age where id=hpd.plan_age_id)as plan_age_name,
							 (select name from hc_plan_covers where id=hpd.plan_cover_id)as plan_cover_name,
							 hpd.* from hc_plan_details hpd where hpd.id='".$res->plan_detail_id."'");
            				$row = $query->num_rows(); 
						$result_plan_details = $query->result();

              

					$id=$res->id;
					$category_id=$res->category_id;
					$title=$res->title;  
					$first_name=$res->first_name;
					$middle_name =$res->middle_name; 
					$last_name =$res->last_name;
					$fatherfull_name=$res->father_full_name;
					$mobile_number=$res->mobile_number;
					$email=$res->email;
					$gender=$res->gender;
					$marital_status=$res->marital_status;
					$pan=$res->pan;
					$adhar_card=$res->adhar_card;
					$date_of_birth=$res->date_of_birth;
					$occupation=$res->occupation;
					$correspondenceaddress_1=$res->correspondence_address_1;
					$correspondenceaddress2=$res->correspondence_address_2;
					$correspondence_address3=$res->correspondence_address_3;
					$correspondencestate=$res->correspondence_state;
					$correspondencecity=$res->correspondence_city;
					$correspondencepincode=$res->correspondence_pincode;
					$education=$res->education;
					$month_income=$res->month_income;
					$createdat=$res->created_at;
					$nominee_name=$res->nominee_name;
					$nominee_relation=$res->nominee_relation;
					$staff_id_no=$res->staff_id_no;
                    
					$planname=$result_plan_details[0]->plan_name;
                    //print_r($result_plan_details); die();
					$userid="";
					$policyno=$res->policy_no;
					$mastercertificationno=$res->master_certification_no;
					$plan_cover_name=$result_plan_details[0]->plan_cover_name;
					$policydate=$res->policy_date;
					$policystartdate=$res->policy_start_date;
					$policyenddate=$res->policy_end_date;
					$policyriskdate=$res->policy_risk_date;
					$amount=$result_plan_details[0]->amount;
					$gstamount=$result_plan_details[0]->gst_amount;
					$gstpercentage=$result_plan_details[0]->gst_percentage;
					$totalamount=$result_plan_details[0]->total_amount;
					$waitingperiod=$result_plan_details[0]->cooling_period;
					$preexistingd=$result_plan_details[0]->pre_existing_d;

					$array_val = array($id,$category_id,$title,$staff_id_no,$first_name,$middle_name,$last_name,$fatherfull_name,$mobile_number,$email,$gender,$marital_status,$pan,$adhar_card,$date_of_birth,$occupation,$correspondenceaddress_1,$correspondenceaddress2,$correspondence_address3,$correspondencestate,$correspondencecity,$correspondencepincode,$education,$month_income,$createdat,$planname,$userid,$policyno,$mastercertificationno,$policydate,$policyriskdate,$policyenddate,$amount,$gstamount,$gstpercentage,$totalamount,$waitingperiod,$preexistingd,$nominee_relation,$nominee_name);
					array_push($main_array1, $array_val); 
			
				}


			
				$csv_file_name = "feed-file-".date('y-m-d').".csv";
		        echo array_to_csv($main_array1,$csv_file_name);
		
	}
	

	

	
}

