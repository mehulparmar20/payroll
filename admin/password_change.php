<?php 
include '../dbconfig.php';

    if(isset($_POST['submit'])){
        $admin_email =$_POST['email'];
        if($admin_email == ''){
            header("location:password_change.php?msg=Enter Your Valid E-mail");
        }
        $query =mysqli_query($conn, "SELECT * FROM company_admin where admin_email = '$admin_email' ");
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_array($query);
            $id = $row['admin_id'];
            $name = $row['admin_name'];
//            $token = bin2hex(random_bytes(50));
            $length = 50;
            $token = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
            $time = time();
            $sql1 = "UPDATE company_admin SET `acc_otp` = '$token',`acc_otp_time` = '$time' where admin_id = '$id' ";
//            echo $token;

                if (mysqli_query($conn, $sql1)){
                    $to = $admin_email;
                    $token = $token.'?'.$id;
//                    echo $name;
                    $subject = "Password Reset Link";

                    $string_to_replace = '@name@';
                    $string_token = '@token@';
                    // for replace name
                    $message = file_get_contents("../email-template/password-change.php");
                    $content_chunks=explode($string_to_replace, $message);
                    $content=implode($name, $content_chunks);
                    file_put_contents('../email-template/password-change.php', $content);
                    // for replace Token
                    $message = file_get_contents("../email-template/password-change.php");
                    $content_chunks=explode($string_token, $message);
                    $content=implode($token, $content_chunks);
                    file_put_contents('../email-template/password-change.php', $content);
                    $message = file_get_contents("../email-template/password-change.php");
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    mail($to,$subject,$message,$headers);
                    // for replace name
                    $message = file_get_contents("../email-template/password-change.php");
                    $content_chunks=explode($name, $message);
                    $content=implode($string_to_replace, $content_chunks);
                    file_put_contents('../email-template/password-change.php', $content);
                    // for replace Token
                    $message = file_get_contents("../email-template/password-change.php");
                    $content_chunks=explode($token, $message);
                    $content=implode($string_token, $content_chunks);
                    file_put_contents('../email-template/password-change.php', $content);
                    header("location:../info.php?token=$token");
                    exit();
                }
        }else{
            header("location:password_change.php?msg=Email does not Exist");
        }
            
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Forgot Password</title>
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="app/img/payrollfavicon.png">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="app/css/bootstrap.min.css">
        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="app/css/font-awesome.min.css">
        <!-- Main CSS -->
        <link rel="stylesheet" href="app/css/style.css">
    </head>
    <body class="account-page">
        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <div class="account-content">
                <!--<a href="job-list.html" class="btn btn-primary apply-btn">Apply Job</a>-->
                <div class="container">
                    <!-- Account Logo -->
                    <div class="account-logo">
                        <a href="index.php"><img src="app/img/payroll.png" height="60px" width="300px" alt="Windson Payroll"></a>
                    </div>
                    <!-- /Account Logo -->
                    <div class="account-box">
                        <div class="account-wrapper">
                            <h3 class="account-title">Forgot Password?</h3>
                            <p class="account-subtitle">Enter your email to get a password reset link</p>
                            <!-- Account Form -->
                            <form method="POST" action="password_change.php">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input class="form-control" name="email" type="email">
                                </div>
                                 <?php if(isset($_GET['msg'])) { ?>
                                    <div class="form-group text-center">
                                        <span class="text-danger"><?php echo $_GET['msg'];  ?></span>
                                    </div>
                                    <?php } ?>
                                <div class="form-group text-center">
                                    <input class="btn btn-primary account-btn" id="submit" name="submit" value="Reset Password" type="submit">
                                </div>
                                <div class="account-footer">
                                    <p>Remember your password? <a href="salary.php"><sapn class="text-blue">Back to Home</sapn></a></p>
                                </div>
                            </form>
                            <!-- /Account Form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Main Wrapper -->

        <!-- jQuery -->
        <script src="app/js/jquery-3.2.1.min.js"></script>
        <script></script>

        <!-- Bootstrap Core JS -->
        <script src="app/js/popper.min.js"></script>
        <script src="app/js/bootstrap.min.js"></script>

        <!-- Custom JS -->
        <script src="app/js/app.js"></script>

    </body>
</html>