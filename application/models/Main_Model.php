<?php
class Main_Model extends CI_Model {

        public function __construct(){
            parent::__construct();
        }
	
    function getDataFromTableWithArray($table_name = null, $where = null) {
        if ($where != null) {
            $this->db->where($where);
        }
        return $result = $this->db
            ->from($table_name)
            ->get()
            ->result_array();
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

    function getRowDataFromTableWithArray($table_name = null, $where = null) {
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
        return $result = $this->db
            ->from($table_name)
            ->get()
            ->row();
    }

    function getRowDataFromTableUsingLikeWithArray($table_name = null, $where = null) {
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
        if ($this->db->update($table)) {
            return true;
        } else {
            return false;
        }
    }

    function insertIntoTable($table_name, $data) {
        if ($this->db->insert($table_name, $data)) {
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
        if ($this->email->send()){
            return true;
        }else{
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
            'mailtype'  => 'html', 
            'charset'   => 'utf-8'
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
       
        $this->email->from($from, $name);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()){
            return true;
        }else{
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

	function admin_email_check($email){
		$id = $this->session->userdata('admin_session')['logged_in'];
		return $this->db->select('user_id')->where('user_id<>',$id)->where('user_email',$email)->get('hc_users')->row();
	}
	
	function user_email_check($email,$user_id){
		if($user_id)
			return $this->db->select('user_id')->where('user_id<>',$user_id)->where('user_email',$email)->get('hc_users')->row();
		else
			return $this->db->select('user_id')->where('user_email',$email)->get('hc_users')->row();
	}

	function getProfileData($id){
		return $this->db->select('user_id,user_fullname,user_password,user_name,user_email,user_address,user_mobile_no_1,user_mobile_no_2,user_pincode,user_image')
		->where('user_id',$id)->get('hc_users')->row();
	}
	
	function getUsers(){
		$result =  $this->db->select('user_id,user_fullname,user_password,user_name,user_email,user_birthday,user_address,user_mobile_no_1,user_mobile_no_2,user_pincode,user_image')
		->where(array('user_is_active' => 1,'user_is_deleted' => 0,'user_role_id'=>3 ))
		->get('hc_users')->result();
        return $result;
	}
	
	

	function submitProfile(){
		$this->db->set($this->input->post());
		$this->db->where('user_id', $this->session->userdata('admin_session')['logged_in']);
		$this->db->update('hc_users'); 
		if($this->db->affected_rows())
			return true;
		else
			return false;
	}
	
	
	
	
	function addUser(){
		if($this->db->insert('hc_users' , $this->input->post()))
			return $this->db->insert_id();
		else	
			return false;
	}
	
	function editUser($id){
		$this->db->set($this->input->post());
		$this->db->where('user_id', $id);
		$this->db->update('hc_users'); 
		if($this->db->affected_rows())
			return true;
		else
			return false;
	}
	
	function getSiteInfo(){
		return $this->db->select('id,name,email,mobile_no_1,mobile_no_2,image,footer')
		->where('id',1)->get('site_setting')->row();
	}
	
	function editSiteInformation(){
		$this->db->set($this->input->post());
		$this->db->where('id', 1);
		$this->db->update('site_setting'); 
		if($this->db->affected_rows())
			return true;
		else
			return false;
	}
	
	
	
	
	
	function getCustomers(){
		return $this->db->select('user_id,user_fullname')
		->where('user_is_active', 1)->where('user_is_deleted', 0)
		->where('user_role_id', 3)
		->from('hc_users')->get()->result();
	}

	
	
	
	
	
	
	
	
	
	function getCategories(){
		return $this->db->select('id,name')
		->from('categories')
		->where('is_deleted',0)
		->where('is_active',1)
		->get()->result();
	}
	
	function getAdminCategories(){
		return $this->db->select('id,name')
		->from('admin_categories')
		->where('is_deleted',0)
		->where('is_active',1)
		->get()->result();
	}
	
	function userDelete($user_id){
		$this->db->set('user_is_deleted','1');
		$this->db->set('user_deleted_at', date('Y-m-d H:i:s'));
		$this->db->where('id', $user_id);
		$this->db->update('users'); 
		if($this->db->affected_rows())
			return true;
		else
			return false;
	}
	
	
	
	
	
	function getEmailTemplate(){
		return $this->db->select('id,name,image')
		->from('email_templates')
		->get()->result();
	}
	
	
	
	function getSiteEmail(){
		return $this->db->select('email,name')
		->from('site_setting')
		->get()->row();
	}
	
	


	
}
	
