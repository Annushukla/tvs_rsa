<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	http://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |	$route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |		my-controller/my-method	-> my_controller/my_method
 */

$route['global_login'] = 'Login/global_login';
$route['check_exist_engineno'] = 'front/myaccount/Rsa_cover/CheckExistEngineNo';
$route['CheckExistFrameNo'] = 'front/myaccount/Rsa_cover/CheckExistFrameNo';
$route['checkDuplicateEntries'] = 'front/myaccount/Rsa_cover/checkDuplicateEntries';
$route['checkIsAvailableForWorkshopPolicy'] = 'front/myaccount/Rsa_cover/checkIsAvailableForWorkshopPolicy';
$route['fetch_model'] = 'front/myaccount/Rsa_cover/fetch_model';
$route['get_cities_list'] = 'front/myaccount/Rsa_cover/get_cities_list';
$route['get_Plan_Details'] = 'front/myaccount/Rsa_cover/get_Plan_Details';
$route['renewalPolicyAjax'] = 'front/myaccount/Rsa_cover/renewalPolicyAjax';
$route['renew_policy/(:num)'] = 'front/myaccount/Rsa_cover/renewPolicy/$1';
$route['rsaPaRenewalSendSms/(:num)'] = 'front/myaccount/Rsa_cover/rsaPaRenewalSendSms/$1';
$route['renewRsaPaPolicy/(:num)'] = 'front/myaccount/Rsa_cover/renewRsaPaPolicy/$1';

$route['check_exist_engineno_rr310'] = 'front/myaccount/Rsa_RR310cover/CheckExistEngineNo_RR310';
$route['rr_310/check_exist_engineno_rr310'] = 'front/myaccount/rr_310/CheckExistEngineNo_RR310';
$route['fetch_model_rr310'] = 'front/myaccount/Rsa_RR310cover/fetch_model_RR310';

// $route['default_controller'] = 'dashboard';
// //logout///
//............PA COVER.......//

$route['Rr_310'] = 'front/myaccount/Rr_310/index';
$route['disclaimer'] = 'front/myaccount/Rr_310/disclaimer';
$route['rr_310_login'] = 'front/myaccount/Rr_310/rr_310_login';
$route['rr_310_policy'] = 'front/myaccount/Rr_310/rr_310_policy';
$route['rr_310_generate_policy'] = 'front/myaccount/Rr_310/rr310GeneratePolicy';
$route['generate_policy'] = 'front/myaccount/Rsa_cover/index';

$route['generate_policy/(:num)'] = 'front/myaccount/Rsa_cover/index/$1';
$route['SaveImportData'] = 'RR310Import/SaveImportData';

$route['admin/rr310_generate_policy_script'] = 'RR310Import/rr310GeneratePolicyScript';
$route['admin/RR310Import'] = 'RR310Import/index';
//$route['checkPayment'] = 'front/myaccount/Rr_310/checkPayment';

$route['checkPayment/(:any)/(:any)'] = 'front/myaccount/Rr_310/checkPayment/$1/$2';

$route['paymentSuccess'] = 'front/myaccount/Rr_310/paymentSuccess';
$route['paymentFail'] = 'front/myaccount/Rr_310/paymentFail';
$route['paymentResponse/(:any)/(:any)'] = 'front/myaccount/Rr_310/paymentResponse/$1/$2';

$route['generate_policy_rr310'] = 'front/myaccount/Rsa_RR310cover/index';
$route['only_rsa_generate_policy'] = 'front/myaccount/Rsa_RR310cover/onlyRsaGeneratePolicy';
$route['download_rsa_pdf/(:num)'] = 'front/myaccount/Rsa_RR310cover/DownloadOnlyRsaPolicy/$1';
$route['download_rr_310_pdf/(:num)'] = 'front/myaccount/Rr_310/DownloadOnlyRsaPolicy/$1';
$route['generate_policy/(:any)'] = 'front/myaccount/Rsa_cover/index/$1';
$route['isAllowedToPunchPolicy'] = 'front/myaccount/Rsa_cover/isAllowedToPunchPolicy';
$route['generated_policy_data'] = 'front/myaccount/Rsa_cover/GeneratedPolicyData';
$route['SoldPolicyPdf'] = 'front/myaccount/Rsa_cover/SoldPolicyPdf';
$route['SoldPolicyPdf/(:any)'] = 'front/myaccount/Rsa_cover/SoldPolicyPdf/$1';


