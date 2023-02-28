<?php
session_start();

if(isset($_SESSION['admin_id'])){
    $admin = $_SESSION['admin_id'];
    unset($_SESSION['admin_id']);
    $_SESSION['admin_id'] = $admin;
    // print_r($_SESSION);
//    echo "session refreshed";
}
else{
    header("Location:../login.php");
    exit();
}