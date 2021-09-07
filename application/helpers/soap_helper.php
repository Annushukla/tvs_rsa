<?php

class soap_helper extends SoapClient {

    var $XMLStr = "";
    var $new_location = "";

    function setXMLStr($value, $new_location) {
        $this->XMLStr = $value;
        $this->new_location = $new_location;
    }

    function getXMLStr() {
        return $this->XMLStr;
    }

    function __doRequest($request, $location, $action, $version, $one_way = 0) {

        $request = $this->XMLStr;
        $location = $this->new_location;
        // print_r($location);die();
        $dom = new DOMDocument('1.0');

        try {
            $dom->loadXML($request);
        } catch (DOMException $e) {
            die($e->code);
        }

        $request = $dom->saveXML();

        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }

    function SoapClientCall($SOAPXML, $new_location) {
        return $this->setXMLStr($SOAPXML, $new_location);
        $this->setXMLStr($SOAPXML, $new_location);
    }

}

function soapCall($wsdlURL, $callFunction = "", $XMLString, $new_location = "", $soap_url_param = "", $username = "", $password = "") {
    $CI_obj = get_instance();
    try {
//          $productSess = $_SESSION['user_action_data']['product_type'];
//          $icSess = $_SESSION['mpn_data']['ic_detail']->InsuranceCompanyID;
          
//        if($icSess==3 || $productSess=='travel'){
//            $context = stream_context_create(array(
//                'ssl' => array(
//                    'verify_peer' => false,
//                    'verify_peer_name' => false,
//                    'allow_self_signed' => true
//                )
//            ));
//
//
//            // SOAP 1.2 client
//            $params = array(
//                'trace' => true,
//                'stream_context' => $context,
//                'login' => $username,
//                'password' => $password
//            );
//
//            $wsdlUrl = $wsdlURL . '?WSDL';
//             $client = new soap_helper($wsdlURL, $params);
//            $reply = $client->SoapClientCall($XMLString, $new_location);
//            $client->__call("$callFunction", array(), array());
//            
//        }else{
             $client = new soap_helper($wsdlURL, array('trace' => true, 'login' => $username,
            'password' => $password));
            $reply = $client->SoapClientCall($XMLString, $new_location);
            $client->__call("$callFunction", array(), array());

//        }
       

        return $client->__getLastResponse();
    } catch (Exception $e) {
        $msg = 'Message: ' .$e->getMessage();
        log_message('error', $msg);
    }
}
?>