///*************Workshop policy **********
$route['generate_policy_workshop'] = 'front/myaccount/Rsa_workshop/index/';
$route['generate_rsa_workshop_policy'] = 'front/myaccount/Rsa_workshop/GenerateWorkshopPolicy/';
$route['planDetailsWorkshop'] = 'front/myaccount/Rsa_workshop/planDetailsWorkshop';

///*************Renew Only RSA Policy **********

$route['renew_only_rsa'] = 'front/myaccount/renew_only_rsa/index/';
$route['checkIsAvailableForOnlyRSAPolicy'] = 'front/myaccount/renew_only_rsa/checkIsAvailableForOnlyRSAPolicy/';
$route['submit_renew_only_rsa_policy'] = 'front/myaccount/renew_only_rsa/SubmitRenewOnlyRsaPolicy/';
$route['OnlyRsapdfdata/(:num)'] = 'front/myaccount/renew_only_rsa/OnlyRsapdfdata/$1';


// ***************TVSRSA API Generate Policy********

$route['BuyRenewRSAPolicy/(:num)'] = 'front/myaccount/BuyRSApolicy/BuyRenewRSAPolicy/$1';
$route['submit_renewal_policy_data'] = 'front/myaccount/BuyRSApolicy/submit_renewal_policy_data';

$route['BuynowRSApolicy/(:num)'] = 'front/myaccount/BuyRSApolicy/BuynowRSApolicy/$1';
$route['submit_pending_policy_data'] = 'front/myaccount/BuyRSApolicy/submit_pending_policy_data';
$route['rsa_paymentResponse/(:any)/(:any)/(:any)'] = 'front/myaccount/BuyRSApolicy/paymentResponse/$1/$2/$3';

// $route['admin/BuynowRSApolicy/(:num)'] = 'BuyRSApolicy/BuynowRSApolicy/$1';
// $route['admin/TVSPendingPolicyByAPI'] = 'TVSRSA_API/TVSPendingPolicyByAPI_post';

/////////TVSRSA RENEWAL //////////////

$route['Renewal'] = 'front/myaccount/TvsRsa_Renewal/index';


$route['ICICI_Pdf'] = 'front/myaccount/Rsa_cover/ICICI_Pdf';
$route['ICICI_Pdf/(:any)'] = 'front/myaccount/Rsa_cover/ICICI_Pdf/$1';
$route['bhartiPdf/(:any)'] = 'front/myaccount/Rsa_cover/bhartiPdf/$1';
$route['tataPdf/(:num)'] = 'front/myaccount/Rsa_cover/tataPdf/$1';
$route['MailSoldPolicyPdf/(:num)/(:num)'] = 'front/myaccount/Rsa_cover/MailSoldPolicyPdf/$1/$2';

$route['update_tvs_payment_details'] = 'front/myaccount/Rsa_cover/update_tvs_payment_details';

$route['cancelation_rejected_policy'] = 'front/myaccount/Rsa_cover/RejectedCancelationPolicies';
$route['RejectCancelPolicyAjax'] = 'front/myaccount/Rsa_cover/RejectCancelPolicyAjax';
$route['Cancelled_list'] = 'front/myaccount/Rsa_cover/Cancelled_list';
$route['CancelledPolicyListAjax'] = 'front/myaccount/Rsa_cover/CancelledPolicyListAjax';
$route['cancelPolicy'] = 'front/myaccount/Rsa_cover/canclePolicy';
$route['cancleRsaPolicyAjax'] = 'front/myaccount/Rsa_cover/cancleRsaPolicyAjax';
$route['requestCancelPolicy'] = 'front/myaccount/Rsa_cover/requestCancelPolicy';
$route['confirm_rsa_cancelation'] = 'front/myaccount/Rsa_cover/confirm_rsa_cancelation';
$route['sold_rsa_policy'] = 'front/myaccount/Rsa_cover/SoldPAPolicy';
$route['checkIsPolicyExist'] = 'front/myaccount/Rsa_cover/checkIsPolicyExist';
$route['sold_pa_policy_ajax'] = 'front/myaccount/Rsa_cover/SoldPaPolicyAjax';
$route['fetchStateCityNames'] = 'front/myaccount/Rsa_cover/fetchStateCity';
$route['renewal_policy'] = 'front/myaccount/Rsa_cover/renewalPolicy';
$route['DealerExpiredPolicyAjax'] = 'front/myaccount/Rsa_cover/DealerExpiredPolicyAjax';
$route['expired_policies'] = 'front/myaccount/Rsa_cover/ExpiredPolicies';
$route['rr_310/fetchStateCityNames'] = 'front/myaccount/rr_310/fetchStateCity';
$route['planDetails'] = 'front/myaccount/Rsa_cover/planDetails';
$route['planDetailsForRR310'] = 'front/myaccount/Rsa_RR310cover/planDetailsForRR310';
$route['submitloginform'] = 'login/SubmitLoginForm';
$route['dealer_logout'] = 'login/DealerLogout';
$route['logout'] = 'login/DealerLogout';
$route['fetch_location'] = 'Rsa_Dashboard/fetchLocation';

