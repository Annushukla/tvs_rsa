<?php


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';


Class Admin extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_Model');
         $this->load->helper("common_helper");
    }

    function api_response($data, $http_status = REST_Controller::HTTP_OK){
        //header('Access-Control-Allow-Origin: *');
        $this->response($data, $http_status);
    }
/************************START OF RSA FRONT APIS****************************/
    function frontLogin_post(){
      $dealer_code = $this->input->post('dealer_code');
      $password = $this->input->post('password');
      $where = array(
        'employee_code' => $dealer_code, //email id 
        'password' => md5($password),
        'is_active' => 1
      );
      $agent_result = $this->Admin_Model->getRowDataFromTableWithOject('biz_users',$where);
       if (!empty($agent_result)) {
            $dealer_code = $agent_result->dealer_code;
            $where = array(
              'sap_ad_code'=>$dealer_code
            );
            $dealer_result = $this->Admin_Model->getRowDataFromTableWithOject('tvs_dealers',$where);
            $dealer_id = $dealer_result->id;
            $session_data = array(
                "id" => $dealer_result->id,
                "dealer_name" => $dealer_result->dealer_name,
                'logged_in' => $dealer_id,
                "dealer_code" => $dealer_result->dealer_code,
                "sap_ad_code" => $agent_result->employee_code,
                "ad_name" => $dealer_result->ad_name,
                "tin_no" => $dealer_result->tin_no,
                "gst_no" => $dealer_result->gst_no,
                "pan_no" => $dealer_result->pan_no,
                "bank_name" => $dealer_result->bank_name,
                "bank_acc_name" => $dealer_result->bank_acc_name,
                "bank_acc_no" => $dealer_result->bank_acc_no,
                "bank_ifsc_code" => $dealer_result->bank_ifsc_code,
                "add1" => $dealer_result->add1,
                "add2" => $dealer_result->add2,
                "add3" => $dealer_result->add3,
                "location" => $dealer_result->location,
                "state" => $dealer_result->state,
                "pin_code" => $dealer_result->pin_code,
                "email" => $dealer_result->email,
                "rsa_ic_master_id" => $dealer_result->rsa_ic_master_id,
                'company_type_id' => $dealer_result->company_type_id,
                'Company_type_name' => '',
            );
            $output_data = [
                'status' => FALSE,
                'message' => 'Wrong login details',
                'result' => ''
                    
            ];
            if (isset($dealer_id) && !empty($dealer_id)) {
                 $output_data = [
                  'status' => TRUE,
                  'message' => 'Login Succesfully',
                  'result' => $session_data
                ];
              }
        }
      $this->api_response($output_data);
    }

    // function generatePolicyForm_get(){
          
    // }


    // function checkExistEngineNo_post(){

    // }

    // function generatePolicy_post(){

    // }

/************************END OF RSA FRONT APIS****************************/

    function login_post(){
      $email = $this->input->post('pancard');
      $password = $this->input->post('password');
      $where = array(
        'email_id' => $email, //email id 
        'password' => md5($password) ,
      );

     // echo '<pre>'; print_r($where);die('here');

      $result = $this->Admin_Model->getRowDataFromTableWithOject('tvs_admin',$where);

      if($result->admin_user_type_id != 1){
           $where = array(
          'id' => $result->admin_user_type_id
        );
        $admin_mapping = $this->Admin_Model->getRowDataFromTableWithOject('admin_mapping',$where); 
        // print_r($admin_mapping);
      }
   

      $output_data = [
          'status' => FALSE,
          'message' => 'Wrong login details',
          'result' => ''
              
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Login Succesfully',
          'result' => $result,
          'map_result'=>$admin_mapping
              
        ];

      }

      $this->api_response($output_data);
    }
/**************Start Get Policy Details ********************/
    function getPolicyDetails_post(){
      $post_data = $this->input->post();
      $result = $this->Admin_Model->getPolicyDetails($post_data);
      $output_data = [
          'status' => FALSE,
          'message' => 'Data Not Exist',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Data Exist',
          'result' => $result
              
        ];
      }
      $this->api_response($output_data);
    }
/**************End Get Policy Details ********************/
/**************Start Set Question ********************/
     function getQuestionaryData_get(){
      $post_data = $this->input->post();
      $result = $this->Admin_Model->getDataFromTable('tvs_claim_questionaries');
      //echo '<pre>'; print_r($result);die('here');
      // foreach ($result as $key => $value) {
      //     $where = array(
      //     'question_id' => $value['id'], //email id 
      //     'is_active' => 1 ,
      //     );
      //     $result_array[$value['question']]['ans'] = $this->Admin_Model->getDataFromTable('tvs_claim_customer_answer',$where);
      // }
      $output_data = [
          'status' => FALSE,
          'message' => 'Data Not Exist',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Data Exist',
          'result' => $result
              
        ];
      }
      $this->api_response($output_data);
    }
/**************End Get Question ********************/
/**************Start get Ans List ********************/
    function getAnsListByQuestion_post(){
      $post_data = $this->input->post();

       $where = array(
        'question_id' => $post_data['selected_qus_id'], //email id 
        'is_active' => 1 ,
      );
      $result = $this->Admin_Model->getDataFromTable('tvs_claim_customer_answer',$where);
     $output_data = [
          'status' => FALSE,
          'message' => 'Answers Data Not Exist',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Answers Data Exist',
          'result' => $result
              
        ];
      }
      $this->api_response($output_data);
    }
