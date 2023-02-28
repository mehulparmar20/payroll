<?php
include '../dbconfig.php';
session_start();
if ($_SESSION['employee'] == 'yes') {
    
$sql = mysqli_query($conn,"select * from employee_document where e_id = '".$_SESSION['e_id']."' AND admin_id = '".$_SESSION['admin_id']."' ");
while ($row = mysqli_fetch_array($sql))
{
    $output = '
            ';
    
    
}

}
