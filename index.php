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
<style>
    .containers {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default radio button */
.containers input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom radio button */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.containers:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.containers input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.containers input:checked ~ .checkmark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.containers .checkmark:after {
 	top: 9px;
	left: 9px;
	width: 8px;
	height: 8px;
	border-radius: 50%;
	background: white;
}
</style>
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
                        <p>The only payroll service that truly cares about your time.</p>
                    </div>
                </div>
                <div class="col-md-5 loginform" id="login-section">
                    <h4>Welcome Back</h4>
                    <p>Signin to your Account</p>
                    <p>Login by</p>
                     <form onsubmit="return false;">
                        <div class="login-det">
                            <div class="form-row">
                                
                                
                                <!--<select class="form-control" id="usertype">-->
                                <!--    <option selected value="employee">Login by </option>-->
                                <!--    <option value="admin">Login by Admin</option>-->
                                <!--</select>-->
                                <div class="form-check form-check-inline">
<label class="containers">Employess
  <input type="radio" checked="checked" value="employee" id="setemployee" name="usertype">
  <span class="checkmark"></span>
</label>
</div>

<div class="form-check form-check-inline">
<label class="containers">Admin
  <input type="radio"  name="usertype" id="setadmin" value="admin">
  <span class="checkmark"></span>
</label>
</div>

                            </div>
                            <div class="form-row">
                                <label for="">Username</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="far fa-user"></i>
                                                    </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Enter Username"
                                           aria-label="useremail" id="useremail" name="useremail" aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <div class="form-row">
                                <label for="">Password</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="fas fa-lock"></i>
                                                    </span>
                                    </div>
                                    <input type="password" class="form-control" placeholder="Enter Password"
                                           aria-label="Username" id="userpass" name="userpass" aria-describedby="basic-addon1">
                                </div>
                            </div>

                            <p class="forget"><a href="forgot_password.php">Forget Password?</a></p>

                            <button class="btn btn-sm btn-danger" id="login-btn" name="submit">Login</button>
                            <p class="sign-up"><a href="register.php?plan=advance">Don't have an account? Sign-up</a></p>
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
                                <!--<p class="forget"> <a href="javascript:void(0)">Resent OTP</a></p>-->
                                <button class="btn btn-sm btn-danger" id="otpSubmit-btn" name="submit">Verify</button>
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