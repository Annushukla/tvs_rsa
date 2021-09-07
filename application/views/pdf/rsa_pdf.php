
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<style>
  .pagewrap {color: #000; font-size: 7pt; line-height:9pt; color:#000;}
  .textcenter {text-align:center;}
  .textleft {text-align:left;}
  .textright {text-align:right;}
  .font-7{font-size: 7pt; line-height:9pt;}
  .font-8{font-size: 8pt; line-height:10pt;}
  .font-9{font-size: 9pt; line-height:11pt;}
  .font-10{font-size: 10pt; line-height:12pt;}
  .font-11{font-size: 11pt; line-height:13pt;}
  .line-height-9{line-height:9pt;}
  .line-height-10{line-height:10pt;}
  .line-height-11{line-height:11pt;}
  .line-height-12{line-height:12pt;}
  .line-height-13{line-height:13pt;}
  .line-height-14{line-height:14pt;}
  .line-height-15{line-height:15pt;}
  .line-height-16{line-height:16pt;}
  .line-height-17{line-height:17pt;}
  .line-height-18{line-height:18pt;}
  .line-height-19{line-height:19pt;}
  .line-height-20{line-height:20pt;}
  .header {background-color:#ec3237;}
  .headertext {font-size:16pt; line-height:40pt; color:#fff;}
  .border, .boxtable td {border:0.2px solid #000;}
  .sectionhead { font-size:7.5pt; line-height:12pt; background-color:#000; color:#fff;}
  .footer {border-top:0.5px solid #808080; text-align:center; line-height:9pt;}

  .asistance-table { font-size:8pt;  line-height:11pt; font-family:arial;} 
  .dotborderleft {border-left:0.5px dotted #014e9f}
  .dotborderright {border-right:0.5px dotted #014e9f}
  .asistance-sectionhead { font-size:9pt; line-height:10pt; background-color:#63a5ea; color:#fff;}  
</style>
  <table width="700" align="center" cellpadding="0" border="0" cellspacing="0" class="pagewrap">
   <tr>
      <td><img src="<?php echo base_url('assets/images/mpdf/banner.jpg');?>" alt="" style="width:800px;"></td>
    </tr>
    <tr>
      <td style="height:30;"></td>
    </tr>
    <tr>
      <td>
        <table width="100%" cellpadding="0" border="0" cellspacing="0">
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td colspan="2" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 9pt;">CERTIFICATE</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Certificate Number</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $certificate_no;?></td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Plan Name</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $plan_name;?></td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Certificate issue Date</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $created_date;?></td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>RSA Start Date</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $sold_policy_effective_date;?></td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>RSA End Date</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $sold_policy_end_date;?></td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td colspan="2" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height:9pt;">VEHICLE</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Vehicle Registration Number</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $vehicle_registration_no;?></td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Manufacturer</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;">TVS</td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Model</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $model_name;?></td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Engine Number </b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $engine_no;?></td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Chassis Number</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $chassis_no;?></td>
                    </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td style="height: 15px;">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt;">&nbsp; PERSONAL DETAILS</td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>First Name</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $fname;?></td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Last Name</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $lname;?></td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Mobile No</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $mobile_no;?></td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Email ID </b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $email;?></td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Address 1 </b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $addr1;?></td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Address 2</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $addr2;?></td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>State</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $state_name;?></td>
                    </tr>
                    <tr>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>City</b></td>
                      <td style="width: 50%; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $city_name;?></td>
                    </tr>
                </tbody>
              </table>
            </td>
          </tr>
            <tr>
            <td style="height: 10px;"></td>
          </tr>
           <tr>
            <td colspan="3" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt;">&nbsp; PAYMENT DETAILS</td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Plan Amount(Rs.)</b></td>

                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Tax Amount(18% IGST in Rs.)</b></td>
                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><b>Total Amount (Rs.)</b></td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c; border-top: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $plan_amount;?></td>
                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $gst_amount;?></td>
                    </tr>
                    <tr>
                      <td style=" color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-left: 1px solid #16365c; border-bottom: 1px solid #16365c;"><?php echo $total_amount;?></td>
                    </tr>

                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td style="height: 20px;"></td>
          </tr>
          <tr>
            <td colspan="3" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt;">&nbsp;  CHANNEL PARTNER: TVS</td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 9pt;">INDICOSMIC CAPITAL</td>
                    </tr>
                    <tr>
                      <td style="  height: 80px; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><img src="assets/images/mpdf/icpl-logo.jpg" alt=""><br><b>Address:</b> 318, 3rd Floor, Summit-Business Bay, Behind Gurunanak Petrol Pump, Off Andheri Kurla Road, Beside Magic Bricks WEH metro stn., Andheri (E),Mumbai-400093, Maharashtra (India)</td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height:9pt;"><?php echo $rsa_name;?></td>
                    </tr>
                    <tr>
                      <td style=" height: 80px; color: #000; font: 8pt arial; line-height: 1.2; padding:5px; border-bottom: 1px solid #16365c;"><img src="<?php echo $rsa_logo;?>" alt="" style="height:30px"><br><b>Address:</b>  <?php echo $rsa_address;?></td>
                    </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 9pt;">EMAIL : info@indicosmic.com</td>
                    </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 4%;">&nbsp;</td>
            <td style="width: 48%;">
              <table class="table" width="100%" bgcolor="" cellpadding="4" border="0" cellspacing="0" style="border-left: 1px solid #16365c; border-right: 1px solid #16365c;">
                  <tbody>
                    <tr>
                      <td style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 9pt;">EMAIL : <?php echo $rsa_email;?></td>
                    </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td colspan="3" style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt; text-align: center;">CUSTOMER CARE NO: <?php echo $customer_care_no;?></td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td colspan="3" col style="color: #fff; background-color: #16365c; -webkit-print-color-adjust: exact; font: 8pt arial; line-height: 16pt;">&nbsp; THE SERVICES PROVIDED UNDER THE ASSISTANCE ARE AS FOLLOWS:</td>
          </tr>
        </table>
      </td>
   </tr>

  </table>
<br pagebreak="true"/>
  <table width="800" align="center" cellpadding="0" border="0" cellspacing="0" class="asistance-table">         
  <tr>   
            <td width="48%">
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Coverage</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/coverage.jpg');?>"></td>
                  <td width="82%">The territorial scope of the Roadside Assistance Services provided by ICPL shall be only ligible to customers.</td>
                </tr>
              </table>
            </td>
            <td width="2%" class=""></td>
            <td width="2%" class="dotborderleft"></td>
            <td width="48%">
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Coverage in North East and J&K Coverage In Islands</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/north-east-and-jk.jpg');?>"></td>
                  <td width="82%">Due to the extreme geographical conditions, ICPL will not provide RSA services in North Eastern States and Jammu & Kashmir. (Specific cities might be covered based on ICPL’s requirement). SLAs will not be applicable for services rendered in these territories. ICPL will not provide RSA services in Islands</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Toll Free</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/toll-free.jpg');?>"></td>
                  <td width="82%">24 X 7 multi lingual support</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Onsite support for Minor repairs</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/onsite-repair.jpg');?>"></td>
                  <td width="82%">In the event the Vehicle covered under this Agreement (Covered Vehicle) having a breakdown due to minor mechanical/ electrical fault, ICPL shall support by arranging vehicle technician to the breakdown location. ICPL will bear the labor cost and conveyance charges. Cost of spare parts if required to repair the vehicle on the spot (Outside coverage area) to obtain such material & spare parts will be borne by the Customer.</td>
                </tr>
              </table>
            </td>
          </tr> 
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Rundown of Battery</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/jump-start.jpg');?>"></td>
                  <td width="82%">In the event the Covered Vehicle having a breakdown due to rundown of battery, ICPL shall support by arranging vehicle technician to jump start the vehicle with appropriate means. ICPL will bear the labor cost and conveyance charges.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Flat Tyre</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/flat-tyre.jpg');?>"></td>
                  <td width="82%">In the event that the Covered Vehicle has a puncture or tyre burst, ICPL shall support the Customer in replacing the flat tyre with spare tyre. The technician will repair the same at the location of breakdown.In case of nonavailability of spare tyre, ICPL will try to repair the faulty tyre. This service is based on the availability of tyre repair shop near the breakdown location. All the cost related to rendering such service will be charged to customers.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Towing Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/towing.jpg');?>"></td>
                  <td width="82%">In the event that a Covered Vehicle is immobilized due to electrical or mechanical failure, ICPL shall arrange towing of the Covered Vehicle to the nearest authorized outlet. These services shall be provided using equipment/s deemed most suitable by ICPL. Towing from incident to nearest tvs service center is free.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Urgent Message Relay</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/msg-relay.jpg');?>"></td>
                  <td width="82%">Relay of Urgent message to family / friends in case of medical emergency.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Emergency Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/med-cordination.jpg');?>"></td>
                  <td width="82%">Medical co-ordination for occupants of the vehicle as a result of accidents.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Fuel Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/fuel.jpg');?>"></td>
                  <td width="82%">In the event Covered Vehicle runs out of fuel or stops due to contaminated fuel, ICPL will provide support by arranging up to 2 liters of fuel. The supply of fuel will be based on availability. ICPL will bear the labor cost and conveyance charges. Fuel charges shall be borne by Customer. This service will not be applicable if the vehicle is at Customer residence This service is based on local availability of fuel.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Key Lost / Replacement</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/spare-key.jpg');?>"></td>
                  <td width="82%">In the event of Key Lost / Replacement, if possible we will arrange replacement key, else vehicle will be towed to nearest garage as per (7) above.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Taxi Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/taxi.jpg');?>"></td>
                  <td width="82%">In the event that a Covered Vehicle is immobilized due to the breakdown or accident and On-site preliminary support to make the vehicle roadworthy, is not possible, ICPL shall arrange and bear the expense for transferring the Covered Vehicle to the nearest authorized outlet. To help the Customer continue with his journey, ICPL will arrange taxi support to the Customer as per availability. SLAs will not be applicable for taxi assistance and ICPL does not guarantee the quality of taxi services.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Accommodation Assistance</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/hotel.jpg');?>"></td>
                  <td width="82%">Arranging for hotel accommodation in case breakdown is reported outside customer’s home city. Co-ordination is free and all the related accommodation cost will be borne by the customer.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Outward / Forward Journey</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/outward-forward.jpg');?>"></td>
                  <td width="82%">Arranging for Outward / Forward Journey in case breakdown is reported outside customer’s home city Co-ordination is free, all the related travel cost will beborne by the customer.</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>          
            <td>
              <table cellpadding="4" border="0" cellspacing="4">
                <tr>
                  <td colspan="2" class="asistance-sectionhead">Arrangement of Rental Vehicle</td>
                </tr>
                <tr>
                  <td width="18%"><img height="70" width="70" src="<?php echo base_url('assets/images/mpdf/rent.jpg');?>"></td>
                  <td width="82%">Arranging rental vehicle in case breakdown is reported outside customer’s home city Co-ordination is free, all the related rental vehicle cost will be borne by the customer.</td>
                </tr>
              </table>
            </td>
            <td class=""></td>
            <td class="dotborderleft"></td>
            <td></td>
          </tr>
        </table>
</body>
</html>