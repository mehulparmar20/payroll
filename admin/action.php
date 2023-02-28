<?php

ob_start();
include '../dbconfig.php';
include 'api/index.php';
include '../stripe/model/Stripeupdate.php';
if($_POST['action'] == 'approve')
{
$sql = "update `leaves` set `leave_status` = 11  where r_leave_id  = ".$_POST['l_id']." ";
$leave = mysqli_query($conn, "Select * from  `leaves` where r_leave_id = ".$_POST['l_id']." ");
$leaves = mysqli_fetch_array($leave);
$e_id = $leaves['e_id'];
$from_date = $leaves['from_date'];
$to_date = $leaves['to_date'];
$total_days = $leaves['number_day'];
 if ($conn->query($sql)) 
{
        $info = mysqli_query($conn, "Select * from  `employee` where e_id = '$e_id' ");
        while($row = mysqli_fetch_array($info))
        {
            $f_name = $row['e_firstname'];
            $l_name = $row['e_lastname'];
            $emp_id = $row['emp_cardid'];
            $email = $row['e_email'];
            $admin_id = $row['admin_id'];
        }
        $sql = mysqli_query($conn, "Select * from  `compnay_time` where admin_id = '$admin_id' ");
        $com = mysqli_fetch_array($sql);
        $timezone = $com['timeszone'];
        date_default_timezone_set($timezone);
            $name = $f_name." ".$l_name;
            include '../mail.php';
            $mail = new Mail();                                 // Set email format to HTML
            $subject = 'Your Leave Request is Approved';
            $string_to_name = '@name@';
            $string_id = '@id@';
            $string_fdate = '@fdate@';
            $string_tdate = '@tdate@';
            $from_date = date("d-m-Y",$from_date);
            $to_date = date("d-m-Y",$to_date);
            // for replace name
            $message = file_get_contents("../email-template/leave_accept.php");
            $message = str_replace ($string_to_name, $name, $message);
            // for replace emp_id
            $message = str_replace ($string_id, $emp_id, $message);
            // for replace from date
            $message = str_replace ($string_fdate, $from_date, $message);
            // for replace to date
            $message = str_replace ($string_tdate, $to_date, $message);
            // for replace name
            if($mail->sentApiMail($email, $name ,$message, $subject)){
                echo 'Leave Approved Successfully.';
            }else{
                echo "Leave Not approved";
            }
        }
        else
        {
            echo "Not Approved";
        }
}
if($_POST['action'] == 'decline')
{
    $sql = "update `leaves` set `leave_status` = 10  where r_leave_id = ".$_POST['l_id']." ";
    $leave = mysqli_query($conn, "Select * from  `leaves` where r_leave_id = ".$_POST['l_id']." ");
    $leaves = mysqli_fetch_array($leave);
    $e_id = $leaves['e_id'];
    $from_date = $leaves['from_date'];
    $to_date = $leaves['to_date'];
    $total_days = $leaves['number_day'];
     if ($conn->query($sql)) 
    {
        $info = mysqli_query($conn, "Select * from  `employee` where e_id = '$e_id' ");
        while($row = mysqli_fetch_array($info))
        {
            $f_name = $row['e_firstname'];
            $l_name = $row['e_lastname'];
            $emp_id = $row['emp_cardid'];
            $email = $row['e_email'];
            $admin_id = $row['admin_id'];
        }
        $name = $f_name." ".$l_name;
        include '../mail.php';
        $mail = new Mail();       
        $subject = 'Your Leave Request is Decline';
        $string_to_name = '@name@';
        $string_id = '@id@';
        $string_fdate = '@fdate@';
        $string_tdate = '@tdate@';
        $from_date = date("d-m-Y",$from_date);
        $to_date = date("d-m-Y",$to_date);
        // for replace name
        $message = file_get_contents("../email-template/leave_declined.php");
        $message = str_replace ($string_to_name, $name, $message);
        // for replace emp_id
        $message = str_replace ($string_id, $emp_id, $message);
        // for replace from date
        $message = str_replace ($string_fdate, $from_date, $message);
        // for replace to date
        $message = str_replace ($string_tdate, $to_date, $message);
        // for sent email
        if($mail->sentApiMail($email, $name ,$message, $subject)){
            echo 'Leave Decline Successfully.';
        }else{
            echo "Leave Not Decline";
        }
   }
   else
   {
       echo "Not Decline";
   }
}

