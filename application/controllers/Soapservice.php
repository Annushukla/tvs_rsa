<?php

Class Soapservice extends Soapserver_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        echo "TVS CLAIM API SERVIVCES";
    }

    function example() {
        $ns = 'http://' . $_SERVER['HTTP_HOST'] . '/soapserver/';
        $method_name = 'addnumbers';
        $method_label = 'Addition Of Two Numbers';
        $input_array = array('a' => "xsd:string", 'b' => "xsd:string"); // "addnumbers" method parameters
        $return_array = array("return" => "xsd:string");
        $this->soap_service_call($ns,$method_name,$input_array,$return_array,$method_label);
        function addnumbers1($a, $b) {
            $c = $a + $b;
            return $c;
        }
    }
    //kavita chnages


    
    function get_dms_request(){

        $ns = 'http://' . $_SERVER['HTTP_HOST'] . '/soapserver/';
        $method_name = 'getdmsresponse';
        $method_label = 'Piggio DMS Response';

   

        $this->nusoap_server = new soap_server(); // create soap server object
        $this->nusoap_server->configureWSDL("Piggio DMS Webservice", $ns); // wsdl cinfiguration
        $this->nusoap_server->wsdl->schemaTargetNamespace = $ns; // server namespace

        
        $input_array = array(
            'USER_NAME' => "xsd:string",    
            'PASSWORD' => "xsd:string",
            'EngineNo' => "xsd:string",    
            'ChasisNo' => "xsd:string",    
            'RegistrationNo' => "xsd:string",
            'AMCCertificateNo' => "xsd:string",
            'DealerCode' => "xsd:string",
            'CustomerSalutation' => "xsd:string",
            'CustomerFirstName' => "xsd:string",
            'CustomerLastName' => "xsd:string",
            'CustomerNo' => "xsd:string",
            'CustomerEmail' => "xsd:string",
            'CustomerDOB' => "xsd:string",
            'CustomerGender' => "xsd:string",
            'Addr1' => "xsd:string",
            'Addr2' => "xsd:string",
            'Addr3' => "xsd:string",
            'City' => "xsd:string",
            'State' => "xsd:string",
            'Pincode' => "xsd:string",
            'NomineeName' => "xsd:string",
            'NomineeAge' => "xsd:string",
            'NomineeRelation' => "xsd:string",
            'AppointyName' => "xsd:string",
            'AppointyAge' => "xsd:string",
            'AppointyRelation' => "xsd:string"
        );
        

        $this->nusoap_server->wsdl->addComplexType(
            'dms_response','complexType','struct','all','',
            array(   
                'Error_code' =>array('name' =>'Status', 'type' => 'xsd:string'),
                "Success" => array('name' =>'Status_msg', 'type' => 'xsd:string'),
                "Message" => array('name' =>'Status_msg', 'type' => 'xsd:string')
            )
        );

        $return_array = array(
            'Error_code'=>"xsd:string",
            'Success'=>"xsd:string",
            'Message'=>"xsd:string"
        ); // "addnumbers" method parameters

        $return_array = array('return' => 'tns:dms_response');
        $this->nusoap_server->register($method_name, $input_array, $return_array, "urn:SOAPServerWSDL", "urn:" . $ns . "/" . $method_name, "rpc", "encoded", $method_label);

     
        function getdmsresponse($USER_NAME,$PASSWORD,$EngineNo,$ChasisNo,$RegistrationNo,$AMCCertificateNo,$DealerCode,$CustomerSalutation,$CustomerFirstName,$CustomerLastName,$CustomerNo,$CustomerEmail,$CustomerDOB,$CustomerGender,$Addr1,$Addr2,$Addr3,$City,$State,$Pincode,$NomineeName,$NomineeAge,$NomineeRelation,$AppointyName,$AppointyAge,$AppointyRelation) {

            $ci =& get_instance();
            $ci->load->model("Home_model");
            $ci->load->helper("common");

            $USER_NAME =($USER_NAME=='[string]')?'':$USER_NAME;

            $PASSWORD =($PASSWORD=='[string]')?'':$PASSWORD;
            $EngineNo =($EngineNo=='[string]')?'':$EngineNo;
            $ChasisNo =($ChasisNo=='[string]')?'':$ChasisNo;
            $RegistrationNo =($RegistrationNo=='[string]')?'':$RegistrationNo;
            $AMCCertificateNo =($AMCCertificateNo=='[string]')?'':$AMCCertificateNo;
            $DealerCode =($DealerCode=='[string]')?'':$DealerCode;
            $CustomerSalutation =($CustomerSalutation=='[string]')?'':$CustomerSalutation;
            $CustomerFirstName =($CustomerFirstName=='[string]')?'':$CustomerFirstName;
            $CustomerLastName =($CustomerLastName=='[string]')?'':$CustomerLastName;
            $CustomerNo =($CustomerNo=='[string]')?'':$CustomerNo;
            $CustomerEmail =($CustomerEmail=='[string]')?'':$CustomerEmail;
            $CustomerDOB =($CustomerDOB=='[string]')?'':$CustomerDOB;
            $CustomerGender =($CustomerGender=='[string]')?'':$CustomerGender;
            $Addr1 =($Addr1=='[string]')?'':$Addr1;
            $Addr2 =($Addr2=='[string]')?'':$Addr2;
            $Addr3 =($Addr3=='[string]')?'':$Addr3;
            $City =($City=='[string]')?'':$City;
            $State =($State=='[string]')?'':$State;

            $Pincode =($Pincode=='[string]')?'':$Pincode;
            $NomineeName =($NomineeName=='[string]')?'':$NomineeName;
            $NomineeAge =($NomineeAge=='[string]')?'':$NomineeAge;
            $NomineeRelation =($NomineeRelation=='[string]')?'':$NomineeRelation;
            $AppointyName =($AppointyName=='[string]')?'':$AppointyName;
            $AppointyAge =($AppointyAge=='[string]')?'':$AppointyAge;
            $AppointyRelation =($AppointyRelation=='[string]')?'':$AppointyRelation;

            // check valid user credentials
            $is_valid = 1;
            //$is_valid = $ci->Common_model->isValid($USER_NAME,$PASSWORD);


            // $where = array('claim_no' =>$ClaimNo);   
            // $ClaimListData=$ci->Common_model->getRowDataFromTableWithOject('claim_list',$where);

            if(empty($is_valid)){

                $Error_code="ERR_0001";
                $msg="Invalid Username, Please check Username and Password and try again.";
                $Success="0";

            }else { 
                if($USER_NAME==""){
                    $Error_code='ERR_0001';
                    $msg='Username Should Not Empty';
                    $Success='0';
                }elseif($PASSWORD==""){
                    $Error_code='ERR_0002';
                    $msg='Password Should Not Empty';
                    $Success='0';
                }elseif($EngineNo==""){
                     $Error_code='ERR_0003';
                    $msg='Engine Number Should Not Empty';
                    $Success='0';
                }elseif($ChasisNo==""){
                     $Error_code='ERR_0004';
                    $msg='Chassis Number Should Not Empty';
                    $Success='0';
                }elseif($RegistrationNo==""){
                     $Error_code='ERR_0005';
                    $msg='Registration NUmber Should Not Empty';
                    $Success='0';
                }elseif($AMCCertificateNo==""){
                    $Error_code='ERR_0006';
                    $msg='AMC Certificate NUmber Should Not Empty';
                    $Success='0';
                }elseif($DealerCode==""){
                    $Error_code='ERR_0007';
                    $msg='Dealer Code Should Not Empty';
                    $Success='0';
                }elseif($CustomerFirstName==""){
                    $Error_code='ERR_0008';
                    $msg='Customer First Name Should Not Empty';
                    $Success='0';
                }elseif($CustomerLastName==""){
                    $Error_code='ERR_0008';
                    $msg='Customer Last Name Should Not Empty';
                    $Success='0';
                }elseif($CustomerNo==""){
                    $Error_code='ERR_0009';
                    $msg='Customer Number Should Not Empty';
                    $Success='0';
                }
                elseif($CustomerEmail==""){
                    $Error_code='ERR_00010';
                    $msg='Customer Email Should Not Empty';
                    $Success='0';
                }
                elseif($CustomerDOB==""){
                    $Error_code='ERR_00011';
                    $msg='Customer DOB Should Not Empty';
                    $Success='0';
                }
                elseif($Addr1==""){
                    $Error_code='ERR_0007';
                    $msg='Customer Addr1 Should Not Empty';
                    $Success='0';
                }
                elseif($Addr2==""){
                    $Error_code='ERR_00012';
                    $msg='Customer Addr2 Should Not Empty';
                    $Success='0';
                }
                elseif($Addr3==""){
                    $Error_code='ERR_00013';
                    $msg='Customer Addr3 Should Not Empty';
                    $Success='0';
                }elseif($City==""){
                    $Error_code='ERR_00014';
                    $msg='Customer City Should Not Empty';
                    $Success='0';
                }elseif($State==""){
                    $Error_code='ERR_00015';
                    $msg='Customer State Should Not Empty';
                    $Success='0';
                }elseif($Pincode==""){
                    $Error_code='ERR_00016';
                    $msg='Customer Pincode Should Not Empty';
                    $Success='0';
                }elseif($NomineeName==""){
                    $Error_code='ERR_00017';
                    $msg='Nominee Name Should Not Empty';
                    $Success='0';
                }elseif($NomineeAge==""){
                    $Error_code='ERR_00018';
                    $msg='Nominee Age Should Not Empty';
                    $Success='0';
                }elseif($NomineeRelation==""){
                    $Error_code='ERR_00019';
                    $msg='Nominee Relation Should Not Empty';
                    $Success='0';
                }else{
                    $Error_code='No Error';
                    $msg='DMS Data Added Successfully.';
                    $Success='1';


                    
                    $insertCustomerDetails=array(
                        'salutation'=>$CustomerSalutation,
                        'fname'=>$CustomerFirstName,
                        'lname'=>$CustomerLastName,
                        'mobile_no'=>$CustomerNo,
                        'email'=>$CustomerEmail,
                        'dob'=>$CustomerDOB,
                        'gender'=>$CustomerGender,
                        'addr1'=>$Addr1,
                        'addr2'=>$Addr2,
                        'addr3'=>$Addr3,
                        'city'=>$City,
                        'state'=>$State,
                        'pincode'=>$Pincode,
                        'nominee_full_name'=>$NomineeName,
                        'nominee_relationship'=>$NomineeRelation,
                        'nominee_age'=>$NomineeAge,
                        'appointee_full_name'=>$AppointeeName,
                        'appointee_relationship'=>$AppointeeRelation,
                        'appointee_age'=>$AppointeeAge,
                    );  

                    $insertVehicleDetails= array(
                        'engine_no'=>$EngineNo,
                        'chassis_no'=>$ChasisNo,
                        'registration_no'=>$RegistrationNo,
                        'amc_registration_no'=>$AMCCertificateNo,
                        'dealer_code'=>$DealerCode,
                    );

                    $returnArr=$ci->Home_model->InsertIntoTable($requestArr);
                }
                  

                //
            }
         
            $inpectorResponse=array(
                'Error_code'=>$Error_code,
                'Success'=>$Success,
                'Message'=>$msg
            );
            return $inpectorResponse;
        }


        $this->nusoap_server->service(file_get_contents("php://input")); // read raw data from request body
    }


}
