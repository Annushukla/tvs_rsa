<?php

class RM_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }



public function RMSoldPolicies($rm_id){
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
            }
        }        
        

    $sql = "SELECT tic.id AS ic_id,tic.name,pw.balance_amount,
    COUNT(IF(tsp.rsa_ic_id IN (1,11),1,NULL)) AS total_policies,
    COUNT(IF((tsp.rsa_ic_id IN (1,11) AND DATE(tsp.created_date) = CURDATE()),1,NULL)) AS todays_policies,
    COUNT(IF(tsp.plan_id IN ($silver_ids),1,NULL)) AS silver_policies,
    COUNT(IF((tsp.plan_id IN ($gold_ids)),1,NULL)) AS gold_policies,
    COUNT(IF((tsp.plan_id IN ($platinum_ids)),1,NULL)) AS platinum_policies,
    COUNT(IF((tsp.plan_id IN ($sapphire_ids)),1,NULL)) AS sapphire_policies,
    COUNT(IF((tsp.plan_id IN ($sapphire_plus_ids)),1,NULL)) AS sapphire_plus_policies
   
            FROM tvs_insurance_companies AS tic 
            INNER JOIN tvs_sold_policies AS tsp ON (tsp.ic_id = tic.id || tsp.rsa_ic_id = tic.id)
            JOIN tvs_rm_dealers trd ON trd.`dealer_id`=tsp.`user_id`
            INNER JOIN tvs_plans AS tp ON tsp.plan_id = tp.id
            LEFT JOIN party_wallet AS pw ON pw.party_id = tic.id
            WHERE tsp.policy_status_id = 3
            AND trd.`rm_id`='$rm_id'
            GROUP BY tic.`id` ";
           // die($sql);
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
}

function RMLimitlessPolicies($rm_id){
    $sql = "SELECT COUNT(IF((tsp.plan_id =62),1,NULL)) AS limitless_new_policies ,
            COUNT(IF((tsp.plan_id =63),1,NULL)) AS limitless_renew_policies,
            COUNT(IF((tsp.plan_id =64),1,NULL)) AS limitless_online_policies 
            FROM tvs_sold_policies tsp JOIN tvs_rm_dealers trd ON trd.`dealer_id`=tsp.`user_id`
            WHERE tsp.policy_status_id = 3 AND trd.`rm_id`='$rm_id'";
}


public function RMlast3MonthsPolicies($rm_id){
	$sql =" SELECT MONTHNAME(tsp.created_date) AS MONTH, DATE_FORMAT(tsp.created_date,'%m') AS month_no,
                COUNT(IF(tsp.rsa_ic_id = 1,1,NULL)) AS bharti_rsa_policies,
                COUNT(IF(tsp.rsa_ic_id = 11,1,NULL)) AS mytvs_rsa_policies,
                COUNT(IF(tsp.ic_id = 2,1,NULL)) AS kotak_policies,
                COUNT(IF(tsp.ic_id = 5,1,NULL)) AS il_policies,
                COUNT(IF(tsp.ic_id = 9,1,NULL)) AS tata_policies,
                COUNT(IF(tsp.ic_id = 10,1,NULL)) AS oriental_policies,
                COUNT(IF(tsp.ic_id = 12,1,NULL)) AS bagi_policies,
                COUNT(IF(tsp.ic_id = 8,1,NULL)) AS reliance_policies,
                COUNT(IF(tsp.ic_id = 13,1,NULL)) AS liberty_policies,
                COUNT(IF(tsp.ic_id = 7,1,NULL)) AS hdfc_policies
                FROM tvs_sold_policies AS tsp JOIN tvs_rm_dealers trd ON trd.`dealer_id`=tsp.`user_id` WHERE tsp.`policy_status_id`=3
                AND trd.`rm_id`='$rm_id'
                 GROUP BY MONTH ORDER BY month_no DESC LIMIT 3 ";
        
        $query = $this->db->query($sql);
        $result = $query->result_array();

       
        return $result;
}

public function getRMTotalWalletBalance($rm_id){
        $sql ="SELECT (SUM(dw.security_amount)-SUM(dw.credit_amount)) AS total_wallet_balance 
				FROM dealer_wallet dw JOIN tvs_rm_dealers trd ON trd.`dealer_id` = dw.`dealer_id`
				WHERE trd.`rm_id`='$rm_id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result['total_wallet_balance'];
    }

