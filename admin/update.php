<?php

if(!isset($_POST['action'])){
    header("../index.php");
    exit();
}
require_once ("../dbconfig.php");

// update salary
if($_POST['action'] == 'update_salary'){
    //insert data in database
    $select_staff =  $_POST['emp_name'];
    $totalWorkingDays =  $_POST['working_days'];
    $netWorkingDaysPresent =  $_POST['present_days'];
    $absent_days =  $_POST['absent_days'];
    $netbasicSalary = $_POST['basic'];
    $over_time = $_POST['over_time'];
    $incentive = $_POST['incentive'];
    $editpf = $_POST['pf'];
    $e_leave = $_POST['e_leave'];
    $break_violation = $_POST['break_violation'];
    $late_fine = $_POST['late_fine'];
    $admin_id = $_SESSION['admin_id'];
    $e_id = $_POST['eId'];
    $perDayBasicSalary = $netbasicSalary/$totalWorkingDays;
    $netEarningSalary = $perDayBasicSalary * $netWorkingDaysPresent;
    $query = "SELECT * from employee WHERE e_id = '$e_id' ";
    $sql = $conn->query($query);
    $row = $sql->fetch_assoc();
    $da = $row['da'];
    $id = $row['e_id'];
    $hra = $row['hra'];
    $conveyance = $row['conveyance'];
    $spallow = $row['m_allow'];
    $tds = $row['tds'];
    $esi = $row['esi'];
    $pf = $row['pf'];
    $proftax = $row['proftax'];
    $l_wel = $row['l_wel'];
    $netDA = $row['daType'] == "+" ? round(($da/$totalWorkingDays)*$netWorkingDaysPresent) : round($netEarningSalary * $da / 100);
    $netHRA = $row['hraType'] == "+" ? round(($hra/$totalWorkingDays)*$netWorkingDaysPresent) : round($netEarningSalary * $hra / 100);
    $netConn = $row['conveyanceType'] == "+" ? round(($conveyance/$totalWorkingDays)*$netWorkingDaysPresent) : round($netEarningSalary * $conveyance / 100);
    $netSepcA = $row['m_allowType'] == "+" ? round(($spallow/$totalWorkingDays)*$netWorkingDaysPresent) : round($netEarningSalary * $spallow / 100);
    $NetTDS = $row['tdsType'] == "+" ? round(($tds/$totalWorkingDays)*$netWorkingDaysPresent) : round($netEarningSalary * $tds / 100);
    $netESI = $row['esiType'] == "+" ? round(($esi/$totalWorkingDays)*$netWorkingDaysPresent) : round($netEarningSalary * $esi / 100);
    $netPF = $row['pfType'] == "+" ? round(($pf/$totalWorkingDays)*$netWorkingDaysPresent) : round($netEarningSalary * $pf / 100);
    $netProfTax = $row['proftaxType'] == "+" ? round(($proftax/$totalWorkingDays)*$netWorkingDaysPresent) : round($netEarningSalary * $proftax / 100);
    $netLWel = $row['l_welType'] == "+" ? round(($l_wel/$totalWorkingDays)*$netWorkingDaysPresent) : round($netEarningSalary * $l_wel / 100);
    
    $earning = $netDA + $netHRA + $netConn + $netSepcA;
    $deduction = $NetTDS + $netESI + $netPF + $netProfTax + $netLWel;
    $s_id = $_POST['s_id'];
    $grosspay = $netEarningSalary + $earning;
    $earning = $earning + $over_time + $incentive;
    $deduction = $deduction + $break_violation + $late_fine + $e_leave;
    $finalsalary = round($netEarningSalary + $earning - $deduction);
    $date = time();
    $finalsalary = $finalsalary; 
    // echo json_encode(array("overtime"=>$over_time,"insentive"=>$incentive,"per day"=>$perday,"earning" => $earning,"Gross" => $grosspay,"Deduction" => $deduction,"Finalsalary" => $finalsalary,"working_days" => $working_days,"present_days"=>$present_days));
    // exit();
    $sql1 = "UPDATE `staff_salary`
            SET `basic` = '$netbasicSalary',
            `working_days` = '$totalWorkingDays',
            `present_days` = '$netWorkingDaysPresent',
            `absent_days` = '$absent_days',
            `da` = '$netDA',
            `hra` = '$netHRA',
            `conveyance` = '$netConn',
            `allowance` = '$netSepcA',
            `medical_allowance` = '$netSepcA',
            `tds` = '$NetTDS',
            `esi` = '$netESI',
            `pf` = '$netPF',
            `e_leave` = '$e_leave',
            `late_fine` = '$late_fine',
            `break_violation` = '$break_violation',
            `prof_tax` = '$netProfTax',
            `over_time` = '$over_time',
            `incentive` = '$incentive',
            `labour_welfare` = '$netLWel',
            `earning`= '$earning',
            `grosspay`= '$grosspay',
            `deduction`= '$deduction',
            `net_salary`= '$finalsalary',
            `salary_added_date`= '$date'
            WHERE salary_id ='$s_id'";

    if ($conn->query($sql1)) {
        $res = $conn->query("SELECT * FROM staff_salary WHERE salary_id ='$s_id' ");
        $row = $res->fetch_assoc();
        echo json_encode(array("message" => "Salary Update Successfully.", "data" => $row));
    } else {
        echo "Salary Not Update";
    }
}

