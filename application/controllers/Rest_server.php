<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rest_server extends CI_Controller {

	function __construct() {
        parent::__construct();
    }

    public function index_post(){
    	echo "sfdf"; exit;
        
    }


    public function geCountOfOldNewPolicy(){
    	$sql ="select vehicle_registration_no from tvs_sold_policies where user_id <> 2871 and policy_status_id = 3";
    	$query = $this->db->query($sql);
    	$results = $query->result_array();
    	$new_vehicle = 0;
    	$old_vehicle = 0;
    	foreach ($results as $key => $result) {
    		$vehicle_regist_no = explode('-', $result['vehicle_registration_no']);
    		if(empty($vehicle_regist_no[2])){
    			$new_vehicle = $new_vehicle+1;
    		}else{
    			$old_vehicle = $old_vehicle+1;
    		}
    	}
    	echo 'new_vehicle :-'.$new_vehicle;


    	echo 'old_vehicle:-'.$old_vehicle;
    	// $vehicle_registration_no = $
    	die('hello moto');
    }
}
