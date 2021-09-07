<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Dashboard extends CI_Controller {

    public function __construct() {

      //  echo "innn"; exit;
        parent::__construct();
        $this->load->library('database_library');
        $this->load->model('Home_Model');
        $this->load->helper('common_helper');
        // isUserLoggedIn();
        /*       $this ->checkLogin(); */
    }

    /*
      public function checkLogin(){
      if($this->session->has_userdata('user_session')){
      redirect(base_url().'myaccount');
      }else{
      redirect(base_url());
      }


      } */

    public function logout() {
        $this->session->unset_userdata('customer_and_vehicle_details');
        $this->session->unset_userdata('user_session');
        $this->session->unset_userdata('vehicle_plan_list');
        $this->session->unset_userdata('sold_policy_details');
        $this->session->unset_userdata('sold_policy_details_id');
        $this->session->unset_userdata('selected_plan');
        $this->session->unset_userdata('payment_detail');
        $this->session->unset_userdata('cus_selected_plan');
        $this->session->unset_userdata('payment_detail_id');
        $this->session->unset_userdata('customer_id');
        $this->session->unset_userdata('policy_details');
        $this->session->unset_userdata('claim_details');
        $this->session->unset_userdata('dealer_estimation');
        $this->session->unset_userdata('dealer_upload_img');
        $this->session->unset_userdata('claim_customer_id');
        redirect(base_url());
    }

    public function index() {
        $user_session = $this->session->userdata('user_session');
        //print_r($user_session);die('annu');
        $dealer_id = $user_session['id'];
        $dealer_code = $user_session['sap_ad_code'];
        if(empty($user_session['gst_no'])){
            redirect('dealer_form');
        }

        $dealer_wallet=$this->Home_Model->getDealerWallet($dealer_id);
        
        $balance=$dealer_wallet['security_amount']-$dealer_wallet['credit_amount'];
        if($balance<1000){
            $wallet_popup = 'true';
        }else{
            $wallet_popup = 'false';
        }

        $this->data['top_three_dealers'] = $top_three_dealers = $this->Home_Model->topThreeDealers();
        //$todays_policies_dealers = $this->Home_Model->getTodaysPoliciesDealers();
         //$this->data['todays_policies_dealers']=($todays_policies_dealers)?$todays_policies_dealers:array();
        // echo "<pre>"; print_r($todays_policies_dealers); echo "</pre>"; die('end of line yoyo');
        $popup_status = 'false';
        //$todays_policies_dealers = $this->Home_Model->getTodaysPoliciesDealers();
         //$this->data['todays_policies_dealers']=($todays_policies_dealers)?$todays_policies_dealers:array();
        $policy_detail = $this->Home_Model->getsold_policy_data($dealer_id);
        // echo "<pre>"; print_r($policy_detail); echo "</pre>"; die('end of line yoyo');
        if(!empty($policy_detail)){
            $invoice_data = $this->Home_Model->getInvoiceData($dealer_id);
            if(empty($invoice_data)){
                $popup_status = 'true';
            }
        }else{
            $popup_status = 'false';
        }

        $dealer_confirm = $this->Home_Model->getConfirmedOriental($dealer_id);

        $dealer_confirmation = $this->Home_Model->getConfirmedOriental($dealer_id);
        if(!empty($dealer_confirmation)){
            $dealer_confirm= 'true';
        }else{
            $dealer_confirm= 'false';
        }
        $where = array('dealer_code'=>$dealer_code);
        $exist_data = $this->Home_Model->getRowDataFromTable('dealer_campaign_list',$where);
        if(!empty($exist_data) || (strlen($dealer_code) >5) ){
            $dealer_campaign_status = 'true';
        }else{
            $dealer_campaign_status = 'false';
        }
        $this->data['popup_status'] = $popup_status;
        $this->data['dealer_code'] = $dealer_code;
        $this->data['wallet_popup'] = $wallet_popup;
        $this->data['balance'] = $balance; 
        $this->data['dealer_confirm'] = $dealer_confirm;
        $this->data['dealer_campaign_status'] = $dealer_campaign_status;
        $this->session->unset_userdata('customer_and_vehicle_details');
        $this->session->unset_userdata('vehicle_plan_list');
        $this->session->unset_userdata('sold_policy_details');
        $this->session->unset_userdata('sold_policy_details_id');
        $this->session->unset_userdata('selected_plan');
        $this->session->unset_userdata('payment_detail');
        $this->session->unset_userdata('cus_selected_plan');
        $this->session->unset_userdata('payment_detail_id');
        $this->session->unset_userdata('customer_id');
        $this->session->unset_userdata('policy_details');
        $this->session->unset_userdata('claim_details');
        $this->session->unset_userdata('dealer_estimation');
        $this->session->unset_userdata('dealer_upload_img');
        $this->session->unset_userdata('claim_customer_id');
        $this->session->unset_userdata('new_odometer');
        $this->load->dashboardTemplate('front/myaccount/dashboard',$this->data);
    }

    public function SoldPolicy() {
        $this->load->dashboardTemplate('front/dashboard/sold_policy');
    }

    public function SoldPolicyAjax() {
        $requestData = $_REQUEST;

        $columns = array(
            0 => 'policy_no',
            1 => 'first_name',
            2 => 'email',
            3 => 'mobile_no',
            4 => 'registration_date',
            5 => 'vehicle_type',
        );
        // $query = $this->db->query("SELECT a1.*,b1.*,c1.* FROM hc_sold_policies  a1 INNER JOIN hc_customers b1 ON b1.id= a1.customer_id INNER JOIN hc_payment_details c1 ON a1.id= c1.sold_id");




        $sql = "SELECT a1.*,b1.*,c1.* FROM hc_sold_policies  a1 INNER JOIN hc_customers b1 ON b1.id= a1.customer_id INNER JOIN hc_payment_details c1 ON a1.id= c1.sold_id";

        // $query = $this->db->query($sql);
        // $totalData = $query->num_rows();
        if (!empty($requestData['search']['value'])) {
            $sql .= " and  policy_no LIKE '" . $requestData['search']['value'] . "%'  or first_name like '" . $requestData['search']['value'] . "%' or email like '" . $requestData['search']['value'] . "%' or mobile_no like '" . $requestData['search']['value'] . "%' or registration_date like '" . $requestData['search']['value'] . "%' or vehicle_type like '" . $requestData['search']['value'] . "%' ";
        }

        $totalFiltered = $totalData;
        $sql = "$sql AND  1 = 1";


        $sql .= "  group by a1.id ORDER BY a1.id DESC";

        $query = $this->db->query($sql);
        // echo $this->db->last_query('query');die;
        $totalFiltered = $query->num_rows();
        $result = $query->result();
        // print_r($result);die;
        // echo $totalFiltered;die;


        $data = array();
        $i = 1;
        foreach ($result as $main) {
            $row = array();
            $row[] = $i++;
            $row[] = $main->policy_no;
            $row[] = $main->first_name . ' ' . $main->last_name;
            $row[] = $main->email;
            $row[] = $main->mobile_no;
            $row[] = $main->registration_date;
            $row[] = $main->vehicle_type;
            $file_list = ($main->vehicle_type == 'NEW') ? '<a href="' . base_url() . 'download-policy/' . $main->id . '" class="btn btn-info">Download Pdf</a>' : '<a href="' . base_url() . 'download-policys/' . $main->id . '" class="btn btn-info">Download Pdf</a>';
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

    public function ClaimPolicyMaster() {
        $this->load->dashboardTemplate('front/dashboard/claim_policy');
    }

    public function ClaimPolicyAjax() {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'policy_no',
            1 => 'claim_no',
            2 => 'name',
            3 => 'email',
            4 => 'mobile_no',
            5 => 'registration_date',
            6 => 'vehicle_type',
        );

        $sql = "SELECT * FROM claim_customer_detail ";

        $query = $this->db->query($sql);
        $result = $query->result();


        $totalData = $query->num_rows();

        $data = array();
        $i = 1;
        foreach ($result as $main) {

            $customer_data = json_decode($main->claim_json_data);

            $row = array();
            $row[] = $i++;
            $row[] = $main->policy_no;
            $row[] = $main->claim_no;
            $row[] = $customer_data->policy_details->first_name . ' ' . $customer_data->policy_details->last_name;
            $row[] = $customer_data->policy_details->email;
            $row[] = $customer_data->policy_details->mobile_no;
            $row[] = $customer_data->policy_details->registration_date;
            $row[] = $customer_data->policy_details->vehicle_type;
            $file_list = '<a href="' . base_url() . 'claim_management/' . urlencode($main->claim_no) . '" class="btn btn-info">View</a>';
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

    function setManufacturingMonth() {
        $vehicle_type = $this->input->post('vehicle_type');
        $manufacturing_year = ($vehicle_type == 'NEW') ? date("Y") : '';
        $selected_manufacturing_month = $this->input->post('selected_manufacturin_month');
        $selected_manufacturing_month = isset($selected_manufacturing_month) ? $selected_manufacturing_month : '';
        $month_array = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'];
        $this_year_month = array();
        if ($manufacturing_year == date("Y")) {
            foreach ($month_array as $key => $month) {
                $this_year_month[$month] = $month;
                if ($key == date("m")) {
                    break;
                }
            }
        }
        $months = !empty($this_year_month) ? $this_year_month : $month_array;
        $html = '';
        $selected = '';
        foreach ($months as $key => $month) {
            $selected = ($key == $selected_manufacturing_month) ? 'selected ="selected"' : '';
            $html .= <<<EOD
                    <option value="{$key}" {$selected}>{$month}</option>
EOD;
        }

        echo $html;
    }

    public function extended_warranty() {
        $data = array();

        $data['state'] = $this->Home_Model->fetchState();
        // echo "<pre>"; print_r($data);die;
        $this->load->dashboardTemplate('front/myaccount/extended_warranty', $data);
    }

    public function claim_management($claim_no = null) {
        $where['part_category'] = 1;
        $this->data['part_details'] = $part_details = $this->Home_Model->getDataFromTable('part_master', $where);
        $this->data['claim_no'] = $claim_no;
        if(!empty($claim_no)){
         $where = array('claim_no' => $claim_no);
        $this->data['claim_data'] = $this->Home_Model->getDataFromTable('claim_customer_detail', $where);
        // echo "<pre>"; print_r($this->data['claim_data'][0]['id']);die('yoyo asdfasd');
        $this->data['policy_number'] = $this->data['claim_data'][0]['policy_no'];
        $this->data['claim_json_decode'] = json_decode($this->data['claim_data'][0]['claim_json_data'], true);

        $this->data['policy_details'] = $this->data['claim_json_decode']['policy_details'];
        // echo "<pre>";print_r($this->data['policy_details']);die;

        if(!empty($this->data['claim_json_decode'])){
            $_SESSION['claim_customer_id'] = $this->data['claim_data'][0]['id'];
            $this->session->set_userdata('dealer_estimation', $this->data['claim_json_decode']['dealer_estimation']);
            $this->session->set_userdata('dealer_upload_img', $this->data['claim_json_decode']['dealer_upload_img']);
            $this->session->set_userdata('policy_details', $this->data['policy_details']);
            $_SESSION['claim_details']['new_odometer'] = $this->data['claim_data'][0]['new_odometer'];
            }
              
        }
        

        $this->data['makes'] = $this->getManufacturers($this->data['policy_details']->product_type, $this->data['policy_details']->vehicle_type);
        $this->data['models'] = $this->getModels($this->data['policy_details']->manufacturer, $this->data['policy_details']->vehicle_type);
        $this->data['states'] = $this->Home_Model->fetchState();
        $this->claimed_state = explode('-', $this->data['policy_details']['state']);

        $where = array('state_id_pk' => $this->claimed_state[0]);
        $this->data['cities'] = $this->Home_Model->fetchCities($where);

// echo"<pre>"; print_r($this->data['states']);die;
        // $this->session->set_userdata('policy_details',$this->data['policy_details']);
        // $this->session->set_userdata('dealer_estimation',$this->data['dealer_estimation']);
        // $this->session->set_userdata('dealer_upload_img',$this->data['dealer_upload_img']);
        // die('claim_no');
        // echo "<pre>";print_r($this->data['dealer_upload_img']);die;
        $this->load->dashboardTemplate('front/myaccount/claim_management', $this->data);
    }

    public function checkExistClaim() {
        $policy_no = $this->input->post('vehicle_details');
        $where = array(
            'policy_no' => $policy_no
        );
        $check_claim_no = $this->Home_Model->checkClaimData($where);
        // print_r($check_claim_no);die;

        $data['check_claim'] = ($check_claim_no[0]->claim_no) ? true : false;
        echo json_encode($data);
    }

    public function fetch_policy_detail() {
        $this->load->model('Home_Model', 'homeMdl');
        $policy_no = $this->input->post('vehicle_details');
        // echo "<pre>";print_r($policy_no);die('policyno');
        $policy_details = $this->homeMdl->getPolicyDetailByPolicyNo($policy_no);
        $where = array('policy_no' => $policy_no);
        // echo "<pre>";print_r($policy_details[0]['pincode']);die;

        $check_claim_no = $this->Home_Model->checkClaimData($where);
        // print_r($check_claim_no);die;

        $return_data['status'] = ($check_claim_no[0]->claim_no) ? true : false;

        $policy_details = $policy_details[0];

        $html = '';
        if ($return_data['status'] == true) {
            $claim_json_data = json_decode($check_claim_no[0]->claim_json_data, true);
            // echo "<pre>"; print_r($claim_json_data['dealer_upload_img']);die('sbb');
            $_SESSION['claim_customer_id'] = $check_claim_no[0]->id;
            $this->session->set_userdata('dealer_estimation', $claim_json_data['dealer_estimation']);
            $this->session->set_userdata('dealer_upload_img', $claim_json_data['dealer_upload_img']);
            $_SESSION['claim_details']['new_odometer'] = $check_claim_no[0]->new_odometer;
        }

        if (!empty($policy_details)) {
            $this->session->set_userdata('policy_details', $policy_details);
            switch ($policy_details['product_type']) {
                case 1:
                    $selected_product_type_1 = 'selected = "selected"';
                    break;
                case 2:
                    $selected_product_type_2 = 'selected = "selected"';
                    break;
                case 3:
                    $selected_product_type_3 = 'selected = "selected"';
                    break;
                default:
                    $selected_product_type_1 = '';
                    $selected_product_type_2 = '';
                    $selected_product_type_3 = '';
                    break;
            }
            $makes = $this->getManufacturers($policy_details['product_type'], $policy_details['vehicle_type']);
            $models = $this->getModels($policy_details['manufacturer'], $policy_details['vehicle_type']);
            $sel_state = isset($policy_details['state']) ? $policy_details['state'] : '';
            $claimed_state = explode('-', $sel_state);
            $states = $this->Home_Model->fetchState();
            $where = array('state_id_pk' => $claimed_state[0]);
            $cities = $this->Home_Model->fetchCities($where);
            // echo "<pre>";print_r($cities);die;
            $sel_city = isset($policy_details['city']) ? $policy_details['city'] : '';
            $selected_manuf_month = isset($policy_details['manufacturing_month']) ? $policy_details['manufacturing_month'] : '';
            $sel_make = isset($policy_details['manufacturer']) ? $policy_details['manufacturer'] : '';
            $sel_model = isset($policy_details['model']) ? $policy_details['model'] : '';
            $selected_year = isset($policy_details['manufacturing_year']) ? $policy_details['manufacturing_year'] : '';
            $selected_reg_date = isset($policy_details['registration_date']) ? $policy_details['registration_date'] : '';
            $registration_no = isset($policy_details['registration_no']) ? $policy_details['registration_no'] : '';
            $odometer_reading = isset($policy_details['odometer_reading']) ? $policy_details['odometer_reading'] : '';
            $adhar_no = isset($policy_details['adhar_card']) ? $policy_details['adhar_card'] : '';
            $pan_card = isset($policy_details['pan']) ? $policy_details['pan'] : '';
            $first_name = isset($policy_details['first_name']) ? $policy_details['first_name'] : '';
            $last_name = isset($policy_details['last_name']) ? $policy_details['last_name'] : '';
            $email = isset($policy_details['email']) ? $policy_details['email'] : '';
            $mobile_no = isset($policy_details['mobile_no']) ? $policy_details['mobile_no'] : '';
            $cust_addr1 = isset($policy_details['address_1']) ? $policy_details['address_1'] : '';
            $cust_addr2 = isset($policy_details['address_2']) ? $policy_details['address_2'] : '';
            $pincode = isset($policy_details['pincode']) ? $policy_details['pincode'] : '';
            $vehicle_type = isset($policy_details['vehicle_type']) ? $policy_details['vehicle_type'] : '';
            $selected_vehicle_type_new = ($policy_details['vehicle_type'] == 'NEW') ? 'selected = "selected"' : '';
            $selected_vehicle_type_old = ($policy_details['vehicle_type'] == 'OLD') ? 'selected = "selected"' : '';
            $engine_no = isset($policy_details['engine_no']) ? $policy_details['engine_no'] : '';
            $chassis_no = isset($policy_details['chassis_no']) ? $policy_details['chassis_no'] : '';
            $html .= <<<EX
                    <div class="col-md-6 nopadding">
                            <div class="form-group">
                            <label for="type" class="col-sm-5 control-label">Product Type
                                <span style="color:red" >*</span>
                            </label>
                                <div class="col-sm-7">
                                <select id="product_type" name="product_type" class="form-control cust_info_field" data-manufacturer="" data-model="" disabled="disabled">
                                  <option value="" >SELECT PRODUCT TYPE</option>
                                <option  value="1" {$selected_product_type_1}>TWO WHEELER</option>
                                <option  value="2" {$selected_product_type_2}>FOUR WHEELER</option>
                                <option  value="3" {$selected_product_type_3}>COMMERCIAL VEHICLE</option>
                                        </select>
                                   </div>
                                </div>
                            </div>
                             <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="type" class="col-sm-5 control-label">Vehicle Type
                                     <span style="color:red" >*</span></label>
                                    <div class="col-sm-7">
                                        <select name="vehicle_type" id="vehicle_type" class="form-control cust_info_field" disabled="disabled">
                                         <option value="" >SELECT VEHICLE TYPE</option>
                                          <option value="NEW"  {$selected_vehicle_type_new}>NEW</option>
                                          <option value="OLD"  {$selected_vehicle_type_old}>OLD</option>
                                      </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="engine_no" class="col-sm-5 control-label">Engine number
                                        <span style="color:red" >*</span>
                                    </label>
                                    <div class="col-sm-7">
                                        <input type="text" name="engine_no" id="engine_no" placeholder="ENGINE NO." class="form-control cust_info_field keypress_validation" value="{$engine_no}" data-regex="" autofocus style="text-transform:uppercase" readonly> </div>
                                </div>
                            </div>
                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="chassis_no" class="col-sm-5 control-label">Chassis Number
                                     <span style="color:red" >*</span>
                                 </label>
                                    <div class="col-sm-7">
                                        <input type="text" name="chassis_no" id="chassis_no" placeholder="CHASSIS NO." value="{$chassis_no}" class="form-control cust_info_field" autofocus style="text-transform:uppercase" readonly> </div>
                                </div>
                            </div>
                            <div class="col-md-6 nopadding">
                                <div class="form-group" id="manufacturer_cont">
                                    <label for="manufacturer" class="col-sm-5 control-label">Manufacturer
                                    <span style="color:red" >*</span></label>
                                    <div class="col-sm-7">
                                        <select name="manufacturer" id="manufacturer" class="form-control make cust_info_field" disabled="disabled">
EX;
            $selected_make = '';
            foreach ($makes as $make) {
                $make_name = $make['make'];
                $manufa = $make['id'] . '-' . $make['make'];
                $selected_make = ($manufa == $sel_make) ? 'selected = "selected"' : '';

                $html .= <<<EX
                                            <option value="{$manufa}" {$selected_make}>{$make_name}</option>
EX;
            }
            $html .= <<<EX
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 nopadding">
                                <div class="form-group" id="model_cont">
                                    <label for="model" class="col-sm-5 control-label">Model
                                        <span style="color:red" >*</span>
                                    </label>
                                    <div class="col-sm-7">
                                        <select name="model" id="model" class="form-control cust_info_field" disabled="disabled">
EX;
            $selected_model = '';
            foreach ($models as $model) {
                $model_name = $model['model'];
                $mode = $model['id'] . '-' . $model['model'];
                $show_model = $model['model'] . '(' . $model['fuel'] . ')';
                $selected_model = ($mode == $sel_model) ? 'selected = "selected"' : '';
                $html .= <<<EX
                                            <option value="{$mode}" {$selected_model}>{$show_model}</option>
EX;
            }
            $html .= <<<EX
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="manufacturin_month" class="col-sm-5 control-label">Manufacturing Month</label>
                                        <div class="col-sm-7">
                                        <select name="manufacturer" id="manufacturer" class="form-control make cust_info_field" disabled="disabled">
EX;

            $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            $selected_month = '';
            foreach ($months as $month) {
                $selected_month = ($month == $selected_manuf_month) ? 'selected="selected"' : '';
                $html .= <<<EX
                                                <option value="{$month}" {$selected_month}>{$month}</option>
EX;
            }
            $html .= <<<EX
                                        </select>
                                </div>
                                </div>
                            </div>

                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="manufacturin_year" class="col-sm-5 control-label">Manufacturing Year</label>
                                    <div class="col-sm-7">
                                        <select name="manufacturer" id="manufacturer" class="form-control make cust_info_field"  disabled ="disabled">
                                            <optgroup label="">
EX;

            $current_year = date("Y");
            $current_year = ($vehicle_type == 'NEW') ? $current_year : ($current_year - 1);
            $past_year = $current_year - 7;
            if ($vehicle_type == 'NEW') {
                $past_year = $current_year;
            }
            $year_list = range($past_year, $current_year);
            $selected = '';
            foreach ($year_list as $year) {
                if (!empty($selected_year)) {
                    $selected = ($year == $selected_year) ? 'selected ="selected"' : '';
                } else {
                    $selected = ($year == $current_year) ? 'selected ="selected"' : '';
                }
                $html .= <<<EX
                                                <option {$selected} value={$year}>{$year}</option>
EX;
            }
            $html .= <<<EX
                                            </optgroup>
                                    </select>
                                        </div>
                                </div>
                            </div>

                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="reg_date" class="col-sm-5 control-label">Registration Date</label>
                                    <div class="col-sm-7">
                                        <input maxlength="12" size="12" type="text" id="reg_date" name="reg_date" placeholder="E.g: MM/DD/YYYY" class="form-control cust_info_field datetimepicker" value="{$selected_reg_date}" autofocus readonly> </div>

                                </div>
                            </div>

                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="registration_no" class="col-sm-5 control-label">Registration no.
                                    <span style="color:red" >*</span>
                                </label>
                                    <div class="col-sm-7">
                                        <input  type="text" id="registration_no" name="registration_no" value="{$registration_no}" placeholder="Registration No. E.g(XX 01 XX 6831)" class="form-control cust_info_field" autofocus style="text-transform:uppercase" readonly> </div>
                                </div>
                            </div>

                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="odometer_reading" class="col-sm-5 control-label">Odometer Reading</label>
                                    <div class="col-sm-7">
                                        <input maxlength="12" size="12" type="text" id="odometer_reading" name="odometer_reading" placeholder="Odometer Reading" class="form-control cust_info_field" value="{$odometer_reading}" autofocus readonly> </div>
                                </div>
                            </div>

                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="aadhar_no" class="col-sm-5 control-label">Aadhaar number</label>
                                    <div class="col-sm-7">
                                        <input maxlength="12" size="12" type="text" id="aadhar_no" name="aadhar_no" placeholder="AADHAR NUMBER" class="form-control cust_info_field" value="{$adhar_no}" autofocus readonly> </div>
                                </div>
                            </div>
                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="pan_card" class="col-sm-5 control-label">PAN</label>
                                    <div class="col-sm-7">
                                        <input maxlength="10" size="10" type="text" id="pan_card" name="pan_card" placeholder="PAN No." class="form-control cust_info_field" value="{$pan_card}" autofocus style="text-transform:uppercase"/ readonly> </div>
                                </div>
                            </div>
                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="first_name" class="col-sm-5 control-label">First name
                                    <span style="color:red" >*</span>
                                </label>
                                    <div class="col-sm-7">
                                        <input type="text" id="first_name" value="{$first_name}" name="first_name" placeholder="FIRST NAME" class="form-control cust_info_field" autofocus style="text-transform:uppercase" readonly> </div>
                                </div>
                            </div>
                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="last_name" class="col-sm-5 control-label">Last Name
                                    <span style="color:red" >*</span>
                                </label>
                                    <div class="col-sm-7">
                                        <input type="text" id="last_name" name="last_name" placeholder="LAST NAME" value="{$last_name}" class="form-control cust_info_field" autofocus style="text-transform:uppercase" readonly> </div>
                                </div>
                            </div>
                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="email" class="col-sm-5 control-label">Email
                                    <span style="color:red" >*</span>
                                </label>
                                    <div class="col-sm-7">
                                        <input type="text" id="email" name="email" placeholder="EMAIL ID" class="form-control cust_info_field" value="{$email}" autofocus> </div>
                                </div>
                            </div>
                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="mobile_no" class="col-sm-5 control-label">Mobile number
                                    <span style="color:red" >*</span></label>
                                    <div class="col-sm-7">
                                        <input maxlength="10" size="10" type="text" id="mobile_no" name="mobile_no" placeholder="MOBILE NO." class="form-control cust_info_field" value="{$mobile_no}" autofocus> </div>
                                </div>
                            </div>
                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Address 1<span style="color:red" >*</span>
                                    </label>
                                    <div class="col-sm-7">
                                        <input type="text" id="cust_addr1" name="cust_addr1" placeholder="ADDRESS 1" class="form-control cust_info_field" value="{$cust_addr1}" autofocus style="text-transform:uppercase" readonly> </div>
                                </div>
                            </div>

                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Address 2</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="cust_addr2" name="cust_addr2" placeholder="ADDRESS 2" class="form-control cust_info_field" value="{$cust_addr2}" autofocus style="text-transform:uppercase" readonly> </div>
                                </div>
                                </div>

                               <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">State<span style="color:red" >*</span>
                                    </label>
                                    <div class="col-sm-7">
                                        <select id="state" name="state" class="form-control cust_info_field" data-city_selected="" autofocus disabled="disabled">
EX;
            if (!empty($states)) {
                foreach ($states as $state) {
                    $selected = '';
                    $value_state = $state->state_id_pk . '-' . $state->state_name;
                    if ($value_state == strtoupper($sel_state)) {
                        $selected = 'selected = "selected"';
                    }
                    $html .= <<<EX
                                            <option value="{$state->state_id_pk}-{$state->state_name}" {$selected}>{$state->state_name}</option>
EX;
                }
            }
            $html .= <<<EX

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">City<span style="color:red" >*</span></label>
                                    <div class="col-sm-7">
                                        <select id="city" name="city" class="form-control cust_info_field" disabled="disabled">
EX;
            if (!empty($cities)) {
                $selected = '';
                foreach ($cities as $city) {
                    $value_city = $city->city_or_village_id_pk . '-' . $city->city_or_village_name;
                    if ($value_state == $sel_state) {
                        $selected = 'selected = "selected"';
                    }
                    $html .= <<<EX
                                            <option value="{$value_city}" {$selected}>{$city->city_or_village_name}</option>
EX;
                }
            }
            $html .= <<<EX
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 nopadding">
                                <div class="form-group">
                                    <label for="pin" class="col-sm-5 control-label">Pin</label>
                                    <div class="col-sm-7">
                                        <input maxlength="6" size="6" type="text" id="pin" value="{$pincode}" name="pin"
                                        placeholder="ENTER PIN" class="form-control cust_info_field"
                                        value="" autofocus readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
EX;
        } else {
            $return_data['policy_data'] = 'no_data';
        }



        $return_data['html'] = $html;

        echo json_encode($return_data);
    }

    public function setSelectedPartData() {
        $part_id = $this->input->post('part_id');
        $where['id'] = $part_id;
        $part_detail = $this->Home_Model->getDataFromTable('part_master', $where);
        echo json_encode($part_detail[0]);
    }

    //set dealer estimation session data

    public function setDealerEstimationData() {

        if (!empty($this->input->post())) {
            $post_data = $this->input->post();
            $data = array();
            $count = count(array_keys($this->input->post('claimed_part')));
            for ($i = 0; $i < $count; $i++) {
                foreach ($post_data as $key => $value) {
                    $data[$i][$key] = $value[$i];
                }
            }
            $this->session->set_userdata('dealer_estimation', $data);
            $claim_customer_id = $this->session->userdata('claim_customer_id');
            $policy_details = $this->session->userdata('policy_details');
            $dealer_estimation = $this->session->userdata('dealer_estimation');
            $dealer_upload_img = $this->session->userdata('dealer_upload_img');
            $new_odo = $this->session->userdata('claim_details');

            $claim_data = array();
            if(!empty($claim_customer_id)){
            $claim_data['policy_details'] = $policy_details;
            $claim_data['dealer_estimation'] = $dealer_estimation;
            $claim_data['dealer_upload_img'] = $dealer_upload_img;
            $claim_data['new_odometer'] = $new_odo['new_odometer'];

            $policy_no = $policy_details['policy_no'];
            $claim_no = date('ymdHmsi');
            $array = array(
                'new_odometer' => $new_odo['new_odometer'],
                'policy_no' => $policy_no,
                'claim_no' => $claim_no,
                'claim_json_data' => json_encode($claim_data),
            );

            $this->db->where('id', $claim_customer_id);
            $update_data = $this->db->update('claim_customer_detail', $array);
            $id = (!empty($update_data)) ? true : false;
            }
            else{
            $this->session->set_userdata('dealer_estimation', $data);
            $id = (!empty($this->session->userdata('dealer_estimation'))) ? true : false;
            }

        }
        
        echo $id;
    }

    function setDealerEstimationUploadData() {

        $claim_customer_id = $this->session->userdata('claim_customer_id');
        $policy_details = $this->session->userdata('policy_details');
        $dealer_estimation = $this->session->userdata('dealer_estimation');
        $new_odo = $this->session->userdata('claim_details');

        if (empty($claim_customer_id)) {

            $data = array();
            foreach ($_FILES as $key => $file) {
                if (isset($_FILES[$key]["name"])) {
                    $config['upload_path'] = './uploads/claim_img/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config[$key] = $_FILES[$key]["name"];
                    $config['remove_spaces'] = TRUE;
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload', $config);
                    $uploadData = $this->upload->data();
                    $picture = $uploadData[$key];
                    if ($this->upload->do_upload($key)) {
                        $uploadData = $this->upload->data();
                        $picture = $uploadData['file_name'];
                        $post_name = explode('-', $key);
                        $data['text_data'][$key] = $_POST[$post_name[0]];
                        $data[$key] = $picture;
                    } else {
                        $picture = '';
                    }
                }
            }
            if (!empty($data)) {

                $this->session->set_userdata('dealer_upload_img', $data);
                $dealer_upload_img = $this->session->userdata('dealer_upload_img');
                $claim_data = array();
                $claim_data['policy_details'] = $policy_details;
                $claim_data['dealer_estimation'] = $dealer_estimation;
                $claim_data['dealer_upload_img'] = $dealer_upload_img;
                $claim_data['new_odometer'] = $new_odo['new_odometer'];

                $policy_no = $policy_details['policy_no'];
                $claim_no = date('ymdHmsi');
                $array = array(
                    'new_odometer' => $new_odo['new_odometer'],
                    'policy_no' => $policy_no,
                    'claim_no' => $claim_no,
                    'claim_json_data' => json_encode($claim_data),
                );

                $this->db->Insert('claim_customer_detail', $array);
                $claim_customer_id = $this->db->insert_id();
             // echo "<pre>";   print_r($array);die('claim_customer_id');

                $this->session->set_userdata('claim_customer_id', $claim_customer_id);
            }
        } elseif (!empty($claim_customer_id)) {
            // unlink('/var/www/test/folder/images/image_name.jpeg');
            $dealer_upload_img = $this->session->userdata('dealer_upload_img');

// print_r($dealer_upload_img);die;
            $data = array();
            foreach ($_FILES as $key => $file) {
                if (isset($_FILES[$key]["name"])) {
                    $config['upload_path'] = './uploads/claim_img/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config[$key] = $_FILES[$key]["name"];
                    $config['remove_spaces'] = TRUE;
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload', $config);
                    $uploadData = $this->upload->data();
                    $picture = $uploadData[$key];
                    if ($this->upload->do_upload($key)) {
                        $uploadData = $this->upload->data();
                        $picture = $uploadData['file_name'];
                        $post_name = explode('-', $key);
                        $data['text_data'][$key] = $_POST[$post_name[0]];
                        $data[$key] = $picture;
                    } else {
                        $picture = '';
                    }
                }
            }
            if (!empty($data)) {

                // $first_year = $_SERVER['DOCUMENT_ROOT'].'/mypolicynow.com/myewnow.com/uploads/claim_img/'.$dealer_upload_img['first_year-pdf'];
                // if(!empty($dealer_upload_img['first_year-pdf'])){
                // unlink($first_year);
                // unset($_SESSION['dealer_upload_img']['first_year-pdf']);
                // }
                //     $second_year = $_SERVER['DOCUMENT_ROOT'].'/mypolicynow.com/myewnow.com/uploads/claim_img/'.$dealer_upload_img['second_year-pdf'];
                //     if(!empty($dealer_upload_img['second_year-pdf'])){
                //     unlink($second_year);
                //     unset($_SESSION['dealer_upload_img']['second_year-pdf']);
                // }
                // $third_year = $_SERVER['DOCUMENT_ROOT'].'/mypolicynow.com/myewnow.com/uploads/claim_img/'.$dealer_upload_img['third_year-pdf'];
                // if(!empty($dealer_upload_img['third_year-pdf'])){
                // unlink($third_year);
                // unset($_SESSION['dealer_upload_img']['third_year-pdf']);
                // }
                // $first_code = $_SERVER['DOCUMENT_ROOT'].'/mypolicynow.com/myewnow.com/uploads/claim_img/'.$dealer_upload_img['first_code-pdf'];
                // if(!empty($dealer_upload_img['first_code-pdf'])){
                // unlink($first_code);
                // unset($_SESSION['dealer_upload_img']['first_code-pdf']);
                // }
                // $second_code = $_SERVER['DOCUMENT_ROOT'].'/mypolicynow.com/myewnow.com/uploads/claim_img/'.$dealer_upload_img['second_code-pdf'];
                // if(!empty($dealer_upload_img['second_code-pdf'])){
                // unlink($second_code);
                // unset($_SESSION['dealer_upload_img']['second_code-pdf']);
                // }
                // $third_code = $_SERVER['DOCUMENT_ROOT'].'/mypolicynow.com/myewnow.com/uploads/claim_img/'.$dealer_upload_img['third_code-pdf'];
                // if(!empty($dealer_upload_img['third_code-pdf'])){
                // unlink($third_code);
                // unset($_SESSION['dealer_upload_img']['third_code-pdf']);
                // }
                // $fourth_code_pdf = $_SERVER['DOCUMENT_ROOT'].'/mypolicynow.com/myewnow.com/uploads/claim_img/'.$dealer_upload_img['fourth_code_pdf'];
                // if(!empty($dealer_upload_img['fourth_code_pdf'])){
                // unlink($fourth_code_pdf);
                // unset($_SESSION['dealer_upload_img']['fourth_code_pdf']);
                // }
                // $fifth_code = $_SERVER['DOCUMENT_ROOT'].'/mypolicynow.com/myewnow.com/uploads/claim_img/'.$dealer_upload_img['fifth_code-pdf'];
                // if(!empty($dealer_upload_img['fifth_code-pdf'])){
                // unlink($fifth_code);
                // unset($_SESSION['dealer_upload_img']['fifth_code-pdf']);
                // }
                $this->session->set_userdata('dealer_upload_img', $data);
                $dealer_upload_img = $this->session->userdata('dealer_upload_img');

                $claim_data = array();
                $claim_data['policy_details'] = $policy_details;
                $claim_data['dealer_estimation'] = $dealer_estimation;
                $claim_data['dealer_upload_img'] = $dealer_upload_img;
                // print_r($claim_data);die('dd');
                $claim_data['new_odometer'] = $new_odo['new_odometer'];

                $policy_no = $policy_details['policy_no'];
                $claim_no = date('ymdHmsi');
                $array = array(
                    'new_odometer' => $new_odo['new_odometer'],
                    'policy_no' => $policy_no,
                    'claim_no' => $claim_no,
                    'claim_json_data' => json_encode($claim_data),
                );

                $this->db->where('id', $claim_customer_id);
                $update_data = $this->db->update('claim_customer_detail', $array);
            }
        }

        $data['message'] = (!empty($claim_customer_id)) ? 'Thank you for the payment. Your policy successfully created' : 'Error';

        $data['main_contain'] = 'front/home/success';
        $this->load->view('front/includes/template', $data);
    }

    public function setClaimData() {
        $post_data = $this->input->post();
        $return_data['status'] = 'false';
        if (!empty($post_data)) {
            $new_odometer = isset($post_data['new_odometer']) ? $post_data['new_odometer'] : '';
            $session_data['new_odometer'] = $new_odometer;
            $this->session->set_userdata('claim_details', $session_data);
            $return_data['status'] = 'true';
        }
        echo json_encode($return_data);
    }

    public function fetch_plan_details() {

        if (!empty($this->input->post())) {
            $this->session->set_userdata('customer_and_vehicle_details', $this->input->post());
            $odometer_reading = $this->input->post('odometer_reading');
            $vehicle_type = $this->input->post('vehicle_type');
        }
        $data = $this->session->userdata('customer_and_vehicle_details');
        if ($vehicle_type == 'OLD') {
            $km = " and km_start >=0 AND km_end >= $odometer_reading";
        }
        $manufacturer = $data['manufacturer'];
        $manu_facturer = explode('-', $manufacturer);
        $model = $data['model'];
        $vehicle_type = $data['vehicle_type'];

        $plan_data = $this->db->query("SELECT * FROM `make_model_rater` where make='$manu_facturer[1]' $km and vehicle_type like '%$vehicle_type%' GROUP BY vehicle_type")->result();
        if (!empty($plan_data)) {
            $this->session->set_userdata('vehicle_plan_list', $plan_data);
        } else {
            $this->data['failed'] = 'Plan Is not Available';
            $this->session->set_flashdata('failed', 'Plan Is not Available');
            redirect('extended_warranty');
        }

        if (!empty($this->input->post())) {

            $product_type = $this->input->post('product_type');
            $vehicle_type = $this->input->post('vehicle_type');
            $engine_no = $this->input->post('engine_no');
            $chassis_no = $this->input->post('chassis_no');
            $manufacturer = $this->input->post('manufacturer');
            $model = $this->input->post('model');
            $manufacturin_month = $this->input->post('manufacturin_month');
            $manufacturin_year = $this->input->post('manufacturin_year');
            $reg_date = $this->input->post('reg_date');
            $registration_no = $this->input->post('registration_no');
            $odometer_reading = $this->input->post('odometer_reading');
            $aadhar_no = $this->input->post('aadhar_no');
            $pan_card = $this->input->post('pan_card');
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $email = $this->input->post('email');
            $mobile_no = $this->input->post('mobile_no');
            $cust_addr1 = $this->input->post('cust_addr1');
            $cust_addr2 = $this->input->post('cust_addr2');
            $state = $this->input->post('state');
            $city = $this->input->post('city');
            $pincode = $this->input->post('pin');
            $insert_data = array(
                'product_type' => $product_type,
                'vehicle_type' => $vehicle_type,
                'engine_no' => $engine_no,
                'chassis_no' => $chassis_no,
                'manufacturer' => $manufacturer,
                'model' => $model,
                'manufacturing_month' => $manufacturin_month,
                'manufacturing_year' => $manufacturin_year,
                'registration_date' => $reg_date,
                'registration_no' => $registration_no,
                'odometer_reading' => $odometer_reading,
                'adhar_card' => $aadhar_no,
                'pan' => $pan_card,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'mobile_no' => $mobile_no,
                'address_1' => $cust_addr1,
                'address_2' => $cust_addr2,
                'state' => $state,
                'city' => $city,
                'pincode' => $pincode,
                'created_at' => date('Y-m-d h:i:sa'),
                'updated_at' => date('Y-m-d h:i:sa')
            );

            if (empty($this->session->userdata('customer_id'))) {
                $this->db->insert('hc_customers', $insert_data);
                $insert_id = $this->db->insert_id();
                $this->session->set_userdata('customer_id', $insert_id);
                $this->session->set_userdata('customer_and_vehicle_details', $this->input->post());
            }

            if (!empty($this->session->userdata('customer_id'))) {

                $id = $this->session->userdata('customer_id');
                $this->db->where('id', $id);
                $this->db->update('hc_customers', $insert_data);
            }
        }
        if (empty($this->session->userdata('customer_and_vehicle_details'))) {
            redirect('extended_warranty');
        }
        if (!empty($this->session->userdata('customer_id'))) {

            $this->load->dashboardTemplate('front/myaccount/plan_details', $this->data);
        }
    }

    public function plan_details() {
        $this->load->view('front/myaccount/plan_details');
    }

    public function policy_sold() {
        $data['main_contain'] = 'front/myaccount/sold-policy';
        $this->load->view('front/myaccount/includes/template', $data);
    }

    public function claim_courier_view($policy_details_id) {

        $query = $this->db->query("SELECT * FROM `hc_sold_policies` where unique_value='$policy_details_id'");
        $policy_list_result = $query->row();
        if (!$policy_list_result) {
            die("No Policy Exist");
        }
        $plan_details_data = $this->Home_Model->get_plan_data($policy_list_result->plan_detail_id);

        $customer_policy_data = $this->Home_Model->get_customers_data($policy_list_result->customer_id);
        $bankmaster = $this->Home_Model->getDataFromTable('hc_bankmaster');
        $claim_courier_process = $this->Home_Model->claim_courier_process_data($policy_list_result->claim_courier_process_id);

        $data['main_contain'] = 'front/myaccount/claim-process';
        $data['policy_details_id'] = $policy_details_id;
        $data['sold_policy_details'] = $policy_list_result;
        $data['customer_policy_data'] = $customer_policy_data;
        $data['hc_bankmaster'] = $bankmaster;
        $data['plan_details_data'] = $plan_details_data;
        $data['claim_courier_process'] = $claim_courier_process;

        $data['main_contain'] = 'front/myaccount/view_courier_document';
        $this->load->view('front/myaccount/includes/template', $data);
    }

    public function upload_courier_document() {
        $data['main_contain'] = 'front/myaccount/upload_courier_document';
        $this->load->view('front/myaccount/includes/template', $data);
    }

    public function cliam_policy() {
        $data['main_contain'] = 'front/myaccount/claim';
        $this->load->view('front/myaccount/includes/template', $data);
    }

    public function SoldPolicy_data() {
        $user_id = $_SESSION['user_session']['logged_in'];
        $requestData = $_REQUEST;
        $sql = "select CONCAT(hc_customers.title,' ',first_name,' ' ,middle_name)AS full_name,
                CONCAT(hc_sold_policies.policy_no,'',hc_sold_policies.master_certification_no)as policy_number,
                hc_customers.mobile_number,
                hc_sold_policies.unique_value,
                hc_sold_policies.policy_start_date,
                hc_sold_policies.policy_end_date,
                hc_sold_policies.policy_risk_date
                from hc_sold_policies left join hc_customers
                on hc_customers.id=hc_sold_policies.customer_id where hc_sold_policies.user_id='$user_id'";
        $query = $this->db->query($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
        $sql = "$sql AND  1 = 1";
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND policy_no LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " and ic_id = '" . $requestData['columns'][1]['search']['value'] . "' ";
        }
        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY hc_sold_policies.id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        $data = array();


        $i = 1;
        foreach ($result as $main) {
            $nestedData = array();
            $nestedData[] = $i++;
            $nestedData[] = $main->policy_number;
            $nestedData[] = strtoupper($main->full_name);
            $nestedData[] = $main->mobile_number;
            $nestedData[] = $main->policy_start_date;
            $nestedData[] = $main->policy_end_date;
            $nestedData[] = $main->policy_risk_date;
            $nestedData[] = '<a  href="' . base_url() . 'myaccount/download-policy/' . $main->unique_value . '" target="_blank"> <button type="button" class="btn btn-warning" id="policy_no" style="margin-top: 7px;"><i class="fa fa-download" aria-hidden="true"></i></button></a>';
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

    public function sold_policy_claim_ajax() {
        // echo 'hello moto';die();
        $user_id = $_SESSION['user_session']['logged_in'];
        $requestData = $_REQUEST;

        $sql = "select hc_sold_policies.id,hc_sold_policies.cliam_status_id,hc_sold_policies.unique_value, CONCAT(hc_customers.title,' ',first_name,' ',middle_name)AS full_name,
CONCAT(hc_sold_policies.policy_no,'',hc_sold_policies.master_certification_no)as policy_number,
hc_customers.mobile_number,
hc_sold_policies.policy_start_date,
hc_sold_policies.policy_end_date,
hc_sold_policies.policy_risk_date
from hc_sold_policies left join hc_customers
on hc_customers.id=hc_sold_policies.customer_id where hc_sold_policies.user_id='$user_id'  ";


        $query = $this->db->query($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
        $sql = "$sql AND  1 = 1";
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND  CONCAT(hc_sold_policies.policy_no,'',hc_sold_policies.master_certification_no) LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }


        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY hc_sold_policies.id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        $data = array();


        $i = 1;
        foreach ($result as $main) {

            $nestedData = array();
            $nestedData[] = $i++;
            $nestedData[] = $main->policy_number;
            $nestedData[] = strtoupper($main->full_name);
            $nestedData[] = $main->mobile_number;
            if ($main->cliam_status_id == '1') {
                $nestedData[] = '<a href="' . base_url() . 'myaccount/claim-upload-document/' . $main->unique_value . '"> <button type="button" class="btn btn-info" id="policy_no" style="margin-top: 7px;">Upload Document</button></a>';
            } elseif ($main->cliam_status_id == '10') {
                $nestedData[] = "Waiting For Company Approval";
            } elseif ($main->cliam_status_id == '2') {
                $nestedData[] = "Proposal Claim Is Reject ";
            } elseif ($main->cliam_status_id == '6') {
                $nestedData[] = "Proposal Claim Is Approval process to upload Courier document    ";
            } else {
                $nestedData[] = '<a href="' . base_url() . 'myaccount/claim-issue-policy/' . $main->unique_value . '"> <button type="button" class="btn btn-warning" id="policy_no" style="margin-top: 7px;">Claim Policy</button></a>
<a href="' . base_url() . 'uploads/NEFT MANDATE FORM.PDF" target="_blank" data-toggle="tooltip" title="HOSPITAL CASH CLAIM FORM !"> <button type="button" class="btn btn-info" id="policy_no" style="margin-top: 7px;"><i class="fa fa-download" aria-hidden="true"></i>
</button></a>
 <a href="' . base_url() . 'uploads/HOSPITAL CASH CLAIM FORM.PDF" target="_blank"  data-toggle="tooltip" title="NEFT MANDATE FORM !"> <button type="button" class="btn btn-sucess" id="policy_no" style="margin-top: 7px;"><i class="fa fa-download" aria-hidden="true"></i>
</button></a>
             ';
            }
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        // echo '<pre>';print_r($json_data);die;
        echo json_encode($json_data);
    }

    public function sold_policy_courier_claim_ajax() {

        $user_id = $_SESSION['user_session']['logged_in'];
        $requestData = $_REQUEST;
        $sql = "select hc_sold_policies.document_status_id, hc_sold_policies.id,hc_sold_policies.cliam_status_id,hc_sold_policies.unique_value, CONCAT(hc_customers.title,' ',first_name,' ',middle_name)AS full_name,
                CONCAT(hc_sold_policies.policy_no,'',hc_sold_policies.master_certification_no)as policy_number,
                hc_customers.mobile_number,
                hc_sold_policies.policy_start_date,
                hc_sold_policies.policy_end_date,
                hc_sold_policies.policy_risk_date
                from hc_sold_policies left join hc_customers
                on hc_customers.id=hc_sold_policies.customer_id where hc_sold_policies.user_id='$user_id' and hc_sold_policies.document_status_id!=''";


        $query = $this->db->query($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
        $sql = "$sql AND  1 = 1";

        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND  CONCAT(hc_sold_policies.policy_no,'',hc_sold_policies.master_certification_no) LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $sql .= " ORDER BY hc_sold_policies.id DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        $data = array();
        $i = 1;



        foreach ($result as $main) {

            switch ($main->document_status_id) {
                case '1':
                    $button = '<a href="' . base_url() . 'myaccount/claim-courier-process/' . $main->unique_value . '"> <button type="button" class="btn btn-info" id="policy_no" style="margin-top: 7px;">Upload Courier Document </button></a>';
                    break;
                case '2':
                    $button = '<a href="' . base_url() . 'myaccount/claim-courier-process/' . $main->unique_value . '"> <button type="button" class="btn btn-info" id="policy_no" style="margin-top: 7px;">Upload Courier Document </button></a>';
                    break;

                case '3':
                    $button = 'Wating For Insurance Company Approval';
                    break;

                case '5':
                    $button = '<a href="' . base_url() . 'myaccount/claim-courier-view/' . $main->unique_value . '"> <button type="button" class="btn btn-info" id="policy_no" style="margin-top: 7px;">Document Received </button></a>';
                    break;

                default:
                    # code...
                    break;
            }

            $nestedData = array();
            $nestedData[] = $i++;
            $nestedData[] = $main->policy_number;
            $nestedData[] = strtoupper($main->full_name);
            $nestedData[] = $main->mobile_number;
            $nestedData[] = $button;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function upload_doument_cliam() {

        if ($this->input->post()) {


            $policy_details_id = htmlspecialchars($this->input->post('policy_details_id'));
            $start_date = htmlspecialchars($this->input->post('start_date'));
            $end_date = htmlspecialchars($this->input->post('end_date'));
            $total_amount = htmlspecialchars($this->input->post('total_amount'));
            $total_day = htmlspecialchars($this->input->post('total_days'));
            /*       $aadhar_no=htmlspecialchars($this->input->post('aadhar_no'));
              $bank_branch=htmlspecialchars($this->input->post('bank_branch'));
              $branch_city=htmlspecialchars($this->input->post('branch_city'));
              $cheque_no=htmlspecialchars($this->input->post('cheque_no'));
              $cheque_date=htmlspecialchars($this->input->post('cheque_date'));
              $account_name=htmlspecialchars($this->input->post('account_name'));
              $account_no=htmlspecialchars($this->input->post('account_no'));
              $ifsc_code=htmlspecialchars($this->input->post('ifsc_code')); */

            $per_day = htmlspecialchars($this->input->post('per_day'));

            /* $discharge_report = $this->fn_do_upload_image("discharge_report");
              $aadhar_images = $this->fn_do_upload_image("aadhar_images");
              $billing_images = $this->fn_do_upload_image("billing_images");
              $cheque_images = $this->fn_do_upload_image("cheque_images");
              $hospicash_images = $this->fn_do_upload_image("hospicash_images");
              $neft_images = $this->fn_do_upload_image("neft_images"); */

            /*
              if($this->input->post('radio')=="cheque") {
              $claim_array=array(
              "hospital_start_date"=>date('Y-m-d',strtotime($start_date)),
              "hospital_end_date"=>date('Y-m-d',strtotime($end_date)),
              "total_day"=>$total_day,
              "per_day"=>$per_day,
              "total_amount"=>$total_amount,
              "policy_sold_id"=>$policy_details_id,
              "make_status"=>'Submit',
              "aadhar_no"=>$aadhar_no,
              "bank_branch"=>$bank_branch,
              "branch_city"=>$branch_city,
              "cheque_no"=>$cheque_no,
              "cheque_date"=>date('Y-m-d',strtotime($cheque_date)),
              "total_premium"=>$total_premium,
              "account_name"=>$account_name,
              "account_no"=>$account_no,
              "ifsc_code"=>$ifsc_code,
              "billing_images"=>$billing_images,
              "aadhar_images"=>$aadhar_images,
              "cheque_images"=>$cheque_images,
              "discharge_report"=>$discharge_report,
              "hospicash_images"=>$hospicash_images,
              "neft_images"=>$neft_images,
              );
              }
             */
            /* else
              { */
            $claim_array = array(
                "hospital_start_date" => date('Y-m-d', strtotime($start_date)),
                "hospital_end_date" => date('Y-m-d', strtotime($end_date)),
                "total_day" => $total_day,
                "per_day" => $per_day,
                "total_amount" => $total_amount,
                "policy_sold_id" => $policy_details_id,
                "make_status" => 'No Action',
                "total_amount" => $total_amount,
                // "aadhar_no"=>$aadhar_no,
                // "account_name"=>$account_name,
                // "account_no"=>$account_no,
                // "ifsc_code"=>$ifsc_code,
                //"billing_images"=>$billing_images,
                //"aadhar_images"=>$aadhar_images,
                //"discharge_report"=>$discharge_report,
                //"hospicash_images"=>$hospicash_images,
                //"neft_images"=>$neft_images,
            );
            /*     } */
            $this->Home_Model->insertIntoTable('hc_claim_process', $claim_array);
            $update_array = array(
                "cliam_status_id" => 1
            );
            $where = array(
                "id" => $policy_details_id
            );
            $this->Home_Model->updateTable('hc_sold_policies', $update_array, $where);
        }
        redirect("cliam-policy");
    }

    public function upload_doument_cliam_process() {

        if ($this->input->post()) {
            $policy_details_id = htmlspecialchars($this->input->post('policy_details_id'));
            $claim_process_id = htmlspecialchars($this->input->post('claim_process_id'));
            $aadhar_no = htmlspecialchars($this->input->post('aadhar_no'));
            $bank_branch = htmlspecialchars($this->input->post('bank_branch'));
            $branch_city = htmlspecialchars($this->input->post('branch_city'));
            $cheque_no = htmlspecialchars($this->input->post('cheque_no'));
            $cheque_date = htmlspecialchars($this->input->post('cheque_date'));
            $account_name = htmlspecialchars($this->input->post('account_name'));
            $account_no = htmlspecialchars($this->input->post('account_no'));
            $ifsc_code = htmlspecialchars($this->input->post('ifsc_code'));

            // $per_day=htmlspecialchars($this->input->post('per_day'));

            $discharge_report = $this->fn_do_upload_image("discharge_report");
            $aadhar_images = $this->fn_do_upload_image("aadhar_images");
            $billing_images = $this->fn_do_upload_image("billing_images");
            $cheque_images = $this->fn_do_upload_image("cheque_images");
            $hospicash_images = $this->fn_do_upload_image("hospicash_images");
            $neft_images = $this->fn_do_upload_image("neft_images");
            $claim_array = array(
                "policy_sold_id" => $policy_details_id,
                "bank_branch" => $bank_branch,
                "account_name" => $account_name,
                "account_no" => $account_no,
                "ifsc_code" => $ifsc_code,
                "make_status" => "Submit",
                "payment_type" => $this->input->post('radio'),
            );

            $where_array = array("id" => $claim_process_id);
            $this->Home_Model->updateTable('hc_claim_process', $claim_array, $where_array);
            /* Insert data */
            $claim_image_array = array(
                "aadhar_no" => $aadhar_no,
                "claim_process_id" => $claim_process_id,
                "cheque_images" => $cheque_images,
                "billing_images" => $billing_images,
                "aadhar_images" => $aadhar_images,
                "cheque_images" => $cheque_images,
                "discharge_report" => $discharge_report,
                "hospicash_images" => $hospicash_images,
                "neft_images" => $neft_images,
                "update_date" => date("Y-m-d H:i:s"),
            );
            $this->Home_Model->insertIntoTable('hc_claim_process_images', $claim_image_array);
            /* Update Sold Policy Data */
            $update_array = array(
                "cliam_status_id" => 10
            );
            $where = array(
                "id" => $policy_details_id
            );
            $this->Home_Model->updateTable('hc_sold_policies', $update_array, $where);
        }
        redirect("cliam-policy");
    }

    public function upload_courier_document_action() {

        if ($this->input->post()) {

            $policy_details_id = htmlspecialchars($this->input->post('policy_details_id'));
            $courier_doc_number = htmlspecialchars($this->input->post('courier_doc_number'));

            $upload_doc = $this->fn_do_upload_courier_image("upload_doc");

            $claim_courier_array = array(
                "hc_sold_policy_id" => $policy_details_id,
                "courier_doc_number" => $courier_doc_number,
                "courier_doc_file" => $upload_doc,
                "create_date" => date('Y-m-d H:i:s'),
            );
            $this->Home_Model->insertIntoTable('hc_claim_courier_process', $claim_courier_array);
            $update_array = array(
                "document_status_id" => 3
            );
            $where = array(
                "id" => $policy_details_id
            );
            $this->Home_Model->updateTable('hc_sold_policies', $update_array, $where);
        }
        redirect("upload-courier-document");
    }

    public function claim_policy_data($policy_details_id) {
        $query = $this->db->query("SELECT * FROM `hc_sold_policies` where unique_value='$policy_details_id'");
        $policy_list_result = $query->row();
        if (!$policy_list_result) {
            die("No Policy Exist");
        }
        $plan_details_data = $this->Home_Model->get_plan_data($policy_list_result->plan_detail_id);

        $customer_policy_data = $this->Home_Model->get_customers_data($policy_list_result->customer_id);
        $bankmaster = $this->Home_Model->getDataFromTable('hc_bankmaster');



        $data['main_contain'] = 'front/myaccount/claim-process';
        $data['policy_details_id'] = $policy_details_id;
        $data['sold_policy_details'] = $policy_list_result;
        $data['customer_policy_data'] = $customer_policy_data;
        $data['hc_bankmaster'] = $bankmaster;
        $data['plan_details_data'] = $plan_details_data;

        $this->load->view('front/myaccount/includes/template', $data);
    }

    public function claim_upload_document($policy_details_id) {
        $query = $this->db->query("SELECT * FROM `hc_sold_policies` where unique_value='$policy_details_id'");
        $policy_list_result = $query->row();
        if (!$policy_list_result) {
            die("No Policy Exist");
        }
        $plan_details_data = $this->Home_Model->get_plan_data($policy_list_result->plan_detail_id);
        $customer_policy_data = $this->Home_Model->get_customers_data($policy_list_result->customer_id);
        $claim_data = $this->Home_Model->get_claim_data($policy_list_result->id);
        $bankmaster = $this->Home_Model->getDataFromTable('hc_bankmaster');

        $data['main_contain'] = 'front/myaccount/claim-upload-process';
        $data['policy_details_id'] = $policy_details_id;
        $data['sold_policy_details'] = $policy_list_result;
        $data['customer_policy_data'] = $customer_policy_data;
        $data['hc_bankmaster'] = $bankmaster;
        $data['plan_details_data'] = $plan_details_data;
        $data['claim_data'] = $claim_data;

        $this->load->view('front/myaccount/includes/template', $data);
    }

    public function claim_courier_process_data($policy_details_id) {
        $query = $this->db->query("SELECT * FROM `hc_sold_policies` where unique_value='$policy_details_id'");
        $policy_list_result = $query->row();
        if (!$policy_list_result) {
            die("No Policy Exist");
        }
        $plan_details_data = $this->Home_Model->get_plan_data($policy_list_result->plan_detail_id);
        $customer_policy_data = $this->Home_Model->get_customers_data($policy_list_result->customer_id);
        $bankmaster = $this->Home_Model->getDataFromTable('hc_bankmaster');

        $data['main_contain'] = 'front/myaccount/claim-courier-process';
        $data['policy_details_id'] = $policy_details_id;
        $data['sold_policy_details'] = $policy_list_result;
        $data['customer_policy_data'] = $customer_policy_data;
        $data['hc_bankmaster'] = $bankmaster;
        $data['plan_details_data'] = $plan_details_data;

        $this->load->view('front/myaccount/includes/template', $data);
    }

    public function download_policy_data($unique_value) {
        $query = $this->db->query("SELECT * FROM `hc_sold_policies` where unique_value='$unique_value'");
        $policy_list_result = $query->row();
        if (!$policy_list_result) {
            die("No Policy Exist");
        }
        $plan_details_data = $this->Home_Model->get_plan_data($policy_list_result->plan_detail_id);
        $customer_policy_data = $this->Home_Model->get_customers_data($policy_list_result->customer_id);

        $bankmaster = $this->Home_Model->getDataFromTable('hc_bankmaster');

        /* policy sold dateils */

        $policy_no = $policy_list_result->policy_no;
        $policy_id = $policy_list_result->id;
        $master_certification_no = $policy_list_result->master_certification_no;
        $policy_date = $policy_list_result->policy_date;
        $policy_date = $policy_list_result->policy_date;
        $policy_start_date = $policy_list_result->policy_start_date;
        $policy_end_date = $policy_list_result->policy_end_date;
        $policy_risk_date = $policy_list_result->policy_risk_date;
        /* End */

        /* Plan Details selected */
        $amount = $plan_details_data[0]->amount;
        $plan_name = $plan_details_data[0]->plan_name;
        $plan_cover_name = $plan_details_data[0]->plan_cover_name;
        $plan_age_name = $plan_details_data[0]->plan_age_name;
        $gst_amount = $plan_details_data[0]->gst_amount;
        $gst_amount = $plan_details_data[0]->gst_amount;
        $gst_percentage = $plan_details_data[0]->gst_percentage;
        $round_off_amount = $plan_details_data[0]->round_off_amount;
        $basic_total = $plan_details_data[0]->basic_total;
        $total_amount = $plan_details_data[0]->total_amount;
        $cooling_period = $plan_details_data[0]->cooling_period;
        $pre_existing_d = $plan_details_data[0]->pre_existing_d;


        /* Custmoer Details Data */

        $full_name = $customer_policy_data[0]->title . ' ' . $customer_policy_data[0]->first_name . ' ' . $customer_policy_data[0]->middle_name . ' ' . $customer_policy_data[0]->last_name;
        $father_full_name = $customer_policy_data[0]->father_full_name;
        $mobile_number = $customer_policy_data[0]->mobile_number;
        $email = $customer_policy_data[0]->email;
        $gender = $customer_policy_data[0]->gender;
        $marital_status = $customer_policy_data[0]->marital_status;
        $pan = $customer_policy_data[0]->pan;
        $adhar_card = $customer_policy_data[0]->adhar_card;
        $date_of_birth = $customer_policy_data[0]->date_of_birth;
        $employee_code = $customer_policy_data[0]->employee_code;
        $branch_code = $customer_policy_data[0]->branch_code;
        $corporate_name = $customer_policy_data[0]->corporate_name;
        $occupation = $customer_policy_data[0]->occupation;
        $correspondence_address_1 = $customer_policy_data[0]->correspondence_address_1;
        $correspondence_address_2 = $customer_policy_data[0]->correspondence_address_2;
        $correspondence_address_3 = $customer_policy_data[0]->correspondence_address_3;
        $correspondence_state = $customer_policy_data[0]->correspondence_state;
        $correspondence_city = $customer_policy_data[0]->correspondence_city;
        $correspondence_pincode = $customer_policy_data[0]->correspondence_pincode;
        $permanent_address_1 = $customer_policy_data[0]->permanent_address_1;
        $permanent_address_2 = $customer_policy_data[0]->permanent_address_2;
        $permanent_address_3 = $customer_policy_data[0]->permanent_address_3;
        $permanent_state = $customer_policy_data[0]->permanent_state;
        $permanent_city = $customer_policy_data[0]->permanent_city;
        $permanent_city = $customer_policy_data[0]->permanent_city;
        $permanent_pincode = $customer_policy_data[0]->permanent_pincode;
        $education = $customer_policy_data[0]->education;
        $year_of_passing = $customer_policy_data[0]->year_of_passing;
        $month_income = $customer_policy_data[0]->month_income;
        $introducer_emp_code = $customer_policy_data[0]->introducer_emp_code;
        $introducer_emp_name = $customer_policy_data[0]->introducer_emp_name;
        $promo_code = $customer_policy_data[0]->promo_code;
        $nominee_name = $customer_policy_data[0]->nominee_name;
        $nominee_relation = $customer_policy_data[0]->nominee_relation;

        $this->load->library('Ciqrcode');
        /*        $this->load->library('Pdf');
         */
        ob_start();
        $this->load->library('Tcpdf/Tcpdf.php');
        $this->load->library('Ciqrcode');
        $pdf = new TCPDF();
        $pdf->SetFont('helvetica', '', 7, '', 'default', true);

        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);


        $params['data'] = " Name: '" . $full_name . "' , POLICY NO: '" . $policy_no . "', FROM: '" . $policy_start_date . "' , TO: '" . $policy_end_date . "'";
        $params['level'] = 'H';
        $params['size'] = 5;
        $params['savename'] = FCPATH . 'uploads/qr/tes' . $policy_id . '.png';

        $this->ciqrcode->generate($params);
        $qr_img = '<img src="uploads/qr/tes' . $policy_id . '.png" width="90px" />';

        /* $pdf = $this->pdf->load(); */
        /* $pdf->SetTitle('Sold Policy'); */

        $html = '<table width="100%" cellpadding="0" border="0" cellspacing="2" style="border-bottom: 1px solid #000;">
  <tr>
    <td width="50%" style=" font-size: 9px; padding: 20px 0;"><span style="font-weight: bold; display: block; text-transform: uppercase;">Bharti Axa General Insurance Company Ltd.</span>
     <br/>   <span style="font-weight: bold;">Phoenix House, Senapati Bapat Marg, Lower Parel, </span> <br/>
       <span style="font-weight: bold;">Mumbai, Maharashtra 400013</span><br/>
      <span style="font-weight: bold;">Tel:</span>098194 22839<br><span style="font-weight: bold;">PAN Number :</span> AABCB5730G<br><span style="font-weight: bold;">Provisional/ Final Registration :</span> 27AABCB5730G1ZX<br><span style="font-weight: bold;">ARN :</span> AA270317018610I<br><span style="font-weight: bold;">State:</span> Maharashtra<br><span style="font-weight: bold;">GST:</span> 32AAFCM3434Q1Z9</td>
    <td width="20%">
      ' . $qr_img . '
    </td>
    <td width="30%">
    <img src="uploads/logo.jpg" width="150px" /><span style=" font-size: 12px; padding: 20px 0; font-weight: bold; display: block; text-transform: uppercase; text-align: right;">Global India Insurance</span>
    </td>
  </tr>
  </table>
  <table width="100%" bgcolor="#000" cellpadding="3" border="0" style="margin-bottom:9px;-webkit-print-color-adjust: exact; ">
  <tr>
    <td style=" font-size: 11 px; padding: 5px; color: #fff; text-align: center;"><span style="padding: 0 30px;"><strong>Master Policy No. :</strong>' . $master_certification_no . ' / <b>Certificate No. </b>' . $policy_no . ' </span> </td>
  </tr>
</table>
<table width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style=" border-bottom: 0; margin-bottom: 15px;">
  <tr>
    <td width="50%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Name : </strong> ' . strtoupper($full_name) . '</td>
    <td width="50%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Father Name : </strong> ' . $father_full_name . '</td>
  </tr>
  <tr>
    <td width="50%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Mobile Number : </strong> ' . $mobile_number . '</td>
    <td width="50%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Email : </strong> ' . $email . '</td>
  </tr>
  <tr>
    <td width="50%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Gender : </strong> ' . $gender . '</td>
    <td width="50%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Marital Status : </strong> ' . $marital_status . '</td>
  </tr>
  <tr>
    <td width="50%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>PAN : </strong> ' . $pan . '</td>
    <td width="50%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Adhar Card : </strong> ' . $adhar_card . '</td>
  </tr>
  <tr>
    <td width="50%" style="font-size: 9px; padding: 5px; border-bottom: 0px solid #000;"><strong>Date of Birth : </strong> ' . date('d-m-Y', strtotime($date_of_birth)) . '</td>
    <td width="50%" style="font-size: 9px; padding: 5px; border-bottom: 0px solid #000;"><strong>Occupation : </strong> ' . $occupation . '</td>
  </tr>
    <tr>
    <td width="100%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong style="display: block;">Address  : </strong>' . $correspondence_address_1 . ' ' . $correspondence_address_2 . ' ' . $correspondence_address_3 . ' ' . $correspondence_city . ' ' . $correspondence_state . '  ' . $correspondence_pincode . '</td>

  </tr>
   <tr>
    <td width="50%" style="font-size: 9px; padding: 5px; "><strong>Nominee Name : </strong> ' . $nominee_name . '</td>
    <td width="50%" style="font-size: 9px; padding: 5px; "><strong>Nominee Relation : </strong> ' . $nominee_relation . '</td>
  </tr>

   <tr>
      <td width="100%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Monthly Income : </strong> ' . $month_income . '</td>

  </tr>
</table>
<br/>
<br/>
<br/>
<table width="100%" bgcolor="#000" cellpadding="2" border="0" cellspacing="2" style="margin-bottom:9px;-webkit-print-color-adjust: exact; ">
  <tr>
    <td style=" font-size: 11px; padding: 5px; color: #fff; text-align: center;"><span style="padding: 0 30px;"><strong>Plan Details</strong></span></td>
  </tr>
</table>
<table width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style=" border-bottom: 0; margin-bottom: 15px;">
  <tr>
    <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Created At : </strong> ' . date('d-m-Y', strtotime($policy_date)) . '</td>

    <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Plan Name : </strong> ' . $plan_name . '</td>
    <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Days : </strong> ' . $plan_cover_name . ' </td>
    <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Policy No : </strong> ' . $policy_no . '</td>
  </tr>
  <tr>

    <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Master Cert. No : </strong> ' . $master_certification_no . '</td>
    <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Policy Date : </strong> ' . date('d-m-Y', strtotime($policy_date)) . '</td>
    <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Policy Start : </strong> ' . date('d-m-Y', strtotime($policy_start_date)) . '</td>
    <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Policy End : </strong> ' . date('d-m-Y', strtotime($policy_end_date)) . '</td>
  </tr>
  <tr>
    <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Risk Date : </strong> ' . date('d-m-Y', strtotime($policy_risk_date)) . '</td>
    <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Waiting Period : </strong> ' . $cooling_period . ' day</td>
   <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">&nbsp;</td>
   <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">&nbsp;</td>
  </tr>
  <tr>

     <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Amount : </strong> ' . $amount . '/-</td>
    <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>GST Amount : </strong> ' . $gst_amount . '/-</td>
    <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;"><strong>Gst Percentage : </strong> ' . $gst_percentage . '%</td>
    <td width="25%" style="font-size: 9px; padding: 5px; border-bottom: 1px solid #000;">&nbsp;</td>
  </tr>
  <tr>
    <td width="100%" colspan="4" style="font-size: 11px; padding: 5px;  text-align: right;"><strong>Total Amount : ' . $total_amount . '/-</strong></td>
  </tr>
</table>
<br/>
<br/>

<table width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style=" border: 1px solid #000; margin-bottom: 10px;">
  <tr>
    <td style="font-size: 9px; padding: 10px; border: 1px solid #000;">
    <h3 style="font-size:11px;">General Exclusion</h3>
    <p>The Company shall not be liable to make any payment for any claim directly or indirectly caused by or, based on or, arising out of or howsoever attributable to any of the following:</p>
    <ul>
        <li>suicide, attempted suicide (whether sane or insane) or intentionally self-inflicted Injury or illness, or sexually transmitted conditions, mental or nervous disorder, anxiety, stress or depression, Acquired Immune Deficiency Syndrome (AIDS), Human Immune-deficiency Virus (HIV) infection; or</li>
        <li>Medical treatment required following any criminal act of the Policyholder/ Insured / Insured Person and/or following use of intoxicating drugs and alcohol or drug abuse, solvent abuse or any addiction or medical condition resulting from or relating to such abuse or addiction; or</li>
        <li>Disease / illness / injury, directly or indirectly, caused by or arising from or attributable to foreign invasion, act of foreign enemies, hostilities (whether war be declared or not), civil war, rebellion, revolution, insurrection, military or usurped power, riot, strike, lockout, military or popular uprising or civil commotion, act of terrorism or any terrorist incident.</li>
        <li>participation in any Professional Sport, any bodily contact sport or any other hazardous or potentially dangerous sport for which you are trained or untrained; or.</li>
        <li>Dental treatment or surgery of any kind unless requiring hospitalization.</li>
        <li>Aesthetic treatment, cosmetic surgery or plastic surgery unless necessitated due to Accident</li>
        <li>Experimental and unproven treatment.</li>
        <li>Any treatment which is taken as an out-patient without any admission as an in-patient at the Hospital.</li>
        <li>Any treatment received outside India.</li>
        <li>Acupressure, acupuncture, magnetic therapies.</li>
        <li>Naturopathy or unani forms of treatment.</li>
    </ul>
    <p>Refer the Policy wordings for detailed list of policy exclusions</p>
    </td>
  </tr>
  <tr>
    <td style="font-size: 10px; padding: 10px; border: 1px solid #000;">
    <h3 style="font-size:11px;">General Conditions</h3>
    <p><strong>Free Look Period</strong></p>
    <p>Policyholder/ Insured has a period of 15 days from the date of receipt of the Policy document to review the terms and conditions of this Policy. If the Policyholder/ Insured has any objections to any of the terms and conditions, he / she has the option of cancelling the Policy stating the reasons for cancellation. Refund of premium shall be subject to Policy terms & conditions.</p>
    <p><strong>Cancellation & Refund</strong></p>
    <p>The Company may cancel this Policy, by giving 15 days’ notice in writing by registered post acknowledgment due to the Policyholder/ Insured at his / their last known address. The Company shall exercise its right to cancel only on grounds of mis-representation, fraud, non-disclosure of material facts or non-cooperation of the Policyholder/ Insured / Insured Person in implementing the terms and conditions of this Policy/Certificate of Insurance, in which case the policy shall stand cancelled ab-initio and there will be no refund of premium.</p>
    <p>The Policyholder/ Insured may also give 15 days notice in writing, to the Company, for the cancellation of this Policy/Certificate of Insurance, in which case the Company shall from the date of receipt of notice, cancel the Policy and retain the premium for the period this Policy has been in force at the Companys short period scales given below. Provided that, refund on cancellation of Policy by the Policyholder/ Insured shall be made only if no claim has/is occurred/reported up to the date of cancellation of this Policy.</p>
    <table width="70%" bgcolor="" cellpadding="4" border="1" cellspacing="0" style=" margin-bottom: 10px;">
        <tr>
            <th><strong>Period on Risk</strong></th>
            <th><strong>Rate of Premium to be retained by Company</strong> </th>
        </tr>
        <tr>
            <td>Up to 1 month</td>
            <td>25%</td>
        </tr>
        <tr>
            <td>Exceeding 1 month Up to 3 months</td>
            <td>50%</td>
        </tr>
        <tr>
            <td>Exceeding 3 months Up to 6 months</td>
            <td>75%</td>
        </tr>
        <tr>
            <td>Exceeding 6 months Up to 12 months</td>
            <td>100%</td>
        </tr>
    </table>
    </td>
   </tr>
   <tr>
    <td style="font-size: 10px; padding: 10px; ">
    <h3 style="font-size:11px;">Claim Procedure</h3>
    <p><strong>Claim Notification - Multi Model Intimation: </strong></p>
    <p>It is the endeavor of Company to give multiple options to the Insured person to intimate the claim to the Company. The intimation can be given in following ways:</p>
    <ol>
        <li>Toll Free call Centre of the Insurance Company (24x7) 1800-103-2292</li>
        <li>Login to the website of the Insurance Company and intimate the claim <a href="http://www.bhartiaxagi.co.in/contact-us" target="_blank"> http://www.bhartiaxagi.co.in/contact-us</a></li>
        <li>Send an email to the Company <a href="customer.service@bharti-axagi.co.in">customer.service@bharti-axagi.co.in</a></li>
        <li>Post/courier to Company Bharti AXA General Insurance Company Limited, 1st Floor, 102 - Raheja Titanium, Western Express Highway, Goregaon (East), Mumbai – 400063</li>
    </ol>
    </td>
  </tr>
</table>';
        /*   $pdf->AddPage();
          $pdf->writeHtml($html); */
        /* ob_clean(); */

        $pdf->AddPage();
        $pdf->writeHtml($html);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        $html1 .= '<table class="table break-before" style="font-size:10px;">
               <tbody>
                  <tr>
                     <td align="right" colspan="3"><img src="uploads/bharti-axa.jpg"></td>
                  </tr>
                  <tr>
                     <td align="center" colspan="3" style="font-size: 15px;"><b>Certificate for the purpose of deduction under Section 80-D of <br>
Income Tax(Amendment) Act,1986</b></td>
                  </tr>
                  <tr>
                     <td align="left" colspan="3">This is to certify that ' . $full_name . ' has paid Rs.  ' . $total_amount . ' towards premium for HOSPITAL CASH DAILY ALLOWANCE INSURANCE
for the period from ' . date('d-M-Y', strtotime($policy_start_date)) . ' to midnight of ' . date('d-M-Y', strtotime($policy_end_date)) . ' under Policy no ' . $policy_no . '</td>
                  </tr>
                  <tr>
                     <td align="left"><b>Date :</b></td>
                     <td align="left">' . date('d-M-Y') . '</td>
                  </tr>
                  <tr>
                     <td align="left"><b>Place :</b></td>
                     <td align="left" colspan="2"> </td>
                  </tr>
                  <tr>
                     <td align="left" colspan="3"><b>For and on behalf of</b> <br>Bharti AXA General Insurance Company
Limited <br><br>
<img src="http://via.placeholder.com/100x50"><br><br>
<b>Authorized Signatory</b>
<br>
This certificate must be surrendered to the Company for issuance of fresh certificate in case of cancellation of the Policy or any
alteration in the insurance affecting premium. <br>Regd. Office: GE Plaza, Airport Road,Yerwada, Pune-411006 (India).</td>
                  </tr>
               </tbody>
            </table>';

        $pdf->AddPage();
        $pdf->writeHtml($html1);
        ob_clean();
        /*   $pdf->WriteHTML($html); */
        $output = "myassistance";
        /*  $pdf->Output("$output.pdf", 'I'); */
        $pdf->Output($output, 'I');
    }

    function fn_do_upload_image($image_file_name) {
        $upload_PATH_NAME = './uploads/claim/';


        if ($_FILES[$image_file_name]['name'] != "") {
            $config = array();
            $config['upload_path'] = $upload_PATH_NAME;

            $config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
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

    function fn_do_upload_courier_image($image_file_name) {
        $upload_PATH_NAME = './uploads/courier/';


        if ($_FILES[$image_file_name]['name'] != "") {
            $config = array();
            $config['upload_path'] = $upload_PATH_NAME;

            $config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
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

    function load_view() {



        /*
          $pdf->writeHtml($html);


          if (!($this->config->item('is_live'))) {
          $pdf->Image('images/dummypolicy.png', 25, 5, 150, 250, '', '', '', true, 500);
          }
          //"policy"-firtsandlastnamecompany-policynumber
          $pdf_to_name = "Policy- .'$insured_name'..'$insurancecompanyname'..'$policy_no'.pdf";
          // $pdf_to_name = $insured_name.' - '.$policy_no.'.pdf';
          // $pdf_to_name = str_replace(" ", "-", 'test');
          ob_clean();
          $pdf->Output($pdf_to_name, 'I'); */
    }

    function getManufacturers($prod_type = null, $vehicle_type = null) {
        $make = $this->db->query("SELECT * from make_model_rater where vehicle_type_id = '$prod_type' and vehicle_type LIKE '%$vehicle_type%'  GROUP BY make")->result_array();
        return $make;
    }

    function FetchManufacturers() {

        $prod_type = htmlspecialchars($this->input->post('prod_type'));

        $vehicle_type = htmlspecialchars($this->input->post('vehicle_type'));

        $make = $this->db->query("SELECT * from make_model_rater where vehicle_type_id = $prod_type and vehicle_type LIKE '%$vehicle_type%'  GROUP BY make")->result();


        $customerr_vehicle = $this->session->userdata('customer_and_vehicle_details');
        $selected_make = $customerr_vehicle['manufacturer'];
        // print_r($selected_make);die;

        $html = '<option value="">SELECT MANUFACTURER</option>';

        foreach ($make as $result) {
            $make = $result->id . '-' . $result->make;

            // $selected = '';
            // if((int)$selected_make[0] == $result->id){
            //     $selected = "selected ='selected'";
            // }
            $selected = ($make == $selected_make) ? "selected ='selected'" : "";

            $html .= '<option value="' . $make . '" ' . $selected . '>' . $result->make . '</option>';
        }

        echo $html;
    }

    // echo json_encode($data);

    function getModels($make = null, $vehicle_type = null) {
        $make_id = explode('-', $make);
        $model = $this->db->query("SELECT * FROM `make_model_rater` where make='$make_id[1]' and vehicle_type like '%$vehicle_type%' GROUP BY model")->result_array();
        return $model;
    }

    function FetchModels() {


        $make = htmlspecialchars($this->input->post('make'));
        $prod_type = htmlspecialchars($this->input->post('prod_type'));
        $vehicle_type = htmlspecialchars($this->input->post('vehicle_type'));
        $make_id = explode('-', $make);
        $model = $this->db->query("SELECT * FROM `make_model_rater` where make='$make_id[1]' and vehicle_type like '%$vehicle_type%' GROUP BY model")->result();

        $customer_data = $this->session->userdata('customer_and_vehicle_details');
        $selected_model = $customer_data['model'];
        echo '<option value="">SELECT MODEL</option>';
        foreach ($model as $m) {
            $model = $m->id . '-' . $m->model;
            $selected = ($model == $selected_model) ? "selected ='selected'" : "";

            echo '<option value = "' . $model . '" ' . $selected . '>' . $m->model . '</option>';
        }
    }

    function getCities($state) {
        $state = explode('-', $state);
        $where = array(
            'state_id' => $state[0]
        );
        if (!empty($where)) {
            $cities = $this->Home_Model->getDataFromTableWithOject('hc_cities', $where);
            return $cities;
        }
    }

    function getStates() {
        $states = $this->Home_Model->getDataFromTableWithOject('hc_pincode_master');
        print_r($states);
        die;

        return $states;
    }

    function FetchCities() {


        $customer_data = $this->session->userdata('customer_and_vehicle_details');
        if (isset($customer_data)) {
            $selected_city = $customer_data['city'];
        }
        $where = array(
            'state_id_pk' => $this->input->post('state')
        );
        if (!empty($where)) {
            $cities = $this->Home_Model->fetchCities($where);
            // print_r($cities);die;
            echo '<option value="">SELECT CITY</option>';
            foreach ($cities as $city) {

                $cityy = $city->city_or_village_id_pk . '-' . $city->city_or_village_name;

                $selected = ($cityy == $selected_city) ? "selected = 'selected'" : "";

                echo '<option value= "' . $cityy . '" ' . $selected . '>' . $city->city_or_village_name . '</option>';
            }
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

}