$route['getDealerInvoice'] = 'Rsa_Dashboard/getDealerInvoice';
$route['post_invoice_data'] = 'Rsa_Dashboard/post_invoice_data';
$route['generate_invoice'] = 'Rsa_Dashboard/generateInvoice';
$route['generated_invoice'] = 'Rsa_Dashboard/GeneratedInvoiceList';
$route['InvoiceListAjax'] = 'Rsa_Dashboard/InvoiceListAjax';
$route['invoice_pdf/(:any)'] = 'Rsa_Dashboard/invoice_pdf/$1';

$route['submitDealerCampaignList'] = 'Rsa_Dashboard/submitDealerCampaignList';

$route['myDashboardSection'] = 'Rsa_Dashboard/myDashboardSection';
$route['myDashboardSection/(:any)'] = 'Rsa_Dashboard/myDashboardSection/$1';
$route['getPolicyPayout'] = 'Rsa_Dashboard/getPolicyPayout';
$route['gst_transanction'] = 'Rsa_Dashboard/GstTransanction';
$route['UploadGstComplaintData'] = 'Rsa_Dashboard/UploadGstComplaintData';

// $route['download_kotak_pdf/(:any)'] = 'Rsa_Dashboard/DownloadKotakPdf/$1';
// $route['download_il_pdf/(:any)'] = 'Rsa_Dashboard/DownloadILPdf/$1';
$route['OICL_pdf/(:any)'] = 'front/myaccount/Rsa_cover/DownloadOrientalPdf/$1'; 
// $route['download_tata_pdf/(:any)'] = 'Rsa_Dashboard/DownloadTataPdf/$1';
// $route['download_bagi_pdf/(:any)'] = 'Rsa_Dashboard/DownloadBagiPDF/$1';
// $route['download_pa_policy/(:any)'] = 'Rsa_Dashboard/DownloadPolicy/$1';
// $route['download_bharti_policy/(:any)'] = 'Rsa_Dashboard/DownloadBhartiPolicy/$1';
// $route['download_tata_policy/(:any)'] = 'Rsa_Dashboard/DownloadTataPolicy/$1';
// $route['download_icici_policy/(:any)'] = 'Rsa_Dashboard/ICICI_Pdf/$1';

$route['SubmitConfirmOriental'] = 'Rsa_Dashboard/SubmitConfirmOriental';
$route['DownloadKotakFullPolicy/(:any)'] = 'front/myaccount/Rsa_cover/DownloadKotakFullPolicy/$1';
$route['download_kotak_lite_pdf/(:any)'] = 'Rsa_Dashboard/DownloadKotakLitePdf/$1';
$route['download_il_lite_pdf/(:any)'] = 'Rsa_Dashboard/DownloadILLitePdf/$1';
$route['download_OICL_pdf/(:any)'] = 'Rsa_Dashboard/DownloadOrientalPdf/$1';
$route['download_tata_lite_pdf/(:any)'] = 'Rsa_Dashboard/DownloadTataLitePdf/$1';
$route['download_bagi_lite_pdf/(:any)'] = 'Rsa_Dashboard/DownloadBagiLitePDF/$1';
$route['download_kotak_full_policy/(:any)'] = 'Rsa_Dashboard/DownloadKotakFullPolicy/$1';
$route['download_bhartiaxa_full_policy/(:any)'] = 'Rsa_Dashboard/DownloadBhartiFullPolicy/$1';
$route['download_tata_full_policy/(:any)'] = 'Rsa_Dashboard/DownloadTataFullPolicy/$1';
$route['download_icici_full_policy/(:any)'] = 'Rsa_Dashboard/ICICI_full_Pdf/$1';
$route['download_liberty_policy/(:any)'] = 'Rsa_Dashboard/LibertyGeneral/$1';
$route['download_reliance_policy/(:any)'] = 'Rsa_Dashboard/ReliancePDF/$1';
$route['download_hdfc_policy/(:any)'] = 'Rsa_Dashboard/HDFCPDF/$1';
$route['download_workshop_OICL_pdf/(:any)'] = 'Rsa_Dashboard/DownloadWorkshopOICLPdf/$1';


