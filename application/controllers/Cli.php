<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Cli extends CI_Controller {

    public function __construct() {

      //  echo "innn"; exit;
        parent::__construct();
        $this->load->library('database_library');
        $this->load->model('Home_Model');
        $this->load->helper('common_helper');
        $this->load->helper('encdec_paytm');
        // isUserLoggedIn();
        /*       $this ->checkLogin(); */
    }


    function index(){
      echo 'hello amit';
    } 

    
      public function hitDmsApi(){
        $where = array();
        $vehicle_details = $this->Home_Model->getDataFromTable('sudeep_data',$where);
        // $response_data = $this->getTvsRsaPolicyData('PF5HH1545276');
        // echo '<pre>'; print_r($response_data);die('hello');
        // $insert_data 
        foreach ($vehicle_details as $key => $vehicle_detail) {
           $response_data = $this->getTvsRsaPolicyData($vehicle_detail['engine_no']);
           $policy_start_date = $response_data['policy']['policy_start_date'];
           $policy_start_date = str_replace('/', '-', $policy_start_date);
           $policy_end_date = $response_data['policy']['policy_end_date'];
            $policy_start_date = date("Y-m-d", strtotime($policy_start_date));
            $policy_end_date = str_replace('/', '-', $policy_end_date);
            $policy_end_date = date("Y-m-d", strtotime($policy_end_date));
            // echo 'policy_start_date:-'.$policy_start_date.'New:-'.$policy_start_date;
            // echo 'policy_end_date:-'.$policy_end_date.'New:-'.$policy_end_date;
            $insert_data = array(
                'engine_no'=>$response_data['vehicle']['engine_no'],
                'policy_start_date'=>$policy_start_date,
                'policy_end_date'=>$policy_end_date,
                'ic_name'=>$response_data['Insurance_Company_name'],
                'ic_id'=>$response_data['insurance_company_id']
            );

           $status = $this->Home_Model->insertIntoTable('dms_response_data',$insert_data);
           echo 'status:-'.$status.'<br>';
        }
        // echo '<pre>'; print_r($insert_data);die('hello');
       // $this->Home_Model->insertIntoTableBatch('dms_response_data',$insert_data);
      } 



    //   function getTvsRsaPolicyData1($engineNo) {
    //     $this->load->helper("soap_helper");
    //     $soap_url = 'https://www.advantagetvs.com/PolicyServices/PolicyService.asmx?WSDL';
    //     $soap_method = 'get_policy_detail';
    //     $username = '';
    //     $password = '';
    //     $newArray = array();
    //     //tokenexe='INSR201808160900|87000001|16/08/2018'
    //     $currentDate = date("d/m/Y");
    //     $data = '';
    //     $salt = 'INSR201808160900';
    //     $accessToken = $salt . '|' . $engineNo . '|' . $currentDate;
    //     $dataArr = array();
    //     $dataArr['engineno'] = $engineNo;
    //     $dataArr['token'] = strtoupper(hash('sha512', $accessToken));
    //     $dataString = json_encode($dataArr);
    //     $soap_body = '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
    //         <Body>
    //             <get_policy_detail xmlns="http://tempuri.org/">
    //                 <id>' . $dataString . '</id>
    //             </get_policy_detail>
    //         </Body>
    //     </Envelope>';
    //     $response = soapCall($soap_url, $soap_method, $soap_body, $soap_url, "", $username, $password);
    //     $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
    //     $xml = new SimpleXMLElement($response);
    //     $body = $xml->soapBody->get_policy_detailResponse->get_policy_detailResult;
    //     $val = array("\n", "\r");
    //     $body = str_replace($val, "", $body);
    //     $array = json_decode(($body), TRUE);
    //    // echo '<pre>';
    //    // print_r($array);
    //    // die;
    //     $ERROR_CODES = (isset($array['ERROR_CODES']) ? $array['ERROR_CODES'] : '');
    //     if ($ERROR_CODES == '') {

    //         $newArray = $array;
    //     }
    //     return $newArray;
    // }

    public function dealerDmsIcMapping(){
      // die('here');
        // $where = array('dms_ic_id !='=>'');
        // $pa_ic_details = $this->Home_Model->getDataFromTable('tvs_insurance_companies',$where);
        // echo '<pre>'; print_r($pa_ic_details);die('here');
        $tvs_dealers = $this->Home_Model->getDataFromTable('tvs_dealers');
        foreach ($tvs_dealers as $key => $tvs_dealer) {
           $dealer_code = $tvs_dealer['sap_ad_code'];
           $where = array('dms_ic_id !='=>'');
           $pa_ic_details = $this->Home_Model->getDataFromTable('tvs_insurance_companies',$where);
           $insert_data = array();
           foreach ($pa_ic_details as $key => $pa_ic_detail) {
              switch ($pa_ic_detail['dms_ic_id']) {
                case 13:
                 $pa_ic_id = 13;
                  break;
                case 5:
                 $pa_ic_id = 5;
                  break;
                case 12:
                 $pa_ic_id = 12;
                  break;
                case 9:
                 $pa_ic_id = 9;
                  break;
                default:
                 $pa_ic_id = 10;
                  break;
              }
             $insert_data[] = array(
              'dealer_code'=>$dealer_code,
              'dms_ic_id'=>$pa_ic_detail['dms_ic_id'],
              'pa_ic_id'=>$pa_ic_id,
             );
           }
           $is_exist = $this->db->select('*')->from('dms_ic_and_pa_ic_mapping')->where('dealer_code',$dealer_code)->get()->row();
           if(empty($is_exist)){
             $insert_query = $this->db->insert_batch('dms_ic_and_pa_ic_mapping', $insert_data);

             echo '<pre>'; print_r($insert_query);
           }
           else{
             echo 'already exist.'.'<br>';
           }
               //die('here');
           // break;
        }
        }

//     public function updateDealerDmsIcMapping(){
//         $dealers_dmsic_logic = $this->Home_Model->getDataFromTable('tvs_dealers_dmsic_logic');
        
// //        if()
        
        
        
        
        
//     }


    // public function updateLibartyIc(){
    //       $sql = "select id,sap_ad_code from tvs_dealers";
    //       $query = $this->db->query($sql);
    //       $dealer_codes = $query->result_array();
    //       foreach ($dealer_codes as $key => $dealer_code) {
    //         $sql = "select count(*) from dms_ic_and_pa_ic_mapping_copy where dealer_code = $dealer_code and ";
    //       }



    //         $pa_ic_count = 


    // }

    function asignRR310Dealer(){
       $rr310_dealers = $this->db->select('*')->from('rr310_dealers')->where('phase',1)->get()->result_array();
       foreach ($rr310_dealers as $key => $rr310_dealer) {
          
       }

    }



    function checkInvoiceMapping(){
           $sql = "select * from dealer_transection_statement_amit dts where dts.invoice_id IS NULL AND dts.transection_purpose IN ('GST Credit','TDS Deducted') ";
           //AND dts.dealer_id =  981
          //  group by dealer_id
          // echo $sql;
            $query =  $this->db->query($sql);
           // $num_row = $query->num_rows();
            $not_updated_dealers = $query->result_array();
           // echo '<pre>';print_r($not_updated_dealers);//die('hello moto');
          //  echo count($not_updated_dealers);
          //  die();
             if(!empty($not_updated_dealers)){
              $i = 0;
                  foreach ($not_updated_dealers as $key => $not_updated_dlrs) {
                      $dealer_id = $not_updated_dlrs['dealer_id'];
                      $transection_date = $not_updated_dlrs['transection_date'];
                      $transection_date = new DateTime($transection_date);
                      $transection_date = $transection_date->format('Y-m-d');
                      $transection_amount = $not_updated_dlrs['transection_amount'];
                      $sql = "SELECT * FROM invoice_details  id WHERE id.`dealer_id` = $dealer_id
                                AND ((JSON_EXTRACT(id.`invoice_data` , '$.total_policy_commission_gst')) = $transection_amount || (JSON_EXTRACT(id.`invoice_data`, '$.total_policy_commission')*(5/100)) = $transection_amount)
                                AND date(id.`updated_at`) = '$transection_date'
                                AND id.`invoice_status`= 'approved'
                      ";
                   //  echo $sql;
                      $query =  $this->db->query($sql);
                      //$num_row = $query->num_rows();
                      $dealers_invoices = $query->row_array();
                      if(!empty($dealers_invoices)){
                        $invoice_id = $dealers_invoices['id'];
                        $sql = "select * from dealer_transection_statement_amit where invoice_id = $invoice_id";
                        $query =  $this->db->query($sql);
                      //$num_row = $query->num_rows();
                        $is_exist_invoice = $query->result_array();
                        if(count($is_exist_invoice) > 1){
                          echo 'This Invoice Is Already Used :- '.$invoice_id.PHP_EOL;
                        }else{
                           $transection_no = $this->getRandomNumber('16');
                            // foreach ($dealers_invoices as $key => $dealers_invoice) {
                              $update_transection_statement_array = array(
                                'invoice_id'=>$dealers_invoices['id'],
                                'transection_no'=>$transection_no,
                                'transection_date'=>$not_updated_dlrs['transection_date'],
                                'updated_date'=>date('Y-m-d H:i:s'),
                                'is_updated'=>1
                              );
                               echo '<pre>'.$not_updated_dlrs['id']; print_r($update_transection_statement_array);

                              $this->db->where('id',$not_updated_dlrs['id']);
                              $j = 0;
                              $update_status = $this->db->update('dealer_transection_statement',$update_transection_statement_array);
                              if($update_status){
                                $j++;
                              }
                              echo $i.' :- is_updated :- '.$update_status.PHP_EOL.'total_updated_records :-'.$j;
                            // }








                         echo '<pre>';print_r($not_updated_dlrs).PHP_EOL;
                         echo $i.' -:- Invoice Id -:-'.$dealers_invoices['id']. ' -:- dealer_id -:-'.$dealers_invoices['dealer_id'].PHP_EOL;

                        $i++;
                        }
                      }
               }
              }
    }


     function getInvoiceMapping(){

      $sql = "select * from dealer_transection_statement dts where dts.invoice_id IS  NULL AND dts.transection_purpose IN ('GST Credit','TDS Deducted') group by dealer_id";
      $query =  $this->db->query($sql);
      $not_updated_dealers = $query->result_array();
      $num_row = $query->num_rows();
       echo '<pre>'; print_r($not_updated_dealers).' total:- '; //echo  $num_row;die('here');
      if(!empty($not_updated_dealers)){
      foreach ($not_updated_dealers as $key => $not_updated_dlrs) {
      $not_updated_dlrs_id = $not_updated_dlrs['dealer_id'];
      $sql = "select td.id,td.sap_ad_code,id.id as invoice_id,id.invoice_no,id.created_at,id.invoice_data 
      FROM invoice_details as id 
      INNER join tvs_dealers as td on td.id = id.dealer_id
      WHERE id.invoice_status = 'approved'
      AND td.id = $not_updated_dlrs_id
      ";
      $query =  $this->db->query($sql);
      $num_row = $query->num_rows();
      $results = $query->result_array();
      $transection_statement_amounts = array();
      $i = 0;
      foreach ($results as $key => $result) {
        $dealer_id = $result['id'];
        $invoice_id = $result['invoice_id'];
        $sql = "select count(1) as count from dealer_transection_statement AS dts WHERE dts.dealer_id = $dealer_id 
        AND dts.invoice_id = $invoice_id";
        $query =  $this->db->query($sql);
        $invoice_exist_count = $query->row_array();

           echo 'key:- '.$key.' -:- dealer_id :-'.$dealer_id. ' -:- invoice_id :-'.$result['invoice_id'].' -:- invoice_exist_count:- '.$invoice_exist_count['count'].PHP_EOL;
         if($invoice_exist_count['count'] == 2){
            continue;
         }





        $invoice_details = json_decode($result['invoice_data'],TRUE);
        $policy_commission_gst_amount =  $invoice_details['total_policy_commission_gst'];
        $total_policy_commission =  $invoice_details['total_policy_commission'];
        $invoice_created_date =  $result['created_at'];
        $policy_commission_tds_amount = (5 / 100) *  $total_policy_commission;
        $dealer_deposit_amount = ($policy_commission_gst_amount - $policy_commission_tds_amount);
        
        $sql ="select * from dealer_transection_statement AS dts 
        WHERE dts.dealer_id = $dealer_id 
        AND ROUND(dts.transection_amount) IN (ROUND($policy_commission_gst_amount),ROUND($policy_commission_tds_amount)) 
        AND dts.transection_purpose IN ('GST Credit','TDS Deducted') 
        AND invoice_id IS  NULL";
         echo $sql.PHP_EOL; 
        // AND date(dts.transection_date) <= date('2019-10-10')  
        //AND date(dts.transection_date) >= date('$invoice_created_date')
        $query =  $this->db->query($sql);
        $transection_statement_num_row = $query->num_rows();
        $transection_statement = $query->result_array();
        echo '<pre>'; print_r($transection_statement).PHP_EOL;
       echo $result['invoice_id'] .' :- ' . $invoice_exist_count['count'] . PHP_EOL;
        if((!empty($transection_statement)) && $invoice_exist_count['count'] <= 1){
          $transection_no = $this->getRandomNumber('16');
          foreach ($transection_statement as $key => $transection) {
            $update_transection_statement_array = array(
              'invoice_id'=>$result['invoice_id'],
              'transection_no'=>$transection_no,
              'transection_date'=>$transection['transection_date'],
              'updated_date'=>date('Y-m-d H:i:s'),
              'is_updated'=>1
            );
             echo '<pre>'; print_r($update_transection_statement_array);

            $this->db->where('id',$transection['id']);
            $j = 0;
            $update_status = $this->db->update('dealer_transection_statement',$update_transection_statement_array);
            if($update_status){
              $j++;
            }
            echo $i.' :- is_updated :- '.$update_status.PHP_EOL.'total_updated_records :-'.$j;
          }
        
        }
        $i++;
      }

      }
      }
      


    }
      



    

    function getInvoiceMappingForNewGST(){

      $sql = "select td.id,td.sap_ad_code,td.is_gst_compliant,id.id as invoice_id,id.invoice_no,id.invoice_data 
      from invoice_details as id 
      inner join tvs_dealers as td on td.id = id.dealer_id
      where id.invoice_status = 'approved'";
      // echo $sql;
      $query =  $this->db->query($sql);
      $results = $query->result_array();
     // echo '<pre>'.PHP_EOL; print_r($results);die('hello moto');
      $transection_statement_amounts = array();
      $i = 0;
      foreach ($results as $key => $result) {
        $invoice_details = json_decode($result['invoice_data'],TRUE);
        $policy_commission_gst_amount =  $invoice_details['total_policy_commission_gst'];
        $total_policy_commission =  $invoice_details['total_policy_commission'];
        // echo $result['invoice_id'].PHP_EOL;
        $policy_commission_tds_amount = (5 / 100) *  $total_policy_commission;
        // echo $policy_commission_tds_amount.PHP_EOL;
        $dealer_deposit_amount = ($policy_commission_gst_amount - $policy_commission_tds_amount);
        $dealer_id = $result['id'];
      $sql ="select * from dealer_transection_statement AS dts where dts.dealer_id = $dealer_id and dts.transection_amount IN ($policy_commission_gst_amount,$policy_commission_tds_amount) AND dts.transection_purpose IN ('GST Credit','TDS Deducted')";
       
        // and date(dts.transection_date) >= date('2019-10-04');
        $query =  $this->db->query($sql);
        $transection_statement = $query->row_array();
        // echo $sql.PHP_EOL;
        // echo '<pre>'; print_r($transection_statement);
        // if($i == 3){
        //   break;
        // }
        if(!empty($transection_statement)){

          $is_inserted = $this->db->insert('total_gst_tds_data',$transection_statement);
          // echo $this->db->last_query().PHP_EOL;die('here');
          echo $i.' :-is_inserted '. $is_inserted.PHP_EOL;
          $transection_no = $this->getRandomNumber('16');
          // echo $transection_no.PHP_EOL;
          $transection_statement_amounts = array(
            'transection_statement_id'=>$transection_statement['id'],
            'dealer_id'=>$transection_statement['dealer_id'],
            'dealer_code'=>$result['sap_ad_code'],
            'invoice_no'=>$result['invoice_no'],
            'invoice_id'=>$result['invoice_id'],
            'transection_amount'=>$transection_statement['transection_amount'],
            'tds_amount'=>$policy_commission_tds_amount,
            'total_policy_count'=>$invoice_details['total_policy_count'],
            'total_policy_premium'=>$invoice_details['total_policy_premium'],
            'total_policy_gst'=>$invoice_details['total_policy_gst'],
            'final_policy_premium'=>$invoice_details['final_policy_premium'],
            'total_policy_commission'=>$invoice_details['total_policy_commission'],
            'commission_gst'=>$invoice_details['total_policy_commission_gst'],
            'final_policy_commission'=>$invoice_details['final_policy_commission'],
            'invoice_date'=>$invoice_details['invoice_date'],
            'invoice_month'=>$invoice_details['post_invoice_month'],
            'transaction_date'=>$transection_statement['transection_date']
          );
        //    if($i == 3){
        //   break;
        // }
        // $update_transection_statement_array = array(
        //   'transection_amount'=>$invoice_details['total_policy_commission_gst'],
        //   'invoice_id'=>$result['invoice_id'],
        //   'transection_no'=>$transection_no,
        //   'is_updated'=>1
        // );
        // $this->db->where('id',$transection_statement['id']);
        // $update_status = $this->db->update('dealer_transection_statement',$update_transection_statement_array);
        // echo $update_status.PHP_EOL;
        // if($update_status){
        //     $insert_data = array(
        //       'dealer_id'=>$transection_statement['dealer_id'],
        //       'invoice_id'=>$result['invoice_id'],
        //       'transection_no'=>$transection_no,
        //       'transection_type'=>'dr',
        //       'transection_amount'=>$policy_commission_tds_amount,
        //       'transection_purpose'=>'TDS Deducted',
        //       'transection_date'=>$transection_statement['transection_date'],
        //       'is_updated'=>1
        //     );
           // echo '<pre>'; print_r($insert_data);
            // $is_gst_inserted = $this->db->insert('dealer_transection_statement',$insert_data);
            // // echo $this->db->last_query();
            // if($is_gst_inserted){
            //    $status = $this->db->insert('tvs_gst_data',$transection_statement_amounts);
            // }
        }
       
        // echo $this->db->last_query().PHP_EOL;
         // echo 'IS INSERTED '. $i ." :".$transection_statement['id'].' :-> '.$status.PHP_EOL;
        // }

        // echo '<pre>'; echo $i; print_r($transection_statement_amounts).PHP_EOL;
        $i++;
      }
      


    }



    function updateInvoiceIdByTds(){
      $sql = "SELECT * FROM  dealer_transection_statement_copy AS dts WHERE dts.`invoice_id` <> 0 AND dts.`transection_purpose` = 'TDS Deducted' AND DATE(transection_date) >= DATE('2019-10-11')";

      $query =  $this->db->query($sql);
      $transection_statement_tds = $query->result_array();
      // echo '<pre>'; print_r($transection_statement_tds);die('here');
      if(!empty($transection_statement_tds)){
        $i= 1;
        foreach ($transection_statement_tds as $key => $statement_tds) {
          echo '<pre>'.$i; print_r($statement_tds);
         $update_array = array(
            'invoice_id'=>$statement_tds['invoice_id']
         );
         $where = array('transection_no'=>$statement_tds['transection_no'],'dealer_id'=>$statement_tds['dealer_id'],'invoice_id'=>0,'transection_purpose'=>'GST Credit');
         $selected_records = $this->db->select('*')->from('dealer_transection_statement_copy')->where($where)->get()->result_array();
         // 
         // echo $i. 'is_updated:-'.$is_updated.PHP_EOL;
         if($selected_records){
            $this->db->where($where);
            $is_updated =  $this->db->update('dealer_transection_statement_copy',$update_array);

         echo 'is_updated :- '.$is_updated.'<pre>'.$i; //print_r($selected_records).PHP_EOL;
        // die('here');
          $i++;
         }
        }
      }


    }


     function getRandomNumber($len)
        {
            $better_token = $code=sprintf("%0".$len."d", mt_rand(1, str_pad("", $len,"9")));
            return $better_token;
        }



     function insertTdsData(){

        $sql = "SELECT id.`id` AS invoice_id,id.`dealer_id`,id.`invoice_no`,id.`invoice_month`, JSON_EXTRACT(id.`invoice_data` , '$.total_policy_commission') AS total_policy_commission,
          JSON_EXTRACT(id.`invoice_data` , '$.total_policy_commission_gst') AS total_policy_commission_gst,
          (JSON_EXTRACT(id.`invoice_data` , '$.total_policy_commission')*(5/100)) AS total_policy_commission_tds,
          id.`created_at`,id.`updated_at` FROM invoice_details_amit AS id WHERE id.`dealer_id` <> 2871";
          //echo $sql;
        $query =  $this->db->query($sql);
        $not_updated_invoices = $query->result_array();
        // echo '<pre>'.$key; print_r($not_updated_invoices);
        if(!empty($not_updated_invoices)){
         // die('here');
          $i = 1 ;
            foreach ($not_updated_invoices as $key => $not_updated_invoice) {
            //  echo '<pre>'.$key; print_r($not_updated_invoice);
              $invoice_id = $not_updated_invoice['invoice_id'];
              $sql = "SELECT * FROM dealer_transection_statement WHERE invoice_id = $invoice_id";
              $query =  $this->db->query($sql);
              $is_invoice_exist = $query->result_array();
              // echo '<pre>';  print_r($is_invoice_exist);
              if(empty($is_invoice_exist)){
                $transection_no = $this->getRandomNumber(16);
                // echo 'pre1';
                $insert_array = array(
                  'dealer_id'=>$not_updated_invoice['dealer_id'],
                  'invoice_id'=>$invoice_id,
                  'transection_no'=>$transection_no,
                  'transection_type'=>'dr',
                  'transection_amount'=>$not_updated_invoice['total_policy_commission_tds'],
                  'transection_purpose'=>'TDS Deducted',
                  'transection_date'=>$not_updated_invoice['updated_at']
                );
                // echo '<pre>'; print_r($insert_array);echo PHP_EOL;
               // $is_insert_tds =  $this->db->insert('dealer_transection_statement',$insert_array);
               // echo $this->db->last_query();
               // $is_insert_tds = TRUE;
                 if($is_insert_tds){
                     echo 'tds inserted'.PHP_EOL;
                     $insert_array = array(
                      'dealer_id'=>$not_updated_invoice['dealer_id'],
                      'invoice_no'=>$not_updated_invoice['invoice_no'],
                      'invoice_id'=>$invoice_id,
                      'transaction_no'=>$transection_no,
                      'gst_amount'=>$not_updated_invoice['total_policy_commission_gst'],
                      'created_at'=>$not_updated_invoice['updated_at']
                    );
                    // echo '<pre>'; print_r($insert_array);echo PHP_EOL;
                  // $is_insert_gst =  $this->db->insert('tvs_dealers_gst_status',$insert_array);
                   //echo  $this->db->last_query();
                   if($is_insert_gst){
                    echo $i.' :- pending gst inserted :- '.$is_insert_gst.PHP_EOL;
                    $i++;
                   }
               }
              }else{
                echo 'Invoice Is Exist <pre>';print_r($is_invoice_exist);//die('here');
              }

              //die('here');
            }
        }
    }

// function checkIsInvoiceUptoDate(){

//       $sql = "SELECT id.`id` AS invoice_id,id.`dealer_id`,id.`invoice_no`,id.`invoice_month`, JSON_EXTRACT(id.`invoice_data` , '    $.total_policy_commission') AS total_policy_commission,
//           JSON_EXTRACT(id.`invoice_data` , '$.total_policy_commission_gst') AS total_policy_commission_gst,
//           (JSON_EXTRACT(id.`invoice_data` , '$.total_policy_commission')*(5/100)) AS total_policy_commission_tds,
//           id.`created_at`,id.`updated_at` FROM invoice_details AS id WHERE id.`dealer_id` <> 2871 AND invoice_status = 'approved'";
//       $query =  $this->db->query($sql);
//       $invoice_details = $query->result_array();

//      // echo '<pre>'; print_r($invoice_details);die('hello moto');
//       if(!empty($invoice_details)){
//         foreach ($invoice_details as $key => $invoice_detail) {
//          $dealer_id =  $invoice_detail['dealer_id'];
//          $invoice_id =  $invoice_detail['invoice_id'];
//          $gst_amount =  $invoice_detail['total_policy_commission_gst'];
//          $tds_amount =  $invoice_detail['total_policy_commission_tds'];
        
//          $sql = "SELECT * FROM dealer_transection_statement WHERE dealer_id = $dealer_id AND invoice_id = $invoice_id";
//         //die($sql);
//          $query =  $this->db->query($sql);
//          $invoice_trns_details = $query->result_array();
//          if(!empty($invoice_trns_details)){

//               foreach ($invoice_trns_details as $key => $invoice_trns_detail) {
//                   if($invoice_trns_detail['transection_purpose'] == 'GST Credit'){

//                       echo 'invoice id :- '. $invoice_id;
//                       echo ' transection_amount :- '. round($invoice_trns_detail['transection_amount']);
//                       echo ' gst amount :- ' . round($gst_amount).PHP_EOL;
//                       if(round($gst_amount) != round($invoice_trns_detail['transection_amount'])){
//                         $insert_array = array(
//                           'dealer_id'=>$dealer_id,
//                           'invoice_id'=>$invoice_id,
//                           'invoice_trns_id'=>$invoice_trns_detail['id'],
//                           'gst_amount'=>$gst_amount,
//                           'transection_amount'=>$invoice_trns_detail['transection_amount'],
//                           'transection_purpose'=>'GST Credit'
//                         );
//                         $this->db->insert('mismached_invoices',$insert_array);

//                         // $update_array = array(
//                         //   'transection_amount'=>$gst_amount
//                         // );
//                         // $where = array(
//                         //   'id'=>$invoice_trns_detail['id']
//                         // );


//                        // $is_updated = $this->db->update('dealer_transection_statement_copy',$update_array);
//                         // if($is_updated){
//                         //   echo 'GST Credit Updated'.$invoice_id;
//                         // }
//                       }else{
//                         echo $invoice_id.' This Invoice Uptodate for gst'.PHP_EOL;
//                       }

//                     //gst conditions will be here
//                   }
//                   if($invoice_trns_detail['transection_purpose'] == 'TDS Deducted'){
//                      // echo '<pre>'; print_r($invoice_trns_detail);echo PHP_EOL;
//                   echo 'invoice id :- '.$invoice_id;
//                   echo ' transection_amount :- '.round($invoice_trns_detail['transection_amount']);
//                   echo ' tds amount :- '.round($tds_amount).PHP_EOL;
//                        if(round($tds_amount) != round($invoice_trns_detail['transection_amount'])){

//                            $insert_array = array(
//                             'dealer_id'=>$dealer_id,
//                             'invoice_id'=>$invoice_id,
//                             'invoice_trns_id'=>$invoice_trns_detail['id'],
//                             'gst_amount'=>$tds_amount,
//                             'transection_amount'=>$invoice_trns_detail['transection_amount'],
//                             'transection_purpose'=>'TDS Deducted'
//                           );
//                           $this->db->insert('mismached_invoices',$insert_array);
//                       //   $update_array = array(
//                       //     'transection_amount'=>$tds_amount
//                       //   );
//                       //   $where = array(
//                       //     'id'=>$invoice_trns_detail['id']
//                       //   );
//                       //   $is_updated = $this->db->update('dealer_transection_statement_copy',$update_array);
//                       // if($is_updated){
//                       //     echo 'TDS Deducted Updated'.$invoice_id;
//                          // }
//                       }else{
//                         echo $invoice_id.' This Invoice Uptodate for tds'.PHP_EOL;
//                       }



//                     //tds conditions will be here
//                   }
//               }

//          }else{

//                 $transection_no = $this->getRandomNumber(16);
//                     // echo 'pre1';
//                     $insert_array = array(
//                       'dealer_id'=>$invoice_detail['dealer_id'],
//                       'invoice_id'=>$invoice_id,
//                       'transection_no'=>$transection_no,
//                       'transection_type'=>'dr',
//                       'transection_amount'=>$invoice_detail['total_policy_commission_tds'],
//                       'transection_purpose'=>'TDS Deducted',
//                       'transection_date'=>$invoice_detail['updated_at']
//                     );
//                     // echo '<pre>'; print_r($insert_array);echo PHP_EOL;
//                     $is_insert_tds =  $this->db->insert('dealer_transection_statement_copy1',$insert_array);
//                    // echo $this->db->last_query();
//                    // $is_insert_tds = TRUE;
//                      if($is_insert_tds){
//                          echo 'tds inserted'.PHP_EOL;
//                          $insert_array = array(
//                           'dealer_id'=>$invoice_detail['dealer_id'],
//                           'invoice_no'=>$invoice_detail['invoice_no'],
//                           'invoice_id'=>$invoice_id,
//                           'transaction_no'=>$transection_no,
//                           'gst_amount'=>$invoice_detail['total_policy_commission_gst'],
//                           'created_at'=>$invoice_detail['updated_at']
//                         );
//                         // echo '<pre>'; print_r($insert_array);echo PHP_EOL;
//                        $is_insert_gst =  $this->db->insert('tvs_dealers_gst_status_copy1',$insert_array);
//                        if($is_insert_gst){
//                             echo $i.' :- pending gst inserted :- '.$is_insert_gst.PHP_EOL;
//                             $i++;
//                        }
//                     }

//               }
//        }
//   }
// }

        function sendPaymentLink(){
          $sql = "SELECT tcd.*,tsp.* FROM tvs_customer_details AS tcd 
                  INNER JOIN tvs_sold_policies AS tsp ON tcd.id = tsp.customer_id
                  WHERE tsp.`plan_name` = 'LIMITLESS ASSIST E (RR 310)'
                  AND (date(tsp.`created_date`) between '2019-12-18' AND '2019-12-26')
                  AND tsp.`policy_status_id` = 3
                  AND tsp.`user_id` = 4632
                  ";
                  $query =  $this->db->query($sql);
                  $limitless_policy_details = $query->result_array();
                  //echo '<pre>'; print_r($limitless_policy_details);die('here');

                foreach ($limitless_policy_details as $key => $customer_details) {
                  extract($customer_details);
                  $mobile_no = '7977588122';
                  $is_sms_sent = $this->sendSms($fname,$sold_policy_no,$mobile_no);
                  echo $is_sms_sent;
                }

        }


    function sendSms($name,$policy_no,$Mobilenumber) { 
        $payment_url = base_url('Cli/payTmPayment/'.$Mobilenumber);
        $Message = "TVS Apache RR310: Dear $name,
                    Because of some payment getway issue your payment was not completed againset TVS Apache RR310 RSA policy $policy_no.
                    Kindly click on given link to complete your payment. $payment_url";

        
        error_reporting(1);
        $uid = "MPOLNW";
        $pwd = "Pwe\$NiTm";
        $sid = "TVSRSA";
        $method = "POST";
        $message = urlencode($Message);
        //$get_url = "http://www.k3digitalmedia.in/vendorsms/pushsms.aspx?user=" . $uid . "&password=" . $pwd . "&msisdn=" . $Mobilenumber . "&sid=" . $sid . "&msg=" . $message . "&fl=0&gwid=2";

        $get_url = "http://123.108.46.12/API/WebSMS/Http/v1.0a/index.php?username=".$uid."&password=".$pwd."&sender=".$sid."&to=".$Mobilenumber."&message=".$message."&reqid=1&format=text";

       
       
        
        $result = $this->httpGet($get_url);

        return $result;
    }

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



function payTmPayment($mobile_no){
    $sql = "SELECT tsp.`id` as inserted_id,tsp.`transection_no` FROM tvs_customer_details AS tcd 
    INNER JOIN tvs_sold_policies AS tsp ON tcd.id = tsp.customer_id
    WHERE tcd.mobile_no = $mobile_no";
    $query =  $this->db->query($sql);
    $invoice_details = $query->row_array();
    extract($invoice_details);
    //echo '<pre>'; print_r($invoice_details);die('here');
    $dealer_details = $this->Home_Model->getDealersDetails();
    $this->checkPayment($transection_no,$inserted_id,$invoice_details,$dealer_details);
}


function checkPayment($transection_no,$inserted_id,$post_data,$user_data){
            $checkSum = "";
            $paramList = array();
            $ORDER_ID = $transection_no;
            $CUST_ID = $user_data['sap_ad_code'];
            $paramList = array(
                "MID" => MID,
                "WEBSITE" => WEBSITE,
                "INDUSTRY_TYPE_ID" => INDUSTRY_TYPE_ID,
                "CHANNEL_ID" => CHANNEL_ID,
                "ORDER_ID" => $transection_no,
                "CUST_ID" => $CUST_ID,
                "MOBILE_NO" => !empty($post_data['mobile_no'])?$post_data['mobile_no']:'',
                "EMAIL" => !empty($post_data['email'])?$post_data['email']:'',
                "TXN_AMOUNT" => 998,
                "CALLBACK_URL" => base_url('Cli/paymentResponseSpl').'/'.$transection_no.'/'.$inserted_id
            );
            // echo '<pre>'; print_r($paramList);die('hello');
            $checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_MID);
            $data['checkSum'] = $checkSum;
            $data['paramList'] = $paramList;
            $this->load->view('welcome_message', $data);

        }


    function paymentResponseSpl($transection_no,$inserted_id){
                  //echo '<pre>'; print_r($transection_no);
                 // echo '<pre>'; print_r($inserted_id);
                  // $get_data = $this->input->get();
                  // echo '<pre>'; print_r($transection_no);
                  // echo '<pre>'; print_r($inserted_id);
                  // die('here');
                  $paytmChecksum = "";
                  $paramList = array();
                  $isValidChecksum = "FALSE";
                  $paramList = $_POST;
                  $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; 
                  $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum);     
                  if($isValidChecksum){
                      $insert_payment_response = array(
                                          'sold_policy_id' => $inserted_id,
                                          'customer_id' => $inserted_id,
                                          'order_id' => $_POST["ORDERID"],
                                          'merchant_id' => $_POST["MID"],
                                          'transaction_id' => $_POST["TXNID"],
                                          // 'transaction_amount' => 1,
                                          'transaction_amount' => $_POST["TXNAMOUNT"],
                                          'payment_mode' => $_POST["PAYMENTMODE"],
                                          'currency'=> $_POST["CURRENCY"],
                                          'transaction_date'=> $_POST["TXNDATE"],
                                          'status'=> $_POST["STATUS"],
                                          'respcode'=> $_POST["RESPCODE"],
                                          'respmsg'=> $_POST["RESPMSG"],
                                          'gatewayname'=> $_POST["GATEWAYNAME"],
                                          'banktransactionid'=> $_POST["BANKTXNID"],
                                          'bankname'=> $_POST["BANKNAME"],
                                          'checksumhash'=> $_POST["CHECKSUMHASH"]);
                      $res_payment_response=$this->Home_Model->insertIntoTable('tvs_payment_response_missing',$insert_payment_response);

                    $res_payment_response = 1;
                      if ($_POST["STATUS"] == "TXN_SUCCESS") {
                         // echo $inserted_id; echo '<pre>'; print_r($_POST);

                          $res_upd_sold_policies = 1;
                          if($res_upd_sold_policies){
                              $paymentStatus = $this->paymentStatus();
                              if($paymentStatus['STATUS'] == 'TXN_SUCCESS'){
                                     $where = array(
                                        'order_id' => $_POST["ORDERID"]
                                    );
                                    $input_data = array(
                                         'trns_approval_status' => 1,
                                    );
                                    $this->updateTable('tvs_payment_response_missing',$input_data,$where);


                                       $this->session->set_userdata('policy_id',$inserted_id);
                                       redirect('Cli/paymentSuccessSpl'); 
                                   
                          }else{
                              echo 'something went wrong kindly contact to tech team.';
                          }        
                      }else {
                          $this->session->set_flashdata('message', $_POST["RESPMSG"]);
                          redirect('rr_310_policy');
                      } 
                     
                  }else{
                      $this->session->set_flashdata('message', $_POST["RESPMSG"]);
                      redirect('rr_310_policy');
                  }
              }

}

    function paymentSuccessSpl(){
        $policy_id = !empty(($this->session->userdata('policy_id')))?$this->session->userdata('policy_id'):$this->input->get('id');

        $this->data['ic_pdf'] = 'download_rr_310_pdf';
        $this->data['inserted_id'] = $policy_id;
        $this->load->dashboardTemplate('front/myaccount/rr_310_success', $this->data);
    }     




    function paymentStatus(){
        // echo '<pre>'; print_r($_POST);//die('hello');
        $ORDER_ID = "";
        $requestParamList = array();
        $responseParamList = array();

        if (isset($_POST["ORDERID"]) && $_POST["ORDERID"] != "") {
            $ORDER_ID = $_POST["ORDERID"];
            $MID = $_POST["MID"];
            $requestParamList = array("MID" => $MID , "ORDERID" => $ORDER_ID);  
            // echo '<pre>'; print_r($requestParamList);
            $StatusCheckSum = getChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY);
            $requestParamList['CHECKSUMHASH'] = $StatusCheckSum;
            $responseParamList = getTxnStatusNew($requestParamList);
                return $responseParamList;
        }else{
                return FALSE;
        }
    }




    function checkTransections(){

      $sql = "SELECT id,sap_ad_code FROM tvs_dealers AS td WHERE td.pan_no <>''";
      $query =  $this->db->query($sql);
      $dealer_details = $query->result_array();

 //     echo '<pre>'; print_r($dealer_details);die('here');
      if (!empty($dealer_details)) {

        foreach ($dealer_details as $key => $dealer_detail) {

          $dealer_id = $dealer_detail['id'];
          $sql = "SELECT sold_policy_no,user_id FROM tvs_sold_policies as tsp WHERE tsp.user_id = $dealer_id";
          $query =  $this->db->query($sql);
          $policy_details = $query->result_array();
          if(!empty($policy_details)){

          foreach ($policy_details as $key => $policy_detail) {
            $sold_policy_no = $policy_detail['sold_policy_no'];
            $dealer_id = $policy_detail['user_id'];
            $sql = "SELECT * FROM dealer_transection_statement AS dts WHERE policy_no like '%$sold_policy_no%' AND dealer_id = $dealer_id";
           // die($sql);
            $query =  $this->db->query($sql);
            $transection_details = $query->result_array();
            if (!empty($transection_details)) {
              echo '<pre>'; print_r($transection_details);
            }else{

            $insert_array = array(
              'policy_no'=>trim($sold_policy_no),
              'dealer_id'=>$dealer_id
            );
            $is_inserted = $this->db->insert('transection_not_exist_policy',$insert_array);
            if($is_inserted){
              echo 'Inserted '.PHP_EOL;
            }
              echo 'Transection Dose Not Exist'.PHP_EOL;
              }
          }
      }else{
          echo 'Policy Dose Not Exist For Dealer Id:-'.$dealer_id;
      }
        }

    }else{
      echo 'Dealer Not Exist'.PHP_EOL;
      }
}



