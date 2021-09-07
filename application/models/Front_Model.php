<?php

class Front_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**********************Start Anguler Apis****************************/

  public function getPolicyDetails ($post_data){
        if(isset($post_data['policy_no']) && !empty($post_data['policy_no'])){
                  $policy_no = $post_data['policy_no'];
        }else{
                 $policy_no = $post_data;
        }
        $sql = "SELECT tsp.*,tcd.*,td.* 
        FROM tvs_sold_policies AS tsp 
        INNER JOIN tvs_customer_details AS tcd ON tcd.id = tsp.customer_id 
        INNER JOIN tvs_dealers AS td ON td.id = tsp.user_id
        WHERE tsp.sold_policy_no like '%$policy_no%'
        ";
        
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

   

   public function getNearestVendores($task_details){
      $lat = $task_details['lat'];      
      $longi = $task_details['longi'];
      $sql = "SELECT *, SQRT(
            POW(69.1 * (lat - $lat), 2) +
            POW(69.1 * ($longi - longi) * COS(lat / 57.3), 2)) AS distance
        FROM vendor_details  ORDER BY distance LIMIT 5";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
      }


       function getAnsDataFromTable($table_name = null, $where = null) {
         if ($where != null) {
             $this->db->where($where);
         }
         return $result = $this->db
             ->from($table_name)
             ->get()
             ->result_array();
     }

    // public function getPolicyDetails($policy_id){
    //        $sql = "SELECT tcs.*,tsp.*,tp.id as tp_plan_id,tp.*,dw.* FROM tvs_sold_policies AS tsp INNER JOIN tvs_plans AS tp ON tsp.plan_id = tp.id INNER JOIN dealer_wallet AS dw ON tsp.user_id = dw.dealer_id INNER JOIN tvs_customer_details tcs ON tcs.`id` = tsp.`customer_id` WHERE tsp.id = '$policy_id' ";
    //         $result = $this->db->query($sql)->row_array();
    //         return $result;
    // }





    /**********************Start Anguler Apis****************************/























































    function totalSoldPolicies(){
    $sql = "SELECT tic.id,tic.name,pw.balance_amount,COUNT(IF(tsp.rsa_ic_id = 1,1,NULL)) AS total_policies,
    COUNT(IF((tsp.rsa_ic_id = 1 AND DATE(tsp.created_date) = CURDATE()),1,NULL)) AS todays_policies,
    COUNT(IF(tsp.rsa_ic_id = 1 AND (tsp.plan_id = 1 OR tsp.plan_id = 4 OR tsp.plan_id = 9  OR tsp.plan_id = 10),1,NULL) ) AS silver_policies,
    COUNT(IF(tsp.rsa_ic_id = 1 AND (tsp.plan_id = 2 OR tsp.plan_id = 5 OR tsp.plan_id = 8 OR tsp.plan_id = 11),1,NULL) ) AS gold_policies,
    COUNT(IF(tsp.rsa_ic_id = 1 AND (tsp.plan_id = 3 OR tsp.plan_id = 6 OR tsp.plan_id = 7 OR tsp.plan_id = 12),1,NULL) ) AS platinum_policies,
    COUNT(IF(tsp.rsa_ic_id = 1 AND (tsp.plan_id = 13 OR tsp.plan_id = 16 OR tsp.plan_id = 14 OR tsp.plan_id = 17),1,NULL) ) AS sapphire_policies
            FROM tvs_insurance_companies  AS tic 
            INNER JOIN tvs_sold_policies AS tsp ON (tsp.ic_id = tic.id || tsp.rsa_ic_id = tic.id)
            INNER JOIN tvs_plans AS tp ON tsp.plan_id = tp.id
            INNER JOIN party_wallet AS pw ON pw.party_id = tic.id
            WHERE tsp.policy_status_id = 3
            GROUP BY tic.id";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
    }

  function getDealerSoldPolicies($invoice_month){
        $policy_date = '01-'.$invoice_month;
        $policy_date = date('Y-m-d',strtotime($policy_date));
        $user_session = $this->session->userdata('user_session');
        $user_id = $user_session['id'];
    $sql = "SELECT td.*, COUNT(IF((tsp.plan_id = 1 OR tsp.plan_id = 4 OR tsp.plan_id = 9  OR tsp.plan_id = 10),1,NULL) ) AS silver_policies,
    COUNT(IF((tsp.plan_id = 2 OR tsp.plan_id = 5 OR tsp.plan_id = 8 OR tsp.plan_id = 11),1,NULL) ) AS gold_policies,
    COUNT(IF((tsp.plan_id = 3 OR tsp.plan_id = 6 OR tsp.plan_id = 7 OR tsp.plan_id = 12),1,NULL) ) AS platinum_policies,
    COUNT(IF((tsp.plan_id = 13 OR tsp.plan_id = 16 OR tsp.plan_id = 14 OR tsp.plan_id = 17),1,NULL) ) AS sapphire_policies
        FROM  tvs_sold_policies AS tsp
        INNER JOIN tvs_dealers as td ON td.id = tsp.user_id
        WHERE tsp.policy_status_id = 3
        AND   tsp.user_id = $user_id
        AND   MONTH(tsp.created_date) = MONTH('$policy_date')";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function cheeckIsStatusApproved($post_data){
        $dealer_bank_tran_id = $post_data['dealer_bank_tran_id'];
        $sql = "SELECT * FROM dealer_bank_transaction WHERE id = $dealer_bank_tran_id AND approval_status = 'approved'";
        $query = $this->db->query($sql);
                $result = $query->row_array();
                return $result;
    }
    function getDataFromTable($table_name = null, $where = null) {
        if ($where != null) {
            $this->db->where($where);
        }
        return $result = $this->db
            ->from($table_name)
            ->get()
            ->result_array();
    }

    function insertCustomerQuesAnsDetails($table_name, $data) {

        if ($this->db->insert($table_name, $data)) {
            // echo $this->db->last_query();//die();
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

     function getDataFromTableOrderByDesc($table_name = null,$key,$where = null) {
        if ($where != null) {
            $this->db->where($where);
        }
        return $result = $this->db
            ->from($table_name)
            ->order_by($key,'desc')
            ->get()
            ->result_array();
    }
     function countRecordsInTables($table_name = null, $where = null){
             if ($where != null) {
            $this->db->where($where);
        }
        $result = $this->db
            ->from($table_name)
            ->get();
        $result = $result->num_rows();
             return $result;
    }
    function CountDealerLoggedIn(){
        $sql = "SELECT td.* FROM redirect_key_log AS rkl INNER JOIN tvs_dealers AS td ON td.sap_ad_code = rkl.sap_ad_code WHERE DATE(rkl.created_at) = CURDATE() AND rkl.redirect_key IS NOT NULL";
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();
        return $num_rows ;
    }
    function getTotalWalletBalance(){
        $sql ="SELECT (SUM(security_amount)-SUM(credit_amount)) AS total_wallet_balance FROM dealer_wallet WHERE dealer_id != 2871";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result['total_wallet_balance'];
    }
    function checkIsPdfDownloaded() {
        $user_data = $this->session->userdata('user_session');
        return $return = $this->db->select('is_pdf_downloaded')->from('biz_users')->where('employee_code', $user_data['sap_ad_code'])->get()->row_array();
    }

    function updatePdfDownloadedStatus() {
        $user_data = $this->session->userdata('user_session');
        $user_data = $this->session->userdata('user_session');
        $this->db->set('is_pdf_downloaded', 1);
        $this->db->where('employee_code', $user_data['sap_ad_code']);
        $this->db->update('biz_users');
    }

    function checkIsDealerDocUploaded() {
        $user_session = $this->session->userdata('user_session');
        $dealer_id = $user_session['id'];
        $sql = "SELECT * FROM tvs_dealer_documents WHERE dealer_id = $dealer_id";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        $is_uploaded = isset($result) ? 'FALSE' : 'TRUE';
        return $is_uploaded;
    }

    function getPolicyPayout($post_data) {
        $result = array();
        $today = date("Y-m-d");
        $user_session = $this->session->userdata('user_session');
        $user_id = $user_session['id'];
        switch ($post_data['selected_days']) {
            case 'today':
                $sql = "SELECT SUM(sold_policy_price_with_tax) as policy_premium,COUNT(*) as no_of_policies  FROM tvs_sold_policies WHERE created_date like '$today%' AND ( policy_status_id <> '5') AND user_id = $user_id";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
                break;
            case 'month':
                $sql = "SELECT SUM(sold_policy_price_with_tax) as policy_premium,COUNT(*) as no_of_policies  FROM tvs_sold_policies WHERE created_date BETWEEN NOW() - INTERVAL 1 MONTH AND NOW() AND ( policy_status_id <> '5') AND user_id = $user_id";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
                break;
            case 'quarter':
                $sql = "SELECT SUM(sold_policy_price_with_tax) as policy_premium,COUNT(*) as no_of_policies  FROM tvs_sold_policies WHERE created_date BETWEEN NOW() - INTERVAL 1 QUARTER AND NOW() AND ( policy_status_id <> '5') AND user_id = $user_id";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
                break;
            case 'year':
                $sql = "SELECT SUM(sold_policy_price_with_tax) as policy_premium,COUNT(*) as no_of_policies  FROM tvs_sold_policies WHERE created_date BETWEEN NOW() - INTERVAL 1 YEAR AND NOW() AND ( policy_status_id <> '5') AND user_id = $user_id";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
                break;

            default:
                return $result;
                break;
        }
    }

    function getSelectedModel($model_id) {
        return $model_name = $this->db->select('model_name')->from('tvs_model_master')->where('model_id', $model_id)->get()->row_array();
    }

    function fetchStateCity($pin) {
        return $return = $this->db->select('*')->from('tvs_pincode_master')->where('pin_code', $pin)->get()->row_array();
    }

    public function checkDuplicatePolicy($engine_no) {
        $sql = "SELECT tsp.*,tp.* FROM tvs_sold_policies tsp INNER JOIN tvs_plans AS tp ON tsp.plan_id = tp.id WHERE `engine_no`='$engine_no' AND policy_status_id = 3";
        $is_exist = $this->db->query($sql)->row_array();
        $rsa_tenure = 0;
        $status = false;
        if(!empty($is_exist['rsa_tenure'])){
            $rsa_tenure = $is_exist['rsa_tenure'];
            $currentDate = date("Y-m-d");
            $sql = "SELECT * FROM tvs_sold_policies WHERE engine_no='$engine_no' AND policy_status_id = 3 AND DATE(sold_policy_end_date) >= '$currentDate'";
            $is_policy_exist = $this->db->query($sql)->row_array();
            $status = !empty($is_policy_exist)?true:false;
        }
        return $status;
    }
    public function checkIsPolicyExist($engine_no,$chassis_no){
            $currentDate = date("Y-m-d");
            $sql ="SELECT * FROM `tvs_sold_policies` WHERE (`engine_no` = '$engine_no' OR `chassis_no` = '$chassis_no') AND sold_policy_end_date >= '$currentDate' AND policy_status_id = 3";
            $result = $this->db->query($sql)->row_array($sql);
            return $return_data = !empty($result) ? true : false;
    }

    function getBankdetails() {
        $sql = "select * from tvs_bankmaster";
        $result = $this->db->query($sql)->result();
        return $result;
    }
     function getPolicyById($id) {
        $sql = "SELECT tsp.id as policy_id,tsp.user_id,tsp.master_policy_no,tsp.mp_start_date,tsp.mp_end_date,tsp.product_type_name,tsp.plan_id,tsp.created_date as created_at,tsp.plan_name,tsp.sold_policy_no,tsp.engine_no,tsp.chassis_no,tsp.created_date,tsp.vehicle_registration_no,tsp.sold_policy_effective_date,tsp.sold_policy_end_date,tsp.pa_sold_policy_effective_date,tsp.pa_sold_policy_end_date,tsp.model_name,tsp.model_id,tsp.make_id,tsp.ic_id,ts.name AS state_name1,
    tc.name AS city_name1,tcd.*,tp.plan_type_id FROM tvs_sold_policies AS tsp"
            . " LEFT JOIN tvs_customer_details AS tcd  ON tcd.id = tsp.customer_id "
            . " INNER JOIN tvs_plans AS tp  ON tp.id = tsp.plan_id "
            . " LEFT JOIN tvs_state AS ts ON ts.id = tcd.state "
            . " LEFT JOIN tvs_city AS tc  ON tc.id = tcd.city "
            . " WHERE tsp.id = '$id' ";
            // echo $3sql;die;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result[0];
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

    function getRowDataFromTable($table_name = null, $where = null) {
        if ($where != null) {
            $this->db->where($where);
        }
        return $result = $this->db
            ->from($table_name)
            ->get()
            ->row_array();
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

    function getRowDataFromTableUsingLike($table_name = null, $where = null) {
        if ($where != null) {
            $this->db->like($where);
        }
        return $result = $this->db
            ->from($table_name)
            ->get()
            ->row_array();
    }

    function checkDuplicateEntry($table, $where, $select) {
        $result = $this->db->select($select)
            ->from($table)
            ->where($where)
            ->get()
            ->row();

        if (count($result) > 0) {
            return $result->id;
        } else {
            return false;
        }
    }

    function deleteTable($where, $table) {
        $this->db->where($where);
        if ($this->db->delete($table)) {
            return true;
        } else {
            return false;
        }
    }

    function updateTable($table, $data, $where) {
        $this->db->where($where);
        $this->db->set($data);
        $status = $this->db->update($table);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    function insertIntoTable($table_name, $data) {

        if ($this->db->insert($table_name, $data)) {
            // echo $this->db->last_query();//die();
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    function insertIntoTableBatch($table_name, $data) {
        if ($this->db->insert_batch($table_name, $data)) {
            return true;
        } else {
            return false;
        }
    }

    function sendEmail($from, $to, $subject, $message, $name) {

        $this->load->library('email');
        $this->email->from($from, $name);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            return true;
        } else {
            //echo $this->email->print_debugger();exit;
            return false;
        }
    }

    function sendEmailByPort($from, $to, $subject, $message, $name) {
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.mailtrap.io',
            'smtp_port' => 25,
            'smtp_user' => '086222c4ca8fb0',
            'smtp_pass' => '5efe588f17de6f',
            'mailtype' => 'html',
            'charset' => 'utf-8'
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        $this->email->from($from, $name);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            return true;
        } else {
            // echo $this->email->print_debugger();exit;
            return false;
        }
    }

    function countAllStateFiltered($table, $column_order, $column_search, $order) {
        $this->getAllStates($table, $column_order, $column_search, $order);
        //$this->getCities();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all($table, $column_order, $column_search, $order, $where = null) {
        if ($where != null) {
            $this->db->where($where);
        }
        $this->db->from($table);

        return $this->db->count_all_results();
    }

    public function fetchState() {
        return $this->db->group_by("state_id_pk")->get('hc_pincode_master')->result();
    }

    public function fetchCities($where) {
        return $this->db->where($where)
                ->from('hc_pincode_master')
                ->group_by("city_or_village_id_pk")
                ->get()
                ->result();
    }

    public function getDealerTransactionData($dealer_id){
        // $sql = "SELECT * ";
        $user_session =  $this->session->userdata('user_session');
        $sap_ad_code = $user_session['sap_ad_code'];
       $where = '';
       if(strlen($sap_ad_code) > 5){
            $where = 'AND employee_code = '.$sap_ad_code.'';
       }
            $sql = "SELECT tsp.*,dt.* FROM tvs_sold_policies AS tsp 
            LEFT JOIN dealer_transections AS dt ON tsp.sold_policy_no = dt.policy_no
             WHERE tsp.user_id = '$dealer_id' AND tsp.status = 1 AND tsp.policy_status_id = 3 $where";
        $result = $this->db->query($sql)->result_array();
        // echo '<pre>'; print_r($result);die('hello');
        return $result;
    }

    public function getDealerWallet($dealer_id){
        $result = $this->db->query(" SELECT * FROM dealer_wallet WHERE dealer_id = '$dealer_id' ")->row_array();
        return $result;
    }

      public function getPolicyBYDate($from_date,$to_date){
        $user_session = $this->session->userdata('user_session');
        $user_id = $user_session['id'];
        $sap_ad_code = $user_session['sap_ad_code'];
        $where = '';
        if(strlen($sap_ad_code) > 5){
            $where = 'AND tsp.employee_code = '.$sap_ad_code.'';
        }
        $sql = " SELECT tsp.*,tsp.id AS sold_id,tcs.*,CONCAT(bu.f_name,' ',bu.l_name) AS employee_name,CONCAT(tcs.fname,tcs.lname) AS customer_name ,tp.dealer_commission,tp.rsa_commission_amount,tp.gst_amount,tp.pa_tenure,tp.rsa_tenure,tp.sum_insured,tic.name as rsa_ic_name,tic_pa.name as pa_ic_name FROM tvs_sold_policies tsp 
        JOIN tvs_customer_details tcs ON tsp.customer_id = tcs.id
        JOIN tvs_plans tp ON tsp.plan_id = tp.id
        JOIN tvs_plans tvsp ON tsp.plan_id = tvsp.id
        LEFT JOIN biz_users bu ON tsp.employee_code = bu.employee_code
        JOIN tvs_insurance_companies  as tic ON tsp.rsa_ic_id = tic.id
        JOIN tvs_insurance_companies tic_pa ON tsp.ic_id = tic_pa.id
        WHERE  tsp.user_id = $user_id AND tsp.status = 1 $where
        AND  DATE(tsp.created_date) BETWEEN '$from_date' AND '$to_date' GROUP BY tsp.id";
          // die($sql);
      $result =  $this->db->query($sql)->result_array();
      // echo '<pre>'; print_r($result);die('hello');
      return $result;
    }

    public function getDealerBankTransaction($id){
        $result = $this->db->query(" SELECT td.*,dbt.* FROM dealer_bank_transaction dbt  INNER JOIN tvs_dealers td ON td.`id` = dbt.`dealer_id` WHERE dbt.`id` = '$id' ")->row_array();
        return $result;
    }


     

     public function getFeedFileData($from_date,$to_date,$ic_id){
        $sql = " SELECT 
                      tsp.`chassis_no`,tsp.`created_date`,tsp.`customer_id`,tsp.`engine_no`,tsp.`id` AS sold_id,tsp.`model_id`,tsp.`make_name`,tsp.`model_name`,tsp.`plan_id`,tsp.`plan_name`,tsp.`policy_status_id`,tsp.`product_type`,tsp.`sold_policy_effective_date`,tsp.`sold_policy_date`,tsp.`sold_policy_end_date`,tsp.`sold_policy_no`,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,tsp.`sold_policy_tax`,tsp.`status`,tsp.`user_id`,tsp.`vehicle_registration_no`,tsp.`plan_id`, DATE(tsp.`sold_policy_date`) AS policy_start_date, TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.`addr1`,tcs.`addr2`,tcs.`city`,tcs.`city_name`,tcs.`state`,tcs.`state_name`,tcs.`dob`,tcs.`email`,tcs.`fname`,tcs.`lname`,tcs.`gender`,tcs.`mobile_no`,tcs.`nominee_age`,tcs.`nominee_full_name`,tcs.`nominee_relation`,tcs.`pan_card_no`,tcs.`pincode`,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,tp.`dealer_commission`,tp.`gst_amount` , tp.`plan_code` 
                      FROM tvs_sold_policies tsp 
                      JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` 
                      JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` 
                      WHERE tsp.user_id != 2871 
                      AND tsp.policy_status_id = 3 
                      AND  DATE(tsp.`created_date`) BETWEEN '$from_date' AND '$to_date' 
                      AND tsp.`rsa_ic_id` = '$ic_id'";
                      // die($sql);
                      $result['result'] =  $this->db->query($sql)->result_array();
                      $result['num_rows'] = $this->db->query($sql)->num_rows();
                      return $result;
    }

     public function getFeedFileDataForPaIc($ic_id,$from_date,$to_date){
        $sql = " SELECT 
                      tsp.*,tsp.`id` AS sold_id,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,td.`dealer_code`, DATE(tsp.`sold_policy_date`) AS policy_start_date, TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.*,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,tp.*,td.sap_ad_code FROM tvs_sold_policies tsp 
                          INNER JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` 
                          INNER JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` 
                          INNER JOIN    tvs_dealers td ON td.id = tsp.user_id 
                            WHERE tsp.ic_id = $ic_id 
                            AND tsp.policy_status_id = 3 
                            AND (tsp.user_id != 2871 OR tsp.user_id != 2872) 
                            AND  DATE(tsp.`created_date`) 
                            BETWEEN '$from_date' AND '$to_date' ";
                  $result['result'] =  $this->db->query($sql)->result_array();
                  $result['num_rows'] = $this->db->query($sql)->num_rows();
                  return $result;
    }


    public function getRsaPolicyDetail($where){
      $admin_session = $this->session->userdata('admin_session');
        $ic_id = $admin_session['ic_id'];
       if($admin_session['admin_role']=='zone_code'){
           $zone_code_id = $admin_session['admin_role_id'];
           $zone_code = " AND tzm.`zone_code` = '$zone_code_id' " ;
           
       }else{
                $zone_code = '';
       }
        if(!empty($ic_id)){
            $condition = "AND (tsp.`ic_id`='$ic_id' OR tsp.`rsa_ic_id`='$ic_id')";
        }else{
           $condition = "";
        }
        $sql = " 
                 SELECT 
                  tsp.*,
                  tsp.`id` AS sold_id,
                  tsp.`sold_policy_price_without_tax` AS basic_premium,
                  tsp.`sold_policy_price_with_tax` AS total_premium,
                  DATE(tsp.`sold_policy_date`) AS policy_start_date,
                  TIME(tsp.`sold_policy_date`) AS policy_start_time,
                  tcs.*,
                  CONCAT(tcs.`fname`, ' ', tcs.`lname`) AS customer_name,
                  tp.`dealer_commission`,
                  tp.`gst_amount`,
                  tp.`sum_insured`,
                  tp.`plan_code`,
                  td.`sap_ad_code` ,td.`dealer_name` ,tzm.`zone`,tzm.`zone_code`
                FROM
                  tvs_sold_policies tsp 
                  INNER JOIN tvs_customer_details tcs 
                    ON tsp.`customer_id` = tcs.`id` 
                  INNER JOIN tvs_plans tp 
                    ON tsp.`plan_id` = tp.`id` 
                  INNER JOIN tvs_dealers td 
                    ON tsp.`user_id` = td.`id`  
                  LEFT JOIN tvs_dealer_zone_mapper tzm ON td.`zone_id` = tzm.`id`
                  WHERE tsp.policy_status_id = 3 $where $condition $zone_code
                  
                  AND (tsp.user_id != 2871 
                  OR tsp.user_id != 2872)
                ORDER BY tsp.`id` DESC

    ";
    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows();
    return $result;

    }
public function getPAEndorseFeedfile($ic_id,$from_date,$to_date){
     $sql = " SELECT 
                      tsp.`chassis_no`,tsp.`created_date`,tsp.`customer_id`,tsp.`engine_no`,tsp.`id` AS sold_id,tsp.`model_id`,tsp.`make_name`,tsp.`model_name`,tsp.`plan_id`,tsp.`plan_name`,tsp.`policy_status_id`,tsp.`product_type`,tsp.`sold_policy_effective_date`,tsp.`sold_policy_date`,tsp.`sold_policy_end_date`,tsp.`sold_policy_no`,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,tsp.`sold_policy_tax`,tsp.`status`,tsp.`user_id`,tsp.`vehicle_registration_no`,tsp.`plan_id`, DATE(tsp.`sold_policy_date`) AS policy_start_date, TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.`addr1`,tcs.`addr2`,tcs.`city`,tcs.`city_name`,tcs.`state`,tcs.`dob`,tcs.`email`,tcs.`fname`,tcs.`lname`,tcs.`gender`,tcs.`mobile_no`,tcs.`nominee_age`,tcs.`nominee_full_name`,tcs.`nominee_relation`,tcs.`pan_card_no`,tcs.`pincode`,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,tp.`dealer_commission`,tp.`gst_amount` , tp.`plan_code` FROM tvs_sold_policies tsp JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` WHERE tsp.ic_id = $ic_id AND tsp.policy_status_id = 3 AND (tsp.user_id != 2871 OR tsp.user_id != 2872) AND tcs.`updated_at` IS NOT NULL AND  DATE(tcs.`updated_at`) BETWEEN '$from_date' AND '$to_date' ";
                  $result['result'] =  $this->db->query($sql)->result_array();
                  $result['num_rows'] = $this->db->query($sql)->num_rows();
                  return $result;
}

public function getRSAEndoresfeedfile($from_date,$to_date,$ic_id){
     $sql = " SELECT 
                  tsp.`chassis_no`,tsp.`created_date`,tsp.`customer_id`,tsp.`engine_no`,tsp.`id` AS sold_id,tsp.`model_id`,tsp.`make_name`,tsp.`model_name`,tsp.`plan_id`,tsp.`plan_name`,tsp.`policy_status_id`,tsp.`product_type`,tsp.`sold_policy_effective_date`,tsp.`sold_policy_date`,tsp.`sold_policy_end_date`,tsp.`sold_policy_no`,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,tsp.`sold_policy_tax`,tsp.`status`,tsp.`user_id`,tsp.`vehicle_registration_no`,tsp.`plan_id`, DATE(tsp.`sold_policy_date`) AS policy_start_date, TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.`addr1`,tcs.`addr2`,tcs.`city`,tcs.`city_name`,tcs.`state`,tcs.`dob`,tcs.`email`,tcs.`fname`,tcs.`lname`,tcs.`gender`,tcs.`mobile_no`,tcs.`nominee_age`,tcs.`nominee_full_name`,tcs.`nominee_relation`,tcs.`pan_card_no`,tcs.`pincode`,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,tp.`dealer_commission`,tp.`gst_amount` , tp.`plan_code` FROM tvs_sold_policies tsp JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id`
                   WHERE tsp.user_id != 2871 
                   AND tsp.policy_status_id = 3 
                   AND tcs.`updated_at` IS NOT NULL 
                   AND  DATE(tcs.`updated_at`) BETWEEN '$from_date' AND '$to_date' 
                   AND tsp.`rsa_ic_id` = '$ic_id'  ";
                      // die($sql);
                      $result['result'] =  $this->db->query($sql)->result_array();
                      $result['num_rows'] = $this->db->query($sql)->num_rows();
                      return $result;
}
public function getEndorsePolicyData($where){
    $admin_session = $this->session->userdata('admin_session');
        $ic_id = $admin_session['ic_id'];
        if(!empty($ic_id)){
            $condition = "AND (tsp.`ic_id`='$ic_id' OR tsp.`rsa_ic_id`='$ic_id')";
        }else{
           $condition = "";
        }

        $sql = " 
                    SELECT 
                    tsp.`id` AS sold_id,tsp.* ,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,DATE(tsp.`sold_policy_date`) AS policy_start_date,TIME(tsp.`sold_policy_date`) AS policy_start_time, CONCAT(tcs.`fname`, ' ', tcs.`lname`) AS customer_name,tcs.*,tp.`dealer_commission`,tp.`gst_amount`,tp.`plan_code`,td.`sap_ad_code` 
                    FROM
                      tvs_sold_policies tsp 
                      JOIN tvs_customer_details tcs 
                        ON tsp.`customer_id` = tcs.`id` 
                      JOIN tvs_plans tp 
                        ON tsp.`plan_id` = tp.`id` 
                      JOIN tvs_dealers td 
                        ON tsp.`user_id` = td.`id` 
                    WHERE tsp.user_id != 2871 
                      AND tcs.`updated_at` IS NOT NULL $where AND tsp.policy_status_id=3 $condition
                            " ;
                // echo $sql;die;

                $result['result'] =  $this->db->query($sql)->result_array();
                $result['num_rows'] = $this->db->query($sql)->num_rows();
                return $result;
    }
function getPACanceledFeedfile($ic_id,$from_date,$to_date){
     $sql = " SELECT 
                  tsp.`chassis_no`,tsp.`created_date`,tsp.`customer_id`,tsp.`engine_no`,tsp.`id` AS sold_id,tsp.`model_id`,tsp.`make_name`,tsp.`model_name`,tsp.`plan_id`,tsp.`plan_name`,tsp.`policy_status_id`,tsp.`product_type`,tsp.`sold_policy_effective_date`,tsp.`sold_policy_date`,tsp.`sold_policy_end_date`,tsp.`sold_policy_no`,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,tsp.`sold_policy_tax`,tsp.`status`,tsp.`user_id`,tsp.`vehicle_registration_no`,tsp.`plan_id`, DATE(tsp.`sold_policy_date`) AS policy_start_date, TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.`addr1`,tcs.`addr2`,tcs.`city`,tcs.`city_name`,tcs.`state`,tcs.`dob`,tcs.`email`,tcs.`fname`,tcs.`lname`,tcs.`gender`,tcs.`mobile_no`,tcs.`nominee_age`,tcs.`nominee_full_name`,tcs.`nominee_relation`,tcs.`pan_card_no`,tcs.`pincode`,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,tp.`dealer_commission`,tp.`gst_amount` , tp.`plan_code` FROM tvs_sold_policies tsp JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` WHERE tsp.ic_id = '$ic_id' AND tsp.policy_status_id = 5 AND (tsp.user_id != 2871 OR tsp.user_id != 2872) AND  DATE(tsp.`created_date`) BETWEEN '$from_date' AND '$to_date' ";
              $result['result'] =  $this->db->query($sql)->result_array();
              $result['num_rows'] = $this->db->query($sql)->num_rows();
              return $result;
}

function getRSACanceledFeedfile($from_date,$to_date,$ic_id){
    $sql = " SELECT 
                  tsp.`chassis_no`,tsp.`created_date`,tsp.`customer_id`,tsp.`engine_no`,tsp.`id` AS sold_id,tsp.`model_id`,tsp.`make_name`,tsp.`model_name`,tsp.`plan_id`,tsp.`plan_name`,tsp.`policy_status_id`,tsp.`product_type`,tsp.`sold_policy_effective_date`,tsp.`sold_policy_date`,tsp.`sold_policy_end_date`,tsp.`sold_policy_no`,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,tsp.`sold_policy_tax`,tsp.`status`,tsp.`user_id`,tsp.`vehicle_registration_no`,tsp.`plan_id`, DATE(tsp.`sold_policy_date`) AS policy_start_date, TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.`addr1`,tcs.`addr2`,tcs.`city`,tcs.`city_name`,tcs.`state`,tcs.`state_name`,tcs.`dob`,tcs.`email`,tcs.`fname`,tcs.`lname`,tcs.`gender`,tcs.`mobile_no`,tcs.`nominee_age`,tcs.`nominee_full_name`,tcs.`nominee_relation`,tcs.`pan_card_no`,tcs.`pincode`,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,tp.`dealer_commission`,tp.`gst_amount` , tp.`plan_code` FROM tvs_sold_policies tsp JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id`
                  WHERE tsp.user_id != 2871 
                  AND tsp.policy_status_id = 5 
                  AND  DATE(tsp.`created_date`) BETWEEN '$from_date' AND '$to_date' 
                  AND tsp.`rsa_ic_id` = '$ic_id'  ";
                      // die($sql);
                      $result['result'] =  $this->db->query($sql)->result_array();
                      $result['num_rows'] = $this->db->query($sql)->num_rows();
                      return $result;
}

    public function getMasterPolicyDetailByState($state_id){
        $sql = "SELECT * FROM ic_pa_master_policy_nos WHERE FIND_IN_SET($state_id,mp_state_ids)";
        $return_data = $this->db->query($sql)->row_array();
        return $return_data;
    }   

    public function getDealerinfo($user_id){
        $result = $this->db->query("SELECT tds.*,tic.* FROM tvs_dealers tds JOIN tvs_insurance_companies tic ON tds.`rsa_ic_master_id` = tic.`id` WHERE tds.`id` = '$user_id' ")->row_array();
        return $result;
    }


    public function getDealerDocumentData($dealer_id){
        $result = $this->db->query(" SELECT td.*,td.id as tvs_dealer_id,(dw.security_amount-dw.credit_amount) AS dealer_wallet,tdd.* FROM tvs_dealers td LEFT JOIN dealer_wallet dw ON td.id = dw.dealer_id LEFT JOIN tvs_dealer_documents tdd ON td.id = tdd.dealer_id where td.id = '$dealer_id'  ")->row_array();

        return $result ;
    }

    public function DealerActivityReport(){
        $sql = " SELECT td.sap_ad_code,td.dealer_type,td.dealer_name,td.mobile,td.state,td.location,
                IF(tdd.id != '','Logged In','Logged In') AS logged_in,
                IF(tdd.`agreement` != '','Uploaded','Not Uploaded') AS agreement_pdf,
                IF(tdd.`pan_card` != '','Uploaded','Not Uploaded') AS pan_card,
                IF(tdd.`gst_certificate` != '','Uploaded','Not Uploaded') AS gst_certificate,
                IF(tdd.`cancel_cheque` != '','Uploaded','Not Uploaded') AS cancel_cheque,
                IF(dw.id != '','Done','Not Done') AS payment_status,
                IF(tsp.id != '','Yes','No') AS Is_Sold_Policy
                FROM tvs_dealers AS td 
                INNER JOIN redirect_key_log AS rkl ON td.`sap_ad_code` = rkl.`sap_ad_code`
                LEFT JOIN `tvs_dealer_documents` AS tdd ON td.id = tdd.`dealer_id`
                LEFT JOIN dealer_wallet AS dw ON td.id = dw.`dealer_id`
                LEFT JOIN tvs_sold_policies AS tsp ON td.id = tsp.`user_id`
                WHERE td.id != 2871 
                GROUP BY td.id " ;

                $result['result'] =  $this->db->query($sql)->result_array();
                $result['num_rows'] = $this->db->query($sql)->num_rows();
                return $result;
    }

    public function lastweek_soldpolicy(){
        $sql = " 
            SELECT td.sap_ad_code as dealer_code,td.`dealer_name`,
            SUM(DATE(tsp.`created_date`) = CURDATE()) AS today,
            SUM(DATE(tsp.`created_date`) = SUBDATE(CURDATE(),1)) AS T_1,
            SUM(DATE(tsp.`created_date`) = SUBDATE(CURDATE(),2)) AS T_2,
            SUM(DATE(tsp.`created_date`) = SUBDATE(CURDATE(),3)) AS T_3,
            SUM(DATE(tsp.`created_date`) = SUBDATE(CURDATE(),4)) AS T_4,
            SUM(DATE(tsp.`created_date`) = SUBDATE(CURDATE(),5)) AS T_5,
            SUM(DATE(tsp.`created_date`) = SUBDATE(CURDATE(),6)) AS T_6,
            SUM(DATE(tsp.`created_date`) = SUBDATE(CURDATE(),7)) AS T_7,
            SUM(DATE(tsp.`created_date`) = SUBDATE(CURDATE(),8)) AS T_8,
            SUM(DATE(tsp.`created_date`) = SUBDATE(CURDATE(),9)) AS T_9,
            SUM(DATE(tsp.`created_date`) = SUBDATE(CURDATE(),10)) AS T_10,
            SUM(DATE(tsp.`created_date`) = SUBDATE(CURDATE(),11)) AS T_11,
            COUNT(tsp.id) AS total_policies
            FROM tvs_dealers AS td
            LEFT JOIN tvs_sold_policies AS tsp ON td.id = tsp.`user_id`
            WHERE tsp.`policy_status_id` = 3 AND tsp.`user_id` <> 2871
            GROUP BY td.`sap_ad_code` " ;

            $result['result'] =  $this->db->query($sql)->result_array();
            $result['num_rows'] = $this->db->query($sql)->num_rows();
            return $result;
    }

 public function getExistInvoiceDetails($invoice_no,$dealer_id){
        $result = $this->db->query(" SELECT COUNT(id) AS count_invoice FROM invoice_details WHERE invoice_no = '$invoice_no' AND dealer_id = '$dealer_id'  ")->row_array();
        return $result ;
    }


    
}
