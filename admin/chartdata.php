<?php

ob_start();
require('../dbconfig.php');

// Include the main TCPDF library (search for installation path).
require_once('../vendor/TCPDF-master/examples/tcpdf_include.php');
require_once('../vendor/TCPDF-master/tcpdf.php');
require_once('../vendor/autoload.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// NOTE: 2D barcode algorithms must be implemented on 2dbarcode.php class file.

// set font
$pdf->SetFont('helvetica', '', 11);

// add a page
$pdf->AddPage();

// set style for barcode
$style = array(
    'border' => 2,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255)
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);


$fill = false;
$width = 30;
$t_width = 25;

foreach ($_POST['employee_id'] as $value) {
    $fetch = mysqli_query($conn, "SELECT * FROM employee where emp_cardid = '$value' ");

    while ($row = mysqli_fetch_array($fetch)) {
        $code = $row['emp_cardid'];
        $first = $row['e_firstname'];
    }
$pdf->write2DBarcode($code, 'QRCODE,Q', 20, $width, 50, 50, $style, 'N');
$pdf->Text(16, $t_width, $first);
//$pdf->write2DBarcode('www.tcpdf.org', 'QRCODE,L', 20, 30, 50, 50, $style, 'N');
//$pdf->Text(20, 25, 'QRCODE L');
    $width = $width + 60;
    $t_width = $t_width + 60;
$fill = !$fill;
//if($width == 150){
//    $width =30;
//    $t_width = 25;
//    $pdf->Ln();
//}else {
//    $width = $width + 60;
//    $t_width = $t_width + 60;
//}
}

//Close and output PDF document
ob_end_clean();
$pdf->Output('example_050.pdf', 'I');