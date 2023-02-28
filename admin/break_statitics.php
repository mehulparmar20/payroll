<?php
session_start();
require_once "../dbconfig.php";
// $obj = new Database();
// $conn = $obj->connect();
class employee{
    private $empname;
    private $break_time = 0;
    function getEmpname() {
        return $this->empname;
    }
    function setEmpname($empname) {
        $this->empname = $empname;
    }
    function getBreak_time() {
        return $this->break_time;
    }
    function setBreak_time($break_time) {
        $this->break_time += $break_time;
    }
}
$query = mysqli_query($conn, "select *  from company_time where admin_id = ".$_SESSION['admin_id']." ");
while($ron = mysqli_fetch_array($query))
{
    $b_time = $ron['company_break_time'];
    $company_time = $ron['company_in_time'];
    $timezone = $ron['timezone'];
}
date_default_timezone_set($timezone);
$date = date("Y/m/d");
$fromdt= strtotime("$date 00:00:00");
$todt= strtotime("$date 23:59:59");
$result1 = mysqli_query($conn, "SELECT * FROM employee where admin_id = ".$_SESSION['admin_id']." and shift_no = " . $_SESSION['shift_id'] . " and employee_status = 1 and delete_status = 0");
$k = 0;
$emp = array();
$employee = array();
while($row = mysqli_fetch_array($result1))
{
    $employee[$row['e_id']] = $k;
    $id = $row['e_id'];
    $name = $row['e_firstname']." ".$row['e_lastname'];
    array_push($emp, new employee($employee[$row['e_id']]));
    $emp[$employee[$id]]->setEmpname($name);
    $k++;
}
$sql = "SELECT * FROM `break` INNER JOIN employee ON employee.e_id = break.employee_id WHERE  break.admin_id = " . $_SESSION['admin_id'] . " AND employee.shift_no = " . $_SESSION['shift_id'] . " AND break.break_time BETWEEN $fromdt and $todt";
$result = mysqli_query($conn,$sql);
$k = 0;
while($row = mysqli_fetch_array($result)){
    $diff = 0;
    $emp_id = $row['employee_id'];
    $break_time = $row['break_time'];
    $break_out = $row['out_time'];
    $emp_name = $row['emp_name'];
    if($break_out != 'OUT')
    {
        $diff = $break_out - $break_time;
    }
    $diff = $diff + 0;
    $diffBreak = ceil($diff / 60);
    $emp[$employee[$emp_id]]->setBreak_time($diffBreak);
}
$data = array();
for($j = 0; $j < sizeof($emp); $j++ )
         {
             $no = $emp[$j] -> getBreak_time() + 0;
             $name = $emp[$j]->getEmpname();
             $arr = array(
                 0 => $name,
                 1 => $no
             );
             array_push($data, $arr);
        }
echo json_encode(array("subvalues" => $data, "values" => $data));
?>