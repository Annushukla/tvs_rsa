<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	die('in');
    }

   

    public function dump_session() {
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
        //die;
    }


    public function update_dealers(){
        $this->load->model('Home_Model');
        $dealer_code = array();
        $dealers_data = $this->Home_Model->getDataFromTable('pa_dealers');
        foreach ($dealers_data as $key => $dealer) {
            $dealer_code  = $dealer['dealer_code'];

            $update_data = array(
                'user_password'=> md5($dealer_code),
                'user_role_id'=> 1
            );

            $this->db->where('id', $dealer['id']);
            $updated_query = $this->db->update('pa_dealers', $update_data);
            if($updated_query){
                echo "success: ".$dealer['id']."\n";
            }else{
                echo "error: ".$dealer['id']."\n";
            }
       // print_r($dealer['dealer_code']);die('dealers');
        }

        echo "Import complete";

    }

  
}
