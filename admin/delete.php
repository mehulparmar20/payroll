<?php
ob_start();
include '../dbconfig.php';
include 'api/index.php';
// Delete Holiday
if ($_POST['action'] == 'holiday_delete') {

    $sql = "DELETE FROM `holidays` WHERE `holidays`.`holiday_id` = " . $_POST['h_id'] . "";

    if ($conn->query($sql)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}

// Delete Leave
if ($_POST['action'] == 'leave_delete') {

    $sql = "DELETE FROM `leaves` WHERE `leaves`.`r_leave_id` = " . $_POST['l_id'] . "";

    if ($conn->query($sql)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}

// Delete User
if ($_POST['action'] == 'user_delete') {

    $sql = "DELETE FROM `add_users` WHERE `add_users`.`user_id` = " . $_POST['user_id'] . "";

    if ($conn->query($sql)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}

// Delete Leave
if ($_POST['action'] == 'delete_user') {

    $sql = "DELETE FROM `break_off_user` WHERE `bo_id` = " . $_POST['user_id'] . "";
    echo $sql;
    if ($conn->query($sql)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}

// Delete Employee
if ($_POST['action'] == 'employee_delete') {
    $api = new Util();
    $empId = $_POST['d_id'];
    $devIndex = $_SESSION['devIndex'];
    $username = $_SESSION['device_username'];
    $password = $_SESSION['device_password'];
    $res = $api->deleteUser($devIndex, $empId, $username, $password, $conn);
    echo $res;
}

// Delete employee of Month
if ($_POST['action'] == 'employee_month') {

    $sql = "DELETE FROM employee_performance WHERE `ep_id` = " . $_POST['ep_id'] . "";

    if ($conn->query($sql)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}

// Delete Designation
if ($_POST['action'] == 'designation_delete') {

    $sql = "DELETE FROM `designation` WHERE `designation`.`designation_id` = " . $_POST['d_id'] . "";

    if ($conn->query($sql)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}

// Delete Department
if ($_POST['action'] == 'department_delete') {

    $sql = "DELETE FROM `departments` WHERE `departments`.`departments_id` = " . $_POST['d_id'] . "";

    if ($conn->query($sql)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}
// Delete Department
if ($_POST['action'] == 'designation_delete') {

    $sql = "DELETE FROM `designation` WHERE `designation`.`designation_id` = " . $_POST['d_id'] . "";

    if ($conn->query($sql)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}
// Delete Salary
if ($_POST['action'] == 'delete_salary') {

    $sql = "UPDATE staff_salary SET salary_status = '0' WHERE `staff_salary`.`salary_id` = " . $_POST['s_id'] . "";

    if ($conn->query($sql)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}

// Delete Salary
if ($_POST['action'] == 'announcement_delete') {

    $sql = "DELETE FROM notice WHERE `n_id` = " . $_POST['n_id'] . "";

    if ($conn->query($sql)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}

// Delete Break
if ($_POST['action'] == 'break_delete') {

    $sql = "DELETE FROM `break` WHERE `b_id` = " . $_POST['b_id'] . "";

    if ($conn->query($sql)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}

//For Delete policies
if ($_POST['action'] == 'delete_policies') {
    $id = $_POST['p_id'];
    $policies_statuse = 0;
    $queri = mysqli_query($conn, "update company_policy set policies_statuse = '$policies_statuse' where policy_id = '$id' ");
    if ($conn->query($queri)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}

//For delete Salary
if ($_POST['action'] == 'delete_salary') {
    $id = $_POST['s_id'];
    $salary_statuse = 0;
    $queri = mysqli_query($conn, "update staff_salary set salary_status = $salary_statuse where salary_id = $id");
    if ($conn->query($queri)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}

//For delete attendance
if ($_POST['action'] == 'attendance_delete') {
    $sql = "DELETE FROM attendance WHERE `Attandance_id` = " . $_POST['a_id'] . "";

    if ($conn->query($sql)) {
        echo 'Deleted successfully.';
    } else {
        echo "Not Deleted";
    }
}

//For delete attendance
if ($_POST['action'] == 'leavetype_delete') {
    $query = "delete from add_leave where leave_id = '" . $_POST['leave_id'] . "' ";
    $output = "";
    if (mysqli_query($conn, $query)) {
        return 'success';
    }
}

//For delete attendance
if ($_POST['action'] == 'company_time_delete') {
    $query = "delete from company_time where time_id = '" . $_POST['time_id'] . "' ";
    if (mysqli_query($conn, $query)) {
        return 'success';
    }
}

if($_POST['action'] == 'delete_joining'){
    $sql = mysqli_query($conn, " update joining_employee set `joining_delete` = '1' where joining_id = '" . $_POST['id'] . "' ");
        if ($sql) {
            echo 'Deleted successfully.';
        } else {
            echo "Not Deleted";
        }
}

if($_POST['action'] == 'getADdelete'){
    $table = $_POST['table'];
    $id = $_POST['id'];
    $sql = mysqli_query($conn, " delete from {$table} where id = '$id' ");
        if ($sql) {
            echo json_encode(array(
            "status" => "success",
            "message" => 'Allowance deleted successfully.'
        ));
        } else {
            echo json_encode(array(
            "status" => "info",
            "message" => 'something went wrong.',
            'error' => $conn->error
        ));
        }
}

//For delete attendance
if ($_POST['action'] == 'company_file_delete') {
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
