<?php

require_once 'controller/registerUser.php';
require_once  './master/utils/Helper.php';
class RegisterMapper
{
    public function regiterUser($db,$data){
        $helper = new Helper();
        $obj = new RegisterUser();
        return $obj->createSubscription($db,$data,$helper);
    }
    public function getPrice($data){
//        $helper = new Helper();
        $obj = new RegisterUser();
        return $obj->get_Price($data);
    }
    public function new_user($db,$data){
        $helper = new Helper();
        $obj = new RegisterUser();
        return $obj->add_User($db,$data,$helper);
    }

    public function email_Exists($db,$data){
        $obj = new RegisterUser();
        return $obj->check_email($db,$data);
    }

    public function loginRequest($db,$data){
        $obj = new RegisterUser();
        return $obj->login_Request($db,$data);
    }

    public function verifyotp($db,$data){
        $obj = new RegisterUser();
        return $obj->verify_otp($db,$data);
    }
	
	public function verification($db,$data){
        $obj = new RegisterUser();
        return $obj->Verification($db,$data);
    }
    public function verifyForgot($db,$data){
        $obj = new RegisterUser();
        return $obj->verifyForgot($db,$data);
    }

    public function changePass($db, $data){
        $obj = new RegisterUser();
        return $obj->changePass($db,$data);
    }

    public function forgotPassword($db,$data){
        $obj = new RegisterUser();
        return $obj->forgotPassword($db,$data);
    }
    public function emailVerify($db, $data) {
        $obj = new RegisterUser();
        return $obj->emailVerify($db,$data);
    }
    public function changeEmailStatus($db, $data) {
        $obj = new RegisterUser();
        return $obj->changeEmailStatus($db,$data);
    }

    public function checkEmail($db, $data) {
        $obj = new RegisterUser();
        return $obj->checkEmail($db,$data);
    }
}