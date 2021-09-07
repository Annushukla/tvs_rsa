<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Tvs_Dealer extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->library('database_library');
        $this->load->model('Home_Model');
        $this->load->helper('common_helper');
        isUserLoggedIn();
        /*       $this ->checkLogin(); */
    }

public function DealerTransactionPost(){
    $session_data = $this->session->userdata('user_session');
	$dealer_session_id = $session_data['id'];
	$post_data = $this->input->post();
    $transaction_date = $post_data['transaction_date'];
    $newDate = date("Y-m-d", strtotime($transaction_date));
    // echo "<pre>"; print_r($newDate); echo "</pre>"; die('end of line yoyo');
    if($newDate=='0000-00-00'||$newDate=='1970-01-01'||$post_data['transaction_date']=="" || $post_data['transaction_type']=="" || $post_data['deposit_amount']==""){
        $this->session->set_flashdata('message','Please Fill the Valid Input Data');
        redirect('myDashboardSection');
    }
    $where = array('bank_transaction_no'=>$post_data['transaction_no']);
    $exist_dealer_bank_trans_data = $this->Home_Model->getRowDataFromTable('dealer_bank_transaction',$where);
    if(in_array($exist_dealer_bank_trans_data['approval_status'], array('approved','referback','pending'))){
        $this->session->set_flashdata('message','Transanction is exist');
        redirect('myDashboardSection');
    }

    $transaction_data = array(
        'bank_name' => $this->input->post('bank_name'),
        'bank_transaction_no' => $this->input->post('transaction_no'),
        'bank_ifsc_code' => $this->input->post('ifsc_code'),
        'deposit_amount' => $this->input->post('deposit_amount'),
        'transaction_type' => $this->input->post('transaction_type'),
        'account_type' => $this->input->post('acc_type'),
        'transaction_date' => $newDate,
        'dealer_id' => $dealer_session_id,
        'created_date' => date('Y-m-d H:i:s')
    );
	if($post_data['deposit_amount']<=0){
		$this->session->set_flashdata('message','please enter vallid amount !');
		redirect('myDashboardSection');
	}
    $where = array(
        'dealer_id'=>$dealer_session_id
    );
	$dealer_transctn_data = $this->Home_Model->getRowDataFromTable('dealer_wallet',$where);
	if($post_data['transaction_type']=='withdrawal'){
        $wallet_balance = ($dealer_transctn_data['security_amount'] - $dealer_transctn_data['credit_amount']);
			if($wallet_balance < $post_data['deposit_amount']){
			$this->session->set_flashdata('message','You have insufficient balance !');
			redirect('myDashboardSection');
		}
	}
	if(empty($post_data['dealer_bank_trans_id'])){
		$insert_id = $this->Home_Model->insertIntoTable('dealer_bank_transaction',$transaction_data);
			if(!empty($insert_id)){
                    $server = $_SERVER['SERVER_NAME'];
                    if($server != 'localhost'){
                           $this->send_tvs_mail($post_data['transaction_no'],$post_data['transaction_type']);
                       }

				$this->session->set_flashdata('message','Your Transaction has been successfully done');
				redirect('myDashboardSection');
			}	
	}else{
        $update_trans_data = array(
                'bank_name' => $this->input->post('bank_name'),
                'bank_transaction_no' => $this->input->post('transaction_no'),
                'bank_ifsc_code' => $this->input->post('ifsc_code'),
                'deposit_amount' => $this->input->post('deposit_amount'),
                'transaction_type' => $this->input->post('transaction_type'),
                'account_type' => $this->input->post('acc_type'),
                'transaction_date' => $this->input->post('transaction_date'),
                'dealer_id' => $dealer_session_id,
                'updated_at' => date('Y-m-d H:i:s'),
                'approval_status' => 'pending'
            );
		$where = array('id' => $post_data['dealer_bank_trans_id']);
		$update_dealer_bank = $this->Home_Model->updateTable('dealer_bank_transaction',$update_trans_data,$where);
		if(!empty($update_dealer_bank)){
				$this->session->set_flashdata('message','Your Transaction has been successfully Updated');
				redirect('myDashboardSection');
		}
	}
	
}

