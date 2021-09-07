<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

Class RR310Import extends CI_Controller {

  public function __construct() {
      parent::__construct();
      $this->load->model('Home_Model');
  }

  public function index(){
    $data['main_contain'] = 'admin/report/rr_310_vehcle_cust_import';
        $this->load->view('admin/includes/template', $data);
  }

  function SaveImportData(){
    // echo "<pre>"; print_r($_POST); echo "</pre>"; die('end of line yoyo');
    if(isset($_POST["cust_veh_import_hd"]))
    {
      $filename = explode(".", $_FILES['import_cust_veh_file']['name']);
      
      if(end($filename) == "csv")
      {
        $handle = fopen($_FILES['import_cust_veh_file']['tmp_name'], "r");
        $count=0;
        $column_heading = array('cust_name','contact_no','Email_Address','Address_line_1','Address_line_2','Pin_Code','Invoice_Date','Engine_No','Frame_No','Reg_No','Dealership_Name','Dealer_Id','Model');

        while($data = fgetcsv($handle))
          {
            $count++;
            $data=array_map('trim',$data);
            if($count==1){
              
                if($data != $column_heading){
                    echo "CSV is invalid ";exit;
                }
                
            }
              //echo "<pre>"; print_r($data); echo "</pre>";exit;
            if($count>1){ 
              // $dob = date("d-m-Y",strtotime($data[4]));
              $invoice_date = date("d-m-Y", strtotime($data[6]));

              $string_position = strpos($data[0], ' ');
                if($string_position=='') {
                  $fname = $data[0];
                  $lname = '';
                }
                else {
                  $fname = substr($data[0], 0,$string_position);
                  $lname = substr($data[0], $string_position+1);
                }            

              
              $rr310vehicle_customer_data = array(
                'fname'=>$fname, 
                'lname'=>$lname, 
                'cust_name'=>$data[0],
                'email'=>$data[2], 
                'phone_no'=>$data[1],
                // 'gender'=>$data[3], 
                'dob'=>'',
                'address1'=>$data[3],
                'address2'=>$data[4],
                'pincode'=>$data[5],
                // 'nominee_name'=>$data[8],
                // 'nominee_age'=>$data[9], 
                // 'nominee_relation'=>$data[10],
                'invoice_date'=>$invoice_date, 
                'engine_no'=>$data[7], 
                'chassis_no'=>$data[8],
                'vehicle_registration_no'=>$data[9],
                'dealer_name'=>$data[10],
                'dealer_code'=>$data[11], 
                'model'=>$data[12],
                'created_date' => date('Y-m-d H:i:s')
              );
               //echo "<pre>"; print_r($rr310vehicle_customer_data); echo "</pre>"; die('end of line yoyo');exit;
              
                      $is_exist = $this->db->query("SELECT * FROM rr310_script_data WHERE engine_no='".$data[7]."' OR chassis_no='".$data[8]."'")->row_array();
                      if(!$is_exist){
                        $insert_res=$this->Home_Model->insertIntoTable("rr310_script_data",$rr310vehicle_customer_data);
                        // echo $this->db->last_query();die;
                        $this->session->set_flashdata('success_message', 'Vehicle Customer data updated successfully');
                      }
                      else{
                        print_r($is_exist);die('is_exist');
                        $rr310vehicle_customer_data['status'] ='duplicate';
                        $insert_res=$this->Home_Model->insertIntoTable("rr310_script_data_copy",$rr310vehicle_customer_data);
                        // echo $this->db->last_query();die;
                        $this->session->set_flashdata('success_message', 'Engine No Or Chassiss No is duplicate');
                      }
               
            }
        }
        fclose($handle);

        
        
          
          
        redirect('RR310Import');
      }
    }
  }



function ValidateRegistrationNo($reg_no){
    if(strlen($reg_no)==10){
        $reg1 = ctype_alpha(substr($reg_no, 0,2) )?substr($reg_no, 0,2):'';
        $reg2 = is_numeric(substr($reg_no, 2,2) ) ? substr($reg_no, 2,2):'';
        $reg3 = ctype_alpha(substr($reg_no, 4,2) ) ? substr($reg_no, 4,2):'';
        $reg4 = is_numeric(substr($reg_no, 6,4) ) ? substr($reg_no, 6,4):'';
        $final_reg_no = ( (!empty($reg1) && !empty($reg2) && !empty($reg3) && !empty($reg4) ) && (strlen($reg4)==4) )?strtoupper($reg1.'-'.$reg2.'-'.$reg3.'-'.$reg4):'';
        
    }elseif(strlen($reg_no)==11){
        $reg1 = ctype_alpha(substr($reg_no, 0,2) )?substr($reg_no, 0,2):'';
        $reg2 = is_numeric(substr($reg_no, 2,2) ) ? substr($reg_no, 2,2):'';
        $reg3 = ctype_alpha(substr($reg_no, 4,3) ) ? substr($reg_no, 4,3):'';
        $reg4 = is_numeric(substr($reg_no, 7,4) ) ? substr($reg_no, 7,4):'';
        $final_reg_no = ( (!empty($reg1) && !empty($reg2) && !empty($reg3) && !empty($reg4) ) && (strlen($reg4)==4) )?strtoupper($reg1.'-'.$reg2.'-'.$reg3.'-'.$reg4):'';
        
    }elseif(strlen($reg_no)==4){
        $reg1 = ctype_alpha(substr($reg_no, 0,2) )?substr($reg_no, 0,2):'';
        $reg2 = is_numeric(substr($reg_no, 2,2) ) ? substr($reg_no, 2,2):''; 
        $final_reg_no = (!empty($reg1) && !empty($reg2))?strtoupper($reg1.'-'.$reg2):'';
        // echo '<br>'; echo $final_reg_no;
        // echo '<br>';die('in');
    }
    elseif(strlen($reg_no)==9){
        $reg1 = ctype_alpha(substr($reg_no, 0,2) )?substr($reg_no, 0,2):'';
        $reg2 = is_numeric(substr($reg_no, 2,2) ) ? substr($reg_no, 2,2):'';
        $reg3 = ctype_alpha(substr($reg_no, 4,1) ) ? substr($reg_no, 4,1):'';
        $reg4 = is_numeric(substr($reg_no, 5,4) ) ? substr($reg_no, 5,4):'';
        $final_reg_no = ( (!empty($reg1) && !empty($reg2) && !empty($reg3) && !empty($reg4) ) && (strlen($reg4)==4) )?strtoupper($reg1.'-'.$reg2.'-'.$reg3.'-'.$reg4):'';
       
    }else{
        $final_reg_no = '';
    }
    return $final_reg_no;
}

 function rr310GeneratePolicyScript(){
      $rr310_data = $this->Home_Model->getDataFromTable('rr310_script_data');
    // echo "<pre>";  print_r($rr310_data);die('rr310_data');
      
      $test_data = array();
      $status='false';
      foreach ($rr310_data as $value) {
          $engine_no = $value['engine_no'];$chassis_no=$value['chassis_no'];
          $new_model_name  = preg_replace('/[^a-zA-Z0-9_ -]/s','',$value['model']);
          $model_id = $this->Home_Model->getMakeModelNameByName('model', strtolower(trim($new_model_name)));
          // echo $model_id;
          // echo $this->db->last_query();die;
          $check_exist_data = $this->db->query("SELECT * FROM tvs_sold_policies WHERE (engine_no='$engine_no' OR chassis_no='$chassis_no') AND plan_id IN (62,63,64) ")->row_array();
          $reg_no = trim($value['vehicle_registration_no']);$final_reg_no='';
          if(!empty($reg_no)){
              if( ctype_alnum($reg_no) && !ctype_alpha($reg_no) && ! is_numeric($reg_no) ){
                  $final_reg_no = $this->ValidateRegistrationNo($reg_no);
              }
          }
                     
     // echo "<pre>";   print_r($check_exist_data);die('check_exist_data');
          $dealer_code = trim($value['dealer_code']);
          $dealer_data = $this->db->query("SELECT id,sap_ad_code,rsa_ic_master_id FROM tvs_dealers WHERE sap_ad_code='$dealer_code'")->row_array();
        if(empty($check_exist_data) && !empty($dealer_data) ){
            $pincode_data = $this->Home_Model->fetchStateCity($value['pincode']);
            $created_date = date('Y-m-d H:i:s');
            $string_position = strpos($value['cust_name'], ' ');
            if($string_position=='') {
              $fname = $value['cust_name'];
              $lname = '';
            }
            else {
              $fname = substr($value['cust_name'], 0,$string_position);
              $lname = substr($value['cust_name'], $string_position+1);
            }            
            // $fname = trim($value['fname']);
            // $lname = trim($value['lname']);
            $dob=date('Y-m-d',strtotime($value['dob']));
            $insert_customer_detail = array(
                'fname' => ($fname)?$fname : '',
                'lname' => ($lname)?$lname : '',
                'email' => ($value['email'])? trim($value['email']) : '',
                'mobile_no' => ($value['phone_no'])? trim($value['phone_no']) : '',
                'gender' => ($value['gender'])? trim($value['gender']) : '',
                'dob' => ($dob)? trim($dob) : '',
                'addr1' => ($value['address1'])? trim($value['address1']) : '',
                'addr2' => ($value['address2'])? trim($value['address2']) : '',
                'state_name' => ($pincode_data['state_name'])? trim($pincode_data['state_name']) : '',
                'city_name' => ($pincode_data['district_name'])? trim($pincode_data['district_name']) : '',
                'state' => ($pincode_data['state_id'])? trim($pincode_data['state_id']) : '',
                'city' => ($pincode_data['city_id'])? trim($pincode_data['city_id']) : '',
                'pincode' => ($value['pincode'])? trim($value['pincode']) : '',
                'created_date' => $created_date
            );
// echo "<pre>"; print_r($insert_customer_detail); echo "</pre>"; die('foreach');
              $inserted_customer_detail = $this->db->insert('tvs_customer_details', $insert_customer_detail); 
              $customer_detail_last_id = $this->db->insert_id();
              // $customer_detail_last_id = 1;
               if($customer_detail_last_id) {
                
                    $where = array('id'=>62);
                    $plan_details  = $this->Home_Model->getRowDataFromTable('tvs_plans',$where);
                    $plan_details['plan_amount'] = 0;
                    $plan_details['gst_amount'] = 0;
                    $invoice_date = date("d-m-Y", strtotime($value['invoice_date']));
                    $result_sold = $this->Home_Model->GeneratePolicyNo($dealer_data['rsa_ic_master_id']);
                    $date_result = $this->StartEndDate($value['invoice_date']);
                    $effective_date = $date_result['effective_date'];
                    $end_date = $date_result['end_date'];
                    $transection_no = $this->Home_Model->getRandomNumber('16');
                    
                    $insert_data_sold = array(
                        'user_id' => $dealer_data['id'],
                        'employee_code' => $dealer_data['sap_ad_code'],
                        'plan_id' => $plan_details['id'],
                        'plan_name' => $plan_details['plan_name'],
                        'customer_id' => $customer_detail_last_id,
                        'sold_policy_no' => trim($result_sold['sold_policy_no']),
                        'sold_policy_date' => date('Y-m-d H:i:s'),
                        'sold_policy_effective_date' => $effective_date,
                        'sold_policy_end_date' => $end_date,
                        'sold_policy_price_with_tax' => ($plan_details['plan_amount']+$plan_details['gst_amount']),
                        'sold_policy_price_without_tax' => $plan_details['plan_amount'],
                        'sold_policy_tax' => '18%',
                        'sold_policy_igst' => '18',
                        'engine_no' => ($value['engine_no'])? strtoupper(trim($value['engine_no'])) : '',
                        'chassis_no' => ($value['chassis_no'])? strtoupper(trim($value['chassis_no'])) : '',
                        'make_id' => '11',
                        'make_name' => 'TVS',
                        'model_name' => ($new_model_name)? trim($new_model_name) : '',
                        'model_id' => ($model_id)? $model_id : '',
                        'vehicle_registration_no' => ($final_reg_no)?strtoupper($final_reg_no) : '',
                        'registration_date'=>$invoice_date,
                        'ic_id' => 0,
                        'mp_id' => 0,
                        'product_type' => 1,
                        'product_type_name' => 'Two Wheeler',
                        'created_date' => $created_date,
                        'policy_status_id' => 3,
                        'status' => 1,
                        'transection_no' => $transection_no,
                        'rsa_ic_id' => $dealer_data['rsa_ic_master_id'],
                        'dms_response' => 'API'
                        

                    );
 // echo "<pre>"; print_r($insert_data_sold); echo "</pre>"; die('foreach');
                    $inserted_id = $this->Home_Model->insertIntoTable('tvs_sold_policies',$insert_data_sold);
                    // $inserted_id=1;
                    // echo $this->db->last_query();die;
                    if(!empty($inserted_id)) {
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
                                      
                                          $data = array(
                                              'policy_no'=>$result_sold['sold_policy_no'],
                                              'policy_id'=>$inserted_id,
                                              'dealer_id'=> $dealer_data['id'],
                                              'transection_no' =>$transection_no,
                                              'transection_type'=> 'dr',
                                              'transection_amount'=>0,
                                              'transection_purpose'=>'Policy Created'
                                          );
                                          // echo '<pre>'; print_r($data);//die('hello');
                                          $this->Home_Model->insertIntoTable('dealer_transection_statement',$data);
                                          $data = array(
                                                'policy_no'=> $result_sold['sold_policy_no'],
                                                'policy_id'=>$inserted_id,
                                                'dealer_id'=> $dealer_data['id'],
                                                'transection_no' => $transection_no,
                                                'transection_type'=>'cr',
                                                'transection_amount'=>0,
                                                'transection_purpose'=>'Commission'
                                            );
                                          $this->Home_Model->insertIntoTable('dealer_transection_statement',$data);

                          }
                              $status='true';
                              $send = $this->Renewal_sendSms($value['phone_no'],$inserted_id);
                    }else{ 
                      // ***IF Data is not inserted into tvs_sold_policies table
                          $test_data['query'][] = $this->db->last_query(); 
                          $scriptdata = array(
                            'engine_no'=>($value['engine_no'])?$value['engine_no']:'',
                            'chassis_no'=>($value['chassis_no'])?$value['chassis_no']:'',
                            'manufacturer'=>($value['manufacturer'])?$value['manufacturer']:'',
                            'model'=>($value['model'])?$value['model']:'',
                            'vehcicle_registration_no'=>($value['vehcicle_registration_no'])?$value['vehcicle_registration_no']:'',
                            'invoice_date'=>($value['invoice_date'])?$value['invoice_date']:'',
                            'cust_name'=>($value['cust_name'])?$value['cust_name']:'',
                            'email'=>($value['email'])?$value['email']:'',
                            'gender'=>($value['gender'])?$value['gender']:'',
                            'address1'=>($value['address1'])?$value['address1']:'',
                            'address2'=>($value['address2'])?$value['address2']:'',
                            'pincode'=>($value['pincode'])?$value['pincode']:'',
                            'dealer_code'=>($value['dealer_code'])?$value['dealer_code']:'',
                            'phone_no'=>($value['phone_no'])?$value['phone_no']:'',
                            'invoice_date'=>($value['invoice_date'])?$value['invoice_date']:'',
                            'created_date' => date('Y-m-d H:i:s')
                          );
                          $insert = $this->Home_Model->insertIntoTable('rr310_script_data_copy',$scriptdata);
                          
                    }
                    // echo "<pre>"; print_r($insert_data_sold); echo "</pre>"; die('foreach');
                  }else{
                    // ***IF Data is not inserted into customer table
                      $test_data['cust_query'][] = $this->db->last_query(); 
                      $scriptdata = array(
                        'engine_no'=>($value['engine_no'])?$value['engine_no']:'',
                        'chassis_no'=>($value['chassis_no'])?$value['chassis_no']:'',
                        'manufacturer'=>($value['manufacturer'])?$value['manufacturer']:'',
                        'model'=>($value['model'])?$value['model']:'',
                        'vehcicle_registration_no'=>($value['vehcicle_registration_no'])?$value['vehcicle_registration_no']:'',
                        'invoice_date'=>($value['invoice_date'])?$value['invoice_date']:'',
                        'cust_name'=>($value['cust_name'])?$value['cust_name']:'',
                        'email'=>($value['email'])?$value['email']:'',
                        'gender'=>($value['gender'])?$value['gender']:'',
                        'address1'=>($value['address1'])?$value['address1']:'',
                        'address2'=>($value['address2'])?$value['address2']:'',
                        'pincode'=>($value['pincode'])?$value['pincode']:'',
                        'dealer_code'=>($value['dealer_code'])?$value['dealer_code']:'',
                        'phone_no'=>($value['phone_no'])?$value['phone_no']:'',
                        'invoice_date'=>($value['invoice_date'])?$value['invoice_date']:'',
                        'created_date' => date('Y-m-d H:i:s')
                      );
                      $insert = $this->Home_Model->insertIntoTable('rr310_script_data_copy',$scriptdata);
                         
                  }

          }
      }
      // END Foreach; 
      if(!empty($check_exist_data)){
          echo 'Already Exist Data : - <br>';echo "<pre>"; print_r($check_exist_data); echo "</pre>";        
          echo 'Not Inserted Data : - <br>';echo "<pre>"; print_r($test_data); echo "</pre>";        
      }
      echo $status;
  }
  
