<?php
// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$pdf = new Dompdf();
ob_start();

include '../dbconfig.php';
$sql = mysqli_query($conn, " select * from experience INNER JOIN employee ON employee.e_id = experience.e_id INNER JOIN company_admin ON company_admin.admin_id = experience.admin_id INNER JOIN designation ON designation.designation_id = employee.designation where experience_id = '" . $_GET['id'] . "' ");
$no = 0;
while ($row = mysqli_fetch_array($sql)) {
$no++;
    $from_date = date_create(date("d-m-Y", $row['period_from']));
    $to_date = date_create(date("d-m-Y", $row['period_to']));
    $total_year = date_diff($from_date,$to_date);
    $year = $total_year->format('%y year, %m month and %d days');
    if ($row['e_gender'] == "Male") {
        $gender = 'he';
        $gender1 = 'his';
        $gender2 = 'him';
    } elseif ($row['e_gender'] == "Female") {
        $gender = 'she';
        $gender1 = 'her';
        $gender2 = 'him';
    } else {
        $gender = 'it';
        $gender1 = 'its';
        $gender2 = 'it';
    }
    ?>
    <html lang="en">
        <head>

            <style>
                .formContainer .container{
                    max-width: 900px; background: white;margin-top: 50px;margin-bottom: 0px;padding-bottom: 90px;padding-left: 50px;padding-right: 20px;padding-top: 50px;display: none;
                }
                .formContainer .container.active{
                    display: block;
                }

            </style>
        </head>
        <div class="formContainer" style="margin-bottom: 60px;">
            <div id="print" class="container active" data-form="1">
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <div class="row">
                    <div class="addressTop col-md-6" style="position: inherit" data-step="1" data-intro="Candidate Address">
                        
                        <center><h3><b> Experience Certificate</b></h3></center>


                        <div class="welcomePara" style="text-align: justify;">
                            <p>This is to certify that <b><?php echo ucfirst($row['e_firstname']) . '&nbsp;' . ucfirst($row['e_lastname']); ?></b> worked as <b><?php echo $row['designation_name']; ?>. </b>
                                <?php echo ucfirst($gender); ?> worked for our company for <b><?php echo $year; ?>  </b> with our entire satisfaction.</p>
                        </div>
                        <div class="jobDetail" style="text-align: justify;">
                            During <?php echo $gender1; ?> working period we found <?php echo $gender2; ?> sincere, honest, hardworking, dedicated employee with
                            a professional attitude and having very good job knowledge.
                        </div>
                        <p style="text-align: justify;">
                            <?php echo ucfirst($gender); ?> is amiable in nature and character as well. We have no objection to allow <?php echo $gender2; ?> in any better
                            position and have no liabilities in our company.</p>
                    </div>
                    <div class="lastLines">
                        <p style="text-align: justify;">We wish <?php echo $gender2; ?> success in life.</p>
                        <div class="col-md-7" style="position: inherit;padding-left: 0px;">
                            <p><span style="display: block">Sincerely,</span><br>
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
}
if($no == 0){
    echo '<h4 style="color: red">Employee Profile is incomplete</h4>';
}
?>