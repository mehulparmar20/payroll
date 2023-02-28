<?php
if(!isset($_POST['action'])){
    header("../index.php");
    exit();
}
ob_start();
session_start();
use PhpOffice\PhpSpreadsheet\src\PhpSpreadsheet\Reader\Xlsx;
require_once('../vendor/autoload.php');
require_once("../dbconfig.php");
require_once("api/index.php");
require_once("../stripe/model/Stripeupdate.php");
$api = new Util();
$stripe = new Stripeupdate();
// add Break off user
if ($_POST['action'] == 'break_off') {
    $date = strtotime($_POST['user_date']);
    $data = explode(",", $_POST['user_name']);
    $admin_id = $_POST['admin_id'];
    $time = time();
    $e_id = $data[0];
    $emp_name = $data[1];
    $sql = mysqli_query($conn, "select * from break_off_user where e_id = '$e_id' ");
    $count = mysqli_num_rows($sql);
    if($count == 0){
        $set_pass = mysqli_query($conn, "insert into break_off_user (emp_name,admin_id,e_id,add_date)values('$emp_name','$admin_id','$e_id','$date')");
        if ($set_pass) {
            $message = "User Added Successfully!";
        } else {
            $message = "User Not Added Successfully!";
        }
    }else{
        $message = 'User already in Break of list';
    }
$response = array(
    "message" => $message
);
echo json_encode($response);
}

// policy View
if ($_POST['action'] == 'add_notice') {
    $query = mysqli_query($conn, "select *  from company_time where admin_id = " . $_POST['admin_id'] . " ");
    while ($r = mysqli_fetch_array($query)) {
        $b_time = $r['company_break_time'];
        $timezone = $r['timezone'];
    }

    date_default_timezone_set($timezone);
    $notice= $_POST['text'];
    $admin_id= $_POST['admin_id'];
    $subject= $_POST['subject'];
    $time = time();

    $sql1 = mysqli_query($conn,"Insert into notice (notice,notice_subject,show_status,admin_id,entry_time)values('$notice','$subject','1','$admin_id','$time')");
    if ($sql1) {
        echo "Notice added Successfully.";

    }else{
        echo "Notice Not Added.......";
    }

}

// add Allow Benefits Leave
if ($_POST['action'] == 'company_benefits') {
    $query = mysqli_query($conn, "select *  from company_time where admin_id = " . $_POST['admin_id'] . " ");
    while ($r = mysqli_fetch_array($query)) {
        $b_time = $r['company_break_time'];
        $timezone = $r['timezone'];
    }

    date_default_timezone_set($timezone);
    $allow_leave = $_POST['allow_leave'];
    $admin_id= $_POST['admin_id'];

    $time = time();
    $query = mysqli_query($conn, "select *  from company_benefits where admin_id = " . $_POST['admin_id'] . " ");
    $count = mysqli_num_rows($query);
    if($count = 0){
        $sql1 = mysqli_query($conn,"Insert into company_benefits (admin_id,allow_leave,entry_time)values('$admin_id','$allow_leave','$time')");
        if ($sql1) {
            echo "Company Benefits added Successfully.";

        }else{
            echo "Company Benefits Not Added.......";
        }
    }else{
        $sql1 = mysqli_query($conn,"update company_benefits set allow_leave = '$allow_leave',entry_time = '$time' where admin_id = '$admin_id'");
        if ($sql1) {
            echo "Company Benefits added Successfully.";

        }else{
            echo "Company Benefits Not Added.......";
        }
    }

}

