<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Renew_only_rsa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('database_library');
        $this->load->model('Home_Model', 'HomeMdl');
        $this->load->helper('common_helper');
        isUserLoggedIn();
    }

    function index() {
        $user_session = $this->session->userdata('user_session');
        //plans
        $where = array('is_active'=>1,'id'=>66);
        $plan_details = $this->HomeMdl->getRowDataFromTable('tvs_plans',$where);
// echo "<pre>"; print_r($plan_details); echo "</pre>"; die('end of line yoyo');

        //plan types
        $where = array('id'=>1);
        $plan_types = $this->HomeMdl->getDataFromTable('plan_types',$where);

        $this->data['plan_details'] = $plan_details;
        $this->data['plan_types'] = $plan_types;
        $this->data['policyid'] = $policyid;
        
        if(!empty($user_session['dealer_code'])){
            $this->load->dashboardTemplate('front/myaccount/renew_only_rsa', $this->data);
        }else{
            redirect('');
        }
    }

    function abc(){
    	$this->load->view('pdf/rsa_pdf');
    }

    function check_onlyrsa_vehicledata_exist(){
	    $engine_no = $this->input->post('engine_no');
	    if(!empty($engine_no)){
	        $rsa_data = $this->HomeMdl->getRowDataFromTable('workshop_tvs_vehicle_master',['chassis_no'=>$engine_no]);
	        // echo "<pre>"; print_r($rsa_data); echo "</pre>"; die('end of line yoyo');
	        if(!empty($rsa_data) && $rsa_data['is_policy_punched'] !=1 ){
	            $response['message'] = "This Frame No. is Elegible for Generate Policy";
	            $response['status'] = "eligible";
	        }else{
	            $response['message'] = "This Frame No. is Not Elegible for Generate Policy";
	            $response['status'] = "noteligible";
	        }
	        
	        echo json_encode($response);

	    }
}

function checkIsAvailableForOnlyRSAPolicy(){
            $engine_no = $this->input->post('engine_no');
            $status = $this->HomeMdl->checkIsAvailableForOnlyRSAPolicy($engine_no);
            $return_data['status'] = ($status)?'true':'false';
        
         echo json_encode($return_data);
   }