/**************Start Get Ans List ***************************/
/**************Start Set Question Answer ********************/
    function setQuesAnsDetails_post(){
      $post_data = $this->input->post();
    
      $policy_details = json_decode($post_data['policy_details'],TRUE);
      $mobile_no = ($post_data['customer_mobile_no'] == '') ? $policy_details['mobile'] : $post_data['customer_mobile_no'] ; 
      date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
      $post_data['created_at'] =  date('Y-m-d H:i:s');
      $result = $this->Admin_Model->insertCustomerQuesAnsDetails('customer_ques_ans_details',$post_data);

      $task_id=number_encrypt($result);
      $where = array(
           'id' => $result
      );
      $data = array('jobid'=>$task_id);
      $result = $this->Admin_Model->updateTable('customer_ques_ans_details',$data,$where);

      $output_data = [
              'status' => FALSE,
              'message' => 'Error in Adding Details',
              'result' => ''
          ];

      if($result){
          $job_id = $result;
          $result = TRUE;
          if(!empty($result)){
             $link = "https://myassistancenow.com/tvsservices/crm/customer-location-details/".$task_id ; 
             $message = 'Dear '.$policy_details['fname'].', your case number is : RSA'.$task_id .'. Kindly visit below link to confirm assistance : '.$link;
            
             if($policy_details['rsa_ic_id'] == 2){
                  $rsa_ic_id = sendSms($mobile_no,$message);
             }

            $output_data = [
              'status' => TRUE,
              'message' => 'Added Succesfully',
              'result' => $result
            ];
          }
        }
      $this->api_response($output_data);

    }
/**************End Set Question Answer ********************/

/**************Start Update Question Answer ********************/
    function updateQuesAnsDetails_post(){
       $post_data = $this->input->post();
       $where = array(
        'id' => $post_data['task_id']
      );
      $mobile_no = ($post_data['customer_mobile_no'] == '') ? $employee_details['mobile'] : $post_data['customer_mobile_no'] ; 
      $data = array('question_id'=>$post_data['question_id'],
                    'answer_id'=>$post_data['answer_id'],
                    'customer_mobile_no'=>$post_data['customer_mobile_no'],
                    'towing'=>$post_data['towing'],
                    'update_at'=>date('Y-m-d H:i:s')
                  );

      $result = $this->Admin_Model->updateTable('customer_ques_ans_details',$data,$where);
      $policy_details = json_decode($post_data['policy_details'],TRUE);
      
      $job_id =$post_data['task_id'];
      $task_id=number_encrypt($job_id);
      

      $result = TRUE;
      $output_data = [
          'status' => FALSE,
          'message' => 'Error in Adding Details',
          'result' => ''
      ];
      if(!empty($result)){
        $link = "https://myassistancenow.com/tvsservices/crm/customer-location-details/".$task_id ; 
        $message = 'Dear '.$policy_details['fname'].', your case number is : RSA'.$task_id .'. Kindly visit below link to confirm assistance : '.$link;
        
        $status = sendSms($mobile_no,$message);
        // print_r($status); 
        $output_data = [
          'status' => TRUE,
          'message' => 'Added Succesfully',
          'result' => $result
        ];
      }
      $this->api_response($output_data);

    }
/**************End Update Question Answer ********************/

/**************Start Get Nearest Vendor ********************/
    function getNearestVendores_post(){
      $post_data = $this->input->post();   
      $task_id = $post_data['task_id']; 

      $where = array(
        'id'=>$task_id
      );
      $task_details = $this->Admin_Model->getRowDataFromTable('customer_ques_ans_details',$where);
      // echo $this->db->last_query();
      // die;

      $result = $this->Admin_Model->getNearestVendores($task_details);
      $output_data = [
          'status' => FALSE,
          'message' => 'Error in Adding Details',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Added Succesfully',
          'result' => $result
        ];
      }
      $this->api_response($output_data);

    }
/**************End Get Nearest Vendor ********************/
/**************Start Update Vendor Reach Status ********************/
    function updateVendorReachStatus_post(){
      $post_data = $this->input->post();   
      date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)

      $where = array(
        'id'=>$post_data['task_id']
      );  

      $status_update = array('rsa_status_id'=>7);
      $vendor_status_update = $this->Admin_Model->updateTable('customer_ques_ans_details',$status_update,$where);
      $output_data = [
                'status' => FALSE,
                'message' => 'Error in Updating Status',
                'result' => ''
            ];
      if($vendor_status_update){
             $update_vendor_data = array(
              'reach_status'=>'reached',
               'reach_time'=>date('H:i:s'),
               'reach_date'=>date('Y-m-d')
            );
            $update_vendor_where = array(
              'task_id'=>$post_data['task_id'],
              'vendor_id'=>$post_data['vendor_id']
            ); 
            $result = $this->Admin_Model->updateTable('vendor_current_record',$update_vendor_data,$update_vendor_where);
            $output_data = [
                'status' => FALSE,
                'message' => 'Error in Updating Status',
                'result' => ''
            ];
            if(!empty($result)){
              $output_data = [
                'status' => TRUE,
                'message' => 'Updated Succesfully',
                'result' => $result
              ];
            }
          }
      $this->api_response($output_data);

    }
/**************End Update Vendor Reach Status ********************/

/**************Start Add Vendor Contact No  ********************/
    // function addVendorContactNo_post(){
    //   $post_data = $this->input->post();   
    //   $where = array(
    //     'id'=>$post_data['task_id']
    //   );
    //   $update_data = array(
    //      'vendor_contact_no'=>$post_data['mobile_no']
    //   );
    //   $result = $this->Admin_Model->updateTable('customer_ques_ans_details',$update_data,$where);
    //   $output_data = [
    //       'status' => FALSE,
    //       'message' => 'Error in Updating Status',
    //       'result' => ''
    //   ];
    //   if(!empty($result)){
    //     $output_data = [
    //       'status' => TRUE,
    //       'message' => 'Updated Succesfully',
    //       'result' => $result
    //     ];
    //   }
    //   $this->api_response($output_data);

    // }
/**************End Add Vendor Contact No ********************/

