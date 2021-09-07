<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Rsa_cover extends CI_Controller {

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
        $where = array('is_active'=>1,'plan_type_id'=>2);
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
        $where = array('is_active'=>1);
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
            $this->load->dashboardTemplate('front/myaccount/rsa_cover', $this->data);
        }else{
            redirect('');
        }
    } 
    public function planDetails(){
            $plan_type_id = $this->input->post('plan_type_id');
            $plan_id = $this->input->post('plan_id');
            $dms_ic_id = $this->input->post('dms_ic_id');
            $where = array('dms_ic_id' =>$dms_ic_id,'is_active' =>1);
            $ic_details = $this->HomeMdl->getRowDataFromTable('dms_ic_and_pa_ic_mapping',$where);
            $ic_id = isset($ic_details['pa_ic_id'])?$ic_details['pa_ic_id']:10;
            $ic_id = ($ic_id == 2)?10:$ic_id;
            $where = array(
                'plan_type_id'=>$plan_type_id,
                'ic_id'=>$ic_id,
                'is_active'=>1
            );
            $key = 'plan_amount';
            $plan_details = $this->HomeMdl->getDataFromTableOrderByDesc('tvs_plans',$key,$where);
            $plan_details_kotak = $this->HomeMdl->getKotakPlansOnly($plan_type_id);
             // echo '<pre>';print_r($plan_details_kotak);die('hello');
             $plan_details = array_merge($plan_details,$plan_details_kotak);
                 // echo '<pre>';print_r($plan_details);die('here');
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

            foreach ($plan_details as $plan_detail) {
                
                $checked = ($plan_detail['id'] == $plan_id)?'checked':'';
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
                          <td><input type="radio" name="plan" id="{$plan_code}" value="{$id}" data-plan="{$plan_amount}" {$checked}></td>
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

    public function count_amount_ajax() {

        $val = $_POST['jsonval'];

        if (!empty($val)) {
            $sql = "select  tp.* ,sum(tpa.price_with_gst)final_amount from tvs_sold_policies tp left join tvs_plan_india_assistance tpa  on tp.plan_id=tpa.id  where tp.id IN($val)";
            $query = $this->db->query($sql);
            $values = $query->result();

            $sql = "SELECT min(created_date)  as created FROM `tvs_sold_policies` WHERE id IN($val)";
            $query = $this->db->query($sql);
            $result = $query->result();


            $created = explode(" ", $result[0]->created);
            $data['created'] = $created[0];
            $data['amount'] = $values[0]->final_amount;
        } else {
            $data['created'] = '';
            $data['amount'] = '';
        }
        echo json_encode($data);
    }


    function generate_pay_ing_slip() {
        $user_session = $this->session->userdata('user_session');
        if (empty($user_session)) {
            redirect(base_url());
        } else {
            $bankmaster_details = $this->HomeMdl->getBankdetails();
            $data['bankmaster_details'] = $bankmaster_details;
            $this->load->dashboardTemplate('front/dashboard/pay_in_slip', $data);
        }
    }

    function view_pdf_policy_data() {
        $post_data = $this->input->post();
        $ic_list = $post_data['counts_policy_id'];
        if ($ic_list == "") {
            $policy_id = "";
        } else {
            $policy_id = $ic_list;
        }
        $amount = $post_data['amount'];
        $list = explode(',', $ic_list);
        $paying_slip_number = date('dmyis');
        if ($ic_list != "") {
            $policy_id = trim($payment_slip->sold_policy_id);
            $sql = "select CONCAT(tcd.fname,' ',lname)AS full_name,tsp.sold_policy_price_with_tax as total_amount,tsp.sold_policy_no,tsp.sold_policy_effective_date as policy_start_date,tsp.sold_policy_end_date as policy_end_date  FROM tvs_sold_policies as tsp "
                . " LEFT JOIN tvs_customer_details as tcd "
                . " ON tsp.customer_id = tcd.id "
                . " WHERE tsp.id IN($ic_list)";
            $query_data = $this->db->query($sql);
            $result_data = $query_data->result_array();
            $html_table = "";
            foreach ($result_data as $value) {
                $html_table .= '<tr>
                                            <td style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">' . $value['sold_policy_no'] . '</td>
                                            <td style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">' . $value['full_name'] . '</td>
                                            <td style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">' . $value['total_amount'] . '</td>
                                            <td style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">' . $value['policy_start_date'] . '</td>
                                            <td style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">' . $value['policy_end_date'] . '</td>
                                            </tr>';
            }
        }
        ob_start();
        $this->load->library('Tcpdf/Tcpdf.php');
        $this->load->library('Ciqrcode');
        $pdf = new TCPDF();
        $pdf->SetFont('helvetica', '', 7, '', 'default', true);

        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        $html = '
<style>
    @page { margin: 400px 25px; }
</style>

<table width="100%"  cellpadding="2" border="0" cellspacing="2" style="margin-bottom:9px;-webkit-print-color-adjust: exact; ">                                        <thead>
                                          <tr>
                                            <th style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">Policy No</th>
                                            <th style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">Full Name</th>
                                            <th style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">Policy Amount</th>
                                            <th style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">Policy Start Date</th>
                                            <th style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">Policy End Date</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                       ' . $html_table . '
                                        </tbody>
                                        <footer>
                                            <tr>
                                                <td colspan="2" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"></td>
                                                <td style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">' . $amount . '</td>
                                                <td style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"></td>
                                                <td style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"></td>
                                            </tr>
                                        </footer>
                                      </table>';

        $pdf->AddPage();
        $pdf->writeHtml($html);
        ob_clean();
        $pdf->Output("list.pdf", 'D');
    }

    function view_generated_payslip() {
        $this->load->dashboardTemplate('front/dashboard/view_generated_payslip');
    }

    function view_generated_payslip_ajax() {
        $requestData = $_REQUEST;

        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'paying_slip_number',
            1 => 'created_date',
            2 => '',
            3 => '',
            4 => '',
            5 => '',
            6 => '',
        );
        $session_data = $this->session->userdata('user_session');
        $user_id = $session_data['id'];
        $limit = ' LIMIT ' . $start . ', ' . $length . '';
        $sql = "SELECT tsp.id,tsp.product_type_name,tsp.plan_name,tsp.sold_policy_no,tsp.engine_no,tsp.chassis_no,tsp.created_date,tsp.policy_status_id,tcd.fname,tpd.created_date,tpd.payin_slip_no FROM tvs_sold_policies AS tsp"
            . " LEFT JOIN tvs_customer_details AS tcd"
            . " ON tcd.id = tsp.customer_id "
            . "LEFT JOIN tvs_payment_details AS tpd ON tpd.id = tsp.payment_detail_id"
            . " WHERE tsp.user_id = $user_id  and policy_status_id=6 Group By tpd.payin_slip_no ";

        $query = $this->db->query($sql);
        $totalData = $query->num_rows();

        $totalFiltered = $totalData;
//        $sql = "$sql WHERE  1 = 1";


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
            $show_endrosement = 'block';

            $row = array();
            $row[] = $i++;
            $row[] = $main->payin_slip_no;
            $row[] = $main->created_date;

            $payslip_button = '<a href="' . base_url() . 'view_pdf_paying_slip/' . $main->payin_slip_no . '" class="btn btn-info">Payslip-Pdf Pdf</a><br/>' . $endrose_button;
            $row[] = $payslip_button;

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

    public function payslip_insert_data() {
        $ic_list = $this->input->post('ic_list');
        $total_policy = $this->input->post('total_policy');
        $amount = $this->input->post('amount');
        $dealer_bank_id = $this->input->post('bank_id');
        $ifsc_code = $this->input->post('ifsc_code');
        $bank_city = $this->input->post('bank_city');
        $cheque_no = $this->input->post('cheque_no');
        $payment_mode = $this->input->post('payment_mode');

        $created_at = $date;
        $list = explode(',', $ic_list);
        $paying_slip_number = date('dmyis');

        foreach ($list as $key) {
            $query = $this->db->query("SELECT id,plan_id,payment_detail_id FROM tvs_sold_policies  WHERE id=$key");
            $result = $query->row();
            $payment_detail_id = $result->payment_detail_id;
            $arrayName = array(
                'payin_slip_no' => $paying_slip_number,
                'cheque_no' => $cheque_no,
                'payment_type' => "Dealer Cheque",
                "cheque_no" => $cheque_no,
                "cheque_date" => date('Y-m-d'),
                'payment_date' => date('Y-m-d H:i:s'),
                'bank_id' => $dealer_bank_id,
                'payment_status' => "Cheque Generated"
            );
            $this->db->where("id", $payment_detail_id);
            $this->db->update("tvs_payment_details", $arrayName);

            $array_sold_policy_data = array(
                "policy_status_id" => 6
            );
            $this->db->where("id", $key);
            $this->db->update("tvs_sold_policies", $array_sold_policy_data);
        }
        $array_inser = array(
            "sold_policy_id" => $ic_list,
            "payment_mode" => $payment_mode,
            "cheque_no" => $cheque_no,
            "cheque_date" => date('Y-m-d'),
            "bank_id" => $dealer_bank_id,
            "paying_slip_no" => $paying_slip_number,
            "branch_city" => $bank_city,
            "payment_amount" => $amount,
            "user_id" => $user_id,
            "is_payment" => 1
        );

        $this->HomeMdl->insertIntoTable('tvs_payment_slip', $array_inser);
        $return_data['error_status'] = 'true';
        $return_data['paying_slip_number'] = $paying_slip_number;
        $return_data['message'] = "data_save";
        echo json_encode($return_data);
    }

    function view_pdf_paying_slip($paying_slip_number) {

        $query_data = $this->db->query("select * from tvs_payment_slip where paying_slip_no=$paying_slip_number");
        $result_data = $query_data->row();

        $sold_policy_id = $result_data->sold_policy_id;
        $create_date = $result_data->create_date;
        $cheque_no = $result_data->cheque_no;
        $cheque_date = $result_data->cheque_date;
        $branch_city = $result_data->branch_city;
        $payment_mode = $result_data->payment_mode;
        $payment_amount = $result_data->payment_amount;


        $query_data = $this->db->query("select sp.id, sp.sold_policy_no,sp.sold_policy_date,sp.sold_policy_end_date,sp.sold_policy_price_with_tax,sp.plan_name,cd.fname,cd.mobile_no
from tvs_sold_policies sp left join tvs_customer_details cd on sp.customer_id=cd.id
                where sp.id in ($sold_policy_id)");
        $result_data = $query_data->result_array();
        $html_table = "";
        foreach ($result_data as $value) {
            $html_table .= '<tr>
                                            <td style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">' . $value['sold_policy_no'] . '</td>
                                            <td style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">' . $value['fname'] . '</td>
                                            <td style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">' . $value['sold_policy_price_with_tax'] . '</td>
                                            <td style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">' . $value['sold_policy_date'] . '</td>
                                            <td style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">' . $value['sold_policy_end_date'] . '</td>
                                            </tr>';
        }




        ob_start();
        $this->load->library('Tcpdf/Tcpdf.php');
        $this->load->library('Ciqrcode');
        $pdf = new TCPDF();
        $pdf->SetFont('helvetica', '', 7, '', 'default', true);

        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        $html = '
<table width="100%" cellspacing="0" cellpadding="2">
  <tbody>
    <tr style="background-color: #999;">
    <td align="center" style="font-weight: bold; padding:5px; font-size: 19px;">PAY IN SLIP</td>
    </tr>
  </tbody>
</table>
<table width="100%" cellspacing="0" cellpadding="2">
  <tbody>
    <tr>
    <td align="left" style="font-weight: lighter;  ">Receipt No :' . $paying_slip_number . '</td>
    </tr>
  </tbody>
</table>
<table width="100%" cellspacing="0" cellpadding="2">
  <tbody>
    <tr>
    <td align="right" style="font-weight: lighter;  ">Receipt Date : ' . date('d/m/Y', strtotime($create_date)) . '</td>
    </tr>
  </tbody>
</table>
<table width="100%" cellpadding="2" border="1" cellspacing="2" style="font-size: 12px; margin-bottom: 30px;">
<tbody>
<tr>
 <th style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">Policy No</th>
                                            <th style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">Full Name</th>
                                            <th style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">Policy Amount</th>
                                            <th style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">Policy Start Date</th>
                                            <th style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">Policy End Date</th>
</tr>

 ' . $html_table . '
</tbody>
</table>
<br>
<br>
<br>
<table width="100%" cellpadding="2" border="1" cellspacing="2"  style="font-size: 12px; margin-bottom: 30px;">
<tbody>
<tr>
<td><b style="font-weight: bold;">Sr No. </b></td>
<td><b style="font-weight: bold;">NEFT No</b></td>
<td><b style="font-weight: bold;">NEFT Date</b></td>
<td><b style="font-weight: bold;">Total Amount (Rs)</b></td>
</tr>
<tr>
<td><b style="font-weight: bold;"> 1</b></td>
<td>' . $cheque_no . '</td>
<td>' . date('d-M-Y', strtotime($cheque_date)) . '</td>
<td>' . $payment_amount . '</td>
</tr>

</tbody>
</table>



<table width="100%" cellpadding="2" border="0" cellspacing="0" style="font-size: 12px">
<tbody>
<tr>
 <td>

   <ul style="list-style: disc; list-style-position: outside;font-family:arial, sans-serif; ">
     <li>
    This is a computer generated receipt and does not require a signature.</li> <br>
<li>Upon issuance of this Receipt, all previously issued temporary receipts, if any, related to this Policy shall be considered <span style="margin-left: 10px;">&nbsp;&nbsp;&nbsp;null or void.</span></li> <br>
<li> Amounts received by cheque shall be subjected to realisation.</li> <br>
<li>Any amount received in excess of the Premium is being/shall be refunded by the Company</li> <br>
     </li>
   </ul>
   <br>
   <b>GSTIN : 27AAECI3370G1ZN</b>
 </td>
</tr>
</tbody>
</table>
';

        $pdf->AddPage();
        $pdf->writeHtml($html);
        ob_clean();
        $pdf->Output("$paying_slip_number.pdf", 'I');
    }

    function PayingSlipjax() {
        $requestData = $_REQUEST;
        //print_r($requestData);exit;
// print_r($_REQUEST['search']['value']);die('post');
//$search_value = $_REQUEST['search']['value'];
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
        $limit = ' LIMIT ' . $start . ', ' . $length . '';
        $sql = "SELECT tsp.id,tsp.product_type_name,tsp.plan_name,tsp.sold_policy_no,tsp.engine_no,tsp.chassis_no,tsp.created_date,tcd.fname FROM tvs_sold_policies AS tsp"
            . " LEFT JOIN tvs_customer_details AS tcd "
            . " ON tcd.id = tsp.customer_id "
            . " WHERE tsp.user_id = $user_id  and policy_status_id=3";

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
            $show_endrosement = 'block';

            $endrose_button = '';
            if ($end_date < $current_date) {
                $endrose_button = '';
            }

            $row = array();
            $row[] = $i++ . '<input type="checkbox" name="check_data[]" class="check_data policy_price" value=" ' . $main->id . '" data-policy_price="' . $main->id . '" >';
            $row[] = $main->product_type_name;
            $row[] = $main->plan_name;
            $row[] = $main->sold_policy_no;
            $row[] = $main->fname;
            $row[] = $main->engine_no;
            $row[] = $main->chassis_no;
            $row[] = $main->created_date;

            /* $file_list = '<a href="' . base_url() . 'download_kotak_lite_pdf/' . $main->id . '" class="btn btn-info">Download Pdf</a><br/>' . $endrose_button;
              $row[] = $file_list; */

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

    function CheckExistEngineNo() {
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
                    $customer_and_vehicle_details = array(
                        'product_type' => 1,
                        'engine_no' => trim($policy_data['vehicle']['engine_no']),
                        'chassis_no' => trim($policy_data['vehicle']['chassis_no']),
                        'manufacturer' => 11,
                        'model' => trim($policy_data['vehicle']['model_name']),
                        'Insurance_Company_name' => trim($policy_data['Insurance_Company_name']),
                        'insurance_company_id' => trim($policy_data['insurance_company_id']),
                        'model_id' => trim($policy_data['vehicle']['model_id']),
                        'odometer_readng' => trim($policy_data['vehicle']['odometer_readng']),
                        'fuel_type' => trim($policy_data['vehicle']['fuel_type']),
                        'mfg_date' => trim($policy_data['vehicle']['mfg_date']),
                        'color' => trim($policy_data['vehicle']['color']),
                        'registration_no' => trim($policy_data['vehicle']['registration_no']),
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

    // function CheckExistEngineNo() {
    //     $engine_no = $this->input->post('vehicle_detail');
    //     if (!empty($engine_no)) {
    //         $vehicle_detail = $engine_no;
    //         $where = array('engine_no' => $engine_no);
    //         $status = $this->HomeMdl->checkDuplicatePolicy($vehicle_detail);
    //         $end_date = date("d/M/Y", strtotime($fetch_exist_data['end_date']));
    //         $explode_end_date = explode(' ', $fetch_exist_data['end_date']);
    //         if ($status) {
    //             $result['status'] = 'exist';
    //             $result['end_date'] = '';
    //         } else {
    //             $policy_data = $this->getTvsRsaPolicyData(strtoupper($engine_no));
    //             // echo '<pre>'; print_r($policy_data);die('hello');
    //             if (!empty($policy_data)) {
    //                 $result['status'] = 'new_policy';
    //                 $regis_no = $policy_data['Owner']['vehicle']['registration_no'];
    //                 $regis_no = explode('-', $regis_no);
    //                 $name = $policy_data['Owner']['customer']['insured_name'];
    //                 $insured_nominee_name = $policy_data['Owner']['customer']['insured_nominee_name'];
    //                 $name = explode(' ', $name);
    //                 $fname = $name[0];
    //                 $lname = $name[2];
    //                 $customer_and_vehicle_details = array(
    //                     'product_type' => 1,
    //                     'engine_no' => trim($policy_data['Owner']['vehicle']['engine_no']),
    //                     'chassis_no' => trim($policy_data['Owner']['vehicle']['chassis_no']),
    //                     'manufacturer' => 11,
    //                     'model' => trim($policy_data['Owner']['vehicle']['model_name']),
    //                     'Insurance_Company_name' => trim($policy_data['Owner']['Insurance_Company_name']),
    //                     'insurance_company_id' => trim($policy_data['Owner']['insurance_company_id']),
    //                     'model_id' => trim($policy_data['Owner']['vehicle']['model_id']),
    //                     'odometer_readng' => trim($policy_data['Owner']['vehicle']['odometer_readng']),
    //                     'fuel_type' => trim($policy_data['Owner']['vehicle']['fuel_type']),
    //                     'mfg_date' => trim($policy_data['Owner']['vehicle']['mfg_date']),
    //                     'color' => trim($policy_data['Owner']['vehicle']['color']),
    //                     'registration_no' => trim($policy_data['Owner']['vehicle']['registration_no']),
    //                     'reg1' => empty($regis_no[0])?trim($policy_data['Owner']['state_id']):trim($regis_no[0]),
    //                     'reg2' => trim($regis_no[1]),
    //                     'reg3' => trim($regis_no[2]),
    //                     'reg4' => trim($regis_no[3]),
    //                     'first_name' => trim($fname),
    //                     'last_name' => trim($lname),
    //                     'insured_nominee_name' => trim($insured_nominee_name),
    //                     'email' => trim($policy_data['Owner']['customer']['insured_email_id']),
    //                     'mobile_no' => trim($policy_data['Owner']['insured_mobile_no']),
    //                     'cust_addr1' => trim($policy_data['Owner']['customer']['insured_address1']),
    //                     'cust_addr2' => trim($policy_data['Owner']['customer']['insured_address2']),
    //                     'state' => trim($policy_data['Owner']['customer']['insured_state']),
    //                     'city' => trim($policy_data['Owner']['customer']['insured_city']),
    //                     'pin' => trim($policy_data['Owner']['customer']['insured_pincode']),
    //                     'product_type_id' => 1
    //                 );
    //                 $result['data'] = $customer_and_vehicle_details;
    //                 // echo '<pre>'; print_r($customer_and_vehicle_details);die('here');
    //                $this->session->set_userdata('dms_response', $policy_data);
    //             } else {
    //                 $result['status'] = 'no_response';
    //             }
    //         }
    //         echo json_encode($result);
    //     }
    // }

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

     public function fetch_model() {
       $model =  $this->input->post('model');
       $model = isset($model)?$model:'';
       // echo '<pre>'; print_r($post_data);die('hello');
        $policyid= $this->input->post('policyid');
        $policy_data = $this->HomeMdl->getPolicyById($policyid);
        // echo '<pre>';print_r($policy_data);die;
        $selected_model = $policy_data['model_name'];
        $models = $this->getDataFromTableWithOject('tvs_model_master');
        $data['html'] = "<option value='' >SELECT MODEL</option>";
        if (!empty($models)) {
            foreach ($models as $row) {
                // if($row->model_id == 8){
                //     continue;
                // }
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

    function getDataFromTableWithOject($table_name = null, $where = null) {
        if ($where != null) {
            $this->db->where($where);
        }
        return $result = $this->db
            ->from($table_name)
            ->get()
            ->result();
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

function GeneratedPolicyData() {

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
            } else {

               $where = array('id'=>$post_data['plan']);
               $plan_details  = $this->HomeMdl->getRowDataFromTable('tvs_plans',$where);
               $user_data = $this->session->userdata('user_session');
               $dealer_code = (strlen($user_data['sap_ad_code']) >5)?$user_data['dealer_code'] : $user_data['sap_ad_code'];
                $where =array('dealer_id'=>$user_data['id']);
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
                    //echo '<pre>'; print_r($insert_customer_detail);die('hello');


                        $inserted_customer_detail = $this->db->insert('tvs_customer_details', $insert_customer_detail);
                        $customer_detail_last_id = $this->db->insert_id();
                        // $customer_detail_last_id = 1;
                        if ($customer_detail_last_id) {
                            $result_sold = $this->GeneratePolicyNo();
                            $date_result = $this->StartEndDate($post_data,$plan_details);
                            $effective_date = $date_result['effective_date'];
                            $end_date = $date_result['end_date'];
                            $selected_date = (!empty($post_data['start_date']) && isset($post_data['start_date']) ) ? $post_data['start_date'] : date('Y-m-d');
                            $pa_effective_date = date('Y-m-d', strtotime($selected_date . "0 day")). ' 00:00:01';
                            $pa_end_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime($selected_date . "-1 day")) . "1 year")). ' 23:59:59';
                            $model_name = $this->getMakeModelName('model', $model_id);
                            $transection_no = $this->getRandomNumber('16'); 
                            if(in_array($post_data['plan'], array(51,52))){
                            $state_id = ($city_id == 470)?$city_id:$state_id;
                                $where = array('ic_id'=>$ic_id);
                                $master_policy_details = $this->HomeMdl->getMasterPolicyDetailByState($state_id);
                            }else{
                                 $where = array(
                                    'dealer_code'=>$dealer_code,
                                    'dms_ic_id' => $post_data['dms_ic_id']);
                                 $getic_map = $this->HomeMdl->getRowDataFromTable('dms_ic_and_pa_ic_mapping',$where);
                                 $today_date = date('Y-m-d');
                             
                              if(!empty($getic_map['pa_ic_id'])){
                                $pa_ic_id = ($getic_map['pa_ic_id'] == 2)?10:$getic_map['pa_ic_id'];
                                        if($pa_ic_id == 10){
                                            $where = array('start_date'=>date('Y-m-d'));
                                            $master_policy_details = $this->HomeMdl->getRowDataFromTable('tvs_oriental_master_policy',$where);
                                            if(empty($master_policy_details)){
                                               $this->session->set_flashdata('message', 'Master Policy Not Found Please Contact To Tech Team.');
                                                redirect('generate_policy');
                                            }
                                            $master_policy_details['mp_start_date'] = $master_policy_details['start_date'];
                                            $master_policy_details['mp_end_date'] = $master_policy_details['end_date'];
                                            $master_policy_details['ic_id'] = 10;
                                        }else{   
                                            $where = array('ic_id' => $pa_ic_id);
                                            $master_policy_details = $this->HomeMdl->getRowDataFromTable('ic_pa_master_policy_nos',$where);
                                        }
                                 }else{
                                    $where = array(
                                        'dealer_code'=>$dealer_code
                                    );
                                    $dealer_ic_details = $this->HomeMdl->getRowDataFromTable('tvs_dealers_dmsic_logic',$where);
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
                                            break;
                                        default:
                                        $ic_id = 10;
                                            break;
                                        }
                                     if($ic_id == 10){
                                            $where = array('start_date'=>date('Y-m-d'));
                                            $master_policy_details = $this->HomeMdl->getRowDataFromTable('tvs_oriental_master_policy',$where);
                                            if(empty($master_policy_details)){
                                               $this->session->set_flashdata('message', 'Master Policy Not Found Please Contact To Tech Team.');
                                                redirect('generate_policy');
                                            }
                                            $master_policy_details['mp_start_date'] = $master_policy_details['start_date'];
                                            $master_policy_details['mp_end_date'] = $master_policy_details['end_date'];
                                            $master_policy_details['ic_id']=10;
                                        }else{
                                             $where = array('ic_id'=>$ic_id);
                                             $master_policy_details = $this->HomeMdl->getRowDataFromTable('ic_pa_master_policy_nos',$where);
                                        }
                                 }
                             }
                            
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
                                                'policy_id'=>$inserted_id,
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
                                                'policy_id'=>$inserted_id,
                                                'transection_no' => $transection_no,
                                                'transection_type'=>'cr',
                                                'transection_amount'=>$plan_details['dealer_commission'],
                                                'transection_purpose'=>'Commission'
                                            );
                                            $this->HomeMdl->insertIntoTable('dealer_transection_statement',$data);
                                        }
                            }
                            //$this->sendSms($mobile_no, $inserted_id);
                            $domainName = $_SERVER['HTTP_HOST'];
                            
                           if(in_array($post_data['plan'], array(51,52))){
                                $this->data['ic_pdf'] = 'download_kotak_lite_pdf';
                                $this->data['inserted_id'] = $inserted_id;
                                if ($domainName != 'localhost' && !empty($post_data['email'])) {
                                    $this->MailSoldPolicyPdf($inserted_id,2);                                    
                                }
                                $this->load->dashboardTemplate('front/myaccount/success', $this->data);
                            }else{
                            if(!empty($getic_map['pa_ic_id'])){
                                if ($domainName != 'localhost' && !empty($post_data['email'])) {
                                    $this->MailSoldPolicyPdf($inserted_id,$getic_map['pa_ic_id']);
                                }
                                switch ($master_policy_details['ic_id']) {
                                    case 5:
                                    $this->data['ic_pdf'] = 'download_il_lite_pdf';
                                    $this->data['inserted_id'] = $inserted_id;
                                        $this->load->dashboardTemplate('front/myaccount/success', $this->data);
                                       // header('location: download_il_lite_pdf/' . $inserted_id . '');
                                        break;
                                    case 8:
                                        $this->data['ic_pdf'] = 'download_reliance_policy';
                                        $this->data['inserted_id'] = $inserted_id;
                                            $this->load->dashboardTemplate('front/myaccount/success', $this->data);
                                           // header('location: download_il_lite_pdf/' . $inserted_id . '');
                                        break;
                                        
                                    case 9:
                                        $this->data['ic_pdf'] = 'download_tata_lite_pdf';
                                        $this->data['inserted_id'] = $inserted_id;
                                        $this->load->dashboardTemplate('front/myaccount/success', $this->data);
                                       // header('location: download_tata_lite_pdf/' . $inserted_id . '');
                                        break;
                                    case 12:
                                        $this->data['ic_pdf'] = 'download_bagi_lite_pdf';
                                        $this->data['inserted_id'] = $inserted_id;
                                        $this->load->dashboardTemplate('front/myaccount/success', $this->data);
                                        //header('location: download_bagi_lite_pdf/' . $inserted_id . '');
                                        break;
                                    case 13:
                                            $this->data['ic_pdf'] = 'download_liberty_policy';
                                            $this->data['inserted_id'] = $inserted_id;
                                            $this->load->dashboardTemplate('front/myaccount/success', $this->data);
                                            //header('location: download_bagi_lite_pdf/' . $inserted_id . '');
                                        break;
                                    default:
                                        $this->data['ic_pdf'] = 'download_OICL_pdf';
                                        $this->data['inserted_id'] = $inserted_id;
                                        $this->load->dashboardTemplate('front/myaccount/success', $this->data);
                                        // header('location: download_kotak_lite_pdf/' . $inserted_id . '');
                                        break;
                                }
                             }else{
                                $where = array(
                                    'dealer_code'=>$dealer_code
                                );
                                $dealer_ic_details = $this->HomeMdl->getRowDataFromTable('tvs_dealers_dmsic_logic',$where);
                                switch ($dealer_ic_details['pa_ic_mapping']) {
                                    case 'OICL':
                                    case 'Universal Logic':
                                        $pdf_ic_id = 10;
                                        $this->data['ic_pdf'] = 'download_OICL_pdf';
                                        break;
                                    case 'TAGIC':
                                        $pdf_ic_id = 9;
                                        $this->data['ic_pdf'] = 'download_tata_lite_pdf';
                                        break;
                                    case 'BAGI':
                                        $pdf_ic_id = 12;
                                        $this->data['ic_pdf'] = 'download_bagi_lite_pdf';
                                        break;
                                    case 'ICICIL':
                                        $pdf_ic_id = 5;
                                        $this->data['ic_pdf'] = 'download_icici_policy';
                                        break;
                                    case 'RGL':
                                        $pdf_ic_id = 8;
                                        $this->data['ic_pdf'] = 'download_reliance_policy';
                                        break;
                                    case 'LGI':
                                        $pdf_ic_id = 13;
                                        $this->data['ic_pdf'] = 'download_liberty_policy';
                                        break;
                                    default:
                                        $pdf_ic_id = 10;
                                        $this->data['ic_pdf'] = 'download_OICL_pdf';
                                        break;
                                }

                                $this->data['inserted_id'] = $inserted_id;
                                if ($domainName != 'localhost' && !empty($post_data['email'])) {
                                            $this->MailSoldPolicyPdf($inserted_id,$pdf_ic_id);
                                        }
                                $this->load->dashboardTemplate('front/myaccount/success', $this->data);
                             }
                            }
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
               $dealer_code = (strlen($user_data['sap_ad_code']) >5)?$user_data['dealer_code'] : $user_data['sap_ad_code'];
               $where = array('id'=>$post_data['plan']);
               $plan_details = $this->HomeMdl->getDataFromTable('tvs_plans',$where);
                $dob = date("Y-m-d", strtotime($this->input->post('dob')));
               $update_customer_detail = array(
                    'fname' => $this->input->post('first_name'),
                    'lname' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'mobile_no' => $this->input->post('mobile_no'),
                    'dob' => $dob,
                    'addr1' => $this->input->post('cust_addr1'),
                    'addr2' => $this->input->post('cust_addr2'),
                    'state' => $this->input->post('state'),
                    'city' => $this->input->post('city'),
                    'state_name' => $this->input->post('state'),
                    'city_name' => $this->input->post('city'),
                    'pincode' => $this->input->post('pin'),
                    'gender' => $post_data['gender'],
                    'nominee_full_name' => $post_data['nominee_full_name'],
                    'nominee_relation' => $nominee_relation,
                    'nominee_age' => $post_data['nominee_age'],
                    'appointee_full_name' => isset($post_data['appointee_full_name']) ? $post_data['appointee_full_name'] : '',
                    'appointee_relation' => isset($post_data['appointee_relation']) ? $post_data['appointee_relation'] : '',
                    'appointee_age' => isset($post_data['appointee_age']) ? $post_data['appointee_age'] : '',
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
                    $this->MailSoldPolicyPdf($policy_id,$pa_ic_id);
                }
                switch ($pa_ic_id) {
                    case 2:
                        $this->data['ic_pdf'] = 'download_kotak_lite_pdf';
                        break;
                    case 5:
                        $this->data['ic_pdf'] = 'download_il_lite_pdf';
                        break;
                    case 8:
                        $this->data['ic_pdf'] = 'download_reliance_policy';
                        break;
                    case 9:
                        $this->data['ic_pdf'] = 'download_tata_lite_pdf';
                        break;
                    case 10:
                        $this->data['ic_pdf'] = 'download_OICL_pdf';
                        break;
                    case 12:
                        $this->data['ic_pdf'] = 'download_bagi_lite_pdf';
                        break;
                    case 13:
                        $this->data['ic_pdf'] = 'download_liberty_policy';
                        break;
                    default:
                        $this->data['ic_pdf'] = 'download_OICL_pdf';
                        break;
                }
                $this->data['inserted_id'] = $policy_id;
                $this->data['endorsement'] = 'yes';
                $this->load->dashboardTemplate('front/myaccount/success', $this->data);
            }
        // }
    }
}


    function StartEndDate($post_data,$plan_details){
     if($post_data['plan_type'] == 1){
        $selected_date = $post_data['start_date'] ;
        $start_date = (!empty($selected_date) && isset($selected_date) ) ? $selected_date : date('Y-m-d');
         if($plan_details['rsa_tenure'] == 2){
                        $result['effective_date'] = date('Y-m-d', strtotime($start_date . "0 day")) . ' 00:00:01';
                        $result['end_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "-1 day")) . "2 year")) . ' 23:59:59';
                        }else{
                            $result['effective_date'] = date('Y-m-d', strtotime($start_date . "0 day")) . ' 00:00:01';
                            $result['end_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "-1 day")) . "1 year")) . ' 23:59:59';
                            }
    }else if($post_data['plan_type'] == 2){
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

    function MailSoldPolicyPdf($inserted_id,$pa_ic_id) {
        switch ($pa_ic_id) {
                    case 2:
                        $policypdf_obj = $this->DownloadKotakFullPolicy($inserted_id);
                        break;
                    case 5:
                        $policypdf_obj = $this->ICICI_full_Pdf($inserted_id);
                        break;
                    case 8:
                        $policypdf_obj = $this->ReliancePDF($inserted_id);
                        break;                      
                    case 9:
                    $policypdf_obj = $this->DownloadTataFullPolicy($inserted_id);
                        break;
                    case 10:
                    $policypdf_obj = $this->DownloadOrientalPdf($inserted_id);
                        break;
                    case 12:
                        $policypdf_obj = $this->DownloadBhartiFullPolicy($inserted_id);
                        break;
                    case 13:
                        $policypdf_obj = $this->LibertyGeneral($inserted_id);
                        break;                      
                        
                   
                }
        $status = $this->MailAttachments($policypdf_obj, $inserted_id);

    }

function ReliancePDF($id){

$rsa_policy_data = $this->HomeMdl->getPolicyById($id);
  // echo "<pre>"; print_r($rsa_policy_data); echo "</pre>"; die('end of line yoyo');
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
$plan_id = isset($rsa_policy_data['plan_id']) ? $rsa_policy_data['plan_id'] : '--';
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
$created_date = isset($rsa_policy_data['created_at']) ? $rsa_policy_data['created_at'] : '0000-00-00 00:00:00';

// echo "<pre>"; print_r($issue_date_html); echo "</pre>"; die('end of line yoyo');
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
$mobile_no = isset($rsa_policy_data['mobile_no']) ? $rsa_policy_data['mobile_no'] : '--';
$gender = isset($rsa_policy_data['gender']) ? strtoupper($rsa_policy_data['gender']) : '--';
$dob = isset($rsa_policy_data['dob']) ? $rsa_policy_data['dob'] : '--';
$dob = date("d-M-Y", strtotime($dob) );
$insured_dob = date('Y-m-d',strtotime($dob));
$age =  date_diff(date_create($insured_dob), date_create('today'))->y;

$addr1 = isset($rsa_policy_data['addr1']) ? strtoupper($rsa_policy_data['addr1']) : '--';
$addr2 = isset($rsa_policy_data['addr2']) ? strtoupper($rsa_policy_data['addr2']) : '--';
$state_name = isset($rsa_policy_data['state_name']) ? strtoupper($rsa_policy_data['state_name']) : '--';
$city_name = isset($rsa_policy_data['city_name']) ? strtoupper($rsa_policy_data['city_name']) : '--';
$sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '--';
$sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date']: '--';
$pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '--';

$date = new DateTime($pa_sold_policy_effective_date);
$pa_sold_policy_effective_date = $date->format('d-M-Y H:i:s');
$pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '0000-00-00 00:00:00';
$date = new DateTime($pa_sold_policy_end_date);
$pa_sold_policy_end_date = $date->format('d-M-Y H:i:s');
$model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';
$this->load->library('Tcpdf/Tcpdf.php');
ob_start();
$this->load->library('Ciqrcode');

$params['data'] = "Name: '" . $full_name_of_insured . "' , POLICY NO: '" . $certificate_no . "', FROM: '" . $pa_sold_policy_effective_date . "' , TO: '" . $pa_sold_policy_end_date . "'";
$params['level'] = 'H';
$params['size'] = 5;
$params['savename'] = FCPATH . 'assets/images/qr_image/'.trim($certificate_no).'.png';
$this->ciqrcode->generate($params);
$qr_code_image_url = '<img src="assets/images/qr_image/'.trim($certificate_no).'.png" height="55px" />';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Reliance GPA Master Policy');
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

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);

// add a page
$pdf->AddPage();

// set some text to print
$html = <<<EOD
<style>
  .pagewrap {color: #000; font-size: 7pt; line-height:8pt; color:#000;}
  .textcenter {text-align:center;}
  .textleft {text-align:left;}
  .textright {text-align:right;}
  .font-6{font-size: 5.5pt; line-height:7pt;}
  .font-7{font-size: 7pt; line-height:9pt;}
  .font-8{font-size: 8pt; line-height:10pt;}
  .font-9{font-size: 9pt; line-height:11pt;}
  .font-10{font-size: 10pt; line-height:12pt;}
  .font-11{font-size: 11pt; line-height:13pt;}
  .font-12{font-size: 12pt; line-height:14pt;}
  .font-14{font-size: 14pt; line-height:16pt;}
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
  .border, .boxtable td {border:0.2px solid #000;}
  .bluetable td {border:0.2px solid #16365c;}
  .boxtable td.heading {background-color:#d9d9d9; color:#000; font-weight:bold;}
  .tb-heading {background-color:#16365c; color:#fff;text-transform:uppercase; font-weight:bold;}
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
                    <td width="50%"><b>Certificate Number</b></td>
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
          <table cellpadding="1" border="0" cellspacing="0">
            <tr>
              <td colspan="3">
                <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
                  <tr class="tb-heading">
                    <td colspan="2"> PAYMENT DETAILS</td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td colspan="3"></td>
            </tr>
          </table>
          <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
            <tr>
              <td><b>Plan Ammount(Rs.)</b></td>
              <td>{$plan_amount}</td>
            </tr>
            <tr>
              <td><b>Tax Ammount(18% IGST in Rs.)</b></td>
              <td>{$gst_amount}</td>
            </tr>
            <tr>
              <td><b>Total Ammount (Rs.)</b></td>
              <td>{$total_amount}</td>
            </tr>
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
      <table cellpadding="0" border="0" cellspacing="0">
        <tr>
          <td width="25%" class="textleft"><img src="assets/images/Reliance-General-Insurance-logo.png" height="22px"></td>
          <td width="50%" class="font-11 line-height-14 textcenter"><br><b><u>RELIANCE GENERAL INSURANCE COMPANY LTD <br><span class="font-9">Certificate of Insurance under Group Personal Accident (GPA)</span></u></b></td>
          <td width="25%" class="textright font-8"  style="color:#0294c0;"><b>reliancegeneral.co.in <br>(Toll Free) 1800 3009<br>022) 4890 3009</b></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable">       
        <tr>          
          <td width="50%"><b>Policy Issuing Office:</b> {$master_policy_address}</td>        
          <td width="50%"><b>Policy Servicing Office:</b>4th Floor, Chintamani Avenue, Next to Virvani Industrial Estate, Off
          Western Express Highway, Goregaon East, Mumbai - 400063, Mumbai, Mumbai, Maharashtra - 400063 Phone No. : 022 33123123</td>
        </tr>           
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable">           
        <tr>
          <td width="20%"><b>Office Contact No :</b></td>
          <td colspan="3" width="80%">1800 3009</td>
        </tr>
        <tr>
          <td width="20%"><b>Certificate Number:</b></td>
          <td width="30%">{$certificate_no}</td>
          <td width="20%"><b>Master Policy Number:</b></td>
          <td width="30%">{$master_policy_no}</td>
        </tr>
        <tr>
          <td><b>Group Policy Holder:</b></td>
          <td colspan="3">INDICOSMIC CAPITAL PVT LTD</td>
        </tr>
        <tr>
          <td><b>Name of the Insured Person: </b></td>
          <td>{$full_name_of_insured}</td>
          <td><b>Address of Insured:</b></td>
          <td>{$addr1},{$addr2}</td>
        </tr>
        <tr>
          <td><b>Period of Insurance:</b></td>
          <td colspan="3">From : {$pa_sold_policy_effective_date} To : {$pa_sold_policy_end_date}</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="16.66%"><b>Age:</b></td>
          <td width="16.66%">{$age} Years</td>
          <td width="16.66%"><b>Gender:</b></td>
          <td width="16.66%">{$gender}</td>
          <td width="16.66%"><b>Sum Insured:</b></td>
          <td width="16.66%">Rs. {$sum_insured}/- only.</td>
        </tr>
        <tr>
          <td><b>Nominee Name:</b></td>
          <td>{$nominee_name}</td>
          <td><b>Relationship:</b></td>
          <td>{$nominee_relation}</td>
          <td><b>Identification Number: </b></td>
          <td>TDCC2036818</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2"><b>Scope of Cover</b></td>
        </tr>
        <tr>
          <td colspan="2">Personal Accident</td>
        </tr>
        <tr>
          <td width="2%">1)</td>
          <td width="98%">Accidental Death (AD)<br>if such injury shall within twelve calendar months of its occurrence be the sole and direct cause of the death of the Insured Person</td>
        </tr>
        <tr>
          <td>2)</td>
          <td>Permanent Total Disability (PTD)<br>Sight of both eyes / Physical separation of Two entire hands / Physical separation of Two entire Feet /Physical separation of One entire hand and one entire foot / or of such loss of sight of one eye and such loss of one entire hand or one entire foot.<br>Use of two hands or two feet /One hand and one foot / Loss of sight of one eye and such loss of use of one hand or one foot,<br>If such injury shall, as a direct consequence thereof, immediately, permanently, totally and absolutely, disable the Insured Person from engaging in being occupied with or giving attention to any employment or occupation of any description</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable"> 
        <tr>
          <td colspan="4" font-8><b>INDICOSMIC GPA PLAN</b></td>
        </tr>
        <tr>
          <td width="60%" class="heading"><b>Coverage Description</b></td>
          <td width="20%" class="heading"><b>Table of Benefit</b></td>
          <td width="20%" class="heading"><b>SI (in Rs.)</b></td>
        </tr>
        <tr>
          <td>Death + Permanent Total Disability</td>
          <td>B</td>
          <td>{$sum_insured}</td>
        </tr>       
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2"><b>Important Note:</b></td>
        </tr>
        <tr>
          <td width="2%">1)</td>
          <td width="98%">The personal accident coverage is applicable only to the owner of the vehicle for which Road Side Assistance service has been provided by the Indicosmic Capital Pvt Ltd.</td>
        </tr>
        <tr>
          <td>2)</td>
          <td>The said personal accident cover is active only whilst the owner is driving or travelling in the said vehicle including mounting into/dismounting from the said vehicle.</td>
        </tr>
        <tr>
          <td>3)</td>
          <td>Death or permanent total disability claims due to any other incidence would not be covered.</td>
        </tr>
        <tr>
          <td>4)</td>
          <td>The policy is valid for 365 days from the policy risk start date.</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td colspan="2"><b>Special Condition::</b></td>
        </tr>
        <tr>
          <td width="2%">1)</td>
          <td width="98%">The policy will be valid to all Two wheeler customers of TVS Motor Company</td>
        </tr>
        <tr>
          <td>2)</td>
          <td>In case of Claim payment under Accidental Death (AD) or Permanent Total Disabilities (PTD) there will  be no further claim accepted either for AD or PTD.</td>
        </tr>
        <tr>
          <td>3)</td>
          <td>The Companys total liability for an Individual in aggregate shall not exceed Individual Sum Insured issued in a single policy irrespective of no of covers issued. Given such scenario the first policy will be taken in to consideration</td>
        </tr>
        <tr>
          <td>4)</td>
          <td>In case of any claim made under the policy no premium shall be refunded on cancellation of the policy.</td>
        </tr>
        <tr>
          <td>5)</td>
          <td>In the event of any incorrect representation,the liability shall be upon the Policyholder.</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td colspan="2"><b>Exclusion:</b></td>
        </tr>
        <tr>
          <td colspan="2">Lives involved in the below mentioned activities/occupation shall be outside the scope of the policy</td>
        </tr>
        <tr>
          <td width="2%">1)</td>
          <td width="98%">Payment of benefits shall not be available in respect of death, injury or disablement directly or indirectly arising out of or contributed to by or traceable to any disability existing on the date of issue of the policy.</td>
        </tr>
        <tr>
          <td>2)</td>
          <td>Intentional self-injury, suicide or attempted suicide or whilst under the influence of intoxicating liquor or drugs.</td>
        </tr>
        <tr>
          <td>3)</td>
          <td>Venereal disease or insanity.</td>
        </tr>
        <tr>
          <td>4)</td>
          <td>Servicing - on duty with any armed forces.</td>
        </tr>
        <tr>
          <td>5)</td>
          <td>War, war-like situation, invasion or in consequence thereof or nuclear risk.</td>
        </tr>
        <tr>
          <td>6)</td>
          <td>Payment of compensation in respect of death, permanent total disablement of Insured person arising or
           resulting from the Insured Person committing any breach of law with criminal intent.</td>
        </tr>
        <tr>
          <td>7)</td>
          <td>Crew of aircraft and ship; naval, military, airforce personnel, policemen, firemen, fishermen are excluded from scope of this policy.</td>
        </tr>
        <tr>
          <td>8)</td>
          <td>Any loss sustained while performing or participating in any of the following occupations or events shall not be covered - Working in mines, explosives, electrical installations on high tension electric lines, racing, circus personnel, skiing, mountaineering, hunting, gliding, river rafting, winter sports, ice hockey, polo and occupations of similar hazard.</td>
        </tr>
        <tr>
          <td colspan="2">The above list of exclusions are not exhaustive. Kindly refer Policy Terms and conditions.</td>
        </tr>   
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td><b>Claims Settlement Procedure:</b> As per policy wording</td>
        </tr>
        <tr>
          <td>In case of a renewal, the benefits provided under the policy and/or terms and conditions of the policy including premium rate may be subject to change.</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td><b>Grievance Clause:-</b></td>
        </tr>
        <tr>
          <td>For resolution of any query or grievance, Insured may contact the respective branch office of the Company or may call at 1800 3009 or may write an email at rgicl.services@relianceada.com. In case the insured is not satisfied with the response of the office, insured may contact the Nodal Grievance Officer of the Company at rgicl.grievances@relianceada.com. In the event of unsatisfactory response from the Nodal Grievance Officer, insured may email to Head Grievance Officer at rgicl.headgrievances@relianceada.com. In the event of unsatisfactory response from the Head Grievance Officer, he/she may, subject to vested jurisdiction, approach the Insurance Ombudsman for the redressal of grievance. Details of the offices of the Insurance Ombudsman are available at IRDAI website www.irda.gov.in or on company website www.reliancegeneral.co.in or on www.gbic.co.in. The insured may also contact the following office of the Insurance Ombudsman within whose territorial jurisdiction the branch or office of the Company is located. Office of the Insurance Ombudsman,3rd Floor,Jeevan Seva Annexe,S. V. Road,Santacruz (W), Mumbai - 400 054. Tel.: 022 - 26106552 / 26106960 Fax: 022 - 26106052 Email: bimalokpal.mumbai@gbic.co.in | Shri. A. K. Sahoo Office of the Insurance Ombudsman,Jeevan Darshan Bldg.,3rd Floor,C.T.S. No.s. 195 to 198,N.C. Kelkar Road,Narayan Peth, Pune – 411 030. Tel.: 020-41312555 Email: bimalokpal.pune@gbic.co.in</td>
        </tr>
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="5" border="0" cellspacing="0" class="boxtable" >
        <tr>
          <td><table cellpadding="2" border="0" cellspacing="0">  
              <tr>
                <td class="textright"><b>Reliance General Insurance Company Ltd.</b></td>
              </tr>
              <tr>
                <td class="textright"><img src="assets/images/sign-img.jpg" height="40px"></td>
              </tr>
              <tr>
                <td class="textright"><b>Authorized Signatory</b></td>
              </tr>
              <tr>
                <td>In case of any assistance with claims, please contact us on 1800 3009 (Toll Free) or email us at services.rgicl@rcap.co.in.</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>  
      <table cellpadding="1" border="0" cellspacing="0" >
        <tr>
          <td></td>
        </tr>
            <tr> 
              <td width="75%">Reliance General Insurance Company Limited. <span class="font-7 line-height-10"><b>IRDAI Registration No. 103</b></span></td>
              <td width="25%">An ISO 9001:2008 Certified Company</td>
            </tr>
            <tr> 
              <td colspan="2">Registered Office: H Block, 1st Floor, Dhirubhai Ambani Knowledge City, Navi Mumbai 400710.<br>Corporate Office: Reliance Centre, South Wing, 4th Floor, Off. Western Express Highway , Santacruz (East), Mumbai - 400 055.<br>Corporate Identity No.U66603MH2000PLC128300. UIN No.: IRDA/NL-HLT/RGI/P-P/V.I/320/13-14. RGI/UW/CO/2914/PS/1.0/010218.</td>
            </tr>
            <tr> 
              <td colspan="2" class="font-6">Trade Logo displayed above belongs to Anil Dhirubhai Ambani Ventures Private Limited and used by Reliance General Insurance Company Limited under License</td>
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
$result= $pdf->Output('Reliance GPA Master Policy.pdf', 'S');