$route['download_opnrsa_kotak_lite_pdf/(:any)'] = 'Rsa_Dashboard/OpenRSAKotakPDF/$1';
$route['download_apology_letter_pdf/(:any)/(:any)'] = 'Rsa_Dashboard/ApologyLetterPDF/$1/$1';
$route['download_serialwise_apology_letter_pdf/(:num)'] = 'Rsa_Dashboard/SerialwiseApologyLetterPDF/$1';

$route['generate-pay-ing-slip'] = 'front/myaccount/Rsa_cover/generate_pay_ing_slip';
$route['view_pdf_policy_data'] = 'front/myaccount/Rsa_cover/view_pdf_policy_data';
$route['payslip_insert_data'] = 'front/myaccount/Rsa_cover/payslip_insert_data';
$route['view_pdf_paying_slip/(:any)'] = 'front/myaccount/Rsa_cover/view_pdf_paying_slip/$1';
$route['Paying_slip_policy_ajax'] = 'front/myaccount/Rsa_cover/PayingSlipjax';
$route['count_amount_ajax'] = 'front/myaccount/Rsa_cover/count_amount_ajax';
$route['view_generated_payslip'] = 'front/myaccount/Rsa_cover/view_generated_payslip';
$route['view_generated_payslip_ajax'] = 'front/myaccount/Rsa_cover/view_generated_payslip_ajax';
$route['biz_users'] = 'front/myaccount/Rsa_cover/BizUsers';
$route['faq_generate_invoice'] = 'front/myaccount/Rsa_cover/FaqGenerateInvoice';


//...........RSA Admin..............//

$route['admin'] = 'Rsa_admin/index';
$route['admin/feed-file/(:num)'] = 'Report/rsa_feedfile/$1';
$route['admin/feed-file'] = 'Report/rsa_feedfile';
$route['admin/feed_file'] = 'Report/feedfile';
$route['admin/postal_ad_card'] = 'Report/postalAdCard';

$route['admin/kotak_openrsa_feedfile/(:num)'] = 'Report/kotak_openrsa_feedfile/$1';

$route['admin/feed_file/(:any)'] = 'Report/feedfile/$1';
$route['admin/cover_oriental_feedfile/(:any)'] = 'Report/cover_oriental_feedfile/$1';
$route['admin/level_oriental_feedfile/(:any)'] = 'Report/level_oriental_feedfile/$1';
$route['admin/oriental_canceled_feedfile/(:any)'] = 'Report/oriental_canceled_feedfile/$1';
$route['admin/icici_feedfile/(:any)'] = 'Report/icici_feedfile/$1';
$route['admin/icici_endorse_feedfile/(:any)'] = 'Report/icici_endorse_feedfile/$1';
$route['admin/icici_canceled_feedfile/(:any)'] = 'Report/icici_canceled_feedfile/$1';

$route['admin/hdfc_feedfile/(:any)'] = 'Report/hdfc_feedfile/$1';
$route['admin/hdfc_endorse_feedfile/(:any)'] = 'Report/hdfc_endorse_feedfile/$1';
$route['admin/hdfc_canceled_feedfile/(:any)'] = 'Report/hdfc_canceled_feedfile/$1';

$route['admin/download_rsa_feedfile/(:any)/(:any)/(:any)'] = 'Report/download_rsa_feedfile/$1/$2/$3';

$route['admin/download_kotakopenrsa_feedfile/(:any)/(:any)/(:any)'] = 'Report/download_kotakopenrsa_feedfile/$1/$2/$3';

$route['admin/bharti_axa_feedfile/(:any)'] = 'Report/bharti_axa_feedfile/$1';
$route['admin/bhartiaxa_endorse_feedfile/(:any)'] = 'Report/bhartiaxa_endorse_feedfile/$1';
$route['admin/bhartiaxa_canceled_feedfile/(:any)'] = 'Report/bhartiaxa_canceled_feedfile/$1';
$route['admin/liberty_general_feedfile/(:any)'] = 'Report/LibertyGeneralFeedfile/$1';
$route['admin/liberty_general_endorsed_feedfile/(:any)'] = 'Report/liberty_endorse_feedfile/$1';
$route['admin/liberty_general_canceled_feedfile/(:any)'] = 'Report/liberty_canceled_feedfile/$1';

$route['admin/tata_feedfile/(:any)'] = 'Report/TataAigFeedfile/$1';
$route['admin/tata_endorse_feedfile/(:any)'] = 'Report/tata_endorse_feedfile/$1';
$route['admin/tata_canceled_feedfile/(:any)'] = 'Report/tata_canceled_feedfile/$1';
$route['admin/reliance_general_feedfile/(:any)'] = 'Report/RelianceGeneralFeedfile/$1';
$route['admin/reliance_general_endorsed_feedfile/(:any)'] = 'Report/reliance_endorse_feedfile/$1';
$route['admin/reliance_general_canceled_feedfile/(:any)'] = 'Report/reliance_canceled_feedfile/$1';