/**************Start Update Vendor Task Complete Status ********************/
    function updateVendorTaskCompletedStatus_post(){

      $post_data = $this->input->post();  

       $where = array(
        'id'=>$post_data['task_id']
      );  

      $status_update = array('rsa_status_id'=>8);
      $vendor_status_update = $this->Admin_Model->updateTable('customer_ques_ans_details',$status_update,$where);

      $output_data = [
                    'status' => FALSE,
                    'message' => 'Error in Updating Status',
                    'result' => ''
                ];

      if($vendor_status_update){
                $update_vendor_data = array(
                  'task_completed_status'=>$post_data['vendor_task_complete_status'],
                   'task_completed_comment'=>$post_data['vendor_task_completed_comment'],
                   'task_completed_time'=> date('Y-m-d H:i:s')
                );

                // echo "<pre>";
                // print_r($update_vendor_data);
                // die;
                $update_vendor_where = array(
                  'task_id'=>$post_data['task_id'],
                  'vendor_id'=>$post_data['vendor_id']
                ); 
                 $result = $this->Admin_Model->updateTable('vendor_current_record',$update_vendor_data,$update_vendor_where);

                $where = array(
                  'id'=>$post_data['task_id']
                ); 
                 $update_data = array(
                  'vendor_task_completed_status'=>$post_data['vendor_task_complete_status']
                );
                 $result_vendor = $this->Admin_Model->updateTable('customer_ques_ans_details',$update_data,$where);
                 // echo $this->db->last_query();


                // echo $this->db->last_query();
                // die;

                
                if(!empty($result)){
                  $output_data = [
                    'status' => TRUE,
                    'message' => 'Updated Succesfully',
                    'result' => $result
                  ];
                }
        }
      $this->api_response($output_data);

    }
/**************End Update Vendor Task Complete Status ********************/


/**************Start Update Vendor Task Complete Status ********************/
      function updateAdminTaskCompletedStatus_post(){

        $post_data = $this->input->post();  

        $where = array(
        'id'=>$post_data['task_id']
        );  

        $status_update = array('rsa_status_id'=>9);
        $vendor_status_update = $this->Admin_Model->updateTable('customer_ques_ans_details',$status_update,$where);

        $output_data = [
          'status' => FALSE,
          'message' => 'Error in Updating Status',
          'result' => ''
        ];

        if(!empty($vendor_status_update)){
              $output_data = [
              'status' => TRUE,
              'message' => 'Updated Succesfully',
              'result' => $vendor_status_update
              ];
        }

        $this->api_response($output_data);

      }
/**************Start Update Customer Task Complete Status ********************/
    function updateCustomerTaskCompletedStatus_post(){
      $post_data = $this->input->post();   
      $where = array(
        'id'=>$post_data['task_id'],
        'rsa_status_id'=>10
      ); 
      $update_data = array(
        'customer_task_complete_status'=>$post_data['customer_task_complete_status'],
        'customer_task_complete_comment'=>$post_data['customer_task_complete_comment']
      );
      $result = $this->Admin_Model->updateTable('customer_ques_ans_details',$update_data,$where);
      $output_data = [
          'status' => FALSE,
          'message' => 'Error in Updating Status',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Updated Succesfully',
          'result' => $result
        ];
      }
      $this->api_response($output_data);

    }
/**************End Update Customer Task Complete Status ********************/


/**************Start Add Workshop To Task ********************/
    function addWorkshopToTask_post(){
      $post_data = $this->input->post();   
      $where = array(
        'id'=>$post_data['task_id']
      ); 
      $update_data = array(
        'workshop_id'=>$post_data['workshop_id'],
        'rsa_status_id'=>4

      );
      $result = $this->Admin_Model->updateTable('customer_ques_ans_details',$update_data,$where);
      // echo '<pre>'; print_r($result);die('here');
      $output_data = [
          'status' => FALSE,
          'message' => 'Error in Updating Status',
          'result' => ''
      ];
       if(!empty($result)){
          $output_data = [
            'status' => TRUE,
            'message' => 'Succesfully Updating Status',
            'result' => ''
          ];

       }
      $this->api_response($output_data);

    }
/**************End Add Workshop To Task ********************/


/**************Start Submit Customer Lat Log ********************/


    function submitCustomerLatLong_post(){
      $post_data = $this->input->post();   
      $where = array(
        'id'=>$post_data['task_id']
      );  
      $update_data = array(
        'lat'=>$post_data['latsource'],
         'longi'=>$post_data['langsource'],
         'cust_curr_addr'=>$post_data['cust_curr_addr'],
          'rsa_status_id' => 3
         );
      $result = $this->Admin_Model->updateTable('customer_ques_ans_details',$update_data,$where);
      // echo $this->db->last_query();
      
      $output_data = [
          'status' => FALSE,
          'message' => 'Error in Adding Details',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Added Succesfully',
          'result' => $result
        ];
      }
      $this->api_response($output_data);

    }

/**************End Submit Customer Lat Log ********************/



