<?php
// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$pdf = new Dompdf();
ob_start();

include '../dbconfig.php';

class employee
{
   private $name;
   private $date = array();
   private $name1;
   function __construct($name)
   {
     $this->name = $name;
//     $this->name1 = $name1;
     for($i = 0; $i <= 31; $i++){
         $this->date[] = 0;
     }
   }
           
   
   
    function getName() {
       return $this->name;
   }
   
    function getName1() {
       return $this->name1;
   }
   
    function getDate($i) {
       return $this->date[$i];
   }

   function setName($name) {
       $this->name = $name;
   }
   
   function setName1($name1) {
       $this->name = $name1;
   }

   function setDate($date, $i) {
       $this->date[$i] = $date;
   }
   
}
  
$months = $_GET['month'];  
$years= $_GET['year'];                    

//$months = date('m');  
//$years= date('Y'); 
date_default_timezone_set("Asia/Kolkata");
$monthName = date("F", mktime(0, 0, 0, $months));

$fromdt= strtotime("First Day Of  $monthName $years");
$todt= strtotime("Last Day of $monthName $years");
$result = mysqli_query($conn, "SELECT * FROM attendance where admin_id = 2 and in_time between $fromdt and $todt  ");

$emp = array();

$i = 0;
$result1 = mysqli_query($conn, "SELECT * FROM employee where admin_id = 2 ");
$k = 0;
//$n = 0;

while($row = mysqli_fetch_array($result1))
{
    $employee[$row['e_id']] = $k;
    $id = $row['e_id'];
    $name = $row['e_firstname']." ".$row['e_lastname'];
    array_push($emp, new employee($employee[$row['e_id']]));
    $emp[$employee[$id]]->setName1($name);
    $k++;
}

$temp = 1;
while($row = mysqli_fetch_array($result)){
   $id = $row['employee_id'];
   $name = $row['emp_name'];
   $time = $row['in_time'];
   $date = date("j", $time);
   $emp[$employee[$id]]->setDate($temp,$date);
   $emp[$employee[$id]]->setName1($name);
}
$emp[1]->getName();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Attendence </title>
        <link type="text/css" href="bootstrap.min.css">
    <body>
    <center><h2>Windson Payroll</h2></center>
    <div class="row">
        <div class="col-md-12">
            <div class="title"><h4>report from : <?php echo $months . " " . $years; ?></h4></div>
            <div class="table-responsive">
                <table id="example" class="table table-striped custom-table">
                    <thead class="table-dark">
                        <tr >
                            <th>Employees</th>
                            <?php
                            for ($j = 1; $j <= 31; $j++) {
                                ?>
                                <th><b><?php echo $j; ?></b></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>	
                        <?php
                        for ($j = 0; $j < sizeof($emp); $j++) {
//                                   
                            ?>
                            <tr>

                                <td><b><?php echo $emp[$j]->getName(); ?></b></td>
                                <?php
                                for ($k = 1; $k <= 31; $k++) {
                                    ?>
        <?php if ($emp[$j]->getDate($k) == 1) { ?>

                                        <td><span class="text-success"><b>P</b></span></td>
                                    <?php } else { ?>
                                        <td><span class="text-danger"><b>A</b></span></td>
        <?php }
    } ?>

                            </tr>
<?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </body>
    </head>
</html>
<?php
$html = ob_get_clean();
$pdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$pdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$pdf->render();

// Output the generated PDF to Browser
$pdf->stream('result.php', Array('Attachment'=>0));
?>