if($_POST['action'] == 'add_joining'){
    $e_id = mysqli_real_escape_string($conn, $_POST['e_id']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $application_date = mysqli_real_escape_string($conn, strtotime($_POST['application_date']));
    $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
    $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
    $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name']);
    $manager_designation = mysqli_real_escape_string($conn, $_POST['manager_designation']);
    $added_time = time();
    $sql = mysqli_query($conn, "insert into joining_employee(`application_date`,`e_id`, `admin_id`, `joining_date`,`emp_id`,`department_name`,`added_time`,`manager_name`,`manager_designation`)values('$added_time','$e_id', '$admin_id', '$application_date','$emp_id','$department','$added_time','$manager_name','$manager_designation') ");
    if($sql){
        echo "success";
    }
}

// Add Department Instant
if($_POST['action'] == 'department_add')
{
    $department_name = $_POST['department_name'];
    $admin_id = $_POST['admin_id'];
    $sql = "Insert into departments(departments_name,admin_id)values('$department_name','$admin_id')";
    if($conn->query($sql)) {
        $output = '<select class="form-control" required id="department">
                        <option value="">Select Department</option>';
        $sql = mysqli_query($conn, "SELECT * FROM departments where admin_id = '$admin_id' ");
        while ($row = mysqli_fetch_assoc($sql)) {
            $output .='<option value="'.$row['departments_id'].",".$row['used_count'].'">'.$row['departments_name'].'</option>';
        }
        $output .='</select>';

        echo $output;
    }
}

// Add Department Instant
if($_POST['action'] == 'leave_insert')
{
    $leave_type = mysqli_real_escape_string($conn, $_POST["leave_type"]);
    $admin_id = mysqli_real_escape_string($conn, $_POST["admin_id"]);
    $query = "INSERT INTO add_leave (leave_type, admin_id) VALUES('$leave_type', '$admin_id')";
    if (mysqli_query($conn, $query)){
        echo "Leave Type Added";
    }else{
        echo "Leave Type Not Added";
    }
}

// Add Designation Instant
if($_POST['action'] == 'add_designation')
{
    $designation_name = $_POST['designation_name'];
    $department = $_POST['department'];
    $admin_id = $_POST['admin_id'];
    $sql = "Insert into designation(designation_name,department_id,admin_id)values('$designation_name','$department','$admin_id')";
    if($conn->query($sql)) {
        $output = '<select class="form-control" required id="designation">
                        <option value="">Select Department</option>';
        $sql = mysqli_query($conn, "SELECT * FROM designation where admin_id = '$admin_id' ");
        while ($row = mysqli_fetch_assoc($sql)) {
            $output .='<option value="'.$row['designation_id'].'">'.$row['designation_name'].'</option>';
        }
        $output .='</select>';

        echo $output;
    }
}
// add request for renew plan
if ($_POST['action'] == 'renew_plan') {
    $plan_name = $_POST['plan_name'];
    $company_name = $_POST['company_name'];
    $contact_no = $_POST['company_no'];
    $month = $_POST['month'];
    $admin_id = $_POST['admin_id'];
    $time = time();
    $no_of_emp = $_POST['add_employee'];
    $price = $_POST['price'];
    $query = mysqli_query($conn, "INSERT INTO `renew_request`(`company_name`,`company_contact`,`new_employee`, `renew_date`, `renew_price`, `plan_name`, `plan_month`, `admin_id`) VALUES ('$company_name',$contact_no,'$no_of_emp','$time','$price','$plan_name','$month','$admin_id')");

}

// add Employee Of The Month
if($_POST['action'] == 'employee_month'){

    $emp_name = explode(",", $_POST['emp_name']);
    $department = $_POST['department'];
    $month_name = strtotime($_POST['month']);
    $admin_id = $_POST['admin_id'];
    $profile = $_POST['profile'];
    $attendance = $_POST['attendance'];
    $break = $_POST['break'];
    $leave = $_POST['leave'];
    $profit = $_POST['profit'];
    $time = time();
    $sql1 = "INSERT INTO `employee_performance`(`emp_name`, `department`, `attendance`, `break`, `leave`, `profit`, `profile`, `admin_id`, `month_name`, `entry_time`) VALUES ('$emp_name[1]','$department','$attendance','$break','$leave','$profit','$profile','$admin_id','$month_name','$time')";
    if ($conn->query($sql1)) {
        echo "User Added Successfully.";
    }
    else{
        echo 'Not Added User';
    }
}

// add accounting_password
if ($_POST['action'] == 'add_accounting_password') {
    $password = hash('sha1', $_POST['new_pass']);
    $admin_id = $_POST['admin_id'];
    $time = time();
    $set_pass = mysqli_query($conn, "insert into accounting_auth (password,admin_id,last_change_password,entry_time)values('$password','$admin_id','$time','$time')");
    if ($set_pass) {
        echo "true";
    } else {
        echo "false";
    }
}

// add company Time
if ($_POST['action'] == 'add_company_time') {
    $start_time= $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $break_time = $_POST['break_time'];
    $admin_id = $_POST['admin_id'];
    $timezone = $_POST['timezone'];
    $shift_no = $_POST['shift_no'];
    $break_fine = $_POST['break_fine'];
    $late_fine = $_POST['late_fine'];
    $added_time = time();

    $sql = "INSERT INTO `company_time`(`shift_no`,`late_fine`,`break_fine`,`company_in_time`,`timezone`, `company_out_time`, `company_break_time`, `admin_id`, `entry_time`) 
                                VALUES ('$shift_no','$late_fine','$break_fine','$start_time','$timezone','$end_time','$break_time','$admin_id','$added_time')";
    if($conn->query($sql)){
        return 'success';
    }else{
        return 'failed';
    }
}

// add User
if($_POST['action'] == 'add_user'){
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_password = hash("sha1", $_POST['user_password']);
    $user_type = $_POST['user_type'];
    $user_date = strtotime($_POST['user_date']);
    $admin_id = $_POST['admin_id'];
    $employee = $_POST['employee'];
    $payroll = $_POST['payroll'];
    $attendance = $_POST['attendance'];
    $break = $_POST['breaks'];
    $leave = $_POST['leave'];
    $letters = $_POST['letters'];
    $administration = $_POST['administration'];
    $time = time();
    $sql1 = "INSERT INTO `add_users`(`admin_id`, `user_name`,`user_email`, `user_password`, `user_type`,`user_add_date`, `last_change_password`, `entry_time`, `employee`, `payroll`, `attendance`, `break`, `leave`, `letters`, `administration`) VALUES ('$admin_id','$user_name','$user_email','$user_password','$user_type','$user_date','$time','$time','$employee','$payroll','$attendance','$break','$leave','$letters','$administration')";
    if ($conn->query($sql1)) {
        echo "User Added Successfully.";
    }
    else{
        echo 'Not Added User';
    }
}

// add Attendance
if($_POST['action'] == 'add_attendance')
{
    $admin_id = $_POST['admin_id'];
    $present_type = $_POST['present_status'];
$query = mysqli_query($conn, "select *  from company_time where time_id = ".$_SESSION['shift_id']." ");
    while($ron = mysqli_fetch_array($query))
    {
        $break_time = $ron['company_break_time'];
        $company_intime = $ron['company_in_time'];
        $company_outtime = $ron['company_out_time'];
        $timezone = $ron['timezone'];
    }
    date_default_timezone_set($timezone);
    $emp_cardno = $_POST['emp_cardno'];

    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $date = date("$date $company_intime:00");
    $no = 0;
    foreach ((array)$emp_cardno as $value)
    {    $fetch = mysqli_query($conn,"SELECT * FROM employee where emp_cardid = '$value' ");

        while ($row = mysqli_fetch_array($fetch))
        {
            $first = $row['e_firstname'];
            $last = $row['e_lastname'];
            $e_id = $row['e_id'];
            $admin_id = $row['admin_id'];
            $email = $row['e_email'];
        }

        $name = $first." ".$last;
        $time = strtotime($date);
        $present_status = 'On Time';
        $sql = "INSERT INTO `attendance` (`employee_id`, `emp_name`, `admin_id`, `employee_cardno`,`present_status`,`in_time`, `attendance_status`) VALUES ('$e_id', '$name', '$admin_id', '$value', '$present_status','$time','$present_type')";
        if ($conn->query($sql)) {
            $no++;
        } else {
            echo "Attendance Not Added";
        }
    }

    if ($no > 0) {
        echo "Attendance added Successfully.";
    } else {
        echo "Attendance Not Added";
    }
}

// add salary Settings
if($_POST['action'] == 'add_salary_setting')
{
    $da = mysqli_real_escape_string($conn, $_POST['da']);
    $hra = mysqli_real_escape_string($conn, $_POST['hra']);
    $conveyance = mysqli_real_escape_string($conn, $_POST['conveyance']);
    $allow = mysqli_real_escape_string($conn, $_POST['allow']);
    $m_allow = mysqli_real_escape_string($conn, $_POST['m_allow']);
    $tds = mysqli_real_escape_string($conn, $_POST['tds']);
    $esi = mysqli_real_escape_string($conn, $_POST['esi']);
    $pf = mysqli_real_escape_string($conn, $_POST['pf']);
    $proftax = mysqli_real_escape_string($conn, $_POST['proftax']);
    $l_wel = mysqli_real_escape_string($conn, $_POST['l_wel']);
    $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
    $time = time();
    $fetch = mysqli_query($conn,"SELECT * FROM salary_setting WHERE admin_id = '$admin_id' ");
    $no = mysqli_num_rows($fetch);
    if($no == 0) {
        $sql = "INSERT INTO `salary_setting` (`da`, `hra`, `conveyance`, `allow`, `m_allow`, `tds`, `esi`, `pf`, `proftax`, `l_wel`, `admin_id`, `entry_time`) VALUES ( '$da', '$hra', '$conveyance', '$allow', '$m_allow', '$tds', '$esi', '$pf', '$proftax', '$l_wel', '$admin_id', '$time')";
        if ($conn->query($sql)) {
            echo "Salary Settings added Successfully.";
        } else {
            echo "Salary Settings Not Added";
        }
    }else{
        $sql = "UPDATE `salary_setting` SET `da` = '$da', `hra` = '$hra', `conveyance` = '$conveyance', `allow` = '$allow', `m_allow` = '$m_allow', `tds` = '$tds', `esi` = '$esi', `pf` = '$pf', `proftax` = '$proftax', `l_wel` ='$l_wel' WHERE `admin_id` = '$admin_id' ";
        if ($conn->query($sql)) {
            echo "Salary Settings update Successfully.";
        } else {
            echo "Salary Settings Not update";
        }
    }
}
//// add Company File
//if($_POST['action'] == 'add_company_file'){
//    print_r($_FILES['files']);
//    if (!empty(array_filter($_FILES['files']['name']))) {
//
//        $targetDir = "company_document/"; // target folder path
//        $allowedType = array('jpg', 'jpeg', 'png', 'pdf'); // allowed file
//        $current_time = time();
//        $admin_id = $_POST['admin_id'];
//
//        foreach ($_FILES['files']['name'] as $key => $val) {
//            $filename = $_POST['admin_id']. rand(0,999999).$_FILES['files']['name'][$key]; // file name
//            $temLoc = $_FILES['files']['tmp_name'][$key]; // temporary location
//            $targetPath = $targetDir . $filename; // target file path with file
//            $fileType = pathinfo($targetPath, PATHINFO_EXTENSION); // get file extention
//            $fileSize = $_FILES['files']['size'][$key]; // get file size
//
//            $storage = mysqli_query($conn," select company_upload_storage from company_admin where admin_id = '$admin_id' ");
//            while ($s = mysqli_fetch_array($storage))
//            {
//                $store = $s['company_upload_storage'];
//            }
//
//            if (in_array($fileType, $allowedType)) { // condition for check fileType is same or not
//                if ($fileSize < $store) { // condition for check file size
//                    if (move_uploaded_file($temLoc, $targetPath)) { // condition for move file from current location(temporary location) to upload/ folder
//                        $Size = $store - $fileSize;
//                        mysqli_query($conn, "Insert into company_document(admin_id,file_name,file_size,file_extension,file_added_time) values('$admin_id','$filename','$fileSize','$fileType','$current_time')"); // get filename and date
//                        mysqli_query($conn, " update company_admin set company_upload_storage = '".$Size."' where admin_id = '".$admin_id."' ");
//                    } else {
//                        echo "Could Not Upload File";
//                    }
//                } else {
//                    echo "You do not have enough space";
//                }
//            } else {
//                echo "File Type Error";
//            }
//        }
//    }
//}

if($_POST['action'] == 'addemployee'){
    $device_status = $api->checkDeviceStatus($_SESSION['devIndex'],$_SESSION['device_username'],$_SESSION['device_password']);
    if($device_status != 'offline'){
        $com = $api->getCompanyDetails($conn,$_SESSION['admin_id']);
        $totalrem = $com['remainingemployee'];
        if($totalrem > 0) {
            if(!isset($_POST['entry_type'])) {
                $f_name = mysqli_real_escape_string($conn, $_POST['f_name']);
                $l_name = mysqli_real_escape_string($conn, $_POST['l_name']);
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $gender = mysqli_real_escape_string($conn, $_POST['gender']);
                
                // $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
                $join_date = mysqli_real_escape_string($conn, strtotime($_POST['join_date']));
                $ph_no = mysqli_real_escape_string($conn, $_POST['ph_no']);
                $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
                $salary = $_POST['e_salary'];
                $department = $_POST['department'];
                $designation = $_POST['designation'];
                $shift_no = $_POST['shift_no'];
                $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
                $startdate = substr(date('c', $join_date), 0, 19);
                $name = $f_name . " " . $l_name;
                $empId = $api->RandomString();
                
                $pass = $f_name.rand(100,999);
                $password = hash('sha1', $pass);
                $user = '{
                            "UserInfo" : [{
                            "employeeNo": "'.$empId.'",
                            "name": "'.$name.'",
                            "userType": "normal",
                            "Valid" : {
                            "enable": true,
                            "beginTime": "'.$startdate.'",
                            "endTime": "2035-08-01T17:30:08",
                            "timeType": "local"
                            }
                            }]
                        }';
                $res = $api->addEmployee($_SESSION['devIndex'], $user, $com['device_username'], $com['device_password']);
                $res = json_decode($res,true);
                $res = json_decode($res,true);
                $data = $res['UserInfoOutList']['UserInfoOut'];
                $status = $data[0]['statusCode'];
                if($status == 1){
                    $sql1 = "Insert into employee(e_firstname,e_lastname,emp_cardid,e_email,e_gender,e_password,join_date,e_phoneno,department,admin_id,designation,e_salary,shift_no)
                                       values('$f_name','$l_name','$empId','$email','$gender','$password','$join_date','$ph_no','$department','$admin_id','$designation','$salary','$shift_no')";
                    if ($conn->query($sql1)) {
                        $to = $email;
                        $comdataname = mysqli_query($conn, "SELECT * FROM `company_admin` WHERE admin_id = $admin_id ");
                        while ($row = mysqli_fetch_array($comdataname)) {
                            $c_name = $row['company_name'];
                        }
                        include '../mail.php';
                        $obj = new Mail();
                        $totalrem -= 1;
                        $res = $api->decTotalrem($conn, $totalrem, $admin_id);
                        $subject = 'Welcome To Windson Payroll';        // Subject
                        $string_to_name = '@name@';                         // Set Name in file
                        $string_email = '@@email@@';
                        $string_password = '@@password@@';
                        $string_cname = '@cname@';
                        // for replace name
                        $message = file_get_contents("../email-template/new_joining.php");
                        $message = str_replace ($string_to_name, $name, $message);
                        // for replace Admin
                        $message = str_replace ($string_password, $pass, $message);
                        // for replace Token
                        $message = str_replace ($string_email, $email, $message);
                        // for replace Token
                        $message = str_replace ($string_cname, $c_name, $message);
                        if ($obj->sentApiMail($email, $name ,$message, $subject)) {
                            $response['status'] = 'success';
                            $response['message'] = 'Employee added successfully';
                            echo json_encode($response);
                        } else {
                            $response['status'] = 'failed';
                            $response['message'] = 'Email not sent';
                            echo json_encode($response);
                        }
                    } else {
                        echo 'Failed';
                    }
                }
            }elseif ($_POST['entry_type'] == 'excel'){
                $allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
                if (in_array($_FILES["file"]["type"], $allowedFileType)) {
                    $targetPath = 'uploads/' . $_FILES['file']['name'];
                    move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
                    $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $spreadSheet = $Reader->load($targetPath);
                    $excelSheet = $spreadSheet->getActiveSheet();
                    $spreadSheetAry = $excelSheet->toArray();
                    $sheetCount = count($spreadSheetAry);
                    $time = time();
                    $count = 0;
                    $data = 'success';
                    $user = array();
                    $empdata = array();
                    if($totalrem >= $sheetCount) {
                        for ($i = 0; $i <= $sheetCount; $i++) {
                            if ($i != 0) {
                                if (isset($spreadSheetAry[$i][0]) && $spreadSheetAry[$i][0] != '') {
                                    $Fisrtname = $spreadSheetAry[$i][1];
                                    $Lastname = $spreadSheetAry[$i][2];
                                    $email = $spreadSheetAry[$i][3];
                                    $password = hash("sha1",$spreadSheetAry[$i][4]);
                                    $name = $Fisrtname." ".$Lastname;
                                    $joiningdate = strtotime($spreadSheetAry[$i][6]);
                                    $startdate = substr(date('c', $joiningdate), 0, 19);
                                    $num = rand(0,1000);
                                    $time = time();
                                    $gender = $spreadSheetAry[$i][5];
                                    $phone = $spreadSheetAry[$i][7];
                                    $salary = $spreadSheetAry[$i][8];
                                    $empId = $api->RandomString();
            //                        $empId = "$empId";
            //                        $name = "$name";
            //                        $startdate = "$startdate";
                                    array_push($user,array("employeeNo" => $empId,
                                        "name" =>  $name,
                                        "userType" => "normal",
                                        "Valid" => array(
                                            "enable"=> true,
                                            "beginTime"=> $startdate,
                                            "endTime" => "2035-08-01T17:30:08",
                                            "timeType" => "local"
                                        )
                                    ));
                                    $empdata[$empId] = array("emp_id" => $empId,"firstname" => $Fisrtname,"lastname" => $Lastname,"email" => $email, "password" => $password,"gender" => $gender, "join_date" => $joiningdate, "phone" => $phone, "salary" => $salary,'status' => 'yes');
                                }
                            }
                        }
                        $arrdata = array ("UserInfo" => $user);
                        $com = $api->getCompanyDetails($conn,$_SESSION['admin_id']);
                        $res = $api->addEmployee($_SESSION['devIndex'], json_encode($arrdata), $com['device_username'], $com['device_password']);
                        $res = json_decode($res,true);
                        $res = json_decode($res,true);
                        $data = $res['UserInfoOutList']['UserInfoOut'];
                        $len = sizeof($data);
                        for ($i = 0; $i < $len; $i++) {
                            $status = $data[$i]['statusCode'];
                            $empId = $data[$i]['employeeNo'];
                            if($status != 1){
                                $empdata[$empId]['status'] = "no";
                            }
                        }
                        $len = sizeof($empdata);
                        $admin_id = $_SESSION['admin_id'];
                        $time = $api->getcompanytime($conn,$admin_id);
                        $shift_no = $time['time_id'];
                        $keys = array_keys($empdata);
                        $response = array(
                            'success' => 0,
                            'Error' => 0,
                            'Error_msg' => array(),
                        );
                        $errcount = 0;
                        $count = 0;
                        $error= array();
                        $unsucess = array();
                        for ($i = 0; $i < $len; $i++) {
                            if($empdata[$keys[$i]]['status'] == 'yes') {
                                $Fisrtname = $empdata[$keys[$i]]['firstname'];
                                $Lastname = $empdata[$keys[$i]]['lastname'];
                                $email = $empdata[$keys[$i]]['email'];
                                $password = $empdata[$keys[$i]]['password'];
                                $joiningdate = $empdata[$keys[$i]]['join_date'];
                                $time = time();
                                $gender = $empdata[$keys[$i]]['gender'];
                                $phone = $empdata[$keys[$i]]['phone'];
                                $salary = $empdata[$keys[$i]]['salary'];
                                $emp_id = $empdata[$keys[$i]]['emp_id'];
                                $sql = "INSERT INTO `employee`(`admin_id`, `e_firstname`, `e_lastname`, `e_gender`, `e_email`, `e_password`, `emp_cardid`, `join_date`, `e_phoneno`, `e_salary`, `shift_no`, `emp_added_time`) VALUES 
                                                            ('$admin_id','$Fisrtname','$Lastname','$gender','$email','$password','$emp_id','$joiningdate','$phone','$salary','$shift_no','$time')";
                                if($conn->query($sql)){
                                    $totalrem -= 1;
                                    $res = $api->decTotalrem($conn, $totalrem, $admin_id);
                                    $count++;
                                }else{
                                    $error[$emp_id] = array("emp-id" => $emp_id, "Error" => $conn->error);
                                    $errcount++;
                                }
                            }else{
                                $empdata[$i]['password'] = '';
                                $unsucess[$keys[$i]] = $empdata[$keys[$i]];
                            }
                        }
                        $response['success'] = $count;
                        $response['status'] = 'success';
                        $response['Error'] = $errcount;
                        $response['Error_msg'] = $error;
                        $response['Unsuccessfull'] = $unsucess;
                        unlink($targetPath);
                        echo json_encode($response);
                    }else{
                        $response = array();
                        $response['status'] = 'error';
                        $response['message'] = "You have 0 remaining Employee.To add more Employee please buy more Employees.";
                        echo json_encode($response);
                    }
                }
            }
        }else{
            $response = array();
            $response['status'] = 'error';
            $response['message'] = "You have 0 remaining Employee.To add more Employee please buy more Employees.";
            echo json_encode($response);
        }
    }else{
        $response = array('status' => 'offline');
        echo json_encode($response);
    }
}


