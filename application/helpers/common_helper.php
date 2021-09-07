<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('checkMuthootLoginForRedirect')) {

    function checkMuthootLoginForRedirect() {
        $data = array('segments' => array());
        $CI = &get_instance();
        $business_email = $CI->session->userdata('business_email');
        
        $data['segments'] = $CI->uri->segment_array();
        $data['uri'] = $_SERVER['REQUEST_URI'];
        if (empty($business_email)) {
            if($_COOKIE['business_partner']=="Muthoot"){
                redirect("http://mymuthoot.com/#popup5");
            }
            
            /*$is_b2binsurance_found = strpos($_SERVER['REQUEST_URI'], 'b2binsurance');
            if ($is_b2binsurance_found == false) {
                redirect(getUrl('b2binsurance/'));
            }*/
        }
    }
}

if (!function_exists('GenerateTCPDF')) {
    function GenerateTCPDF($view_html, $pdf_title, $pdf_action, $pdf_name, $extra_paramter) {
        //echo '<pre>'; print_r($view_html);die('here');
        $ci = &get_instance();
        $ci->load->library('Tcpdf/Tcpdf.php');
        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setTitle($pdf_title);
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(7, 14, 6);
        $pdf->SetAutoPageBreak(TRUE, 0);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('helvetica', '', 8);
        $pdf->AddPage('L', array('format' => 'A4', 'Rotate' => $extra_paramter));
        $pdf->writeHTML($view_html, true, 0, true, 0, '');
        ob_clean();
         if($pdf_action=="F")
         {   
            $url=$extra_paramter['url'];
            $pdf = $pdf->Output($url, $pdf_action);
        }
        else{
            $pdf = $pdf->Output($pdf_name.'.pdf', $pdf_action);
        }
        return $pdf;
    }

}

if (!function_exists('getReferenceNumber')) {

    function getReferenceNumber($short_code,$id,$limit=10) {
        $reference_no = $short_code.str_pad($id, $limit, "0", STR_PAD_LEFT);
        return $reference_no;
    }
}

if (!function_exists('getActivePaymentMethodList')) {

    function getActivePaymentMethodList($ic_id) {
        $CI = &get_instance();
        $CI->load->model('common_model', 'commonMdl');
        $html  = $CI->commonMdl->getActivePaymentMethodList($ic_id);
        return $html;
    }

}

if (!function_exists('getHeaderMenu')) {

    function getHeaderMenu() {
        $CI = &get_instance();
        $home_url = getUrl('');
        $hospi_url = getUrl('muthoot-hospicash');
        $myaccount_url = getUrl('myaccount');
        $pos_registration = getUrl('giib/pos/landing.php');

        $html = <<<EOD
                                            
EOD;
        $logout_url = getUrl('logout');
        
        switch ($CI->session->userdata('customer_type_id')) {
            case 1:
                $html .= <<<EOD
                    <li><a href="{$home_url}">home</a></li>
                    <li><a href="{$myaccount_url}">My Account</a></li>
                    <li><a onclick="logout();" href="javascript:void">logout</a></li>
EOD;
                break;

            case 2:
                $html .= <<<EOD
                    <li><a href="{$home_url}">home</a></li>
                    <li><a href="{$pos_registration}">POS Enrollment</a></li>
                    <li><a onclick="logout();" href="javascript:void">logout</a></li>
EOD;
                break;

            case 4:
                $html .= <<<EOD
                    <li><a href="{$home_url}">home</a></li>
                    <li><a href="{$myaccount_url}">My Account</a></li>
                    <li><a onclick="logout();" href="javascript:void">logout</a></li>
EOD;
                break;

            default:
                $html .= <<<EOD
                    <li><a href="{$home_url}">home</a></li>
                    <li><a href="#login-popup" class="popup-with-move-anim login-btn">Login</a></li>
EOD;
        }
        return $html;
    }

}