function SubmitRenewOnlyRsaPolicy(){
	// echo "<pre>"; print_r($_POST); echo "</pre>"; die('end of line yoyo');
	$is_eligible = $this->input->post('is_eligible');
    if($is_eligible=='no'){
        $this->session->set_flashdata('message', 'The Given engine No. is not Elegible.');
        redirect('renew_only_rsa');
    }
    $post_data = $this->input->post();
    $engine_no_val = $post_data['chassis_no'];
    $rsa_data = $this->HomeMdl->getRowDataFromTable('workshop_tvs_vehicle_master',['chassis_no'=>$engine_no_val]);
    // echo "<pre>"; print_r($rsa_data); echo "</pre>"; die('end of line yoyo');
    if($engine_no_val != $rsa_data['chassis_no']){
        $this->session->set_flashdata('message', 'The Given engine No. is not Elegible.');
        redirect('renew_only_rsa');
    }
    $dob = date('Y-m-d',strtotime($post_data['dob']));
    $age =  date_diff(date_create($dob), date_create('today'))->y;
    if($age > 64 || $age < 18){
        $this->session->set_flashdata('message', 'Customer Age Should Not More Than 65 Or Less Than 16.');
        redirect('renew_only_rsa');
    }
      // echo "<pre>"; print_r($post_data); echo "</pre>";
    $policy_id = $post_data['policyid'];
    $city_id = $post_data['city_id'];
    $state_id = $post_data['state_id'];
    if($post_data['nominee_relation']=='other'){
        $nominee_relation = $post_data['other_relation'];
    }else{
        $nominee_relation = $post_data['nominee_relation'];
    }
    $this->load->helper('form');
    $this->load->library('form_validation');
    if (empty($policy_id)) {
        $this->form_validation->run('tvs_rsa_form');
        if ($this->form_validation->run() == FALSE) {
             $this->set_validation();
            redirect('renew_only_rsa');
        }else{
            $where = array('id'=>$post_data['only_rsa_plan']);
            $plan_details  = $this->HomeMdl->getRowDataFromTable('tvs_plans',$where);
            $user_data = $this->session->userdata('user_session');
            $dealer_code = (strlen($user_data['sap_ad_code']) >5)?$user_data['dealer_code'] : $user_data['sap_ad_code'];
            
            $is_exist = $this->HomeMdl->checkIsPolicyExist($post_data['engine_no'],$post_data['chassis_no']);
               // print $is_exist;exit;
            if($is_exist){
                $this->session->set_flashdata('message', 'Duplicate Policy To Check Visit On Certificate Section.');
                redirect('renew_only_rsa');
            }else{
            $rto_name = $this->input->post('rto_name');
            $rto_code1 = $this->input->post('rto_code1');
            $rto_code2 = $this->input->post('rto_code2');
            $reg_no = $this->input->post('reg_no');
            $final_reg_no = $rto_name . '-' . $rto_code1 . '-' . $rto_code2 . '-' . $reg_no;
            $model_id = $this->input->post('model_id');
            $dob = date("Y-m-d", strtotime($this->input->post('dob')));
            $insert_customer_detail = array(
            'fname' => $this->input->post('first_name'),
            'lname' => $this->input->post('last_name'),
            'email' => $this->input->post('email'),
            'mobile_no' => $this->input->post('mobile_no'),
            'gender' => $this->input->post('gender'),
            'dob' => $dob,
            'addr1' => $this->input->post('cust_addr1'),
            'addr2' => $this->input->post('cust_addr2'),
            'state' => $this->input->post('state_id'),
            'city' => $this->input->post('city_id'),
            'state_name' => $this->input->post('state'),
            'city_name' => $this->input->post('city'),
            'pincode' => $this->input->post('pin'),
            'nominee_full_name' => $this->input->post('nominee_full_name'),
            'nominee_relation' => $nominee_relation,
            'nominee_age' => $this->input->post('nominee_age'),
            'appointee_full_name' => $this->input->post('appointee_full_name'),
            'appointee_relation' => $this->input->post('appointee_relation'),
            'appointee_age' => $this->input->post('appointee_age'),
            'created_date' => date('Y-m-d H:i:s')
            );
            $inserted_customer_detail = $this->db->insert('tvs_customer_details', $insert_customer_detail);
            $customer_detail_last_id = $this->db->insert_id();
            if($customer_detail_last_id) {
            $result_sold = $this->GeneratePolicyNo();
            $date_result = $this->StartEndDate($post_data,$plan_details);
            $effective_date = $date_result['effective_date'];
            $end_date = $date_result['end_date'];
            $selected_date = (!empty($post_data['start_date']) && isset($post_data['start_date']) ) ? $post_data['start_date'] : date('Y-m-d');
            $pa_effective_date = date('Y-m-d', strtotime($selected_date . "0 day")). ' H:i:s';
            $pa_end_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime($selected_date . "-1 day")) . "1 year")). ' 23:59:59';
            $model_name = $this->getMakeModelName('model', $model_id);
            $transection_no = $this->getRandomNumber('16');

            $dms_response = $this->session->userdata('dms_response');
            $dms_response = !empty($dms_response)?json_encode($dms_response):'';
            $insert_data_sold = array(
            'user_id' => $user_data['id'],
            'employee_code' => $user_data['sap_ad_code'],
            'plan_id' => $plan_details['id'],
            'plan_name' => $plan_details['plan_name'],
            'customer_id' => $customer_detail_last_id,
            'sold_policy_no' => trim($result_sold['sold_policy_no']),
            'sold_policy_date' => date('Y-m-d H:i:s'),
            'sold_policy_effective_date' => $effective_date,
            'sold_policy_end_date' => $end_date,
            'pa_sold_policy_effective_date' => $pa_effective_date,
            'pa_sold_policy_end_date' => $pa_end_date,
            'sold_policy_price_with_tax' => ($plan_details['plan_amount']+$plan_details['gst_amount']),
            'sold_policy_price_without_tax' => $plan_details['plan_amount'],
            'sold_policy_tax' => '18%',
            'sold_policy_igst' => '18',
            'engine_no' => $post_data['engine_no'],
            'chassis_no' => $post_data['chassis_no'],
            'make_id' => '11',
            'model_id' => $model_id,
            'make_name' => 'TVS',
            'model_name' => $model_name,
            'vehicle_registration_no' => strtoupper($final_reg_no),
            'product_type' => 1,
            'product_type_name' => 'Two Wheeler',
            'created_date' => date('Y-m-d H:i:s'),
            'policy_status_id' => 3,
            'status' => 1,
            'transection_no' => $transection_no,
            'dms_response'=>$dms_response,
            'rsa_ic_id' => 1,
            'vehicle_type' => $post_data['vehicle_type'],
            'sold_policy_date' => date('Y-m-d H:i:s'),
            'created_date' => date('Y-m-d H:i:s')

            );
            // echo '<pre>'; print_r($insert_data_sold);die('here');
            $inserted_id = $this->HomeMdl->insertIntoTable('tvs_sold_policies',$insert_data_sold);
            // echo $this->db->last_query();die();
            if (!empty($inserted_id)) {
                $update = array(
                    'is_policy_punched' => 1,
                    'updated_date'=> date('Y-m-d H:i:s')
                );
                $where = array('chassis_no'=>$engine_no_val);
                $status = $this->HomeMdl->updateTable('workshop_tvs_vehicle_master',$update,$where);
                $domainName = $_SERVER['HTTP_HOST'];
                    if ($domainName != 'localhost' && !empty($post_data['email'])) {
                        // $this->MailSoldPolicyPdf($inserted_id);
                    }

                    $this->data['ic_pdf'] = 'OnlyRsapdfdata';
                    $this->data['inserted_id'] = $inserted_id;
                    $this->load->dashboardTemplate('front/myaccount/only_rsa_success', $this->data);                    

//if closing of inserted_id                                
            }else{ 
                die('in not insert policy data'); 
            } 
//if closing of customer_detail_last_id
            }else{ 
                die('in not insert Customer data');
            }


        }

