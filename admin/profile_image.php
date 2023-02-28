<?php
session_start();
include '../dbconfig.php';
if ($_SESSION['admin'] == 'yes') {
$e_id = $_POST['id'];
$admin_id = $_SESSION['admin_id'];

if($_FILES["file"]["name"] != '')
{
 $test = explode('.', $_FILES["file"]["name"]);
 $ext = end($test);
 $name = md5($_FILES["file"]["name"]) . '.' . $ext;
 $location = '../employee/employee_profile/'.$name;  
 move_uploaded_file($_FILES["file"]["tmp_name"], $location);
 mysqli_query($conn,"update employee set employee_profile = '$name' where e_id = '$e_id' AND admin_id = '$admin_id' ");
 echo 'Profile Change Successfully';
}
    
}