/**************Start Get All Policy Details ********************/
    function getAllPolicyDetails_post(){
      $post_data = $this->input->post();
      $rsa_ic_id  = '';
      if($post_data['icToList'] == 1){
		$rsa_ic_id = 1;
      }
      $result = $this->Admin_Model->getAllPolicyDetails($rsa_ic_id);
      // echo $this->db->last_query();
      // die;

      foreach ($result as $key => $value) {
        $policy_details = json_decode($value['policy_details']);;
        $mobile_no = ($value['customer_mobile_no'] == '') ? $policy_details->mobile : $value['customer_mobile_no'];
        $policy_detail_array[$key]['policy_no'] = $value['policy_no'];
        $policy_detail_array[$key]['id'] = $value['id'];
        $policy_detail_array[$key]['customer_mobile_no'] = $mobile_no;
        $policy_detail_array[$key]['policy_details'] = json_decode($value['policy_details']);
        $policy_detail_array[$key]['lat'] = $value['lat'];
        $policy_detail_array[$key]['longi'] = $value['longi'];
        $policy_detail_array[$key]['workshop_id'] = $value['workshop_id'];
        $policy_detail_array[$key]['vendor_id'] = $value['vendor_id'];
        $policy_detail_array[$key]['customer_task_complete_status'] = ($value['customer_task_complete_status'] == '') ? 'pending' : $value['customer_task_complete_status'];
        $policy_detail_array[$key]['is_invoice_generated'] = $value['is_invoice_generated'];
        $policy_detail_array[$key]['created_at'] = $value['created_at'];
        $policy_detail_array[$key]['vendor_task_complete_status'] = $value['vendor_task_completed_status'];
        $policy_detail_array[$key]['vendor_travel_time'] = $value['vendor_travel_time'];
        $policy_detail_array[$key]['rsa_ic_id'] = $value['rsa_ic_id'];

        $policy_detail_array[$key]['question_id'] = $value['question_id'];
        $policy_detail_array[$key]['vendor_reach_time'] = $value['reach_time'];
        $policy_detail_array[$key]['vendor_reach_date'] = $value['reach_date'];

        $policy_detail_array[$key]['task_completed_status'] = $value['task_completed_status'];
        $policy_detail_array[$key]['vendor_name'] = $value['member_Name'];
        $policy_detail_array[$key]['vendor_email'] = $value['email_id'];
        $policy_detail_array[$key]['vendor_contact_no'] = $value['vendor_contact_no'];
        $policy_detail_array[$key]['task_start_time'] = $value['task_start_time'];
        $policy_detail_array[$key]['reach_status'] = $value['reach_status'];
        $policy_detail_array[$key]['rsa_status_id'] = $value['rsa_status_id'];
        $policy_detail_array[$key]['status_code'] = $value['status_code'];
        $policy_detail_array[$key]['status_labal'] = $value['status_labal'];
      }

      // print_r($policy_detail_array);

      //echo '<pre>'; print_r($result);die('here');
      $output_data = [
          'status' => FALSE,
          'message' => 'Data Not Exist',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Data Exist',
          'result' => $policy_detail_array
              
        ];
      }
      $this->api_response($output_data);
    }



     function checkPolicyNoStatus_post(){
       $post_data = $this->input->post();
       $key =  $post_data['policy_no'];
       $is_exist = $this->Admin_Model->checkPolicyKey($key);
       // echo "<pre>";
       // print_r($is_exist);
       // die;

       // echo $this->db->last_query();
       // die;
       
       if(!empty($is_exist) && $is_exist['status_code'] != 'job_completed'){
       		  $output_data = [
		           'status' => FALSE,
		           'message' => 'Policy Number already Exist',
		           'result' => $is_exist
		         ];
       }else{
       		$result = $this->Admin_Model->getPolicyDetails($post_data['policy_no']);
       		$output_data = [
		           'status' => FALSE,
		           'message' => 'Policy Number Not Found.',
		           'result' => ''
		         ];
       		if(!empty($result)){
       			$output_data = [
		           'status' => TRUE,
		           'message' => 'Policy Number Found.',
		           'result' => $result
		         ];
       		}


       }	
       $this->api_response($output_data);
   }

     function customerVerificationByTaskID_get($task_id){
      $data = array();
      $og_task_id = $task_id;
       $og_task_id = number_decrypt($task_id);
       $where = array('id'=>$og_task_id);
       $result = $this->Admin_Model->getRowDataFromTable('customer_ques_ans_details',$where);
       if(!empty($result['lat'])){
          $all_party_details = $this->getAllLatLongWithCust($og_task_id);
       }
       $policy_no = $result['policy_no'];
       $data['policy_data'] = $this->Admin_Model->getPolicyDetails($policy_no);
       $where_qun = array("id"=> $result['question_id']);
       $question_details = $this->Admin_Model->getRowDataFromTable('tvs_claim_questionaries',$where_qun);
       $data['all_party_details'] = $all_party_details;
       $data['question'] = $question_details['question'];
       $data['qun_ans_data'] = $result;
     $this->load->dashboardTemplate('api_view/customer',$data);
   }
   function vendorVerificationByTaskID_get($task_id){
       $data = array();
       $og_task_id = $task_id;
       // $og_task_id = number_decrypt($task_id);
       $where = array('id'=>$og_task_id);
       $result = $this->Admin_Model->getRowDataFromTable('customer_ques_ans_details',$where);
       $policy_no = $result['policy_no'];
       $data['policy_data'] = $this->Admin_Model->getPolicyDetails($policy_no);
       $where_qun = array("id"=> $result['question_id']);
       $question_details = $this->Admin_Model->getRowDataFromTable('tvs_claim_questionaries',$where_qun);
       $data['question'] = $question_details['question'];
       $data['qun_ans_data'] = $result;
     $this->load->dashboardTemplate('api_view/vendor',$data);
   }
   function getAllLatLongWithCust($task_id){
      //die($task_id);  
      $where = array(
        'id'=>$task_id
      );
      //$result = $this->Admin_Model->getAllLatLongWithCustomer($task_id);
      $task_details = $this->Admin_Model->getRowDataFromTable('customer_ques_ans_details',$where);
      $output_data = [
          'cust_status' => FALSE,
          'vendor_status' => FALSE,
          'workshop_status' => FALSE,
          'cust_message' => 'Error in Customer Locations',
          'vendor_message' => 'Error in Vendor Locations',
          'workshop_message' => 'Error in Workshop Locations',
          'result' => ''
      ];
      if(!empty($task_details)){
         $output_data['cust_status'] = TRUE;
         $output_data['cust_message'] = 'Succesfully Fetched Customer Locations';
         $customer_location = array(
          'lat'=>$task_details['lat'],
          'longi'=>$task_details['longi'],
          'vendor_distance'=>$task_details['vendor_distance'],
          'vendor_travel_time'=>$task_details['vendor_travel_time']
        );
       $vendor_location = array();
       $workshop_location = array();
      if(!empty($task_details['vendor_id'])){
       $output_data['assigned_vendor'] = $task_details['vendor_id'];
      }
       $vendor_location = $this->Admin_Model->getNearestVendores($task_details);
        if(!empty($vendor_location)){
          $output_data['vendor_status'] = TRUE;
            $output_data['vendor_message'] = 'Succesfully Fetched Vendor Locations';
            $output_data['vendor_asigned_status'] = FALSE;
        }
      // else{
      //       $where = array(
      //         'id'=>$task_details['vendor_id']
      //       );
      //       $vendor_location = $this->Admin_Model->getDataFromTable('vendor_details',$where);
      //       if(!empty($vendor_location)){
      //         $output_data['vendor_status'] = TRUE;
      //         $output_data['vendor_message'] = 'Succesfully Fetched Vendor Locations';
      //         $output_data['vendor_asigned_status'] = TRUE;
      //       }
      //   }
      if(empty($task_details['workshop_id'])){
          $workshop_location = $this->Admin_Model->getNearestWorkshop($task_details);
          if(!empty($workshop_location)){
            $output_data['workshop_status'] = TRUE;
            $output_data['workshop_message'] = 'Succesfully Fetched Vendor Locations';
            $output_data['workshop_asigned_status'] = FALSE;
          }
        }else{
          $where = array(
              'id'=>$task_details['workshop_id']
            );
            $workshop_location = $this->Admin_Model->getDataFromTable('tvs_dealers',$where);
            if(!empty($vendor_location)){
                $output_data['workshop_status'] = TRUE;
                $output_data['workshop_message'] = 'Succesfully Fetched Vendor Locations';
                $output_data['workshop_asigned_status'] = TRUE;
            }
        }
           $result = array(
              'customer_location'=>$customer_location,
              'vendor_location'=>$vendor_location,
              'workshop_location'=>$workshop_location
            );
          $output_data['result'] = $result;
        }
        return $output_data;
        //echo '<pre>'; print_r($output_data);die('here');
      //$this->api_response($output_data);
    }

