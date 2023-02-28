<?php
session_start();
include '../dbconfig.php';

$admin_id = $_POST['admin_id'];
$time = time();
if (isset($_POST['FingerID'])) {
    $fingerID = $_POST['FingerID'];
    $sql = "SELECT * FROM employee WHERE admin_id= '$admin_id' AND emp_cardid = '$fingerID' AND finger_status = 'YES' ";
    $result = $conn->query($sql);
    $count = $result->num_rows;
    echo $count;
    if($count == 0){
        $sql = "SELECT * FROM employee WHERE admin_id= '$admin_id' AND emp_cardid = '$fingerID'";
        $result = $conn->query($sql);
        if ($row = $result->fetch_assoc()){
            //*****************************************************
            //An existed fingerprint has been detected for Login or Logout
            if ($row['e_firstname'] != "Name"){

                $Uname = $row['e_firstname']." ".$row['e_lastname'];
                $emp_id = $row['emp_cardid'];
                $admin_id = $row['admin_id'];
                $shift_no = $row['shift_no'];
                $e_id = $row['e_id'];

                $sql = "SELECT * FROM company_time WHERE shift_no= '$shift_no' AND admin_id = '$admin_id'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                $company_starttime = $row['company_in_time'];
                $company_end = $row['company_out_time'];
                $break_time = $row['company_break_time'];
                $timezone = $row['timezone'];

                $start = strtotime(date("Y-m-d 00:00:00"));
                $company_start = strtotime(date("Y-m-d $company_starttime:00"));

                $sql = "select * from attendance where employee_cardno = '$emp_id' and in_time > '$start' ";
                $result = $conn->query($sql);
                if ($result->num_rows == 0) {
                    $present_status = "On Time";
                    $late = 0;
                    $fine = 0;
                    if($time > $company_start)
                    {
                        $late = round(($time - $company_start)/60);
                        $fine = round($late*25);
                    }
                    $sql1 = "INSERT INTO `attendance` (`employee_id`, `emp_name`, `admin_id`, `employee_cardno`,`in_time`,`present_status`,`fine`,`late_time`) VALUES ('$emp_id', '$Uname', '$admin_id', '$emp_id', '$time','$present_status','$fine','$late')";

                    if ($conn->query($sql1))
                    {

                        echo "Welcome ".$Uname;
                    }else
                    {
                        echo 'Attendance Failed';
                    }
                }else{

                    $check = "SELECT * FROM break where employee_cardno = '$emp_id' and break_time > '$start' ";
                    $result = $conn->query($check);
                    $val = 0;
                    while ($row = $result->fetch_assoc())
                    {
                        $break_time = $row["total_break"];
                        $b_time = $row["break_time"];
                        $out_time = $row["out_time"];
                        $last_id = $row['b_id'];
                        $val++;
                    }
                    $add = 0;
                    $diff = 0;
                    //break
                    if ($val > 0){
                        if($out_time == 'OUT'){
                            $diff = $time - $b_time;
                            $total_time = intval($diff/60).":".($diff % 60);
                            $add = $diff;
                        }
                        if($val == 0){
                            $out_time = "on";
                        }
                            $months = date("m",$time);
                            $years= date('Y',$time);
                            $monthName = date("F", mktime(0, 0, 0, $months));

                            $fromdt= strtotime("First Day Of  $monthName $years");
                            $todt= strtotime("Last Day of $monthName $years");

                            $sql = "SELECT * FROM `break` WHERE employee_id = '$e_id' and break_time BETWEEN $fromdt and $todt";
                            $result = mysqli_query($conn,$sql);
                            $breakdiff = 0;
                            while($row = mysqli_fetch_array($result)){

                                $break_in = $row['break_time'];
                                $break_out = $row['out_time'];
                                if($break_out == 'OUT')
                                {
                                    break;
                                }
                                $diff_time = $break_out - $break_in;
                                $breakdiff += $diff_time;
                            }
                            if($out_time != "OUT" || $val == 0){
                                $sql = "INSERT INTO `break` (`employee_id`, `emp_name`, `admin_id`, `employee_cardno`,`total_break`,`break_time`) VALUES ('$e_id', '$Uname', '$admin_id', '$emp_id', '$add','$time')";
                                    if ($conn->query($sql)) {
                                        echo "Break added Successfully1.";
                                    }else{
                                        echo 'Break Failed';
                                    }
                            }else{
                                 $violation = "No";
                                 $fine = 0;
                                 $fine_min = intval($diff/60);
                                    $fine_sec = 0;
                                    $sec = $diff % 60;

                                    if($sec > 0)
                                    {
                                        $fine_sec = 1;
                                    }

                                    $final = $fine_min + $fine_sec;
                                    $t_break = $fine_min.":".$fine_sec;

                                if($diff > 1800) {
                                     $total_fine = $final - 30;
                                    $violation = "Yes";
                                    $fine = $total_fine * 25;
                                }
                               $sql = "update `break` set `employee_id` = '$e_id', `emp_name` = '$Uname', `admin_id` = '$admin_id', `employee_cardno` = '$emp_id',`total_break` = '$break_time',`total_time` = '$t_break', `violation` = '$violation', `fine` = '$fine', `out_time` = '$time' where b_id = '$last_id' ";
                                    if ($conn->query($sql)) {
                                        echo "Break Update Successfully.";
                                    }
                                    else
                                    {
                                        echo 'Fail Update';
                                    }
                              }
                            }
                        }
                    }
                }
            }else{
                echo 'Employee Not Found';
            }
        }
        //*****************************************************
        //New Fingerprint has been added
if (isset($_POST['Get_Fingerid'])) {

    if ($_POST['Get_Fingerid'] == "get_id") {
        $sql= "SELECT * FROM employee WHERE admin_id= '$admin_id' AND finger_status = 'YES' ";
        $result = $conn->query($sql);
        $count = $result->num_rows;
        if ($count == 0) {
            echo "SQL_Error_Select";
            exit();
        }
        else{
            if ($row = $result->fetch_assoc()) {
                echo "add-id".$row['emp_cardid'];
                exit();
            }
            else{
                echo "Nothing";
                exit();
            }
        }
    }
    else{
        exit();
    }
}

if (!empty($_POST['confirm_id'])) {
    $fingerid = $_POST['confirm_id'];
    $sql= "SELECT * FROM employee WHERE admin_id= '$admin_id' AND emp_cardid = '$fingerid' AND finger_status = 'YES' ";
    $result = $conn->query($sql);
    $count = $result->num_rows;
    if($count > 0){
        $sql="UPDATE employee SET finger_status = 'No' WHERE emp_cardid = '$fingerid' ";
        if ($conn->query($sql)) {
            echo "Fingerprint has been added!";
            exit();
        }else{
            echo "SQL_Error_Select";
            exit();
        }
    }
}


if (isset($_POST['DeleteID'])) {
    if ($_POST['DeleteID'] == "check") {
        $sql= "SELECT * FROM employee WHERE admin_id= '$admin_id' AND finger_status = 'DEL' ";
        $result = $conn->query($sql);
        $count = $result->num_rows;
        if($count > 0){
            $row = $result->fetch_assoc();
            echo "del-id".$row['emp_cardid'];
        }
        else{
            echo "nothing";
            exit();
        }
    }
    else{
        exit();
    }
}
mysqli_close($conn);
?>
