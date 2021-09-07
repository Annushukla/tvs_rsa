<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

Class Rest_api extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_Model');
        $this->load->model('Home_Model');
         $this->load->helper("common_helper");
    }

    function api_response($data, $http_status = REST_Controller::HTTP_OK){
        //header('Access-Control-Allow-Origin: *');
        $this->response($data, $http_status);
    }
/************************START OF RSA FRONT APIS****************************/
    // function set_transection_detail_post(){
    //   $headers = apache_request_headers();
    //   // echo '<pre>'; print_r($headers);//die('here');
    //   $x_auth_tocken = $headers['X-AUTH-TOKEN'];
    //   $raw_input_stream = $this->input->raw_input_stream;
    //   $input_data = json_decode($raw_input_stream, true);
    //   // echo "<pre>"; print_r($input_data); echo "</pre>"; die('end of line yoyo');
    //   $response_data['SuccessANDRejected'] = 'Invalid user name or password';
    //   $response_data['CODE'] = 02;
    //   if($x_auth_tocken == md5($input_data['VirtualACCode'])){
    //    if($input_data){
    //     //approveDealer
    //       $dealer_code = str_replace('ICPL000', "", strtoupper($input_data['VirtualACCode']));          
    //       $where = array(
    //         'dealer_code'=>$dealer_code
    //       );
    //       $is_dealer_exist = $this->Home_Model->getRowDataFromTable('biz_users',$where);          
    //       if(!empty($is_dealer_exist)){
    //         $where = array(
    //           'UTR'=>$input_data['UTR']
    //         );
    //         $transaction_exist = $this->Home_Model->getRowDataFromTable('tvs_icici_bank_payment_response',$where);
    //         $transaction_exist = '';
    //         // echo "<pre>"; print_r($transaction_exist); echo "</pre>"; die('end of line yoyo');
    //         if(!empty($transaction_exist)){
    //           $response_data['SuccessANDRejected'] = 'Duplicate UTR/ Transaction No';
    //           $response_data['CODE'] = '06';
              
    //         }else{
    //           $is_inserted = $this->Home_Model->insertIntoTable('tvs_icici_bank_payment_response',$input_data);
    //           if($is_inserted){
    //             $input_data['dealer_code'] = $dealer_code;
    //             $update_status_data  = $this->updateDealerPayment($input_data);
    //           }else{
    //             $response_data['SuccessANDRejected'] = 'Rejected Transaction';
    //             $response_data['CODE'] = '12';
    //           }
    //         }
    //        }else{
    //           $response_data['SuccessANDRejected'] = 'Invalid user name or password';
    //           $response_data['CODE'] = '02';
    //        }

    //         // $output_data = [
    //         //   'status' => TRUE,
    //         //   'message' => 'Successful Transaction',
    //         //   'result' => $result
                  
    //         // ];
    //       }
    //     }else{
    //       $response_data['SuccessANDRejected'] = 'Not a Valid Method';
    //           $response_data['CODE'] = '03';
    //       // $output_data = [
    //       //   'status' => FALSE,
    //       //   'message' => 'Rejected Transaction',
    //       //   'result' => ''
    //       // ];
    //     }
    //     $result = [
    //             'CustomerCode'=>$input_data['CustomerCode'],
    //             'VirtualACCode'=>$input_data['VirtualACCode'],
    //             'UTR'=>$input_data['UTR'],
    //             'CustomerAccountNo'=>$input_data['CustomerAccountNo'],
    //             'AMT'=>$input_data['AMT'],
    //             'PayeeName'=>$input_data['PayeeName'],
    //             'PayeeAccountNumber'=>$input_data['PayeeAccountNumber'],
    //             'PayeeBankIFSC'=>$input_data['PayeeBankIFSC'],
    //             'PayeePaymentDate'=>$input_data['PayeePaymentDate'],
    //             'BankInternalTransactionNumber'=>$input_data['BankInternalTransactionNumber'],
    //             'MODE'=>$input_data['MODE'],
    //             'SuccessANDRejected'=>$response_data['SuccessANDRejected'],
    //             'CODE'=>$response_data['CODE']
    //         ];


    //   //$post_data = $this->input->post();
      
      
    //   $this->api_response($result);
    // }

    function set_transection_detail_post(){
        // echo '<pre>'; print_r($_SERVER);//die('ads');
      // $headers = apache_request_headers();
      $x_auth_tocken = ($_SERVER['HTTP_X_AUTH_TOKEN'])? $_SERVER['HTTP_X_AUTH_TOKEN'] : $_SERVER['HTTP_X_AUTH_TOKEN'];
      $raw_input_stream = $this->input->raw_input_stream;
      $input_data = json_decode($raw_input_stream, true);
      // echo '<pre>'; print_r(md5($input_data['VirtualACCode']));die;
      $response_data['SuccessANDRejected'] = '';
      $response_data['CODE'] = '';     
      if(strtoupper($x_auth_tocken) == strtoupper(md5($input_data['VirtualACCode'])) )
      {
        if($input_data){
        //approveDealer
          // echo "sneh11";die();
          $dealer_code = str_replace('ICCP000', "", strtoupper($input_data['VirtualACCode']));
          $where = array(
            'dealer_code'=>$dealer_code
          );
          $is_dealer_exist = $this->Home_Model->getRowDataFromTable('biz_users',$where);
          //print_r($is_dealer_exist);die("==sneh==");
           if($is_dealer_exist){
            $where = array(
              'UTR'=>$input_data['UTR']
            );
            $transaction_exist = $this->Home_Model->getRowDataFromTable('tvs_icici_bank_payment_response',$where);
            //print_r($transaction_exist);die("==sneh==");
            if($transaction_exist){
              $response_data['SuccessANDRejected'] = 'Duplicate UTR/ Transaction No';
              $response_data['CODE'] = 06;
            }else if($input_data){
              //die('hello');
              $is_inserted = $this->Home_Model->insertIntoTable('tvs_icici_bank_payment_response',$input_data);
              if($is_inserted){

                $input_data['transection_inserted_id'] = $is_inserted;
                $input_data['dealer_code'] = $dealer_code;
                $update_status_data  = $this->updateDealerPayment($input_data);
                //die("sneh"); 
                $response_data['SuccessANDRejected'] = 'Successful Transaction';
                $response_data['CODE'] = 11;
              }else{
                  $response_data['SuccessANDRejected'] = 'Rejected Transaction';
                  $response_data['CODE'] = 12;
              }
             }
          }else{
              $response_data['SuccessANDRejected'] = 'Invalid user name or password';
              $response_data['CODE'] = 02;
          }
       }
       else{
          $response_data['SuccessANDRejected'] = 'Not a Valid Method';
          $response_data['CODE'] = 03;
       }
      }else{
          $response_data['SuccessANDRejected'] = 'Invalid user name or password';
          $response_data['CODE'] = 02;
      }
      $result = [
                'CustomerCode'=>$input_data['CustomerCode'],
                'VirtualACCode'=>$input_data['VirtualACCode'],
                'UTR'=>$input_data['UTR'],
                'CustomerAccountNo'=>$input_data['CustomerAccountNo'],
                'AMT'=>$input_data['AMT'],
                'PayeeName'=>$input_data['PayeeName'],
                'PayeeAccountNumber'=>$input_data['PayeeAccountNumber'],
                'PayeeBankIFSC'=>$input_data['PayeeBankIFSC'],
                'PayeePaymentDate'=>$input_data['PayeePaymentDate'],
                'BankInternalTransactionNumber'=>$input_data['BankInternalTransactionNumber'],
                'MODE'=>$input_data['MODE'],
                'SuccessANDRejected'=>$response_data['SuccessANDRejected'],
                'CODE'=>$response_data['CODE']
            ];

      //$post_data = $this->input->post();
     // die('hello amit1');
      
      $this->api_response($result);
    }
    public function updateDealerPayment($input_data) {
        $return_data = array();
        $post_data = $input_data;
        // echo '<pre>'; print_r($post_data);die('hello moto');
        $dealer_bank_tran_id = $post_data['UTR'];
        $dealer_details = $this->Home_Model->getRowDataFromTable('tvs_dealers',array('sap_ad_code'=>$post_data['dealer_code']));
        if(empty($dealer_details)){
          $this->db->where('id',$input_data['transection_inserted_id']);
          $this->db->update('tvs_icici_bank_payment_response',array('update_msg'=>'dealer not exist'));
        }
        $dealer_id = $dealer_details['id'];
        $post_data['dealer_id'] = $dealer_id;
        $amount = $post_data['AMT'];
        $transection_type = 'deposit';
         //echo '<pre>'; print_r($post_data);die('hello moto');
        $is_request_exist = $this->Home_Model->cheeckIsBankPaymentApproved($post_data);
        // echo '<pre>'; print_r($is_request_exist);die('hello moto');
        if(!empty($is_request_exist)){
          if($is_request_exist['approval_status'] == 'approved'){
            $return_data['status'] = false;
            $return_data['msg'] = 'Already Approved';
            $return_data['update_status_data'] = array();
          }else if($is_request_exist['approval_status'] == 'pending'){
             $update_status_data = array(
                    'is_online_also' => 1
                    );
                
                $where = array('id'=>$is_request_exist['id']);
                $result = $this->Home_Model->updateTable('dealer_bank_transaction', $update_status_data, $where);
                $return_data['status'] == ($result == true)?'true':'false';

            // $return_data['update_status_data'] = $update_status_data = $this->updatePaymentStatus($post_data);
          }
          $this->db->where('id',$input_data['transection_inserted_id']);
          $this->db->update('tvs_icici_bank_payment_response',array('update_msg'=>'Request Already Exist.'));
        }else{
          $old_date = $post_data['PayeePaymentDate'];
          $new_date=substr_replace(substr_replace($old_date,"-",4,0),"-",7,0);
          $insert_data = array(
            'dealer_id'=>$post_data['dealer_id'],
            'bank_account_no'=>$post_data['PayeeAccountNumber'],
            'bank_ifsc_code'=>$post_data['PayeeBankIFSC'],
            'acc_holder_name'=>$post_data['PayeeName'],
            'bank_transaction_no'=>$post_data['UTR'],
            'deposit_amount'=>$post_data['AMT'],
            'transaction_type'=>'deposit',
            'is_online'=>1,
            'transaction_date'=>$new_date
          );
          $is_inserted = $this->Home_Model->insertIntoTable('dealer_bank_transaction',$insert_data);
          if($is_inserted){
          //  $return_data['update_status_data'] = $update_status_data = $this->updatePaymentStatus($post_data);
            $this->db->where('id',$input_data['transection_inserted_id']);
            $this->db->update('tvs_icici_bank_payment_response',array('update_msg'=>'Request Is Generated.'));
          }
        } 
        return $return_data;
      }
      
