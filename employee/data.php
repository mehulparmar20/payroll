<?php
session_start();
include '../dbconfig.php';
include '../admin/api/index.php';
$event = new Util();
$res = $event->refreshPagedata($conn, $_SESSION['devIndex']);
    $months = $_POST['month'];
    $days = date('t',strtotime($months));
class employee
{
   private $name;
   private $date = array();
   private $name1;
   function __construct($name)
   {
       $months = $_POST['month'];
       $days = date('t',strtotime($months));
     $this->name = $name;
     for($i = 0; $i <= $days; $i++){
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
    
                




$emp = array();

$result1 = mysqli_query($conn, "SELECT * FROM employee where e_id = " . $_SESSION['e_id'] . " ");


$row = mysqli_fetch_array($result1);
    $employee[$row['e_id']] = 0;
    $id = $row['e_id'];
    $shift = $row['shift_no'];
    $name = $row['e_firstname'] . " " . $row['e_lastname'];
    array_push($emp, new employee($employee[$row['e_id']]));
    $emp[$employee[$id]]->setName1($name);


$query = mysqli_query($conn, "select *  from company_time where admin_id = ".$_POST['admin_id']." AND time_id = '$shift' ");
while($ron = mysqli_fetch_array($query))
{
    $break_time = $ron['company_break_time'];
    $company_time = $ron['company_in_time'];
    $timezone = $ron['timezone'];
}

date_default_timezone_set($timezone);

$weeked = [];
$working_days = mysqli_query($conn, "Select * from working_days where admin_id = ".$_POST['admin_id']);
$info = mysqli_fetch_array($working_days);
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

$fromdt= strtotime($months."-01");
$todt= strtotime($months."-".$days." 24:59:59");
$temp = 1;
$half = 2;
$result = mysqli_query($conn, "SELECT * FROM attendance where employee_id = " . $_SESSION['e_id'] . " and in_time between $fromdt and $todt ");
while ($row = mysqli_fetch_array($result)) {
    $id = $row['employee_id'];
    $name = $row['emp_name'];
    $time = $row['in_time'];
    $date = date("j", $time);
    if($row['attendance_status'] == "Full"){
    $emp[$employee[$id]]->setDate($temp, $date);
    }else{
        $emp[$employee[$id]]->setDate($half, $date);
    }
    $emp[$employee[$id]]->setName1($name);
}
$emp[0]->getName();
$today = date("d");
$output = '<table id="attendance" class="table table-bordered custom-table table-nowrap mb-0">
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
    if($months == date('Y-m')){
        $todaydate = date('d');
    }else{
        $todaydate = $days;
    }
    for ($i = 1; $i < $d1; $i++) {
        $output .= '<td ></td>';
    }
        $count = $d1;
        
    for ($k = 1; $k <= $days; $k++) {
        $date = $months."-".$k;
        
        $day = date('l', strtotime($date));
        if ($emp[0]->getDate($k) == 1) {
            $status = '<span class="text-success" data-toggle="tooltip" data-placement="top" title="Present!"><b>'.$k.'</b></span>';
        } elseif($emp[0]->getDate($k) == 2) {
            $status = '<span class="text-blue" data-toggle="tooltip" data-placement="top" title="Half Day!"><b>'.$k.'</b></span>';
        }elseif(in_array($day, $weeked)){
            $color = '#5304ba';
            $tooltip = 'Weekend';
            $status ='<span style="color:'.$color.'" data-toggle="tooltip" data-placement="top" title="'.$tooltip.'"><b>'.$k.'</b></span>';
        }else{
            
            if($k <= $todaydate){
                $color = 'red';
                $tooltip = 'data-toggle="tooltip" data-placement="top" title="Absent."';
            }else{
                $color = '#d9d9e2';
                $tooltip = '';
            }
             $status ='<span style="color:'.$color.'" '.$tooltip.'><b>'.$k.'</b></span>';
            //  echo $k."-".$status;
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
