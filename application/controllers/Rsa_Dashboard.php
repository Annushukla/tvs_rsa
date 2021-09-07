<?php
Class Rsa_Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('database_library');
        $this->load->model('Home_Model');
        $this->load->helper('common_helper');
        // isUserLoggedIn();
        /*       $this ->checkLogin(); */
    }

    public function index() {
      
      $user_session = $this->session->userdata('user_session');
      //echo '<pre>'; print_r($user_session);die('hello');
      if(empty($user_session)){
        $uri = $this->uri->segment(2);
        //die($uri);
        if(!empty($uri)){
            $row  =  $this->Home_Model->getRowDataFromTable('redirect_key_log', array('redirect_key'=>$uri));
           // echo '<pre>'; print_r($row);die('here');
            if(isset($row['user_session'])&&!empty($row['user_session'])){
              $user_session = json_decode($row['user_session'],true);
              $this->session->set_userdata('user_session',$user_session);
              //redirect(base_url().'dashboard/');
            }
        }
      }
        if (empty($user_session)) {
            redirect(base_url());
        } else {
          // echo '<pre>'; print_r($user_session);die('here');
            if (empty($user_session['gst_no'])) {
              // die('1here');
                redirect('dealer_form');
            } else {
                $dealer_code = $user_session['sap_ad_code'];
                $dealer_id = $user_session['id'];
                $dealer_wallet=$this->Home_Model->getDealerWallet($dealer_id);
                $balance=$dealer_wallet['security_amount']-$dealer_wallet['credit_amount'];
                if($balance<1000){
                    $wallet_popup = 'true';
                }else{
                    $wallet_popup = 'false';
                }
                // $this->data['top_three_dealers'] = $top_three_dealers = $this->Home_Model->topThreeDealers();
                $where = array('dealer_code'=>$user_session['sap_ad_code']);
                $this->data['is_rr310_allowed'] = $is_rr310_allowed = $this->Home_Model->getRowDataFromTable('rr310_dealer',$where);
                $is_uploaded = $this->Home_Model->checkIsDealerDocUploaded();
                $where = array('dealer_code'=>$dealer_code);
                $exist_data = $this->Home_Model->getRowDataFromTable('dealer_campaign_list',$where);
                if(!empty($exist_data) || (strlen($dealer_code) >5) ){
                    $dealer_campaign_status = 'true';
                }else{
                    $dealer_campaign_status = 'false';
                }
                $this->data['is_uploaded'] = $is_uploaded;
                $this->data['dealer_campaign_status'] = $dealer_campaign_status;
                $this->data['dealer_code'] = $dealer_code;
                $this->data['wallet_popup'] = $wallet_popup;
                $this->data['balance'] = $balance;
                $this->load->dashboardTemplate('front/myaccount/dashboard', $this->data);
            }
        }
    }

function validate_mobile($mobile)
{
    return preg_match('/^[0-9]{10}+$/', $mobile);
}
     public function submitDealerCampaignList(){
      $dealer_code = $this->session->userdata('user_session')['sap_ad_code'];
      if(empty($dealer_code)){
        redirect(base_url());
      }
      $where = array('dealer_code'=>$dealer_code);
      $exist_data = $this->Home_Model->getRowDataFromTable('dealer_campaign_list',$where);
      // echo "<pre>"; print_r($exist_data); echo "</pre>"; die('exist');
      if(empty($exist_data)){
              $post_data = $this->input->post();
              if(!empty($post_data['dealer_no']) && !empty($post_data['principle_contact']) && !empty($post_data['ins_manager_name']) && !empty($post_data['gm_name'])){
                $valid_ins_mobile = $this->validate_mobile($post_data['ins_manager_contact']);
                $valid_gm_mobile = $this->validate_mobile($post_data['gm_contact']);
                $valid_principal_mobile = $this->validate_mobile($post_data['principle_contact']);
                if($valid_ins_mobile && $valid_gm_mobile && $valid_principal_mobile){
                        $insert_data = array(
                        'dealer_code'=> $dealer_code,
                        'insurance_name'=>($post_data['ins_manager_name'])?$post_data['ins_manager_name']:'',
                        'insurance_email'=>($post_data['ins_manager_email'])?$post_data['ins_manager_email']:'',
                        'insurance_contact'=>($post_data['ins_manager_contact'])?$post_data['ins_manager_contact']:'',
                        'principle_name'=>($post_data['principle_name'])?$post_data['principle_name']:'',
                        'principle_email'=>($post_data['principle_email'])?$post_data['principle_email']:'',
                        'principle_contact'=>($post_data['principle_contact'])?$post_data['principle_contact']:'',
                        'gm_name'=>($post_data['gm_name'])?$post_data['gm_name']:'',
                        'gm_email'=>($post_data['gm_email'])?$post_data['gm_email']:'',
                        'gm_contact'=>($post_data['gm_contact'])?$post_data['gm_contact']:'',
                        'dealer_no'=>($post_data['dealer_no'])?$post_data['dealer_no']:'',
                        'showroom'=>($post_data['showoom_name'])?$post_data['showoom_name']:'',
                        'created_at'=>date('Y-m-d H:i:s'),
                        'is_active'=>1
                    );

                  $insert_id = $this->Home_Model->insertIntoTable('dealer_campaign_list',$insert_data);
                }else{
                    $this->session->set_flashdata('campaign_msg',"Please Enter Valid Mobile No.");
                }
                    

              }
      }
      
      redirect('dashboard');
      // echo "<pre>"; print_r($insert_data); echo "</pre>"; die('end of line yoyo');
    }

    public function update_pasword(){
      $post_data = $this->input->post();
        if(!empty($post_data)){
            $where = array('employee_code' => $post_data['emp_code']);
            $update_data = array('password' => $post_data['final_pwd'],
                                  'updated_at' => date('Y-m-d H:i:s')
              );
            $updated = $this->Home_Model->updateTable('biz_users',$update_data,$where);
            if($updated){
              $status = 'true';
            }else{
              $status = 'false';
            }
        }else{
          $status = 'Something went wrong please try again !' ;
        }

        echo json_encode($status);
    }
    public function check_Exist_mobile_no(){
        $post_data = $this->input->post();
        $return_data = array();
        if(!empty($post_data)){
          $dealer_code = $post_data['dealer_code'];
          $mobile = $post_data['mobile_no'];
          $sql = "SELECT * FROM tvs_dealers WHERE dealer_code = '$dealer_code' AND mobile = '$mobile'";
          $result = $this->db->query($sql)->row_array();
              if(!empty($result)){
                $return_data['status'] = 'true';
                $return_data['msg'] = 'Record Exist';
                $return_data['error_code'] = 200;
              }else{
                $return_data['status'] = 'false';
                $return_data['msg'] = 'Record Not Exist';
                $return_data['error_code'] = 404;
              }
        }else{
                $return_data['status'] = 'false';
                $status = 'Something went wrong please try again !';
                $return_data['error_code'] = 500;
            }

            echo json_encode($return_data);
      }

 public function update_bizusers_data(){
     // echo 'hiee'; die('in post');
        $post_data = $this->input->post();
        $return_data = array();
        if(!empty($post_data)){
          $update_data = array(
              'f_name' => $post_data['f_name'],
              'l_name' => $post_data['l_name'],
              'email_id' => $post_data['email_id'],
              'company_name' => $post_data['company_name'],
              'dob' => $post_data['dob'],
              'updated_at' => date("Y-m-d H:i:s")
            );
          $where = array('employee_code' => $post_data['employee_code']);
          $updated = $this->Home_Model->updateTable('biz_users',$update_data,$where);
          if($updated){
                $return_data['status'] = 'true';
                $return_data['msg'] = 'Data Updated';
                $return_data['error_code'] = 200;
          }else{
                $return_data['status'] = 'false';
                $return_data['msg'] = 'Data is Not Updated';
                $return_data['error_code'] = 404;
          }
        }else{
              $return_data['status'] = 'false';
              $status = 'Something went wrong please try again !';
              $return_data['error_code'] = 500;
        }

        echo json_encode($return_data);
    }


    public function resetCustomerForm() {
        $this->session->unset_userdata('customer_and_vehicle_details');
    }

    function SubmitConfirmOriental(){
      // echo "<pre>"; print_r($_POST); echo "</pre>"; die('end of line yoyo');
      $post = $this->input->post();
      $submit = $post['submit'];
      $dealer_id = $post['dealer_id'];
      if(!empty($dealer_id) && !empty($submit)){
          $confirm_data = array(
            'is_oriental_confirmed' => 1,
			'is_oriental_date' =>date("Y-m-d H:i:s")
          );

          $where = array('id'=>$dealer_id);
          $status = $this->Home_Model->updateTable('tvs_dealers',$confirm_data,$where);
      }
      redirect('dashboard');
    }

   public function submit_dealer_details() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $post_data = $this->input->post();
        // echo '<pre>'; print_r($post_data);die;
        $session_data = $this->session->userdata('user_session');
        if (!empty($post_data) && !empty($session_data)) {
            $this->form_validation->run('tvs_dealer_form');
            if ($this->form_validation->run() == FALSE) {
                $this->set_dealers_formvalidation();
                redirect('dealer_form');
            } else {   
                $dealer_id = $session_data['id'];
                $set_data = array(
                    'dealer_name' => $post_data['company_name'],
                    'email' => $post_data['email'],
                    'ad_name' => $post_data['dealer_full_name'],
                    'landline' => $post_data['phone_no'],
                    'mobile' => $post_data['mobile_no'],
                    'tin_no' => $post_data['tin_no'],
                    'gst_no' => $post_data['gst_no'],
                    'aadhar_no' => $post_data['aadhar_no'],
                    'pan_no' => $post_data['pan_no'],
                    'add1' => $post_data['dealer_addr1'],
                    'add2' => $post_data['dealer_addr2'],
                    'pin_code' => $post_data['pin'],
                    'state' => $post_data['state'],
                    'location' => $post_data['city'],
                    'bank_name' => $post_data['bank_name'],
                    'banck_acc_name' => $post_data['acc_holder_name'],
                    'banck_acc_no' => $post_data['account_no'],
                    'banck_ifsc_code' => $post_data['ifsc_code'],
                    'account_type' => $post_data['account_type'],
                    'branch_address' => $post_data['branch_address'],
                    'company_type_id' => $post_data['company_type'],
                    'pa_ic_id' => $post_data['pa_ic_id'],
                    'updated_at' =>date("Y-m-d H:i:s")
                );
                $where = array('id' => $dealer_id);
                $status = $this->Home_Model->updateTable('tvs_dealers', $set_data, $where);
                $where = array('dealer_code'=>$session_data['sap_ad_code']);
                $exist_dms = $this->Home_Model->getDataFromTable('dms_ic_and_pa_ic_mapping',$where);
                // echo "<pre>"; print_r($exist_dms); echo "</pre>"; die('end of line yoyo');
                if(!empty($exist_dms)){
                  // echo "<pre>"; print_r($post_data); echo "</pre>"; die('end of line yoyo');
                $response =  $this->updateDMSICMapping($exist_dms,$post_data['pa_ic_id']);
                }else{
                $response =  $this->addDMSICMapping($session_data['sap_ad_code'],$post_data['pa_ic_id']);
                }
                if($status){
                    $where = array('id' => $dealer_id);
                    $dealer_data = $this->Home_Model->getDataFromTable('tvs_dealers', $where);
                    $dealer_data = $dealer_data[0];
                    //$this->session->unset_userdata('user_session');
                    $this->session->set_userdata('user_session', $dealer_data);
                    redirect('dealer_document_form');
                }
            }
        }else {
            redirect('dealer_form');
        }
    }

    function addDMSICMapping($sap_ad_code,$pa_ic_id){
        $dms_ic_id_ar = array('5','6','7','8','9','10','12','13');
        if($pa_ic_id=='all'){
            foreach($dms_ic_id_ar as $dms_ic_id) {
              $pa_ic_id=$dms_ic_id;
              if(in_array($dms_ic_id, array('6','7','8','10','13') )){
                  $pa_ic_id = '2';
              }
              $dms_map = array(
                  'dealer_code'=> $sap_ad_code,
                  'dms_ic_id'=> $dms_ic_id,
                  'pa_ic_id'=> $pa_ic_id,
                  'is_active'=>1,
                  'created_at'=>date("Y-m-d H:i:s")
              );
            $insert_id = $this->Home_Model->insertIntoTable('dms_ic_and_pa_ic_mapping',$dms_map);
          }
        }else{
            foreach($dms_ic_id_ar as $dms_ic_id) {
                $dms_map = array(
                    'dealer_code'=> $sap_ad_code,
                    'dms_ic_id'=> $dms_ic_id,
                    'pa_ic_id'=> $pa_ic_id,
                    'is_active'=>1,
                    'created_at'=>date("Y-m-d H:i:s")
                );
              $insert_id = $this->Home_Model->insertIntoTable('dms_ic_and_pa_ic_mapping',$dms_map);
            }
        }
        return $insert_id;
    }

    function updateDMSICMapping($exist_dms,$pa_ic_id){
        if($pa_ic_id=='all'){
            foreach($exist_dms as $dms_ic_id) {
              // echo"<pre>"; print_r($dms_ic_id['dms_ic_id']);die;
              $pa_ic_id=$dms_ic_id['dms_ic_id'];
             if(in_array($dms_ic_id['dms_ic_id'], array('6','7','8','10','13') )){
              $pa_ic_id=$dms_ic_id;
                  $pa_ic_id = '2';
              }
              $dms_map = array(
                  'pa_ic_id'=> $pa_ic_id,
                  'updated_at'=>date("Y-m-d H:i:s")
              );
              $where = array('dealer_code'=>$dms_ic_id['dealer_code'],'dms_ic_id'=>$dms_ic_id['dms_ic_id']);
              $update = $this->Home_Model->updateTable('dms_ic_and_pa_ic_mapping',$dms_map,$where);
          }

        }else{
            foreach($exist_dms as $dms_ic_id) {
                $dms_map = array(
                    'pa_ic_id'=> $pa_ic_id,
                    'updated_at'=>date("Y-m-d H:i:s")
                );
              $where = array('dealer_code'=>$dms_ic_id['dealer_code'],'dms_ic_id'=>$dms_ic_id['dms_ic_id']);
              $update = $this->Home_Model->updateTable('dms_ic_and_pa_ic_mapping',$dms_map,$where);
            }
        }
        return $update;
    }
    function set_dealers_formvalidation() {
        $er_dealer_full_name = form_error('dealer_full_name');
        $er_email = form_error('email');
        $er_company_name = form_error('company_name');
        $er_mobile_no = form_error('mobile_no');
        $er_phone_no = form_error('phone_no');
        // $er_tin_no = form_error('tin_no');
        $er_gst_no = form_error('gst_no');
        // $er_aadhar_no = form_error('aadhar_no');
        $er_pan_no = form_error('pan_no');
        $er_dealer_addr1 = form_error('dealer_addr1');
        $er_dealer_addr2 = form_error('dealer_addr2');
        $er_pin = form_error('pin');
        $er_state = form_error('state');
        $er_city = form_error('city');
        $er_bank_name = form_error('bank_name');
        $er_acc_holder_name = form_error('acc_holder_name');
        $er_account_no = form_error('account_no');
        $er_ifsc_code = form_error('ifsc_code');

        $this->session->set_flashdata('er_dealer_full_name', $er_dealer_full_name);
        $this->session->set_flashdata('er_email', $er_email);
        $this->session->set_flashdata('er_company_name', $er_company_name);
        $this->session->set_flashdata('er_mobile_no', $er_mobile_no);
        $this->session->set_flashdata('er_phone_no', $er_phone_no);
        // $this->session->set_flashdata('er_tin_no',$er_tin_no);
        $this->session->set_flashdata('er_gst_no', $er_gst_no);
        // $this->session->set_flashdata('er_aadhar_no',$er_aadhar_no);
        $this->session->set_flashdata('er_pan_no', $er_pan_no);
        $this->session->set_flashdata('er_dealer_addr1', $er_dealer_addr1);
        $this->session->set_flashdata('er_dealer_addr2', $er_dealer_addr2);
        $this->session->set_flashdata('er_pin', $er_pin);
        $this->session->set_flashdata('er_state', $er_state);
        $this->session->set_flashdata('er_city', $er_city);
        $this->session->set_flashdata('er_bank_name', $er_bank_name);
        $this->session->set_flashdata('er_acc_holder_name', $er_acc_holder_name);
        $this->session->set_flashdata('er_account_no', $er_account_no);
        $this->session->set_flashdata('er_ifsc_code', $er_ifsc_code);
    }
     function download_rsa_agreement_pdf() {
        $user_session = $this->session->userdata('user_session');
        $dealer_name = strtoupper($user_session['dealer_name']);
        $dealer_address = strtoupper($user_session['add1'] . ' ' . $user_session['add2']);
        $company_type_id = $user_session['company_type_id'];
        $where = array('id'=>$company_type_id);
        $company_details = $this->Home_Model->getRowDataFromTable('company_type',$where);
        $company_type = $company_details['type_name'];
        $company_acts = $company_details['acts'];
          //echo '<pre>';print_r($company_details);die;
        $dealer_ad_name = strtoupper($user_session['ad_name']);
         
        $today = date("d");
        $this_month = date("M");
        $this_year = date("Y");
        $this->load->library('Tcpdf/Tcpdf.php');

        $pdf = new TCPDF();
        $pdf->SetFont('helvetica', '', 7, '', 'default', true);
        $pdf->AddPage();
        ob_start();
        // first page
        $html = ' <style>
  .pagewrap {color: #000; font-size: 8.5pt; line-height:14pt; color:#000;}
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
      <table cellpadding="8" border="0" cellspacing="0" class="textcenter line-height-13">
        <tr>
          <td></td>
        </tr>
        <tr>
          <td>(TO BE PRINTED ON LETTER HEAD OF DEALERSHIP)</td>
        </tr>
        <tr>
          <td class="font-10 line-height-16"><b>AGREEMENT</b></td>
        </tr>
      </table>
      <table cellpadding="8" border="0" cellspacing="0">
        <tr>
          <td height="20"></td>
        </tr>
        <tr>
          <td>This Agreement is made on the ' . $today .' of ' . $this_month . ' '. $this_year .' (Effective Date), </td>
        </tr>
        <tr>
          <td>By and Between</td>
        </tr>
        <tr>
          <td><b>Indicosmic Capital Private Limited</b> a Service provider incorporated under the Companies Act, 2013 and having its corporate Office at 318, 3rd Floor, Summit-Business Bay, Behind Gurunanak Petrol Pump, Off Andheri Kurla Road, Beside Magic Bricks WEH metro stn., Andheri (E), Mumbai-400093 hereinafter referred to as "ICPL", which expression shall, unless repugnant to the meaning and context include and mean their service provider being the FIRST PARTY</td>
        </tr>
        <tr>
          <td><b>AND</b></td>
        </tr>
        <tr>
          <td> <b>'.$dealer_name.' </b> incorporated under the provision of the <b> '.$company_type.' </b> <b> '.$company_acts.' </b>, having its Registered Office at <b>'.$dealer_address.' </b> , hereinafter referred to as the "Dealer", which expression shall, unless repugnant to the meaning and context include and mean their legal heirs, legal representatives, successors, assigns, representatives, nominees, administrators, permitted assigns etc. as the case may be, being the Party of the OTHER PART; </td>
        </tr>
        <tr>
          <td>ICPL and Dealer are hereinafter collectively referred to as "Parties" and individually as "Party"</td>
        </tr>
        <tr>
          <td><b>WHEREAS</b></td>
        </tr>
        <tr>
          <td><b>A.</b>ICPL is inter-alia engaged in the business as explained in the ANNEXURE 1 includes:-</td>
        </tr>
        <tr>
          <td>1.Providing assistance program, extended warranty program and other movable motorized vehicle related services;</td>
        </tr>
        <tr>
          <td>2.Advertisements in mass media including television, radio, internet, print media etc;</td>
        </tr>
        <tr>
          <td>3.Printing and publishing of posters, banners, hoardings, pamphlets, hand bills, brochures etc. with slogans, pictures, animated depictions etc. as per the strategies approved by and instructions of the client/customer; Providing call centre support for a products offering;</td>
        </tr>
        <tr>
          <td>4.Documentation, Data Entry, Scanning and Storage of data with respect to the activities;</td>
        </tr>
        <tr>
          <td>5.Creating awareness and interest in seminars and conferences organized by various companies with respect to its banking/ insurance and service sector business;</td>
        </tr>
        <tr>
          <td>6.Cross-selling of financial services products e.g. Mutual Funds, Share Trading, vehicle loans, personal loans etc;</td>
        </tr>
        <tr>
          <td><b>B</b> Assistance services provided in ANNEXURE 1 attached to this Agreement (Services);</td>
        </tr>
        <tr>
          <td><b>C</b> On the basis of the aforesaid representations and warranties mentioned in Annexure 1 ICPL wishes to engage the Dealer to sell ICPL’S RSA policies to the customers of the Dealer under the terms and conditions of this Agreement;</td>
        </tr>
      </table>  
      </td>     
  </tr>
</table>
<br pagebreak="true" />
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="8" border="0" cellspacing="0">     
        <tr>
          <td>IT IS HEREBY FURTHER AGREED BETWEEN THE PARTIES THAT, </td>
        </tr>
        <tr>
          <td>
            <table cellpadding="8" border="0" cellspacing="0">
              <tr>
                <td width="8%" class="textright">1.</td>
                <td width="92%"><b><u>SERVICES</u></b><br>ICPL shall provide such Services, as mentioned in ANNEXURE 1 to this Agreement, to its policy holders. ICPL, in consultation with the Dealer, may increase or decrease or alter or change the scope of Services of this agreement from time to time. Such changes shall be communicated to the Dealer in writing and due acceptance of the Dealer shall be obtained in writing. The Dealer, pursuant to this Agreement, shall not, solicit, negotiate or accept any business or issue any other documents in the name of or for and on behalf of ICPL. Dealers any specific request shall be in mutual consent to be listed and such arrangement shall be made in systems there to, else shall not make any promise or representation to or negotiate with clients in respect of any business or claim.</td>
              </tr>
              <tr>
                <td class="textright">2.</td>
                <td><b><u>Dealer’s Responsibility</u></b><br>Dealer’s responsibility, under this agreement, shall be limited to selling RSA policy of ICPL to their customers and the Dealer shall, in no way be responsible/Liable for providing any services mentioned in the RSA policy or any claim arising on account of not providing the RSA services to the satisfaction of the of policy holder.</td>
              </tr>
              <tr>
                <td class="textright">3.</td>
                <td><b><u>PAYMENT</u></b><br>Dealer shall make all payment for such assistance services policies issued on behalf of ICPL in advance. Dealer needs to transfer/ credit to bank account of ICPL in advance in order to issue the RSA policy through ICPL online platform. Dealer shall add ICPL as beneficiary as detailed in ANNEXURE 2.</td>
              </tr>
              <tr>
                <td class="textright">4.</td>
                <td><b><u>FEES</u></b><br>For RSA polices issued by the Dealer, ICPL shall pay the Dealer such fees, as mutually agreed between the Parties, by way of credit of the amount to the Dealer’s account with ICPL on a daily basis. The Dealer shall raise and provide ICPL Invoice, or any other document as may be required under the prevalent law, towards the Fee credited to its account with ICPL on a monthly basis. The Current Fee structure is detailed in ANNEXURE 3.The payment of Fees under this Agreement shall be the maximum liability of ICPL towards the Dealer, under this Agreement. GST and or any other statutory charges, as applicable from time to time, shall be borne by ICPL, subject to Dealer providing necessary taxable invoice/relevant documents.
              </td>
              </tr>
              <tr>
                <td class="textright">5.</td>
                <td><b><u>TERM</u></b><br>This Agreement shall be valid for the period of three years (3 years) commencing from the effective date of this Agreement.</td>
              </tr>
              <tr>
                <td class="textright">6.</td>
                <td><b><u>TERMINATION</u></b><br>Each of the Parties shall be entitled to terminate this Agreement by giving 30 days prior notice without assigning any reason thereof.ICPL at its sole discretion may terminate this Agreement forthwith, if required to do so by any governmental authority. ICPL may terminate this Agreement forthwith by giving a notice in writing to Dealer on the occurrence of any or all of the following events:
              </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td> i. If the Dealer breaches any law, rule or regulation as applicable from time to time;</td>
        </tr>
        <tr>
          <td>ii. If there is a material change in the corporate form of the Dealer;</td>
        </tr>
        <tr>
          <td>iii. An order is made by a court of competent jurisdiction for the dissolution or winding-up of the Dealer (otherwise than in the course of a re-organization or restructuring previously approved in writing by the Dealer, which approval shall not be withheld unreasonably);</td>
        </tr>
        <tr>
          <td>iv. Any step is taken and not withdrawn within thirty days to appoint a liquidator, receiver or other similar officer in respect of any assets of the Dealer:</td>
        </tr>
      </table>  
      </td>     
  </tr>
</table>
<br pagebreak="true" />
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="8" border="0" cellspacing="0">
        <tr>
          <td>
            <table cellpadding="8" border="0" cellspacing="0">
              <tr>
                <td width="8%" class="textright">6.</td>
                <td width="92%"><b><u>INDEMNIFICATION</u></b><br>Dealer shall at its own expense indemnify and hold harmless, and at ICPLs request defend, ICPL and its successors and assigns (and its and their officers, directors and employees) from and against all losses, liabilities, damages, settlements, expenses and costs (including attorney’s fees and court costs) (the foregoing, collectively, Claim(s)) which arise directly or indirectly out of or relate to <br>(a) any breach (or claim or threat thereof that, if true, would be a breach) of the terms of this Agreement or any provisions of applicable law by Dealer; <br>(b) the gross negligence or willful misconduct of Dealers employees or agents; <br>(c) employment-related claims by Dealer employees or agents; <br>(d) personal/bodily injury and property damage that arise from the performance of the Services; and/or ICPL may, at its option decide the amount of Claim and recover the same from the Dealer including by recovering from the amount, if any payable to the Dealer.</td>
              </tr>
              <tr>
                <td class="textright">8.</td>
                <td><b><u>CONFIDENTIALITY</u></b><br>Dealer undertakes that it shall not reveal, and shall use its reasonable efforts to ensure that its directors, officers, managers, partners, members, employees, legal, financial and professional advisors and bankers (collectively, Representatives) who have access to the Confidential Information relating to ICPL do not reveal, to any third party any such Confidential Information without the prior written consent of the ICPL. The Dealer shall ensure to implement required security policies, procedures and controls to protect the confidentiality and security of policy holders information even after the contract terminates. Upon termination of the agreement, the Dealer shall handover all the customer data to the Service provider and Dealer shall not use the customer data lying in its possession in any circumstances whatsoever.</td>
              </tr>
              <tr>
                <td class="textright">9.</td>
                <td>ICPL shall continue to own and possess all intellectual property rights in the information/documents provided by it to the Dealer at all times, including, after termination of this Agreement.</td>
              </tr>
              <tr>
                <td class="textright">10.</td>
                <td>The Parties agree that nothing contemplated in this Agreement constitutes or may be construed to constitute the Dealer as an agent, broker or intermediary of ICPL for soliciting or procuring or marketing the insurance products to any customers, or that there exists a principal-agent relationship between the Dealer and ICPL, or confers any exclusivity to either Party for the arrangements as contemplated herein.</td>
              </tr>
              <tr>
                <td class="textright">11.</td>
                <td>The Dealer shall always act in accordance with provisions of applicable law during the course of providing the Services to ICPL and in any matter related thereto.</td>
              </tr>
              <tr>
                <td class="textright">12.</td>
                <td>The Dealer shall provide all the necessary support/assistance/co-operation as may be required by ICPL to comply with applicable law related to the said business arrangement.</td>
              </tr>
              <tr>
                <td class="textright">13.</td>
                <td>No unethical or illegal action shall be performed by Dealer or any of its employee Representatives in relation to performing the ICLP obligations under this engagement Agreement.</td>
              </tr>
              <tr>
                <td width="8%" class="textright">14.</td>
                <td width="92%">All work products including any tangible or intangible program etc., which are created under these agreements shall be owned by the Service provider. The Service Provider shall ensure to comply with the security standards for the purpose of the data security and protection of confidential information including the policy holder’s information.</td>
              </tr>
              <tr>
                <td class="textright">15.</td>
                <td>Dealer shall not subcontract any of the activity under this Agreement without the express prior written consent of ICPL.</td>
              </tr>
              <tr>
                <td class="textright">16.</td>
                <td>Both Parties shall comply with the respective laws applicable to each of them.</td>
              </tr>
              <tr>
                <td class="textright">17.</td>
                <td>The ICPL service provider shall have adequate mechanism of disaster recovery and shall ensure that in case of flood or other circumstances, the Dealer has alternative mode of providing services. The Dealer shall have adequate business continuity planning (BCP) for the processes provided under the scope of Services herein.</td>
              </tr>
            </table>
          </td>
        </tr>       
      </table>  
      </td>     
  </tr>
</table>
<br pagebreak="true" />
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="8" border="0" cellspacing="0">
        <tr>
          <td>
            <table cellpadding="8" border="0" cellspacing="0">
              <tr>
                <td width="8%" class="textright">18.</td>
                <td width="92%"><b><u>Grievance Redressal </u></b><br>Any complaints, abuse or concerns with regards to assistance services and or comment or breach of terms shall be immediately informed to toll free contact information provided on the service contract copy. in case issue needs to be escalated then the Designated Grievance Officer for grievances redressal is as mentioned below can be contacted via writing through email signed with the electronic signature at info@indicosmic.com OR write at the below address:-<br><br>Mr. Linto Francis Grievance Redressal Officer Indicosmic Capital Pvt. Ltd.; 318, 3rd Floor, Summit-Business Bay, Behind Gurunanak Petrol Pump, Off AndheriKurla Road, Beside Magic Bricks WEH metro stn., Andheri (E), Mumbai-400093. Tel. No. +9122 2088 0555 </td>
              </tr>     
              <tr>
                <td width="8%" class="textright">18.</td>
                <td width="92%"><b><u>GOVERING LAW & DISPUTE RESOLUTION: </u></b><br>This Agreement shall be governed by and interpreted in accordance with Indian law. Any Disputes, arising under or in relation to this Agreement if any, shall be referred to the a sole arbitrator to be appointed by ICPL both Parties in accordance with the (Indian) Arbitration and Conciliation Act, 1996 or any amendment there to. Venue of arbitration and hearings shall be Mumbai, India.</td>
              </tr>
              <tr>
                <td width="8%" class="textright">20.</td>
                <td width="92%">Nothing contained in this agreement shall make the Dealer responsibility/Liable for providing any services mentioned in the RSA policy or any claim arising against a policy.<br><br>IN WITNESS WHEREOF, <br><br>the Parties have caused this Agreement to be duly executed and delivered as of the day and year first above written:-  </td>
              </tr>         
            </table>
          </td>
        </tr>       
      </table>  
      </td>     
  </tr>
   <tr>
    <td>
      <table cellpadding="8" border="0" cellspacing="0">
       <tr>    
          <td colspan="3" height="20"></td>
        </tr>
        <tr>
          <td width="45%">For '.$dealer_name.'</td>
          <td width="10%"></td>
          <td width="45%">For Indicosmic Capital Private Limited </td>
        </tr> 
        <tr>    
          <td height="30">X</td>
          <td height="30"></td>
          <td height="30"><img src="assets/images/mpdf/amit_deep_sign.jpeg" height="70px"></td>
        </tr>
        <tr>    
          <td>Name: '.$dealer_ad_name.' </td>
          <td></td>
          <td>Name: Mr. Amit Deep </td>
        </tr>
        <tr>
          <td>Designation: </td>
          <td></td>
          <td>Designation: Chief Operating Officer</td>
        </tr>
        <tr>    
          <td colspan="3"></td>
          
        </tr>
        <tr>
          <td>Witness 1)   </td>
          <td></td>
          <td>Witness 2)</td>
        </tr>
        <tr>    
          <td height="30">X</td>
          <td height="30"></td>
          <td height="30"><img src="assets/images/mpdf/amit_sign.jpg" height="70px"></td>
        </tr>
        <tr>
          <td><u>X</u>______________________________________</td>
          <td></td>
          <td><u></u> Amit Yadav </td>
        </tr>
      </table>  
      </td>     
  </tr>