// Attendance Edit
if($_POST['action'] == 'attendance_edit')
{
    $fine = $_POST['fine'];
    $a_status = $_POST['a_status'];
    $query =  mysqli_query($conn, "update attendance set fine = '$fine', attendance_status = '$a_status' where Attandance_id = ".$_POST['id']." ");
    //echo  "update attendance set fine = '$fine', attendance_status = '$a_status' where Attandance_id = ".$_POST['id']." ";
    $conn->query($query);
    echo "Attendance update Successfully.";
}

// announcement_edit Data
if($_POST['action'] == 'announcement_edit')
{
    $notice = $_POST['notice'];
    $subject = $_POST['subject'];
    $query =  mysqli_query($conn, "update notice set notice = '$notice', notice_subject = '$subject' where n_id = ".$_POST['n_id']." ");
    $conn->query($query);
    echo "Notice update Successfully.";
}

// break Edit Data
if($_POST['action'] == 'break_edit')
{
    $break_time = $_POST['break_time'];
    $fine = $_POST['fine'];
    $comment = $_POST['comment'];
    $b_id = $_POST['b_id'];
    $query =  mysqli_query($conn, "update break set total_time = '$break_time',fine = '$fine',comment = '$comment' where b_id = '$b_id' ");

    $conn->query($query);
    echo "Break Update Successfully.";
}

// Attendance Change
if($_POST['action'] == 'edit_attendance')
{
    $present_type = $_POST['present_type'];
    $id = $_POST['id'];
    if($present_type == 'Absent'){
        $query = mysqli_query($conn, "DELETE FROM attendance where Attandance_id = '$id' ");
    }else {
        $query = mysqli_query($conn, "UPDATE attendance SET attendance_status = '$present_type' WHERE Attandance_id = '$id' ");
    }
    if($query){
        echo "Attendance Update Successfully.";
    }else{
        echo "Attendance Not Updated.";
    }
}

// add remaining leaves
if($_POST['action'] == 'remaining_leave_add')
{
    $time= time();
    $sql = mysqli_query($conn, "UPDATE `total_add_leave` SET `total_leave`= ".$_POST['leave'].",`entry_time`= '$time' WHERE e_id = ".$_POST['r_id']." ");

    if($sql){
        echo 'Leaves Added Successfully';
    }
}

// Change Fine In Break
if($_POST['action'] == 'fine_edit')
{
    $sql = mysqli_query($conn, "upadte  break SET ".$_POST['column']." = ".$_POST['value']." where b_id = ".$_POST['id']."");
    if($sql){
        echo 'success';
    }else{
        echo 'failed';
    }
}

