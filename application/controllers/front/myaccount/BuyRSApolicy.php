<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BuyRSApolicy extends CI_Controller {

    public function __construct() {
        Parent::__construct();
        $this->load->model('Rsa_Api_Model');
        $this->load->model('Home_Model');
        $this->load->helper('csv');
        $this->load->helper('common_helper');
        $this->load->helper('encdec_paytm');
        
    }


    function BuyRenewRSAPolicy($policy_id){
        if(!empty($policy_id)){
            $where = array('policy_id'=>$policy_id);
            $exist = $this->Home_Model->getRowDataFromTable('customer_activity_log',$where);
            $customer_activity = array(
                    'policy_id'=>$policy_id,
                    'page_name'=>'customer_buy_renew',
                    'created_at'=>date('Y-m-d H:i:s')
                );
                $inserted_id = $this->Home_Model->insertIntoTable('customer_activity_log',$customer_activity);  
            
            $data['policy_data'] = $this->Home_Model->getPolicyDetails($policy_id);
            $renewed_data = $this->Home_Model->getRowDataFromTable('tvs_policy_renewal_log',['prev_policy_id'=>$policy_id]);
            $dat['plan_details']=$plan_details = $this->getPlanDetails();
            // echo '<pre>';print_r($data);die('dadta');
            $data['is_renewed'] = $renewed_data['prev_policy_id'];
            $data['paic_readonly'] = '';
            $data['paic_disable']= '';
            $data['ic_id']= $data['policy_data']['ic_id'];
            $data['plan_name']= $plan_details['plan_name'];
            $data['km_covered']= $plan_details['km_covered'];
            $data['rsa_tenure']= $plan_details['rsa_tenure'];
            $data['pa_tenure']= $plan_details['pa_tenure'];
            $data['sum_insured']= $plan_details['sum_insured'];
            $data['plan_code']= $plan_details['plan_code'];
            $data['plan_amount']= $plan_details['plan_amount'];
            $data['id']= $plan_details['id'];
            $data['Current']= 'Current';
            $data['policy_price'] = '₹ ' .$plan_details['plan_code'];            
            $veh_reg_no = explode('-', $data['policy_data']['vehicle_registration_no']);
            $data['rto_name'] = $veh_reg_no[0];
            $data['rto_code1'] = $veh_reg_no[1];
            $data['rto_code2'] = $veh_reg_no[2];
            $data['reg_no'] = $veh_reg_no[3];
            $data['user_id'] = $data['policy_data']['user_id'];
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
            $data['nominee_age'] = ($data['policy_data']['nominee_age'] + 1);
            $data['appointee_full_name'] = $data['policy_data']['appointee_full_name'];
            $data['appointee_relation'] = $data['policy_data']['appointee_relation'];
            $data['appointee_age'] = $data['policy_data']['appointee_age'];
            $data['plan_type_id'] = $data['policy_data']['plan_type_id'];
            $data['plan_id'] = $data['policy_data']['plan_id'];
            $data['policy_id'] = $policy_id;
            $data['customer_id'] = $data['policy_data']['customer_id'];
            $data['readonly'] = 'readonly';
            $data['disabled'] = 'disabled';
            if(!empty($data['policy_data']['appointee_full_name']) && !empty($data['policy_data']['appointee_relation'])){
                $data['display'] = 'display: block' ;
            }else{
                $data['display'] = 'display: none' ;
            }

        }
        

        $this->load->dashboardTemplate('front/myaccount/api_rsa_cover',$data);
    }

function getPlanDetails(){
    $plan_type_id = 1;
    // echo '<pre>'; print_r($policy_data);die;
    $ic_id = 10;
    $where = array(
        'plan_type_id'=>$plan_type_id,
        'ic_id'=>$ic_id,
        'plan_name' => 'Platinum',
        'is_active'=>1
    );
    $plan_details = $this->Home_Model->getRowDataFromTable('tvs_plans',$where);
     // echo '<pre>';print_r($plan_details);die('hello');
    return $plan_details;
}

public function getModel() {
       // $model =  $this->input->post('model');
       // $model = isset($model)?$model:'';
       // echo '<pre>'; print_r($post_data);die('hello');
        $policyid= $this->input->post('hid_policy_id');
        $policy_data = $this->Home_Model->getPolicyDetails($policyid);
        // echo '<pre>';print_r($policy_data);die;
        $selected_model = $policy_data['model_name'];
        $models = $this->Home_Model->getDataFromTableWithOject('tvs_model_master');
        $data['html'] = "<option value='' >SELECT MODEL</option>";
        if (!empty($models)) {
            foreach ($models as $row) {
                $select = '';
                if ((strtolower(trim($selected_model)) == strtolower($row->model_name)) )
                 {
                    $select = 'selected';
                    $data['model_name'] = $row->model_name;
                }
                $data['html'] .= "<option class'model_list' value='" . $row->model_id . "' " . $select . " >" . $row->model_name . "</option>";
            }
        }

        echo json_encode($data);
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

function getRenewedPolicyExist(){
    // echo "<pre>"; print_r($_POST); echo "</pre>"; die('end of line yoyo');
    $renew_policyid = $this->input->post('renew_policyid');
    $exist_renew_data = $this->Home_Model->getRowDataFromTable('tvs_policy_renewal_log',['prev_policy_id'=>$renew_policyid]);
    // echo "<pre>"; print_r($exist_renew_data); echo "</pre>"; die('end of line yoyo');
    if(!empty($exist_renew_data)){
        $status = "exist";
    }else{
        $status = "nonexist";
    }
    echo json_encode($status);
}

function submit_renewal_policy_data(){
    $post_data = $this->input->post();
    // echo "<pre>"; print_r($post_data); echo "</pre>"; die('end of line yoyo');
    $prev_policyid = $post_data['hid_policy_id'];
    $dob = date('Y-m-d',strtotime($post_data['dob']));
    $age =  date_diff(date_create($dob), date_create('today'))->y;
    // echo 'BuynowRSApolicy/'.$pendin_policyid;die('as');
    $where = array('id'=>$post_data['plan']);
    $plan_details  = $this->Home_Model->getRowDataFromTable('tvs_plans',$where);
    $where = array('sap_ad_code'=>'98765');
    $user_data = $this->Home_Model->getRowDataFromTable('tvs_dealers',$where);
    $where =array('dealer_id'=>$user_data['id']);
    $dealer_wallet_details  = $this->Home_Model->getRowDataFromTable('dealer_wallet',$where);
    // echo $wallet_balance;die;
    $where = array('id'=>$prev_policyid);
    $perv_policy_data = $this->Home_Model->getRowDataFromTable('tvs_sold_policies',$where);
    // echo "<pre>"; print_r($perv_policy_data); echo "</pre>"; die('end of line yoyo');
    if($age > 74 || $age < 18){
        $this->session->set_flashdata('message', 'Customer Age Should Not More Than 65 Or Less Than 16.');
        redirect('BuyRenewRSAPolicy/'.$prev_policyid);
    }
    $is_exist = $this->Home_Model->checkIsPolicyExist($post_data['engine_no'],$post_data['chassis_no']);
    if($is_exist){
        $this->session->set_flashdata('message', 'Duplicate Policy To Check Visit On Certificate Section.');
        redirect('BuyRenewRSAPolicy/'.$prev_policyid);
    }else{  
            
           $state_id = $post_data['state_id'];
           $city_id = $post_data['city_id'];
           $dealer_code = $user_data['sap_ad_code'];
            $rto_name = $this->input->post('rto_name');
            $rto_code1 = $this->input->post('rto_code1');
            $rto_code2 = $this->input->post('rto_code2');
            $reg_no = $this->input->post('reg_no');
           $final_reg_no = $rto_name . '-' . $rto_code1 . '-' . $rto_code2 . '-' . $reg_no;
           $model_id = $this->input->post('pending_model_id');
           $dob = date("Y-m-d", strtotime($this->input->post('dob')));
           $insert_customer_detail = array(
                'fname' => $post_data['first_name'],
                'lname' => $post_data['last_name'],
                'email' => $post_data['email'],
                'mobile_no' => $post_data['mobile_no'],
                'gender' => $post_data['mobile_no'],
                'dob' => $dob,
                'addr1' => $post_data['cust_addr1'],
                'addr2' => $post_data['cust_addr2'],
                'state' => $post_data['state_id'],
                'city' => $post_data['city_id'],
                'state_name' => $post_data['state'],
                'city_name' => $post_data['city'],
                'pincode' => $post_data['pin'],
                'nominee_full_name' => $post_data['nominee_full_name'],
                'nominee_relation' => $post_data['nominee_relation'],
                'nominee_age' => $post_data['nominee_age'],
                'appointee_full_name' => $post_data['appointee_full_name'],
                'appointee_relation' => $post_data['appointee_relation'],
                'appointee_age' => $post_data['appointee_age'],
                'created_date' => date('Y-m-d H:i:s')
            );
            $inserted_customer_detail = $this->db->insert('tvs_customer_details', $insert_customer_detail);
            $customer_detail_last_id = $this->db->insert_id();
            
            if($customer_detail_last_id){
                    $result_sold = $this->Rsa_Api_Model->GenerateRsaPolicyNo($perv_policy_data['rsa_ic_id']);
                    $date_result = $this->Rsa_Api_Model->StartEndDate($plan_details['plan_type_id'],$plan_details['rsa_tenure']);
                    $effective_date = $date_result['effective_date'];
                    $end_date = $date_result['end_date'];
                    $selected_date = (!empty($post_data['start_date']) && isset($post_data['start_date']) ) ? $post_data['start_date'] : date('Y-m-d');
                    $pa_effective_date = date('Y-m-d', strtotime($selected_date . "0 day")). ' '.date('H:i:s');
                    $pa_end_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime($selected_date . "-1 day")) . "1 year")). ' 23:59:59';
                    $model_name = $this->Rsa_Api_Model->getMakeModelName('model', $model_id);
                    $transection_no = $this->Rsa_Api_Model->getRandomNumber('16');
                    if($age > 64){
                        $where = array('ic_id'=>13);
                        $master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
                       
                        if(empty($master_policy_details)){
                           $this->session->set_flashdata('message', 'Master Policy Not Found Please Contact To Tech Team.');
                            redirect('BuyRenewRSAPolicy/'.$prev_policyid);
                        }
                        
                    }else{
                        $where = array('start_date'=>date('Y-m-d'));
                        $master_policy_details = $this->Home_Model->getRowDataFromTable('tvs_oriental_master_policy',$where);
                       
                        if(empty($master_policy_details)){
                           $this->session->set_flashdata('message', 'Master Policy Not Found Please Contact To Tech Team.');
                            redirect('BuyRenewRSAPolicy/'.$prev_policyid);
                        }
                        $master_policy_details['mp_start_date'] = $master_policy_details['start_date'];
                        $master_policy_details['mp_end_date'] = $master_policy_details['end_date'];
                        $master_policy_details['ic_id'] = 10;
                    }                             
                       
                        // echo '<pre>'; 
                             // print_r($post_data);
                             // print_r($master_policy_details);
                             // die('here');
                            
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
                                'policy_status_id' => 1,
                                'status' => 1,
                                'transection_no' => $transection_no,
                                'dms_response'=>'',
                                'rsa_ic_id' => $perv_policy_data['rsa_ic_id'],
                                'vehicle_type' => $post_data['vehicle_type'],
                                'sold_policy_date' => date('Y-m-d H:i:s'),
                                'created_date' => date('Y-m-d H:i:s')

                            );
                            // echo '<pre>'; print_r($insert_data_sold);die('here');
                            $inserted_id = $this->Home_Model->insertIntoTable('tvs_sold_policies',$insert_data_sold);
                            if (!empty($inserted_id)) {
                                    $insert_customer_detail['policy_id'] = $inserted_id;
                                $inserted_endors_customer_id = $this->Home_Model->insertIntoTable('tvs_endorse_customer_details',$insert_customer_detail);
                                    $insert_array = array(
                                        'prev_policy_id'=>$prev_policyid,
                                        'new_policy_id'=>$inserted_id,
                                        'create_date'=>date('Y-m-d H:i:s')
                                    );
                                    $this->db->insert('tvs_policy_renewal_log',$insert_array);
                                
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
                                $status = $this->Home_Model->insertIntoTable('dealer_transections',$dealer_transection_data);
                                            
                                            
                                    $paytm_response = $this->checkPayment($transection_no,$inserted_id,$post_data,$user_data);
                                    // echo $paytm_response;die(' paytm_response');
                            }
                        // if closin $inserted_id
            }else{

            }
        // else after update

    }
    //after is_exist main else
    // echo "<pre>"; echo $inserted_id; echo "</pre>"; die('annu');
}


    function checkPayment($transection_no,$inserted_id,$post_data,$user_data){
            $checkSum = "";
            $paramList = array();
            $ORDER_ID = $transection_no;
            $CUST_ID = $user_data['sap_ad_code'];
            $paramList = array(
                "MID" => MID,
                "WEBSITE" => WEBSITE,
                "INDUSTRY_TYPE_ID" => INDUSTRY_TYPE_ID,
                "CHANNEL_ID" => CHANNEL_ID,
                "ORDER_ID" => $transection_no,
                "CUST_ID" => $CUST_ID,
                "MOBILE_NO" => !empty($post_data['mobile_no'])?$post_data['mobile_no']:'',
                "EMAIL" => !empty($post_data['email'])?$post_data['email']:'',
                "TXN_AMOUNT" => 1,
                "CALLBACK_URL" => base_url(RSA_API_CALLBACK_URL).'/'.$transection_no.'/'.$inserted_id.'/'.$post_data['hid_policy_id']
            );
            // echo '<pre>'; print_r($paramList);die('hello');
            $checkSum = getChecksumFromArray($paramList,'7ngTah&GtTo87pCT');
            $data['checkSum'] = $checkSum;
            $data['paramList'] = $paramList;
            $this->load->view('welcome_message', $data);

    }

    function paymentResponse($transection_no,$inserted_id,$pendin_policyid){
        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";
        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; 
        $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum);     
        if($isValidChecksum){
            
            $insert_payment_response = array(
                                'sold_policy_id' => $inserted_id,
                                'customer_id' => $inserted_id,
                                'order_id' => $_POST["ORDERID"],
                                'merchant_id' => $_POST["MID"],
                                'transaction_id' => $_POST["TXNID"],
                                'transaction_amount' => $_POST["TXNAMOUNT"],
                                'payment_mode' => $_POST["PAYMENTMODE"],
                                'currency'=> $_POST["CURRENCY"],
                                'transaction_date'=> $_POST["TXNDATE"],
                                'status'=> $_POST["STATUS"],
                                'respcode'=> $_POST["RESPCODE"],
                                'respmsg'=> $_POST["RESPMSG"],
                                'gatewayname'=> $_POST["GATEWAYNAME"],
                                'banktransactionid'=> $_POST["BANKTXNID"],
                                'bankname'=> $_POST["BANKNAME"],
                                'checksumhash'=> $_POST["CHECKSUMHASH"]);
            $res_payment_response=$this->Home_Model->insertIntoTable('tvs_payment_response',$insert_payment_response);
            if ($_POST["STATUS"] == "TXN_SUCCESS") {
                            $where = array(
                                'id' => $inserted_id
                            );
                            $input_data = array( 
                                'policy_status_id' => 3,
                                'payment_detail_id' => $res_payment_response
                            );
                $res_upd_sold_policies=$this->Home_Model->updateTable('tvs_sold_policies',$input_data,$where);
                
                if($res_upd_sold_policies){
                   
                    $paymentStatus = $this->paymentStatus();
                    if($paymentStatus['STATUS'] == 'TXN_SUCCESS'){
                          
                             $where = array(
                                'order_id' => $_POST["ORDERID"]
                            );
                            $input_data = array(
                                 'trns_approval_status' => 1,
                            );
                            $this->Home_Model->updateTable('tvs_payment_response',$input_data,$where);
                            $this->session->set_userdata('policy_id',$inserted_id);
                        $sql = "SELECT tcs.`mobile_no` ,tcs.`email`,tcs.`fname` AS name,tsp.`sold_policy_no` as policy_no,tsp.`ic_id` FROM tvs_sold_policies tsp JOIN tvs_customer_details tcs ON tsp.`customer_id`= tcs.`id` WHERE tsp.`id`='$inserted_id'";
                        $policy_data = $this->db->query($sql)->row_array();
                        
                        $Mobilenumber = $policy_data['mobile_no'];
                        
                        $email = $policy_data['email'];
                        $name = ucfirst($policy_data['name']);
                        $policy_no = $policy_data['policy_no'];
                        $policy_id = $inserted_id;
                        $ic_id = $policy_data['ic_id'];

                        $hostName = $_SERVER['HTTP_HOST'];
                        
                        if ($hostName != 'localhost' && !empty($email)) {
                            $this->MailSoldPolicyPdf($inserted_id,$ic_id);
                        }
                        $is_sent = $this->RSA_paymentsuccess_sms($name,$policy_no,$Mobilenumber,$inserted_id,$ic_id);
                        //$is_sent=1;
                        switch ($ic_id) {
                            case 2:
                            $this->data['ic_pdf'] = 'download_kotak_lite_pdf';
                            $this->data['inserted_id'] = $inserted_id;
                            $this->load->dashboardTemplate('front/myaccount/pytm_success', $this->data);
                                break;
                            case 5:
                            $this->data['ic_pdf'] = 'download_il_lite_pdf';
                            $this->data['inserted_id'] = $inserted_id;
                            $this->load->dashboardTemplate('front/myaccount/pytm_success', $this->data);
                                break;
                            case 8:
                            $this->data['ic_pdf'] = 'download_reliance_policy';
                            $this->data['inserted_id'] = $inserted_id;
                            $this->load->dashboardTemplate('front/myaccount/pytm_success', $this->data);
                                break;
                            case 9:
                            $this->data['ic_pdf'] = 'download_tata_lite_pdf';
                            $this->data['inserted_id'] = $inserted_id;
                            $this->load->dashboardTemplate('front/myaccount/pytm_success', $this->data);
                                break;
                            case 10:
                            $this->data['ic_pdf'] = 'download_OICL_pdf';
                            $this->data['inserted_id'] = $inserted_id;
                            $this->load->dashboardTemplate('front/myaccount/pytm_success', $this->data);
                                break;
                            case 12:
                            $this->data['ic_pdf'] = 'download_bagi_lite_pdf';
                            $this->data['inserted_id'] = $inserted_id;
                            $this->load->dashboardTemplate('front/myaccount/pytm_success', $this->data);
                                break;
                            case 13:
                            $this->data['ic_pdf'] = 'download_liberty_policy';
                            $this->data['inserted_id'] = $inserted_id;
                            $this->load->dashboardTemplate('front/myaccount/pytm_success', $this->data);
                                break;
                            
                            
                        }
                       
                       
                        }else{
                            $this->session->set_flashdata('message', $_POST["RESPMSG"]);
                            redirect('BuyRenewRSAPolicy/'.$pendin_policyid);
                        }
                }else{
                    echo 'something went wrong kindly contact to tech team.';
                }        
            }else {
                $this->session->set_flashdata('message', $_POST["RESPMSG"]);
                redirect('BuyRenewRSAPolicy/'.$pendin_policyid);
            } 
           
        }else{
            $this->session->set_flashdata('message', $_POST["RESPMSG"]);
            redirect('BuyRenewRSAPolicy/'.$pendin_policyid);
        }
    }

    // function paymentSuccess(){

    //     $policy_id = !empty(($this->session->userdata('policy_id')))?$this->session->userdata('policy_id'):$this->input->get('id');

    //     $this->data['ic_pdf'] = 'download_rr_310_pdf';
    //     $this->data['inserted_id'] = $policy_id;
    //     $this->load->dashboardTemplate('front/myaccount/rr_310_success', $this->data);
    // }                        
    function paymentStatus(){
        // echo '<pre>'; print_r($_POST);//die('hello');
        $ORDER_ID = "";
        $requestParamList = array();
        $responseParamList = array();

        if (isset($_POST["ORDERID"]) && $_POST["ORDERID"] != "") {
            $ORDER_ID = $_POST["ORDERID"];
            $MID = $_POST["MID"];
            $requestParamList = array("MID" => $MID , "ORDERID" => $ORDER_ID);  
            // echo '<pre>'; print_r($requestParamList);
            $StatusCheckSum = getChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY);
            $requestParamList['CHECKSUMHASH'] = $StatusCheckSum;
            $responseParamList = getTxnStatusNew($requestParamList);
                return $responseParamList;
        }else{
                return FALSE;
        }
    }


    function paymentFail(){
           
       // echo "<pre>"; print_r($_SESSION); exit;
       //  echo "<b>Transaction status is failure</b>" . "<br/>";
    $this->load->dashboardTemplate('front/myaccount/rsa_rr310_policy');

    }



