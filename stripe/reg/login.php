<?php 
session_start();
if(isset($_SESSION['authtoken'])){
    header("location:../../Dashboard.php");
}else{
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Windson Dispatch: A Complete Trucking Business Solutions.</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/img/favicon.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="css/fontawesome-all.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="font/flaticon.css">
    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="loading" style="display: none;">
        <div class="uil-ring-css" style="transform:scale(0.79);">
            <div></div>
        </div>
    </div>
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <style type="text/css">
    .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        left: 50%;
    }

    .extra::before {
        content: none;
    }

    .btn:focus {
        outline: none;
        box-shadow: none;
    }

    input[type="text"] {
        text-align: center;
        font-size: 27px;
    }
    </style>
    <section class="fxt-template-animation fxt-template-layout4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-12 fxt-bg-wrap">
                    <div class="fxt-bg-img" data-bg-image="../assets/img/login.jpg">
                        <div class="fxt-header">

                            <div class="fxt-transformY-50 fxt-transition-delay-2">
                                <h1>Welcome To Windson Dispatch</h1>
                            </div>
                            <div class="fxt-transformY-50 fxt-transition-delay-3">
                                <p>Windson Dispatch System assists a wide array of logistics and trucking companies
                                    across the North america. </p>
                                <a href="../index.php" class="custome_btn"><i
                                        class="fa fa-home"></i>&nbsp;&nbsp;HOME</a>
                            </div>
                        </div>
                        <ul class="fxt-socials">
                            <li class="fxt-facebook fxt-transformY-50 fxt-transition-delay-4"><a href="#"
                                    title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                            <li class="fxt-twitter fxt-transformY-50 fxt-transition-delay-5"><a href="#"
                                    title="twitter"><i class="fab fa-twitter"></i></a></li>
                            <li class="fxt-linkedin fxt-transformY-50 fxt-transition-delay-7"><a href="#"
                                    title="linkedin"><i class="fab fa-instagram"></i></a></li>
                            <li class="fxt-youtube fxt-transformY-50 fxt-transition-delay-8"><a href="#"
                                    title="youtube"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-12 fxt-bg-color">
                    <div class="fxt-content">
                        <a href="../index.php" class="fxt-logo"><img src="../assets/img/logo.png" alt="Logo"></a>
                        <form onsubmit="companyLogin();return false">
                            <div class="fxt-form">
                                <div class="form-group">
                                    <label for="companyEmail" class="input-label">Email Address</label>
                                    <input type="email" id="companyEmail" class="form-control" name="email"
                                        placeholder="demo@Windsondispatch.com">
                                </div>
                                <div class="form-group">
                                    <label for="companyPassword" class="input-label">Password</label>
                                    <input id="companyPassword" type="password" class="form-control" name="password"
                                        placeholder="********">
                                    <i toggle="#companyPassword" class="fa fa-fw fa-eye toggle-password field-icon"></i>
                                </div>
                                <div class="form-group">
                                    <div class="fxt-checkbox-area">
                                        <a href="forgot-password.php" class="switcher-text">Forgot Password</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="fxt-btn-fill">Log in</button>
                                </div>
                            </div>
                        </form>
                        <div class="fxt-footer">
                            <p>Don't have an account?<a href="register.php" class="switcher-text">Register</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="otp" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static"
        aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle" style="color: #3E4095; font-weight:bold">
                        OTP Verification</h5>
                    <button type="button" onclick="closealertfy()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img class="center" src="../assets/img/logo.png" alt="Logo" width="150" height="100">
                    <br>
                    <img class="center" src="../assets/img/otp.svg" alt="Logo">
                    <br>
                    <form onsubmit="otpverify();return false">
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <input type="text" maxlength="6" placeholder="000000" id="otpdata"
                                    style="border: 0; outline: 0; background: transparent; border-bottom: 2px solid #0269ac;"
                                    class="form-control" name="otpdata">
                                <input type="hidden" id="id">
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <br>
                        <button type="submit" id="verify" name="verify" onclick="otpverify()"
                            class="btn custome_btn center btn-verify" style="">Verify</button>
                    </form>


                </div>
                <div class="modal-footer">
                    <section class="fxt-template-animation fxt-template-layout4 extra" style="min-height: 4vh;">
                        <!-- <ul class="fxt-socials">
                            <li class="fxt-facebook fxt-transformY-50 fxt-transition-delay-4"><a href="#"
                            title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                            <li class="fxt-twitter fxt-transformY-50 fxt-transition-delay-5"><a href="#" title="twitter"><i
                            class="fab fa-twitter"></i></a></li>
                            <li class="fxt-linkedin fxt-transformY-50 fxt-transition-delay-7"><a href="#"
                            title="linkedin"><i class="fab fa-instagram"></i></a></li>
                            <li class="fxt-youtube fxt-transformY-50 fxt-transition-delay-8"><a href="#" title="youtube"><i class="fab fa-youtube"></i></a></li>
                        </ul> -->
                        <!-- </div> -->
                    </section>
                </div>
            </div>
        </div>
    </div>
    <script src="../../assets/plugins/alertify/js/alertify.js"></script>
    <script src="../../assets/pages/alertify-init.js"></script>
    <!-- jquery-->
    <script src="js/jquery-3.5.0.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Popper js -->
    <script src="js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Imagesloaded js -->
    <script src="js/imagesloaded.pkgd.min.js"></script>
    <!-- Validator js -->
    <script src="js/validator.min.js"></script>
    <!-- Custom Js -->
    <script src="js/main.js"></script>
    <script src="register/js/script.js"></script>
</body>

</html>
<?php
}
?>