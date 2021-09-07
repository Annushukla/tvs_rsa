<?php

class Home_Model_New extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getmytvspolicyDetail($where){
      $admin_session = $this->session->userdata('admin_session');
      
       // please put this condition in where on live (AND tsp.user_id <> 2871)
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
                  WHERE tsp.policy_status_id IN (3,4) $where
                  AND tsp.`rsa_ic_id`=11 
                ORDER BY tsp.`id` DESC
    ";
    // die($sql);
    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows();
    return $result;

    }

    public function getLimitlessAssistBhartipolicyDetail($where){
      $admin_session = $this->session->userdata('admin_session');
      
       // please put this condition in where on live (AND tsp.user_id <> 2871)
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
                  WHERE tsp.policy_status_id IN (3,4) $where
                  AND tsp.`rsa_ic_id`=1 AND tsp.`ic_id`=0 AND tsp.`plan_id`=62
                ORDER BY tsp.`id` DESC
    ";
    //die($sql);
    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows();
    return $result;

    }

    public function getLimitlessAssistMyTvspolicyDetail($where){
      $admin_session = $this->session->userdata('admin_session');
      
       // please put this condition in where on live (AND tsp.user_id <> 2871)
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
                  WHERE tsp.policy_status_id IN (3,4) $where
                  AND tsp.`rsa_ic_id`=11 AND tsp.`ic_id`=0 AND tsp.`plan_id`=62
                ORDER BY tsp.`id` DESC
    ";
    // die($sql);
    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows();
    return $result;

    }

    public function getLimitlessAssistEBhartipolicyDetail($where){
      $admin_session = $this->session->userdata('admin_session');
      
       // please put this condition in where on live (AND tsp.user_id <> 2871)
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
                  WHERE tsp.policy_status_id IN (3,4) $where 
                  AND tsp.`rsa_ic_id`=1 AND tsp.`ic_id`=0 AND tsp.`plan_id`=64
                ORDER BY tsp.`id` DESC
    ";
    //die($sql);
    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows();
    return $result;

    }

    public function getLimitlessAssistEMyTvspolicyDetail($where){
      $admin_session = $this->session->userdata('admin_session');
      
       // please put this condition in where on live (AND tsp.user_id <> 2871)
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
                  WHERE tsp.policy_status_id IN (3,4) $where
                  AND tsp.`rsa_ic_id`=11 AND tsp.`ic_id`=0 AND tsp.`plan_id`=64
                ORDER BY tsp.`id` DESC
    ";
    //die($sql);
    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows();
    return $result;

    }

    public function getLimitlessAssistRenewBhartipolicyDetail($where){
      $admin_session = $this->session->userdata('admin_session');
             // please put this condition in where on live (AND tsp.user_id <> 2871)
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
                  WHERE tsp.policy_status_id IN (3,4) $where 
                  AND tsp.`rsa_ic_id`=1 AND tsp.`ic_id`=0 AND tsp.`plan_id`=63
                ORDER BY tsp.`id` DESC
    ";
    //die($sql);
    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows();
    return $result;

    }

    public function getLimitlessAssistRenewMyTvspolicyDetail($where){
      $admin_session = $this->session->userdata('admin_session');
      
       // please put this condition in where on live (AND tsp.user_id <> 2871)
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
                  WHERE tsp.policy_status_id IN (3,4) $where 
                  AND tsp.`rsa_ic_id`=11 AND tsp.`ic_id`=0 AND tsp.`plan_id`=63
                ORDER BY tsp.`id` DESC
    ";
    //die($sql);
    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows();
    return $result;

    }

    public function totallimitlessPolicies(){
      $sql="SELECT COUNT(IF(tsp.`plan_id`=62 AND DATE(tsp.`created_date`)=CURDATE() ,1,NULL)) AS td_limitless_new,
            COUNT(IF(tsp.`plan_id`=63 AND DATE(tsp.`created_date`)=CURDATE() ,1,NULL)) AS td_limitless_renew,
            COUNT(IF(tsp.`plan_id`=64 AND DATE(tsp.`created_date`)=CURDATE() ,1,NULL)) AS td_limitless_online,
            COUNT(IF(tsp.`plan_id`=62 AND MONTH(tsp.`created_date`)= MONTH(CURDATE()) ,1,NULL)) AS mtd_limitless_new,
            COUNT(IF(tsp.`plan_id`=63 AND MONTH(tsp.`created_date`)= MONTH(CURDATE()) ,1,NULL)) AS mtd_limitless_renew,
            COUNT(IF(tsp.`plan_id`=64 AND MONTH(tsp.`created_date`)= MONTH(CURDATE()) ,1,NULL)) AS mtd_limitless_online,
            COUNT(IF(tsp.`plan_id`=62 AND YEAR(tsp.`created_date`)= YEAR(CURDATE()) ,1,NULL)) AS ytd_limitless_new,
            COUNT(IF(tsp.`plan_id`=63 AND YEAR(tsp.`created_date`)= YEAR(CURDATE()) ,1,NULL)) AS ytd_limitless_renew,
            COUNT(IF(tsp.`plan_id`=64 AND YEAR(tsp.`created_date`)= YEAR(CURDATE()) ,1,NULL)) AS ytd_limitless_online 
            FROM `tvs_sold_policies` tsp WHERE tsp.policy_status_id IN (3,4) AND tsp.`ic_id`=0" AND tsp.`user_id`<>0;
         // echo $sql;die('anu');

      $query = $this->db->query($sql);
      $result = $query->result_array();
      return $result;
    }

  function getPendingPolicyByID($policy_id){
    $sql = "SELECT tsp.id AS policy_id,tsp.user_id,tsp.master_policy_no,tsp.mp_start_date,tsp.mp_end_date,tsp.product_type_name,
            tsp.plan_id,tsp.created_date AS created_at,tsp.plan_name,tsp.sold_policy_no,tsp.engine_no,tsp.chassis_no,tsp.created_date,
            tsp.vehicle_registration_no,tsp.sold_policy_effective_date,tsp.sold_policy_end_date,tsp.pa_sold_policy_effective_date,
            tsp.pa_sold_policy_end_date,tsp.model_name,tsp.model_id,tsp.make_id,tsp.ic_id,tsp.rsa_ic_id,tsp.customer_id,
            ts.name AS state_name1, tc.name AS city_name1,tcd.*,tp.plan_type_id 
            FROM tvs_pending_sold_policies AS tsp INNER JOIN tvs_customer_details AS tcd ON tcd.id = tsp.customer_id 
            INNER JOIN tvs_plans AS tp ON tp.id = tsp.plan_id LEFT JOIN tvs_state AS ts ON ts.id = tcd.state 
            LEFT JOIN tvs_city AS tc ON tc.id = tcd.city WHERE tsp.id = '$policy_id' ";

    $result = $this->db->query($sql)->row_array();
    return $result;
  }

  

}
?>