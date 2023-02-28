<?php
use Stripe\StripeClient;

require './Windson-Dispatch/reg/register/model/Register.php';

//    $stripe = new StripeClient($auth['secrate_key_test']);

class RegisterUser{

    public function get_Price($data){
        // get products price
        require_once('./frontsite/register/config/stripe_key.php');
        $stripe = new StripeClient($auth['secrate_key_test']);
        $price = $stripe->prices->retrieve($data['plan_id']);
        echo json_encode($price['unit_amount'] / 100);
    }

    
    public function createSubscription($db,$data,$helper) {
        // get products price
        $register = new Register();
        $register->setID($helper->getNextSequence("companyAdmin",$db));
        $register->setFirstname($data['fname']);
        $register->setLastname($data['lname']);
        $register->setCompanyname($data['company_name']);
        $register->setPhoneNo($data['phone_no']);
        $register->setEmail($data['email']);
        $register->setPassword($data['password']);
        $register->setPlanId($data['plan_id']);
        $register->setStripeID($data['customer_id']);
        $register->createUser($db,$register,$helper);
    }
    public function add_User($db,$data,$helper){
        // get products price
        $register = new Register();
        $register->setID($data['id']);
        $register->setFirstname($data['fname']);
        $register->setLastname($data['lname']);
        $register->setCompanyname($data['company_name']);
        $register->setPhoneNo($data['phone_no']);
        $register->setEmail($data['email']);
        $register->setBusinesstype($data['business_type']);
        $register->setUsername($data['username']);
        $register->setPassword($data['password']);
        $register->setCountry($data['country']);
        $register->setState($data['state']);
        $register->setTown($data['town']);
        $register->setAddress($data['address']);
        $register->setZip($data['zip']);
        $register->setMcnumber($data['mc_num']);
        $register->setFfnumber($data['ff_num']);
        $plan = explode(",",$data['plan_id']);
        $register->setPlanId($plan);
        $register->setTotalUsers($data['total_user']);
        $register->registerUser($db,$register,$helper);
    }
    public function check_email($db,$data){
        $register = new Register();
        $register->setEmail($data['email']);
        $response = $register->emailExist($db,$register);
        return $response;
    }

    public function login_Request($db,$data){
        $register = new Register();
        $register->setEmail($data['companyEmail']);
        $register->setPassword($data['companyPassword']);
        $response = $register->loginRequest($db,$register);
        return $response;
    }

    public function verify_otp($db,$data){
        $register = new Register();
        $register->setId($data['id']);
        $register->setOtp($data['otp']);
        $response = $register->verifyOtp($db,$register);
        return $response;
    }
	
	public function Verification($db,$data){
		$register = new Register();
		$arr = explode("^",$data);
		$register->setToken($arr[0]);
		$register->setId($arr[1]);
		$register->setEmail($arr[2]);
		$response = $register->VerifyEmail($db,$register);
		return $response;
    }

    public function forgotPassword($db,$data)
    {
        $register = new Register();
        $register->setEmail($data['email']);
        $response = $register->forgot($db,$register);
    }

    public function verifyForgot($db,$data){
        $register = new Register();
        $register->setId($data['id']);
        $register->setOtp($data['otp']);
        $response = $register->verifyForgot($db,$register);
        return $response;
    }

    public function changePass($db,$data){
        $register = new Register();
        $register->setId($data['id']);
        $register->setNewpass($data['newpass']);
        $response = $register->changePass($db,$register);
        return $response;
    }
    public function emailVerify($db, $data) {
        $register = new Register();
        $register->setEmail($data['email']);
        $register->setPassword($data['token']);
        $register->setId($data['id']);
        $register->setCompanyname($data['name']);
        $register->setDocid($data['_id']);
        $register->setUsertype($data['user_type']);
        $response = $register->emailVerify($db,$register);
        return $response;
    }
    public function changeEmailStatus($db, $data) { 
        $register = new Register();
        $register->setId($data['companyid']);
        $register->setDocid($data['docid']);
        $response = $register->changeEmailStatus($db,$register);
        return $response;
    }

    public function checkEmail($db, $data) {
        $verEmail  =$data['verifyEmail'];
        $checkEmail = $db->user->findOne(['userEmail' => $verEmail]);
                
        if($checkEmail != '') {
            echo "HasEmail";
        } else {
            echo "NewEmail";
        }
    }
}