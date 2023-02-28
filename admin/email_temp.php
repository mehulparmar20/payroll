<?php

use PHPMailer\PHPMailer\PHPMailer;

require('pdf/fpdf.php');
require('../dbconfig.php');
require('../vendor/autoload.php');
require('../vendor/phpmailer/phpmailer/src/SMTP.php');
class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Set font-family and font-size
        $this->SetFont('Times','B',20);

        // GFG logo image
        $this->Cell(30, 20, 'Windson Payroll', 0, 2, 'L');

        // GFG logo image
        $this->Image('app/img/payroll.png', 160, 8, 40);

        // Move to the right
        $this->Cell(80);

//        // Set the title of pages.
//        $this->Cell(30, 20, 'Welcome to Windson Payroll', 0, 2, 'C');

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
}
// Create new object.
$pdf = new PDF();
$pdf->AliasNbPages();

// Add new pages
$pdf->AddPage();

// Set font-family and font-size.
//$pdf->SetFont('Times','',12);

// Loop to display line number content

$time = time();
$company_id = mysqli_query($conn,"SELECT * FROM company_admin");
//while ($rpw= mysqli_fetch_array($company_id)){
//if($rpw['plan_end'] > $time) {

    $query = mysqli_query($conn, "select *  from company_time where admin_id = 1 ");
    while ($ron = mysqli_fetch_array($query)) {
        $timezone = $ron['timezone'];
    }

    date_default_timezone_set("$timezone");
    $day = date("d-m-Y");

    $fromdt = strtotime("$day 00:00:00");
    $todt = strtotime("$day 23:59:59");

    $atten = mysqli_query($conn, "SELECT * FROM `attendance` WHERE admin_id = 1 and in_time between $fromdt and $todt ");
    $empl = mysqli_query($conn, "SELECT * FROM `employee` WHERE admin_id = 1 and employee_status = 1 and delete_status = 0 ");

    $present_id = array();
    $present_name = array();
    $present_status = array();
    $late_time = array();
    $present_time = array();
    $late_fine = array();
    $count_Present = 0;
    $count_Late = 0;
    while ($row = mysqli_fetch_array($atten)) {
        $present_id[] = $row['employee_id'];
        $present_name[] = $row['emp_name'];
        $present_status[] = $row['present_status'];
        $present_time[] = $row['in_time'];
        $late_time[] = $row['late_time'];
        $late_fine[] = $row['fine'];
        if ($row['present_status'] == 'Late') {
            $count_Late++;
        }
        $count_Present++;
    }
    $emp_id = array();
    $emp_name = array();
    $emp_contact = array();
    $total = 0;
    while ($ro = mysqli_fetch_array($empl)) {
        $total++;
        $emp_id[] = $ro['e_id'];
        $emp_name[$ro['e_id']] = $ro['e_firstname'] . " " . $ro['e_lastname'];
        $emp_contact[$ro['e_id']] = $ro['e_phoneno'];
    }
    $result = array_values(array_diff($emp_id, $present_id));
    $count_Absent = $total - $count_Present;



    $date = date("d M Y");
    $pdf->AddFont('Verdana', '', 'Verdana.php');
    $pdf->AddFont('verdanab', '', 'verdanab.php');
    $pdf->SetFont('verdanab', '', 14);
    $pdf->Cell(110, 28, 'Daily Rport', 0, 0, 'R');
    $pdf->Ln(4);
    $pdf->SetFont('Times', 'B', 12);
    $pdf->Ln(18);
    $pdf->Cell(38, 10, 'Company Name: NBP TECHNOLOGY LLP', 0, 0, 'L');
    $pdf->Cell(158, 10, 'Date: ' . $date, 0, 0, 'R');
    $pdf->SetFont('Times', 'B', 12);
    $pdf->Ln(18);
    $pdf->Cell(50, 5, 'Present', 1, 0, 'C');
    $pdf->Cell(50, 5, 'Absent', 1, 0, 'C');
    $pdf->Cell(45, 5, 'Late', 1, 0, 'C');
    $pdf->Cell(45, 5, 'Total', 1, 0, 'C');
    $pdf->SetFont('Times', '', 12);
    $pdf->Ln(5);
    $pdf->Cell(50, 5, $count_Present, 1, 0, 'C');
    $pdf->Cell(50, 5, $count_Absent, 1, 0, 'C');
    $pdf->Cell(45, 5, $count_Late, 1, 0, 'C');
    $pdf->Cell(45, 5, $total, 1, 0, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('verdanab', '', 11);
    $pdf->Cell(100, 10, 'Present', 0, 0, 'R');
    $pdf->SetFont('Times', 'B', 12);
    $pdf->Ln(12);
    $pdf->Cell(20, 5, 'No', 1, 0, 'C');
    $pdf->Cell(70, 5, 'Name', 1, 0, 'C');
    $pdf->Cell(55, 5, 'Time', 1, 0, 'C');
    $pdf->Cell(45, 5, 'Status', 1, 0, 'C');
    $pdf->Ln(5);
    date_default_timezone_set('Asia/Kolkata');
    $fill = false;
    for ($i = 0; $i < sizeof($present_id); $i++) {
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(20, 5, ($i + 1), 1, 0, 'C');
        $pdf->Cell(70, 5, $present_name[$i], 1, 0, 'C');
        $pdf->Cell(55, 5, date("d-M-Y h:i:s A",$present_time[$i]), 1, 0, 'C');
        $pdf->SetFont('Times', 'B', 12);
        $pdf->SetTextColor(19, 167, 47);
        $pdf->Cell(45, 5, 'Present', 1, 0, 'C');
        $pdf->SetFont('Times', '', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln();
        $fill = !$fill;
    }
    $pdf->SetFont('verdanab', '', 11);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(5);
    $pdf->Cell(95, 10, 'Late', 0, 0, 'R');
    $pdf->SetFont('Times', 'B', 12);
    $pdf->Ln(12);
    $pdf->Cell(20, 5, 'No', 1, 0, 'C');
    $pdf->Cell(70, 5, 'Name', 1, 0, 'C');
    $pdf->Cell(35, 5, 'Late Time', 1, 0, 'C');
    $pdf->Cell(32, 5, 'Late Fine', 1, 0, 'C');
    $pdf->Cell(33, 5, 'Status', 1, 0, 'C');
    $pdf->Ln(5);
    $fill = false;
    $no = 1;
    for ($i = 0; $i < sizeof($present_id); $i++) {
        if ($present_status[$i] == 'Late') {
            $pdf->SetFont('Times', '', 12);
            $pdf->Cell(20, 5, $no++, 1, 0, 'C');
            $pdf->Cell(70, 5, $present_name[$i], 1, 0, 'C');
            $pdf->SetTextColor(225, 6, 6);
            $pdf->Cell(35, 5, $late_time[$i] . ' Min', 1, 0, 'C');
            $pdf->Cell(32, 5, $late_fine[$i] . ' Rupees', 1, 0, 'C');
            $pdf->SetFont('Times', 'B', 12);
            $pdf->Cell(33, 5, 'Late', 1, 0, 'C');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Ln();
            $fill != $fill;
        }
    }

    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('verdanab', '', 11);
    $pdf->Cell(100, 10, 'Absent', 0, 0, 'R');
    $pdf->SetFont('Times', 'B', 12);
    $pdf->Ln(12);
    $pdf->Cell(15, 5, 'No', 1, 0, 'C');
    $pdf->Cell(90, 5, 'Name', 1, 0, 'C');
    $pdf->Cell(50, 5, 'Contact No', 1, 0, 'C');
    $pdf->Cell(35, 5, 'Status', 1, 0, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Times', '', 12);
    $fill = false;
    for ($i = 0; $i < sizeof($result); $i++) {
        $pdf->Cell(15, 5, ($i + 1), 1, 0, 'C');
        $pdf->Cell(90, 5, $emp_name[$result[$i]], 1, 0, 'C');
        $pdf->Cell(50, 5, $emp_contact[$result[$i]], 1, 0, 'C');
        $pdf->SetFont('Times', 'B', 12);
        $pdf->SetTextColor(225, 6, 6);
        $pdf->Cell(35, 5, 'Absent', 1, 0, 'C');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $pdf->Ln();
        $fill = !$fill;
    }
//    $file_name = "Attechments/".$rpw['company_name'].".pdf";
//    $file = $pdf->Output('F',"Attechments/".$rpw['company_name']);
//    ob_end_flush();
//    file_put_contents($file_name, $file);
   $file = $pdf->Output('S');
$mail = new PHPMailer;
$mail->IsSMTP();        //Sets Mailer to send message using SMTP
#$mail->Host = 'smtp.pepipost.com';  // Specify main and backup SMTP servers
#$mail->SMTPAuth = true;                               // Enable SMTP authentication
#$mail->Username = 'support1k21wk';                 // SMTP username
#$mail->Password = 'support1k21wk_b67f1ee573b7dbac3ea05f75b18e2fc4';                           // SMTP password
#$mail->Port = 587;                                    // TCP port to connect to
$mail->Host = 'localhost';  // Specify main and backup SMTP servers
$mail->SMTPAuth = false;                               // Enable SMTP authentication
$mail->Username = 'windsonpayroll@gmail.com';                 // SMTP username
$mail->Password = 'Windson@123';                           // SMTP password
$mail->SMTPSecure = false;                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 25;                                 // TCP port to connect to
$mail->setFrom('support@windsonpayroll.com', 'Windson Payroll');
$mail->addAddress('pshyam176@gmail.com');
$mail->addReplyTo('pshyam176@gmail.com', 'Shyam');

$mail->addStringAttachment($file,'DailyReport.pdf');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Send Mail with PDF';
$mail->Body = '<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
        <link href="https://fonts.googleapis.com/css?family=Work+Sans:200,300,400,500,600,700" rel="stylesheet">
        <!-- CSS Reset : BEGIN -->
        <style>
html,
        body {
    margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            background: #f1f1f1;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
    -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
    margin: 0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
    mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. */
        table {
    border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
    -ms-interpolation-mode:bicubic;
        }

        /* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
        a {
    text-decoration: none;
        }

        /* What it does: A work-around for email clients meddling in triggered links. */
        *[x-apple-data-detectors],  /* iOS */
        .unstyle-auto-detected-links *,
        .aBn {
    border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
        .a6S {
    display: none !important;
            opacity: 0.01 !important;
        }

        /* What it does: Prevents Gmail from changing the text color in conversation threads. */
        .im {
    color: inherit !important;
        }

        img.g-img + div {
    display: none !important;
        }
        @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
    u ~ div .email-container {
        min-width: 320px !important;
        }
        }
        /* iPhone 6, 6S, 7, 8, and X */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
    u ~ div .email-container {
        min-width: 375px !important;
            }
        }
        /* iPhone 6+, 7+, and 8+ */
        @media only screen and (min-device-width: 414px) {
    u ~ div .email-container {
        min-width: 414px !important;
            }
        }
        </style>
        <!-- CSS Reset : END -->

                            <!-- Progressive Enhancements : BEGIN -->
    <style>

        .primary{
    background: #f34949;
}
        .bg_white{
    background: #ffffff;
}
        .bg_light{
    background: #f7fafa;
}
        .bg_black{
    background: #000000;
}
        .bg_dark{
    background: rgba(0,0,0,.8);
}
        .email-section{
    padding:2.5em;
        }

        /*BUTTON*/
        .btn{
    padding: 3px 10px;
            display: inline-block;
        }
        .btn.btn-primary{
    border-radius: 5px;
            background: #f34949;
            color: #ffffff;
        }
        .btn.btn-white{
    border-radius: 5px;
            background: #ffffff;
            color: #000000;
        }
        .btn.btn-white-outline{
    border-radius: 5px;
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
        }
        .btn.btn-black-outline{
    border-radius: 0px;
            background: transparent;
            border: 2px solid #000;
            color: #000;
            font-weight: 700;
        }
        .btn-custom{
    color: rgba(0,0,0,.3);
    text-decoration: underline;
        }

        h1,h2,h3,h4,h5,h6{
    font-family: "Work Sans", sans-serif;
            color: #000000;
            margin-top: 0;
            font-weight: 400;
        }

        body{
    font-family: "Work Sans", sans-serif;
            font-weight: 400;
            font-size: 15px;
            line-height: 1.8;
            color: rgba(0,0,0,.4);
        }

        a{
    color: #f34949;
}

        table{
}
        /*LOGO*/

        .logo h1{
    margin: 0 0 20px 0;
        }
        .logo h1 a{
    color: #000;
    font-size: 24px;
            font-weight: 300;
            font-family: "Work Sans", sans-serif;
        }

        /*HERO*/
        .hero{
    position: relative;
    z-index: 0;
        }

        .hero .text{
    color: rgba(0,0,0,.3);
}
        .hero .text h2{
    color: #000;
    font-size: 34px;
            margin-bottom: 15px;
            font-weight: 300;
            line-height: 1.2;
        }
        .hero .text h3{
    font-size: 24px;
            font-weight: 200;
        }
        .hero .text h2 span{
    font-weight: 600;
            color: #000;
        }


        /*PRODUCT*/
        .product-entry{
    display: block;
    position: relative;
    float: left;
    padding-top: 20px;
        }
        .product-entry .text{
    width: calc(100% - 125px);
            padding-left: 20px;
        }
        .product-entry .text h3{
    margin-bottom: 0;
            padding-bottom: 0;
        }
        .product-entry .text p{
    margin-top: 0;
        }
        .product-entry .text span{
    color: #000;
    font-size: 14px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 10px;
        }
        .product-entry img, .product-entry .text{
    float: left;
}

        ul.social{
    padding: 0;
}
        ul.social li{
    display: inline-block;
    margin-right: 10px;
        }

        /*FOOTER*/

        .footer{
    color: rgba(0,0,0,.5);
}
        .footer .heading{
    color: #000;
    font-size: 20px;
        }
        .footer ul{
    margin: 0;
    padding: 0;
}
        .footer ul li{
    list-style: none;
            margin-bottom: 10px;
        }
        .footer ul li a{
    color: rgba(0,0,0,1);
}


        @media screen and (max-width: 500px) {


}


    </style>
        </head>

            <body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f1f1;">
                <center style="width: 100%; background-color: #f1f1f1;">
                    <div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
                                        &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
                    </div>
                        <div style="max-width: 600px; margin: 0 auto;" class="email-container">
                            <!-- BEGIN BODY -->
                                <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                                    <tr>
                                        <td valign="top" class="bg_white" style="padding: 1em 2.5em 0 2.5em;">
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tr>
                                                    <td class="logo" style="text-align: left;">
                                                        <h1><a href="#"><img src="http://osttbrokeragellc.com/Nbp_Payroll/admin/app/img/payroll.png" alt="Windson Payroll" width="150" height="60"></a></h1>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr><!-- end tr -->
                                    <tr>
                                        <td valign="middle" class="hero bg_white" style="padding: 2em 0 2em 0;">
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tr>
                                                    <td style="padding: 0 2.5em; text-align: left;">
                                                        <div class="text">
                                                            <h2>Today Attendance Report</h2>
                                                            <h3>In this report you will get Absent Employee and Present Employee name. Also which employee is late today is show in report.</h3>
                                                            <h3>Attechment is Attach in this mail.</h3>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                                </td>
                                        </tr><!-- end tr -->
                                        <tr>
                                            <td class="bg_white" style="padding: 0 0 4em 0;">
                                                <table class="bg_white" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
                                                        <td valign="middle" width="70%" style="text-align:left; padding: 0 2.5em;">
                                                            <div class="product-entry">
                                                                <div class="text">
                                                                    <h3>Present</h3>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td valign="middle" width="30%" style="text-align:center; padding-right: 2.5em;">
                                                            <span class="price" style="color: #f34949; font-size: 20px; display: block;">'.$count_Present.' Employees</span>
                                                        </td>
                                                    </tr>
                                                    <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
                                                        <td valign="middle" width="70%" style="text-align:left; padding: 0 2.5em;">
                                                            <div class="product-entry">
                                                                <div class="text">
                                                                    <h3>Absent</h3>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td valign="middle" width="30%" style="text-align:center; padding-right: 2.5em;">
                                                            <span class="price" style="color: #f34949; font-size: 20px; display: block;">'.$count_Absent.' Employees</span>
                                                        </td>
                                                    </tr>
                                                    <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
                                                        <td valign="middle" width="70%" style="text-align:left; padding: 0 2.5em;">
                                                            <div class="product-entry">
                                                                <div class="text">
                                                                    <h3>Late</h3>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td valign="middle" width="30%" style="text-align:center; padding-right: 2.5em;">
                                                            <span class="price" style="color: #f34949; font-size: 20px; display: block;">'.$count_Late.' Employees</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </tr><!-- end tr -->
                                            <!-- 1 Column Text + Button : END -->
                                        </table>
                                    </div>
                                </center>
                            </body>
                            </html>';

    if($mail->Send())        //Send an Email. Return true on success or false on error
    {
        echo '<label class="text-success">Email Sent Successfully...</label>';
    }else{
        echo '<label class="text-success">Email Not Sent Successfully...</label>'.$mail->ErrorInfo;;
    }

//}
//    unlink($file_name);
//}
?>