function StartEndDate($start_date){
    // $start_date = date('Y-m-d');
    $result['effective_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "0 day")) . "0 year")) .' '.date('H:i:s');
    $effective_date = $result['effective_date'];
    $result['end_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($effective_date . "-1 day")) . "1 year")) . ' 23:59:59';
    return $result ;
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

//     function Renewal_sendSms($Mobilenumber,$sold_policy_id) {
//      echo $Mobilenumber.'  '.$sold_policy_id;
//         $base_url = base_url().'download_rsa_pdf/'.$sold_policy_id;
//         // echo $base_url.'<br>';
//         $short_policy_url = $this->make_bitly_url($base_url,'json');
//         // echo $short_policy_url.'<br>';
//         $Message = "Welcome to my TVS RSA Limitless Assist to find your policy please click on given link ".$base_url;
//         // echo $Message,'<br>';
//         error_reporting(1);
//         $uid = "MPOLNW";
//         $pwd = 'Pwe$NiTm';
//         $sid = "TVSRSA";
//         $method = "GET";
//         $message = urlencode($Message);
        
//         $get_url = "http://123.108.46.12/API/WebSMS/Http/v1.0a/index.php?username=".$uid."&password=".$pwd."&sender=TVSRSA&to=".$Mobilenumber."&message=".$message."&reqid=1&format=text";

// // echo $get_url;die;
//             $ch = curl_init();
//             curl_setopt($ch, CURLOPT_URL, $get_url);
//             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//             curl_setopt($ch, CURLOPT_HEADER, false);
//             // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
//             $output = curl_exec($ch);
//             if($errno = curl_errno($ch)) {
//                 $error_message = curl_strerror($errno);
//                 echo "cURL error ({$errno}):\n {$error_message}";
//             }
//             curl_close($ch);
// //            echo "out=". $output; die;
//             if (curl_errno ( $ch )) {
//             echo curl_error ( $ch );
//             curl_close ( $ch );
//             exit ();
//         }
//        // echo "<pre>"; print_r($output); echo "</pre>"; die('end of line yoyo');

//         return $output;
//     }
    function Renewal_sendSms($Mobilenumber,$sold_policy_id) {

     // echo $Mobilenumber.'  '.$sold_policy_id;
        $base_url = base_url().'download_rsa_pdf/'.$sold_policy_id;
        // echo $base_url.'<br>';
        $uid = 'di78-indicosm';
        $pwd = 'digimile';
        $sid = 'TVSRSA';
        $method = 'POST';
        $is_errror = 1;
        $Message = "Welcome to my TVS RSA Limitless Assist to find your policy please click on given link ".$base_url;
       // echo $Message,'<br>';
        $message_string = urlencode($Message);
        // k3digital media
        $get_url='http://sms.digimiles.in/bulksms/bulksms?username='.$uid.'&password='.$pwd.'&type=0&dlr=1&destination='.$Mobilenumber.'&source='.$sid.'&message='.$message_string.'';


// echo $get_url;die;
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
            if (curl_errno ( $ch )) {
            echo curl_error ( $ch );
            curl_close ( $ch );
            exit ();
        }
       // echo "<pre>"; print_r($output); echo "</pre>"; die('end of line yoyo');

        return $output;
    }
    
}