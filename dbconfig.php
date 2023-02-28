<?php
if(!isset($_SESSION)){
    session_start();    
}
$server = "localhost";
$username = "windsonpayroll";
$password = "Windson@123";
if(isset($_SESSION['admin_id'])){
    if($_SESSION['admin_id'] == 5){
        $dbname = "testpayroll";
    }else{
        $dbname = "windsonpayrollsite";
    }
}else{
    $dbname = "windsonpayrollsite";
}
$conn = new mysqli($server, $username , $password , $dbname);

if($conn -> connect_error)
{
//    echo "Connection Fail";
}else{
//    echo "Connection Success";
}
?>



