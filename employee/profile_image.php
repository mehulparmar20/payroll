<?php
session_start();
include '../dbconfig.php';
if ($_SESSION['employee'] == 'yes') {
    $e_id = $_SESSION['e_id'];
    $admin_id = $_SESSION['admin_id'];

    if ($_FILES["file"]["name"] != '') {
        $test = explode('.', $_FILES["file"]["name"]);
        $ext = end($test);
        $name = md5($_FILES["file"]["name"]) . '.' . $ext;
        $location = './employee_profile/' . $name;
        move_uploaded_file($_FILES["file"]["tmp_name"], $location);
        $sql = "update employee set employee_profile = '$name' where e_id = '$e_id' AND admin_id = '$admin_id' ";
        if ($conn->query($sql)) {
            echo 'Profile Change Successfully';
        }

    }

}