public function send_tvs_mail($transaction_no,$transaction_type){
    $userdata = $this->session->userdata('user_session');
    $this->load->library('email');
    $customer_mail = $userdata['email'];
    // $customer_mail = 'annushukla100@gmail.com,bhupati.jagi@indicosmic.com';
    $this->email->from($userdata['email'], $userdata['ad_name']);
    $this->email->to('amitdeep@indicosmic.com');
    $this->email->cc('accounts@indicosmic.com');

    $this->email->subject('Dealer '.$transaction_type.' Request');
    $meassage = 'Hello, '.$userdata['ad_name'].' Dealer is requested for '.$transaction_type.' Find Transaction Number is '.$transaction_no ;
    $this->email->message($meassage);
    if ($this->email->send()) {
        $result['status'] = true;
    } else {
        $to = $customer_mail;
        mail($to, 'test', 'Other sent option failed');
        echo $customer_mail;
        show_error($this->email->print_debugger());
        $result['status'] = false;
    }
    // print_r($result);die;
    return $result;
}


public function checkExistTransactionNO(){
    $transaction_no = $this->input->post('trans_no');
    $where = array('bank_transaction_no'=>$transaction_no);
    $dealer_bank_trans_data = $this->Home_Model->getRowDataFromTable('dealer_bank_transaction',$where);
    $response_status = true;
    if(in_array($dealer_bank_trans_data['approval_status'], array('approved','referback','pending'))){
        $response_status = false;
    }
    echo json_encode($response_status);
}

public function SummaryPage(){
	$dealer_id = $this->session->user_session['id'];
    $sap_ad_code = $this->session->user_session['sap_ad_code'];
    if(empty($sap_ad_code)){
        redirect('');
    }
	$this->data['dealer_transactn_data'] = $this->Home_Model->getDealerTransactionData($dealer_id);
    $where = array('dealer_id'=>$dealer_id);
    $dealer_wallet_data = $this->Home_Model->getRowDataFromTable('dealer_wallet',$where);
    $dealer_details = $this->Home_Model->getRowDataFromTable('tvs_dealers',array('id'=>$dealer_id));
	//echo '<pre>';print_r($dealer_details['is_allowed_commission_to_bank']);die('dealer_transactn_data');
    $this->data['wallet_balance'] = ($dealer_wallet_data['security_amount'] - $dealer_wallet_data['credit_amount']);
    $this->data['total_commission'] = !empty($dealer_wallet_data['total_commission'])?$dealer_wallet_data['total_commission']:'';
    $this->data['is_allowed_commission_to_bank'] = !empty($dealer_details['is_allowed_commission_to_bank'])?$dealer_details['is_allowed_commission_to_bank']:'';
    //echo '<pre>';print_r($this->data['is_allowed_commission_to_bank']);die('dealer_transactn_data');
	 // if(strlen($sap_ad_code) < 6){
       $this->load->dashboardTemplate('front/myaccount/summary_page',$this->data);
    // }else{
    //     redirect('dashboard');
    // } 
}

public function updateCommisionMethod(){
   $is_bank_payment =  $this->input->post('is_bank_payment');
   $session_data = $this->session->userdata('user_session');
   $return_array = array(
    'status'=>'FALSE',
    'msg'=>'INVALID METHOD',
    'error_code'=>303
   );
   if(isset($is_bank_payment) && !empty($session_data['id'])){
        $where = array(
            'id'=>$session_data['id']
        );
        $this->db->where('id',$session_data['id']);
        $is_updated = $this->db->update('tvs_dealers',array('is_allowed_commission_to_bank'=>$is_bank_payment));
           $return_array = array(
                'status'=>'FALSE',
                'msg'=>'Data Not Updated',
                'error_code'=>500
               );
        if(!empty($is_updated)){
            $return_array = array(
                'status'=>'TRUE',
                'msg'=>'SUCCESS!',
                'error_code'=>200
           );
        }
   }
   echo json_encode($return_array);
}


