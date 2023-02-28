<?php
ob_start();
include '../dbconfig.php';
include '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
if (isset($_POST['csv'])) {
//     $months = $_POST['e_sal_month'];
//     $years = $_POST['e_sal_year'];
//     $admin_id = $_POST['admin_id'];
//     $f_date = strtotime(date("1-$months-$years 00:00:00"));
//     $t_date = strtotime(date("30-$months-$years 23:59:59"));
//     $sql = mysqli_query($conn, "SELECT * FROM staff_salary INNER JOIN employee_bank_detail on staff_salary.e_id = employee_bank_detail.e_id  where staff_salary.admin_id = '$admin_id' and staff_salary.salary_status = 1 and staff_salary.staff_salary_date between '$f_date' and '$t_date' ");
//     $no = 0;
//     $total = 0;
//     $user_arr[] = array("No","Name","Account No","Bank Name","IFSC Code","Branch Name","Salary");
//     while ($row = mysqli_fetch_assoc($sql)) {
//         $no += 1;
//         $name = $row['emp_name'];
//         $acc_no = $row['eb_account_number'];
//         $bank_name = $row['eb_name'];
//         $ifsc_code = $row['eb_ifsc_code'];
//         $branch_name = $row['eb_branch_name'];
//         $salary = $row['net_salary'];
//         $total += $salary;
//         $user_arr[] = array($no, $name, strval($acc_no), $bank_name, $ifsc_code, $branch_name, $salary);
//     }
//     $user_arr[] = array(" ", " ", " ", " ","","Total Salary", $total);
//     $serialize_user_arr = serialize($user_arr);
//     $filename = 'salary.csv';
//     $export_data = unserialize($serialize_user_arr);

// // file creation
//     $file = fopen($filename, "w");

//     foreach ($export_data as $line) {
//         fputcsv($file, $line);
//     }

//     fclose($file);

$months = $_POST['e_sal_month'];
    $years = $_POST['e_sal_year'];
    $admin_id = $_POST['admin_id'];
    $f_date = strtotime(date("1-$months-$years 00:00:00"));
    $t_date = strtotime(date("30-$months-$years 23:59:59"));
    $bank_name = $_POST['bank_name'];
    $bank = explode(" ",$_POST['bank_name']);
    $sql = mysqli_query($conn, "SELECT * FROM staff_salary INNER JOIN employee_bank_detail on staff_salary.e_id = employee_bank_detail.e_id  where staff_salary.admin_id = '$admin_id' and employee_bank_detail.eb_name like '%$bank[0]%' and staff_salary.salary_status = 1 and staff_salary.staff_salary_date between '$f_date' and '$t_date' ");
    $no = 0;
    $total = 0;
  
    $file = new Spreadsheet();

  $active_sheet = $file->getActiveSheet();

  $active_sheet->setCellValue('A1', 'No');
  $active_sheet->setCellValue('B1', 'Name');
  $active_sheet->setCellValue('C1', 'Account No');
  $active_sheet->setCellValue('D1', 'Bank Name');
  $active_sheet->setCellValue('E1', 'IFSC Code');
  $active_sheet->setCellValue('F1', 'Branch Name');
  $active_sheet->setCellValue('G1', 'Salary');

  $count = 2;

  while($row = $sql->fetch_assoc())
  {
    $no += 1;
    $salary = round($row['net_salary']);
    $active_sheet->setCellValue('A' . $count, $no);
    $active_sheet->setCellValue('B' . $count, $row["emp_name"]);
    $active_sheet->setCellValue('C' . $count, $row["eb_account_number"]);
    $active_sheet->setCellValue('D' . $count, $row["eb_name"]);
    $active_sheet->setCellValue('E' . $count, $row["eb_ifsc_code"]);
    $active_sheet->setCellValue('F' . $count, $row["eb_branch_name"]);
    $active_sheet->setCellValue('G' . $count, $salary);
    $total += $salary;
    $count = $count + 1;
  }
  $active_sheet->setCellValue('A' . $count, '');
  $active_sheet->setCellValue('B' . $count, '');
  $active_sheet->setCellValue('C' . $count, '');
  $active_sheet->setCellValue('D' . $count, '');
  $active_sheet->setCellValue('E' . $count, '');
  $active_sheet->setCellValue('F' . $count, 'Total Salary');
  $active_sheet->setCellValue('G' . $count, $total);
  $writer = IOFactory::createWriter($file, 'Xlsx');
    ob_end_clean();
  $file_name =  'salary.xlsx';

  $writer->save($file_name);

  header('Content-Type: application/x-www-form-urlencoded');

  header('Content-Transfer-Encoding: Binary');

  header("Content-disposition: attachment; filename=\"".$file_name."\"");

  readfile($file_name);

  unlink($file_name);

  exit;

// download
    // header("Content-Description: File Transfer");
    // header("Content-Disposition: attachment; filename=" . $filename);
    // header("Content-Type: application/csv; ");
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=salary.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    readfile($filename);

// deleting file
    unlink($filename);
}

