<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Rsa_RR310cover extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('database_library');
        $this->load->model('Home_Model', 'HomeMdl');
        $this->load->helper('common_helper');
        isUserLoggedIn();
    }

    function index($policyid = NULL) {
        // die('hello moto1');
        $user_session = $this->session->userdata('user_session');
        //plans
        $where = array('ic_id'=>'');
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
        $where = array('id'=>3);
        $plan_types = $this->HomeMdl->getDataFromTable('plan_types',$where);
        
        // echo '<pre>'; print_r($plan_types);die('plan_types');
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
            $this->load->dashboardTemplate('front/myaccount/rsa_rr310_cover', $this->data);
        }else{
            redirect('');
        }
    }
    public function planDetailsForRR310(){
            $plan_type_id = $this->input->post('plan_type_id');
            $post_data = $this->input->post();
            //echo '<pre>'; print_r($post_data);die('hello');
            $vehicle_age = $post_data['vehicle_age'];
            $plan_id = ($vehicle_age > 90)?63:62;
            $where = array('id' =>$plan_id);
            $key = 'plan_amount';
            $plan_details_arr = $this->HomeMdl->getDataFromTable('tvs_plans',$where); 
            //$query->result_array();
            // echo '<pre>'; print_r($plan_details_arr);die('hello moto');
            $html ='';
            $html .=<<<EOD
            <div class="plan-details cf" id="plan_details_rr310">

EOD;
            $html .=<<<EOD
                    <table class="table table-hover table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Plan Name</th>
                          <th scope="col">RSA Tenure</th>
                          <th scope="col">RSA Covered Kms</th>
                          <th scope="col">Policy Price <span style="color:red">*</span></th>
                          <th scope="col">Select Plan</th>
                        </tr>
                      </thead>
                      <tbody>
EOD;
$i = 1;

            foreach ($plan_details_arr as $plan_details_arr) {
                // if($plan_details_arr['id']==62 || $plan_details_arr['id']==55){
                //     continue;
                // }
                $checked = ($plan_details_arr['id'] == $selected_plan_id)?'checked':'checked';
                $plan_code = ($plan_details_arr['id']==62)?0:$plan_details_arr['plan_code'];
                $id = $plan_details_arr['id'];
                $plan_amount = ($plan_details_arr['id']==62)?0:$plan_details_arr['plan_amount'];
                $plan_name = $plan_details_arr['plan_name'];
                $rsa_tenure = ($plan_details_arr['rsa_tenure'] == 1)?'1 Year':'2 Years';
                $km_covered = $plan_details_arr['km_covered'];
                $pa_tenure = ($plan_details_arr['pa_tenure'] == 1)?'1 Year':'2 Years';
                $pa_plan_amount = $plan_details_arr['pa_plan_amount'];
                $lable = '₹ ' .$plan_code;
                $inputId=round($plan_code);
                $html .=<<<EOD
                        <tr>
                          <th scope="row">{$i}</th>
                          <td>{$plan_name}</td>
                          <td>{$rsa_tenure}</td>
                          <td>TO THE NEAREST TVS AUTHORIZED WORKSHOP</td>                        
                          <td>{$lable}</td>
                          <td><input type="radio" name="plan_rr310" id="{$inputId}" value="{$id}" data-plan="{$plan_amount}" {$checked}></td>
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
    public function fetchStateCity() {
        $pin = $this->input->post('data');
        $response_data = $this->HomeMdl->fetchStateCity($pin);
        $return_data['state'] = $response_data['state_name'];
        $return_data['city'] = $response_data['district_name'];
        $return_data['state_id'] = $response_data['state_id'];
        $return_data['city_id'] = $response_data['city_id'];
        echo json_encode($return_data);
    }

    public function get_states() {
        $query = $this->db->select('id,name')
            ->from('tvs_state')
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();
        return $query;
    }

    public function getStateId($stateName) {
        $query = $this->db->select('id')
            ->from('tvs_state')
            ->where('name', $stateName)
            ->order_by('name', 'ASC')
            ->get()
            ->row();
        return $query;
    }

    public function get_cities_list($sid = '') {
        $sid = ($sid == '') ? ($this->input->post('sid')) ? $this->input->post('sid') : '' : '';
        $query = $this->db->select('id, name')
            ->from('tvs_city');
        if ($sid != '') {
            $query = $this->db->where('state_id', $sid);
        }

        $this->db->order_by('name', 'ASC');
        $query = $this->db->get()->result_array();
        //echo $this->db->last_query();
        if ($this->input->post('sid')) {
            echo json_encode($query);
        }
        return $query;
    }


    function CheckExistEngineNo_RR310() {
        $engine_no = $this->input->post('vehicle_detail');
        if (!empty($engine_no)) {
            $vehicle_detail = $engine_no;
            $where = array('engine_no' => $engine_no);
            $status = $this->HomeMdl->checkDuplicatePolicy($vehicle_detail);
            $end_date = date("d/M/Y", strtotime($fetch_exist_data['end_date']));
            $explode_end_date = explode(' ', $fetch_exist_data['end_date']);
            if ($status) {
                $result['status'] = 'exist';
                $result['end_date'] = '';
            } else {
                $policy_data = $this->getTvsRsaPolicyData(strtoupper($engine_no));
                // echo '<pre>'; print_r($policy_data);die('hello');
                if (!empty($policy_data)) {
                    $result['status'] = 'new_policy';
                    $regis_no = $policy_data['vehicle']['registration_no'];
                    $regis_no = explode('-', $regis_no);
                    $name = $policy_data['customer']['insured_name'];
                    $insured_nominee_name = $policy_data['customer']['insured_nominee_name'];
                    if($policy_data['Owner_Gender']=='M'){
                        $gender ='male';
                    }elseif($policy_data['Owner_Gender']=='F'){
                        $gender ='female';
                    }
                    $dob_ar = explode(' ', $policy_data['Owner']['owner_DOB']);
                    $dob = date("m/d/Y", strtotime($dob_ar[0]));
                    $name = explode(' ', $name);
                    $fname = $name[0];
                    $lname = $name[2];
					$today_date = date('d-m-Y');
					
				    $reg_date = ($policy_data['vehicle']['registration_date']=='') ? $today_date : date('d-m-Y',strtotime($policy_data['vehicle']['registration_date']));
					
					
					$age_of_vehicle = $this->dateDiffInDays($today_date, $reg_date);
					// echo '<pre>';print_r($age_of_vehicle);die('hello');
                    if($age_of_vehicle > 90){
                        $plan_id = '63';        
                    }else{
                        $plan_id = '62';
                    }

   
					
                    $customer_and_vehicle_details = array(
                        'product_type' => 1,
                        'engine_no' => trim($policy_data['vehicle']['engine_no']),
                        'chassis_no' => trim($policy_data['vehicle']['chassis_no']),
                        'manufacturer' => 11,
                        'model' => 'APACHE RR 310',//trim($policy_data['vehicle']['model_name']),
                        'Insurance_Company_name' => trim($policy_data['Insurance_Company_name']),
                        'insurance_company_id' => trim($policy_data['insurance_company_id']),
                        'model_id' => trim($policy_data['vehicle']['model_id']),
                        'odometer_readng' => trim($policy_data['vehicle']['odometer_readng']),
                        'fuel_type' => trim($policy_data['vehicle']['fuel_type']),
                        'mfg_date' => trim($policy_data['vehicle']['mfg_date']),
                        'color' => trim($policy_data['vehicle']['color']),
                        'registration_no' => trim($policy_data['vehicle']['registration_no']),
                        'registration_date' => $reg_date,
						'age_of_vehicle' => $age_of_vehicle,
                        'reg1' => empty($regis_no[0])?trim($policy_data['state_id']):trim($regis_no[0]),
                        'reg2' => trim($regis_no[1]),
                        'reg3' => trim($regis_no[2]),
                        'reg4' => trim($regis_no[3]),
                        'first_name' => trim($fname),
                        'last_name' => trim($lname),
                        'dob'=>($dob=='01/01/1900')? '' : $dob,
                        'insured_nominee_name' => trim($insured_nominee_name),
                        'email' => trim($policy_data['customer']['insured_email_id']),
                        'mobile_no' => trim($policy_data['insured_mobile_no']),
                        'cust_addr1' => trim($policy_data['customer']['insured_address1']),
                        'cust_addr2' => trim($policy_data['customer']['insured_address2']),
                        'state' => trim($policy_data['customer']['insured_state']),
                        'city' => trim($policy_data['customer']['insured_city']),
                        'pin' => trim($policy_data['customer']['insured_pincode']),
                        'nominee_age' => ($policy_data['Owner']['Nominee_Age']==0)?'':$policy_data['Owner']['Nominee_Age'],
                        'nominee_full_name'=>($policy_data['customer']['insured_nominee_name'])?$policy_data['customer']['insured_nominee_name']:'',
                        'nominee_relation'=>($policy_data['Owner']['Nominee_Relation'])?strtolower($policy_data['Owner']['Nominee_Relation']):'',
                        'nominee_gender' => ($policy_data['Owner']['Nominee_Gender'])?$policy_data['Owner']['Nominee_Gender']:'',
                        'gender'=> $gender,
                        'plan_id'=>$plan_id,
                        'product_type_id' => 1
                    );
                    $result['data'] = $customer_and_vehicle_details;


                    // echo '<pre>'; print_r($customer_and_vehicle_details);die('here');
                   $this->session->set_userdata('dms_response', $policy_data);
                } else {
                    $result['status'] = 'no_response';
                }
            }
            echo json_encode($result);
        }
    }
	
	function dateDiffInDays($date1, $date2)  
	{ 
		// Calulating the difference in timestamps 
		$diff = strtotime($date2) - strtotime($date1); 
		  
		// 1 day = 24 hours 
		// 24 * 60 * 60 = 86400 seconds 
		return abs(round($diff / 86400)); 
	} 

    function getTvsRsaPolicyData($engineNo) {
        $newArray = array();
        //tokenexe='INSR201808160900|87000001|16/08/2018'
        $currentDate = date("d/m/Y");
        $data = '';
        $salt = 'INSR201808160900';
        $accessToken = $salt . '|' . $engineNo . '|' . $currentDate;
        $dataArr = array();
        $dataArr['engineno'] = $engineNo;
        $dataArr['token'] = strtoupper(hash('sha512', $accessToken));
        $dataString = json_encode($dataArr);
        $soap_url="https://www.advantagetvs.com/PolicyServices/PolicyService.asmx?WSDL=";
        
        $soap_body = '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <get_policy_detail xmlns="http://tempuri.org/">
                    <id><![CDATA['.$dataString.']]></id>
                </get_policy_detail>
            </Body>
        </Envelope>';
        //echo $soap_body;
        $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $soap_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $soap_body,
            CURLOPT_HTTPHEADER => array(
              "cache-control: no-cache",
              "content-type: text/xml",
              "postman-token: 27683099-7f6e-34ea-13db-3785ec39b201"
            ),
        ));
    $response = curl_exec($curl);
    $file_contents = str_replace('<?xml version="1.0" encoding="utf-8"?>', "", $response);
    $file_contents = str_replace('<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body>', "", $file_contents);
    $file_contents = str_replace('<get_policy_detailResponse xmlns="http://tempuri.org/" /></soap:Body></soap:Envelope>', "", $file_contents);
    
        $original_value = array("\n", "\r","\\");
        $new_value = array("", ""," ");
        // $original_value = array("\n", "\r","\\",'"Nominee_Gender":"F",','"Nominee_Gender":"M",','"make_id":"1"}');
        // $new_value = array("", ""," ","","",'"make_id":"1"}}');
        $body = str_replace($original_value, $new_value, $file_contents);
        $array = json_decode(($body), TRUE);
        $ERROR_CODES = (isset($array['ERROR_CODES']) ? $array['ERROR_CODES'] : '');
        if ($ERROR_CODES == '') {
            $newArray = $array;
        }
        return $newArray;
    }

     public function fetch_model_RR310() {
       $model =  "APACHE RR 310";//$this->input->post('model');
       $model = isset($model)?$model:'';
       // echo '<pre>'; print_r($post_data);die('hello');
        $policyid= $this->input->post('policyid');
        $policy_data = $this->HomeMdl->getPolicyById($policyid);
        // echo '<pre>';print_r($policy_data);die;
        $policy_data['model_name'] = "APACHE RR 310";
        $selected_model = $policy_data['model_name'];
        $models = $this->HomeMdl->getDataFromTableWithOject('tvs_model_master');
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
    function get_Plan_Details() {
        $post_data = $this->input->post();
        $post_data = $post_data['form_data'];
        $data = array();
        parse_str($post_data, $data);
        $this->session->set_userdata('form_data', $data);

        $query = $this->db->select('id,insurance_company_id,name,price,price_with_gst')
            ->from('tvs_plan_india_assistance')
            ->where('product_type_id', '1')
            ->order_by('name', 'ASC')
            ->get()
            ->row();

        $this->session->set_userdata('plan_details', $query);

        echo json_encode($query);
    }

    function valid_date($date) {
        $this->form_validation->set_message('valid_date', "Please enter a valid date");
        $d = DateTime::createFromFormat('y-m-d', $date);
        // $current_date = date('d/m/Y');
        if (($d && $d->format('y-m-d') === $date) || $date != '') {
            return true;
        } else {
            return false;
        }
    }

     function checkDuplicateEntries(){
            $engine_no = $this->input->post('engine_no');
            $status = $this->HomeMdl->checkDuplicatePolicy($engine_no);
            $return_data['status'] = ($status)?'true':'false';
        
         echo json_encode($return_data);
   }

function onlyRsaGeneratePolicy() {
        $post_data = $this->input->post();
       
        // echo "<pre>"; print_r($post_data); echo "</pre>"; die;
        $policy_id = $post_data['policyid'];
        $city_id = $post_data['city_id'];
        $state_id = $post_data['state_id'];
        $this->load->helper('form');
        $this->load->library('form_validation');
        if (empty($policy_id)) {
            $this->form_validation->run('only_rsa_form');
            if ($this->form_validation->run() == FALSE) {

                // die('hello moto2');
                 $this->set_validation();
                redirect('generate_policy_rr310');
            } else {

                // die('hello moto3');
               $where = array('id'=>$post_data['plan_rr310']);
               $plan_details  = $this->HomeMdl->getRowDataFromTable('tvs_plans',$where);
               if($post_data['plan_rr310'] == 62){
                    $plan_details['plan_amount'] = 0;
                    $plan_details['gst_amount'] = 0;
               }
               $user_data = $this->session->userdata('user_session');
                $where =array('dealer_id'=>$user_data['id']);
                $dealer_wallet_details  = $this->HomeMdl->getRowDataFromTable('dealer_wallet',$where);
                $wallet_balance = ($dealer_wallet_details['security_amount'] - $dealer_wallet_details['credit_amount']);
                if($wallet_balance < ($plan_details['plan_amount'] + $plan_details['gst_amount'])){
                     $this->session->set_flashdata('message', 'Wallet Amount Is Less Than Policy Amount.');
                     redirect('generate_policy_rr310');
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
                            'fname' => trim($this->input->post('first_name')),
                            'lname' => trim($this->input->post('last_name')),
                            'email' => trim($this->input->post('email')),
                            'mobile_no' => trim($this->input->post('mobile_no')),
                            'gender' => $this->input->post('gender'),
                            'dob' => $dob,
                            'addr1' => trim($this->input->post('cust_addr1')),
                            'addr2' => trim($this->input->post('cust_addr2')),
                            'state' => $this->input->post('state_id'),
                            'city' => $this->input->post('city_id'),
                            'state_name' => $this->input->post('state'),
                            'city_name' => $this->input->post('city'),
                            'pincode' => $this->input->post('pin'),
                            'created_date' => date('Y-m-d H:i:s')
                        );
                    // echo '<pre>'; print_r($insert_customer_detail);die('hello');
                        $inserted_customer_detail = $this->db->insert('tvs_customer_details', $insert_customer_detail);
                        $customer_detail_last_id = $this->db->insert_id();
                        // $customer_detail_last_id = 1;
                        if ($customer_detail_last_id) {
                            $result_sold = $this->GeneratePolicyNo();
                            $date_result = $this->StartEndDate($post_data,$plan_details);
                            $effective_date = $date_result['effective_date'];
                            $end_date = $date_result['end_date'];
                            $model_name = $this->getMakeModelName('model', $model_id);
                            $transection_no = $this->getRandomNumber('16');
                            
                             // echo '<pre>'; 
                             // print_r($post_data);
                             // print_r($master_policy_details);
                             // die('here');
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
                                'pa_sold_policy_effective_date' => $effective_date,
                                'pa_sold_policy_end_date' => $end_date,
                                'sold_policy_price_with_tax' => ($plan_details['plan_amount']+$plan_details['gst_amount']),
                                'sold_policy_price_without_tax' => $plan_details['plan_amount'],
                                'sold_policy_tax' => '18%',
                                'sold_policy_igst' => '18',
                                'engine_no' => trim($post_data['engine_no']),
                                'chassis_no' => trim($post_data['chassis_no']),
                                'make_id' => '11',
                                'model_id' => $model_id,
                                'make_name' => 'TVS',
                                'model_name' => $model_name,
                                'vehicle_registration_no' => strtoupper($final_reg_no),
                                'ic_id' => 0,
                                'mp_id' => 0,
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
                            // echo $this->db->last_query();
                            if (!empty($inserted_id)) {
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
                                        if($post_data['plan_rr310'] == 62){
                                            $status = true;
                                        }else{
                                            $where = array('dealer_id'=>$user_data['id']);
                                            $wallet_details = $this->HomeMdl->getDataFromTable('dealer_wallet',$where);
                                            $wallet_details = $wallet_details[0];

                                            $wallet_amount = (($wallet_details['credit_amount'] + $policy_amount) - $plan_details['dealer_commission']);
                                            $data = array(
                                                'credit_amount'=> $wallet_amount
                                            );
                                            $where = array('dealer_id'=>$user_data['id']);
                                            $status = $this->HomeMdl->updateTable('dealer_wallet',$data,$where);
                                        }
                                        if($status){
                                            $data = array(
                                                'policy_no'=>$result_sold['sold_policy_no'],
                                                'policy_id'=>$inserted_id,
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
                                                'policy_id'=>$inserted_id,
                                                'dealer_id'=> $user_data['id'],
                                                'transection_no' => $transection_no,
                                                'transection_type'=>'cr',
                                                'transection_amount'=>$plan_details['dealer_commission'],
                                                'transection_purpose'=>'Commission'
                                            );
                                            $this->HomeMdl->insertIntoTable('dealer_transection_statement',$data);
                                        }
                            }
                            //$this->sendSms($mobile_no, $inserted_id);
                            $hostName = $_SERVER['HTTP_HOST'];
                            if ($hostName != 'localhost' && !empty($post_data['email'])) {
                                    $this->MailSoldPolicyPdf($inserted_id);
                                }
                            $this->data['ic_pdf'] = 'download_rsa_pdf';
                            $this->data['inserted_id'] = $inserted_id;
                            $this->load->dashboardTemplate('front/myaccount/success', $this->data);
                            // die('hello moto:-'.$getic_map['pa_ic_id']);
                            
                        }else{
                             die('something went wrong!');
                        }
                    }
                }
            }
        }
               
    } else {
            $where = array('id'=>$policy_id);
            $policy_details = $this->HomeMdl->getRowDataFromTable('tvs_sold_policies',$where);
               $user_data = $this->session->userdata('user_session');
               $where = array('id'=>$post_data['plan_rr310']);
               $plan_details = $this->HomeMdl->getDataFromTable('tvs_plans',$where);
                $dob = date("Y-m-d", strtotime($this->input->post('dob')));
               $update_customer_detail = array(
                    'fname' => trim($this->input->post('first_name')),
                    'lname' => trim($this->input->post('last_name')),
                    'email' => trim($this->input->post('email')),
                    'mobile_no' => trim($this->input->post('mobile_no')),
                    'dob' => $dob,
                    'addr1' => trim($this->input->post('cust_addr1')),
                    'addr2' => trim($this->input->post('cust_addr2')),
                    'state' => $this->input->post('state'),
                    'city' => $this->input->post('city'),
                    'state_name' => $this->input->post('state'),
                    'city_name' => $this->input->post('city'),
                    'pincode' => $this->input->post('pin'),
                    'gender' => $post_data['gender'],
                    'updated_at' => date('Y-m-d H:i:s')
                ); 
            $this->db->where("id",$policy_details['customer_id']);
            $updated_cust = $this->db->update("tvs_customer_details", $update_customer_detail);
            $update_customer_detail['policy_id'] = $policy_id;
            $update_customer_detail['updated_at'] = date('Y-m-d H:i:s');
            // $update_customer_detail['created_at'] = date('Y-m-d H:i:s');
            $inserted_endors_customer_id = $this->HomeMdl->insertIntoTable('tvs_endorse_customer_details',$update_customer_detail);
            if (!empty($updated_cust)) {
                $pa_ic_id = $policy_details['ic_id'];
                $domainName = $_SERVER['HTTP_HOST'];
                if ($domainName != 'localhost') {
                    $this->MailSoldPolicyPdf($policy_id);
                }
                $this->data['ic_pdf'] = 'download_rsa_pdf';
                $this->data['inserted_id'] = $policy_id;
                $this->data['endorsement'] = 'yes';
                $this->load->dashboardTemplate('front/myaccount/success', $this->data);
            }
        // }
    }
}


    function StartEndDate($post_data,$plan_details){
        $start_date = date('Y-m-d');
        $result['effective_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "1 day")) . "0 year")) . ' 00:00:01';
        $result['end_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "-1 day")) . "1 year")) . ' 23:59:59';
        return $result ;
    }
    function getRandomNumber($len)
        {
            $better_token = $code=sprintf("%0".$len."d", mt_rand(1, str_pad("", $len,"9")));
            return $better_token;
        }

    function isAllowedToPunchPolicy(){
        $post_data = $this->input->post();
        echo '<pre>'; print_r($post_data);
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

    function MailSoldPolicyPdf($inserted_id) {
        $policypdf_obj = $this->DownloadRsaPolicy($inserted_id);
        $status = $this->MailAttachments($policypdf_obj, $inserted_id);

    }
function DownloadOnlyRsaPolicy($id){
  $rsa_policy_data = $this->HomeMdl->getPolicyById($id);
  $getDealerInfo = $this->HomeMdl->getDealerinfo($rsa_policy_data['user_id']);
  // echo '<pre>';print_r($getDealerInfo);die;
  $rsa_name = $getDealerInfo['name'];
  $rsa_logo = base_url($getDealerInfo['logo']);
  $rsa_address = $getDealerInfo['address'];
  $rsa_email = $getDealerInfo['email'];
  $customer_care_no = $getDealerInfo['toll_free_no'];
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
  $email = isset($rsa_policy_data['email']) ? strtoupper($rsa_policy_data['email']) : '--';
  $mobile_no = isset($rsa_policy_data['mobile_no']) ? strtoupper($rsa_policy_data['mobile_no']) : '--';
  $gender = isset($rsa_policy_data['gender']) ? strtoupper($rsa_policy_data['gender']) : '--';
  $dob = isset($rsa_policy_data['dob']) ? strtoupper($rsa_policy_data['dob']) : '--';
  $addr1 = isset($rsa_policy_data['addr1']) ? strtoupper($rsa_policy_data['addr1']) : '--';
  $addr2 = isset($rsa_policy_data['addr2']) ? strtoupper($rsa_policy_data['addr2']) : '--';
  $state_name = isset($rsa_policy_data['state_name']) ? strtoupper($rsa_policy_data['state_name']) : '--';
  $city_name = isset($rsa_policy_data['city_name']) ? strtoupper($rsa_policy_data['city_name']) : '--';
  $sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '--';
  $sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date'] : '--';
  $model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';
   if($plan_detalis['id'] == 62){
        $payment_details_tag = '';
    }else{
        $payment_details_tag = '<tr>
            <td colspan="3" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt;">&nbsp; PAYMENT DETAILS</td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Plan Amount(Rs.)</b></td>

                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Tax Amount(18% IGST in Rs.)</b></td>
                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Total Amount (Rs.)</b></td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">'.$plan_amount.'</td>
                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">'.$gst_amount.'</td>
                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">'.$total_amount.'</td>
                    </tr>

                </tbody>
              </table>
            </td>
          </tr>';
        }

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
$html = <<<EOD
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
  .headertext {font-size:16pt; line-height:40pt; color:#fff;}
  .border, .boxtable td {border:0.2px solid #000;}
  .sectionhead { font-size:7.5pt; line-height:12pt; background-color:#000; color:#fff;}
  .footer {border-top:0.5px solid #808080; text-align:center; line-height:9pt;}

  .asistance-table { font-size:7pt; line-height:9pt;} 
  .dotborderleft {border-left:0.5px dotted #014e9f}
  .dotborderright {border-right:0.5px dotted #014e9f}
  .asistance-sectionhead { font-size:9pt; line-height:10pt; background-color:#63a5ea; color:#fff;}  
</style>
  <table width="100%" cellpadding="0" border="0" cellspacing="0" class="pagewrap">
   <tr>
      <td><img src="assets/images/mpdf/banner.jpg" alt="" style="width:800px;"></td>
    </tr>
    <tr>
      <td style="height:30;"></td>
    </tr>
    <tr>
      <td>
        <table width="100%" cellpadding="0" border="0" cellspacing="0">
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td colspan="2" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 9pt;">CERTIFICATE</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Certificate Number</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$certificate_no</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Plan Name</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$plan_name</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Certificate issue Date</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$created_date</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>RSA Start Date</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$sold_policy_effective_date</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>RSA End Date</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$sold_policy_end_date</td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td colspan="2" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height:9pt;">VEHICLE</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Vehicle Registration Number</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$vehicle_registration_no</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Manufacturer</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">TVS</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Model</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$model_name</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Engine Number </b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$engine_no</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Chassis Number</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$chassis_no</td>
                    </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td style="height: 15px;">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt;">&nbsp; PERSONAL DETAILS</td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>First Name</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$fname</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Last Name</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$lname</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Mobile No</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$mobile_no</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Email ID </b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$email</td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Address 1 </b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$addr1</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Address 2</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$addr2</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>State</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$state_name</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>City</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$city_name</td>
                    </tr>
                </tbody>
              </table>
            </td>
          </tr>
            <tr>
            <td style="height: 10px;"></td>
          </tr>
           {$payment_details_tag}
          <tr>
            <td style="height: 20px;"></td>
          </tr>
          <tr>
            <td colspan="3" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt;">&nbsp;  CHANNEL PARTNER: TVS</td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 9pt;">INDICOSMIC CAPITAL</td>
                    </tr>
                    <tr>
                      <td style="  height: 80px; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><img src="assets/images/mpdf/icpl-logo.jpg" alt=""><br><b>Address:</b> 318, 3rd Floor, Summit-Business Bay, Behind Gurunanak Petrol Pump, Off Andheri Kurla Road, Beside Magic Bricks WEH metro stn., Andheri (E),Mumbai-400093, Maharashtra (India)</td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height:9pt;">{$rsa_name}</td>
                    </tr>
                    <tr>
                      <td style=" height: 80px; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><img src="{$rsa_logo}" alt="" style="height:30px"><br><b>Address:</b>  {$rsa_address}</td>
                    </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 9pt;">EMAIL : info@indicosmic.com</td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 9pt;">EMAIL : {$rsa_email}</td>
                    </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td colspan="3" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt; text-align: center;">CUSTOMER CARE NO: {$customer_care_no}</td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td colspan="3" col style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt;">&nbsp; THE SERVICES PROVIDED UNDER THE ASSISTANCE ARE AS FOLLOWS:</td>
          </tr>
        </table>
      </td>
   </tr>

  </table>
<br pagebreak="true"/>
  <table cellpadding="0" border="0" cellspacing="0" class="asistance-table">         
          <tr>          
            <td width="48%">
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Coverage</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/coverage.jpg"></td>
                  <td width="82%">The territorial scope of the Roadside Assistance Services provided by ICPL shall be only ligible to customers.</td>
                </tr>
              </table>
            </td>
            <td width="2%" class=""></td>
            <td width="2%" class="dotborderleft"></td>
            <td width="48%">
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Coverage in North East and J&K Coverage In Islands</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/north-east-and-jk.jpg"></td>
                  <td width="82%">Due to the extreme geographical conditions, ICPL will not provide RSA services in North Eastern States and Jammu & Kashmir. (Specific cities might be covered based on ICPL’s requirement). SLAs will not be applicable for services rendered in these territories. ICPL will not provide RSA services in Islands</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Toll Free</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/toll-free.jpg"></td>
                  <td width="82%">24 X 7 multi lingual support</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Onsite support for Minor repairs</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/onsite-repair.jpg"></td>
                  <td width="82%">In the event the Vehicle covered under this Agreement (Covered Vehicle) having a breakdown due to minor mechanical/ electrical fault, ICPL shall support by arranging vehicle technician to the breakdown location. ICPL will bear the labor cost and conveyance charges. Cost of spare parts if required to repair the vehicle on the spot (Outside coverage area) to obtain such material & spare parts will be borne by the Customer.</td>
                </tr>
              </table>
            </td>
          </tr> 
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Rundown of Battery</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/jump-start.jpg"></td>
                  <td width="82%">In the event the Covered Vehicle having a breakdown due to rundown of battery, ICPL shall support by arranging vehicle technician to jump start the vehicle with appropriate means. ICPL will bear the labor cost and conveyance charges.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Flat Tyre</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/flat-tyre.jpg"></td>
                  <td width="82%">In the event that the Covered Vehicle has a puncture or tyre burst, ICPL shall support the Customer in replacing the flat tyre with spare tyre. The technician will repair the same at the location of breakdown.In case of nonavailability of spare tyre, ICPL will try to repair the faulty tyre. This service is based on the availability of tyre repair shop near the breakdown location. All the cost related to rendering such service will be charged to customers.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Towing Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/towing.jpg"></td>
                  <td width="82%">In the event that a Covered Vehicle is immobilized due to electrical or mechanical failure, ICPL shall arrange towing of the Covered Vehicle to the nearest authorized outlet. These services shall be provided using equipment/s deemed most suitable by ICPL. Towing from incident to nearest tvs service center is free.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Urgent Message Relay</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/msg-relay.jpg"></td>
                  <td width="82%">Relay of Urgent message to family / friends in case of medical emergency.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Emergency Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/med-cordination.jpg"></td>
                  <td width="82%">Medical co-ordination for occupants of the vehicle as a result of accidents.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Fuel Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/fuel.jpg"></td>
                  <td width="82%">In the event Covered Vehicle runs out of fuel or stops due to contaminated fuel, ICPL will provide support by arranging up to 2 liters of fuel. The supply of fuel will be based on availability. ICPL will bear the labor cost and conveyance charges. Fuel charges shall be borne by Customer. This service will not be applicable if the vehicle is at Customer residence This service is based on local availability of fuel.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Key Lost / Replacement</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/spare-key.jpg"></td>
                  <td width="82%">In the event of Key Lost / Replacement, if possible we will arrange replacement key, else vehicle will be towed to nearest garage as per (7) above.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Taxi Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/taxi.jpg"></td>
                  <td width="82%">In the event that a Covered Vehicle is immobilized due to the breakdown or accident and On-site preliminary support to make the vehicle roadworthy, is not possible, ICPL shall arrange and bear the expense for transferring the Covered Vehicle to the nearest authorized outlet. To help the Customer continue with his journey, ICPL will arrange taxi support to the Customer as per availability. SLAs will not be applicable for taxi assistance and ICPL does not guarantee the quality of taxi services.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Accommodation Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/hotel.jpg"></td>
                  <td width="82%">Arranging for hotel accommodation in case breakdown is reported outside customer’s home city. Co-ordination is free and all the related accommodation cost will be borne by the customer.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Outward / Forward Journey</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/outward-forward.jpg"></td>
                  <td width="82%">Arranging for Outward / Forward Journey in case breakdown is reported outside customer’s home city Co-ordination is free, all the related travel cost will beborne by the customer.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Arrangement of Rental Vehicle</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/rent.jpg"></td>
                  <td width="82%">Arranging rental vehicle in case breakdown is reported outside customer’s home city Co-ordination is free, all the related rental vehicle cost will be borne by the customer.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td></td>
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
    $result= $pdf->Output('RSA-Certificate.pdf', 'I');
   return $result;
}

function DownloadRsaPolicy($id){
 $rsa_policy_data = $this->HomeMdl->getPolicyById($id);
  $getDealerInfo = $this->HomeMdl->getDealerinfo($rsa_policy_data['user_id']);
  // echo '<pre>';print_r($getDealerInfo);die;
  $rsa_name = $getDealerInfo['name'];
  $rsa_logo = base_url($getDealerInfo['logo']);
  $rsa_address = $getDealerInfo['address'];
  $rsa_email = $getDealerInfo['email'];
  $customer_care_no = $getDealerInfo['toll_free_no'];
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
  $email = isset($rsa_policy_data['email']) ? strtoupper($rsa_policy_data['email']) : '--';
  $mobile_no = isset($rsa_policy_data['mobile_no']) ? strtoupper($rsa_policy_data['mobile_no']) : '--';
  $gender = isset($rsa_policy_data['gender']) ? strtoupper($rsa_policy_data['gender']) : '--';
  $dob = isset($rsa_policy_data['dob']) ? strtoupper($rsa_policy_data['dob']) : '--';
  $addr1 = isset($rsa_policy_data['addr1']) ? strtoupper($rsa_policy_data['addr1']) : '--';
  $addr2 = isset($rsa_policy_data['addr2']) ? strtoupper($rsa_policy_data['addr2']) : '--';
  $state_name = isset($rsa_policy_data['state_name']) ? strtoupper($rsa_policy_data['state_name']) : '--';
  $city_name = isset($rsa_policy_data['city_name']) ? strtoupper($rsa_policy_data['city_name']) : '--';
  $sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '--';
  $sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date'] : '--';
  $model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';
   if($plan_detalis['id'] == 62){
        $payment_details_tag = '';
    }else{
        $payment_details_tag = '<tr>
            <td colspan="3" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt;">&nbsp; PAYMENT DETAILS</td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Plan Amount(Rs.)</b></td>

                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Tax Amount(18% IGST in Rs.)</b></td>
                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Total Amount (Rs.)</b></td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$plan_amount</td>
                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$gst_amount</td>
                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$total_amount</td>
                    </tr>

                </tbody>
              </table>
            </td>
          </tr>';
        }

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
$html = <<<EOD
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
  .headertext {font-size:16pt; line-height:40pt; color:#fff;}
  .border, .boxtable td {border:0.2px solid #000;}
  .sectionhead { font-size:7.5pt; line-height:12pt; background-color:#000; color:#fff;}
  .footer {border-top:0.5px solid #808080; text-align:center; line-height:9pt;}

  .asistance-table { font-size:7pt; line-height:9pt;} 
  .dotborderleft {border-left:0.5px dotted #014e9f}
  .dotborderright {border-right:0.5px dotted #014e9f}
  .asistance-sectionhead { font-size:9pt; line-height:10pt; background-color:#63a5ea; color:#fff;}  
</style>
  <table width="100%" cellpadding="0" border="0" cellspacing="0" class="pagewrap">
   <tr>
      <td><img src="assets/images/mpdf/banner.jpg" alt="" style="width:800px;"></td>
    </tr>
    <tr>
      <td style="height:30;"></td>
    </tr>
    <tr>
      <td>
        <table width="100%" cellpadding="0" border="0" cellspacing="0">
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td colspan="2" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 9pt;">CERTIFICATE</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Certificate Number</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$certificate_no</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Plan Name</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$plan_name</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Certificate issue Date</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$created_date</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>RSA Start Date</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$sold_policy_effective_date</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>RSA End Date</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$sold_policy_end_date</td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td colspan="2" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height:9pt;">VEHICLE</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Vehicle Registration Number</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$vehicle_registration_no</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Manufacturer</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">TVS</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Model</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$model_name</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Engine Number </b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$engine_no</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Chassis Number</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$chassis_no</td>
                    </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td style="height: 15px;">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt;">&nbsp; PERSONAL DETAILS</td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>First Name</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$fname</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Last Name</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$lname</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Mobile No</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$mobile_no</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Email ID </b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$email</td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Address 1 </b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$addr1</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Address 2</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$addr2</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>State</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$state_name</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>City</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">$city_name</td>
                    </tr>
                </tbody>
              </table>
            </td>
          </tr>
            <tr>
            <td style="height: 10px;"></td>
          </tr>
           {$payment_details_tag}
          <tr>
            <td style="height: 20px;"></td>
          </tr>
          <tr>
            <td colspan="3" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt;">&nbsp;  CHANNEL PARTNER: TVS</td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 9pt;">INDICOSMIC CAPITAL</td>
                    </tr>
                    <tr>
                      <td style="  height: 80px; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><img src="assets/images/mpdf/icpl-logo.jpg" alt=""><br><b>Address:</b> 318, 3rd Floor, Summit-Business Bay, Behind Gurunanak Petrol Pump, Off Andheri Kurla Road, Beside Magic Bricks WEH metro stn., Andheri (E),Mumbai-400093, Maharashtra (India)</td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height:9pt;">{$rsa_name}</td>
                    </tr>
                    <tr>
                      <td style=" height: 80px; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><img src="{$rsa_logo}" alt="" style="height:30px"><br><b>Address:</b>  {$rsa_address}</td>
                    </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 9pt;">EMAIL : info@indicosmic.com</td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 9pt;">EMAIL : {$rsa_email}</td>
                    </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td colspan="3" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt; text-align: center;">CUSTOMER CARE NO: {$customer_care_no}</td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td colspan="3" col style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt;">&nbsp; THE SERVICES PROVIDED UNDER THE ASSISTANCE ARE AS FOLLOWS:</td>
          </tr>
        </table>
      </td>
   </tr>

  </table>
<br pagebreak="true"/>
  <table cellpadding="0" border="0" cellspacing="0" class="asistance-table">         
          <tr>          
            <td width="48%">
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Coverage</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/coverage.jpg"></td>
                  <td width="82%">The territorial scope of the Roadside Assistance Services provided by ICPL shall be only ligible to customers.</td>
                </tr>
              </table>
            </td>
            <td width="2%" class=""></td>
            <td width="2%" class="dotborderleft"></td>
            <td width="48%">
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Coverage in North East and J&K Coverage In Islands</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/north-east-and-jk.jpg"></td>
                  <td width="82%">Due to the extreme geographical conditions, ICPL will not provide RSA services in North Eastern States and Jammu & Kashmir. (Specific cities might be covered based on ICPL’s requirement). SLAs will not be applicable for services rendered in these territories. ICPL will not provide RSA services in Islands</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Toll Free</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/toll-free.jpg"></td>
                  <td width="82%">24 X 7 multi lingual support</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Onsite support for Minor repairs</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/onsite-repair.jpg"></td>
                  <td width="82%">In the event the Vehicle covered under this Agreement (Covered Vehicle) having a breakdown due to minor mechanical/ electrical fault, ICPL shall support by arranging vehicle technician to the breakdown location. ICPL will bear the labor cost and conveyance charges. Cost of spare parts if required to repair the vehicle on the spot (Outside coverage area) to obtain such material & spare parts will be borne by the Customer.</td>
                </tr>
              </table>
            </td>
          </tr> 
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Rundown of Battery</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/jump-start.jpg"></td>
                  <td width="82%">In the event the Covered Vehicle having a breakdown due to rundown of battery, ICPL shall support by arranging vehicle technician to jump start the vehicle with appropriate means. ICPL will bear the labor cost and conveyance charges.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Flat Tyre</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/flat-tyre.jpg"></td>
                  <td width="82%">In the event that the Covered Vehicle has a puncture or tyre burst, ICPL shall support the Customer in replacing the flat tyre with spare tyre. The technician will repair the same at the location of breakdown.In case of nonavailability of spare tyre, ICPL will try to repair the faulty tyre. This service is based on the availability of tyre repair shop near the breakdown location. All the cost related to rendering such service will be charged to customers.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Towing Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/towing.jpg"></td>
                  <td width="82%">In the event that a Covered Vehicle is immobilized due to electrical or mechanical failure, ICPL shall arrange towing of the Covered Vehicle to the nearest authorized outlet. These services shall be provided using equipment/s deemed most suitable by ICPL. Towing from incident to nearest tvs service center is free.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Urgent Message Relay</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/msg-relay.jpg"></td>
                  <td width="82%">Relay of Urgent message to family / friends in case of medical emergency.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Emergency Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/med-cordination.jpg"></td>
                  <td width="82%">Medical co-ordination for occupants of the vehicle as a result of accidents.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Fuel Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/fuel.jpg"></td>
                  <td width="82%">In the event Covered Vehicle runs out of fuel or stops due to contaminated fuel, ICPL will provide support by arranging up to 2 liters of fuel. The supply of fuel will be based on availability. ICPL will bear the labor cost and conveyance charges. Fuel charges shall be borne by Customer. This service will not be applicable if the vehicle is at Customer residence This service is based on local availability of fuel.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Key Lost / Replacement</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/spare-key.jpg"></td>
                  <td width="82%">In the event of Key Lost / Replacement, if possible we will arrange replacement key, else vehicle will be towed to nearest garage as per (7) above.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Taxi Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/taxi.jpg"></td>
                  <td width="82%">In the event that a Covered Vehicle is immobilized due to the breakdown or accident and On-site preliminary support to make the vehicle roadworthy, is not possible, ICPL shall arrange and bear the expense for transferring the Covered Vehicle to the nearest authorized outlet. To help the Customer continue with his journey, ICPL will arrange taxi support to the Customer as per availability. SLAs will not be applicable for taxi assistance and ICPL does not guarantee the quality of taxi services.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Accommodation Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/hotel.jpg"></td>
                  <td width="82%">Arranging for hotel accommodation in case breakdown is reported outside customer’s home city. Co-ordination is free and all the related accommodation cost will be borne by the customer.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Outward / Forward Journey</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/outward-forward.jpg"></td>
                  <td width="82%">Arranging for Outward / Forward Journey in case breakdown is reported outside customer’s home city Co-ordination is free, all the related travel cost will beborne by the customer.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Arrangement of Rental Vehicle</td>
                </tr>
                <tr>
                  <td width="18%"><img src="assets/images/mpdf/rent.jpg"></td>
                  <td width="82%">Arranging rental vehicle in case breakdown is reported outside customer’s home city Co-ordination is free, all the related rental vehicle cost will be borne by the customer.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td></td>
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
    $result= $pdf->Output('RSA-Certificate.pdf', 'S');
   return $result;
}




   

    function MailAttachments($policypdf_obj, $sold_policy_detail_last_id) {
        $rsa_policy_data = $this->HomeMdl->getPolicyById($sold_policy_detail_last_id);
        $rsa_ic_master_id = $this->session->userdata('user_session')['rsa_ic_master_id'];
        $where = array(
            'id' => $rsa_ic_master_id
        );
        $insurance_companies = $this->getRowDataFromTableWithOject('rsa_ic_master', $where);

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

    function sendSms($Mobilenumber, $sold_policy_id) {
        $Message = "Welcome to my TVS RSA to find your policy please click on given link http://myassistancenow.com/tvs/download_kotak_lite_pdf/$sold_policy_id";
        error_reporting(1);
//        $uid = "indicosmic";
//        $pwd = "6620402";
//        $sid = "MPOLNW";
//        $method = "POST";
//        $message = urlencode($Message);
//        $get_url = "https://login.bulksmsgateway.in/unicodesmsapi.php?username=" . $uid . "&password=" . $pwd . "&mobilenumber=" . $Mobilenumber . "&message=" . $message . "&senderid=" . $sid . "&type=3";


        $uid = "MPOLNW";
        $pwd = "2000";
        $sid = "TVSRSA";
        $method = "POST";
        $message = urlencode($Message);
        $get_url = "http://www.k3digitalmedia.in/vendorsms/pushsms.aspx?user=" . $uid . "&password=" . $pwd . "&msisdn=" . $Mobilenumber . "&sid=" . $sid . "&msg=" . $message . "&fl=0&gwid=2";

        function httpGet($url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            $output = curl_exec($ch);
            curl_close($ch);
            return $output;
        }

        $result = httpGet($get_url);

        function old() {
            $ch = curl_init($get_url);

            $curlversion = curl_version();
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP ' . phpversion() . ' + Curl ' . $curlversion['version']);
            curl_setopt($ch, CURLOPT_REFERER, null);

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            if ($method == "POST") {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            } else {
                curl_setopt($ch, CURLOPT_POST, 0);
                curl_setopt($ch, CURLOPT_URL, $get_url);
            }

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            // DO NOT RETURN HTTP HEADERS
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // RETURN THE CONTENTS OF THE CALL
            $buffer = curl_exec($ch);
            $err = curl_errno($ch);
            $errmsg = curl_error($ch);
            $header = curl_getinfo($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $_SESSION['buffer'] = $err . "   " . $errmsg . "   " . $buffer;
            $_SESSION['curlURL'] = $get_url;
            curl_close($ch);
        }

        return $result;
    }

    Function getMakeModelName($type, $id) {
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

    function GeneratePolicyNo($Product_type = NULL) {
        $rsa_ic_master_id = $this->session->userdata('user_session')['rsa_ic_master_id'];
        $where = array(
            'id' => $rsa_ic_master_id
        );
        $insurance_companies = $this->getRowDataFromTableWithOject('tvs_insurance_companies', $where);
        $certificate_no_prefix = $insurance_companies->certificate_no_prefix;
        $certificate_no = $insurance_companies->certificate_no + 1;
        $certificate_no = sprintf("%'010d\n", $certificate_no);
        $data['sold_policy_no'] = $insurance_companies->certificate_no_prefix . '' . $certificate_no;
        $input_data = array(
            'certificate_no' => $certificate_no,
        );
        $this->updateTable('tvs_insurance_companies', $input_data, $where);
        return $data;
    }

    function getRowDataFromTableWithOject($table_name = null, $where = null) {
        if ($where != null) {
            $this->db->where($where);
        }
        return $result = $this->db
            ->from($table_name)
            ->get()
            ->row();
    }

    function updateTable($table, $data, $where) {
        $this->db->where($where);
        $this->db->set($data);
        if ($this->db->update($table)) {
            return true;
        } else {
            return false;
        }
    }

    function SoldPAPolicy() {
        $user_session = $this->session->userdata('user_session');
        if (empty($user_session)) {
            redirect(base_url());
        } else {
            $this->load->dashboardTemplate('front/dashboard/sold_pa_policy');
        }
    }

    function Cancelled_list(){
        $user_session = $this->session->userdata('user_session');
        if (empty($user_session)) {
            redirect(base_url());
        } else {
            $this->load->dashboardTemplate('front/dashboard/cancelled_list');
        }
    }

    function CancelledPolicyListAjax(){
        $requestData = $_REQUEST;
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
        $user_id = $session_data['id'];
        $employee_code = $session_data['sap_ad_code'];
        $where = '';
        if(strlen($employee_code) > 5){
            $where = 'AND employee_code = '.$employee_code.'';
        }
        $limit = ' LIMIT ' . $start . ', ' . $length . '';

        $sql = "SELECT tsp.id,tsp.policy_status_id,tsp.product_type_name,tsp.plan_name,tsp.sold_policy_no,tsp.engine_no,
                tsp.chassis_no,tsp.created_date,tcd.fname FROM tvs_sold_policies AS tsp
                 LEFT JOIN tvs_customer_details AS tcd ON tcd.id = tsp.customer_id 
                 WHERE tsp.user_id = '$user_id' AND tsp.`policy_status_id`=5 $where ";
                 
            // die($sql);
        $query = $this->db->query($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
//        $sql = "$sql WHERE  1 = 1";
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND engine_no LIKE '%" . $requestData['search']['value'] . "%' OR invoice_no LIKE '%" . $requestData['search']['value'] . "%' OR invoice_date LIKE '%" . $requestData['search']['value'] . "%' OR chassis_no LIKE '%" . $requestData['search']['value'] . "%' OR certificate_no LIKE '%" . $requestData['search']['value'] . "%' ";
        }
        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY tsp.id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        $data = array();
        $i = 1;
        foreach ($result as $main) {
            $created_date = $main->created_date;
            $created_date = explode(' ', $main->created_date);

            $date = strtotime($created_date[0]);
            $new_date = strtotime('+ 3 day', $date);
            $end_date = date('Y-m-d 23:59:59', $new_date);
            $current_date = date('Y-m-d');

            $row = array();
            $row[] = $i++;
            $row[] = $main->product_type_name;
            $row[] = $main->plan_name;
            $row[] = $main->sold_policy_no;
            $row[] = $main->fname;
            $row[] = $main->engine_no;
            $row[] = $main->chassis_no;
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

    function canclePolicy() {
        $user_session = $this->session->userdata('user_session');
        if (empty($user_session)) {
            redirect(base_url());
        } else {
            $this->load->dashboardTemplate('front/dashboard/cancel_policy');
        }
    }

    function cancleRsaPolicyAjax() {
        $requestData = $_REQUEST;
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
        $user_id = $session_data['id'];
        $employee_code = $session_data['sap_ad_code'];
        $where = '';
        if(strlen($employee_code) > 5){
            $where = 'AND employee_code = '.$employee_code.'';
        }
        $limit = ' LIMIT ' . $start . ', ' . $length . '';
        // $sql = "SELECT tsp.id,tsp.policy_status_id,tsp.product_type_name,tsp.plan_name,tsp.sold_policy_no,tsp.engine_no,tsp.chassis_no,tsp.created_date,tcd.fname FROM tvs_sold_policies AS tsp"
        //     . " LEFT JOIN tvs_customer_details AS tcd "
        //     . " ON tcd.id = tsp.customer_id "
        //     . " WHERE tsp.user_id = $user_id "
        //     . " $where "
        //     . " AND DATE(tsp.created_date) >(NOW() - INTERVAL 1 DAY)";

        $sql = "SELECT tsp.id,tsp.policy_status_id,tsp.product_type_name,tsp.plan_name,tsp.sold_policy_no,tsp.engine_no,
                tsp.chassis_no,tsp.created_date,tcd.fname FROM tvs_sold_policies AS tsp
                 LEFT JOIN tvs_customer_details AS tcd ON tcd.id = tsp.customer_id WHERE tsp.user_id = '$user_id' $where AND DATE(tsp.created_date) >(NOW() - INTERVAL 15 DAY) ";
                 
             // die($sql);
        $query = $this->db->query($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
//        $sql = "$sql WHERE  1 = 1";
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND engine_no LIKE '%" . $requestData['search']['value'] . "%' OR invoice_no LIKE '%" . $requestData['search']['value'] . "%' OR invoice_date LIKE '%" . $requestData['search']['value'] . "%' OR chassis_no LIKE '%" . $requestData['search']['value'] . "%' OR certificate_no LIKE '%" . $requestData['search']['value'] . "%' ";
        }
        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY tsp.id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        $data = array();
        $i = 1;
        foreach ($result as $main) {
            $created_date = $main->created_date;
            $created_date = explode(' ', $main->created_date);

            $date = strtotime($created_date[0]);
            $new_date = strtotime('+ 3 day', $date);
            $end_date = date('Y-m-d 23:59:59', $new_date);
            $current_date = date('Y-m-d');
            $link = '';
            if ($main->policy_status_id == 4) {
                $link = '<span class="btn btn-info">Requested For Cancellation</span><br/>';
            } else if ($main->policy_status_id == 3 || $main->policy_status_id == 6) {
                $link = '<span onclick=confirmRsaCancelation(' . $main->id . ') class="btn btn-info">Request To Cancel</span><br/>';
            } else if ($main->policy_status_id == 5) {
                $link = '<span class="btn btn-info">Cancelled Policy</span><br/>';
            }

            $row = array();
            $row[] = $i++;
            $row[] = $main->product_type_name;
            $row[] = $main->plan_name;
            $row[] = $main->sold_policy_no;
            $row[] = $main->fname;
            $row[] = $main->engine_no;
            $row[] = $main->chassis_no;
            $row[] = $main->created_date;
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

    function requestCancelPolicy() {

        $post_data = $this->input->post();
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
                 'cancel_file_name' => $get_uploaded_name
             );
            $status = $this->HomeMdl->updateTable('tvs_sold_policies', $data, $where);
        }
        if ($status) {
            $this->session->set_flashdata('success','Cancellation Request Is Done');
        }else{
            $this->session->set_flashdata('success','Something Went Wrong,Please Try Again.');
        }
    }else{
            $this->session->set_flashdata('success','Please Fill all the input fields.');
    }
    redirect('cancelPolicy');
    }

        function SoldPAPolicyAjax() {
        $requestData = $_REQUEST;
        $from_date = $requestData['from_date'];
        $to_date = $requestData['to_date'];
        $where1 = '';
        if($from_date !='' && $to_date !=''){
            $where1 = "AND DATE(tsp.created_date) BETWEEN '$from_date' AND '$to_date' ";
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
        $where = '';
        if(strlen($session_data['sap_ad_code']) > 5 ){
            $employee_code = $session_data['sap_ad_code'];
            $where = 'AND employee_code = '.$employee_code.'';
        }
        $user_id = $session_data['id'];
        $limit = ' LIMIT ' . $start . ', ' . $length . '';
        $sql = "SELECT tic.`name` AS ic_name,tsp.*,tsp.id as policy_id,tcd.*,tcd.id as customer_id FROM tvs_sold_policies AS tsp "
            . " INNER JOIN tvs_customer_details AS tcd ON tcd.id = tsp.customer_id "
            ."INNER JOIN tvs_insurance_companies tic ON tic.`id`=tsp.`ic_id`"
            . " WHERE tsp.user_id = $user_id "
            . " AND tsp.policy_status_id IN(3,4) $where $where1";
                 // die($sql);
        $query = $this->db->query($sql);
        $totalData = $query->num_rows();

        $totalFiltered = $totalData;
//        $sql = "$sql WHERE  1 = 1";


        if (!empty($requestData['search']['value'])) {
            $sql .= " AND engine_no LIKE '%" . $requestData['search']['value'] . "%' OR invoice_no LIKE '%" . $requestData['search']['value'] . "%' OR invoice_date LIKE '%" . $requestData['search']['value'] . "%' OR chassis_no LIKE '%" . $requestData['search']['value'] . "%' OR certificate_no LIKE '%" . $requestData['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY tsp.id DESC";
        
        $query = $this->db->query($sql);
        $result = $query->result();
        $data = array();
        $i = 1;
        foreach ($result as $main) {
            $created_date = $main->created_date;
            $created_date1 = $main->created_date;
            $created_date = explode(' ', $main->created_date);
// echo $created_date[0];die('abc');
            $now = time(); 
            $your_date = strtotime($created_date[0]);
            $datediff = $now - $your_date;

            $ddiff = round($datediff / (60 * 60 * 24));

            // echo"<pre>";print_r($ddiff);die('  ..diff');
            if ($ddiff > 7) {
                $endrose_button = '';
            } else {
                $endrose_button = '<a href="' . base_url() . 'generate_policy/' . $main->policy_id . '" class="btn btn-info">Endorsement</a><br/>';
            }


            $row = array();
            $row[] = $i++;
            $row[] = $main->product_type_name;
            $row[] = $main->ic_name;
            $row[] = $main->plan_name;
            $row[] = $main->sold_policy_no;
            $row[] = $main->fname;
            $row[] = $main->engine_no;
            $row[] = $main->chassis_no;
            $row[] = $main->created_date;

            //$where = array('pa_ic_id'==$main->ic_id);
            //$ic_master = $this->HomeMdl->getDataFromTable('dms_ic_and_pa_ic_mapping',$where);
            switch ($main->ic_id) {
                case 2:
                    $file_list = '<a href="' . base_url() . 'download_kotak_lite_pdf/' . $main->policy_id . '" class="btn btn-info" target="_blank">Lite Pdf</a><br/><a href="' . base_url() . 'download_kotak_full_policy/' . $main->policy_id . '" class="btn btn-info" target="_blank">Download Pdf</a><br/>';
                    break;
                case 5:
                    $file_list = '<a href="' . base_url() . 'download_il_lite_pdf/' . $main->policy_id . '" class="btn btn-info" target="_blank">Lite Pdf</a><br/><a href="' . base_url() . 'download_icici_full_policy/' . $main->policy_id . '" class="btn btn-info" target="_blank">Download Pdf</a><br/>';
                    break;
                case 9:
                    $file_list = '<a href="' . base_url() . 'download_tata_lite_pdf/' . $main->policy_id . '" class="btn btn-info" target="_blank">Lite Pdf</a><br/><a href="' . base_url() . 'download_tata_full_policy/' . $main->policy_id . '" class="btn btn-info" target="_blank">Download Pdf</a><br/>';
                    break;
                case 10:
                    $file_list = '<a href="' . base_url() . 'download_OICL_pdf/' . $main->policy_id . '" class="btn btn-info" target="_blank">Download Pdf</a>';
                    break;
                 case 12:
                    $file_list = '<a href="' . base_url() . 'download_bagi_lite_pdf/' . $main->policy_id . '" class="btn btn-info" target="_blank">Lite Pdf</a><br/><a href="' . base_url() . 'download_bhartiaxa_full_policy/' . $main->policy_id . '" class="btn btn-info" target="_blank">Download Pdf</a><br/>';
                    break;
                
                default:
                    $file_list = '<a href="' . base_url() . 'download_OICL_pdf/' . $main->policy_id . '" class="btn btn-info" target="_blank">Lite Pdf</a><br/><a href="' . base_url() . 'download_OICL_pdf/' . $main->policy_id . '" class="btn btn-info" target="_blank">Download Pdf</a><br/>';
                    break;
            }
            
            $row[] = $file_list.$endrose_button;

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

    public function checkIsPolicyExist() {
        $post_data = $this->input->post();
        $engine_no = $post_data['engine_no'];
        $chassis_no = $post_data['chessiss_no'];
        $is_exist   = $this->HomeMdl->checkIsPolicyExist($engine_no,$chassis_no);
        $return_data['status'] = ($is_exist) ? 'true' : 'false';
        echo json_encode($return_data);
    }

    public function PaClaim() {
        $this->load->dashboardTemplate('front/dashboard/pa_claim_policy');
    }

    public function SearchData() {
        $search_value = $this->input->post('search_value');
        $html = "";
        $base_url = base_url();
        if (!empty($search_value)) {
            $searched_data = $this->db->query("SELECT * FROM pa_policy WHERE engine_no ='$search_value' OR chassis_no = '$search_value' OR invoice_no = '$search_value' OR invoice_date = '$search_value' OR certificate_no = '$search_value'")->row_array();
            if (!empty($searched_data)) {
                $html = '
                        <td>' . $searched_data['id'] . '</td>
                        <td>' . $searched_data['invoice_no'] . '</td>
                        <td>' . $searched_data['invoice_date'] . '</td>
                        <td>' . $searched_data['engine_no'] . '</td>
                        <td>' . $searched_data['chassis_no'] . '</td>
                        <td>' . $searched_data['certificate_no'] . '</td>
                        <td align="right">
                        <a href = "' . $base_url . 'assets/images/GPA_claim_form.pdf" target="_blank" class="btn btn-info" >Claim-Pdf</a>&nbsp;<button onclick="claim_popup(' . $searched_data['id'] . ')" class="btn btn-info" type="button">EDIT</button>
                        </td>';
            }
        }

        echo $html;
    }

    public function UploadClaimData() {
        if (!empty($this->input->post())) {
            $pa_policy_id = $this->input->post('pa_policy_id');
            $courier_no = $this->input->post('courier_no');
            $claim_data = array();

            $count = count($_FILES['pa_cover_pdf']['size']);

            foreach ($_FILES as $key => $value) {
                for ($s = 0; $s <= $count - 1; $s++) {
                    $_FILES['pa_cover_pdf']['name'] = $value['name'][$s];
                    $_FILES['pa_cover_pdf']['type'] = $value['type'][$s];
                    $_FILES['pa_cover_pdf']['tmp_name'] = $value['tmp_name'][$s];
                    $_FILES['pa_cover_pdf']['error'] = $value['error'][$s];
                    $_FILES['pa_cover_pdf']['size'] = $value['size'][$s];
                    $config['upload_path'] = './uploads/claim_img/';
                    $config['allowed_types'] = 'pdf|jpg|png';

                    $this->load->library('upload', $config);
                    $this->upload->do_upload('pa_cover_pdf');
                    $data = $this->upload->data();
                    $name_array[] = $data['file_name'];
                }

                // $names= implode(',', $name_array);
                $json_pdf_data = json_encode($name_array);
            }

// echo "<pre>";  print_r($json_decode);die('name');
            $claim_data = array(
                'pdf_data' => $json_pdf_data,
                'courier_no' => $courier_no,
                'pa_policy_id' => $pa_policy_id
            );

            $sql = "SELECT count(id)as total FROM pa_claim WHERE pa_policy_id=" . $pa_policy_id . "";
            $query = $this->db->query($sql);
            $result = $query->row_array();
            /* check condition if data exist or not in pa_claim data */
            if ($result['total'] == 0) {
                $this->db->Insert('pa_claim', $claim_data);
                $message = "Saved Data successfully";
            } else {
                $claim_data['updated_at'] = date('y-m-d');
                $this->db->where("pa_policy_id", $pa_policy_id);
                $this->db->update("pa_claim", $claim_data);
                $message = "Updated Data successfully";
            }

            $this->session->set_flashdata('Message', $message);
            $this->session->keep_flashdata('Message', $message);

            redirect('pa_claim', 'refresh');
        }
    }

    function fn_do_upload_image($image_file_name) {
        $upload_PATH_NAME = './uploads/claim_img/';

        if ($_FILES[$image_file_name]['name'] != "") {
            $config = array();
            $config['upload_path'] = $upload_PATH_NAME;
            $config['allowed_types'] = 'gif|jpg|png|bmp|jpeg|pdf';
            $config['file_name'] = $_FILES[$image_file_name]['name'];
            $config['remove_spaces'] = TRUE;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            //$this->upload->initialize($config);
            $upload = $this->upload->do_upload($image_file_name);
            $get_uploaded_name = $this->upload->data('file_name');
            return $get_uploaded_name;
        }
    }

    function BizUsers() {
        $post_data = $this->input->post();
        $return_data = array();
        if (!empty($post_data)) {
            $biz_users_data = array(
                'f_name' => $post_data['f_name'],
                'l_name' => $post_data['l_name'],
                'dealer_code' => $post_data['dealer_code'],
                'employee_code' => $post_data['employee_code'],
                'email_id' => $post_data['email_id'],
                'password' => md5($post_data['employee_code']),
                'dob' => $post_data['dob'],
                'company_name' => $post_data['company_name'],
                'created_at' => date('Y-m-d H:i:s')
            );
            $insert_id = $this->HomeMdl->insertIntoTable('biz_users', $biz_users_data);
            if ($insert_id) {
                $return_data['inserted_id'] = $insert_id;
                $return_data['status'] = true;
                $return_data['error'] = false;
            } else {
                $return_data['inserted_id'] = '';
                $return_data['status'] = false;
                $return_data['error'] = true;
            }
        }
        echo json_encode($return_data);
    }

function FaqGenerateInvoice(){
    $this->load->dashboardTemplate('front/myaccount/view_faq');
}


}