return $result ;
//============================================================+
// END OF FILE
//============================================================+

}




    
function LibertyGeneral($id){
  $rsa_policy_data = $this->HomeMdl->getPolicyById($id);
  // echo "<pre>"; print_r($rsa_policy_data); echo "</pre>"; die('end of line yoyo');
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
  $plan_id = isset($rsa_policy_data['plan_id']) ? $rsa_policy_data['plan_id'] : '--';
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
  $created_date = isset($rsa_policy_data['created_at']) ? $rsa_policy_data['created_at'] : '0000-00-00 00:00:00';
  // die($created_date);
  $master_policy_no = isset($rsa_policy_data['master_policy_no']) ? $rsa_policy_data['master_policy_no'] : ' ';
  if( strtotime($created_date) >= strtotime('2019-10-31 11:22:59') ){
        $master_policy_holder = 'INDICOSMIC CAPITAL PVT. LTD.';
        $servicing_office = "902 And 903,9th Floor, Jai Antariksh, Off Andheri Kurla Road, Makwana Road,Near Marol Metro Station, Andheri East, Mumbai, Maharashtra - 400059. Phone: +91 22-40506300 Fax: +91 22 67001606";
        $master_policy_address = $master_policy_details['address'];
        $receipt_no = "1201970040186";
        $stamp_duty = "Consolidated Stamp duty has been paid as per letter of Authorization no. CSD/129/2019/4211/19 dated 23/08/2019 issued by Main Stamp Office, Mumbai. ** Not Applicable for the State of Jammu & Kashmir.";
        $place_of_issuance = "Andheri";
        $geographical_scope = 'India';
  }else{
        $master_policy_holder = 'M/S INDICOSMIT PVT. LTD.';
        $servicing_office = "10th Floor, Tower A, Peninsula Business Park, Ganpatrao Kadam Marg, Lower Parel, Mumbai - 400013. Phone: +91 22 67001313 Fax: + 91 22 67001606";
        $master_policy_address = "10th Floor, Tower A, Peninsula Business Park, Ganpatrao Kadam Marg, Lower Parel, Mumbai - 400013. Phone: +91 22 67001313 Fax: + 91 2267001606.";
        $receipt_no = "1201970003138";
        $stamp_duty = "Consolidated Stamp duty has been paid as per letter of Authorization no. CSD/05/2019/1892/19 dated 23/04/2019 issued by Main Stamp Office, Mumbai. ** Not Applicable for the State of Jammu & Kashmir.";
        $place_of_issuance = "Mumbai 1";
        $geographical_scope = 'Worldwide';
    }
  $issue_date_html='';
  if(!empty($created_date)){
        $exploded_date = explode(" ", $created_date);
        $create_date_ar = explode("-", $exploded_date[0]);
        $year_split = str_split((string)$create_date_ar[0]);
        $month_split = str_split((string)$create_date_ar[1]);
        $day_split = str_split((string)$create_date_ar[2]);
        $issue_date_html = '<tr>
                                  <td>'.$day_split[0].'</td>
                                  <td>'.$day_split[1].'</td>
                                  <td>'.$month_split[0].'</td>
                                  <td>'.$month_split[1].'</td>
                                  <td>'.$year_split[0].'</td>
                                  <td>'.$year_split[1].'</td>
                                  <td>'.$year_split[2].'</td>
                                  <td>'.$year_split[3].'</td>
                                </tr>';
  }
  
  // echo "<pre>"; print_r($issue_date_html); echo "</pre>"; die('end of line yoyo');
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
 
  $mp_start_date = isset($rsa_policy_data['mp_start_date']) ? $rsa_policy_data['mp_start_date'] : ' ';
  $date = new DateTime($mp_start_date);
  $mp_start_date = $date->format('d-M-Y');
  $mp_end_date = isset($rsa_policy_data['mp_end_date']) ? $rsa_policy_data['mp_end_date'] : ' ';
  $date = new DateTime($mp_end_date);
  $mp_end_date = $date->format('d-M-Y');

  $email = isset($rsa_policy_data['email']) ? strtoupper($rsa_policy_data['email']) : '--';
  $mobile_no = isset($rsa_policy_data['mobile_no']) ? $rsa_policy_data['mobile_no'] : '--';
  $gender = isset($rsa_policy_data['gender']) ? strtoupper($rsa_policy_data['gender']) : '--';
  $dob = isset($rsa_policy_data['dob']) ? $rsa_policy_data['dob'] : '--';
  $dob = date("d-M-Y", strtotime($dob) );
  $insured_dob = date('Y-m-d',strtotime($dob));
  $age =  date_diff(date_create($insured_dob), date_create('today'))->y;

  $addr1 = isset($rsa_policy_data['addr1']) ? strtoupper($rsa_policy_data['addr1']) : '--';
  $addr2 = isset($rsa_policy_data['addr2']) ? strtoupper($rsa_policy_data['addr2']) : '--';
  $state_name = isset($rsa_policy_data['state_name']) ? strtoupper($rsa_policy_data['state_name']) : '--';
  $city_name = isset($rsa_policy_data['city_name']) ? strtoupper($rsa_policy_data['city_name']) : '--';
  $sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '--';
  $sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date']: '--';
  $pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '--';

  $date = new DateTime($pa_sold_policy_effective_date);
  $pa_sold_policy_effective_date = $date->format('d-M-Y H:i:s');
  $pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '0000-00-00 00:00:00';
  $date = new DateTime($pa_sold_policy_end_date);
  $pa_sold_policy_end_date = $date->format('d-M-Y H:i:s');
  $model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';
  $this->load->library('Tcpdf/Tcpdf.php');
  ob_start();
  $this->load->library('Ciqrcode');

  $params['data'] = "Name: '" . $full_name_of_insured . "' , POLICY NO: '" . $certificate_no . "', FROM: '" . $pa_sold_policy_effective_date . "' , TO: '" . $pa_sold_policy_end_date . "'";
  $params['level'] = 'H';
  $params['size'] = 5;
  $params['savename'] = FCPATH . 'assets/images/qr_image/'.trim($certificate_no).'.png';
  $this->ciqrcode->generate($params);
  $qr_code_image_url = '<img src="assets/images/qr_image/'.trim($certificate_no).'.png" height="70" />';
      

    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nicola Asuni');
    $pdf->SetTitle('Liberty RPA Policy');
    $pdf->SetSubject('TCPDF Tutorial');
    $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(5, 2, 5);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 5);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
      require_once(dirname(__FILE__).'/lang/eng.php');
      $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------

    // set font
    $pdf->SetFont('helvetica', '', 8);

    // add a page
    $pdf->AddPage();

    // set some text to print
  $html = <<<EOD
  
    <style>
      .brandcolor {color:#002b6a;}
      .sectionhead {color:#fff; background-color:#003e7e; font-size:7pt; line-height:9pt; font-weight:bold;}
      .ftable { line-height:12pt;}
      .footer { background-color:#7283b2; color:#003e7e; font-size:9pt; line-height:14pt; font-weight:bold;}
      .web { background-color:#003e7e; color:#fff; font-size:7pt; line-height:14pt; text-align:center;}

      .pagewrap {color: #000; font-size: 7pt; line-height:8.5pt; color:#000;}
      .textcenter {text-align:center;}
      .textleft {text-align:left;}
      .textright {text-align:right;}
      .font-6{font-size: 6pt; line-height:7pt;}
      .font-6-5{font-size: 6.8pt; line-height:8pt;}
      .font-7{font-size: 7pt; line-height:8.5pt;}
      .font-8{font-size: 7pt; line-height:9pt;}
      .font-9{font-size: 9pt; line-height:11pt;}
      .font-10{font-size: 10pt; line-height:12pt;}
      .font-11{font-size: 11pt; line-height:13pt;}
      .font-12{font-size: 12pt; line-height:14pt;}
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
     
      .border, .boxtable td {border:0.2px solid #000;}
      .bluetable td {border:0.2px solid #16365c;}
      .boxtable td.heading {background-color:#d9d9d9; color:#000; font-weight:bold;}
      .tb-heading {background-color:#16365c; color:#fff;text-transform:uppercase; font-weight:bold;}
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
                    <td width="50%"><b>Certificate Number</b></td>
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
          <table cellpadding="1" border="0" cellspacing="0">
            <tr>
              <td colspan="3">
                <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
                  <tr class="tb-heading">
                    <td colspan="2"> PAYMENT DETAILS</td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td colspan="3"></td>
            </tr>
          </table>
          <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
            <tr>
              <td><b>Plan Ammount(Rs.)</b></td>
              <td>{$plan_amount}</td>
            </tr>
            <tr>
              <td><b>Tax Ammount(18% IGST in Rs.)</b></td>
              <td>{$gst_amount}</td>
            </tr>
            <tr>
              <td><b>Total Ammount (Rs.)</b></td>
              <td>{$total_amount}</td>
            </tr>
            <tr>
              <td><b>Coverage Kilometer RSA Upto</b></td>
              <td>25 KM</td>
            </tr>
            <tr>
              <td colspan="2"><b>**We are glad to inform you that you are covered under Group Personal Accident Policy issued by Liberty General Insurance Limited. This is offered to you as complementary service benefit.</b></td>
            </tr>
          </table>
          
          </td>     
      </tr>
    </table>
    <br pagebreak="true" />

    <table width="100%" cellpadding="0" border="0" class="pagewrap">  
        <tr>
            <td>
              <table cellpadding="0" border="0" cellspacing="0">
                <tr>
                  <td width="50%"><img src="assets/images/liberty-logo.jpg" height="60"></td>
                  <td width="50%" class="textright">{$qr_code_image_url}</td>                
                </tr>
              </table>
              <table cellpadding="0" border="0" cellspacing="0" style="font-family:times;">
                <tr>
                  <td width="30%" class="font-6">Phone: +91 22 6700 1313 Fax: +91 22 6700 1606 <br>Email: <span style="color:#1a0dab"><u>care@libertyinsurance.in</u></span> <br>IRDA registration number: 150 • CIN: U66000MH2010PLC209656
                  </td>
                  <td width="40%" class="textcenter font-6">&nbsp;<br>Awarded for <b>"Best Contact Center - 2015"</b> across <br>BFSI sector in Customer Experience Summit – An Initiative by Kamikaze
                  </td>    
                  <td width="30%">

                  </td>            
                </tr>
              </table>
              <table cellpadding="0" border="0" cellspacing="0">
                <tr>
                  <td class="brandcolor textcenter font-12 line-height-14" style="font-family:times;"><b>LIBERTY GROUP PERSONAL<br>ACCIDENT POLICY - CERTIFICATE OF INSURANCE</b></td>
                </tr>
              </table>
            </td>   
        </tr> 
        <tr>
          <td></td>
        </tr>
        <tr>
          <td>
            <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
              <tr>
                <td colspan="4" class="textcenter line-height-10">This Certificate is subject to the terms and conditions of the Master Policy No. {$master_policy_no} mentioned in This Certificate of Insurance</td>
              </tr> 
              <tr>
                <td colspan="4"><table cellpadding="0" border="0" cellspacing="0">
                      <tr>
                        <td width="16%"0><b>Policy Issuing Office:</b></td>
                        <td width="84%">{$master_policy_address}</td>
                      </tr>
                      <tr>
                        <td><b>Policy Servicing Office:</b></td>
                        <td>{$servicing_office}</td>
                      </tr>
                  </table>
                </td>
              </tr>  
              <tr>
                <td colspan="2"><table cellpadding="1" border="0" cellspacing="0">
                    <tr>
                      <td colspan="2"></td>
                    </tr>
                    <tr>
                      <td width="30%"><b>Master Policy Number:</b></td>
                      <td width="70%">{$master_policy_no}</td>
                    </tr>
                    <tr>
                      <td><b>Insured Name:</b> </td>
                      <td>{$full_name_of_insured}</td>
                    </tr>
                    <tr>
                      <td><b>Certificate No.:</b></td>
                      <td>{$certificate_no}</td>
                    </tr>
                    <tr>
                      <td><b>Address:</b></td>
                      <td>{$addr1}, {$addr2}</td>
                    </tr>
                    <tr>
                      <td><b>Contact No.:</b></td>
                      <td>{$mobile_no}</td>
                    </tr>
                  </table>
                </td>
                <td colspan="2"><table cellpadding="1" border="0" cellspacing="0">
                    <tr>
                      <td colspan="2" class="textcenter line-height-16"><b></b></td>
                    </tr>
                    <tr>
                      <td width="45%"><b>Name of Master Policy Holder: </b></td>
                      <td width="55%">{$master_policy_holder}</td>
                    </tr>
                    <tr>
                      <td><b>Master Policy Issue Date:</b> </td>
                      <td>{$mp_start_date}</td>
                    </tr>
                    <tr>
                      <td><b>Master Policy Period of Insurance:</b></td>
                      <td>From {$mp_start_date} To {$mp_end_date}</td>
                    </tr>
                    <tr>
                      <td><b>COI Issue Date:</b></td>
                      <td>{$created_date}</td>
                    </tr>
                    <tr>
                      <td><b>COI Period of Insurance:</b></td>
                      <td>From {$pa_sold_policy_effective_date}  To {$pa_sold_policy_end_date}</td>
                    </tr>
                  </table>
                </td>
              </tr> 
            </table>
            <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
              <tr>
                <td width="25%"><b>Proposal:</b> </td>
                <td width="25%">Individual</td>
                <td width="25%"><b>Transaction Date / Date of Debit of Premium:</b></td>
                <td width="25%">NA</td>
              </tr>
              <tr>
                <td><b>Geographical Scope:</b></td>
                <td>{$geographical_scope}</td>
                <td><b>Occupation:</b></td>
                <td>Others in Risk Group 1</td>
              </tr>
              <tr>
                <td><b>Insured / Insured Person Relationship:</b> </td>
                <td>Non employee-employer relationship</td>
                <td><b>Relationship with Insured:</b></td>
                <td>Non employee-employer relationship</td>
              </tr>        
            </table>
            <table cellpadding="1" border="0" cellspacing="0">
              <tr>
                <td width="2%" class="sectionhead"></td>
                <td width="22%" class="sectionhead">INSURED PERSON(S) DETAILS</td>
                <td width="76%"></td>
              </tr>
            </table>
            <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
              <tr style="background-color:#ebecec;">
                <td width="25%"></td>
                <td width="75%" class="textcenter"><b>Insured Person I</b></td>
              </tr>
              <tr>
                <td><b>Name</b></td>
                <td class="textcenter">{$full_name_of_insured}</td>
              </tr>
              <tr>
                <td><b>Relationship with Primary Member</b></td>
                <td class="textcenter">Self</td>
              </tr>
              <tr>
                <td><b>Gender</b></td>
                <td class="textcenter">{$gender}</td>
              </tr>
              <tr>
                <td><b>Date Of Birth</b></td>
                <td class="textcenter">{$dob}</td>
              </tr>
              <tr>
                <td><b>Age(Years)</b></td>
                <td class="textcenter">{$age} Years</td>
              </tr>
              <tr>
                <td><b>Occupation</b></td>
                <td class="textcenter">Others in Risk Group 1</td>
              </tr>
              <tr>
                <td><b>Annual Income</b></td>
                <td class="textcenter">NA</td>
              </tr>
              <tr>
                <td><b>Capital Sum Insured</b></td>
                <td class="textcenter">{$sum_insured}</td>
              </tr>
              <tr>
                <td><b>Nominee Name</b></td>
                <td class="textcenter">{$nominee_name}</td>
              </tr>
              <tr>
                <td><b>Relationship with Nominee</b></td>
                <td class="textcenter">{$nominee_relation}</td>
              </tr>
              <tr>
                <td><b>Nominee Address</b></td>
                <td class="textcenter"></td>
              </tr>
            </table>
            <table cellpadding="1" border="0" cellspacing="0">
              <tr>
                <td width="2%" class="sectionhead"></td>
                <td width="10%" class="sectionhead"> Coverages</td>
                <td width="88%"></td>
              </tr>
            </table>
            <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
              <tr style="background-color:#ebecec;">
                <td width="5%" class="textcenter"><b>Sr. No</b></td>
                <td width="50%" class="textcenter"><b>Accidental Benefit(s)</b></td>
                <td width="45%" class="textcenter"><b>Capital Sum Insured</b></td>
              </tr>
              <tr>
                <td class="textcenter">1</td>
                <td class="textcenter">Accidental Death (AD) </td>
                <td class="textcenter">{$sum_insured}</td>
              </tr>
              <tr>
                <td class="textcenter">2</td>
                <td class="textcenter">Permanent Total Disability (PTD)</td>
                <td class="textcenter">{$sum_insured}</td>
              </tr>
            </table>        
            <table cellpadding="1" border="0" cellspacing="0">
              <tr>
                <td width="2%" class="sectionhead"></td>
                <td width="15%" class="sectionhead">Important Note</td>
                <td width="83%"></td>
              </tr>
            </table>
            <table cellpadding="2" border="0" cellspacing="0" class="boxtable font-6-5">
              <tr>
                <td>1) The personal accident coverage is applicable only to the owner of the vehicle for which Road Side Assistance service has been provided by the Indicosmic Capital Pvt Ltd. <br>2) Death or permanent total disability claims due to any other incidence would not be covered. <br>3) The policy is valid for 365 days from the policy risk start date.</td>
              </tr>
            </table>        
            <table cellpadding="1" border="0" cellspacing="0">
              <tr>
                <td width="2%" class="sectionhead"></td>
                <td width="15%" class="sectionhead">Special Conditions</td>
                <td width="83%"></td>
              </tr>
            </table>
            <table cellpadding="2" border="0" cellspacing="0" class="boxtable font-6-5">
              <tr>
                <td>1.Accidental Death (AD) -Covers Death due to Accident 2. Permanent Total Disability (PTD)-Covers Permanent Total Disability due to Accident, which totally disables the Insured from attending to any occupation/job/business or normal duties for a continuous period of 12 months 3. Any form of Nuclear, Chemical and biological Terrorism is excluded 4. 24*7 cover 5.India Cover Only as motor policy is valid within India.</td>
              </tr>
            </table>
            <table cellpadding="1" border="0" cellspacing="0">
              <tr>
                <td width="2%" class="sectionhead"></td>
                <td width="15%" class="sectionhead">General Conditions</td>
                <td width="83%"></td>
              </tr>
            </table>
            <table cellpadding="2" border="0" cellspacing="0" class="boxtable font-6-5">
              <tr>
                <td>"<b>Important Note:</b> Insurance is a contract of Utmost Good Faith requiring the Insured not only to disclose all material information and which has a bearing on the acceptance or rejection of the Proposal by the Insurer. In the event of any discrepancy, contact us immediately, it being noted that this Policy shall be otherwise considered as being entirely in order.<br>All terms, conditions and exclusions as per standard Policy wordings attached with this schedule."</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td></td>
        </tr>
         <tr>
          <td>        
            <table cellpadding="0" border="0" cellspacing="0">
              <tr>
                <td width="50%"><table cellpadding="0" border="0" cellspacing="0">
                    <tr>
                      <td width="45%">Receipt No:</td>
                      <td width="55%">{$receipt_no}</td>
                    </tr>
                    <tr>
                      <td>Date of Issue:</td>
                      <td><table cellpadding="0" border="0" cellspacing="0" class="boxtable textcenter" width="60%">
                          {$issue_date_html}
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td>Place:</td>
                      <td>{$place_of_issuance}</td>
                    </tr>
                    <tr>
                      <td>Service Tax Registration No:</td>
                      <td>AABCL9950ASD001</td>
                    </tr>
                    <tr>
                      <td>IRDA Registration Number:</td>
                      <td>150</td>
                    </tr>
                    <tr>
                      <td>CIN:</td>
                      <td>U66000MH2010PLC209656</td>
                    </tr>
                    <tr>
                      <td>UIN:</td>
                      <td>LVGPAGP18098V011718</td>
                    </tr>
                  </table>
                </td>
                <td width="50%"><table cellpadding="0" border="0" cellspacing="0" class="textcenter">              
                  <tr>
                    <td><b>For and on behalf of Liberty General Insurance Limited</b></td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                  <tr>
                    <td><img src="assets/images/liberty_sign-img.jpg" height="35"></td>  
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                  <tr>
                    <td><b>(Authorized Signatory)</b></td>
                  </tr>
                </table>
                </td>
              </tr>
            </table>
            <table cellpadding="0" border="0" cellspacing="0"> 
              <tr>
                <td></td>
              </tr>
              <tr>
                <td>{$stamp_duty} </td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td><b>For Policy and claim related assistance please feel free to write to us on care@libertyinsurance.in OR call us on our Toll Free number 1800 266 5844 (between 8:00 am to 8:00 pm, 7 days of the week) our representatives will be glad to help you</b></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td>
            <table cellpadding="0" border="0" cellspacing="0" class="footer">
              <tr>
                <td width="65%"></td>
                <td width="32%" class="web">www.libertyinsurance.in</td>
                <td width="3%"></td>
              </tr>
            </table>
          </td>
        </tr>      
    </table> 
    <!--<br pagebreak="true" />-->
EOD;

if($dealer_id == 2871 || $dealer_id == 2872){
    $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 10, 100, 200, '', '', '', true, 500);
   }
// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0, '');

    // ---------------------------------------------------------

    //Close and output PDF document
    $result= $pdf->Output('Liberty RPA Policy.pdf', 'S');

    return $result ;

}
    
    
function DownloadOrientalPdf($id){
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
  $date = new DateTime($pa_sold_policy_effective_date);
  $pa_sold_policy_effective_date = $date->format('d-M-Y');
  $created_time = date("H:i:s",strtotime($created_date));
  $pa_sold_policy_effective_date = $pa_sold_policy_effective_date.' '.$created_time;
  $date = new DateTime($pa_sold_policy_end_date);
  $pa_sold_policy_end_date = $date->format('d-M-Y H:i:s');
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
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td colspan="3">
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
              <tr class="tb-heading">
                <td colspan="2"> PAYMENT DETAILS</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="3"></td>
        </tr>
      </table>
      <table cellpadding="3" border="0" cellspacing="0" class="bluetable ">
        <tr>
          <td><b>Plan Ammount(Rs.)</b></td>
          <td>{$plan_amount}</td>
        </tr>
        <tr>
          <td><b>Tax Ammount(18% IGST in Rs.)</b></td>
          <td>{$gst_amount}</td>
        </tr>
        <tr>
          <td><b>Total Ammount (Rs.)</b></td>
          <td>{$total_amount}</td>
        </tr>
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
          <td>Insured’s Name: {$full_name_of_insured}</td>
        </tr>
        <tr>
          <td>Insured’s Address: {$addr1} {$addr2}</td>
        </tr>
        <tr>
          <td>Certificate No: {$certificate_no}</td>
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
          <td colspan="2"><b>Details of GPA Policy</b><br>If the Insured person sustains any bodily injury during the policy period which directly and independently of all other causes result in death/ disablement stated below within 12 months from the date of accident  resulting solely and directly from Accident then the Company shall pay to the insured the sum set in the schedule to the Insured person’s nominee ,beneficiary or legal representative. This certificate is effective only if the vehicle is registered in the name of insured person as on date of accident.</td>
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
            <td>The said personal accident cover is active only whilst the owner is driving or travelling in the said vehicle including mounting into/dismounting from the said vehicle.</td>
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
        <td>After receipt of all the claim related documents OICL will settle the claim within 10 working days.</td>
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
   return $result;
}
   public function DownloadTataFullPolicy($id) {
        $rsa_policy_data = $this->HomeMdl->getPolicyById($id);
        // echo '<pre>'; print_r($rsa_policy_data);die('hello');
        $getDealerInfo = $this->HomeMdl->getDealerinfo($rsa_policy_data['user_id']);
        // echo '<pre>';print_r($getDealerInfo);die;
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
        $plan_detalis = $this->HomeMdl->getRowDataFromTable('tvs_plans',$where);
         // echo '<pre>'; print_r($plan_detalis);die();
        $km_covered = isset($plan_detalis['km_covered'])?$plan_detalis['km_covered']:'50';
        // die($km_covered);
        $sum_insured = isset($plan_detalis['sum_insured'])?$plan_detalis['sum_insured']:0;
        $plan_amount  = round($plan_detalis['plan_amount']);
        $gst_amount  = round($plan_detalis['gst_amount']);
        $total_amount =  ($plan_amount + $gst_amount);
        $engine_no = isset($rsa_policy_data['engine_no']) ? strtoupper($rsa_policy_data['engine_no']) : '--';
        $chassis_no = isset($rsa_policy_data['chassis_no']) ? strtoupper($rsa_policy_data['chassis_no']) : '--';
        $created_date = isset($rsa_policy_data['created_at']) ? strtoupper($rsa_policy_data['created_at']) : '--';
        $fname = isset($rsa_policy_data['fname']) ? strtoupper($rsa_policy_data['fname']) : '--';
        $lname = isset($rsa_policy_data['lname']) ? strtoupper($rsa_policy_data['lname']) : '--';
        $full_name_of_insured = $fname.' '.$lname;
        $nominee_name = isset($rsa_policy_data['nominee_full_name']) ? strtoupper($rsa_policy_data['nominee_full_name']) : '--';
        $nominee_relation = isset($rsa_policy_data['nominee_relation']) ? strtoupper($rsa_policy_data['nominee_relation']) : '--';
        $nominee_age = isset($rsa_policy_data['nominee_age']) ? strtoupper($rsa_policy_data['nominee_age']) : '--';

        $appointee_name = isset($rsa_policy_data['appointee_full_name']) ? strtoupper($rsa_policy_data['appointee_full_name']) : ' ';
        $appointee_relation = isset($rsa_policy_data['appointee_relation']) ? strtoupper($rsa_policy_data['appointee_relation']) : ' ';
        $appointee_age = isset($rsa_policy_data['appointee_age']) ? strtoupper($rsa_policy_data['appointee_age']) : ' ';

        //master policy detils
        $master_policy_no = isset($rsa_policy_data['master_policy_no']) ? strtoupper($rsa_policy_data['master_policy_no']) : ' ';
        $mp_start_date = isset($rsa_policy_data['mp_start_date']) ? strtoupper($rsa_policy_data['mp_start_date']) : ' ';
        $date = new DateTime($mp_start_date);
        $mp_start_date = $date->format('d-M-Y');
        $mp_end_date = isset($rsa_policy_data['mp_end_date']) ? strtoupper($rsa_policy_data['mp_end_date']) : ' ';
        $date = new DateTime($mp_end_date);
        $mp_end_date = $date->format('d-M-Y');
        $email = isset($rsa_policy_data['email']) ? strtoupper($rsa_policy_data['email']) : '--';
        $mobile_no = isset($rsa_policy_data['mobile_no']) ? strtoupper($rsa_policy_data['mobile_no']) : '--';
        $gender = isset($rsa_policy_data['gender']) ? strtoupper($rsa_policy_data['gender']) : '--';
        $dob = isset($rsa_policy_data['dob']) ? strtoupper($rsa_policy_data['dob']) : '--';
        $addr1 = isset($rsa_policy_data['addr1']) ? strtoupper($rsa_policy_data['addr1']) : '--';
        $addr2 = isset($rsa_policy_data['addr2']) ? strtoupper(mb_strimwidth($rsa_policy_data['addr2'], 0, 20, "...")) : '--';
        $pin_code = isset($rsa_policy_data['pin_code']) ? $rsa_policy_data['pin_code'] : ' ';
        $state_name = isset($rsa_policy_data['state_name']) ? strtoupper($rsa_policy_data['state_name']) : '--';
        $city_name = isset($rsa_policy_data['city_name']) ? strtoupper($rsa_policy_data['city_name']) : '--';
        $full_address = $addr1.' '.$addr2.' '.$city_name.'-'.$pin_code.' '.$state_name;
        $issuing_offc = $city_name.' / '.$state_name;
        $sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '--';
        $sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? strtoupper($rsa_policy_data['sold_policy_end_date']) : '--';
        $pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '--';
          $imp_note ='';
        if( strtotime($pa_sold_policy_effective_date) >= strtotime('2019-09-05 21:59:59') ){
        $imp_note = ' <table cellpadding="1" border="0" cellspacing="0" class="font-8">
                      <tr>
                        <td width="5%" class="textcenter">4.</td>
                        <td width="95%">The personal accident coverage is applicable only to the owner of the vehicle for which Road Side Assistance service has been provided by the Indicosmic Capital Pvt Ltd.</td>
                      </tr>
                      <tr>
                        <td class="textcenter">5.</td>
                        <td>The said personal accident cover is active only whilst the owner is driving or travelling in the said vehicle including mounting into/dismounting from the said vehicle.</td>
                      </tr>
                      <tr>
                        <td class="textcenter">6.</td>
                        <td>Death or permanent total disability claims due to any other incidence would not be covered</td>
                      </tr>
                      <tr>
                        <td class="textcenter">7.</td>
                        <td>The policy is valid for 365 days from the policy risk start date</td>
                      </tr>
                    </table>';
          }
        $date = new DateTime($pa_sold_policy_effective_date);
        $pa_sold_policy_effective_date = $date->format('d-M-Y');
        $created_time = date("H:i:s",strtotime($created_date));
        $pa_sold_policy_effective_date = $pa_sold_policy_effective_date.' '.$created_time;
        $pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? strtoupper($rsa_policy_data['pa_sold_policy_end_date']) : '--';
        $date = new DateTime($pa_sold_policy_end_date);
        $pa_sold_policy_end_date = $date->format('d-M-Y H:i:s');
          $period_of_insurance = 'From 12 Hrs:00 Mins on'.$pa_sold_policy_effective_date.' To midnight of '.$pa_sold_policy_end_date;
        $model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';
        $this->load->library('Tcpdf/Tcpdf.php');
        ob_start();
        $this->load->library('Tcpdf/Tcpdf.php');
        $this->load->library('Ciqrcode');

        $params['data'] = "Name: '" . $full_name_of_insured . "' , POLICY NO: '" . $certificate_no . "', FROM: '" . $pa_sold_policy_effective_date . "' , TO: '" . $pa_sold_policy_end_date . "'";
        $params['level'] = 'H';
        $params['size'] = 5;
        $params['savename'] = FCPATH . 'assets/images/qr_image/'.trim($certificate_no).'.png';
        $this->ciqrcode->generate($params);
        $qr_code_image_url = '<img src="assets/images/qr_image/'.trim($certificate_no).'.png" width="60px" />';
        $pdf = new TCPDF();
        $pdf->SetFont('helvetica', '', 7, '', 'default', true);


        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        // set margins
        $pdf->SetMargins(3, 8, 5);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 2);

     

        // first page
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
<div style="margin: 0 auto; width: 660px;">
  <table width="100%" cellpadding="0" border="0" cellspacing="0">
   <tr>
      <td><img src="assets/images/mpdf/banner.jpg" alt="" style="width:660px;"></td>
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
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Certificte Number</b></td>
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
            <tr>
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
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Plan Ammount(Rs.)</b></td>

                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Tax Ammount(18% IGST in Rs.)</b></td>
                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Total Ammount (Rs.)</b></td>
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
          </tr>
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
            <tr>
    <td style="height:100px;">&nbsp;</td>
            </tr>
   <tr>
    <td style="padding: 20px 0 0;">
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
          <td width="82%">In the event that a Covered Vehicle is immobilized due to electrical or mechanical failure, ICPL shall arrange towing of the Covered Vehicle to the nearest authorized outlet. These services shall be provided using equipment/s deemed most suitable by ICPL. Towing up to $km_covered km from incident to nearest garage is free.</td>
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
    </td>
   </tr>

  </table>
</div>
EOD;



$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
        $pdf->AddPage();
         if($dealer_id == 2871 || $dealer_id == 2872){
          $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 5, 150, 250, '', '', '', true, 500);
         }

        $pdf->writeHtml($html);

$html2= <<<EOD
<br pagebreak="true" />
<style>
  .pagewrap {color: #000; font-size: 9pt; line-height:12pt; color:#000;}
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
  .border, .boxtable td {border:0.2px solid #000;}
  .tb-heading {background-color:#0070c0; color:#fff; font-wieght:bold;}
</style>

<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td height="70" class="textleft">{$qr_code_image_url}</td>
          <td height="70" class="textright"><img src="assets/images/mpdf/tataaig-logo.jpg" height="60"></td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0" class="textcenter">
        <tr>
          <td class="font-9 line-height-15"><b><u>Personal Accident Policy  - Certificate <br>Master Policy Holder Name:</u></b>
          </td>

        </tr> 
        <tr>
          <td class="font-9 line-height-15"><b><u> INDICOSMIC CAPITAL PVT. LTD.</u></b>
          </td>
        </tr>          
        <tr>
          <td></td>
        </tr>   
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td>Master Policy No.: {$master_policy_no} <b></b></td>          
        </tr>
        <tr>
          <td>Issuing Office: <b>{$issuing_offc }</b></td>
        </tr>
        <tr>
          <td>Insured’s Name:{$full_name_of_insured} </td>
        </tr>
        <tr>
          <td>Insured’s Address:{$full_address} </td>
        </tr>
        <tr>
          <td>Certificate No: {$certificate_no} </td>
        </tr> 
      </table>
      <table cellpadding="1" border="0" cellspacing="0">      
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td width="50%">Certificate Inception date: From - {$pa_sold_policy_effective_date}</td>
          <td width="50%">Expiry date: {$pa_sold_policy_end_date}</td>
        </tr>
        <tr>
          <td>Master Policy Period: From – {$mp_start_date}</td>
          <td class="textright">Expiry Date: To {$mp_end_date}</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0" class="boxtable textcenter">
        <tr>
          <td width="10%"><b>Name of Insured Person</b></td>
          <td width="10%"><b>Date of Birth</b></td>
          <td width="10%"><b>Sum Insured</b></td>
          <td width="15%"><b>Nominee Name</b></td>
          <td width="25%"><b>Nominee Relationship with Insured Person</b></td>
          <td width="30%"><b>Benefits</b></td>
        </tr>
        <tr>
          <td>{$full_name_of_insured}</td>
          <td>{$dob}</td>
          <td>{$sum_insured}</td>
          <td>{$nominee_name}</td>
          <td>{$nominee_relation}</td>
          <td>Accidental death / Permanent Total Disability</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0" class="boxtable textcenter">
              <tr>
                <td class="heading" colspan="6"><b>Agent / Broker Details</b></td>
              </tr>
              <tr>
                <td width="12%"><b>Broker Name</b></td>
                <td width="30%">Global India Insurance Brokers Pvt. Ltd.</td>
                <td width="14%"><b>Broker Code</b></td>
                <td width="14%">IRDA/DB-596/14</td>
                <td width="16%"><b>Broker contact No.</b></td>
                <td width="14%">022-49707493</td>
              </tr>     
        </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td height="20" colspan="2"></td>
        </tr>
        <tr>
          <td width="60%"></td>
          <td width="40%" class="textcenter"><b>For and on behalf of Tata AIG General Insurance Company Limited</b></td>          
        </tr>
        <tr>
          <td><b>Place:</b>{$city_name} <br><b>Date:</b>{$created_date} </td>
          <td class="textcenter"><img src="assets/images/revised/Malpa-Signature.png" height="53"></td>
        </tr>
        <tr>
          <td></td>
          <td class="textcenter"><b>Authorized Signatory</b></td>
        </tr>
        <tr>
          <td height="30" colspan="2"></td>
        </tr>       
      </table>
      <table cellpadding="1" border="0" cellspacing="0" class="font-8">
        <tr>
          <td colspan="2"><b><u>Details of GPA Policy</u></b><br>If the Insured shall sustain any bodily injury resulting solely and directly from Accident then the Company shall pay to the insured the sum hereinafter set forth:-</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2"><b>Policy features in brief:</b> Please refer to policy for detail information on coverage, exclusion and other terms and conditions.</td>
        </tr>
        <tr>
          <td width="5%" class="textcenter">1.</td>
          <td width="95%">Accidental death - We will pay the Principal Sum shown in the Policy Schedule if Injury to You results in loss of life.</td>
        </tr>
        <tr>
          <td class="textcenter">2.</td>
          <td>Permanent Total Disability - When as the result of Injury occurring under the circumstances described in a Hazard and commencing within 365 Days from the date of the Accident You suffer a Permanent Total Disability, We will pay, provided such disability has continued for a period of 12 consecutive months and is total, continuous and Permanent at the end of this period</td>
        </tr>
        <tr>
          <td class="textcenter">3.</td>
          <td>Policy is valid for age 18 to 65 years only</td>
        </tr>
      </table>
     {$imp_note}
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td height="30"></td>
        </tr>
        <tr>  
          <td class="textcenter font-8" style="color:#808080;">Tata AIG General Insurance Company Limited <br>Registered Office: : Peninsula Business Park, Tower A, 15th Floor, G. K. Marg, Off Senapati Bapat Road, Lower Parel, Mumbai- 400013. Toll Free Helpline No. 1800 266 7780 / 1800119966. Website: www.tataaiginsurance.in <br>IRDA Registration Number: 108   CIN: U85110MH2000PLC128425 <br>Group Personal Accident and Business Travel Accident Policy– UIN: IRDA/NL-HLT/TAGI/P-P/V.I/290/13-14</td>
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
          <td height="70" class="textright"><img src="assets/images/mpdf/tataaig-logo.jpg" height="60"></td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0" class="font-8">
        <tr>
          <td colspan="2"><b><u>Important Exclusions in brief:</u></b></td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2">Company shall not be liable under this Policy for -</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td width="5%" class="textcenter">1.</td>
          <td width="95%">Suicide, attempted suicide (whether sane or insane) or intentionally self inflicted Injury or Illness, or sexually transmitted conditions, mental or nervous disorder, anxiety, stress or depression, Acquired Immune Deficiency Syndrome (AIDS), Human Immune deficiency Virus (HIV) infection; or</td>
        </tr>
        <tr>
          <td class="textcenter">2.</td>
          <td>Being under the influence of drugs, alcohol, or other intoxicants or hallucinogens unless properly prescribed by a Physician and taken as prescribed; or</td>
        </tr>
        <tr>
          <td class="textcenter">3.</td>
          <td>Participation in an actual or attempted felony, riot, crime, misdemeanor, or civil commotion; or</td>
        </tr>
        <tr>
          <td class="textcenter">4.</td>
          <td>Self exposure to needless peril (except in an attempt to save human life);</td>
        </tr>
        <tr>
          <td class="textcenter">5.</td>
          <td>Any Pre-existing Condition or any complication arising from it.</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2"><b>Claim Procedure:</b></td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2">All claims under this policy will be processed and settled by us.  </td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2">You can get in touch with us as below -</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td class="textcenter"> > </td>
          <td>Please call Our 24-hour Toll Free Call Center on 1-800-119966 or 022-66939500 (tolled) or 1800 22 9966 (only for senior citizen policy holders)  </td>
        </tr>
        <tr>
          <td class="textcenter"> > </td>
          <td>Email at general.claims@tataaig.com / customersupport@tataaig.com.</td>
        </tr>
        <tr>
          <td class="textcenter"> > </td>
          <td>Fax: 022-6654 6464</td>
        </tr>
        <tr>
          <td class="textcenter"> > </td>
          <td>SMS with word ‘CLAIMS’ @ 5616181</td>
        </tr>
        <tr>
          <td class="textcenter"> > </td>
          <td>Write to us:  <br>Claims Department – Accident & Health,<br>Tata AIG General Insurance Company Limited,<br>501, 5th floor, Building N. 4, Infinity park, Dindoshi, Malad (E), Mumbai – 400 097</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td class="textcenter"> > </td>
          <td><b><u>Documents for claims:</u></b></td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2">An indicative document list in case of claim is as given below. Additional documents/ information may be called for and/or we may carry out verification where felt necessary.</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0" class="boxtable font-8">
        <tr>
          <td width="50%">
            <table cellpadding="2" border="0" cellspacing="0">
              <tr>
                <td colspan="2"><b>Death Claims</b></td>
              </tr>
              <tr>
                <td width="5%">1. </td>
                <td width="95%">Claim form</td>
              </tr>
              <tr>
                <td>2.  </td>
                <td>Original Death Certificate</td>
              </tr>
              <tr>
                <td>3.  </td>
                <td>Original/ Attested Post Mortem Report, if conducted </td>
              </tr>
              <tr>
                <td>4.  </td>
                <td>Attested copy of FIR, Spot Panchanama & Police Inquest report, where applicable. </td>
              </tr>
              <tr>
                <td>5.  </td>
                <td>Complete medical records including Death Summary, in case of hospitalization</td>
              </tr>
              <tr>
                <td>6.  </td>
                <td>Any other document requested by the Company in view of claim</td>
              </tr>
              <tr>
                <td>7.  </td>
                <td>KYC Documents</td>
              </tr>
            </table>
          </td>
          <td width="50%">
            <table cellpadding="2" border="0" cellspacing="0">
              <tr>
                <td colspan="2"><b>Disability Claims</b></td>
              </tr>
              <tr>
                <td width="5%">1. </td>
                <td width="95%">Claim form</td>
              </tr>
              <tr>
                <td>2.  </td>
                <td>Attending Doctor’s Report</td>
              </tr>
              <tr>
                <td>3.  </td>
                <td>Original Disability Certificate from the Doctor </td>
              </tr>
              <tr>
                <td>4.  </td>
                <td>Complete medical records including Investigation/ Lab reports (X-Ray , MRI etc.)</td>
              </tr>
              <tr>
                <td>5.  </td>
                <td>FIR, Police report, where applicable</td>
              </tr>
              <tr>
                <td>6.  </td>
                <td>Any other document requested by the Company in view of claim</td>
              </tr>
              <tr>
                <td>7.  </td>
                <td>KYC Documents</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td height="150"></td>
        </tr>
        <tr>  
          <td class="textcenter font-8" style="color:#808080;">Tata AIG General Insurance Company Limited <br>Registered Office: : Peninsula Business Park, Tower A, 15th Floor, G. K. Marg, Off Senapati Bapat Road, Lower Parel, Mumbai- 400013. Toll Free Helpline No. 1800 266 7780 / 1800119966. Website: www.tataaiginsurance.in <br>IRDA Registration Number: 108   CIN: U85110MH2000PLC128425 <br>Group Personal Accident and Business Travel Accident Policy– UIN: IRDA/NL-HLT/TAGI/P-P/V.I/290/13-14</td>
        </tr>
      </table>  
      </td>     
  </tr>
</table>
EOD;
  $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        if($dealer_id == 2871 || $dealer_id == 2872){
            $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 5, 150, 250, '', '', '', true, 500);
          }
        $pdf->writeHtml($html2);
        //"policy"-firtsandlastnamecompany-policynumber
        $pdf_to_name = "TATA-RSA-Policy- .'$certificate_no'.pdf";
        ob_clean();
      $result= $pdf->Output($pdf_to_name, 'S');
      return $result;
    }



 public function DownloadBhartiFullPolicy($id) {
        $rsa_policy_data = $this->HomeMdl->getPolicyById($id);
        // echo '<pre>'; print_r($rsa_policy_data);die('hello');
        $getDealerInfo = $this->HomeMdl->getDealerinfo($rsa_policy_data['user_id']);
        // echo '<pre>';print_r($getDealerInfo);die;
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

        //master policy detils
        $master_policy_no = isset($rsa_policy_data['master_policy_no']) ? strtoupper($rsa_policy_data['master_policy_no']) : ' ';
        $mp_start_date = isset($rsa_policy_data['mp_start_date']) ? $rsa_policy_data['mp_start_date'] : ' ';
        $date = new DateTime($mp_start_date);
        $mp_start_date = $date->format('d-M-Y');
        $mp_end_date = isset($rsa_policy_data['mp_end_date']) ? $rsa_policy_data['mp_end_date'] : ' ';

        $email = isset($rsa_policy_data['email']) ? strtoupper($rsa_policy_data['email']) : '--';
        $mobile_no = isset($rsa_policy_data['mobile_no']) ? strtoupper($rsa_policy_data['mobile_no']) : '--';
        $gender = isset($rsa_policy_data['gender']) ? strtoupper($rsa_policy_data['gender']) : '--';
        $dob = isset($rsa_policy_data['dob']) ? $rsa_policy_data['dob']: '--';
        $addr1 = isset($rsa_policy_data['addr1']) ? strtoupper($rsa_policy_data['addr1']) : '--';
        $addr2 = isset($rsa_policy_data['addr2']) ? strtoupper(mb_strimwidth($rsa_policy_data['addr2'], 0, 20, "...")) : '--';
        $pin_code = isset($rsa_policy_data['pin_code']) ? $rsa_policy_data['pin_code'] : ' ';
        $state_name = isset($rsa_policy_data['state_name']) ? strtoupper($rsa_policy_data['state_name']) : '--';
        $city_name = isset($rsa_policy_data['city_name']) ? strtoupper($rsa_policy_data['city_name']) : '--';
        $full_address = $addr1.' '.$addr2.' '.$city_name.'-'.$pin_code.' '.$state_name;
        $sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '--';
        $sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date'] : '--';
        $pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '--';
        $pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '0000-00-00 00:00:00';
        // $pa_sold_policy_effective_date = '2019-09-01 23:59:59';
        $imp_note ='';
        if( strtotime($pa_sold_policy_effective_date) >= strtotime('2019-09-05 21:59:59') ){
            $imp_note = '<table cellpadding="1" border="0" cellspacing="0">
                          <tr>  
                              <td colspan="2"></td>            
                          </tr>
                          <tr>  
                              <td colspan="2"><b>Important Notes:</p></td>            
                          </tr>
                          <tr>
                            <td width="2%">1</td>
                            <td width="98%">The personal accident coverage is applicable only to the owner of the vehicle for which Road Side Assistance service has been provided by the Indicosmic Capital Pvt Ltd.</td>
                          </tr>
                          <tr>  
                              <td>2</td>
                              <td>The said personal accident cover is active only whilst the owner is driving or travelling in the said vehicle including mounting into/dismounting from the said vehicle.</td>
                          </tr>
                          <tr>  
                              <td>3</td>
                              <td>Death or permanent total disability claims due to any other incidence would not be covered</td>
                          </tr>
                          <tr>  
                              <td>4</td>
                              <td>The policy is valid for 365 days from the policy risk start date</td>
                          </tr>
                          
                        </table>  ';
        }
        $date = new DateTime($pa_sold_policy_effective_date);
        $pa_sold_policy_effective_date = $date->format('d-M-Y');
        $created_time = date("H:i:s",strtotime($created_date));
        $pa_sold_policy_effective_date = $pa_sold_policy_effective_date.' '.$created_time;
        $pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '--';
        $date = new DateTime($pa_sold_policy_end_date);
        $pa_sold_policy_end_date = $date->format('d-M-Y H:i:s');
          $period_of_insurance = 'From 12 Hrs:00 Mins on'.$pa_sold_policy_effective_date.' To midnight of '.$pa_sold_policy_end_date;
        $model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';
        $this->load->library('Tcpdf/Tcpdf.php');
        ob_start();
        $this->load->library('Tcpdf/Tcpdf.php');
        $this->load->library('Ciqrcode');

        $params['data'] = "Name: '" . $full_name_of_insured . "' , POLICY NO: '" . $certificate_no . "', FROM: '" . $pa_sold_policy_effective_date . "' , TO: '" . $pa_sold_policy_end_date . "'";
        $params['level'] = 'H';
        $params['size'] = 5;
        $params['savename'] = FCPATH . 'assets/images/qr_image/'.trim($certificate_no).'.png';
        $this->ciqrcode->generate($params);
        $qr_code_image_url = '<img src="assets/images/qr_image/'.trim($certificate_no).'.png" width="60px" />';
        $pdf = new TCPDF();
        $pdf->SetFont('helvetica', '', 7, '', 'default', true);


        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        // set margins
        $pdf->SetMargins(3, 8, 5);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 2);

     

        // first page
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
<div style="margin: 0 auto; width: 660px;">
  <table width="100%" cellpadding="0" border="0" cellspacing="0">
   <tr>
      <td><img src="assets/images/mpdf/banner.jpg" alt="" style="width:660px;"></td>
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
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Certificte Number</b></td>
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
            <tr>
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
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Plan Ammount(Rs.)</b></td>

                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Tax Ammount(18% IGST in Rs.)</b></td>
                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Total Ammount (Rs.)</b></td>
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
          </tr>
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
            <tr>
    <td style="height:100px;">&nbsp;</td>
            </tr>
   <tr>
    <td style="padding: 20px 0 0;">
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
          <td width="82%">In the event that a Covered Vehicle is immobilized due to electrical or mechanical failure, ICPL shall arrange towing of the Covered Vehicle to the nearest authorized outlet. These services shall be provided using equipment/s deemed most suitable by ICPL. Towing up to $km_covered km from incident to nearest garage is free.</td>
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
    </td>
   </tr>

  </table>
</div>
EOD;



$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
        $pdf->AddPage();
         if($dealer_id == 2871 || $dealer_id == 2872){
          $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 5, 150, 250, '', '', '', true, 500);
         }

        $pdf->writeHtml($html);

$html2= <<<EOD
<br pagebreak="true" />
<style>
  .pagewrap {color: #000; font-size: 8.5pt; line-height:10pt; color:#000;}
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
  .border, .boxtable td {border:0.2px solid #000;}
  .tb-heading {background-color:#0070c0; color:#fff; font-wieght:bold;}
</style>

<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td height="70" class="textleft">{$qr_code_image_url}</td>
          <td height="70" class="textright"><img src="assets/images/mpdf/bharti-axa-logo-big.jpg" height="60"></td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0" class="textcenter">
        <tr>
          <td class="font-9 line-height-15"><b><u>Personal Accident Policy  - Certificate <br>Master Policy Holder Name:</u></b>
          </td>

        </tr> 
        <tr>
          <td class="font-9 line-height-15"><b><u> INDICOSMIC CAPITAL PVT. LTD.</u></b>
          </td>
        </tr>          
        <tr>
          <td></td>
        </tr>   
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td>IMD Code: 13001409</td>
          <td>IMD Name: GLOBAL INDIA INSURANCE BROKERS PVT LTD</td>
        </tr>
        <tr>
          <td>Broker Code: IRDA/DB-596/14</td>
          <td>Broker Contact: 022-26820489</td>
        </tr>
        <tr>
          <td>Master Policy No.: $master_policy_no</td>
          <td>Certificate No.: $certificate_no</td>
        </tr>
        <tr>
          <td>Name of Insured: {$full_name_of_insured}</td>
          <td rowspan="2">Address: {$full_address}</td>
        </tr>
        <tr>
          <td>Period of Insurance: {$period_of_insurance}</td>
        </tr>       
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable textcenter">
        <tr>
          <td width="25%">Name of Member</td>
          <td width="12%">Gender</td>
          <td width="18%">Date of Birth</td>
          <td width="20%">Name of Nominee</td>
          <td width="25%">Relationship of nominee with insured</td>
        </tr>
        <tr>
          <td>{$full_name_of_insured}</td>
          <td>{$gender}</td>
          <td>{$dob}</td>
          <td>{$nominee_name}</td>
          <td>{$nominee_relation}</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="12%">Section</td>
          <td width="60%" class="textcenter">Coverage</td>
          <td width="15%" class="textcenter">Sum Insured</td>
          <td width="13%" class="textcenter">Scope of Cover</td>
        </tr>
        <tr>
          <td>Accidental Death</td>
          <td>If any Insured Person  sustains Injury during the policy period which directly and independently of all other causes result in death within 12 Months from the date of accident, the company agrees to pay to the Insured Person’s Nominee, Beneficiary or legal representative, the Sum Insured specified in the Schedule/Certificate of Insurance</td>
          <td rowspan="2" class="textcenter">Rs.15,00,000/-</td>
          <td rowspan="2" class="textcenter">Within India</td>
        </tr>
        <tr>
          <td>Permanent Total Disablement (PTD)</td>
          <td>If any Insured Person sustains Injury during the policy period which directly and independently of all other causes result in any of the disablement stated in the table below and opted for by the Policyholder/Insured Person as indicated in the Policy Schedule/Certificate of Insurance, within twelve months from the date of accident, the company agrees to pay to the Insured Person, the Sum Insured specified in the Schedule of the policy</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td height="10"></td>
        </tr>
        <tr>
          <td class="textright"><img src="assets/images/mpdf/sign-img.jpg" height="40"></td>
        </tr>
        <tr>
          <td class="textright">For Bharti AXA General Insurance Company Limited</td>
        </tr>
        <tr>
          <td height="10"></td>
        </tr>
        <tr>
          <td>The policy has been issued based on the information provided by you/your representative and the policy shall become Null and Void, if the submissions are incorrect or untrue, misrepresented, non disclosure or withholding of material information by the proposer or anyone acting on behalf to obtain benefit under the policyis not valid if any of the information provided is incorrect.<br>Subject otherwise to the Terms, Conditions and Exclusions mentioned in the Policy Wordings. </td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td><b>General Exclusions:</b> The Company shall not be liable to make any payment for any claim directly or indirectly caused by or, based on or, arising out of or howsoever attributable to any of the following:</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td width="2%">•</td>
          <td width="98%">Any Pre-existing Condition(s) and complications, suicide or intentional self inflicted injury, Mental or nervous disorder, anxiety, stress or depression.</td>
        </tr>
        <tr>
          <td>•</td>
          <td>Injury whilst under the influence of liquor or drugs, alcohol or other intoxicants, participation in any adventure sport unless specifically covered under the policy</td>
        </tr>
        <tr>
          <td>•</td>
          <td>Injury, directly or indirectly, caused by or arising from or attributable to foreign invasion, act of foreign enemies, hostilities (whether war be declared or not), civil war, rebellion, revolution, insurrection, military or usurped power, riot, strike, lockout, military or popular uprising or civil commotion, act of terrorism or any terrorist incident.</td>
        </tr>
        <tr>
          <td>•</td>
          <td>Operating or learning to operate any aircraft, or performing duties as a member of the crew on any aircraft; or Scheduled Airlines</td>
        </tr>
        <tr>
          <td colspan="2">Refer the Policy wordings for detailed list of policy exclusions</td>
        </tr>
      </table>
     {$imp_note}
      </td>     
  </tr>
</table>
EOD;

  $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        if($dealer_id == 2871 || $dealer_id == 2872){
          $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 5, 150, 250, '', '', '', true, 500);
        }
        $pdf->writeHtml($html2);
$html3= <<<EOD
<style>
  .pagewrap {color: #000; font-size: 8.5pt; line-height:12pt; color:#000;}
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
  .border, .boxtable td {border:0.2px solid #000;}
  .tb-heading {background-color:#0070c0; color:#fff; font-wieght:bold;}
</style>
<br pagebreak="true" />
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td colspan="2"><b><u>Claim Intimation:</u></b></td>
        </tr>
        <tr>
          <td colspan="2">It is the endeavor of Company to give multiple options to the Insured person to intimate the claim to the Company. The intimation can be given in following ways:</td>
        </tr>
        <tr>
          <td width="2%">•</td>
          <td width="98%">Toll Free call Centre of the Insurance Company (24x7) 1800-103-2292</td>
        </tr>
        <tr>
          <td>•</td>
          <td>Login to the website of the Insurance Company and intimate the claim http://www.bhartiaxagi.co.in/contact-us</td>
        </tr>
        <tr>
          <td>•</td>
          <td>Send an email to the Company customer.service@bharti-axagi.co.in</td>
        </tr>
        <tr>
          <td>•</td>
          <td>Directly Contacting our Company office but in writing.</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td><b><u>Claim Form</u></b></td>
        </tr>
        <tr>
          <td>Upon the notification of the claim, the Company will dispatch the claim form to the Insured/Covered person. Claim forms will also be available with the Company offices and on its website.</td>
        </tr>
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td colspan="2"><b><u>Claim Procedure</b></u></td>
        </tr>     
        <tr>
          <td width="2%">•</td>
          <td width="98%">The Company shall be under no obligation to make any payment under this Policy unless all the premium payments are received in full and all payments have been realized.</td>
        </tr>
        <tr>
          <td>•</td>
          <td>The Company will only make payment as per the Policyholder/ Insured’s direction. In case of Insured’s unfortunate demise, the Company will only make payment to the Nominee (as named in the Policy Schedule/ Certificate of Insurance).</td>
        </tr>
        <tr>
          <td>•</td>
          <td>This Policy only covers medical treatment taken in India, and payments under this Policy shall only be made in Indian Rupees within India.</td>
        </tr>
        <tr>
          <td>•</td>
          <td>The Company is not obliged to make payment for any claim or that part of any claim that could have been avoided or reduced if the Insured / Insured Person could reasonably have minimised the costs incurred, or that is brought about or contributed to by the Insured/ Insured Person failing to follow the directions, advice or guidance provided by a Medical Practitioner.</td>
        </tr>
        <tr>
          <td>•</td>
          <td>The Company will process the claims and make claim payments.</td>
        </tr>
        <tr>
          <td>•</td>
          <td>If there is any deficiency in the documents / information submitted by Insured person, the Company will send the deficiency letter within 7 days of receipt of the claim documents.</td>
        </tr>
        <tr>
          <td>•</td>
          <td>On receipt of the complete set of claim documents to the Company’s satisfaction, the Company will send offer of settlement, along with a settlement statement within 30 days to the insured. Payment will be made within 7 days of receipt of acceptance of such settlement offer.</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2"><b><u>Grievance Redressal Procedure</u></b></td>
        </tr>
        <tr>
          <td colspan="2">If Policy holder / Insured Person have agrievance that he/she wish us to redress, he/she may contact the Company with the details of their grievance via</td>
        </tr>
        <tr>
          <td>•</td>
          <td>Website: www.bharti-axagi.co.in</td>
        </tr>
        <tr>
          <td>•</td>
          <td>Email: customer.service@bharti-axagi.co.in</td>
        </tr>
        <tr>
          <td>•</td>
          <td>Phone: 080-49123900</td>
        </tr>
        <tr>
          <td>•</td>
          <td>Courier: Any of the Company’s Branch office or corporate office</td>
        </tr>
        <tr>
          <td colspan="2">Policy holder / Insured / Insured Person may also approach the grievance cell at any of the Company’s branches with the details of the grievance during working hours from Monday to Friday.</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td><b><u>Escalation Level 1</u></b></td>
        </tr>
        <tr>
          <td>For lack of are sponseor if the resolution still does not meet the expectations through one of the above methods, Policy holder / Insured / Insured Person may contact the Company’s Head of Customer Service at: Bharti AXA General Insurance Co. Ltd., 19th Floor, Parinee Cresenzo, G Block, Bandra Kurla Complex, Bandra (E), Mumbai– 400053</td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td><b><u>Escalation Level 2</u></b></td>
        </tr>
        <tr>
          <td>In case the Policy holder / Insured / Insured Person has not got his / her grievances redressed by the Company within 14days, or, If Policy holder / Insured / Insured Person is not satisfied with Company’s redress al of the grievance through one of the above methods, they may approach the nearest Insurance Ombudsman for resolution of their grievance</td>
        </tr>
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td colspan="2"><b><u>Grievance of Senior Citizens</u></b></td>
        </tr>
        <tr>
          <td colspan="2">Inrespect of Senior Citizens, the Company has established a separate channel to address the grievances. Any concerns may be directly addressed to the Senior Citizen’s channel of the Company for faster attention or speedy disposal of grievance, if any.</td>
        </tr>
        <tr>
          <td width="2%">•</td>
          <td width="98%">Website: www.bharti-axagi.co.in</td>
        </tr>
        <tr>
          <td>•</td>
          <td>Email: customer.service@bharti-axagi.co.in</td>
        </tr>
        <tr>
          <td>•</td>
          <td>Phone: 080-49123900</td>
        </tr>
        <tr>
          <td colspan="2">Courier: Any of the Company’s Branch office or corporate office</td>
        </tr>
      </table>  
      </td>     
  </tr>
</table>
EOD;

  
  $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        if($dealer_id == 2871 || $dealer_id == 2872){
            $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 5, 150, 250, '', '', '', true, 500);
          }
        $pdf->writeHtml($html3);
        //"policy"-firtsandlastnamecompany-policynumber
        $pdf_to_name = "BhartiAxa-RSA-Policy- .'$certificate_no'.pdf";
        ob_clean();
      
        $policy_pdf = $pdf->Output($pdf_to_name, 'S');
        return $policy_pdf;
    }


 public function ICICI_full_Pdf($id){
        $rsa_policy_data = $this->HomeMdl->getPolicyById($id);
        // echo '<pre>'; print_r($rsa_policy_data);die('hello');
        $where = array(
          'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
        );
        $master_policy_details = $this->HomeMdl->getRowDataFromTable('ic_pa_master_policy_nos',$where);
        $master_policy_location = strtoupper($master_policy_details['mp_localtion']);
        $master_policy_address = strtoupper($master_policy_details['address']);
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
        $created_date = isset($rsa_policy_data['created_at']) ? $rsa_policy_data['created_at']: '--';
        $fname = isset($rsa_policy_data['fname']) ? strtoupper($rsa_policy_data['fname']) : '--';
        $lname = isset($rsa_policy_data['lname']) ? strtoupper($rsa_policy_data['lname']) : '--';
        $full_name_of_insured = $fname.' '.$lname;
        $nominee_name = isset($rsa_policy_data['nominee_full_name']) ? strtoupper($rsa_policy_data['nominee_full_name']) : '--';
        $nominee_relation = isset($rsa_policy_data['nominee_relation']) ? strtoupper($rsa_policy_data['nominee_relation']) : '--';
        $nominee_age = isset($rsa_policy_data['nominee_age']) ? strtoupper($rsa_policy_data['nominee_age']) : '--';

        $appointee_name = isset($rsa_policy_data['appointee_full_name']) ? strtoupper($rsa_policy_data['appointee_full_name']) : ' ';
        $appointee_relation = isset($rsa_policy_data['appointee_relation']) ? strtoupper($rsa_policy_data['appointee_relation']) : ' ';
        $appointee_age = isset($rsa_policy_data['appointee_age']) ? strtoupper($rsa_policy_data['appointee_age']) : ' ';

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
        $sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '0000-00-00';
        // $pa_sold_policy_effective_date ='2019-09-01 23:59:59';
        $pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '0000-00-00';
         $imp_note ='';
        if( strtotime($pa_sold_policy_effective_date) >= strtotime('2019-09-05 21:59:59') ){
            $imp_note = '<tr>
            <td style="color:#365f91;">7. The personal accident coverage is applicable only to the owner of the vehicle for which Road Side Assistance service has been provided by the Indicosmic Capital Pvt Ltd. <br>8. The said personal accident cover is active only whilst the owner is driving or travelling in the said vehicle including mounting into/dismounting from the said vehicle. <br>9. Death or permanent total disability claims due to any other incidence would not be covered <br>10. The policy is valid for 365 days from the policy risk start date</td>
          </tr>';
        }
        $sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date'] : '--';
        $date = new DateTime($pa_sold_policy_effective_date);
        $pa_sold_policy_effective_date = $date->format('d-M-Y');
        $created_time = date("H:i:s",strtotime($created_date));
        $pa_sold_policy_effective_date = $pa_sold_policy_effective_date.' '.$created_time;
        $pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '--';
        $date = new DateTime($pa_sold_policy_end_date);
        $pa_sold_policy_end_date = $date->format('d-M-Y H:i:s');
        $model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';
        $this->load->library('Tcpdf/Tcpdf.php');
        ob_start();
        $this->load->library('Tcpdf/Tcpdf.php');
        $this->load->library('Ciqrcode');

        $params['data'] = "Name: '" . $full_name_of_insured . "' , POLICY NO: '" . $certificate_no . "', FROM: '" . $pa_sold_policy_effective_date . "' , TO: '" . $pa_sold_policy_end_date . "'";
        $params['level'] = 'H';
        $params['size'] = 5;
        $params['savename'] = FCPATH . 'assets/images/qr_image/'.trim($certificate_no).'.png';
        $this->ciqrcode->generate($params);
        $qr_code_image_url = '<img src="assets/images/qr_image/'.trim($certificate_no).'.png" width="50px" />';
        $pdf = new TCPDF();
        $pdf->SetFont('helvetica', '', 8, '', 'default', true);

        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        // set margins
        $pdf->SetMargins(3, 8, 5);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 2);
        // $pdf->AddPage();

        // first page
        $html1 = <<<EOD

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
<div style="margin: 0 auto; width: 660px;">
  <table width="100%" cellpadding="0" border="0" cellspacing="0">
   <tr>
      <td><img src="assets/images/mpdf/banner.jpg" alt="" style="width:660px;"></td>
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
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Certificte Number</b></td>
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
            <tr>
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
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Plan Ammount(Rs.)</b></td>

                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Tax Ammount(18% IGST in Rs.)</b></td>
                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Total Ammount (Rs.)</b></td>
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
          </tr>
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
            <tr>
    <td style="height:100px;">&nbsp;</td>
            </tr>
   <tr>
    <td style="padding: 20px 0 0;">
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
          <td width="82%">In the event that a Covered Vehicle is immobilized due to electrical or mechanical failure, ICPL shall arrange towing of the Covered Vehicle to the nearest authorized outlet. These services shall be provided using equipment/s deemed most suitable by ICPL. Towing up to $km_covered km from incident to nearest garage is free.</td>
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
    </td>
   </tr>

  </table>
</div>
EOD;



$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
        $pdf->AddPage();
         if($dealer_id == 2871 || $dealer_id == 2872){
          $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 5, 150, 250, '', '', '', true, 500);
         }

        $pdf->writeHtml($html1);



        $html = '<style>
  .pagewrap {color: #000; font-size: 8pt; line-height:11pt; color:#000;}
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
  .border, .boxtable td {border:0.2px solid #000;}
  .boxtable td.heading {background-color:#d9d9d9; color:#000; font-weight:bold;}
  .sectionhead { font-size:7.5pt; line-height:12pt; background-color:#000; color:#fff;}
</style>

<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
    <table cellpadding="0" border="0" cellspacing="0">
      <tr>
        <td  class="textleft" style="width:50%;">
        '.$qr_code_image_url.'
        </td>
          <td class="textright" style="width:50%;"><img src="'.base_url('ICICI_Lombard_Policy_Certificate').'/images/icici_lombard.png" height="30px"></td>
        </tr>   
 </table>        
 <table cellpadding="0" border="0" cellspacing="0">
        <tr>
          <td><img src="'.base_url('ICICI_Lombard_Policy_Certificate').'/images/sepline-top.png" height="15"></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table cellpadding="4" border="0" cellspacing="0" class="textcenter line-height-13">  
        <tr>      
          <td></td>
        </tr>
        <tr>
          <td class="font-10 line-height-16" style="color:#365f91;"><b><u>Policy Certificate – Group Personal Accident</u></b></td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0">
        <tr>
          <td style="text-align:justify;">ICICI Lombard Group Personal Accident Policy no. '.$master_policy_no.' dated '.$mp_start_date.' has been issued at Mumbai, by ICICI Lombard General Insurance Company Limited to the Policyholder '.$mp_end_date.',,as specified in the policy and is governed by, and is subject to, the terms, conditions & exclusions therein contained or otherwise expressed in the said policy, but not exceeding the sum insured as specified in Part I of the schedule to the said policy.</td>
        </tr>
        <tr>
          <td style="text-align:justify;">This certificate, issued under the signature of an authorized signatory of the Company represents the availability of benefit to the insured named below, Customers of <b>Indicosmic Capital Pvt. Ltd.</b> subject to the terms, conditions and exclusions contained or otherwise expressed in the said Policy to the extent of sum insured mentioned as maximum liability, but not exceeding the Sum Insured as specified below.</td>
        </tr>
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0">        
        <tr>
          <td>
            <table cellpadding="4" border="0" cellspacing="0" class="boxtable textleft" width="90%">
              <tr>
                <td width="50%"><b>Policy No.</b></td>
                <td width="50%">'.$certificate_no.'</td>         
              </tr>
              <tr>
                <td><b>Policy Tenure</b></td>
                <td>1 Year</td>
              </tr>
              <tr>
                <td><b>Period of Insurance</b></td>
                <td>From: '.$pa_sold_policy_effective_date.'&nbsp;&nbsp;&nbsp;&nbsp; To:'.$pa_sold_policy_end_date.'</td>
              </tr>
              <tr>
                <td><b>Insured Name</b></td>
                <td>'.$full_name_of_insured.'</td>
              </tr>
              <tr>
                <td><b>Insured Address</b></td>
                <td>'.$addr1.'</td>
              </tr>
              <tr>
                <td><b>Contact No.</b></td>
                <td>'.$mobile_no.'</td>
              </tr>
              <tr>
                <td><b>Email ID</b></td>
                <td>'.$email.'</td>
              </tr>
              <tr>
                <td><b>Policy Issuing Office</b></td>
                <td>'.$master_policy_location.'</td>
              </tr>       
            </table>
          </td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0">  
        <tr>
          <td></td>
        </tr>
        <tr>
          <td><b>PREAMBLE</b></td>
        </tr>
        <tr>
          <td style="text-align:justify;">ICICI Lombard General Insurance Company Limited (“the Company”), having received a Proposal and the premium from the Policy holder named in the Schedule referred to herein below, and the said Proposal and Declaration together with any statement, report or other document leading to the issue of this Policy and referred to therein having been accepted and agreed to by the Company and the Policy holder as the basis of this contract do, by this Policy agree, in consideration of and subject to the due receipt of the subsequent premiums, as set out in the Schedule with all its Parts, and further,  subject to the terms and conditions contained in this Policy, as set out in the Schedule with all its Parts that on proof to the satisfaction of the Company of the compensation having become payable as set out in Part I of the Schedule to the title of the said person or persons claiming payment or upon the happening of an event upon which one or more benefits become payable under this Policy, the Sum Insured/ appropriate benefit amount will be paid by the Company.</td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0">  
        <tr>
          <td></td>
        </tr>
        <tr>
          <td><b>Insured Details</b></td>
        </tr>     
        <tr>
          <td>
            <table cellpadding="4" border="0" cellspacing="0" class="boxtable textcenter">
              <tr>
                <td class="heading" width="6%">Sr No:</td>
                <td class="heading" width="14%">Name in Full</td>
                <td class="heading" width="10%">Date of Birth</td>
                <td class="heading" width="8%">Gender</td>
                <td class="heading" width="12%">Occupation</td>
                <td class="heading" width="10%">Risk Category</td>
                <td class="heading" width="11%">Relation with Proposer</td>
                <td class="heading" width="11%">Beneficiary / Nominee</td>
                <td class="heading" width="18%">Relation of Nominee with the Insured</td>
              </tr>
              <tr>
                <td></td>
                <td>'.$full_name_of_insured.'</td>
                <td>'.$dob.'</td>
                <td>'.$gender.'</td>
                <td></td>
                <td></td>
                <td>RSA Policy Holder</td>
                <td>'.$nominee_name.'</td>
                <td>'.$nominee_relation.'</td>
              </tr>     
            </table>
          </td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0">  
        <tr>
          <td></td>
        </tr>
        <tr>
          <td><b>Benefit & Extension Table</b></td>
        </tr>     
        <tr>
          <td>
            <table cellpadding="4" border="0" cellspacing="0" class="boxtable textleft">
              <tr>
                <td width="12%"><b>Benefit </b></td>
                <td width="28%"><b>Cover</b></td>
                <td width="40%"><b>Benefit Amount</b></td>
                <td width="20%"><b>Sum Insured (Rs.)</b></td>
              </tr>
              <tr>
                <td>Benefit 1</td>
                <td>Death resulting from Accident</td>
                <td>To pay Sum Insured as mentioned against Death benefit on the occurrence of death of the Insured Person, provided such death results solely and directly from an Injury, within twelve months from the date of Accident</td>
                <td rowspan="2">'.$sum_insured.'</td>
              </tr>
              <tr>
                <td>Benefit 2</td>
                <td>Permanent Total Disablement (PTD) resulting from Accident</td>
                <td>To pay Sum Insured on the occurrence of PTD which result solely and directly from an Injury, within twelve months from the date of Accident</td>
              </tr>     
            </table>
          </td>
        </tr>
      </table>  
      </td>     
  </tr>
  <tr>
    <td>
      <table cellpadding="0" border="0" cellspacing="0">
        <tr>  
          <td></td>
        </tr> 
        <tr>
          <td><img src="'.base_url('ICICI_Lombard_Policy_Certificate').'/images/sepline-bot.png" height="10"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="0" border="0" cellspacing="0">
        <tr>
          <td class="textright"><img src="'.base_url('ICICI_Lombard_Policy_Certificate').'/images/icici_lombard.png" height="40px"></td>
        </tr>   
        <tr>
          <td></td>
        </tr>
        <tr>
          <td><img src="'.base_url('ICICI_Lombard_Policy_Certificate').'/images/sepline-top.png" height="15"></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table cellpadding="4" border="0" cellspacing="0">  
       
        <tr>
          <td></td>
        </tr>     
        <tr>
          <td>
            <table cellpadding="4" border="0" cellspacing="0" class="boxtable textcenter">
              <tr>
                <td class="heading" width="30%">GSTIN Reg. No</td>
                <td class="heading" width="30%">IRDA Reg No</td>
                <td class="heading" width="40%">Category</td>
              </tr>
              <tr>
                <td>27AAACI7904G1ZN</td>
                <td>115</td>
                <td>GENERAL INSURANCE SERVICES 9971</td>
              </tr>     
            </table>
          </td>
        </tr>
        <tr>
          <td></td>
        </tr>     
        <tr>
          <td>
            <table cellpadding="4" border="0" cellspacing="0" class="boxtable textcenter">
              <tr>
                <td class="heading" colspan="6">Agent / Broker Details</td>
              </tr>
              <tr>
                <td width="12%"><b>Broker Name</b></td>
                <td width="30%">Global India Insurance Brokers Pvt Ltd.</td>
                <td width="14%"><b>Broker Code</b></td>
                <td width="14%">IRDA/DB-596/14</td>
                <td width="16%"><b>Broker contact No.</b></td>
                <td width="14%">022-49707493</td>
              </tr>     
            </table>
          </td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td style="color:#365f91;"><b>Important Notes:</b> <br>1. Insurance cover will start only on receipt of complete premium by ICICI Lombard General Insurance Company Limited <br>2. Insurance cover is subject to the terms and conditions mentioned in the Policy wordings provided to you with this Certificate <br>3. The above covers would not be applicable for persons occupied in underground mines, explosives and electrical installations on high tension lines <br>4. Major exclusions: Intentional self-injury, suicide or attempted suicide whilst under the influence of intoxicating liquor or drugs, Any loss arising from an act made in breach of law with or without criminal intent. <br>5. The claimant can contact us at Toll Free Number 1800-2-666 or Email us at customersupport@icicilombard.com for lodging the claim. <br>6. Claim Notification address: IL Health Care, Secure Mind Claims, ICICI LOMBARD HEALTHCARE ICICI BANK TOWER, PLOT NO.12FINANCIAL DISTRICT, NANAKRAM GUDA, GACHIBOWLI, HYDERABAD </td>
        </tr>
       '.$imp_note.'
        <tr>
          <td></td>
        </tr>
        <tr>
          <td>
            <table cellpadding="4" border="0" cellspacing="0" class="boxtable">             
              <tr>
                <td width="30%" style="background-color:#d9d9d9;">
                  <table cellpadding="2" border="0" cellspacing="0">  
                    <tr>
                      <td>For ICICI Lombard General Insurance Company ltd.</td>
                    </tr>
                    <tr>
                      <td><img src="'.base_url('ICICI_Lombard_Policy_Certificate').'/images/sign-img.jpg" height="80"></td>
                    </tr>
                    <tr>
                      <td><b>Authorised Signatory</b></td>
                    </tr>
                  </table>
                </td>
                <td width="70%" style="background-color:#d9d9d9;text-align:justify; ">Important: Insurance benefit shall become voidable at the option of the company, in the event of any untrue or incorrect statement, misrepresentation non-description of any material particular in the proposal form/ personal statement, declaration and connected documents, or any material information has been withheld by beneficiary or anyone acting on beneficiary’s behalf to obtain insurance benefit. Please note that any claims arising out of pre-existing illness/ injury/ symptoms is excluded from the scope of this policy subject to applicable terms and conditions. Refer to policy wordings for the terms and conditions. All disputes are subject to the jurisdiction of Mumbai High Court only. For claims, please call us at our toll free no. 1800 2666 or e-mail to us at ihealthcare@icicilombard.com or write to us at ICICI Lombard GIC, ICICI Bank Tower, Plot no-12, Financial district Nanakramguda, Gachibowli, Hyderabad, Andhra Pradesh 500032.</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align:justify;">This policy has been issued based on the details furnished by the policyholder. Please review the details furnished in the policy certificate and confirm that same are in order. In case of any discrepancy/ variation, you are requested to call us immediately at our toll free no. 1800 2666 or write to us at customersupport@icicilombard.com. In the absence of any communication from you within the period of 15 days of receipt of this document, the policy would be deemed to be in order and issued as per your proposal. All refunds and claim payment will be done through NEFT only. In case of addition of member/ increase in sum insured, fresh waiting period will be applicable to new member/ increased sum insured. This policy certificate is to be read with the policy wordings, as one contract or any word or expression to which a specific meaning has been attached in any part of this policy shall bear the same meaning wherever it may appear.</td>
              </tr> 
            </table>
          </td>
        </tr>
        <tr>
          <td height="100"></td>
        </tr>
      </table>        
      </td>     
  </tr>
  <tr>
    <td>
      <table cellpadding="0" border="0" cellspacing="0">
        <tr>  
          <td></td>
        </tr> 
        <tr>
          <td><img src="'.base_url('ICICI_Lombard_Policy_Certificate').'/images/sepline-bot.png" height="10"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>';
      
      $pdf->AddPage();
      if($dealer_id == 2871){
            $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 5, 150, 250, '', '', '', true, 500);
          }
        $pdf->writeHtml($html);
        //"policy"-firtsandlastnamecompany-policynumber
        $pdf_to_name = "ICICI-LOMBARD-RSA-Policy- .'$certificate_no'.pdf";

        ob_clean();
        $policy_pdf = $pdf->Output($pdf_to_name, 'S');

        // $policy_pdf = $pdf->Output('$pdf_to_name.pdf', 'S');
        return $policy_pdf;
}

  public function DownloadKotakFullPolicy($id) {
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
        $created_date = isset($rsa_policy_data['created_at']) ? $rsa_policy_data['created_at'] : '0000-00-00 00:00:00';
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
        $dob = date("d-M-Y", strtotime($dob) );
        $addr1 = isset($rsa_policy_data['addr1']) ? strtoupper($rsa_policy_data['addr1']) : '--';
        $addr2 = isset($rsa_policy_data['addr2']) ? strtoupper(mb_strimwidth($rsa_policy_data['addr2'], 0, 20, "...")) : '--';
        $state_name = isset($rsa_policy_data['state_name']) ? strtoupper($rsa_policy_data['state_name']) : '--';
        $city_name = isset($rsa_policy_data['city_name']) ? strtoupper($rsa_policy_data['city_name']) : '--';
        $sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '--';
        $sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date']: '--';
        $pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '--';
        // $pa_sold_policy_effective_date = '2019-09-01 23:59:59'
        $imp_note ='';
        $imp_note1='';
        if(strtotime($pa_sold_policy_effective_date) >= strtotime('2019-09-05 21:59:59') ){
          // die('helloi');
          $imp_note1 = '<tr>
                          <td class="textcenter">3</td>
                          <td>Accidental Medical Expenses Extension</td>
                          <td class="textright">upto INR 25,000/-</td>
                        </tr>  ';
        $imp_note = '<table cellpadding="0" border="0" cellspacing="0">
                      <tr>
                        <td class="line-height-20"></td>
                      </tr>
                      <tr>
                        <td class="line-height-20"><b>Important Conditions:</b></td>
                      </tr>
                    </table>
                    
                    <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
                      <tr>
                        <td width="6%" class="textcenter">Sr. No </td>
                        <td width="94%" class="textcenter">Clause Description</td>
                      </tr>
                      
                      <tr>
                        <td class="textcenter">1</td>
                        <td>The personal accident coverage is applicable only to the owner of the vehicle for which Road Side Assistance service has been provided by the Indicosmic Capital Pvt Ltd.</td>
                      </tr>
                      <tr>
                        <td class="textcenter">2</td>
                        <td>The said personal accident cover is active only whilst the owner is driving or travelling in the said vehicle including mounting into/dismounting from the said vehicle.</td>
                      </tr>
                      <tr>
                        <td class="textcenter">3</td>
                        <td>Death or permanent total disability claims due to any other incidence would not be covered</td>
                      </tr>
                      <tr>
                        <td class="textcenter">4</td>
                        <td>The policy is valid for 365 days from the policy risk start date</td>
                      </tr>
                      <tr>
                        <td class="textcenter">5</td>
                        <td>Accidental Medical Expenses Extension We will pay the reimburse the Medical Expenses upto INR 25,000/- incurred by the Insured Person provided that such treatment is following the Accident and If we have admitted a Claim for Accidental Death or Permanent Total Disablement</td>
                      </tr>
                    </table>';
        }

        $date = new DateTime($pa_sold_policy_effective_date);
        $pa_sold_policy_effective_date = $date->format('d-M-Y');
        $created_time = date("H:i:s",strtotime($created_date));
        $pa_sold_policy_effective_date = $pa_sold_policy_effective_date.' '.$created_time;
        $pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '0000-00-00 00:00:00';
        $date = new DateTime($pa_sold_policy_end_date);
        $pa_sold_policy_end_date = $date->format('d-M-Y H:i:s');
        $model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';
        $this->load->library('Tcpdf/Tcpdf.php');
        ob_start();
        $this->load->library('Tcpdf/Tcpdf.php');
        $this->load->library('Ciqrcode');

        $params['data'] = "Name: '" . $full_name_of_insured . "' , POLICY NO: '" . $certificate_no . "', FROM: '" . $pa_sold_policy_effective_date . "' , TO: '" . $pa_sold_policy_end_date . "'";
        $params['level'] = 'H';
        $params['size'] = 5;
        $params['savename'] = FCPATH . 'assets/images/qr_image/'.trim($certificate_no).'.png';
        $this->ciqrcode->generate($params);
        $qr_code_image_url = '<img src="assets/images/qr_image/'.trim($certificate_no).'.png" width="60px" />';
        $pdf = new TCPDF();
        $pdf->SetFont('helvetica', '', 7, '', 'default', true);


        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        // set margins
        $pdf->SetMargins(3, 8, 5);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 2);

     

        // first page
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
<div style="margin: 0 auto; width: 660px;">
  <table width="100%" cellpadding="0" border="0" cellspacing="0">
   <tr>
      <td><img src="assets/images/mpdf/banner.jpg" alt="" style="width:660px;"></td>
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
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Certificte Number</b></td>
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
            <tr>
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
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Plan Ammount(Rs.)</b></td>

                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Tax Ammount(18% IGST in Rs.)</b></td>
                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Total Ammount (Rs.)</b></td>
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
          </tr>
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
            <tr>
    <td style="height:100px;">&nbsp;</td>
            </tr>
   <tr>
    <td style="padding: 20px 0 0;">
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
          <td width="82%">In the event that a Covered Vehicle is immobilized due to electrical or mechanical failure, ICPL shall arrange towing of the Covered Vehicle to the nearest authorized outlet. These services shall be provided using equipment/s deemed most suitable by ICPL. Towing up to $km_covered km from incident to nearest garage is free.</td>
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
    </td>
   </tr>

  </table>
</div>
EOD;



$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
        $pdf->AddPage();
         if($dealer_id == 2871 || $dealer_id == 2872){
          $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 5, 150, 250, '', '', '', true, 500);
         }

        $pdf->writeHtml($html);

$html2= <<<EOD
<br pagebreak="true" />
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

<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
 
  <tr>
      <td width="100%">
      <table cellpadding="2" border="0" cellspacing="0" class="textcenter line-height-10">
        <tr>
          <td width="25%">
          <img src="assets/images/mpdf/kotak-logo.png" width="200">
          </td>
          <td width="50%">
            <table cellpadding="0" border="0" cellspacing="0">
              
              <tr>
                <td><h1>KOTAK GROUP ACCIDENT PROTECT</h1></td>
              </tr>
              <tr>
                <td><b>For any assistance please call 1800 266 4545, <br>please save the number for your reference.</b></td>
              </tr>
              <tr>
                <td><b>FOR RENEWALS: Visit www.kotakgeneralinsurance.com Call 1800 266 4545</b></td>
              </tr>
              <tr>
                <td class="font-8 line-height-13"><b>CERTIFICATE OF INSURANCE</b></td>
              </tr>
            </table>
          </td>
          <td width="25%">
            <table cellpadding="0" border="0" cellspacing="0">
              <tr>  
                <td class="textright">$qr_code_image_url</td>
              </tr>
            </table>
          </td>
        </tr> 
        <tr>
            <td colspan="3"></td>
        </tr>       
      </table>
      <table cellpadding="4" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>&nbsp;</b></td>
        </tr>
      </table>
      <table cellpadding="0" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
        <tr>
          <td>Kotak Group Accident Protect Policy No. $master_policy_no dated $mp_start_date has been issued at {$master_policy_location} by Kotak Mahindra General Insurance Company Limited to the Policyholder, <b>Indicosmic Capital Pvt. Ltd.</b> as specified in the Policy Schedule and is governed by, and subject to the terms, condition and exclusions therein contained or otherwise expressed in the said policy, but not exceeding the Sum Insured as specified in the Policy Schedule to the said policy.This certificate issued under the signature of the authorised signatory of the Company represents the availability of benefits to the Insured person / persons named below,Customers of <b>Indicosmic Capital Pvt. Ltd.</b> subject to the terms, conditions and exclusions contained or otherwise expressed in the said Policy, but not exceeding the Sum Insured as specified below.For the purpose of this document, we consider <b>Indicosmic Capital Pvt. Ltd.</b> as the policyholder and its Customers as the Insured.</td>
        </tr>
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>DETAILS OF THE INSURED PERSON(S) UNDER THE POLICY</b></td>
        </tr>
        <tr>
          <td></td>
        </tr> 
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="27%">Certificate No. </td>
          <td width="24%">$certificate_no</td>
          <td width="15%">Policy Type </td>
          <td width="34%" colspan="2">New</td>          
        </tr>
        <tr>
          <td>Previous Policy No. </td>
          <td></td>
          <td>Issued At </td>
          <td colspan="2">{$master_policy_location}</td>
        </tr>
        <tr>
          <td>Name of the Insured</td>
          <td colspan="2">$full_name_of_insured</td>
          <td width="22%">GSTIN</td>
          <td width="12%"></td>
        </tr>
        <tr>
          <td>Policy Issuing Office </td>
          <td colspan="4">$master_policy_location</td>
        </tr>
        <tr>
          <td rowspan="2">Mailing Address of the Insured</td>
          <td colspan="4">$addr1</td>
        </tr>
        <tr>
          <td colspan="4">$addr2,$city_name ,$state_name</td>
        </tr>
        <tr>
          <td>Contact details of the Insured:</td>
          <td>Mobile No. $mobile_no </td>
          <td>Email Address:</td>
          <td colspan="2">$email</td>         
        </tr>
        <tr>
          <td>Policy Period</td>
          <td>From $pa_sold_policy_effective_date</td>
          <td>To midnight of </td>
          <td colspan="2">$pa_sold_policy_end_date</td>         
        </tr>
        <tr>
          <td>Installment Option</td>
          <td colspan="4">NO</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="50%">Membership ID/ Employee Number/ Account Number pertaining to Credit(#)</td>
          <td width="50%">{$chassis_no} | Financer Name: Indicosmic Capital Pvt. Ltd.</td>
        </tr>
        <tr>
          <td>Credit Tenure(#)</td>
          <td></td>
        </tr>

        <tr>
          <td>Name of the Insured Person</td>
          <td>$full_name_of_insured</td>
        </tr>
        <tr>
          <td>Applicant / Co-applicant</td>
          <td>Applicant</td>
        </tr>
        <tr>
          <td>Occupation(Salaried/Self-employed)(#)</td>
          <td></td>
        </tr>
        <tr>
          <td>Relation with the Insured Person </td>
          <td>self</td>
        </tr>
        <tr>
          <td>Date of Birth DD/MM/YYYY</td>
          <td>$dob</td>
        </tr>
        <tr>
          <td>Gender</td>
          <td>$gender</td>
        </tr>
        <tr>
          <td>Category</td>
          <td></td>
        </tr>
        <tr>
          <td>Credit Amount / Outstanding Credit Amount (#)</td>
          <td></td>
        </tr>
        <tr>
          <td>Sum Insured</td>
          <td>$sum_insured</td>
        </tr>
        <tr>
          <td>Sum Insured Basis (#)</td>
          <td>Fixed</td>
        </tr>
        <tr>
          <td>Description / Remarks</td>
          <td></td>
        </tr>
        <tr>
          <td>Nominee Name</td>
          <td>$nominee_name</td>
        </tr>
        <tr>
          <td>Nominee Relationship with the Insured Person</td>
          <td>$nominee_relation</td>
        </tr>
        <tr>
          <td>Nominee Age </td>
          <td>$nominee_age</td>
        </tr>
        <tr>
          <td>Appointee Details in case Nominee is Minor</td>
          <td>{$appointee_details}</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="line-height-20">(#) Applicable only to Credit linked policies</td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>INTERFMEDIARY DETAILS</b></td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="25%">Intermediary Code</td>
          <td width="25%">Intermediary Name</td>
          <td width="25%">Intermediary E-Mail Id</td>
          <td width="25%">Intermediary Landline No.</td>
        </tr>
        <tr>
          <td>3204180000</td>
          <td>Global India Insurance Brokers Pvt Ltd.</td>
          <td>info@giib.co.in</td>
          <td>022-26820489</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
        <tr>
          <td class="textcenter line-height-20"><b>Kotak Group Accident Protect UIN: IRDAI/HLT/KMGI/P-H(G)/V.I/61/2016-17</b></td>
        </tr>
      </table>      
      </td>
  </tr>
  <tr>
      <td>
        <table cellpadding="10" border="0" cellspacing="0" class="footer">
        <tr>
          <td>Kotak Mahindra General Insurance Company Ltd. (Formerly Kotak Mahindra General Insurance Ltd. <br>CIN: U66000MH2014PLC260291 <b>Registered Office:</b> 27 BKC, C 27, G Block, Bandra Kurla Complex, Bandra East, Mumbai - 400051. Maharashtra, India. <br><b>Office:</b> 8th Floor, Kotak Infiniti, Bldg. 21, Infinity IT Park, Off WEH, Gen. AK Vaidya Marg, Dindoshi, Malad(E), Mumbai - 400097.India. <br>Toll Free: 1800 266 4545 Email: care@kotak.com Website: www.kotakgeneralinsurance.com IRDAI Reg. No. 152</td>
        </tr>
      </table>
      </td>
  </tr>
</table>
EOD;

  $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        if($dealer_id == 2871 || $dealer_id == 2872){
          $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 5, 150, 250, '', '', '', true, 500);
        }
        $pdf->writeHtml($html2);
$html3= <<<EOD
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
<br pagebreak="true" />
<table cellpadding="2" border="0" cellspacing="0" class="textcenter line-height-10">
        <tr>
          <td width="25%">
          <img src="assets/images/mpdf/kotak-logo.png" width="200">
          </td>
          <td width="50%">
            <table cellpadding="0" border="0" cellspacing="0">
              
              <tr>
                <td><h1>KOTAK GROUP ACCIDENT PROTECT</h1></td>
              </tr>
              <tr>
                <td><b>For any assistance please call 1800 266 4545, <br>please save the number for your reference.</b></td>
              </tr>
              <tr>
                <td><b>FOR RENEWALS: Visit www.kotakgeneralinsurance.com Call 1800 266 4545</b></td>
              </tr>
              <tr>
                <td class="font-8 line-height-13"><b>CERTIFICATE OF INSURANCE</b></td>
              </tr>
            </table>
          </td>
          <td width="25%">
            <table cellpadding="0" border="0" cellspacing="0">
              <tr>  
                <td class="textright">$qr_code_image_url</td>
              </tr>
            </table>
          </td>
        </tr> 
        <tr>
            <td colspan="3"></td>
        </tr>       
      </table>
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
 
  <tr>
      <td width="100%">
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>Coverage Details:</b></td>
        </tr>
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="6%"></td>
          <td width="47%" class="font-8 textcenter"><b>Coverage Opted</b></td>
          <td width="47%" class="font-8 textcenter"><b>Description/ Sum Insured Limits</b></td>         
        </tr>
        <tr>
          <td colspan="3" style="background-color:#ffebcd;" class="font-8"><b>Benefit - Section A</b></td>
        </tr>
        <tr>
          <td class="textcenter">1</td>
          <td>Accidental Death (AD) </td>
          <td class="textright">$sum_insured</td>
        </tr>
        <tr>
          <td class="textcenter">2</td>
          <td>Permanent Total Disablement (PTD)</td>
          <td class="textright">$sum_insured</td>
        </tr>
        {$imp_note1}           
      </table>
     {$imp_note}
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>PERMANENT EXCLUSION</b></td>
        </tr>
      </table>
      <table cellpadding="0" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
        <tr>
          <td>1. Arising or resulting from the Insured Person committing any breach of the law with criminal intent. <br>2. Any Injury or Illness directly or indirectly caused by or arising from or attributable to war or war like perils. <br>3. Any Illness or Injury directly or indirectly caused by or contributed to by nuclear weapons/material usage, consumption or abuse of substances intoxicants, hallucinogens, alcohol and/or drugs.self-destruction or self inflicted injury, attempted suicide or suicide. <br>4. Any consequential or indirect loss or expenses arising out of or related to any event giving rise to a Claim under the Policy.</td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td><b>For complete details please refer to the Policy wordings available with the Group Master Policyholder. Alternatively, the same can be downloaded from our website www.kotakgeneralinsurance.com</b></td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td>Premium For Your Personal Accident Insurance Has Been Paid By Indicosmic Capital Pvt. Ltd.</td>
        </tr>
        <tr>
          <td height="40"></td>
        </tr>
      </table>
      <!--<table cellpadding="4" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>PREMIUM DETAILS</b></td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0" class="boxtable textcenter font-8">
        <tr>
          <td width="25%">Taxable Value Of Services (Rs.)</td>
          <td width="25%">CGST@9%</td>
          <td width="25%">SGST@9%</td>
          <td width="25%">Total Amount(Rs.)</td>
        </tr>
        <tr>
          <td>3188.14 </td>
          <td>286.93 </td>
          <td>286.93 </td>
          <td>3762</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>-->
      <table cellpadding="4" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>DISCLAIMER</b></td>
        </tr>
      </table>
      <table cellpadding="0" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
        <tr>
          <td>This Certificate of Insurance shall be read together with the Policy Schedule and Policy Wording and regarded as one contract and any word or expression to which a specific meaning has been assigned in any part of the policy or this schedule shall bear the same meaning wherever it may appear.</td>
        </tr>
        <tr>
          <td></td>
        </tr>       
      </table>
      <table cellpadding="4" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>IN THE EVENT OF CLAIM</b></td>
        </tr>
      </table>
      <table cellpadding="0" border="0" cellspacing="0">
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td width="60%"><b>Contact Us at:</b> <br>Timings : 8 AM to 8 PM Toll Free number: 1800 266 4545 or may write an e-mail at <u>care@kotak.com</u></td>
          <td width="40%" rowspan="4" class="textright">For & On Behalf of <br> Kotak Mahindra General Insurance Company Ltd. <br><img src="assets/images/mpdf/eswar-sign-final.jpg" height="50"> <br>Authorized Signatory</td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td><b>Please send the relevant documents to:</b> <br>Kotak Mahindra General Insurance Company Ltd. <br>8th Floor, Zone IV, Kotak Infiniti, Bldg. 21,Infinity IT Park, <br>Off WEH, Gen. AK Vaidya Marg, Dindoshi, Malad (E), <br>Mumbai - 400097. India</td>
        </tr>
        <tr>
          <td height="70" colspan="2"></td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="textcenter line-height-20"><b>Kotak Group Accident Protect UIN: IRDAI/HLT/KMGI/P-H(G)/V.I/61/2016-17</b></td>
        </tr>
      </table>      
      </td>
      
  </tr>
  <tr>
      <td>
        <table cellpadding="10" border="0" cellspacing="0" class="footer">
        <tr>
          <td>Kotak Mahindra General Insurance Company Ltd. (Formerly Kotak Mahindra General Insurance Ltd. <br>CIN: U66000MH2014PLC260291 <b>Registered Office:</b> 27 BKC, C 27, G Block, Bandra Kurla Complex, Bandra East, Mumbai - 400051. Maharashtra, India. <br><b>Office:</b> 8th Floor, Kotak Infiniti, Bldg. 21, Infinity IT Park, Off WEH, Gen. AK Vaidya Marg, Dindoshi, Malad(E), Mumbai - 400097.India. <br>Toll Free: 1800 266 4545 Email: care@kotak.com Website: www.kotakgeneralinsurance.com IRDAI Reg. No. 152</td>
        </tr>
      </table>
      </td>
  </tr>
</table>
EOD;

  
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        if($dealer_id == 2871 || $dealer_id == 2872){
            $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 5, 150, 250, '', '', '', true, 500);
          }
        $pdf->writeHtml($html3);
        $pdf_to_name = "Kotak-RSA-Policy- .'$certificate_no'.pdf";
        ob_clean();
        $policy_pdf = $pdf->Output($pdf_to_name, 'S');
        return $policy_pdf;
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
//        $pwd = "6610402";
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
        $sql = "SELECT tic.`name` AS ic_name,tsp.product_type_name,tsp.plan_name,tsp.sold_policy_no,tsp.engine_no,tsp.chassis_no,tsp.id as policy_id,tsp.ic_id,CONCAT(tcd.fname , tcd.lname) AS fname,tcd.created_date,tcd.id as customer_id FROM tvs_sold_policies AS tsp "
            . " INNER JOIN tvs_customer_details AS tcd ON tcd.id = tsp.customer_id "
            ."LEFT JOIN tvs_insurance_companies tic ON tic.`id`=tsp.`ic_id`"
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
        echo "<pre>"; print_r($result); echo "</pre>"; die('end of line yoyo');
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
                case 8:
                    $file_list = '<a href="' . base_url() . 'download_reliance_policy/' . $main->policy_id . '" class="btn btn-info" target="_blank">Download Pdf</a><br/>';
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
                case 13:
                    $file_list = '<a href="' . base_url() . 'download_liberty_policy/' . $main->policy_id . '" class="btn btn-info" target="_blank">Download Pdf</a>';
                    break;
                 
                default:
                    $file_list = '<a href="' . base_url() . 'download_rsa_pdf/' . $main->policy_id . '" class="btn btn-info" target="_blank">Download Pdf</a><br/>';
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

function RejectedCancelationPolicies(){
 $user_session = $this->session->userdata('user_session');
    if (empty($user_session)) {
        redirect(base_url());
    } else {
        $this->load->dashboardTemplate('front/dashboard/reject_cancel_policy');
    }
}

function RejectCancelPolicyAjax(){
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

        $sql = "SELECT tsp.id,tsp.policy_status_id,tsp.product_type_name,tsp.plan_name,tsp.sold_policy_no,tsp.engine_no,tsp.`cancellation_reson`, tsp.chassis_no,tsp.created_date,tcd.fname FROM tvs_sold_policies AS tsp 
            LEFT JOIN tvs_customer_details AS tcd ON tcd.id = tsp.customer_id 
            WHERE tsp.user_id = '$user_id' AND tsp.`policy_status_id`=3 AND tsp.`cancellation_reson` IS NOT NULL $where";
                 
        $query = $this->db->query($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
//        $sql = "$sql WHERE  1 = 1";
       
     
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
            $row[] = $main->cancellation_reson;
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

    public function checkIP(){
    $ip_addreaa = get_client_ip();
    
   
    
    if($ip_addreaa=="::ffff:59.152.55.202"){
           die('sushant');
        }
}


}
