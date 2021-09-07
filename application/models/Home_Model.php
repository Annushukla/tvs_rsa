<?php

class Home_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

     function totalSoldPolicies(){
        $plan_sql = "SELECT id,plan_name FROM tvs_plans";
        $query = $this->db->query($plan_sql);
        $tvs_plans = $query->result_array();
        $plan_ids =array();
        $silver_ids="";
        $gold_ids="";
        $platinum_ids="";
        $sapphire_ids="";
        $sapphire_plus_ids="";
        foreach ($tvs_plans as $key => $tvs_plan) {
            switch ($tvs_plan['plan_name']) {
                case 'Silver':
                    $plan_ids['Silver'][] = $tvs_plan['id'];
                    $silver_ids = implode(',', $plan_ids['Silver']);
                    break;
                case 'Gold':
                    $plan_ids['Gold'][] = $tvs_plan['id'];
                    $gold_ids = implode(',', $plan_ids['Gold']);
                    break;
                case 'Platinum':
                    $plan_ids['Platinum'][] = $tvs_plan['id'];
                    $platinum_ids = implode(',', $plan_ids['Platinum']);
                    break;
                case 'Sapphire':
                    $plan_ids['Sapphire'][] = $tvs_plan['id'];
                    $sapphire_ids = implode(',', $plan_ids['Sapphire']);
                    break;
                case 'Sapphire Plus':
                    $plan_ids['Sapphire_plus'][] = $tvs_plan['id'];
                    $sapphire_plus_ids = implode(',', $plan_ids['Sapphire_plus']);
                    break;

                case 'LIMITLESS ASSIST(RR 310)':
                    $plan_ids['limitless_assist_RR310'][] = $tvs_plan['id'];
                    $limitless_assist_RR310_ids = implode(',',$plan_ids['limitless_assist_RR310']);
                    break;

                case 'LIMITLESS ASSIST RENEWAL':
                    $plan_ids['limitless_assistrenew_RR310'][] = $tvs_plan['id'];
                    $limitless_assistrenew_RR310_ids = implode(',',$plan_ids['limitless_assistrenew_RR310']);
                    break;

                case 'LIMITLESS ASSIST E (RR 310)':
                    $plan_ids['limitless_assistE_RR310'][] = $tvs_plan['id'];
                    $limitless_assistE_RR310_ids = implode(',', $plan_ids['limitless_assistE_RR310']);
                    break;

            }
        }        
       // echo '<pre>'; print_r($limitless_assistE_RR310_ids);die('here');

    $sql = "SELECT tic.id AS ic_id,tic.name,pw.balance_amount,
    COUNT(IF(tsp.rsa_ic_id IN (1,11),1,NULL)) AS total_policies,
    COUNT(IF((tsp.rsa_ic_id IN (1,11) AND DATE(tsp.created_date) = CURDATE()),1,NULL)) AS todays_policies,
    COUNT(IF(tsp.plan_id IN ($silver_ids),1,NULL)) AS silver_policies,
    COUNT(IF((tsp.plan_id IN ($gold_ids)),1,NULL)) AS gold_policies,
    COUNT(IF((tsp.plan_id IN ($platinum_ids)),1,NULL)) AS platinum_policies,
    COUNT(IF((tsp.plan_id IN ($sapphire_ids)),1,NULL)) AS sapphire_policies,
    COUNT(IF((tsp.plan_id IN ($sapphire_plus_ids)),1,NULL)) AS sapphire_plus_policies,
    COUNT(IF((tsp.plan_id IN (62)) AND DATE(tsp.created_date)=CURDATE(),1,NULL)) AS td_limitless_assist_RR310_policies, 
    COUNT(IF((tsp.plan_id IN (63)) AND DATE(tsp.created_date)=CURDATE(),1,NULL)) AS td_limitless_assist_RR310renew_policies,
    COUNT(IF((tsp.plan_id IN (64)) AND DATE(tsp.created_date)=CURDATE(),1,NULL)) AS td_limitless_assistE_RR310_policies,
    COUNT(IF((tsp.plan_id IN ($limitless_assist_RR310_ids)),1,NULL)) AS limitless_assist_RR310_policies,
    COUNT(IF((tsp.plan_id IN ($limitless_assistrenew_RR310_ids)),1,NULL)) AS limitless_assistrenew_RR310_policies,
    COUNT(IF((tsp.plan_id IN ($limitless_assistE_RR310_ids)),1,NULL)) AS limitless_assistE_RR310_policies,
    COUNT(IF(tsp.`plan_name` IN ('Sapphire','Sapphire Plus') ,1,NULL)) AS rsa_tenure_2_count,
    COUNT(IF(tsp.`plan_name` NOT IN ('Sapphire','Sapphire Plus') ,1,NULL)) AS rsa_tenure_1_count

            FROM tvs_insurance_companies AS tic 
            INNER JOIN tvs_sold_policies AS tsp ON (tsp.ic_id = tic.id || tsp.rsa_ic_id = tic.id)
            INNER JOIN tvs_plans AS tp ON tsp.plan_id = tp.id
            LEFT JOIN party_wallet AS pw ON pw.party_id = tic.id
            WHERE tsp.policy_status_id = 3
            AND tsp.user_id <> 0
            GROUP BY tic.`id` ";
            //die($sql);
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
    }

    function todaysplansoldpolicies($ic_id){
        // please add this to where condition on live user_id <> 2871
        $sql="SELECT COUNT(tsp.`id`) as 'count',tsp.`plan_name`,tsp.`plan_id` FROM tvs_sold_policies tsp WHERE tsp.ic_id='".$ic_id."' AND tsp.`policy_status_id` IN (3,4) AND DATE(tsp.created_date) = CURDATE() GROUP BY tsp.`plan_name` ORDER BY tsp.plan_name";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function getKotakPlansOnly($plan_type_id){
        $sql = "select * from tvs_plans WHERE id IN ('51','52') AND plan_type_id = $plan_type_id";
        // die($sql);
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    function getsold_policy_data($dealer_id){
        $result = $this->db->query("SELECT * FROM tvs_sold_policies WHERE MONTH(created_date) = '".date('m', strtotime("-1 month"))."' AND policy_status_id=3 AND user_id = '$dealer_id'")->result_array();
        return $result;
    }

    function getInvoiceData($dealer_id){
        $invoice_data = $this->db->query("SELECT * FROM `invoice_details` ind WHERE ind.`invoice_month` = '".date('m-Y', strtotime("-1 month"))."' AND ind.dealer_id = '$dealer_id' AND ind.`invoice_status`='approved' ")->row_array();

        return $invoice_data ;
    }

    function getConfirmedOriental($dealer_id){
        $sql ="SELECT * FROM tvs_dealers WHERE id='$dealer_id' AND is_oriental_confirmed=1";
        // die($sql);
        $result = $this->db->query($sql)->row_array();

        return $result ;
    }
    function getOrientalCount($from_date){
        $sql = "SELECT COUNT(id) AS count FROM tvs_sold_policies WHERE DATE(created_date) < '$from_date' AND policy_status_id IN (3,4) AND ic_id=10 AND user_id <> 2871";
        $result = $this->db->query($sql)->row_array();
        return $result ;
    }
    function cheeckIsBankPaymentApproved($post_data){
        $dealer_id = $post_data['dealer_id'];
        $dealer_bank_tran_id = $post_data['UTR'];
        $sql = "SELECT * FROM dealer_bank_transaction WHERE dealer_id = '$dealer_id' AND bank_transaction_no ='$dealer_bank_tran_id' ";
        $result = $this->db->query($sql)->row_array();
        return $result;
    }


// function last3MonthsPolicies(){
//     $Month = date('m', strtotime('month'));
//     $lastMonth = date('m', strtotime('last month'));
//     $twoMonthsAgo = date('m', strtotime('-2 months'));
//     // $threeMonthsAgo = date('m', strtotime('-3 months'));
//         $sql =" SELECT MONTHNAME(tsp.created_date) AS MONTH,
//                 COUNT(IF(tsp.rsa_ic_id = 1,1,NULL)) AS bharti_rsa_policies,
//                 COUNT(IF(tsp.rsa_ic_id = 11,1,NULL)) AS mytvs_rsa_policies,
//                 COUNT(IF(tsp.ic_id = 2,1,NULL)) AS kotak_policies,
//                 COUNT(IF(tsp.ic_id = 5,1,NULL)) AS il_policies,
//                 COUNT(IF(tsp.ic_id = 9,1,NULL)) AS tata_policies,
//                 COUNT(IF(tsp.ic_id = 10,1,NULL)) AS oriental_policies,
//                 COUNT(IF(tsp.ic_id = 12,1,NULL)) AS bagi_policies,
//                 COUNT(IF(tsp.ic_id = 8,1,NULL)) AS reliance_policies,
//                 COUNT(IF(tsp.ic_id = 13,1,NULL)) AS liberty_policies,
//                 COUNT(IF(tsp.ic_id = 7,1,NULL)) AS hdfc_policies,
//                 COUNT(IF((tsp.rsa_ic_id = 1) AND (tsp.plan_id=62),1,NULL)) AS limitless_assist_RR310_bharti,
//                 COUNT(IF((tsp.rsa_ic_id = 1) AND (tsp.plan_id=63),1,NULL)) AS limitless_assistrenew_RR310_bharti,
//                 COUNT(IF((tsp.rsa_ic_id = 11) AND (tsp.plan_id=62),1,NULL)) AS limitless_assist_RR310_mytvs,
//                 COUNT(IF((tsp.rsa_ic_id = 11) AND (tsp.plan_id=63),1,NULL)) AS limitless_assistrenew_RR310_mytvs,
//                 COUNT(IF((tsp.rsa_ic_id = 1) AND (tsp.plan_id=64),1,NULL)) AS limitless_assistE_RR310_bharti,
//                 COUNT(IF((tsp.rsa_ic_id = 11) AND (tsp.plan_id=64),1,NULL)) AS limitless_assistE_RR310_mytvs
//                 FROM tvs_sold_policies AS tsp WHERE tsp.`policy_status_id`=3 AND MONTH(created_date) IN ($Month,$lastMonth,$twoMonthsAgo) 
//                 GROUP BY MONTH(created_date)='$Month',MONTH(created_date)='$lastMonth',MONTH(created_date)='$twoMonthsAgo' ";

//         //AND tsp.`user_id`!=2871 put on live in above query
//         $query = $this->db->query($sql);
//         $result = $query->result_array();

       
//         return $result;
//     }

    function last3MonthsPolicies(){
        $sql =" SELECT YEAR(tsp.`created_date`) AS policy_year, MONTHNAME(tsp.created_date) AS MONTH, DATE_FORMAT(tsp.created_date,'%m') AS month_no,
                COUNT(IF(tsp.rsa_ic_id = 1,1,NULL)) AS bharti_rsa_policies,
                COUNT(IF(tsp.rsa_ic_id = 11,1,NULL)) AS mytvs_rsa_policies,
                COUNT(IF(tsp.ic_id = 2,1,NULL)) AS kotak_policies,
                COUNT(IF(tsp.ic_id = 5,1,NULL)) AS il_policies,
                COUNT(IF(tsp.ic_id = 9,1,NULL)) AS tata_policies,
                COUNT(IF(tsp.ic_id = 10,1,NULL)) AS oriental_policies,
                COUNT(IF(tsp.ic_id = 12,1,NULL)) AS bagi_policies,
                COUNT(IF(tsp.ic_id = 8,1,NULL)) AS reliance_policies,
                COUNT(IF(tsp.ic_id = 13,1,NULL)) AS liberty_policies,
                COUNT(IF(tsp.ic_id = 7,1,NULL)) AS hdfc_policies,
                COUNT(IF((tsp.rsa_ic_id = 1) AND (tsp.plan_id=62),1,NULL)) AS limitless_assist_RR310_bharti,
                COUNT(IF((tsp.rsa_ic_id = 1) AND (tsp.plan_id=63),1,NULL)) AS limitless_assistrenew_RR310_bharti,
                COUNT(IF((tsp.rsa_ic_id = 11) AND (tsp.plan_id=62),1,NULL)) AS limitless_assist_RR310_mytvs,
                COUNT(IF((tsp.rsa_ic_id = 11) AND (tsp.plan_id=63),1,NULL)) AS limitless_assistrenew_RR310_mytvs,
                COUNT(IF((tsp.rsa_ic_id = 1) AND (tsp.plan_id=64),1,NULL)) AS limitless_assistE_RR310_bharti,
                COUNT(IF((tsp.rsa_ic_id = 11) AND (tsp.plan_id=64),1,NULL)) AS limitless_assistE_RR310_mytvs
                FROM tvs_sold_policies AS tsp WHERE tsp.`policy_status_id`=3
                GROUP BY YEAR(tsp.`created_date`), MONTH(tsp.`created_date`) ORDER BY YEAR(tsp.`created_date`) DESC, MONTH(tsp.`created_date`) DESC LIMIT 3 ";

                // -- GROUP BY MONTH ORDER BY month_no DESC LIMIT 3 ";

        //AND tsp.`user_id`!=2871 put on live in above query
        $query = $this->db->query($sql);
        $result = $query->result_array();

       
        return $result;
    }

    function topThreeDealers(){
        $sql = "SELECT td.sap_ad_code, td.dealer_name,COUNT(tsp.id) AS todays_policies 
                FROM tvs_sold_policies AS tsp 
                INNER JOIN tvs_dealers AS td ON td.id=tsp.`user_id`
                WHERE DATE(tsp.`created_date`) = CURDATE()
                GROUP BY td.`sap_ad_code`
                ORDER BY todays_policies DESC
                LIMIT 3";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
    }

    function liability(){
        $sql = "SELECT (SELECT SUM(deposit_amount) FROM dealer_bank_transaction WHERE transaction_type = 'deposit' AND dealer_id NOT IN (2871,2872) AND approval_status= 'approved') - (SELECT SUM(sold_policy_price_with_tax) FROM tvs_sold_policies WHERE user_id NOT IN (2871,2872) AND policy_status_id=3) AS liability";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result['liability'];        
    }

    function deposit_status(){
        /*$sql = "SELECT SUM(IF(DATE(created_date) = CURDATE(),deposit_amount,0)) AS td_deposit_amount,
                SUM(IF(MONTH(created_date) = MONTH(CURRENT_DATE()),deposit_amount,0)) AS mtd_deposit_amount,
                SUM(IF(YEAR(created_date) = YEAR(CURRENT_DATE()),deposit_amount,0)) AS ytd_deposit_amount FROM dealer_bank_transaction WHERE transaction_type = 'deposit' AND dealer_id NOT IN (2871,2872) AND reconcile_status= 'approved'";*/
        $sql =  "SELECT 
                    SUM(IF(YEAR(dbt.created_date) = YEAR(CURDATE()),dbt.`deposit_amount`,0)) AS ytd_deposit_amount,
                    SUM(IF(MONTH(dbt.created_date) = MONTH(CURDATE()) ,dbt.`deposit_amount`,0)) AS mtd_deposit_amount,
                    SUM(IF(DATE(dbt.`created_date`) = CURDATE(),dbt.`deposit_amount`,0)) AS td_deposit_amount
                    FROM dealer_bank_transaction dbt WHERE dbt.`transaction_type`='deposit'
                    AND dbt.`approval_status`='approved'";                
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function sold_policies(){
        $sql = "SELECT COUNT(IF(YEAR(created_date) = YEAR(CURRENT_DATE()),1,NULL)) AS this_year_total_policies,
                       SUM(IF(DATE(created_date) = CURDATE(),sold_policy_price_with_tax,0)) AS td_sold_amount,
                       SUM(IF(MONTH(created_date) = MONTH(CURRENT_DATE()),sold_policy_price_with_tax,0)) AS mtd_sold_amount,
                       SUM(IF(YEAR(created_date) = YEAR(CURRENT_DATE()),sold_policy_price_with_tax,0)) AS ytd_sold_amount FROM tvs_sold_policies WHERE policy_status_id = 3 AND user_id NOT IN (2871,2872)";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function total_commission(){
        $sql = "SELECT SUM(transection_amount) as commission FROM dealer_transection_statement WHERE transection_purpose = 'Commission'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;        
    }

    function tvs_deposit(){
        $sql = "SELECT 
                SUM(IF((party_id = 14) AND (DATE(payment_date) = CURRENT_DATE()),amount,0)) AS td_tvs_deposit_amount,
                SUM(IF((party_id = 14) AND (MONTH(payment_date) = MONTH(CURRENT_DATE())),amount,0)) AS mtd_tvs_deposit_amount,
                SUM(IF((party_id = 14) AND (YEAR(payment_date) = YEAR(CURRENT_DATE())),amount,0)) AS ytd_tvs_deposit_amount,
                SUM(IF((party_id = 15) AND (YEAR(payment_date) = YEAR(CURRENT_DATE())),amount,0)) AS ytd_gst_deposit_amount,
                SUM(IF((party_id = 16) AND (YEAR(payment_date) = YEAR(CURRENT_DATE())),amount,0)) AS ytd_tds_deposit_amount FROM party_payment_details WHERE party_id IN (14,15,16)";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;        
    }        

       function getDashboardSummary(){
        $sql = "SELECT * from zoneicwisecounts";

        $query = $this->db->query($sql);
        $results = $query->result_array();

        //AND tsp.`user_id` <> 2871    
            /////////////////////////////////
          $sql = "SELECT * from zonewisetotaldepositamount";
            $query = $this->db->query($sql);
            $results1 = $query->result_array();
            // echo '<pre>'; print_r($results1);die('here');
            ///////////////////////////////

            //AND td.`dealer_code` <> 11111
        $sql = "SELECT * from zonewisedealerdata";
       // die($sql);
        // AND td.`dealer_code` <> 11111
        $query = $this->db->query($sql);
        $results2 = $query->result_array();
        // echo '<pre>'; print_r($results2);die('here');
        foreach ($results as $key => $result) {
            if(!empty($results1)){
                foreach ($results1 as $result1) {
                    if($result['state'] == $result1['state']){
                         $results[$key]['todays_deposit_amount'] = $result1['todays_deposit_amount'];
                         $results[$key]['last_month_deposit_amount'] = $result1['last_month_deposit_amount'];
                         $results[$key]['last_year_deposit_amount'] = $result1['last_year_deposit_amount'];
                    }
                }
            }
        }
        foreach ($results as $key => $result) {
            if(!empty($results2)){
                foreach ($results2 as $result2) {
                    if($result['state'] == $result2['state']){
                         $results[$key]['todays_active_dealers'] = $result2['todays_active_dealers'];
                         $results[$key]['last_month_active_dealers'] = $result2['last_month_active_dealers'];
                         $results[$key]['last_year_active_dealers'] = $result2['last_year_active_dealers'];
                         $results[$key]['active_dealers'] = $result2['active_dealers'];
                         $results[$key]['total_dealers'] = $result2['total_dealers'];
                         $results[$key]['available_wallet_balance'] = $result2['available_wallet_balance'];
                    }
                }
            }
       } 
       
         //echo '<pre>'; print_r($results);die('here');
         return $results;


    }

function getDealerSoldPolicies($invoice_month){
       $policy_date = date('Y-m-d',strtotime($invoice_month));
        $user_session = $this->session->userdata('user_session');
        $user_id = $user_session['id'];
        $plan_sql = "SELECT * from tvs_plans WHERE plan_name NOT IN('LIMITLESS ASSIST(RR 310)','LIMITLESS ASSIST E (RR 310)','Basic Service') GROUP BY plan_name";
        $query = $this->db->query($plan_sql);
        $tvs_plans = $query->result_array();
      //  echo "<pre>"; print_r($tvs_plans); echo "</pre>"; die('end of line yoyo');
        $plan_ids =array();
        $silver_ids="";
        $gold_ids="";
        $platinum_ids="";
        $sapphire_ids="";
        $sapphire_plus_ids="";
        $plans_query ='';
        if(!empty($tvs_plans)){
        foreach ($tvs_plans as $key => $tvs_plan) {
            $plan_amount = $tvs_plan['plan_amount'];
            $plan_code = $tvs_plan['plan_code'];
           $plan_name =  $tvs_plan['plan_name'];
           $dealer_commission =  $tvs_plan['dealer_commission'];
           $plan_name_column = $plan_name;
            if ($plan_name == trim($plan_name) && strpos($plan_name, ' ') !== false) {
                    $plan_name_column = str_replace(' ', '_', $plan_name);
                }
            $plans_query .="
            SUM(IF((tsp.`plan_name` ='$plan_name' && tsp.`policy_status_id` IN (3,4) && dts.`transection_purpose` = 'Commission'),".$dealer_commission.",NULL)) AS 
            ".strtolower($plan_name_column)."_policy_commission,
            (SUM(IF((tsp.`plan_name` ='$plan_name' && tsp.`policy_status_id` IN (3,4) && dts.`transection_purpose` = 'Commission'),".$dealer_commission.",NULL))*0.18) AS ".strtolower($plan_name_column)."_policy_commission_gst,
            SUM(IF((tsp.`plan_name` ='$plan_name' && tsp.`policy_status_id` IN (5) && dts.`transection_purpose` = 'Commission diducted'),".$dealer_commission.",NULL)) AS ".strtolower($plan_name_column)."_policy_cancelled_commission,
            (SUM(IF((tsp.`plan_name` ='$plan_name' && tsp.`policy_status_id` IN (5) && dts.`transection_purpose` = 'Commission diducted'),".$dealer_commission.",NULL))*0.18) AS ".strtolower($plan_name_column)."_policy_cancelled_commission_gst,
            COUNT(IF((tsp.`plan_name` ='$plan_name' && tsp.`policy_status_id` IN (3,4) && dts.`transection_purpose` = 'Commission'),1,NULL)) AS count_".strtolower($plan_name_column)."_policies,
            COUNT(IF((tsp.`plan_name` ='$plan_name' && tsp.`policy_status_id` IN (5) && dts.`transection_purpose` = 'Commission diducted'),1,NULL)) AS count_".strtolower($plan_name_column)."_policies_cancelled,
            (COUNT(IF((tsp.`plan_name` ='$plan_name' && tsp.`policy_status_id` IN (3,4) && dts.`transection_purpose` = 'Commission'),1,NULL))* (".$plan_amount.")) AS ".strtolower($plan_name_column)."_policy_premium,
            (COUNT(IF((tsp.`plan_name` ='$plan_name' && tsp.`policy_status_id` IN (5) && dts.`transection_purpose` = 'Commission diducted'),1,NULL))* (".$plan_amount.")) AS ".strtolower($plan_name_column)."_policy_cancelled_premium,
            ROUND(((COUNT(IF((tsp.`plan_name` ='$plan_name' && tsp.`policy_status_id` IN (3,4) && dts.`transection_purpose` = 'Commission'),1,NULL))* (".$plan_amount."))*0.18),2) AS ".strtolower($plan_name_column)."_policy_gst,
            ((COUNT(IF((tsp.`plan_name` ='$plan_name' && tsp.`policy_status_id` IN (5) && dts.`transection_purpose` = 'Commission diducted'),1,NULL))* (".$plan_amount."))*0.18) AS ".strtolower($plan_name_column)."_policy_cancelled_gst,
            (COUNT(IF((tsp.`plan_name` ='$plan_name' && tsp.`policy_status_id` IN (3,4) && dts.`transection_purpose` = 'Commission'),1,NULL))* (".$plan_code.")) AS ".strtolower($plan_name_column)."_total_policy_premium,
            (COUNT(IF((tsp.`plan_name` ='$plan_name' && tsp.`policy_status_id` IN (5) && dts.`transection_purpose` = 'Commission diducted'),1,NULL))* (".$plan_code.")) AS ".strtolower($plan_name_column)."_total_policy_cancelled_premium,
            " ;

                    }
                }
                // echo $plans_query;die('hello moto'); 
                   
                    $sql = "SELECT $plans_query td.*
            FROM  dealer_transection_statement AS dts
            INNER JOIN tvs_sold_policies AS tsp ON dts.`policy_id` = tsp.`id`
            INNER JOIN tvs_plans AS tp ON tp.`id` = tsp.`plan_id`
            INNER JOIN tvs_dealers AS td ON td.`id` = tsp.`user_id`
            WHERE   tsp.`user_id` = $user_id
            AND   MONTH(tsp.`created_date`) = MONTH('$policy_date')
            AND   YEAR(tsp.`created_date`)  =YEAR('$policy_date')";
             // die($sql);
            $query = $this->db->query($sql);
            $result = $query->row_array();
            return $result;
    }
        function getLayerTwoDetails(){
        $sql = "SELECT 
            SUM(IF(tsp.plan_name = 'Platinum' AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_platinum_policy_count,
            SUM(IF(tsp.plan_name = 'Platinum' AND tsp.ic_id = 2 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_kotak_platinum_policy_count,
            SUM(IF(tsp.plan_name = 'Platinum' AND tsp.ic_id = 5 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_il_platinum_policy_count,
            SUM(IF(tsp.plan_name = 'Platinum' AND tsp.ic_id = 9 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_tata_platinum_policy_count,
            SUM(IF(tsp.plan_name = 'Platinum' AND tsp.ic_id = 12 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_bagi_platinum_policy_count,
            
            SUM(IF(tsp.plan_name = 'Sapphire' AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_sapphire_policy_count,
            SUM(IF(tsp.plan_name = 'Sapphire' AND tsp.ic_id = 2 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_kotak_sapphire_policy_count,
            SUM(IF(tsp.plan_name = 'Sapphire' AND tsp.ic_id = 5 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_il_sapphire_policy_count,
            SUM(IF(tsp.plan_name = 'Sapphire' AND tsp.ic_id = 9 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_tata_sapphire_policy_count,
            SUM(IF(tsp.plan_name = 'Sapphire' AND tsp.ic_id = 12 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_bagi_sapphire_policy_count,
            
            SUM(IF(tsp.plan_name = 'Gold' AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_gold_policy_count,
            SUM(IF(tsp.plan_name = 'Gold' AND tsp.ic_id = 2 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_kotak_gold_policy_count,
            SUM(IF(tsp.plan_name = 'Gold' AND tsp.ic_id = 5 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_il_gold_policy_count,
            SUM(IF(tsp.plan_name = 'Gold' AND tsp.ic_id = 9 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_tata_gold_policy_count,
            SUM(IF(tsp.plan_name = 'Gold' AND tsp.ic_id = 12 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_bagi_gold_policy_count,
            
            SUM(IF(tsp.plan_name = 'Silver' AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_silver_policy_count,
            SUM(IF(tsp.plan_name = 'Silver' AND tsp.ic_id = 2 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_kotak_silver_policy_count,
            SUM(IF(tsp.plan_name = 'Silver' AND tsp.ic_id = 5 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_il_silver_policy_count,
            SUM(IF(tsp.plan_name = 'Silver' AND tsp.ic_id = 9 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_tata_silver_policy_count,
            SUM(IF(tsp.plan_name = 'Silver' AND tsp.ic_id = 12 AND DATE(tsp.created_date) = CURDATE(),1,0)) AS td_bagi_silver_policy_count,

            SUM(IF(tsp.plan_name = 'Platinum' AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_platinum_policy_count,
            SUM(IF(tsp.plan_name = 'Platinum' AND tsp.ic_id = 2 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_kotak_platinum_policy_count,
            SUM(IF(tsp.plan_name = 'Platinum' AND tsp.ic_id = 5 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_il_platinum_policy_count,
            SUM(IF(tsp.plan_name = 'Platinum' AND tsp.ic_id = 9 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_tata_platinum_policy_count,
            SUM(IF(tsp.plan_name = 'Platinum' AND tsp.ic_id = 12 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_bagi_platinum_policy_count,
            
            SUM(IF(tsp.plan_name = 'Sapphire' AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_sapphire_policy_count,
            SUM(IF(tsp.plan_name = 'Sapphire' AND tsp.ic_id = 2 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_kotak_sapphire_policy_count,
            SUM(IF(tsp.plan_name = 'Sapphire' AND tsp.ic_id = 5 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_il_sapphire_policy_count,
            SUM(IF(tsp.plan_name = 'Sapphire' AND tsp.ic_id = 9 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_tata_sapphire_policy_count,
            SUM(IF(tsp.plan_name = 'Sapphire' AND tsp.ic_id = 12 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_bagi_sapphire_policy_count,
            
            SUM(IF(tsp.plan_name = 'Gold' AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_gold_policy_count,
            SUM(IF(tsp.plan_name = 'Gold' AND tsp.ic_id = 2 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_kotak_gold_policy_count,
            SUM(IF(tsp.plan_name = 'Gold' AND tsp.ic_id = 5 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_il_gold_policy_count,
            SUM(IF(tsp.plan_name = 'Gold' AND tsp.ic_id = 9 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_tata_gold_policy_count,
            SUM(IF(tsp.plan_name = 'Gold' AND tsp.ic_id = 12 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_bagi_gold_policy_count,
            
            SUM(IF(tsp.plan_name = 'Silver' AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_silver_policy_count,
            SUM(IF(tsp.plan_name = 'Silver' AND tsp.ic_id = 2 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_kotak_silver_policy_count,
            SUM(IF(tsp.plan_name = 'Silver' AND tsp.ic_id = 5 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_il_silver_policy_count,
            SUM(IF(tsp.plan_name = 'Silver' AND tsp.ic_id = 9 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_tata_silver_policy_count,
            SUM(IF(tsp.plan_name = 'Silver' AND tsp.ic_id = 12 AND MONTH(tsp.created_date) = MONTH(CURDATE()),1,0)) AS mtd_bagi_silver_policy_count,

            SUM(IF(tsp.plan_name = 'Platinum' AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_platinum_policy_count,
            SUM(IF(tsp.plan_name = 'Platinum' AND tsp.ic_id = 2 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_kotak_platinum_policy_count,
            SUM(IF(tsp.plan_name = 'Platinum' AND tsp.ic_id = 5 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_il_platinum_policy_count,
            SUM(IF(tsp.plan_name = 'Platinum' AND tsp.ic_id = 9 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_tata_platinum_policy_count,
            SUM(IF(tsp.plan_name = 'Platinum' AND tsp.ic_id = 12 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_bagi_platinum_policy_count,
            
            SUM(IF(tsp.plan_name = 'Sapphire' AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_sapphire_policy_count,
            SUM(IF(tsp.plan_name = 'Sapphire' AND tsp.ic_id = 2 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_kotak_sapphire_policy_count,
            SUM(IF(tsp.plan_name = 'Sapphire' AND tsp.ic_id = 5 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_il_sapphire_policy_count,
            SUM(IF(tsp.plan_name = 'Sapphire' AND tsp.ic_id = 9 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_tata_sapphire_policy_count,
            SUM(IF(tsp.plan_name = 'Sapphire' AND tsp.ic_id = 12 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_bagi_sapphire_policy_count,
            
            SUM(IF(tsp.plan_name = 'Gold' AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_gold_policy_count,
            SUM(IF(tsp.plan_name = 'Gold' AND tsp.ic_id = 2 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_kotak_gold_policy_count,
            SUM(IF(tsp.plan_name = 'Gold' AND tsp.ic_id = 5 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_il_gold_policy_count,
            SUM(IF(tsp.plan_name = 'Gold' AND tsp.ic_id = 9 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_tata_gold_policy_count,
            SUM(IF(tsp.plan_name = 'Gold' AND tsp.ic_id = 12 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_bagi_gold_policy_count,
            
            SUM(IF(tsp.plan_name = 'Silver' AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_silver_policy_count,
            SUM(IF(tsp.plan_name = 'Silver' AND tsp.ic_id = 2 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_kotak_silver_policy_count,
            SUM(IF(tsp.plan_name = 'Silver' AND tsp.ic_id = 5 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_il_silver_policy_count,
            SUM(IF(tsp.plan_name = 'Silver' AND tsp.ic_id = 9 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_tata_silver_policy_count,
            SUM(IF(tsp.plan_name = 'Silver' AND tsp.ic_id = 12 AND YEAR(tsp.created_date) = YEAR(CURDATE()),1,0)) AS ytd_bagi_silver_policy_count

            FROM tvs_sold_policies AS tsp 
            WHERE tsp.user_id <>2871 AND tsp.policy_status_id = 3 ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function getStateByPincodeMaster(){
        $sql = "SELECT state_name,state_id FROM tvs_pincode_master GROUP BY state_id ORDER BY state_name";
       return $result = $this->db->query($sql)->result_array();
    }
    function getCityByPincodeMaster($state_id){
        $sql = "SELECT district_name,city_id FROM tvs_pincode_master WHERE state_id='$state_id' GROUP BY district_name ORDER BY district_name";
       return $result = $this->db->query($sql)->result_array();
    }

    function getCityBYCityid($city_id){
        $sql2 = "SELECT district_name,city_id,city_or_village_name,city_cleaned,district_id_pk FROM tvs_pincode_master WHERE city_id='$city_id' GROUP BY city_id";
       return $result = $this->db->query($sql2)->row_array();
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
        $sql = "SELECT tsp.*,tp.* FROM tvs_sold_policies tsp "
                . "INNER JOIN tvs_plans AS tp ON tsp.plan_id = tp.id "
                . "WHERE `engine_no`='$engine_no' "
                . "AND ((CURDATE() BETWEEN DATE(tsp.sold_policy_effective_date) AND DATE_SUB(tsp.sold_policy_end_date,INTERVAL 60 DAY)) || (CURDATE() <= DATE(tsp.sold_policy_effective_date)))"
                . "AND tsp.ic_id <> 0 AND policy_status_id = 3";
       // echo $sql;
        $is_exist = $this->db->query($sql)->row_array();
        $rsa_tenure = 0;
        $status = false;
        //echo '<pre>'; print_r($is_exist);die('here1');
        if(!empty($is_exist['rsa_tenure'])){
            $rsa_tenure = $is_exist['rsa_tenure'];
            $currentDate = date("Y-m-d");
            $sql = "SELECT * FROM tvs_sold_policies WHERE engine_no='$engine_no' AND policy_status_id = 3 AND DATE(sold_policy_end_date) >= '$currentDate'";
            $is_policy_exist = $this->db->query($sql)->row_array();
            $status = !empty($is_policy_exist)?true:false;
        }
        return $status;
    }

    public function checkIsAvailableForWorkshopPolicy($frame_no){
        $sql = "SELECT * FROM workshop_tvs_vehicle_master AS wtvm WHERE wtvm.`chassis_no` = '$frame_no' AND wtvm.`is_active` = 1 AND wtvm.`is_policy_punched` = 0";
        //echo $sql;exit;
        $is_exist = $this->db->query($sql)->row_array();
        // echo '<pre>'; print_r($is_exist);die('here');
        $status = !empty($is_exist)?true:false;
        return $status;
    }

    public function checkIsAvailableForOnlyRSAPolicy($frame_no){
        $sql = "SELECT * FROM workshop_tvs_vehicle_master AS wtvm WHERE wtvm.`chassis_no` = '$frame_no' AND wtvm.`is_active` = 1 AND wtvm.`is_policy_punched` = 0";
        //echo $sql;exit;
        $is_exist = $this->db->query($sql)->row_array();
        // echo '<pre>'; print_r($is_exist);die('here');
        $status = !empty($is_exist)?true:false;
        return $status;
    }


    public function checkIsPolicyExist($engine_no,$chassis_no){
            $currentDate = date("Y-m-d");
            $sql ="SELECT * FROM `tvs_sold_policies` WHERE (`engine_no` = '$engine_no' OR `chassis_no` = '$chassis_no') "
                    . " AND CURDATE() BETWEEN DATE(sold_policy_effective_date) AND DATE_SUB(sold_policy_end_date,INTERVAL  60 DAY) "
                    . " AND ic_id <> 0 AND policy_status_id = 3";
           // print $sql;exit;
            $result = $this->db->query($sql)->row_array($sql);
            return $return_data = !empty($result) ? true : false;
    }

    function checkIsPendingPolicyExist($engine_no,$chassis_no){
        $currentDate = date("Y-m-d");
        $sql ="SELECT * FROM `tvs_pending_sold_policies` WHERE (`engine_no` = '$engine_no' OR `chassis_no` = '$chassis_no') AND sold_policy_end_date >= '$currentDate' AND policy_status_id = 3";
       // print $sql;exit;
        $result = $this->db->query($sql)->row_array($sql);
        return $return_data = !empty($result) ? true : false;

    }

    public function RR310checkIsPolicyExist($engine_no,$chassis_no){
            $currentDate = date("Y-m-d");
            $sql ="SELECT * FROM `tvs_sold_policies` WHERE (`engine_no` = '$engine_no' OR `chassis_no` = '$chassis_no') AND sold_policy_end_date >= '$currentDate' ";
           // print $sql;exit;
            $result = $this->db->query($sql)->row_array();
            return $return_data = !empty($result) ? true : false;
    }

    public function checkExistPolicyData($engine_no,$chassis_no,$policy_id){
        $currentDate = date("Y-m-d");
        $sql ="SELECT id FROM `tvs_sold_policies` WHERE (`engine_no` = '$engine_no' OR `chassis_no` = '$chassis_no') AND sold_policy_end_date >= '$currentDate' AND policy_status_id = 3 AND id != '$policy_id' ";
            $result = $this->db->query($sql)->row_array($sql);
            // echo $sql;die;
            return $result ;
    }

    function checkRenewalPolicy($policy_id){
        $sql = "SELECT * FROM `tvs_policy_renewal_log` WHERE prev_policy_id='$policy_id' ";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    function getBankdetails() {
        $sql = "select * from tvs_bankmaster";
        $result = $this->db->query($sql)->result();
        return $result;
    }
     function getPolicyById($id) {
        $sql = "SELECT tsp.id as policy_id,tsp.user_id,tsp.vehicle_type,tsp.master_policy_no,tsp.mp_start_date,tsp.mp_end_date,tsp.product_type_name,tsp.plan_id,tsp.created_date as created_at,TIME(tsp.`created_date`) AS created_time,tsp.plan_name,tsp.sold_policy_no,tsp.engine_no,tsp.chassis_no,tsp.created_date,tsp.vehicle_registration_no,tsp.sold_policy_effective_date,tsp.sold_policy_end_date,tsp.pa_sold_policy_effective_date,tsp.pa_sold_policy_end_date,tsp.model_name,tsp.model_id,tsp.make_id,tsp.ic_id,tsp.rsa_ic_id,tsp.customer_id,ts.name AS state_name1,tc.name AS city_name1,tcd.*,tp.plan_type_id FROM tvs_sold_policies AS tsp"
            . " LEFT JOIN tvs_customer_details AS tcd  ON tcd.id = tsp.customer_id "
            . " INNER JOIN tvs_plans AS tp  ON tp.id = tsp.plan_id "
            . " LEFT JOIN tvs_state AS ts ON ts.id = tcd.state "
            . " LEFT JOIN tvs_city AS tc  ON tc.id = tcd.city "
            . " WHERE tsp.id = '$id' ";
             
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result[0];
    }

    function getOpnRsaKotakPolicy($id) {
        $open_rsa = $this->load->database('open_rsa', TRUE); 
        $sql = "SELECT tsp.id as policy_id,tsp.created_date as created_at,tsp.*,ts.name AS state_name1,
    tc.name AS city_name1,tcd.* FROM opn_sold_policies AS tsp"
            . " LEFT JOIN opn_customer_details AS tcd  ON tcd.id = tsp.customer_id "
            . " INNER JOIN opn_dealer_product_detail AS tp  ON tp.id = tsp.plan_id "
            . " LEFT JOIN opn_state AS ts ON ts.id = tcd.state "
            . " LEFT JOIN opn_city AS tc  ON tc.id = tcd.city "
            . " WHERE tsp.id = '$id' ";
            // echo $sql;die;
        $query = $open_rsa->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function getOpnrsaPlan($plan_id){
        $open_rsa = $this->load->database('open_rsa', TRUE);
        $sql = "SELECT * FROM opn_dealer_product_detail WHERE id='$plan_id' ";
        $result = $open_rsa->query($sql)->row_array();
        return $result;
    }

function partyPaymentDetails() {
        $sql = "SELECT 
                SUM(IF(ppd.`party_id` = 2 AND ppd.`created_at` = CURDATE(),ppd.amount,0)) AS kotak_td_deposit, 
                SUM(IF(ppd.`party_id` = 2 AND MONTH(ppd.`created_at`) = MONTH(CURDATE()),ppd.amount,0)) AS kotak_mtd_deposit,
                SUM(IF(ppd.`party_id` = 2,ppd.amount,0)) AS kotak_ytd_deposit,

                SUM(IF(ppd.`party_id` = 5 AND ppd.`created_at` = CURDATE(),ppd.amount,0)) AS il_td_deposit,
                SUM(IF(ppd.`party_id` = 5 AND MONTH(ppd.`created_at`) = MONTH(CURDATE()),ppd.amount,0)) AS il_mtd_deposit ,
                SUM(IF(ppd.`party_id` = 5,ppd.amount,0)) AS il_ytd_deposit,

                SUM(IF(ppd.`party_id` = 9 AND ppd.`created_at` = CURDATE(),ppd.amount,0)) AS tata_td_deposit,
                SUM(IF(ppd.`party_id` = 9 AND MONTH(ppd.`created_at`) = MONTH(CURDATE()),ppd.amount,0)) AS tata_mtd_deposit, 
                SUM(IF(ppd.`party_id` = 9,ppd.amount,0)) AS tata_ytd_deposit,

                SUM(IF(ppd.`party_id` = 12 AND ppd.`created_at` = CURDATE(),ppd.amount,0)) AS bagi_td_deposit,
                SUM(IF(ppd.`party_id` = 12 AND MONTH(ppd.`created_at`) = MONTH(CURDATE()),ppd.amount,0)) AS bagi_mtd_deposit, 
                SUM(IF(ppd.`party_id` = 12,ppd.amount,0)) AS bagi_ytd_deposit,

                SUM(IF(ppd.`party_id` = 1 AND ppd.`created_at` = CURDATE(),ppd.amount,0)) AS bharti_assist_td_deposit, 
                SUM(IF(ppd.`party_id` = 1 AND MONTH(ppd.`created_at`) = MONTH(CURDATE()),ppd.amount,0)) AS bharti_assist_mtd_deposit,
                SUM(IF(ppd.`party_id` = 1,ppd.amount,0)) AS bharti_assist_ytd_deposit

                FROM `party_payment_details` AS ppd ";
//        echo $sql;
        $query = $this->db->query($sql);
        $result = $query->row_array();
//        echo '<pre>';
//        print_r($result);
//        echo '<pre>';
        //die('hello moto');
        $sql = "SELECT 
                COUNT(IF(tsp.`ic_id` = 2 AND tsp.`created_date` = CURDATE(),tp.pa_ic_commission_amount,NULL)) AS kotak_td_policy_amount,
                COUNT(IF(tsp.`ic_id` = 2 AND DATE(tsp.`created_date`) = CURDATE(),tsp.`id`,NULL)) AS kotak_td_policy_count, 
                SUM(IF(tsp.`ic_id` = 2 AND MONTH(tsp.`created_date`) = MONTH(CURDATE()),tp.pa_ic_commission_amount,0)) AS kotak_mtd_policy_amount,
                COUNT(IF(tsp.`ic_id` = 2 AND MONTH(tsp.`created_date`) = MONTH(CURDATE()),tsp.`id`,NULL)) AS kotak_mtd_policy_count,
                SUM(IF(tsp.`ic_id` = 2,tp.pa_ic_commission_amount,0)) AS kotak_ytd_policy_amount,
                COUNT(IF(tsp.`ic_id` = 2 AND YEAR(tsp.`created_date`)= YEAR(CURDATE()),tsp.`id`,NULL)) AS kotak_ytd_policy_count,  

                COUNT(IF(tsp.`ic_id` = 5 AND tsp.`created_date` = CURDATE(),tp.pa_ic_commission_amount,NULL)) AS il_td_policy_amount,
                COUNT(IF(tsp.`ic_id` = 5 AND DATE(tsp.`created_date`) = CURDATE(),tsp.`id`,NULL)) AS il_td_policy_count,  
                SUM(IF(tsp.`ic_id` = 5 AND MONTH(tsp.`created_date`) = MONTH(CURDATE()),tp.pa_ic_commission_amount,0)) AS il_mtd_policy_amount,
                COUNT(IF(tsp.`ic_id` = 5 AND MONTH(tsp.`created_date`) = MONTH(CURDATE()),tsp.`id`,NULL)) AS il_mtd_policy_count,
                SUM(IF(tsp.`ic_id` = 5,tp.pa_ic_commission_amount,0)) AS il_ytd_policy_amount,
                COUNT(IF(tsp.`ic_id` = 5,tsp.`id`,NULL)) AS il_ytd_policy_count,   

                COUNT(IF(tsp.`ic_id` = 9 AND tsp.`created_date` = CURDATE(),tp.pa_ic_commission_amount,NULL)) AS tata_td_policy_amount,
                COUNT(IF(tsp.`ic_id` = 9 AND DATE(tsp.`created_date`) = CURDATE(),tsp.`id`,NULL)) AS tata_td_policy_count,   
                SUM(IF(tsp.`ic_id` = 9 AND MONTH(tsp.`created_date`) = MONTH(CURDATE()),tp.pa_ic_commission_amount,0)) AS tata_mtd_policy_amount,
                COUNT(IF(tsp.`ic_id` = 9 AND MONTH(tsp.`created_date`) = MONTH(CURDATE()),tsp.`id`,NULL)) AS tata_mtd_policy_count,
                SUM(IF(tsp.`ic_id` = 9,tp.pa_ic_commission_amount,0)) AS tata_ytd_policy_amount,
                COUNT(IF(tsp.`ic_id` = 9,tsp.`id`,NULL)) AS tata_ytd_policy_count,   

                COUNT(IF(tsp.`ic_id` = 12 AND tsp.`created_date` = CURDATE(),tp.pa_ic_commission_amount,NULL)) AS bagi_td_policy_amount,
                COUNT(IF(tsp.`ic_id` = 12 AND DATE(tsp.`created_date`) = CURDATE(),tsp.`id`,NULL)) AS bagi_td_policy_count,   
                SUM(IF(tsp.`ic_id` = 12 AND MONTH(tsp.`created_date`) = MONTH(CURDATE()),tp.pa_ic_commission_amount,0)) AS bagi_mtd_policy_amount,
                COUNT(IF(tsp.`ic_id` = 12 AND MONTH(tsp.`created_date`) = MONTH(CURDATE()),tsp.`id`,NULL)) AS bagi_mtd_policy_count,
                SUM(IF(tsp.`ic_id` = 12,tp.pa_ic_commission_amount,0)) AS bagi_ytd_policy_amount,
                COUNT(IF(tsp.`ic_id` = 12,tsp.`id`,NULL)) AS bagi_ytd_policy_count,    

                SUM(IF(tsp.`rsa_ic_id` = 1 AND DATE(tsp.`created_date`) = CURDATE(),tp.rsa_commission_amount,0)) AS bharti_assist_td_amount,
                COUNT(IF(tsp.`rsa_ic_id` = 1 AND DATE(tsp.`created_date`) = CURDATE(),tsp.`id`,NULL)) AS bharti_td_policy_count,   
                SUM(IF(tsp.`rsa_ic_id` = 1 AND MONTH(tsp.`created_date`) = MONTH(CURDATE()),tp.rsa_commission_amount,0)) AS bharti_assist_mtd_amount,
                COUNT(IF(tsp.`rsa_ic_id` = 1 AND MONTH(tsp.`created_date`) = MONTH(CURDATE()),tsp.`id`,NULL)) AS bharti_mtd_policy_count,
                SUM(IF(tsp.`rsa_ic_id` = 1,tp.rsa_commission_amount,0)) AS bharti_assist_ytd_amount,
                COUNT(IF(tsp.`rsa_ic_id` = 1,tsp.`id`,NULL)) AS bharti_ytd_policy_count    

                FROM tvs_sold_policies AS tsp 
                INNER JOIN tvs_plans AS tp ON tp.id = tsp.plan_id WHERE tsp.`policy_status_id`=3 AND tsp.`user_id`!=2871";
        $query = $this->db->query($sql);
        $result1 = $query->row_array();
        $final_result = array_merge($result,$result1);
//        echo '<pre>';
//        print_r($final_result);
//        echo '<pre>';
//        die('hello moto');
        return $final_result;
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
        return 
        $result = $this->db
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
        if($status) {
            return true;
        }else {
            return false;
        }
    }

    // function update_invoice_cancel_status($table, $data, $where) {
    //     $this->db->where($where);
    //     $this->db->set($data);
    //     $status = $this->db->update($table);
    //     if($this->db->affected_rows() > 0 ){
    //         $check_invoice_cancel_status = $this->check_invoice_cancel_status($where['id']);
    //     }
    //     return $check_invoice_cancel_status;
    // }

    function update_invoice_cancel_status($table, $data, $where) {
        $this->db->where($where);
        $this->db->set($data);
        $status = $this->db->update($table);
        if($status) {
            $check_invoice_cancel_status = $this->check_invoice_cancel_status($where['id']);
        }
        if($check_invoice_cancel_status){
            return true;
        }else{
            return false;
        }
    }

    function insertIntoTable($table_name, $data) {
        //$this->db->insert($table_name, $data);
        if ($this->db->insert($table_name, $data)) {
            //echo $this->db->last_query();die();
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
function getTransanctionData($transaction_from_date,$transaction_to_date){
        $where = '';
        if($transaction_from_date!='' && $transaction_to_date!=''){
            $where = "AND DATE(transaction_date) BETWEEN '$transaction_from_date' AND '$transaction_to_date' ";
            $where1 = "AND DATE(dts.`transection_date`) BETWEEN '$transaction_from_date' AND '$transaction_to_date' ";
        }else{
            $where = "AND MONTH(transaction_date) = MONTH(CURRENT_DATE()) ";
            $where1 = "AND MONTH(dts.`transection_date`) = MONTH(CURRENT_DATE())" ;
        }
        $dealer_session_id = $this->session->userdata('user_session')['id'];
        $dealer_name = $this->session->userdata('user_session')['dealer_name'];
        $sql1 ="SELECT dbt.bank_transaction_no,dbt.deposit_amount,dbt.transaction_type,dbt.created_date AS transection_date FROM dealer_bank_transaction AS dbt  WHERE dbt.dealer_id = '$dealer_session_id' $where order by id desc";
         // die($sql1);
        $query1 = $this->db->query($sql1);
        $result1 = $query1->result_array();
// echo "<pre>"; print_r($result1); echo "</pre>"; //die('end of line yoyo');
        $where2 = '';
        if(strlen($user_session['sap_ad_code']) >5){
            $employee_code = $user_session['sap_ad_code'];
            $where2 = 'AND tsp.employee_code = '.$employee_code.'';
        }
        $sql = "SELECT dts.policy_no,dts.transection_no,dts.transection_date,dts.transection_type,dts.transection_amount,dts.transection_purpose,tsp.model_name,tsp.engine_no,tsp.chassis_no,tsp.plan_name,bu.employee_code,tsp.`vehicle_registration_no`,bu.employee_code,CONCAT(bu.f_name,' ',bu.l_name) AS employee_name, CONCAT(tcd.fname,' ',tcd.lname) AS customer_name,tp.plan_type_id
            FROM dealer_transection_statement AS dts 
            LEFT JOIN tvs_sold_policies AS tsp ON dts.policy_id = tsp.id
            LEFT JOIN tvs_customer_details AS tcd ON tsp.customer_id = tcd.id
            LEFT JOIN biz_users AS bu ON tsp.employee_code = bu.employee_code
            LEFT JOIN tvs_plans AS tp ON tsp.plan_id = tp.id
            WHERE dts.dealer_id = '$dealer_session_id' $where2 $where1 order by dts.id desc";
            // die($sql);
            $query2 = $this->db->query($sql);
            $result2 = $query2->result_array();
            $result = array_merge($result1,$result2);
             if(!empty($result)){
                foreach ($result as $key => $part) {
                       $sort[$key] = strtotime($part['transection_date']);
                  }
                array_multisort($sort, SORT_DESC, $result);
            }
            // echo "<pre>"; print_r($result); echo "</pre>"; die('end of line yoyo');
            return $result;
    }

    public function getDealerTransactionData($dealer_id){
        // $sql = "SELECT * ";
        $user_session =  $this->session->userdata('user_session');
        $sap_ad_code = $user_session['sap_ad_code'];
       $where = '';
       if(strlen($sap_ad_code) > 5){
            $where = 'AND employee_code = '.$sap_ad_code.'';
       }
            $sql = "SELECT COUNT(id) AS policy_count, SUM(sold_policy_price_with_tax) AS sum_policy_amount,SUM(sold_policy_price_without_tax) AS sum_policy_amount_without_tax
            FROM tvs_sold_policies WHERE user_id = '$dealer_id' AND status=1 AND policy_status_id = 3 $where";
            // die($sql);
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

function getLibertyFeedfile_data($ic_id,$from,$to){
    $sql = "SELECT tsp.`sold_policy_no`,tcd.dob,tcd.`gender`,tcd.state_name,tcd.city_name,tcd.addr1,tcd.addr2,tcd.`pincode`,tcd.`nominee_age`,
            tcd.`nominee_full_name`,tcd.`nominee_relation`,tsp.`created_date`,tp.`sum_insured`,
            CONCAT(tcd.`fname`,' ',tcd.`lname`) AS customer_name
            FROM tvs_sold_policies tsp 
            JOIN tvs_customer_details tcd ON tsp.`customer_id`=tcd.`id`
            JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` WHERE tsp.`ic_id`='$ic_id' AND tsp.`policy_status_id`=3 AND  DATE(tsp.`created_date`) 
                        BETWEEN '$from' AND '$to' ";
                        // echo $sql;die('  -wad');
    $result = $this->db->query($sql)->result_array();
    return $result ;
}

function getRelianceFeedfile_data($ic_id,$from,$to,$policy_status_id){
   $sql = "SELECT tsp.`sold_policy_no`,tcd.dob,tcd.`gender`,tcd.state_name,tcd.city_name,tcd.addr1,tcd.addr2,tcd.`pincode`,tcd.`nominee_age`,
            tcd.`nominee_full_name`,tcd.`nominee_relation`,tcd.`appointee_full_name`,tcd.`appointee_relation`,tsp.`created_date`,tp.`sum_insured`,CONCAT(tcd.`fname`,' ',tcd.`lname`) AS customer_name,td.`sap_ad_code`,tcd.`mobile_no`,tcd.`email`,tp.`pa_ic_commission_amount`,tsp.`sold_policy_effective_date`,tsp.`sold_policy_end_date`,tsp.`pa_sold_policy_effective_date`,tsp.`pa_sold_policy_end_date`
            FROM tvs_sold_policies tsp 
            JOIN tvs_customer_details tcd ON tsp.`customer_id`=tcd.`id`
            JOIN tvs_dealers td ON td.`id`=tsp.`user_id`
            JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id`
            WHERE tsp.`ic_id`='$ic_id' AND tsp.`policy_status_id`='$policy_status_id' AND  DATE(tsp.`created_date`) BETWEEN '$from' AND '$to' " ;

    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows();
    return $result;
}

     public function getPolicyDetails($policy_id){
           $sql = "SELECT tcs.*,tsp.*,tp.id as tp_plan_id,tp.*,dw.* FROM tvs_sold_policies AS tsp INNER JOIN tvs_plans AS tp ON tsp.plan_id = tp.id INNER JOIN dealer_wallet AS dw ON tsp.user_id = dw.dealer_id INNER JOIN tvs_customer_details tcs ON tcs.`id` = tsp.`customer_id` WHERE tsp.id = '$policy_id' ";
           // echo $sql;die();
            $result = $this->db->query($sql)->row_array();
            return $result;
    }

     public function getFeedFileData($from_date,$to_date,$ic_id){
        $sql = " SELECT 
                      tsp.`chassis_no`,tsp.`created_date`,tsp.`customer_id`,tsp.`engine_no`,tsp.`id` AS sold_id,tsp.`model_id`,tsp.`make_name`,tsp.`model_name`,tsp.`plan_id`,tsp.`plan_name`,tsp.`policy_status_id`,tsp.`product_type`,tsp.`sold_policy_effective_date`,tsp.`sold_policy_date`,tsp.`sold_policy_end_date`,tsp.`sold_policy_no`,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,tsp.`sold_policy_tax`,tsp.`status`,tsp.`user_id`,tsp.`vehicle_registration_no`,tsp.`plan_id`, DATE(tsp.`sold_policy_date`) AS policy_start_date, TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.`addr1`,tcs.`addr2`,tcs.`city`,tcs.`city_name`,tcs.`state`,tcs.`state_name`,tcs.`dob`,tcs.`email`,tcs.`fname`,tcs.`lname`,tcs.`gender`,tcs.`mobile_no`,tcs.`nominee_age`,tcs.`nominee_full_name`,tcs.`nominee_relation`,tcs.`pan_card_no`,tcs.`pincode`,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,tp.`dealer_commission`,tp.`gst_amount` , tp.`plan_code` 
                      FROM tvs_sold_policies tsp 
                      JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` 
                      JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` 
                      WHERE tsp.user_id <> 2871 
                      AND tsp.policy_status_id = 3 
                      AND  DATE(tsp.`created_date`) BETWEEN '$from_date' AND '$to_date' 
                      AND tsp.`rsa_ic_id` = '$ic_id'";
                      // die($sql);
                      $result['result'] =  $this->db->query($sql)->result_array();
                      $result['num_rows'] = $this->db->query($sql)->num_rows();
                      return $result;
    }

       public function RSAICID($ic_id,$from_date,$to_date){
        $sql = " SELECT 
                  tsp.*,icpa.`mp_localtion`,tsp.`id` AS sold_id,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,td.`dealer_code`, DATE(tsp.`sold_policy_date`) AS policy_start_date, TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.*,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,tp.*,td.sap_ad_code,td.icici_geo FROM tvs_sold_policies tsp 
                      INNER JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` 
                      INNER JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` 
                      INNER JOIN    tvs_dealers td ON td.id = tsp.user_id 
                      LEFT JOIN ic_pa_master_policy_nos icpa ON tsp.`mp_id` = icpa.`id`
                        WHERE tsp.`rsa_ic_id` = '$ic_id' 
                        AND tsp.policy_status_id IN (3,4) 
                        AND tsp.user_id <> 0 
                        AND  DATE(tsp.`created_date`) 
                        BETWEEN '$from_date' AND '$to_date' ";
                        // die($sql); 
              $result['result'] =  $this->db->query($sql)->result_array();
              $result['num_rows'] = $this->db->query($sql)->num_rows();
              return $result;
    }

    public function getFeedFileDataForPaIc($ic_id,$from_date,$to_date){
        $sql = " SELECT 
                  tsp.*,icpa.`mp_localtion`,tsp.`id` AS sold_id,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,td.`dealer_code`, DATE(tsp.`pa_sold_policy_effective_date`) AS policy_start_date, TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.*,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,tp.*,td.sap_ad_code,td.icici_geo FROM tvs_sold_policies tsp 
                      INNER JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` 
                      INNER JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` 
                      INNER JOIN    tvs_dealers td ON td.id = tsp.user_id 
                      INNER JOIN ic_pa_master_policy_nos icpa ON tsp.`mp_id` = icpa.`id`
                        WHERE tsp.ic_id = $ic_id 
                        AND tsp.policy_status_id IN (3,4) 
                        -- AND tsp.user_id <> 2871 
                        AND  DATE(tsp.`created_date`) 
                        BETWEEN '$from_date' AND '$to_date' ";
                      //  die($sql); 
              $result['result'] =  $this->db->query($sql)->result_array();
              $result['num_rows'] = $this->db->query($sql)->num_rows();
              return $result;
    }

    function getkotakOpnrsaFeedfile($ic_id,$from_date,$to_date){
        $open_rsa = $this->load->database('open_rsa', TRUE);

       $sql="SELECT osp.*,oic.`mp_location`,osp.`id` AS sold_id,
            osp.`sold_policy_price_without_tax` AS basic_premium,
            osp.`sold_policy_price_with_tax` AS total_premium,od.`dealer_code`, 
            DATE(osp.`sold_policy_effective_date`) AS policy_start_date,
            TIME(osp.`sold_policy_effective_date`) AS policy_start_time,ocd.*, 
            CONCAT(ocd.`fname`, ocd.`lname`) AS customer_name, 
            odp.`plan_name`,odp.`dealer_commission`,odp.`dealer_commission_with_gst`, 
            osp.`plan_name`,od.sap_ad_code,od.icici_geo,round(odp.policy_premium+odp.policy_premium_with_gst) AS plan_code
            FROM opn_sold_policies osp INNER JOIN opn_customer_details ocd 
            ON osp.`customer_id` = ocd.`id` INNER JOIN 
            opn_dealer_product_detail odp ON odp.`dealer_id`=osp.`user_id`
            INNER JOIN opn_dealers od ON od.id = osp.user_id 
            INNER JOIN opn_insurance_companies oic ON oic.`id`=osp.`ic_id`
            WHERE osp.ic_id = '$ic_id' AND osp.policy_status_id IN (3,4)
            AND osp.user_id <> 1 AND DATE(osp.`created_date`) BETWEEN '$from_date' 
            AND '$to_date' group by osp.`sold_policy_no`";
        //die($sql);
        $result['result'] = $open_rsa->query($sql)->result_array();
        $result['num_rows'] = $open_rsa->query($sql)->num_rows();

        return $result;
    }

    // function getkotakOpnrsadata($ic_id,$where){
    //     $open_rsa = $this->load->database('open_rsa', TRUE);

    //    $sql="SELECT osp.*,oic.`mp_location`,osp.`id` AS sold_id,
    //         osp.`sold_policy_price_without_tax` AS basic_premium,
    //         osp.`sold_policy_price_with_tax` AS total_premium,od.`dealer_code`, 
    //         DATE(osp.`sold_policy_effective_date`) AS policy_start_date,
    //         TIME(osp.`sold_policy_effective_date`) AS policy_start_time,ocd.*, 
    //         CONCAT(ocd.`fname`, ocd.`lname`) AS customer_name, 
    //         odp.`plan_name`,odp.`dealer_commission`,odp.`dealer_commission_with_gst`, 
    //         osp.`plan_name`,od.sap_ad_code,od.icici_geo,round(odp.policy_premium+odp.policy_premium_with_gst) AS plan_code
    //         FROM opn_sold_policies osp INNER JOIN opn_customer_details ocd 
    //         ON osp.`customer_id` = ocd.`id` INNER JOIN 
    //         opn_dealer_product_detail odp ON odp.`dealer_id`=osp.`user_id`
    //         INNER JOIN opn_dealers od ON od.id = osp.user_id 
    //         INNER JOIN opn_insurance_companies oic ON oic.`id`=osp.`ic_id`
    //         WHERE osp.ic_id = '$ic_id' AND osp.policy_status_id IN (3,4)
    //         AND osp.user_id <> 1 $where group by osp.`sold_policy_no`";
    //     die($sql);
    //     $result['result'] = $open_rsa->query($sql)->result_array();
    //     $result['num_rows'] = $open_rsa->query($sql)->num_rows();

    //     return $result;
    // }

    public function getkotakOpnrsadata($where){
      $admin_session = $this->session->userdata('admin_session');
      $open_rsa = $this->load->database('open_rsa', TRUE);        
        $sql = " 
                 SELECT 
                  tsp.*,
                  tsp.`id` AS sold_id,
                  tsp.`sold_policy_price_without_tax` AS basic_premium,
                  tsp.`sold_policy_price_with_tax` AS total_premium,
                  DATE(tsp.`sold_policy_effective_date`) AS sold_policy_effective_date,
                  TIME(tsp.`sold_policy_date`) AS policy_start_time,
                  tcs.*,
                  CONCAT(tcs.`fname`, ' ', tcs.`lname`) AS customer_name,
                  tp.`dealer_commission`,
                  tp.`policy_premium_with_gst` AS gst_amount,
                  tp.`plan_name` AS plan_code,
                  td.`sap_ad_code`,td.`dealer_name` ,tzm.`zone`,tzm.`zone_code`
                FROM
                  opn_sold_policies tsp 
                  INNER JOIN opn_customer_details tcs 
                    ON tsp.`customer_id` = tcs.`id` 
                  INNER JOIN opn_dealer_product_detail tp 
                    ON tsp.`plan_id` = tp.`id` 
                  INNER JOIN opn_dealers td 
                    ON tsp.`user_id` = td.`id`  
                  LEFT JOIN opn_dealer_zone_mapper tzm ON td.`zone_id` = tzm.`id`
                  WHERE tsp.policy_status_id IN (3,4) $where AND tsp.`ic_id`=4
                  AND tsp.user_id <> 1
                ORDER BY tsp.`id` DESC";
                // die($sql);
        $result['result'] = $open_rsa->query($sql)->result_array();
        $result['num_rows'] = $open_rsa->query($sql)->num_rows();
        return $result;

    }

    function getTataOpnrsaFeedfile($ic_id,$from,$to){
        $open_rsa = $this->load->database('open_rsa', TRUE); 
        // the TRUE paramater tells CI that you'd like to return the database object.

        $sql = "SELECT osp.`sold_policy_no`,osp.`sold_policy_effective_date`,osp.`sold_policy_end_date`,ocd.`fname`,ocd.`lname`,ocd.`email`,ocd.`addr1`,ocd.`addr2`,
            ocd.`mobile_no`,ocd.`nominee_full_name`,ocd.`nominee_relation`,ocd.`appointee_full_name`,ocd.`appointee_relation`,
            ocd.`state_name`,ocd.`city_name`,ocd.`dob`,ocd.`gender`,ocd.`pincode`,od.`sap_ad_code`,odp.`policy_premium`,odp.`policy_premium_with_gst`,
            oic.`sum_insured`
            FROM opn_sold_policies osp 
            JOIN opn_customer_details ocd ON osp.`customer_id`=ocd.`id`
            JOIN opn_dealer_product_detail odp ON osp.`plan_id`=odp.`id`
            JOIN opn_dealers od ON od.`id`=osp.`user_id`  JOIN opn_insurance_companies oic ON oic.`id`=osp.`ic_id`
            WHERE osp.`user_id` <> 1 AND osp.`policy_status_id` IN (3,4) AND osp.`ic_id` ='$ic_id' AND DATE(osp.`created_date`) BETWEEN '$from' AND '$to' ";
        $result = $open_rsa->query($sql)->result_array();
        // echo "<pre>"; print_r($result); echo "</pre>"; die('end of line yoyo');
        return $result;
    }

    function getTataOpnrsaEndorseFeedfile($ic_id,$from,$to){
        $open_rsa = $this->load->database('open_rsa', TRUE); 
        $sql = "SELECT osp.`sold_policy_no`,osp.`sold_policy_effective_date`,osp.`sold_policy_end_date`,ocd.`fname`,ocd.`lname`,ocd.`email`,ocd.`addr1`,ocd.`addr2`,
            ocd.`mobile_no`,ocd.`nominee_full_name`,ocd.`nominee_relation`,ocd.`appointee_full_name`,ocd.`appointee_relation`,
            ocd.`state_name`,ocd.`city_name`,ocd.`dob`,ocd.`gender`,ocd.`pincode`,od.`sap_ad_code`,odp.`policy_premium`,odp.`policy_premium_with_gst`,
            oic.`sum_insured`
            FROM opn_sold_policies osp 
            JOIN opn_customer_details ocd ON osp.`customer_id`=ocd.`id`
            JOIN opn_dealer_product_detail odp ON osp.`plan_id`=odp.`id`
            JOIN opn_dealers od ON od.`id`=osp.`user_id`  JOIN opn_insurance_companies oic ON oic.`id`=osp.`ic_id`
            WHERE osp.`user_id` <> 1 AND ocd.`updated_at` IS NOT NULL AND osp.`policy_status_id` IN (3,4) AND osp.`ic_id` ='$ic_id' AND DATE(osp.`created_date`) BETWEEN '$from' AND '$to'";
        $result = $open_rsa->query($sql)->result_array();
        // echo "<pre>"; print_r($result); echo "</pre>"; die('end of line yoyo');
        return $result;
    }

    function getTataOpnrsaCanceledFeedfile($ic_id,$from,$to){
        $open_rsa = $this->load->database('open_rsa', TRUE); 
        $sql = "SELECT osp.`sold_policy_no`,osp.`sold_policy_effective_date`,osp.`sold_policy_end_date`,ocd.`fname`,ocd.`lname`,ocd.`email`,ocd.`addr1`,ocd.`addr2`,
            ocd.`mobile_no`,ocd.`nominee_full_name`,ocd.`nominee_relation`,ocd.`appointee_full_name`,ocd.`appointee_relation`,osp.`cancellation_reson`,osp.`cancellation_date`,
            ocd.`state_name`,ocd.`city_name`,ocd.`dob`,ocd.`gender`,ocd.`pincode`,od.`sap_ad_code`,odp.`policy_premium`,odp.`policy_premium_with_gst`,
            oic.`sum_insured`
            FROM opn_sold_policies osp 
            JOIN opn_customer_details ocd ON osp.`customer_id`=ocd.`id`
            JOIN opn_dealer_product_detail odp ON osp.`plan_id`=odp.`id`
            JOIN opn_dealers od ON od.`id`=osp.`user_id`  JOIN opn_insurance_companies oic ON oic.`id`=osp.`ic_id`
            WHERE osp.`user_id` <> 1 AND osp.`policy_status_id` =5 AND osp.`ic_id` ='$ic_id' AND DATE(osp.`created_date`) BETWEEN '$from' AND '$to'";
           
        $result = $open_rsa->query($sql)->result_array();
        // echo "<pre>"; print_r($result); echo "</pre>"; die('end of line yoyo');
        return $result;
    }

    public function getRMPolicyDetail($where,$role_id){
        $sql = "SELECT 
                tsp.`id` AS sold_id,tsp.`sold_policy_no`,tsp.`engine_no`,tsp.`chassis_no`,tsp.`model_name`,tsp.`plan_name`,tsp.`created_date`,
                tsp.`sold_policy_effective_date`,tsp.`sold_policy_end_date`,tsp.`ic_id`,
                tsp.`sold_policy_price_without_tax` AS basic_premium,
                tsp.`sold_policy_price_with_tax` AS total_premium,
                DATE(tsp.`sold_policy_date`) AS policy_start_date,
                TIME(tsp.`sold_policy_date`) AS policy_start_time,
                tsp.`master_policy_no`,
                CONCAT(tcs.`fname`, ' ', tcs.`lname`) AS customer_name,tcs.`state_name`,tcs.`city_name`,
                tp.`dealer_commission`,tp.`gst_amount`,tp.`sum_insured`,tp.`plan_code`,
                td.`sap_ad_code` ,td.`dealer_name`,tzm.`zone`,tzm.`zone_code`
                FROM
                tvs_sold_policies tsp 
                INNER JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` 
                INNER JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` 
                INNER JOIN tvs_dealers td ON tsp.`user_id` = td.`id` 
                JOIN tvs_rm_dealers trd ON trd.dealer_id = tsp.user_id 
                LEFT JOIN tvs_dealer_zone_mapper tzm ON td.`zone_id` = tzm.`id`
                WHERE tsp.policy_status_id IN (3,4) $where AND trd.rm_id='$role_id'
                ORDER BY tsp.`id` DESC";
               // die($sql);
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

        $status = ($ic_id==10)?' tsp.policy_status_id IN (3,4,5)':' tsp.policy_status_id IN (3,4)';

        $sql = " 
                 SELECT 
                  tsp.*,
                  tsp.`id` AS sold_id,
                  tsp.`sold_policy_price_without_tax` AS basic_premium,
                  tsp.`sold_policy_price_with_tax` AS total_premium,
                  DATE(tsp.`sold_policy_effective_date`) AS policy_start_date,
                  TIME(tsp.`sold_policy_date`) AS policy_start_time,
                  (CASE 
                    WHEN tsp.policy_status_id=3 THEN 'Live'
                    WHEN tsp.policy_status_id=4 THEN 'Live'
                    WHEN tsp.policy_status_id=5 THEN 'Canceled'
                    ELSE ''
                  END) as 'policy_status',
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
                  WHERE $status $where $condition $zone_code AND tsp.user_id <> 0 ORDER BY tsp.`id` DESC
    ";
    //AND tsp.user_id <> 2871
    //die($sql);
    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows();
    return $result;

    }

public function getPolicyDataByNo($search_policy_no='')
{
    $sql = "SELECT tsp.`engine_no`,tsp.`chassis_no`,tsp.`sold_policy_no`,tsp.`created_date`,tsp.`sold_policy_end_date`,tsp.`sold_policy_effective_date`,CONCAT(tcd.`fname`,' ',tcd.`lname`) AS customer_name,tcd.`city_name`,tcd.`state_name`,tcd.`mobile_no`,tic.`name`,tcd.`dob`,tsp.`vehicle_registration_no`,tsp.`make_name`,tsp.`model_name`,tsp.`plan_name`,tsp.`policy_status_id`,tsp.`cancellation_date`,td.`sap_ad_code`
        FROM tvs_sold_policies tsp 
        JOIN tvs_customer_details tcd ON tsp.`customer_id`=tcd.`id`
        LEFT JOIN tvs_insurance_companies tic ON tsp.`ic_id` = tic.`id`
        INNER JOIN tvs_dealers td ON  td.`id`=tsp.`user_id` 
        WHERE (tsp.`sold_policy_no` LIKE '%$search_policy_no%') OR (tsp.`engine_no` LIKE '%$search_policy_no%') OR (tsp.`chassis_no` LIKE '%$search_policy_no%')" ;
        // echo $sql;die;
    $result = $this->db->query($sql)->result_array();
    return $result;
}
    
public function getPAEndorseFeedfile($ic_id,$from_date,$to_date){
  $sql = " SELECT 
                      tsp.*,icpa.`mp_localtion`,tsp.`id` AS sold_id,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,tsp.`sold_policy_tax`,tsp.`status`,DATE(tsp.`sold_policy_date`) AS policy_start_date, TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.*,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,tp.*,td.sap_ad_code,td.`dealer_code` FROM tvs_sold_policies tsp JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id`  INNER JOIN  tvs_dealers td ON td.id = tsp.user_id INNER JOIN ic_pa_master_policy_nos icpa ON tsp.`mp_id` = icpa.`id`
                       WHERE tsp.ic_id = $ic_id AND tsp.policy_status_id = 3 AND (tsp.user_id != 2871 OR tsp.user_id != 2872) AND tcs.`updated_at` IS NOT NULL AND  DATE(tcs.`updated_at`) BETWEEN '$from_date' AND '$to_date' ";
                  $result['result'] =  $this->db->query($sql)->result_array();
                  $result['num_rows'] = $this->db->query($sql)->num_rows();
                  return $result;
}

function getOldData($policy_id){
    $sql = "SELECT * FROM tvs_endorse_customer_details WHERE policy_id='$policy_id' AND created_date !='0000-00-00 00:00:00' ORDER BY id ASC LIMIT 1 ";
    $result = $this->db->query($sql)->row_array();
    return $result;

}

public function getRSAEndoresfeedfile($from_date,$to_date,$ic_id){
     $sql = " SELECT 
                  tsp.`chassis_no`,tsp.`created_date`,tsp.`customer_id`,tsp.`engine_no`,tsp.`id` AS sold_id,tsp.`model_id`,tsp.`make_name`,tsp.`model_name`,tsp.`plan_id`,tsp.`plan_name`,tsp.`policy_status_id`,tsp.`product_type`,tsp.`sold_policy_effective_date`,tsp.`sold_policy_date`,tsp.`sold_policy_end_date`,tsp.`sold_policy_no`,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,tsp.`sold_policy_tax`,tsp.`status`,tsp.`user_id`,tsp.`vehicle_registration_no`,tsp.`plan_id`, DATE(tsp.`sold_policy_date`) AS policy_start_date, TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.`addr1`,tcs.`addr2`,tcs.`city`,tcs.`city_name`,tcs.`state`,tcs.`dob`,tcs.`email`,tcs.`fname`,tcs.`lname`,tcs.`gender`,tcs.`mobile_no`,tcs.`nominee_age`,tcs.`nominee_full_name`,tcs.`nominee_relation`,tcs.`pan_card_no`,tcs.`pincode`,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,tp.`dealer_commission`,tp.`gst_amount` , tp.`plan_code` ,td.`sap_ad_code`,tcs.`appointee_full_name`,tcs.`appointee_relation`,tcs.`appointee_age`
                  FROM tvs_sold_policies tsp JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` JOIN tvs_dealers td ON tsp.`user_id` = td.`id`
                   WHERE tsp.user_id != 0 
                   AND tsp.policy_status_id IN (3,4) 
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
                    tsp.`id` AS sold_id,tsp.`chassis_no`,tsp.`engine_no`,tsp.`vehicle_registration_no`,tsp.`sold_policy_no`,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,DATE(tsp.`sold_policy_date`) AS policy_start_date,TIME(tsp.`sold_policy_date`) AS policy_start_time, CONCAT(tcs.`fname`, ' ', tcs.`lname`) AS customer_name,tcs.`email`,tcs.`fname`,tcs.`lname`,tcs.`nominee_full_name`,tcs.`nominee_relation`,tcs.`mobile_no`,tp.`dealer_commission`,tp.`gst_amount`,tp.`plan_code`,td.`sap_ad_code`
                    FROM
                      tvs_sold_policies tsp 
                      JOIN tvs_customer_details tcs 
                        ON tsp.`customer_id` = tcs.`id` 
                      JOIN tvs_plans tp 
                        ON tsp.`plan_id` = tp.`id` 
                      JOIN tvs_dealers td 
                        ON tsp.`user_id` = td.`id` 
                    WHERE tsp.user_id != 0 
                      AND tcs.`updated_at` IS NOT NULL $where AND tsp.policy_status_id=3 $condition
                            " ;
                // echo $sql;die;

                $result['result'] =  $this->db->query($sql)->result_array();
                $result['num_rows'] = $this->db->query($sql)->num_rows();
                return $result;
    }
    
function getPACanceledFeedfile($ic_id,$from_date,$to_date){
      $sql = " SELECT tsp.`sold_policy_no`,tsp.`chassis_no`,tsp.`engine_no`,tsp.`cancellation_reson`,
            tsp.`sold_policy_effective_date`,tsp.`pa_sold_policy_effective_date`,tsp.`pa_sold_policy_end_date`,tsp.`user_id`,tsp.`sold_policy_end_date`,
            icpa.`mp_localtion`,icpa.`master_policy_no`,tsp.`id` AS sold_id,tsp.`sold_policy_price_without_tax` AS basic_premium,
            tsp.`sold_policy_price_with_tax` AS total_premium,tsp.`cancellation_date`, DATE(tsp.`sold_policy_date`) AS policy_start_date,
            TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.*,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,
            tp.`plan_code`,tp.`plan_name`,tp.`sum_insured`,td.sap_ad_code,td.`dealer_code`,tp.`pa_ic_commission_amount`
           FROM tvs_sold_policies tsp JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` INNER JOIN  tvs_dealers td ON td.id = tsp.user_id INNER JOIN ic_pa_master_policy_nos icpa ON tsp.`mp_id` = icpa.`id`
            WHERE tsp.ic_id = '$ic_id' AND tsp.policy_status_id = 5 AND (tsp.user_id != 62871 OR tsp.user_id != 62872) AND  DATE(tsp.`created_date`) BETWEEN '$from_date' AND '$to_date' ";

       $result['result'] =  $this->db->query($sql)->result_array();
       $result['num_rows'] = $this->db->query($sql)->num_rows();
       return $result;
}

function getKotakCanceledFeedfile($ic_id,$from_date,$to_date){
    $sql = "SELECT tsp.`sold_policy_no`,tsp.`chassis_no`,tsp.`engine_no`,tsp.`cancellation_reson`,
            tsp.`sold_policy_effective_date`,tsp.`user_id`,tsp.`sold_policy_end_date`,
            icpa.`mp_localtion`,icpa.`master_policy_no`,tsp.`id` AS sold_id,tsp.`sold_policy_price_without_tax` AS basic_premium,
            tsp.`sold_policy_price_with_tax` AS total_premium,tsp.`cancellation_date`, DATE(tsp.`sold_policy_date`) AS policy_start_date,
            TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.*,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,
            tp.`plan_code`,tp.`plan_name`,tp.`sum_insured`,td.sap_ad_code,td.`dealer_code` FROM tvs_sold_policies tsp
            JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` 
            INNER JOIN tvs_dealers td ON td.id = tsp.user_id INNER JOIN ic_pa_master_policy_nos icpa ON tsp.`mp_id` = icpa.`id`
            WHERE tsp.ic_id = '$ic_id' AND tsp.policy_status_id = 5 
            AND tsp.user_id <> 2871 
            AND DATE(tsp.`cancellation_date`) <> DATE(tsp.`created_date`)
            AND DATE(tsp.`created_date`) BETWEEN '$from_date' AND '$to_date' ";
            $result['result'] =  $this->db->query($sql)->result_array();
            $result['num_rows'] = $this->db->query($sql)->num_rows();
            return $result;
}

function getRSACanceledFeedfile($from_date,$to_date,$ic_id){
    $sql = " SELECT 
                  tsp.`chassis_no`,tsp.`created_date`,tsp.`customer_id`,tsp.`engine_no`,tsp.`id` AS sold_id,tsp.`model_id`,tsp.`make_name`,tsp.`model_name`,tsp.`plan_id`,tsp.`plan_name`,tsp.`policy_status_id`,tsp.`product_type`,tsp.`sold_policy_effective_date`,tsp.`sold_policy_date`,tsp.`sold_policy_end_date`,tsp.`sold_policy_no`,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,tsp.`sold_policy_tax`,tsp.`status`,tsp.`user_id`,tsp.`vehicle_registration_no`,tsp.`plan_id`,tsp.`cancellation_date`,tsp.`cancelation_reason_type`,tsp.`cancellation_reson` ,tsp.`cancel_file_name`, DATE(tsp.`sold_policy_date`) AS policy_start_date, TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.`addr1`,tcs.`addr2`,tcs.`city`,tcs.`city_name`,tcs.`state`,tcs.`state_name`,tcs.`dob`,tcs.`email`,tcs.`fname`,tcs.`lname`,tcs.`gender`,tcs.`mobile_no`,tcs.`nominee_age`,tcs.`nominee_full_name`,tcs.`nominee_relation`,tcs.`pan_card_no`,tcs.`pincode`,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,tp.`dealer_commission`,tp.`gst_amount` , tp.`plan_code`,td.`sap_ad_code`,tcs.`appointee_full_name`,tcs.`appointee_relation`,tcs.`appointee_age` 
                  FROM tvs_sold_policies tsp JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` JOIN tvs_dealers td ON tsp.`user_id` = td.`id`
                  WHERE tsp.user_id != 0 
                  AND tsp.policy_status_id = 5 
                  AND  DATE(tsp.`created_date`) BETWEEN '$from_date' AND '$to_date' 
                  AND tsp.`rsa_ic_id` = '$ic_id'  ";
                      // die($sql);
                      $result['result'] =  $this->db->query($sql)->result_array();
                      $result['num_rows'] = $this->db->query($sql)->num_rows();
                      return $result;
}

public function getOrientalCancelledFeedFile($ic_id,$from_date,$to_date){
        $sql = " SELECT 
                  tsp.*,tsp.`id` AS sold_id,tsp.`sold_policy_price_without_tax` AS basic_premium,tsp.`sold_policy_price_with_tax` AS total_premium,td.`dealer_code`, DATE(tsp.`sold_policy_date`) AS policy_start_date, TIME(tsp.`sold_policy_date`) AS policy_start_time,tcs.*,CONCAT(tcs.`fname`, tcs.`lname`) AS customer_name,tp.*,td.sap_ad_code,td.icici_geo,tsp.`created_date` FROM tvs_sold_policies tsp 
                      INNER JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` 
                      INNER JOIN tvs_plans tp ON tsp.`plan_id` = tp.`id` 
                      INNER JOIN    tvs_dealers td ON td.id = tsp.user_id 
                      WHERE tsp.ic_id = $ic_id 
                      AND tsp.policy_status_id = 5 
                      -- AND tsp.user_id <> 2871 
                      AND  DATE(tsp.`cancellation_date`) 
                      BETWEEN '$from_date' AND '$to_date' ";
                //die($sql); 
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
        $result = $this->db->query("SELECT tds.*,tds.`id` AS tvs_dealer_id,tic.* FROM tvs_dealers tds JOIN tvs_insurance_companies tic ON tds.`rsa_ic_master_id` = tic.`id` WHERE tds.`id` = '$user_id' ")->row_array();
        return $result;
    }

    public function getICInfo($ic_id){
        $result = $this->db->query("SELECT tic.* FROM tvs_insurance_companies tic  WHERE tic.`id` = '$ic_id' ")->row_array();
        return $result;
    }
    public function getOpnRSAICInfo($ic_id){
        $open_rsa = $this->load->database('open_rsa', TRUE);
        $result = $open_rsa->query("SELECT tic.* FROM opn_insurance_companies tic  WHERE tic.`id` = '$ic_id' ")->row_array();
        return $result;
    }

    public function getOnlyRsaPolicyDealerinfo($id){
        $result=$this->db->query("SELECT tds.*,tds.`id` AS tvs_dealer_id,tic.* FROM tvs_sold_policies tsp JOIN tvs_dealers tds ON tsp.`user_id`=tds.id JOIN tvs_insurance_companies tic ON tsp.rsa_ic_id=tic.id WHERE tsp.id='$id'")->row_array();
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

   public function getExistInvoiceDetails($invoice_no,$dealer_id,$invoice_id=""){
        if(!empty($invoice_id)){
            $sql = "SELECT COUNT(id) AS count_invoice FROM invoice_details WHERE invoice_no = '$invoice_no' AND dealer_id = '$dealer_id' AND id != '$invoice_id'";
           // echo $sql;die('1');
            $result = $this->db->query($sql)->row_array();
        }else{
            $sql = "SELECT COUNT(id) AS count_invoice FROM invoice_details WHERE invoice_no = '$invoice_no' AND dealer_id = '$dealer_id' AND invoice_status !='rejected'";
            // echo $sql;die('2');
            $result = $this->db->query($sql)->row_array();
        }
        
        return $result ;
    }

    function getDealersNotLogged(){
        $today = date("Y-m-d");
        $sql = "SELECT rkl.sap_ad_code,rkl.`created_at` FROM redirect_key_log AS rkl 
                WHERE DATE(rkl.`created_at`) = '$today'";
        $logged_in_dealers = $this->db->query($sql)->result_array(); 
        $logged_in_dealer_codes = '';
        if(!empty($logged_in_dealers)){
            $logged_in_dealer_codes =  implode(',', array_map(function ($logged_in_dealers) {
                  return $logged_in_dealers['sap_ad_code'];
                }, $logged_in_dealers));
        }
        $where = !empty($logged_in_dealer_codes)?'AND td.sap_ad_code NOT IN ('.$logged_in_dealer_codes.')':'';
        $sql = "SELECT td.mobile,td.sap_ad_code,td.dealer_name FROM tvs_dealers td INNER JOIN dealer_wallet AS dw ON dw.`dealer_id` = td.id
        WHERE td.`gst_no` IS NOT NULL 
        AND td.`gst_no` !=''
        AND  td.id <> 2871
        $where";
        // die($sql);
        $result = $this->db->query($sql)->result_array();  
        //echo '<pre>'; print_r($result);die('hello moto');
        return $result ;
    }

    function getDealerLessBalance(){
        $sql = "SELECT td.mobile,td.sap_ad_code,td.dealer_name,(dw.`security_amount`- dw.`credit_amount`) AS wallet_balance FROM tvs_dealers td INNER JOIN dealer_wallet AS dw ON dw.`dealer_id` = td.id WHERE td.`gst_no` IS NOT NULL 
            AND td.`gst_no` !=''
            AND  td.id <> 2871 HAVING wallet_balance < 1000 " ;
           // die($sql);
           $result = $this->db->query($sql)->result_array();
           return $result; 
    }

    function getTodayPolicyIssued(){
       // $main_array = array();
        $sql = " SELECT td.`id` AS dealer_id,td.sap_ad_code as dealer_code,
        drm.`dealership_name`,drm.`dp_mobile`,
        COUNT(IF(DATE(tsp.`created_date`) = CURDATE(),tsp.`id`,NULL)) AS td_policy,
        COUNT(IF(MONTH(tsp.`created_date`) = MONTH(CURDATE()),tsp.`id`,NULL)) AS mtd_policy
                FROM dealers_rm_mapping drm 
                INNER JOIN tvs_dealers td ON drm.`dealer_code`=td.`sap_ad_code`
                INNER JOIN dealer_wallet dw ON td.`id`=dw.`dealer_id` 
                INNER JOIN tvs_sold_policies tsp ON tsp.`user_id`=td.`id`
                WHERE tsp.`policy_status_id`=3 AND tsp.`user_id`<>2871
                 GROUP BY td.`sap_ad_code`";
                 //die($sql);
        $result = $this->db->query($sql)->result_array();
       //  echo "<pre>"; print_r($result); echo "</pre>"; die('end of line yoyo');
        return $result;
    }

    function get7days_inactive_dealers(){
        $sql = " SELECT td.id,td.`sap_ad_code`, td.`mobile`,td.`dealer_name`,tsp.`created_date`,
                COUNT(IF(DATE(tsp.created_date) > DATE_SUB(CURDATE(), INTERVAL 7 DAY),1, NULL)) AS total_policy
                FROM tvs_dealers AS td
                INNER JOIN tvs_sold_policies AS tsp  ON td.id = tsp.user_id
                GROUP BY td.`sap_ad_code` ";
        $result = $this->db->query($sql)->result_array();
        $newarray = array();$i=0;
        // echo "<pre>"; print_r($result); echo "</pre>"; die('end of line yoyo');
        foreach ($result as $value) {
            if($value['total_policy']==0){
                $newarray[$i]['total_policy']=$value['total_policy'];
                $newarray[$i]['sap_ad_code']=$value['sap_ad_code'];
                $newarray[$i]['mobile']=$value['mobile'];
                $newarray[$i]['created_date']=$value['created_date'];
                $i++;
            }
        }
        return $newarray;
        // echo "<pre>"; print_r($newarray); echo "</pre>"; die('end of line yoyo');
    }


    function getDealerData($dealer_id){
        $total_policy_sql = "
                    SELECT 
                      tsp.`sold_policy_no`,
                      COUNT(tsp.id) AS policy_count,
                      SUM(tp.`plan_code`) AS total_policy_amount,
                      SUM(tp.`gst_amount`) AS total_gst_amount,
                      SUM(tp.`dealer_commission`) AS total_commission 
                    FROM
                      tvs_sold_policies tsp 
                      JOIN tvs_plans tp 
                        ON tsp.`plan_id` = tp.`id` 

                    WHERE tsp.`user_id` = '$dealer_id' 
                      AND tsp.policy_status_id IN (3,4) ";
                   
        $result = $this->db->query($total_policy_sql)->row_array();
        $result_arr = $result;
        $tvs_dealers = $this->db->query(" SELECT * FROM tvs_dealers WHERE id = '$dealer_id' ")->row_array();
        $result_arr = $tvs_dealers;
// echo '<pre>';print_r($result);

        // $wallet_data = $this->db->query("SELECT (security_amount - credit_amount) AS balance_amount FROM dealer_wallet WHERE dealer_id='$dealer_id' " )->row_array();
    
        // $result_arr['balance_amount'] = $wallet_data['balance_amount'];
        // below query is to calculate only financial year for opening balance and credit amount 
        $totl_amt_Sql = "
                        SELECT tsp.`sold_policy_no`,
                        COUNT(tsp.id) AS policy_count,
                        SUM(tp.`plan_code`) AS total_policy_amount,
                        SUM(tp.`dealer_commission`) AS total_commission,
                        CASE WHEN MONTH(tsp.created_date)>=4 THEN
                                  CONCAT(YEAR(tsp.created_date), '-',YEAR(tsp.created_date)+1)
                                ELSE CONCAT(YEAR(tsp.created_date)-1,'-', YEAR(tsp.created_date)) 
                           END AS financial_year
                        FROM tvs_sold_policies tsp 
                        JOIN tvs_plans tp ON tsp.`plan_id`=tp.`id`
                        JOIN dealer_wallet dw ON tsp.user_id = dw.`dealer_id`
                        WHERE tsp.user_id = '$dealer_id' AND tsp.policy_status_id IN (3,4) GROUP BY financial_year
                         LIMIT 2 ";
                         // die($totl_amt_Sql);
         $result2 = $this->db->query($totl_amt_Sql)->result_array();
         // echo '<pre>';print_r($result2);die('result2');
         $deposit_Sql = "SELECT SUM(deposit_amount) AS total_deposit_amount ,
                        CASE WHEN MONTH(created_date)>=4 THEN
                              CONCAT(YEAR(created_date), '-',YEAR(created_date)+1)
                            ELSE CONCAT(YEAR(created_date)-1,'-', YEAR(created_date)) 
                           END AS financial_year
                        FROM dealer_bank_transaction
                         WHERE approval_status='approved' AND dealer_id = '$dealer_id' AND transaction_type = 'deposit'
                         GROUP BY financial_year
                         LIMIT 2 " ;
                         //die();

         $deposit_result = $this->db->query($deposit_Sql)->result_array();

         $getdebit_amt = $this->db->query("SELECT SUM(deposit_amount) AS total_debit_amount ,
                            CASE WHEN MONTH(created_date)>=4 THEN
                                  CONCAT(YEAR(created_date), '-',YEAR(created_date)+1)
                                ELSE CONCAT(YEAR(created_date)-1,'-', YEAR(created_date)) 
                               END AS financial_year
                            FROM dealer_bank_transaction
                             WHERE approval_status='approved' AND dealer_id = '$dealer_id' AND transaction_type = 'withdrawal'
                             GROUP BY financial_year
                             LIMIT 2")->row_array();
         
         $total_commission_gst = (0.18) * $result2[0]['total_commission'];
         $tds = (0.05) * $result2[0]['total_commission'];

         $opening_balance = $deposit_result[0]['total_deposit_amount'] + $total_commission_gst + $result2[0]['total_commission']  - $result2[0]['total_policy_amount'] ;
         // $result_arr['total_credit_amount'] = $deposit_result[1]['total_deposit_amount'];
// echo $deposit_result[0]['total_deposit_amount'].' -'.$result2[0]['total_commission'].' -'.$result2[0]['total_policy_amount'].'-'.$total_commission_gst;die;
         if(empty($deposit_result[1]['total_deposit_amount'])){
            $result_arr['c_n_amount'] = $total_commission_gst + $result2[0]['total_commission']-$tds;
            $result_arr['opening_balance'] = 0;
            $result_arr['total_credit_amount'] = $deposit_result[0]['total_deposit_amount'];
            $result_arr['bank_debit_amount'] =  !empty($getdebit_amt[0]['total_debit_amount'])?$getdebit_amt[0]['total_debit_amount']:0;
            $result_arr['policy_debit_amount'] =  ($result2[0]['total_policy_amount'])?$result2[0]['total_policy_amount']:0;
            $result_arr['total_debit_amount'] =  $result_arr['bank_debit_amount'] + $result_arr['policy_debit_amount'];
         }else{
            $total_commission_gst = (0.18) * $result2[1]['total_commission'];
            $tds = (0.05) * $result2[1]['total_commission'];
            // echo $result2[1]['total_commission'].' -'.$total_commission_gst.' tds- '.$tds;die;
            $result_arr['c_n_amount'] = $total_commission_gst + $result2[1]['total_commission']-$tds;
            $result_arr['opening_balance'] = $opening_balance;
            $result_arr['total_credit_amount'] = $deposit_result[1]['total_deposit_amount'];
            $result_arr['bank_debit_amount'] =  !empty($getdebit_amt[1]['total_debit_amount'])?$getdebit_amt[1]['total_debit_amount']:0;
            $result_arr['policy_debit_amount'] =  ($result2[1]['total_policy_amount'])?$result2[1]['total_policy_amount']:0;
            $result_arr['total_debit_amount'] =  $result_arr['bank_debit_amount'] + $result_arr['policy_debit_amount'];
         }


// echo '<pre>';print_r($getdebit_amt);die('getdebit_amt');
         
         $plan_sql = " 
                        SELECT tsp.`plan_name`,tp.`dealer_commission`,
                        COUNT(IF(tsp.`plan_name` ='Platinum',tsp.`plan_name`,NULL)) AS platinum_policies,
                        COUNT(IF(tsp.`plan_name`='Sapphire',tsp.`plan_name`,NULL)) AS sapphire_policies,
                        COUNT(IF(tsp.`plan_name` = 'Gold',tsp.`plan_name`,NULL)) AS gold_policies,
                        COUNT(IF(tsp.`plan_name`='Silver',tsp.`plan_name`,NULL)) AS silver_policies,
                        SUM(tp.`plan_code`) AS product_policy_amount,SUM(tp.`gst_amount`) AS product_gst,
                        SUM(tp.`dealer_commission`) AS product_commission
                        FROM tvs_sold_policies tsp
                        JOIN tvs_plans tp ON tsp.`plan_id`=tp.`id`
                        WHERE tsp.`user_id` = '$dealer_id' AND tsp.policy_status_id IN (3,4) AND MONTH(tsp.`created_date`)=MONTH(CURDATE())
                        GROUP BY tsp.`plan_name`    ";
                        // die($plan_sql);
        $result4 = $this->db->query($plan_sql)->result_array();
        foreach ($result4 as $value) {
            if($value['plan_name']=='Gold'){
                $result_arr['gold_policies'] = ($value['gold_policies'])?$value['gold_policies']:0;
                $result_arr['gold_policy_amount'] = ($value['product_policy_amount'])?$value['product_policy_amount']:0;
                $result_arr['gold_commission'] = ($value['dealer_commission'])?$value['dealer_commission']:0;
                $result_arr['gold_commission_total'] = ($value['product_commission'])?$value['product_commission']:0;
                $result_arr['gold_gst'] = ($value['product_gst'])?$value['product_gst']:0;
                $result_arr['product_cn_amount_gold'] = $result_arr['c_n_amount'];

            }
            if($value['plan_name']=='Platinum'){
                $result_arr['platinum_policies'] = ($value['platinum_policies'])?$value['platinum_policies']:0;
                $result_arr['platinum_policy_amount'] = ($value['product_policy_amount'])?$value['product_policy_amount']:0;
                $result_arr['platinum_commission'] = ($value['dealer_commission'])?$value['dealer_commission']:0;
                $result_arr['platinum_commission_total'] = ($value['product_commission'])?$value['product_commission']:0;
                $result_arr['platinum_gst'] = ($value['product_gst'])?$value['product_gst']:0;
                $result_arr['product_cn_amount_platinum'] = $result_arr['c_n_amount'];

            }
            if($value['plan_name']=='Silver'){
                $result_arr['silver_policies'] = ($value['silver_policies'])?$value['silver_policies']:0;
                $result_arr['silver_policy_amount'] = ($value['product_policy_amount'])?$value['product_policy_amount']:0;
                $result_arr['silver_commission'] = ($value['dealer_commission'])?$value['dealer_commission']:0;
                $result_arr['silver_commission_total'] = ($value['product_commission'])?$value['product_commission']:0;
                $result_arr['silver_gst'] = ($value['product_gst'])?$value['product_gst']:0;
                $result_arr['product_cn_amount_silver'] = $result_arr['c_n_amount'];

            }
            if($value['plan_name']=='Sapphire'){
                $result_arr['sapphire_policies'] = ($value['sapphire_policies'])?$value['sapphire_policies']:0;
                $result_arr['sapphire_policy_amount'] = ($value['product_policy_amount'])?$value['product_policy_amount']:0;
                $result_arr['sapphire_commission'] = ($value['dealer_commission'])?$value['dealer_commission']:0;
                $result_arr['sapphire_commission_total'] = ($value['product_commission'])?$value['product_commission']:0;
                $result_arr['sapphire_gst'] = ($value['product_gst'])?$value['product_gst']:0;
                $result_arr['product_cn_amount_sapphire'] = $result_arr['c_n_amount'];

            }
        }

        $result_arr['achive_till_date_sapphire'] = $result_arr['sapphire_policies'];
        $result_arr['achive_till_date_gold'] = $result_arr['gold_policies'];
        $result_arr['achive_till_date_platinum'] = $result_arr['platinum_policies'];
        $result_arr['achive_till_date_silver'] = $result_arr['silver_policies'];

        $result_arr['commission_generated_gold'] = $result_arr['gold_policies'] * $result_arr['gold_commission'];
        $result_arr['commission_generated_silver'] = $result_arr['silver_policies'] * $result_arr['silver_commission'];
        $result_arr['commission_generated_sapphire'] = $result_arr['sapphire_policies'] * $result_arr['sapphire_commission'];
        $result_arr['commission_generated_platinum'] = $result_arr['platinum_policies'] * $result_arr['platinum_commission'];

        $gold_tds = (0.05) * $result_arr['commission_generated_gold'];
        $gold_gst_commission = (0.18) * $result_arr['commission_generated_gold'];
        $platinum_gst_commission = (0.18) * $result_arr['commission_generated_platinum'];
        $platinum_tds = (0.05) * $result_arr['commission_generated_platinum'];
        $silver_gst_commission = (0.18) * $result_arr['commission_generated_silver'];
        $silver_tds = (0.05) * $result_arr['commission_generated_silver'];
        $sapphire_tds = (0.18) * $result_arr['commission_generated_sapphire'];
        $sapphire_gst_commission = (0.05) * $result_arr['commission_generated_sapphire'];

        $result_arr['commission_generated_gold'] = $result_arr['commission_generated_gold'] + $gold_gst_commission - $gold_tds;
        $result_arr['commission_generated_silver'] = $result_arr['commission_generated_silver'] + $silver_gst_commission - $silver_tds;
        $result_arr['commission_generated_sapphire'] = $result_arr['commission_generated_sapphire'] + $sapphire_gst_commission - $sapphire_tds;
        $result_arr['commission_generated_platinum'] = $result_arr['commission_generated_platinum'] + $platinum_gst_commission - $platinum_tds;

      
        // $invoice_details = $this->db->query("SELECT * FROM `invoice_details` WHERE dealer_id = '$dealer_id' AND `invoice_month` = '".date('m-Y')."'  ")->row_array();
        for ($i = 0; $i < date("m"); $i++) {
            $months[] = date("m-Y", strtotime( date( 'Y-m-01' )." -$i months"));
        }
        $start_month = '01-'.date('Y');
        $current_month = date('m-Y');
        $invoice_data = $this->db->query("SELECT * FROM `invoice_details` ind WHERE ind.`invoice_month` BETWEEN '$start_month' AND '$current_month' AND ind.`dealer_id` = '$dealer_id'  AND ind.`invoice_status`='approved' ")->result_array();
        if(empty($invoice_data)){
            $invoice_months = implode(',',$months);
            $invoice_months = str_replace("-".date('Y'), '', $invoice_months);
            $invoice_not_gen_policy = $this->PolicyAmountMonthWise($invoice_months,$dealer_id);
        }else{
                $invoice_gen_months = array();
                foreach ($invoice_data as $value) {
                    $invoice_gen_months[] = $value['invoice_month'];
                }

                $invoice_not_gen_months = array_diff($months, $invoice_gen_months);

                $invoice_not_gen_months = implode(',',$invoice_not_gen_months);
                $invoice_not_gen_months = str_replace("-".date('Y'), '', $invoice_not_gen_months);

                $invoice_gen_months = implode(',',$invoice_gen_months);
                $invoice_gen_months = str_replace("-".date('Y'), '', $invoice_gen_months);
                // echo "<pre>"; print_r($invoice_gen_months); echo "</pre>"; die('end of line yoyo');
                $invoice_gen_policy = $this->PolicyAmountMonthWise($invoice_gen_months,$dealer_id);
                $invoice_not_gen_policy = $this->PolicyAmountMonthWise($invoice_not_gen_months,$dealer_id);
        
// echo "<pre>"; print_r($invoice_not_gen_policy); echo "</pre>"; die('end of line yoyo');

        // To calculate billed mount(invoice generated)
        $billed_array=array();
        foreach ($invoice_gen_policy as $value) {
            if($value['plan_name']=='Gold'){
                $billed_array['b_gold_policies'] = ($value['gold_policies'])?$value['gold_policies']:0;
                $billed_array['b_gold_policy_amount'] = ($value['product_policy_amount'])?$value['product_policy_amount']:0;
                $billed_array['b_gold_commission'] = ($value['dealer_commission'])?$value['dealer_commission']:0;
                $billed_array['b_gold_commission_total'] = ($value['product_commission'])?$value['product_commission']:0;
                $billed_array['b_gold_gst'] = ($value['product_gst'])?$value['product_gst']:0;
            }
            if($value['plan_name']=='Silver'){
                $billed_array['b_silver_policies'] = ($value['silver_policies'])?$value['silver_policies']:0;
                $billed_array['b_silver_policy_amount'] = ($value['product_policy_amount'])?$value['product_policy_amount']:0;
                $billed_array['b_silver_commission'] = ($value['dealer_commission'])?$value['dealer_commission']:0;
                $billed_array['b_silver_commission_total'] = ($value['product_commission'])?$value['product_commission']:0;
                $billed_array['b_silver_gst'] = ($value['product_gst'])?$value['product_gst']:0;
            }
            if($value['plan_name']=='Platinum'){
                $billed_array['b_platinum_policies'] = ($value['platinum_policies'])?$value['platinum_policies']:0;
                $billed_array['b_platinum_policy_amount'] = ($value['product_policy_amount'])?$value['product_policy_amount']:0;
                $billed_array['b_platinum_commission'] = ($value['dealer_commission'])?$value['dealer_commission']:0;
                $billed_array['b_platinum_commission_total'] = ($value['product_commission'])?$value['product_commission']:0;
                $billed_array['b_platinum_gst'] = ($value['product_gst'])?$value['product_gst']:0;
            }
            if($value['plan_name']=='Sapphire'){
                $billed_array['b_sapphire_policies'] = ($value['sapphire_policies'])?$value['sapphire_policies']:0;
                $billed_array['b_sapphire_policy_amount'] = ($value['product_policy_amount'])?$value['product_policy_amount']:0;
                $billed_array['b_sapphire_commission'] = ($value['dealer_commission'])?$value['dealer_commission']:0;
                $billed_array['b_sapphire_commission_total'] = ($value['product_commission'])?$value['product_commission']:0;
                $billed_array['b_sapphire_gst'] = ($value['product_gst'])?$value['product_gst']:0;
            }
        }

        $billed_array['billed_generated_gold'] = $billed_array['b_gold_policies'] * $billed_array['b_gold_commission'];
        $billed_array['billed_generated_silver'] = $billed_array['b_silver_policies'] * $billed_array['b_silver_commission'];
        $billed_array['billed_generated_sapphire'] = $billed_array['b_sapphire_policies'] * $billed_array['b_sapphire_commission'];
        $billed_array['billed_generated_platinum'] = $billed_array['b_platinum_policies'] * $billed_array['b_platinum_commission'];
        $billed_gold_tds = (0.05) * $billed_array['billed_generated_gold'];
        $billed_gold_gst_commission = (0.18) * $billed_array['billed_generated_gold'];
        $billed_platinum_gst_commission = (0.18) * $billed_array['billed_generated_platinum'];
        $billed_platinum_tds = (0.05) * $billed_array['billed_generated_platinum'];
        $billed_silver_gst_commission = (0.18) * $billed_array['billed_generated_silver'];
        $billed_silver_tds = (0.05) * $billed_array['billed_generated_silver'];
        $billed_sapphire_tds = (0.18) * $billed_array['billed_generated_sapphire'];
        $billed_sapphire_gst_commission = (0.05) * $billed_array['billed_generated_sapphire'];
        // echo "<pre>"; print_r($billed_array); echo "</pre>"; die('end of line yoyo');
        $result_arr['billed_generated_gold'] = $billed_array['billed_generated_gold'] + $billed_gold_gst_commission - $billed_gold_tds;
        $result_arr['billed_generated_silver'] = $billed_array['billed_generated_silver'] + $billed_silver_gst_commission - $billed_silver_tds;
        $result_arr['billed_generated_sapphire'] = $billed_array['billed_generated_sapphire'] + $billed_sapphire_gst_commission - $billed_sapphire_tds;
        $result_arr['billed_generated_platinum'] = $billed_array['billed_generated_platinum'] + $billed_platinum_gst_commission - $billed_platinum_tds;
        }
// To calculate pending invoice
         $pending_array=array();
        foreach ($invoice_not_gen_policy as $value) {
            if($value['plan_name']=='Gold'){
                $pending_array['pending_gold_policies'] = ($value['gold_policies'])?$value['gold_policies']:0;
                $pending_array['pending_gold_policy_amount'] = ($value['product_policy_amount'])?$value['product_policy_amount']:0;
                $pending_array['pending_gold_commission'] = ($value['dealer_commission'])?$value['dealer_commission']:0;
                $pending_array['pending_gold_commission_total'] = ($value['product_commission'])?$value['product_commission']:0;
                $pending_array['pending_gold_gst'] = ($value['product_gst'])?$value['product_gst']:0;
            }
            if($value['plan_name']=='Silver'){
                $pending_array['pending_silver_policies'] = ($value['silver_policies'])?$value['silver_policies']:0;
                $pending_array['pending_silver_policy_amount'] = ($value['product_policy_amount'])?$value['product_policy_amount']:0;
                $pending_array['pending_silver_commission'] = ($value['dealer_commission'])?$value['dealer_commission']:0;
                $pending_array['pending_silver_commission_total'] = ($value['product_commission'])?$value['product_commission']:0;
                $pending_array['pending_silver_gst'] = ($value['product_gst'])?$value['product_gst']:0;
            }
            if($value['plan_name']=='Platinum'){
                $pending_array['pending_platinum_policies'] = ($value['platinum_policies'])?$value['platinum_policies']:0;
                $pending_array['pending_platinum_policy_amount'] = ($value['product_policy_amount'])?$value['product_policy_amount']:0;
                $pending_array['pending_platinum_commission'] = ($value['dealer_commission'])?$value['dealer_commission']:0;
                $pending_array['pending_platinum_commission_total'] = ($value['product_commission'])?$value['product_commission']:0;
                $pending_array['pending_platinum_gst'] = ($value['product_gst'])?$value['product_gst']:0;
            }
            if($value['plan_name']=='Sapphire'){
                $pending_array['pending_sapphire_policies'] = ($value['sapphire_policies'])?$value['sapphire_policies']:0;
                $pending_array['pending_sapphire_policy_amount'] = ($value['product_policy_amount'])?$value['product_policy_amount']:0;
                $pending_array['pending_sapphire_commission'] = ($value['dealer_commission'])?$value['dealer_commission']:0;
                $pending_array['pending_sapphire_commission_total'] = ($value['product_commission'])?$value['product_commission']:0;
                $pending_array['pending_sapphire_gst'] = ($value['product_gst'])?$value['product_gst']:0;
            }
        }

        $pending_array['pending_generated_gold'] = $pending_array['pending_gold_policies'] * $pending_array['pending_gold_commission'];
        $pending_array['pending_generated_silver'] = $pending_array['pending_silver_policies'] * $pending_array['pending_silver_commission'];
        $pending_array['pending_generated_sapphire'] = $pending_array['pending_sapphire_policies'] * $pending_array['pending_sapphire_commission'];
        $pending_array['pending_generated_platinum'] = $pending_array['pending_platinum_policies'] * $pending_array['pending_platinum_commission'];
        $pending_gold_tds = (0.05) * $pending_array['pending_generated_gold'];
        $pending_gold_gst_commission = (0.18) * $pending_array['pending_generated_gold'];
        $pending_platinum_gst_commission = (0.18) * $pending_array['pending_generated_platinum'];
        $pending_platinum_tds = (0.05) * $pending_array['pending_generated_platinum'];
        $pending_silver_gst_commission = (0.18) * $pending_array['pending_generated_silver'];
        $pending_silver_tds = (0.05) * $pending_array['pending_generated_silver'];
        $pending_sapphire_tds = (0.18) * $pending_array['pending_generated_sapphire'];
        $pending_sapphire_gst_commission = (0.05) * $pending_array['pending_generated_sapphire'];
        // echo "<pre>"; print_r($pending_array); echo "</pre>"; die('end of line yoyo');
        $result_arr['pending_generated_gold'] = $pending_array['pending_generated_gold'] + $pending_gold_gst_commission - $pending_gold_tds;
        $result_arr['pending_generated_silver'] = $pending_array['pending_generated_silver'] + $pending_silver_gst_commission - $pending_silver_tds;
        $result_arr['pending_generated_sapphire'] = $pending_array['pending_generated_sapphire'] + $pending_sapphire_gst_commission - $pending_sapphire_tds;
        $result_arr['pending_generated_platinum'] = $pending_array['pending_generated_platinum'] + $pending_platinum_gst_commission - $pending_platinum_tds;
// echo "<pre>"; print_r($pending_array); echo "</pre>"; die('end of line yoyo');
        return $result_arr; 
    }

    function PolicyAmountMonthWise($month_array,$dealer_id){
        $policy_sql = "SELECT tsp.`plan_name`,tp.`dealer_commission`,
                        COUNT(IF(tsp.`plan_name` ='Platinum',tsp.`plan_name`,NULL)) AS platinum_policies,
                        COUNT(IF(tsp.`plan_name`='Sapphire',tsp.`plan_name`,NULL)) AS sapphire_policies,
                        COUNT(IF(tsp.`plan_name` = 'Gold',tsp.`plan_name`,NULL)) AS gold_policies,
                        COUNT(IF(tsp.`plan_name`='Silver',tsp.`plan_name`,NULL)) AS silver_policies,
                        SUM(tp.`plan_code`) AS product_policy_amount,SUM(tp.`gst_amount`) AS product_gst,
                        SUM(tp.`dealer_commission`) AS product_commission
                        FROM tvs_sold_policies tsp
                        JOIN tvs_plans tp ON tsp.`plan_id`=tp.`id`
                        WHERE tsp.`user_id` = '$dealer_id' AND tsp.policy_status_id IN (3,4) AND MONTH(tsp.`created_date`) IN('$month_array')
                        GROUP BY tsp.`plan_name`" ;
// echo $policy_sql;die;
        $result = $this->db->query($policy_sql)->result_array();
        return $result;
    }

 function getTargetData(){
       $target_data = $this->db->query("SELECT * FROM tvs_target ORDER BY id DESC LIMIT 1")->row_array();
       return $target_data ;
    }

function getLastPolicydate($from,$to){
    $sql = "SELECT td.sap_ad_code, MAX(tsp.created_date) AS last_sold_policy_date FROM 
            tvs_sold_policies tsp INNER JOIN tvs_dealers td ON td.id = tsp.user_id WHERE tsp.policy_status_id IN (3,4) AND tsp.`plan_name` <> 'Basic Service' AND user_id!=2871 
            AND DATE(created_date) BETWEEN '$from' AND '$to' GROUP BY tsp.user_id ORDER BY tsp.id DESC";
            // echo $sql;die;
    $result = $this->db->query($sql)->result_array();
    // echo "<pre>"; print_r($result); echo "</pre>"; die('end of line yoyo');
    return $result;    
}

function getKotakDealerGraphicalData($dealer_id)
{
    if($_POST['submit']=="Submit"){
        
        $datef=$this->input->post('start_date');
        $cdate=$this->input->post('end_date');
    }else{
        $date = new DateTime('7 days ago');
        $datef = $date->format('Y-m-d');
        $cdate = date('Y-m-d');
    }
    

    $period = new DatePeriod(
         new DateTime($datef),
         new DateInterval('P1D'),
         new DateTime($cdate.' +1 day')
    );
    $chartarr = array();
    foreach ($period as $key => $value) {
        $days = $value->format('Y-m-d');
        $chartarr[$days] = 0;      
    }

        $sql ="SELECT date(created_date) as policy_date,COUNT(IF(tsp.ic_id = 2,1,NULL)) AS kotak_policies FROM tvs_sold_policies AS tsp WHERE tsp.`user_id`=$dealer_id AND tsp.`policy_status_id`=3 and DATE(created_date) between '$datef' and '$cdate' group by DATE(created_date)";
       // echo $sql;exit;
        $query = $this->db->query($sql);
        $results = $query->result_array();

        foreach ($results as $result) {
            $chartarr[$result['policy_date']] = $result['kotak_policies'];  
            # code...
        }

        return $chartarr;

    // $sql =" SELECT MONTHNAME(tsp.created_date) AS MONTH, DATE_FORMAT(tsp.created_date,'%m') AS month_no,
    //     COUNT(IF(tsp.ic_id = 2,1,NULL)) AS kotak_policies
    //     FROM tvs_sold_policies AS tsp WHERE 
    //     YEAR(tsp.created_date)= YEAR(CURDATE()) AND
    //     tsp.`policy_status_id`=3
    //     AND tsp.`user_id`=$dealer_id
    //     GROUP BY MONTH ORDER BY month_no ASC";
    //     $query = $this->db->query($sql);
    //     $result = $query->result_array();
    //     return $result;
}
function getIlDealerGraphicalData($dealer_id)
{
    if($_POST['submit']=="Submit"){
        $datef=$this->input->post('start_date');
        $cdate=$this->input->post('end_date');
    }else{
        $date = new DateTime('7 days ago');
        $datef = $date->format('Y-m-d');
        $cdate = date('Y-m-d');
    }
    $period = new DatePeriod(
         new DateTime($datef),
         new DateInterval('P1D'),
         new DateTime($cdate.' +1 day')
    );
    $chartarr = array();
    foreach ($period as $key => $value) {
        $days = $value->format('Y-m-d');
        $chartarr[$days] = 0;      
    }

    $sql ="SELECT date(created_date) as policy_date,COUNT(IF(tsp.ic_id = 5,1,NULL)) AS kotak_policies FROM tvs_sold_policies AS tsp WHERE tsp.`user_id`=$dealer_id AND tsp.`policy_status_id`=3 and DATE(created_date) between '$datef' and '$cdate' group by DATE(created_date)";
       // echo $sql;exit;
        $query = $this->db->query($sql);
        $results = $query->result_array();

        foreach ($results as $result) {
            $chartarr[$result['policy_date']] = $result['kotak_policies'];  
            # code...
        }

        return $chartarr;
    /*$sql =" SELECT MONTHNAME(tsp.created_date) AS MONTH, DATE_FORMAT(tsp.created_date,'%m') AS month_no,
        COUNT(IF(tsp.ic_id = 5,1,NULL)) AS il_policies
        FROM tvs_sold_policies AS tsp WHERE 
        YEAR(tsp.created_date)= YEAR(CURDATE()) AND
        tsp.`policy_status_id`=3
        AND tsp.`user_id`=$dealer_id
        GROUP BY MONTH ORDER BY month_no ASC";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;*/
}
function getTataDealerGraphicalData($dealer_id)
{
    if($_POST['submit']=="Submit"){
        $datef=$this->input->post('start_date');
        $cdate=$this->input->post('end_date');
    }else{
        $date = new DateTime('7 days ago');
        $datef = $date->format('Y-m-d');
        $cdate = date('Y-m-d');
    }

    $period = new DatePeriod(
         new DateTime($datef),
         new DateInterval('P1D'),
         new DateTime($cdate.' +1 day')
    );
    $chartarr = array();
    foreach ($period as $key => $value) {
        $days = $value->format('Y-m-d');
        $chartarr[$days] = 0;      
    }

        $sql ="SELECT date(created_date) as policy_date,COUNT(IF(tsp.ic_id = 9,1,NULL)) AS kotak_policies FROM tvs_sold_policies AS tsp WHERE tsp.`user_id`=$dealer_id AND tsp.`policy_status_id`=3 and DATE(created_date) between '$datef' and '$cdate' group by DATE(created_date)";
       // echo $sql;exit;
        $query = $this->db->query($sql);
        $results = $query->result_array();

        foreach ($results as $result) {
            $chartarr[$result['policy_date']] = $result['kotak_policies'];  
            # code...
        }

        return $chartarr;
    /*$sql =" SELECT MONTHNAME(tsp.created_date) AS MONTH, DATE_FORMAT(tsp.created_date,'%m') AS month_no,
        COUNT(IF(tsp.ic_id = 9,1,NULL)) AS tata_policies
        FROM tvs_sold_policies AS tsp WHERE 
        YEAR(tsp.created_date)= YEAR(CURDATE()) AND
        tsp.`policy_status_id`=3
        AND tsp.`user_id`=$dealer_id
        GROUP BY MONTH ORDER BY month_no ASC";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;*/
}
function getBagiDealerGraphicalData($dealer_id)
{
    if($_POST['submit']=="Submit"){
        $datef=$this->input->post('start_date');
        $cdate=$this->input->post('end_date');
    }else{
        $date = new DateTime('7 days ago');
        $datef = $date->format('Y-m-d');
        $cdate = date('Y-m-d');
    }
    $period = new DatePeriod(
         new DateTime($datef),
         new DateInterval('P1D'),
         new DateTime($cdate.' +1 day')
    );
    $chartarr = array();
    foreach ($period as $key => $value) {
        $days = $value->format('Y-m-d');
        $chartarr[$days] = 0;      
    }
    $sql ="SELECT date(created_date) as policy_date,COUNT(IF(tsp.ic_id = 12,1,NULL)) AS kotak_policies FROM tvs_sold_policies AS tsp WHERE tsp.`user_id`=$dealer_id AND tsp.`policy_status_id`=3 and DATE(created_date) between '$datef' and '$cdate' group by DATE(created_date)";
      
        $query = $this->db->query($sql);
        $results = $query->result_array();

        foreach ($results as $result) {
            $chartarr[$result['policy_date']] = $result['kotak_policies'];  
            # code...
        }

        return $chartarr;
   
}
function getDealerDetails($dealer_id){
        $sql = "SELECT td.id,td.dealer_code,td.dealer_name FROM tvs_dealers td WHERE td.id =".$dealer_id;
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;        
    }

 private function _get_datatables_query()
    {
         
        //add custom filter here
        // if($this->input->post('country'))
        // {
        //     $this->db->where('country', $this->input->post('country'));
        // }
        // if($this->input->post('FirstName'))
        // {
        //     $this->db->like('FirstName', $this->input->post('FirstName'));
        // }
        // if($this->input->post('LastName'))
        // {
        //     $this->db->like('LastName', $this->input->post('LastName'));
        // }
        // if($this->input->post('address'))
        // {
        //     $this->db->like('address', $this->input->post('address'));
        // }
    
        $this->db->from($this->table);
        $i = 0;
     
        // foreach ($this->column_search as $item) // loop column 
        // {
        //     if($_POST['search']['value']) // if datatable send POST for search
        //     {
                 
        //         if($i===0) // first loop
        //         {
        //             $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
        //             $this->db->like($item, $_POST['search']['value']);
        //         }
        //         else
        //         {
        //             $this->db->or_like($item, $_POST['search']['value']);
        //         }
 
        //         if(count($this->column_search) - 1 == $i) //last loop
        //             $this->db->group_end(); //close bracket
        //     }
        //     $i++;
        // }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }


public function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    // public function count_all()
    // {
    //     $this->db->from($this->table);
    //     return $this->db->count_all_results();
    // }

public function getOiclCoverFeedFile($ic_id,$from_date,$to_date){
        $sql = "SELECT tsp.sold_policy_no,tp.sum_insured,CONCAT(tcd.fname , tcd.lname) as customer_name, tcd.dob,tcd.gender
                        FROM tvs_sold_policies AS tsp
                        INNER JOIN tvs_customer_details AS tcd ON tcd.id = tsp.customer_id
                        INNER JOIN tvs_plans AS tp ON tsp.plan_id = tp.id
                        WHERE tsp.ic_id = $ic_id 
                        AND tsp.policy_status_id IN (3,4) 
                        AND tsp.user_id <> 2871 
                        AND  DATE(tsp.`created_date`) 
                        BETWEEN '$from_date' AND '$to_date' ";
                        // die($sql);
              $result['result'] =  $this->db->query($sql)->result_array();
              $result['num_rows'] = $this->db->query($sql)->num_rows();
              return $result;
    }

function getGSTInvoicedata($gst_id){
    $sql = "SELECT tdgs.*,inv.invoice_month,inv.invoice_date,td.dealer_name,td.sap_ad_code,td.`gst_no`,td.`pan_no` 
            FROM tvs_dealers_gst_status tdgs INNER JOIN tvs_dealers td ON tdgs.dealer_id = td.id 
            INNER JOIN invoice_details inv ON inv.id = tdgs.invoice_id WHERE tdgs.`id`='$gst_id'
            ORDER BY tdgs.`id` DESC";
    $result = $this->db->query($sql)->row_array();
    return $result;
}

function getDealersDetails($dealer_code = NULL){
    $dealer_code = !empty($dealer_code)?$dealer_code:'98765';
    $this->db->select('*');
    $this->db->from('biz_users AS bu');
    $this->db->join('tvs_dealers AS td','bu.employee_code = td.sap_ad_code','INNER');
    $this->db->where('bu.employee_code',$dealer_code);
    $query = $this->db->get();
    $result = $query->row_array();
    return $result;
    // echo '<pre>'; print_r($result);die('hello moto');
}

function getduplicateenginenos(){
    $resduplicateenginenos=$this->db->query("SELECT DISTINCT engine_no FROM `rr310_script_data_copy` where date(created_date)=curdate()");
    $duplicateenginenos=$resduplicateenginenos->result_array();

    return $duplicateenginenos; 
}

function specialReports($ic_id,$from,$to){
        $sql = "SELECT DATE(tsp.`created_date`) AS date,tp.`plan_name`,tp.`sum_insured`,tsp.`master_policy_no`,
                (CASE WHEN (tp.`plan_name` = 'Platinum' OR  tp.`plan_name` = 'Sapphire') 
             THEN
                  (140 *  COUNT(tsp.`id`)) 
              WHEN(tp.`plan_name` = 'Silver')
             THEN
                  (50 *  COUNT(tsp.`id`)) 
                  ELSE
                  (95 *  COUNT(tsp.`id`)) 
             END)
             AS premium,
            COUNT(tsp.`id`) AS no_of_member  FROM tvs_sold_policies AS tsp 
            INNER JOIN `tvs_plans` AS tp ON tsp.`plan_id` = tp.`id`
            WHERE tsp.`ic_id` = $ic_id AND tsp.policy_status_id IN (3,4) AND tsp.user_id <> 2871
            AND tsp.`created_date` BETWEEN '$from' AND '$to'
            GROUP BY DATE(tsp.`created_date`),tp.`plan_name`";
           //die($sql);
            $result = $this->db->query($sql)->result_array();
    return $result;
    }

function last3MonthsOriental(){
    $last3Monthnos=date('m', strtotime('-3 month')).','.date('m', strtotime('-2 month')).','.date('m', strtotime('-1 month'));

   
//     $reslast3MonthsOriental=$this->db->query("SELECT MONTHNAME(tsp.created_date) AS MONTH, DATE_FORMAT(tsp.created_date,'%m') AS month_no,COUNT(IF(tsp.`plan_name` = 'Platinum',1,NULL)) AS platinum_policies,COUNT(IF(tsp.`plan_name` = 'Silver',1,NULL)) AS silver_policies,COUNT(IF(tsp.`plan_name` = 'Sapphire',1,NULL)) AS sapphire_policies,COUNT(IF(tsp.`plan_name` = 'Gold',1,NULL)) AS gold_policies
// FROM tvs_sold_policies AS tsp WHERE tsp.`ic_id`=10 AND tsp.`policy_status_id`=3 AND tsp.`user_id`!=2871 GROUP BY MONTH ORDER BY month_no DESC LIMIT 3");
    $reslast3MonthsOriental=$this->db->query("SELECT YEAR(tsp.`created_date`) AS policy_year,MONTHNAME(tsp.created_date) AS MONTH, DATE_FORMAT(tsp.created_date,'%m') AS month_no,
        COUNT(IF(tsp.`plan_name` = 'Platinum',1,NULL)) AS platinum_policies,
        COUNT(IF(tsp.`plan_name` = 'Silver',1,NULL)) AS silver_policies,
        COUNT(IF(tsp.`plan_name` = 'Sapphire',1,NULL)) AS sapphire_policies,
        COUNT(IF(tsp.`plan_name` = 'Gold',1,NULL)) AS gold_policies
        FROM tvs_sold_policies AS tsp WHERE tsp.`ic_id`=10 AND tsp.`policy_status_id`=3 AND tsp.`user_id`!=2871 
        GROUP BY YEAR(tsp.`created_date`), MONTH(tsp.`created_date`) ORDER BY YEAR(tsp.`created_date`) DESC, MONTH(tsp.`created_date`) DESC LIMIT 3");
    $last3MonthsOriental=$reslast3MonthsOriental->result_array();
     

    for($i=0;$i<count($last3MonthsOriental);$i++){
        $last3MonthsOriental[$i]['platinum_premium']=$last3MonthsOriental[$i]['platinum_policies']*140;
        $last3MonthsOriental[$i]['sapphire_premium']=$last3MonthsOriental[$i]['sapphire_policies']*140;
        $last3MonthsOriental[$i]['silver_premium']=$last3MonthsOriental[$i]['silver_policies']*50;
        $last3MonthsOriental[$i]['gold_premium']=$last3MonthsOriental[$i]['gold_policies']*95;
        $last3MonthsOriental[$i]['total_policy']=$last3MonthsOriental[$i]['platinum_policies']+$last3MonthsOriental[$i]['sapphire_policies']+$last3MonthsOriental[$i]['silver_policies']+$last3MonthsOriental[$i]['gold_policies'];
        $last3MonthsOriental[$i]['total_premium']=$last3MonthsOriental[$i]['platinum_premium']+$last3MonthsOriental[$i]['sapphire_premium']+$last3MonthsOriental[$i]['silver_premium']+$last3MonthsOriental[$i]['gold_premium'];
    }

    return $last3MonthsOriental;
}

function totalorientalpolicies(){
    $restotaloriental=$this->db->query("SELECT tic.id AS ic_id,tic.name,pw.balance_amount, COUNT(IF(tsp.rsa_ic_id IN (1,11),1,NULL)) AS total_policies, COUNT(IF((tsp.rsa_ic_id IN (1,11) AND DATE(tsp.created_date) = CURDATE()),1,NULL)) AS todays_policies, COUNT(IF(tsp.plan_id IN (1,4,9,10,18,19,26,27,34,35,43,44,53,54,70,71),1,NULL)) AS silver_policies, COUNT(IF((tsp.plan_id IN (2,5,8,11,20,21,28,29,36,37,45,46,55,56,68,69)),1,NULL)) AS gold_policies, COUNT(IF((tsp.plan_id IN (3,6,7,12,22,23,30,31,38,39,47,49,57,59,65,67)),1,NULL)) AS platinum_policies, COUNT(IF((tsp.plan_id IN (13,14,16,17,24,25,32,33,40,41,48,50,58,60,64,66)),1,NULL)) AS sapphire_policies, COUNT(IF((tsp.plan_id IN (51,52)),1,NULL)) AS sapphire_plus_policies FROM tvs_insurance_companies AS tic INNER JOIN tvs_sold_policies AS tsp ON (tsp.ic_id = tic.id || tsp.rsa_ic_id = tic.id) INNER JOIN tvs_plans AS tp ON tsp.plan_id = tp.id LEFT JOIN party_wallet AS pw ON pw.party_id = tic.id WHERE tsp.policy_status_id = 3 AND tsp.user_id <> 0 AND tic.id=10 GROUP BY tic.`id`");
    $totaloriental=$restotaloriental->result_array();

    for($i=0;$i<count($totaloriental);$i++){
       $totaloriental[$i]['sapphire_premium']=$totaloriental[$i]['sapphire_policies']*140;
       $totaloriental[$i]['platinum_premium']=$totaloriental[$i]['platinum_policies']*140;
       $totaloriental[$i]['gold_premium']=$totaloriental[$i]['gold_policies']*95;
       $totaloriental[$i]['silver_premium']=$totaloriental[$i]['silver_policies']*50;

       $totaloriental[$i]['total_policies']=$totaloriental[$i]['sapphire_policies']+$totaloriental[$i]['platinum_policies']+$totaloriental[$i]['gold_policies']+$totaloriental[$i]['silver_policies'];

       $totaloriental[$i]['total_premium']=$totaloriental[$i]['sapphire_premium']+$totaloriental[$i]['platinum_premium']+$totaloriental[$i]['gold_premium']+$totaloriental[$i]['silver_premium'];
    }

    return $totaloriental; 
    
}

function GeneratePolicyNo($rsa_ic_master_id) {
        // $rsa_ic_master_id = $this->session->userdata('user_session')['rsa_ic_master_id'];
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

function StartEndDate(){
    $start_date = date('Y-m-d');
    $result['effective_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "1 day")) . "0 year")) . ' 00:00:01';
    $result['end_date'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime($start_date . "-1 day")) . "1 year")) . ' 23:59:59';
    return $result ;
}
function getRandomNumber($len){
        $better_token = $code=sprintf("%0".$len."d", mt_rand(1, str_pad("", $len,"9")));
        $where = array('transection_no'=>$better_token);
        $check_exist = $this->getRowDataFromTable('tvs_sold_policies',$where);
        if(!empty($check_exist)){
            $this->getRandomNumber($len);
        }
        return $better_token;
    }
function getMakeModelNameByName($type, $model_name) {
    if(!empty($model_name)){

        $new_model_name  = preg_replace('/[^a-zA-Z0-9_ -]/s','',$model_name);
        switch ($type) {
            case 'make':
                $table_name = 'tvs_make';
                $column_name = 'make';
                $column_where = 'id';
                break;
            case 'model':
                $table_name = 'tvs_model_master';
                $column_name = 'model_id';
                $column_where = 'model_name';
                break;
        }

        $column_id = $this->db->select($column_name)->from($table_name)->where(strtolower(trim($column_where)), $new_model_name)->limit(1)->get()->row();
        // echo $this->db->last_query();
        if(!empty($column_id)){
          return $column_id->$column_name;
        }else{
          $model_id=$this->AddModel($model_name);
          return $model_id;
        }
    }

}

  function AddModel($model_name){
    if(!empty($model_name)){        
        $new_model_name  = preg_replace('/[^a-zA-Z0-9_ -]/s','',$model_name);
        $insert_model_detail = array(
            'model_name'=>$new_model_name,
            'model' =>$new_model_name,
            'created_at'=> date('Y-m-d H:i:s')
            );
       $inserted_model_detail = $this->db->insert('tvs_model_master', $insert_model_detail);

       $model_detail_last_id = $this->db->insert_id();

       return $model_detail_last_id;
    }
  }

function getCampaignList($from,$to){
    $sql = "SELECT * FROM `dealer_campaign_list` WHERE DATE(created_at) BETWEEN '$from' AND '$to' ";
    $result = $this->db->query($sql)->result_array();
    return $result;
}

function CheckGSTUploadExist($dealer_id,$invoice_no){
    $sql = " SELECT * FROM tvs_dealers_gst_status WHERE dealer_id='$dealer_id' AND invoice_no='$invoice_no' AND is_gst_compliant_file_uploaded=1 ";
    $result = $this->db->query($sql)->row_array();
    return $result ;
}

function getRMdata($where=''){
    $sql = " SELECT id,name,email,mobile_no,is_active,created_at from tvs_rm_list where is_active=1 ".$where." ORDER BY id";
    $result = $this->db->query($sql)->result();
    return $result ;
}

function getExistRm($contact,$email){
    $sql = "SELECT * FROM tvs_rm_list WHERE mobile_no='".$contact."' OR email='".$email."'";
    $result = $this->db->query($sql)->result_array();
    return $result;
}

function isrmdealerexist($dealer_id,$rm_id){
    $sql = " SELECT * FROM  `tvs_rm_dealers` WHERE dealer_id='$dealer_id' AND rm_id='$rm_id'";
    $result = $this->db->query($sql)->result_array();
    
    if(!empty($result)){
        return true;
    }
    else{
        return false;
    }
}

function getDealers($rm_id){
  $sql = "SELECT td.id,dealer_name,ad_name,sap_ad_code FROM `tvs_dealers` td INNER JOIN dealer_wallet dw ON td.id = dw.dealer_id LEFT JOIN tvs_dealer_documents tdd ON td.id = tdd.dealer_id LEFT 
JOIN tvs_sold_policies tsp ON tsp.`user_id` = td.id WHERE td.id != 2871 and td.id NOT IN
(SELECT dealer_id FROM tvs_rm_dealers WHERE rm_id='$rm_id') GROUP BY td.id order by ad_name";


 $result = $this->db->query($sql)->result_array();
    
 return $result;
}

function getrmDealers($rm_id){
  $sql = "SELECT trd.`id`,trd.`dealer_id`,trd.`dealer_name`,td.`ad_name`,trl.`id` AS rm_id,
trl.`name` AS 'rm_name',trd.`is_active`
FROM tvs_rm_dealers trd JOIN tvs_dealers td ON trd.`dealer_id`=td.id 
RIGHT JOIN tvs_rm_list trl ON trd.`rm_id`=trl.`id`
WHERE trl.`id`=".$rm_id." ORDER BY td.ad_name";

    $result = $this->db->query($sql)->result_array();
    
    return $result;
}

function getRsacancelPolicyDetail($where){
    $sql = " 
                 SELECT 
                  tsp.*,
                  tsp.`id` AS sold_id,
                  tsp.`sold_policy_price_without_tax` AS basic_premium,
                  tsp.`sold_policy_price_with_tax` AS total_premium,
                  DATE(tsp.`sold_policy_effective_date`) AS policy_start_date,
                  TIME(tsp.`sold_policy_date`) AS policy_start_time,
                  (CASE 
                    WHEN tsp.policy_status_id=3 THEN 'Live'
                    WHEN tsp.policy_status_id=4 THEN 'Live'
                    WHEN tsp.policy_status_id=5 THEN 'Canceled'
                    ELSE ''
                  END) as 'policy_status',
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
                  WHERE 1 $where AND tsp.policy_status_id=5 AND tsp.user_id <> 2871 ORDER BY tsp.`id` DESC
    ";
    //AND tsp.user_id <> 2871
    //die($sql);
    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows();
    return $result;
}

    public function getallstates(){
        $sql = "SELECT id,name FROM tvs_state ORDER BY name";

        $result = $this->db->query($sql)->result_array();

        return $result;
    }

    public function getCitiesbyStateid($state_id){
        $sql = "SELECT id,name FROM tvs_city WHERE state_id=".$state_id."  ORDER BY name";

        $result = $this->db->query($sql)->result_array();

        return $result;
    }

    public function check_invoice_cancel_status($policyid){
        $sql = " SELECT MONTH(created_date) AS created_month,YEAR(created_date) AS created_year,user_id FROM `tvs_sold_policies` WHERE `id` = '$policyid' ";
        $result1 = $this->db->query($sql)->row_array();
        $policy_month = $result1['created_month'].'-'.$result1['created_year'];
        $user_id = $result1['user_id'];

        $sql2 = "SELECT * FROM invoice_details WHERE invoice_month='$policy_month' AND dealer_id='$user_id' AND invoice_status='approved'";
        $result2 = $this->db->query($sql)->row_array();
        if(!empty($result2)){
            $data = array(
                'invoice_cancel_status'=>0,
                'invoice_cancel_update_date'=>date('Y-m-d H:i:s')
            );
           $staus = $this->updateTable('tvs_sold_policies',$data,['id'=>$policyid]);
        }
      if($staus){
        return true;
      }else {
          return false;
      }
       // echo '<pre>';  print_r($result1);die;
        // echo $this->db->last_query();die('annu');

    }

    function UpdateInvoiceCancelStatus($plan_name,$dealer_id,$limit_count){
        $invoice_cancel_date = date('Y-m-d H:i:s');
        $sql = "
        UPDATE tvs_sold_policies SET invoice_cancel_status=1 ,invoice_cancel_update_date='$invoice_cancel_date'
        WHERE plan_name='$plan_name' AND user_id='$dealer_id' AND policy_status_id=5 AND invoice_cancel_status=0 ORDER BY id ASC LIMIT $limit_count ";
        $this->db->query($sql);
        if($this->db->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }

        

    }

    function getSapphirePolicyTill30($is_downloaded,$start_date,$end_date){
        if(!empty($start_date) && !empty($end_date)){
            $condition = "AND DATE(tsp.`created_date`) BETWEEN '$start_date' AND '$end_date' ";
        }else{
            $condition = "AND tsp.`created_date` <= '2019-08-30'";
        }
        $sql = "SELECT td.`sap_ad_code`,td.`dealer_name`,tsp.`sold_policy_no`,tsp.`engine_no`,tsp.`chassis_no`,tsp.`vehicle_registration_no`,
            tsp.`make_name`,tsp.`model_name`,tsp.`plan_name`,tsp.`sold_policy_price_without_tax`,tsp.`sold_policy_price_with_tax`,tcd.`fname`,tcd.pincode,
            tcd.`lname`,tcd.`addr1`,tcd.`addr2`,tcd.`mobile_no`,tcd.`dob`,tcd.`email`,tcd.`gender`,tcd.`city_name`,tcd.`state_name`,
            tcd.`nominee_age`,tcd.`nominee_full_name`,tcd.`nominee_relation`,tcd.`appointee_full_name`,tcd.`appointee_age`,tcd.`appointee_relation`,CONCAT(tcd.fname,' ',tcd.lname) AS customer_name,tsp.`master_policy_no`,tsp.`created_date`,tsp.`ic_id`,tsp.`id` AS sold_id
            FROM tvs_sold_policies tsp
            INNER JOIN tvs_customer_details tcd ON tsp.`customer_id`=tcd.`id`
            INNER JOIN tvs_dealers td ON td.`id`=tsp.`user_id`
            WHERE tsp.`policy_status_id` IN (3,4) $condition
            AND tsp.`plan_name`='Sapphire' AND tsp.`ic_id` IN (12,9,5) 
            AND is_mail_send = $is_downloaded
            ORDER BY date(tsp.`created_date`) ASC";
            // die($sql);
        $result['result'] =  $this->db->query($sql)->result_array();
        $result['num_rows'] = $this->db->query($sql)->num_rows();
        return $result;
    }
    function getApologyPolicy(){
        $sql = "SELECT td.`sap_ad_code`,td.`dealer_name`,tsp.`sold_policy_no`,tsp.`engine_no`,tsp.`chassis_no`,tsp.`vehicle_registration_no`,
            tsp.`make_name`,tsp.`model_name`,tsp.`plan_name`,tsp.`sold_policy_price_without_tax`,tsp.`sold_policy_price_with_tax`,tcd.`fname`,tcd.pincode,
            tcd.`lname`,tcd.`addr1`,tcd.`addr2`,tcd.`mobile_no`,tcd.`dob`,tcd.`email`,tcd.`gender`,tcd.`city_name`,tcd.`state_name`,
            tcd.`nominee_age`,tcd.`nominee_full_name`,tcd.`nominee_relation`,tcd.`appointee_full_name`,tcd.`appointee_age`,tcd.`appointee_relation`,CONCAT(tcd.fname,' ',tcd.lname) AS customer_name,tsp.`master_policy_no`,tsp.`created_date`,tsp.`ic_id`,tsp.`id` AS sold_id
            FROM tvs_sold_policies tsp
            INNER JOIN tvs_customer_details tcd ON tsp.`customer_id`=tcd.`id`
            INNER JOIN tvs_dealers td ON td.`id`=tsp.`user_id`
            WHERE tsp.`policy_status_id` IN (3,4) AND tsp.`created_date` <= '2019-08-30'
            AND tsp.`plan_name`='Sapphire' AND tsp.`ic_id` IN (12,9,5) 
            ORDER BY date(tsp.`created_date`) ASC";
            // die($sql);
        $result['result'] =  $this->db->query($sql)->result_array();
        $result['num_rows'] = $this->db->query($sql)->num_rows();
        return $result;
    }
    function getAllSapphirePolicyTill30(){
        $sql = "SELECT tsp.`sold_policy_no`,tsp.`sold_policy_effective_date`,tsp.`sold_policy_end_date`,tsp.`pa_sold_policy_effective_date`,
            tsp.`pa_sold_policy_end_date`,tcd.`addr1`,tcd.`addr2`,CONCAT(tcd.`fname`,' ',tcd.`lname`) AS customer_name,
            tcd.`city_name`,tcd.`state_name`,tcd.pincode
            FROM tvs_sold_policies tsp
            INNER JOIN tvs_customer_details tcd ON tsp.`customer_id`=tcd.`id`
            WHERE 
            tsp.`created_date` <= '2019-08-30' AND tsp.`policy_status_id` IN (3,4) 
            AND tsp.`plan_name`='Sapphire' AND tsp.`ic_id` IN (12,9,5) LIMIT 500 ";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

function getDataOfAppology($policy_id){
    $sql = " SELECT tsp.`sold_policy_no`,tsp.`sold_policy_effective_date`,tsp.`sold_policy_end_date`,tsp.`pa_sold_policy_effective_date`,
            tsp.`pa_sold_policy_end_date`,tcd.`addr1`,tcd.`addr2`,CONCAT(tcd.`fname`,' ',tcd.`lname`) AS customer_name,tcd.`city_name`,tcd.`state_name`,tcd.pincode
            FROM tvs_sold_policies tsp 
            JOIN tvs_customer_details tcd ON tsp.`customer_id` =tcd.id WHERE tsp.id='$policy_id' ";
    $result = $this->db->query($sql)->row_array();
    return $result;
}

function getPolicyExpiryCustomersData($dealer_id,$interval){
     $sql = "SELECT tsp.id AS policy_id,CONCAT(tcd.`fname`,' ',tcd.`lname`) AS customer_name,tic.`name` AS ic_name,tsp.`model_name`,tsp.`engine_no`,tsp.`chassis_no`,tsp.`sold_policy_no`,tsp.`sold_policy_effective_date` AS start_date,tsp.`sold_policy_end_date` AS end_date,tsp.`created_date` 
            FROM tvs_sold_policies AS tsp 
            INNER JOIN tvs_customer_details AS tcd ON tsp.`customer_id` = tcd.`id` 
            INNER JOIN tvs_insurance_companies AS tic ON tic.`id` = tsp.`ic_id`
            LEFT OUTER JOIN tvs_policy_renewal_log AS tprl ON tprl.`prev_policy_id` = tsp.`id`
            WHERE (DATE(tsp.`sold_policy_end_date`) BETWEEN DATE(CURDATE()) AND DATE_ADD(CURDATE(), INTERVAL $interval DAY))
            AND tsp.`user_id` = $dealer_id

            AND tsp.`policy_status_id` IN(3,4)
            AND tprl.`prev_policy_id` IS NULL
            GROUP BY tsp.`engine_no`";
            // die($sql);
        $results = $this->db->query($sql)->result();
        if(!empty($results)){
            return $results;
        } else {
            return false;
        }
     }

function getPendingPolicyByID($policy_id){
            $sql = "SELECT tsp.id AS policy_id,tsp.user_id,tsp.master_policy_no,tsp.mp_start_date,tsp.mp_end_date,tsp.product_type_name,
                    tsp.plan_id,tsp.created_date AS created_at,tsp.plan_name,tsp.sold_policy_no,tsp.engine_no,tsp.chassis_no,tsp.created_date,
                    tsp.vehicle_registration_no,tsp.sold_policy_effective_date,tsp.sold_policy_end_date,tsp.pa_sold_policy_effective_date,
                    tsp.pa_sold_policy_end_date,tsp.model_name,tsp.model_id,tsp.make_id,tsp.ic_id,tsp.rsa_ic_id,tsp.customer_id,
                    ts.name AS state_name1, tc.name AS city_name1,tcd.*,tp.plan_type_id 
                    FROM tvs_sold_policies AS tsp 
                    INNER JOIN tvs_customer_details AS tcd ON tcd.id = tsp.customer_id 
                    INNER JOIN tvs_plans AS tp ON tp.id = tsp.plan_id 
                    LEFT JOIN tvs_state AS ts ON ts.id = tcd.state 
                    LEFT JOIN tvs_city AS tc ON tc.id = tcd.city WHERE tsp.id = '$policy_id' ";

            $result = $this->db->query($sql)->row_array();
            return $result;
          }     
     
function getInvoiceNotApprove($dealer_id){
        $sql = "SELECT * FROM invoice_details WHERE dealer_id = '$dealer_id' AND invoice_status NOT IN('approved','rejected')";
        $result = $this->db->query($sql)->result_array();
        return $result;
        
}


function getPaidServicePolicy($where){
    $sql = "
             SELECT 
              tsp.`sold_policy_no`,tsp.`plan_name`,tsp.`chassis_no`,tsp.`engine_no`,tsp.`model_name`,tsp.`created_date`,tsp.`sold_policy_effective_date`,tsp.`master_policy_no`,
              tsp.`sold_policy_end_date`,tsp.`pa_sold_policy_effective_date`,tsp.`pa_sold_policy_end_date`,tcs.`state_name`,tcs.`city_name`,
              CONCAT(tcs.`fname`, ' ', tcs.`lname`) AS customer_name,
              td.`sap_ad_code` ,td.`dealer_name` ,tzm.`zone`,tzm.`zone_code`,
              tsp.`id` AS sold_id,
              
              (CASE 
                WHEN tsp.policy_status_id=3 THEN 'Active'
                WHEN tsp.policy_status_id=4 THEN 'Active'
                WHEN tsp.policy_status_id=5 THEN 'Canceled'
                ELSE ''
              END) AS 'policy_status'
              
            FROM
              tvs_sold_policies tsp 
              INNER JOIN tvs_customer_details tcs 
                ON tsp.`customer_id` = tcs.`id` 
              INNER JOIN tvs_dealers td 
                ON tsp.`user_id` = td.`id`  
              LEFT JOIN tvs_dealer_zone_mapper tzm ON td.`zone_id` = tzm.`id`
              WHERE  tsp.policy_status_id IN (3,4) $where AND tsp.user_id <> 0 AND tsp.`plan_id`=65
               ORDER BY tsp.`id` DESC ";
    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows();
    return $result;
}


function getWrongpunchedpolicies($where=''){

   $sql = "SELECT sold_policy_no,dealer_name,engine_no,chassis_no,mobile_no,if(is_link_opened=1,'Yes','No') as 'is_link_opened',created_at FROM `wrong_punched_policies` WHERE 1 ".$where;

    $result = $this->db->query($sql)->result();
    return $result;
}


function getWrongedPolicyDetails(){
    $sql = "SELECT wpp.sold_policy_no,CONCAT(tcd.fname,' ',tcd.lname) AS cust_name,CONCAT(tcd.`addr1`,' ',tcd.`addr2`) AS    cust_address,tcd.`city_name`,tcd.`state_name`,tcd.`pincode`  
            FROM `wrong_punched_policies` AS wpp 
            INNER JOIN tvs_sold_policies AS tsp ON tsp.id = wpp.policy_id
            INNER JOIN tvs_customer_details AS tcd ON tsp.customer_id = tcd.id";
    $result = $this->db->query($sql)->result();
    return $result;
}

function checkExistFrameNo($frame_no){
    $sql = "SELECT * FROM `workshop_tvs_vehicle_master` WHERE chassis_no='$frame_no' OR engine_no='$frame_no' ";
    $result = $this->db->query($sql)->row_array();
    return $result;
}

function getPendingRenewal($dealer_code,$engine_no,$start_date,$end_date){
    if(!empty($start_date) && !empty($end_date)){
        if(!empty($dealer_code)){
            $condition = "AND DATE(tsp.`sold_policy_end_date`) BETWEEN DATE('$start_date') AND DATE('$end_date') AND td.`sap_ad_code` = '$dealer_code'";
        }else{
            $condition = "AND DATE(tsp.`sold_policy_end_date`) BETWEEN DATE('$start_date') AND DATE('$end_date')";
        }
    }else{
            if(!empty($engine_no)){
                $condition = "AND (tsp.`engine_no` = '$engine_no' OR tsp.`chassis_no` = '$engine_no' OR tsp.`sold_policy_no` LIKE '%$engine_no%')";
            }else{
                $condition="AND DATE(tsp.`sold_policy_end_date`) = SUBDATE(CURRENT_DATE, 1)";
            }
    }
    
    $sql = "SELECT 
        tsp.`sold_policy_no`,tsp.`plan_name`,tsp.`chassis_no`,tsp.`engine_no`,tsp.`model_name`,tsp.`created_date`,tsp.`sold_policy_effective_date`,tsp.`master_policy_no`,
        tsp.`sold_policy_end_date`,tsp.`pa_sold_policy_effective_date`,tsp.`pa_sold_policy_end_date`,tcs.`state_name`,tcs.`city_name`,
        CONCAT(tcs.`fname`, ' ', tcs.`lname`) AS customer_name,tsp.`ic_id`,tsp.`rsa_ic_id`,
        td.`sap_ad_code` ,td.`dealer_name` ,sms.`is_send`,sms.`sms_type`,
        tsp.`id` AS sold_id      
        FROM
        tvs_sold_policies tsp 
        INNER JOIN tvs_customer_details tcs 
        ON tsp.`customer_id` = tcs.`id` 
        INNER JOIN tvs_dealers td 
        ON tsp.`user_id` = td.`id`  LEFT JOIN `send_sms_status` sms ON tsp.`id`=sms.`policy_id`
        WHERE  tsp.policy_status_id IN (3,4) AND tsp.user_id <> 0 
        $condition AND tsp.`id` NOT IN (SELECT prev_policy_id FROM tvs_policy_renewal_log)
        ORDER BY tsp.`id` DESC";
        // echo $sql;die;
    $result['result'] = $this->db->query($sql)->result_array();
    // echo "<pre>"; print_r($result); echo "</pre>"; die('end of line yoyo');
    $result['num_rows'] = $this->db->query($sql)->num_rows();

    return $result;
}

function getpaicpolicycounts($select_date=''){
    
        $sql = "SELECT tic.name as 'ic_name',COUNT(IF(tsp.`vehicle_type`='NEW' AND tsp.`policy_status_id` = 3 AND DATE(tsp.`created_date`)='".$select_date."',1,NULL)) AS N_FTD,
        COUNT(IF(tsp.`vehicle_type`='NEW' AND tsp.`policy_status_id` = 3 AND MONTH(tsp.`created_date`)=MONTH('".$select_date."') AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS N_MTD,
        COUNT(IF(tsp.`vehicle_type`='NEW' AND tsp.`policy_status_id` = 3 AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS N_YTD,
        COUNT(IF(tsp.`vehicle_type`='OLD' AND tsp.`policy_status_id` = 3 AND DATE(tsp.`created_date`)='".$select_date."',1,NULL)) AS R_FTD,
        COUNT(IF(tsp.`vehicle_type`='OLD' AND tsp.`policy_status_id` = 3 AND MONTH(tsp.`created_date`)=MONTH('".$select_date."') AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS R_MTD,
        COUNT(IF(tsp.`vehicle_type`='OLD' AND tsp.`policy_status_id` = 3 AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS R_YTD,
        COUNT(IF(tsp.`policy_status_id` = 5 AND DATE(tsp.`created_date`)=DATE('".$select_date."'),1,NULL)) AS C_FTD,
        COUNT(IF(tsp.`policy_status_id` = 5 AND MONTH(tsp.`created_date`)=MONTH('".$select_date."') AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS C_MTD,
        COUNT(IF(tsp.`policy_status_id` = 5 AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS C_YTD
        FROM tvs_sold_policies tsp 
        INNER JOIN tvs_insurance_companies tic ON tsp.`ic_id`=tic.id
        WHERE tic.`insurance_type`='PA' AND tsp.user_id <> 2871
        GROUP BY tic.id";

   
    $result = $this->db->query($sql)->result_array();
    return $result;

}

function gettotalpolicies($select_date=''){
    $sql = "SELECT SUM(IF(tsp.`vehicle_type`='NEW' AND tsp.`policy_status_id` = 3 AND DATE(tsp.`created_date`)='".$select_date."',1,0)) AS NSUM_FTD,
        SUM(IF(tsp.`vehicle_type`='NEW' AND tsp.`policy_status_id` = 3 AND MONTH(tsp.`created_date`)=MONTH('".$select_date."') AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,0)) AS NSUM_MTD,
        SUM(IF(tsp.`vehicle_type`='NEW' AND tsp.`policy_status_id` = 3 AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,0)) AS NSUM_YTD,
        SUM(IF(tsp.`vehicle_type`='OLD' AND tsp.`policy_status_id` = 3 AND DATE(tsp.`created_date`)='".$select_date."',1,0)) AS RSUM_FTD,
        SUM(IF(tsp.`vehicle_type`='OLD' AND tsp.`policy_status_id` = 3 AND MONTH(tsp.`created_date`)=MONTH('".$select_date."') AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,0)) AS RSUM_MTD,
        SUM(IF(tsp.`vehicle_type`='OLD' AND tsp.`policy_status_id` = 3 AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,0)) AS RSUM_YTD,
        SUM(IF(tsp.`policy_status_id` = 5 AND DATE(tsp.`created_date`)=DATE('".$select_date."'),1,0)) AS CSUM_FTD,
        SUM(IF(tsp.`policy_status_id` = 5 AND MONTH(tsp.`created_date`)=MONTH('".$select_date."') AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,0)) AS CSUM_MTD,
        SUM(IF(tsp.`policy_status_id` = 5 AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,0)) AS CSUM_YTD
        FROM tvs_sold_policies tsp 
        INNER JOIN tvs_insurance_companies tic ON tsp.`ic_id`=tic.id
        WHERE tic.`insurance_type`='PA'" AND tsp.user_id <> 2871;

        $result = $this->db->query($sql)->result_array();
        return $result;

}

function getzonewisepolicycounts($select_date=''){
    $sql = "SELECT tzm.zone as 'zone',tzm.`zone_code`,COUNT(IF(tsp.`vehicle_type`='NEW' AND tsp.`policy_status_id` = 3 AND DATE(tsp.`created_date`)='".$select_date."',1,NULL)) AS N_FTD,
        COUNT(IF(tsp.`vehicle_type`='NEW' AND tsp.`policy_status_id` = 3 AND MONTH(tsp.`created_date`)=MONTH('".$select_date."') AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS N_MTD,
        COUNT(IF(tsp.`vehicle_type`='NEW' AND tsp.`policy_status_id` = 3 AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS N_YTD,
        COUNT(IF(tsp.`vehicle_type`='OLD' AND tsp.`policy_status_id` = 3 AND DATE(tsp.`created_date`)='".$select_date."',1,NULL)) AS R_FTD,
        COUNT(IF(tsp.`vehicle_type`='OLD' AND tsp.`policy_status_id` = 3 AND MONTH(tsp.`created_date`)=MONTH('".$select_date."') AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS R_MTD,
        COUNT(IF(tsp.`vehicle_type`='OLD' AND tsp.`policy_status_id` = 3 AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS R_YTD,
        COUNT(IF(tsp.`policy_status_id` = 5 AND DATE(tsp.`created_date`)=DATE('".$select_date."'),1,NULL)) AS C_FTD,
        COUNT(IF(tsp.`policy_status_id` = 5 AND MONTH(tsp.`created_date`)=MONTH('".$select_date."') AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS C_MTD,
        COUNT(IF(tsp.`policy_status_id` = 5 AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS C_YTD
        FROM tvs_sold_policies tsp 
        INNER JOIN tvs_insurance_companies tic ON tsp.`ic_id`=tic.id 
        INNER JOIN tvs_dealers td ON tsp.`user_id` = td.`id`  
        LEFT JOIN tvs_dealer_zone_mapper tzm ON td.`zone_id` = tzm.`id`
        WHERE tic.`insurance_type`='PA' AND tsp.user_id <> 2871
        GROUP BY tzm.`zone_code` order by tzm.zone";

    $result = $this->db->query($sql)->result_array();
    return $result;    
}

function getCustomerPolicydata($policyid){
    $sql = "SELECT tsp.`sold_policy_no`,tcd.`mobile_no`,CONCAT(tcd.`fname`,' ',tcd.`lname`) AS customer_name ,tsp.`sold_policy_end_date`,tsp.`model_name`
        FROM tvs_sold_policies tsp JOIN tvs_customer_details tcd ON tsp.`customer_id`=tcd.`id` WHERE tsp.`id`='$policyid' ";
        echo $sql;die;
    $result = $this->db->query($sql)->row_array();
    // echo $this->db->last_query();die;
    return $result;
}

function getzoneallpolicycounts($select_date=''){
   $sql="SELECT tzm.zone AS 'zone',tzm.`zone_code`,
   COUNT(IF(tsp.`policy_status_id` = 3 AND DATE(tsp.`created_date`)='".$select_date."',1,NULL)) AS RSA_FTD,COUNT(IF(tsp.`policy_status_id` = 3 AND (MONTH(tsp.`created_date`)=MONTH('".$select_date."') AND YEAR(tsp.`created_date`)=YEAR('".$select_date."')),1,NULL)) AS RSA_MTD,
    COUNT(IF(tsp.`policy_status_id` = 3 AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS RSA_YTD,
   COUNT(IF(tsp.`plan_id`=62  AND tsp.ic_id=0 AND tsp.`policy_status_id` IN (3,4) AND
DATE(tsp.`created_date`)='".$select_date."',1,NULL)) AS N_FTD, 
COUNT(IF(tsp.`plan_id`=62 AND tsp.ic_id=0 AND tsp.`policy_status_id` IN (3,4) AND (MONTH(tsp.`created_date`)=MONTH('".$select_date."') AND YEAR(tsp.`created_date`)=YEAR('".$select_date."')),1,NULL)) AS N_MTD,COUNT(IF(tsp.`plan_id`=62 AND tsp.ic_id=0 AND tsp.`policy_status_id` IN (3,4) AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS N_YTD,
COUNT(IF(tsp.`plan_id`=63 AND tsp.ic_id=0 AND tsp.`policy_status_id` IN (3,4) AND DATE(tsp.`created_date`)='".$select_date."',1,NULL)) AS R_FTD, 
COUNT(IF(tsp.`plan_id`=63 AND tsp.ic_id=0 AND tsp.`policy_status_id` IN (3,4) AND (MONTH(tsp.`created_date`)=MONTH('".$select_date."') AND 
YEAR(tsp.`created_date`)=YEAR('".$select_date."')),1,NULL)) AS R_MTD, COUNT(IF(tsp.`plan_id`=63 AND tsp.ic_id=0 AND tsp.`policy_status_id` IN (3,4)
AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS R_YTD, COUNT(IF(tsp.`plan_id`=64 AND tsp.ic_id=0 AND tsp.`policy_status_id` IN (3,4)
AND DATE(tsp.`created_date`)=DATE('".$select_date."'),1,NULL)) AS O_FTD, COUNT(IF(tsp.`plan_id`=64 AND tsp.ic_id=0 AND tsp.`policy_status_id` IN (3,4)
AND (MONTH(tsp.`created_date`)=MONTH('".$select_date."') AND YEAR(tsp.`created_date`))=YEAR('".$select_date."'),1,NULL)) AS O_MTD,
COUNT(IF(tsp.`plan_id` = 64 AND tsp.ic_id=0 AND tsp.`policy_status_id` IN (3,4)  AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS O_YTD,
COUNT(IF(tsp.`plan_id` = 65 AND tsp.`policy_status_id` IN (3,4) AND DATE(tsp.`created_date`)=DATE('".$select_date."'),1,NULL)) AS B_FTD,
COUNT(IF(tsp.`plan_id` = 65 AND tsp.`policy_status_id` IN (3,4) AND (MONTH(tsp.`created_date`)=MONTH('".$select_date."')
AND YEAR(tsp.`created_date`)=YEAR('".$select_date."')),1,NULL)) AS B_MTD,
COUNT(IF(tsp.`plan_id` = 65 AND tsp.`policy_status_id` IN (3,4)
AND YEAR(tsp.`created_date`)=YEAR('".$select_date."'),1,NULL)) AS B_YTD FROM
tvs_sold_policies tsp INNER JOIN tvs_dealers td ON tsp.`user_id` = td.`id` LEFT JOIN tvs_dealer_zone_mapper tzm ON td.`zone_id` = tzm.`id` WHERE tsp.`user_id` <> 2871
GROUP BY tzm.`zone_code` ORDER BY tzm.zone";

$result = $this->db->query($sql)->result_array();
return $result;    
}

function getRenewedPolicies($where){
    $sql = "SELECT 
            tsp.`sold_policy_no`,tsp.`plan_name`,tsp.`chassis_no`,tsp.`engine_no`,tsp.`model_name`,tsp.`created_date`,tsp.`sold_policy_effective_date`,tsp.`master_policy_no`,
            tsp.`sold_policy_end_date`,tsp.`pa_sold_policy_effective_date`,tsp.`pa_sold_policy_end_date`,tcs.`state_name`,tcs.`city_name`,tsp.`user_id`,
            CONCAT(tcs.`fname`, ' ', tcs.`lname`) AS customer_name,tsp.`ic_id`,tsp.`rsa_ic_id`,tsp.`payment_detail_id`,
            tsp.`id` AS sold_id,tic.`name`      
            FROM
            tvs_sold_policies tsp 
            INNER JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` 
            INNER JOIN tvs_policy_renewal_log tpl ON tpl.`new_policy_id`=tsp.`id`
            JOIN tvs_insurance_companies tic ON tsp.`ic_id`=tic.`id`
            WHERE  tsp.policy_status_id IN (3,4)  $where
            ORDER BY tsp.`id` DESC";
    $result['result'] = $this->db->query($sql)->result_array();
    // echo "<pre>"; print_r($result); echo "</pre>"; die('end of line yoyo');
    $result['num_rows'] = $this->db->query($sql)->num_rows();
    return $result;        
}

function getPendingDealerPaymentdata(){
    $sql="SELECT td.id,td.dealer_name,td.sap_ad_code,td.banck_acc_no,td.bank_name,td.branch_address,td.banck_ifsc_code,dw.`total_commission` FROM tvs_dealers td JOIN dealer_wallet dw ON td.id=dw.`dealer_id` WHERE is_allowed_commission_to_bank=1 AND dealer_id NOT IN ( SELECT dealer_id FROM dealer_bank_transaction WHERE module='DealerPayment' AND approval_status='approved' AND MONTH(transaction_date)=MONTH(CURDATE()) AND YEAR(transaction_date)=YEAR(CURDATE()))";

    
    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows(); 

    return $result;   


}

function getDealerExpiredPolicies($dealer_id,$start_date,$end_date,$engine_no){
    if(!empty($start_date) && !empty($end_date) ){
            $where = "AND DATE(tsp.`sold_policy_end_date`) BETWEEN DATE('$start_date') AND DATE('$end_date')";
    }else{
            if(!empty($engine_no)){
                $where = "AND (tsp.`engine_no` = '$engine_no' OR tsp.`chassis_no` = '$engine_no' OR tsp.`sold_policy_no` LIKE '%$engine_no%')";
            }else{
                $where = "AND DATE(tsp.`sold_policy_end_date`) = SUBDATE(CURRENT_DATE, 1)";
            }
    }
    $sql = "SELECT tsp.`sold_policy_no`,tsp.`plan_name`,tsp.`chassis_no`,tsp.`engine_no`,tsp.`model_name`,tsp.`created_date`,
            tsp.`sold_policy_effective_date`,tsp.`master_policy_no`, tsp.`sold_policy_end_date`,tsp.`pa_sold_policy_effective_date`,
            tsp.`pa_sold_policy_end_date`,tcs.`state_name`,tcs.`city_name`, CONCAT(tcs.`fname`, ' ', tcs.`lname`) AS customer_name,
            tsp.`ic_id`,tsp.`rsa_ic_id`, td.`sap_ad_code` ,td.`dealer_name` , tsp.`id` AS sold_id ,tic.`name`
            FROM tvs_sold_policies tsp INNER JOIN tvs_customer_details tcs ON tsp.`customer_id` = tcs.`id` 
            INNER JOIN tvs_dealers td ON tsp.`user_id` = td.`id` LEFT JOIN tvs_insurance_companies tic ON tic.id=tsp.`ic_id`
            WHERE tsp.policy_status_id IN (3,4) 
            AND tsp.user_id = '$dealer_id' 
             $where 
            AND tsp.`id` NOT IN (SELECT prev_policy_id FROM tvs_policy_renewal_log) ORDER BY tsp.`id` DESC";
            // die($sql);
    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows(); 
    return $result;
}

function getDealerBankTran_id($dealer_id){
    // check if dealer bank tran id exists
    $dealerbanktranid = "SELECT id FROM dealer_bank_transaction WHERE dealer_id='".$dealer_id."' AND module='DealerPayment' AND approval_status='pending'";

    $dealerbanktranidexists = $this->db->query($dealerbanktranid)->result_array();

    $dealer_bank_tran_id = '';

    if(!empty($dealerbanktranidexists)){
        $dealer_bank_tran_id = $dealerbanktranidexists[0]['id'];
    }
    else{
        $dealer_bank_data = "SELECT td.banck_ifsc_code,td.bank_name,dw.total_commission FROM tvs_dealers td JOIN dealer_wallet dw ON td.id=dw.dealer_id WHERE td.id='".$dealer_id."'";

        $result_dealer_bank_data = $this->db->query($dealer_bank_data)->result_array();

        $dealer_bank_trans_data = array('dealer_id'=>$dealer_id,
        'bank_ifsc_code'=>$result_dealer_bank_data[0]['banck_ifsc_code'],
        'deposit_amount'=>$result_dealer_bank_data[0]['total_commission'],
        'bank_name'=>$result_dealer_bank_data[0]['bank_name'],
        'created_date'=>date('Y-m-d h:i:s'),
        'transaction_type'=>'withdrawal',
        'transaction_date'=>date('Y-m-d'),
        'approval_status'=>'pending',
        'module'=>'DealerPayment',
        );

        $ins_dealer_bank_trans = $this->db->insert('dealer_bank_transaction',$dealer_bank_trans_data);

        $dealer_bank_tran_id = $this->db->insert_id();

    }

    return $dealer_bank_tran_id;
  }  

  function getApprovedDealerPaymentdata(){
    $sql="SELECT td.id,td.dealer_name,td.sap_ad_code,td.banck_acc_no,td.bank_name,td.branch_address,td.banck_ifsc_code,dw.`total_commission`,dbt.`transaction_date`,dbt.`approved_date`,dbt.`deposit_amount` FROM tvs_dealers td JOIN dealer_wallet dw ON td.id=dw.`dealer_id` JOIN dealer_bank_transaction dbt ON td.id=dbt.`dealer_id` WHERE is_allowed_commission_to_bank=1 AND dbt.`module`='DealerPayment' AND dbt.`approval_status`='approved' ORDER BY dbt.`approved_date` desc";

    $result['result'] =  $this->db->query($sql)->result_array();
    $result['num_rows'] = $this->db->query($sql)->num_rows(); 

    return $result;   

  }

  function getRenewedPolicyReport(){
    $array=array();
    $sql="SELECT 
        COUNT(IF(DATE(tsp.`created_date`)=CURDATE()AND tsp.`payment_detail_id` IS NOT NULL AND tsp.`payment_detail_id`<>0,1,NULL)) 
        AS policy_ftd,
        COUNT(IF(MONTH(tsp.`created_date`)=MONTH(CURDATE())AND tsp.`payment_detail_id` IS NOT NULL AND tsp.`payment_detail_id`<>0,1,NULL)) 
        AS policy_mtd,
        COUNT(IF(YEAR(tsp.`created_date`)=YEAR(CURDATE())AND tsp.`payment_detail_id` IS NOT NULL AND tsp.`payment_detail_id`<>0,1,NULL)) 
        AS policy_ytd
        FROM tvs_sold_policies tsp 
        WHERE tsp.`policy_status_id` IN (3,4) AND tsp.`user_id`=4632 
        GROUP BY YEAR(tsp.`created_date`)  
        ORDER BY YEAR(tsp.`created_date`) DESC, MONTH(tsp.`created_date`) DESC LIMIT 1";
        $result =  $this->db->query($sql)->row_array();
        // echo "<pre>"; print_r($array); echo "</pre>"; die('end of line yoyo');

    $sql2 = " 
            SELECT 
            COUNT(IF(Date(sms.`created_at`)=DATE(CURDATE()),1,NULL))AS sms_ftd,
            COUNT(IF(MONTH(sms.`created_at`)=MONTH(CURDATE()) ,1,NULL))AS sms_mtd,
            COUNT(IF(YEAR(sms.`created_at`)=YEAR(CURDATE()) ,1,NULL))AS sms_ytd
            FROM send_sms_status sms 
            WHERE sms.`dealer_code`='98765' AND sms.`sms_type`='buy_renew_policy'
            GROUP BY YEAR(sms.`created_at`) 
            ORDER BY MONTH(sms.`created_at`) DESC LIMIT 1";
            $result2 =  $this->db->query($sql2)->row_array();

    $sql3 = "SELECT 
            COUNT(IF(Date(cl.`created_at`)=DATE(CURDATE()),1,NULL))AS customer_activity_ftd,
            COUNT(IF(MONTH(cl.`created_at`)=MONTH(CURDATE()) ,1,NULL))AS customer_activity_mtd,
            COUNT(IF(YEAR(cl.`created_at`)=YEAR(CURDATE()) ,1,NULL))AS customer_activity_ytd
            FROM customer_activity_log cl 
            WHERE cl.`page_name`='customer_buy_renew'
            GROUP BY YEAR(cl.`created_at`) 
            ORDER BY MONTH(cl.`created_at`) DESC LIMIT 1";
            $result3 =  $this->db->query($sql3)->row_array();
            $array=array_merge($result,$result2,$result3);
            
            return $array;
  }

}