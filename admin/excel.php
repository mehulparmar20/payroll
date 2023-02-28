<?php
ob_start();
session_start();
include '../dbconfig.php';
    $sql1 = mysqli_query($conn, "SELECT * FROM total_add_leave  WHERE  admin_id = 1  ");
    $arra =  array();
    while ($row = mysqli_fetch_array($sql1)) {
         $name = $row['emp_name'];
         $leave = $row['total_leave'];
         $arra[] = array("Name" => $name, "Total Leave" => $leave);
     }

    echo json_encode($arra);


?>