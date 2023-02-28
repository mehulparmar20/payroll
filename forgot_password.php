<?php
ob_start();
session_start();
if (isset($_SESSION['admin'])) {
    header("Location:admin/index.php");
    exit;
} elseif (isset($_SESSION['employee'])) {
    header("Location:employee/home.php");
    exit;
}
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login | WINDSON PAYROLL | windsonpayroll.com</title>
    <link rel="shortcut icon" href="assets/images/payrollfavicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap"
          rel="stylesheet">
    <link rel="shortcut icon" href="assets/images/payrollfavicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/plugins/slider/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/plugins/slider/css/owl.theme.default.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css"/>
</head>

<body class="form-login-body" id="style-8">
<div class="container">
    <div class="row">
        <div class="col-lg-10 mx-auto login-desk">
            <div class="row border-class">
                <div class="col-md-7 detail-box">
                    <img class="logo" src="assets/images/payroll.png" alt="">
                    <div class="detailsh">
                        <img class="help" src="assets/images/help.png" alt="">
                        <h3>Windson Payroll</h3>
                        <p>The Only Payroll Service That Truly Cares About Your Time</p>
                    </div>
                </div>
                <div class="col-md-5 loginform" id="login-section">
                    <h4>Find your email</h4>
                    <p>Enter your phone number or recovery email</p>
                    <form onsubmit="return false;">
                        <div class="login-det">
                            <div class="form-row">
                                <label for="">Email</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="far fa-user"></i>
                                                    </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Enter email"
                                           aria-label="recoveremail" id="recoveremail" name="recoveremail" aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <p class="forget"><a href="index.php">Login</a></p>
                            <button class="btn btn-sm btn-danger" id="recover-btn" name="submit">Forget Password</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-5 loginform" id="otp-section" style="display: none">
                        <h4>Validate OTP </h4>
                        <p>A One Time Passcode has been sent to <span id="myemail-id"></span>.</p>
                    <form onsubmit="return false;">
                        <div class="login-det">
                            <div class="form-row">
                                <label for="">OTP</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                                 <span class="input-group-text" id="basic-addon1">
                                                        <i class="fas fa-lock"></i>
                                                 </span>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Enter OTP"
                                           aria-label="Username" id="userotp" aria-describedby="basic-addon1">
                                    <input type="hidden" id="user-type">
                                    <input type="hidden" id="user-id">
                                </div>
                            </div>
                                <button class="btn btn-sm btn-danger" id="forgototpSubmit-btn" name="submit">Verify</button>
                        </div>
                    </form>
                </div>
                 <div class="col-md-5 loginform" id="password-section" style="display: none">
                    <h4>Reset Password </h4>
                    <form onsubmit="return false;">
                        <div class="login-det">
                            <div class="form-row">
                                <label for="">New Password</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                                 <span class="input-group-text" id="basic-addon1">
                                                        <i class="fas fa-lock"></i>
                                                 </span>
                                    </div>
                                    <input type="password" class="form-control" placeholder="Enter password"
                                           aria-label="Username" id="newpass" aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <div class="form-row">
                                <label for="">Confirm Password</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                                 <span class="input-group-text" id="basic-addon1">
                                                        <i class="fas fa-lock"></i>
                                                 </span>
                                    </div>
                                    <input type="password" class="form-control" placeholder="Enter password"
                                           aria-label="Username" id="confirmpass" aria-describedby="basic-addon1">
                                </div>
                            </div>
                                <button class="btn btn-sm btn-danger" id="changepass-btn" name="submit">Verify</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/plugins/scroll-fixed/jquery-scrolltofixed-min.js"></script>
<script src="assets/plugins/slider/js/owl.carousel.min.js"></script>
<script src="assets/js/script.js"></script>
</html>
