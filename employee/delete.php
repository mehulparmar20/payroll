<?php
include '../dbconfig.php';

// Delete Holiday
if($_POST['action'] == 'leave_delete' )
{

$sql = mysqli_query($conn, "DELETE FROM `leaves` WHERE `r_leave_id` = ".$_POST['l_id']."");

 if ($sql) 
{
    echo 'Deleted successfully.';
}
else
{
    echo "Not Deleted";
}
}

//// Delete Leave
//if($_POST['action'] == 'leave_delete' )
//{
//
//$sql = "DELETE FROM `leaves` WHERE `leaves`.`r_leave_id` = ".$_POST['l_id']."";
//
// if ($conn->query($sql)) 
//{
//    echo 'Deleted successfully.';
//}
//else
//{
//    echo "Not Deleted";
//}
//}
//
//// Delete Employee
//if($_POST['action'] == 'employee_delete' )
//{
//
//$sql = "UPDATE employee SET delete_status = '1' WHERE `employee`.`e_id` = ".$_POST['d_id']."";
//
// if ($conn->query($sql)) 
//{
//    echo 'Deleted successfully.';
//}
//else
//{
//    echo "Not Deleted";
//}
//}
//// Delete Designation
//if($_POST['action'] == 'designation_delete' )
//{
//
//$sql = "DELETE FROM `designation` WHERE `designation`.`designation_id` = ".$_POST['d_id']."";
//
// if ($conn->query($sql)) 
//{
//    echo 'Deleted successfully.';
//}
//else
//{
//    echo "Not Deleted";
//}
//}
//
//// Delete Department
//if($_POST['action'] == 'department_delete' )
//{
//
//$sql = "DELETE FROM `departments` WHERE `departments`.`departments_id` = ".$_POST['d_id']."";
//
// if ($conn->query($sql)) 
//{
//    echo 'Deleted successfully.';
//}
//else
//{
//    echo "Not Deleted";
//}
//}
//// Delete Department
//if($_POST['action'] == 'designation_delete' )
//{
//
//$sql = "DELETE FROM `designation` WHERE `designation`.`designation_id` = ".$_POST['d_id']."";
//
// if ($conn->query($sql)) 
//{
//    echo 'Deleted successfully.';
//}
//else
//{
//    echo "Not Deleted";
//}
//}
//// Delete Salary
//if($_POST['action'] == 'delete_salary')
//{
//
//$sql = "UPDATE staff_salary SET salary_status = '0' WHERE `staff_salary`.`salary_id` = ".$_POST['s_id']."";
//
// if ($conn->query($sql)) 
//{
//    echo 'Deleted successfully.';
//}
//else
//{
//    echo "Not Deleted";
//}
//}