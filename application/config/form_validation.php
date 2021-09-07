<?php

$config = array(
//before sold//
    'tvs_rsa_form' => array(
        array(
            'field' => 'engine_no',
            'label' => 'Engine No',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'chassis_no',
            'label' => 'Chassis No',
            'rules' => 'required|trim|exact_length[17]'
        ),
        array(
            'field' => 'model_id',
            'label' => 'Model',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'rto_name',
            'label' => 'Rto Name',
            'rules' => 'required|trim|alpha'
        ),
        array(
            'field' => 'rto_code1',
            'label' => 'Rto Code1',
            'rules' => 'required|trim|numeric|exact_length[2]'
        ),
        array(
            'field' => 'first_name',
            'label' => 'First Name',
            'rules' => 'required|trim|alpha'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last Name',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|trim|valid_email'
        ),
        array(
            'field' => 'mobile_no',
            'label' => 'Mobile No',
            'rules' => 'required|trim|numeric'
        ),
        array(
            'field' => 'cust_addr1',
            'label' => 'Address1',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'cust_addr2',
            'label' => 'Address2',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'pin',
            'label' => 'Pincode',
            'rules' => 'required|trim|numeric|exact_length[6]'
        ),
        array(
            'field' => 'state',
            'label' => 'State',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'city',
            'label' => 'City',
            'rules' => 'required|trim'
        )
    ),
    'only_rsa_form' => array(
        array(
            'field' => 'engine_no',
            'label' => 'Engine No',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'chassis_no',
            'label' => 'Chassis No',
            'rules' => 'required|trim|exact_length[17]'
        ),
        array(
            'field' => 'model_id',
            'label' => 'Model',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'rto_name',
            'label' => 'Rto Name',
            'rules' => 'required|trim|alpha'
        ),
        array(
            'field' => 'rto_code1',
            'label' => 'Rto Code1',
            'rules' => 'required|trim|numeric|exact_length[2]'
        ),
        array(
            'field' => 'first_name',
            'label' => 'First Name',
            'rules' => 'required|trim|alpha'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last Name',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'gender',
            'label' => 'Gender',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|valid_email'
        ),
        array(
            'field' => 'mobile_no',
            'label' => 'Mobile No',
            'rules' => 'required|trim|numeric'
        ),
        array(
            'field' => 'cust_addr1',
            'label' => 'Address1',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'cust_addr2',
            'label' => 'Address2',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'pin',
            'label' => 'Pincode',
            'rules' => 'required|trim|numeric|exact_length[6]'
        ),
        array(
            'field' => 'state',
            'label' => 'State',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'city',
            'label' => 'City',
            'rules' => 'required|trim'
        )
    ),
    'rr310_rsa_form' => array(
        array(
            'field' => 'engine_no',
            'label' => 'Engine No',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'chassis_no',
            'label' => 'Chassis No',
            'rules' => 'required|trim|exact_length[17]'
        ),
        array(
            'field' => 'model_id',
            'label' => 'Model',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'first_name',
            'label' => 'First Name',
            'rules' => 'required|trim|alpha'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last Name',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|valid_email'
        ),
        array(
            'field' => 'mobile_no',
            'label' => 'Mobile No',
            'rules' => 'required|trim|numeric'
        ),
        array(
            'field' => 'pin',
            'label' => 'Pincode',
            'rules' => 'required|trim|numeric|exact_length[6]'
        ),
        array(
            'field' => 'state',
            'label' => 'State',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'city',
            'label' => 'City',
            'rules' => 'required|trim'
        )
    ),
//after sold on endrosement//
    'tvs_rsa_endrosementform' => array(
        array(
            'field' => 'model_id',
            'label' => 'Model',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'rto_name',
            'label' => 'Rto Name',
            'rules' => 'required|trim|alpha|exact_length[2]'
        ),
        array(
            'field' => 'rto_code1',
            'label' => 'Rto Code1',
            'rules' => 'required|trim|numeric|exact_length[2]'
        ),
        array(
            'field' => 'first_name',
            'label' => 'First Name',
            'rules' => 'required|trim|alpha'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last Name',
            'rules' => 'required|trim|alpha'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|trim|valid_email'
        ),
        array(
            'field' => 'mobile_no',
            'label' => 'Mobile No',
            'rules' => 'required|trim|numeric'
        ),
        array(
            'field' => 'cust_addr1',
            'label' => 'Address1',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'cust_addr2',
            'label' => 'Address2',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'pin',
            'label' => 'Pincode',
            'rules' => 'required|trim|numeric|exact_length[6]'
        ),
        array(
            'field' => 'state',
            'label' => 'State',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'city',
            'label' => 'City',
            'rules' => 'required|trim'
        )
    ),
//dealer form
    'tvs_dealer_form' => array(
        array(
            'field' => 'dealer_full_name',
            'label' => 'Full Name',
            'rules' => 'required|trim|regex_match[/^[a-zA-Z\s]+$/]'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|trim|valid_email'
        ),
        array(
            'field' => 'company_name',
            'label' => 'Company Name',
            'rules' => 'required|trim|regex_match[/^[a-zA-Z\s]+$/]'
        ),
        array(
            'field' => 'mobile_no',
            'label' => 'Mobile No',
            'rules' => 'required|trim|numeric|regex_match[/^[0-9]{10}$/]'
        ),
//        array(
//            'field' => 'phone_no',
//            'label' => 'Phone No',
//            'rules' => 'required|trim|numeric|regex_match[/^[0-9]{11}$/]'
//        ),
        array(
            'field' => 'gst_no',
            'label' => 'GST No',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'pan_no',
            'label' => 'Pan No',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'dealer_addr1',
            'label' => 'Address1',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'dealer_addr2',
            'label' => 'Address2',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'pin',
            'label' => 'Pincode',
            'rules' => 'required|trim|numeric|exact_length[6]|regex_match[/^[0-9]{6,}$/]'
        ),
        array(
            'field' => 'state',
            'label' => 'State',
            'rules' => 'required|trim|regex_match[/^[a-zA-Z\s]+$/]'
        ),
        array(
            'field' => 'city',
            'label' => 'City',
            'rules' => 'required|trim|regex_match[/^[a-zA-Z\s]+$/]'
        ),
        array(
            'field' => 'bank_name',
            'label' => 'Bank Name',
            'rules' => 'required|trim|regex_match[/^[a-zA-Z\s]+$/]'
        ),
        array(
            'field' => 'acc_holder_name',
            'label' => 'Acount Holder Name',
            'rules' => 'required|trim|regex_match[/^[a-zA-Z\s]+$/]'
        ),
        array(
            'field' => 'account_no',
            'label' => 'Acount No',
            'rules' => 'required|trim|numeric'
        ),
        array(
            'field' => 'ifsc_code',
            'label' => 'IFSC Code',
            'rules' => 'required|trim'
        )
    ),
    'dealer_uploads_document' => array(
        array(
            'field' => 'agreement_pdf',
            'label' => 'Agreement Upload',
            'rules' => 'required|trim'
        ),
        // array(
        // 'field' => 'adharcard',
        // 'label' => 'Adhar Card',
        // 'rules' => 'required|trim'
        // ),
        array(
            'field' => 'pancard',
            'label' => 'Pan Card',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'cancel_cheque',
            'label' => 'Cancel Cheque',
            'rules' => 'required|trim'
        ),
    )
);
?>