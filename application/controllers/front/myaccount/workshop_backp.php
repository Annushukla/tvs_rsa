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
                
                //$checked = ($plan_detail['id'] == $plan_id)?'checked':'';
                $plan_code = $plan_detail['plan_code'];
                $id = $plan_detail['id'];
                $plan_amount = $plan_detail['plan_amount'];
                $plan_name = $plan_detail['plan_name'];
                $rsa_tenure = ($plan_detail['rsa_tenure'] == 1)?'1 Year':'2 Years';
                $km_covered = $plan_detail['km_covered'];
                $pa_tenure = ($plan_detail['pa_tenure'] == 1)?'1 Year':'2 Years';
                $pa_plan_amount = $plan_detail['pa_plan_amount'];
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
        redirect('generate_policy');
    }
    $post_data = $this->input->post();
    $dob = date('Y-m-d',strtotime($post_data['dob']));
    $age =  date_diff(date_create($dob), date_create('today'))->y;
    if($age > 64 || $age < 18){
        $this->session->set_flashdata('message', 'Customer Age Should Not More Than 65 Or Less Than 16.');
        redirect('generate_policy');
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
            redirect('generate_policy');
        }else{
                $where = array('id'=>$post_data['workshop_plan']);
                $plan_details  = $this->HomeMdl->getRowDataFromTable('tvs_plans',$where);
                $user_data = $this->session->userdata('user_session');
                $dealer_code = (strlen($user_data['sap_ad_code']) >5)?$user_data['dealer_code'] : $user_data['sap_ad_code'];
                $where = array('dealer_id'=>$user_data['id']);
                $dealer_wallet_details  = $this->HomeMdl->getRowDataFromTable('dealer_wallet',$where);
                $wallet_balance = ($dealer_wallet_details['security_amount'] - $dealer_wallet_details['credit_amount']);

                // if($_SERVER['HTTP_X_FORWARDED_FOR']=="::ffff:59.152.55.202")
                // {
                //     echo '<pre>';print_r($plan_details);die();

                // }

                if($wallet_balance < ($plan_details['plan_amount'] + $plan_details['gst_amount'])){
                 $this->session->set_flashdata('message', 'Wallet Amount Is Less Than Policy Amount.');
                 redirect('generate_policy');
                }else{
                        $is_exist = $this->HomeMdl->checkIsPolicyExist($post_data['engine_no'],$post_data['chassis_no']);
                        if($is_exist){
                            $this->session->set_flashdata('message', 'Duplicate Policy To Check Visit On Certificate Section.');
                            redirect('generate_policy');
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
                            $pa_effective_date = date('Y-m-d', strtotime($selected_date . "0 day")). ' 00:00:01';
                            $pa_end_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime($selected_date . "-1 day")) . "1 year")). ' 23:59:59';
                            $model_name = $this->getMakeModelName('model', $model_id);
                            $transection_no = $this->getRandomNumber('16');
                                    
                                        $where = array('ic_id'=>$plan_details['ic_id']);
                                        $master_policy_details = $this->HomeMdl->getRowDataFromTable('ic_pa_master_policy_nos',$where);
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
                                        'ic_id' => $master_policy_details['ic_id'],
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
                                        'rsa_ic_id' => $user_data['rsa_ic_master_id'],
                                        'vehicle_type' => $post_data['vehicle_type'],
                                        'sold_policy_date' => date('Y-m-d H:i:s'),
                                        'created_date' => date('Y-m-d H:i:s')

                                        );
                                        // echo '<pre>'; print_r($insert_data_sold);//die('here');
                                        $inserted_id = $this->HomeMdl->insertIntoTable('tvs_sold_policies',$insert_data_sold);
                                        // echo $this->db->last_query();die();
                                        if (!empty($inserted_id)) {
                                            if(!empty($plan_details['plan_amount']))
                                            {
                                            $insert_customer_detail['policy_id'] = $inserted_id;
                                            $inserted_endors_customer_id = $this->HomeMdl->insertIntoTable('tvs_endorse_customer_details',$insert_customer_detail);
                                            $this->session->unset_userdata('dms_response');
                                            $dealer_transection_data = array(
                                                'dealer_id'=> $user_data['id'],
                                                'policy_no'=> $result_sold['sold_policy_no'],
                                                'transection_id' => $transection_no,
                                                'transection_type' => 'cr',
                                                'transection_purpose' => 'Policy Created',
                                                'policy_amount' =>($plan_details['plan_amount']+$plan_details['gst_amount']),
                                                'dealer_commission' =>$plan_details['dealer_commission'],
                                                'rsa_commission' =>$plan_details['rsa_commission_amount'],
                                                'pa_ic_commission' =>$plan_details['pa_ic_commission_amount'],
                                                'oem_commission' =>$plan_details['oem_commission_amount'],
                                                'brocker_commission' =>$plan_details['brocker_commission_amount'],
                                            );
                                            $status = $this->HomeMdl->insertIntoTable('dealer_transections',$dealer_transection_data);
                                            if($status){
                                                $policy_amount = ($plan_details['plan_amount']+$plan_details['gst_amount']);
                                                $where = array('dealer_id'=>$user_data['id']);
                                                $wallet_details = $this->HomeMdl->getDataFromTable('dealer_wallet',$where);
                                                $wallet_details = $wallet_details[0];

                                                $wallet_amount = (($wallet_details['credit_amount'] + $policy_amount) - $plan_details['dealer_commission']);
                                                $data = array(
                                                    'credit_amount'=> $wallet_amount
                                                );
                                                $where = array('dealer_id'=>$user_data['id']);
                                                $status = $this->HomeMdl->updateTable('dealer_wallet',$data,$where);
                                            if($status){
                                                $data = array(
                                                    'policy_no'=>$result_sold['sold_policy_no'],
                                                    'dealer_id'=> $user_data['id'],
                                                    'transection_no' =>$transection_no,
                                                    'transection_type'=> 'dr',
                                                    'transection_amount'=>$policy_amount,
                                                    'transection_purpose'=>'Policy Created'
                                                );
                                                // echo '<pre>'; print_r($data);//die('hello');
                                                $this->HomeMdl->insertIntoTable('dealer_transection_statement',$data);

                                                $data = array(
                                                    'policy_no'=> $result_sold['sold_policy_no'],
                                                    'dealer_id'=> $user_data['id'],
                                                    'transection_no' => $transection_no,
                                                    'transection_type'=>'cr',
                                                    'transection_amount'=>$plan_details['dealer_commission'],
                                                    'transection_purpose'=>'Commission'
                                                );
                                                $this->HomeMdl->insertIntoTable('dealer_transection_statement',$data);
                                            }
                                        }
                                    }
                                        $domainName = $_SERVER['HTTP_HOST'];
                                       if(!empty($plan_details['ic_id'])){
                                            if ($domainName != 'localhost' && !empty($post_data['email'])) {
                                                $this->MailSoldPolicyPdf($inserted_id,$plan_details['ic_id']);
                                            }

                                            $this->data['ic_pdf'] = 'download_tata_lite_pdf';
                                            $this->data['inserted_id'] = $inserted_id;
                                            $this->load->dashboardTemplate('front/myaccount/success', $this->data);
                                        }

            //if closing of inserted_id                                
                                    }else{ die('in not insert policy data'); } 
//if closing of customer_detail_last_id
                        }else{ die('in not insert Customer data');}
//else closing after is_exist if    
                        }

// else closing after if wallet                    
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
    $rsa_ic_master_id = $this->session->userdata('user_session')['rsa_ic_master_id'];
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
        $workshop_data = $this->HomeMdl->getRowDataFromTable('workshop_tvs_vehicle_master',['engine_no'=>$engine_no]);
        // echo "<pre>"; print_r($workshop_data); echo "</pre>"; die('end of line yoyo');
        if(!empty($workshop_data) && $workshop_data['is_policy_punched'] !=1 ){
            $response['message'] = "This Engine No. is Elegible for Generate Policy";
            $response['status'] = "eligible";
        }else{
            $response['message'] = "This Engine No. is Not Elegible for Generate Policy";
            $response['status'] = "noteligible";
        }
        
        echo json_encode($response);

    }
}

}
?>