// Change Fine In Break
if($_POST['action'] == 'leave_edit')
{
    $leave_type = mysqli_real_escape_string($conn, $_POST["leave_type"]);
    $leave_id = mysqli_real_escape_string($conn, $_POST["leave_id"]);
    $query = "UPDATE add_leave SET leave_type = '$leave_type' WHERE leave_id = '$leave_id'";
    if (mysqli_query($conn, $query))
    {
        echo "Leave Type Edited";
    }
    else{
        echo "Leave Type Not Edited";
    }
}

// Edit Company Time
if($_POST['action'] == 'edit_company_time')
{
    $start_time= $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $break_time = $_POST['break_time'];
    $break_fine = $_POST['break_fine'];
    $time_id = $_POST['time_id'];
    $timezone = $_POST['timezone'];
    $shift_no = $_POST['shift_no'];
    $latefine = $_POST['late_fine'];
    $added_time = time();
    $query = "UPDATE `company_time` SET `company_in_time`='$start_time',`late_fine` = '$latefine',`break_fine`= '$break_fine',`timezone`='$timezone',`company_out_time`='$end_time',`company_break_time`='$break_time',`entry_time`='$added_time', `shift_no` = '$shift_no' WHERE time_id = '$time_id' ";
    if ($conn->query($query))
    {
        echo "Company Time Edit Successfully";
    }
    else{
        echo "Company Time Not Edit Successfully";
    }
}

if($_POST['action'] == 'updateEmployeePassword')
{
    $eid = $_POST['empId'];
    $new_pass = hash('sha1', $_POST['password']);
    $sql = mysqli_query($conn, "UPDATE employee SET e_password = '$new_pass' WHERE e_id = '$eid' ");
    if ($sql) {
        echo 'true';
    } else {
        echo 'false';
    }
}

// edit accounting_password
if($_POST['action'] == 'accounting_password')
{
    $old_pass = hash('sha1', $_POST['old_pass']);
    $password = hash('sha1', $_POST['new_pass']);
    $admin_id = $_POST['admin_id'];
    $time = time();
    $fetch = mysqli_query($conn, "SELECT * FROM accounting_auth where admin_id = '$admin_id' ");
    while ($row = mysqli_fetch_array($fetch)) {
        $user_password = $row['password'];
    }
    if ($old_pass == $user_password) {

        $update = mysqli_query($conn, "UPDATE accounting_auth SET password = '$password',last_change_password = '$time' where admin_id = '$admin_id' ");
        if ($update) {
            $status = "Password change successfully.";
        } else {
            $status = "Password not change";
        }
    }else {
        $status = "Your old password is invalid.";
    }
    $arr = array(
        "status" => $status
    );
    echo json_encode($arr);
}

// Edit User
if($_POST['action'] == 'edit_user'){

    $user_name = $_POST['user_name'];
    $user_id = $_POST['user_id'];
    $user_email = $_POST['user_email'];
    $user_type = $_POST['user_type'];
    $password = $_POST['user_password'];
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
    $pass_element = '';

    if($password != ''){
        $password = hash('sha1',$password);
        $pass_element = ",user_password = '$password'";
    }
    $sql1 = "update `add_users` set `admin_id` = '$admin_id', `user_name` = '$user_name', `user_email` = '$user_email', `user_type` = '$user_type',`user_add_date` = '$user_date', `last_change_password` = '$time', `entry_time` = '$time', `employee` = '$employee', `payroll` = '$payroll', `attendance` = '$attendance', `break` = '$break', `leave` = '$leave', `letters` = '$letters', `administration` = '$administration' $pass_element where user_id = '$user_id' ";
    if ($conn->query($sql1)) {
        echo "User Edit Successfully.";
    }
    else{
        echo 'Not Edited User';
    }
}