/**************End Get All Policy Details ********************/


/**************Start Add WorkShop********************/

function addWorkShop_post(){
    $post_data = $this->input->post();
    $post_data = array(
      'workshop_code'=>$post_data['workshop_code'],
      'contact_person'=>$post_data['contact_person'],
      'mobile'=>$post_data['mobile'],
      'lat'=>$post_data['lat'],
      'longi'=>$post_data['longi'],
      'is_provide_service_in_off_time'=>$post_data['off_time_service_status'],
      'opening_time'=>$post_data['opening_time'],
      'closing_time'=>$post_data['closing_time']
    );
    $result = $this->Admin_Model->insertIntoTable('workshop_details',$post_data);
     $output_data = [
          'status' => FALSE,
          'message' => 'Error In Inserting Data',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Succesfully Inserted Data',
          'result' => $result
        ];
      }
      $this->api_response($output_data);
}

/**************End Add WorkShop********************/

/**************Start Add WorkShop********************/

function getCompanyType_get(){
    $result = $this->Admin_Model->getDataFromTable('company_type');
      $output_data = [
          'status' => FALSE,
          'message' => 'Error In Getting Company Types',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Succesfully Fetched Company Types',
          'result' => $result
        ];
      }
      $this->api_response($output_data);
}

/**************End Add WorkShop********************/


/**************Start Add WorkShop********************/

function getStateCityByPinCode_Post(){
  $pincode = $this->input->post('pin_code');
  $where = array('pin_code'=>$pincode);
    $result = $this->Admin_Model->getRowDataFromTable('tvs_pincode_master',$where);
      $output_data = [
          'status' => FALSE,
          'message' => 'Error In Getting State City',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Succesfully Fetched State City',
          'result' => $result
        ];
      }
      $this->api_response($output_data);
}
/**************End Add WorkShop********************/

/**************Start Get WorkShop List********************/
function getWorkShopList_get(){
    $result = $this->Admin_Model->getDataFromTable('workshop_details');
      $output_data = [
          'status' => FALSE,
          'message' => 'Error In Getting Workshop List',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Succesfully Fetched Workshop List',
          'result' => $result
        ];
      }
      $this->api_response($output_data);
}

/**************End Get WorkShop List********************/

/**************Start Get WorkShop List********************/
function getDealersList_get(){
  $where = array('dealer_type'=>'AMD');
    $result = $this->Admin_Model->getDataFromTable('tvs_dealers',$where);
      $output_data = [
          'status' => FALSE,
          'message' => 'Error In Getting Dealers List',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Succesfully Fetched Dealers List',
          'result' => $result
        ];
      }
      $this->api_response($output_data);
}

/**************End Get WorkShop List********************/





function getBankDetailsByIFSC_post(){
   $ifsccode = $this->input->post('ifsc_code');
    $result = getBankDetailsByIFSC($ifsc_code);
    echo '<pre>'; print_r($result);die('here');
      $output_data = [
          'status' => FALSE,
          'message' => 'Error In Getting Workshop List',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Succesfully Fetched Workshop List',
          'result' => $result
        ];
      }
      $this->api_response($output_data);
}
/**************End Get WorkShop List********************/

/**************Start Get Dealers Details********************/
function getDealerDetailsByCode_Post(){
  $dealer_code = $this->input->post('dealer_code');
  $where = array('sap_ad_code'=>$dealer_code);
    $result = $this->Admin_Model->getRowDataFromTable('tvs_dealers',$where);
      $output_data = [
          'status' => FALSE,
          'message' => 'Error In Getting Dealer Details',
          'result' => ''
      ];
      if(!empty($result)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Succesfully Fetched Dealer Details',
          'result' => $result
        ];
      }
      $this->api_response($output_data);
}
/**************End Get Dealers Details********************/


