<?php
// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$pdf = new Dompdf();
ob_start();
include '../dbconfig.php';
$sql = mysqli_query($conn, "select * from resignation INNER JOIN company_admin ON company_admin.admin_id = resignation.admin_id INNER JOIN employee ON employee.e_id = resignation.e_id INNER JOIN departments ON departments.departments_id = employee.department INNER JOIN designation ON designation.designation_id = employee.designation  where resignation_id = '" . $_GET['id'] . "' ");
$no = 0;
while ($row = mysqli_fetch_array($sql)) {
    $no++;
    $join_date = date_create(date('d-m-Y',$row['join_date']));
    $resign_date = date_create(date('d-m-Y',$row['resignation_date']));
    $total_year = date_diff($join_date,$resign_date);
    $year = $total_year->format('%y year, %m month and %d days');

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta content='text/html; charset=UTF-8' http-equiv='Content-Type'/>
        <style>
            .formContainer .container {
                max-width: 900px;
                background: white;
                margin-top: 50px;
                margin-bottom: 0px;
                padding-bottom: 90px;
                padding-left: 50px;
                padding-right: 20px;
                padding-top: 50px;
                display: none;
            }

            .formContainer .container.active {
                display: block;
            }

        </style>
    </head>
    <div class="formContainer">
        <form method="POST" id="form-one" action=>
            <div class="container active" data-form="1">
                <div class="row">
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="addressTop col-md-6" style="position: inherit">
                        <div class="dearLine">
                            <p>Dear <span class="pCandidateName1" style="display: inline">Sir or Madam,</span>
                            </p>
                        </div>
                        <div class="welcomePara" style="text-align: justify;">
                            <p>Please accept this letter as formal notification that I am resigning from my position as
                                <b><?php echo $row['designation_name']; ?></b> with
                                <b><?php echo $row['company_name']; ?></b>. My last day will be
                                <b><?php echo date("l ,d F Y", $row['resignation_date']); ?></b></p>
                            <p>Reason: <b><?php echo $row['reason']; ?></b></p>
                            <p>Thank you so much for the opportunity to work in this position for the past <?php echo $year; ?>. I’ve
                                greatly enjoyed and appreciated the opportunities I’ve had to grow our team and work
                                with my fellow colleagues, and I had great experience to working with this company and 
                                which I will certainly take with me throughout my career.</p>
                            <p>I had done everything possible to wrap up my duties in the past two weeks. Please let me
                                know if there’s anything else I can do to help.</p>
                            <p>I wish the company continued success, and I hope to stay in touch in the future.</p>
                        </div>
                        <div class="lastLines">
                            <div class="col-md-7" style="position: inherit;padding-left: 0px;">
                                <p><span style="display: block">Sincerely,</span><br>
                                    <?php echo ucfirst($row['e_firstname']) . '&nbsp;' . ucfirst($row['e_lastname']); ?>
                                    <br>
                                    <?php echo $row['departments_name']; ?><br>
                                    <?php echo $row['company_name'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </body>
    </html>
    <?php
}
if($no == 0){
    echo '<h4 style="color: red">Employee Profile is incomplete</h4>';
}
$html = ob_get_clean();
$pdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$pdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$pdf->render();

// Output the generated PDF to Browser
$pdf->stream('result.php', Array('Attachment' => 0));
?>