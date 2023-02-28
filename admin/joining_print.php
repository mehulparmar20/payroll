<?php
// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$pdf = new Dompdf();
ob_start();

include '../dbconfig.php';

$sql = mysqli_query($conn, "select * from joining_employee INNER JOIN company_admin ON company_admin.admin_id = joining_employee.admin_id INNER JOIN employee ON employee.e_id = joining_employee.e_id INNER JOIN company_time ON employee.shift_no = company_time.time_id INNER JOIN employee_profile ON employee_profile.e_id = joining_employee.e_id INNER JOIN designation ON designation.designation_id = employee.designation  where joining_id = '" . $_GET['id'] . "' ");
$no = 0;
while ($row = mysqli_fetch_array($sql)) {
$no++;
// $timesql = mysqli_query($conn, "SELECT * FROM company_time where time_id = " . $row['shift_id'] . " ");
// $time = mysqli_fetch_assoc($sql);
$timezone = $row["timezone"];
// echo $timezone;
// exit();
date_default_timezone_set($timezone);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
       <style>
            .formContainer .container {
                max-width: 900px;
                background: white;
                margin-top: 70px;
                margin-bottom: 0px;
                padding-bottom: 90px;
                padding-left: 50px;
                padding-right: 20px;
                padding-top: 70px;
                display: none;
            }

            .formContainer .container.active {
                display: block;
            }

        </style>
    </head>
    <div class="formContainer">
    <form method="POST" id="form-one" action="offer-letter-word">
        <div class="container active" data-form="1">
            <div class="row">
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <?php echo ucfirst($row['e_firstname']) . '&nbsp;' . ucfirst($row['e_lastname']); ?><br>
                <?php echo $row['emp_address'] ?><br>
                <!--<?php // echo $row['pin_code'] ?><br>-->
                <br>
                Contact No: <?php echo $row['e_phoneno'] ?><br>
                Email ID: <?php echo $row['e_email'] ?><br>
                <div class="dearLine">
                    <p>Dear <span class="pCandidateName1"
                                  style="display: inline"><?php echo ucfirst($row['e_firstname']) . '&nbsp;' . ucfirst($row['e_lastname']); ?>,</span>
                    </p>
                </div>
                <div class="welcomePara" style="text-align: justify;">
                    <p style="-ms-text-size-adjust: auto">After a thorough and comprehensive review, I am pleased to
                        announce that <?php echo $row['company_name']; ?> would like to offer the position
                        of <?php echo $row['designation_name'] ?>.</p>
                    <p>Your role will begin <?php echo date("d-M-Y", $row['joining_date']); ?>. You will report every
                        weekday to <?php echo $row['company_name'] ?> facilitated
                        in <?php echo $row['company_address'] ?> between the hours
                        of <?php $in_time = $row['company_in_time']; $date = date("$in_time:00 T"); echo $date;?> To <?php $outtime = $row['company_out_time']; $odate = date("$outtime:00 T"); echo $odate; ?>
                        , In your role as <?php echo $row['designation_name'] ?>, you
                        will report to the Manager, <?php echo $row['manager_name'] ?>.</p>
                    <p>The salary for this position will be Rs.<?php echo $row['e_salary'] * 12; ?>/- per annum. This
                        amount will be paid by cheque or direct deposit every month with amount of
                        Rs. <?php echo $row['e_salary']; ?>/-</p>
                    <p>The agreement between you and the Company will be classified as and at will which means the
                        Company may terminate the agreement at any time.</p>
                    <p>As the date of joining, the period of 12 month, which is the probation period, you will not
                        attain any benefits from the Co.</p>
                    <p>One month has to be notified to your respective Manager, when want to quit or resign your current
                        position in the Co.</p>
                </div>
                <p style="text-align: justify;">An implication has been brought forward that, you can withdraw your
                    leave counts compiled at once, that is after you have served the Co. for a year or more from the
                    date of joining, in the case you leave before this period, the Co is not liable to pay the compiled
                    leaves.</p>
                <p>Leave taken without informing and notifying your respective manager, in terms of writing, would cause
                    your salary and the leave counts to be deducted, unless of some emergent incidents.</p>
                <p style="text-align: justify">To add to the above, leave taken without informing and notifying your
                    respective manager, on Fridays and Mondays would lead to deduction of salary and the leave counts as
                    well.</p>
                <p style="text-align: justify">All employees of <?php echo $row['company_name']; ?> are expected to aid by policies
                    outlined in our handbook, which covers safety rules conduct and behavior and our business casual
                    dress code.</p>
                    <p>If you choose to accept this position, please sign and return it back to the office.
                    When you report on your first day, you will be asked to present a state issued photo ID.
                </p>
            </div>
            <div class="lastLines">

                <div style="position: inherit;padding-left: 0px;">
                    <div style="text-align: left">
                        <p><span style="display: block">Sincerely,</span>
                            <?php echo $row['manager_name']; ?><br>
                            <?php echo $row['manager_designation']; ?><br>
                            <?php echo $row['company_name'] ?>
                    </div>
                    <div style="text-align: right">
                        <p><span>Accepted by, </span><br>
                        <?php echo $row['manager_name']; ?></p>
                    </div>
                </div>

            </div>
        </div>
    </form>
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
    echo '<h3 style="color: red">Employee Data is incomplete.</h3>';
}
?>