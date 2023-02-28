<?php
ob_start();
session_start();
if(isset($_GET['token'])){
$token = $_GET['token'];
$id = explode('?',$token);
$_SESSION['id'] = $id[1];
$_SESSION['token'] = $token;
}

if(isset($_POST['submit'])){
    include '../dbconfig.php';
    $password = hash('sha1', $_POST['new_pass']);
    $token = $_POST['id'];
    $id = $_SESSION['id'];
    $token = $_SESSION['token'];
    $time = time();
    $query =mysqli_query($conn, "SELECT * FROM company_admin where admin_id = '$id' ");
        if(mysqli_num_rows($query) > 0){
            $set_pass = mysqli_query($conn, "UPDATE `accounting_auth` SET `password`= '$password',`last_change_password`= '$time',`entry_time`= '$time' WHERE  admin_id = '$id' ");
            if($set_pass){
                header("location:salary.php");
            }
        }else{
            $msg = 'Password Not Changed';
            header("location:auc_password.php?msg=".$msg." ");
        }
}



?>
<!DOCTYPE html>
<html lang="en">
    <head>

        <title>Accounting Reset PAssword</title>

        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="app/img/payrollfavicon.png">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="app/css/bootstrap.min.css">

        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="app/css/font-awesome.min.css">

        <!-- Main CSS -->
        <link rel="stylesheet" href="app/css/style.css">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
                <script src="assets/js/html5shiv.min.js"></script>
                <script src="assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="account-page">

        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <div class="account-content">
                <!--<a href="job-list.php" class="btn btn-primary apply-btn">Apply Job</a>-->
                <div class="container">

                    <!-- Account Logo -->
                    <div class="account-logo">
                        <a href="index.php"><img src="app/img/payroll.png" alt="Windson Payroll"></a>
                    </div>
                    <!-- /Account Logo -->

                    <div class="account-box">
                        <div class="account-wrapper">
                            <p class="account-subtitle">Create New Password</p>
                            <!-- Account Form -->
                            <form method="post" action="auc_password.php">
                                <div class="form-group">
                                    <input type="password" placeholder="New Password" name="new_pass" id="new_pass" minlength="8" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="password" onchange="check_pass()" placeholder="Reenter New Password" name="c_pass" id="c_pass" minlength="8" class="form-control">
                                    <input type="hidden" value="<?php echo $token; ?>" name="id" id="id"  class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" onclick="myFunction()" class="checked"> &nbsp;Show Password
                                </div>
                                <?php if(isset($_GET['msg'])){ ?>
                                <div class="form-group">
                                    <label style="color: red"><?php echo $_GET['msg']; ?> </label>
                                </div>
                                <?php } ?>
                                <div class="form-group text-center">
                                    <button class="btn btn-primary account-btn" name="submit" id="submit" type="submit">Change</button>
                                </div>
                                <div class="account-footer">
                                    <p>Not yet received? <a href="javascript:void(0);">Resend OTP</a></p>
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
        <script src="assets/js/jquery-3.2.1.min.js"></script>
        <script>
            function myFunction() {
                var pass1 = document.getElementById("new_pass");
                var pass2 = document.getElementById("c_pass");
                if (pass1.type == "password") {
                    pass1.type = "text";
                    pass2.type = "text";
                } else {
                    pass1.type = "password";
                    pass2.type = "password";
                }
            }
            function check_pass() {
                var pass1 = document.getElementById("new_pass");
                var pass2 = document.getElementById("c_pass");
                if (pass1 != pass2 ) {
                    swal({
                        title: "Oppps!",
                        text: "Password Does Not Match",
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                        confirmButtonColor: "#00ff55",
                        reverseButtons: true,
                    });
                }
            }
        </script>
        <script src="app/js/sweet_alert.js"></script>
        <!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>

        <!-- Custom JS -->
        <script src="assets/js/app.js"></script>

    </body>
</html>