if (!function_exists('getQuotationVehicleMenu')) {

    function getQuotationVehicleMenu($product_type_id) {
        $CI = &get_instance();
        $CI->load->model('Common_Model', 'Common_Model');
        $product_type_list = $CI->Common_Model->getProductTypeQuotationList();
        $html = <<<EOD
            <div class="row no-margin travel-booking padding-one ">
                <div class="tab-style3">
                    <ul class="nav nav-tabs nav-tabs-light text-center xs-no-border xs-no-margin-top no-margin">                         
EOD;
        foreach ($product_type_list as $product_type) {
            $active_class = ($product_type_id == $product_type['id']) ? ' active' : '';
            $url = getUrl($product_type['url']);
            $lable = $product_type['group_lable'];
            $html .= <<<EOD
                    <li class="no-margin-left xs-no-border {$active_class}"><a href="{$url}">{$lable}</a></li>                         
EOD;
        }
        $html .= <<<EOD
                            </ul>
                </div>
            </div>                       
EOD;
        return $html;
    }

}


if (!function_exists('generateQuoteForwardLink')) {

    function generateQuoteForwardLink($link = '') {
        if ($link) {
            $quote_forward_string = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $link;
            $quote_forward_string_shuffled = str_shuffle($quote_forward_string);
            return $quote_forward_link = substr($quote_forward_string_shuffled, 1, 15);
        } else {
            $quote_forward_string = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $quote_forward_string_shuffled = str_shuffle($quote_forward_string);
            return $quote_forward_link = substr($quote_forward_string_shuffled, 1, 15);
        }
    }

}

if (!function_exists('redirectToProductPage')) {

    function redirectToProductPage() {
        $product_type_id = '';
        $CI = &get_instance();
        if ($CI->session->userdata('product_type_id')) {
            $product_type_id = $CI->session->userdata('product_type_id');
        }
        // print_r($product_type_id);die;
        switch ($product_type_id) {
            case 1:
                redirect('quotation/privatecar');
                break;
            case 2:
                redirect('quotation/bike');
                break;
            case 3:
//                redirect('quotation/commercial');
                redirect('quotation/commercial_taxi');
                break;                
            default:
                redirect('');
                break;
        }
    }

}

if (!function_exists('send_otp')) {

    function send_otp($otp_mobile, $id) {
        $varname = 'buypolicy_otpfor_' . $id;
        //$otp_mobile = $this->input->post('otp_mobile');
        $otp = str_pad(mt_rand(0, 9999), 6, '0', STR_PAD_LEFT);


        $mmsg = "Your OTP is " . $otp . " to login to your account at mypolicynow.com";
        $msg = NULL;
        $from = "info@mypolicynow.com";
        $headers = "From:{$from}";
        $msg .= "Dear Sir/ Madam,\n\nYour OTP is  " . $otp . " to login  on www.mypolicynow.com.\nPlease enter your OTP using your login ID and password\nIf you did not submit this OTP or if you believe an unauthorised person has used your details, you should change your password as soon as possible from your login ID at https://www.mypolicynow.com\n\nSincerely,\nSupport team";
        $subject = "OTP to login at mypolicynow.com";



        //include("dependencies/smsapi.php");
        //include("dependencies/mail-function.php");
        $result = sendsms($otp_mobile, $mmsg);


        //$result = 'success';

        $_SESSION[$varname] = $otp;
        //sendmail($user_email, "OTP to login at mypolicynow.com", $msg, "");
        //echo $otp;
        return $result;
    }

}

