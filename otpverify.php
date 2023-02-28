<?php
        session_start();
        if(isset($_GET['user_id'])){
            $id = $_GET['user_id'];
        }elseif(isset($_GET['id'])){
            $id = $_GET['id'];
        }else{
             header("Location:index.php?error=You Must Login First");
        }
       
//        echo $id;
        if(isset($_POST['submit'])){
            include 'dbconfig.php';
            $otp = $_POST['otp'];
            $id = $_POST['id'];
            $user = $_POST['user'];
            if($user == 'admin'){    
              $sql =mysqli_query($conn,"SELECT * FROM company_admin where admin_id = '$id'");
                    while ($row = mysqli_fetch_array($sql)) 
                    {
                       $admin_otp = $row['otp'];
                       $admin_id = $row['admin_id'];
                       $user_id = $row['admin_name'];
                       $expired_time = $row['expired_time'];
                       $deviceId = $row['device_id'];
                       $device_username = $row['device_username'];
                       $device_password = $row['device_password'];
                       $device_type = $row['device_type'];
                    }
                $login_time = time();
                if($expired_time > $login_time) {
                    if ($admin_otp == $otp) {
                        setcookie('admin_id', $admin_id, time() + (86400 * 30), "/"); // 86400 = 1 day
                        $_SESSION['admin'] = 'yes';
                        $_SESSION['admin_id'] = $id;
                        $_SESSION['admin_name'] = $user_id;
                        $_SESSION['devIndex'] = $deviceId;
                        $_SESSION['device_username'] = $device_username;
                        $_SESSION['device_password'] = $device_password;
                        $_SESSION['device_type'] = $device_type;
                        $result = mysqli_query($conn, "update company_admin set last_login = '$login_time' where admin_id = '$id'");
                        header("Location:admin/index.php");
                        exit;
                    } else {
                        header("Location:otpverify.php?id=$id&error=invalid");
                    }
                }else{
                    header("Location:otpverify.php?id=$id&error=expired");
                }
            }else{
                $sql = mysqli_query($conn,"SELECT * FROM add_users where user_id = '$id'");
                        while ($row = mysqli_fetch_array($sql)) 
                        {
                           $user_otp = $row['otp'];
                           $user_name = $row['user_name'];
                           $user_id = $row['user_id'];
                           $admin_id = $row['admin_id'];
                        }

                        $sql =mysqli_query($conn,"SELECT * FROM company_admin where admin_id = '$admin_id'");
                        while ($row = mysqli_fetch_array($sql)){
                            $deviceId = $row['device_id'];
                            $device_username = $row['device_username'];
                            $device_password = $row['device_password'];
                            $device_type = $row['device_type'];
                        }
                if($user_otp == $otp){
                    setcookie('admin_id', $admin_id, time() + (86400 * 30), "/"); // 86400 = 1 day
                    $_SESSION['admin'] = 'yes';
                    $_SESSION['admin_id'] = $admin_id;
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_name'] = $user_name;
                    $_SESSION['devIndex'] = $deviceId;
                    $_SESSION['device_username'] = $device_username;
                    $_SESSION['device_password'] = $device_password;
                    $_SESSION['device_type'] = $device_type;
                    $login_time = time();
                    $result =mysqli_query($conn, "update add_users set last_login = '$login_time' where user_id = '$user_id'");
                    header("Location:admin/index.php");
                    exit();
                }else
                {
                    header("Location:otpverify.php?user_id=$id&error=invalid");
                }
            }    
        }
            
        

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Verify OTP</title>

        
        <!-- BASE CSS -->
            <link href="assets/wizard/css/style.css" rel="stylesheet">
    <!--===============================================================================================-->	
            <link rel="icon" type="image/png" href="assets/wizard/images/icons/favicon.ico"/>
    <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="assets/wizard/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="assets/wizard/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="assets/wizard/fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="assets/wizard/vendor/animate/animate.css">
    <!--===============================================================================================-->	
            <link rel="stylesheet" type="text/css" href="assets/wizard/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="assets/wizard/vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="assets/wizard/vendor/select2/select2.min.css">
    <!--===============================================================================================-->	
            <link rel="stylesheet" type="text/css" href="assets/wizard/vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="assets/wizard/css/util.css">
            <link rel="stylesheet" type="text/css" href="assets/wizard/css/main.css">
    <!--===============================================================================================-->

</head>

<body>

    <div class="animateme">
        <ul class="bg-bubbles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
    <!-- /Animated Canvas -->

   
   

        <main>
            <div class="limiter">
                <div class="container-login100" >
                    <div class="wrap-login100">
                        <form class="login100-form validate-form" action="otpverify.php" method="post">
                            <span class="login100-form-logo">
                                <img src="https://img.icons8.com/cute-clipart/64/000000/lock.png">
                            </span>

                            <span class="login100-form-title p-b-34 p-t-27">
                                Enter OTP
                            </span>      
                                <div class="wrap-input100 validate-input" data-validate = "OTP">
                                    <input id="otp" type="text" name="otp" class="input100" value="<?php if(isset($_GET['otp'])){ echo $_GET['otp']; }?>" placeholder="Enter OTP *" />
                                    <input id="id" type="hidden" name="id" class="input100"   value="<?php if(isset($_GET['user_id']) || isset($_GET['id'])){ echo $id; }?>" style="border: none; border-bottom: 1px solid black"/>
                                    <input id="user" type="hidden" name="user" class="input100"   value="<?php if(isset($_GET['user_id'])){ echo "user";}else{ echo 'admin'; } ?>" style="border: none; border-bottom: 1px solid black"/>
                                    <span class="focus-input100"></span>
                                </div>
                                <div class="container-login100-form-btn">
                                    <button type="submit" name="submit" id="submit" class="login100-form-btn">
                                        Login
                                    </button>
                                </div>
                            <?php if(isset($_GET['error']) && $_GET['error'] == "invalid") { ?>
                                    <div style="padding-top:15px;" >
                                        <center><span style="color:white">Invalid OTP.</span></center>
                                        <center><a href="index.php">Back To Login</a></center>
                                    </div>
                            <?php }else if(isset($_GET['error']) && $_GET['error'] == "expired"){
                                echo '<div style="padding-top:15px;" >
                                            <center><span style="color:white">OTP is Expired.</span></center>
                                            <center><a href="index.php">Back To Login</a></center>
                                      </div>';
                                }?>
                        </form>
                    </div>
                </div>
            </div>
	</main>
	<!-- /main -->
	
	<!-- COMMON SCRIPTS -->
        <script src="assets/js/ajax.js"></script>
<!--===============================================================================================-->
        <script src="assets/wizard/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/wizard/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/wizard/vendor/bootstrap/js/popper.js"></script>
	<script src="assets/wizard/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/wizard/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/wizard/vendor/daterangepicker/moment.min.js"></script>
	<script src="assets/wizard/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="assets/wizard/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="assets/wizard/js/main.js"></script>        
        	
</body>
</html>
    <?php // } ?>