<?php
session_start();
include '../dbconfig.php';
if ($_SESSION['admin'] == 'yes') {
    
    $sql = mysqli_query($conn,"select employee_profile from employee where e_id = '".$_POST['id']."' AND admin_id = '".$_SESSION['admin_id']."' ");
    while ($row = mysqli_fetch_array($sql))
    {
        $output = '<img src="../employee/employee_profile/'.$row["employee_profile"].'" height="300px" width="300px"/>';
    }
    echo $output;
}

