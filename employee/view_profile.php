<?php
session_start();
include '../dbconfig.php';
if ($_SESSION['employee'] == 'yes') {
    
    $sql = mysqli_query($conn,"select * from employee where e_id = '".$_SESSION['e_id']."' AND admin_id = '".$_SESSION['admin_id']."' ");
    $no = 0;
    while ($row = mysqli_fetch_array($sql))
    {
        $no++;
        $output = '<img src="employee_profile/'.$row["employee_profile"].'" height="300px" width="300px"/>';
    }
    if($no == 0){
        $output = '<img src="app/img/user.jpg" height="300px" width="300px"/>';
    }
    echo $output;
}