</table>

<br pagebreak="true" />
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="8" border="0" cellspacing="0">        
        <tr>
          <td class="font-10 line-height-16 textcenter"><b><u>ANNEXURE 1</u></b></td>
        </tr>
        
        <tr>
          <td>THE SERVICES PROVIDED UNDER THE ASSISTANCE ARE AS FOLLOWS: </td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="8%" class="tb-heading textcenter">SN</td>
          <td width="70%" class="tb-heading">Featured Benefits</td>
          <td width="22%" class="tb-heading textcenter">Covered</td>
        </tr>
        <tr>
          <td class="textcenter">1</td>
          <td>Breakdown Support over phone</td>
          <td class="textcenter">Yes</td>
        </tr>
        <tr>
          <td class="textcenter">2</td>
          <td>“On site” Minor Repairs of the Covered 2 Wheelers</td>
          <td class="textcenter">Yes</td>
        </tr>
        <tr>
          <td class="textcenter">3</td>
          <td>Flat Tyre Support</td>
          <td class="textcenter">Yes</td>
        </tr>
        <tr>
          <td class="textcenter">4</td>
          <td>Puncture Repair Assistance</td>
          <td class="textcenter">Yes</td>
        </tr>
        <tr>
          <td class="textcenter">5</td>
          <td>Battery Jumpstart</td>
          <td class="textcenter">Yes</td>
        </tr>
        <tr>
          <td class="textcenter">6</td>
          <td>Emergency Fuel delivery (2 Ltr for 2w)</td>
          <td class="textcenter">Yes</td>
        </tr>
        <tr>
          <td class="textcenter">7</td>
          <td>Cost of fuel</td>
          <td class="textcenter">Payable</td>
        </tr>
        <tr>
          <td class="textcenter">8</td>
          <td>Locked/ Lost Keys</td>
          <td class="textcenter">Yes</td>
        </tr>
        <tr>
          <td class="textcenter">9</td>
          <td>Replacement Keys</td>
          <td class="textcenter">Yes</td>
        </tr>
        <tr>
          <td class="textcenter">10</td>
          <td>Transfer /Transportation for Mechanical & Electrical Breakdown of Covered 2 Wheelers</td>
          <td class="textcenter">Yes</td>
        </tr>
        <tr>
          <td class="textcenter">11</td>
          <td>Arrangement of Local taxi</td>
          <td class="textcenter">Coordination</td>
        </tr>
        <tr>
          <td class="textcenter">12</td>
          <td>Relay of Urgent Messages</td>
          <td class="textcenter">Yes</td>
        </tr>
        <tr>
          <td class="textcenter">13</td>
          <td>Customer Care No</td>
          <td class="textcenter">Yes</td>
        </tr>
        <tr>
          <td class="textcenter">14</td>
          <td>Arrangement of Rental 2 Wheelers</td>
          <td class="textcenter">Coordination</td>
        </tr>
        <tr>
          <td class="textcenter">15</td>
          <td>Outward & Forward Journey</td>
          <td class="textcenter">Coordination</td>
        </tr>
        <tr>
          <td class="textcenter">16</td>
          <td>Provision for Hotel Accommodation</td>
          <td class="textcenter">Coordination</td>
        </tr>
      </table>
      <table cellpadding="8" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="5%" class="tb-heading textcenter">#</td>
          <td width="12%" class="tb-heading textcenter">Plan Name</td>
          <td width="12%" class="tb-heading textcenter">RSA Tenure</td>
          <td width="20%" class="tb-heading textcenter">RSA Covered Kms</td>
          <td width="11%" class="tb-heading textcenter">PA Tenure</td>
          <td width="16%" class="tb-heading textcenter">PA Sum Insured</td>
          <td width="12%" class="tb-heading textcenter">PA RSD *</td>
          <td width="12%" class="tb-heading textcenter">Policy Price (Incl GST)*</td>
        </tr>
        <tr>
          <td>1</td>
          <td>Sapphire</td>
          <td>2 Years</td>
          <td>25</td>
          <td>1 Year</td>
          <td>15 lakh</td>
          <td>Current</td>
          <td>RS. 471</td>
        </tr>
        <tr style="background-color:#f2f2f2;">
          <td>2</td>
          <td>Platinum</td>
          <td>1 Year</td>
          <td>25</td>
          <td>1 Year</td>
          <td>15 lakh</td>
          <td>Current</td>
          <td>RS. 441</td>
        </tr>
        <tr>
          <td>3</td>
          <td>Gold</td>
          <td>1 Year</td>
          <td>25</td>
          <td>1 Year</td>
          <td>10 lakh</td>
          <td>Current</td>
          <td>RS. 350</td>
        </tr>
        <tr style="background-color:#f2f2f2;">
          <td>4</td>
          <td>Silver</td>
          <td>1 Year</td>
          <td>25</td>
          <td>1 Year</td>
          <td>5 lakh</td>
          <td>Current</td>
          <td>RS. 251</td>
        </tr>
      </table>
      </td>     
  </tr>
</table>
<br pagebreak="true" />
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="8" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="50%">Coverage <br>The territorial scope of the Roadside Assistance Services provided by ICPL shall be only liable to customers.</td>
          <td width="50%">Onsite support for Minor repairs In the event the Vehicle covered under this Agreement (Covered Vehicle) having a breakdown due to minor mechanical/ electrical fault, ICPL shall support by arranging vehicle technician to the breakdown location. ICPL will bear the labour cost and conveyance charges. Cost of spare parts if required to repair the vehicle on the spot (Outside coverage area) to obtain such material
            & spare parts will be borne by the Customer. </td>
        </tr>
        <tr>
          <td>Coverage in North East and J&K Coverage In Islands Due to the extreme geographical conditions, ICPL will not provide RSA services in North Eastern States and Jammu & Kashmir. (Specific cities might be covered based on ICPL’s requirement). SLAs will not be applicable for services rendered in these territories. ICPL will not provide RSA services in Islands</td>
          <td>Rundown of Battery In the event the Covered Vehicle having a breakdown due to rundown of battery, ICPL shall support by arranging vehicle technician to jump start the vehicle with appropriate means. ICPL will bear the labour cost and conveyance charges.</td>
        </tr>
        <tr>
          <td>Customer care  24 X 7 multi lingual support.</td>
          <td>Flat Tyre In the event that the Covered Vehicle has a puncture or tyre burst, ICPL shall support the Customer in replacing the flat tyre with spare tyre. The technician will repair the same at the location of break down. In case of non- availability of spare tyre, ICPL will try to repair the faulty tyre. This service is based on the availability of tyre repair shop near the breakdown location. All the cost related to rendering such service will be charged to customer.</td>
        </tr>
        <tr>
          <td>Towing Assistance In the event that a Covered Vehicle is immobilized due to electrical or mechanical failure, ICPL shall arrange towing of the Covered Vehicle to the nearest authorized outlet. These services shall be provided using equipment/s deemed most suitable by ICPL. Towing up to 25 km from incident to nearest garage is free.</td>
          <td>Urgent Message Relay Relay of Urgent message to family / friends in case of medical emergency.</td>
        </tr>
        <tr>
          <td>Emergency Assistance Medical co-ordination for occupants of the vehicle as a result of accidents.</td>
          <td>Fuel Assistance In the event Covered Vehicle runs out of fuel or stops due to contaminated fuel, ICPL will provide support by arranging up to 2 litres of fuel. The supply of fuel will be based on availability. ICPL will bear the labour cost and conveyance charges. Fuel charges shall be borne by Customer. This service will not be applicable if the vehicle is at Customer residence This service is based on local availability of fuel.</td>
        </tr>
        <tr>
          <td>Key Lost / Replacement In the event of Key Lost / Replacement, if possible we will arrange replacement key, else vehicle will be towed to nearest garage as per (7) above. </td>
          <td>Taxi Assistance In the event that a Covered Vehicle is immobilized due to the breakdown or accident and On-site preliminary support to make the vehicle roadworthy, is not possible, ICPL shall arrange and bear the expense for transferring the Covered Vehicle to the nearest authorized outlet. To help the Customer continue with his journey, ICPL will arrange taxi support to the Customer as per availability. SLAs will not be applicable for taxi assistance and ICPL does not guarantee the quality of taxi services.  </td>
        </tr>
      </table>
      </td>     
  </tr>
</table>
<br pagebreak="true" />
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="8" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="50%">Accommodation Assistance Arranging for hotel accommodation in case breakdown is reported outside customer’s home city. Co-ordination is free and all the related accommodation cost will be borne by the customer. </td>
          <td width="50%">Outward / Forward Journey Arranging for Outward / Forward Journey in case breakdown is reported outside customer’s home city Co-ordination is free, all the related travel cost will be borne by the customer. </td>
        </tr>
        <tr>
          <td>Arrangement of Rental Vehicle Arranging rental vehicle in case breakdown is reported outside customer’s home city Co-ordination is free, all the related rental vehicle cost will be borne by the customer.</td>
          <td></td>
        </tr>
      </table>
      <table cellpadding="8" border="0" cellspacing="0">
        <tr>
          <td height="50"></td>
        </tr>
        <tr>
          <td class="font-10 line-height-16 textcenter"><b><u>ANNEXURE 2</u></b></td>
        </tr>
        
        <tr>
          <td><u>Beneficiary name : Indicosmic Capital Pvt. Ltd. Bank </u></td>
        </tr>
        <tr>
          <td><u>Bank Name : ICICI Bank Ltd. </u></td>
        </tr>
        <tr>
          <td><u>Bank Branch : MIDC Andheri (E), Mumbai; </u></td>
        </tr>
        <tr>
          <td><u>Bank Ac Number : 054405007965 </u></td>
        </tr>
        <tr>
          <td><u>IFSC Code : ICIC0000544 </u></td>
        </tr>
        <tr>
          <td><u>ICPLGST No. : 27AAECI3370G1ZN</u></td>
        </tr>
      </table>
      <table cellpadding="8" border="0" cellspacing="0">
      <tr>
          <td height="50"></td>
        </tr>
        <tr>
          <td class="font-10 line-height-16 textcenter"><b><u>ANNEXURE 3</u></b></td>
        </tr>
        
      </table>
      <table cellpadding="4" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="5%" class="tb-heading textcenter">Sr No</td>
          <td width="50%" class="tb-heading textcenter">Plan Name</td>
          <td width="45%" class="tb-heading textcenter">Fee Per Policy</td>
        </tr>
        <tr>
          <td>1</td>
          <td>Sapphire</td>
          <td>80</td>
        </tr>
        <tr style="background-color:#f2f2f2;">
          <td>2</td>
          <td>Platinum</td>
          <td>75</td>
        </tr>
        <tr>
          <td>3</td>
          <td>Gold</td>
          <td>60</td>
        </tr>
        <tr style="background-color:#f2f2f2;">
          <td>3</td>
          <td>Silver</td>
          <td>50</td>
        </tr>
      </table>
      </td>     
  </tr>
</table>
';

        $pdf->writeHtml($html);
        //"policy"-firtsandlastnamecompany-policynumber
        $pdf_to_name = "Dealership-Agreement.pdf";
        // $pdf_to_name = $insured_name.' - '.$policy_no.'.pdf';
        // $pdf_to_name = str_replace(" ", "-", 'test');
        ob_clean();
        $pdf->Output($pdf_to_name, 'I');
    }
    function download_rsa_agreement_pdf_old() {
        $user_session = $this->session->userdata('user_session');
        $dealer_name = $user_session['dealer_name'];
        $dealer_address = $user_session['add1'] . ' ' . $user_session['add2'];
        $today = date("d");
        $this_month = date("m");
        $this_year = date("Y");
        $this->load->library('Tcpdf/Tcpdf.php');

        $pdf = new TCPDF();
        $pdf->SetFont('helvetica', '', 7, '', 'default', true);
        $pdf->AddPage();
        ob_start();
        // first page
        $html = ' <table width="100%" cellpadding="0" border="0" cellspacing="0" style="font-family: arial; font-size: 10pt; color: #000; line-height: 14pt;">
  <tr>
      <td valign="top" style="padding: 40px 0;">
<table width="100%" cellpadding="0" border="0" cellspacing="0">
          <tr>
            <td>
              <p style="text-align:center" ><b>(TO BE PRINTED ON LETTER HEAD OF DEALERSHIP)<br>AGREEMENT</b><br></p>
              <p>This Agreement is made on this ' . $today .' of ' . $this_month . ' '. $this_year .'(Effective Date), between  </p>
              <p><b>Indicosmic Capital Private Limited</b> a Company incorporated under the Companies Act, 2013 and having its corporate Office at 318, 3rd Floor, Summit-Business Bay, Behind Gurunanak Petrol Pump, Off Andheri Kurla Road, Beside Magic Bricks WEH metro stn., Andheri (E), Mumbai-400093 hereinafter referred to as"ICPL", which expression shall, unless repugnant to the meaning and context include and mean their legal representatives, nominees, administrators, permitted assigns etc. of the ONE PART</p>
              <p><b>AND</b></p>
              <p><b>' . $dealer_name . '</b> incorporated under the provision of the Companies Act, 2013, having its Registered Office at <b>' . $dealer_address . '</b>, hereinafter referred to as the "Vendor", which expression shall, unless repugnant to the meaning and context include and mean their legal heirs, legal representatives, successors, assigns, representatives, nominees, administrators, permitted assigns etc. as the case may be, being the Party of the OTHER PART;</p>
              <p>(ICPL and Vendor are hereinafter collectively referred to as "Parties" and individually as "Party").</p>
              <p style="margin:0;"><b>WHEREAS</b></p>
                 <table width="100%" cellpadding="0" border="0" cellspacing="0">
                <tr>
                  <td width="3%"></td>
                  <td width="4%">A.</td>
                  <td width="93%">ICPL is inter-alia engaged in the business as below:- </td>
                </tr>
              </table>
              <table width="100%" cellpadding="0" border="0" cellspacing="0">
                <tr>
                  <td width="7%"></td>
                  <td width="4%">1.</td>
                  <td width="89%">Providing assistance program, extended warranty program and other movable motorisedvehicle related services.</td>
                </tr>
                <tr>
                  <td width="7%"></td>
                  <td width="4%">2.</td>
                  <td width="89%">Advertisements in mass media including television, radio, internet, print media etc.;</td>
                </tr>
                 <tr>
                  <td width="7%"></td>
                  <td width="4%">3.</td>
                  <td width="89%">Printing and publishing of posters, banners, hoardings, pamphlets, hand bills, brochures etc. with slogans, pictures, animated depictions etc. as per the strategies approved by and instructions of the client/customer.</td>
                </tr>
                 <tr>
                  <td width="7%"></td>
                  <td width="4%">4.</td>
                  <td width="89%">Providing call center support for a products offering</td>
                </tr>
                 <tr>
                  <td width="7%"></td>
                  <td width="4%">5.</td>
                  <td width="89%">Documentation, Data Entry, Scanning and Storage of data with respect to the aforementioned activities; </td>
                </tr>
                 <tr>
                  <td width="7%"></td>
                  <td width="4%">6.</td>
                  <td width="89%">Creating awareness and interest in seminars and conferences organized by various companies with respect to its banking/ insurance and service sector.  business.</td>
                </tr>
                 <tr>
                  <td width="7%"></td>
                  <td width="4%">7.</td>
                  <td width="89%">Cross-selling of financial services products e.g. Mutual Funds, Share Trading, vehicle loans, personal loansetc.</td>
                </tr>
              </table>
                <table width="100%" cellpadding="0" border="0" cellspacing="0">
                <tr>
                  <td width="100%" height="15px" colspan="3"></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">B.</td>
                  <td width="93%">Assistance services provided in Schedule A attached to this Agreement (Services). </td>
                </tr>
              </table>
              <table width="100%" cellpadding="0" border="0" cellspacing="0">
                <tr>
                  <td width="100%" height="15px" colspan="3"></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">C.</td>
                  <td width="93%">On the basis of the aforesaid representations and warranties ICPL wishes to engage the Vendor to provide the Assistance Services under the terms and conditions of this Agreement.; and</td>
                </tr>
                <tr>
                  <td width="100%" height="15px" colspan="3"></td>
                </tr>
              </table>
                <p><b>IT IS HEREBY FURTHER AGREED BETWEEN THE PARTIES THAT,</b></p>
                <table width="100%" cellpadding="0" border="0" cellspacing="0">
                    <tr>
                  <td width="3%"></td>
                  <td width="4%">1.</td>
                  <td width="93%"><b>SERVICES:</b> Vendor shall provide such Services as provided in Schedule A to this Agreement. ICPL may increase or decrease or alter or change the scope of Services from time to time and the same shall be communicated to the Vendor in writing. The Vendor, pursuant to this Agreement, shall not, solicit, negotiate or accept any business or issue other documents in the name of or for and on behalf of ICPL; Vendors such specific request shall be in mutual consent to be listed and such arrangement shall be made in systems there to; else shall not make any promise or representation to or negotiate with clients in respect of any business or claim.<br></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">2.</td>
                  <td width="93%"><b>PAYMENT:</b> Vendor shall make all payment for such assistance services policies issued on behalf of ICPL. This payment shall be transferred/ credited to bank account of ICPL with in stipulated time/ _____days after issuing a service policy document on ICPL online platform. Vendor shalladd ICPL as beneficiary as detailed below:-<br></td>
                </tr>
                <tr>
                <td width="3%"></td>
                  <td width="4%"></td>
                  <td width="93%">
                    <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="30%"><b>Beneficiary name</b></td>
                        <td width="70%">: Indicosmic Capital Pvt. Ltd.</td>
                      </tr>
                      <tr>
                        <td><b>Bank Name</b></td>
                        <td><b>: ICICI Bank Ltd.</b></td>
                      </tr>
                      <tr>
                        <td><b>Bank Branch</b></td>
                        <td>: MIDC Andheri (E), Mumbai; </td>
                      </tr>
                      <tr>
                        <td><b>ank Ac Number</b></td>
                        <td><b>: 054405007965</b></td>
                      </tr>
                      <tr>
                        <td><b>IFSC Code</b></td>
                        <td>: ICIC0000544</td>
                      </tr>
                      <tr>
                        <td><b>ICPLGST No.</b></td>
                        <td><b>: 27AAECI3370G1ZN</b></td>
                      </tr>
                    </table>
                  </td>
</tr>
<tr>
<td width="3%"></td>
                  <td width="4%">3.</td>
                  <td width="93%"><b>FEES:</b> For Services satisfactorily rendered, ICPL shall pay Vendor such fees as are mutually agreed between the Parties (Fees) from time to time. The payment of Fees under this Agreement shall be maximum liability of ICPL under this Agreement. GST or any other tax, if any shall be borne by ICPL subject to Vendor providing the taxable invoice.<br></td>
</tr>
 <tr>
                  <td width="3%"></td>
                  <td width="4%">4.</td>
                  <td width="93%"><b>TERM:</b> This Agreement shall be valid for the perpetual period commencing from the effective date of this Agreement, as mentioned at the beginning (first page) of this Agreement.<br></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">5.</td>
                  <td width="93%"><b>TERMINATION:</b> Each Party shall be entitled to terminate this Agreement by giving 30 days prior notice without assigning any reason. ICPL at its sole discretion may terminate this Agreement forthwith if required to do so by any governmental authority. ICPL may terminate this Agreement forthwith by giving a notice in writing to Vendor on the occurrence of any or all of the following events:<br></td>
                </tr>
                </table>


  <table width="100%" cellpadding="0" border="0" cellspacing="0">
                <tr>
                  <td width="7%"></td>
                  <td width="4%">i.</td>
                  <td width="89%">If the Vendor breaches any law, rule or regulation as applicable from time to time;</td>
                </tr>
                <tr>
                  <td width="7%"></td>
                  <td width="4%">ii.</td>
                  <td width="89%">If there is a material change in the corporate form of the Vendor;</td>
                </tr>
                <tr>
                  <td width="7%"></td>
                  <td width="4%">iii.</td>
                  <td width="89%">An order is made by a court of competent jurisdiction for the dissolution or winding-up of the Vendor (otherwise than in the course of a re-organisation or restructuring previously approved in writing by the Vendor, which approval shall not be withheld unreasonably);</td>
                </tr>
                <tr>
                  <td width="7%"></td>
                  <td width="4%">iv.</td>
                  <td width="89%">Any step is taken (and not withdrawn within thirty (30) days) to appoint a liquidator, receiver or other similar officer in respect of any assets of the Vendor.</td>
                </tr>
                <tr>
                  <td width="100%" height="15px" colspan="3"></td>
                </tr>
                <tr>
                  <td width="7%"></td>
                  <td width="4%">5.1</td>
                  <td width="89%">After termination of this Agreement the Vendor shall provide all the support/assistance/co-operation as may be required by ICPL for ensuring a smooth transfer of the Services being performed by the Vendor pursuant to this Agreement to any third party identified by the Vendor or to ICPL itself, as the case may be. Further, immediately upon termination of this Agreement the Vendor shall return to ICPL all the information/documents/Assets relating to ICPL, provision of the Services to ICPL and all information shared by ICPL with the Vendor.</td>
                </tr>
                <tr>
                  <td width="100%" height="15px" colspan="3"></td>
                </tr>
                </table>

  <table width="100%" cellpadding="0" border="0" cellspacing="0">
                <tr>
                  <td width="3%"></td>
                  <td width="4%">6.</td>
                  <td width="93%"><b>INDEMNIFICATION:</b> Vendor shall at its own expense indemnify and hold harmless, and at ICPLs request defend, ICPL and its successors and assigns (and its and their officers, directors and employees) from and against all losses, liabilities, damages, settlements, expenses and costs (including attorneys fees and court costs) (the foregoing, collectively, Claim(s)) which arise directly or indirectly out of or relate to (a) any breach (or claim or threat thereof that, if true, would be a breach) of the terms of this Agreement or any provisions of applicable law by Vendor (b) the gross negligence or willful misconduct of Vendors employees or agents; (c) employment-related claims by Vendors employees or agents; (d) personal/bodily injury and property damage that arise from the performance of the Services; and/or ICPL may, at its option decide the amount of Claim and recover the same from the Vendor including by recovering from the amount, if any payable to the Vendor.<br></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">7.</td>
                  <td width="93%"><b>CONFIDENTIALITY:</b> Vendor undertakes that it shall not reveal, and shall use its reasonable efforts to ensure that its directors, officers, managers, partners, members, employees, legal, financial and professional advisors and bankers (collectively, Representatives) who have access to the Confidential Information relating to ICPL do not reveal, to any third party any such Confidential Information without the prior written consent of the ICPL. The Vendor shall ensure to implement required security policies, procedures and controls to protect the confidentiality and security of policyholders  information even after the contract terminates.  Upon termination of the agreement, the Vendor shall handover all the customer data to the Company and Vendor shall not use the customer data lying in its possession in any circumstances whatsoever.<br></td>
                </tr>
 <tr>
                  <td width="3%"></td>
                  <td width="4%">8.</td>
                  <td width="93%">ICPL shall continue to own and possess all intellectual property rights in the information/documents provided by it to the Vendor at all times, including, after termination of this Agreement.<br></td>
                </tr>
                 <tr>
                  <td width="3%"></td>
                  <td width="4%">9.</td>
                  <td width="93%">The Parties agree that nothing contemplated in this Agreement constitutes or may be construed to constitute the Vendor as an agent, broker or intermediary of ICPL for soliciting or procuring or marketing the insurance products to any customers, or that there exists a principal-agent relationship between the Vendor and ICPL, or confers any exclusivity to either Party for the arrangements as contemplated herein.<br></td>
                </tr>
                 <tr>
                  <td width="3%"></td>
                  <td width="4%">10.</td>
                  <td width="93%">The Vendor shall always act in accordance with provisions of applicable law during the course of providing the Services to ICPL and in any matter related thereto.<br></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">11.</td>
                  <td width="93%">The Vendor shall provide all the necessary support/assistance/co-operation as may be required by ICPL to comply with applicable law or the instructions/directors of any governmental authorities. By the virtue of this agreement vendor is abide by to comply with the provisions of applicable law. Vendor may provide the relevant information/clarification/documents required by the respective authorities from time to time, if any.<br></td>
                </tr>

 <tr>
                  <td width="3%"></td>
                  <td width="4%">12.</td>
                  <td width="93%">The Vendor shall not engage directly or indirectly make, offer or agree to offer anything of value to any government official, employee of the Company, political party or official thereof or candidate for government office in order to obtain, retain or direct business to any business enterprise or person, or to obtain an advantage. The Vendor shall be fully responsible for all consequences arising out of a breach or anticipated breach of this condition. No unethical or illegal action shall be performed by Vendor or any of its employee Representatives in relation to performing the Vendors obligations under this engagement Agreement.<br></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">13.</td>
                  <td width="93%">All work products including any tangible or intangible program etc., which are created under this Agreement shall be owned by the Company. The Service Provider shall ensure to comply with the security standards of the Company for the purpose of the data security and protection of confidential information including the policyholders information.<br></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">14.</td>
                  <td width="93%">Notwithstanding anything contained in this Agreement, the Company can undertake the services which has been outsourced under this Agreement, exclusively, simultaneously or in such manner as in deems fit. There shall be no obligation on the Company to notify the Vendor for providing any services under this Agreement at any point of time.<br></td>
                </tr>



<tr>
                  <td width="3%"></td>
                  <td width="4%">15.</td>
                  <td width="93%">Vendor shall not subcontract any of the activity under this Agreement without the express prior written consent of ICPL.<br></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">16.</td>
                  <td width="93%">ICPL /its auditor/representatives/Regulators shall be entitled to inspect/ audit the premises of the Vendor by giving reasonable prior written notice and the Vendor shall provide all related information as may be required by the said representatives/auditors of ICPL. ICPL may also call for the related records from Vendor, which Vendor shall provide within 3 days from the date such requirement for records is raised by ICPL. In addition, the Vendor shall be subject to continuous monitoring and assessment by ICPL, in the manner as ICPL may deem fit and the Vendor undertakes to take all the necessary corrective measures which ICPL may require from time to time.<br></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">17.</td>
                  <td width="93%">Vendor, for same services shall not directly or indirectly engage itself into any activity/business other than withICPL.<br></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">18.</td>
                  <td width="93%">Both Parties shall comply with the respective laws applicable to each of them.<br></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">19.</td>
                  <td width="93%">The Vendor shall have adequate mechanism of disaster recovery and shall ensure that in case of flood or other circumstances, the Vendor has alternative mode of providing services. The Vendor shall have adequate business continuity planning (BCP) for the processes provided under the scope of Services herein.<br></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">20.</td>
                  <td width="93%"><b>Grievance Redressal</b><br>Any complaints, abuse or concerns with regards to assistance services and or comment or breach of  terms shall be immediately informed to toll free contact information provided on the service contract copy. in case issue needs to be escalated then the Designated Grievance Officer for grievances redressal is as mentioned below can be contacted via writing through email signed with the electronic signature at <a href="info@indicosmic.com">info@indicosmic.com</a> OR write at the below address:-<br><br><b><i>Mr. Linto Francis</i></b><br>Grievance Redressal Officer<br>Indicosmic Capital Pvt. Ltd.; 318, 3rd Floor, Summit-Business Bay, Behind Gurunanak Petrol Pump, Off AndheriKurla Road, Beside Magic Bricks WEH metro stn., Andheri (E), Mumbai-400093.<br>Tel.  No. +9122 2088 0555<br></td>
                </tr>
                <tr>
                  <td width="3%"></td>
                  <td width="4%">21.</td>
                  <td width="93%"><b>GOVERING LAW & DISPUTE RESOLUTION:</b> This Agreement shall be governed by and interpreted in accordance with Indian law. Any Disputes, arising under or in relation to this Agreement if any,  shall be referred to the a sole arbitrator to be appointed by ICPL both Parties in accordance with the (Indian) Arbitration and Conciliation Act, 1996 or any amendment thereto. Venue of arbitration and hearings shall be Mumbai, India.<br></td>
                </tr>
</table>

<p><b>IN WITNESS WHEREOF,</b> the Parties have caused this Agreement to be duly executed and delivered as of the day and year first above written:-</p>
              <p>&nbsp;</p>
              <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="47%"><b>For</b> ____________________________________</td>
                  <td width="6%"></td>
                  <td width="47%"><b>For Indicosmic Capital Private Limited</b></td>
                </tr>
                <tr>
                  <td width="100%" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td width="100%" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td width="100%" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td width="47%">Name: __________________________________</td>
                  <td width="6%"></td>
                  <td width="47%"><b>Mr. Amit Deep</b></td>
                </tr>
                <tr>
                  <td width="100%" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td width="47%">Designation: _____________________________</td>
                  <td width="6%"></td>
                  <td width="47%">Chief Operating Officer</td>
                </tr>
                <tr>
                  <td width="100%" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td width="100%" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td width="47%">Witness 1) ______________________________</td>
                  <td width="6%"></td>
                  <td width="47%">Witness 2) ______________________________</td>
                </tr>
              </table>

              </td>
        </tr>