function getPolicyDetailsByTaskID_post(){
        $post_data = $this->input->post();   
        $task_id = $post_data['task_id']; 
        $where = array(
            'id'=>$task_id
        );
        $task_details = $this->Admin_Model->getRowDataFromTable('customer_ques_ans_details',$where);
        // echo $this->db->last_query();

         $output_data = [
          'status' => FALSE,
          'message' => 'Error In Getting Task Details',
          'result' => ''
      ];
      if(!empty($task_details)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Succesfully Fetched Task Details',
          'result' => $task_details
        ];
      }
      $this->api_response($output_data);
}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/**************Start Add Vendor To Task ********************/
    function addVendorToTask_post(){
      $post_data = $this->input->post();   
      $where = array(
        'id'=>$post_data['task_id']
      ); 
      $update_data = array(
        'vendor_id'=>$post_data['vendor_id'],
         'rsa_status_id'=>5
      );
      $result = $this->Admin_Model->updateTable('customer_ques_ans_details',$update_data,$where);

      if($result>0){

              $update_status = array(
                   'active'=>'0'
              );
              $update_status_where = array(
                 'task_id'=>$post_data['task_id']
              );
              $vendor_status_result = $this->Admin_Model->updateTable('vendor_current_record',$update_status,$update_status_where);


              $insert_data = array(
                              'task_id'=>$post_data['task_id'],
                              'vendor_id'=>$post_data['vendor_id'],
                              'travel_time'=>$post_data['duration'],
                              'vendor_contact_no'=>$post_data['vendor_contact_no'],
                              'distance'=>$post_data['distance'],
                              'active'=>1
                              );

              $insert_result = $this->Admin_Model->insertIntoTable('vendor_current_record',$insert_data);
        }
      $output_data = [
          'status' => FALSE,
          'message' => 'Error in Updating Status',
          'result' => ''
      ];
      if(!empty($result)){
      $insert_log =array(
        'task_id'=>$post_data['task_id'],
        'vendor_id'=>$post_data['vendor_id'],
        'vendor_travel_time'=>$post_data['duration'],
        'vendor_contact_no'=>$post_data['vendor_contact_no'],
        'vendor_distance'=>$post_data['distance']
      );
      $is_log_inserted = $this->Admin_Model->insertIntoTable('vendor_assign_log',$insert_log);
      $task_details = $this->Admin_Model->getRowDataFromTable('customer_ques_ans_details',$where);
      $customer_mobile_no = $task_details['customer_mobile_no'];
      $vendor_mobile_no = $post_data['vendor_contact_no']; 

       $task_id=number_encrypt($task_details['id']);

      //$vendor_details = $this->Admin_Model->getRowDataFromTable('vendor_details',$where);
      $url_vendor = 'https://myassistancenow.com/tvsservices/crm/vendor-location-details/'.$task_id;
      // $url_customer = 'https://myassistancenow.com/tvsservices/crm/customer-details/'.$task_id;

      $msg = "Dear Vendor, ".$post_data['customer_name']." Customer is waiting for RSA assistance. Please track the customer from following link:".$url_vendor;
      // $msg_customer = "Dear Customer, ".$post_data['vendor_name']." Vendor is assigned for your service. Kindly track him from follwoing link :".$url_customer;
      // echo 'hello';die($vendor_mobile_no);
      $is_sent = sendSms($vendor_mobile_no,$msg);
      // if($is_sent){
      //   $is_sent_cust = sendSms($customer_mobile_no,$msg_customer);
      // }

      $output_data = [
          'status' => FALSE,
          'message' => 'Error in asigning task',
          'result' => ''
      ];
       if($is_sent){
          $output_data = [
            'status' => TRUE,
            'message' => 'Task Asigned',
            'result' => $is_sent
          ];

       }
        
      }
      $this->api_response($output_data);

    }
/**************End Add Vendor To Task ********************/



/**************Start Get All Lat Long With Customer ********************/
function getAllLatLongWithCustomer_post(){
      $post_data = $this->input->post(); 
      // echo '<pre>'; print_r($post_data); die('here'); 
      $task_id = $post_data['task_id'];   
      $where = array(
        'id'=>$task_id
      );
      //$result = $this->Admin_Model->getAllLatLongWithCustomer($task_id);
      $task_details = $this->Admin_Model->getRowDataFromTable('customer_ques_ans_details',$where);
      // echo '<pre>'; print_r($task_details); die;

      $vendor_where = array('task_id' => $task_id, 'vendor_id'=> $task_details['vendor_id']);
      $current_vendor_details = $this->Admin_Model->getRowDataFromTable('vendor_current_record',$vendor_where);
      // echo "<pre>";
      // print_r($current_vendor_details);
      // die;


      $output_data = [
          'cust_status' => FALSE,
          'vendor_status' => FALSE,
          'workshop_status' => FALSE,
          'cust_message' => 'Error in Customer Locations',
          'vendor_message' => 'Error in Vendor Locations',
          'workshop_message' => 'Error in Workshop Locations',
          'result' => ''
      ];
      if(!empty($task_details)){
         $output_data['cust-status'] = TRUE;
         $output_data['cust-message'] = 'Succesfully Fetched Vendor Locations';
         $customer_location = array(
          'lat'=>$task_details['lat'],
          'longi'=>$task_details['longi'], 
          'vendor_distance'=>$current_vendor_details['distance'],
          'vendor_travel_time'=>$current_vendor_details['travel_time'],
          'policy_details' => json_decode($task_details['policy_details']),
          'vendor_reach_status'=>$current_vendor_details['reach_status'],
          'customer_task_complete_status'=>$task_details['customer_task_complete_status'],
          'vendor_task_complete_status'=>$current_vendor_details['task_completed_status'],
          'vendor_image'=>$current_vendor_details['photo'],
          'vendor_mobile_no'=>$current_vendor_details['vendor_contact_no'],
          'jobid'=>$task_details['jobid'], 


        );
       $vendor_location = array();
       $workshop_location = array();
      if(!empty($task_details['vendor_id'])){
        $output_data['assigned_vendor'] = $task_details['vendor_id'];
        $key = 'id';
        $where = array(
          'task_id'=>$task_id,
          'vendor_id'=>$task_details['vendor_id']
        );
        $vendor_latest_lat_long = $this->Admin_Model->getRowDataFromTableOrderByDesc('vendor_location_track',$key,$where);
      //   echo "<pre>";
      // print_r($vendor_latest_lat_long);
        }
      if(!empty($vendor_latest_lat_long)){
            $output_data['vendor_lat'] = $vendor_latest_lat_long[0]['lat'];
            $output_data['vendor_long'] = $vendor_latest_lat_long[0]['longi'];
          }else{
            $output_data['vendor_lat'] = $current_vendor_details['lat'];
            $output_data['vendor_long'] = $current_vendor_details['long'];
        }


      $vendor_location = $this->Admin_Model->getNearestVendores($task_details);
        if(!empty($vendor_location)){
          $output_data['vendor_status'] = TRUE;
            $output_data['vendor_message'] = 'Succesfully Fetched Vendor Locations';
            $output_data['vendor_asigned_status'] = FALSE;
        }
     
      if(empty($task_details['workshop_id'])){
          $workshop_location = $this->Admin_Model->getNearestWorkshop($task_details);
          if(!empty($workshop_location)){
            $output_data['workshop_status'] = TRUE;
            $output_data['workshop_message'] = 'Succesfully Fetched Vendor Locations';
            $output_data['workshop_asigned_status'] = FALSE;
          }
        }else{
          $where = array(
              'id'=>$task_details['workshop_id']
            );
            $workshop_location = $this->Admin_Model->getDataFromTable('workshop_details',$where);
            if(!empty($vendor_location)){
                $output_data['workshop_status'] = TRUE;
                $output_data['workshop_message'] = 'Succesfully Fetched Vendor Locations';
                $output_data['workshop_asigned_status'] = TRUE;
            }
        }
           $result = array(
              'customer_location'=>$customer_location,
              'vendor_location'=>$vendor_location,
              'workshop_location'=>$workshop_location
            );
          $output_data['result'] = $result;
        }

        //echo '<pre>'; print_r($output_data);die('here');
      $this->api_response($output_data);
    }

