<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

Class Tvsrsa_Api extends REST_Controller {
    public function __construct() {
        parent::__construct();
       // $this->load->model('Admin_Model');
        $this->load->model('Rsa_Api_Model');
        $this->load->model('Home_Model');
         $this->load->helper("common_helper");
    }

    function api_response($data, $http_status = REST_Controller::HTTP_OK){
        //header('Access-Control-Allow-Origin: *');
        $this->response($data, $http_status);
    }

    
function GenerateRSAPolicy_post(){
        $post = $this->post();
        // print_r($post['post']); exit;
        $post_data = json_decode($post['post'],true);
        // echo "<pre>"; print_r($post_data); echo "</pre>"; die('end of line yoyo');
        $dealer_code = $post_data['dealer_code']['dealer_code'];
        $where = array('sap_ad_code'=>$dealer_code);
        $dealer_data = $this->Home_Model->getRowDataFromTable('tvs_dealers',$where);
        $where = array('id'=>$post_data['plan']['plan_id']);
        $plan_details  = $this->Home_Model->getRowDataFromTable('tvs_plans',$where);
        $where =array('dealer_id'=>$dealer_data['id']);
        $dealer_wallet_details  = $this->Home_Model->getRowDataFromTable('dealer_wallet',$where);
        $wallet_balance = ($dealer_wallet_details['security_amount'] - $dealer_wallet_details['credit_amount']);
        // echo $wallet_balance.'<br>';die;
        $statecity = $this->Rsa_Api_Model->fetchStateCity($post_data['customer']['insured_pincode']);
        $city_id = $statecity['city_id'];
        $state_id = $statecity['state_id'];
        $state = $statecity['state'];
        $city = $statecity['city'];
        $pa_ic_id = $post_data['plan']['insurance_company_id'];
         // echo "<pre>"; print_r($dealer_wallet_details); echo "</pre>"; die('end of line yoyo');
        $birthdate = new DateTime($post_data['customer']['insured_dob']);
        $today   = new DateTime('today');
        $age = $birthdate->diff($today)->y;
        $master_policy_details = $this->getMasterpolicyNo($post_data,$state_id,$city_id);
        // echo "<pre>"; print_r($master_policy_details); echo "</pre>"; die('end of line yoyo');
        $is_exist = $this->Home_Model->checkIsPolicyExist($post_data['vehicle']['engine_no'],$post_data['vehicle']['chassis_no']);
        $is_exist_pendinpolicy = $this->Home_Model->checkIsPendingPolicyExist($post_data['vehicle']['engine_no'],$post_data['vehicle']['chassis_no']);
        // echo "<pre>"; print_r($is_exist); echo "</pre>"; die('end of line yoyo');
        if($is_exist){
            $response['status'] = false;
            $response['message'] = 'Duplicate Policy To Check Visit On Certificate Section.';
        }
        elseif($is_exist_pendinpolicy){
            $response['status'] = false;
            $response['message'] = 'Duplicate Policy To Check Visit On Pending Section.';
        }
        elseif($age > 64 || $age < 18){
            $response['status'] = false ;
            $response['message'] = "Customer Age Should Not More Than 65 Or Less Than 16.";
        }
        elseif(empty($dealer_data) || $dealer_data==""){
            $response['status'] = false ;
            $response['message'] = "Dealer is Not Exist";
        }elseif($master_policy_details['status']=='false'){
            $response['message'] = $master_policy_details['message'];
        }elseif($wallet_balance < ($plan_details['plan_amount'] + $plan_details['gst_amount'])){
            $inserted_id = $this->TvsPolicyPendingData($post_data,$post[0],$dealer_data,$master_policy_details);
            $response['status'] = true ;
            $response['message'] = "Wallet Amount Is Less Than Policy Amount.";
        }
        
        else{
                // $rto_name = $post_data['vehicle']['rto_name'];
                // $rto_code1 = $post_data['vehicle']['rto_code1'];
                // $rto_code2 = $post_data['vehicle']['rto_code2'];
                // $reg_no = $post_data['vehicle']['reg_no'];
                $vehicle_registration_no = $post_data['vehicle']['vehicle_registration_no'];
                $dob = date("Y-m-d", strtotime($post_data['customer']['insured_dob']));
                $insert_customer_detail = array(
                'fname' => $post_data['customer']['insured_fname'],
                'lname' => $post_data['customer']['insured_lname'],
                'email' => $post_data['customer']['insured_email_id'],
                'mobile_no' => $post_data['customer']['insured_mobile_no'],
                'gender' => $post_data['customer']['insured_gender'],
                'dob' => $dob,
                'addr1' => $post_data['customer']['insured_address1'],
                'addr2' => $post_data['customer']['insured_address2'],
                'state' => $state_id,
                'city' => $city_id,
                'state_name' => $state,
                'city_name' => $city,
                'pincode' => $post_data['customer']['insured_pincode'],
                'nominee_full_name' => $post_data['nominee']['nominee_name'],
                'nominee_relation' => $post_data['nominee']['nominee_relation'],
                'nominee_age' => $post_data['nominee']['nominee_age'],
                'appointee_full_name' => $post_data['nominee']['appointee_name'],
                'appointee_relation' => $post_data['nominee']['appointee_relation'],
                'appointee_age' => $post_data['nominee']['appointee_age'],
                'created_date' => date('Y-m-d H:i:s')
                );

                $inserted_customer_detail = $this->db->insert('tvs_customer_details', $insert_customer_detail);
                $customer_detail_last_id = $this->db->insert_id();
                if($customer_detail_last_id) {
                    $result_sold = $this->Rsa_Api_Model->GenerateRsaPolicyNo($dealer_data['rsa_ic_master_id']);
                    $date_result = $this->Rsa_Api_Model->StartEndDate($post_data['plan']['plan_type'],$plan_details['rsa_tenure']);
                    $effective_date = $date_result['effective_date'];
                    $end_date = $date_result['end_date'];
                    $selected_date = date('Y-m-d');
                    $pa_effective_date = date('Y-m-d', strtotime($selected_date . "0 day")).' '.date('H:i:s');
                    $pa_end_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime($selected_date . "-1 day")) . "1 year")). ' 23:59:59';
                    $model_id = $this->Rsa_Api_Model->getMakeModelNameByName('model', strtolower(trim($post_data['vehicle']['model_name'])));
                    
                    $transection_no = $this->Rsa_Api_Model->getRandomNumber('16');
                    
                        $dms_response = $post[0];
                        $insert_data_sold = array(
                        'user_id' => $dealer_data['id'],
                        'employee_code' => $dealer_code,
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
                        'engine_no' => $post_data['vehicle']['engine_no'],
                        'chassis_no' => $post_data['vehicle']['chassis_no'],
                        'make_id' => '11',
                        'model_id' => $model_id,
                        'make_name' => 'TVS',
                        'model_name' => $post_data['vehicle']['model_name'],
                        'vehicle_registration_no' => strtoupper($post_data['vehicle']['vehicle_registration_no']),
                        'registration_date'=>$post_data['vehicle']['registration_date'],
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
                        'rsa_ic_id' => $dealer_data['rsa_ic_master_id'],
                        'vehicle_type' => $post_data['vehicle']['vehicle_type'],
                        'sold_policy_date' => date('Y-m-d H:i:s'),
                        'created_date' => date('Y-m-d H:i:s')

                    );
                    // echo '<pre>'; print_r($insert_data_sold);die('here');
                    $inserted_id = $this->Home_Model->insertIntoTable('tvs_sold_policies',$insert_data_sold);
                    // echo $this->db->last_query();die;
                    if(!empty($inserted_id) ){
                            $insert_customer_detail['policy_id'] = $inserted_id;
                            $inserted_endors_customer_id = $this->Home_Model->insertIntoTable('tvs_endorse_customer_details',$insert_customer_detail);
                            $dealer_transection_data = array(
                                'dealer_id'=> $dealer_data['id'],
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
                            if($status){
                                        $policy_amount = ($plan_details['plan_amount']+$plan_details['gst_amount']);
                                        $where = array('dealer_id'=>$dealer_data['id']);
                                        $wallet_details = $this->Home_Model->getDataFromTable('dealer_wallet',$where);
                                        $wallet_details = $wallet_details[0];

                                        $wallet_amount = (($wallet_details['credit_amount'] + $policy_amount) - $plan_details['dealer_commission']);
                                        $data = array(
                                            'credit_amount'=> $wallet_amount
                                        );
                                        $where = array('dealer_id'=>$dealer_data['id']);
                                        $status = $this->Home_Model->updateTable('dealer_wallet',$data,$where);
                                    if($status){
                                        $data = array(
                                            'policy_no'=>$result_sold['sold_policy_no'],
                                            'dealer_id'=> $dealer_data['id'],
                                            'transection_no' =>$transection_no,
                                            'transection_type'=> 'dr',
                                            'transection_amount'=>$policy_amount,
                                            'transection_purpose'=>'Policy Created'
                                        );
                                        // echo '<pre>'; print_r($data);//die('hello');
                                        $this->Home_Model->insertIntoTable('dealer_transection_statement',$data);

                                        $data = array(
                                            'policy_no'=> $result_sold['sold_policy_no'],
                                            'dealer_id'=> $dealer_data['id'],
                                            'transection_no' => $transection_no,
                                            'transection_type'=>'cr',
                                            'transection_amount'=>$plan_details['dealer_commission'],
                                            'transection_purpose'=>'Commission'
                                        );
                                        $this->Home_Model->insertIntoTable('dealer_transection_statement',$data);
                                    }
                        }
                            $this->RSAsendSms($post_data['customer']['insured_mobile_no'], $inserted_id);
                            $domainName = $_SERVER['HTTP_HOST'];
                            if ($domainName != 'localhost' && !empty($post_data['customer']['insured_email_id'] )) {
                                        $this->MailSoldPolicyPdf($inserted_id,$getic_map['pa_ic_id']);
                                    }
                            $response['status'] = true ;
                            $response['message'] = "Policy Created successfully";
                    }
                    // if closing of inserted_id of tvs_sold_policies
                    else{
                            $response['status'] = false ;
                            $response['message'] = "Something went wrong in sold policies";
                    }
                    
            }
            // END if(customer_detail_last_id)
            else{
                    // IF not insert customer_data
                $response['status'] = false ;
                $response['message'] = "Something went wrong";
            }
            

            
        }
        // End MAIN ELSE

        echo json_encode($response);

    }