// change admin_password
if($_POST['action'] == 'change_password'){
    $old_pass = hash('sha1',$_POST['old_pass']);
    $new_pass = hash('sha1',$_POST['new_pass']);
    $admin_id = $_POST['admin_id'];
    $sql = mysqli_query($conn, "SELECT * from company_admin where admin_id = $admin_id ");
    while ($row = mysqli_fetch_array($sql)) {
        $pass = $row['admin_password'];
    }
    $time = time();
    if ($old_pass === $pass) {
        $sql1 = mysqli_query($conn,"UPDATE company_admin SET admin_password = '$new_pass',password_change = '$time' where admin_id = $admin_id ");
        if ($sql1) {
            $status = "Password Changed Successfully.";
        }else{
            $status = 'Password not Changed';
        }
    } else {
        $status = 'Your old password is invalid.';
    }
    $arr = array(
        "status" => $status
    );
    echo json_encode($arr);
}

// Company File Delete
if($_POST['action'] == 'company_file_delete'){
    $path = $_POST['path'];
    $query = mysqli_query($conn," select * from company_document where company_document_id = '".$path."' ");
    $row = mysqli_fetch_array($query);
    $image = $row['file_name'];
    $size = $row['file_size'];
    $q2 = mysqli_query($conn," select company_upload_storage from company_admin where admin_id = '".$_POST['admin_id']."' ");
    $q = mysqli_fetch_array($q2);
    $remaing_size = $q['company_upload_storage'];
    $total = $remaing_size + $size;
    mysqli_query($conn," update company_admin set company_upload_storage = '".$total."' where admin_id = '".$_POST['admin_id']."' ");
    unlink("company_document/".$image);
    mysqli_query($conn," delete from company_document where company_document_id = '".$path."' ");
}


// Company Policy Delete
if($_POST['action'] == 'pupdate'){
    $policy = mysqli_real_escape_string($conn, $_POST['policy_name']);
    $description = mysqli_real_escape_string($conn, $_POST['policy_description']);
    $p_id = mysqli_real_escape_string($conn, $_POST['p_id']);
    $sql = "UPDATE `company_policy`
            SET `policy_name` = '$policy',
            `policy_description` = '$description'
            WHERE `policy_id` = $p_id";
    if ($conn->query($sql)) {
        echo "Policies Update Successfully.";
    } else {
        echo "Policies Not Update";
    }
    mysqli_close($conn);

}

// Company Vip User
if($_POST['action'] == 'isVipUser'){
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $sql = "UPDATE `employee`
            SET `isVipUser` = $type
            WHERE `e_id` = $id";
    if ($conn->query($sql)) {
        echo json_encode(array("isVipUser" => $type, "id" => $id));
    } else {
        echo json_encode(array("isVipUser" => $type, "id" => $id));
    }
    mysqli_close($conn);

}