/**************End Get All Lat Long With Customer ********************/

/**************Start Submit Customer Lat Log ********************/


    function submitVendorLatLong_post(){
      $post_data = $this->input->post();   
      $where = array(
        'id'=>$post_data['task_id']
      );  

      $status_update = array('rsa_status_id'=>6);
      $vendor_status_update = $this->Admin_Model->updateTable('customer_ques_ans_details',$status_update,$where);
      $output_data = [
                'status' => FALSE,
                'message' => 'Error in Adding Details',
                'result' => ''
            ];

      if($vendor_status_update){
            $image_data =  str_replace('[removed]', 'data:image/jpeg;base64,', $post_data['image']);
            $update_vendor_where = array(
                                    'task_id'=>$post_data['task_id'],
                                    'vendor_id'=>$post_data['vendor_id']
                                  ); 

            $update_vendor_data = array(
                  'lat'=>$post_data['vendor_lat'],
                  'long'=>$post_data['vendor_long'],
                  'photo'=> $image_data,
                  'task_start_time'=>date('Y-m-d H:i:s')
                  );

            $vendor_update_result = $this->Admin_Model->updateTable('vendor_current_record',$update_vendor_data,$update_vendor_where);

          
            if(!empty($vendor_update_result)){
                $task_details = $this->Admin_Model->getRowDataFromTable('customer_ques_ans_details',$where);
                $customer_mobile_no = $task_details['customer_mobile_no'];

                $task_id=number_encrypt($post_data['task_id']);
                $url_customer = 'https://myassistancenow.com/tvsservices/crm/customer-details/'.$task_id;
                $msg_customer = "Dear Customer, ".$post_data['vendor_name']." Vendor is assigned for your service. Kindly track him from follwoing link :".$url_customer;
                $is_sent_cust = sendSms($customer_mobile_no,$msg_customer);

              $output_data = [
                'status' => TRUE,
                'message' => 'Added Succesfully',
                'result' => $vendor_update_result
              ];
            }
       }
      $this->api_response($output_data);
    }

/**************End Submit Customer Lat Log ********************/


/**************Start Get All Policy Details ********************/
    function getVendorIDByTask_post(){
      $post_data = $this->input->post();   
      $where = array(
        'id'=>$post_data['task_id']
      );  
      //Update Vendor table start
      $result_task = $this->Admin_Model->getRowDataFromTable('customer_ques_ans_details',$where);
            $output_data = [
          'status' => FALSE,
          'message' => 'Error in Adding Details',
          'result' => ''
      ];
      if(!empty($result_task)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Added Succesfully',
          'result' => $result_task['vendor_id']
        ];
      }
      $this->api_response($output_data);

    }
/**************Start Get All Policy Details ********************/



/**************Start Get All Lat Long With Customer ********************/
function getCustomerLatLongForVendor_post(){
      $post_data = $this->input->post();   
      $task_id = $post_data['task_id'];   
      $where = array(
        'id'=>$task_id
      );


       $update_vendor_where = array(
                              'task_id'=>$post_data['task_id'],
                              'vendor_id'=>$post_data['vendor_id']
                            ); 

      //$result = $this->Admin_Model->getAllLatLongWithCustomer($task_id);
      $result = $this->Admin_Model->getRowDataFromTable('customer_ques_ans_details',$where);

      $vendor_result = $this->Admin_Model->getRowDataFromTable('vendor_current_record',$update_vendor_where);
      
      $output_data = [
          'status' => FALSE,
          'message' => 'Error in getting customer location',
          'result' => ''
      ];
      if(!empty($result)){
        $policy_details = json_decode($result['policy_details'],TRUE);
        $where = array('id'=>$result['workshop_id']);
        $workshop_details = $this->Admin_Model->getRowDataFromTable('workshop_details',$where);
        $workshop_details = !empty($workshop_details)?$workshop_details:array();
        $output_data = [
          'status' => TRUE,
          'message' => 'Succesfully Fetched customer Locations',
          'result' => $result,
          'policy_details' =>$policy_details,
          'workshop_details'=>$workshop_details,
          'vendor_details' => $vendor_result
        ];
      }
      $this->api_response($output_data);

    }


/**************End Get All Lat Long With Customer ********************/




function setVendorLatestLatLong_post(){
        $do_insert = TRUE;
        $post_data = $this->input->post(); 
        $where = array( 
          'task_id'=>$post_data['task_id'],
          'vendor_id'=>$post_data['vendor_id']
        );
        // echo "sdfdsfsdfsdf";
        $result = $this->Admin_Model->getRowDataFromTableOrderByDesc('vendor_location_track','id',$where);

        if(($result[0]['lat'] == $post_data['lat']) && ($result[0]['longi'] == $post_data['long'])){
            $do_insert = FALSE;
        }

        if(empty($result) || ($do_insert) ){
              $data = array(
                'task_id'=>$post_data['task_id'],
                'vendor_id'=>$post_data['vendor_id'],
                'lat'=>$post_data['lat'],
                'longi'=>$post_data['long'],
                'time'=>date('G:i:s')
              );
              $is_inserted = $this->Admin_Model->insertIntoTable('vendor_location_track',$data);
              $output_data = [
                'status' => TRUE,
                'message' => 'Succesfully Fetched Task Details',
                'result' => ''
              ];

        }else{
              $output_data = [
              'status' => TRUE,
              'message' => 'Vendor Location Updated Succesfully',
              'result' => ''
              ];
        }
      $this->api_response($output_data);
}

 function decriptJobId_post()
{
         $post_data = $this->input->post(); 
         // echo "<br>". $post_data['task_id']; 
         $task_id = number_decrypt($post_data['task_id']);
        if($task_id !=''){
             $output_data = [
                'status' => TRUE,
                'message' => 'Succesfully Fetched Task Details',
                'result' => $task_id
              ];

        }else{
              $output_data = [
              'status' => TRUE,
              'message' => 'Vendor Location Updated Succesfully',
              'result' => ''
              ];
        }
      $this->api_response($output_data);


}

 function uploadDatabaseFileTestDemo_post()
	{
	  echo "<pre>";
	  print_r($_POST);
	    $file = $_FILES["file"];
	    echo "<pre>";
	    print_r($file);
	    die;

	    // $amc_id = $this->input->post('amc_id');
	    // $amc_name = $this->input->post('amc_name');
	    // $file_type = $this->input->post('formDatabaseFile');

	    // $new_file_name = $this->Admin_Model->uploadFile('file','reports');
	    if($uploaded_successfully){
	      $status = true;
	      $message = "file uploaded succesfully.";
	    }else{
	      $status = false;
	      $message = "file not uploaded succesfully.";
	    }

	    $output_data = [
	      'status' => $status,
	      'message' => $message     
	                   
	    ];
	    $this->api_response($output_data);
	}

