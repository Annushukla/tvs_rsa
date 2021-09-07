<?php
//============================================================+
// File name   : example_002.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 002 for TCPDF class
//               Removing Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Removing Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('ICICI Lombard Policy Certificate');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(9, 7, 10);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 2);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);

// add a page
$pdf->AddPage();

// set some text to print
$html = '
<style>
	.pagewrap {color: #000; font-size: 8pt; line-height:11pt; color:#000;}
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
	.border, .boxtable td {border:0.2px solid #000;}
	.boxtable td.heading {background-color:#d9d9d9; color:#000; font-weight:bold;}
	.sectionhead { font-size:7.5pt; line-height:12pt; background-color:#000; color:#fff;}
</style>

<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
	<tr>
		<td>
			<table cellpadding="0" border="0" cellspacing="0">
				<tr>
					<td class="textright"><img src="images/icici_lombard.png" height="40px"></td>
				</tr>		
				<tr>
					<td></td>
				</tr>
				<tr>
					<td><img src="images/sepline-top.png" height="15"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellpadding="5" border="0" cellspacing="0" class="textcenter line-height-13">	
				<tr>			
					<td></td>
				</tr>
				<tr>
					<td class="font-10 line-height-16" style="color:#365f91;"><b><u>Policy Certificate – Group Personal Accident</u></b></td>
				</tr>
			</table>
			<table cellpadding="5" border="0" cellspacing="0">
				<tr>
					<td style="text-align:justify;">ICICI Lombard Group Personal Accident Policy no. ___________________ dated _________ has been issued at Mumbai, by ICICI Lombard General Insurance Company Limited to the Policyholder _______________,,as specified in the policy and is governed by, and is subject to, the terms, conditions & exclusions therein contained or otherwise expressed in the said policy, but not exceeding the sum insured as specified in Part I of the schedule to the said policy.</td>
				</tr>
				<tr>
					<td style="text-align:justify;">This certificate, issued under the signature of an authorized signatory of the Company represents the availability of benefit to the insured named below, Customers of ___________ subject to the terms, conditions and exclusions contained or otherwise expressed in the said Policy to the extent of sum insured mentioned as maximum liability, but not exceeding the Sum Insured as specified below.</td>
				</tr>
				<tr>
					<td></td>
				</tr>
			</table>
			<table cellpadding="5" border="0" cellspacing="0">				
				<tr>
					<td>
						<table cellpadding="5" border="0" cellspacing="0" class="boxtable textleft" width="90%">
							<tr>
								<td width="50%"><b>Policy No.</b></td>
								<td width="50%"></td>					
							</tr>
							<tr>
								<td><b>Policy Tenure</b></td>
								<td></td>
							</tr>
							<tr>
								<td><b>Period of Insurance</b></td>
								<td>From: 01/01/2018 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To: 01/01/2019</td>
							</tr>
							<tr>
								<td><b>Insured Name</b></td>
								<td></td>
							</tr>
							<tr>
								<td><b>Insured Address</b></td>
								<td></td>
							</tr>
							<tr>
								<td><b>Contact No.</b></td>
								<td></td>
							</tr>
							<tr>
								<td><b>Email ID</b></td>
								<td></td>
							</tr>
							<tr>
								<td><b>Policy Issuing Office</b></td>
								<td>Prabhadevi</td>
							</tr>				
						</table>
					</td>
				</tr>
			</table>
			<table cellpadding="5" border="0" cellspacing="0">	
				<tr>
					<td></td>
				</tr>
				<tr>
					<td><b>PREAMBLE</b></td>
				</tr>
				<tr>
					<td style="text-align:justify;">ICICI Lombard General Insurance Company Limited (“the Company”), having received a Proposal and the premium from the Policy holder named in the Schedule referred to herein below, and the said Proposal and Declaration together with any statement, report or other document leading to the issue of this Policy and referred to therein having been accepted and agreed to by the Company and the Policy holder as the basis of this contract do, by this Policy agree, in consideration of and subject to the due receipt of the subsequent premiums, as set out in the Schedule with all its Parts, and further,  subject to the terms and conditions contained in this Policy, as set out in the Schedule with all its Parts that on proof to the satisfaction of the Company of the compensation having become payable as set out in Part I of the Schedule to the title of the said person or persons claiming payment or upon the happening of an event upon which one or more benefits become payable under this Policy, the Sum Insured/ appropriate benefit amount will be paid by the Company.</td>
				</tr>
			</table>
			<table cellpadding="5" border="0" cellspacing="0">	
				<tr>
					<td></td>
				</tr>
				<tr>
					<td><b>Insured Details</b></td>
				</tr>			
				<tr>
					<td>
						<table cellpadding="5" border="0" cellspacing="0" class="boxtable textcenter">
							<tr>
								<td class="heading" width="6%">Sr No:</td>
								<td class="heading" width="14%">Name in Full</td>
								<td class="heading" width="10%">Date of Birth</td>
								<td class="heading" width="8%">Gender</td>
								<td class="heading" width="12%">Occupation</td>
								<td class="heading" width="10%">Risk Category</td>
								<td class="heading" width="11%">Relationship with Proposer</td>
								<td class="heading" width="11%">Beneficiary / Nominee</td>
								<td class="heading" width="18%">Relation of Nominee with the Insured</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>			
						</table>
					</td>
				</tr>
			</table>
			<table cellpadding="5" border="0" cellspacing="0">	
				<tr>
					<td></td>
				</tr>
				<tr>
					<td><b>Benefit & Extension Table</b></td>
				</tr>			
				<tr>
					<td>
						<table cellpadding="5" border="0" cellspacing="0" class="boxtable textleft">
							<tr>
								<td width="12%"><b>Benefit </b></td>
								<td width="28%"><b>Cover</b></td>
								<td width="40%"><b>Benefit Amount</b></td>
								<td width="20%"><b>Sum Insured (Rs.)</b></td>
							</tr>
							<tr>
								<td>Benefit 1</td>
								<td>Death resulting from Accident</td>
								<td>To pay Sum Insured as mentioned against Death benefit on the occurrence of death of the Insured Person, provided such death results solely and directly from an Injury, within twelve months from the date of Accident</td>
								<td rowspan="2"></td>
							</tr>
							<tr>
								<td>Benefit 2</td>
								<td>Permanent Total Disablement (PTD) resulting from Accident</td>
								<td>To pay Sum Insured on the occurrence of PTD which result solely and directly from an Injury, within twelve months from the date of Accident</td>
							</tr>			
						</table>
					</td>
				</tr>
			</table>	
	    </td>	    
	</tr>
	<tr>
		<td>
			<table cellpadding="0" border="0" cellspacing="0">
				<tr>	
					<td></td>
				</tr>	
				<tr>
					<td><img src="images/sepline-bot.png" height="10"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br pagebreak="true" />
<table cellpadding="0" border="0" cellspacing="0" class="pagewrap">  
	<tr>
		<td>
			<table cellpadding="0" border="0" cellspacing="0">
				<tr>
					<td class="textright"><img src="images/icici_lombard.png" height="40px"></td>
				</tr>		
				<tr>
					<td></td>
				</tr>
				<tr>
					<td><img src="images/sepline-top.png" height="15"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellpadding="5" border="0" cellspacing="0">	
				<tr>
					<td><b>Benefit & Extension Table</b></td>
				</tr>			
				<tr>
					<td>
						<table cellpadding="5" border="0" cellspacing="0" class="boxtable textleft">
							<tr>
								<td width="50%">Basic Premium</td>
								<td width="50%"></td>
							</tr>	
							<tr>	
								<td>GST (As Applicable)</td>
								<td></td>
							</tr>
							<tr>	
								<td>Total Amount</td>
								<td></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td></td>
				</tr>			
				<tr>
					<td>
						<table cellpadding="5" border="0" cellspacing="0" class="boxtable textcenter">
							<tr>
								<td class="heading" width="30%">GSTIN Reg. No</td>
								<td class="heading" width="30%">IRDA Reg No</td>
								<td class="heading" width="40%">Category</td>
							</tr>
							<tr>
								<td>27AAACI7904G1ZN</td>
								<td>115</td>
								<td>GENERAL INSURANCE SERVICES 9971</td>
							</tr>			
						</table>
					</td>
				</tr>
				<tr>
					<td></td>
				</tr>			
				<tr>
					<td>
						<table cellpadding="5" border="0" cellspacing="0" class="boxtable textcenter">
							<tr>
								<td class="heading" colspan="6">Agent / Broker Details</td>
							</tr>
							<tr>
								<td width="12%"><b>Agent Name</b></td>
								<td width="30%"><b>GLOBAL INDIA INSURANCE BROKERS PVT LTD</b></td>
								<td width="14%"><b>Agent Code</b></td>
								<td width="14%"><b>IRDA/DB-596/14</b></td>
								<td width="16%"><b>Agent contact No.</b></td>
								<td width="14%"><b>9869467120</b></td>
							</tr>			
						</table>
					</td>
				</tr>
				<tr>
					<td></td>
				</tr>
				<tr>
					<td style="color:#365f91;"><b>Important Notes:</b> <br>1. Insurance cover will start only on receipt of complete premium by ICICI Lombard General Insurance Company Limited <br>2. Insurance cover is subject to the terms and conditions mentioned in the Policy wordings provided to you with this Certificate <br>3. The above covers would not be applicable for persons occupied in underground mines, explosives and electrical installations on high tension lines <br>4. Major exclusions: Intentional self-injury, suicide or attempted suicide whilst under the influence of intoxicating liquor or drugs, Any loss arising from an act made in breach of law with or without criminal intent. <br>5. The claimant can contact us at Toll Free Number 1800-2-666 or Email us at customersupport@icicilombard.com for lodging the claim. <br>6. Claim Notification address: IL Health Care,Secure Mind Claims,ICICI LOMBARD HEALTHCARE ICICI BANK TOWER,PLOT NO.12FINANCIAL DISTRICT,NANAKRAM GUDA,GACHIBOWLI,HYDERABAD </td>
				</tr>
				<tr>
					<td></td>
				</tr>
				<tr>
					<td>
						<table cellpadding="5" border="0" cellspacing="0" class="boxtable">							
							<tr>
								<td width="30%" style="background-color:#d9d9d9;">
									<table cellpadding="2" border="0" cellspacing="0">	
										<tr>
											<td>For ICICI Lombard General Insurance Company ltd.</td>
										</tr>
										<tr>
											<td><img src="images/sign-img.jpg" height="80"></td>
										</tr>
										<tr>
											<td><b>Authorised Signatory</b></td>
										</tr>
									</table>
								</td>
								<td width="70%" style="background-color:#d9d9d9;text-align:justify; ">Important: Insurance benefit shall become voidable at the option of the company, in the event of any untrue or incorrect statement, misrepresentation non-description of any material particular in the proposal form/ personal statement, declaration and connected documents, or any material information has been withheld by beneficiary or anyone acting on beneficiary’s behalf to obtain insurance benefit. Please note that any claims arising out of pre-existing illness/ injury/ symptoms is excluded from the scope of this policy subject to applicable terms and conditions. Refer to policy wordings for the terms and conditions. All disputes are subject to the jurisdiction of Mumbai High Court only. For claims, please call us at our toll free no. 1800 2666 or e-mail to us at ihealthcare@icicilombard.com or write to us at ICICI Lombard GIC, ICICI Bank Tower, Plot no-12, Financial district Nanakramguda, Gachibowli, Hyderabad, Andhra Pradesh 500032.</td>
							</tr>
							<tr>
								<td colspan="2" style="text-align:justify;">This policy has been issued based on the details furnished by the policyholder. Please review the details furnished in the policy certificate and confirm that same are in order. In case of any discrepancy/ variation, you are requested to call us immediately at our toll free no. 1800 2666 or write to us at customersupport@icicilombard.com. In the absence of any communication from you within the period of 15 days of receipt of this document, the policy would be deemed to be in order and issued as per your proposal. All refunds and claim payment will be done through NEFT only. In case of addition of member/ increase in sum insured, fresh waiting period will be applicable to new member/ increased sum insured. This policy certificate is to be read with the policy wordings, as one contract or any word or expression to which a specific meaning has been attached in any part of this policy shall bear the same meaning wherever it may appear.</td>
							</tr>	
						</table>
					</td>
				</tr>
				<tr>
					<td height="165"></td>
				</tr>
			</table>				
	    </td>	    
	</tr>
	<tr>
		<td>
			<table cellpadding="0" border="0" cellspacing="0">
				<tr>	
					<td></td>
				</tr>	
				<tr>
					<td><img src="images/sepline-bot.png" height="10"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('ICICI-Lombard-Policy-Certificate.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
