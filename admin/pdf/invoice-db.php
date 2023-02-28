<?php
ob_start();
require('fpdf.php');
include '../../dbconfig.php';
$id = $_GET['id'];
//print curent Date
$datee = date("d/m/Y");
//select Data From Database
$query = mysqli_query($conn, "select * from staff_salary INNER JOIN company_logo ON company_logo.admin_id = staff_salary.admin_id INNER JOIN company_admin ON company_admin.admin_id = company_logo.admin_id  where 	staff_salary.salary_id = '$id'");
// echo "select * from staff_salary INNER JOIN company_logo ON company_logo.admin_id = saff_salary.admin_id INNER JOIN company_admin ON company_admin.admin_id = company_logo.admin_id  where 	staff_salary.salary_id = '$id'";
$invoice = mysqli_fetch_array($query);
$admin_id = $invoice['admin_id'];
$company_logo = '../company_document/'.$invoice['logo_name'];
$company_address = $invoice['company_address'].",".$invoice['city'].",".$invoice['state'].",".$invoice['pin_code'].",".$invoice['country'];
if($invoice['basic'] > $invoice['grosspay']){
    $gross = $invoice['basic'];
}else{
    $gross = $invoice['grosspay'];
}
$pdf = new FPDF();
$pdf->AddPage();
$pdf->AddFont('Verdana','','Verdana.php');
$pdf->AddFont('verdanab','','verdanab.php');
$pdf->SetFont('Verdana', '', 6);
$pdf->Cell(55, 5, 'Print Date is '.': '.$datee, 0, 0);
$pdf->Image($company_logo, 84,6,-1450);
$pdf->Ln(4);
$pdf->SetFont('Verdana', '', 9);
$pdf->Cell(175, 23, $company_address, 0, 0,'C');
$pdf->Cell(10, 5, '', 0, 1,'C');
//$pdf->Cell(128, 20, $val[1].",".$val[2], 0, 0,'R');
$pdf->Cell(90, 5, '', 0, 1,'C');
$pdf->SetFont('verdanab', '', 14);
$pdf->Cell(110, 28, 'Salary Slip', 0, 0,'R');
$pdf->SetFont('verdanab', '', 11);
$pdf->Ln(18);
$pdf->Cell(38, 10, 'Employee Name:', 0, 0);
$pdf->SetFont('verdana', '', 11);
$pdf->Cell(65, 10,$invoice['emp_name'], 0, 0,'');
$pdf->Cell(25, 10, '', 0, 0);
$pdf->SetFont('verdanab', '', 11);
$pdf->Cell(52, 10, 'Date: '.date('d/m/Y',$invoice['staff_salary_date']), 0, 1);
$pdf->Line(47,49,125,49);
//$pdf->Line(151,49,180,49);
$pdf->Cell(38, 10, 'Employee ID:', 0, 0);
$pdf->SetFont('verdana', '', 11);
$pdf->Cell(65, 10,$invoice['emp_cardid'], 0, 0,'L');
$pdf->Cell(25, 10, '', 0, 0);
$pdf->SetFont('verdanab', '', 11);
$pdf->Cell(52, 10, 'Month/Year: '.date('M-Y',$invoice['staff_salary_date']), 0, 1);
$pdf->Line(40,59,125,59);
//$pdf->Line(167,59,192,59);
$pdf->ln(5);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('verdana', '', 10);
$pdf->Cell(45, 5, 'Basic Salary', 1, 0);
$pdf->Cell(50, 5,number_format($invoice['basic']), 1, 0,'C');
$pdf->Cell(45, 5, 'Per Day Salary', 1, 0);
$pdf->Cell(50, 5,$invoice['par_day_salary'], 1, 0,'C');
$pdf->Ln(8);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(158,81,182);
$pdf->Cell(70, 7, 'Description', 1, 0,'C',TRUE);
$pdf->Cell(60, 7, 'Earning', 1, 0,'C',TRUE);
$pdf->Cell(60, 7, 'Deduction', 1, 1,'C',TRUE);
$pdf->Ln(0);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(255,255,255);
if($invoice['da'] != 'not used') {
    $pdf->Cell(70, 6, 'Dearness allowance', 1, 0,'C');
    $pdf->Cell(60, 6, $invoice['da'], 1, 0, 'C');
    $pdf->Cell(60, 6, " ", 1, 1, 'C');
    $pdf->Ln(0);
}
if($invoice['hra'] != 'not used') {
    $pdf->Cell(70, 6, 'House Rent Allowance', 1, 0,'C');
    $pdf->Cell(60, 6,$invoice['hra'], 1, 0,'C');
    $pdf->Cell(60, 6,' ', 1, 1,'C');
    $pdf->Ln(0);
}
if($invoice['conveyance'] != 'not used') {
    $pdf->Cell(70, 6, 'Conveyance', 1, 0,'C');
    $pdf->Cell(60, 6, $invoice['conveyance'], 1, 0,'C');
    $pdf->Cell(60, 6, ' ', 1, 1,'C');
    $pdf->Ln(0);
}
if($invoice['allowance'] != 'not used') {
    $pdf->Cell(70, 6, 'Allow', 1, 0,'C');
    $pdf->Cell(60, 6, $invoice['allowance'], 1, 0, 'C');
    $pdf->Cell(60, 6, '', 1, 1, 'C');
    $pdf->Ln(0);
}
if($invoice['medical_allowance'] != 'not used') {
    $pdf->Cell(70, 6, 'Medical Allow', 1, 0,'C');
    $pdf->Cell(60, 6, $invoice['medical_allowance'], 1, 0, 'C');
    $pdf->Cell(60, 6, '', 1, 1, 'C');
    $pdf->Ln(0);
}
// if($invoice['other'] != 'not used') {
//     $pdf->Cell(70, 6, 'Other', 1, 0,'C');
//     $pdf->Cell(60, 6, $invoice['others'], 1, 0, 'C');
//     $pdf->Cell(60, 6, '', 1, 1, 'C');
//     $pdf->Ln(0);
// }
$pdf->Cell(70, 6, 'Incentive', 1, 0,'C');
$pdf->Cell(60, 6, $invoice['incentive'], 1, 0, 'C');
$pdf->Cell(60, 6, '', 1, 1, 'C');
$pdf->Ln(0);
$pdf->Cell(70, 6, 'Over Time', 1, 0,'C');
$pdf->Cell(60, 6, $invoice['over_time'], 1, 0, 'C');
$pdf->Cell(60, 6, '', 1, 1, 'C');
$pdf->Ln(0);
//Deduction
if($invoice['tds'] != 'not used') {
    $pdf->Cell(70, 6, 'TDS', 1, 0,'C');
    $pdf->Cell(60, 6, ' ', 1, 0, 'C');
    $pdf->Cell(60, 6, $invoice['tds'], 1, 1, 'C');
    $pdf->Ln(0);
}