public function RMDealerActivityReport($rm_id){
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
                INNER JOIN tvs_rm_dealers trd ON trd.`dealer_id` = td.`id`
                LEFT JOIN `tvs_dealer_documents` AS tdd ON td.id = tdd.`dealer_id`
                LEFT JOIN dealer_wallet AS dw ON td.id = dw.`dealer_id`
                LEFT JOIN tvs_sold_policies AS tsp ON td.id = tsp.`user_id`
                WHERE trd.`rm_id`='$rm_id' 
                GROUP BY td.id " ;
                $result['result'] =  $this->db->query($sql)->result_array();
                $result['num_rows'] = $this->db->query($sql)->num_rows();
                return $result;
    }

 public function RM_Lastweek_Soldpolicy($rm_id){
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
            INNER JOIN tvs_rm_dealers trd ON trd.`dealer_id`= tsp.`user_id`
            WHERE tsp.`policy_status_id` = 3 AND trd.`rm_id`='$rm_id'
            GROUP BY td.`sap_ad_code` " ;

            $result['result'] =  $this->db->query($sql)->result_array();
            $result['num_rows'] = $this->db->query($sql)->num_rows();
            return $result;
    }

function getRMLastPolicydate($from,$to,$rm_id){
    $sql = "SELECT td.sap_ad_code, MAX(tsp.created_date) AS last_sold_policy_date FROM 
            tvs_sold_policies tsp INNER JOIN tvs_dealers td ON td.id = tsp.user_id
            INNER JOIN tvs_rm_dealers trd ON trd.`dealer_id`=tsp.`user_id`
            WHERE tsp.policy_status_id=3 AND trd.`rm_id`='$rm_id' 
            AND DATE(created_date) BETWEEN '$from' AND '$to' GROUP BY tsp.user_id ORDER BY tsp.id DESC";
            // echo $sql;die;
    $result = $this->db->query($sql)->result_array();
    // echo "<pre>"; print_r($result); echo "</pre>"; die('end of line yoyo');
    return $result;    
}