function make_bitly_url($url,$format = 'xml',$version = '2.0.1'){
    $login = 'indicosmic';
    $appkey = 'R_379aaef81a104a3fb6991acaf8ecaf86';
    $bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;
     // echo $bitly.'  bitly'.'<br>';
    $response = file_get_contents($bitly);
    if(strtolower($format) == 'json')
    {
        $json = @json_decode($response,true);
        // echo '<pre>';print_r($json);die;
        return $json['results'][$url]['shortUrl'];
    }
    else
    {
        $xml = simplexml_load_string($response);
        return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
    }
}

    function RSA_paymentsuccess_sms($name,$policy_no,$Mobilenumber,$sold_policy_id,$ic_id) {
        // $policydata = $this->Home_Model->getRowDataFromTable('tvs_sold_policies',['id'=>$sold_policy_id]);
        switch ($ic_id) {
            case 2:
                // Kotak...
            $pdf_url = base_url().'download_kotak_full_policy/'.$sold_policy_id;
                break;
            case 5:
                // ICICI...
            $pdf_url = base_url().'download_icici_full_policy/'.$sold_policy_id;
                break;
            case 8:
                // RELIANCE...
            $pdf_url = base_url().'download_reliance_policy/'.$sold_policy_id;
                break;
            case 9:
                // TATA...
            $pdf_url = base_url().'download_tata_full_policy/'.$sold_policy_id;
                break;
            case 10:
                // ORIENTAL...
            $pdf_url = base_url().'download_OICL_pdf/'.$sold_policy_id;
                break;
            case 12:
                // BHARI AXA...
            $pdf_url = base_url().'download_bhartiaxa_full_policy/'.$sold_policy_id;
                break;
            case 13:
                // LIBERTY...
            $pdf_url = base_url().'download_liberty_policy/'.$sold_policy_id;
                break; 
         
        }
        
        
        // $short_policy_url = $this->make_bitly_url($pdf_url,'json');
        //$Message = "Welcome to my TVS RSA Limitless Assist to find your policy please click on given link $short_policy_url. and your video link is $short_video_url";
        // die($Message);

        $Message = "TVS RSA: Hi $name,
                    Thank you for purchasing RSA policy. Your RSA policy number $policy_no is successfully generated. To download your policy, see $pdf_url
                    Emergency helpline contact number 1800 258 7111";

        $Mobilenumber='8898188910';
        $output = sendTVSRSAsms($Mobilenumber,$Message);

        return $output;
    }