if($_POST['action'] == 'pending')
{
     $sql = "update `leaves` set `leave_status` = 12  where r_leave_id = ".$_POST['l_id']." ";
$leave = mysqli_query($conn, "Select * from  `leaves` where r_leave_id = ".$_POST['l_id']." ");
$leaves = mysqli_fetch_array($leave);
$e_id = $leaves['e_id'];
$from_date = $leaves['from_date'];
$to_date = $leaves['to_date'];
$total_days = $leaves['number_day'];
 if ($conn->query($sql)) 
{
    $info = mysqli_query($conn, "Select * from  `employee` where e_id = '$e_id' ");
       while($row = mysqli_fetch_array($info))
        {
            $f_name = $row['e_firstname'];
            $l_name = $row['e_lastname'];
            $emp_id = $row['emp_cardid'];
            $email = $row['e_email'];
            $admin_id = $row['admin_id'];
        }

    $name = $f_name." ".$l_name;
    include '../mail.php';
    $mail = new Mail();                                  // Set email format to HTML
    $subject = 'Your Leave Request is Pending';
    $string_to_name = '@name@';
    $string_id = '@id@';
    $string_fdate = '@fdate@';
    $string_tdate = '@tdate@';
    $from_date = date("d-m-Y",$from_date);
    $to_date = date("d-m-Y",$to_date);
    // for replace name
    $message = file_get_contents("../email-template/leave_pending.php");
    $message = str_replace ($string_to_name, $name, $message);
    // for replace emp_id
    $message = str_replace ($string_id, $emp_id, $message);
    // for replace from date
    $message = str_replace ($string_fdate, $from_date, $message);
    // for replace to date
    $message = str_replace ($string_tdate, $to_date, $message);
    // for replace file
    if($mail->sentApiMail($email, $name ,$message, $subject)){
        echo 'Leave Pending Successfully.';
    }else{
        echo "Leave Not Pending";
    }

   }
   else
   {
       echo "Not Pending";
   }
}



// delete leave
if($_POST['action'] == 'delete')
{
    $sql = "DELETE FROM `leaves` WHERE `leaves`.`r_leave_id` = ".$_POST['l_id']." ";

    if ($conn->query($sql)) 
   {
       echo 'Decline successfully.';
   }
   else
   {
       echo "Not Decline";
   }
}

//-approve resignation
if($_POST['action'] == 'approve_resign')
{

$sql = "update `resignation` set `request_status` = '11'  where resignation_id = ".$_POST['resignation_id']." ";

 if ($conn->query($sql)) 
{
    echo json_encode(array('status' => 'success', 'message' =>   'Approved successfully.'));
}
else
{
    echo "Not Approved";
}
}

// Decline Resignation
if($_POST['action'] == 'decline_resign')
{
    $sql = "update `resignation` set `request_status` = '10'  where resignation_id = ".$_POST['resignation_id']." ";

    if ($conn->query($sql)) 
   {
       echo json_encode(array('status' => 'success', 'message' =>  'Decline successfully.'));
   }
   else
   {
       echo "Not Decline";
   }
}
// Active User
if($_POST['action'] == 'activate_user')
{
    $sql = "update `add_users` set `user_status` = '0'  where user_id = ".$_POST['user_id']." ";

    if ($conn->query($sql))
   {
       echo json_encode(array('status' => 'success', 'message' =>  'Active user successfully.'));
   }
   else
   {
       echo "Not Decline";
   }
}

// Deactive User
if($_POST['action'] == 'deactivate_user')
{
    $sql = "update `add_users` set `user_status` = '1'  where user_id = ".$_POST['user_id']." ";

    if ($conn->query($sql))
   {
       echo json_encode(array('status' => 'success', 'message' =>  'Deactivate successfully.'));
   }
   else
   {
       echo "Not Decline";
   }
}

// Delete Resign
if($_POST['action'] == 'delete_resign')
{
    $sql = "DELETE FROM `resignation` WHERE `resignation_id` = ".$_POST['resignation_id']." ";

    if ($conn->query($sql)) 
   {
       echo json_encode(array('status' => 'success', 'message' =>  'Delete resign successfully.'));
   }
   else
   {
       echo "Not Decline";
   }
}

// Activate Employee
if($_POST['action'] == 'active')
{
    $api = new Util();
    $empId = $_POST['e_id'];
    if($empId != '') {
        $devIndex = $_SESSION['devIndex'];
        $username = $_SESSION['device_username'];
        $password = $_SESSION['device_password'];
        $res = $api->activateEmployee($devIndex, $username, $password, $empId, $conn);
        echo $res;
    }
}