//after form validation else closing
        }


// if empty policyid closing            
    }

}


function OnlyRsapdfdata($inserted_id){
	$this->data['rsa_policy_data']=$rsa_policy_data = $this->HomeMdl->getPolicyById($inserted_id);
  // echo "<pre>"; print_r($rsa_policy_data); echo "</pre>"; 
	$getDealerInfo = $this->HomeMdl->getDealerinfo($rsa_policy_data['user_id']);
  // echo '<pre>';print_r($getDealerInfo);die;
  $this->data['rsa_name'] = $getDealerInfo['name'];
  $this->data['rsa_logo'] = base_url($getDealerInfo['logo']);
  $this->data['rsa_address'] = $getDealerInfo['address'];
  $this->data['rsa_email'] = $getDealerInfo['email'];
  $this->data['customer_care_no'] = $getDealerInfo['toll_free_no'];
  $this->data['dealer_id'] = isset($rsa_policy_data['user_id']) ? $rsa_policy_data['user_id'] : '';
  $this->data['certificate_no'] = isset($rsa_policy_data['sold_policy_no']) ? $rsa_policy_data['sold_policy_no'] : '--';
  $this->data['vehicle_registration_no'] = isset($rsa_policy_data['vehicle_registration_no']) ? $rsa_policy_data['vehicle_registration_no'] : '--';
  $this->data['plan_name'] = isset($rsa_policy_data['plan_name']) ? strtoupper($rsa_policy_data['plan_name']) : '--';
  $plan_id = isset($rsa_policy_data['plan_id']) ? strtoupper($rsa_policy_data['plan_id']) : '--';
  $where = array(
    'id'=>$plan_id
  );
  $plan_detalis = $this->HomeMdl->getDataFromTable('tvs_plans',$where);
   // echo '<pre>'; print_r($plan_detalis);die();
  $plan_detalis = $plan_detalis[0];
  $this->data['km_covered'] = isset($plan_detalis['km_covered'])?$plan_detalis['km_covered']:'50';
  // die($km_covered);
  $this->data['sum_insured'] = isset($plan_detalis['sum_insured'])?$plan_detalis['sum_insured']:'--';
  $this->data['plan_amount']  = round($plan_detalis['plan_amount']);
  $this->data['gst_amount']  = round($plan_detalis['gst_amount']);
  $this->data['total_amount'] =  ($plan_amount + $gst_amount);
  $this->data['engine_no'] = isset($rsa_policy_data['engine_no']) ? strtoupper($rsa_policy_data['engine_no']) : '--';
  $this->data['chassis_no'] = isset($rsa_policy_data['chassis_no']) ? strtoupper($rsa_policy_data['chassis_no']) : '--';
  $this->data['created_date'] = isset($rsa_policy_data['created_at']) ? $rsa_policy_data['created_at'] : '--';
  $this->data['fname'] = isset($rsa_policy_data['fname']) ? strtoupper($rsa_policy_data['fname']) : '--';
  $this->data['lname'] = isset($rsa_policy_data['lname']) ? strtoupper($rsa_policy_data['lname']) : '--';
  $this->data['full_name_of_insured'] = $fname.' '.$lname;
  $this->data['email'] = isset($rsa_policy_data['email']) ? strtoupper($rsa_policy_data['email']) : '--';
  $this->data['mobile_no'] = isset($rsa_policy_data['mobile_no']) ? strtoupper($rsa_policy_data['mobile_no']) : '--';
  $this->data['gender'] = isset($rsa_policy_data['gender']) ? strtoupper($rsa_policy_data['gender']) : '--';
  $this->data['dob'] = isset($rsa_policy_data['dob']) ? strtoupper($rsa_policy_data['dob']) : '--';
  $this->data['addr1'] = isset($rsa_policy_data['addr1']) ? strtoupper($rsa_policy_data['addr1']) : '--';
  $this->data['addr2'] = isset($rsa_policy_data['addr2']) ? strtoupper($rsa_policy_data['addr2']) : '--';
  $this->data['state_name'] = isset($rsa_policy_data['state_name']) ? strtoupper($rsa_policy_data['state_name']) : '--';
  $this->data['city_name'] = isset($rsa_policy_data['city_name']) ? strtoupper($rsa_policy_data['city_name']) : '--';
  $this->data['sold_policy_effective_date'] = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '--';
  $this->data['sold_policy_end_date'] = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date'] : '--';
  $this->data['model_name'] = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';

  $this->load->library('Tcpdf/Tcpdf.php');
  ob_start();
   
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('RSA Certificate');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(5, 5, 5);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 5);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
  require_once(dirname(__FILE__).'/lang/eng.php');
  $pdf->setLanguageArray($l);
}

