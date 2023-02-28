<?php
if(!isset($_SESSION)){
    session_start();    
}
$server = "localhost";
$username = "root";
$password = "";
if(isset($_SESSION['admin_id'])){
    if($_SESSION['admin_id'] == 5){
        $dbname = "payroll";
    }else{
        $dbname = "payroll";
    }
}else{
    $dbname = "payroll";
}
$conn = new mysqli($server, $username , $password , $dbname);

if($conn -> connect_error)
{
//    echo "Connection Fail";
}else{
//    echo "Connection Success";
}
?>