if (isset($_POST['pdf'])) {
    require('pdf/fpdf.php');
    require('../dbconfig.php');
    require('../vendor/autoload.php');
    $months = $_POST['e_sal_month'];
    $years = $_POST['e_sal_year'];
    class PDF extends FPDF
    {
// Page header
        function Header()
        {
// Set font-family and font-size
            $this->SetFont('Times','B',20);

// Move to the right
            $this->Cell(80);

// Break line with given space
            $this->Ln(5);
        }

// Page footer
        function Footer()
        {
// Position at 1.5 cm from bottom
            $this->SetY(-15);

// Set font-family and font-size of footer.
            $this->SetFont('Times', 'I', 8);

// set page number
            $this->Cell(0, 10, 'Page ' . $this->PageNo() .
                '/{nb}', 0, 0, 'C');
        }

        function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
        {
//Get string width
            $str_width=$this->GetStringWidth($txt);

//Calculate ratio to fit cell
            if($w==0)
                $w = $this->w-$this->rMargin-$this->x;
            $ratio = ($w-$this->cMargin*2)/$str_width;

            $fit = ($ratio < 1 || ($ratio > 1 && $force));
            if ($fit)
            {
                if ($scale)
                {
//Calculate horizontal scaling
                    $horiz_scale=$ratio*100.0;
//Set horizontal scaling
                    $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
                }
                else
                {
//Calculate character spacing in points
                    $char_space=($w-$this->cMargin*2-$str_width)/max(strlen($txt)-1,1)*$this->k;
//Set character spacing
                    $this->_out(sprintf('BT %.2F Tc ET',$char_space));
                }
//Override user alignment (since text will fill up cell)
                $align='';
            }

//Pass on to Cell method
            $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

//Reset character spacing/horizontal scaling
            if ($fit)
                $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
        }

//Cell with horizontal scaling only if necessary
        function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
        {
            $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);
        }

//Cell with horizontal scaling always
        function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
        {
            $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,true);
        }

//Cell with character spacing only if necessary
        function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
        {
            $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
        }

//Cell with character spacing always
        function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
        {
//Same as calling CellFit directly
            $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,true);
        }

    }
// Create new object.
    $pdf = new PDF();
    $pdf->AliasNbPages();

// Add new pages
    $date1 = date("d-m-Y");
    $date = date("m-Y");
    $admin_id = $_POST['admin_id'];
    $bank_name = $_POST['bank_name'];
    $bank = explode(" ",$_POST['bank_name']);
    $f_date = strtotime(date("1-$months-$years 00:00:00"));
    $t_date = strtotime(date("30-$months-$years 23:59:59"));
    $sql = mysqli_query($conn, "SELECT * FROM staff_salary INNER JOIN employee_bank_detail on staff_salary.e_id = employee_bank_detail.e_id  where staff_salary.admin_id = '$admin_id' and employee_bank_detail.eb_name like '%$bank[0]%' and staff_salary.salary_status = 1 and staff_salary.staff_salary_date between '$f_date' and '$t_date' ");
    $com = mysqli_query($conn, "SELECT * FROM company_admin WHERE admin_id = '$admin_id' ");
    $month = date("M",$f_date);
    $row = mysqli_fetch_array($com);
    $pdf->AddPage();
    $pdf->Ln(70);
    $pdf->SetFont('Times', '', 14);
    $pdf->Cell(0, 28, 'Dear '.$bank_name, 0, 0, 'L');
    $pdf->Ln(6);
    $pdf->Cell(0, 28, $date1, 0, 0, 'L');
    $pdf->Ln(30);
    $pdf->SetFont('Times', '', 10);
    $pdf->Cell(190, 5, 'Details of accounts for Disbursement of '.$month.'-'.$years.' Salary (Direct Deposit) FOR '.$bank_name.' ACCOUNT', 1, 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(190, 5, 'Company Name: '.$row['company_name'], 1, 0, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Times', 'B', 12);
    $pdf->Cell(10, 5, 'No.', 1, 0, 'C');
    $pdf->Cell(65, 5, 'Name', 1, 0, 'C');
    $pdf->Cell(40, 5, 'Account No', 1, 0, 'C');
    $pdf->Cell(20, 5, 'Amount', 1, 0, 'C');
    $pdf->Cell(55, 5, 'Remarks', 1, 0, 'C');
    $pdf->SetFont('Times', '', 12);
    $pdf->Ln(5);

    $no = 0;
    $fill = false;
    $total_salary = 0;
    while ($row = mysqli_fetch_array($sql)) {
        $name = $row['emp_name'];
        $acc_no = $row['eb_account_number'];
        $bank_name = $row['eb_name'];
        $salary = (int)$row['net_salary'];
        $total_salary += $salary;
        $pdf->Cell(10, 10, ++$no, 1, 'C');
        $pdf->CellFitScale(65, 10, $name, 1, 'C');
        $pdf->Cell(40, 10, $acc_no, 1, '0', 'C');
        $pdf->Cell(20, 10, $salary, 1, '0', 'C');
        $pdf->CellFitScale(55, 10, 'Salary Deposit for the month of '.$month.'-'.$years, 1, 0, 'C');
        $pdf->Ln(10);
        $fill = !$fill;
    }
    $pdf->Cell(10, 10, "", 1, 'C');
    $pdf->Cell(65, 10, "", 1, 'C');
    $pdf->Cell(40, 10, "Total Salary", 1, '0', 'C');
    $pdf->Cell(20, 10, $total_salary, 1, '0', 'C');
    $pdf->Cell(55, 10, " ", 1, 0, 'C');
    $pdf->Output("D",'Salary Report.pdf');
}
ob_end_flush();