<?php
session_start();

if(isset($_POST['request']) && $_POST['module']){
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once 'register/Register.php';
    require_once 'stripe/model/Stripe.php';
    include_once 'dbconfig.php';
    $api = new Register();
    $stripe = new Stripe();
    $request = $_POST['request'];
    if($request == "register"){
        $module =$_POST['module'];
//        print_r($_POST);
        if(isset($_POST['data'])){
            $data = $_POST['data'];
        }
        if($module == "login"){
            $res = $api->authenticuser($data,$conn);
            echo json_encode($res);
        }elseif ($module == 'verifyOtp'){
            $otp = $data['otp'];
            $id = $data['id'];
            $type = $data['type'];
            if($type == 'admin'){
                echo json_encode($api->verfyAdminOTP($otp,$id,$conn));
            }else{
                echo json_encode($api->verfyUserOTP($otp,$id,$conn));
            }
        }elseif ($module == 'create-checkout'){
            echo $stripe->createCheckout($conn,$data);
        }elseif ($module == 'forgotpassword'){
            $api = new Register();
            $email = $_POST['email'];
            $res = $api->forgotpassword($email,$conn);
            echo json_encode($res);
        }elseif ($module == 'verifyforgotrequest'){
            $api = new Register();
            $otp = $data['otp'];
            $id = $data['id'];
            $type = $data['type'];
            if($type == 'admin'){
                $res = $api->recoveradminRequest($otp,$id,$conn);
            }elseif($type == 'user'){
                $res = $api->recoveruserRequest($otp,$id,$conn);
            }elseif($type == 'employee'){
                $res = $api->recoveremployeeRequest($otp,$id,$conn);
            }
            echo json_encode($res);
        }elseif($module == 'changepass'){
            $api = new Register();
            $newpass = $data['newpass'];
            $encrypassword = hash('sha1',$newpass); 
            $id = $data['id'];
            $type = $data['type'];
            if($type == 'admin'){
                $res = $api->updatepassword($encrypassword,$id,$conn,$type);
            }elseif($type == 'user'){
                $res = $api->updatepassword($encrypassword,$id,$conn,$type);
            }elseif($type == 'employee'){
                $res = $api->updatepassword($encrypassword,$id,$conn,$type);
            }
            echo json_encode($res);
        }
    }

}