function updatePaymentStatus($post_data){
            $where = array('dealer_id' =>$post_data['dealer_id']);
            $transaction_no = $this->getRandomNumber('16');
            $is_wallet_exist = $this->Home_Model->getRowDataFromTable('dealer_wallet', $where);
            if(!empty($is_wallet_exist)){
                        $credit_amount = $is_wallet_exist['credit_amount'];
                        if(round($credit_amount) > round($amount)){
                            $credit_amount = ($credit_amount - $amount);
                            $data = array('credit_amount'=> $credit_amount);
                        }else{
                            $secu_amount = ($is_wallet_exist['security_amount'] - $is_wallet_exist['credit_amount']);
                            $secu_amount = ($secu_amount +  $amount);
                            $data = array(
                                'credit_amount'=> 0,
                                'security_amount'=> $secu_amount
                            );
                        }

                        $where = array('dealer_id'=>$dealer_id);
                        $status = $this->Home_Model->updateTable('dealer_wallet',$data,$where);
                        $return_data['status'] = ($status == true)?'true':'false';
                        $return_data['msg'] = ($status == true)?'success':'failed';
                    
            }else{ 
                   $data = array(
                    'dealer_id'=>$dealer_id,
                    'security_amount'=>$amount,
                   ); 
                   $is_inserted = $this->Home_Model->insertIntoTable('dealer_wallet',$data);
                   $return_data['status'] = !empty($is_inserted)?'true':'false';
                   $return_data['msg'] = !empty($is_inserted)?'success':'failed';
               
            }
            if($return_data['status'] == 'true'){
                    $update_status_data = array(
                    'approval_status' => 'approved',
                    'transection_no' => $transaction_no
                    );
                
                $where = array('id'=>$dealer_bank_tran_id);
                $result = $this->Home_Model->updateTable('dealer_bank_transaction', $update_status_data, $where);
                $return_data['status'] == ($result == true)?'true':'false';

                if($result){
                    $data = array(
                        'dealer_id'=> $dealer_id,
                        'transection_no' => $transaction_no,
                        'transection_type'=>'cr',
                        'transection_amount'=>$amount,
                        'transection_purpose'=>'Wallet Credit'
                    );
                    $this->Home_Model->insertIntoTable('dealer_transection_statement',$data);
                   $where = array('dealer_id'=>$dealer_id);
                    $total_tran_record = $this->Home_Model->getRowDataFromTable('dealer_total_transaction_record', $where);
                    if(!empty($total_tran_record)){
                        if($transection_type == 'withdrawal'){
                                $exist_withdrowal_record = $total_tran_record['total_withdrowal_amount'];
                                $final_withdrowal_record = $exist_withdrowal_record + $amount;
                                $insert_data = array(
                                    'total_withdrowal_amount'=>$final_withdrowal_record
                                );
                          }else{
                               $exist_deposit_record = $total_tran_record['total_deposit_amount'];
                               $final_deposit_record = $exist_deposit_record + $amount;
                               $insert_data = array(
                                          'total_deposit_amount'=>$final_deposit_record
                                      );
                          }
                          $status = $this->Home_Model->updateTable('dealer_total_transaction_record',$insert_data,$where);
                    }else{
                        $insert_data = array(
                                    'dealer_id'=>$dealer_id,
                                    'total_deposit_amount'=>$amount
                                );
                        $status = $this->Home_Model->insertIntoTable('dealer_total_transaction_record',$insert_data);
                    }
                    $domainName = $_SERVER['HTTP_HOST'];
                        // if ($domainName != 'localhost') {
                        //     $mail_response = $this->PaymentMail($dealer_bank_tran_id);
                        // }
                }
                $return_data['msg'] == ($result == true)?'status updated':'status not updated';
            }
            return $return_data;
        }

function getRandomNumber($len){
            $better_token = $code=sprintf("%0".$len."d", mt_rand(1, str_pad("", $len,"9")));
            return $better_token;
        }


}

          