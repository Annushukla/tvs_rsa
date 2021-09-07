<?php

//============================================================+
// File name   : tcpdf_import.php
// Version     : 1.0.001
// Begin       : 2011-05-23
// Last Update : 2013-09-17
// Author      : Nicola Asuni - Tecnick.com LTD - www.tecnick.com - info@tecnick.com
// License     : GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
// -------------------------------------------------------------------
// Copyright (C) 2011-2013 Nicola Asuni - Tecnick.com LTD
//
// This file is part of TCPDF software library.
//
// TCPDF is free software: you can redistribute it and/or modify it
// under the terms of the GNU Lesser General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// TCPDF is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU Lesser General Public License for more details.
//
// You should have received a copy of the License
// along with TCPDF. If not, see
// <http://www.tecnick.com/pagefiles/tcpdf/LICENSE.TXT>.
//
// See LICENSE.TXT file for more information.
// -------------------------------------------------------------------
//
// Description : This is a PHP class extension of the TCPDF library to
//               import existing PDF documents.
//
//============================================================+

/**
 * @file
 * !!! THIS CLASS IS UNDER DEVELOPMENT !!!
 * This is a PHP class extension of the TCPDF (http://www.tcpdf.org) library to import existing PDF documents.<br>
 * @package com.tecnick.tcpdf
 * @author Nicola Asuni
 * @version 1.0.001
 */
// include the TCPDF class
require_once(dirname(__FILE__) . '/tcpdf.php');
// include PDF parser class
require_once(dirname(__FILE__) . '/tcpdf_parser.php');

/**
 * @class TCPDF_IMPORT
 * !!! THIS CLASS IS UNDER DEVELOPMENT !!!
 * PHP class extension of the TCPDF (http://www.tcpdf.org) library to import existing PDF documents.<br>
 * @package com.tecnick.tcpdf
 * @brief PHP class extension of the TCPDF library to import existing PDF documents.
 * @version 1.0.001
 * @author Nicola Asuni - info@tecnick.com
 */
class MYPDF extends TCPDF {

    //Page header
     public function Header(){
     $html = '<table cellpadding="0" border="0" cellspacing="0" class="header"><tr><td colspan="2"></td></tr><tr><td width="14%"><img src="assets/images/mpdf/oicl_logo.png" alt="" width="75"></td><td width="86%" style="font-size:12pt; line-height:16pt; color:#000;"><br><br>THE ORIENTAL INSURANCE COMPANY LIMITED <br><span style="font-size: 8pt; line-height:10pt;">(A Government of India Undcrtaking)</span></td></tr></table>';
     $this->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
  }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'R', 8);
        // Page number
        $this->Cell(0, 10, 'Regd. Office : ORIENTAL HOUSE, PB No 7037 A-25/27, Asal Ali Road, New Delhi - 110 002', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}


// END OF CLASS

//============================================================+
// END OF FILE
//============================================================+