if($_POST['action'] == 'addoffice'){
    $officeName = mysqli_real_escape_string($conn, $_POST['officeName']);
    $officeLocation = mysqli_real_escape_string($conn, $_POST['officeLocation']);
    $officeOpenDate = strtotime($_POST['officeOpenDate']);
    $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);

    $sql = "INSERT INTO `office`(`officeName`, `officeLocation`, `officeopenDate`, `admin_id`) VALUES  ('$officeName','$officeLocation','$officeOpenDate', '$admin_id')";
    if ($conn->query($sql)) {
        echo json_encode(array(
            "status" => "success",
            "message" => 'Office added successfully.'
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => 'error while adding office.',
            'error' => $conn -> error
        ));
    }
}

if($_POST['action'] == 'addAllowances'){
    $Name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $id = $_POST['id'];
    $admin_id = $_SESSION['admin_id'];
    if($id != ""){
        $sql = "UPDATE `allowances` SET `allowances`= '$Name', `description` = '$description' WHERE `id` = '$id'";
        $type = 'updated';
    }else{
        $sql = "INSERT INTO `allowances`(`allowances`, `description`, `admin_id`) VALUES  ('$Name','$description','$admin_id')";
        $type = 'addeded';
    }
    if ($conn->query($sql)) {
        echo json_encode(array(
            "status" => "success",
            "message" => 'Allowance '.$type.' successfully.'
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => 'error while '.$type.' allowance.',
            'error' => $conn -> error
        ));
    }
}