</table>
    </td>
  </tr>
</table>
<br pagebreak="true" />
<table cellpadding="0" border="0" cellspacing="0" class="asistance-table"> 
  <tr>
    <td colspan="4" col style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt;">&nbsp; THE SERVICES PROVIDED UNDER THE ASSISTANCE ARE AS FOLLOWS:</td>
  </tr>         
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
          <td width="82%">In the event that a Covered Vehicle is immobilized due to electrical or mechanical failure, ICPL shall arrange towing of the Covered Vehicle to the nearest authorized outlet. These services shall be provided using equipment/s deemed most suitable by ICPL. Towing up to 50 km from incident to nearest garage is free.</td>
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
  <style> 
  .asistance-table { font-size:7pt; line-height:8pt;} 
  .dotborderleft {border-left:0.5px dotted #014e9f}
  .dotborderright {border-right:0.5px dotted #014e9f}
  .asistance-sectionhead { font-size:8pt; line-height:9pt; background-color:#63a5ea; color:#fff;}  
</style>
';

        $pdf->writeHtml($html);
        //"policy"-firtsandlastnamecompany-policynumber
        $pdf_to_name = "Rsa_Agreement.pdf";
        // $pdf_to_name = $insured_name.' - '.$policy_no.'.pdf';
        // $pdf_to_name = str_replace(" ", "-", 'test');
        ob_clean();
        $pdf->Output($pdf_to_name, 'D');
    }

    function dealer_form() {
       // $this->data['user_session'] = $this->session->userdata('user_session');
        $this->data['company_type'] = $this->Home_Model->getDataFromTable('company_type');
        $pa_ics_ar = $this->db->query("SELECT * FROM tvs_insurance_companies WHERE insurance_type = 'PA' AND id NOT IN (6,7,2) ")->result_array();
        $universal = array(
            'id'=>'all',
            'dms_ic_id' =>'',
            'insurance_type' =>'',
            'name'=> 'Universal',
            'display_name'=>'Universal',
            'logo'=>'',
            'email'=>'',
            'address'=>'',
            'toll_free_no'=>'',
            'certificate_no_prefix'=>'',
            'certificate_no'=>''
        );
        array_push($pa_ics_ar, $universal);
        $this->data['pa_ics'] = $pa_ics_ar;
        // echo '<pre>'; print_r($pa_ics_ar);die('here');
        $this->load->dashboardTemplate('front/myaccount/dealer_form',$this->data);
    }

    function DealerDocumentForm() {
        $user_session = $this->session->userdata('user_session');
        $dealer_id = $user_session['id'];
        $sap_ad_code = $user_session['sap_ad_code'];
         if( (strlen($sap_ad_code) != 5) && !in_array($sap_ad_code, array('1011591','1010964')) ){
          redirect('dashboard');
        }
        if (empty($dealer_id)) {
            redirect(base_url());
        }else {
            $where = array('dealer_id' => $dealer_id);
            $is_downloaded = $this->Home_Model->checkIsPdfDownloaded();
            $is_downloaded = $is_downloaded['is_pdf_downloaded'];
            $this->data['is_downloaded'] = ($is_downloaded == 0) ? "FALSE" : "TRUE";
            if ($is_downloaded == 0) {
                $this->Home_Model->updatePdfDownloadedStatus();
            }
            $check_dealer_docs = $this->Home_Model->getDataFromTable('tvs_dealer_documents', $where);
            // echo '<pre>'; print_r($check_dealer_docs);die('hello');
            $this->data['check_dealer_docs'] = isset($check_dealer_docs) ? $check_dealer_docs : '';
            $this->load->dashboardTemplate('front/myaccount/dealer_doc_form', $this->data);
        }
    }

     public function getBankDetailsByIFSC(){
        $ifsccode = $this->input->post('ifsc_code');
        // First APi Call
        $curl = curl_init();
/*        $ifsccode = $_GET['ifsc'];
*/
        $url = "https://ifsc.razorpay.com/".$ifsccode;

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response = json_decode($response);
        $response = (array)$response;
        //Second Api Call
        $curl = curl_init();
        $url = "https://api.bank.codes/in-ifsc/?format=json&api_key=7e21a094a023bebbb2ea50434eafb83e&ifsc=".$ifsccode;
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response1 = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response1 = json_decode($response1);
        $response1 = (array) $response1;
        $district = isset($response['DISTRICT'])?$response['DISTRICT']:$response1['district'];
        $address = isset($response['ADDRESS'])?$response['ADDRESS']:$response1['address'];
        $contact = isset($response['CONTACT'])?$response['CONTACT']:$response1['contact'];
        $branch = isset($response['BRANCH'])?$response['BRANCH']:$response1['branch'];
        $state = isset($response['STATE'])?$response['STATE']:$response1['state'];
        $city = isset($response['CITY'])?$response['CITY']:$response1['city'];
        $bank = isset($response['BANK'])?$response['BANK']:$response1['bank'];
        $bankcode = isset($response['BANKCODE'])?$response['BANKCODE']:'';
        $ifsc = isset($response['IFSC'])?$response['IFSC']:$response1['ifsc'];
        $micr = isset($response1['micr'])?$response1['micr']:'';
        $finalArray = array(
            'address' =>$this->clean($address),
            'bank' =>$this->clean($bank)
        );
        // if($response[0])
        //print_r($finalArray);
       echo  json_encode($finalArray);
    }
    function clean($string) {
         $string = str_replace('-', ' ', $string); // Replaces all spaces with hyphens.
         return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
      }



    function DealerUploadsData() {
        $dealer_id = $this->session->userdata('user_session')['id'];
        $sql = "SELECT * FROM `tvs_dealer_documents` WHERE `dealer_id`='$dealer_id'";
        $result = $this->db->query($sql)->row();
        // echo '<pre>';print_r($_FILES);die('hello');
        $agreement_pdf = !empty($_FILES['agreement_pdf']['name']) ? $this->fn_do_upload_image('agreement_pdf') : @$result->agreement;
        $gst_certificate = !empty($_FILES['gst_certificate']['name']) ? $this->fn_do_upload_image('gst_certificate') : @$result->gst_certificate;
        $pancard = !empty($_FILES['pancard']['name']) ? $this->fn_do_upload_image('pancard') : @$result->pancard;
        $cancel_cheque = !empty($_FILES['cancel_cheque']['name']) ? $this->fn_do_upload_image('cancel_cheque') : @$result->cancel_cheque;

        // echo $agreement_pdf ; echo "<pre>";
        // echo $gst_certificate; echo "<pre>";
        // echo $pancard; echo "<pre>";
        // echo $cancel_cheque ;  echo "<pre>";
        // die;

        $user_session = $this->session->userdata('user_session');
        $upload_data = array(
            'dealer_id' => $user_session['id'],
            'agreement' => $agreement_pdf,
            'gst_certificate' => $gst_certificate,
            'pan_card' => $pancard,
            'cancel_cheque' => $cancel_cheque,
            'created_at' => date('Y-m-d H:i:s')
        );
        // echo '<pre>'; print_r($upload_data);die('hello img');
        if (empty($result)) {
            $this->Home_Model->insertIntoTable('tvs_dealer_documents', $upload_data);
        } else {
            $insert_id = $result->id;
            $upload_data['updated_at'] = date('Y-m-d H:i:s');
            $where = array("id" => $insert_id);
            $status = $this->Home_Model->updateTable('tvs_dealer_documents', $upload_data, $where);
        }
        $this->session->set_flashdata('upload_success', 'Documents are Successfully Uploaded');
        redirect('dealer_document_form');
    }


    function fn_do_upload_image($image_file_name) {
        $upload_PATH_NAME = './uploads/dealer_docs/';

        if ($_FILES[$image_file_name]['name'] != "") {
            $config = array(
              'upload_path' => $upload_PATH_NAME,
              'allowed_types' => 'gif|jpg|png|jpeg|pdf'           
            );
           
            
            $this->load->library('upload', $config);
            //$this->upload->initialize($config);
            $upload = $this->upload->do_upload($image_file_name);
            $get_uploaded_name = $this->upload->data('file_name');
            // echo '<pre>';print_r($get_uploaded_name);die;
            return $get_uploaded_name;
        }
    }


    function fetchLocation() {
        $pin = $this->input->post('pincode');
        if (!empty($pin)) {
            $where = array(
                'pin_code' => $pin
            );
        }

        $locatin_data = $this->Home_Model->getRowDataFromTableWithOject('hc_pincode_master', $where);
        // echo "<pre>";print_r($locatin_data->city_or_village_name);die;
        $result['status'] = false;
        if (!empty($locatin_data)) {
            $selected = ($cityy == $selected_city) ? "selected = 'selected'" : "";

            $result['status'] = true;

            $result['state'] = $locatin_data->state_id_pk . '-' . $locatin_data->state_name;

            $where = array(
                'state_id_pk' => $locatin_data->state_id_pk
            );
            $cities = $this->Home_Model->fetchCities($where);
            $city = $locatin_data->city_or_village_id_pk . '-' . $locatin_data->city_or_village_name;
            foreach ($cities as $c) {
                $cityy = $c->city_or_village_id_pk . '-' . $c->city_or_village_name;
                $selected = ($cityy == $city) ? "selected = 'selected'" : "";
                $result['city_html'] .= '<option value= "' . $cityy . '" ' . $selected . '>' . $c->city_or_village_name . '</option>';
            }
        }
        echo json_encode($result);
    }

        function GstTransanction(){
      $user_session = $this->session->userdata('user_session');
      $sap_ad_code = $user_session['sap_ad_code'];
      if ( (strlen($sap_ad_code) ==5) || in_array($sap_ad_code, array('1011591','1010964')) ) {
            $this->load->dashboardTemplate('front/myaccount/gst_transanction');
        } else {
            redirect('dashboard');
        }
    }

    function GstTransanctionAjax(){
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columns = array(
            0 => 'invoice_no',
            1 => 'gst_amount',
            2 => 'is_gst_compliant_file_uploaded',
            3 => 'gst_compliant_file',
            4 => 'approval_status',
            5 => 'comment',
            6 => 'created_at',
        );
        $dealer_id = $this->session->userdata('user_session')['id'];
        $sql = "SELECT tdg.*,idd.invoice_month FROM `tvs_dealers_gst_status` tdg JOIN invoice_details idd ON idd.id = tdg.invoice_id WHERE tdg.dealer_id = '$dealer_id' ORDER BY tdg.`approval_status` DESC ";
        // echo '<pre>'; print_r($sql);die('hello');
        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $result = $query->result();
        $data = array();
        
        $k=1;
        foreach ($result as $main) {
          $action='';
          if($main->is_gst_compliant_file_uploaded==0 || (in_array($main->approval_status, array("pending","referback"))) ){
            // $action = "<button id='complaint_upload_btn' onclick='UploadModalOpen(".$main->dealer_id.",".$main->invoice_no.")' class='btn btn-primary'>Upload</button>";
            
            $action = "<button id='complaint_upload_btn' onclick=\"UploadModalOpen(".$main->dealer_id.",'".trim($main->invoice_no)."')\" class='btn btn-primary'>Upload</button>";
          }
          $gst_compliant_file ="No such File";
          if(!empty($main->gst_compliant_file)){
            $gst_compliant_file = '<a href="'.base_url('uploads/gst_uploaded_files').'/'.$main->gst_compliant_file.'" download>'.$main->gst_compliant_file.'</a>';
          }
            $row = array();
            $row[] = $k;
            $row[] = $main->invoice_no;
            $row[] = $main->invoice_month;
            $row[] = $main->gst_amount;
            $row[] = ($main->is_gst_compliant_file_uploaded==1)?'Yes':'No';
            $row[] = $gst_compliant_file;
            $row[] = $main->approval_status;
            $row[] = $main->comment;
            $row[] = $main->created_at;
            $row[] = $action;
        
            $data[] = $row;
       $k++;
     }  
        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

    function UploadGstComplaintData(){
      $post = $this->input->post();
      $check_exist = $this->Home_Model->CheckGSTUploadExist($post['dealer_id'],$post['invoice_no']);
      // echo "<pre>"; print_r($check_exist); echo "</pre>"; die('end of line yoyo');
      $file_name = $_FILES['gst_upload_file']['name'];
      // echo "<pre>"; print_r($_FILES); echo "</pre>"; die('end of line yoyo');
      if(!empty($post['dealer_id'] && !empty($file_name) )){
          $upload_PATH_NAME = './uploads/gst_uploaded_files/';


          $allowed_image_extension = array("png","jpg","jpeg");
               $allow_upload = 'false';    
                  // Get image file extension
              $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
              if (in_array($file_extension, $allowed_image_extension)) {
                  $allow_upload = 'true';
              }
              // die($allow_upload);
              if($file_name != "" && $allow_upload=='true') {
              $config = array(
                'upload_path' => $upload_PATH_NAME,
                'allowed_types' => 'gif|jpg|png|jpeg'           
              );

              $this->load->library('upload', $config);
              //$this->upload->initialize($config);
              $upload = $this->upload->do_upload('gst_upload_file');
              $get_uploaded_name = $this->upload->data('file_name');
              // echo '<pre>';print_r($get_uploaded_name);die('gst_upload_file');
              if(!empty($check_exist)){
                /////Referback///
                $exist_gst_file = FCPATH.'uploads/gst_uploaded_files/'.$check_exist['gst_compliant_file'];
                $unlink_status = unlink($exist_gst_file);
                  if($unlink_status){
                        if (!empty($get_uploaded_name)) {
                            $gst_data = array(
                                'is_gst_compliant_file_uploaded' => 1,
                                'gst_compliant_file' => $get_uploaded_name,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'approval_status' => 'pending'
                            );
                        }
                  }
              }else{
                    if (!empty($get_uploaded_name)) {
                        $gst_data = array(
                            'is_gst_compliant_file_uploaded' => 1,
                            'gst_compliant_file' => $get_uploaded_name,
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        
                    }
              }
              
              $where = array('dealer_id'=>$post['dealer_id'] ,'invoice_no'=>$post['invoice_no'] );
              $status = $this->Home_Model->updateTable('tvs_dealers_gst_status',$gst_data,$where);
          }
      }
      redirect('gst_transanction');
    }

     public function myDashboardSection($dealer_bank_trans_id=0) {
        $user_session = $this->session->userdata('user_session');
        $where = array('id'=>$user_session['id']);
        $dealer_data =  $this->Home_Model->getDataFromTable('tvs_dealers',$where);
        $dealer_data = $dealer_data[0];
        $sap_ad_code = $user_session['sap_ad_code'];
        $this->data['user_session'] = $dealer_data;
        // echo '<pre>';print_r($dealer_data);die;
        $dealer_bank_data='';
        if(!empty($dealer_bank_trans_id)){
          $dealer_bank_data = $this->Home_Model->getDealerBankTransaction($dealer_bank_trans_id);
          $created_date = explode(' ',$dealer_bank_data['created_date']);
          $date = new DateTime($created_date[0]);
          $date->format('Y-m-d'); 
          $created_date = $date->format('m/d/Y');
          // echo '<pre>';print_r($created_date);die;
          $this->data['dealer_bank_data'] = $dealer_bank_data;
          $this->data['dealer_bank_data']['created_date'] = $created_date;

        }
		if(in_array($sap_ad_code, array('102123','102121') ) ){
          redirect('summary_page');
        }		
        else if ( (strlen($sap_ad_code) ==5) || in_array($sap_ad_code, array('1011591','1010964')) ) {
            $this->load->dashboardTemplate('front/myaccount/my_account',$this->data);
        } else {
            redirect('dashboard');
        }
    }


    public function GeneratedInvoiceList(){
      $this->data['user_id'] = $dealer_id = $this->session->userdata('user_session')['id'];
      $sap_ad_code = $this->session->user_session['sap_ad_code'];
      $where = array('dealer_id' => $dealer_id);
      $invoice_data = $this->db->query("SELECT * FROM invoice_details WHERE dealer_id = '$dealer_id' AND invoice_status NOT IN('rejected') ")->result_array();
      // echo '<pre>'; print_r($invoice_data);die('hello moto');
      $policy_data = $this->db->query("SELECT Date(created_date) AS created_date FROM tvs_sold_policies where user_id = '$dealer_id' AND policy_status_id = 3 ORDER BY id ASC Limit 1")->result_array();
      // echo '<pre>'; print_r($policy_data);die('hello moto');
      $invoice_date_ar = array();
      foreach($invoice_data as $data){
        $selected_invoice_date = '01-'.$data['invoice_month'];
        $hide_months = date("M-Y", strtotime($selected_invoice_date));
        $invoice_date_ar[] = $hide_months;
       }
      $this->data['invoice_date_ar'] = $invoice_date_ar = implode(',', $invoice_date_ar);
      $this->data['policy_started_from'] = date("m-Y", strtotime($policy_data[0]['created_date'])) ;

      if ( (strlen($sap_ad_code) ==5) || in_array($sap_ad_code, array('1011591','1010964')) ) {
      $this->load->dashboardTemplate('front/myaccount/generated_invoice',$this->data);
      }else{
        redirect('dashboard');
      }
}
    public function InvoiceListAjax(){
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
        $dealer_id = $this->session->userdata('user_session')['id'];
        $sql = "SELECT ind.* ,td.dealer_name FROM invoice_details ind 
                JOIN tvs_dealers td ON ind.dealer_id = td.id WHERE ind.dealer_id='$dealer_id' ";
        // echo '<pre>'; print_r($sql);die('hello');
        $totalFiltered = $totalData;
        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        $data = array();
        $i = 1;
        foreach ($result as $main) {
            // echo ($main->invoice_data) ;die('sds');
            $created_date = explode(' ', $main->created_at);
            $created_date = $created_date[0];
            $invoice_data = json_decode($main->invoice_data,true);
            // echo '<pre>'; print_r($invoice_data);die('hello');
            $link = '<a class"form-control btn btn-success" href="'.base_url('invoice_pdf').'/'.$main->id.'" target="_blank"><i class="fa fa-download"></i></a>';
            $edit = '';
            if($main->invoice_status=='referback'){
              $edit = '<a class"form-control btn btn-success" onclick="edit_invoice_data('.$main->id.')" ><i class="fa fa-edit"></i></a>';
            }
            $row = array();
            $row[] = $main->dealer_name;
            $row[] = $main->invoice_no;
            $row[] = $main->invoice_date;
            $row[] = $main->invoice_month;
            $row[] = $invoice_data['total_policy_count'];
            $row[] = $invoice_data['total_policy_premium'];
            $row[] = $invoice_data['total_policy_gst'];
            $row[] = $invoice_data['final_policy_premium'];
            $row[] = $main->invoice_status;
            $row[] = $created_date;
            $row[] = $main->comment;
            $row[] =  $link.' '.$edit;
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

     public function getDealerInvoice(){
     $post = $this->input->post();
      $invoice_id = ($post['invoice_id']) ? $post['invoice_id'] : '';
      // echo "<pre>"; print_r($invoice_data); echo "</pre>"; die('end of line yoyo');
      if(!empty($invoice_id)){
            $invoice_details = $this->db->query("SELECT ind.* ,td.* FROM invoice_details ind 
                          JOIN tvs_dealers td ON ind.dealer_id = td.id WHERE ind.id='$invoice_id'")->row_array();
            $dealer_address = strtoupper($invoice_details['add1'].','.$invoice_details['add2'].','.$invoice_details['location'].'-'.$invoice_details['pin_code'].','.$invoice_details['state']);
            $invoice_no = ($invoice_details['invoice_no'])?$invoice_details['invoice_no']:'';
            $invoice_date = ($invoice_details['invoice_date'])?$invoice_details['invoice_date']:'';
            $month = date("M",strtotime($invoice_date));
            $year = date("y",strtotime($invoice_date));
            $invoice_date_format = date("y",strtotime($invoice_date));
            $gst_no = strtoupper($invoice_details['gst_no']);
            $dealer_name = strtoupper($invoice_details['dealer_name']);
            $pan_no = strtoupper($invoice_details['pan_no']);
            $state = strtoupper($invoice_details['state']);
            $invoice_month1 = $invoice_details['invoice_month'];
            $invoice_data = json_decode($invoice_details['invoice_data'],true);
            extract($invoice_data);
            // echo '<pre>';print_r($invoice_data);die('asasd');
      }else{
            $invoice_month1 = $post['invoice_month'];
            $invoice_month = '01-'.$invoice_month1;
            $month = date("M",strtotime($invoice_month));
            $year = date("y",strtotime($invoice_month));
            $dealer_policy_records = $this->Home_Model->getDealerSoldPolicies($invoice_month);
            if(!empty($dealer_policy_records)){
               extract($dealer_policy_records);
            }
              // echo "<pre>"; print_r($dealer_policy_records); echo "</pre>"; die('end of line yoyo');
            $gst_no = $gst_no?strtoupper($gst_no):'';;
            $dealer_name = $dealer_name?strtoupper($dealer_name):'';
            //silver policy
            $dealer_address = ($add1)?$add1:''.','.($add2)?$add2:''.','.($location)?$location:''.'-'.($pin_code)?$pin_code:''.','.($state)?$state:'';
            $count_silver_policies = ($count_silver_policies - $count_silver_policies_cancelled);
            $silver_policy_premium = ($silver_policy_premium - $silver_policy_cancelled_premium);
            $silver_policy_gst     = ($silver_policy_gst - $silver_policy_cancelled_gst);
            $silver_policy_total_commission = (($silver_policy_commission + $silver_policy_commission_gst) - ($silver_policy_cancelled_commission + $silver_policy_cancelled_commission_gst));
            $silver_policy_commission = ($silver_policy_commission - $silver_policy_cancelled_commission);
            $silver_policy_commission_gst     = ($silver_policy_commission_gst - $silver_policy_cancelled_commission_gst);

            //gold policy
            $count_gold_policies = ($count_gold_policies - $count_gold_policies_cancelled);
            $gold_policy_premium = ($gold_policy_premium - $gold_policy_cancelled_premium);
            $gold_policy_gst     = ($gold_policy_gst - $gold_policy_cancelled_gst);
            $gold_policy_total_commission = (($gold_policy_commission +$gold_policy_commission_gst) - ($gold_policy_cancelled_commission+$gold_policy_cancelled_commission_gst));
            $gold_policy_commission = ($gold_policy_commission - $gold_policy_cancelled_commission);
            $gold_policy_commission_gst     = ($gold_policy_commission_gst - $gold_policy_cancelled_commission_gst);

            //Platinum policy
            $count_platinum_policies = ($count_platinum_policies - $count_platinum_policies_cancelled);
            $platinum_policy_premium = ($platinum_policy_premium - $platinum_policy_cancelled_premium);
            $platinum_policy_gst     = ($platinum_policy_gst - $platinum_policy_cancelled_gst);
            $platinum_policy_total_commission = (($platinum_policy_commission +$platinum_policy_commission_gst) - ($platinum_policy_cancelled_commission +$platinum_policy_cancelled_commission_gst));
            $platinum_policy_commission = ($platinum_policy_commission - $platinum_policy_cancelled_commission);
            $platinum_policy_commission_gst     = ($platinum_policy_commission_gst - $platinum_policy_cancelled_commission_gst);

            //Sapphire policy
            $count_sapphire_policies = ($count_sapphire_policies - $count_sapphire_policies_cancelled);
            $sapphire_policy_premium = ($sapphire_policy_premium - $sapphire_policy_cancelled_premium);
            $sapphire_policy_gst     = ($sapphire_policy_gst - $sapphire_policy_cancelled_gst);
            $sapphire_policy_total_commission = (($sapphire_policy_commission + $sapphire_policy_commission_gst) - ($sapphire_policy_cancelled_commission + $sapphire_policy_cancelled_commission_gst));
            $sapphire_policy_commission = ($sapphire_policy_commission - $sapphire_policy_cancelled_commission);
            $sapphire_policy_commission_gst     = ($sapphire_policy_commission_gst - $sapphire_policy_cancelled_commission_gst);

            //Sapphire Plus Policy
            $count_sapphireplus_policies = ($count_sapphire_plus_policies - $count_sapphire_plus_policies_cancelled);
            $sapphire_plus_policy_premium = ($sapphire_plus_policy_premium - $sapphire_plus_policy_cancelled_premium);
            $sapphire_plus_policy_gst     = ($sapphire_plus_policy_gst - $sapphire_plus_policy_cancelled_gst);
            $sapphire_plus_policy_total_commission = (($sapphire_plus_policy_commission +$sapphire_plus_policy_commission_gst) -($sapphire_plus_policy_cancelled_commission+$sapphire_plus_policy_cancelled_commission_gst));
            $sapphire_plus_policy_commission = ($sapphire_plus_policy_commission - $sapphire_plus_policy_cancelled_commission);
            $sapphire_plus_policy_commission_gst     = ($sapphire_plus_policy_commission_gst - $sapphire_plus_policy_cancelled_commission_gst);

            //limitless renewal
            $count_limitless_renewal_policies = ($count_limitless_assist_renewal_policies - $count_limitless_assist_renewal_policies_cancelled);
            $limitless_renewal_policy_premium = ($limitless_assist_renewal_policy_premium - $limitless_assist_renewal_policy_cancelled_premium);
            $limitless_renewal_policy_gst     = ($limitless_assist_renewal_policy_gst - $limitless_assist_renewal_policy_cancelled_gst);
            $limitless_renewal_policiy_total_commission = (($limitless_assist_renewal_policy_commission + $limitless_assist_renewal_policy_commission_gst) - ($limitless_assist_renewal_policy_cancelled_commission + $limitless_assist_renewal_policy_cancelled_commission_gst));
            $limitless_assist_renewal_policy_commission = ($limitless_assist_renewal_policy_commission - $limitless_assist_renewal_policy_cancelled_commission);
            $limitless_renewal_policy_commission_gst     = ($limitless_assist_renewal_policy_commission_gst - $limitless_assist_renewal_policy_cancelled_commission_gst); 

            $total_policy_count = round(($count_silver_policies + $count_gold_policies + $count_platinum_policies + $count_sapphire_policies + $count_sapphireplus_policies + $count_limitless_renewal_policies),2);
            $total_policy_premium = round(($silver_policy_premium + $gold_policy_premium + $platinum_policy_premium + $sapphire_policy_premium +$sapphire_plus_policy_premium + $limitless_renewal_policy_premium),2);
            $total_policy_gst = round(($silver_policy_gst + $gold_policy_gst + $platinum_policy_gst + $sapphire_policy_gst+$sapphire_plus_policy_gst + $limitless_renewal_policy_gst),2);

            $total_policy_commission = round(($silver_policy_commission + $gold_policy_commission + $platinum_policy_commission + $sapphire_policy_commission + $sapphire_plus_policy_commission + $limitless_assist_renewal_policy_commission),2);
            $total_policy_commission_gst = round(($silver_policy_commission_gst + $gold_policy_commission_gst + $platinum_policy_commission_gst + $sapphire_policy_commission_gst +$sapphire_plus_policy_commission_gst + $limitless_renewal_policy_commission_gst),2);

           $final_policy_premium =  round($total_policy_premium + $total_policy_gst);
           $final_policy_commission =  ($total_policy_commission + $total_policy_commission_gst);
      }
     
     $invoice_data_array =array(
          'count_silver_policies' => $count_silver_policies,
          'silver_policy_premium' => $silver_policy_premium,
          'silver_policy_gst' => $silver_policy_gst,
          'silver_total_policy_premium' => $silver_total_policy_premium,
          'silver_policy_commission' => $silver_policy_commission,
          'silver_policy_commission_gst' => $silver_policy_commission_gst,
          'silver_policy_total_commission' => $silver_policy_total_commission,

          'count_gold_policies' => $count_gold_policies,
          'gold_policy_premium' => $gold_policy_premium,
          'gold_policy_gst' => $gold_policy_gst,
          'gold_total_policy_premium' => $gold_total_policy_premium,
          'gold_policy_commission' => $gold_policy_commission,
          'gold_policy_commission_gst' => $gold_policy_commission_gst,
          'gold_policy_total_commission' => $gold_policy_total_commission,

          'count_platinum_policies' => $count_platinum_policies,
          'platinum_policy_premium'=> $platinum_policy_premium,
          'platinum_policy_gst' => $platinum_policy_gst ,
          'platinum_total_policy_premium' => $platinum_total_policy_premium,
          'platinum_policy_commission' => $platinum_policy_commission ,
          'platinum_policy_commission_gst' => $platinum_policy_commission_gst ,
          'platinum_policy_total_commission' => $platinum_policy_total_commission,

          'count_sapphire_policy' => $count_sapphire_policies,
          'sapphire_policy_premium' => $sapphire_policy_premium,
          'sapphire_policy_gst' => $sapphire_policy_gst,
          'sapphire_total_policy_premium' => $sapphire_total_policy_premium,
          'sapphire_policy_commission' => $sapphire_policy_commission,
          'sapphire_policy_commission_gst' => $sapphire_policy_commission_gst,
          'sapphire_policy_total_commission' => $sapphire_policy_total_commission,

          'count_sapphireplus_policy' => $count_sapphireplus_policies,
          'sapphireplus_policy_premium' => $sapphire_plus_policy_premium,
          'sapphireplus_policy_gst' => $sapphire_plus_policy_gst,
          'sapphireplus_total_policy_premium' => $sapphire_plus_total_policy_premium,
          'sapphireplus_policy_commission' => $sapphire_plus_policy_commission,
          'sapphireplus_policy_commission_gst' => $sapphire_plus_policy_commission_gst,
          'sapphireplus_policy_total_commission' => $sapphire_plus_policy_total_commission,

          'count_limitless_renewal_policy' => $count_limitless_renewal_policies,
          'limitless_renewal_policy_premium' => $limitless_renewal_policy_premium,
          'limitless_renewal_policy_gst' => $limitless_renewal_policy_gst,
          'limitless_renewal_total_policy_premium' => $limitless_assist_renewal_total_policy_premium,
          'limitless_renewal_policy_commission' => $limitless_assist_renewal_policy_commission,
          'limitless_renewal_policy_commission_gst' => $limitless_renewal_policy_commission_gst,
          'limitless_renewal_policy_total_commission' => $limitless_renewal_policiy_total_commission,

          'total_policy_count' => $total_policy_count,
          'total_policy_premium' => $total_policy_premium,
          'total_policy_gst' => $total_policy_gst,
          'final_policy_premium' => $final_policy_premium,
          'total_policy_commission' => $total_policy_commission,
          'total_policy_commission_gst' => $total_policy_commission_gst,
          'final_policy_commission' => $final_policy_commission
       );
     $this->session->set_userdata('invoice_detail',$invoice_data_array);
     // echo '<pre>';print_r($invoice_data_array);die;
      $html =<<<EOD
      
      <form class="invoice-form" id="invoice_form">
            <div class="row">
                <Div class="col-md-6"><p><b>Dealership Name:</b>- {$dealer_name}</p></div>
            </div>
            <div class="row">
                <div class="col-md-6"><p><b>Addr:</b>- {$dealer_address}</p></div>
                <div class="col-md-6 text-right"><p><b>Invoice Month:-</b> {$invoice_month1}</p></div>
            </div>
             <div class="row" style="margin-bottom:20px;">
                <div class="col-md-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Invoice No</span>
                  </div>
                  <span id="invoice_er" style="color:red;"></span>
                  <input type="text" class="form-control" id="invoice_no" name="invoice_no" value="{$invoice_no}" placeholder="Invoice No" aria-label="Invoice No" aria-describedby="basic-addon1" required>
                </div>
                 <div class="col-md-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text">GST No</span>
                  </div>
                  <span id="invoice_er" style="color:red;"></span>
                  <input type="text" class="form-control" id="gst_no" name="gst_no" value="{$gst_no}" readonly>
                </div>
                <div class="col-md-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Invoice Date</span>
                  </div>
                  <span id="date_er" style="color:red;"></span>
                  <input type="text" class="form-control invoice_date" id="invoice_date" name="invoice_date" value="{$invoice_date}"  readonly required>
                </div>
                <input type="hidden" name="post_invoice_month" id="post_invoice_month" value="{$invoice_month1}">
            </div>
           
          <div class="row">
          <div class="col-md-12 transaction_data-mainwrap">
          <div class="dataTables_wrapper">
            <table class="table  table-striped">
                <thead class="summary_tblhead">
                  <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Count Of Policies</th>
                    <th scope="col">Policy Premium</th>
                    <th scope="col">Policy Gst</th>
                    <th scope="col">Total Policy Premium</th>
                    <th scope="col">Commission</th>
                    <th scope="col">Commission Gst</th>
                    <th scope="col">Total Commission</th>
                  </tr>
                </thead>
                <tbody>
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
                    <td>{$count_sapphire_policies}</td>
                    <td>{$sapphire_policy_premium}</td>
                    <td>{$sapphire_policy_gst}</td>
                    <td>{$sapphire_total_policy_premium}</td>
                    <td>{$sapphire_policy_commission}</td>
                    <td>{$sapphire_policy_commission_gst}</td>
                    <td>{$sapphire_policy_total_commission}</td>
                  </tr>
                  <tr>
                    <th scope="row">Sapphire Plus</th>
                    <td>{$count_sapphireplus_policies}</td>
                    <td>{$sapphire_plus_policy_premium}</td>
                    <td>{$sapphire_plus_policy_gst}</td>
                    <td>{$sapphire_plus_total_policy_premium}</td>
                    <td>{$sapphire_plus_policy_commission}</td>
                    <td>{$sapphire_plus_policy_commission_gst}</td>
                    <td>{$sapphire_plus_policy_total_commission}</td>
                  </tr>
                  <tr>
                    <th scope="row">Limitless Renewal</th>
                    <td>{$count_limitless_renewal_policies}</td>
                    <td>{$limitless_renewal_policy_premium}</td>
                    <td>{$limitless_renewal_policy_gst}</td>
                    <td>{$limitless_assist_renewal_total_policy_premium}</td>
                    <td>{$limitless_assist_renewal_policy_commission}</td>
                    <td>{$limitless_renewal_policy_commission_gst}</td>
                    <td>{$limitless_renewal_policiy_total_commission}</td>
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
                </tbody>
              </table>
            </div>
              <input type="hidden" id="invoice_id" value="{$invoice_id}" name="invoice_id">
              <button type="button" id="invoice_btn" class="btn btn-primary">Submit</button>
              </form>
              </div>
              </div>
EOD;
  echo $html;
 } 


public function post_invoice_data(){
  $post = $this->input->post();
  // echo "<pre>"; print_r($post); echo "</pre>"; die('end of line yoyo');
  $dealer_id = $this->session->userdata('user_session')['id'];
  $invoice_detail= $this->session->userdata('invoice_detail');
  $invoice_detail['invoice_no'] = $post['invoice_no'];
  $invoice_id = ($post['invoice_id']) ? $post['invoice_id'] : '';
  $exist_invoice = $this->Home_Model->getExistInvoiceDetails($post['invoice_no'],$dealer_id,$invoice_id);
  $invoice_detail['invoice_date'] = $post['invoice_date'];
  $invoice_detail['post_invoice_month'] = $post['post_invoice_month'];
  // echo "<pre>"; print_r($invoice_detail); echo "</pre>"; die('post');
  $json_data = json_encode($invoice_detail);
  $data = array(
        'invoice_no' => $post['invoice_no'] ,
        'invoice_data' => $json_data,
        'invoice_date' => $post['invoice_date'] ,
        'dealer_id' => $dealer_id,
    );
  if(!empty($invoice_id)){
          if($exist_invoice['count_invoice'] > 0){
            $result['status'] = 'false' ;
            $result['msg'] = 'Invoice No is already Exist' ;
        }else{
            $where = array('id'=>$invoice_id);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['invoice_status'] = 'pending';
            $updated = $this->Home_Model->updateTable('invoice_details',$data,$where);
            if(!empty($updated)){
                    $result['status'] = 'true' ;
                    $result['msg'] = 'Invoice is Updated' ;
                    $this->session->unset_userdata('invoice_detail');
                }else{
                    $result['status'] = 'false' ;
                    $result['msg'] = 'Something went wrong, please try again.' ;
                }
            }
  }else{
        if($exist_invoice['count_invoice'] > 0){
              $result['status'] = 'false' ;
              $result['msg'] = 'Invoice No is already Exist' ;
          }else{
              $data['created_at'] = date('Y-m-d H:i:s');
              $data['invoice_month'] = $post['post_invoice_month'];
              $insert_id = $this->Home_Model->insertIntoTable('invoice_details',$data);
              if(!empty($insert_id)){
                      $result['status'] = 'true' ;
                      $result['msg'] = 'Invoice is generated' ;
                      $this->session->unset_userdata('invoice_detail');
                  }else{
                      $result['status'] = 'false' ;
                      $result['msg'] = 'Something went wrong, please try again.' ;
                  }
          }
  }
  
        echo json_encode($result);
  // print_r($invoice_detail);
 }


 function invoice_pdf($invoice_id){
        $invoice_details = $this->db->query("SELECT ind.* ,td.* FROM invoice_details ind 
                      JOIN tvs_dealers td ON ind.dealer_id = td.id WHERE ind.id='$invoice_id'")->row_array();
        $dealer_address = strtoupper($invoice_details['add1'].','.$invoice_details['add2'].','.$invoice_details['location'].'-'.$invoice_details['pin_code'].','.$invoice_details['state']);
        $invoice_no = $invoice_details['invoice_no'];
        $invoice_month = $invoice_details['invoice_month'];
        $invoice_date = $invoice_details['invoice_date'];
        $gst_no = strtoupper($invoice_details['gst_no']);
        $dealer_name = strtoupper($invoice_details['dealer_name']);
        $pan_no = strtoupper($invoice_details['pan_no']);
        $state = strtoupper($invoice_details['state']);
        $invoice_data = json_decode($invoice_details['invoice_data'],true);
        // echo '<pre>';print_r($invoice_data);die('asasd');s
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
        $tds_amt = $total_policy_commission * 0.05 ;
        $total_commission_cgst = 0 ;
        $total_commission_sgst = 0 ;
        $total_policy_commission_igst = 0;
        $tot_comm_with_gst = 0;
        if($state=='MAHARASHTRA'){
          $total_commission_cgst = $total_policy_commission * 0.09 ;
          $total_commission_sgst = $total_policy_commission * 0.09 ;
          $total_gst = $total_commission_cgst + $total_commission_sgst ;
          $tot_comm_with_gst = $total_gst + $total_policy_commission ;
          $deposit_amt = $tot_comm_with_gst - $tds_amt ;
          // echo 'MAHARASHTRA';die($tot_comm_with_gst);
        }else{
          $total_policy_commission_igst = $total_policy_commission_gst ;
          $tot_comm_with_gst = $total_policy_commission_igst + $total_policy_commission ;
          $total_gst = $total_policy_commission_igst;
          $deposit_amt = $tot_comm_with_gst - $tds_amt ;
          // echo 'other';die($tot_comm_with_gst);
        }

          // $amt_exp = explode('.', $deposit_amt);
          // $f = new NumberFormatter("en_US", NumberFormatter::SPELLOUT);
          // $deposit_amt_in_words = ucfirst($f->format($amt_exp[0])) . ' and ' . ucfirst($f->format($amt_exp[1]));
        $deposit_amt_in_words = '';

    $this->load->library('Tcpdf/Tcpdf.php');
    ob_start();
    $this->load->library('Ciqrcode');
    $pdf = new TCPDF();
    $pdf->SetFont('helvetica', '', 7, '', 'default', true);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    // set margins
    $pdf->SetMargins(3, 8, 5);
    $pdf->SetAutoPageBreak(TRUE, 1);
    $pdf->AddPage();
    $html =<<<EOD
<style>
  .pagewrap {color: #000; font-size: 8pt; line-height:11pt; color:#000;}
  .textcenter {text-align:center;}
  .textleft {text-align:left;}
  .textright {text-align:right;}
  .font-7{font-size: 7pt; line-height:9pt;}
  .font-8{font-size: 8pt; line-height:10pt;}
  .font-9{font-size: 9pt; line-height:11pt;}
  .font-10{font-size: 10pt; line-height:12pt;}
  .font-11{font-size: 11pt; line-height:13pt;}
  .header {background-color:#fff;}
  .headertext {font-size:16pt; line-height:40pt; color:#000;}
  .border, .boxtable td {border:0.2px solid #000;}
  .sectionhead { font-size:9pt; line-height:12pt; background-color:#000; color:#fff;} 
</style>


<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
      <td colspan="2">
        <table cellpadding="0" border="0" cellspacing="0" class="header">
          <tr>
          <td colspan="2" class="textcenter headertext">Invoice</td>
        </tr>
        <tr>
          <td colspan="2" class="textcenter" style="font-size:10pt;"><b>{$dealer_name}</b></td>
        </tr>
        <tr>
          <td colspan="2" height="20"></td>
        </tr>
        <tr>
          <td width="50%" class="textleft"><b>GST No:</b> {$gst_no}</td>         
          <td width="50%" class="textright"><b>Invoice No: </b> {$invoice_no}</td>
        </tr>
        <tr>          
          <td width="50%" class="textleft"><b>PAN No:</b> {$pan_no}</td>
          <td width="50%" class="textright"><b>Invoice Date: </b> {$invoice_date}</td>
        </tr>
        <tr>          
          <td width="50%" class="textleft"><b>SAC Code:</b> 999799</td>
          <td width="50%" class="textright"><b>Invoice Month:</b> {$invoice_month}</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>        
        <tr>          
          <td class="textleft" colspan="2"><b>INDICOSMIC CAPITAL PVT. LTD. </b><br>318, 3rd floor, Summit - Business Bay, <br>Beside Magic Bricks WEH Metro Station, <br>Andheri - Kurla Rd, Andheri East, <br>Mumbai, Maharashtra 400093<br><b>GST:</b> 27AAECI3370G1ZN</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
      <td colspan="2">
      
      <table cellpadding="4" border="0" cellspacing="0" class="boxtable">
        <tr>
          <th width="12%" class="font-9 sectionhead"><b>Product Name</b></th>
          <th width="20%" class="font-9 sectionhead"><b>Count Of Policies</b></th>
          <th width="20%" class="font-9 sectionhead"><b>Policy Premium</b></th>
          <th width="16%" class="font-9 sectionhead"><b>Policy Gst</b></th>
          <th width="16%" class="font-9 sectionhead"><b>Total Policy Premium</b></th>
          <th width="16%" class="font-9 sectionhead"><b>Commission</b></th>
        </tr>       
        <tr>
          <td>Silver</td>
          <td>{$count_silver_policies}</td>
          <td>{$silver_policy_premium}</td>
          <td>{$silver_policy_gst}</td>
          <td>{$silver_total_policy_premium}</td>
          <td>{$silver_policy_commission}</td>
        </tr>
        <tr>
          <td>Gold</td>
          <td>{$count_gold_policies}</td>
          <td>{$gold_policy_premium}</td>
          <td>{$gold_policy_gst}</td>
          <td>{$gold_total_policy_premium}</td>
          <td>{$gold_policy_commission}</td>
        </tr>
        <tr>
          <td>Platinum</td>
          <td>{$count_platinum_policies}</td>
          <td>{$platinum_policy_premium}</td>
          <td>{$platinum_policy_gst}</td>
          <td>{$platinum_total_policy_premium}</td>
          <td>{$platinum_policy_commission}</td>
        </tr>
        <tr>
          <td>Sapphire</td>
          <td>{$count_sapphire_policy}</td>
          <td>{$sapphire_policy_premium}</td>
          <td>{$sapphire_policy_gst}</td>
          <td>{$sapphire_total_policy_premium}</td>
          <td>{$sapphire_policy_commission}</td>
        </tr>
        <tr>
          <td>Sapphire Plus</td>
          <td>{$count_sapphireplus_policy}</td>
          <td>{$sapphireplus_policy_premium}</td>
          <td>{$sapphireplus_policy_gst}</td>
          <td>{$sapphireplus_total_policy_premium}</td>
          <td>{$sapphireplus_policy_commission}</td>
        </tr>
        <tr>
          <td>Limitless Renewal</td>
          <td>{$count_limitless_renewal_policy}</td>
          <td>{$limitless_renewal_policy_premium}</td>
          <td>{$limitless_renewal_policy_gst}</td>
          <td>{$limitless_renewal_total_policy_premium}</td>
          <td>{$limitless_renewal_policy_commission}</td>
        </tr>
        <tr>
          <td><b>Total</b></td>
          <td><b>{$total_policy_count}</b></td>
          <td><b>{$total_policy_premium}</b></td>
          <td><b>{$total_policy_gst}</b></td>
          <td><b>{$final_policy_premium}</b></td>
          <td><b>{$total_policy_commission}</b></td>
        </tr>
      </table>  
      </td>     
  </tr>
  <tr>
      <td height="30" colspan="2"></td>
  </tr>
  <tr>
      <td width="60%"></td>
      <td width="40%">
          <table cellpadding="4" border="0" cellspacing="0" class="boxtable">
            <tr>
                <td><b>IGST:</b></td>
                <td>18%</td>
                <td>{$total_policy_commission_igst}</td>
            </tr>
            <tr>
                <td><b>CGST:</b></td>
                <td>9%</td>
                <td>{$total_commission_cgst}</td>
            </tr>
            <tr>
                <td><b>SGST:</b></td>
                <td>9%</td>
                <td>{$total_commission_sgst}</td>
            </tr>            
            <tr>
                <td colspan="2" class="textright"><b>Total:</b></td>
                <td><b>{$total_gst}</b></td>
            </tr>
        </table>
        <table cellpadding="4" border="0" cellspacing="0">
            <tr>
                <td></td>
            </tr>
        </table>
        <table cellpadding="4" border="0" cellspacing="0" class="boxtable">
            <tr>
                <td class="textright font-10"><b>Total:</b> {$tot_comm_with_gst}</td>
            </tr>
        </table>
        <table cellpadding="4" border="0" cellspacing="0">
            <tr>
                <td></td>
            </tr>
        </table>
        <table cellpadding="4" border="0" cellspacing="0" class="boxtable">
            <tr>
                <td><b>TDS:</b></td>
                <td> 5%</td>
                <td> {$tds_amt}</td>
            </tr>
        </table>
        <table cellpadding="4" border="0" cellspacing="0">
            <tr>
                <td></td>
            </tr>
        </table>
        <table cellpadding="4" border="0" cellspacing="0" class="boxtable">
            <tr>
                <td class="textright font-10"><b>Total:</b> {$deposit_amt}</td>
            </tr>
        </table>
        <table cellpadding="4" border="0" cellspacing="0">
            <tr>
                <td></td>
            </tr>
        </table>
        <table cellpadding="4" border="0" cellspacing="0" class="boxtable">
            <tr>
                <...! <td class="textright"><b>Amount in Words:</b> {$deposit_amt_in_words}</td> ...>
            </tr>
        </table>
      </td>
  </tr>
  <tr>
      <td colspan="2">
          <table cellpadding="4" border="0" cellspacing="0" class="textright">
              <tr>
                <td height="20"></td>
              </tr>
              <tr>
                  <td>For {$dealer_name}</td>
              </tr>
              <tr>
                <td height="50"></td>
              </tr>
              <tr>
                  <td>Authorised signature</td>
              </tr>
              <tr>
                <td height="30"></td>
              </tr>
          </table>
      </td>
  </tr>
 <tr>
      <td colspan="2">
          <table cellpadding="4" border="0" cellspacing="0">
              <tr>
                  <td class="textcenter"> This invoice is Computer generated, Attestation is not Required. </td>
              </tr>
              <tr>
                  <td height="30"></td>      
              </tr>
          </table>
          <table cellpadding="4" border="0" cellspacing="0">
              <tr>
                  <td class="textcenter"><b>Address:</b> {$dealer_address}</td>
              </tr>
          </table>
      </td>
  </tr>
</table>

EOD;
    $pdf->writeHtml($html);
    //"policy"-firtsandlastnamecompany-policynumber
    $pdf_to_name = "RSA-Invoice.pdf";
    ob_clean();
    $pdf->Output($pdf_to_name, 'I');

 }



    public function getPolicyPayout() {
        $post_data = $this->input->post();
        $response_data = $this->Home_Model->getPolicyPayout($post_data);
        $response_data = $response_data[0];
        echo json_encode($response_data);
    }

    function HDFCPDF($id){
$rsa_policy_data = $this->Home_Model->getPolicyById($id);
  // echo "<pre>"; print_r($rsa_policy_data); echo "</pre>"; die('end of line yoyo');
$where = array(
  'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
);
$master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
$master_policy_location = $master_policy_details['mp_localtion'];
$master_policy_address = $master_policy_details['address'];
$getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
//echo '<pre>';print_r($master_policy_details);die;
$rsa_name = $getDealerInfo['name'];
$rsa_logo = base_url($getDealerInfo['logo']);
$rsa_address = $getDealerInfo['address'];
$rsa_email = $getDealerInfo['email'];
$customer_care_no = $getDealerInfo['toll_free_no'];
$dealer_id = isset($rsa_policy_data['user_id']) ? $rsa_policy_data['user_id'] : '';
$certificate_no = isset($rsa_policy_data['sold_policy_no']) ? $rsa_policy_data['sold_policy_no'] : '--';
$vehicle_registration_no = isset($rsa_policy_data['vehicle_registration_no']) ? $rsa_policy_data['vehicle_registration_no'] : '--';
$plan_name = isset($rsa_policy_data['plan_name']) ? strtoupper($rsa_policy_data['plan_name']) : '--';
$plan_id = isset($rsa_policy_data['plan_id']) ? $rsa_policy_data['plan_id'] : '--';
$where = array(
  'id'=>$plan_id
);
$plan_detalis = $this->Home_Model->getDataFromTable('tvs_plans',$where);
//echo '<pre>'; print_r($plan_detalis);die();
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
$created_date = date('d/m/Y h:i:s',strtotime($created_date));

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
$sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '0000-00-00 00:00:00';
$sold_policy_effective_date = date('d/m/Y h:i:s',strtotime($sold_policy_effective_date));

$sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date']: '--';
$sold_policy_end_date = date('d/m/Y h:i:s',strtotime($sold_policy_end_date));

$pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '0000-00-00 00:00:00';
$pa_sold_policy_effective_date = date('d/m/Y h:i:s',strtotime($pa_sold_policy_effective_date));

$pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date']: '--';
$pa_sold_policy_end_date = date('d/m/Y h:i:s',strtotime($pa_sold_policy_end_date));

$model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';

$this->load->library('Tcpdf/Tcpdf.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('HDFC ERGO GPA Policy');
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
              <td><b>Plan Amount(Rs.)</b></td>
              <td>{$plan_amount}</td>
            </tr>
            <tr>
              <td><b>Tax Amount(18% IGST in Rs.)</b></td>
              <td>{$gst_amount}</td>
            </tr>
            <tr>
              <td><b>Total Amount (Rs.)</b></td>
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
<style>

  .sectionhead {color:#fff; background-color:#d82819; font-size:7pt; line-height:9pt; font-weight:bold;}
  .footer { color:#666;}

  .pagewrap {color: #000; font-size: 7pt; line-height:9pt; color:#000;}
  .textcenter {text-align:center;}
  .textleft {text-align:left;}
  .textright {text-align:right;}
  .font-6{font-size: 6pt; line-height:8pt;}
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

<table width="100%" cellpadding="0" border="0" class="pagewrap">  
    <tr>
        <td>
          <table cellpadding="0" border="0" cellspacing="0">
            <tr>
              <td width="70%" class="font-12 line-height-18"><br><br><b>HDFC ERGO General Insurance Company Limited</b></td>
              <td width="30%" class="textright"><img src="assets/images/hdfc-ergo-logo.png" height="70"></td>                
            </tr>
          </table>
          <table cellpadding="0" border="0" cellspacing="0">
            <tr>
              <td width="32%" class="font-8"><b>Master Policy No.</b>{$master_policy_no} <br><b>Certificate Number: </b>{$certificate_no}</td>
              <td width="36%" class="textcenter font-10"><b>Certificate of Insurance <br>Group Personal Accident Insurance</b></td>    
              <td width="32%">
              </td>            
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
            <td width="40%"><b>Proposer Name: </b>{$full_name_of_insured} </td> 
            <td width="28%"><b>PAN No.:</b> </td>
            <td width="32%"><b>Premium frequency:</b> Annual </td>            
          </tr> 
        </table>
        <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
            <tr>
              <td width="40%"><b>Corr. Address/ Place of Supply:</b> </td>
              <td width="60%"><b>Permanent Address:</b> {$addr1}, {$addr1} </td>              
            </tr>
        </table>
        <table cellpadding="0" border="0" cellspacing="0">
            <tr>
                <td></td>
            </tr> 
        </table>
        <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
          <tr>
            <td width="25%"><b>Mobile:</b> {$mobile_no}</td>
            <td width="30%"><b>E Mail:</b> {$email}</td>
            <td width="25%"><b>Territorial Limits:</b> India Only</td>
            <td width="20%"><b>Policy Type:</b> Individual</td>
          </tr>       
        </table>
        <table cellpadding="0" border="0" cellspacing="0">
            <tr>
                <td></td>
            </tr> 
        </table>
        <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
          <tr>
            <td width="20%"><b>Period of Insurance</b> </td>
            <td width="25%">From Date & Time: {$pa_sold_policy_effective_date} </td>
            <td width="25%">To Date & Time: {$pa_sold_policy_end_date} </td>
            <td width="30%"><b>Policy Issuance Date:</b> {$created_date}</td>
          </tr>       
        </table>
        <table cellpadding="0" border="0" cellspacing="0">
            <tr>
                <td></td>
            </tr> 
        </table>
        <table cellpadding="2" border="0" cellspacing="0" class="boxtable textcenter">
            <tr style="background-color:#ebecec;">
                <td width="70%"><b>Insured’s Name</b></td>
                <td width="15%"><b>Date of Birth</b></td>
                <td width="15%"><b>Gender</b></td>
            </tr> 
            <tr>
              <td>{$full_name_of_insured}</td>
              <td>{$dob}</td>
              <td>{$gender}</td>
            </tr>
        </table>
        <table cellpadding="0" border="0" cellspacing="0">
            <tr>
                <td></td>
            </tr> 
        </table>
        <table cellpadding="2" border="0" cellspacing="0">
          <tr>
            <td class="sectionhead textcenter">Nominee Details</td>
          </tr>
        </table>
        <table cellpadding="2" border="0" cellspacing="0" class="boxtable textcenter">
          <tr style="background-color:#ebecec;">
            <td width="70%"><b>Name of Nominee</b></td>
            <td width="15%"><b>Relation</b></td>
            <td width="15%"><b>Benefit</b></td>
          </tr>
          <tr>
            <td>{$nominee_name}</td>
            <td>{$nominee_relation}</td>
            <td>100 %</td>
          </tr>
        </table>
        <table cellpadding="0" border="0" cellspacing="0">
            <tr>
                <td></td>
            </tr> 
        </table>
        <table cellpadding="2" border="0" cellspacing="0">
          <tr>
            <td class="sectionhead textcenter">Coverage Details</td>
          </tr>
        </table>
        <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
          <tr style="background-color:#ebecec;">
            <td width="85%"><b>Coverage</b></td>
            <td width="15%" class="textcenter"><b>Sum Insured (Rs.)</b></td>
          </tr>
          <tr>
            <td>Accidental Death</td>
            <td class="textcenter">{$sum_insured}</td>
          </tr>
          <tr>
            <td>Permanent Disablement (Table B)</td>
            <td class="textcenter">{$sum_insured}</td>
          </tr>
        </table>    
        <table cellpadding="0" border="0" cellspacing="0">
            <tr>
                <td></td>
            </tr> 
        </table>  
        <table cellpadding="0" border="0" cellspacing="0">
            <tr>
                <td></td>
            </tr> 
        </table>     
        <table cellpadding="1" border="0" cellspacing="0">
          <tr>
            <td width="2%" class="sectionhead"></td>
            <td width="30%" class="sectionhead">Special Conditions, Warranties & Exclusions</td>
            <td width="68%"></td>
          </tr>
        </table>
        <table cellpadding="2" border="0" cellspacing="0" class="boxtable ">
          <tr>
            <td>1. Insurance cover will start only on receipt of complete premium by HDFC ERGO General Insurance Company Limited. <br>2.  Insurance cover is subject to the terms and conditions mentioned in the policy wordings provided to you with this certificate. <br>3.  The above covers would not be applicable for persons occupied in underground mines, explosives and electrical installations on high tension lines <br>4.  Major exclusions: Intentional self-injury, suicide or attempted suicide whilst under the influence of intoxicating liquor or drugs, Any loss arising from an act made in breach of law with or without criminal intent. <br>5.  The following risk / perils have been explicitly excluded under the policy: •Nuclear energy risk, •Professional activities of military personnel, •Offshore activities, •Terrorism due to nuclear / chemical / biological risk, •Adventure sports, •Epidemic / Pandemic, •War, •Organized racing. Please refer to the policy wording for detailed list of coverage & exclusions. <br>6.  The personal accident coverage is applicable only to the owner of the vehicle for which Road Side Assistance service has been provided by the Indicosmic Capital Private Limited <br>7.The said personal accident cover is active only whilst the owner is driving or travelling in the said vehicle including mounting into/dismounting from the said vehicle.<br>8. Death or permanent total disability claims due to any other incidence would not be covered.</td>
          </tr>
        </table>
        <table cellpadding="0" border="0" cellspacing="0">
            <tr>
                <td></td>
            </tr> 
        </table> 
        <table cellpadding="2" border="0" cellspacing="0">
          <tr>
            <td width="2%" class="sectionhead"></td>
            <td width="15%" class="sectionhead">For Claim Services</td>
            <td width="83%"></td>
          </tr>
        </table>
        <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
          <tr>
            <td width="30%"><b>Phone:</b> 1800 2 700 700</td>
            <td width="30%"><b>Fax No.:</b> +91 22 66383699</td>
            <td width="40%"><b>E-mail:</b> care@hdfcergo.com</td>
          </tr>
          <tr>
            <td colspan="3"><b>Processing Centre</b> 6th Floor, Leela Business Park, Andheri-Kurla Road, Andheri East, Mumbai 400059.</td>
          </tr>
          <tr>
            <td colspan="3">For any other query call toll-free  022 - 6234 6234 (Accessible from India only) or email us at care@hdfcergo.com or log on to <b>www.hdfcergo.com</b></td>
          </tr>
          <tr>
            <td colspan="3">We shall not be liable to make any payment under this policy in connection with or in respect of any injury directly or indirectly caused by or contributed to by nuclear weapons/materials or contributed to by or arising from ionising radiation or contamination by radioactivity by any nuclear fuel or from any nuclear waste or from the combustion of nuclear fuel.<br>If the premium is not realised the policy shall be void from inception. Subject otherwise to the terms, exclusions and conditions of this policy. Income proof for availing the compensation at the time of claim is mandatory. Income proof shall mean the previous year’s returns filed with the Income Tax Department.<br>Consolidated stamp duty for this Insurance Policy is paid by Demand Draft, vide Receipt/Challan no. 654109201819 dated 03/05/2018 as prescribed in Government Notification Revenue and Forest Department No. Mudrank 2004/4125/CR 690/M-1, dated 31/12/2004.Goods & Services Tax Registration No: 27AABCL5045N1Z8. Goods and Services Tax for this invoice is not payable under reverse charge basis.</td>
          </tr>
        </table>
        <table cellpadding="2" border="0" cellspacing="0">
            <tr>
                <td>The Policy wording attached herewith includes all the standard coverage offered by the Company to its customers. Your entitlement for coverage/benefits shall be restricted to the coverage/benefits as mentioned in this policy schedule. For any clarification please call our toll free number</td>
            </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td height="20"></td>
    </tr>
     <tr>
      <td>  
        <table cellpadding="0" border="0" cellspacing="0">  
          <tr>
            <td></td>
          </tr>
          <tr>
            <td><img src="assets/images/hdfc_sign-img.jpg" height="40"></td>  
          </tr>
          <tr>
            <td><b>For HDFC ERGO General Insurance Company Limited</b></td>
          </tr>
          <tr>
            <td><b>(Authorized Signatory)</b></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td height="70"></td>
    </tr>
    <tr>
      <td>
        <table cellpadding="0" border="0" cellspacing="0" class="footer font-6">
          <tr>
            <td>HDFC ERGO General Insurance Company Limited (Formerly HDFC General Insurance Limited). CIN: U66030MH2007PLC177117. Registered & Corporate Office: 1st Floor, HDFC House, 165-166 Backbay Reclamation, H. T. Parekh Marg, Churchgate, Mumbai - 400020. Customer Service Address:D-301, 3rd Floor, Eastern Business District (Magnet Mall), LBS Marg, Bhandup (West), Mumbai - 400 078. Telephone No: 022 6638 3600 Customer Service No: 022 - 6234 6234 / 0120 - 6234 6234 | care@hdfcergo.com | www.hdfcergo.com. Trade Logo displayed above belongs to HDFC Ltd and ERGO International AG and used by the Company under license I IRDAI Reg No. 146. Group Personal Accident Insurance UIN: HDFPAGP03006V010203</td>
          </tr>
        </table>
      </td>
    </tr>      
</table> 
<!--<br pagebreak="true" />-->

EOD;

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('HDFC-ERGO-GPA-Policy.pdf', 'I');
}

function DownloadKotakLitePdf($id){ 
$this->load->library('Tcpdf/Tcpdf.php');
$this->load->library('Ciqrcode');
ob_start();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Kotak GPA Policy Certificate');
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

$rsa_policy_data = $this->Home_Model->getPolicyById($id);
$where = array(
  'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
);
$master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
$master_policy_location = $master_policy_details['mp_localtion'];
$master_policy_address = $master_policy_details['address'];
$getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
$getICInfo = $this->Home_Model->getICInfo($rsa_policy_data['rsa_ic_id']);
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
$plan_detalis = $this->Home_Model->getDataFromTable('tvs_plans',$where);
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
$dob = isset($rsa_policy_data['dob']) ? $rsa_policy_data['dob']: '--';
$dob = date("d-M-Y", strtotime($dob) );
$addr1 = isset($rsa_policy_data['addr1']) ? strtoupper($rsa_policy_data['addr1']) : '--';
$addr2 = isset($rsa_policy_data['addr2']) ? strtoupper(mb_strimwidth($rsa_policy_data['addr2'], 0, 20, "...")) : '--';
$state_name = isset($rsa_policy_data['state_name']) ? strtoupper($rsa_policy_data['state_name']) : '--';
$city_name = isset($rsa_policy_data['city_name']) ? strtoupper($rsa_policy_data['city_name']) : '--';
$sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '--';
$sold_policy_issued_date = isset($rsa_policy_data['created_date']) ? $rsa_policy_data['created_date'] : '--';
$sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date']: '--';
$pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '0000-00-00 00:00:00';
// echo $pa_sold_policy_effective_date;die(' pa_sold_policy_effective_date');
// $pa_sold_policy_effective_date = '2019-09-01 23:59:59';
$imp_note ='';
$imp_note1='';
if( strtotime($pa_sold_policy_effective_date) >= strtotime('2019-09-05 21:59:59') ){
  $imp_note1 = '<tr>
                <td class="textcenter">3</td>
                <td>Accidental Medical Expenses Extension</td>
                <td class="textright">upto INR 25,000/- </td>
              </tr>';
    $imp_note = '
      <table cellpadding="0" border="0" cellspacing="0">        
          <tr>
            <td class="line-height-15"><b>Important Conditions:</b></td>
          </tr>
        </table>
        
        <table cellpadding="1" border="0" cellspacing="0" class="boxtable font-7">
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
$pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '0000-00-00 00:00:00';

$model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';


$params['data'] = "Name: '" . $full_name_of_insured . "' , POLICY NO: '" . $certificate_no . "', FROM: '" . $pa_sold_policy_effective_date . "' , TO: '" . $pa_sold_policy_end_date . "'";
$params['level'] = 'H';
$params['size'] = 5;
$params['savename'] = FCPATH . 'assets/images/qr_image/'.trim($certificate_no).'.png';
$this->ciqrcode->generate($params);
$qr_code_image_url = '<img src="assets/images/qr_image/'.trim($certificate_no).'.png" width="60px" />';
// <img src="assets/images/revised/qr-code.png" width="60">
// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);
        $html = <<<EOD
        <style>
  .pagewrap {color: #000; font-size: 8pt; line-height:9pt; color:#000;}
  .textcenter {text-align:center;}
  .textleft {text-align:left;}
  .textright {text-align:right;}
  .font-6{font-size: 5.5pt; line-height:7pt;}
  .font-7{font-size: 7pt; line-height:8.5pt;}
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
  .headertext {font-size:14pt; line-height:40pt; color:#fff;}
  .border, .boxtable td {border:0.2px solid #000;}
  .bluetable td {border:0.2px solid #16365c;}
  .boxtable td.heading {background-color:#d9d9d9; color:#000; font-weight:bold;}
  .tb-heading {background-color:#16365c; color:#fff;text-transform:uppercase; font-weight:bold;}
  .sectionhead { font-size:7pt; line-height:8pt; background-color:#000; color:#fff;}
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
                <td>{$sold_policy_issued_date}</td>
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
          <td><b>Plan Amount(Rs.)</b></td>
          <td>{$plan_amount}</td>
        </tr>
        <tr>
          <td><b>Tax Amount(18% IGST in Rs.)</b></td>
          <td>{$gst_amount}</td>
        </tr>
        <tr>
          <td><b>Total Amount (Rs.)</b></td>
          <td>{$total_amount}</td>
        </tr>
      </table>
      </td>     
  </tr>
</table>
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
                <td><b>FOR RENEWALS: Visit www.kotakgeneralinsurance.com <br>Call 1800 266 4545</b></td>
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
      
      <table cellpadding="0" border="0" cellspacing="0" class="font-7">
        <tr>
          <td>Kotak Group Accident Protect Policy No. $master_policy_no dated $mp_start_date has been issued at {$master_policy_location} by Kotak Mahindra General Insurance Company Limited to the Policyholder, <b>Indicosmic Capital Pvt. Ltd.</b> as specified 
          in the Policy Schedule and is governed by, and subject to the terms, condition and exclusions therein contained 
          or otherwise expressed in the said policy, but not exceeding the Sum Insured as specified in the Policy 
          Schedule to the said policy.This certificate issued under the signature of the authorised signatory of the 
          Company represents the availability of benefits to the Insured person / persons named below,Customers of 
          <b>Indicosmic Capital Pvt. Ltd.</b> subject to the terms, conditions and exclusions contained or otherwise 
          expressed in the said Policy, but not exceeding the Sum Insured as specified below.For the purpose of this 
          document, we consider <b>Indicosmic Capital Pvt. Ltd.</b> as the policyholder and its Customers as the 
          Insured.</td>
        </tr>
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>DETAILS OF THE INSURED PERSON(S) UNDER THE POLICY</b></td>
        </tr>
      </table>
      <table cellpadding="0" border="0" cellspacing="0">
        <tr>
          <td width="49.5%">
            <table cellpadding="2" border="0" cellspacing="0" class="boxtable font-7">
              <tr>
                <td width="44%">Certificate No.</td>
                <td width="56%">{$certificate_no}</td>
              </tr>
              <tr>
                <td>Policy Type</td>
                <td>New</td>
              </tr>
              <tr>
                <td>Previous Policy No.</td>
                <td></td>
              </tr>
              <tr>
                <td>Issued At</td>
                <td>{$state_name}</td>
              </tr>
              <tr>
                <td>Name of the Insured</td>
                <td>{$full_name_of_insured}</td>
              </tr>
              <tr>
                <td>GSTIN</td>
                <td></td>
              </tr>
              <tr>
                <td>Policy Issuing Office</td>
                <td>{$master_policy_location}</td>
              </tr>
              <tr>
                <td>Mailing Address of the Insured</td>
                <td>{$addr1},{$addr2}</td>
              </tr>
              <tr>
                <td>Mobile no. of the Insured:</td>
                <td>{$mobile_no}</td>
              </tr>
              <tr>
                <td>Email Address:</td>
                <td>{$email}</td>
              </tr>
              <tr>
                <td>Policy Period - From</td>
                <td>{$pa_sold_policy_effective_date}</td>
              </tr>
              <tr>
                <td>Policy Period - To midnight of</td>
                <td>{$pa_sold_policy_end_date}</td>
              </tr>
              <tr>
                <td>Installment Option</td>
                <td>NO</td>
              </tr>
              <tr>
                <td class="sectionhead" colspan="2">INTERMEDIARY DETAILS</td>               
              </tr>
              <tr>
                <td>Intermediary Code</td>
                <td>3204180000</td>
              </tr>
              <tr>
                <td>Intermediary Name</td>
                <td>Global India Insurance Brokers Pvt Ltd.</td>
              </tr>
              <tr>
                <td>Intermediary E-Mail Id</td>
                <td>info@giib.co.in</td>
              </tr>
              <tr>
                <td>Intermediary Landline No.</td>
                <td>022-26820489</td>
              </tr>
            </table>
          </td>
          <td width="1%"></td>
          <td width="49.5%">
            <table cellpadding="2" border="0" cellspacing="0" class="boxtable font-7">
              <tr>
                <td width="56%">Membership ID/ Employee Number Account Number pertaining to Credit(#)</td>
                <td width="44%">MD626AG79K2E03171 Financer Name: Indicosmic Capital Pvt. Ltd.</td>
              </tr>
              <tr>
                <td>Credit Tenure(#)</td>
                <td></td>
              </tr>
              <tr>
                <td>Name of the Insured Person</td>
                <td>{$full_name_of_insured}</td>
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
                <td>Relation with the Insured Person</td>
                <td>self</td>
              </tr>
              <tr>
                <td>Date of Birth DD/MM/YYYY</td>
                <td>{$dob}</td>
              </tr>
              <tr>
                <td>Gender</td>
                <td>{$gender}</td>
              </tr>
              <tr>
                <td>Category</td>
                <td></td>
              </tr>
              <tr>
                <td>Credit Amount <br>Outstanding Credit Amount (#)</td>
                <td></td>
              </tr>
              <tr>
                <td>Sum Insured</td>
                <td>{$sum_insured}</td>
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
                <td>{$nominee_name}</td>
              </tr>
              <tr>
                <td>Nominee Relationship with the Insured Person</td>
                <td>{$nominee_relation}</td>
              </tr>
              <tr>
                <td>Nominee Age</td>
                <td>{$nominee_age}</td>
              </tr>
              <tr>
                <td>Appointee Details in case Nominee is Minor</td>
                <td>{$appointee_details}</td>
              </tr>
              
            </table>
          </td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="line-height-15">(#) Applicable only to Credit linked policies</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>COVERAGE DETAILS:</b></td>
        </tr>       
      </table>
      <table cellpadding="1" border="0" cellspacing="0" class="boxtable font-7">
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
          <td class="textright">{$sum_insured}</td>
        </tr>
        <tr>
          <td class="textcenter">2</td>
          <td>Permanent Total Disablement (PTD) </td>
          <td class="textright">{$sum_insured}</td>
        </tr>
        {$imp_note1}           
      </table>
      {$imp_note}
      <table cellpadding="2" border="0" cellspacing="0">
       
        <tr>
          <td class="sectionhead"><b>PERMANENT EXCLUSION</b></td>
        </tr>
      </table>
      <table cellpadding="0" border="0" cellspacing="0" class="font-7">        
        <tr>
          <td>1. Arising or resulting from the Insured Person committing any breach of the law with criminal intent. <br>2. Any Injury or Illness directly or indirectly caused by or arising from or attributable to war or war like perils. <br>3. Any Illness or Injury directly or indirectly caused by or contributed to by nuclear weapons/material usage, consumption or abuse of substances intoxicants, hallucinogens, alcohol and/or drugs.self-destruction or self inflicted injury, attempted suicide or suicide. <br>4. Any consequential or indirect loss or expenses arising out of or related to any event giving rise to a Claim under the Policy.
            <br><b>For complete details please refer to the Policy wordings available with the Group Master Policyholder. Alternatively, the same can be downloaded from our website www.kotakgeneralinsurance.com</b> <br>5. Premium for your personal accident insurance has been paid by indicosmic capital Pvt Ltd.
          </td>
        </tr>
      </table>  
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>DISCLAIMER</b></td>
        </tr>
      </table>
      <table cellpadding="0" border="0" cellspacing="0" class="font-7">        
        <tr>
          <td>This Certificate of Insurance shall be read together with the Policy Schedule and Policy Wording and regarded as one contract and any word or expression to which a specific meaning has been assigned in any part of the policy or this schedule shall bear the same meaning wherever it may appear.</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>IN THE EVENT OF CLAIM</b></td>
        </tr>
      </table>
      <table cellpadding="0" border="0" cellspacing="0" class="font-7">
        <tr>
          <td width="60%"><b>Contact Us at:</b> <br>Timings : 8 AM to 8 PM Toll Free number: 1800 266 4545 or may write an e-mail at <u>care@kotak.com</u></td>
          <td width="40%" rowspan="4" class="textright">For & On Behalf of <br> Kotak Mahindra General Insurance Company Ltd. <br><img src="assets/images/mpdf/eswar-sign-final.jpg" height="50"> <br>Authorized Signatory</td>
        </tr>

        <tr>
          <td><b>Please send the relevant documents to:</b> <br>Kotak Mahindra General Insurance Company Ltd. <br>8th Floor, Zone IV, Kotak Infiniti, Bldg. 21,Infinity IT Park, <br>Off WEH, Gen. AK Vaidya Marg, Dindoshi, Malad (E), <br>Mumbai - 400097. India</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="textcenter sectionhead"><b>Kotak Group Accident Protect UIN: IRDAI/HLT/KMGI/P-H(G)/V.I/61/2016-17</b></td>
        </tr>
      </table>    
      </td>
  </tr> 
  <tr>
      <td height="30"></td>
  </tr>
  <tr>
      <td>
        <table cellpadding="4" border="0" cellspacing="0" class="footer font-7" style="line-height:7pt;">
        <tr>
          <td>Kotak Mahindra General Insurance Company Ltd. (Formerly Kotak Mahindra General Insurance Ltd. <br>CIN: U66000MH2014PLC260291 <b>Registered Office:</b> 27 BKC, C 27, G Block, Bandra Kurla Complex, Bandra East, Mumbai - 400051. Maharashtra, India. <br><b>Office:</b> 8th Floor, Kotak Infiniti, Bldg. 21, Infinity IT Park, Off WEH, Gen. AK Vaidya Marg, Dindoshi, Malad(E), Mumbai - 400097.India. <br>Toll Free: 1800 266 4545 Email: care@kotak.com Website: www.kotakgeneralinsurance.com IRDAI Reg. No. 152</td>
        </tr>
      </table>
      </td>
  </tr>
</table>

EOD;
        $pdf->AddPage();
        $pdf->writeHTML($html);
        ob_clean();
        $pdf_to_name = "RSA-Kotak- .'$certificate_no'.pdf";
        $pdf->Output($pdf_to_name, 'I');

}

function OpenRSAKotakPDF($id){
$this->load->library('Tcpdf/Tcpdf.php');
$this->load->library('Ciqrcode');
ob_start();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Kotak GPA Policy Certificate');
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

//check rsa_ic_id is 1 or 11 

$rsa_policy_data = $this->Home_Model->getOpnRsaKotakPolicy($id);
 // echo '<pre>'; print_r($rsa_policy_data);die('hello');
  $getICInfo = $this->Home_Model->getICInfo($rsa_policy_data['rsa_ic_id']);
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
  
  $plan_detalis = $this->Home_Model->getOpnrsaPlan($plan_id);
   // echo '<pre>'; print_r($plan_detalis);die();
  $km_covered = isset($plan_detalis['km_covered'])?$plan_detalis['km_covered']:'50';
  // die($km_covered);
  $plan_amount  = round($plan_detalis['policy_premium']);
  $gst_amount  = round($plan_detalis['policy_premium_with_gst']);
  $total_amount =  ($plan_amount + $gst_amount);
  $engine_no = isset($rsa_policy_data['engine_no']) ? strtoupper($rsa_policy_data['engine_no']) : '--';
  $chassis_no = isset($rsa_policy_data['chassis_no']) ? strtoupper($rsa_policy_data['chassis_no']) : '--';
  $fname = isset($rsa_policy_data['fname']) ? strtoupper($rsa_policy_data['fname']) : '--';
  $lname = isset($rsa_policy_data['lname']) ? strtoupper($rsa_policy_data['lname']) : '--';
  $full_name_of_insured = $fname.' '.$lname;
  $nominee_name = isset($rsa_policy_data['nominee_full_name']) ? strtoupper($rsa_policy_data['nominee_full_name']) : '--';
  $nominee_relation = isset($rsa_policy_data['nominee_relation']) ? strtoupper($rsa_policy_data['nominee_relation']) : '--';
  $nominee_age = isset($rsa_policy_data['nominee_age']) ? strtoupper($rsa_policy_data['nominee_age']) : '--';

  $appointee_name = isset($rsa_policy_data['appointee_full_name']) ? strtoupper($rsa_policy_data['appointee_full_name']) : ' ';
  $appointee_relation = isset($rsa_policy_data['appointee_relation']) ? strtoupper($rsa_policy_data['appointee_relation']) : ' ';
  $appointee_age = isset($rsa_policy_data['appointee_age']) ? $rsa_policy_data['appointee_age'] : ' ';
  if(!empty($appointee_age)){
      $appointee_details = '';
    }else{
      $appointee_details = 'Name:' .$appointee_name.' Relationship: '.$appointee_relation .'Age: '.$appointee_age;
    }

  //master policy detils
  $master_policy_no = isset($rsa_policy_data['master_policy_no']) ? strtoupper($rsa_policy_data['master_policy_no']) : ' ';
  $where = array(
    'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
  );
  $master_policy_details = $this->Home_Model->getRowDataFromTable('opn_insurance_companies',$where);
  $master_policy_address = $master_policy_details['address'];
  $master_policy_email = $master_policy_details['email'];
  $master_policy_location = $master_policy_details['mp_location'];
  $master_policy_toll_free_no = $master_policy_details['toll_free_no'];
  $sum_insured = isset($master_policy_details['sum_insured'])?$master_policy_details['sum_insured']:0;

  $mp_start_date = isset($rsa_policy_data['mp_start_date']) ? $rsa_policy_data['mp_start_date'] : ' ';
  $date = new DateTime($mp_start_date);
  $mp_start_date = $date->format('d-M-Y').' 12:00:01 AM';
  $mp_end_date = isset($rsa_policy_data['mp_end_date']) ? $rsa_policy_data['mp_end_date'] : ' ';
  $date = new DateTime($mp_end_date);
  $mp_end_date = $date->format('d-M-Y').' 11:59:59 PM';
  $email = isset($rsa_policy_data['email']) ? strtoupper($rsa_policy_data['email']) : '--';
  $mobile_no = isset($rsa_policy_data['mobile_no']) ? $rsa_policy_data['mobile_no'] : '--';
  $gender = isset($rsa_policy_data['gender']) ? strtoupper($rsa_policy_data['gender']) : '--';
  $dob = isset($rsa_policy_data['dob']) ? $rsa_policy_data['dob'] : '--';
  $addr1 = isset($rsa_policy_data['addr1']) ? strtoupper($rsa_policy_data['addr1']) : '--';
  $addr2 = isset($rsa_policy_data['addr2']) ? strtoupper($rsa_policy_data['addr2']) : '--';
  $pin_code = isset($rsa_policy_data['pin_code']) ? $rsa_policy_data['pin_code'] : ' ';
  $state_name = isset($rsa_policy_data['state_name']) ? strtoupper($rsa_policy_data['state_name']) : '--';
  $city_name = isset($rsa_policy_data['city_name']) ? strtoupper($rsa_policy_data['city_name']) : '--';
  $full_address = $addr1.' '.$addr2.' '.$city_name.'-'.$pin_code.' '.$state_name;
  $issuing_office = 'DO 140600, Jyoti Chambers, 3rd Floor, J V Road, Khot lane, Ghatkopar West, Mumbai 400 086';
  $created_date = isset($rsa_policy_data['created_date']) ? $rsa_policy_data['created_date'] : '--';
  $created_date_ar = explode(' ',$created_date);
  $created_time = $created_date_ar[1];
  $sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '--';
  $sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date'] : '--';
  $pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '--';
  $date = new DateTime($pa_sold_policy_effective_date);
  $pa_sold_policy_effective_date = $date->format('d-M-Y').' '.$created_time;
  $pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date']: '--';
  $date = new DateTime($pa_sold_policy_end_date);
  $pa_sold_policy_end_date = $date->format('d-M-Y').' 11:59:59';
    $period_of_insurance = 'From 12 Hrs:00 Mins on'.$pa_sold_policy_effective_date.' To midnight of '.$pa_sold_policy_end_date;
  $model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';
  $make_name = isset($rsa_policy_data['make_name']) ? strtoupper($rsa_policy_data['make_name']) : '--';
// $pa_sold_policy_effective_date = '2019-09-01 23:59:59';
$imp_note ='';
$imp_note1='';
if( strtotime($pa_sold_policy_effective_date) >= strtotime('2019-09-05 21:59:59') ){
  $imp_note1 = '<tr>
                <td class="textcenter">3</td>
                <td>Accidental Medical Expenses Extension</td>
                <td class="textright">upto INR 25,000/- </td>
              </tr>';
    $imp_note = '
      <table cellpadding="0" border="0" cellspacing="0">        
          <tr>
            <td class="line-height-15"><b>Important Conditions:</b></td>
          </tr>
        </table>
        
        <table cellpadding="1" border="0" cellspacing="0" class="boxtable font-7">
          <tr>
            <td width="6%" class="textcenter">Sr. No </td>
            <td width="94%" class="textcenter">Clause Description</td>
          </tr>
          <tr>
            <td class="textcenter">1</td>
            <td>8.22-Assignment</td>
          </tr>
          <tr>
            <td class="textcenter">2</td>
            <td>The personal accident coverage is applicable only to the owner of the vehicle for which Road Side Assistance service has been provided by the Indicosmic Capital Pvt Ltd.</td>
          </tr>
          <tr>
            <td class="textcenter">3</td>
            <td>The said personal accident cover is active only whilst the owner is driving or travelling in the said vehicle including mounting into/dismounting from the said vehicle.</td>
          </tr>
          <tr>
            <td class="textcenter">4</td>
            <td>Death or permanent total disability claims due to any other incidence would not be covered</td>
          </tr>
          <tr>
            <td class="textcenter">5</td>
            <td>The policy is valid for 365 days from the policy risk start date</td>
          </tr>
          <tr>
            <td class="textcenter">6</td>
            <td>Accidental Medical Expenses Extension We will pay the reimburse the Medical Expenses upto INR 25,000/- incurred by the Insured Person provided that such treatment is following the Accident and If we have admitted a Claim for Accidental Death or Permanent Total Disablement</td>
          </tr>
        </table>';
}
$pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '0000-00-00 00:00:00';
$date = new DateTime($pa_sold_policy_effective_date);
$pa_sold_policy_effective_date = $date->format('d-M-Y');
$created_time = date("H:i:s",strtotime($created_date));
$pa_sold_policy_effective_date = $pa_sold_policy_effective_date.' '.$created_time;
$date = new DateTime($pa_sold_policy_end_date);
$pa_sold_policy_end_date = $date->format('d-M-Y H:i:s');
$model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';


$params['data'] = "Name: '" . $full_name_of_insured . "' , POLICY NO: '" . $certificate_no . "', FROM: '" . $pa_sold_policy_effective_date . "' , TO: '" . $pa_sold_policy_end_date . "'";
$params['level'] = 'H';
$params['size'] = 5;
$params['savename'] = FCPATH . 'assets/images/qr_image/'.trim($certificate_no).'.png';
$this->ciqrcode->generate($params);
$qr_code_image_url = '<img src="assets/images/qr_image/'.trim($certificate_no).'.png" width="60px" />';
// <img src="assets/images/revised/qr-code.png" width="60">
// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);
        $html = <<<EOD
        <style>
  .pagewrap {color: #000; font-size: 8pt; line-height:9pt; color:#000;}
  .textcenter {text-align:center;}
  .textleft {text-align:left;}
  .textright {text-align:right;}
  .font-6{font-size: 5.5pt; line-height:7pt;}
  .font-7{font-size: 7pt; line-height:8.5pt;}
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
  .headertext {font-size:14pt; line-height:40pt; color:#fff;}
  .border, .boxtable td {border:0.2px solid #000;}
  .bluetable td {border:0.2px solid #16365c;}
  .boxtable td.heading {background-color:#d9d9d9; color:#000; font-weight:bold;}
  .tb-heading {background-color:#16365c; color:#fff;text-transform:uppercase; font-weight:bold;}
  .sectionhead { font-size:7pt; line-height:8pt; background-color:#000; color:#fff;}
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
          <td><b>Plan Amount(Rs.)</b></td>
          <td>{$plan_amount}</td>
        </tr>
        <tr>
          <td><b>Tax Amount(18% IGST in Rs.)</b></td>
          <td>{$gst_amount}</td>
        </tr>
        <tr>
          <td><b>Total Amount (Rs.)</b></td>
          <td>{$total_amount}</td>
        </tr>
      </table>
      </td>     
  </tr>
</table>
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
                <td><b>FOR RENEWALS: Visit www.kotakgeneralinsurance.com <br>Call 1800 266 4545</b></td>
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
      
      <table cellpadding="0" border="0" cellspacing="0" class="font-7">
        <tr>
          <td>Kotak Group Accident Protect Policy No. $master_policy_no dated $mp_start_date has been issued at {$master_policy_location} by Kotak Mahindra General Insurance Company Limited to the Policyholder, <b>Indicosmic Capital Pvt. Ltd.</b> as specified 
          in the Policy Schedule and is governed by, and subject to the terms, condition and exclusions therein contained 
          or otherwise expressed in the said policy, but not exceeding the Sum Insured as specified in the Policy 
          Schedule to the said policy.This certificate issued under the signature of the authorised signatory of the 
          Company represents the availability of benefits to the Insured person / persons named below,Customers of 
          <b>Indicosmic Capital Pvt. Ltd.</b> subject to the terms, conditions and exclusions contained or otherwise 
          expressed in the said Policy, but not exceeding the Sum Insured as specified below.For the purpose of this 
          document, we consider <b>Indicosmic Capital Pvt. Ltd.</b> as the policyholder and its Customers as the 
          Insured.</td>
        </tr>
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>DETAILS OF THE INSURED PERSON(S) UNDER THE POLICY</b></td>
        </tr>
      </table>
      <table cellpadding="0" border="0" cellspacing="0">
        <tr>
          <td width="49.5%">
            <table cellpadding="2" border="0" cellspacing="0" class="boxtable font-7">
              <tr>
                <td width="44%">Certificate No.</td>
                <td width="56%">{$certificate_no}</td>
              </tr>
              <tr>
                <td>Policy Type</td>
                <td>New</td>
              </tr>
              <tr>
                <td>Previous Policy No.</td>
                <td></td>
              </tr>
              <tr>
                <td>Issued At</td>
                <td>{$state_name}</td>
              </tr>
              <tr>
                <td>Name of the Insured</td>
                <td>{$full_name_of_insured}</td>
              </tr>
              <tr>
                <td>GSTIN</td>
                <td></td>
              </tr>
              <tr>
                <td>Policy Issuing Office</td>
                <td>{$master_policy_location}</td>
              </tr>
              <tr>
                <td>Mailing Address of the Insured</td>
                <td>{$addr1},{$addr2}</td>
              </tr>
              <tr>
                <td>Mobile no. of the Insured:</td>
                <td>{$mobile_no}</td>
              </tr>
              <tr>
                <td>Email Address:</td>
                <td>{$email}</td>
              </tr>
              <tr>
                <td>Policy Period - From</td>
                <td>{$pa_sold_policy_effective_date}</td>
              </tr>
              <tr>
                <td>Policy Period - To midnight of</td>
                <td>{$pa_sold_policy_end_date}</td>
              </tr>
              <tr>
                <td>Installment Option</td>
                <td>NO</td>
              </tr>
              <tr>
                <td class="sectionhead" colspan="2">INTERMEDIARY DETAILS</td>               
              </tr>
              <tr>
                <td>Intermediary Code</td>
                <td>3204180000</td>
              </tr>
              <tr>
                <td>Intermediary Name</td>
                <td>Global India Insurance Brokers Pvt Ltd.</td>
              </tr>
              <tr>
                <td>Intermediary E-Mail Id</td>
                <td>info@giib.co.in</td>
              </tr>
              <tr>
                <td>Intermediary Landline No.</td>
                <td>022-26820489</td>
              </tr>
            </table>
          </td>
          <td width="1%"></td>
          <td width="49.5%">
            <table cellpadding="2" border="0" cellspacing="0" class="boxtable font-7">
              <tr>
                <td width="56%">Membership ID/ Employee Number Account Number pertaining to Credit(#)</td>
                <td width="44%">MD626AG79K2E03171 Financer Name: Indicosmic Capital Pvt. Ltd.</td>
              </tr>
              <tr>
                <td>Credit Tenure(#)</td>
                <td></td>
              </tr>
              <tr>
                <td>Name of the Insured Person</td>
                <td>{$full_name_of_insured}</td>
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
                <td>Relation with the Insured Person</td>
                <td>self</td>
              </tr>
              <tr>
                <td>Date of Birth DD/MM/YYYY</td>
                <td>{$dob}</td>
              </tr>
              <tr>
                <td>Gender</td>
                <td>{$gender}</td>
              </tr>
              <tr>
                <td>Category</td>
                <td></td>
              </tr>
              <tr>
                <td>Credit Amount <br>Outstanding Credit Amount (#)</td>
                <td></td>
              </tr>
              <tr>
                <td>Sum Insured</td>
                <td>{$sum_insured}</td>
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
                <td>{$nominee_name}</td>
              </tr>
              <tr>
                <td>Nominee Relationship with the Insured Person</td>
                <td>{$nominee_relation}</td>
              </tr>
              <tr>
                <td>Nominee Age</td>
                <td>{$nominee_age}</td>
              </tr>
              <tr>
                <td>Appointee Details in case Nominee is Minor</td>
                <td>{$appointee_details}</td>
              </tr>
              
            </table>
          </td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="line-height-15">(#) Applicable only to Credit linked policies</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>COVERAGE DETAILS:</b></td>
        </tr>       
      </table>
      <table cellpadding="1" border="0" cellspacing="0" class="boxtable font-7">
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
          <td class="textright">{$sum_insured}</td>
        </tr>
        <tr>
          <td class="textcenter">2</td>
          <td>Permanent Total Disablement (PTD) </td>
          <td class="textright">{$sum_insured}</td>
        </tr>
        {$imp_note1}           
      </table>
      {$imp_note}
      <table cellpadding="2" border="0" cellspacing="0">
       
        <tr>
          <td class="sectionhead"><b>PERMANENT EXCLUSION</b></td>
        </tr>
      </table>
      <table cellpadding="0" border="0" cellspacing="0" class="font-7">        
        <tr>
          <td>1. Arising or resulting from the Insured Person committing any breach of the law with criminal intent. <br>2. Any Injury or Illness directly or indirectly caused by or arising from or attributable to war or war like perils. <br>3. Any Illness or Injury directly or indirectly caused by or contributed to by nuclear weapons/material usage, consumption or abuse of substances intoxicants, hallucinogens, alcohol and/or drugs.self-destruction or self inflicted injury, attempted suicide or suicide. <br>4. Any consequential or indirect loss or expenses arising out of or related to any event giving rise to a Claim under the Policy.
            <br><b>For complete details please refer to the Policy wordings available with the Group Master Policyholder. Alternatively, the same can be downloaded from our website www.kotakgeneralinsurance.com</b> <br>5. Premium for your personal accident insurance has been paid by indicosmic capital Pvt Ltd.
          </td>
        </tr>
      </table>  
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>DISCLAIMER</b></td>
        </tr>
      </table>
      <table cellpadding="0" border="0" cellspacing="0" class="font-7">        
        <tr>
          <td>This Certificate of Insurance shall be read together with the Policy Schedule and Policy Wording and regarded as one contract and any word or expression to which a specific meaning has been assigned in any part of the policy or this schedule shall bear the same meaning wherever it may appear.</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="sectionhead"><b>IN THE EVENT OF CLAIM</b></td>
        </tr>
      </table>
      <table cellpadding="0" border="0" cellspacing="0" class="font-7">
        <tr>
          <td width="60%"><b>Contact Us at:</b> <br>Toll Free number: 1800 266 4545 or may write an e-mail at <u>care@kotak.com</u> , Timings : 8 AM to 6 PM </td>
          <td width="40%" rowspan="4" class="textright">For & On Behalf of <br> Kotak Mahindra General Insurance Company Ltd. <br><img src="assets/images/mpdf/eswar-sign-final.jpg" height="50"> <br>Authorized Signatory</td>
        </tr>

        <tr>
          <td><b>Please send the relevant documents to:</b> <br>Kotak Mahindra General Insurance Company Ltd. <br>8th Floor, Zone IV, Kotak Infiniti, Bldg. 21,Infinity IT Park, <br>Off WEH, Gen. AK Vaidya Marg, Dindoshi, Malad (E), <br>Mumbai - 400097. India</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td class="textcenter sectionhead"><b>Kotak Group Accident Protect UIN: IRDAI/HLT/KMGI/P-H(G)/V.I/61/2016-17</b></td>
        </tr>
      </table>    
      </td>
  </tr> 
  <tr>
      <td height="30"></td>
  </tr>
  <tr>
      <td>
        <table cellpadding="4" border="0" cellspacing="0" class="footer font-7" style="line-height:7pt;">
        <tr>
          <td>Kotak Mahindra General Insurance Company Ltd. (Formerly Kotak Mahindra General Insurance Ltd. <br>CIN: U66000MH2014PLC260291 <b>Registered Office:</b> 27 BKC, C 27, G Block, Bandra Kurla Complex, Bandra East, Mumbai - 400051. Maharashtra, India. <br><b>Office:</b> 8th Floor, Kotak Infiniti, Bldg. 21, Infinity IT Park, Off WEH, Gen. AK Vaidya Marg, Dindoshi, Malad(E), Mumbai - 400097.India. <br>Toll Free: 1800 266 4545 Email: care@kotak.com Website: www.kotakgeneralinsurance.com IRDAI Reg. No. 152</td>
        </tr>
      </table>
      </td>
  </tr>
</table>

EOD;
        $pdf->AddPage();
         if($dealer_id == 1){
          $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 5, 150, 250, '', '', '', true, 500);
         }
        $pdf->writeHTML($html);
        ob_clean();
        $pdf_to_name = "RSA-Kotak- .'$certificate_no'.pdf";
        $pdf->Output($pdf_to_name, 'I');

}


function DownloadTataLitePdf($id){
  $this->load->library('Tcpdf/Tcpdf.php');
  $this->load->library('Ciqrcode');
  ob_start();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TATA AIG PA Certificate');
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

$rsa_policy_data = $this->Home_Model->getPolicyById($id);
$where = array(
  'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
);
$master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
$master_policy_location = $master_policy_details['mp_localtion'];
$master_policy_address = $master_policy_details['address'];
$getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
$getICInfo = $this->Home_Model->getICInfo($rsa_policy_data['rsa_ic_id']);
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
$plan_detalis = $this->Home_Model->getDataFromTable('tvs_plans',$where);
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
$mp_end_date = isset($rsa_policy_data['mp_end_date']) ? $rsa_policy_data['mp_end_date']: ' ';

$email = isset($rsa_policy_data['email']) ? strtoupper($rsa_policy_data['email']) : '--';
$mobile_no = isset($rsa_policy_data['mobile_no']) ? strtoupper($rsa_policy_data['mobile_no']) : '--';
$gender = isset($rsa_policy_data['gender']) ? strtoupper($rsa_policy_data['gender']) : '--';
$dob = isset($rsa_policy_data['dob']) ? strtoupper($rsa_policy_data['dob']) : '--';
$addr1 = isset($rsa_policy_data['addr1']) ? strtoupper($rsa_policy_data['addr1']) : '--';
$addr2 = isset($rsa_policy_data['addr2']) ? strtoupper(mb_strimwidth($rsa_policy_data['addr2'], 0, 20, "...")) : '--';
$state_name = isset($rsa_policy_data['state_name']) ? strtoupper($rsa_policy_data['state_name']) : '--';
$city_name = isset($rsa_policy_data['city_name']) ? strtoupper($rsa_policy_data['city_name']) : '--';
$sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '--';
$sold_policy_issued_date = isset($rsa_policy_data['created_date']) ? $rsa_policy_data['created_date'] : '--';
$sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date']: '--';
$pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '--';
 $imp_note ='';
if( strtotime($pa_sold_policy_effective_date) >= strtotime('2019-09-05 21:59:59') ){
$imp_note = '<table cellpadding="1" border="0" cellspacing="0" class="">
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
$pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? strtoupper($rsa_policy_data['pa_sold_policy_end_date']) : '0000-00-00 00:00:00';

$model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';


$params['data'] = "Name: '" . $full_name_of_insured . "' , POLICY NO: '" . $certificate_no . "', FROM: '" . $pa_sold_policy_effective_date . "' , TO: '" . $pa_sold_policy_end_date . "'";
$params['level'] = 'H';
$params['size'] = 5;
$params['savename'] = FCPATH . 'assets/images/qr_image/'.trim($certificate_no).'.png';

$this->ciqrcode->generate($params);
$qr_code_image_url = '<img src="assets/images/qr_image/'.trim($certificate_no).'.png" width="60px" />';

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
  .font-7{font-size: 7pt; line-height:8pt;}
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
                <td width="50%"><b>Certificte Number</b></td>
                <td width="50%">{$certificate_no}</td>
              </tr> 
              <tr>
                <td><b>Plan Name</b></td>
                <td>{$plan_name}</td>
              </tr>
              <tr>
                <td><b>Certificate issue Date</b></td>
                <td>{$sold_policy_issued_date}</td>
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
          <td><b>Plan Amount(Rs.)</b></td>
          <td>{$plan_amount}</td>
        </tr>
        <tr>
          <td><b>Tax Amount(18% IGST in Rs.)</b></td>
          <td>{$gst_amount}</td>
        </tr>
        <tr>
          <td><b>Total Amount (Rs.)</b></td>
          <td>{$total_amount}</td>
        </tr>
      </table>
      </td>     
  </tr>
</table>
<br pagebreak="true" />
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="1" border="0" cellspacing="0" class="textcenter">
        <tr>
          <td width="20%" class="textleft">{$qr_code_image_url}</td>
          <td width="60%" class="textcenter line-height-18 font-9"><b><u>Personal Accident Policy - Certificate <br>Master Insured Name:<br>INDICOSMIC CAPITAL PVT. LTD.</u></b></td>
          <td width="20%" class="textright"><img src="assets/images/revised/tataaig-logo.png" height="70"></td>
        </tr>
        <tr>
          <td></td>
        </tr>   
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="25%">Master Policy No.:</td>
          <td width="25%">{$master_policy_no}</td>
          <td width="25%">Issuing Office: </td>
          <td width="25%"><b>{$city_name}</b></td>
        </tr>
        <tr>
          <td>Insured’s Name:</td>
          <td>{$full_name_of_insured}</td>
          <td>Certificate No: </td>
          <td>{$certificate_no}</td>
        </tr>
        <tr>
          <td>Insured’s Address:</td>
          <td colspan="3">{$addr1}</td>
        </tr>
        <tr>
          <td>Certificate Inception date: </td>
          <td>From - {$pa_sold_policy_effective_date}</td>
          <td>Expiry date: </td>
          <td>{$pa_sold_policy_end_date}</td>
        </tr>
        <tr>
          <td>Master Policy Period: </td>
          <td>From – {$mp_start_date}</td>
          <td>Expiry Date:</td>
          <td>To {$mp_end_date}</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable textcenter">
        <tr>
          <td width="16%"><b>Name of Insured Person</b></td>
          <td width="10%"><b>Date of Birth</b></td>
          <td width="10%"><b>Sum Insured</b></td>
          <td width="15%"><b>Nominee Name</b></td>
          <td width="22%"><b>Nominee Relationship with Insured Person</b></td>
          <td width="27%"><b>Benefits</b></td>
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
      <table cellpadding="4" border="0" cellspacing="0" class="boxtable textcenter">
        <tr>
          <td width="50%"><b>Benefits</b></td>
          <td width="50%"><b>Sum Insured</b></td>
        </tr>   
        <tr>
          <td>Accidental death</td>
          <td rowspan="2">INR {$sum_insured}</td>
        </tr>   
        <tr>
          <td>Permanent Total Disability</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td width="65%"><b>Place:</b> {$city_name} <br><b>Date:</b>{$created_date}</td>
          <td width="35%" class="textcenter"><b>For and on behalf of Tata AIG General Insurance Company Limited</b></td>
        </tr>
        <tr>
          <td></td>
          <td class="textcenter"><img src="assets/images/revised/Malpa-Signature.png" height="30"></td>
        </tr>
        <tr>
          <td style="border-bottom:1px solid #000;"></td>
          <td class="textcenter line-height-16" style="border-bottom:1px solid #000;"><b>Authorized Signatory</b></td>
        </tr>             
      </table>
      <table cellpadding="1" border="0" cellspacing="0" class="">
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2"><b><u>Details of GPA Policy</u></b><br>If the Insured shall sustain any bodily injury resulting solely and directly from Accident then the Company shall pay to the insured the sum hereinafter set forth:-</td>
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
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td colspan="2"><b><u>Important Exclusions in brief:</u></b></td>
        </tr>
        <tr>
          <td colspan="2">Company shall not be liable under this Policy for -</td>
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
          <td colspan="2"><b>Claim Procedure:</b></td>
        </tr>       
        <tr>
          <td colspan="2">All claims under this policy will be processed and settled by us.  </td>
        </tr>     
        <tr>
          <td colspan="2">You can get in touch with us as below -</td>
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
          <td colspan="2"><b><u>Documents for claims:</u></b></td>
        </tr>
        <tr>
          <td colspan="2">An indicative document list in case of claim is as given below. Additional documents/ information may be called for and/or we may carry out verification where felt necessary.</td>
        </tr>         
      </table>
      <table cellpadding="0" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="50%">
            <table cellpadding="1" border="0" cellspacing="0">
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
            <table cellpadding="1" border="0" cellspacing="0">
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
          <td style="border-bottom:1px solid #000;"></td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>  
          <td class="textcenter" style="color:#808080;">Tata AIG General Insurance Company Limited <br>Registered Office: : Peninsula Business Park, Tower A, 15th Floor, G. K. Marg, Off Senapati Bapat Road, Lower Parel, Mumbai- 400013. Toll Free Helpline <br>No. 1800 266 7780 / 1800119966. Website: www.tataaiginsurance.in <br>IRDA Registration Number: 108 CIN: U85110MH2000PLC128425 <br>Group Personal Accident and Business Travel Accident Policy– UIN: IRDA/NL-HLT/TAGI/P-P/V.I/290/13-14</td>
        </tr>
      </table>
      </td>     
  </tr>
</table>

EOD;

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('TATA-AIG-PA-"$certificate_no".pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

}

function DownloadILLitePdf($id){
$this->load->library('Tcpdf/Tcpdf.php');
$this->load->library('Ciqrcode');
ob_start();

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('ICICI Lombard Policy Certificate');
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

$rsa_policy_data = $this->Home_Model->getPolicyById($id);
// echo '<pre>';print_r($rsa_policy_data);die;

$where = array(
  'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
);
$master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
$master_policy_location = $master_policy_details['mp_localtion'];
$master_policy_address = $master_policy_details['address'];
$getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
$getICInfo = $this->Home_Model->getICInfo($rsa_policy_data['rsa_ic_id']);
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
$plan_detalis = $this->Home_Model->getDataFromTable('tvs_plans',$where);
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

if(!empty($appointee_age)){
  $appointee_details = '';
}else{
  $appointee_details = 'Name:' .$appointee_name.' Relationship: '.$appointee_relation .'Age: '.$appointee_age;
}
 //echo $appointee_details;exit;
//master policy detils
$master_policy_no = isset($rsa_policy_data['master_policy_no']) ? strtoupper($rsa_policy_data['master_policy_no']) : ' ';
$mp_start_date = isset($rsa_policy_data['mp_start_date']) ? strtoupper($rsa_policy_data['mp_start_date']) : ' ';
$date = new DateTime($mp_start_date);
$mp_start_date = $date->format('d-M-Y');
$mp_end_date = isset($rsa_policy_data['mp_end_date']) ? strtoupper($rsa_policy_data['mp_end_date']) : ' ';

$email = isset($rsa_policy_data['email']) ? strtoupper($rsa_policy_data['email']) : '--';
$mobile_no = isset($rsa_policy_data['mobile_no']) ? strtoupper($rsa_policy_data['mobile_no']) : '--';
$gender = isset($rsa_policy_data['gender']) ? strtoupper($rsa_policy_data['gender']) : '--';
$dob = isset($rsa_policy_data['dob']) ? strtoupper($rsa_policy_data['dob']) : '--';
$addr1 = isset($rsa_policy_data['addr1']) ? strtoupper($rsa_policy_data['addr1']) : '--';
$addr2 = isset($rsa_policy_data['addr2']) ? strtoupper(mb_strimwidth($rsa_policy_data['addr2'], 0, 20, "...")) : '--';
$state_name = isset($rsa_policy_data['state_name']) ? strtoupper($rsa_policy_data['state_name']) : '--';
$city_name = isset($rsa_policy_data['city_name']) ? strtoupper($rsa_policy_data['city_name']) : '--';
$sold_policy_effective_date = isset($rsa_policy_data['sold_policy_effective_date']) ? $rsa_policy_data['sold_policy_effective_date'] : '--';
$sold_policy_issued_date = isset($rsa_policy_data['created_date']) ? $rsa_policy_data['created_date'] : '--';
$sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? strtoupper($rsa_policy_data['sold_policy_end_date']) : '--';
$pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '--';
$imp_note ='';
  if( strtotime($pa_sold_policy_effective_date) >= strtotime('2019-09-05 21:59:59') ){
      $imp_note = '<tr>
          <td style="color:#365f91;">7. The personal accident coverage is applicable only to the owner of the vehicle for which Road Side Assistance service has been provided by the Indicosmic Capital Pvt Ltd. <br>8. The said personal accident cover is active only whilst the owner is driving or travelling in the said vehicle including mounting into/dismounting from the said vehicle. <br>9. Death or permanent total disability claims due to any other incidence would not be covered <br>10. The policy is valid for 365 days from the policy risk start date</td>
        </tr>';
  }
$pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '0000-00-00 00:00:00';

$model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';


$params['data'] = "Name: '" . $full_name_of_insured . "' , POLICY NO: '" . $certificate_no . "', FROM: '" . $pa_sold_policy_effective_date . "' , TO: '" . $pa_sold_policy_end_date . "'";
$params['level'] = 'H';
$params['size'] = 5;
$params['savename'] = FCPATH . 'assets/images/qr_image/'.trim($certificate_no).'.png';

$this->ciqrcode->generate($params);
$qr_code_image_url = '<img src="assets/images/qr_image/'.trim($certificate_no).'.png" width="60px" />';
// ---------------------------------------------------------


// set font
$pdf->SetFont('helvetica', '', 8);


// set some text to print
$html = <<<EOD
<style>
  .pagewrap {color: #000; font-size: 8pt; line-height:9pt; color:#000;}
  .textcenter {text-align:center;}
  .textleft {text-align:left;}
  .textright {text-align:right;}
  .font-6{font-size: 5.5pt; line-height:7pt;}
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
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable font-7">
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
                <td>$sold_policy_issued_date</td>
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
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable font-7">
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
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable font-7">
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
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable font-7">
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
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable font-7">
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
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable font-7">
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
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable font-7">
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
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable font-7">
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
      <table cellpadding="2" border="0" cellspacing="0" class="bluetable font-7">
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
            <table cellpadding="3" border="0" cellspacing="0" class="bluetable font-7">
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
      <table cellpadding="3" border="0" cellspacing="0" class="bluetable font-7">
        <tr>
          <td><b>Plan Amount(Rs.)</b></td>
          <td>{$plan_amount}</td>
        </tr>
        <tr>
          <td><b>Tax Amount(18% IGST in Rs.)</b></td>
          <td>{$gst_amount}</td>
        </tr>
        <tr>
          <td><b>Total Amount (Rs.)</b></td>
          <td>{$total_amount}</td>
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
          <td width="25%" class="textleft">{$qr_code_image_url} </td>
          <td width="50%" class="font-10 line-height-16 textcenter" style="color:#365f91;"><br><br><br><b><u>Policy Certificate – Group Personal Accident</u></b></td>
          <td width="25%" class="textright"><img src="assets/images/revised/icici_lombard.png" height="35px"></td>
        </tr>
        <tr>
          <td colspan="3"><img src="assets/images/revised/sepline-top.png" height="15"></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table cellpadding="2" border="0" cellspacing="0">
        <tr>
          <td style="text-align:justify;">ICICI Lombard Group Personal Accident Policy no. {$master_policy_no} dated {$mp_start_date} has been issued at Mumbai, by ICICI Lombard General Insurance Company Limited to the Policyholder {$mp_end_date},as specified in the policy and is governed by, and is subject to, the terms, conditions & exclusions therein contained or otherwise expressed in the said policy, but not exceeding the sum insured as specified in Part I of the schedule to the said policy</td>
        </tr>
        <tr>
          <td style="text-align:justify;">This certificate, issued under the signature of an authorized signatory of the Company represents the availability of benefit to the insured named below, Customers of <b>Indicosmic Pvt.Ltd.</b> subject to the terms, conditions and exclusions contained or otherwise expressed in the said Policy to the extent of sum insured mentioned as maximum liability, but not exceeding the Sum Insured as specified below.</td>
        </tr>
      
      </table>
      <table cellpadding="2" border="0" cellspacing="0">        
        <tr>
          <td width="49%">
            <table cellpadding="2" border="0" cellspacing="0">  
              <tr>
                <td></td>
              </tr>
            </table>
            <table cellpadding="2" border="0" cellspacing="0" class="boxtable textleft">
              <tr>
                <td width="40%"><b>Policy No.</b></td>
                <td width="60%">{$certificate_no}</td>         
              </tr>
              <tr>
                <td><b>Policy Tenure</b></td>
                <td>1</td>
              </tr>
              <tr>
                <td><b>Period of Insurance From: </b></td>
                <td>{$pa_sold_policy_effective_date}</td>
              </tr>
              <tr>
                <td><b>Period of Insurance To: </b></td>
                <td>{$pa_sold_policy_end_date}</td>
              </tr>
              <tr>
                <td><b>Insured Name</b></td>
                <td>{$full_name_of_insured}</td>
              </tr>
              <tr>
                <td><b>Insured Address</b></td>
                <td>{$addr1},{$addr2}</td>
              </tr>
              <tr>
                <td><b>Contact No.</b></td>
                <td>{$mobile_no}</td>
              </tr>
              <tr>
                <td><b>Email ID</b></td>
                <td>{$email}</td>
              </tr>
              <tr>
                <td><b>Policy Issuing Office</b></td>
                <td>{$master_policy_location}</td>
              </tr>       
            </table>
          </td>
          <td width="2%"></td>
          <td width="49%">
            <table cellpadding="2" border="0" cellspacing="0">  
              <tr>
                <td><b>Insured Details</b></td>
              </tr>
            </table>
            <table cellpadding="2" border="0" cellspacing="0" class="boxtable textleft">
              <tr>
                <td width="40%"><b>Name in full</b></td>
                <td width="60%">{$full_name_of_insured}</td>         
              </tr> 
              <tr>
                <td><b>Date of birth</b></td>
                <td>{$dob}</td>
              </tr>
              <tr>
                <td><b>Gender</b></td>
                <td>{$gender}</td>
              </tr>
              <tr>
                <td><b>Occupation</b></td>
                <td></td>
              </tr>
              <tr>
                <td><b>Risk Category</b></td>
                <td></td>
              </tr>
              <tr>
                <td><b>RSA Policyholder group</b></td>
                <td></td>
              </tr>
              <tr>
                <td><b>Beneficiary / Nominee</b></td>
                <td>{$nominee_name}</td>
              </tr>
              <tr>
                <td height="34"><b>Relation of Nominee with the Insured</b></td>
                <td>{$nominee_relation}</td>
              </tr> 
            </table>
          </td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">  
        <tr>
          <td style="text-align:justify;"><b>PREAMBLE</b> <br>ICICI Lombard General Insurance Company Limited (“the Company”), having received a Proposal and the premium from the Policy holder named in the Schedule referred to herein below, and the said Proposal and Declaration together with any statement, report or other document leading to the issue of this Policy and referred to therein having been accepted and agreed to by the Company and the Policy holder as the basis of this contract do, by this Policy agree, in consideration of and subject to the due receipt of the subsequent premiums, as set out in the Schedule with all its Parts, and further,  subject to the terms and conditions contained in this Policy, as set out in the Schedule with all its Parts that on proof to the satisfaction of the Company of the compensation having become payable as set out in Part I of the Schedule to the title of the said person or persons claiming payment or upon the happening of an event upon which one or more benefits become payable under this Policy, the Sum Insured/ appropriate benefit amount will be paid by the Company.</td>
        </tr>
      </table>
      <table cellpadding="2" border="0" cellspacing="0">  
        <tr>
          <td><b>Benefit & Extension Table</b></td>
        </tr> 
      </table>  
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable textleft">
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
          <td rowspan="2">{$sum_insured}</td>
        </tr>
        <tr>
          <td>Benefit 2</td>
          <td>Permanent Total Disablement (PTD) resulting from Accident</td>
          <td>To pay Sum Insured on the occurrence of PTD which result solely and directly from an Injury, within twelve months from the date of Accident</td>
        </tr>     
      </table>
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable textcenter">
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
      <table cellpadding="2" border="0" cellspacing="0" class="boxtable textcenter">
        <tr>
          <td class="heading" colspan="6">Agent / Broker Details</td>
        </tr>
        <tr>
          <td width="10%" class="heading"><b>Agent Name</b></td>
          <td width="37%">GLOBAL INDIA INSURANCE BROKERS PVT LTD</td>
          <td width="10%" class="heading"><b>Agent Code</b></td>
          <td width="16%">IRDA/DB-596/14</td>
          <td width="14%" class="heading"><b>Agent contact No.</b></td>
          <td width="13%">022-26820489</td>
        </tr>     
      </table>  
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td style="color:#365f91;"><b>Important Notes:</b> <br>1. Insurance cover will start only on receipt of complete premium by ICICI Lombard General Insurance Company Limited <br>2. Insurance cover is subject to the terms and conditions mentioned in the Policy wordings provided to you with this Certificate <br>3. The above covers would not be applicable for persons occupied in underground mines, explosives and electrical installations on high tension lines <br>4. Major exclusions: Intentional self-injury, suicide or attempted suicide whilst under the influence of intoxicating liquor or drugs, Any loss arising from an act made in breach of law with or without criminal intent. <br>5. The claimant can contact us at Toll Free Number 1800-2-666 or Email us at customersupport@icicilombard.com for lodging the claim. <br>6. Claim Notification address: IL Health Care,Secure Mind Claims, ICICI LOMBARD HEALTHCARE ICICI BANK TOWER, PLOT NO.12FINANCIAL DISTRICT, NANAKRAM GUDA, GACHIBOWLI, HYDERABAD</td>
        </tr>
        {$imp_note}
      </table>  
      <table cellpadding="5" border="0" cellspacing="0" class="boxtable">             
        <tr>
          <td width="30%" style="background-color:#d9d9d9;">
            <table cellpadding="2" border="0" cellspacing="0">  
              <tr>
                <td>For ICICI Lombard General Insurance Company ltd.</td>
              </tr>
              <tr>
                <td><img src="assets/images/revised/sign-img.jpg" height="80"></td>
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
    <td>
      <table cellpadding="0" border="0" cellspacing="0">
        <tr>
          <td><img src="assets/images/revised/sepline-bot.png" height="10"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>


EOD;
$pdf->AddPage();
$pdf->writeHTML($html);
ob_clean();
$pdf_to_name = "RSA-ICICLombard- .'$certificate_no'.pdf";
$pdf->Output($pdf_to_name, 'I');
}

function DownloadBagiLitePDF($id){
$this->load->library('Tcpdf/Tcpdf.php');
$this->load->library('Ciqrcode');
ob_start();
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Bharti AXA PA Information');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(5, 8, 5);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
  require_once(dirname(__FILE__).'/lang/eng.php');
  $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

$rsa_policy_data = $this->Home_Model->getPolicyById($id);
$where = array(
  'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
);
$master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
$master_policy_location = $master_policy_details['mp_localtion'];
$master_policy_address = $master_policy_details['address'];
$getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
// echo '<pre>';print_r($getDealerInfo);die;
$getICInfo = $this->Home_Model->getICInfo($rsa_policy_data['rsa_ic_id']);
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
$plan_detalis = $this->Home_Model->getDataFromTable('tvs_plans',$where);
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
$master_policy_no = isset($rsa_policy_data['master_policy_no']) ?$rsa_policy_data['master_policy_no'] : ' ';
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
$sold_policy_issued_date = isset($rsa_policy_data['created_date']) ? $rsa_policy_data['created_date'] : '--';
$sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? strtoupper($rsa_policy_data['sold_policy_end_date']) : '--';
$pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '--';
$imp_note ='';
  if( strtotime($pa_sold_policy_effective_date) >= strtotime('2019-09-05 21:59:59') ){
      $imp_note = '<table cellpadding="1" border="0" cellspacing="0">
                    <tr>
                      <td colspan="2"><b><u>Important Notes:</u></b></td>
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
                    
                    <tr>
                      <td colspan="2"></td>
                    </tr>
                  </table>  ';
  }
$pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '0000-00-00 00:00:00';

$model_name = isset($rsa_policy_data['model_name']) ? strtoupper($rsa_policy_data['model_name']) : '--';


$params['data'] = "Name: '" . $full_name_of_insured . "' , POLICY NO: '" . $certificate_no . "', FROM: '" . $pa_sold_policy_effective_date . "' , TO: '" . $pa_sold_policy_end_date . "'";
$params['level'] = 'H';
$params['size'] = 5;
$params['savename'] = FCPATH . 'assets/images/qr_image/'.trim($certificate_no).'.png';

$this->ciqrcode->generate($params);
$qr_code_image_url = '<img src="assets/images/qr_image/'.trim($certificate_no).'.png" width="60px" />';
// ---------------------------------------------------------


// set font
$pdf->SetFont('helvetica', '', 8);
$html=<<<EOD
<style>
  .pagewrap {color: #000; font-size: 7pt; line-height:9pt; color:#000;}
  .textcenter {text-align:center;}
  .textleft {text-align:left;}
  .textright {text-align:right;}
  .font-6{font-size: 5.5pt; line-height:7pt;}
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
  .bluetable td {border:0.2px solid #16365c;}
  .tb-heading {background-color:#16365c; color:#fff;text-transform:uppercase; font-weight:bold;}
</style>
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="1" border="0" cellspacing="0" class="textcenter">
        <tr>
          <td><img src="assets/images/bikes-img.jpg"></td>
        </tr>   
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td width="48%">
            <table cellpadding="4" border="0" cellspacing="0" class="bluetable font-7">
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
                <td>{$sold_policy_issued_date}</td>
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
            <table cellpadding="4" border="0" cellspacing="0" class="bluetable font-7">
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
            <table cellpadding="4" border="0" cellspacing="0" class="bluetable font-7">
              <tr class="tb-heading">
                <td colspan="2">PERSONAL DETAILS</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td width="48%">
            <table cellpadding="4" border="0" cellspacing="0" class="bluetable font-7">
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
            <table cellpadding="4" border="0" cellspacing="0" class="bluetable font-7">
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
          <td colspan="3">
            <table cellpadding="4" border="0" cellspacing="0" class="bluetable font-7">
              <tr class="tb-heading">
                <td colspan="2"> PAYMENT DETAILS</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td width="48%">
            <table cellpadding="4" border="0" cellspacing="0" class="bluetable font-7">
              <tr>
                <td><b>Plan Amount(Rs.)</b></td>
              </tr>
              <tr>
                <td><b>Tax Amount(18% IGST in Rs.)</b></td>
              </tr>
              <tr>
                <td><b>Total Amount (Rs.)</b></td>
              </tr>
            </table>
          </td>
          <td width="4%"></td>
          <td width="48%">
            <table cellpadding="4" border="0" cellspacing="0" class="bluetable font-7">
              <tr>
                <td>{$plan_amount}</td>
              </tr>
              <tr>
                <td>{$gst_amount}</td>
              </tr>
              <tr>
                <td>{$total_amount}</td>
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
      <table cellpadding="2" border="0" cellspacing="0" class="textcenter font-6">
        <tr>
          <td><img src="assets/images/revised/service-icon-1.jpg"></td>
          <td><img src="assets/images/revised/service-icon-2.jpg"></td>
          <td><img src="assets/images/revised/service-icon-3.jpg"></td>
          <td><img src="assets/images/revised/service-icon-4.jpg"></td>
          <td><img src="assets/images/revised/service-icon-5.jpg"></td>
          <td><img src="assets/images/revised/service-icon-6.jpg"></td>
          <td><img src="assets/images/revised/service-icon-7.jpg"></td>
          <td><img src="assets/images/revised/service-icon-8.jpg"></td>
          <td><img src="assets/images/revised/service-icon-9.jpg"></td>
          <td><img src="assets/images/revised/service-icon-10.jpg"></td>
          <td><img src="assets/images/revised/service-icon-11.jpg"></td>
          <td><img src="assets/images/revised/service-icon-12.jpg"></td>
          <td><img src="assets/images/revised/service-icon-13.jpg"></td>
          <td><img src="assets/images/revised/service-icon-14.jpg"></td>
        </tr>
        <tr>
          <td>Towing Assistance</td>
          <td>Onsite support for Minor repairs</td>
          <td>Rundown of Battery</td>
          <td>Flat Tyre</td>
          <td>Fuel Assistance</td>
          <td>Customer Coverage Care</td>
          <td>Urgent Message Relay</td>
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
      </table>      
      <table cellpadding="2" border="0" cellspacing="0" class="bluetable font-6">
        <tr class="tb-heading textcenter">
          <td> *All consumables would be charged back to the customer. Cost of accommodation, Taxi, rental vehicle needs to be paid by the customer</td>
        </tr>
      </table>
          
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0" class="bluetable">
        <tr class="tb-heading">
          <td>CHANNEL PARTNER: TVS</td>
        </tr>
      </table>          
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td width="48%">
            <table cellpadding="4" border="0" cellspacing="0" class="bluetable font-7">
              <tr class="tb-heading">
                <td>INDICOSMIC CAPITAL</td>
              </tr>
              <tr>
                <td>
                  <table cellpadding="1" border="0" cellspacing="0">
                    <tr>
                      <td><img src="assets/images/icpl-logo.jpg" height="40"></td>
                    </tr>
                    <tr>
                      <td height="50"><b>Address:</b> 318, 3rd Floor, Summit-Business Bay, Behind Gurunanak Petrol Pump,
                      Off Andheri Kurla Road, Beside Magic Bricks WEH metro stn., Andheri (E),Mumbai-400093, Maharashtra (India)</td>
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
            <table cellpadding="4" border="0" cellspacing="0" class="bluetable font-7">
              <tr class="tb-heading">
                <td>EMAIL : info@indicosmic.com</td>
              </tr>             
            </table>
          </td>
          <td width="4%"></td>
          <td width="48%">
            <table cellpadding="4" border="0" cellspacing="0" class="bluetable font-7">
              <tr class="tb-heading">
                <td>{$rsa_name}</td>
              </tr>
              <tr>
                <td>
                  <table cellpadding="1" border="0" cellspacing="0">
                    <tr>
                      <td><img src="{$rsa_logo}" height="40"></td>
                    </tr>
                    <tr>
                      <td height="50"><b>Address:</b> {$rsa_address}</td>
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
            <table cellpadding="4" border="0" cellspacing="0" class="bluetable font-7">
              <tr class="tb-heading">
                <td>EMAIL : {$rsa_email}</td>
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
      <table cellpadding="4" border="0" cellspacing="0" class="bluetable">
        <tr class="tb-heading textcenter">
          <td>CUSTOMER CARE NO: {$customer_care_no}</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="4" border="0" cellspacing="0" class="bluetable">
        <tr class="tb-heading">
          <td>THE SERVICES PROVIDED UNDER THE ASSISTANCE ARE AS FOLLOWS:</td>
        </tr>
      </table>
      </td>     
  </tr>
</table>
<br pagebreak="true" />
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="1" border="0" cellspacing="0" class="textcenter">
        <tr>
          <td width="30%" class="textleft"><img src="assets/images/revised/qr-code.png" height="70"></td>
          <td width="40%" class="font-9 line-height-16"><b><u>Personal Accident Policy - Information <br>Master Policy Holder Name: <br>INDICOSMIC CAPITAL PVT. LTD.</u></b></td>
          <td width="30%" class="textright"><img src="assets/images/bharti-axa-logo-big.jpg" height="70"></td>
        </tr>         
        <tr>
          <td colspan="3"></td>
        </tr>   
      </table>
      <table cellpadding="3" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td>IMD Code: 13001409</td>
          <td>IMD Name: GLOBAL INDIA INSURANCE BROKERS PVT. LTD.</td>
        </tr>
        <tr>
          <td>Master Policy No.: {$master_policy_no}</td>
          <td>Certificate No.: {$certificate_no}</td>
        </tr>
        <tr>
          <td>Name of Insured: {$full_name_of_insured}</td>
          <td rowspan="3">Address: {$addr1} {$addr2}</td>
        </tr>
        <tr>
          <td>Period of Insurance: From {$pa_sold_policy_effective_date} To midnight of {$pa_sold_policy_end_date}</td>
        </tr>       
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="3" border="0" cellspacing="0" class="boxtable textcenter">
        <tr>
          <td width="22%">Name of Member</td>
          <td width="14%">Gender</td>
          <td width="16%">Date of Birth</td>
          <td width="20%">Name of Nominee</td>
          <td width="28%">Relationship of nominee with insured</td>
        </tr>
        <tr>
          <td>{$full_name_of_insured}</td>
          <td>{$gender}</td>
          <td>{$dob}</td>
          <td>{$nominee_name}</td>
          <td>{$nominee_relation}</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table cellpadding="3" border="0" cellspacing="0" class="boxtable">
        <tr>
          <td width="12%">Section</td>
          <td width="60%" class="textcenter">Coverage</td>
          <td width="15%" class="textcenter">Sum Insured</td>
          <td width="13%" class="textcenter">Scope of Cover</td>
        </tr>
        <tr>
          <td>Accidental Death</td>
          <td>If any Insured Person sustains Injury during the policy period which directly and independently of all other causes result in death within 12 Months from the date of accident, the company agrees to pay to the Insured Person’s Nominee, Beneficiary or legal representative, the Sum Insured specified in the Schedule/Certificate of Insurance</td>
          <td rowspan="2" class="textcenter">Rs.{$sum_insured}/-</td>
          <td rowspan="2" class="textcenter">Within India</td>
        </tr>
        <tr>
          <td>Permanent Total Disablement (PTD)</td>
          <td>If any Insured Person sustains Injury during the policy period which directly and independently of all other causes result in any of the disablement stated in the table below and opted for by the Policyholder/Insured Person as indicated in the Policy Schedule/Certificate of Insurance, within twelve months from the date of accident, the company agrees to pay to the Insured Person, the Sum Insured specified in the Schedule of the policy</td>
        </tr>
      </table>
      <table cellpadding="1" border="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
        <tr>
          <td class="textright"><img src="assets/images/sign-img.jpg" height="40"></td>
        </tr>
        <tr>
          <td class="textright">For Bharti AXA General Insurance Company Limited</td>
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
        <tr>
          <td colspan="2"></td>
        </tr>
      </table>  
       {$imp_note}
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
      </table>
      </td>     
  </tr>
</table>
EOD;
// add a page
$pdf->AddPage();
$pdf->writeHTML($html);
ob_clean();
$pdf_to_name = "RSA-BAGI- .'$certificate_no'.pdf";
$pdf->Output($pdf_to_name, 'I');
}

function DownloadWorkshopOICLPdf($id){
  $rsa_policy_data = $this->Home_Model->getPolicyById($id);
  $where = array(
    'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
  );
  $master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
  $master_policy_location = $master_policy_details['mp_localtion'];
  $master_policy_address = $master_policy_details['address'];
  $getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
  $getICInfo = $this->Home_Model->getICInfo($rsa_policy_data['rsa_ic_id']);
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
  $plan_detalis = $this->Home_Model->getDataFromTable('tvs_plans',$where);
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
          <td>Beneficiary Name: {$full_name_of_insured}</td>
        </tr>
        <tr>
          <td>Beneficiary's Address: {$addr1} {$addr2}</td>
        </tr>
        <tr>
          <td>Beneficiary ID no.: {$certificate_no}</td>
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
          <td colspan="2"><b>Details of GPA Policy</b><br>If the Insured person sustains any bodily injury during the policy period which directly and independently of all other causes result in death/ disablement stated below within 12 months from the date of accident  resulting solely and directly from Accident then the Company shall pay to the insured the sum set in the schedule to the Insured person’s nominee ,beneficiary or legal representative. Details of GPA Policy- This certificate is effective from the date of issuance of the certificate and 24/7.</td>
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
            <td>The said personal accident cover is active 24/7.</td>
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
        <td>After receipt of all claim related documents Oriental Insurance Company Limited will settle the claim with the beneficiary/nominee.</td>
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
$pdf->Output('OICL-PA-Certificate.pdf', 'I');
    ob_end_flush();
    ob_end_clean();
}

function DownloadOrientalPdf($id){
  $rsa_policy_data = $this->Home_Model->getPolicyById($id);
  $where = array(
    'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
  );
  $master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
  $master_policy_location = $master_policy_details['mp_localtion'];
  $master_policy_address = $master_policy_details['address'];
  $getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
  $getICInfo = $this->Home_Model->getICInfo($rsa_policy_data['rsa_ic_id']);
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
  $plan_detalis = $this->Home_Model->getDataFromTable('tvs_plans',$where);
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
          <td><b>Plan Amount(Rs.)</b></td>
          <td>{$plan_amount}</td>
        </tr>
        <tr>
          <td><b>Tax Amount(18% IGST in Rs.)</b></td>
          <td>{$gst_amount}</td>
        </tr>
        <tr>
          <td><b>Total Amount (Rs.)</b></td>
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
          <td>Beneficiary Name: {$full_name_of_insured}</td>
        </tr>
        <tr>
          <td>Beneficiary's Address: {$addr1} {$addr2}</td>
        </tr>
        <tr>
          <td>Beneficiary ID no.: {$certificate_no}</td>
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
        <td>After receipt of all claim related documents Oriental Insurance Company Limited will settle the claim with the beneficiary/nominee.</td>
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
$pdf->Output('OICL-PA-Certificate.pdf', 'I');
    ob_end_flush();
    ob_end_clean();
    // $pdf->Output($pdf_to_name, 'I');
}


    public function DownloadKotakFullPolicy($id) {
        $rsa_policy_data = $this->Home_Model->getPolicyById($id);
        $where = array(
          'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
        );
        $master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
        $master_policy_location = $master_policy_details['mp_localtion'];
        $master_policy_address = $master_policy_details['address'];
        $getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
        // echo '<pre>';print_r($getDealerInfo);die;
        $getICInfo = $this->Home_Model->getICInfo($rsa_policy_data['rsa_ic_id']);
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
        $plan_detalis = $this->Home_Model->getDataFromTable('tvs_plans',$where);
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

        
        $pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '0000-00-00 00:00:00';
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
          <td width="60%"><b>Contact Us at:</b> <br>8 AM to 8 PM Toll Free number: 1800 266 4545 or may write an e-mail at <u>care@kotak.com</u></td>
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
        //"policy"-firtsandlastnamecompany-policynumber
        $pdf_to_name = "Kotak-RSA-Policy- .'$certificate_no'.pdf";
        ob_clean();
        $pdf->Output($pdf_to_name, 'I');
    }





public function ICICI_full_Pdf($id){
        $rsa_policy_data = $this->Home_Model->getPolicyById($id);
        // echo '<pre>'; print_r($rsa_policy_data);die('hello');
        $where = array(
          'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
        );
        $master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
        $master_policy_location = strtoupper($master_policy_details['mp_localtion']);
        $master_policy_address = strtoupper($master_policy_details['address']);
        $getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
        $getICInfo = $this->Home_Model->getICInfo($rsa_policy_data['rsa_ic_id']);
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
        $plan_detalis = $this->Home_Model->getDataFromTable('tvs_plans',$where);
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
        $pa_sold_policy_effective_date = isset($rsa_policy_data['pa_sold_policy_effective_date']) ? $rsa_policy_data['pa_sold_policy_effective_date'] : '0000-00-00';
        // $pa_sold_policy_effective_date ='2019-09-01 23:59:59';
         $imp_note ='';
        if(strtotime($pa_sold_policy_effective_date) >= strtotime('2019-09-05 21:59:59')){
            $imp_note = '<tr>
            <td style="color:#365f91;">7. The personal accident coverage is applicable only to the owner of the vehicle for which Road Side Assistance service has been provided by the Indicosmic Capital Pvt Ltd. <br>8. The said personal accident cover is active only whilst the owner is driving or travelling in the said vehicle including mounting into/dismounting from the said vehicle. <br>9. Death or permanent total disability claims due to any other incidence would not be covered <br>10. The policy is valid for 365 days from the policy risk start date</td>
          </tr>';
        }
        $sold_policy_end_date = isset($rsa_policy_data['sold_policy_end_date']) ? $rsa_policy_data['sold_policy_end_date'] : '--';
        
        $pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '--';
        
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
                <td>'.$addr1.' '.$addr2.'</td>
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
      if($dealer_id == 2871 || $dealer_id == 2872){
          $pdf->Image('assets/images/mpdf/dummy-policy-bg.png', 25, 5, 150, 250, '', '', '', true, 500);
         }

       $pdf->writeHTML($html, true, 0, true, 0, '');
        //"policy"-firtsandlastnamecompany-policynumber
        ob_end_clean();
       $pdf->Output('ICICI-Lombard-Policy-Certificate.pdf', 'I');
}

public function DownloadTataFullPolicy($id) {
        $rsa_policy_data = $this->Home_Model->getPolicyById($id);
        // echo '<pre>'; print_r($rsa_policy_data);die('hello');
        $getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
        $getICInfo = $this->Home_Model->getICInfo($rsa_policy_data['rsa_ic_id']);
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
        $plan_detalis = $this->Home_Model->getRowDataFromTable('tvs_plans',$where);
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
        
        $pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? strtoupper($rsa_policy_data['pa_sold_policy_end_date']) : '--';
        
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
        $pdf->Output($pdf_to_name, 'I');
    }

public function DownloadBhartiFullPolicy($id) {
        $rsa_policy_data = $this->Home_Model->getPolicyById($id);
        // echo '<pre>'; print_r($rsa_policy_data);die('hello');
        $getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
        // echo '<pre>';print_r($getDealerInfo);die;
        $getICInfo = $this->Home_Model->getICInfo($rsa_policy_data['rsa_ic_id']);
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
        $plan_detalis = $this->Home_Model->getDataFromTable('tvs_plans',$where);
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
        
        $pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '--';
        
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
          <td rowspan="2" class="textcenter">{$sum_insured}/-</td>
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
        $pdf->Output($pdf_to_name, 'I');
    }

function ReliancePDF($id){

$rsa_policy_data = $this->Home_Model->getPolicyById($id);
  // echo "<pre>"; print_r($rsa_policy_data); echo "</pre>"; die('end of line yoyo');
$where = array(
  'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
);
$master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
$master_policy_location = $master_policy_details['mp_localtion'];
$master_policy_address = $master_policy_details['address'];
$getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
$getICInfo = $this->Home_Model->getICInfo($rsa_policy_data['rsa_ic_id']);
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
$plan_detalis = $this->Home_Model->getDataFromTable('tvs_plans',$where);
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
$pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '0000-00-00 00:00:00';

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
              <td><b>Plan Amount(Rs.)</b></td>
              <td>{$plan_amount}</td>
            </tr>
            <tr>
              <td><b>Tax Amount(18% IGST in Rs.)</b></td>
              <td>{$gst_amount}</td>
            </tr>
            <tr>
              <td><b>Total Amount (Rs.)</b></td>
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
$pdf->Output('Reliance GPA Master Policy.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

}


function LibertyGeneral($id){
  $rsa_policy_data = $this->Home_Model->getPolicyById($id);
  // echo "<pre>"; print_r($rsa_policy_data); echo "</pre>"; die('end of line yoyo');
  $where = array(
    'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
  );
  $master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
  $master_policy_location = $master_policy_details['mp_localtion'];
  $master_policy_address = $master_policy_details['address'];
  $getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
  $getICInfo = $this->Home_Model->getICInfo($rsa_policy_data['rsa_ic_id']);
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
  $plan_detalis = $this->Home_Model->getDataFromTable('tvs_plans',$where);
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

  $pa_sold_policy_end_date = isset($rsa_policy_data['pa_sold_policy_end_date']) ? $rsa_policy_data['pa_sold_policy_end_date'] : '0000-00-00 00:00:00';
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
              <td><b>Plan Amount(Rs.)</b></td>
              <td>{$plan_amount}</td>
            </tr>
            <tr>
              <td><b>Tax Amount(18% IGST in Rs.)</b></td>
              <td>{$gst_amount}</td>
            </tr>
            <tr>
              <td><b>Total Amount (Rs.)</b></td>
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
    $pdf->Output('Liberty RPA Policy.pdf', 'I');

}

function LibertyGeneral_30_10_2019($id){
  $rsa_policy_data = $this->Home_Model->getPolicyById($id);
  // echo "<pre>"; print_r($rsa_policy_data); echo "</pre>"; die('end of line yoyo');
  $where = array(
    'master_policy_no' =>$rsa_policy_data['master_policy_no'] 
  );
  $master_policy_details = $this->Home_Model->getRowDataFromTable('ic_pa_master_policy_nos',$where);
  $master_policy_location = $master_policy_details['mp_localtion'];
  $master_policy_address = $master_policy_details['address'];
  $getDealerInfo = $this->Home_Model->getDealerinfo($rsa_policy_data['user_id']);
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
  $plan_id = isset($rsa_policy_data['plan_id']) ? $rsa_policy_data['plan_id'] : '--';
  $where = array(
    'id'=>$plan_id
  );
  $plan_detalis = $this->Home_Model->getDataFromTable('tvs_plans',$where);
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
  $master_policy_no = isset($rsa_policy_data['master_policy_no']) ? strtoupper($rsa_policy_data['master_policy_no']) : ' ';
  // $master_policy_no = '4112-400401-19-7000834-00-000';
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
                    <td width="50%"><b>certificate Number</b></td>
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
              <td><b>Plan Amount(Rs.)</b></td>
              <td>{$plan_amount}</td>
            </tr>
            <tr>
              <td><b>Tax Amount(18% IGST in Rs.)</b></td>
              <td>{$gst_amount}</td>
            </tr>
            <tr>
              <td><b>Total Amount (Rs.)</b></td>
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
                        <td>{$master_policy_address}</td>
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
                      <td colspan="2" class="textcenter line-height-16">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="45%"><b>Name of Master Policy Holder: </b></td>
                      <td width="55%">M/S INDICOSMIC PVT. LTD.</td>
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
                <td>Worldwide</td>
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
                <td>1.Accidental Death (AD) -Covers Death due to Accident 2. Permanent Total Disability (PTD)-Covers Permanent Total Disability due to Accident, which totally disables the Insured from attending to any occupation/job/business or normal duties for a continuous period of 12 months 3. Any form of Nuclear, Chemical and biological Terrorism is excluded 4. 24*7 cover 5.The cover would be valid only when the insured is riding the TW vehicle on which the CPA is given 6.India Cover Only as motor policy is valid within India.</td>
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
                      <td width="55%">1201970003138</td>
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
                      <td>MUMBAI 1</td>
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
                <td>Consolidated Stamp duty has been paid as per letter of Authorization no. CSD/05/2019/1892/19 dated 23/04/2019 issued by Main Stamp Office, Mumbai. ** Not Applicable for the State of Jammu & Kashmir. </td>
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
    $pdf->Output('Liberty RPA Policy.pdf', 'I');

}

function ApologyLetterPDF($policyid,$serial_no){
$serial_no = $this->uri->segment(3);
$policy_data = $this->Home_Model->getDataOfAppology($policyid);
$sold_policy_no = $policy_data['sold_policy_no'];
$customer_name = $policy_data['customer_name'];
$address = $policy_data['addr1'].' '.$policy_data['addr1'].' '.$policy_data['state_name'].' '.$policy_data['city_name'].' '.$policy_data['pincode'];
$sold_policy_effective_date = $policy_data['sold_policy_effective_date'];
$sold_policy_end_date = $policy_data['sold_policy_end_date'];
$sold_policy_end_date = $policy_data['sold_policy_end_date'];
$pa_sold_policy_effective_date = $policy_data['pa_sold_policy_effective_date'];
$pa_sold_policy_end_date = $policy_data['pa_sold_policy_end_date'];
$current_date = date('d/M/Y');
$this->load->library('Tcpdf/Tcpdf.php');
ob_start();
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Apology letter 2yr RSA 2yr PA');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(22, 20, 22);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

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
    $pdf->AddPage();
$html = <<<EOD
        
<style>
  .pagewrap {color: #000; font-size: 9pt; line-height:14pt; color:#000;}
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
</style>

<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="8" border="0" cellspacing="0" class="line-height-13">       
        <tr>
          <td colspan="2"><b>Date: </b>{$current_date}</td>
        </tr>
        <tr>
          <td colspan="2" height="80"></td>
        </tr>
        <tr>
          <td colspan="2"><b>Mr. / Mrs. / Ms. </b> {$customer_name}</td>
        </tr>
        <tr>
          <td><b>Address: </b>{$address} </td>
          <td></td>
        </tr>
      </table>
      <table cellpadding="8" border="0" cellspacing="0">
        <tr>
          <td width="11%"><b>Subject:</b></td>
          <td width="89%"><b>Apology for the incorrect period printed on Personal Accident (PA) Insurance Certificate no. {$sold_policy_no}  ($serial_no)</b></td>
        </tr>
        <tr>
          <td colspan="2" height="10"></td>
        </tr>
      </table>
      <table cellpadding="8" border="0" cellspacing="0">
        <tr>
          <td>Dear Sir / Ma’am</td>
        </tr>
        <tr>
          <td>We have issued the RSA certificates for a period of 2 years from <b>{$sold_policy_effective_date}</b> to <b>{$sold_policy_end_date}</b>. The complimentary Personal Accident Insurance cover offered was erroneously issued for the same period instead of one year.</td>
        </tr>
        <tr>
          <td>We now request you to please take note that the correct period of PA Insurance Cover is for 1 year from <b>{$pa_sold_policy_effective_date}</b> to <b>{$pa_sold_policy_end_date}</b>.</td>
        </tr>
        <tr>
          <td>We apologies for the inconvenience caused to you; due to a technical error.</td>
        </tr>
        <tr>
          <td>Certificate with necessary corrections is attached herewith.</td>
        </tr>
        <tr>
          <td><b>Note:</b> Your acceptance of registered postal AD confirms you have accepted necessary changes and surrendered the incorrect certificate which is null and void.</td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td>Thanking You</td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td><img src="assets/images/mpdf/amit_deep_sign.jpeg" height="70px"></td>
        </tr>
        <tr>
          <td>Yours Faithful</td>
        </tr>
        <tr>
          <td><b>Amit Deep <br>(COO)</b></td>
        </tr>
      </table>  
      </td>     
  </tr>
</table>

EOD;
    $pdf->writeHTML($html, true, 0, true, 0, '');

    // ---------------------------------------------------------

    //Close and output PDF document
    $pdf->Output('Apology_pdf.pdf', 'I');
}


function ApologyLetterPDFold($policyid){
$policy_data = $this->Home_Model->getDataOfAppology($policyid);
$sold_policy_no = $policy_data['sold_policy_no'];
$customer_name = $policy_data['customer_name'];
$address = $policy_data['addr1'].' '.$policy_data['addr1'].' '.$policy_data['state_name'].' '.$policy_data['city_name'].' '.$policy_data['pincode'];
$sold_policy_effective_date = $policy_data['sold_policy_effective_date'];
$sold_policy_end_date = $policy_data['sold_policy_end_date'];
$sold_policy_end_date = $policy_data['sold_policy_end_date'];
$pa_sold_policy_effective_date = $policy_data['pa_sold_policy_effective_date'];
$pa_sold_policy_end_date = $policy_data['pa_sold_policy_end_date'];
$current_date = date('d/M/Y');
$this->load->library('Tcpdf/Tcpdf.php');
ob_start();
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Apology letter 2yr RSA 2yr PA');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(22, 20, 22);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

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
    $pdf->AddPage();
$html = <<<EOD
        
<style>
  .pagewrap {color: #000; font-size: 9pt; line-height:14pt; color:#000;}
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
</style>

<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="8" border="0" cellspacing="0" class="line-height-13">       
        <tr>
          <td colspan="2"><b>Date: </b>{$current_date}</td>
        </tr>
        <tr>
          <td colspan="2" height="80"></td>
        </tr>
        <tr>
          <td colspan="2"><b>Mr. / Mrs. / Ms. </b> {$customer_name}</td>
        </tr>
        <tr>
          <td><b>Address: </b>{$address} </td>
          <td></td>
        </tr>
      </table>
      <table cellpadding="8" border="0" cellspacing="0">
        <tr>
          <td width="11%"><b>Subject:</b></td>
          <td width="89%"><b>Apology for the incorrect period printed on Personal Accident (PA) Insurance Certificate no. {$sold_policy_no}</b></td>
        </tr>
        <tr>
          <td colspan="2" height="10"></td>
        </tr>
      </table>
      <table cellpadding="8" border="0" cellspacing="0">
        <tr>
          <td>Dear Sir / Ma’am</td>
        </tr>
        <tr>
          <td>We have issued the RSA certificates for a period of 2 years from <b>{$sold_policy_effective_date}</b> to <b>{$sold_policy_end_date}</b>. The complimentary Personal Accident Insurance cover offered was erroneously issued for the same period instead of one year.</td>
        </tr>
        <tr>
          <td>We now request you to please take note that the correct period of PA Insurance Cover is for 1 year from <b>{$pa_sold_policy_effective_date}</b> to <b>{$pa_sold_policy_end_date}</b>.</td>
        </tr>
        <tr>
          <td>We apologies for the inconvenience caused to you; due to a technical error.</td>
        </tr>
        <tr>
          <td>Certificate with necessary corrections is attached herewith.</td>
        </tr>
        <tr>
          <td><b>Note:</b> Your acceptance of registered postal AD confirms you have accepted necessary changes and surrendered the incorrect certificate which is null and void.</td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td>Thanking You</td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td><img src="assets/images/mpdf/amit_deep_sign.jpeg" height="70px"></td>
        </tr>
        <tr>
          <td>Yours Faithful</td>
        </tr>
        <tr>
          <td><b>Amit Deep <br>(COO)</b></td>
        </tr>
      </table>  
      </td>     
  </tr>
</table>
<br pagebreak="true">
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="8" border="0" cellspacing="0" class="line-height-13"> 
        <tr>
          <td width="10%"></td>
          <td width="60%" height="270"></td>
          <td width="30%"></td>
        </tr>
        <tr>
        <td width="15%"></td>
          <td><b>To:-</b></td>
          <td></td>
        </tr>
        <tr>
        <td></td>
          <td><b>Mr. / Mrs. / Ms. </b> {$customer_name}</td>
          <td></td>
        </tr>
        <tr>
        <td></td>
          <td><b>Address: </b>{$address} </td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td class="textcenter">{$policyid}</td>
          <td></td>
        </tr>
      </table>
      </td>     
  </tr>
</table>
EOD;
    $pdf->writeHTML($html, true, 0, true, 0, '');

    // ---------------------------------------------------------

    //Close and output PDF document
    $pdf->Output('Apology_pdf.pdf', 'I');
}


function SerialwiseApologyLetterPDF($policyid)
{
$policy_data = $this->Home_Model->getDataOfAppology($policyid);
$sold_policy_no = $policy_data['sold_policy_no'];
$customer_name = $policy_data['customer_name'];
$address = $policy_data['addr1'].' '.$policy_data['addr1'].' '.$policy_data['state_name'].' '.$policy_data['city_name'].' '.$policy_data['pincode'];
$sold_policy_effective_date = $policy_data['sold_policy_effective_date'];
$sold_policy_end_date = $policy_data['sold_policy_end_date'];
$sold_policy_end_date = $policy_data['sold_policy_end_date'];
$pa_sold_policy_effective_date = $policy_data['pa_sold_policy_effective_date'];
$pa_sold_policy_end_date = $policy_data['pa_sold_policy_end_date'];
$current_date = date('d/M/Y');
$this->load->library('Tcpdf/Tcpdf.php');
ob_start();
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Apology letter 2yr RSA 2yr PA');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(20, 20, 20);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

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
    $pdf->AddPage();
$html = <<<EOD
        
<style>
  .pagewrap {color: #000; font-size: 9pt; line-height:14pt; color:#000;}
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
</style>

<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="8" border="0" cellspacing="0" class="line-height-13">       
        <tr>
          <td colspan="2"><b>Date: </b>{$current_date}</td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2"><b>Mr. / Mrs. / Ms. </b> {$customer_name}</td>
        </tr>
        <tr>
          <td><b>Address: </b>{$address} </td>
          <td></td>
        </tr>
      </table>
      <table cellpadding="8" border="0" cellspacing="0">
        <tr>
          <td colspan="2" height="10"></td>
        </tr>
        <tr>
          <td width="11%"><b>Subject:</b></td>
          <td width="89%"><b>Apology for the incorrect period printed on Personal Accident (PA) Insurance Certificate no. {$sold_policy_no}</b></td>
        </tr>
        <tr>
          <td colspan="2" height="10"></td>
        </tr>
      </table>
      <table cellpadding="8" border="0" cellspacing="0">
        <tr>
          <td>Dear Sir / Ma’am</td>
        </tr>
        <tr>
          <td>We have issued the RSA certificates for a period of 2 years from <b>{$sold_policy_effective_date}</b> to <b>{$sold_policy_end_date}</b>. The complimentary Personal Accident Insurance cover offered was erroneously issued for the same period instead of one year.</td>
        </tr>
        <tr>
          <td>We now request you to please take note that the correct period of PA Insurance Cover is for 1 year from <b>{$pa_sold_policy_effective_date}</b> to <b>{$pa_sold_policy_end_date}</b>.</td>
        </tr>
        <tr>
          <td>We apologies for the inconvenience caused to you; due to a technical error.</td>
        </tr>
        <tr>
          <td>Certificate with necessary corrections is attached herewith.</td>
        </tr>
        <tr>
          <td><b>Note:</b> Your acceptance of registered postal AD confirms you have accepted necessary changes and surrendered the incorrect certificate which is null and void.</td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td>Thanking You</td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td><img src="assets/images/mpdf/amit_deep_sign.jpeg" height="70px"></td>
        </tr>
        <tr>
          <td>Yours Faithful</td>
        </tr>
        <tr>
          <td><b>Amit Deep <br>(COO)</b></td>
        </tr>
      </table>  
      </td>     
  </tr>
</table>
<br pagebreak="true">
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
  <tr>
    <td>
      <table cellpadding="8" border="0" cellspacing="0" class="line-height-13"> 
        <tr>
          <td width="25%"></td>
          <td width="75%" height="250"></td>
        </tr>
        <tr>
        <td width="25%"></td>
          <td><b>To:-</b></td>
        </tr>
        <tr>
        <td width="25%"></td>
          <td><b>Mr. / Mrs. / Ms. </b> {$customer_name}</td>
        </tr>
        <tr>
        <td width="25%"></td>
          <td><b>Address: </b>{$address} </td>
        </tr>
        <tr>
          <td width="25%"></td>
          <td width="75%" height="250"></td>
        </tr>
        <tr>
          <td width="25%"></td>
          <td width="75%" height="100">{$policyid}</td>
        </tr>
      </table>
      </td>     
  </tr>
</table>
EOD;
    $pdf->writeHTML($html, true, 0, true, 0, '');

    // ---------------------------------------------------------

    //Close and output PDF document
    $pdf->Output('Apology_pdf.pdf', 'I');
}

function AllApologyLetterPDF(){
  $sapphire_data = $this->Home_Model->getAllSapphirePolicyTill30();
  // echo "<pre>"; print_r($sapphire_data); echo "</pre>"; die('end of line yoyo');
    foreach ($sapphire_data as $value) {
      $sold_policy_no = $value['sold_policy_no'];
      $customer_name = $value['customer_name'];
      $address = $value['addr1'].' '.$value['addr1'].' '.$value['state_name'].' '.$value['city_name'].' '.$value['pincode'];
      $sold_policy_effective_date = $value['sold_policy_effective_date'];
      $sold_policy_end_date = $value['sold_policy_end_date'];
      $sold_policy_end_date = $value['sold_policy_end_date'];
      $pa_sold_policy_effective_date = $value['pa_sold_policy_effective_date'];
      $pa_sold_policy_end_date = $value['pa_sold_policy_end_date'];
      $current_date = date('d/M/Y');
      $this->load->library('Tcpdf/Tcpdf.php');
ob_start();
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Apology letter 2yr RSA 2yr PA');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(20, 20, 20);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

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
    $pdf->AddPage();
    $html .= <<<EOD
      <style>
    .pagewrap {color: #000; font-size: 9pt; line-height:14pt; color:#000;}
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
  </style>

  <table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
    <tr>
      <td>
        <table cellpadding="8" border="0" cellspacing="0" class="line-height-13">       
          <tr>
            <td colspan="2"><b>Date: </b>{$current_date}</td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td colspan="2"><b>Mr. / Mrs. / Ms. </b> {$customer_name}</td>
          </tr>
          <tr>
            <td><b>Address: </b>{$address} </td>
            <td></td>
          </tr>
        </table>
        <table cellpadding="8" border="0" cellspacing="0">
          <tr>
            <td colspan="2" height="10"></td>
          </tr>
          <tr>
            <td width="11%"><b>Subject:</b></td>
            <td width="89%"><b>Apology for the incorrect period printed on Personal Accident (PA) Insurance Certificate no. {$sold_policy_no}</b></td>
          </tr>
          <tr>
            <td colspan="2" height="10"></td>
          </tr>
        </table>
        <table cellpadding="8" border="0" cellspacing="0">
          <tr>
            <td>Dear Sir / Ma’am</td>
          </tr>
          <tr>
            <td>We have issued the RSA certificates for a period of 2 years from <b>{$sold_policy_effective_date}</b> to <b>{$sold_policy_end_date}</b>. The complimentary Personal Accident Insurance cover offered was erroneously issued for the same period instead of one year.</td>
          </tr>
          <tr>
            <td>We now request you to please take note that the correct period of PA Insurance Cover is for 1 year from <b>{$pa_sold_policy_effective_date}</b> to <b>{$pa_sold_policy_end_date}</b>.</td>
          </tr>
          <tr>
            <td>We apologies for the inconvenience caused to you; due to a technical error.</td>
          </tr>
          <tr>
            <td>Certificate with necessary corrections is attached herewith.</td>
          </tr>
          <tr>
            <td><b>Note:</b> Your acceptance of registered postal AD confirms you have accepted necessary changes and surrendered the incorrect certificate which is null and void.</td>
          </tr>
          <tr>
            <td></td>
          </tr>
          <tr>
            <td>Thanking You</td>
          </tr>
          <tr>
            <td></td>
          </tr>
          <tr>
            <td><img src="assets/images/mpdf/amit_deep_sign.jpeg" height="70px"></td>
          </tr>
          <tr>
            <td>Yours Faithful</td>
          </tr>
          <tr>
            <td><b>Amit Deep <br>(COO)</b></td>
          </tr>
        </table>  
        </td>     
    </tr>
  </table>
  <br pagebreak="true">
  <table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
    <tr>
      <td>
        <table cellpadding="8" border="0" cellspacing="0" class="line-height-13"> 
          <tr>
            <td width="25%"></td>
            <td width="75%" height="250"></td>
          </tr>
          <tr>
          <td width="25%"></td>
            <td><b>To:-</b></td>
          </tr>
          <tr>
          <td width="25%"></td>
            <td><b>Mr. / Mrs. / Ms. </b> {$customer_name}</td>
          </tr>
          <tr>
          <td width="25%"></td>
            <td><b>Address: </b>{$address} </td>
          </tr>
        </table>
        </td>     
    </tr>
  </table>
  <br pagebreak="true" />
EOD;

      
    }


$pdf->writeHTML($html, true, 0, true, 0, '');

    // ---------------------------------------------------------

    //Close and output PDF document
$pdf->Output('Apology_pdf.pdf', 'I');
}

}