function updateDealersWallet(){
//  $result_array = $thi->db->select('*')->from('dealer_wallet_amount')->where('updated_status',0)->get()->result_array();

  $sql = "SELECT 
          SUM(CASE 
              WHEN dts.`transection_purpose` = 'Wallet Credit'
              THEN dts.transection_amount 
              ELSE 0 
          END) AS total_deposit_amount,

          SUM(CASE 
              WHEN dts.`transection_purpose` = 'Commission'
              THEN dts.transection_amount 
              ELSE 0 
          END) AS total_dealer_commission_amount,

          SUM(CASE 
              WHEN dts.`transection_purpose` = 'Policy Cancelled'
              THEN dts.transection_amount 
              ELSE 0 
          END) AS total_cancelled_policy_amount,

          SUM(CASE 
              WHEN dts.`transection_purpose` = 'GST Credit'
              THEN dts.transection_amount 
              ELSE 0 
          END) AS total_gst_credit_amount,

          SUM(CASE 
              WHEN dts.`transection_purpose` = 'Wallet Debit'
              THEN dts.transection_amount 
              ELSE 0 
          END) AS total_withdrowal_amount,

          SUM(CASE 
              WHEN dts.`transection_purpose` = 'Policy Created'
              THEN dts.transection_amount 
              ELSE 0 
          END) AS total_policy_created_amount,

          SUM(CASE 
              WHEN dts.`transection_purpose` = 'TDS Deducted'
              THEN dts.transection_amount 
              ELSE 0 
          END) AS total_tds_deducted_amount,


          SUM(CASE 
              WHEN dts.`transection_purpose` = 'Commission diducted'
              THEN dts.transection_amount 
              ELSE 0 
          END) AS total_commission_deducted_amount,

          ((SUM(CASE 
              WHEN dts.`transection_purpose` = 'Wallet Credit'
              THEN dts.transection_amount 
              ELSE 0 
          END)+SUM(CASE 
              WHEN dts.`transection_purpose` = 'Commission'
              THEN dts.transection_amount 
              ELSE 0 
          END)+SUM(CASE 
              WHEN dts.`transection_purpose` = 'Policy Cancelled'
              THEN dts.transection_amount 
              ELSE 0 
          END)+SUM(CASE 
              WHEN dts.`transection_purpose` = 'GST Credit'
              THEN dts.transection_amount 
              ELSE 0 
          END)) -(SUM(CASE 
              WHEN dts.`transection_purpose` = 'Wallet Debit'
              THEN dts.transection_amount 
              ELSE 0 
          END)+SUM(CASE 
              WHEN dts.`transection_purpose` = 'Policy Created'
              THEN dts.transection_amount 
              ELSE 0 
          END)+SUM(CASE 
              WHEN dts.`transection_purpose` = 'TDS Deducted'
              THEN dts.transection_amount 
              ELSE 0 
          END)+SUM(CASE 
              WHEN dts.`transection_purpose` = 'Commission diducted'
              THEN dts.transection_amount 
              ELSE 0 
          END)) ) AS total_wallet_balance,

          ((count(if(dts.`transection_purpose` = 'Policy Created',1,null))) - (count(if(dts.`transection_purpose` = 'Policy Cancelled',1,null)))) as count_of_live_policy,
          (dw.security_amount - dw.credit_amount) AS actual_dealer_wallet,
          td.`sap_ad_code`,td.`ad_name`,dttr.`total_deposit_amount` AS final_deposit_amount,
          dttr.`total_withdrowal_amount` AS final_withdrowal_amount,DATE_FORMAT(dts.`transection_date`,'%M %Y') AS MONTH
          FROM `dealer_transection_statement`  AS dts
          INNER JOIN tvs_dealers AS td ON td.id = dts.`dealer_id`
          INNER JOIN dealer_wallet AS dw ON dw.`dealer_id` = td.`id`
          INNER JOIN `dealer_total_transaction_record` AS dttr ON dttr.`dealer_id` = td.id 
          GROUP BY dts.dealer_id";
          $query =  $this->db->query($sql);
          $transection_details = $query->result_array();
          echo '<pre>'; print_r($transection_details);die('hello moto');






  if(!empty($result_array)){
    foreach ($result_array as $key => $result) {
      $dealer_id = $result['dealer_id'];
      $amount = $result['amount'];
    }
  }
}