// Employee Update
if($_POST['action'] == 'edit_employee'){
    $f_name = mysqli_real_escape_string($conn, $_POST['f_name']);
    $l_name = mysqli_real_escape_string($conn, $_POST['l_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $join_date = mysqli_real_escape_string($conn, strtotime($_POST['join_date']));
    $ph_no = mysqli_real_escape_string($conn, $_POST['ph_no']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $designation = mysqli_real_escape_string($conn, $_POST['designation']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $shift_no = explode(',',$_POST['shift_no']);
    $emp_cardid = $_POST['e_id'];
//    $sql = $conn->query("SELECT * FROM employee WHERE e_id = $emp_cardid");
//    $row = $sql->fetch_assoc();
//    $previous_shift_no = $row['shift_no'];
//    if($previous_shift_no != $shift_no){
//        $sql = $conn->query("SELECT * FROM company_time WHERE time_id = '$previous_shift_no' ");
//        $row = $sql->fetch_assoc();
//        $previous_count = $row['used_count'];
//        $previous_count = $previous_count - 1;
//        $counter = $shift_no[1] + 1;
//        $sql = $conn->query("UPDATE company_time SET used_count = '$previous_count' WHERE time_id = '$previous_shift_no' ");
//        $sql = $conn->query("UPDATE company_time SET used_count = '$counter' WHERE time_id = '$shift_no[0]' ");
//    }
    $sql1 = "UPDATE employee SET e_firstname = '$f_name',e_lastname = '$l_name',e_email = '$email',join_date = '$join_date',e_phoneno = '$ph_no',e_gender = '$gender',department = '$department',designation = '$designation', e_salary = '$salary', shift_no = '$shift_no[0]' WHERE emp_cardid = '$emp_cardid' ";
    if ($conn->query($sql1))
    {
//        $fetch = mysqli_query("SELECT * FROM employee where emp_cardid = '$emp_cardid' ");
//        $row = mysqli_fetch_array($fetch);
//        $depart_id = $row['departments_id'];
//        $desig_id = $row['designation_id'];
//        if($desig_id != $designation){
//            $fetch = mysqli_query("SELECT * FROM designation where designation_id = '$desig_id' ");
//            $row = mysqli_fetch_array($fetch);
//            $count = $row['used_count'];
//            $count = $count - 1;
//            $d_count = $d_count + 1;
//            mysqli_query($conn,"update designation set used_count = '$d_count' where designation_id = '$designation' ");
//            mysqli_query($conn,"update designation set used_count = '$count' where designation_id = '$desig_id' ");
//        }
//        if($depart_id != $department){
//            $fetch = mysqli_query("SELECT * FROM departments where departments_id = '$depart_id' ");
//            $row = mysqli_fetch_array($fetch);
//            $dcount = $row['used_count'];
//            $dcount = $dcount - 1;
//            $count = $count + 1;
//            mysqli_query($conn,"update department set d_used_count = '$count' where departments_id = '$department' ");
//            mysqli_query($conn,"update department set d_used_count = '$dcount' where departments_id = '$depart_id' ");
//        }
        echo "Employee Edit Successfully.";
    }
    else
    {
        echo 'Edit Failed';
    }
}

if($_POST['action'] == 'edit_joining'){
    $e_id = mysqli_real_escape_string($conn, $_POST['e_id']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $application_date = mysqli_real_escape_string($conn,strtotime($_POST['application_date']));
    $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
    $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
    $joining_id = mysqli_real_escape_string($conn, $_POST['joining_id']);
    $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name']);
    $manager_designation = mysqli_real_escape_string($conn, $_POST['manager_designation']);
    $added_time = time();
    $sql = mysqli_query($conn, "update joining_employee set manager_name = '$manager_name',manager_designation = '$manager_designation',e_id = '$e_id', department_name = '$department', joining_date = '$application_date', emp_id = '$emp_id' where joining_id = '$joining_id' ");
    if($sql){
        echo 'Success';
    }else{
        echo 'fail';
    }
}

if($_POST['action'] == 'editOffice'){
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $officeName = mysqli_real_escape_string($conn, $_POST['officeName']);
    $officeLocation = mysqli_real_escape_string($conn, $_POST['officeLocation']);
    $officeOpenDate = strtotime($_POST['officeOpenDate']);
    $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
    $sql = "update office set officeName = '$officeName',officeLocation = '$officeLocation', officeOpenDate = '$officeOpenDate' where id = '$id' ";
    if ($conn->query($sql)) {
        echo json_encode(array(
            "status" => "success",
            "message" => 'Office edited successfully.'
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => 'error while editing office.',
            'error' => $conn->error
        ));
    }
}


if($_POST['action'] == 'addEmpAllowDedu'){
    $f1 = $_POST['id'];
    $f2 = $_POST['id']."Type";
    $eId = $_POST['eId'];
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $sql = "update employee set {$f1} = '$amount', {$f2} = '$type' WHERE e_id = '$eId' ";
    if ($conn->query($sql)) {
        echo json_encode(array(
            "status" => "success",
            "message" => 'Data edited successfully.'
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => 'error while editing data.',
            'error' => $conn->error
        ));
    }
}

if($_POST['action'] == 'editBasic'){
    $eId = $_POST['eId'];
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $sql = "update employee set e_salary = '$amount' WHERE e_id = '$eId' ";
    if ($conn->query($sql)) {
        echo json_encode(array(
            "status" => "success",
            "message" => 'Data edited successfully.'
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => 'error while editing data.',
            'error' => $conn->error
        ));
    }
}