function getMasterpolicyNo($post_data,$state_id,$city_id){
    // echo "<pre>"; print_r($post_data); echo "</pre>"; die('end of line yoyo');
    $pa_ic_id = $post_data['plan']['insurance_company_id'];
    $dealer_code = $post_data['dealer_code']['dealer_code'];
    if(in_array($post_data['plan']['plan_id'], array(51,52)) ){
    $state_id = ($city_id == 470)?$city_id:$state_id;
        
        $master_policy_details = $this->Home_Model->getMasterPolicyDetailByState($state_id);
    }else{
                $where = array('dealer_code'=>$dealer_code,'dms_ic_id' => $post_data['plan']['insurance_company_id']);
                $getic_map = $this->Home_Model->getRowDataFromTable('dms_ic_and_pa_ic_mapping',$where);
             
            $today_date = date('Y-m-d');
              if(!empty($getic_map['pa_ic_id'])){
                $pa_ic_id = ($getic_map['pa_ic_id'] == 2)?10:$getic_map['pa_ic_id'];
                        if($pa_ic_id == 10){
                            
                            $where = array('start_date'=>date('Y-m-d'));
                            $master_policy_details = $this->Home_Model->getRowDataFromTable('tvs_oriental_master_policy',$where);

                            if(empty($master_policy_details)){
                                $master_policy_details['status'] = 'false' ;
                                $master_policy_details['message'] = "Master Policy Not Found Please Contact To Tech Team.";
                            }
                            $master_policy_details['mp_start_date'] = $master_policy_details['start_date'];
                            $master_policy_details['mp_end_date'] = $master_policy_details['end_date'];
                            $master_policy_details['ic_id'] = $pa_ic_id;
                        }else{   
                            $where = array('ic_id' => $pa_ic_id);
                            $master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
                        }
                 }else{
                    $where = array(
                        'dealer_code'=>$dealer_code
                    );
                    $dealer_ic_details = $this->Home_Model->getRowDataFromTable('tvs_dealers_dmsic_logic',$where);
                    switch ($dealer_ic_details['pa_ic_mapping']) {
                        case 'OICL':
                        case 'Universal Logic':
                        $ic_id = 10;
                            break;
                        case 'TAGIC':
                        $ic_id = 9;
                            break;
                        case 'BAGI':
                        $ic_id = 12;
                            break;
                        case 'ICICIL':
                        $ic_id = 5;
                            break;
                        case 'LGI':
                        $ic_id = 13;
                            break;
                        case 'RGL':
                        $ic_id = 8;
                        case 'HDFC':
                        $ic_id = 7;
                            break;
                        default:
                        $ic_id = 10;
                            break;
                        }

                        if($ic_id == 10){
                            $where = array('start_date'=>date('Y-m-d'));
                            $master_policy_details = $this->Home_Model->getRowDataFromTable('tvs_oriental_master_policy',$where);
                            if(empty($master_policy_details)){                                               
                                $master_policy_details['status'] = 'false' ;
                                $master_policy_details['message'] = "Master Policy Not Found Please Contact To Tech Team.";
                            }
                            $master_policy_details['mp_start_date'] = $master_policy_details['start_date'];
                            $master_policy_details['mp_end_date'] = $master_policy_details['end_date'];
                            $master_policy_details['ic_id']=$ic_id;
                        }else{
                             $where = array('ic_id'=>$ic_id);
                             $master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
                        }
                 }
    }
    // Else closing After $master_policy_details
    return $master_policy_details;
}