$route['admin/tata_opnrsa_feedfile/(:any)'] = 'Report/TataOpnrsaFeedfile/$1';
$route['admin/tata_opnrsa_endorse_feedfile/(:any)'] = 'Report/TataOpnrsaEndorseFeedfile/$1';
$route['admin/tata_opnrsa_canceled_feedfile/(:any)'] = 'Report/TataOpnrsaCanceledFeedfile/$1';

$route['admin/pa_endorse_feedfile/(:any)'] = 'Report/PAEndorseFeedFile/$1';
$route['admin/bharti_endorse_feedfile/(:any)'] = 'Report/bharti_endorse_feedfile/$1';
$route['admin/pa_canceled_feedfile/(:any)'] = 'Report/pa_canceled_feedfile/$1';
$route['admin/bharti_canceled_feedfile/(:any)'] = 'Report/bharti_canceled_feedfile/$1';
$route['admin/mytvs_endorse_feedfile/(:any)'] = 'Report/mytvs_endorse_feedfile/$1';
$route['admin/mytvs_canceled_feedfile/(:any)'] = 'Report/mytvs_canceled_feedfile/$1';

$route['admin/model_list'] = 'Report/ModelList';
$route['admin/fetchByPolicyNo'] = 'Report/fetchByPolicyNo';
$route['admin/fetchPolicydata'] = 'Report/fetchPolicydata';
$route['admin/submit_model_data'] = 'Report/SubmitModelData';
$route['admin/pincode_list'] = 'Report/PincodeList';
$route['admin/submit_pincode_data'] = 'Report/SubmitPincodeData';
$route['admin/edit_pincode_data'] = 'Report/UpdatePincodeData';

$route['admin/cancel_policies'] = 'Report/cancelPolicies';
$route['admin/cancel_policies/(:any)'] = 'Report/cancelPolicies/$1';
$route['admin/cancel_policies_ajax'] = 'Report/cancelPoliciesAjax';
$route['admin/approveRsaCancellation'] = 'Report/approveRsaCancellation';
$route['admin/rejectRsaCancellation'] = 'Report/rejectRsaCancellation';
$route['admin/RequestcancelpolicyByAdmin'] = 'Report/RequestcancelpolicyByAdmin';

$route['admin/policies_for_kotak'] = 'Report/PolicyforKotak';
$route['admin/endorsement_policy'] = 'Report/EndorsementPolicy';
$route['admin/tvs_policies'] = 'Report/TvsPolicies';
$route['admin/bharti_feedfile/(:num)'] = 'Report/BhartiFeedfile/$1';
$route['admin/bharti_policies'] = 'Report/BajajPolicies';
$route['admin/testDataTable'] = 'Report/testDataTable';
$route['admin/endorse_by_admin/(:num)'] = 'Report/EdorsePolicyBYAdmin/$1';
$route['admin/post_endorsement_data'] = 'Report/PostEndorsementData';
$route['admin/fetchStateCityBypincode'] = 'Report/fetchStateCityBypincode';
$route['admin/orientalReports/(:num)'] = 'Report/orientalReports/$1';
$route['admin/specialReportsAjax'] = 'Report/specialReportsAjax';
$route['admin/specialReportsAjax/(:any)/(:any)/(:any)'] = 'Report/specialReportsAjax/$1/$2/$3';
$route['admin/SapphirePolicytill30Aug'] = 'Report/SapphirePolicytill30Aug';
$route['admin/SapphirePolicytill30AugDownloaded'] = 'Report/SapphirePolicytill30AugDownloaded';
$route['admin/updateDownloadStatus'] = 'Report/updateDownloadStatus';
$route['admin/serial_no_of_apology_letter'] = 'Report/serial_no_of_apology_letter';

//...........Admin approve..............//
$route['admin/admin_dealer_approve'] = 'Dealer_Approve/approvedDealer';
$route['admin/admin_dealer_approve/(:any)'] = 'Dealer_Approve/approvedDealer/$1';
$route['admin/admin_dealer_password_changes'] = 'Dealer_Approve/admin_dealer_password_changes';