$pdf->AddPage();
// $html="<h1>Annu<h1/>";
$html = $this->load->view('pdf/rsa_pdf',$this->data, TRUE);
 if($dealer_id == 2871 || $dealer_id == 2872){
          // $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 10, 100, 200, '', '', '', true, 500);
         }
// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0, '');

// ---------------------------------------------------------

//Close and output PDF document
    $result= $pdf->Output('RSA-Certificate.pdf', 'I');
   // return $result;

	// $this->load->view('pdf/rsa_pdf',$this->data);
}

function set_validation() {
        $er_engine = form_error('engine_no');
        $er_chassis = form_error('chassis_no');
        $er_model_id = form_error('model_id');
        $er_rto_name = form_error('rto_name');
        $er_rto_code1 = form_error('rto_code1');
        $er_rto_code2 = form_error('rto_code2');
        $er_reg_no = form_error('reg_no');
        $er_first_name = form_error('first_name');
        $er_last_name = form_error('last_name');
        $er_email = form_error('email');
        $er_mobile_no = form_error('mobile_no');
        $er_cust_addr1 = form_error('cust_addr1');
        $er_cust_addr2 = form_error('cust_addr2');
        $er_pin = form_error('pin');
        $er_state = form_error('state');
        $er_city = form_error('city');
        $this->session->set_flashdata('er_engin_no', $er_engine);
        $this->session->set_flashdata('er_chassis', $er_chassis);
        $this->session->set_flashdata('er_model_id', $er_model_id);
        $this->session->set_flashdata('er_rto_name', $er_rto_name);
        $this->session->set_flashdata('er_rto_code1', $er_rto_code1);
        $this->session->set_flashdata('er_rto_code2', $er_rto_code2);
        $this->session->set_flashdata('er_reg_no', $er_reg_no);
        $this->session->set_flashdata('er_first_name', $er_first_name);
        $this->session->set_flashdata('er_last_name', $er_last_name);
        $this->session->set_flashdata('er_email', $er_email);
        $this->session->set_flashdata('er_mobile_no', $er_mobile_no);
        $this->session->set_flashdata('er_cust_addr1', $er_cust_addr1);
        $this->session->set_flashdata('er_cust_addr2', $er_cust_addr2);
        $this->session->set_flashdata('er_pin', $er_pin);
        $this->session->set_flashdata('er_state', $er_state);
        $this->session->set_flashdata('er_city', $er_city);
    }

