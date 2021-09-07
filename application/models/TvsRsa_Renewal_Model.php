<?php

class TvsRsa_Renewal_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }


function getSoldPolicyBYEngineNo($engineno){
        $sql = "SELECT tsp.`id` AS policy_id,tsp.`engine_no`,tsp.`chassis_no`,tsp.`vehicle_registration_no`,tsp.`model_name`,tsp.`model_id`,tsp.`plan_id`,
        tsp.`sold_policy_effective_date`,tsp.`sold_policy_end_date`,tsp.`created_date`,tsp.`ic_id`,tsp.`policy_status_id`,tc.`fname`,tc.`lname`,
        tc.`addr1`,tc.`addr2`,tc.`mobile_no`,tc.`email`,tc.`city_name`,tc.`state_name`,tc.`pincode`,tc.`nominee_age`,tc.`nominee_full_name`,
        tc.`nominee_relation`,tc.`gender`,tc.`appointee_age`,tc.`appointee_full_name`,tc.`appointee_relation`,tc.`dob`
        FROM tvs_sold_policies tsp 
        INNER JOIN tvs_customer_details tc ON tsp.`customer_id`=tc.`id` WHERE tsp.`policy_status_id`=3 AND tsp.`engine_no`='$engineno' ";
        $result = $this->db->query($sql)->row_array();
        return $result;
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
        // $policyid= $this->input->post('policyid');
        // $policy_data = $this->HomeMdl->getPolicyById($policyid);
        // echo '<pre>';print_r($policy_data);die;
        $selected_model = $policy_data['model_name'];
        $models = $this->getDataFromTableWithOject('tvs_model_master');
        $data['html'] = "<option value='' >SELECT MODEL</option>";
        if (!empty($models)) {
            foreach ($models as $row) {
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

}