<?php
        class MyCustomPDFWithWatermark extends TCPDF {
                public function Header() {
                    // Get the current page break margin
                    $bMargin = $this->getBreakMargin();

                    // Get current auto-page-break mode
                    $auto_page_break = $this->AutoPageBreak;

                    // Disable auto-page-break
                    $this->SetAutoPageBreak(false, 0);

                    // Define the path to the image that you want to use as watermark.
                    $img_file = base_url().'assets/images/mpdf/hotel.jpg';
                    $pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

                    // Restore the auto-page-break status
                    $this->SetAutoPageBreak($auto_page_break, $bMargin);

                    // Set the starting point for the page content
                    $this->setPageMark();
                }
            }

?>