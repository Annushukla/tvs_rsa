<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dealer_Approve extends CI_Controller {

    public function __construct() {
        Parent::__construct();
        $this->load->model('Home_Model');
        $this->load->library('database_library');
        $this->load->helper('csv');
        if (!empty($this->session->userdata('admin_session'))) {
            return true;
        } else {
            redirect('admin');
        }
    }
    public function approvedDealer($status=null) {
        $data['status'] = $status ;
        $data['main_contain'] = 'admin/dealer_approve/dealer_policies';
        $this->load->view('admin/includes/template', $data);
        
    }



    public function admin_dealer_password_changes() {
       $admin_session =  $this->session->userdata('admin_session');
       if($admin_session['logged_in'] == 1 && $admin_session['admin_role_id'] == 2){
        $data['main_contain'] = 'admin/dealer_approve/dealer_policies';
        $this->load->view('admin/includes/template', $data);
        }else{
            redirect('admin');
        }
    }

 
    

     public function approveDealerAjax() {
        $requestData = $_REQUEST;
         $session_data = $this->session->userdata('admin_session');
        $trans_start_date = $requestData['trans_start_date'];
        $trans_end_date = $requestData['trans_end_date'];
        $approval_status_val = ($requestData['approval_status_val']) ? $requestData['approval_status_val'] : 'pending' ;
        if($approval_status_val=='reconcile_approved'){
            $status_filter = "AND dbt.`reconcile_status` = 'approved' " ;
        }else{
            $status_filter = "AND dbt.`approval_status` = '$approval_status_val' AND dbt.`reconcile_status` = 'pending' " ;
        }

        if($approval_status_val=='all'){
            $status_filter = '' ;
        }
        $post_dealer_code = ($requestData['dealer_code']) ? $requestData['dealer_code'] : '';
        $filterd_dealer_code = "" ;
        if(!empty($post_dealer_code)){
            $filterd_dealer_code =" AND td.`dealer_code` = '$post_dealer_code' ";
        }

        $where = '';
        if($trans_end_date!='' && $trans_start_date!=''){
            $where = "AND DATE(created_date) BETWEEN '$trans_start_date' AND '$trans_end_date'";
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
       
        $sql = "SELECT dbt.*, td.`dealer_code`,td.`sap_ad_code`,td.`location`,td.`state`,td.`dealer_name` FROM dealer_bank_transaction AS dbt LEFT JOIN tvs_dealers td ON td.`id` = dbt.`dealer_id` WHERE dbt.dealer_id != 0 AND dbt.dealer_id != 0 $status_filter $filterd_dealer_code $where ";
        // echo '<pre>'; print_r($sql);die('hello');
        $totalFiltered = $totalData;
        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY dbt.id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();

        $data = array();
        $i = 1;
        foreach ($result as $main) {
            $created_date = explode(' ', $main->created_date);
            $created_date = $created_date[0];
            
            $app_selected = '';
            $pendin_selected=''; 
            $rej_selected = ''; 
            $reffer_selected='';
            $disabled ='';
            $reconcile = '';  
            if($main->approval_status=='approved'){
                $app_selected = 'selected';
                $disabled =  "disabled";
                
            }
            if($main->approval_status=='pending'){
                $pendin_selected = 'selected';
            }
            if($main->approval_status=='rejected'){
                $rej_selected = 'selected';
               $disabled  = "disabled";
            }
            if($main->approval_status=='referback'){
                $reffer_selected = 'selected';
            }
            if($session_data['admin_role_id']!=1 && $session_data['admin_role']!='admin_master'){
                $disabled =  "disabled";
            }
            if($main->approval_status=='approved' && $main->reconcile_status=='pending'){
                $reconcile = '<button class="btn btn-info" onclick="Reconcile('.$main->id.')"><i class="fa fa-edit"></i></button>';
            }
            $status_html = '<select class="form-control approval_status" name="approval_status" data-transactn_id="'.$main->id.'" data-selected_app_status="'.$main->approval_status.'" data-dealer_id="'.$main->dealer_id.'" data-amount="'.$main->deposit_amount.'" data-transaction_type="'.$main->transaction_type.'"  '.$disabled.'>
                            <option value="pending" '.$pendin_selected.'>Pending</option>
                            <option value="approved" '.$app_selected.'>Approve</option>
                            <option value="rejected" '.$rej_selected.'>Reject</option>
                            <option value="referback" '.$reffer_selected.'>Referback</option>
                            </select>';
            if(in_array($main->approval_status, array("approved","rejected")) || ($session_data['admin_role_id']!=1 && $session_data['admin_role']!='admin_master') ){
                 $edit_btn = '';
             }else{
                $edit_btn = '<button class="btn btn-warning" onclick="UpdateTransaction_data('.$main->id.')"><i class="fa fa-edit"></i></button>';
             }

            $transaction_date = $main->transaction_date;
            $transaction_date = date("M-d-Y", strtotime($transaction_date));

            $row = array();
            $row[] = $i++;
            $row[] = $main->dealer_name;
            $row[] = $main->sap_ad_code;
            $row[] = $main->bank_transaction_no;
            if($approval_status_val=='reconcile_approved'){
            $row[] = $main->reconcile_no;
            }
            $row[] = $main->deposit_amount;
            $row[] = $main->bank_name;
            $row[] = $main->transaction_type;
            $row[] = $transaction_date;
            $row[] = $main->state;
            $row[] = $main->location;
            $row[] = $main->created_date;
            $row[] = $main->updated_at;
            $row[] = $main->approval_status;
            $row[] = $main->approved_date;
            $row[] = ($main->is_online == 1)?'<span style = "color:red;background-color: black;">Online</span>':'<span>Offline</span>';
            $file_list = $status_html;
            $row[] = $file_list.' '.$edit_btn.$reconcile;
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


      public function updateTransactionStatus(){
        $update_status_data = array(
            'approval_status' => $this->input->post('hid_status'),
            'comment' => $this->input->post('comment'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        $post_status = $this->input->post('hid_status');
        $where = array('id'=>$this->input->post('dealer_bank_tran_id'));
        $result= $this->Home_Model->updateTable('dealer_bank_transaction', $update_status_data, $where);
        if($result){
            redirect('admin/admin_dealer_approve');
        }

    }

    public function addGstToDealers(){
            $post_data = $this->input->post();
            // echo '<pre>'; print_r($post_data);
             $return_data = array();
            $where = array(
                'id'=>$post_data['gst_id']
            );
            $gst_details = $this->Home_Model->getRowDataFromTable('tvs_dealers_gst_status',$where);

            if($gst_details['approval_status'] == 'pending' || $gst_details['approval_status'] == 'referback'){
            $where = array(
                'dealer_id'=>$post_data['dealer_id']
            );
           $dealer_wallet_details =  $this->Home_Model->getRowDataFromTable('dealer_wallet',$where);
           if(!empty($dealer_wallet_details['dealer_id'])){
                $credit_amount = $dealer_wallet_details['credit_amount'];
                $security_amount = $dealer_wallet_details['security_amount'];
                $total_deposit_amount = $gst_details['gst_amount'];
                if($credit_amount > 0 && ($credit_amount > $total_deposit_amount)){
                    $credit_amount = ($credit_amount - $total_deposit_amount);
                    $update_data = array(
                            'credit_amount'=>$credit_amount
                        );
                }else{
                    $security_amount = $security_amount +$total_deposit_amount;
                    $update_data = array(
                            'security_amount'=>$security_amount
                        );
                }
                $is_updated = $this->Home_Model->updateTable('dealer_wallet',$update_data,$where);
                // $is_updated = 1;
                if($is_updated){
                    $where = array(
                            'dealer_id'=>$post_data['dealer_id'],
                            'invoice_id'=>$gst_details['invoice_id'],
                            'transection_purpose'=>'GST Credit'
                    );
                    $is_transection_exist = $this->Home_Model->getRowDataFromTable('dealer_transection_statement',$where);
                if(empty($is_transection_exist)){
                    $transaction_no = $this->getRandomNumber('16');
                    $insert_data = array(
                        'dealer_id'=>$post_data['dealer_id'],
                        'invoice_id'=>$gst_details['invoice_id'],
                        'transection_no'=>$transaction_no,
                        'transection_type'=>'cr',
                        'transection_amount'=>$gst_details['gst_amount'],
                        'transection_purpose'=>'GST Credit'
                        );
                    $is_inserted = $this->Home_Model->insertIntoTable('dealer_transection_statement',$insert_data);
                    if($is_inserted){
                        $where = array(
                            'id'=>$post_data['gst_id']
                        );
                        $data = array('approval_status'=>'approved');
                        $is_approved = $this->Home_Model->updateTable('tvs_dealers_gst_status',$data,$where);
                        $return_data['status'] = 'false';
                        $return_data['msg'] = 'Error In GST Approve.';
                        if($is_approved){
                            $return_data['status'] = 'true';
                            $return_data['msg'] = 'GST Approve Success.';
                        }
                    }else{
                        $return_data['status'] = 'false';
                        $return_data['msg'] = 'Error In Balance Updation.';
                    }
                }else{
                    $return_data['status'] = 'true';
                    $return_data['msg'] = 'Transaction Data Already exist.';
                }
                }else{
                    $return_data['status'] = 'false';
                    $return_data['msg'] = 'Error In Balance Updation.';
                }
           }else{
                $return_data['status'] = 'false';
                $return_data['msg'] = 'Error In Balance Updation.';
           }
       }else{
            $return_data['status'] = 'false';
            $return_data['msg'] = 'GST Already Approved.';
       }
       echo json_encode($return_data);
    }


    public function approveDealer() {
        $return_data = array();
        $post_data = $this->input->post();
        //echo '<pre>'; print_r($post_data);exit;
        $dealer_bank_tran_id = $post_data['dealer_bank_tran_id'];
        $dealer_id = $post_data['dealer_id'];
        $amount = $post_data['amount'];
        $transection_type = $post_data['transaction_type'];
        $is_approved = $this->Home_Model->cheeckIsStatusApproved($post_data);
        if(empty($is_approved)){
        $where = array('dealer_id' =>$dealer_id);
        $transaction_no = $this->getRandomNumber('16');
        $is_wallet_exist = $this->Home_Model->getRowDataFromTable('dealer_wallet', $where);
         //echo '<pre>'; print_r( $is_wallet_exist);die('here');
        if(!empty($is_wallet_exist)){
                if($post_data['transaction_type'] == 'withdrawal'){
                        $wallet_amount = ($is_wallet_exist['security_amount'] - $is_wallet_exist['credit_amount']);
                        $is_withdrawal_allowed = ($wallet_amount >= $post_data['amount'])?'yes':'no';
                         //echo $wallet_amount.'--wallet_amount-- '.$post_data['amount'].' -amount--  '.$is_withdrawal_allowed;die('wall');
                        if($is_withdrawal_allowed == 'yes'){
                            // $credit_amount = ($is_wallet_exist['credit_amount'] + $amount);
                            // $data = array('credit_amount'=>$credit_amount);
                            // $where = array('dealer_id'=>$dealer_id);
                            // $status = $this->Home_Model->updateTable('dealer_wallet',$data,$where);
                           //print $this->db->last_query();exit;
                            // $return_data['status'] = ($status == true)?'true':'false';
                            $return_data['status'] = 'false';
                            $return_data['msg'] = 'Contact To Accountant';
                        }else{
                            $return_data['status'] = 'false';
                            $return_data['msg'] = 'insufficient balance';
                        }
                    }else{

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
                    }
            }else{
              if($post_data['transaction_type'] != 'withdrawal'){  
                   $data = array(
                        'dealer_id'=>$dealer_id,
                        'security_amount'=>$amount,
                   ); 
                   $is_inserted = $this->Home_Model->insertIntoTable('dealer_wallet',$data);
                   $return_data['status'] = !empty($is_inserted)?'true':'false';
                   $return_data['msg'] = !empty($is_inserted)?'success':'failed';
               }else{
                    $return_data['status'] = 'false';
                   $return_data['msg'] = 'failed';
               }
            }
            if($return_data['status'] == 'true'){

                // if($post_data['transaction_type'] == 'withdrawal'){
                //     $update_status_data = array(
                //     'approval_status' => 'approved',
                //     'approved_date' => date('Y-m-d'),
                //     'bank_transaction_no' => $post_data['admin_trans_no'],
                //     'transection_no' => $transaction_no
                // );
                // }else{
                    $update_status_data = array(
                    'approval_status' => 'approved',
                    'transection_no' => $transaction_no
                    );
                // }
                $where = array('id'=>$dealer_bank_tran_id);
                $result = $this->Home_Model->updateTable('dealer_bank_transaction', $update_status_data, $where);
                //print $this->db->last_query();exit;
                $return_data['status'] == ($result == true)?'true':'false';

                if($result){
                    $data = array(
                        'dealer_id'=> $dealer_id,
                        'transection_no' => $transaction_no,
                        'transection_type'=>($transection_type == 'withdrawal')?'dr':'cr',
                        'transection_amount'=>$amount,
                        'transection_purpose'=>($transection_type == 'withdrawal')?'Wallet Debit':'Wallet Credit',
                    );
                    $this->Home_Model->insertIntoTable('dealer_transection_statement',$data);

                   $where = array('dealer_id'=>$dealer_id);
                    $total_tran_record = $this->Home_Model->getRowDataFromTable('dealer_total_transaction_record', $where);

                    //print $this->db->last_query();exit;

                    if(!empty($total_tran_record)){
                    //     if($transection_type == 'withdrawal'){
                    //             $exist_withdrowal_record = $total_tran_record['total_withdrowal_amount'];
                    //             $final_withdrowal_record = $exist_withdrowal_record + $amount;
                    //             $insert_data = array(
                    //                 'total_withdrowal_amount'=>$final_withdrowal_record
                    //             );

                    // }else{
                         $exist_deposit_record = $total_tran_record['total_deposit_amount'];
                         $final_deposit_record = $exist_deposit_record + $amount;
                         $insert_data = array(
                                    'total_deposit_amount'=>$final_deposit_record
                                );
                    // }
                        $status = $this->Home_Model->updateTable('dealer_total_transaction_record',$insert_data,$where);

                        //print $this->db->last_query();exit;
                    }else{
                    //     if($transection_type == 'withdrawal'){
                    //             $insert_data = array(
                    //                 'dealer_id'=>$dealer_id,
                    //                 'total_withdrowal_amount'=>$amount
                    //             );

                    // }else{
                        $insert_data = array(
                                    'dealer_id'=>$dealer_id,
                                    'total_deposit_amount'=>$amount
                                );
                    // }
                        $status = $this->Home_Model->insertIntoTable('dealer_total_transaction_record',$insert_data);
                    }
                    $domainName = $_SERVER['HTTP_HOST'];
                        if ($domainName != 'localhost') {
                            $mail_response = $this->PaymentMail($dealer_bank_tran_id);
                        }

                    // if($post_data['transaction_type'] == 'withdrawal'){
                    //      $sms_response = $this->PaymentSms($dealer_bank_tran_id);
                    // }

                }
                $return_data['msg'] == ($result == true)?'status updated':'status not updated';
            }
            }else{
            $return_data['status'] = 'false';
            $return_data['msg'] = 'Already Approved.';
        }
        echo json_encode($return_data);
}

public function PaymentMail($dealer_bank_tran_id){
    $trans_data = $this->Home_Model->getDealerBankTransaction($dealer_bank_tran_id);
    $transaction_type = $trans_data['transaction_type'];
    $transaction_date = $trans_data['transaction_date'];
    $deposit_amount = $trans_data['deposit_amount'];
    $bank_transaction_no = $trans_data['bank_transaction_no'];
    $dealer_name = $trans_data['dealer_name'];
    $sap_ad_code = $trans_data['sap_ad_code'];
    $bank_name = $trans_data['bank_name'];
    $from = 'info@indicosmic.com';
    $email = $trans_data['email'];
    $this->load->library('email');
    $this->email->from($from, "TVS-RSA");
    $this->email->to($email);
    $this->email->bcc('info@indicosmic.com');
    $this->email->cc('amitdeep@indicosmic.com');

        $msg = "Dear MR. $dealer_name,
        Your payment request is approved .
        Warm Regards,

        Team ICPL";
 
        $this->email->subject('TVS RSA Payment Request id Approved');
        $this->email->message($msg);
        if ($this->email->send()) {
            $result['status'] = true;
        } else {
            $to = $email;
            mail($to, 'test', 'Other sent option failed');
            echo $email;
            show_error($this->email->print_debugger());
            $result['status'] = false;
        }
        return $result;
}

public function PaymentSms($dealer_bank_tran_id){
    $this->load->helper('common_helper');
    $trans_data = $this->Home_Model->getDealerBankTransaction($dealer_bank_tran_id);
    $dealer_name = $trans_data['dealer_name'];
    //$mobile_no = $trans_data['mobile'];
    $mobile_no = '8291027664';

    $message = "Dear MR. $dealer_name,
        Your payment request is approved .
        Warm Regards,

        Team ICPL";

    //$output = sendTVSRSAsms($mobile_no,$message);
}

function getRandomNumber($len)
        {
            $better_token = $code=sprintf("%0".$len."d", mt_rand(1, str_pad("", $len,"9")));
            return $better_token;
        }
function GetTransaction_data(){
        $bank_id = $this->input->post('bank_id');
        $where = array('id' => $bank_id);
        $bank_data = $this->Home_Model->getRowDataFromTable('dealer_bank_transaction',$where);
        // echo '<pre>';print_r($bank_data);
        if(!empty($bank_data)){
            $status = true;
        }else{
            $status= false;
            $bank_data = 'no data';

        }

        $result = array('bank_data'=>$bank_data,
                        'status'=>$status
                        );

        echo json_encode($result);
    }

    function update_transaction_data(){
        $post_data = $this->input->post();
        if(!empty($post_data['update_bank_trans_id'])){
            $update_data = array(
                'bank_transaction_no' => $post_data['update_transaction_no'],
                'deposit_amount' => $post_data['update_amount'],
                'updated_at' => date('Y-m-d H:i:s')
                );
            $where = array('id' => $post_data['update_bank_trans_id']);
            $update_table = $this->Home_Model->updateTable('dealer_bank_transaction',$update_data,$where);
        }
        if(!empty($update_table)){
            redirect('admin/admin_dealer_approve');
        }

        // echo '<pre>';print_r($_POST);
    }


 function reconcile_data(){
        // echo '<pre>';print_r($_POST);
        $post_data = $this->input->post();
        if(!empty($post_data)){
            $where = array('id'=>$post_data['bank_id']);
            $reconcile_data = array(
                'reconcile_no' => $post_data['reconcile_no'] ,
                'reconcile_status' => 'approved'
                );
            $update = $this->Home_Model->updateTable('dealer_bank_transaction',$reconcile_data,$where);
        }
        if($update){
            redirect('admin/admin_dealer_approve/approved');
        }
    }

function invoice_approval($status=null){
        $data['status'] = $status ;
        $admin_session =  $this->session->userdata('admin_session');
        if(($admin_session['admin_role'] == 'opreation_admin' && $admin_session['admin_role_id'] == 2) || ($admin_session['admin_role'] == 'admin_master' && $admin_session['admin_role_id'] == 1) ){
        $data['main_contain'] = 'admin/dealer_approve/invoice_approval';
        $this->load->view('admin/includes/template', $data);
        }else{
            redirect('admin/admin_dashboard');
        }
}

    function invoice_approval_ajax(){
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
        $invoice_approval_status = ($requestData['invoice_approval_status']) ? $requestData['invoice_approval_status'] : 'pending' ;
            $status_filter = "WHERE ind.`invoice_status` = '$invoice_approval_status' " ;
        if($invoice_approval_status=='all'){
            $status_filter = '' ;
        }
        $sql = "SELECT ind.* ,td.dealer_name,td.sap_ad_code,td.`gst_no`,td.`pan_no` FROM invoice_details ind 
                JOIN tvs_dealers td ON ind.dealer_id = td.id $status_filter ORDER BY ind.`id` DESC";
        $totalFiltered = $totalData;
        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $result = $query->result();
        $data = array();
        $i = 1;
        foreach ($result as $main) {
            $created_date = explode(' ', $main->created_at);
            $created_date = $created_date[0];
            $invoice_data = json_decode($main->invoice_data,true);
            $view ='<button class"form-control btn btn-success" onclick="view_invoice_data('.$main->id.','.$main->dealer_id.')"><i class="fa fa-eye"></i></button>' ;
            $edit_btn = '<button class"form-control btn btn-success" onclick="EditInvoiceByAdmin('.$main->id.','.$main->dealer_id.')"><i class="fa fa-edit"></i></button>' ;
            $pending_btn = '<button title="Change Pending" class"form-control btn btn-success" onclick="UpdatePending('.$main->id.','.$main->dealer_id.')"><i class="fa fa-wrench" aria-hidden="true"></i></button>' ;
            $row = array();
            $row[] = $main->dealer_name;
            $row[] = $main->sap_ad_code;
            $row[] = $main->gst_no;
            $row[] = $main->pan_no;
            $row[] = $main->invoice_no;
            $row[] = $main->invoice_date;
            $row[] = $invoice_data['total_policy_count'];
            $row[] = $invoice_data['total_policy_premium'];
            $row[] = $invoice_data['total_policy_gst'];
            $row[] = $invoice_data['final_policy_premium'];
            $row[] = ($main->invoice_month)?$main->invoice_month:'';
            $row[] = $main->updated_at;
            $row[] = $created_date;
            $row[] = $main->invoice_status;
            $row[] = $main->comment;
            $row[] = $view.' '.$edit_btn;
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


public function submitDealerGstAmount(){
        $post_data = $this->input->post();
        // echo '<pre>'; print_r($post_data);die('here');
        $return_data = array();
        $where = array(
            'id'=>$post_data['invoice_id']
        );
        $invoice_details = $this->Home_Model->getRowDataFromTable('invoice_details',$where);

        if($invoice_details['invoice_status'] == 'pending'){
        $where = array(
            'dealer_id'=>$post_data['dealer_id']
        );
       $dealer_wallet_details =  $this->Home_Model->getRowDataFromTable('dealer_wallet',$where);
       if(!empty($dealer_wallet_details['dealer_id'])){
            $credit_amount = $dealer_wallet_details['credit_amount'];
            $security_amount = $dealer_wallet_details['security_amount'];
            $where = array(
                'id'=>$post_data['dealer_id']
            );
            $dealer_details = $this->Home_Model->getRowDataFromTable('tvs_dealers',$where);
            $is_gst_compliant = $dealer_details['is_gst_compliant']; 
            $is_allowed_commission_to_bank = $dealer_details['is_allowed_commission_to_bank']; 
            // $total_deposit_amount = ($is_gst_compliant ==1)?$post_data['total_deposit_amount']:$post_data['tds_deducted'];
            // echo '<pre>'; print_r($dealer_details);die('hello moto');
        if($is_allowed_commission_to_bank == 1){
            $total_commission = $dealer_wallet_details['total_commission'];
            if($is_gst_compliant ==1){
                $total_deposit_amount = $post_data['total_deposit_amount'];
                $total_commission     = ($total_commission + $total_deposit_amount);
            }else{
                $total_deposit_amount = $post_data['tds_deducted'];
                $total_commission     = ($total_commission - $total_deposit_amount);
            }
            $update_data = array('total_commission'=>$total_commission);

        }else{
            if($is_gst_compliant ==1){
                $total_deposit_amount = $post_data['total_deposit_amount'];
                if($credit_amount > 0 && ($credit_amount > $total_deposit_amount)){
                    $credit_amount = ($credit_amount - $total_deposit_amount);
                    $update_data = array(
                            'credit_amount'=>$credit_amount
                        );
                    }else{
                        $security_amount = ($security_amount +$total_deposit_amount);
                        $update_data = array(
                                'security_amount'=>$security_amount
                            );
                    }   
            }else{
                $total_deposit_amount = $post_data['tds_deducted'];
                if(($credit_amount + $total_deposit_amount) >= $security_amount){
                    $security_amount = ($security_amount - $total_deposit_amount);
                    $update_data = array(
                            'security_amount'=>$security_amount
                        );
                }else{
                    $credit_amount = $credit_amount + $total_deposit_amount;
                    $update_data = array(
                            'credit_amount'=>$credit_amount
                        );
                }
            }
        }
        $is_updated = $this->Home_Model->updateTable('dealer_wallet',$update_data,$where);
            // if($is_gst_compliant == 1){
            
            // }else{

            // }
            // $is_updated = 1;
            if($is_updated){
                $transaction_no = $this->getRandomNumber('16');
            if($is_gst_compliant == 1){
                $insert_data = array(
                    'dealer_id'=>$post_data['dealer_id'],
                    'invoice_id'=>$post_data['invoice_id'],
                    'transection_no'=>$transaction_no,
                    'transection_type'=>'cr',
                    'transection_amount'=>$post_data['total_policy_commission_gst'],
                    'transection_purpose'=>'GST Credit'
                    );
                $is_inserted = $this->Home_Model->insertIntoTable('dealer_transection_statement',$insert_data);
            }else{
                $where = array(
                    'id'=>$post_data['invoice_id']
                );
                $invoice_data = $this->Home_Model->getRowDataFromTable('invoice_details',$where);
                $insert_data = array(
                    'dealer_id'=>$post_data['dealer_id'],
                    'invoice_no'=>$invoice_data['invoice_no'],
                    'invoice_id'=>$invoice_data['id'],
                    'transaction_no'=>$transaction_no,
                    'gst_amount'=>$post_data['total_policy_commission_gst']
                    );
                $is_inserted = $this->Home_Model->insertIntoTable('tvs_dealers_gst_status',$insert_data);
            }
                //echo 'gst'.$is_inserted;
                // $transaction_no = $this->getRandomNumber('16');
                $tds_insert_data = array(
                    'dealer_id'=>$post_data['dealer_id'],
                    'invoice_id'=>$post_data['invoice_id'],
                    'transection_no'=>$transaction_no,
                    'transection_type'=>'dr',
                    'transection_amount'=>$post_data['tds_deducted'],
                    'transection_purpose'=>'TDS Deducted'
                    );
                $is_inserted = $this->Home_Model->insertIntoTable('dealer_transection_statement',$tds_insert_data);
               // echo 'tds'.$is_inserted;die('hello moto');
                if($is_inserted){
                    $where = array(
                        'id'=>$post_data['invoice_id']
                    );
                    $data = array('invoice_status'=>'approved');
                    $is_approved = $this->Home_Model->updateTable('invoice_details',$data,$where);
                    $return_data['status'] = 'false';
                    $return_data['msg'] = 'Error In Invoice Approve.';
                    if($is_approved){
                        $return_data['status'] = 'true';
                        $return_data['msg'] = 'Invoice Approve Success.';
                    }
                }else{
                    $return_data['status'] = 'false';
                    $return_data['msg'] = 'Error In Balance Updation.';
                }
            }else{
                $return_data['status'] = 'false';
                $return_data['msg'] = 'Error In Balance Updation.';
            }
       }else{
            $return_data['status'] = 'false';
            $return_data['msg'] = 'Error In Balance Updation.';
       }
   }else{
        $return_data['status'] = 'false';
        $return_data['msg'] = 'Invoice Already Approved.';
   }
   echo json_encode($return_data);
}

//      public function submitDealerGstAmount(){
//         $post_data = $this->input->post();
//         // echo '<pre>'; print_r($post_data);die('here');
//         $return_data = array();
//         $where = array(
//             'id'=>$post_data['invoice_id']
//         );
//         $invoice_details = $this->Home_Model->getRowDataFromTable('invoice_details',$where);

//         if($invoice_details['invoice_status'] == 'pending'){
//         $where = array(
//             'dealer_id'=>$post_data['dealer_id']
//         );
//        $dealer_wallet_details =  $this->Home_Model->getRowDataFromTable('dealer_wallet',$where);
//        if(!empty($dealer_wallet_details['dealer_id'])){
//             $credit_amount = $dealer_wallet_details['credit_amount'];
//             $security_amount = $dealer_wallet_details['security_amount'];
//             $where = array(
//                 'id'=>$post_data['dealer_id']
//             );
//             $dealer_details = $this->Home_Model->getRowDataFromTable('tvs_dealers',$where);
//             $is_gst_compliant = $dealer_details['is_gst_compliant'];
//             $total_deposit_amount = ($is_gst_compliant ==1)?$post_data['total_deposit_amount']:$post_data['tds_deducted'];
//             // echo '<pre>'; print_r($dealer_details);die('hello moto');

//             if($credit_amount > 0 && ($credit_amount > $total_deposit_amount)){
//                 $credit_amount = ($credit_amount - $total_deposit_amount);
//                 $update_data = array(
//                         'credit_amount'=>$credit_amount
//                     );
//             }else{
//                 $security_amount = $security_amount +$total_deposit_amount;
//                 $update_data = array(
//                         'security_amount'=>$security_amount
//                     );
//             }
//             $where = array(
//                 'dealer_id'=>$post_data['dealer_id']
//             );
//             // echo '<pre>'; 
//             // print_r($update_data);
//             // print_r($where);
//             // die('here');
//             $is_updated = $this->Home_Model->updateTable('dealer_wallet',$update_data,$where);
//             // $is_updated = 1;
//             if($is_updated){
//                 $transaction_no = $this->getRandomNumber('16');
//             if($is_gst_compliant == 1){
//                 $insert_data = array(
//                     'dealer_id'=>$post_data['dealer_id'],
//                     'transection_no'=>$transaction_no,
//                     'transection_type'=>'cr',
//                     'transection_amount'=>$post_data['total_policy_commission_gst'],
//                     'transection_purpose'=>'GST Credit'
//                     );
//                 $is_inserted = $this->Home_Model->insertIntoTable('dealer_transection_statement',$insert_data);
//             }else{
//                 $where = array(
//                     'id'=>$post_data['invoice_id']
//                 );
//                 $invoice_data = $this->Home_Model->getRowDataFromTable('invoice_details',$where);
//                 $insert_data = array(
//                     'dealer_id'=>$post_data['dealer_id'],
//                     'invoice_no'=>$invoice_data['invoice_no'],
//                     'invoice_id'=>$invoice_data['id'],
//                     'transaction_no'=>$transaction_no,
//                     'gst_amount'=>$post_data['total_policy_commission_gst']
//                     );
//                 $is_inserted = $this->Home_Model->insertIntoTable('tvs_dealers_gst_status',$insert_data);
//             }
//                 //echo 'gst'.$is_inserted;
//                 // $transaction_no = $this->getRandomNumber('16');
//                 $tds_insert_data = array(
//                     'dealer_id'=>$post_data['dealer_id'],
//                     'invoice_id'=>$post_data['invoice_id'],
//                     'transection_no'=>$transaction_no,
//                     'transection_type'=>'dr',
//                     'transection_amount'=>$post_data['tds_deducted'],
//                     'transection_purpose'=>'TDS Deducted'
//                     );
//                 $is_inserted = $this->Home_Model->insertIntoTable('dealer_transection_statement',$tds_insert_data);
//                // echo 'tds'.$is_inserted;die('hello moto');
//                 if($is_inserted){
//                     $where = array(
//                         'id'=>$post_data['invoice_id']
//                     );
//                     $data = array('invoice_status'=>'approved');
//                     $is_approved = $this->Home_Model->updateTable('invoice_details',$data,$where);
//                     $update_cancel_invoice_status = $this->update_cancel_invoice_status($post_data['invoice_id']);

//                     $return_data['status'] = 'false';
//                     $return_data['msg'] = 'Error In Invoice Approve.';
//                     if($is_approved){
//                         $return_data['status'] = 'true';
//                         $return_data['msg'] = 'Invoice Approve Success.';
//                     }
//                 }else{
//                     $return_data['status'] = 'false';
//                     $return_data['msg'] = 'Error In Balance Updation.';
//                 }
//             }else{
//                 $return_data['status'] = 'false';
//                 $return_data['msg'] = 'Error In Balance Updation.';
//             }
//        }else{
//             $return_data['status'] = 'false';
//             $return_data['msg'] = 'Error In Balance Updation.';
//        }
//    }else{
//         $return_data['status'] = 'false';
//         $return_data['msg'] = 'Invoice Already Approved.';
//    }
//    echo json_encode($return_data);
// }

function update_cancel_invoice_status($invoice_id){
$invoice_data_ar = $this->Home_Model->getRowDataFromTable('invoice_details',['id'=>$invoice_id]);
$invoice_data = json_decode($invoice_data_ar['invoice_data'],true);
$invoice_cancel_silver_count= $invoice_data['invoice_cancel_silver_count'];
$invoice_cancel_gold_count= $invoice_data['invoice_cancel_gold_count'];
$invoice_cancel_platinum_count= $invoice_data['invoice_cancel_platinum_count'];
$invoice_cancel_sapphireplus_count= $invoice_data['invoice_cancel_sapphireplus_count'];
$invoice_cancel_limitless_renewal_count= $invoice_data['invoice_cancel_limitless_renewal_count'];
$invoice_cancel_sapphire_count= $invoice_data['invoice_cancel_sapphire_count'];
// echo "<pre>"; print_r($invoice_data); echo "</pre>"; die('end of line yoyo');


    if($invoice_cancel_silver_count!=0 || !empty($invoice_cancel_silver_count)){
        
        $this->Home_Model->UpdateInvoiceCancelStatus('Silver',$invoice_data_ar['dealer_id'],$invoice_cancel_silver_count);
    }
    if($invoice_cancel_gold_count!=0 || !empty($invoice_cancel_gold_count)){
        
        $this->Home_Model->UpdateInvoiceCancelStatus('Gold',$invoice_data_ar['dealer_id'],$invoice_cancel_gold_count);
    }
    if($invoice_cancel_sapphireplus_count!=0 || !empty($invoice_cancel_sapphireplus_count)){
        
        $this->Home_Model->UpdateInvoiceCancelStatus('Sapphire Plus',$invoice_data_ar['dealer_id'],$invoice_cancel_sapphireplus_count);
    }
    if($invoice_cancel_sapphire_count!=0 || !empty($invoice_cancel_sapphire_count)){
        
        $this->Home_Model->UpdateInvoiceCancelStatus('Sapphire',$invoice_data_ar['dealer_id'],$invoice_cancel_sapphire_count);
    }
    if($invoice_cancel_limitless_renewal_count!=0 || !empty($invoice_cancel_limitless_renewal_count)){
        
        $this->Home_Model->UpdateInvoiceCancelStatus('LIMITLESS ASSIST RENEWAL',$invoice_data_ar['dealer_id'],$invoice_cancel_limitless_renewal_count);
    }
    if($invoice_cancel_platinum_count!=0 || !empty($invoice_cancel_platinum_count)){
        
        $this->Home_Model->UpdateInvoiceCancelStatus('Platinum',$invoice_data_ar['dealer_id'],$invoice_cancel_platinum_count);
    }

}

function gstApproval($status=null){
    $data['status'] = $status;
        $admin_session =  $this->session->userdata('admin_session');
        if(($admin_session['admin_role'] == 'opreation_admin' && $admin_session['admin_role_id'] == 2) || ($admin_session['admin_role'] == 'admin_master' && $admin_session['admin_role_id'] == 1) ){
        $data['main_contain'] = 'admin/dealer_approve/gst_approval';
        $this->load->view('admin/includes/template', $data);
        }else{
            redirect('admin/admin_dashboard');
        }
}

function gstApprovalAjax(){
        $requestData = $_REQUEST;
        // echo '<pre>'; print_r($requestData);die('hello');
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
        $gst_approval_status = ($requestData['gst_approval_status']) ? $requestData['gst_approval_status'] : 'pending' ;
            $status_filter = "WHERE tdgs.`approval_status` = '$gst_approval_status' " ;
        if($gst_approval_status=='all'){
            $status_filter = '' ;
        }
        $sql = "SELECT tdgs.*,inv.invoice_month,inv.invoice_date,td.dealer_name,td.sap_ad_code,td.`gst_no`,td.`pan_no` 
                FROM tvs_dealers_gst_status tdgs 
                INNER JOIN tvs_dealers td ON tdgs.dealer_id = td.id 
                INNER JOIN invoice_details inv ON inv.id = tdgs.invoice_id 
                $status_filter 
                ORDER BY tdgs.`id` DESC";
        $totalFiltered = $totalData;
        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $result = $query->result();
         // echo '<pre>'; print_r($result);die('hello moto');
        $data = array();
        $i = 1;
        foreach ($result as $main) {
            // echo '<pre>'; print_r($main->gst_amount);
            $created_date = explode(' ', $main->created_at);
            $created_date = $created_date[0];
            $invoice_data = json_decode($main->invoice_data,true);
            $view ='<button class"form-control btn btn-success" onclick="view_gst_data('.$main->id.','.$main->dealer_id.')"><i class="fa fa-eye"></i></button>' ;
            if($main->is_gst_compliant_file_uploaded == 1 && $main->gst_compliant_file != '' && (in_array($gst_approval_status,array("pending","referback") ) ) ){

        // die($gst_approval_status);
                $add_gst_button = '<button class"form-control btn btn-success" onclick="addGstToDealer('.$main->id.','.$main->dealer_id.')"><i class="fa fa-plus"></i></button>' ;
            }else{
                $add_gst_button = '';
            }
            $row = array();
            $row[] = $main->dealer_name;
            $row[] = $main->sap_ad_code;
            $row[] = $main->gst_no;
            $row[] = $main->pan_no;
            $row[] = $main->invoice_no;
            $row[] = $main->invoice_date;
            $row[] = ($main->invoice_month)?$main->invoice_month:'';
            $row[] = $main->gst_amount;
            $row[] = $main->created_at;
            $row[] = $main->approval_status;
            $row[] = $main->gst_compliant_file;
            $row[] = $view.' '.$add_gst_button;
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

function ViewUploadedGstFile(){
    $post = $this->input->post();
    $gst_id = $post['gst_id'];
    $gst_data="";
    if(!empty($gst_id)){
        $gst_data = $this->Home_Model->getGSTInvoicedata($gst_id);
        
    }
    echo json_encode($gst_data);
    // echo "<pre>"; print_r($gst_data); echo "</pre>"; die('end of line yoyo');
}

function updateGSTComplaintData(){
   $post = $this->input->post();
   // echo "<pre>"; print_r($post); echo "</pre>"; die('end of line yoyo');
   if(!empty($post['sap_ad_code']) && !empty($post['sap_ad_code'])){
        $update_data = array(
            "is_gst_compliant"=>$post['gst_complaint_val'],
            "updated_at" => date('Y-m-d H:i:s')

        );
        $updated = $this->Home_Model->updateTable('tvs_dealers',$update_data,['sap_ad_code'=>$post['sap_ad_code'] ]);
        if(!empty($updated)){
            $this->session->set_flashdata('success',"GST is Updated");
        }else{
            $this->session->set_flashdata('success',"Something went wrong,try again .");
        }
   }

   redirect('admin/dealer_master');
}

function ReferbackInvoiceStatus(){
    $post = $this->input->post();
    $where = array('id'=>$post['invoice_id']);
    $data = array('invoice_status'=>'referback' ,
                    'updated_at' => date('Y-m-d H:i:s')
                );
    $updated = $this->Home_Model->updateTable('invoice_details',$data,$where);

    if($updated){
        $status = 'true';
    }else{
        $status = 'false';
    }

    echo json_encode($status);
}

function ViewInvoiceData(){
        $post_data = $this->input->post();
        $invoice_details = $this->Home_Model->getRowDataFromTable('invoice_details',['id'=>$post_data['invoice_id']] );
        $invoice_data = json_decode($invoice_details['invoice_data'],true);
        // echo "<pre>"; print_r($invoice_data); echo "</pre>"; die('end of line yoyo');
        
        $cancelled_silver_policies =$invoice_data['cancelled_silver_policies'];
        $cancelled_gold_policies =$invoice_data['cancelled_gold_policies'];
        $cancelled_platinum_policies =$invoice_data['cancelled_platinum_policies'];
        $cancelled_sapphire_policies =$invoice_data['cancelled_sapphire_policies'];
        $cancelled_sapphireplus_policies =$invoice_data['cancelled_sapphireplus_policies'];
        $cancelled_limitless_renewal_policies =$invoice_data['cancelled_limitless_renewal_policies'];

        $active_silver_policies = $invoice_data['active_silver_policies'];
        $active_gold_policies = $invoice_data['active_gold_policies'];
        $active_platinum_policies = $invoice_data['active_platinum_policies'];
        $active_sapphire_policy = $invoice_data['active_sapphire_policy'];
        $active_sapphireplus_policy = $invoice_data['active_sapphireplus_policy'];
        $active_limitless_renewal_policy = $invoice_data['active_limitless_renewal_policy'];
        
        $pending_silver_canceled_policies = $invoice_data['pending_silver_canceled_policies'];
        $pending_gold_canceled_policies = $invoice_data['pending_gold_canceled_policies'];
        $pending_platinum_canceled_policies = $invoice_data['pending_platinum_canceled_policies'];
        $pending_sapphire_canceled_policy = $invoice_data['pending_sapphire_canceled_policy'];
        $pending_sapphireplus_canceled_policy = $invoice_data['pending_sapphireplus_canceled_policy'];
        $pending_limitless_renewal_canceled_policies = $invoice_data['pending_limitless_renewal_canceled_policies'];

        $count_silver_policies = $invoice_data['count_silver_policies'];
        $silver_policy_premium = $invoice_data['silver_policy_premium'];
        $silver_policy_gst = $invoice_data['silver_policy_gst'];
        $silver_total_policy_premium = $invoice_data['silver_total_policy_premium'];
        $silver_policy_commission = $invoice_data['silver_policy_commission'];
        $silver_policy_commission_gst = $invoice_data['silver_policy_commission_gst'];
        $silver_policy_total_commission = $invoice_data['silver_policy_total_commission'];

        $count_gold_policies = $invoice_data['count_gold_policies'];
        $gold_policy_premium = $invoice_data['gold_policy_premium'];
        $gold_policy_gst = $invoice_data['gold_policy_gst'];
        $gold_total_policy_premium = $invoice_data['gold_total_policy_premium'];
        $gold_policy_commission = $invoice_data['gold_policy_commission'];
        $gold_policy_commission_gst = $invoice_data['gold_policy_commission_gst'];
        $gold_policy_total_commission = $invoice_data['gold_policy_total_commission'];

        $count_platinum_policies = $invoice_data['count_platinum_policies'];
        $platinum_policy_premium = $invoice_data['platinum_policy_premium'];
        $platinum_policy_gst = $invoice_data['platinum_policy_gst'];
        $platinum_total_policy_premium = $invoice_data['platinum_total_policy_premium'];
        $platinum_policy_commission = $invoice_data['platinum_policy_commission'];
        $platinum_policy_commission_gst = $invoice_data['platinum_policy_commission_gst'];
        $platinum_policy_total_commission = $invoice_data['platinum_policy_total_commission'];

        $count_sapphire_policy = $invoice_data['count_sapphire_policy'];
        $sapphire_policy_premium = $invoice_data['sapphire_policy_premium'];
        $sapphire_policy_gst = $invoice_data['sapphire_policy_gst'];
        $sapphire_total_policy_premium = $invoice_data['sapphire_total_policy_premium'];
        $sapphire_policy_commission = $invoice_data['sapphire_policy_commission'];
        $sapphire_policy_commission_gst = $invoice_data['sapphire_policy_commission_gst'];
        $sapphire_policy_total_commission = $invoice_data['sapphire_policy_total_commission'];

        $count_sapphireplus_policy = $invoice_data['count_sapphireplus_policy'];
        $sapphireplus_policy_premium = $invoice_data['sapphireplus_policy_premium'];
        $sapphireplus_policy_gst = $invoice_data['sapphireplus_policy_gst'];
        $sapphireplus_total_policy_premium = $invoice_data['sapphireplus_total_policy_premium'];
        $sapphireplus_policy_commission = $invoice_data['sapphireplus_policy_commission'];
        $sapphireplus_policy_commission_gst = $invoice_data['sapphireplus_policy_commission_gst'];
        $sapphireplus_policy_total_commission = $invoice_data['sapphireplus_policy_total_commission'];

        $count_limitless_renewal_policy = $invoice_data['count_limitless_renewal_policy'];
        $limitless_renewal_policy_premium = $invoice_data['limitless_renewal_policy_premium'];
        $limitless_renewal_policy_gst = $invoice_data['limitless_renewal_policy_gst'];
        $limitless_renewal_total_policy_premium = $invoice_data['limitless_renewal_total_policy_premium'];
        $limitless_renewal_policy_commission = $invoice_data['limitless_renewal_policy_commission'];
        $limitless_renewal_policy_commission_gst = $invoice_data['limitless_renewal_policy_commission_gst'];
        $limitless_renewal_policy_total_commission = $invoice_data['limitless_renewal_policy_total_commission'];

        $total_policy_count = $invoice_data['total_policy_count'];
        $total_policy_premium = $invoice_data['total_policy_premium'];
        $total_policy_gst = $invoice_data['total_policy_gst'];
        $final_policy_premium = $invoice_data['final_policy_premium'];
        $total_policy_commission = $invoice_data['total_policy_commission'];
        $total_policy_commission_gst = $invoice_data['total_policy_commission_gst'];
        $final_policy_commission = $invoice_data['final_policy_commission'];
       
        $tds_deducted = ($total_policy_commission*0.05);
        $total_deposit_amount = ($total_policy_commission_gst - $tds_deducted);
        $return_data['total_deposit_amount'] = $total_deposit_amount;
        $return_data['total_policy_commission_gst'] = $total_policy_commission_gst;
        $return_data['tds_deducted'] = $tds_deducted;
        $return_data['invoice_status'] = $invoice_details['invoice_status'];
        if(!empty($invoice_data)){
            $html2 = <<<EOD

            <tr><th>Plan</th>
                          <td>Silver</td>
                          <td>Gold</td>
                          <td>Platinum</td>
                          <td>Sapphire</td>
                          <td>Sapphire Plus</td>
                          <td>Limitless Renewal</td>
                      </tr>
                      <tr><th>Total Canceled Policy Count</th>
                            <td>{$cancelled_silver_policies}</td>
                            <td>{$cancelled_gold_policies}</td>
                            <td>{$cancelled_platinum_policies}</td>
                            <td>{$cancelled_sapphire_policies}</td>
                            <td>{$cancelled_sapphireplus_policies}</td>
                            <td>{$cancelled_limitless_renewal_policies}</td>
                      </tr>
EOD;
            $html = <<<EOD
                    <tr>
                        <th scope="row">Silver</th>
                        <td>{$count_silver_policies}</td>
                        <td>{$silver_policy_premium}</td>
                        <td>{$silver_policy_gst}</td>
                        <td>{$silver_total_policy_premium}</td>
                        <td>{$silver_policy_commission}</td>
                        <td>{$silver_policy_commission_gst}</td>
                        <td>{$silver_policy_total_commission}</td>
                      </tr>
                      <tr>
                        <th scope="row">Gold</th>
                        <td>{$count_gold_policies}</td>
                        <td>{$gold_policy_premium}</td>
                        <td>{$gold_policy_gst}</td>
                        <td>{$gold_total_policy_premium}</td>
                        <td>{$gold_policy_commission}</td>
                        <td>{$gold_policy_commission_gst}</td>
                        <td>{$gold_policy_total_commission}</td>
                      </tr>
                      <tr>
                        <th scope="row">Platinum</th>
                        <td>{$count_platinum_policies}</td>
                        <td>{$platinum_policy_premium}</td>
                        <td>{$platinum_policy_gst}</td>
                        <td>{$platinum_total_policy_premium}</td>
                        <td>{$platinum_policy_commission}</td>
                        <td>{$platinum_policy_commission_gst}</td>
                        <td>{$platinum_policy_total_commission}</td>
                      </tr>
                      <tr>
                        <th scope="row">Sapphire</th>
                        <td>{$count_sapphire_policy}</td>
                        <td>{$sapphire_policy_premium}</td>
                        <td>{$sapphire_policy_gst}</td>
                        <td>{$sapphire_total_policy_premium}</td>
                        <td>{$sapphire_policy_commission}</td>
                        <td>{$sapphire_policy_commission_gst}</td>
                        <td>{$sapphire_policy_total_commission}</td>
                      </tr>
                      <tr>
                        <th scope="row">Sapphire Plus</th>
                        <td>{$count_sapphireplus_policy}</td>
                        <td>{$sapphireplus_policy_premium}</td>
                        <td>{$sapphireplus_policy_gst}</td>
                        <td>{$sapphireplus_total_policy_premium}</td>
                        <td>{$sapphireplus_policy_commission}</td>
                        <td>{$sapphireplus_policy_commission_gst}</td>
                        <td>{$sapphireplus_policy_total_commission}</td>
                      </tr>
                      <tr>
                        <th scope="row">Limitless Renewal</th>
                        <td>{$count_limitless_renewal_policy}</td>
                        <td>{$limitless_renewal_policy_premium}</td>
                        <td>{$limitless_renewal_policy_gst}</td>
                        <td>{$limitless_renewal_total_policy_premium}</td>
                        <td>{$limitless_renewal_policy_commission}</td>
                        <td>{$limitless_renewal_policy_commission_gst}</td>
                        <td>{$limitless_renewal_policy_total_commission}</td>
                      </tr>
                      <tr>
                        <th scope="row">Total</th>
                        <td>{$total_policy_count}</td>
                        <td>{$total_policy_premium}</td>
                        <td>{$total_policy_gst}</td>
                        <td>{$final_policy_premium}</td>
                        <td>{$total_policy_commission}</td>
                        <td>{$total_policy_commission_gst}</td>
                        <td>{$final_policy_commission}</td>
                      </tr>
EOD;
        }else{
            $html = <<<EOD
                    <tr><td>No Data</tr></td>
EOD;
        }
        $return_data['html'] = $html;
        $return_data['html2'] = $html2;
        echo json_encode($return_data);
    }

    public function submit_reject_invoice(){
        $post = $this->input->post();
        // echo "<pre>"; print_r($post); echo "</pre>"; die('end of line yoyo');
        if(!empty($post['rej_invoice_id']) && !empty($post['reject_comment'])){
            $update_data = array('invoice_status'=>'rejected',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'comment' => $post['reject_comment']
                            );
            $where = array('id'=> $post['rej_invoice_id']);
            $update = $this->Home_Model->updateTable('invoice_details',$update_data,$where);
        }
        
            redirect('admin/invoice_approval');
        
    }

function getInvoiceData(){
        $post = $this->input->post();
        $invoice_data = array();
        if(!empty($post['invoice_id'])){
            $where = array('id'=>$post['invoice_id']);
            $invoice_data = $this->Home_Model->getRowDataFromTable('invoice_details',$where);
            $dealer_id = $invoice_data['dealer_id'];
            $policy_data = $this->db->query("SELECT Date(sold_policy_date) AS sold_policy_date FROM tvs_sold_policies where user_id = '$dealer_id' AND policy_status_id = 3 ORDER BY id ASC ")->result_array();
            $invoice_data['policy_started_from'] = date("m-Y", strtotime($policy_data[0]['sold_policy_date'])) ;
            if(!empty($invoice_data)){
                $invoice_data['status'] = 'true';
            }else{
                $invoice_data['status'] = 'false';
            }
        }else{
            $invoice_data['status'] = 'false';
        }

        echo json_encode($invoice_data);
    }
    

function update_invoice_data(){
        $post = $this->input->post();
        $invoice_id = $post['hid_invoice_id'];
  if(!empty($invoice_id)){
        $where = array('id'=>$invoice_id);
        $invoice_data = $this->Home_Model->getRowDataFromTable('invoice_details',$where);
        // echo "<pre>"; print_r($invoice_data); echo "</pre>"; die('end of line yoyo');
        $exist_invoice = $this->Home_Model->getExistInvoiceDetails($post['edit_invoice_no'],$invoice_data['dealer_id'],$invoice_id);
          if($exist_invoice['count_invoice'] > 0){
            $this->session->set_flashdata('success','Invoice is Already Exist.');
        }else{
            $json_invoice_data = json_decode($invoice_data['invoice_data'],true);
            $json_invoice_data['invoice_no'] = $post['edit_invoice_no'];
            $json_invoice_data['invoice_date'] = $post['edit_invoice_date'];
            $json_invoice_data['post_invoice_month'] = $post['edit_invoice_month'];
            $edited_invoice = json_encode($json_invoice_data);
            $data = array(
            'invoice_no' => $post['edit_invoice_no'],
            'invoice_date' => $post['edit_invoice_date'],
            'invoice_month' => $post['edit_invoice_month'],
            'invoice_data' => $edited_invoice,
            'updated_at' => date('Y-m-d H:i:s')
            );
            // echo "<pre>"; print_r($json_invoice_data); echo "</pre>"; die('end of line yoyo');
            $where = array('id'=>$invoice_id);
            $updated = $this->Home_Model->updateTable('invoice_details',$data,$where);
            if(!empty($updated)){
                    $this->session->set_flashdata('success','Invoice is Updated.');
                }else{
                    $this->session->set_flashdata('success','Something went wrong, please try again.');
                }
            }
  }else{
    $this->session->set_flashdata('success','Something went wrong, please try again.');
  }

redirect('admin/invoice_approval');

}


function SendSMSToDealer(){
    $data['main_contain'] = 'admin/dealer_approve/send_sms';
    $this->load->view('admin/includes/template', $data);
}



function NotLoggedInDealer(){
    $today = date("Y-m-d");
    $not_logged_in_dealers = $this->Home_Model->getDealersNotLogged();
     // echo "<pre>"; print_r($not_logged_in_dealers); echo "</pre>"; die('end of line yoyo');
    // $not_logged_in_dealers = array(
    //             array('mobile' =>'7977588122',
    //                     'dealer_name' => 'Annu' ,
    //                     'sap_ad_code' => '11111' ,
    //                 ),
    //             array('mobile' =>'7208602389',
    //                     'dealer_name' => 'Amitdeep' ,
    //                     'sap_ad_code' => '10212' ,
    //                 ),
    //             array('mobile' =>'7208602389',
    //                     'dealer_name' => 'Amita' ,
    //                     'sap_ad_code' => '10576' ,
    //                 ),
    //             array('mobile' =>'8108818995',
    //                     'dealer_name' => 'Amit' ,
    //                     'sap_ad_code' => '612311' ,
    //                 )
                // array('mobile' =>'8080414842',
                //         'dealer_name' => 'Linto' ,
                //         'sap_ad_code' => '11535' ,
                //     )
            // );
    $not_logged_in_dealer_codes = '';
    if(!empty($not_logged_in_dealers)){
            $not_logged_in_dealer_codes =  implode(',', array_map(function ($not_logged_in_dealers) {
                  return $not_logged_in_dealers['sap_ad_code'];
                }, $not_logged_in_dealers));
        }
        $sql = "SELECT *
                FROM send_sms_status
                WHERE dealer_code IN ($not_logged_in_dealer_codes)
                AND sms_type = 'not_logged_in'
                AND date = '$today'
                AND is_send = 1";
                //die($sql);
                $exist_sms = $this->db->query($sql)->result_array(); 
              
    foreach ($not_logged_in_dealers as $value) {
        $status = false;
        $sap_ad_code = $value['sap_ad_code'];
        $mobile = $value['mobile'];
        $dealer_name = $value['dealer_name'];
        $is_sms_sent = $this->checkIsValueExistInMultiArray($exist_sms,$sap_ad_code);
            if($is_sms_sent == true){
                $status == false;
                $this->session->set_flashdata('success','SMS All Ready Sent');
            }else{
                $Message = "Dear ".$dealer_name." You have not logged into TVS - RSA portal, kindly log in and participate. For any issues please call /write back to concerned.";
                $output = $this->sendSms($mobile,$Message);
                $response = json_decode($output);
                // echo '<pre>'.$mobile.' :-'; print_r(json_decode($output));die('sfasfas');
                if(trim($output) == "Message+Sent+Successfully"){
                    $insert = array(
                        'dealer_code'=> $sap_ad_code,
                        'sms_type' => 'not_logged_in',
                        'is_send' => 1,
                        'date' => date('Y-m-d'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    $insert_id = $this->Home_Model->insertIntoTable('send_sms_status',$insert);
                    if(!empty($insert_id)){
                        $status = true;
                    }
                }
            }
    }
        if($status == true){
            $this->session->set_flashdata('success','SMS Sent');
        }

        redirect('admin/send_sms');
}
function checkIsValueExistInMultiArray($exist_sms,$sap_ad_code){

   if(is_array($exist_sms) && !empty($exist_sms)){
    foreach ($exist_sms as $key => $exist) {
        if($exist['dealer_code'] == $sap_ad_code){
            return true;
        }
    }
   }
}
function LessBalanceDealer(){
    $today = date("Y-m-d");
    $less_balance_dealer = $this->Home_Model->getDealerLessBalance();
    // echo "<pre>"; print_r($less_balance_dealer); echo "</pre>"; die('end of line yoyo');
    // $less_balance_dealer = array(
    //             array('mobile' =>'8898188910',
    //                     'dealer_name' => 'Annu' ,
    //                     'wallet_balance' => 277.3,
    //                     'sap_ad_code' => '11111' ,
    //                 ),
    //             // array('mobile' =>'9790931655',
    //             //         'dealer_name' => 'Amitdeep' ,
    //             //         'wallet_balance' => 150,
    //             //         'sap_ad_code' => '10576' ,
    //             //     ),
    //             array('mobile' =>'7208602389',
    //                     'wallet_balance' => 500.00,
    //                     'sap_ad_code' => '10576' ,
    //                 ),
    //             array('mobile' =>'8108818995',
    //                     'dealer_name' => 'Amit' ,
    //                     'wallet_balance' => 500.00,
    //                     'sap_ad_code' => '60311' ,
    //                 )
    //             // array('mobile' =>'8080414842',
    //             //         'dealer_name' => 'Linto' ,
    //             //         'wallet_balance' => 400.55,
    //             //         'sap_ad_code' => '11535' ,
    //             //     )
    //         );
     $less_balance_dealer_codes = '';
    if(!empty($less_balance_dealer)){
            $less_balance_dealer_codes =  implode(',', array_map(function ($less_bal_dealers) {
                  return $less_bal_dealers['sap_ad_code'];
                }, $less_balance_dealer));
        }
        $sql = "SELECT *
                FROM send_sms_status
                WHERE dealer_code IN ($less_balance_dealer_codes)
                AND sms_type = 'less_balance'
                AND date = '$today'
                AND is_send = 1";
                $exist_sms = $this->db->query($sql)->result_array(); 
                $i = 1;
    foreach ($less_balance_dealer as $value) {
        $status = false;
        $sap_ad_code = $value['sap_ad_code'];
        $mobile = $value['mobile'];
        $dealer_name = $value['dealer_name'];
        $wallet_balance = $value['wallet_balance'];
        $is_sms_sent = $this->checkIsValueExistInMultiArray($exist_sms,$sap_ad_code);
            if($is_sms_sent == true){
                $status = false;
                $this->session->set_flashdata('success','SMS All Ready Sent');
            }else{

                 $Message = "Dear ".$dealer_name." this is to inform you that your account balance in TVS-RSA has gone below INR 1000 please deposit minimum INR 5000 to enjoy uninterrupted policy issuance. ";
                $output = $this->sendSms($mobile,$Message);
                $response = json_decode($output);
                // echo '<pre>'.$mobile.' :-'; print_r(json_decode($output));die('sfasfas');
                if(trim($output) == "Message+Sent+Successfully"){
                    $insert = array(
                        'dealer_code'=> $sap_ad_code,
                        'sms_type' => 'less_balance',
                        'is_send' => 1,
                        'date' => date('Y-m-d'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    $insert_id = $this->Home_Model->insertIntoTable('send_sms_status',$insert);
                    if(!empty($insert_id)){
                        $status = true;
                    }
                }
            }
            $i++;
        }
    if($status == true){
        $this->session->set_flashdata('success','SMS Sent');
    }

        redirect('admin/send_sms');
}


function dealer_policy_issued_today(){
    $today = date("Y-m-d");
    $today_issued_policy_dealers = $this->Home_Model->getTodayPolicyIssued();
     //echo "<pre>"; print_r($today_issued_policy_dealers); echo "</pre>"; die('end of line yoyo');
    // $today_issued_policy_dealers = array(
    //             array('mobile' =>'8898188910',
    //                     'dealer_name' => 'Annu' ,
    //                     'sap_ad_code' => '11111' ,
    //                     'td_policy' => 3,
    //                     'mtd_policy'=>54
    //                 ),
    //             // array('mobile' =>'9790931655',
    //             //         'dealer_name' => 'Amitdeep' ,
    //             //         'sap_ad_code' => '10576' ,
    //             //     ),
    //             array('mobile' =>'7208602389',
    //                     'dealer_name' => 'Amita' ,
    //                     'sap_ad_code' => '10576' ,
    //                     'td_policy' => 5,
    //                     'mtd_policy'=>55
    //                 ),
    //             array('mobile' =>'8108818995',
    //                     'dealer_name' => 'Amit' ,
    //                     'sap_ad_code' => '60311' ,
    //                     'td_policy'=>10,
    //                     'mtd_policy'=>80
    //                 )
                
    //         );

     $today_issued_policy_dealer_codes = '';
    if(!empty($today_issued_policy_dealers)){
            $today_issued_policy_dealer_codes =  implode(',', array_map(function ($today_issued_policy) {
                  return $today_issued_policy['dealer_code'];
                }, $today_issued_policy_dealers));
        }
        $sql = "SELECT *
                FROM send_sms_status
                WHERE dealer_code IN ($today_issued_policy_dealer_codes)
                AND sms_type = 'today_issued_policy_dealers'
                AND date = '$today'
                AND is_send = 1";
                $exist_sms = $this->db->query($sql)->result_array(); 
                $i = 1;
    foreach ($today_issued_policy_dealers as $value) {
        $status = false;
        $sap_ad_code = $value['dealer_code'];
        $mobile = $value['dp_mobile'];
        $dealer_name = $value['dealership_name'];
        $td_policy = $value['td_policy'];
        $mtd_policy = $value['mtd_policy'];

         $is_sms_sent = $this->checkIsValueExistInMultiArray($exist_sms,$sap_ad_code);
            if($is_sms_sent == true){
                $status = false;
                $this->session->set_flashdata('success','SMS All Ready Sent');
            }else{
                $Message = "Dear ".$dealer_name.",Current update for TVS RSA + PA policy issued FTD ".$td_policy." MTD ".$mtd_policy."
                    Team ICPL ";
                    $output = $this->sendSms($mobile,$Message);
                    $response = json_decode($output);
                    // echo '<pre>'.$mobile.' :-'; print_r(json_decode($output));die('sfasfas');
                    if(trim($output) == "Message+Sent+Successfully"){
                        $insert = array(
                            'dealer_code'=> $sap_ad_code,
                            'sms_type' => 'today_issued_policy_dealers',
                            'is_send' => 1,
                            'date' => date('Y-m-d'),
                            'created_at' => date('Y-m-d H:i:s')
                        );
                        $insert_id = $this->Home_Model->insertIntoTable('send_sms_status',$insert);
                        if(!empty($insert_id)){
                            $status = true;
                        }
                    }
                }
            }
        if($status == true){
            $this->session->set_flashdata('success','SMS Sent');
        }

        redirect('admin/send_sms');
}

function Last7DaysDealerInactive(){
    $last_7days_inactive_dealers = $this->Home_Model->get7days_inactive_dealers();
    // echo "<pre>"; print_r($last_7days_inactive_dealers); echo "</pre>"; die('end of line yoyo');
    // $last_7days_inactive_dealers = array(
    //             array('mobile' =>'8898188910',
    //                     'dealer_name' => 'Annu' ,
    //                     'td_policy' => 0,
    //                     'sap_ad_code' => '11111' ,
    //                 ),
    //             // array('mobile' =>'9790931655',
    //             //         'dealer_name' => 'Amitdeep' ,
    //             //         'wallet_balance' => 150,
    //             //         'sap_ad_code' => '10576' ,
    //             //     ),
    //             array('mobile' =>'7208602389',
    //                     'td_policy' => 0,
    //                     'sap_ad_code' => '10576' ,
    //                 ),
    //             array('mobile' =>'8108818995',
    //                     'dealer_name' => 'Amit' ,
    //                     'td_policy' => 0,
    //                     'sap_ad_code' => '60311' ,
    //                 )
    //             // array('mobile' =>'8080414842',
    //             //         'dealer_name' => 'Linto' ,
    //             //         'wallet_balance' => 400.55,
    //             //         'sap_ad_code' => '11535' ,
    //             //     )
    //         );
    foreach ($last_7days_inactive_dealers as $value) {
        $status = false;
        $sap_ad_code = $value['sap_ad_code'];
        $mobile = $value['mobile'];
        $dealer_name = $value['dealer_name'];
        $Message = "Dear ".$dealer_name.", You have not issued TVS -RSA +PA policy  in last 7 days. For any issues please call /write back to us on ......... ";
        $where = array('dealer_code'=>$sap_ad_code,'sms_type'=>'last_7days_inactive_dealers');
            $exist_sms = $this->Home_Model->getRowDataFromTable('send_sms_status',$where);
            // echo "<pre>"; print_r($exist_sms); echo "</pre>"; die('end of line yoyo');
            if(!empty($exist_sms)){
                // echo "<pre>"; print_r($exist_sms); echo "</pre>"; die('end of line yoyo');
                if($exist_sms['date']==date('Y-m-d')){
                        $status = false;
                }else{
                        $output = $this->sendSms($mobile,$Message);
                        $response = json_decode($output);
                        // echo '<pre>'.$mobile.' :-'; print_r(json_decode($output));die('sfasfas');
                        if(trim($output) == "Message+Sent+Successfully"){
                        $update = array(
                            'sms_type' => 'last_7days_inactive_dealers',
                            'is_send' => 1,
                            'date' => date('Y-m-d'),
                            
                        );
                        $where = array('dealer_code'=>$sap_ad_code,'sms_type'=>'last_7days_inactive_dealers');
                        $update = $this->Home_Model->updateTable('send_sms_status',$update,$where);
                        if(!empty($update)){
                        $status = true;
                        }
                    }
                }
            }else{
                $output = $this->sendSms($mobile,$Message);
                $response = json_decode($output);
                // echo '<pre>'.$mobile.' :-'; print_r(json_decode($output));die('sfasfas');
                if(trim($output) == "Message+Sent+Successfully"){
                    $insert = array(
                        'dealer_code'=> $sap_ad_code,
                        'sms_type' => 'last_7days_inactive_dealers',
                        'is_send' => 1,
                        'date' => date('Y-m-d'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    $insert_id = $this->Home_Model->insertIntoTable('send_sms_status',$insert);
                    if(!empty($insert_id)){
                        $status = true;
                    }
                }
            }
            
        }
    if($status == true){
        $this->session->set_flashdata('success','SMS Sent');
    }

        redirect('admin/send_sms');
}


function sendSms($Mobilenumber,$Message) {
     //$Mobilenumber = 7977588122;
       //  $uid = "MPOLNW";
       //  $pwd = "2000";
       //  $sid = "TVSRSA";
       //  $method = "POST";
       //  $message = urlencode($Message);
       //  $get_url = "http://www.k3digitalmedia.in/vendorsms/pushsms.aspx?user=" . $uid . "&password=" . $pwd . "&msisdn=" . $Mobilenumber . "&sid=" . $sid . "&msg=" . $message . "&fl=0&gwid=2";
       // // die($get_url);
       //      $ch = curl_init();
       //      curl_setopt($ch, CURLOPT_POST, false);
       //      curl_setopt($ch, CURLOPT_URL, $get_url);
       //      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       //      $response = curl_exec($ch);
       //      curl_close($ch);
       //      echo '<pre>'; print_r($response);die('hello moto');
       //      // Use file get contents when CURL is not installed on server.
       //      if(!$response){
       //          $response = file_get_contents($get_url);
       //      }

        $uid = "MPOLNW";
        $pwd = "Pwe$NiTm";
        $sid = "MPOLNW";
        $method = "POST";
       $get_url='http://123.108.46.12/API/WebSMS/Http/v1.0a/index.php?username='.$uid.'&password=Pwe$NiTm&sender=MPOLNW&to='.$Mobilenumber.'&message='.urlencode($Message).'';
       $output = file_get_contents($get_url);
        return $output;
    }

function submitTarget(){
    $post = $this->input->post();
    $target = array(
        'td'=>$post['td_target'],
        'mtd'=>$post['mtd_target'],
        'ytd'=>$post['ytd_target'],
        'created_at' => date('Y-m-d H:i:s')
    );

    $inserted_target_id = $this->Home_Model->insertIntoTable('tvs_target',$target);
    if(!empty($inserted_target_id)){
        redirect('admin/target_achivement');
    }
}

function sendSmsTask(){
    $active_dealers = $this->db->query("SELECT td.mobile,td.sap_ad_code,td.dealer_name FROM tvs_dealers td 
        INNER JOIN dealer_wallet AS dw ON td.id = dw.dealer_id ")->result_array();
    // echo "<pre>"; print_r($active_dealers); echo "</pre>"; die('end of line yoyo');
    // $active_dealers = array(
    //             array('mobile' =>'8898188910',
    //                     'dealer_name' => 'Annu' ,
    //                     'td_policy' => 0,
    //                     'sap_ad_code' => '11111' ,
    //                 ),
              
    //             array('mobile' =>'8108818995',
    //                     'dealer_name' => 'Amit' ,
    //                     'td_policy' => 0,
    //                     'sap_ad_code' => '60311' ,
    //                 )
    //         );

foreach ($active_dealers as $value) {
    $mobile = $value['mobile'];
    $Message = "Now Auto-Fill of Customer's data is available with Engine No , Team TVSRSA. ";
    $output = $this->sendSms($mobile,$Message);
    if(trim($output) == "Message+Sent+Successfully"){
        $status == true;
    }else{
        $status == false;
    }
    // echo '<pre>';  print_r($output);die('sj');
}
    
 if($status == true){
        $this->session->set_flashdata('success','SMS Sent');
    }

        redirect('admin/send_sms');
  
}

function UpdatePendingInvoice(){
    $post = $this->input->post();
    // echo "<pre>"; print_r($post); echo "</pre>"; die('end of line yoyo');
    $invoice_id = $post['invoice_id'];
    $udate_pending = array(
                        'invoice_status' => 'pending',
                        'updated_at' => date('Y-m-d H:i:s')
                    );
    $where = array('id'=>$invoice_id);
    $update_status = $this->Home_Model->updateTable('invoice_details',$udate_pending,$where);
    $status = ($update_status)?'true':'flase';
    echo json_encode($status);
}

function ReferbackGstInvoice(){
    $post = $this->input->post();
    // print_r($post);die;
     $hid_gst_invoice = $post['hid_gst_invoice'];
     $hid_dealer_id = $post['hid_dealer_id'];
     $comment = $post['comment'];

     $update_data = array(
            'approval_status'=>'referback',
            'comment' => $comment,
            'updated_at'=> date('Y-m-d H:i:s')
     );
     $where = array('dealer_id'=>$hid_dealer_id,'invoice_no'=>trim($hid_gst_invoice));
    $update_status = $this->Home_Model->updateTable('tvs_dealers_gst_status',$update_data,$where);
    // echo $this->db->last_query();die;
    $status = ($update_status)?'true':'flase';
    echo json_encode($status);
}

}
