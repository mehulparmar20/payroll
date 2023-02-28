<?php
// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$pdf = new Dompdf();
ob_start();
?>
<html>
    <head>
        <title>Leave Report</title>
    </head>
    <body>
       <table class="w3-table">
            <tr>
                <th><p>Company Name:<br></th>
                <th>Date:<br></p></th>
                <th>Employees</th>
                <th>Leave</th>
            </tr>
            <tr>
                <td>NBP LLP</td>
                <td>01-12-2019 to 31-12-2019</td>
                <td>Shyam Patel</td>
                <td>06</td>
            </tr>
           </table>
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
$pdf->stream('result.php', Array('Attachment'=>0));
?>