<?php
class Policy_pdf extends CI_Controller{
	 public function __construct() {
        parent::__construct();
        
    }

function HDFCPDF($id){

$this->load->library('Tcpdf/Tcpdf.php'); 
	ob_start();
$pdf = new TCPDF();
  $hdfc_view = $this->load->view('pdf/hdfc_pdf');
  $pdf->AddPage();
  $pdf->writeHTML($hdfc_view);
  ob_clean();

  $pdf->Output('HDFC-ERGO-GPA-Policy.pdf', 'I');

  
}


}


?>