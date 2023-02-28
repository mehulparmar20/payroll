<?php 
session_start(); 
include 'dbconfig.php';

$result = $conn->query("SELECT * FROM employee where admin_id = '1' and employee_status = 1 and delete_status = 0 ");
$user = [];
while($row = $result->fetch_assoc()){
    $name = $row['e_firstname']." ".$row['e_lastname'];
    $joiningdate = $row['join_date'];
    $startdate = substr(date('c', $joiningdate), 0, 19);
    $time = time();
    $empId = (string)$row['emp_cardid'];
    array_push($user,array("employeeNo" => $empId,
        "name" =>  $name,
        "userType" => "normal",
        "Valid" => array(
            "enable"=> true,
            "beginTime"=> (string)$startdate,
            "endTime" => "2035-08-01T17:30:08",
            "timeType" => "local"
        )
    ));   
}
$arrdata = array ("UserInfo" => $user);
if($_GET['data'] == 'json'){
    echo json_encode($arrdata);
}else{
    print_r($user);
}
