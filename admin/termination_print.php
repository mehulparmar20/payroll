<?php
// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$pdf = new Dompdf();
ob_start();

include '../dbconfig.php';

$company = mysqli_query($conn,"select * from termination INNER JOIN company_admin ON company_admin.admin_id = termination.admin_id INNER JOIN employee ON employee.e_id = termination.e_id INNER JOIN departments ON departments.departments_id = employee.department INNER JOIN designation ON designation.designation_id = employee.designation  where termination_id = '" . $_GET['id'] . "' ");
$row = mysqli_fetch_array($company);
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta content='text/html; charset=UTF-8' http-equiv='Content-Type'/>
        <style>
            .formContainer .container{
                max-width: 900px; background: rgba(255, 255, 255, 0.25);margin-top: 50px;margin-bottom: 0px;padding-bottom: 90px;padding-left: 50px;padding-right: 20px;padding-top: 50px;display: none;
            }
            .formContainer .container.active{
                display: block;
            }

        </style>
    </head>
    <body>
        <div class="formContainer"  >
            <div id="print" class="container active" data-form="1">
                <div class="row" style="margin-top: 80px;">
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="addressTop col-md-6" style="position: inherit" data-step="1" data-intro="Candidate Address">
                        <p>Dear <span class="pCandidateName1" style="display: inline"><b><?php echo ucfirst($row['e_firstname']) . '&nbsp;' . ucfirst($row['e_lastname']); ?>,</b></span></p>
                        <div style="text-align: justify;">
                            <p>This letter is to inform you that your employment with <b><?php echo $row['company_name'] ?></b> will end as of &nbsp;<b><?php echo date("d/m/Y",$row['termination_date']); ?></b>.</p>
                        
                            <p>You have been terminated for the following reason(s):</p>
                            <p style="text-align: justify;"><b><?php echo $row['reason']; ?></b></p>
                       
                            <p>You are requested to return all the company property which you used.</p>
                       
                            <p>Also, please keep in mind that you have signed company contract so do not try to leak the company confidential Information.</p>
                        
                            <p>If you have questions about policies you have signed,  
                                or returning company property, please contact <b><?php echo $row['manager_name']; ?></b>.</p>
                        </div>
                        <div class="col-md-7" style="position: inherit;padding-left: 0px;">
                            <p><span style="display: block">Sincerely,</span>
                                <b><?php echo $row['manager_name'] ?></b><br>
                                <b><?php echo $row['manager_designation'] ?></b><br>
                                <b>Date : <?php echo date("d/m/Y") ?></b><br>
                        </div>
                    </div>
                </div>
            </div>    
        </div>    
    </body>
</html>
    <?php
    $html = ob_get_clean();
    $pdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
    $pdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
    $pdf->render();

// Output the generated PDF to Browser
    $pdf->stream('result.php', Array('Attachment' => 0));
?>