function getWrongPolicyDetailsCustomers(){
  $sql ="SELECT td.`id` as dealer_id,td.`sap_ad_code`,td.`dealer_name`,tsp.`id` as policy_id,tsp.`sold_policy_no`,tsp.`engine_no`,tsp.`chassis_no`,tcd.`mobile_no`,tcd.`fname`,tsp.`sold_policy_effective_date`,tsp.`sold_policy_end_date`,tsp.`pa_sold_policy_effective_date`,tsp.`pa_sold_policy_end_date`,tsp.`ic_id`
          FROM tvs_sold_policies tsp
          INNER JOIN tvs_customer_details tcd ON tsp.`customer_id`=tcd.`id`
          INNER JOIN tvs_dealers td ON td.`id`=tsp.`user_id`
          WHERE tsp.`created_date` <= '2019-08-30' AND tsp.`policy_status_id` IN (3,4) 
          AND tsp.`plan_name`='Sapphire' AND tsp.`ic_id` IN (12,9,5)";
          $query =  $this->db->query($sql);
          $policy_details = $query->result_array();
          if(!empty($policy_details)){

           // echo '<pre>'; print_r($policy_details);die('here');
            $i= 1;
            foreach ($policy_details as $key => $policy_detail) {

              if($i >2){
                break;
              }
              $engine_no = $policy_detail['engine_no'];
              $insert_array = array(
                'dealer_id'=>$policy_detail['dealer_id'],
                'sap_ad_code'=>$policy_detail['sap_ad_code'],
                'dealer_name'=>$policy_detail['dealer_name'],
                'policy_id'=>$policy_detail['policy_id'],
                'sold_policy_no'=>$policy_detail['sold_policy_no'],
                'engine_no'=>$policy_detail['engine_no'],
                'chassis_no'=>$policy_detail['chassis_no'],
                'mobile_no'=>$policy_detail['mobile_no'],
                'fname'=>$policy_detail['fname'],
                'created_at'=>date('Y-m-d H:i:s')
              );

             $is_inserted =  $this->db->insert('wrong_punched_policies', $insert_array); 
            if(!empty($is_inserted)){
                    echo 'api call'.PHP_EOL;
                    $resposed_data = $this->getTvsRsaPolicyData(strtoupper($engine_no));
                    echo '<pre>'; print_r($resposed_data);//die('here');
                    $is_sms_sent = '';
                    if(!empty($resposed_data)){
                      $resposed_data['insured_mobile_no'] = '8108818995';
                      $is_sms_sent = $this->sendRightPolicySms($policy_detail,$resposed_data['insured_mobile_no']);
                    }else{
                      $policy_detail['mobile_no'] = '8108818995';
                      $is_sms_sent = $this->sendRightPolicySms($policy_detail,$policy_detail['mobile_no']);
                    }
                    if(!empty($is_sms_sent)){
                      $this->db->where('policy_id',$policy_detail['policy_id']);
                      $this->db->update('wrong_punched_policies',array('is_sms_sent'=>1));
                    }else{
                      echo 'SMS IS NOT SENT';
                    }
                    // echo strtoupper($engine_no).':-'.$resposed_data['insured_mobile_no'].PHP_EOL;
              }else{
                     echo 'DATA NOT INSERTED';
                 }
              $i++;
            }
          } 
}