function sendSms($post_data){
    $post_data = $this->input->post();
    $customer_mobile_no = !empty($post_data['customer_mobile_no'])?$post_data['customer_mobile_no']:'';
    $vendor_mobile_no = !empty($post_data['vendor_mobile_no'])?$post_data['vendor_mobile_no']:'';
    $task_status = !empty($post_data['status_code'])?$post_data['status_code']:'';
    $customer_name = !empty($post_data['customer_name'])?$post_data['customer_name']:'';
    $jobid = !empty($post_data['job_id'])?$post_data['job_id']:'';
    $tracking_url = !empty($post_data['url'])?$post_data['url']:'';
    $invoice_url = !empty($post_data['invoice_url'])?$post_data['invoice_url']:'';
    $scheduled_date = !empty($post_data['scheduled_date'])?$post_data['scheduled_date']:'';
    $scheduled_time = !empty($post_data['scheduled_time'])?$post_data['scheduled_time']:'';
    $where = array('status_code'=>$task_status);
    $status_details = $this->Admin_Model->getRowDataFromTable('tvs_status_details',$where);
    $return_data = array();
    if(!empty($status_details)){
      switch ($task_status) {
        case 'job_created':
          $customer_sms  =  $status_details['customer_sms_lable'];
          $customer_sms  =  str_replace(array('[CUSTMOERNAME]', '[JOBID]','[CUSTOMERTRACKINGURL]'), array($customer_name, $jobid,$tracking_url), $customer_sms);
        break;
        case 'vendor_assigned':
          $vendor_sms  =  $status_details['vendor_sms_lable'];
          $vendor_sms  =  str_replace(array('[CUSTMOERNAME]', '[VENDERLOCATIONURL]'), array($customer_name,$tracking_url), $vendor_sms);
        break;
        case 'vendor_location_mapped':
          $customer_sms  =  $status_details['customer_sms_lable'];
          $customer_sms  =  str_replace(array('[CUSTMOERNAME]','[VENDORTRACKINGURL]'), array($customer_name,$tracking_url), $customer_sms);
        break;
        case 'job_completed':
          $customer_sms  =  $status_details['customer_sms_lable'];
          $customer_sms  =  str_replace(array('[CUSTMOERNAME]','[JOBID]'), array($customer_name,$jobid), $customer_sms);
        break;
        case 'customer_declined_service':
          $customer_sms  =  $status_details['customer_sms_lable'];
          $customer_sms  =  str_replace(array('[CUSTMOERNAME]','[JOBID]'), array($customer_name,$jobid), $customer_sms);

          $vendor_sms  =  $status_details['vendor_sms_lable'];
          $vendor_sms  =  str_replace(array('[CUSTMOERNAME]','[JOBID]'), array($customer_name,$jobid), $customer_sms);
        break;
        case 'invoice_generated':
          $customer_sms  =  $status_details['customer_sms_lable'];
          $customer_sms  =  str_replace(array('[CUSTMOERNAME]','[JOBID]','[CUSTOMERINVOICEURL]'), array($customer_name,$jobid,$invoice_url), $customer_sms);

          $vendor_sms  =  $status_details['vendor_sms_lable'];
          $vendor_sms  =  str_replace(array('[JOBID]','[CUSTOMERINVOICEURL]'), array($jobid,$invoice_url), $customer_sms);
        break;
        case 'assistance_scheduled':
          $customer_sms  =  $status_details['customer_sms_lable'];
          $customer_sms  =  str_replace(array('[CUSTMOERNAME]','[DATE]','[TIME]'), array($customer_name,$jobid,$invoice_url), $customer_sms);
          break;
        case 'vendor_declined':
          $customer_sms  =  $status_details['customer_sms_lable'];
          $customer_sms  =  str_replace(array('[CUSTMOERNAME]'), array($customer_name), $customer_sms);
          break;
        default:
          # code...
          break;
      }

      if(!empty($customer_sms)){
        $is_sent = sendSms($customer_mobile_no,$customer_sms);
          $return_data['status'] = 'false';
          $return_data['msg'] = 'Customer SMS Is Not Sent.';
        if($is_sent){
          $return_data['status'] = 'true';
          $return_data['msg'] = 'Customer SMS Is Sent.';
        }
      }else if(!empty($vendor_sms)){
       $is_sent = sendSms($vendor_mobile_no,$vendor_sms);
        $return_data['status'] = 'false';
          $return_data['msg'] = 'Vendor SMS Is Not Sent.';
        if($is_sent){
          $return_data['status'] = 'true';
          $return_data['msg'] = 'Vendor SMS Is Sent.';
        }
      }
    }else{
      $return_data['status'] = 'false';
      $return_data['msg'] = 'Status Is Not Exist.';
    }
    return $return_data;
  }

 function uploadFile($image_file,$directory){
        if ($image_file && $directory) {
            $filename = $_FILES[$image_file]["name"];
            $_FILES[$image_file]["name"] = time().$filename;
            $config = array(
                'upload_path' => './'.$directory,
                'allowed_types' => '*'
             );

             $this ->load->library("upload", $config);
            if ($this->upload->do_upload($image_file)) {
                $image_data = $this->upload->data();
                return $newimagename = $image_data["file_name"];
            }else{
            $error = array('error' => $this->upload->display_errors());
                return $error;
            
            }
        }
    }






}

          