public function getDealerBalance(){
	$amount = $this->input->post('amount');
	$dealer_id = $this->session->user_session['id'];
	$dealer_wallet_data = $this->Home_Model->getDealerWallet($dealer_id);
	$status = true;
    $wallet_balance = ($dealer_wallet_data['security_amount'] - $dealer_wallet_data['credit_amount']);
	if(($wallet_balance < $amount)){
		$status = false;
	}
	echo json_encode($status);

}

public function DownloadCsv(){
    $from_date = $this->input->post('from_date');
    $to_date =$this->input->post('to_date');
    $policy_data = $this->Home_Model->getPolicyBYDate($from_date,$to_date);
    $user_session = $this->session->userdata('user_session');
    $sap_ad_code = $user_session['sap_ad_code'];
 // echo "<pre>"; print_r(strlen($sap_ad_code)); echo "</pre>"; die('end of line yoyo');
    $this->load->library('excel');

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Employee Code'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Employee Name'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Policy No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Engine No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Chassis No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Registration No'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Model'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Customer Name');
    $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Customer Address1');
    $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Customer Address2');
    $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Customer Email'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Customer Mobile'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Nomine Name'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Nominee Relation'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Plan Name');
    $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Assistance Company');
    $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Tenure');
    $objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Policy Created Date'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Policy Start Date'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Policy End Date');  
    $objPHPExcel->getActiveSheet()->SetCellValue('U1', 'IC Company');  
    $objPHPExcel->getActiveSheet()->SetCellValue('V1', 'Tenure');  
    $objPHPExcel->getActiveSheet()->SetCellValue('W1', 'Sum Insured');  
    $objPHPExcel->getActiveSheet()->SetCellValue('X1', 'Basic Premium');
    $objPHPExcel->getActiveSheet()->SetCellValue('Y1', 'GST'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('Z1', 'Total Premium'); 
    if(strlen($sap_ad_code) == 5){
    $objPHPExcel->getActiveSheet()->SetCellValue('AA1', 'Dealer Commission');
    $objPHPExcel->getActiveSheet()->SetCellValue('AB1', 'Vehicle Type'); 
    }else{
        $objPHPExcel->getActiveSheet()->SetCellValue('AA1', 'Vehicle Type');
    }

    $objPHPExcel->getActiveSheet()->SetCellValue('AC1', 'Policy Status');
    $rowCount = 2;
    foreach ($policy_data as $row) {
        $reg_no_arr = explode('-',$row['vehicle_registration_no']) ;
           
            if(isset($reg_no_arr[2]) && !empty($reg_no_arr[2]) ){
                $vehicle_type = "Old";
            }else{
                $vehicle_type = "New";
            }

            if($row['policy_status_id']=='5'){
                $policy_status = 'Cancelled';
            }else{
                $policy_status = 'Active';
            }

        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row['employee_code']);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['employee_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['sold_policy_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['engine_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row['chassis_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row['vehicle_registration_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row['model_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row['customer_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row['addr1']);
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row['addr2']);
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row['email']);
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $row['mobile_no']);
        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $row['nominee_full_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $row['nominee_relation']);
        $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $row['plan_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $row['rsa_ic_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $row['rsa_tenure']);
        $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, $row['created_date']);
        $objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, $row['sold_policy_date']);
        $objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, $row['sold_policy_end_date']);
        $objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, $row['pa_ic_name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, $row['pa_tenure']);
        $objPHPExcel->getActiveSheet()->SetCellValue('W' . $rowCount, $row['sum_insured']);
        $objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowCount, $row['sold_policy_price_without_tax']);
        $objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowCount, $row['gst_amount']);
        $objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, $row['sold_policy_price_with_tax']);
        if(strlen($sap_ad_code) == 5){
            $objPHPExcel->getActiveSheet()->SetCellValue('AA' . $rowCount, $row['dealer_commission']);
            $objPHPExcel->getActiveSheet()->SetCellValue('AB' . $rowCount, $vehicle_type);
        }else{
            $objPHPExcel->getActiveSheet()->SetCellValue('AA' . $rowCount, $vehicle_type);
        }

        $objPHPExcel->getActiveSheet()->SetCellValue('AC' . $rowCount, $policy_status);
        $rowCount++;
    }

