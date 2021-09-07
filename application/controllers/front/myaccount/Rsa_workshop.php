<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Rsa_workshop extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('database_library');
        $this->load->model('Home_Model', 'HomeMdl');
        $this->load->helper('common_helper');
        isUserLoggedIn();
    }

    function index($policyid = NULL) {
        $user_session = $this->session->userdata('user_session');
        //plans
        $where = array('is_active'=>1,'id'=>65);
        $plan_details = $this->HomeMdl->getDataFromTable('tvs_plans',$where);


        //wallet amount
        $where = array('dealer_id'=>$user_session['dealer_id']);
        $wallet_balance = $this->HomeMdl->getRowDataFromTable('dealer_wallet',$where);

        $credit_limit = ($wallet_balance['security_amount'] - $wallet_balance['credit_amount']);
        $plan_amount = ($plan_details['plan_amount'] + $wallet_balance['gst_amount']);
        $is_allow_policy = ($credit_limit < $plan_amount)?'no':'yes';
        $is_allow_policy = !empty($policyid)?'no':$is_allow_policy;
        $this->data['is_allow_policy'] = $is_allow_policy;
        //plan types
        $where = array('id'=>1);
        $plan_types = $this->HomeMdl->getDataFromTable('plan_types',$where);

        $this->data['plan_details'] = $plan_details;
        $this->data['plan_types'] = $plan_types;
        $this->data['policyid'] = $policyid;
        if(!empty($policyid)){
            $this->data['policy_data'] = $this->HomeMdl->getPolicyById($policyid);
            $veh_reg_no = explode('-', $this->data['policy_data']['vehicle_registration_no']);
            $this->data['rto_name'] = $veh_reg_no[0];
            $this->data['rto_code1'] = $veh_reg_no[1];
            $this->data['rto_code2'] = $veh_reg_no[2];
            $this->data['reg_no'] = $veh_reg_no[3];
            $this->data['engine_no'] = $this->data['policy_data']['engine_no'];
            $this->data['chassis_no'] = $this->data['policy_data']['chassis_no'];
            $this->data['pincode'] = $this->data['policy_data']['pincode'];
            $this->data['city'] = $this->data['policy_data']['city'];
            $this->data['state'] = $this->data['policy_data']['state'];
            $this->data['addr1'] = $this->data['policy_data']['addr1'];
            $this->data['addr2'] = $this->data['policy_data']['addr2'];
            $this->data['dob'] = $this->data['policy_data']['dob'];
            $this->data['gender'] = $this->data['policy_data']['gender'];
            $this->data['mobile_no'] = $this->data['policy_data']['mobile_no'];
            $this->data['email'] = $this->data['policy_data']['email'];
            $this->data['fname'] = $this->data['policy_data']['fname'];
            $this->data['lname'] = $this->data['policy_data']['lname'];
            $this->data['nominee_full_name'] = $this->data['policy_data']['nominee_full_name'];
            $this->data['nominee_relation'] = $this->data['policy_data']['nominee_relation'];
            $this->data['nominee_age'] = $this->data['policy_data']['nominee_age'];
            $this->data['appointee_full_name'] = $this->data['policy_data']['appointee_full_name'];
            $this->data['appointee_relation'] = $this->data['policy_data']['appointee_relation'];
            $this->data['appointee_age'] = $this->data['policy_data']['appointee_age'];
            $this->data['plan_type_id'] = $this->data['policy_data']['plan_type_id'];
            $this->data['plan_id'] = $this->data['policy_data']['plan_id'];
            $this->data['policy_id'] = $this->data['policy_data']['policy_id'];
            $this->data['readonly'] = 'readonly';
            $this->data['disabled'] = 'disabled';

        }
        if(!empty($user_session['dealer_code'])){
            $this->load->dashboardTemplate('front/myaccount/rsa_workshop', $this->data);
        }else{
            redirect('');
        }
    }

    public function planDetailsWorkshop(){
        $plan_type_id = $this->input->post('plan_type_id');
        //print $plan_type_id;exit;

        $where = array('plan_type_id'=>$plan_type_id,'id'=>65);
        $plan_details_arr = $this->HomeMdl->getDataFromTable('tvs_plans',$where);

        //print '<pre>';
        //print_R($plan_details_arr);exit;

        $html ='';
            $html .=<<<EOD
            <div class="plan-details cf" id="plan_details_data">

EOD;
            $html .=<<<EOD
                    <table class="table table-hover table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Plan Name</th>
                          <th scope="col">RSA Tenure</th>
                          <th scope="col">RSA Covered Kms</th>
                          <th scope="col">PA Tenure</th>
                          <th scope="col">PA Sum Insured</th>
                          <th scope="col">PA RSD <span style="color:red">*</span></th>
                          <th scope="col">Policy Price <span style="color:red">*</span></th>
                          <th scope="col">Select Plan</th>
                        </tr>
                      </thead>
                      <tbody>
EOD;
$i = 1;

            foreach ($plan_details_arr as $plan_detail) {
                // echo "<pre>"; print_r($plan_detail); echo "</pre>"; die('end of line yoyo');
                //$checked = ($plan_detail['id'] == $plan_id)?'checked':'';
                $plan_code = $plan_detail['plan_code'];
                $id = $plan_detail['id'];
                $plan_amount = $plan_detail['plan_amount'];
                $plan_name = $plan_detail['plan_name'];
                $rsa_tenure = ($plan_detail['rsa_tenure'] == 1)?'1 Year' :'1 Year';
                $km_covered = $plan_detail['km_covered'];
                $pa_tenure = ($plan_detail['pa_tenure'] == 1)?'1 Year':'1 Year';
                $pa_plan_amount = $plan_detail['sum_insured'];
                $lable = '₹ ' .$plan_code;
                $html .=<<<EOD
                        <tr>
                          <th scope="row">{$i}</th>
                          <td>{$plan_name}</td>
                          <td>{$rsa_tenure}</td>
                          <td>{$km_covered}</td>
                          <td>{$pa_tenure}</td>
                          <td>{$pa_plan_amount}</td>
                          <td>Current</td>
                          <td>{$lable}</td>
                          <td><input type="radio" name="workshop_plan" id="{$plan_code}" value="{$id}" data-plan="{$plan_amount}" checked></td>
                        </tr>
EOD;
            $i++;
            }
                
           $html .=<<<EOD
            </tbody>
                    </table>
                    <div class="col-md-12 text-left" style="position: relative;"><h5>Note :</h5></div>
                        <p style="color: black";>RSD : Risk Start Date.</p>
                        <p style="color: black";>Policy Price : Policy Price Inclusive GST.</p>
                    </div>
            </div>
EOD;

         echo $html;
    }

