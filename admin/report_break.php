 <?php 
 session_start();
include '../dbconfig.php';
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
}
$query = mysqli_query($conn, "select *  from company_time where time_id = ".$_POST['shift_id']." ");
            while($ron = mysqli_fetch_array($query))
            {
                $b_time = $ron['company_break_time']; 
                $company_time = $ron['company_in_time']; 
                 $timezone = $ron['timezone']; 
            }
            $total_time = explode (":", $b_time);
           // $b_time = ($total_time[0] * 60) + $total_time[1];
            date_default_timezone_set($timezone);
$monthName = date("F", mktime(0, 0, 0, $months));

$fromdt= strtotime("First Day Of  $monthName $years");
$todt= strtotime("Last Day of $monthName $years");

$result1 = mysqli_query($conn, "SELECT * FROM employee where admin_id = ".$_POST['admin_id']." and shift_no = " . $_SESSION['shift_id'] . " and employee_status = 1 and delete_status = 0");
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
$sql = "SELECT * FROM `break` INNER JOIN employee ON employee.e_id = break.employee_id WHERE  break.admin_id = " . $_POST['admin_id'] . " AND employee.shift_no = " . $_POST['shift_id'] . " AND break.break_time BETWEEN $fromdt and $todt";
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
$output = ' <table id="attendance" class="table table-bordered custom-table table-nowrap  mb-0">
                        <thead>
                            <tr>
                                <th><b>Employee</b></th>';
                                for($j = 1; $j <= $days; $j++){
                                     $output .='<th width="30px"><b>'.$j.'</b></th>';
                                } 
                $output .= '</tr>
                        </thead>
                    <tbody>';
       for($j = 0; $j < sizeof($emp); $j++){
        $output .='<tr>
                        <td>                                                                                            
                            <h2 class="table-avatar">
                                <b>'.$emp[$j]->getEmpname().'</b>
                            </h2>
                        </td>';
            $count = 0;
            for($k = 1; $k <= $days; $k++){
                if($emp[$j]->getDateBreak($k) > $b_time*60){
                    $output .= '<td><b><span style="font-size:18px;color:red">'.ceil($emp[$j]->getDateBreak($k) / 60).'</span></b></></td>';
                }
                else{
                    if($emp[$j]->getDateBreak($k) > -1){
                        $output .= '<td><h5><b><span style="font-size:18px;color:green">'.ceil($emp[$j]->getDateBreak($k) / 60).'</span></b></h5></td>';
                    }
                    else{
                        $output .= '<td><h5><b><span style="font-size:18px;color:green">0</span></b></h5></td>';
                    }
                }
            } 
    $output .= '</tr>';
} 
$output .='</tbody>
        </table>'; 
echo $output;
?>