 <?php 
  session_start();
    include '../dbconfig.php';
    include '../admin/api/index.php';
    $event = new Util();
    $res = $event->refreshPagedata($conn, $_SESSION['devIndex']);
    $months = $_POST['month'];  
    $years= $_POST['year'];    
    $days = cal_days_in_month(CAL_GREGORIAN,$months,$years);
class employee{
    private $empname;
    private $dateBreak;
    private $count;
    private $current_date;
    private $previous_time;
    function __construct() {
        $this->dateBreak = array();
        $this->count = 1;
        $this->previous_time = 0;
        $this->current_date = 1;
        $months = $_POST['month'];  
        $years= $_POST['year'];    
        $days = cal_days_in_month(CAL_GREGORIAN,$months,$years);
        for($i = 0; $i <= $days; $i++){
            $this->dateBreak[$i] = 0;
        }
    }
    
    function getEmpname() {
        return $this->empname;
    }

    function getDateBreak($i) {
        return $this->dateBreak[$i];
    }

    function setEmpname($empname) {
        $this->empname = $empname;
    }

    function setDateBreak($dateBreak,$i) {
        $this->dateBreak[$i] += $dateBreak;
    }
    function getCount() {
        return $this->count;
    }

    function setCount($count) {
        $this->count = $count;
    }

    function getCurrent_date() {
        return $this->current_date;
    }

    function setCurrent_date($current_date) {
        $this->current_date = $current_date;
    }
    function getPrevious_time() {
        return $this->previous_time;
    }

    function setPrevious_time($previous_time) {
        $this->previous_time = $previous_time;
    }
    
    function gettimediff($diff){
        // $diff = abs($end - $start);

        $years   = floor($diff / (365*60*60*24));
        $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        
        $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
        
        $minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
        
        $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60));
        return $hourd.":".$minuts.":".$seconds;
    }
}
$query = mysqli_query($conn, "select *  from company_time where time_id = ".$_POST['shiftid']." ");
            while($ron = mysqli_fetch_array($query))
            {
                $b_time = $ron['company_break_time']; 
                $company_time = $ron['company_in_time']; 
                 $timezone = $ron['timezone']; 
            }
            // $total_time = explode (":", $b_time);
           // $b_time = ($total_time[0] * 60) + $total_time[1];
            date_default_timezone_set($timezone);
$monthName = date("F", mktime(0, 0, 0, $months));

$fromdt= strtotime("First Day Of  $monthName $years");
$todt= strtotime("Last Day of $monthName $years");

$result1 = mysqli_query($conn, "SELECT * FROM employee where e_id = ".$_POST['e_id']." and shift_no = " . $_POST['shiftid'] . " and employee_status = 1 and delete_status = 0");
$k = 0;
$emp = array();
while($row = mysqli_fetch_array($result1))
{
    $employee[$row['e_id']] = $k;
    $id = $row['e_id'];
    $name = $row['e_firstname']." ".$row['e_lastname'];
    array_push($emp, new employee($employee[$row['e_id']]));
    $emp[$employee[$id]]->setEmpname($name);
    $k++;
}
$sql = "SELECT * FROM `break` INNER JOIN employee ON employee.e_id = break.employee_id WHERE  break.employee_id = " . $_POST['e_id'] . " AND employee.shift_no = " . $_POST['shiftid'] . " AND break.break_time BETWEEN $fromdt and $todt";
$result = mysqli_query($conn,$sql);
$diff = 0;
while($row = mysqli_fetch_array($result)){
    $diff = 0;
    $emp_id = $row['employee_id'];
    $break_time = $row['break_time'];
    $break_out = $row['out_time'];
    if($break_out != 'OUT')
    {
        $diff = $break_out - $break_time;
    }
    $diffBreak = $diff;
    if(array_key_exists($emp_id,$employee)){
        $emp[$employee[$emp_id]]->setDateBreak($diffBreak,date('j',$break_time));
    }
}

 $output = '<table id="attendance" class="table table-striped custom-table table-nowrap mb-0">
                        <thead >
                                <tr style="border: 1px solid DodgerBlue;">
                                    <td style="border-bottom: 1px solid DodgerBlue;"><center><h5><b>Sun</b></h5></center></td>
                                    <td style="border-bottom: 1px solid DodgerBlue;"><center><h5><b>Mon</b></h5></center></td>
                                    <td style="border-bottom: 1px solid DodgerBlue;"><center><h5><b>Tue</b></h5></center></td>
                                    <td style="border-bottom: 1px solid DodgerBlue;"><center><h5><b>Wed</b></h5></center></td>
                                    <td style="border-bottom: 1px solid DodgerBlue;"><center><h5><b>Thu</b></h5></center></td>
                                    <td style="border-bottom: 1px solid DodgerBlue;"><center><h5><b>Fri</b></h5></center></td>
                                    <td style="border-bottom: 1px solid DodgerBlue;"><center><h5><b>Sat</b></h5></center></td>
                                </tr>
                            </thead>
                        <tbody>';

 $day = date("D", $fromdt);

 if($day == 'Sun'){
     $d1 = 1;
 }
 elseif($day == 'Mon'){
     $d1 = 2;
 }
 elseif($day == 'Tue'){
     $d1 = 3;
 }
 elseif($day == 'Wed'){
     $d1 = 4;
 }
 elseif($day == 'Thu'){
     $d1 = 5;
 }
 elseif($day == 'Fri'){
     $d1 = 6;
 }
 else{
     $d1 = 7;
 }
 $output .= ' <tr>';
 for ($i = 1; $i < $d1; $i++) {
     $output .= '<td><center></center></td>';
 }
 $count = $d1;
 for ($k = 1; $k <= $days; $k++) {
         if($emp[0]->getDateBreak($k) > $b_time*60)
         {
             $status = '<b>'.$k.'--<span style="color:red">'.ceil($emp[0]->getDateBreak($k)/60).'</span></b>';
         }
         else
         {
             if($emp[0]->getDateBreak($k) < -1)
             {
                 $status = '<b>'.$k.'--<span style="color:green">0</span></b>';
             }
             else
             {
                 $status = '<b>'.$k.'--<span style="color:green">'.ceil($emp[0]->getDateBreak($k)/60).'</span></b>';
             }
         }
     if($count == 0){
         $output .='<tr>';
     }
     $output .= '<td><center>' . $status . '</center></td>';
     if ($count == 7) {
         $output .= '</tr>';
         $count = 0;
     }
     $count++;
 }
 $output .= '</tr>
          </tbody>
        </table>';

echo $output;
?>