function sendSmsToCustomers(){
    $sql = "SELECT tsp.`sold_policy_no`,tcd.`fname`,tsp.`sold_policy_effective_date`,tsp.`sold_policy_end_date`,tsp.`pa_sold_policy_effective_date`,tsp.`pa_sold_policy_end_date`,tsp.`ic_id` FROM wrong_punched_policies AS wpp 
    INNER JOIN tvs_sold_policies tsp ON tsp.`id` = wpp.`policy_id`
    INNER JOIN tvs_customer_details tcd ON tsp.`customer_id`=tcd.`id`
    WHERE wpp.`is_link_opened` = 0";
    $query =  $this->db->query($sql);
    $customer_details = $query->result_array();
  // echo '<pre>'; print_r($customer_details);die('hello');
    if(!empty($customer_details)){
       $i= 1;
      foreach ($customer_details as $key => $customer_detail) {
        if($i >2){
                break;
              }
        $resposed_data = $this->getTvsRsaPolicyData(strtoupper($customer_detail['engine_no']));
        $is_sms_sent = '';
        if(!empty($resposed_data)){
          $resposed_data['insured_mobile_no'] = '8108818995';
          $is_sms_sent = $this->sendRightPolicySms($customer_detail,$resposed_data['insured_mobile_no']);
        }else{
          $customer_detail['mobile_no'] = '8108818995';
          $is_sms_sent = $this->sendRightPolicySms($customer_detail,$policy_detail['mobile_no']);
        }
        if(!empty($is_sms_sent)){
          $this->db->where('policy_id',$customer_detail['policy_id']);
          $this->db->update('wrong_punched_policies',array('is_sms_sent'=>1,'mobile_no'=>$resposed_data['insured_mobile_no']));
          echo 'SMS IS SENT'.PHP_EOL;
        }else{
          echo 'SMS IS NOT SENT'.PHP_EOL;
        }
      }

    }else{
      echo 'CUSTOMER DATA NOT EXIST'.PHP_EOL;
    }
}





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
        // echo $soap_body;
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
        // echo "<pre>"; print_r($array); echo "</pre>"; die('end of line yoyo');
        $ERROR_CODES = (isset($array['ERROR_CODES']) ? $array['ERROR_CODES'] : '');
        if ($ERROR_CODES == '') {
            $newArray = $array;
        }
        return $newArray;
    }

    function sendRightPolicySms($policy_detail,$Mobilenumber) { 
      $policy_url = base_url('Cli/viewSmsUrl/'.$policy_detail['ic_id'].'/'.$policy_detail['policy_id']);
       $name = !empty($policy_detail['fname'])?$policy_detail['fname']:'NAN';
       $policy_no = !empty($policy_detail['sold_policy_no'])?$policy_detail['sold_policy_no']:'NAN';
       $pre_start_date = !empty($policy_detail['sold_policy_effective_date'])?$policy_detail['sold_policy_effective_date']:'';
       if(!empty($pre_start_date)){
         $pre_start_date = new DateTime($pre_start_date);
         $pre_start_date = $pre_start_date->format('d/m/Y');
       }
       $pre_end_date = !empty($policy_detail['sold_policy_end_date'])?$policy_detail['sold_policy_end_date']:'';
       if(!empty($pre_start_date)){
         $pre_end_date = new DateTime($pre_end_date);
         $pre_end_date = $pre_end_date->format('d/m/Y');
       }
       $curr_start_date = !empty($policy_detail['pa_sold_policy_effective_date'])?$policy_detail['pa_sold_policy_effective_date']:'';
       if(!empty($curr_start_date)){
         $curr_start_date = new DateTime($curr_start_date);
         $curr_start_date = $curr_start_date->format('d/m/Y');
       }
       $curr_end_date = !empty($policy_detail['pa_sold_policy_end_date'])?$policy_detail['pa_sold_policy_end_date']:'';
       if(!empty($curr_end_date)){
         $curr_end_date = new DateTime($curr_end_date);
         $curr_end_date = $curr_end_date->format('d/m/Y');
       }
        $Message = "Dear $name, PA certificate no. $policy_no issued to you for the insurance period from $pre_start_date till $pre_end_date is incorrect. The correct period is from $curr_start_date till $curr_end_date. Sorry for the inconvenience caused.For new policy pdf kindly click on given link $policy_url. Team Indicosmic Capital.";
        //die($Message);
        //echo $Message;
        error_reporting(1);
        $uid = "MPOLNW";
        $pwd = "Pwe\$NiTm";
        $sid = "TVSRSA";
        $method = "POST";
        $message = urlencode($Message);
        //$get_url = "http://www.k3digitalmedia.in/vendorsms/pushsms.aspx?user=" . $uid . "&password=" . $pwd . "&msisdn=" . $Mobilenumber . "&sid=" . $sid . "&msg=" . $message . "&fl=0&gwid=2";

        $get_url = "http://123.108.46.12/API/WebSMS/Http/v1.0a/index.php?username=".$uid."&password=".$pwd."&sender=".$sid."&to=".$Mobilenumber."&message=".$message."&reqid=1&format=text";

       
       
        
        $result = $this->httpGet($get_url);

        return $result;
    }


function viewSmsUrl($ic_id,$policy_id){
  if(!empty($policy_id) &&  is_numeric($policy_id)){

      $this->db->where('policy_id',$policy_id);
      $is_updated = $this->db->update('wrong_punched_policies',array('link_opened_date_time'=>date('Y-m-d H:i:s'),'is_link_opened'=>1));
      if($is_updated){

        $ic_url = '';
        switch ($ic_id) {
          case 5:
            $ic_url = 'download_icici_full_policy';
            break;
           case 12:
            $ic_url = 'download_bhartiaxa_full_policy';
            break;
           case 9:
            $ic_url = 'download_tata_full_policy';
            break;
          default:
            break;
        }
        if(!empty($ic_url)){

          redirect($ic_url.'/'.$policy_id);
        }else{
          echo 'Policy Dose Not Exist';
        }
      }

  }else{
    echo 'Policy Dose Not Exist';
  }
}









}