function GeneratePolicyNo($Product_type = NULL) {
    $rsa_ic_master_id = 1;
    $where = array(
        'id' => $rsa_ic_master_id
    );
    $insurance_companies = $this->HomeMdl->getRowDataFromTableWithOject('tvs_insurance_companies', $where);
    $certificate_no_prefix = $insurance_companies->certificate_no_prefix;
    $certificate_no = $insurance_companies->certificate_no + 1;
    $certificate_no = sprintf("%'010d\n", $certificate_no);
    $data['sold_policy_no'] = $insurance_companies->certificate_no_prefix . '' . $certificate_no;
    $input_data = array(
        'certificate_no' => $certificate_no,
    );
    $this->HomeMdl->updateTable('tvs_insurance_companies', $input_data, $where);
    return $data;
}

 function StartEndDate($post_data,$plan_details){
     if($post_data['rsa_plan_type'] == 1){
        $selected_date = $post_data['start_date'] ;
        $start_date = (!empty($selected_date) && isset($selected_date) ) ? $selected_date : date('Y-m-d');
         if($plan_details['rsa_tenure'] == 2){
	            $result['effective_date'] = date('Y-m-d', strtotime($start_date . "0 day")) . ' 00:00:01';
	            $result['end_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "-1 day")) . "2 year")) . ' 23:59:59';
	            }else{
	                $result['effective_date'] = date('Y-m-d', strtotime($start_date . "0 day")) . ' 00:00:01';
	                $result['end_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "-1 day")) . "1 year")) . ' 23:59:59';
	                }
    }else if($post_data['rsa_plan_type'] == 2){
            $start_date = date('Y-m-d');
        if($plan_details['rsa_tenure'] == 2){
                $result['effective_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "0 day")) . "2 year")) . ' 00:00:01';
                $result['end_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "-1 day")) . "4 year")) . ' 23:59:59';
            }else{
                $result['effective_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "0 day")) . "2 year")) . ' 00:00:01';
                $result['end_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "-1 day")) . "3 year")) . ' 23:59:59';
            }
    }

    return $result ;
}


function getMakeModelName($type, $id){
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

function getRandomNumber($len){
        $better_token = $code=sprintf("%0".$len."d", mt_rand(1, str_pad("", $len,"9")));
        return $better_token;
    }
function check_workshop_vehicledata_exist(){
    $engine_no = $this->input->post('engine_no');
    if(!empty($engine_no)){
        $workshop_data = $this->HomeMdl->getRowDataFromTable('workshop_tvs_vehicle_master',['chassis_no'=>$engine_no]);
        // echo "<pre>"; print_r($workshop_data); echo "</pre>"; die('end of line yoyo');
        if(!empty($workshop_data) && $workshop_data['is_policy_punched'] !=1 ){
            $response['message'] = "This Frame No. is Elegible for Generate Policy";
            $response['status'] = "eligible";
        }else{
            $response['message'] = "This Frame No. is Not Elegible for Generate Policy";
            $response['status'] = "noteligible";
        }
        
        echo json_encode($response);

    }
}

function MailSoldPolicyPdf($inserted_id) {
    // $policypdf_obj = $this->DownloadWorkshopOICLPdf($inserted_id);
    // $status = $this->MailAttachments($policypdf_obj, $inserted_id);
 }


}