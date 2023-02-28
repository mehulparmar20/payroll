<?php
if(isset($_GET['cron']) || isset($_POST['id'])){
    include 'dbconfig.php';
    include 'admin/api/index.php';
    
    function isWeekend($adminId, $conn){
        $day  = date('D');
        $working_days = $conn->query( "Select * from working_days where admin_id = '$adminId' ");
        $info = mysqli_fetch_array($working_days);
        if($info[strtolower($day)] != 1){
            return true;
        }else{
            return false;
        }
    }
    $api = new Util();
    if($_GET['cron'] == 'yes'){
        $query = $conn->query("select *  from `employee` where employee_status = 1 and delete_status = 0 and isVipUser = 1 ");
    }else{
        $id = $_POST['id'];
        $query = $conn->query("select *  from `employee` where emp_cardid = $id ");
    }
    if($query->num_rows == 0){
        echo json_encode(array('status' => "error", "message" => "User not found!"));
        exit();
    }
    while($res = $query->fetch_assoc()){
        $timezone = '';
        $shift_no = '';
        $ch_date = '';
        $at_date = '';
        $currenttime = '';
        $lasttime = '';
        $date = '';
        $fine = 0;
        $diff = 0;
        $emp_id = $res['emp_cardid'];
        $first = $res['e_firstname'];
        $last = $res['e_lastname'];
        $e_id = $res['e_id'];
        $admin_id = $res['admin_id'];
        $shift_no = $res['shift_no'];
        $isVipUser = $res['isVipUser'];
        //  combine the Fristname and lastname
        $name = $first . " " . $last;
        // get Shift Details
        $time = $api->getShifTtime($conn, $shift_no);
        $break_time = $time['company_break_time'];
        $company_intime = $time['company_in_time'];
        $company_outtime = $time['company_out_time'];
        $timezone = $time['timezone'];
        $breakfine = $time['break_fine'];
        $latefine = $time['late_fine'];
        date_default_timezone_set($timezone);
        $maxbreak = 30;
        // Store the comapny start Time in variable
        $at_date = strtotime(date("Y-m-d 00:00:00"));
        $ch_date = strtotime(date("Y-m-d $company_intime:00"));
        if($_GET['cron'] == 'yes'){
            if(isWeekend($admin_id, $conn)){
                echo json_encode(array('status' => "error", "message" => "This is weekend!"));
                continue;
            }
            if($api->getAttendanceDetails($conn, $emp_id, $at_date) != 0){
                continue;
            }
        }
        // Get company Details
        $companyDetails = $api->getCompanyDetails($conn, $admin_id);
        $c_email = $companyDetails['admin_email'];
        // convert Punch date and time to timestamp
        $date = time();
        //Device Type
        $devicetype = 10;
        $currenttime = $date;
        if ($api->getAttendanceDetails($conn, $emp_id, $at_date) == 0) {
            $present_status = "On Time";
            $late = 0;
            $fine = 0;
            if ($isVipUser == 0  && $currenttime > $ch_date) {
                $diff = $currenttime - $ch_date;
                $late = ceil($diff / 60);
                $fine = round($late * $latefine);
                $present_status = "Late";
            }
            $data = array(
                'fine' => $fine,
                'late' => $late,
                'e_id' => $e_id,
                'admin_id' => $admin_id,
                'emp_id' => $emp_id,
                'time' => $ch_date,
                'present_status' => $present_status,
                'name' => $name,
                'devicetype' => $devicetype,
            );
            if ($api->attendance($conn, $data)) {
                echo json_encode(array('status' => 'success', 'message' => 'Attendance added successfully.'));
            } else {
                break;
            }
        } else {
            // Check user in Break off list
            $breakoff = $api->getBreakoff($conn, $e_id);
            $data = array(
                'emp_id' => $emp_id,
                'at_date' => $at_date
            );
            if($breakoff == 0) {
                $break_data = $api->getlastBreak($conn, $data);
                $val = $break_data['count'];
                // get details last break
                $diff = 0;
                if ($val > 0) {
                    // Create variable for Last break
                    $row = $break_data['data'];
                    $b_time = $row["break_time"];
                    $out_time = $row["out_time"];
                    $last_id = $row['b_id'];
                    $violation = 'No';
                    $final = 0;
                    // if user is OUT then
                    if ($out_time == "OUT") {
                        // $diff is diffreance between $time and $b_time
                        $diff = $currenttime - $b_time;
                        $total_time = ceil($diff / 60);
                    }
                    $maxbreakSeconds = $maxbreak*60;
                    if ($diff > $maxbreakSeconds) {
                        $final = $total_time - $maxbreak;
                        $t_break = $final;
                        $final *= $breakfine;
                        $violation = "Yes";
                    }
                    $data = array(
                        'e_id' => $e_id,
                        'fine' => round($final),
                        'violation' => $violation,
                        'time' => $date,
                        'break_id' => $last_id,
                        'devicetype' => $devicetype,
                    );
                    if ($api->endbreak($conn, $data)) {
                        echo json_encode(array('status' => 'success', 'message' => 'Break end successfully.'));
                    } else {
                        break;
                    }
                } else {
                    $data = array(
                        'e_id' => $e_id,
                        'name' => $name,
                        'admin_id' => $admin_id,
                        'emp_id' => $emp_id,
                        'start_time' => $date,
                        'devicetype' => $devicetype,
                    );
                    if ($api->addbreak($conn, $data)) {
                        echo json_encode(array('status' => 'success', 'message' => 'Break start successfully.'));
                    } else {
                        break;
                    }
                }
            }
        }
    }
    
}
    
        