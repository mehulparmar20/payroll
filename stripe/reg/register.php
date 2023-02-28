<?php 
    if(isset($_GET['plan'])){
        if($_GET['plan'] == "carrier" || $_GET['plan'] == "trucker" || $_GET['plan'] == "broker"){ ?>

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
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="font/flaticon.css">
    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .email_class_err{
        border-bottom: 1px solid red !important;
    }
    .email_class_suc{
        border-bottom: 1px solid green !important;
    }
    .email_error{
        color: red !important;
        font-weight: bold;
        font-family: sans-serif;
    }
    .email_succ{
        color: green !important;
        font-weight: bold;
        font-family: sans-serif;
    }
</style>
<body>
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div class="loading" style="display: none;">
        <div class="uil-ring-css" style="transform:scale(0.79);">
            <div></div>
        </div>
    </div>
    <div id="wrapper" class="wrapper">
        <div class="fxt-template-animation fxt-template-layout4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-12 fxt-bg-wrap">
                        <div class="fxt-bg-img" data-bg-image="../assets/img/truck2.jpg">
                            <div class="fxt-header">
                                <div class="fxt-transformY-50 fxt-transition-delay-2">
                                    <h1>Welcome To Windson Dispatch</h1>
                                </div>
                                <div class="fxt-transformY-50 fxt-transition-delay-3">
                                    <p>Windson Dispatch serves up many features, custom loads and time saving tools that
                                        keeps data updated and safe for Carriers, Brokers and Shippers </p>
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
                            <div class="fxt-form">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="f_name" class="input-label">First Name</label>
                                        <input type="text" id="f_name" class="form-control" name="f_name" placeholder="First Name" required="required">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="l_name" class="input-label">Last Name</label>
                                        <input type="text" id="l_name" class="form-control" name="l_name" placeholder="Last Name" required="required">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="c_name" class="input-label">Company Name</label>
                                        <input type="text" id="c_name" class="form-control" name="c_name"
                                            placeholder="Company Name" required="required">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="l_name" class="input-label">Phone</label>
                                        <input type="phone" id="phone"  class="form-control" name="phone" placeholder="Phone"
                                            required="required">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="email" class="input-label" id="invemail">Email Address</label> <span id="check_msg"></span>
                                        <input type="email" id="email" class="form-control" name="email" onkeyup="checkEmail(this.value)"
                                            placeholder="Email Address" required="required">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="password" class="input-label">Password</label>
                                        <input id="password" type="password" class="form-control" name="password"
                                            placeholder="********" required="required">
                                        <i toggle="#password" class="fa fa-fw fa-eye toggle-password field-icon"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="fxt-checkbox-area">
                                        <div class="checkbox">
                                            <input id="agree" type="checkbox">
                                            <label for="agree">I agree with the </label><a href="#"
                                                class="switcher-text" data-toggle="modal"
                                                data-target="#terms_condition">terms and condition</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="button" id="free_trial"
                                        class="fxt-btn-fill">Start Free Trial Now</button>
                                </div>
                            </div>
                            <div class="text-center">
                                <p>already a user?<a href="login.php" class="switcher-text">Log in</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="terms_condition" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#3E4095">
                    <h5 class="modal-title" id="exampleModalScrollableTitle" style="color: #FFFFFF; font-weight:bold">
                        Terms and condition</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Policy
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- jquery-->
    <script src="js/jquery-3.5.0.min.js"></script>
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
    <!-- Script Js -->
    <script src="register/js/script.js"></script>
    <script src="register/js/get_data.js"></script>
    <script src="register/js/valid_register.js"></script>
</body>

</html>

        <?php
        }else{
            header('Location: ../plans.php');
            exit();  
        }
    }else{
        header('Location: ../plans.php');
        exit();  
    }
?>