if($invoice['esi'] != 'not used') {
    $pdf->Cell(70, 6, 'ESI', 1, 0,'C');
    $pdf->Cell(60, 6, '', 1, 0,'C');
    $pdf->Cell(60, 6,number_format($invoice['esi']), 1, 1,'C');
    $pdf->Ln(0);
}
if($invoice['pf'] != 'not used') {
    $pdf->Cell(70, 6, 'PF', 1, 0,'C');
    $pdf->Cell(60, 6,'', 1, 0,'C');
    $pdf->Cell(60, 6,$invoice['pf'], 1, 1,'C');
    $pdf->Ln(0);
}
if($invoice['prof_tax'] != 'not used') {
    $pdf->Cell(70, 6, 'Prof Tax', 1, 0,'C');
    $pdf->Cell(60, 6,'', 1, 0,'C');
    $pdf->Cell(60, 6,$invoice['prof_tax'], 1, 1,'C');
    $pdf->Ln(0);
}

if($invoice['labour_welfare'] != 'not used') {
    $pdf->Cell(70, 6, 'Labour Welfare', 1, 0,'C');
    $pdf->Cell(60, 6,'', 1, 0,'C');
    $pdf->Cell(60, 6,$invoice['labour_welfare'], 1, 1,'C');
    $pdf->Ln(0);
}
$pdf->Cell(70, 6, 'Leave', 1, 0,'C');
$pdf->Cell(60, 6,'', 1, 0,'C');
$pdf->Cell(60, 6,$invoice['e_leave'], 1, 1,'C');
$pdf->Ln(0);//Line break
$pdf->Cell(70, 6, 'Break Violation Fine', 1, 0,'C');
$pdf->Cell(60, 6,'', 1, 0,'C');
$pdf->Cell(60, 6,$invoice['break_violation'], 1, 1,'C');
$pdf->Ln(0);//Line break
$pdf->Cell(70, 6, 'Late Violation Fine', 1, 0,'C');
$pdf->Cell(60, 6,'', 1, 0,'C');
$pdf->Cell(60, 6,$invoice['late_fine'], 1, 1,'C');
$pdf->Ln(0);//Line break
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(166,162,162);
$pdf->Cell(55, 5, 'Earning', 1, 0,'L',TRUE);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(158,81,182);
$pdf->Cell(58, 5,number_format($invoice['earning']), 1, 0,'C',TRUE);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(166,162,162);
$pdf->Cell(25, 5, 'Deduction', 1, 0,'L',TRUE);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(158,81,182);
$pdf->Cell(52, 5,number_format($invoice['deduction']), 1, 1,'C',TRUE);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(166,162,162);
$pdf->Cell(55, 5, 'Gross Salary', 1, 0,'L',TRUE);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(158,81,182);
$pdf->Cell(58, 5,number_format($gross), 1, 0,'C',TRUE);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(166,162,162);
$pdf->Cell(25, 5, 'Net Salary', 1, 0,'L',TRUE);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(158,81,182);
$pdf->Cell(52, 5, number_format($invoice['net_salary']), 1, 1,'C',TRUE);
$pdf->Ln(10);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(25, 5, 'Cheque No:', 0, 0,'L');
$pdf->Cell(25, 5, '', 0, 0);
$pdf->Cell(90, 5, 'Name of Bank:', 0, 1,'R');
//$pdf->Line(31,144,70,144);
//$pdf->Line(149,144,200,144);
$pdf->Cell(25, 8, 'Date:', 0, 1,'L');
//$pdf->Line(20,150,70,150);
$pdf->Cell(25, 5, 'Signature of the Manager:', 0, 0,'L');
//$pdf->Line(57,157,110,157);
$pdf->Cell(25, 5, '', 0, 0);
$pdf->Cell(90, 5, 'Director:', 0, 1,'R');
//$pdf->Line(149,157,200,157);
//$pdf->Output();
$pdf->Output($invoice['emp_name'].'.pdf','I');
ob_end_flush(); 
?>