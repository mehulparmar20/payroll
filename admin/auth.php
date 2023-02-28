<?php 
include '../dbconfig.php';
if (isset($_GET['token'])) {

    $token = $_GET['token'];
    $id = explode('?',$token);
    $query = mysqli_query($conn, "SELECT * FROM company_admin where admin_id = '$id[1]' ");

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_array($query);
        $admin_otp = $row['acc_otp'] .'?'. $id[1];
        $admin_time = $row['acc_otp_time'] + 900;
        $time = time();
        echo $token."<br>";
        echo $admin_otp;
        $admin_time == $time;
        if ($time < $admin_time) {
            if ($token == $admin_otp) {
                header("location:auc_password.php?token=".$token."");
            } else {
                echo 'invalid token';
            }
        }
    } else {
        header("location:index.php");
    }
} else {
    header("location:index.php");
}