if (!function_exists('sendSms')) {

    function sendSms($Mobilenumber, $Message) {
        error_reporting(1);
        $uid = "indicosmic";
        $pwd = "6610402";
        $sid = "MPOLNW";
        $method = "POST";
        $message = urlencode($Message);
        $CI = &get_instance();

//        $get_url = "https://login.bulksmsgateway.in/unicodesmsapi.php?username=indicosmic&password=7720676&mobilenumber=8108688880&message=testfromanil&senderid=MPOLNW&type=3";
        $get_url = "https://login.bulksmsgateway.in/unicodesmsapi.php?username=" . $uid . "&password=" . $pwd . "&mobilenumber=" . $Mobilenumber . "&message=" . $message . "&senderid=" . $sid . "&type=3";

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

        $result = httpGet($get_url);

        function old() {
            $ch = curl_init($get_url);

            $curlversion = curl_version();
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP ' . phpversion() . ' + Curl ' . $curlversion['version']);
            curl_setopt($ch, CURLOPT_REFERER, null);

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            if ($method == "POST") {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            } else {
                curl_setopt($ch, CURLOPT_POST, 0);
                curl_setopt($ch, CURLOPT_URL, $get_url);
            }

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            // DO NOT RETURN HTTP HEADERS
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // RETURN THE CONTENTS OF THE CALL
            $buffer = curl_exec($ch);
            $err = curl_errno($ch);
            $errmsg = curl_error($ch);
            $header = curl_getinfo($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $_SESSION['buffer'] = $err . "   " . $errmsg . "   " . $buffer;
            $_SESSION['curlURL'] = $get_url;
            curl_close($ch);
        }

        return $result;
    }

}


if (!function_exists('sendTVSRSAsms')) {
    function sendTVSRSAsms($Mobilenumber,$message,$link='') {

     // echo $Mobilenumber.'  '.$sold_policy_id;
        // echo $base_url.'<br>';
        $uid = 'di78-indicosm';
        $pwd = 'digimile';
        $sid = 'TVSRSA';
        $method = 'POST';
        $is_errror = 1;
        $Message = $message;
       // echo $Message,'<br>';
        $message_string = urlencode($Message);
        // k3digital media
        $get_url='http://sms.digimiles.in/bulksms/bulksms?username='.$uid.'&password='.$pwd.'&type=0&dlr=1&destination='.$Mobilenumber.'&source='.$sid.'&message='.$message_string.'';


// echo $get_url;die;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $get_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            $output = curl_exec($ch);
            if($errno = curl_errno($ch)) {
                $error_message = curl_strerror($errno);
                echo "cURL error ({$errno}):\n {$error_message}";
            }
           // curl_close($ch);
            if (curl_errno ( $ch )) {
            echo curl_error ( $ch );
            curl_close ( $ch );
            exit ();
        }
       // echo "<pre>"; print_r($output); echo "</pre>"; die('end of line yoyo');

        return $output;
    }
}


if (!function_exists('removeArrayKeyPrefix')) {

    function removeArrayKeyPrefix($key_str, array $input_array) {
        $return = array();
        foreach ($input_array as $key => $value) {
            if (strpos($key, $key_str) === 0)
                $key = substr($key, strlen($key_str));

            $return[$key] = $value;
        }
        return $return;
    }

}

if (!function_exists('roundTotal')) {

    function roundTotal($value) {
        if (isset($value) && !empty($value)) {
            return ceil($value);
        }
    }

}

if (!function_exists('roundValue')) {

    function roundValue($value) {
        if (isset($value) && !empty($value)) {
            return round($value, 2);
        }
    }

}

if (!function_exists('getDatatableGrid')) {

    function getDatatableGrid($colum_array, $sql, $js_edit_function_name, $js_delete_function_name) {
        $ci = & get_instance();
        $columns = $colum_array;
        $query = $ci->db->query($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $query->num_rows();
        $result = $query->result();
        $data = array();
        foreach ($result as $main) {
            $nestedData = array();
            foreach ($colum_array as $key => $value) {
                $nestedData[] = $main->$value;
            }
            $nestedData[] = '<i class="fa fa-trash-o delete_record" table="' . $js_delete_function_name . '" atr="' . $main->id . '" > </i>&nbsp;&nbsp;<a onClick="' . $js_edit_function_name . '(' . $main->id . ')" style="cursor:pointer"> <i class="fa fa-edit"></i></a>';
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

}

if (!function_exists('getCustomerAddress')) {

    // date should be in dmy format

    function getCustomerAddress($address) {
        $html = (isset($address['address1']) && !empty($address['address1'])) ? $address['address1'] : '';
        $html .= (isset($address['address2']) && !empty($address['address2'])) ? ", " . $address['address2'] : '';

        $html .= (isset($address['city']) && !empty($address['city'])) ? ", " . $address['city']['name'] : '';
        $html .= (isset($address['pincode']) && !empty($address['pincode'])) ? ", " . $address['pincode'] : '';
        $html .= (isset($address['state']) && !empty($address['state'])) ? ", " . $address['state']['name'] : '';
        return $html;
    }

}

if (!function_exists('getDateArray')) {

    // date should be in dmy format
    function getDateArray($string = NULL) {
        if ($string == NULL) {
            $string = date("d/m/Y H:i:s", now());
        }
        if (!preg_match("/\d{4}/", $string, $match)) {
            return null; //year must be in YYYY form
        }
        $string = str_replace("/", "-", $string);
        $year = intval($match[0]); //converting the year to integer
        if ($year >= 1970) {
            goto return_label;
        }
        if (stristr(PHP_OS, "WIN") && !stristr(PHP_OS, "DARWIN")) { //OS seems to be Windows, not Unix nor Mac
            $diff = 1975 - $year; //calculating the difference between 1975 and the year
            $new_year = $year + $diff; //year + diff = new_year will be for sure > 1970
            $new_date = date("d/m/Y H:i:s", strtotime(str_replace($year, $new_year, $string))); //replacing the year with the new_year, try strtotime, rendering the date
            $string = str_replace($new_year, $year, $new_date); //returning the date with the correct year
            goto return_label;
        }

        return_label:
        $date_array = date_parse(date("Y-m-d  H:i:s", strtotime($string)));
        $date_array['datetime'] = date("d/m/Y H:i:s", strtotime($string));
        $date_array['date'] = date("d/m/Y", strtotime($string));
        $date_array['time'] = date("H:i:s", strtotime($string));
        $date_array['date_format2'] = date("Y-m-d", strtotime($string));
        $date_array['datetime_format2'] = date("Y-m-d H:i:s", strtotime($string));
        return $date_array; //do normal strtotime
    }

}

if (!function_exists('addXDays')) {

    function addXDays($how_many_days, $datetime) {
        $date = new DateTime($datetime);
        $date->modify('+' . $how_many_days . ' day');
        return $date;
    }

}


if (!function_exists('subXDays')) {

    function subXDays($how_many_days, $datetime) {
        $date = new DateTime($datetime);
        $date->modify('-' . $how_many_days . ' day');
        return $date;
    }

}

if (!function_exists('isValidDateTimeString')) {

    function isValidDateTimeString($str_dt, $str_dateformat, $str_timezone) {
        $date = DateTime::createFromFormat($str_dateformat, $str_dt, new DateTimeZone($str_timezone));
        return $date && DateTime::getLastErrors()["warning_count"] == 0 && DateTime::getLastErrors()["error_count"] == 0;
    }

}

if (!function_exists('convertToBase64')) {

    function convertToBase64($path) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

}

if (!function_exists('getUrl')) {

    function getUrl($url = null) {
        return site_url($url);
    }

}

if (!function_exists('getJs')) {

    function getJs($script = null) {
        return '<script type="text/javascript" src="' . getUrl("assets/js/" . $script) . '"></script>';
    }

}

if (!function_exists('getJsUrl')) {

    function getJsUrl($script = null) {
        if (strstr($script, 'http')) {
            return $script;
        }
        return getUrl("assets/js/" . $script);
    }

}

if (!function_exists('getCss')) {

    function getCss($style) {
        return '<link rel="stylesheet" type="text/css"  href="' . getUrl("assets/css/" . $style) . '">';
    }

}

if (!function_exists('getCssUrl')) {

    function getCssUrl($style = null) {
        return getUrl("assets/css/" . $style);
    }

}

if (!function_exists('getImage')) {

    function getImage($image, $class = '', $id = '') {
        return ' <img class="' . $class . '" id="' . $id . '" src="' . getUrl("assets/images/" . $image) . '">';
    }

}

if (!function_exists('getImageUrl')) {

    function getImageUrl($image = null) {
        return getUrl("assets/images/" . $image);
    }

}
if (!function_exists('getUploadImageUrl')) {

    function getUploadImageUrl($image = null) {
        return getUrl("uploads/products/" . $image);
    }

}

if (!function_exists('isUserLoggedIn')) {
            function isUserLoggedIn() {
                $ci = & get_instance();
                $user_data = $ci->session->userdata();
                if(empty($user_data['user_session']) && empty($user_data['user_session']['dealer_code'])){
                   if (in_array($ci->router->class, array('Rsa_Dashboard','Rsa_cover','Tvs_Dealer'))) {
                        redirect('');
                    }
                }else{
                    if($ci->router->class == 'login'){
                        redirect('dashboard');
                    }           
                }
            }
        }


if (!function_exists('getMetaTags')) {

    function getMetaTags($meta_tags) {
        $meta_html = '';
        foreach ($meta_tags as $meta_key => $meta_value) {
            $meta_html .= '<meta name="' . $meta_key . '" content="' . $meta_value . '">' . "\n";
        }
        return $meta_html;
    }

}

if (!function_exists('addCss')) {

    function addCss($css) {
        $ci = &get_instance();
        if ($css)
            if (is_array($css))
                $ci->data['css_files'] = array_merge($ci->data['css_files'], $css);
            else
                $ci->data['css_files'][] = $css;
    }

}

if (!function_exists('addJs')) {

    function addJs($js) {
        $ci = &get_instance();
        if ($js)
            if (is_array($js))
                $ci->data['js_files'] = array_merge($ci->data['js_files'], $js);
            else
                $ci->data['js_files'][] = $js;
    }

}


if (!function_exists('loadCss')) {

    function loadCss() {
        $ci = &get_instance();
        $html = '';
        foreach ($ci->data['css_files'] as $value) {
            $html .= '<link rel="stylesheet" type="text/css"  href="' . getCssUrl($value) . '">' . "\n";
        }
        echo $html;
    }

}

if (!function_exists('loadJs')) {

    function loadJs() {
        $ci = &get_instance();
        $html = '';
        foreach ($ci->data['js_files'] as $value) {
            $html .= '<script type="text/javascript" src="' . getJsUrl($value) . '"></script>' . "\n";
        }
        echo $html;
    }

}

if (!function_exists('showDateYMD')) {

    function showDateYMD($date_str) {
        $date_obj = new DateTime($date_str);
        $date = $date_obj->format('Y-m-d h:m:s');
        return $date;
    }

}

if (!function_exists('showDateDMY')) {

    function showDateDMY($date_str) {
        $date_obj = new DateTime($date_str);
        $date = $date_obj->format('d/m/Y h:m:s');
        return $date;
    }

}

if (!function_exists('filterArrayBykeyVal')) {

    function filterArrayBykeyVal($array, $key, $val) {
        $result = array_filter($array, function ($var) use ($key, $val) {
            return ($var[$key] == $val);
        });

        return array_pop($result);
    }

}

if (!function_exists('getTodayDate')) {

    function getTodayDate() {
        $CI = & get_instance();
        // $CI->load->model('common_model', 'commonMdl');
        // $result = $CI->commonMdl->getTodayDate();
        return $result;
    }

}
if (!function_exists('getTomorrowDate')) {

    function getTomorrowDate() {
        $datetime = new DateTime('tomorrow');
        $result = $datetime->format('d-m-Y');
        return $result;
    }

}
if (!function_exists('getDiffDays')) {

    function getDiffDays($start_date, $end_date) {

        $datetime1 = new DateTime(str_replace('/', '-', $start_date));

        $datetime2 = new DateTime(str_replace('/', '-', $end_date));

        $difference = $datetime1->diff($datetime2);

        return $difference;
    }

}
if (!function_exists('getDobByAge')) {

    function getDobByAge($age) {
        $currentData = date("Y-m-d");
        $dob = date('d-m-Y', strtotime($currentData . '-' . $age . ' Year'));
        return $dob;
    }

}
if (!function_exists('emptyCheck')) {

    function emptyCheck($var) {

        if (isset($var)) {
            return false;
        }

        if (!isset($var) || empty($var) || is_null($var)) {
            return true;
        }


        return false;
    }

}

if (!function_exists('checkLoginType')) {

    function checkLoginType() {
        $html = '';
        if (!isset($_SESSION['user_type'])) {
            $html .= <<<EOT
                    <li class="login md-trigger" data-modal="sign-in-popup"><i class="fa fa-lock" style="font-size: 19px;color: #1b88cc;"></i> &nbsp;Login</li>
EOT;
            goto loginend;
        }

        $username = strtoupper($_SESSION['user_name']);
        $dashboard = 'Dashboard'; //<?php echo $common_info['dashboard']
        $logout = 'Logout'; //<?php echo $common_info['logout'] 
        $base_url = base_url();

        $html .= <<<EOD
                    <li><a href="#"><i class="fa fa-user" aria-hidden="true" style="font-size: 19px;color: #1b88cc;"></i>&nbsp;&nbsp; Welcome {$username}</a>&nbsp;&nbsp;<i class="fa fa-caret-down"  style="font-size: 19px;color: #1b88cc;"></i>
                         <ul class="submenu animated speed fadeInDown">
                            
EOD;
        switch ($_SESSION['user_type']) {
            case 1:
                $html .= <<<EOD
                    <li><a href="{$base_url}myaccount_dashboard"><i class="fa fa-tachometer" aria-hidden="true" ></i>&nbsp;&nbsp; {$dashboard}</a></li>
EOD;
                break;
            case 2:
                $html .= <<<EOD
                    <li><a href="{$base_url}myaccount_dashboard"><i class="fa fa-tachometer" aria-hidden="true" ></i>{$dashboard}</a></li>
EOD;
                break;
            case 3:
                $html .= <<<EOD
                    <li><a href="{$base_url}myaccount_dashboard"><i class="fa fa-tachometer" aria-hidden="true" ></i>&nbsp;&nbsp; {$dashboard}</a></li>
EOD;
                break;
            case 4:
                $html .= <<<EOD
                    <li><a href="{$base_url}myaccount_dashboard"><i class="fa fa-tachometer" aria-hidden="true" ></i>&nbsp;&nbsp; {$dashboard}</a></li>
EOD;
                break;
            case 5:
                $html .= <<<EOD
                    <li><a href="{$base_url}myaccount_dashboard"><i class="fa fa-tachometer" aria-hidden="true" ></i>&nbsp;&nbsp; {$dashboard}</a></li>
EOD;
                break;
            case 6:
                $html .= <<<EOD
                    <li><a href="{$base_url}myaccount_dashboard"><i class="fa fa-tachometer" aria-hidden="true" ></i>&nbsp;&nbsp; {$dashboard}</a></li>;
EOD;
                break;
        }
        $html .= <<<EOD
            <li id= 'logout'><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;&nbsp;{$logout}</li></ul></li>
EOD;
        loginend:
        return $html;
    }

}

if (!function_exists('array_to_csv')) {

    function array_to_csv($array, $download = "") {
        if ($download != "") {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="' . $download . '"');
        }

        ob_start();
        $f = fopen('php://output', 'w') or show_error("Can't open php://output");
        $n = 0;
        foreach ($array as $line) {
            $n++;
            if (!fputcsv($f, $line)) {
                show_error("Can't write line $n: $line");
            }
        }
        fclose($f) or show_error("Can't close php://output");
        $str = ob_get_contents();
        ob_end_clean();

        if ($download == "") {
            return $str;
        } else {
            echo $str;
        }
    }

}

if (!function_exists('getDatatableGridAdmin')) {

    function getDatatableGridAdmin($colum_array, $sql, $js_edit_function_name, $js_delete_function_name) {

        $ci = & get_instance();
        $columns = $colum_array;
        $query = $ci->db->query($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $query->num_rows();
        $result = $query->result();
        $data = array();
        foreach ($result as $main) {
            $nestedData = array();
            foreach ($colum_array as $key => $value) {
                $nestedData[] = $main->$value;
            }
            $nestedData[] = '<input type="checkbox" name="cid[]" class="checkbox1" id="cid[]" value=' . $main->id . '>';
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval(0),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

}







//######################################################################################################################