//     // die;
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
    ob_clean();

            $fileName = 'proposal_data.xlsx';  

            ob_start();
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response =  array(
                    'op' => 'ok',
                    'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
                );

            die(json_encode($response));


}



public function DealerRequestData(){
    $sap_ad_code = $this->session->user_session['sap_ad_code'];
	if ( (strlen($sap_ad_code) ==5) || in_array($sap_ad_code, array('1011591','1010964')) ) {
            $this->load->dashboardTemplate('front/myaccount/dealer_request_data');
        } else {
            redirect('dashboard');
        }
}

public function DealerRequestDataAjax(){
    $requestData = $_REQUEST;

    // echo '<pre>';print_r($requestData);die;
    $where = '';
    $dealerbank_from_date = $requestData['dealerbank_from_date'];
    $dealerbank_to_date = $requestData['dealerbank_to_date'];
    if($dealerbank_to_date!='' && $dealerbank_from_date!=''){

        $where = "AND DATE(created_date) BETWEEN '$dealerbank_from_date' AND '$dealerbank_to_date' ";
    }
        $columns = array(
            0 => 'dealer_id',
            1 => 'bank_transaction_no',
            2 => 'deposit_amount',
            3 => 'bank_name',
            4 => 'created_date',
            5 => 'transaction_type',
            6 => 'approval_status',
        );
        $dealer_session_id = $this->session->userdata('user_session')['id'];
        $dealer_name = $this->session->userdata('user_session')['dealer_name'];

        $sql = "SELECT dbt.* FROM dealer_bank_transaction dbt WHERE dealer_id = '$dealer_session_id' $where order by id desc";
// echo $sql ;die;
        $query = $this->db->query($sql);
        $result = $query->result();


        $totalData = $query->num_rows();

        $data = array();
        $i = 1;
        foreach ($result as $main) {
            $edit_btn='';
        if($main->approval_status =='referback'){
            $edit_btn = '<a href="'.base_url('myDashboardSection').'/'.$main->id.'"><i class="fa fa-edit"></i></a>';
        }            

            $row = array();
            $row[] = $i++;
            $row[] = $dealer_name;
            $row[] = $main->bank_transaction_no;
            $row[] = $main->deposit_amount;
            $row[] = $main->acc_holder_name;
            $row[] = $main->account_type;
            $row[] = $main->transaction_type;
            $row[] = $main->approval_status;
            $row[] = $main->created_date;
            $file_list = $edit_btn;
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

public function TransactionDataGrid(){
        $dealer_session_id = $this->session->userdata('user_session')['id'];
        $sap_ad_code = $this->session->user_session['sap_ad_code'];
        $where = array('dealer_id'=>$dealer_session_id);
        $wallet_data = $this->Home_Model->getRowDataFromTable('dealer_wallet',$where);
        $wallet_balance = ($wallet_data['security_amount'] - $wallet_data['credit_amount']);
        $this->data['wallet_balance'] = $wallet_balance;
        if ( (strlen($sap_ad_code) ==5) || in_array($sap_ad_code, array('1011591','1010964')) ) {
           $this->load->dashboardTemplate('front/myaccount/transaction_master',$this->data);
        }else{
            redirect('dashboard');
        }
}

public function TransactionAjax(){
    $user_session = $this->session->userdata('user_session');

    $requestData = $_REQUEST;
    $where = '';
        $where = "AND MONTH(transaction_date) = MONTH(CURRENT_DATE()) ";
        $where1 = "AND MONTH(dts.`transection_date`) = MONTH(CURRENT_DATE())" ;
        $columns = array(
            0 => 'policy_no',
            1 => 'transection_no',
            2 => 'transection_type',
            3 => 'transection_amount',
            4 => 'transection_purpose',
            5 => 'transection_date',
        );
        $dealer_session_id = $this->session->userdata('user_session')['id'];
        $dealer_name = $this->session->userdata('user_session')['dealer_name'];
        $sql1 ="SELECT dbt.bank_transaction_no,dbt.deposit_amount,dbt.transaction_type,dbt.created_date AS transection_date FROM dealer_bank_transaction AS dbt  WHERE dbt.dealer_id = '$dealer_session_id' $where order by id desc";
         // die($sql1);
        $query1 = $this->db->query($sql1);
        $result1 = $query1->result_array();
        // echo '<pre>';print_r($result1);die('result1');
        $where2 = '';
        if(strlen($user_session['sap_ad_code']) >5){
            $employee_code = $user_session['sap_ad_code'];
            $where2 = 'AND tsp.employee_code = '.$employee_code.'';
        }
        $sql2 = "SELECT dts.transection_no,dts.transection_date,dts.transection_type,dts.transection_amount,dts.transection_purpose,tsp.model_name,tsp.engine_no,tsp.chassis_no,tsp.plan_name,bu.employee_code,tsp.`vehicle_registration_no`,bu.employee_code,CONCAT(bu.f_name,' ',bu.l_name) AS employee_name, CONCAT(tcd.fname,' ',tcd.lname) AS customer_name,tp.plan_type_id
        FROM dealer_transection_statement AS dts 
        LEFT JOIN tvs_sold_policies AS tsp ON dts.transection_no = tsp.transection_no
        LEFT JOIN tvs_customer_details AS tcd ON tsp.customer_id = tcd.id
        LEFT JOIN biz_users AS bu ON tsp.employee_code = bu.employee_code
        LEFT JOIN tvs_plans AS tp ON tsp.plan_id = tp.id
        WHERE dts.dealer_id = '$dealer_session_id' $where2 $where1 order by dts.id desc";
         // die($sql2);
        $query2 = $this->db->query($sql2);
        $result2 = $query2->result_array();
        // echo '<pre>';print_r($result2);die('result2');
        $result = array_merge($result1,$result2);
         if(!empty($result)){
            foreach ($result as $key => $part) {
                   $sort[$key] = strtotime($part['transection_date']);
              }

        array_multisort($sort, SORT_DESC, $result);
        }
        $totalData = sizeof($result);
        $data = array();
        $i = 1;
        $vehicle_type = '';
        foreach ($result as $main) {
            if($main['transaction_type'] == 'deposit'){
                $transaction_type = "cr";
            }else if($main['transaction_type'] == 'withdrawal'){
                $transaction_type = "dr";
            }else {
                $transaction_type = $main['transection_type'];
                
            }

            $reg_no_arr = explode('-',$main['vehicle_registration_no']) ;
           
            if(isset($reg_no_arr[2]) && !empty($reg_no_arr[2]) ){
                $vehicle_type = "Old";
            }elseif(!empty($main['vehicle_registration_no']) ){
                $vehicle_type = "New";
            }
            
            $symbol = ($transaction_type == "dr")?'-':'';
            $transection_purpose = '';
            if(empty($main['transection_purpose']) && $transaction_type == 'cr'){
                $transection_purpose = 'Deposit';
            }else if(empty($main['transection_purpose']) && $transaction_type == 'dr'){
                $transection_purpose = 'Withdrawal';
            }else{
                $transection_purpose = $main['transection_purpose'];
            } 
            $row = array();
            $row[] = $i++;
            $row[] = isset($main['policy_no'])?$main['policy_no']:'';
            $row[] = isset($main['customer_name'])?$main['customer_name']:'';
            $row[] = isset($vehicle_type)?$vehicle_type:'';
            $row[] = isset($main['model_name'])?$main['model_name']:'';
            $row[] = isset($main['engine_no'])?$main['engine_no']:'';
            $row[] = isset($main['chassis_no'])?$main['chassis_no']:'';
            $row[] = isset($main['plan_name'])?$main['plan_name']:'';
            $row[] = isset($main['bank_transaction_no'])?$main['bank_transaction_no']:$main['transection_no'];
            $row[] = isset($main['transection_amount'])?$symbol.$main['transection_amount']:$symbol.$main['deposit_amount'];
            $row[] = $transaction_type;
            $row[] = $transection_purpose;
            $row[] = $main['employee_code'];
            $row[] = $main['employee_name'];
            $row[] = $main['transection_date'];
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


function TransactionDataCSV($from,$to){
    $heading_array = array("Policy No","Customer Name","Vehicle Type","Model","Engine No","Chassis No","Plan Name","Transaction No","Transaction Amount","Transaction Type","Transaction Purpose","Employee Code","Employee Name","Created Date");
    $main_array = array();
    ini_set('memory_limit', '-1');
    array_push($main_array, $heading_array);
    $transaction_data = $this->Home_Model->getTransanctionData($from,$to);
     // echo "<pre>"; print_r($transaction_data); echo "</pre>"; die('end of line yoyo');
    $vehicle_type = '';$i=0;
        foreach ($transaction_data as $main) {
            // echo "<pre>"; print_r($main); echo "</pre>";die;
            if($main['transaction_type'] == 'deposit'){
                $transaction_type = "cr";
            }else if($main['transaction_type'] == 'withdrawal'){
                $transaction_type = "dr";
            }else {
                $transaction_type = $main['transection_type'];
                
            }
            $reg_no_arr = explode('-',$main['vehicle_registration_no']) ;
           
            if(isset($reg_no_arr[2]) && !empty($reg_no_arr[2]) ){
                $vehicle_type = "Old";
            }else{
                $vehicle_type = "New";
            }
            
            $symbol = ($transaction_type == "dr")?'-':'';
            $transection_purpose = '';
            if(empty($main['transection_purpose']) && $transaction_type == 'cr'){
                $transection_purpose = 'Deposit';
            }else if(empty($main['transection_purpose']) && $transaction_type == 'dr'){
                $transection_purpose = 'Withdrawal';
            }else{
                $transection_purpose = $main['transection_purpose'];
            } 
            // $sr_no = $i++;
            $policy_no = isset($main['policy_no'])?$main['policy_no']:'';
            $customer_name = isset($main['customer_name'])?$main['customer_name']:'';
            $vehicle_type = isset($vehicle_type)?$vehicle_type:'';
            $model_name = isset($main['model_name'])?$main['model_name']:'';
            $engine_no = isset($main['engine_no'])?$main['engine_no']:'';
            $chassis_no = isset($main['chassis_no'])?$main['chassis_no']:'';
            $plan_name = isset($main['plan_name'])?$main['plan_name']:'';
            $bank_transaction_no = isset($main['bank_transaction_no'])?$main['bank_transaction_no']:$main['transection_no'];
            $transection_amount = isset($main['transection_amount'])?$symbol.$main['transection_amount']:$symbol.$main['deposit_amount'];
            $transaction_type = $transaction_type;
            $transection_purpose = $transection_purpose;
            $employee_code = $main['employee_code'];
            $employee_name = $main['employee_name'];
            $transection_date = $main['transection_date'];

            $array_val = array($policy_no,$customer_name,$vehicle_type,$model_name,$engine_no,$chassis_no,$plan_name,$bank_transaction_no,$transection_amount,$transaction_type,$transection_purpose,$employee_code,$employee_name,$transection_date);
        array_push($main_array, $array_val);
//echo '<pre>'; print_r($main_array); 

        }
        // die('annu');
        $csv_file_name = "transanction_data-" . date('y-m-d') . ".csv";
        echo array_to_csv($main_array, $csv_file_name);
}


}


?>