function MailSoldPolicyPdf($inserted_id,$pa_ic_id) {
    switch ($pa_ic_id) {
                case 2:
                    $policypdf_obj = $this->Rsa_Api_Model->DownloadKotakFullPolicy($inserted_id);
                    break;
                case 5:
                    $policypdf_obj = $this->Rsa_Api_Model->ICICI_full_Pdf($inserted_id);
                    break;
                case 7:
                    $policypdf_obj = $this->Rsa_Api_Model->HDFCPDF($inserted_id);
                    break;
                case 8:
                    $policypdf_obj = $this->Rsa_Api_Model->ReliancePDF($inserted_id);
                    break;
                case 9:
                $policypdf_obj = $this->Rsa_Api_Model->DownloadTataFullPolicy($inserted_id);
                    break;
                case 10:
                $policypdf_obj = $this->Rsa_Api_Model->DownloadOrientalPdf($inserted_id);
                    break;
                case 12:
                    $policypdf_obj = $this->Rsa_Api_Model->DownloadBhartiFullPolicy($inserted_id);
                    break;
                case 13:
                    $policypdf_obj = $this->Rsa_Api_Model->LibertyGeneral($inserted_id);
                    break;
                
            }
    $status = $this->MailAttachments($policypdf_obj, $inserted_id);

}

 function MailAttachments($policypdf_obj, $sold_policy_detail_last_id) {
        $rsa_policy_data = $this->Home_Model->getPolicyById($sold_policy_detail_last_id);
        $rsa_ic_master_id = $rsa_policy_data['rsa_ic_id'];
        $where = array(
            'id' => $rsa_ic_master_id
        );
        $insurance_companies = $this->Home_Model->getRowDataFromTableWithOject('rsa_ic_master', $where);

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