// Deactivate Employee
if($_POST['action'] == 'deactive')
{
    $api = new Util();
    $empId = $_POST['e_id'];
    $resigndate = strtotime(date($_POST['resigndate']." 12:00:00"));
    if($empId != '') {
        $devIndex = $_SESSION['devIndex'];
        $username = $_SESSION['device_username'];
        $password = $_SESSION['device_password'];
        $res = $api->deleteUser($devIndex, $empId, $username, $password, $conn,$resigndate);
        if($res == 'success'){
            $stripe = new Stripeupdate();
            $planId = 'price_1IYa1ZHpxXHfgq9U6r7w1oul';
            if($_SESSION['admin_id'] == 1){
                // $admin_id = $_SESSION['admin_id'];
                // $res = $api->deccounter($admin_id,$conn);
                echo json_encode(array('status' => 'success','message' => "Employee deactivated successfully"));
                exit();
            }
            $res = $stripe->decSubscriptionqty($planId,$conn);
            if($res){
                $admin_id = $_SESSION['admin_id'];
                $res = $api->deccounter($admin_id,$conn);
                echo json_encode(array('status' => 'success','message' => "Employee deactivated successfully"));
            }else{
                echo json_encode(array('status' => 'error','message' => "Subscription not update" ));
            }
        }

    }
}

// ON Benefits Of Employee
if($_POST['action'] == 'on_benifits')
{
    $sql = "UPDATE `employee` SET `e_benefits`= 1 WHERE e_id = ".$_POST['e_id']." ";
    $s = mysqli_query($conn,"select * from total_add_leave where e_id = ".$_POST['e_id']." ");
    $count = mysqli_num_rows($s);
    if($count == 0){
        $s = mysqli_query($conn,"select * from employee where e_id = ".$_POST['e_id']." ");
        $row = mysqli_fetch_array($s);
        $name = $row['e_firstname']." ".$row['e_lastname'];
        $e_id = $row['e_id'];
        $admin_id = $row['admin_id'];
        $time =time();
        $query = mysqli_query($conn,"INSERT INTO `total_add_leave`(`e_id`, `emp_name`, `entry_time`, `admin_id`) VALUES ('$e_id','$name','$time','$admin_id')");
        if ($conn->query($sql)) 
        {
           echo json_encode(array('status' => 'success', 'message' =>  'Active successfully.'));
        }
        else
        {
           echo "Not Active";
        }
    }else{
        if ($conn->query($sql)) 
        {
           echo json_encode(array('status' => 'success', 'message' => 'Active successfully.'));
        }
        else
        {
           echo "Not Active";
        }
    }
}

// OFF Benefits of employee
if($_POST['action'] == 'off_benifits')
{
    $sql = "UPDATE `employee` SET `e_benefits`= 0 WHERE e_id = ".$_POST['e_id']." ";

    if ($conn->query($sql)) 
   {
      echo json_encode(array('status' => 'success', 'message' => 'Benifits stop successfully.'));
   }
   else
   {
       echo "Not Deactivate";
   }
}

// _show_announcement
if ($_POST['action'] == 'show_announcement')
{
    $n_id= $_POST['n_id'];
    $sql1 =  mysqli_query($conn, "UPDATE notice SET show_status = 1 WHERE n_id = '$n_id' ");
    if($sql1){
        echo json_encode(array('status' => 'success', 'message' => "Announcement Show Successfully."));
    }
    else {
        echo 'Announcement not Show Successfully.';
    }
}

// _hide_announcement
if ($_POST['action'] == 'hide_announcement')
{
    $n_id= $_POST['n_id'];
    $sql1 =  mysqli_query($conn, "UPDATE notice SET show_status = 0 WHERE n_id = '$n_id' ");
    if($sql1){
        echo json_encode(array('status' => 'success', 'message' => 'Announcement hide successfully.'));
    }else{
        echo json_encode(array('status' => 'error', 'message' => '$conn->Error'));
    }
}

// Email Sent
if ($_POST['action'] == 'email_send')
{
    include '../mail.php';
    $to = mysqli_real_escape_string($conn,$_POST['to']);
    $subject = mysqli_real_escape_string($conn,$_POST['subject']);
    $message = mysqli_real_escape_string($conn,$_POST['message']);
    $admin_id = mysqli_real_escape_string($conn,$_POST['admin_id']);

    $sql = mysqli_query($conn," select * from company_admin where admin_id = '$admin_id' ");
    while ($row = mysqli_fetch_array($sql))
    {
        $from = $row['admin_email'];
        $company_name = $row['company_name'];
    }
    $mail = new Mail();
    
    if($mail->sentApiMail($to, $company_name ,$message, $subject))
    {
        echo "success";
    }else{
        echo "fail";
    }
}

