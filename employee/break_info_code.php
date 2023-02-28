<?php
session_start();
    include '../dbconfig.php';
    include '../admin/api/index.php';
    $event = new Util();
    $res = $event->refreshPagedata($conn, $_SESSION['devIndex']);
    $result1 = mysqli_query($conn, "SELECT * FROM employee where e_id = " . $_SESSION['e_id'] . " ");
    $row = mysqli_fetch_array($result1);
    $id = $row['e_id'];
    $shift = $row['shift_no'];
    $query = mysqli_query($conn, "select *  from company_time where time_id = '$shift' ");
    while ($ron = mysqli_fetch_array($query)) {
        $break_time = $ron['company_break_time'];
        $company_time = $ron['company_in_time'];
        $timezone = $ron['timezone'];
    }

    date_default_timezone_set($timezone);
            
            // date_default_timezone_set("$timezone");
$day = $_POST['day'];

$fromdt= strtotime("$day 00:00:00");
$todt= strtotime("$day 23:59:59");

$sql = "SELECT * FROM `break` WHERE employee_id = ".$_SESSION['e_id']." and break_time between $fromdt and $todt ";
$result = mysqli_query($conn,$sql);
$output ='<table id="attendance" class="table table-striped custom-table table-nowrap  mb-0">
                        <thead>
                            <tr>
                                <th><center><b>No</b></center></th>
                                <th><center><b>Device</b></center></th>
                                <th><center><b>Employee Card No</b></center></th>
                                <th><center><b>Break Out</b></center></th>
                                <th><center><b>Break In</b></center></th>
                                <th><center><b>Total Break Time</b></center></th>
                                <th><center><b>Violation</b></center></th>
                                <th><center><b>Violation Fine</b></center></th>
                            </tr>
                        </thead>
                        <tbody>';
               $no = 1;         
 date_default_timezone_set($timezone);                      
while($row = mysqli_fetch_array($result)){
    $emp_id = $row['employee_id'];
    $emp_name = $row['emp_name'];
    $emp_no = $row['employee_cardno'];
    $break_intime = $row['break_time'];
    $break_outtime = $row['out_time'];
    
    $fine = $row['fine'];
    $status = $row['violation'];
    $device = $row['devicetype'];
    if($break_outtime == 'OUT')
    {
        $in_time ='<span style="color:red"><b>OUT</b></span>';
        $break_time = 0;
    }else{
        $diff = $break_outtime - $break_intime;
        $break_time = ceil($diff/60);
        $in_time = date("d m Y h:i:sa", $break_outtime);
    }
    if ($device == 75) {
        $device = '<img src="app/img/face-recognition.svg" alt="Face Recognition" width="20" height="20" data-toggle="tooltip" data-placement="top" title="Face Recognition"></img>';
    } elseif ($device == 38) {
        $device = '<img src="app/img/fingerprint.svg" alt="Finger Print" width="20" height="20" data-toggle="tooltip" data-placement="top" title="Finger Print">';
    } else {
        $device = '<img src="app/img/id-card.svg" alt="ID Card" width="20" height="20" data-toggle="tooltip" data-placement="top" title="ID Card"></img>';
    }

    
        $output .='         <tr>
                                <td><center>'.$no++.'</center></td>
                                <td><center>'.$device.'</center></td>
                                <td><center>'.$emp_no.'</center></td>
                                <td><center>'.date("d m Y h:i:sa", $break_intime).'</center></td>
                                <td><center>'.$in_time.'</center></td>
                                <td><center>'.$break_time.' min</center></td>
                                <td><center>'.$status.'</center></td>
                                <td><center>'.$fine.'</center></td>
                            </tr>
                        ';
 }
 
 $output .= '</tbody>
                    </table>';
echo $output;