<?php
    
$server = "localhost";
$username = "root";
$password = "";
$dbname = "nbp_payroll";

$conn = new mysqli($server , $username , $password , $dbname);

if($conn -> connect_error)
{
//    echo "Connection Fail";
}else{
//    echo "Connection Success";
}
?>