function TvsPolicyPendingData($post_data,$post,$dealer_data,$master_policy_details){
    $vehicle_registration_no = $post_data['vehicle']['vehicle_registration_no'];
    $dealer_code = $post_data['dealer_code']['dealer_code'];
    $dob = date("Y-m-d", strtotime($post_data['customer']['insured_dob']));
    $statecity = $this->Rsa_Api_Model->fetchStateCity($post_data['customer']['insured_pincode']);
    $city_id = $statecity['city_id'];
    $state_id = $statecity['state_id'];
    $state = $statecity['state'];
    $city = $statecity['city'];
    $where = array('id'=>$post_data['plan']['plan_id']);
    $plan_details  = $this->Home_Model->getRowDataFromTable('tvs_plans',$where);
    $insert_customer_detail = array(
    'fname' => $post_data['customer']['insured_fname'],
    'lname' => $post_data['customer']['insured_lname'],
    'email' => $post_data['customer']['insured_email_id'],
    'mobile_no' => $post_data['customer']['insured_mobile_no'],
    'gender' => $post_data['customer']['insured_gender'],
    'dob' => $dob,
    'addr1' => $post_data['customer']['insured_address1'],
    'addr2' => $post_data['customer']['insured_address2'],
    'state' => $state_id,
    'city' => $city_id,
    'state_name' => $state,
    'city_name' => $city,
    'pincode' => $post_data['customer']['insured_pincode'],
    'nominee_full_name' => $post_data['nominee']['nominee_name'],
    'nominee_relation' => $post_data['nominee']['nominee_relation'],
    'nominee_age' => $post_data['nominee']['nominee_age'],
    'appointee_full_name' => $post_data['nominee']['appointee_name'],
    'appointee_relation' => $post_data['nominee']['appointee_relation'],
    'appointee_age' => $post_data['nominee']['appointee_age'],
    'created_date' => date('Y-m-d H:i:s')
    );
// echo '<pre>';print_r($insert_customer_detail);die;
    $inserted_customer_detail = $this->db->insert('tvs_customer_details', $insert_customer_detail);
    $customer_detail_last_id = $this->db->insert_id();
    if($customer_detail_last_id) {
        
        $date_result = $this->Rsa_Api_Model->StartEndDate($post_data['plan']['plan_type'],$plan_details['rsa_tenure']);
        $effective_date = $date_result['effective_date'];
        $end_date = $date_result['end_date'];
        $selected_date = date('Y-m-d');
        $pa_effective_date = date('Y-m-d', strtotime($selected_date . "0 day")). ' '.date('H:i:s');
        $pa_end_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime($selected_date . "-1 day")) . "1 year")). ' 23:59:59';
        $model_id = $this->Rsa_Api_Model->getMakeModelNameByName('model', strtolower(trim($post_data['vehicle']['model_name'])));
            $dms_response = $post;
            $insert_data_sold = array(
            'user_id' => $dealer_data['id'],
            'employee_code' => $dealer_code,
            'plan_id' => $plan_details['id'],
            'plan_name' => $plan_details['plan_name'],
            'customer_id' => $customer_detail_last_id,
            'sold_policy_no' => $post_data['vehicle']['engine_no'],
            'sold_policy_date' => date('Y-m-d H:i:s'),
            'sold_policy_effective_date' => $effective_date,
            'sold_policy_end_date' => $end_date,
            'pa_sold_policy_effective_date' => $pa_effective_date,
            'pa_sold_policy_end_date' => $pa_end_date,
            'sold_policy_price_with_tax' => ($plan_details['plan_amount']+$plan_details['gst_amount']),
            'sold_policy_price_without_tax' => $plan_details['plan_amount'],
            'sold_policy_tax' => '18%',
            'sold_policy_igst' => '18',
            'engine_no' => $post_data['vehicle']['engine_no'],
            'chassis_no' => $post_data['vehicle']['chassis_no'],
            'make_id' => '11',
            'model_id' => $model_id,
            'make_name' => 'TVS',
            'model_name' => $post_data['vehicle']['model_name'],
            'vehicle_registration_no' => strtoupper($post_data['vehicle']['vehicle_registration_no']),
            'registration_date'=>$post_data['vehicle']['registration_date'],
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
            'transection_no' => '',
            'dms_response'=>$dms_response,
            'rsa_ic_id' => $dealer_data['rsa_ic_master_id'],
            'vehicle_type' => $post_data['vehicle']['vehicle_type'],
            'sold_policy_date' => date('Y-m-d H:i:s'),
            'created_date' => date('Y-m-d H:i:s')

        );
        // echo '<pre>'; print_r($insert_data_sold);die('here');
        $inserted_id = $this->Home_Model->insertIntoTable('tvs_pending_sold_policies',$insert_data_sold);
        if(!empty($inserted_id)){
        $this->PendingRSASendSMS($post_data['customer']['insured_mobile_no'],$inserted_id);
        }
        // echo $this->db->last_query();die;
        return $inserted_id;
}

}


