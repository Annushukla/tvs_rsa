<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Database_library {

    function __construct() {
        $this->CI = & get_instance();
    }

    function checkLogin() {
        if ($this->CI->session->has_userdata('admin_session'))
            return true;
        else
            return false;
    }

    function postAdminLogin($email, $pass) {
        $where = array('email_id' => $email, 'password' => md5($pass) ,'is_active'=>1 );
        $this->CI->db->select('*');
        $this->CI->db->where($where);
        $query = $this->CI->db->get('tvs_admin');
        $result = $query->row();

        if (isset($result)) {
            $admin_data = array(
                'user_fullname' => $result->first_name . ' ' . $result->last_name,
                'email' => $result->email_id,
                'logged_in' => $result->id,
                'mobile_no' => $result->mobile_no,
                'admin_role' => $result->admin_role,
                'admin_role_id' => $result->admin_role_id,
                'ic_id' => $result->ic_id,
                'is_active' => $result->is_active
            );
            $this->CI->session->set_userdata('admin_session', $admin_data);
            return true;
        } else {
            return false;
        }
    }

function global_login() {
        $this->CI->session->sess_destroy();
         // echo '<pre>'; print_r($_POST);die('hello');
        if (isset($_POST) && $_POST['type'] == 'tvs_rsa') {

            $timestamp = $_POST['timestamp'];
            $sap_ad_code = $_POST['sap_ad_code'];
            $accesskey=$sap_ad_code."-INDICLAIM-".$timestamp;
            $post_accesskey = $_POST['accesskey'];
            // echo $post_accesskey;echo '</br>';
            //  echo md5($accesskey);
            if($post_accesskey == md5($accesskey)){
                 $result = $this->checkUserLogin($sap_ad_code);
                 if($result['status'] == TRUE){
                 return true;
             }else{
                return false;
             }
            }else{
                //die('not mached');
                return false;
            }
           
        } else {
            return false;
        }
    }


    // function global_login() {
    //     // $this->CI->session->sess_destroy();
    //     echo '<pre>'; print_r($_POST);die('hello');
    //     if (isset($_POST)) {
    //         $accesskey = $_POST['accesskey'];
    //         $sap_ad_code = $_POST['sap_ad_code'];
    //         $type = $_POST['type'];
    //         $newdata = array(
    //             'accesskey' => $accesskey,
    //             'sap_ad_code' => $sap_ad_code,
    //             'type' => $type
    //         );
    //         $result = $this->checkUserLogin($sap_ad_code, $sap_ad_code);
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    function postLogin($email, $pass) {
        // echo $email.'<br>';echo $pass;die('log');

        $result = $this->checkUserLogin($email, $pass);
// print_r($result);die('kjk');
        if ($result['status'] == 1) {
            return true;
        } else {
            return false;
        }
    }

function checkUserLogin($username, $password ='') {
//        $this->session->unset_userdata('user_session');
        if($password !=''){
                $sql = "SELECT * FROM `biz_users` WHERE `employee_code` = '$username'  AND `password` = '" . md5($password) . "' AND `is_active` = 1  limit 1";
        }else{
                $sql = "SELECT * FROM `biz_users` WHERE `employee_code` = '$username' AND `is_active` = 1  limit 1";
        }
        $query = $this->CI->db->query($sql);
       // echo $this->CI->db->last_query();
       // exit;
        $query_count = $query->num_rows();
        $retutn_data = array('status' => false, 'redirect' => false, 'message' => "something went wrong");

        if ($query_count >= 1) {
            $agent_result = $query->row();
            $dealer_code = $agent_result->dealer_code;
            $query = $this->CI->db->query("SELECT * FROM `tvs_dealers` WHERE `sap_ad_code` = '$dealer_code' limit 1");
            $customer_result = $query->row();
           // echo '<pre>';
           // print_r($customer_result);
           // die('hello moto');
            $customer_id = $customer_result->id;
            $session_data = array(
                "id" => $customer_result->id,
                "dealer_name" => $customer_result->dealer_name,
                'logged_in' => $customer_id,
                "dealer_code" => $agent_result->dealer_code,
                "amd_code" => $customer_result->dealer_code,
                "sap_ad_code" => $agent_result->employee_code,
                "ad_name" => $customer_result->ad_name,
                "tin_no" => $customer_result->tin_no,
                "gst_no" => $customer_result->gst_no,
                "pan_no" => $customer_result->pan_no,
                "bank_name" => $customer_result->bank_name,
                "bank_acc_name" => $customer_result->bank_acc_name,
                "bank_acc_no" => $customer_result->bank_acc_no,
                "bank_ifsc_code" => $customer_result->bank_ifsc_code,
                "add1" => $customer_result->add1,
                "add2" => $customer_result->add2,
                "add3" => $customer_result->add3,
                "location" => $customer_result->location,
                "state" => $customer_result->state,
                "pin_code" => $customer_result->pin_code,
                "email" => $customer_result->email,
                "rsa_ic_master_id" => $customer_result->rsa_ic_master_id,
                'company_type_id' => $customer_result->company_type_id,
                'Company_type_name' => $company_data['type_name'],
            );

            if (isset($customer_id) && !empty($customer_id)) {
                $status = $this->CI->session->set_userdata('user_session', $session_data);
                $retutn_data['status'] = true;
            } else {
                $retutn_data['status'] = false;
            }
        }

        // echo '<pre>';
           // print_r($customer_result);
           // die('hello moto');
        return $retutn_data;
    }

//     function checkUserLogin($username, $password) {
//         // $session = $this->CI->session->userdata('user_session');
//         // if(!empty($session)){
//         //    redirect('dealer_form');
//         // }

// //        $this->session->unset_userdata('user_session');
//         $query = $this->CI->db->query("SELECT * FROM `biz_users` WHERE `employee_code` = '$username'  AND `password` = '" . md5($password) . "' AND `is_active` = 1  limit 1");
//         $query_count = $query->num_rows();
//         $retutn_data = array('status' => false, 'redirect' => false, 'message' => "something went wrong");

//         if ($query_count >= 1) {
//             $agent_result = $query->row();
//             $dealer_code = $agent_result->dealer_code;
//             $query = $this->CI->db->query("SELECT * FROM `tvs_dealers` WHERE `sap_ad_code` = '$dealer_code' limit 1");
//             $customer_result = $query->row();
//             $customer_id = $customer_result->id;
//             $session_data = array(
//                 "id" => $customer_result->id,
//                 "dealer_name" => $customer_result->dealer_name,
//                 'logged_in' => $customer_id,
//                 "dealer_code" => $customer_result->dealer_code,
//                 "sap_ad_code" => $agent_result->employee_code,
//                 "ad_name" => $customer_result->ad_name,
//                 "tin_no" => $customer_result->tin_no,
//                 "gst_no" => $customer_result->gst_no,
//                 "pan_no" => $customer_result->pan_no,
//                 "bank_name" => $customer_result->bank_name,
//                 "bank_acc_name" => $customer_result->bank_acc_name,
//                 "bank_acc_no" => $customer_result->bank_acc_no,
//                 "bank_ifsc_code" => $customer_result->bank_ifsc_code,
//                 "add1" => $customer_result->add1,
//                 "add2" => $customer_result->add2,
//                 "add3" => $customer_result->add3,
//                 "location" => $customer_result->location,
//                 "state" => $customer_result->state,
//                 "pin_code" => $customer_result->pin_code,
//                 "email" => $customer_result->email,
//                 "rsa_ic_master_id" => $customer_result->rsa_ic_master_id,
//                 'company_type_id' => $customer_result->company_type_id,
//                 'Company_type_name' => $company_data['type_name'],
//             );

//             if (isset($customer_id) && !empty($customer_id)) {
//                 $status = $this->CI->session->set_userdata('user_session', $session_data);
//                 $retutn_data['status'] = true;
//             } else {
//                 $retutn_data['status'] = false;
//             }
//         }

//         // echo '<pre>';
//            // print_r($customer_result);
//            // die('hello moto');
//         return $retutn_data;
//     }

    function getCustomerDetails($id) {
        $result = false;
        $query = $this->CI->db->query("SELECT * from pa_dealers where id = '$id'");
        $query_count = $query->num_rows();
        //echo $this->CI->db->last_query();
        if ($query_count > 0) {
            $result = $query->row();
        }
//        echo '<pre>';
//        print_r($result);
//        exit;
        return $result;
    }

    function uploadImage($image_file, $directory) {
        if ($image_file && $directory) {
            $filename = $_FILES[$image_file]["name"];
            $_FILES[$image_file]["name"] = time() . $filename;
            $config = array(
                'upload_path' => './' . $directory,
                'allowed_types' => 'jpg|jpeg|gif|png',
            );
            $this->CI->load->library("upload", $config);
            if ($this->CI->upload->do_upload($image_file)) {
                $image_data = $this->CI->upload->data();
                return $newimagename = $image_data["file_name"];
            } else {
                return false;
            }
        }
    }

    function resizeImage($height, $width, $source_path, $destination_path) {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $source_path;
        $config['new_image'] = './' . $destination_path;
        $config['create_thumb'] = TRUE;
        $config['thumb_marker'] = '';
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $width;
        $config['height'] = $height;

        $this->CI->load->library('image_lib', $config);
        if ($this->CI->image_lib->resize())
            return true;
        else
            return false;
    }

    //$upload_path = "./uploads/products/";
    //$image_title = "user_album_";
    //$image_file = "user_album_images";
    function multipleImageUpload($upload_path, $image_file, $image_title) {

        $config = array(
            'upload_path' => $upload_path,
            'allowed_types' => 'jpg|jpeg|gif|png'
        );
        $this->CI->load->library('upload', $config);
        $images = array();
        $files = $_FILES[$image_file];

        foreach ($files['name'] as $key => $image) {

            $_FILES['images[]']['name'] = $files['name'][$key];
            $_FILES['images[]']['type'] = $files['type'][$key];
            $_FILES['images[]']['tmp_name'] = $files['tmp_name'][$key];
            $_FILES['images[]']['error'] = $files['error'][$key];
            $_FILES['images[]']['size'] = $files['size'][$key];
            $title = $image_title . time();
            $fileName = $title . '_' . $files['name'][$key];
            $images[] = $fileName;
            $newimagenames[] = $fileName;
            $config['file_name'] = $fileName;
            $this->CI->upload->initialize($config);

            if ($this->CI->upload->do_upload('images[]')) {
                $image_detail_data = $this->CI->upload->data();
                $this->CI->load->library("image_lib");
                $config['image_library'] = 'gd2';
                $config['source_image'] = $image_detail_data["full_path"];
                $config['create_thumb'] = TRUE;
                $config['thumb_marker'] = '';
                $config['maintain_ratio'] = TRUE;

                $config['new_image'] = $upload_path . '100x100/';
                $config['width'] = 100;
                $config['height'] = 100;
                $this->CI->image_lib->initialize($config);
                $this->CI->image_lib->resize();
            }

            if ($this->CI->upload->do_upload('images[]')) {
                $image_detail_data = $this->CI->upload->data();
                $this->CI->load->library("image_lib");
                $config['image_library'] = 'gd2';
                $config['source_image'] = $image_detail_data["full_path"];
                $config['create_thumb'] = TRUE;
                $config['thumb_marker'] = '';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = $upload_path . '500x500/';
                $config['width'] = 500;
                $config['height'] = 500;
                $this->CI->image_lib->initialize($config);
                $this->CI->image_lib->resize();
            }

            if ($this->CI->upload->do_upload('images[]')) {
                $image_detail_data = $this->CI->upload->data();
                $this->CI->load->library("image_lib");
                $config['image_library'] = 'gd2';
                $config['source_image'] = $image_detail_data["full_path"];
                $config['create_thumb'] = TRUE;
                $config['thumb_marker'] = '';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = $upload_path . '300x300/';
                $config['width'] = 300;
                $config['height'] = 300;
                $this->CI->image_lib->initialize($config);
                $this->CI->image_lib->resize();
            }
        }
        return $newimagenames;
    }

    function sendEmail($from, $to, $subject, $message, $name) {

        $this->CI->load->library('email');
        $this->CI->email->from($from, $name);
        $this->CI->email->to($to);
        $this->CI->email->cc('another@another-example.com');
        $this->CI->email->bcc('them@their-example.com');
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        if ($this->CI->email->send())
            return true;
        else
            return false;
    }

    function calculateLagnitudeLatitude($address) {
        //$address = '201 S. Division St., Ann Arbor, MI 48104'; // Google HQ
        $prepAddr = str_replace(' ', '+', $address);
        $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
        $output = json_decode($geocode);
        $lat = $output->results[0]->geometry->location->lat;
        $long = $output->results[0]->geometry->location->lng;
        $data['lat'] = $lat;
        $data['long'] = $long;
        return $data;
        //echo $address.'<br>Lat: '.$lat.'<br>Long: '.$long;
    }

    function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    function setJsonEncode($data) {
        return json_encode($data);
    }

}