function RmDealerWiseReportData($rm_id){
    $sql = " SELECT  
td.`sap_ad_code`,td.`dealer_name`,td.`location`,COUNT(tsp.id) AS no_of_policies, 
COUNT(IF(tsp.ic_id = 5, tsp.ic_id, NULL)) AS no_il_policies,
COUNT(IF(tsp.ic_id = 5 AND DATE(tsp.`created_date`)=CURDATE(), tsp.ic_id, NULL)) AS td_il_policies,
COUNT(IF(tsp.ic_id = 5 AND MONTH(tsp.`created_date`)=MONTH(CURDATE()), tsp.ic_id, NULL)) AS mtd_il_policies,
COUNT(IF(tsp.ic_id = 5 AND YEAR(tsp.`created_date`)=YEAR(CURDATE()), tsp.ic_id, NULL)) AS ytd_il_policies, 
COUNT(IF(tsp.ic_id = 2, tsp.ic_id, NULL)) AS no_kotak_policies,
COUNT(IF(tsp.ic_id = 2 AND DATE(tsp.`created_date`)=CURDATE(), tsp.ic_id, NULL)) AS td_kotak_policies,
COUNT(IF(tsp.ic_id = 2 AND MONTH(tsp.`created_date`)=MONTH(CURDATE()), tsp.ic_id, NULL)) AS mtd_kotak_policies,
COUNT(IF(tsp.ic_id = 2 AND YEAR(tsp.`created_date`)=YEAR(CURDATE()), tsp.ic_id, NULL)) AS ytd_kotak_policies, 
COUNT(IF(tsp.ic_id = 12, tsp.ic_id, NULL)) AS no_bagi_policies,
COUNT(IF(tsp.ic_id = 12 AND DATE(tsp.`created_date`)=CURDATE(), tsp.ic_id, NULL)) AS td_bagi_policies,
COUNT(IF(tsp.ic_id = 12 AND MONTH(tsp.`created_date`)=MONTH(CURDATE()), tsp.ic_id, NULL)) AS mtd_bagi_policies,
COUNT(IF(tsp.ic_id = 12 AND YEAR(tsp.`created_date`)=YEAR(CURDATE()), tsp.ic_id, NULL)) AS ytd_bagi_policies,
COUNT(IF(tsp.ic_id = 9, tsp.ic_id, NULL)) AS no_tata_policies, 
COUNT(IF(tsp.ic_id = 9 AND DATE(tsp.`created_date`)=CURDATE(), tsp.ic_id, NULL)) AS td_tata_policies,
COUNT(IF(tsp.ic_id = 9 AND MONTH(tsp.`created_date`)=MONTH(CURDATE()), tsp.ic_id, NULL)) AS mtd_tata_policies,
COUNT(IF(tsp.ic_id = 9 AND YEAR(tsp.`created_date`)=YEAR(CURDATE()), tsp.ic_id, NULL)) AS ytd_tata_policies,
COUNT(IF(tsp.ic_id = 10, tsp.ic_id, NULL)) AS no_oriental_policies,
COUNT(IF(tsp.ic_id = 10 AND DATE(tsp.`created_date`)=CURDATE(), tsp.ic_id, NULL)) AS td_oriental_policies,
COUNT(IF(tsp.ic_id = 10 AND MONTH(tsp.`created_date`)=MONTH(CURDATE()), tsp.ic_id, NULL)) AS mtd_oriental_policies,
COUNT(IF(tsp.ic_id = 10 AND YEAR(tsp.`created_date`)=YEAR(CURDATE()), tsp.ic_id, NULL)) AS ytd_oriental_policies,
COUNT(IF(tsp.ic_id = 7, tsp.ic_id, NULL)) AS no_hdfc_policies,
COUNT(IF(tsp.ic_id = 7 AND DATE(tsp.`created_date`)=CURDATE(), tsp.ic_id, NULL)) AS td_hdfc_policies,
COUNT(IF(tsp.ic_id = 7 AND MONTH(tsp.`created_date`)=MONTH(CURDATE()), tsp.ic_id, NULL)) AS mtd_hdfc_policies,
COUNT(IF(tsp.ic_id = 7 AND YEAR(tsp.`created_date`)=YEAR(CURDATE()), tsp.ic_id, NULL)) AS ytd_hdfc_policies,
COUNT(IF(tsp.ic_id = 8, tsp.ic_id, NULL)) AS no_reliance_policies,
COUNT(IF(tsp.ic_id = 8 AND DATE(tsp.`created_date`)=CURDATE(), tsp.ic_id, NULL)) AS td_reliance_policies,
COUNT(IF(tsp.ic_id = 8 AND MONTH(tsp.`created_date`)=MONTH(CURDATE()), tsp.ic_id, NULL)) AS mtd_reliance_policies,
COUNT(IF(tsp.ic_id = 8 AND YEAR(tsp.`created_date`)=YEAR(CURDATE()), tsp.ic_id, NULL)) AS ytd_reliance_policies,
COUNT(IF(tsp.ic_id = 13, tsp.ic_id, NULL)) AS no_liberty_policies,
COUNT(IF(tsp.ic_id = 13 AND DATE(tsp.`created_date`)=CURDATE(), tsp.ic_id, NULL)) AS td_liberty_policies,
COUNT(IF(tsp.ic_id = 13 AND MONTH(tsp.`created_date`)=MONTH(CURDATE()), tsp.ic_id, NULL)) AS mtd_liberty_policies,
COUNT(IF(tsp.ic_id = 13 AND YEAR(tsp.`created_date`)=YEAR(CURDATE()), tsp.ic_id, NULL)) AS ytd_liberty_policies,
COUNT(IF(tsp.rsa_ic_id = 1, tsp.rsa_ic_id, NULL)) AS no_bharti_policies, 
COUNT(IF(tsp.rsa_ic_id = 2, tsp.rsa_ic_id, NULL)) AS no_tvs_policies 
FROM tvs_sold_policies AS tsp JOIN tvs_dealers td ON tsp.`user_id`=td.`id` 
JOIN tvs_rm_dealers trd ON tsp.`user_id`=trd.`dealer_id` WHERE trd.`rm_id`='$rm_id' AND tsp.`policy_status_id`=3
GROUP BY tsp.`user_id` ";

$result = $this->db->query($sql)->result_array();
    // echo "<pre>"; print_r($result); echo "</pre>"; die('end of line yoyo');
    return $result;  
}

public function MappedRMDealerIC($rm_id)
{
    $sql = "SELECT t.dealer_name, t.sap_ad_code, IF(COUNT(DISTINCT d.pa_ic_id) > 1, GROUP_CONCAT(DISTINCT ic.display_name), ic.display_name) AS ic_name
        FROM dms_ic_and_pa_ic_mapping d INNER JOIN tvs_dealers t ON d.dealer_code = t.sap_ad_code
        INNER JOIN tvs_insurance_companies ic ON ic.id = d.pa_ic_id
        JOIN tvs_rm_dealers trd ON trd.`dealer_id`=t.`id` WHERE trd.`rm_id`='$rm_id'
        GROUP BY d.dealer_code";
    $result = $this->db->query($sql)->result_array();
    // echo "<pre>"; print_r($result); echo "</pre>"; die('end of line yoyo');
    return $result;

}


}