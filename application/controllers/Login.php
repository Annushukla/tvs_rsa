<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		Parent::__construct();
			$this->load->library('database_library');
			$this->load->model('Home_Model');
			$this->load->helper('common_helper');
		
	}
	
	public function index(){
		isUserLoggedIn();
		$this->load->view('front/home/index');
	}

	public function submitLogin(){
		// echo strtoupper($this->input->post('email')).'<br>';echo strtoupper($this->input->post('password'));die('log');
		if($this->database_library->postLogin(strtoupper($this->input->post('email')),$this->input->post('password'))){
			redirect(base_url().'dashboard');
		}else{

			redirect(base_url());
		}
	}
	public function dump_session(){
		echo '<pre>'; print_r($this->session->userdata());die;
	}

	public function global_login(){
	$return_status = $this->database_library->global_login();
	$all_session = $this->session->all_userdata();

	if($return_status){
			//temprrary fix for sessu=ion loss issue and need to check database from 
		    $sap_ad_code = $all_session['user_session']['sap_ad_code'];
			$user_session = json_encode($all_session['user_session']);
			$date = new DateTime("now");
            $curr_date = $date->format('Y-m-d');
			$where = array('user_session'=>$user_session,'DATE(created_at)'=>$curr_date);
			$row  =  $this->Home_Model->getRowDataFromTable('redirect_key_log', $where);
			if(isset($row['user_session'])&&!empty($row['user_session'])){
				$redirect_key = $row['redirect_key'];
				$insert_id = $row['id'];
			}else{
				$redirect_key = md5(time().$sap_ad_code);
				$insert_id = $this->Home_Model->insertIntoTable('redirect_key_log',array('redirect_key'=>$redirect_key,'user_session'=>$user_session,'sap_ad_code'=>$sap_ad_code));
			}
			
			if($insert_id){
				redirect(base_url().'dashboard/'.$redirect_key);
			}else{
				return false;
			}
		}else{
			redirect(base_url());
		}		
	}

	
	public function DealerLogout(){
		// die('in');
		// session_unset(); 
		session_destroy();
		// $this->session->unset_userdata('user_session');
		// $this->session->unset_userdata('policy_data');
		// $this->session->unset_userdata('pa_customer_id');
		redirect(base_url());
	}

	// public function admin(){
	// 	$this->load->view('admin/login/admin');
	// }
	

	// public function submitAdminLogin(){
	
	// 	if($this->database_library->postAdminLogin($this->input->post('email'),$this->input->post('password')))
	// 		redirect(base_url().'Admin/dashboard');
	// 	else
	// 		redirect(base_url().'admin');
	// }


	// public function SubmitLoginForm(){
	// 	// die('submitloginform');
	// 	$post = $this->input->post();
	// 	$username = isset($post['username']) ? $post['username'] : '';
	// 	$userpassword = $post['userpassword'];
	// 	$result = array();
	// 	$result['status'] = true;
	// 	$result['error_email'] = true;

	// 	$is_user_logged =  $this->database_library->postLogin($username,$userpassword);
	// 	if($is_user_logged){
	// 		$result['user_exist'] = 'true';
	// 	}else{
	// 		$result['user_exist'] = 'false';
	// 	}
	// 	// echo($is_user_logged);die('loggd');
		
	// 	echo json_encode($result);

		
	// }


	
	
}