$route['admin/admin_approved_dealer_ajax'] = 'Dealer_Approve/approveDealerAjax';
$route['admin/approveDealer'] = 'Dealer_Approve/approveDealer';
$route['admin/updateTransactionStatus'] = 'Dealer_Approve/updateTransactionStatus';
$route['admin/update_transaction_data'] = 'Dealer_Approve/update_transaction_data';
$route['admin/reconcile_data'] = 'Dealer_Approve/reconcile_data';
$route['admin/submitDealerGstAmount'] = 'Dealer_Approve/submitDealerGstAmount';
$route['admin/gst_approval'] = 'Dealer_Approve/gstApproval';
$route['admin/gst_approval/(:any)'] = 'Dealer_Approve/gstApproval/$1';
$route['admin/gst_approval_ajax'] = 'Dealer_Approve/gstApprovalAjax';
$route['admin/invoice_approval'] = 'Dealer_Approve/invoice_approval';
$route['admin/invoice_approval/(:any)'] = 'Dealer_Approve/invoice_approval/$1';
$route['admin/invoice_approval_ajax'] = 'Dealer_Approve/invoice_approval_ajax';
$route['admin/submit_reject_invoice'] = 'Dealer_Approve/submit_reject_invoice';
$route['admin/update_invoice_data'] = 'Dealer_Approve/update_invoice_data';
$route['admin/NotLoggedIn'] = 'Dealer_Approve/NotLoggedInDealer';
$route['admin/send_sms'] = 'Dealer_Approve/SendSMSToDealer';
$route['admin/less_balance_dealer'] = 'Dealer_Approve/LessBalanceDealer';
$route['admin/dealer_policy_issued_today'] = 'Dealer_Approve/dealer_policy_issued_today';
$route['admin/last_7days_inactive_dealers'] = 'Dealer_Approve/Last7DaysDealerInactive';
$route['admin/submitTarget'] = 'Dealer_Approve/submitTarget';
$route['admin/sendSmsTask'] = 'Dealer_Approve/sendSmsTask';

$route['dealer_transaction_post'] = 'Tvs_Dealer/DealerTransactionPost';
$route['summary_page'] = 'Tvs_Dealer/SummaryPage';
$route['downloadpolicybydate'] = 'Tvs_Dealer/DownloadCsv';
$route['dealer_request_data'] = 'Tvs_Dealer/DealerRequestData';
$route['transaction_data'] = 'Tvs_Dealer/TransactionDataGrid';
$route['updateCommisionMethod'] = 'Tvs_Dealer/updateCommisionMethod';
$route['admin/admin_dashboard'] = 'Report/adminDashboard';
$route['admin/oreiental_policies_upload'] = 'Report/orientalPoliciesUpload';
$route['admin/submit_oriental_policies_form'] = 'Report/submitOrientalPoliciesForm';

$route['admin/getReasonOfCancellationPolicy'] = 'Report/getReasonOfCancellationPolicy';
$route['admin/view_policies'] = 'Report/ViewPolicies';
$route['admin/view_policy_ajax'] = 'Report/ViewPoliciesAjax';

$route['admin/servicecancel_policy_ajax'] = 'Report/servicecancel_policy_ajax';

$route['admin/view_policies_openrsa'] = 'Report/ViewPolicies_openrsa';
$route['admin/view_openrsa_policy_ajax'] = 'Report/ViewOpenrsaPoliciesAjax';

$route['admin/dealer_master'] = 'Report/DealerMaster';
$route['admin/dealer_ajax'] = 'Report/DealerMasterAjax';

$route['admin/mytvs_policies'] = 'Report_New/mytvspolicies';
$route['admin/mytvs_policies_ajax'] = 'Report_New/ViewtvspoliciesAjax';

$route['admin/limitless_dashboard'] = 'Report_New/LimitlessDashboard';
$route['admin/limitless_assist_RR310_Bharti'] = 'Report_New/limitless_assist_RR310_Bharti';
$route['admin/limitless_assist_RR310_Bharti_ajax'] = 'Report_New/limitless_assist_RR310_Bharti_ajax';

$route['admin/limitless_assist_RR310_Mytvs'] = 'Report_New/limitless_assist_RR310_Mytvs';
$route['admin/limitless_assist_RR310_Mytvs_ajax'] = 'Report_New/limitless_assist_RR310_Mytvs_ajax';

$route['admin/limitless_assistrenew_RR310_Bharti'] = 'Report_New/limitless_assistrenew_RR310_Bharti';
$route['admin/limitless_assistrenew_RR310_Bharti_ajax'] = 'Report_New/limitless_assistrenew_RR310_Bharti_ajax';

