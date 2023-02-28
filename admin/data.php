<?php
session_start();
include '../dbconfig.php';
require_once ("api/index.php");
$event = new Util();
$res = $event->refreshPagedata($conn, $_SESSION['devIndex']);
$months = $_POST['month'];  
    $years= $_POST['year'];    
    $days = cal_days_in_month(CAL_GREGORIAN,$months,$years);
    
class employee
{
   private $name;
   private $date = array();
   private $punchDate = array();
   private $name1;
   private $id;
   private $a_id = array();
    
   function __construct($name)
   {
        $months = $_POST['month'];  
        $years= $_POST['year'];    
        $days = cal_days_in_month(CAL_GREGORIAN,$months,$years);
     $this->name = $name;
//     $this->name1 = $name1;
     for($i = 0; $i <= $days; $i++){
         $this->date[] = 0;
     }
     for($i = 0; $i <= $days; $i++){
         $this->a_id[] = 0;
     }
   }
   
    function getName() {
       return $this->name;
   }
   
    function getName1() {
       return $this->name1;
   }
   function getA_id($i) {
       return $this->a_id[$i];
   }

   function setA_id($i, $a_id) {
       $this->a_id[$i] = $a_id;
   }

   function getDate($i) {
    return $this->date[$i];
   }
   function getId() {
       return $this->id;
   }

   function setId($id) {
       $this->id = $id;
   }
   
   function getPunchDate($i) {
       return $this->punchDate[$i];
   }

