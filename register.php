<?php
if(isset($_GET['plan'])){
    if($_GET['plan'] == "advance" || $_GET['plan'] == "pro" ){
        ob_start();
        session_start();
        if (isset($_SESSION['admin'])) {
            header("Location:admin/index.php");
            exit();
        } elseif (isset($_SESSION['employee'])) {
            header("Location:employee/index.php");
            exit();
        }
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register | WINDSON PAYROLL | windsonpayroll.com</title>
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
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body class="form-login-body" id="style-8">
<div class="container">
    <div class="row">
        <div class="col-lg-10 mx-auto login-desk">
            <div class="row border-class">
                <div class="col-md-7 detail-box">
                    <img class="logo" src="assets/images/payroll.png" alt="">
                    <div class="detailsh">
                        <img style="width: 543px; margin-top: 21px; margin-bottom: 1px;" src="assets/images/register.png" alt="">
                        <h3>Start your payroll service with us.</h3>
                        <p>Pay Your Employees Without the Headache.</p>
                    </div>
                </div>
                <div class="col-md-5 loginform" id="login-section">
                    <p>Register to Windson Payroll</p>
                    <form onsubmit="return false;">
                        <div class="login-det">
                            <div class="form-row">
                                <label for="">Name</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="far fa-user"></i>
                                                    </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Enter Name"
                                           aria-label="adminname" id="adminname" name="adminname"
                                           aria-describedby="basic-addon1" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <label for="">Company Name</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="far fa-user"></i>
                                                    </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Enter Company Name"
                                           aria-label="comName" id="comName" name="comName"
                                           aria-describedby="basic-addon1" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <label for="">Email</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="far fa-user"></i>
                                                    </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Enter Username"
                                           aria-label="adminemail" id="adminemail" name="useremail"
                                           aria-describedby="basic-addon1" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <label for="">Contact No.</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="far fa-user"></i>
                                                    </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Enter Contact No"
                                           aria-label="adminemail" id="admincontact" name="admincontact"
                                           aria-describedby="basic-addon1" required>
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
                                           aria-label="Username" id="userpass" name="userpass"
                                           aria-describedby="basic-addon1" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <label for="">No of Employees</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="far fa-user"></i>
                                                    </span>
                                    </div>
                                    <input type="number" min="5" value="5" class="form-control"
                                           placeholder="no of user" aria-label="Username" id="noofUser" name="noofUser"
                                           aria-describedby="basic-addon1" required>
                                </div>
                            </div>
                            <p class="forget"><a href="index.php">Already have an account? Login here</a></p>
                            <button class="btn btn-sm btn-danger" id="checkout-button" name="submit">Register</button>
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
<?php
    }else{
        header("Location:https://www.windsonpayroll.com/");
        exit();
    }
}else{
    header("Location:https://www.windsonpayroll.com/");
    exit();
}