if($_POST['action'] == 'addDeduction'){
    $Name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $id = $_POST['id'];
    $admin_id = $_SESSION['admin_id'];
    if($id != ""){
        $sql = "UPDATE `deductions` SET `deductions`= '$Name', `description` = '$description' WHERE `id` = '$id'";
        $type = 'updated';
    }else{
        $sql = "INSERT INTO `deductions`(`deductions`, `description`, `admin_id`) VALUES  ('$Name','$description','$admin_id')";
        $type = 'addeded';
    }
    if ($conn->query($sql)) {
        echo json_encode(array(
            "status" => "success",
            "message" => 'Description '.$type.' successfully.'
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => 'error while '.$type.' description.',
            'error' => $conn -> error
        ));
    }
}

if($_POST['action'] == 'addEmpDeduction'){
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $deductionId = mysqli_real_escape_string($conn, $_POST['allowenceId']);
    $amount = $_POST['amount'];
    $id = $_POST['id'];
    $eId = $_POST['eId'];
    $admin_id = $_SESSION['admin_id'];
    if($id != ""){
        $sql = "UPDATE `employee_deductions` SET `deductionId`= '$deductionId', `type` = '$type', `amount` = '$amount' WHERE `id` = '$id'";
        $type = 'updated';
    }else{
        $sql = "INSERT INTO `employee_deductions`( `eId`, `deductionId`, `type`, `amount`, `admin_id`) VALUES  ('$eId','$deductionId','$type','$amount','$admin_id')";
        $type = 'addeded';
    }
    if ($conn->query($sql)) {
        echo json_encode(array(
            "status" => "success",
            "message" => 'Data '.$type.' successfully.'
        ));
    } else {
        echo json_encode(array(
            "status" => "info",
            "message" => 'error while '.$type.' data.',
            'error' => $conn->error
        ));
    }
}

if($_POST['action'] == 'addEmpAllowence'){
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $deductionId = mysqli_real_escape_string($conn, $_POST['allowenceId']);
    $amount = $_POST['amount'];
    $id = $_POST['id'];
    $eId = $_POST['eId'];
    $admin_id = $_SESSION['admin_id'];
    if($id != ""){
        $sql = "UPDATE `employee_allowances` SET `allowanceId`= '$deductionId', `type` = '$type', `amount` = '$amount' WHERE `id` = '$id'";
        $type = 'updated';
    }else{
        $sql = "INSERT INTO `employee_allowances`( `eId`, `allowanceId`, `type`, `amount`, `admin_id`) VALUES  ('$eId','$deductionId','$type','$amount','$admin_id')";
        $type = 'addeded';
    }
    if ($conn->query($sql)) {
        echo json_encode(array(
            "status" => "success",
            "message" => 'Allowance '.$type.' successfully.'
        ));
    } else {
        echo json_encode(array(
            "status" => "info",
            "message" => 'error while '.$type.' allowances.',
            'error' => $conn->error
        ));
    }
}