   function setPunchDate($date, $i) {
       $this->punchDate[$i] = $date;
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
$query = mysqli_query($conn, "select *  from company_time where time_id = ".$_SESSION['shift_id']." ");
            while($ron = mysqli_fetch_array($query))
            {
                $break_time = $ron['company_break_time']; 
                $company_time = $ron['company_in_time']; 
                 $timezone = $ron['timezone']; 
            }
            
            date_default_timezone_set("$timezone");
$monthName = date("F", mktime(0, 0, 0, $months));

$fromdt= strtotime("First Day Of  $monthName $years");
$todt= strtotime("Last Day of $monthName $years 23:59:00");

$result = mysqli_query($conn, "SELECT * FROM attendance INNER JOIN employee ON employee.e_id = attendance.employee_id WHERE attendance.admin_id = " . $_POST['admin_id'] . " AND employee.shift_no = " . $_SESSION['shift_id'] . " and attendance.in_time between $fromdt and $todt  ");

//echo "SELECT * FROM attendance where admin_id = ".$_POST['admin_id']." and in_time between $fromdt and $todt  ";
$emp = array();

$i = 0;
$result1 = mysqli_query($conn, "SELECT * FROM employee  where admin_id = ".$_POST['admin_id']." and shift_no = " . $_SESSION['shift_id'] . "  and employee_status = 1 and delete_status = 0");
$k = 0;
//$n = 0;

while($row = mysqli_fetch_array($result1))
{
    $employee[$row['e_id']] = $k;
    $id = $row['e_id'];
    $emp_cardid = $row['emp_cardid'];
    $name = $row['e_firstname']." ".$row['e_lastname'];
    array_push($emp, new employee($employee[$row['e_id']]));
    $emp[$employee[$id]]->setName1($name);
    $emp[$employee[$id]]->setId($emp_cardid);
    $k++;
}
$weeked = [];
$working = mysqli_query($conn, "Select * from working_days where admin_id = ".$_POST['admin_id']);
$info = mysqli_fetch_array($working);
$day1 = $info['mon'];
$day2 = $info['tue'];
$day3 = $info['wed'];
$day4 = $info['thu'];
$day5 = $info['fri'];
$day6 = $info['sat'];
$day7 = $info['sun'];

 if($day1 == 0){
     $weeked[] = 'Monday';
 }
 if($day2 == 0){
     $weeked[] = 'Tuesday';
 }
 if($day3 == 0){
     $weeked[] = 'Wednesday';
 }
 if($day4 == 0){
     $weeked[] = 'Thursday';
 }
 if($day5 == 0){
     $weeked[] = 'Friday';
 }
 if($day6 == 0){
     $weeked[] = 'Saturday';
 }
 if($day7 == 0){
     $weeked[] = 'Sunday';
 }
 
 $working_days = $day1 + $day2 + $day3 + $day4 + $day5 + $day6 + $day7;

 // go to inside if when working days is 5 and saturday and sunday is 0
 if($working_days == 5 && $day6 == 0 && $day7 == 0){
     $startdate = strtotime($years . '-' . $months . '-01');
     $enddate = strtotime('+' . (date('t',$startdate) - 1). ' days',$startdate);
     $currentdate = $startdate;
     //get the total number of days in the month
     $return = intval((date('t',$startdate)),10);
     //loop through the dates, from the start date to the end date
     while ($currentdate <= $enddate)
     {
         //if you encounter a Saturday or Sunday, remove from the total days count
         if ((date('D',$currentdate) == 'Sat') || (date('D',$currentdate) == 'Sun'))
         {
             $return = $return - 1;
         }
         $currentdate = strtotime('+1 day', $currentdate);
     }
 }
 // going inside elseif when working days is 6
 elseif($working_days == 6){
     $startdate = strtotime($years . '-' . $months . '-01');
     $enddate = strtotime('+' . (date('t',$startdate) - 1). ' days',$startdate);
     $currentdate = $startdate;
     //get the total number of days in the month
     $return = intval((date('t',$startdate)),10);
     //loop through the dates, from the start date to the end date
     while ($currentdate <= $enddate)
     {
         //if you encounter a Saturday or Sunday, remove from the total days count
         if (date('D',$currentdate) == 'Sun')
         {
             $return = $return - 1;
         }
         $currentdate = strtotime('+1 day', $currentdate);
     }
 }
 // going indse else part when total working days is 7   and here return  is indicate that total working days
 else{
     $return = $days;
 }

$temp = 1;
$half = 2;
$absent = 0;
while($row = mysqli_fetch_array($result)){
   $id = $row['employee_id'];
   $a_id = $row['Attandance_id'];
   $name = $row['emp_name'];
   $time = $row['in_time'];
   $date = date("j", $time);
   $punchDate = date("l jS \of F h:i:s A", $time);
    if(array_key_exists($id,$employee)){
       if ($row['attendance_status'] == "Full") {
           $emp[$employee[$id]]->setDate($temp, $date);
           $emp[$employee[$id]]->setName1($name);
           $emp[$employee[$id]]->setA_id($date, $a_id);
           $emp[$employee[$id]]->setPunchDate($date, $a_id);
       } elseif ($row['attendance_status'] == "Half") {
           $emp[$employee[$id]]->setDate($half, $date);
           $emp[$employee[$id]]->setName1($name);
           $emp[$employee[$id]]->setA_id($date, $a_id);
       } else {
           $emp[$employee[$id]]->setDate($absent, $date);
           $emp[$employee[$id]]->setName1($name);
       }
}
}
$thDate = '';
$thDays = '';
for($j = 1; $j <= $days; $j++){
    $date = $years."-".$months."-".$j;
    $day = date('D', strtotime($date));
    $thDays .= '<th><b>'.$day.'</b></th>';
    $thDate .= '<th><b>'.$j.'</b></th>';
} 

    $output = ' <table id="attendance" class="table table-bordered custom-table table-nowrap">
                        <thead>
                                <tr>
                                    <th style=".DTFC_LeftBodyLiner { overflow-x: hidden; }"><b>'.$monthName.'</b></th>
                                    '.$thDays.'
                                </tr>
                                <tr>
                                    <th style=".DTFC_LeftBodyLiner { overflow-x: hidden; }"><b>Employee</b></th>
                                    '.$thDate.'
                                </tr>
                            </thead>
                            <tbody>';
    // echo $months." == ".(int)date('m')." && ".$years." == ".date('Y');
    if($months == (int)date('m') && $years == date('Y')){
        $todaydate = date('d');
    }else{
        $todaydate = $days;
    }
    $status = '';
        for($j = 0; $j < sizeof($emp); $j++)
        { 
            
        $output .= '<tr>
                        <td style=".DTFC_LeftBodyLiner { overflow-x: hidden; }"><b>'.$emp[$j]->getName().'</b></td>';
                        for($k = 1; $k <= $days; $k++)
                            {
                                $date = $years."-".$months."-".$k;
                                $day = date('l', strtotime($date));
                                if($emp[$j]->getDate($k) == 1){
                                    $output .='<td><a  style="color:green"  onclick="attendance_info(this.id)" id="'.$emp[$j]->getA_id($k).'" data-toggle="tooltip" data-placement="top" title="Present!" ><b>P</b></a></td>';
                                }elseif($emp[$j]->getDate($k) == 2) {
                                    $output .='<td><a   style="color:blue" onclick="attendance_info(this.id)" id="'.$emp[$j]->getA_id($k).'" data-toggle="tooltip" data-placement="top" title="Half Day!"><b>H</b></a></td>';
                                }elseif(in_array($day, $weeked)){
                                    $color = '#5304ba';
                                    $tooltip = 'Weekend';
                                    $output .='<td><a  id="'.$emp[$j]->getId().','.$emp[$j]->getName().','.$k.'" class="add_attendance" style="color:'.$color.'" data-toggle="tooltip" data-placement="top" title="'.$tooltip.'" >W</a></td>';
                                }else{
                                    if($k <= $todaydate){
                                        $color = 'red';
                                        $tooltip = 'data-toggle="tooltip" data-placement="top" title="Absent."';
                                    }else{
                                        $color = '#d9d9e2';
                                        $tooltip = '';
                                    }
                                    
                                    $output .='<td><a  id="'.$emp[$j]->getId().','.$emp[$j]->getName().','.$k.'" class="add_attendance" style="color:'.$color.'" '.$tooltip.' ><b>A</b></a></td>';
                                }
                            } 
                                    
            $output .='</tr>';
        }
        $output .= '</tbody>
                        </table>';
echo $output;