$route['admin/limitless_assistrenew_RR310_Mytvs'] = 'Report_New/limitless_assistrenew_RR310_Mytvs';
$route['admin/limitless_assistrenew_RR310_Mytvs_ajax'] = 'Report_New/limitless_assistrenew_RR310_Mytvs_ajax';

$route['admin/limitless_assistE_RR310_Bharti'] = 'Report_New/limitless_assistE_RR310_Bharti';
$route['admin/limitless_assistE_RR310_Bharti_ajax'] = 'Report_New/limitless_assistE_RR310_Bharti_ajax';

$route['admin/limitless_assistE_RR310_Mytvs'] = 'Report_New/limitless_assistE_RR310_Mytvs';
$route['admin/limitless_assistE_RR310_Mytvs_ajax'] = 'Report_New/limitless_assistE_RR310_Mytvs_ajax';

$route['admin/RMActiveDealers'] = 'RM_Reports/RMActiveDealers';
$route['admin/RMInactiveDealers'] = 'RM_Reports/RMInactiveDealers';
$route['admin/rm_dashboard'] = 'RM_Reports/RMDashboard';
$route['admin/rm_dealer_activity_report'] = 'RM_Reports/RMDealerActivityReport';
$route['admin/rm_last_week_sold_policies'] = 'RM_Reports/RMLastWeekSoldPolicy';
$route['admin/rm_last_policy_date'] = 'RM_Reports/RMLastSoldpolicyDate';
$route['admin/rm_dealer_wise_reports'] = 'RM_Reports/RMDealerWiseReport';
$route['admin/rm_DealerIC_mapped'] = 'RM_Reports/RMDealerICMpapped';

$route['admin/dealerCmpaignList'] = 'Report/dealerCmpaignList';

$route['admin/active_dealer'] = 'Report/activeDealers';
$route['admin/active_dealer_ajax'] = 'Report/activeDealersAjax';

$route['admin/add_dealer'] = 'Report/AddDealer';
$route['admin/submit_dealler_form'] = 'Report/SubmitDealerForm';
$route['admin/logged_in_dealer'] = 'Report/loggedInDealer';
$route['admin/logged_in_dealer_ajax'] = 'Report/loggedInDealerAjax';
$route['admin/assignIC'] = 'Report/assignIC';
$route['admin/submit_dms_ic_data'] = 'Report/SubmitDMSICData';
$route['admin/exclusive_ic_submit'] = 'Report/SubmitExclusiveIC';

$route['admin/add_rm'] = 'Report/AddRM';
$route['admin/submit_rm_form'] = 'Report/SubmitRMForm';
$route['admin/import_rm'] = 'Report/ImportRM';
$route['admin/save_rm_data'] = 'Report/SaveRMdata';

$route['admin/rm_master'] = 'Report/rm_master';
$route['admin/rm_ajax'] = 'Report/RMMasterAjax';

$route['admin/editrmdealer/(:any)'] = 'Report/editrmdealer/$1';


//$route['admin/cancel_policies'] = 'Report/ViewCancelPolicies';
$route['admin/cancelpolicy_ajax'] = 'Report/CancelPolicyAjax';
$route['admin/cancel_policy'] = 'Report/CancelPolicy';
$route['admin/active'] = 'Report/ActivePolicy';
$route['admin/inactive_dealers'] = 'Report/InActiveDealers';
$route['admin/inactive_dealer_ajax'] = 'Report/InActiveDealersAjax';
$route['admin/dealer_uploaded_docs'] = 'Report/dealer_uploaded_docs';
$route['admin/document_not_uploaded'] = 'Report/document_not_uploaded';
$route['admin/dealer_activity_report'] = 'Report/dealer_activity_report';
$route['admin/last_week_sold_policies'] = 'Report/last_week_sold_policies';

$route['admin/dealer_wise_reports'] = 'Report/dealerWiseReports';
$route['admin/dealerWiseReportsAjax'] = 'Report/dealerWiseReportsAjax';
$route['admin/dealer_graphical_details/(:any)'] = 'Report/dealerGraphicalReport/$1';

$route['admin/mig_reports'] = 'Report/migReports';
$route['admin/mig_report_ajax'] = 'Report/migReportsAjax';

$route['admin/dashboard_summary'] = 'Report/dashboardSummary';
$route['admin/dashboard_summary_ajax'] = 'Report/dashboardSummaryAjax';
$route['admin/target_achivement'] = 'Report/target_achivement';
$route['admin/layer_two'] = 'Report/layerTwo';
$route['admin/payable_dashboard'] = 'Report/payable_dashboard';
$route['admin/dealer_dashboard'] = 'Report/dealer_dashboard';
$route['admin/receivable_dashboard'] = 'Report/receivable_dashboard';
$route['admin/dealer_rsa_payment'] = 'Report/dealer_rsa_payment';
$route['admin/policy_detail'] = 'Report/policy_detail';
$route['admin/last_policy_date'] = 'Report/LastSoldPolicyDate';
$route['admin/tvs_dashboard'] = 'Report/tvsDashboard';