function MailSoldPolicyPdf($inserted_id,$pa_ic_id) {
        switch ($pa_ic_id) {
            case 2:
                $policypdf_obj = $this->Rsa_Api_Model->DownloadKotakFullPolicy($inserted_id);
                break;
            case 5:
                $policypdf_obj = $this->Rsa_Api_Model->ICICI_full_Pdf($inserted_id);
                break;
            // case 7:
            //     $policypdf_obj = $this->HDFCPDF($inserted_id);
            //     break;
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
        
        $where = array(
            'id' => $rsa_policy_data['rsa_ic_id']
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

function RSAsendSms($Mobilenumber,$sold_policy_id) {
        $rsa_policy_data = $this->Home_Model->getPolicyById($sold_policy_id);
        switch ($rsa_policy_data['ic_id']) {
            case 2:
                // Kotak...
            $policy_pdf = 'download_kotak_lite_pdf';
                break;
            case 5:
                // ICICL...
            $policy_pdf = 'download_il_lite_pdf';
                break;
            case 7:
                // HDFC...
            $policy_pdf = 'download_hdfc_policy';
                break;
            case 8:
                // Reliance...
            $policy_pdf = 'download_reliance_policy';
                break;
            case 9:
                // TATA...
            $policy_pdf = 'download_tata_lite_pdf';
                break;
            case 12:
                // Bharti Axa...
            $policy_pdf = 'download_bagi_lite_pdf';
                break;
            case 13:
                // Liberty...
            $policy_pdf = 'download_liberty_policy';
                break;
            case 10:
                // Oriental...
            $policy_pdf = 'download_OICL_pdf';
                break;
            
            
        }
        $base_url = base_url().$policy_pdf.'/'.$sold_policy_id;
        // echo $base_url.'<br>';
        // $short_policy_url = $this->make_bitly_url($base_url,'json');
        // echo $short_policy_url.'<br>';
        $Message = "Welcome to my TVS RSA to find your policy please click on given link ".$base_url;
        // echo $Message,'<br>';
        error_reporting(1);
        
        $message = urlencode($Message);
        
        $output = $this->CurlSMS($message,$Mobilenumber);
       // echo "<pre>"; print_r($output); echo "</pre>"; die('end of line yoyo');

        return $output;
    }

function PendingRSASendSMS($Mobilenumber,$policy_id){
    $rsa_policy_data = $this->Home_Model->getPolicyById($sold_policy_id);
        
    $base_url = base_url('BuynowRSApolicy').'/'.$policy_id;
    // echo $base_url.'<br>';
    // $short_policy_url = $this->make_bitly_url($base_url,'json');
    // echo $short_policy_url.'<br>';
    $Message = "Dear Customer,Your policy is under pending request due to less balance.To generate this policy click on given link below ".$base_url;
    // echo $Message,'<br>';
    error_reporting(1);
    
    $message = urlencode($Message);
    
    $output = $this->CurlSMS($message,$Mobilenumber);
   // echo "<pre>"; print_r($output); echo "</pre>"; die('end of line yoyo');

    return $output;
}

function CurlSMS($message,$Mobilenumber){
    $uid = "MPOLNW";
    $pwd = 'Pwe$NiTm';
    $sid = "TVSRSA";
    $method = "GET";

    $get_url = "http://123.108.46.12/API/WebSMS/Http/v1.0a/index.php?username=".$uid."&password=".$pwd."&sender=TVSRSA&to=".$Mobilenumber."&message=".$message."&reqid=1&format=text";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $get_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            $output = curl_exec($ch);
            if($errno = curl_errno($ch)) {
                $error_message = curl_strerror($errno);
                echo "cURL error ({$errno}):\n {$error_message}";
            }
            curl_close($ch);
//            echo "out=". $output; die;
            if (curl_errno ( $ch )) {
            echo curl_error ( $ch );
            curl_close ( $ch );
            exit ();
        }

        return $output;
}

function TVSPendingPolicyByAPI_post(){
    
    $post = $this->post();
    $post_data = json_decode($post['post'],true);
    // echo "<pre>"; print_r($post_data); echo "</pre>"; die('end of line yoyo');
    $dealer_code = $post_data['dealer_code']['dealer_code'];
    $where = array('sap_ad_code'=>$dealer_code);
    $dealer_data = $this->Home_Model->getRowDataFromTable('tvs_dealers',$where);
    $master_policy_details = $this->getMasterpolicyNo($post_data,$state_id,$city_id);
    // echo "<pre>"; print_r($master_policy_details); echo "</pre>"; die('end of line yoyo');
    $is_exist_pendinpolicy = $this->Home_Model->checkIsPendingPolicyExist($post_data['vehicle']['engine_no'],$post_data['vehicle']['chassis_no']);
    // echo "<pre>"; print_r($is_exist_pendinpolicy); echo "</pre>"; die('end of line yoyo');
    if($is_exist_pendinpolicy){
        $response['status'] = false;
        $response['message'] = 'Duplicate Policy To Check Visit On Pending Section.';
    }else{
            $vehicle_registration_no = $post_data['vehicle']['vehicle_registration_no'];
            $dealer_code = $post_data['dealer_code']['dealer_code'];
            $dob = date("Y-m-d", strtotime($post_data['customer']['insured_dob']));
            $statecity = $this->Rsa_Api_Model->fetchStateCity($post_data['customer']['insured_pincode']);
            $city_id = $statecity['city_id'];
            $state_id = $statecity['state_id'];
            $state = $statecity['state'];
            $city = $statecity['city'];
            $where = array('id'=>$post_data['plan']['plan_id']);
            $plan_details  = $this->Home_Model->getRowDataFromTable('tvs_plans',$where);
            $insert_customer_detail = array(
            'fname' => $post_data['customer']['insured_fname'],
            'lname' => $post_data['customer']['insured_lname'],
            'email' => $post_data['customer']['insured_email_id'],
            'mobile_no' => $post_data['customer']['insured_mobile_no'],
            'gender' => $post_data['customer']['insured_gender'],
            'dob' => $dob,
            'addr1' => $post_data['customer']['insured_address1'],
            'addr2' => $post_data['customer']['insured_address2'],
            'state' => $state_id,
            'city' => $city_id,
            'state_name' => $state,
            'city_name' => $city,
            'pincode' => $post_data['customer']['insured_pincode'],
            'nominee_full_name' => $post_data['nominee']['nominee_name'],
            'nominee_relation' => $post_data['nominee']['nominee_relation'],
            'nominee_age' => $post_data['nominee']['nominee_age'],
            'appointee_full_name' => $post_data['nominee']['appointee_name'],
            'appointee_relation' => $post_data['nominee']['appointee_relation'],
            'appointee_age' => $post_data['nominee']['appointee_age'],
            'created_date' => date('Y-m-d H:i:s')
            );
            $inserted_customer_detail = $this->db->insert('tvs_customer_details', $insert_customer_detail);
            // echo $this->db->last_query();
            $customer_detail_last_id = $this->db->insert_id();
            // echo $customer_detail_last_id;die(' customer_detail_last_id');
            if($customer_detail_last_id) {
                
                $date_result = $this->Rsa_Api_Model->StartEndDate($post_data['plan']['plan_type'],$plan_details['rsa_tenure']);
                $effective_date = $date_result['effective_date'];
                $end_date = $date_result['end_date'];
                $selected_date = date('Y-m-d');
                $pa_effective_date = date('Y-m-d', strtotime($selected_date . "0 day")). ' '.date('H:i:s');
                $pa_end_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime($selected_date . "-1 day")) . "1 year")). ' 23:59:59';
                $model_id = $this->Rsa_Api_Model->getMakeModelNameByName('model', strtolower(trim($post_data['vehicle']['model_name'])));
                    $dms_response = $post['post'];
                    $insert_data_sold = array(
                    'user_id' => $dealer_data['id'],
                    'employee_code' => $dealer_code,
                    'plan_id' => $plan_details['id'],
                    'plan_name' => $plan_details['plan_name'],
                    'customer_id' => $customer_detail_last_id,
                    'sold_policy_no' => $post_data['vehicle']['engine_no'],
                    'sold_policy_date' => date('Y-m-d H:i:s'),
                    'sold_policy_effective_date' => $effective_date,
                    'sold_policy_end_date' => $end_date,
                    'pa_sold_policy_effective_date' => $pa_effective_date,
                    'pa_sold_policy_end_date' => $pa_end_date,
                    'sold_policy_price_with_tax' => ($plan_details['plan_amount']+$plan_details['gst_amount']),
                    'sold_policy_price_without_tax' => $plan_details['plan_amount'],
                    'sold_policy_tax' => '18%',
                    'sold_policy_igst' => '18',
                    'engine_no' => $post_data['vehicle']['engine_no'],
                    'chassis_no' => $post_data['vehicle']['chassis_no'],
                    'make_id' => '11',
                    'model_id' => $model_id,
                    'make_name' => 'TVS',
                    'model_name' => $post_data['vehicle']['model_name'],
                    'vehicle_registration_no' => strtoupper($post_data['vehicle']['vehicle_registration_no']),
                    'registration_date'=>$post_data['vehicle']['registration_date'],
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
                    'transection_no' => '',
                    'dms_response'=>$dms_response,
                    'rsa_ic_id' => $dealer_data['rsa_ic_master_id'],
                    'vehicle_type' => $post_data['vehicle']['vehicle_type'],
                    'sold_policy_date' => date('Y-m-d H:i:s'),
                    'created_date' => date('Y-m-d H:i:s')

                );
                // echo '<pre>'; print_r($insert_data_sold);
                $inserted_id = $this->Home_Model->insertIntoTable('tvs_pending_sold_policies',$insert_data_sold);
                // echo $inserted_id.'<br>';
                // echo $this->db->last_query();die('sold');
                if(!empty($inserted_id)){
                    $this->PendingRSASendSMS($post_data['customer']['insured_mobile_no'],$inserted_id);
                    redirect('BuynowRSApolicy/'.$inserted_id);
                }
                
            }
            
}

 echo json_encode($response);   

}



}