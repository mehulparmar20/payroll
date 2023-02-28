<?php
require('fpdf.php');

$pdf = new FPDF('P','mm','A4');

$pdf->AddPage();

//set font to , bold, 14pt
$pdf->Image('nbptechnology.png', 65, 8,80);
$pdf->SetFont('Times','UB',14);

//Cell(width , height , text , border , end line , [align] )

$pdf->Cell(130	,40,'NBP TECHNOLOGY LLP',0,0);
$pdf->Cell(59	,40,'INVOICE',0,1);//end of line

//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',12);

$pdf->Cell(130	,5,'R.O.RANOLI ROAD',0,0);
$pdf->Cell(59	,5,'',0,1);//end of line

$pdf->Cell(130	,5,'TOBACCO COMPOUD',0,0);
$pdf->Cell(25	,5,'Date',0,0);
$pdf->Cell(34	,5,'24/10/2019',0,1);//end of line

$pdf->Cell(130	,5,'Phone +91-9601130819',0,0);
$pdf->Cell(25	,5,'Invoice ',0,0);
$pdf->Cell(34	,5,'448448',0,1);//end of line

$pdf->Cell(130	,5,'Fax +91-2345678',0,0);
$pdf->Cell(25	,5,'Customer ID',0,0);
$pdf->Cell(34	,5,'448441',0,1);//end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189	,10,'',0,1);//end of line

//billing address
$pdf->Cell(100	,5,'Bill to',0,1);//end of line

//add dummy cell at beginning of each line for indentation
$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(90	,5,'NBP Technology',0,1);

$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(90	,5,'Dharmaj',0,1);

$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(90	,5,'9601130819',0,1);

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189	,10,'',0,1);//end of line

//invoice contents
$pdf->SetFont('Arial','B',12);


$pdf->Cell(100	,5,'Earnings',1,0);
$pdf->Cell(92	,5,'Deductions',1,1);//end of line

$pdf->SetFont('Arial','',12);


//summary

$pdf->Cell(100	,5,'Basic Salary',1,0);

$pdf->Cell(92	,5,'Tax Deducted at Source (T.D.S.)',1,1,'L');//end of line


$pdf->Cell(100	,5,'House Rent Allowance (H.R.A.)',1,0);

$pdf->Cell(92	,5,'Provident Fund',1,1,'L');//end of line


$pdf->Cell(100	,5,'Conveyance',1,0);

$pdf->Cell(92	,5,'ESI',1,1,'L');//end of line


$pdf->Cell(100	,5,'Other Allowance',1,0);

$pdf->Cell(92	,5,'Loan',1,1,'L');//end of line

$pdf->Cell(100	,5,'Total Earnings',1,0);

$pdf->Cell(92	,5,'Total Deductions',1,1,'L');//end of line




$pdf->Output();
?>