$route['admin/submit_party_payment_form'] = 'Report/submitPartyPaymentForm';
$route['admin/add_party_payment'] = 'Report/addPartyPayment';
$route['admin/party_payment_details'] = 'Report/partyPaymentDetails';
$route['admin/party_payment_details_ajax'] = 'Report/partyPaymentDetailsAjax';
$route['admin/ResetDealerpassword/(:num)'] = 'Report/ResetDealerpassword/$1';

$route['admin/pending_dealer_payment'] = 'Report/pendingDealerPayment';
$route['admin/pendingDealerPayment_ajax'] = 'Report/pendingDealerPaymentAjax';

$route['admin/paycommission'] = 'Report/paycommission';

$route['admin/approved_dealer_payment'] = 'Report/approvedDealerPayment';
$route['admin/approvedDealerPayment_ajax'] = 'Report/approvedDealerPaymentAjax';

$route['admin/get_dealer_bank_tran_id'] = 'Report/getDealerBankTran_id';

$route['admin/rr310_new_policies'] = 'RR310_Dashboard/RR310NewPolicies';
$route['admin/rr310_new_policy_ajax'] = 'RR310_Dashboard/RR310NewPolicyAjax';
$route['admin/rr310_renew_policies'] = 'RR310_Dashboard/RR310RenewPolicy';
$route['admin/rr310_renew_policy_ajax'] = 'RR310_Dashboard/RR310RENewPolicyAjax';
$route['admin/paid_service'] = 'Report/paid_service';
$route['admin/Workshop_frameno'] = 'Report/Workshop_frameno';
$route['admin/upload_frameno_file'] = 'Report/upload_frameno_file';
$route['admin/pending_renewal_policies'] = 'Report/pending_renewal_policies';
$route['admin/todays_renewal_policies'] = 'Report/todays_renewal_policies';
$route['admin/renewed_policy_report'] = 'Report/renewed_policy_report';

$route['admin/wrong_punched_policies'] = 'Report/wrong_punched_policies';
$route['admin/wrong_punched_policies_ajax'] = 'Report/wrong_punched_policies_ajax';

$route['admin/flash_report'] = 'Report/flash_report';
$route['admin/tvsdashboard_report'] = 'Report/tvsdashboard_report';
$route['admin/consolidated_report'] = 'Report/consolidated_report';

$route['admin/sendsms_checkedboxdata'] = 'Report/sendsms_checkedboxdata';

//..........PA Claim...........//

$route['pa_claim'] = 'front/myaccount/Rsa_cover/PaClaim';
$route['search_data'] = 'front/myaccount/Rsa_cover/SearchData';
$route['upload_claim_data'] = 'front/myaccount/Rsa_cover/UploadClaimData';


// $route['dashboard'] = 'Rsa_Dashboard/index';
$route['dashboard/(:any)'] = 'Rsa_Dashboard/index/$1';
$route['resetCustomerForm'] = 'Rsa_Dashboard/resetCustomerForm';
$route['dealer_form'] = 'Rsa_Dashboard/dealer_form';
$route['getBankDetailsByIFSC'] = 'Rsa_Dashboard/getBankDetailsByIFSC';
$route['submit_dealer_details'] = 'Rsa_Dashboard/submit_dealer_details';
$route['download_rsa_agreement_pdf'] = 'Rsa_Dashboard/download_rsa_agreement_pdf';
$route['dealer_document_form'] = 'Rsa_Dashboard/DealerDocumentForm';
$route['dealer_uploads_data'] = 'Rsa_Dashboard/DealerUploadsData';
$route['update_pasword'] = 'Rsa_Dashboard/update_pasword';
$route['check_Exist_mobile_no'] = 'Rsa_Dashboard/check_Exist_mobile_no';
$route['update_bizusers_data'] = 'Rsa_Dashboard/update_bizusers_data';


$route['admin/MYTVS_feed_file/(:num)'] = 'Report/MYTVS_feed_file/$1';

$route['downlodfeedfile_bydate'] = 'Report/downlodfeedfile_bydate';
$route['geCountOfOldNewPolicy'] = 'Rest_server/geCountOfOldNewPolicy';
$route['customers'] = 'customers';


//................End.........//
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;