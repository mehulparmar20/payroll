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
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login | WINDSON PAYROLL</title>
    <link rel="shortcut icon" href="assets/img/payrollfavicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css"/>
</head>

<body>
<div class="container-fluid">
    <div class="container">
        <div class="col-xl-10 col-lg-11 mx-auto login-container">
            <div class="row">

                <div class="col-lg-5 col-md-6 no-padding">

                    <div class="login-box">
                        <h5 id="message-section">Welcome Back!</h5>
                        <div id="login-section">
                            <form onsubmit="return false;">
                                <div class="login-row row no-margin">
                                    <select class="form-control" id="usertype">
                                        <option selected value="employee">Login by Employee</option>
                                        <option value="admin">Login by Admin</option>
                                    </select>
                                </div>

                                <div class="login-row row no-margin">
                                    <div id="status"></div>
                                </div>

                                <div class="login-row row no-margin">
                                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                                    <input type="text" id="useremail" name="ueseremail"
                                           class="form-control form-control-sm">
                                </div>

                                <div class="login-row row no-margin">
                                    <label for="userpass"><i class="fas fa-unlock-alt"></i> Password</label>
                                    <input type="password" id="userpass" name="userpass"
                                           class="form-control form-control-sm">
                                </div>

                                <div class="login-row row forrr no-margin">
                                    <p><a class="vgh" href="forgot_password.php">Forget Password?</a></p>
                                </div>

                                <div class="login-row btnroo row no-margin">
                                    <button class="btn btn-primary btn-sm" id="login-btn" name="submit"> Sign In
                                    </button>
                                    <!--                                <button class="btn btn-success  btn-sm"> Create Account</button>-->
                                </div>
                            </form>
                            <div class="login-row donroo row no-margin">
                                <p>Dont have an Account ? <a href="register.php">Sign Up</a></p>
                            </div>
                        </div>
                        <div style="display: none" id="otp-section">
                            <form onsubmit="return false;">
                                <div class="login-row btnroo row no-margin">
                                    <div class="login-row row no-margin">
                                        <input type="number" id="uerotp" placeholder="------" name="uerotp"
                                               class="form-control form-control-sm">
                                        <input type="hidden" id="user-type">
                                        <input type="hidden" id="user-id">
                                    </div>
                                    <button class="btn btn-primary btn-sm" id="otpSubmit-btn" name="submit"> Verify
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="col-lg-7 col-md-6 img-box">
                    <img src="assets/img/sideimg.png" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/script.js"></script>
<script>
    $(document).ready(function () {
        var msg = $("#plan_ex").val();

        if (msg === 'plan_expierd') {
            $("#plan_expierd").modal('show');
        }
    });
</script>
</html>