function GenerateWorkshopPolicy(){
    // echo "<pre>"; print_r($_POST); echo "</pre>"; die('end of line yoyo');
    $is_eligible = $this->input->post('is_eligible');
    if($is_eligible=='no'){
        $this->session->set_flashdata('message', 'The Given engine No. is not Elegible.');
        redirect('generate_policy_workshop');
    }
    $post_data = $this->input->post();
    $engine_no_val = $post_data['engine_no'];
    $workshop_data = $this->HomeMdl->getRowDataFromTable('workshop_tvs_vehicle_master',['engine_no'=>$engine_no_val]);
    // echo "<pre>"; print_r($workshop_data); echo "</pre>"; die('end of line yoyo');
    if($engine_no_val != $post_data['engine_no']){
        $this->session->set_flashdata('message', 'The Given engine No. is not Elegible.');
        redirect('generate_policy_workshop');
    }
    $dob = date('Y-m-d',strtotime($post_data['dob']));
    $age =  date_diff(date_create($dob), date_create('today'))->y;
    if($age > 64 || $age < 18){
        $this->session->set_flashdata('message', 'Customer Age Should Not More Than 65 Or Less Than 16.');
        redirect('generate_policy_workshop');
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
            redirect('generate_policy_workshop');
        }else{
            $where = array('id'=>$post_data['workshop_plan']);
            $plan_details  = $this->HomeMdl->getRowDataFromTable('tvs_plans',$where);
            $user_data = $this->session->userdata('user_session');
            $dealer_code = (strlen($user_data['sap_ad_code']) >5)?$user_data['dealer_code'] : $user_data['sap_ad_code'];
            
            $is_exist = $this->HomeMdl->checkIsPolicyExist($post_data['engine_no'],$post_data['chassis_no']);
               // print $is_exist;exit;
            if($is_exist){
                $this->session->set_flashdata('message', 'Duplicate Policy To Check Visit On Certificate Section.');
                redirect('generate_policy_workshop');
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

            $where = array('start_date'=>date('Y-m-d'));
            $master_policy_details = $this->HomeMdl->getRowDataFromTable('tvs_oriental_master_policy',$where);

            if(empty($master_policy_details)){
               $this->session->set_flashdata('message', 'Master Policy Not Found Please Contact To Tech Team.');
                redirect('generate_policy_workshop');
            }
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
            'ic_id' => 10,
            'mp_id' => $master_policy_details['id'],
            'master_policy_no' => $master_policy_details['master_policy_no'],
            'mp_start_date' => $master_policy_details['mp_start_date'],
            'mp_end_date' => $master_policy_details['mp_end_date'],
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
            // echo '<pre>'; print_r($insert_data_sold);//die('here');
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
                if(!empty($plan_details['ic_id'])){
                    if ($domainName != 'localhost' && !empty($post_data['email'])) {
                        $this->MailSoldPolicyPdf($inserted_id,$plan_details['ic_id']);
                    }

                    $this->data['ic_pdf'] = 'download_workshop_OICL_pdf';
                    $this->data['inserted_id'] = $inserted_id;
                    $this->load->dashboardTemplate('front/myaccount/workshop_success', $this->data);
                }

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
     if($post_data['plan_type_workshop'] == 1){
        $selected_date = $post_data['start_date'] ;
        $start_date = (!empty($selected_date) && isset($selected_date) ) ? $selected_date : date('Y-m-d');
         if($plan_details['rsa_tenure'] == 2){
                        $result['effective_date'] = date('Y-m-d', strtotime($start_date . "0 day")) . ' 00:00:01';
                        $result['end_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "-1 day")) . "2 year")) . ' 23:59:59';
                        }else{
                            $result['effective_date'] = date('Y-m-d', strtotime($start_date . "0 day")) . ' 00:00:01';
                            $result['end_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "-1 day")) . "1 year")) . ' 23:59:59';
                            }
    }else if($post_data['plan_type_workshop'] == 2){
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



 function MailSoldPolicyPdf($inserted_id,$pa_ic_id) {
    $policypdf_obj = $this->DownloadWorkshopOICLPdf($inserted_id);
    $status = $this->MailAttachments($policypdf_obj, $inserted_id);
 }

function DownloadWorkshopOICLPdf($id){
  $rsa_policy_data = $this->HomeMdl->getPolicyById($id);
  $where = array(
    'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
  );
  $master_policy_details = $this->HomeMdl->getRowDataFromTable('ic_pa_master_policy_nos',$where);
  $master_policy_location = $master_policy_details['mp_localtion'];
  $master_policy_address = $master_policy_details['address'];
  $getDealerInfo = $this->HomeMdl->getDealerinfo($rsa_policy_data['user_id']);
  $getICInfo = $this->HomeMdl->getICInfo($rsa_policy_data['rsa_ic_id']);
  // echo '<pre>';print_r($getICInfo);die;
  $rsa_name = $getICInfo['name'];
  $rsa_logo = base_url($getICInfo['logo']);
  $rsa_address = $getICInfo['address'];
  $rsa_email = $getICInfo['email'];
  $customer_care_no = $getICInfo['toll_free_no'];
  $dealer_id = isset($rsa_policy_data['user_id']) ? $rsa_policy_data['user_id'] : '';
  $certificate_no = isset($rsa_policy_data['sold_policy_no']) ? $rsa_policy_data['sold_policy_no'] : '--';
  $vehicle_registration_no = isset($rsa_policy_data['vehicle_registration_no']) ? $rsa_policy_data['vehicle_registration_no'] : '--';
  $plan_name = isset($rsa_policy_data['plan_name']) ? strtoupper($rsa_policy_data['plan_name']) : '--';
  $plan_id = isset($rsa_policy_data['plan_id']) ? strtoupper($rsa_policy_data['plan_id']) : '--';
  $where = array(
    'id'=>$plan_id
  );
  $plan_detalis = $this->HomeMdl->getDataFromTable('tvs_plans',$where);
   // echo '<pre>'; print_r($plan_detalis);die();
  $plan_detalis = $plan_detalis[0];
  $km_covered = isset($plan_detalis['km_covered'])?$plan_detalis['km_covered']:'50';
  // die($km_covered);
  $sum_insured = isset($plan_detalis['sum_insured'])?$plan_detalis['sum_insured']:'--';
  $plan_amount  = round($plan_detalis['plan_amount']);
  $gst_amount  = round($plan_detalis['gst_amount']);
  $total_amount =  ($plan_amount + $gst_amount);
  $engine_no = isset($rsa_policy_data['engine_no']) ? strtoupper($rsa_policy_data['engine_no']) : '--';
  $chassis_no = isset($rsa_policy_data['chassis_no']) ? strtoupper($rsa_policy_data['chassis_no']) : '--';
  $created_date = isset($rsa_policy_data['created_at']) ? $rsa_policy_data['created_at'] : '--';
  $fname = isset($rsa_policy_data['fname']) ? strtoupper($rsa_policy_data['fname']) : '--';
  $lname = isset($rsa_policy_data['lname']) ? strtoupper($rsa_policy_data['lname']) : '--';
  $full_name_of_insured = $fname.' '.$lname;
  $nominee_name = isset($rsa_policy_data['nominee_full_name']) ? strtoupper($rsa_policy_data['nominee_full_name']) : '--';
  $nominee_relation = isset($rsa_policy_data['nominee_relation']) ? strtoupper($rsa_policy_data['nominee_relation']) : '--';
  $nominee_age = isset($rsa_policy_data['nominee_age']) ? strtoupper($rsa_policy_data['nominee_age']) : '--';

  $appointee_name = isset($rsa_policy_data['appointee_full_name']) ? strtoupper($rsa_policy_data['appointee_full_name']) : ' ';
  $appointee_relation = isset($rsa_policy_data['appointee_relation']) ? strtoupper($rsa_policy_data['appointee_relation']) : ' ';
  $appointee_age = isset($rsa_policy_data['appointee_age']) ? strtoupper($rsa_policy_data['appointee_age']) : ' ';
  
  if(!empty($appointee_age)){
    $appointee_details = '';
  }else{
    $appointee_details = 'Name:' .$appointee_name.' Relationship: '.$appointee_relation .'Age: '.$appointee_age;
  }
   //echo $appointee_details;exit;
  //master policy detils
  $master_policy_no = isset($rsa_policy_data['master_policy_no']) ? strtoupper($rsa_policy_data['master_policy_no']) : ' ';
  $mp_start_date = isset($rsa_policy_data['mp_start_date']) ? $rsa_policy_data['mp_start_date'] : ' ';
  $date = new DateTime($mp_start_date);
  $mp_start_date = $date->format('d-M-Y');
  $mp_end_date = isset($rsa_policy_data['mp_end_date']) ? $rsa_policy_data['mp_end_date'] : ' ';

  $email = isset($rsa_policy_data['email']) ? strtoupper($rsa_policy_data['email']) : '--';
  $mobile_no = isset($rsa_policy_data['mobile_no']) ? strtoupper($rsa_policy_data['mobile_no']) : '--';
  $gender = isset($rsa_policy_data['gender']) ? strtoupper($rsa_policy_data['gender']) : '--';
  $dob = isset($rsa_policy_data['dob']) ? strtoupper($rsa_policy_data['dob']) : '--';
  $addr1 = isset($rsa_policy_data['addr1']) ? strtoupper($rsa_policy_data['addr1']) : '--';
  $addr2 = isset($rsa_policy_data['addr2']) ? strtoupper(mb_strimwidth($rsa_policy_data['addr2'], 0, 20, "...")) : '--';
  $state_name = isset($rsa_policy_data['state_name']) ? strtoupper($rsa_policy_data['state_name']) : '--';
  $city_name = isset($rsa_policy_data['city_name']) ? strtoupper($rsa_policy_data['city_name']) : '--';
  $sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '--';
  $sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date'] : '--';
  $pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '--';
  $imp_note ='';
  // if( strtotime($pa_sold_policy_effective_date) >= strtotime('2019-09-05 21:59:59') ){
  $imp_note = '<tr>
          <td colspan="2"><b>Insurance cover in brief:</b> Please refer to policy for detail information on coverage, exclusion and other terms and conditions.</td>
        </tr>
        <tr>
          <td width="5%" class="textcenter">1.</td>
          <td width="95%">Death only. 100%</td>
        </tr>
        <tr>
          <td class="textcenter">2.</td>
          <td>Loss of two limbs two eyes or  one limb one eye. 100%</td>
        </tr>
        <tr>
          <td class="textcenter">3.</td>
          <td>Loss of one limb or one eye. 50%</td>
        </tr>
        <tr>
          <td class="textcenter">4.</td>
          <td>Permanent total disablement from injuries other than those named above. 100%</td>
        </tr>';
  // }
  $pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '--';
  
  $model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';
  $this->load->library('Tcpdf/Tcpdf.php');
  ob_start();
  $this->load->library('Ciqrcode');

  $params['data'] = "Name: '" . $full_name_of_insured . "' , POLICY NO: '" . $certificate_no . "', FROM: '" . $pa_sold_policy_effective_date . "' , TO: '" . $pa_sold_policy_end_date . "'";
  $params['level'] = 'H';
  $params['size'] = 5;
  $params['savename'] = FCPATH . 'assets/images/qr_image/'.trim($certificate_no).'.png';
  $this->ciqrcode->generate($params);
  $qr_code_image_url = '<img src="assets/images/qr_image/'.trim($certificate_no).'.png" width="60px" />';
   
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('The Oriental Insurance Company Ltd. PA Certificate');
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
$html = <<<EOD
<style>
  .pagewrap {color: #000; font-size: 7pt; line-height:8.5pt; color:#000;}
  .textcenter {text-align:center;}
  .textleft {text-align:left;}
  .textright {text-align:right;}
  .font-6{font-size: 5.5pt; line-height:6.5pt;}
  .font-7{font-size: 7pt; line-height:8.5pt;}
  .font-8{font-size: 7pt; line-height:9pt;}
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
  .header {background-color:#ec3237;}
  .headertext {font-size:14pt; line-height:40pt; color:#fff;}
  .border, .boxtable td {border:0.2px solid #000;}
  .bluetable td {border:0.2px solid #16365c;}
  .boxtable td.heading {background-color:#d9d9d9; color:#000; font-weight:bold;}
  .tb-heading {background-color:#16365c; color:#fff;text-transform:uppercase; font-weight:bold;}
  .sectionhead { font-size:7.5pt; line-height:10pt; background-color:#000; color:#fff;}
  .footer {border-top:0.5px solid #808080; text-align:center; line-height:9pt;}
</style>
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td width="50%"><img src="assets/images/bikes-img.jpg"></td>
          <td width="15%"></td>
          <td width="35%">
            <table cellpadding="1" border="0" cellspacing="0">
              <tr>
                <td colspan="2"><img src="{$rsa_logo}" height="40"></td>
              </tr>
              <tr>
                <td width="20%"><b>Address:</b></td>
                <td width="80%">{$rsa_address}</td>
              </tr>
              <tr>
                <td><b>Email:</b></td>
                <td>{$rsa_email}</td>
              </tr>
            </table>
          </td>
        </tr> 
        <tr>
          <td colspan="3" style="border-top:2px solid #16365c;"></td>
        </tr> 
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td width="48%">
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
              <tr class="tb-heading">
                <td colspan="2">CERTIFICATE</td>
              </tr>   
              <tr>
                <td width="50%"><b>Certificte Number</b></td>
                <td width="50%">{$certificate_no}</td>
              </tr> 
              <tr>
                <td><b>Plan Name</b></td>
                <td>{$plan_name}</td>
              </tr>
              <tr>
                <td><b>Certificate issue Date</b></td>
                <td>{$created_date}</td>
              </tr>
              <tr>
                <td><b>RSA Start Date</b></td>
                <td>{$sold_policy_effective_date}</td>
              </tr>
              <tr>
                <td><b>RSA End Date</b></td>
                <td>{$sold_policy_end_date}</td>
              </tr>
            </table>
          </td>
          <td width="4%"></td>
          <td width="48%">
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
              <tr class="tb-heading">
                <td colspan="2">VEHICLE</td>
              </tr>   
              <tr>
                <td width="50%"><b>Vehicle Registration Number</b></td>
                <td width="50%">{$vehicle_registration_no}</td>
              </tr> 
              <tr>
                <td><b>Manufacturer </b></td>
                <td>TVS</td>
              </tr>
              <tr>
                <td><b>Model</b></td>
                <td>{$model_name}</td>
              </tr>
              <tr>
                <td><b>Engine Number</b></td>
                <td>{$engine_no}</td>
              </tr>
              <tr>
                <td><b>Chassis Number</b></td>
                <td>{$chassis_no}</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td colspan="3">
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
              <tr class="tb-heading">
                <td colspan="2">PERSONAL DETAILS</td>
              </tr>             
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="3"></td>
        </tr>
        <tr>
          <td width="48%">
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
              <tr>
                <td width="50%"><b>First Name</b></td>
                <td width="50%">{$fname}</td>
              </tr>
              <tr>
                <td><b>Last Name</b></td>
                <td>{$lname}</td>
              </tr>
              <tr>
                <td><b>Mobile No</b></td>
                <td>{$mobile_no}</td>
              </tr>
              <tr>
                <td><b>Email ID </b></td>
                <td>{$email}</td>
              </tr>
            </table>
          </td>
          <td width="4%"></td>
          <td width="48%">
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
              <tr>
                <td width="50%"><b>Address 1 </b></td>
                <td width="50%">{$addr1}</td>
              </tr>
              <tr>
                <td><b>Address 2</b></td>
                <td>{$addr2}</td>
              </tr>
              <tr>
                <td><b>State</b></td>
                <td>{$state_name}</td>
              </tr>
              <tr>
                <td><b>City</b></td>
                <td>{$city_name}</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">        
        <tr>
          <td>
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
              <tr class="tb-heading">
                <td colspan="2"> INDICOSMIC CAPITAL</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table cellpadding="4" border="0" cellspacing="0">
              <tr>
                <td width="40%"><img src="assets/images/icpl-logo.jpg" height="40"></td>
                <td width="60%"><b>Address:</b> 318, 3rd Floor, Summit-Business Bay, Behind Gurunanak Petrol Pump,
                Off Andheri Kurla Road, Beside Magic Bricks WEH metro stn., Andheri (E),Mumbai-400093, Maharashtra (India)
                <br><br><b>Email:</b> info@indicosmic.com</td>
              </tr>
            </table>  
          </td>       
        </tr>
        <tr>
          <td>
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
              <tr class="tb-heading textcenter">
                <td colspan="2"> CUSTOMER CARE NO: {$customer_care_no}</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td>
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
              <tr class="tb-heading">
                <td colspan="2"> THE SERVICES PROVIDED UNDER THE ASSISTANCE ARE AS FOLLOWS:</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="10" border="0" cellspacing="0" class="textcenter font-9">
        <tr>
          <td><img src="assets/images/revised/service-icon-1.jpg"></td>
          <td><img src="assets/images/revised/service-icon-2.jpg"></td>
          <td><img src="assets/images/revised/service-icon-3.jpg"></td>
          <td><img src="assets/images/revised/service-icon-4.jpg"></td>
          <td><img src="assets/images/revised/service-icon-5.jpg"></td>
          <td><img src="assets/images/revised/service-icon-6.jpg"></td>
          <td><img src="assets/images/revised/service-icon-7.jpg"></td>
        </tr>
        <tr>
          <td>Towing Assistance</td>
          <td>Onsite support for Minor repairs</td>
          <td>Rundown of Battery</td>
          <td>Flat Tyre</td>
          <td>Fuel Assistance</td>
          <td>Customer Coverage Care</td>
          <td>Urgent Message Relay</td>
        </tr>
        <tr>
          <td><img src="assets/images/revised/service-icon-8.jpg"></td>
          <td><img src="assets/images/revised/service-icon-9.jpg"></td>
          <td><img src="assets/images/revised/service-icon-10.jpg"></td>
          <td><img src="assets/images/revised/service-icon-11.jpg"></td>
          <td><img src="assets/images/revised/service-icon-12.jpg"></td>
          <td><img src="assets/images/revised/service-icon-13.jpg"></td>
          <td><img src="assets/images/revised/service-icon-14.jpg"></td>
        </tr>       
        <tr>
          <td>Emergency Assistance</td>
          <td>Key Lost / Replacement</td>
          <td>Taxi Assistance</td>
          <td>Accommodation Assistance</td>
          <td>Outward / Forward Journey</td>
          <td>Arrangement of Rental Vehicle</td>
          <td>Coverage</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
        <tr>
          <td></td>
        </tr>
      </table>      
      <table cellpadding="2" border="0" cellspacing="0" class="bluetable ">
        <tr class="tb-heading textcenter">
          <td> *All consumables would be charged back to the customer. Cost of accommodation, Taxi, rental vehicle needs to be paid by the customer</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
        <tr>
          <td colspan="3" style="border-top:2px solid #16365c;"></td>
        </tr>
      </table>
    <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
    
        <tr>
          <td><b>Coverage Kilometer RSA Upto</b></td>
          <td>25 KM</td>
        </tr>
  </table>
      
      </td>     
  </tr>
</table>
<br pagebreak="true" />
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td width="20%" height="70" class="textleft">$qr_code_image_url</td>
          <td width="60%">
            <table cellpadding="1" border="0" cellspacing="0" class="textcenter">
            <tr>
              <td class="font-11 line-height-14"><h1>The Oriental Insurance Company Ltd.</h1></td>

            </tr> 
            <tr>
              <td class="font-9 line-height-12"><b><u>Details of Personal Accident Cover <br>Master Policy Holder Name:</u></b> <b><u> INDICOSMIC CAPITAL PVT LTD</u></b>
              </td>

            </tr> 
                
            <tr>
              <td></td>
            </tr>   
          </table>
          </td>
          <td width="20%" height="70" class="textright"><img src="assets/images/mpdf/oicl_logo.png" height="60"></td>
        </tr>
      </table>
      
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td>Master Policy No.:  <b>{$master_policy_no}</b></td>          
        </tr>
        <tr>
          <td>Issuing Office: <b> Corporate Business Unit No. 3, Oriental House, 3rd Floor, 7, J. Tata Road, Churchgate, Mumbai- 400020
</b></td>
        </tr>
        <tr>
          <td>Beneficiary Name: {$full_name_of_insured}</td>
        </tr>
        <tr>
          <td>Beneficiary's Address: {$addr1} {$addr2}</td>
        </tr>
        <tr>
          <td>Beneficiary ID no.: {$certificate_no}</td>
        </tr> 
      </table>
      <table cellpadding="1" border="0" cellspacing="0"> 
        <tr>
          <td>Certificate Inception date: From - {$pa_sold_policy_effective_date} Expiry date: {$pa_sold_policy_end_date}</td>
        </tr>        
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable textcenter">
        <tr>
          <td width="15%"><b>Name of the Member Covered</b></td>
          <td width="10%"><b>Date of Birth/Age</b></td>
          <td width="8%"><b>Gender</b></td>
          <td width="10%"><b>Nominee Name</b></td>
          <td width="12%"><b>Nominee Relationship with Member</b></td>
          <td width="15%"><b>Email ID and Mobile no. (if any)</b></td>          
          <td width="20%"><b>Benefits</b></td>
          <td width="10%"><b>Sum Insured</b></td>
        </tr>
        <tr>
          <td>{$full_name_of_insured}</td>
          <td>{$dob}</td>
          <td>{$gender}</td>
          <td>{$nominee_name}</td>
          <td>{$nominee_relation}</td>
          <td>{$email}</td>
          <td>Accidental death Total Disablement (PTD) </td>
          <td>INR {$sum_insured}</td>
        </tr>
      </table>
        <table cellpadding="2" border="0" cellspacing="0">
              <tr>
                <td width="40%"><b>Broker Name: </b>Global India Insurance Brokers Pvt. Ltd.</td>
                <td width="30%"><b>Broker Email: </b>info@giib.co.in</td>
                <td width="30%"><b>Broker contact No.: </b>022-49707493</td>
              </tr>     
            </table>
      <table cellpadding="1" border="0" cellspacing="0">
        
        <tr>
          <td colspan="2"><b>Details of GPA Policy</b><br>If the Insured person sustains any bodily injury during the policy period which directly and independently of all other causes result in death/ disablement stated below within 12 months from the date of accident  resulting solely and directly from Accident then the Company shall pay to the insured the sum set in the schedule to the Insured person’s nominee ,beneficiary or legal representative. This certificate is effective from the date of issuance of the certificate and 24/7.</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
          <tr>
            <td colspan="2"><b>Important Notes:</b></td>
          </tr>
          <tr>
            <td width="5%" class="textcenter">1</td>
            <td width="95%">The personal accident coverage is applicable only to the owner of the vehicle for which Road Side Assistance service has been provided by the Indicosmic Capital Pvt Ltd.</td>
          </tr>
          <tr>
            <td class="textcenter">2</td>
            <td>The said personal accident cover is active 24/7.</td>
          </tr>
          <tr>
            <td class="textcenter">3</td>
            <td>Death or permanent total disability claims due to any other incidence would not be covered</td>
          </tr>
          <tr>
            <td class="textcenter">4</td>
            <td>The policy is valid for 365 days from the policy risk start date</td>
          </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0" >
        <tr>
          <td colspan="2"><b>Insurance cover in brief:</b> Please refer to policy for detail information on coverage, exclusion and other terms and conditions.</td>
        </tr>
        <tr>
          <td width="5%" class="textcenter">1.</td>
          <td width="95%">Death only. 100%</td>
        </tr>
        <tr>
          <td class="textcenter">2.</td>
          <td>Loss of two limbs two eyes or  one limb one eye. 100%</td>
        </tr>
        <tr>
          <td class="textcenter">3.</td>
          <td>Loss of one limb or one eye. 50%</td>
        </tr>
        <tr>
          <td class="textcenter">4.</td>
          <td>Permanent total disablement from injuries other than those named above. 100%</td>
        </tr>
        
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        
        <tr>
          <td colspan="2"><b>Important Exclusions in brief:</b> The insurance cover herein does not cover death, injury or disablement resulting from: </td>
        </tr>
        <tr>
          <td width="5%" class="textcenter">a)</td>
          <td width="95%">Services on duty with any armed forces.  </td>
        </tr>
        <tr>
          <td class="textcenter">b)</td>
          <td>Intentional self injury, suicide or attempted suicide, insanity, venereal diseases or the influence of intoxicating drug.</td>
        </tr>
        <tr>
          <td class="textcenter">c)</td>
          <td>Medical or surgical treatment.</td>
        </tr>
        <tr>
          <td class="textcenter">d)</td>
          <td>Aviation other than as a passenger (fare-paying or otherwise) in any duly licensed standard type of aircraft anywhere in the world.</td>
        </tr>
        <tr>
          <td class="textcenter">e)</td>
          <td>Nuclear radiation or nuclear weapons related accident.</td>
        </tr>
        <tr>
          <td class="textcenter">f)</td>
          <td>War & warlike operation, the act of foreign enemy, civil war & similar risk. </td>
        </tr>
        <tr>
          <td class="textcenter">g)</td>
          <td>Child birth, pregnancy or other physical cause peculiar to the female sex.</td>
        </tr>
        <tr>
          <td class="textcenter">h)</td>
          <td>Whilst committing any breach of law with criminal intent.</td>
        </tr>
       
        <tr>
          <td colspan="2"><b>Claim Procedure:</b> An indicative document list in case of claim is as given below.</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr> 
          
            
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="46%">
            <table cellpadding="2" border="0" cellspacing="0">
              <tr>
                <td colspan="2"><b>Permanent Total Disablement Claims</b></td>
              </tr>
              <tr>
                <td width="5%">1. </td>
                <td width="95%">Duly filled claim form, Download claim from through below link <a href="https://orientalinsurance.org.in/documents/10182/1177126/PA_Claim.pdf/ac9868c4-202c-4d11-a040-1e29ef3fe54d" target="_blank" style="color:#1a0dab;">https://orientalinsurance.org.in/documents/10182/1177126/PA_Claim.pdf/ac9868c4-202c-4d11-a040-1e29ef3fe54d</a></td>
              </tr>
              <tr>
                <td>2.  </td>
                <td>Police FIR and Reports (Mandatory)</td>
              </tr>
              <tr>
                <td>3.  </td>
                <td>Permanent Total Disablement Medical Certificate issued by attending doctor/ treating hospital authorities.</td>
              </tr>
              <tr>
                <td>4.  </td>
                <td>Photographs of disablement attested by doctor</td>
              </tr>
              <tr>
                <td>5.  </td>
                <td>ID proof (PAN card, Aadhar Card, Voter ID, ID card issued by State OR Central Govt. authorities.</td>
              </tr>
              <tr>
                <td>6.  </td>
                <td>Any other documents requested by Oriental Insurance Companys office in view of claim.</td>
              </tr>
              
            </table>
          </td>
          <td width="54%">
            <table cellpadding="2" border="0" cellspacing="0">
              <tr>
                <td colspan="2"><b>Accidental Death Claims</b></td>
              </tr>
              <tr>
                <td width="5%">1. </td>
                <td width="95%">Duly filled claim form, Download claim from through below link <a href="https://orientalinsurance.org.in/documents/10182/1177126/PA_Claim.pdf/ac9868c4-202c-4d11-a040-1e29ef3fe54d" target="_blank" style="color:#1a0dab;">https://orientalinsurance.org.in/documents/10182/1177126/PA_Claim.pdf/ac9868c4-202c-4d11-a040-1e29ef3fe54d</a></td>
              </tr>
              <tr>
                <td>2.  </td>
                <td>Police FIR and Reports (Mandatory)</td>
              </tr>
              <tr>
                <td>3.  </td>
                <td>Death certificate of deceased.</td>
              </tr>
              <tr>
                <td>4.  </td>
                <td>ID proof (PAN card, Aadhar Card, Voter ID, ID card issued by State OR Central Govt. authorities.</td>
              </tr>
              <tr>
                <td>5.  </td>
                <td>Post mortem report.</td>
              </tr>
              <tr>
                <td>6.  </td>
                <td>Coroner&#39;s report.</td>
              </tr>
              <tr>
                <td>7.  </td>
                <td>Declaration from Nominee (affidavit) with ID proof.</td>
              </tr>
              <tr>
                <td>8.  </td>
                <td>Any other documents requested by Oriental Insurance Companys office in view of claim.</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
       <tr>
        <td colspan="2"></td>
      </tr>
      
      <tr>
        <td colspan="2">All claims under this personal accident cover will be processed and settled by Corporate Business Unit No. 3 of Oriental Insurance Company  Ltd.</td>
      </tr>
      <tr>
        <td width="5%" class="textcenter">i)</td>
        <td width="95%">In respect of fatal claims the payment is to be made to the assignee named under the policy. If there is no assignee, the payment is made to the legal representative as identified by Will / Probate / Letter of Administration/ Succession Certificate.</td>
      </tr>
      <tr>
        <td class="textcenter">ii)</td>
        <td>Where the above documents are not available, the following procedure may be followed :-
    (a) an affidavit from the claimant(s) that he/she (they) is (are) the legal heir(s) of the deceased (b) an affidavit from other near family members and relatives of the deceased that they have no objection if the claim amount is paid to the claimant(s)
    </td>
      </tr>
      <tr>
        <td class="textcenter">iii)</td>
        <td>PERMANENT TOTAL DISABLEMENT as described in this policy feature only.<br>
    (CERTIFIED BY COMPETANT AUTHORITIES)<br>
    PARTIAL DISABLEMENT is not covered under this policy.</td>
      </tr>
      <tr>
        <td class="textcenter">iv)</td>
        <td>Claim intimation e-mail sent to kishor.sawant@orientalinsurance.co.in / kannan.r@orientalinsurance.co.in</td>
      </tr>
    </table>
     <table cellpadding="1" border="0" cellspacing="0">
       <tr>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td width="5%" class="textcenter">1</td>
        <td width="95%">Upon the happening of any event which may give rise to a claim under this policy, written notice with all particulars must be given to the Company immediately. In case of death, internment cremation, and in any case within one calendar month after the death and in the event of loss of sight or amputation of limbs, written notice thereof must also be given within one calendar month after such loss of sight or amputation.</td>
      </tr>
      <tr>
        <td class="textcenter">2</td>
        <td>After receipt of the claim intimation OICL will share claim no. to the Insured.</td>
      </tr>
      <tr>
        <td class="textcenter">3</td>
        <td>If required other than Road Traffic Accident OICL reserves the right to investigate the claim to know the proximate cause of loss.</td>
      </tr>
      <tr>
        <td class="textcenter">4</td>
        <td>The insured has to submit all the claim documents along with duly filled in Claim Form to the policy issuing office within 7 days from the date of completion of treatment in respect of Permanent Total Disablement claims. In case of Death Claims within 7 days from the date of intimation.</td>
      </tr>
      <tr>
        <td class="textcenter">5</td>
        <td>After receipt of all claim related documents Oriental Insurance Company Limited will settle the claim with the beneficiary/nominee.</td>
      </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td height="20"></td>
        </tr>
        <tr>  
          <td class="textcenter font-8" style="color:#808080;">Policy Servicing office: Oriental House, 3rd Floor, 7, J. Tata Road, Churchgate, Mumbai- 400020. <br>Helpline No. +91-22-22040419 / 22049064.  Website: https://orientalinsurance.org.in/ <br>IRDA Registration Number: 556 CIN: U66010DL1947GOI007158</td>
        </tr>
      </table>
      </td>     
  </tr>
</table>

EOD;
 if($dealer_id == 2871 || $dealer_id == 2872){
          $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 10, 100, 200, '', '', '', true, 500);
         }
// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0, '');

// ---------------------------------------------------------

//Close and output PDF document

     $result= $pdf->Output('OICL-PA-Certificate.pdf', 'S');

    return $result ;
}

function MailAttachments($policypdf_obj, $sold_policy_detail_last_id) {
        $rsa_policy_data = $this->HomeMdl->getPolicyById($sold_policy_detail_last_id);
        $rsa_ic_master_id = $this->session->userdata('user_session')['rsa_ic_master_id'];
        $where = array(
            'id' => $rsa_ic_master_id
        );
        $insurance_companies = $this->HomeMdl->getRowDataFromTableWithOject('rsa_ic_master', $where);

        $rsa_email = $insurance_companies->email;
        $toll_free_no = $insurance_companies->customer_care_no;
        $this->load->library('email');
          $config = array(
            'charset' => 'utf-8',
            'wordwrap' => TRUE,
            'mailtype' => 'html',
            'priority' => 1,
            'charset' => 'iso-8859-1'
        );

        $from = 'info@indicosmic.com';
        $fname = $rsa_policy_data['fname'];
        $lname = $rsa_policy_data['lname'];
        $customer_name = $fname . ' ' . $lname;
        $sold_policy_no = $rsa_policy_data['sold_policy_no'];
        $customer_mail = $rsa_policy_data['email'];
        $this->email->from($from, "TVS-RSA");
        $this->email->to($customer_mail);
        // $this->email->bcc('info@indicosmic.com');
        $this->email->attach($policypdf_obj, 'attachment', 'RSA.pdf', 'application/pdf');
        $this->email->set_mailtype('html');
        $msg = "<html><body>
                
        <p>Dear $customer_name,</p>
        <p>
        Thank you for purchasing your Road Side Assistance policy from Authorised TVS Dealer.
        Your RSA Policy number $sold_policy_no has been successfully generated and it is attached for your reference.
        In case of any queries or assistance, please call us on ".$toll_free_no." or write to us at ".$rsa_email.".
        This is your original policy copy.</p><br>

        <p>Warm Regards,</p>
        <br>
        <p>Team ICPL</p>
        


        <h3>*****DISCLAIMER*****</h3><br>
        

        </body></html>";

        $this->email->subject('TVS RSA SOLD POLICY');
        $this->email->message($msg);
        if ($this->email->send()) {
            $result['status'] = true;
        } else {
            $result['status'] = false;
        }
        return $result;
    }

}
?>


